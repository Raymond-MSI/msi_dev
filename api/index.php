<?php

declare(strict_types=1);

spl_autoload_register(function ($class)
{
    require __DIR__ . "/src/$class.php";
});

set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");

header("Content-type: application/json; charset=UTF-8");
$xxx = explode("?", $_SERVER["REQUEST_URI"]);
$parts = explode("/", $xxx[0]);
$id = $parts[3] ?? null;

$username = "wsyakinah@gmail.com";
$password = "P@ssw0rd123";
// Localhost
// $hostname = "localhost";
// $dbname = "sa_google_calendar";
// $dbusername = "root";
// $dbpassword = "";
// Production
$hostname = "mariadb.mastersystem.co.id:4006";
$dbname = "sa_google_calendar";
$dbusername = "ITAdmin";
$dbpassword = "P@ssw0rd.1";

switch ($parts[2])
{
    case "Schedule":
        $title = "Schedule Google Calendar";
        $tblname = "sa_schedule";
        $database = new Database($hostname, $dbname, $dbusername, $dbpassword);
        $authentication = new Authentication($database, $username, $password);
        $MSIZonegateway = new MSIZoneGateway($database, $authentication, $tblname);
        $MSIZonecontroller = new MSIZoneController($MSIZonegateway, $title);
        $MSIZonecontroller->processRequest($_SERVER["REQUEST_METHOD"], $id);
        break;

    case "Preschedule":
        $title = "Pre-Schedule Google Calendar";
        $tblname = "sa_preschedule";
        $database = new Database($hostname, $dbname, $dbusername, $dbpassword);
        $authentication = new Authentication($database, $username, $password);
        $MSIZonegateway = new MSIZoneGateway($database, $authentication, $tblname);
        $MSIZonecontroller = new MSIZoneController($MSIZonegateway, $title);
        $MSIZonecontroller->processRequest($_SERVER["REQUEST_METHOD"], $id);
        break;

    case "ResourceAssignment":
        $title = "Resource Assignment Google Calendar";
        $dbname = "sa_wrike_integrate";
        $tblname = "sa_resource_assignment";
        $database = new Database($hostname, $dbname, $dbusername, $dbpassword);
        $authentication = new Authentication($database, $username, $password);
        $MSIZonegateway = new MSIZoneGateway($database, $authentication, $tblname);
        $MSIZonecontroller = new MSIZoneController($MSIZonegateway, $title);
        $MSIZonecontroller->processRequest($_SERVER["REQUEST_METHOD"], $id);
        break;
    
    case "METimeElapsed":
        $title = "Time Elapsed Request manage Engine";
        $database = new Database($hostname, "sa_manage_engine", $dbusername, $dbpassword);
        $authentication = new Authentication($database, $username, $password);
        $metimeelapsedgateway = new METimeElapsedGateway($database, $authentication, "sa_time_elapsed");
        $metimeelapsedcontroller = new METimeElapsedController($metimeelapsedgateway);
        $metimeelapsedcontroller->processRequest($_SERVER["REQUEST_METHOD"], $id);
        break;

    default:
        http_response_code(404);
        exit;
}



