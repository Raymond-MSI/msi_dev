<?php

global $DBHCM, $tblname;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
 
    $condition = "id=" . GetSQLValueString($id, "int");
    $delete_success = $DBHCM->delete_data($tblname, $condition);

    if ($delete_success !== false ) {
        $redirect_url = "index.php?mod=md_holiday&msg=deleted&submit=Submit";
    } else {
        $redirect_url = "index.php?mod=md_holiday&err=delete_failed&submit=Submit";
    }
    echo '<script type="text/javascript">';
    echo 'window.location.href = "' . $redirect_url . '";';
    echo '</script>';
    exit; 

} else {
    $redirect_url = "index.php?mod=md_holiday&err=invalid_id&submit=Submit";
    echo '<script type="text/javascript">';
    echo 'window.location.href = "' . $redirect_url . '";';
    echo '</script>';
    exit;
}
?>