<?php
require_once 'config/constants.php'; require_once 'config/database.php'; require_once 'includes/functions.php'; require_once 'includes/auth.php'; require_once 'matching/compatibility.php'; require_once 'matching/career_recommendation.php'; require_once 'matching/domain_catalog.php';
requireStudentLogin();
$studentId=currentStudentId();
// Backward-compatible migration.
$col=$conn->query("SHOW COLUMNS FROM students LIKE 'career_domain'");
if($col && $col->num_rows===0) $conn->query("ALTER TABLE students ADD COLUMN career_domain VARCHAR(100) DEFAULT NULL");

$stmt=$conn->prepare("SELECT * FROM students WHERE id=?"); $stmt->bind_param('i',$studentId); $stmt->execute(); $student=$stmt->get_result()->fetch_assoc();
$catalog=sbDomainCatalog(); $domainId=$student['career_domain']??'';
if(!$domainId || !isset($catalog[$domainId])) { redirect('onboarding.php'); }
$domain=$catalog[$domainId];

// Quick-add a roadmap skill using the same Skills profile system the student already uses.
if($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['action']??'')==='add_roadmap_skill'){
  $skillName=trim($_POST['skill_name']??''); $level=in_array($_POST['proficiency']??'', ['Beginner','Intermediate','Advanced'],true)?$_POST['proficiency']:'Beginner';
  if($skillName!==''){
    $stmt=$conn->prepare("SELECT id FROM skills WHERE LOWER(skill_name)=LOWER(?) LIMIT 1"); $stmt->bind_param('s',$skillName); $stmt->execute(); $r=$stmt->get_result()->fetch_assoc();
    if($r){$skillId=(int)$r['id'];} else { $stmt=$conn->prepare("INSERT INTO skills(skill_name,category) VALUES(?,?)"); $cat=$domain['title']; $stmt->bind_param('ss',$skillName,$cat); $stmt->execute(); $skillId=$stmt->insert_id; }
    $stmt=$conn->prepare("INSERT IGNORE INTO student_skills(student_id,skill_id,proficiency) VALUES(?,?,?)"); $stmt->bind_param('iis',$studentId,$skillId,$level); $stmt->execute();
    setFlash('success',$skillName.' added to your skills. Compatibility has been recalculated.');
  }
  redirect('career_roadmap.php');
}

$studentSkills=sbGetStudentSkillNames($conn,$studentId);
$matched=sbMatchedSkills($studentSkills,$domain['skills']);
$missing=array_values(array_diff($domain['skills'],$matched));
$skillScore=sbSkillMatchScore($studentSkills,$domain['skills']);
$degreeScore=sbDomainDegreeFit($student['course']??'', $domain['degrees']);
$profileScore=0; if(!empty($student['course']))$profileScore+=45; if(!empty($student['college']))$profileScore+=25; if(!empty($studentSkills))$profileScore+=30;
$compatibility=round(($degreeScore*.40)+($skillScore*.50)+($profileScore*.10));
$ranked=getRankedOpportunities($conn,$studentId,6);

