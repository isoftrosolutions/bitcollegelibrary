<?php
$page = 'books';
$page_title = 'Edit Book';
require_once '../includes/header.php';

$error = '';
$success = '';

if (!isset($_GET['sn'])) {
    header("Location: index.php");
    exit();
}

$sn = sanitize_input($_GET['sn']);
$res = $conn->query("SELECT * FROM library_books WHERE sn = '$sn'");
$book = $res->fetch_assoc();

if (!$book) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_no = sanitize_input($_POST['book_no']);
    $book_name = sanitize_input($_POST['book_name']);
    $author = sanitize_input($_POST['author']);
    $remarks = sanitize_input($_POST['remarks']);
    
    $image_name = $book['image'];
    if (isset($_FILES['book_image']) && $_FILES['book_image']['error'] === 0) {
        $ext = pathinfo($_FILES['book_image']['name'], PATHINFO_EXTENSION);
        $image_name = 'book_' . time() . '.' . $ext;
        $target = '../../assets/uploads/books/' . $image_name;
        if (move_uploaded_file($_FILES['book_image']['tmp_name'], $target)) {
            // Delete old image if exists
            if ($book['image'] && file_exists('../../assets/uploads/books/' . $book['image'])) {
                unlink('../../assets/uploads/books/' . $book['image']);
            }
        } else {
            $error = "Failed to upload image.";
        }
    }

    if (!$error) {
        $stmt = $conn->prepare("UPDATE library_books SET book_no = ?, book_name = ?, author = ?, remarks = ?, image = ? WHERE sn = ?");
        $stmt->bind_param("ssssss", $book_no, $book_name, $author, $remarks, $image_name, $sn);
        
        if ($stmt->execute()) {
            $success = "Book updated successfully!";
            $book['book_no'] = $book_no;
            $book['book_name'] = $book_name;
            $book['author'] = $author;
            $book['remarks'] = $remarks;
            $book['image'] = $image_name;
        } else {
            $error = "Failed to update book: " . $conn->error;
        }
    }
}
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card-admin">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0 fw-bold">Update Book Details</h5>
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
                        <input type="text" name="book_no" class="form-control bg-dark border-0 text-white p-3" value="<?php echo htmlspecialchars($book['book_no']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-gray">Book Name</label>
                        <input type="text" name="book_name" class="form-control bg-dark border-0 text-white p-3" value="<?php echo htmlspecialchars($book['book_name']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-gray">Author</label>
                        <input type="text" name="author" class="form-control bg-dark border-0 text-white p-3" value="<?php echo htmlspecialchars($book['author']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-gray">Change Image (Optional)</label>
                        <input type="file" name="book_image" class="form-control bg-dark border-0 text-white p-3">
                    </div>
                    <?php if ($book['image']): ?>
                    <div class="col-12 mt-2">
                        <p class="text-gray mb-2">Current Image:</p>
                        <img src="<?= BASE_URL ?>/assets/uploads/books/<?php echo $book['image']; ?>" alt="Book" style="max-width: 150px; border-radius: 8px;">
                    </div>
                    <?php endif; ?>
                    <div class="col-12">
                        <label class="form-label text-gray">Remarks</label>
                        <textarea name="remarks" class="form-control bg-dark border-0 text-white p-3" rows="3"><?php echo htmlspecialchars($book['remarks']); ?></textarea>
                    </div>
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-register py-3 px-5">
                            <i class="fas fa-save me-2"></i> Update Book
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
