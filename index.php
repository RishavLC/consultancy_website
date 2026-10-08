<?php
$pageTitle = 'Strata & Beam Engineering — Structural & Civil Engineering, Kathmandu';
$pageMeta  = 'Structural design, geotechnical investigation, site supervision and infrastructure engineering in Kathmandu, Nepal.';
require_once __DIR__ . '/includes/header.php';
?>

<?php $bannerImg = $banner ? 'assets/images/uploads/' . rawurlencode($banner['image']) : ''; ?>
<section class="hero<?php echo $banner ? ' hero-has-banner' : ''; ?>"<?php if ($banner): ?> style="--hero-img:url('<?php echo e($bannerImg); ?>')"<?php endif; ?>>
    <div class="container hero-inner">
        <div class="hero-copy">
            <span class="eyebrow">Structural &amp; Civil Engineering</span>
            <?php if ($banner && trim($banner['title']) !== ''): ?>
            <h1><?php echo e($banner['title']); ?></h1>
            <?php else: ?>
            <h1>We engineer ground you<br>can actually <em>build on</em>.</h1>
            <?php endif; ?>
            <?php if ($banner && trim($banner['subtitle']) !== ''): ?>
            <p><?php echo e($banner['subtitle']); ?></p>
            <?php else: ?>
            <p>Structural design, geotechnical investigation and site supervision for buildings and infrastructure across Nepal — engineered to code, reported in plain numbers.</p>
            <?php endif; ?>
            <div class="hero-cta">
                <a href="contact.php" class="btn btn-primary">Start a Project <?php icon('arrow'); ?></a>
                <a href="work.php" class="btn btn-light">View Our Work</a>
            </div>
            <div class="hero-stats">
                <?php foreach (array_slice($stats, 0, 3) as $s): ?>
                <div class="stat"><b><?php echo e($s['value']); ?></b><span><?php echo e($s['label']); ?></span></div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if (!$banner): ?>
        <div class="hero-mosaic">
            <div class="tile t1"><img src="https://picsum.photos/seed/strata-beam-1/700/700" alt="Structural steel frame under construction"><span class="tag">Structural Frame</span></div>
            <div class="tile t2"><img src="https://picsum.photos/seed/strata-beam-2/500/320" alt="Site engineer reviewing blueprints on site"><span class="tag">On-Site Review</span></div>
            <div class="tile t3"><img src="https://picsum.photos/seed/strata-beam-3/380/380" alt="Reinforced concrete column formwork"><span class="tag">Formwork</span></div>
            <div class="tile t4"><img src="https://picsum.photos/seed/strata-beam-4/300/620" alt="Completed commercial building exterior"><span class="tag">Completed Build</span></div>
            <div class="tile t5"><img src="https://picsum.photos/seed/strata-beam-5/460/380" alt="Road and drainage infrastructure survey"><span class="tag">Infrastructure</span></div>
            <div class="tile t6"><img src="https://picsum.photos/seed/strata-beam-6/900/280" alt="Geotechnical soil boring test on site"><span class="tag">Geotechnical</span></div>
        </div>
        <?php endif; ?>
    </div>
</section>

<section class="section section-tight welcome-intro">
    <div class="container">
        <div class="section-head center" style="margin-bottom:0;">
            <div class="dim-line"><span>About Us</span></div>
            <h2><?php echo e($site['welcome']); ?></h2>
            <p class="section-lede"><?php echo e($site['intro']); ?></p>
            <p style="margin-top:18px;"><a href="about.php" class="btn btn-outline">More About Us</a></p>
        </div>
    </div>
</section>

