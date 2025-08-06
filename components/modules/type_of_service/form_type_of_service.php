<?php
    if($_GET['act']=='edit') {
        global $DB;
        $condition = "tos_id=" . $_GET['tos_id'];
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
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">tos_id</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="tos_id" name="tos_id" value="<?php if($_GET['act']=='edit') { echo $ddata['tos_id']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">tos_name</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="tos_name" name="tos_name" value="<?php if($_GET['act']=='edit') { echo $ddata['tos_name']; } ?>">
                    </div>
                </div>
    </div>
            <div class="col-lg-6">
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">service_type</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="service_type" name="service_type" value="<?php if($_GET['act']=='edit') { echo $ddata['service_type']; } ?>">
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
    