<?php
// Start a new session or resume the existing one
session_start();

// Check if the user session is not set or if the user's role is not 'admin'
// If the condition is true, redirect them to the employee/admin login page and exit the script
if (!isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin') {
    header('Location: ../employee_admin_login.php');
    exit;
}

// Include the database configuration file to establish a connection
include '../config.php';

// Include any utility functions that might be needed (e.g., input sanitization)
include '../utils.php';

// Query to fetch all appointments with their ID, time, date, and plate number
$appointmentsQuery = "SELECT aid, time, date, plateNo, service_type FROM appointment";

// Execute the query and store the result
$result = $conn->query($appointmentsQuery);

// Initialize an empty array to store appointments' data
$appointments = [];

// Check if there are any rows returned by the query
if ($result->num_rows > 0) {
    // Loop through each row and add the appointment data to the $appointments array
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <!-- Set the title of the page -->
    <title>View Appointments</title>

    <!-- Link to the external CSS file for styling -->
    <link rel="stylesheet" type="text/css" href="assets/css/admin_style.css">
</head>
<body>
    <!-- Sidebar section of the admin dashboard -->
    <div class="sidebar">
        <!-- Title of the sidebar -->
        <h2>Admin Dashboard</h2>
        
        <!-- Navigation menu inside the sidebar -->
        <nav>
            <ul>
                <!-- Links to different pages within the admin dashboard -->
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="manage_appointments.php">Manage Appointments</a></li>
                <li><a href="assign_to.php">Assign Mechanic</a></li>
                <li><a href="manage_jobs.php">Manage Jobs</a></li>
                <li><a href="reports.php">Reports</a></li>
                <li><a href="../logout.php" class="logout">Logout</a></li>
            </ul>
        </nav>
    </div>
    
    <!-- Main content section where appointments are displayed -->
    <div class="main-content">
        <!-- Header section with a page title -->
        <header>
            <h1>View Appointments</h1>
        </header>

        <!-- Container for displaying the list of appointments -->
        <div class="container">
            <h2>Appointments</h2>
            
            <!-- Table to display the appointment data -->
            <table>
    <!-- Table header defining the column names -->
    <thead>
        <tr>
            <th>Appointment ID</th> <!-- Column for Appointment ID -->
            <th>Time</th>           <!-- Column for Appointment Time -->
            <th>Date</th           <!-- Column for Appointment Date -->
            <th>Plate No</th>      <!-- Column for Vehicle Plate Number -->
            <th>Service Type</th>  <!-- Column for Service Type -->
        </tr>
    </thead>
    
    <!-- Table body containing the data -->
    <tbody>
        <!-- Check if the $appointments array is not empty -->
        <?php if (!empty($appointments)): ?>
            <!-- Loop through each appointment in the $appointments array -->
            <?php foreach ($appointments as $appointment): ?>
                <tr>
                    <!-- Display the appointment ID -->
                    <td><?php echo($appointment['aid']); ?></td>
                    
                    <!-- Display the appointment time -->
                    <td><?php echo($appointment['time']); ?></td>
                    
                    <!-- Display the appointment date -->
                    <td><?php echo($appointment['date']); ?></td>
                    
                    <!-- Display the vehicle's plate number -->
                    <td><?php echo($appointment['plateNo']); ?></td>
                    
                    <!-- Display the service type associated with the appointment -->
                    <td><?php echo($appointment['service_type']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- If no appointments are found, display a message -->
            <tr>
                <td colspan="5">No appointments found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

        </div>
    </div>
</body>
</html>
