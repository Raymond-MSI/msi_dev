<?php


if (isset($_POST['add'])) {
    $insert = sprintf(
        "(`folder_id`,`customer_no`,`project_code`,`department`,`type`,`email_permission`,`level`) VALUES (%s,%s,%s,%s,%s,%s,%s)",
        GetSQLValueString($_POST['folder_id'], "text"),
        GetSQLValueString($_POST['customer_no'], "text"),
        GetSQLValueString($_POST['project_code'], "text"),
        GetSQLValueString($_POST['department'], "text"),
        GetSQLValueString($_POST['type'], "text"),
        GetSQLValueString($_POST['email_permission'], "text"),
        GetSQLValueString($_POST['level'], "text")
    );
    $res = $DB->insert_data($tblname, $insert);
    $ALERT->savedata();
} elseif (isset($_POST['save'])) {
    $condition = "folder_id=" . $_POST['folder_id'];
    $update = sprintf(
        "`folder_id`=%s,`customer_no`=%s,`project_code`=%s,`department`=%s,`type`=%s,`email_permission`=%s,`level`=%s",
        GetSQLValueString($_POST['folder_id'], "text"),
        GetSQLValueString($_POST['customer_no'], "text"),
        GetSQLValueString($_POST['project_code'], "text"),
        GetSQLValueString($_POST['department'], "text"),
        GetSQLValueString($_POST['type'], "text"),
        GetSQLValueString($_POST['email_permission'], "text"),
        GetSQLValueString($_POST['level'], "text")
    );
    $res = $DB->update_data($tblname, $update, $condition);
    $ALERT->savedata();
}

error_reporting(E_ERROR | E_PARSE);
echo 'Form 1';
require 'google_drive.php';

echo 'Sudah lewat Requireeee';

if (isset($_POST['submit'])) {
    $nama_folder = $_POST['name_folder'];
    echo 'cuk';

    // if( empty( $_FILES["file"]['tmp_name'] ) ){
    //     echo "Go back and Select file to upload.";
    //     exit;
    // }

    // $file_tmp  = $_FILES["file"]["tmp_name"];
    // $file_type = $_FILES["file"]["type"];
    // $file_name = basename($_FILES["file"]["name"]);
    // $path = "uploads/".$file_name;

    // move_uploaded_file($file_tmp, $path);

    $folder_id = drive_create_folder1("$nama_folder");
    echo 'cipuy';

    // $success = insert_file_to_drive( $path , $file_name, $folder_id );

    if ($folder_id) {
        echo "Folder created successfully";
    } else {
        echo "Something went wrong.";
    }
}

if (isset($_POST['submit1'])) {
    $project_code = $_POST['project_code'];
    $parents_id = $_POST['parents_id'];

    //$permission_email = $_POST['permission_email'];
    $folder_id1 = drive_create_folder2("$project_code", "$parents_id");
    // $subfolder_id = drive_create_folder1("B", "$parents_id");
    // $subfolder_id = drive_create_folder1("C", "$parents_id");

    // $subfolder_id->GetID();

    // return $service->permissions->create($fileId /* $fileId->getId(); */ , $newPermission);

    if ($subfolder_id2) {
        echo "Sub Folder created successfully";
    } else {
        echo "Something went wrong.";
    }
}

if (isset($_POST['submit2'])) {
    $parent_folder_id = $_POST['parent_id'];
    $email = $_POST['email'];
    // $permission_email = $_POST['permission_email'];

    $permission = insertPermission("$parent_folder_id", "$email");

    if ($folder_id) {
        echo "Folder created successfully";
    } else {
        echo "Something went wrong.";
    }
}

function get_customer_data_sb()
{
    global $DBGD, $DBGD_SB;
    $db_sb = "SERVICE_BUDGET";
    // $DBSB = get_conn($db_sb);
    $tblname = 'trx_project_list';
    $tblfolder = 'folder_customer';

    $condition = "status = 'approved' OR STATUS = 'acknowledge' AND CAST(modified_date AS DATE) = DATE_ADD(CAST(NOW() AS DATE),INTERVAL -1 DAY)";
    // 
    $sb_data = $DBGD_SB->get_data_distinct($tblname, $condition);

    do {
        $customer_code = $sb_data[0]['customer_code'];
        $customer_name = $sb_data[0]['customer_name'];
        // echo "$customer_code - $customer_name  <br/>";

        $condition_check = "customer_code = '$customer_code'";
        $testdata = $DBGD->get_data($tblfolder, $condition_check);

        if ($testdata[2] > 0) {
            //Query Update
            $update = sprintf(
                "`customer_code`='$customer_code',`customer_name`='$customer_name'",
                GetSQLValueString($customer_code, "text"),
                GetSQLValueString($customer_name, "text")
            );

            $res1 = $DBGD->update_data($tblfolder, $update, $condition_check);
        } else {
            if ($customer_code != '') {
                //Query Insert
                $insert = sprintf(
                    "(`customer_code`,`customer_name`) VALUES ('$customer_code', '$customer_name')",
                    GetSQLValueString($customer_code, "text"),
                    GetSQLValueString($customer_name, "text")
                );
                $res = $DBGD->insert_data($tblfolder, $insert);
            }
        }
    } while ($sb_data[0] = $sb_data[1]->fetch_assoc());
}

