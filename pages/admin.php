<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

if (!is_admin()) {
    header("Location: dashboard.php");
    exit();
}

$page_title = "Admin Panel";
require_once '../includes/header.php';

$total_users = get_user_count();
$total_books = get_book_count();
?>

<div class="container py-5 mt-5">
    <div class="row mb-5">
        <div class="col-md-12">
            <h1 class="headline-lg">Admin <span style="color: var(--primary-container);">Dashboard</span></h1>
            <p class="body-lg" style="color: var(--on-surface-variant);">Manage library resources and user accounts.</p>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="stats-card text-center">
                <div class="mb-3" style="color: var(--primary-container);"><i class="fas fa-users fa-3x"></i></div>
                <div class="stats-card-number"><?php echo $total_users; ?></div>
                <div class="stats-card-text">Registered Users</div>
                <a href="#" class="btn-cta-outline btn-sm mt-3">Manage Users</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card text-center">
                <div class="mb-3" style="color: var(--primary-container);"><i class="fas fa-book fa-3x"></i></div>
                <div class="stats-card-number"><?php echo $total_books; ?></div>
                <div class="stats-card-text">Total Books</div>
                <a href="#" class="btn-cta-outline btn-sm mt-3">Manage Books</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card text-center">
                <div class="mb-3" style="color: var(--primary-container);"><i class="fas fa-hand-holding fa-3x"></i></div>
                <div class="stats-card-number">42</div>
                <div class="stats-card-text">Active Borrows</div>
                <a href="#" class="btn-cta-outline btn-sm mt-3">View Reports</a>
            </div>
        </div>
    </div>

    <div class="academic-card">
        <h3 class="headline-md mb-4">System Actions</h3>
        <div class="d-flex gap-3 flex-wrap">
            <button class="btn-cta"><i class="fas fa-plus"></i> Add New Book</button>
            <button class="btn-cta-outline"><i class="fas fa-file-export"></i> Export Data</button>
            <button class="btn-cta-outline"><i class="fas fa-cog"></i> Settings</button>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
