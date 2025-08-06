<?php
if((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $author = 'Syamsul Arham';
} else {
    $tblname = "mst_users";
?>
<script>
    $(document).ready(function() {
        var tableUnApproved = $('#<?php echo $tblname; ?>').DataTable( {
            dom: 'Blfrtip',
            select: {
                style: 'single'
            },
            buttons: [
                // {
                //     extend: 'colvis',
                //     text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Columns'><i class='fa fa-columns'></i></span>",
                //     collectionLayout: 'fixed four-column',
                //     enabled: false
                // },
                {
                    text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Add User'><i class='fa fa-plus'></i></span>",
                    action: function () {
                        window.location.href = "index.php?mod=hcm&sub=employee_list&todo=users&version=<?php echo $_GET['version']; ?>";
                    }
                },
                {
                    text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Edit User'><i class='fa fa-pen'></i></span>",
                    action: function () {
                        var rownumber = tableUnApproved.rows({selected: true}).indexes();
                        var user_name = tableUnApproved.cell( rownumber,2 ).data();
                        window.location.href = "index.php?mod=users&act=edit&user_name="+user_name+"&version=<?php echo $_GET['version']; ?>&submit=Submit";
                    }
                },
                // {
                //     text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Change Password'><i class='fa fa-key'></i></span>",
                //     action: function () {
                //         var rownumber = tableUnApproved.rows({selected: true}).indexes();
                //         var user_id = tableUnApproved.cell( rownumber,0 ).data();
                //         window.location.href = "index.php?mod=users&act=change_password&user_id="+user_id;
                //     }
                // },
                // {
                //     text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Add User Level'><i class='fa fa-tools'></i></span>",
                //     action: function () {
                //         window.location.href = "index.php?mod=users&act=level";
                //     },
                //     // enabled: false
                // }
            ],
            "columnDefs": [
                {
                    // project_id
                    "targets": [ 0,3,4,6,7,8,9,13,14,15,16,17,18,19,20,21,22,24,25,26,28 ],
                    "visible": false
                },
                {
                    "targets": [23,27],
                    "className": 'dt-body-center'
                },
                {
                    "targets": [10,11,12],
                    "className": 'dt-body-right'
                }
            ],
            "order": [
                [ 0 , "DES"]
            ]
        } );
    } );
</script>

<?php
    // if(isset($_GET['act']) && $_GET['act']=='change_password') {
    //     $condition = "`user_id`=" . $_GET['user_id'];
    //     $myupdate = sprintf("`user_id`=%s, `password`=%s",
    //         GetSQLValueString($_GET['user_id'], "int"),
    //         GetSQLValueString(MD5("p@ssw0rd123!"), "text")
    //         );
    //     $res = $DB->update_data($tblname, $myupdate, $condition);
    //     $ALERT = new Alert();
    //     $ALERT->change_password_success();
    // }

    $modulename = "Service Budget";
    $userpermission = useraccess($modulename);
    if(USERPERMISSION=="7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION=="dbf36ff3e3827639223983ee8ac47b42" || USERPERMISSION=="726ea0dd998698e8a87f8e344d373533") {

        if(isset($_POST['save']) || isset($_POST['add']) || (isset($_GET['act']) && $_GET['act']=="change_password")) {
            if(isset($_GET['version']) && $_GET['version']==1)
            {
                include("components/modules/users/func_users.php");
            } else
            {
                include("components/modules/users/func_users_v2.php");
            }
        }
        $tblname = "mst_users"; 
        ?>

        <?php 

        // Function
        // if($_SESSION['Microservices_UserLevel'] == "Administrator") {
            function view_data($tblname) {

                // Definisikan tabel yang akan ditampilkan dalam DataTable
                global $DB;
                $primarykey = "user_id";
                $condition = "usertype<>'Administrator' AND block=0";
                $order = "";
            ?>

                <?php 
                if(isset($_GET['err']) && $_GET['err']=="datanotfound") { 
                    global $ALERT;
                    $ALERT->datanotfound(); 
                } 
                ?>
                <div class="col-lg-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">User List</h6>
                        </div>
                        <div class="card-body">
                            <?php
                            view_table($DB, $tblname, $primarykey, $condition, $order, 0, 0);
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

            function edit_module($tblname) {
                ?>
                <div class="col-lg-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">Permission</div>
                        <div class="card-body">
                            <?php 
                            global $DB;
                            $tblname = "mst_users_level";
                            $level = $DB->get_data($tblname);
                            $dlevel = $level[0];
                            $qlevel = $level[1];
                            ?>
                            <div class="col-lg-6">
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">User Level</label>
                                    <div class="col-sm-9">
                                        <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="user_level" <?php echo $permission; ?>>
                                            <?php do { ?>
                                                <option value="<?php echo $dlevel['user_level_id']; ?>"><?php echo $dlevel['user_level']; ?></option>
                                            <?php } while($dlevel=$qlevel->fetch_assoc()); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Module</label>
                                    <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" id="email" name="module" value="<?php if($permission=='edit') { echo $dusers['email']; } ?>" <?php if($permission=='edit') { echo 'readonly'; }; ?>>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
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
                                    if(isset($_GET['version']) && $_GET['version']==1)
                                    {
                                        include("components/modules/users/form_users.php"); 
                                    } else
                                    {
                                        include("components/modules/users/form_users_v2.php"); 
                                    }
                                } else {
                                    // echo "Data not found.";
                                    ?>
                                    <script>
                                    window.location.href='index.php?mod=users&err=datanotfound';
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

            // $DB = new Databases($hostname, $username, $password, $database);
            $tblname = 'mst_users';

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
            } elseif($_GET['act'] == 'editmodule') {
                edit_module($tblname);
            } elseif($_GET['act'] == 'change_password') {
                view_data($tblname);
            } elseif($_GET['act'] == 'level') {
                include("components/modules/users/form_user_level.php");
            }
            // End Body


        // Body
        // } else { 
        //     $ALERT->notpermission();
        // } 
    // End Body
    } else {
        $ALERT->notpermission();
    }
} 
?>
