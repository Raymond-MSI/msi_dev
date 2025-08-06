<?php
global $DBCOBA;

$mdlname = "req";
$DBCOBA   = get_conn($mdlname);
$query   = $DBCOBA->get_sql("SELECT * FROM sa_reqreq");

$mdlname = "HCM";
$DBHCM = get_conn($mdlname);
// $nariknamadivisi = $DBHCM->get_data("view_department", "organization_name IS not NULL");
$user = $_SESSION['Microservices_UserEmail'];
$narikstatus = $DBCOBA->get_data("status_k", "status_karyawan is not null");
//$nariknamadivisi = $DBHCM->get_sql("select * from sa_view_employees_v2 where employee_email = '" . $_SESSION['Microservices_UserEmail'] .  "'");
$nariknamadivisi = $DBHCM->get_sql("select distinct unit_name , division_name from sa_view_employees_v2 where employee_email is not null and unit_name is not null ORDER BY unit_name, division_name ASC");
$nariknamaposisi = $DBHCM->get_data("mst_jobtitle", "job_title IS not NULL ORDER BY job_title ASC");
$namaemployee    = $DBHCM->get_sql("select DISTINCT employee_name, employee_email from sa_view_employees_v2 where resign_date is null");
// $deskripsi = $DBHCM->get_data("mst_organization","description IS not NULL");
$aproval_gm = $DBHCM->get_sql("SELECT * FROM sa_view_employees_v2 WHERE job_level='2' AND resign_date is null and division_name IS NOT NULL");
$aproval_bod = $DBHCM->get_sql("SELECT * FROM sa_view_employees_v2 WHERE job_level='1' AND resign_date is null and division_name IS NOT NULL");
$aproval_gm_hcm = $DBHCM->get_sql("SELECT * FROM sa_view_employees_v2 WHERE department_name = 'Human Capital Management' AND job_level='2'");


