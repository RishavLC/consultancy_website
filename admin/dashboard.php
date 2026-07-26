<?php
require_once __DIR__ . '/includes/auth.php';
$pageTitle = 'Dashboard';
$active = 'dashboard';

$bannerCount  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM banners"))['c'];
$workCount    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM work_projects"))['c'];
$galleryCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM gallery"))['c'];
$msgCount     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) c FROM contact_messages WHERE is_read = 0"))['c'];

require_once __DIR__ . '/includes/admin-header.php';
?>

<h2 class="mb-4">Welcome, <?= h($_SESSION['admin_username']) ?></h2>

<div class="row g-4">
  <div class="col-md-3">
    <div class="bg-white p-4 border-start border-4 border-warning">
      <div class="text-muted small">Banner Slides</div>
      <div class="fs-2 fw-bold"><?= $bannerCount ?></div>
      <a href="banners.php">Manage &rarr;</a>
    </div>
  </div>
  <div class="col-md-3">
    <div class="bg-white p-4 border-start border-4 border-warning">
      <div class="text-muted small">Work / Project Logs</div>
      <div class="fs-2 fw-bold"><?= $workCount ?></div>
      <a href="work.php">Manage &rarr;</a>
    </div>
  </div>
  <div class="col-md-3">
    <div class="bg-white p-4 border-start border-4 border-warning">
      <div class="text-muted small">Gallery Images</div>
      <div class="fs-2 fw-bold"><?= $galleryCount ?></div>
      <a href="gallery.php">Manage &rarr;</a>
    </div>
  </div>
  <div class="col-md-3">
    <div class="bg-white p-4 border-start border-4 border-warning">
      <div class="text-muted small">Unread Messages</div>
      <div class="fs-2 fw-bold"><?= $msgCount ?></div>
      <a href="messages.php">View &rarr;</a>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
