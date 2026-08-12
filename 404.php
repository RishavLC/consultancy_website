<?php
http_response_code(404);
$pageTitle = 'Page Not Found — Strata & Beam Engineering';
require_once __DIR__ . '/includes/header.php';
?>
<section class="section" style="padding:160px 0; text-align:center;">
    <div class="container">
        <span class="eyebrow" style="justify-content:center;">Error 404</span>
        <h1>This page was never on the drawing set.</h1>
        <p class="section-lede" style="margin:0 auto 30px;">The page you're looking for doesn't exist or has moved.</p>
        <a href="index.php" class="btn btn-primary">Back to Home</a>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
