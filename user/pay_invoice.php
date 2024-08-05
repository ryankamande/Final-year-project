<?php

include '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $paymentId = $_POST['payment_id'];
    $paymentAmount = $_POST['payment_amount'];

    // Update payment status to 'completed'
    $updateQuery = "UPDATE payment SET total = ?, status = 'completed' WHERE PID = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ds", $paymentAmount, $paymentId);

    if ($stmt->execute()) {
        echo "Payment completed successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pay Invoice</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <h2>Customer Dashboard</h2>
        <nav>
            <ul>
                <li><a href="view_invoices.php"><i class="fas fa-file-invoice"></i> View Invoices</a></li>
                <li><a href="../logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </div>
    <div class="main-content">
        <header>
            <h1>Pay Invoice</h1>
        </header>
        <div class="container">
            <form method="post" action="pay_invoice.php">
                <div>
                    <label for="payment_id">Payment ID:</label>
                    <input type="text" name="payment_id" required>
                </div>
                <div>
                    <label for="payment_amount">Amount:</label>
                    <input type="number" name="payment_amount" step="0.01" required>
                </div>
                <button type="submit">Pay Now</button>
            </form>
        </div>
    </div>
</body>
</html>