function get_db_data()
{
    global $DBGD;
    $db_sb = "SERVICE_BUDGET";
    $DBSB = get_conn($db_sb);
    $tblname = 'trx_project_list';
    $tblfolder = 'folder_project';

    $condition = "status = 'approved' OR STATUS = 'acknowledge' AND CAST(modified_date AS DATE) = DATE_ADD(CAST(NOW() AS DATE),INTERVAL -1 DAY)";
//
    $sb_data = $DBSB->get_data($tblname, $condition);

    do {
        $project_code = $sb_data[0]['project_code'];
        $project_name = $sb_data[0]['project_name'];
        $customer_code = $sb_data[0]['customer_code'];
        $customer_name = $sb_data[0]['customer_name'];
        echo "$project_code - $project_name - $customer_code - $customer_name  <br/>";

        $condition_check = "project_code = '$project_code'";
        $testdata = $DBGD->get_data($tblfolder, $condition_check);

        if ($testdata[2] > 0) {
            //Query Update
            $update = sprintf(
                "`project_code`='$project_code',`project_name`='$project_name',`customer_code`='$customer_code',`customer_name`='$customer_name'",
                GetSQLValueString($project_code, "text"),
                GetSQLValueString($project_name, "text"),
                GetSQLValueString($customer_code, "text"),
                GetSQLValueString($customer_name, "text")
            );

            $res1 = $DBGD->update_data($tblfolder, $update, $condition_check);
        } else {
            if ($project_code != '') {
                //Query Insert
                $insert = sprintf(
                    "(`project_code`,`project_name`,`customer_code`,`customer_name`) VALUES ('$project_code', '$project_name', '$customer_code', '$customer_name')",
                    GetSQLValueString($project_code, "text"),
                    GetSQLValueString($project_name, "text"),
                    GetSQLValueString($customer_code, "text"),
                    GetSQLValueString($customer_name, "text")
                );
                $res = $DBGD->insert_data($tblfolder, $insert);
            }
        }
    } while ($sb_data[0] = $sb_data[1]->fetch_assoc());
}

function auto_post_gd()
{
    global $DBGD;
    $tblfolder = 'folder_project';
    $tblcustomer = 'folder_customer';
    // $condition= "project_id =" . $_GET['project_id'];
    $get_data_sb = $DBGD->get_data($tblcustomer);
    //$get_data_sb = $DBGD->get_data($tblfolder);
    $row = $get_data_sb[0];
    $res = $get_data_sb[1];
    $totalRow = $get_data_sb[2];

    do {
        // $project_code = $row['project_code'];
        // $project_name = $row['project_name'];
        $customer_code = $row['customer_code'];
        $customer_name = $row['customer_name'];
        $flag = $row['flag'];

        $service = new Google_Service_Drive($GLOBALS['client']);
        $folder = new Google_Service_Drive_DriveFile();
        $folder1 = new Google_Service_Drive_DriveFile();

        // echo "$customer_code $customer_name  <br/>";

        // if folder does not exists

        $folder_name = "$customer_code" . "_"  . "$customer_name";
        //$folder_list = check_folder_exists($folder_name);

        //count($folder_list) == 0 && 
        if ($flag == 0) {
            echo "$customer_code - $customer_name - $flag <br/>";
            $service = new Google_Service_Drive($GLOBALS['client']);
            $folder = new Google_Service_Drive_DriveFile();
            $folder1 = new Google_Service_Drive_DriveFile();
            $parents = "1XktdHB4HGHiVx3N45eo73r_T22cLmA9V";

            $folder->setName($folder_name);
            $folder->setMimeType('application/vnd.google-apps.folder');
            $folder->setDriveId($parents);
            $folder->setTeamDriveId($parents);
            $folder->setParents([$parents]);

            $optparams = [
                "supportsAllDrives" => true,
                "supportsTeamDrives" => true
            ];

            $result = $service->files->create($folder, $optparams);

            $folder_id = null;
            $folder_id = $result['id'];
            echo "$folder_name - $folder_id <br/>";

            $condition_check = "customer_code = '$customer_code'";
            $testdata = $DBGD->get_data($tblcustomer, $condition_check);

            //Query Update
            $update = sprintf(
                "`gd_id` = '$folder_id', `flag`=1",
                GetSQLValueString($folder_id, "text")
            );

            $res1 = $DBGD->update_data($tblcustomer, $update, $condition_check);

            //else{
            //Query Insert
            //$insert = sprintf("(`gd_id`) VALUES ('$folder_id')",
            //GetSQLValueString($folder_id, "text")
            //);

            //$res = $DBGD->insert_data($tblcustomer, $insert);
            //}
        }
    } while ($row = $res->fetch_assoc());
}

function auto_post_gd_project()
{
    global $DBGD;
    $tblfolder = 'folder_project';
    $tblcustomer = 'folder_customer';
    // $condition= "project_id =" . $_GET['project_id'];
    $get_data_sb = $DBGD->get_data_project($tblfolder, $tblcustomer);
    //$get_data_sb = $DBGD->get_data($tblfolder);
    $row = $get_data_sb[0];
    $res = $get_data_sb[1];
    $totalRow = $get_data_sb[2];

    echo "Cek Masukkk <br/>";

    do {
        $project_code = $row['project_code'];
        $project_name = $row['project_name'];
        $customer_id = $row['gd_id_cust'];
        $flag    = $row['status'];

        if ($flag == "Open") {
            $service = new Google_Service_Drive($GLOBALS['client']);
            $folder = new Google_Service_Drive_DriveFile();
            $folder1 = new Google_Service_Drive_DriveFile();

            $subfolder_name = $project_code;

            $folder_list1 = check_folder_exists($subfolder_name);

            $condition_check = "project_code = '$project_code'";
            $testdata = $DBGD->get_data($tblfolder, $condition_check);
            $gdProjectId = $testdata[0]['gd_id'];

            if ($gdProjectId == NULL || $gdProjectId == '') {
                if (count($folder_list1) == 0) {
                    //Subfolder 1 under Parent Folder

                    echo "$subfolder_name - $project_name - $flag - $customer_id <br/>";

                    $folder->setName($subfolder_name);
                    $folder->setMimeType('application/vnd.google-apps.folder');
                    $folder->setParents([$customer_id]);

                    $optparams = [
                        "supportsAllDrives" => true,
                        "supportsTeamDrives" => true
                    ];

                    $result1 = $service->files->create($folder, $optparams);

                    $folder_id = null;
                    $folder_id = $result1['id'];
                    // echo "$folder_id";

                    //Query Update

                    $condition_check = "project_code = '$project_code'";
                    // $testdata = $DBGD->get_data($tblfolder, $condition_check);
                    // $gdProjectId = $testdata[0]['gd_id'];

                    $updateGdIdStatus = sprintf(
                        "`gd_id`='$folder_id', `status`='close'",
                        GetSQLValueString($folder_id, "text")
                    );

                    $res1 = $DBGD->update_data($tblfolder, $updateGdIdStatus, $condition_check);

                    // Tutup folderlist1
                }
            } else if ($gdProjectId != '') {
                echo "$subfolder_name - $project_name - $flag - $customer_id - sudah ada Gd Id <br/>";

                $updateStatus = sprintf(
                    "`status`='close'"
                );

                $resStatus = $DBGD->update_data($tblfolder, $updateStatus, $condition_check);
            }

            if ($project_code != '') {
                auto_post_gd_department($project_code);
            } else {
                echo "Tidak terdapat project code";
            }
        }
    } while ($row = $res->fetch_assoc());
}

