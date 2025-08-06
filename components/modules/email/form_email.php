<?php
    if($_GET['act']=='edit') {
        global $DB;
        $condition = "email_id=" . $_GET['email_id'];
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
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Email Id</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="email_id" name="email_id" value="<?php if($_GET['act']=='edit') { echo $ddata['email_id']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Email</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="email" name="email" value="<?php if($_GET['act']=='edit') { echo $ddata['email']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Tanggal Interview</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="tanggal_interview" name="tanggal_interview" value="<?php if($_GET['act']=='edit') { echo $ddata['tanggal_interview']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Link Webex</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="link_webex" name="link_webex" value="<?php if($_GET['act']=='edit') { echo $ddata['link_webex']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Pic</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="pic" name="pic" value="<?php if($_GET['act']=='edit') { echo $ddata['pic']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Code</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="project_code" name="project_code" value="<?php if($_GET['act']=='edit') { echo $ddata['project_code']; } ?>">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="catatan" name="catatan" value="<?php if($_GET['act']=='edit') { echo $ddata['catatan']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Interview User</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="interview_user" name="interview_user" value="<?php if($_GET['act']=='edit') { echo $ddata['interview_user']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Interview Hcm</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="interview_hcm" name="interview_hcm" value="<?php if($_GET['act']=='edit') { echo $ddata['interview_hcm']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="status" name="status" value="<?php if($_GET['act']=='edit') { echo $ddata['status']; } ?>">
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
    