<?php
/**
 * Shared, safe image-upload helpers used by every admin page that accepts photos.
 * Files are saved to assets/images/uploads/ with a random name; the original filename is never used.
 */
const UPLOAD_MAX_BYTES = 5 * 1024 * 1024;

function upload_dir(): string {
    $dir = __DIR__ . '/../assets/images/uploads/';
    if (!is_dir($dir)) { @mkdir($dir, 0755, true); }
    return $dir;
}

/**
 * Validates and stores ONE uploaded image ($entry is a single $_FILES-style array).
 * Returns the new filename, or throws RuntimeException with a friendly message.
 */
function save_uploaded_image(array $entry): string {
    $name = isset($entry['name']) ? basename((string)$entry['name']) : 'photo';
    if (($entry['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        $msg = [
            UPLOAD_ERR_INI_SIZE => 'is larger than the server allows',
            UPLOAD_ERR_FORM_SIZE => 'is too large',
            UPLOAD_ERR_PARTIAL => 'was only partly uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'could not be saved (server has no temp folder)',
            UPLOAD_ERR_CANT_WRITE => 'could not be written to disk',
        ][$entry['error'] ?? 0] ?? 'failed to upload';
        throw new RuntimeException("\"$name\" $msg.");
    }
    if (!is_uploaded_file($entry['tmp_name'])) throw new RuntimeException("\"$name\" was not a valid upload.");
    if ($entry['size'] > UPLOAD_MAX_BYTES) throw new RuntimeException("\"$name\" is too large — please use photos under 5MB.");

    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $entry['tmp_name']);
    finfo_close($finfo);
    if (!isset($allowed[$mime]) || @getimagesize($entry['tmp_name']) === false) {
        throw new RuntimeException("\"$name\" is not a JPG, PNG, WEBP or GIF photo.");
    }
    $filename = uniqid('img_', true) . '.' . $allowed[$mime];
    if (!move_uploaded_file($entry['tmp_name'], upload_dir() . $filename)) {
        throw new RuntimeException('Could not save the photo. Check that the assets/images/uploads folder is writable.');
    }
    return $filename;
}

/** Deletes a previously uploaded image file (safe against path tricks; ignores missing files). */
function delete_uploaded_image(?string $filename): void {
    if (!$filename || preg_match('#^https?://#i', $filename)) return;   // links are not files on this server
    $path = upload_dir() . basename($filename);
    if (is_file($path)) { @unlink($path); }
}

/** Turns PHP's awkward multi-file $_FILES array into a simple list of single-file entries. */
function normalize_multi_files(string $field): array {
    if (empty($_FILES[$field]) || !is_array($_FILES[$field]['name'])) return [];
    $out = [];
    foreach ($_FILES[$field]['name'] as $i => $n) {
        if (($_FILES[$field]['error'][$i] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) continue;
        $out[] = ['name' => $n, 'type' => $_FILES[$field]['type'][$i], 'tmp_name' => $_FILES[$field]['tmp_name'][$i],
                  'error' => $_FILES[$field]['error'][$i], 'size' => $_FILES[$field]['size'][$i]];
    }
    return $out;
}

/** True when the browser sent more data than PHP's post_max_size (PHP then silently drops $_POST and $_FILES). */
function post_too_big(): bool {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SERVER['CONTENT_LENGTH'])) return false;
    $max = trim((string)ini_get('post_max_size')); $n = (int)$max;
    switch (strtolower(substr($max, -1))) { case 'g': $n *= 1024; case 'm': $n *= 1024; case 'k': $n *= 1024; }
    return $n > 0 && (int)$_SERVER['CONTENT_LENGTH'] > $n;
}


/* ------------------------------------------------------------------
 * Image links (use a picture from the web) and "download a copy"
 * ------------------------------------------------------------------ */

/** Largest photo the admin can really upload: our 5MB cap, or lower if the server's PHP limits are lower. */
function max_upload_bytes(): int {
    $to = function (string $v): int { $n = (int)$v; switch (strtolower(substr(trim($v), -1))) { case 'g': $n *= 1024; case 'm': $n *= 1024; case 'k': $n *= 1024; } return $n; };
    $limits = [UPLOAD_MAX_BYTES];
    $u = $to((string)ini_get('upload_max_filesize')); if ($u > 0) $limits[] = $u;
    $p = $to((string)ini_get('post_max_size'));       if ($p > 0) $limits[] = $p;
    return min($limits);
}
function human_bytes(int $b): string { return $b >= 1048576 ? round($b / 1048576, 1) . ' MB' : max(1, round($b / 1024)) . ' KB'; }

