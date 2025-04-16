document.addEventListener('DOMContentLoaded', function() {
    // Handle post deletion confirmations
    const blogDeleteButton = document.querySelectorAll('.delete-post button.delete-btn');
    
    blogDeleteButton.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const confirmation = confirm("Are you sure you want to delete this post?");
            
            if (confirmation) {
                this.closest('form').submit();
            }
        });
    });

    // Handle comment deletion confirmations
    const commentDeleteButton = document.querySelectorAll('.delete-comment button.delete-btn');
    
    commentDeleteButton.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const confirmation = confirm("Are you sure you want to delete this comment?");
            
            if (confirmation) {
                this.closest('form').submit();
            }
        });
    });
});