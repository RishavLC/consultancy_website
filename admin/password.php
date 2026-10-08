<?php
require_once __DIR__.'/auth.php'; require_admin(); $pdo = db();
$error = ''; $message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check()) {
        $error = 'Your session expired — please try again.';
    } else {
        $cur = (string)($_POST['current'] ?? ''); $new = (string)($_POST['new'] ?? ''); $conf = (string)($_POST['confirm'] ?? '');
        $st = $pdo->prepare('SELECT * FROM admins WHERE id=?'); $st->execute([(int)$_SESSION['admin_id']]); $a = $st->fetch();
        if (!$a || !password_verify($cur, $a['password_hash'])) $error = 'Your current password is not correct.';
        elseif (strlen($new) < 10) $error = 'Please choose a new password of at least 10 characters.';
        elseif ($new !== $conf) $error = 'The two new passwords do not match.';
        elseif ($new === $cur) $error = 'The new password must be different from the current one.';
        elseif (strtolower($new) === strtolower($a['username']) || in_array(strtolower($new), ['admin123','password123','1234567890','qwertyuiop'], true)) $error = 'That password is too easy to guess. Please choose another.';
        else {
            $pdo->prepare('UPDATE admins SET password_hash=? WHERE id=?')->execute([password_hash($new, PASSWORD_DEFAULT), $a['id']]);
            session_regenerate_id(true);
            $message = 'Password changed. Use the new password next time you log in.';
        }
    }
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Change Password — Admin</title><meta name="robots" content="noindex, nofollow"><link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Karla:wght@400;500;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet"><link rel="stylesheet" href="../assets/css/admin.css"></head><body><div class="top"><b>Admin — Change Password</b><a href="dashboard.php">Dashboard</a></div><div class="wrap"><div class="box"><a href="dashboard.php" class="muted">&larr; Dashboard</a><h1>Change Password</h1><?php if($error):?><p class="err" role="alert"><?php echo htmlspecialchars($error);?></p><?php endif;?><?php if($message):?><p class="ok"><?php echo htmlspecialchars($message);?></p><?php endif;?>
<form method="post" autocomplete="off"><input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token());?>">
<label for="current">Current password</label><input id="current" type="password" name="current" required autocomplete="current-password">
<label for="new">New password</label><input id="new" type="password" name="new" required minlength="10" autocomplete="new-password"><div class="hint">At least 10 characters. A few random words works well, e.g. <i>blue-kettle-mountain-47</i>.</div>
<label for="confirm">Repeat new password</label><input id="confirm" type="password" name="confirm" required minlength="10" autocomplete="new-password">
<button>Change Password</button></form></div></div></body></html>
