<?php
require_once 'config/constants.php';
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';
require_once 'matching/compatibility.php';
requireStudentLogin();
$studentId = currentStudentId();

$stmt = $conn->prepare(
    "SELECT o.* FROM bookmarks b JOIN opportunities o ON o.id = b.opportunity_id
     WHERE b.student_id = ? ORDER BY b.created_at DESC"
);
$stmt->bind_param("i", $studentId);
$stmt->execute();
$bookmarks = $stmt->get_result();

$pageTitle = "Bookmarks";
include 'includes/header.php';
include 'includes/navbar.php';
?>
<div class="container py-4">
  <h2>My Bookmarks</h2>
  <div class="row g-3">
    <?php while ($opp = $bookmarks->fetch_assoc()):
        $match = calculateCompatibility($conn, $studentId, $opp['id']);
    ?>
      <div class="col-md-6 col-lg-4">
        <div class="sb-card p-3 h-100 d-flex flex-column">
          <span class="badge sb-badge <?php echo matchBadgeClass($match); ?> align-self-start"><?php echo $match; ?>% Match</span>
          <h5 class="mt-2"><?php echo htmlspecialchars($opp['title']); ?></h5>
          <p class="text-muted mb-1"><?php echo htmlspecialchars($opp['company']); ?></p>
          <a href="opportunity.php?id=<?php echo $opp['id']; ?>" class="btn sb-btn-primary btn-sm mt-auto">View Details</a>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>
<?php include 'includes/footer.php'; ?>
