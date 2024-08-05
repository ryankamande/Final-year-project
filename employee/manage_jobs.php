<?php
session_start();
if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'mechanic') {
    header('Location: ../employee_admin_login.php');
    exit;
}

include '../config.php'; // Assuming your config.php sets up the $conn variable
include '../functions.php';

// Fetch job data from the database
$jobs = [];
$query = "SELECT * FROM job"; // Adjust the table name as necessary
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $jobs[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Jobs</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <h1>Manage Jobs</h1>
        <nav>
            <ul>
                <li><a href="employee_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="../logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <h2>Jobs</h2>
        <table>
            <thead>
                <tr>
                    <th>Job ID <i class="fas fa-id-badge"></i></th>
                    <th>Description <i class="fas fa-file-alt"></i></th>
                    <th>Status <i class="fas fa-tasks"></i></th>
                    <th>Assigned To <i class="fas fa-user"></i></th>
                    <th>Actions <i class="fas fa-cogs"></i></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($jobs)): ?>
                    <?php foreach ($jobs as $job): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($job['job_id']); ?></td>
                            <td><?php echo htmlspecialchars($job['description']); ?></td>
                            <td><?php echo htmlspecialchars($job['status']); ?></td>
                            <td><?php echo htmlspecialchars($job['assigned_to']); ?></td>
                            <td>
                                <a href="edit_job.php?id=<?php echo $job['job_id']; ?>"><i class="fas fa-edit"></i> Edit</a>
                                <a href="delete_job.php?id=<?php echo $job['job_id']; ?>" onclick="return confirm('Are you sure?');"><i class="fas fa-trash"></i> Delete</a>
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
</body>
</html>
