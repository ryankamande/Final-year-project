<!-- config.php -->
<?php

//database connection details
$servername = "localhost";
$username = "Drexx";
$password = "@Ryan4404";
$dbname = "garage_management_system";

// Create connection
//conn is variable which holds mysqli connection
$conn = mysqli_connect($servername, $username, $password, $dbname);// parameters being passed to the db to be passed to my sqli library

// check connection if successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>
