<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] == 'admin') {
    header('Location: ../login.php');
    exit;
}

include '../config.php'; // Assuming your config.php sets up the $conn variable
include '../functions.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $paymentAmount = sanitizeInput($_POST['payment_amount']);
    $paymentMethod = sanitizeInput($_POST['payment_method']);
    $customerId = sanitizeInput($_POST['customer_id']);
    $appointmentId = sanitizeInput($_POST['appointment_id']);
    
    // Insert payment into database
    $stmt = $conn->prepare("INSERT INTO payment (customer_id, appointment_id, amount, method, date) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("iids", $customerId, $appointmentId, $paymentAmount, $paymentMethod);
    
    if ($stmt->execute()) {
        echo "Payment processed successfully.";
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
    <title>Process Payments</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <h1>Process Payments</h1>
        <nav>
            <ul>
                <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="../logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <h2>Payments</h2>
        <form action="process_payments.php" method="post">
            <label for="payment_amount"><i class="fas fa-dollar-sign"></i> Payment Amount</label>
            <input type="number" id="payment_amount" name="payment_amount" step="0.01" required>
            
            <label for="payment_method"><i class="fas fa-credit-card"></i> Payment Method</label>
            <select id="payment_method" name="payment_method" required>
                <option value="Credit Card">Credit Card</option>
                <option value="Cash">Cash</option>
                <option value="Check">Check</option>
            </select>
            
            <label for="customer_id"><i class="fas fa-user"></i> Customer ID</label>
            <input type="number" id="customer_id" name="customer_id" required>
            
            <label for="appointment_id"><i class="fas fa-calendar-alt"></i> Appointment ID</label>
            <input type="number" id="appointment_id" name="appointment_id" required>
            
            <button type="submit"><i class="fas fa-money-check-alt"></i> Process Payment</button>
        </form>
    </div>
</body>
</html>
