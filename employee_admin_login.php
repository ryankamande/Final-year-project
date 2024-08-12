<?php
// Include configuration and utility files
include 'config.php';  // Database configuration file
include 'utils.php';   // Utility file for functions like sanitizing input

// Check if the request method is POST, indicating that the login form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize user inputs to prevent SQL injection or XSS attacks
    $username = Utils::sanitizeInput($_POST['username']); // Sanitize the username input
    $password = Utils::sanitizeInput($_POST['password']); // Sanitize the password input
    $role = Utils::sanitizeInput($_POST['role']);         // Sanitize the role input (Admin or Mechanic)

    // Prepare an SQL statement to select the user from the employee table based on username and role
    $stmt = $conn->prepare("SELECT * FROM employee WHERE username = ? AND type = ?");
    $stmt->bind_param("ss", $username, $role); // Bind the sanitized username and role to the SQL statement
    $stmt->execute(); // Execute the prepared statement
    $result = $stmt->get_result(); // Get the result set from the executed statement

    // Check if a user with the given username and role exists
    if ($result->num_rows == 1) {
        // Fetch the user's data
        $user = $result->fetch_assoc();

        // Verify the password against the hashed password stored in the database
        if (password_verify($password, $user['password'])) {
            // Start a new session or resume the existing session
            session_start();

            // Store user data and role in the session
            $_SESSION['user'] = $user;
            $_SESSION['user']['role'] = $role;

            // Redirect the user to the appropriate dashboard based on their role
            if ($role == 'Admin') {
                header('Location: admin/admin_dashboard.php'); // Redirect to the Admin dashboard
            } else if ($role == 'Mechanic') {
                header('Location: employee/employee_dashboard.php'); // Redirect to the Mechanic dashboard
            }
        } else {
            // If the password is incorrect, display an error message
            echo "Invalid password.";
        }
    } else {
        // If no user is found with the provided username and role, display an error message
        echo "No user found with this username and role.";
    }

    // Close the prepared statement to free up resources
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> <!-- Character encoding for the document -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Ensures the page is responsive on all devices -->
    <title>Employee/Admin Login</title> <!-- Title of the web page -->
    <link rel="stylesheet" href="assets/css/register_style.css"> 
</head>
<body>
    <div class="container"> <!-- Main container for the login form -->
        <div class="header">
            <h1>Employee/Admin Login</h1> <!-- Header of the login page -->
        </div>
        <div class="content">
            <!-- Login form for employees/admins -->
            <form action="employee_admin_login.php" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" > <!-- Input field for the username -->
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" > <!-- Input field for the password -->
                </div>
                <div class="form-group">
                    <label for="role">User Role</label>
                    <select id="role" name="role" > <!-- Dropdown to select the user role -->
                        <option value="Admin">Admin</option> <!-- Option for Admin role -->
                        <option value="Mechanic">Mechanic</option> <!-- Option for Mechanic role -->
                    </select>
                </div>
                <button type="submit" class="button">Login</button> <!-- Submit button for the login form -->
            </form>
            <ul class="inline-links">
                <li>
                    <span>Back to <a class="link" href="index.php">Home</a></span> <!-- Link to navigate back to the home page -->
                </li>
            </ul>
        </div>
        <div class="footer">
            <p>&copy; 2024 HMS Garage Management System. All rights reserved.</p> <!-- Footer with copyright notice -->
        </div>
    </div>
</body>
</html>
