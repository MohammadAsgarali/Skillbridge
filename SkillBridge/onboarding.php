<?php
require_once 'config/constants.php'; require_once 'config/database.php'; require_once 'includes/functions.php'; require_once 'includes/auth.php';
requireStudentLogin();
$studentId=currentStudentId();
// Safe migration for existing installations.
$col=$conn->query("SHOW COLUMNS FROM students LIKE 'career_domain'");
if($col && $col->num_rows===0) $conn->query("ALTER TABLE students ADD COLUMN career_domain VARCHAR(100) DEFAULT NULL");
$student=$conn->query("SELECT course,career_domain,college FROM students WHERE id=".(int)$studentId)->fetch_assoc();
$course=$student['course']??''; $domain=$student['career_domain']??'';
require_once 'matching/career_recommendation.php'; require_once 'matching/domain_catalog.php';
$catalog=sbDomainCatalog();
if($_SERVER['REQUEST_METHOD']==='POST'){
  $course=sanitize($conn,$_POST['course']??''); $domain=sanitize($conn,$_POST['career_domain']??'');
  if(!isset($catalog[$domain])) { setFlash('error','Please select a valid career domain.'); redirect('onboarding.php'); }
  $stmt=$conn->prepare("UPDATE students SET course=?,career_domain=? WHERE id=?"); $stmt->bind_param('ssi',$course,$domain,$studentId); $stmt->execute();
  setFlash('success','Your domain and course are saved. Your personalized roadmap is ready.'); redirect('career_roadmap.php');
}
$pageTitle='Choose Your Career Direction'; include 'includes/header.php'; include 'includes/navbar.php';
?>
<div class="container py-4">
 <div class="career-hero mb-4"><div><span class="career-eyebrow">STEP 1 · PERSONALIZED CAREER SETUP</span><h1>Tell us your course and career domain</h1><p class="mb-0">SkillBridge will use these choices to build the skills roadmap first. After you learn and add those skills, your compatibility percentage will update automatically.</p></div><i class="bi bi-signpost-split career-hero-icon"></i></div>
 <?php showFlash(); ?>
 <form method="POST" class="sb-card p-4">
  <div class="row g-4">
   <div class="col-md-5"><label class="form-label fw-bold">Which course are you from?</label><select name="course" class="form-select form-select-lg" required><option value="">Select course</option><?php foreach(sbCourseOptions() as $c): ?><option value="<?=htmlspecialchars($c)?>" <?=$course===$c?'selected':''?>><?=htmlspecialchars($c)?></option><?php endforeach; ?></select><small class="text-muted">Example: B.Com, BBA, BCA, MCA, B.Tech, M.Tech</small></div>
   <div class="col-md-7"><label class="form-label fw-bold">Which domain do you want to build your career in?</label><select name="career_domain" class="form-select form-select-lg" required><option value="">Select domain</option><?php foreach($catalog as $id=>$d): ?><option value="<?=htmlspecialchars($id)?>" <?=$domain===$id?'selected':''?>><?=htmlspecialchars($d['title'])?></option><?php endforeach; ?></select><small class="text-muted">Choose the domain you want to learn toward. You can change it later.</small></div>
  </div>
  <div class="career-onboarding-flow mt-4"><div><b>1. Course + Domain</b><span>You tell us your direction</span></div><i class="bi bi-arrow-right"></i><div><b>2. Skill Roadmap</b><span>Required skills are shown</span></div><i class="bi bi-arrow-right"></i><div><b>3. Learn + Add Skills</b><span>Update your profile</span></div><i class="bi bi-arrow-right"></i><div><b>4. Match + Opportunities</b><span>Compatibility, projects & internships</span></div></div>
  <button class="btn sb-btn-primary btn-lg mt-4">Build My Roadmap <i class="bi bi-arrow-right ms-1"></i></button>
 </form>
</div>
<?php include 'includes/footer.php'; ?>