// bagian ADD
if ($_GET['act'] == 'add') {
    global $DBCOBA;
    // var_dump($DBCOBA)
    // $condition = "id=" . $_GET['id'];
    // var_dump($condition);
    // $data = $DB->get_data($tblname, $condition);
    // $ddata = $data[0];
    // $qdata = $data[1];
    // $tdata = $data[2];
?>
    <form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="requirement" role="tabpanel">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <h2>Department</h2>
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
                            <h2>Project Status</h2>
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
                            <h2>Requirement</h2>
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
                            <h2>Offering</h2>
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
                                <div class="row mb-3" id="internal">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Internal <b>*</b></label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="internal" id="internal">
                                            <?php if ($_GET['act'] == "add") { ?>
                                                <?php if (isset($_GET['internal'])) {
                                                    $internal = $DBHCM->get_data("view_employees_v2", "employee_name = '" . $_GET['internal'] . "'"); ?>
                                                    <option value="<?php echo $internal[0]['employee_name']; ?>"><?php echo $internal[0]['employee_name']; ?></option>
                                                <?php } ?>
                                                <option></option>
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
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h2>Approval</h2>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Approval GM <b>*</b></label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="gm" id="gm">
                                        <?php if ($_GET['act'] == "add") { ?>
                                            <option></option>
                                            <option value="malik.aulia@mastersystem.co.id">malik.aulia@mastersystem.co.id</option>
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
                                            <option value="malik.aulia@mastersystem.co.id">malik.aulia@mastersystem.co.id</option>
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
                                            <option value="malik.aulia@mastersystem.co.id">malik.aulia@mastersystem.co.id</option>
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
        <?php }
    // bagian edit request by (yg nge add)
    else if ($_GET['act'] == 'edit') {
        global $DBCOBA;
        $condition = "id=" . $_GET['id'];
        $data = $DBCOBA->get_data($tblname, $condition);
        $ddata = $data[0];
        $qdata = $data[1];
        $tdata = $data[2];
        ?>
            <?php if ($ddata['gm_hcm'] == $_SESSION['Microservices_UserEmail'] || $ddata['gm'] == $_SESSION['Microservices_UserEmail'] || $ddata['gm_bod'] == $_SESSION['Microservices_UserEmail'] || $ddata['request_by'] == $_SESSION['Microservices_UserEmail']) { ?>
                <form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="requirement" role="tabpanel">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <h2>Department</h2>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">ID</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control form-control-sm" id="id" name="id" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                            echo $ddata['id'];
                                                                                                                                        } ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Divisi <b>*</b></label>
                                                <div class="col-sm-9">
                                                    <select class="form-control" name="divisi" id="divisi" readonly>
                                                        <option value="<?php echo $query[0]['divisi']; ?>" readonly><?php echo $query[0]['divisi']; ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Posisi Yang Dibutuhkan <b>*</b></label>
                                                <div class="col-sm-9">
                                                    <select class="form-control" name="posisi" id="posisi" readonly>
                                                        <option value="<?php echo $query[0]['posisi']; ?>" readonly><?php echo $query[0]['posisi']; ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- KANAN -->
                                        <div class="col-lg-6">
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Jumlah Dibutuhkan</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control form-control-sm" id="jumlah_dibutuhkan" name="jumlah_dibutuhkan" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                                                        echo $ddata['jumlah_dibutuhkan'];
                                                                                                                                                                    } ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Tanggal Dibutuhkan</label>
                                                <div class="col-sm-9">
                                                    <input type="date" class="form-control form-control-sm" id="tanggal_dibutuhkan" name="tanggal_dibutuhkan" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                                                            echo $ddata['tanggal_dibutuhkan'];
                                                                                                                                                                        } ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Deskripsi</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control form-control-sm" id="deskripsi" name="deskripsi" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                                        echo $ddata['deskripsi'];
                                                                                                                                                    } ?>" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <h2>Project Status</h2>
                                        <div class="col-lg-6">
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Nama Project</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control form-control-sm" id="nama_project" name="nama_project" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                                                echo $ddata['nama_project'];
                                                                                                                                                            } ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Nama Customer</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control form-control-sm" id="nama_customer" name="nama_customer" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                                                echo $ddata['nama_customer'];
                                                                                                                                                            } ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Kode Project</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control form-control-sm" id="project_code" name="project_code" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                                                echo $ddata['project_code'];
                                                                                                                                                            } ?>" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- KANAN -->
                                        <h2>Requirement</h2>
                                        <div class="col-lg-6">
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Rekrutmen <b>*</b></label>
                                                <div class="col-sm-9">
                                                    <select class="form-control" name="status_rekrutmen" id="status_rekrutmen" readonly>
                                                        <option><?php echo ucwords($ddata['status_rekrutmen']); ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3" id="status_rek_edit">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Karyawan <b>*</b></label>
                                                <div class="col-sm-9">
                                                    <select class="form-control" name="status_karyawan" id="status_karyawan" readonly>
                                                        <option><?php echo ucwords($ddata['status_karyawan']); ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Apakah Ada Di MPP <b>*</b></label>
                                                <div class="col-sm-9">
                                                    <select class="form-control" name="mpp" id="mpp" readonly>
                                                        <option><?php echo ucwords($ddata['mpp']); ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Jenis Kelamin <b>*</b></label>
                                                <div class="col-sm-9">
                                                    <select class="form-control" name="jenis_kelamin" id="jenis_kelamin" readonly>
                                                        <option><?php echo ucwords($ddata['jenis_kelamin']); ?></option>
                                                        <option value="Laki-Laki">Laki-Laki</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Usia</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control form-control-sm" id="usia" name="usia" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                                echo $ddata['usia'];
                                                                                                                                            } ?>" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- KANAN -->
                                        <div class="col-lg-6">
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Fakultas/Jurusan</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control form-control-sm" id="jurusan" name="jurusan" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                                    echo $ddata['jurusan'];
                                                                                                                                                } ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Pendidikan Minimal <b>*</b></label>
                                                <div class="col-sm-9">
                                                    <select class="form-control" name="pendidikan_minimal" id="pendidikan_minimal" readonly>
                                                        <option><?php echo ucwords($ddata['pendidikan_minimal']); ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Pengalaman Minimal</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control form-control-sm" id="pengalaman_minimal" name="pengalaman_minimal" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                                                            echo $ddata['pengalaman_minimal'];
                                                                                                                                                                        } ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Kompetensi Teknis Yang Harus Dikuasai</label>
                                                <div class="col-sm-9">
                                                    <textarea type="text" class="form-control" id="kompetensi_teknis" name="kompetensi_teknis" rows="2" readonly><?php if ($_GET['act'] == 'edit') {
                                                                                                                                                                        echo $ddata['kompetensi_teknis'];
                                                                                                                                                                    } ?></textarea>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Kompetensi Non Teknis Yang Harus Dikuasai</label>
                                                <div class="col-sm-9">
                                                    <textarea type="text" class="form-control" id="kompetensi_non_teknis" name="kompetensi_non_teknis" rows="2" readonly><?php if ($_GET['act'] == 'edit') {
                                                                                                                                                                                echo $ddata['kompetensi_non_teknis'];
                                                                                                                                                                            } ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <h2>Offering</h2>
                                        <div class="col-lg-6">
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Kandidat <b>*</b></label>
                                                <div class="col-sm-9">
                                                    <select class="form-control" name="kandidat" id="kandidat" readonly>
                                                        <option><?php echo ucwords($ddata['kandidat']); ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Internal <b>*</b></label>
                                                <div class="col-sm-9">
                                                    <select class="form-control" name="internal" id="internal" readonly>
                                                        <?php if ($_GET['act'] == "edit") { ?>
                                                            <?php $query = $DBCOBA->get_sql("SELECT * FROM sa_reqreq where id=" . $ddata['id'] . ""); ?>
                                                            <option value="<?php echo $query[0]['internal']; ?>" readonly><?php echo $query[0]['internal']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan</label>
                                                <div class="col-sm-9">
                                                    <textarea type="text" class="form-control form-control-sm" id="catatan" name="catatan" rows="2" readonly><?php if ($_GET['act'] == 'edit') {
                                                                                                                                                                    echo $ddata['catatan'];
                                                                                                                                                                } ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- KANAN -->
                                        <div class="col-lg-6">
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Diisi Oleh HCM </label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control form-control-sm" id="diisi_oleh_hcm" name="diisi_oleh_hcm" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                                                    echo $ddata['diisi_oleh_hcm'];
                                                                                                                                                                } ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Range Salary <b>*</b></label>
                                                <div class="col-sm-9">
                                                    <input type="number" class="form-control form-control-sm" id="range_salary" name="range_salary" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                                                echo $ddata['range_salary'];
                                                                                                                                                            } ?>" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <h2>Approval</h2>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Approval GM <b>*</b></label>
                                            <div class="col-sm-9">
                                                <select class="form-control" name="gm" id="gm">
                                                    <?php if ($_GET['act'] == "edit") { ?>
                                                        <?php $query = $DBCOBA->get_sql("SELECT * FROM sa_reqreq where id=" . $ddata['id'] . ""); ?>
                                                        <option value="<?php echo $query[0]['gm']; ?>" readonly><?php echo $query[0]['gm']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3" id="gm_edit" <?php if ($ddata['gm'] == $_SESSION['Microservices_UserEmail']) { ?>>
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status GM <b>*</b></label>
                                            <div class="col-sm-9">
                                                <?php if ($_GET['act'] == "edit" && $ddata['status_gm'] == "Pending" || $_GET['act'] == "edit" && $ddata['status_gm'] == null) { ?>
                                                    <?php if ($ddata['gm'] == $_SESSION['Microservices_UserEmail']) { ?>
                                                        <!-- approve hanya bisa GM -->
                                                        <select class="form-control" name="status_gm" id="status_gm">
                                                            <option><?php echo ucwords($ddata['status_gm']); ?></option>
                                                            <option value="Approve">Approve</option>
                                                            <option value="Disapprove">Disapprove</option>
                                                        </select>
                                                    <?php } ?>
                                                <?php } elseif ($_GET['act'] == "edit" && $ddata['status_gm'] == "Approve" || $_GET['act'] == "edit" && $ddata['status_gm'] == null) { ?>
                                                    <select class="form-control" name="status_gm" id="status_gm">
                                                        <option><?php echo ucwords($ddata['status_gm']); ?></option>
                                                    </select>
                                                <?php } elseif ($_GET['act'] == "edit" && $ddata['status_gm'] == "Disapprove" || $_GET['act'] == "edit" && $ddata['status_gm'] == null) { ?>
                                                    <select class="form-control" name="status_gm" id="status_gm" readonly>
                                                        <option><?php echo ucwords($ddata['status_gm']); ?></option>
                                                    </select>
                                                <?php } ?>
                                            <?php } ?>
                                            </div>
                                        </div>
                                        <div class="row mb-3" id="catatan-gm-row" <?php if ($ddata['gm'] == $_SESSION['Microservices_UserEmail']) { ?>>
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan GM</label>
                                            <div class="col-sm-9">
                                                <textarea type="text" class="form-control form-control-sm" id="catatan_gm" name="catatan_gm" rows="2"><?php if ($_GET['act'] == 'edit') {
                                                                                                                                                            echo $ddata['catatan_gm'];
                                                                                                                                                        } ?></textarea>
                                            <?php } ?>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Approval GM HCM <b>*</b></label>
                                            <div class="col-sm-9">
                                                <select class="form-control" name="gm_hcm" id="gm_hcm">
                                                    <?php if ($_GET['act'] == "edit") { ?>
                                                        <?php $query = $DBCOBA->get_sql("SELECT * FROM sa_reqreq where id=" . $ddata['id'] . ""); ?>
                                                        <option value="<?php echo $query[0]['gm_hcm']; ?>"><?php echo $query[0]['gm_hcm']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3" <?php if ($ddata['gm_hcm'] == $_SESSION['Microservices_UserEmail']) { ?>>
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status GM HCM <b>*</b></label>
                                            <div class="col-sm-9">
                                                <?php if ($_GET['act'] == "edit" && $ddata['status_gm'] == "Pending" || $_GET['act'] == "edit" && $ddata['status_gm'] == null) { ?>
                                                    <?php if ($ddata['gm_hcm'] == $_SESSION['Microservices_UserEmail']) { ?>
                                                        <!-- approve hanya bisa GM HCM -->
                                                        <select class="form-control" name="status_gm_hcm" id="status_gm_hcm">
                                                            <option><?php echo ucwords($ddata['status_gm_hcm']); ?></option>
                                                            <option value="Approve">Approve</option>
                                                            <option value="Disapprove">Disapprove</option>
                                                        </select>
                                                    <?php } ?>
                                                <?php } elseif ($_GET['act'] == "edit" && $ddata['status_gm_hcm'] == "Approve" || $_GET['act'] == "edit" && $ddata['status_gm_hcm'] == null) { ?>
                                                    <select class="form-control" name="status_gm_hcm" id="status_gm_hcm">
                                                        <option><?php echo ucwords($ddata['status_gm_hcm']); ?></option>
                                                    </select>
                                                <?php } elseif ($_GET['act'] == "edit" && $ddata['status_gm_hcm'] == "Disapprove" || $_GET['act'] == "edit" && $ddata['status_gm_hcm'] == null) { ?>
                                                    <select class="form-control" name="status_gm_hcm" id="status_gm_hcm" readonly>
                                                        <option><?php echo ucwords($ddata['status_gm_hcm']); ?></option>
                                                    </select>
                                                <?php } ?>
                                            <?php } ?>
                                            </div>
                                        </div>
                                        <div class="row mb-3" id="catatan-gm-hcm-row" <?php if ($ddata['gm_hcm'] == $_SESSION['Microservices_UserEmail']) { ?>>
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan GM HCM</label>
                                            <div class="col-sm-9">
                                                <textarea type="text" class="form-control form-control-sm" id="catatan_gm_hcm" name="catatan_gm_hcm" rows="2"><?php if ($_GET['act'] == 'edit') {
                                                                                                                                                                    echo $ddata['catatan_gm_hcm'];
                                                                                                                                                                } ?></textarea>
                                            <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6" id="approval_gm_bod_section">
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Approval GM BOD<b>*</b></label>
                                            <div class="col-sm-9">
                                                <select class="form-control" name="gm_bod" id="gm_bod">
                                                    <?php if ($_GET['act'] == "edit") { ?>
                                                        <?php $query = $DBCOBA->get_sql("SELECT * FROM sa_reqreq where id=" . $ddata['id'] . ""); ?>
                                                        <option value="<?php echo $query[0]['gm_bod']; ?>"><?php echo $query[0]['gm_bod']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3" <?php if ($ddata['gm_bod'] == $_SESSION['Microservices_UserEmail']) { ?>>
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status GM BOD <b>*</b></label>
                                            <div class="col-sm-9">
                                                <?php if ($_GET['act'] == "edit" && $ddata['status_gm_bod'] == "Pending" || $_GET['act'] == "edit" && $ddata['status_gm_bod'] == null) { ?>
                                                    <?php if ($ddata['gm_bod'] == $_SESSION['Microservices_UserEmail']) { ?>
                                                        <!-- approve hanya bisa GM HCM -->
                                                        <select class="form-control" name="status_gm_bod" id="status_gm_bod">
                                                            <option><?php echo ucwords($ddata['status_gm_bod']); ?></option>
                                                            <option value="Approve">Approve</option>
                                                            <option value="Disapprove">Disapprove</option>
                                                        </select>
                                                    <?php } ?>
                                                <?php } elseif ($_GET['act'] == "edit" && $ddata['status_gm_bod'] == "Approve" || $_GET['act'] == "edit" && $ddata['status_gm_bod'] == null) { ?>
                                                    <select class="form-control" name="status_gm_bod" id="status_gm_bod" readonly>
                                                        <option><?php echo ucwords($ddata['status_gm_bod']); ?></option>
                                                    </select>
                                                <?php } elseif ($_GET['act'] == "edit" && $ddata['status_gm_bod'] == "Disapprove" || $_GET['act'] == "edit" && $ddata['status_gm_bod'] == null) { ?>
                                                    <select class="form-control" name="status_gm_bod" id="status_gm_bod" readonly>
                                                        <option><?php echo ucwords($ddata['status_gm_bod']); ?></option>
                                                    </select>
                                                <?php } ?>
                                            <?php } ?>
                                            </div>
                                        </div>
                                        <div class="row mb-3" id="catatan-gm-bod-row" <?php if ($ddata['gm_bod'] == $_SESSION['Microservices_UserEmail']) { ?>>
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan GM BOD</label>
                                            <div class="col-sm-9">
                                                <textarea type="text" class="form-control form-control-sm" id="catatan_gm_bod" name="catatan_gm_bod" rows="2"><?php if ($_GET['act'] == 'edit') {
                                                                                                                                                                    echo $ddata['catatan_gm_bod'];
                                                                                                                                                                } ?></textarea>
                                            <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
                <input type="submit" class="btn btn-secondary" name="cancel" value="Cancel">
                <?php if (isset($_GET['act']) && $_GET['act'] == 'edit') { ?>
                    <input type="submit" class="btn btn-primary" name="edit" value="Save">
                <?php } elseif (isset($_GET['act']) && $_GET['act'] == 'add') { ?>
                    <input type="submit" class="btn btn-primary" name="add" value="Save">
                <?php } ?>
                </form>

                <script>
                    const kandidat = document.getElementById('kandidat');
                    const internal = document.getElementById('internal');
                    kandidat.addEventListener('change', function handleChange(event) {
                        if (event.target.value === 'Internal') {
                            internal.style.visibility = 'visible';
                        } else {
                            internal.style.visibility = 'hidden';
                        }
                    });
                </script>

                <!-- status rekrutmen penggantian -->
                <script>
                    const status_rekrutmen = document.getElementById('status_rekrutmen');
                    const gm_bod = document.getElementById('gm_bod_add'); // Replace 'div1' with the actual ID of your first div
                    const stat_bod = document.getElementById('status_gm_bod_add'); // Replace 'div2' with the actual ID of your second div

                    status_rekrutmen.addEventListener('change', function handleChange(event) {
                        if (event.target.value === 'Penggantian' || event.target.value === 'Internship') {
                            gm_bod.style.visibility = 'hidden'; // Hide the first div
                            stat_bod.style.visibility = 'hidden'; // Hide the second div
                        } else {
                            gm_bod.style.visibility = 'visible'; // Show the first div
                            stat_bod.style.visibility = 'visible'; // Show the second div
                        }
                    });
                </script>


                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        var statrekedit = document.getElementById("stat_rek_edit");
                        var gmbod = document.getElementById("gm_bod_edit");
                        var statbod = document.getElementById("status_gm_bod");


                        function updateVisibility() {
                            if (statrekedit.value === "Penggantian" || statrekedit.value === "Internship") {
                                gmbod.style.display = "block";
                                statbod.style.display = "block";
                            } else {
                                gmbod.style.display = "none";

                            }
                        }


                        updateVisibility();


                        statrekedit.addEventListener("change", function() {
                            updateVisibility();
                        });
                    });
                </script>



                <script>
                    // ini bisa jika status selain penambahan maka bagian bod akan hide
                    // Function to toggle the visibility of the "Approval GM BOD" section based on the selected value
                    function toggleApprovalSection() {
                        var statusRekrutmen = document.getElementById("status_rekrutmen");
                        var gmBodSection = document.getElementById("approval_gm_bod_section");

                        if (statusRekrutmen.value === "Internship" || statusRekrutmen.value === "Penggantian") {
                            gmBodSection.style.display = "none"; // Hide the section
                        } else {
                            gmBodSection.style.display = "block"; // Show the section
                        }
                    }

                    // Add an event listener to the "Status Rekrutmen" dropdown to trigger the function
                    document.getElementById("status_rekrutmen").addEventListener("change", toggleApprovalSection);

                    // Initial call to set the initial visibility state
                    toggleApprovalSection();
                </script>


                <!-- jika stat gm pending maka stat gm hcm tidak muncul -->
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        var statusGmDropdown = document.getElementById("gm_edit");
                        var statusGmBodDiv = document.getElementById("status_gm_hcm");

                        function updateVisibility() {
                            if (statusGmDropdown.value === "Pending" || statusGmDropdown.value === "Disapprove") {
                                statusGmBodDiv.style.display = "none";
                            } else {
                                statusGmBodDiv.style.display = "block";
                            }
                        }

                        updateVisibility();

                        statusGmDropdown.addEventListener("change", function() {
                            updateVisibility();
                        });
                    });
                </script>

                <!-- jika stat gm hcm pending maka stat gm bod tidak muncul -->
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        var statusGmDropdown = document.getElementById("status_gm_hcm_edit");
                        var statusGmBodDiv = document.getElementById("status_gm_bod");

                        // Function to update visibility based on the initial value of status_gm
                        function updateVisibility() {
                            if (statusGmDropdown.value === "Pending" || statusGmDropdown.value === "Disapprove") {
                                statusGmBodDiv.style.display = "none";
                            } else {
                                statusGmBodDiv.style.display = "block";
                            }
                        }

                        // Call the function to set the initial visibility
                        updateVisibility();

                        // Listen for changes in the "Status GM" dropdown
                        statusGmDropdown.addEventListener("change", function() {
                            updateVisibility();
                        });
                    });
                </script>



                <script>
                    // "Catatan GM"
                    $(document).ready(function() {
                        var statusGmSelect = $("#status_gm");
                        var catatanGmRow = $("#catatan-gm-row");

                        statusGmSelect.change(function() {
                            var selectedStatus = statusGmSelect.val();
                            if (selectedStatus === "Pending") {
                                catatanGmRow.hide();
                            } else {
                                catatanGmRow.show();
                            }
                        });

                        // Trigger the change event on page load to set the initial state.
                        statusGmSelect.trigger("change");

                        // "Catatan GM HCM"
                        var statusGmHCMSelect = $("#status_gm_hcm");
                        var catatanGmHcmRow = $("#catatan-gm-hcm-row");

                        statusGmHCMSelect.change(function() {
                            var selectedStatus = statusGmHCMSelect.val();
                            if (selectedStatus === "Pending") {
                                catatanGmHcmRow.hide();
                            } else {
                                catatanGmHcmRow.show();
                            }
                        });

                        // Trigger the change event on page load to set the initial state.
                        statusGmHCMSelect.trigger("change");

                        // "Catatan GM BOD"
                        var statusGmBODSelect = $("#status_gm_bod");
                        var catatanGmbodRow = $("#catatan-gm-bod-row");

                        statusGmBODSelect.change(function() {
                            var selectedStatus = statusGmBODSelect.val();
                            if (selectedStatus === "Pending") {
                                catatanGmbodRow.hide();
                            } else {
                                catatanGmbodRow.show();
                            }
                        });

                        // Trigger the change event on page load to set the initial state.
                        statusGmBODSelect.trigger("change");
                    });
                </script>