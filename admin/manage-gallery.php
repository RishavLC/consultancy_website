<?php
require_once __DIR__.'/auth.php'; require_admin();
require_once __DIR__.'/upload_helper.php';
$pdo=db(); $errors=[]; $message='';
$cats=['sites'=>'Active Sites','structures'=>'Structures','team'=>'Team','completed'=>'Completed Work'];
const MAX_BATCH = 20;

if (post_too_big()) {
    $errors[]='Those photos together are bigger than the server allows in one upload (limit: '.ini_get('post_max_size').'). Upload fewer photos at a time, or smaller ones.';
} elseif ($_SERVER['REQUEST_METHOD']==='POST') {
    if (!csrf_check()) { $errors[]='Your session expired — please try again.'; }
    elseif (isset($_POST['delete'])) {
        $id=(int)$_POST['delete'];
        $st=$pdo->prepare('SELECT image FROM gallery WHERE id=?'); $st->execute([$id]); $img=$st->fetchColumn();
        $pdo->prepare('DELETE FROM gallery WHERE id=?')->execute([$id]);
        if ($img) delete_uploaded_image($img);
        header('Location: manage-gallery.php?done=deleted'); exit;
    } else {
        $files=normalize_multi_files('images');
        $category=(string)($_POST['category']??'sites'); if(!isset($cats[$category])) $category='sites';
        $caption=trim((string)($_POST['caption']??'')); if (mb_strlen($caption)>150) $caption=mb_substr($caption,0,150);
        if (!$files) $errors[]='Please choose at least one photo.';
        elseif (count($files)>MAX_BATCH) $errors[]='Please upload at most '.MAX_BATCH.' photos at a time.';
        else {
            $ins=$pdo->prepare('INSERT INTO gallery(category,title,image,sort_order,is_active) VALUES(?,?,?,?,1)');
            $next=(int)$pdo->query('SELECT COALESCE(MAX(sort_order),0) FROM gallery')->fetchColumn();
            $ok=0; $n=0;
            foreach($files as $f){
                $n++;
                try {
                    $saved=save_uploaded_image($f);
                    // Caption: the shared caption (numbered when several), else a tidy version of the file name.
                    $base=pathinfo((string)$f['name'],PATHINFO_FILENAME);
                    $tidy=ucfirst(trim(preg_replace('/[\s_\-]+/',' ',preg_replace('/[^\p{L}\p{N}\s_\-]/u','',$base)))) ?: 'Gallery photo';
                    $title=$caption!=='' ? (count($files)>1 ? $caption.' '.$n : $caption) : mb_substr($tidy,0,150);
                    try { $ins->execute([$category,$title,$saved,++$next]); $ok++; }
                    catch (Throwable $e) { delete_uploaded_image($saved); throw $e; }
                } catch (RuntimeException $e) { $errors[]=$e->getMessage(); }
                catch (Throwable $e) { log_error($e,'gallery bulk insert'); $errors[]='A photo could not be saved to the database.'; }
            }
            if ($ok) $message=$ok.' photo'.($ok>1?'s':'').' uploaded. You can edit the captions below.';
        }
    }
}
if(($_GET['done']??'')==='deleted') $message='Photo deleted.';
$items=$pdo->query('SELECT * FROM gallery ORDER BY sort_order DESC,id DESC')->fetchAll(); $tok=csrf_token();
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Gallery — Admin</title><meta name="robots" content="noindex, nofollow"><link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Karla:wght@400;500;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet"><link rel="stylesheet" href="../assets/css/admin.css"><style>.g-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(210px,1fr));gap:14px}.g-card{background:var(--a-sand-050);border:1px solid var(--a-line);border-radius:6px;overflow:hidden}.g-card img{width:100%;height:140px;object-fit:cover;display:block;background:#ddd}.g-card div{padding:10px 12px}.g-card .btn{margin-top:8px;padding:7px 12px;font-size:12px}</style></head><body>
<div class="top"><b>Admin — Gallery</b><a href="dashboard.php">Dashboard</a></div><div class="wrap"><a href="dashboard.php" class="muted">&larr; Dashboard</a><h1>Gallery Images</h1>
<?php foreach($errors as $e):?><p class="err" role="alert"><?php echo htmlspecialchars($e);?></p><?php endforeach;?>
<?php if($message):?><p class="ok"><?php echo htmlspecialchars($message);?></p><?php endif;?>
<form class="form" method="post" enctype="multipart/form-data"><input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($tok);?>">
<h3>Upload multiple photos</h3>
<label for="images">Photos (hold Ctrl / Shift or tap several to pick many)</label><input id="images" type="file" name="images[]" multiple required accept="image/jpeg,image/png,image/webp,image/gif">
<div class="hint">Up to <?php echo MAX_BATCH;?> photos at a time. JPG, PNG, WEBP or GIF, each up to 5 MB.</div>
<label for="category">Category for these photos</label><select id="category" name="category" style="width:100%;padding:10px;border:1px solid var(--a-brown-500);border-radius:3px;"><?php foreach($cats as $k=>$v):?><option value="<?php echo $k;?>"><?php echo htmlspecialchars($v);?></option><?php endforeach;?></select>
<label for="caption">Caption (optional)</label><input id="caption" name="caption" maxlength="150">
<div class="hint">Empty = each photo's file name is used. If you type one caption for several photos it is numbered (Site visit 1, Site visit 2…). You can edit each caption afterwards.</div>
<button>Upload Photos</button></form>

<h2 style="margin-top:28px;">All gallery photos (<?php echo count($items);?>)</h2>
<?php if(!$items):?><p class="muted">No photos yet.</p><?php endif;?>
<div class="g-grid"><?php foreach($items as $g): $exists=is_file(upload_dir().basename((string)$g['image']));?>
<div class="g-card"><?php if($exists):?><img src="../assets/images/uploads/<?php echo rawurlencode($g['image']);?>" alt="<?php echo htmlspecialchars($g['title']);?>" loading="lazy"><?php else:?><img alt="" src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='210' height='140'><rect width='100%25' height='100%25' fill='%23ddd'/></svg>"><?php endif;?>
<div><b><?php echo htmlspecialchars($g['title']);?></b><br><small class="muted"><?php echo htmlspecialchars($cats[$g['category']]??$g['category']);?><?php echo $g['is_active']?'':' · hidden';?><?php echo $exists?'':' · placeholder';?></small><br>
<a class="btn outline" href="content.php?type=gallery&amp;edit=<?php echo (int)$g['id'];?>">Edit</a>
<form method="post" style="display:inline" onsubmit="return confirm('Delete this photo?')"><input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($tok);?>"><button class="btn danger" name="delete" value="<?php echo (int)$g['id'];?>">Delete</button></form></div></div><?php endforeach;?></div></div></body></html>
