<?php

error_reporting(E_ERROR | E_PARSE);
require 'google_drive.php';

function get_contact()
{
    $db_wr = "WRIKE_INTEGRATE";
    $DBWR = get_conn($db_wr);
    $tbl_contact = 'contact_user';
    $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, 'https://www.wrike.com/api/v4/contacts');
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    curl_close($curl);

    $result2 = json_decode($result, true);

    //GET Customer Name
    $result1 = $result2['data'];

    for ($i = 0; $i < count($result1); $i++) {
        $author_id = $result1[$i]['id'];
        $first_name = $result1[$i]['firstName'];
        $last_name = $result1[$i]['lastName'];
        $email = $result1[$i]['profiles'][0]['email'];
        $role = $result1[$i]['profiles'][0]['role'];
        $type = $result1[$i]['type'];
        $deleted = $result1[$i]['deleted'];

        //Jika comment = time entry, maka jalankan function
        if ($deleted == false) {
            if (strpos($type, 'Person') !== false) {
                $condition = "id = '$author_id'";
                $testdata = $DBWR->get_data($tbl_contact, $condition);
                if ($testdata[2] > 0) {
                    //Query Update
                    $update = sprintf(
                        "`name`='$first_name $last_name', `email`='$email', `role`='$role'",
                        GetSQLValueString($first_name, "text"),
                        GetSQLValueString($last_name, "text"),
                        GetSQLValueString($email, "text"),
                        GetSQLValueString($role, "text")
                    );

                    $res1 = $DBWR->update_data($tbl_contact, $update, $condition);
                } else {
                    //Query Insert
                    $insert = sprintf(
                        "(`id`,`name`,`email`, `role`) VALUES ('$author_id', '$first_name $last_name', '$email', '$role')",
                        GetSQLValueString($author_id, "text"),
                        GetSQLValueString($first_name, "text"),
                        GetSQLValueString($last_name, "text"),
                        GetSQLValueString($email, "text"),
                        GetSQLValueString($role, "text")
                    );
                    $res = $DBWR->insert_data($tbl_contact, $insert);
                }
            }
        }
    }
}

function get_project()
{
    $db_wr = "WRIKE_INTEGRATE";
    $DBWR = get_conn($db_wr);
    $tbl_wrike_project_list = 'wrike_project_list';
    $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, "https://www.wrike.com/api/v4/folders/IEAEOPF5I4U6PGE7/folders?project=true&fields=['customFields']");
    //&updatedDate={'start':'" . date('Y-m-d', strtotime('-1 days')) . "T00:00:00Z','end':'" . date('Y-m-d', strtotime('-1 days')) . "T23:59:59Z'}
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    curl_close($curl);

    $result2 = json_decode($result, true);

    //GET Customer Name
    $result1 = $result2['data'];

    for ($i = 0; $i < count($result1); $i++) {
        $id = $result1[$i]['id'];
        $title = $result1[$i]['title'];
        $createdDate = $result1[$i]['createdDate'];
        $permalink = $result1[$i]['permalink'];
        $ownerId = $result1[$i]['project']['ownerIds'];
        $updatedDate = $result1[$i]['updatedDate'];
        $cf = $result1[$i]['customFields'];

        for ($j = 0; $j < count($cf); $j++) {
            $projectId = $cf[$j]["id"];
            $projectCode = $cf[$j]["value"];

            if ($projectId == 'IEAEOPF5JUACAY7C') {
                $noSO = $projectCode;
            }

            if (strpos($projectId, 'IEAEOPF5JUACAVJN') !== false) {
                try {
                    echo "$id - $projectCode - $title - $noSO <br/>";
                    $condition = "id = '$id'";
                    $testdata = $DBWR->get_data($tbl_wrike_project_list, $condition);
                    if ($testdata[2] > 0) {
                        //Query Update
                        $update = sprintf(
                            "`title`='$title', `created_date`='$createdDate', `permalink`='$permalink', `project_code`='$projectCode', `no_so` = '$noSO'",
                            GetSQLValueString($title, "text"),
                            GetSQLValueString($createdDate, "text"),
                            GetSQLValueString($permalink, "text"),
                            GetSQLValueString($projectCode, "text"),
                            GetSQLValueString($noSO, "text")
                        );

                        $res1 = $DBWR->update_data($tbl_wrike_project_list, $update, $condition);
                    } else {
                        //Query Insert
                        echo "1";
                        $insert = sprintf(
                            "(`id`,`title`,`created_date`, `permalink`, `project_code`, `no_so`) VALUES ('$id', '$title', '$createdDate', '$permalink', '$projectCode', '$noSO')",
                            GetSQLValueString($id, "text"),
                            GetSQLValueString($title, "text"),
                            GetSQLValueString($createdDate, "text"),
                            GetSQLValueString($permalink, "text"),
                            GetSQLValueString($projectCode, "text"),
                            GetSQLValueString($noSO, "text")
                        );

                        $res = $DBWR->insert_data($tbl_wrike_project_list, $insert);
                    }
                } catch (Exception $e) {
                    echo 'Message: ' . $e->getMessage();
                }
            }
        }

        for ($j = 0; $j < count($cf); $j++) {
            $projectTypeId = $cf[$j]["id"];
            $projectTypeValue = $cf[$j]["value"];

            if (strpos($projectTypeId, 'IEAEOPF5JUACAVMK') !== false) {
                $condition_type = "id = '$id'";
                $testdata_type = $DBWR->get_data($tbl_wrike_project_list, $condition_type);
                if ($testdata_type[2] > 0) {
                    //Query Update
                    $updateProjectType = sprintf(
                        "`project_type`='$projectTypeValue'",
                        GetSQLValueString($projectTypeValue, "text")
                    );

                    $res1ProjectType = $DBWR->update_data($tbl_wrike_project_list, $updateProjectType, $condition_type);
                }
            }
        }

        $countOwnerId = count($ownerId);
        $email_id = '';
        $owner_id = '';

        for ($k = 0; $k < count($ownerId); $k++) {
            $tblContactUser = "contact_user";
            $conditionContact = "id = '$ownerId[$k]'";
            $dataContact = $DBWR->get_data($tblContactUser, $conditionContact);

            $dataContactRow = $dataContact[0];
            $dataContactRes = $dataContact[1];
            $dataContactRTotalRow = $dataContact[2];

            $contactId = $dataContactRow['email'];

            if ($k == 0) {
                $owner_id = $ownerId[$k];
                $email_id = $contactId;
            } else {
                $owner_id = $owner_id . ', ' . $ownerId[$k];
                $email_id = $email_id . ', ' . $contactId;
                $email_id = rtrim($email_id, ',');
                $owner_id = rtrim($owner_id, ',');
            }

            $condition = "id = '$id'";
            $testdata1 = $DBWR->get_data($tbl_wrike_project_list, $condition);

            if ($testdata1[2] > 0) {
                //Query Update
                $update = sprintf(
                    "`owner_id`='$owner_id', `owner_email`='$email_id'",
                    GetSQLValueString($ownerId, "text")
                );

                $res3 = $DBWR->update_data($tbl_wrike_project_list, $update, $condition);
            }
        }

        $condition_flag = "id = '$id'";
        $flagdata = $DBWR->get_data($tbl_wrike_project_list, $condition_flag);

        if ($flagdata[2] > 0) {
            //Query Update
            $update_flag = sprintf(
                "`flag` = 0, `flag_assignment` = 0",
                GetSQLValueString($ownerId, "text")
            );

            $resFlag = $DBWR->update_data($tbl_wrike_project_list, $update_flag, $condition_flag);
        }
    }
}

