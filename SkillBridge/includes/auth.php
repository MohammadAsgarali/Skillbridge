<?php
/**
 * Student authentication guard.
 * Member 1 (Junaid)
 * Include this file (after session_start()) at the top of any page
 * that only a logged-in STUDENT should see.
 */
if (session_status() === PHP_SESSION_NONE) session_start();

function isStudentLoggedIn() {
    return isset($_SESSION['student_id']);
}

function requireStudentLogin() {
    if (!isStudentLoggedIn()) {
        $_SESSION['flash_type'] = 'error';
        $_SESSION['flash_message'] = 'Please login first.';
        header("Location: " . BASE_URL . "login.php");
        exit;
    }
}

function currentStudentId() {
    return $_SESSION['student_id'] ?? null;
}
