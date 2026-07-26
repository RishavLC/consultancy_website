<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = mysqli_prepare($conn, "SELECT w.*, c.name AS category_name FROM work_projects w
                                LEFT JOIN work_categories c ON w.category_id = c.id
                                WHERE w.id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$project = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$project) {
    header('Location: our-work.php');
    exit;
}

// Related gallery images for this project
$galleryStmt = mysqli_prepare($conn, "SELECT * FROM gallery WHERE work_project_id = ? ORDER BY sort_order ASC");
mysqli_stmt_bind_param($galleryStmt, 'i', $id);
mysqli_stmt_execute($galleryStmt);
$projectGallery = mysqli_stmt_get_result($galleryStmt);

$pageTitle = $project['title'];
$active = 'work';
require_once __DIR__ . '/includes/header.php';
?>

<div class="page-header">
  <div class="container">
    <h1><?= h($project['title']) ?></h1>
    <nav><ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php">Home</a></li>
      <li class="breadcrumb-item"><a href="our-work.php">Our Work</a></li>
      <li class="breadcrumb-item active"><?= h($project['title']) ?></li>
    </ol></nav>
  </div>
</div>

<section class="py-5">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-8">
        <img src="<?= h($project['cover_image']) ?>" class="img-fluid mb-4" alt="<?= h($project['title']) ?>">
        <h3>Project Overview</h3>
        <p><?= nl2br(h($project['full_desc'] ?: $project['short_desc'])) ?></p>

        <?php if (mysqli_num_rows($projectGallery) > 0): ?>
          <h3 class="mt-4">Project Gallery</h3>
          <div class="row g-3">
            <?php while ($g = mysqli_fetch_assoc($projectGallery)): ?>
              <div class="col-md-4">
                <img src="<?= h($g['image']) ?>" class="img-fluid" alt="<?= h($g['title']) ?>">
              </div>
            <?php endwhile; ?>
          </div>
        <?php endif; ?>
      </div>

      <div class="col-lg-4">
        <div class="service-card">
          <h5 class="mb-3">Project Details</h5>
          <ul class="list-unstyled mb-0">
            <li class="mb-2"><strong>Category:</strong> <?= h($project['category_name'] ?? '—') ?></li>
            <li class="mb-2"><strong>Client:</strong> <?= h($project['client_name'] ?: '—') ?></li>
            <li class="mb-2"><strong>Location:</strong> <?= h($project['location'] ?: '—') ?></li>
            <li class="mb-2"><strong>Start Date:</strong> <?= fdate($project['start_date']) ?: '—' ?></li>
            <li class="mb-2"><strong>End Date:</strong> <?= $project['end_date'] ? fdate($project['end_date']) : '—' ?></li>
            <li class="mb-0"><strong>Status:</strong> <span class="badge badge-status text-capitalize"><?= h($project['status']) ?></span></li>
          </ul>
        </div>
        <a href="contact.php" class="btn btn-amber w-100 mt-3">Discuss A Similar Project</a>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
