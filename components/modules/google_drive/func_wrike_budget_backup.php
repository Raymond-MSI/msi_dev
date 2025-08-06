<?php

function get_data_sb()
{
    global $DBGD, $DBWR;
    $db_sb = "SERVICE_BUDGET";
    // $DBSB = get_conn($db_sb);
    $DBSB = get_conn($db_sb);

    $querySB = "SELECT a.project_id, a.project_code, a.project_name, a.project_name_internal, a.order_number, a.amount_idr, a.amount_usd, a.so_number, a.bundling, b.tos_id, b.service_type, c.tos_name, a.status FROM sa_trx_project_list AS a 
    LEFT JOIN sa_trx_project_implementations AS b ON a.project_id = b.project_id
    LEFT JOIN sa_mst_type_of_service AS c ON b.tos_id = c.tos_id
    WHERE a.status = 'acknowledge' AND a.bundling != '' AND a.sbtype = 1 AND c.tos_id IN (1,2,3,4,5,6,7)
    AND a.project_name_internal != ''
    AND a.order_number != ''
    ORDER BY project_id";

    $tbl_wrike_config = 'wrike_config';
    $tbl_trx_project_list = 'trx_project_list';
    $conditionSB = "status = 'acknowledge'";
    $dataSB = $DBSB->get_sql($querySB);
    $rowSB = $dataSB[0];
    $resSB = $dataSB[1];
    $totalRowSB = $dataSB[2];

    $tbl_trx_project_implementations = 'trx_project_implementations';
    $dataSBExpand = $DBSB->get_data($tbl_trx_project_list);
    $rowSBExpand = $dataSBExpand[0];
    $resSBExpand = $dataSBExpand[1];
    $totalRowSBExpand = $dataSBExpand[2];

    $tbl_initial_project = "initial_project";
    $tbl_wrike_project_list = "wrike_project_list";

    // do{
    //     $arraySBExpand[] = $rowSBExpand;
    // }while($rowSBExpand = $resSBExpand->fetch_assoc());

    do {
        $projectId = $rowSB['project_id'];
        $projectCode = $rowSB['project_code'];
        $orderNumber = $rowSB['order_number'];
        $projectName = $rowSB['project_name'];
        $internalProjectName = $rowSB['project_name_internal'];
        $projectNameExplode = explode('#', $projectName);
        $tosId = $rowSB['tos_id'];
        $serviceType = $rowSB['service_type'];
        $tosName = $rowSB['tos_name'];

        if ($serviceType == 1) {
            $serviceType = "Implementation";
            $conditionImplementation = "object = 'Blueprint' AND condition1 = '$serviceType'";
            $configImplementation = $DBWR->get_data($tbl_wrike_config, $conditionImplementation);
            $blueprintId = $configImplementation[0]['object_id'];

            date_default_timezone_set('Indonesia/Jakarta');
            $date = date('Y-m-d', time());

            //Check Table Wrike Project List Implementation
            $conditionWrikeProjectList = "project_code = '$projectCode' AND project_type = 'MSI Project Implementation'";
            $dataWrikeProjectList = $DBWR->get_data($tbl_wrike_project_list, $conditionWrikeProjectList);
            $projectCodeWPL = $dataWrikeProjectList[0]['project_code'];
            $projectTypeWPL = $dataWrikeProjectList[0]['project_type'];
            $totalRowWPL = $dataWrikeProjectList[2];

            //Check Table Initial Project
            $conditionInitialImplementation = "project_code='$projectCode' AND project_type='$serviceType' AND order_number = '$orderNumber'";
            $dataInitialProject = $DBWR->get_data($tbl_initial_project, $conditionInitialImplementation);
            $totalRowInitialProject = $dataInitialProject[2];

            if ($totalRowWPL > 0) {
                echo "Project $serviceType dengan KP. $projectCode sudah ada di WRIKE <br/>";
            } else {
                if ($totalRowInitialProject > 0) {
                    echo "Project Implementation dengan KP($projectCode) sudah dibuat <br/>";
                } else {
                    $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
                    $url = "https://www.wrike.com/api/v4/folder_blueprints/$blueprintId/launch_async";
                    //?parent=IEAEOPF5I4U765EL&title=".$projectNameExplode[0]."&titlePrefix=".$projectNameExplode[1]."&copyCustomFields=true&rescheduleMode=Start&rescheduleDate=$date";
                    $data = array('parent' => "IEAEOPF5I4U765EL", 'title' => "" . $projectNameExplode[0] . "", 'titlePrefix' => "[" . $internalProjectName . "]", 'copyCustomFields' => "true", 'rescheduleMode' => "Start", 'rescheduleDate' => "$date");
                    $postdata = json_encode($data);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                    $result = curl_exec($ch);
                    curl_close($ch);
                    print_r($result);

                    echo "<br/>";

                    $result = json_decode($result, true);
                    $jobId = $result['data'][0]['id'];

                    $insertJobId = sprintf(
                        "(`project_code`,`job_id`, `project_type`, `order_number`) VALUES ('$projectCode', '$jobId' , '$serviceType', '$orderNumber')",
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($jobId, "text"),
                        GetSQLValueString($serviceType, "text"),
                        GetSQLValueString($orderNumber, "text")
                    );

                    $resJobId = $DBWR->insert_data($tbl_initial_project, $insertJobId);

                    // echo "Project Name = " . $projectNameExplode[0] . " Prefix = " . $projectNameExplode[1] . "<br/>";
                    echo "$projectId - $projectCode - $projectName - $serviceType - $blueprintId - $jobId <br/>";
                }
            }
        } else if ($serviceType == 2 && $tosId == 5) {
            $serviceType = "Maintenance";
            $tosName = "Gold";

            $conditionMaintenanceGold = "object = 'Blueprint' AND condition1 = '$serviceType' AND condition2='$tosName'";
            $configMaintenanceGold = $DBWR->get_data($tbl_wrike_config, $conditionMaintenanceGold);
            $blueprintId = $configMaintenanceGold[0]['object_id'];

            date_default_timezone_set('Indonesia/Jakarta');
            $date = date('Y-m-d', time());

            //Check Table Wrike Project List Maintenance
            $conditionWrikeProjectList = "project_code = '$projectCode' AND project_type = 'MSI Project Maintenance'";
            $dataWrikeProjectList = $DBWR->get_data($tbl_wrike_project_list, $conditionWrikeProjectList);
            $projectCodeWPL = $dataWrikeProjectList[0]['project_code'];
            $projectTypeWPL = $dataWrikeProjectList[0]['project_type'];
            $totalRowWPL = $dataWrikeProjectList[2];

            //Check Table Initial Project
            $conditionInitialGold = "project_code='$projectCode' AND project_type='$serviceType' AND service_type='$tosName' AND order_number = '$orderNumber'";
            $dataInitialProject = $DBWR->get_data($tbl_initial_project, $conditionInitialGold);
            $totalRowInitialProject = $dataInitialProject[2];

            if ($totalRowWPL > 0) {
                echo "Project $serviceType dan ToS $tosName dengan KP. $projectCode sudah ada di WRIKE <br/>";
            } else {
                if ($totalRowInitialProject > 0) {
                    echo "Project Maintenance Gold dengan KP($projectCode) sudah dibuat <br/>";
                } else {
                    $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
                    $url = "https://www.wrike.com/api/v4/folder_blueprints/$blueprintId/launch_async";
                    //?parent=IEAEOPF5I4U765EL&title=".$projectNameExplode[0]."&titlePrefix=".$projectNameExplode[1]."&copyCustomFields=true&rescheduleMode=Start&rescheduleDate=$date";
                    $data = array('parent' => "IEAEOPF5I4U765EL", 'title' => "" . $projectNameExplode[0] . " " . $serviceType . "", 'titlePrefix' => "[" . $internalProjectName . "]", 'copyCustomFields' => "true", 'rescheduleMode' => "Start", 'rescheduleDate' => "$date");
                    $postdata = json_encode($data);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                    $result = curl_exec($ch);
                    curl_close($ch);
                    print_r($result);

                    echo "<br/>";

                    $result = json_decode($result, true);
                    $jobId = $result['data'][0]['id'];

                    $insertJobIdGold = sprintf(
                        "(`project_code`,`job_id`, `project_type`, `service_type`, `order_number`) VALUES ('$projectCode', '$jobId' , '$serviceType', '$tosName', '$orderNumber')",
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($jobId, "text"),
                        GetSQLValueString($serviceType, "text"),
                        GetSQLValueString($tosName, "text"),
                        GetSQLValueString($orderNumber, "text")
                    );

                    $resJobIdGold = $DBWR->insert_data($tbl_initial_project, $insertJobIdGold);

                    // echo "Project Name = " . $projectNameExplode[0] . " Prefix = " . $projectNameExplode[1] . "<br/>";
                    echo "$projectId - $projectCode - $projectName - $serviceType - $tosName - $blueprintId - $jobId <br/>";
                }
            }
        } else if ($serviceType == 2 && $tosId == 6) {
            $serviceType = "Maintenance";
            $tosName = "Silver";

            $conditionMaintenanceSilver = "object = 'Blueprint' AND condition1 = '$serviceType' AND condition2='$tosName'";
            $configMaintenanceSilver = $DBWR->get_data($tbl_wrike_config, $conditionMaintenanceSilver);
            $blueprintId = $configMaintenanceSilver[0]['object_id'];

            date_default_timezone_set('Indonesia/Jakarta');
            $date = date('Y-m-d', time());

            //Check Table Wrike Project List Maintenance
            $conditionWrikeProjectList = "project_code = '$projectCode' AND project_type = 'MSI Project Maintenance'";
            $dataWrikeProjectList = $DBWR->get_data($tbl_wrike_project_list, $conditionWrikeProjectList);
            $projectCodeWPL = $dataWrikeProjectList[0]['project_code'];
            $projectTypeWPL = $dataWrikeProjectList[0]['project_type'];
            $totalRowWPL = $dataWrikeProjectList[2];

            //Check Table Initial Project
            $conditionInitialSilver = "project_code='$projectCode' AND project_type='$serviceType' AND service_type='$tosName' AND order_number = '$orderNumber'";
            $dataInitialProject = $DBWR->get_data($tbl_initial_project, $conditionInitialSilver);
            $totalRowInitialProject = $dataInitialProject[2];

            if ($totalRowWPL > 0) {
                echo "Project $serviceType $tosName dengan KP. $projectCode sudah ada di WRIKE <br/>";
            } else {
                if ($totalRowInitialProject > 0) {
                    echo "Project Maintenance Silver dengan KP($projectCode) sudah dibuat <br/>";
                } else {
                    $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
                    $url = "https://www.wrike.com/api/v4/folder_blueprints/$blueprintId/launch_async";
                    //?parent=IEAEOPF5I4U765EL&title=".$projectNameExplode[0]."&titlePrefix=".$projectNameExplode[1]."&copyCustomFields=true&rescheduleMode=Start&rescheduleDate=$date";
                    $data = array('parent' => "IEAEOPF5I4U765EL", 'title' => "" . $projectNameExplode[0] . " " . $serviceType . "", 'titlePrefix' => "[" . $internalProjectName . "]", 'copyCustomFields' => "true", 'rescheduleMode' => "Start", 'rescheduleDate' => "$date");
                    $postdata = json_encode($data);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                    $result = curl_exec($ch);
                    curl_close($ch);
                    print_r($result);

                    echo "<br/>";

                    $result = json_decode($result, true);
                    $jobId = $result['data'][0]['id'];

                    $insertJobIdSilver = sprintf(
                        "(`project_code`,`job_id`, `project_type`, `service_type`, `order_number`) VALUES ('$projectCode', '$jobId' , '$serviceType', '$tosName', '$orderNumber')",
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($jobId, "text"),
                        GetSQLValueString($serviceType, "text"),
                        GetSQLValueString($tosName, "text"),
                        GetSQLValueString($orderNumber, "text")
                    );

                    $resJobIdSilver = $DBWR->insert_data($tbl_initial_project, $insertJobIdSilver);

                    // echo "Project Name = " . $projectNameExplode[0] . " Prefix = " . $projectNameExplode[1] . "<br/>";
                    echo "$projectId - $projectCode - $projectName - $serviceType - $tosName - $blueprintId - $jobId <br/>";
                }
            }
        } else if ($serviceType == 2 && $tosId == 7) {
            $serviceType = "Maintenance";
            $tosName = "Bronze";
            $conditionMaintenanceBronze = "object = 'Blueprint' AND condition1 = '$serviceType' AND condition2='$tosName'";
            $configMaintenanceBronze = $DBWR->get_data($tbl_wrike_config, $conditionMaintenanceBronze);
            $blueprintId = $configMaintenanceBronze[0]['object_id'];

            date_default_timezone_set('Indonesia/Jakarta');
            $date = date('Y-m-d', time());

            //Check Table Wrike Project List Maintenance
            $conditionWrikeProjectList = "project_code = '$projectCode' AND project_type = 'MSI Project Maintenance'";
            $dataWrikeProjectList = $DBWR->get_data($tbl_wrike_project_list, $conditionWrikeProjectList);
            $projectCodeWPL = $dataWrikeProjectList[0]['project_code'];
            $projectTypeWPL = $dataWrikeProjectList[0]['project_type'];
            $totalRowWPL = $dataWrikeProjectList[2];

            //Check Table Initial Project
            $conditionInitialBronze = "project_code='$projectCode' AND project_type='$serviceType' AND service_type='$tosName' AND order_number = '$orderNumber'";
            $dataInitialProject = $DBWR->get_data($tbl_initial_project, $conditionInitialBronze);
            $totalRowInitialProject = $dataInitialProject[2];

            if ($totalRowWPL > 0) {
            } else {
                if ($totalRowInitialProject > 0) {
                    echo "Project Maintenance Bronze dengan KP($projectCode) sudah dibuat <br/>";
                } else {
                    $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
                    $url = "https://www.wrike.com/api/v4/folder_blueprints/$blueprintId/launch_async";
                    //?parent=IEAEOPF5I4U765EL&title=".$projectNameExplode[0]."&titlePrefix=".$projectNameExplode[1]."&copyCustomFields=true&rescheduleMode=Start&rescheduleDate=$date";
                    $data = array('parent' => "IEAEOPF5I4U765EL", 'title' => "" . $projectNameExplode[0] . " " . $serviceType . "", 'titlePrefix' => "[" . $internalProjectName . "]", 'copyCustomFields' => "true", 'rescheduleMode' => "Start", 'rescheduleDate' => "$date");
                    $postdata = json_encode($data);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                    $result = curl_exec($ch);
                    curl_close($ch);
                    print_r($result);

                    echo "<br/>";

                    $result = json_decode($result, true);
                    $jobId = $result['data'][0]['id'];

                    $insertJobIdBronze = sprintf(
                        "(`project_code`,`job_id`, `project_type`, `service_type`, `order_number`) VALUES ('$projectCode', '$jobId' , '$serviceType', '$tosName', '$orderNumber')",
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($jobId, "text"),
                        GetSQLValueString($serviceType, "text"),
                        GetSQLValueString($tosName, "text"),
                        GetSQLValueString($orderNumber, "text")
                    );

                    $resJobIdBronze = $DBWR->insert_data($tbl_initial_project, $insertJobIdBronze);

                    echo "$projectId - $projectCode - $projectName - $serviceType - $tosName - $blueprintId - $jobId <br/>";
                }
            }
        }
    } while ($rowSB = $resSB->fetch_assoc());
}

