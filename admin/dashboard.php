<?php require_once __DIR__.'/auth.php'; require_admin(); require_once __DIR__.'/upload_helper.php'; $pdo=db();
$count=function(string $sql) use ($pdo): int { try { return (int)$pdo->query($sql)->fetchColumn(); } catch (Throwable $e) { log_error($e,'dashboard'); return 0; } };
$cards=[
 'Work Logs'=>$count('SELECT COUNT(*) FROM projects'),
 'Gallery Images'=>$count('SELECT COUNT(*) FROM gallery'),
 'Contact Messages'=>$count('SELECT COUNT(*) FROM enquiries'),
 'Services'=>$count('SELECT COUNT(*) FROM services'),
 'Team Members'=>$count('SELECT COUNT(*) FROM team_members'),
 'Testimonials'=>$count('SELECT COUNT(*) FROM testimonials'),
];
$new=$count("SELECT COUNT(*) FROM enquiries WHERE status='new'");
// Banner status: Live / Hidden / Image missing / Not set
$bannerStatus='Not set';
try {
    $b=$pdo->query('SELECT * FROM banners ORDER BY id DESC LIMIT 1')->fetch();
    if ($b) $bannerStatus = !$b['is_active'] ? 'Hidden' : (admin_image_src($b['image']) !== null ? 'Live' : 'Image missing');
} catch (Throwable $e) { log_error($e,'dashboard banner'); }
// Warn if the admin is still using the factory default password.
$st=$pdo->prepare('SELECT password_hash FROM admins WHERE id=?'); $st->execute([(int)$_SESSION['admin_id']]); $h=(string)$st->fetchColumn();
$defaultPw = $h !== '' && password_verify('admin123', $h);
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Admin Dashboard</title><meta name="robots" content="noindex, nofollow"><link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Karla:wght@400;500;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet"><link rel="stylesheet" href="../assets/css/admin.css"></head><body>
<div class="top"><b>Admin Dashboard</b><span class="muted" style="color:var(--a-skin);"><?php echo htmlspecialchars($_SESSION['admin_username']);?> &middot; <a href="password.php">Change password</a> &middot;
<form method="post" action="logout.php" style="display:inline"><input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token());?>"><button style="background:none;border:0;padding:0;color:inherit;text-decoration:underline;cursor:pointer;font:inherit">Logout</button></form></span></div>
<div class="wrap"><h1>Dashboard</h1>
<?php if($defaultPw):?><p class="err" role="alert"><b>Action needed:</b> you are still using the default password. <a href="password.php">Change it now</a> — anyone who knows the default can edit your website.</p><?php endif;?>
<div class="stat-grid">
<?php foreach($cards as $k=>$v):?><div class="stat-card"><b><?php echo $v;?></b><span><?php echo htmlspecialchars($k);?></span></div><?php endforeach;?>
<a class="stat-card" href="manage-banner.php" style="text-decoration:none"><b><?php echo htmlspecialchars($bannerStatus);?></b><span>Home Banner</span></a>
</div>
<h2>Manage</h2><div class="quick-links">
<a href="manage-banner.php" class="btn outline">Home Banner</a>
<a href="manage-work.php" class="btn outline">Work Logs</a>
<a href="manage-gallery.php" class="btn outline">Gallery</a>
<a href="manage-contact.php" class="btn outline">Contact Messages<?php if($new>0):?><span class="badge-new"><?php echo $new;?> new</span><?php endif;?></a></div>
<h2>Other website content</h2><div class="quick-links">
<a href="content.php?type=services" class="btn outline">Services</a><a href="content.php?type=team" class="btn outline">Team Members</a><a href="content.php?type=testimonials" class="btn outline">Testimonials</a>
<a href="content.php?type=stats" class="btn outline">Key Numbers</a><a href="content.php?type=workflow" class="btn outline">How We Work</a><a href="content.php?type=milestones" class="btn outline">Company Timeline</a><a href="content.php?type=values" class="btn outline">Core Values</a><a href="content.php?type=hours" class="btn outline">Office Hours</a></div>
<h2>Website</h2><div class="quick-links"><a href="settings.php" class="btn outline">Website Settings &amp; About Text</a><a href="password.php" class="btn outline">Change Password</a></div>
<p style="margin-top:26px;"><a href="../index.php" class="muted">&larr; View website</a></p></div></body></html>