function auto_post_gd_department($project_code)
{
    global $DBGD;

    //Subfolder Department under Subfolder1 Folder

    // $result_id2 = $result1->getId($project_code);
    $tbl_project = 'folder_project';
    $tbl_dep = 'department';
    $tbl_projectdetail = 'project_detail';
    $condition = 'flag_active_folder = 1';
    $get_department = $DBGD->get_data($tbl_dep, $condition);
    $row1 = $get_department[0];
    $res1 = $get_department[1];
    $totalRow1 = $get_department[2];

    $condition_project = "project_code = '$project_code'";
    $get_gdid_project = $DBGD->get_data($tbl_project, $condition_project);
    $gd_id = $get_gdid_project[0]['gd_id'];
    // echo $gd_id;

    do {
        $folder_id = $row1['folder_id'];
        $folder_name = $row1['folder_name'];
        $folder_level = $row1['folder_level'];
        $folder_id_parent = $row1['folder_id_parent'];

        $service = new Google_Service_Drive($GLOBALS['client']);
        $folder = new Google_Service_Drive_DriveFile();
        $folder1 = new Google_Service_Drive_DriveFile();

        $checkProjectDetail = "project_code = '$project_code' AND folder_name = '$folder_name' and folder_id='$folder_id'";
        $dataProjectDetail = $DBGD->get_data($tbl_projectdetail, $checkProjectDetail);
        $folderNameProjectDetail = $dataProjectDetail[0]['folder_name'];
        $projectCodeProjectDetail = $dataProjectDetail[0]['project_code'];
        $folderId = $dataProjectDetail[0]['folder_id'];

        if ($projectCodeProjectDetail == $project_code && $folderNameProjectDetail == $folder_name && $folderId == $folder_id) {
            echo "Data $projectCodeProjectDetail - $folderNameProjectDetail - $folderId sudah ada <br/>";
        } else {
            if ($folder_id_parent == NULL) {
                $folder1->setName($folder_name);
                $folder1->setMimeType('application/vnd.google-apps.folder');
                $folder1->setParents([$gd_id]);

                $optparams = [
                    "supportsAllDrives" => true,
                    "supportsTeamDrives" => true
                ];

                $level1_folder = $service->files->create($folder1, $optparams);

                $level1_folder_id = null;
                $level1_folder_id = $level1_folder['id'];

                $insert_dept = sprintf(
                    "(`project_code`,`folder_name`,`folder_id`,`gd_id`) VALUES ('$project_code','$folder_name','$folder_id','$level1_folder_id')",
                    GetSQLValueString($project_code, "text"),
                    GetSQLValueString($folder_name, "text"),
                    GetSQLValueString($folder_id, "text"),
                    GetSQLValueString($level1_folder_id, "text")
                );

                $res_dept = $DBGD->insert_data($tbl_projectdetail, $insert_dept);
            } else {
                $condition_detail = "project_code = '$project_code' AND folder_id = '$folder_id_parent'";
                $get_project_detail = $DBGD->get_data($tbl_projectdetail, $condition_detail);
                $gd_id_parent = $get_project_detail[0]['gd_id'];

                if ($folder_level == 3) {
                    $folder1->setName($project_code . " " . $folder_name);
                } else {
                    $folder1->setName($folder_name);
                }

                $folder1->setMimeType('application/vnd.google-apps.folder');
                $folder1->setParents([$gd_id_parent]);

                $optparams = [
                    "supportsAllDrives" => true,
                    "supportsTeamDrives" => true
                ];

                $level2_folder = $service->files->create($folder1, $optparams);

                $level2_folder_id = null;
                $level2_folder_id = $level2_folder['id'];

                $insert_level2 = sprintf(
                    "(`project_code`,`folder_name`,`folder_id`,`gd_id`) VALUES ('$project_code','$folder_name','$folder_id','$level2_folder_id')",
                    GetSQLValueString($project_code, "text"),
                    GetSQLValueString($folder_name, "text"),
                    GetSQLValueString($folder_id, "text"),
                    GetSQLValueString($level2_folder_id, "text")
                );

                $res_level2 = $DBGD->insert_data($tbl_projectdetail, $insert_level2);
            }
        }
    } while ($row1 = $res1->fetch_assoc());
}

