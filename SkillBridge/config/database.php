<?php
/**
 * Database connection
 */
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'skillbridge');
define('DB_PORT', 3307);   // <-- tumhara MySQL isi port par chal raha hai

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error .
        "<br>Make sure XAMPP's Apache + MySQL are running and you have " .
        "imported database/schema.sql and database/sample_data.sql into phpMyAdmin.");
}
$conn->set_charset('utf8mb4');
