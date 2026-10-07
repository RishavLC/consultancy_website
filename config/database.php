<?php
/**
 * MySQL connection + global app settings.
 *
 * DO NOT put real passwords in this file. Copy config/local.php.example to
 * config/local.php and put your real credentials there — local.php overrides
 * the defaults below and is blocked from the web by config/.htaccess.
 */
if (is_file(__DIR__ . '/local.php')) { require __DIR__ . '/local.php'; }

if (!defined('DB_HOST')) define('DB_HOST', '127.0.0.1');
if (!defined('DB_NAME')) define('DB_NAME', 'consultancy_db');
if (!defined('DB_USER')) define('DB_USER', 'root');   // XAMPP default - change for production
if (!defined('DB_PASS')) define('DB_PASS', '');       // XAMPP default - change for production
if (!defined('APP_TIMEZONE')) define('APP_TIMEZONE', 'Asia/Kathmandu');
if (!defined('APP_DEBUG')) define('APP_DEBUG', false); // true only on your own computer

date_default_timezone_set(APP_TIMEZONE);
ini_set('display_errors', APP_DEBUG ? '1' : '0');
ini_set('log_errors', '1');

function db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;
    $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4', DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    // Keep MySQL's clock in step with PHP's so enquiry times are correct in Nepal.
    try { $pdo->exec("SET time_zone = '" . (new DateTime('now', new DateTimeZone(APP_TIMEZONE)))->format('P') . "'"); } catch (Throwable $e) {}
    return $pdo;
}

/** Writes a technical error to the server log and returns a generic message for users. */
function log_error(Throwable $e, string $context = ''): void {
    error_log('[consultancy] ' . $context . ' ' . get_class($e) . ': ' . $e->getMessage());
}

/** Absolute site URL (https-aware), no trailing slash, e.g. https://example.com/sub */
function base_url(): string {
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    $host  = $_SERVER['HTTP_HOST'] ?? 'localhost';
    if (!preg_match('/^[A-Za-z0-9.\-:\[\]]+$/', $host)) $host = 'localhost';
    $dir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
    if (substr($dir, -6) === '/admin') $dir = substr($dir, 0, -6);
    return ($https ? 'https://' : 'http://') . $host . $dir;
}
