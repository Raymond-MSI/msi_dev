<?php
ini_set('display_errors', 1);
if ( !isset( $_SESSION ) ) {
  // server should keep session data for AT LEAST 1 hour
  ini_set( 'session.gc_maxlifetime', 7200 );

  // each client should remember their session id for EXACTLY 1 hour
  session_set_cookie_params( 7200 );

  session_start(); // ready to go!
}

date_default_timezone_set( "Asia/Jakarta" );

include( "applications/connections/connections.php" );
include( "components/classes/func_databases_v3.php" );
include( "components/classes/func_functions.php" );
include( "components/classes/func_modules.php" );
include( "components/classes/func_datatable.php"); 
include( "components/classes/func_component.php");
include( "components/classes/func_alert.php");
include( "components/classes/func_api.php");
include( "components/classes/func_cfg_web.php");
include( "components/classes/func_hcm.php");

$ALERT = new Alert();

// $DB = new Databases($hostname, $username, $password, $database);
$DB = new WebConfig($hostname, $username, $password, $database);

$database = "sa_md_hcm";
$DBHCM = new HCM($hostname, $username, $password, $database);
include( "components/classes/parameters.php" );

$condition = "config_key='TITLE_OF_WEBSITE'";
$gTemplate = $DB->get_data( "cfg_web", $condition );
$dTemplate = $gTemplate[ 0 ];
define( "TITLEOFWEBSITE", $dTemplate[ "config_value" ] );


//$_SESSION['Microservices_UserName'] = 'Hendra';
//$_SESSION['Microservices_UserEmail'] = 'hendra.novyantara@mastersystem.co.id';
//$_SESSION['Microservices_UserLevel'] = 'Administrator';
//$_SESSION['Microservices_AD'] = '';

if(isset($_GET['logout'])) {
  include('login.php');
} else if(isset($_GET['forgotpassword'])) { 
  include('forgot-password.php');
} else if(isset($_SESSION['Microservices_UserName'])) {
  include( "applications/templates/" . TEMPLATE . "/index.php" );
} else if(isset($_GET['notif']) && $_GET['notif']!="") {
  include("components/modules/" . $_GET['mod'] . "/" . $_GET['notif'] . ".php");
} else if(isset($_GET['approval']) && $_GET['approval']=="true") {
  include( "applications/templates/" . TEMPLATE . "/indexapp.php" );
} else {
  include('login.php');
}