$pageTitle='My Career Roadmap'; include 'includes/header.php'; include 'includes/navbar.php';
?>
<div class="container py-4 pb-5">
 <?php showFlash(); ?>
 <div class="career-hero mb-4"><div><span class="career-eyebrow">YOUR SELECTED DOMAIN</span><h1><?=htmlspecialchars($domain['title'])?></h1><p class="mb-2">For <strong><?=htmlspecialchars($student['course']?:'Your course')?></strong>, SkillBridge shows the exact skills to build first. As you add those skills, the compatibility percentage changes.</p><a href="onboarding.php" class="btn btn-light btn-sm"><i class="bi bi-pencil me-1"></i>Change Course / Domain</a></div><i class="bi <?=htmlspecialchars($domain['icon'])?> career-hero-icon"></i></div>

 <div class="sb-card p-3 mb-4 career-profile-strip">
  <div><small class="text-muted">Course</small><h5 class="mb-0"><?=htmlspecialchars($student['course']?:'Not selected')?></h5></div>
  <div><small class="text-muted">Domain</small><h6 class="mb-0"><?=htmlspecialchars($domain['title'])?></h6></div>
  <div><small class="text-muted">Roadmap progress</small><h6 class="mb-0"><?=count($matched)?> / <?=count($domain['skills'])?> skills</h6></div>
  <div class="text-center"><div class="career-score-number"><?=max(0,min(100,$compatibility))?>%</div><small>Current compatibility</small></div>
 </div>

 <div class="row g-4">
  <div class="col-lg-8">
   <div class="sb-card p-4 mb-4">
    <div class="d-flex justify-content-between align-items-start gap-3 mb-4"><div><span class="career-rank">STEP-BY-STEP ROADMAP</span><h3 class="mb-1">Skills you need for <?=htmlspecialchars($domain['title'])?></h3><p class="text-muted mb-0">Learn each skill, then add it from this roadmap. Your compatibility updates immediately.</p></div><span class="badge sb-badge badge-high"><?=count($matched)?> / <?=count($domain['skills'])?> complete</span></div>
    <?php foreach($domain['skills'] as $i=>$skill): $isDone=in_array($skill,$matched,true); ?>
      <div class="roadmap-step mb-4"><div class="roadmap-dot <?=$isDone?'done':''?>"><?=$isDone?'✓':($i+1)?></div><div class="roadmap-skill <?=$isDone?'done':''?>"><div><strong><?=htmlspecialchars($skill)?></strong><small><?=$isDone?'Already added to your profile':'Required skill for this domain'?></small></div><?php if($isDone): ?><span class="badge bg-success">Learned / Added</span><?php else: ?><form method="POST" class="d-flex gap-2 align-items-center"><input type="hidden" name="action" value="add_roadmap_skill"><input type="hidden" name="skill_name" value="<?=htmlspecialchars($skill)?>"><select name="proficiency" class="form-select form-select-sm" style="width:125px"><option>Beginner</option><option>Intermediate</option><option>Advanced</option></select><button class="btn sb-btn-primary btn-sm">Add Skill</button></form><?php endif; ?></div></div>
    <?php endforeach; ?>
   </div>

   <div class="sb-card p-4 mb-4"><span class="career-rank">LEARNING PLAN</span><h3>Courses to complete</h3><div class="row g-3 mt-1"><?php foreach($domain['courses'] as $i=>$course): ?><div class="col-md-6"><div class="roadmap-course"><small>Course <?=$i+1?></small><strong><?=htmlspecialchars($course)?></strong></div></div><?php endforeach; ?></div></div>

   <div class="sb-card p-4"><span class="career-rank">PRACTICAL PROOF</span><h3>Projects to build</h3><p class="text-muted">Build these after learning the skills. Projects strengthen your portfolio and help you become internship-ready.</p><div class="row g-3"><?php foreach($domain['projects'] as $i=>$project): ?><div class="col-md-6"><div class="roadmap-course h-100"><small>Project <?=$i+1?></small><strong><?=htmlspecialchars($project)?></strong></div></div><?php endforeach; ?></div></div>
  </div>

  <div class="col-lg-4">
   <div class="sb-card p-4 mb-4"><h5><i class="bi bi-speedometer2 me-1"></i> Compatibility</h5><div class="career-score-number mb-1"><?=$compatibility?>%</div><div class="career-score-grid d-block m-0 p-0 bg-transparent"><div class="mb-3"><span>Course fit</span><strong><?=$degreeScore?>%</strong><div class="career-progress"><span style="width:<?=$degreeScore?>%"></span></div></div><div class="mb-3"><span>Required skill fit</span><strong><?=$skillScore?>%</strong><div class="career-progress"><span style="width:<?=$skillScore?>%"></span></div></div><div><span>Profile readiness</span><strong><?=$profileScore?>%</strong><div class="career-progress"><span style="width:<?=$profileScore?>%"></span></div></div></div><p class="small text-muted mt-3 mb-0">Formula: 40% course fit + 50% domain skill fit + 10% profile readiness.</p></div>
   <div class="sb-card p-4 mb-4"><h5><i class="bi bi-link-45deg me-1"></i> Learn from these resources</h5><?php foreach($domain['resources'] as $r): ?><a class="career-resource mb-2" href="<?=htmlspecialchars($r['url'])?>" target="_blank" rel="noopener"><span><small>Learning Resource</small><strong><?=htmlspecialchars($r['name'])?></strong></span><i class="bi bi-box-arrow-up-right"></i></a><?php endforeach; ?></div>
   <div class="sb-card p-4"><h5><i class="bi bi-briefcase-fill me-1"></i> Internship suggestions</h5><p class="small text-muted">These use your current SkillBridge skill compatibility. Add roadmap skills to improve the match.</p><?php if(empty($ranked)): ?><p class="text-muted">No internships/projects have been posted yet.</p><?php else: foreach($ranked as $opp): ?><div class="internship-card mb-2"><div class="d-flex justify-content-between gap-2"><strong><?=htmlspecialchars($opp['title'])?></strong><span class="match-pill"><?=$opp['match_percent']?>%</span></div><small class="text-muted"><?=htmlspecialchars($opp['company'])?> · <?=htmlspecialchars($opp['type'])?></small><div class="mt-2"><a href="opportunity.php?id=<?=$opp['id']?>" class="btn sb-btn-outline btn-sm">View</a></div></div><?php endforeach; endif; ?></div>
  </div>
 </div>
</div>
<?php include 'includes/footer.php'; ?>
