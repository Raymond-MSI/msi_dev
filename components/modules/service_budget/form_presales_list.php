<?php
    if($_GET['act']=='edit') {
        global $DBMPL;
        $condition = "presales_id=" . $_GET['presales_id'];
        $data = $DBMPL->get_data($tblname, $condition);
        $ddata = $data[0];
        $qdata = $data[1];
        $tdata = $data[2];
    }
    ?>
    <form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
        <div class="row">
            <div class="col-lg-6">
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Presales Id</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="presales_id" name="presales_id" value="<?php if($_GET['act']=='edit') { echo $ddata['presales_id']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Employee Name</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="employee_name" name="employee_name" value="<?php if($_GET['act']=='edit') { echo $ddata['employee_name']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Employee Email</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="employee_email" name="employee_email" value="<?php if($_GET['act']=='edit') { echo $ddata['employee_email']; } ?>">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Organization Name</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="organization_name" name="organization_name" value="<?php if($_GET['act']=='edit') { echo $ddata['organization_name']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Presales Type</label>
                    <div class="col-sm-9">
                    <select class="form-select form-select-sm" aria-label="Default select example" id="presales_type" name="presales_type">
                        <option value="Presales Account" <?php echo (isset( $ddata['presales_type']) && $ddata['presales_type']=="Presales Account") ? "selected" : ""; ?>>Presales Account</option>
                        <option value="Presales Engineer" <?php echo (isset( $ddata['presales_type']) && $ddata['presales_type']=="Presales Engineer") ? "selected" : ""; ?>>Presales Engineer</option>
                    </select>
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
    