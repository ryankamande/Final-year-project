<?php
include 'config.php';
include 'utils.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username =  Utils::sanitizeInput($_POST['username']);
    $password =  Utils::sanitizeInput($_POST['password']);
    $role =  Utils::sanitizeInput($_POST['role']);
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee/Admin Login</title>
    <link rel="stylesheet" href="assets/css/register_style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Employee/Admin Login</h1>
        </div>
        <div class="content">
            <form action="employee_admin_login.php" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username"  >
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password"  >
                </div>
                <div class="form-group">
                    <label for="role">User Role</label>
                    <select id="role" name="role"  >
                        <option value="Admin">Admin</option>
                        <option value="Mechanic">Mechanic</option>
                    </select>
                </div>
                <button type="submit" class="button">Login</button>
            </form>
            <ul class="inline-links">
                <li>
                    <span>Back to <a class="link" href="index.php">Home</a></span>
                </li>
            </ul>
        </div>
        <div class="footer">
            <p>&copy; 2024 HMS Garage Management System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
