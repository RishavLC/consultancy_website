<?php
require_once __DIR__ . '/data.php';
require_once __DIR__ . '/functions.php';
$pageTitle = $pageTitle ?? $site['name'];
$pageMetaText = $pageMeta ?? 'Structural, geotechnical and infrastructure engineering based in Kathmandu.';
$canonical = base_url() . '/' . ltrim(basename($_SERVER['SCRIPT_NAME']) === 'index.php' ? '' : basename($_SERVER['SCRIPT_NAME']), '/');
if (basename($_SERVER['SCRIPT_NAME']) === 'work.php' && !empty($activeProject)) $canonical .= '?id=' . (int)$activeProject['id'];
$ogImage = $ogImage ?? '';
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo e($pageTitle); ?></title>
<meta name="description" content="<?php echo e($pageMetaText); ?>">
<link rel="canonical" href="<?php echo e($canonical); ?>">
<link rel="icon" type="image/svg+xml" href="assets/images/favicon.svg">
<meta name="theme-color" content="#2b1d14">
<meta property="og:type" content="website">
<meta property="og:site_name" content="<?php echo e($site['name']); ?>">
<meta property="og:title" content="<?php echo e($pageTitle); ?>">
<meta property="og:description" content="<?php echo e($pageMetaText); ?>">
<meta property="og:url" content="<?php echo e($canonical); ?>">
<?php if ($ogImage): ?><meta property="og:image" content="<?php echo e($ogImage); ?>">
<meta name="twitter:card" content="summary_large_image"><?php else: ?><meta name="twitter:card" content="summary"><?php endif; ?>
<?php if (!empty($noindex)): ?><meta name="robots" content="noindex, follow"><?php endif; ?>
<script type="application/ld+json"><?php
echo json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'ProfessionalService',
    'name' => $site['name'],
    'url' => base_url() . '/',
    'telephone' => $site['phone'],
    'email' => $site['email'],
    'foundingDate' => (string)$site['founded'],
    'address' => ['@type' => 'PostalAddress', 'streetAddress' => $site['address'], 'addressCountry' => 'NP'],
    'sameAs' => array_values(array_filter([$site['facebook'], $site['instagram'], $site['linkedin']])),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
?></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Karla:wght@400;500;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<a class="skip-link" href="#main">Skip to main content</a>

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
            <a href="work.php" class="<?php echo nav_class('work.php'); ?>">Our Work</a>
            <a href="gallery.php" class="<?php echo nav_class('gallery.php'); ?>">Gallery</a>
            <a href="contact.php" class="<?php echo nav_class('contact.php'); ?> nav-cta">Contact Us</a>
        </nav>

        <button class="nav-toggle" id="navToggle" aria-label="Toggle menu" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>
    </div>
</header>
<main id="main">
