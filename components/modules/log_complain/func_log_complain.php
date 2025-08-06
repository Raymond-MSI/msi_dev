<?php
if (isset($_POST['add'])) {
    // $userName = $_SESSION['Microservices_UserName'];
    $userName = addslashes($_SESSION['Microservices_UserName']);
    $entry_by = $userName . "<" . $_SESSION['Microservices_UserEmail'] . ">";

    $insert = sprintf(
        "(`complain_id`,`project_code`,`so_number`,`order_number`,`project_name`,`project_leader`,`customer`,`pic_customer`,`category`,`type_project`,`tittle`,`complain`,`action_plan`,`tanggal`,`status`,`entry_by`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
        GetSQLValueString($_POST['complain_id'], "int"),
        GetSQLValueString($_POST['project_code'], "text"),
        GetSQLValueString($_POST['so_number'], "text"),
        GetSQLValueString($_POST['order_number'], "text"),
        GetSQLValueString($_POST['project_name'], "text"),
        GetSQLValueString($_POST['project_leader'], "text"),
        GetSQLValueString($_POST['customer'], "text"),
        GetSQLValueString($_POST['pic_customer'], "text"),
        GetSQLValueString($_POST['category'], "text"),
        GetSQLValueString($_POST['type_project'], "text"),
        GetSQLValueString($_POST['tittle'], "text"),
        GetSQLValueString($_POST['complain'], "text"),
        GetSQLValueString($_POST['action_plan'], "text"),
        GetSQLValueString($_POST['tanggal'], "date"),
        GetSQLValueString($_POST['status'], "text"),
        GetSQLValueString($entry_by, "text"),
    );
    $res = $DBKPILog->insert_data($tblname, $insert);

    $project_code = $_POST['project_code'];
    $description = $userName . " Telah memasukan data dengan KP " . $_POST['project_code'] . " dan SO Number " . $_POST['so_number'];
    $insert = sprintf(
        "(`project_code`,`description`,`entry_by`) VALUES (%s,%s,%s)",
        GetSQLValueString($project_code, "text"),
        GetSQLValueString($description, "text"),
        GetSQLValueString($entry_by, "text")
    );
    $res = $DBKPILog->insert_data("complain_log", $insert);
    $ALERT->savedata();

    if (isset($_POST['result'])) {
        // $complain_id = $_POST['complain_id'];
        $combine_arr = array();
        for ($i = 0; $i < count($_POST['result']); $i++) {
            $combine_arr[] = array($_POST['result'][$i], $_POST['solution'][$i]);
        }
        foreach ($combine_arr as $value) {
            $insert_sql = sprintf(
                "(`project_code`,`customer`,`result`,`solution`) VALUES (%s,%s,%s,%s)",
                GetSQLValueString($_POST['project_code'], "text"),
                GetSQLValueString($_POST['customer'], "text"),
                GetSQLValueString($value[0], "text"),
                GetSQLValueString($value[1], "text")

            );
            $DBKPILog->insert_data("complain_result", $insert_sql);
        }
    }
} elseif (isset($_POST['save'])) {
    $condition = "complain_id=" . $_POST['complain_id'];
    $update = sprintf(
        "`project_code`=%s,`so_number`=%s,`order_number`=%s,`project_name`=%s,`project_leader`=%s,`customer`=%s,`pic_customer`=%s,`category`=%s,`type_project`=%s,`tittle`=%s,`complain`=%s,`action_plan`=%s,`tanggal`=%s,`status`=%s",
        GetSQLValueString($_POST['project_code'], "text"),
        GetSQLValueString($_POST['so_number'], "text"),
        GetSQLValueString($_POST['order_number'], "text"),
        GetSQLValueString($_POST['project_name'], "text"),
        GetSQLValueString($_POST['project_leader'], "text"),
        GetSQLValueString($_POST['customer'], "text"),
        GetSQLValueString($_POST['pic_customer'], "text"),
        GetSQLValueString($_POST['category'], "text"),
        GetSQLValueString($_POST['type_project'], "text"),
        GetSQLValueString($_POST['tittle'], "text"),
        GetSQLValueString($_POST['complain'], "text"),
        GetSQLValueString($_POST['action_plan'], "text"),
        GetSQLValueString($_POST['tanggal'], "date"),
        GetSQLValueString($_POST['status'], "text"),
    );

    $res = $DBKPILog->update_data($tblname, $update, $condition);


    $userName = addslashes($_SESSION['Microservices_UserName']);
    $entry_by = $userName . "<" . $_SESSION['Microservices_UserEmail'] . ">";
    $project_code = $_POST['project_code'];
    $description = $userName . " Telah mengubah data pada KP " . $_POST['project_code'];
    $insert = sprintf(
        "(`project_code`,`description`,`entry_by`) VALUES (%s,%s,%s)",
        GetSQLValueString($project_code, "text"),
        GetSQLValueString($description, "text"),
        GetSQLValueString($entry_by, "text")
    );
    $res = $DBKPILog->insert_data("complain_log", $insert);
    $ALERT->savedata();

    // if (isset($_POST['result'])) {
    //     // $complain_id = $_POST['complain_id'];
    //     // $condition = "order_number=" . $_POST['order_number'];
    //     $combine_arr = array();
    //     for ($i = 0; $i < count($_POST['result']); $i++) {
    //         $combine_arr[] = array($_POST['result'][$i], $_POST['solution'][$i]);
    //     }
    //     foreach ($combine_arr as $value) {
    //         $update_result = sprintf(
    //             "(`project_code`,`customer`,`result`,`solution`) VALUES (%s,%s,%s,%s)",
    //             GetSQLValueString($_POST['project_code'], "text"),
    //             GetSQLValueString($_POST['customer'], "text"),
    //             GetSQLValueString($value[0], "text"),
    //             GetSQLValueString($value[1], "text")

    //         );
    //         $DBKPILog->insert_data("complain_result", $update_result);
    //     }
    // }
} elseif (isset($_POST['delete'])) {
    $condition = "complain_id=" . $_POST['complain_id'];
    $res = $DBKPILog->delete_data($tblname, $condition);
    var_dump($res);
    die;



    $userName = addslashes($_SESSION['Microservices_UserName']);
    $entry_by = $userName . "<" . $_SESSION['Microservices_UserEmail'] . ">";
    $project_code = $_POST['project_code'];
    $description = $userName . " Telah Menghapus data pada KP " . $_POST['project_code'];
    $insert = sprintf(
        "(`project_code`,`description`,`entry_by`) VALUES (%s,%s,%s)",
        GetSQLValueString($project_code, "text"),
        GetSQLValueString($description, "text"),
        GetSQLValueString($entry_by, "text")
    );
    $res = $DBKPILog->insert_data("complain_log", $insert);

    $condition = 'config_key="MEDIA_LOG_COMPLAIN" AND parent=8';
    $folders = $DB->get_data('cfg_web', $condition);
    $FolderName = 'log_complain';
    $sFolderTarget = $folders[0]['config_value'] . '/' . $_POST['project_code'] . '/' . $FolderName . '/';
    $sSubFolders = explode("/", $sFolderTarget);
    $xFolder = "";
    for ($i = 0; $i < count($sSubFolders); $i++) {
        if ($i == 0) {
            $xFolder .= $sSubFolders[$i];
        } else {
            $xFolder .= '/' . $sSubFolders[$i];
        }
        if ($sSubFolders[$i] != "..") {
            if (!(is_dir($xFolder))) {
                mkdir($xFolder, 0777, true);
                $file = 'media/documents/projects/index.php';
                $newfile = $xFolder . '/index.php';
                // isset($file, $newfile);
                // if (!copy($file, $newfile)) {
                // 	echo "";
                // }
            }
        }
    }
    $d = dir($sFolderTarget);
    while (false !== ($entry = $d->read())) {
        if ($entry != '.' && $entry != '..' && $entry != 'index.php') {
            $fstat = stat($sFolderTarget . $entry);
            $cobatarget = $sFolderTarget;

            $filePath = $cobatarget . '/' . $entry;
            // Check if the file exists before attempting to delete
            if (file_exists($filePath)) {
                // Attempt to delete the file
                if (unlink($filePath)) {
                    echo "File '$entry' deleted successfully.";
                } else {
                    echo "Error deleting file '$entry'.";
                }
            } else {
                echo "File '$entry' not found.";
            }
        }
    }
    $ALERT->savedata();
} elseif (isset($_POST['deletefile'])) {
    $condition = 'config_key="MEDIA_LOG_COMPLAIN" AND parent=8';
    $folders = $DB->get_data('cfg_web', $condition);
    $FolderName = 'log_complain';
    $sFolderTarget = $folders[0]['config_value'] . '/' . $_POST['project_code'] . '/' . $FolderName . '/';
    // var_dump($sFolderTarget);
    // die;
    $sSubFolders = explode("/", $sFolderTarget);
    $xFolder = "";
    for ($i = 0; $i < count($sSubFolders); $i++) {
        if ($i == 0) {
            $xFolder .= $sSubFolders[$i];
        } else {
            $xFolder .= '/' . $sSubFolders[$i];
        }
        if ($sSubFolders[$i] != "..") {
            if (!(is_dir($xFolder))) {
                mkdir($xFolder, 0777, true);
                $file = 'media/documents/projects/index.php';
                $newfile = $xFolder . '/index.php';
                // isset($file, $newfile);
                // if (!copy($file, $newfile)) {
                // 	echo "";
                // }
            }
        }
    }
    $d = dir($sFolderTarget);
    while (false !== ($entry = $d->read())) {
        if ($entry != '.' && $entry != '..' && $entry != 'index.php') {
            $fstat = stat($sFolderTarget . $entry);
            $cobatarget = $sFolderTarget;

            $filePath = $cobatarget . '/' . $entry;
            // Check if the file exists before attempting to delete
            if (file_exists($filePath)) {
                // Attempt to delete the file
                if (unlink($filePath)) {
                    echo "File '$entry' deleted successfully.";
                    $ALERT->savedata();
                } else {
                    echo "Error deleting file '$entry'.";
                }
            } else {
                echo "File '$entry' not found.";
            }
        }
    }
}
// else {
//     // Jika parameter complain_id tidak ada, mungkin ada kesalahan
//     // Tambahkan log atau tindakan lain sesuai kebutuhan
//     echo "Parameter complain_id tidak valid.";
// }
