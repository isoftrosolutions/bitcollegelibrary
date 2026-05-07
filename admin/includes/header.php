<?php
require_once dirname(dirname(__DIR__)) . '/config/database.php';
require_once dirname(dirname(__DIR__)) . '/includes/functions.php';

if (!is_admin()) {
    header("Location: " . BASE_URL . "/pages/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Admin Panel | BIT Library</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/logo.png">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/modern.css">
    <script>window.BASE_URL = '<?= BASE_URL ?>';</script>
    <script>
        // Prevent theme flicker
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="px-4 mb-5 text-center">
                <img src="<?= BASE_URL ?>/assets/images/logo.png" alt="Logo" style="width: 50px;">
                <h5 class="mt-2 text-teal fw-bold">BIT Admin</h5>
            </div>
            <nav>
                <a href="<?= BASE_URL ?>/admin/dashboard.php" class="nav-link-admin <?php echo $page == 'dashboard' ? 'active' : ''; ?>">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>
                <a href="<?= BASE_URL ?>/admin/books/index.php" class="nav-link-admin <?php echo $page == 'books' ? 'active' : ''; ?>">
                    <i class="fas fa-book"></i> Books Management
                </a>
                <a href="<?= BASE_URL ?>/admin/users/index.php" class="nav-link-admin <?php echo $page == 'users' ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i> Users Management
                </a>
                <a href="<?= BASE_URL ?>/admin/payments.php" class="nav-link-admin <?php echo $page == 'payments' ? 'active' : ''; ?>">
                    <i class="fas fa-file-invoice-dollar"></i> Fee Payments
                </a>
                <a href="<?= BASE_URL ?>/admin/rentals.php" class="nav-link-admin <?php echo $page == 'rentals' ? 'active' : ''; ?>">
                    <i class="fas fa-hand-holding"></i> Rentals Management
                </a>
                <a href="<?= BASE_URL ?>/index.php" class="nav-link-admin">
                    <i class="fas fa-external-link-alt"></i> View Site
                </a>
                <hr class="mx-4 opacity-10">
                <a href="<?= BASE_URL ?>/logout.php" class="nav-link-admin text-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </nav>
        </div>

        <div class="main-content">
            <div class="admin-header rounded-3 mb-5 shadow-sm">
                <div>
                    <h4 class="mb-0 fw-bold"><?php echo $page_title; ?></h4>
                    <span class="text-gray small">Birgunj Institute of Technology <span class="badge bg-primary ms-1">Admin</span></span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="me-4 text-gray d-none d-md-block">Welcome, <strong class="text-white"><?php echo $_SESSION['user_name']; ?></strong></span>
                    <div id="theme-toggle" class="theme-toggle me-3" style="width: 45px; height: 45px; background: var(--bg-secondary); border: 1px solid var(--border-color);">
                        <i class="fas fa-sun"></i>
                    </div>
                </div>
            </div>