/** Validates a pasted image link. Returns the cleaned link or throws RuntimeException. */
function normalize_image_link(string $url): string {
    $url = trim($url);
    if ($url === '') throw new RuntimeException('Please paste an image link.');
    if (strlen($url) > 500) throw new RuntimeException('That image link is too long (maximum 500 characters).');
    if (!preg_match('#^https?://#i', $url)) throw new RuntimeException('The image link must start with http:// or https://');
    if (preg_match('/[\s<>"\\\\]/', $url) || preg_match('/[\x00-\x1f]/', $url)) throw new RuntimeException('That image link contains characters that are not allowed. Please copy the address again.');
    $parts = parse_url($url);
    if (!$parts || empty($parts['host']) || isset($parts['user']) || isset($parts['pass'])) throw new RuntimeException('That does not look like a valid image link.');
    // Make the link safe to place inside CSS url('...') and HTML attributes.
    return str_replace(["'", '(', ')'], ['%27', '%28', '%29'], $url);
}

/**
 * Downloads an image from a link and stores it like a normal upload. Safe against "server-side request forgery":
 * only http/https on ports 80/443, the host must resolve to a PUBLIC address (never localhost / private networks),
 * redirects are re-checked, size is capped, and the file must really be a JPG/PNG/WEBP/GIF.
 */
function fetch_remote_image(string $url): string {
    $url = normalize_image_link($url);
    if (!function_exists('curl_init')) {
        throw new RuntimeException('This server cannot download images (PHP cURL is switched off). Untick "Download a copy" to use the link directly, or upload the file instead.');
    }
    $max = UPLOAD_MAX_BYTES;
    for ($hop = 0; $hop < 4; $hop++) {
        $p = parse_url($url);
        $scheme = strtolower($p['scheme'] ?? ''); $host = $p['host'] ?? '';
        $port = $p['port'] ?? ($scheme === 'https' ? 443 : 80);
        if (!in_array($scheme, ['http', 'https'], true) || !in_array((int)$port, [80, 443], true)) {
            throw new RuntimeException('Only normal web links (http/https on the standard ports) can be downloaded.');
        }
        $ips = filter_var($host, FILTER_VALIDATE_IP) ? [$host] : (gethostbynamel($host) ?: []);
        if (!$ips) throw new RuntimeException('Could not find that website. Please check the link.');
        foreach ($ips as $ip) {
            if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) || strpos($ip, ':') !== false) {
                throw new RuntimeException('That link points to a private or internal address, so it cannot be downloaded.');
            }
        }
        $buf = ''; $tooBig = false; $location = '';
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_CONNECTTIMEOUT => 6, CURLOPT_TIMEOUT => 20,
            CURLOPT_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS,
            CURLOPT_RESOLVE => [$host . ':' . $port . ':' . $ips[0]],   // connect to the address we just checked
            CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; SiteImageFetcher/1.0)',
            CURLOPT_HTTPHEADER => ['Accept: image/*'],
            CURLOPT_HEADERFUNCTION => function ($c, $h) use (&$location) {
                if (stripos($h, 'location:') === 0) $location = trim(substr($h, 9));
                return strlen($h);
            },
            CURLOPT_WRITEFUNCTION => function ($c, $chunk) use (&$buf, &$tooBig, $max) {
                $buf .= $chunk;
                if (strlen($buf) > $max) { $tooBig = true; return 0; }   // abort the download
                return strlen($chunk);
            },
        ]);
        curl_exec($ch);
        $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);
        if ($tooBig) throw new RuntimeException('That image is larger than 5MB. Please use a smaller one.');
        if (in_array($code, [301, 302, 303, 307, 308], true) && $location !== '') {
            if (!preg_match('#^https?://#i', $location)) {   // relative redirect
                $location = $scheme . '://' . $host . ($location[0] === '/' ? '' : '/') . $location;
            }
            $url = normalize_image_link($location);
            continue;
        }
        if ($code !== 200 || $buf === '') {
            throw new RuntimeException($code ? "The other website answered with an error ($code). Please check the link." : 'Could not download from that link' . ($err ? " ($err)" : '') . '.');
        }
        break;
    }
    if (!isset($buf) || $buf === '' || $hop >= 4) throw new RuntimeException('That link redirects too many times. Please use the direct image address.');

    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE); $mime = finfo_buffer($finfo, $buf); finfo_close($finfo);
    $tmp = tempnam(sys_get_temp_dir(), 'dl');
    file_put_contents($tmp, $buf);
    $okImg = isset($allowed[$mime]) && @getimagesize($tmp) !== false;
    if (!$okImg) { @unlink($tmp); throw new RuntimeException('That link is not a direct JPG, PNG, WEBP or GIF image. Open the picture on its website, right-click it and choose "Copy image address".'); }
    $filename = uniqid('img_', true) . '.' . $allowed[$mime];
    $dest = upload_dir() . $filename;
    if (!@rename($tmp, $dest) && !@copy($tmp, $dest)) { @unlink($tmp); throw new RuntimeException('Could not save the photo. Check that the assets/images/uploads folder is writable.'); }
    @unlink($tmp);
    return $filename;
}

