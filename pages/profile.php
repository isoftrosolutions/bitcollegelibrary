<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user = get_user_info($_SESSION['user_id']);
$page_title = "My Profile";
require_once '../includes/header.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $user_id = $_SESSION['user_id'];
    
    // Handle File Upload
    $image_name = $user['image']; // Default to existing image
    
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $filename = $_FILES['profile_image']['name'];
        $file_tmp = $_FILES['profile_image']['tmp_name'];
        $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($file_ext, $allowed)) {
            $new_filename = 'user_' . $user_id . '_' . time() . '.' . $file_ext;
            $upload_path = '../assets/uploads/users/' . $new_filename;
            
            if (move_uploaded_file($file_tmp, $upload_path)) {
                // Delete old image if it exists and is not a default/placeholder
                if ($user['image'] && file_exists('../assets/uploads/users/' . $user['image'])) {
                    unlink('../assets/uploads/users/' . $user['image']);
                }
                $image_name = $new_filename;
            } else {
                $error = "Failed to upload image.";
            }
        } else {
            $error = "Invalid file type. Allowed: JPG, JPEG, PNG, WEBP.";
        }
    }
    
    if (empty($error)) {
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, image = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $email, $image_name, $user_id);
        
        if ($stmt->execute()) {
            $success = "Profile updated successfully!";
            $_SESSION['user_name'] = $name;
            $user = get_user_info($_SESSION['user_id']);
        } else {
            $error = "Failed to update profile.";
        }
    }
}
?>

<style>
    .profile-avatar-container {
        position: relative;
        width: 150px;
        height: 150px;
        margin: 0 auto 2rem;
    }
    .profile-avatar {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid var(--teal-accent);
        background: #2a2a2a;
        box-shadow: 0 10px 25px rgba(0,0,0,0.3);
    }
    .avatar-upload-btn {
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--teal-accent);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: 3px solid #1a1a1a;
        transition: all 0.3s ease;
    }
    .avatar-upload-btn:hover {
        transform: scale(1.1);
        background: #00d2d2;
    }
    #profile_image {
        display: none;
    }
    .profile-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
    }
    .form-control:disabled {
        background-color: rgba(255, 255, 255, 0.02) !important;
        color: #888 !important;
        opacity: 0.7;
    }
</style>

<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card profile-card p-4 p-md-5">
                <div class="text-center mb-5">
                    <h1 class="hero-title mb-4" style="font-size: 2.5rem;">User <span class="text-teal">Profile</span></h1>
                    
                    <form method="POST" action="" enctype="multipart/form-data" id="profileForm">
                        <div class="profile-avatar-container">
                            <?php if ($user['image']): ?>
                                <img src="/bit/assets/uploads/users/<?php echo $user['image']; ?>" alt="Profile" class="profile-avatar" id="avatarPreview">
                            <?php else: ?>
                                <div class="profile-avatar d-flex align-items-center justify-content-center" id="avatarPreview">
                                    <i class="fas fa-user fa-4x text-gray"></i>
                                </div>
                            <?php endif; ?>
                            <label for="profile_image" class="avatar-upload-btn">
                                <i class="fas fa-camera"></i>
                            </label>
                            <input type="file" name="profile_image" id="profile_image" accept="image/*" onchange="previewImage(this)">
                        </div>

                        <?php if ($success): ?>
                            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                                <i class="fas fa-check-circle me-2"></i> <?php echo $success; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <div class="row g-4 text-start">
                            <div class="col-md-6">
                                <label class="form-label text-gray small text-uppercase fw-bold">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark border-0 text-gray"><i class="fas fa-user"></i></span>
                                    <input type="text" name="name" class="form-control bg-dark border-0 text-white p-3" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-gray small text-uppercase fw-bold">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark border-0 text-gray"><i class="fas fa-envelope"></i></span>
                                    <input type="email" name="email" class="form-control bg-dark border-0 text-white p-3" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-gray small text-uppercase fw-bold">Faculty</label>
                                <input type="text" class="form-control bg-dark border-0 text-white p-3" value="<?php echo htmlspecialchars($user['faculty']); ?>" disabled>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-gray small text-uppercase fw-bold">Year</label>
                                <input type="text" class="form-control bg-dark border-0 text-white p-3" value="<?php echo htmlspecialchars($user['year']); ?>" disabled>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-gray small text-uppercase fw-bold">Part</label>
                                <input type="text" class="form-control bg-dark border-0 text-white p-3" value="<?php echo htmlspecialchars($user['part']); ?>" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-gray small text-uppercase fw-bold">CRN</label>
                                <input type="text" class="form-control bg-dark border-0 text-white p-3" value="<?php echo htmlspecialchars($user['crn']); ?>" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-gray small text-uppercase fw-bold">Card Number</label>
                                <input type="text" class="form-control bg-dark border-0 text-white p-3" value="<?php echo htmlspecialchars($user['card_no']); ?>" disabled>
                            </div>
                            
                            <div class="col-12 mt-5 text-center">
                                <button type="submit" class="btn btn-register py-3 px-5 w-100 w-md-auto">
                                    <i class="fas fa-save me-2"></i> Update Profile
                                </button>
                                <div class="mt-4">
                                    <a href="library-card.php" class="text-teal text-decoration-none">
                                        <i class="fas fa-id-card me-1"></i> View My Library Card
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var preview = document.getElementById('avatarPreview');
            if (preview.tagName === 'IMG') {
                preview.src = e.target.result;
            } else {
                preview.innerHTML = '<img src="' + e.target.result + '" class="profile-avatar">';
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php require_once '../includes/footer.php'; ?>
