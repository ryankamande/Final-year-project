<?php


include '../config.php'; // Assuming your config.php sets up the $conn variable

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jobId = $_POST['job_id'];
    $amount = $_POST['amount'];

    // Fetch customer details
    $query = "SELECT a.plateNo, c.email FROM job j JOIN appointment a ON j.aid = a.APPID JOIN customer c ON a.plateNo = c.plateNo WHERE j.jobId = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $jobId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $customer = $result->fetch_assoc();
        $customerEmail = $customer['email'];
        $plateNo = $customer['plateNo'];

        // Insert invoice into the payment table
        $insertQuery = "INSERT INTO payment (date, subtotal, total, cid, plateNo, time, payId, status) VALUES (NOW(), ?, ?, (SELECT cid FROM customer WHERE plateNo = ?), ?, UUID(), 'pending')";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("dss", $amount, $amount, $plateNo, $plateNo);

        if ($stmt->execute()) {
            // Prepare email
            $subject = "Invoice for Vehicle Service";
            $message = "Dear Customer,\n\nAn invoice of $amount has been generated for your vehicle service. Please log in to your account to view and pay the invoice.\n\nBest Regards,\nThe Team";
            $headers = "From: no-reply@example.com\r\n";
            
            // Send email to customer
            mail($customerEmail, $subject, $message, $headers);
            
            echo "Invoice sent successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "No customer found for the specified job ID.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Send Invoice</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <h2>Admin Dashboard</h2>
        <nav>
            <ul>
                <li><a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="send_invoice.php"><i class="fas fa-file-invoice"></i> Send Invoice</a></li>
                <li><a href="../logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>
    </div>
    <div class="main-content">
        <header>
            <h1>Send Invoice</h1>
        </header>
        <div class="container">
            <form method="post" action="send_invoice.php">
                <div>
                    <label for="job_id">Job ID:</label>
                    <input type="text" name="job_id" required>
                </div>
                <div>
                    <label for="amount">Amount:</label>
                    <input type="number" name="amount" step="0.01" required>
                </div>
                <button type="submit">Send Invoice</button>
            </form>
        </div>
    </div>
</body>
</html>
