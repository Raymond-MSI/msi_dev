<?php 
function get_data_sb() {
    global $DBGD,
    $DBWR;
    $db_sb="SERVICE_BUDGET";
    // $DBSB = get_conn($db_sb);
    $DBSB=get_conn($db_sb);

    $querySB="SELECT a.project_id, a.project_code, a.project_name, a.project_name_internal, a.order_number, a.amount_idr, a.amount_usd, a.so_number, a.bundling, b.tos_id, b.tos_category_id, b.service_type, c.tos_name, a.status, a.create_date FROM sa_trx_project_list AS a 
    LEFT JOIN sa_trx_project_implementations AS b ON a.project_id=b.project_id LEFT JOIN sa_mst_type_of_service AS c ON b.tos_id=c.tos_id WHERE a.status='acknowledge'AND a.bundling !=''AND a.sbtype=1 AND c.tos_id IN (1, 2, 3, 4, 5, 6, 7) AND a.project_name_internal !=''
    AND a.order_number !=''
    AND a.create_date>='2022-08-01'
    ORDER BY project_id";

    $tbl_wrike_config='wrike_config';
    $tbl_trx_project_list='trx_project_list';
    $conditionSB="status = 'acknowledge'";
    $dataSB=$DBSB->get_sql($querySB);
    $rowSB=$dataSB[0];
    $resSB=$dataSB[1];
    $totalRowSB=$dataSB[2];

    $tbl_trx_project_implementations='trx_project_implementations';
    $dataSBExpand=$DBSB->get_data($tbl_trx_project_list);
    $rowSBExpand=$dataSBExpand[0];
    $resSBExpand=$dataSBExpand[1];
    $totalRowSBExpand=$dataSBExpand[2];

    $tbl_initial_project="initial_project";
    $tbl_wrike_project_list="wrike_project_list";

    // do{
    //     $arraySBExpand[] = $rowSBExpand;
    // }while($rowSBExpand = $resSBExpand->fetch_assoc());

    do {
        $projectId=$rowSB['project_id'];
        $projectCode=$rowSB['project_code'];
        $orderNumber=$rowSB['order_number'];
        $projectName=$rowSB['project_name'];
        $internalProjectName=$rowSB['project_name_internal'];
        $projectNameExplode=explode('#', $projectName);
        $tosId=$rowSB['tos_id'];
        $projectCategory=$rowSB['tos_category_id'];
        $serviceType=$rowSB['service_type'];
        $tosName=$rowSB['tos_name'];

        if ($serviceType==1 && $projectCategory==1) {
            $serviceType="Implementation";
            $projectCategoryDescription="High";
            $conditionImplementation="object = 'Blueprint' AND condition1 = '$serviceType' AND condition2 = '$projectCategoryDescription'";
            $configImplementation=$DBWR->get_data($tbl_wrike_config, $conditionImplementation);
            $blueprintId=$configImplementation[0]['object_id'];

            // date_default_timezone_set('Indonesia/Jakarta');
            $date=date('Y-m-d', time());

            //Check Table Initial Project
            $conditionInitialImplementation="project_code='$projectCode' AND project_type='$serviceType' AND service_type = '$projectCategoryDescription' AND order_number = '$orderNumber'";
            $dataInitialProject=$DBWR->get_data($tbl_initial_project, $conditionInitialImplementation);
            $totalRowInitialProject=$dataInitialProject[2];

            //Check Table Wrike Project List Implementation
            $conditionWrikeProjectList="project_code = '$projectCode' AND project_type = 'MSI Project Implementation'";
            $dataWrikeProjectList=$DBWR->get_data($tbl_wrike_project_list, $conditionWrikeProjectList);
            $totalRowWPL=$dataWrikeProjectList[2];

            if ($totalRowWPL > 0) {
                echo "Project $serviceType dengan KP. $projectCode sudah ada di WRIKE <br/>";
            }

            else {
                if ($totalRowInitialProject > 0) {
                    echo "Project Implementation dengan KP($projectCode) sudah dibuat <br/>";
                }

                else {
                    $authorization="Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
                    $url="https://www.wrike.com/api/v4/folder_blueprints/$blueprintId/launch_async";
                    //?parent=IEAEOPF5I4U765EL&title=".$projectNameExplode[0]."&titlePrefix=".$projectNameExplode[1]."&copyCustomFields=true&rescheduleMode=Start&rescheduleDate=$date";
                    $data=array('parent'=> "IEAEOPF5I4U765EL", 'title'=> "". $projectNameExplode[0] . "", 'titlePrefix'=> "[". $internalProjectName . "] ", 'copyCustomFields'=> "true", 'rescheduleMode'=> "Start", 'rescheduleDate'=> "$date");
                    $postdata=json_encode($data);
                    $ch=curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                    $result=curl_exec($ch);
                    curl_close($ch);
                    print_r($result);

                    echo "<br/>";

                    $result=json_decode($result, true);
                    $jobId=$result['data'][0]['id'];

                    $insertJobId=sprintf("(`project_code`,`job_id`, `project_type`, `service_type`, `order_number`) VALUES ('$projectCode', '$jobId' , '$serviceType', '$projectCategoryDescription', '$orderNumber')",
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($jobId, "text"),
                        GetSQLValueString($serviceType, "text"),
                        GetSQLValueString($orderNumber, "text"));

                    $resJobId=$DBWR->insert_data($tbl_initial_project, $insertJobId);

                    // echo "Project Name = " . $projectNameExplode[0] . " Prefix = " . $projectNameExplode[1] . "<br/>";
                    echo "$projectId - $projectCode - $projectName - $serviceType - $projectCategoryDescription - $blueprintId - $jobId <br/>";


                    //INSERT LOG BLUEPRINT IMPLEMENTATION
                    $tbl_sa_log_activity='log_activity';
                    $insertLogBlueprintImplementation=sprintf("(`activity`) VALUES ('Created $serviceType - $projectCategoryDescription Blueprint in $projectCode with job id $jobId')",
                        GetSQLValueString($serviceType, "text"),
                        GetSQLValueString($projectCategoryDescription, "text"),
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($jobId, "text"));

                    $resLogImplementation=$DBWR->insert_data($tbl_sa_log_activity, $insertLogBlueprintImplementation);
                }
            }
        }

        else if ($serviceType==1 && $projectCategory==2) {
            $serviceType="Implementation";
            $projectCategoryDescription="Medium";
            $conditionImplementation="object = 'Blueprint' AND condition1 = '$serviceType' AND condition2 = '$projectCategoryDescription'";
            $configImplementation=$DBWR->get_data($tbl_wrike_config, $conditionImplementation);
            $blueprintId=$configImplementation[0]['object_id'];

            // date_default_timezone_set('Indonesia/Jakarta');
            $date=date('Y-m-d', time());

            //Check Table Initial Project
            $conditionInitialImplementation="project_code='$projectCode' AND project_type='$serviceType' AND service_type = '$projectCategoryDescription' AND order_number = '$orderNumber'";
            $dataInitialProject=$DBWR->get_data($tbl_initial_project, $conditionInitialImplementation);
            $totalRowInitialProject=$dataInitialProject[2];

            //Check Table Wrike Project List Implementation
            $conditionWrikeProjectList="project_code = '$projectCode' AND project_type = 'MSI Project Implementation'";
            $dataWrikeProjectList=$DBWR->get_data($tbl_wrike_project_list, $conditionWrikeProjectList);
            $totalRowWPL=$dataWrikeProjectList[2];

            if ($totalRowWPL > 0) {
                echo "Project $serviceType - $projectCategoryDescription dengan KP. $projectCode sudah ada di WRIKE <br/>";
            }

            else {
                if ($totalRowInitialProject > 0) {
                    echo "Project Implementation dengan KP($projectCode) sudah dibuat <br/>";
                }

                else {
                    $authorization="Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
                    $url="https://www.wrike.com/api/v4/folder_blueprints/$blueprintId/launch_async";
                    //?parent=IEAEOPF5I4U765EL&title=".$projectNameExplode[0]."&titlePrefix=".$projectNameExplode[1]."&copyCustomFields=true&rescheduleMode=Start&rescheduleDate=$date";
                    $data=array('parent'=> "IEAEOPF5I4U765EL", 'title'=> "". $projectNameExplode[0] . "", 'titlePrefix'=> "[". $internalProjectName . "] ", 'copyCustomFields'=> "true", 'rescheduleMode'=> "Start", 'rescheduleDate'=> "$date");
                    $postdata=json_encode($data);
                    $ch=curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                    $result=curl_exec($ch);
                    curl_close($ch);
                    print_r($result);

                    echo "<br/>";

                    $result=json_decode($result, true);
                    $jobId=$result['data'][0]['id'];

                    $insertJobId=sprintf("(`project_code`,`job_id`, `project_type`, `service_type`, `order_number`) VALUES ('$projectCode', '$jobId' , '$serviceType', '$projectCategoryDescription', '$orderNumber')",
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($jobId, "text"),
                        GetSQLValueString($serviceType, "text"),
                        GetSQLValueString($orderNumber, "text"));

                    $resJobId=$DBWR->insert_data($tbl_initial_project, $insertJobId);

                    // echo "Project Name = " . $projectNameExplode[0] . " Prefix = " . $projectNameExplode[1] . "<br/>";
                    echo "$projectId - $projectCode - $projectName - $serviceType - $projectCategoryDescription - $blueprintId - $jobId <br/>";


                    //INSERT LOG BLUEPRINT IMPLEMENTATION
                    $tbl_sa_log_activity='log_activity';
                    $insertLogBlueprintImplementation=sprintf("(`activity`) VALUES ('Created $serviceType - $projectCategoryDescription Blueprint in $projectCode with job id $jobId')",
                        GetSQLValueString($serviceType, "text"),
                        GetSQLValueString($projectCategoryDescription, "text"),
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($jobId, "text"));

                    $resLogImplementation=$DBWR->insert_data($tbl_sa_log_activity, $insertLogBlueprintImplementation);
                }
            }
        }

        else if ($serviceType==1 && $projectCategory==3) {
            $serviceType="Implementation";
            $projectCategoryDescription="Standard";
            $conditionImplementation="object = 'Blueprint' AND condition1 = '$serviceType' AND condition2 = '$projectCategoryDescription'";
            $configImplementation=$DBWR->get_data($tbl_wrike_config, $conditionImplementation);
            $blueprintId=$configImplementation[0]['object_id'];

            // date_default_timezone_set('Indonesia/Jakarta');
            $date=date('Y-m-d', time());

            //Check Table Initial Project
            $conditionInitialImplementation="project_code='$projectCode' AND project_type='$serviceType' AND service_type = '$projectCategoryDescription' AND order_number = '$orderNumber'";
            $dataInitialProject=$DBWR->get_data($tbl_initial_project, $conditionInitialImplementation);
            $totalRowInitialProject=$dataInitialProject[2];

            //Check Table Wrike Project List Implementation
            $conditionWrikeProjectList="project_code = '$projectCode' AND project_type = 'MSI Project Implementation'";
            $dataWrikeProjectList=$DBWR->get_data($tbl_wrike_project_list, $conditionWrikeProjectList);
            $totalRowWPL=$dataWrikeProjectList[2];

            if ($totalRowWPL > 0) {
                echo "Project $serviceType - $projectCategoryDescription dengan KP. $projectCode sudah ada di WRIKE <br/>";
            }

            else {
                if ($totalRowInitialProject > 0) {
                    echo "Project Implementation dengan KP($projectCode) sudah dibuat <br/>";
                }

                else {
                    $authorization="Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
                    $url="https://www.wrike.com/api/v4/folder_blueprints/$blueprintId/launch_async";
                    //?parent=IEAEOPF5I4U765EL&title=".$projectNameExplode[0]."&titlePrefix=".$projectNameExplode[1]."&copyCustomFields=true&rescheduleMode=Start&rescheduleDate=$date";
                    $data=array('parent'=> "IEAEOPF5I4U765EL", 'title'=> "". $projectNameExplode[0] . "", 'titlePrefix'=> "[". $internalProjectName . "] ", 'copyCustomFields'=> "true", 'rescheduleMode'=> "Start", 'rescheduleDate'=> "$date");
                    $postdata=json_encode($data);
                    $ch=curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                    $result=curl_exec($ch);
                    curl_close($ch);
                    print_r($result);

                    echo "<br/>";

                    $result=json_decode($result, true);
                    $jobId=$result['data'][0]['id'];

                    $insertJobId=sprintf("(`project_code`,`job_id`, `project_type`, `service_type`, `order_number`) VALUES ('$projectCode', '$jobId' , '$serviceType', '$projectCategoryDescription', '$orderNumber')",
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($jobId, "text"),
                        GetSQLValueString($serviceType, "text"),
                        GetSQLValueString($orderNumber, "text"));

                    $resJobId=$DBWR->insert_data($tbl_initial_project, $insertJobId);

                    // echo "Project Name = " . $projectNameExplode[0] . " Prefix = " . $projectNameExplode[1] . "<br/>";
                    echo "$projectId - $projectCode - $projectName - $serviceType - $projectCategoryDescription - $blueprintId - $jobId <br/>";


                    //INSERT LOG BLUEPRINT IMPLEMENTATION
                    $tbl_sa_log_activity='log_activity';
                    $insertLogBlueprintImplementation=sprintf("(`activity`) VALUES ('Created $serviceType - $projectCategoryDescription Blueprint in $projectCode with job id $jobId')",
                        GetSQLValueString($serviceType, "text"),
                        GetSQLValueString($projectCategoryDescription, "text"),
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($jobId, "text"));

                    $resLogImplementation=$DBWR->insert_data($tbl_sa_log_activity, $insertLogBlueprintImplementation);
                }
            }
        }

        else if ($serviceType==2 && $tosId==5) {
            $serviceType="Maintenance";
            $tosName="Gold";

            $conditionMaintenanceGold="object = 'Blueprint' AND condition1 = '$serviceType' AND condition2='$tosName'";
            $configMaintenanceGold=$DBWR->get_data($tbl_wrike_config, $conditionMaintenanceGold);
            $blueprintId=$configMaintenanceGold[0]['object_id'];

            date_default_timezone_set('Indonesia/Jakarta');
            $date=date('Y-m-d', time());

            //Check Table Initial Project
            $conditionInitialGold="project_code='$projectCode' AND project_type='$serviceType' AND service_type='$tosName' AND order_number = '$orderNumber'";
            $dataInitialProject=$DBWR->get_data($tbl_initial_project, $conditionInitialGold);
            $totalRowInitialProject=$dataInitialProject[2];

            //Check Table Wrike Project List Maintenance
            $conditionWrikeProjectList="project_code = '$projectCode' AND project_type = 'MSI Project Maintenance'";
            $dataWrikeProjectList=$DBWR->get_data($tbl_wrike_project_list, $conditionWrikeProjectList);
            $projectCodeWPL=$dataWrikeProjectList[0]['project_code'];
            $projectTypeWPL=$dataWrikeProjectList[0]['project_type'];
            $totalRowWPL=$dataWrikeProjectList[2];

            if ($totalRowWPL > 0) {
                echo "Project $serviceType dan ToS $tosName dengan KP. $projectCode sudah ada di WRIKE <br/>";
            }

            else {
                if ($totalRowInitialProject > 0) {
                    echo "Project Maintenance Gold dengan KP($projectCode) sudah dibuat <br/>";
                }

                else {
                    $authorization="Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
                    $url="https://www.wrike.com/api/v4/folder_blueprints/$blueprintId/launch_async";
                    //?parent=IEAEOPF5I4U765EL&title=".$projectNameExplode[0]."&titlePrefix=".$projectNameExplode[1]."&copyCustomFields=true&rescheduleMode=Start&rescheduleDate=$date";
                    $data=array('parent'=> "IEAEOPF5I4U765EL", 'title'=> "". $projectNameExplode[0] . " ". $serviceType . "", 'titlePrefix'=> "[". $internalProjectName . "] ", 'copyCustomFields'=> "true", 'rescheduleMode'=> "Start", 'rescheduleDate'=> "$date");
                    $postdata=json_encode($data);
                    $ch=curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                    $result=curl_exec($ch);
                    curl_close($ch);
                    print_r($result);

                    echo "<br/>";

                    $result=json_decode($result, true);
                    $jobId=$result['data'][0]['id'];

                    $insertJobIdGold=sprintf("(`project_code`,`job_id`, `project_type`, `service_type`, `order_number`) VALUES ('$projectCode', '$jobId' , '$serviceType', '$tosName', '$orderNumber')",
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($jobId, "text"),
                        GetSQLValueString($serviceType, "text"),
                        GetSQLValueString($tosName, "text"),
                        GetSQLValueString($orderNumber, "text"));

                    $resJobIdGold=$DBWR->insert_data($tbl_initial_project, $insertJobIdGold);

                    // echo "Project Name = " . $projectNameExplode[0] . " Prefix = " . $projectNameExplode[1] . "<br/>";
                    echo "$projectId - $projectCode - $projectName - $serviceType - $tosName - $blueprintId - $jobId <br/>";

                    //INSERT LOG BLUEPRINT MAINTENANCE GOLD
                    $tbl_sa_log_activity='log_activity';
                    $insertLogBlueprintMaintenanceG=sprintf("(`activity`) VALUES ('Created $serviceType $tosName Blueprint in $projectCode with job id $jobId')",
                        GetSQLValueString($serviceType, "text"),
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($jobId, "text"));

                    $resLogMaintenanceG=$DBWR->insert_data($tbl_sa_log_activity, $insertLogBlueprintMaintenanceG);
                }
            }
        }

        else if ($serviceType==2 && $tosId==6) {
            $serviceType="Maintenance";
            $tosName="Silver";

            $conditionMaintenanceSilver="object = 'Blueprint' AND condition1 = '$serviceType' AND condition2='$tosName'";
            $configMaintenanceSilver=$DBWR->get_data($tbl_wrike_config, $conditionMaintenanceSilver);
            $blueprintId=$configMaintenanceSilver[0]['object_id'];

            date_default_timezone_set('Indonesia/Jakarta');
            $date=date('Y-m-d', time());

            //Check Table Initial Project
            $conditionInitialSilver="project_code='$projectCode' AND project_type='$serviceType' AND service_type='$tosName' AND order_number = '$orderNumber'";
            $dataInitialProject=$DBWR->get_data($tbl_initial_project, $conditionInitialSilver);
            $totalRowInitialProject=$dataInitialProject[2];

            //Check Table Wrike Project List Maintenance
            $conditionWrikeProjectList="project_code = '$projectCode' AND project_type = 'MSI Project Maintenance'";
            $dataWrikeProjectList=$DBWR->get_data($tbl_wrike_project_list, $conditionWrikeProjectList);
            $projectCodeWPL=$dataWrikeProjectList[0]['project_code'];
            $projectTypeWPL=$dataWrikeProjectList[0]['project_type'];
            $totalRowWPL=$dataWrikeProjectList[2];

            if ($totalRowWPL > 0) {
                echo "Project $serviceType $tosName dengan KP. $projectCode sudah ada di WRIKE <br/>";
            }

            else {
                if ($totalRowInitialProject > 0) {
                    echo "Project Maintenance Silver dengan KP($projectCode) sudah dibuat <br/>";
                }

                else {
                    $authorization="Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
                    $url="https://www.wrike.com/api/v4/folder_blueprints/$blueprintId/launch_async";
                    //?parent=IEAEOPF5I4U765EL&title=".$projectNameExplode[0]."&titlePrefix=".$projectNameExplode[1]."&copyCustomFields=true&rescheduleMode=Start&rescheduleDate=$date";
                    $data=array('parent'=> "IEAEOPF5I4U765EL", 'title'=> "". $projectNameExplode[0] . " ". $serviceType . "", 'titlePrefix'=> "[". $internalProjectName . "] ", 'copyCustomFields'=> "true", 'rescheduleMode'=> "Start", 'rescheduleDate'=> "$date");
                    $postdata=json_encode($data);
                    $ch=curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                    $result=curl_exec($ch);
                    curl_close($ch);
                    print_r($result);

                    echo "<br/>";

                    $result=json_decode($result, true);
                    $jobId=$result['data'][0]['id'];

                    $insertJobIdSilver=sprintf("(`project_code`,`job_id`, `project_type`, `service_type`, `order_number`) VALUES ('$projectCode', '$jobId' , '$serviceType', '$tosName', '$orderNumber')",
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($jobId, "text"),
                        GetSQLValueString($serviceType, "text"),
                        GetSQLValueString($tosName, "text"),
                        GetSQLValueString($orderNumber, "text"));

                    $resJobIdSilver=$DBWR->insert_data($tbl_initial_project, $insertJobIdSilver);

                    // echo "Project Name = " . $projectNameExplode[0] . " Prefix = " . $projectNameExplode[1] . "<br/>";
                    echo "$projectId - $projectCode - $projectName - $serviceType - $tosName - $blueprintId - $jobId <br/>";

                    //INSERT LOG BLUEPRINT MAINTENANCE SILVER
                    $tbl_sa_log_activity='log_activity';
                    $insertLogBlueprintMaintenanceS=sprintf("(`activity`) VALUES ('Created $serviceType $tosName Blueprint in $projectCode with job id $jobId')",
                        GetSQLValueString($serviceType, "text"),
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($jobId, "text"));

                    $resLogMaintenanceS=$DBWR->insert_data($tbl_sa_log_activity, $insertLogBlueprintMaintenanceS);
                }
            }
        }

        else if ($serviceType==2 && $tosId==7) {
            $serviceType="Maintenance";
            $tosName="Bronze";
            $conditionMaintenanceBronze="object = 'Blueprint' AND condition1 = '$serviceType' AND condition2='$tosName'";
            $configMaintenanceBronze=$DBWR->get_data($tbl_wrike_config, $conditionMaintenanceBronze);
            $blueprintId=$configMaintenanceBronze[0]['object_id'];

            // date_default_timezone_set('Indonesia/Jakarta');
            $date=date('Y-m-d', time());

            //Check Table Initial Project
            $conditionInitialBronze="project_code='$projectCode' AND project_type='$serviceType' AND service_type='$tosName' AND order_number = '$orderNumber'";
            $dataInitialProject=$DBWR->get_data($tbl_initial_project, $conditionInitialBronze);
            $totalRowInitialProject=$dataInitialProject[2];

            //Check Table Wrike Project List Maintenance
            $conditionWrikeProjectList="project_code = '$projectCode' AND project_type = 'MSI Project Maintenance'";
            $dataWrikeProjectList=$DBWR->get_data($tbl_wrike_project_list, $conditionWrikeProjectList);
            $totalRowWPL=$dataWrikeProjectList[2];

            if ($totalRowWPL > 0) {}

            else {
                if ($totalRowInitialProject > 0) {
                    echo "Project Maintenance Bronze dengan KP($projectCode) sudah dibuat <br/>";
                }

                else {
                    $authorization="Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
                    $url="https://www.wrike.com/api/v4/folder_blueprints/$blueprintId/launch_async";
                    //?parent=IEAEOPF5I4U765EL&title=".$projectNameExplode[0]."&titlePrefix=".$projectNameExplode[1]."&copyCustomFields=true&rescheduleMode=Start&rescheduleDate=$date";
                    $data=array('parent'=> "IEAEOPF5I4U765EL", 'title'=> "". $projectNameExplode[0] . " ". $serviceType . "", 'titlePrefix'=> "[". $internalProjectName . "] ", 'copyCustomFields'=> "true", 'rescheduleMode'=> "Start", 'rescheduleDate'=> "$date");
                    $postdata=json_encode($data);
                    $ch=curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                    $result=curl_exec($ch);
                    curl_close($ch);
                    print_r($result);

                    echo "<br/>";

                    $result=json_decode($result, true);
                    $jobId=$result['data'][0]['id'];

                    $insertJobIdBronze=sprintf("(`project_code`,`job_id`, `project_type`, `service_type`, `order_number`) VALUES ('$projectCode', '$jobId' , '$serviceType', '$tosName', '$orderNumber')",
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($jobId, "text"),
                        GetSQLValueString($serviceType, "text"),
                        GetSQLValueString($tosName, "text"),
                        GetSQLValueString($orderNumber, "text"));

                    $resJobIdBronze=$DBWR->insert_data($tbl_initial_project, $insertJobIdBronze);

                    echo "$projectId - $projectCode - $projectName - $serviceType - $tosName - $blueprintId - $jobId <br/>";

                    //INSERT LOG BLUEPRINT MAINTENANCE BRONZE
                    $tbl_sa_log_activity='log_activity';
                    $insertLogBlueprintMaintenanceB=sprintf("(`activity`) VALUES ('Created $serviceType $tosName Blueprint in $projectCode with job id $jobId')",
                        GetSQLValueString($serviceType, "text"),
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($jobId, "text"));

                    $resLogMaintenanceB=$DBWR->insert_data($tbl_sa_log_activity, $insertLogBlueprintMaintenanceB);
                }
            }
        }
    }

    while ($rowSB=$resSB->fetch_assoc());
}

