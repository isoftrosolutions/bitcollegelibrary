<?php
require_once dirname(__DIR__) . '/config/database.php';

// Function to sanitize input
function sanitize_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $conn->real_escape_string($data);
}

// Function to check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Function to check if user is admin
function is_admin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

// Function to get user info with details
function get_user_info($user_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT u.*, sd.name, sd.profile_image, sd.year, sd.faculty, sd.contact, sd.parent_name, sd.parent_contact, p.status as payment_status, p.fee_slip_image
                           FROM users u 
                           LEFT JOIN student_details sd ON u.id = sd.user_id 
                           LEFT JOIN payments p ON u.id = p.user_id 
                           WHERE u.id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Function to get all books with improved filtering
function get_all_books($search = '', $department = '', $limit = null) {
    global $conn;
    
    $sql = "SELECT * FROM library_books WHERE 1=1";
    $params = [];
    $types = "";
    
    if (!empty($search)) {
        $sql .= " AND (`book_name` LIKE ? OR `author` LIKE ? OR `book_no` LIKE ? OR `remarks` LIKE ?)";
        $search_param = "%$search%";
        $params[] = $search_param;
        $params[] = $search_param;
        $params[] = $search_param;
        $params[] = $search_param;
        $types .= "ssss";
    }
    
    if (!empty($department)) {
        // Broaden department search with multiple keywords if needed
        $sql .= " AND (`book_name` LIKE ? OR `remarks` LIKE ?)";
        $dept_param = "%$department%";
        $params[] = $dept_param;
        $params[] = $dept_param;
        $types .= "ss";
    }
    
    $sql .= " ORDER BY sn ASC"; // Changed to SN for consistent ordering after cleanup
    
    if ($limit !== null) {
        $sql .= " LIMIT ?";
        $params[] = $limit;
        $types .= "i";
    }
    
    if (!empty($params)) {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $result = $conn->query($sql);
    }
    
    $books = [];
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
    
    return $books;
}

// Function to get book details
function get_book_details($sn) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM library_books WHERE sn = ?");
    $stmt->bind_param("i", $sn);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Function to get book count
function get_book_count() {
    global $conn;
    $result = $conn->query("SELECT COUNT(*) as count FROM library_books");
    $row = $result->fetch_assoc();
    return $row['count'];
}

// Function to get user count
function get_user_count() {
    global $conn;
    $result = $conn->query("SELECT COUNT(*) as count FROM users");
    $row = $result->fetch_assoc();
    return $row['count'];
}

/* --- Rental Functions --- */

// Function to request a rental
function request_rental($user_id, $book_sn) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO rentals (user_id, book_sn, status) VALUES (?, ?, 'pending')");
    $stmt->bind_param("ii", $user_id, $book_sn);
    if ($stmt->execute()) {
        $rental_id = $conn->insert_id;
        // Create notification for admin
        $user = get_user_info($user_id);
        $book = get_book_details($book_sn);
        $message = "New rental request from " . $user['name'] . " for book: " . $book['book_name'];
        // Find admin user ID (first admin)
        $admin_res = $conn->query("SELECT id FROM users WHERE role = 'admin' LIMIT 1");
        if ($admin_res->num_rows > 0) {
            $admin = $admin_res->fetch_assoc();
            create_notification($admin['id'], $message);
        }
        return $rental_id;
    }
    return false;
}

// Function to get all rentals for admin
function get_all_rentals($status = null) {
    global $conn;
    $sql = "SELECT r.*, u.name as user_name, u.email as user_email, b.book_name, b.book_no 
            FROM rentals r 
            JOIN users u ON r.user_id = u.id 
            JOIN library_books b ON r.book_sn = b.sn";
    if ($status) {
        $sql .= " WHERE r.status = '$status'";
    }
    $sql .= " ORDER BY r.request_date DESC";
    $result = $conn->query($sql);
    $rentals = [];
    while ($row = $result->fetch_assoc()) {
        $rentals[] = $row;
    }
    return $rentals;
}

