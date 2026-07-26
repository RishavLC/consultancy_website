<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'Our Work';
$active = 'work';

// Simple category filter using a GET parameter (no JS needed)
$catFilter = isset($_GET['cat']) ? (int)$_GET['cat'] : 0;

$categories = [];
$catResult = mysqli_query($conn, "SELECT * FROM work_categories ORDER BY name ASC");
while ($row = mysqli_fetch_assoc($catResult)) { $categories[] = $row; }

if ($catFilter > 0) {
    $stmt = mysqli_prepare($conn, "SELECT w.*, c.name AS category_name FROM work_projects w
                                    LEFT JOIN work_categories c ON w.category_id = c.id
                                    WHERE w.category_id = ? ORDER BY w.created_at DESC");
    mysqli_stmt_bind_param($stmt, 'i', $catFilter);
    mysqli_stmt_execute($stmt);
    $projects_result = mysqli_stmt_get_result($stmt);
} else {
    $projects_result = mysqli_query($conn, "SELECT w.*, c.name AS category_name FROM work_projects w
                                              LEFT JOIN work_categories c ON w.category_id = c.id
                                              ORDER BY w.created_at DESC");
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="page-header">
  <div class="container">
    <h1>Our Work</h1>
    <nav><ol class="breadcrumb"><li class="breadcrumb-item"><a href="index.php">Home</a></li><li class="breadcrumb-item active">Our Work</li></ol></nav>
  </div>
</div>

<section class="py-5">
  <div class="container">

    <!-- Category filter (simple links, no JS) -->
    <div class="mb-4 text-center">
      <a href="our-work.php" class="btn btn-sm <?= $catFilter === 0 ? 'btn-amber' : 'btn-outline-secondary' ?> me-2 mb-2">All</a>
      <?php foreach ($categories as $c): ?>
        <a href="our-work.php?cat=<?= (int)$c['id'] ?>" class="btn btn-sm <?= $catFilter === (int)$c['id'] ? 'btn-amber' : 'btn-outline-secondary' ?> me-2 mb-2"><?= h($c['name']) ?></a>
      <?php endforeach; ?>
    </div>

    <div class="row g-4">
      <?php if (mysqli_num_rows($projects_result) > 0): ?>
        <?php while ($p = mysqli_fetch_assoc($projects_result)): ?>
          <div class="col-md-6 col-lg-4">
            <div class="work-card h-100">
              <img src="<?= h($p['cover_image']) ?>" alt="<?= h($p['title']) ?>">
              <div class="work-body">
                <?php if ($p['status'] === 'ongoing'): ?><span class="badge badge-status mb-2">Ongoing</span><?php endif; ?>
                <div class="text-amber small"><?= h($p['category_name'] ?? 'Project') ?></div>
                <h5><a href="work-detail.php?id=<?= (int)$p['id'] ?>" class="text-dark"><?= h($p['title']) ?></a></h5>
                <p class="text-muted small mb-1"><i class="bi bi-geo-alt"></i> <?= h($p['location']) ?></p>
                <p class="mb-0"><?= h(excerpt($p['short_desc'], 16)) ?></p>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="col-12 text-center text-muted">
          <p>No projects found in this category.</p>
        </div>
      <?php endif; ?>
    </div>

  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
