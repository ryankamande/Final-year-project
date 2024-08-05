<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; // Sanitize
    $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
    $address = isset($_POST['address']) ? htmlspecialchars($_POST['address']) : '';
    $mobile = isset($_POST['mobile']) ? htmlspecialchars($_POST['mobile']) : ''; // Remove if not in database
    $plateNo = isset($_POST['plateNo']) ? htmlspecialchars($_POST['plateNo']) : '';

    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $cusid = uniqid('CUS');

    $sql = "INSERT INTO customer (name, email, address, mobile, plateNo, password, CUSID) VALUES ('$name', '$email', '$address', $mobile ,'$plateNo', '$password', '$cusid')";

    if (mysqli_query($conn, $sql)) {
        header('Location: login.php');
    } else {
        echo "Error: " . mysqli_error($conn); // More detailed error handling can be implemented here
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
                    <label for="password"><i class="fas fa-lock form-icon"></i>Password</label>
                    <input type="password" id="password" name="password"  >
                </div>
                <div class="form-group">
                    <label for="confirm_password"><i class="fas fa-lock form-icon"></i>Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password"  >
                </div>
                <button type="submit" class="button">Register</button>
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
