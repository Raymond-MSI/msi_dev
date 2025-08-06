<?php
// if (isset($_POST['add'])) {
//     $insert = sprintf(
//         "(`id`,`request`,`divisi`,`posisi`,`kode_project`,`share`,`entry_datetime`,`share_datetime`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s)",
//         GetSQLValueString($_POST['id'], "int"),
//         GetSQLValueString($_POST['request'], "int"),
//         GetSQLValueString($_POST['divisi'], "text"),
//         GetSQLValueString($_POST['posisi'], "text"),
//         GetSQLValueString($_POST['kode_project'], "text"),
//         GetSQLValueString($_POST['share'], "text"),
//         GetSQLValueString($_POST['entry_datetime'], "date"),
//         GetSQLValueString($_POST['share_datetime'], "date")
//     );
//     $res = $DBTRXSHARE->insert_data($tblname, $insert);
//     $ALERT->savedata();
// } else
if (isset($_POST['save'])) {
    $condition = "id=" . $_POST['id'];
    $update = sprintf(
        "`id`=%s,`request`=%s,`divisi`=%s,`posisi`=%s,`kode_project`=%s,`share`=%s",
        GetSQLValueString($_POST['id'], "int"),
        GetSQLValueString($_POST['request'], "int"),
        GetSQLValueString($_POST['divisi'], "text"),
        GetSQLValueString($_POST['posisi'], "text"),
        GetSQLValueString($_POST['kode_project'], "text"),
        GetSQLValueString($_POST['share'], "text"),
        // GetSQLValueString($_POST['entry_datetime'], "date"),
        GetSQLValueString(date("Y-m-d G:i:s"), "date")
    );
    $res = $DBTRXSHARE->update_data($tblname, $update, $condition);
    $ALERT->savedata();
}
