<?php

class WRIKE_PROJECT
{
    function get_projectMandiri($projectCode)
    {
        $API_WRIKE = new API_WRIKE;
        $infoOutput = '';

        if (!isset($projectCode) || $projectCode == '') {
            return "Project Code tidak boleh kosong";
        }
        $db_wr = "WRIKE_INTEGRATE";
        $DBWR = get_conn($db_wr);

        $db_wrkld = "WORKLOAD";
        $DBWRKLD = get_conn($db_wrkld);

        $tbl_wrike_project_list = 'wrike_project_list';
        $sqlGetId = "select project_id, project_type, order_number from sa_initial_project where project_code = '$projectCode' and project_id != ''";
        $dataGetId = $DBWR->get_sqlV2($sqlGetId);
        $rowGetId = $dataGetId[0];
        $resGetId = $dataGetId[1];
        $countGetId = $dataGetId[2];

        if ($countGetId == 0) {
            return "Initial project belum tersedia";
        }

        while ($rowGetId = $resGetId->fetch_assoc()) {
            $projectId = $rowGetId['project_id'];
            $projectType = $rowGetId['project_type'];
            $urlApiWrike = "https://www.wrike.com/api/v4/folders/$projectId";
            $dataApi = $API_WRIKE->getApiWrike($urlApiWrike);

            if (count($dataApi) == 0) {
                continue;
            }

            foreach ($dataApi as $dataA) {
                $id = $dataA['id'];
                $title = addslashes($dataA['title']);
                $createdDate = $dataA['createdDate'];
                $permalink = $dataA['permalink'];
                $ownerId = $dataA['project']['ownerIds'];
                $updatedDate = $dataA['updatedDate'];
                $cf = $dataA['customFields'];

                $noSO = '';
                $projectCode = '';
                $orderNumber = '';
                $projectTypeId = '';
                $projectTypeValue = '';
                $testid = '';
                foreach ($cf as $cfTurunan) {
                    $cfId = $cfTurunan['id'];
                    $cfValue = $cfTurunan["value"];

                    if ($cfId == 'IEAEOPF5JUACAY7C') {
                        $noSO = $cfValue;
                    }

                    if ($cfId == 'IEAEOPF5JUACAVJN') {
                        $projectCode = $cfValue;
                    }

                    if ($cfId == 'IEAEOPF5JUAD65N6') {
                        $orderNumber = $cfValue;
                        $testid = $cfId;
                    }

                    if ($cfId == 'IEAEOPF5JUACAVMK') {
                        $projectTypeId = $cfId;
                        $projectTypeValue = $cfValue;
                    }
                }

                if ($projectType == 'Maintenance') {
                    $orderNumber = $rowGetId['order_number'];
                } else {
                    $orderNumber = $orderNumber;
                }

                //harusnya nilai null tidak apa2, kecuali ada update harus ada idnya
                // if ($noSO != '' && $projectCode != '' && $orderNumber != '') {
                if ($id != '') {
                    $infoOutput .= "</br>Data : $id - $projectCode - $title - $noSO - $orderNumber($testid)</br>";
                    $conditionFirst = "id = '$id'";
                    $testdata = $DBWR->get_data($tbl_wrike_project_list, $conditionFirst);
                    if ($testdata[2] > 0) {
                        //Query Update
                        $updateFirst = sprintf(
                            "`title`='$title', `created_date`='$createdDate', `permalink`='$permalink', `project_code`='$projectCode', `no_so` = '$noSO',
                                `order_number` = '$orderNumber'",
                            GetSQLValueString($title, "text"),
                            GetSQLValueString($createdDate, "text"),
                            GetSQLValueString($permalink, "text"),
                            GetSQLValueString($projectCode, "text"),
                            GetSQLValueString($noSO, "text"),
                            GetSQLValueString($orderNumber, "text")
                        );

                        $resUpdateFirst = $DBWR->update_data($tbl_wrike_project_list, $updateFirst, $conditionFirst);
                        $infoOutput .= 'tabel : ' . $tbl_wrike_project_list . ' update : ' . $updateFirst . ' condition : ' . $conditionFirst . '</br>';
                    } else {
                        //Query Insert
                        $insertFirst = sprintf(
                            "(`id`,`title`,`created_date`, `permalink`, `project_code`, `no_so`, `order_number`) VALUES ('$id', '$title', '$createdDate', '$permalink', '$projectCode', '$noSO', '$orderNumber')",
                            GetSQLValueString($id, "text"),
                            GetSQLValueString($title, "text"),
                            GetSQLValueString($createdDate, "text"),
                            GetSQLValueString($permalink, "text"),
                            GetSQLValueString($projectCode, "text"),
                            GetSQLValueString($noSO, "text"),
                            GetSQLValueString($orderNumber, "text")
                        );

                        $resInsertFirst = $DBWR->insert_data($tbl_wrike_project_list, $insertFirst);
                        $infoOutput .= 'tabel : ' . $tbl_wrike_project_list . ' insert : ' . $insertFirst . '</br>';
                    }
                }

                if ($projectTypeId != '') {
                    $condition_type = "id = '$id'";
                    $testdata_type = $DBWR->get_data($tbl_wrike_project_list, $condition_type);
                    if ($testdata_type[2] > 0) {
                        //Query Update
                        $updateProjectType = sprintf(
                            "`project_type`='$projectTypeValue'",
                            GetSQLValueString($projectTypeValue, "text")
                        );
                        $res1ProjectType = $DBWR->update_data($tbl_wrike_project_list, $updateProjectType, $condition_type);
                        $infoOutput .= 'tabel : ' . $tbl_wrike_project_list . ' update : ' . $updateProjectType . ' condition : ' . $condition_type . '</br>';
                    }
                }

                $email_id = '';
                $owner_id = '';

                if (count($ownerId) > 0) {
                    $i = 0;
                    foreach ($ownerId as $ownerI) {
                        $tblContactUser = "contact_user";
                        $conditionContact = "id = '$ownerI'";
                        $dataContact = $DBWR->get_data($tblContactUser, $conditionContact);

                        $dataContactRow = $dataContact[0];
                        $dataContactRes = $dataContact[1];
                        $dataContactRTotalRow = $dataContact[2];

                        $contactId = isset($dataContactRow['email']) ? $dataContactRow['email'] : '';
                        //aneh
                        if ($i == 0) {
                            $owner_id = $ownerI;
                            $email_id = $contactId;
                        } else {
                            $owner_id = $owner_id . ', ' . $ownerI;
                            $email_id = $email_id . ', ' . $contactId;
                            $email_id = rtrim($email_id, ',');
                            $owner_id = rtrim($owner_id, ',');
                        }
                        $i++;
                    }

                    $conditionSecond = "id = '$id'";
                    $testdata1 = $DBWR->get_data($tbl_wrike_project_list, $conditionSecond);
                    if ($testdata1[2] > 0) {
                        //kalo email enggk ada update atau enggk ?
                        $updateSecond = sprintf(
                            "`owner_id`='$owner_id', `owner_email`='$email_id'"
                        );
                        $resUpdateSecond = $DBWR->update_data($tbl_wrike_project_list, $updateSecond, $conditionSecond);
                        $infoOutput .= 'tabel : ' . $tbl_wrike_project_list . ' update second : ' . $updateSecond . ' condition : ' . $conditionSecond . '</br>';
                    }
                }

                $condition_flag = "id = '$id'";
                $flagdata = $DBWR->get_data($tbl_wrike_project_list, $condition_flag);
                if ($flagdata[2] > 0) {
                    //Query Update
                    $update_flag = sprintf(
                        "`flag` = 0, `flag_assignment` = 0, `flag_timelog` = 0, `flag_workload` = 0, `flag_jobrole` = 0, `flag_detail_task` = 0"
                    );

                    $resFlag = $DBWR->update_data($tbl_wrike_project_list, $update_flag, $condition_flag);
                    $infoOutput .= 'tabel : ' . $tbl_wrike_project_list . ' update flag : ' . $update_flag . ' condition : ' . $condition_flag . '</br>';
                }

                $condition_flag_jobroles = "project_id = '$id'";
                $flagdataJobroles = $DBWRKLD->get_data("initial_jobroles", $condition_flag_jobroles);
                if ($flagdataJobroles[2] > 0) {
                    //Query Update
                    $update_flag_jobroles = sprintf(
                        "`flag` = 0"
                    );
                    $resFlagJobroles = $DBWRKLD->update_data("initial_jobroles", $update_flag_jobroles, $condition_flag_jobroles);
                    $infoOutput .= 'tabel : initial_jobroles ' . ' update flag : ' . $update_flag_jobroles . ' condition : ' . $condition_flag_jobroles . '</br>';
                }

                $condition_flag_wps = "project_id = '$id'";
                $flagdataWPS = $DBWRKLD->get_data("workload_project_solution", $condition_flag_wps);
                if ($flagdataWPS[2] > 0) {
                    //Query Update
                    $updateWPS = sprintf(
                        "`flag_get_task` = 0"
                    );
                    $resFlagWPS = $DBWRKLD->update_data("workload_project_solution", $updateWPS, $condition_flag_wps);
                    $infoOutput .= 'tabel : workload_project_solution ' . ' update flag : ' . $updateWPS . ' condition : ' . $condition_flag_wps . '</br>';
                }
            }
        }

        return true;
    }

