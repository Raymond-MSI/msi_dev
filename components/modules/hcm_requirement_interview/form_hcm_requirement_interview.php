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
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Id Request</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="id_request" name="id_request" value="<?php if($_GET['act']=='edit') { echo $ddata['id_request']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Id Fpkb</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="id_fpkb" name="id_fpkb" value="<?php if($_GET['act']=='edit') { echo $ddata['id_fpkb']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Id Calendar</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="id_calendar" name="id_calendar" value="<?php if($_GET['act']=='edit') { echo $ddata['id_calendar']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Nama Kandidat</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="nama_kandidat" name="nama_kandidat" value="<?php if($_GET['act']=='edit') { echo $ddata['nama_kandidat']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Email</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="email" name="email" value="<?php if($_GET['act']=='edit') { echo $ddata['email']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Id Folderdrive</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="id_folderdrive" name="id_folderdrive" value="<?php if($_GET['act']=='edit') { echo $ddata['id_folderdrive']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Id File</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="id_file" name="id_file" value="<?php if($_GET['act']=='edit') { echo $ddata['id_file']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">File</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="file" name="file" value="<?php if($_GET['act']=='edit') { echo $ddata['file']; } ?>">
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
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Offering Salary</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="offering_salary" name="offering_salary" value="<?php if($_GET['act']=='edit') { echo $ddata['offering_salary']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Salary Probation</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="salary_Probation" name="salary_Probation" value="<?php if($_GET['act']=='edit') { echo $ddata['salary_Probation']; } ?>">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Salary Permanent</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="salary_Permanent" name="salary_Permanent" value="<?php if($_GET['act']=='edit') { echo $ddata['salary_Permanent']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Manager Hcm</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="manager_hcm" name="manager_hcm" value="<?php if($_GET['act']=='edit') { echo $ddata['manager_hcm']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Manager Hcm</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="status_manager_hcm" name="status_manager_hcm" value="<?php if($_GET['act']=='edit') { echo $ddata['status_manager_hcm']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan Manager Hcm</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="catatan_manager_hcm" name="catatan_manager_hcm" value="<?php if($_GET['act']=='edit') { echo $ddata['catatan_manager_hcm']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Hcm Gm</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="hcm_gm" name="hcm_gm" value="<?php if($_GET['act']=='edit') { echo $ddata['hcm_gm']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Hcm Gm</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="status_hcm_gm" name="status_hcm_gm" value="<?php if($_GET['act']=='edit') { echo $ddata['status_hcm_gm']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan Hcm Gm</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="catatan_hcm_gm" name="catatan_hcm_gm" value="<?php if($_GET['act']=='edit') { echo $ddata['catatan_hcm_gm']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Gm Offering</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="gm_offering" name="gm_offering" value="<?php if($_GET['act']=='edit') { echo $ddata['gm_offering']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Gm Offering</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="status_gm_offering" name="status_gm_offering" value="<?php if($_GET['act']=='edit') { echo $ddata['status_gm_offering']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan Gm Offering</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="catatan_gm_offering" name="catatan_gm_offering" value="<?php if($_GET['act']=='edit') { echo $ddata['catatan_gm_offering']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Bod</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="bod" name="bod" value="<?php if($_GET['act']=='edit') { echo $ddata['bod']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Bod</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="status_bod" name="status_bod" value="<?php if($_GET['act']=='edit') { echo $ddata['status_bod']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan Bod</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="catatan_bod" name="catatan_bod" value="<?php if($_GET['act']=='edit') { echo $ddata['catatan_bod']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Cv</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="status_cv" name="status_cv" value="<?php if($_GET['act']=='edit') { echo $ddata['status_cv']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Join Date</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="join_date" name="join_date" value="<?php if($_GET['act']=='edit') { echo $ddata['join_date']; } ?>">
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
    