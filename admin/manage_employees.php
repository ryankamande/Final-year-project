<?php
// Start a new session or resume the existing one
session_start();

// Check if the user is not logged in or if the user's role is 'admin'.
// If the user is not logged in or the role is 'admin', redirect them to the login page and exit the script.
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] == 'admin') {
    header('Location: ../login.php');
    exit;
}

// Include the database configuration file to establish a connection to the database
include '../config.php';

// Fetch all employees from the 'employee' table in the database
$query = "SELECT * FROM employee";

// Execute the query and store the result
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html>
<head>
    <!-- Set the title of the page -->
    <title>Manage Employees</title>

    <!-- Link to the external CSS file for styling -->
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>
<body>
    <!-- Header section of the page -->
    <header>
        <!-- Page title -->
        <h1>Manage Employees</h1>

        <!-- Navigation menu -->
        <nav>
            <ul>
                <!-- Links to different pages within the admin dashboard -->
                <li><a href="admin_dashboard.php"> Dashboard</a></li>
                <li><a href="manage_appointments.php">Manage Appointments</a></li>
                <li><a href="assign_to.php">Assign Mechanic</a></li>
                <li><a href="manage_jobs.php">Manage Jobs</a></li>
                <li><a href="send_invoice.php">Billing</a></li>
                <li><a href="reports.php">Reports</a></li>
                <li><a href="../logout.php" class="logout">Logout</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main container for displaying the employee data -->
    <div class="container">
        <h2>Employees</h2>

        <!-- Table for displaying the list of employees -->
        <table>
            <!-- Table header defining the column names -->
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            
            <!-- Table body containing the data from the employees table -->
            <tbody>
                <?php
                // Check if there are any employees in the result set
                if ($result->num_rows > 0) {
                    // Loop through each employee and create a table row
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['eid'] . "</td>";  // Display employee ID
                        echo "<td>" . $row['name'] . "</td>"; // Display employee name
                        echo "<td>" . $row['username'] . "</td>"; // Display employee email (username)
                        echo "<td>" . $row['type'] . "</td>";  // Display employee role/type

                        // Display action links for editing or deleting the employee record
                        
                    }
                } else {
                    // If no employees are found, display a message in a single table row
                    echo "<tr><td colspan='5'>No employees found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
// Close the database connection after all operations are done
$conn->close();
?>
