<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if (!is_admin()) {
    header("Location: " . BASE_URL . "/pages/login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Prevent self-deletion
    if ($id != $_SESSION['user_id']) {
        $conn->query("DELETE FROM users WHERE id = $id");
    }
}

header("Location: index.php");
exit();
?>
