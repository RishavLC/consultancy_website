<?php
/**
 * functions.php — small reusable helpers, Core PHP only.
 */

function current_page(): string {
    return basename($_SERVER['PHP_SELF']);
}

function nav_class(string $page): string {
    return current_page() === $page ? 'nav-link active' : 'nav-link';
}

/**
 * Renders a small inline SVG icon by name so the whole UI ships with
 * zero external icon-font/image requests.
 */
function icon(string $name, string $class = 'icon'): void {
    $icons = [
        'blueprint' => '<path d="M4 4h24v24H4z"/><path d="M4 12h24M12 4v24" stroke-width="1.5"/><circle cx="20" cy="18" r="3" fill="currentColor" stroke="none"/>',
        'hardhat'   => '<path d="M6 22c0-7 5-13 12-13s12 6 12 13" /><rect x="4" y="22" width="28" height="4" rx="1"/><path d="M18 9V5" />',
        'strata'    => '<path d="M4 8h24M4 16h24M4 24h24" stroke-width="2.5"/><path d="M4 8l6 4-6 4M28 16l-6 4 6 4" stroke-width="1.5"/>',
        'road'      => '<path d="M12 4L4 32h6l3-11h6l3 11h6L20 4z"/><path d="M18 14v3M17 21v3" stroke-width="2"/>',
        'pin'       => '<path d="M18 4c-6 0-11 5-11 11 0 8 11 17 11 17s11-9 11-17c0-6-5-11-11-11z"/><circle cx="18" cy="15" r="4" fill="currentColor" stroke="none"/>',
        'phone'     => '<path d="M8 6c0 12 10 22 22 22l3-6-8-3-2 3c-4-2-8-6-10-10l3-2-3-8z"/>',
        'mail'      => '<rect x="4" y="8" width="28" height="20" rx="2"/><path d="M4 10l14 10L32 10"/>',
        'clock'     => '<circle cx="18" cy="18" r="14"/><path d="M18 10v8l6 4"/>',
        'check'     => '<path d="M6 19l8 8L30 9"/>',
        'arrow'     => '<path d="M6 18h24M20 8l10 10-10 10"/>',
        'facebook'  => '<path d="M22 12h-4V9c0-1.1.9-2 2-2h2V2h-4a6 6 0 0 0-6 6v4H8v5h4v13h6V17h4z" fill="currentColor" stroke="none"/>',
        'instagram' => '<rect x="4" y="4" width="28" height="28" rx="8"/><circle cx="18" cy="18" r="7"/><circle cx="27" cy="9" r="1.5" fill="currentColor" stroke="none"/>',
        'linkedin'  => '<rect x="4" y="4" width="28" height="28" rx="3"/><circle cx="12" cy="12" r="1.8" fill="#fff" stroke="none"/><path d="M12 16v12M18 28v-7c0-3 2-5 4.5-5s4.5 1.5 4.5 5v7" stroke="#fff"/>',
    ];
    echo '<svg class="' . htmlspecialchars($class) . '" viewBox="0 0 36 36" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . ($icons[$name] ?? '') . '</svg>';
}

