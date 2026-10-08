<?php
/**
 * Automatic, safe database upgrades. Runs once (a flag file stops it re-running),
 * so sites installed from an older version get the new tables/columns without any manual SQL.
 */
function ensure_schema(PDO $pdo): void {
    $flag = __DIR__ . '/../data/schema_v2.flag';
    if (is_file($flag)) return;
    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS banners (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            image VARCHAR(255) NOT NULL,
            title VARCHAR(200) NOT NULL DEFAULT '',
            subtitle VARCHAR(300) NOT NULL DEFAULT '',
            is_active TINYINT(1) NOT NULL DEFAULT 1,
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        // projects table only exists after the main installer has run
        $hasProjects = $pdo->query("SHOW TABLES LIKE 'projects'")->fetchColumn();
        if ($hasProjects) {
            if (!$pdo->query("SHOW COLUMNS FROM projects LIKE 'completed_date'")->fetchColumn()) {
                $pdo->exec('ALTER TABLE projects ADD COLUMN completed_date DATE NULL AFTER year');
            }
            @file_put_contents($flag, date('c'));
        }
    } catch (Throwable $e) {
        error_log('[consultancy] schema upgrade failed: ' . $e->getMessage());
    }
}
