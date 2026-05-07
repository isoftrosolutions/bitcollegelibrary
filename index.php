<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$page_title = "Home | BIT College";

// Fetch recent books (up to 5)
$recent_books = function_exists('get_all_books') ? get_all_books('', '', 5) : [];

require_once 'includes/header-premium.php';
?>

<!-- ===================== HERO ===================== -->
<section class="home-hero">
    <div class="home-hero-grid"></div>

    <div class="container">
        <div class="row align-items-center g-4">

            <!-- Left: text -->
            <div class="col-lg-6">
                <div class="hero-content-home">
                    <div class="hero-smart-badge">
                        <span class="badge-pulse"></span>
                        Smart Digital Campus
                    </div>

                    <h1 class="hero-title-home">
                        Search, Reserve &amp; Collect<br>
                        <span class="highlight">Library Books Online</span>
                    </h1>

                    <p class="hero-subtitle-home">
                        BIT's digital platform for library access, notes, resources and academic services.
                    </p>

                    <div class="hero-actions-home">
                        <a href="/bit/pages/books.php" class="btn-hero-primary">
                            <i class="fas fa-search"></i> Search Books
                        </a>
                        <a href="/bit/pages/login.php" class="btn-hero-secondary">
                            <i class="fas fa-user"></i> Student Login
                        </a>
                    </div>

                    <div class="hero-stats-home">
                        <div class="hstat">
                            <strong>26+</strong>
                            <span>Years of Excellence</span>
                        </div>
                        <div class="hstat">
                            <strong>1500+</strong>
                            <span>Students Enrolled</span>
                        </div>
                        <div class="hstat">
                            <strong>6</strong>
                            <span>Diploma Programs</span>
                        </div>
                        <div class="hstat">
                            <strong>10K+</strong>
                            <span>Books in Library</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: dashboard mockup -->
            <div class="col-lg-6 d-none d-lg-block">
                <div class="dashboard-mockup-wrap">
                    <div class="dashboard-mockup-card">

                        <!-- Top bar -->
                        <div class="dmock-topbar">
                            <div class="dmock-avatar">AS</div>
                            <div class="dmock-user-info">
                                <strong>Welcome back,</strong>
                                <span>Amit Shrestha</span>
                                <small>Library ID: Engineering</small>
                            </div>
                        </div>

                        <!-- Body -->
                        <div class="dmock-body">
                            <!-- Sidebar -->
                            <div class="dmock-sidebar">
                                <div class="dmock-nav-item active"><i class="fas fa-tachometer-alt"></i> Dashboard</div>
                                <div class="dmock-nav-item"><i class="fas fa-book"></i> My books</div>
                                <div class="dmock-nav-item"><i class="fas fa-calendar-check"></i> Reservations</div>
                                <div class="dmock-nav-item"><i class="fas fa-download"></i> Downloads</div>
                                <div class="dmock-nav-item"><i class="fas fa-bell"></i> Notices</div>
                                <div class="dmock-nav-item"><i class="fas fa-user"></i> Profile</div>
                                <div class="dmock-nav-item"><i class="fas fa-cog"></i> Settings</div>
                                <div class="dmock-nav-item danger"><i class="fas fa-sign-out-alt"></i> Logout</div>
                            </div>

                            <!-- Main -->
                            <div class="dmock-main">
                                <div class="dmock-page-title">
                                    Dashboard
                                    <i class="fas fa-bell dmock-bell"></i>
                                </div>
                                <div class="dmock-issued-heading">
                                    My Issued Books
                                    <span class="dmock-view-all">View All</span>
                                </div>
                                <div class="dmock-stats-row">
                                    <div class="dmock-stat-box">
                                        <strong>2</strong>
                                        <span>Books Issued</span>
                                    </div>
                                    <div class="dmock-stat-box">
                                        <strong>7</strong>
                                        <span>Days Left</span>
                                    </div>
                                </div>
                                <div class="dmock-book-row">
                                    <div class="dmock-book-cover-mini"></div>
                                    <div class="dmock-book-info">
                                        <strong>Data Structures</strong>
                                        <small>Author: Y. Kanetkar</small>
                                    </div>
                                    <span class="dmock-book-due">Due: 15 May</span>
                                </div>
                                <div class="dmock-book-row">
                                    <div class="dmock-book-cover-mini v2"></div>
                                    <div class="dmock-book-info">
                                        <strong>Engineering Physics</strong>
                                        <small>Author: D. Halliday</small>
                                    </div>
                                    <span class="dmock-book-due">Due: 22 May</span>
                                </div>
                            </div>

                            <!-- Right panel -->
                            <div class="dmock-right-panel">
                                <div class="dmock-panel-title">Reservation Status</div>
                                <div class="dmock-status-chart">
                                    <div class="dmock-donut"></div>
                                </div>
                                <div class="dmock-legend">
                                    <div class="dmock-legend-item"><div class="dmock-legend-dot" style="background:#22c55e"></div> Approved: 2</div>
                                    <div class="dmock-legend-item"><div class="dmock-legend-dot" style="background:#FDB913"></div> Pending: 1</div>
                                    <div class="dmock-legend-item"><div class="dmock-legend-dot" style="background:#ef4444"></div> Rejected: 0</div>
                                </div>
                                <div class="dmock-panel-title">Notifications</div>
                                <div class="dmock-notif-item">
                                    <div class="dmock-notif-text">Your book "AutoCAD 2D&amp;3D" is ready for pickup.</div>
                                    <div class="dmock-notif-time">2 hours ago</div>
                                </div>
                                <div class="dmock-notif-item">
                                    <div class="dmock-notif-text">Internal Examination Routine Published.</div>
                                    <div class="dmock-notif-time">5 hours ago</div>
                                </div>
                            </div>
                        </div><!-- /dmock-body -->

                    </div><!-- /dashboard-mockup-card -->
                </div>
            </div>

        </div><!-- /row -->
    </div><!-- /container -->
