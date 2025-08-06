<?php

function getSolutionProject()
{
    $db_wr = 'WRIKE_INTEGRATE';
    $DBWR = get_conn($db_wr);

    $db_workload = 'WORKLOAD';
    $DBWRKLD = get_conn($db_workload);
    $tbl_workload_project_solution = "workload_project_solution";

    //Ganti URL dengan : https://www.wrike.com/api/v4/folders/IEAEOPF5I4U6PGE7/folders?project=true&fields=['customFields']&updatedDate={'start':'" . date('Y-m-d', strtotime('-1 days')) . "T00:00:00Z','end':'" . date('Y-m-d', strtotime('-1 days')) . "T23:59:59Z'}
    $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, "https://www.wrike.com/api/v4/folders/IEAEOPF5I452Y2DS");
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    curl_close($curl);

    $result2 = json_decode($result, true);
    $result1 = $result2['data'];

    for ($i = 0; $i < count($result1); $i++) {
        $id = $result1[$i]['id'];
        $customFields = $result1[$i]['customFields'];

        for ($j = 0; $j < count($customFields); $j++) {
            $idCustomFields = $customFields[$j]['id'];
            $valueCustomFields = $customFields[$j]['value'];

            if ($idCustomFields == 'IEAEOPF5JUACAVJN') {
                $projectCode = $valueCustomFields;
            }

            if ($idCustomFields == 'IEAEOPF5JUACAVMK') {
                $projectType = $valueCustomFields;
            }

            if ($idCustomFields == 'IEAEOPF5JUACQU6A') {
                $projectSolutionRaw = $valueCustomFields;
            }
        }

        if ($projectType == 'MSI Project Implementation') {
            $projectSolutionSplit = explode('","', "$projectSolutionRaw");
            for ($k = 0; $k < count($projectSolutionSplit); $k++) {
                $projectSolutionFinal = $projectSolutionSplit[$k];
                $projectSolution1 = str_replace('["', '', "$projectSolutionFinal");
                $projectSolution2 = str_replace('"]', '', "$projectSolution1");

                $dataProjectSolution = explode("] ", "$projectSolution2");
                $projectSolution = $dataProjectSolution[1];
                $categoryTask = str_replace('[', '', "" . $dataProjectSolution[0] . "");

                $sqlLookupPSCode = "SELECT * FROM sa_workload_config WHERE project_solution_name = '$projectSolution'";
                $dataPSCode = $DBWRKLD->get_sql($sqlLookupPSCode);
                $PSCode = $dataPSCode[0]['project_solution_code'];

                $queryPSValidation = "SELECT * FROM sa_workload_project_solution WHERE project_id = '$id' AND project_solution = '$PSCode'";
                $dataPSValidation = $DBWRKLD->get_sql($queryPSValidation);
                $totalRowPSValidation = $dataPSValidation[2];

                if ($totalRowPSValidation > 0) {
                    echo "Function getSolutionProject() : <br/>Data dengan Project Code : $projectCode & Project Solution : $PSCode existing di database.<br/>";
                } else {
                    $tbl_workload_project_solution = 'workload_project_solution';
                    $insertPSWorkload = sprintf(
                        "(`project_id`, `project_code`, `project_solution`, `category`) VALUES ('$id', '$projectCode', '$PSCode', '$categoryTask')",
                        GetSQLValueString($id, "text"),
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($PSCode, "text"),
                        GetSQLValueString($categoryTask, "text")
                    );

                    $resPSWorkload = $DBWRKLD->insert_data($tbl_workload_project_solution, $insertPSWorkload);
                }
                echo "$id<br/>$PSCode<br/>$projectCode<br/>$projectSolution<br/>$categoryTask<br/><br/>";
            }
        } else {
            echo "Project bukan Implementation !<br/>";
        }
    }
}

