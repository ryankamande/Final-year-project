<?php
// Start a session or resume the current one
session_start();

// Check if the user session is not set or if the user's role is 'admin'.
// If true, redirect them to the employee/admin login page and exit the script.
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] == 'admin') {
    header('Location: ../employee_admin_login.php');
    exit;
}

// Include the database configuration file to establish a connection
include '../config.php';

// Include any utility functions that might be needed
include '../utils.php';

// Query to count the number of appointments scheduled for today
$totalAppointmentsTodayQuery = "SELECT COUNT(*) AS total_appointments_today FROM appointment WHERE DATE(date) = CURDATE()";

// Execute the query and fetch the result (number of appointments for today)
$totalAppointmentsTodayResult = $conn->query($totalAppointmentsTodayQuery)->fetch_assoc()['total_appointments_today'];

// Query to count the total number of customers in the system
$totalUsersQuery = "SELECT COUNT(*) AS total_users FROM customer";

// Execute the query and fetch the result (total number of customers)
$totalUsersResult = $conn->query($totalUsersQuery)->fetch_assoc()['total_users'];

// Query to count the number of jobs that have a status of 'pending'
$pendingJobsQuery = "SELECT COUNT(*) AS pending_jobs FROM job WHERE status = 'pending'";

// Execute the query and fetch the result (number of pending jobs)
$pendingJobsResult = $conn->query($pendingJobsQuery)->fetch_assoc()['pending_jobs'];

// Query to count the number of jobs that have been completed
$completedTasksQuery = "SELECT COUNT(*) AS completed_tasks FROM job WHERE status = 'completed'";

// Execute the query and fetch the result (number of completed jobs)
$completedTasksResult = $conn->query($completedTasksQuery)->fetch_assoc()['completed_tasks'];
?>
<!DOCTYPE html>
<html>
<head>
    <!-- Set the title of the admin dashboard page -->
    <title>Admin Dashboard</title>
    
    <!-- Link to the external CSS file that contains the styles for the admin dashboard -->
    <link rel="stylesheet" type="text/css" href="assets/css/admin_style.css">
</head>
<body>
    <!-- Sidebar section of the admin dashboard -->
    <div class="sidebar">
        <!-- Title of the sidebar -->
        <h2>Admin Dashboard</h2>
        
        <!-- Navigation menu inside the sidebar -->
        <nav>
            <ul>
                <!-- Links to different pages within the admin dashboard -->
                <li><a href="admin_dashboard.php"> Dashboard</a></li>
                <li><a href="manage_appointments.php">Manage Appointments</a></li>
                <li><a href="assign_to.php">Assign Mechanic</a></li>
                <li><a href="manage_jobs.php"> Manage Jobs</a></li>
                <li><a href="reports.php">Reports</a></li>
                <li><a href="../logout.php" class="logout"> Logout</a></li>
            </ul>
        </nav>
    </div>
    
    <!-- Main content section of the admin dashboard -->
    <div class="main-content">
        <!-- Header section displaying a welcome message with the admin's name -->
        <header>
            <h2>Welcome, <?php echo $_SESSION['user']['name']; ?></h2>
        </header>
        
        <!-- Dashboard cards section displaying various statistics -->
        <div class="dashboard-cards">
            <!-- Card for total appointments today -->
            <div class="card">
                <div class="card-icon">
                    <!-- Optional place for an icon -->
                </div>
                <div class="card-details">
                    <h3>Total Appointments Today</h3>
                    <!-- Display the total number of appointments scheduled for today -->
                    <p><?php echo $totalAppointmentsTodayResult; ?></p>
                </div>
            </div>

            <!-- Card for total users -->
            <div class="card">
                <div class="card-icon">
                    <!-- Optional place for an icon -->
                </div>
                <div class="card-details">
                    <h3>Total Users</h3>
                    <!-- Display the total number of users (customers) -->
                    <p><?php echo $totalUsersResult; ?></p>
                </div>
            </div>

            <!-- Card for pending jobs -->
            <div class="card">
                <div class="card-icon">
                    <!-- Optional place for an icon -->
                </div>
                <div class="card-details">
                    <h3>Pending Jobs</h3>
                    <!-- Display the number of jobs that are currently pending -->
                    <p><?php echo $pendingJobsResult; ?></p>
                </div>
            </div>

            <!-- Card for completed jobs -->
            <div class="card">
                <div class="card-icon">
                    <!-- Optional place for an icon -->
                </div>
                <div class="card-details">
                    <h3>Completed Jobs</h3>
                    <!-- Display the number of jobs that have been completed -->
                    <p><?php echo $completedTasksResult; ?></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
