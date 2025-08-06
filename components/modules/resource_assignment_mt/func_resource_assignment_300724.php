<?php
global $DTSB, $DBHCM;
include_once("components/modules/resource_assignment_mt/class_wrike_project.php");
$WRIKE_PROJECT = new WRIKE_PROJECT();


// var_dump($_POST);die;

if (isset($_POST['exportPDF'])) {
    echo "Berhasil <br/>";
}

if (isset($_POST['submitResourceApproval'])) {
    $db_wr = "WRIKE_INTEGRATE";
    $DBWR = get_conn($db_wr);

    $DBHCM = get_conn("HCM");

    $idRow = $_POST['id'];
    $userName = $_SESSION['Microservices_UserName'];
    $userName = addslashes($userName);
    $modifiedBy = $userName . "<" . $_SESSION['Microservices_UserEmail'] . ">";

    if (isset($_POST['description'])) {
        $description = $_POST['description'];
    } else {
        $description = '';
    }

    $sqlOldInformation = "SELECT * FROM sa_resource_assignment
        WHERE id = " . $_POST['id'] . "";
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

    $startDate  = $executeOldInformation[0]['start_date'];
    $endDate = $executeOldInformation[0]['end_date'];

    //Olah Resource Email untuk CC
    $resourceEmail = $executeOldInformation[0]['resource_email'];
    $explodeResourceEmail = explode("<", $resourceEmail);
    $finalResourceEmail = str_replace(">", "", $explodeResourceEmail[1]);

    //Get GM untuk Header To :
    if ($projectRolesSB == "PIC Maintenance"){
        $get_leader = $DBHCM->get_sqlV2("SELECT * FROM sa_view_employees WHERE employee_email='$finalResourceEmail' AND resign_date is null");
        $finalLeaderName = $get_leader[0]['leader_name'] . "<" . $get_leader[0]['leader_email'] . ">";
        $finalLeaderNameExplodeRaw = explode("<", $finalLeaderName);
        $resourceEmailExplode = $finalLeaderNameExplodeRaw[1];
        $replacedLeaderEmail = str_replace(">", "", "$resourceEmailExplode");
    } else {
        $finalLeaderName = $DBHCM->get_general_manager($finalResourceEmail);
        $finalLeaderNameExplodeRaw = explode("<", $finalLeaderName);
        $resourceEmailExplode = $finalLeaderNameExplodeRaw[1];
        $replacedLeaderEmail = str_replace(">", "", "$resourceEmailExplode");
    }

    // if ($_POST['status'] == "Penuh" && $_POST['startProgress'] != 0) {
    //     echo "<script>alert('Data gagal di edit karena Status = Penuh dan Start Progress > 0')</script>";
    // } else {
    if (USERPERMISSION == "dbf36ff3e3827639223983ee8ac47b42" || USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION == "0162bce636a63c3ae499224203e06ed0") {
        $conditionUpdate = "id=$idRow";
        $updateData = sprintf(
            "`status`= %s, `start_date`= %s, `end_date`= %s, `description` = %s, `modified_by` = %s, `modified_in_msizone`= %s",
            GetSQLValueString($_POST['status'], "text"),
            GetSQLValueString($_POST['start_date'], "text"),
            GetSQLValueString($_POST['end_date'], "text"),
            GetSQLValueString($description, "text"),
            GetSQLValueString($userName . "<" . $_SESSION['Microservices_UserEmail'] . ">", "text"),
            GetSQLValueString(date("Y-m-d H:i:s"), "text")
        );

        $resUpdate = $DTSB->update_data("resource_assignment", $updateData, $conditionUpdate);
        $ALERT->savedata();
        return;
    }

    $conditionUpdate = "id=$idRow";
    $updateData = sprintf(
        "`status`= %s, `start_date`= %s, `end_date`= %s, `approval_status`= %s, `description` = %s, `modified_by` = %s, `modified_in_msizone`= %s",
        GetSQLValueString($_POST['status'], "text"),
        GetSQLValueString($_POST['start_date'], "text"),
        GetSQLValueString($_POST['end_date'], "text"),
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
    $subject = "[MSIZONE] Project Charter Maintenance Edit Project Charter";
    $msg = "<table width='100%'";
    $msg .= "<tr><td width='30%' rowspan='3'></td><td style='width:40%; border: thin solid #dadada; padding:20px'>";
    $msg .= "<img src='https://www.f5.com/content/dam/f5-com/page-assets-en/home-en/services/professional-services/guardian-partners/mastersystem-logo.jpg' width='150' height='50'>";
    $msg .= "<br/>";
    $msg .= "<p>Dear " . $finalLeaderNameExplodeRaw[0] . ",</p>";
    $msg .= "<p>Mohon melakukan approval kepada resource yang di edit dibawah ini di Project Charter : </p>";
    $msg .= "<p>";
    $msg .= "<table style='width:100%;'>";
    $msg .= "<tr><td>Project Code</td><td>: </td><td>$projectCode</td></tr>";
    $msg .= "<tr><td>Order Number</td><td>: </td><td>$orderNumber</td></tr>";
    $msg .= "<tr><td>No SO</td><td>: </td><td>$noSO</td></tr>";
    $msg .= "<tr><td>Project Name</td><td>: </td><td>$projectName</td></tr>";
    $msg .= "<tr><td>Project Type</td><td>: </td><td>$projectType</td></tr>";
    $msg .= "<tr><td>Resources</td> <td>: </td><td>" . $resourceEmail . "</td></tr>";
    $msg .= "<tr><td>Service Budget Roles</td> <td>: </td><td>" . $rolesSB . "</td></tr>";
    $msg .= "<tr><td>Project Roles</td> <td>: </td><td>" . $projectRolesSB . "</td></tr>";
    $msg .= "<tr><td colspan='3'></td></tr>";
    $msg .= "<tr><td colspan='3'></td></tr>";
    $msg .= "<tr><td colspan='3'></td></tr>";
    $msg .= "<tr><td colspan='3'><b>From :</b></td></tr>";
    $msg .= "<tr><td>Status</td><td>: </td><td>" . $status . "</td></tr>";
    $msg .= "<tr><td>Start Progress</td><td>: </td><td>" . $startProgress . "%</td></tr>";
    $msg .= "<tr><td>End Progress</td><td>: </td><td>" . $endProgress . "%</td></tr>";
    $msg .= "<tr><td colspan='3'></td></tr>";
    $msg .= "<tr><td colspan='3'><b>To :</b></td></tr>";
    $msg .= "<tr><td>Status</td><td>: </td><td>" . $_POST['status'] . "</td></tr>";
    $msg .= "<tr><td>Start Progress</td><td>: </td><td>" . $_POST['startProgress'] . "%</td></tr>";
    $msg .= "<tr><td>End Progress</td><td>: </td><td>" . $_POST['endProgress'] . "%</td></tr>";
    $msg .= "<tr><td colspan='3'></td></tr>";
    $msg .= "<tr><td colspan='3'></td></tr>";
    $msg .= "<tr><td colspan='3'><a href='https://msizone.mastersystem.co.id/index.php?mod=resource_assignment_mt&status=approved_assignment' style='background-color: #D21312; 
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
    $msg .= "<p>" . $_SESSION['Microservices_UserName'] . "<br/></p>";
    $msg .= "<p>" . date("Y/m/d") . "<br/></p>";
    $msg .= "</td><td width='30%' rowspan='3'>";
    $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
    $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
    $msg .= "</table>";

    $headers = "From: " . $_SESSION['Microservices_UserEmail'] . " \r\n" .
        "Reply-To: " . $_SESSION['Microservices_UserEmail'] . " \r\n" .
        "Cc: $finalResourceEmail \r\n" .
        "Bcc: \r\n" .
        "MIME-Version: 1.0" . "\r\n" .
        "Content-Type: text/html; charset=UTF-8" . "\r\n" .
        "X-Mailer: PHP/" . phpversion();

    $ALERT = new Alert();
    if (!mail($to, $subject, $msg, $headers)) {
        echo "Email gagal terkirim pada jam " . date("d M Y G:i:s");
    } else {
        echo "Email terkirim pada jam " . date("d M Y G:i:s");
        // echo "$replacedLeaderEmail";
    }
    // }
}

if (isset($_POST['deleteResource'])) {
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

    $insertData = sprintf(
        "(`ra_id`, `project_code`, `description`, `entry_by`) VALUES (%s, %s, %s, %s)",
        GetSQLValueString($idRow, "text"),
        GetSQLValueString($projectCode, "text"),
        GetSQLValueString($description, "text"),
        GetSQLValueString($modifiedBy, "text")
    );

    $resLogs = $DTSB->insert_data("resource_logs", $insertData);
}

if (isset($_POST['submitResource'])) {
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

    if ($totalRowTemporary > 0) {
        $allName = "";
        $allNameResources = "";
        $allTo = "";
        $allCc = "";
        $allRoles = "";
        $allProjectRoles = "";
        $allStatus = "";
        $allStartProgress = 0;
        $allEndProgress = 0;
        do {
            $idTemporary = $rowTemporary['id'];
            $projectCodeTemporary = $rowTemporary['project_code'];
            $projectIdTemporary = $rowTemporary['project_id'];
            $noSOTemporary = $rowTemporary['no_so'];
            $orderNumberTemporary = $rowTemporary['order_number'];
            $projectTypeTemporary = $rowTemporary['project_type'];
            $customerNameTemporary = $rowTemporary['customer_name'];
            $projectNameTemporary = $rowTemporary['project_name'];
            $resourceEmailTemporary = addslashes($rowTemporary['resource_email']);
            $rolesTemporary = $rowTemporary['roles'];
            $projectRolesTemporary = $rowTemporary['project_roles'];
            $startProgressTemporary = $rowTemporary['start_progress'];
            $endProgressTemporary = $rowTemporary['end_progress'];
            $statusTemporary = $rowTemporary['status'];
            $descriptionTemporary = $rowTemporary['description'];
            $createdByTemporary = addslashes($rowTemporary['created_by']);

            $startDateTemporary = $rowTemporary['start_date'];
            $endDateTemporary = $rowTemporary['end_date'];

            $resourceEmailTemporaryExplode = explode("<", $resourceEmailTemporary);
            $resourceEmailExplode = $resourceEmailTemporaryExplode[1];
            $replacedEmail = str_replace(">", "", "$resourceEmailExplode");

            if (strpos($replacedEmail, 'rio.agustian@mastersystem.co.id') !== false || strpos($replacedEmail, 'aldo.octavianto@mastersystem.co.id') !== false || strpos($replacedEmail, 'andri.ramadhani@mastersystem.co.id') !== false || strpos($replacedEmail, 'bill.julian@mastersystem.co.id') !== false || strpos($replacedEmail, 'handi.widiansyah@mastersystem.co.id') !== false) {
                $finalLeaderName = "Anggi Fachrizal<anggi.fachrizal@mastersystem.co.id>";
            } else {
                $finalLeaderNameraw = $DBHCM->get_general_manager($replacedEmail);
                if (strpos($finalLeaderNameraw, 'lintar@mastersystem.co.id') !== false) {
                    $finalLeaderName = 'Andino Holi Fajra<andino.hf@mastersystem.co.id>';
                } else if ($finalLeaderNameraw == 'lintar@mastersystem.co.id') {
                    $finalLeaderName = 'Andino Holi Fajra<andino.hf@mastersystem.co.id>';
                } else if (strpos($finalLeaderNameraw, 'Johnny@mastersystem.co.id') !== false) {
                    $finalLeaderName = 'Iwan Rusmin<iwan@mastersystem.co.id>';
                } else if ($finalLeaderNameraw == 'Johnny@mastersystem.co.id') {
                    $finalLeaderName = 'Iwan Rusmin<iwan@mastersystem.co.id>';
                } else {
                    $finalLeaderName = $DBHCM->get_general_manager($replacedEmail);
                }
            }
            $finalLeaderNameExplodeRaw = explode("<", $finalLeaderName);
            $resourceEmailExplode = $finalLeaderNameExplodeRaw[1];
            $replacedLeaderEmail = str_replace(">", "", "$resourceEmailExplode");

            $sqlCheckResource = "SELECT * FROM sa_resource_assignment WHERE project_id = '$projectId' AND resource_email = '$resourceEmailTemporary' AND roles = '$rolesTemporary' AND status ='$statusTemporary'";
            //IN ('Penuh','Mutasi')";
            $dataResource = $DTSB->get_sql($sqlCheckResource);
            $rowResource = $dataResource[0];
            $totalRowResource = $dataResource[2];

            if ($totalRowResource > 0) {
                echo "<script>alert('Resource $resourceEmailTemporary sudah pernah di assign dengan roles " . $rowResource['roles'] . " di KP $projectCodeTemporary SO $noSOTemporary - $projectTypeTemporary!');</script>";
            } else {
                $insertData = sprintf(
                    "(`project_code`, `project_id`, `order_number`, `no_so`, `project_type`, `customer_name`, `project_name`, `resource_email`, `roles`, `project_roles`, `start_date`, `end_date`, `status`, `description`, `created_by`, `modified_by`, `created_in_msizone`, `modified_in_msizone`, `approval_to`) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                    GetSQLValueString($projectCodeTemporary, "text"),
                    GetSQLValueString($projectIdTemporary, "text"),
                    GetSQLValueString($orderNumberTemporary, "text"),
                    GetSQLValueString($noSOTemporary, "text"),
                    GetSQLValueString($projectTypeTemporary, "text"),
                    GetSQLValueString($customerNameTemporary, "text"),
                    GetSQLValueString($projectNameTemporary, "text"),
                    GetSQLValueString(addslashes($resourceEmailTemporary), "text"),
                    GetSQLValueString($rolesTemporary, "text"),
                    GetSQLValueString($projectRolesTemporary, "text"),
                    GetSQLValueString($startDateTemporary, "text"),
                    GetSQLValueString($endDateTemporary, "text"),
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
            if (isset($allName) && (strpos($allName, $finalLeaderNameExplodeRaw[0]) !== false)) {
            } else {
                $allName .= $finalLeaderNameExplodeRaw[0] . ",";
            }

            //Collect General Manager Email
            if (isset($allTo) && (strpos($allTo, $replacedLeaderEmail) !== false)) {
            } else {
                $allTo .= $replacedLeaderEmail . ", ";
            }

            //Collect Resources Name
            if (isset($allNameResources) && (strpos($allNameResources, $resourceEmailTemporaryExplode[0]) !== false)) {
            } else {
                $allNameResources .= $resourceEmailTemporaryExplode[0] . ",";
            }


            //Collect Resources Email
            if (isset($allCc) && (strpos($allCc, $replacedEmail) !== false)) {
            } else {
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
        } while ($rowTemporary = $resTemporary->fetch_assoc());

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
        $explodeAllNameResources = explode(",", $finalAllNameResources);
        $explodeAllCc = explode(", ", $finalAllCc);
        $explodeAllRoles = explode(", ", $finalAllRoles);
        $explodeAllProjectRoles = explode(", ", $finalAllProjectRoles);
        $explodeAllStatus = explode(", ", $finalAllStatus);
        $explodeAllStartProgress = explode(", ", $finalAllStartProgress);
        $explodeEndProgress = explode(", ", $finalEndProgress);

        //Gunakan $allTo untuk $to
        // echo "$finalAllTo <br/>";
        // echo "$finalAllCc <br/>";
        $db_mt = "MAINTENANCE_DATE";
        $DBMTdate = get_conn($db_mt);
        $userName = addslashes($_SESSION['Microservices_UserName']);
        $entry_by = $userName . "<" . $_SESSION['Microservices_UserEmail'] . ">";
        $project_code = $_POST['project_code'];
        $order_number = $_POST['master']['order_number'];
        $ontime = $_POST['master']['ontime'];
        $ontime_notes = $_POST['master']['ontime'] == 0 ? $_POST['master']['ontime_notes'] : '';
        $notes = $_POST['master']['assignment_note'];
        // tiket
        $total_ticket_allocation = $_POST['master']['total_ticket_allocation'];
        $total_mt_report_date = $_POST['master']['total_mt_report_date'];
        $total_preventive_mt_date = $_POST['master']['total_preventive_mt_date'];
        $updateParam = "";

        $flag = 0;
        if (isset($_POST['submitResource'])) {
            $flag = 1;
        }

        if (isset($_POST['master']['ticket_allocation'])) {
            $ticket_allocation = $_POST['master']['ticket_allocation'];
            $mt_report_date = $_POST['master']['mt_report_date'];
            $preventive_mt_date = $_POST['master']['preventive_mt_date'];

            $addon_ticket_allocation = $_POST['master']['addon_ticket_allocation'];
            $addon_mt_report_date = $_POST['master']['addon_mt_report_date'];
            $addon_preventive_mt_date = $_POST['master']['addon_preventive_mt_date'];

            $updateParam = "`ticket_allocation` = '$ticket_allocation', 
        `mt_report_date` = '$mt_report_date',
        `preventive_mt_date` = '$preventive_mt_date',
        `addon_ticket_allocation` = '$addon_ticket_allocation', 
        `addon_mt_report_date` = '$addon_mt_report_date',
        `addon_preventive_mt_date` = '$addon_preventive_mt_date',
        `total_ticket_allocation` = '$total_ticket_allocation', 
        `total_mt_report_date` = '$total_mt_report_date',
        `total_preventive_mt_date` = '$total_preventive_mt_date',";
        }

        $queryCheckMtMaster = "SELECT * FROM sa_master_maintenance_date where project_code = '$project_code' and `order_number` = '$order_number'";
        $dataMtMaster = $DBMTdate->get_sqlV2($queryCheckMtMaster);

        if ($dataMtMaster[2] == 0) {

            if (isset($_POST['master']['ticket_allocation'])) {
                $queryInsert = "INSERT INTO `sa_master_maintenance_date` (
                `project_code`, 
                `order_number`, 
                `ontime`,
                `ontime_notes`,
                `notes`,
                `ticket_allocation`, 
                `mt_report_date`,
                `preventive_mt_date`,
                `addon_ticket_allocation`, 
                `addon_mt_report_date`,
                `addon_preventive_mt_date`,
                `total_ticket_allocation`, 
                `total_mt_report_date`,
                `total_preventive_mt_date`,
                `flag`,
                `entry_by`) VALUES (
                '$project_code', 
                '$order_number', 
                '$ontime',
                '$ontime_notes',
                '$notes',
                '$ticket_allocation', 
                '$mt_report_date',
                '$preventive_mt_date',
                '$addon_ticket_allocation', 
                '$addon_mt_report_date',
                '$addon_preventive_mt_date',
                '$total_ticket_allocation', 
                '$total_mt_report_date',
                '$total_preventive_mt_date',
                '$flag',
                '$entry_by')";
            } else {
                $queryInsert = "INSERT INTO `sa_master_maintenance_date` (
                `project_code`, 
                `order_number`, 
                `ontime`,
                `ontime_notes`,
                `notes`,
                `ticket_allocation`, 
                `mt_report_date`,
                `preventive_mt_date`,
                `total_ticket_allocation`, 
                `total_mt_report_date`,
                `total_preventive_mt_date`,
                `flag`,
                `entry_by`) VALUES (
                '$project_code', 
                '$order_number', 
                '$ontime',
                '$ontime_notes',
                '$notes',
                '$total_ticket_allocation', 
                '$total_mt_report_date',
                '$total_preventive_mt_date',
                '$total_ticket_allocation', 
                '$total_mt_report_date',
                '$total_preventive_mt_date',
                '$flag',
                '$entry_by')";
            }

            $res = $DBMTdate->get_res($queryInsert);
        } else {
            if ($dataMtMaster[0]['flag'] == 1) {

                return $ALERT->msgcustom('danger', 'Maintenance report sudah disubmit tidak dapat diubah/submit ulang');
            }

            $update = "UPDATE `sa_master_maintenance_date` SET 
            `project_code` = '$project_code', 
            `order_number` = '$order_number', 
            `ontime` = '$ontime',
            `ontime_notes` = '$ontime_notes',
            `notes` = '$notes',
            `flag` = '$flag',
            {$updateParam}
            `modified_by` = '$entry_by'
          WHERE `project_code` = '$project_code' and `order_number` = '$order_number'";

            $res = $DBMTdate->get_res($update);

            $delete = "DELETE FROM `sa_maintenance_date` WHERE `project_code` = '$project_code' and `order_number` = '$order_number'";
            $res = $DBMTdate->get_res($delete);
        }


        foreach ($_POST['date'] as $key => $value) {

            if ($value != '') {
                $date = $value;

                if ($key == 'preventive_mt_date' || $key == 'mt_report_date') {
                    foreach ($value as $k => $v) {
                        $k++;
                        $childKey = $key . '_' . $k;
                        $childValue = $v;

                        if ($childValue != '') {
                            $queryCheckMtDate = "SELECT * FROM sa_maintenance_date where project_code = '$project_code' and `order_number` = '$order_number' and id_date = '$childKey'";
                            $dataMtDate = $DBMTdate->get_sqlV2($queryCheckMtDate);
                            if ($dataMtDate[2] == 0) {
                                $insert = "INSERT INTO `sa_maintenance_date` (
                            `project_code`, 
                            `order_number`, 
                            `id_date`,
                            `entry_by`,
                            `date`) VALUES (
                            '$project_code', 
                            '$order_number', 
                            '$childKey',
                            '$entry_by',
                            NULLIF('$childValue',''))";
                                $res = $DBMTdate->get_res($insert);
                            }
                        }
                    }
                } else {
                    $queryCheckMtDate = "SELECT * FROM sa_maintenance_date where project_code = '$project_code' and `order_number` = '$order_number' and id_date = '$key'";
                    $dataMtDate = $DBMTdate->get_sqlV2($queryCheckMtDate);

                    if ($dataMtDate[2] == 0) {
                        $insert = "INSERT INTO `sa_maintenance_date` (
                    `project_code`, 
                    `order_number`, 
                    `id_date`,
                    `entry_by`, 
                    `date`) VALUES (
                    '$project_code', 
                    '$order_number', 
                    '$key',
                    '$entry_by',
                    NULLIF('$date',''))";
                        $res = $DBMTdate->get_res($insert);
                    }
                }
            }
        }

        if ($flag == 1) {
            createTaskMT($order_number);
        }

        // $DBWRIKE = get_conn("WRIKE_INTEGRATE");
        // $update_flag = "UPDATE sa_wrike_project_list SET flag_mt_date = 1 WHERE project_code = '$project_code' AND project_type = 'MSI Project Maintenance';";
        // $res = $DBWRIKE->get_res($update_flag);

        $to = "$finalAllTo";
        $from = $_SESSION['Microservices_UserEmail'];
        $cc = "$finalAllCc";
        $bcc = "";
        $reply = "$from";
        $subject = "[MSIZONE] Project Charter Maintenance";
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
        for ($i = 0; $i < count($explodeAllCc); $i++) {
            $finalAllRoles2 = str_replace(" - ", " ", $explodeAllRoles[$i]);
            $msg .= "<tr><td>Resources</td> <td>: </td><td>" . $explodeAllNameResources[$i] . "</td></tr>";
            $msg .= "<tr><td>Service Budget Roles</td> <td>: </td><td>" . $finalAllRoles2 . "</td></tr>";
            $msg .= "<tr><td>Project Roles</td> <td>: </td><td>" . $explodeAllProjectRoles[$i] . "</td></tr>";
            if ($explodeAllStartProgress[$i] == 00) {
                $explodeAllStartProgress[$i] = 0;
            }
            if ($explodeEndProgress[$i] == 0100) {
                $explodeEndProgress[$i] = "100";
            }
            $msg .= "<tr><td>Status</td><td>: </td><td>" . $explodeAllStatus[$i] . " " . $explodeAllStartProgress[$i] . "% - " . $explodeEndProgress[$i] . "%</td></tr>";
            $msg .= "<tr><td colspan='3'></td></tr>";
            $msg .= "<tr><td colspan='3'></td></tr>";
        }
        $msg .= "<tr><td colspan='3'><a href='https://msizone.mastersystem.co.id/index.php?mod=resource_assignment_mt&status=approved_assignment' style='background-color: #D21312; 
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
        $msg .= "<p>" . $_SESSION['Microservices_UserName'] . "<br/></p>";
        $msg .= "<p>" . date("Y/m/d") . "<br/></p>";
        $msg .= "</td><td width='30%' rowspan='3'>";
        $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
        $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
        $msg .= "</table>";

        $headers = "From: " . $_SESSION['Microservices_UserEmail'] . " \r\n" .
            "Reply-To: " . $_SESSION['Microservices_UserEmail'] . " \r\n" .
            "Cc: $finalAllCc \r\n" .
            "Bcc: \r\n" .
            "MIME-Version: 1.0" . "\r\n" .
            "Content-Type: text/html; charset=UTF-8" . "\r\n" .
            "X-Mailer: PHP/" . phpversion();

        $ALERT = new Alert();
        if (!mail($to, $subject, $msg, $headers)) {
            echo "Email gagal terkirim pada jam " . date("d M Y G:i:s");
        } else {
            echo "Email terkirim pada jam " . date("d M Y G:i:s");
        }
    } else if ($totalRowTemporary < 1 && isset($_POST['date'])) {
        $db_mt = "MAINTENANCE_DATE";
        $DBMTdate = get_conn($db_mt);
        $userName = addslashes($_SESSION['Microservices_UserName']);
        $entry_by = $userName . "<" . $_SESSION['Microservices_UserEmail'] . ">";
        $project_code = $_POST['project_code'];
        $order_number = $_POST['master']['order_number'];
        $ontime = $_POST['master']['ontime'];
        $ontime_notes = $_POST['master']['ontime'] == 0 ? $_POST['master']['ontime_notes'] : '';
        $notes = $_POST['master']['assignment_note'];
        // tiket
        $total_ticket_allocation = $_POST['master']['total_ticket_allocation'];
        $total_mt_report_date = $_POST['master']['total_mt_report_date'];
        $total_preventive_mt_date = $_POST['master']['total_preventive_mt_date'];
        $updateParam = "";

        $flag = 0;
        if (isset($_POST['submitResource'])) {
            $flag = 1;
        }

        if (isset($_POST['master']['ticket_allocation'])) {
            $ticket_allocation = $_POST['master']['ticket_allocation'];
            $mt_report_date = $_POST['master']['mt_report_date'];
            $preventive_mt_date = $_POST['master']['preventive_mt_date'];

            $addon_ticket_allocation = $_POST['master']['addon_ticket_allocation'];
            $addon_mt_report_date = $_POST['master']['addon_mt_report_date'];
            $addon_preventive_mt_date = $_POST['master']['addon_preventive_mt_date'];

            $updateParam = "`ticket_allocation` = '$ticket_allocation', 
        `mt_report_date` = '$mt_report_date',
        `preventive_mt_date` = '$preventive_mt_date',
        `addon_ticket_allocation` = '$addon_ticket_allocation', 
        `addon_mt_report_date` = '$addon_mt_report_date',
        `addon_preventive_mt_date` = '$addon_preventive_mt_date',
        `total_ticket_allocation` = '$total_ticket_allocation', 
        `total_mt_report_date` = '$total_mt_report_date',
        `total_preventive_mt_date` = '$total_preventive_mt_date',";
        }

        $queryCheckMtMaster = "SELECT * FROM sa_master_maintenance_date where project_code = '$project_code' and `order_number` = '$order_number'";
        $dataMtMaster = $DBMTdate->get_sqlV2($queryCheckMtMaster);

        if ($dataMtMaster[2] == 0) {

            if (isset($_POST['master']['ticket_allocation'])) {
                $queryInsert = "INSERT INTO `sa_master_maintenance_date` (
                `project_code`, 
                `order_number`, 
                `ontime`,
                `ontime_notes`,
                `notes`,
                `ticket_allocation`, 
                `mt_report_date`,
                `preventive_mt_date`,
                `addon_ticket_allocation`, 
                `addon_mt_report_date`,
                `addon_preventive_mt_date`,
                `total_ticket_allocation`, 
                `total_mt_report_date`,
                `total_preventive_mt_date`,
                `flag`,
                `entry_by`) VALUES (
                '$project_code', 
                '$order_number', 
                '$ontime',
                '$ontime_notes',
                '$notes',
                '$ticket_allocation', 
                '$mt_report_date',
                '$preventive_mt_date',
                '$addon_ticket_allocation', 
                '$addon_mt_report_date',
                '$addon_preventive_mt_date',
                '$total_ticket_allocation', 
                '$total_mt_report_date',
                '$total_preventive_mt_date',
                '$flag',
                '$entry_by')";
            } else {
                $queryInsert = "INSERT INTO `sa_master_maintenance_date` (
                `project_code`, 
                `order_number`, 
                `ontime`,
                `ontime_notes`,
                `notes`,
                `ticket_allocation`, 
                `mt_report_date`,
                `preventive_mt_date`,
                `total_ticket_allocation`, 
                `total_mt_report_date`,
                `total_preventive_mt_date`,
                `flag`,
                `entry_by`) VALUES (
                '$project_code', 
                '$order_number', 
                '$ontime',
                '$ontime_notes',
                '$notes',
                '$total_ticket_allocation', 
                '$total_mt_report_date',
                '$total_preventive_mt_date',
                '$total_ticket_allocation', 
                '$total_mt_report_date',
                '$total_preventive_mt_date',
                '$flag',
                '$entry_by')";
            }

            $res = $DBMTdate->get_res($queryInsert);
        } else {
            if ($dataMtMaster[0]['flag'] == 1) {

                return $ALERT->msgcustom('danger', 'Maintenance report sudah disubmit tidak dapat diubah/submit ulang');
            }

            $update = "UPDATE `sa_master_maintenance_date` SET 
            `project_code` = '$project_code', 
            `order_number` = '$order_number', 
            `ontime` = '$ontime',
            `ontime_notes` = '$ontime_notes',
            `notes` = '$notes',
            `flag` = '$flag',
            {$updateParam}
            `modified_by` = '$entry_by'
          WHERE `project_code` = '$project_code' and `order_number` = '$order_number'";

            $res = $DBMTdate->get_res($update);

            $delete = "DELETE FROM `sa_maintenance_date` WHERE `project_code` = '$project_code' and `order_number` = '$order_number'";
            $res = $DBMTdate->get_res($delete);
        }


        foreach ($_POST['date'] as $key => $value) {

            if ($value != '') {
                $date = $value;

                if ($key == 'preventive_mt_date' || $key == 'mt_report_date') {
                    foreach ($value as $k => $v) {
                        $k++;
                        $childKey = $key . '_' . $k;
                        $childValue = $v;

                        if ($childValue != '') {
                            $queryCheckMtDate = "SELECT * FROM sa_maintenance_date where project_code = '$project_code' and `order_number` = '$order_number' and id_date = '$childKey'";
                            $dataMtDate = $DBMTdate->get_sqlV2($queryCheckMtDate);
                            if ($dataMtDate[2] == 0) {
                                $insert = "INSERT INTO `sa_maintenance_date` (
                            `project_code`, 
                            `order_number`, 
                            `id_date`,
                            `entry_by`,
                            `date`) VALUES (
                            '$project_code', 
                            '$order_number', 
                            '$childKey',
                            '$entry_by',
                            NULLIF('$childValue',''))";
                                $res = $DBMTdate->get_res($insert);
                            }
                        }
                    }
                } else {
                    $queryCheckMtDate = "SELECT * FROM sa_maintenance_date where project_code = '$project_code' and `order_number` = '$order_number' and id_date = '$key'";
                    $dataMtDate = $DBMTdate->get_sqlV2($queryCheckMtDate);

                    if ($dataMtDate[2] == 0) {
                        $insert = "INSERT INTO `sa_maintenance_date` (
                    `project_code`, 
                    `order_number`, 
                    `id_date`,
                    `entry_by`, 
                    `date`) VALUES (
                    '$project_code', 
                    '$order_number', 
                    '$key',
                    '$entry_by',
                    NULLIF('$date',''))";
                        $res = $DBMTdate->get_res($insert);
                    }
                }
            }
        }

        if ($flag == 1) {
            createTaskMT($order_number);
        }
    } else {
        echo "<script>alert('Mohon maaf, anda belum memilih resource untuk project ini.');</script>";
    }
}