function async_job() {
    global $DBWR,
    $DBSB,
    $DBGD;

    $tbl_initial_project='initial_project';
    $dataInitialProject=$DBWR->get_data($tbl_initial_project);
    $rowInitialProject=$dataInitialProject[0];
    $resInitialProject=$dataInitialProject[1];
    $totalRowInitialProject=$dataInitialProject[2];

    do {
        $projectCode=$rowInitialProject['project_code'];
        $jobId=$rowInitialProject['job_id'];
        $status=$rowInitialProject['status'];

        //echo "$projectCode - $jobId <br/>";
        if ($status==1) {
            $authorization="Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
            $curl=curl_init();

            curl_setopt($curl, CURLOPT_URL, "https://www.wrike.com/api/v4/async_job/$jobId");
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $result=curl_exec($curl);
            curl_close($curl);

            $result=json_decode($result, true);

            //GET Customer Name
            $data=$result['data'];

            for ($i=0; $i < count($data); $i++) {
                $folderId=$data[0]['result']['folderId'];

                $conditionUpdateFolderId="job_id = '$jobId'";

                if ($folderId !='') {
                    $updateFolderIdData=sprintf("`project_id`= '$folderId', `status` = 2",
                        GetSQLValueString($folderId, "text"));

                    $resFolderIdData=$DBWR->update_data($tbl_initial_project, $updateFolderIdData, $conditionUpdateFolderId);
                    echo "$projectCode - $jobId - $folderId <br/>";
                }
            }

            //INSERT LOG ASYNCJOB
            $tbl_sa_log_activity='log_activity';
            $insertLogAsyncJob=sprintf("(`activity`) VALUES ('Asynced project $projectCode with job id $jobId')",
                GetSQLValueString($projectCode, "text"));

            $resLogAsync=$DBWR->insert_data($tbl_sa_log_activity, $insertLogAsyncJob);
        }

        else {
            echo "$projectCode sudah di async <br/>";
        }
    }

    while ($rowInitialProject=$resInitialProject->fetch_assoc());
}

