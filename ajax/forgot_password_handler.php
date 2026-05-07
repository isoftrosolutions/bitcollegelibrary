<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

if ($action === 'send_otp') {
    $email = trim($_POST['email'] ?? '');

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Please enter a valid email address.']);
        exit;
    }

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'No account found with that email address.']);
        exit;
    }

    $user = $result->fetch_assoc();

    if (empty($user['password'])) {
        echo json_encode(['status' => 'error', 'message' => 'This account has not been fully registered yet.']);
        exit;
    }

    $otp = rand(100000, 999999);

    $stmt = $conn->prepare("UPDATE users SET otp = ?, otp_verified = 0 WHERE id = ?");
    $stmt->bind_param("si", $otp, $user['id']);

    if (!$stmt->execute()) {
        echo json_encode(['status' => 'error', 'message' => 'Something went wrong. Please try again.']);
        exit;
    }

    $_SESSION['reset_user_id'] = $user['id'];
    $_SESSION['reset_email']   = $email;

    $subject = "BIT Library — Password Reset Code";
    $body = "
        <div style='font-family: Arial, sans-serif; padding: 20px; border: 1px solid #eee; border-radius: 10px; max-width: 480px;'>
            <h2 style='color: #0d9488;'>BIT Library</h2>
            <p>Hello,</p>
            <p>We received a request to reset your password. Use the code below:</p>
            <div style='font-size: 28px; font-weight: bold; color: #0d9488; padding: 14px 20px; background: #f1f5f9; border-radius: 8px; display: inline-block; letter-spacing: 8px;'>
                $otp
            </div>
            <p style='margin-top: 16px;'>This code expires in 10 minutes.</p>
            <p style='color: #64748b; font-size: 12px; margin-top: 20px;'>If you did not request a password reset, ignore this email — your password will not change.</p>
        </div>
    ";

    $mail_sent = send_mail($email, $subject, $body);

    $response = ['status' => 'success', 'message' => 'OTP sent to your email.'];
    if (MAIL_USERNAME === 'your-email@gmail.com' || !$mail_sent) {
        $response['otp'] = $otp;
        if (!$mail_sent) {
            $response['message'] = 'OTP generated (email delivery failed — check SMTP config).';
        }
    }

    echo json_encode($response);

} elseif ($action === 'verify_otp') {
    $otp     = trim($_POST['otp'] ?? '');
    $user_id = $_SESSION['reset_user_id'] ?? null;

    if (!$user_id) {
        echo json_encode(['status' => 'error', 'message' => 'Session expired. Please start again.']);
        exit;
    }

    if (empty($otp)) {
        echo json_encode(['status' => 'error', 'message' => 'Please enter the OTP.']);
        exit;
    }

    $stmt = $conn->prepare("SELECT otp FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && $user['otp'] === $otp) {
        $stmt = $conn->prepare("UPDATE users SET otp_verified = 1 WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $_SESSION['reset_otp_verified'] = true;
        echo json_encode(['status' => 'success', 'message' => 'OTP verified.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid OTP. Please try again.']);
    }

} elseif ($action === 'reset_password') {
    $user_id  = $_SESSION['reset_user_id']      ?? null;
    $verified = $_SESSION['reset_otp_verified'] ?? false;

    if (!$user_id || !$verified) {
        echo json_encode(['status' => 'error', 'message' => 'Session expired or OTP not verified.']);
        exit;
    }

    $password         = $_POST['password']         ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (strlen($password) < 6) {
        echo json_encode(['status' => 'error', 'message' => 'Password must be at least 6 characters.']);
        exit;
    }

    if ($password !== $confirm_password) {
        echo json_encode(['status' => 'error', 'message' => 'Passwords do not match.']);
        exit;
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE users SET password = ?, otp = NULL, otp_verified = 0 WHERE id = ?");
    $stmt->bind_param("si", $hashed, $user_id);

    if ($stmt->execute()) {
        unset($_SESSION['reset_user_id'], $_SESSION['reset_email'], $_SESSION['reset_otp_verified']);
        echo json_encode(['status' => 'success', 'message' => 'Password reset successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to reset password. Please try again.']);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
}
?>