if (isset($_POST['save'])) {
    $userName = $_SESSION['Microservices_UserName'];
    $userName = addslashes($userName);

    // $condition = "id=" . $_POST['id'];

    // $updateData = sprintf(
    //     "`resource_email`= %s, `roles`= %s, `status`= %s, `start_progress` = %s, `end_progress` = %s, `description`= %s, `modified_by` = %s, `modified_in_msizone`= %s",
    //     GetSQLValueString($_POST['email'], "text"),
    //     GetSQLValueString($_POST['roles'], "text"),
    //     GetSQLValueString($_POST['status'], "text"),
    //     GetSQLValueString($_POST['startProgress'], "int"),
    //     GetSQLValueString($_POST['endProgress'], "int"),
    //     GetSQLValueString($_POST['description'], "text"),
    //     GetSQLValueString($userName . "<" . $_SESSION['Microservices_UserEmail'] . ">", "text"),
    //     GetSQLValueString(date("Y-m-d H:i:s"), "text")
    // );

    // $resUpdate = $DTSB->update_data("resource_assignment", $updateData, $condition);
    $ALERT->savedata();
}

// if (isset($_POST['btn_approve'])) {
//     if (isset($_POST['id'])) {
//         $checkbox = $_POST['id'];
//         $userNameApprove = $_POST['userSession'];