function push_jobroles() {
    $db_wr="WRIKE_INTEGRATE";
    $DBWR=get_conn($db_wr);

    $db_sb="SERVICE_BUDGET";
    $DBSB=get_conn($db_sb);

    $db_wrkld="WORKLOAD";
    $DBWRKLD=get_conn($db_wrkld);

    $querySQLIP="SELECT * FROM sa_initial_project WHERE status = 2 AND project_id != ''";
    $dataSQLIP=$DBWR->get_sql($querySQLIP);
    $rowSQLIP=$dataSQLIP[0];
    $resSQLIP=$dataSQLIP[1];

    do {
        $projectCode=$rowSQLIP['project_code'];
        $projectId=$rowSQLIP['project_id'];
        $projectType=$rowSQLIP['project_type'];

        if ($projectType=='Implementation') {
            $projectType=1;
        }

        else {
            $projectType=2;
        }

        $sqlGetProjectInternal="SELECT * FROM sa_trx_project_list WHERE project_code = '$projectCode' AND STATUS = 'acknowledge'";
        $dataProjectInternal=$DBSB->get_sql($sqlGetProjectInternal);
        $projectNameInternalSB=$dataProjectInternal[0]['project_name_internal'];

        // $projectNameInternal = "[$projectNameInternalSB] Project Resources";
        // $projectNameInternal = "[$projectNameInternalSB] Service Budget";

        $authorization="Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
        $curl=curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://www.wrike.com/api/v4/folders/$projectId");
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result=curl_exec($curl);
        curl_close($curl);
        $result=json_decode($result, true);

        //GET Customer Name
        $data=$result['data'];

        for ($i=0; $i < count($data); $i++) {
            $projectIdWrike = $data[$i]['id'];
            $jobRoleFolderIdArray=$data[$i]['childIds'];
            $title=$data[$i]['title'];

            for ($j=0; $j < count($jobRoleFolderIdArray); $j++) {
                $jobRoleFolderId=$jobRoleFolderIdArray[$j];
                $authorization="Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
                $curl=curl_init();
                curl_setopt($curl, CURLOPT_URL, "https://www.wrike.com/api/v4/folders/$jobRoleFolderId");
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                $result1=curl_exec($curl);
                curl_close($curl);
                $result2=json_decode($result1, true);

                //GET Customer Name
                $jobrolesName=$result2['data'][0]['title'];

                if (strpos($jobrolesName, 'Job Role') !==false) {
                    $jobrolesChildId=$result2['data'][0]['childIds'];
                    var_dump($jobrolesChildId);

                    for($i1=0; $i1 < count($jobrolesChildId); $i1++) {
                        $jobrolesChildId1=$jobrolesChildId[$i1];

                        $authorization="Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
                        $curl=curl_init();
                        curl_setopt($curl, CURLOPT_URL, "https://www.wrike.com/api/v4/folders/$jobrolesChildId1");
                        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                        $result2=curl_exec($curl);
                        curl_close($curl);
                        $result3=json_decode($result2, true);

                        //GET Customer Name
                        $sbName=$result3['data'][0]['title'];

                        echo $sbName;

                        if (strpos($sbName, 'Service Budget') !==false) {
                            $sbFolderId=$result3['data'][0]['id'];
                            echo "$sbFolderId - $sbName<br/>";
                        }
                    }
                }
            }
        }

        $sqlMandaysInformation="SELECT * FROM sa_trx_project_list WHERE project_code = '$projectCode' AND STATUS = 'acknowledge'";
        $dataMandaysInformation=$DBSB->get_sql($sqlMandaysInformation);
        $idProject=$dataMandaysInformation[0]['project_id'];

        $sqlMandays="SELECT SUBSTRING(resource_level, 1, 1) AS resource_level, mantotal, mandays, brand FROM sa_trx_project_mandays WHERE project_id = $idProject AND mantotal != '' AND mandays !='' AND service_type=$projectType AND (SUBSTRING(resource_level, 1, 1) BETWEEN 1 AND 4)";
        $dataSqlMandays=$DBSB->get_sql($sqlMandays);
        $rowSqlMandays=$dataSqlMandays[0];
        $resSqlMandays=$dataSqlMandays[1];

        $sqlMandaysInformation1="SELECT SUBSTRING(resource_level, 1, 1) AS resource_level, mantotal, mandays, brand FROM sa_trx_project_mandays WHERE project_id = $idProject AND mantotal != '' AND mandays !='' AND service_type=$projectType AND (SUBSTRING(resource_level, 1, 1) BETWEEN 1 AND 4)";
        $dataSqlMandaysInformation1=$DBSB->get_sql($sqlMandaysInformation1);
        $rowSqlMandaysInformation1=$dataSqlMandaysInformation1[0];
        $resSqlMandaysInformation1=$dataSqlMandaysInformation1[1];

        do {
            $resourceLevels = $rowSqlMandays['resource_level'];
            $mantotal=$rowSqlMandays['mantotal'];
            $mandays=$rowSqlMandays['mandays'];

            echo "$resourceLevels - $mantotal - $mandays <br/>";

            if($resourceLevels == 1){
                $arrMantotals1[] = $mantotal;
                $totalMandays1=$mantotal * $mandays;
                $arrTotalMandays1[]=$totalMandays1;
            }else if($resourceLevels == 2){
                $arrMantotals2[] = $mantotal;
                $totalMandays2=$mantotal * $mandays;
                $arrTotalMandays2[]=$totalMandays2;
            }else if($resourceLevels == 3){
                $arrMantotals3[] = $mantotal;
                $totalMandays3=$mantotal * $mandays;
                $arrTotalMandays3[]=$totalMandays3;
            }else{
                $arrMantotals4[] = $mantotal;
                $totalMandays4=$mantotal * $mandays;
                $arrTotalMandays4[]=$totalMandays4;
            }
        }while ($rowSqlMandays=$resSqlMandays->fetch_assoc());
        
        // Variabel mantotal
        $mantotals1 = array_sum($arrMantotals1);
        $mantotals2 = array_sum($arrMantotals2);
        $mantotals3 = array_sum($arrMantotals3);
        $mantotals4 = array_sum($arrMantotals4);

        // Variabel Mandays
        $mandaysJobRoles1=array_sum($arrTotalMandays1);
        $mandaysJobRoles2=array_sum($arrTotalMandays2);
        $mandaysJobRoles3=array_sum($arrTotalMandays3);
        $mandaysJobRoles4=array_sum($arrTotalMandays4);

        do {
            $resourceLevel=$rowSqlMandaysInformation1['resource_level'];
            $brand=$rowSqlMandaysInformation1['brand'];

            echo "$resourceLevel";
            
            if ($resourceLevel !='') {
                if($resourceLevel == 1){
                    $mantotals = $mantotals1;
                    $totalMandays = $mandaysJobRoles1;
                }else if($resourceLevel == 2){
                    $mantotals = $mantotals2;
                    $totalMandays = $mandaysJobRoles2;
                }else if($resourceLevel == 3){
                    $mantotals = $mantotals3;
                    $totalMandays = $mandaysJobRoles3;
                }else{
                    $mantotals = $mantotals4;
                    $totalMandays = $mandaysJobRoles4;
                }
                // echo "$mandaysJobRoles1<br/> $mandaysJobRoles2 <br/> $mandaysJobRoles3 <br/> $mandaysJobRoles4<br/>";

                $queryLookupCatalogs="SELECT resource_qualification FROM sa_mst_resource_catalogs WHERE resource_catalog_id = $resourceLevel";
                $dataLookupCatalogs=$DBSB->get_sql($queryLookupCatalogs);
                $resourceCatalogName=$dataLookupCatalogs[0]['resource_qualification'];

                $tbl_initial_jobroles='initial_jobroles';
                $queryCheckJobRoles="SELECT * FROM sa_initial_jobroles WHERE project_id = '$projectId' AND resource_category_id = $resourceLevel AND brand = '$brand'";
                $dataCheckJobRoles=$DBWRKLD->get_sql($queryCheckJobRoles);
                $totalRowJobRoles=$dataCheckJobRoles[2];

                if($totalRowJobRoles > 0) {
                    echo "Sudah Ada <br/>";
                }
                else {
                    $authorization="Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
                    $url="https://www.wrike.com/api/v4/custom_item_types/IEAEOPF5PIAAFWRU/instantiate";
                    //?parent=IEAEOPF5I4U765EL&title=".$projectNameExplode[0]."&titlePrefix=".$projectNameExplode[1]."&copyCustomFields=true&rescheduleMode=Start&rescheduleDate=$date";
                    $data=array('title'=> "$resourceCatalogName", 'parentId'=> "$sbFolderId");
                    $postdata=json_encode($data);
                    $ch=curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                    $result3=curl_exec($ch);
                    curl_close($ch);
                    print_r($result3);

                    echo "<br/>";
                    $result3=json_decode($result3, true);
                    $taskJobId=$result3['data'][0]['id'];

                    //INSERT LOG MODIFT IMPLEMENTATION
                    $insertJobRoles=sprintf("(`task_id`, `parent_id`, `project_code`, `project_id`, `resource_category_id`, `total_mandays`) VALUES ('$taskJobId', 'SERVICE BUDGET', '$projectCode', '$projectIdWrike', '$resourceLevel',$totalMandays)",
                        GetSQLValueString($taskJobId, "text"),
                        GetSQLValueString($sbFolderId, "text"),
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($resourceCatalogName, "text"));

                    $resInitialJobRoles=$DBWRKLD->insert_data($tbl_initial_jobroles, $insertJobRoles);

                    $url="https://www.wrike.com/api/v4/tasks/$taskJobId";
                    $dataPut=array('description'=> "Total Mans = $mantotals", 'customFields' => "[{'id':'IEAEOPF5JUAC3YH2','value':'$totalMandays'}]");
                    $put_data=json_encode($dataPut);
                    $authorization="Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";

                    $ch=curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $put_data);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));

                    $response=curl_exec($ch);
                    curl_close($ch);
                    print_r($response);

                    echo "$resourceLevel - $brand - $sbFolderId - $projectCode - $totalMandays<br/>";
                }
            }
        }while ($rowSqlMandaysInformation1=$resSqlMandaysInformation1->fetch_assoc());


        $sqlMandaysInformation2="SELECT SUBSTRING(resource_level, 1, 1) AS resource_level, mantotal, mandays, brand FROM sa_trx_project_mandays WHERE project_id = $idProject AND mantotal != '' AND mandays !='' AND service_type=$projectType AND (SUBSTRING(resource_level, 1, 1) BETWEEN 5 AND 7)";
        $dataSqlMandaysInformation2=$DBSB->get_sql($sqlMandaysInformation2);
        $rowSqlMandaysInformation2=$dataSqlMandaysInformation2[0];
        $resSqlMandaysInformation2=$dataSqlMandaysInformation2[1];

        do {
            $resourceLevelEngineer=$rowSqlMandaysInformation2['resource_level'];
            $brandEngineer=$rowSqlMandaysInformation2['brand'];
            $mantotalEngineer=$rowSqlMandaysInformation2['mantotal'];
            $mandaysEngineer=$rowSqlMandaysInformation2['mandays'];
            $totalMandaysEngineer=$mantotalEngineer * $mandaysEngineer;

            if ($resourceLevelEngineer !='') {
                $queryLookupCatalogsEngineer="SELECT resource_qualification FROM sa_mst_resource_catalogs WHERE resource_catalog_id = $resourceLevelEngineer";
                $dataLookupCatalogsEngineer=$DBSB->get_sql($queryLookupCatalogsEngineer);
                $resourceCatalogNameEngineer=$dataLookupCatalogsEngineer[0]['resource_qualification'];

                $tbl_initial_jobroles='initial_jobroles';
                $queryCheckJobRolesEngineer="SELECT * FROM sa_initial_jobroles WHERE resource_category_id = $resourceLevelEngineer AND brand = '$brandEngineer'";
                $dataCheckJobRolesEngineer=$DBWRKLD->get_sql($queryCheckJobRolesEngineer);
                $totalRowJobRolesEngineer=$dataCheckJobRolesEngineer[2];

                if($totalRowJobRolesEngineer > 0) {}

                else {
                    $authorization="Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
                    $url="https://www.wrike.com/api/v4/custom_item_types/IEAEOPF5PIAAFWRU/instantiate";
                    //?parent=IEAEOPF5I4U765EL&title=".$projectNameExplode[0]."&titlePrefix=".$projectNameExplode[1]."&copyCustomFields=true&rescheduleMode=Start&rescheduleDate=$date";
                    $data=array('title'=> "$resourceCatalogNameEngineer $brandEngineer", 'parentId'=> "$sbFolderId");
                    $postdata=json_encode($data);
                    $ch=curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                    $result4=curl_exec($ch);
                    curl_close($ch);
                    print_r($result4);

                    echo "<br/>";
                    $result4=json_decode($result4, true);
                    $taskJobIdEngineer=$result4['data'][0]['id'];

                    //INSERT LOG MODIFT IMPLEMENTATION

                    $insertJobRolesEngineer=sprintf("(`task_id`, `parent_id`, `project_code`, `project_id`, `resource_category_id`, `brand`, `total_mandays`) VALUES ('$taskJobIdEngineer', 'SERVICE BUDGET', '$projectCode', '$projectIdWrike', '$resourceLevelEngineer', '$brandEngineer', $totalMandaysEngineer)",
                        GetSQLValueString($taskJobIdEngineer, "text"),
                        GetSQLValueString($sbFolderId, "text"),
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($resourceCatalogName, "text"));

                    $resInitialJobRolesEngineer=$DBWRKLD->insert_data($tbl_initial_jobroles, $insertJobRolesEngineer);

                    $urlEngineer="https://www.wrike.com/api/v4/tasks/$taskJobIdEngineer";
                    $dataPutEngineer=array('description'=> "Mans = $mantotalEngineer", 'customFields' => "[{'id':'IEAEOPF5JUAC3YH2','value':'$totalMandaysEngineer'}]");
                    $put_dataEngineer=json_encode($dataPutEngineer);
                    $authorization="Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";

                    $ch=curl_init();
                    curl_setopt($ch, CURLOPT_URL, $urlEngineer);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $put_dataEngineer);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));

                    $response=curl_exec($ch);
                    curl_close($ch);
                    print_r($response);

                    echo "$resourceLevelEngineer - $brandEngineer - $sbFolderId - $projectCode - $totalMandaysEngineer<br/>";
                }
            }
        }

        while($rowSqlMandaysInformation2=$resSqlMandaysInformation2->fetch_assoc());

    }

    while ($rowSQLIP=$resSQLIP->fetch_assoc());
}

