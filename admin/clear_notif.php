<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

if (!is_admin()) {
    header("Location: " . BASE_URL . "/pages/login.php");
    exit();
}

if (isset($_GET['id'])) {
    mark_notification_read($_GET['id']);
}

header("Location: dashboard.php");
exit();
?>
