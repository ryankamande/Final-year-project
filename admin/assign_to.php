<?php
// Start a session or resume the current one
session_start();

// Check if the user session is not set or if the user's role is not 'admin'.
// If true, redirect them to the employee/admin login page and exit the script.
if (!isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin') {
    header('Location: ../employee_admin_login.php');
    exit;
}

// Include the database configuration file to establish a connection
include '../config.php';

// Include any utility functions that might be needed
include '../utils.php';

// Initialize an empty array to store mechanics' data
$mechanics = [];

// Query to fetch all mechanics (employees with type 'mechanic')
$mechanicsQuery = "SELECT eid, name FROM employee WHERE type = 'mechanic'";

// Execute the query and store the result
$result = $conn->query($mechanicsQuery);

// If there are results, loop through them and add each mechanic to the $mechanics array
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $mechanics[] = $row;
    }
}

// Initialize an empty array to store appointments' data
$appointments = [];

// Query to fetch all appointments
$appointmentsQuery = "SELECT * FROM appointment";

// Execute the query and store the result
$result = $conn->query($appointmentsQuery);

// If there are results, loop through them and add each appointment to the $appointments array
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }
}

// Check if the form was submitted (i.e., the request method is POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the appointment ID, mechanic ID, and job description from the POST request
    $appointmentId = $_POST['aid'];
    $mechanicId = $_POST['mechanic_id'];
    $description = Utils::sanitizeInput($_POST['description']); // Sanitize the input to prevent XSS/SQL injection

    // Prepare an SQL query to insert the job assignment into the 'job' table
    $assignJobQuery = "INSERT INTO job (description, status, assigned_to, aid) VALUES (?, 'pending', ?, ?)";
    
    // Prepare the statement for execution
    $stmt = $conn->prepare($assignJobQuery);
    
    // Bind the parameters to the SQL query
    $stmt->bind_param("sii", $description, $mechanicId, $appointmentId);

    // Execute the query and check if the insertion was successful
    if ($stmt->execute()) {
        echo "Job assigned successfully.";
    } else {
        // Display an error message if the insertion failed
        echo "Error: " . $stmt->error;
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <!-- Set the title of the page -->
    <title>Assign Job</title>
    
    <!-- Link to the external CSS file for styling -->
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
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
                <li><a href="admin_dashboard.php"> Dashboard</a></li>
                <li><a href="manage_appointments.php">Manage Appointments</a></li>
                <li><a href="assign_to.php">Assign Mechanic</a></li>
                <li><a href="manage_jobs.php"> Manage Jobs</a></li>
                <li><a href="send_invoice.php">Billing</a></li>
                <li><a href="reports.php">Reports</a></li>
                <li><a href="../logout.php" class="logout"> Logout</a></li>
            </ul>
        </nav>
    </div>
    
    <!-- Main content section for assigning jobs -->
    <div class="main-content">
        <!-- Header section with a page title -->
        <header>
            <h1>Assign Job</h1>
        </header>
        
        <!-- Container for the job assignment form -->
        <div class="container">
            <!-- Form to assign a job to a mechanic -->
            <form method="post" action="assign_to.php">
                
                <!-- Dropdown menu to select an appointment -->
                <div>
                    <label for="appointment_id">Appointment:</label>
                    <select name="appointment_id">
                        <!-- Loop through the $appointments array to populate the dropdown -->
                        <?php foreach ($appointments as $appointment): ?>
                            <option value="<?php echo $appointment['APPID']; ?>">
                                <!-- Display the appointment date and vehicle's plate number -->
                                <?php echo htmlspecialchars($appointment['date']) . ' - ' . htmlspecialchars($appointment['plateNo']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Dropdown menu to select a mechanic to assign the job to -->
                <div>
                    <label for="mechanic_id">Assign To Mechanic:</label>
                    <select name="mechanic_id">
                        <!-- Loop through the $mechanics array to populate the dropdown -->
                        <?php foreach ($mechanics as $mechanic): ?>
                            <option value="<?php echo $mechanic['eid']; ?>"><?php echo htmlspecialchars($mechanic['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Textarea for entering a job description -->
                <div>
                    <label for="description">Job Description:</label>
                    <textarea name="description"></textarea>
                </div>
                
                <!-- Submit button to assign the job -->
                <button type="submit">Assign Job</button>
            </form>
        </div>
    </div>
</body>
</html>
