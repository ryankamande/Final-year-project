<?php
session_start();
if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'mechanic') {
    header('Location: ../employee_admin_login.php');
    exit;
}

include '../config.php';
include '../functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $eid = $_SESSION['user']['eid'];
    $date = $_POST['date'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO attendence (eid, date, status) VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE status = VALUES(status)");
    $stmt->bind_param("iss", $eid, $date, $status);

    if ($stmt->execute()) {
        echo "Attendance recorded successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch attendance records for the logged-in employee
$attendanceRecords = [];
$eid = $_SESSION['user']['eid'];
$query = "SELECT * FROM attendence WHERE eid = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $eid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $attendanceRecords[] = $row;
    }
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Attendance</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <h2>Employee Dashboard</h2>
        <nav>
            <ul>
                <li><a href="employee_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="manage_attendance.php"><i class="fas fa-user-check"></i> Manage Attendance</a></li>
                <li><a href="manage_jobs.php"><i class="fas fa-briefcase"></i> View Jobs</a></li>
                <li><a href="update_job.php"><i class="fas fa-car"></i> Update Job</a></li>
                <li><a href="../logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </div>
    <div class="main-content">
        <header>
            <h1>Manage Attendance</h1>
        </header>
        <div class="container">
            <h2>Record Attendance</h2>
            <form method="post" action="manage_attendance.php">
                <label for="date"><i class="fas fa-calendar-alt"></i> Date:</label>
                <input type="date" id="date" name="date" required>
                
                <label for="status"><i class="fas fa-check-circle"></i> Status:</label>
                <select id="status" name="status" required>
                    <option value="Present">Present</option>
                    <option value="Absent">Absent</option>
                    <option value="Leave">Leave</option>
                </select>
                
                <button type="submit"><i class="fas fa-save"></i> Save Attendance</button>
            </form>
            <h2>Attendance Records</h2>
            <table>
                <thead>
                    <tr>
                        <th>Date <i class="fas fa-calendar-alt"></i></th>
                        <th>Status <i class="fas fa-check-circle"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($attendanceRecords)): ?>
                        <?php foreach ($attendanceRecords as $record): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($record['date']); ?></td>
                                <td><?php echo htmlspecialchars($record['status']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2">No attendance records found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
