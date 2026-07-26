<footer class="site-footer">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-4 col-md-6">
        <h5>STRUCTURA<span class="text-amber">.</span></h5>
        <p>A full-service civil engineering consultancy delivering structural design, site supervision and project management for residential, commercial and infrastructure works.</p>
        <div class="mt-3">
          <a href="#" class="social-icon text-white"><i class="bi bi-facebook"></i></a>
          <a href="#" class="social-icon text-white"><i class="bi bi-linkedin"></i></a>
          <a href="#" class="social-icon text-white"><i class="bi bi-instagram"></i></a>
          <a href="#" class="social-icon text-white"><i class="bi bi-twitter-x"></i></a>
        </div>
      </div>
      <div class="col-lg-2 col-md-6">
        <h5>Quick Links</h5>
        <ul class="list-unstyled">
          <li class="mb-2"><a href="<?= SITE_URL ?>about.php">About Us</a></li>
          <li class="mb-2"><a href="<?= SITE_URL ?>services.php">Our Services</a></li>
          <li class="mb-2"><a href="<?= SITE_URL ?>our-work.php">Our Work</a></li>
          <li class="mb-2"><a href="<?= SITE_URL ?>gallery.php">Gallery</a></li>
          <li class="mb-2"><a href="<?= SITE_URL ?>contact.php">Contact Us</a></li>
        </ul>
      </div>
      <div class="col-lg-3 col-md-6">
        <h5>Our Services</h5>
        <ul class="list-unstyled">
          <li class="mb-2"><a href="<?= SITE_URL ?>services.php">Structural Design</a></li>
          <li class="mb-2"><a href="<?= SITE_URL ?>services.php">Site Supervision</a></li>
          <li class="mb-2"><a href="<?= SITE_URL ?>services.php">Project Management</a></li>
          <li class="mb-2"><a href="<?= SITE_URL ?>services.php">Feasibility Studies</a></li>
        </ul>
      </div>
      <div class="col-lg-3 col-md-6">
        <h5>Get In Touch</h5>
        <ul class="list-unstyled">
          <li class="mb-2"><i class="bi bi-geo-alt-fill text-amber me-2"></i>Putalisadak, Kathmandu, Nepal</li>
          <li class="mb-2"><i class="bi bi-telephone-fill text-amber me-2"></i>+977-1-4XXXXXX</li>
          <li class="mb-2"><i class="bi bi-envelope-fill text-amber me-2"></i>info@structuraconsult.com</li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom d-flex flex-wrap justify-content-between">
      <span>&copy; <?= date('Y') ?> Structura Consultancy. All rights reserved.</span>
      <span>Built with Core PHP &amp; Bootstrap</span>
    </div>
  </div>
</footer>

<!-- Simple lightbox for gallery -->
<div id="simpleLightbox" style="display:flex;position:fixed;inset:0;background:rgba(10,10,10,.92);z-index:2000;align-items:center;justify-content:center;opacity:0;visibility:hidden;transition:.25s;">
  <img src="" alt="" style="max-width:90%;max-height:85%;border:4px solid #fff;">
</div>
<style>
#simpleLightbox.show{ opacity:1 !important; visibility:visible !important; }
</style>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= SITE_URL ?>assets/js/script.js"></script>
</body>
</html>
