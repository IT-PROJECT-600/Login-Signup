document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('signup-form');
    
    form.addEventListener('submit', function(event) {
        let isValid = true;
        
        // Name validation
        const name = document.getElementById('name');
        if (name.value.trim() === '') {
            showError('name', 'Name is required');
            isValid = false;
        } else {
            clearError('name');
        }
        
        // Email validation
        const email = document.getElementById('email');
        if (!isValidEmail(email.value)) {
            showError('email', 'Please enter a valid email address');
            isValid = false;
        } else {
            clearError('email');
        }
        
        // Password validation
        const password = document.getElementById('password');
        if (password.value.length < 8) {
            showError('password', 'Password must be at least 8 characters long');
            isValid = false;
        } else if (!/[A-Z]/.test(password.value) || !/[a-z]/.test(password.value) || !/[0-9]/.test(password.value)) {
            showError('password', 'Password must contain at least one uppercase letter, one lowercase letter, and one number');
            isValid = false;
        } else {
            clearError('password');
        }
        
        // Password confirmation validation
        const passwordConfirmation = document.getElementById('password_confirmation');
        if (password.value !== passwordConfirmation.value) {
            showError('password-confirmation', 'Passwords do not match');
            isValid = false;
        } else {
            clearError('password-confirmation');
        }
        
        // Prevent form submission if validation fails
        if (!isValid) {
            event.preventDefault();
        }
    });
    
    // Helper function to validate email format
    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
    
    // Function to display error messages
    function showError(field, message) {
        const errorElement = document.getElementById(`${field}-error`);
        errorElement.textContent = message;
    }
    
    // Function to clear error messages
    function clearError(field) {
        const errorElement = document.getElementById(`${field}-error`);
        errorElement.textContent = '';
    }
});