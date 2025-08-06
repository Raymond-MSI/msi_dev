<?php
    if($_GET['act']=='edit') {
        global $DB;
        $condition = "answer_id=" . $_GET['answer_id'];
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
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Answer Id</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="answer_id" name="answer_id" value="<?php if($_GET['act']=='edit') { echo $ddata['answer_id']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Survey Id</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="survey_id" name="survey_id" value="<?php if($_GET['act']=='edit') { echo $ddata['survey_id']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Survey Link</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="survey_link" name="survey_link" value="<?php if($_GET['act']=='edit') { echo $ddata['survey_link']; } ?>">
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
    