    function masterForwardAssignment($checkbox = '')
    {
        $infoOuput = '';
        $db_wrike = 'WRIKE_INTEGRATE';
        $DBWR = get_conn($db_wrike);

        $idResource = implode(",", $checkbox);
        $sqlResourceAssignment = "SELECT * FROM sa_resource_assignment 
    WHERE id IN ($idResource)";
        $dataResource = $DBWR->get_sqlV2($sqlResourceAssignment);
        $rowDataResource = $dataResource[0];
        $resDataResource = $dataResource[1];
        $totalRowDataResource = $dataResource[2];

        if ($totalRowDataResource > 0) {
            while ($rowDataResource = $resDataResource->fetch_assoc()) {
                if ($rowDataResource['project_type'] === 'Implementation') {
                    //implementation
                    $infoOuput .= $this->forwardAssignmentMandiri($rowDataResource);
                } else {
                    //mt
                    $infoOuput .= $this->forwardAssignmentMTMandiri($rowDataResource);
                }
            }
        } else {
            $infoOuput .= "Semua data telah di push/tidak ada!<br/>";
        }

        $infoOuput .= 'Proses selesai </br>';
        return $infoOuput;
    }

    function forwardAssignmentMandiri($rowDataResource = '')
    {
        $API_WRIKE = new API_WRIKE;
        $infoOutput = '';
        $userNameApprove = $_POST['userSession'];
        $db_wrike = 'WRIKE_INTEGRATE';
        $DBWR = get_conn($db_wrike);

        $db_workload = 'WORKLOAD';
        $DBWRKLD = get_conn($db_workload);

        $db_sb = 'SERVICE_BUDGET';
        $DBSB = get_conn($db_sb);

        $id = $rowDataResource['id'];
        $projectId = $rowDataResource['project_id'];
        $projectRoles = $rowDataResource['project_roles'];
        $projectCode = $rowDataResource['project_code'];
        $resourceEmail = $rowDataResource['resource_email'];
        $resourceRoles = $rowDataResource['roles'];
        $explodeResourceEmail = explode("<", $resourceEmail);
        $finalResourceEmail = str_replace(">", "", $explodeResourceEmail[1]);

        $explodeResourceRoles = explode(" - ", $resourceRoles);

        if ($explodeResourceRoles[1] == "") {
            $jobRolesFinal = $explodeResourceRoles[0];
        } else if ($explodeResourceRoles[1] != "") {
            $jobRolesFinal = $explodeResourceRoles[0] . " " . $explodeResourceRoles[1];
        }

        $sqlLookupRolesId = "SELECT * FROM sa_mst_resource_catalogs WHERE resource_qualification = '" . $explodeResourceRoles[0] . "'";
        $dataLookupRolesId = $DBSB->get_sqlV2($sqlLookupRolesId);
        $rowRolesId = $dataLookupRolesId[0];

        //Digunakan
        $idRoles = $rowRolesId['resource_catalog_id'];

        //Lookup Initial JobRoles
        $sqlLookupIJ = "SELECT * FROM sa_initial_jobroles WHERE project_id = '$projectId' AND resource_category_id = '$idRoles' AND brand = '" . $explodeResourceRoles[1] . "'";
        $dataLookupIJ = $DBWRKLD->get_sqlV2($sqlLookupIJ);
        $rowLookupIJ = $dataLookupIJ[0];
        $taskId = $rowLookupIJ['task_id'];

        //Lookup Initial Jobroles Project
        $sqlLookupIJP = "SELECT * FROM sa_initial_jobroles_project WHERE project_id = '$projectId' AND jobroles_name LIKE '%$projectRoles%'";
        $dataLookupIJP = $DBWRKLD->get_sqlV2($sqlLookupIJP);
        $rowLookupIJP = $dataLookupIJP[0];
        if ($dataLookupIJP[2] > 1) {
            while ($row = $dataLookupIJP[1]->fetch_assoc()) {
                $jobrolesnameraw = $row['jobroles_name'];
                if (!strpos($jobrolesnameraw, 'Co - Project Leader') !== false) {
                    $projectRolesId = $row['job_task_id'];
                }
            }
        } else {
            $projectRolesId = $rowLookupIJP['job_task_id'];
        }

        $dataLookupIJ = $DBWRKLD->get_sqlV2($sqlLookupIJ);
        $rowLookupIJ = $dataLookupIJ[0];
        $taskId = $rowLookupIJ['task_id'];

        //Lookup Wrike ID From Email
        $sqlWrikeId = "SELECT * FROM sa_contact_user WHERE email = '$finalResourceEmail'";
        $dataWrikeId = $DBWR->get_sqlV2($sqlWrikeId);
        $rowWrikeId = $dataWrikeId[0];

        if ($rowWrikeId == NULL) {
            $resourceWrikeId = "";
        } else {
            $resourceWrikeId = $rowWrikeId['id'];
        }

        if ($resourceWrikeId != '') {
            //Check Resource Jobroles
            $sqlCheckResourceJobrole = "SELECT * FROM sa_resource_jobroles WHERE project_id = '$projectId' AND resource_email = '$resourceEmail' AND job_roles LIKE '%$jobRolesFinal%'";
            $executeSqlCheck = $DBWRKLD->get_sqlV2($sqlCheckResourceJobrole);
            $totalRowSqlCheck = $executeSqlCheck[2];

            if ($totalRowSqlCheck < 1) {
                // PUT ASSIGNMENT TASK
                $response = $API_WRIKE->putApiWrikeAccessTask($taskId, $resourceWrikeId);

                if (isset($response['errorDescription'])) {
                    $infoOutput .= 'Projectcode ' . $projectCode . ' id ' . $projectId . ' Service Budget ' . $response['errorDescription'] . '</br>';
                } else {
                    $infoOutput .= 'Projectcode ' . $projectCode . ' id ' . $projectId . ' Service Budget addResponsibles berhasil </br>';
                }

                $now = date('Y-m-d H:i:s');

                if ($explodeResourceRoles[1] == '') {
                    $resourceRoles = $explodeResourceRoles[0];
                }

                // echo "$projectCode - $resourceEmail - $resourceRoles - $idRoles - $taskId - $resourceWrikeId<br/>";

                $responsePut = $API_WRIKE->putApiWrikeAccessTask($projectRolesId, $resourceWrikeId);

                if (isset($responsePut['errorDescription'])) {
                    $infoOutput .= 'Projectcode ' . $projectCode . ' id ' . $projectId . ' Project Role Tidak sesuai/belum ada task role pada project wrike bisa open ticket ke sd untuk menambahkan Task Jobrole</br>';
                } else {
                    $infoOutput .= 'Projectcode ' . $projectCode . ' id ' . $projectId . ' Project Role addResponsibles berhasil</br>';
                }

                // echo "ECHO ASSIGNMENT $id - $projectCode - $resourceEmail - $resourceRoles - $idRoles - $taskId - $resourceWrikeId<br/>";

                if ((!isset($response['errorDescription'])) && (!isset($responsePut['errorDescription']))) {
                    $conditionJobRoles = "id = '$id'";
                    $updateFlag = sprintf(
                        "`flag_assign_wrike`= 2, `timestamp_wrike` = '" . date("Y-m-d H:i:s") . "', `approval_status`= 'approved', `modified_by` = '" . $userNameApprove . "', `modified_in_msizone` = '" . date("Y-m-d H:i:s") . "'",
                    );
                    $resUpdateFlag = $DBWR->update_data("resource_assignment", $updateFlag, $conditionJobRoles);
                }
            } else {
                $infoOutput .= 'Projectcode ' . $projectCode . " Resource sudah ada<br/>";
            }
        } else {
            //$infoOutput .= 'Projectcode ' . $projectCode . " Resource IdWrike kosong<br/>";
            $infoOutput .= 'Projectcode ' . $projectCode . " Resource Belum accept invitation dari Wrike, mohon diinfokan agar mengaktifkan akun wrike pada resource.<br/>";
        }

        return $infoOutput;
    }

