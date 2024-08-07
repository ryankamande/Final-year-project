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

    $sql = "DELETE FROM appointment WHERE aid = ? AND cid = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("ii", $appointmentId, $_SESSION['user']['id']);
    if ($stmt->execute()) {
        header('Location: view_appointment.php?message=Appointment canceled successfully');
    } else {
        echo "Error: " . htmlspecialchars($stmt->error);
    }
    $stmt->close();
} else {
    header('Location: view_appointment.php');
}

$conn->close();
?>
