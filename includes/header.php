<?php
require_once __DIR__ . '/data.php';
require_once __DIR__ . '/functions.php';
$pageTitle = $pageTitle ?? $site['name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo e($pageTitle); ?></title>
<meta name="description" content="<?php echo e($pageMeta ?? 'Structural, geotechnical and infrastructure engineering based in Kathmandu.'); ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Karla:wght@400;500;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="site-wrap">

<header class="site-header" id="siteHeader">
    <div class="container header-inner">
        <a href="index.php" class="brand">
            <span class="brand-mark"><?php icon('blueprint','brand-icon'); ?></span>
            <span class="brand-text">
                <span class="brand-name"><?php echo e($site['shortName']); ?></span>
                <span class="brand-sub">ENGINEERING</span>
            </span>
        </a>

        <nav class="main-nav" id="mainNav">
            <a href="index.php" class="<?php echo nav_class('index.php'); ?>">Home</a>
            <a href="about.php" class="<?php echo nav_class('about.php'); ?>">About Us</a>
            <a href="services.php" class="<?php echo nav_class('services.php'); ?>">Our Services</a>
            <a href="our-work.php" class="<?php echo nav_class('our-work.php'); ?>">Our Work</a>
            <a href="gallery.php" class="<?php echo nav_class('gallery.php'); ?>">Gallery</a>
            <a href="contact.php" class="<?php echo nav_class('contact.php'); ?> nav-cta">Contact Us</a>
        </nav>

        <button class="nav-toggle" id="navToggle" aria-label="Toggle menu" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>
    </div>
</header>
