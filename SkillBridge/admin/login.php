<?php
require_once '../config/constants.php';
require_once '../config/database.php';
require_once '../includes/functions.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($conn, $_POST['username']);
    $pass = $_POST['password'];
    $stmt = $conn->prepare("SELECT id, password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows === 1) {
        $admin = $res->fetch_assoc();
        if (password_verify($pass, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            header("Location: dashboard.php");
            exit;
        }
    }
    setFlash('error', 'Invalid admin credentials.');
    header("Location: login.php");
    exit;
}
$pageTitle = "Admin Login";
include '../includes/header.php';
?>
<div class="container sb-form-page">
  <div class="sb-card p-4 mx-auto" style="max-width:420px;">
    <h2 class="mb-3">Admin Login</h2>
    <?php showFlash(); ?>
    <form method="POST">
      <div class="mb-3"><label class="form-label">Username</label><input type="text" name="username" class="form-control" required></div>
      <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
      <button type="submit" class="btn sb-btn-primary w-100">Login</button>
    </form>
    <p class="mt-2 text-muted small">Default: admin / admin123 (from sample_data.sql)</p>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
