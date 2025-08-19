<?php
$servername = "mariadb.mastersystem.co.id:4006";
$database = "sa_wrike_integrate";
$username = "ITAdmin";
$password = "P@ssw0rd.1";

// Create connection
$conn_WR = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn_WR) {
    die("Connection failed: " . mysqli_connect_error());
}
// echo "Connected successfully";
// // mysqli_close($conn);
// $servername = "localhost";
// $database = "sa_dashboard_kpi";
// $username = "root";
// $password = "";
// // Create connection
// $conn = mysqli_connect($servername, $username, $password, $database);

// // Check connection
// if (!$conn) {
//     die("Connection failed: " . mysqli_connect_error());
// }
// // echo "Connected successfully";
