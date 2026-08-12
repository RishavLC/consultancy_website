<?php
$pageTitle = 'Our Services — Strata & Beam Engineering';
$pageMeta  = 'Structural design, geotechnical investigation, site supervision, infrastructure and project management services.';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-banner">
    <div class="banner-strip">
        <img src="https://picsum.photos/seed/strata-beam-s1/300/500" alt="">
        <img src="https://picsum.photos/seed/strata-beam-s2/300/500" alt="">
        <img src="https://picsum.photos/seed/strata-beam-s3/300/500" alt="">
        <img src="https://picsum.photos/seed/strata-beam-s4/300/500" alt="">
        <img src="https://picsum.photos/seed/strata-beam-s5/300/500" alt="">
    </div>
    <div class="container">
        <p class="crumb"><a href="index.php">Home</a> / Our Services</p>
        <span class="eyebrow">What We Do</span>
        <h1>Six services, <br>one accountable team.</h1>
        <p>Each service below can stand alone or combine into a single managed scope, depending on where your project is right now.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="grid-3" style="margin-bottom:20px;">
            <?php foreach ($services as $s): ?>
            <a href="#<?php echo e($s['id']); ?>" class="service-card reveal">
                <div class="service-icon"><?php icon($s['icon']); ?></div>
                <h3><?php echo e($s['title']); ?></h3>
                <p><?php echo e($s['short']); ?></p>
                <span class="card-link">Read Details <?php icon('arrow'); ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section" style="background:var(--sand-050); padding-top:20px;">
    <div class="container">
        <div class="dim-line"><span>Full Scope</span></div>
        <h2 style="margin-bottom:34px;">Service details</h2>
        <?php foreach ($services as $s): ?>
        <div class="service-detail reveal" id="<?php echo e($s['id']); ?>">
            <div class="service-icon"><?php icon($s['icon']); ?></div>
            <div>
                <h3><?php echo e($s['title']); ?></h3>
                <p><?php echo e($s['desc']); ?></p>
                <ul class="feature-list">
                    <?php foreach ($s['features'] as $f): ?>
                    <li><?php icon('check'); ?><span><?php echo e($f); ?></span></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="section alt blueprint-bg workflow">
    <div class="container">
        <div class="section-head center">
            <div class="dim-line"><span>How A Service Engagement Runs</span></div>
            <h2>Same six steps, every scope</h2>
            <p class="section-lede">Whichever service you start with, it moves through the same sequence — so design, permitting and site work never get out of order.</p>
        </div>
        <div class="workflow-track">
            <?php foreach ($workflow as $step): ?>
            <div class="wf-step reveal">
                <div class="wf-num"><?php echo e($step['step']); ?></div>
                <h4><?php echo e($step['title']); ?></h4>
                <p><?php echo e($step['desc']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section-tight">
    <div class="container">
        <div class="cta-band reveal">
            <div>
                <h2>Not sure which service you need?</h2>
                <p>Send us your site details — we'll recommend a scope during the first call, free of charge.</p>
            </div>
            <a href="contact.php" class="btn btn-light">Ask an Engineer <?php icon('arrow'); ?></a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
