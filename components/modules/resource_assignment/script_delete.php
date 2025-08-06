<?php
//$servername = "10.120.50.92";
$servername = "mariadb.mastersystem.co.id:4006";
$username = "ITAdmin";
$password = "P@ssw0rd.1";
$database = "sa_wrike_integrate";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'];
$projectCode = $_GET['project_code'];
$orderNumber = $_GET['order_number'];

$sqlDelete = "DELETE FROM sa_temporary_resource WHERE id = '$id'";
$dataDelete = mysqli_query($conn, $sqlDelete);

if($dataDelete){
    echo "<script>alert('Data Berhasil Dihapus');</script>";
    header("Location: http:///msizone.mastersystem.co.id/index.php?mod=resource_assignment&act=add&project_code=$projectCode&order_number=$orderNumber");
}

// if($rowcount > 0) {
//     echo "<script>alert('Data Sudah Ada !')</script>";
// }else{
//     $sqlInsert = "INSERT INTO sa_temporary_resource (`project_code`,`no_so`,`customer_name`,`project_name`,`resource_email`,`roles`,`start_progress`,`end_progress`,`status`,`created_by`)
//     VALUES ('$projectCode', '$noSO', '$customer', '$projectName', '$email', '$roles', '$startProgress', '$endProgress', '$status', '$emailSession')";

//     $insertDataAjax = mysqli_query($conn, $sqlInsert);
//     if($insertDataAjax){
//         echo "Data masuk ke database!";
//     }
// }


?>
