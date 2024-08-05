<!-- create_appointment.php -->
<?php
include '../config.php';
include '../functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $time = $_POST['time'];
    $date = $_POST['date'];
    $plateNo = $_POST['plateNo'];
    $APPID = uniqid('APP');

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Insert the appointment
        $stmt = $conn->prepare("INSERT INTO appointment (time, date, plateNo, APPID) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $time, $date, $plateNo, $APPID);
        $stmt->execute();
        $stmt->close();

     

        // Commit transaction
        $conn->commit();

        echo "Appointment booked and vehicle status updated.";
    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Appointment</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>
<body>
    <form method="post" action="">
        <h2>Create Appointment</h2>
        <label for="time">Time:</label>
        <input type="time" id="time" name="time" required>
        <label for="date">Date:</label>
        <input type="date" id="date" name="date" required>
        <label for="plateNo">Plate Number:</label>
        <input type="text" id="plateNo" name="plateNo" required>
        <button type="submit">Create Appointment</button>
    </form>
</body>
</html>
