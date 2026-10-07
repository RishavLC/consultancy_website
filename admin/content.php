<?php
require_once __DIR__.'/auth.php'; require_admin(); $pdo=db();
$type=$_GET['type']??'services'; $allowed=['services','projects','gallery','team','testimonials','stats','workflow','milestones','values','hours']; if(!in_array($type,$allowed,true)) exit('Invalid section');

/**
 * Every section is described here in plain language: what the field is
 * called on screen, what kind of input it needs, and — for the plainer
 * fields — a one-line hint shown right under the label. This is the
 * single place to look if a label ever needs changing.
 */
$cfg=[
'services'=>[
    'table'=>'services','title'=>'Services',
    'blurb'=>'These are the six service cards shown on the homepage and the full Services page.',
    'fields'=>[
        'title'=>['label'=>'Service Name','type'=>'text'],
        'icon'=>['label'=>'Icon','type'=>'select','options'=>['blueprint'=>'Blueprint','hardhat'=>'Hard Hat','strata'=>'Layered Strata','road'=>'Road'],'hint'=>'The small picture shown on the service card.'],
        'short_text'=>['label'=>'Short Description','type'=>'textarea','hint'=>'One sentence, shown on the card itself (homepage & Services page).'],
        'description'=>['label'=>'Full Description','type'=>'textarea','hint'=>'The longer paragraph shown when someone opens the Services page.'],
        'features'=>['label'=>'Key Features','type'=>'textarea','hint'=>'One feature per line. Each line becomes its own checkmark bullet point.'],
        'sort_order'=>['label'=>'Display Order','type'=>'number','hint'=>'Lower numbers appear first. Use 0, 1, 2, 3… to set the order.'],
        'is_active'=>['label'=>'Show on website','type'=>'checkbox'],
    ],
],
'projects'=>[
    'table'=>'projects','title'=>'Projects',
    'blurb'=>'These appear on the "Our Work" page and as "Recent Projects" on the homepage.',
    'fields'=>[
        'title'=>['label'=>'Project Name','type'=>'text'],
        'category'=>['label'=>'Category','type'=>'select','options'=>['commercial'=>'Commercial','residential'=>'Residential','infrastructure'=>'Infrastructure','retrofit'=>'Retrofit'],'hint'=>'Used for the filter buttons on the Our Work page.'],
        'year'=>['label'=>'Year','type'=>'number'],
        'location'=>['label'=>'Location','type'=>'text'],
        'summary'=>['label'=>'Short Summary','type'=>'textarea','hint'=>'One sentence, shown on the project card.'],
        'detail'=>['label'=>'Full Detail','type'=>'textarea','hint'=>'The longer write-up shown on the project\'s own page.'],
        'scope'=>['label'=>'Scope of Work','type'=>'textarea','hint'=>'One item per line, e.g. "Structural Design". Shown as tags on the project page.'],
        'stats'=>['label'=>'Key Numbers','type'=>'textarea','hint'=>'One per line, written as Label=Value — e.g. Floors=9  or  Area=42,000 sq.ft'],
        'image'=>['label'=>'Photo','type'=>'image'],
        'sort_order'=>['label'=>'Display Order','type'=>'number','hint'=>'Lower numbers appear first.'],
        'is_active'=>['label'=>'Show on website','type'=>'checkbox'],
    ],
],
'gallery'=>[
    'table'=>'gallery','title'=>'Gallery',
    'blurb'=>'Photos shown on the site\'s Gallery page.',
    'fields'=>[
        'title'=>['label'=>'Caption','type'=>'text','hint'=>'A short caption shown when the photo is enlarged.'],
        'category'=>['label'=>'Category','type'=>'select','options'=>['sites'=>'Active Sites','structures'=>'Structures','team'=>'Team','completed'=>'Completed Work'],'hint'=>'Used for the filter buttons on the Gallery page.'],
        'image'=>['label'=>'Photo','type'=>'image'],
        'sort_order'=>['label'=>'Display Order','type'=>'number','hint'=>'Lower numbers appear first.'],
        'is_active'=>['label'=>'Show on website','type'=>'checkbox'],
    ],
],
'team'=>[
    'table'=>'team_members','title'=>'Team Members',
    'blurb'=>'The staff grid shown on the About page.',
    'fields'=>[
        'name'=>['label'=>'Full Name','type'=>'text'],
        'role'=>['label'=>'Job Title','type'=>'text'],
        'bio'=>['label'=>'Short Bio','type'=>'textarea','hint'=>'One or two sentences about this person.'],
        'image'=>['label'=>'Photo','type'=>'image'],
        'sort_order'=>['label'=>'Display Order','type'=>'number','hint'=>'Lower numbers appear first.'],
        'is_active'=>['label'=>'Show on website','type'=>'checkbox'],
    ],
],
'testimonials'=>[
    'table'=>'testimonials','title'=>'Testimonials',
    'blurb'=>'Client quotes shown on the homepage.',
    'fields'=>[
        'name'=>['label'=>'Client Name','type'=>'text'],
        'project'=>['label'=>'Related Project','type'=>'text','optional'=>true,'hint'=>'Optional — which project this quote is about.'],
        'quote'=>['label'=>'Quote','type'=>'textarea'],
        'sort_order'=>['label'=>'Display Order','type'=>'number','hint'=>'Lower numbers appear first.'],
        'is_active'=>['label'=>'Show on website','type'=>'checkbox'],
    ],
],
'stats'=>[
    'table'=>'site_stats','title'=>'Key Numbers','singular'=>'Key Number','label'=>'label',
    'blurb'=>'The big numbers shown on the homepage (e.g. "120+" Projects Completed). The first three also appear in the top banner.',
    'fields'=>[
        'value'=>['label'=>'Number / Value','type'=>'text','hint'=>'What is displayed large, e.g. 120+ or 15 yrs.'],
        'label'=>['label'=>'Label','type'=>'text','hint'=>'Short text under the number, e.g. Projects Completed.'],
        'sort_order'=>['label'=>'Display Order','type'=>'number','hint'=>'Lower numbers appear first.'],
        'is_active'=>['label'=>'Show on website','type'=>'checkbox'],
    ],
],
'workflow'=>[
    'table'=>'workflow_steps','title'=>'How We Work Steps','singular'=>'Step','label'=>'title',
    'blurb'=>'The numbered process steps shown on the homepage.',
    'fields'=>[
        'step'=>['label'=>'Step Number','type'=>'text','hint'=>'Shown in the circle, e.g. 01.'],
        'title'=>['label'=>'Step Title','type'=>'text'],
        'description'=>['label'=>'Description','type'=>'textarea','hint'=>'One or two sentences.'],
        'sort_order'=>['label'=>'Display Order','type'=>'number','hint'=>'Lower numbers appear first.'],
        'is_active'=>['label'=>'Show on website','type'=>'checkbox'],
    ],
],
'milestones'=>[
    'table'=>'milestones','title'=>'Company Timeline','singular'=>'Milestone','label'=>'year',
    'blurb'=>'The company history timeline on the About page.',
    'fields'=>[
        'year'=>['label'=>'Year','type'=>'text','hint'=>'e.g. 2015 or 2019-2020.'],
        'text'=>['label'=>'What happened','type'=>'textarea','hint'=>'One sentence.'],
        'sort_order'=>['label'=>'Display Order','type'=>'number','hint'=>'Lower numbers appear first (oldest first).'],
        'is_active'=>['label'=>'Show on website','type'=>'checkbox'],
    ],
],
'values'=>[
    'table'=>'company_values','title'=>'Company Values','singular'=>'Value','label'=>'title',
    'blurb'=>'The values/principles shown on the About page.',
    'fields'=>[
        'title'=>['label'=>'Value Name','type'=>'text'],
        'text'=>['label'=>'Description','type'=>'textarea','hint'=>'One or two sentences.'],
        'sort_order'=>['label'=>'Display Order','type'=>'number','hint'=>'Lower numbers appear first.'],
        'is_active'=>['label'=>'Show on website','type'=>'checkbox'],
    ],
],
'hours'=>[
    'table'=>'office_hours','title'=>'Office Hours','singular'=>'Day','label'=>'day_name','order'=>'day_order',
    'blurb'=>'Opening hours shown on the Contact page. One row per day, e.g. Sunday = 10:00 AM – 5:00 PM, or Saturday = Closed.',
    'fields'=>[
        'day_name'=>['label'=>'Day','type'=>'select','options'=>['Sunday'=>'Sunday','Monday'=>'Monday','Tuesday'=>'Tuesday','Wednesday'=>'Wednesday','Thursday'=>'Thursday','Friday'=>'Friday','Saturday'=>'Saturday'],'hint'=>'Each day can only be listed once.'],
        'hours'=>['label'=>'Hours','type'=>'text','hint'=>'e.g. 10:00 AM – 5:00 PM, or Closed.'],
        'day_order'=>['label'=>'Display Order','type'=>'number','hint'=>'0 = first row shown. Use 0-6 to follow the week.'],
    ],
],
];
$c=$cfg[$type]; $table=$c['table']; $message=''; $uploadError='';

