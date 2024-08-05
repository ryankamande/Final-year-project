<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] == 'mechanic') {
    header('Location: ../login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Employee Dashboard</title>
    <link rel="stylesheet" type="text/css" href="assets/css/employee_style.css"> <!-- Custom CSS for employee dashboard -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <h2>Employee Dashboard</h2>
        <nav>
            <ul>
                <li><a href="manage_attendance.php"><i class="fas fa-user-check"></i> Manage Attendance</a></li>
                <li><a href="manage_jobs.php"><i class="fas fa-briefcase"></i> View Jobs</a></li>
                <li><a href="update_job.php"><i class="fas fa-car"></i> Update Job</a></li>
                <li><a href="../logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </div>
    <div class="main-content">
        <header>
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user']['name']); ?></h2>
        </header>
        <!-- Add content specific to the employee dashboard here -->
    </div>
</body>
</html>
