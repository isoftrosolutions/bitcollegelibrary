<?php
require_once '../config/database.php';

if (isset($_GET['id'])) {
    $book_id = (int)$_GET['id'];
    
    $stmt = $conn->prepare("SELECT * FROM library_books WHERE `sn` = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
        ?>
        <div class="row">
            <div class="col-md-4">
                <div class="text-center mb-3">
                    <?php if ($book['image']): ?>
                        <img src="<?= BASE_URL ?>/assets/uploads/books/<?php echo $book['image']; ?>" alt="Book Cover" class="img-fluid rounded shadow" style="max-height: 250px;">
                    <?php else: ?>
                        <i class="fas fa-book fa-5x text-teal"></i>
                    <?php endif; ?>
                </div>
                <div class="d-grid gap-2">
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <button class="btn btn-register"><i class="fas fa-bookmark"></i> Borrow Book</button>
                    <?php else: ?>
                    <a href="<?= BASE_URL ?>/pages/login.php" class="btn btn-secondary">Login to Borrow</a>
                    <?php endif; ?>
                    <button class="btn btn-outline-teal"><i class="fas fa-heart"></i> Add to Wishlist</button>
                </div>
            </div>
            <div class="col-md-8">
                <h4 class="text-teal"><?php echo htmlspecialchars($book["book_name"]); ?></h4>
                <hr class="border-secondary">
                
                <div class="row mb-3">
                    <div class="col-6">
                        <strong class="text-gray">Book Number:</strong><br>
                        <span class="badge bg-teal-light text-teal"><?php echo htmlspecialchars($book["book_no"]); ?></span>
                    </div>
                    <div class="col-6">
                        <strong class="text-gray">Serial Number:</strong><br>
                        <?php echo htmlspecialchars($book['sn']); ?>
                    </div>
                </div>
                
                <div class="mb-3">
                    <strong class="text-gray">Author:</strong><br>
                    <?php echo htmlspecialchars($book['author']); ?>
                </div>
                
                <div class="mb-3">
                    <strong class="text-gray">Department:</strong><br>
                    <span class="badge bg-secondary">
                        <?php
                        $book_name = strtolower($book["book_name"]);
                        if (strpos($book_name, 'computer') !== false) echo 'Computer Engineering';
                        elseif (strpos($book_name, 'electronic') !== false) echo 'Electronics Engineering';
                        elseif (strpos($book_name, 'electrical') !== false) echo 'Electrical Engineering';
                        elseif (strpos($book_name, 'civil') !== false) echo 'Civil Engineering';
                        elseif (strpos($book_name, 'mechanical') !== false) echo 'Mechanical Engineering';
                        else echo 'General';
                        ?>
                    </span>
                </div>
                
                <div class="mb-3">
                    <strong class="text-gray">Availability:</strong><br>
                    <span class="badge bg-success">Available</span>
                </div>
                
                <div class="mb-3">
                    <strong class="text-gray">Remarks:</strong><br>
                    <?php echo !empty($book['remarks']) ? htmlspecialchars($book['remarks']) : 'No remarks'; ?>
                </div>
            </div>
        </div>
        <?php
    } else {
        echo '<div class="alert alert-danger">Book not found!</div>';
    }
} else {
    echo '<div class="alert alert-danger">Invalid request!</div>';
}
?>