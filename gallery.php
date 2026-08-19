<?php
$pageTitle = 'Gallery — Strata & Beam Engineering';
$pageMeta  = 'Photos from active sites, completed structures and the team behind Strata & Beam Engineering.';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-banner">
    <div class="banner-strip">
        <img src="https://picsum.photos/seed/strata-beam-g1/300/500" alt="">
        <img src="https://picsum.photos/seed/strata-beam-g2/300/500" alt="">
        <img src="https://picsum.photos/seed/strata-beam-g3/300/500" alt="">
        <img src="https://picsum.photos/seed/strata-beam-g4/300/500" alt="">
        <img src="https://picsum.photos/seed/strata-beam-g5/300/500" alt="">
    </div>
    <div class="container">
        <p class="crumb"><a href="index.php">Home</a> / Gallery</p>
        <span class="eyebrow">In The Field</span>
        <h1>What the work<br>actually looks like.</h1>
        <p>Unfiltered shots from active pours, finished structures and the engineers running both.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="filter-row" data-filter-group data-filter-target=".js-gallery-item" data-empty-target="#galleryEmpty">
            <button class="filter-btn active" data-filter="all">All</button>
            <button class="filter-btn" data-filter="sites">Active Sites</button>
            <button class="filter-btn" data-filter="structures">Structures</button>
            <button class="filter-btn" data-filter="team">Team</button>
            <button class="filter-btn" data-filter="completed">Completed</button>
        </div>

        <div class="gallery-grid">
            <?php foreach ($gallery as $g): ?>
            <div class="gallery-item js-gallery-item reveal"
                 data-category="<?php echo e($g['category']); ?>"
                 data-title="<?php echo e($g['title']); ?>"
                 data-full="<?php echo e(image_url($g['image'], 'strata-beam-full' . $g['id'], '1100/800')); ?>">
                <img src="<?php echo e(image_url($g['image'], 'strata-beam-full' . $g['id'], '500/500')); ?>" alt="<?php echo e($g['title']); ?>" loading="lazy">
                <div class="g-cap"><?php echo e($g['title']); ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <p id="galleryEmpty" class="empty-state" style="display:none;">No photos in this category yet.</p>
    </div>
</section>

<div class="lightbox" id="lightbox">
    <button class="lb-close" aria-label="Close"><svg class="icon" viewBox="0 0 36 36" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M9 9l18 18M27 9L9 27"/></svg></button>
    <button class="lb-prev" aria-label="Previous"><svg class="icon" viewBox="0 0 36 36" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 18h24M20 8l10 10-10 10"/></svg></button>
    <img src="" alt="">
    <button class="lb-next" aria-label="Next"><svg class="icon" viewBox="0 0 36 36" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 18h24M20 8l10 10-10 10"/></svg></button>
    <div class="lb-cap"></div>
</div>

<section class="section-tight" style="background:var(--sand-050);">
    <div class="container">
        <div class="cta-band reveal">
            <div>
                <h2>Want photos from your own site?</h2>
                <p>Every active project gets weekly progress photography included in supervision.</p>
            </div>
            <a href="services.php#site-supervision" class="btn btn-light">See Site Supervision <?php icon('arrow'); ?></a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
