<?php
  if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Include the configuration file to establish a database connection
include '../config.php';
include '../utils.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit;
}



// Initialize counts
$appointmentsCount = 0;
$inProgressCount = 0;
$pendingPaymentsCount = 0;
$completedCount = 0;

// Fetch data for appointments
$queryAppointments = "SELECT COUNT(*) as count FROM appointment WHERE cid = ? AND status = 'pending'";
$stmt = $conn->prepare($queryAppointments);
$stmt->bind_param("i", $_SESSION['user']['cid']);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $appointmentsCount = $row['count'];
}

// Fetch data for in-progress jobs
$queryInProgress = "SELECT COUNT(*) as count FROM job WHERE cid = ? AND status = 'In Progress'";
$stmt = $conn->prepare($queryInProgress);
$stmt->bind_param("i", $_SESSION['user']['cid']);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $inProgressCount = $row['count'];
}

// Fetch data for pending payments
$queryPendingPayments = "SELECT COUNT(*) as count FROM payment WHERE payId = ? AND status = 'pending'";
$stmt = $conn->prepare($queryPendingPayments);
$stmt->bind_param("i", $_SESSION['user']['cid']);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $pendingPaymentsCount = $row['count'];
}

// Fetch data for completed jobs
$queryCompleted = "SELECT COUNT(*) as count FROM job WHERE cid  = ? AND status = 'Completed'";
$stmt = $conn->prepare($queryCompleted);
$stmt->bind_param("i", $_SESSION['user']['cid']);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $completedCount = $row['count'];
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Basic styling for dashboard cards */
        .card-container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
        }

        .card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            flex: 1;
            min-width: 200px;
            text-align: center;
        }

        .card h3 {
            margin: 0;
            font-size: 24px;
        }

        .card i {
            font-size: 40px;
            margin-bottom: 10px;
            color: #007bff;
        }

        .card p {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>User Dashboard</h2>
        <nav>
            <ul>
                <li><a href="dashboard.php"><i class="class fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="create_appointment.php"><i class="fas fa-plus"></i> Create Appointment</a></li>
                <li><a href="view_appointment.php"><i class="fas fa-calendar-check"></i> View Appointments</a></li>
                <li><a href="../logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </div>
    <div class="main-content">
        <header>
            <h1>Welcome, <?php echo ($_SESSION['user']['name']); ?></h1>
        </header>
        <!-- Dashboard cards -->
        <div class="card-container">
            <div class="card">
                <i class="fas fa-calendar-alt"></i>
                <h3><?php echo ($appointmentsCount); ?></h3>
                <p>Appointments</p>
            </div>
            <div class="card">
                <i class="fas fa-cogs"></i>
                <h3><?php echo ($inProgressCount); ?></h3>
                <p>In Progress</p>
            </div>
            <div class="card">
                <i class="fas fa-hourglass-half"></i>
                <h3><?php echo ($pendingPaymentsCount); ?></h3>
                <p>Pending Payments</p>
            </div>
            <div class="card">
                <i class="fas fa-check-circle"></i>
                <h3><?php echo ($completedCount); ?></h3>
                <p>Completed</p>
            </div>
        </div>
    </div>
</body>
</html>
