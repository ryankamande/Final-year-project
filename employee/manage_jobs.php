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

// Include the functions file for any additional functionality
include '../utils.php';

// Fetch job data from the database
$jobs = [];
$query = "SELECT * FROM job"; // Adjust the table name as necessary
$result = $conn->query($query);

// Check if the query returned any results
if ($result->num_rows > 0) {
    // Loop through each row and store the data in the $jobs array
    while ($row = $result->fetch_assoc()) {
        $jobs[] = $row;
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <!-- Set the title of the page -->
    <title>Manage Jobs</title>
    <!-- Link to the stylesheet for styling the page -->
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <!-- Include jQuery for modal functionality -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Basic modal styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 40%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <!-- Create a sidebar for navigation -->
    <div class="sidebar">
        <h2>Employee Dashboard</h2>
        <nav>
            <ul>
                <!-- Link to the employee dashboard page -->
                <li><a href="employee_dashboard.php"> Dashboard</a></li>
                <!-- Link to the manage jobs page (current page) -->
                <li><a href="manage_jobs.php"> View Jobs</a></li>
                <!-- Link to the logout page -->
                <li><a href="../logout.php" class="logout"> Logout</a></li>
            </ul>
        </nav>
    </div>
    
    <!-- Create a header for the page -->
    <header>
        <h1>Manage Jobs</h1>
        <nav>
            <ul>
                <!-- Link to the employee dashboard page -->
                <li><a href="employee_dashboard.php"> Dashboard</a></li>
                <!-- Link to the logout page -->
                <li><a href="../logout.php" class="logout"> Logout</a></li>
            </ul>
        </nav>
    </header>
    
    <!-- Create a container for the page content -->
    <div class="container">
        <h2>Jobs</h2>
        <!-- Create a table to display the job data -->
        <table>
            <thead>
                <tr>
                    <!-- Table headers for job data -->
                    <th>Job ID </th>
                    <th>Description </th>
                    <th>Status </th>
                    <th>Assigned To </th>
                    <th>Actions </th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($jobs)): ?>
                    <?php foreach ($jobs as $job): ?>
                        <tr>
                            <!-- Display job data in table cells -->
                            <td><?php echo htmlspecialchars($job['jobId']); ?></td>
                            <td><?php echo htmlspecialchars($job['description']); ?></td>
                            <td><?php echo htmlspecialchars($job['status']); ?></td>
                            <td><?php echo htmlspecialchars($job['assigned_to']); ?></td>
                            <td>
                                <!-- Button to open the modal for updating the status -->
                                <button class="open-modal" data-id="<?= htmlspecialchars($job['jobId']); ?>" data-status="<?= htmlspecialchars($job['status']); ?>">Update Status</button>
                                
                            </td>
     
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No jobs found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Modal for updating job status -->
    <div id="statusModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Update Job Status</h2>
            <form id="statusForm" action="update_status.php" method="post">
    <input type="hidden" name="jobId" id="jobId">
    <label for="status">Status:</label>
    <select name="status" id="status">
    <option value="" selected="selected" hidden="hidden">Choose here</option>
        <option value="Pending">Pending</option>
        <option value="In Progress">In Progress</option>
        <option value="Completed">Completed</option>
    </select>
    <button type="submit">Update Status</button>
</form>

        </div>
    </div>
    
    <script>
        // Open the modal and populate the form fields
        $(document).on('click', '.open-modal', function() {
            var jobId = $(this).data('id');
            var status = $(this).data('status');
            
            $('#jobId').val(jobId);
            $('#status').val(status);
            $('#statusModal').show();
        });

        // Close the modal
        $('.close').on('click', function() {
            $('#statusModal').hide();
        });

        // Close the modal if clicked outside the modal content
        $(window).on('click', function(event) {
            if ($(event.target).is('#statusModal')) {
                $('#statusModal').hide();
            }
        });
    </script>
</body>
</html>
