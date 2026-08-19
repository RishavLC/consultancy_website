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
function admin_logged_in(): bool { return !empty($_SESSION['admin_id']); }
function require_admin(): void { if (!admin_logged_in()) { header('Location: login.php'); exit; } }

/** Simple per-session CSRF token for state-changing admin forms/links. */
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
function csrf_check(): bool {
    $token = $_POST['csrf_token'] ?? $_GET['csrf_token'] ?? null;
    return $token !== null && isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
