<?php
require_once '../../config/database.php';
require_once '../../includes/functions.php';

if (!is_admin()) {
    header("Location: /bit/pages/login.php");
    exit();
}

if (isset($_GET['sn'])) {
    $sn = sanitize_input($_GET['sn']);
    
    // Get image name to delete file
    $res = $conn->query("SELECT image FROM library_books WHERE sn = '$sn'");
    if ($row = $res->fetch_assoc()) {
        if ($row['image'] && file_exists('../../assets/uploads/books/' . $row['image'])) {
            unlink('../../assets/uploads/books/' . $row['image']);
        }
    }
    
    $conn->query("DELETE FROM library_books WHERE sn = '$sn'");
}

header("Location: index.php");
exit();
?>
