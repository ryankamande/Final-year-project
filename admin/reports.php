<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] == 'admin') {
    header('Location: ../login.php');
    exit;
}

include '../config.php'; // Include your database connection
include '../functions.php';

// Fetching filter values
$startDate = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$endDate = isset($_POST['end_date']) ? $_POST['end_date'] : '';

// Total Sales
$salesQuery = "SELECT 
    (SELECT SUM(total) FROM payment WHERE DATE(date) BETWEEN CURDATE() - INTERVAL 7 DAY AND CURDATE()) AS weekly_sales,
    (SELECT SUM(total) FROM payment WHERE DATE(date) BETWEEN CURDATE() - INTERVAL 1 MONTH AND CURDATE()) AS monthly_sales,
    (SELECT SUM(total) FROM payment WHERE DATE(date) BETWEEN CURDATE() - INTERVAL 1 YEAR AND CURDATE()) AS yearly_sales";
$salesResult = $conn->query($salesQuery)->fetch_assoc();

// Payments
$paymentsQuery = "SELECT 
    (SELECT COUNT(*) FROM payment WHERE total > 0) AS successful_payments,
    (SELECT COUNT(*) FROM payment WHERE total = 0) AS pending_payments";
$paymentsResult = $conn->query($paymentsQuery)->fetch_assoc();

// Inventory
$inventoryQuery = "SELECT 
    (SELECT SUM(qty) FROM stock WHERE qty > 0) AS total_in_stock,
    (SELECT SUM(qty) FROM stock WHERE qty < 0) AS total_used";
$inventoryResult = $conn->query($inventoryQuery)->fetch_assoc();

// Appointments
$appointmentsQuery = "SELECT 
    (SELECT COUNT(*) FROM appointment WHERE status = 'booked') AS booked_appointments,
    (SELECT COUNT(*) FROM appointment WHERE status = 'completed') AS completed_appointments,
    (SELECT COUNT(*) FROM appointment WHERE status = 'rescheduled') AS rescheduled_appointments";
$appointmentsResult = $conn->query($appointmentsQuery)->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Generate Reports</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <header>
        <h1>Generate Reports</h1>
        <nav>
            <ul>
                <li><a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="../logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <h2>Reports</h2>
        
        <form method="post">
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($startDate); ?>">
            
            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($endDate); ?>">
            
            <button type="submit"><i class="fas fa-filter"></i> Apply Filters</button>
        </form>
        
        <h3>Total Sales</h3>
        <table>
            <thead>
                <tr>
                    <th>Period</th>
                    <th>Sales Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Weekly Sales</td>
                    <td>KSH.<?php echo number_format($salesResult['weekly_sales'], 2); ?></td>
                </tr>
                <tr>
                    <td>Monthly Sales</td>
                    <td>KSH.<?php echo number_format($salesResult['monthly_sales'], 2); ?></td>
                </tr>
                <tr>
                    <td>Yearly Sales</td>
                    <td>KSH.<?php echo number_format($salesResult['yearly_sales'], 2); ?></td>
                </tr>
            </tbody>
        </table>

        <h3>Payments</h3>
        <table>
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Successful Payments</td>
                    <td><?php echo $paymentsResult['successful_payments']; ?></td>
                </tr>
                <tr>
                    <td>Pending Payments</td>
                    <td><?php echo $paymentsResult['pending_payments']; ?></td>
                </tr>
            </tbody>
        </table>

        <h3>Inventory</h3>
        <table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Total In-Stock</td>
                    <td><?php echo $inventoryResult['total_in_stock']; ?> items</td>
                </tr>
                <tr>
                    <td>Total Used Stock</td>
                    <td><?php echo $inventoryResult['total_used']; ?> items</td>
                </tr>
            </tbody>
        </table>

        <h3>Appointments</h3>
        <table>
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Booked Appointments</td>
                    <td><?php echo $appointmentsResult['booked_appointments']; ?></td>
                </tr>
                <tr>
                    <td>Completed Appointments</td>
                    <td><?php echo $appointmentsResult['completed_appointments']; ?></td>
                </tr>
                <tr>
                    <td>Rescheduled Appointments</td>
                    <td><?php echo $appointmentsResult['rescheduled_appointments']; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php
$conn->close();
?>