function getProjectTask()
{
    $db_wr = 'WRIKE_INTEGRATE';
    $DBWR = get_conn($db_wr);

    $db_workload = 'WORKLOAD';
    $DBWRKLD = get_conn($db_workload);

    $queryWorkloadPS = "SELECT * FROM sa_workload_project_solution WHERE flag_get_task = 0";
    $dataWorkloadPS = $DBWRKLD->get_sql($queryWorkloadPS);
    $rowWorkloadPS = $dataWorkloadPS[0];
    $resWorkloadPS = $dataWorkloadPS[1];

    do {
        $projectId = $rowWorkloadPS['project_id'];
        $projectCode = $rowWorkloadPS['project_code'];
        $flagTask = $rowWorkloadPS['flag_get_task'];

        if ($flagTask == 0) {
            $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, "https://www.wrike.com/api/v4/folders/$projectId/tasks?subTasks=true&fields=['customFields','superTaskIds']");
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($curl);
            curl_close($curl);

            $result2 = json_decode($result, true);
            $result1 = $result2['data'];
            if (is_countable($result1) && count($result1) > 0){
                for ($i = 0; $i < count($result1); $i++) {
                    $taskId = $result1[$i]['id'];
                    $taskName = $result1[$i]['title'];
                    $createdDate = $result1[$i]['createdDate'];
                    $updatedDate = $result1[$i]['updatedDate'];
                    $permalink = $result1[$i]['permalink'];
                    $superTaskIds = $result1[$i]['superTaskIds'][0];
    
                    if($superTaskIds == ''){
                        $superTaskIds = "";
                    }
    
                    echo "$superTaskIds<br/>";
    
                    $cfTask = $result1[$i]['customFields'];
                    
                    for($z = 0 ; $z<count($cfTask) ; $z++){
                        // $catalogueCode = '';
                        // $taskCategory = '';
                        
                        $valueCFTask = $cfTask[$z]['value'];
                        $idCFTask = $cfTask[$z]['id'];
                    
                        echo "$idCFTask<br/>";
                        if ($idCFTask == 'IEAEOPF5JUACAVIQ') {
                            $taskCategory = $valueCFTask;
                        }
    
                        echo "$idCFTask<br/>";
                        if ($idCFTask == 'IEAEOPF5JUADD5BX') {
                            $catalogueCode = $valueCFTask;
                        }
                    
                    }
    
                    $tbl_workload_task_list = 'wrike_task';
    
                    $queryTaskValidation = "SELECT * FROM sa_wrike_task WHERE task_id='$taskId'";
                    $dataTaskValidation = $DBWR->get_sql($queryTaskValidation);
                    $totalRowTaskValidation = $dataTaskValidation[2];
    
                    if ($totalRowTaskValidation > 0) {
                        $condition = "task_id = '$taskId'";
                        $updateTaskList = sprintf(
                            "`task_id`='$taskId', `parent_id`='$superTaskIds', `project_id`='$projectId', `task_name`='$taskName', `catalogue_code` = '$catalogueCode', `project_code` = '$projectCode', `task_category` = '$taskCategory', `created_date`='$createdDate', `updated_date`='$updatedDate', `permalink`='$permalink'",
                            GetSQLValueString($taskId, "text"),
                            GetSQLValueString($projectId, "text"),
                            GetSQLValueString($taskName, "text"),
                            GetSQLValueString($createdDate, "text"),
                            GetSQLValueString($updatedDate, "text"),
                            GetSQLValueString($permalink, "text")
                        );
                        $resTaskList = $DBWR->update_data($tbl_workload_task_list, $updateTaskList, $condition);
    
                        echo "Updated $taskId - $projectId - $taskName - $createdDate - $updatedDate - $permalink <br/>";
                    } else {
                        $insertTaskWorkload = sprintf(
                            "(`task_id`, `parent_id`, `project_id`, `task_name`, `catalogue_code`, `project_code`, `task_category`, `created_date`, `updated_date`, `permalink`) VALUES ('$taskId', '$superTaskIds', '$projectId', '$taskName', '$catalogueCode', '$projectCode', '$taskCategory', '$createdDate', '$updatedDate', '$permalink')",
                            GetSQLValueString($taskId, "text"),
                            GetSQLValueString($projectId, "text"),
                            GetSQLValueString($taskName, "text"),
                            GetSQLValueString($createdDate, "text"),
                            GetSQLValueString($updatedDate, "text"),
                            GetSQLValueString($permalink, "text")
                        );
                        $resPSWorkload = $DBWR->insert_data($tbl_workload_task_list, $insertTaskWorkload);
    
                        echo "Inserted $taskId - $projectId - $taskName - $createdDate - $updatedDate - $permalink <br/>";
                    }
                }

                $condition = "project_id = '$projectId'";
            $tbl_workload_project_solution = 'workload_project_solution';
            $updateFlagGetTask = sprintf(
                "`flag_get_task`= 1"
            );

            $res1 = $DBWRKLD->update_data($tbl_workload_project_solution, $updateFlagGetTask, $condition);
            }
        } else {
            echo "$projectCode telah dilakukan get task ! <br/>";
        }
    } while ($rowWorkloadPS = $resWorkloadPS->fetch_assoc());
}

