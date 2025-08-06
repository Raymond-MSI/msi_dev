<?php
function module($title, $module_name, $dusers)
{
    if (isset($dusers)) {
        $permission = json_decode($dusers['permission'], true);
    } else {
        $permission = "{}";
    }
?>
    <div class="row">
        <div class="col-sm-2 border border-light">
            <input class="form-control border-0 bg-transparent form-control-sm" type="text" id="<?php echo $module_name; ?>_module" name="<?php echo $module_name; ?>_module" value="<?php echo $title; ?>" readonly>
        </div>
        <div class="col-sm-1 text-center border border-light">
            <div class="form-check">
                <input class="form-check-input" type="radio" id="<?php echo $module_name; ?>_read" name="<?php echo $module_name; ?>" value="Read" <?php echo (isset($permission[$module_name]) && $permission[$module_name]['user_level'] == "Read") ? "Checked" : ""; ?>>
            </div>
        </div>
        <!-- <div class="col-sm-1 text-center border border-light">
                <div class="form-check">
                    <input class="form-check-input" type="radio" id="<?php //echo $module_name; 
                                                                        ?>_edit" name="<?php //echo $module_name; 
                                                                                        ?>" value="Edit" <?php //echo (isset($permission[$module_name]) && $permission[$module_name]=="Edit") ? "Checked" : ""; 
                                                                                                                                    ?>>
                </div>
            </div> -->
        <div class="col-sm-1 text-center border border-light">
            <div class="form-check">
                <input class="form-check-input" type="radio" id="<?php echo $module_name; ?>_entry" name="<?php echo $module_name; ?>" value="Entry" <?php echo (isset($permission[$module_name]) && $permission[$module_name]['user_level'] == "Entry") ? "Checked" : ""; ?>>
            </div>
        </div>
        <div class="col-sm-1 text-center border border-light">
            <div class="form-check">
                <input class="form-check-input" type="radio" id="<?php echo $module_name; ?>_approval" name="<?php echo $module_name; ?>" value="Approval" <?php echo (isset($permission[$module_name]) && $permission[$module_name]['user_level'] == "Approval") ? "Checked" : ""; ?>>
            </div>
        </div>
        <div class="col-sm-1 text-center border border-light">
            <div class="form-check">
                <input class="form-check-input" type="radio" id="<?php echo $module_name; ?>_pmoimp" name="<?php echo $module_name; ?>" value="PMO Implementation" <?php echo (isset($permission[$module_name]) && $permission[$module_name]['user_level'] == "PMO Implementation") ? "Checked" : ""; ?>>
            </div>
        </div>
        <div class="col-sm-1 text-center border border-light">
            <div class="form-check">
                <input class="form-check-input" type="radio" id="<?php echo $module_name; ?>_pmomnt" name="<?php echo $module_name; ?>" value="PMO Maintenance" <?php echo (isset($permission[$module_name]) && $permission[$module_name]['user_level'] == "PMO Maintenance") ? "Checked" : ""; ?>>
            </div>
        </div>
        <div class="col-sm-1 text-center border border-light">
            <div class="form-check">
                <input class="form-check-input" type="radio" id="<?php echo $module_name; ?>_disabled" name="<?php echo $module_name; ?>" value="Disabled" <?php echo (!isset($permission[$module_name])) ? "Checked" : ""; ?>>
            </div>
        </div>
    </div>
<?php
}

$userExisting = false;
if (isset($_GET['act']) && $_GET['act'] == "add") {
    $mdlname = "HCM";
    $DBHCM = get_conn($mdlname);
    $tblname = "view_employees";
    $condition = "employee_email='" . $_GET['employee_email'] . "'";
    $employees = $DBHCM->get_data($tblname, $condition);
    $demployee = $employees[0];
    $employee_name = $demployee['employee_name'];
    $usernameexp = explode("@", $demployee['employee_email']);
    $user_name = $usernameexp[0];
    $user_email = $demployee['employee_email'];
    $user_departement = $demployee['organization_name'];
    $userid = "";

    $tblname = "mst_users";
    $condition = "email = '" . $demployee['employee_email'] . "'";
    $testuser = $DB->get_data($tblname, $condition);
    if ($testuser[2] > 0) {
        $userExisting = true;
        $userid = $testuser[0]['user_id'];
    }
    $dusers = $testuser[0];
} else {
    $employee_name = $dusers['name'];
    $user_name = $dusers['username'];
    $user_email = $dusers['email'];
    $user_departement = $dusers['organization_name'];
    $userid = $dusers['user_id'];
}
?>

