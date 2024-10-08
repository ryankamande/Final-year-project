<?php
session_start();
include '../config.php';
include '../utils.php';

// Check if the customer is logged in
if (!isset($_SESSION['user']) || isset($_SESSION['user']['id'])) {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appointmentId = isset($_POST['aid']) ? (int)$_POST['aid'] : 0;
    $amount =  Utils::sanitizeInput($_POST['amount']);

    // Validate the amount
    if (!is_numeric($amount) || $amount <= 0) {
        echo "Invalid amount.";
        exit();
    }

    $sql = "UPDATE job SET pay = 'completed' WHERE aid = ? AND cid = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("ii", $appointmentId, $_SESSION['user']['id']);
    if ($stmt->execute()) { 
        // check on this
        header('Location: view_appointment.php?message=Invoice paid successfully');
    } else {
        echo "Error: " . htmlspecialchars($stmt->error);
    }
    $stmt->close();
} else {
    header('Location: view_appointment.php');
}

$conn->close();
?>
