<?php
$pageTitle = 'About Us — Strata & Beam Engineering';
$pageMeta  = 'Our story, values and the engineers behind Strata & Beam Engineering, Kathmandu.';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-banner">
    <div class="banner-strip">
        <img src="https://picsum.photos/seed/strata-beam-a1/300/500" alt="">
        <img src="https://picsum.photos/seed/strata-beam-a2/300/500" alt="">
        <img src="https://picsum.photos/seed/strata-beam-a3/300/500" alt="">
        <img src="https://picsum.photos/seed/strata-beam-a4/300/500" alt="">
        <img src="https://picsum.photos/seed/strata-beam-a5/300/500" alt="">
    </div>
    <div class="container">
        <p class="crumb"><a href="index.php">Home</a> / About Us</p>
        <span class="eyebrow">About The Practice</span>
        <h1>Engineers first, <br>consultants second.</h1>
        <p>Founded in <?php echo (int)$site['founded']; ?>, we've grown from a two-person structural office into a full-discipline civil engineering practice — without losing the habit of showing up on site.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="grid-2" style="align-items:center;">
            <div>
                <div class="dim-line"><span>Our Story</span></div>
                <h2>Built the way we build: one honest layer at a time</h2>
                <p class="section-lede">Strata &amp; Beam started with a simple complaint from clients: designers who never visited site, and contractors who never got checked. We built the company to close that gap — the same engineers who model the loads also walk the formwork before it's poured.</p>
                <p>Today that means structural design, geotechnical investigation, site supervision, infrastructure and project management sitting inside one office, reporting to one client contact, on one schedule.</p>
                <a href="services.php" class="btn btn-outline">See What We Do</a>
            </div>
            <div>
                <img src="https://picsum.photos/seed/strata-beam-office/700/560" alt="Strata & Beam engineering office team at work" style="border-radius:6px;">
            </div>
        </div>
    </div>
</section>

<section class="section alt blueprint-bg">
    <div class="container">
        <div class="grid-2">
            <div>
                <div class="dim-line"><span>Milestones</span></div>
                <h2>16 years, in order</h2>
                <div class="timeline" style="margin-top:30px;">
                    <?php foreach ($milestones as $m): ?>
                    <div class="timeline-item reveal">
                        <b><?php echo e($m['year']); ?></b>
                        <p><?php echo e($m['text']); ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div>
                <div class="dim-line"><span>What We Hold To</span></div>
                <h2>Four working values</h2>
                <div class="grid-2" style="margin-top:30px;gap:26px;">
                    <?php foreach ($values as $v): ?>
                    <div class="value-card reveal">
                        <h4><?php echo e($v['title']); ?></h4>
                        <p><?php echo e($v['text']); ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head center">
            <div class="dim-line"><span>The Team</span></div>
            <h2>Engineers of record</h2>
            <p class="section-lede">The people who sign the drawings and show up when it rains.</p>
        </div>
        <div class="grid-4">
            <?php foreach ($team as $member): ?>
            <div class="team-card reveal">
                <div class="team-photo"><img src="https://picsum.photos/seed/strata-beam-<?php echo e(str_replace(' ', '', strtolower($member['name']))); ?>/400/500" alt="<?php echo e($member['name']); ?>"></div>
                <h4><?php echo e($member['name']); ?></h4>
                <span class="role"><?php echo e($member['role']); ?></span>
                <p><?php echo e($member['bio']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section-tight" style="background:var(--sand-050);">
    <div class="container">
        <div class="cta-band reveal" style="background:var(--brown-900);">
            <div>
                <h2 style="color:#fff;">Want to work with this team?</h2>
                <p>We take on a limited number of active sites at a time so supervision never gets thin.</p>
            </div>
            <a href="contact.php" class="btn btn-primary">Contact Us <?php icon('arrow'); ?></a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