function permissionFolder()
{

    global $DBGD;

    $tbl_folder_project = 'folder_project';
    $project = $DBGD->get_data($tbl_folder_project);
    $row_folder_project = $project[0];
    $res_folder_project = $project[1];
    $totalRow_folder_project = $project[2];

    $tbl_role_access = 'role_access';
    $condition_role_access = "access_role != ''";
    $role_access = $DBGD->get_data($tbl_role_access, $condition_role_access);
    $row = $role_access[0];
    $res = $role_access[1];
    $totalRow = $role_access[2];

    $tbl_user_group = 'user_group';
    $condition_user_group = "group_mail != ''";
    $user_group = $DBGD->get_data($tbl_user_group, $condition_user_group);
    $row_user_group = $user_group[0];
    $res_user_group = $user_group[1];
    $totalRow_user_group = $user_group[2];

    $tbl_project_list = 'project_detail';
    $condition_project_list = "permission_flag=0";
    $project_list = $DBGD->get_data($tbl_project_list, $condition_project_list);
    $row_project_list = $project_list[0];
    $res_project_list = $project_list[1];
    $totalRow_project_list = $project_list[2];


    $tbl_project_folder_access = 'project_folder_access';

    // do{
    //     $array_folder_project[] = $row_folder_project;

    // }while($row_folder_project = $res_folder_project->fetch_assoc());

    // var_dump($array_folder_project);

    do {
        $array_role_access[] = $row;
    } while ($row = $res->fetch_assoc());

    // var_dump($array_role_access);

    do {

        $array_user_group[] = $row_user_group;
    } while ($row_user_group = $res_user_group->fetch_assoc());

    // var_dump($array_user_group);

    // do{
    //     $array_project_list[] = $row_project_list;

    // }while($row_project_list=$res_project_list->fetch_assoc());

    // var_dump($array_project_list);


    do {
        $project_code = $row_project_list['project_code'];
        $folder_name = $row_project_list['folder_name'];
        $flag = $row_project_list['flag'];
        $gd_id = $row_project_list['gd_id'];

        // echo "$project_code - $folder_name <br/>";
        // $gd_id_selesai = '';
        $permissionFlag = 2;

        for ($x = 0; $x < count($array_role_access); $x++) {
            $group_code_role_access = $array_role_access[$x]['group_code'];
            $folder_name_role_access = $array_role_access[$x]['folder_name'];
            $access_role = $array_role_access[$x]['access_role'];

            for ($i = 0; $i < count($array_user_group); $i++) {
                $group_code_user_group = $array_user_group[$i]['group_code'];
                $group_mail_user_group = $array_user_group[$i]['group_mail'];

                if ($group_code_role_access == $group_code_user_group && $folder_name == $folder_name_role_access) {

                    //Berhenti Sampai disini
                    $service = new Google_Service_Drive($GLOBALS['client']);
                    $newPermission = new Google_Service_Drive_Permission();
                    $newPermission->setEmailAddress($group_mail_user_group);
                    $newPermission->setType('user');
                    $newPermission->setRole($access_role);

                    $optparams = [
                        "supportsAllDrives" => true,
                        "supportsTeamDrives" => true,
                        "transferOwnership" => false,
                        "sendNotificationEmail" => false
                    ];

                    $permissionFolder = $service->permissions->create("$gd_id" /* $fileId->getId(); */, $newPermission, $optparams);

                    //    $gd_id_selesai = $gd_id;  
                    $permissionFlag = 1;
                }
            }
        }

        $conditionProjectDetail = "gd_id = '$gd_id'";
        $updateFlagProjectDetail = sprintf("`permission_flag`= $permissionFlag");

        $updateFlagProjectDetail = $DBGD->update_data($tbl_project_list, $updateFlagProjectDetail, $conditionProjectDetail);

        //         for($i = 0 ; $i < count($array_user_group) ; $i++){
        //             $group_code_user_group = $array_user_group[$i]['group_code'];
        //             $group_mail_user_group = $array_user_group[$i]['group_mail'];
        //         for($x = 0 ; $x < count($array_role_access) ; $x++){
        //             $group_code = $array_role_access[$x]['group_code'];
        //             $folder_name= $array_role_access[$x]['folder_name'];
        //             $access_role = $array_role_access[$x]['access_role'];



        //                 // $group_mail_access_role = $access_role;
        //                 if($group_code_user_group == $group_code){
        //                  $condition_project_list1 = "project_code = '$project_code' && folder_name = '$folder_name' && permission_flag = 0"; 
        //                  $project_listed = $DBGD->get_data($tbl_project_list, $condition_project_list1);
        //                  $folder_name_project_listed = $project_listed[0]['folder_name'];
        //                  $gd_id = $project_listed[0]['gd_id'];

        //                  if ($folder_name == $folder_name_project_listed && $group_mail_user_group != ""){
        //                     //  echo $project_code . "-" . $folder_name . "-" . $folder_name_project_listed . "-" . $group_mail_user_group . "-" . $access_role .  "-". $gd_id ."<br/>" ;

        //                     //  //Berhenti Sampai disini
        //                     //  $service = new Google_Service_Drive($GLOBALS['client']);

        //                     //  $newPermission = new Google_Service_Drive_Permission();
        //                     //  $newPermission->setEmailAddress($group_mail_user_group);
        //                     //  $newPermission->setType('user');
        //                     //  $newPermission->setRole($access_role);

        //                     //  $optparams = [
        //                     //      "supportsAllDrives" => true,
        //                     //      "supportsTeamDrives" => true,
        //                     //      "transferOwnership" => false,
        //                     //      "sendNotificationEmail" => false
        //                     //  ];

        //                     //  $permissionFolder = $service->permissions->create("$gd_id" /* $fileId->getId(); */ , $newPermission, $optparams);
        //                  }

        //             }

        //          }  
        // }
    } while ($row_project_list = $res_project_list->fetch_assoc());


    // for($z = 0 ; $z < count($array_folder_project) ; $z++){
    //         $project_code = $array_folder_project[$z]['project_code'];
    //      do{
    //          $group_code = $row['group_code'];
    //          $folder_name= $row['folder_name'];
    //          $access_role = $row['access_role'];

    //             for($i = 0 ; $i < count($array_user_group) ; $i++){
    //                 $group_code_user_group = $array_user_group[$i]['group_code'];
    //                 $group_mail_user_group = $array_user_group[$i]['group_mail'];

    //                 // $group_mail_access_role = $access_role;
    //                 if($group_code_user_group == $group_code){
    //                  $condition_project_list1 = "project_code = '$project_code' && folder_name = '$folder_name' && permission_flag = 0"; 
    //                  $project_listed = $DBGD->get_data($tbl_project_list, $condition_project_list1);
    //                  $folder_name_project_listed = $project_listed[0]['folder_name'];
    //                  $gd_id = $project_listed[0]['gd_id'];

    //                  if ($folder_name == $folder_name_project_listed && $group_mail_user_group != ""){
    //                      //echo $project_code . "-" . $group_mail_user_group . "-" . $access_role . "-" . $folder_name . "-" . $folder_name_project_listed . "-". $gd_id ."<br/>" ;

    //                     //  //Berhenti Sampai disini
    //                     //  $service = new Google_Service_Drive($GLOBALS['client']);

    //                     //  $newPermission = new Google_Service_Drive_Permission();
    //                     //  $newPermission->setEmailAddress($group_mail_user_group);
    //                     //  $newPermission->setType('user');
    //                     //  $newPermission->setRole($access_role);

    //                     //  $optparams = [
    //                     //      "supportsAllDrives" => true,
    //                     //      "supportsTeamDrives" => true,
    //                     //      "transferOwnership" => false,
    //                     //      "sendNotificationEmail" => false
    //                     //  ];

    //                     //  $permissionFolder = $service->permissions->create("$gd_id" /* $fileId->getId(); */ , $newPermission, $optparams);
    //                  }

    //             }

    //          }  

    //      }while($row=$res->fetch_assoc());
    //     //echo "<br/> Tutup <br/>";
    //     }
    //     //  $conditionProjectDetail = "project_code = '$project_code' && folder_name = '$folder_name_project_listed'";
    //     //  $updateFlagProjectDetail = sprintf("`permission_flag`=1");

    //     // $updateFlagProjectDetail = $DBGD->update_data($tbl_project_list, $updateFlagProjectDetail, $conditionProjectDetail);
}