function addDescription()
{
    global $DBGD;

    $db_gd = "GOOGLE_DRIVE";
    $DBGD = get_conn($db_gd);

    $db_wr = "WRIKE_INTEGRATE";
    $DBWR = get_conn($db_wr);


    $tbl_wrike_project_list = 'wrike_project_list';
    $tbl_project_detail = 'project_detail';
    $conditionDescription = "flag_description = 0";
    $wrikeProject = $DBWR->get_data($tbl_wrike_project_list, $conditionDescription);
    $rowWrikeProject = $wrikeProject[0];
    $resWrikeProject = $wrikeProject[1];
    $totalRowWrikeProject = $wrikeProject[2];

    do {
        $folderId = $rowWrikeProject['id'];
        $projectCode = $rowWrikeProject['project_code'];
        $projectType = $rowWrikeProject['project_type'];
        $flagDescription = $rowWrikeProject['flag_description'];

        if ($flagDescription == 0) {
            echo "$folderId - $projectCode";
            $arrayFolderName = '';
            if ($projectType == 'MSI Project Implementation') {
                $arrayFolderName = array('Project Management External', 'Project Management Internal');
            } else if ($projectType == 'MSI Project Maintenance') {
                $arrayFolderName = array('Maintenance External', 'Maintenance Internal');
                echo $arrayFolderName;
            }

            $folderLinkDesc = '';

            for ($i = 0; $i < count($arrayFolderName); $i++) {
                $folderArrayName = $arrayFolderName[$i];

                echo "<br/>$projectCode - $folderArrayName";

                $queryFolderSQL = "SELECT * FROM sa_project_detail WHERE project_code = '$projectCode' AND folder_name = '$folderArrayName'";
                $DataGoogleID = $DBGD->get_sql($queryFolderSQL);

                echo "<br/>$projectCode - $folderArrayName";

                $folderName = $DataGoogleID[0]['folder_name'];
                $googleID = $DataGoogleID[0]['gd_id'];

                if ($googleID != '') {
                    $folderLink = $folderName . " Google Link : https://drive.google.com/drive/u/3/folders/$googleID <br/>";
                    $folderLinkDesc = $folderLinkDesc . $folderLink;
                } else {
                    $folderLinkDesc = '';
                }
            }

            if ($folderLinkDesc != '') {
                $url = "https://www.wrike.com/api/v4/folders/$folderId";
                $data = array('description' => "$folderLinkDesc");
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

                //Query Update
                $conditionDesc = "id = '$folderId'";
                $updateFlag = sprintf("`flag_description`=1");

                $updateFlagDesc = $DBWR->update_data($tbl_wrike_project_list, $updateFlag, $conditionDesc);
            }
        } else {
            echo "Gagal";
        }
    } while ($rowWrikeProject = $resWrikeProject->fetch_assoc());
}

