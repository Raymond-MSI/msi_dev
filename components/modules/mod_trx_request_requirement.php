<?php
if ((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $released = "1698659499";
    $author = 'Syamsul Arham';
} else {

    $modulename = "trx_request_requirement";
    $userpermission = useraccess($modulename);
    if (USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION == "dbf36ff3e3827639223983ee8ac47b42") {
?>
        <script>
            $(document).ready(function() {
                var table = $('#trx_request_requirement').DataTable({
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
                                window.location.href = "index.php?mod=trx_request_requirement&act=view&id=" + id + "&submit=Submit";
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
                                window.location.href = "index.php?mod=trx_request_requirement&act=edit&id=" + id + "&submit=Submit";
                            }
                        },
                        {
                            text: "<i class='fa fa-plus'></i>",
                            action: function() {
                                window.location.href = "index.php?mod=trx_request_requirement&act=add";
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
            function view_data($tblname, $condition)
            {
                // Definisikan tabel yang akan ditampilkan dalam DataTable
                global $DBNEWREQUEST;
                $primarykey = "id";
                // $condition = "";
                $order = "";
                if (isset($_GET['err']) && $_GET['err'] == "datanotfound") {
                    global $ALERT;
                    $ALERT->datanotfound();
                }
                view_table($DBNEWREQUEST, $tblname, $primarykey, $condition, $order);
            }
            function form_data($tblname)
            {
                include("components/modules/trx_request_requirement/form_trx_request_requirement.php");
            }

            // End Function

            // $database = 'sa_new_request';
            // include("components/modules/trx_request_requirement/connection.php");
            // $DBNEWREQUEST = get_conn('new_request_requirement');
            // $tblname = 'trx_request_requirement';
            $tblname = 'trx_request_requirement';
            $tblname2 = 'trx_approval';
            $tblname3 = 'trx_share';
            $tblname4 = 'interview';
            $tblname5 = 'offering';
            $tblname6 = 'email';
            $tblname7 = 'join';

            include("components/modules/trx_request_requirement/func_trx_request_requirement.php");

            // Body
        ?>
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary"></h6>
                    </div>
                    <div class="card-body">
                        <?php if (!isset($_GET['act'])) { ?>
                            <select name="" id="nr_stat">
                                <option value="">Requirement</option>
                                <option value="Pending">Pending</option>
                                <!-- <option value="Disapprove">Disapprove</option> -->
                                <!-- <option value="Approve">Approve</option> -->
                                <!-- <option value="Submitted">Requirement Submitted</option> -->
                                <option value="Approval">Assign Recruitment</option>
                                <option value="Share">Upload CV</option>
                                <option value="Interview">Interview</option>
                                <option value="Offering">Offering</option>
                                <option value="Join">Join</option>
                            </select>
                        <?php } ?>
                        <?php
                        if (!isset($_GET['act']) && !isset($_GET['nr_stat'])) {
                            $condition = "status_request = 'Inactive'";
                            view_data($tblname, $condition);
                        } elseif (!isset($_GET['act']) && $_GET['nr_stat'] == "Pending") {
                            $condition = "status_request='Pending Approval'";
                            view_data($tblname, $condition);
                            // } elseif (!isset($_GET['act']) && $_GET['nr_stat'] == "Approve") {
                            //     $condition = "status_gm = 'Approve' AND status_gm_hcm = 'Approve' AND status_request = 'Pending Approval'";
                            //     view_data($tblname, $condition);
                        } elseif (!isset($_GET['act']) && $_GET['nr_stat'] == "Submitted") {
                            $condition = "status_request = 'Submitted'";
                            view_data($tblname, $condition);
                        } elseif (!isset($_GET['act']) && $_GET['nr_stat'] == "Approval") {
                            $condition = "status_approval = 'Approved'";
                            view_data($tblname2, $condition);
                        } elseif (!isset($_GET['act']) && $_GET['nr_stat'] == "Share") {
                            $condition = "divisi is not null";
                            view_data($tblname3, $condition);
                        } elseif (!isset($_GET['act']) && $_GET['nr_stat'] == "Interview") {
                            $condition = "divisi is not null";
                            view_data($tblname4, $condition);
                        } elseif (!isset($_GET['act']) && $_GET['nr_stat'] == "Offering") {
                            $condition = "project_code is not null";
                            view_data($tblname5, $condition);
                        } elseif (!isset($_GET['act']) && $_GET['nr_stat'] == "Join") {
                            $condition = "status_manager_hcm = 'Approve' AND status_hcm_gm = 'Approve' AND status_manager_gm_bod = 'Approve'";
                            view_data($tblname7, $condition);
                        } elseif ($_GET['act'] == 'add') {
                            form_data($tblname);
                            // } elseif ($_GET['act'] == 'new') {
                            //     new_projects($tblname);
                        } elseif ($_GET['act'] == 'edit') {
                            form_data($tblname);
                        } elseif ($_GET['act'] == 'editapproval') {
                            form_data($tblname2);
                        } elseif ($_GET['act'] == 'editshare') {
                            form_data($tblname3);
                        } elseif ($_GET['act'] == 'complete') {
                            form_data($tblname3);
                        } elseif ($_GET['act'] == 'editinterview') {
                            form_data($tblname4);
                        } elseif ($_GET['act'] == 'editoffering') {
                            form_data($tblname5);
                        } elseif ($_GET['act'] == 'editjoin') {
                            form_data($tblname7);
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
// }
// }
?>
<script>
    // $(document).on('click', '.dt-button', function(e){
    //     if(!$('.select-item').length){
    //         alert('Please select the row from table below');
    //         window.location.href = "";
    //     }
    // });
    $(document).on('change', '#nr_stat', function() {
        var sta = $('#nr_stat').val();
        if (sta == "") {
            window.location = window.location.pathname + "?mod=trx_request_requirement";
        } else {
            window.location = window.location.pathname + "?mod=trx_request_requirement&nr_stat=" + sta;
        }
    });
    <?php if (isset($_GET['nr_stat'])) { ?>
        $('#nr_stat option[value=<?php echo $_GET['nr_stat']; ?>]').attr('selected', 'selected');
    <?php } ?>

    // function pilih() {
    //     alert(document.getElementById("select[]").count);
    // }
</script>