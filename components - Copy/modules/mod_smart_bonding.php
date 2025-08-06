<?php
if ((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $released = "1662434634";
    $author = 'Syamsul Arham';
} else {

    $modulename = "master_data";
    $userpermission = useraccess($modulename);
    if (USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION == "dbf36ff3e3827639223983ee8ac47b42" || USERPERMISSION == "726ea0dd998698e8a87f8e344d373533" || USERPERMISSION == "5898299487c5b9cdbe7d61809fd20213" || USERPERMISSION == "335a66c239a137964a33e8c60b24e3d9" || USERPERMISSION == "0162bce636a63c3ae499224203e06ed0" || USERPERMISSION == "858ba4765e53c712ef672a9570474b1d") {
?>
        <script>
            $(document).ready(function() {
                var table = $('#master_data').DataTable({
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
                            text: "Get Data Manage Engine",
                            action: function() {
                                var rownumber = table.rows({
                                    selected: true
                                }).indexes();
                                var Master_id = table.cell(rownumber, 0).data();
                                window.location.href = "components/modules/smart_bonding/smartbonding.php";
                            },
                        },
                        {
                            text: "Create Ticket to Cisco",
                            action: function() {
                                var rownumber = table.rows({
                                    selected: true
                                }).indexes();
                                var Master_id = table.cell(rownumber, 0).data();
                                window.location.href = "components/modules/smart_bonding/CreateTicket.php";
                            }
                        },
                        {
                            text: "Update Ticket",
                            action: function() {
                                window.location.href = "components/modules/smart_bonding/UpdateWorkNote.php";
                            },
                            // enabled: false
                        },
                        {
                            text: "Close Ticket",
                            action: function() {
                                var rownumber = table.rows({
                                    selected: true
                                }).indexes();
                                var Master_id = table.cell(rownumber, 0).data();
                                window.location.href = "components/modules/smart_bonding/CloseTicket.php";
                            }
                        },
                        {
                            text: "Pull TSP Codes",
                            action: function() {
                                var rownumber = table.rows({
                                    selected: true
                                }).indexes();
                                var Master_id = table.cell(rownumber, 0).data();
                                window.location.href = "components/modules/smart_bonding/PullTSPCodes.php";
                            }
                        },
                        {
                            text: "Create Escalated Ticket",
                            action: function() {
                                var rownumber = table.rows({
                                    selected: true
                                }).indexes();
                                var Master_id = table.cell(rownumber, 0).data();
                                window.location.href = "components/modules/smart_bonding/CreateEscalatedTicket.php";
                            }
                        },
                        {
                            text: "Escalated Ticket to Cisco",
                            action: function() {
                                var rownumber = table.rows({
                                    selected: true
                                }).indexes();
                                var Master_id = table.cell(rownumber, 0).data();
                                window.location.href = "components/modules/smart_bonding/EscalatedTickettoCisco.php";
                            }
                        },
                        {
                            text: "Resolve Ticket",
                            action: function() {
                                var rownumber = table.rows({
                                    selected: true
                                }).indexes();
                                var Master_id = table.cell(rownumber, 0).data();
                                window.location.href = "components/modules/smart_bonding/ResolveWorkNote.php";
                            }
                        },
                        {
                            text: "Pull Update",
                            action: function() {
                                var rownumber = table.rows({
                                    selected: true
                                }).indexes();
                                var Master_id = table.cell(rownumber, 0).data();
                                window.location.href = "components/modules/smart_bonding/PullUpdate.php";
                            }
                        },
			{
                            text: "Add Worklog",
                            action: function() {
                                var rownumber = table.rows({
                                    selected: true
                                }).indexes();
                                var Master_id = table.cell(rownumber, 0).data();
                                window.location.href = "components/modules/smart_bonding/AddWorklog.php";
                            },
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
        function view_data($tblname)
        {
            // Definisikan tabel yang akan ditampilkan dalam DataTable
            global $DB;
            $primarykey = "Master_id";
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
            include("components/modules/smart_bonding/smartbonding.php");
        }

        // End Function

        // $database = 'sa_smartbonding';
        // include("components/modules/smart_bonding/connection.php");
        $tblname = 'cfg_web';
        $condition = 'config_key="MODULE_SMART_BONDING"';
        $setupDB = $DB->get_data($tblname, $condition);
        $dsetupDB = $setupDB[0];
        if ($setupDB[2] > 0) {
            $params = get_params($dsetupDB['params']);
            $hostname = $params['database']['hostname'];
            $username = $params['database']['username'];
            $userpassword = $params['database']['userpassword'];
            $database = $params['database']['database_name'];
            $DB = new Databases($hostname, $username, $userpassword, $database);
            $tblname = 'master_data';

            // include("components/modules/smart_bonding/func_smart_bonding.php");

            // Body
        ?>
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">master_data</h6>
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
        }
    } else {
        $ALERT->notpermission();
    }
    // End Body
}
?>