</section>

<!-- Popular Searches Strip -->
<div class="popular-strip">
    <div class="container">
        <span class="popular-label">Popular Searches:</span>
        <a href="/bit/pages/books.php?search=DBMS" class="popular-tag">DBMS</a>
        <a href="/bit/pages/books.php?search=C+Programming" class="popular-tag">C Programming</a>
        <a href="/bit/pages/books.php?search=Electrical+Machine" class="popular-tag">Electrical Machine</a>
        <a href="/bit/pages/books.php?search=Surveying" class="popular-tag">Surveying</a>
        <a href="/bit/pages/books.php?search=Physics" class="popular-tag">Physics</a>
        <a href="/bit/pages/books.php?search=AutoCAD" class="popular-tag">AutoCAD</a>
    </div>
</div>

<!-- ===================== FEATURES ===================== -->
<section class="home-features-section">
    <div class="container">
        <div class="home-features-grid">
            <div class="home-feature-card">
                <div class="home-feature-icon icon-blue"><i class="fas fa-search"></i></div>
                <div class="home-feature-title">Search Books</div>
                <div class="home-feature-desc">Find books instantly by title, author or subject.</div>
            </div>
            <div class="home-feature-card">
                <div class="home-feature-icon icon-green"><i class="fas fa-bookmark"></i></div>
                <div class="home-feature-title">Reserve Online</div>
                <div class="home-feature-desc">Reserve books before arrival and save your time.</div>
            </div>
            <div class="home-feature-card">
                <div class="home-feature-icon icon-orange"><i class="fas fa-bell"></i></div>
                <div class="home-feature-title">Pickup Alerts</div>
                <div class="home-feature-desc">Get notified when your book is ready for pickup.</div>
            </div>
            <div class="home-feature-card">
                <div class="home-feature-icon icon-purple"><i class="fas fa-undo-alt"></i></div>
                <div class="home-feature-title">Return Tracking</div>
                <div class="home-feature-desc">Track issued books and due dates easily.</div>
            </div>
            <div class="home-feature-card">
                <div class="home-feature-icon icon-red"><i class="fas fa-tachometer-alt"></i></div>
                <div class="home-feature-title">Student Dashboard</div>
                <div class="home-feature-desc">Manage all your requests from one place.</div>
            </div>
            <div class="home-feature-card">
                <div class="home-feature-icon icon-teal"><i class="fas fa-file-alt"></i></div>
                <div class="home-feature-title">Digital Resources</div>
                <div class="home-feature-desc">Access notes, papers and study materials online.</div>
            </div>
        </div>
    </div>
</section>

