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
    $appointmentId = $_POST['aid'];
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
                <li><a href="reports.php">Reports</a></li>
                <li><a href="../logout.php" class="logout"> Logout</a></li>
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
                <select name="appointment_id"  >
                    <?php foreach ($appointments as $appointment): ?>
                        <option value="<?php echo $appointment['APPID']; ?>">
                            <?php echo htmlspecialchars($appointment['date']) . ' - ' . htmlspecialchars($appointment['plateNo']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="mechanic_id">Assign To Mechanic:</label>
                <select name="mechanic_id"  >
                    <?php foreach ($mechanics as $mechanic): ?>
                        <option value="<?php echo $mechanic['eid']; ?>"><?php echo ($mechanic['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="description">Job Description:</label>
                <textarea name="description"  ></textarea>
            </div>
            <button type="submit">Assign Job</button>
        </form>
    </div>
</div>
</body>
</html>
