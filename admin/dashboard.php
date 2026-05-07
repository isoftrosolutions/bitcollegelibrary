<?php
$page = 'dashboard';
$page_title = 'Dashboard Overview';
require_once 'includes/header.php';

$total_books = get_book_count();
$total_users = get_user_count();

// Count pending payments
$pending_payments = $conn->query("SELECT COUNT(*) as count FROM payments WHERE status = 'pending'")->fetch_assoc()['count'];
?>

<div class="row g-4 mb-5">
    <div class="col-md-12">
        <div class="card-admin">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0 fw-bold"><i class="fas fa-search me-2 text-teal"></i> Search Everything</h5>
            </div>
            <div class="row g-3">
                <div class="col-md-12">
                    <div class="input-group">
                        <input type="text" id="admin-search-input" class="form-control bg-dark border-0 text-white" placeholder="Search for users, books, rentals... type anything to search">
                        <button class="btn btn-register" type="button" id="admin-search-btn"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
            <!-- Search Results -->
            <div id="admin-search-results" class="mt-4"></div>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="card-admin text-center">
            <div class="theme-toggle m-auto mb-3" style="width: 60px; height: 60px; font-size: 1.5rem; cursor: default;">
                <i class="fas fa-book"></i>
            </div>
            <h2 class="fw-bold mb-1"><?php echo $total_books; ?></h2>
            <p class="text-gray mb-0">Total Books</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-admin text-center">
            <div class="theme-toggle m-auto mb-3" style="width: 60px; height: 60px; font-size: 1.5rem; cursor: default; color: #ef4444; background: rgba(239, 68, 68, 0.1);">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <h2 class="fw-bold mb-1"><?php echo $pending_payments; ?></h2>
            <p class="text-gray mb-0">Pending Payments</p>
            <a href="payments.php" class="stretched-link"></a>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-admin text-center">
            <div class="theme-toggle m-auto mb-3" style="width: 60px; height: 60px; font-size: 1.5rem; cursor: default; color: #f59e0b; background: rgba(245, 158, 11, 0.1);">
                <i class="fas fa-clock"></i>
            </div>
            <h2 class="fw-bold mb-1"><?php echo count(get_all_rentals('pending')); ?></h2>
            <p class="text-gray mb-0">Pending Requests</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-admin text-center">
            <div class="theme-toggle m-auto mb-3" style="width: 60px; height: 60px; font-size: 1.5rem; cursor: default; color: #10b981; background: rgba(16, 185, 129, 0.1);">
                <i class="fas fa-hand-holding"></i>
            </div>
            <h2 class="fw-bold mb-1"><?php echo count(get_all_rentals('approved')); ?></h2>
            <p class="text-gray mb-0">Active Borrows</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card-admin h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0 fw-bold">Recent Book Additions</h5>
                <a href="<?= BASE_URL ?>/admin/books/index.php" class="btn btn-sm btn-outline-teal">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-dark-custom">
                    <thead>
                        <tr>
                            <th>Book ID</th>
                            <th>Title</th>
                            <th>Author</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $recent_books = get_all_books('', '', 5);
                        foreach ($recent_books as $book):
                        ?>
                        <tr>
                            <td>#<?php echo htmlspecialchars($book['book_no']); ?></td>
                            <td><?php echo htmlspecialchars($book['book_name']); ?></td>
                            <td><?php echo htmlspecialchars($book['author']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-admin h-100">
            <h5 class="mb-4 fw-bold">Admin Actions</h5>
            <div class="d-grid gap-3">
                <a href="<?= BASE_URL ?>/admin/books/add.php" class="btn btn-register text-start py-3">
                    <i class="fas fa-plus-circle me-2"></i> Add New Book
                </a>
                <a href="<?= BASE_URL ?>/admin/users/add.php" class="btn btn-outline-teal text-start py-3">
                    <i class="fas fa-user-plus me-2"></i> Add New User
                </a>
                <button class="btn btn-outline-teal text-start py-3">
                    <i class="fas fa-file-export me-2"></i> Export Report
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-2">
    <div class="col-md-12">
        <div class="card-admin">
            <h5 class="mb-4 fw-bold"><i class="fas fa-bell me-2 text-teal"></i> Recent Notifications</h5>
            <?php
            $notifications = get_admin_notifications();
            if (empty($notifications)):
            ?>
                <p class="text-gray small">No new notifications.</p>
            <?php else: ?>
                <div class="list-group list-group-flush bg-transparent">
                    <?php foreach ($notifications as $notif): ?>
                        <div class="list-group-item bg-transparent border-0 px-0 py-3 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1 text-white"><?php echo htmlspecialchars($notif['message']); ?></h6>
                                <small class="text-gray"><?php echo date('M d, Y h:i A', strtotime($notif['created_at'])); ?></small>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="<?= BASE_URL ?>/admin/users/verify.php?id=<?php echo $notif['user_id']; ?>&notif_id=<?php echo $notif['id']; ?>" class="btn btn-sm btn-register">Verify User</a>
                                <a href="<?= BASE_URL ?>/admin/clear_notif.php?id=<?php echo $notif['id']; ?>" class="btn btn-sm btn-outline-teal"><i class="fas fa-check"></i></a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
