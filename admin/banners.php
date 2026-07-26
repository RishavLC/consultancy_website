<?php
require_once __DIR__ . '/includes/auth.php';
$pageTitle = 'Home Banners';
$active = 'banners';

$msg = '';
$uploadDir = __DIR__ . '/../assets/uploads/banners/';

// DELETE
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM banners WHERE id = $id");
    header('Location: banners.php');
    exit;
}

// ADD / EDIT
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $title = trim($_POST['title']);
    $subtitle = trim($_POST['subtitle']);
    $button_text = trim($_POST['button_text']);
    $button_link = trim($_POST['button_link']);
    $sort_order = (int)$_POST['sort_order'];
    $status = $_POST['status'];

    $imagePath = trim($_POST['existing_image'] ?? '');

    if (!empty($_FILES['image']['name'])) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $fileName = 'banner_' . time() . '.' . $ext;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName)) {
            $imagePath = 'assets/uploads/banners/' . $fileName;
        }
    }

    if ($id > 0) {
        $stmt = mysqli_prepare($conn, "UPDATE banners SET title=?, subtitle=?, image=?, button_text=?, button_link=?, sort_order=?, status=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, 'sssssisi', $title, $subtitle, $imagePath, $button_text, $button_link, $sort_order, $status, $id);
        mysqli_stmt_execute($stmt);
        $msg = 'Banner updated.';
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO banners (title, subtitle, image, button_text, button_link, sort_order, status) VALUES (?,?,?,?,?,?,?)");
        mysqli_stmt_bind_param($stmt, 'sssssis', $title, $subtitle, $imagePath, $button_text, $button_link, $sort_order, $status);
        mysqli_stmt_execute($stmt);
        $msg = 'Banner added.';
    }
}

// Edit fetch
$editRow = null;
if (isset($_GET['edit'])) {
    $eid = (int)$_GET['edit'];
    $editRow = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM banners WHERE id = $eid"));
}

$banners = mysqli_query($conn, "SELECT * FROM banners ORDER BY sort_order ASC");

require_once __DIR__ . '/includes/admin-header.php';
?>

<h2 class="mb-4">Home Banners</h2>
<?php if ($msg): ?><div class="alert alert-success"><?= h($msg) ?></div><?php endif; ?>

<div class="row g-4">
  <div class="col-lg-5">
    <div class="bg-white p-4">
      <h5><?= $editRow ? 'Edit Banner' : 'Add New Banner' ?></h5>
      <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $editRow['id'] ?? 0 ?>">
        <input type="hidden" name="existing_image" value="<?= h($editRow['image'] ?? '') ?>">

        <div class="mb-2">
          <label class="form-label">Title *</label>
          <input type="text" name="title" class="form-control" required value="<?= h($editRow['title'] ?? '') ?>">
        </div>
        <div class="mb-2">
          <label class="form-label">Subtitle</label>
          <input type="text" name="subtitle" class="form-control" value="<?= h($editRow['subtitle'] ?? '') ?>">
        </div>
        <div class="mb-2">
          <label class="form-label">Banner Image <?= $editRow ? '(leave empty to keep current)' : '*' ?></label>
          <input type="file" name="image" class="form-control" <?= $editRow ? '' : 'required' ?>>
          <?php if (!empty($editRow['image'])): ?>
            <img src="../<?= h($editRow['image']) ?>" style="height:60px;" class="mt-2">
          <?php endif; ?>
        </div>
        <div class="row">
          <div class="col-6 mb-2">
            <label class="form-label">Button Text</label>
            <input type="text" name="button_text" class="form-control" value="<?= h($editRow['button_text'] ?? '') ?>">
          </div>
          <div class="col-6 mb-2">
            <label class="form-label">Button Link</label>
            <input type="text" name="button_link" class="form-control" value="<?= h($editRow['button_link'] ?? '') ?>">
          </div>
        </div>
        <div class="row">
          <div class="col-6 mb-3">
            <label class="form-label">Sort Order</label>
            <input type="number" name="sort_order" class="form-control" value="<?= h($editRow['sort_order'] ?? 0) ?>">
          </div>
          <div class="col-6 mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
              <option value="active" <?= (($editRow['status'] ?? '') === 'active') ? 'selected' : '' ?>>Active</option>
              <option value="inactive" <?= (($editRow['status'] ?? '') === 'inactive') ? 'selected' : '' ?>>Inactive</option>
            </select>
          </div>
        </div>
        <button type="submit" class="btn btn-amber"><?= $editRow ? 'Update Banner' : 'Add Banner' ?></button>
        <?php if ($editRow): ?><a href="banners.php" class="btn btn-outline-secondary">Cancel</a><?php endif; ?>
      </form>
    </div>
  </div>

  <div class="col-lg-7">
    <div class="bg-white p-3">
      <table class="table align-middle mb-0">
        <thead><tr><th>Image</th><th>Title</th><th>Order</th><th>Status</th><th></th></tr></thead>
        <tbody>
        <?php while ($b = mysqli_fetch_assoc($banners)): ?>
          <tr>
            <td><img src="../<?= h($b['image']) ?>" style="height:45px;width:70px;object-fit:cover;"></td>
            <td><?= h($b['title']) ?></td>
            <td><?= $b['sort_order'] ?></td>
            <td><span class="badge bg-<?= $b['status'] === 'active' ? 'success' : 'secondary' ?>"><?= h($b['status']) ?></span></td>
            <td>
              <a href="banners.php?edit=<?= $b['id'] ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
              <a href="banners.php?delete=<?= $b['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this banner?')">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