function permissionDriveAuthor()
{
    // global $DBGD;

    $db_wr = 'WRIKE_INTEGRATE';
    $DBWR = get_conn($db_wr);

    global $DBGD;

    $tbl_role_access = 'role_access';
    $condition_role_access = "access_role != ''";
    $role_access = $DBGD->get_data($tbl_role_access, $condition_role_access);
    $row = $role_access[0];
    $res = $role_access[1];
    $totalRow = $role_access[2];

    $tbl_project_list = 'project_detail';
    $project_list = $DBGD->get_data($tbl_project_list);
    $row_project_list = $project_list[0];
    $res_project_list = $project_list[1];
    $totalRow_project_list = $project_list[2];

    $tblWrikeProjectList = 'wrike_project_list';
    $wrikeProjectList = $DBWR->get_data($tblWrikeProjectList);
    $rowWrikeProjectList = $wrikeProjectList[0];
    $resWrikeProjectList = $wrikeProjectList[1];
    $totalRowWrikeProjectList = $wrikeProjectList[2];

    do {
        $array_project_list[] = $row_project_list;
    } while ($row_project_list = $res_project_list->fetch_assoc());

    // var_dump($array_project_list);

    do {
        $arrayWrikeProjectList[] = $rowWrikeProjectList;
    } while ($rowWrikeProjectList = $resWrikeProjectList->fetch_assoc());

    // var_dump($arrayWrikeProjectList);



    do {
        $group_code = $row['group_code'];
        $folder_name = $row['folder_name'];
        $access_role = $row['access_role'];

        for ($i = 0; $i < count($arrayWrikeProjectList); $i++) {
            $project_code = $arrayWrikeProjectList[$i]['project_code'];
            $userEmail = $arrayWrikeProjectList[$i]['owner_email'];
            $flag = $arrayWrikeProjectList[$i]['flag'];
            // $group_mail_access_role = $access_role;

            if ($group_code == 'UPMG' && $flag == '0') {
                $condition_project_list1 = "project_code = '$project_code' && folder_name = '$folder_name'";
                $project_listed = $DBGD->get_data($tbl_project_list, $condition_project_list1);
                $folder_name_project_listed = $project_listed[0]['folder_name'];
                $gd_id = $project_listed[0]['gd_id'];

                if ($folder_name == $folder_name_project_listed && $userEmail != "") {
                    $explode = explode(", ", $userEmail);

                    for ($j = 0; $j < count($explode); $j++) {
                        echo $explode[$j];
                        // Berhenti Sampai disini
                        $service = new Google_Service_Drive($GLOBALS['client']);
                        $newPermission = new Google_Service_Drive_Permission();
                        $newPermission->setEmailAddress($explode[$j]);
                        $newPermission->setType('user');
                        $newPermission->setRole($access_role);

                        $optparams = [
                            "supportsAllDrives" => true,
                            "supportsTeamDrives" => true,
                            "transferOwnership" => false,
                            "sendNotificationEmail" => false
                        ];
                        $permissionFolder = $service->permissions->create("$gd_id" /* $fileId->getId(); */, $newPermission, $optparams);
                    }
                }

                //Bikin Update Flag 

            }
        }
    } while ($row = $res->fetch_assoc());
}

function getResource()
{

    global $DBGD;

    $db_wr = 'WRIKE_INTEGRATE';
    $DBWR = get_conn($db_wr);

    $tblContactUser = 'contact_user';
    $contact_user = $DBWR->get_data($tblContactUser);
    $rowContactUser = $contact_user[0];
    $resContactUser = $contact_user[1];
    $totalRowContactUser = $contact_user[2];

    $tblProjectList = 'wrike_project_list';
    // $conditionProjectList = "flag = 0";
    $projectList = $DBWR->get_data($tblProjectList);
    $rowProjectList = $projectList[0];
    $resProjectList = $projectList[1];
    $totalRowProjectList = $contact_user[2];

    $tblProjectResources = 'wrike_project_resources';
    $projectResources = $DBWR->get_data($tblProjectResources);
    $rowProjectResources = $projectResources[0];
    $resProjectResources = $projectResources[1];
    $totalRowProjectResources = $projectResources[2];

    do {
        $arrayContactUser[] = $rowContactUser;
    } while ($rowContactUser = $resContactUser->fetch_assoc());

    //var_dump($arrayContactUser);

    do {
        $projectId = $rowProjectList['id'];
        $projectCode = $rowProjectList['project_code'];
        $ownerProjectIdArray = $rowProjectList['owner_id'];
        $flag = $rowProjectList['flag'];
        $owner_id = $rowProjectList['owner_id'];

        $ownerProjectData = explode(", ", $ownerProjectIdArray);

        if ($flag == 0) {
            for ($j = 0; $j < count($ownerProjectData); $j++) {
                $ownerProjectId = $ownerProjectData[$j];

                $conditionsOwnerEmail = "id = '$ownerProjectId'";
                $dataOwner = $DBWR->get_data($tblContactUser, $conditionsOwnerEmail);
                $ownerProjectEmail = $dataOwner[0]['email'];

                $conditionsOwnerProject = "project_code = '$projectCode' AND resource_id = '$ownerProjectId'";
                $dataResources1 = $DBWR->get_data($tblProjectResources, $conditionsOwnerProject);

                echo "$projectCode - $ownerProjectId - $ownerProjectEmail <br/>";

                if ($dataResources1[2] > 0) {
                    //Kosong
                } else {
                    $insertOwnerProject = sprintf(
                        "(`project_code`, `resource_id`, `resource_email`, `user_group`) VALUES ('$projectCode', '$ownerProjectId', '$ownerProjectEmail', 'UPMG')",
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($ownerProjectId, "text"),
                        GetSQLValueString($ownerProjectEmail, "text")
                    );

                    $resOwnerProjectInsert = $DBWR->insert_data($tblProjectResources, $insertOwnerProject);
                }
            }
        }


        $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://www.wrike.com/api/v4/folders/$projectId/tasks?subTasks=true&fields=['responsibleIds']");
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);

        $result2 = json_decode($result, true);
        $result1 = $result2['data'];

        if ($flag == 0) {
            for ($i = 0; $i < count($result1); $i++) {
                $responsibleId = $result1[$i]['responsibleIds'];
                if ($responsibleId != '' && $flag == 0) {
                    for ($k = 0; $k < count($responsibleId); $k++) {
                        $responsibleIds = $responsibleId[$k];
                        //Responsible Ids = Api, $projectOwnerId = Table.
                        if (strpos($owner_id, $responsibleIds) !== false) {
                        } else {
                            $conditionContact = "id = '$responsibleIds'";
                            $dataContact = $DBWR->get_data($tblContactUser, $conditionContact);
                            $dataContactRow = $dataContact[0];
                            $contactEmail = $dataContactRow['email'];
                            $conditionsProjectResources = "project_code = '$projectCode' AND resource_id = '$responsibleIds'";
                            $dataResources = $DBWR->get_data($tblProjectResources, $conditionsProjectResources);

                            //Insert ke db
                            if ($dataResources[2] > 0) {
                                //$updateProjectResources = sprintf("`project_code`='$projectCode', `resource_id` = '$responsibleIds', `resource_email` = '$contactEmail', `user_group`='UENG'",
                                //GetSQLValueString($projectId, "text"),
                                //GetSQLValueString($responsibleIds, "text")
                                //);

                                //$resResourcesUpdate1 = $DBWR->update_data($tblProjectResources, $updateProjectResources, $conditionsProjectResources);
                            } else {
                                $insertProjectResources = sprintf(
                                    "(`project_code`, `resource_id`, `resource_email`, `user_group`) VALUES ('$projectCode', '$responsibleIds', '$contactEmail', 'UENG')",
                                    GetSQLValueString($projectCode, "text"),
                                    GetSQLValueString($responsibleIds, "text"),
                                    GetSQLValueString($contactEmail, "text")
                                );

                                $resResourcesInsert1 = $DBWR->insert_data($tblProjectResources, $insertProjectResources);
                            }
                        }
                        // }
                    }
                } else {
                    echo "Tidak terdapat responsible id atau responsible id sudah terdapat di owner";
                }
            }
        }


        $conditionsUpdateFlagResources = "id = '$projectId'";
        $updateFlagResources = sprintf(
            "`flag`= 1",
        );

        $resFlagResources = $DBWR->update_data($tblProjectList, $updateFlagResources, $conditionsUpdateFlagResources);
    } while ($rowProjectList = $resProjectList->fetch_assoc());
}

