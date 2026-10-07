<?php require_once __DIR__.'/auth.php'; require_admin(); $pdo=db();
$counts=[]; foreach(['services'=>'Services','projects'=>'Projects','gallery'=>'Gallery','team_members'=>'Team Members','testimonials'=>'Testimonials','enquiries'=>'Enquiries'] as $t=>$lbl) $counts[$lbl]=(int)$pdo->query("SELECT COUNT(*) FROM `$t`")->fetchColumn();
$new=(int)$pdo->query("SELECT COUNT(*) FROM enquiries WHERE status='new'")->fetchColumn();
// Warn if the admin is still using the factory default password.
$st=$pdo->prepare('SELECT password_hash FROM admins WHERE id=?'); $st->execute([(int)$_SESSION['admin_id']]); $h=(string)$st->fetchColumn();
$defaultPw = $h !== '' && password_verify('admin123', $h);
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Admin Dashboard</title><meta name="robots" content="noindex, nofollow"><link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Karla:wght@400;500;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet"><link rel="stylesheet" href="../assets/css/admin.css"></head><body>
<div class="top"><b>Admin Dashboard</b><span class="muted" style="color:var(--a-skin);"><?php echo htmlspecialchars($_SESSION['admin_username']);?> &middot; <a href="password.php">Change password</a> &middot;
<form method="post" action="logout.php" style="display:inline"><input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token());?>"><button style="background:none;border:0;padding:0;color:inherit;text-decoration:underline;cursor:pointer;font:inherit">Logout</button></form></span></div>
<div class="wrap"><h1>Dashboard</h1>
<?php if($defaultPw):?><p class="err" role="alert"><b>Action needed:</b> you are still using the default password. <a href="password.php">Change it now</a> — anyone who knows the default can edit your website.</p><?php endif;?>
<div class="stat-grid"><?php foreach($counts as $k=>$v):?><div class="stat-card"><b><?php echo $v;?></b><span><?php echo htmlspecialchars($k);?></span></div><?php endforeach;?></div>
<h2>Manage Content</h2><div class="quick-links">
<a href="content.php?type=services" class="btn outline">Services</a><a href="content.php?type=projects" class="btn outline">Projects</a><a href="content.php?type=gallery" class="btn outline">Gallery</a><a href="content.php?type=team" class="btn outline">Team Members</a><a href="content.php?type=testimonials" class="btn outline">Testimonials</a>
<a href="enquiries.php" class="btn outline">Enquiries<?php if($new>0):?><span class="badge-new"><?php echo $new;?> new</span><?php endif;?></a></div>
<h2>Homepage &amp; About Page Content</h2><div class="quick-links">
<a href="content.php?type=stats" class="btn outline">Key Numbers (homepage)</a><a href="content.php?type=workflow" class="btn outline">How We Work (steps)</a><a href="content.php?type=milestones" class="btn outline">Company Timeline</a><a href="content.php?type=values" class="btn outline">Company Values</a><a href="content.php?type=hours" class="btn outline">Office Hours</a></div>
<h2>Website</h2><div class="quick-links"><a href="settings.php" class="btn outline">Website Settings</a><a href="password.php" class="btn outline">Change Password</a></div>
<p style="margin-top:26px;"><a href="../index.php" class="muted">&larr; View website</a></p></div></body></html>
