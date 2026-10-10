<?php
/** Dynamic site data loaded from MySQL. Run install.php once after creating the database. */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

try {
    $pdo = db();
    $pdo->query('SELECT 1 FROM site_settings LIMIT 1'); // fails if the installer has not been run yet
} catch (Throwable $e) {
    log_error($e, 'DB connect');
    http_response_code(503);
    $hint = APP_DEBUG ? '<p><small>' . htmlspecialchars($e->getMessage()) . '</small></p>' : '';
    exit('<!doctype html><meta charset="utf-8"><title>Temporarily unavailable</title><body style="font-family:sans-serif;max-width:560px;margin:15vh auto;padding:0 20px"><h1>We\'ll be right back</h1><p>The website is temporarily unavailable. Please try again in a few minutes.</p>' . $hint . '</body>');
}

function rows(string $sql, array $params = []): array {
    global $pdo;
    $st = $pdo->prepare($sql); $st->execute($params); return $st->fetchAll();
}
function row(string $sql, array $params = []): ?array {
    global $pdo;
    $st = $pdo->prepare($sql); $st->execute($params); $r = $st->fetch(); return $r ?: null;
}
function json_array(?string $json): array { $v = json_decode($json ?? '[]', true); return is_array($v) ? $v : []; }

$settings = [];
foreach (rows('SELECT setting_key, setting_value FROM site_settings') as $r) $settings[$r['setting_key']] = $r['setting_value'];
$site = [
    'name' => $settings['name'] ?? 'Strata & Beam Engineering',
    'shortName' => $settings['shortName'] ?? 'Strata & Beam',
    'phone' => $settings['phone'] ?? '+977-1-4567890',
    'email' => $settings['email'] ?? 'info@strataandbeam.com',
    'address' => $settings['address'] ?? 'Putalisadak Road, Kathmandu 44600, Nepal',
    'founded' => (int)($settings['founded'] ?? 2008),
    'facebook' => $settings['facebook'] ?? '',
    'instagram' => $settings['instagram'] ?? '',
    'linkedin' => $settings['linkedin'] ?? '',
    'mapQuery' => $settings['mapQuery'] ?? '',
    'welcome' => $settings['welcome'] ?? ('Welcome to ' . ($settings['name'] ?? 'our practice')),
    'intro' => $settings['intro'] ?? 'We are a civil and structural engineering consultancy. Our engineers design the structure, investigate the ground and supervise the build, so one accountable team carries your project from the first soil sample to the final handover.',
    'mission' => $settings['mission'] ?? 'To deliver safe, economical and code-compliant engineering that clients can build with confidence, and to stay on site long enough to prove it.',
    'vision' => $settings['vision'] ?? 'To be the engineering practice that owners, builders and authorities across Nepal trust first for resilient, well-documented construction.',
];

$services = rows("SELECT id AS db_id, slug AS id, icon, title, short_text AS short, description AS `desc`, features, sort_order FROM services WHERE is_active=1 ORDER BY sort_order,id");
foreach ($services as &$s) $s['features'] = json_array($s['features']); unset($s);
$workflow = rows('SELECT step,title,description AS `desc` FROM workflow_steps WHERE is_active=1 ORDER BY sort_order,id');
$projects = rows('SELECT * FROM projects WHERE is_active=1 ORDER BY year DESC,id DESC');
foreach ($projects as &$p) { $p['id']=(int)$p['id']; $p['year']=(int)$p['year']; $p['completed_date']=$p['completed_date'] ?? null; $p['scope']=json_array($p['scope']); $p['stats']=json_array($p['stats']); } unset($p);
$gallery = rows('SELECT id,category,title,image FROM gallery WHERE is_active=1 ORDER BY sort_order,id');
$team = rows('SELECT name,role,bio,image FROM team_members WHERE is_active=1 ORDER BY sort_order,id');
$milestones = rows('SELECT year,text FROM milestones WHERE is_active=1 ORDER BY sort_order,id');
$values = rows('SELECT title,text FROM company_values WHERE is_active=1 ORDER BY sort_order,id');
$testimonials = rows('SELECT name,project,quote FROM testimonials WHERE is_active=1 ORDER BY sort_order,id');
$officeHours = [];
foreach (rows('SELECT day_name,hours FROM office_hours ORDER BY day_order') as $r) $officeHours[$r['day_name']] = $r['hours'];
$stats = rows('SELECT value,label FROM site_stats WHERE is_active=1 ORDER BY sort_order,id');

// Home-page banner (admin: manage-banner.php). Fails quietly so the site still works if the table is missing.
$banner = null;
try {
    $b = $pdo->query('SELECT * FROM banners WHERE is_active=1 ORDER BY id DESC LIMIT 1')->fetch();
    if ($b && !empty($b['image']) && (is_remote_image($b['image']) || is_file(__DIR__ . '/../assets/images/uploads/' . basename($b['image'])))) $banner = $b;
} catch (Throwable $e) { log_error($e, 'banner load'); }