function getAssignment()
{
    global $DBWR;
    $db_workload = 'WORKLOAD';
    $DBWRKLD = get_conn($db_workload); 

    $db_wr = 'WRIKE_INTEGRATE';
    $DBWR = get_conn($db_wr);

    $tbl_wrike_project_list = 'wrike_project_list';
    $queryAssignment = "SELECT * FROM sa_wrike_project_list WHERE flag_assignment = 0";
    $dataAssignment = $DBWR->get_sql($queryAssignment);
    $rowAssignment = $dataAssignment[0];
    $resAssignment = $dataAssignment[1];
    $totalRowAssignment = $dataAssignment[2];

    if($totalRowAssignment > 0){
        do {
            $projectId = $rowAssignment['id'];
            $projectCode = $rowAssignment['project_code'];
            $ownerId = $rowAssignment['owner_id'];
    
            $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, "https://www.wrike.com/api/v4/folders/$projectId/tasks?subTasks=true&fields=['responsibleIds','customFields']");
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($curl);
            curl_close($curl);
    
            $result2 = json_decode($result, true);
            $result1 = $result2['data'];
    
            $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, "https://www.wrike.com/api/v4/folders/$projectId");
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $resultP = curl_exec($curl);
            curl_close($curl);
    
            $resultProject = json_decode($resultP, true);
            $resultPProject = $resultProject['data'];
    
            $startDate = $resultPProject[0]['project']['startDate'];
            $endDate = $resultPProject[0]['project']['endDate'];
            $customFields = $resultPProject[0]['customFields'];
    
            for ($h = 0; $h < count($customFields); $h++) {
                $idCF = $customFields[$h]['id'];
                $valueCF = $customFields[$h]['value'];
                if ($idCF == 'IEAEOPF5JUACQU6J') {
                    $customerName = $valueCF;
                }
                if ($idCF == 'IEAEOPF5JUACAVMK') {
                    $projectType = $valueCF;
                }
                if ($idCF == 'IEAEOPF5JUACAVIQ') {
                    $projectCategory = $valueCF;
                }
                if ($idCF == 'IEAEOPF5JUACAVMJ') {
                    $projectMaintenanceCategory = $valueCF;
                }
                if ($idCF == 'IEAEOPF5JUACQU6I') {
                    $product = $valueCF;
                    $productSeperate = str_replace("[", "", "$product");
                    $productSeperate2 = str_replace("]", "", "$productSeperate");
                    $productSeperate3 = str_replace("/", "", "$productSeperate2");
                    $productSeperate4 = str_replace('"', "", "$productSeperate3");
                    $finalProduct = str_replace(",", ", ", "$productSeperate4");
                }
            }
    
            //GET BAST
            // for ($i = 0; $i < count($result1); $i++) {
            //     $taskId = $result1[$i]['id'];
            //     $taskName = $result1[$i]['title'];
            //     $responsibleIds = $result1[$i]['responsibleIds'];
            //     $cfTask = $result1[$i]['customFields'];
                
            //     if (strpos($taskName, '5.2. BAST') !== false) {
            //         $bastDateId = $result1[$i]['id'];
    
            //         $sqlLookupBAST = "SELECT * FROM sa_wrike_timelog WHERE task_id = '$bastDateId'";
            //         $dataLookupBAST = $DBWR->get_sql($sqlLookupBAST);
            //         $totalRowBAST = $dataLookupBAST[2];
    
            //         if($totalRowBAST < 1){
            //             $trackedDateBAST = '';
            //         }else{
            //             $trackedDateBAST = $dataLookupBAST[0]['tracked_date'];
            //         }
    
            //         echo "$trackedDateBAST - Kucik<br/>";
    
            //     }
    
            //     for($z = 0 ; $z<count($cfTask) ; $z++){
            //         $idCFTask = $cfTask[$z]['id'];
            //         $valueCFTask = $cfTask[$z]['value'];
                    
            //         echo "$idCFTask<br/>";
    
            //         if ($idCFTask == 'IEAEOPF5JUACAVIQ') {
            //             $taskCategory = $valueCFTask;
            //         }
            //     }
    
            //     if (empty($responsibleIds)) {
            //         echo "Kosong <br/>";
            //     } else {
            //         for ($k = 0; $k < count($responsibleIds); $k++) {
            //             $responsibleId = $responsibleIds[$k];
    
            //             $queryDataUser = "SELECT email FROM sa_contact_user WHERE id = '$responsibleId'";
            //             $dataUser = $DBWR->get_sql($queryDataUser);
            //             $emailUser = $dataUser[0]['email'];
    
            //             $sqlLookupRoles = "SELECT * FROM sa_resource_jobroles WHERE project_id = '$projectId' AND resource_id = '$responsibleId'";
            //             $dataRoles = $DBWRKLD->get_sql($sqlLookupRoles);
            //             $totalRowJobRoles = $dataRoles[2];
    
            //             if($totalRowJobRoles < 1){
            //                 $jobRoles = '';
            //             }else{
                            
            //             $jobRoles = $dataRoles[0]['job_roles'];
            //             }
    
            //             $tbl_wrike_assignment = 'wrike_assignment';
            //             $conditionAssignment = "project_id = '$projectId' AND task_id = '$taskId' AND resource_id = '$responsibleId'";
            //             $dataAssignment = $DBWR->get_data($tbl_wrike_assignment, $conditionAssignment);
    
            //             if ($dataAssignment[2] > 0) {
            //                 //Jika ada update tulis disini
            //                 $updateAssignment = sprintf(
            //                     "`project_code`='$projectCode', `task_id`='$taskId', `resource_id`='$responsibleId', `resource_email`='$emailUser', `resource_role`='$jobRoles'",
            //                     GetSQLValueString($projectCode, "text"),
            //                     GetSQLValueString($taskId, "text"),
            //                     GetSQLValueString($taskName, "text"),
            //                     GetSQLValueString($responsibleId, "text"),
            //                     GetSQLValueString($emailUser, "text"),
            //                     GetSQLValueString($jobRoles, "text"),
            //                     GetSQLValueString($taskCategory, "text")
            //                 );
    
            //                 $resUpdateAssignment = $DBWR->update_data($tbl_wrike_assignment, $updateAssignment, $conditionAssignment);
    
            //                 echo "<br/>Sudah di Update<br/>";
            //             } else {
            //                 //Data baru di insert ke database
            //                 $insertAssignment = sprintf(
            //                     "(`project_id`, `project_code`, `task_id`, `resource_id`, `resource_email`, `resource_role`) VALUES ('$projectId', '$projectCode', '$taskId', '$responsibleId', '$emailUser', '$jobRoles')",
            //                     GetSQLValueString($projectId, "text"),
            //                     GetSQLValueString($projectCode, "text"),
            //                     GetSQLValueString($taskId, "text"),
            //                     GetSQLValueString($taskName, "text"),
            //                     GetSQLValueString($responsibleId, "text"),
            //                     GetSQLValueString($emailUser, "text"),
            //                     GetSQLValueString($jobRoles, "text"),
            //                     GetSQLValueString($taskCategory, "text")
            //                 );
            //                 $resResourcesAssignment = $DBWR->insert_data($tbl_wrike_assignment, $insertAssignment);
            //             }
            //         }
            //     }
            // }
    
            //GET 5 Row BAST
            for($j = 0; $j < count($resultPProject); $j++){
                $title = $resultPProject[$j]['title'];
                $childIds = $resultPProject[$j]['childIds'];
    
                for($k = 0 ; $k < count($childIds); $k++){
                    $eachChildIds = $childIds[$k];
                    $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, "https://www.wrike.com/api/v4/folders/$eachChildIds/tasks?subTasks=true&fields=['customFields']");
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                    $bastDateResponse = curl_exec($curl);
                    curl_close($curl);
    
                    $bastDateResponseJson = json_decode($bastDateResponse, true);
                    $resultBastDate = $bastDateResponseJson['data'];
    
                    for($l = 0 ; $l < count($resultBastDate); $l++){
                        $titleBast = $resultBastDate[$l]['title'];
    
                        if ($titleBast == '06. SBF document (estimated duration)') {
                            $titleSBF = $resultBastDate[$l]['title'];
                            $sbfBastDate = $resultBastDate[$l]['customFields'][0]['value'];
                        }
                        if ($titleBast == '05. Kick-off Meeting') {
                            $titleKOM = $resultBastDate[$l]['title'];
                            $komBastDate = $resultBastDate[$l]['customFields'][0]['value'];
                        }
                        if ($titleBast == '04. PO') {
                            $titlePO = $resultBastDate[$l]['title'];
                            $poBastDate = $resultBastDate[$l]['customFields'][0]['value'];
                        }
                        if ($titleBast == '03. Contract') {
                            $titleContract = $resultBastDate[$l]['title'];
                            $contractBastDate = $resultBastDate[$l]['customFields'][0]['value'];
                        }
                        if ($titleBast == '02. Change Request') {
                            $titleCR = $resultBastDate[$l]['title'];
                            $crBastDate = $resultBastDate[$l]['customFields'][0]['value'];
                        }
                        if ($titleBast == '01. Addendum') {
                            $titleAddendum = $resultBastDate[$l]['title'];
                            $addendumBastDate = $resultBastDate[$l]['customFields'][0]['value'];
                        }
                    }
                }
            }
    
            echo "$title - $titleSBF - $sbfBastDate <br/>";
            echo "$title - $titleKOM - $komBastDate <br/>";
            echo "$title - $titlePO - $poBastDate <br/>";
            echo "$title - $titleContract - $contractBastDate <br/>";
            echo "$title - $titleCR - $crBastDate <br/>";
            echo "$title - $titleAddendum - $addendumBastDate <br/>";
    
            for ($i = 0; $i < count($result1); $i++) {
                $taskId = $result1[$i]['id'];
                $taskName = $result1[$i]['title'];
                $responsibleIds = $result1[$i]['responsibleIds'];
                $cfTask = $result1[$i]['customFields'];
    
                for($z = 0 ; $z<count($cfTask) ; $z++){
                    $idCFTask = $cfTask[$z]['id'];
                    $valueCFTask = $cfTask[$z]['value'];
                    
                    echo "$idCFTask<br/>";
    
                    if ($idCFTask == 'IEAEOPF5JUACAVIQ') {
                        $taskCategory = $valueCFTask;
                    }
                }
    
                if (empty($responsibleIds)) {
                    echo "Kosong <br/>";
                } else {
                    for ($k = 0; $k < count($responsibleIds); $k++) {
                        $responsibleId = $responsibleIds[$k];
    
                        $queryDataUser = "SELECT email FROM sa_contact_user WHERE id = '$responsibleId'";
                        $dataUser = $DBWR->get_sql($queryDataUser);
                        $emailUser = $dataUser[0]['email'];
    
                        $sqlLookupRoles = "SELECT * FROM sa_resource_jobroles WHERE project_id = '$projectId' AND resource_id = '$responsibleId'";
                        $dataRoles = $DBWRKLD->get_sql($sqlLookupRoles);
                        $totalRowJobRoles = $dataRoles[2];
    
                        if($totalRowJobRoles < 1){
                            $jobRoles = '';
                        }else{
                            $jobRoles = $dataRoles[0]['job_roles'];
                        }
    
                        $tbl_wrike_assignment = 'wrike_assignment';
                        $conditionAssignment = "project_id = '$projectId' AND task_id = '$taskId' AND resource_id = '$responsibleId'";
                        $dataAssignment = $DBWR->get_data($tbl_wrike_assignment, $conditionAssignment);
    
                        if ($dataAssignment[2] > 0) {
                            //Jika ada update tulis disini
                            $updateAssignment = sprintf(
                                "`project_code`='$projectCode', `task_id`='$taskId', `resource_id`='$responsibleId', `resource_email`='$emailUser', `resource_role`='$jobRoles'",
                                GetSQLValueString($projectCode, "text"),
                                GetSQLValueString($taskId, "text"),
                                GetSQLValueString($taskName, "text"),
                                GetSQLValueString($responsibleId, "text"),
                                GetSQLValueString($emailUser, "text"),
                                GetSQLValueString($jobRoles, "text"),
                                GetSQLValueString($taskCategory, "text")
                            );
    
                            $resUpdateAssignment = $DBWR->update_data($tbl_wrike_assignment, $updateAssignment, $conditionAssignment);
    
                            echo "<br/>Sudah di Update<br/>";
                        } else {
                            //Data baru di insert ke database
                            $insertAssignment = sprintf(
                                "(`project_id`, `project_code`, `task_id`, `resource_id`, `resource_email`, `resource_role`) VALUES ('$projectId', '$projectCode', '$taskId', '$responsibleId', '$emailUser', '$jobRoles')",
                                GetSQLValueString($projectId, "text"),
                                GetSQLValueString($projectCode, "text"),
                                GetSQLValueString($taskId, "text"),
                                GetSQLValueString($taskName, "text"),
                                GetSQLValueString($responsibleId, "text"),
                                GetSQLValueString($emailUser, "text"),
                                GetSQLValueString($jobRoles, "text"),
                                GetSQLValueString($taskCategory, "text")
                            );
                            $resResourcesAssignment = $DBWR->insert_data($tbl_wrike_assignment, $insertAssignment);
                        }
                    }
                }
            }
    
    
            //INSERT or UPDATE PROJECT DETAIL
            if ($projectType == 'MSI Project Implementation') {
                $tbl_wrike_project_detail = 'wrike_project_detail';
                $conditionProjectDetailImplementation = "project_id = '$projectId'";
                $dataProjectDetailImplementation = $DBWR->get_data($tbl_wrike_project_detail, $conditionProjectDetailImplementation);
    
                if ($dataProjectDetailImplementation[2] > 0) {
                    //Jika ada update tulis disini
                    $updateProjectDetailImplementation = sprintf(
                        "`project_code`='$projectCode', `customer_name`='$customerName', `start_date_project`='$startDate', `finish_date_project`='$endDate', `sbf_bast_date` = '$sbfBastDate', 
                        `kom_bast_date` = '$komBastDate', `po_bast_date` = '$poBastDate', `contract_bast_date` = '$contractBastDate', `cr_bast_date` = '$crBastDate', `addendum_bast_date` = '$addendumBastDate', `project_type`='$projectType', `project_category`='$projectCategory', `product`='$finalProduct'",
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($taskId, "text"),
                        GetSQLValueString($taskName, "text"),
                        GetSQLValueString($responsibleId, "text"),
                        GetSQLValueString($emailUser, "text"),
                        GetSQLValueString($jobRoles, "text")
                    );
    
                    $resProjectDetailImplementation = $DBWR->update_data($tbl_wrike_project_detail, $updateProjectDetailImplementation, $conditionProjectDetailImplementation);
                } else {
                    //Data baru di insert ke database
                    $insertProjectDetailImplementation = sprintf(
                        "(`project_id`, `project_code`, `customer_name`, `start_date_project`, `finish_date_project`, `sbf_bast_date`, `kom_bast_date`, `po_bast_date`, `contract_bast_date`, `cr_bast_date`, `addendum_bast_date`, `project_type`, `project_category`, `product`) 
                        VALUES ('$projectId', '$projectCode', '$customerName', '$startDate', '$endDate', '$sbfBastDate', '$komBastDate', '$poBastDate', '$contractBastDate', '$crBastDate', '$addendumBastDate','$projectType', '$projectCategory', '$finalProduct')",
                        GetSQLValueString($projectId, "text"),
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($customerName, "text"),
                        GetSQLValueString($startDate, "text"),
                        GetSQLValueString($endDate, "text"),
                        GetSQLValueString($projectType, "text"),
                        GetSQLValueString($projectCategory, "text"),
                        GetSQLValueString($finalProduct, "text")
                    );
    
                    $resProjectDetailImplementation = $DBWR->insert_data($tbl_wrike_project_detail, $insertProjectDetailImplementation);
                    echo "Update Project Detail Implementation <br/>";
                }
    
                echo "PROJECT DETAIL : $projectId - $projectCode - $customerName - $startDate - $endDate - $projectType - $projectCategory - $finalProduct<br/>";
            } else if ($projectType == 'MSI Project Maintenance') {
                $tbl_wrike_project_detail = 'wrike_project_detail';
                $conditionProjectDetailMaintenance = "project_id = '$projectId'";
                $dataProjectDetailMaintenance = $DBWR->get_data($tbl_wrike_project_detail, $conditionProjectDetailMaintenance);
    
                if ($dataProjectDetailMaintenance[2] > 0) {
                    //Jika ada update tulis disini
                    $updateProjectDetailMaintenance = sprintf(
                        "`project_code`='$projectCode', `customer_name`='$customerName', `start_date_project`='$startDate', `finish_date_project`='$endDate', `sbf_bast_date` = '$sbfBastDate', 
                        `kom_bast_date` = '$komBastDate', `po_bast_date` = '$poBastDate', `contract_bast_date` = '$contractBastDate', `cr_bast_date` = '$crBastDate', `addendum_bast_date` = '$addendumBastDate', `project_type`='$projectType', `project_category`='$projectMaintenanceCategory', `product`='$finalProduct'",
                        
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($customerName, "text"),
                        GetSQLValueString($startDate, "text"),
                        GetSQLValueString($endDate, "text"),
                        GetSQLValueString($sbfBastDate, "text"),
                        GetSQLValueString($komBastDate, "text"),
                        GetSQLValueString($poBastDate, "text"),
                        GetSQLValueString($contractBastDate, "text"),
                        GetSQLValueString($crBastDate, "text"),
                        GetSQLValueString($addendumBastDate, "text"),
                        GetSQLValueString($projectType, "text"),
                        GetSQLValueString($projectMaintenanceCategory, "text"),
                        GetSQLValueString($finalProduct, "text")
                    );
    
                    $resProjectDetailMaintenance = $DBWR->update_data($tbl_wrike_project_detail, $updateProjectDetailMaintenance, $conditionProjectDetailMaintenance);
                } else {
                    //Data baru di insert ke database
                    $insertProjectDetailMaintenance = sprintf(
                        "(`project_id`, `project_code`, `customer_name`, `start_date_project`, `finish_date_project`, `sbf_bast_date`, `kom_bast_date`, `po_bast_date`, `contract_bast_date`, `cr_bast_date`, `addendum_bast_date`, `project_type`, `project_category`, `product`) 
                        VALUES ('$projectId', '$projectCode', '$customerName', '$startDate', '$endDate', '$sbfBastDate', '$komBastDate', '$poBastDate', '$contractBastDate', '$crBastDate', '$addendumBastDate', '$projectType', '$projectMaintenanceCategory', '$finalProduct')",
                        GetSQLValueString($projectId, "text"),
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($customerName, "text"),
                        GetSQLValueString($startDate, "text"),
                        GetSQLValueString($endDate, "text"),
                        GetSQLValueString($sbfBastDate, "text"),
                        GetSQLValueString($komBastDate, "text"),
                        GetSQLValueString($poBastDate, "text"),
                        GetSQLValueString($contractBastDate, "text"),
                        GetSQLValueString($crBastDate, "text"),
                        GetSQLValueString($addendumBastDate, "text"),
                        GetSQLValueString($projectType, "text"),
                        GetSQLValueString($projectMaintenanceCategory, "text"),
                        GetSQLValueString($finalProduct, "text")
                    );
                    $resProjectDetailMaintenance = $DBWR->insert_data($tbl_wrike_project_detail, $insertProjectDetailMaintenance);
                }
                echo "PROJECT DETAIL : $projectId - $projectCode - $customerName - $startDate - $endDate - $projectType - $projectMaintenanceCategory - $finalProduct<br/>";
            }
    
            //Tutup Flag
            $conditionCloseAssignmentFlag = "id = '$projectId'";
            $updateProjectList = sprintf(
                "`flag_assignment`= 1"
            );
    
            $resCloseAssignmentFlag = $DBWR->update_data($tbl_wrike_project_list, $updateProjectList, $conditionCloseAssignmentFlag);
    
            //Clean BAST
            $titleContract = "";
            $contractBastDate = ""; 
            $titleIKOM = "";
            $ikomBastDate = ""; 
            $titleCR = "";
            $crBastDate = ""; 
            $titleSB = "";
            $sbBastDate = ""; 
            $titleDocument = "";
            $documentBastDate = ""; 
        } while ($rowAssignment = $resAssignment->fetch_assoc());
    }else{
        echo "Semua flag = 1<br/>";
    }

    
}