//         for ($i = 0; $i < count($checkbox); $i++) {
//             $idCheckbox = $checkbox[$i];

//             $conditionApprove = "id=$idCheckbox";
//             $updateDataApprove = sprintf(
//                 "`approval_status`= 'approved', `modified_by` = %s, `modified_in_msizone`= %s",
//                 GetSQLValueString($userNameApprove, "text"),
//                 GetSQLValueString(date("Y-m-d H:i:s"), "text")
//             );

//             $resUpdateApprove = $DTSB->update_data("resource_assignment", $updateDataApprove, $conditionApprove);
//             $ALERT->savedata();
//         }
//     } else {
//         echo "<script>alert('Anda belum checklist data apapun !')</script>";
//     }
// }

if (isset($_POST['btn_approve'])) {
    if (isset($_POST['id'])) {
        $checkbox = $_POST['id'];
        $userNameApprove = $_POST['userSession'];

        try {
            $resultAssignment = $WRIKE_PROJECT->masterForwardAssignment($checkbox);
            echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>' . $resultAssignment . '</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>';
        } catch (Exception $e) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>' . $e->getMessage() . ' coba kembali' . '</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>';
        }
    } else {
        echo "<script>alert('Anda belum checklist data apapun !')</script>";
    }
}

if (isset($_POST['btn_reject'])) {
    if (isset($_POST['id'])) {
        $checkbox = $_POST['id'];
        $userNameApprove = $_POST['userSession'];

        for ($i = 0; $i < count($checkbox); $i++) {
            $idCheckbox = $checkbox[$i];

            $conditionApprove = "id=$idCheckbox";
            $updateDataApprove = sprintf(
                "`approval_status`= 'rejected', `modified_by` = %s, `modified_in_msizone`= %s",
                GetSQLValueString($userNameApprove, "text"),
                GetSQLValueString(date("Y-m-d H:i:s"), "text")
            );

            $resUpdateApprove = $DTSB->update_data("resource_assignment", $updateDataApprove, $conditionApprove);
            $ALERT->savedata();
        }
    } else {
        echo "<script>alert('Anda belum checklist data apapun !')</script>";
    }
}


