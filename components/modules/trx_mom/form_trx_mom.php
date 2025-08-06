<?php


if ($_GET['act'] == 'add') {
    global $DBMOM;
    // $condition = "mom_id=" . $_GET['mom_id'];
    // $data = $DBMOM->get_data($tblname, $condition);
    // $ddata = $data[0];
    // $qdata = $data[1];
    // $tdata = $data[2];
    $mdlname = "MICROSERVICES";
    $dbmdl = get_conn($mdlname);
    $narikmodule = $dbmdl->get_sqlV2("SELECT * FROM sa_cfg_web");

    $mdlnamehcm = "HCM";
    $dbhcm = get_conn($mdlnamehcm);
    $mdl_hcm = $dbhcm->get_sqlV2("SELECT DISTINCT employee_email FROM sa_view_employees_v2 WHERE employee_email IS NOT NULL ORDER BY employee_email")
?>
    <form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
        <div class="row">
            <div class="col-lg-6">
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Mom Id</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" id="mom_id" name="mom_id" readonly>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Module Name</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" id="module_name" name="module_name">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Created By</label>
                    <div class="col-sm-9">
                        <?php if ($_GET['act'] == "add") { ?>
                            <select class="form-control" name="created_by" id="created_by">
                                <?php do { ?>
                                    <option value="<?php echo $mdl_hcm[0]['employee_email'] ?>"><?php echo $mdl_hcm[0]['employee_email'] ?></option>
                                <?php } while ($mdl_hcm[0] = $mdl_hcm[1]->fetch_assoc()); ?>
                            </select>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Module Note</label>
                    <div class="col-sm-9">
                        <textarea class="form-control form-control-sm" name="module_note" id="module_note" cols="30" rows="2"></textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">ETC</label>
                    <div class="col-sm-9">
                        <input type="date" class="form-control form-control-sm" name="etc" id="etc" value="<?php echo date("Y-m-d"); ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status</label>
                    <div class="col-sm-9">
                        <?php if ($_GET['act'] == "add") { ?>
                            <select class="form-control" name="status_mom" id="status_mom" readonly="">
                                <option value="On Progress">On Progress</option>
                            </select>
                        <?php } ?>
                        <!-- <input type="date" class="form-control form-control-sm" name="etc" id="etc" value="<?php //echo date("Y-m-d"); 
                                                                                                                ?>"> -->
                    </div>
                </div>
            </div>
        </div>
    <?php
} else if ($_GET['act'] == 'edit') {
    global $DBMOM;
    $condition = "mom_id=" . $_GET['mom_id'];
    $data = $DBMOM->get_data($tblname, $condition);
    $ddata = $data[0];
    $qdata = $data[1];
    $tdata = $data[2];

    global $DBMOM;
    $data_mom = $DBMOM->get_sqlV2('SELECT * FROM sa_trx_mom');
    ?>
        <form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
            <div class="row">
                <div class="col-lg-6">
                    <div class="row mb-3">
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Mom Id</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control form-control-sm" id="mom_id" name="mom_id" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                            echo $ddata['mom_id'];
                                                                                                                        } ?>" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Module Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control form-control-sm" id="module_name" name="module_name" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                    echo $ddata['module_name'];
                                                                                                                                } ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Created by</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control form-control-sm" id="created_by" name="created_by" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                    echo $ddata['created_by'];
                                                                                                                                } ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row mb-3">
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Module Note</label>
                        <div class="col-sm-9">
                            <textarea class="form-control form-control-sm" name="module_note" id="module_note" cols="30" rows="2"><?php if ($_GET['act'] == 'edit') {
                                                                                                                                        echo $ddata['module_note'];
                                                                                                                                    } ?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">ETC</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control form-control-sm" name="etc" id="etc" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                    echo $ddata['etc'];
                                                                                                                } ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status</label>
                        <div class="col-sm-9">
                            <?php if ($_GET['act'] == "edit" && $ddata['status_mom'] == "On Progress" && $ddata['created_by'] == $_SESSION['Microservices_UserEmail']) { ?>
                                <select class="form-control form-control-sm" name="status_mom" id="status_mom">
                                    <option value="<?php echo $ddata['status_mom']; ?>"><?php echo $ddata['status_mom']; ?></option>
                                    <option value="Done">Done</option>
                                </select>
                            <?php } elseif ($_GET['act'] == "edit") { ?>
                                <select class="form-control form-control-sm" name="status_mom" id="status_mom" readonly>
                                    <option value="<?php echo $ddata['status_mom']; ?>"><?php echo $ddata['status_mom']; ?></option>
                                </select>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php
    } ?>
        <input type="submit" class="btn btn-secondary" name="cancel" value="Cancel">
        <?php if (isset($_GET['act']) && $_GET['act'] == 'edit') { ?>
            <input type="submit" class="btn btn-primary" name="save" value="Save">
        <?php } elseif (isset($_GET['act']) && $_GET['act'] == 'add') { ?>
            <input type="submit" class="btn btn-primary" name="add" value="Save">
        <?php } ?>
        </form>