<!-- ===================== RECENTLY ADDED BOOKS ===================== -->
<section class="home-books-section">
    <div class="container">
        <div class="section-header-flex">
            <h2 class="section-title-sm">Recently Added Books</h2>
            <a href="/bit/pages/books.php" class="section-view-all">View All Books <i class="fas fa-arrow-right"></i></a>
        </div>

        <div class="books-row-home">
            <?php
            $statuses = ['available', 'available', 'reserved', 'limited', 'available'];
            if ($recent_books && count($recent_books) > 0):
                foreach ($recent_books as $i => $book):
                    $status = $statuses[$i % count($statuses)];
                    $badge_class = 'badge-' . $status;
                    $badge_label = ucfirst($status);
            ?>
            <div class="book-card-home">
                <div class="book-cover-home">
                    <?php if (!empty($book['image']) && file_exists('assets/uploads/books/' . $book['image'])): ?>
                        <img src="/bit/assets/uploads/books/<?php echo htmlspecialchars($book['image']); ?>"
                             alt="<?php echo htmlspecialchars($book['book_name']); ?>">
                    <?php else: ?>
                        <div class="book-cover-home-bg">
                            <i class="fas fa-book-open"></i>
                            <span><?php echo htmlspecialchars(substr($book['book_name'], 0, 30)); ?></span>
                        </div>
                    <?php endif; ?>
                    <span class="book-status-badge <?php echo $badge_class; ?>"><?php echo $badge_label; ?></span>
                </div>
                <div class="book-info-home">
                    <div class="book-title-home"><?php echo htmlspecialchars($book['book_name']); ?></div>
                    <div class="book-author-home">Author: <?php echo htmlspecialchars($book['author']); ?></div>
                    <a href="/bit/pages/books.php?sn=<?php echo $book['sn']; ?>" class="book-reserve-btn">Reserve</a>
                </div>
            </div>
            <?php
                endforeach;
            else:
                $placeholder_books = [
                    ['name'=>'Data Structures Using C','author'=>'Y. Kanetkar','status'=>'available'],
                    ['name'=>'Engineering Physics','author'=>'D. Halliday','status'=>'reserved'],
                    ['name'=>'AutoCAD 2D and 3D','author'=>'Sham Tickoo','status'=>'available'],
                    ['name'=>'Electrical Machine','author'=>'P.S. Bimbhra','status'=>'limited'],
                    ['name'=>'Digital Logic Design','author'=>'M. Morris Mano','status'=>'available'],
                ];
                foreach ($placeholder_books as $book):
            ?>
            <div class="book-card-home">
                <div class="book-cover-home">
                    <div class="book-cover-home-bg">
                        <i class="fas fa-book-open"></i>
                        <span><?php echo htmlspecialchars($book['name']); ?></span>
                    </div>
                    <span class="book-status-badge badge-<?php echo $book['status']; ?>"><?php echo ucfirst($book['status']); ?></span>
                </div>
                <div class="book-info-home">
                    <div class="book-title-home"><?php echo htmlspecialchars($book['name']); ?></div>
                    <div class="book-author-home">Author: <?php echo htmlspecialchars($book['author']); ?></div>
                    <a href="/bit/pages/books.php" class="book-reserve-btn">Reserve</a>
                </div>
            </div>
            <?php endforeach; endif; ?>
        </div>
    </div>
</section>

<!-- ===================== LATEST RESOURCES ===================== -->
<section class="home-resources-section" id="resources">
    <div class="container">
        <div class="section-header-flex">
            <h2 class="section-title-sm">Latest Resources</h2>
            <a href="/bit/pages/books.php" class="section-view-all">View All Resources <i class="fas fa-arrow-right"></i></a>
        </div>

        <div class="resources-tabs-home">
            <button class="res-tab active" data-rtab="all">All</button>
            <button class="res-tab" data-rtab="notes">Notes</button>
            <button class="res-tab" data-rtab="qp">Question Papers</button>
            <button class="res-tab" data-rtab="assign">Assignments</button>
            <button class="res-tab" data-rtab="lab">Lab Manuals</button>
            <button class="res-tab" data-rtab="syllabus">Syllabus</button>
        </div>

        <div class="resources-grid-home">
            <div class="resource-card-home">
                <div class="resource-icon-home"><i class="fas fa-file-alt"></i></div>
                <div class="resource-title-home">Data Structures</div>
                <div class="resource-sub-home">Unit 1 Notes</div>
                <div class="resource-meta-home"><i class="fas fa-tag"></i> Computer Eng. &bull; 5.4 MB</div>
            </div>
            <div class="resource-card-home">
                <div class="resource-icon-home"><i class="fas fa-file-alt"></i></div>
                <div class="resource-title-home">Engineering Physics</div>
                <div class="resource-sub-home">Important Questions</div>
                <div class="resource-meta-home"><i class="fas fa-tag"></i> Civil Eng. &bull; 1.8 MB</div>
            </div>
            <div class="resource-card-home">
                <div class="resource-icon-home"><i class="fas fa-flask"></i></div>
                <div class="resource-title-home">Surveying I</div>
                <div class="resource-sub-home">Lab Manual</div>
                <div class="resource-meta-home"><i class="fas fa-tag"></i> Civil Eng. &bull; 3.1 MB</div>
            </div>
            <div class="resource-card-home">
                <div class="resource-icon-home"><i class="fas fa-file-alt"></i></div>
                <div class="resource-title-home">Digital Electronics</div>
                <div class="resource-sub-home">Unit 2 Notes</div>
                <div class="resource-meta-home"><i class="fas fa-tag"></i> Electronics Eng. &bull; 2.2 MB</div>
            </div>
            <div class="resource-card-home">
                <div class="resource-icon-home"><i class="fas fa-question-circle"></i></div>
                <div class="resource-title-home">Thermodynamics</div>
                <div class="resource-sub-home">Question Set</div>
                <div class="resource-meta-home"><i class="fas fa-tag"></i> Mechanical Eng. &bull; 1.4 MB</div>
            </div>
            <div class="resource-card-home">
                <div class="resource-icon-home"><i class="fas fa-code"></i></div>
                <div class="resource-title-home">C Programming</div>
                <div class="resource-sub-home">Practical File</div>
                <div class="resource-meta-home"><i class="fas fa-tag"></i> Computer Eng. &bull; 3.7 MB</div>
            </div>
        </div>
    </div>
