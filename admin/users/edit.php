<?php
$page = 'users';
$page_title = 'Edit User';
require_once '../includes/header.php';

$error = '';
$success = '';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = (int)$_GET['id'];
$res = $conn->query("SELECT * FROM users WHERE id = $id");
$user = $res->fetch_assoc();

if (!$user) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $role = sanitize_input($_POST['role']);
    $faculty = sanitize_input($_POST['faculty']);
    $year = sanitize_input($_POST['year']);
    $part = sanitize_input($_POST['part']);

    // Handle password update separately
    $password_sql = "";
    if (!empty($_POST['password'])) {
        $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $password_sql = ", password = '$hashed_password'";
    }

    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, role = ?, faculty = ?, year = ?, part = ? $password_sql WHERE id = ?");
    $stmt->bind_param("ssssssi", $name, $email, $role, $faculty, $year, $part, $id);
    
    if ($stmt->execute()) {
        $success = "User updated successfully!";
        $user['name'] = $name;
        $user['email'] = $email;
        $user['role'] = $role;
        $user['faculty'] = $faculty;
        $user['year'] = $year;
        $user['part'] = $part;
    } else {
        $error = "Failed to update user: " . $conn->error;
    }
}
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card-admin">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0 fw-bold">Update User Information</h5>
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
                        <input type="text" name="name" class="form-control bg-dark border-0 text-white p-3" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-gray">Email Address</label>
                        <input type="email" name="email" class="form-control bg-dark border-0 text-white p-3" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-gray">Role</label>
                        <select name="role" class="form-select bg-dark border-0 text-white p-3">
                            <option value="user" <?php echo $user['role'] == 'user' ? 'selected' : ''; ?>>User</option>
                            <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-gray">New Password (leave blank to keep current)</label>
                        <input type="password" name="password" class="form-control bg-dark border-0 text-white p-3">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-gray">Faculty</label>
                        <select name="faculty" class="form-select bg-dark border-0 text-white p-3">
                            <option value="computer" <?php echo $user['faculty'] == 'computer' ? 'selected' : ''; ?>>Computer</option>
                            <option value="electrical" <?php echo $user['faculty'] == 'electrical' ? 'selected' : ''; ?>>Electrical</option>
                            <option value="mechanical" <?php echo $user['faculty'] == 'mechanical' ? 'selected' : ''; ?>>Mechanical</option>
                            <option value="civil" <?php echo $user['faculty'] == 'civil' ? 'selected' : ''; ?>>Civil</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-gray">Year</label>
                        <select name="year" class="form-select bg-dark border-0 text-white p-3">
                            <option value="first" <?php echo $user['year'] == 'first' ? 'selected' : ''; ?>>First</option>
                            <option value="second" <?php echo $user['year'] == 'second' ? 'selected' : ''; ?>>Second</option>
                            <option value="third" <?php echo $user['year'] == 'third' ? 'selected' : ''; ?>>Third</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-gray">Part</label>
                        <select name="part" class="form-select bg-dark border-0 text-white p-3">
                            <option value="first" <?php echo $user['part'] == 'first' ? 'selected' : ''; ?>>First</option>
                            <option value="second" <?php echo $user['part'] == 'second' ? 'selected' : ''; ?>>Second</option>
                        </select>
                    </div>
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-register py-3 px-5">
                            <i class="fas fa-save me-2"></i> Update User
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
