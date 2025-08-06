<?php

if ($_GET['act'] == 'view') {
    // global $DB;
    // $condition = "id_note=" . $_GET['id_note'];
    // $data = $DB->get_data($tblname, $condition);
    // $ddata = $data[0];
    // $qdata = $data[1];
    // $tdata = $data[2];
?>
    <div class="row">
        <div class="col-lg-6">
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Id Note</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="id_note" name="id_note" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                    echo $ddata['id_note'];
                                                                                                                } ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Code</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="project_code" name="project_code" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                echo $ddata['project_code'];
                                                                                                                            } ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">File</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="file" name="file" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                echo $ddata['file'];
                                                                                                            } ?>">
                </div>
            </div>
        </div>
    </div>
<?php }

if ($_GET['act'] == 'edit') {
    global $DB;
    $condition = "id_note=" . $_GET['id_note'];
    $data = $DB->get_data($tblname, $condition);
    $ddata = $data[0];
    $qdata = $data[1];
    $tdata = $data[2];
}
?>
<form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
    <div class="row">
        <div class="col-lg-6">
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Id Note</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="id_note" name="id_note" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                    echo $ddata['id_note'];
                                                                                                                } ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Code</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="project_code" name="project_code" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                echo $ddata['project_code'];
                                                                                                                            } ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">File</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="file" name="file" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                echo $ddata['file'];
                                                                                                            } ?>">
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