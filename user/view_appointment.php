<?php
// view_appointment.php
include '../config.php';
include '../functions.php';

// Fetch appointments from the database
$sql = "SELECT aid, time, date, plateNo, APPID FROM appointment";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Appointments</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <h1>Appointments</h1>
    </header>
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
