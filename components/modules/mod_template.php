<?php
if((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $author = 'Syamsul Arham';
} else {
?>
    <?php include('components/classes/func_datatable.php'); ?>
    <?php include('components/classes/func_component.php'); ?>

    <!-- Custom styles for this page -->
    <!-- <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"> -->
    <link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.datatables.net/select/1.3.3/css/select.dataTables.min.css" rel="stylesheet" type="text/css">

    <!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> -->
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>

    <!-- Edit Paramater -->
    <?php include("components/modules/users/func_users.php"); ?>
    <?php $tblname = "mst_users"; ?>
    <!-- End Edit Parameter -->

    <!-- Edit DataTable -->
    <script>
        $(document).ready(function() {
            var tableUnApproved = $('#<?php echo $tblname; ?>').DataTable( {
                dom: 'Blfrtip',
                select: {
                    style: 'single'
                },
                buttons: [
                    {
                        extend: 'colvis',
                        text: "<i class='fa fa-columns'></i>",
                        collectionLayout: 'fixed four-column'
                    },
                    {
                        text: "<i class='fa fa-pen'></i>",
                        action: function () {
                            var rownumber = tableUnApproved.rows({selected: true}).indexes();
                            var user_name = tableUnApproved.cell( rownumber,2 ).data();
                            window.location.href = "index.php?mod=users&act=edit&user_name="+user_name+"&submit=Submit";
                        }
                    },
                    {
                        text: "<i class='fa fa-plus'></i>",
                        action: function () {
                            window.location.href = "index.php?mod=users&act=add";
                        }
                    }
                ],
                "columnDefs": [
                    {
                        // project_id
                        "targets": [ 0,4,6,7,8,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26 ],
                        "visible": false
                    }
                ],
                "order": [
                    [ 0 , "DES"]
                ]
            } );
        } );
    </script>
    <!-- End Edit DataTabel -->

<?php 

    // Function
    if($_SESSION['Microservices_UserLevel'] == "Administrator") {
        function view_data($tblname) {

            // Definisikan tabel yang akan ditampilkan dalam DataTable
            global $DB;
            // Edit table information
            $primarykey = "user_id";
            $condition = "usertype<>'Administrator'";
            $order = "";
            // End Edit tabel information
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
                    <h6 class="m-0 font-weight-bold text-primary">User List</h6>
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
            $permission = "edit";
            form_data($tblname, $permission);
        }

        function add_data($tblname) {
            $permission = "add";
            form_data($tblname);
        }

        function update_data($tblname) {
            form_data($tblname);
        }

        function delete_data($tblname) {
            echo 'Delete Data';
        }

        function form_data($tblname, $permission="") {
            global $DB;
            $querystr = $_SERVER['REQUEST_URI'];

            //Defisikan tabel yang akan diedit
            if(isset($_GET['act']) && $_GET['act']=='edit') {
                $condition = "username='" . $_GET['user_name'] . "'";
                $users = $DB->get_data($tblname, $condition);
                $dusers = $users[0];
                $qusers = $users[1];
                $tusers = $users[2];
            } elseif($_GET['act']=='add') {
                $tusers = 1;
            }
            // end
            ?>
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">Users</div>
                    <div class="card-body">
                        <?php 
                            if($tusers > 0) {
                                // bila datanya ada
                                include("components/modules/users/form_users.php"); 
                            } else {
                                // echo "Data not found.";
                                ?>
                                <script>
                                window.location.href='index.php?mod=users&msg=Data not found';
                                </script>

                                <?php
                            }        
                        ?>
                    </div>
                </div>
            </div>
        <?php
        } 

        // End Function

        $hostname = 'localhost';
        $database = 'sa_microservices';
        $username = 'root';
        $password = '';
        include_once( "components/classes/func_databases_v3.php" );
        // include('components/modules/service_budget/form_view_table.php');
        $DB = new Databases($hostname, $username, $password, $database);
        $tblname = 'mst_users';

        // include("components/modules/service_budget/func_service_budget.php");

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
