<?php
require_once 'config/constants.php';
require_once 'config/database.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$pageTitle = "Home";
include 'includes/header.php';
include 'includes/navbar.php';
?>
<section class="sb-hero">
  <div class="container text-center">
    <h1>SKILLBRIDGE</h1>
    <p class="lead">Bridge Your Skills With the Right Opportunities</p>
    <p class="text-muted">Skill-based Internship &amp; Project Matching System</p>
    <?php if (!isset($_SESSION['student_id'])): ?>
      <a href="<?php echo BASE_URL; ?>register.php" class="btn sb-btn-primary btn-lg me-2">Get Started</a>
      <a href="<?php echo BASE_URL; ?>opportunities.php" class="btn sb-btn-outline btn-lg">Browse Opportunities</a>
    <?php else: ?>
      <a href="<?php echo BASE_URL; ?>dashboard.php" class="btn sb-btn-primary btn-lg">Go to Dashboard</a>
    <?php endif; ?>
  </div>
</section>

<section class="container py-5">
  <div class="row g-4 text-center">
    <div class="col-md-4">
      <div class="sb-card p-4 h-100">
        <h3>Skill-Based Matching</h3>
        <p>Your skills are matched against the requirements of every opportunity.</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="sb-card p-4 h-100">
        <h3>Compatibility Score</h3>
        <p>See a transparent 0-100% match score for every internship and project.</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="sb-card p-4 h-100">
        <h3>Skill Gap Detection</h3>
        <p>Know exactly which skills you're missing before you apply.</p>
      </div>
    </div>
  </div>
</section>
<?php include 'includes/footer.php'; ?>
