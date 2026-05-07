<?php
session_start();

// Base URL: auto-detected — empty on live domain, /bit on localhost
$_base_host = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('BASE_URL', (strpos($_base_host, 'localhost') !== false || $_base_host === '127.0.0.1') ? '/bit' : '');

// ── Fill in your live server credentials ──
define('DB_HOST', 'localhost');
define('DB_USER', 'your_db_user');
define('DB_PASS', 'your_db_password');
define('DB_NAME', 'your_db_name');
// ─────────────────────────────────────────

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// Errors off in production, on for localhost
if (BASE_URL === '/bit') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
?>
