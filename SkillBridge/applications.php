<?php
require_once 'config/constants.php';
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';
requireStudentLogin();
$studentId = currentStudentId();

$stmt = $conn->prepare(
    "SELECT a.id, a.status, a.applied_at, o.id AS opp_id, o.title, o.company
     FROM applications a JOIN opportunities o ON o.id = a.opportunity_id
     WHERE a.student_id = ? ORDER BY a.applied_at DESC"
);
$stmt->bind_param("i", $studentId);
$stmt->execute();
$apps = $stmt->get_result();

$pageTitle = "My Applications";
include 'includes/header.php';
include 'includes/navbar.php';
?>
<div class="container py-4">
  <h2>My Applications</h2>
  <?php showFlash(); ?>
  <table class="table sb-table">
    <thead><tr><th>Opportunity</th><th>Company</th><th>Status</th><th>Applied On</th><th></th></tr></thead>
    <tbody>
    <?php while ($a = $apps->fetch_assoc()): ?>
      <tr>
        <td><?php echo htmlspecialchars($a['title']); ?></td>
        <td><?php echo htmlspecialchars($a['company']); ?></td>
        <td><span class="badge sb-status-<?php echo strtolower($a['status']); ?>"><?php echo $a['status']; ?></span></td>
        <td><?php echo date('d M Y', strtotime($a['applied_at'])); ?></td>
        <td><a href="opportunity.php?id=<?php echo $a['opp_id']; ?>">View</a></td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</div>
<?php include 'includes/footer.php'; ?>
