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
$appointmentStatus = isset($_POST['appointment_status']) ? $_POST['appointment_status'] : '';

// Prepare SQL query for appointments with date range
// Add status filter if appointmentStatus is set
$appointmentQuery = "SELECT * FROM appointment WHERE date BETWEEN ? AND ?" . ($appointmentStatus ? " AND status = ?" : "");

// Prepare the SQL statement
$appointmentStmt = $conn->prepare($appointmentQuery);

// Bind parameters to the prepared statement
if ($appointmentStatus) {
    // If status filter is applied, bind three parameters
    $appointmentStmt->bind_param("sss", $startDate, $endDate, $appointmentStatus);
} else {
    // If no status filter, bind only date range parameters
    $appointmentStmt->bind_param("ss", $startDate, $endDate);
}

// Execute the prepared statement
$appointmentStmt->execute();

// Get the result set from the executed statement
$appointmentsResult = $appointmentStmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Appointments Report</title>
    <!-- Link to external CSS file for admin styles -->
    <link rel="stylesheet" type="text/css" href="../assets/css/admin_style.css">
    <style>
        
        
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
            <a href="report_appointments.php" class="active">Appointments Report</a>
            <a href="report_jobs.php">Jobs Report</a>
            <a href="report_payments.php">Payments Report</a>
        </div>

        <!-- Filter form for appointments -->
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
                    <option value="completed" <?php echo $appointmentStatus == 'completed' ? 'selected' : ''; ?>>Completed</option>
                    <option value="rescheduled" <?php echo $appointmentStatus == 'rescheduled' ? 'selected' : ''; ?>>Rescheduled</option>
                    <option value="canceled" <?php echo $appointmentStatus == 'canceled' ? 'selected' : ''; ?>>Canceled</option>
                </select>
                <button type="submit"> Apply Filters</button>
            </form>
        </div>

        <!-- Appointments report table -->
        <div class="report-section">
            <h2>Appointments Report</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Plate Number</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Check if there are any appointments
                    if ($appointmentsResult->num_rows > 0) {
                        // Loop through each appointment and display its details
                        while ($row = $appointmentsResult->fetch_assoc()) {
                            echo "<tr>";
                            // Output each field, using htmlspecialchars to prevent XSS attacks
                            echo "<td>" . htmlspecialchars($row['aid']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['time']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['plateNo']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        // If no appointments found, display a message
                        echo "<tr><td colspan='5'>No appointments found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php
    // Close the prepared statement
    $appointmentStmt->close();
    // Close the database connection
    $conn->close();
    ?>
</body>
</html>