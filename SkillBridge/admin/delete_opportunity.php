<?php
require_once '../config/constants.php';
require_once '../config/database.php';
require_once '../includes/admin_auth.php';
require_once '../includes/functions.php';
requireAdminLogin();

$id = (int) ($_GET['id'] ?? 0);
$stmt = $conn->prepare("DELETE FROM opportunities WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
setFlash('success', 'Opportunity deleted.');
header("Location: opportunities.php");
exit;
