document.addEventListener('DOMContentLoaded', function() {
    const clearButton = document.querySelector('button[type="reset"]');
    const textarea = document.getElementById('blog');
    const title = document.querySelector('input[name="title"]');
    
    if (clearButton) {
        clearButton.addEventListener('click', function(e) {
            if (title.value.trim() !== '' || textarea.value.trim() !== '') {
                e.preventDefault();
                
                const confirmation = confirm("Are you sure you want to clear your blog post?");
                
                if (confirmation) {
                    title.value = '';
                    textarea.value = '';
                    title.classList.add('empty');
                    textarea.classList.add('empty');
                    
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert success';
                    alertDiv.textContent = 'Form cleared!';
                    
                    document.body.insertBefore(alertDiv, document.body.firstChild);
                    setTimeout(() => {
                        alertDiv.classList.add('show');
                    }, 100);

                    setTimeout(() => {
                        alertDiv.classList.remove('show');
                        alertDiv.classList.add('hide');
                        
                        setTimeout(() => {
                            alertDiv.remove();
                        }, 300);
                    }, 3000);
                }
            }
        });
    }
});