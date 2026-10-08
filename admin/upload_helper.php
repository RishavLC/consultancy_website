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
    if (!$filename) return;
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
