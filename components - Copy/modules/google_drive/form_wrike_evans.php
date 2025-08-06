<?php

$_SESSION{'Microservices_UserEmail'} = 'syamsul@mastersystem.co.id';

include_once("components/modules/google_drive/func_wrike_project.php");
$modulename = 'WRIKE_INTEGRATE';
$DBWR = get_conn($modulename);

getAssignment();
// getWorkschedule();
?>