function permissionResource()
{
    $db_gd = "GOOGLE_DRIVE";
    $DBGD = get_conn($db_gd);

    $db_wr = 'WRIKE_INTEGRATE';
    $DBWR = get_conn($db_wr);

    global $DBGD;

    $tbl_resource = 'wrike_project_resources';
    $condition_resources_data = 'flag = 0';
    $resources_data = $DBWR->get_data($tbl_resource, $condition_resources_data);
    $rowResourcesData = $resources_data[0];
    $resResourcesData = $resources_data[1];
    $totalRowResourcesData = $resources_data[2];

    $tbl_role_access = 'role_access';
    $condition_role_access = "access_role != ''";
    $role_access = $DBGD->get_data($tbl_role_access, $condition_role_access);
    $row = $role_access[0];
    $res = $role_access[1];
    $totalRow = $role_access[2];

    $tbl_project_list = 'project_detail';
    $project_list = $DBGD->get_data($tbl_project_list);
    $row_project_list = $project_list[0];
    $res_project_list = $project_list[1];
    $totalRow_project_list = $project_list[2];

    do {
        $arrRoleAccess[] = $row;
    } while ($row = $res->fetch_assoc());

    // var_dump($arrRoleAccess);

    // do {
    //     $arrProjectDetail[] = $row_project_list;
    // } while ($row_project_list = $res_project_list->fetch_assoc());

    // var_dump($arrProjectDetail);

    do {
        $projectCode = $rowResourcesData['project_code'];
        $resourceId = $rowResourcesData['resource_id'];
        $resourceEmail = $rowResourcesData['resource_email'];
        $userGroup = $rowResourcesData['user_group'];
        $flag = $rowResourcesData['flag'];
        $permissionFlag = 0;

        for ($i = 0; $i < count($arrRoleAccess); $i++) {
            $groupCode = $arrRoleAccess[$i]['group_code'];
            $folderName = $arrRoleAccess[$i]['folder_name'];
            $accessRole = $arrRoleAccess[$i]['access_role'];

            //for ($k = 0; $k < count($arrProjectDetail); $k++) {
            $condition_project_list_detail = "project_code = '$projectCode' && folder_name = '$folderName'";
            $projectDetail = $DBGD->get_data($tbl_project_list, $condition_project_list_detail);
            $folderNameProjectDetail = $projectDetail[0]['folder_name'];
            $gd_id = $projectDetail[0]['gd_id'];

            if ($userGroup == $groupCode && $flag == 0 && $folderName == $folderNameProjectDetail) {
                echo "$projectCode - $resourceEmail - $flag - $groupCode - $folderName - $folderNameProjectDetail -  $accessRole - $gd_id <br/>";
                $service = new Google_Service_Drive($GLOBALS['client']);
                $newPermission = new Google_Service_Drive_Permission();
                $newPermission->setEmailAddress($resourceEmail);
                $newPermission->setType('user');
                $newPermission->setRole($accessRole);

                $optparams = [
                    "supportsAllDrives" => true,
                    "supportsTeamDrives" => true,
                    "transferOwnership" => false,
                    "sendNotificationEmail" => false
                ];
                $permissionFolder = $service->permissions->create("$gd_id" /* $fileId->getId(); */, $newPermission, $optparams);
                $permissionFlag = 1;
            }

            if ($permissionFlag == 1) {
                $conditionsResourcesUpdate = "project_code = '$projectCode' AND resource_id = '$resourceId'";
                $updateFlagResources = sprintf(
                    "`flag`= 1",
                );

                $resResourcesUpdate1 = $DBWR->update_data($tbl_resource, $updateFlagResources, $conditionsResourcesUpdate);
            }
            //}
        }
    } while ($rowResourcesData = $resResourcesData->fetch_assoc());
}

