<?php
$page = 'users';
$page_title = 'Users Management';
require_once '../includes/header.php';

$search = isset($_GET['search']) ? sanitize_input($_GET['search']) : '';
$sql = "SELECT u.*, sd.name, sd.year, sd.faculty, p.status as payment_status 
        FROM users u 
        LEFT JOIN student_details sd ON u.id = sd.user_id 
        LEFT JOIN (SELECT user_id, status FROM payments ORDER BY created_at DESC LIMIT 1) p ON u.id = p.user_id 
        WHERE 1=1";
if (!empty($search)) {
    $sql .= " AND (sd.name LIKE '%$search%' OR u.email LIKE '%$search%' OR u.card_no LIKE '%$search%')";
}
$sql .= " ORDER BY u.created_at DESC";
$res = $conn->query($sql);
?>

<div class="card-admin">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <form method="GET" class="d-flex" style="max-width: 400px;">
            <div class="input-group">
                <input type="text" name="search" class="form-control bg-dark border-0 text-white" placeholder="Search by name, email or card no..." value="<?php echo htmlspecialchars($search); ?>">
                <button class="btn btn-register" type="submit"><i class="fas fa-search"></i></button>
            </div>
        </form>
        <a href="add.php" class="btn btn-register">
            <i class="fas fa-user-plus me-2"></i> Add New User
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-dark-custom align-middle">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Status</th>
                    <th>Role</th>
                    <th>Faculty</th>
                    <th>Details</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $res->fetch_assoc()): ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="theme-toggle me-3" style="width: 45px; height: 45px; cursor: default;">
                                <i class="fas fa-user text-teal"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-white"><?php echo htmlspecialchars($user['name'] ?: 'No Name'); ?></div>
                                <div class="small text-gray"><?php echo htmlspecialchars($user['email']); ?></div>
                                <div class="text-xs text-muted">@<?php echo htmlspecialchars($user['username'] ?: 'no-username'); ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-column gap-1">
                            <!-- OTP Status -->
                            <?php if ($user['otp_verified']): ?>
                                <span class="badge bg-success-light text-success small" style="font-size: 0.65rem;">
                                    <i class="fas fa-envelope-open me-1"></i> Email OK
                                </span>
                            <?php else: ?>
                                <span class="badge bg-danger-light text-danger small" style="font-size: 0.65rem;">
                                    <i class="fas fa-envelope me-1"></i> OTP Pending
                                </span>
                            <?php endif; ?>

                            <!-- Payment Status -->
                            <?php if ($user['payment_status'] === 'approved'): ?>
                                <span class="badge bg-success-light text-success small" style="font-size: 0.65rem;">
                                    <i class="fas fa-receipt me-1"></i> Fee Paid
                                </span>
                            <?php elseif ($user['payment_status'] === 'rejected'): ?>
                                <span class="badge bg-danger-light text-danger small" style="font-size: 0.65rem;">
                                    <i class="fas fa-times-circle me-1"></i> Fee Rejected
                                </span>
                            <?php elseif ($user['payment_status'] === 'pending'): ?>
                                <span class="badge bg-warning-light text-warning small" style="font-size: 0.65rem;">
                                    <i class="fas fa-hourglass-half me-1"></i> Fee Pending
                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary-light text-gray small" style="font-size: 0.65rem;">
                                    <i class="fas fa-minus me-1"></i> No Fee Slip
                                </span>
                            <?php endif; ?>

                            <!-- Admin Verified -->
                            <?php if ($user['is_verified']): ?>
                                <span class="badge bg-primary-light text-primary small" style="font-size: 0.65rem;">
                                    <i class="fas fa-check-double me-1"></i> Verified
                                </span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-<?php echo ($user['role'] ?? '') == 'admin' ? 'teal' : 'secondary'; ?>">
                            <?php echo ucfirst($user['role'] ?? ''); ?>
                        </span>
                    </td>
                    <td>
                        <div class="small text-white"><?php echo ucfirst($user['faculty'] ?? '-'); ?></div>
                        <div class="small text-gray"><?php echo ucfirst($user['year'] ?? ''); ?> Year</div>
                    </td>
                    <td>
                        <div class="small">Card: <?php echo $user['card_no'] ?: '<span class="text-gray">N/A</span>'; ?></div>
                        <div class="small">CRN: <?php echo $user['crn'] ?: '<span class="text-gray">N/A</span>'; ?></div>
                    </td>
                    <td>
                        <div class="btn-group">
                            <a href="profile.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-outline-info" title="View Profile">
                                <i class="fas fa-eye"></i>
                            </a>
                            <?php if (!$user['is_verified']): ?>
                                <a href="verify.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-outline-warning" title="Verify Account">
                                    <i class="fas fa-user-check"></i>
                                </a>
                            <?php else: ?>
                                <a href="card-preview.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-outline-success" title="Preview Library Card">
                                    <i class="fas fa-id-card"></i>
                                </a>
                            <?php endif; ?>
                            <a href="edit.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-outline-teal" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                <a href="delete.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this user?')" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .bg-success-light { background: rgba(25, 135, 84, 0.1); }
    .bg-warning-light { background: rgba(255, 193, 7, 0.1); }
    .table-dark-custom td { padding: 1rem 0.75rem; }
    .btn-group .btn { margin-right: 5px; border-radius: 4px !important; }
</style>

<?php require_once '../includes/footer.php'; ?>
