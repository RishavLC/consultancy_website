<?php
// Work logs (projects) are managed with the shared content editor: add, edit, delete, photo upload, completion date.
require_once __DIR__ . '/auth.php'; require_admin();
header('Location: content.php?type=projects'); exit;