function e(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

/**
 * Resolves an uploaded image filename to a real URL. Falls back to a
 * placeholder image (seeded so it stays consistent) until someone
 * uploads a real photo through /admin — so the site never shows a
 * broken image icon.
 */
function image_url(?string $filename, string $fallbackSeed, string $size = '600/440'): string {
    if (!empty($filename)) {
        $path = __DIR__ . '/../assets/images/uploads/' . $filename;
        if (is_file($path)) {
            return 'assets/images/uploads/' . rawurlencode($filename);
        }
    }
    return 'https://picsum.photos/seed/' . rawurlencode($fallbackSeed) . '/' . $size;
}

/* ------------------------------------------------------------------
 * Security helpers (contact form protection, rate limiting, mail)
 * ------------------------------------------------------------------ */

/** Real client IP as seen by PHP. (Proxy headers are deliberately NOT trusted — they are spoofable.) */
function client_ip(): string {
    return preg_replace('/[^0-9a-fA-F:.]/', '', $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0') ?: '0.0.0.0';
}

/** Secret used to sign form tokens. Generated once, stored in data/ (web-blocked). */
function app_secret(): string {
    static $secret = null;
    if ($secret !== null) return $secret;
    $file = __DIR__ . '/../data/app_secret.key';
    if (is_file($file) && strlen($s = trim((string)file_get_contents($file))) >= 32) return $secret = $s;
    $secret = bin2hex(random_bytes(32));
    @file_put_contents($file, $secret, LOCK_EX);
    @chmod($file, 0600);
    return $secret;
}

/** Signed, time-stamped token embedded in the contact form (works without sessions). */
function form_token(): string {
    $t = (string)time();
    return $t . '.' . hash_hmac('sha256', 'contact|' . $t, app_secret());
}

/** Returns true if the token is genuine, at least $minAge seconds old, and under $maxAge. */
function form_token_ok(string $token, int $minAge = 3, int $maxAge = 7200): bool {
    $parts = explode('.', $token, 2);
    if (count($parts) !== 2 || !ctype_digit($parts[0])) return false;
    if (!hash_equals(hash_hmac('sha256', 'contact|' . $parts[0], app_secret()), $parts[1])) return false;
    $age = time() - (int)$parts[0];
    return $age >= $minAge && $age <= $maxAge;
}

/**
 * Small file-based rate limiter. Returns true if the action is ALLOWED and records the hit;
 * false once $max hits happened inside the last $window seconds for this bucket+key.
 */
function rate_limit_hit(string $bucket, string $key, int $max, int $window): bool {
    $file = __DIR__ . '/../data/rate_' . preg_replace('/[^a-z0-9_]/i', '', $bucket) . '.json';
    $fh = @fopen($file, 'c+');
    if (!$fh) return true; // never block real users because of a disk problem
    flock($fh, LOCK_EX);
    $data = json_decode((string)stream_get_contents($fh), true);
    if (!is_array($data)) $data = [];
    $now = time(); $k = hash('sha256', $key);
    foreach ($data as $dk => $hits) {                       // purge old entries
        $data[$dk] = array_values(array_filter($hits, fn($t) => $t > $now - $window));
        if (!$data[$dk]) unset($data[$dk]);
    }
    $hits = $data[$k] ?? [];
    $allowed = count($hits) < $max;
    if ($allowed) { $hits[] = $now; $data[$k] = $hits; }
    ftruncate($fh, 0); rewind($fh); fwrite($fh, json_encode($data));
    flock($fh, LOCK_UN); fclose($fh);
    return $allowed;
}

/** Read-only check (does not record a hit). */
function rate_limit_blocked(string $bucket, string $key, int $max, int $window): bool {
    $file = __DIR__ . '/../data/rate_' . preg_replace('/[^a-z0-9_]/i', '', $bucket) . '.json';
    $data = is_file($file) ? json_decode((string)file_get_contents($file), true) : [];
    $hits = is_array($data) ? ($data[hash('sha256', $key)] ?? []) : [];
    $now = time();
    return count(array_filter($hits, fn($t) => $t > $now - $window)) >= $max;
}
function rate_limit_clear(string $bucket, string $key): void {
    $file = __DIR__ . '/../data/rate_' . preg_replace('/[^a-z0-9_]/i', '', $bucket) . '.json';
    if (!is_file($file)) return;
    $data = json_decode((string)file_get_contents($file), true);
    if (is_array($data)) { unset($data[hash('sha256', $key)]); @file_put_contents($file, json_encode($data), LOCK_EX); }
}

/** Strips CR/LF so user input can never inject extra mail headers. */
function mail_safe(string $s): string { return trim(preg_replace('/[\r\n]+/', ' ', $s)); }

/**
 * Emails a new enquiry to the company address. Uses PHP's mail() (works on most shared hosting).
 * Failure is logged but never shown to the visitor — the enquiry is already saved in the database.
 */
function send_enquiry_mail(array $site, array $d): bool {
    $to = mail_safe($site['email'] ?? '');
    if ($to === '' || !filter_var($to, FILTER_VALIDATE_EMAIL)) return false;
    $host = preg_replace('/^www\./', '', preg_replace('/:\d+$/', '', $_SERVER['HTTP_HOST'] ?? 'localhost'));
    $from = defined('MAIL_FROM') ? MAIL_FROM : 'no-reply@' . $host;
    $subject = '=?UTF-8?B?' . base64_encode('New website enquiry: ' . mail_safe($d['service'])) . '?=';
    $body = "A new enquiry was submitted on " . ($site['name'] ?? 'your website') . ":\r\n\r\n"
          . "Name:    " . mail_safe($d['name']) . "\r\n"
          . "Email:   " . mail_safe($d['email']) . "\r\n"
          . "Phone:   " . mail_safe($d['phone']) . "\r\n"
          . "Service: " . mail_safe($d['service']) . "\r\n\r\n"
          . "Message:\r\n" . $d['message'] . "\r\n\r\n"
          . "Manage enquiries: " . base_url() . "/admin/enquiries.php\r\n";
    $headers = 'From: ' . mail_safe($site['shortName'] ?? 'Website') . ' <' . mail_safe($from) . ">\r\n"
             . 'Reply-To: ' . mail_safe($d['email']) . "\r\n"
             . "MIME-Version: 1.0\r\nContent-Type: text/plain; charset=UTF-8\r\nX-Mailer: PHP\r\n";
    try { $ok = @mail($to, $subject, $body, $headers); }
    catch (Throwable $e) { log_error($e, 'mail'); return false; }
    if (!$ok) error_log('[consultancy] mail() returned false for enquiry notification');
    return $ok;
}


/** "March 2024" when a completion date exists, otherwise just the year. */
function project_date_label(array $p): string {
    if (!empty($p['completed_date']) && ($t = strtotime($p['completed_date']))) return date('F Y', $t);
    return (string)($p['year'] ?? '');
}
/** Full date for the single-project page, e.g. "12 March 2024"; empty when no exact date is stored. */
function project_full_date(array $p): string {
    return (!empty($p['completed_date']) && ($t = strtotime($p['completed_date']))) ? date('j F Y', $t) : '';
}
