<?php
    if(isset($_POST['add'])) {
        $insert = sprintf("(`tos_name`,`service_type`) VALUES (%s,%s)",
            GetSQLValueString($_POST['tos_name'], "text"),
            GetSQLValueString($_POST['service_type'], "text")
        );
        $res = $DBSB->insert_data($tblname, $insert);
        $ALERT->savedata();
    } elseif(isset($_POST['save'])) {
        $condition = "tos_id=" . $_POST['tos_id'];
        $update = sprintf("`tos_id`=%s,`tos_name`=%s,`service_type`=%s",
            GetSQLValueString($_POST['tos_id'], "text"),
            GetSQLValueString($_POST['tos_name'], "text"),
            GetSQLValueString($_POST['service_type'], "text")
    );
        $res = $DBSB->update_data($tblname, $update, $condition);
        $ALERT->savedata();
    }
    ?>