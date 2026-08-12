<?php require_once __DIR__ . '/data.php'; require_once __DIR__ . '/functions.php'; ?>
    <footer class="site-footer">
        <div class="footer-band container">
            <div class="footer-col footer-brand">
                <a href="index.php" class="brand">
                    <span class="brand-mark"><?php icon('blueprint','brand-icon'); ?></span>
                    <span class="brand-text">
                        <span class="brand-name"><?php echo e($site['shortName']); ?></span>
                        <span class="brand-sub">ENGINEERING</span>
                    </span>
                </a>
                <p>Structural, geotechnical &amp; infrastructure engineering, built on-site reporting and code-first design since <?php echo (int)$site['founded']; ?>.</p>
                <div class="social-row">
                    <a href="#" aria-label="Facebook"><?php icon('facebook'); ?></a>
                    <a href="#" aria-label="Instagram"><?php icon('instagram'); ?></a>
                    <a href="#" aria-label="LinkedIn"><?php icon('linkedin'); ?></a>
                </div>
            </div>

            <div class="footer-col">
                <h4>Quick Links</h4>
                <ul class="footer-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="services.php">Our Services</a></li>
                    <li><a href="our-work.php">Our Work</a></li>
                    <li><a href="gallery.php">Gallery</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Services</h4>
                <ul class="footer-links">
                    <?php foreach (array_slice($services, 0, 5) as $s): ?>
                    <li><a href="services.php#<?php echo e($s['id']); ?>"><?php echo e($s['title']); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Get In Touch</h4>
                <ul class="footer-contact">
                    <li><?php icon('pin'); ?><span><?php echo e($site['address']); ?></span></li>
                    <li><?php icon('phone'); ?><span><?php echo e($site['phone']); ?></span></li>
                    <li><?php icon('mail'); ?><span><?php echo e($site['email']); ?></span></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom container">
            <p>&copy; <?php echo date('Y'); ?> <?php echo e($site['name']); ?>. All rights reserved.</p>
            <p class="footer-tag">Engineered on ground truth.</p>
        </div>
    </footer>

</div><!-- /.site-wrap -->

<button type="button" class="back-to-top-fab" id="backToTop" aria-label="Back to top">
    <?php icon('arrow','icon-up'); ?>
</button>

<script src="assets/js/script.js"></script>
</body>
</html>
