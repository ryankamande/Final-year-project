<?php
// Start a new or resume an existing session
session_start();

// Check if user is logged in and is an admin
// If not, redirect to the login page
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] == 'admin') {
    header('Location: ../../employee_admin_login.php');
    exit;
}

// Include database configuration file
include '../../config.php';

// Fetch filter values from POST request
// If not set, default to empty string
$startDate = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$endDate = isset($_POST['end_date']) ? $_POST['end_date'] : '';
$jobStatus = isset($_POST['appointment_status']) ? $_POST['appointment_status'] : '';

// Prepare SQL query for Jobswith date range
// Add status filter if appointmentStatus is set
$jobQuery = "SELECT * FROM job WHERE date BETWEEN ? AND ?" . ($jobStatus ? " AND status = ?" : "");

// Prepare the SQL statement
$jobStmt = $conn->prepare($jobQuery);

// Bind parameters to the prepared statement
if ($jobStatus) {
    // If status filter is applied, bind three parameters
    $jobStmt->bind_param("sss", $startDate, $endDate, $jobStatus);
} else {
    // If no status filter, bind only date range parameters
    $jobStmt->bind_param("ss", $startDate, $endDate);
}

// Execute the prepared statement
$jobStmt->execute();

// Get the result set from the executed statement
$jobsResult = $jobStmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Jobs Report</title>
    <!-- Link to external CSS file for admin styles -->
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
    <!-- Sidebar navigation menu -->
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

    <!-- Main content area -->
    <div class="main-content">
        <!-- Navigation bar for different report types -->
        <div class="navbar">
            <a href="report_appointments.php">Appointment Report</a>
            <a href="report_jobs.php" class="active">Jobs Report</a>
            <a href="report_payments.php">Payments Report</a>
        </div>

        <!-- Filter form for Jobs-->
        <div class="filter-section">
            <form method="post">
                <!-- Date range inputs -->
                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($startDate); ?>">
                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($endDate); ?>">
                
                <!-- Appointment status dropdown -->
                <label for="appointment_status">Status:</label>
                <select id="appointment_status" name="appointment_status">
                    <option value="">All</option>
                    <option value="completed" <?php echo $jobStatus == 'completed' ? 'selected' : ''; ?>>Completed</option>
                    <option value="rescheduled" <?php echo $jobStatus == 'rescheduled' ? 'selected' : ''; ?>>Rescheduled</option>
                    <option value="canceled" <?php echo $jobStatus == 'canceled' ? 'selected' : ''; ?>>Canceled</option>
                </select>
                <button type="submit"> Apply Filters</button>
            </form>
        </div>

        <!-- Jobsreport table -->
        <div class="report-section">
            <h2>JobsReport</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Assigned To</th>
                        <th>Plate Number</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Check if there are any appointments
                    if ($jobsResult->num_rows > 0) {
                        // Loop through each appointment and display its details
                        while ($row = $jobsResult->fetch_assoc()) {
                            echo "<tr>";
                            // Output each field, using htmlspecialchars to prevent XSS attacks
                            echo "<td>" . htmlspecialchars($row['jobId']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['assigned_to']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        // If no Jobsfound, display a message
                        echo "<tr><td colspan='5'>No Jobsfound</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php
    // Close the prepared statement
    $jobsResult->close();
    // Close the database connection
    $conn->close();
    ?>
</body>
</html>