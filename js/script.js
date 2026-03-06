// Navigation mobile toggle
document.addEventListener('DOMContentLoaded', function() {
    const navToggle = document.querySelector('.nav-toggle');
    const navMenu = document.querySelector('.nav-menu');

    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function() {
            const isOpen = navMenu.classList.toggle('active');
            // Update button text for ☰ style toggle
            const spans = this.querySelectorAll('span');
            if (spans.length > 0) {
                // Animated 3-bar burger
                this.classList.toggle('active');
                if (this.classList.contains('active')) {
                    spans[0].style.transform = 'rotate(45deg) translate(7px, 7px)';
                    spans[1].style.opacity = '0';
                    spans[2].style.transform = 'rotate(-45deg) translate(7px, -7px)';
                } else {
                    spans[0].style.transform = '';
                    spans[1].style.opacity = '1';
                    spans[2].style.transform = '';
                }
            } else {
                // Simple ☰ / ✕ toggle
                this.textContent = isOpen ? '✕' : '☰';
            }
        });
    }

    // Close mobile menu when clicking on a non-dropdown link
    const navLinks = document.querySelectorAll('.nav-menu a');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                // Only close if it's not a dropdown parent
                const parentLi = this.closest('li');
                if (!parentLi || !parentLi.classList.contains('has-dropdown')) {
                    if (navMenu) navMenu.classList.remove('active');
                    if (navToggle) {
                        const spans = navToggle.querySelectorAll('span');
                        if (spans.length > 0) {
                            navToggle.classList.remove('active');
                            spans[0].style.transform = '';
                            spans[1].style.opacity = '1';
                            spans[2].style.transform = '';
                        } else {
                            navToggle.textContent = '☰';
                        }
                    }
                }
            }
        });
    });

    // Mobile dropdown toggle — new .has-dropdown pattern
    const hasDropdowns = document.querySelectorAll('.nav-menu li.has-dropdown');
    hasDropdowns.forEach(item => {
        const link = item.querySelector(':scope > a');
        if (link) {
            link.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                    // Close other open dropdowns
                    hasDropdowns.forEach(other => {
                        if (other !== item) other.classList.remove('open');
                    });
                    item.classList.toggle('open');
                }
            });
        }
    });

    // Mobile dropdown toggle — legacy .nav-dropdown pattern (backward compat)
    const legacyDropdowns = document.querySelectorAll('.nav-dropdown');
    legacyDropdowns.forEach(dropdown => {
        const link = dropdown.querySelector('a');
        const menu = dropdown.querySelector('.dropdown-menu');
        if (link && menu) {
            link.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                    menu.style.display = menu.style.display === 'flex' ? 'none' : 'flex';
                    menu.style.flexDirection = 'column';
                }
            });
        }
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href !== '#' && href !== '') {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        });
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.has-dropdown')) {
            hasDropdowns.forEach(item => item.classList.remove('open'));
        }
        // Close mobile menu when clicking outside header
        if (window.innerWidth <= 768 && !e.target.closest('.header')) {
            if (navMenu) navMenu.classList.remove('active');
            if (navToggle) {
                const spans = navToggle.querySelectorAll('span');
                if (spans.length === 0) navToggle.textContent = '☰';
                else { navToggle.classList.remove('active'); }
            }
        }
    });

    // Lazy loading for images
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        observer.unobserve(img);
                    }
                }
            });
        });
        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
});

// Scroll reveal animations
window.addEventListener('scroll', function() {
    const reveals = document.querySelectorAll('.symptome-card, .symptom-card, .intro-text, .intro-image, .card');
    reveals.forEach(element => {
        const windowHeight = window.innerHeight;
        const elementTop = element.getBoundingClientRect().top;
        if (elementTop < windowHeight - 100) {
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }
    });
});

// Initialize animation styles
document.addEventListener('DOMContentLoaded', function() {
    const animatedElements = document.querySelectorAll('.symptome-card, .symptom-card, .intro-text, .intro-image');
    animatedElements.forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    });
    window.dispatchEvent(new Event('scroll'));
});
