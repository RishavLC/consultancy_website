<?php
/** Dynamic site data loaded from MySQL. Run install.php once after creating the database. */
require_once __DIR__ . '/../config/database.php';

try {
    $pdo = db();
} catch (Throwable $e) {
    http_response_code(500);
    exit('<h1>Database connection failed</h1><p>Open <code>config/database.php</code>, set your MySQL credentials, create the <code>consultancy_db</code> database, then run <a href="install.php">install.php</a>.</p>');
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
];

$services = rows('SELECT * FROM services WHERE is_active=1 ORDER BY sort_order,id');
foreach ($services as &$s) $s['features'] = json_array($s['features']); unset($s);
$workflow = rows('SELECT step,title,description AS `desc` FROM workflow_steps WHERE is_active=1 ORDER BY sort_order,id');
$projects = rows('SELECT * FROM projects WHERE is_active=1 ORDER BY year DESC,id DESC');
foreach ($projects as &$p) { $p['id']=(int)$p['id']; $p['year']=(int)$p['year']; $p['scope']=json_array($p['scope']); $p['stats']=json_array($p['stats']); } unset($p);
$gallery = rows('SELECT id,category,title,image FROM gallery WHERE is_active=1 ORDER BY sort_order,id');
$team = rows('SELECT name,role,bio,image FROM team_members WHERE is_active=1 ORDER BY sort_order,id');
$milestones = rows('SELECT year,text FROM milestones WHERE is_active=1 ORDER BY sort_order,id');
$values = rows('SELECT title,text FROM company_values WHERE is_active=1 ORDER BY sort_order,id');
$testimonials = rows('SELECT name,project,quote FROM testimonials WHERE is_active=1 ORDER BY sort_order,id');
$officeHours = [];
foreach (rows('SELECT day_name,hours FROM office_hours ORDER BY day_order') as $r) $officeHours[$r['day_name']] = $r['hours'];
$stats = rows('SELECT value,label FROM site_stats WHERE is_active=1 ORDER BY sort_order,id');
