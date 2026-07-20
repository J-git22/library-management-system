// Main JavaScript for Library Management System

document.addEventListener('DOMContentLoaded', function() {
    // Auto-fade flash messages after 3.5 seconds
    const flashMessages = document.querySelectorAll('.alert-success, .alert-danger, .alert-info, .alert-warning');
    
    if (flashMessages.length > 0) {
        flashMessages.forEach(msg => {
            setTimeout(() => {
                msg.classList.add('fade-out');
                setTimeout(() => {
                    msg.remove();
                }, 500); // Wait for the CSS animation to complete before removing from DOM
            }, 3500);
        });
    }

    // Initialize tooltips if Bootstrap tooltip component is used
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    if (tooltipTriggerList.length > 0 && typeof bootstrap !== 'undefined') {
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    }
});
