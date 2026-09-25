/**
 * Client-side compatibility % calculator.
 * Member 3 (Mohtashim) - Matching Engine (JS side)
 * Mirrors matching/compatibility.php so the UI can show an instant
 * preview (e.g. while a student is still adding skills on skills.php)
 * without waiting for a full page reload. The PHP version is always the
 * authoritative score that gets saved/shown on real pages.
 */
function calculateMatchPercent(studentSkillIds, requiredSkillIds) {
    if (!requiredSkillIds.length) return 0;
    const studentSet = new Set(studentSkillIds);
    const matched = requiredSkillIds.filter(id => studentSet.has(id));
    return Math.round((matched.length / requiredSkillIds.length) * 100);
}

// Example use: fetch skills via API and preview a match live
async function previewMatch(studentSkillIds, opportunityId) {
    const res = await fetch(`api/opportunities.php`);
    const opportunities = await res.json();
    const opp = opportunities.find(o => o.id == opportunityId);
    return opp ? opp.match_percent : null;
}
