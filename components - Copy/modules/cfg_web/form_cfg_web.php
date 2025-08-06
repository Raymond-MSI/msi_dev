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
    <form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>&mod_id=<?php echo $_GET['mod_id']; ?>">
        <div class="row">
            <div class="col-lg-6">
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">id</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="id" name="id" value="<?php if($_GET['act']=='edit') { echo $ddata['id']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">title</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="title" name="title" value="<?php if($_GET['act']=='edit') { echo $ddata['title']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">config_key</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="config_key" name="config_key" value="<?php if($_GET['act']=='edit') { echo $ddata['config_key']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">config_value</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="config_value" name="config_value" value="<?php if($_GET['act']=='edit') { echo $ddata['config_value']; } ?>">
                    </div>
                </div>
    </div>
            <div class="col-lg-6">
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">params</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="params" name="params" value="<?php if($_GET['act']=='edit') { echo $ddata['params']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">parent</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="parent" name="parent" value="<?php if($_GET['act']=='edit') { echo $ddata['parent']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">order</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="order" name="order" value="<?php if($_GET['act']=='edit') { echo $ddata['order']; } ?>">
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="user_id" value="<?php if($permission=='edit') { echo $dusers['user_id']; } ?>">
        <input type="submit" class="btn btn-secondary" name="cancel" value="Cancel">
        <?php if(isset($_GET['act']) && $_GET['act']=='edit') { ?>
            <input type="submit" class="btn btn-primary" name="save" value="Save">
        <?php } elseif(isset($_GET['act']) && $_GET['act']=='add') { ?>
            <input type="submit" class="btn btn-primary" name="add" value="Save">
        <?php } ?>
    </form>
    