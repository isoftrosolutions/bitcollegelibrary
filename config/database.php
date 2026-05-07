<?php
session_start();

// Base URL: auto-detected from host
$_base_host = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('BASE_URL', (str_contains($_base_host, 'localhost') || $_base_host === '127.0.0.1') ? '/bit' : '');

// Database configuration
if (str_contains($_base_host, 'localhost') || $_base_host === '127.0.0.1') {
    // Local development
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'bit');
} else {
    // Production/Remote server
    define('DB_HOST', 'deschoolproject.com');
    define('DB_USER', 'ektamultp_bit');
    define('DB_PASS', 'zDJ+iZNrsU,-YKp4');
    define('DB_NAME', 'ektamultp_bit');
}

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8mb4");

// Error reporting: off in production, on for localhost
if (BASE_URL === '/bit') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
?>