<?php
/**
 * GET /api/opportunities.php -> JSON list of opportunities, each with the
 * logged-in student's match_percent if a session exists.
 * Member 4 (Kunal) - API layer
 */
require_once '../config/database.php';
require_once '../matching/compatibility.php';
if (session_status() === PHP_SESSION_NONE) session_start();
header('Content-Type: application/json');

$studentId = $_SESSION['student_id'] ?? null;
$result = $conn->query("SELECT * FROM opportunities ORDER BY created_at DESC");
$data = [];
while ($row = $result->fetch_assoc()) {
    $row['match_percent'] = $studentId ? calculateCompatibility($conn, $studentId, $row['id']) : null;
    $data[] = $row;
}
echo json_encode($data);
