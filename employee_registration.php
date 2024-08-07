<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mobile = $_POST['mobile'];
    $DOB = $_POST['DOB'];
    $type = $_POST['type'];
    $address = $_POST['address'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $name = $_POST['name'];
    

    $stmt = $conn->prepare("INSERT INTO employee (mobile, DOB, type, address, username, password, name) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssisss", $mobile, $DOB, $type, $address, $username, $password, $name);

    if ($stmt->execute()) {
        echo "Registration successful.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    // echo "$mobile, $DOB, $type, $address, $username, $password, $name";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee/Admin Registration</title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <h1>Employee/Admin Registration</h1>
    </header>
    <div class="container">
        <form method="POST" action="employee_registration.php">
            <label for="name"><i class="fas fa-user"></i> Name:</label>
            <input type="text" id="name" name="name" required><br>
            
            <label for="mobile"><i class="fas fa-phone"></i> Mobile:</label>
            <input type="text" id="mobile" name="mobile" required><br>
            
            <label for="DOB"><i class="fas fa-calendar-alt"></i> Date of Birth:</label>
            <input type="date" id="DOB" name="DOB" required><br>
            
            <label for="type"><i class="fas fa-user-tag"></i> Type:</label>
            <select id="type" name="type" required>
                <option value="Admin">Admin</option>
                <option value="Mechanic">Mechanic</option>
            </select><br>
            
            <label for="address"><i class="fas fa-home"></i> Address:</label>
            <input type="text" id="address" name="address" required><br>
            
            <label for="username"><i class="fas fa-user-circle"></i> Username:</label>
            <input type="text" id="username" name="username" required><br>
            
            <label for="password"><i class="fas fa-lock"></i> Password:</label>
            <input type="password" id="password" name="password" required><br>
            
                    
            <button type="submit"><i class="fas fa-user-plus"></i> Register</button>
        </form>
    </div>
</body>
</html>