function modify_project() {
    global $DBWR,
    $DBGD,
    $DBSB;

    $tbl_wrike_config='wrike_config';
    $tbl_initial_project='initial_project';
    $dataInitialProject=$DBWR->get_data($tbl_initial_project);
    $rowInitialProject=$dataInitialProject[0];
    $resInitialProject=$dataInitialProject[1];
    $totalRowInitialProject=$dataInitialProject[2];

    do {
        $projectType='';
        $projectId=$rowInitialProject['project_id'];
        $projectCode=$rowInitialProject['project_code'];
        $projectType=$rowInitialProject['project_type'];
        // $projectSbId = $rowInitialProject['project_sb_id'];
        $orderNumber=$rowInitialProject['order_number'];
        $status=$rowInitialProject['status'];

        if ($status==2) {
            $queryOwner="SELECT * FROM sa_wrike_config WHERE object = 'Owner' AND condition1 = '$projectType'";
            $dataQuery=$DBWR->get_sql($queryOwner);
            $rowOwner=$dataQuery[0];
            $resOwner=$dataQuery[1];
            $fullOwnerId='';

            do {
                $ownerId=$rowOwner['object_id'];
                $fullOwnerId='"'. $ownerId . '", '. $fullOwnerId;
                $fullOwnerId=rtrim($fullOwnerId, ', ');
            }

            while ($rowOwner=$resOwner->fetch_assoc());

            // echo "$fullOwnerId <br/>";

            if ($projectType=='Implementation') {
                $projectType=1;
            }

            else if ($projectType=='Maintenance') {
                $projectType=2;
            }

            $db_sb="SERVICE_BUDGET";
            $DBSB=get_conn($db_sb);
            $queryEnterpriseProjectType="SELECT a.project_id, a.project_code, a.project_name, a.project_name_internal, a.customer_name, a.amount_idr, a.amount_usd, a.so_number, a.order_number, a.bundling, b.tos_id, b.tos_category_id, b.service_type, c.tos_name FROM sa_trx_project_list AS a 
    LEFT JOIN sa_trx_project_implementations AS b ON a.project_id=b.project_id LEFT JOIN sa_mst_type_of_service AS c ON b.tos_id=c.tos_id WHERE a.status='acknowledge'AND a.bundling !=''AND a.sbtype=1 AND c.tos_id IN (1, 2, 3, 4, 5, 6, 7) AND a.order_number='$orderNumber'";

            $dataProjectCategory=$DBSB->get_sql($queryEnterpriseProjectType);
            $rowProjectCategory=$dataProjectCategory[0];
            $resProjectCategory=$dataProjectCategory[1];
            $totalRowProjectCategory=$dataProjectCategory[2];

            do {
                $projectCategory='';
                $fullTypeOfService='';
                $amountIDR='';
                $amountUSD='';
                $currency='';
                $soNumber='';
                $projectName=$rowProjectCategory['project_name'];
                $projectNameExplode=explode('#', $projectName);
                $soNumber=$rowProjectCategory['so_number'];
                $customerName=$rowProjectCategory['customer_name'];
                $projectCategory=$rowProjectCategory['tos_category_id'];
                $serviceType1=$rowProjectCategory['service_type'];
                $serviceType=$rowProjectCategory['tos_id'];
                $serviceTypeImplement=$rowProjectCategory['tos_id'];
                $serviceTypeImplement=rtrim($serviceTypeImplement, ';');
                $serviceTypeImplement=explode(';', $serviceTypeImplement);
                $tosName=$rowProjectCategory['tos_name'];
                $internalProjectName=$rowProjectCategory['project_name_internal'];
                $amountIDR=$rowProjectCategory['amount_idr'];
                $amountIDR=round($amountIDR, 0);
                $amountUSD=$rowProjectCategory['amount_usd'];
                $amountUSD=round($amountUSD, 0);


                if ($amountIDR !='') {
                    $currency='IDR';
                    $priceService=$amountIDR;
                    $conditionCurrencyOption="object = 'CustomField' AND title = 'Currency' and condition2 = '". $currency . "'";
                    $customfieldCurrency=$DBWR->get_data($tbl_wrike_config, $conditionCurrencyOption);
                    $currencyOptionId=$customfieldCurrency[0]['object_id'];
                }

                else if ($amountUSD !='') {
                    $currency='USD';
                    $priceService=$amountUSD;
                    $conditionCurrencyOption="object = 'CustomField' AND title = 'Currency' and condition2 = '". $currency . "'";
                    $customfieldCurrency=$DBWR->get_data($tbl_wrike_config, $conditionCurrencyOption);
                    $currencyOptionId=$customfieldCurrency[0]['object_id'];
                }

                if ($projectType==1 && $serviceType1==1) {
                    if ($projectCategory==1) {
                        $projectCategoryDescription='High';
                    }

                    else if ($projectCategory==2) {
                        $projectCategoryDescription='Medium';
                    }

                    else if ($projectCategory==3) {
                        $projectCategoryDescription='Standard';
                    }

                    for ($j=0; $j < count($serviceTypeImplement); $j++) {
                        $conditionServiceName="tos_id = '". $serviceTypeImplement[$j] . "'";
                        $tblMstTypeOfService='mst_type_of_service';
                        $getTypeOfService=$DBSB->get_data($tblMstTypeOfService, $conditionServiceName);
                        $valueTypeOfService=$getTypeOfService[0]['tos_name'];
                        $fullTypeOfService='"'. $valueTypeOfService . '", '. $fullTypeOfService;
                        $fullTypeOfService=rtrim($fullTypeOfService, ', ');
                    }

                    if ($serviceType1==1) {
                        $enterpriseProjectType="MSI Project Implementation";
                    }

                    //Enterprise Project Type ID
                    $conditionEnterpriseProjectType="object = 'CustomField' AND title = 'Enterprise Project Type' AND condition1 = '$enterpriseProjectType'";
                    $customfieldEPT=$DBWR->get_data($tbl_wrike_config, $conditionEnterpriseProjectType);
                    $eptId=$customfieldEPT[0]['object_id'];

                    //Project Category ID
                    $conditionProjectCategory="object = 'CustomField' AND title = 'Project Category' AND condition2 = '$projectCategoryDescription'";
                    $customfieldPC=$DBWR->get_data($tbl_wrike_config, $conditionProjectCategory);
                    $pcId=$customfieldPC[0]['object_id'];

                    //Project Code ID
                    $conditionProjectCode="object = 'CustomField' AND title = 'Project Code'";
                    $customfieldPCode=$DBWR->get_data($tbl_wrike_config, $conditionProjectCode);
                    $projectCodeId=$customfieldPCode[0]['object_id'];

                    //Sales Order Number Id
                    $conditionSalesOrder="object = 'CustomField' AND title = 'Sales Order'";
                    $customfieldSOrder=$DBWR->get_data($tbl_wrike_config, $conditionSalesOrder);
                    $noSOId=$customfieldSOrder[0]['object_id'];

                    //Project Value ID
                    $conditionProjectValue="object = 'CustomField' AND title = 'Project Value'";
                    $customfieldPValue=$DBWR->get_data($tbl_wrike_config, $conditionProjectValue);
                    $projectValueId=$customfieldPValue[0]['object_id'];

                    //Project Service ID
                    $conditionProjectService="object = 'CustomField' AND title = 'Project Service'";
                    $customfieldPService=$DBWR->get_data($tbl_wrike_config, $conditionProjectService);
                    $projectServiceId=$customfieldPService[0]['object_id'];

                    //Mandays
                    $mandaysQuery="SELECT sa_trx_project_list.project_id, sa_trx_project_list.project_id, sa_trx_project_list.order_number, sa_trx_project_list.project_code, sa_trx_project_mandays.mantotal, 
    sa_trx_project_mandays.mandays,
                    sa_trx_project_mandays.service_type,
                    sa_trx_project_mandays.brand FROM sa_trx_project_list JOIN sa_trx_project_mandays ON sa_trx_project_list.project_id=sa_trx_project_mandays.project_id WHERE sa_trx_project_list.project_code='$projectCode'
                    AND sa_trx_project_mandays.service_type=$projectType AND sa_trx_project_list.order_number='$orderNumber'";

                    $dataMandays=$DBSB->get_sql($mandaysQuery);
                    $rowDataMandays=$dataMandays[0];
                    $resDataMandays=$dataMandays[1];
                    $totalRowDataMandays=$dataMandays[2];
                    unset($arrTotalMandays);

                    do {
                        $mantotal=$rowDataMandays['mantotal'];
                        $mandays=$rowDataMandays['mandays'];

                        if ($mantotal !=NULL && $mandays !=NULL) {
                            $totalMandays=$mantotal * $mandays;
                            $arrTotalMandays[]=$totalMandays;
                        }

                        else {
                            echo "Terdapat Nilai NULL";
                        }
                    }

                    while ($rowDataMandays=$resDataMandays->fetch_assoc());

                    // Variabel Mandays
                    $mandaysWrike=array_sum($arrTotalMandays);

                    //Mandays ID
                    $conditionTotalMandays="object = 'CustomField' AND title = 'Total Mandays'";
                    $customfieldTMandays=$DBWR->get_data($tbl_wrike_config, $conditionTotalMandays);
                    $totalMandaysId=$customfieldTMandays[0]['object_id'];

                    //Customer ID
                    $conditionCustomerName="object = 'CustomField' AND title = 'Customer'";
                    $customfieldCName=$DBWR->get_data($tbl_wrike_config, $conditionCustomerName);
                    $customerNameId=$customfieldCName[0]['object_id'];

                    //Internal Project Name ID
                    $conditionInternalProjectName="object = 'CustomField' AND title = 'Internal Project Name'";
                    $customfieldInternalProjectName=$DBWR->get_data($tbl_wrike_config, $conditionInternalProjectName);
                    $internalProjectNameId=$customfieldInternalProjectName[0]['object_id'];

                    echo "
    1. Currency ID : $currencyOptionId<br/>2. Pc ID : $pcId<br/>3. Project Code ID : $projectCodeId<br/>4. No. SO ID : $noSOId<br/>5. Project Value ID : $projectValueId<br/>6. Project Service ID : $projectServiceId<br/>7. Total Mandays ID : $totalMandaysId<br/>8. Customer Name ID : $customerNameId<br/>9. internalProjectName ID : $internalProjectNameId<br/>";

                    $url="https://www.wrike.com/api/v4/folders/$projectId"; //?project={'ownersAdd':[$fullOwnerId]}&customFields=[{'id':'$pcId', 'value':'$projectCategoryDescription'}]";
                    $data=array("project"=> "{'ownersAdd':[$fullOwnerId]}", "customFields"=> "[{'id':'$eptId', 'value':'$enterpriseProjectType'}, {'id':'$currencyOptionId', 'value':'$currency'}, {'id':'$pcId', 'value':'$projectCategoryDescription'},  {'id':'$projectCodeId','value':'$projectCode'}, {'id':'$noSOId','value':'$soNumber'}, {'id':'$projectValueId','value':'$priceService'}, {'id':'$projectServiceId','value':'[$fullTypeOfService]'}, {'id':'$totalMandaysId','value':'$mandaysWrike'}, {'id':'$customerNameId','value':'$customerName'}, {'id':'$internalProjectNameId','value':'$internalProjectName'}]");

                    //Internal Project Name
                    //, {'id':'$internalProjectNameId','value':'" . $projectNameExplode[1] . "'}
                    $put_data=json_encode($data);
                    $authorization="Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";

                    $ch=curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $put_data);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));

                    $response=curl_exec($ch);
                    curl_close($ch);
                    print_r($response);

                    $conditionStatus3="project_id = '$projectId'";
                    $updateStatus3=sprintf("`status`= 3");
                    $resStatus3=$DBWR->update_data($tbl_initial_project, $updateStatus3, $conditionStatus3);

                    echo "<br/>";

                    echo "$projectCode - $soNumber - $priceService - [$fullOwnerId] - $projectType - $tosName - $pcId ($projectCategoryDescription) <br/>";

                    //INSERT LOG MODIFT IMPLEMENTATION
                    $tbl_sa_log_activity='log_activity';
                    $insertLogCustomFieldsImplementation=sprintf("(`activity`) VALUES ('Edited customfields Wrike with id project $projectId and code $projectCode - Implementation')",
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($projectId, "text"));

                    $resLogCustomFieldsImplementation=$DBWR->insert_data($tbl_sa_log_activity, $insertLogCustomFieldsImplementation);
                }

                else if ($projectType==2 && $serviceType1==2) {
                    if ($serviceType==5) {
                        $serviceTypeDescription='Gold';
                    }

                    else if ($serviceType==6) {
                        $serviceTypeDescription='Silver';
                    }

                    else if ($serviceType==7) {
                        $serviceTypeDescription='Bronze';
                    }

                    if ($serviceType1==2) {
                        $enterpriseProjectType="MSI Project Maintenance";
                    }

                    //Enterprise Project Type ID
                    $conditionEnterpriseProjectType="object = 'CustomField' AND title = 'Enterprise Project Type' AND condition1 = '$enterpriseProjectType'";
                    $customfieldEPT=$DBWR->get_data($tbl_wrike_config, $conditionEnterpriseProjectType);
                    $eptId=$customfieldEPT[0]['object_id'];

                    //Service Type ID
                    $conditionServiceTypeMaintenance="object = 'CustomField' AND title = 'Service Type' AND condition2 = '$serviceTypeDescription'";
                    $customfieldServiceTypeMaintenance=$DBWR->get_data($tbl_wrike_config, $conditionServiceTypeMaintenance);
                    $serviceTypeId=$customfieldServiceTypeMaintenance[0]['object_id'];

                    //Project Code ID
                    $conditionProjectCode="object = 'CustomField' AND title = 'Project Code'";
                    $customfieldPCode=$DBWR->get_data($tbl_wrike_config, $conditionProjectCode);
                    $projectCodeId=$customfieldPCode[0]['object_id'];

                    //Sales Order Number Id
                    $conditionSalesOrder="object = 'CustomField' AND title = 'Sales Order'";
                    $customfieldSOrder=$DBWR->get_data($tbl_wrike_config, $conditionSalesOrder);
                    $noSOId=$customfieldSOrder[0]['object_id'];

                    //Project Value ID
                    $conditionProjectValue="object = 'CustomField' AND title = 'Project Value'";
                    $customfieldPValue=$DBWR->get_data($tbl_wrike_config, $conditionProjectValue);
                    $projectValueId=$customfieldPValue[0]['object_id'];

                    //Project Service ID
                    // $conditionProjectService = "object = 'CustomField' AND title = 'Project Service'";
                    // $customfieldPService = $DBWR->get_data($tbl_wrike_config, $conditionProjectService);
                    // $projectServiceId = $customfieldPService[0]['object_id'];

                    //Mandays
                    $mandaysQuery="SELECT sa_trx_project_list.project_id, sa_trx_project_list.project_id, sa_trx_project_list.order_number, sa_trx_project_list.project_code, sa_trx_project_mandays.mantotal, 
    sa_trx_project_mandays.mandays,
                    sa_trx_project_mandays.service_type,
                    sa_trx_project_mandays.brand FROM sa_trx_project_list JOIN sa_trx_project_mandays ON sa_trx_project_list.project_id=sa_trx_project_mandays.project_id WHERE sa_trx_project_list.project_code='$projectCode'
                    AND sa_trx_project_mandays.service_type=$projectType AND sa_trx_project_list.order_number='$orderNumber'";

                    $dataMandays=$DBSB->get_sql($mandaysQuery);
                    $rowDataMandays=$dataMandays[0];
                    $resDataMandays=$dataMandays[1];
                    $totalRowDataMandays=$dataMandays[2];
                    unset($arrTotalMandays);

                    do {
                        $mantotal=$rowDataMandays['mantotal'];
                        $mandays=$rowDataMandays['mandays'];

                        if ($mantotal !=NULL && $mandays !=NULL) {
                            $totalMandays=$mantotal * $mandays;
                            $arrTotalMandays[]=$totalMandays;
                        }

                        else {
                            echo "Terdapat Nilai NULL <br/>";
                        }
                    }

                    while ($rowDataMandays=$resDataMandays->fetch_assoc());

                    // Variabel Mandays
                    if ($arrTotalMandays !='') {
                        $mandaysWrike=array_sum($arrTotalMandays);
                    }

                    else {
                        $mandaysWrike='';
                    }

                    //Mandays ID
                    $conditionTotalMandays="object = 'CustomField' AND title = 'Total Mandays'";
                    $customfieldTMandays=$DBWR->get_data($tbl_wrike_config, $conditionTotalMandays);
                    $totalMandaysId=$customfieldTMandays[0]['object_id'];

                    //Customer ID
                    $conditionCustomerName="object = 'CustomField' AND title = 'Customer'";
                    $customfieldCName=$DBWR->get_data($tbl_wrike_config, $conditionCustomerName);
                    $customerNameId=$customfieldCName[0]['object_id'];

                    //Internal Project Name ID
                    $conditionInternalProjectName="object = 'CustomField' AND title = 'Internal Project Name'";
                    $customfieldInternalProjectName=$DBWR->get_data($tbl_wrike_config, $conditionInternalProjectName);
                    $internalProjectNameId=$customfieldInternalProjectName[0]['object_id'];

                    $url="https://www.wrike.com/api/v4/folders/$projectId"; //?project={'ownersAdd':[$fullOwnerId]}&customFields=[{'id':'$pcId', 'value':'$projectCategoryDescription'}]";
                    $data=array("project"=> "{'ownersAdd':[$fullOwnerId]}", "customFields"=> "[{'id':'$eptId', 'value':'$enterpriseProjectType'}, {'id':'$currencyOptionId', 'value':'$currency'}, {'id':'$serviceTypeId', 'value':'$serviceTypeDescription'}, {'id':'$projectCodeId','value':'$projectCode'}, {'id':'$noSOId','value':'$soNumber'}, {'id':'$projectValueId','value':'$priceService'}, {'id':'$totalMandaysId','value':'0'}, {'id':'$customerNameId','value':'$customerName'}, {'id':'$internalProjectNameId','value':'$internalProjectName'}]");
                    $put_data=json_encode($data);
                    $authorization="Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";

                    $ch=curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $put_data);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));

                    $response=curl_exec($ch);
                    curl_close($ch);
                    print_r($response);

                    $conditionStatus3="project_id = '$projectId'";
                    $updateStatus3=sprintf("`status`= 3");
                    $resStatus3=$DBWR->update_data($tbl_initial_project, $updateStatus3, $conditionStatus3);

                    echo "<br/>";

                    echo "$projectCode - $soNumber - $priceService - [$fullOwnerId] - $projectType - $serviceTypeDescription - $serviceTypeId ($serviceTypeDescription) <br/>";

                    //INSERT LOG MODIFT MAINTENANCE
                    $tbl_sa_log_activity='log_activity';
                    $insertLogCustomFieldsMaintenance=sprintf("(`activity`) VALUES ('Edited customfields Wrike with id project $projectId and code $projectCode - Maintenance $serviceTypeDescription')",
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($projectId, "text"),
                        GetSQLValueString($serviceTypeDescription, "text"));

                    $resLogCustomFieldsMaintenance=$DBWR->insert_data($tbl_sa_log_activity, $insertLogCustomFieldsMaintenance);
                }
            }

            while ($rowProjectCategory=$resProjectCategory->fetch_assoc());
        }

        else if ($status==1) {
            echo "$projectCode belum sampai step Modify Customfield <br/>";
        }

        else {
            echo "$projectCode sudah memiliki CustomField. <br/>";
        }
    }

    while ($rowInitialProject=$resInitialProject->fetch_assoc());
}

