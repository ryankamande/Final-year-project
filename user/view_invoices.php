<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

include '../config.php';
include '../functions.php';

// Fetch pending payments for the logged-in user
$userId = $_SESSION['user']['cid'];


$pendingPaymentsQuery = "
    SELECT p.payId, a.date AS appointment_date, p.total, p.status
    FROM payment p
    JOIN appointment a ON p.plateNo = a.plateNo
    WHERE p.cid = ? AND p.status = 'pending'
";
$stmt = $conn->prepare($pendingPaymentsQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$pendingPayments = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $paymentId = $_POST['payment_id'];
    $paymentAmount = sanitizeInput($_POST['payment_amount']);
    
    // Update payment status to 'completed'
    $updatePaymentQuery = "UPDATE payment SET total = ?, status = 'completed' WHERE payId = ?";
    $stmt = $conn->prepare($updatePaymentQuery);
    $stmt->bind_param("di", $paymentAmount, $paymentId);
    
    if ($stmt->execute()) {
        echo "Payment processed successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Make Payment</title>
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
                <li><a href="view_vehicle_status.php"><i class="fas fa-car"></i> View Vehicle Status</a></li>
                <li><a href="payments.php"><i class="fas fa-credit-card"></i> Make Payment</a></li>
                <li><a href="update_appointment.php"><i class="fas fa-user-edit"></i> Update Details</a></li>
                <li><a href="delete_appointment.php"><i class="fas fa-calendar-times"></i> Delete Appointments</a></li>
                <li><a href="../logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </div>
    <div class="main-content">
        <header>
            <h2>Pending Payments</h2>
        </header>
        <div class="container">
            <?php if (!empty($pendingPayments)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Appointment Date <i class="fas fa-calendar-alt"></i></th>
                            <th>Amount <i class="fas fa-dollar-sign"></i></th>
                            <th>Status <i class="fas fa-info-circle"></i></th>
                            <th>Action <i class="fas fa-cogs"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pendingPayments as $payment): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($payment['appointment_date']); ?></td>
                                <td><?php echo htmlspecialchars($payment['total']); ?></td>
                                <td><?php echo htmlspecialchars($payment['status']); ?></td>
                                <td>
                                    <form method="post" action="payments.php">
                                        <input type="hidden" name="payment_id" value="<?php echo htmlspecialchars($payment['payId']); ?>">
                                        <input type="number" name="payment_amount" step="0.01" placeholder="Amount" required>
                                        <button type="submit"><i class="fas fa-money-check-alt"></i> Pay</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No pending payments.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