function pushWorkload()
{
    $db_service_budget = 'SERVICE_BUDGET';
    $DBSB = get_conn($db_service_budget);

    $db_wr = 'WRIKE_INTEGRATE';
    $DBWR = get_conn($db_wr);

    $db_workload = 'WORKLOAD';
    $DBWRKLD = get_conn($db_workload);

    $sqlGetProject = "SELECT * FROM sa_workload_project_solution WHERE flag = 0";
    $dataGetProject = $DBWRKLD->get_sql($sqlGetProject);
    $rowGetProject = $dataGetProject[0];
    $resGetProject = $dataGetProject[1];
    $totalRowGetProject = $dataGetProject[2];

    if($totalRowGetProject < 1){
        echo "Tidak ada task yang di push <br/>";
    }else{
        do {
            $flag = $rowGetProject['flag'];
            $projectCode = $rowGetProject['project_code'];
            $projectId = $rowGetProject['project_id'];
            $projectSolution = $rowGetProject['project_solution'];
            $categoryTask = $rowGetProject['category'];
    
            //Get Project Internal Name
            $sqlGetPIN = "SELECT * FROM sa_trx_project_list WHERE project_code = '$projectCode'";
            $dataGetPIN = $DBSB->get_sql($sqlGetPIN);
            $internalProjectName = $dataGetPIN[0]['project_name_internal'];
    
            //Get Workload Task Type
            $sqlTaskType = "SELECT CASE
                                    WHEN LEFT(catalogue_code,3) = 'I.P' THEN REPLACE(catalogue_code, 'I.P', '2')
                                    WHEN LEFT(catalogue_code,3) = 'I.E' THEN REPLACE(catalogue_code, 'I.E', '3')
                                    END CatalogueKey,
                                    CASE
                                    WHEN LEFT(summary_catalogue,3) = 'I.P' THEN REPLACE(summary_catalogue, 'I.P', '2')
                                    WHEN LEFT(summary_catalogue,3) = 'I.E' THEN REPLACE(summary_catalogue, 'I.E', '3')
                                    END CatalogueKeySummary,
                                    is_summary,
                                    catalogue_code,
                                    task_name
                                FROM sa_catalogue_task
                                WHERE catalogue_code LIKE '%$projectSolution%'
                                ORDER BY CatalogueKey";
    
            $dataTaskType = $DBWRKLD->get_sql($sqlTaskType);
            $rowTaskType = $dataTaskType[0];
            $resTaskType = $dataTaskType[1];
    
            do {
                $catalogueKey = $rowTaskType['CatalogueKey'];
                $catalogueKeySummary = $rowTaskType['CatalogueKeySummary'];
                $catalogueCode = $rowTaskType['catalogue_code'];
                $taskName = $rowTaskType['task_name'];
                $isSummary = $rowTaskType['is_summary'];
    
                $workloadTaskName = "[$internalProjectName] $catalogueKey $taskName";
                $catalogueName = "$catalogueCode - $taskName";
    
                $queryTaskList = "SELECT * FROM sa_wrike_task WHERE task_name LIKE '%[$internalProjectName] $catalogueKeySummary%' LIMIT 1";
                $queryTaskList = "SELECT * FROM sa_wrike_task WHERE task_name LIKE '%[$internalProjectName] $catalogueKeySummary%' LIMIT 1";
                
                $dataTaskList = $DBWR->get_sql($queryTaskList);
                $parentTask = $dataTaskList[0]['task_id'];
    
                $queryDurationTask = "SELECT * FROM sa_catalogue_task_detail WHERE catalogue_code = '$catalogueCode' AND category_service_type = '$categoryTask'";
                $dataDurationTask = $DBWRKLD->get_sql($queryDurationTask);
                $durationTask = $dataDurationTask[0]['duration'];
    
                $dateNow = date("Y-m-d");
                $date = strtotime($dateNow);
                $date = strtotime("+$durationTask day", $date);
                $date = date('Y-m-d', $date);
    
                //POST TASK
                $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
                $url = "https://www.wrike.com/api/v4/folders/$projectId/tasks";
    
    
                if ($isSummary == 1) {
                    $data = array('superTasks' => "['$parentTask']", 'title' => "$workloadTaskName", 'description' => "WORKLOAD TASK TESTING", 'status' => "Active", 'customFields' => "[{'id':'IEAEOPF5JUADD5BX','value':'$catalogueName'}, {'id':'IEAEOPF5JUACAVIQ','value':'$categoryTask'}]");
                } else {
                    $data = array('superTasks' => "['$parentTask']", 'title' => "$workloadTaskName", 'description' => "WORKLOAD TASK TESTING", 'status' => "Active", 'customFields' => "[{'id':'IEAEOPF5JUADD5BX','value':'$catalogueName'}, {'id':'IEAEOPF5JUACAVIQ','value':'$categoryTask'}]", 'dates' => "{'start':'$dateNow','due':'$date'}");
                }
    
                $postdata = json_encode($data);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                $result = curl_exec($ch);
                curl_close($ch);
                echo "Function pushWorkload() :<br/>";
                print_r($result);
    
                $result = json_decode($result, true);
                $taskIdCreate = $result['data'][0]['id'];
                $taskTitleCreate = $result['data'][0]['title'];
                $parentsIdCreate = $result['data'][0]['parentIds'][0];
                $createdDateCreate = $result['data'][0]['createdDate'];
                $updatedDateCreate = $result['data'][0]['updatedDate'];
                $permalinkCreate = $result['data'][0]['permalink'];
    
                $tbl_workload_task_list = "wrike_task";
                $insertTaskWorkload = sprintf(
                    "(`task_id`, `parent_id`, `project_id`, `task_name`, `catalogue_code`, `project_code`, `task_category`, `created_date`, `updated_date`, `permalink`) VALUES ('$taskIdCreate', '$parentTask' , '$projectId', '$taskTitleCreate', '$catalogueName', '$projectCode', '$categoryTask', '$createdDateCreate', '$updatedDateCreate', '$permalinkCreate')",
                    GetSQLValueString($taskIdCreate, "text"),
                    GetSQLValueString($parentTask, "text"),
                    GetSQLValueString($projectId, "text"),
                    GetSQLValueString($taskTitleCreate, "text"),
                    GetSQLValueString($createdDateCreate, "text"),
                    GetSQLValueString($updatedDateCreate, "text"),
                    GetSQLValueString($permalinkCreate, "text")
                );
                $resTaskWorkload = $DBWR->insert_data($tbl_workload_task_list, $insertTaskWorkload);
    
                echo "$workloadTaskName - $catalogueCode - $categoryTask - $dateNow - $date<br/>";
    
    
                echo "Task dengan ID : $taskIdCreate sudah di PUSH ke WRIKE<br/><br/>";
            } while ($rowTaskType = $resTaskType->fetch_assoc());
    
            $tbl_workload_project_solution = "workload_project_solution";
            $conditionProjectSolution = "project_id = '$projectId'";
            $updateStatusFlagPS = sprintf("`flag`= 1");
            $resStatusFlag = $DBWRKLD->update_data($tbl_workload_project_solution, $updateStatusFlagPS, $conditionProjectSolution);

        } while ($rowGetProject = $resGetProject->fetch_assoc());
    }
}

