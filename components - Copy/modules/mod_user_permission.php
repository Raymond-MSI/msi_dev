<?php
if((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $author = 'Syamsul Arham';
} else {
?>

<?php //include('components/classes/func_datatable.php'); ?>
    <?php //include('components/classes/func_component.php'); ?>

    <!-- Custom styles for this page -->
    <!-- <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"> -->

    <!-- <link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.datatables.net/select/1.3.3/css/select.dataTables.min.css" rel="stylesheet" type="text/css"> -->

    <!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> -->

    <!-- <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script> -->
    <script>
        $(document).ready(function() {
            // var events = $('#events');
            var table = $('#view_user_access').DataTable( {
                "order": [[ 2, "asc" ]],
                "columnDefs": [
                    {
                        "targets": [ 1,2,3,4,5,8 ],
                        "visible": false,
                    }
                ],
            } );
        } );
</script>
<?php 

    // Function
    if($_SESSION['Microservices_UserLevel'] == "Administrator") {
        function view_data($tblname) {

            // Definisikan tabel yang akan ditampilkan dalam DataTable
            global $DB;
            // $tblname = "";
            $primarykey = "username";
            $condition = "";
            $order = "";

           ?>

            <?php if(isset($_GET['msg'])) { ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <?php echo $_GET['msg']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php } ?>
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Module Permission List</h6>
                    </div>
                    <div class="card-body">
                        <?php
                        view_table($DB, $tblname, $primarykey, $condition, $order);
                        ?>
                    </div>
                </div>
            </div>
        <?php
        }

        function edit_data($tblname) {
            form_data($tblname);
        }

        function add_data($tblname) {
            form_data($tblname);
        }

        function update_data($tblname) {
            form_data($tblname);
        }

        function delete_data($tblname) {
            echo 'Delete Data';
        }

        function form_data($tblname) {
        } 

        // End Function

        // $hostname = 'localhost';
        // $database = 'sa_microservices';
        // $username = 'root';
        // $password = '';
        // include_once( "components/classes/func_databases_v3.php" );
        $DB = new Databases($hostname, $username, $password, $database);
        $tblname = 'view_user_access';

        // Body
        if(!isset($_GET['act'])) {
            view_data($tblname);
        } elseif($_GET['act'] == 'add') {
            add_data($tblname);
        } elseif($_GET['act'] == 'new') {
            new_projects($tblname);
        } elseif($_GET['act'] == 'edit') {
            edit_data($tblname);
        } elseif($_GET['act'] == 'del') {
            delete_data($tblname);
        } elseif($_GET['act'] == 'save') {
            update_data($tblname);
        }
        // End Body


    // Body
    } else { 
        echo "Not permission";
    } 
    // End Body

} 
?>
