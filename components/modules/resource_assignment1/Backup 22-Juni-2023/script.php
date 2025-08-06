<?php
$servername = "10.120.50.92";
$username = "ITAdmin";
$password = "P@ssw0rd.1";
$database = "sa_wrike_integrate";


// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];
$roles = $_POST['roles'];
$status = $_POST['status'];
$startProgress = $_POST['startProgress'];
$endProgress = $_POST['endProgress']; 
$projectCode = $_POST['projectCode'];
$projectType = $_POST['projectType'];
$noSO = $_POST['noSO'];
$emailSession = $_POST['emailSession'];
$customer = $_POST['customer'];
$projectId = $_POST['projectId'];
$orderNumber = $_POST['orderNumber'];
$projectRoles = $_POST['projectRoles'];
$projectName = $_POST['projectName'];
$actualLink = $_POST['actualLink'];

if(isset($_POST['description'])){
    $description = $_POST['description'];
}else{
    $description = '';
}

if($roles != '' && $roles != ' - '){
    $sqlCheckRes = "SELECT * FROM sa_resource_assignment WHERE resource_email = '$email' AND project_id = '$projectId' AND roles = '$roles'";
    $dataCheckRes = mysqli_query($conn, $sqlCheckRes);
    $rowCountRes = mysqli_num_rows($dataCheckRes);

    if($rowCountRes > 0){
        $resultNotif = array(
            'notifId' => 1,
            'notification' => 'Data sudah pernah di assign !'
        );
        header('Content-Type: application/json; charset="utf-8"');
        echo json_encode($resultNotif);
    }else{
        $sqlCheck = "SELECT * FROM sa_temporary_resource WHERE resource_email = '$email' AND project_id = '$projectId' AND roles = '$roles'";
        $dataAjax = mysqli_query($conn, $sqlCheck);
        $rowcount = mysqli_num_rows($dataAjax);
        
        if($rowcount > 0) {
            $resultNotifTemporary = array(
                'notifId' => 2,
                'notification' => 'Data sudah ada di temporary resource dengan role yang sama !'
            );
            header('Content-Type: application/json; charset="utf-8"');
            echo json_encode($resultNotifTemporary);
        }else{
            $sqlInsert = "INSERT INTO sa_temporary_resource (`project_code`,`project_id`, `order_number`,`no_so`, `project_type`, `customer_name`,`project_name`,`resource_email`,`roles`,`project_roles`,`start_progress`,`end_progress`,`status`, `description`, `created_by`)
            VALUES ('$projectCode', '$projectId', '$orderNumber', '$noSO', '$projectType', '$customer', '$projectName', '$email', '$roles', '$projectRoles', '$startProgress', '$endProgress', '$status', '$description', '$emailSession')";
            $insertDataAjax = mysqli_query($conn, $sqlInsert);
            if($insertDataAjax){
                $resultNotifSuccess = array(
                    'notifId' => 3,
                    'notification' => 'Data berhasil masuk ke temporary resource !'
                );
                header('Content-Type: application/json; charset="utf-8"');
                echo json_encode($resultNotifSuccess);
            }
        }
    }
}else{
    echo "<script>alert('Ada field yang kosong !')</script>";
}


?>
