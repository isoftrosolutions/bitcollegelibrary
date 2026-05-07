<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

$page_title = "Latest Arrivals";
require_once '../includes/header.php';
?>

<div class="container py-5 mt-5">
    <div class="text-center mb-5">
        <h1 class="hero-title" style="font-size: 3rem;">Latest <span class="text-teal">Arrivals</span></h1>
        <p class="hero-subtitle mx-auto">Discover the newest additions to our collection. We update our library weekly.</p>
    </div>

    <div class="row g-4">
        <?php
        $latest_books = get_all_books('', '', 12);
        foreach ($latest_books as $book):
        ?>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="book-card">
                <div class="book-img-placeholder">
                    <?php if ($book['image']): ?>
                        <img src="/bit/assets/uploads/books/<?php echo $book['image']; ?>" alt="Book Cover" class="img-fluid rounded" style="height: 100%; width: 100%; object-fit: cover;">
                    <?php else: ?>
                        <i class="fas fa-book-reader"></i>
                    <?php endif; ?>
                </div>
                <h3 class="book-title"><?php echo htmlspecialchars(substr($book["book_name"], 0, 50)); ?></h3>
                <p class="book-author">By <?php echo htmlspecialchars($book['author']); ?></p>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="badge bg-teal-light text-teal">New</span>
                    <a href="/bit/pages/books.php?sn=<?php echo $book['sn']; ?>" class="btn btn-sm btn-register">Details</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
