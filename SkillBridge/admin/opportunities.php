<?php
require_once '../config/constants.php';
require_once '../config/database.php';
require_once '../includes/admin_auth.php';
require_once '../includes/functions.php';
requireAdminLogin();

$opps = $conn->query("SELECT * FROM opportunities ORDER BY created_at DESC");
$pageTitle = "Manage Opportunities";
include '../includes/header.php';
?>
<nav class="navbar sb-navbar"><div class="container">
  <span class="navbar-brand sb-brand">SkillBridge Admin</span>
  <div><a href="dashboard.php" class="btn sb-btn-outline btn-sm me-2">Dashboard</a>
  <a href="add_opportunity.php" class="btn sb-btn-primary btn-sm">+ Add Opportunity</a></div>
</div></nav>
<div class="container py-4">
  <h2>Manage Opportunities</h2>
  <?php showFlash(); ?>
  <table class="table sb-table">
    <thead><tr><th>Title</th><th>Company</th><th>Type</th><th></th></tr></thead>
    <tbody>
    <?php while ($o = $opps->fetch_assoc()): ?>
      <tr>
        <td><?php echo htmlspecialchars($o['title']); ?></td>
        <td><?php echo htmlspecialchars($o['company']); ?></td>
        <td><?php echo $o['type']; ?></td>
        <td>
          <a href="edit_opportunity.php?id=<?php echo $o['id']; ?>">Edit</a> |
          <a href="delete_opportunity.php?id=<?php echo $o['id']; ?>" class="text-danger" onclick="return confirm('Delete this opportunity?')">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</div>
<?php include '../includes/footer.php'; ?>
