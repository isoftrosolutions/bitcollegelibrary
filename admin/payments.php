<?php
$page = 'payments';
$page_title = 'Fee Payment Verification';
require_once 'includes/header.php'; // Corrected path for admin includes

// Handle actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $action = $_GET['action'];
    $status = ($action === 'approve') ? 'approved' : 'rejected';
    
    $stmt = $conn->prepare("UPDATE payments SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    if ($stmt->execute()) {
        // Create notification for user
        $pay_res = $conn->query("SELECT user_id FROM payments WHERE id = $id");
        $pay = $pay_res->fetch_assoc();
        create_notification($pay['user_id'], "Your library fee payment has been $status.");
        echo "<script>alert('Payment $status successfully!'); window.location.href='payments.php';</script>";
    }
}

$sql = "SELECT p.*, sd.name, u.email 
        FROM payments p 
        JOIN users u ON p.user_id = u.id 
        LEFT JOIN student_details sd ON u.id = sd.user_id 
        ORDER BY p.created_at DESC";
$res = $conn->query($sql);
?>

<div class="card-admin">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 fw-bold">Library Fee <span class="text-teal">Payments</span></h4>
    </div>

    <div class="table-responsive">
        <table class="table table-dark-custom align-middle">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Fee Slip</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($payment = $res->fetch_assoc()): ?>
                <tr>
                    <td>
                        <div class="fw-bold text-white"><?php echo htmlspecialchars($payment['name'] ?: 'N/A'); ?></div>
                        <div class="small text-gray"><?php echo htmlspecialchars($payment['email']); ?></div>
                    </td>
                    <td>
                        <a href="<?= BASE_URL ?>/assets/uploads/fees/<?php echo $payment['fee_slip_image']; ?>" target="_blank">
                            <img src="<?= BASE_URL ?>/assets/uploads/fees/<?php echo $payment['fee_slip_image']; ?>" alt="Fee Slip" class="rounded" style="width: 100px; height: 60px; object-fit: cover; border: 1px solid var(--border-color);">
                        </a>
                    </td>
                    <td>
                        <?php if ($payment['status'] === 'approved'): ?>
                            <span class="badge bg-success-light text-success">Approved</span>
                        <?php elseif ($payment['status'] === 'rejected'): ?>
                            <span class="badge bg-danger-light text-danger">Rejected</span>
                        <?php else: ?>
                            <span class="badge bg-warning-light text-warning">Pending</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="small text-gray"><?php echo date('M d, Y', strtotime($payment['created_at'])); ?></div>
                        <div class="small text-muted"><?php echo date('h:i A', strtotime($payment['created_at'])); ?></div>
                    </td>
                    <td>
                        <?php if ($payment['status'] === 'pending'): ?>
                            <a href="?action=approve&id=<?php echo $payment['id']; ?>" class="btn btn-sm btn-success me-2" onclick="return confirm('Approve this payment?')">
                                <i class="fas fa-check"></i> Approve
                            </a>
                            <a href="?action=reject&id=<?php echo $payment['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Reject this payment?')">
                                <i class="fas fa-times"></i> Reject
                            </a>
                        <?php else: ?>
                            <span class="text-muted small">Processed</span>
                        <?php endif; ?>
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
    .bg-danger-light { background: rgba(220, 53, 69, 0.1); }
</style>

<?php require_once '../includes/footer.php'; ?>
