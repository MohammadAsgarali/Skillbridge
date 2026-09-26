<?php
/**
 * POST /api/apply.php  (opportunity_id) -> inserts an application row.
 * Member 4 (Kunal) - API layer
 */
require_once '../config/constants.php';
require_once '../config/database.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';
requireStudentLogin();

$studentId = currentStudentId();
$oppId = (int) ($_POST['opportunity_id'] ?? 0);

$stmt = $conn->prepare("INSERT IGNORE INTO applications (student_id, opportunity_id) VALUES (?, ?)");
$stmt->bind_param("ii", $studentId, $oppId);
$stmt->execute();

setFlash('success', 'Application submitted successfully.');
redirect("opportunity.php?id=$oppId");
