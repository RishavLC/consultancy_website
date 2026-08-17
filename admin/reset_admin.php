<?php
// Local XAMPP helper: reset/create the default administrator.
require_once __DIR__ . '/../config/database.php';
$message = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $server = new PDO('mysql:host=' . DB_HOST . ';charset=utf8mb4', DB_USER, DB_PASS, [PDO::ATTR_ERRMODE =>
PDO::ERRMODE_EXCEPTION]); $server->exec('CREATE DATABASE IF NOT EXISTS `' .
str_replace('`', '', DB_NAME) . '` CHARACTER SET utf8mb4 COLLATE
utf8mb4_unicode_ci'); $pdo = db(); $pdo->exec("CREATE TABLE IF NOT EXISTS admins
(id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, username VARCHAR(80) UNIQUE NOT
NULL, password_hash VARCHAR(255) NOT NULL, created_at TIMESTAMP DEFAULT
CURRENT_TIMESTAMP)"); $hash = password_hash('admin123', PASSWORD_DEFAULT); $st =
$pdo->prepare('INSERT INTO admins (username,password_hash) VALUES (?,?) ON
DUPLICATE KEY UPDATE password_hash=VALUES(password_hash)');
$st->execute(['admin', $hash]); $message = 'Admin account is ready. Username:
admin | Password: admin123'; } catch (Throwable $e) { $error = $e->getMessage();
} } ?><!doctype html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Reset Admin</title>
    <style>
      body {
        font-family: Arial;
        background: #f4efe7;
        padding: 40px;
      }
      .box {
        max-width: 620px;
        margin: auto;
        background: #fff;
        padding: 30px;
        border-radius: 10px;
      }
      .ok {
        padding: 15px;
        background: #e7f7ed;
        color: #185b31;
      }
      .err {
        padding: 15px;
        background: #fdecec;
        color: #8a1f1f;
      }
      button {
        padding: 12px 18px;
        background: #7d402d;
        color: #fff;
        border: 0;
        border-radius: 6px;
      }
    </style>
  </head>
  <body>
    <div class="box">
      <h1>Admin Account Setup</h1>
      <p>
        Use this once on your local XAMPP installation if the default admin
        account was not created.
      </p>
      <?php if($message):?>
      <div class="ok"><?php echo htmlspecialchars($message);?></div>
      <p><a href="login.php">Go to Admin Login</a></p>
      <?php endif;?><?php if($error):?>
      <div class="err"><?php echo htmlspecialchars($error);?></div>
      <?php endif;?>
      <form method="post">
        <button type="submit">Create / Reset Admin Account</button>
      </form>
    </div>
  </body>
</html>
