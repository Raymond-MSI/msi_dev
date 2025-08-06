<?php
    $queryString = $_SERVER['HTTP_REFERER'];
    if($_GET['act']=='edit') {
        global $DB;
        $condition = "id=" . $_GET['id'];
        $data = $DB->get_data($tblname, $condition);
        $ddata = $data[0];
        $qdata = $data[1];
        $tdata = $data[2];
    }
    ?>
    <form method="post" action="<?php echo $queryString; ?>">
        <div class="row">
            <div class="col-lg-6">
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Id</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="id" name="id" value="<?php if($_GET['act']=='edit') { echo $ddata['id']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Title</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="title" name="title" value="<?php if($_GET['act']=='edit') { echo $ddata['title']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Config Key</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="config_key" name="config_key" value="<?php if($_GET['act']=='edit') { echo $ddata['config_key']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Config Value</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="config_value" name="config_value" value="<?php if($_GET['act']=='edit') { echo $ddata['config_value']; } ?>">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Params</label>
                    <div class="col-sm-9">
                    <!-- <input type="text" class="form-control form-control-sm" id="params" name="params" value="<?php if($_GET['act']=='edit') { echo $ddata['params']; } ?>"> -->
                    <textarea class="form-control" id="params" name="params" rows="3"><?php if($_GET['act']=='edit') { echo $ddata['params']; } ?></textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Parent</label>
                    <div class="col-sm-9">
                    <!-- <input type="text" class="form-control form-control-sm" id="parent" name="parent" value="<?php if($_GET['act']=='edit') { echo $ddata['parent']; } ?>"> -->
                    <?php
                    global $DB;
                    $tblname = 'cfg_web';
                    $condition = '';
                    $order = "parent ASC, `order` ASC";
                    $parents = $DB->get_data($tblname, $condition, $order);
                    $dparents = $parents[0];
                    $qparents = $parents[1];
                    ?>
                    <select class="form-select form-select-sm" id="parent" name="parent">
                        <?php
                            do { ?>
                                <option value="<?php echo $dparents['id']; ?>" 
                                <?php
                                if($_GET['act']!='add') { 
                                    if($dparents['id']==$ddata['parent']) { 
                                        echo 'selected'; 
                                    }
                                }
                                ?>><?php echo $dparents['title']; ?></option>
                                <?php 
                            } while($dparents=$qparents->fetch_assoc()); 
                        ?>
                    </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Order</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="order" name="order" value="<?php if($_GET['act']=='edit') { echo $ddata['order']; } ?>">
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
    