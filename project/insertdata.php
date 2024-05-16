<?php
session_start();

$servername = "localhost";
$username = "root"; // Change this to your MySQL username
$password = ""; // Change this to your MySQL password
$dbname = "project";

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    // Redirect the user to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and bind the SQL statement to retrieve user's id and username based on email
$stmt = $conn->prepare("SELECT id, username FROM users WHERE email = ?");
$stmt->bind_param("s", $email);

// Set parameter and execute
$email = $_SESSION['email'];
$stmt->execute();

// Store the result
$stmt->store_result();

// Check if a user with the provided email exists
if ($stmt->num_rows > 0) {
    // Bind the result variables
    $stmt->bind_result($id, $username);

    // Fetch the result
    $stmt->fetch();

    // Calculate total usage
    $shower = $_POST['shower'] ?? 0;
    $dishwashing = $_POST['dishwashing'] ?? 0;
    $laundry = $_POST['laundry'] ?? 0;
    $flushes = $_POST['flushes'] ?? 0;
    $totalUsage = $shower + $dishwashing + $laundry + $flushes;

    // Calculate total bill
    $limit = 0;
    if ($_POST['apartment-type'] == 1) {
        $limit = 250;
    } elseif ($_POST['apartment-type'] == 2) {
        $limit = 400;
    } elseif ($_POST['apartment-type'] == 3) {
        $limit = 600;
    }

    $baseCharge = 500;
    $extraCharge = 0;
    if ($totalUsage > $limit) {
        $extraCharge = min($totalUsage - $limit, 100) * 5.50;
    }
    $excessUsageCharge = $totalUsage > ($limit + 100) ? ($totalUsage - ($limit + 100)) * 7.50 : 0;

    $totalBill = $baseCharge + $extraCharge + $excessUsageCharge;

    // Prepare and bind the SQL statement to insert data into the info table
    $stmt_insert = $conn->prepare("INSERT INTO info (id, username, shower, dishwashing, laundry, flushes, apartment, measures, totalbill, totalusage) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt_insert->bind_param("ssiiiiissi", $id, $username, $shower, $dishwashing, $laundry, $flushes, $apartment_type, $usage_status, $totalBill, $totalUsage);

    // Set parameters and execute
    $shower = $_POST['shower'] ?? 0;
    $dishwashing = $_POST['dishwashing'] ?? 0;
    $laundry = $_POST['laundry'] ?? 0;
    $flushes = $_POST['flushes'] ?? 0;
    $apartment_type = $_POST['apartment-type'] ?? 0;
    $usage_status = $_POST['usage-status'] ?? '';

    $stmt_insert->execute();

    echo "Data inserted successfully.";

    $stmt_insert->close();
} else {
    echo "User not found.";
}

$stmt->close();
$conn->close();
?>
