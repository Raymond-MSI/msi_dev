<?php
$tblname = "provider";
if(isset($_POST['add_prov'])) {
    $insert = sprintf("(`provider_name`) VALUES (%s)",
        GetSQLValueString($_POST['provider_name'], "text")
    );
    $res = $DBLD->insert_data($tblname, $insert);
    $ALERT->savedata();
} elseif(isset($_POST['save'])) {
    $condition = "provider_id=" . $_POST['provider_id'];
    $update = sprintf("`provider_name`=%s",
        GetSQLValueString($_POST['provider_name'], "text")
);
    $res = $DBLD->update_data($tblname, $update, $condition);
    $ALERT->savedata();
}
?>