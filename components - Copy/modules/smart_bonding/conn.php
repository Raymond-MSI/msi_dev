<?php
$servername = "10.120.50.92";
$database = "sa_smartbonding";
$username = "ITAdmin";
$password = "P@ssw0rd.1";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";
// mysqli_close($conn);
