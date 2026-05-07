<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user = get_user_info($_SESSION['user_id']);
$page_title = "Dashboard";
require_once '../includes/header.php';
?>

<div class="container py-5 mt-5">
    <div class="row mb-5">
        <div class="col-md-12">
            <h1 class="headline-lg">Welcome Back, <span style="color: var(--primary-container);"><?php echo explode(' ', $user['name'])[0]; ?>!</span></h1>
            <p class="body-lg" style="color: var(--on-surface-variant);">Here's an overview of your library activity.</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- User Info Card -->
        <div class="col-lg-4">
            <div class="academic-card">
                <div class="text-center mb-4">
                     <div class="d-inline-flex mb-3" style="width: 60px; height: 60px; font-size: 1.5rem; background: var(--primary-fixed); border-radius: var(--radius-default); align-items: center; justify-content: center; color: var(--primary-container);">
                        <i class="fas fa-user"></i>
                    </div>
                    <h4><?php echo htmlspecialchars($user['name']); ?></h4>
                    <p class="small" style="color: var(--on-surface-variant);"><?php echo htmlspecialchars($user['email']); ?></p>
                </div>

                <div class="row g-2 mb-4">
                    <div class="col-6">
                        <div class="p-2 rounded text-center" style="background: var(--surface-container);">
                            <small class="d-block" style="color: var(--on-surface-variant);">Faculty</small>
                            <span class="small fw-bold"><?php echo ucfirst($user['faculty']); ?></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 rounded text-center" style="background: var(--surface-container);">
                            <small class="d-block" style="color: var(--on-surface-variant);">Year</small>
                            <span class="small fw-bold"><?php echo ucfirst($user['year']); ?> Year</span>
                        </div>
                    </div>
                </div>

                <a href="profile.php" class="btn-cta-outline btn-sm w-100 mb-2">
                    <i class="fas fa-edit"></i> Edit Profile
                </a>
                <a href="library-card.php" class="btn-cta btn-sm w-100">
                    <i class="fas fa-id-card"></i> Library Card
                </a>
            </div>
        </div>

        <!-- Stats Grid -->
                <?php
                $rentals = get_user_rentals($user['id']);
                $borrowed_count = 0;
                $pending_count = 0;
                foreach($rentals as $r) {
                    if($r['status'] == 'approved') $borrowed_count++;
                    if($r['status'] == 'pending') $pending_count++;
                }
                ?>
                <div class="col-md-6">
                    <div class="dashboard-stat-card">
                        <div class="stat-icon stat-icon-primary"><i class="fas fa-book"></i></div>
                        <div class="stat-content">
                            <span class="stat-value"><?php echo $borrowed_count; ?></span>
                            <span class="stat-label">Books Borrowed</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="dashboard-stat-card">
                        <div class="stat-icon" style="background: var(--secondary-fixed); color: var(--on-secondary-fixed-variant);"><i class="fas fa-clock"></i></div>
                        <div class="stat-content">
                            <span class="stat-value" style="color: var(--secondary-container);"><?php echo $pending_count; ?></span>
                            <span class="stat-label">Pending Requests</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="dashboard-stat-card">
                        <div class="stat-icon" style="background: #d1fae5; color: #059669;"><i class="fas fa-bell"></i></div>
                        <div class="stat-content">
                            <span class="stat-value" style="color: #059669;"><?php echo count(get_user_notifications($user['id'])); ?></span>
                            <span class="stat-label">Total Notifications</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="dashboard-stat-card">
                        <div class="stat-icon" style="background: #dbeafe; color: #2563eb;"><i class="fas fa-user-shield"></i></div>
                        <div class="stat-content">
                            <span class="stat-value" style="color: #2563eb;"><?php echo ucfirst($user['role']); ?></span>
                            <span class="stat-label">Account Role</span>
                        </div>
                    </div>
                </div>
    </div>

    <!-- Recent Activity Table (Rentals) -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card book-card border-0 shadow-sm p-4">
                <h4 class="mb-4">My Rental Requests</h4>
                <div class="table-responsive">
                    <table class="table table-dark table-hover border-0">
                        <thead class="text-gray">
                            <tr>
                                <th>Request Date</th>
                                <th>Book Title</th>
                                <th>Status</th>
                                <th>Rental Date</th>
                                <th>Admin Remarks</th>
                            </tr>
                        </thead>
                        <tbody class="border-0">
                            <?php
                            $user_rentals = get_user_rentals($user['id']);
                            if (empty($user_rentals)):
                            ?>
                            <tr><td colspan="5" class="text-center text-gray py-4">No rental requests found.</td></tr>
                            <?php else: ?>
                                <?php foreach ($user_rentals as $rental): ?>
                                <tr>
                                    <td><?php echo date('M d, Y', strtotime($rental['request_date'])); ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($rental['book_name']); ?></strong><br>
                                        <small class="text-gray">#<?php echo htmlspecialchars($rental['book_no']); ?></small>
                                    </td>
                                    <td>
                                        <span class="badge <?php 
                                            echo $rental['status'] == 'approved' ? 'bg-success' : 
                                                ($rental['status'] == 'pending' ? 'bg-warning' : 
                                                ($rental['status'] == 'rejected' ? 'bg-danger' : 'bg-info')); 
                                        ?>">
                                            <?php echo ucfirst($rental['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo $rental['rental_date'] ? date('M d, Y', strtotime($rental['rental_date'])) : '-'; ?></td>
                                    <td><small class="text-gray"><?php echo htmlspecialchars($rental['admin_remarks'] ?: 'No remarks'); ?></small></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recommendations -->
    <div class="row mt-5">
        <div class="col-12">
            <h4 class="mb-4">Recommended for You</h4>
            <div class="row g-4">
                <?php
                $faculty = $user['faculty'];
                $recommended_books = get_all_books($faculty, '', 4);
                foreach ($recommended_books as $book):
                ?>
                <div class="col-md-3">
                    <div class="book-card p-3">
                        <div class="book-img-placeholder mb-3" style="height: 120px; font-size: 2rem;">
                            <i class="fas fa-star text-teal"></i>
                        </div>
                        <h6 class="book-title small"><?php echo htmlspecialchars(substr($book["book_name"], 0, 40)); ?>...</h6>
                        <a href="books.php?sn=<?php echo $book['sn']; ?>" class="btn btn-sm btn-register w-100">Details</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>