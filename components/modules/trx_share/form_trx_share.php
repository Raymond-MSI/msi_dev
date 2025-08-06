<?php
if ($_GET['act'] == 'edit') {
    global $DBTRXSHARE;
    $condition = "id=" . $_GET['id'];
    $data = $DBTRXSHARE->get_data($tblname, $condition);
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
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Request</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="request" name="request" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                    echo $ddata['request'];
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
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Share <b>*</b></label>
                <div class="col-sm-9">
                    <?php if ($_GET['act'] == "add") { ?>
                        <select class="form-control" name="share" id="share">
                            <option></option>
                            <option value="Iklan">Iklan</option>
                            <option value="CV Kandidat">CV Kandidat</option>
                        </select>
                    <?php } ?>
                    <?php if ($_GET['act'] == "edit" && $ddata['share'] == "" || $_GET['act'] == "edit" && $ddata['share'] == null) { ?>
                        <select class="form-control" name="share" id="share">
                            <option><?php echo ucwords($ddata['share']); ?></option>
                            <option value="IKLAN"> IKLAN </option>
                            <option value="CV Kandidat">CV Kandidat</option>
                        </select>
                    <?php } elseif ($_GET['act'] == "edit" && $ddata['share'] == "Iklan" || $_GET['act'] == "edit" && $ddata['share'] == null) { ?>
                        <select class="form-control" name="share" id="share" readonly>
                            <option><?php echo ucwords($ddata['share']); ?></option>
                            <option value="CV Kandidat">CV Kandidat</option>
                        </select>
                    <?php } else if ($_GET['act'] == "edit" && $ddata['share'] == "CV Kandidat" || $_GET['act'] == "edit" && $ddata['share'] == null) { ?>
                        <select class="form-control" name="share" id="share" readonly>
                            <option><?php echo ucwords($ddata['share']); ?></option>
                            <option value="IKLAN">IKLAN</option>
                        </select>
                    <?php } ?>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Entry Datetime</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="entry_datetime" name="entry_datetime" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                    echo $ddata['entry_datetime'];
                                                                                                                                } ?>" readonly>
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