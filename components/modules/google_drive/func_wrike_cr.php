<?php

function createCRFolder()
{
    global $DBWR, $DBSB;

    $db_service_budget = 'SERVICE_BUDGET';
    $DBSB = get_conn($db_service_budget);

    $db_wrike_integrate = 'wrike_integrate';
    $DBWR = get_conn($db_wrike_integrate);

    $db_change_request = 'change_request';
    $DBCR = get_conn($db_change_request);

    $tbl_general_informations = 'general_informations';

    //GET DATA CHANGE REQUEST
    $queryDataCR = "SELECT a.project_code, a.project_name, a.type_of_service, a.so_number, a.change_request_approval_type, a.request_date, a.cr_no, a.classification, a.change_request_type, b.start_date, b.finish_date
    FROM sa_general_informations AS a
    LEFT JOIN sa_implementation_plans AS b ON a.gi_id = b.ip_id
    WHERE a.classification = 'Major Change'
    AND a.request_date >= '2022-08-01'
    AND a.change_request_approval_type = 'submission_approved'";

    $dataCRFolder = $DBCR->get_sql($queryDataCR);
    $rowDataCRFolder = $dataCRFolder[0];
    $resDataCRFolder = $dataCRFolder[1];
    $totalRowDataCRFolder = $dataCRFolder[2];

    do {
        $projectCode = $rowDataCRFolder['project_code'];
        $noSO = $rowDataCRFolder['so_number'];
        $crNO = $rowDataCRFolder['cr_no'];
        $crType = $rowDataCRFolder['change_request_type'];

        if ($crType == 'Implementation') {
            $crType = 1;
        } else if ($crType == 'Maintenance') {
            $crType = 2;
        }

        //LOOKUP TABLE SERVICE BUDGETS
        //Lookup ke SB jika tidak SO Number
        $queryInternalProjectName = "SELECT a.project_code, a.project_name, a.project_name_internal, a.so_number, b.service_type, a.status FROM sa_trx_project_list AS a
        LEFT JOIN sa_trx_project_implementations AS b ON a.project_id = b.project_id
        WHERE a.project_code = '$projectCode' AND a.so_number = '$noSO' AND b.service_type = $crType";

        $dataInternalProjectName = $DBSB->get_sql($queryInternalProjectName);
        $rowInternalProjectName = $dataInternalProjectName[0];
        $internalProjectName = $rowInternalProjectName['project_name_internal'];

        //LOOKUP TABLE WRIKE PROJECT LIST
        $queryProjectId = "SELECT * FROM sa_wrike_project_list WHERE project_code = '$projectCode' AND no_so LIKE '%$noSO%'";
        $dataProjectId = $DBWR->get_sql($queryProjectId);
        $rowProjectId = $dataProjectId[0];
        $projectId = $rowProjectId['id'];
        $projectTitle = $rowProjectId['title'];

        $titleName = "[$internalProjectName] $crNO";
        $desc = "Change Request Number $crNO";

        //VALIDASI TABLE SA_PROJECT_CR_NO
        $queryProjectCRNo = "SELECT * FROM sa_project_cr_no WHERE cr_no = '$crNO'";
        $dataProjectCRNo = $DBWR->get_sql($queryProjectCRNo);
        $totalRowProjectCRNo = $dataProjectCRNo[2];

        if ($totalRowProjectCRNo > 0) {
            //JIKA CR SUDAH ADA DI SA_PROJECT_CR_NO
            echo "Change Request dengan CR Number $crNO sudah ada di project '$projectTitle'<br/>";
        } else {
            //JIKA CR BELUM ADA DI SA_PROJECT_CR_NO
            $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
            $url = "https://www.wrike.com/api/v4/folders/$projectId/folders";
            $data = array('title' => "$titleName", 'description' => "$desc");
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

            $result = json_decode($result, true);
            $folderId = $result['data'][0]['id'];

            //INSERT KE TABLE SA_PROJECT_CR_NO
            $tbl_project_cr_no = "project_cr_no";
            $insertCRNo = sprintf(
                "(`project_id`, `cr_no`, `folder_id`, `internal_project_name`, `folder_title`, `folder_desc`) VALUES ('$projectId', '$crNO' , '$folderId', '$internalProjectName', '$titleName', '$desc')",
                GetSQLValueString($projectId, "text"),
                GetSQLValueString($crNO, "text"),
                GetSQLValueString($folderId, "text"),
                GetSQLValueString($titleName, "text"),
                GetSQLValueString($desc, "text")
            );

            $resCRNo = $DBWR->insert_data($tbl_project_cr_no, $insertCRNo);
            echo "$projectCode - $noSO - $crType - $internalProjectName - $projectId - $folderId<br/>";
        }
    } while ($rowDataCRFolder = $resDataCRFolder->fetch_assoc());
}

