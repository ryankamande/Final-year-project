<?php
session_start();
if (!isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin') {
    header('Location: ../employee_admin_login.php');
    exit;
}

include '../config.php'; 
include '../utils.php';

// Fetch all mechanics
$mechanics = [];
$mechanicsQuery = "SELECT eid, name FROM employee WHERE type = 'mechanic'";
$result = $conn->query($mechanicsQuery);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $mechanics[] = $row;
    }
}

// Fetch all appointments
$appointments = [];
$appointmentsQuery = "SELECT * FROM appointment";
$result = $conn->query($appointmentsQuery);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }
}

// Handle job assignment
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $appointmentId = $_POST['appointment_id'];
    $mechanicId = $_POST['mechanic_id'];
    $description =  Utils::sanitizeInput($_POST['description']);

    $assignJobQuery = "INSERT INTO job (description, status, assigned_to, aid) VALUES (?, 'pending', ?, ?)";
    $stmt = $conn->prepare($assignJobQuery);
    $stmt->bind_param("sii", $description, $mechanicId, $appointmentId);

    if ($stmt->execute()) {
        echo "Job assigned successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Assign Job</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="sidebar">
    <h2>Admin Dashboard</h2>
    <nav>
            <ul>
                <li><a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="manage_appointments.php"><i class="fas fa-calendar-alt"></i>Manage Appointments</a></li>
                <li><a href="assign_to.php"><i class="fa fa-briefcase"></i>Assign Mechanic</a></li>
                <li><a href="manage_jobs.php"><i class="fas fa-briefcase"></i> Manage Jobs</a></li>
                <li><a href="send_invoice.php"><i class="fas fa-tasks"></i>Billing</a></li>
                <li><a href="reports.php"><i class="fas fa-file-alt"></i> Reports</a></li>
                <li><a href="../logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
</div>
<div class="main-content">
    <header>
        <h1>Assign Job</h1>
    </header>
    <div class="container">
        <form method="post" action="assign_to.php">
            <div>
                <label for="appointment_id">Appointment:</label>
                <select name="appointment_id" required>
                    <?php foreach ($appointments as $appointment): ?>
                        <option value="<?php echo $appointment['APPID']; ?>">
                            <?php echo htmlspecialchars($appointment['date']) . ' - ' . htmlspecialchars($appointment['plateNo']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="mechanic_id">Assign To Mechanic:</label>
                <select name="mechanic_id" required>
                    <?php foreach ($mechanics as $mechanic): ?>
                        <option value="<?php echo $mechanic['eid']; ?>"><?php echo ($mechanic['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="description">Job Description:</label>
                <textarea name="description" required></textarea>
            </div>
            <button type="submit">Assign Job</button>
        </form>
    </div>
</div>
</body>
</html>
