<?php
require_once __DIR__.'/auth.php';
if (admin_logged_in()) { header('Location: index.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = trim((string)($_POST['username'] ?? ''));
    $p = (string)($_POST['password'] ?? '');
    if (login_locked($u)) {
        $error = 'Too many failed attempts. Please wait 15 minutes and try again.';
    } else {
        try {
            $st = db()->prepare('SELECT * FROM admins WHERE username=? LIMIT 1');
            $st->execute([$u]);
            $a = $st->fetch();
            // Always run a hash check so response time doesn't reveal whether the username exists.
            $hash = $a['password_hash'] ?? '$2y$10$usesomesillystringfore7hnbRJHxXVLeakoG8K30oukPsA.ztMG';
            if (password_verify($p, $hash) && $a) {
                session_regenerate_id(true);
                $_SESSION['admin_id'] = $a['id'];
                $_SESSION['admin_username'] = $a['username'];
                $_SESSION['last_active'] = time();
                login_succeeded($u);
                if (password_needs_rehash($hash, PASSWORD_DEFAULT)) {
                    db()->prepare('UPDATE admins SET password_hash=? WHERE id=?')->execute([password_hash($p, PASSWORD_DEFAULT), $a['id']]);
                }
                header('Location: index.php'); exit;
            }
            login_failed($u);
            $error = 'Invalid username or password.';
        } catch (Throwable $e) {
            log_error($e, 'admin login');
            $error = 'The site could not reach its database. Please try again shortly.';
        }
    }
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Admin Login</title><meta name="robots" content="noindex, nofollow"><link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Karla:wght@400;500;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet"><link rel="stylesheet" href="../assets/css/admin.css"><style>body{display:grid;place-items:center;min-height:100vh}</style></head><body><div class="box"><h1>Admin Login</h1><p class="muted" style="margin-bottom:20px;">Content management</p><?php if($error):?><div class="err" role="alert"><?php echo htmlspecialchars($error);?></div><?php endif;?><form method="post" autocomplete="on"><label for="username">Username</label><input id="username" name="username" required autofocus autocomplete="username"><label for="password">Password</label><input id="password" name="password" type="password" required autocomplete="current-password"><button style="width:100%;text-align:center;">Log In</button></form><p style="margin-top:20px;"><a href="../index.php" class="muted">&larr; Back to website</a></p></div></body></html>
