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
    // if (USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION == "dbf36ff3e3827639223983ee8ac47b42" || USERPERMISSION == "726ea0dd998698e8a87f8e344d373533" || USERPERMISSION == "5898299487c5b9cdbe7d61809fd20213" || USERPERMISSION == "335a66c239a137964a33e8c60b24e3d9" || USERPERMISSION == "0162bce636a63c3ae499224203e06ed0" || USERPERMISSION == "858ba4765e53c712ef672a9570474b1d" || USERPERMISSION == "34ac674a9e7eead3136801663c282dff" || USERPERMISSION == "125b55092905c1919f7558d68cfd62d7" || USERPERMISSION == "975031eb0e919d08ec6ba1993b455793") {
    $user = $_SESSION['Microservices_UserEmail'];
    $HCMfull = $DBHCM->get_sqlV2("SELECT employee_email FROM sa_view_employees WHERE employee_email = '" . $_SESSION['Microservices_UserEmail'] .
        "'  AND job_structure LIKE '%JW ATN WM HCM Recruitment%' AND resign_date is null");
?>
    <input type="hidden" id="userEmail" value="<?php echo $HCMfull[0]['employee_email']; ?>">


    <!-- <script>
            $(document).ready(function() {
                var table = $('#hcm_requirement').DataTable({
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
                        {
                            text: "<i class='fa fa-plus'></i>",
                            action: function() {
                                window.location.href = "index.php?mod=hcm_requirement&act=add";
                            },
                            // enabled: false
                        },
                        // {
                        //     text: "<i class='fa fa-eye'></i>",
                        //     action: function() {
                        //         var rownumber = table.rows({
                        //             selected: true
                        //         }).indexes();
                        //         var id = table.cell(rownumber, 0).data();
                        //         window.location.href = "index.php?mod=hcm_requirement&act=view&id=" + id + "&submit=Submit";
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
                                var gm_hcm = table.cell(rownumber, 28).data();
                                var gm_bod = table.cell(rownumber, 31).data();
                                var created_by = table.cell(rownumber, 24).data();
                                var userEmail = document.getElementById('userEmail').value;
                                <?php
                                // $HCMfull = $DBHCM->get_sqlV2("SELECT employee_email FROM sa_view_employees WHERE employee_email = '" . $_SESSION['Microservices_UserEmail'] .
                                //     "'  AND job_structure LIKE '%JW ATN WM HCM Recruitment%' AND resign_date is null");
                                // $email = $HCMfull[0]['employee_email'];
                                ?>

                                if (id == null) {
                                    alert("Silahkan Pilih Project");
                                } else if (id != null && gm == "<?php echo $user ?>" || id != null && gm_hcm == "<?php echo $user ?>" || id != null && gm_bod == "<?php echo $user ?>" || id != null && created_by == "<?php echo $user ?>" || id != null && userEmail == "<?php echo $user ?>") {
                                    window.location.href = "index.php?mod=hcm_requirement&act=edit&id=" + id + "&submit=Submit";
                                }
                            },
                        }

                    ],
                    "columnDefs": [{
                        "targets": [],
                        "visible": false,
                    }],
                });
            });
        </script> -->

    <script>
        $(document).ready(function() {
            // Function to initialize DataTable with appropriate button actions
            function initializeDataTable(nr_stat_value) {
                var tableConfig = {
                    dom: 'Blfrtip',
                    select: {
                        style: 'single'
                    },
                    buttons: [
                        //     {
                        //     text: "<i class='fa fa-plus'></i>",
                        //     action: function() {
                        //         window.location.href = "index.php?mod=hcm_requirement&act=add";
                        //     }
                        // }
                    ],
                    columnDefs: [{
                        targets: [],
                        visible: false
                    }]
                };

                // Customize button actions based on nr_stat_value
                if (nr_stat_value === "") {
                    tableConfig.buttons.push({
                        text: "<i class='fa fa-plus'></i>",
                        action: function() {
                            window.location.href = "index.php?mod=hcm_requirement&act=add";
                        }
                    }, {
                        text: "<i class='fa fa-pen'></i>",
                        action: function() {
                            var rownumber = table.rows({
                                selected: true
                            }).indexes();
                            var id = table.cell(rownumber, 0).data();
                            var project_code = table.cell(rownumber, 8).data;
                            var gm = table.cell(rownumber, 25).data();
                            var gm_hcm = table.cell(rownumber, 28).data();
                            var gm_bod = table.cell(rownumber, 31).data();
                            var created_by = table.cell(rownumber, 24).data();
                            var userEmail = document.getElementById('userEmail').value;

                            if (id == null) {
                                alert("Silahkan Pilih Project");
                            } else if (id != null && gm == "<?php echo $user ?>" || id != null && gm_hcm == "<?php echo $user ?>" || id != null && gm_bod == "<?php echo $user ?>" || id != null && created_by == "<?php echo $user ?>" || id != null && userEmail == "<?php echo $user ?>") {
                                window.location.href = "index.php?mod=hcm_requirement&act=edit&id=" + id + "&submit=Submit";
                            }
                        },
                    });
                    tableConfig.columnDefs.push({
                        targets: [0],
                        visible: false
                    });
                } else if (nr_stat_value === "Submitted") {
                    tableConfig.buttons.push({
                        text: "<i class='fa fa-eye'></i>",
                        action: function() {
                            var rownumber = table.rows({
                                selected: true
                            }).indexes();
                            var id = table.cell(rownumber, 0).data();
                            var project_code = table.cell(rownumber, 8).data;
                            var gm = table.cell(rownumber, 25).data();
                            var gm_hcm = table.cell(rownumber, 28).data();
                            var gm_bod = table.cell(rownumber, 31).data();
                            var created_by = table.cell(rownumber, 24).data();
                            var userEmail = document.getElementById('userEmail').value;

                            if (id == null) {
                                alert("Silahkan Pilih Project");
                            } else {
                                window.location.href = "index.php?mod=hcm_requirement&act=view&id=" + id + "&submit=Submit";
                            }
                        },
                    });
                    tableConfig.columnDefs.push({
                        targets: [0],
                        visible: false
                    });
                } else if (nr_stat_value === "Approval") {
                    tableConfig.buttons.push({
                        text: "<i class='fa fa-pen'></i>",
                        action: function() {
                            var table = $('#hcm_requirement').DataTable();
                            var rownumber = table.rows({
                                selected: true
                            }).indexes();
                            var id = table.cell(rownumber, 0).data();
                            // Add your logic for action on 'Approval' here
                            window.location.href = "index.php?mod=hcm_requirement&act=editapproval&id=" + id + "&submit=Submit";
                        }
                    });
                    tableConfig.columnDefs.push({
                        targets: [0],
                        visible: false
                    });
                } else if (nr_stat_value === "Share") {
                    tableConfig.buttons.push({
                        text: "<i class='fa fa-pen'></i>",
                        action: function() {
                            var table = $('#hcm_requirement').DataTable();
                            var rownumber = table.rows({
                                selected: true
                            }).indexes();
                            var id = table.cell(rownumber, 0).data();
                            var project_code = table.cell(rownumber, 6).data();
                            // Add your logic for action on 'Approval' here
                            window.location.href = "index.php?mod=hcm_requirement&act=editshare&id=" + id + "&ProjectCode=" + project_code + "&submit=Submit";
                        }
                    });
                    tableConfig.columnDefs.push({
                        targets: [0],
                        visible: false
                    });
                }

                // Initialize DataTable with the configured options
                var table = $('#hcm_requirement').DataTable(tableConfig);
            }

            // Get the current value of nr_stat from the URL query parameter
            var currentNrStat = "<?php echo isset($_GET['nr_stat']) ? $_GET['nr_stat'] : ''; ?>";

            // Initialize DataTable based on the current nr_stat value
            initializeDataTable(currentNrStat);

            // Handle change event on nr_stat dropdown
            $('#nr_stat').change(function() {
                var selectedValue = $(this).val();
                var url = "index.php?mod=hcm_requirement";

                // Append nr_stat value to the URL
                if (selectedValue) {
                    url += "&nr_stat=" + selectedValue;
                }

                // Redirect to the updated URL
                window.location.href = url;
            });

            // Set the selected option in the dropdown based on nr_stat value
            <?php if (isset($_GET['nr_stat'])) { ?>
                $('#nr_stat').val('<?php echo $_GET['nr_stat']; ?>');
            <?php } ?>
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
                <h6 class="m-0 font-weight-bold text-primary">MASIH COBA</h6>
            </div>
            <div class="card-body">
                <?php if (!isset($_GET['act'])) { ?>
                    <select name="" id="nr_stat">
                        <?php //if (!$HCMfull[2] > 0) { 
                        ?>

                        <!-- <option value="">Requirement</option> -->
                        <option value="">Request</option>
                        <option value="Submitted">Requirement Submitted</option>
                        <?php //} else { 
                        ?>
                        <!-- <option value="">Request</option>
                        <option value="Submitted">Requirement Submitted</option> -->
                        <option value="Approval">Assignment Requester</option>
                        <option value="Share">Upload CV</option>
                        <option value="Interview">Interview</option>
                        <option value="Offering">Offering</option>
                        <option value="Join">Join</option>
                        <option value="Selesai">Selesai</option>
                        <?php //} 
                        ?>
                    </select>
                <?php } ?>
                <?php
                if (!isset($_GET['act']) && !isset($_GET['nr_stat'])) {
                    $condition = "status_request = 'Pending Approval'";
                    view_data($tblname, $condition);
                } elseif (!isset($_GET['act']) && $_GET['nr_stat'] == "Submitted") {
                    $condition = "status_request = 'Submitted'";
                    view_data($tblname, $condition);
                } elseif (!isset($_GET['act']) && $_GET['nr_stat'] == "Approval") {
                    $condition = "status_request = 'Submitted'";
                    view_data($tblname, $condition);
                } elseif (!isset($_GET['act']) && $_GET['nr_stat'] == "Share") {
                    $condition = "assign_requirement is not null";
                    view_data($tblname, $condition);
                } elseif (!isset($_GET['act']) && $_GET['nr_stat'] == "Interview") {
                    $condition = "divisi is not null";
                    view_data($tblname4, $condition);
                } elseif (!isset($_GET['act']) && $_GET['nr_stat'] == "Hasil") {
                    $condition = "email_id is not null";
                    view_data($tblname6, $condition);
                } elseif (!isset($_GET['act']) && $_GET['nr_stat'] == "Offering") {
                    $condition = "project_code is not null";
                    view_data($tblname5, $condition);
                } elseif (!isset($_GET['act']) && $_GET['nr_stat'] == "Join") {
                    $condition = "status_manager_hcm = 'Approve' AND status_hcm_gm = 'Approve' AND status_manager_gm_bod = 'Approve' AND status != 'Selesai'";
                    view_data($tblname7, $condition);
                } elseif (!isset($_GET['act']) && $_GET['nr_stat'] == "Selesai") {
                    $condition = "status = 'Selesai'";
                    view_data($tblname7, $condition);
                } elseif ($_GET['act'] == 'add') {
                    form_data($tblname);
                } elseif ($_GET['act'] == 'edit') {
                    form_data($tblname);
                } elseif ($_GET['act'] == 'view') {
                    form_data($tblname);
                } elseif ($_GET['act'] == 'editapproval') {
                    form_data($tblname);
                } elseif ($_GET['act'] == 'editshare') {
                    form_data($tblname);
                } elseif ($_GET['act'] == 'complete') {
                    form_data($tblname);
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

    // } else {
    //     $ALERT->notpermission();
    // }
    // End Body
}
// }
?>

<script>
    // $(document).on('click', '.dt-button', function(e){
    //     if(!$('.select-item').length){
    //         alert('Please select the row from table below');
    //         window.location.href = "";
    //     }
    // });
    // $(document).on('change', '#nr_stat', function() {
    //     var sta = $('#nr_stat').val();
    //     if (sta == "") {
    //         window.location = window.location.pathname + "?mod=hcm_requirement";
    //     } else {
    //         window.location = window.location.pathname + "?mod=hcm_requirement&nr_stat=" + sta;
    //     }
    // });
    // <?php //if (isset($_GET['nr_stat'])) { 
        ?>
    //     $('#nr_stat option[value=<?php //echo $_GET['nr_stat']; 
                                    ?>]').attr('selected', 'selected');
    // <?php //} 
        ?>

    // function pilih() {
    //     alert(document.getElementById("select[]").count);
    // }
</script>