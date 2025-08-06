<?php
    if($_GET['act']=='edit') {
        global $DB;
        $condition = "survey_id=" . $_GET['survey_id'];
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
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Survey Id</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="survey_id" name="survey_id" value="<?php if($_GET['act']=='edit') { echo $ddata['survey_id']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Customer Id</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="customer_id" name="customer_id" value="<?php if($_GET['act']=='edit') { echo $ddata['customer_id']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Survey Question</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="survey_question" name="survey_question" value="<?php if($_GET['act']=='edit') { echo $ddata['survey_question']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Survey Answer</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="survey_answer" name="survey_answer" value="<?php if($_GET['act']=='edit') { echo $ddata['survey_answer']; } ?>">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Survey Weight</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="survey_weight" name="survey_weight" value="<?php if($_GET['act']=='edit') { echo $ddata['survey_weight']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Survey Date</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="survey_date" name="survey_date" value="<?php if($_GET['act']=='edit') { echo $ddata['survey_date']; } ?>">
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
    