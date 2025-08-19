<?php
// $servername = "localhost";
// $database = "sa_ps_service_budgets";
// $username = "root";
// $password = "";

$servername = "mariadb.mastersystem.co.id:4006";
$database = "sa_ps_service_budgets";
$username = "ITAdmin";
$password = "P@ssw0rd.1";

// Create connection
$conn_SB = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn_SB) {
    die("Connection failed: " . mysqli_connect_error());
}
// echo "Connected successfully";
// mysqli_close($conn);