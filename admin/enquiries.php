<?php require_once __DIR__.'/auth.php'; require_admin(); $pdo=db();
if ($_SERVER['REQUEST_METHOD']==='POST') {
    if (!csrf_check()) { http_response_code(400); exit('Invalid or expired request. Go back, reload the page and try again.'); }
    $allowed=['new','read','replied','closed']; $status=(string)($_POST['status']??'');
    if (in_array($status,$allowed,true) && isset($_POST['id'])) $pdo->prepare('UPDATE enquiries SET status=? WHERE id=?')->execute([$status,(int)$_POST['id']]);
    header('Location: enquiries.php'); exit;
}
$items=$pdo->query('SELECT * FROM enquiries ORDER BY created_at DESC LIMIT 500')->fetchAll(); $tok=csrf_token();
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Enquiries — Admin</title><meta name="robots" content="noindex, nofollow"><link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Karla:wght@400;500;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet"><link rel="stylesheet" href="../assets/css/admin.css"></head><body><div class="top"><b>Admin — Enquiries</b><a href="index.php">Dashboard</a></div><div class="wrap"><a href="index.php" class="muted">&larr; Dashboard</a><h1>Project Enquiries</h1>
<?php if(empty($items)):?><p class="muted" style="padding:20px 0;">No enquiries yet — they'll show up here as soon as someone submits the contact form.</p><?php endif;?>
<?php foreach($items as $x):?><div class="item <?php echo $x['status']==='new'?'new':'';?>"><h3><?php echo htmlspecialchars($x['name']);?> &mdash; <?php echo htmlspecialchars($x['service']);?><?php if($x['status']==='new'):?><span class="badge-new">New</span><?php endif;?></h3>
<p><b>Email:</b> <a href="mailto:<?php echo htmlspecialchars($x['email']);?>"><?php echo htmlspecialchars($x['email']);?></a> &nbsp; <b>Phone:</b> <?php echo htmlspecialchars($x['phone']??'');?></p><p><?php echo nl2br(htmlspecialchars($x['message']));?></p>
<small class="muted"><?php echo htmlspecialchars($x['created_at']);?> &middot; Status: <?php echo htmlspecialchars($x['status']);?></small>
<form class="actions" method="post" style="display:flex;gap:8px;flex-wrap:wrap"><input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($tok);?>"><input type="hidden" name="id" value="<?php echo (int)$x['id'];?>">
<button class="btn outline" name="status" value="read">Mark Read</button><button class="btn outline" name="status" value="replied">Replied</button><button class="btn outline" name="status" value="closed">Close</button></form></div><?php endforeach;?></div></body></html>
