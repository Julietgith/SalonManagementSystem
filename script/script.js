document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.btn-primary');

    buttons.forEach(button => {
        button.addEventListener('mouseover', function() {
            this.style.backgroundColor = '#FFD700';
            this.style.color = '#ffffff';
    
        });

        button.addEventListener('mouseout', function() {
            // Revert to the original Bootstrap button styles
            this.style.backgroundColor = '';
            this.style.borderColor = '';
            this.style.color = '';
        });
    });
});