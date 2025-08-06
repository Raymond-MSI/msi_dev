<?php
if ((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $released = "1704249910";
    $author = 'Syamsul Arham';
} else {

    $modulename = "trx_mom";
    $userpermission = useraccess($modulename);
    // if(USERPERMISSION=="7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION=="dbf36ff3e3827639223983ee8ac47b42") {
?>
    <script>
        $(document).ready(function() {
            var table = $('#trx_mom').DataTable({
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
                            window.location.href = "index.php?mod=trx_mom&act=add";
                        },
                        // enabled: false
                    },
                    {
                        text: "<i class='fa fa-pen'></i>",
                        action: function() {
                            var rownumber = table.rows({
                                selected: true
                            }).indexes();
                            var mom_id = table.cell(rownumber, 0).data();
                            window.location.href = "index.php?mod=trx_mom&act=edit&mom_id=" + mom_id + "&submit=Submit";
                        }
                    },
                    {
                        text: "<i class='fa fa-eye'></i>",
                        action: function() {
                            var rownumber = table.rows({
                                selected: true
                            }).indexes();
                            var mom_id = table.cell(rownumber, 0).data();
                            window.location.href = "index.php?mod=trx_mom&act=view&mom_id=" + mom_id + "&submit=Submit";
                        },
                        enabled: false
                    },
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
    if ($_SESSION['Microservices_UserLevel'] == "Super Admin") {
        function view_data($tblname, $condition)
        {
            // Definisikan tabel yang akan ditampilkan dalam DataTable
            global $DBMOM;
            $primarykey = "mom_id";
            // $condition = "";
            $order = "";
            if (isset($_GET['err']) && $_GET['err'] == "datanotfound") {
                global $ALERT;
                $ALERT->datanotfound();
            }
            view_table($DBMOM, $tblname, $primarykey, $condition, $order);
        }
        function form_data($tblname)
        {
            include("components/modules/trx_mom/form_trx_mom.php");
        }

        // End Function

        // $database = 'sa_mom';
        // include("components/modules/trx_mom/connection.php");
        // $DB = new Databases($hostname, $username, $userpassword, $database);
        $DBMOM = get_conn('mom');
        $tblname = 'trx_mom';

        include("components/modules/trx_mom/func_trx_mom.php");

        // Body
    ?>
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Minute Of Meeting</h6>
                </div>
                <div class="card-body">
                    <?php if (!isset($_GET['act'])) { ?>
                        <select name="" id="mom">
                            <option value="">coba</option>
                            <option value="pas">pas</option>
                            <option value="aduh">aduh</option>
                        </select>
                    <?php } ?>
                    <?php
                    if (!isset($_GET['act']) && !isset($_GET['mom'])) {
                        $condition = "module_name is null";
                        view_data($tblname, $condition);
                    } elseif (!isset($_GET['act']) && $_GET['mom'] == "pas") {
                        $condition = "status_mom = 'On Progress'";
                        view_data($tblname, $condition);
                    } elseif (!isset($_GET['act']) && $_GET['mom'] == "aduh") {
                        $condition = "status_mom = 'Done'";
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
    //    } 
}
?>

<script>
    $(document).on('change', '#mom', function() {
        var sta = $('#mom').val();
        if (sta == "") {
            window.location = window.location.pathname + "?mod=trx_mom";
        } else {
            window.location = window.location.pathname + "?mod=trx_mom&mom=" + sta;
        }
    });
    <?php if (isset($_GET['mom'])) { ?>
        $('#mom option[value=<?php echo $_GET['mom']; ?>]').attr('selected', 'selected');
    <?php } ?>
</script>