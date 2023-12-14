document.addEventListener('DOMContentLoaded', function() {
    var fadeElement = document.getElementById('fade-in-element');

    var options = {
        root: null,
        rootMargin: '0px',
        threshold: 0.5
    };

    var observer = new IntersectionObserver(function(entries, observer) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                fadeElement.classList.remove('hidden');
                fadeElement.style.opacity = 1;
                fadeElement.style.transform = 'translateY(0)';
                observer.unobserve(entry.target);
            }
        });
    }, options);

    observer.observe(fadeElement);
});