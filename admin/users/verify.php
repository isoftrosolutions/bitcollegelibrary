<?php
$page = 'users';
$page_title = 'Verify User';
require_once '../includes/header.php';

if (!isset($_GET['id'])) {
    header("Location: " . BASE_URL . "/admin/dashboard.php");
    exit();
}

$user_id = (int)$_GET['id'];
$notif_id = isset($_GET['notif_id']) ? (int)$_GET['notif_id'] : null;

$user = get_user_info($user_id);

if (!$user) {
    header("Location: " . BASE_URL . "/admin/users/index.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $card_no = sanitize_input($_POST['card_no']);
    $crn = sanitize_input($_POST['crn']);
    $valid_upto = sanitize_input($_POST['valid_upto']);
    
    $stmt = $conn->prepare("UPDATE users SET is_verified = 1, card_no = ?, crn = ?, valid_upto = ? WHERE id = ?");
    $stmt->bind_param("sssi", $card_no, $crn, $valid_upto, $user_id);
    
    if ($stmt->execute()) {
        if ($notif_id) {
            mark_notification_read($notif_id);
        }
        $success = "User verified and Library Card info updated!";
    } else {
        $error = "Failed to verify user.";
    }
}
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card-admin">
            <h4 class="mb-4 fw-bold">Verify User: <span class="text-teal"><?php echo htmlspecialchars($user['name']); ?></span></h4>
            
            <?php if ($error): ?>
                <div class="alert alert-danger border-0 bg-danger-light text-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success border-0 bg-teal-light text-teal"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST">
                
                <!-- Student Details & Payment Status Check -->
                <div class="row mb-4">
                    <div class="col-12">
                         <!-- Payment Warning -->
                        <?php if ($user['payment_status'] !== 'approved'): ?>
                            <div class="alert alert-warning border-0 bg-warning-light text-warning mb-4">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Warning:</strong> This student's fee payment is currently <strong><?php echo $user['payment_status'] ?: 'Missing'; ?></strong>. 
                                It is recommended to <a href="../payments.php" class="text-teal fw-bold">verify payment</a> before issuing a library card.
                            </div>
                        <?php endif; ?>

                        <div class="bg-dark p-4 rounded border border-secondary">
                            <h6 class="text-teal text-uppercase fw-bold mb-3">Student Information</h6>
                            <div class="d-flex align-items-center mb-4">
                                <div class="me-3">
                                    <?php if ($user['profile_image']): ?>
                                        <img src="<?= BASE_URL ?>/assets/uploads/profile/<?php echo $user['profile_image']; ?>" alt="Avatar" style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%; border: 2px solid var(--teal-accent);">
                                    <?php else: ?>
                                        <div class="theme-toggle" style="width: 80px; height: 80px; font-size: 2rem; cursor: default;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <h4 class="mb-0 text-white"><?php echo htmlspecialchars($user['name'] ?: 'No Name'); ?></h4>
                                    <p class="mb-0 text-gray"><?php echo htmlspecialchars($user['email']); ?></p>
                                    <div class="mt-1 d-flex gap-2">
                                        <span class="badge bg-teal-light text-teal"><?php echo ucfirst($user['faculty']); ?> | <?php echo ucfirst($user['year']); ?> Year</span>
                                        <span class="badge bg-primary-light text-teal">@<?php echo htmlspecialchars($user['username']); ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="text-gray small text-uppercase">Contact</label>
                                    <div class="text-white"><?php echo htmlspecialchars($user['contact'] ?: 'N/A'); ?></div>
                                </div>
                                <div class="col-md-4">
                                    <label class="text-gray small text-uppercase">Parent Name</label>
                                    <div class="text-white"><?php echo htmlspecialchars($user['parent_name'] ?: 'N/A'); ?></div>
                                </div>
                                <div class="col-md-4">
                                    <label class="text-gray small text-uppercase">Parent Contact</label>
                                    <div class="text-white"><?php echo htmlspecialchars($user['parent_contact'] ?: 'N/A'); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Issuance Form -->
                <h6 class="text-teal text-uppercase fw-bold mb-3">Issue Library Card</h6>
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label text-gray">Library Card No.</label>
                        <input type="text" name="card_no" class="form-control bg-dark border-0 text-white p-3" placeholder="e.g. BIT-LIB-2024-001" value="<?php echo htmlspecialchars($user['card_no'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label text-gray">CRN No.</label>
                        <input type="text" name="crn" class="form-control bg-dark border-0 text-white p-3" placeholder="e.g. 2080-COM-12" value="<?php echo htmlspecialchars($user['crn'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="col-md-12">
                        <label class="form-label text-gray">Valid Upto</label>
                        <input type="text" name="valid_upto" class="form-control bg-dark border-0 text-white p-3" placeholder="e.g. 2088-05-30" value="<?php echo htmlspecialchars($user['valid_upto'] ?? '2088-01-01'); ?>" required>
                    </div>
                    
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-register py-3 px-5">
                            <i class="fas fa-check-circle me-2"></i> Verify & Issue Card
                        </button>
                        <a href="<?= BASE_URL ?>/admin/users/index.php" class="btn btn-outline-teal py-3 px-5 ms-2">Back</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
