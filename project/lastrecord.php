<?php
// Start session
session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

// Include database connection
include 'db_connection.php';

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
    $userId = $row_user['id'];
    $username = $row_user['username'];
} else {
    echo "User not found.";
    exit;
}

// Fetch the last record from the info table
$query_last_record = "SELECT * FROM info WHERE id = ? ORDER BY id DESC LIMIT 1";
$stmt_last_record = $conn->prepare($query_last_record);
$stmt_last_record->bind_param("i", $userId);
$stmt_last_record->execute();
$result_last_record = $stmt_last_record->get_result();

// Fetch the last record
if ($result_last_record->num_rows > 0) {
    $row_last_record = $result_last_record->fetch_assoc();
    // Fetch the specific fields you need from the last record
    $shower = $row_last_record['shower'];
    $dishwashing = $row_last_record['dishwashing'];
    $laundry = $row_last_record['laundry'];
    $flushes = $row_last_record['flushes'];
    // Add more fields as needed
} else {
    echo "No record found.";
    exit;
}

// Return the last record data as JSON
echo json_encode(array(
    'shower' => $shower,
    'dishwashing' => $dishwashing,
    'laundry' => $laundry,
    'flushes' => $flushes
    // Add more fields as needed
));

// Close statements and database connection
$stmt_user->close();
$stmt_last_record->close();
$conn->close();
?>
