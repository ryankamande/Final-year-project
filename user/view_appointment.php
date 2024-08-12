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
$sql = "SELECT aid, time, date, make, model, plateNo, service_type FROM appointment WHERE cid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user']['cid']);
if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}
if (!$stmt->execute()) {
    die('Execute failed: ' . htmlspecialchars($stmt->error));
}
$result = $stmt->get_result();

// Coalescing operator to check for session messages
$message = $_SESSION['success'] ?? $_SESSION['error'] ?? null;

// Unset session messages
unset($_SESSION['success'], $_SESSION['error']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Appointments</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="sidebar">
    <h2>User Dashboard</h2>
    <nav>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="create_appointment.php">Create Appointment</a></li>
            <li><a href="view_appointment.php">View Appointments</a></li>
            <li><a href="../logout.php" class="logout">Logout</a></li>
        </ul>
    </nav>
</div>
<div class="container">
<?= $message ?>

<?php if($result->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Time</th>
                <th>Date</th>
                <th>Vehicle Make</th>
                <th>Vehicle Model</th>
                <th>Plate Number</th>
                <th>Service Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['aid']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['time']) . "</td>";
                    echo "<td>" . htmlspecialchars(date('jS M Y', strtotime($row['date']))) . "</td>";
                    echo "<td>" . htmlspecialchars($row['make']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['model']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['plateNo']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['service_type']) . "</td>";
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
<?php else: ?>
    <h3>No appointments found!</h3>
<?php endif ?>
</div>

<!-- Reschedule Modal -->
<div id="rescheduleModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('rescheduleModal')">&times;</span>
        <h2>Reschedule Appointment</h2>
        <form id="rescheduleForm">
            <input type="hidden" id="rescheduleAid" name="aid">
            <label for="newDate">New Date (YYYY-MM-DD):</label>
            <input type="text" id="newDate" name="date" placeholder="YYYY-MM-DD" pattern="\d{4}-\d{2}-\d{2}" title="Enter date in YYYY-MM-DD format" />
            <label for="newTime">New Time (HH:MM):</label>
            <input type="text" id="newTime" name="time" placeholder="HH:MM" pattern="\d{2}:\d{2}" title="Enter time in HH:MM format" />
            <button type="submit">Reschedule</button>
        </form>
    </div>
</div>

<!-- Pay Invoice Modal -->
<div id="payInvoiceModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('payInvoiceModal')">&times;</span>
        <h2>Pay Invoice</h2>
        <form id="payInvoiceForm">
            <input type="hidden" id="payInvoiceAid" name="aid">
            <label for="amount">Amount:</label>
            <input type="text" id="amount" name="amount">
            <button type="submit">Pay</button>
        </form>
    </div>
</div>

<!-- Cancel Appointment Modal -->
<div id="cancelModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('cancelModal')">&times;</span>
        <h2>Cancel Appointment</h2>
        <form id="cancelForm">
            <input type="hidden" id="cancelAid" name="aid"> <!-- Hidden field for appointment ID -->
            <h1 id="cancelAidDisplay"></h1>
            <p>Are you sure you want to cancel this appointment?</p>
            <button type="submit">Yes, Cancel</button>
            <button type="button" onclick="closeModal('cancelModal')">No, Keep</button>
        </form>
    </div>
</div>

<script>
   function openModal(modalId, aid) {
    // Construct the full ID for the modal and the hidden input field
    const modalElement = document.getElementById(modalId);
    const hiddenInput = document.getElementById(modalId + 'Aid');
    
    // Display the modal
    modalElement.style.display = "block";
    
    // Set the value of the hidden input field with the appointment ID
    if (hiddenInput) {
        hiddenInput.value = aid;
    }

    // Special handling for cancelModal
    if (modalId === 'cancelModal') {
        const cancelAidDisplay = document.getElementById('cancelAidDisplay');
        if (cancelAidDisplay) {
            cancelAidDisplay.textContent = "Appointment ID: " + aid;
        }
    }
}
//it wont be displayed by default when you load the page

//Hides the modal to make the element not to be displayed by using css style none
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = "none";
    }

    // Hides the modal to make the element not to be displayed by using css style none
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = "none";
        }
    }

    // AJAX for rescheduling an appointment
    $('#rescheduleForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'reschedule_appointment.php',
            type: 'POST',
            data: $(this).serialize(), // Serialize the form data
            success: function(response) {
                alert('Appointment rescheduled successfully.');
                closeModal('rescheduleModal');
            },
            error: function() {
                alert('An error occurred while rescheduling the appointment.');
            }
        });
    });

   // AJAX for canceling an appointment
$('#cancelForm').on('submit', function(e) {
    e.preventDefault(); // Prevent the default form submission
    
    // Get the appointment ID from the cancelAidDisplay element
    const cancelAidDisplay = $('#cancelAidDisplay').text();
    const aid = cancelAidDisplay.replace('Appointment ID: ', '').trim();
    
    $.ajax({
        url: 'cancel_appointment.php',
        type: 'POST',
        data: { aid: aid }, // Send the appointment ID
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                alert(response.message);
                closeModal('cancelModal');
                location.reload(); 
            } else {
                alert(response.message);
            }
        },
        error: function() {
            alert('An error occurred while canceling the appointment.');
        }
    });
});
</script>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
