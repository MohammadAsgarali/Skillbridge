<?php
/**
 * Small shared helper functions used across the whole project.
 * Member 1 (Junaid)
 */

function sanitize($conn, $value) {
    return htmlspecialchars(trim($conn->real_escape_string($value)));
}

function redirect($path) {
    header("Location: " . BASE_URL . $path);
    exit;
}

function setFlash($type, $message) {
    $_SESSION['flash_type'] = $type;     // 'success' | 'error'
    $_SESSION['flash_message'] = $message;
}

function showFlash() {
    if (isset($_SESSION['flash_message'])) {
        $type = $_SESSION['flash_type'] === 'error' ? 'flash-error' : 'flash-success';
        echo '<div class="flash-message ' . $type . '">' . $_SESSION['flash_message'] . '</div>';
        unset($_SESSION['flash_message'], $_SESSION['flash_type']);
    }
}

// Returns compatibility % rounded, with a colour class for the UI
function matchBadgeClass($percent) {
    if ($percent >= 70) return 'badge-high';
    if ($percent >= 40) return 'badge-mid';
    return 'badge-low';
}
