<?php
/**
 * Skill gap detection: tells a student exactly which required skills
 * they are missing for a given opportunity.
 * Member 3 (Mohtashim) - Matching Engine
 */
require_once __DIR__ . '/skill_match.php';

function getSkillGap($conn, $studentId, $opportunityId) {
    $studentSkills  = getStudentSkills($conn, $studentId);
    $requiredSkills = getOpportunityRequiredSkills($conn, $opportunityId);
    $result = getMatchedSkills($studentSkills, $requiredSkills);
    return $result['missing']; // array of skill rows the student still needs
}
