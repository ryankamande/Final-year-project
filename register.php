<?php
// This line includes the config.php file, which is assumed to contain database connection settings. 
// It ensures that the $conn variable is available for database operations.
require 'config.php';


// This condition checks if the request method is POST, meaning that the form has been submitted.
if (isset($_POST["register-btn"])){
    
    // Sanitizing User Input
    // htmlspecialchars: Converts special characters to HTML entities to prevent XSS attacks and ensures that user input is properly formatted. 
    // This sanitizes input data before inserting it into the database.
    // isset($_POST['field']) ? ... : '': This checks if the form field is set; if not, it defaults to an empty string.
    $name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; 
    $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
    $address = isset($_POST['address']) ? htmlspecialchars($_POST['address']) : '';
    $mobile = isset($_POST['mobile']) ? htmlspecialchars($_POST['mobile']) : ''; 
    $plateNo = isset($_POST['plateNo']) ? htmlspecialchars($_POST['plateNo']) : '';
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); 

    
    // // Hashes the password using the BCRYPT algorithm. This provides a secure way to store passwords in the database by making them unreadable and difficult to reverse-engineer.
    
    $sql = "INSERT INTO customer (name, email, address, mobile, plateNo, password) VALUES ('$name', '$email', '$address', $mobile ,'$plateNo', '$password')";

    if (mysqli_query($conn, $sql)) {
        header('Location: login.php');
    } else {
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
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Register</h1>
        </div>
        <div class="content">
            <form action="register.php" method="post">
                <div class="form-group">
                    <label for="name"><i class="fas fa-user form-icon"></i>Name</label>
                    <input type="text" id="name" name="name"  >
                </div>
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope form-icon"></i>Email</label>
                    <input type="email" id="email" name="email" placeholder="example@gmail.com" >
                </div>
                <div class="form-group">
                    <label for="telephone"><i class="fas fa-mobile-alt"></i> Mobile</label>
                    <input type="tel" id="mobile" name="mobile">
                </div>
                <div class="form-group">
                    <label for="address"><i class="class fas fa envelope"></i>Address</label>
                    <input type="text" id="address" name="address">
                </div>
                <div class="form group">
                    <label for="plateNo"><i class="class fas fa motorvehicle"></i>PlateNo</label>
                    <input type="text" id="plateNo" name="plateNo">
                </div>
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock form-icon"></i>Password</label>
                    <input type="password" id="password" name="password"  >
                </div>
                <div class="form-group">
                    <label for="confirm_password"><i class="fas fa-lock form-icon"></i>Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password"  >
                </div>
                <button type="submit" name="register-btn" class="button">Register</button>
            </form>
            <ul class="inline-links">
			<li class="inline-links-item">
				<span>Already got an account? &#160&#160&#160<a class="link" href="login.php">Sign in</a></span>
			</li>
            </ul>
        </div>
        <div class="footer">
            <p>&copy; 2024 Garage Management System. All rights reserved.</p>
        </div>
    </div>
    <script src="assets/js/validate.js"></script>
</body>
</html>
