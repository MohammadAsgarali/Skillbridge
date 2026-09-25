<?php
/**
 * Core matching helper: fetch a student's skills and an opportunity's
 * required skills, and find the overlap between them.
 * Member 3 (Mohtashim) - Matching Engine
 */

function getStudentSkills($conn, $studentId) {
    $skills = [];
    $stmt = $conn->prepare(
        "SELECT s.id, s.skill_name, ss.proficiency
         FROM student_skills ss
         JOIN skills s ON s.id = ss.skill_id
         WHERE ss.student_id = ?"
    );
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $skills[$row['id']] = $row; // keyed by skill_id for fast lookup
    }
    return $skills;
}

function getOpportunityRequiredSkills($conn, $opportunityId) {
    $skills = [];
    $stmt = $conn->prepare(
        "SELECT s.id, s.skill_name, os.required_level
         FROM opportunity_skills os
         JOIN skills s ON s.id = os.skill_id
         WHERE os.opportunity_id = ?"
    );
    $stmt->bind_param("i", $opportunityId);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $skills[$row['id']] = $row;
    }
    return $skills;
}

/**
 * Returns ['matched' => [...skill rows...], 'missing' => [...skill rows...]]
 */
function getMatchedSkills($studentSkills, $requiredSkills) {
    $matched = [];
    $missing = [];
    foreach ($requiredSkills as $skillId => $skill) {
        if (isset($studentSkills[$skillId])) {
            $matched[] = $skill;
        } else {
            $missing[] = $skill;
        }
    }
    return ['matched' => $matched, 'missing' => $missing];
}
