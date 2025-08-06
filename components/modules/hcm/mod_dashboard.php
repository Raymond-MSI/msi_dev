<?php 
// include("components/templates/template_dashboard.php"); 
$mdlname = "HCM";
$DBLD = get_conn($mdlname);
$mdlname = "EDO";
$DBEDO = get_conn($mdlname);

// EXTRA DAY OFF
$tblname = "trx_edo_request";
$condition = "";
$order = "";
$totals = $DBEDO->get_data($tblname, $condition, $order);

$conditionUser = "";
$sambung = "";
if($_SESSION['Microservices_UserLevel']!="Administrator") {
    $conditionUser = "employee_name='" . addslashes($_SESSION['Microservices_UserName']) . "<" . $_SESSION['Microservices_UserEmail'] . ">'";
    $sambung = " AND ";
}

$title = "Extra Day Off";
$items = array();

$condition = $conditionUser . $sambung . "(status='drafted')";
$tdoc = $DBLD->get_data($tblname, $condition);
$item = array("title"=>"Draft", "data"=>$tdoc[2], "type"=>"item1");
array_push($items, $item);

$condition = $conditionUser . $sambung . "(status='edo submitted' OR status='leave submitted')";
$tdoc = $DBLD->get_data($tblname, $condition);
$item = array("title"=>"Waiting Approval", "data"=>$tdoc[2], "type"=>"item1");
array_push($items, $item);

$condition = $conditionUser . $sambung . "status='request approved'";
$tdoc = $DBLD->get_data($tblname, $condition);
$item = array("title"=>"Approved", "data"=>$tdoc[2], "type"=>"item1");
array_push($items, $item);

$condition = $conditionUser . $sambung . "status LIKE '%rejected'";
$tdoc = $DBLD->get_data($tblname, $condition);
$item = array("title"=>"Rejected", "data"=>$tdoc[2], "type"=>"item1");
array_push($items, $item);

show_dashboard($title, $items);


// EMPLOYEES
$tblname = "mst_users";
$condition = "username='" . $_SESSION['Microservices_UserLogin'] . "'";
$users = $DB->get_data($tblname, $condition);
$json = $users[0]['permission'];
$permission = json_decode($json, true);
if(isset($permission['MODULE_EDO']['user_level']))
{
    $user_level = $permission['MODULE_EDO']['user_level'];
} else
{
    $user_level = "";
}

$tblname = "view_employees";

if($user_level<>"Read" && $user_level<>"Entry")
{

    $title = "Employees";
    $items = array();

    $condition = "(isnull(resign_date) OR resign_date='000-00-00') AND (job_structure LIKE 'EA%' OR job_structure LIKE 'KB%' OR job_structure LIKE 'RW%' OR job_structure LIKE 'THK%')";
    $tdoc = $DBLD->get_data($tblname, $condition);
    $item = array("title"=>"Sales & Business", "data"=>$tdoc[2], "type"=>"item1");
    array_push($items, $item);

    $condition = "(isnull(resign_date) OR resign_date='000-00-00') AND job_structure LIKE 'JW%'";
    $tdoc = $DBLD->get_data($tblname, $condition);
    $item = array("title"=>"Corporate Service", "data"=>$tdoc[2], "type"=>"item1");
    array_push($items, $item);

    $condition = "(isnull(resign_date) OR resign_date='000-00-00') AND (job_structure LIKE 'JG%' OR job_structure LIKE 'LWW%' OR job_structure LIKE 'RBC%')";
    $tdoc = $DBLD->get_data($tblname, $condition);
    $item = array("title"=>"Tech. & Solution", "data"=>$tdoc[2], "type"=>"item1");
    array_push($items, $item);

    $mysql = "SELECT COUNT('employee_id') AS `lastyear` FROM `sa_view_employees` WHERE `join_date` < '" . date("Y-m-d", mktime(0,0,0,1,1,date("Y"))) . "' AND isnull(`resign_date`)";
    $lastyear = $DBHCM->get_sql($mysql);
    $mysql = "SELECT COUNT('employee_id') AS `now` FROM `sa_view_employees` WHERE `join_date` <= '" . date("Y-m-d", mktime(0,0,0,31,12,date("Y"))) . "' AND isnull(`resign_date`)";
    $now = $DBHCM->get_sql($mysql);
    $mysql = "SELECT COUNT('employee_id') AS `resign` FROM `sa_view_employees` WHERE `resign_date` >= '" . date("Y-m-d", mktime(0,0,0,1,1,date("Y"))) . "' AND `resign_date` <= '" . date("Y-m-d", mktime(0,0,0,31,12,date("Y"))) . "'";
    $resign = $DBHCM->get_sql($mysql);
    $tdocx = number_format($resign[0]['resign']/($lastyear[0]['lastyear'] + $now[0]['now'])/2*100, 2, ".", ",") . "%";
    $item = array("title"=>"Turn Over", "data"=>$tdocx, "type"=>"item1");
    array_push($items, $item);

    $condition = "join_date >= '" . date("Y-m-d", strtotime("-1 day"), ) . "'";
    $order = "join_date DESC, nik DESC";
    $newEmp = $DBHCM->get_data($tblname, $condition, $order);
    $msgx = '
    <table class="table table-sm">
    <tr class="bg-light"><th>NIK</th><th>Employee Name</th><th>Organization</th></tr>';
    if($newEmp[2]>0) {
        do {
                $msgx .= '<tr><td class=\'align-text-top\'>' . $newEmp[0]['nik'] . '</td><td class=\'align-text-top\'>' . $newEmp[0]['employee_name'] . '</td><td class=\'align-text-top\'>' . $newEmp[0]['organization_name'] . '</td></tr>';
        } while($newEmp[0]=$newEmp[1]->fetch_assoc());
    }
    $msgx .= '</table>';
    $msg = array("title"=>"Today Update", "total"=>$newEmp[2], "data"=>$msgx);
    $item = array("title"=>"Update", "data"=>$msg, "type"=>"item2");
    array_push($items, $item);

    show_dashboard($title, $items);
}
?>

