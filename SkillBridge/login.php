<?php
require_once 'config/constants.php';
require_once 'config/database.php';
require_once 'includes/functions.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($conn, $_POST['email']);
    $pass  = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, full_name, password FROM students WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $student = $result->fetch_assoc();
        if (password_verify($pass, $student['password'])) {
            $_SESSION['student_id'] = $student['id'];
            $_SESSION['student_name'] = $student['full_name'];
            redirect('dashboard.php');
        }
    }
    setFlash('error', 'Invalid email or password.');
    redirect('login.php');
}

$pageTitle = "Login";
include 'includes/header.php';
include 'includes/navbar.php';
?>
<div class="container sb-form-page">
  <div class="sb-card p-4 mx-auto" style="max-width:420px;">
    <h2 class="mb-3">Login to SkillBridge</h2>
    <?php showFlash(); ?>
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn sb-btn-primary w-100">Login</button>
    </form>
    <p class="mt-3 text-center">New here? <a href="<?php echo BASE_URL; ?>register.php">Create an account</a></p>
    <p class="text-center"><a href="<?php echo BASE_URL; ?>admin/login.php" class="text-muted small">Admin login</a></p>
  </div>
</div>
<?php include 'includes/footer.php'; ?>
