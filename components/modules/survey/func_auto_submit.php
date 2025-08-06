<?php
$_SESSION['Microservices_UserEmail'] = 'syamsul@mastersystem.co.id';
echo "==========";
    echo "Execution module : SURVEY";
    echo " ";
    echo "Started : " . date("d-M-Y G:i:s");
    echo "==========<br/>";
    $time_start = microtime(true);


//     include_once("components/classes/func_crontab.php");
//     $descErr = "Completed";
//     $DBCRON = get_conn("CRONTAB");
//     $DBCRON->beginning();
// $rootfolder = $_SERVER['DOCUMENT_ROOT'];
// include( $rootfolder."/MICROSERVICES/components/classes/func_databases_v3.php" ); 
// include( $rootfolder."/MICROSERVICES/components/classes/func_component.php");

// $hostname = "10.20.50.161";
// $username = "ITAdmin";
// $password = "P@ssw0rd.1";

$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "sa_survey";

// $DB1 = new Databases('localhost', 'root', '', $dbname);
$DB1 = new Databases($hostname, $username, $password, $dbname);
$mdlname = "SURVEY";
$DB1 = get_conn($mdlname);
// SELECT * FROM `sa_survey` WHERE `status` LIKE '%Active%' and so_number is not null and TIMESTAMPDIFF(MONTH, created_datetime, CURDATE()) >=1
$query = $DB1->get_sqlV2("SELECT * FROM sa_survey WHERE status = 'Active' AND project_type is not null");
// $query = "SELECT * FROM `sa_survey` WHERE status = 'Active'  and so_number is  null and TIMESTAMPDIFF(MONTH, created_datetime, CURDATE()) >=1";
// $query = "SELECT * from sa_survey WHERE status = 'Active' AND project_type is not null AND TIMESTAMPDIFF(MONTH, created_datetime, CURDATE()) >= 1";
// $res1 = $DB1->get_sql($query);
// $curr = $res1[0];
// $next = $res1[1];
// var_dump($curr);
while ($curr = mysqli_fetch_array($query[1])){
    var_dump($curr);
    $main_rating = '
    [{"question_id":"2","value":"7"},{"question_id":"5","value":"7"},{"question_id":"8","value":"7"},{"question_id":"11","value":"7"},{"question_id":"14","value":"7"},{"question_id":"17","value":"7"},{"question_id":"20","value":"7"},{"question_id":"23","value":"7"}]';
    $rating_average = '7';
    $main_essay = '[{"question_id":"26","value":"Tidak Ada"}]';
    $main_engineer = '[{"name":"","email":"none"}]';
    $survey_id = $curr['survey_id'];
    $reply_datetime = date("Y-m-d G:i:s");
    $updatesurvey = sprintf("status = 'Submitted', reply_datetime = '$reply_datetime' ");
    $conditionsurvey = "survey_id = ". $survey_id; 
    // $reply_datetime = $curr['reply_datetime'];


    $insert = sprintf("(`survey_id`, `survey_link`, `project_code`,`project_title`, `type`, `main_rating`, `rating_average`, `main_essay`, `main_engineer`, `souvenir`,`souvenir_address`) 
    VALUES (%s,%s, %s, %s, %s, '$main_rating', '$rating_average', '$main_essay',  '$main_engineer', 'No', '')",
        GetSQLValueString($curr['survey_id'], "int"),
        GetSQLValueString($curr['survey_link'], "text"),
        GetSQLValueString($curr['project_code'], "text"),
        GetSQLValueString($curr['project_name'], "text"),
        GetSQLValueString($curr['template_type'], "text") 
    );
    $res = $DB1->insert_data('trx_survey', $insert);

    $upsurvey = $DB1->update_data("survey", $updatesurvey, $conditionsurvey);


    
}

$time_end = microtime(true);
$time = $time_end - $time_start;
echo "<br/>==========";
echo "Finished : " . date("d-M-Y G:i:s");
echo " ";
echo "The time used to run this module $time seconds";
echo "==========";
// $DBCRON->ending($descErr);
?>