/** What to put in an <img src> inside /admin for a stored value (file name or link). Null = nothing to show. */
function admin_image_src(?string $value): ?string {
    if (!$value) return null;
    if (preg_match('#^https?://#i', $value)) return $value;
    return is_file(upload_dir() . basename($value)) ? '../assets/images/uploads/' . rawurlencode(basename($value)) : null;
}

/**
 * Reads ONE image form field built by render_image_field() and returns the value to store:
 *   keep   -> the existing value        upload -> new file name
 *   link   -> the pasted link (or, with "download a copy", a new file name)       remove -> ''
 * Old uploaded files that get replaced/removed are deleted. Throws RuntimeException with a friendly message.
 */
function resolve_image_input(?string $existing, string $field = 'image'): ?string {
    $mode = (string)($_POST[$field . '_mode'] ?? 'keep');
    $hasFile = !empty($_FILES[$field]) && ($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE;
    $url = trim((string)($_POST[$field . '_url'] ?? ''));
    if ($mode === 'keep' && $hasFile) $mode = 'upload';          // a file was chosen: treat as upload

    $new = $existing;
    if ($mode === 'upload') {
        if (!$hasFile) throw new RuntimeException('You chose "Upload from computer" but did not pick a photo.');
        $new = save_uploaded_image($_FILES[$field]);
    } elseif ($mode === 'link') {
        if ($url === '') throw new RuntimeException('You chose "Use image link" but did not paste a link.');
        $new = !empty($_POST[$field . '_save_copy']) ? fetch_remote_image($url) : normalize_image_link($url);
    } elseif ($mode === 'remove') {
        $new = '';
    }
    if ($new !== $existing && $existing && !preg_match('#^https?://#i', $existing)) delete_uploaded_image($existing);
    return $new;
}

/**
 * Prints the standard image field: live preview + choose between uploading a file or pasting an image link.
 * Pair it with resolve_image_input() on the server and assets/js/admin-image.js in the page.
 */
function render_image_field(string $label, ?string $current, string $field = 'image', bool $allowRemove = true): void {
    $src = admin_image_src($current);
    $hasCurrent = $src !== null;
    $isLink = $current && preg_match('#^https?://#i', $current);
    $max = max_upload_bytes();
    $h = fn($v) => htmlspecialchars((string)$v, ENT_QUOTES);
    ?>
<div class="imgfield" data-imgfield data-max="<?php echo $max; ?>" data-current="<?php echo $h($src); ?>">
    <label><?php echo $h($label); ?></label>
    <div class="imgfield-preview">
        <img class="imgfield-img" alt="Image preview" <?php echo $hasCurrent ? 'src="' . $h($src) . '"' : 'hidden'; ?>>
        <div class="imgfield-empty" <?php echo $hasCurrent ? 'hidden' : ''; ?>>No image yet</div>
    </div>
    <div class="imgfield-note"><?php echo $hasCurrent ? ($isLink ? 'Current image (from a link).' : 'Current image.') : ''; ?></div>
    <div class="imgfield-modes">
        <?php if ($hasCurrent): ?><label><input type="radio" name="<?php echo $field; ?>_mode" value="keep" checked> Keep current</label><?php endif; ?>
        <label><input type="radio" name="<?php echo $field; ?>_mode" value="upload" <?php echo $hasCurrent ? '' : 'checked'; ?>> Upload from computer</label>
        <label><input type="radio" name="<?php echo $field; ?>_mode" value="link"> Use an image link</label>
        <?php if ($hasCurrent && $allowRemove): ?><label><input type="radio" name="<?php echo $field; ?>_mode" value="remove"> Remove image</label><?php endif; ?>
    </div>
    <div class="imgfield-pane" data-pane="upload" <?php echo $hasCurrent ? 'hidden' : ''; ?>>
        <input type="file" name="<?php echo $field; ?>" accept="image/jpeg,image/png,image/webp,image/gif">
        <div class="hint">JPG, PNG, WEBP or GIF. Maximum <?php echo human_bytes($max); ?> on this server.</div>
    </div>
    <div class="imgfield-pane" data-pane="link" hidden>
        <input type="url" name="<?php echo $field; ?>_url" placeholder="https://example.com/photo.jpg" maxlength="500" autocomplete="off">
        <label class="imgfield-check"><input type="checkbox" name="<?php echo $field; ?>_save_copy" value="1"> Also download a copy to this website's server</label>
        <div class="hint">Without the tick, the picture is shown straight from the link (nothing is downloaded, but it disappears if the other website removes it). With the tick, a copy is saved here and always keeps working.</div>
    </div>
</div>
<?php
}
