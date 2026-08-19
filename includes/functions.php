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