    function forwardAssignmentMTMandiri($rowDataResource = '')
    {
        $API_WRIKE = new API_WRIKE;
        $infoOutput = '';
        $userNameApprove = $_POST['userSession'];
        $db_wrike = 'WRIKE_INTEGRATE';
        $DBWR = get_conn($db_wrike);

        $db_workload = 'WORKLOAD';
        $DBWRKLD = get_conn($db_workload);

        $id = $rowDataResource['id'];
        $projectId = $rowDataResource['project_id'];
        $projectRoles = $rowDataResource['project_roles'];
        $projectCode = $rowDataResource['project_code'];
        $resourceEmail = $rowDataResource['resource_email'];
        $resourceRoles = $rowDataResource['roles'];
        $explodeResourceEmail = explode("<", $resourceEmail);
        $finalResourceEmail = str_replace(">", "", $explodeResourceEmail[1]);


        //Lookup Initial Jobroles Project
        $sqlLookupIJP = "SELECT * FROM sa_initial_jobroles_project WHERE project_id = '$projectId' AND jobroles_name LIKE '%$projectRoles%'";
        $dataLookupIJP = $DBWRKLD->get_sqlV2($sqlLookupIJP);
        $rowLookupIJP = $dataLookupIJP[0];

        if ($dataLookupIJP[2] == 0) {
            if ($projectRoles == 'PIC Maintenance') {
                $sqlLookupIJP2 = "SELECT * FROM sa_initial_jobroles_project WHERE project_id = '$projectId' AND jobroles_name LIKE '%PM Maintenance%'";
            } else if ($projectRoles == 'PM Maintenance') {
                $sqlLookupIJP2 = "SELECT * FROM sa_initial_jobroles_project WHERE project_id = '$projectId' AND jobroles_name LIKE '%PIC Maintenance%'";
            } else if ($projectRoles == 'Technical Team Member') {
                $sqlLookupIJP2 = "SELECT * FROM sa_initial_jobroles_project WHERE project_id = '$projectId' AND jobroles_name LIKE '%Technical Member%'";
            } else if ($projectRoles == 'Technical Member') {
                $sqlLookupIJP2 = "SELECT * FROM sa_initial_jobroles_project WHERE project_id = '$projectId' AND jobroles_name LIKE '%Technical Team Member%'";
            } else {
                echo "masuk sini 1";
                return "projectRolesId tidak tersedia $projectRoles <br/>
                SELECT * FROM sa_initial_jobroles_project WHERE project_id = '$projectId' AND jobroles_name LIKE '%$projectRoles%'";
            }
            $dataLookupIJP2 = $DBWRKLD->get_sqlV2($sqlLookupIJP2);
            $rowLookupIJP2 = $dataLookupIJP2[0];
            if ($dataLookupIJP2[2] == 0) {
                echo "masuk sini2";
                return "projectRolesId tidak tersedia $projectRoles <br/>
                SELECT * FROM sa_initial_jobroles_project WHERE project_id = '$projectId' AND jobroles_name LIKE '%$projectRoles%' <br>";
            }
            $projectRolesId = $rowLookupIJP2['job_task_id'];
        } else {
            $projectRolesId = $rowLookupIJP['job_task_id'];
        }

        //Lookup Wrike ID From Email
        $sqlWrikeId = "SELECT * FROM sa_contact_user WHERE email = '$finalResourceEmail'";
        $dataWrikeId = $DBWR->get_sqlV2($sqlWrikeId);
        $rowWrikeId = $dataWrikeId[0];

        if ($rowWrikeId == NULL) {
            $resourceWrikeId = "";
        } else {
            $resourceWrikeId = $rowWrikeId['id'];
        }

        if ($resourceWrikeId != '') {
            //Check Resource Jobroles
            $sqlCheckResourceJobrole = "SELECT * FROM sa_resource_jobroles WHERE project_id = '$projectId' AND resource_email = '$resourceEmail' AND job_roles LIKE '%$projectRoles%'";
            $executeSqlCheck = $DBWRKLD->get_sqlV2($sqlCheckResourceJobrole);
            $totalRowSqlCheck = $executeSqlCheck[2];

            if ($totalRowSqlCheck < 1) {

                //PUT ASSIGNMENT TASK
                $responsePut = $API_WRIKE->putApiWrikeAccessTask($projectRolesId, $resourceWrikeId);

                if (isset($responsePut['errorDescription'])) {
                    $infoOutput .= 'Projectcode ' . $projectCode . ' id ' . $projectId . ' Project Role ' . $responsePut['errorDescription'] . '</br>';
                } else {
                    $infoOutput .= 'Projectcode ' . $projectCode . ' id ' . $projectId . ' Project Role addResponsibles berhasil</br>';
                    // echo "TEEEEESSSSSTTT $id - $projectCode - $resourceEmail - $resourceRoles  - $resourceWrikeId<br/>";

                    $conditionJobRoles = "id = '$id'";
                    $updateFlag = sprintf(
                        "`flag_assign_wrike`= 2, `timestamp_wrike` = '" . date("Y-m-d H:i:s") . "', `approval_status`= 'approved', `modified_by` = '" . $userNameApprove . "', `modified_in_msizone` = '" . date("Y-m-d H:i:s") . "'",
                    );
                    $resUpdateFlag = $DBWR->update_data("resource_assignment", $updateFlag, $conditionJobRoles);
                }
            } else {
                $infoOutput .= 'Projectcode ' . $projectCode . " Resource sudah ada<br/>";
            }
        } else {
            //$infoOutput .= 'Projectcode ' . $projectCode . " Resource IdWrike kosong<br/>";
            $infoOutput .= 'Projectcode ' . $projectCode . " Resource Belum accept invitation dari Wrike, mohon diinfokan agar mengaktifkan akun wrike pada resource.<br/>";
        }

        return $infoOutput;
    }
}