function create_approval() {
    global $DBWR;

    $tbl_initial_project="initial_project";
    $tbl_wrike_config="wrike_config";
    $dataApproval=$DBWR->get_data($tbl_initial_project);
    $rowDataApproval=$dataApproval[0];
    $resDataApproval=$dataApproval[1];
    $totalRowDataApproval=$dataApproval[2];

    do {
        $projectId=$rowDataApproval['project_id'];
        $projectCode=$rowDataApproval['project_code'];
        $projectType=$rowDataApproval['project_type'];
        $status=$rowDataApproval['status'];
        //echo "$projectId <br/>";

        $conditionOwner="object = 'Owner' AND condition1 = '$projectType'";
        $dataOwner=$DBWR->get_data($tbl_wrike_config, $conditionOwner);
        $rowOwner=$dataOwner[0];
        $resOwner=$dataOwner[1];

        $fullOwnerId='';

        do {
            $ownerId=$rowOwner['object_id'];
            $fullOwnerId='"'. $ownerId . '", '. $fullOwnerId;
            $fullOwnerId=rtrim($fullOwnerId, ', ');
        }

        while ($rowOwner=$resOwner->fetch_assoc());

        $conditionApprovalDay="object = 'approval' and title ='Approval Maximum Day'";
        $dataApprovalDay=$DBWR->get_data($tbl_wrike_config);
        $day=$dataApprovalDay[0]['condition1'];

        $dueDate=date('Y-m-d', strtotime("+$day  day"));

        if ($status==3) {
            $authorization="Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
            $url="https://www.wrike.com/api/v4/folders/$projectId/approvals"; //?approvers=$ownerId&description=$descApproval&dueDate=$dueApproval";
            //?parent=IEAEOPF5I4U765EL&title=".$projectNameExplode[0]."&titlePrefix=".$projectNameExplode[1]."&copyCustomFields=true&rescheduleMode=Start&rescheduleDate=$date";
            $data=array('approvers'=> "[$fullOwnerId]", 'description'=> "New Project Request $projectType", 'dueDate'=> "$dueDate");
            // $data = array('approvers' => "['KUAL4N7R']", 'description' => "New Project Request $projectType", 'dueDate' => "$dueDate");
            $postdata=json_encode($data);
            $ch=curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
            $result=curl_exec($ch);
            curl_close($ch);
            print_r($result);

            $conditionStatus4="project_id = '$projectId'";
            $updateStatus4=sprintf("`status`= 4");
            $resStatus4=$DBWR->update_data($tbl_initial_project, $updateStatus4, $conditionStatus4);

            echo "<br/>";

            echo "$projectId - [$fullOwnerId] - $projectType - $dueDate - $status <br/>";

            //INSERT LOG CREATE APPROVAL
            $tbl_sa_log_activity='log_activity';
            $insertLogCreateApproval=sprintf("(`activity`) VALUES ('Created approval from $projectId - $projectCode - $projectType to $fullOwnerId')",
                GetSQLValueString($projectCode, "text"),
                GetSQLValueString($projectId, "text"),
                GetSQLValueString($projectType, "text"),
                GetSQLValueString($fullOwnerId, "text"));

            $resLogCreateApproval=$DBWR->insert_data($tbl_sa_log_activity, $insertLogCreateApproval);
        }

        else if ($status==1 || $status==2) {
            echo "$projectCode belum sampai tahapan approval <br/>";
        }

        else {
            echo "$projectCode sudah diberikan approval ke $fullOwnerId <br/>";
        }
    }

    while ($rowDataApproval=$resDataApproval->fetch_assoc());
}

