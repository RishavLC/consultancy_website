<?php
require_once __DIR__ . '/config/database.php';
$message = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $server = new PDO('mysql:host='.DB_HOST.';charset=utf8mb4', DB_USER, DB_PASS, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
        $server->exec('CREATE DATABASE IF NOT EXISTS `'.str_replace('`','',DB_NAME).'` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        $pdo = db();
        $schema = file_get_contents(__DIR__.'/schema.sql');
        $seed = file_get_contents(__DIR__.'/seed.sql');
        foreach (preg_split('/;\s*(?:\r?\n|$)/', $schema) as $sql) { if (trim($sql)) $pdo->exec($sql); }
        foreach (preg_split('/;\s*(?:\r?\n|$)/', $seed) as $sql) { if (trim($sql)) $pdo->exec($sql); }
        $hash = password_hash('admin123', PASSWORD_DEFAULT);
        $st = $pdo->prepare('INSERT INTO admins(username,password_hash) VALUES(?,?) ON DUPLICATE KEY UPDATE password_hash=VALUES(password_hash)');
        $st->execute(['admin', $hash]);
        $message = 'Installation completed. Admin login: username <b>admin</b>, password <b>admin123</b>. Change the password after your first login and delete/rename install.php.';
    } catch (Throwable $e) { $error = $e->getMessage(); }
}
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Consultancy DB Installer</title><style>body{font-family:Arial;background:#f4efe7;padding:40px}.box{max-width:650px;margin:auto;background:#fff;padding:30px;border-radius:10px;box-shadow:0 10px 30px #0001}button{background:#7d402d;color:#fff;border:0;padding:12px 18px;border-radius:6px;cursor:pointer}.ok{background:#e8f6ec;padding:15px}.err{background:#fdecec;padding:15px;word-break:break-word}code{background:#eee;padding:2px 5px}</style></head><body><div class="box"><h1>Strata &amp; Beam Database Setup</h1><p>This creates the MySQL database, tables and starter content from the original website.</p><?php if($message):?><div class="ok"><?php echo $message;?></div><p><a href="admin/login.php">Open Admin Login</a> · <a href="index.php">Open Website</a></p><?php elseif($error):?><div class="err"><b>Installation failed:</b><br><?php echo htmlspecialchars($error);?></div><p>Check <code>config/database.php</code> and make sure MySQL is running.</p><?php endif;?><form method="post"><button type="submit">Install / Seed Database</button></form></div></body></html>
