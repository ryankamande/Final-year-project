<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] == 'admin') {
    header('Location: ../employee_admin_login.php');
    exit;
}

include '../config.php'; // Include your database connection
include '../utils.php';

// counting appointments according to the current date
$totalAppointmentsTodayQuery = "SELECT COUNT(*) AS total_appointments_today FROM appointment WHERE DATE(date) = CURDATE()";
$totalAppointmentsTodayResult = $conn->query($totalAppointmentsTodayQuery)->fetch_assoc()['total_appointments_today'];

// counting customers 
$totalUsersQuery = "SELECT COUNT(*) AS total_users FROM customer";
$totalUsersResult = $conn->query($totalUsersQuery)->fetch_assoc()['total_users'];

$pendingJobsQuery = "SELECT COUNT(*) AS pending_jobs FROM job WHERE status = 'pending'";
$pendingJobsResult = $conn->query($pendingJobsQuery)->fetch_assoc()['pending_jobs'];

$completedTasksQuery = "SELECT COUNT(*) AS completed_tasks FROM job WHERE status = 'completed'";
$completedTasksResult = $conn->query($completedTasksQuery)->fetch_assoc()['completed_tasks'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="assets/css/admin_style.css">
</head>
<body>
    <div class="sidebar">
        <h2>Admin Dashboard</h2>
        <nav>
            <ul>
                <li><a href="admin_dashboard.php"> Dashboard</a></li>
                <li><a href="manage_appointments.php">Manage Appointments</a></li>
                <li><a href="assign_to.php">Assign Mechanic</a></li>
                <li><a href="manage_jobs.php"> Manage Jobs</a></li>
                <li><a href="send_invoice.php">Billing</a></li>
                <li><a href="reports.php">Reports</a></li>
                <li><a href="../logout.php" class="logout"> Logout</a></li>
            </ul>
        </nav>
    </div>
    <div class="main-content">
        <header>
            <h2>Welcome, <?php echo $_SESSION['user']['name']; ?></h2>
        </header>
        <div class="dashboard-cards">
            <div class="card">
                <div class="card-icon">
                </div>
                <div class="card-details">
                    <h3>Total Appointments Today</h3>
                    <p><?php echo $totalAppointmentsTodayResult; ?></p>
                </div>
            </div>
            <div class="card">
                <div class="card-icon">
                </div>
                <div class="card-details">
                    <h3>Total Users</h3>
                    <p><?php echo $totalUsersResult; ?></p>
                </div>
            </div>
            <div class="card">
                <div class="card-icon">
                </div>
                <div class="card-details">
                    <h3>Pending Jobs</h3>
                    <p><?php echo $pendingJobsResult; ?></p>
                </div>
            </div>
            <div class="card">
                <div class="card-icon">
                </div>
                <div class="card-details">
                    <h3>Completed Jobs</h3>
                    <p><?php echo $completedTasksResult; ?></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php
$conn->close();
?>
