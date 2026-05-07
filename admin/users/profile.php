<?php
$page = 'users';
$page_title = 'User Profile';
require_once '../includes/header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('Invalid user ID'); window.location.href='index.php';</script>";
    exit;
}

$user_id = (int)$_GET['id'];
$user = get_user_info($user_id);

if (!$user) {
    echo "<script>alert('User not found'); window.location.href='index.php';</script>";
    exit;
}

// Get all rentals for this user
function get_user_all_rentals($user_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT r.*, b.book_name, b.book_no, b.author 
                            FROM rentals r 
                            JOIN library_books b ON r.book_sn = b.sn 
                            WHERE r.user_id = ? 
                            ORDER BY r.request_date DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $rentals = [];
    while ($row = $result->fetch_assoc()) {
        $rentals[] = $row;
    }
    return $rentals;
}

$user_rentals = get_user_all_rentals($user_id);

// Check clearance status
$has_outstanding_rentals = false;
foreach ($user_rentals as $rental) {
    if ($rental['status'] == 'approved') {
        $has_outstanding_rentals = true;
        break;
    }
}
?>

<div class="row g-4">
    <!-- User Profile Card -->
    <div class="col-md-4">
        <div class="card-admin">
            <div class="text-center mb-4">
                <div class="theme-toggle" style="width: 100px; height: 100px; margin: 0 auto; cursor: default;">
                    <i class="fas fa-user text-teal" style="font-size: 48px;"></i>
                </div>
                <h3 class="mt-3"><?php echo htmlspecialchars($user['name']); ?></h3>
                <p class="text-gray"><?php echo htmlspecialchars($user['email']); ?></p>
                <span class="badge bg-<?php echo $user['role'] == 'admin' ? 'teal' : 'secondary'; ?>">
                    <?php echo ucfirst($user['role'] ?? ''); ?>
                </span>
            </div>

            <div class="mb-4">
                <h5 class="fw-bold mb-3 text-teal"><i class="fas fa-info-circle me-2"></i> Personal Information</h5>
                <div class="row mb-2">
                    <div class="col-5 text-gray">Faculty:</div>
                    <div class="col-7"><?php echo ucfirst($user['faculty'] ?? ''); ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-5 text-gray">Year:</div>
                    <div class="col-7"><?php echo ucfirst($user['year'] ?? '-'); ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-5 text-gray">Part:</div>
                    <div class="col-7"><?php echo ucfirst($user['part'] ?? '-'); ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-5 text-gray">CRN:</div>
                    <div class="col-7"><?php echo $user['crn'] ?? '-'; ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-5 text-gray">Card No:</div>
                    <div class="col-7"><?php echo $user['card_no'] ?? '-'; ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-5 text-gray">Valid Up To:</div>
                    <div class="col-7"><?php echo $user['valid_upto'] ?? '-'; ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-5 text-gray">Verified:</div>
                    <div class="col-7">
                        <span class="badge <?php echo $user['is_verified'] ? 'bg-success' : 'bg-danger'; ?>">
                            <?php echo $user['is_verified'] ? 'Verified' : 'Not Verified'; ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <h5 class="fw-bold mb-3 text-teal"><i class="fas fa-calendar-alt me-2"></i> Account Details</h5>
                <div class="row mb-2">
                    <div class="col-5 text-gray">Joined:</div>
                    <div class="col-7"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-5 text-gray">Last Updated:</div>
                    <div class="col-7"><?php echo date('M d, Y H:i', strtotime($user['updated_at'])); ?></div>
                </div>
            </div>

            <div class="mb-4">
                <h5 class="fw-bold mb-3 text-teal"><i class="fas fa-shield-alt me-2"></i> Clearance Status</h5>
                <div class="clearance-status">
                    <?php if ($has_outstanding_rentals): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>NOT CLEARED</strong><br>
                            User has outstanding book rentals
                        </div>
                    <?php else: ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>CLEARED</strong><br>
                            All books have been returned
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="d-grid gap-2">
                <a href="edit.php?id=<?php echo $user['id']; ?>" class="btn btn-register">
                    <i class="fas fa-edit me-2"></i> Edit Profile
                </a>
                <?php if ($user['id'] != $_SESSION['user_id']): ?>
                    <a href="delete.php?id=<?php echo $user['id']; ?>" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this user?')">
                        <i class="fas fa-trash me-2"></i> Delete User
                    </a>
                <?php endif; ?>
                <a href="index.php" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Users
                </a>
            </div>
        </div>
    </div>

    <!-- Rental History -->
    <div class="col-md-8">
        <div class="card-admin">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold text-teal">
                    <i class="fas fa-book-reader me-2"></i> Rental History
                </h5>
                <span class="badge bg-teal"><?php echo count($user_rentals); ?> Rentals</span>
            </div>

            <?php if (empty($user_rentals)): ?>
                <div class="text-center text-gray py-4">
                    <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 16px;"></i>
                    <p>No rental history</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-dark-custom">
                        <thead>
                            <tr>
                                <th>Book</th>
                                <th>Book No</th>
                                <th>Status</th>
                                <th>Request Date</th>
                                <th>Rental Date</th>
                                <th>Return Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($user_rentals as $rental): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($rental['book_name']); ?></strong><br>
                                    <small class="text-gray"><?php echo htmlspecialchars($rental['author']); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($rental['book_no']); ?></td>
                                <td>
                                    <span class="badge <?php 
                                        echo $rental['status'] == 'approved' ? 'bg-success' : 
                                            ($rental['status'] == 'pending' ? 'bg-warning' : 
                                            ($rental['status'] == 'rejected' ? 'bg-danger' : 'bg-info')); 
                                    ?>">
                                        <?php echo ucfirst($rental['status'] ?? ''); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($rental['request_date'])); ?></td>
                                <td><?php echo $rental['rental_date'] ? date('M d, Y', strtotime($rental['rental_date'])) : '-'; ?></td>
                                <td><?php echo $rental['return_date'] ? date('M d, Y', strtotime($rental['return_date'])) : '-'; ?></td>
                                <td>
                                    <?php if ($rental['status'] == 'approved'): ?>
                                        <a href="../rentals.php?action=returned&id=<?php echo $rental['id']; ?>" class="btn btn-sm btn-outline-teal">
                                            Mark Returned
                                        </a>
                                    <?php elseif ($rental['status'] == 'pending'): ?>
                                        <a href="../rentals.php?action=approved&id=<?php echo $rental['id']; ?>" class="btn btn-sm btn-outline-success me-1">
                                            Approve
                                        </a>
                                        <a href="../rentals.php?action=rejected&id=<?php echo $rental['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Reject this request?')">
                                            Reject
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
