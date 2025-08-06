<?php

// $_SESSION{
//     'Microservices_UserEmail'} = 'syamsul@mastersystem.co.id';
$_SESSION['Microservices_UserEmail'] = 'malik.aulia@mastersystem.co.id';


echo "==========";
echo "Execution module : Alert email 2024";
echo "Started : " . date("d-M-Y G:i:s");
echo "==========<br/>";
$time_start = microtime(true);


// include_once("components/classes/func_crontab.php");
// $descErr = "Completed";
// $DBCRON = get_conn("CRONTAB");
// $DBCRON->beginning();


global $DBHCM;
$mdlname = "REQUIREMENT_HCM";
$DBHCM = get_conn($mdlname);
$query = $DBHCM->get_sqlV2("SELECT * FROM sa_hcm_requirement 
WHERE (request_date >= DATE_SUB(CURDATE(), INTERVAL 4 DAY) AND request_date < DATE_SUB(CURDATE(), INTERVAL 3 DAY))
   OR (request_date >= DATE_SUB(CURDATE(), INTERVAL 8 DAY) AND request_date < DATE_SUB(CURDATE(), INTERVAL 7 DAY))
");
while ($data = mysqli_fetch_array($query[1])) {

    var_dump($data);
    die;
}
$time_end = microtime(true);
$time = $time_end - $time_start;
echo "<br/>==========";
echo "Finished : " . date("d-M-Y G:i:s");
echo "The time used to run this module $time seconds";
echo "==========";
// $DBCRON->ending($descErr);
