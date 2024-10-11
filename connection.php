<?php
// Database credentials
$servername = "localhost"; // Replace with your server address if needed
$username = "";        // Replace with your MySQL username
$password = "";            // Replace with your MySQL password
$dbname = ""; // Replace with your actual database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
