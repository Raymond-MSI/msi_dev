<?php
global $DTSB;

// echo $_SESSION['Microservices_UserEmail'] . "<br/>";
// echo $_SESSION['Microservices_UserName'];

if ((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $released = "1667208841";
    $author = 'Syamsul Arham';
} else {
    $modulename = "Project Charter MT";
    $userpermission = useraccess($modulename);

    // if(USERPERMISSION=="7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION=="dbf36ff3e3827639223983ee8ac47b42" || USERPERMISSION == '0162bce636a63c3ae499224203e06ed0' || USERPERMISSION == '5898299487c5b9cdbe7d61809fd20213'){
?>
    <?php
    if (!isset($_GET['act']) && !isset($_GET['status'])) { ?>
        <script>
            $(document).ready(function() {
                var table = $('#resource_assignment').DataTable({
                    dom: 'Blfrtip',
                    select: {
                        style: 'single'
                    },
                    buttons: [{
                            extend: 'colvis',
                            text: "<i class='fa fa-columns'></i>",
                            collectionLayout: 'fixed four-column'
                        },
                        {
                            text: "<i class='fa fa-eye'></i>",
                            action: function() {
                                var rownumber = table.rows({
                                    selected: true
                                }).indexes();
                                var id = table.cell(rownumber, 0).data();
                                window.location.href = "index.php?mod=resource_assignment_mt&act=view&id=" +
                                    id + "&submit=Submit";
                            },
                            enabled: false
                        },
                        {
                            text: "<i class='fa fa-pen'></i>",
                            action: function() {
                                var rownumber = table.rows({
                                    selected: true
                                }).indexes();
                                var id = table.cell(rownumber, 0).data();
                                var project_code = table.cell(rownumber, 1).data();
                                var no_so = table.cell(rownumber, 2).data();
                                var project_type = table.cell(rownumber, 3).data();
                                if (id == null) {
                                    alert("Mohon pilih data yang ingin diedit");
                                } else {
                                    window.location.href =
                                        "index.php?mod=resource_assignment_mt&act=edit&project_code=" +
                                        project_code + "&no_so=" + no_so + "&project_type=" + project_type +
                                        "&id=" + id + "&submit=Submit";
                                }
                            }
                        },
                        {
                            text: "<i class='fa fa-plus'></i>",
                            action: function() {
                                window.location.href = "index.php?mod=resource_assignment_mt&act=add";
                            },
                            // enabled: false
                        }
                    ],
                    "columnDefs": [{
                        "targets": [0],
                        "visible": false,
                    }, ],
                });
            });
        </script>
        <?php
    } else {
        if (!isset($_GET['act']) && $_GET['status'] == 'report_project_charter') { ?>
            <script>
                $(document).ready(function() {
                    var table = $('#resource_assignment').DataTable({
                        dom: 'Blfrtip',
                        select: {
                            style: 'single'
                        },
                        buttons: [{
                            extend: 'excelHtml5',
                            text: "<i class='fa fa-file-pdf'></i>",
                            title: 'Report_Project_Charter_' + <?php echo date("YmdGis"); ?>
                        }],
                        "columnDefs": [{
                            "targets": [0],
                            "visible": false,
                        }],
                    });
                });
            </script>
        <?php } else if (!isset($_GET['act']) && $_GET['status'] == 'approved_assignment' || !isset($_GET['act']) && $_GET['status'] == 'rejected_assignment') { ?>
            <script>
                $(document).ready(function() {
                    var table = $('#resource_assignment').DataTable({
                        dom: 'Blfrtip',
                        select: {
                            style: 'single'
                        },
                        buttons: [{
                            extend: 'excelHtml5',
                            text: "<i class='fa fa-file-pdf'></i>",
                            title: 'Report_Project_Charter_' + <?php echo date("YmdGis"); ?>
                        }],
                        "columnDefs": [{
                            "targets": [0],
                            "visible": false,
                        }],
                    });
                });
            </script>
    <?php }
    } ?>


    <?php
    // Function
    // if($_SESSION['Microservices_UserLevel'] == "Administrator") {
    function view_data($tblname)
    {
        // Definisikan tabel yang akan ditampilkan dalam DataTable
        global $DTSB;
        $primarykey = "id";
        $order = "";
        $condition = "project_type = 'Maintenance'";
        $firstRow = 0;
        $totalRow = 10000;
        $index = "";
        if (isset($_GET['err']) && $_GET['err'] == "datanotfound") {
            global $ALERT;
            $ALERT->datanotfound();
        }

        view_table($DTSB, $tblname, $primarykey, $condition, $order, $firstRow, $totalRow, $index);
    }

    function view_data_approval_report($tblname, $condition)
    {
        // Definisikan tabel yang akan ditampilkan dalam DataTable
        global $DBKPI;
        $primarykey = "id";
        $order = "";
        $firstRow = 0;
        $totalRow = 10000;
        $index = "";
        if (isset($_GET['err']) && $_GET['err'] == "datanotfound") {
            global $ALERT;
            $ALERT->datanotfound();
        }

        view_table($DBKPI, $tblname, $primarykey, $condition, $order, $firstRow, $totalRow, $index);
    }

    function view_data_approval($tblname, $condition)
    {
        // Definisikan tabel yang akan ditampilkan dalam DataTable
        global $DTSB;
        $primarykey = "id";
        $order = "";
        $firstRow = 0;
        $totalRow = 10000;
        $index = "";
        if (isset($_GET['err']) && $_GET['err'] == "datanotfound") {
            global $ALERT;
            $ALERT->datanotfound();
        }

        view_table($DTSB, $tblname, $primarykey, $condition, $order, $firstRow, $totalRow, $index);
    }

    function view_data_approval2($tblname, $condition)
    {
        // Definisikan tabel yang akan ditampilkan dalam DataTable
        global $DTSB;
        $primarykey = "id";
        $order = "";
        if (isset($_GET['err']) && $_GET['err'] == "datanotfound") {
            global $ALERT;
            $ALERT->datanotfound();
        }

        view_table($DTSB, $tblname, $primarykey, $condition, $order);
    }

    function view_data_temporary($tblnametemporary, $condition)
    {
        // Definisikan tabel yang akan ditampilkan dalam DataTable
        global $DTSB;
        $primarykey = "id";
        $order = "";
        if (isset($_GET['err']) && $_GET['err'] == "datanotfound") {
            global $ALERT;
            $ALERT->datanotfound();
        }

        view_table($DTSB, $tblnametemporary, $primarykey, $condition, $order);
    }

    function form_data($tblname)
    {
        global $DTSB;
        include("components/modules/resource_assignment_mt/form_resource_assignment.php");
    }

    function jobs_data($tblname)
    {
        include("components/modules/resource_assignment_mt/form_update_approval.php");
    }
    // End Function

    //   $database = 'sa_wrike_integrate';
    //   include("components/modules/resource_assignment/connection.php");
    //   $DB = new Databases($hostname, $username, $userpassword, $database);
    //   $tblname = 'resource_assignment';

    // $tblname = 'cfg_web';
    // $condition = 'config_key="MODULE_RESOURCE_ASSIGNMENT_MT"';
    // $setupDB = $DB->get_data($tblname, $condition);
    // $dsetupDB = $setupDB[0];
    // if ($setupDB[2] > 0) {
    //     $params = get_params($dsetupDB['params']);
    //     $hostname = $params['database']['hostname'];
    //     $username = $params['database']['username'];
    //     $userpassword = $params['database']['userpassword'];
    //     $database = $params['database']['database_name'];

    // $DTSB = new Databases($hostname, $username, $userpassword, $database);
    $DTSB = get_conn("WRIKE_INTEGRATE");
    // $tblname = 'view_resource_assignment';
    $tblname = 'resource_assignment';
    $tblnametemporary = 'temporary_resource';
    $DBKPI = get_conn("DASHBOARD_KPI");
    $DBSB = get_conn("SERVICE_BUDGET");
    $tbl_report = 'master_maintenance_date';

    include("components/modules/resource_assignment_mt/func_resource_assignment.php");
    // include("components/classes/func_hcm.php");
    // Bodyyy
    ?>
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Project Charter Maintenance</h6>
            </div>
            <div class="card-body">
                <?php if (!isset($_GET['act'])) { ?>
                    <select name="" id="assignment_status">
                        <option value="my_assignment">My Assignment</option>
                        <option value="pending_assignment">Pending Assignment</option>
                        <option value="pending_baseline_report">Pending Baseline Report</option>
                        <option value="approved_assignment">Approved Assignment</option>
                        <option value="approved_baseline_report">Approved Baseline Report</option>
                        <option value="rejected_assignment">Rejected Assignment</option>
                        <option value="rejected_baseline_report">Rejected Baseline Report</option>
                        <?php
                        if (USERPERMISSION == "dbf36ff3e3827639223983ee8ac47b42" || USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION == "5898299487c5b9cdbe7d61809fd20213") {
                        ?>
                            <option value="temporary_assignment">Temporary Assignment</option>
                        <?php } ?>
                        <option value="report_project_charter_mt">Report Project Charter</option>
                    </select>
                    <?php if (!isset($_GET['status'])) { ?>
                    <?php } else { ?>
                        <?php if (!isset($_GET['act']) && $_GET['status'] == 'report_project_charter_mt') { ?>
                            <select name="" id="type_project">
                                <option value="Maintenance">Maintenance</option>
                            </select>
                            <select name="" id="project_code">
                                <?php $DBWR = get_conn("WRIKE_INTEGRATE");
                                $get_project = $DWBR->get_sqlV2("SELECT * FROM sa_resource_assignment GROUP BY project_id");
                                while ($row = $get_project[1]->fetch_assoc()) { ?>
                                    <option value="<?php echo $row['project_id'] ?>">
                                        <?php echo $row['project_code'] . " - " . $row['no_so'] . " - " . $row['order_number']; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                            <select name="" id="project_code">
                                <?php $get_resource = $DWBR->get_sqlV2("SELECT DISTINCT resource_email FROM sa_resource_assignment");
                                while ($row = $get_resource[1]->fetch_assoc()) {
                                    $nama = explode("<", $row['resource_email']);
                                    $email = explode(">", $nama[1]); ?>
                                    <option value="<?php echo $email[0] ?>"><?php echo $row['resource_email']; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
                <?php
                if (!isset($_GET['act']) && !isset($_GET['status'])) {
                    if (USERPERMISSION == "dbf36ff3e3827639223983ee8ac47b42" || USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b") {
                        $condition = "project_type='Maintenance'";
                        view_data($tblname, $condition);
                    } else {
                        $condition = "project_type = 'Maintenance' and (approval_to LIKE '%" . $_SESSION['Microservices_UserEmail'] . "%' OR created_by LIKE '%" . $_SESSION['Microservices_UserEmail'] . "%')";
                        view_data_approval($tblname, $condition);
                    }
                } elseif (!isset($_GET['act']) && $_GET['mod'] == "resource_assignment_mt" && $_GET['status'] == 'pending_assignment') {
                    if (USERPERMISSION == "dbf36ff3e3827639223983ee8ac47b42" || USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b") {
                ?>
                        <div class="col-12 mt-3">
                            <hr />
                            <form method="POST" action="index.php?mod=<?php echo $_GET['mod']; ?>" enctype="multipart/form-data">
                                <table id="example" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Checklist</th>
                                            <th>Project Code</th>
                                            <th>SO Number</th>
                                            <th>Customer Name</th>
                                            <th>Project Name</th>
                                            <th>Resource Email</th>
                                            <th>Roles SBF</th>
                                            <th>Roles Project</th>
                                            <th>Status</th>
                                            <th>Start Assignment</th>
                                            <th>End Assignment</th>
                                            <th>Created By</th>
                                            <th>Approval To</th>
                                            <th>Approval Status</th>
                                            <th>Created In</th>
                                            <th>Check Data</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sqlViewData = "SELECT * from sa_resource_assignment WHERE project_type = 'Maintenance' and approval_status = 'pending'";
                                        $getData = $DTSB->get_sql($sqlViewData);
                                        $rowData = $getData[0];
                                        $resData = $getData[1];
                                        $totalRowData = $getData[2];

                                        if ($totalRowData > 0) {
                                            do {
                                                $assignmentId = $rowData['id'];
                                                $projectCode = $rowData['project_code'];
                                                $soNumber = $rowData['no_so'];
                                                $customerName = $rowData['customer_name'];
                                                $projectName = $rowData['project_name'];
                                                $rawResourceEmail = $rowData['resource_email'];
                                                $rawResourceEmail2 = explode("<", $rawResourceEmail);
                                                $resourceEmail = str_replace(">", "", $rawResourceEmail2[1]);
                                                $rawRoles = $rowData['roles'];
                                                $roles = str_replace(" - ", " ", "$rawRoles");
                                                $project_roles = $rowData['project_roles'];
                                                $status = $rowData['status'];
                                                $startProgress = $rowData['start_progress'];
                                                $endProgress = $rowData['end_progress'];
                                                $start_assignment = $rowData['start_date'];
                                                $end_assignment = $rowData['end_date'];
                                                $finalStatus = $status;
                                                $createdBy = $rowData['created_by'];
                                                $approvalTo = $rowData['approval_to'];
                                                $approvalStatus = $rowData['approval_status'];
                                                $createdIn = $rowData['created_in_msizone'];

                                                $userSession = $_SESSION['Microservices_UserName'] . "<" . $_SESSION['Microservices_UserEmail'] . ">";
                                        ?>
                                                <tr>
                                                    <td><input type="checkbox" name="id[]" class="ml-auto form-check-input chk_boxes1"
                                                            value="<?php echo $assignmentId ?>" style="width: 20px; height: 20px;"></td>
                                                    <td><?php echo $projectCode; ?></td>
                                                    <td><?php echo $soNumber; ?></td>
                                                    <td><?php echo $customerName; ?></td>
                                                    <td><?php echo $projectName; ?></td>
                                                    <td><?php echo $resourceEmail; ?></td>
                                                    <td><?php echo $roles; ?></td>
                                                    <td><?php echo $project_roles; ?></td>
                                                    <td><?php echo $finalStatus; ?></td>
                                                    <td><?php echo $start_assignment; ?></td>
                                                    <td><?php echo $end_assignment; ?></td>
                                                    <td><?php echo $createdBy; ?></td>
                                                    <td><?php echo $approvalTo; ?></td>
                                                    <td><?php echo $approvalStatus; ?></td>
                                                    <td><?php echo $createdIn; ?></td>
                                                    <td><button type="button" value=<?php echo $assignmentId; ?> class="btn btn-warning"
                                                            data-toggle="modal" data-target="#modal-<?php echo $assignmentId; ?>">Check
                                                            Resource</button></td>
                                                </tr>

                                                <!-- MODAL -->
                                                <div class="modal fade" id="modal-<?php echo $assignmentId; ?>" tabindex="-1" role="dialog"
                                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document" style='max-width: 50% !important;'>
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Resource Information Detail
                                                                </h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <?php
                                                                $sqlCheckResourceAssignment = "SELECT * FROM sa_resource_assignment WHERE project_type = 'Maintenance' and resource_email LIKE '%$resourceEmail%' AND approval_status = 'approved'";
                                                                $dataResourceAssignment = $DTSB->get_sql($sqlCheckResourceAssignment);
                                                                $rowRA = $dataResourceAssignment[0];
                                                                $resRA = $dataResourceAssignment[1];
                                                                $totalRowRA = $dataResourceAssignment[2];

                                                                if ($totalRowRA > 0) {
                                                                ?>
                                                                    <h4>Resource ini di assign di :</h4>
                                                                <?php
                                                                    $number = 1;
                                                                    do {
                                                                        $projectCodeRA = $rowRA['project_code'];
                                                                        $noSORA = $rowRA['no_so'];
                                                                        $projectTypeRA = $rowRA['project_type'];
                                                                        $customerNameRA = $rowRA['customer_name'];
                                                                        $rolesRA = $rowRA['roles'];
                                                                        $rolesFinalRA = str_replace(" - ", " ", $rolesRA);
                                                                        $statusRA = $rowRA['status'];
                                                                        $createdInRA = $rowRA['created_in_msizone'];

                                                                        echo "
                                                                $number. $projectCodeRA   -   $noSORA  -  $projectTypeRA  -  $customerNameRA  -  $rolesFinalRA  -  $statusRA  -  $createdInRA  <br/> 
                                                            ";
                                                                        $number++;
                                                                    } while ($rowRA = $resRA->fetch_assoc());
                                                                }
                                                                ?>

                                                                <h4 class="mt-3">Project yang sedang dikerjakan : <?php echo $totalRowRA; ?>
                                                                </h4>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                            } while ($rowData = $resData->fetch_assoc());
                                        } else {
                                            ?>
                                            <td colspan="12">
                                                <center>Belum ada approval</center>
                                            </td>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th><input type="checkbox" class="check_all" style="width: 20px; height: 20px;" />
                                                Checklist All</th>
                                            <th>Project Code</th>
                                            <th>SO Number</th>
                                            <th>Customer Name</th>
                                            <th>Project Name</th>
                                            <th>Resource Email</th>
                                            <th>Roles</th>
                                            <th>Status</th>
                                            <th>Start Assignment</th>
                                            <th>End Assignment</th>
                                            <th>Created By</th>
                                            <th>Approval To</th>
                                            <th>Approval Status</th>
                                            <th>Created In</th>
                                            <th>Check Data</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <hr />
                                <input type="hidden" name="userSession" value="<?php echo $userSession; ?>">
                                <button type="submit" name="btn_approve" id="btn_approve" class="btn btn-primary mt-1"
                                    onclick="javascript: return confirm('Apakah Anda yakin ingin approve data ini?')">Approve</button>
                                <button type="submit" name="btn_reject" id="btn_danger" class="btn btn-danger mt-1"
                                    onclick="javascript: return confirm('Apakah Anda yakin ingin reject data ini?')">Reject</button>
                            </form>
                        </div>

                    <?php
                    } else if (USERPERMISSION == "0162bce636a63c3ae499224203e06ed0") {
                    ?>
                        <div class="col-12 mt-3">
                            <hr />
                            <form method="POST" action="index.php?mod=<?php echo $_GET['mod']; ?>" enctype="multipart/form-data">
                                <table id="example" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Checklist</th>
                                            <th>Project Code</th>
                                            <th>SO Number</th>
                                            <th>Customer Name</th>
                                            <th>Project Name</th>
                                            <th>Resource Email</th>
                                            <th>Roles</th>
                                            <th>Status</th>
                                            <th>Start Assignment</th>
                                            <th>End Assignment</th>
                                            <th>Created By</th>
                                            <th>Approval To</th>
                                            <th>Approval Status</th>
                                            <th>Created In</th>
                                            <th>Check Data</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sqlViewData = "SELECT * from sa_resource_assignment WHERE project_type = 'Maintenance' and approval_to LIKE '%" . $_SESSION['Microservices_UserEmail'] . "%' AND approval_status = 'pending'";
                                        $getData = $DTSB->get_sql($sqlViewData);
                                        $rowData = $getData[0];
                                        $resData = $getData[1];
                                        $totalRowData = $getData[2];

                                        if ($totalRowData > 0) {
                                            do {
                                                $assignmentId = $rowData['id'];
                                                $projectCode = $rowData['project_code'];
                                                $soNumber = $rowData['no_so'];
                                                $customerName = $rowData['customer_name'];
                                                $projectName = $rowData['project_name'];
                                                $rawResourceEmail = $rowData['resource_email'];
                                                $rawResourceEmail2 = explode("<", $rawResourceEmail);
                                                $resourceEmail = str_replace(">", "", $rawResourceEmail2[1]);
                                                $roles = $rowData['project_roles'];
                                                //$roles = str_replace(" - ", " ", "$rawRoles");
                                                $start_assignment = $rowData['start_date'];
                                                $end_assignment = $rowData['end_date'];
                                                $status = $rowData['status'];
                                                $startProgress = $rowData['start_progress'];
                                                $endProgress = $rowData['end_progress'];
                                                $finalStatus = $status;
                                                $createdBy = $rowData['created_by'];
                                                $approvalTo = $rowData['approval_to'];
                                                $approvalStatus = $rowData['approval_status'];
                                                $createdIn = $rowData['created_in_msizone'];

                                                $userSession = $_SESSION['Microservices_UserName'] . "<" . $_SESSION['Microservices_UserEmail'] . ">";
                                        ?>
                                                <tr>
                                                    <td><input type="checkbox" name="id[]" class="ml-auto form-check-input chk_boxes1"
                                                            value="<?php echo $assignmentId ?>" style="width: 20px; height: 20px;"></td>
                                                    <td><?php echo $projectCode; ?></td>
                                                    <td><?php echo $soNumber; ?></td>
                                                    <td><?php echo $customerName; ?></td>
                                                    <td><?php echo $projectName; ?></td>
                                                    <td><?php echo $resourceEmail; ?></td>
                                                    <td><?php echo $roles; ?></td>
                                                    <td><?php echo $finalStatus; ?></td>
                                                    <td><?php echo $start_assignment; ?></td>
                                                    <td><?php echo $end_assignment; ?></td>
                                                    <td><?php echo $createdBy; ?></td>
                                                    <td><?php echo $approvalTo; ?></td>
                                                    <td><?php echo $approvalStatus; ?></td>
                                                    <td><?php echo $createdIn; ?></td>
                                                    <td><button type="button" value=<?php echo $assignmentId; ?> class="btn btn-warning"
                                                            data-toggle="modal" data-target="#modal-<?php echo $assignmentId; ?>">Check
                                                            Resource</button></td>
                                                </tr>

                                                <!-- MODAL -->
                                                <div class="modal fade" id="modal-<?php echo $assignmentId; ?>" tabindex="-1" role="dialog"
                                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document" style='max-width: 50% !important;'>
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Resource Information Detail
                                                                </h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <?php
                                                                $sqlCheckResourceAssignment = "SELECT * FROM sa_resource_assignment WHERE project_type='Maintenance' AND resource_email LIKE '%$resourceEmail%' AND approval_status = 'approved'";
                                                                $dataResourceAssignment = $DTSB->get_sql($sqlCheckResourceAssignment);
                                                                $rowRA = $dataResourceAssignment[0];
                                                                $resRA = $dataResourceAssignment[1];
                                                                $totalRowRA = $dataResourceAssignment[2];

                                                                if ($totalRowRA > 0) {
                                                                ?>
                                                                    <h4>Resource ini di assign di :</h4>
                                                                <?php
                                                                    $number = 1;
                                                                    do {
                                                                        $projectCodeRA = $rowRA['project_code'];
                                                                        $noSORA = $rowRA['no_so'];
                                                                        $projectTypeRA = $rowRA['project_type'];
                                                                        $customerNameRA = $rowRA['customer_name'];
                                                                        $rolesRA = $rowRA['roles'];
                                                                        $rolesFinalRA = str_replace(" - ", " ", $rolesRA);
                                                                        $statusRA = $rowRA['status'];
                                                                        $createdInRA = $rowRA['created_in_msizone'];

                                                                        echo "
                                                                $number. $projectCodeRA   -   $noSORA  -  $projectTypeRA  -  $customerNameRA  -  $rolesFinalRA  -  $statusRA  -  $createdInRA  <br/> 
                                                            ";
                                                                        $number++;
                                                                    } while ($rowRA = $resRA->fetch_assoc());
                                                                }
                                                                ?>

                                                                <h4 class="mt-3">Project yang sedang dikerjakan : <?php echo $totalRowRA; ?>
                                                                </h4>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                            } while ($rowData = $resData->fetch_assoc());
                                        } else {
                                            ?>
                                            <td colspan="12">
                                                <center>Belum ada approval</center>
                                            </td>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th><input type="checkbox" class="check_all" style="width: 20px; height: 20px;" />
                                                Checklist All</th>
                                            <th>Project Code</th>
                                            <th>SO Number</th>
                                            <th>Customer Name</th>
                                            <th>Project Name</th>
                                            <th>Resource Email</th>
                                            <th>Roles</th>
                                            <th>Status</th>
                                            <th>Start Assignment</th>
                                            <th>End Assignment</th>
                                            <th>Created By</th>
                                            <th>Approval To</th>
                                            <th>Approval Status</th>
                                            <th>Created In</th>
                                            <th>Check Data</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <hr />
                                <input type="hidden" name="userSession" value="<?php echo $userSession; ?>">
                                <button type="submit" name="btn_approve" id="btn_approve" class="btn btn-primary mt-1"
                                    onclick="javascript: return confirm('Apakah Anda yakin ingin approve data ini?')">Approve</button>
                                <button type="submit" name="btn_reject" id="btn_danger" class="btn btn-danger mt-1"
                                    onclick="javascript: return confirm('Apakah Anda yakin ingin reject data ini?')">Reject</button>
                            </form>
                        </div>
                    <?php
                    } else {
                        // $condition = "approval_to LIKE '%".$_SESSION['Microservices_UserEmail']."%' AND approval_status = 'pending'";
                        // view_data_approval($tblname, $condition);
                        $condition = "project_type='Maintenance' and approval_status = 'pending' AND created_by LIKE '%" . $_SESSION['Microservices_UserEmail'] . "%'";
                        view_data_approval($tblname, $condition);
                    }
                } else if (!isset($_GET['act']) && $_GET['mod'] == "resource_assignment_mt" && $_GET['status'] == "pending_baseline_report") {
                    if (USERPERMISSION == "dbf36ff3e3827639223983ee8ac47b42" || USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b") {
                    ?>
                        <div class="col-12 mt-3">
                            <hr />
                            <form method="POST" action="index.php?mod=<?php echo $_GET['mod']; ?>" enctype="multipart/form-data">
                                <table id="example" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Checklist</th>
                                            <th>Project Code</th>
                                            <th>SO Number</th>
                                            <th>Customer Name</th>
                                            <th>Project Name</th>
                                            <th>Requester</th>
                                            <th>Periode Maintenance</th>
                                            <th>Total Preventive Maintenance</th>
                                            <th>Total Maintenance Report</th>
                                            <th>Total Ticket Allocation</th>
                                            <th>Total Preventive & Maintenance Report</th>
                                            <th>Approval To</th>
                                            <th>Approval Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sqlViewData = "SELECT * from sa_master_maintenance_date WHERE approval_status = 'pending'";
                                        $getData = $DBKPI->get_sql($sqlViewData);
                                        $rowData = $getData[0];
                                        $resData = $getData[1];
                                        $totalRowData = $getData[2];

                                        if ($totalRowData > 0) {
                                            do {
                                                $assignmentId = $rowData['id'];
                                                $projectCode = $rowData['project_code'];
                                                $order_number = $rowData['order_number'];
                                                $rawResourceEmail = $rowData['entry_by'];
                                                $rawResourceEmail2 = explode("<", $rawResourceEmail);
                                                $resourceEmail = str_replace(">", "", $rawResourceEmail2[1]);
                                                $approvalTo = $rowData['approval_to'];
                                                $approvalStatus = $rowData['approval_status'];
                                                $total_preventive = $rowData['total_preventive_mt_date'];
                                                $total_maintenance_report = $rowData['total_mt_report_date'];
                                                $total_ticket_allocation = $rowData['total_ticket_allocation'];
                                                $total_pmr_date = $rowData['total_pmr_date'];
                                                $get_information_project = $DBSB->get_sqlV2("SELECT * FROM sa_trx_project_list WHERE project_code='$projectCode' AND order_number='$order_number'");
                                                $soNumber = $get_information_project[0]['so_number'];
                                                $customerName = $get_information_project[0]['customer_name'];
                                                $projectName = $get_information_project[0]['project_name'];
                                                $get_data_periode_start = $DBKPI->get_sqlV2("SELECT * FROM sa_maintenance_date WHERE project_code='$projectCode' AND order_number='$order_number' AND id_date='mt_date_start'");
                                                $start_date = $get_data_periode_start[0]['date'];
                                                $get_data_periode_end = $DBKPI->get_sqlV2("SELECT * FROM sa_maintenance_date WHERE project_code='$projectCode' AND order_number='$order_number' AND id_date='mt_date_end'");
                                                $end_date = $get_data_periode_end[0]['date'];


                                                $userSession = $_SESSION['Microservices_UserName'] . "<" . $_SESSION['Microservices_UserEmail'] . ">";
                                        ?>
                                                <tr>
                                                    <td><input type="checkbox" name="id[]" class="ml-auto form-check-input chk_boxes1"
                                                            value="<?php echo $assignmentId ?>" style="width: 20px; height: 20px;"></td>
                                                    <td><?php echo $projectCode; ?></td>
                                                    <td><?php echo $soNumber; ?></td>
                                                    <td><?php echo $customerName; ?></td>
                                                    <td><?php echo $projectName; ?></td>
                                                    <td><?php echo $rawResourceEmail; ?></td>
                                                    <td><?php echo $start_date . " - " . $end_date; ?></td>
                                                    <td><?php echo $total_preventive; ?></td>
                                                    <td><?php echo $total_maintenance_report; ?></td>
                                                    <td><?php echo $total_ticket_allocation; ?></td>
                                                    <td><?php echo $total_pmr_date; ?></td>
                                                    <td><?php echo $approvalTo; ?></td>
                                                    <td><?php echo $approvalStatus; ?></td>
                                                </tr>

                                                <!-- MODAL -->
                                                <div class="modal fade" id="modal-<?php echo $assignmentId; ?>" tabindex="-1" role="dialog"
                                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document" style='max-width: 50% !important;'>
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Resource Information Detail
                                                                </h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <?php
                                                                $sqlCheckResourceAssignment = "SELECT * FROM sa_resource_assignment WHERE project_type = 'Maintenance' and resource_email LIKE '%$resourceEmail%' AND approval_status = 'approved'";
                                                                $dataResourceAssignment = $DTSB->get_sql($sqlCheckResourceAssignment);
                                                                $rowRA = $dataResourceAssignment[0];
                                                                $resRA = $dataResourceAssignment[1];
                                                                $totalRowRA = $dataResourceAssignment[2];

                                                                if ($totalRowRA > 0) {
                                                                ?>
                                                                    <h4>Resource ini di assign di :</h4>
                                                                <?php
                                                                    $number = 1;
                                                                    do {
                                                                        $projectCodeRA = $rowRA['project_code'];
                                                                        $noSORA = $rowRA['no_so'];
                                                                        $projectTypeRA = $rowRA['project_type'];
                                                                        $customerNameRA = $rowRA['customer_name'];
                                                                        $rolesRA = $rowRA['roles'];
                                                                        $rolesFinalRA = str_replace(" - ", " ", $rolesRA);
                                                                        $statusRA = $rowRA['status'];
                                                                        $createdInRA = $rowRA['created_in_msizone'];

                                                                        echo "
                                                                $number. $projectCodeRA   -   $noSORA  -  $projectTypeRA  -  $customerNameRA  -  $rolesFinalRA  -  $statusRA  -  $createdInRA  <br/> 
                                                            ";
                                                                        $number++;
                                                                    } while ($rowRA = $resRA->fetch_assoc());
                                                                }
                                                                ?>

                                                                <h4 class="mt-3">Project yang sedang dikerjakan : <?php echo $totalRowRA; ?>
                                                                </h4>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                            } while ($rowData = $resData->fetch_assoc());
                                        } else {
                                            ?>
                                            <td colspan="12">
                                                <center>Belum ada approval</center>
                                            </td>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th><input type="checkbox" class="check_all" style="width: 20px; height: 20px;" />
                                                Checklist All</th>
                                            <th>Project Code</th>
                                            <th>SO Number</th>
                                            <th>Customer Name</th>
                                            <th>Project Name</th>
                                            <th>Requester</th>
                                            <th>Periode Maintenance</th>
                                            <th>Total Preventive Maintenance</th>
                                            <th>Total Maintenance Report</th>
                                            <th>Total Ticket Allocation</th>
                                            <th>Total Preventive & Maintenance Report</th>
                                            <th>Approval To</th>
                                            <th>Approval Status</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <hr />
                                <input type="hidden" name="userSession" value="<?php echo $userSession; ?>">
                                <button type="submit" name="btn_approve_report" id="btn_approve" class="btn btn-primary mt-1"
                                    onclick="javascript: return confirm('Apakah Anda yakin ingin approve data ini?')">Approve</button>
                                <button type="submit" name="btn_reject_report" id="btn_danger" class="btn btn-danger mt-1"
                                    onclick="javascript: return confirm('Apakah Anda yakin ingin reject data ini?')">Reject</button>
                            </form>
                        </div>

                    <?php
                    } else if (USERPERMISSION == "0162bce636a63c3ae499224203e06ed0") {
                    ?>
                        <div class="col-12 mt-3">
                            <hr />
                            <form method="POST" action="index.php?mod=<?php echo $_GET['mod']; ?>" enctype="multipart/form-data">
                                <table id="example" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Checklist</th>
                                            <th>Project Code</th>
                                            <th>SO Number</th>
                                            <th>Customer Name</th>
                                            <th>Project Name</th>
                                            <th>Requester</th>
                                            <th>Periode Maintenance</th>
                                            <th>Total Preventive Maintenance</th>
                                            <th>Total Maintenance Report</th>
                                            <th>Total Ticket Allocation</th>
                                            <th>Total Preventive & Maintenance Report</th>
                                            <th>Approval To</th>
                                            <th>Approval Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($_SESSION['Microservices_UserEmail'] == "anita.angelina@mastersystem.co.id" || $_SESSION['Microservices_UserEmail'] == "lucky.andiani@mastersystem.co.id") {
                                            $sqlViewData = "SELECT * from sa_master_maintenance_date WHERE approval_status = 'pending'";
                                        } else {
                                            $sqlViewData = "SELECT * from sa_master_maintenance_date WHERE approval_to LIKE '%" . $_SESSION['Microservices_UserEmail'] . "%' AND approval_status = 'pending'";
                                        }
                                        $getData = $DBKPI->get_sql($sqlViewData);
                                        $rowData = $getData[0];
                                        $resData = $getData[1];
                                        $totalRowData = $getData[2];

                                        if ($totalRowData > 0) {
                                            do {
                                                $assignmentId = $rowData['id'];
                                                $projectCode = $rowData['project_code'];
                                                $order_number = $rowData['order_number'];
                                                $rawResourceEmail = $rowData['entry_by'];
                                                $rawResourceEmail2 = explode("<", $rawResourceEmail);
                                                $resourceEmail = str_replace(">", "", $rawResourceEmail2[1]);
                                                $approvalTo = $rowData['approval_to'];
                                                $approvalStatus = $rowData['approval_status'];
                                                $total_preventive = $rowData['total_preventive_mt_date'];
                                                $total_maintenance_report = $rowData['total_mt_report_date'];
                                                $total_ticket_allocation = $rowData['total_ticket_allocation'];
                                                $total_pmr_date = $rowData['total_pmr_date'];
                                                $get_information_project = $DTSB->get_sqlV2("SELECT * FROM sa_resource_assignment WHERE project_code='$projectCode' AND order_number='$order_number' AND project_type='Maintenance' LIMIT 1");
                                                $soNumber = $get_information_project[0]['no_so'];
                                                $customerName = $get_information_project[0]['customer_name'];
                                                $projectName = $get_information_project[0]['project_name'];
                                                $get_data_periode_start = $DBKPI->get_sqlV2("SELECT * FROM sa_maintenance_date WHERE project_code='$projectCode' AND order_number='$order_number' AND id_date='mt_date_start'");
                                                $start_date = $get_data_periode_start[0]['date'];
                                                $get_data_periode_end = $DBKPI->get_sqlV2("SELECT * FROM sa_maintenance_date WHERE project_code='$projectCode' AND order_number='$order_number' AND id_date='mt_date_end'");
                                                $end_date = $get_data_periode_end[0]['date'];

                                                $userSession = $_SESSION['Microservices_UserName'] . "<" . $_SESSION['Microservices_UserEmail'] . ">";
                                        ?>
                                                <tr>
                                                    <td><input type="checkbox" name="id[]" class="ml-auto form-check-input chk_boxes1"
                                                            value="<?php echo $assignmentId ?>" style="width: 20px; height: 20px;"></td>
                                                    <td><?php echo $projectCode; ?></td>
                                                    <td><?php echo $soNumber; ?></td>
                                                    <td><?php echo $customerName; ?></td>
                                                    <td><?php echo $projectName; ?></td>
                                                    <td><?php echo $rawResourceEmail; ?></td>
                                                    <td><?php echo $start_date . " - " . $end_date; ?></td>
                                                    <td><?php echo $total_preventive; ?></td>
                                                    <td><?php echo $total_maintenance_report; ?></td>
                                                    <td><?php echo $total_ticket_allocation; ?></td>
                                                    <td><?php echo $total_pmr_date; ?></td>
                                                    <td><?php echo $approvalTo; ?></td>
                                                    <td><?php echo $approvalStatus; ?></td>
                                                </tr>

                                                <!-- MODAL -->
                                                <div class="modal fade" id="modal-<?php echo $assignmentId; ?>" tabindex="-1" role="dialog"
                                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document" style='max-width: 50% !important;'>
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Resource Information Detail
                                                                </h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <?php
                                                                $sqlCheckResourceAssignment = "SELECT * FROM sa_resource_assignment WHERE project_type='Maintenance' AND resource_email LIKE '%$resourceEmail%' AND approval_status = 'approved'";
                                                                $dataResourceAssignment = $DTSB->get_sql($sqlCheckResourceAssignment);
                                                                $rowRA = $dataResourceAssignment[0];
                                                                $resRA = $dataResourceAssignment[1];
                                                                $totalRowRA = $dataResourceAssignment[2];

                                                                if ($totalRowRA > 0) {
                                                                ?>
                                                                    <h4>Resource ini di assign di :</h4>
                                                                <?php
                                                                    $number = 1;
                                                                    do {
                                                                        $projectCodeRA = $rowRA['project_code'];
                                                                        $noSORA = $rowRA['no_so'];
                                                                        $projectTypeRA = $rowRA['project_type'];
                                                                        $customerNameRA = $rowRA['customer_name'];
                                                                        $rolesRA = $rowRA['roles'];
                                                                        $rolesFinalRA = str_replace(" - ", " ", $rolesRA);
                                                                        $statusRA = $rowRA['status'];
                                                                        $createdInRA = $rowRA['created_in_msizone'];

                                                                        echo "
                                                                $number. $projectCodeRA   -   $noSORA  -  $projectTypeRA  -  $customerNameRA  -  $rolesFinalRA  -  $statusRA  -  $createdInRA  <br/> 
                                                            ";
                                                                        $number++;
                                                                    } while ($rowRA = $resRA->fetch_assoc());
                                                                }
                                                                ?>

                                                                <h4 class="mt-3">Project yang sedang dikerjakan : <?php echo $totalRowRA; ?>
                                                                </h4>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                            } while ($rowData = $resData->fetch_assoc());
                                        } else {
                                            ?>
                                            <td colspan="12">
                                                <center>Belum ada approval</center>
                                            </td>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th><input type="checkbox" class="check_all" style="width: 20px; height: 20px;" />
                                                Checklist All</th>
                                            <th>Project Code</th>
                                            <th>SO Number</th>
                                            <th>Customer Name</th>
                                            <th>Project Name</th>
                                            <th>Requester</th>
                                            <th>Periode Maintenance</th>
                                            <th>Total Preventive Maintenance</th>
                                            <th>Total Maintenance Report</th>
                                            <th>Total Ticket Allocation</th>
                                            <th>Total Preventive & Maintenance Report</th>
                                            <th>Approval To</th>
                                            <th>Approval Status</th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <hr />
                                <input type="hidden" name="userSession" value="<?php echo $userSession; ?>">
                                <button type="submit" name="btn_approve_report" id="btn_approve" class="btn btn-primary mt-1"
                                    onclick="javascript: return confirm('Apakah Anda yakin ingin approve data ini?')">Approve</button>
                                <button type="submit" name="btn_reject_report" id="btn_danger" class="btn btn-danger mt-1"
                                    onclick="javascript: return confirm('Apakah Anda yakin ingin reject data ini?')">Reject</button>
                            </form>
                        </div>
                    <?php
                    } else {
                        // $condition = "approval_to LIKE '%".$_SESSION['Microservices_UserEmail']."%' AND approval_status = 'pending'";
                        // view_data_approval($tblname, $condition);
                        $condition = "approval_status = 'pending' AND entry_by LIKE '%" . $_SESSION['Microservices_UserEmail'] . "%'";
                        view_data_approval_report($tbl_report, $condition);
                    }
                } elseif (!isset($_GET['act']) && $_GET['status'] == 'approved_assignment') {
                    if (USERPERMISSION == "dbf36ff3e3827639223983ee8ac47b42" || USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION == "0162bce636a63c3ae499224203e06ed0") {
                        $condition = "project_type='Maintenance' AND approval_status = 'approved'";
                        view_data_approval($tblname, $condition);
                    } else {
                        $condition = "project_type = 'Maintenance' and created_by LIKE '%" . $_SESSION['Microservices_UserEmail'] . "%' AND approval_status = 'approved'";
                        view_data_approval($tblname, $condition);
                    }
                } elseif (!isset($_GET['act']) && $_GET['status'] == 'approved_baseline_report') {
                    if (USERPERMISSION == "dbf36ff3e3827639223983ee8ac47b42" || USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION == "0162bce636a63c3ae499224203e06ed0" || $_SESSION['Microservices_UserEmail'] == "anita.angelina@mastersystem.co.id" || $_SESSION['Microservices_UserEmail'] == "lucky.andiani@mastersystem.co.id") {
                        $condition = "approval_status = 'approved'";
                        view_data_approval_report($tbl_report, $condition);
                    } else {
                        $condition = "entry_by LIKE '%" . $_SESSION['Microservices_UserEmail'] . "%' AND approval_status = 'approved'";
                        view_data_approval_report($tbl_report, $condition);
                    }
                } elseif (!isset($_GET['act']) && $_GET['status'] == 'rejected_assignment') {
                    if (USERPERMISSION == "dbf36ff3e3827639223983ee8ac47b42" || USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b") {
                        $condition = "project_type='Maintenance' and approval_status = 'rejected'";
                        view_data_approval($tblname, $condition);
                    } else if (USERPERMISSION == "0162bce636a63c3ae499224203e06ed0") {
                        $condition = "project_type='Maintenance' and approval_status = 'rejected' AND (approval_to LIKE '%" . $_SESSION['Microservices_UserEmail'] . "%' OR created_by LIKE '%" . $_SESSION['Microservices_UserEmail'] . "%')";
                        view_data_approval($tblname, $condition);
                    } else {
                        $condition = "project_type='Maintenance' AND created_by LIKE '%" . $_SESSION['Microservices_UserEmail'] . "%' AND approval_status = 'rejected'";
                        view_data_approval($tblname, $condition);
                    }
                } elseif (!isset($_GET['act']) && $_GET['status'] == 'temporary_assignment') {
                    if (USERPERMISSION == "dbf36ff3e3827639223983ee8ac47b42" || USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b") {
                    ?>
                        <div class="col-12 mt-3">
                            <hr />
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Project Code</th>
                                        <th>SO Number</th>
                                        <th>Project Type</th>
                                        <th>Customer Name</th>
                                        <th>Project Name</th>
                                        <th>Resource Email</th>
                                        <th>Roles</th>
                                        <th>Status & Progress</th>
                                        <th>Created By</th>
                                        <th>Created In</th>
                                        <th>Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sqlViewDataTemp = "SELECT * from sa_temporary_resource where project_type = 'Maintenance'";
                                    $getDataTemp = $DTSB->get_sql($sqlViewDataTemp);
                                    $rowDataTemp = $getDataTemp[0];
                                    $resDataTemp = $getDataTemp[1];
                                    $totalRowDataTemp = $getDataTemp[2];

                                    if ($totalRowDataTemp > 0) {
                                        do {
                                            $assignmentId = $rowDataTemp['id'];
                                            $projectCode = $rowDataTemp['project_code'];
                                            $soNumber = $rowDataTemp['no_so'];
                                            $projectType = $rowDataTemp['project_type'];
                                            $customerName = $rowDataTemp['customer_name'];
                                            $projectName = $rowDataTemp['project_name'];
                                            $rawResourceEmail = $rowDataTemp['resource_email'];
                                            $rawResourceEmail2 = explode("<", $rawResourceEmail);
                                            $resourceEmail = str_replace(">", "", $rawResourceEmail2[1]);
                                            $rawRoles = $rowDataTemp['roles'];
                                            $roles = str_replace(" - ", " ", "$rawRoles");
                                            $status = $rowDataTemp['status'];
                                            $startProgress = $rowDataTemp['start_progress'];
                                            $endProgress = $rowDataTemp['end_progress'];
                                            $finalStatus = "$status $startProgress% - $endProgress%";
                                            $createdBy = $rowDataTemp['created_by'];
                                            $createdIn = $rowDataTemp['created_in_msizone'];

                                            $userSession = $_SESSION['Microservices_UserName'] . "<" . $_SESSION['Microservices_UserEmail'] . ">";
                                    ?>
                                            <tr>
                                                <td><?php echo $projectCode; ?></td>
                                                <td><?php echo $soNumber; ?></td>
                                                <td><?php echo $projectType; ?></td>
                                                <td><?php echo $customerName; ?></td>
                                                <td><?php echo $projectName; ?></td>
                                                <td><?php echo $resourceEmail; ?></td>
                                                <td><?php echo $roles; ?></td>
                                                <td><?php echo $finalStatus; ?></td>
                                                <td><?php echo $createdBy; ?></td>
                                                <td><?php echo $createdIn; ?></td>
                                                <td><a href="http://localhost/msizone/index.php?mod=resource_assignment_mt&act=add&project_code=<?php echo $projectCode; ?>&no_so=<?php echo $soNumber; ?>&project_type=<?php echo $projectType ?>"
                                                        class="btn btn-warning">Details</a></td>
                                            </tr>
                                        <?php
                                        } while ($rowDataTemp = $resDataTemp->fetch_assoc());
                                    } else {
                                        ?>
                                        <td colspan="11">
                                            <center>Belum ada temporary resource</center>
                                        </td>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Project Code</th>
                                        <th>SO Number</th>
                                        <th>Project Type</th>
                                        <th>Customer Name</th>
                                        <th>Project Name</th>
                                        <th>Resource Email</th>
                                        <th>Roles</th>
                                        <th>Status & Progress</th>
                                        <th>Created By</th>
                                        <th>Created In</th>
                                        <th>Detail</th>
                                    </tr>
                                </tfoot>
                            </table>
                            <hr />
                        </div>
                    <?php } else {
                    ?>
                        <div class="col-12 mt-3">
                            <hr />
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Project Code</th>
                                        <th>SO Number</th>
                                        <th>Project Type</th>
                                        <th>Customer Name</th>
                                        <th>Project Name</th>
                                        <th>Resource Email</th>
                                        <th>Roles</th>
                                        <th>Status & Progress</th>
                                        <th>Created By</th>
                                        <th>Created In</th>
                                        <th>Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sqlViewDataTemp = "SELECT * from sa_temporary_resource WHERE project_type = 'Maintenance' and created_by LIKE '%" . $_SESSION['Microservices_UserEmail'] . "%'";
                                    $getDataTemp = $DTSB->get_sql($sqlViewDataTemp);
                                    $rowDataTemp = $getDataTemp[0];
                                    $resDataTemp = $getDataTemp[1];
                                    $totalRowDataTemp = $getDataTemp[2];

                                    if ($totalRowDataTemp > 0) {
                                        do {
                                            $assignmentId = $rowDataTemp['id'];
                                            $projectCode = $rowDataTemp['project_code'];
                                            $soNumber = $rowDataTemp['no_so'];
                                            $projectType = $rowDataTemp['project_type'];
                                            $customerName = $rowDataTemp['customer_name'];
                                            $projectName = $rowDataTemp['project_name'];
                                            $rawResourceEmail = $rowDataTemp['resource_email'];
                                            $rawResourceEmail2 = explode("<", $rawResourceEmail);
                                            $resourceEmail = str_replace(">", "", $rawResourceEmail2[1]);
                                            $rawRoles = $rowDataTemp['roles'];
                                            $roles = str_replace(" - ", " ", "$rawRoles");
                                            $status = $rowDataTemp['status'];
                                            $startProgress = $rowDataTemp['start_progress'];
                                            $endProgress = $rowDataTemp['end_progress'];
                                            $finalStatus = "$status $startProgress% - $endProgress%";
                                            $createdBy = $rowDataTemp['created_by'];
                                            $createdIn = $rowDataTemp['created_in_msizone'];

                                            $userSession = $_SESSION['Microservices_UserName'] . "<" . $_SESSION['Microservices_UserEmail'] . ">";
                                    ?>
                                            <tr>
                                                <td><?php echo $projectCode; ?></td>
                                                <td><?php echo $soNumber; ?></td>
                                                <td><?php echo $projectType; ?></td>
                                                <td><?php echo $customerName; ?></td>
                                                <td><?php echo $projectName; ?></td>
                                                <td><?php echo $resourceEmail; ?></td>
                                                <td><?php echo $roles; ?></td>
                                                <td><?php echo $finalStatus; ?></td>
                                                <td><?php echo $createdBy; ?></td>
                                                <td><?php echo $createdIn; ?></td>
                                                <td><a href="http://localhost/msizone/index.php?mod=resource_assignment_mt&act=add&project_code=<?php echo $projectCode; ?>&no_so=<?php echo $soNumber; ?>&project_type=<?php echo $projectType ?>"
                                                        class="btn btn-warning">Details</a></td>
                                            </tr>
                                        <?php
                                        } while ($rowDataTemp = $resDataTemp->fetch_assoc());
                                    } else {
                                        ?>
                                        <td colspan="11">
                                            <center>Belum ada temporary resource</center>
                                        </td>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Project Code</th>
                                        <th>SO Number</th>
                                        <th>Project Type</th>
                                        <th>Customer Name</th>
                                        <th>Project Name</th>
                                        <th>Resource Email</th>
                                        <th>Roles</th>
                                        <th>Status & Progress</th>
                                        <th>Created By</th>
                                        <th>Created In</th>
                                        <th>Detail</th>
                                    </tr>
                                </tfoot>
                            </table>
                            <hr />
                        </div>
                <?php }
                } elseif (!isset($_GET['act']) && $_GET['status'] == 'report_project_charter_mt') {
                    $condition = "";
                    view_data_approval($tblname, $condition, 5000);
                } elseif ($_GET['act'] == 'add') {
                    form_data($tblname);
                } elseif ($_GET['act'] == 'new') {
                    new_projects($tblname);
                } elseif ($_GET['act'] == 'edit') {
                    form_data($tblname);
                } elseif ($_GET['act'] == 'del') {
                    echo 'Delete Data';
                } elseif ($_GET['act'] == 'save') {
                    form_data($tblname);
                } elseif ($_GET['act'] == 'testing') {
                    form_data($tblname);
                }

                ?>
            </div>
        </div>
    </div>

<?php
    // } else {
    //     echo "Aplikasi belum disetup";
    // }
    // }else { 
    //   $ALERT->notpermission();
    // } 
    // End Body
    // }else{
    //     $ALERT->notpermission();
    // } 
}
?>

<script>
    $(document).on('change', '#assignment_status', function() {
        var sta = $('#assignment_status').val();
        if (sta == "my_assignment") {
            window.location = window.location.pathname + "?mod=resource_assignment_mt";
        } else if (sta == "report_project_charter_mt") {
            window.location = window.location.pathname +
                "?mod=report_project_charter_mt&status=report_project_charter_mt&project_type=Maintenance&approval_status=";
        } else {
            window.location = window.location.pathname + "?mod=resource_assignment_mt&status=" + sta;
        }
    });

    <?php
    if (isset($_GET['status'])) { ?>
        $('#assignment_status option[value=<?php echo $_GET['status']; ?>]').attr('selected', 'selected');
    <?php
    }
    ?>

    $(document).ready(function() {
        // $('#example').DataTable({
        //     scrollX: true,
        //     buttons: [{
        //         extend: 'excelHtml5',
        //         text: "<i class='fa fa-file-pdf'></i>",
        //         title: 'Pending_assignment' + <?php echo date("YmdGis"); ?>
        //     }, ],
        var table = $('#example').DataTable({
            dom: 'Blfrtip',
            scrollX: true,
            //select: {
            //  style: 'single'
            //},
            buttons: [{
                extend: 'excelHtml5',
                text: "<i class='fa fa-file-pdf'></i>",
                title: 'Pending_assignment_' + <?php echo date("YmdGis"); ?>
            }, ],
        });
    });
</script>

<script type="text/javascript">
    $(function() {
        $('.check_all').click(function() {
            $('.chk_boxes1').prop('checked', this.checked);
        });
    });
</script>