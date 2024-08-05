<?php
session_start();
if (!isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
    header('Location: ../employee_admin_login.php');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
    <h2>Welcome, <?php echo $_SESSION['user']['name']; ?></h2>
        <nav>
            <ul>
                <li><a href="manage_employees.php"><i class="fas fa-users-cog"></i> Manage Employees</a></li>
                <li><a href="manage_appointments.php"><i class="fas fa-calendar"></i>Manage Appointments</a></li>
                <li><a href="manage_customer.php"><i class="fas fa-users-cog"></i>Manage Customer</a></li>
                <li><a href="manage_stock.php"><i class="fas fa-shelves"></i>Manage Stock</a></li>
                <li><a href="reports.php"><i class="fas fa-chart-line"></i> Generate Reports</a></li>
                <li><a href="logs.php"><i class="fas fa-cogs"></i>Logs</a></li>
                <li><a href="admin_system_settings.php"><i class="fas fa-cogs"></i>System settings</a></li>
                <li><a href="../logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </header>
    
</body>
</html>
