<?php
$servername = "mariadb.mastersystem.co.id:4006";
$database = "sa_change_request";
$username = "ITAdmin";
$password = "P@ssw0rd.1";

// Create connection
$conn_CR = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn_CR) {
    die("Connection failed: " . mysqli_connect_error());
}
// echo "Connected successfully";
