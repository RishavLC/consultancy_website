<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'About Us';
$active = 'about';
require_once __DIR__ . '/includes/header.php';
?>

<div class="page-header">
  <div class="container">
    <h1>About Us</h1>
    <nav><ol class="breadcrumb"><li class="breadcrumb-item"><a href="index.php">Home</a></li><li class="breadcrumb-item active">About Us</li></ol></nav>
  </div>
</div>

<section class="py-5">
  <div class="container">
    <div class="row align-items-center g-4">
      <div class="col-lg-6">
        <img src="https://images.unsplash.com/photo-1541888946425-d81bb19240f5?auto=format&fit=crop&w=900&q=80" class="img-fluid" alt="Structura team on site">
      </div>
      <div class="col-lg-6">
        <h6 class="text-amber">Our Story</h6>
        <h2 class="mb-3">Two Decades Of Building With Integrity</h2>
        <p>Founded in 2001, Structura Consultancy began as a small structural design office and has grown into a full-service civil engineering consultancy serving residential, commercial, industrial and government clients.</p>
        <p>We believe good engineering is invisible when it works. That standard guides every drawing, inspection and report we produce.</p>
      </div>
    </div>
  </div>
</section>

<section class="py-5 bg-concrete">
  <div class="container">
    <div class="row g-4">
      <div class="col-md-6">
        <div class="service-card h-100">
          <div class="icon-box"><i class="bi bi-bullseye"></i></div>
          <h4>Our Mission</h4>
          <p class="mb-0">To deliver structurally sound, cost-efficient and safety-compliant engineering solutions our clients can depend on for decades.</p>
        </div>
      </div>
      <div class="col-md-6">
        <div class="service-card h-100">
          <div class="icon-box"><i class="bi bi-eye-fill"></i></div>
          <h4>Our Vision</h4>
          <p class="mb-0">To be the region's most trusted civil engineering consultancy, recognized for technical excellence and transparency.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="py-5">
  <div class="container">
    <div class="text-center mb-4">
      <h6 class="text-amber">How We Work</h6>
      <h2>Our Process</h2>
    </div>
    <div class="row g-4 text-center">
      <div class="col-md-3">
        <h3 class="text-amber">01</h3>
        <h5>Consultation</h5>
        <p class="mb-0">We understand your site, budget and objectives first.</p>
      </div>
      <div class="col-md-3">
        <h3 class="text-amber">02</h3>
        <h5>Design &amp; Analysis</h5>
        <p class="mb-0">Structural modelling and drawings prepared to code.</p>
      </div>
      <div class="col-md-3">
        <h3 class="text-amber">03</h3>
        <h5>Execution Support</h5>
        <p class="mb-0">Site supervision and quality checks during construction.</p>
      </div>
      <div class="col-md-3">
        <h3 class="text-amber">04</h3>
        <h5>Handover</h5>
        <p class="mb-0">Final inspection and as-built reporting delivered to you.</p>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
