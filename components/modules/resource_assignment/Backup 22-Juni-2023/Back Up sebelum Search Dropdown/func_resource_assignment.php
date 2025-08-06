<?php
global $DTSB;

    if(isset($_POST['add'])) {
        $projectCode = $_POST['projectCode'];
        $customerName = $_POST['customerName'];
        $projectName = $_POST['projectName'];
        $email = $_POST['email'];
        $description = $_POST['description'];
        $createdBy = $_SESSION['Microservices_UserName'] . "<" . $_SESSION['Microservices_UserEmail'] . ">";

        $total = count($email);
        
        for($i=0; $i<$total; $i++){
            $sqlCheckUsers = "SELECT * FROM sa_resource_assignment Where project_code = '$projectCode' AND resource_email = '".$email[$i]."' AND status = 'Active'";
            $dataCheckUsers = $DTSB->get_sql($sqlCheckUsers);
            $totalRowCheck = $dataCheckUsers[2];

            if($totalRowCheck < 1 && $_POST['roles'][$i] != ' - '){
                $insertData = sprintf("(`project_code`, `customer_name`, `project_name`, `resource_email`, `roles`, `description`, `created_by`, `created_in_msizone`) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                GetSQLValueString($projectCode, "text"),
                GetSQLValueString($customerName, "text"),
                GetSQLValueString($projectName, "text"),
                GetSQLValueString($email[$i], "text"),
                GetSQLValueString($_POST['roles'][$i], "text"),
                GetSQLValueString($description[$i], "text"),
                GetSQLValueString($createdBy, "text"),
                GetSQLValueString(date("Y-d-m H:i:s"), "text")
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
        $condition = "id=" . $_POST['id']; 

        $updateData = sprintf("`resource_email`=%s, `roles`=%s,`status`=%s, `description`=%s",
            GetSQLValueString($_POST['email'], "text"),
            GetSQLValueString($_POST['roles'], "text"),
            GetSQLValueString($_POST['status'], "text"),
            GetSQLValueString($_POST['description'], "text")
    );
        $resUpdate = $DTSB->update_data("resource_assignment", $updateData, $condition);
        $ALERT->savedata();
    }
?>