<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] == 'admin') {
    header('Location: ../../employee_admin_login.php');
    exit;
}

include '../../config.php';

// Fetch filter values
$startDate = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$endDate = isset($_POST['end_date']) ? $_POST['end_date'] : '';
$jobStatus = isset($_POST['job_status']) ? $_POST['job_status'] : '';

// Job Report
$jobQuery = "SELECT * FROM job WHERE date BETWEEN ? AND ?" . ($jobStatus ? " AND status = ?" : "");
$jobStmt = $conn->prepare($jobQuery);
if ($jobStatus) {
    $jobStmt->bind_param("sss", $startDate, $endDate, $jobStatus);
} else {
    $jobStmt->bind_param("ss", $startDate, $endDate);
}
$jobStmt->execute();
$jobsResult = $jobStmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Jobs Report</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/admin_style.css">
    <style>
        /* General body styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            background-color: #f4f4f4;
        }

        /* Sidebar styles */
        .sidebar {
            width: 250px;
            height: 100%;
            background-color: #333;
            color: #fff;
            padding: 15px;
            position: fixed; /* Fixed position to stay in place */
            top: 0;
            left: 0;
            bottom: 0;
            overflow-y: auto; /* Allows scrolling if content overflows */
        }

        /* Sidebar heading styles */
        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Sidebar navigation styles */
        .sidebar nav ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar nav ul li {
            margin: 15px 0;
        }

        .sidebar nav ul li a {
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .sidebar nav ul li a .fas {
            margin-right: 10px;
        }

        .sidebar nav ul li a.logout {
            color: #ff4b4b;
        }

        /* Main content area styles */
        .main-content {
            margin-left: 260px; /* Offset to account for the fixed sidebar */
            padding: 20px;
            width: calc(100% - 260px); /* Adjust width to fill remaining space */
            height: 100%; /* Ensure it takes the full height */
            box-sizing: border-box; /* Include padding and border in width/height calculations */
        }

        /* Header styles */
        .navbar {
            margin-bottom: 20px;
            background-color: #007bff;
            padding: 10px;
            color: white;
            text-align: center;
        }
        .navbar a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
        }
        .navbar a.active {
            text-decoration: underline;
        }
        .filter-section, .report-section {
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Admin Dashboard</h2>
        <nav>
            <ul>
                <li><a href="../admin_dashboard.php"> Dashboard</a></li>
                <li><a href="../create_appointment.php"> Create Appointment</a></li>
                <li><a href="../view_appointment.php"> View Appointments</a></li>
                <li><a href="../../logout.php" class="logout"> Logout</a></li>
            </ul>
        </nav>
    </div>

    <div class="main-content">
        <div class="navbar">
            <a href="report_appointments.php">Appointments Report</a>
            <a href="report_jobs.php" class="active">Jobs Report</a>
            <a href="report_payments.php">Payments Report</a>
        </div>

        <div class="filter-section">
            <form method="post">
                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($startDate); ?>">
                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($endDate); ?>">
                <label for="job_status">Status:</label>
                <select id="job_status" name="job_status">
                    <option value="">All</option>
                    <option value="in progress" <?php echo $jobStatus == 'in progress' ? 'selected' : ''; ?>>In Progress</option>
                    <option value="completed" <?php echo $jobStatus == 'completed' ? 'selected' : ''; ?>>Completed</option>
                    <option value="pending" <?php echo $jobStatus == 'pending' ? 'selected' : ''; ?>>Pending</option>
                </select>
                <button type="submit"> Apply Filters</button>
            </form>
        </div>

        <div class="report-section">
            <h2>Jobs Report</h2>
            <table>
                <thead>
                    <tr>
                        <th>Job ID</th>
                        <th>Description</th>
                        <th>Estimated Time</th>
                        <th>Total Time Spent</th>
                        <th>Status</th>
                        <th>Vehicle Make</th>
                        <th>Vehicle Model</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($jobsResult->num_rows > 0) {
                        while ($row = $jobsResult->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['jobId']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['timeEstimated']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['totalTimeSpent']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['vehicle_model']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['vehicle_type']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>No jobs found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php
    // Close statements and connection
    $jobStmt->close();
    $conn->close();
    ?>
</body>
</html>
