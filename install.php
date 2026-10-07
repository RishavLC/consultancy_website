<?php
/**
 * One-time installer. Creates the database, tables and starter content, then creates the
 * admin account with a RANDOM password shown once on screen.
 * It locks itself afterwards (data/installed.lock), refuses to run on a site that already
 * has an admin account, and tries to delete itself. Delete this file after installing anyway.
 */
require_once __DIR__ . '/config/database.php';
$lock = __DIR__ . '/data/installed.lock';
$message = ''; $error = ''; $password = '';

function already_installed(string $lock): bool {
    if (is_file($lock)) return true;
    try {
        $n = (int)db()->query('SELECT COUNT(*) FROM admins')->fetchColumn();
        return $n > 0;
    } catch (Throwable $e) { return false; }
}
$locked = already_installed($lock);

if (!$locked && $_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $server = new PDO('mysql:host='.DB_HOST.';charset=utf8mb4', DB_USER, DB_PASS, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
        $server->exec('CREATE DATABASE IF NOT EXISTS `'.str_replace('`','',DB_NAME).'` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        $pdo = db();
        $schema = file_get_contents(__DIR__.'/schema.sql');
        $seed = file_get_contents(__DIR__.'/seed.sql');
        foreach (preg_split('/;\s*(?:\r?\n|$)/', $schema) as $sql) { if (trim($sql)) $pdo->exec($sql); }
        foreach (preg_split('/;\s*(?:\r?\n|$)/', $seed) as $sql) { if (trim($sql)) $pdo->exec($sql); }
        $alphabet = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        for ($i = 0; $i < 16; $i++) $password .= $alphabet[random_int(0, strlen($alphabet) - 1)];
        $st = $pdo->prepare('INSERT INTO admins(username,password_hash) VALUES(?,?)');
        $st->execute(['admin', password_hash($password, PASSWORD_DEFAULT)]);
        @file_put_contents($lock, date('c'));
        $message = 'Installation completed.';
        $selfDeleted = @unlink(__FILE__);
    } catch (Throwable $e) {
        log_error($e, 'install');
        $error = APP_DEBUG ? $e->getMessage() : 'Installation failed. Check the MySQL details in config/database.php (or config/local.php), make sure MySQL is running, then try again. The exact error was written to the PHP error log.';
        $password = '';
    }
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>Installer</title><style>body{font-family:Arial,sans-serif;background:#f4efe7;padding:40px}.box{max-width:650px;margin:auto;background:#fff;padding:30px;border-radius:10px;box-shadow:0 10px 30px #0001}button{background:#7d402d;color:#fff;border:0;padding:12px 18px;border-radius:6px;cursor:pointer}.ok{background:#e8f6ec;padding:15px}.err{background:#fdecec;padding:15px;word-break:break-word}.pw{font:700 22px monospace;background:#fff8e1;border:2px dashed #c9a227;padding:14px;margin:12px 0;user-select:all}code{background:#eee;padding:2px 5px}</style></head><body><div class="box"><h1>Database Setup</h1>
<?php if ($locked): ?>
  <div class="err"><b>This site is already installed.</b><br>For security, the installer is disabled. Please delete <code>install.php</code> from the server.</div>
<?php elseif ($message): ?>
  <div class="ok"><b><?php echo htmlspecialchars($message); ?></b><br>Admin username: <b>admin</b><br>Your admin password (shown <u>only once</u> — copy it now):</div>
  <div class="pw"><?php echo htmlspecialchars($password); ?></div>
  <p>Log in, then use <b>Change Password</b> to set your own. <?php echo !empty($selfDeleted) ? 'This installer file has been removed automatically.' : 'Now delete <code>install.php</code> from the server.'; ?></p>
  <p><a href="admin/login.php">Open Admin Login</a> · <a href="index.php">Open Website</a></p>
<?php else: ?>
  <p>This creates the MySQL database, tables and starter content, and a first admin account with a random password.</p>
  <?php if ($error): ?><div class="err"><b>Installation failed:</b><br><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
  <form method="post"><button type="submit">Install / Seed Database</button></form>
<?php endif; ?></div></body></html>
