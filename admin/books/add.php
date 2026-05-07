<?php
$page = 'books';
$page_title = 'Add New Book';
require_once '../includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_no = sanitize_input($_POST['book_no']);
    $book_name = sanitize_input($_POST['book_name']);
    $author = sanitize_input($_POST['author']);
    $remarks = sanitize_input($_POST['remarks']);
    
    // Image Upload
    $image_name = null;
    if (isset($_FILES['book_image']) && $_FILES['book_image']['error'] === 0) {
        $ext = pathinfo($_FILES['book_image']['name'], PATHINFO_EXTENSION);
        $image_name = 'book_' . time() . '.' . $ext;
        $target = '../../assets/uploads/books/' . $image_name;
        if (!move_uploaded_file($_FILES['book_image']['tmp_name'], $target)) {
            $error = "Failed to upload image.";
        }
    }

    if (!$error) {
        $stmt = $conn->prepare("INSERT INTO library_books (book_no, book_name, author, remarks, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $book_no, $book_name, $author, $remarks, $image_name);
        
        if ($stmt->execute()) {
            $success = "Book added successfully!";
        } else {
            $error = "Failed to add book: " . $conn->error;
        }
    }
}
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card-admin">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0 fw-bold">Enter Book Details</h5>
                <a href="index.php" class="btn btn-sm btn-outline-teal">Back to List</a>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label text-gray">Book Number</label>
                        <input type="text" name="book_no" class="form-control bg-dark border-0 text-white p-3" placeholder="e.g. 1024" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-gray">Book Name</label>
                        <input type="text" name="book_name" class="form-control bg-dark border-0 text-white p-3" placeholder="Enter book title" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-gray">Author</label>
                        <input type="text" name="author" class="form-control bg-dark border-0 text-white p-3" placeholder="Enter author name" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-gray">Book Cover Image</label>
                        <input type="file" name="book_image" class="form-control bg-dark border-0 text-white p-3">
                    </div>
                    <div class="col-12">
                        <label class="form-label text-gray">Remarks</label>
                        <textarea name="remarks" class="form-control bg-dark border-0 text-white p-3" rows="3" placeholder="Any additional notes..."></textarea>
                    </div>
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-register py-3 px-5">
                            <i class="fas fa-save me-2"></i> Save Book
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
