// Intersection Observer for scroll animations
function setupScrollAnimations() {
    const animateElements = document.querySelectorAll('.animate-on-scroll');
    
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Add the appropriate animation class based on data attributes
                    const animationType = entry.target.dataset.animation || 'fade-in';
                    entry.target.classList.add(`animate-${animationType}`);
                    
                    // Unobserve after animation completes
                    setTimeout(() => {
                        observer.unobserve(entry.target);
                    }, 1000);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        // Observe all elements with the animate-on-scroll class
        animateElements.forEach(element => {
            observer.observe(element);
        });
    } else {
        // Fallback for browsers that don't support IntersectionObserver
        animateElements.forEach(element => {
            const animationType = element.dataset.animation || 'fade-in';
            element.classList.add(`animate-${animationType}`);
        });
    }
}

// Add hover effect to cards
function setupHoverEffects() {
    const cards = document.querySelectorAll('.card, .event-card, .resource-card, .news-card, .portfolio-item');
    cards.forEach(card => {
        card.classList.add('hover-lift');
    });
}

// Global animation initialization
function initAnimations() {
    setupScrollAnimations();
    setupHoverEffects();
    
    // Add hover-lift class to all cards
    const cards = document.querySelectorAll('.card, .event-card, .resource-card, .news-card, .portfolio-item, .ministry-card');
    cards.forEach(card => {
        if (!card.classList.contains('hover-lift')) {
            card.classList.add('hover-lift');
        }
    });
}

// Initialize animations when the DOM is loaded
document.addEventListener('DOMContentLoaded', initAnimations);

// Export functions if using modules
if (typeof module !== 'undefined' && typeof module.exports !== 'undefined') {
    module.exports = {
        setupScrollAnimations,
        setupHoverEffects,
        initAnimations
    };
}
