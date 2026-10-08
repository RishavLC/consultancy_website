<?php
// Old address kept so existing links/bookmarks keep working. The page now lives at work.php.
$q = $_SERVER['QUERY_STRING'] ?? '';
header('Location: work.php' . ($q !== '' ? '?' . $q : ''), true, 301); exit;
