<?php
// Connect to MySQL database
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$database = "project"; // Name of your MySQL database

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$name = $_POST['name'];
$email = $_POST['email'];
$feedback = $_POST['feedback'];

// Validate and sanitize input (you can add more validation as per your requirements)
$name = htmlspecialchars(trim($name));
$email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
$feedback = htmlspecialchars(trim($feedback));

// Prepare SQL statement to insert data into database
$stmt = $conn->prepare("INSERT INTO feedback_table (name, email, feedback) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $feedback);

// Execute the prepared statement
if ($stmt->execute()) {
    // Redirect back to the feedback form page with a success message
    header("Location: feedback.html?success=true");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

// Close the statement and database connection
$stmt->close();
$conn->close();
?>
