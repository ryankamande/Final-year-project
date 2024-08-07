<?php
  if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}


include '../config.php';
include '../utils.php';

// Check if the customer is logged in
if (!isset($_SESSION['user'])) {
    Utils::redirect_to('../login.php');
}

// Fetch all appointments
$sql = "SELECT aid, time, date, plateNo FROM appointment where cid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i",  $_SESSION['user']['cid']);
if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}
if (!$stmt->execute()) {
    die('Execute failed: ' . htmlspecialchars($stmt->error));
}
$result = $stmt->get_result();

// coalescing operator `??`
// checks if a variable exists and is not null,
// and if it doesn't, it returns a default value
$message = $_SESSION['success'] ?? $_SESSION['error'] ?? null;

// `unset()` function destroys a variable. Once a variable is unset, it's no longer accessible
unset($_SESSION['success'], $_SESSION['error']);

?>

<!DOCTYPE html>
<html>
<head>
    <title>View Appointments</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Basic styling for modals */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgb(0,0,0); 
            background-color: rgba(0,0,0,0.4); 
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 40%; 
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="sidebar">
    <h2>User Dashboard</h2>
    <nav>
        <ul>
            <li><a href="dashboard.php"><i class="class fas fa-tachometer-alt"></i>Dashboard</a></li>
            <li><a href="create_appointment.php"><i class="fas fa-calendar-plus"></i> Create Appointment</a></li>
            <li><a href="view_appointment.php"><i class="fas fa-calendar-check"></i> View Appointments</a></li>
            <li><a href="../logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>
</div>
<div class="container">
<?= $message ?>

<?php if($result->num_rows > 0):?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Time</th>
                <th>Date</th>
                <th>Plate Number</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['aid']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['time']) . "</td>";
                    echo "<td>" . htmlspecialchars( date('jS M Y', strtotime($row['date']))) . "</td>";
                    echo "<td>" . htmlspecialchars($row['plateNo']) . "</td>";
                    echo "<td>";
                    echo "<button class='btn' onclick='openModal(\"rescheduleModal\", \"" . htmlspecialchars($row['aid']) . "\")'><i class='fas fa-calendar-edit'></i> Reschedule</button> | ";
                    echo "<button class='btn' onclick='openModal(\"payInvoiceModal\", \"" . htmlspecialchars($row['aid']) . "\")'><i class='fas fa-credit-card'></i> Pay Invoice</button> | ";
                    echo "<button class='btn' onclick='openModal(\"cancelModal\", \"" . htmlspecialchars($row['aid']) . "\")'><i class='fas fa-trash'></i> Cancel</button>";
                    echo "</td>";
                    echo "</tr>";
                }
            
            ?>
        </tbody>
    </table>
    <?php else :?>
        <h3>No appointments found!</h3>
        <?php endif?>
</div>

<!-- Reschedule Modal -->
<div id="rescheduleModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('rescheduleModal')">&times;</span>
        <h2>Reschedule Appointment</h2>
        <form method="post" action="reschedule_appointment.php">
            <input type="hidden" id="rescheduleAid" name="aid">
            <label for="newDate">New Date:</label>
            <input type="date" id="newDate" name="date" required>
            <label for="newTime">New Time:</label>
            <input type="time" id="newTime" name="time" required>
            <button type="submit">Reschedule</button>
        </form>
    </div>
</div>

<!-- Pay Invoice Modal -->
<div id="payInvoiceModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('payInvoiceModal')">&times;</span>
        <h2>Pay Invoice</h2>
        <form method="post" action="pay_invoice.php">
            <input type="hidden" id="payInvoiceAid" name="aid">
            <label for="amount">Amount:</label>
            <input type="text" id="amount" name="amount" required>
            <button type="submit">Pay</button>
        </form>
    </div>
</div>

<!-- Cancel Appointment Modal -->
<div id="cancelModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('cancelModal')">&times;</span>
        <h2>Cancel Appointment</h2>
        <form method="post" action="cancel_appointment.php">
            <input type="hidden" id="cancelAid" name="aid">
            <p>Are you sure you want to cancel this appointment?</p>
            <button type="submit">Yes, Cancel</button>
            <button type="button" onclick="closeModal('cancelModal')">No, Keep</button>
        </form>
    </div>
</div>

<script>
    function openModal(modalId, aid) {
        document.getElementById(modalId).style.display = "block";
        document.getElementById(modalId + 'Aid').value = aid;
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = "none";
        }
    }
</script>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
