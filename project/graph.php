<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Water Usage Chart and Bill</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.css">
    <style>
        /* Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap');

        /* Colors */
        :root {
            --primary-color: #007bff;
            --secondary-color: #0056b3;
            --danger-color: #dc3545;
            --success-color: #28a745;
            --info-color: #17a2b8;
            --dark-color: #333;
            --light-color: #f8f9fa;
            --gray-color: #6c757d;
        }

        /* Global Styles */
        body {
    font-family: 'Montserrat', sans-serif;
    background-image: url('wall3.jpg');
    background-size: 100% 200%; /* Adjust the image size to cover the entire background */
    background-repeat: no-repeat; /* Prevent the image from repeating */
    background-position: center; /* Center the image */
    margin: 0;
    padding: 0;
}

.container {
    max-width: 800px;
    margin: 20px auto;
    background-color: rgba(255, 255, 255, 0.2); /* Adjust the alpha channel (last value) to control transparency */
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 20px;
    position: relative;
    overflow: hidden;
}

        /* Header Styles */
        h2, h3 {
            color: var(--dark-color);
        }

        /* Form Styles */
        .input-container {
            margin-bottom: 20px;
            text-align: center;
        }

        label {
            font-weight: bold;
            margin-right: 10px;
            color: var(--dark-color);
        }

        select {
            padding: 8px 12px;
            font-size: 16px;
            border: 1px solid var(--gray-color);
            border-radius: 5px;
            background-color: #fff;
        }

        button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: var(--primary-color);
            color: #fff;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: var(--secondary-color);
        }

        button.reset-button {
            background-color: var(--danger-color);
            position: absolute;
            top: 10px;
            right: 10px;
        }

        /* Usage Status Styles */
        .usage-status {
            margin-top: 10px;
            padding: 10px;
            border-radius: 5px;
            font-size: 20px;
            color: var(--dark-color);
        }

        .usage-status.green {
            color: var(--success-color);
        }

        .usage-status.blue {
            color: var(--info-color);
        }

        .usage-status.red {
            color: var(--danger-color);
        }

        /* Bill Container Styles */
        .bill-container {
            margin-top: 20px;
            padding: 20px;
            border-radius: 10px;
            background-color: #f1f1f1;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            color: var(--dark-color);
        }

        /* Chart Styles */
        #usage-chart {
            margin-top: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
<div class="container">
    <div class="input-container">
        <button class="reset-button" onclick="resetForm()">Reset</button>
        <div id="last-record">
            <!-- PHP code here -->
<?php
            // Database connection parameters
            $servername = "localhost"; // Change this to your MySQL server hostname
            $username = "root"; // Change this to your MySQL username
            $password = ""; // Change this to your MySQL password
            $database = "project"; // Change this to your MySQL database name

            // Create connection
            $conn = new mysqli($servername, $username, $password, $database);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Query to get the last record from the usage table
            $sql = "SELECT * FROM `usage` ORDER BY id DESC LIMIT 1"; // Fixed table name with backticks
            $result = $conn->query($sql);

            // Check if the query was successful
            if ($result === false) {
                // Query failed
                echo "Error executing query: " . $conn->error;
            } else {
                // Check if any rows were returned
                if ($result->num_rows > 0) {
                    // Fetch the last record
                    $row = $result->fetch_assoc();
                    echo "<h3><em><strong>Usage table's ultimate tale:</strong></em></h3><br>";
                    echo "Shower: " . $row["shower"] . "<br>";
                    echo "Dishwashing: " . $row["dishwashing"] . "<br>";
                    echo "Laundry: " . $row["laundry"] . "<br>";
                    echo "Flushes: " . $row["flushes"] . "<br>";
                } else {
                    // No rows returned
                    echo "No records found in the usage table.";
                }
            }

            // Close connection
            $conn->close();
            ?>
        </div>
        <div>
            <label for="apartment-type">Apartment Type:</label>
            <select id="apartment-type">
                <option value="1">1 BHK</option>
                <option value="2">2 BHK</option>
                <option value="3">3 BHK</option>
            </select>
        </div>
        <button id="generate-chart-btn">Generate Chart & Bill</button>
        <div id="usage-status" class="usage-status"></div>
        <div class="bill-container" id="bill-container" style="display: none;">
            <h2>Water Bill</h2>
            <div id="bill-details"></div>
        </div>
    </div>
    <div style="width: 100%;">
        <canvas id="usage-chart"></canvas>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
