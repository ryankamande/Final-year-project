<?php
session_start();
if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'mechanic') {
    header('Location: ../employee_admin_login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Employee Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
    <h2>Welcome, <?php echo $_SESSION['user']['name']; ?></h2>
        <nav>
            <ul>
                
                <li><a href="manage_attendance.php"><i class="fas fa-user-check"></i> Manage Attendance</a></li>
               
                <li><a href="manage_jobs.php"><i class="fas fa-briefcase"></i> Manage Jobs</a></li>
                
                <li><a href="update_vehicle_status.php"><i class="fas fa-car"></i> Update Vehicle Status</a></li>
                <li><a href="../logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </header>
   
</body>
</html>
