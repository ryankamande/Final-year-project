document.querySelector('form').addEventListener('submit', function(event) {
    var email = document.getElementById('email').value;
    var password = document.getElementById('signupPassword').value;
    var username = document.getElementById('username').value;

    // Email validation
    if (!validateEmail(email)) {
        alert("Please enter a valid email address.");
        event.preventDefault(); // Prevent form submission
    }

    // Password validation
    if (!validatePassword(password)){
        alert("Password must be at least 8 characters long and contain at least one capital letter.");
        event.preventDefault(); // Prevent form submission
    }

    // Username validation
    if (username.trim() === "") {
        alert("Username cannot be empty.");
        event.preventDefault(); // Prevent form submission
    }

    // If any validation fails
    if (!validateEmail(email) || !validatePassword(password) || username.trim() === "") {
        alert("Registration failed. Please check the inputs and try again.");
        event.preventDefault(); // Prevent form submission
    }

    // If all validations pass
    if (validateEmail(email) && validatePassword(password) && username.trim() !== "") {
        alert("Registration successful!");
    }
});

function validateEmail(email) {
    // Check for the presence of exactly one "@" symbol
    var atSymbolCount = 0;
    for (var i = 0; i < email.length; i++) {
        if (email[i] === '@') {
            atSymbolCount++;
        }
    }
    if (atSymbolCount !== 1) {
        return false;
    }

    // Split the email into local part and domain part
    var atPosition = email.indexOf('@');
    var localPart = email.slice(0, atPosition);
    var domainPart = email.slice(atPosition + 1);

    // Ensure both parts are not empty
    if (localPart.length === 0 || domainPart.length === 0) {
        return false;
    }

    // Ensure the domain part contains a period (.)
    if (domainPart.indexOf('.') === -1) {
        return false;
    }

    // Ensure the domain doesn't start or end with a period
    if (domainPart[0] === '.' || domainPart[domainPart.length - 1] === '.') {
        return false;
    }

    // Ensure the local part contains only valid characters
    var validLocalChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789._%+-";
    for (var i = 0; i < localPart.length; i++) {
        if (validLocalChars.indexOf(localPart[i]) === -1) {
            return false;
        }
    }

    // Ensure the domain part contains only valid characters
    var validDomainChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789.-";
    for (var i = 0; i < domainPart.length; i++) {
        if (validDomainChars.indexOf(domainPart[i]) === -1) {
            return false;
        }
    }

    // All checks passed
    return true;
}
function validatePassword(password) {
    // Check if the password is at least 8 characters long
    if (password.length < 8) {
        return false;
    }

    // Check if the password contains at least one uppercase letter
    var hasUppercase = false;
    for (var i = 0; i < password.length; i++) {
        if (password[i] >= 'A' && password[i] <= 'Z') {
            hasUppercase = true;
            break;
        }
    }

    // If it has an uppercase letter, return true
    if (hasUppercase) {
        return true;
    } else {
        return false;
    }
}