<section class="stats-strip">
    <div class="container stats-grid">
        <?php foreach ($stats as $s): ?>
        <div class="stat"><b><?php echo e($s['value']); ?></b><span><?php echo e($s['label']); ?></span></div>
        <?php endforeach; ?>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head center">
            <div class="dim-line"><span>What We Do</span></div>
            <h2>Six disciplines. One site team.</h2>
            <p class="section-lede">From the first soil sample to the final walkthrough — every discipline your project needs, coordinated under one roof.</p>
        </div>
        <div class="grid-3">
            <?php foreach (array_slice($services, 0, 6) as $s): ?>
            <div class="service-card reveal">
                <div class="service-icon"><?php icon($s['icon']); ?></div>
                <h3><?php echo e($s['title']); ?></h3>
                <p><?php echo e($s['short']); ?></p>
                <a href="services.php#<?php echo e($s['id']); ?>" class="card-link">Learn More <?php icon('arrow'); ?></a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section alt blueprint-bg workflow">
    <div class="container">
        <div class="section-head center">
            <div class="dim-line"><span>How We Work</span></div>
            <h2>A fixed six-step process</h2>
            <p class="section-lede">The same sequence on every project, so nothing gets skipped between survey and handover.</p>
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

<section class="section">
    <div class="container">
        <div class="section-head center">
            <div class="dim-line"><span>Selected Work</span></div>
            <h2>Recently completed</h2>
        </div>
        <div class="grid-3">
            <?php foreach (array_slice($projects, 0, 3) as $p): ?>
            <a href="work.php?id=<?php echo (int)$p['id']; ?>" class="project-card reveal">
                <div class="project-thumb">
                    <img src="<?php echo e(image_url($p['image'], 'strata-beam-proj' . $p['id'], '600/440')); ?>" alt="<?php echo e($p['title']); ?>">
                    <span class="cat-tag"><?php echo e(ucfirst($p['category'])); ?></span>
                </div>
                <div class="project-body">
                    <div class="meta"><?php echo e($p['location']); ?> · <?php echo e(project_date_label($p)); ?></div>
                    <h3><?php echo e($p['title']); ?></h3>
                    <p><?php echo e($p['summary']); ?></p>
                    <span class="card-link">View Project <?php icon('arrow'); ?></span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <div style="text-align:center;margin-top:40px;">
            <a href="work.php" class="btn btn-outline">See All Projects</a>
        </div>
    </div>
</section>

<section class="section section-tight" style="background:var(--sand-050);">
    <div class="container">
        <div class="section-head center">
            <div class="dim-line"><span>Client Feedback</span></div>
            <h2>What clients tell us after handover</h2>
        </div>
        <div class="grid-3">
            <?php foreach ($testimonials as $t): ?>
            <div class="testi-card reveal">
                <p>"<?php echo e($t['quote']); ?>"</p>
                <span class="testi-name"><?php echo e($t['name']); ?></span><br>
                <span class="testi-project"><?php echo e($t['project']); ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section section-tight contact-preview">
    <div class="container">
        <div class="section-head center">
            <div class="dim-line"><span>Contact</span></div>
            <h2>Talk to an engineer</h2>
        </div>
        <div class="grid-3">
            <div class="service-card"><h3>Visit</h3><p><?php echo e($site['address']); ?></p></div>
            <div class="service-card"><h3>Call</h3><p><a href="tel:<?php echo e(preg_replace('/[^0-9+]/', '', $site['phone'])); ?>"><?php echo e($site['phone']); ?></a></p></div>
            <div class="service-card"><h3>Email</h3><p><a href="mailto:<?php echo e($site['email']); ?>"><?php echo e($site['email']); ?></a></p></div>
        </div>
        <p style="text-align:center;margin-top:30px;"><a href="contact.php" class="btn btn-primary">Send an Enquiry <?php icon('arrow'); ?></a></p>
    </div>
</section>

<section class="section-tight">
    <div class="container">
        <div class="cta-band reveal">
            <div>
                <h2>Have a site that needs engineering?</h2>
                <p>Tell us where it is and what you're building — we'll reply with next steps within one business day.</p>
            </div>
            <a href="contact.php" class="btn btn-light">Get In Touch <?php icon('arrow'); ?></a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