function async_job()
{
    global $DBWR, $DBSB, $DBGD;

    $tbl_initial_project = 'initial_project';
    $dataInitialProject = $DBWR->get_data($tbl_initial_project);
    $rowInitialProject = $dataInitialProject[0];
    $resInitialProject = $dataInitialProject[1];
    $totalRowInitialProject = $dataInitialProject[2];

    do {
        $projectCode = $rowInitialProject['project_code'];
        $jobId = $rowInitialProject['job_id'];
        $status = $rowInitialProject['status'];

        //echo "$projectCode - $jobId <br/>";
        if ($status == 1) {
            $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, "https://www.wrike.com/api/v4/async_job/$jobId");
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($curl);
            curl_close($curl);

            $result = json_decode($result, true);

            //GET Customer Name
            $data = $result['data'];

            for ($i = 0; $i < count($data); $i++) {
                $folderId = $data[0]['result']['folderId'];

                $conditionUpdateFolderId = "job_id = '$jobId'";

                if ($folderId != '') {
                    $updateFolderIdData = sprintf(
                        "`project_id`= '$folderId', `status` = 2",
                        GetSQLValueString($folderId, "text")
                    );

                    $resFolderIdData = $DBWR->update_data($tbl_initial_project, $updateFolderIdData, $conditionUpdateFolderId);
                    echo "$projectCode - $jobId - $folderId <br/>";
                }
            }
        } else {
            echo "$projectCode sudah di async <br/>";
        }
    } while ($rowInitialProject = $resInitialProject->fetch_assoc());
}

