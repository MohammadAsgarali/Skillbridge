<?php
require_once '../config/constants.php';
require_once '../config/database.php';
require_once '../includes/admin_auth.php';
requireAdminLogin();

$students = $conn->query("SELECT COUNT(*) c FROM students")->fetch_assoc()['c'];
$opps = $conn->query("SELECT COUNT(*) c FROM opportunities")->fetch_assoc()['c'];
$apps = $conn->query("SELECT COUNT(*) c FROM applications")->fetch_assoc()['c'];
$skillsCount = $conn->query("SELECT COUNT(*) c FROM skills")->fetch_assoc()['c'];

$pageTitle = "Admin Dashboard";
include '../includes/header.php';
?>
<nav class="navbar sb-navbar"><div class="container">
  <span class="navbar-brand sb-brand">SkillBridge Admin</span>
  <div><a href="opportunities.php" class="btn sb-btn-outline btn-sm me-2">Opportunities</a>
  <a href="skills.php" class="btn sb-btn-outline btn-sm me-2">Skills</a>
  <a href="applications.php" class="btn sb-btn-outline btn-sm me-2">Applications</a>
  <a href="logout.php" class="btn sb-btn-outline btn-sm">Logout</a></div>
</div></nav>
<div class="container py-4">
  <h2>Admin Dashboard</h2>
  <div class="row g-3 mt-2">
    <div class="col-md-3"><div class="sb-card p-3 text-center"><h3><?php echo $students; ?></h3><p class="mb-0">Students</p></div></div>
    <div class="col-md-3"><div class="sb-card p-3 text-center"><h3><?php echo $opps; ?></h3><p class="mb-0">Opportunities</p></div></div>
    <div class="col-md-3"><div class="sb-card p-3 text-center"><h3><?php echo $apps; ?></h3><p class="mb-0">Applications</p></div></div>
    <div class="col-md-3"><div class="sb-card p-3 text-center"><h3><?php echo $skillsCount; ?></h3><p class="mb-0">Skills in Catalogue</p></div></div>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
