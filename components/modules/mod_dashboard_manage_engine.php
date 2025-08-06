<?php
if (isset($_GET['sub'])) {
    include("components/modules/dashboard_manage_engine/" . $_GET['sub'] . ".php");
} else 
if ((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $released = "1722398279";
    $author = 'Syamsul Arham';
} else {

    $modulename = "hcm_notecv";
    $userpermission = useraccess($modulename);
    if (USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION == "dbf36ff3e3827639223983ee8ac47b42") {
?>
        <script>
            $(document).ready(function() {
                var table = $('#hcm_notecv').DataTable({
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
                                var id_note = table.cell(rownumber, 0).data();
                                window.location.href = "index.php?mod=dashboard_manage_engine&act=view&id_note=" + id_note + "&submit=Submit";
                            },
                            enabled: false
                        },
                        {
                            text: "<i class='fa fa-pen'></i>",
                            action: function() {
                                var rownumber = table.rows({
                                    selected: true
                                }).indexes();
                                var id_note = table.cell(rownumber, 0).data();
                                window.location.href = "index.php?mod=dashboard_manage_engine&act=edit&id_note=" + id_note + "&submit=Submit";
                            }
                        },
                        {
                            text: "<i class='fa fa-plus'></i>",
                            action: function() {
                                window.location.href = "index.php?mod=dashboard_manage_engine&act=add";
                            },
                            // enabled: false
                        }
                    ],
                    "columnDefs": [{
                        "targets": [],
                        "visible": false,
                    }],
                });
            });
        </script>
        <?php

        // Function
        // if ($_SESSION['Microservices_UserLevel'] == "Administrator") {
        function view_data($tblname)
        {
            // Definisikan tabel yang akan ditampilkan dalam DataTable
            global $DB;
            $primarykey = "id_note";
            $condition = "";
            $order = "";
            if (isset($_GET['err']) && $_GET['err'] == "datanotfound") {
                global $ALERT;
                $ALERT->datanotfound();
            }
            view_table($DB, $tblname, $primarykey, $condition, $order);
        }
        function form_data($tblname)
        {
            include("components/modules/dashboard_manage_engine/form_dashboard_manage_engine.php");
        }

        // End Function

        $database = 'sa_md_hcm';
        include("components/modules/dashboard_manage_engine/connection.php");
        $DB = new Databases($hostname, $username, $userpassword, $database);
        $tblname = 'hcm_notecv';

        include("components/modules/dashboard_manage_engine/func_dashboard_manage_engine.php");

        // Body
        ?>
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">hcm_notecv</h6>
                </div>
                <div class="card-body">
                    <?php
                    if (!isset($_GET['act'])) {
                        view_data($tblname);
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