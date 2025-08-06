<?php
if ((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $released = "1686808920";
    $author = 'Syamsul Arham';
} else {

    $modulename = "trx_maskomen";
    $userpermission = useraccess_v2($modulename);
    if (USERPERMISSION_V2 == "5bc984b5c1405fff204c52a432b617b7" || USERPERMISSION_V2 == "5898299487c5b9cdbe7d61809fd20213") {
        // if(USERPERMISSION_V2=="7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION_V2=="dbf36ff3e3827639223983ee8ac47b42") {
?>
        <script>
            $(document).ready(function() {
                var table = $('#trx_maskomen').DataTable({
                    dom: 'Blfrtip',
                    select: {
                        style: 'single'
                    },
                    buttons: [{
                            extend: 'excelHtml5',
                            text: "<i class='fa-solid fa-file-excel'></i>",
                            title: 'Report Review Survey Question Full' + <?php echo date("YmdGis"); ?>
                        },
                        {
                            text: "<i class='fa fa-eye'></i>",
                            action: function() {
                                var rownumber = table.rows({
                                    selected: true
                                }).indexes();
                                var id = table.cell(rownumber, 0).data();
                                window.location.href = "index.php?mod=trx_maskomen&act=view&id=" + id + "&submit=Submit";
                            },
                            //    enabled: false
                        },
                        {
                            text: "<i class='fa fa-pen'></i>",
                            action: function() {
                                var rownumber = table.rows({
                                    selected: true
                                }).indexes();
                                var id = table.cell(rownumber, 0).data();
                                window.location.href = "index.php?mod=trx_maskomen&act=edit&id=" + id + "&submit=Submit";
                            }
                        },
                        {
                            text: "<i class='fa fa-plus'></i>",
                            action: function() {
                                window.location.href = "index.php?mod=trx_maskomen&act=add";
                            },
                            // enabled: false
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
        //   if($_SESSION['Microservices_UserLevel'] == "Administrator"|| $_SESSION['Microservices_UserLevel'] == "Super Admin") {
        function view_data($tblname, $condition)
        {
            // Definisikan tabel yang akan ditampilkan dalam DataTable
            global $DBMK;
            $primarykey = "id";
            // $condition = "";
            $order = "";
            if (isset($_GET['err']) && $_GET['err'] == "datanotfound") {
                global $ALERT;
                $ALERT->datanotfound();
            }
            view_table($DBMK, $tblname, $primarykey, $condition, $order);
        }
        function form_data($tblname)
        {
            include("components/modules/trx_maskomen/form_trx_maskomen.php");
        }

        // End Function

        // $tblname = 'cfg_web';
        // $condition = 'config_key="MODULE_TRX_MASKOMEN"';
        // $setupDB = $DB->get_data($tblname, $condition);
        // $dsetupDB = $setupDB[0];
        // if ($setupDB[2] > 0) {
        //     $params = get_params($dsetupDB['params']);
        //     $hostname = $params['database']['hostname'];
        //     $username = $params['database']['username'];
        //     $userpassword = $params['database']['userpassword'];
        //     $database = $params['database']['database_name'];
        //     //   $database = 'sa_md_hcm';
        //     //   include("components/modules/trx_maskomen/connection.php");
        //     $DBMK = new Databases($hostname, $username, $userpassword, $database);
        $DBMK = get_conn('trx_maskomen');
        $tblname = 'trx_maskomen';

        include("components/modules/trx_maskomen/func_trx_maskomen.php");

        // Body
        ?>
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Collect Artikel Maskomen</h6>
                </div>
                <div class="card-body">
                    <?php
                    if (!isset($_GET['act'])) {
                        $condition = "";
                        view_data($tblname, $condition);
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
                    } elseif ($_GET['act'] == 'view') {
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