<!-- dashboard.php -->
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
    <h2>Welcome, <?php echo $_SESSION['user']['name']; ?></h2>
    <nav>
        <ul>
                <li><a href="create_appointment.php"><i class=""></i>Create Appointment</a></li>
                <li><a href="view_appointment.php"><i class="fas fa-calendar-check"></i> View Appointments</a></li>
                <li><a href="view_vehicle_status.php"><i class="fas fa-car"></i> View Vehicle Status</a></li>
                <li><a href="payments.php"><i class="fas fa-credit-card"></i> Make Payment</a></li>
                <li><a href="update_appointment.php"><i class="fas fa-user-edit"></i> Update Details</a></li>
                <li><a href="delete_appointment.php"><i class=""></i>Delete Appointments</a></li>
                <li><a href="../logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
    </nav>
     
</body>
</html>
