<?php
require_once 'config/constants.php';
require_once 'config/database.php';
require_once 'includes/functions.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = sanitize($conn, $_POST['full_name']);
    $email = sanitize($conn, $_POST['email']);
    $pass  = $_POST['password'];
    $college = sanitize($conn, $_POST['college']);

    if (strlen($pass) < 6) {
        setFlash('error', 'Password must be at least 6 characters.');
    } else {
        $check = $conn->prepare("SELECT id FROM students WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            setFlash('error', 'An account with this email already exists.');
        } else {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO students (full_name, email, password, college) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $hash, $college);
            if ($stmt->execute()) {
                $_SESSION['student_id'] = $stmt->insert_id;
                $_SESSION['student_name'] = $name;
                setFlash('success', 'Welcome to SkillBridge, ' . $name . '!');
                redirect('skills.php'); // send them straight to add skills
            } else {
                setFlash('error', 'Something went wrong. Please try again.');
            }
        }
    }
    redirect('register.php');
}

$pageTitle = "Register";
include 'includes/header.php';
include 'includes/navbar.php';
?>
<div class="container sb-form-page">
  <div class="sb-card p-4 mx-auto" style="max-width:480px;">
    <h2 class="mb-3">Create your account</h2>
    <?php showFlash(); ?>
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" name="full_name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">College</label>
        <input type="text" name="college" class="form-control">
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" minlength="6" required>
      </div>
      <button type="submit" class="btn sb-btn-primary w-100">Register</button>
    </form>
    <p class="mt-3 text-center">Already have an account? <a href="<?php echo BASE_URL; ?>login.php">Login</a></p>
  </div>
</div>
<?php include 'includes/footer.php'; ?>
