<?php
session_start();
if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'mechanic') {
    header('Location: ../employee_admin_login.php');
    exit;
}

include '../config.php'; // Assuming your config.php sets up the $conn variable
include '../functions.php'; // Assuming you have a functions.php for any utility functions

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $plateNo = sanitizeInput($_POST['plateNo']);
    $status = sanitizeInput($_POST['status']);
    $description = sanitizeInput($_POST['description']);
    $vehicleMileage = sanitizeInput($_POST['vehicle_mileage']);
    $timeToComplete = sanitizeInput($_POST['time_to_complete']);
    
    // Insert or update vehicle status in the database
    $stmt = $conn->prepare("INSERT INTO vehicle_status (date, terminal, decription, plateNo, vehicle_mileage, addedTime, timeToComplete) VALUES (CURDATE(), 'IN', ?, ?, ?, CURTIME(), ?) ON DUPLICATE KEY UPDATE decription = VALUES(decription), vehicle_mileage = VALUES(vehicle_mileage), addedTime = VALUES(addedTime), timeToComplete = VALUES(timeToComplete)");
    $stmt->bind_param("ssiii", $description, $plateNo, $vehicleMileage, $timeToComplete);
    
    if ($stmt->execute()) {
        echo "Vehicle status updated successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Vehicle Status</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <h1>Update Vehicle Status</h1>
        <nav>
            <ul>
                <li><a href="employee_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="../logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <h2>Vehicle Status</h2>
        <form action="update_vehicle_status.php" method="post">
            <label for="plateNo"><i class="fas fa-car"></i> Plate Number</label>
            <input type="text" id="plateNo" name="plateNo" required>
            
            <label for="status"><i class="fas fa-info-circle"></i> Status</label>
            <input type="text" id="status" name="status" required>
            
            <label for="description"><i class="fas fa-pencil-alt"></i> Description</label>
            <input type="text" id="description" name="description" required>
            
            <label for="vehicle_mileage"><i class="fas fa-tachometer-alt"></i> Vehicle Mileage</label>
            <input type="number" id="vehicle_mileage" name="vehicle_mileage" required>
            
            <label for="time_to_complete"><i class="fas fa-clock"></i> Time to Complete (in minutes)</label>
            <input type="number" id="time_to_complete" name="time_to_complete" required>
            
            <button type="submit"><i class="fas fa-save"></i> Update Status</button>
        </form>
    </div>
</body>
</html>
