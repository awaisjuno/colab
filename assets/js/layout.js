// Mobile dropdown functionality
const hamburger = document.getElementById('hamburger');
const mobileDropdown = document.getElementById('mobileDropdown');

hamburger.addEventListener('click', function() {
    mobileDropdown.classList.toggle('open');
    this.innerHTML = mobileDropdown.classList.contains('open') ? '✕' : '☰';
});

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('.hamburger') && !event.target.closest('.mobile-dropdown')) {
        mobileDropdown.classList.remove('open');
        hamburger.innerHTML = '☰';
    }
});

// Header scroll effect
window.addEventListener('scroll', function() {
    const header = document.getElementById('header');
    if (window.scrollY > 50) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
});

// Intersection Observer for animations
const animateOnScroll = function() {
    const featureCards = document.querySelectorAll('.feature-card');
    const testimonialCard = document.querySelector('.testimonial-card');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    featureCards.forEach(card => {
        observer.observe(card);
    });

    // Testimonial card animation
    const testimonialObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                testimonialObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    testimonialObserver.observe(testimonialCard);
};

// Initialize animations when DOM is loaded
document.addEventListener('DOMContentLoaded', animateOnScroll);