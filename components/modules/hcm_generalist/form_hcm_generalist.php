<?php
if ($_GET['act'] == 'edit') {
    global $DBHCM;
    $condition = "email_id=" . $_GET['email_id'];
    $data = $DBHCM->get_data($tblname, $condition);
    $ddata = $data[0];
    $qdata = $data[1];
    $tdata = $data[2];
?>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active text-body" id="editreq-tab" data-bs-toggle="tab" data-bs-target="#editreq" type="button" role="tab" aria-controls="editreq" aria-selected="true">General</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link text-body" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab" aria-controls="history" aria-selected="false">History</button>
        </li>
    </ul>
    <form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="editreq" role="tabpanel" aria-labelledby="editreq-tab">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="row mb-3" hidden>
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Email Id</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control form-control-sm" id="email_id" name="email_id" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                                    echo $ddata['email_id'];
                                                                                                                                                } ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3" hidden>
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Id Request</label>
                                            <div class="col-sm-9">
                                                <input type="text" readclass="form-control form-control-sm" id="id_request" name="id_request" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                                            echo $ddata['id_request'];
                                                                                                                                                        } ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Id Fpkb</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control form-control-sm" id="id_fpkb" name="id_fpkb" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                                echo $ddata['id_fpkb'];
                                                                                                                                            } ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3" hidden>
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Id Calendar</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control form-control-sm" id="id_calendar" name="id_calendar" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                                        echo $ddata['id_calendar'];
                                                                                                                                                    } ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Nama</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control form-control-sm" id="nama_kandidat" name="nama_kandidat" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                                            echo $ddata['nama_kandidat'] . " [" . $ddata['email'] . "] ";
                                                                                                                                                        } ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Salary Permanent</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control form-control-sm" id="salary_Permanent" name="salary_Permanent" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                                                    echo $ddata['salary_Permanent'];
                                                                                                                                                                } ?>">
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    <?php } ?>
    <input type="submit" class="btn btn-secondary" name="cancel" value="Cancel">
    <?php if (isset($_GET['act']) && $_GET['act'] == 'edit') { ?>
        <input type="submit" class="btn btn-primary" name="save" value="Save">
    <?php } elseif (isset($_GET['act']) && $_GET['act'] == 'add') { ?>
        <input type="submit" class="btn btn-primary" name="add" value="Save">
    <?php } ?>
    </form>