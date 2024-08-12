<?php
session_start();
// Check if user is logged in and is an admin, redirect if not
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] == 'admin') {
    header('Location: ../../employee_admin_login.php');
    exit;
}

include '../../config.php';

// Fetch filter values from POST request
$startDate = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$endDate = isset($_POST['end_date']) ? $_POST['end_date'] : '';
$appointmentStatus = isset($_POST['appointment_status']) ? $_POST['appointment_status'] : '';

// Prepare SQL query for appointments with date range and optional status filter
$appointmentQuery = "SELECT * FROM appointment WHERE date BETWEEN ? AND ?" . ($appointmentStatus ? " AND status = ?" : "");
$appointmentStmt = $conn->prepare($appointmentQuery);
if ($appointmentStatus) {
    $appointmentStmt->bind_param("sss", $startDate, $endDate, $appointmentStatus);
} else {
    $appointmentStmt->bind_param("ss", $startDate, $endDate);
}
$appointmentStmt->execute();
$appointmentsResult = $appointmentStmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Appointments Report</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/admin_style.css">
    <style>
        
    </style>
</head>
<body>
    <!-- Sidebar navigation -->
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
        <!-- Navigation bar for reports -->
        <div class="navbar">
            <a href="report_appointments.php" class="active">Appointments Report</a>
            <a href="report_jobs.php">Jobs Report</a>
            <a href="report_payments.php">Payments Report</a>
        </div>

        <!-- Filter form for appointments -->
        <div class="filter-section">
            <form method="post">
                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($startDate); ?>">
                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($endDate); ?>">
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
                    // Display appointments or "No appointments found" message
                    if ($appointmentsResult->num_rows > 0) {
                        while ($row = $appointmentsResult->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['aid']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['time']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['plateNo']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No appointments found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php
    // Close database statements and connection
    $appointmentStmt->close();
    $conn->close();
    ?>
</body>
</html>