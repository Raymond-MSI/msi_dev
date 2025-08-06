<?php
$userExisting = false;
if(isset($_GET['act']) && $_GET['act']=="add") {
    $mdlname = "HCM";
    $DBHCM = get_conn($mdlname);
    $tblname = "view_employees";
    $condition = "employee_email='" . $_GET['employee_email'] . "'";
    $employees = $DBHCM->get_data($tblname, $condition);
    $demployee = $employees[0];
    $employee_name = $demployee['employee_name'];
    $usernameexp = explode("@",$demployee['employee_email']);
    $user_name = $usernameexp[0];
    $user_email = $demployee['employee_email'];
    $user_departement = $demployee['organization_name'];
    $userid = "";

    $tblname = "mst_users";
    $condition = "email = '" . $demployee['employee_email'] ."'";
    $testuser = $DB->get_data($tblname, $condition);
    if($testuser[2]>0) {
        $userExisting = true;
        $userid = $testuser[0]['user_id'];
    }
} else {
    $employee_name = $dusers['name'];
    $user_name = $dusers['username'];
    $user_email = $dusers['email'];
    $user_departement = $dusers['organization_name'];
    $userid = $dusers['user_id'];
}
?>

<form method="post" action="index.php?mod=users">
    <div class="row">
        <div class="col-lg-6">
            <label for="inputCID3" class="col-sm-12 alert alert-secondary"><b>User Information</b></label>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Name</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="name" name="name" value="<?php if($permission=='edit' || $_GET['act']=='add') { echo $employee_name; } ?>" <?php if($permission=='edit') { echo 'readonly'; }; ?>>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">User Name</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="username" name="username" value="<?php if($permission=='edit' || $_GET['act']=='add') { echo $user_name; } ?>" <?php if($permission=='edit') { echo 'readonly'; }; ?>>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Email</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="email" name="email" value="<?php if($permission=='edit' || $_GET['act']=='add') { echo $user_email; } ?>" <?php if($permission=='edit') { echo 'readonly'; }; ?>>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Departement</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="organization_name" name="organization_name" value="<?php if($permission=='edit' || $_GET['act']=='add') { echo $user_departement; } ?>" <?php if($permission=='edit') { echo 'readonly'; }; ?>>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Active</label>
                <div class="col-sm-9">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" name="user_status" id="user_status" 
                        <?php 
                        if($_GET['act']!='add') { 
                            echo ($users[0]['user_status']==1 ? 
                            'checked' : 
                            'unchecked'); } ?>>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <label for="inputCID3" class="col-sm-12 alert alert-secondary"><b>User Permission</b></label>
            <?php
                $tblname = "cfg_web";
                $condition = "parent=10 AND params LIKE '%log_database: visibility=1%'";
                $order = "title ASC";
                $modules = $DB->get_data($tblname, $condition, $order);
                $dmodules = $modules[0];
                $qmodules = $modules[1];
            ?>

            <table class="table" width="100%">
                <thead>
                    <tr><th>Module</th><th>User Level</th></tr>
                </thead>
                <tbody>
                    <?php $i=0; ?>
                    <?php do { ?>
                        <tr>
                        <td>
                            <?php echo $dmodules['title']; ?>
                            <input type="hidden" name="<?php echo $dmodules['title']; ?>" value="<?php echo $dmodules['id']; ?>">
                        </td>
                        <td>
                            <?php
                            $tblname = "view_user_access";
                            if($permission=='edit') {
                                $username = $dusers['username'];
                            } else {
                                $username = "";
                            }
                            $condition = "title='" . $dmodules['title'] . "' AND `username`='" . $username . "'";
                            $access = $DB->get_data($tblname, $condition);
                            $daccess = $access[0];
                            $taccess = $access[2];

                            $tblname = "mst_users_level";
                            $level = $DB->get_data($tblname);
                            $dlevel = $level[0];
                            $qlevel = $level[1];
                            ?>
                            <select class="select-control" name="<?php echo $dmodules['config_value']; ?>_id">
                                <option>--Select Level--</option>
                                <?php do { ?>
                                    <option value="<?php echo $dlevel['user_level_id']; ?>" 
                                    <?php if($taccess>0) { if($dlevel['user_level_id'] == $daccess['user_level_id']) { 
                                        echo "selected"; }} ?>>
                                        <?php echo $dlevel['user_level']; ?></option>
                                <?php } while($dlevel=$qlevel->fetch_assoc()) ?>
                            </select>
                        </td>
                        </tr>
                        <?php $i++; ?>
                    <?php } while($dmodules=$qmodules->fetch_assoc()) ?>
                </tbody>
                <tfoot>
                    <tr><th>Module</th><th>User Level</th></tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <input type="hidden" name="user_id" value="<?php if($permission=='edit') { echo $userid; } ?>">
            <input type="submit" class="btn btn-primary" name="<?php
                if(isset($_GET['act']) && $_GET['act']=='edit' || $userExisting) {
                    echo 'save';
                } elseif(isset($_GET['act']) && $_GET['act']=='add') {
                    echo 'add';
                }
            ?>" value="Save">
            <input type="submit" class="btn btn-secondary" name="cancel" value="Cancel">
        </div>
    </div>
</form>
<?php if($userExisting) { ?>
    <script>alert("User sudah didaftarkan");</script>
<?php } ?>