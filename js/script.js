document.addEventListener('DOMContentLoaded', function() {
    // Admin search functionality
    const searchInput = document.getElementById('admin-search-input');
    const searchBtn = document.getElementById('admin-search-btn');
    const searchResults = document.getElementById('admin-search-results');
    
    if (searchInput && searchBtn && searchResults) {
        // Search function
        const performSearch = function(query) {
            if (query.length < 2) {
                searchResults.innerHTML = '';
                return;
            }
            
            fetch(`/bit/ajax/search_everything.php?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    displaySearchResults(data);
                })
                .catch(error => {
                    console.error('Search error:', error);
                    searchResults.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Error searching. Please try again.
                        </div>
                    `;
                });
        };
        
        // Display results function
        const displaySearchResults = function(data) {
            let html = '';
            
            // Users section
            if (data.users.length > 0) {
                html += `
                    <div class="mb-4">
                        <h6 class="fw-bold text-teal mb-3">
                            <i class="fas fa-users me-2"></i>Users (${data.users.length})
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-dark-custom">
                                <thead>
                                    <tr>
                                        <th>Avatar</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Faculty</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                `;
                
                data.users.forEach(user => {
                    html += `
                        <tr>
                            <td>
                                <div class="theme-toggle" style="width: 40px; height: 40px; cursor: default;">
                                    <i class="fas fa-user text-teal"></i>
                                </div>
                            </td>
                            <td>${htmlspecialchars(user.name)}</td>
                            <td>${htmlspecialchars(user.email)}</td>
                            <td><span class="badge bg-${(user.role || '') == 'admin' ? 'teal' : 'secondary'}">${ucfirst(user.role || '')}</span></td>
                            <td>${ucfirst(user.faculty || '')}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="/bit/admin/users/profile.php?id=${user.id}" class="btn btn-sm btn-outline-info me-2"><i class="fas fa-eye"></i></a>
                                    <a href="/bit/admin/users/edit.php?id=${user.id}" class="btn btn-sm btn-outline-teal me-2"><i class="fas fa-edit"></i></a>
                                    <a href="/bit/admin/users/delete.php?id=${user.id}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                    `;
                });
                
                html += `
                                </tbody>
                            </table>
                        </div>
                    </div>
                `;
            }
            
            // Books section
            if (data.books.length > 0) {
                html += `
                    <div class="mb-4">
                        <h6 class="fw-bold text-teal mb-3">
                            <i class="fas fa-book me-2"></i>Books (${data.books.length})
                        </h6>
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
                `;
                
                data.books.forEach(book => {
                    html += `
                        <tr>
                            <td>
                                ${book.image ? 
                                    `<img src="/bit/assets/uploads/books/${book.image}" alt="Book" style="width: 40px; height: 50px; object-fit: cover; border-radius: 4px;">` : 
                                    `<div class="bg-dark d-flex align-items-center justify-content-center" style="width: 40px; height: 50px; border-radius: 4px;"><i class="fas fa-book text-gray small"></i></div>`
                                }
                            </td>
                            <td>#${htmlspecialchars(book.book_no)}</td>
                            <td>${htmlspecialchars(book.book_name)}</td>
                            <td>${htmlspecialchars(book.author)}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="/bit/admin/books/edit.php?sn=${book.sn}" class="btn btn-sm btn-outline-teal me-2"><i class="fas fa-edit"></i></a>
                                    <a href="/bit/admin/books/delete.php?sn=${book.sn}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                    `;
                });
                
                html += `
                                </tbody>
                            </table>
                        </div>
                    </div>
                `;
            }
            
            // Rentals section
            if (data.rentals.length > 0) {
                html += `
                    <div class="mb-4">
                        <h6 class="fw-bold text-teal mb-3">
                            <i class="fas fa-hand-holding me-2"></i>Rentals (${data.rentals.length})
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-dark-custom">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Book</th>
                                        <th>Book No</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                `;
                
                data.rentals.forEach(rental => {
                    const statusClass = rental.status === 'pending' ? 'warning' : 
                                       rental.status === 'approved' ? 'teal' : 
                                       rental.status === 'returned' ? 'success' : 'secondary';
                                       
                    html += `
                        <tr>
                            <td>${htmlspecialchars(rental.user_name)}</td>
                            <td>${htmlspecialchars(rental.book_name)}</td>
                            <td>#${htmlspecialchars(rental.book_no)}</td>
                            <td><span class="badge bg-${statusClass}">${ucfirst(rental.status)}</span></td>
                            <td>
                                <div class="btn-group">
                                    <a href="/bit/admin/rentals.php" class="btn btn-sm btn-outline-info me-2"><i class="fas fa-eye"></i></a>
                                </div>
                            </td>
                        </tr>
                    `;
                });
                
                html += `
                                </tbody>
                            </table>
                        </div>
                    </div>
                `;
            }
            
            // No results
            if (data.users.length === 0 && data.books.length === 0 && data.rentals.length === 0) {
                html = `
                    <div class="text-center py-5">
                        <i class="fas fa-search text-gray mb-3" style="font-size: 3rem;"></i>
                        <p class="text-gray">No results found for "${htmlspecialchars(searchInput.value)}"</p>
                    </div>
                `;
            }
            
            searchResults.innerHTML = html;
        };
        
        // Helper functions
        const htmlspecialchars = function(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"]/g, function(m) { return map[m]; });
        };
        
        const ucfirst = function(text) {
            return text.charAt(0).toUpperCase() + text.slice(1).toLowerCase();
        };
        
        // Event listeners
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                performSearch(this.value);
            }, 300);
        });
        
        searchBtn.addEventListener('click', function() {
            performSearch(searchInput.value);
        });
        
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch(this.value);
            }
        });
    }
    
    // Navbar scroll effect
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('navbar-scrolled');
            } else {
                navbar.classList.remove('navbar-scrolled');
            }
        });
    }

    // Add navbar-scrolled class to CSS
    const style = document.createElement('style');
    style.innerHTML = `
        .navbar-scrolled {
            background: var(--nav-bg) !important;
            backdrop-filter: blur(15px);
            border-bottom: 1px solid var(--border-color);
            padding: 10px 0 !important;
        }
        .navbar {
            transition: var(--transition);
            padding: 20px 0;
        }
    `;
    document.head.appendChild(style);

    // Smooth hover for book cards
    const cards = document.querySelectorAll('.book-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
