<?php
// Database configuration parameters
$host = "localhost";
$user = "root";
$pass = ""; 
$dbname = "myfoodmart";

// Establish the connection
$conn = mysqli_connect($host, $user, $pass, $dbname);

// Error handling if the connection fails
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>