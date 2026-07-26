<?php if (!isset($conn)) { require_once __DIR__ . '/db.php'; } ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= isset($pageTitle) ? h($pageTitle) . ' | Structura Consultancy' : 'Structura Civil Engineering Consultancy'; ?></title>
<meta name="description" content="Structura Civil Engineering Consultancy - structural design, project supervision, and construction consultancy services.">

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Work+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<!-- Site CSS -->
<link rel="stylesheet" href="<?= SITE_URL ?>assets/css/style.css">
</head>
<body>

<!-- Top bar -->
<div class="topbar">
  <div class="container d-flex flex-wrap justify-content-between align-items-center">
    <div class="d-none d-md-flex gap-4">
      <span><i class="bi bi-geo-alt-fill me-1"></i>Putalisadak, Kathmandu, Nepal</span>
      <span><i class="bi bi-envelope-fill me-1"></i>info@structuraconsult.com</span>
    </div>
    <div class="d-flex gap-3 ms-auto ms-md-0">
      <span><i class="bi bi-telephone-fill me-1"></i>+977-1-4XXXXXX</span>
      <span><i class="bi bi-clock-fill me-1"></i>Sun–Fri: 9AM – 6PM</span>
    </div>
  </div>
</div>

<!-- Main Nav -->
<nav class="navbar navbar-expand-lg main-navbar sticky-top">
  <div class="container">
    <a class="navbar-brand" href="<?= SITE_URL ?>index.php">STRUCTURA<span>.</span></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav ms-auto align-items-lg-center">
        <li class="nav-item"><a class="nav-link <?= ($active ?? '') === 'home' ? 'active' : '' ?>" href="<?= SITE_URL ?>index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link <?= ($active ?? '') === 'about' ? 'active' : '' ?>" href="<?= SITE_URL ?>about.php">About Us</a></li>
        <li class="nav-item"><a class="nav-link <?= ($active ?? '') === 'services' ? 'active' : '' ?>" href="<?= SITE_URL ?>services.php">Our Services</a></li>
        <li class="nav-item"><a class="nav-link <?= ($active ?? '') === 'work' ? 'active' : '' ?>" href="<?= SITE_URL ?>our-work.php">Our Work</a></li>
        <li class="nav-item"><a class="nav-link <?= ($active ?? '') === 'gallery' ? 'active' : '' ?>" href="<?= SITE_URL ?>gallery.php">Gallery</a></li>
        <li class="nav-item ms-lg-2">
          <a class="btn btn-amber" href="<?= SITE_URL ?>contact.php">Contact Us</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
