<?php
// Mail configuration - loads settings from database

// Fetch mail settings from database
$query = "SELECT * FROM mail_settings WHERE id = 1 LIMIT 1";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    $mail_settings = $result->fetch_assoc();

    // Define mail constants
    define('MAIL_HOST', $mail_settings['mail_host']);
    define('MAIL_USERNAME', $mail_settings['mail_username']);
    define('MAIL_PASSWORD', $mail_settings['mail_password']);
    define('MAIL_PORT', $mail_settings['mail_port']);
    define('MAIL_ENCRYPTION', $mail_settings['mail_encryption']);
    define('MAIL_FROM_EMAIL', $mail_settings['mail_from_email']);
    define('MAIL_FROM_NAME', $mail_settings['mail_from_name']);
} else {
    // Fallback configuration for demo/development
    define('MAIL_HOST', 'smtp.gmail.com');
    define('MAIL_USERNAME', 'your-email@gmail.com');
    define('MAIL_PASSWORD', 'your-app-password');
    define('MAIL_PORT', 587);
    define('MAIL_ENCRYPTION', 'tls');
    define('MAIL_FROM_EMAIL', 'noreply@yourdomain.com');
    define('MAIL_FROM_NAME', 'Your App Name');
}
?>