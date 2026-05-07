<?php
$page = 'books';
$page_title = 'Books Management';
require_once '../includes/header.php';

$search = isset($_GET['search']) ? sanitize_input($_GET['search']) : '';
$books = get_all_books($search);
?>

<div class="card-admin">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <form method="GET" class="d-flex" style="max-width: 400px;">
            <div class="input-group">
                <input type="text" name="search" class="form-control bg-dark border-0 text-white" placeholder="Search books..." value="<?php echo htmlspecialchars($search); ?>">
                <button class="btn btn-register" type="submit"><i class="fas fa-search"></i></button>
            </div>
        </form>
        <a href="add.php" class="btn btn-register">
            <i class="fas fa-plus me-2"></i> Add New Book
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-dark-custom">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Book No</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book): ?>
                <tr>
                    <td>
                        <?php if ($book['image']): ?>
                            <img src="/bit/assets/uploads/books/<?php echo $book['image']; ?>" alt="Book" style="width: 40px; height: 50px; object-fit: cover; border-radius: 4px;">
                        <?php else: ?>
                            <div class="bg-dark d-flex align-items-center justify-content-center" style="width: 40px; height: 50px; border-radius: 4px;">
                                <i class="fas fa-book text-gray small"></i>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td>#<?php echo htmlspecialchars($book['book_no']); ?></td>
                    <td><?php echo htmlspecialchars($book['book_name']); ?></td>
                    <td><?php echo htmlspecialchars($book['author']); ?></td>
                    <td>
                        <div class="btn-group">
                            <a href="edit.php?sn=<?php echo $book['sn']; ?>" class="btn btn-sm btn-outline-teal me-2"><i class="fas fa-edit"></i></a>
                            <a href="delete.php?sn=<?php echo $book['sn']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
