<?php
if (isset($_POST['addlink'])) {
    $insert = sprintf(
        "(`id_link`,`link_from`) VALUES (%s,%s)",
        GetSQLValueString($_POST['id_link'], "int"),
        GetSQLValueString($_POST['link_from'], "text")
    );
    $res = $DBLINK->insert_data($tblname, $insert);
    $ALERT->savedata();
} elseif (isset($_POST['save'])) {
    $condition = "id_link=" . $_POST['id_link'];
    $update = sprintf(
        "`id_link`=%s,`link_from`=%s",
        GetSQLValueString($_POST['id_link'], "int"),
        GetSQLValueString($_POST['link_from'], "text")
    );
    $res = $DBLINK->update_data($tblname, $update, $condition);
    $ALERT->savedata();
}
