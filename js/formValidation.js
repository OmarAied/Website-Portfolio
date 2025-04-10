// clearConfirm.js
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const textarea = document.getElementById('blog');
    const title = document.querySelector('input[name="title"]');
    
    form.addEventListener('submit', function(e) {
        let isValid = true;
        title.classList.remove('empty');
        textarea.classList.remove('empty');

        void title.offsetWidth;
        void textarea.offsetWidth;

        if (title.value.trim() === '') {
            title.classList.add('empty');
            isValid = false;
        }
        if (textarea.value.trim() === '') {
            textarea.classList.add('empty');
            isValid = false;
        }

        if(!isValid) {
            e.preventDefault();
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert error';
            alertDiv.textContent = 'Please fill in all fields';
            
            document.body.insertBefore(alertDiv, document.body.firstChild);
            setTimeout(() => alertDiv.classList.add('show'), 100);
            setTimeout(() => {
                alertDiv.classList.remove('show');
                alertDiv.classList.add('hide');
                setTimeout(() => alertDiv.remove(), 300);
            }, 3000);

        }
    });

    title.addEventListener('input', function() {
        if (this.value.trim() !== '') {
            this.classList.remove('empty');
        }
    });
    
    textarea.addEventListener('input', function() {
        if (this.value.trim() !== '') {
            this.classList.remove('empty');
        }
    });
    
    
});