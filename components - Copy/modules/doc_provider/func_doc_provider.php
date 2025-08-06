<?php
    global $DBLD;
    if(isset($_POST['add'])) {
        $insert = sprintf("(`provider_id`,`provider_name`) VALUES (%s,%s)",
            GetSQLValueString($_POST['provider_id'], "int"),
            GetSQLValueString($_POST['provider_name'], "text")
        );
        $res = $DBLD->insert_data($tblname, $insert);
        $ALERT->savedata();
    } elseif(isset($_POST['save'])) {
        $condition = "provider_id=" . $_POST['provider_id'];
        $update = sprintf("`provider_id`=%s,`provider_name`=%s",
            GetSQLValueString($_POST['provider_id'], "int"),
            GetSQLValueString($_POST['provider_name'], "text")
    );
        $res = $DBLD->update_data($tblname, $update, $condition);
        $ALERT->savedata();
    }
    ?>