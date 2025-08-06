<?php
    if($_GET['act']=='edit') {
        global $DB;
        $tblname = 'mst_users';
        $condition = 'username="' . $_SESSION['Microservices_UserName'] . '" OR email="' . $_SESSION['Microservices_UserEmail'] . '"';
        $data = $DB->get_data($tblname, $condition);
        $ddata = $data[0];
        $qdata = $data[1];
        $tdata = $data[2];
    }
    ?>
    <form method="post" action="index.php?mod=change_password&act=edit">
        <div class="row">
            <div class="col-lg-6">
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">User Id</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="user_id" name="user_id" value="<?php if($_GET['act']=='edit') { echo $ddata['user_id']; } ?>" readonly>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Name</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="name" name="name" value="<?php if($_GET['act']=='edit') { echo $ddata['name']; } ?>" readonly>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Username</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="username" name="username" value="<?php if($_GET['act']=='edit') { echo $ddata['username']; } ?>" readonly>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Email</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="email" name="email" value="<?php if($_GET['act']=='edit') { echo $ddata['email']; } ?>" readonly>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Password</label>
                    <div class="col-sm-9">
                    <input type="password" class="form-control form-control-sm" id="password" name="password" value="<?php if($_GET['act']=='edit') { echo $ddata['password']; } ?>" readonly>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">New Password</label>
                    <div class="col-sm-9">
                    <input type="password" class="form-control form-control-sm" id="newpassword" name="newpassword">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Repeat New Password</label>
                    <div class="col-sm-9">
                    <input type="password" class="form-control form-control-sm" id="repeatpassword" name="repeatpassword">
                    </div>
                </div>
            </div>
        </div>
        <input type="submit" class="btn btn-secondary" name="cancel" value="Cancel">
        <?php if(isset($_GET['act']) && $_GET['act']=='edit') { ?>
            <input type="submit" class="btn btn-primary" name="save" value="Save">
        <?php } elseif(isset($_GET['act']) && $_GET['act']=='add') { ?>
            <input type="submit" class="btn btn-primary" name="add" value="Save">
        <?php } ?>
    </form>
    