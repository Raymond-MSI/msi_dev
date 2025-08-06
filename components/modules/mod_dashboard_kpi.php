<?php
if ((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $released = "1666925346";
    $author = 'Syamsul Arham';
} else {
    $modulename = "user";
    $mdlname = "dashboard_kpi";
    $mdl_permission = useraccess_v2($mdlname);
    // $modulename = "get_data";
    // $userpermission = useraccess($modulename);
    if (USERPERMISSION_V2 == "b9746e15cbc1d6d7332506a86afe564a") {
        //  $user_mail = $_SESSION['Microservices_UserEmail'];
?>
        <script>
            $(document).ready(function() {
                var table = $('#user').DataTable({
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
                                var nama = table.cell(rownumber, 1).data();
				if (nama == null) {
                                    alert("Please select the data");
                                } else {
                                    window.location.href = "index.php?mod=dashboard_kpi&act=ganti&nama=" + nama;
                                }
                            }
                        },
                        {
                            text: "<i class='fa-solid fa-diagram-project'></i>",
                            action: function() {
                                var rownumber = table.rows({
                                    selected: true
                                }).indexes();
                                var id = table.cell(rownumber, 0).data();
                                var project_code = table.cell(rownumber, 2).data();
                                window.location.href = "index.php?mod=project_kpi";
                            }
                        },
                        {
                            text: "<i class='fa-solid fa-person'></i>",
                            action: function() {
                                var rownumber = table.rows({
                                    selected: true
                                }).indexes();
                                var id = table.cell(rownumber, 0).data();
                                var nama = table.cell(rownumber, 1).data();
                                if (id == null) {
                                    alert("Please select the data");
                                } else {
                                    window.location.href = "index.php?mod=kpi_engineer&nama=" + nama;
                                }
                            }
                        },
                        {
                            extend: 'excelHtml5',
                            text: "<i class='fa fa-file-pdf'></i>",
                            title: 'KPI_Engineer'
                        }
                        //{
                          //  text: "Sync Data KPI",
                            //action: function() {
                              //  var rownumber = table.rows({
                                //    selected: true
                                //}).indexes();
                                //var id = table.cell(rownumber, 0).data();
                                //window.location.href = "index.php?mod=get_data&act=edit&id=" + id + "&submit=Submit";
                            //}
                        //},
                        //{
                          //  text: "Sync Data Project",
                            //action: function() {
                              //  window.location.href = "index.php?mod=get_data&act=add";
                            //},
                            // enabled: false
                        //}
                    ],
                    "columnDefs": [{
                        "targets": [],
                        "visible": false,
                    }],
                    "columnDefs": [{
                        "targets": [0],
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
            global $DBKPI;
            $primarykey = "id";
            $condition = "";
            $order = "";
            if (isset($_GET['err']) && $_GET['err'] == "datanotfound") {
                global $ALERT;
                $ALERT->datanotfound();
            }
            view_table($DBKPI, $tblname, $primarykey, $condition, $order);
        }
        function form_data($tblname)
        {
            include("components/modules/get_data/form_get_data.php");
        }

        function form_project($tblname2)
        {
            include("components/modules/get_data/form_project_kpi.php");
        }

        function form_edit($tblname)
        {
            include("components/modules/get_data/form_edit_data.php");
        }
        // End Function

        // $database = 'sa_dashboard_kpi';
        // include("components/modules/get_data/connection.php");
        $tblname = 'cfg_web';
        $condition = 'config_key="MODULE_DASHBOARD_KPI"';
        $setupDB = $DB->get_data($tblname, $condition);
        $dsetupDB = $setupDB[0];
        if ($setupDB[2] > 0) {
            $params = get_params($dsetupDB['params']);
            $hostname = $params['database']['hostname'];
            $username = $params['database']['username'];
            $userpassword = $params['database']['userpassword'];
            $database = $params['database']['database_name'];

            $DBKPI = new Databases($hostname, $username, $userpassword, $database);
            $tblname = 'user';
            $tblname2 = 'kpi_project_wr';

            include("components/modules/get_data/func_get_data.php");

            // Body
        ?>
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Data All Engineer</h6>
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
                        } elseif ($_GET['act'] == 'ganti') {
                            form_data($tblname);
                        } elseif ($_GET['act'] == 'data_orang') {
                            form_data($tblname);
                        } elseif ($_GET['act'] == 'export_project') {
                            form_project($tblname2);
                        }
                        ?>
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
    // End Body
    // }
}
?>