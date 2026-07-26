<?php
require_once __DIR__ . '/includes/auth.php';
$pageTitle = 'Gallery';
$active = 'gallery';

$msg = '';
$uploadDir = __DIR__ . '/../assets/uploads/gallery/';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM gallery WHERE id = $id");
    header('Location: gallery.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['image']['name'])) {
    $title = trim($_POST['title']);
    $work_project_id = (int)$_POST['work_project_id'] ?: null;
    $sort_order = (int)$_POST['sort_order'];

    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $fileName = 'gallery_' . time() . rand(100, 999) . '.' . $ext;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName)) {
        $imagePath = 'assets/uploads/gallery/' . $fileName;
        $stmt = mysqli_prepare($conn, "INSERT INTO gallery (title, image, work_project_id, sort_order) VALUES (?,?,?,?)");
        mysqli_stmt_bind_param($stmt, 'ssii', $title, $imagePath, $work_project_id, $sort_order);
        mysqli_stmt_execute($stmt);
        $msg = 'Image added to gallery.';
    }
}

$projects = mysqli_query($conn, "SELECT id, title FROM work_projects ORDER BY title ASC");
$projectsArr = [];
while ($p = mysqli_fetch_assoc($projects)) { $projectsArr[] = $p; }

$images = mysqli_query($conn, "SELECT g.*, w.title AS project_title FROM gallery g
                                LEFT JOIN work_projects w ON g.work_project_id = w.id
                                ORDER BY g.sort_order ASC, g.created_at DESC");

require_once __DIR__ . '/includes/admin-header.php';
?>

<h2 class="mb-4">Gallery</h2>
<?php if ($msg): ?><div class="alert alert-success"><?= h($msg) ?></div><?php endif; ?>

<div class="row g-4">
  <div class="col-lg-4">
    <div class="bg-white p-4">
      <h5>Add Image</h5>
      <form method="POST" enctype="multipart/form-data">
        <div class="mb-2">
          <label class="form-label">Title / Caption</label>
          <input type="text" name="title" class="form-control">
        </div>
        <div class="mb-2">
          <label class="form-label">Link To Project (optional)</label>
          <select name="work_project_id" class="form-select">
            <option value="">— None —</option>
            <?php foreach ($projectsArr as $p): ?>
              <option value="<?= $p['id'] ?>"><?= h($p['title']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-2">
          <label class="form-label">Sort Order</label>
          <input type="number" name="sort_order" class="form-control" value="0">
        </div>
        <div class="mb-3">
          <label class="form-label">Image *</label>
          <input type="file" name="image" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-amber">Upload</button>
      </form>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="row g-3">
      <?php while ($g = mysqli_fetch_assoc($images)): ?>
        <div class="col-md-4">
          <div class="bg-white p-2">
            <img src="../<?= h($g['image']) ?>" class="img-fluid mb-2" style="height:130px;width:100%;object-fit:cover;">
            <div class="small text-muted"><?= h($g['title'] ?: '—') ?></div>
            <div class="small text-amber"><?= h($g['project_title'] ?? '') ?></div>
            <a href="gallery.php?delete=<?= $g['id'] ?>" class="btn btn-sm btn-outline-danger mt-1" onclick="return confirm('Delete this image?')">Delete</a>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
