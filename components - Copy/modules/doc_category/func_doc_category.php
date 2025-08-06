<?php
    global $DBLD;
    if(isset($_POST['add'])) {
        $insert = sprintf("(`cat_id`,`cat_name`) VALUES (%s,%s)",
            GetSQLValueString($_POST['cat_id'], "int"),
            GetSQLValueString($_POST['cat_name'], "text")
        );
        $res = $DBLD->insert_data($tblname, $insert);
        $ALERT->savedata();
    } elseif(isset($_POST['save'])) {
        $condition = "cat_id=" . $_POST['cat_id'];
        $update = sprintf("`cat_id`=%s,`cat_name`=%s",
            GetSQLValueString($_POST['cat_id'], "int"),
            GetSQLValueString($_POST['cat_name'], "text")
    );
        $res = $DBLD->update_data($tblname, $update, $condition);
        $ALERT->savedata();
    }
    ?>