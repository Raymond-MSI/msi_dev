<?php
global $DBREC;
$mdlname = "new_request";
$DBREC   = get_conn($mdlname);
$linkfrom = $DBREC->get_sql("SELECT * FROM sa_link");

if ($_GET['act'] == 'edit') {
    global $DBINT;
    $condition = "interview_id=" . $_GET['interview_id'];
    $data = $DBINT->get_data($tblname, $condition);
    $ddata = $data[0];
    $qdata = $data[1];
    $tdata = $data[2];

?>
    <form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
        <div class="row">
            <div class="col-lg-6">
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Interview Id</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" id="interview_id" name="interview_id" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                    echo $ddata['interview_id'];
                                                                                                                                } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Divisi</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" id="divisi" name="divisi" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                        echo $ddata['divisi'];
                                                                                                                    } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Posisi</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" id="posisi" name="posisi" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                        echo $ddata['posisi'];
                                                                                                                    } ?>">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Code</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" id="project_code" name="project_code" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                    echo $ddata['project_code'];
                                                                                                                                } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Link From</label>
                    <div class="col-sm-9">
                        <select class="form-control" name="link_from" id="link_from">
                            <option></option>
                            <?php do { ?>
                                <option value="<?php echo $linkfrom[0]['link_from']; ?>"><?php echo $linkfrom[0]['link_from']; ?></option>
                            <?php } while ($linkfrom[0] = $linkfrom[1]->fetch_assoc()); ?>
                        </select>
                    </div>
                </div>
                <!-- Tombol untuk memicu modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahLinkFormModal">
                    Tambah Link Form Baru
                </button>
            </div>
        </div>
    <?php } ?>

    <!-- Modal untuk menambahkan Link Form Baru -->
    <div class="modal fade" id="tambahLinkFormModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Link Form Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form untuk menambahkan Link Form Baru -->
                    <?php
                    if ($_GET['act'] == 'edit') {
                        global $DBLINK;
                    ?>
                        <form>
                            <div class="form-group">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Link Baru</label>
                                <input type="text" class="form-control" id="link_from" name="link_from">
                            </div>
                        </form>
                    <?php } ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <!-- Tombol Simpan dengan logika PHP -->
                    <?php if ($_GET['act'] == 'edit') { ?>
                        <input type="submit" class="btn btn-primary" name="edit" value="Save">
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <input type="submit" class="btn btn-secondary" name="cancel" value="Cancel">
    <?php if (isset($_GET['act']) && $_GET['act'] == 'edit') { ?>
        <input type="submit" class="btn btn-primary" name="save" value="Save">
    <?php } elseif (isset($_GET['act']) && $_GET['act'] == 'add') { ?>
        <input type="submit" class="btn btn-primary" name="add" value="Save">
    <?php } ?>
    </form>