<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] == 'admin') {
    header('Location: ../login.php');
    exit;
}

include '../config.php'; // Include your database connection
include '../functions.php';

// Fetch key elements
$totalAppointmentsTodayQuery = "SELECT COUNT(*) AS total_appointments_today FROM appointment WHERE DATE(date) = CURDATE()";
$totalAppointmentsTodayResult = $conn->query($totalAppointmentsTodayQuery)->fetch_assoc()['total_appointments_today'];

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <h2>Admin Dashboard</h2>
        <nav>
            <ul>
                <li><a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="manage_appointments.php"><i class="fas fa-calendar-alt"></i>Manage Appointments</a></li>
                <li><a href="assign_to.php"><i class="fa fa-briefcase"></i>Assign Mechanic</a></li>
                <li><a href="manage_jobs.php"><i class="fas fa-briefcase"></i> Manage Jobs</a></li>
                <li><a href="process_payments.php"><i class="fas fa-dollar-sign">Manage Payments</i></a></li>
                <li><a href="send_invoice.php"><i class="fas fa-tasks"></i>Billing</a></li>
                <li><a href="reports.php"><i class="fas fa-file-alt"></i> Reports</a></li>
                <li><a href="../logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
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
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="card-details">
                    <h3>Total Appointments Today</h3>
                    <p><?php echo $totalAppointmentsTodayResult; ?></p>
                </div>
            </div>
            <div class="card">
                <div class="card-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="card-details">
                    <h3>Total Users</h3>
                    <p><?php echo $totalUsersResult; ?></p>
                </div>
            </div>
            <div class="card">
                <div class="card-icon">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="card-details">
                    <h3>Pending Jobs</h3>
                    <p><?php echo $pendingJobsResult; ?></p>
                </div>
            </div>
            <div class="card">
                <div class="card-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="card-details">
                    <h3>Completed Tasks</h3>
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
