<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "", "project");

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

// Check if the email already exists in the database
$query_check_email = "SELECT * FROM users WHERE email=?";
$stmt_check_email = mysqli_prepare($conn, $query_check_email);
mysqli_stmt_bind_param($stmt_check_email, "s", $email);
mysqli_stmt_execute($stmt_check_email);
$result_check_email = mysqli_stmt_get_result($stmt_check_email);

if(mysqli_num_rows($result_check_email) > 0) {
    // If email already exists, display alert and redirect to login page
    echo "<script>alert('Email id is already registered.'); window.location.href = 'login.html';</script>";
} else {
    // If email doesn't exist, proceed with registration
    $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $password);
    $result = mysqli_stmt_execute($stmt);

    if($result) {
        // Registration successful, display alert and redirect to login page
        echo "<script>alert('Registration successful. You can now login.'); window.location.href = 'login.html';</script>";
    } else {
        // If registration fails, display alert
        echo "<script>alert('Registration Failed !!. Please try again or Use Forgot Password Option.');</script>";
    }
}
?>
