<?php
if((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $released = "1631011177";
    $author = 'Syamsul Arham';
} else {
    $modulename = "change_password";
    $userpermission = useraccess($modulename);
    // Function
    function form_data($tblname) {
        include("components/modules/change_password/form_change_password.php"); 
    } 
    // End Function

    // $database = 'sa_microservices';
    include("components/modules/change_password/connection.php");
    $DB = new Databases($hostname, $username, $userpassword, $database);
    $tblname = 'mst_users';

    include("components/modules/change_password/func_change_password.php");

    // Body
    ?>
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">change_password</h6>
            </div>
            <div class="card-body">
                <?php
                if(!isset($_GET['act'])) {
                } elseif($_GET['act'] == 'edit') {
                    form_data($tblname);
                } elseif($_GET['act'] == 'save') {
                    form_data($tblname);
                }
                ?>
            </div>
        </div>
    </div>

<?php } ?>