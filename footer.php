<?php
// Common footer (copyright, quick links, social links). Real file: includes/footer.php.
if (isset($_SERVER['SCRIPT_FILENAME']) && realpath($_SERVER['SCRIPT_FILENAME']) === __FILE__) { http_response_code(404); exit('Not found'); }
require_once __DIR__ . '/includes/footer.php';
