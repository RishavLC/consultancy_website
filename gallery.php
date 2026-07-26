<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'Gallery';
$active = 'gallery';

$images = [];
$result = mysqli_query($conn, "SELECT * FROM gallery ORDER BY sort_order ASC, created_at DESC");
while ($row = mysqli_fetch_assoc($result)) { $images[] = $row; }

require_once __DIR__ . '/includes/header.php';
?>

<div class="page-header">
  <div class="container">
    <h1>Gallery</h1>
    <nav><ol class="breadcrumb"><li class="breadcrumb-item"><a href="index.php">Home</a></li><li class="breadcrumb-item active">Gallery</li></ol></nav>
  </div>
</div>

<section class="py-5">
  <div class="container">
    <?php if (count($images) > 0): ?>
      <div class="row">
        <?php foreach ($images as $img): ?>
          <div class="col-md-4 col-6 gallery-item">
            <a href="<?= h($img['image']) ?>" data-bs-toggle="modal" data-bs-target="#imgModal" onclick="document.getElementById('modalImg').src=this.getAttribute('href'); return false;">
              <img src="<?= h($img['image']) ?>" alt="<?= h($img['title']) ?>" class="img-fluid">
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p class="text-center text-muted">No gallery images added yet. Add images from the admin panel.</p>
    <?php endif; ?>
  </div>
</section>

<!-- Bootstrap modal used as a simple lightbox -->
<div class="modal fade" id="imgModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-dark">
      <button type="button" class="btn-close btn-close-white m-2 ms-auto" data-bs-dismiss="modal"></button>
      <img id="modalImg" src="" class="img-fluid" alt="Gallery preview">
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
