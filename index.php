<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'Home';
$active = 'home';

// Get active banner slides (dynamic - managed from admin panel)
$banners = [];
$result = mysqli_query($conn, "SELECT * FROM banners WHERE status='active' ORDER BY sort_order ASC");
while ($row = mysqli_fetch_assoc($result)) {
    $banners[] = $row;
}

// Get latest 3 projects for the homepage preview (dynamic)
$latestWork = [];
$result2 = mysqli_query($conn, "SELECT w.*, c.name AS category_name FROM work_projects w
                                 LEFT JOIN work_categories c ON w.category_id = c.id
                                 ORDER BY w.created_at DESC LIMIT 3");
while ($row = mysqli_fetch_assoc($result2)) {
    $latestWork[] = $row;
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- ============ HOME BANNER (dynamic, Bootstrap Carousel) ============ -->
<?php if (count($banners) > 0): ?>
<div id="homeBanner" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-indicators">
    <?php foreach ($banners as $i => $b): ?>
      <button type="button" data-bs-target="#homeBanner" data-bs-slide-to="<?= $i ?>" class="<?= $i === 0 ? 'active' : '' ?>"></button>
    <?php endforeach; ?>
  </div>
  <div class="carousel-inner">
    <?php foreach ($banners as $i => $b): ?>
      <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>" style="background-image:url('<?= h($b['image']) ?>');">
        <div class="carousel-caption-custom">
          <div class="container">
            <h1><?= h($b['title']) ?></h1>
            <?php if ($b['subtitle']): ?><p><?= h($b['subtitle']) ?></p><?php endif; ?>
            <?php if ($b['button_text'] && $b['button_link']): ?>
              <a href="<?= h($b['button_link']) ?>" class="btn btn-amber btn-lg">
                <?= h($b['button_text']) ?>
              </a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <?php if (count($banners) > 1): ?>
    <button class="carousel-control-prev" type="button" data-bs-target="#homeBanner" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#homeBanner" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>
  <?php endif; ?>
</div>
<?php else: ?>
  <div class="bg-charcoal text-center text-white py-5">
    <div class="container py-5">
      <h1>Building The Infrastructure Of Tomorrow</h1>
      <p>Add banner slides from the admin panel to feature them here.</p>
      <a href="services.php" class="btn btn-amber btn-lg">Our Services</a>
    </div>
  </div>
<?php endif; ?>

<!-- ============ ABOUT PREVIEW (static) ============ -->
<section class="py-5">
  <div class="container">
    <div class="row align-items-center g-4">
      <div class="col-lg-6">
        <img src="https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=900&q=80" class="img-fluid" alt="Engineers reviewing blueprints">
      </div>
      <div class="col-lg-6">
        <h6 class="text-amber">Who We Are</h6>
        <h2 class="mb-3">Engineering Consultancy Built On Precision And Trust</h2>
        <p>Structura Consultancy has spent over two decades delivering structural design, site supervision, and full project management for residential, commercial, industrial and public infrastructure projects.</p>
        <p>Our team of licensed civil engineers combines technical rigor with practical, on-site experience so every project is delivered safely and on schedule.</p>
        <a href="about.php" class="btn btn-amber mt-2">More About Us</a>
      </div>
    </div>
  </div>
</section>

<!-- ============ SERVICES PREVIEW (static) ============ -->
<section class="py-5 bg-concrete">
  <div class="container">
    <div class="text-center mb-4">
      <h6 class="text-amber">What We Do</h6>
      <h2>Our Core Services</h2>
    </div>
    <div class="row g-4">
      <div class="col-md-6 col-lg-3">
        <div class="service-card">
          <div class="icon-box"><i class="bi bi-rulers"></i></div>
          <h5>Structural Design</h5>
          <p class="mb-0">Structural analysis and drawings compliant with national codes.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="service-card">
          <div class="icon-box"><i class="bi bi-cone-striped"></i></div>
          <h5>Site Supervision</h5>
          <p class="mb-0">On-ground quality and safety supervision from start to handover.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="service-card">
          <div class="icon-box"><i class="bi bi-clipboard-data"></i></div>
          <h5>Project Management</h5>
          <p class="mb-0">Scheduling, budgeting and contractor coordination.</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="service-card">
          <div class="icon-box"><i class="bi bi-search"></i></div>
          <h5>Feasibility Studies</h5>
          <p class="mb-0">Site and soil feasibility studies before you build.</p>
        </div>
      </div>
    </div>
    <div class="text-center mt-4">
      <a href="services.php" class="btn btn-amber">View All Services</a>
    </div>
  </div>
</section>

<!-- ============ OUR WORK PREVIEW (dynamic) ============ -->
<section class="py-5">
  <div class="container">
    <div class="text-center mb-4">
      <h6 class="text-amber">Project Logs</h6>
      <h2>Recent Work</h2>
    </div>
    <div class="row g-4">
      <?php if (count($latestWork) > 0): ?>
        <?php foreach ($latestWork as $w): ?>
          <div class="col-md-4">
            <div class="work-card h-100">
              <img src="<?= h($w['cover_image']) ?>" alt="<?= h($w['title']) ?>">
              <div class="work-body">
                <?php if ($w['status'] === 'ongoing'): ?><span class="badge badge-status mb-2">Ongoing</span><?php endif; ?>
                <div class="text-amber small"><?= h($w['category_name'] ?? 'Project') ?></div>
                <h5><a href="work-detail.php?id=<?= (int)$w['id'] ?>" class="text-dark"><?= h($w['title']) ?></a></h5>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-12 text-center text-muted">
          <p>No projects added yet. Add project logs from the admin panel to display them here.</p>
        </div>
      <?php endif; ?>
    </div>
    <div class="text-center mt-4">
      <a href="our-work.php" class="btn btn-amber">View Full Portfolio</a>
    </div>
  </div>
</section>

<!-- ============ CTA ============ -->
<section class="py-5 bg-charcoal text-center">
  <div class="container">
    <h2 class="text-white">Have A Project In Mind?</h2>
    <p class="text-white-50 mb-4">Talk to our engineering team for a free initial consultation.</p>
    <a href="contact.php" class="btn btn-amber">Get In Touch</a>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
