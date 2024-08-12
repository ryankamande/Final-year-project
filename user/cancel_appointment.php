<?php
// Ensure that session is active
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

include '../config.php';
include '../utils.php';

// Check if the customer is logged in
if (!isset($_SESSION['user'])) {
    Utils::redirect_to('../login.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $aid = $_POST['aid'];

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Delete the appointment
        $stmt = $conn->prepare("DELETE FROM appointment WHERE aid = ? AND cid = ?");
        $stmt->bind_param("ii", $aid, $_SESSION['user']['cid']);
        $stmt->execute();
        $stmt->close();

        // Commit transaction
        $conn->commit();

        $_SESSION['success'] = "Appointment canceled successfully.";
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $conn->rollback();
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }
}

// Redirect back to view appointments page
Utils::redirect_to('view_appointment.php');
