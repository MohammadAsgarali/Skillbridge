<?php
require_once '../config/constants.php';
require_once '../config/database.php';
require_once '../includes/admin_auth.php';
require_once '../includes/functions.php';
requireAdminLogin();

$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($conn, $_POST['title']);
    $company = sanitize($conn, $_POST['company']);
    $type = in_array($_POST['type'], ['Internship','Project']) ? $_POST['type'] : 'Internship';
    $description = sanitize($conn, $_POST['description']);
    $location = sanitize($conn, $_POST['location']);
    $duration = sanitize($conn, $_POST['duration']);
    $stipend = sanitize($conn, $_POST['stipend']);

    $stmt = $conn->prepare("UPDATE opportunities SET title=?, company=?, type=?, description=?, location=?, duration=?, stipend=? WHERE id=?");
    $stmt->bind_param("sssssssi", $title, $company, $type, $description, $location, $duration, $stipend, $id);
    $stmt->execute();

    // Reset required skills then re-insert selected ones (simplest correct approach for a hackathon build)
    $conn->query("DELETE FROM opportunity_skills WHERE opportunity_id = $id");
    if (!empty($_POST['skill_ids'])) {
        $insSkill = $conn->prepare("INSERT INTO opportunity_skills (opportunity_id, skill_id, required_level) VALUES (?,?,?)");
        foreach ($_POST['skill_ids'] as $skillId) {
            $skillId = (int) $skillId; $level = 'Intermediate';
            $insSkill->bind_param("iis", $id, $skillId, $level);
            $insSkill->execute();
        }
    }
    setFlash('success', 'Opportunity updated.');
    header("Location: opportunities.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM opportunities WHERE id = ?");
$stmt->bind_param("i", $id); $stmt->execute();
$opp = $stmt->get_result()->fetch_assoc();
if (!$opp) { die("Opportunity not found."); }

$selectedIds = [];
$sel = $conn->prepare("SELECT skill_id FROM opportunity_skills WHERE opportunity_id = ?");
$sel->bind_param("i", $id); $sel->execute();
$r = $sel->get_result();
while ($row = $r->fetch_assoc()) $selectedIds[] = $row['skill_id'];

$allSkills = $conn->query("SELECT * FROM skills ORDER BY skill_name");
$pageTitle = "Edit Opportunity";
include '../includes/header.php';
?>
<nav class="navbar sb-navbar"><div class="container"><span class="navbar-brand sb-brand">SkillBridge Admin</span>
<a href="opportunities.php" class="btn sb-btn-outline btn-sm">Back</a></div></nav>
<div class="container py-4">
  <div class="sb-card p-4">
    <h2>Edit Opportunity</h2>
    <form method="POST">
      <input type="hidden" name="id" value="<?php echo $id; ?>">
      <div class="row g-3">
        <div class="col-md-6"><label class="form-label">Title</label><input name="title" class="form-control" value="<?php echo htmlspecialchars($opp['title']); ?>" required></div>
        <div class="col-md-6"><label class="form-label">Company</label><input name="company" class="form-control" value="<?php echo htmlspecialchars($opp['company']); ?>" required></div>
        <div class="col-md-4"><label class="form-label">Type</label>
          <select name="type" class="form-select">
            <option <?php echo $opp['type']==='Internship'?'selected':''; ?>>Internship</option>
            <option <?php echo $opp['type']==='Project'?'selected':''; ?>>Project</option>
          </select></div>
        <div class="col-md-4"><label class="form-label">Location</label><input name="location" class="form-control" value="<?php echo htmlspecialchars($opp['location']); ?>"></div>
        <div class="col-md-4"><label class="form-label">Duration</label><input name="duration" class="form-control" value="<?php echo htmlspecialchars($opp['duration']); ?>"></div>
        <div class="col-md-4"><label class="form-label">Stipend</label><input name="stipend" class="form-control" value="<?php echo htmlspecialchars($opp['stipend']); ?>"></div>
        <div class="col-12"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($opp['description']); ?></textarea></div>
        <div class="col-12">
          <label class="form-label">Required Skills</label><br>
          <?php while ($s = $allSkills->fetch_assoc()): ?>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" name="skill_ids[]" value="<?php echo $s['id']; ?>" id="sk<?php echo $s['id']; ?>"
                <?php echo in_array($s['id'], $selectedIds) ? 'checked' : ''; ?>>
              <label class="form-check-label" for="sk<?php echo $s['id']; ?>"><?php echo htmlspecialchars($s['skill_name']); ?></label>
            </div>
          <?php endwhile; ?>
        </div>
      </div>
      <button type="submit" class="btn sb-btn-primary mt-3">Update Opportunity</button>
    </form>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
