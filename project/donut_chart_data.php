<?php
session_start();

$servername = "localhost";
$username = "root"; // Change this to your MySQL username
$password = ""; // Change this to your MySQL password
$dbname = "project";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the email from the session
$email = $_SESSION['email'];

// Prepare and execute the SQL statement to retrieve user's id and username based on email
$stmt = $conn->prepare("SELECT id, username FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();

// Check for errors in SQL execution
if ($stmt->errno) {
    echo "Error executing query: " . $stmt->error;
    exit();
}

$stmt->store_result();

// Check if a user with the provided email exists
if ($stmt->num_rows > 0) {
    // Bind the result variables
    $stmt->bind_result($id, $username);

    // Fetch the result
    $stmt->fetch();

    // Close the statement
    $stmt->close();

    // Fetch the last seven records of total usage from the info table
    $sql = "SELECT totalbill FROM info WHERE username = ? ORDER BY id DESC LIMIT 7";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();

    // Check for errors in SQL execution
    if ($stmt->errno) {
        echo "Error executing query: " . $stmt->error;
        exit();
    }

    $result = $stmt->get_result();

    // Check for errors in fetching result
    if (!$result) {
        echo "Error fetching result: " . $conn->error;
        exit();
    }

    // Fetch the data
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row['totalbill'];
    }

    // Close the statement
    $stmt->close();

    // Close connection
    $conn->close();

    // Send JSON response
    echo json_encode($data);
} else {
    // If user not found, handle it here (e.g., redirect or error message)
    // For example:
    echo "User not found.";
    exit();
}
?>