function modify_project()
{
    global $DBWR, $DBGD, $DBSB;

    $tbl_wrike_config = 'wrike_config';
    $tbl_initial_project = 'initial_project';
    $dataInitialProject = $DBWR->get_data($tbl_initial_project);
    $rowInitialProject = $dataInitialProject[0];
    $resInitialProject = $dataInitialProject[1];
    $totalRowInitialProject = $dataInitialProject[2];

    do {
        $projectType = '';
        $projectId = $rowInitialProject['project_id'];
        $projectCode = $rowInitialProject['project_code'];
        $projectType = $rowInitialProject['project_type'];
        // $projectSbId = $rowInitialProject['project_sb_id'];
        $orderNumber = $rowInitialProject['order_number'];
        $status = $rowInitialProject['status'];

        if ($status == 2) {
            $queryOwner = "SELECT * FROM sa_wrike_config WHERE object = 'Owner' AND condition1 = '$projectType'";
            $dataQuery = $DBWR->get_sql($queryOwner);
            $rowOwner = $dataQuery[0];
            $resOwner = $dataQuery[1];

            $fullOwnerId = '';

            do {
                $ownerId = $rowOwner['object_id'];
                $fullOwnerId = '"' . $ownerId . '", ' . $fullOwnerId;
                $fullOwnerId = rtrim($fullOwnerId, ', ');
            } while ($rowOwner = $resOwner->fetch_assoc());

            // echo "$fullOwnerId <br/>";

            if ($projectType == 'Implementation') {
                $projectType = 1;
            } else if ($projectType == 'Maintenance') {
                $projectType = 2;
            }

            $db_sb = "SERVICE_BUDGET";
            $DBSB = get_conn($db_sb);
            $queryEnterpriseProjectType = "SELECT a.project_id, a.project_code, a.project_name, a.project_name_internal, a.customer_name, a.amount_idr, a.amount_usd, a.so_number, a.order_number, a.bundling, b.tos_id, b.tos_category_id, b.service_type, c.tos_name FROM sa_trx_project_list AS a 
            LEFT JOIN sa_trx_project_implementations AS b ON a.project_id = b.project_id
            LEFT JOIN sa_mst_type_of_service AS c ON b.tos_id = c.tos_id
            WHERE a.status = 'acknowledge' AND a.bundling != '' AND a.sbtype = 1 AND c.tos_id IN (1,2,3,4,5,6,7) AND a.order_number = '$orderNumber'";

            $dataProjectCategory = $DBSB->get_sql($queryEnterpriseProjectType);
            $rowProjectCategory = $dataProjectCategory[0];
            $resProjectCategory = $dataProjectCategory[1];
            $totalRowProjectCategory = $dataProjectCategory[2];

            do {
                $projectCategory = '';
                $amountIDR = '';
                $amountUSD = '';
                $currency = '';
                $soNumber = '';
                $projectName = $rowProjectCategory['project_name'];
                $projectNameExplode = explode('#', $projectName);
                $soNumber = $rowProjectCategory['so_number'];
                $customerName = $rowProjectCategory['customer_name'];
                $projectCategory = $rowProjectCategory['tos_category_id'];
                $serviceType1 = $rowProjectCategory['service_type'];
                $serviceType = $rowProjectCategory['tos_id'];
                $tosName = $rowProjectCategory['tos_name'];
                $internalProjectName = $rowProjectCategory['project_name_internal'];
                $amountIDR = $rowProjectCategory['amount_idr'];
                $amountIDR = round($amountIDR, 0);
                $amountUSD = $rowProjectCategory['amount_usd'];
                $amountUSD = round($amountUSD, 0);

                if ($amountIDR != '') {
                    $currency = 'IDR';
                    $priceService = $amountIDR;
                    $conditionCurrencyOption = "object = 'CustomField' AND title = 'Currency' and condition2 = '" . $currency . "'";
                    $customfieldCurrency = $DBWR->get_data($tbl_wrike_config, $conditionCurrencyOption);
                    $currencyOptionId = $customfieldCurrency[0]['object_id'];
                } else if ($amountUSD != '') {
                    $currency = 'USD';
                    $priceService = $amountUSD;
                    $conditionCurrencyOption = "object = 'CustomField' AND title = 'Currency' and condition2 = '" . $currency . "'";
                    $customfieldCurrency = $DBWR->get_data($tbl_wrike_config, $conditionCurrencyOption);
                    $currencyOptionId = $customfieldCurrency[0]['object_id'];
                }

                if ($projectType == 1 && $serviceType1 == 1) {
                    if ($projectCategory == 1) {
                        $projectCategoryDescription = 'Standard';
                    } else if ($projectCategory == 2) {
                        $projectCategoryDescription = 'Medium';
                    } else if ($projectCategory == 3) {
                        $projectCategoryDescription = 'High';
                    }

                    //Project Category ID
                    $conditionProjectCategory = "object = 'CustomField' AND title = 'Project Category' AND condition2 = '$projectCategoryDescription'";
                    $customfieldPC = $DBWR->get_data($tbl_wrike_config, $conditionProjectCategory);
                    $pcId = $customfieldPC[0]['object_id'];

                    //Project Code ID
                    $conditionProjectCode = "object = 'CustomField' AND title = 'Project Code'";
                    $customfieldPCode = $DBWR->get_data($tbl_wrike_config, $conditionProjectCode);
                    $projectCodeId = $customfieldPCode[0]['object_id'];

                    //Sales Order Number Id
                    $conditionSalesOrder = "object = 'CustomField' AND title = 'Sales Order'";
                    $customfieldSOrder = $DBWR->get_data($tbl_wrike_config, $conditionSalesOrder);
                    $noSOId = $customfieldSOrder[0]['object_id'];

                    //Project Value ID
                    $conditionProjectValue = "object = 'CustomField' AND title = 'Project Value'";
                    $customfieldPValue = $DBWR->get_data($tbl_wrike_config, $conditionProjectValue);
                    $projectValueId = $customfieldPValue[0]['object_id'];

                    //Project Service ID
                    $conditionProjectService = "object = 'CustomField' AND title = 'Project Service'";
                    $customfieldPService = $DBWR->get_data($tbl_wrike_config, $conditionProjectService);
                    $projectServiceId = $customfieldPService[0]['object_id'];

                    //Mandays
                    $mandaysQuery = "SELECT sa_trx_project_list.project_id, project_code, sa_trx_project_mandays.mantotal, sa_trx_project_mandays.mandays, sa_trx_project_mandays.service_type
                    FROM sa_trx_project_list
                    JOIN sa_trx_project_mandays ON sa_trx_project_list.project_id = sa_trx_project_mandays.project_id
                    WHERE sa_trx_project_list.project_code = '$projectCode' AND service_type = $projectType";

                    $dataMandays = $DBSB->get_sql($mandaysQuery);
                    $rowDataMandays = $dataMandays[0];
                    $resDataMandays = $dataMandays[1];
                    $totalRowDataMandays = $dataMandays[2];
                    unset($arrTotalMandays);

                    do {
                        $mantotal = $rowDataMandays['mantotal'];
                        $mandays = $rowDataMandays['mandays'];

                        if ($mantotal != NULL && $mandays != NULL) {
                            $totalMandays = $mantotal * $mandays;
                            $arrTotalMandays[] = $totalMandays;
                        } else {
                            echo "Terdapat Nilai NULL";
                        }
                    } while ($rowDataMandays = $resDataMandays->fetch_assoc());

                    // Variabel Mandays
                    $mandaysWrike = array_sum($arrTotalMandays);

                    //Mandays ID
                    $conditionTotalMandays = "object = 'CustomField' AND title = 'Total Mandays'";
                    $customfieldTMandays = $DBWR->get_data($tbl_wrike_config, $conditionTotalMandays);
                    $totalMandaysId = $customfieldTMandays[0]['object_id'];

                    //Customer ID
                    $conditionCustomerName = "object = 'CustomField' AND title = 'Customer' AND condition2 = '$customerName'";
                    $customfieldCName = $DBWR->get_data($tbl_wrike_config, $conditionCustomerName);
                    $customerNameId = $customfieldCName[0]['object_id'];
                    $customerNameWrike = $customfieldCName[0]['condition2'];

                    //Internal Project Name ID
                    $conditionInternalProjectName = "object = 'CustomField' AND title = 'Internal Project Name'";
                    $customfieldInternalProjectName = $DBWR->get_data($tbl_wrike_config, $conditionInternalProjectName);
                    $internalProjectNameId = $customfieldInternalProjectName[0]['object_id'];

                    $url = "https://www.wrike.com/api/v4/folders/$projectId"; //?project={'ownersAdd':[$fullOwnerId]}&customFields=[{'id':'$pcId', 'value':'$projectCategoryDescription'}]";
                    $data = array("project" => "{'ownersAdd':[$fullOwnerId]}", "customFields" => "[{'id':'$currencyOptionId', 'value':'$currency'}, {'id':'$pcId', 'value':'$projectCategoryDescription'},  {'id':'$projectCodeId','value':'$projectCode'}, {'id':'$noSOId','value':'$soNumber'}, {'id':'$projectValueId','value':'$priceService'}, {'id':'$projectServiceId','value':'$tosName'}, {'id':'$totalMandaysId','value':'$mandaysWrike'}, {'id':'$customerNameId','value':'$customerNameWrike'}, {'id':'$internalProjectNameId','value':'$internalProjectName'}]");

                    //Internal Project Name
                    //, {'id':'$internalProjectNameId','value':'" . $projectNameExplode[1] . "'}
                    $put_data = json_encode($data);
                    $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $put_data);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));

                    $response = curl_exec($ch);
                    curl_close($ch);
                    print_r($response);

                    $conditionStatus3 = "project_id = '$projectId'";
                    $updateStatus3 = sprintf("`status`= 3");
                    $resStatus3 = $DBWR->update_data($tbl_initial_project, $updateStatus3, $conditionStatus3);

                    echo "<br/>";

                    echo "$projectCode - $soNumber - $priceService - [$fullOwnerId] - $projectType - $tosName - $pcId ($projectCategoryDescription) <br/>";
                } 
                else if ($projectType == 2 && $serviceType1 == 2) {
                    if ($serviceType == 5) {
                        $serviceTypeDescription = 'Gold';
                    } else if ($serviceType == 6) {
                        $serviceTypeDescription = 'Silver';
                    } else if ($serviceType == 7) {
                        $serviceTypeDescription = 'Bronze';
                    }

                    //Service Type ID
                    $conditionServiceTypeMaintenance = "object = 'CustomField' AND title = 'Service Type' AND condition2 = '$serviceTypeDescription'";
                    $customfieldServiceTypeMaintenance = $DBWR->get_data($tbl_wrike_config, $conditionServiceTypeMaintenance);
                    $serviceTypeId = $customfieldServiceTypeMaintenance[0]['object_id'];

                    //Project Code ID
                    $conditionProjectCode = "object = 'CustomField' AND title = 'Project Code'";
                    $customfieldPCode = $DBWR->get_data($tbl_wrike_config, $conditionProjectCode);
                    $projectCodeId = $customfieldPCode[0]['object_id'];

                    //Sales Order Number Id
                    $conditionSalesOrder = "object = 'CustomField' AND title = 'Sales Order'";
                    $customfieldSOrder = $DBWR->get_data($tbl_wrike_config, $conditionSalesOrder);
                    $noSOId = $customfieldSOrder[0]['object_id'];

                    //Project Value ID
                    $conditionProjectValue = "object = 'CustomField' AND title = 'Project Value'";
                    $customfieldPValue = $DBWR->get_data($tbl_wrike_config, $conditionProjectValue);
                    $projectValueId = $customfieldPValue[0]['object_id'];

                    //Project Service ID
                    // $conditionProjectService = "object = 'CustomField' AND title = 'Project Service'";
                    // $customfieldPService = $DBWR->get_data($tbl_wrike_config, $conditionProjectService);
                    // $projectServiceId = $customfieldPService[0]['object_id'];

                    //Mandays
                    $mandaysQuery = "SELECT sa_trx_project_list.project_id, project_code, sa_trx_project_mandays.mantotal, sa_trx_project_mandays.mandays, sa_trx_project_mandays.service_type
                    FROM sa_trx_project_list
                    JOIN sa_trx_project_mandays ON sa_trx_project_list.project_id = sa_trx_project_mandays.project_id
                    WHERE sa_trx_project_list.project_code = '$projectCode' AND service_type = $projectType";

                    $dataMandays = $DBSB->get_sql($mandaysQuery);
                    $rowDataMandays = $dataMandays[0];
                    $resDataMandays = $dataMandays[1];
                    $totalRowDataMandays = $dataMandays[2];
                    unset($arrTotalMandays);

                    do {
                        $mantotal = $rowDataMandays['mantotal'];
                        $mandays = $rowDataMandays['mandays'];

                        if ($mantotal != NULL && $mandays != NULL) {
                            $totalMandays = $mantotal * $mandays;
                            $arrTotalMandays[] = $totalMandays;
                        } else {
                            echo "Terdapat Nilai NULL <br/>";
                        }
                    } while ($rowDataMandays = $resDataMandays->fetch_assoc());

                    // Variabel Mandays
                    if ($arrTotalMandays != '') {
                        $mandaysWrike = array_sum($arrTotalMandays);
                    } else {
                        $mandaysWrike = '';
                    }

                    //Mandays ID
                    $conditionTotalMandays = "object = 'CustomField' AND title = 'Total Mandays'";
                    $customfieldTMandays = $DBWR->get_data($tbl_wrike_config, $conditionTotalMandays);
                    $totalMandaysId = $customfieldTMandays[0]['object_id'];

                    //Customer ID
                    $conditionCustomerName = "object = 'CustomField' AND title = 'Customer' AND condition2 = '$customerName'";
                    $customfieldCName = $DBWR->get_data($tbl_wrike_config, $conditionCustomerName);
                    $customerNameId = $customfieldCName[0]['object_id'];
                    $customerNameWrike = $customfieldCName[0]['condition2'];

                    //Internal Project Name ID
                    $conditionInternalProjectName = "object = 'CustomField' AND title = 'Internal Project Name'";
                    $customfieldInternalProjectName = $DBWR->get_data($tbl_wrike_config, $conditionInternalProjectName);
                    $internalProjectNameId = $customfieldInternalProjectName[0]['object_id'];

                    $url = "https://www.wrike.com/api/v4/folders/$projectId"; //?project={'ownersAdd':[$fullOwnerId]}&customFields=[{'id':'$pcId', 'value':'$projectCategoryDescription'}]";
                    $data = array("project" => "{'ownersAdd':[$fullOwnerId]}", "customFields" => "[{'id':'$currencyOptionId', 'value':'$currency'}, {'id':'$serviceTypeId', 'value':'$serviceTypeDescription'}, {'id':'$projectCodeId','value':'$projectCode'}, {'id':'$noSOId','value':'$soNumber'}, {'id':'$projectValueId','value':'$priceService'}, {'id':'$totalMandaysId','value':'$mandaysWrike'}, {'id':'$customerNameId','value':'$customerNameWrike'}, {'id':'$internalProjectNameId','value':'$internalProjectName'}]");
                    $put_data = json_encode($data);
                    $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $put_data);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));

                    $response = curl_exec($ch);
                    curl_close($ch);
                    print_r($response);

                    $conditionStatus3 = "project_id = '$projectId'";
                    $updateStatus3 = sprintf("`status`= 3");
                    $resStatus3 = $DBWR->update_data($tbl_initial_project, $updateStatus3, $conditionStatus3);

                    echo "<br/>";

                    echo "$projectCode - $soNumber - $priceService - [$fullOwnerId] - $projectType - $serviceTypeDescription - $serviceTypeId ($serviceTypeDescription) <br/>";
                }
            } while ($rowProjectCategory = $resProjectCategory->fetch_assoc());
        } else if ($status == 1) {
            echo "$projectCode belum sampai step Modify Customfield <br/>";
        } else {
            echo "$projectCode sudah memiliki CustomField. <br/>";
        }
    } while ($rowInitialProject = $resInitialProject->fetch_assoc());
}

