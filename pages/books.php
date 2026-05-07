<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

$page_title = "Books Catalog";
require_once '../includes/header.php';


// Get search parameters
$search = isset($_GET['search']) ? sanitize_input($_GET['search']) : '';
$department = isset($_GET['dept']) ? sanitize_input($_GET['dept']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

// Get books with pagination
$books = get_all_books($search, $department);
$total_books = count($books);
$total_pages = ceil($total_books / $limit);
$current_page_books = array_slice($books, $offset, $limit);
?>

</section>

<div class="container py-5 mt-5">
    <div class="row mb-5 text-center">
        <div class="col-md-12">
            <h1 class="hero-title">Library <span class="text-teal">Catalog</span></h1>
            <p class="hero-subtitle mx-auto">Browse through our extensive collection of <?php echo $total_books; ?> resources.</p>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-md-12">
            <div class="card book-card border-0 shadow-sm p-4">
                <form method="GET" action="" class="row g-3">
                    <div class="col-md-7">
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-dark border-0 text-gray"><i class="fas fa-search"></i></span>
                            <input type="text" name="search" class="form-control bg-dark border-0 text-white" 
                                   placeholder="Search by title, author, or code..." 
                                   value="<?php echo htmlspecialchars($search); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="dept" class="form-select form-select-lg bg-dark border-0 text-white">
                            <option value="">All Categories</option>
                            <option value="computer" <?php echo $department === 'computer' ? 'selected' : ''; ?>>Computer</option>
                            <option value="electronics" <?php echo $department === 'electronics' ? 'selected' : ''; ?>>Electronics</option>
                            <option value="electrical" <?php echo $department === 'electrical' ? 'selected' : ''; ?>>Electrical</option>
                            <option value="civil" <?php echo $department === 'civil' ? 'selected' : ''; ?>>Civil</option>
                            <option value="mechanical" <?php echo $department === 'mechanical' ? 'selected' : ''; ?>>Mechanical</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-register btn-lg w-100">Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <?php if ($total_books > 0): ?>
            <?php foreach ($current_page_books as $book): ?>
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="book-card">
                    <div class="book-img-placeholder">
                        <?php if ($book['image']): ?>
                            <img src="/bit/assets/uploads/books/<?php echo $book['image']; ?>" alt="Book Cover" class="img-fluid rounded" style="height: 100%; width: 100%; object-fit: cover;">
                        <?php else: ?>
                            <i class="fas fa-book"></i>
                        <?php endif; ?>
                    </div>
                    <h3 class="book-title"><?php echo htmlspecialchars(substr($book["book_name"], 0, 50)); ?></h3>
                    <p class="book-author">By <?php echo htmlspecialchars($book['author']); ?></p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-teal-light text-teal">#<?php echo htmlspecialchars($book["book_no"]); ?></span>
                        <button class="btn btn-sm btn-register view-book" 
                                data-id="<?php echo $book['sn']; ?>"
                                data-bs-toggle="modal" data-bs-target="#bookModal">View</button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            
            <div class="col-12 mt-5">
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-lg justify-content-center">
                        <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                            <a class="page-link bg-dark border-0 text-white" href="?page=<?php echo $page - 1; ?>&search=<?php echo $search; ?>&dept=<?php echo $department; ?>">Previous</a>
                        </li>
                        <?php 
                        $start = max(1, $page - 2);
                        $end = min($total_pages, $page + 2);
                        for ($i = $start; $i <= $end; $i++): 
                        ?>
                        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                            <a class="page-link <?php echo $i == $page ? 'btn-register border-0' : 'bg-dark border-0 text-white'; ?>" href="?page=<?php echo $i; ?>&search=<?php echo $search; ?>&dept=<?php echo $department; ?>"><?php echo $i; ?></a>
                        </li>
                        <?php endfor; ?>
                        <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                            <a class="page-link bg-dark border-0 text-white" href="?page=<?php echo $page + 1; ?>&search=<?php echo $search; ?>&dept=<?php echo $department; ?>">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <i class="fas fa-search fa-4x text-gray mb-4"></i>
                <h2 class="hero-title" style="font-size: 2rem;">No results found</h2>
                <p class="hero-subtitle mx-auto">We couldn't find any books matching your search.</p>
                <a href="books.php" class="btn btn-register">Refresh Catalog</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Book Details Modal -->
<div class="modal fade" id="bookModal" tabindex="-1">
    <div class="modal-dialog bg-warning  modal-dialog-centered modal-lg" >
        <div class="modal-content   border-teal" style="color:#000000">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="modal-title hero-title" style="font-size: 1.5rem;">Book <span class="text-teal">Information</span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="bookDetails">
                <div class="text-center py-5">
                    <div class="spinner-border text-teal" role="status"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const viewButtons = document.querySelectorAll('.view-book');
    const bookModalElement = document.getElementById('bookModal');
    const bookModal = new bootstrap.Modal(bookModalElement);
    const detailsContainer = document.getElementById('bookDetails');

    function loadBookDetails(bookId) {
        detailsContainer.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-teal" role="status"></div></div>';
        
        fetch(`../ajax/get_book.php?id=${bookId}`)
            .then(response => response.text())
            .then(data => {
                detailsContainer.innerHTML = data;
            })
            .catch(() => {
                detailsContainer.innerHTML = '<div class="alert alert-danger">Error loading book details</div>';
            });
    }

    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const bookId = this.getAttribute('data-id');
            loadBookDetails(bookId);
        });
    });

    // Handle auto-open if sn is in URL
    const urlParams = new URLSearchParams(window.location.search);
    const sn = urlParams.get('sn');
    if (sn) {
        loadBookDetails(sn);
        bookModal.show();
    }
});
</script>

<?php require_once '../includes/footer.php'; ?>
