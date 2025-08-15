**💧 WaterConservation – Smart Water Usage Tracking System
📌 Project Overview**

WaterConservation is a smart water usage monitoring and awareness platform that helps households track their daily water consumption in real-time.
Using flow sensors connected to a Raspberry Pi, the system collects water usage data and sends it to the cloud.
A web application (HTML, CSS, JavaScript, PHP) displays the usage as a line graph, compares it with a predefined usage limit, and provides personalized feedback to encourage water conservation.

✨**Features**

📊 Real-time Water Monitoring – Collects water usage data from flow sensors via Raspberry Pi.

☁ Cloud Storage – Stores water usage securely in the cloud for anytime access.

📈 Interactive Line Graph – Visual representation of daily usage trends.

🎯 Usage Limit Tracking – Compare household consumption against a set limit.

🗨 Smart Feedback System:

Above Limit: "⚠ Please reduce your water usage!"

Below Limit: "🎉 Congratulations! You're conserving water!"

Equal to Limit: "🧘 Balanced as a Zen Master – Perfect water usage!"

🔐 User Authentication:

Register – Create an account to track your household.

Login – Secure access to your dashboard.

Forgot Password – Recover account securely.

💵 Bill Estimation Page – Calculate approximate water bill based on usage.

🖥️ **Tech Stack**

Frontend: HTML5, CSS3, JavaScript

Backend: PHP

Database: MySQL (Cloud Hosted)

Hardware: Raspberry Pi + Flow Sensor

Data Visualization: Chart.js (Line Graph)

Cloud Communication: REST APIs

🛠️ **How It Works**

Flow Sensor Data Collection
The Raspberry Pi reads water flow data from the sensor.

Data Upload to Cloud
Data is sent to a cloud server using PHP APIs.

Database Storage
The server stores data in a MySQL database.

Dashboard Display
Users log in to view their water usage in a line graph format.

Limit Checking
The system compares usage with the set household limit and provides feedback.

Bill Calculation
Estimates monthly water bill based on usage trends.


🚀**Getting Started Prerequisites**

Raspberry Pi with Raspbian OS installed

Flow Sensor connected to Raspberry Pi GPIO pins

Web Server (XAMPP, WAMP, or Cloud Hosting with PHP & MySQL)

Internet Connection for cloud sync

**Installation**

**Clone the Repository**

git clone https://github.com/BgAnush/water-conservation.git
cd WaterConservation


**Setup Database**

Import the provided database.sql into MySQL.

Update database credentials in php/config.php.

**Deploy Website**

Place files in your web server's htdocs directory (XAMPP/WAMP) or cloud host.

Configure Raspberry Pi

Install required Python libraries to read flow sensor data.

Use the provided Python script to send data to your PHP API endpoint.

📊 Sample Output
Dashboard Graph

**Feedback Messages**

Above Limit: ⚠ Please reduce your water usage!

Below Limit: 🎉 Congratulations! You're conserving water!

Equal to Limit: 🧘 Balanced as a Zen Master – Perfect water usage!

💡 **Future Improvements**

Mobile App version for instant alerts.

AI-based predictions for water shortage warnings.

Integration with smart meters for automated billing.

Gamification (Water-saving challenges among users).

🤝**Contributing**

We welcome contributions!
If you'd like to improve features, fix bugs, or enhance UI, feel free to fork this repo and submit a pull request.

📜 **License**

This project is licensed under the MIT License – you can freely modify and distribute it.

👨‍💻 **Authors**

Anush B G – Project Lead & Developer

🌊 Save Water, Secure Future! – With WaterConservation, every drop counts.