function create_approval()
{
    global $DBWR;

    $tbl_initial_project = "initial_project";
    $tbl_wrike_config = "wrike_config";
    $dataApproval = $DBWR->get_data($tbl_initial_project);
    $rowDataApproval = $dataApproval[0];
    $resDataApproval = $dataApproval[1];
    $totalRowDataApproval = $dataApproval[2];

    do {
        $projectId = $rowDataApproval['project_id'];
        $projectCode = $rowDataApproval['project_code'];
        $projectType = $rowDataApproval['project_type'];
        $status = $rowDataApproval['status'];
        //echo "$projectId <br/>";

        $conditionOwner = "object = 'Owner' AND condition1 = '$projectType'";
        $dataOwner = $DBWR->get_data($tbl_wrike_config, $conditionOwner);
        $rowOwner = $dataOwner[0];
        $resOwner = $dataOwner[1];

        $fullOwnerId = '';

        do {
            $ownerId = $rowOwner['object_id'];
            $fullOwnerId = '"' . $ownerId . '", ' . $fullOwnerId;
            $fullOwnerId = rtrim($fullOwnerId, ', ');
        } while ($rowOwner = $resOwner->fetch_assoc());

        $conditionApprovalDay = "object = 'approval' and title ='Approval Maximum Day'";
        $dataApprovalDay = $DBWR->get_data($tbl_wrike_config);
        $day = $dataApprovalDay[0]['condition1'];

        $dueDate = date('Y-m-d', strtotime("+$day  day"));

        if ($status == 3) {
            $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";

            $url = "https://www.wrike.com/api/v4/folders/$projectId/approvals"; //?approvers=$ownerId&description=$descApproval&dueDate=$dueApproval";
            //?parent=IEAEOPF5I4U765EL&title=".$projectNameExplode[0]."&titlePrefix=".$projectNameExplode[1]."&copyCustomFields=true&rescheduleMode=Start&rescheduleDate=$date";
            $data = array('approvers' => "[$fullOwnerId]", 'description' => "New Project Request $projectType", 'dueDate' => "$dueDate");
            $postdata = json_encode($data);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
            $result = curl_exec($ch);
            curl_close($ch);
            print_r($result);

            $conditionStatus4 = "project_id = '$projectId'";
            $updateStatus4 = sprintf("`status`= 4");
            $resStatus4 = $DBWR->update_data($tbl_initial_project, $updateStatus4, $conditionStatus4);

            echo "<br/>";

            echo "$projectId - [$fullOwnerId] - $projectType - $dueDate - $status <br/>";
        } else if ($status == 1 || $status == 2) {
            echo "$projectCode belum sampai tahapan approval <br/>";
        } else {
            echo "$projectCode sudah diberikan approval ke $fullOwnerId <br/>";
        }
    } while ($rowDataApproval = $resDataApproval->fetch_assoc());
}