function delete_folder()
{
    global $DBGD;

    //Delete Folder Level 5
    $querySQLLevel5 = 'SELECT * FROM sa_project_detail
    WHERE folder_id IN (22,23,24,49,50,51,52,53,54)';
    $tbl_project_detail = 'project_detail';
    $dataDeleteLevel5 = $DBGD->get_sql($querySQLLevel5);
    $rowDataDeleteLevel5 = $dataDeleteLevel5[0];
    $resDataDeleteLevel5 = $dataDeleteLevel5[1];
    do {
        $projectCodeLevel5 = $rowDataDeleteLevel5['project_code'];
        $folderIdLevel5 = $rowDataDeleteLevel5['folder_id'];
        $folderNameLevel5 = $rowDataDeleteLevel5['folder_name'];
        $gdIdLevel5 = $rowDataDeleteLevel5['gd_id'];

        echo "$projectCodeLevel5 - $folderIdLevel5 - $folderNameLevel5 - $gdIdLevel5<br/>";

        $service = new Google_Service_Drive($GLOBALS['client']);
        $optparams = [
            "supportsAllDrives" => true,
            "supportsTeamDrives" => true
        ];

        try {
            $deleteLevel5 = $service->files->delete($gdIdLevel5, $optparams);
            if($deleteLevel5){
                echo "$projectCodeLevel5 - $folderIdLevel5 - $folderNameLevel5 - $gdIdLevel5 Level 5 Telah Dihapus<br/>";
            }
            $conditionDeleteLevel5 = "project_code = '$projectCodeLevel5' AND folder_id = '$folderIdLevel5'";
            $deleteRecordLevel5 = $DBGD->delete_data($tbl_project_detail, $conditionDeleteLevel5);
        } catch (Exception $e) {
            print "An error occurred: " . $e->getMessage();
        }

    } while ($rowDataDeleteLevel5 = $resDataDeleteLevel5->fetch_assoc());

    echo "<br/><br/>";

    //Delete Folder Level 4
    $querySQLLevel4 = 'SELECT * FROM sa_project_detail WHERE folder_id IN 
    (21,25,26,30,31,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70)';
    $tbl_project_detail = 'project_detail';
    $dataDeleteLevel4 = $DBGD->get_sql($querySQLLevel4);
    $rowDataDeleteLevel4 = $dataDeleteLevel4[0];
    $resDataDeleteLevel4 = $dataDeleteLevel4[1];
    do {
        $projectCodeLevel4 = $rowDataDeleteLevel4['project_code'];
        $folderIdLevel4 = $rowDataDeleteLevel4['folder_id'];
        $folderNameLevel4 = $rowDataDeleteLevel4['folder_name'];
        $gdIdLevel4 = $rowDataDeleteLevel4['gd_id'];

        echo "$projectCodeLevel4 - $folderIdLevel4 - $folderNameLevel4 - $gdIdLevel4<br/>";

        $service = new Google_Service_Drive($GLOBALS['client']);
        $optparams = [
            "supportsAllDrives" => true,
            "supportsTeamDrives" => true
        ];

        try {
            $deleteLevel4 = $service->files->delete($gdIdLevel4, $optparams);
            if($deleteLevel4){
                echo "$projectCodeLevel4 - $folderIdLevel4 - $folderNameLevel4 - $gdIdLevel4 Level 4 Telah Dihapus<br/>";
            }
            $conditionDeleteLevel4 = "project_code = '$projectCodeLevel4' AND folder_id = '$folderIdLevel4'";
            $deleteRecordLevel4 = $DBGD->delete_data($tbl_project_detail, $conditionDeleteLevel4);
        } catch (Exception $e) {
            print "An error occurred: " . $e->getMessage();
        }

    } while ($rowDataDeleteLevel4 = $resDataDeleteLevel4->fetch_assoc());

    echo "<br/><br/>";

    //DELETE FOLDER LEVEL 3
    $querySQLLevel3 = 'SELECT * FROM sa_project_detail WHERE folder_id IN 
    (27,28,29,32,55)';
    $tbl_project_detail = 'project_detail';
    $dataDeleteLevel3 = $DBGD->get_sql($querySQLLevel3);
    $rowDataDeleteLevel3 = $dataDeleteLevel3[0];
    $resDataDeleteLevel3 = $dataDeleteLevel3[1];
    do {
        $projectCodeLevel3 = $rowDataDeleteLevel3['project_code'];
        $folderIdLevel3 = $rowDataDeleteLevel3['folder_id'];
        $folderNameLevel3 = $rowDataDeleteLevel3['folder_name'];
        $gdIdLevel3 = $rowDataDeleteLevel3['gd_id'];

        echo "$projectCodeLevel3 - $folderIdLevel3 - $folderNameLevel3 - $gdIdLevel3<br/>";

        $service = new Google_Service_Drive($GLOBALS['client']);
        $optparams = [
            "supportsAllDrives" => true,
            "supportsTeamDrives" => true
        ];

        try {
            $deleteLevel3 = $service->files->delete($gdIdLevel3, $optparams);
            if($deleteLevel3){
                echo "$projectCodeLevel3 - $folderIdLevel3 - $folderNameLevel3 - $gdIdLevel3 Level 3 Telah Dihapus<br/>";
            }
            $conditionDeleteLevel3 = "project_code = '$projectCodeLevel3' AND folder_id = '$folderIdLevel3'";
            $deleteRecordLevel3 = $DBGD->delete_data($tbl_project_detail, $conditionDeleteLevel3);
        } catch (Exception $e) {
            print "An error occurred: " . $e->getMessage();
        }
    } while ($rowDataDeleteLevel3 = $resDataDeleteLevel3->fetch_assoc());
}

