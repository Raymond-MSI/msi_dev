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
                var table = $('#kpi_project_wr').DataTable({
                    dom: 'Blfrtip',
                    select: {
                        style: 'single'
                    },
                    buttons: [
			//{
                          //  text: "<i class='fa fa-file-pdf'></i>",
                            //action: function() {
                              //  var rownumber = table.rows({
                                //    selected: true
                                //}).indexes();
                                //var id = table.cell(rownumber, 0).data();
                                //var project_code = table.cell(rownumber, 2).data();
                                //window.location.href = "components/modules/get_data/export_project.php";
                            //}
                        //},
			{
                            text: "<i class='fa-solid fa-people-group'></i>",
                            action: function() {
                                var rownumber = table.rows({
                                    selected: true
                                }).indexes();
                                var id = table.cell(rownumber, 0).data();
                                var project_code = table.cell(rownumber, 2).data();
                                window.location.href = "index.php?mod=dashboard_kpi";
                            }
                        },
			{
                            extend: 'excelHtml5',
                            text: "<i class='fa fa-file-pdf'></i>",
                            title: 'KPI_Project'
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
        // if ($_SESSION['Microservices_UserLevel'] == "Administrator") {
        function view_data($tblname)
        {
            // Definisikan tabel yang akan ditampilkan dalam DataTable
            global $DBPKPI;
            $primarykey = "id";
            $condition = "";
            $order = "";
            if (isset($_GET['err']) && $_GET['err'] == "datanotfound") {
                global $ALERT;
                $ALERT->datanotfound();
            }
            view_table($DBPKPI, $tblname, $primarykey, $condition, $order);
        }
        function form_data($tblname)
        {
            include("components/modules/get_data/form_project_kpi.php");
            // include("components/modules/get_data/form_person_kpi.php");
        }

        function export($tblname)
        {
            include("components/modules/get_data/export_project.php");
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

            $DBPKPI = new Databases($hostname, $username, $userpassword, $database);
            $tblname = 'kpi_project_wr';

            include("components/modules/get_data/func_get_data.php");

            // Body
        ?>
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Project KPI</h6>
                    </div>
                    <div class="card-body">
                        <?php
                        if (!isset($_GET['act'])) {
                            view_data($tblname);
                        } elseif ($_GET['act'] == 'export') {
                            form_data($tblname);
                        } elseif ($_GET['act'] == 'add') {
                            export($tblname);
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
            echo "Aplikasi belum disetup";
        }
    } else {
        $ALERT->notpermission();
    }
    // End Body
}
?>