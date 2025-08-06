<?php
$tblname = "category";
if(isset($_POST['add_cat'])) {
    $insert = sprintf("(`cat_name`) VALUES (%s)",
        GetSQLValueString($_POST['cat_name'], "text")
    );
    $res = $DBLD->insert_data($tblname, $insert);
    $ALERT->savedata();
} elseif(isset($_POST['save'])) {
    $condition = "cat_id=" . $_POST['cat_id'];
    $update = sprintf("`cat_name`=%s",
        GetSQLValueString($_POST['cat_name'], "text")
);
    $res = $DBLD->update_data($tblname, $update, $condition);
    $ALERT->savedata();
}
?>