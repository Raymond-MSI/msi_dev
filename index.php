<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

if (!isset($_SESSION)) {
  // server should keep session data for AT LEAST 1 hour
  ini_set('session.gc_maxlifetime', 7200);

  // each client should remember their session id for EXACTLY 1 hour
  session_set_cookie_params(7200);

  session_start(); // ready to go!
}

date_default_timezone_set("Asia/Jakarta");

include("applications/connections/connections.php");
include("components/classes/func_databases_v3.php");
include("components/classes/func_functions.php");
include("components/classes/func_modules.php");
include("components/classes/func_datatable.php");
include("components/classes/func_component.php");
include("components/classes/func_alert.php");
include("components/classes/func_api.php");
include("components/classes/func_cfg_web.php");
include("components/classes/func_hcm.php");
include("components/classes/func_property.php");

$ALERT = new Alert();
$ClassVersion = new Property();

// $DB = new Databases($hostname, $username, $password, $database);
$DB = new WebConfig($hostname, $username, $password, $database);

$database = "sa_md_hcm";
$DBHCM = new HCM($hostname, $username, $password, $database);
include("components/classes/parameters.php");

$condition = "config_key='TITLE_OF_WEBSITE'";
$gTemplate = $DB->get_data("cfg_web", $condition);
$dTemplate = $gTemplate[0];
define("TITLEOFWEBSITE", $dTemplate["config_value"]);

//reset notif
if (isset($_SESSION['Microservices_UserEmail'])) {
  $link = "index.php?" . $_SERVER['QUERY_STRING'];
  reset_notif($_SESSION['Microservices_UserEmail'], $link);
}

if (isset($_GET['logout'])) {
  include('login.php');
} else if (isset($_GET['forgotpassword'])) {
  include('forgot-password.php');
} else if (isset($_SESSION['Microservices_UserName'])) {
  $MyFullName = $_SESSION['Microservices_UserName'] . "<" . $_SESSION['Microservices_UserEmail'] . ">";
  define("MYFULLNAME", $MyFullName);
  define("MYEMAIL", $_SESSION['Microservices_UserEmail']);
  define("MYNAME", $_SESSION['Microservices_UserName']);
  include("applications/templates/" . TEMPLATE . "/index.php");
} else if (isset($_GET['notif']) && $_GET['notif'] != "") {
  include("components/modules/" . $_GET['mod'] . "/" . $_GET['notif'] . ".php");
} else if (isset($_GET['approval']) && $_GET['approval'] == "true") {
  include("applications/templates/" . TEMPLATE . "/indexapp.php");
} else {
  include('login.php');
}
