<?php
global $DTSB, $DBHCM;

    if(isset($_POST['exportPDF'])){
        echo "Berhasil <br/>";
    }

    if(isset($_POST['submitResourceApproval'])) {
        $db_wr = "WRIKE_INTEGRATE";
        $DBWR = get_conn($db_wr);

        $idRow = $_POST['id'];
        $userName = $_SESSION['Microservices_UserName'];
        $userName = addslashes($userName);
        $modifiedBy = $userName . "<" . $_SESSION['Microservices_UserEmail'] . ">";

        if(isset($_POST['description'])){
            $description = $_POST['description'];
        }else{
            $description = '';
        }

        $sqlOldInformation = "SELECT * FROM sa_resource_assignment
        WHERE id = ".$_POST['id']."";
        $executeOldInformation = $DBWR->get_sql($sqlOldInformation);
        $projectCode = $executeOldInformation[0]['project_code'];
        $orderNumber = $executeOldInformation[0]['order_number'];
        $noSO = $executeOldInformation[0]['no_so'];
        $projectName = $executeOldInformation[0]['project_name'];
        $projectType = $executeOldInformation[0]['project_type'];
        $rolesSB = $executeOldInformation[0]['roles'];
        $projectRolesSB = $executeOldInformation[0]['project_roles'];
        $status = $executeOldInformation[0]['status'];
        $startProgress  = $executeOldInformation[0]['start_progress'];
        $endProgress = $executeOldInformation[0]['end_progress'];
        
        //Olah Resource Email untuk CC
        $resourceEmail = $executeOldInformation[0]['resource_email'];
        $explodeResourceEmail = explode("<", $resourceEmail);
        $finalResourceEmail = str_replace(">", "", $explodeResourceEmail[1]);

        //Get GM untuk Header To :
        $finalLeaderName = $DBHCM->get_general_manager($finalResourceEmail);
        $finalLeaderNameExplodeRaw = explode("<", $finalLeaderName);
        $resourceEmailExplode = $finalLeaderNameExplodeRaw[1];
        $replacedLeaderEmail = str_replace(">","","$resourceEmailExplode");

        if($_POST['status'] == "Penuh" && $_POST['startProgress'] != 0){
            echo "<script>alert('Data gagal di edit karena Status = Penuh dan Start Progress > 0')</script>";
        }else{
            if(USERPERMISSION=="dbf36ff3e3827639223983ee8ac47b42" || USERPERMISSION=="7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION=="0162bce636a63c3ae499224203e06ed0"){
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
                return;
            }

                $conditionUpdate = "id=$idRow"; 
                $updateData = sprintf("`status`= %s, `start_progress`= %s, `end_progress`= %s, `approval_status`= %s, `description` = %s, `modified_by` = %s, `modified_in_msizone`= %s",
                    GetSQLValueString($_POST['status'], "text"),
                    GetSQLValueString($_POST['startProgress'], "text"),
                    GetSQLValueString($_POST['endProgress'], "text"),
                    GetSQLValueString("pending", "text"),
                    GetSQLValueString($description, "text"),
                    GetSQLValueString($userName . "<" . $_SESSION['Microservices_UserEmail'] . ">", "text"),
                    GetSQLValueString(date("Y-m-d H:i:s"), "text")
                );

                $resUpdate = $DTSB->update_data("resource_assignment", $updateData, $conditionUpdate);
                $ALERT->savedata();
            
                $to = "$replacedLeaderEmail";
                // $from = "raka.putra@mastersystem.co.id";
                $from = $_SESSION['Microservices_UserEmail'];
                $cc = "$finalResourceEmail";
                $bcc = "";
                $reply = "$from";
                $subject = "[MSIZone] Project Charter Edit Project Charter";
                $msg = "<table width='100%'";
                $msg .= "<tr><td width='30%' rowspan='3'></td><td style='width:40%; border: thin solid #dadada; padding:20px'>";
                $msg .= "<img src='https://www.f5.com/content/dam/f5-com/page-assets-en/home-en/services/professional-services/guardian-partners/mastersystem-logo.jpg' width='150' height='50'>";
                $msg .= "<br/>";
                $msg .= "<p>Dear ".$finalLeaderNameExplodeRaw[0].",</p>";
                $msg .= "<p>Mohon melakukan approval kepada resource yang di edit dibawah ini di Project Charter : </p>";
                $msg .= "<p>";
                $msg .= "<table style='width:100%;'>";
                $msg .= "<tr><td>Project Code</td><td>: </td><td>$projectCode</td></tr>";
                $msg .= "<tr><td>Order Number</td><td>: </td><td>$orderNumber</td></tr>";
                $msg .= "<tr><td>No SO</td><td>: </td><td>$noSO</td></tr>";
                $msg .= "<tr><td>Project Name</td><td>: </td><td>$projectName</td></tr>";
                $msg .= "<tr><td>Project Type</td><td>: </td><td>$projectType</td></tr>";
                $msg .= "<tr><td>Resources</td> <td>: </td><td>".$resourceEmail."</td></tr>";
                $msg .= "<tr><td>Service Budget Roles</td> <td>: </td><td>".$rolesSB."</td></tr>";
                $msg .= "<tr><td>Project Roles</td> <td>: </td><td>".$projectRolesSB."</td></tr>";
                $msg .= "<tr><td colspan='3'></td></tr>";
                $msg .= "<tr><td colspan='3'></td></tr>";
                $msg .= "<tr><td colspan='3'></td></tr>";
                $msg .= "<tr><td colspan='3'><b>From :</b></td></tr>"; 
                $msg .= "<tr><td>Status</td><td>: </td><td>".$status."</td></tr>";
                $msg .= "<tr><td>Start Progress</td><td>: </td><td>".$startProgress."%</td></tr>";
                $msg .= "<tr><td>End Progress</td><td>: </td><td>".$endProgress."%</td></tr>";
                $msg .= "<tr><td colspan='3'></td></tr>"; 
                $msg .= "<tr><td colspan='3'><b>To :</b></td></tr>"; 
                $msg .= "<tr><td>Status</td><td>: </td><td>".$_POST['status']."</td></tr>";
                $msg .= "<tr><td>Start Progress</td><td>: </td><td>".$_POST['startProgress']."%</td></tr>";
                $msg .= "<tr><td>End Progress</td><td>: </td><td>".$_POST['endProgress']."%</td></tr>";
                $msg .= "<tr><td colspan='3'></td></tr>";
                $msg .= "<tr><td colspan='3'></td></tr>";
                $msg .= "<tr><td colspan='3'><a href='https://msizone.mastersystem.co.id/index.php?mod=resource_assignment&status=approved_assignment' style='background-color: #D21312; 
                    border: none;
                    color: white; 
                    padding: 15px 32px; 
                    text-align: center;
                    text-decoration: none; 
                    display: inline-block; 
                    font-size: 12px; 
                    margin: 4px 2px; 
                    cursor: pointer; 
                    border-radius: 10px;
                    width:83%;'>Menuju Approval</a></td></tr>";
                $msg .= "</table>";
                $msg .= "</p>";
                $msg .= "<p>Mohon Approvalnya,</p>";
                $msg .= "<p>Terima Kasih<br/></p>";
                $msg .= "<p>".$_SESSION['Microservices_UserName']."<br/></p>";
                $msg .= "<p>".date("Y/m/d")."<br/></p>";
                $msg .= "</td><td width='30%' rowspan='3'>";
                $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
                $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
                $msg .= "</table>";

                $headers = "From: ".$_SESSION['Microservices_UserEmail']." \r\n" .
                        "Reply-To: ".$_SESSION['Microservices_UserEmail']." \r\n" .
                        "Cc: $finalResourceEmail \r\n" .
                        "Bcc: \r\n" .
                        "MIME-Version: 1.0" . "\r\n" .
                        "Content-Type: text/html; charset=UTF-8" . "\r\n" .
                        "X-Mailer: PHP/" . phpversion();

                $ALERT = new Alert();
                if (!mail($to,$subject,$msg,$headers)) {
                    echo "Email gagal terkirim pada jam " . date("d M Y G:i:s");
                } else {
                    echo "Email terkirim pada jam " . date("d M Y G:i:s");
                    echo "$replacedLeaderEmail";
                }
        }
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
            $allName = "";
            $allNameResources = "";
            $allTo = "";
            $allCc = "";
            $allRoles = "";
            $allProjectRoles = "";
            $allStatus = "";
            $allStartProgress = 0;
            $allEndProgress = 0;
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
                $finalLeaderNameExplodeRaw = explode("<", $finalLeaderName);
                $resourceEmailExplode = $finalLeaderNameExplodeRaw[1];
                $replacedLeaderEmail = str_replace(">","","$resourceEmailExplode");

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

                //Collect General Manager Name
                if(isset($allName) && (strpos($allName, $finalLeaderNameExplodeRaw[0]) !== false)) {
                }else{
                    $allName .= $finalLeaderNameExplodeRaw[0] . ",";
                }
                
                //Collect General Manager Email
                if(isset($allTo) && (strpos($allTo, $replacedLeaderEmail) !==false)) {
                }else{
                    $allTo .= $replacedLeaderEmail . ", ";
                }

                //Collect Resources Name
                if(isset($allNameResources) && (strpos($allNameResources, $resourceEmailTemporaryExplode[0]) !== false)) {
                }else{
                    $allNameResources .= $resourceEmailTemporaryExplode[0] . ",";
                }
                

                //Collect Resources Email
                if(isset($allCc) && (strpos($allCc, $replacedEmail) !==false)) {
                }else{
                    $allCc .= $replacedEmail . ", ";
                }

                //Collect SB Roles
                $allRoles .= $rolesTemporary . ", ";

                //Collect Project Roles
                $allProjectRoles .= $projectRolesTemporary . ", ";

                //Collect Status
                $allStatus .= $statusTemporary . ", ";

                //Collect Start Progress
                $allStartProgress .= $startProgressTemporary . ", ";

                //Collect End Progress
                $allEndProgress .= $endProgressTemporary . ", ";
                
            }while($rowTemporary = $resTemporary->fetch_assoc());

            //Delete ; in last character
            $finalAllName = rtrim($allName, ",");
            $finalAllNameResources = rtrim($allNameResources, ",");
            $finalAllTo = rtrim($allTo, ", ");
            $finalAllCc = rtrim($allCc, ", ");
            $finalAllRoles = rtrim($allRoles, ", ");
            $finalAllProjectRoles = rtrim($allProjectRoles, ", ");
            $finalAllStatus = rtrim($allStatus, ", ");
            $finalAllStartProgress = rtrim($allStartProgress, ", ");
            $finalEndProgress = rtrim($allEndProgress, ", ");

            //Explode Needed
            $explodeAllNameResources = explode(",",$finalAllNameResources);
            $explodeAllCc = explode(", ",$finalAllCc);
            $explodeAllRoles = explode(", ",$finalAllRoles);
            $explodeAllProjectRoles = explode(", ",$finalAllProjectRoles);
            $explodeAllStatus = explode(", ",$finalAllStatus);
            $explodeAllStartProgress = explode(", ",$finalAllStartProgress);
            $explodeEndProgress = explode(", ",$finalEndProgress);
            
            //Gunakan $allTo untuk $to
            // echo "$finalAllTo <br/>";
            // echo "$finalAllCc <br/>";
            
            $to = "$finalAllTo";
            $from = $_SESSION['Microservices_UserEmail'];
            $cc = "$finalAllCc";
            $bcc = "";
            $reply = "$from";
            $subject = "[MSIZone] Project Charter";
            $msg = "<table width='100%'";
            $msg .= "<tr><td width='30%' rowspan='3'></td><td style='width:40%; border: thin solid #dadada; padding:20px'>";
            $msg .= "<img src='https://www.f5.com/content/dam/f5-com/page-assets-en/home-en/services/professional-services/guardian-partners/mastersystem-logo.jpg' width='150' height='50'>";
            $msg .= "<br/>";
            $msg .= "<p>Dear $finalAllName,</p>";
            $msg .= "<p>Mohon melakukan approval kepada resource dibawah ini di Project Charter : </p>";
            $msg .= "<p>";
            $msg .= "<table style='width:100%;'>";
            $msg .= "<tr><td>Project Code</td><td>: </td><td>$projectCodeTemporary</td></tr>";
            $msg .= "<tr><td>Order Number</td><td>: </td><td>$orderNumberTemporary</td></tr>";
            $msg .= "<tr><td>No SO</td><td>: </td><td>$noSOTemporary</td></tr>";
            $msg .= "<tr><td>Project Name</td><td>: </td><td>$projectNameTemporary</td></tr>";
            $msg .= "<tr><td>Project Type</td><td>: </td><td>$projectTypeTemporary</td></tr>";
            $msg .= "<tr><td colspan='3'></td></tr>";
            $msg .= "<tr><td colspan='3'></td></tr>";
            $msg .= "<tr><td colspan='3'></td></tr>";
            $msg .= "<tr><td colspan='3'><b>Resources Information</b></td></tr>";
            for($i=0; $i<count($explodeAllCc); $i++){
                $finalAllRoles2 = str_replace(" - ", " ", $explodeAllRoles[$i]); 
                $msg .= "<tr><td>Resources</td> <td>: </td><td>".$explodeAllNameResources[$i]."</td></tr>";
                $msg .= "<tr><td>Service Budget Roles</td> <td>: </td><td>".$finalAllRoles2."</td></tr>";
                $msg .= "<tr><td>Project Roles</td> <td>: </td><td>".$explodeAllProjectRoles[$i]."</td></tr>";
                if($explodeAllStartProgress[$i] == 00){
                    $explodeAllStartProgress[$i] = 0;
                }
                if($explodeEndProgress[$i] == 0100){
                    $explodeEndProgress[$i] = "100";
                }
                $msg .= "<tr><td>Status</td><td>: </td><td>".$explodeAllStatus[$i]." ".$explodeAllStartProgress[$i]."% - ".$explodeEndProgress[$i]."%</td></tr>";
                $msg .= "<tr><td colspan='3'></td></tr>";
                $msg .= "<tr><td colspan='3'></td></tr>";
            }
            $msg .= "<tr><td colspan='3'><a href='https://msizone.mastersystem.co.id/index.php?mod=resource_assignment&status=approved_assignment' style='background-color: #D21312; 
            border: none;
            color: white; 
            padding: 15px 32px; 
            text-align: center;
            text-decoration: none; 
            display: inline-block; 
            font-size: 12px; 
            margin: 4px 2px; 
            cursor: pointer; 
            border-radius: 10px;
            width:83%;'>Menuju Approval</a></td></tr>";
            $msg .= "</table>";
            $msg .= "</p>";
            $msg .= "<p>Mohon Approvalnya,</p>";
            $msg .= "<p>Terima Kasih<br/></p>";
            $msg .= "<p>".$_SESSION['Microservices_UserName']."<br/></p>";
            $msg .= "<p>".date("Y/m/d")."<br/></p>";
            $msg .= "</td><td width='30%' rowspan='3'>";
            $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
            $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
            $msg .= "</table>";

            $headers = "From: ".$_SESSION['Microservices_UserEmail']." \r\n" .
                    "Reply-To: ".$_SESSION['Microservices_UserEmail']." \r\n" .
                    "Cc: $finalAllCc \r\n" .
                    "Bcc: \r\n" .
                    "MIME-Version: 1.0" . "\r\n" .
                    "Content-Type: text/html; charset=UTF-8" . "\r\n" .
                    "X-Mailer: PHP/" . phpversion();

            $ALERT = new Alert();
            if (!mail($to,$subject,$msg,$headers)) {
                echo "Email gagal terkirim pada jam " . date("d M Y G:i:s");
            } else {
                echo "Email terkirim pada jam " . date("d M Y G:i:s");
            }

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
            WHERE so_number = '$noSO'
            AND status = 'acknowledge'";
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