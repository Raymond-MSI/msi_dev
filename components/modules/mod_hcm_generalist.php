<?php
if ((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $released = "1749001272";
    $author = 'Syamsul Arham';
} else {

    $modulename = "hcm_requirement_interview";

    $mdlname = 'HCM_GENERALIST';
    $mdl_permission = useraccess_v3($mdlname);


    if ($mdl_permission['user_level'] != "None") {
        // if (
        //     USERPERMISSION_V2 == "7b7bc2512ee1fedcd76bdc68926d4f7b"
        //     || USERPERMISSION_V2 == "dbf36ff3e3827639223983ee8ac47b42"
        //     || USERPERMISSION_V2 == "726ea0dd998698e8a87f8e344d373533"
        //     || USERPERMISSION_V2 == "5898299487c5b9cdbe7d61809fd20213"
        //     || USERPERMISSION_V2 == "335a66c239a137964a33e8c60b24e3d9"
        // ) {
        // var_dump($modulename);
        // die;
?>
        <script>
            $(document).ready(function() {
                var table = $('#hcm_requirement_interview').DataTable({
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
                                var email_id = table.cell(rownumber, 0).data();
                                window.location.href = "index.php?mod=hcm_generalist&act=view&email_id=" + email_id + "&submit=Submit";
                            },
                            // enabled: false
                        },
                        {
                            text: "<i class='fa fa-pen'></i>",
                            action: function() {
                                var rownumber = table.rows({
                                    selected: true
                                }).indexes();
                                var email_id = table.cell(rownumber, 0).data();
                                window.location.href = "index.php?mod=hcm_generalist&act=edit&email_id=" + email_id + "&submit=Submit";
                            }
                        },
                        {
                            text: "<i class='fa fa-plus'></i>",
                            action: function() {
                                window.location.href = "index.php?mod=hcm_generalist&act=add";
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
            global $DBHCM;
            $primarykey = "email_id";
            $condition = "";
            $order = "";
            if (isset($_GET['err']) && $_GET['err'] == "datanotfound") {
                global $ALERT;
                $ALERT->datanotfound();
            }
            view_table($DBHCM, $tblname, $primarykey, $condition, $order);
        }
        function form_data($tblname)
        {
            include("components/modules/hcm_generalist/form_hcm_generalist.php");
        }

        // End Function

        // $database = 'sa_md_hcm';
        // include("components/modules/hcm_generalist/connection.php");
        // $DB = new Databases($hostname, $username, $userpassword, $database);
        // $DBHCMGENERALIST = get_conn('sa_md_hcm');
        $tblname = 'hcm_requirement_interview';

        include("components/modules/hcm_generalist/func_hcm_generalist.php");

        // Body
        ?>
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">HCM GENERALIST</h6>
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