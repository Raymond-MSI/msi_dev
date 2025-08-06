<?php
if ((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $released = "1708498806";
    $author = 'Syamsul Arham';
} else {

    $modulename = "Log_Complain";
    $userpermission = useraccess($modulename);
    if (USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION == "dbf36ff3e3827639223983ee8ac47b42" || USERPERMISSION == "5898299487c5b9cdbe7d61809fd20213") {
?>
        <script>
            $(document).ready(function() {
                var table = $('#complain').DataTable({
                    dom: 'Blfrtip',
                    select: {
                        style: 'single'
                    },
                    buttons: [
                        // {
                        //     extend: 'colvis',
                        //     text: "<i class='fa fa-columns'></i>",
                        //     collectionLayout: 'fixed four-column'
                        // },
                        {
                            extend: 'csvHtml5',
                            text: "<i class='fa-solid fa-file-excel'></i>",
                            title: 'Report Complain Log' + <?php echo date("YmdGis"); ?>
                        },
                        {
                            // text: "<i class='fa fa-eye'></i>",
                            // action: function() {
                            //     var rownumber = table.rows({
                            //         selected: true
                            //     }).indexes();
                            //     var complain_id = table.cell(rownumber, 0).data();
                            //     window.location.href = "index.php?mod=log_complain&act=view&complain_id=" + complain_id + "&submit=Submit";
                            // },
                            // enabled: false
                            text: "<i class='fa fa-plus'></i>",
                            action: function() {
                                window.location.href = "index.php?mod=log_complain&act=add";
                            },
                        },
                        {
                            text: "<i class='fa fa-pen'></i>",
                            action: function() {
                                var rownumber = table.rows({
                                    selected: true
                                }).indexes();
                                var complain_id = table.cell(rownumber, 0).data();
                                var project_code = table.cell(rownumber, 1).data();
                                window.location.href = "index.php?mod=log_complain&act=edit&complain_id=" + complain_id + "&ProjectCode=" + project_code + "&submit=Submit";
                            }
                            // },
                            // {
                            //     text: "<i class='fa fa-plus'></i>",
                            //     action: function() {
                            //         window.location.href = "index.php?mod=log_complain&act=add";
                            //     },
                            // enabled: false
                        },
                        {
                            text: "<i class='fa fa-trash'></i>",
                            action: function() {
                                var rownumber = table.rows({
                                    selected: true
                                }).indexes();
                                var complain_id = table.cell(rownumber, 0).data();
                                var project_code = table.cell(rownumber, 1).data();
                                window.location.href = "index.php?mod=log_complain&act=delete&complain_id=" + complain_id + "&submit=Submit";
                            }
                        },
                        {
                            text: '<span class="d-inline-block" tabindex="0" data-bs-toggle="modal" data-bs-target="#filterModal" title="Filter data"><i class="fa fa-filter"></i></span>',
                            enabled: true
                        }
                    ],
                    "columnDefs": [{
                        "targets": [0],
                        "visible": false,
                    }],
                });
            });
        </script>
        <?php

        // Function
        // if ($_SESSION['Microservices_UserLevel'] == "Super Admin") {
        function view_data($tblname, $condition)
        {
            // Definisikan tabel yang akan ditampilkan dalam DataTable
            global $DBKPILog;
            $primarykey = "id";
            // $condition = "";
            $order = "";
            if (isset($_GET['err']) && $_GET['err'] == "datanotfound") {
                global $ALERT;
                $ALERT->datanotfound();
            }
            view_table($DBKPILog, $tblname, $primarykey, $condition, $order);
        }
        function form_data($tblname)
        {
            include("components/modules/log_complain/form_log_complain.php");
        }

        function delete_data($tblname)
        {
            ("components/modules/log_complain/func_log_complain.php");
        }

        // End Function

        // $database = 'sa_dashboard_kpi';
        // include("components/modules/log_complain/connection.php");
        // $DBKPILog = new Databases($hostname, $username, $userpassword, $database);
        $DBKPILog = get_conn('log_complain');
        $tblname = 'complain';

        include("components/modules/log_complain/func_log_complain.php");

        // Body
        ?>
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">log_complain</h6>
                </div>
                <div class="card-body">
                    <?php
                    if (!isset($_GET['act'])) {
                        if (!empty($_GET['project_code'])) {
                            if ($_GET['project_code'] == "All") {
                                $project_code = "AND type_project IS NOT NULL";
                            } else {
                                $project_code = "AND project_code LIKE '%" . $_GET['project_code'] . "%'";
                            }
                        } else {
                            $project_code = "AND type_project IS NOT NULL";
                        }
                        if (!empty($_GET['type_project'])) {
                            if ($_GET['type_project'] == "All") {
                                $tipe = "AND type_project IS NOT NULL";
                            } else {
                                $tipe = "AND type_project LIKE '%" . $_GET['type_project'] . "%'";
                            }
                        } else {
                            $tipe = "AND type_project IS NOT NULL";
                        }
                        if (!empty($_GET['tanggal'])) {
                            $tanggal = "AND tanggal LIKE '%" . $_GET['tanggal'] . "%'";
                        } else {
                            $tanggal = "AND tanggal IS NOT NULL";
                        }
                        $condition = "status is not null $project_code $tipe $tanggal";
                        view_data($tblname, $condition);
                    } elseif ($_GET['act'] == 'add') {
                        form_data($tblname);
                    } elseif ($_GET['act'] == 'new') {
                        new_projects($tblname);
                    } elseif ($_GET['act'] == 'edit') {
                        form_data($tblname);
                    } elseif ($_GET['act'] == 'delete') {
                        form_data($tblname);
                    } elseif ($_GET['act'] == 'del') {
                        delete_data($tblname);
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

<?php
$date = date("Y");
$kemarin = $date - 1;
$besok = $date + 1;
?>
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="get" action="index.php?mod=<?php echo $_GET['mod']; ?>">
                <input type="hidden" id="mod" name="mod" value="<?php echo $_GET['mod']; ?>">
                <!-- <input type="hidden" id="status_board" name="status_board" value="<?php //echo $_GET['status_board']; 
                                                                                        ?>"> -->
                <div class="modal-header">
                    <h4 class="modal-title">Filter Complain</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Type Project</label>
                            <select type="text" class="form-control" id="type_project" name="type_project">
                                <option value="All">All</option>
                                <option value="Maintenance">Maintenance</option>
                                <option value="Implementation">Implementation</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tahun</label>
                            <select type="text" class="form-control" id="tanggal" name="tanggal">
                                <option value="">All</option>
                                <option value="<?php echo $kemarin; ?>"><?php echo $kemarin; ?></option>
                                <option value="<?php echo $date; ?>"><?php echo $date; ?></option>
                                <option value="<?php echo $besok; ?>"><?php echo $besok; ?></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Resource</label>
                            <select type="text" class="form-control form-control-sm" id="project_code" name="project_code">
                                <option value="All">All</option>
                                <?php $ambildata = $DBKPILog->get_sqlV2("SELECT * FROM sa_complain WHERE type_project is not null");
                                while ($row = $ambildata[1]->fetch_assoc()) { ?>
                                    <option value="<?php echo $row['project_code']; ?>"><?php echo $row['project_code']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-success" name="search_data" value="Search">
                </div>
            </form>
        </div>
    </div>
</div>