<?php
if ($_GET['act'] == 'edit') {
    global $DBTRXAPP;
    $condition = "id=" . $_GET['id'];
    $data = $DBTRXAPP->get_data($tblname, $condition);
    $ddata = $data[0];
    $qdata = $data[1];
    $tdata = $data[2];
}
?>
<form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
    <div class="row">
        <div class="col-lg-6">
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Id</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="id" name="id" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                            echo $ddata['id'];
                                                                                                        } ?>" readonly>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Id Request</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="id_request" name="id_request" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                            echo $ddata['id_request'];
                                                                                                                        } ?>" readonly>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Divisi</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="divisi" name="divisi" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                    echo $ddata['divisi'];
                                                                                                                } ?>" readonly>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Posisi</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="posisi" name="posisi" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                    echo $ddata['posisi'];
                                                                                                                } ?>" readonly>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Kode Project</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="kode_project" name="kode_project" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                echo $ddata['kode_project'];
                                                                                                                            } ?>" readonly>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Send Datetime</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="send_datetime" name="send_datetime" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                echo $ddata['send_datetime'];
                                                                                                                            } ?>" readonly>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Jumlah Dibutuhkan</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="jumlah_dibutuhkan" name="jumlah_dibutuhkan" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                        echo $ddata['jumlah_dibutuhkan'];
                                                                                                                                    } ?>" readonly>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Requirement</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="status_requirement" name="status_requirement" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                            echo $ddata['status_requirement'];
                                                                                                                                        } ?>" readonly>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Requirements</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="requirements" name="requirements" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                echo $ddata['requirements'];
                                                                                                                            } ?>" readonly>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="status_approval" name="status_approval" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                    echo $ddata['status_approval'];
                                                                                                                                } ?>" readonly>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Assign <b>*</b></label>
                <div class="col-sm-9">
                    <?php if ($_GET['act'] == "add") { ?>
                        <select class="form-control" name="assign" id="assign">
                            <option></option>
                            <option value="Laki-Laki">Laki-Laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    <?php } ?>
                    <?php if ($_GET['act'] == "edit" && $ddata['assign'] == "" || $_GET['act'] == "edit" && $ddata['assign'] == null) { ?>
                        <select class="form-control" name="assign" id="assign">
                            <option><?php echo ucwords($ddata['assign']); ?></option>
                            <option value="Assign Recruitment 1">Assign Recruitment 1</option>
                            <option value="Assign Recruitment 2">Assign Recruitment 2</option>
                            <option value="Assign Recruitment 3">Assign Recruitment 3</option>
                        </select>
                    <?php } else if ($_GET['act'] == "edit" && $ddata['assign'] == "Assign Recruitment 1" || $_GET['act'] == "edit" && $ddata['assign'] == null) { ?>
                        <select class="form-control" name="assign" id="assign">
                            <option><?php echo ucwords($ddata['assign']); ?></option>
                            <option value="Assign Recruitment 2">Assign Recruitment 2</option>
                            <option value="Assign Recruitment 3">Assign Recruitment 3</option>
                        </select>
                    <?php } else if ($_GET['act'] == "edit" && $ddata['assign'] == "Assign Recruitment 2" || $_GET['act'] == "edit" && $ddata['assign'] == null) { ?>
                        <select class="form-control" name="assign" id="assign">
                            <option><?php echo ucwords($ddata['assign']); ?></option>
                            <option value="Assign Recruitment 1">Assign Recruitment 1</option>
                            <option value="Assign Recruitment 3">Assign Recruitment 3</option>
                        </select>
                    <?php } else if ($_GET['act'] == "edit" && $ddata['assign'] == "Assign Recruitment 3" || $_GET['act'] == "edit" && $ddata['assign'] == null) { ?>
                        <select class="form-control" name="assign" id="assign">
                            <option><?php echo ucwords($ddata['assign']); ?></option>
                            <option value="Assign Recruitment 1">Assign Recruitment 1</option>
                            <option value="Assign Recruitment 2">Assign Recruitment 2</option>
                        </select>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <input type="submit" class="btn btn-secondary" name="cancel" value="Cancel">
    <?php if (isset($_GET['act']) && $_GET['act'] == 'edit') { ?>
        <input type="submit" class="btn btn-primary" name="save" value="Save">
    <?php } elseif (isset($_GET['act']) && $_GET['act'] == 'add') { ?>
        <input type="submit" class="btn btn-primary" name="add" value="Save">
    <?php } ?>
</form>