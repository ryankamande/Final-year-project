// Wait for the document to finish loading before executing the code
document.addEventListener('DOMContentLoaded', function() {
    // Registration form validation
    const registrationForm = document.querySelector('form[action="register_process.php"]');
    if (registrationForm) {
      // Add an event listener to the registration form's submit event
      registrationForm.addEventListener('submit', function(event) {
        // Initialize a flag to track whether the form is valid
        let valid = true;
  
        // Get the values of the form fields
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();
        const confirmPassword = document.getElementById('confirm_password').value.trim();
  
        // Check if the name field is empty
        if (!name) {
          // Display an alert message if the name field is empty
          alert('Name is required');
          // Set the valid flag to false
          valid = false;
        }
  
        // Check if the email field is empty
        if (!email) {
          // Display an alert message if the email field is empty
          alert('Email is required');
          // Set the valid flag to false
          valid = false;
        } else if (!validateEmail(email)) {
          // Display an alert message if the email format is invalid
          alert('Invalid email format');
          // Set the valid flag to false
          valid = false;
        }
  
        // Check if the password field is empty
        if (!password) {
          // Display an alert message if the password field is empty
          alert('Password is required');
          // Set the valid flag to false
          valid = false;
        } else if (password.length < 6) {
          // Display an alert message if the password is too short
          alert('Password must be at least 6 characters long');
          // Set the valid flag to false
          valid = false;
        }
  
        // Check if the confirm password field is empty
        if (!confirmPassword) {
          // Display an alert message if the confirm password field is empty
          alert('Confirm Password is required');
          // Set the valid flag to false
          valid = false;
        } else if (password !== confirmPassword) {
          // Display an alert message if the passwords do not match
          alert('Passwords do not match');
          // Set the valid flag to false
          valid = false;
        }
  
        // If the form is not valid, prevent the default submit behavior
        if (!valid) {
          event.preventDefault();
        }
      });
    }
  
    // Login form validation
    const loginForm = document.querySelector('form[action="login_process.php"]');
    if (loginForm) {
      // Add an event listener to the login form's submit event
      loginForm.addEventListener('submit', function(event) {
        // Initialize a flag to track whether the form is valid
        let valid = true;
  
        // Get the values of the form fields
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();
  
        // Check if the email field is empty
        if (!email) {
          // Display an alert message if the email field is empty
          alert('Email is required');
          // Set the valid flag to false
          valid = false;
        } else if (!validateEmail(email)) {
          // Display an alert message if the email format is invalid
          alert('Invalid email format');
          // Set the valid flag to false
          valid = false;
        }
  
        // Check if the password field is empty
        if (!password) {
          // Display an alert message if the password field is empty
          alert('Password is required');
          // Set the valid flag to false
          valid = false;
        }
  
        // If the form is not valid, prevent the default submit behavior
        if (!valid) {
          event.preventDefault();
        }
      });
    }
  
    // Email validation function
    function validateEmail(email) {
      // Regular expression to match a valid email format
      const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      // Return true if the email matches the regular expression, false otherwise
      return re.test(email);
    }
  });