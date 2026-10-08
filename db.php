<?php
/**
 * Database connection (the "db.php" from the project brief).
 * The real code lives in config/database.php so credentials stay in config/local.php.
 * Include this file from any script that needs db() or the connection settings.
 */
if (isset($_SERVER['SCRIPT_FILENAME']) && realpath($_SERVER['SCRIPT_FILENAME']) === __FILE__) { http_response_code(404); exit('Not found'); }
require_once __DIR__ . '/config/database.php';
