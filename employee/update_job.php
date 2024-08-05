<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] == 'employee') {
    header('Location: ../employee_admin_login.php');
    exit;
}

include '../config.php'; // Assuming your config.php sets up the $conn variable
include '../functions.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jobId = $_POST['job_id'];
    $completionDate = date('Y-m-d'); // Current date

    // Update job status to completed
    $updateQuery = "UPDATE job SET status = 'completed', completion_date = ? WHERE jobId = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ss", $completionDate, $jobId);

    if ($stmt->execute()) {
        // Fetch appointment and customer details
        $appointmentQuery = "SELECT * FROM appointment WHERE APPID = (SELECT aid FROM job WHERE jobId = ?)";
        $stmt = $conn->prepare($appointmentQuery);
        $stmt->bind_param("s", $jobId);
        $stmt->execute();
        $result = $stmt->get_result();
        $appointment = $result->fetch_assoc();

        if ($appointment) {
            $customerEmail = $appointment['customer_email'];
            $adminEmail = 'admin@example.com'; // Admin email address

            // Prepare email
            $subject = "Vehicle Service Completed";
            $message = "Dear Customer,\n\nYour vehicle service has been completed. Please contact us if you have any questions.\n\nBest Regards,\nThe Team";
            $headers = "From: no-reply@example.com\r\n";
            
            // Send email to customer
            mail($customerEmail, $subject, $message, $headers);
            
            // Send email to admin
            $adminMessage = "The job with ID $jobId has been marked as completed.";
            mail($adminEmail, $subject, $adminMessage, $headers);
            
            echo "Job updated and notifications sent successfully.";
        } else {
            echo "Error fetching appointment details.";
        }

        $stmt->close();
    } else {
        echo "Error updating job: " . $stmt->error;
    }
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Job Status</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <h2>Mechanic Dashboard</h2>
        <nav>
            <ul>
                <li><a href="employee_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="manage_attendance.php"><i class="fas fa-user-check"></i> Manage Attendance</a></li>
                <li><a href="manage_jobs.php"><i class="fas fa-briefcase"></i> View Jobs</a></li>
                <li><a href="update_job.php"><i class="fas fa-car"></i> Update Job</a></li>
                <li><a href="../logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </div>
    <div class="main-content">
        <header>
            <h1>Update Job Status</h1>
        </header>
        <div class="container">
            <form method="post" action="update_job.php">
                <div>
                    <label for="job_id">Job ID:</label>
                    <input type="text" name="job_id" required>
                </div>
                <button type="submit">Mark as Completed</button>
            </form>
        </div>
    </div>
</body>
</html>
