<?php
/**
 * GET /api/career_recommendations.php
 * Returns personalized career paths for the logged-in student.
 */
require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../matching/career_recommendation.php';
header('Content-Type: application/json');

if (!isStudentLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$studentId = currentStudentId();
$stmt->bind_param("i", $studentId);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

$recommendations = sbCareerRecommendations($conn, $student);
$limit = isset($_GET['limit']) ? max(1, min(15, (int)$_GET['limit'])) : 5;

echo json_encode([
    'student_course' => $student['course'] ?? '',
    'formula' => [
        'degree_fit' => '40%',
        'skill_fit' => '50%',
        'profile_readiness' => '10%'
    ],
    'recommendations' => array_slice($recommendations, 0, $limit)
], JSON_UNESCAPED_SLASHES);
