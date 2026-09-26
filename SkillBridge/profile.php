<?php
require_once 'config/constants.php';
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';
requireStudentLogin();
$studentId = currentStudentId();

// Backward-compatible profile migration: older databases may not yet have the new profile fields.
$profileColumns = [
    'phone' => 'VARCHAR(30) DEFAULT NULL',
    'course' => 'VARCHAR(120) DEFAULT NULL',
    'career_domain' => 'VARCHAR(100) DEFAULT NULL',
    'graduation_year' => 'SMALLINT DEFAULT NULL',
    'college_location' => 'VARCHAR(120) DEFAULT NULL',
    'linkedin_url' => 'VARCHAR(255) DEFAULT NULL',
    'github_url' => 'VARCHAR(255) DEFAULT NULL',
    'instagram_url' => 'VARCHAR(255) DEFAULT NULL',
    'portfolio_url' => 'VARCHAR(255) DEFAULT NULL'
];
foreach ($profileColumns as $column => $definition) {
    $checkColumn = $conn->query("SHOW COLUMNS FROM students LIKE '" . $conn->real_escape_string($column) . "'");
    if ($checkColumn && $checkColumn->num_rows === 0) {
        $conn->query("ALTER TABLE students ADD COLUMN `{$column}` {$definition}");
    }
}

$tab = $_GET['tab'] ?? 'personal';
$allowedTabs = ['personal','education','social','skills','account'];
if (!in_array($tab, $allowedTabs, true)) $tab = 'personal';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $section = $_POST['section'] ?? 'personal';

    if ($section === 'personal') {
        $name = sanitize($conn, $_POST['full_name'] ?? '');
        $bio = sanitize($conn, $_POST['bio'] ?? '');
        $phone = sanitize($conn, $_POST['phone'] ?? '');
        $stmt = $conn->prepare("UPDATE students SET full_name=?, bio=?, phone=? WHERE id=?");
        $stmt->bind_param("sssi", $name, $bio, $phone, $studentId);
        $stmt->execute();
        $_SESSION['student_name'] = $name;
        setFlash('success', 'Personal details updated.');
        redirect('profile.php?tab=personal');
    }

    if ($section === 'education') {
        $college = sanitize($conn, $_POST['college'] ?? '');
        $course = sanitize($conn, $_POST['course'] ?? '');
        $year = (int)($_POST['graduation_year'] ?? 0);
        $location = sanitize($conn, $_POST['college_location'] ?? '');
        $stmt = $conn->prepare("UPDATE students SET college=?, course=?, graduation_year=?, college_location=? WHERE id=?");
        $stmt->bind_param("ssisi", $college, $course, $year, $location, $studentId);
        $stmt->execute();
        setFlash('success', 'College and education details updated.');
        redirect('profile.php?tab=education');
    }

    if ($section === 'social') {
        $linkedin = sanitize($conn, $_POST['linkedin_url'] ?? '');
        $github = sanitize($conn, $_POST['github_url'] ?? '');
        $instagram = sanitize($conn, $_POST['instagram_url'] ?? '');
        $portfolio = sanitize($conn, $_POST['portfolio_url'] ?? '');
        $stmt = $conn->prepare("UPDATE students SET linkedin_url=?, github_url=?, instagram_url=?, portfolio_url=? WHERE id=?");
        $stmt->bind_param("ssssi", $linkedin, $github, $instagram, $portfolio, $studentId);
        $stmt->execute();
        setFlash('success', 'Social and portfolio links updated.');
        redirect('profile.php?tab=social');
    }

    if ($section === 'skills') {
        $skillId = (int)($_POST['skill_id'] ?? 0);
        $level = in_array($_POST['proficiency'] ?? '', ['Beginner','Intermediate','Advanced'], true) ? $_POST['proficiency'] : 'Beginner';
        if ($skillId > 0) {
            $stmt = $conn->prepare("INSERT IGNORE INTO student_skills (student_id, skill_id, proficiency) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $studentId, $skillId, $level);
            $stmt->execute();
            setFlash('success', 'Skill added to your profile.');
        }
        redirect('profile.php?tab=skills');
    }
}

if (isset($_GET['remove_skill'])) {
    $skillId = (int)$_GET['remove_skill'];
    $stmt = $conn->prepare("DELETE FROM student_skills WHERE student_id=? AND skill_id=?");
    $stmt->bind_param("ii", $studentId, $skillId);
    $stmt->execute();
    setFlash('success', 'Skill removed.');
    redirect('profile.php?tab=skills');
}

$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $studentId);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

$skillsStmt = $conn->prepare("SELECT s.id, s.skill_name, ss.proficiency FROM student_skills ss JOIN skills s ON s.id=ss.skill_id WHERE ss.student_id=? ORDER BY s.skill_name");
$skillsStmt->bind_param("i", $studentId);
$skillsStmt->execute();
$mySkills = $skillsStmt->get_result()->fetch_all(MYSQLI_ASSOC);
$allSkills = $conn->query("SELECT id, skill_name, category FROM skills ORDER BY skill_name");

