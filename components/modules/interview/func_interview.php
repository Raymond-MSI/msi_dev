<?php

global $DBLINK;
$mdlname = "new_request";
$DBREC   = get_conn($mdlname);
if (isset($_POST['add'])) {
    $insert = sprintf(
        "(`interview_id`,`divisi`,`posisi`,`project_code`) VALUES (%s,%s,%s,%s)",
        GetSQLValueString($_POST['interview_id'], "int"),
        GetSQLValueString($_POST['divisi'], "text"),
        GetSQLValueString($_POST['posisi'], "text"),
        GetSQLValueString($_POST['project_code'], "text")
    );
    $res = $DB->insert_data($tblname, $insert);
    $ALERT->savedata();
} elseif (isset($_POST['save'])) {
    $condition = "interview_id=" . $_POST['interview_id'];
    $update = sprintf(
        "`interview_id`=%s,`divisi`=%s,`posisi`=%s,`project_code`=%s",
        GetSQLValueString($_POST['interview_id'], "int"),
        GetSQLValueString($_POST['divisi'], "text"),
        GetSQLValueString($_POST['posisi'], "text"),
        GetSQLValueString($_POST['project_code'], "text")
    );
    $res = $DB->update_data($tblname, $update, $condition);
    $ALERT->savedata();
} elseif (isset($_POST['edit'])) {
    $insert = sprintf(
        "(`link_from`) VALUES (%s)",
        // GetSQLValueString($_POST['id_link'], "int"),
        GetSQLValueString($_POST['link_from'], "text")
    );
    $res = $DBINT->insert_data("link", $insert);
    // $ALERT->savedata();
}
