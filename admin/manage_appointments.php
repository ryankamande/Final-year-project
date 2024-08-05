<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] == 'admin') {
    header('Location: ../employee_admin_login.php');
    exit;
}

include '../config.php';
include '../functions.php';

// Fetch appointments from the database
$appointments = [];
$query = "SELECT * FROM appointment";
$result = $conn->query($query);

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
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <h1>View Appointments</h1>
        <nav>
            <ul>
                <li><a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="../logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <h2>Appointments</h2>
        <table>
            <thead>
                <tr>
                    <th>Appointment ID <i class="fas fa-id-badge"></i></th>
                    <th>Time <i class="fas fa-clock"></i></th>
                    <th>Date <i class="fas fa-calendar-alt"></i></th>
                    <th>Plate No <i class="fas fa-car"></i></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($appointments)): ?>
                    <?php foreach ($appointments as $appointment): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($appointment['APPID']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['time']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['date']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['plateNo']); ?></td>
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
</body>
</html>
