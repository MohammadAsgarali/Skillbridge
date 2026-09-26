<?php
require_once 'config/constants.php';
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';
requireStudentLogin();
$studentId = currentStudentId();

// Add a skill
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['skill_id'])) {
    $skillId = (int) $_POST['skill_id'];
    $level = in_array($_POST['proficiency'], ['Beginner','Intermediate','Advanced']) ? $_POST['proficiency'] : 'Beginner';
    $stmt = $conn->prepare("INSERT IGNORE INTO student_skills (student_id, skill_id, proficiency) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $studentId, $skillId, $level);
    $stmt->execute();
    setFlash('success', 'Skill added.');
    redirect('skills.php');
}

// Remove a skill (delete)
if (isset($_GET['remove'])) {
    $skillId = (int) $_GET['remove'];
    $stmt = $conn->prepare("DELETE FROM student_skills WHERE student_id = ? AND skill_id = ?");
    $stmt->bind_param("ii", $studentId, $skillId);
    $stmt->execute();
    setFlash('success', 'Skill removed.');
    redirect('skills.php');
}

$mySkills = $conn->prepare("SELECT s.id, s.skill_name, ss.proficiency FROM student_skills ss JOIN skills s ON s.id = ss.skill_id WHERE ss.student_id = ?");
$mySkills->bind_param("i", $studentId);
$mySkills->execute();
$mySkills = $mySkills->get_result();

$allSkills = $conn->query("SELECT * FROM skills ORDER BY skill_name");

$pageTitle = "My Skills";
include 'includes/header.php';
include 'includes/navbar.php';
?>
<div class="container py-4">
  <h2>My Skills</h2>
  <?php showFlash(); ?>

  <div class="sb-card p-4 mb-4">
    <h5>Add a Skill</h5>
    <form method="POST" class="row g-2 align-items-end">
      <div class="col-md-6">
        <label class="form-label">Skill</label>
        <select name="skill_id" class="form-select" required>
          <?php while ($s = $allSkills->fetch_assoc()): ?>
            <option value="<?php echo $s['id']; ?>"><?php echo htmlspecialchars($s['skill_name']); ?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Proficiency</label>
        <select name="proficiency" class="form-select">
          <option>Beginner</option><option>Intermediate</option><option>Advanced</option>
        </select>
      </div>
      <div class="col-md-2">
        <button class="btn sb-btn-primary w-100" type="submit">Add</button>
      </div>
    </form>
  </div>

  <h5>My Current Skills</h5>
  <table class="table sb-table">
    <thead><tr><th>Skill</th><th>Proficiency</th><th></th></tr></thead>
    <tbody>
    <?php while ($row = $mySkills->fetch_assoc()): ?>
      <tr>
        <td><?php echo htmlspecialchars($row['skill_name']); ?></td>
        <td><?php echo $row['proficiency']; ?></td>
        <td><a href="?remove=<?php echo $row['id']; ?>" class="text-danger" onclick="return confirm('Remove this skill?')">Remove</a></td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</div>
<?php include 'includes/footer.php'; ?>
