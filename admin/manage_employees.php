<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] == 'admin') {
    header('Location: ../login.php');
    exit;
}

include '../config.php'; 

// Fetch employees from the database
$query = "SELECT * FROM employee";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Employees</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <h1>Manage Employees</h1>
        <nav>
            <ul>
                <li><a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="../logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </header>
    <div class="container">
        <h2>Employees</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['eid'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['username'] . "</td>";
                        echo "<td>" . $row['type'] . "</td>";
                        echo '<td>
                                <a href="edit_employee.php?id=' . $row['eid'] . '"><i class="fas fa-edit"></i> Edit</a>
                                <a href="delete_employee.php?id=' . $row['eid'] . '"><i class="fas fa-trash-alt"></i> Delete</a>
                              </td>';
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No employees found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php
$conn->close();
?>