<script>
    var chartInstance = null;

    document.getElementById('generate-chart-btn').addEventListener('click', function () {
        var apartmentType = parseInt(document.getElementById('apartment-type').value);
        if (!apartmentType || apartmentType < 1 || apartmentType > 3) {
            alert("Invalid apartment type selected.");
            return;
        }

        var lastRecord = document.getElementById('last-record').innerHTML;
        var data = lastRecord.split('<br>');
        var usageData = {};
        data.forEach(function (item) {
            var parts = item.split(': ');
            usageData[parts[0].toLowerCase()] = parseInt(parts[1]);
        });

        var totalUsage = usageData.shower + usageData.dishwashing + usageData.laundry + usageData.flushes;

        var limits = {1: {shower: 150, dishwashing: 30, laundry: 45, flushes: 25},
            2: {shower: 240, dishwashing: 60, laundry: 60, flushes: 40},
            3: {shower: 350, dishwashing: 100, laundry: 100, flushes: 50}};
        var limit = {1: 250, 2: 400, 3: 600};
        var baseCharge = {1: 500, 2: 500, 3: 500};
        var extraChargePerUnit = 5.50;
        var excessUsageChargePerUnit = 7.50;

        var extraCharge = 0;
        var excessUsageCharge = 0;
        var totalCharge = baseCharge[apartmentType];

        if (totalUsage > limit[apartmentType]) {
            var extraUnits = Math.min(totalUsage - limit[apartmentType], 100);
            extraCharge = extraUnits * extraChargePerUnit;
            totalCharge += extraCharge;
        }

        if (totalUsage > limit[apartmentType] + 100) {
            var excessUnits = totalUsage - (limit[apartmentType] + 100);
            excessUsageCharge = excessUnits * excessUsageChargePerUnit;
            totalCharge += excessUsageCharge;
        }

        var highUsageCount = 0;
        var highUsageActivities = [];

        Object.keys(limits[apartmentType]).forEach(function(activity) {
            if (limits[apartmentType][activity] < usageData[activity]) {
                highUsageCount++;
                highUsageActivities.push(activity);
            }
        });

        var usageStatus = document.getElementById('usage-status');
        if (highUsageCount == 0) {
            usageStatus.textContent = "Usage is within limits.";
            usageStatus.className = "usage-status green";
        } else if (highUsageCount <= 2) {
            usageStatus.textContent = "Moderate high usage. Measures should be taken to reduce usage in the following activities: " + highUsageActivities.join(", ");
            usageStatus.className = "usage-status blue";
        } else {
            usageStatus.textContent = "High usage. Measures should be taken to reduce usage in the following activities: " + highUsageActivities.join(", ");
            usageStatus.className = "usage-status red";
        }

        var billContainer = document.getElementById('bill-container');
        var billDetails = document.getElementById('bill-details');
        billDetails.innerHTML = `
                <p>Total Usage: ${totalUsage} L</p>
                <p>Base Charge (${limit[apartmentType]} units): ₹${baseCharge[apartmentType].toFixed(2)}</p>
                ${(extraCharge > 0) ? `<p>Extra Charge (${extraUnits} units) at ₹${extraChargePerUnit} per unit: ₹${extraCharge.toFixed(2)}</p>` : ''}
                ${(excessUsageCharge > 0) ? `<p>Excess Usage Charge (${excessUnits} units) at ₹${excessUsageChargePerUnit} per unit: ₹${excessUsageCharge.toFixed(2)}</p>` : ''}
                <h3>Total Bill: ₹${totalCharge.toFixed(2)}</h3>
            `;
        billContainer.style.display = 'block';

        var ctx = document.getElementById('usage-chart').getContext('2d');
        if (chartInstance) {
            chartInstance.destroy();
        }
        chartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Shower', 'Dishwashing', 'Laundry', 'Flushes'],
                datasets: [{
                    label: 'Water Usage (User)',
                    data: [usageData.shower, usageData.dishwashing, usageData.laundry, usageData.flushes],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 4
                }, {
                    label: `Limit for ${apartmentType} BHK`,
                    data: [limits[apartmentType].shower, limits[apartmentType].dishwashing, limits[apartmentType].laundry, limits[apartmentType].flushes],
                    backgroundColor: 'rgba(255, 0, 0, 1)',
                    borderColor: 'rgba(255, 0, 0, 1)',
                    borderWidth: 4
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "insertdata.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                console.log(xhr.responseText);
            }
        };
        var data = "shower=" + usageData.shower + "&dishwashing=" + usageData.dishwashing + "&laundry=" + usageData.laundry + "&flushes=" + usageData.flushes + "&apartment-type=" + apartmentType + "&usage-status=" + usageStatus.textContent;
        xhr.send(data);
    });

    function resetForm() {
        document.getElementById("apartment-type").value = "1";

        if (chartInstance) {
            chartInstance.destroy();
        }

        var billContainer = document.getElementById('bill-container');
        billContainer.style.display = 'none';

        var usageStatus = document.getElementById('usage-status');
        usageStatus.textContent = "";
        usageStatus.className = "usage-status";
    }
</script>
</body>
</html>
