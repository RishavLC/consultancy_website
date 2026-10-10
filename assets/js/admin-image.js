/* Admin image helpers: live preview for single image fields (upload or link) and for multi-photo uploads. */
(function () {
  'use strict';
  function fmt(n) { return n >= 1048576 ? (n / 1048576).toFixed(1) + ' MB' : Math.max(1, Math.round(n / 1024)) + ' KB'; }
  function ready(fn) { document.readyState !== 'loading' ? fn() : document.addEventListener('DOMContentLoaded', fn); }

  /* ---------- single image field: [data-imgfield] ---------- */
  function initField(box) {
    var max = parseInt(box.getAttribute('data-max') || '5242880', 10);
    var original = box.getAttribute('data-current') || '';
    var img = box.querySelector('.imgfield-img');
    var empty = box.querySelector('.imgfield-empty');
    var note = box.querySelector('.imgfield-note');
    var radios = box.querySelectorAll('input[type=radio]');
    var panes = { upload: box.querySelector('[data-pane=upload]'), link: box.querySelector('[data-pane=link]') };
    var file = box.querySelector('input[type=file]');
    var url = box.querySelector('input[type=url]');
    var objectUrl = null, timer = null, source = '';

    function show(src, msg, cls) {
      if (src) { img.hidden = false; empty.hidden = true; if (img.getAttribute('src') !== src) img.src = src; }
      else { img.hidden = true; empty.hidden = false; img.removeAttribute('src'); }
      note.textContent = msg || ''; note.className = 'imgfield-note' + (cls ? ' ' + cls : '');
    }
    function mode() { for (var i = 0; i < radios.length; i++) if (radios[i].checked) return radios[i].value; return 'upload'; }
    function setMode(m) { for (var i = 0; i < radios.length; i++) if (radios[i].value === m) radios[i].checked = true; refresh(); }

    function refresh() {
      var m = mode();
      if (panes.upload) panes.upload.hidden = (m !== 'upload');
      if (panes.link) panes.link.hidden = (m !== 'link');
      if (m === 'keep') { source = 'keep'; show(original, 'Current image.'); }
      else if (m === 'remove') { source = ''; show('', 'The image will be removed. A placeholder photo is shown on the website until you add another.', 'warn'); }
      else if (m === 'upload') { source = 'file'; previewFile(); }
      else if (m === 'link') { source = 'link'; previewLink(); }
    }

    function previewFile() {
      if (objectUrl) { URL.revokeObjectURL(objectUrl); objectUrl = null; }
      var f = file && file.files && file.files[0];
      if (!f) { show(original && mode() === 'keep' ? original : '', 'Choose a photo to see a preview here.'); return; }
      if (f.type && f.type.indexOf('image/') !== 0) { show('', '"' + f.name + '" is not an image.', 'bad'); return; }
      objectUrl = URL.createObjectURL(f);
      var big = f.size > max;
      show(objectUrl, f.name + ' — ' + fmt(f.size) + (big ? ' — too large (limit ' + fmt(max) + '). Please choose a smaller photo.' : ''), big ? 'bad' : 'good');
    }

    function previewLink() {
      var v = (url && url.value || '').trim();
      if (!v) { show('', 'Paste an image link to see a preview here.'); return; }
      if (!/^https?:\/\//i.test(v)) { show('', 'The link must start with http:// or https://', 'bad'); return; }
      show(v, 'Loading preview…');
    }

    img.addEventListener('load', function () {
      if (source === 'link') note.className = 'imgfield-note good', note.textContent = 'Link works ✓ (' + img.naturalWidth + ' × ' + img.naturalHeight + ' px)';
      else if (source === 'file' && note.className.indexOf('bad') < 0) note.textContent += ' (' + img.naturalWidth + ' × ' + img.naturalHeight + ' px)';
    });
    img.addEventListener('error', function () {
      if (source === 'link') { img.hidden = true; empty.hidden = false; note.className = 'imgfield-note bad'; note.textContent = 'Could not load an image from this link. Open the picture on its website, right-click it → "Copy image address", and paste that.'; }
      else if (source === 'file') { img.hidden = true; empty.hidden = false; note.className = 'imgfield-note bad'; note.textContent = 'This file could not be previewed — it may not be a valid image.'; }
    });

    for (var i = 0; i < radios.length; i++) radios[i].addEventListener('change', refresh);
    if (file) file.addEventListener('change', function () { setMode('upload'); });
    if (url) {
      url.addEventListener('input', function () { if (mode() !== 'link') setMode('link'); clearTimeout(timer); timer = setTimeout(previewLink, 350); });
      url.addEventListener('paste', function () { setTimeout(function () { if (mode() !== 'link') setMode('link'); previewLink(); }, 0); });
    }
    refresh();
  }

  /* ---------- several photos: file input with data-multi-preview="#gridId" ---------- */
  function initMulti(input) {
    var grid = document.querySelector(input.getAttribute('data-multi-preview'));
    var max = parseInt(input.getAttribute('data-max') || '5242880', 10);
    var urls = [];
    if (!grid) return;
    input.addEventListener('change', function () {
      urls.forEach(function (u) { URL.revokeObjectURL(u); }); urls = []; grid.innerHTML = '';
      var files = Array.prototype.slice.call(input.files || []);
      var total = 0;
      files.forEach(function (f) {
        total += f.size;
        var ok = (!f.type || f.type.indexOf('image/') === 0) && f.size <= max;
        var card = document.createElement('div'); card.className = 'mp-card' + (ok ? '' : ' bad');
        if (!f.type || f.type.indexOf('image/') === 0) { var u = URL.createObjectURL(f); urls.push(u); var im = document.createElement('img'); im.src = u; im.alt = ''; card.appendChild(im); }
        var cap = document.createElement('span');
        cap.textContent = f.name + ' · ' + fmt(f.size) + (f.size > max ? ' · too large' : (f.type && f.type.indexOf('image/') !== 0 ? ' · not an image' : ''));
        card.appendChild(cap); grid.appendChild(card);
      });
      var sum = document.querySelector(input.getAttribute('data-multi-summary') || '#none');
      if (sum) sum.textContent = files.length ? files.length + ' photo' + (files.length > 1 ? 's' : '') + ' selected (' + fmt(total) + ' in total)' : '';
    });
  }

  /* ---------- list of image links: textarea[data-link-preview="#gridId"] ---------- */
  function initLinks(area) {
    var grid = document.querySelector(area.getAttribute('data-link-preview')), timer = null;
    if (!grid) return;
    function render() {
      grid.innerHTML = '';
      area.value.split(/\r?\n/).map(function (l) { return l.trim(); }).filter(Boolean).slice(0, 20).forEach(function (l) {
        var card = document.createElement('div'); card.className = 'mp-card';
        var cap = document.createElement('span'); cap.textContent = l.replace(/^https?:\/\//i, '').slice(0, 48);
        if (!/^https?:\/\//i.test(l)) { card.className += ' bad'; cap.textContent = 'Not a valid link: ' + cap.textContent; card.appendChild(cap); grid.appendChild(card); return; }
        var im = document.createElement('img'); im.alt = ''; im.onerror = function () { card.className += ' bad'; im.remove(); cap.textContent = 'Could not load: ' + cap.textContent; };
        im.src = l; card.appendChild(im); card.appendChild(cap); grid.appendChild(card);
      });
    }
    area.addEventListener('input', function () { clearTimeout(timer); timer = setTimeout(render, 450); });
  }

  ready(function () {
    Array.prototype.forEach.call(document.querySelectorAll('[data-imgfield]'), initField);
    Array.prototype.forEach.call(document.querySelectorAll('input[data-multi-preview]'), initMulti);
    Array.prototype.forEach.call(document.querySelectorAll('textarea[data-link-preview]'), initLinks);
  });
})();
