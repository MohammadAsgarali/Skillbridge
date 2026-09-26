/**
 * Dashboard-page behaviour: loads live recommendations via the API
 * so the dashboard cards can refresh without a full page reload.
 * Member 3 (Mohtashim)
 */
async function loadDashboardRecommendations() {
    const container = document.getElementById('dash-recommendations');
    if (!container) return;
    try {
        const res = await fetch('api/recommendations.php?limit=3');
        if (!res.ok) return; // not logged in / no session
        const data = await res.json();
        console.log('Live recommendations loaded:', data);
        // Page already renders these server-side with PHP;
        // this hook is here for further AJAX-driven upgrades.
    } catch (err) {
        console.error('Could not refresh recommendations', err);
    }
}
document.addEventListener('DOMContentLoaded', loadDashboardRecommendations);
