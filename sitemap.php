<?php
require_once __DIR__ . '/includes/data.php';
header('Content-Type: application/xml; charset=utf-8');
$base = base_url();
$urls = ['' => null, 'about.php' => null, 'services.php' => null, 'our-work.php' => null, 'gallery.php' => null, 'contact.php' => null];
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach (array_keys($urls) as $u) echo '  <url><loc>' . htmlspecialchars($base . '/' . $u, ENT_XML1) . '</loc></url>' . "\n";
foreach ($projects as $p) echo '  <url><loc>' . htmlspecialchars($base . '/our-work.php?id=' . (int)$p['id'], ENT_XML1) . '</loc></url>' . "\n";
echo '</urlset>';
