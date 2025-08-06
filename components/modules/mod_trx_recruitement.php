<?php
if ((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $released = "1693885189";
    $author = 'Syamsul Arham';
} else {

    $modulename = "trx_recruitement";
    $userpermission = useraccess($modulename);
    if (USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION == "dbf36ff3e3827639223983ee8ac47b42") {
        $user = $_SESSION['Microservices_UserEmail'];
?>
        <?php
        $mdlname = "trx_recruitement";
        $DBREC = get_conn($mdlname);
        $query = $DBREC->get_sqlV2("select * from sa_trx_recruitement where status_request = 'Approved'");
        ?>
        <script>
            $(document).ready(function() {
                var table = $('#trx_recruitement').DataTable({
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
                                var gm = table.cell(rownumber, 24).data();
                                var gm_hcm = table.cell(rownumber, 26).data();
                                var created_by = table.cell(rownumber, 23).data();
                                if (id == null) {
                                    alert("Silahkan Pilih Project");

                                } else if (id != null && gm == "<?php echo $user ?>" || id != null && gm_hcm == "<?php echo $user ?>" || id != null && created_by == "<?php echo $user ?>") {
                                    window.location.href = "index.php?mod=trx_recruitement&act=edit&id=" + id + "&submit=Submit";
                                }
                            },
                        },
                        {
                            text: "<i class='fa fa-plus'></i>",
                            action: function() {
                                window.location.href = "index.php?mod=trx_recruitement&act=add";
                            },
                            // enabled: false
                        }
                    ],
                    "columnDefs": [{
                        "targets": [0, 6, 7, 8, 12, 13, 20, 21, 22],
                        "visible": false,
                    }],
                });
            });
        </script>


        <?php

        // Function
        // if ($_SESSION['Microservices_UserLevel'] == "Administrator") {
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
            include("components/modules/trx_recruitement/form_trx_recruitement.php");
        }

        // End Function

        // $database = 'sa_md_hcm';
        // include("components/modules/trx_recruitement/connection.php");
        $tblname = 'cfg_web';
        $condition = 'config_key="MODULE_TRX_RECRUITEMENT"';
        $setupDB = $DB->get_data($tblname, $condition);
        $dsetupDB = $setupDB[0];
        if ($setupDB[2] > 0) {
            $params = get_params($dsetupDB['params']);
            $hostname = $params['database']['hostname'];
            $username = $params['database']['username'];
            $userpassword = $params['database']['userpassword'];
            $database = $params['database']['database_name'];
            $DBREC = new Databases($hostname, $username, $userpassword, $database);
            $tblname = 'trx_recruitement';

            include("components/modules/trx_recruitement/func_trx_recruitement.php");

            // Body
        ?>
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Request Recruitment</h6>
                    </div>
                    <div class="card-body">
                        <?php if (!isset($_GET['act'])) { ?>
                            <select name="" id="nr_stat">
                                <option value="">Requirement</option>
                                <option value="Pending">Pending</option>
                                <!-- <option value="Disapprove">Disapprove</option> -->
                                <option value="Approve">Approve</option>
                                <option value="Submitted">Submitted</option>
                            </select>
                        <?php } ?>
                        <?php
                        if (!isset($_GET['act']) && !isset($_GET['nr_stat'])) {
                            $condition = "status_gm='malik'";
                            view_data($tblname, $condition);
                        } elseif (!isset($_GET['act']) && $_GET['nr_stat'] == "Pending") {
                            $condition = "status_gm_hcm='Pending'";
                            view_data($tblname, $condition);
                            // }elseif (!isset($_GET['act']) && $_GET['nr_stat']=="Disapprove") {
                            // $condition = "status_gm='Disapprove' OR status_gm_hcm='Disapprove'";
                            // view_data($tblname, $condition);
                        } elseif (!isset($_GET['act']) && $_GET['nr_stat'] == "Approve") {
                            $condition = "status_gm = 'Approve' AND status_gm_hcm = 'Approve' AND status_request = 'Active'";
                            view_data($tblname, $condition);
                        } elseif (!isset($_GET['act']) && $_GET['nr_stat'] == "Submitted") {
                            $condition = "status_request = 'Submitted'";
                            view_data($tblname, $condition);
                        } elseif ($_GET['act'] == 'add') {
                            form_data($tblname);
                            // } elseif ($_GET['act'] == 'new') {
                            //     new_projects($tblname);
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
            window.location = window.location.pathname + "?mod=trx_recruitement";
        } else {
            window.location = window.location.pathname + "?mod=trx_recruitement&nr_stat=" + sta;
        }
    });
    <?php if (isset($_GET['nr_stat'])) { ?>
        $('#nr_stat option[value=<?php echo $_GET['nr_stat']; ?>]').attr('selected', 'selected');
    <?php } ?>
</script>