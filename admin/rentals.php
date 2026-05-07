<?php
$page = 'rentals';
$page_title = 'Rentals Management';
require_once 'includes/header.php';

// Handle status updates
if (isset($_GET['action']) && isset($_GET['id'])) {
    $rental_id = (int)$_GET['id'];
    $action = $_GET['action']; // approved, rejected, returned
    $remarks = isset($_POST['remarks']) ? sanitize_input($_POST['remarks']) : '';
    
    if (update_rental_status($rental_id, $action, $remarks)) {
        echo "<script>alert('Rental status updated successfully!'); window.location.href='rentals.php';</script>";
    } else {
        echo "<script>alert('Failed to update status.');</script>";
    }
}

$pending_rentals = get_all_rentals('pending');
$all_rentals = get_all_rentals();
?>

<div class="row g-4">
    <div class="col-md-12">
        <div class="card-admin mb-4">
            <h5 class="fw-bold mb-4 text-teal"><i class="fas fa-clock me-2"></i> Pending Requests</h5>
            <div class="table-responsive">
                <table class="table table-dark-custom">
                    <thead>
                        <tr>
                            <th>Book</th>
                            <th>User</th>
                            <th>Request Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pending_rentals)): ?>
                            <tr><td colspan="4" class="text-center text-gray py-4">No pending requests</td></tr>
                        <?php else: ?>
                            <?php foreach ($pending_rentals as $rental): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($rental['book_name']); ?></strong><br>
                                    <small class="text-gray">Code: #<?php echo htmlspecialchars($rental['book_no']); ?></small>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($rental['user_name']); ?><br>
                                    <small class="text-gray"><?php echo htmlspecialchars($rental['user_email']); ?></small>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($rental['request_date'])); ?></td>
                                <td>
                                    <a href="?action=approved&id=<?php echo $rental['id']; ?>" class="btn btn-sm btn-register me-2">Approve</a>
                                    <a href="?action=rejected&id=<?php echo $rental['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Reject this request?')">Reject</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-admin">
            <h5 class="fw-bold mb-4"><i class="fas fa-history me-2 text-teal"></i> Rental History</h5>
            <div class="table-responsive">
                <table class="table table-dark-custom">
                    <thead>
                        <tr>
                            <th>Book</th>
                            <th>User</th>
                            <th>Status</th>
                            <th>Rental Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_rentals as $rental): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($rental['book_name']); ?></td>
                            <td><?php echo htmlspecialchars($rental['user_name']); ?></td>
                            <td>
                                <span class="badge <?php 
                                    echo $rental['status'] == 'approved' ? 'bg-success' : 
                                        ($rental['status'] == 'pending' ? 'bg-warning' : 
                                        ($rental['status'] == 'rejected' ? 'bg-danger' : 'bg-info')); 
                                ?>">
                                    <?php echo ucfirst($rental['status'] ?? ''); ?>
                                </span>
                            </td>
                            <td><?php echo $rental['rental_date'] ? date('M d, Y', strtotime($rental['rental_date'])) : '-'; ?></td>
                            <td>
                                <?php if ($rental['status'] == 'approved'): ?>
                                    <a href="?action=returned&id=<?php echo $rental['id']; ?>" class="btn btn-sm btn-outline-teal">Mark Returned</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
