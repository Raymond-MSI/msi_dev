<?php
    if(isset($_POST['add'])) {
        $insert = sprintf("(`title`,`config_key`,`config_value`,`params`,`parent`,`order`) VALUES (%s,%s,%s,%s,%s,%s)",
            GetSQLValueString($_POST['title'], "text"),
            GetSQLValueString($_POST['config_key'], "text"),
            GetSQLValueString($_POST['config_value'], "text"),
            GetSQLValueString($_POST['params'], "text"),
            GetSQLValueString($_POST['parent'], "int"),
            GetSQLValueString($_POST['order'], "int")
        );
        $res = $DB->insert_data($tblname, $insert);
        $ALERT->savedata();
    } elseif(isset($_POST['save'])) {
        $condition = "id=" . $_POST['id'];
        $update = sprintf("`title`=%s,`config_key`=%s,`config_value`=%s,`params`=%s,`parent`=%s,`order`=%s",
            GetSQLValueString($_POST['title'], "text"),
            GetSQLValueString($_POST['config_key'], "text"),
            GetSQLValueString($_POST['config_value'], "text"),
            GetSQLValueString($_POST['params'], "text"),
            GetSQLValueString($_POST['parent'], "int"),
            GetSQLValueString($_POST['order'], "int")
    );
        $res = $DB->update_data($tblname, $update, $condition);
        $ALERT->savedata();
    }
    ?>