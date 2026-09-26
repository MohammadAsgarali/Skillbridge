<?php
require_once 'config/constants.php';
require_once 'config/database.php';
require_once 'includes/functions.php';
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'matching/compatibility.php';

$studentId = $_SESSION['student_id'] ?? null;
$type = $_GET['type'] ?? 'All';

$sql = "SELECT * FROM opportunities";
if (in_array($type, ['Internship', 'Project'])) {
    $sql .= " WHERE type = '" . $conn->real_escape_string($type) . "'";
}
$sql .= " ORDER BY created_at DESC";
$opps = $conn->query($sql);

$pageTitle = "Opportunities";
include 'includes/header.php';
include 'includes/navbar.php';
?>
<div class="container py-4">
  <h2>Browse Opportunities</h2>
  <div class="btn-group my-3">
    <a href="opportunities.php" class="btn sb-btn-outline btn-sm <?php echo $type==='All'?'active':''; ?>">All</a>
    <a href="opportunities.php?type=Internship" class="btn sb-btn-outline btn-sm <?php echo $type==='Internship'?'active':''; ?>">Internships</a>
    <a href="opportunities.php?type=Project" class="btn sb-btn-outline btn-sm <?php echo $type==='Project'?'active':''; ?>">Projects</a>
  </div>

  <div class="row g-3">
    <?php while ($opp = $opps->fetch_assoc()):
        $match = $studentId ? calculateCompatibility($conn, $studentId, $opp['id']) : null;
    ?>
    <div class="col-md-6 col-lg-4">
      <div class="sb-card p-3 h-100 d-flex flex-column">
        <?php if ($match !== null): ?>
          <span class="badge sb-badge <?php echo matchBadgeClass($match); ?> align-self-start"><?php echo $match; ?>% Match</span>
        <?php endif; ?>
        <h5 class="mt-2"><?php echo htmlspecialchars($opp['title']); ?></h5>
        <p class="text-muted mb-1"><?php echo htmlspecialchars($opp['company']); ?> • <?php echo htmlspecialchars($opp['type']); ?></p>
        <p class="small mb-1"><?php echo htmlspecialchars($opp['location']); ?> · <?php echo htmlspecialchars($opp['duration']); ?></p>
        <p class="small text-truncate"><?php echo htmlspecialchars($opp['description']); ?></p>
        <a href="opportunity.php?id=<?php echo $opp['id']; ?>" class="btn sb-btn-primary btn-sm mt-auto">View & Apply</a>
      </div>
    </div>
    <?php endwhile; ?>
  </div>
</div>
<?php include 'includes/footer.php'; ?>
