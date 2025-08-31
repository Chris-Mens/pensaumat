// Load Navigation
function loadNavigation() {
    const navPlaceholder = document.getElementById('nav-placeholder');
    if (navPlaceholder) {
        navPlaceholder.innerHTML = `
            <nav class="navbar">
                <div class="nav-container">
                    <div class="logo-container">
                        <a href="index.html" class="logo-link">
                            <img src="images/pensa-Logo.png" alt="PENSA UMaT Logo" class="logo-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';">
                            <span class="logo-text">PENSA UMaT</span>
                        </a>
                    </div>
                    <button class="mobile-menu-toggle" aria-label="Toggle menu" aria-expanded="false">
                        <i class="fas fa-bars"></i>
                    </button>
                    <ul class="nav-links">
                        <li><a href="index.html">Home</a></li>
                        <li><a href="about.html">About</a></li>
                        <li><a href="ministries.html">Ministries</a></li>
                        <li><a href="events.html">Events</a></li>
                        <li><a href="portfolio.html">Portfolio</a></li>
                        <li><a href="contact.html">Contact Us</a></li>
                    </ul>
                </div>
            </nav>
        `;
    }

    // Set active link
    const currentPage = window.location.pathname.split('/').pop() || 'index.html';
    const navLinks = document.querySelectorAll('.nav-links a');
    navLinks.forEach(link => {
        const linkHref = link.getAttribute('href');
        if ((currentPage === '' && linkHref === 'index.html') || 
            (linkHref !== 'index.html' && currentPage.includes(linkHref))) {
            link.classList.add('active');
        }
    });
}

// Load Footer
function loadFooter() {
    const footerPlaceholder = document.getElementById('footer-placeholder');
    if (footerPlaceholder) {
        footerPlaceholder.innerHTML = `
            <footer class="footer">
                <div class="footer-content">
                    <div class="footer-section">
                        <h3>Contact Us</h3>
                        <p><i class="fas fa-envelope"></i> info@pensau.edu.gh</p>
                        <p><i class="fas fa-phone"></i> +233 XX XXX XXXX</p>
                        <p><i class="fas fa-map-marker-alt"></i> University of Mines and Technology, Tarkwa</p>
                    </div>
                    <div class="footer-section">
                        <h3>Quick Links</h3>
                        <ul>
                            <li><a href="index.html">Home</a></li>
                            <li><a href="about.html">About Us</a></li>
                            <li><a href="ministries.html">Ministries</a></li>
                            <li><a href="portfolio.html">Our Team</a></li>
                        </ul>
                    </div>
                    <div class="footer-section">
                        <h3>Follow Us</h3>
                        <div class="social-links">
                            <a href="#" target="_blank" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                            <a href="#" target="_blank" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                            <a href="#" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                            <a href="#" target="_blank" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                            <a href="#" target="_blank" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                        </div>
                        <div class="newsletter" style="margin-top: 20px;">
                            <h4>Subscribe to our newsletter</h4>
                            <form id="newsletter-form" style="display: flex; margin-top: 10px;">
                                <input type="email" placeholder="Your email" required style="padding: 8px; border: 1px solid #ddd; border-radius: 4px 0 0 4px; flex: 1;">
                                <button type="submit" style="background: var(--secondary-color); border: none; padding: 0 15px; border-radius: 0 4px 4px 0; cursor: pointer;">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="footer-bottom">
                    <p>&copy; ${new Date().getFullYear()} PENSA UMaT. All Rights Reserved.</p>
                </div>
            </footer>
        `;
        
        // Add newsletter form submission handler
        const newsletterForm = document.getElementById('newsletter-form');
        if (newsletterForm) {
            newsletterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const email = this.querySelector('input[type="email"]').value;
                // Here you would typically send the email to your server
                alert('Thank you for subscribing to our newsletter!');
                this.reset();
            });
        }
    }
}