function get_approval()
{
    global $DBWR;

    $tbl_initial_project = 'initial_project';
    $dataGetApproval = $DBWR->get_data($tbl_initial_project,);
    $rowGetApproval = $dataGetApproval[0];
    $resGetApproval = $dataGetApproval[1];
    $totalRowGetApproval = $dataGetApproval[2];

    do {
        $projectId = $rowGetApproval['project_id'];
        $projectCode = $rowGetApproval['project_code'];
        $approvalStatus = $rowGetApproval['approval_status'];
        $projectType = $rowGetApproval['project_type'];
        $status = $rowGetApproval['status'];
        // echo "$projectId <br/>";

        if ($approvalStatus != 'Approved' && $status == 4) {
            $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, "https://www.wrike.com/api/v4/folders/$projectId/approvals");
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($curl);
            curl_close($curl);

            $result2 = json_decode($result, true);

            $resultApproval = $result2['data'];

            for ($i = 0; $i < count($resultApproval); $i++) {
                $statusApproval = $resultApproval[$i]['status'];
                // echo "$projectId - $statusApproval - $projectType <br/>";

                if ($statusApproval == 'Approved') {
                    $conditionStatus5 = "project_id = '$projectId'";
                    $updateStatus5 = sprintf("`approval_status` = 'Approved', `status`= 5");
                    $resStatus5 = $DBWR->update_data($tbl_initial_project, $updateStatus5, $conditionStatus5);
                } else if ($statusApproval == 'Rejected') {
                    $conditionStatus5 = "project_id = '$projectId'";
                    $updateStatus5 = sprintf("`approval_status` = '$statusApproval', `status` = 5");
                    $resStatus5 = $DBWR->update_data($tbl_initial_project, $updateStatus5, $conditionStatus5);
                } else {
                    $conditionStatus4 = "project_id = '$projectId'";
                    $updateStatus4 = sprintf("`approval_status` = '$statusApproval'");
                    $resStatus4 = $DBWR->update_data($tbl_initial_project, $updateStatus4, $conditionStatus4);
                    echo "Project dengan No. KP : $projectCode ($projectType) masih di pending <br/>";
                }
            }
        } else if ($approvalStatus == 'Approved') {
            echo "Project dengan No. KP : $projectCode telah di Approve <br/>";
        } else if ($approvalStatus == 'Rejected') {
            echo "Project dengan No. KP : $projectCode memiliki status $approvalStatus <br/>";
        }
    } while ($rowGetApproval = $resGetApproval->fetch_assoc());
}


