<?php
/** Common footer + shared scripts. */
?>
<footer class="sb-footer">
  <div class="container text-center">
    <p class="mb-0">&copy; <?php echo date('Y'); ?> SkillBridge — Skill-Based Internship & Project Matching System</p>
  </div>
</footer>
<?php if (isset($_SESSION['student_id'])): ?></main><?php endif; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/script.js"></script>
</body>
</html>
