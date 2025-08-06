<?php
global $DTSB;

    if(isset($_POST['add'])) {
        $projectCode = $_POST['projectCode'];
        $noSO = $_POST['noSO'];
        $customerName = $_POST['customerName'];
        $projectName = $_POST['projectName'];
        $email = $_POST['email'];
        $status = $_POST['status'];
        $startProgress = $_POST['startProgress'];
        $endProgress = $_POST['endProgress']; 
        $description = $_POST['description'];
        $userName = $_SESSION['Microservices_UserName'];
        $userName = addslashes($userName);
        $createdBy = $userName . "<" . $_SESSION['Microservices_UserEmail'] . ">";

        $total = count($email);
        
        for($i=0; $i<$total; $i++){
            $sqlCheckUsers = "SELECT * FROM sa_resource_assignment WHERE project_code = '$projectCode' AND status IN('Active', 'Joined') AND resource_email = '".$email[$i]."'";
            $dataCheckUsers = $DTSB->get_sql($sqlCheckUsers);
            $totalRowCheck = $dataCheckUsers[2];

            if($totalRowCheck < 1 && $_POST['roles'][$i] != ' - '){
                $insertData = sprintf("(`project_code`, `no_so`, `customer_name`, `project_name`, `resource_email`, `roles`, `start_progress`, `end_progress`, `status`, `description`, `created_by`, `modified_by`, `created_in_msizone`, `modified_in_msizone`) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                GetSQLValueString($projectCode, "text"),
                GetSQLValueString($noSO, "text"),
                GetSQLValueString($customerName, "text"),
                GetSQLValueString($projectName, "text"),
                GetSQLValueString($email[$i], "text"),
                GetSQLValueString($_POST['roles'][$i], "text"),
                GetSQLValueString($startProgress[$i], "text"),
                GetSQLValueString($endProgress[$i], "text"),
                GetSQLValueString($status[$i], "text"),
                GetSQLValueString($description[$i], "text"),
                GetSQLValueString($createdBy, "text"),
                GetSQLValueString($createdBy, "text"),
                GetSQLValueString(date("Y-m-d H:i:s"), "text"),
                GetSQLValueString(date("Y-m-d H:i:s"), "text")
            );
                $DTSB->insert_data("resource_assignment", $insertData);
                $ALERT->savedata();
            }else{
                ?>
                <script>alert("Salah satu resource yang dimasukkan telah di assign di project / terdapat roles yang kosong !");</script>
                <?php
            }
        }

    } else if(isset($_POST['save'])) {
        $userName = $_SESSION['Microservices_UserName'];
        $userName = addslashes($userName);

        $condition = "id=" . $_POST['id']; 

        $updateData = sprintf("`resource_email`= %s, `roles`= %s, `status`= %s, `start_progress` = %s, `end_progress` = %s, `description`= %s, `modified_by` = %s, `modified_in_msizone`= %s",
            GetSQLValueString($_POST['email'], "text"),
            GetSQLValueString($_POST['roles'], "text"),
            GetSQLValueString($_POST['status'], "text"),
            GetSQLValueString($_POST['startProgress'], "int"),
            GetSQLValueString($_POST['endProgress'], "int"),
            GetSQLValueString($_POST['description'], "text"),
            GetSQLValueString($userName . "<" . $_SESSION['Microservices_UserEmail'] . ">", "text"),
            GetSQLValueString(date("Y-m-d H:i:s"), "text")
    );

        $resUpdate = $DTSB->update_data("resource_assignment", $updateData, $condition);
        $ALERT->savedata();
    }
?>