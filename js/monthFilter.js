document.addEventListener('DOMContentLoaded', function() {
    const monthFilter = document.getElementById('month');
    
    if (monthFilter) {
        monthFilter.addEventListener('change', function() {
            this.closest('form').submit();
        });
    }
});