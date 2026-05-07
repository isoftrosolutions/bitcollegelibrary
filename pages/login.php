<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = null;

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login_id = sanitize_input($_POST['login_id']);
    $password = $_POST['password'];
    
    // Query the database for email or username
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
    $stmt->bind_param("ss", $login_id, $login_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password']) || $password === $user['password']) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_faculty'] = $user['faculty'];
            
            // Redirect logic
            if (isset($_GET['redirect']) && !empty($_GET['redirect'])) {
                $redirect = $_GET['redirect'];
            } else {
                // Default based on role
                $redirect = ($user['role'] === 'admin') ? '../admin/dashboard.php' : 'dashboard.php';
            }
            
            header("Location: " . $redirect);
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "User not found!";
    }
}

$page_title = "Login";
require_once '../includes/header.php';
?>

<div class="row justify-content-center pt-5">
    <div class="col-lg-5 col-md-8">
        <div class="card book-card border-0 shadow-lg p-4">
            <div class="text-center mb-4">
                <div class="mb-4">
                    <img src="<?= BASE_URL ?>/assets/images/logo.png" alt="BIT Logo" style="width: 80px;">
                </div>
                <h1 class="hero-title" style="font-size: 2.2rem;">Welcome <span class="text-teal">Back</span></h1>
                <p class="text-gray">Login to access your library account</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger border-0 bg-danger-light text-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if (isset($_GET['registered'])): ?>
                <div class="alert alert-success border-0 bg-teal-light text-teal">Registration successful! Please login.</div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="mb-4">
                    <label for="login_id" class="form-label text-gray">Email or Username</label>
                    <input type="text" class="form-control bg-dark border-0 text-white p-3" id="login_id" name="login_id" placeholder="Enter email or username" required>
                </div>
                
                <div class="mb-4">
                    <div class="d-flex justify-content-between">
                        <label for="password" class="form-label text-gray">Password</label>
                        <a href="forgot-password.php" class="text-teal small text-decoration-none">Forgot?</a>
                    </div>
                    <input type="password" class="form-control bg-dark border-0 text-white p-3" id="password" name="password" placeholder="••••••••" required>
                </div>
                
                <button type="submit" class="btn btn-register btn-lg w-100 py-3 mb-4">
                    Sign In <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </form>
            
            <p class="text-center text-gray mb-0">
                Don't have an account? 
                <a href="register.php" class="text-teal fw-bold text-decoration-none">Join now</a>
            </p>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>