function get_approval() {
    global $DBWR;

    $tbl_initial_project='initial_project';
    $dataGetApproval=$DBWR->get_data($tbl_initial_project, );
    $rowGetApproval=$dataGetApproval[0];
    $resGetApproval=$dataGetApproval[1];
    $totalRowGetApproval=$dataGetApproval[2];

    do {
        $projectId=$rowGetApproval['project_id'];
        $projectCode=$rowGetApproval['project_code'];
        $approvalStatus=$rowGetApproval['approval_status'];
        $projectType=$rowGetApproval['project_type'];
        $status=$rowGetApproval['status'];
        // echo "$projectId <br/>";

        if ($approvalStatus !='Approved'&& $status==4) {
            $authorization="Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
            $curl=curl_init();

            curl_setopt($curl, CURLOPT_URL, "https://www.wrike.com/api/v4/folders/$projectId/approvals");
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $result=curl_exec($curl);
            curl_close($curl);

            $result2=json_decode($result, true);

            $resultApproval=$result2['data'];

            for ($i=0; $i < count($resultApproval); $i++) {
                $statusApproval=$resultApproval[$i]['status'];
                // echo "$projectId - $statusApproval - $projectType <br/>";

                if ($statusApproval=='Approved') {
                    $conditionStatus5="project_id = '$projectId'";
                    $updateStatus5=sprintf("`approval_status` = 'Approved', `status`= 5");
                    $resStatus5=$DBWR->update_data($tbl_initial_project, $updateStatus5, $conditionStatus5);

                    //INSERT GET APPROVAL
                    $tbl_sa_log_activity='log_activity';
                    $insertLogGetApproval=sprintf("(`activity`) VALUES ('$projectId - $projectCode Approved')",
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($projectId, "text"));

                    $resLogGetApproval=$DBWR->insert_data($tbl_sa_log_activity, $insertLogGetApproval);
                }

                else if ($statusApproval=='Rejected') {
                    $conditionStatus5="project_id = '$projectId'";
                    $updateStatus5=sprintf("`approval_status` = '$statusApproval', `status` = 5");
                    $resStatus5=$DBWR->update_data($tbl_initial_project, $updateStatus5, $conditionStatus5);

                    //INSERT GET APPROVAL
                    $tbl_sa_log_activity='log_activity';
                    $insertLogGetApproval=sprintf("(`activity`) VALUES ('$projectId - $projectCode Rejected')",
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($projectId, "text"));

                    $resLogGetApproval=$DBWR->insert_data($tbl_sa_log_activity, $insertLogGetApproval);
                }

                else {
                    $conditionStatus4="project_id = '$projectId'";
                    $updateStatus4=sprintf("`approval_status` = '$statusApproval'");
                    $resStatus4=$DBWR->update_data($tbl_initial_project, $updateStatus4, $conditionStatus4);
                    echo "Project dengan No. KP : $projectCode ($projectType) masih di pending <br/>";

                    //INSERT GET APPROVAL
                    $tbl_sa_log_activity='log_activity';
                    $insertLogGetApproval=sprintf("(`activity`) VALUES ('$projectId - $projectCode Pending')",
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($projectId, "text"));

                    $resLogGetApproval=$DBWR->insert_data($tbl_sa_log_activity, $insertLogGetApproval);
                }
            }
        }

        else if ($approvalStatus=='Approved') {
            echo "Project dengan No. KP : $projectCode telah di Approve <br/>";
        }

        else if ($approvalStatus=='Rejected') {
            echo "Project dengan No. KP : $projectCode memiliki status $approvalStatus <br/>";
        }
    }

    while ($rowGetApproval=$resGetApproval->fetch_assoc());
}

