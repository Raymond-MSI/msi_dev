<?php
if((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $author = 'Syamsul Arham';
} else {
    // $modulename = "Employee List";
    // $userpermission = useraccess($modulename);
    // if(USERPERMISSION=="7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION=="dbf36ff3e3827639223983ee8ac47b42" || USERPERMISSION=="726ea0dd998698e8a87f8e344d373533" || USERPERMISSION=="5898299487c5b9cdbe7d61809fd20213" || USERPERMISSION=="335a66c239a137964a33e8c60b24e3d9" || USERPERMISSION=="0162bce636a63c3ae499224203e06ed0") {
        $mdlname = "HCM";
        $userpermission = useraccess_v2($mdlname);
        if(USERPERMISSION_V2=="f29bf94cd036fd131ced9cecc6b2469a" ) {

        $tblname = 'cfg_web';
        $condition = 'config_key="MODULE_HCM"';
        $setupDB = $DB->get_data($tblname, $condition);
        $dsetupDB = $setupDB[0];
        if($setupDB[2]>0) {
            $params = get_params($dsetupDB['params']);
            $hostname = $params['database']['hostname'];
            $username = $params['database']['username'];
            $userpassword = $params['database']['userpassword'];
            $database = $params['database']['database_name'];

            $DPNAV = new Databases($hostname, $username, $userpassword, $database);
            $tblname = "view_employees";
            ?>
            <script> 
                $(document).ready(function() {
                    var table = $('#<?php echo $tblname; ?>').DataTable( {
                        dom: 'Blfrtip',
                        "order": [
                            [ 9 , "asc"]
                        ],
                        select: {
                            style: 'single'
                        },
                        buttons: [
                            {
                                text: "<i class='fa fa-plus'></i>",
                                action: function () {
                                    var rownumber = table.rows({selected: true}).indexes();
                                    var employee_email = table.cell( rownumber,2 ).data();
                                    window.location.href = "index.php?mod=users&act=add&employee_email="+employee_email+"&submit=Submit";
                                }
                            },
                            {
                                extend: 'excelHtml5',
                                text: "<i class='fa fa-file-excel'></i>",
                                title: 'Employee List'
                            },
                        ],
                        "columnDefs": [
                            {
                                "targets": [2,8],
                                "visible": false
                            },
                            {
                                "targets": [5],
                                className: 'dt-body-center',
                            },
                            {
                                "targets": [11,12],
                                className: 'dt-body-right',
                                "render": DataTable.render.datetime('DD MMM YYYY'),
                            },

                        ],
                    });
                } );
            </script>


            <div class="col-lg-12">

                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-secondary">Select Structure</h6>
                        <?php spinner(); ?>
                        <div class="align-items-right">
                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#filter"><span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Filter data'><i class='fa fa-filter'></i></span></button>
                                <!-- <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#summaryBackup"><span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Asset by Category'><i class='fa fa-table'></i></span></button> -->
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-lg-12">
                            <div class="row mb-3">
                                <?php 
                                $tblname = "view_employees";
                                $primarykey = "job_name";
                                $condition='';
                                $totalRows=100;
                                $sambung="";
                                if(isset($_GET['organization']) && $_GET['organization']!='') {
                                    $condition="organization_name = '" . $_GET['organization'] . "'";
                                    $totalRows=0;
                                    $sambung=" AND ";
                                } 
                                if(isset($_GET['leader']) && $_GET['leader']!='') {
                                    $condition .= $sambung . "(leader_name LIKE '%" . $_GET['leader'] . "%' OR employee_name LIKE'%" . $_GET['leader'] . "%')";
                                    $totalRows=0;
                                    $sambung=" AND ";
                                }
                                if((isset($_GET['status']) && $_GET['status']=='active') || !isset($_GET['status'])) {
                                    $condition .= $sambung . " (resign_date = '0000-00-00' OR isnull(resign_date))";
                                    $totalRows=0;
                                } elseif(isset($_GET['status']) && $_GET['status']=='resign') {
                                    $condition .= $sambung . " resign_date <> '0000-00-00'";
                                    $totalRows=0;
                                }
                                if(!isset($_GET['status'])) {
                                    $totalRows = 100;
                                }
                                $order = "job_name DESC";

                                view_table($DPNAV, $tblname, $primarykey, $condition, $order, $firstRow = 0, $totalRows)
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
            <?php 
        } else {
            echo "Aplikasi belum disetup";            
        }
    } else { 
        $ALERT->notpermission();
    }
} 
?>

<!-- Modal -->
<div class="modal fade" id="filter" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="filter" aria-hidden="true">
    <div class="modal-dialog">    
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Filter Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form name="form" method="get" action="index.php">
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-lg-12">Departement:</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <select class="form-select" name="organization" aria-label="Default select example">
                                <?php
                                $mdlname = "HCM";
                                $DBHCM = get_conn($mdlname);
                                // $mysql = "SELECT `organization_name` FROM `sa_mst_organization` group by `organization_name` order by `organization_name`";
                                // $employees = $DBHCM->get_sql($mysql);
                                $tblname = "view_department";
                                $department = $DBHCM->get_data($tblname);
                                $ddepartment = $department[0];
                                $qdepartment = $department[1];
                                ?>
                                <option value=''>Select Status</option>
                                <?php do { ?>
                                    <option value="<?php echo $ddepartment['organization_name']; ?>" <?php if(isset($_GET['organization']) && $_GET['organization']==$ddepartment['organization_name']) { echo 'selected'; } ?>><?php echo $ddepartment['organization_name']; ?></option>
                                <?php } while($ddepartment=$qdepartment->fetch_assoc()); ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-12">Employee/Leader Name:</div>
                    </div>
                    <div class="rwo mb-3">
                        <input type="text" class="form-control form-control-sm" name="leader" id="leader" value="<?php if(isset($_GET['leader'])) { echo $_GET['leader']; } ?>" placeholder="Leader Name">
                    </div>
                    <?php 
                    // $ref = $_SERVER["HTTP_REFERER"];
                    if(isset($_GET['todo']) && $_GET['todo']=='list') {
                    ?>
                        <div class="row mb-3">
                            <div class="col-lg-12">Employee Status</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <select class="form-select" name="status" aria-label="Default select example">
                                    <option value="active">Active</option>
                                    <option value="resign" <?php if(isset($_GET['status']) && $_GET['status']=='resign') { echo 'selected'; } ?>>Resign</option>
                                </select>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <input type="hidden" name="mod" value="hcm">
                            <input type="hidden" name="sub" value="employee_list">
                            <input type="hidden" name="todo" value="<?php if(isset($_GET['todo'])) { echo $_GET['todo']; } ?>">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" name="btn_search" id="btn_search" value="Search">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