function OverrideApprovalTo()
{
    $db_wr = "WRIKE_INTEGRATE";
    $DBWR = get_conn($db_wr);

    $db_hcm = "HCM";
    $DBHCM = get_conn($db_hcm);

    $sqlLoopData = "SELECT * FROM sa_resource_assignment WHERE approval_to LIKE '%Lintar%'";
    $dataLoop = $DBWR->get_sql($sqlLoopData);
    $rowDataLoop = $dataLoop[0];
    $resDataLoop = $dataLoop[1];
    $totalRowDataLoop = $dataLoop[2];

    do {
        $id = $rowDataLoop['id'];
        $resourceEmail = $rowDataLoop['resource_email'];
        $orderNumber = $rowDataLoop['order_number'];
        $noSO = $rowDataLoop['no_so'];
        $projectName = $rowDataLoop['project_name'];
        $projectType = $rowDataLoop['project_type'];
        $rolesSB = $rowDataLoop['roles'];
        $projectRolesSB = $rowDataLoop['project_roles'];
        $status = $rowDataLoop['status'];
        $startProgress = $rowDataLoop['start_progress'];
        $endProgress = $rowDataLoop['end_progress'];
        $projectCode = $rowDataLoop['project_code'];
        $createdBy = $rowDataLoop['created_by'];

        $explodeResourceEmail = explode("<", $resourceEmail);
        $finalResourceEmail = str_replace(">", "", $explodeResourceEmail[1]);

        $approvalToRaw = $DBHCM->get_general_manager($finalResourceEmail);
        if ($approvalToRaw == 'Moch. Lintar Wahyu Wardana<lintar@mastersystem.co.id>') {
            $approvalTo = 'Andino Holi Fajra<andino.hf@mastersystem.co.id>';
        } elseif ($approvalToRaw == 'lintar@mastersystem.co.id') {
            $approvalTo = 'Andino Holi Fajra<andino.hf@mastersystem.co.id>';
        } else {
            $approvalTo = $DBHCM->get_general_manager($finalResourceEmail);
        }
        $finalLeaderNameExplodeRaw = explode("<", $approvalTo);
        $resourceEmailExplode = $finalLeaderNameExplodeRaw[1];
        $replacedLeaderEmail = str_replace(">", "", "$resourceEmailExplode");

        $to = "$replacedLeaderEmail";
        $from = $finalResourceEmail;
        $cc = "$finalResourceEmail";
        $bcc = "";
        $reply = "$from";
        $subject = "[MSIZONE] Project Charter Maintenance";
        $msg = "<table width='100%'";
        $msg .= "<tr><td width='30%' rowspan='3'></td><td style='width:40%; border: thin solid #dadada; padding:20px'>";
        $msg .= "<img src='https://www.f5.com/content/dam/f5-com/page-assets-en/home-en/services/professional-services/guardian-partners/mastersystem-logo.jpg' width='150' height='50'>";
        $msg .= "<br/>";
        $msg .= "<p>Dear " . $finalLeaderNameExplodeRaw[0] . ",</p>";
        $msg .= "<p>Mohon melakukan approval kepada resource dibawah ini di Project Charter : </p>";
        $msg .= "<p>";
        $msg .= "<table style='width:100%;'>";
        $msg .= "<tr><td>Project Code</td><td>: </td><td>$projectCode</td></tr>";
        $msg .= "<tr><td>Order Number</td><td>: </td><td>$orderNumber</td></tr>";
        $msg .= "<tr><td>No SO</td><td>: </td><td>$noSO</td></tr>";
        $msg .= "<tr><td>Project Name</td><td>: </td><td>$projectName</td></tr>";
        $msg .= "<tr><td>Project Type</td><td>: </td><td>$projectType</td></tr>";
        $msg .= "<tr><td>Resources</td> <td>: </td><td>" . $resourceEmail . "</td></tr>";
        $msg .= "<tr><td>Service Budget Roles</td> <td>: </td><td>" . $rolesSB . "</td></tr>";
        $msg .= "<tr><td>Project Roles</td> <td>: </td><td>" . $projectRolesSB . "</td></tr>";
        $msg .= "<tr><td colspan='3'></td></tr>";
        $msg .= "<tr><td colspan='3'></td></tr>";
        $msg .= "<tr><td colspan='3'></td></tr>";
        $msg .= "<tr><td colspan='3'><b>From :</b></td></tr>";
        $msg .= "<tr><td>Status</td><td>: </td><td>" . $status . "</td></tr>";
        $msg .= "<tr><td>Start Progress</td><td>: </td><td>" . $startProgress . "%</td></tr>";
        $msg .= "<tr><td>End Progress</td><td>: </td><td>" . $endProgress . "%</td></tr>";
        $msg .= "<tr><td colspan='3'></td></tr>";
        $msg .= "<tr><td colspan='3'><b>To :</b></td></tr>";
        $msg .= "<tr><td>Status</td><td>: </td><td>" . $status . "</td></tr>";
        $msg .= "<tr><td>Start Progress</td><td>: </td><td>" . $startProgress . "%</td></tr>";
        $msg .= "<tr><td>End Progress</td><td>: </td><td>" . $endProgress . "%</td></tr>";
        $msg .= "<tr><td colspan='3'></td></tr>";
        $msg .= "<tr><td colspan='3'></td></tr>";
        $msg .= "<tr><td colspan='3'><a href='https://msizone.mastersystem.co.id/index.php?mod=resource_assignment_mt&status=approved_assignment' style='background-color: #D21312; 
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
        $msg .= "<p>Alya Yulisti Fauziyyah Bachtiar<br/></p>";
        $msg .= "<p>" . date("Y/m/d") . "<br/></p>";
        $msg .= "</td><td width='30%' rowspan='3'>";
        $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
        $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
        $msg .= "</table>";

        $headers = "From: " . $from . "\r\n" .
            "Reply-To: " . $from . "\r\n" .
            "Cc: " . $cc . "\r\n" .
            "Bcc: " . $bcc . "\r\n" .
            "MIME-Version: 1.0" . "\r\n" .
            "Content-Type: text/html; charset=UTF-8" . "\r\n" .
            "X-Mailer: PHP/" . phpversion();

        $ALERT = new Alert();
        if (!mail(
            $to,
            $subject,
            $msg,
            $headers
        )) {
            echo
            "Email gagal terkirim pada jam " . date("d M Y G:i:s") . "$to" . "/ $subject";
        } else {
            echo
            "Email terkirim pada jam " . date("d M Y G:i:s");
        }
        $conditionApprove = "id=$id";
        $updateDataApprove = sprintf(
            "`approval_to`= '$approvalTo'",
            GetSQLValueString($approvalTo, "text")
        );
        $resUpdateApprove = $DBWR->update_data("resource_assignment", $updateDataApprove, $conditionApprove);
        echo "$id - $finalResourceEmail - $approvalTo <br/>";
    } while ($rowDataLoop = $resDataLoop->fetch_assoc());
}

