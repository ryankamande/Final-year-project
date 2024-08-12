<?php
session_start();
if (!isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin') {
    header('Location: ../employee_admin_login.php');
    exit;
}

include '../config.php';
include '../utils.php';

// Fetch all appointments
$appointmentsQuery = "SELECT aid, time, date, plateNo FROM appointment";
$result = $conn->query($appointmentsQuery);

$appointments = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Appointments</title>
    <link rel="stylesheet" type="text/css" href="assets/css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <h2>Admin Dashboard</h2>
        <nav>
            <ul>
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="manage_appointments.php">Manage Appointments</a></li>
                <li><a href="assign_to.php">Assign Mechanic</a></li>
                <li><a href="manage_jobs.php"> Manage Jobs</a></li>
                <li><a href="send_invoice.php">Billing</a></li>
                <li><a href="reports.php"> Reports</a></li>
                <li><a href="../logout.php" class="logout"> Logout</a></li>
            </ul>
        </nav>
    </div>
    <div class="main-content">
        <header>
            <h1>View Appointments</h1>
        </header>
        <div class="container">
            <h2>Appointments</h2>
            <table>
                <thead>
                    <tr>
                        <th>Appointment ID </th>
                        <th>Time </th>
                        <th>Date </th>
                        <th>Plate No </th>
                        <th>Service Type</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($appointments)): ?>
                        <?php foreach ($appointments as $appointment): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($appointment['aid']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['time']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['date']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['plateNo']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['serviceType']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No appointments found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
