<?php
// Start a new session or resume the existing one
session_start();

// Check if the user is not logged in or if the user's role is not 'admin'.
// If the user is not logged in or their role is not 'admin', redirect them to the employee/admin login page and exit the script.
if (!isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin') {
    header('Location: ../employee_admin_login.php');
    exit;
}

// Include the database configuration file to establish a connection to the database
include '../config.php';

// Include any utility functions that might be needed
include '../utils.php';

// Initialize an empty array to hold job data
$jobs = [];

// Define a query to fetch all records from the 'job' table in the database
$query = "SELECT * FROM job";

// Execute the query and store the result set in $result
$result = $conn->query($query);

// Check if the result set contains any rows (jobs)
if ($result->num_rows > 0) {
    // If there are rows, fetch each row and add it to the $jobs array
    while ($row = $result->fetch_assoc()) {
        $jobs[] = $row;
    }
}

// Close the database connection after fetching the data
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <!-- Set the title of the page -->
    <title>Manage Jobs</title>

    <!-- Link to the external CSS file for styling -->
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>
<body>
    <!-- Sidebar section with navigation links for the admin dashboard -->
    <div class="sidebar">
        <h2>Admin Dashboard</h2>
        <nav>
            <ul>
                <!-- Links to various admin management pages -->
                <li><a href="admin_dashboard.php"> Dashboard</a></li>
                <li><a href="manage_appointments.php">Manage Appointments</a></li>
                <li><a href="assign_to.php">Assign Mechanic</a></li>
                <li><a href="manage_jobs.php">Manage Jobs</a></li>
                <li><a href="reports.php">Reports</a></li>
                <li><a href="../logout.php" class="logout">Logout</a></li>
            </ul>
        </nav>
    </div>

    <!-- Header section of the page -->
    <header>
        <!-- Page title -->
        <h1>Manage Jobs</h1>

        <!-- Additional navigation links, if necessary -->
        <nav>
            <ul>
                <!-- Link to the employee dashboard and logout -->
                <li><a href="employee_dashboard.php">Dashboard</a></li>
                <li><a href="../logout.php" class="logout">Logout</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main container for displaying the job data -->
    <div class="container">
        <h2>Jobs</h2>

        <!-- Table for displaying the list of jobs -->
        <table>
            <!-- Table header defining the column names -->
            <thead>
                <tr>
                    <th>Job ID</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                </tr>
            </thead>
            
            <!-- Table body containing the data from the jobs table -->
            <tbody>
                <?php if (!empty($jobs)): ?>
                    <!-- Loop through each job and create a table row -->
                    <?php foreach ($jobs as $job): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($job['jobId']); ?></td> <!-- Display job ID -->
                            <td><?php echo htmlspecialchars($job['description']); ?></td> <!-- Display job description -->
                            <td><?php echo htmlspecialchars($job['status']); ?></td> <!-- Display job status -->
                            <td><?php echo htmlspecialchars($job['assigned_to']); ?></td> <!-- Display the ID of the mechanic assigned to the job -->
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- If no jobs are found, display a message in a single table row -->
                    <tr>
                        <td colspan="4">No jobs found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
