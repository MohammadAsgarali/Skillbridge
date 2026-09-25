<?php
require_once '../config/constants.php';
require_once '../config/database.php';
require_once '../includes/admin_auth.php';
require_once '../includes/functions.php';
requireAdminLogin();

if (isset($_POST['app_id'])) {
    $appId = (int) $_POST['app_id'];
    $status = in_array($_POST['status'], ['Pending','Shortlisted','Rejected','Selected']) ? $_POST['status'] : 'Pending';
    $stmt = $conn->prepare("UPDATE applications SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $appId);
    $stmt->execute();
    setFlash('success', 'Application status updated.');
    header("Location: applications.php");
    exit;
}

$apps = $conn->query(
    "SELECT a.id, a.status, a.applied_at, s.full_name, s.email, o.title
     FROM applications a
     JOIN students s ON s.id = a.student_id
     JOIN opportunities o ON o.id = a.opportunity_id
     ORDER BY a.applied_at DESC"
);
$pageTitle = "Applications";
include '../includes/header.php';
?>
<nav class="navbar sb-navbar"><div class="container"><span class="navbar-brand sb-brand">SkillBridge Admin</span>
<a href="dashboard.php" class="btn sb-btn-outline btn-sm">Back</a></div></nav>
<div class="container py-4">
  <h2>Student Applications</h2>
  <?php showFlash(); ?>
  <table class="table sb-table">
    <thead><tr><th>Student</th><th>Opportunity</th><th>Status</th><th>Update</th></tr></thead>
    <tbody>
    <?php while ($a = $apps->fetch_assoc()): ?>
      <tr>
        <td><?php echo htmlspecialchars($a['full_name']); ?><br><small class="text-muted"><?php echo htmlspecialchars($a['email']); ?></small></td>
        <td><?php echo htmlspecialchars($a['title']); ?></td>
        <td><?php echo $a['status']; ?></td>
        <td>
          <form method="POST" class="d-flex gap-1">
            <input type="hidden" name="app_id" value="<?php echo $a['id']; ?>">
            <select name="status" class="form-select form-select-sm">
              <?php foreach (['Pending','Shortlisted','Selected','Rejected'] as $st): ?>
                <option <?php echo $a['status']===$st?'selected':''; ?>><?php echo $st; ?></option>
              <?php endforeach; ?>
            </select>
            <button class="btn sb-btn-outline btn-sm">Save</button>
          </form>
        </td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</div>
<?php include '../includes/footer.php'; ?>
