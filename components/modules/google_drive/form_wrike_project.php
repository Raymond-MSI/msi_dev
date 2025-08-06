<?php
$_SESSION{'Microservices_UserEmail'} = 'syamsul@mastersystem.co.id';

include_once("components/modules/google_drive/func_wrike_project.php");
$modulename = 'WRIKE_INTEGRATE';
$DBWR = get_conn($modulename);

//echo "updatedDate={'start':'".date('Y-m-d',strtotime('-1 days'))."T00:00:00Z','end':'".date('Y-m-d',strtotime('-1 days'))."T23:59:59Z'}";

get_contact();
get_project();
// getResource();
// getAssignment();
// addDescription();
// //permissionDriveAuthor(); //Tidak Digunakan
// permissionResource();
?>