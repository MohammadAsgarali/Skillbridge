<?php
/**
 * GET /api/recommendations.php -> JSON top recommendations for the logged-in student.
 * Member 4 (Kunal) - API layer
 */
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../matching/compatibility.php';
header('Content-Type: application/json');

if (!isStudentLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}
$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 5;
echo json_encode(getRankedOpportunities($conn, currentStudentId(), $limit));
