<?php
include 'config.php';
include 'functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitizeInput($_POST['username']);
    $password = sanitizeInput($_POST['password']);
    $role = sanitizeInput($_POST['role']);

    $stmt = $conn->prepare("SELECT * FROM employee WHERE username = ? AND type = ?");
    $stmt->bind_param("ss", $username, $role);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user'] = $user;
            $_SESSION['user']['role'] = $role;
            if ($role == 'Admin') {
                header('Location: admin/admin_dashboard.php');
            } else if ($role == 'Mechanic') {
                header('Location: employee/employee_dashboard.php');
            }
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with this username and role.";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Employee/Admin Login</title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <h1>Employee/Admin Login</h1>
    </header>
    <div class="container">
        <form action="employee_admin_login.php" method="post">
            <label for="username"><i class="fas fa-user"></i> Username</label>
            <input type="text" id="username" name="username" required>

            <label for="password"><i class="fas fa-lock"></i> Password</label>
            <input type="password" id="password" name="password" required>

            <label for="role"><i class="fas fa-user-tag"></i> User Role</label>
            <select id="role" name="role" required>
                <option value="Admin">Admin</option>
                <option value="Mechanic">Mechanic</option>
            </select>

            <button type="submit"><i class="fas fa-sign-in-alt"></i> Login</button>
        </form>
    </div>
</body>
</html>
