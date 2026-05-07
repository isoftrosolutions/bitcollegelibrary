<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

$page_title = "Return Book";
require_once '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h1 class="hero-title" style="font-size: 3rem;">Return <span class="text-teal">Book</span></h1>
            <p class="hero-subtitle mx-auto">Please enter the book details to process your return. Note that late fees might apply.</p>
            
            <div class="card book-card border-0 shadow-sm p-4 text-start mt-5">
                <form action="#" method="POST">
                    <div class="mb-4">
                        <label class="form-label text-gray">Book Registration Number</label>
                        <input type="text" class="form-control form-control-lg" style="background: var(--bg-dark); border: 1px solid var(--border-color); color: var(--text-primary);" placeholder="e.g. BIT-BK-1234" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label text-gray">Return Reason (Optional)</label>
                        <select class="form-select form-select-lg" style="background: var(--bg-dark); border: 1px solid var(--border-color); color: var(--text-primary);">
                            <option value="finished">Finished Reading</option>
                            <option value="exam">Exams Over</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div class="info-box w-100 mb-4" style="margin-top: 0;">
                        <div class="info-icon"><i class="fas fa-info-circle"></i></div>
                        <div class="info-text">
                            <span class="label">Policy</span>
                            <span class="value">Books must be returned by 10 PM.</span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-register w-100 py-3 text-uppercase fw-bold">Process Return</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
