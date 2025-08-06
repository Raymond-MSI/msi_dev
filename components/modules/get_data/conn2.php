<?php
$servername = "192.168.234.158";
$database = "sa_dashboard_kpi";
$username = "moodleuser";
$password = "P@ssw0rd";
// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// echo "Connected successfully";