function getJobRoles(){
    $db_workload = 'WORKLOAD';
    $DBWRKLD = get_conn($db_workload); 
    $db_wrike = 'WRIKE_INTEGRATE';
    $DBWR = get_conn($db_wrike); 

    $sqlCheckFlag = "SELECT * FROM sa_initial_jobroles WHERE flag = 0";
    $dataCheckFlag = $DBWRKLD->get_sql($sqlCheckFlag);
    $rowData = $dataCheckFlag[0];
    $resData = $dataCheckFlag[1];

    do{
        $taskId = $rowData['task_id'];
        $projectId = $rowData['project_id'];
        $folderId = $rowData['parent_id'];

        $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://www.wrike.com/api/v4/tasks/$taskId");
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);

        $result2 = json_decode($result, true);
        $rolesName = $result2['data'][0]['title'];
        $cfResponsibleIds = $result2['data'][0]['responsibleIds'];

        for($i=0;$i<count($cfResponsibleIds);$i++){
            $rolesId = $cfResponsibleIds[$i];

            if($rolesId != ''){
                $sqlLookupRolesName = "SELECT * FROM sa_contact_user WHERE id = '$rolesId'";
                $dataRolesUserName = $DBWR->get_sql($sqlLookupRolesName);
                $rolesUserEmail = $dataRolesUserName[0]['email'];
                
                $tbl_initial_jobroles = 'initial_jobroles';
                $tbl_resource_jobroles = 'resource_jobroles';
                $queryCheckRoles = "SELECT * FROM sa_resource_jobroles WHERE project_id = '$projectId' AND folder_id = 'SERVICE BUDGET' AND resource_id = '$rolesId'";
                $dataCheckRoles = $DBWRKLD->get_sql($queryCheckRoles);
                $totalRowRoles = $dataCheckRoles[2];
    
                if($totalRowRoles > 0){
                    $conditionUpdateRoles = "project_id = '$projectId' AND folder_id = 'SERVICE BUDGET' AND resource_id = '$rolesId'";
                    //Jika ada update tulis disini
                    $updateRoles = sprintf(
                        "`project_id`= '$projectId', `folder_id` = '$folderId', `task_id` = '$taskId', `resource_id` = '$rolesId', `resource_email` = '$rolesUserEmail', `job_roles` = '$rolesName'",
                        GetSQLValueString($projectId, "text"),
                        GetSQLValueString($taskId, "text"),
                        GetSQLValueString($rolesId, "text"),
                        GetSQLValueString($rolesUserEmail, "text"),
                        GetSQLValueString($rolesName, "text")
                    );
                    $resUpdateRoles = $DBWRKLD->update_data($tbl_resource_jobroles, $updateRoles, $conditionUpdateRoles);

                }else{
                    //Data baru di insert ke database
                $insertJobRoles = sprintf(
                    "(`project_id`, `folder_id`, `task_id`, `resource_id`, `resource_email`, `job_roles`) VALUES ('$projectId', '$folderId', '$taskId', '$rolesId', '$rolesUserEmail', '$rolesName')",
                    GetSQLValueString($projectId, "text"),
                    GetSQLValueString($taskId, "text"),
                    GetSQLValueString($rolesId, "text"),
                    GetSQLValueString($rolesUserEmail, "text"),
                    GetSQLValueString($rolesName, "text")
                );
                $resInsertJobRoles = $DBWRKLD->insert_data($tbl_resource_jobroles, $insertJobRoles);
            }

                $conditionJobRoles = "task_id = '$taskId'";
                //Jika ada update tulis disini
                $updateFlagRoles = sprintf(
                    "`flag`= 1",
                );
                $resUpdateFlagRoles = $DBWRKLD->update_data($tbl_initial_jobroles, $updateFlagRoles, $conditionJobRoles);

                echo "($rolesId - $rolesUserEmail) $rolesName<br/>";
            }
        }
    }while($rowData = $resData->fetch_assoc());
}

