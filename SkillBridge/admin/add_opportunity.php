<?php
require_once '../config/constants.php';
require_once '../config/database.php';
require_once '../includes/admin_auth.php';
require_once '../includes/functions.php';
requireAdminLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($conn, $_POST['title']);
    $company = sanitize($conn, $_POST['company']);
    $type = in_array($_POST['type'], ['Internship','Project']) ? $_POST['type'] : 'Internship';
    $description = sanitize($conn, $_POST['description']);
    $location = sanitize($conn, $_POST['location']);
    $duration = sanitize($conn, $_POST['duration']);
    $stipend = sanitize($conn, $_POST['stipend']);
    $adminId = $_SESSION['admin_id'];

    $stmt = $conn->prepare("INSERT INTO opportunities (title, company, type, description, location, duration, stipend, posted_by) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->bind_param("sssssssi", $title, $company, $type, $description, $location, $duration, $stipend, $adminId);
    $stmt->execute();
    $oppId = $stmt->insert_id;

    if (!empty($_POST['skill_ids'])) {
        $insSkill = $conn->prepare("INSERT INTO opportunity_skills (opportunity_id, skill_id, required_level) VALUES (?,?,?)");
        foreach ($_POST['skill_ids'] as $skillId) {
            $skillId = (int) $skillId;
            $level = 'Intermediate';
            $insSkill->bind_param("iis", $oppId, $skillId, $level);
            $insSkill->execute();
        }
    }
    setFlash('success', 'Opportunity added.');
    header("Location: opportunities.php");
    exit;
}

$allSkills = $conn->query("SELECT * FROM skills ORDER BY skill_name");
$pageTitle = "Add Opportunity";
include '../includes/header.php';
?>
<nav class="navbar sb-navbar"><div class="container"><span class="navbar-brand sb-brand">SkillBridge Admin</span>
<a href="opportunities.php" class="btn sb-btn-outline btn-sm">Back</a></div></nav>
<div class="container py-4">
  <div class="sb-card p-4">
    <h2>Add Opportunity</h2>
    <form method="POST">
      <div class="row g-3">
        <div class="col-md-6"><label class="form-label">Title</label><input name="title" class="form-control" required></div>
        <div class="col-md-6"><label class="form-label">Company</label><input name="company" class="form-control" required></div>
        <div class="col-md-4"><label class="form-label">Type</label>
          <select name="type" class="form-select"><option>Internship</option><option>Project</option></select></div>
        <div class="col-md-4"><label class="form-label">Location</label><input name="location" class="form-control"></div>
        <div class="col-md-4"><label class="form-label">Duration</label><input name="duration" class="form-control"></div>
        <div class="col-md-4"><label class="form-label">Stipend</label><input name="stipend" class="form-control"></div>
        <div class="col-12"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="4" required></textarea></div>
        <div class="col-12">
          <label class="form-label">Required Skills</label><br>
          <?php while ($s = $allSkills->fetch_assoc()): ?>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" name="skill_ids[]" value="<?php echo $s['id']; ?>" id="sk<?php echo $s['id']; ?>">
              <label class="form-check-label" for="sk<?php echo $s['id']; ?>"><?php echo htmlspecialchars($s['skill_name']); ?></label>
            </div>
          <?php endwhile; ?>
        </div>
      </div>
      <button type="submit" class="btn sb-btn-primary mt-3">Save Opportunity</button>
    </form>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
