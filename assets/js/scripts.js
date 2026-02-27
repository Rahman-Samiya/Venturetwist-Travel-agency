// Mobile Menu Toggle
const hamburger = document.querySelector('.hamburger');
const navLinks = document.querySelector('.nav-links');

hamburger.addEventListener('click', () => {
    navLinks.classList.toggle('active');
    hamburger.innerHTML = navLinks.classList.contains('active')
        ? '<i class="fas fa-times"></i>'
        : '<i class="fas fa-bars"></i>';
});

// Dropdown Toggle for Mobile
const dropdowns = document.querySelectorAll('.dropdown');

dropdowns.forEach(dropdown => {
    const button = dropdown.querySelector('.dropdown-btn');

    button.addEventListener('click', () => {
        if (window.innerWidth <= 992) {
            dropdown.classList.toggle('active');
        }
    });
});

// Close menu when clicking outside on mobile
document.addEventListener('click', (e) => {
    if (window.innerWidth <= 992 &&
        !e.target.closest('.navbar') &&
        navLinks.classList.contains('active')) {
        navLinks.classList.remove('active');
        hamburger.innerHTML = '<i class="fas fa-bars"></i>';
    }
});