<?php
if((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $author = 'Syamsul Arham';
} else {
?>
<!-- <?php //include('components/classes/func_datatable.php'); ?>
<link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"> -->

<!-- <link href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css">
<link href="https://cdn.datatables.net/select/1.3.3/css/select.dataTables.min.css" rel="stylesheet" type="text/css"> -->

<!-- <link href="https://cdn.datatables.net/datetime/1.1.0/css/dataTables.dateTime.min.css" rel="stylesheet" type="text/css"> -->

<!-- <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script> -->

<!-- <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>

<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script> -->
<!-- <script src="https://cdn.datatables.net/datetime/1.1.0/js/dataTables.dateTime.min.js"></script> -->

<?php //include('components/modules/service_budget/form_view_table.php'); ?>
<?php //include('components/classes/func_component.php'); ?>
<?php 
    // $hostname = 'localhost';
    // $database = 'sa_md_products';
    // $username = 'root';
    // $password = '';
    // include_once( "components/classes/func_databases_v3.php" );

    $tblname = 'cfg_web';
    $condition = 'config_key="MODULE_LIFECYCLE_PRODUCTS"';
    $setupDB = $DB->get_data($tblname, $condition);
    $dsetupDB = $setupDB[0];
    if($setupDB[2]>0) {
        $params = get_params($dsetupDB['params']);
        $hostname = $params['database']['hostname'];
        $username = $params['database']['username'];
        $userpassword = $params['database']['userpassword'];
        $database = $params['database']['database_name'];

        // $hostname = "localhost";
        // $username = "root";
        // $userpassword="";

        $DPLIST = new Databases($hostname, $username, $userpassword, $database);
        $tblname = "md_products";
        $primarykey = "part_number_id";
        $condition = "";
        $order = "part_number ASC";

        ?>
        <script> 
            $(document).ready(function() {
                var table = $('#<?php echo $tblname; ?>').DataTable( {
                    "scrollX": true,
                    scrollY:        '50vh',
                    paging:         false,
                    "columnDefs": [
                        {
                            "targets": [ 0 ],
                            "visible": false
                        }
                    ],
                    "language": {
                        "decimal": ".",
                        "thousands": ","
                    }
                });
            });
        </script>

        <div class="col-lg-12">

                <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Product List</h6>
                    <?php spinner(); ?>
                </div>
                <div class="card-body">
                <?php 
                        view_table($DPLIST, $tblname, $primarykey, $condition, $order, $firstRow = 0, $totalRow = 10000)
                    ?>
                </div>
            </div>
        </div>
 
        <?php 
    } else {
        echo "Aplikasi belum disetup";            
    }


} ?>