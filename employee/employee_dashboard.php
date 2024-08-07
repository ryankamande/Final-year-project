<?php
session_start();
if (!isset($_SESSION['user']) && $_SESSION['user']['role'] == 'mechanic') {
    header('Location: ../employee_admin_login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Employee Dashboard</title>
    <link rel="stylesheet" type="text/css" href="assets/css/employee_style.css"> <!-- Custom CSS for employee dashboard -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Basic styling for the dashboard cards */
        .dashboard-cards {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin: 20px;
        }

        .card {
            background-color: #f4f4f4;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            width: 24%;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            text-align: center;
        }

        .card h3 {
            margin: 0;
            font-size: 24px;
        }

        .card p {
            margin: 10px 0;
            font-size: 18px;
        }

        .card i {
            font-size: 40px;
            color: #333;
        }

        @media (max-width: 768px) {
            .card {
                width: 48%;
            }
        }

        @media (max-width: 480px) {
            .card {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Employee Dashboard</h2>
        <nav>
            <ul>
                <li><a href="employee_dashboard.php"><i class="class fa fas tachometer alt"></i>Dashboard</a></li>
                <li><a href="manage_jobs.php"><i class="fas fa-briefcase"></i> View Jobs</a></li>
                <li><a href="../logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </div>
    <div class="main-content">
        <header>
        <h2>Welcome, <?php echo $_SESSION['user']['name']; ?></h2>
        </header>
        <div class="dashboard-cards">
            <div class="card">
                <i class="fas fa-calendar-check"></i>
                <h3>Appointments</h3>
                <p>5 Upcoming</p>
            </div>
            <div class="card">
                <i class="fas fa-tachometer-alt"></i>
                <h3>Tasks</h3>
                <p>12 In Progress</p>
            </div>
         
            <div class="card">
                <i class="fas fa-cogs"></i>
                <h3>Jobs</h3>
                <p>7 Assigned</p>
            </div>
        </div>
    </div>
</body>
</html>