function auto_post_gd_crm($customerCode, $customerName, $projectCode, $projectName, $emailGroup, $emailSales, $token)
{
    global $DBGD;

    $customerName = str_replace("_", " ", "$customerName");
    $projectName = str_replace("_", " ", "$projectName");

    if ($token == '791700914907') {
        //Validasi Check Customer Code
        $querySQLCustomer = "SELECT * FROM sa_folder_customer WHERE customer_code = '$customerCode'";
        $dataSQLCustomer = $DBGD->get_sql($querySQLCustomer);
        $rowSQLCustomer = $dataSQLCustomer[0];
        $totalRowSQLCustomer = $dataSQLCustomer[2];
        $customerGdId = $rowSQLCustomer['gd_id'];

        if ($totalRowSQLCustomer > 0) {
            //If Customer Existing
            echo "<br/><br/>Folder Customer dengan ID : " . $rowSQLCustomer['customer_code'] . " - " . $rowSQLCustomer['customer_name'] . " telah terdapat folder dengan <br/>Link : https://drive.google.com/drive/u/0/folders/$customerGdId <br/><br/>";
            //Validasi Check Project
            $querySQLProject = "SELECT * FROM sa_folder_project WHERE project_code = '$projectCode' AND customer_code='$customerCode'";
            $dataSQLProject = $DBGD->get_sql($querySQLProject);
            $rowSQLProject = $dataSQLProject[0];
            $totalRowSQLProject = $dataSQLProject[2];
            $projectGdId = $rowSQLProject['gd_id'];

            $queryCheckSalesFolder = "SELECT * FROM sa_project_detail WHERE project_code = '$projectCode' AND folder_name = 'Sales'";
            $dataCheckSalesFolder = $DBGD->get_sql($queryCheckSalesFolder);
            $salesFolderId = $dataCheckSalesFolder[0]['gd_id'];

            if ($totalRowSQLProject > 0) {
                //If Project Existing
                echo "Project $projectCode - $projectName sudah existing dengan <br/>Link : https://drive.google.com/drive/u/0/folders/$projectGdId<br/>";
                echo "Link Group Sales : https://drive.google.com/drive/u/0/folders/$salesFolderId<br/><br/>";
            } else {
                //If Customer Existing But Project Not Existing
                $serviceProject = new Google_Service_Drive($GLOBALS['client']);
                $folderProject = new Google_Service_Drive_DriveFile();
                $folderProjectName = "$projectCode";
                $folderProject->setName($folderProjectName);
                $folderProject->setMimeType('application/vnd.google-apps.folder');
                $folderProject->setParents([$customerGdId]);

                $optparams = [
                    "supportsAllDrives" => true,
                    "supportsTeamDrives" => true
                ];

                $resultProject = $serviceProject->files->create($folderProject, $optparams);

                $folder_id_project = null;
                $folder_id_project = $resultProject['id'];

                $tbl_folder_project = 'folder_project';
                $insertProject = sprintf(
                    "(`project_code`,`project_name`,`customer_code`,`customer_name`, `gd_id`, `status`) VALUES ('$projectCode','$projectName','$customerCode','$customerName','$folder_id_project', 'close')",
                    GetSQLValueString($projectCode, "text"),
                    GetSQLValueString($projectName, "text"),
                    GetSQLValueString($customerCode, "text"),
                    GetSQLValueString($customerName, "text"),
                    GetSQLValueString($folder_id_project, "text")
                );

                $resProject = $DBGD->insert_data($tbl_folder_project, $insertProject);

                auto_post_gd_department($projectCode);

                //Get Google Drive Id Sales
                $querySQLPermission = "SELECT * FROM sa_project_detail WHERE project_code = '$projectCode' AND folder_name = 'Sales'";
                $dataSQLPermission = $DBGD->get_sql($querySQLPermission);
                $salesGdId = $dataSQLPermission[0]['gd_id'];

                //Permission If Customer Existing But New Project
                $servicePermission = new Google_Service_Drive($GLOBALS['client']);
                $newPermission = new Google_Service_Drive_Permission();
                $newPermission->setEmailAddress($emailGroup);
                $newPermission->setType('user');
                $newPermission->setRole('fileOrganizer');

                $optparams = [
                    "supportsAllDrives" => true,
                    "supportsTeamDrives" => true,
                    "transferOwnership" => false,
                    "sendNotificationEmail" => false
                ];

                $permissionFolder = $servicePermission->permissions->create("$salesGdId" /* $fileId->getId(); */, $newPermission, $optparams);

                $tbl_folder_project_crm = 'folder_project_crm';
                $insertProjectCRM = sprintf(
                    "(`customer_code`,`customer_name`,`project_code`, `email_sales`, `email_group`, `link_folder_sales`, `flag`) VALUES ('$customerCode','$customerName','$projectCode','$emailSales','$emailGroup', 'https://drive.google.com/drive/u/0/folders/$salesGdId', 1)",
                    GetSQLValueString($projectCode, "text"),
                    GetSQLValueString($customerCode, "text"),
                    GetSQLValueString($customerName, "text"),
                    GetSQLValueString($emailSales, "text"),
                    GetSQLValueString($emailGroup, "text"),
                    GetSQLValueString($salesGdId, "text")
                );

                $resProjectCRM = $DBGD->insert_data($tbl_folder_project_crm, $insertProjectCRM);

                echo "Project $projectCode - $projectName sudah dibuat dengan <br/>Link : https://drive.google.com/drive/u/0/folders/$folder_id_project<br/><br/>";
                echo "Link Group Sales : https://drive.google.com/drive/u/0/folders/$salesGdId<br/>";
            }
        } else {
            //If Customer Not Exist
            $service = new Google_Service_Drive($GLOBALS['client']);
            $folder = new Google_Service_Drive_DriveFile();
            $folder1 = new Google_Service_Drive_DriveFile();
            $parents = "1YdCyhnSu3Lt4O0mnfkzNKGYxlepe8Jec";

            $folderName = $customerCode . "_" . $customerName;

            $folder->setName($folderName);
            $folder->setMimeType('application/vnd.google-apps.folder');
            $folder->setDriveId($parents);
            $folder->setTeamDriveId($parents);
            $folder->setParents([$parents]);

            $optparams = [
                "supportsAllDrives" => true,
                "supportsTeamDrives" => true
            ];

            $result = $service->files->create($folder, $optparams);

            $folder_id_customer = null;
            $folder_id_customer = $result['id'];

            $tbl_folder_customer = 'folder_customer';
            $insertCustomer = sprintf(
                "(`customer_code`,`customer_name`,`gd_id`,`flag`) VALUES ('$customerCode','$customerName','$folder_id_customer',1)",
                GetSQLValueString($customerCode, "text"),
                GetSQLValueString($customerName, "text"),
                GetSQLValueString($folder_id_customer, "text")
            );

            $resCustomer = $DBGD->insert_data($tbl_folder_customer, $insertCustomer);

            echo "<br/>Folder $customerCode - $customerName sudah dibuat dengan <br/> Link : https://drive.google.com/drive/u/0/folders/$folder_id_customer<br/><br/>";

            //Validasi Check Project
            $querySQLProject = "SELECT * FROM sa_folder_project WHERE project_code = '$projectCode' AND customer_code='$customerCode'";
            $dataSQLProject = $DBGD->get_sql($querySQLProject);
            $gdProjectId = $dataSQLProject[0]['gd_id'];
            $totalRowSQLProject = $dataSQLProject[2];

            if ($totalRowSQLProject > 0) {
                //If Project Already Existing Even Customer Folder New, The Folder Will Dont Create
                echo "Project $projectCode - $projectName sudah existing dengan <br/>Link : https://drive.google.com/drive/u/0/folders/$gdProjectId<br/>";
            } else {
                //Create Project With New Customer Folder
                if ($gdProjectId == NULL || $gdProjectId == '') {
                    $serviceProject = new Google_Service_Drive($GLOBALS['client']);
                    $folderProject = new Google_Service_Drive_DriveFile();
                    $folderProjectName = "$projectCode";
                    $folderProject->setName($folderProjectName);
                    $folderProject->setMimeType('application/vnd.google-apps.folder');
                    $folderProject->setParents([$folder_id_customer]);

                    $optparams = [
                        "supportsAllDrives" => true,
                        "supportsTeamDrives" => true
                    ];

                    $resultProject = $serviceProject->files->create($folderProject, $optparams);

                    $folder_id_project = null;
                    $folder_id_project = $resultProject['id'];

                    $tbl_folder_project = 'folder_project';
                    $insertProject = sprintf(
                        "(`project_code`,`project_name`,`customer_code`,`customer_name`, `gd_id`, `status`) VALUES ('$projectCode','$projectName','$customerCode','$customerName','$folder_id_project', 'close')",
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($projectName, "text"),
                        GetSQLValueString($customerCode, "text"),
                        GetSQLValueString($customerName, "text"),
                        GetSQLValueString($folder_id_project, "text")
                    );

                    $resProject = $DBGD->insert_data($tbl_folder_project, $insertProject);

                    auto_post_gd_department($projectCode);

                    //Get Google Drive Id Sales
                    $querySQLPermission = "SELECT * FROM sa_project_detail WHERE project_code = '$projectCode' AND folder_name = 'Sales'";
                    $dataSQLPermission = $DBGD->get_sql($querySQLPermission);
                    $salesGdId = $dataSQLPermission[0]['gd_id'];

                    //Permission If Customer Existing But New Project
                    $servicePermission = new Google_Service_Drive($GLOBALS['client']);
                    $newPermission = new Google_Service_Drive_Permission();
                    $newPermission->setEmailAddress($emailGroup);
                    $newPermission->setType('user');
                    $newPermission->setRole('fileOrganizer');

                    $optparams = [
                        "supportsAllDrives" => true,
                        "supportsTeamDrives" => true,
                        "transferOwnership" => false,
                        "sendNotificationEmail" => false
                    ];

                    $permissionFolder = $servicePermission->permissions->create("$salesGdId" /* $fileId->getId(); */, $newPermission, $optparams);

                    $tbl_folder_project_crm = 'folder_project_crm';
                    $insertProjectCRM = sprintf(
                        "(`customer_code`,`customer_name`,`project_code`, `email_sales`, `email_group`, `link_folder_sales`, `flag`) VALUES ('$customerCode','$customerName','$projectCode','$emailSales','$emailGroup', 'https://drive.google.com/drive/u/0/folders/$folder_id_project', 1)",
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($customerCode, "text"),
                        GetSQLValueString($customerName, "text"),
                        GetSQLValueString($emailSales, "text"),
                        GetSQLValueString($emailGroup, "text"),
                        GetSQLValueString($salesGdId, "text")
                    );

                    $resProjectCRM = $DBGD->insert_data($tbl_folder_project_crm, $insertProjectCRM);

                    echo "Project $projectCode - $projectName sudah dibuat dengan <br/>Link : https://drive.google.com/drive/u/0/folders/$folder_id_project<br/><br/>";
                    echo "Link Group Sales : https://drive.google.com/drive/u/0/folders/$salesGdId<br/>";
                } else {
                    echo "Project $projectCode - $projectName sudah existing dengan <br/>Link : https://drive.google.com/drive/u/0/folders/$gdProjectId<br/>";
                }
            }
        }
    } else {
        echo "TOKEN SALAH !";
    }
}

