<?php
include 'config.php';
include 'functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM customer WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
    
    if ($user && password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['user'] = $user;
        header('Location: user/dashboard.php');
    } else {
        echo "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Login</title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <h1>Customer Login</h1>
    </header>
    <div class="container">
        <form action="login.php" method="post">
            <label for="username"><i class="fas fa-envelope"></i> Email</label>
            <input type="email" id="email" name="email" placeholder="user@example.com" required>

            <label for="password"><i class="fas fa-lock"></i> Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit"><i class="fas fa-sign-in-alt"></i> Login</button>
        </form>
    </div>
</body>
</html>
