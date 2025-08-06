<?php
    if(isset($_POST['add'])) {
        $insert = sprintf("(`id`,`id_req`,`divisi`,`posisi`) VALUES (%s,%s,%s,%s)",
            GetSQLValueString($_POST['id'], "int"),
            GetSQLValueString($_POST['id_req'], "int"),
            GetSQLValueString($_POST['divisi'], "text"),
            GetSQLValueString($_POST['posisi'], "text")
        );
        $res = $DB->insert_data($tblname, $insert);
        $ALERT->savedata();
    } elseif(isset($_POST['save'])) {
        $condition = "id=" . $_POST['id'];
        $update = sprintf("`id`=%s,`id_req`=%s,`divisi`=%s,`posisi`=%s",
            GetSQLValueString($_POST['id'], "int"),
            GetSQLValueString($_POST['id_req'], "int"),
            GetSQLValueString($_POST['divisi'], "text"),
            GetSQLValueString($_POST['posisi'], "text")
    );
        $res = $DB->update_data($tblname, $update, $condition);
        $ALERT->savedata();
    }
    ?>