/** Turns "Structural Design" into "structural-design", used only for the services table. */
function make_slug(string $text): string {
    $slug = strtolower(trim($text));
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    return trim($slug, '-') ?: 'item';
}

/** Ensures a slug is unique in the services table, appending -2, -3, etc. if needed. */
function unique_slug(PDO $pdo, string $baseSlug, int $excludeId = 0): string {
    $slug = $baseSlug;
    $suffix = 2;
    $st = $pdo->prepare("SELECT COUNT(*) FROM services WHERE slug = ? AND id != ?");
    while (true) {
        $st->execute([$slug, $excludeId]);
        if ((int) $st->fetchColumn() === 0) {
            return $slug;
        }
        $slug = $baseSlug . '-' . $suffix;
        $suffix++;
    }
}

function clean_data($type, $fieldDefs, $post) {
    $d = [];
    foreach ($fieldDefs as $key => $def) {
        if ($def['type'] === 'image' || $def['type'] === 'checkbox') continue; // handled separately
        $d[$key] = isset($post[$key]) ? trim((string) $post[$key]) : '';
    }
    if (array_key_exists('is_active', $fieldDefs)) {
        $d['is_active'] = isset($post['is_active']) ? 1 : 0;
    }
    if ($type === 'services') {
        $lines = preg_split('/\r?\n/', $d['features'] ?? '');
        $d['features'] = json_encode(array_values(array_filter(array_map('trim', $lines))), JSON_UNESCAPED_UNICODE);
    }
    if ($type === 'projects') {
        $lines = preg_split('/\r?\n/', $d['scope'] ?? '');
        $d['scope'] = json_encode(array_values(array_filter(array_map('trim', $lines))), JSON_UNESCAPED_UNICODE);
        $stats = [];
        foreach (preg_split('/\r?\n/', $d['stats'] ?? '') as $line) {
            if (strpos($line, '=') !== false) {
                [$k, $v] = array_map('trim', explode('=', $line, 2));
                if ($k !== '') $stats[$k] = $v;
            }
        }
        $d['stats'] = json_encode($stats, JSON_UNESCAPED_UNICODE);
        $d['year'] = (int) ($d['year'] ?? 0);
    }
    if (isset($d['sort_order'])) $d['sort_order'] = (int) $d['sort_order'];
    if (isset($d['day_order'])) $d['day_order'] = (int) $d['day_order'];
    return $d;
}

