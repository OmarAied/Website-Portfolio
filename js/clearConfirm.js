// clearConfirm.js
document.addEventListener('DOMContentLoaded', function() {
    const clearButton = document.querySelector('button[type="reset"]');
    const textarea = document.getElementById('blog');
    const nameInput = document.querySelector('input[name="name"]');
    
    if (clearButton) {
        clearButton.addEventListener('click', function(e) {
            if (nameInput.value.trim() !== '' || textarea.value.trim() !== '') {
                e.preventDefault();
                
                const confirmation = confirm("Are you sure you want to clear your blog post?");
                
                if (confirmation) {
                    nameInput.value = '';
                    textarea.value = '';
                    
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