function getJobRoles2(){
    $db_workload = 'WORKLOAD';
    $DBWRKLD = get_conn($db_workload); 
    $db_wrike = 'WRIKE_INTEGRATE';
    $DBWR = get_conn($db_wrike); 

    //Ganti URL dengan : https://www.wrike.com/api/v4/folders/IEAEOPF5I4U6PGE7/folders?project=true&fields=['customFields']&updatedDate={'start':'" . date('Y-m-d', strtotime('-1 days')) . "T00:00:00Z','end':'" . date('Y-m-d', strtotime('-1 days')) . "T23:59:59Z'}
    $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
    $curl = curl_init();
    //Project Id Harus Di Ganti Dengan Trigger Yang Ada
    curl_setopt($curl, CURLOPT_URL, "https://www.wrike.com/api/v4/folders/IEAEOPF5I452Y2DS");
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    curl_close($curl);

    $result2 = json_decode($result, true);
    $result1 = $result2['data'];

    for($i = 0 ; $i < count($result1) ; $i++){
        $projectId = $result1[$i]['id'];
        $childIdsArray = $result1[$i]['childIds'];

        if (is_countable($childIdsArray) && count($childIdsArray) > 0){
            for ($j=0; $j < count($childIdsArray); $j++) {
                $jobRoleFolderId=$childIdsArray[$j];
                $authorization="Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
                $curl=curl_init();
                curl_setopt($curl, CURLOPT_URL, "https://www.wrike.com/api/v4/folders/$jobRoleFolderId");
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                $result1=curl_exec($curl);
                curl_close($curl);
                $result1=json_decode($result1, true);
    
                //GET Customer Name
                $jobrolesName=$result1['data'][$j]['title'];
    
                if (strpos($jobrolesName, 'Job Role') !==false) {
                    $jobrolesChildId=$result1['data'][$j]['childIds'];
    
                    for($i1=0; $i1 < count($jobrolesChildId); $i1++) {
                        $jobrolesChildId1=$jobrolesChildId[$i1];
    
                        $authorization="Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
                        $curl=curl_init();
                        curl_setopt($curl, CURLOPT_URL, "https://www.wrike.com/api/v4/folders/$jobrolesChildId1");
                        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                        $result2=curl_exec($curl);
                        curl_close($curl);
                        $result2=json_decode($result2, true);
    
                        //GET Customer Name
                        $prName=$result2['data'][0]['title'];
    
                        if (strpos($prName, 'Project Role') !==false) {
                            $prFolderId=$result2['data'][0]['id'];
                            
                            $authorization="Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
                            $curl=curl_init();
                            curl_setopt($curl, CURLOPT_URL, "https://www.wrike.com/api/v4/folders/$prFolderId/tasks?subTasks=true&fields=['responsibleIds']");
                            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                            $result3=curl_exec($curl);
                            curl_close($curl);
                            $result3=json_decode($result3, true);
    
                            echo "$prFolderId<br/>";
    
                            for($i2 = 0 ; $i2 < count($result3); $i2++ ){
                                $prId=$result3['data'][$i2]['id'];
                                $prJobRolesName = $result3['data'][$i2]['title'];
                                $prResponsibleIds = $result3['data'][$i2]['responsibleIds'];
                                
                                for($i3 = 0 ; $i3 < count($prResponsibleIds) ; $i3++){
                                    $prResponsibleId = $prResponsibleIds[$i3];
    
                                    $queryLookupUser = "SELECT * FROM sa_contact_user WHERE id = '$prResponsibleId'";
                                    $dataUser = $DBWR->get_sql($queryLookupUser);
                                    $resourceEmail = $dataUser[0]['email'];
    
                                    $tbl_resource_jobroles = 'resource_jobroles';
                                    $queryCheckRoles = "SELECT * FROM sa_resource_jobroles WHERE project_id = '$projectId' AND folder_id = 'PROJECT ROLE' AND resource_id = '$prResponsibleId'";
                                    $dataCheckRoles = $DBWRKLD->get_sql($queryCheckRoles);
                                    $totalRowRoles = $dataCheckRoles[2];
    
                                    if($totalRowRoles > 0){
                                        $conditionUpdateRoles = "project_id = '$projectId' AND folder_id = 'PROJECT ROLE' AND resource_id = '$prResponsibleId'";
                                        //Jika ada update tulis disini
                                        $updateRoles = sprintf(
                                            "`project_id`= '$projectId', `folder_id` = 'PROJECT ROLE', `task_id` = '$prId', `resource_id` = '$prResponsibleId', `resource_email` = '$resourceEmail', `job_roles` = '$prJobRolesName'",
                                            GetSQLValueString($projectId, "text"),
                                            GetSQLValueString($prId, "text"),
                                            GetSQLValueString($prResponsibleId, "text"),
                                            GetSQLValueString($resourceEmail, "text"),
                                            GetSQLValueString($prJobRolesName, "text")
                                        );
                                        $resUpdateRoles = $DBWRKLD->update_data($tbl_resource_jobroles, $updateRoles, $conditionUpdateRoles);
                                    }else{
                                        $insertJobRoles = sprintf(
                                            "(`project_id`, `folder_id`, `task_id`, `resource_id`, `resource_email`, `job_roles`) VALUES ('$projectId', 'PROJECT ROLE', '$prId', '$prResponsibleId', '$resourceEmail', '$prJobRolesName')",
                                            GetSQLValueString($projectId, "text"),
                                            GetSQLValueString($prId, "text"),
                                            GetSQLValueString($prResponsibleId, "text"),
                                            GetSQLValueString($resourceEmail, "text"),
                                            GetSQLValueString($prJobRolesName, "text")
                                        );
                                    $resInsertJobRoles = $DBWRKLD->insert_data($tbl_resource_jobroles, $insertJobRoles);
                                        
                                    echo "$projectId - JOB ROLES - $prId - $prResponsibleId - $resourceEmail - $prJobRolesName<br/>";
                                    }
                                    //Data baru di insert ke database
                                    
                                }
    
                                
                            }   
                            
                        }
                    }
                }
            }
        }
    }
}