function OverrideOrderNumber()
{
    $db_wr = "WRIKE_INTEGRATE";
    $DBWR = get_conn($db_wr);

    $db_sb = "SERVICE_BUDGET";
    $DBSB = get_conn($db_sb);

    $sqlCheckOrderNumber = "SELECT distinct no_so FROM sa_resource_assignment 
    WHERE order_number = '' 
    OR order_number = NULL or order_number is NULL";
    $dataGet = $DBWR->get_sql($sqlCheckOrderNumber);
    $rowOrderNumber = $dataGet[0];
    $resOrderNumber = $dataGet[1];
    $totalRowOrderNumber = $dataGet[2];

    if ($totalRowOrderNumber > 0) {
        do {
            $noSO = $rowOrderNumber['no_so'];

            // Lookup Order Number
            $sqlGetOrderNumber = "SELECT order_number FROM sa_trx_project_list 
            WHERE so_number = '$noSO'
            AND status = 'acknowledge'";
            $dataOrderNumber = $DBSB->get_sql($sqlGetOrderNumber);

            if (isset($dataOrderNumber[0]['order_number'])) {
                $orderNumber = $dataOrderNumber[0]['order_number'];
            } else {
                $orderNumber = '';
            }

            $conditionUpdate = "no_so = '$noSO'";
            $updateData = sprintf(
                "`order_number`= '$orderNumber'",
                GetSQLValueString($orderNumber, "text")
            );
            $resUpdate = $DBWR->update_data("resource_assignment", $updateData, $conditionUpdate);

            echo "Data berhasil di update<br/>";
        } while ($rowOrderNumber = $resOrderNumber->fetch_assoc());
    } else {
        echo "Semua data sudah memiliki Order Number <br/>";
    }
}


