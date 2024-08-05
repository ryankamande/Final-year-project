<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <h2>User Dashboard</h2>
        <nav>
            <ul>
                <li><a href="create_appointment.php"><i class="fas fa-plus"></i> Create Appointment</a></li>
                <li><a href="view_appointment.php"><i class="fas fa-calendar-check"></i> View Appointments</a></li>
                <li><a href="payments.php"><i class="fas fa-credit-card"></i> View Payment History</a></li>
                <li><a href="pay_invoice.php"><i class="fas fa-credit-card"></i>Pay</a></li>
                <li><a href="update_appointment.php"><i class="fas fa-user-edit"></i> Update Details</a></li>
                <li><a href="delete_appointment.php"><i class="fas fa-trash"></i> Delete Appointments</a></li>
                <li><a href="../logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </div>
    <div class="main-content">
        <header>
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user']['name']); ?></h1>
        </header>
    </div>
</body>
</html>
