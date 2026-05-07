<?php
session_start();
session_destroy();
$_h = $_SERVER['HTTP_HOST'] ?? 'localhost';
$_base = (str_contains($_h, 'localhost') || $_h === '127.0.0.1') ? '/bit' : '';
header("Location: " . $_base . "/pages/login.php");
exit();
?>