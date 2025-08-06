<?php
$servername = "localhost";
$database = "sa_dashboard_kpi";
$username = "root";
$password = "";
// Create connection
$connx = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$connx) {
    die("Connection failed: " . mysqli_connect_error());
}
// echo "Connected successfully";
