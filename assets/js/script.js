document.addEventListener('DOMContentLoaded', function () {

  /* ---------- Mobile nav toggle ---------- */
  var navToggle = document.getElementById('navToggle');
  var mainNav = document.getElementById('mainNav');
  if (navToggle && mainNav) {
    navToggle.addEventListener('click', function () {
      var open = mainNav.classList.toggle('open');
      navToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
    mainNav.querySelectorAll('a').forEach(function (a) {
      a.addEventListener('click', function () { mainNav.classList.remove('open'); });
    });
  }

  /* ---------- Scroll reveal ---------- */
  var revealEls = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window && revealEls.length) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('in');
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15 });
    revealEls.forEach(function (el) { io.observe(el); });
  } else {
    revealEls.forEach(function (el) { el.classList.add('in'); });
  }

  /* ---------- Back to top ---------- */
  var backToTop = document.getElementById('backToTop');
  if (backToTop) {
    window.addEventListener('scroll', function () {
      backToTop.classList.toggle('visible', window.scrollY > 500);
    });
    backToTop.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  /* ---------- Sticky header shrink shadow on scroll ---------- */
  var header = document.getElementById('siteHeader');
  if (header) {
    window.addEventListener('scroll', function () {
      header.style.boxShadow = window.scrollY > 10 ? '0 4px 18px rgba(43,29,20,.08)' : 'none';
    });
  }

  /* ---------- Project / Gallery filters ---------- */
  document.querySelectorAll('[data-filter-group]').forEach(function (group) {
    var targetSelector = group.getAttribute('data-filter-target');
    var items = document.querySelectorAll(targetSelector);
    var buttons = group.querySelectorAll('.filter-btn');
    var emptyState = document.querySelector(group.getAttribute('data-empty-target') || '');

    buttons.forEach(function (btn) {
      btn.addEventListener('click', function () {
        buttons.forEach(function (b) { b.classList.remove('active'); });
        btn.classList.add('active');
        var filter = btn.getAttribute('data-filter');
        var visibleCount = 0;

        items.forEach(function (item) {
          var match = filter === 'all' || item.getAttribute('data-category') === filter;
          item.style.display = match ? '' : 'none';
          if (match) visibleCount++;
        });

        if (emptyState) emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
      });
    });
  });

  /* ---------- Gallery lightbox ---------- */
  var lightbox = document.getElementById('lightbox');
  if (lightbox) {
    var lbImg = lightbox.querySelector('img');
    var lbCap = lightbox.querySelector('.lb-cap');
    var galleryItems = Array.prototype.slice.call(document.querySelectorAll('.gallery-item'));
    var currentIndex = 0;

    function openLightbox(index) {
      currentIndex = index;
      var item = galleryItems[currentIndex];
      lbImg.src = item.getAttribute('data-full') || item.querySelector('img').src;
      lbImg.alt = item.getAttribute('data-title') || '';
      lbCap.textContent = item.getAttribute('data-title') || '';
      lightbox.classList.add('open');
    }
    function closeLightbox() { lightbox.classList.remove('open'); }
    function showRelative(delta) {
      var visibleItems = galleryItems.filter(function (it) { return it.style.display !== 'none'; });
      var pos = visibleItems.indexOf(galleryItems[currentIndex]);
      var next = visibleItems[(pos + delta + visibleItems.length) % visibleItems.length];
      currentIndex = galleryItems.indexOf(next);
      openLightbox(currentIndex);
    }

    galleryItems.forEach(function (item, idx) {
      item.addEventListener('click', function () { openLightbox(idx); });
    });
    var lbClose = lightbox.querySelector('.lb-close');
    var lbPrev = lightbox.querySelector('.lb-prev');
    var lbNext = lightbox.querySelector('.lb-next');
    if (lbClose) lbClose.addEventListener('click', closeLightbox);
    if (lbPrev) lbPrev.addEventListener('click', function () { showRelative(-1); });
    if (lbNext) lbNext.addEventListener('click', function () { showRelative(1); });
    lightbox.addEventListener('click', function (e) { if (e.target === lightbox) closeLightbox(); });
    document.addEventListener('keydown', function (e) {
      if (!lightbox.classList.contains('open')) return;
      if (e.key === 'Escape') closeLightbox();
      if (e.key === 'ArrowLeft') showRelative(-1);
      if (e.key === 'ArrowRight') showRelative(1);
    });
  }

  /* ---------- Contact form: lightweight client-side hint (server validates too) ---------- */
  var contactForm = document.getElementById('contactForm');
  if (contactForm) {
    contactForm.addEventListener('submit', function () {
      var submitBtn = contactForm.querySelector('button[type="submit"]');
      if (submitBtn) {
        submitBtn.setAttribute('disabled', 'disabled');
        submitBtn.textContent = 'Sending…';
      }
    });
  }

});
