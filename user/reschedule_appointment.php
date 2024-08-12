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
    $newDate = $_POST['date'];
    $newTime = $_POST['time'];

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Update the appointment date and time
        $stmt = $conn->prepare("UPDATE appointment SET date = ?, time = ? WHERE aid = ? AND cid = ?");
        $stmt->bind_param("ssii", $newDate, $newTime, $aid, $_SESSION['user']['cid']);
        $stmt->execute();
        $stmt->close();

        // Commit transaction
        $conn->commit();

        $_SESSION['success'] = "Appointment rescheduled successfully.";
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $conn->rollback();
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }
}

// Redirect back to view appointments page
Utils::redirect_to('view_appointment.php');
