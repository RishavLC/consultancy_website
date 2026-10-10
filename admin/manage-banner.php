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
    $error = 'That image is bigger than the server allows in one upload (limit: ' . ini_get('post_max_size') . '). Please use a smaller photo, or paste an image link instead.';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check()) {
        $error = 'Your session expired — please try again.';
    } else {
        try {
            $action = $_POST['action'] ?? 'save';
            if ($action === 'delete') {
                if ($banner) {
                    delete_uploaded_image($banner['image']);        // does nothing for pasted links
                    $pdo->prepare('DELETE FROM banners')->execute();
                    $banner = null;
                }
                $message = 'Banner deleted. The homepage is showing its default photo collage again.';
            } else {
                $title = trim((string)($_POST['title'] ?? '')); $subtitle = trim((string)($_POST['subtitle'] ?? ''));
                $active = isset($_POST['is_active']) ? 1 : 0;
                if (mb_strlen($title) > 200 || mb_strlen($subtitle) > 300) throw new RuntimeException('Title is limited to 200 characters and subtitle to 300.');
                $existing = $banner['image'] ?? null;
                $newImage = resolve_image_input($existing, 'image');       // upload, link (optionally downloaded), or keep
                if (!$newImage) throw new RuntimeException('Please upload a banner image or paste an image link.');
                $changed = ($newImage !== $existing);
                if ($banner) {
                    $pdo->prepare('UPDATE banners SET image=?,title=?,subtitle=?,is_active=? WHERE id=?')->execute([$newImage, $title, $subtitle, $active, $banner['id']]);
                    $message = $changed ? 'Banner image replaced.' : 'Banner details saved.';
                } else {
                    $pdo->prepare('INSERT INTO banners(image,title,subtitle,is_active) VALUES(?,?,?,?)')->execute([$newImage, $title, $subtitle, $active]);
                    $message = 'Banner saved. It is now live on the homepage.';
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
$imgOk = $banner && admin_image_src($banner['image']) !== null;
$status = !$banner ? 'Not set' : (!$banner['is_active'] ? 'Hidden' : ($imgOk ? 'Live' : 'Image missing'));
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Home Banner — Admin</title><meta name="robots" content="noindex, nofollow"><link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Karla:wght@400;500;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet"><link rel="stylesheet" href="../assets/css/admin.css"><script src="../assets/js/admin-image.js" defer></script></head><body>
<div class="top"><b>Admin — Home Banner</b><a href="dashboard.php">Dashboard</a></div>
<div class="wrap"><a href="dashboard.php" class="muted">&larr; Dashboard</a><h1>Home Banner</h1>
<p class="muted" style="margin-bottom:14px;">The large photo at the top of the homepage. Status: <b><?php echo htmlspecialchars($status); ?></b></p>
<?php if($error):?><p class="err" role="alert"><?php echo htmlspecialchars($error);?></p><?php endif;?>
<?php if($message):?><p class="ok"><?php echo htmlspecialchars($message);?></p><?php endif;?>

<?php if($banner):?><form method="post" style="margin-bottom:16px;" onsubmit="return confirm('Delete the banner? The homepage will go back to its default photos.')"><input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token());?>"><input type="hidden" name="action" value="delete"><button class="btn danger">Delete Banner</button></form><?php endif;?>

<form class="form" method="post" enctype="multipart/form-data"><input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token());?>"><input type="hidden" name="action" value="save">
<h3><?php echo $banner ? 'Replace image / edit text' : 'Add a banner'; ?></h3>
<?php render_image_field($banner ? 'Banner image' : 'Banner image (required)', $banner['image'] ?? '', 'image', false); ?>
<div class="hint">A wide landscape photo works best (about 1920 × 900 px). Darker photos make the headline easier to read.</div>
<label for="title">Banner title (optional)</label><input id="title" name="title" maxlength="200" value="<?php echo htmlspecialchars($banner['title'] ?? '');?>">
<div class="hint">Big headline on the banner. Empty = the default headline.</div>
<label for="subtitle">Banner subtitle (optional)</label><input id="subtitle" name="subtitle" maxlength="300" value="<?php echo htmlspecialchars($banner['subtitle'] ?? '');?>">
<label style="display:flex;gap:8px;align-items:center;margin-top:16px;"><input type="checkbox" name="is_active" value="1" style="width:auto;" <?php echo (!$banner || $banner['is_active']) ? 'checked' : '';?>> Show banner on homepage</label>
<button><?php echo $banner ? 'Save Banner' : 'Save Banner'; ?></button></form></div></body></html>