function getWorkschedule(){
    $db_wr = 'WRIKE_INTEGRATE';
    $DBWR = get_conn($db_wr);
    $tbl_wrike_workdays_exception = 'wrike_workdays_exception';
    
    $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6ODEyMTU0OSxcImNcIjo0NjI3NTcyLFwidVwiOjEwNTQ2MTcxLFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNjQ0NDc2Mjg0fQ.OYiWMExZd0ylwzdF-jqHC2E3FzziGRcLV_5NQgGuH-w";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, "https://www.wrike.com/api/v4/workschedules/IEAEOPF5MIACBAEU/workschedule_exclusions");
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    curl_close($curl);

    $resultWorkschedule = json_decode($result, true);
    $resultWorkscheduleData = $resultWorkschedule['data'];

    for($i = 0 ; $i < count($resultWorkscheduleData) ; $i++){
        $wsId = $resultWorkscheduleData[$i]['id'];
        $fromDate = $resultWorkscheduleData[$i]['fromDate'];
        $toDate = $resultWorkscheduleData[$i]['toDate'];
        $isWorkDays = $resultWorkscheduleData[$i]['isWorkDays'];
        $exclusionType = $resultWorkscheduleData[$i]['exclusionType'];

        if($isWorkDays == false){
            $isWorkdays = 0;
        }else{
            $isWorkdays = 1;
        }

        $insertWS = sprintf(
            "(`id`,`from_date`,`to_date`, `is_workdays`, `exclusion_type`) VALUES ('$wsId', '$fromDate', '$toDate', '$isWorkdays', '$exclusionType')",
            GetSQLValueString($wsId, "text"),
            GetSQLValueString($fromDate, "text"),
            GetSQLValueString($toDate, "text"),
            GetSQLValueString($isWorkdays, "bool"),
            GetSQLValueString($exclusionType, "text")
        );
        $resWS = $DBWR->insert_data($tbl_wrike_workdays_exception, $insertWS);

        echo "$wsId - $fromDate - $toDate - $isWorkDays - $exclusionType <br/>";
    }
}