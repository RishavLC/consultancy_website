<?php
$pageTitle = 'Strata & Beam Engineering — Structural & Civil Engineering, Kathmandu';
$pageMeta  = 'Structural design, geotechnical investigation, site supervision and infrastructure engineering in Kathmandu, Nepal.';
require_once __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <div class="container hero-inner">
        <div class="hero-copy">
            <span class="eyebrow">Structural &amp; Civil Engineering</span>
            <h1>We engineer ground you<br>can actually <em>build on</em>.</h1>
            <p>Structural design, geotechnical investigation and site supervision for buildings and infrastructure across Nepal — engineered to code, reported in plain numbers.</p>
            <div class="hero-cta">
                <a href="contact.php" class="btn btn-primary">Start a Project <?php icon('arrow'); ?></a>
                <a href="our-work.php" class="btn btn-light">View Our Work</a>
            </div>
            <div class="hero-stats">
                <?php foreach (array_slice($stats, 0, 3) as $s): ?>
                <div class="stat"><b><?php echo e($s['value']); ?></b><span><?php echo e($s['label']); ?></span></div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="hero-mosaic">
            <div class="tile t1"><img src="https://picsum.photos/seed/strata-beam-1/700/700" alt="Structural steel frame under construction"><span class="tag">Structural Frame</span></div>
            <div class="tile t2"><img src="https://picsum.photos/seed/strata-beam-2/500/320" alt="Site engineer reviewing blueprints on site"><span class="tag">On-Site Review</span></div>
            <div class="tile t3"><img src="https://picsum.photos/seed/strata-beam-3/380/380" alt="Reinforced concrete column formwork"><span class="tag">Formwork</span></div>
            <div class="tile t4"><img src="https://picsum.photos/seed/strata-beam-4/300/620" alt="Completed commercial building exterior"><span class="tag">Completed Build</span></div>
            <div class="tile t5"><img src="https://picsum.photos/seed/strata-beam-5/460/380" alt="Road and drainage infrastructure survey"><span class="tag">Infrastructure</span></div>
            <div class="tile t6"><img src="https://picsum.photos/seed/strata-beam-6/900/280" alt="Geotechnical soil boring test on site"><span class="tag">Geotechnical</span></div>
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
            <a href="our-work.php?id=<?php echo (int)$p['id']; ?>" class="project-card reveal">
                <div class="project-thumb">
                    <img src="<?php echo e(image_url($p['image'], 'strata-beam-proj' . $p['id'], '600/440')); ?>" alt="<?php echo e($p['title']); ?>">
                    <span class="cat-tag"><?php echo e(ucfirst($p['category'])); ?></span>
                </div>
                <div class="project-body">
                    <div class="meta"><?php echo e($p['location']); ?> · <?php echo e((string)$p['year']); ?></div>
                    <h3><?php echo e($p['title']); ?></h3>
                    <p><?php echo e($p['summary']); ?></p>
                    <span class="card-link">View Project <?php icon('arrow'); ?></span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <div style="text-align:center;margin-top:40px;">
            <a href="our-work.php" class="btn btn-outline">See All Projects</a>
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
