<?php
/**
 * Database connection (core PHP, mysqli)
 * Update these 4 values to match your hosting/XAMPP/WAMP setup.
 */
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'civil_consultancy');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');

// Base site URL - used for admin redirects. Adjust if the site lives in a sub-folder.
define('SITE_URL', '/');