function createCRTask()
{
    global $DBWR, $DBCR;

    $db_change_request = 'change_request';
    $DBCR = get_conn($db_change_request);

    $tbl_project_cr_no = 'project_cr_no';
    $queryProjectCRNo = "SELECT * FROM sa_project_cr_no";
    $dataProjectCRNo = $DBWR->get_sql($queryProjectCRNo);
    $rowProjectCRNo = $dataProjectCRNo[0];
    $resProjectCRNo = $dataProjectCRNo[1];
    $totalRowProjectCRNo = $dataProjectCRNo[2];

    do {
        //LOOPING TABLE SA_PROJECT_CR_NO
        $folderId = $rowProjectCRNo['folder_id'];
        $crNo = $rowProjectCRNo['cr_no'];
        $internalProjectName = $rowProjectCRNo['internal_project_name'];
        $folderStatus = $rowProjectCRNo['folder_status'];

        //LOOKUP TABLE SA_DETAIL_PLANS CHANGE REQUEST
        $queryDetailPlans = "SELECT * FROM sa_detail_plans WHERE cr_no = '$crNo'";
        $dataDetailPlans = $DBCR->get_sql($queryDetailPlans);
        $rowDetailPlans = $dataDetailPlans[0];
        $resDetailPlans = $dataDetailPlans[1];

        if ($folderStatus == 0) {
            do {
                //LOOPING TABLE SA_DETAIL_PLANS CHANGE REQUEST
                $workingDetail = $rowDetailPlans['working_detail'];
                $startTimeDetail = $rowDetailPlans['time'];
                $startTimeDetail = substr($startTimeDetail, 0, 10);
                $finishTimeDetail = $rowDetailPlans['finish_time'];
                $finishTimeDetail = substr($finishTimeDetail, 0, 10);
                $picDetail = $rowDetailPlans['pic'];

                //LOOKUP PIC KE SA_WRIKE_CONTACT_USER
                $queryUser = "SELECT * FROM sa_contact_user WHERE email = '$picDetail'";
                $dataUser = $DBWR->get_sql($queryUser);
                $picId = $dataUser[0]['id'];
                $picIdArray = '["' . $picId . '"]';

                //SETTING TASK TITLE DAN TASK DESCRIPTION 
                $taskTitleDetail = "[$internalProjectName] $crNo $workingDetail";
                $descriptionDetail = "Desc $crNo $workingDetail || PIC : $picDetail";

                // //VALIDASI SA_PROJECT_CR_PLAN
                // $queryCheckCrPlan = "SELECT * FROM sa_project_cr_plan WHERE cr_no = '$crNo'";
                // $dataCheckCrPlan = $DBWR->get_sql($queryCheckCrPlan);
                // $crNoChecking = $dataCheckCrPlan[0]['cr_no'];
                // $folderIdChecking = $dataCheckCrPlan[0]['task_id'];
                // $totalRowTaskId = $dataCheckCrPlan[2];

                // if ($totalRowTaskId > 0) {
                //     echo "TASK $crNoChecking FOLDER ID $folderIdChecking SUDAH ADA !<br/>";
                // } else {
                    //POST TASK
                    $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
                    $url = "https://www.wrike.com/api/v4/folders/$folderId/tasks";
                    if ($picId == '') {
                        //JIKA PIC TIDAK BERHASIL DI LOOKUP
                        $data = array('importance' => "High", 'title' => "$taskTitleDetail", 'description' => "$descriptionDetail", 'dates' => "{'start':'$startTimeDetail','due':'$finishTimeDetail'}");
                    } else {
                        //JIKA PIC BERHASIL DI LOOKUP
                        $data = array('importance' => "High", 'title' => "$taskTitleDetail", 'description' => "$descriptionDetail", 'dates' => "{'start':'$startTimeDetail','due':'$finishTimeDetail'}", 'responsibles' => "$picIdArray");
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
                    print_r($result);

                    $result = json_decode($result, true);
                    $taskId = $result['data'][0]['id'];

                    //INSERT KE TABLE SA_PROJECT_CR_PLAN
                    $tbl_project_cr_plan = "project_cr_plan";
                    $insertCRPlan = sprintf(
                        "(`cr_no`, `task_id`, `task_title`, `task_desc`, `task_start`, `task_finish`, `pic_id`) VALUES ('$crNo', '$taskId' , '$taskTitleDetail', '$descriptionDetail', '$startTimeDetail', '$finishTimeDetail', '$picId')",
                        GetSQLValueString($crNo, "text"),
                        GetSQLValueString($taskId, "text"),
                        GetSQLValueString($descriptionDetail, "text"),
                        GetSQLValueString($startTimeDetail, "text"),
                        GetSQLValueString($finishTimeDetail, "text"),
                        GetSQLValueString($picId, "text")
                    );

                    $resCRPlan = $DBWR->insert_data($tbl_project_cr_plan, $insertCRPlan);

                    echo "<br/>$folderId - $crNo - $internalProjectName - $workingDetail - $picId - $startTimeDetail - $finishTimeDetail<br/>";
                // }
            } while ($rowDetailPlans = $resDetailPlans->fetch_assoc());
        } else {
            //JIKA FLAG SA_PROJECT_CR_NO = 1, MAKA TELAH DIMASUKKAN
            echo "CR Number $crNo telah dimasukkan di WRIKE ! <br/>";
        }
        //UPDATE FLAG DI SA_PROJECT_CR_NO
        $tbl_project_cr_no = "project_cr_no";
        $conditionUpdateCRNo = "cr_no = '$crNo'";
        $updateStatus3 = sprintf("`folder_status`= 1");
        $resStatus3 = $DBWR->update_data($tbl_project_cr_no, $updateStatus3, $conditionUpdateCRNo);
    } while ($rowProjectCRNo = $resProjectCRNo->fetch_assoc());
}
