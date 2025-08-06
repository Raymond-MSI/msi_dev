<?php
if((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $author = 'Syamsul Arham';
} else {
    $ref = $_SERVER['HTTP_REFERER'];
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
            $tblname = "view_employees_v2";
            $version = "";
            if(isset($_GET['version']))
            {
                $version = "&version=" . $_GET['version'];
            }
            ?>
            <script> 
                $(document).ready(function() {
                    var table = $('#<?php echo $tblname; ?>').DataTable( {
                        dom: 'Blfrtip',
                        "order": [
                            [ 15 , "desc"]
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
                                    window.location.href = "index.php?mod=users&act=add&employee_email="+employee_email+"<?php echo $version; ?>&submit=Submit";
                                }
                            },
                            {
                                text: "<i class='fa-solid fa-address-card'></i>",
                                action: function () 
                                {
                                    var rownumber = table.rows({selected: true}).indexes();
                                    var nik = table.cell( rownumber, 0 ).data();
                                    var employee_name = table.cell( rownumber, 1 ).data();
                                    var employee_email = table.cell( rownumber, 2 ).data();
                                    var division_name = table.cell( rownumber, 3 ).data();
                                    var department_name = table.cell( rownumber, 4 ).data();
                                    var section_name = table.cell( rownumber, 5 ).data();
                                    var unit_name = table.cell( rownumber, 6 ).data();
                                    var sub_unit_name = table.cell( rownumber, 7 ).data();
                                    // var division_name= table.cell( rownumber, 3 ).data();
                                    var job_title = table.cell( rownumber, 8 ).data();
                                    var job_level = table.cell( rownumber, 9 ).data();
                                    var job_structure = table.cell( rownumber, 10 ).data();
                                    var leader_name = table.cell( rownumber, 11 ).data();
                                    var leader_email = table.cell( rownumber, 12 ).data();
                                    var branch_name = table.cell( rownumber, 13 ).data();
                                    var job_name = table.cell( rownumber, 14).data();
                                    var join_date = table.cell( rownumber, 15 ).data();
                                    var resign_date = table.cell( rownumber, 16 ).data();
                                    var gender = table.cell( rownumber, 17 ).data();
                                    var religion = table.cell( rownumber, 18 ).data();
                                    var phone = table.cell( rownumber, 19 ).data();
                                    var handphone = table.cell( rownumber, 20 ).data();
                                    var employee_status = table.cell( rownumber, 21 ).data();
                                    var id_number = table.cell( rownumber, 22 ).data();
                                    var address = table.cell( rownumber, 23 ).data();
                                    var city = table.cell( rownumber, 24 ).data();
                                    var date_of_birth = table.cell( rownumber, 25 ).data();
                                    // const date_of_birth = format(new Date(year(date_of_birthx), month(date_of_birthx), day(date_of_birthx)));
                                    var place_of_birth = table.cell( rownumber, 26 ).data();
                                    var unitdrawing = table.cell( rownumber, 27 ).data();
                                    document.getElementById("nik").innerHTML = nik;
                                    document.getElementById("employee_name").innerHTML = employee_name;
                                    document.getElementById("employee_email").innerHTML = employee_email;
                                    document.getElementById("division_name").innerHTML = division_name;
                                    document.getElementById("department_name").innerHTML = department_name;
                                    document.getElementById("section_name").innerHTML = section_name;
                                    document.getElementById("unit_name").innerHTML = unit_name;
                                    document.getElementById("sub_unit_name").innerHTML = sub_unit_name;
                                    document.getElementById("job_title1").innerHTML = job_title;
                                    document.getElementById("job_title").innerHTML = job_title;
                                    document.getElementById("job_level").innerHTML = job_level;
                                    document.getElementById("job_structure").innerHTML = job_structure;
                                    document.getElementById("leader_name").innerHTML = leader_name;
                                    document.getElementById("leader_name1").innerHTML = leader_name;
                                    document.getElementById("leader_email").innerHTML = leader_email;
                                    document.getElementById("branch_name").innerHTML = branch_name;
                                    document.getElementById("job_name").innerHTML = job_name;
                                    // const join_datex = format_date(new Date(join_date));
                                    document.getElementById("join_date").innerHTML = join_date;
                                    // const resign_datex = format_date(new Date(resign_date));
                                    document.getElementById("resign_date").innerHTML = resign_date;
                                    document.getElementById("religion").innerHTML = religion;
                                    document.getElementById("gender").innerHTML = gender;
                                    document.getElementById("religion").innerHTML = religion;
                                    document.getElementById("phone").innerHTML = phone;
                                    document.getElementById("handphone").innerHTML = handphone;
                                    document.getElementById("employee_status").innerHTML = employee_status;
                                    document.getElementById("id_number").innerHTML = id_number;
                                    document.getElementById("addressx").innerHTML = address;
                                    document.getElementById("city").innerHTML = city;
                                    // const date_of_birthx = format_date(new Date(date_of_birth));
                                    document.getElementById("date_of_birth").innerHTML = date_of_birth;
                                    document.getElementById("place_of_birth").innerHTML = place_of_birth;
                                    // var img = "<img src='data:image/jpeg;base64, <?php //echo based64_encode("+unitdrawing+"); ?>'";
                                    // document.getElementById("unitdrawing").innerHTML = img;
                                    if(nik==null) {
                                        alert ("Please select the data.");
                                    } else {
                                        $('#profile').modal('show') 
                                    }

                                    if(employee_status=='Resigned')
                                    {
                                        document.getElementById("resigned").style.display = '';
                                    } else
                                    {
                                        document.getElementById("resigned").style.display = 'none';
                                    }
                                },
                                visible: false
                            },
                            {
                                extend: 'excelHtml5',
                                text: "<i class='fa fa-file-excel'></i>",
                                title: 'Employee List'
                            },
                        ],
                        "columnDefs": [
                            {
                                "targets": [2,3,4,5,6,7,12,13,14,15,16,17,18,19,20,22,23,24,25,26,27],
                                "visible": false
                            },
                            {
                                "targets": [9,21],
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
                                $tblname = "view_employees_v2";
                                $primarykey = "job_name";
                                $condition='';
                                $totalRows=100;
                                $sambung="";
                                if(isset($_GET['organization']) && $_GET['organization']!='') {
                                    $condition="division_name= '" . $_GET['organization'] . "'";
                                    $totalRows=0;
                                    $sambung=" AND ";
                                } 
                                if(isset($_GET['leader']) && $_GET['leader']!='') {
                                    $condition .= $sambung . "(leader_name LIKE '%" . addslashes($_GET['leader']) . "%' OR employee_name LIKE'%" . addslashes($_GET['leader']) . "%')";
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
                    <?php //module_version("HCM"); ?>
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
<!-- Filter data -->
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
                            <input type="hidden" name="version" value="<?php echo $ref; ?>">
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

<!-- Profile -->
<div class="modal fade" id="profile" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="profile" aria-hidden="true">
    <div class="modal-dialog modal-xl">    
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Employee Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- <form name="form" method="get" action="index.php"> -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="row">
                                <img class='img-profile' id='unitdrawing' src='media/images/profiles/blank-profile.png' />
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="row">
                                            <label for="inputKP3" class="col-sm-4 col-form-label col-form-label-sm">NIK</label>
                                            <label for="inputKP3" class="col-sm-8 col-form-label col-form-label-sm" id="nik"></label>
                                        </div>
                                        <div class="row">
                                            <label for="inputKP3" class="col-sm-4 col-form-label col-form-label-sm">Employee</label>
                                            <label for="inputKP3" class="col-sm-8 col-form-label col-form-label-sm" id="employee_name"></label>
                                        </div>
                                        <div class="row">
                                            <label for="inputKP3" class="col-sm-4 col-form-label col-form-label-sm">Title</label>
                                            <label for="inputKP3" class="col-sm-8 col-form-label col-form-label-sm" id="job_title1"></label>
                                        </div>
                                        <div class="row">
                                            <label for="inputKP3" class="col-sm-4 col-form-label col-form-label-sm">Leader</label>
                                            <label for="inputKP3" class="col-sm-8 col-form-label col-form-label-sm" id="leader_name1"></label>
                                        </div>
                                        <div class="row">
                                            <label for="inputKP3" class="col-sm-4 col-form-label col-form-label-sm">Join</label>
                                            <label for="inputKP3" class="col-sm-8 col-form-label col-form-label-sm" id="join_date"></label>
                                        </div>
                                        <div class="row">
                                            <label for="inputKP3" class="col-sm-4 col-form-label col-form-label-sm">Status</label>
                                            <label for="inputKP3" class="col-sm-8 col-form-label col-form-label-sm" id="employee_status"></label>
                                        </div>
                                        <div class="row" id="resigned">
                                            <label for="inputKP3" class="col-sm-4 col-form-label col-form-label-sm">Resign</label>
                                            <label for="inputKP3" class="col-sm-8 col-form-label col-form-label-sm" id="resign_date"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active text-body" id="home-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab" aria-controls="info" aria-selected="true" title='SB yang masih dalam bentuk draft'>Profile</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link text-body" id="submit-tab" data-bs-toggle="tab" data-bs-target="#organization" type="button" role="tab" aria-controls="organization" aria-selected="false" title='SB yang sudah disubmit ke manager'>Organization</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">

                                <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <label for="inputKP3" class="col-sm-2 col-form-label col-form-label-sm">ID Number</label>
                                                <label for="inputKP3" class="col-sm-10 col-form-label col-form-label-sm" id="id_number"></label>
                                            </div>
                                            <div class="row">
                                                <label for="inputKP3" class="col-sm-2 col-form-label col-form-label-sm">Gender</label>
                                                <label for="inputKP3" class="col-sm-10 col-form-label col-form-label-sm" id="gender"></label>
                                            </div>
                                            <div class="row">
                                                <label for="inputKP3" class="col-sm-2 col-form-label col-form-label-sm">Religion</label>
                                                <label for="inputKP3" class="col-sm-10 col-form-label col-form-label-sm" id="religion"></label>
                                            </div>
                                            <div class="row">
                                                <label for="inputKP3" class="col-sm-2 col-form-label col-form-label-sm">Bird Date</label>
                                                <label for="inputKP3" class="col-sm-10 col-form-label col-form-label-sm" id="date_of_birth"></label>
                                            </div>
                                            <div class="row">
                                                <label for="inputKP3" class="col-sm-2 col-form-label col-form-label-sm">Bird Place</label>
                                                <label for="inputKP3" class="col-sm-10 col-form-label col-form-label-sm" id="place_of_birth"></label>
                                            </div>
                                            <div class="row">
                                                <label for="inputKP3" class="col-sm-2 col-form-label col-form-label-sm">Email</label>
                                                <label for="inputKP3" class="col-sm-10 col-form-label col-form-label-sm" id="employee_email"></label>
                                            </div>
                                            <div class="row">
                                                <label for="inputKP3" class="col-sm-2 col-form-label col-form-label-sm">Phone</label>
                                                <label for="inputKP3" class="col-sm-10 col-form-label col-form-label-sm" id="phone"></label>
                                            </div>
                                            <div class="row">
                                                <label for="inputKP3" class="col-sm-2 col-form-label col-form-label-sm">Hand Phone</label>
                                                <label for="inputKP3" class="col-sm-10 col-form-label col-form-label-sm" id="handphone"></label>
                                            </div>
                                            <div class="row">
                                                <label for="inputKP3" class="col-sm-2 col-form-label col-form-label-sm">Address</label>
                                                <label for="inputKP3" class="col-sm-10 col-form-label col-form-label-sm" id="addressx"></label>
                                            </div>
                                            <div class="row">
                                                <label for="inputKP3" class="col-sm-2 col-form-label col-form-label-sm">City</label>
                                                <label for="inputKP3" class="col-sm-10 col-form-label col-form-label-sm" id="city"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="organization" role="tabpanel" aria-labelledby="organization-tab">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <label for="inputKP3" class="col-sm-2 col-form-label col-form-label-sm">Job Name</label>
                                                <label for="inputKP3" class="col-sm-10 col-form-label col-form-label-sm" id="job_name"></label>
                                            </div>
                                            <div class="row">
                                                <label for="inputKP3" class="col-sm-2 col-form-label col-form-label-sm">Job Structure</label>
                                                <label for="inputKP3" class="col-sm-10 col-form-label col-form-label-sm" id="job_structure"></label>
                                            </div>
                                            <div class="row">
                                                <label for="inputKP3" class="col-sm-2 col-form-label col-form-label-sm">Job Title</label>
                                                <label for="inputKP3" class="col-sm-10 col-form-label col-form-label-sm" id="job_title"></label>
                                            </div>
                                            <div class="row">
                                                <label for="inputKP3" class="col-sm-2 col-form-label col-form-label-sm">Job Level</label>
                                                <label for="inputKP3" class="col-sm-10 col-form-label col-form-label-sm" id="job_level"></label>
                                            </div>
                                            <div class="row">
                                                <label for="inputKP3" class="col-sm-2 col-form-label col-form-label-sm">Branch</label>
                                                <label for="inputKP3" class="col-sm-10 col-form-label col-form-label-sm" id="branch_name"></label>
                                            </div>
                                            <div class="row">
                                                <label for="inputKP3" class="col-sm-2 col-form-label col-form-label-sm">Division</label>
                                                <label for="inputKP3" class="col-sm-10 col-form-label col-form-label-sm" id="division_name"></label>
                                            </div>
                                            <div class="row">
                                                <label for="inputKP3" class="col-sm-2 col-form-label col-form-label-sm">Department</label>
                                                <label for="inputKP3" class="col-sm-10 col-form-label col-form-label-sm" id="department_name"></label>
                                            </div>
                                            <div class="row">
                                                <label for="inputKP3" class="col-sm-2 col-form-label col-form-label-sm">Section</label>
                                                <label for="inputKP3" class="col-sm-10 col-form-label col-form-label-sm" id="section_name"></label>
                                            </div>
                                            <div class="row">
                                                <label for="inputKP3" class="col-sm-2 col-form-label col-form-label-sm">Unit</label>
                                                <label for="inputKP3" class="col-sm-10 col-form-label col-form-label-sm" id="unit_name"></label>
                                            </div>
                                            <div class="row">
                                                <label for="inputKP3" class="col-sm-2 col-form-label col-form-label-sm">Sub-Unit</label>
                                                <label for="inputKP3" class="col-sm-10 col-form-label col-form-label-sm" id="sub_unit_name"></label>
                                            </div>
                                            <div class="row">
                                                <label for="inputKP3" class="col-sm-2 col-form-label col-form-label-sm">Leader Name</label>
                                                <label for="inputKP3" class="col-sm-10 col-form-label col-form-label-sm" id="leader_name"></label>
                                            </div>
                                            <div class="row">
                                                <label for="inputKP3" class="col-sm-2 col-form-label col-form-label-sm">Leader Email</label>
                                                <label for="inputKP3" class="col-sm-10 col-form-label col-form-label-sm" id="leader_email"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                           </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            <!-- </form> -->
        </div>
    </div>
</div>