// Mobile menu toggle functionality
function setupMobileMenu() {
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    const navLinks = document.querySelector('.nav-links');
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    const navContainer = document.querySelector('.nav-container');
    let resizeTimer; // Moved to outer scope to prevent redeclaration

    if (menuToggle && navLinks) {
        // Toggle mobile menu
        const toggleMenu = (isExpanded) => {
            if (isExpanded === undefined) {
                isExpanded = navLinks.classList.toggle('active');
            } else {
                navLinks.classList.toggle('active', isExpanded);
            }
            
            menuToggle.setAttribute('aria-expanded', isExpanded);
            document.body.style.overflow = isExpanded ? 'hidden' : '';
            
            // Close all dropdowns when toggling the mobile menu
            if (!isExpanded) {
                closeAllDropdowns();
            }
            
            return isExpanded;
        };
        
        // Close all dropdown menus
        const closeAllDropdowns = () => {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.remove('show');
            });
            dropdownToggles.forEach(toggle => {
                const icon = toggle.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                }
            });
        };
        
        // Toggle menu when clicking the hamburger button
        menuToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleMenu();
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!navContainer.contains(e.target) && navLinks.classList.contains('active')) {
                toggleMenu(false);
            }
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (window.innerWidth > 992) {
                    toggleMenu(false);
                    closeAllDropdowns();
                }
            }, 100);
        });
        
        // Handle escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && navLinks.classList.contains('active')) {
                toggleMenu(false);
            }
        });
        
        // Handle dropdown toggles on mobile
        dropdownToggles.forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                if (window.innerWidth <= 992) { // Mobile view only
                    e.preventDefault();
                    const dropdown = this.nextElementSibling;
                    const isExpanded = dropdown.classList.contains('show');
                    
                    // Close all other dropdowns
                    document.querySelectorAll('.dropdown-menu').forEach(menu => {
                        if (menu !== dropdown) {
                            menu.classList.remove('show');
                            const otherIcon = menu.previousElementSibling?.querySelector('i');
                            if (otherIcon) {
                                otherIcon.classList.remove('fa-chevron-up');
                                otherIcon.classList.add('fa-chevron-down');
                            }
                        }
                    });
                    
                    // Toggle current dropdown
                    dropdown.classList.toggle('show');
                    const icon = this.querySelector('i');
                    if (icon) {
                        icon.classList.toggle('fa-chevron-down');
                        icon.classList.toggle('fa-chevron-up');
                    }
                }
            });
        });
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 992 && navLinks.classList.contains('active')) {
                if (!e.target.closest('.nav-container') && !e.target.closest('.mobile-menu-toggle')) {
                    navLinks.classList.remove('active');
                    menuToggle.setAttribute('aria-expanded', 'false');
                    
                    // Close all dropdowns
                    document.querySelectorAll('.dropdown-menu').forEach(menu => {
                        menu.classList.remove('show');
                    });
                    dropdownToggles.forEach(toggle => {
                        const icon = toggle.querySelector('i');
                        if (icon) {
                            icon.classList.remove('fa-chevron-up');
                            icon.classList.add('fa-chevron-down');
                        }
                    });
                }
            }
        });
        
        // Handle window resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (window.innerWidth > 992) {
                    navLinks.classList.remove('active');
                    menuToggle.setAttribute('aria-expanded', 'false');
                    document.querySelectorAll('.dropdown-menu').forEach(menu => {
                        menu.classList.remove('show');
                    });
                    dropdownToggles.forEach(toggle => {
                        const icon = toggle.querySelector('i');
                        if (icon) {
                            icon.classList.remove('fa-chevron-up');
                            icon.classList.add('fa-chevron-down');
                        }
                    });
                }
            }, 250);
        });
    }
}

// Portfolio Filtering
const filterButtons = document.querySelectorAll('.filter-btn');
const portfolioItems = document.querySelectorAll('.portfolio-item');

