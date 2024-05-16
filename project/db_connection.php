<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = ""; // Assuming no password is set
$dbname = "project"; // Replace 'project' with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
