<?php
if ((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $released = "1675673999";
    $author = 'Syamsul Arham';
} else {
    function view_table_kpi($DTBL, $tblname, $primarykey = "", $condition = "", $order = "", $firstRow = 0, $totalRow = 500, $index = "")
    {
        $datatable = $DTBL->get_data($tblname, $condition, $order, $firstRow, $totalRow);
        $ddatatable = $datatable[0];
        $qdatatable = $datatable[1];
        $tdatatable = $datatable[2];
        $modtitle = 'Catalog listing';

        $datatable_header = $DTBL->get_columns($tblname);
        $ddatatable_header = $datatable_header[0];
        $qdatatable_header = $datatable_header[1];
        $tdatatable_header = $datatable_header[2];
        $datatable_header2 = $DTBL->get_columns($tblname);
        $ddatatable_header2 = $datatable_header2[0];
        $qdatatable_header2 = $datatable_header2[1];
        $tdatatable_header2 = $datatable_header2[2];
?>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered hover stripe display compact dataTableMulti" id="<?php echo $tblname . $index; ?>" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <?php
                            $header = '';
                            do {
                                // $header .="<th>" . strtoupper($ddatatable_header['Field']) . "</th>";
                                $headerx = str_replace("_", " ", strtoupper($ddatatable_header['Field']));
                                $header .= "<th class='text-center align-middle'>" . $headerx . "</th>";
                            } while ($ddatatable_header = $qdatatable_header->fetch_assoc());
                            echo $header;
                            ?>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <?php echo $header; ?>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                        if ($tdatatable > 0) {
                            do { ?>
                                <tr>
                                    <?php
                                    $datatable_header2 = $DTBL->get_columns($tblname);
                                    $ddatatable_header2 = $datatable_header2[0];
                                    $qdatatable_header2 = $datatable_header2[1];
                                    ?>
                                    <?php do { ?>
                                        <td><?php echo $ddatatable[$ddatatable_header2['Field']]; ?></td>
                                    <?php } while ($ddatatable_header2 = $qdatatable_header2->fetch_assoc()); ?>
                                </tr>
                        <?php
                            } while ($ddatatable = $qdatatable->fetch_assoc());
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php
    }

    $mdlname = "KPI_BOARD";
    $userpermission = useraccess_v2($mdlname);
    $modulename = "KPI Board";
    $userpermission = useraccess($modulename);
    if (USERPERMISSION_V2 == "8660be9ee75ee946d592d90f89a2ea6f") {
    ?>
        <?php if (USERPERMISSION_V2 == "0162bce636a63c3ae499224203e06ed0" || $_SESSION['Microservices_UserEmail'] == "fortuna@mastersystem.co.id" || $_SESSION['Microservices_UserEmail'] == "iwan@mastersystem.co.id" || $_SESSION['Microservices_UserEmail'] == "cassendra@mastersystem.co.id" || $_SESSION['Microservices_UserEmail'] == "aditya.wisnu@mastersystem.co.id" || $_SESSION['Microservices_UserEmail'] == "ardi.haris@mastersystem.co.id" || $_SESSION['Microservices_UserEmail'] == "Herdiman@mastersystem.co.id") { ?>
            <script>
                $(document).ready(function() {
                    var table = $('#kpi_board').DataTable({
                        dom: 'Blfrtip',
                        select: {
                            style: 'single'
                        },
                        buttons: [{
                            text: "<i class='fa fa-pen'></i>",
                            action: function() {
                                var rownumber = table.rows({
                                    selected: true
                                }).indexes();
                                var id = table.cell(rownumber, 0).data();
                                var project_code = table.cell(rownumber, 3).data();
                                var so_number = table.cell(rownumber, 2).data();
                                var status = table.cell(rownumber, 41).data();
                                if (id == null) {
                                    alert("Please select data");
                                } else if (status == 'Ready for Approve' && id != null) {
                                    window.location.href = "index.php?mod=kpi_board&act=review&id=" + id + "&so_number=" + so_number;
                                } else {
                                    alert("Can't Review this Project");
                                }
                            },
                            //enabled: false
                        }],
                        "columnDefs": [{
                            "targets": [0, 1, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 43, 42],
                            "visible": false,
                        }],
                    });
                });
            </script>
        <?php } else { ?>
            <script>
                $(document).ready(function() {
                    var table = $('#kpi_so_wr').DataTable({
                        dom: 'Blfrtip',
                        select: {
                            style: 'single'
                        },
                        buttons: [{
                                extend: 'excelHtml5',
                                text: "<i class='fa fa-file-pdf'></i>",
                                title: 'KPI_Panel_' + <?php echo date("YmdGis"); ?>
                            },
                            {
                                text: "<i class='fas fa-edit'></i>",
                                action: function() {
                                    var rownumber = table.rows({
                                        selected: true
                                    }).indexes();
                                    var id = table.cell(rownumber, 0).data();
                                    var project_code = table.cell(rownumber, 1).data();
                                    var so_number = table.cell(rownumber, 2).data();
                                    var board_status = table.cell(rownumber, 25).data();
                                    if (id == null) {
                                        alert("Silahkan pilih data");
                                    } else if (board_status == 'Reviewed') {
                                        window.location.href = "index.php?mod=kpi_board&act=read_add&id=" + id + "&so_number=" + so_number + "&project_code=" + project_code;
                                    } else {
                                        window.location.href = "index.php?mod=kpi_board&act=add&id=" + id + "&so_number=" + so_number + "&project_code=" + project_code;
                                    }
                                }
                            },
                            {
                                text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Error Category'><i class='fa-solid fa-pen-to-square'></i></span>",
                                action: function() {
                                    var rownumber = table.rows({
                                        selected: true
                                    }).indexes();
                                    var id = table.cell(rownumber, 0).data();
                                    var so_number = table.cell(rownumber, 2).data();
                                    var board_status = table.cell(rownumber, 24).data();
                                    if (id == null) {
                                        alert("Silahkan pilih data");
                                    } else if (board_status == 'Reviewed') {
                                        window.location.href = "index.php?mod=kpi_board&act=read_edit&id=" + id + "&so_number=" + so_number;
                                    } else {
                                        window.location.href = "index.php?mod=kpi_board&act=edit&id=" + id + "&so_number=" + so_number;
                                    }
                                },
                                //enabled: false
                            }
                        ],
                        "columnDefs": [{
                            "targets": [0, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19],
                            "visible": false,
                        }],
                    });
                });
            </script>
            <script>
                $(document).ready(function() {
                    var table = $('#table_report').DataTable({
                        dom: 'Blfrtip',
                        select: {
                            style: 'single'
                        },
                        buttons: [{
                            extend: 'excelHtml5',
                            text: "<i class='fa fa-file-pdf'></i>",
                            title: 'KPI_Panel_' + <?php echo date("YmdGis"); ?>
                        }],
                        "columnDefs": [{
                            "targets": [0, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19],
                            "visible": false,
                        }],
                    });
                });
            </script>
            <script>
                $(document).ready(function() {
                    var table = $('#kpi_board').DataTable({
                        dom: 'Blfrtip',
                        select: {
                            style: 'single'
                        },
                        buttons: [{
                            text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Error Category'><i class='fa-solid fa-pen-to-square'></i></span>",
                            action: function() {
                                var rownumber = table.rows({
                                    selected: true
                                }).indexes();
                                var id = table.cell(rownumber, 0).data();
                                var so_number = table.cell(rownumber, 2).data();
                                var board_status = table.cell(rownumber, 24).data();
                                if (id == null) {
                                    alert("Silahkan pilih data");
                                } else if (board_status == 'Reviewed') {
                                    window.location.href = "index.php?mod=kpi_board&act=read_edit&id=" + id + "&so_number=" + so_number;
                                } else {
                                    window.location.href = "index.php?mod=kpi_board&act=edit&id=" + id + "&so_number=" + so_number;
                                }
                            },
                            //enabled: false
                        }],
                        "columnDefs": [{
                            "targets": [0, 1, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 43, 42],
                            "visible": false,
                        }],
                        "order": [
                            [41, "asc"]
                        ],
                    });
                });
            </script>
        <?php } ?>
        <?php

        // Function
        // if ($_SESSION['Microservices_UserLevel'] == "Administrator") {
        function view_data($tblname, $condition)
        {
            // Definisikan tabel yang akan ditampilkan dalam DataTable
            global $DBKPISO;
            $primarykey = "id";
            $order = "";
            if (isset($_GET['err']) && $_GET['err'] == "datanotfound") {
                global $ALERT;
                $ALERT->datanotfound();
            }
            view_table_kpi($DBKPISO, $tblname, $primarykey, $condition, $order);
        }
        function form_data($tblname)
        {
            include("components/modules/kpi_board/form_kpi_board.php");
        }

        // End Function

        $tblname = 'cfg_web';
        $condition = 'config_key="MODULE_KPI_BOARD"';
        $setupDB = $DB->get_data($tblname, $condition);
        $dsetupDB = $setupDB[0];
        if ($setupDB[2] > 0) {
            $params = get_params($dsetupDB['params']);
            $hostname = $params['database']['hostname'];
            $username = $params['database']['username'];
            $userpassword = $params['database']['userpassword'];
            $database = $params['database']['database_name'];

            // // $database = 'sa_dashboard_kpi';
            // include("components/modules/kpi_project/connection.php");
            $DBKPISO = new Databases($hostname, $username, $userpassword, $database);
            $tblname = 'kpi_so_wr';
            $tblname2 = 'kpi_board';
            $tblname3 = 'data_so';
            $tblname4 = 'user';
            $tblname5 = 'data_kp';
            $tblname6 = 'kpi_project_wr';
            $tblname7 = 'log_board';
            $tblname8 = 'error';
            $tblname9 = 'general_informations';
            $tblname_report = 'table_report';

            include("components/modules/kpi_board/func_kpi_board.php");

            $user_mail = $_SESSION['Microservices_UserEmail'];
            // Body
        ?>
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">KPI Board Project</h6>
                    </div>
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a href=""></a>
                        </li>
                    </ul>
                    <div class="card-body">
                        <?php if (!isset($_GET['act'])) { ?>
                            <select name="" id="status_board">
                                <?php if (USERPERMISSION == "0162bce636a63c3ae499224203e06ed0" || $_SESSION['Microservices_UserEmail'] == "fortuna@mastersystem.co.id"  || $_SESSION['Microservices_UserEmail'] == "iwan@mastersystem.co.id"  || $_SESSION['Microservices_UserEmail'] == "cassendra@mastersystem.co.id") { ?>
                                    <option value="pending_review">Pending Review Project</option>
                                    <option value="approved_review">Approved Project</option>
                                    <option value="cancel">Cancel/Reject Project</option>
                                <?php } else { ?>
                                    <option value="all_data">All Data</option>
                                    <option value="pending_review">Pending Review Project</option>
                                    <option value="approved_review">Approved Project</option>
                                    <option value="cancel">Cancel/Reject Project</option>
                                    <option value="report">Report Excel</option>
                                <?php } ?>
                            </select>
                        <?php } ?>
                        <?php
                        if (USERPERMISSION == "0162bce636a63c3ae499224203e06ed0" && !isset($_GET['act']) && !isset($_GET['status_board'])) {
                            $condition = "status='Ready for Approve' AND pic_email LIKE '%$user_mail%' AND status_approval LIKE '%Pending%'";
                            view_data($tblname2, $condition);
                        } else if (USERPERMISSION == "0162bce636a63c3ae499224203e06ed0" && !isset($_GET['act']) && $_GET['status_board'] == 'pending_review') {
                            $condition = "status='Ready for Approve' AND pic_email LIKE '%$user_mail%' AND status_approval LIKE '%Pending%'";
                            view_data($tblname2, $condition);
                        } elseif (USERPERMISSION == "0162bce636a63c3ae499224203e06ed0" && !isset($_GET['act']) && $_GET['status_board'] == 'approved_review') {
                            $condition = "status='Reviewed' AND pic_email LIKE '%$user_mail%' OR status_approval NOT LIKE '%Pending%' AND pic_email LIKE '%$user_mail%'";
                            view_data($tblname2, $condition);
                        } elseif (!isset($_GET['act'])  && !isset($_GET['status_board']) || !isset($_GET['act']) && $_GET['status_board'] == 'all_data') {
                            $condition = "status_wr LIKE '%Closed%' AND kpi_status !='Reviewed'";
                            view_data($tblname, $condition);
                        } elseif (!isset($_GET['act']) && $_GET['status_board'] == 'all_data') {
                            $condition = "status_wr LIKE '%Closed%' AND kpi_status !='Reviewed'";
                            view_data($tblname, $condition);
                        } elseif (!isset($_GET['act']) && $_GET['status_board'] == 'pending_review') {
                            $condition = "status_approval LIKE '%Pending%' AND status_approval NOT LIKE '%Reject%' AND status NOT LIKE '%Cancel%'";
                            view_data($tblname2, $condition);
                        } elseif (!isset($_GET['act']) && $_GET['status_board'] == 'pending_error') {
                            $condition = "status_error='Pending' OR status_error='Draft'";
                            view_data($tblname2, $condition);
                        } elseif (!isset($_GET['act']) && $_GET['status_board'] == 'approved_review') {
                            $condition = "status='Reviewed' AND status_approval NOT LIKE '%Pending%'";
                            view_data($tblname2, $condition);
                        } elseif (!isset($_GET['act']) && $_GET['status_board'] == 'approved_error') {
                            $condition = "status_error='Approved' AND status_approval NOT LIKE '%Pending%'";
                            view_data($tblname2, $condition);
                        } elseif (!isset($_GET['act']) && $_GET['status_board'] == 'cancel') {
                            $condition = "status='Canceled' OR status_approval LIKE '%Reject%'";
                            view_data($tblname2, $condition);
                        } elseif (!isset($_GET['act']) && $_GET['status_board'] == 'report') {
                            $condition = "status_wr LIKE '%Closed%' AND kpi_status !='Reviewed'";
                            view_data($tblname_report, $condition);
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
                        } elseif ($_GET['act'] == 'review') {
                            form_data($tblname);
                        } elseif ($_GET['act'] == 'test') {
                            form_data($tblname);
                        } elseif ($_GET['act'] == 'error') {
                            form_data($tblname);
                        } elseif ($_GET['act'] == 'read_edit') {
                            form_data($tblname);
                        } elseif ($_GET['act'] == 'read_add') {
                            form_data($tblname);
                        }
                        // }
                        ?>
                    </div>
                </div>
            </div>
<?php

        } else {
            $ALERT->notpermission();
        }
        // End Body
    }
}
?>
<script>
    $(document).on('change', '#status_board', function() {
        var sta = $('#status_board').val();
        if (sta == "all_data") {
            window.location = window.location.pathname + "?mod=kpi_board";
        } else {
            window.location = window.location.pathname + "?mod=kpi_board&status_board=" + sta;
        }
    });
    <?php if (isset($_GET['status_board'])) { ?>
        $('#status_board option[value=<?php echo $_GET['status_board']; ?>]').attr('selected', 'selected');
    <?php } ?>
</script>