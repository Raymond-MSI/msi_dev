<?php

$_SESSION{'Microservices_UserEmail'} = 'syamsul@mastersystem.co.id';

include_once("components/modules/google_drive/func_wrike_cr.php");

//FUNCTION
createCRFolder();
createCRTask();