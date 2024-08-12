<?php
include 'config.php';
//  condition in PHP is used to check if the current request method is a POST request. 
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

    // if ($stmt->execute()) {
    //     echo "Registration successful.";
    // } else {
    //     echo "Error: " . $stmt->error;
    // }

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
</head>
<body>
    <header>
        <h1>Employee/Admin Registration</h1>
    </header>
    <div class="container">
        <form method="POST" action="employee_registration.php">
            <label for="name"> Name:</label>
            <input type="text" id="name" name="name"  ><br>
            
            <label for="mobile"> Mobile:</label>
            <input type="text" id="mobile" name="mobile"  ><br>
            
            <label for="DOB"> Date of Birth:</label>
            <input type="text" id="DOB" name="DOB" placeholder="YYYY-MM-DD" />
            
            <label for="type"> Type:</label>
            <select id="type" name="type"  >
                <option value="Admin">Admin</option>
                <option value="Mechanic">Mechanic</option>
            </select><br>
            
            <label for="address"> Address:</label>
            <input type="text" id="address" name="address"  ><br>
            
            <label for="username"> Username:</label>
            <input type="text" id="username" name="username"  ><br>
            
            <label for="password"> Password:</label>
            <input type="password" id="password" name="password"  ><br>
            
                    
            <button type="submit"><i class="fas fa-user-plus"></i> Register</button>
        </form>
    </div>
    <div class="success-message">
        Registration successful.
    </div>
</body>
</html>
