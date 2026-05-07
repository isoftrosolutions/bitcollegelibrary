<?php
$page = 'users';
$page_title = 'Library Card Preview';
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
?>

<style>
    .card-print-area {
        background: #fdfdfd;
        color: #333;
        width: 650px;
        height: 400px;
        padding: 30px;
        border: 1px solid #ddd;
        border-radius: 15px;
        position: relative;
        margin: 0 auto;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        font-family: 'Arial', sans-serif;
    }
    .card-header-bit {
        display: flex;
        align-items: center;
        border-bottom: 2px solid #b22222;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }
    .bit-logo-card {
        width: 60px;
        margin-right: 15px;
    }
    .bit-text-card h2 {
        font-size: 22px;
        margin: 0;
        color: #b22222;
        font-weight: 800;
        text-transform: uppercase;
    }
    .bit-text-card p {
        margin: 0;
        font-size: 11px;
        color: #666;
    }
    .card-title-badge {
        background: #b22222;
        color: white;
        padding: 3px 15px;
        font-size: 14px;
        font-weight: bold;
        position: absolute;
        top: 95px;
        left: 50%;
        transform: translateX(-50%);
        border-radius: 4px;
        letter-spacing: 1px;
    }
    .card-body-bit {
        display: flex;
        justify-content: space-between;
        margin-top: 40px;
    }
    .card-details {
        flex: 1;
        padding-right: 20px;
    }
    .detail-row {
        margin-bottom: 12px;
        display: flex;
        align-items: flex-end;
    }
    .detail-label {
        font-weight: bold;
        color: #000;
        width: 90px;
        font-size: 15px;
        white-space: nowrap;
    }
    .detail-value {
        border-bottom: 1px dotted #333;
        flex: 1;
        padding-left: 10px;
        font-size: 16px;
        color: #000;
        font-style: italic;
        font-family: 'Courier New', Courier, monospace;
    }
    .photo-area {
        width: 140px;
        height: 170px;
        border: 2px solid #ddd;
        background: #eee;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .photo-area img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .card-signatures {
        display: flex;
        justify-content: space-between;
        margin-top: 30px;
        align-items: flex-end;
    }
    .sig-block {
        text-align: center;
        width: 150px;
    }
    .sig-line {
        border-top: 1px solid #333;
        margin-top: 50px;
        padding-top: 5px;
        font-size: 12px;
        font-weight: bold;
    }
    .no-print {
        margin-top: 50px;
        text-align: center;
    }
    @media print {
        body * { display: none; }
        .card-print-area, .card-print-area * { display: block; }
        .card-print-area {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            box-shadow: none;
            border: 1px solid #eee;
        }
    }
</style>

<div class="container py-4">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h4 class="fw-bold"><i class="fas fa-id-card me-2"></i> Card Preview: <span class="text-teal"><?php echo htmlspecialchars($user['name']); ?></span></h4>
        <a href="index.php" class="btn btn-outline-teal"><i class="fas fa-arrow-left me-2"></i> Back to Users</a>
    </div>

    <!-- The Card -->
    <div class="card-print-area mb-5">
        <div class="card-header-bit">
            <img src="/bit/assets/images/logo.png" class="bit-logo-card" alt="BIT">
            <div class="bit-text-card">
                <h2>Birgunj Institute of Technology</h2>
                <p>(An Engineering College affiliated to CTEVT)</p>
                <p>P.O Box # 117, Birgunj-17, Nepal</p>
                <p>Tel: 051-528020, E-mail: bitorg@ntc.net.np</p>
            </div>
        </div>
        
        <div class="card-title-badge">LIBRARY CARD</div>
        
        <div class="card-body-bit">
            <div class="card-details">
                <div class="detail-row">
                    <span class="detail-label">Card No.</span>
                    <span class="detail-value"><?php echo htmlspecialchars($user['card_no']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Name</span>
                    <span class="detail-value"><?php echo htmlspecialchars($user['name']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Faculty</span>
                    <span class="detail-value"><?php echo strtoupper($user['faculty']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Year/Part</span>
                    <span class="detail-value"><?php echo strtoupper(($user['year'][0] ?? '-') . '/' . ($user['part'][0] ?? '-')); ?></span>
                    <span class="detail-label ms-3" style="width: 40px;">CRN</span>
                    <span class="detail-value"><?php echo htmlspecialchars($user['crn']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Valid upto</span>
                    <span class="detail-value"><?php echo htmlspecialchars($user['valid_upto']); ?></span>
                </div>
            </div>
            
            <div class="photo-area">
                <?php if ($user['image']): ?>
                    <img src="/bit/assets/uploads/users/<?php echo $user['image']; ?>" alt="Photo">
                <?php else: ?>
                    <i class="fas fa-user-circle fa-5x text-silver"></i>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card-signatures">
            <div class="sig-block">
                <span class="detail-value" style="display: block; min-height: 25px;"></span>
                <p class="sig-line">Card Holder Sign</p>
            </div>
            <div class="sig-block">
                <span class="detail-value" style="display: block; min-height: 25px; border-bottom: 0;">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/3/3a/Jon_Kirsch%27s_Signature.png" style="width: 100px; opacity: 0.7;" alt="Sig">
                </span>
                <p class="sig-line" style="margin-top: 25px;">Librarian</p>
            </div>
        </div>
    </div>

    <div class="no-print d-flex justify-content-center gap-3 mt-5">
        <button onclick="window.print()" class="btn btn-register py-3 px-5">
            <i class="fas fa-print me-2"></i> Print Library Card
        </button>
        <a href="edit.php?id=<?php echo $user['id']; ?>" class="btn btn-outline-teal py-3 px-5">
            <i class="fas fa-edit me-2"></i> Edit User Info
        </a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
