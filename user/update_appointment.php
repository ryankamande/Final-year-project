<!-- update_appointment.php -->
<?php
require '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $aid = $_POST['aid'];
    $time = $_POST['time'];
    $date = $_POST['date'];
    $plateNo = $_POST['plateNo'];
    

    $sql = "UPDATE appointment SET time='$time', date='$date', plateNo='$plateNo', WHERE aid='$aid'";

    if (mysqli_query($conn, $sql)) {
        echo "Appointment updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Appointment</title>
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css">
</head>
<body>
    <form method="post" action="">
        <h2>Update Appointment</h2>
        <label for="aid">Appointment ID:</label>
        <input type="number" id="aid" name="aid" required>
        <label for="time">Time:</label>
        <input type="time" id="time" name="time" required>
        <label for="date">Date:</label>
        <input type="date" id="date" name="date" required>
        <label for="plateNo">Plate Number:</label>
        <input type="text" id="plateNo" name="plateNo" required>
        <button type="submit">Update Appointment</button>
    </form>
</body>
</html>
