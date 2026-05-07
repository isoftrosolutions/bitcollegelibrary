<?php
session_start();
session_destroy();
$_h = $_SERVER['HTTP_HOST'] ?? 'localhost';
$_base = (strpos($_h, 'localhost') !== false || $_h === '127.0.0.1') ? '/bit' : '';
header("Location: " . $_base . "/pages/login.php");
exit();
?>