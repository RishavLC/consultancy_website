<?php require_once __DIR__.'/auth.php'; require_admin(); $pdo=db();
$statuses=['new'=>'New','read'=>'Read','replied'=>'Replied','closed'=>'Closed'];
$filter=(string)($_GET['status']??''); if(!isset($statuses[$filter])) $filter='';

if ($_SERVER['REQUEST_METHOD']==='POST') {
    if (!csrf_check()) { http_response_code(400); exit('Invalid or expired request. Go back, reload the page and try again.'); }
    $id=(int)($_POST['id']??0);
    if (isset($_POST['delete'])) {
        $pdo->prepare('DELETE FROM enquiries WHERE id=?')->execute([$id]);
        $flash='deleted';
    } elseif (isset($statuses[(string)($_POST['status']??'')]) && $id) {
        $pdo->prepare('UPDATE enquiries SET status=? WHERE id=?')->execute([$_POST['status'],$id]);
        $flash='updated';
    }
    header('Location: manage-contact.php?'.($filter?'status='.urlencode($filter).'&':'').'done='.($flash??'')); exit;
}
$counts=array_fill_keys(array_keys($statuses),0);
foreach($pdo->query('SELECT status,COUNT(*) c FROM enquiries GROUP BY status') as $r) $counts[$r['status']]=(int)$r['c'];
$total=array_sum($counts);
if($filter){ $st=$pdo->prepare('SELECT * FROM enquiries WHERE status=? ORDER BY created_at DESC LIMIT 500'); $st->execute([$filter]); $items=$st->fetchAll(); }
else $items=$pdo->query('SELECT * FROM enquiries ORDER BY created_at DESC LIMIT 500')->fetchAll();
$tok=csrf_token();
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Contact Messages — Admin</title><meta name="robots" content="noindex, nofollow"><link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Karla:wght@400;500;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet"><link rel="stylesheet" href="../assets/css/admin.css"></head><body><div class="top"><b>Admin — Contact Messages</b><a href="dashboard.php">Dashboard</a></div><div class="wrap"><a href="dashboard.php" class="muted">&larr; Dashboard</a><h1>Contact Messages</h1>
<?php if(($_GET['done']??'')==='deleted'):?><p class="ok">Message deleted.</p><?php elseif(($_GET['done']??'')==='updated'):?><p class="ok">Status updated.</p><?php endif;?>
<div class="quick-links" style="margin-bottom:18px;">
<a href="manage-contact.php" class="btn outline" style="margin-top:0;<?php echo !$filter?'background:var(--a-brown-900);color:#fff;':'';?>">All (<?php echo $total;?>)</a>
<?php foreach($statuses as $k=>$lbl):?><a href="manage-contact.php?status=<?php echo $k;?>" class="btn outline" style="margin-top:0;<?php echo $filter===$k?'background:var(--a-brown-900);color:#fff;':'';?>"><?php echo $lbl;?> (<?php echo $counts[$k];?>)</a><?php endforeach;?></div>
<?php if(empty($items)):?><p class="muted" style="padding:20px 0;">No messages here yet — they'll show up as soon as someone submits the contact form.</p><?php endif;?>
<?php foreach($items as $x):?><div class="item <?php echo $x['status']==='new'?'new':'';?>"><h3><?php echo htmlspecialchars($x['name']);?> &mdash; <?php echo htmlspecialchars($x['service']);?><?php if($x['status']==='new'):?><span class="badge-new">New</span><?php endif;?></h3>
<p><b>Email:</b> <a href="mailto:<?php echo htmlspecialchars($x['email']);?>"><?php echo htmlspecialchars($x['email']);?></a> &nbsp; <b>Phone:</b> <?php echo htmlspecialchars($x['phone']??'');?></p><p><?php echo nl2br(htmlspecialchars($x['message']));?></p>
<small class="muted"><?php echo htmlspecialchars($x['created_at']);?> &middot; Status: <?php echo htmlspecialchars($x['status']);?></small>
<form class="actions" method="post" style="display:flex;gap:8px;flex-wrap:wrap"><input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($tok);?>"><input type="hidden" name="id" value="<?php echo (int)$x['id'];?>">
<button class="btn outline" name="status" value="read">Mark Read</button><button class="btn outline" name="status" value="replied">Replied</button><button class="btn outline" name="status" value="closed">Close</button>
<button class="btn danger" name="delete" value="1" formnovalidate onclick="return confirm('Delete this message permanently? This cannot be undone.')">Delete</button></form></div><?php endforeach;?></div></body></html>
