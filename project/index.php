<?php
// Function to fetch data from Google Sheets in CSV format using cURL
function fetchDataFromGoogleSheets($url) {
    // Initialize cURL session
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects

    // Execute cURL request
    $response = curl_exec($ch);

    // Check for errors
    if ($response === false) {
        die("Failed to fetch data from Google Sheets: " . curl_error($ch));
    }

    // Close cURL session
    curl_close($ch);

    return $response;
}

// Specify the URL of the publicly shared Google Sheets document
$sheetUrl = "https://docs.google.com/spreadsheets/d/1W5_9yDTQZSzTL2Es-xHLV0mtJuBpkKPXzuVNmrOurQA/export?format=csv&id=1W5_9yDTQZSzTL2Es-xHLV0mtJuBpkKPXzuVNmrOurQA";

// Fetch data from Google Sheets in CSV format
$sheetData = fetchDataFromGoogleSheets($sheetUrl);

// Parse CSV data
$records = explode(PHP_EOL, trim($sheetData)); // Split data into rows
$headers = str_getcsv(array_shift($records)); // Extract headers
$data = array_map('str_getcsv', $records); // Parse CSV rows

// Get the last record
$lastRecord = end($data);

// Connect to MySQL database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute SQL INSERT query
$sql = "INSERT INTO `usage` (`" . implode("`,`", $headers) . "`) VALUES ('" . implode("','", array_map(array($conn, 'real_escape_string'), $lastRecord)) . "')";

if ($conn->query($sql) === TRUE) {
    // Data inserted successfully, create JavaScript alert and redirect
    echo "<script>alert('Data from sensors are saved');</script>";
    echo "<script>window.location.href = 'graph.php';</script>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close MySQL connection
$conn->close();
?>