/**
 * Handles a real file upload for a "image"-type field. Validates
 * type/size, saves into assets/images/uploads/, and returns the new
 * filename — or the existing filename if no new file was chosen, so
 * editing other fields never blanks out the photo.
 */
function handle_image_upload_field(?string $existingFilename): ?string {
    if (empty($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
        return $existingFilename;
    }
    $file = $_FILES['image'];
    $existingFilename = $existingFilename ? basename($existingFilename) : null; // never trust a path from the browser
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Photo upload failed (error code ' . $file['error'] . '). Please try again.');
    }
    if ($file['size'] > 5 * 1024 * 1024) {
        throw new RuntimeException('That photo is too large — please use something under 5MB.');
    }
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    if (!isset($allowed[$mime])) {
        throw new RuntimeException('Please upload a JPG, PNG, WEBP or GIF photo.');
    }
    $uploadDir = __DIR__ . '/../assets/images/uploads/';
    if (!is_dir($uploadDir)) { mkdir($uploadDir, 0755, true); }
    $filename = uniqid('img_', true) . '.' . $allowed[$mime];
    if (!move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
        throw new RuntimeException('Could not save the uploaded photo. Check that the assets/images/uploads folder is writable.');
    }
    if ($existingFilename) {
        $oldPath = $uploadDir . $existingFilename;
        if (is_file($oldPath)) { @unlink($oldPath); }
    }
    return $filename;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    if (!csrf_check()) { http_response_code(400); exit('That request has expired. Go back, reload the page and try again.'); }
    $id = (int) $_POST['delete'];
    $st = $pdo->prepare("SELECT * FROM `$table` WHERE id=?"); $st->execute([$id]); $row = $st->fetch();
    if ($row && !empty($row['image'])) {
        $imgPath = __DIR__ . '/../assets/images/uploads/' . basename($row['image']);
        if (is_file($imgPath)) { @unlink($imgPath); }
    }
    $pdo->prepare("DELETE FROM `$table` WHERE id=?")->execute([$id]);
    header('Location: content.php?type=' . urlencode($type) . '&deleted=1'); exit;
}

