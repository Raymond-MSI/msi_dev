<?php
if ((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $released = "1715069209";
    $author = 'Syamsul Arham';
} else {

    $mdlname = "REQUIREMENT_HCM";
    $userpermission = useraccess($mdlname);
    // var_dump($userpermission);
    // die;
    if (USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION == "dbf36ff3e3827639223983ee8ac47b42" || USERPERMISSION == "726ea0dd998698e8a87f8e344d373533" || USERPERMISSION == "5898299487c5b9cdbe7d61809fd20213" || USERPERMISSION == "335a66c239a137964a33e8c60b24e3d9" || USERPERMISSION == "0162bce636a63c3ae499224203e06ed0" || USERPERMISSION == "858ba4765e53c712ef672a9570474b1d" || USERPERMISSION == "34ac674a9e7eead3136801663c282dff" || USERPERMISSION == "125b55092905c1919f7558d68cfd62d7" || USERPERMISSION == "975031eb0e919d08ec6ba1993b455793") {
?>
        <script>
            $(document).ready(function() {
                var table = $('#hcm_requirement').DataTable({
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
                                var id = table.cell(rownumber, 0).data();
                                window.location.href = "index.php?mod=hcm_requirement&act=view&id=" + id + "&submit=Submit";
                            },
                            enabled: false
                        },
                        {
                            text: "<i class='fa fa-pen'></i>",
                            action: function() {
                                var rownumber = table.rows({
                                    selected: true
                                }).indexes();
                                var id = table.cell(rownumber, 0).data();
                                window.location.href = "index.php?mod=hcm_requirement&act=edit&id=" + id + "&submit=Submit";
                            }
                        },
                        {
                            text: "<i class='fa fa-plus'></i>",
                            action: function() {
                                window.location.href = "index.php?mod=hcm_requirement&act=add";
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
        //   if($_SESSION['Microservices_UserLevel'] == "Administrator") {
        function view_data($tblname, $condition)
        {
            // Definisikan tabel yang akan ditampilkan dalam DataTable
            global $DBHCM;
            $primarykey = "id";
            // $condition = "";
            $order = "";
            if (isset($_GET['err']) && $_GET['err'] == "datanotfound") {
                global $ALERT;
                $ALERT->datanotfound();
            }
            view_table($DBHCM, $tblname, $primarykey, $condition, $order);
        }
        function form_data($tblname)
        {
            include("components/modules/hcm_requirement/form_hcm_requirement.php");
        }

        // End Function

        // $database = 'sa_md_hcm';
        // include("components/modules/hcm_requirement/connection.php");
        // $DB = new Databases($hostname, $username, $userpassword, $database);
        // $DBHCM = get_conn('hcm_requirement');
        $tblname = 'hcm_requirement';

        include("components/modules/hcm_requirement/func_hcm_requirement.php");

        // Body
        ?>
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">hcm_requirement</h6>
                </div>
                <div class="card-body">
                    <?php if (!isset($_GET['act'])) { ?>
                        <select name="" id="nr_stat">
                            <!-- <option value="">Requirement</option> -->
                            <option value="">Request</option>
                            <option value="Submitted">Requirement Submitted</option>
                            <option value="Approval">Assignment Requester</option>
                            <option value="Share">Upload CV</option>
                            <option value="Interview">Interview</option>
                            <option value="Offering">Offering</option>
                            <option value="Join">Join</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    <?php } ?>
                    <?php
                    if (!isset($_GET['act'])) {
                        $condition = "project_code is NOT null";
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