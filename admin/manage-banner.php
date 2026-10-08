<?php
require_once __DIR__.'/auth.php'; require_admin();
require_once __DIR__.'/upload_helper.php';
$pdo = db(); $error = ''; $message = '';

/** The site uses ONE home banner: the newest row. */
function current_banner(PDO $pdo): ?array {
    try { $r = $pdo->query('SELECT * FROM banners ORDER BY id DESC LIMIT 1')->fetch(); return $r ?: null; }
    catch (Throwable $e) { log_error($e, 'banner read'); return null; }
}
$banner = current_banner($pdo);

if (post_too_big()) {
    $error = 'That image is bigger than the server allows in one upload (limit: ' . ini_get('post_max_size') . '). Please use a smaller photo.';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check()) {
        $error = 'Your session expired — please try again.';
    } else {
        try {
            $action = $_POST['action'] ?? 'save';
            if ($action === 'delete') {
                if ($banner) {
                    delete_uploaded_image($banner['image']);
                    $pdo->prepare('DELETE FROM banners')->execute();
                    $banner = null;
                }
                $message = 'Banner deleted. The homepage is showing its default photo collage again.';
            } else {
                $title = trim((string)($_POST['title'] ?? '')); $subtitle = trim((string)($_POST['subtitle'] ?? ''));
                $active = isset($_POST['is_active']) ? 1 : 0;
                if (mb_strlen($title) > 200 || mb_strlen($subtitle) > 300) throw new RuntimeException('Title is limited to 200 characters and subtitle to 300.');
                $hasUpload = !empty($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE;
                if (!$banner && !$hasUpload) throw new RuntimeException('Please choose a banner image to upload.');
                $newImage = $banner['image'] ?? '';
                if ($hasUpload) {
                    $newImage = save_uploaded_image($_FILES['image']);        // validated + stored
                }
                if ($banner) {
                    $pdo->prepare('UPDATE banners SET image=?,title=?,subtitle=?,is_active=? WHERE id=?')->execute([$newImage, $title, $subtitle, $active, $banner['id']]);
                    if ($hasUpload) delete_uploaded_image($banner['image']);   // replace = old file removed
                    $message = $hasUpload ? 'Banner image replaced.' : 'Banner details saved.';
                } else {
                    $pdo->prepare('INSERT INTO banners(image,title,subtitle,is_active) VALUES(?,?,?,?)')->execute([$newImage, $title, $subtitle, $active]);
                    $message = 'Banner uploaded. It is now live on the homepage.';
                }
                $banner = current_banner($pdo);
            }
        } catch (RuntimeException $e) {
            $error = $e->getMessage();
        } catch (Throwable $e) {
            log_error($e, 'banner save');
            $error = 'Could not save the banner. Please try again.' . (APP_DEBUG ? ' (' . $e->getMessage() . ')' : '');
        }
    }
}
$imgOk = $banner && is_file(upload_dir() . basename($banner['image']));
$status = !$banner ? 'Not set' : (!$banner['is_active'] ? 'Hidden' : ($imgOk ? 'Live' : 'Image missing'));
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Home Banner — Admin</title><meta name="robots" content="noindex, nofollow"><link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Karla:wght@400;500;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet"><link rel="stylesheet" href="../assets/css/admin.css"></head><body>
<div class="top"><b>Admin — Home Banner</b><a href="dashboard.php">Dashboard</a></div>
<div class="wrap"><a href="dashboard.php" class="muted">&larr; Dashboard</a><h1>Home Banner</h1>
<p class="muted" style="margin-bottom:14px;">The large photo at the top of the homepage. Status: <b><?php echo htmlspecialchars($status); ?></b></p>
<?php if($error):?><p class="err" role="alert"><?php echo htmlspecialchars($error);?></p><?php endif;?>
<?php if($message):?><p class="ok"><?php echo htmlspecialchars($message);?></p><?php endif;?>

<?php if($imgOk):?><div class="item"><h3>Current banner</h3>
<img src="../assets/images/uploads/<?php echo rawurlencode($banner['image']);?>" alt="Current banner preview" style="max-width:100%;max-height:260px;border-radius:6px;display:block;margin-bottom:10px;">
<form method="post" onsubmit="return confirm('Delete the banner image? The homepage will go back to its default photos.')"><input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token());?>"><input type="hidden" name="action" value="delete"><button class="btn danger">Delete Banner</button></form></div><?php endif;?>

<form class="form" method="post" enctype="multipart/form-data"><input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token());?>"><input type="hidden" name="action" value="save">
<h3><?php echo $banner ? 'Replace image / edit text' : 'Upload a banner';?></h3>
<label for="image"><?php echo $banner ? 'New banner image (leave empty to keep the current one)' : 'Banner image';?></label><input id="image" type="file" name="image" accept="image/jpeg,image/png,image/webp,image/gif" <?php echo $banner ? '' : 'required';?>>
<div class="hint">Wide landscape photo works best (about 1920 × 900 px). JPG, PNG, WEBP or GIF, up to 5 MB.</div>
<label for="title">Banner title (optional)</label><input id="title" name="title" maxlength="200" value="<?php echo htmlspecialchars($banner['title'] ?? '');?>">
<div class="hint">Big headline on the banner. Empty = the default headline.</div>
<label for="subtitle">Banner subtitle (optional)</label><input id="subtitle" name="subtitle" maxlength="300" value="<?php echo htmlspecialchars($banner['subtitle'] ?? '');?>">
<label style="display:flex;gap:8px;align-items:center;margin-top:16px;"><input type="checkbox" name="is_active" value="1" style="width:auto;" <?php echo (!$banner || $banner['is_active']) ? 'checked' : '';?>> Show banner on homepage</label>
<button><?php echo $banner ? 'Save Banner' : 'Upload Banner';?></button></form></div></body></html>
