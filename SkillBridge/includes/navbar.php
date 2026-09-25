<?php
/**
 * SkillBridge application sidebar navigation.
 */
if (session_status() === PHP_SESSION_NONE) session_start();
$isLoggedIn = isset($_SESSION['student_id']);
$currentPage = basename($_SERVER['PHP_SELF']);
function sbNavActive($page) {
    global $currentPage;
    return $currentPage === $page ? 'active' : '';
}
?>
<?php if ($isLoggedIn): ?>
<button class="sb-mobile-toggle" type="button" aria-label="Open navigation" data-sb-sidebar-toggle>
  <i class="bi bi-list"></i>
</button>
<div class="sb-sidebar-backdrop" data-sb-sidebar-backdrop></div>
<aside class="sb-sidebar" data-sb-sidebar>
  <div class="sb-sidebar-head">
    <a class="sb-brand" href="<?php echo BASE_URL; ?>dashboard.php">SkillBridge</a>
    <button class="sb-sidebar-close" type="button" aria-label="Close navigation" data-sb-sidebar-close><i class="bi bi-x-lg"></i></button>
  </div>

  <div class="sb-user-mini">
    <div class="sb-avatar"><?php echo strtoupper(substr($_SESSION['student_name'] ?? 'S', 0, 1)); ?></div>
    <div>
      <strong><?php echo htmlspecialchars($_SESSION['student_name'] ?? 'Student'); ?></strong>
      <small>Student account</small>
    </div>
  </div>

  <div class="sb-nav-label">Workspace</div>
  <nav class="sb-side-nav">
    <a class="sb-side-link <?php echo sbNavActive('dashboard.php'); ?>" href="<?php echo BASE_URL; ?>dashboard.php"><i class="bi bi-grid-1x2-fill"></i><span>Dashboard</span></a>
    <a class="sb-side-link <?php echo sbNavActive('opportunities.php') || sbNavActive('opportunity.php') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>opportunities.php"><i class="bi bi-briefcase-fill"></i><span>Opportunities</span></a>
    <a class="sb-side-link <?php echo sbNavActive('recommendations.php'); ?>" href="<?php echo BASE_URL; ?>recommendations.php"><i class="bi bi-stars"></i><span>Recommendations</span></a>
    <a class="sb-side-link <?php echo sbNavActive('career_roadmap.php'); ?>" href="<?php echo BASE_URL; ?>career_roadmap.php"><i class="bi bi-compass-fill"></i><span>Career Roadmap</span></a>
    <a class="sb-side-link <?php echo sbNavActive('skills.php'); ?>" href="<?php echo BASE_URL; ?>skills.php"><i class="bi bi-lightning-charge-fill"></i><span>My Skills</span></a>
    <a class="sb-side-link <?php echo sbNavActive('applications.php'); ?>" href="<?php echo BASE_URL; ?>applications.php"><i class="bi bi-send-fill"></i><span>Applications</span></a>
    <a class="sb-side-link <?php echo sbNavActive('bookmarks.php'); ?>" href="<?php echo BASE_URL; ?>bookmarks.php"><i class="bi bi-bookmark-fill"></i><span>Bookmarks</span></a>
  </nav>

  <div class="sb-nav-label">Account</div>
  <nav class="sb-side-nav">
    <a class="sb-side-link <?php echo sbNavActive('profile.php'); ?>" href="<?php echo BASE_URL; ?>profile.php"><i class="bi bi-person-fill"></i><span>Profile</span></a>
  </nav>

  <div class="sb-sidebar-footer">
    <a class="sb-logout-link" href="<?php echo BASE_URL; ?>profile.php?tab=account"><i class="bi bi-person-gear"></i><span>Account settings</span></a>
    <small>Logout is available inside Profile → Account.</small>
  </div>
</aside>
<main class="sb-main">
  <section class="sb-page-visual" aria-label="SkillBridge overview">
    <img src="<?php echo BASE_URL; ?>assets/images/skillbridge-visual.png" alt="SkillBridge skills and opportunities illustration">
    <div class="sb-page-visual-copy">
      <span>SKILLBRIDGE</span>
      <strong>Connect your skills with real opportunities.</strong>
    </div>
  </section>
<?php else: ?>
<nav class="sb-public-nav">
  <div class="container d-flex align-items-center justify-content-between">
    <a class="sb-brand" href="<?php echo BASE_URL; ?>index.php">SkillBridge</a>
    <div class="d-flex align-items-center gap-2">
      <a class="nav-link" href="<?php echo BASE_URL; ?>opportunities.php">Opportunities</a>
      <a class="btn sb-btn-outline btn-sm" href="<?php echo BASE_URL; ?>login.php">Login</a>
      <a class="btn sb-btn-primary btn-sm" href="<?php echo BASE_URL; ?>register.php">Register</a>
    </div>
  </div>
</nav>
<?php endif; ?>