$editing = null;
if (isset($_GET['edit'])) {
    $st = $pdo->prepare("SELECT * FROM `$table` WHERE id=?"); $st->execute([(int) $_GET['edit']]); $editing = $st->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['delete'])) {
    if (!csrf_check()) {
        $uploadError = 'This page had been open a while, so we couldn\'t confirm the request. Please try saving again.';
    } else {
        $d = clean_data($type, $c['fields'], $_POST);
        $id = (int) ($_POST['id'] ?? 0);

        if (array_key_exists('image', $c['fields'])) {
            try {
                $d['image'] = handle_image_upload_field($_POST['existing_image'] ?? null);
            } catch (RuntimeException $ex) {
                $uploadError = $ex->getMessage();
            }
        }

        // Slug is created once and never changed, so links like services.php#structural-design keep working after renames.
        if ($type === 'services' && $uploadError === '' && !$id) {
            $d['slug'] = unique_slug($pdo, make_slug($d['title'] ?: 'service'), 0);
        }

        if ($uploadError === '') {
            try {
                if ($id) {
                    $sets = []; $vals = [];
                    foreach ($d as $k => $v) { $sets[] = "`$k`=?"; $vals[] = $v; }
                    $vals[] = $id;
                    $pdo->prepare("UPDATE `$table` SET " . implode(',', $sets) . " WHERE id=?")->execute($vals);
                } else {
                    $cols = array_keys($d);
                    $pdo->prepare("INSERT INTO `$table` (`" . implode('`,`', $cols) . "`) VALUES (" . implode(',', array_fill(0, count($cols), '?')) . ")")->execute(array_values($d));
                }
                header('Location: content.php?type=' . urlencode($type) . '&saved=1'); exit;
            } catch (PDOException $ex) {
                log_error($ex, 'admin save ' . $table);
                $uploadError = ($ex->getCode() === '23000') ? 'That item already exists (for example, this day is already listed). Edit the existing one instead.' : 'Could not save — please check the fields and try again.' . (APP_DEBUG ? ' (' . $ex->getMessage() . ')' : '');
            }
        }
    }
}

$orderCol = $c['order'] ?? 'sort_order';
$items = $pdo->query("SELECT * FROM `$table` ORDER BY `$orderCol`,id DESC")->fetchAll();

