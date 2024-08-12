<?php
session_start();
if (!isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin') {
    header('Location: ../employee_admin_login.php');
    exit;
}

include '../config.php'; 
include '../utils.php';

// Fetch job data from the database
$jobs = [];
$query = "SELECT * FROM job"; // Adjust the table name as necessary
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $jobs[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Jobs</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
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
                <li><a href="reports.php"> Reports</a></li>
                <li><a href="../logout.php" class="logout"> Logout</a></li>
            </ul>
        </nav>
    </div>
    <header>
        <h1>Manage Jobs</h1>
        <nav>
            <ul>
                <li><a href="employee_dashboard.php"> Dashboard</a></li>
                <li><a href="../logout.php" class="logout"> Logout</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <h2>Jobs</h2>
        <table>
            <thead>
                <tr>
                    <th>Job ID </th>
                    <th>Description </th>
                    <th>Status </th>
                    <th>Assigned To </th>
                    
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($jobs)): ?>
                    <?php foreach ($jobs as $job): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($job['jobId']); ?></td>
                            <td><?php echo htmlspecialchars($job['description']); ?></td>
                            <td><?php echo htmlspecialchars($job['status']); ?></td>
                            <td><?php echo htmlspecialchars($job['assigned_to']); ?></td>
                            
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No jobs found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
