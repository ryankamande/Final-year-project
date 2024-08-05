<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] == 'admin') {
    header('Location: ../login.php');
    exit;
}

include '../config.php';
include '../functions.php';

// Fetch customer data from the database
$customers = [];
$query = "SELECT * FROM customer"; // Adjust the table name as necessary
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $customers[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Customers</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <h1>Manage Customers</h1>
        <nav>
            <ul>
                <li><a href="employee_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="../logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <h2>Customers</h2>
        <table>
            <thead>
                <tr>
                    <th>Name <i class="fas fa-user"></i></th>
                    <th>Email <i class="fas fa-envelope"></i></th>
                    <th>Phone <i class="fas fa-phone"></i></th>
                    <th>Address <i class="fas fa-map-marker-alt"></i></th>
                    <th>Actions <i class="fas fa-cogs"></i></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($customers)): ?>
                    <?php foreach ($customers as $customer): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($customer['name']); ?></td>
                            <td><?php echo htmlspecialchars($customer['email']); ?></td>
                            <td><?php echo htmlspecialchars($customer['mobile']); ?></td>
                            <td><?php echo htmlspecialchars($customer['address']); ?></td>
                            <td>
                                <a href="edit_customer.php?id=<?php echo $customer['id']; ?>"><i class="fas fa-edit"></i> Edit</a>
                                <a href="delete_customer.php?id=<?php echo $customer['id']; ?>" onclick="return confirm('Are you sure?');"><i class="fas fa-trash"></i> Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No customers found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
