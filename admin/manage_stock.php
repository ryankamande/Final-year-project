<?php
session_start(); //script checks if the user is logged in and has the role of employee
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] == 'admin') {
    header('Location: ../login.php');
    exit;
}

include '../config.php'; 
include '../functions.php';

// Fetch stock data from the database and store in the $stockItems array
$stockItems = [];
$query = "SELECT * FROM stock"; // Adjust the table name as necessary
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $stockItems[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Stock</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="sidebar">
        <h2>Admin Dashboard</h2>
        <nav>
            <ul>
                <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="manage_appointments.php"><i class="fas fa-calendar-alt"></i>Manage Appointments</a></li>
                <li><a href="manage_jobs.php"><i class="fas fa-briefcase"></i> Manage Jobs</a></li>
                <li><a href="process_payments.php"><i class="fas fa-dollar-sign">Manage Payments</i></a></li>
                <li><a href="manage_stock.php"><i class="fas fa-tasks"></i>Manage Stock</a></li>
                <li><a href="reports.php"><i class="fas fa-file-alt"></i> Reports</a></li>
                <li><a href="../logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </div>
   
    <div class="container">
        <h2>Stock</h2>
        <table>
            <thead>
                <tr>
                    <th>Stock ID <i class="fas fa-id-badge"></i></th>
                    <th>Item Name <i class="fas fa-box"></i></th>
                    <th>Quantity <i class="fas fa-sort-amount-up"></i></th>
                    <th>Price <i class="fas fa-dollar-sign"></i></th>
                    <th>Actions <i class="fas fa-cogs"></i></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($stockItems)): ?>
                    <?php foreach ($stockItems as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['stock_id']); ?></td>
                            <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                            <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($item['price']); ?></td>
                            <td>
                                <a href="edit_stock.php?id=<?php echo $item['stock_id']; ?>"><i class="fas fa-edit"></i> Edit</a>
                                <a href="delete_stock.php?id=<?php echo $item['stock_id']; ?>" onclick="return confirm('Are you sure?');"><i class="fas fa-trash"></i> Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No stock items found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
