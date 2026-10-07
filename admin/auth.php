<?php
/**
 * admin/auth.php
 *
 * Session handling is configured explicitly here rather than relying on
 * the server's php.ini defaults. On many local XAMPP/WAMP installs the
 * default session save path is missing, unwritable, or shared between
 * multiple projects on localhost — which is the most common reason an
 * admin panel "logs you out" right after logging in. Pointing the
 * session at a folder inside this project, and giving it its own
 * cookie name, removes that dependency entirely.
 */
if (session_status() !== PHP_SESSION_ACTIVE) {
    $sessionDir = __DIR__ . '/../data/sessions';
    if (!is_dir($sessionDir)) {
        @mkdir($sessionDir, 0755, true);
    }
    if (is_dir($sessionDir) && is_writable($sessionDir)) {
        session_save_path($sessionDir);
    }
    // A unique session/cookie name avoids collisions with any other
    // local project also using the default PHPSESSID on localhost.
    session_name('strata_beam_admin_sess');
    session_set_cookie_params([
        'lifetime' => 60 * 60 * 8, // stay logged in for 8 hours of inactivity
        'path'     => '/',
        'domain'   => '',
        'secure'   => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

// Security headers for every admin page.
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: same-origin');
header('Cache-Control: no-store');

const ADMIN_IDLE_TIMEOUT = 60 * 60 * 8; // log out after 8 hours without activity

function admin_logged_in(): bool {
    if (empty($_SESSION['admin_id'])) return false;
    if (time() - (int)($_SESSION['last_active'] ?? 0) > ADMIN_IDLE_TIMEOUT) { admin_logout(); return false; }
    $_SESSION['last_active'] = time();
    return true;
}
function require_admin(): void { if (!admin_logged_in()) { header('Location: login.php'); exit; } }

/** Fully ends the session, including the browser cookie. */
function admin_logout(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', ['expires' => time() - 42000, 'path' => $p['path'], 'domain' => $p['domain'], 'secure' => $p['secure'], 'httponly' => true, 'samesite' => 'Lax']);
    }
    if (session_status() === PHP_SESSION_ACTIVE) session_destroy();
}

/** Login throttle: 5 failed attempts per IP (and 10 per username) in 15 minutes locks sign-in. */
function login_locked(string $username): bool {
    return rate_limit_blocked('login_ip', client_ip(), 5, 900) || rate_limit_blocked('login_user', strtolower($username), 10, 900);
}
function login_failed(string $username): void {
    rate_limit_hit('login_ip', client_ip(), 1000, 900);
    rate_limit_hit('login_user', strtolower($username), 1000, 900);
}
function login_succeeded(string $username): void {
    rate_limit_clear('login_ip', client_ip());
    rate_limit_clear('login_user', strtolower($username));
}

/** Simple per-session CSRF token for state-changing admin forms/links. */
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
function csrf_check(): bool {
    // Tokens are accepted from POST bodies only (never from URLs, where they leak into logs/history).
    $token = $_POST['csrf_token'] ?? null;
    return $token !== null && isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
