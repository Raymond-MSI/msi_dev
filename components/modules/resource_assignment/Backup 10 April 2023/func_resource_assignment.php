<?php
global $DTSB, $DBHCM;

    if(isset($_POST['submitResourceApproval'])) {
        $userName = $_SESSION['Microservices_UserName'];
        $userName = addslashes($userName);
        $modifiedBy = $userName . "<" . $_SESSION['Microservices_UserEmail'] . ">";
        $idRow = $_POST['id'];

        if(isset($_POST['description'])){
            $description = $_POST['description'];
        }else{
            $description = '';
        }

        $conditionUpdate = "id=$idRow"; 

        $updateData = sprintf("`status`= %s, `start_progress`= %s, `end_progress`= %s, `description` = %s, `modified_by` = %s, `modified_in_msizone`= %s",
            GetSQLValueString($_POST['status'], "text"),
            GetSQLValueString($_POST['startProgress'], "text"),
            GetSQLValueString($_POST['endProgress'], "text"),
            GetSQLValueString($description, "text"),
            GetSQLValueString($userName . "<" . $_SESSION['Microservices_UserEmail'] . ">", "text"),
            GetSQLValueString(date("Y-m-d H:i:s"), "text")
        );

        $resUpdate = $DTSB->update_data("resource_assignment", $updateData, $conditionUpdate);
        $ALERT->savedata();
    }   

    if(isset($_POST['deleteResource'])) {
        $userName = $_SESSION['Microservices_UserName'];
        $userName = addslashes($userName);
        $modifiedBy = $userName . "<" . $_SESSION['Microservices_UserEmail'] . ">";
        $idRow = $_POST['id'];
        $projectCode = $_POST['projectCode'];
        $noSO = $_POST['noSO'];
        $resourceEmail = $_POST['email'];

        $description = "$resourceEmail telah di hapus dari KP.$projectCode dan SO.$noSO";

        $conditionDelete = "id = '$idRow'";
        $resDelete = $DTSB->delete_data("resource_assignment", $conditionDelete);

        $insertData = sprintf("(`ra_id`, `project_code`, `description`, `entry_by`) VALUES (%s, %s, %s, %s)",
            GetSQLValueString($idRow, "text"),
            GetSQLValueString($projectCode, "text"),
            GetSQLValueString($description, "text"),
            GetSQLValueString($modifiedBy, "text")
        );

       $resLogs = $DTSB->insert_data("resource_logs", $insertData);
    }   

    if(isset($_POST['submitResource'])) {
        $userName = $_SESSION['Microservices_UserName'];
        $userName = addslashes($userName);
        $createdBy = $userName . "<" . $_SESSION['Microservices_UserEmail'] . ">";
        $projectId = $_POST['projectId'];

        // $leader = $DBHCM->get_leader_v2($_SESSION['Microservices_UserEmail']);
        // $rawLeaderName = $leader[2][0];
        // $finalLeaderName = str_replace(";","","$rawLeaderName");

        $sqlCheckData = "SELECT * FROM sa_temporary_resource WHERE project_id = '$projectId' AND created_by = '$createdBy'";
        $dataTemporary = $DTSB->get_sql($sqlCheckData);
        $rowTemporary = $dataTemporary[0];
        $resTemporary = $dataTemporary[1];
        $totalRowTemporary = $dataTemporary[2];

        if($totalRowTemporary > 0){
            do{
                $idTemporary = $rowTemporary['id'];
                $projectCodeTemporary = $rowTemporary['project_code'];
                $projectIdTemporary = $rowTemporary['project_id'];
                $noSOTemporary = $rowTemporary['no_so'];
                $orderNumberTemporary = $rowTemporary['order_number'];
                $projectTypeTemporary = $rowTemporary['project_type'];
                $customerNameTemporary = $rowTemporary['customer_name'];
                $projectNameTemporary = $rowTemporary['project_name'];
                $resourceEmailTemporary = $rowTemporary['resource_email'];
                $rolesTemporary = $rowTemporary['roles'];
                $projectRolesTemporary = $rowTemporary['project_roles'];
                $startProgressTemporary = $rowTemporary['start_progress'];
                $endProgressTemporary = $rowTemporary['end_progress'];
                $statusTemporary = $rowTemporary['status'];
                $descriptionTemporary = $rowTemporary['description'];
                $createdByTemporary = $rowTemporary['created_by'];

                $resourceEmailTemporaryExplode = explode("<", $resourceEmailTemporary);
                $resourceEmailExplode = $resourceEmailTemporaryExplode[1];
                $replacedEmail = str_replace(">","","$resourceEmailExplode");

                $finalLeaderName = $DBHCM->get_general_manager($replacedEmail);
                echo "$finalLeaderName <br/>";

                $sqlCheckResource = "SELECT * FROM sa_resource_assignment WHERE project_id = '$projectId' AND resource_email = '$resourceEmailTemporary' AND roles = '$rolesTemporary' AND status IN ('Penuh','Mutasi')";
                $dataResource = $DTSB->get_sql($sqlCheckResource);
                $rowResource = $dataResource[0];
                $totalRowResource = $dataResource[2];

                if($totalRowResource > 0){
                    echo "<script>alert('Resource $resourceEmailTemporary sudah pernah di assign dengan roles ".$rowResource['roles']." di KP $projectCodeTemporary SO $noSOTemporary - $projectTypeTemporary!');</script>";
                }else{
                    $insertData = sprintf("(`project_code`, `project_id`, `order_number`, `no_so`, `project_type`, `customer_name`, `project_name`, `resource_email`, `roles`, `project_roles`, `start_progress`, `end_progress`, `status`, `description`, `created_by`, `modified_by`, `created_in_msizone`, `modified_in_msizone`, `approval_to`) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                        GetSQLValueString($projectCodeTemporary, "text"),
                        GetSQLValueString($projectIdTemporary, "text"),
                        GetSQLValueString($orderNumberTemporary, "text"),
                        GetSQLValueString($noSOTemporary, "text"),
                        GetSQLValueString($projectTypeTemporary, "text"),
                        GetSQLValueString($customerNameTemporary, "text"),
                        GetSQLValueString($projectNameTemporary, "text"),
                        GetSQLValueString($resourceEmailTemporary, "text"),
                        GetSQLValueString($rolesTemporary, "text"),
                        GetSQLValueString($projectRolesTemporary, "text"),
                        GetSQLValueString($startProgressTemporary, "text"),
                        GetSQLValueString($endProgressTemporary, "text"),
                        GetSQLValueString($statusTemporary, "text"),
                        GetSQLValueString($descriptionTemporary, "text"),
                        GetSQLValueString($createdByTemporary, "text"),
                        GetSQLValueString($createdBy, "text"),
                        GetSQLValueString(date("Y-m-d H:i:s"), "text"),
                        GetSQLValueString(date("Y-m-d H:i:s"), "text"),
                        GetSQLValueString($finalLeaderName, "text")
                    );

                    $DTSB->insert_data("resource_assignment", $insertData);

                    $conditionDelete = "id = '$idTemporary'";
                    $DTSB->delete_data("temporary_resource", $conditionDelete);

                    $ALERT->savedata();
                }

                //Trade Table
            }while($rowTemporary = $resTemporary->fetch_assoc());
        }else{
            echo "<script>alert('Mohon maaf, anda belum memilih resource untuk project ini.');</script>";
        }
        

    }
    
    if(isset($_POST['save'])) {
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

    if(isset($_POST['btn_approve'])) {
        if(isset($_POST['id'])) {
            $checkbox = $_POST['id'];
            $userNameApprove = $_POST['userSession'];
        
            for($i=0;$i<count($checkbox);$i++){
                $idCheckbox = $checkbox[$i];

                    $conditionApprove = "id=$idCheckbox"; 
                    $updateDataApprove = sprintf("`approval_status`= 'approved', `modified_by` = %s, `modified_in_msizone`= %s",
                        GetSQLValueString($userNameApprove, "text"),
                        GetSQLValueString(date("Y-m-d H:i:s"), "text")
                    );
    
                    $resUpdateApprove = $DTSB->update_data("resource_assignment", $updateDataApprove, $conditionApprove);
                    $ALERT->savedata();
            }
        }else{
            echo "<script>alert('Anda belum checklist data apapun !')</script>";
        }
    }

    if(isset($_POST['btn_reject'])) {
        if(isset($_POST['id'])) {
            $checkbox = $_POST['id'];
            $userNameApprove = $_POST['userSession'];
        
            for($i=0;$i<count($checkbox);$i++){
                $idCheckbox = $checkbox[$i];

                    $conditionApprove = "id=$idCheckbox"; 
                    $updateDataApprove = sprintf("`approval_status`= 'rejected', `modified_by` = %s, `modified_in_msizone`= %s",
                        GetSQLValueString($userNameApprove, "text"),
                        GetSQLValueString(date("Y-m-d H:i:s"), "text")
                    );
    
                    $resUpdateApprove = $DTSB->update_data("resource_assignment", $updateDataApprove, $conditionApprove);
                    $ALERT->savedata();
            }
        }else{
            echo "<script>alert('Anda belum checklist data apapun !')</script>";
        }
    }


function OverrideApprovalTo(){
    $db_wr="WRIKE_INTEGRATE";
    $DBWR=get_conn($db_wr);

    $db_hcm="HCM";
    $DBHCM=get_conn($db_hcm);

    $sqlLoopData = "SELECT * FROM sa_resource_assignment";
    $dataLoop = $DBWR->get_sql($sqlLoopData);
    $rowDataLoop = $dataLoop[0];
    $resDataLoop = $dataLoop[1];
    $totalRowDataLoop = $dataLoop[2];

    do{
        $id = $rowDataLoop['id'];
        $resourceEmail = $rowDataLoop['resource_email'];
        $explodeResourceEmail = explode("<",$resourceEmail);
        $finalResourceEmail = str_replace(">","",$explodeResourceEmail[1]);

        $approvalTo = $DBHCM->get_general_manager($finalResourceEmail);

        $conditionApprove = "id=$id"; 

        $updateDataApprove = sprintf("`approval_to`= '$approvalTo'",
            GetSQLValueString($approvalTo, "text")
        );
    
        $resUpdateApprove = $DBWR->update_data("resource_assignment", $updateDataApprove, $conditionApprove);
        echo "$id - $finalResourceEmail - $approvalTo <br/>";
    }while($rowDataLoop = $resDataLoop->fetch_assoc());
}

function OverrideOrderNumber(){
    $db_wr="WRIKE_INTEGRATE";
    $DBWR=get_conn($db_wr);

    $db_sb="SERVICE_BUDGET";
    $DBSB=get_conn($db_sb);

    $sqlCheckOrderNumber = "SELECT distinct no_so FROM sa_resource_assignment 
    WHERE order_number = '' 
    OR order_number = NULL or order_number is NULL";
    $dataGet = $DBWR->get_sql($sqlCheckOrderNumber);
    $rowOrderNumber = $dataGet[0];
    $resOrderNumber = $dataGet[1];
    $totalRowOrderNumber = $dataGet[2];

    if($totalRowOrderNumber > 0){
        do{
            $noSO = $rowOrderNumber['no_so'];
            
            // Lookup Order Number
            $sqlGetOrderNumber = "SELECT order_number FROM sa_trx_project_list 
            WHERE so_number = '$noSO'";
            $dataOrderNumber = $DBSB->get_sql($sqlGetOrderNumber);

            if(isset($dataOrderNumber[0]['order_number'])){
                $orderNumber = $dataOrderNumber[0]['order_number'];
            }else{
                $orderNumber = '';
            }

            $conditionUpdate = "no_so = '$noSO'"; 
            $updateData = sprintf("`order_number`= '$orderNumber'",
                GetSQLValueString($orderNumber, "text")
            );
            $resUpdate = $DBWR->update_data("resource_assignment", $updateData, $conditionUpdate);

            echo "Data berhasil di update<br/>";

        }while($rowOrderNumber = $resOrderNumber->fetch_assoc());
    }else{
        echo "Semua data sudah memiliki Order Number <br/>";
    }
}
?>