<?php
/**
 * Compatibility score calculation.
 * Member 3 (Mohtashim) - Matching Engine
 * This is the PHP-side (server, authoritative) version of the same logic
 * assets/js/matching.js applies on the client for instant UI feedback.
 */
require_once __DIR__ . '/skill_match.php';

/**
 * Percentage = (skills the student has that are required) / (total required skills) * 100
 * Returns 0 if the opportunity has no listed required skills.
 */
function calculateCompatibility($conn, $studentId, $opportunityId) {
    $studentSkills  = getStudentSkills($conn, $studentId);
    $requiredSkills = getOpportunityRequiredSkills($conn, $opportunityId);

    if (count($requiredSkills) === 0) return 0;

    $result = getMatchedSkills($studentSkills, $requiredSkills);
    $percent = (count($result['matched']) / count($requiredSkills)) * 100;
    return round($percent);
}

/**
 * Returns every opportunity with a computed compatibility % for this student,
 * sorted highest match first. Used by dashboard.php & recommendations.php.
 */
function getRankedOpportunities($conn, $studentId, $limit = null) {
    $ranked = [];
    $opps = $conn->query("SELECT * FROM opportunities ORDER BY created_at DESC");
    while ($opp = $opps->fetch_assoc()) {
        $opp['match_percent'] = calculateCompatibility($conn, $studentId, $opp['id']);
        $ranked[] = $opp;
    }
    usort($ranked, function ($a, $b) {
        return $b['match_percent'] <=> $a['match_percent'];
    });
    if ($limit !== null) {
        $ranked = array_slice($ranked, 0, $limit);
    }
    return $ranked;
}
