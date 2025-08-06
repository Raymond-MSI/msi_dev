<?php
    if(isset($_POST['add'])) {
        $insert = sprintf("(`id_note`,`project_code`,`file`) VALUES (%s,%s,%s)",
            GetSQLValueString($_POST['id_note'], "int"),
            GetSQLValueString($_POST['project_code'], "text"),
            GetSQLValueString($_POST['file'], "text")
        );
        $res = $DB->insert_data($tblname, $insert);
        $ALERT->savedata();
    } elseif(isset($_POST['save'])) {
        $condition = "id_note=" . $_POST['id_note'];
        $update = sprintf("`id_note`=%s,`project_code`=%s,`file`=%s",
            GetSQLValueString($_POST['id_note'], "int"),
            GetSQLValueString($_POST['project_code'], "text"),
            GetSQLValueString($_POST['file'], "text")
    );
        $res = $DB->update_data($tblname, $update, $condition);
        $ALERT->savedata();
    }
    ?>