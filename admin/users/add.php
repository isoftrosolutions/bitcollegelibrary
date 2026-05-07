<?php
$page = 'users';
$page_title = 'Add New User';
require_once '../includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password']; // Will hash this
    $role = sanitize_input($_POST['role']);
    $faculty = sanitize_input($_POST['faculty']);
    $year = sanitize_input($_POST['year']);
    $part = sanitize_input($_POST['part']);

    // Check if email already exists
    $check = $conn->query("SELECT id FROM users WHERE email = '$email'");
    if ($check->num_rows > 0) {
        $error = "Email address already registered.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, faculty, year, part) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $name, $email, $hashed_password, $role, $faculty, $year, $part);
        
        if ($stmt->execute()) {
            $success = "User created successfully!";
        } else {
            $error = "Failed to create user: " . $conn->error;
        }
    }
}
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card-admin">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0 fw-bold">Enter User Details</h5>
                <a href="index.php" class="btn btn-sm btn-outline-teal">Back to List</a>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label text-gray">Full Name</label>
                        <input type="text" name="name" class="form-control bg-dark border-0 text-white p-3" placeholder="Enter full name" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-gray">Email Address</label>
                        <input type="email" name="email" class="form-control bg-dark border-0 text-white p-3" placeholder="Email for login" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-gray">Password</label>
                        <input type="password" name="password" class="form-control bg-dark border-0 text-white p-3" placeholder="Set a password" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-gray">Role</label>
                        <select name="role" class="form-select bg-dark border-0 text-white p-3">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-gray">Faculty</label>
                        <select name="faculty" class="form-select bg-dark border-0 text-white p-3">
                            <option value="computer">Computer</option>
                            <option value="electrical">Electrical</option>
                            <option value="mechanical">Mechanical</option>
                            <option value="civil">Civil</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-gray">Year</label>
                        <select name="year" class="form-select bg-dark border-0 text-white p-3">
                            <option value="first">First</option>
                            <option value="second">Second</option>
                            <option value="third">Third</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-gray">Part</label>
                        <select name="part" class="form-select bg-dark border-0 text-white p-3">
                            <option value="first">First</option>
                            <option value="second">Second</option>
                        </select>
                    </div>
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-register py-3 px-5">
                            <i class="fas fa-user-plus me-2"></i> Create User
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
