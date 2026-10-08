<?php
// Common header (logo + navigation). Real file: includes/header.php. This wrapper only exists to match the project brief.
if (isset($_SERVER['SCRIPT_FILENAME']) && realpath($_SERVER['SCRIPT_FILENAME']) === __FILE__) { http_response_code(404); exit('Not found'); }
require_once __DIR__ . '/includes/header.php';
