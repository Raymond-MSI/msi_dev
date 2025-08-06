<?php
if ((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $released = "1695606223";
    $author = 'Syamsul Arham';
} else {

    $modulename = "reqreq";
    $userpermission = useraccess($modulename);
    if (USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION == "dbf36ff3e3827639223983ee8ac47b42") {
        $user = $_SESSION['Microservices_UserEmail'];
?>
        <script>
            $(document).ready(function() {
                var table = $('#reqreq').DataTable({
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
                        // {
                        //     text: "<i class='fa fa-eye'></i>",
                        //     action: function() {
                        //         var rownumber = table.rows({
                        //             selected: true
                        //         }).indexes();
                        //         var id = table.cell(rownumber, 0).data();
                        //         window.location.href = "index.php?mod=trx_recruitement&act=view&id=" + id + "&submit=Submit";
                        //     },
                        //     enabled: false
                        // },
                        {
                            text: "<i class='fa fa-pen'></i>",
                            action: function() {
                                var rownumber = table.rows({
                                    selected: true
                                }).indexes();
                                var id = table.cell(rownumber, 0).data();
                                var project_code = table.cell(rownumber, 8).data;
                                var gm = table.cell(rownumber, 25).data();
                                var gm_hcm = table.cell(rownumber, 27).data();
                                var gm_bod = table.cell(rownumber, 29).data();
                                var created_by = table.cell(rownumber, 24).data();
                                if (id == null) {
                                    alert("Silahkan Pilih Project");
                                } else if (id != null && gm == "<?php echo $user ?>" || id != null && gm_hcm == "<?php echo $user ?>" || id != null && gm_bod == "<?php echo $user ?>" || id != null && created_by == "<?php echo $user ?>") {
                                    window.location.href = "index.php?mod=reqreq&act=edit&id=" + id + "&submit=Submit";
                                }
                            },
                        },
                        {
                            text: "<i class='fa fa-plus'></i>",
                            action: function() {
                                window.location.href = "index.php?mod=reqreq&act=add";
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
        if ($_SESSION['Microservices_UserLevel'] == "Super Admin") {
            function view_data($tblname)
            {
                // Definisikan tabel yang akan ditampilkan dalam DataTable
                global $DBCOBA;
                $primarykey = "id";
                $condition = "";
                $order = "";
                if (isset($_GET['err']) && $_GET['err'] == "datanotfound") {
                    global $ALERT;
                    $ALERT->datanotfound();
                }
                view_table($DBCOBA, $tblname, $primarykey, $condition, $order);
            }
            function form_data($tblname)
            {
                include("components/modules/reqreq/form_reqreq.php");
            }

            // End Function

            $database = 'sa_new';
            include("components/modules/reqreq/connection.php");
            $DBCOBA = new Databases($hostname, $username, $userpassword, $database);
            $tblname = 'reqreq';

            include("components/modules/reqreq/func_reqreq.php");

            // Body
        ?>
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">reqreq</h6>
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