document.addEventListener('DOMContentLoaded', function() {
    // Registration form validation
    const registrationForm = document.querySelector('form[action="register_process.php"]');
    if (registrationForm) {
        registrationForm.addEventListener('submit', function(event) {
            let valid = true;
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();
            const confirmPassword = document.getElementById('confirm_password').value.trim();

            if (!name) {
                alert('Name is required');
                valid = false;
            }

            if (!email) {
                alert('Email is required');
                valid = false;
            } else if (!validateEmail(email)) {
                alert('Invalid email format');
                valid = false;
            }

            if (!password) {
                alert('Password is required');
                valid = false;
            } else if (password.length < 6) {
                alert('Password must be at least 6 characters long');
                valid = false;
            }

            if (!confirmPassword) {
                alert('Confirm Password is required');
                valid = false;
            } else if (password !== confirmPassword) {
                alert('Passwords do not match');
                valid = false;
            }

            if (!valid) {
                event.preventDefault();
            }
        });
    }

    // Login form validation
    const loginForm = document.querySelector('form[action="login_process.php"]');
    if (loginForm) {
        loginForm.addEventListener('submit', function(event) {
            let valid = true;
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();

            if (!email) {
                alert('Email is required');
                valid = false;
            } else if (!validateEmail(email)) {
                alert('Invalid email format');
                valid = false;
            }

            if (!password) {
                alert('Password is required');
                valid = false;
            }

            if (!valid) {
                event.preventDefault();
            }
        });
    }

    // Email validation function
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
});
