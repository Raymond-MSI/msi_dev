<?php
    if(isset($_POST['add'])) {
        $insert = sprintf("(`presales_id`,`employee_name`,`employee_email`,`organization_name`,`presales_type`) VALUES (%s,%s,%s,%s,%s)",
            GetSQLValueString($_POST['presales_id'], "int"),
            GetSQLValueString($_POST['employee_name'], "text"),
            GetSQLValueString($_POST['employee_email'], "text"),
            GetSQLValueString($_POST['organization_name'], "text"),
            GetSQLValueString($_POST['presales_type'], "text")
        );
        $res = $DBMPL->insert_data($tblname, $insert);
        $ALERT->savedata();
    } elseif(isset($_POST['save'])) {
        $condition = "presales_id=" . $_POST['presales_id'];
        $update = sprintf("`presales_id`=%s,`employee_name`=%s,`employee_email`=%s,`organization_name`=%s,`presales_type`=%s",
            GetSQLValueString($_POST['presales_id'], "int"),
            GetSQLValueString($_POST['employee_name'], "text"),
            GetSQLValueString($_POST['employee_email'], "text"),
            GetSQLValueString($_POST['organization_name'], "text"),
            GetSQLValueString($_POST['presales_type'], "text")
    );
        $res = $DBMPL->update_data($tblname, $update, $condition);
        $ALERT->savedata();
    }
    ?>