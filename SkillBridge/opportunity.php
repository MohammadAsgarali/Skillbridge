<?php
require_once 'config/constants.php';
require_once 'config/database.php';
require_once 'includes/functions.php';
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'matching/compatibility.php';
require_once 'matching/skill_gap.php';

$oppId = (int) ($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM opportunities WHERE id = ?");
$stmt->bind_param("i", $oppId);
$stmt->execute();
$opp = $stmt->get_result()->fetch_assoc();
if (!$opp) { die("Opportunity not found."); }

$reqSkills = $conn->prepare("SELECT s.skill_name, os.required_level FROM opportunity_skills os JOIN skills s ON s.id = os.skill_id WHERE os.opportunity_id = ?");
$reqSkills->bind_param("i", $oppId);
$reqSkills->execute();
$reqSkills = $reqSkills->get_result();

$studentId = $_SESSION['student_id'] ?? null;
$match = null; $gap = []; $alreadyApplied = false; $alreadyBookmarked = false;
if ($studentId) {
    $match = calculateCompatibility($conn, $studentId, $oppId);
    $gap = getSkillGap($conn, $studentId, $oppId);

    $chk = $conn->prepare("SELECT id FROM applications WHERE student_id=? AND opportunity_id=?");
    $chk->bind_param("ii", $studentId, $oppId); $chk->execute();
    $alreadyApplied = $chk->get_result()->num_rows > 0;

    $chk2 = $conn->prepare("SELECT id FROM bookmarks WHERE student_id=? AND opportunity_id=?");
    $chk2->bind_param("ii", $studentId, $oppId); $chk2->execute();
    $alreadyBookmarked = $chk2->get_result()->num_rows > 0;
}

$pageTitle = $opp['title'];
include 'includes/header.php';
include 'includes/navbar.php';
?>
<div class="container py-4">
  <div class="sb-card p-4">
    <?php if ($match !== null): ?>
      <span class="badge sb-badge <?php echo matchBadgeClass($match); ?>"><?php echo $match; ?>% Match</span>
    <?php endif; ?>
    <h2 class="mt-2"><?php echo htmlspecialchars($opp['title']); ?></h2>
    <p class="text-muted"><?php echo htmlspecialchars($opp['company']); ?> • <?php echo htmlspecialchars($opp['type']); ?> • <?php echo htmlspecialchars($opp['location']); ?></p>
    <p><?php echo nl2br(htmlspecialchars($opp['description'])); ?></p>
    <p><strong>Duration:</strong> <?php echo htmlspecialchars($opp['duration']); ?> &nbsp; <strong>Stipend:</strong> <?php echo htmlspecialchars($opp['stipend']); ?></p>

    <h5 class="mt-3">Required Skills</h5>
    <ul>
    <?php while ($rs = $reqSkills->fetch_assoc()): ?>
      <li><?php echo htmlspecialchars($rs['skill_name']); ?> (<?php echo $rs['required_level']; ?>)</li>
    <?php endwhile; ?>
    </ul>

    <?php if ($studentId): ?>
      <?php if (!empty($gap)): ?>
        <div class="sb-gap-box">
          <strong>Skill Gap:</strong> you're missing
          <?php echo implode(', ', array_map(fn($g) => htmlspecialchars($g['skill_name']), $gap)); ?>.
          <a href="<?php echo BASE_URL; ?>skills.php">Add these skills</a> to improve your match.
        </div>
      <?php endif; ?>

      <div class="mt-3 d-flex gap-2">
        <?php if ($alreadyApplied): ?>
          <button class="btn sb-btn-outline" disabled>Already Applied</button>
        <?php else: ?>
          <form method="POST" action="api/apply.php">
            <input type="hidden" name="opportunity_id" value="<?php echo $oppId; ?>">
            <button class="btn sb-btn-primary" type="submit">Apply Now</button>
          </form>
        <?php endif; ?>
        <form method="POST" action="api/bookmark.php">
          <input type="hidden" name="opportunity_id" value="<?php echo $oppId; ?>">
          <button class="btn sb-btn-outline" type="submit"><?php echo $alreadyBookmarked ? 'Remove Bookmark' : 'Bookmark'; ?></button>
        </form>
      </div>
    <?php else: ?>
      <p><a href="<?php echo BASE_URL; ?>login.php">Login</a> to see your match score and apply.</p>
    <?php endif; ?>
  </div>
</div>
<?php include 'includes/footer.php'; ?>
