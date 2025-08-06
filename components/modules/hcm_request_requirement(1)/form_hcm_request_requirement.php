<?php

$user = $_SESSION['Microservices_UserEmail'];
global $DBrec;


$mdlname = "HCM";
$DBHCM = get_conn($mdlname);

?>
<?php
if ($_GET['act'] == 'add') {
    global $DBRec;
    // var_dump($DBREC)
    // $condition = "id=" . $_GET['id'];
    // var_dump($condition);
    // $data = $DB->get_data($tblname, $condition);
    // $ddata = $data[0];
    // $qdata = $data[1];
    // $tdata = $data[2];
    // $assign = $DBRec->get_sql("SELECT * FROM sa_assign where assign IS NOT NULL");

    $narikstatus = $DBRec->get_sql("SELECT * FROM sa_status_k WHERE status_karyawan is not null");
    $nariknamadivisi = $DBHCM->get_sql("select distinct unit_name , division_name from sa_view_employees_v2 where employee_email is not null and unit_name is not null ORDER BY unit_name, division_name ASC");
    $nariknamaposisi = $DBHCM->get_data("mst_jobtitle", "job_title IS not NULL ORDER BY job_title ASC");
    $namaemployee    = $DBHCM->get_sql("select DISTINCT employee_name, employee_email from sa_view_employees_v2 where resign_date is null");
    $aproval_gm = $DBHCM->get_sql("SELECT * FROM sa_view_employees_v2 WHERE job_level='2' AND resign_date is null and division_name IS NOT NULL");
    $aproval_bod = $DBHCM->get_sql("SELECT * FROM sa_view_employees_v2 WHERE job_level='1' AND resign_date is null and division_name IS NOT NULL");
    $aproval_gm_hcm = $DBHCM->get_sql("SELECT * FROM sa_view_employees_v2 WHERE department_name = 'Human Capital Management' AND job_level='2'");

?>
    <form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="requirement" role="tabpanel">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="card shadow mb-1">
                            <div class="card-header fw-bold">Department</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">ID</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control form-control-sm" id="id" name="id" readonly>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Divisi <b>*</b></label>
                                                <div class="col-sm-9">
                                                    <select class="form-control" name="divisi" id="divisi">
                                                        <?php if ($_GET['act'] == "add") {
                                                        ?>
                                                            <option></option>
                                                            <?php do { ?>
                                                                <option value="<?php echo $nariknamadivisi[0]['unit_name']; ?>"><?php echo $nariknamadivisi[0]['unit_name'] . "(" . $nariknamadivisi[0]['division_name'] . ")"; ?></option>
                                                            <?php } while ($nariknamadivisi[0] = $nariknamadivisi[1]->fetch_assoc()); ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Posisi <b>*</b></label>
                                                <div class="col-sm-9">
                                                    <select class="form-control" name="posisi" id="posisi">
                                                        <?php if ($_GET['act'] == "add") { ?>
                                                            <?php if (isset($_GET['posisi'])) {
                                                                $namaposisi = $DBHCM->get_data("mst_jobtitle", "job_title IS not NULL AND job_title LIKE '%" . $_GET['posisi'] . "%'"); ?>
                                                                <option value="<?php echo $namaposisi[0]['job_title']; ?>"><?php echo $namaposisi[0]['job_title']; ?></option>
                                                            <?php } ?>
                                                            <option></option>
                                                            <?php do { ?>
                                                                <option><?php echo $nariknamaposisi[0]['job_title']; ?></option>
                                                            <?php } while ($nariknamaposisi[0] = $nariknamaposisi[1]->fetch_assoc()); ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- KANAN -->
                                        <div class="col-lg-6">
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Jumlah Dibutuhkan</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control form-control-sm" id="jumlah_dibutuhkan" name="jumlah_dibutuhkan">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Tanggal Dibutuhkan</label>
                                                <div class="col-sm-9">
                                                    <input type="date" class="form-control form-control-sm" id="tanggal_dibutuhkan" name="tanggal_dibutuhkan">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Deskripsi</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control form-control-sm" id="deskripsi" name="deskripsi">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card shadow mb-1">
                            <div class="card-header fw-bold">Project</div>
                            <div class="card-body">
                                <div class="col-lg-6">
                                    <div class="row mb-3">
                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Nama Project</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control form-control-sm" id="nama_project" name="nama_project">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Nama Customer</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control form-control-sm" id="nama_customer" name="nama_customer">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Code</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control form-control-sm" id="project_code" name="project_code">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card shadow mb-1">
                            <div class="card-header fw-bold">Requirement</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Rekrutmen <b>*</b></label>
                                            <div class="col-sm-9">
                                                <?php if ($_GET['act'] == "add") { ?>
                                                    <select class="form-control" name="status_rekrutmen" id="status_rekrutmen">
                                                        <option></option>
                                                        <option value="Internship">Internship</option>
                                                        <option value="Penambahan">Penambahan</option>
                                                        <option value="Penggantian">Penggantian</option>
                                                    </select>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Karyawan <b>*</b></label>
                                            <div class="col-sm-9">
                                                <select class="form-control" name="status_karyawan" id="status_karyawan">
                                                    <?php if ($_GET['act'] == "add") { ?>
                                                        <option></option>
                                                        <?php do { ?>
                                                            <option><?php echo $narikstatus[0]['status_karyawan']; ?></option>
                                                        <?php } while ($narikstatus[0] = $narikstatus[1]->fetch_assoc()); ?>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Apakah Ada Di MPP <b>*</b></label>
                                            <div class="col-sm-9">
                                                <?php if ($_GET['act'] == "add") { ?>
                                                    <select class="form-control" name="mpp" id="mpp">
                                                        <option></option>
                                                        <option value="Ya">Ya</option>
                                                        <option value="Tidak">Tidak</option>
                                                    </select>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Jenis Kelamin <b>*</b></label>
                                            <div class="col-sm-9">
                                                <?php if ($_GET['act'] == "add") { ?>
                                                    <select class="form-control" name="jenis_kelamin" id="jenis_kelamin">
                                                        <option></option>
                                                        <option value="None">None</option>
                                                        <option value="Laki-Laki">Laki-Laki</option>
                                                        <option value="Perempuan">Perempuan</option>
                                                    </select>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Usia</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control form-control-sm" id="usia" name="usia">
                                            </div>
                                        </div>

                                    </div>
                                    <!-- KANAN -->
                                    <div class="col-lg-6">
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Pendidikan Minimal <b>*</b></label>
                                            <div class="col-sm-9">
                                                <?php if ($_GET['act'] == "add") { ?>
                                                    <select class="form-control" name="pendidikan_minimal" id="pendidikan_minimal">
                                                        <option></option>
                                                        <option value="SMA (High School)">SMA (High School)</option>
                                                        <option value="D3 (Diploma)">D3 (Diploma)</option>
                                                        <option value="S1 (Bachelor)">S1 (Bachelor)</option>
                                                        <option value="S2 (Master)">S2 (Master)</option>
                                                    </select>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Fakultas/Jurusan</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control form-control-sm" id="jurusan" name="jurusan">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Pengalaman Minimal</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control form-control-sm" id="pengalaman_minimal" name="pengalaman_minimal">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Kompetensi Teknis Yang Harus Dikuasai</label>
                                            <div class="col-sm-9">
                                                <textarea type="text" class="form-control" id="kompetensi_teknis" name="kompetensi_teknis" rows="2"></textarea>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Kompetensi Non Teknis Yang Harus Dikuasai</label>
                                            <div class="col-sm-9">
                                                <textarea type="text" class="form-control" id="kompetensi_non_teknis" name="kompetensi_non_teknis" rows="2"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card shadow mb-1">
                            <div class="card-header fw-bold">Offering</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Kandidat <b>*</b></label>
                                            <div class="col-sm-9">
                                                <?php if ($_GET['act'] == "add") { ?>
                                                    <select class="form-control" name="kandidat" id="kandidat">
                                                        <option></option>
                                                        <option value="Internal">Internal</option>
                                                        <option value="eksternal">Eksternal</option>
                                                    </select>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="row mb-3" id="internalSection">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Internal <b>*</b></label>
                                            <div class="col-sm-9">
                                                <select class="form-control" name="internal" id="internal">
                                                    <?php if ($_GET['act'] == "add") { ?>
                                                        <?php if (isset($_GET['internal'])) {
                                                            $internal = $DBHCM->get_data("view_employees_v2", "employee_name = '" . $_GET['internal'] . "'"); ?>
                                                            <option value="<?php echo $internal[0]['employee_name']; ?>"><?php echo $internal[0]['employee_name']; ?></option>
                                                        <?php } ?>
                                                        <?php do { ?>
                                                            <option value="<?php echo $namaemployee[0]['employee_name']; ?>"><?php echo $namaemployee[0]['employee_name'] . "(" . $namaemployee[0]['employee_email'] . ")"; ?></option>
                                                        <?php } while ($namaemployee[0] = $namaemployee[1]->fetch_assoc()); ?>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan</label>
                                            <div class="col-sm-9">
                                                <textarea type="text" class="form-control form-control-sm" id="catatan" name="catatan" rows="2"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- KANAN -->
                                    <div class="col-lg-6">
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Diisi Oleh HCM </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control form-control-sm" id="diisi_oleh_hcm" name="diisi_oleh_hcm">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Range Salary <b>*</b></label>
                                            <div class="col-sm-9">
                                                <input type="number" class="form-control form-control-sm" id="range_salary" name="range_salary">
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
        <div class="card shadow mb-1">
            <div class="card-header fw-bold">Approval</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Approval GM <b>*</b></label>
                            <div class="col-sm-9">
                                <select class="form-control" name="gm" id="gm">
                                    <?php if ($_GET['act'] == "add") { ?>
                                        <option></option>
                                        <option value="malik@mastersystem.co.id">malik@mastersystem.co.id</option>
                                        <?php do { ?>
                                            <option value="<?php echo $aproval_gm[0]['employee_email']; ?>"><?php echo $aproval_gm[0]['employee_name'] . "(" . $aproval_gm[0]['division_name'] . ")"; ?></option>
                                        <?php } while ($aproval_gm[0] = $aproval_gm[1]->fetch_assoc()); ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status GM <b>*</b></label>
                            <div class="col-sm-9">
                                <?php if ($_GET['act'] == "add") { ?>
                                    <select class="form-control" name="status_gm" id="status_gm">
                                        <option></option>
                                        <option value="Pending">Pending</option>
                                    </select>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Approval GM HCM <b>*</b></label>
                            <div class="col-sm-9">
                                <select class="form-control" name="gm_hcm" id="gm_hcm">
                                    <?php if ($_GET['act'] == "add") { ?>
                                        <option></option>
                                        <option value="malik@mastersystem.co.id">malik@mastersystem.co.id</option>
                                        <?php do { ?>
                                            <option value="<?php echo $aproval_gm_hcm[0]['employee_email']; ?>"><?php echo $aproval_gm_hcm[0]['employee_name'] . " (" . $aproval_gm_hcm[0]['division_name'] . ")"; ?></option>
                                        <?php } while ($aproval_gm_hcm[0] = $aproval_gm_hcm[1]->fetch_assoc()); ?>
                                    <?php } else { ?>
                                        <option></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status GM HCM<b>*</b></label>
                            <div class="col-sm-9">
                                <?php if ($_GET['act'] == "add") { ?>
                                    <select class="form-control" name="status_gm_hcm" id="status_gm_hcm">
                                        <option></option>
                                        <option value="Pending">Pending</option>
                                    </select>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="row mb-3" id="gm_bod_add">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Approval GM BOD<b>*</b></label>
                            <div class="col-sm-9">
                                <select class="form-control" name="gm_bod" id="gm_bod">
                                    <?php if ($_GET['act'] == "add") { ?>
                                        <option></option>
                                        <option value="malik@mastersystem.co.id">malik@mastersystem.co.id</option>
                                        <?php do { ?>
                                            <option value="<?php echo $aproval_bod[0]['employee_email']; ?>"><?php echo $aproval_bod[0]['employee_name'] . " (" . $aproval_bod[0]['division_name'] . ")"; ?></option>
                                        <?php } while ($aproval_bod[0] = $aproval_bod[1]->fetch_assoc()); ?>
                                    <?php } else { ?>
                                        <option></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3" id="status_gm_bod_add">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status GM BOD<b>*</b></label>
                            <div class="col-sm-9">
                                <?php if ($_GET['act'] == "add") { ?>
                                    <select class="form-control" name="status_gm_bod" id="status_gm_bod">
                                        <option></option>
                                        <option value="Pending">Pending</option>
                                    </select>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } else if ($_GET['act'] == 'edit') {
    global $DBRec;
    $condition = "id=" . $_GET['id'];
    $data = $DBRec->get_data($tblname, $condition);
    $ddata = $data[0];
    $qdata = $data[1];
    $tdata = $data[2];

    ?>
        <form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
        <?php
    } ?>
        <input type="submit" class="btn btn-secondary" name="cancel" value="Cancel">
        <?php if (isset($_GET['act']) && $_GET['act'] == 'edit') { ?>
            <input type="submit" class="btn btn-primary" name="save" value="Save">
        <?php } elseif (isset($_GET['act']) && $_GET['act'] == 'add') { ?>
            <input type="submit" class="btn btn-primary" name="add" value="Save">
        <?php } ?>
        </form>



        <script>
            // Add an event listener to the kandidat dropdown to toggle the visibility of the internal section
            document.getElementById('kandidat').addEventListener('change', function() {
                var internalSection = document.getElementById('internalSection');
                internalSection.style.display = (this.value === 'Internal') ? 'block' : 'none';
            });
        </script>