if (isset($_POST['addReport']) || isset($_POST['saveReport']) || isset($_POST['submitReport'])) {
    $db_mt = "MAINTENANCE_DATE";
    $DBMTdate = get_conn($db_mt);
    $userName = addslashes($_SESSION['Microservices_UserName']);
    $entry_by = $userName . "<" . $_SESSION['Microservices_UserEmail'] . ">";
    $project_code = $_POST['project_code'];
    $order_number = $_POST['master']['order_number'];
    $ontime = $_POST['master']['ontime'];
    $ontime_notes = $_POST['master']['ontime'] == 0 ? $_POST['master']['ontime_notes'] : '';
    $notes = $_POST['master']['assignment_note'];
    // tiket
    $total_ticket_allocation = $_POST['master']['total_ticket_allocation'];
    $total_mt_report_date = $_POST['master']['total_mt_report_date'];
    $total_preventive_mt_date = $_POST['master']['total_preventive_mt_date'];
    $updateParam = "";

    $flag = 0;
    if (isset($_POST['submitReport'])) {
        $flag = 1;
    }

    if (isset($_POST['master']['ticket_allocation'])) {
        $ticket_allocation = $_POST['master']['ticket_allocation'];
        $mt_report_date = $_POST['master']['mt_report_date'];
        $preventive_mt_date = $_POST['master']['preventive_mt_date'];

        $addon_ticket_allocation = $_POST['master']['addon_ticket_allocation'];
        $addon_mt_report_date = $_POST['master']['addon_mt_report_date'];
        $addon_preventive_mt_date = $_POST['master']['addon_preventive_mt_date'];

        $updateParam = "`ticket_allocation` = '$ticket_allocation', 
        `mt_report_date` = '$mt_report_date',
        `preventive_mt_date` = '$preventive_mt_date',
        `addon_ticket_allocation` = '$addon_ticket_allocation', 
        `addon_mt_report_date` = '$addon_mt_report_date',
        `addon_preventive_mt_date` = '$addon_preventive_mt_date',
        `total_ticket_allocation` = '$total_ticket_allocation', 
        `total_mt_report_date` = '$total_mt_report_date',
        `total_preventive_mt_date` = '$total_preventive_mt_date',";
    }

    $queryCheckMtMaster = "SELECT * FROM sa_master_maintenance_date where project_code = '$project_code' and `order_number` = '$order_number'";
    $dataMtMaster = $DBMTdate->get_sqlV2($queryCheckMtMaster);

    if ($dataMtMaster[2] == 0) {

        if (isset($_POST['master']['ticket_allocation'])) {
            $queryInsert = "INSERT INTO `sa_master_maintenance_date` (
                `project_code`, 
                `order_number`, 
                `ontime`,
                `ontime_notes`,
                `notes`,
                `ticket_allocation`, 
                `mt_report_date`,
                `preventive_mt_date`,
                `addon_ticket_allocation`, 
                `addon_mt_report_date`,
                `addon_preventive_mt_date`,
                `total_ticket_allocation`, 
                `total_mt_report_date`,
                `total_preventive_mt_date`,
                `flag`,
                `entry_by`) VALUES (
                '$project_code', 
                '$order_number', 
                '$ontime',
                '$ontime_notes',
                '$notes',
                '$ticket_allocation', 
                '$mt_report_date',
                '$preventive_mt_date',
                '$addon_ticket_allocation', 
                '$addon_mt_report_date',
                '$addon_preventive_mt_date',
                '$total_ticket_allocation', 
                '$total_mt_report_date',
                '$total_preventive_mt_date',
                '$flag',
                '$entry_by')";
        } else {
            $queryInsert = "INSERT INTO `sa_master_maintenance_date` (
                `project_code`, 
                `order_number`, 
                `ontime`,
                `ontime_notes`,
                `notes`,
                `ticket_allocation`, 
                `mt_report_date`,
                `preventive_mt_date`,
                `total_ticket_allocation`, 
                `total_mt_report_date`,
                `total_preventive_mt_date`,
                `flag`,
                `entry_by`) VALUES (
                '$project_code', 
                '$order_number', 
                '$ontime',
                '$ontime_notes',
                '$notes',
                '$total_ticket_allocation', 
                '$total_mt_report_date',
                '$total_preventive_mt_date',
                '$total_ticket_allocation', 
                '$total_mt_report_date',
                '$total_preventive_mt_date',
                '$flag',
                '$entry_by')";
        }

        $res = $DBMTdate->get_res($queryInsert);
    } else {
        if ($dataMtMaster[0]['flag'] == 1) {
            return $ALERT->msgcustom('danger', 'Maintenance report sudah disubmit tidak dapat diubah/submit ulang');
        }

        $update = "UPDATE `sa_master_maintenance_date` SET 
            `project_code` = '$project_code', 
            `order_number` = '$order_number', 
            `ontime` = '$ontime',
            `ontime_notes` = '$ontime_notes',
            `notes` = '$notes',
            `flag` = '$flag',
            {$updateParam}
            `modified_by` = '$entry_by'
          WHERE `project_code` = '$project_code' and `order_number` = '$order_number'";

        $res = $DBMTdate->get_res($update);

        $delete = "DELETE FROM `sa_maintenance_date` WHERE `project_code` = '$project_code' and `order_number` = '$order_number'";
        $res = $DBMTdate->get_res($delete);
    }


    foreach ($_POST['date'] as $key => $value) {

        if ($value != '') {
            $date = $value;

            if ($key == 'preventive_mt_date' || $key == 'mt_report_date') {
                foreach ($value as $k => $v) {
                    $k++;
                    $childKey = $key . '_' . $k;
                    $childValue = $v;

                    if ($childValue != '') {
                        $queryCheckMtDate = "SELECT * FROM sa_maintenance_date where project_code = '$project_code' and `order_number` = '$order_number' and id_date = '$childKey'";
                        $dataMtDate = $DBMTdate->get_sqlV2($queryCheckMtDate);
                        if ($dataMtDate[2] == 0) {
                            $insert = "INSERT INTO `sa_maintenance_date` (
                            `project_code`, 
                            `order_number`, 
                            `id_date`,
                            `entry_by`,
                            `date`) VALUES (
                            '$project_code', 
                            '$order_number', 
                            '$childKey',
                            '$entry_by',
                            NULLIF('$childValue',''))";
                            $res = $DBMTdate->get_res($insert);
                        }
                    }
                }
            } else {
                $queryCheckMtDate = "SELECT * FROM sa_maintenance_date where project_code = '$project_code' and `order_number` = '$order_number' and id_date = '$key'";
                $dataMtDate = $DBMTdate->get_sqlV2($queryCheckMtDate);

                if ($dataMtDate[2] == 0) {
                    $insert = "INSERT INTO `sa_maintenance_date` (
                    `project_code`, 
                    `order_number`, 
                    `id_date`,
                    `entry_by`, 
                    `date`) VALUES (
                    '$project_code', 
                    '$order_number', 
                    '$key',
                    '$entry_by',
                    NULLIF('$date',''))";
                    $res = $DBMTdate->get_res($insert);
                }
            }
        }
    }

    if ($flag == 1) {
        createTaskMT($order_number);
    }

    // $DBWRIKE = get_conn("WRIKE_INTEGRATE");
    // $update_flag = "UPDATE sa_wrike_project_list SET flag_mt_date = 1 WHERE project_code = '$project_code' AND project_type = 'MSI Project Maintenance';";
    // $res = $DBWRIKE->get_res($update_flag);
    $ALERT->savedata();
}

