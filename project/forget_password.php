<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "", "project");

$email = $_POST['email'];
$new_password = $_POST['new_password'];

$query = "UPDATE users SET password=? WHERE email=?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ss", $new_password, $email);
$result = mysqli_stmt_execute($stmt);

if($result) {
    echo "<script>alert('Password updated successfully. Now you can login with the new password.');</script>";
    echo "<script>window.location.href = 'login.html';</script>";
} else {
    echo "<script>alert('Failed to update password. Please try again.');</script>";
    echo "<script>window.location.href = 'register.html';</script>";
}
?>
