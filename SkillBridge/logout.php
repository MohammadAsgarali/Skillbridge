<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$_SESSION = [];
session_destroy();
require_once 'config/constants.php';
header("Location: " . BASE_URL . "index.php");
exit;