// Function to get user's rentals
function get_user_rentals($user_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT r.*, b.book_name, b.book_no, b.author 
                            FROM rentals r 
                            JOIN library_books b ON r.book_sn = b.sn 
                            WHERE r.user_id = ? 
                            ORDER BY r.request_date DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $rentals = [];
    while ($row = $result->fetch_assoc()) {
        $rentals[] = $row;
    }
    return $rentals;
}

// Function to update rental status
function update_rental_status($rental_id, $status, $remarks = '') {
    global $conn;
    $rental_date = ($status == 'approved') ? date('Y-m-d') : null;
    $return_date = ($status == 'returned') ? date('Y-m-d') : null;
    $sql = "UPDATE rentals SET status = ?, admin_remarks = ?";
    if ($rental_date) {
        $sql .= ", rental_date = '$rental_date'";
    }
    if ($return_date) {
        $sql .= ", return_date = '$return_date'";
    }
    $sql .= " WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $status, $remarks, $rental_id);
    if ($stmt->execute()) {
        // Notify user
        $rental_res = $conn->query("SELECT user_id, book_sn FROM rentals WHERE id = $rental_id");
        $rental = $rental_res->fetch_assoc();
        $book = get_book_details($rental['book_sn']);
        $message = "Your rental request for '" . $book['book_name'] . "' has been $status.";
        create_notification($rental['user_id'], $message);
        return true;
    }
    return false;
}

/* --- Notification Functions --- */

function create_notification($user_id, $message) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $message);
    return $stmt->execute();
}

function get_user_notifications($user_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function get_admin_notifications() {
    global $conn;
    $res = $conn->query("SELECT n.*, u.name as user_name FROM notifications n JOIN users u ON n.user_id = u.id WHERE n.is_read = 0 ORDER BY n.created_at DESC");
    $notifs = [];
    while ($row = $res->fetch_assoc()) {
        $notifs[] = $row;
    }
    return $notifs;
}

function mark_notification_read($id) {
    global $conn;
    $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

// Consolidated search function for admin
function search_everything($search) {
    global $conn;
    $results = [
        'users' => [],
        'books' => [],
        'rentals' => []
    ];
    
    $search = "%$search%";
    
    // Search users
    $stmt = $conn->prepare("SELECT id, name, email, role, faculty FROM users WHERE name LIKE ? OR email LIKE ? OR faculty LIKE ?");
    $stmt->bind_param("sss", $search, $search, $search);
    $stmt->execute();
    $results['users'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    // Search books
    $stmt = $conn->prepare("SELECT sn, book_no, book_name, author, image FROM library_books WHERE book_name LIKE ? OR author LIKE ? OR book_no LIKE ? OR remarks LIKE ?");
    $stmt->bind_param("ssss", $search, $search, $search, $search);
    $stmt->execute();
    $results['books'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    // Search rentals
    $stmt = $conn->prepare("SELECT r.id, r.status, u.name as user_name, u.email as user_email, b.book_name, b.book_no 
                           FROM rentals r 
                           JOIN users u ON r.user_id = u.id 
                           JOIN library_books b ON r.book_sn = b.sn 
                           WHERE u.name LIKE ? OR u.email LIKE ? OR b.book_name LIKE ? OR b.book_no LIKE ? OR r.status LIKE ?");
    $stmt->bind_param("sssss", $search, $search, $search, $search, $search);
    $stmt->execute();
    $results['rentals'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    return $results;
}

/* --- Mail Functions --- */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/PHPMailer/Exception.php';
require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/SMTP.php';
require_once dirname(__DIR__) . '/config/mail.php';

function send_mail($to, $subject, $body) {
    if (MAIL_USERNAME === 'your-email@gmail.com') {
        return true; 
    }

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = MAIL_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_USERNAME;
        $mail->Password   = MAIL_PASSWORD;
        $mail->SMTPSecure = MAIL_ENCRYPTION;
        $mail->Port       = MAIL_PORT;
        $mail->setFrom(MAIL_FROM_EMAIL, MAIL_FROM_NAME);
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
?>
