<?php
// Start session
session_start();

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

// Include database connection
include 'db_connection.php';

// Fetch user details from the session
$email = $_SESSION['email'];
$query_user = "SELECT id, username FROM users WHERE email = ?";
$stmt_user = $conn->prepare($query_user);
$stmt_user->bind_param("s", $email);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

// Fetch user details
if ($result_user->num_rows > 0) {
    $row_user = $result_user->fetch_assoc();
    $id = $row_user['id'];
    $username = $row_user['username'];
} else {
    echo "User not found.";
    exit;
}

// Fetch usage information from the info table
$query_info = "SELECT shower, dishwashing, laundry, flushes, totalusage, totalbill FROM info WHERE id = ?";
$stmt_info = $conn->prepare($query_info);
$stmt_info->bind_param("i", $id);
$stmt_info->execute();
$result_info = $stmt_info->get_result();

// Initialize variables to store usage details
$shower_total = 0;
$dishwashing_total = 0;
$laundry_total = 0;
$flushes_total = 0;
$total_usage = 0;
$total_bill = 0;

// Iterate through each record and calculate totals
while ($row_info = $result_info->fetch_assoc()) {
    $shower_total += $row_info['shower'];
    $dishwashing_total += $row_info['dishwashing'];
    $laundry_total += $row_info['laundry'];
    $flushes_total += $row_info['flushes'];
    $total_usage += $row_info['totalusage'];
    $total_bill += $row_info['totalbill'];
}

// Return the usage details as JSON
echo json_encode(array(
    'shower_total' => $shower_total,
    'dishwashing_total' => $dishwashing_total,
    'laundry_total' => $laundry_total,
    'flushes_total' => $flushes_total,
    'total_usage' => $total_usage,
    'total_bill' => $total_bill,
));
?>
