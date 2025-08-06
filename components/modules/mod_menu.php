<?php
if ((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $author = 'Syamsul Arham';
} else {
    $tblname = "view_menus";
?>
    <script>
        $(document).ready(function() {
            var table = $('#<?php echo $tblname; ?>').DataTable({
                dom: 'Blfrtip',
                order: [
                    [1, 'asc'],
                    [6, 'asc']
                ],
                // rowGroup: {
                // dataSrc: 1
                // },
                select: {
                    style: 'single'
                },
                "columnDefs": [{
                    "targets": [0],
                    "visible": false,
                }],
                buttons: [
                    // {
                    //     extend: 'colvis',
                    //     text: "<i class='fa fa-columns'></i>",
                    //     collectionLayout: 'fixed four-column'
                    // },
                    {
                        text: "<i class='fa fa-pen'></i>",
                        action: function() {
                            var rownumber = table.rows({
                                selected: true
                            }).indexes();
                            var menu_id = table.cell(rownumber, 0).data();
                            window.location.href = "index.php?mod=menu&act=edit&id=" + menu_id + "&submit=Submit";
                        }
                    },
                    {
                        text: "<i class='fa fa-plus'></i>",
                        action: function() {
                            window.location.href = "index.php?mod=menu&act=add";
                        }
                    }
                ],
            });
        });
    </script>

    <?php

    // Function
    // if($_SESSION['Microservices_UserLevel'] == "Administrator") {
    $modulename = "Service Budget";
    $userpermission = useraccess($modulename);
    if (USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION == "dbf36ff3e3827639223983ee8ac47b42" || USERPERMISSION == "726ea0dd998698e8a87f8e344d373533") {
        function view_data($tblname)
        {

            // Definisikan tabel yang akan ditampilkan dalam DataTable
            global $DBMENU;
            $primarykey = "id";
            $condition = "id>1";
            $order = "";
    ?>

            <?php if (isset($_GET['msg'])) { ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <?php echo $_GET['msg']; ?>aa
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php } ?>
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Menu List</h6>
                        <?php spinner(); ?>
                    </div>
                    <div class="card-body">
                        <?php
                        view_table($DBMENU, $tblname, $primarykey, $condition, $order);
                        ?>
                    </div>
                </div>
            </div>
        <?php
        }

        function form_data($tblname)
        {
            global $DBMENU;

            //Defisikan tabel yang akan diedit
            if (isset($_GET['act']) && ($_GET['act']) == 'edit') {
                if (isset($_GET['id']) && ($_GET['id'] == 'undefined')) {
                    $id = 0;
                    $act = NULL;
                } else {
                    $id = $_GET['id'];
                    $tblname = "cfg_menus";
                    $condition = "id=" . $id;
                    $menu = $DBMENU->get_data($tblname, $condition);
                    $dmenu = $menu[0];
                    $tmenu = $menu[2];
                    $act = 'edit';
                }
            } elseif (isset($_GET['act']) && ($_GET['act'] == 'add')) {
                $act = 'add';
            }

            // end
        ?>
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">Editor</div>
                    <div class="card-body">
                        <?php
                        if ($act == 'edit' || $act == 'add') {
                            // bila datanya ada
                            include("components/modules/menu/form_menu.php");
                        } else {
                            // echo "Data not found.";
                        ?>
                            <script>
                                window.location.href = 'index.php?mod=menu&msg=Data not found';
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

        // $hostname = 'localhost';
        $database = 'sa_microservices';
        // $username = 'root';
        // $password = '';
        // include_once( "components/classes/func_databases_v3.php" );
        $DBMENU = new Databases($hostname, $username, $password, $database);
        include('components/modules/menu/func_menu.php');
        $tblname = 'view_menus';

        // Body
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
        // End Body


        // Body
    } else {
        echo "Not permission";
    }
    // End Body
    ?>

<?php } ?>