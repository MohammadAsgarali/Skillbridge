<?php
/**
 * POST /api/bookmark.php (opportunity_id) -> toggles a bookmark on/off.
 * Member 4 (Kunal) - API layer
 */
require_once '../config/constants.php';
require_once '../config/database.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';
requireStudentLogin();

$studentId = currentStudentId();
$oppId = (int) ($_POST['opportunity_id'] ?? 0);

$chk = $conn->prepare("SELECT id FROM bookmarks WHERE student_id=? AND opportunity_id=?");
$chk->bind_param("ii", $studentId, $oppId);
$chk->execute();
$existing = $chk->get_result()->fetch_assoc();

if ($existing) {
    $del = $conn->prepare("DELETE FROM bookmarks WHERE id = ?");
    $del->bind_param("i", $existing['id']);
    $del->execute();
    setFlash('success', 'Bookmark removed.');
} else {
    $ins = $conn->prepare("INSERT INTO bookmarks (student_id, opportunity_id) VALUES (?, ?)");
    $ins->bind_param("ii", $studentId, $oppId);
    $ins->execute();
    setFlash('success', 'Opportunity bookmarked.');
}
redirect("opportunity.php?id=$oppId");
