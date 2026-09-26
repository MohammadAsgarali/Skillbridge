<?php
require_once '../config/constants.php';
require_once '../config/database.php';
require_once '../includes/admin_auth.php';
require_once '../includes/functions.php';
requireAdminLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($conn, $_POST['skill_name']);
    $category = sanitize($conn, $_POST['category']);
    $stmt = $conn->prepare("INSERT IGNORE INTO skills (skill_name, category) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $category);
    $stmt->execute();
    setFlash('success', 'Skill added to catalogue.');
    header("Location: skills.php");
    exit;
}
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $conn->query("DELETE FROM skills WHERE id = $id");
    setFlash('success', 'Skill deleted.');
    header("Location: skills.php");
    exit;
}

$skills = $conn->query("SELECT * FROM skills ORDER BY category, skill_name");
$pageTitle = "Manage Skills";
include '../includes/header.php';
?>
<nav class="navbar sb-navbar"><div class="container"><span class="navbar-brand sb-brand">SkillBridge Admin</span>
<a href="dashboard.php" class="btn sb-btn-outline btn-sm">Back</a></div></nav>
<div class="container py-4">
  <h2>Manage Skill Catalogue</h2>
  <?php showFlash(); ?>
  <div class="sb-card p-3 mb-3">
    <form method="POST" class="row g-2">
      <div class="col-md-5"><input name="skill_name" class="form-control" placeholder="Skill name" required></div>
      <div class="col-md-5"><input name="category" class="form-control" placeholder="Category (e.g. Frontend)"></div>
      <div class="col-md-2"><button class="btn sb-btn-primary w-100">Add</button></div>
    </form>
  </div>
  <table class="table sb-table">
    <thead><tr><th>Skill</th><th>Category</th><th></th></tr></thead>
    <tbody>
    <?php while ($s = $skills->fetch_assoc()): ?>
      <tr>
        <td><?php echo htmlspecialchars($s['skill_name']); ?></td>
        <td><?php echo htmlspecialchars($s['category']); ?></td>
        <td><a href="?delete=<?php echo $s['id']; ?>" class="text-danger" onclick="return confirm('Delete skill?')">Delete</a></td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</div>
<?php include '../includes/footer.php'; ?>