filterButtons.forEach(button => {
    button.addEventListener('click', () => {
        // Remove active class from all buttons
        filterButtons.forEach(btn => btn.classList.remove('active'));
        // Add active class to clicked button
        button.classList.add('active');
        
        const filterValue = button.getAttribute('data-filter');
        
        portfolioItems.forEach(item => {
            if (filterValue === 'all' || item.getAttribute('data-category') === filterValue) {
                item.style.display = 'block';
                item.style.animation = 'fadeIn 0.5s ease-in-out';
            } else {
                item.style.display = 'none';
            }
        });
    });
});

// Gallery Lightbox functionality
document.addEventListener('DOMContentLoaded', function() {
    const galleryItems = document.querySelectorAll('.gallery-item');
    const lightbox = document.createElement('div');
    lightbox.className = 'lightbox';
    lightbox.innerHTML = `
        <div class="lightbox-content">
            <span class="lightbox-close">&times;</span>
            <img src="" alt="">
        </div>
    `;
    document.body.appendChild(lightbox);

    // Open lightbox when clicking on gallery items
    galleryItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const imgSrc = this.querySelector('img').src;
            const imgAlt = this.querySelector('img').alt;
            
            lightbox.querySelector('img').src = imgSrc;
            lightbox.querySelector('img').alt = imgAlt;
            lightbox.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });
    });

    // Close lightbox when clicking the close button
    lightbox.querySelector('.lightbox-close').addEventListener('click', function() {
        lightbox.style.display = 'none';
        document.body.style.overflow = '';
    });

    // Close lightbox when clicking outside the image
    lightbox.addEventListener('click', function(e) {
        if (e.target === lightbox) {
            lightbox.style.display = 'none';
            document.body.style.overflow = '';
        }
    });

    // Close lightbox with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && lightbox.style.display === 'flex') {
            lightbox.style.display = 'none';
            document.body.style.overflow = '';
        }
    });
});

// Add animation for portfolio items when they come into view
const portfolioObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll('.portfolio-item').forEach(item => {
    item.style.opacity = '0';
    item.style.transform = 'translateY(20px)';
    item.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
    portfolioObserver.observe(item);
});

// Smooth scrolling for anchor links with offset for fixed header 
function setupSmoothScrolling() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        // Skip if it's a dropdown toggle or external link
        if (anchor.classList.contains('dropdown-toggle') || anchor.getAttribute('href') === '#') {
            return;
        }
        
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            
            // Only process if it's a section link (not a page link)
            if (targetId.startsWith('#') && targetId.length > 1) {
                e.preventDefault();
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    const headerHeight = document.querySelector('.navbar').offsetHeight;
                    const elementPosition = targetElement.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerHeight - 20; // 20px extra spacing
                    
                    // Smooth scroll to the target
                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                    
                    // Update URL without adding to history
                    if (history.pushState) {
                        history.pushState(null, null, targetId);
                    } else {
                        window.location.hash = targetId;
                    }
                    
                    // Close mobile menu if open
                    const navLinks = document.querySelector('.nav-links');
                    const menuToggle = document.querySelector('.mobile-menu-toggle');
                    if (navLinks && navLinks.classList.contains('active')) {
                        navLinks.classList.remove('active');
                        menuToggle.setAttribute('aria-expanded', 'false');
                        
                        // Close any open dropdowns
                        document.querySelectorAll('.dropdown-menu').forEach(menu => {
                            menu.classList.remove('show');
                        });
                        document.querySelectorAll('.dropdown-toggle i').forEach(icon => {
                            icon.classList.remove('fa-chevron-up');
                            icon.classList.add('fa-chevron-down');
                        });
                    }
                }
            }
        });
    });
    
    // Handle initial scroll position if there's a hash in the URL
    window.addEventListener('load', function() {
        if (window.location.hash) {
            const targetId = window.location.hash;
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                setTimeout(() => {
                    const headerHeight = document.querySelector('.navbar').offsetHeight;
                    const elementPosition = targetElement.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerHeight - 20;
                    
                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }, 100);
            }
        }
    });
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    loadNavigation();
    loadFooter();
    setupMobileMenu();
    setupSmoothScrolling();
});
