<?php
// Start the session to access stored user data
session_start();

// Check if the user is logged in and has the role of 'mechanic'
// Redirect to the employee admin login page if not
if (!isset($_SESSION['user']) && $_SESSION['user']['role'] == 'mechanic') {
    header('Location: ../employee_admin_login.php');
    exit;
}


// Include the configuration file to establish a database connection
include '../config.php';

// Check if the form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['jobId']) && isset($_POST['status'])) {
    // Sanitize input
    $jobId = $conn->real_escape_string($_POST['jobId']);
    $status = $conn->real_escape_string($_POST['status']);

    // Update job status in the database
    $query = "UPDATE job SET status='$status' WHERE jobId='$jobId'";
    if ($conn->query($query) === TRUE) {
        // Redirect back to the manage jobs page with a success message
        header('Location: manage_jobs.php?status=success');
    } else {
        // Redirect back with an error message
        header('Location: manage_jobs.php?status=error');
    }
} else {
    // Redirect back if no data is received
    header('Location: manage_jobs.php');
}

// Close the database connection
$conn->close();
?>
