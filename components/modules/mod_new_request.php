<?php

use Google\Service\CloudSearch\Resource\Query;

if ((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $released = "1689216683";
    $author = 'Syamsul Arham';
} else {

    // $modulename = "REQUEST";
    $modulename = "NEW_REQUEST";
    // $modulename == "trx_request_requirement";
    $userpermission = useraccess($modulename);

    // if (USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION == "dbf36ff3e3827639223983ee8ac47b42" || USERPERMISSION == "5898299487c5b9cdbe7d61809fd20213" || USERPERMISSION == "0162bce636a63c3ae499224203e06ed0") {
    $user = $_SESSION['Microservices_UserEmail'];
    $mdlname = "HCM";
    $DBHCM = get_conn($mdlname);
    $HCMfull = $DBHCM->get_sqlV2("SELECT employee_email FROM sa_view_employees WHERE employee_email = '" . $_SESSION['Microservices_UserEmail'] .
        "'  AND job_structure LIKE '%JW ATN WM HCM Recruitment%' AND resign_date is null");
    // $email = $HCMfull[0]['employee_email'];



?>
    <input type="hidden" id="userEmail" value="<?php echo $HCMfull[0]['employee_email']; ?>">
    <script>
        $(document).ready(function() {
            var table = $('#trx_request_requirement').DataTable({
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
                                window.location.href = "index.php?mod=new_request&act=edit&id=" + id + "&submit=Submit";
                            }
                        },
                    },
                    {
                        text: "<i class='fa fa-plus'></i>",
                        action: function() {
                            window.location.href = "index.php?mod=new_request&act=add";
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

    <script>
        $(document).ready(function() {
            var table = $('#trx_approval').DataTable({
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
                    //         window.location.href = "index.php?mod=approval&act=view&id=" + id + "&submit=Submit";
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
                            window.location.href = "index.php?mod=new_request&act=editapproval&id=" + id + "&submit=Submit";
                        }
                    },
                    // {
                    //     text: "<i class='fa fa-plus'></i>",
                    //     action: function() {
                    //         window.location.href = "index.php?mod=approval&act=add";
                    //     },
                    //     // enabled: false
                    // }
                ],
                "columnDefs": [{
                    "targets": [0],
                    "visible": false,
                }],
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            var table = $('#email').DataTable({
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
                    //         window.location.href = "index.php?mod=approval&act=view&id=" + id + "&submit=Submit";
                    //     },
                    //     enabled: false
                    // },
                    // {
                    //     text: "<i class='fa fa-pen'></i>",
                    //     action: function() {
                    //         var rownumber = table.rows({
                    //             selected: true
                    //         }).indexes();
                    //         var id = table.cell(rownumber, 0).data();
                    //         window.location.href = "index.php?mod=new_request&act=editapproval&id=" + id + "&submit=Submit";
                    //     }
                    // },
                    // {
                    //     text: "<i class='fa fa-plus'></i>",
                    //     action: function() {
                    //         window.location.href = "index.php?mod=approval&act=add";
                    //     },
                    //     // enabled: false
                    // }
                ],
                "columnDefs": [{
                    "targets": [0, 7, 8],
                    "visible": false,
                }],
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            var table = $('#trx_share').DataTable({
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
                    //         window.location.href = "index.php?mod=new_request&act=view&id=" + id + "&submit=Submit";
                    //     },
                    //     enabled: false
                    // },
                    {
                        text: "<i class='fa fa-file'></i>",
                        action: function() {
                            var rownumber = table.rows({
                                selected: true
                            }).indexes();
                            var id = table.cell(rownumber, 0).data();
                            var ProjectCode = table.cell(rownumber, 3).data();
                            var Department = table.cell(rownumber, 1).data();
                            var Posisi = table.cell(rownumber, 2).data()
                            window.location.href = "index.php?mod=new_request&act=editshare&id=" + id + "&ProjectCode=" + ProjectCode + "&Department=" + Department + "&Posisi=" + Posisi + "&submit=Submit";
                        }
                    },
                    // {
                    //     text: "<i class='fa fa-external-link'></i>",
                    //     action: function() {
                    //         var rownumber = table.rows({
                    //             selected: true
                    //         }).indexes();
                    //         var id = table.cell(rownumber, 0).data();
                    //         var ProjectCode = table.cell(rownumber, 3).data();
                    //         window.location.href = "index.php?mod=new_request&act=complete&id=" + id + "&ProjectCode=" + ProjectCode + "&submit=Submit";
                    //     }
                    // },
                    // {
                    //     text: "<i class='fa fa-plus'></i>",
                    //     action: function() {
                    //         window.location.href = "index.php?mod=new_request&act=add";
                    //     },
                    //     // enabled: false
                    // }
                ],
                "columnDefs": [{
                    "targets": [0, 8, 9, 10],
                    "visible": false,
                }],
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            var table = $('#interview').DataTable({
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
                            var interview_id = table.cell(rownumber, 0).data();
                            var project_code = table.cell(rownumber, 4).data();
                            var status_interview = table.cell(rownumber, 7).data();
                            window.location.href = "index.php?mod=new_request&act=editinterview&interview_id=" + interview_id + "&project_code=" + project_code + "&status_interview=" + status_interview + "&submit=Submit";
                        }
                    },
                    // {
                    //     text: "<i class='fa fa-plus'></i>",
                    //     action: function() {
                    //         window.location.href = "index.php?mod=new_request&act=add";
                    //     },
                    //     // enabled: false
                    // }
                ],
                "columnDefs": [{
                    "targets": [0],
                    "visible": false,
                }],
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            var table = $('#trx_offering').DataTable({
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
                    //         var interview_id = table.cell(rownumber, 0).data();
                    //         window.location.href = "index.php?mod=interview&act=view&interview_id=" + interview_id + "&submit=Submit";
                    //     },
                    //     enabled: false
                    // },
                    {
                        text: "<i class='fa fa-pen'></i>",
                        action: function() {
                            var rownumber = table.rows({
                                selected: true
                            }).indexes();
                            var offering_id = table.cell(rownumber, 0).data();
                            var project_code = table.cell(rownumber, 4).data();
                            // var status_interview = table.cell(rownumber, 7).data();
                            window.location.href = "index.php?mod=new_request&act=editoffering&id_offering=" + offering_id + "&project_code=" + project_code + "&submit=Submit";
                        }
                    },
                    // {
                    //     text: "<i class='fa fa-plus'></i>",
                    //     action: function() {
                    //         window.location.href = "index.php?mod=new_request&act=add";
                    //     },
                    //     // enabled: false
                    // }
                ],
                "columnDefs": [{
                    "targets": [0],
                    "visible": false,
                }],
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            var table = $('#trx_join').DataTable({
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
                    //         var interview_id = table.cell(rownumber, 0).data();
                    //         window.location.href = "index.php?mod=interview&act=view&interview_id=" + interview_id + "&submit=Submit";
                    //     },
                    //     enabled: false
                    // },
                    {
                        text: "<i class='fa fa-pen'></i>",
                        action: function() {
                            var rownumber = table.rows({
                                selected: true
                            }).indexes();
                            var join_id = table.cell(rownumber, 0).data();
                            var project_code = table.cell(rownumber, 4).data();
                            // var status_interview = table.cell(rownumber, 7).data();
                            window.location.href = "index.php?mod=new_request&act=editjoin&id_join=" + join_id + "&project_code=" + project_code + "&submit=Submit";
                        }
                    },
                    // {
                    //     text: "<i class='fa fa-plus'></i>",
                    //     action: function() {
                    //         window.location.href = "index.php?mod=new_request&act=add";
                    //     },
                    //     // enabled: false
                    // }
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
    // if ($_SESSION['Microservices_UserLevel'] == "Super Admin") {
    function view_data($tblname, $condition)
    {
        // Definisikan tabel yang akan ditampilkan dalam DataTable
        global $DBREC;
        $primarykey = "id";
        // $condition = "";
        $order = "";
        if (isset($_GET['err']) && $_GET['err'] == "datanotfound") {
            global $ALERT;
            $ALERT->datanotfound();
        }
        view_table($DBREC, $tblname, $primarykey, $condition, $order);
    }
    function form_data($tblname)
    {
        include("components/modules/new_request/form_request.php");
    }


    // End Function

    // percobaan version 4
    // $tblname = 'cfg_web';
    // $condition = 'config_key="MODULE_NEW_REQUEST"';
    // $setupDB = $DB->get_data($tblname, $condition);
    // $dsetupDB = $setupDB[0];
    // if ($setupDB[2] > 0) {
    //     $json_conn = json_decode($dsetupDB['params'], true);
    //     if ($json_conn['version']['connection'] == "4") {
    //         $hostname = $json_conn['connection']['hostname'];
    //         $username = $json_conn['connection']['username'];
    //         $password = $json_conn['connection']['password'];
    //         $database = $json_conn['connection']['database'];
    //         $DBREC = new Databases($hostname, $username, $password, $database);
    $DBREC = get_conn('new_request');
    $tblname = 'trx_request_requirement';
    $tblname2 = 'trx_approval';
    $tblname3 = 'trx_share';
    $tblname4 = 'interview';
    $tblname5 = 'trx_offering';
    $tblname6 = 'email';
    $tblname7 = 'trx_join';


    include("components/modules/new_request/func_request.php");

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
                if (!isset($_GET['act']) && !isset($_GET['nr_stat'])) {
                    $condition = "status_request = 'Pending Approval'";
                    view_data($tblname, $condition);
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

    // } else {
    //     $ALERT->notpermission();
    // }
    // End Body
}
// }
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
            window.location = window.location.pathname + "?mod=new_request";
        } else {
            window.location = window.location.pathname + "?mod=new_request&nr_stat=" + sta;
        }
    });
    <?php if (isset($_GET['nr_stat'])) { ?>
        $('#nr_stat option[value=<?php echo $_GET['nr_stat']; ?>]').attr('selected', 'selected');
    <?php } ?>

    // function pilih() {
    //     alert(document.getElementById("select[]").count);
    // }
</script>