<?php
    if($_GET['act']=='edit') {
        global $DBLD;
        $condition = "cat_id=" . $_GET['cat_id'];
        $data = $DBLD->get_data($tblname, $condition);
        $ddata = $data[0];
        $qdata = $data[1];
        $tdata = $data[2];
    }
    ?>
    <form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
        <div class="row">
            <div class="col-lg-6">
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Cat Id</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="cat_id" name="cat_id" value="<?php if($_GET['act']=='edit') { echo $ddata['cat_id']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Cat Name</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="cat_name" name="cat_name" value="<?php if($_GET['act']=='edit') { echo $ddata['cat_name']; } ?>">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
            </div>
        </div>
        <input type="submit" class="btn btn-secondary" name="cancel" value="Cancel">
        <?php if(isset($_GET['act']) && $_GET['act']=='edit') { ?>
            <input type="submit" class="btn btn-primary" name="save" value="Save">
        <?php } elseif(isset($_GET['act']) && $_GET['act']=='add') { ?>
            <input type="submit" class="btn btn-primary" name="add" value="Save">
        <?php } ?>
    </form>
    