function updateDate(){
    $db_wrike = 'WRIKE_INTEGRATE';
    $DBWR = get_conn($db_wrike); 

    $db_workload = 'WORKLOAD';
    $DBWRKLD = get_conn($db_workload);

    $sqlAllProject = "SELECT * FROM sa_wrike_project_list";
    $dataAllProject = $DBWR->get_sql($sqlAllProject);
    $rowAllProject = $dataAllProject[0];
    $resAllProject = $dataAllProject[1];

    do{
        $projectId = $rowAllProject['id'];

        $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
        $curl = curl_init();
        //Project Id Harus Di Ganti Dengan Trigger Yang Ada
        curl_setopt($curl, CURLOPT_URL, "https://www.wrike.com/api/v4/folders/$projectId/tasks?subTasks=true&fields=['responsibleIds']");
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);

        $result2 = json_decode($result, true);
        $result1 = $result2['data'];

    for($i = 0 ; $i < count($result1) ; $i++){
        $completeDate = '';
        $duration = '';
        $start = '';
        $due = '';
        $idTask = $result1[$i]['id'];
        $title = $result1[$i]['title'];
        $duration = $result1[$i]['dates']['duration'];
        $start = $result1[$i]['dates']['start'];
        $due = $result1[$i]['dates']['due'];
        $completeDate = $result1[$i]['completedDate'];

        $condition = "task_id = '$idTask'";
        $tbl_workload_task_list = 'wrike_task';

        if($duration == NULL){
            $duration = 0;
        }
        if($start == NULL){
            $start = '';
        }
        if($due == NULL){
            $due = '';
        }
        if($completeDate == NULL){
            $completeDate = '';
        }

        echo "$title - $duration - $start - $due - $completeDate<br/>";

        $updateTaskList = sprintf(
            "`duration`= '$duration', `start_date`='$start', `due_date`='$due', `completed_date`='$completeDate'",
            GetSQLValueString($duration, "text"),
            GetSQLValueString($start, "text"),
            GetSQLValueString($due, "text"),
            GetSQLValueString($completeDate, "text")
        );

        $resTaskList = $DBWR->update_data($tbl_workload_task_list, $updateTaskList, $condition);
        
        if($resTaskList){
            
        }
    }

    }while($rowAllProject = $resAllProject->fetch_assoc());

    
}

