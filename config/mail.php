<?php
<<<<<<< HEAD
// SMTP Configuration
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_USERNAME', 'pdewbrath@gmail.com'); // Replace with your email
define('MAIL_PASSWORD', '');   // Replace with your app password
define('MAIL_PORT', 587);
define('MAIL_ENCRYPTION', 'tls');
define('MAIL_FROM_EMAIL', 'library@bit.edu.np');
define('MAIL_FROM_NAME', 'BIT Library');
=======
// Load DB connection (session already started inside database.php)
if (!defined('DB_HOST')) {
    require_once __DIR__ . '/database.php';
}

// Fetch mail settings from DB
$_mail_row = null;
$_mail_result = $conn->query("SELECT * FROM mail_settings ORDER BY id ASC LIMIT 1");
if ($_mail_result && $_mail_result->num_rows > 0) {
    $_mail_row = $_mail_result->fetch_assoc();
}

// Fall back to hard-coded defaults if table is empty / missing
define('MAIL_HOST',       $_mail_row['mail_host']       ?? 'smtp.gmail.com');
define('MAIL_USERNAME',   $_mail_row['mail_username']   ?? 'pdewbrath@gmail.com');
define('MAIL_PASSWORD',   $_mail_row['mail_password']   ?? 'nkok jcyl wztn daev');
define('MAIL_PORT',       (int)($_mail_row['mail_port'] ?? 587));
define('MAIL_ENCRYPTION', $_mail_row['mail_encryption'] ?? 'tls');
define('MAIL_FROM_EMAIL', $_mail_row['mail_from_email'] ?? 'library@bit.edu.np');
define('MAIL_FROM_NAME',  $_mail_row['mail_from_name']  ?? 'BIT Library');
>>>>>>> 821117b ( database file  fix)
?>
