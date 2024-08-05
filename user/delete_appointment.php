<!-- delete_appointment.php -->
<?php
require '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $aid = $_POST['aid'];

    $sql = "DELETE FROM appointment WHERE aid='$aid'";

    if (mysqli_query($conn, $sql)) {
        echo "Appointment deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Delete Appointment</title>
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css">
</head>
<body>
    <form method="post" action="">
        <h2>Delete Appointment</h2>
        <label for="aid">Appointment ID:</label>
        <input type="number" id="aid" name="aid" required>
        <button type="submit">Delete Appointment</button>
    </form>
</body>
</html>
