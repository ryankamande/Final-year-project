<!-- create_appointment.php -->
<?php
include '../config.php';
include '../utils.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}


if (!isset($_SESSION['user'])) {
    Utils::redirect_to('../login.php');
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $time = $_POST['time'];
    $date = $_POST['date'];
    $plateNo = $_POST['plateNo'];
    $make=$_POST['make'];
    $model=$_POST['model'];
    $service_type = $_POST['service_type'];

 
    // Start a transaction
    $conn->begin_transaction();

    try {
        // Insert the appointment
        $stmt = $conn->prepare("INSERT INTO appointment (time, date, plateNo, make, model, cid, service_type) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssis", $time, $date, $plateNo, $make, $model,  $_SESSION['user']['cid'],$service_type);
        $stmt->execute();
        $stmt->close();

        // Commit transaction
        $conn->commit();

        Utils::redirect_with_message('view_appointment.php', 'success', "Appointment booked and vehicle status updated.");
    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    $conn->close();
}

// coalescing operator `??`
// checks if a variable exists and is not null,
// and if it doesn't, it returns a default value
$message = $_SESSION['success'] ?? $_SESSION['error'] ?? null;

// `unset()` function destroys a variable. Once a variable is unset, it's no longer accessible
unset($_SESSION['success'], $_SESSION['error']);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Appointment</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>

<body>
    <div class="sidebar">
        <h2>User Dashboard</h2>
        <nav>
            <ul>
                <li><a href="dashboard.php"><i class="class fas fa-tachometer-alt"></i>Dashboard</a></li>
                <li><a href="create_appointment.php"><i class="fas fa-plus"></i> Create Appointment</a></li>
                <li><a href="view_appointment.php"><i class="fas fa-calendar-check"></i> View Appointments</a></li>
               
                <li><a href="../logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </div>
    <div class="class main-content">
        <header>
            <h2>Create Appointment</h2>
        </header>
    <div class="class container">   
    <form action="create_appointment.php" method="post">
    <div>
        <label for="time">Time:</label>
        <input type="text" id="time" name="time" placeholder="HH:MM" />
    </div>

    <div>
        <label for="date">Date:</label>
        <input type="date" id="date" name="date">
    </div>

    <div>
        <label for="make">Vehicle make:</label>
        <input type="text" id="make" name="make">
    </div>

    <div>
        <label for="model">Vehicle model:</label>
        <input type="text" id="model" name="model">
    </div>

    <div>
        <label for="plateNo">Plate Number:</label>
        <input type="text" id="plateNo" name="plateNo">
    </div>

    <div>
        <label for="service_type">Service Type:</label>
        <select id="service_type" name="service_type">
            <option value="Routine Maintenance">Routine Maintenance</option>
            <option value="Engine Diagnostics">Engine Diagnostics</option>
            <option value="Brake Service">Brake Service</option>
            <option value="Transmission Repair">Transmission Repair</option>
            <option value="Custom Modifications">Custom Modifications</option>
        </select>
    </div>

    <button type="submit">Create Appointment</button>
</form>
    </div>
    </div> 
</body>
</html>
