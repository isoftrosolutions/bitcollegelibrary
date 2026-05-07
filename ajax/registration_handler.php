<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

if ($action === 'send_otp') {
    $email = sanitize_input($_POST['email']);
    
    if (empty($email)) {
        echo json_encode(['status' => 'error', 'message' => 'Email is required.']);
        exit;
    }

    // Check if email already exists and is fully registered
    $stmt = $conn->prepare("SELECT id, otp_verified, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($user['password'] !== null) {
            echo json_encode(['status' => 'error', 'message' => 'Email already registered. Please login.']);
            exit;
        }
        $user_id = $user['id'];
    } else {
        $user_id = null;
    }

    $otp = rand(100000, 999999);
    
    if ($user_id) {
        $stmt = $conn->prepare("UPDATE users SET otp = ? WHERE id = ?");
        $stmt->bind_param("si", $otp, $user_id);
    } else {
        $stmt = $conn->prepare("INSERT INTO users (email, otp, role, is_verified) VALUES (?, ?, 'user', 0)");
        $stmt->bind_param("ss", $email, $otp);
    }
    
    if ($stmt->execute()) {
        $id = $user_id ?: $conn->insert_id;
        $_SESSION['reg_user_id'] = $id;
        
        $subject = "Your BIT Library Verification Code";
        $body = "
            <div style='font-family: Arial, sans-serif; padding: 20px; border: 1px solid #eee; border-radius: 10px;'>
                <h2 style='color: #1e40af;'>BIT Library Registration</h2>
                <p>Hello,</p>
                <p>Your verification code for the BIT Library registration is:</p>
                <div style='font-size: 24px; font-weight: bold; color: #3b82f6; padding: 10px; background: #f1f5f9; border-radius: 5px; display: inline-block;'>
                    $otp
                </div>
                <p>This code will expire in 10 minutes.</p>
                <p style='color: #64748b; font-size: 12px; margin-top: 20px;'>If you did not request this code, please ignore this email.</p>
            </div>
        ";
        
        $mail_sent = send_mail($email, $subject, $body);
        
        $response = ['status' => 'success', 'message' => 'OTP sent to your email.'];
        // Show OTP only if configured as demo or if mail failes but we want to allow testing
        if (MAIL_USERNAME === 'your-email@gmail.com' || !$mail_sent) {
            $response['otp'] = $otp;
            if (!$mail_sent) $response['message'] = "OTP generated (Email delivery failed - check SMTP config).";
        }
        
        echo json_encode($response);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to send OTP.']);
    }
}