function modify_project_status()
{
    global $DBWR;

    $tbl_wrike_config = 'wrike_config';
    $tbl_initial_project = 'initial_project';
    $dataInitialProject = $DBWR->get_data($tbl_initial_project);
    $rowInitialProject = $dataInitialProject[0];
    $resInitialProject = $dataInitialProject[1];
    $totalRowInitialProject = $dataInitialProject[2];

    do {
        $projectId = $rowInitialProject['project_id'];
        $projectCode = $rowInitialProject['project_code'];
        $approvalStatus = $rowInitialProject['approval_status'];
        $status = $rowInitialProject['status'];

        $workflowData = $DBWR->get_data($tbl_wrike_config);
        $customstatusId = $workflowData[0]['object_id'];

        if ($status == 5 && $approvalStatus == 'Approved') {
            //Custom Status Id ke Active Project
            $url = "https://www.wrike.com/api/v4/folders/$projectId"; //?project={'ownersAdd':[$fullOwnerId]}&customFields=[{'id':'$pcId', 'value':'$projectCategoryDescription'}]";
            $data = array("project" => "{'customStatusId':'IEAEOPF5JMCRGWEK'}");
            $put_data = json_encode($data);
            $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $put_data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));

            $response = curl_exec($ch);
            curl_close($ch);
            print_r($response);

            echo "$projectId - $approvalStatus <br/>";

            $conditionStatus6 = "project_id = '$projectId'";
            $updateStatus6 = sprintf("`status`= 6");
            $resStatus6 = $DBWR->update_data($tbl_initial_project, $updateStatus6, $conditionStatus6);
        } else if ($status == 6) {
            echo "$projectCode sudah selesai <br/>";
        } else {
            echo "Data belum/tidak di approve<br/>";
        }
    } while ($rowInitialProject = $resInitialProject->fetch_assoc());
}

// function testing(){
//     global $DBWR;

//     $test_sql = "SELECT pn, harga FROM sa_testing
//     WHERE pn = 'PN-A'
//     UNION
//     SELECT pn, harga FROM sa_testing2
//     WHERE pn = 'PN-A'";

//     $dataSql = $DBWR->get_sql($test_sql);
//     $rowDataSQL = $dataSql[0];
//     $resDataSQL = $dataSql[1];

//     do{
//         $pn = $rowDataSQL['pn'];
//         $harga = $rowDataSQL['harga'];

//         echo "$pn - $harga <br/>";
        
//     }while($rowDataSQL = $resDataSQL->fetch_assoc());
// }