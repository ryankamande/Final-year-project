<?php
// Include the configuration file for database connection and the utilities file for helper functions
include 'config.php';
include 'utils.php';

// Check if the request method is POST, indicating the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the email and password from the POST request
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Prepare the SQL query to select the user from the customer table where the email matches
    $sql = "SELECT * FROM customer WHERE email = '$email'";
    // Execute the SQL query and store the result
    $result = mysqli_query($conn, $sql);
    // Fetch the associative array from the result set which contains the user details
    $user = mysqli_fetch_assoc($result);
    
    // Check if the user exists and if the provided password matches the hashed password in the database
    if ($user && password_verify($password, $user['password'])) {
        // If the credentials are valid, start a session and store the user's details in the session
        $_SESSION['user'] = $user;
        // Redirect the user to the dashboard using a utility function
        Utils::redirect_to('user/dashboard.php');
    } else {
        // If the credentials are invalid, display an error message
        echo "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Login</title> <!-- Title of the login page -->
    <link rel="stylesheet" href="assets/css/login_style.css"> 
</head>
<body>
<div class="container"> <!-- Main container for the login form -->
        <div class="header">
            <h1>Customer Login</h1> <!-- Header of the login form -->
        
        <!-- Login form for customers -->
        <form action="login.php" method="post"> <!-- Form submission handled by login.php using POST method -->
            <label for="email">Email</label> <!-- Label for email input -->
            <input type="email" id="email" name="email" placeholder="user@example.com" > <!-- Input field for email -->

            <label for="password">Password</label> <!-- Label for password input -->
            <input type="password" id="password" name="password" > <!-- Input field for password -->

            <button type="submit">Login</button> <!-- Submit button for the form -->
        </form>
    </div>
</body>
</html>
