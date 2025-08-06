<?php
    if(isset($_POST['add'])) {
        $insert = sprintf("(`id`,`project_id`,`project_code`,`order_number`) VALUES (%s,%s,%s,%s)",
            GetSQLValueString($_POST['id'], "int"),
            GetSQLValueString($_POST['project_id'], "text"),
            GetSQLValueString($_POST['project_code'], "text"),
            GetSQLValueString($_POST['order_number'], "text")
        );
        $res = $DB->insert_data($tblname, $insert);
        $ALERT->savedata();
    } elseif(isset($_POST['save'])) {
        $condition = "id=" . $_POST['id'];
        $update = sprintf("`id`=%s,`project_id`=%s,`project_code`=%s,`order_number`=%s",
            GetSQLValueString($_POST['id'], "int"),
            GetSQLValueString($_POST['project_id'], "text"),
            GetSQLValueString($_POST['project_code'], "text"),
            GetSQLValueString($_POST['order_number'], "text")
    );
        $res = $DB->update_data($tblname, $update, $condition);
        $ALERT->savedata();
    }
    ?>