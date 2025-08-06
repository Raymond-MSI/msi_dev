<?php
$_SESSION[
    'Microservices_UserEmail'] = 'syamsul@mastersystem.co.id';

include_once("components/modules/google_drive/func_google_drive.php");
include("components/modules/google_drive/func_extension_google_drive.php");

$tblname = "cfg_web";
$mdlname = 'SERVICE_BUDGET';
$condition = "config_key = 'MODULE_" . ucwords($mdlname) . "'";
$cons = $DB->get_data($tblname, $condition);
$dcon = $cons[0];
if ($cons[2] > 0) {
    $params = get_params($dcon['params']);
    $hostname = $params['database']['hostname'];
    $username = $params['database']['username'];
    $userpassword = $params['database']['userpassword'];
    $database = $params['database']['database_name'];

    $DBGD_SB = new SBDrive($hostname, $username, $userpassword, $database);
}

$database = 'sa_google_drive';
//   include("components/modules/google_drive/connection.php");
//   $DBGD = new Databases($hostname, $username, $userpassword, $database);
$tblname = "cfg_web";
$mdlname = 'GOOGLE_DRIVE';
$condition = "config_key = 'MODULE_" . ucwords($mdlname) . "'";
$cons = $DB->get_data($tblname, $condition);
$dcon = $cons[0];
if ($cons[2] > 0) {
    $params = get_params($dcon['params']);
    $hostname = $params['database']['hostname'];
    $username = $params['database']['username'];
    $userpassword = $params['database']['userpassword'];
    $database = $params['database']['database_name'];
    echo $hostname;
    $DBGD = new Drive($hostname, $username, $userpassword, $database);
}


?>

<!-- <div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="card-title">Create Folder Customer Name</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="index.php?mod=<?php echo $_GET['mod'] ?>&act=add" enctype="multipart/form-data">
                        <div class="container">
                            <div class="row">
                                <label for="inputCID3" class="col-sm-2 col-form-label col-form-label-sm">Customer No.
                                    *</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control form-control-sm"
                                        name="name_folder" id="name_folder" value="" required>
                                </div>
                                <div class="col-sm-12 mt-2">
                                <input type="submit" class="btn btn-primary" name="submit" value="Push" style="float: right;"> -->
<?php
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    $url = "https://";
} else {
    $url = "http://";
}
// Append the host(domain name, ip) to the URL.   
$url .= $_SERVER['HTTP_HOST'];

// Append the requested resource location to the URL   
$url .= $_SERVER['REQUEST_URI'];

$urlSplit = explode("=", $url);
$customerCode = explode("&", $urlSplit[3]);
$customerName = explode("&", $urlSplit[4]);
$projectCode = explode("&", $urlSplit[5]);
$projectName = explode("&", $urlSplit[6]);
$emailGroup = explode("&", $urlSplit[7]);
$emailSales = explode("&", $urlSplit[8]);
$token = explode("&", $urlSplit[9]);

echo "<br/>" . $url . "<br/>" . $customerCode[0] . " - " . $customerName[0] . " - " . $projectCode[0] . " - " . $projectName[0] . " - " . $emailGroup[0] ." - " . $emailSales[0] ." - " . $token[0] . "<br/>";

// auto_post_gd_crm("Nahnu", "Nahnu", "Nahnu", "Nahnu", $emailGroup[0], $emailSales[0], $token[0]);
auto_post_gd_crm($customerCode[0], $customerName[0], $projectCode[0], $projectName[0], $emailGroup[0], $emailSales[0], $token[0]);
// get_customer_data_sb();
// get_db_data();
// auto_post_gd(); //CreateFolderCustomer
// auto_post_gd_project(); //CreateProject+ProjectDetails
// Tidak digunakan auto_post_gd_department();

// permissionFolder();
// delete_folder();
?>