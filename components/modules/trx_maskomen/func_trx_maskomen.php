<?php

if (isset($_POST['add'])) {
    $insert = sprintf(
        "(`id`,`email`,`artikel_wajib`,`artikel_submit`,`batas_pengumpulan`,`employee_name`,`entry_by`) VALUES (%s,%s,%s,%s,%s,%s,%s)",
        GetSQLValueString($_POST['id'], "int"),
        GetSQLValueString($_POST['email'], "text"),
        GetSQLValueString($_POST['artikel_wajib'], "int"),
        GetSQLValueString($_POST['artikel_submit'], "int"),
        GetSQLValueString($_POST['batas_pengumpulan'], "date"),
        GetSQLValueString($_POST['employee_name'], "text"),
        GetSQLValueString($_SESSION['Microservices_UserName'] . '<' . $_SESSION['Microservices_UserEmail'] . '>', "text"),
        GetSQLValueString(date("Y-m-d G:i:s"), "date")

    );
    $res = $DBMK->insert_data($tblname, $insert);
    $ALERT->savedata();
} elseif (isset($_POST['save'])) {
    $condition = "id=" . $_POST['id'];
    $update = sprintf(
        "`id`=%s,`email`=%s,`artikel_wajib`=%s,`artikel_submit`=%s,`batas_pengumpulan`=%s,`employee_name`=%s, `update_by`=%s",
        GetSQLValueString($_POST['id'], "int"),
        GetSQLValueString($_POST['email'], "text"),
        GetSQLValueString($_POST['artikel_wajib'], "int"),
        GetSQLValueString($_POST['artikel_submit'], "int"),
        GetSQLValueString($_POST['batas_pengumpulan'], "date"),
        GetSQLValueString($_POST['employee_name'], "text"),
        GetSQLValueString($_SESSION['Microservices_UserName'] . '<' . $_SESSION['Microservices_UserEmail'] . '>', "text"),
        GetSQLValueString(date("Y-m-d G:i:s"), "date")

    );
    $res = $DBMK->update_data($tblname, $update, $condition);
    $ALERT->savedata();
}
