<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check if user is logged in
if (!is_logged_in()) {
    header("Location: login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']));
    exit();
}

$sn = isset($_GET['sn']) ? (int)$_GET['sn'] : 0;
$book = get_book_details($sn);

if (!$book) {
    header("Location: ../index.php");
    exit();
}

$user = get_user_info($_SESSION['user_id']);
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_sn = (int)$_POST['book_sn'];
    $user_id = $_SESSION['user_id'];
    
    // Process rental request
    $rental_id = request_rental($user_id, $book_sn);
    if ($rental_id) {
        $success = "Rental request submitted successfully! Admin will verify and notify you soon.";
    } else {
        $error = "Failed to submit rental request. Please try again.";
    }
}

$page_title = "Rent Book";
require_once '../includes/header.php';
?>

<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card book-card border-0 shadow-lg p-4 p-md-5">
                <div class="text-center mb-5">
                    <h1 class="hero-title">Book <span class="text-teal">Rental Form</span></h1>
                    <p class="text-gray">Please confirm your details for the rental request</p>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger border-0 bg-danger-light text-danger mb-4"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success border-0 bg-teal-light text-teal mb-4"><?php echo $success; ?></div>
                    <div class="text-center">
                        <a href="dashboard.php" class="btn btn-register px-5 py-3">Go to Dashboard</a>
                    </div>
                <?php else: ?>
                
                <form method="POST" action="">
                    <input type="hidden" name="book_sn" value="<?php echo $book['sn']; ?>">
                    
                    <h4 class="text-teal mb-4 border-bottom border-teal pb-2">Personal Information</h4>
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label text-gray">Full Name</label>
                            <input type="text" class="form-control bg-dark border-0 text-white p-3" value="<?php echo htmlspecialchars($user['name']); ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-gray">Email Address</label>
                            <input type="email" class="form-control bg-dark border-0 text-white p-3" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="form-label text-gray">Faculty</label>
                            <input type="text" class="form-control bg-dark border-0 text-white p-3" value="<?php echo htmlspecialchars($user['faculty']); ?>" readonly>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="form-label text-gray">Year</label>
                            <input type="text" class="form-control bg-dark border-0 text-white p-3" value="<?php echo htmlspecialchars($user['year']); ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-gray">Part</label>
                            <input type="text" class="form-control bg-dark border-0 text-white p-3" value="<?php echo htmlspecialchars($user['part']); ?>" readonly>
                        </div>
                    </div>

                    <h4 class="text-teal mb-4 mt-5 border-bottom border-teal pb-2">Book Details</h4>
                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-gray">Book Title</label>
                            <input type="text" class="form-control bg-dark border-0 text-white p-3" value="<?php echo htmlspecialchars($book['book_name']); ?>" readonly>
                        </div>
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label text-gray">Author</label>
                            <input type="text" class="form-control bg-dark border-0 text-white p-3" value="<?php echo htmlspecialchars($book['author']); ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-gray">Book Number / Code</label>
                            <input type="text" class="form-control bg-dark border-0 text-white p-3" value="<?php echo htmlspecialchars($book['book_no']); ?>" readonly>
                        </div>
                    </div>

                    <div class="alert bg-dark border-teal text-gray mt-5 mb-4">
                        <i class="fas fa-info-circle text-teal me-2"></i>
                        By clicking Submit, you agree to the library's rental policy and promise to return the book within the stipulated time.
                    </div>

                    <div class="d-grid gap-3 d-md-flex justify-content-md-end mt-4">
                        <a href="../index.php" class="btn btn-outline-teal px-5 py-3">Cancel</a>
                        <button type="submit" class="btn btn-register px-5 py-3">Submit Request</button>
                    </div>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
