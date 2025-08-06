<?php
if ($_GET['act'] == 'add') {
    global $DBHCM;
    $user = $_SESSION['Microservices_UserEmail'];
    $nariknamadivisi = $DBHCM->get_sqlV2("SELECT employee_name, employee_email, organization_name, job_level, job_title, job_structure, leader_name, leader_email FROM sa_view_employees WHERE employee_email IS NOT null AND resign_date IS null AND job_level = 2");
    $namaemployee    = $DBHCM->get_sqlV2("SELECT DISTINCT employee_name, employee_email FROM sa_view_employees_v2 WHERE resign_date IS NULL");
    // $user = $_SESSION['Microservices_UserEmail'];

    // $cobaan = $DBHCM->get_sqlV2("SELECT * FROM sa_view_employees WHERE job_level = 2 AND employee_email = ' . $user . '");

    // var_dump($cobaan);
    // die;


?>
    <form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
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
                                        <?php if ($_GET['act'] == "add") { ?>
                                            <?php if (isset($_GET['divisi'])) {
                                                $namadivisi = $DBHCM->get_data("view_employees", "employee_email IS NOT NULL AND organization_name LIKE '%" . $_GET['divisi'] . "%'"); ?>
                                                <option value="<?php echo $namadivisi[0]['organization_name']; ?>"><?php echo $namadivisi[0]['organization_name']; ?></option>
                                            <?php } ?>
                                            <option></option>
                                            <?php do { ?>
                                                <option><?php echo $nariknamadivisi[0]['organization_name']; ?></option>
                                            <?php } while ($nariknamadivisi[0] = $nariknamadivisi[1]->fetch_assoc()); ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Posisi <b>*</b></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" id="posisi" name="posisi">
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
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Deskripsi Pekerjaan</label>
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
                <div class="row">
                    <div class="row">
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
                                <select class="form-control" name="status_rekrutmen" id="status_rekrutmen">
                                    <option></option>
                                    <!-- <option value="Internship">Internship</option> -->
                                    <option value="Penambahan">Penambahan</option>
                                    <option value="Penggantian">Penggantian</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Karyawan <b>*</b></label>
                            <div class="col-sm-9">
                                <select class="form-control" name="status_karyawan" id="status_karyawan">
                                    <option></option>
                                    <option value="Kontrak">Kontrak</option>
                                    <option value="Percobaan">Percobaan</option>
                                    <option value="Tetap">Tetap</option>
                                    <option value="Outsourching">Outsourching</option>
                                    <option value="Magang">Magang</option>
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
            <div class="card-header fw-bold">Backround</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Kandidat <b>*</b></label>
                            <div class="col-sm-9">
                                <select class="form-control" name="kandidat" id="kandidat">
                                    <option></option>
                                    <option value="Internal">Internal</option>
                                    <option value="eksternal">Eksternal</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3" id="internal">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Internal <b>*</b></label>
                            <div class="col-sm-9">
                                <select class="form-control" name="internal" id="internal">
                                    <?php $internal = $DBHCM->get_sqlV2("SELECT * FROM sa_view_employees"); ?>
                                    <option></option>
                                    <option value="malik@mastersystem.co.id">malik@mastersystem.co.id</option>
                                    <?php while ($row = $internal[1]->fetch_assoc()) { ?>
                                        <option value="<?php echo $row['employee_email']; ?>">
                                            <?php echo $row['employee_name'] . " (" . $row['employee_email'] . ")"; ?>
                                        </option>
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
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan Lain </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm" id="diisi_oleh_hcm" name="diisi_oleh_hcm">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Range Salary <b>*</b></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm" id="range_salary" name="range_salary">
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
                                    <?php if (isset($_GET['divisi'])) {
                                        $gm = $DBHCM->get_data("view_employees", "employee_email IS NOT NULL AND organization_name LIKE '%" . $_GET['divisi'] . "%' AND job_level = 2"); ?>
                                        <option value="<?php echo $gm[0]['employee_email']; ?>"><?php echo $gm[0]['employee_name'] . " (" . $gm[0]['employee_email'] . ") ";  ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status GM <b>*</b></label>
                            <div class="col-sm-9">
                                <select class="form-control" name="status_gm" id="status_gm">
                                    <?php
                                    // $user = "syamsul@mastersystem.co.id";
                                    $get_structure = $DBHCM->get_sqlV2("SELECT * FROM sa_view_employees WHERE job_level = 4 AND employee_email = '" . $user . "' AND divisi = '" . $_GET['divisi'] . "'");
                                    if ($get_structure[2] > 0) {
                                    ?>
                                        <option value="Approve">Approve</option>
                                    <?php } else { ?>
                                        <option value="Pending">Pending</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Approval GM HCM <b>*</b></label>
                            <div class="col-sm-9">
                                <select class="form-control" name="gm_hcm" id="gm_hcm">
                                    <?php $gmhcm = $DBHCM->get_sqlV2("SELECT * FROM sa_view_employees_v2 WHERE department_name = 'Human Capital Management' AND job_level='2'"); ?>
                                    <option></option>
                                    <option value="malik@mastersystem.co.id">malik@mastersystem.co.id</option>
                                    <?php //do { 
                                    ?>
                                    <option value="<?php echo $gmhcm[0]['employee_email']; ?>"><?php echo $gmhcm[0]['employee_name'] . " (" . $gmhcm[0]['employee_email'] . ")"; ?></option>
                                    <?php //} while ($gmhcm[0] = $gmhcm[1]->fetch_assoc()); 
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status GM HCM<b>*</b></label>
                            <div class="col-sm-9">
                                <select class="form-control" name="status_gm_hcm" id="status_gm_hcm">
                                    <option value="Pending">Pending</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="row mb-3" id="gm_bod_add">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Approval BOD<b>*</b></label>
                            <div class="col-sm-9">
                                <select class="form-control" name="gm_bod" id="gm_bod">
                                    <?php $gmbod = $DBHCM->get_sqlV2("SELECT * FROM sa_view_employees_v2 WHERE job_level='1' AND resign_date is null and division_name IS NOT NULL"); ?>
                                    <option></option>
                                    <option value="malik@mastersystem.co.id">malik@mastersystem.co.id</option>
                                    <?php while ($row = $gmbod[1]->fetch_assoc()) { ?>
                                        <option value="<?php echo $row['employee_email']; ?>">
                                            <?php echo $row['employee_name'] . " (" . $row['division_name'] . ")"; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3" id="status_gm_bod_add">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status GM BOD<b>*</b></label>
                            <div class="col-sm-9">
                                <select class="form-control" name="status_gm_bod" id="status_gm_bod">
                                    <option></option>
                                    <option value="Pending">Pending</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php }
if ($_GET['act'] == 'edit') {
    global $DBHCM;
    $condition = "id=" . $_GET['id'];
    $data = $DBHCM->get_data($tblname, $condition);
    $ddata = $data[0];
    $qdata = $data[1];
    $tdata = $data[2];

    ?>
        <form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
            <div class="card shadow mb-1">
                <div class="card-header fw-bold">Department</div>
                <div class="card-body">
                    <div class="row">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="row mb-3" hidden>
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">ID</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control form-control-sm" id="id" name="id" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Divisi <b>*</b></label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="divisi" id="divisi" readonly>
                                            <option value="<?php echo $ddata['divisi']; ?>" readonly><?php echo $ddata['divisi']; ?></option>
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Posisi <b>*</b></label>
                                    <div class="col-sm-9">
                                        <input readonly type="text" class="form-control form-control-sm" id="posisi" name="posisi" value="<?php echo $ddata['posisi']; ?>">
                                    </div>
                                </div>
                            </div>
                            <!-- KANAN -->
                            <div class="col-lg-6">
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Jumlah Dibutuhkan</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control form-control-sm" id="jumlah_dibutuhkan" name="jumlah_dibutuhkan" value="<?php echo $ddata['jumlah_dibutuhkan']; ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Tanggal Dibutuhkan</label>
                                    <div class="col-sm-9">
                                        <input readonly type="date" class="form-control form-control-sm" id="tanggal_dibutuhkan" name="tanggal_dibutuhkan" value="<?php echo $ddata['tanggal_dibutuhkan']; ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Deskripsi Pekerjaan</label>
                                    <div class="col-sm-9">
                                        <input readonly type="text" class="form-control form-control-sm" id="deskripsi" name="deskripsi" value="<?php echo $ddata['deskripsi']; ?>">
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
                    <div class="row">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Nama Project</label>
                                    <div class="col-sm-9">
                                        <input readonly type="text" class="form-control form-control-sm" id="nama_project" name="nama_project" value="<?php echo $ddata['nama_project']; ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Nama Customer</label>
                                    <div class="col-sm-9">
                                        <input readonly type="text" class="form-control form-control-sm" id="nama_customer" name="nama_customer" value="<?php echo $ddata['nama_customer']; ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Code</label>
                                    <div class="col-sm-9">
                                        <input readonly type="text" class="form-control form-control-sm" id="project_code" name="project_code" value="<?php echo $ddata['project_code']; ?>">
                                    </div>
                                </div>
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
                                    <select class="form-control" name="status_rekrutmen" id="status_rekrutmen" readonly>
                                        <option value="<?php echo $ddata['status_rekrutmen']; ?>" readonly><?php echo $ddata['status_rekrutmen']; ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Karyawan <b>*</b></label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="status_karyawan" id="status_karyawan" readonly>
                                        <option value="<?php echo $ddata['status_karyawan']; ?>" readonly><?php echo $ddata['status_karyawan']; ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Apakah Ada Di MPP <b>*</b></label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="mpp" id="mpp" readonly>
                                        <option value="<?php echo $ddata['mpp']; ?>" readonly><?php echo $ddata['mpp']; ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Jenis Kelamin <b>*</b></label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="jenis_kelamin" id="jenis_kelamin" readonly>
                                        <option value="<?php echo $ddata['jenis_kelamin']; ?>" readonly><?php echo $ddata['jenis_kelamin']; ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Usia</label>
                                <div class="col-sm-9">
                                    <input readonly type="text" class="form-control form-control-sm" id="usia" name="usia" value="<?php echo $ddata['usia']; ?>">
                                </div>
                            </div>

                        </div>
                        <!-- KANAN -->
                        <div class="col-lg-6">
                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Pendidikan Minimal <b>*</b></label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="pendidikan_minimal" id="pendidikan_minimal" readonly>
                                        <option value="<?php echo $ddata['pendidikan_minimal']; ?>" readonly><?php echo $ddata['pendidikan_minimal']; ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Fakultas/Jurusan</label>
                                <div class="col-sm-9">
                                    <input readonly type="text" class="form-control form-control-sm" id="jurusan" name="jurusan" value="<?php echo $ddata['jurusan']; ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Pengalaman Minimal</label>
                                <div class="col-sm-9">
                                    <input readonly type="text" class="form-control form-control-sm" id="pengalaman_minimal" name="pengalaman_minimal" value="<?php echo $ddata['pengalaman_minimal']; ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Kompetensi Teknis Yang Harus Dikuasai</label>
                                <div class="col-sm-9">
                                    <textarea readonly type="text" class="form-control" id="kompetensi_teknis" name="kompetensi_teknis" rows="2"><?php echo $ddata['kompetensi_teknis']; ?></textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Kompetensi Non Teknis Yang Harus Dikuasai</label>
                                <div class="col-sm-9">
                                    <textarea readonly type="text" class="form-control" id="kompetensi_non_teknis" name="kompetensi_non_teknis" rows="2"><?php echo $ddata['kompetensi_non_teknis']; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow mb-1">
                <div class="card-header fw-bold">Backround</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Kandidat <b>*</b></label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="kandidat" id="kandidat" readonly>
                                        <option value="<?php echo $ddata['kandidat']; ?>"><?php echo $ddata['kandidat']; ?></option>
                                        <option value="Internal">Internal</option>
                                        <option value="Eksternal">Eksternal</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3" id="internal">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Internal <b>*</b></label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="internal" id="internal" readonly>
                                        <option value="<?php echo $ddata['internal']; ?>"><?php echo $ddata['internal']; ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan</label>
                                <div class="col-sm-9">
                                    <textarea readonly type="text" class="form-control form-control-sm" id="catatan" name="catatan" rows="2"><?php echo $ddata['catatan']; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <!-- KANAN -->
                        <div class="col-lg-6">
                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan Lain </label>
                                <div class="col-sm-9">
                                    <input readonly type="text" class="form-control form-control-sm" id="diisi_oleh_hcm" name="diisi_oleh_hcm" value="<?php echo $ddata['diisi_oleh_hcm']; ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Range Salary <b>*</b></label>
                                <div class="col-sm-9">
                                    <input readonly type="number" class="form-control form-control-sm" id="range_salary" name="range_salary" value="<?php echo $ddata['range_salary']; ?>">
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
                                    <select class="form-control" name="gm" id="gm" readonly>
                                        <option value="<?php echo $ddata['gm']; ?>"><?php echo $ddata['gm']; ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status GM <b>*</b></label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="status_gm" id="status_gm">
                                        <option value="<?php echo $ddata['status_gm']; ?>"><?php echo $ddata['status_gm']; ?></option>
                                        <option value="Pending">Pending</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan GM <b>*</b></label>
                                <div class="col-sm-9">
                                    <?php if ($ddata['catatan_gm'] == null) { ?>
                                        <textarea type="text" class="form-control form-control-sm" id="catatan_gm" name="catatan_gm" rows="2"></textarea>
                                    <?php } else { ?>
                                        <textarea readonly type="text" class="form-control form-control-sm" id="catatan_gm" name="catatan_gm" rows="2"><?php echo $ddata['catatan_gm']; ?></textarea>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Approval GM HCM <b>*</b></label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="gm_hcm" id="gm_hcm" readonly>
                                        <option value="<?php echo $ddata['gm_hcm']; ?>"><?php echo $ddata['gm_hcm']; ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status GM HCM <b>*</b></label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="status_gm_hcm" id="status_gm_hcm">
                                        <option value="<?php echo $ddata['status_gm_hcm']; ?>"><?php echo $ddata['status_gm_hcm']; ?></option>
                                        <option value="Pending">Pending</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan GM HCM <b>*</b></label>
                                <div class="col-sm-9">
                                    <?php if ($ddata['catatan_gm_hcm'] == null) { ?>
                                        <textarea type="text" class="form-control form-control-sm" id="catatan_gm_hcm" name="catatan_gm_hcm" rows="2"></textarea>
                                    <?php } else { ?>
                                        <textarea readonly type="text" class="form-control form-control-sm" id="catatan_gm_hcm" name="catatan_gm_hcm" rows="2"><?php echo $ddata['catatan_gm_hcm']; ?></textarea>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Approval GM bod <b>*</b></label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="gm_bod" id="gm_bod" readonly>
                                        <option value="<?php echo $ddata['gm_bod']; ?>"><?php echo $ddata['gm_bod']; ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status GM bod <b>*</b></label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="status_gm_bod" id="status_gm_bod">
                                        <option value="<?php echo $ddata['status_gm_bod']; ?>"><?php echo $ddata['status_gm_bod']; ?></option>
                                        <option value="Pending">Pending</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan GM bod <b>*</b></label>
                                <div class="col-sm-9">
                                    <?php if ($ddata['catatan_gm_bod'] == null) { ?>
                                        <textarea type="text" class="form-control form-control-sm" id="catatan_gm_bod" name="catatan_gm_bod" rows="2"></textarea>
                                    <?php } else { ?>
                                        <textarea readonly type="text" class="form-control form-control-sm" id="catatan_gm_bod" name="catatan_gm_bod" rows="2"><?php echo $ddata['catatan_gm_bod']; ?></textarea>
                                    <?php } ?>
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

        <script>
            $("#divisi").on('change', function() {
                var divisi = $(this).val();

                window.location = window.location.pathname + "?mod=hcm_requirement" + "&act=add&divisi=" + divisi;
            })
        </script>

        <script>
            // Fungsi untuk mengubah angka menjadi format uang rupiah dengan awalan "Rp."
            function formatRupiah(angka) {
                var number_string = angka.toString();
                var split = number_string.split(',');
                var sisa = split[0].length % 3;
                var rupiah = split[0].substr(0, sisa);
                var ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                // tambahkan titik jika yang di input lebih dari 3 digit
                if (ribuan) {
                    var separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = 'Rp. ' + rupiah; // Menambahkan awalan "Rp."
                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                return rupiah;
            }

            // Event listener untuk memformat input saat nilai berubah
            document.getElementById('range_salary').addEventListener('keyup', function() {
                var value = this.value;

                // Menghilangkan semua karakter kecuali digit
                value = value.replace(/[^\d]/g, '');

                // Memformat nilai menjadi uang rupiah dengan awalan "Rp."
                this.value = formatRupiah(value);
            });
        </script>