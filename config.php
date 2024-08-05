<!-- config.php -->
<?php

//database connection details
$servername = "localhost";
$username = "Drexx";
$password = "@Ryan4404";
$dbname = "garage_management_system";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