function createTaskMT($order_number)
{

    $db_wr = "WRIKE_INTEGRATE";
    $DBWR = get_conn($db_wr);

    $dbdb = "DASHBOARD_KPI";
    $DBKPI = get_conn($dbdb);

    $getProjectID = $DBWR->get_sqlV2("SELECT * FROM sa_wrike_project_list WHERE order_number='$order_number' AND project_type LIKE '%Maintenance%'");
    $projectID = $getProjectID[0]['id'];
    $checkTaskParent = $DBKPI->get_sqlV2("SELECT * FROM sa_master_maintenance_date WHERE order_number='$order_number'");
    $preventiveCheck = $checkTaskParent[0]['total_preventive_mt_date'];
    $reportCheck = $checkTaskParent[0]['total_mt_report_date'];
    if ($preventiveCheck > 0) {
        $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODU3Mjg5NyxcImNcIjo0NjM4NjM2LFwidVwiOjE2NDc3MzM1LFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjc4NDMxNTkxfQ.ultUzaDzSh_0CxnrbnCTXAXWwD6cHgKJCmtKWAtG5gI";
        $url = "https://www.wrike.com/api/v4/folders/$projectID/tasks";
        $data = array('importance' => "Normal", 'title' => "Submit Preventive Maintenance Task", 'description' => "Task Maintenance Preventive");
        $postdata = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        $result = curl_exec($ch);
        curl_close($ch);
        // print_r($result);

        $result = json_decode($result, true);
        $taskIdpreventive = $result['data'][0]['id'];
    }
    if ($reportCheck > 0) {
        $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODU3Mjg5NyxcImNcIjo0NjM4NjM2LFwidVwiOjE2NDc3MzM1LFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjc4NDMxNTkxfQ.ultUzaDzSh_0CxnrbnCTXAXWwD6cHgKJCmtKWAtG5gI";
        $url = "https://www.wrike.com/api/v4/folders/$projectID/tasks";
        $data = array('importance' => "Normal", 'title' => "Submit Maintenance Report Task", 'description' => "Task Maintenance Report");
        $postdata = json_encode($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        $result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($result, true);
        $taskIdreport = $result['data'][0]['id'];
    }
    $getTaskDetail = $DBKPI->get_sqlV2("SELECT * FROM sa_maintenance_date WHERE order_number='$order_number' AND (id_date LIKE '%report%' OR id_date LIKE '%preventive%') ORDER BY id DESC");
    if ($getTaskDetail[2] > 0) {
        while ($row = $getTaskDetail[1]->fetch_assoc()) {
            $taskNameraw = $row['id_date'];
            $taskNameraw2 = str_replace("_", " ", $taskNameraw);
            $taskName1 = "Submit " . $taskNameraw2;
            $taskName = ucwords($taskName1);
            $taskDate = $row['date'];
            if (strpos($taskName, "Preventive") !== false) {
                $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODU3Mjg5NyxcImNcIjo0NjM4NjM2LFwidVwiOjE2NDc3MzM1LFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjc4NDMxNTkxfQ.ultUzaDzSh_0CxnrbnCTXAXWwD6cHgKJCmtKWAtG5gI";
                $url = "https://www.wrike.com/api/v4/folders/$projectID/tasks";
                $data = array('importance' => "Normal", 'title' => "$taskName", 'description' => "Maintenance Report", "superTasks" => "['$taskIdpreventive']", "dates" => "{'start':'$taskDate','due':'$taskDate'}");
                $postdata = json_encode($data);

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                $result = curl_exec($ch);
                curl_close($ch);

                $result = json_decode($result, true);
                // $taskIdreport = $result['data'][0]['id'];
            } else {
                $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODU3Mjg5NyxcImNcIjo0NjM4NjM2LFwidVwiOjE2NDc3MzM1LFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjc4NDMxNTkxfQ.ultUzaDzSh_0CxnrbnCTXAXWwD6cHgKJCmtKWAtG5gI";
                $url = "https://www.wrike.com/api/v4/folders/$projectID/tasks";
                $data = array('importance' => "Normal", 'title' => "$taskName", 'description' => "Maintenance Report", "superTasks" => "['$taskIdreport']", "dates" => "{'start':'$taskDate','due':'$taskDate'}");
                $postdata = json_encode($data);

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                $result = curl_exec($ch);
                curl_close($ch);

                $result = json_decode($result, true);
            }
        }
    }
}
