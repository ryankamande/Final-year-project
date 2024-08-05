<?php
// view_vehicle_status.php
include '../config.php';
include '../functions.php';

session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit;
}

$statuses = [];
$query = "SELECT * FROM vehicle_status";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $statuses[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Vehicle Status</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <h1>Vehicle Status</h1>
    </header>
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>Status ID <i class="fas fa-id-badge"></i></th>
                    <th>Date <i class="fas fa-calendar-alt"></i></th>
                    <th>Terminal <i class="fas fa-terminal"></i></th>
                    <th>Description <i class="fas fa-info-circle"></i></th>
                    <th>Customer ID <i class="fas fa-user"></i></th>
                    <th>Plate No <i class="fas fa-car"></i></th>
                    <th>Service ID <i class="fas fa-wrench"></i></th>
                    <th>Vehicle Mileage <i class="fas fa-tachometer-alt"></i></th>
                    <th>Added Time <i class="fas fa-clock"></i></th>
                    <th>Quantity <i class="fas fa-sort-amount-up"></i></th>
                    <th>Time to Complete <i class="fas fa-hourglass-half"></i></th>
                    <th>Vehicle ID <i class="fas fa-car-side"></i></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($statuses)): ?>
                    <?php foreach ($statuses as $status): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($status['statusId']); ?></td>
                            <td><?php echo htmlspecialchars($status['date']); ?></td>
                            <td><?php echo htmlspecialchars($status['terminal']); ?></td>
                            <td><?php echo htmlspecialchars($status['decription']); ?></td>
                            <td><?php echo htmlspecialchars($status['cid']); ?></td>
                            <td><?php echo htmlspecialchars($status['plateNo']); ?></td>
                            <td><?php echo htmlspecialchars($status['sid']); ?></td>
                            <td><?php echo htmlspecialchars($status['vehicle_mileage']); ?></td>
                            <td><?php echo htmlspecialchars($status['addedTime']); ?></td>
                            <td><?php echo htmlspecialchars($status['qty']); ?></td>
                            <td><?php echo htmlspecialchars($status['timeToComplete']); ?></td>
                            <td><?php echo htmlspecialchars($status['VID']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="12">No vehicle status records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
