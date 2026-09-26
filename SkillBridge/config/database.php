<?php
/**
 * Database connection
 */
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'skillbridge');
define('DB_PORT', 3307);

mysqli_report(MYSQLI_REPORT_OFF);
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

if ($conn->connect_error) {
    die("Database connection failed: " . htmlspecialchars($conn->connect_error) .
        "<br>Make sure XAMPP's Apache + MySQL are running and the SkillBridge database exists.");
}
$conn->set_charset('utf8mb4');

/**
 * Keep older SkillBridge installations compatible with the new domain-first
 * career roadmap. Missing profile columns are added automatically once.
 */
$requiredColumns = [
    'phone' => 'VARCHAR(30) DEFAULT NULL',
    'course' => 'VARCHAR(120) DEFAULT NULL',
    'graduation_year' => 'SMALLINT DEFAULT NULL',
    'college_location' => 'VARCHAR(120) DEFAULT NULL',
    'linkedin_url' => 'VARCHAR(255) DEFAULT NULL',
    'github_url' => 'VARCHAR(255) DEFAULT NULL',
    'instagram_url' => 'VARCHAR(255) DEFAULT NULL',
    'portfolio_url' => 'VARCHAR(255) DEFAULT NULL',
    'career_domain' => 'VARCHAR(100) DEFAULT NULL'
];

foreach ($requiredColumns as $column => $definition) {
    $columnEscaped = $conn->real_escape_string($column);
    $check = $conn->query("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='" .
        $conn->real_escape_string(DB_NAME) . "' AND TABLE_NAME='students' AND COLUMN_NAME='" .
        $columnEscaped . "' LIMIT 1");

    if ($check && $check->num_rows === 0) {
        $conn->query("ALTER TABLE students ADD COLUMN `{$column}` {$definition}");
    }
}