function form_value($editing, $k, $type) {
    $v = $editing[$k] ?? '';
    if ($type === 'services' && $k === 'features') $v = implode("\n", json_decode($v ?: '[]', true) ?: []);
    if ($type === 'projects' && $k === 'scope') $v = implode("\n", json_decode($v ?: '[]', true) ?: []);
    if ($type === 'projects' && $k === 'stats') {
        $a = json_decode($v ?: '{}', true) ?: [];
        $v = '';
        foreach ($a as $x => $y) $v .= $x . '=' . $y . "\n";
    }
    return $v;
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?php echo htmlspecialchars($c['title']); ?> — Admin</title>
<meta name="robots" content="noindex, nofollow">
<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Karla:wght@400;500;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="top"><b>Strata &amp; Beam Admin — <?php echo htmlspecialchars($c['title']); ?></b> <a href="index.php">Dashboard</a></div>
<div class="wrap">
<a href="index.php" class="muted">&larr; Back to Dashboard</a>
<h1><?php echo htmlspecialchars($c['title']); ?></h1>
<p class="muted" style="margin-top:-10px;margin-bottom:22px;"><?php echo htmlspecialchars($c['blurb']); ?></p>

<?php if ($uploadError): ?><p class="err"><?php echo htmlspecialchars($uploadError); ?></p><?php endif; ?>
<?php if (isset($_GET['saved'])): ?><p class="ok">Saved — this change is now live on the website.</p><?php endif; ?>
<?php if (isset($_GET['deleted'])): ?><p class="ok">Deleted.</p><?php endif; ?>

<form class="form" method="post" enctype="multipart/form-data">
<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>">
<input type="hidden" name="id" value="<?php echo (int) ($editing['id'] ?? 0); ?>">
<h3 style="margin-top:0;"><?php echo $editing ? 'Editing: ' . htmlspecialchars($editing[$c['label'] ?? ''] ?? $editing['title'] ?? $editing['name'] ?? '') : 'Add New'; ?></h3>
<div class="grid">
<?php foreach ($c['fields'] as $k => $def): ?>
<div>
<?php if ($def['type'] === 'checkbox'): ?>
    <label style="margin-top:22px;"><input class="check" type="checkbox" name="<?php echo $k; ?>" value="1" <?php echo (!isset($editing) || !empty($editing[$k])) ? 'checked' : ''; ?>> <?php echo htmlspecialchars($def['label']); ?></label>

<?php elseif ($def['type'] === 'image'): ?>
    <label><?php echo htmlspecialchars($def['label']); ?></label>
    <?php
    $currentImage = $editing['image'] ?? '';
    $imagePath = $currentImage ? __DIR__ . '/../assets/images/uploads/' . $currentImage : '';
    ?>
    <?php if ($currentImage && is_file($imagePath)): ?>
    <div class="thumb-wrap">
        <img src="../assets/images/uploads/<?php echo htmlspecialchars($currentImage); ?>" alt="">
        <span class="hint">Current photo — choose a new file below to replace it.</span>
    </div>
    <?php endif; ?>
    <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($currentImage); ?>">
    <input type="file" name="image" accept="image/jpeg,image/png,image/webp,image/gif">
    <div class="hint">JPG, PNG, WEBP or GIF, up to 5MB.<?php echo $currentImage ? '' : ' A placeholder photo is shown on the site until you upload one.'; ?></div>

<?php elseif ($def['type'] === 'select'): ?>
    <label><?php echo htmlspecialchars($def['label']); ?></label>
    <select name="<?php echo $k; ?>">
        <?php $currentVal = $editing[$k] ?? array_key_first($def['options']); ?>
        <?php foreach ($def['options'] as $val => $optLabel): ?>
        <option value="<?php echo htmlspecialchars($val); ?>" <?php echo $currentVal === $val ? 'selected' : ''; ?>><?php echo htmlspecialchars($optLabel); ?></option>
        <?php endforeach; ?>
    </select>
    <?php if (!empty($def['hint'])): ?><div class="hint"><?php echo htmlspecialchars($def['hint']); ?></div><?php endif; ?>

<?php else: ?>
    <label><?php echo htmlspecialchars($def['label']); ?></label>
    <?php if ($def['type'] === 'textarea'): ?>
    <textarea name="<?php echo $k; ?>" <?php echo empty($def['optional']) ? 'required' : ''; ?>><?php echo htmlspecialchars(form_value($editing, $k, $type)); ?></textarea>
    <?php else: ?>
    <input name="<?php echo $k; ?>" value="<?php echo htmlspecialchars(form_value($editing, $k, $type)); ?>" <?php echo $def['type'] === 'number' ? 'type="number"' : ''; ?> <?php echo empty($def['optional']) ? 'required' : ''; ?>>
    <?php endif; ?>
    <?php if (!empty($def['hint'])): ?><div class="hint"><?php echo htmlspecialchars($def['hint']); ?></div><?php endif; ?>
<?php endif; ?>
</div>
<?php endforeach; ?>
</div>
<button name="save"><?php echo $editing ? 'Save Changes' : 'Add ' . htmlspecialchars($c['singular'] ?? rtrim($c['title'], 's')); ?></button>
<?php if ($editing): ?><a class="btn outline" href="content.php?type=<?php echo $type; ?>">Cancel</a><?php endif; ?>
</form>

<div class="list">
<h3>Current <?php echo htmlspecialchars($c['title']); ?> (<?php echo count($items); ?>)</h3>
<?php if (empty($items)): ?>
<p class="muted" style="padding:16px 0;">Nothing here yet — use the form above to add the first one.</p>
<?php endif; ?>
<?php foreach ($items as $it): ?>
<div class="item">
<?php if (array_key_exists('image', $c['fields'])): ?>
    <?php
    $thumbFile = $it['image'] ?? '';
    $thumbPath = $thumbFile ? __DIR__ . '/../assets/images/uploads/' . $thumbFile : '';
    $thumbSrc = ($thumbFile && is_file($thumbPath)) ? '../assets/images/uploads/' . htmlspecialchars($thumbFile) : 'https://picsum.photos/seed/' . urlencode($table . $it['id']) . '/120/90';
    ?>
    <img class="item-thumb" src="<?php echo $thumbSrc; ?>" alt="">
<?php endif; ?>
<b><?php echo htmlspecialchars((string)($it[$c['label'] ?? ''] ?? $it['title'] ?? $it['name'] ?? 'Item #' . $it['id'])); ?></b>
<?php if (isset($it['is_active']) && !$it['is_active']): ?><span class="hint" style="color:var(--a-red);">(hidden from website)</span><?php endif; ?>
<div class="actions">
<a class="btn outline" href="?type=<?php echo $type; ?>&edit=<?php echo $it['id']; ?>">Edit</a>
<form method="post" style="display:inline" onsubmit="return confirm('Delete this? This cannot be undone.')"><input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token()); ?>"><button class="btn danger" name="delete" value="<?php echo (int) $it['id']; ?>">Delete</button></form>
</div>
</div>
<?php endforeach; ?>
</div>
</div>
</body>
</html>
