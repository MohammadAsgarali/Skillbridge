<?php
require_once 'config/constants.php';
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';
require_once 'matching/compatibility.php';
requireStudentLogin();

$studentId = currentStudentId();
$ranked = getRankedOpportunities($conn, $studentId); // all, sorted best-first

$pageTitle = "Recommendations";
include 'includes/header.php';
include 'includes/navbar.php';
?>
<div class="container py-4">
  <h2>Your Personalized Recommendations</h2>
  <p class="text-muted">Ranked highest match first, based on the skills in your profile.</p>
  <div class="row g-3">
    <?php foreach ($ranked as $opp): ?>
      <div class="col-md-6 col-lg-4">
        <div class="sb-card p-3 h-100 d-flex flex-column">
          <span class="badge sb-badge <?php echo matchBadgeClass($opp['match_percent']); ?> align-self-start"><?php echo $opp['match_percent']; ?>% Match</span>
          <h5 class="mt-2"><?php echo htmlspecialchars($opp['title']); ?></h5>
          <p class="text-muted mb-1"><?php echo htmlspecialchars($opp['company']); ?></p>
          <a href="opportunity.php?id=<?php echo $opp['id']; ?>" class="btn sb-btn-primary btn-sm mt-auto">View Details</a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<?php include 'includes/footer.php'; ?>
