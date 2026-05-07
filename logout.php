<?php
session_start();
session_destroy();
header("Location: /bit/pages/login.php");
exit();
?>