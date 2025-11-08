<?php
// Database configuration
$host = "localhost";    // Server hostname
$user = "root";         // Database username
$password = "";         // Database password
$database = "mydb";     // Database name

// Create connection
$conn = mysqli_connect($host, $user, $password, $database);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
?>