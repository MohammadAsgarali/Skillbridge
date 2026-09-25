<?php
require_once 'config/constants.php';
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';
require_once 'matching/career_recommendation.php';
requireStudentLogin();

$studentId = currentStudentId();
$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $studentId);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

$recommendations = sbCareerRecommendations($conn, $student);
$top = array_slice($recommendations, 0, 5);

$pageTitle = "Career Roadmap";
include 'includes/header.php';
include 'includes/navbar.php';
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/career.css">

<div class="container py-4 pb-5">
  <div class="career-hero mb-4">
    <div>
      <span class="career-eyebrow"><i class="bi bi-stars"></i> PERSONALIZED CAREER ENGINE</span>
      <h1>What should I learn next?</h1>
      <p class="mb-0">SkillBridge checks your degree and current skills, then suggests career paths, courses, projects and learning resources.</p>
    </div>
    <div class="career-hero-icon"><i class="bi bi-compass"></i></div>
  </div>

  <?php if (empty($student['course'])): ?>
    <div class="alert sb-card border-0 career-alert">
      <strong><i class="bi bi-info-circle me-1"></i> Add your course first.</strong>
      Go to <a href="profile.php?tab=education">Profile → College & Education</a> and enter your degree such as B.Com, BBA, BCA, MCA, B.Tech or M.Tech. Then come back here for a more accurate compatibility score.
    </div>
  <?php endif; ?>

  <div class="career-profile-strip sb-card p-3 mb-4">
    <div>
      <small class="text-muted">Your academic background</small>
      <h5 class="mb-0"><?php echo htmlspecialchars($student['course'] ?: 'Not added yet'); ?></h5>
    </div>
    <div>
      <small class="text-muted">College</small>
      <h6 class="mb-0"><?php echo htmlspecialchars($student['college'] ?: 'Not added'); ?></h6>
    </div>
    <div>
      <small class="text-muted">Skills in profile</small>
      <?php
      $skillCountStmt = $conn->prepare("SELECT COUNT(*) c FROM student_skills WHERE student_id=?");
      $skillCountStmt->bind_param("i", $studentId);
      $skillCountStmt->execute();
      $skillCount = (int)$skillCountStmt->get_result()->fetch_assoc()['c'];
      ?>
      <h6 class="mb-0"><?php echo $skillCount; ?> skills</h6>
    </div>
    <a class="btn sb-btn-outline btn-sm" href="profile.php?tab=education"><i class="bi bi-pencil-square me-1"></i>Update Profile</a>
  </div>

  <div class="d-flex align-items-end justify-content-between gap-3 mb-3">
    <div>
      <h3 class="mb-1">Recommended Career Paths</h3>
      <p class="text-muted mb-0">Compatibility is explainable: <strong>40% degree fit + 50% skill fit + 10% profile readiness.</strong></p>
    </div>
  </div>

  <div class="row g-4">
    <?php foreach ($top as $i => $path): ?>
      <div class="col-12">
        <article class="career-card sb-card">
          <div class="career-card-top">
            <div class="career-title-wrap">
              <div class="career-icon"><i class="bi <?php echo htmlspecialchars($path['icon']); ?>"></i></div>
              <div>
                <span class="career-rank">#<?php echo $i + 1; ?> recommendation</span>
                <h4 class="mb-1"><?php echo htmlspecialchars($path['title']); ?></h4>
                <p class="text-muted mb-0"><?php echo htmlspecialchars($path['description']); ?></p>
              </div>
            </div>
            <div class="career-score">
              <div class="career-score-number"><?php echo $path['compatibility']; ?>%</div>
              <small>Compatibility</small>
            </div>
          </div>

          <div class="career-score-grid">
            <div><span>Degree fit</span><strong><?php echo $path['degree_score']; ?>%</strong><div class="career-progress"><span style="width:<?php echo $path['degree_score']; ?>%"></span></div></div>
            <div><span>Skill fit</span><strong><?php echo $path['skill_score']; ?>%</strong><div class="career-progress"><span style="width:<?php echo $path['skill_score']; ?>%"></span></div></div>
            <div><span>Profile readiness</span><strong><?php echo $path['profile_score']; ?>%</strong><div class="career-progress"><span style="width:<?php echo $path['profile_score']; ?>%"></span></div></div>
          </div>

          <div class="row g-3 mt-1">
            <div class="col-lg-4">
              <div class="career-section">
                <h6><i class="bi bi-lightning-charge-fill"></i> Skills to learn</h6>
                <?php if ($path['missing_skills']): ?>
                  <div class="career-tags">
                    <?php foreach ($path['missing_skills'] as $skill): ?><span><?php echo htmlspecialchars($skill); ?></span><?php endforeach; ?>
                  </div>
                <?php else: ?>
                  <div class="career-success"><i class="bi bi-check-circle-fill"></i> You already cover the listed core skills.</div>
                <?php endif; ?>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="career-section">
                <h6><i class="bi bi-mortarboard-fill"></i> Courses to do</h6>
                <ul>
                  <?php foreach ($path['courses'] as $course): ?><li><?php echo htmlspecialchars($course); ?></li><?php endforeach; ?>
                </ul>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="career-section">
                <h6><i class="bi bi-kanban-fill"></i> Projects to build</h6>
                <ul>
                  <?php foreach ($path['projects'] as $project): ?><li><?php echo htmlspecialchars($project); ?></li><?php endforeach; ?>
                </ul>
              </div>
            </div>
          </div>

          <div class="career-resources mt-3">
            <h6><i class="bi bi-link-45deg"></i> Where to learn</h6>
            <div class="row g-2">
              <?php foreach ($path['resources'] as $resource): ?>
                <div class="col-md-4">
                  <a class="career-resource" href="<?php echo htmlspecialchars($resource['url']); ?>" target="_blank" rel="noopener noreferrer">
                    <span><small><?php echo htmlspecialchars($resource['type']); ?></small><strong><?php echo htmlspecialchars($resource['name']); ?></strong></span>
                    <i class="bi bi-box-arrow-up-right"></i>
                  </a>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </article>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="career-method sb-card mt-4">
    <h5><i class="bi bi-info-circle me-1"></i> How this module works</h5>
    <div class="row g-3">
      <div class="col-md-4"><strong>1. Degree fit</strong><p>Checks whether your degree is a direct or related fit for the career path.</p></div>
      <div class="col-md-4"><strong>2. Skill fit</strong><p>Compares your profile skills with the core skills needed for that path.</p></div>
      <div class="col-md-4"><strong>3. Action plan</strong><p>Shows missing skills, courses, projects and trusted learning platforms so you know what to do next.</p></div>
    </div>
    <p class="small text-muted mb-0">The percentage is a SkillBridge rule-based compatibility indicator, not a guarantee of employment, admission or internship selection.</p>
  </div>
</div>
<?php include 'includes/footer.php'; ?>
