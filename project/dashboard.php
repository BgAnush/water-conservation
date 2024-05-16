<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = ""; // Assuming no password is set
$dbname = "project"; // Replace 'project' with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch username from the database
$sql = "SELECT username FROM users"; // Modify 'users' to your actual table name
$result = $conn->query($sql);

$username = ""; // Initialize username variable

if ($result->num_rows > 0) {
    // Output data of the first row (assuming there's only one user)
    $row = $result->fetch_assoc();
    $username = $row["username"];
} else {
    echo "0 results";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Your Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Styling for the navbar and sidebar */
        .navbar {
            background-color: #34a0a4;
            color: white;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .transparent-button {
            font-size: 24px;
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.7);
            cursor: pointer;
            transition: color 0.3s;
            margin-left: 10px;
        }

        .transparent-button:hover {
            color: rgba(255, 255, 255, 1);
        }

        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            left: -250px;
            padding-top: 60px;
            transition: left 0.3s;
            text-align: center;
            border-top-right-radius: 30px;
            border-bottom-right-radius: 30px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.2);
            background-image: linear-gradient(to bottom, #F5F9F9, #a6f8fa);
            z-index: 1;
            display: flex;
            flex-direction: column;
            justify-content: center; /* Center content vertically */
            align-items: center; /* Center content horizontally */
        }

        .logo {
            width: 180px; /* Adjust according to your logo size */
            height: 180px; /* Ensure logo container is square to create a circle */
            margin-bottom: 10px; /* Adjust spacing as needed */
            background-color: rgba(255, 255, 255, 0.001); /* Adjust the alpha channel (last value) to control transparency */
            border-radius: 110%; /* Make the logo container round */
            overflow: hidden; /* Hide any content that overflows the container */
            display: flex; /* Center the logo */
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
        }

        .sidebar a {
            display: block;
            padding: 20px 0;
            text-decoration: none;
            font-size: 20px;
            color: #5C5F5F;
            transition: all 0.3s;
        }

        .sidebar a:hover {
            background-color: #004d80;
            color: #fff;
        }

        /* Styling for the charts */
        .graph-container {
            max-width: 800px;
            margin: 20px auto;
            text-align: center;
        }

        .chart-box {
            margin-bottom: 30px;
            border: 2px solid rgba(255, 255, 255, 0.5); /* Transparent border */
            border-radius: 10px;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.1); /* Transparent background */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            color: #fff; /* Adjust text color for visibility */
        }

        .chart-container {
            width: 100%;
            height: 200px;
        }

        .chart {
            width: 100%;
            height: 100%;
        }

        /* Styling for the usage boxes */
        .usage-box {
            width: 150px;
            height: 150px;
            padding: 10px;
            margin: 20px;
            text-align: center;
            display: inline-block;
            border-radius: 20px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.7);
            position: relative;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            background-color: rgba(255, 255, 255, 0.2); /* Adjust the alpha channel to control transparency */
        }

        .usage-box:before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, #fff, #f0f0f0);
            opacity: 0.2;
            transform: rotateY(-45deg) translateX(-50%);
        }

        .usage-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.5);
        }

        .material-icon {
            font-size: 48px;
            margin-bottom: 20px;
            color: #fff;
        }

        /* Different colors for usage boxes */
        .shower-box {
            background-color: #F9E79F;
        }

        .dishwashing-box {
            background-color: #3498db;
        }

        .laundry-box {
            background-color: #EDBB99 ;
        }

        .flushes-box {
            background-color: #e67e22;
        }

        .total-usage-box {
            background-color: #f1c40f;
        }

        .total-bill-box {
            background-color: #e74c3c;
        }

        /* Body styling */
        body {
            background-image: url('wall2.jpg');
            background-repeat: no-repeat;
            background-size: 100% 100%;
            margin: 0;
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <button class="transparent-button" onclick="toggleSidebar()">☰</button>
    <span>Hey there, Dashboard Dynamo!<span id="username"><?php echo htmlspecialchars($username); ?></span></span>
</div>


<!-- Welcome message -->
<h1 style="text-align: center;color: #fff;"><span >Welcome <?php echo $username; ?></span>!</h1>

<!-- Usage Boxes -->
<div class="usage-boxes" id="usage-boxes">
    <div class="usage-box" style="background-color: #FED766;">
        <h3 style="margin: 0;">Usage Box 1</h3>
        <p style="margin: 0;">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
    </div>
    <div class="usage-box" style="background-color: #D53F8C;">
        <h3 style="margin: 0;">Usage Box 2</h3>
        <p style="margin: 0;">Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
    </div>
</div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <img src='2.jpeg' alt="Logo" class="logo">
    <a href="#" class="active">Dashboard</a>
    <a href="index.php">Graph</a>
    <a href="logout.php">Logout</a>
    <a href="javascript:void(0)" onclick="toggleSidebar()">⏎</a>
</div>

<!-- Usage Chart -->
<div class="graph-container">
    <div class="chart-box">
        <h2>Usage Pulse</h2>
        <div class="chart-container">
            <canvas id="usage-chart" class="chart"></canvas>
        </div>
    </div>
</div>

<!-- Donut Chart -->
<div class="graph-container">
    <div class="chart-box">
        <h2>Last Record</h2>
        <div class="chart-container">
            <canvas id="donut-chart" class="chart"></canvas>
        </div>
    </div>
</div>

<!-- Bar Chart -->
<div class="graph-container">
    <div class="chart-box">
        <h2>Total Bill Chart</h2>
        <div class="chart-container">
            <canvas id="bar-chart" class="chart"></canvas>
        </div>
    </div>
</div>

<script>
    // Function to toggle sidebar
    function toggleSidebar() {
        var sidebar = document.getElementById('sidebar');
        if (sidebar.style.left === "0px") {
            sidebar.style.left = "-250px";
        } else {
            sidebar.style.left = "0px";
        }
    }

    // Fetch data and populate donut chart
    fetch('lastrecord.php')
        .then(response => response.json())
        .then(data => {
            var ctx = document.getElementById('donut-chart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Shower', 'Dishwashing', 'Laundry', 'Flushes'],
                    datasets: [{
                        label: 'Last Record',
                        data: [data.shower, data.dishwashing, data.laundry, data.flushes],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 206, 86, 0.8)',
                            'rgba(99, 57, 116, 0.8)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(99, 57, 116, 1)'
                        ],
                        borderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false, // Adjust aspect ratio
                    animation: {
                        animateRotate: true,
                        animateScale: true
                    }
                }
            });
        })
        .catch(error => console.error('Error fetching data:', error));

    // AJAX request to fetch data from PHP for usage chart
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'your_php_file.php', true);
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 400) {
            var data = JSON.parse(xhr.responseText);
            createChart(data);
        } else {
            console.error('Error fetching data:', xhr.statusText);
        }
    };
    xhr.onerror = function() {
        console.error('Error fetching data:', xhr.statusText);
    };
    xhr.send();

    function createChart(data) {
        var ctx = document.getElementById('usage-chart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5', 'Day 6', 'Day 7'],
                datasets: [{
                    label: 'Usage',
                    data: data,
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // AJAX request to fetch data from PHP for bar chart
    var xhrBar = new XMLHttpRequest();
    xhrBar.open('GET', 'donut_chart_data.php', true);
    xhrBar.onload = function() {
        if (xhrBar.status >= 200 && xhrBar.status < 400) {
            var data = JSON.parse(xhrBar.responseText);
            createBarChart(data);
        } else {
            console.error('Error fetching data:', xhrBar.statusText);
        }
    };
    xhrBar.onerror = function() {
        console.error('Error fetching data:', xhrBar.statusText);
    };
    xhrBar.send();

    function createBarChart(data) {
        var ctx = document.getElementById('bar-chart').getContext('2d');
        var barChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: Object.keys(data), // Days as labels
                datasets: [{
                    label: 'Total Bill',
                    data: Object.values(data), // Total bill amounts as data
                    backgroundColor: 'rgba(64, 119, 50, 0.5)',
                    borderColor: 'rgba(26, 204, 36, 1)',
                    borderWidth: 3
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Fetch data and populate usage boxes
    fetch('data.php')
        .then(response => response.json())
        .then(data => {
            document.getElementById('username').textContent = data.username;

            document.getElementById('usage-boxes').innerHTML = `
                <div class="usage-box shower-box" style="background-color: #D3F7E0 ;">
                    <div class="material-icon">🚿</div>
                    <div>Shower</div>
                    <div>${data.shower_total}</div>
                </div>
                <div class="usage-box dishwashing-box" style="background-color: #F7B4EE;">
                    <div class="material-icon">🍽️</div>
                    <div>Dishwashing</div>
                    <div>${data.dishwashing_total}</div>
                </div>
                <div class="usage-box laundry-box" style="background-color: #EDBB99 ;">
                    <div class="material-icon">👔</div>
                    <div>Laundry</div>
                    <div>${data.laundry_total}</div>
                </div>
                <div class="usage-box flushes-box" style="background-color: #ABE5B8 ;">
                    <div class="material-icon">🚰</div>
                    <div>Flushes</div>
                    <div>${data.flushes_total}</div>
                </div>
                <div class="usage-box total-usage-box" style="background-color: #D0F378 ;">
                    <div class="material-icon">💧</div>
                    <div>Total Usage</div>
                    <div>${data.total_usage}</div>
                </div>
                <div class="usage-box total-bill-box" style="background-color: #CEC5D4 ;">
                    <div class="material-icon">💵</div>
                    <div>Total Bill</div>
                    <div>${data.total_bill}</div>
                </div>
            `;
        })
        .catch(error => console.error('Error fetching data:', error));

         // Function to toggle sidebar
    function toggleSidebar() {
        var sidebar = document.getElementById('sidebar');
        if (sidebar.style.left === "0px") {
            sidebar.style.left = "-250px";
        } else {
            sidebar.style.left = "0px";
        }
    }

    // Fetch username from db_connection.php
    var xhrUsername = new XMLHttpRequest();
    xhrUsername.open('GET', 'db_connection.php', true);
    xhrUsername.onload = function() {
        if (xhrUsername.status >= 200 && xhrUsername.status < 400) {
            var data = JSON.parse(xhrUsername.responseText);
            document.getElementById('username').textContent = data.username;
            document.getElementById('welcomeMessage').textContent = "Welcome " + data.username;
        } else {
            console.error('Error fetching username:', xhrUsername.statusText);
        }
    };
    xhrUsername.onerror = function() {
        console.error('Error fetching username:', xhrUsername.statusText);
    };
    xhrUsername.send();
</script>

</body>
</html>