<form method="post" action="index.php?mod=users&todo=users&version=<?php echo $_GET['version']; ?>">
    <div class="row">
        <label for="inputCID3" class="col-sm-12 alert alert-secondary"><b>User Information</b></label>
        <div class="col-lg-6">
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Name</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="name" name="name" value="<?php if ($permission == 'edit' || $_GET['act'] == 'add') {
                                                                                                                echo $employee_name;
                                                                                                            } ?>" <?php if ($permission == 'edit') {
                                                                                                                        echo 'readonly';
                                                                                                                    }; ?>>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">User Name</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="username" name="username" value="<?php if ($permission == 'edit' || $_GET['act'] == 'add') {
                                                                                                                        echo $user_name;
                                                                                                                    } ?>" <?php if ($permission == 'edit') {
                                                                                                                                echo 'readonly';
                                                                                                                            }; ?>>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Email</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="email" name="email" value="<?php if ($permission == 'edit' || $_GET['act'] == 'add') {
                                                                                                                echo $user_email;
                                                                                                            } ?>" <?php if ($permission == 'edit') {
                                                                                                                        echo 'readonly';
                                                                                                                    }; ?>>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Departement</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="organization_name" name="organization_name" value="<?php if ($permission == 'edit' || $_GET['act'] == 'add') {
                                                                                                                                        echo $user_departement;
                                                                                                                                    } ?>" <?php if ($permission == 'edit') {
                                                                                                                                                echo 'readonly';
                                                                                                                                            }; ?>>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Active</label>
                <div class="col-sm-1">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" name="user_status" id="user_status" <?php
                                                                                                                        if ($_GET['act'] != 'add') {
                                                                                                                            echo ($users[0]['user_status'] == 1 ?
                                                                                                                                'checked' :
                                                                                                                                'unchecked');
                                                                                                                        } ?>>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">User Level</label>
                <div class="col-sm-9">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="usertype" id="usertype1" value="Users" autocomplete="off" <?php echo (isset($_GET['act']) && $_GET['act'] == "add" || $users[0]['usertype'] == "Users") ? "checked" : ""; ?> onchange="showUserLevel(1);">
                        <label class="form-check-label" for="usertype1">User</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="usertype" id="usertype2" autocomplete="off" value="User Admin" <?php echo (isset($_GET['act']) && $_GET['act'] != "add" && $users[0]['usertype'] == "User Admin") ? "checked" : ""; ?> onchange="showUserLevel(0);">
                        <label class="form-check-label" for="usertype2">User Admin</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="usertype" id="usertype3" autocomplete="off" value="Super Admin" <?php echo (isset($_GET['act']) && $_GET['act'] != "add" && $users[0]['usertype'] == "Super Admin") ? "checked" : ""; ?> onchange="showUserLevel(0);">
                        <label class="form-check-label" for="usertype3">Super Admin</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="moduleLevel">
        <label for="inputCID3" class="col-sm-12 alert alert-secondary"><b>User Permission</b></label>
        <div class="col-lg-12">
            <div class="row">
                <div class="col-sm-2 border bg-light border border">
                    <label for="inputCID3" class="col-form-label col-form-label-sm fw-bold">Module</label>
                </div>
                <div class="col-sm-1 border bg-light border border text-center">
                    <label for="inputCID3" class="col-form-label col-form-label-sm fw-bold">Read</label>
                </div>
                <div class="col-sm-1 border bg-light border border text-center">
                    <!-- <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm text-center border bg-light">Edit</label> -->
                    <label for="inputCID3" class="col-form-label col-form-label-sm fw-bold">Entry</label>
                </div>
                <div class="col-sm-1 border bg-light border border text-center">
                    <label for="inputCID3" class="col-form-label col-form-label-sm fw-bold">Approval</label>
                </div>
                <div class="col-sm-1 border bg-light border border text-center">
                    <label for="inputCID3" class="col-form-label col-form-label-sm fw-bold">PMO Implementation</label>
                </div>
                <div class="col-sm-1 border bg-light border border text-center">
                    <label for="inputCID3" class="col-form-label col-form-label-sm fw-bold">PMO Maintenance</label>
                </div>
                <div class="col-sm-1 border bg-light border border text-center">
                    <label for="inputCID3" class="col-form-label col-form-label-sm fw-bold">Disabled</label>
                </div>
            </div>
            <?php
            $tblname = "cfg_web";
            $condition = "parent=10 AND (params LIKE '%module: visibility=1%' OR params LIKE '%\"visible\": \"true\"%')";
            $order = "title";
            $modules = $DB->get_data($tblname, $condition, $order);
            if ($modules[2] > 0) {
                $i = 0;
                do {
                    module($modules[0]['title'], $modules[0]['config_key'], $dusers);
                    $i++;
                } while ($modules[0] = $modules[1]->fetch_assoc());
            }
            ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-lg-12">
            <input type="hidden" name="user_id" value="<?php if ($permission == 'edit') {
                                                            echo $userid;
                                                        } ?>">
            <input type="submit" class="btn btn-primary" name="<?php
                                                                if (isset($_GET['act']) && $_GET['act'] == 'edit' || $userExisting) {
                                                                    echo 'save';
                                                                } elseif (isset($_GET['act']) && $_GET['act'] == 'add') {
                                                                    echo 'add';
                                                                }
                                                                ?>" value="Save">
            <input type="submit" class="btn btn-secondary" name="cancel" value="Cancel">
        </div>
    </div>
</form>
<?php if ($userExisting) { ?>
    <script>
        alert("User sudah didaftarkan");
    </script>
<?php } ?>

<script>
    function showUserLevel(togle) {
        if (togle == 1) {
            document.getElementById("moduleLevel").style.display = "";
        } else {
            document.getElementById("moduleLevel").style.display = "none";
        }
    }
</script>