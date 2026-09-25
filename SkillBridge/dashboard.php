<?php
require_once 'config/constants.php';
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';
require_once 'matching/compatibility.php';
requireStudentLogin();

$studentId = currentStudentId();
$topMatches = getRankedOpportunities($conn, $studentId, 3);

$appCountStmt = $conn->prepare("SELECT COUNT(*) c FROM applications WHERE student_id = ?");
$appCountStmt->bind_param("i", $studentId);
$appCountStmt->execute();
$appCount = $appCountStmt->get_result()->fetch_assoc()['c'];

$skillCountStmt = $conn->prepare("SELECT COUNT(*) c FROM student_skills WHERE student_id = ?");
$skillCountStmt->bind_param("i", $studentId);
$skillCountStmt->execute();
$skillCount = $skillCountStmt->get_result()->fetch_assoc()['c'];

$pageTitle = "Dashboard";
include 'includes/header.php';
include 'includes/navbar.php';
?>
<div class="container py-4">
  <h2>Welcome, <?php echo htmlspecialchars($_SESSION['student_name']); ?> 👋</h2>
  <?php showFlash(); ?>

  <div class="row g-3 my-2">
    <div class="col-md-4">
      <div class="sb-card p-3 text-center"><h3><?php echo $skillCount; ?></h3><p class="mb-0">Skills Added</p></div>
    </div>
    <div class="col-md-4">
      <div class="sb-card p-3 text-center"><h3><?php echo $appCount; ?></h3><p class="mb-0">Applications Sent</p></div>
    </div>
    <div class="col-md-4">
      <div class="sb-card p-3 text-center">
        <h3><?php echo count($topMatches) ? $topMatches[0]['match_percent'] . '%' : '0%'; ?></h3>
        <p class="mb-0">Best Current Match</p>
      </div>
    </div>
  </div>

  <div class="sb-card p-3 mb-4 d-flex align-items-center justify-content-between gap-3">
    <div>
      <span class="badge sb-badge badge-high mb-2"><i class="bi bi-compass-fill me-1"></i> New Career Module</span>
      <h5 class="mb-1">Get your personalized Career Roadmap</h5>
      <p class="text-muted mb-0">See what skills, courses, projects and learning resources you should focus on next.</p>
    </div>
    <a href="<?php echo BASE_URL; ?>career_roadmap.php" class="btn sb-btn-primary">Explore Roadmap <i class="bi bi-arrow-right ms-1"></i></a>
  </div>

  <h4 class="mt-4">Top Recommended For You</h4>
  <div class="row g-3" id="dash-recommendations">
    <?php if (empty($topMatches)): ?>
      <p>No opportunities yet — check back soon, or <a href="<?php echo BASE_URL; ?>skills.php">add more skills</a> to improve your matches.</p>
    <?php endif; ?>
    <?php foreach ($topMatches as $opp): ?>
      <div class="col-md-4">
        <div class="sb-card p-3 h-100">
          <span class="badge sb-badge <?php echo matchBadgeClass($opp['match_percent']); ?>"><?php echo $opp['match_percent']; ?>% Match</span>
          <h5 class="mt-2"><?php echo htmlspecialchars($opp['title']); ?></h5>
          <p class="text-muted mb-1"><?php echo htmlspecialchars($opp['company']); ?></p>
          <a href="<?php echo BASE_URL; ?>opportunity.php?id=<?php echo $opp['id']; ?>" class="btn sb-btn-outline btn-sm">View Details</a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<?php include 'includes/footer.php'; ?>
