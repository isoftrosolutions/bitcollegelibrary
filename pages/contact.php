<?php
require_once '../config/database.php';
require_once '../includes/functions.php';

$page_title = "Contact Us";
require_once '../includes/header.php';
?>

<div class="row justify-content-center pt-5 pb-5">
    <div class="col-lg-10">
        <div class="card book-card border-0 shadow-sm p-4">
            <div class="row g-5">
                <div class="col-md-6">
                    <h2 class="hero-title mb-4" style="font-size: 2.5rem;">Get in <span class="text-teal">Touch</span></h2>
                    <p class="hero-subtitle mb-5">Have questions about library membership or book availability? Our team is here to help.</p>
                    
                    <div class="d-flex mb-4">
                        <div class="theme-toggle me-3" style="cursor: default;"><i class="fas fa-map-marker-alt"></i></div>
                        <div>
                            <h5 class="mb-1">Location</h5>
                            <p class="text-gray">Birgunj Institute of Technology, Parsa, Nepal</p>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-4">
                        <div class="theme-toggle me-3" style="cursor: default;"><i class="fas fa-phone"></i></div>
                        <div>
                            <h5 class="mb-1">Phone</h5>
                            <p class="text-gray">+977-51-123456</p>
                        </div>
                    </div>
                    
                    <div class="d-flex mb-4">
                        <div class="theme-toggle me-3" style="cursor: default;"><i class="fas fa-envelope"></i></div>
                        <div>
                            <h5 class="mb-1">Email</h5>
                            <p class="text-gray">info@bit.edu.np</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <form action="#" method="POST">
                        <div class="mb-3">
                            <label class="form-label text-gray">Your Name</label>
                            <input type="text" class="form-control" style="background: var(--bg-dark); border: 1px solid var(--border-color); color: var(--text-primary);" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-gray">Email Address</label>
                            <input type="email" class="form-control" style="background: var(--bg-dark); border: 1px solid var(--border-color); color: var(--text-primary);" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-gray">Message</label>
                            <textarea class="form-control" style="background: var(--bg-dark); border: 1px solid var(--border-color); color: var(--text-primary);" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-register w-100 py-3">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