function modify_project_status() {
    global $DBWR;

    $tbl_wrike_config='wrike_config';
    $tbl_initial_project='initial_project';
    $dataInitialProject=$DBWR->get_data($tbl_initial_project);
    $rowInitialProject=$dataInitialProject[0];
    $resInitialProject=$dataInitialProject[1];
    $totalRowInitialProject=$dataInitialProject[2];

    do {
        $projectId=$rowInitialProject['project_id'];
        $projectCode=$rowInitialProject['project_code'];
        $approvalStatus=$rowInitialProject['approval_status'];
        $status=$rowInitialProject['status'];

        $workflowData=$DBWR->get_data($tbl_wrike_config);
        $customstatusId=$workflowData[0]['object_id'];

        if ($status==5 && $approvalStatus=='Approved') {
            //Custom Status Id ke Active Project
            $url="https://www.wrike.com/api/v4/folders/$projectId"; //?project={'ownersAdd':[$fullOwnerId]}&customFields=[{'id':'$pcId', 'value':'$projectCategoryDescription'}]";
            $data=array("project"=> "{'customStatusId':'IEAEOPF5JMCRGWEK'}");
            $put_data=json_encode($data);
            $authorization="Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";

            $ch=curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $put_data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));

            $response=curl_exec($ch);
            curl_close($ch);
            print_r($response);

            echo "$projectId - $approvalStatus <br/>";

            $conditionStatus6="project_id = '$projectId'";
            $updateStatus6=sprintf("`status`= 6");
            $resStatus6=$DBWR->update_data($tbl_initial_project, $updateStatus6, $conditionStatus6);

            //INSERT STATUS
            $tbl_sa_log_activity='log_activity';
            $insertLogStatus=sprintf("(`activity`) VALUES ('$projectId - $projectCode is already Open')",
                GetSQLValueString($projectCode, "text"),
                GetSQLValueString($projectId, "text"));

            $resLogStatus=$DBWR->insert_data($tbl_sa_log_activity, $insertLogStatus);

            echo "$projectCode telah di Open<br/>";
        }

        else if ($status==6) {
            echo "$projectCode sudah selesai <br/>";
        }

        else {
            echo "Data belum/tidak di approve<br/>";
        }
    }

    while ($rowInitialProject=$resInitialProject->fetch_assoc());
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