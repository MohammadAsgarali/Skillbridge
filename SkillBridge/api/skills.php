<?php
/**
 * GET /api/skills.php -> JSON list of all skills in the master catalogue.
 * Member 4 (Kunal) - API layer
 */
require_once '../config/database.php';
header('Content-Type: application/json');

$result = $conn->query("SELECT id, skill_name, category FROM skills ORDER BY skill_name");
$skills = [];
while ($row = $result->fetch_assoc()) $skills[] = $row;
echo json_encode($skills);
