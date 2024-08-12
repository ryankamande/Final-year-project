<?php
// This line includes the config.php file, which is assumed to contain database connection settings. 
// It ensures that the $conn variable is available for database operations.
require 'config.php';


// This condition checks if the request method is POST, meaning that the form has been submitted.
if (isset($_POST["register-btn"])){
    
    // Sanitizing User Input
    $name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; 
    $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
    $address = isset($_POST['address']) ? htmlspecialchars($_POST['address']) : '';
    $mobile = isset($_POST['mobile']) ? htmlspecialchars($_POST['mobile']) : ''; 
    $plateNo = isset($_POST['plateNo']) ? htmlspecialchars($_POST['plateNo']) : '';
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); 

    
    // // Hashes the password using the BCRYPT algorithm. This provides a secure way to store passwords in the database by making them unreadable and difficult to reverse-engineer.
    
// Prepare the SQL statement to insert a new customer into the 'customer' table.
// The statement includes the customer's name, email, address, mobile number, plate number, and password.
$sql = "INSERT INTO customer (name, email, address, plateNo, mobile, password) VALUES ('$name', '$email', '$address', $mobile , '$plateNo', '$password')";

// Execute the SQL query using the database connection ($conn).
// The mysqli_query() function sends the SQL query to the database for execution.
if (mysqli_query($conn, $sql)) {
    // If the query executes successfully, redirect the user to the login page.
    // This typically happens after successful registration.
    header('Location: login.php');
} else {
    // If the query fails to execute, display an error message.
    // The mysqli_error() function is used to get a descriptive error message from the database.
    echo "Error: " . mysqli_error($conn); 
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="assets/css/register_style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Register</h1>
        </div>
        <div class="content">
            <form action="register.php" method="post">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name"  >
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="example@gmail.com" >
                </div>
                <div class="form-group">
                    <label for="telephone"> Mobile</label>
                    <input type="tel" id="mobile" name="mobile">
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address">
                </div>
                <div class="form-group">
                    <label for="plateNo">PlateNo</label>
                    <input type="text" id="plateNo" name="plateNo">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password"  >
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password"  >
                </div>
                <button type="submit" name="register-btn" class="button">Register</button>
            </form>
            <ul class="inline-links">
			<li class="inline-links-item">
                <!-- The &#160;&#160;&#160; represents non-breaking spaces for spacing out the text. -->
				<span>Already got an account? &#160&#160&#160<a class="link" href="login.php">Sign in</a></span>
			</li>
            </ul>
        </div>
        <div class="footer">
            <p>&copy; 2024 HMS Garage Management System. All rights reserved.</p>
        </div>
    </div>
    <script src="assets/js/validate.js"></script>
</body>
</html>