</section>

<!-- ===================== NOTICES + TESTIMONIALS ===================== -->
<section class="home-notices-section">
    <div class="container">
        <div class="notices-two-col">

            <!-- Notices -->
            <div>
                <div class="notices-col-title">
                    Notices &amp; Announcements
                    <a href="#">View All Notices <i class="fas fa-arrow-right"></i></a>
                </div>

                <div class="notice-item-home">
                    <div class="notice-date-badge">
                        <div class="notice-day">08</div>
                        <div class="notice-month">May</div>
                    </div>
                    <div class="notice-content-home">
                        <span class="notice-priority priority-important">Important</span>
                        <div class="notice-title-home">Internal Examination Routine Published</div>
                        <div class="notice-text-home">All students are informed to check the internal examination routine for this semester.</div>
                    </div>
                </div>

                <div class="notice-item-home">
                    <div class="notice-date-badge">
                        <div class="notice-day">05</div>
                        <div class="notice-month">May</div>
                    </div>
                    <div class="notice-content-home">
                        <span class="notice-priority priority-notice">Notice</span>
                        <div class="notice-title-home">Library Timing Change</div>
                        <div class="notice-text-home">Library will now remain open from 7:30 AM to 6:00 PM on working days.</div>
                    </div>
                </div>

                <div class="notice-item-home">
                    <div class="notice-date-badge">
                        <div class="notice-day">01</div>
                        <div class="notice-month">May</div>
                    </div>
                    <div class="notice-content-home">
                        <span class="notice-priority priority-update">Update</span>
                        <div class="notice-title-home">Scholarship Form Open</div>
                        <div class="notice-text-home">Scholarship application form is open till 15th May 2025.</div>
                    </div>
                </div>
            </div>

            <!-- Testimonials -->
            <div>
                <div class="notices-col-title">
                    What Our Students Say
                    <a href="#">View All <i class="fas fa-arrow-right"></i></a>
                </div>

                <div class="testimonial-card-new">
                    <div class="testimonial-stars">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <div class="testimonial-text-new">
                        "BIT has provided me the perfect platform to grow my technical skills and confidence. The digital library is a great initiative."
                    </div>
                    <div class="testimonial-author-new">
                        <div class="testimonial-avatar-new">SK</div>
                        <div class="testimonial-info-new">
                            <strong>Sushant Karki</strong>
                            <small>Computer Engineering</small>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card-new">
                    <div class="testimonial-stars">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <div class="testimonial-text-new">
                        "The faculty and resources here are excellent. The digital library is well organized and easy to use."
                    </div>
                    <div class="testimonial-author-new">
                        <div class="testimonial-avatar-new">AT</div>
                        <div class="testimonial-info-new">
                            <strong>Anushka Thapa</strong>
                            <small>Civil Engineering</small>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card-new">
                    <div class="testimonial-stars">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <div class="testimonial-text-new">
                        "The reservation system saves a lot of time. Everything is so well organized."
                    </div>
                    <div class="testimonial-author-new">
                        <div class="testimonial-avatar-new">RY</div>
                        <div class="testimonial-info-new">
                            <strong>Roshan Yadav</strong>
                            <small>Electrical Engineering</small>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ===================== CTA ===================== -->
<section class="home-cta-section">
    <div class="container">
        <div class="home-cta-inner">
            <div class="home-cta-graphic">🎓</div>
            <div class="home-cta-body">
                <h2>Ready to Start Your Journey?</h2>
                <p>Join thousands of students building successful careers through quality education and modern learning.</p>
                <div class="home-cta-buttons">
                    <a href="/bit/pages/register.php" class="btn-hero-primary">Apply Now <i class="fas fa-arrow-right"></i></a>
                    <a href="/bit/pages/contact.php" class="btn-hero-secondary">Take a Tour</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer-premium.php'; ?>

<script>
// Resource tabs (visual only — filter would need real data)
document.querySelectorAll('.res-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        document.querySelectorAll('.res-tab').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
    });
});

</script>
