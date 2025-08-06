<?php
if ((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $released = "1711615181";
    $author = 'Syamsul Arham';
} else {

    $modulename = "Google Calendar";
    $userpermission = useraccess($modulename);
    function view_table_kpi($DTBL, $tblname, $primarykey = "", $condition = "", $order = "", $firstRow = 0, $totalRow = 10000, $index = "")
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

    if (USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION == "dbf36ff3e3827639223983ee8ac47b42") {


    ?>



        <?php

        // Function
        // if ($_SESSION['Microservices_UserLevel'] == "Super Admin") {
        function view_data($tblname)
        {
            // Definisikan tabel yang akan ditampilkan dalam DataTable
            global $DBKPINEW;
            $primarykey = "kpi_project_id";
            $condition = "";
            $order = "";
            if (isset($_GET['err']) && $_GET['err'] == "datanotfound") {
                global $ALERT;
                $ALERT->datanotfound();
            }
            view_table_kpi($DBKPINEW, $tblname, $primarykey, $condition, $order);
        }
        function form_data($tblname)
        {
            // include("components/modules/kpi_board_v2/form_kpi_board_v2.php");
        }

        // End Function

        $database = 'sa_google_calendar';
        include("components/modules/sync_productivity/connection.php");
        $DBKPI = new Databases($hostname, $username, $userpassword, $database);
        $DBKPINEW = get_conn("GOOGLE_CALENDAR");
        $DBGC = get_conn("google_calendar");
        $tblname = 'kpi_project';

        include_once("components/modules/sync_productivity/func_sync_productivity.php");
        include_once("components/modules/sync_productivity/func_sync.php");
        // include("components/modules/kpi_board_v2/func_kpi_board_v2.php");

        // Body
        ?>
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-secondary">Select Project</h6>
                    <div class="align-items-right">
                        <!-- <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#filter" style="font-size:10px"><span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Filter data'><i class='fa fa-filter'></i></span></button> -->
                    </div>
                </div>
                <div class="card-body">
                    <?php
                    if (!isset($_GET['act'])) { 
                       include('components/modules/sync_productivity/list_cek.php');
                    } elseif ($_GET['act'] == 'add') {
                        form_data($tblname);
                    } elseif ($_GET['act'] == 'new') {
                        new_projects($tblname);
                    } elseif ($_GET['act'] == 'edit') {
                        form_data($tblname);
                    } elseif ($_GET['act'] == 'edit_request') {
                        form_data($tblname2);
                    } elseif ($_GET['act'] == 'schdule_request') {
                        form_data($tblname);
                    } elseif ($_GET['act'] == 'schdule_request_monitoring') {
                        form_data($tblname);
                    } elseif ($_GET['act'] == 'request_pending') {
                        form_data($tblname);
                    } elseif ($_GET['act'] == 'del') {
                        echo 'Delete Data';
                    } elseif ($_GET['act'] == 'save') {
                        form_data($tblname);
                    }
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
// }
?>

