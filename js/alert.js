// alert.js
document.addEventListener('DOMContentLoaded', function() {
    const alert = document.querySelector('.alert');
    if (alert) {
        // Show the alert with slide down
        setTimeout(() => {
            alert.classList.add('show');
        }, 100);

        // Hide the alert after 5 seconds
        setTimeout(() => {
            alert.classList.remove('show');
            alert.classList.add('hide');
            
            // Remove the alert from DOM after fade out
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 3000);
    }
});