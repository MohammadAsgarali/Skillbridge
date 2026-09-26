// General site behaviour — Member 2 (Asgar)
document.addEventListener('DOMContentLoaded', function () {
    // Auto-hide flash messages after 4 seconds
    const flash = document.querySelector('.flash-message');
    if (flash) {
        setTimeout(() => { flash.style.display = 'none'; }, 4000);
    }
});

// SkillBridge sidebar interactions
(function () {
  const sidebar = document.querySelector('[data-sb-sidebar]');
  const backdrop = document.querySelector('[data-sb-sidebar-backdrop]');
  const openBtn = document.querySelector('[data-sb-sidebar-toggle]');
  const closeBtn = document.querySelector('[data-sb-sidebar-close]');
  if (!sidebar) return;
  const setOpen = (open) => {
    sidebar.classList.toggle('is-open', open);
    if (backdrop) backdrop.classList.toggle('is-visible', open);
    document.body.classList.toggle('sb-sidebar-open', open);
  };
  openBtn?.addEventListener('click', () => setOpen(true));
  closeBtn?.addEventListener('click', () => setOpen(false));
  backdrop?.addEventListener('click', () => setOpen(false));
  sidebar.querySelectorAll('a').forEach(a => a.addEventListener('click', () => setOpen(false)));
})();

// Profile section switching without losing the selected section on submit.
document.addEventListener('click', function (event) {
  const tab = event.target.closest('[data-profile-tab]');
  if (!tab) return;
  document.querySelectorAll('[data-profile-tab]').forEach(x => x.classList.remove('active'));
  document.querySelectorAll('[data-profile-panel]').forEach(x => x.classList.add('d-none'));
  tab.classList.add('active');
  const panel = document.querySelector('[data-profile-panel="' + tab.dataset.profileTab + '"]');
  panel?.classList.remove('d-none');
});
