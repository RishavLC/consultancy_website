<?php
require_once __DIR__.'/auth.php';
// Logout is a POST (with CSRF token) so another website can't log you out via an image/link.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) { admin_logout(); }
header('Location: login.php'); exit;
