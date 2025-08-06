<?php
    if($_GET['act']=='edit') {
        global $DB;
        $condition = "id=" . $_GET['id'];
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
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Id</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="id" name="id" value="<?php if($_GET['act']=='edit') { echo $ddata['id']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Id Req</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="id_req" name="id_req" value="<?php if($_GET['act']=='edit') { echo $ddata['id_req']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Divisi</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="divisi" name="divisi" value="<?php if($_GET['act']=='edit') { echo $ddata['divisi']; } ?>">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Posisi</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="posisi" name="posisi" value="<?php if($_GET['act']=='edit') { echo $ddata['posisi']; } ?>">
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
    