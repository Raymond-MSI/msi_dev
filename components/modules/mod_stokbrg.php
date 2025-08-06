<?php
if ((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $released = "1686637756";
    $author = 'Syamsul Arham';
} else {
?>
    <?php
    $mdlname = "STOKBRG";
    $userpermission = useraccess_v2($mdlname);
    $modulename = "Stokbrg";
    $userpermission = useraccess($modulename);
    if (USERPERMISSION_V2 == "bd5806c272288b3d83f2bee84170a4e7") {
        // if(USERPERMISSION=="7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION=="dbf36ff3e3827639223983ee8ac47b42" || USERPERMISSION=="726ea0dd998698e8a87f8e344d373533" || USERPERMISSION=="5898299487c5b9cdbe7d61809fd20213" || USERPERMISSION=="335a66c239a137964a33e8c60b24e3d9" || USERPERMISSION=="0162bce636a63c3ae499224203e06ed0" || USERPERMISSION=="858ba4765e53c712ef672a9570474b1d" ) {
    ?>
        <script>
            $(document).ready(function() {
                var table = $('#stokbrg').DataTable({
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
                                var idbrg = table.cell(rownumber, 0).data();
                                window.location.href = "index.php?mod=stokbrg&act=view&idbrg=" + idbrg + "&submit=Submit";
                            },
                            enabled: false
                        },
                        {
                            text: "<i class='fa fa-pen'></i>",
                            action: function() {
                                var rownumber = table.rows({
                                    selected: true
                                }).indexes();
                                var idbrg = table.cell(rownumber, 0).data();
                                window.location.href = "index.php?mod=stokbrg&act=edit&idbrg=" + idbrg + "&submit=Submit";
                            }
                        },
                        {
                            text: "<i class='fa fa-plus'></i>",
                            action: function() {
                                window.location.href = "index.php?mod=stokbrg&act=add";
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
        if ($_SESSION['Microservices_UserLevel'] == "Administrator" || $_SESSION['Microservices_UserLevel'] == "Super Admin") {
            function view_data($tblname)
            {
                // Definisikan tabel yang akan ditampilkan dalam DataTable
                global $DBWH;
                $primarykey = "idbrg";
                $condition = "";
                $order = "";
                if (isset($_GET['err']) && $_GET['err'] == "datanotfound") {
                    global $ALERT;
                    $ALERT->datanotfound();
                }
                view_table($DBWH, $tblname, $primarykey, $condition, $order);
            }
            function form_data($tblname)
            {
                include("components/modules/stokbrg/form_stokbrg.php");
            }

            // End Function
            $database = 'sa_data_warehouse';
            include("components/modules/stokbrg/connection.php");
            $DBWH = new Databases($hostname, $username, $userpassword, $database);
            $tblname = 'stokbrg';

            include("components/modules/stokbrg/func_stokbrg.php");

            // Body
        ?>
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">STOKBRG</h6>
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
}
?>