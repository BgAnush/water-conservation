<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    // Redirect to the login page if not logged in
    header('Location: login.php');
    exit();
}

// If user is logged in, retrieve the email from the session
$email = $_SESSION['email'];

// Database connection
$conn = mysqli_connect("localhost", "root", "", "project");

// Retrieve user information from the database based on the email
$query = "SELECT * FROM users WHERE email='$email'";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) > 0) {
    // Fetch user data
    $user = mysqli_fetch_assoc($result);
    $username = $user['username'];
} else {
    // Redirect to login page if user data not found
    header('Location: login.php');
    exit();
}
?>