$pageTitle = "My Profile";
include 'includes/header.php';
include 'includes/navbar.php';
?>
<div class="container py-3 pb-5">
  <div class="alert alert-light border"><i class="bi bi-compass-fill me-1"></i> Career domain and course are managed in your <a href="onboarding.php">Career Setup</a>. Your roadmap uses them to define required skills.</div>
  <?php showFlash(); ?>
  <div class="sb-profile-heading">
    <div>
      <h2 class="mb-1">My Profile</h2>
      <p>Keep your student profile complete so SkillBridge can match you with better opportunities.</p>
    </div>
    <span class="badge sb-badge badge-high"><i class="bi bi-shield-check me-1"></i> Profile Workspace</span>
  </div>

  <div class="sb-profile-layout">
    <aside class="sb-profile-nav">
      <div class="sb-profile-nav-title">Profile sections</div>
      <button type="button" class="sb-profile-tab <?php echo $tab==='personal'?'active':''; ?>" data-profile-tab="personal"><i class="bi bi-person"></i> Personal Details</button>
      <button type="button" class="sb-profile-tab <?php echo $tab==='education'?'active':''; ?>" data-profile-tab="education"><i class="bi bi-mortarboard"></i> College & Education</button>
      <button type="button" class="sb-profile-tab <?php echo $tab==='social'?'active':''; ?>" data-profile-tab="social"><i class="bi bi-share"></i> Social Accounts</button>
      <button type="button" class="sb-profile-tab <?php echo $tab==='skills'?'active':''; ?>" data-profile-tab="skills"><i class="bi bi-lightning"></i> Skills</button>
      <button type="button" class="sb-profile-tab <?php echo $tab==='account'?'active':''; ?>" data-profile-tab="account"><i class="bi bi-gear"></i> Account</button>
    </aside>

    <section class="sb-profile-content">
      <div data-profile-panel="personal" class="sb-card p-4 <?php echo $tab==='personal'?'':'d-none'; ?>">
        <h4>Personal Details</h4>
        <p class="sb-section-note mb-4">Basic information shown on your SkillBridge profile.</p>
        <form method="POST">
          <input type="hidden" name="section" value="personal">
          <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Full Name</label><input name="full_name" class="form-control" value="<?php echo htmlspecialchars($student['full_name']); ?>" required></div>
            <div class="col-md-6"><label class="form-label">Email</label><input class="form-control" value="<?php echo htmlspecialchars($student['email']); ?>" disabled></div>
            <div class="col-md-6"><label class="form-label">Phone</label><input name="phone" class="form-control" value="<?php echo htmlspecialchars($student['phone'] ?? ''); ?>" placeholder="+91 ..."></div>
            <div class="col-12"><label class="form-label">About You</label><textarea name="bio" class="form-control" rows="5" placeholder="Tell recruiters what you are learning and building."><?php echo htmlspecialchars($student['bio'] ?? ''); ?></textarea></div>
          </div>
          <button class="btn sb-btn-primary mt-4" type="submit"><i class="bi bi-check2-circle me-1"></i> Save Personal Details</button>
        </form>
      </div>

      <div data-profile-panel="education" class="sb-card p-4 <?php echo $tab==='education'?'':'d-none'; ?>">
        <h4>College & Education</h4>
        <p class="sb-section-note mb-4">Add your academic details to make your profile more useful for project and internship matching.</p>
        <form method="POST">
          <input type="hidden" name="section" value="education">
          <div class="row g-3">
            <div class="col-12"><label class="form-label">College / University</label><input name="college" class="form-control" value="<?php echo htmlspecialchars($student['college'] ?? ''); ?>" placeholder="College name"></div>
            <div class="col-md-6">
  <label class="form-label">Course / Degree</label>
  <select name="course" class="form-select">
    <option value="">Select your course</option>
    <?php
      $courseOptions = ['B.Tech','M.Tech','BCA','MCA','BBA','MBA','B.Com','M.Com','B.Sc','M.Sc','BA','MA','B.Des','Other'];
      foreach ($courseOptions as $courseOption):
    ?>
      <option value="<?php echo htmlspecialchars($courseOption); ?>" <?php echo (($student['course'] ?? '') === $courseOption) ? 'selected' : ''; ?>><?php echo htmlspecialchars($courseOption); ?></option>
    <?php endforeach; ?>
  </select>
  <small class="text-muted">Used by Career Roadmap to personalize your recommendations.</small>
