<?php
// Diagnostic script for BIT Library System
// Upload this to your production server to identify 500 error causes

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>BIT Library System - Production Diagnostics</h1>";
echo "<style>body{font-family:Arial,sans-serif;margin:20px;} .success{color:green;} .error{color:red;} .warning{color:orange;}</style>";

// Check PHP version
echo "<h2>PHP Environment</h2>";
echo "PHP Version: " . PHP_VERSION . "<br>";
echo "Server: " . $_SERVER['SERVER_SOFTWARE'] . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br><br>";

// Check required extensions
echo "<h2>Required PHP Extensions</h2>";
$required_extensions = ['mysqli', 'mbstring', 'json', 'session', 'fileinfo'];
foreach ($required_extensions as $ext) {
    $status = extension_loaded($ext) ? "<span class='success'>✓ Loaded</span>" : "<span class='error'>✗ Missing</span>";
    echo "Extension '$ext': $status<br>";
}
echo "<br>";

// Test database connection
echo "<h2>Database Connection Test</h2>";
$host = 'localhost';
$user = 'ektamultp_bit';
$pass = 'zDJ+iZNrsU,-YKp4';
$db = 'ektamultp_bit';

echo "Attempting connection to: $host<br>";
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo "<span class='error'>Database Connection Failed: " . $conn->connect_error . "</span><br>";
} else {
    echo "<span class='success'>Database Connection Successful</span><br>";

    // Test a simple query
    $result = $conn->query("SHOW TABLES");
    if ($result) {
        echo "Tables found: " . $result->num_rows . "<br>";
    } else {
        echo "<span class='error'>Query failed: " . $conn->error . "</span><br>";
    }
    $conn->close();
}
echo "<br>";

// Check file permissions
echo "<h2>File Permissions Check</h2>";
$files_to_check = [
    'config/database.php',
    'config/mail.php',
    'includes/functions.php',
    'includes/PHPMailer/PHPMailer.php'
];

foreach ($files_to_check as $file) {
    if (file_exists($file)) {
        $perms = substr(sprintf('%o', fileperms($file)), -4);
        $readable = is_readable($file) ? "<span class='success'>Readable</span>" : "<span class='error'>Not Readable</span>";
        echo "$file: $perms - $readable<br>";
    } else {
        echo "<span class='error'>$file: File not found</span><br>";
    }
}
echo "<br>";

// Check PHPMailer
echo "<h2>PHPMailer Check</h2>";
if (file_exists('includes/PHPMailer/PHPMailer.php')) {
    require_once 'includes/PHPMailer/PHPMailer.php';
    if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
        echo "<span class='success'>PHPMailer class loaded successfully</span><br>";
    } else {
        echo "<span class='error'>PHPMailer class not found</span><br>";
    }
} else {
    echo "<span class='error'>PHPMailer.php not found</span><br>";
}

echo "<br><h2>Environment Variables</h2>";
echo "HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'Not set') . "<br>";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'Not set') . "<br>";
echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'Not set') . "<br>";

echo "<br><h2>Recommendations</h2>";
echo "<ul>";
echo "<li>Ensure all required PHP extensions are enabled in your hosting control panel</li>";
echo "<li>Check that database credentials are correct</li>";
echo "<li>Verify file permissions are set to 644 for files and 755 for directories</li>";
echo "<li>Check server error logs for detailed error messages</li>";
echo "<li>Ensure PHPMailer files are uploaded correctly</li>";
echo "</ul>";
?>