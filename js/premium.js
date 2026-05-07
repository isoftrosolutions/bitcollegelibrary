// ===========================================
// PREMIUM EDUCATION PLATFORM - JS
// ===========================================

document.addEventListener('DOMContentLoaded', function() {
    
    // ===========================================
    // Header Scroll Effect
    // ===========================================
    const header = document.getElementById('main-header');
    const hero = document.querySelector('.hero');
    
    function handleHeaderScroll() {
        const heroBottom = hero ? hero.offsetHeight : 200;
        
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
            header.classList.remove('transparent');
        } else {
            header.classList.remove('scrolled');
            header.classList.add('transparent');
        }
    }
    
    window.addEventListener('scroll', handleHeaderScroll);
    handleHeaderScroll();
    
    // ===========================================
    // Mobile Navigation
    // ===========================================
    const mobileToggle = document.getElementById('mobile-toggle');
    const mobileNav = document.getElementById('mobile-nav');
    const mobileNavClose = document.getElementById('mobile-nav-close');
    
    if (mobileToggle && mobileNav) {
        mobileToggle.addEventListener('click', () => {
            mobileNav.classList.add('active');
            document.body.style.overflow = 'hidden';
            mobileToggle.classList.add('active');
        });
        
        const closeMobileNav = () => {
            mobileNav.classList.remove('active');
            document.body.style.overflow = '';
            if (mobileToggle) {
                mobileToggle.classList.remove('active');
            }
        };
        
        if (mobileNavClose) {
            mobileNavClose.addEventListener('click', closeMobileNav);
        }
        
        // Close on link click
        document.querySelectorAll('.mobile-nav-link').forEach(link => {
            link.addEventListener('click', closeMobileNav);
        });
        
        // Close on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && mobileNav.classList.contains('active')) {
                closeMobileNav();
            }
        });
    }
    
    // ===========================================
    // Smooth Scroll
    // ===========================================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href === '#') return;
            
            e.preventDefault();
            const target = document.querySelector(href);
            
            if (target) {
                const headerHeight = header ? header.offsetHeight : 80;
                const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerHeight;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // ===========================================
    // Scroll Animations (Intersection Observer)
    // ===========================================
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const animationObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, observerOptions);
    
    document.querySelectorAll('.animate-on-scroll').forEach(el => {
        animationObserver.observe(el);
    });
    
    // ===========================================
    // Stats Counter Animation
    // ===========================================
    const statsSection = document.querySelector('.stats-section');
    let statsAnimated = false;
    
    function animateStats() {
        const statNumbers = document.querySelectorAll('.stat-number');
        
        statNumbers.forEach(stat => {
            const text = stat.textContent;
            const match = text.match(/(\d+)/);
            
            if (match) {
                const target = parseInt(match[0]);
                const isPlus = text.includes('+');
                const prefix = text.includes('26') ? '' : '';
                
                let current = 0;
                const increment = Math.ceil(target / 40);
                
                const updateNumber = () => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        stat.textContent = prefix + current + (isPlus ? '+' : '');
                    } else {
                        stat.textContent = prefix + current;
                        requestAnimationFrame(updateNumber);
                    }
                };
                
                requestAnimationFrame(updateNumber);
            }
        });
    }
    
    if (statsSection) {
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !statsAnimated) {
                    statsAnimated = true;
                    setTimeout(animateStats, 200);
                }
            });
        }, { threshold: 0.3 });
        
        statsObserver.observe(statsSection);
    }
    
    // ===========================================
    // Course Filter Buttons
    // ===========================================
    const filterBtns = document.querySelectorAll('.filter-btn');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all
            filterBtns.forEach(b => b.classList.remove('active'));
            // Add to clicked
            this.classList.add('active');
            
            // Get filter value
            const filter = this.textContent.toLowerCase();
            
            // Filter cards (would connect to actual data)
            const courseCards = document.querySelectorAll('.course-card');
            
            courseCards.forEach(card => {
                if (filter === 'all courses' || filter === 'all') {
                    card.style.display = 'block';
                } else {
                    // Add actual filtering logic here
                    card.style.display = 'block';
                }
            });
        });
    });
    
    // ===========================================
    // Book Category Tabs
    // ===========================================
    const tabBtns = document.querySelectorAll('.tab-btn');
    
    tabBtns.forEach(tab => {
        tab.addEventListener('click', function() {
            tabBtns.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            const category = this.dataset.filter;
            const bookCards = document.querySelectorAll('.book-card');
            
            bookCards.forEach(card => {
                if (category === 'all') {
                    card.style.display = 'block';
                } else {
                    // Add actual category filtering
                    card.style.display = 'block';
                }
            });
        });
    });
    
    // ===========================================
    // Search Toggle (Placeholder)
    // ===========================================
    const searchToggle = document.querySelector('.search-toggle');
    
    if (searchToggle) {
        searchToggle.addEventListener('click', () => {
            // Could implement a search modal here
            const searchInput = prompt('Search for courses, books, or information:');
            if (searchInput) {
                window.location.href = '/bit/pages/books.php?search=' + encodeURIComponent(searchInput);
            }
        });
    }
    
    // ===========================================
    // Parallax Effect for Hero Shapes
    // ===========================================
    const heroShapes = document.querySelectorAll('.hero-shape');
    
    if (heroShapes.length > 0) {
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const rate = scrolled * 0.1;
            
            heroShapes.forEach((shape, index) => {
                const direction = index % 2 === 0 ? 1 : -1;
                shape.style.transform = `translateY(${rate * direction}px)`;
            });
        });
    }
    
    // ===========================================
    // Button Hover Effects Enhancement
    // ===========================================
    const ctaButtons = document.querySelectorAll('.btn');
    
    ctaButtons.forEach(btn => {
        btn.addEventListener('mouseenter', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            this.style.setProperty('--mouse-x', x + 'px');
            this.style.setProperty('--mouse-y', y + 'px');
        });
    });
    
    // ===========================================
    // Keyboard Navigation Support
    // ===========================================
    document.addEventListener('keydown', (e) => {
        // Tab navigation enhancements
        if (e.key === 'Tab') {
            document.body.classList.add('keyboard-navigation');
        }
    });
    
    document.addEventListener('mousedown', () => {
        document.body.classList.remove('keyboard-navigation');
    });
    
    // ===========================================
    // Reduce Motion Preference
    // ===========================================
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    
    if (prefersReducedMotion.matches) {
        // Remove animations for users who prefer reduced motion
        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            el.classList.add('visible');
        });
    }
    
    // ===========================================
    // Initialize Tooltips (Bootstrap)
    // ===========================================
    if (typeof bootstrap !== 'undefined') {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(tooltipTriggerEl => {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
    
    console.log('Premium BIT Education Platform Loaded Successfully');
});

// ===========================================
// Utility Functions
// ===========================================

// Debounce function for scroll events
function debounce(func, wait = 10, immediate = true) {
    let timeout;
    return function() {
        const context = this;
        const args = arguments;
        const later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        const callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
}

// Throttle function for resize events
function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}