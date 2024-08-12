<?php
include 'config.php';
include 'utils.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM customer WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
    
    if ($user && password_verify($password, $user['password'])) {
        
        $_SESSION['user'] = $user;
        // echo "TEST" . $_SESSION['user'];
        Utils::redirect_to('user/dashboard.php');
    } else {
        echo "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Login</title>
    <link rel="stylesheet"  href="assets/css/login_style.css">
    
</head>
<body>
<div class="container">
        <div class="header">
            <h1>Customer Login</h1>
        
        <form action="login.php" method="post">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="user@example.com"  >

            <label for="password">Password</label>
            <input type="password" id="password" name="password"  >

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>