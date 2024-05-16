<?php
session_start();
// Database connection
$conn = mysqli_connect("localhost", "root", "", "project");

$email = $_POST['email'];
$password = $_POST['password'];

$query = "SELECT * FROM users WHERE email=? AND password=?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ss", $email, $password);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) > 0) {
    $_SESSION['email'] = $email;
    echo "<script>alert('Login successful'); window.location.href = 'dashboard.php';</script>";
} else {
    echo "<script>alert('Invalid email or password. Please try again.');</script>";
    echo "<script>window.history.back();</script>"; // Go back to the previous page
}
?>