// This will create a folder and also sub folder when $parent_folder_id is given
function drive_create_folder1($folder_name, $parent_folder_id = null)
{
    $folder_list = check_folder_exists($folder_name);

    // if folder does not exists
    if (count($folder_list) == 0) {
        $service = new Google_Service_Drive($GLOBALS['client']);
        $folder = new Google_Service_Drive_DriveFile();
        $folder->setName($folder_name);
        $folder->setMimeType('application/vnd.google-apps.folder');
        if (!empty($parent_folder_id)) {
            $folder->setParents([$parent_folder_id]);
        }

        $result = $service->files->create($folder);
        $folder_id = null;

        if (isset($result['id']) && !empty($result['id'])) {
            $folder_id = $result['id'];
        }
        return $folder_id;
    }
    return $folder_list[0]['id'];
}

function drive_create_folder2($folder_name, $parent_folder_id = null)
{

    $folder_list = check_folder_exists($folder_name);

    // if folder does not exists
    if (count($folder_list) == 0) {
        $service = new Google_Service_Drive($GLOBALS['client']);
        $folder = new Google_Service_Drive_DriveFile();
        $folder1 = new Google_Service_Drive_DriveFile();

        $folder->setName($folder_name);
        $folder->setMimeType('application/vnd.google-apps.folder');

        if (!empty($parent_folder_id)) {
            $folder->setParents([$parent_folder_id]);
        }

        $result = $service->files->create($folder);

        $folder_id = null;

        if (isset($result['id']) && !empty($result['id'])) {
            $folder_id = $result['id'];
        }

        return $folder_id;

        $result_id = $result->getId();

        $msi_array = array("Account Manager", "Legal", "Business Solution");

        foreach ($msi_array as $value) {
            $folder1->setName($value);
            $folder1->setMimeType('application/vnd.google-apps.folder');
            $folder1->setParents([$result_id]);

            $result1 = $service->files->create($folder1);
        }

        // $folder_id1 = null;

        // if (isset($result1['id']) && !empty($result1['id'])) {
        //     $folder_id1 = $result1['id'];
        // }

        // return $folder_id1;
    }

    return $folder_list[0]['id'];
}

