<?php
session_start(); // Start the session at the beginning

include '../config.php';
include '../functions.php';

// Check if the customer is logged in
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] == 'customer') {
    header('Location: ../login.php');
    exit();
}

$customerId = $_SESSION['user']['id']; // Adjust 'id' to match your session key for the customer's ID

// Fetch appointments for the logged-in customer from the database
$sql = "SELECT aid, time, date, plateNo, APPID FROM appointment WHERE cid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customerId);
$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html>
<head>
    <title>View Appointments</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="sidebar">
        <h2>User Dashboard</h2>
        <nav>
            <ul>
                <li><a href="create_appointment.php"><i class="fas fa-calendar-plus"></i> Create Appointment</a></li>
                <li><a href="view_appointment.php"><i class="fas fa-calendar-check"></i> View Appointments</a></li>
                <li><a href="payments.php"><i class="fas fa-credit-card"></i> View Payment History</a></li>
                <li><a href="pay_invoice.php"><i class="fas fa-credit-card"></i>Pay</a></li>
                <li><a href="update_appointment.php"><i class="fas fa-user-edit"></i> Update Details</a></li>
                <li><a href="delete_appointment.php"><i class="fas fa-trash"></i> Delete Appointments</a></li>
                <li><a href="../logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </div>
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Time</th>
                    <th>Date</th>
                    <th>Plate Number</th>
                    <th>APPID</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['aid'] . "</td>";
                        echo "<td>" . $row['time'] . "</td>";
                        echo "<td>" . $row['date'] . "</td>";
                        echo "<td>" . $row['plateNo'] . "</td>";
                        echo "<td>" . $row['APPID'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No appointments found</td></tr>";
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
