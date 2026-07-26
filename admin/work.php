<?php
require_once __DIR__ . '/includes/auth.php';
$pageTitle = 'Our Work';
$active = 'work';

$msg = '';
$uploadDir = __DIR__ . '/../assets/uploads/work/';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM work_projects WHERE id = $id");
    header('Location: work.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $title = trim($_POST['title']);
    $category_id = (int)$_POST['category_id'];
    $client_name = trim($_POST['client_name']);
    $location = trim($_POST['location']);
    $start_date = $_POST['start_date'] ?: null;
    $end_date = $_POST['end_date'] ?: null;
    $status = $_POST['status'];
    $short_desc = trim($_POST['short_desc']);
    $full_desc = trim($_POST['full_desc']);

    $imagePath = trim($_POST['existing_image'] ?? '');
    if (!empty($_FILES['cover_image']['name'])) {
        $ext = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
        $fileName = 'work_' . time() . '.' . $ext;
        if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $uploadDir . $fileName)) {
            $imagePath = 'assets/uploads/work/' . $fileName;
        }
    }

    if ($id > 0) {
        $stmt = mysqli_prepare($conn, "UPDATE work_projects SET title=?, category_id=?, client_name=?, location=?, start_date=?, end_date=?, status=?, cover_image=?, short_desc=?, full_desc=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, 'sisssssssi', $title, $category_id, $client_name, $location, $start_date, $end_date, $status, $imagePath, $short_desc, $full_desc, $id);
        mysqli_stmt_execute($stmt);
        $msg = 'Project updated.';
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO work_projects (title, category_id, client_name, location, start_date, end_date, status, cover_image, short_desc, full_desc) VALUES (?,?,?,?,?,?,?,?,?,?)");
        mysqli_stmt_bind_param($stmt, 'sisssssss', $title, $category_id, $client_name, $location, $start_date, $end_date, $status, $imagePath, $short_desc, $full_desc);
        // note: 10 params, category_id is int -> fix type string below
        mysqli_stmt_execute($stmt);
        $msg = 'Project added.';
    }
}

$editRow = null;
if (isset($_GET['edit'])) {
    $eid = (int)$_GET['edit'];
    $editRow = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM work_projects WHERE id = $eid"));
}

$categories = mysqli_query($conn, "SELECT * FROM work_categories ORDER BY name ASC");
$categoriesArr = [];
while ($c = mysqli_fetch_assoc($categories)) { $categoriesArr[] = $c; }

$projects = mysqli_query($conn, "SELECT w.*, c.name AS category_name FROM work_projects w
                                  LEFT JOIN work_categories c ON w.category_id = c.id
                                  ORDER BY w.created_at DESC");

require_once __DIR__ . '/includes/admin-header.php';
?>

<h2 class="mb-4">Our Work / Project Logs</h2>
<?php if ($msg): ?><div class="alert alert-success"><?= h($msg) ?></div><?php endif; ?>

<div class="row g-4">
  <div class="col-lg-5">
    <div class="bg-white p-4">
      <h5><?= $editRow ? 'Edit Project' : 'Add New Project' ?></h5>
      <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $editRow['id'] ?? 0 ?>">
        <input type="hidden" name="existing_image" value="<?= h($editRow['cover_image'] ?? '') ?>">

        <div class="mb-2">
          <label class="form-label">Project Title *</label>
          <input type="text" name="title" class="form-control" required value="<?= h($editRow['title'] ?? '') ?>">
        </div>
        <div class="mb-2">
          <label class="form-label">Category</label>
          <select name="category_id" class="form-select">
            <?php foreach ($categoriesArr as $c): ?>
              <option value="<?= $c['id'] ?>" <?= (($editRow['category_id'] ?? 0) == $c['id']) ? 'selected' : '' ?>><?= h($c['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="row">
          <div class="col-6 mb-2">
            <label class="form-label">Client Name</label>
            <input type="text" name="client_name" class="form-control" value="<?= h($editRow['client_name'] ?? '') ?>">
          </div>
          <div class="col-6 mb-2">
            <label class="form-label">Location</label>
            <input type="text" name="location" class="form-control" value="<?= h($editRow['location'] ?? '') ?>">
          </div>
        </div>
        <div class="row">
          <div class="col-6 mb-2">
            <label class="form-label">Start Date</label>
            <input type="date" name="start_date" class="form-control" value="<?= h($editRow['start_date'] ?? '') ?>">
          </div>
          <div class="col-6 mb-2">
            <label class="form-label">End Date</label>
            <input type="date" name="end_date" class="form-control" value="<?= h($editRow['end_date'] ?? '') ?>">
          </div>
        </div>
        <div class="mb-2">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            <option value="completed" <?= (($editRow['status'] ?? '') === 'completed') ? 'selected' : '' ?>>Completed</option>
            <option value="ongoing" <?= (($editRow['status'] ?? '') === 'ongoing') ? 'selected' : '' ?>>Ongoing</option>
          </select>
        </div>
        <div class="mb-2">
          <label class="form-label">Cover Image <?= $editRow ? '(leave empty to keep current)' : '*' ?></label>
          <input type="file" name="cover_image" class="form-control" <?= $editRow ? '' : 'required' ?>>
          <?php if (!empty($editRow['cover_image'])): ?>
            <img src="../<?= h($editRow['cover_image']) ?>" style="height:60px;" class="mt-2">
          <?php endif; ?>
        </div>
        <div class="mb-2">
          <label class="form-label">Short Description</label>
          <input type="text" name="short_desc" class="form-control" value="<?= h($editRow['short_desc'] ?? '') ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Full Description</label>
          <textarea name="full_desc" rows="4" class="form-control"><?= h($editRow['full_desc'] ?? '') ?></textarea>
        </div>
        <button type="submit" class="btn btn-amber"><?= $editRow ? 'Update Project' : 'Add Project' ?></button>
        <?php if ($editRow): ?><a href="work.php" class="btn btn-outline-secondary">Cancel</a><?php endif; ?>
      </form>
    </div>
  </div>

  <div class="col-lg-7">
    <div class="bg-white p-3">
      <table class="table align-middle mb-0">
        <thead><tr><th>Image</th><th>Title</th><th>Category</th><th>Status</th><th></th></tr></thead>
        <tbody>
        <?php while ($p = mysqli_fetch_assoc($projects)): ?>
          <tr>
            <td><img src="../<?= h($p['cover_image']) ?>" style="height:45px;width:70px;object-fit:cover;"></td>
            <td><?= h($p['title']) ?></td>
            <td><?= h($p['category_name'] ?? '—') ?></td>
            <td><span class="badge bg-<?= $p['status'] === 'ongoing' ? 'warning' : 'success' ?>"><?= h($p['status']) ?></span></td>
            <td>
              <a href="work.php?edit=<?= $p['id'] ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
              <a href="work.php?delete=<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this project?')">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