function forwardAssignment(){
    $db_wrike = 'WRIKE_INTEGRATE';
    $DBWR = get_conn($db_wrike); 

    $db_workload = 'WORKLOAD';
    $DBWRKLD = get_conn($db_workload); 

    $db_sb = 'SERVICE_BUDGET';
    $DBSB = get_conn($db_sb); 

    $sqlResourceAssignment = "SELECT * FROM sa_resource_assignment WHERE status = 'Active' OR status = 'Joined' AND flag_assign_wrike = 0";
    $dataResource = $DBWR->get_sql($sqlResourceAssignment);
    $rowDataResource = $dataResource[0];
    $resDataResource = $dataResource[1];

    

    if($rowDataResource != NULL){
        do{
            $id = $rowDataResource['id'];
            $projectCode = $rowDataResource['project_code'];
            $resourceEmail = $rowDataResource['resource_email'];
            $resourceRoles = $rowDataResource['roles'];

	    $explodeResourceEmail = explode("<", $resourceEmail);
	    $finalResourceEmail = str_replace(">","",$explodeResourceEmail[1]);

            $explodeResourceRoles = explode(" - ", $resourceRoles);
    
            $sqlLookupRolesId = "SELECT * FROM sa_mst_resource_catalogs WHERE resource_qualification = '".$explodeResourceRoles[0]."'";
            $dataLookupRolesId = $DBSB->get_sql($sqlLookupRolesId);
            $rowRolesId = $dataLookupRolesId[0];
    
            //Digunakan
            $idRoles = $rowRolesId['resource_catalog_id'];
    
            //Lookup Initial JobRoles
            $sqlLookupIJ = "SELECT * FROM sa_initial_jobroles WHERE project_code = '$projectCode' AND resource_category_id = $idRoles AND brand = '".$explodeResourceRoles[1]."'";
            $dataLookupIJ = $DBWRKLD->get_sql($sqlLookupIJ);
            $rowLookupIJ = $dataLookupIJ[0];
            $taskId = $rowLookupIJ['task_id'];
    
            //Lookup Wrike ID From Email
            $sqlWrikeId = "SELECT * FROM sa_contact_user WHERE email = '$finalResourceEmail '";
            $dataWrikeId = $DBWR->get_sql($sqlWrikeId);
            $rowWrikeId = $dataWrikeId[0];
            
            if($rowWrikeId == NULL){
                $resourceWrikeId = "";
            }else{
                $resourceWrikeId = $rowWrikeId['id'];
            }

            if($resourceWrikeId != ''){
                //PUT ASSIGNMENT TASK
                $data=array("addResponsibles"=> "['$resourceWrikeId']");
                $put_data=json_encode($data);
                $authorization="Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
                $ch=curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://www.wrike.com/api/v4/tasks/$taskId");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $put_data);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                $response=curl_exec($ch);
                curl_close($ch);
                print_r($response);

                $now = date('Y-m-d H:i:s');
            
                if($explodeResourceRoles[1] == ''){
                    $resourceRoles = $explodeResourceRoles[0];
                }
    
                echo "$projectCode - $resourceEmail - $resourceRoles - $idRoles - $taskId - $resourceWrikeId<br/>";
    
                $conditionJobRoles = "id = '$id'";
                $updateFlag = sprintf(
                    "`flag_assign_wrike`= 1, `timestamp_wrike` = '".date("Y-m-d H:i:s")."'"
                );
                $resUpdateFlag = $DBWR->update_data("resource_assignment", $updateFlag, $conditionJobRoles);
            }
            
        }while($rowDataResource = $resDataResource->fetch_assoc());
    }else{
        echo "Semua data telah di push/tidak ada!<br/>";
    }
}

function checkStatusAssignment(){
    $db_wrike = 'WRIKE_INTEGRATE';
    $DBWR = get_conn($db_wrike); 

    $sqlCheckTable = "SELECT * FROM sa_wrike_project_detail";
    $dataTable = $DBWR->get_sql($sqlCheckTable);
    $rowTable = $dataTable[0];
    $resTable = $dataTable[1];

    do{
        $projectCode = $rowTable['project_code'];
        $documentBastDate = $rowTable['document_bast_date'];

        echo $projectCode . " - " . $documentBastDate . "<br/>";

        $conditionStatus = "project_code = '$projectCode'";

        if($documentBastDate != '' || $documentBastDate != NULL){
            $updateStatusResource = sprintf(
                "`status`= 'Non Active'"
            );
    
            $resStatusResource = $DBWR->update_data("resource_assignment", $updateStatusResource, $conditionStatus);
        }
    }while($rowTable = $resTable->fetch_assoc());
}