</div>
            <div class="col-md-6"><label class="form-label">Graduation Year</label><input type="number" min="2000" max="2100" name="graduation_year" class="form-control" value="<?php echo htmlspecialchars($student['graduation_year'] ?? ''); ?>" placeholder="2027"></div>
            <div class="col-12"><label class="form-label">College Location</label><input name="college_location" class="form-control" value="<?php echo htmlspecialchars($student['college_location'] ?? ''); ?>" placeholder="City, State"></div>
          </div>
          <button class="btn sb-btn-primary mt-4" type="submit"><i class="bi bi-mortarboard me-1"></i> Save Education</button>
        </form>
      </div>

      <div data-profile-panel="social" class="sb-card p-4 <?php echo $tab==='social'?'':'d-none'; ?>">
        <h4>Social Accounts & Portfolio</h4>
        <p class="sb-section-note mb-4">Paste your public profile links. These help you present your work professionally.</p>
        <form method="POST">
          <input type="hidden" name="section" value="social">
          <div class="sb-social-grid">
            <div class="sb-social-field"><i class="bi bi-linkedin"></i><div class="flex-grow-1"><label class="form-label mb-1">LinkedIn</label><input type="url" name="linkedin_url" class="form-control" value="<?php echo htmlspecialchars($student['linkedin_url'] ?? ''); ?>" placeholder="https://linkedin.com/in/..."></div></div>
            <div class="sb-social-field"><i class="bi bi-github"></i><div class="flex-grow-1"><label class="form-label mb-1">GitHub</label><input type="url" name="github_url" class="form-control" value="<?php echo htmlspecialchars($student['github_url'] ?? ''); ?>" placeholder="https://github.com/..."></div></div>
            <div class="sb-social-field"><i class="bi bi-instagram"></i><div class="flex-grow-1"><label class="form-label mb-1">Instagram</label><input type="url" name="instagram_url" class="form-control" value="<?php echo htmlspecialchars($student['instagram_url'] ?? ''); ?>" placeholder="https://instagram.com/..."></div></div>
            <div class="sb-social-field"><i class="bi bi-globe2"></i><div class="flex-grow-1"><label class="form-label mb-1">Portfolio / Website</label><input type="url" name="portfolio_url" class="form-control" value="<?php echo htmlspecialchars($student['portfolio_url'] ?? ''); ?>" placeholder="https://yourportfolio.com"></div></div>
          </div>
          <button class="btn sb-btn-primary mt-4" type="submit"><i class="bi bi-link-45deg me-1"></i> Save Links</button>
        </form>
      </div>

      <div data-profile-panel="skills" class="sb-card p-4 <?php echo $tab==='skills'?'':'d-none'; ?>">
        <h4>Skills</h4>
        <p class="sb-section-note mb-4">Add skills and select your current proficiency level. These skills power your opportunity matching.</p>
        <form method="POST" class="row g-2 align-items-end mb-4">
          <input type="hidden" name="section" value="skills">
          <div class="col-md-7"><label class="form-label">Select Skill</label><select name="skill_id" class="form-select" required><option value="">Choose a skill</option><?php while($s=$allSkills->fetch_assoc()): ?><option value="<?php echo $s['id']; ?>"><?php echo htmlspecialchars($s['skill_name']); ?><?php echo $s['category'] ? ' — '.htmlspecialchars($s['category']) : ''; ?></option><?php endwhile; ?></select></div>
          <div class="col-md-3"><label class="form-label">Level</label><select name="proficiency" class="form-select"><option>Beginner</option><option>Intermediate</option><option>Advanced</option></select></div>
          <div class="col-md-2"><button class="btn sb-btn-primary w-100" type="submit">Add Skill</button></div>
        </form>
        <div class="mb-2"><strong>Your current skills</strong></div>
        <?php if (!$mySkills): ?>
          <div class="sb-section-note">No skills added yet. Use the selector above to add your first skill.</div>
        <?php else: ?>
          <div><?php foreach($mySkills as $s): ?><span class="sb-skill-chip"><i class="bi bi-check2"></i><?php echo htmlspecialchars($s['skill_name']); ?> <small>(<?php echo $s['proficiency']; ?>)</small><a class="text-danger text-decoration-none" href="?tab=skills&remove_skill=<?php echo $s['id']; ?>" onclick="return confirm('Remove this skill?')"><i class="bi bi-x"></i></a></span><?php endforeach; ?></div>
        <?php endif; ?>
      </div>

      <div data-profile-panel="account" class="sb-card p-4 <?php echo $tab==='account'?'':'d-none'; ?>">
        <h4>Account</h4>
        <p class="sb-section-note">Use the account action below when you want to sign out from SkillBridge.</p>
        <div class="p-3 mt-3 rounded-3 border bg-light d-flex align-items-center justify-content-between gap-3">
          <div><strong>Sign out</strong><div class="sb-section-note">End your current SkillBridge session.</div></div>
          <a class="btn btn-outline-danger" href="<?php echo BASE_URL; ?>logout.php"><i class="bi bi-box-arrow-right me-1"></i> Logout</a>
        </div>
      </div>
    </section>
  </div>
</div>
<?php include 'includes/footer.php'; ?>
