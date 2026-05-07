<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>BIT Library</title>
    <meta name="description" content="Birgunj Institute of Technology - Digital Library Platform for library access, notes, resources and academic services.">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700&family=Public+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Design System CSS -->
    <link rel="stylesheet" href="/bit/css/design-system.css">

    <!-- Modern CSS (navbar & base styles) -->
    <link rel="stylesheet" href="/bit/css/modern.css">

    <!-- Premium CSS (page content styles) -->
    <link rel="stylesheet" href="/bit/css/premium.css">

    <link rel="icon" type="image/png" href="/bit/assets/images/logo.png">

    <script>
        // Prevent theme flicker
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center me-auto" href="/bit/index.php">
            <img src="/bit/assets/images/logo.png" alt="BIT Logo" class="brand-logo">
            <div class="brand-text ms-2">
                <span class="brand-title">BIT Library</span>
            </div>
        </a>

        <!-- Mobile Actions Area -->
        <div class="d-flex align-items-center d-lg-none gap-2">
            <button class="btn btn-link text-gray p-1" type="button" onclick="toggleMobileSearch()">
                <i class="fas fa-search"></i>
            </button>
            <div id="theme-toggle-mobile" class="theme-toggle">
                <i class="fas fa-moon"></i>
            </div>
            <!-- Navbar Toggler -->
            <button class="navbar-toggler border-0 p-1 ms-1" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNav" aria-controls="navbarNav"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>

        <!-- Mobile Search Bar (Hidden by default) -->
        <div id="mobile-search-bar" class="d-lg-none w-100 mt-2 d-none">
            <form action="/bit/pages/books.php" method="GET" class="position-relative">
                <input type="text" name="search" class="form-control shadow-sm rounded-pill py-2 ps-4"
                       placeholder="Search books, authors..." autofocus>
                <button type="submit" class="btn position-absolute top-50 end-0 translate-middle-y text-primary pe-3">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <!-- Main Menu Collapse -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Links -->
            <ul class="navbar-nav mx-lg-auto mb-3 mb-lg-0 mt-3 mt-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) === 'index.php') ? 'active' : ''; ?>"
                       href="/bit/index.php">Library Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) === 'books.php') ? 'active' : ''; ?>"
                       href="/bit/pages/books.php">Books Catalog</a>
                </li>
                <li class="nav-item d-lg-none">
                    <a class="nav-link" href="/bit/pages/latest.php">Latest Arrivals</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://bit.edu.np/notices/" target="_blank">Notices</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://bit.edu.np/admission/" target="_blank">Admission</a>
                </li>
            </ul>

            <!-- Desktop/Mobile Unified Actions -->
            <div class="d-flex flex-column flex-lg-row align-items-lg-center">
                <!-- Theme Toggle (Desktop Only) -->
                <div id="theme-toggle" class="theme-toggle d-none d-lg-flex me-lg-3">
                    <i class="fas fa-moon"></i>
                </div>

                <!-- Search (Visible on Desktop) -->
                <form action="/bit/pages/books.php" method="GET"
                      class="header-search d-none d-lg-block mb-3 mb-lg-0 me-lg-4 order-last order-lg-0">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-0 text-gray pe-0">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control" placeholder="Search resources...">
                    </div>
                </form>

                <!-- Auth Section -->
                <div class="auth-group d-flex align-items-center mt-3 mt-lg-0">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="dropdown w-100 w-lg-auto">
                            <a class="nav-link dropdown-toggle user-dropdown-btn px-3 py-2 w-100 d-flex justify-content-between align-items-center rounded-pill"
                               href="#" id="navbarDropdown" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar-sm me-2">
                                        <?php
                                        $name_parts = explode(' ', $_SESSION['user_name'] ?? 'User');
                                        echo strtoupper(substr($name_parts[0], 0, 1));
                                        ?>
                                    </div>
                                    <span class="fw-semibold">
                                        <?php echo htmlspecialchars($name_parts[0]); ?>
                                    </span>
                                </div>
                                <i class="fas fa-chevron-down ms-2 opacity-50" style="font-size:10px;"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow-lg border-0 mt-2 p-2"
                                aria-labelledby="navbarDropdown">
                                <li class="px-3 py-2 mb-2 border-bottom border-secondary">
                                    <span class="d-block text-muted" style="font-size:11px;">Signed in as</span>
                                    <span class="d-block fw-bold text-white">
                                        <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?>
                                    </span>
                                </li>
                                <li><a class="dropdown-item rounded-2 py-2 mb-1" href="/bit/pages/profile.php">
                                    <i class="fas fa-id-card me-2 text-primary"></i> My Profile</a></li>
                                <li><a class="dropdown-item rounded-2 py-2 mb-1" href="/bit/pages/dashboard.php">
                                    <i class="fas fa-tachometer-alt me-2 text-info"></i> Dashboard</a></li>
                                <?php if (is_admin()): ?>
                                <li><a class="dropdown-item rounded-2 py-2 mb-1" href="/bit/admin/dashboard.php">
                                    <i class="fas fa-cog me-2 text-warning"></i> Admin Panel</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider opacity-10 my-1"></li>
                                <li><a class="dropdown-item rounded-2 py-2 text-danger" href="/bit/logout.php">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <div class="d-flex gap-2 w-100 w-lg-auto">
                            <a href="/bit/pages/login.php" class="btn-login text-decoration-none flex-grow-1 flex-lg-grow-0">Login</a>
                            <a href="/bit/pages/register.php" class="btn-register text-decoration-none flex-grow-1 flex-lg-grow-0">Join</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile Bottom Navigation -->
<div class="mobile-bottom-nav d-flex justify-content-around d-lg-none">
    <a href="/bit/index.php" class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) === 'index.php') ? 'active' : ''; ?>">
        <i class="fas fa-home"></i>
        <span>Home</span>
    </a>
    <a href="/bit/pages/books.php" class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) === 'books.php') ? 'active' : ''; ?>">
        <i class="fas fa-book"></i>
        <span>Books</span>
    </a>
    <a href="<?php echo isset($_SESSION['user_id']) ? '/bit/pages/profile.php' : '/bit/pages/login.php'; ?>"
       class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) === 'profile.php') ? 'active' : ''; ?>">
        <i class="fas fa-user"></i>
        <span>Profile</span>
    </a>
    <button class="nav-item border-0 bg-transparent" onclick="toggleMobileSearch()">
        <i class="fas fa-search"></i>
        <span>Search</span>
    </button>
</div>

<script src="/bit/js/theme-toggle.js"></script>
<script>
    function toggleMobileSearch() {
        const searchBar = document.getElementById('mobile-search-bar');
        searchBar.classList.toggle('d-none');
        if (!searchBar.classList.contains('d-none')) {
            searchBar.querySelector('input').focus();
        }
    }
</script>

<div class="content-wrapper">
