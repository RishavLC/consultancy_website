<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'Our Services';
$active = 'services';
require_once __DIR__ . '/includes/header.php';

$services = [
  ['bi-rulers', 'Structural Design & Analysis', 'Complete structural design for RCC, steel and composite structures, compliant with national building codes.'],
  ['bi-cone-striped', 'Construction Site Supervision', 'Dedicated site engineers ensure work is executed to drawing and specification with regular reporting.'],
  ['bi-clipboard-data', 'Project Management & Scheduling', 'End-to-end management of budgets, timelines and contractors to keep your project on track.'],
  ['bi-search', 'Feasibility & Soil Investigation', 'Geotechnical investigation and site feasibility studies before construction begins.'],
  ['bi-building-gear', 'Renovation & Retrofitting', 'Structural assessment and retrofit design for aging or damaged buildings.'],
  ['bi-signpost-split', 'Infrastructure & Roadworks', 'Design and supervision for roads, bridges, drainage and other public infrastructure.'],
  ['bi-file-earmark-check', 'Permit & Regulatory Approvals', 'We prepare and submit documentation needed for municipal construction approvals.'],
  ['bi-shield-check', 'Quality & Safety Audits', 'Independent structural and site-safety audits for existing buildings.'],
];
?>

<div class="page-header">
  <div class="container">
    <h1>Our Services</h1>
    <nav><ol class="breadcrumb"><li class="breadcrumb-item"><a href="index.php">Home</a></li><li class="breadcrumb-item active">Our Services</li></ol></nav>
  </div>
</div>

<section class="py-5">
  <div class="container">
    <div class="text-center mb-4">
      <h6 class="text-amber">What We Offer</h6>
      <h2>Consultancy Services Across The Project Lifecycle</h2>
    </div>
    <div class="row g-4">
      <?php foreach ($services as $s): ?>
        <div class="col-md-6 col-lg-4">
          <div class="service-card">
            <div class="icon-box"><i class="bi <?= h($s[0]) ?>"></i></div>
            <h5><?= h($s[1]) ?></h5>
            <p class="mb-0"><?= h($s[2]) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="py-5 bg-concrete text-center">
  <div class="container">
    <h2>Have A Project In Mind?</h2>
    <p class="text-muted mb-4">Talk to our engineering team for a free initial consultation.</p>
    <a href="contact.php" class="btn btn-amber">Request A Proposal</a>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
