<?php
/**
 * Admin authentication guard.
 * Member 1 (Junaid)
 * Include this file at the top of every file inside /admin/.
 */
if (session_status() === PHP_SESSION_NONE) session_start();

function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

function requireAdminLogin() {
    if (!isAdminLoggedIn()) {
        header("Location: " . BASE_URL . "admin/login.php");
        exit;
    }
}
