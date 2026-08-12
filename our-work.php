<?php
$pageTitle = 'Our Work — Strata & Beam Engineering';
$pageMeta  = 'Structural, residential, infrastructure and retrofit projects completed by Strata & Beam Engineering.';
require_once __DIR__ . '/includes/header.php';

// --- Dynamic single-project view driven entirely by the ?id= query param ---
$activeProject = null;
if (isset($_GET['id'])) {
    $requestedId = (int) $_GET['id'];
    foreach ($projects as $p) {
        if ($p['id'] === $requestedId) { $activeProject = $p; break; }
    }
}
?>

<section class="page-banner">
    <div class="banner-strip">
        <img src="https://picsum.photos/seed/strata-beam-w1/300/500" alt="">
        <img src="https://picsum.photos/seed/strata-beam-w2/300/500" alt="">
        <img src="https://picsum.photos/seed/strata-beam-w3/300/500" alt="">
        <img src="https://picsum.photos/seed/strata-beam-w4/300/500" alt="">
        <img src="https://picsum.photos/seed/strata-beam-w5/300/500" alt="">
    </div>
    <div class="container">
        <p class="crumb">
            <a href="index.php">Home</a> / <a href="our-work.php">Our Work</a><?php if ($activeProject): ?> / <?php echo e($activeProject['title']); ?><?php endif; ?>
        </p>
        <span class="eyebrow">Project Portfolio</span>
        <?php if ($activeProject): ?>
            <h1><?php echo e($activeProject['title']); ?></h1>
            <p><?php echo e($activeProject['location']); ?> · <?php echo e((string)$activeProject['year']); ?></p>
        <?php else: ?>
            <h1>Sites we've<br>stood on.</h1>
            <p>Six years of structural, infrastructure and retrofit work across Nepal — filter by project type below.</p>
        <?php endif; ?>
    </div>
</section>

<?php if ($activeProject): ?>

<section class="section">
    <div class="container">
        <div class="project-detail">
            <div class="project-image reveal">
                <img src="https://picsum.photos/seed/strata-beam-proj<?php echo (int)$activeProject['id']; ?>/900/680" alt="<?php echo e($activeProject['title']); ?>">
            </div>
            <div class="reveal">
                <span class="scope-chip" style="background:var(--brown-900); color:#fff;"><?php echo e(ucfirst($activeProject['category'])); ?></span>
                <h2 style="margin-top:16px;"><?php echo e($activeProject['title']); ?></h2>
                <p class="section-lede"><?php echo e($activeProject['summary']); ?></p>
                <p><?php echo e($activeProject['detail']); ?></p>

                <div class="project-spec-list">
                    <?php foreach ($activeProject['stats'] as $label => $value): ?>
                    <div><b><?php echo e($value); ?></b><span><?php echo e($label); ?></span></div>
                    <?php endforeach; ?>
                </div>

                <h4 style="margin-bottom:10px;">Scope of Work</h4>
                <div style="margin-bottom:26px;">
                    <?php foreach ($activeProject['scope'] as $scopeItem): ?>
                    <span class="scope-chip"><?php echo e($scopeItem); ?></span>
                    <?php endforeach; ?>
                </div>

                <a href="contact.php" class="btn btn-primary">Discuss A Similar Project <?php icon('arrow'); ?></a>
                <a href="our-work.php" class="btn btn-outline">&larr; All Projects</a>
            </div>
        </div>
    </div>
</section>

<section class="section" style="background:var(--sand-050); padding-top:50px;">
    <div class="container">
        <div class="dim-line"><span>More Projects</span></div>
        <h2 style="margin-bottom:30px;">Related work</h2>
        <div class="grid-3">
            <?php
            $related = array_filter($projects, function ($p) use ($activeProject) {
                return $p['id'] !== $activeProject['id'] && $p['category'] === $activeProject['category'];
            });
            if (count($related) < 3) { $related = array_filter($projects, function ($p) use ($activeProject) { return $p['id'] !== $activeProject['id']; }); }
            foreach (array_slice($related, 0, 3) as $p):
            ?>
            <a href="our-work.php?id=<?php echo (int)$p['id']; ?>" class="project-card reveal">
                <div class="project-thumb">
                    <img src="https://picsum.photos/seed/strata-beam-proj<?php echo (int)$p['id']; ?>/600/440" alt="<?php echo e($p['title']); ?>">
                    <span class="cat-tag"><?php echo e(ucfirst($p['category'])); ?></span>
                </div>
                <div class="project-body">
                    <div class="meta"><?php echo e($p['location']); ?> · <?php echo e((string)$p['year']); ?></div>
                    <h3><?php echo e($p['title']); ?></h3>
                    <span class="card-link">View Project <?php icon('arrow'); ?></span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php else: ?>

<section class="section">
    <div class="container">
        <div class="filter-row" data-filter-group data-filter-target=".js-project-item" data-empty-target="#projectsEmpty">
            <button class="filter-btn active" data-filter="all">All Projects</button>
            <button class="filter-btn" data-filter="commercial">Commercial</button>
            <button class="filter-btn" data-filter="residential">Residential</button>
            <button class="filter-btn" data-filter="infrastructure">Infrastructure</button>
            <button class="filter-btn" data-filter="retrofit">Retrofit</button>
        </div>

        <div class="grid-3" id="projectsGrid">
            <?php foreach ($projects as $p): ?>
            <a href="our-work.php?id=<?php echo (int)$p['id']; ?>" class="project-card js-project-item reveal" data-category="<?php echo e($p['category']); ?>">
                <div class="project-thumb">
                    <img src="https://picsum.photos/seed/strata-beam-proj<?php echo (int)$p['id']; ?>/600/440" alt="<?php echo e($p['title']); ?>">
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
        <p id="projectsEmpty" class="empty-state" style="display:none;">No projects in this category yet — check back soon or <a href="contact.php" style="color:var(--clay-500);">ask us directly</a>.</p>
    </div>
</section>

<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
