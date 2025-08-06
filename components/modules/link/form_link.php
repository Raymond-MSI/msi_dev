<?php
if ($_GET['act'] == 'addlink') {
    global $DBLINK;
    // $condition = "id_link=" . $_GET['id_link'];
    // $data = $DBLINK->get_data($tblname, $condition);
    // $ddata = $data[0];
    // $qdata = $data[1];
    // $tdata = $data[2];

?>
    <form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
        <div class="row">
            <div class="col-lg-6">
                <div class="row mb-3" <?php if ($_GET['act'] == 'addlink') {
                                            echo 'style="display: none;"';
                                        } ?>>
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Id Link</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" id="id_link" name="id_link">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Link From</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" id="link_from" name="link_from">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
            </div>
        </div>
    <?php } ?>
    <input type="submit" class="btn btn-secondary" name="cancel" value="Cancel">
    <?php if (isset($_GET['act']) && $_GET['act'] == 'edit') { ?>
        <input type="submit" class="btn btn-primary" name="save" value="Save">
    <?php } elseif (isset($_GET['act']) && $_GET['act'] == 'addlink') { ?>
        <input type="submit" class="btn btn-primary" name="addlink" value="Save">
    <?php } ?>
    </form>