elseif ($action === 'verify_otp') {
    $otp = sanitize_input($_POST['otp']);
    $user_id = $_SESSION['reg_user_id'] ?? null;
    
    if (!$user_id) {
        echo json_encode(['status' => 'error', 'message' => 'Session expired. Please start again.']);
        exit;
    }
    
    $stmt = $conn->prepare("SELECT otp FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($user && $user['otp'] === $otp) {
        $stmt = $conn->prepare("UPDATE users SET otp_verified = 1 WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        echo json_encode(['status' => 'success', 'message' => 'Email verified successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid OTP.']);
    }
}

elseif ($action === 'upload_fee') {
    $user_id = $_SESSION['reg_user_id'] ?? null;
    if (!$user_id) {
        echo json_encode(['status' => 'error', 'message' => 'Session expired.']);
        exit;
    }
    
    if (!isset($_FILES['fee_slip']) || $_FILES['fee_slip']['error'] !== 0) {
        echo json_encode(['status' => 'error', 'message' => 'Please upload a valid image.']);
        exit;
    }
    
    $allowed = ['jpg', 'jpeg', 'png'];
    $filename = $_FILES['fee_slip']['name'];
    $file_tmp = $_FILES['fee_slip']['tmp_name'];
    $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
    if (!in_array($file_ext, $allowed)) {
        echo json_encode(['status' => 'error', 'message' => 'Only JPG, JPEG, and PNG files are allowed.']);
        exit;
    }
    
    $new_filename = 'fee_' . $user_id . '_' . time() . '.' . $file_ext;
    $upload_path = '../assets/uploads/fees/' . $new_filename;
    
    if (move_uploaded_file($file_tmp, $upload_path)) {
        // Check if payment record exists
        $stmt = $conn->prepare("SELECT id FROM payments WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $stmt = $conn->prepare("UPDATE payments SET fee_slip_image = ?, status = 'pending' WHERE user_id = ?");
            $stmt->bind_param("si", $new_filename, $user_id);
        } else {
            $stmt = $conn->prepare("INSERT INTO payments (user_id, fee_slip_image, status) VALUES (?, ?, 'pending')");
            $stmt->bind_param("is", $user_id, $new_filename);
        }
        $stmt->execute();
        echo json_encode(['status' => 'success', 'message' => 'Fee slip uploaded successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to move uploaded file.']);
    }
}

elseif ($action === 'save_details') {
    $user_id = $_SESSION['reg_user_id'] ?? null;
    if (!$user_id) {
        echo json_encode(['status' => 'error', 'message' => 'Session expired.']);
        exit;
    }
    
    $name = sanitize_input($_POST['name']);
    $year = sanitize_input($_POST['year']);
    $faculty = sanitize_input($_POST['faculty']);
    $contact = sanitize_input($_POST['contact']);
    $parent_name = sanitize_input($_POST['parent_name']);
    $parent_contact = sanitize_input($_POST['parent_contact']);
    
    // Handle Profile Image if uploaded
    $profile_image = null;
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $filename = $_FILES['profile_image']['name'];
        $file_tmp = $_FILES['profile_image']['tmp_name'];
        $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (in_array($file_ext, $allowed)) {
            $profile_image = 'profile_' . $user_id . '_' . time() . '.' . $file_ext;
            move_uploaded_file($file_tmp, '../assets/uploads/profile/' . $profile_image);
        }
    }
    
    $stmt = $conn->prepare("SELECT id FROM student_details WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $sql = "UPDATE student_details SET name=?, year=?, faculty=?, contact=?, parent_name=?, parent_contact=?";
        if ($profile_image) $sql .= ", profile_image=?";
        $sql .= " WHERE user_id=?";
        
        $stmt = $conn->prepare($sql);
        if ($profile_image) {
            $stmt->bind_param("sssssssi", $name, $year, $faculty, $contact, $parent_name, $parent_contact, $profile_image, $user_id);
        } else {
            $stmt->bind_param("ssssssi", $name, $year, $faculty, $contact, $parent_name, $parent_contact, $user_id);
        }
    } else {
        $stmt = $conn->prepare("INSERT INTO student_details (user_id, name, year, faculty, contact, parent_name, parent_contact, profile_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssss", $user_id, $name, $year, $faculty, $contact, $parent_name, $parent_contact, $profile_image);
    }
    
    if ($stmt->execute()) {
        // Also update the legacy users table for compatibility
        $stmt = $conn->prepare("UPDATE users SET name = ?, faculty = ?, year = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $faculty, $year, $user_id);
        $stmt->execute();
        
        echo json_encode(['status' => 'success', 'message' => 'Details saved successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save details.']);
    }
}

elseif ($action === 'finish_registration') {
    $user_id = $_SESSION['reg_user_id'] ?? null;
    if (!$user_id) {
        echo json_encode(['status' => 'error', 'message' => 'Session expired.']);
        exit;
    }
    
    $username = sanitize_input($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($password !== $confirm_password) {
        echo json_encode(['status' => 'error', 'message' => 'Passwords do not match.']);
        exit;
    }
    
    if (strlen($password) < 6) {
        echo json_encode(['status' => 'error', 'message' => 'Password must be at least 6 characters.']);
        exit;
    }
    
    // Check if username exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
    $stmt->bind_param("si", $username, $user_id);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Username already taken.']);
        exit;
    }
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET username = ?, password = ? WHERE id = ?");
    $stmt->bind_param("ssi", $username, $hashed_password, $user_id);
    
    if ($stmt->execute()) {
        unset($_SESSION['reg_user_id']);
        echo json_encode(['status' => 'success', 'message' => 'Registration complete!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to complete registration.']);
    }
}
?>
