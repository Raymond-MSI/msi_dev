<?php
$servername = "mariadb.mastersystem.co.id:4006";
$database = "sa_md_hcm";
$username = "ITAdmin";
$password = "P@ssw0rd.1";

// Create connection
$conn_HCM = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn_HCM) {
    die("Connection failed: " . mysqli_connect_error());
}
// echo "Connected successfully";