// This will check folders and sub folders by name
function check_folder_exists($folder_name)
{

    $service = new Google_Service_Drive($GLOBALS['client']);

    $project_folder_filters = array(
        'q' => "mimeType='application/vnd.google-apps.folder' and name='$folder_name' and trashed=false",
        'supportsAllDrives' => true,
        "supportsTeamDrives" => true,
        'includeItemsFromAllDrives' => true
    );

    // $parameters['q'] = "mimeType='application/vnd.google-apps.folder' and name='$folder_name' and trashed=false";
    // $files = $service->files->listFiles($parameters);

    $files = $service->files->listFiles($project_folder_filters);

    $op = [];
    foreach ($files as $k => $file) {
        $op[] = $file;
    }

    return $op;
}

// This will display list of folders and direct child folders and files.
function get_files_and_folders()
{
    $service = new Google_Service_Drive($GLOBALS['client']);

    $parameters['q'] = "mimeType='application/vnd.google-apps.folder' and 'root' in parents and trashed=false";
    $files = $service->files->listFiles($parameters);


    echo "<ul>";
    foreach ($files as $k => $file) {
        echo "<li>
            {$file['name']} - {$file['id']}  -------------- " . $file['mimeType'];
        echo "<ul>";
        $sub_files = $service->files->listFiles(array('q' => "'{$file['id']}' in parents"));


        foreach ($sub_files as $kk => $sub_file) {
            echo "<li> {$sub_file['name']} - {$sub_file['id']}  ---- " . $sub_file['mimeType'] . " </li>";

            echo "<ul>";
            $sub_filess = $service->files->listFiles(array('q' => "'{$sub_file['id']}' in parents"));

            foreach ($sub_filess as $kkk => $sub_file1) {
                echo "<li> {$sub_file1['name']} - {$sub_file1['id']}  ---- " . $sub_file1['mimeType'] . " </li>";
            }

            echo "</ul>";
        }


        echo "</ul>";
        echo "</li>";
    }
    echo "</ul>";
}

// This will insert file into drive and returns boolean values.
function insert_file_to_drive($file_path, $file_name, $parent_file_id = null)
{
    $service = new Google_Service_Drive($GLOBALS['client']);
    $file = new Google_Service_Drive_DriveFile();

    $file->setName($file_name);

    if (!empty($parent_file_id)) {
        $file->setParents([$parent_file_id]);
    }

    $result = $service->files->create(
        $file,
        array(
            'data' => file_get_contents($file_path),
            'mimeType' => 'application/octet-stream',
        )
    );

    $is_success = false;

    if (isset($result['name']) && !empty($result['name'])) {
        $is_success = true;
    }

    return $is_success;
}

function insertPermission($fileId, $email)
{
    $service = new Google_Service_Drive($GLOBALS['client']);

    $newPermission = new Google_Service_Drive_Permission();
    $newPermission->setEmailAddress($email);
    $newPermission->setType('user');
    $newPermission->setRole('writer');


    try {
        return $service->permissions->create($fileId /* $fileId->getId(); */, $newPermission);
    } catch (Exception $e) {
        print "An error occurred: " . $e->getMessage();
    }
    return NULL;
}

// function addPermission($folder_id)
// {
//     $client = new Google_Client();
//     $client->setApplicationName('Google Drive API PHP Quickstart');
//     $client->setScopes(Google_Service_Drive::DRIVE);
//     $client->setAccessType('offline');

//     $service = new Google_Service_Drive($GLOBALS['client']);

//     $idfolder = "$folder_id";
//     $results = $service->files->get($idfolder);
//     $permissions = $results->getpermissions();
//     $permissions->setType('user');
//     $permissions->setRole('reader');
//     $permissions->setValue('rizr19911006@gmail.com'); //thats email to share
//     $service->permissions->insert('0ByXNGBI2AHyZX0RZd2pROE5qNFk',$newPermission);
// }

if (isset($_GET['list_files_and_folders'])) {
    echo "<h1>Retriving List all files and folders from Google Drive</h1>";
    get_files_and_folders();
}

// Function just for easier debugging
function dd(...$d)
{
    echo "<pre style='background-color:#000;color:#fff;' >";
    print_r($d);
    exit;
}
