<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

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

                                        <?php if (isset($_GET['divisi'])) {
                                            $namadivisi = $DBHCM->get_data("view_employees", "employee_email IS NOT NULL AND organization_name LIKE '%" . $_GET['divisi'] . "%'"); ?>
                                            <option value="<?php echo $namadivisi[0]['organization_name']; ?>"><?php echo $namadivisi[0]['organization_name']; ?></option>
                                        <?php } ?>
                                        <option></option>
                                        <?php do { ?>
                                            <option><?php echo $nariknamadivisi[0]['organization_name']; ?></option>
                                        <?php } while ($nariknamadivisi[0] = $nariknamadivisi[1]->fetch_assoc()); ?>
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

                                <select class="form-control" name="mpp" id="mpp">
                                    <option></option>
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>

                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Jenis Kelamin <b>*</b></label>
                            <div class="col-sm-9">

                                <select class="form-control" name="jenis_kelamin" id="jenis_kelamin">
                                    <option></option>
                                    <option value="None">None</option>
                                    <option value="Laki-Laki">Laki-Laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>

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
                                <select class="form-control" name="pendidikan_minimal" id="pendidikan_minimal">
                                    <option></option>
                                    <option value="SMA (High School)">SMA (High School)</option>
                                    <option value="D3 (Diploma)">D3 (Diploma)</option>
                                    <option value="S1 (Bachelor)">S1 (Bachelor)</option>
                                    <option value="S2 (Master)">S2 (Master)</option>
                                </select>
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
                        <div class="row mb-3" id="Internal-section">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Internal <b>*</b></label>
                            <div class="col-sm-9">
                                <select class="form-control" name="internal" id="internal">
                                    <?php $internal = $DBHCM->get_sqlV2("SELECT DISTINCT employee_email, employee_name FROM sa_view_employees"); ?>
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
                                    $malik = "ardi.haris@mastersystem.co.id";
                                    $get_structure = $DBHCM->get_sqlV2("SELECT * FROM sa_view_employees WHERE job_level = 2 AND employee_email = '$user' AND organization_name LIKE '%" . $_GET['divisi'] . "%'");
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
                    <div class="col-lg-6" id="approval-bod-section">
                        <div class="row mb-3">
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
                        <div class="row mb-3">
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
if ($_GET['act'] == 'view') {
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
                                        <input type="text" class="form-control form-control-sm" id="id" name="id" value="<?php echo $ddata['id']; ?>">
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
                                        <input type="text" readonly class="form-control form-control-sm" id="jumlah_dibutuhkan" name="jumlah_dibutuhkan" value="<?php echo $ddata['jumlah_dibutuhkan']; ?>">
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
                            <div class="row mb-3">
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
                            <div class="row mb-3" id="gm_edit">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status GM <b>*</b></label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="status_gm" id="status_gm" readonly>
                                        <option><?php echo $ddata['status_gm']; ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3" id="catatan-gm-row">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan GM</label>
                                <div class="col-sm-9">
                                    <textarea type="text" class="form-control form-control-sm" id="catatan_gm" name="catatan_gm" rows="2" readonly> <?php echo $ddata['catatan_gm']; ?> </textarea>
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
                                    <select class="form-control" name="status_gm_hcm" id="status_gm_hcm" readonly>
                                        <option><?php echo $ddata['status_gm_hcm']; ?><?php echo $ddata['status_gm_hcm']; ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3" id="catatan-gm-hcm-row">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan GM HCM <b>*</b></label>
                                <div class="col-sm-9">
                                    <textarea type="text" class="form-control form-control-sm" id="catatan_gm_hcm" name="catatan_gm_hcm" rows="2" readonly> <?php echo $ddata['catatan_gm_hcm']; ?> </textarea>
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
                                    <select class="form-control" name="status_gm_bod" id="status_gm_bod" readonly>
                                        <option><?php echo $ddata['status_gm_bod']; ?><?php echo $ddata['status_gm_bod']; ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3" id="catatan-gm-bod-row">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan GM BOD</label>
                                <div class="col-sm-9">
                                    <textarea type="text" class="form-control form-control-sm" id="catatan_gm_bod" name="catatan_gm_bod" rows="2" readonly> <?php echo $ddata['catatan_gm_bod']; ?> </textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php }
    if ($_GET['act'] == 'editapproval') {
        global $DBHCM;
        $condition = "id=" . $_GET['id'];
        $data = $DBHCM->get_data($tblname, $condition);
        $ddata = $data[0];
        $qdata = $data[1];
        $tdata = $data[2];

        ?>
            <form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="row mb-3" hidden>
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Id</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm" id="id" name="id" value="<?php if ($_GET['act'] == 'editapproval') {
                                                                                                                        echo $ddata['id'];
                                                                                                                    } ?>" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Divisi</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm" id="divisi" name="divisi" value="<?php if ($_GET['act'] == 'editapproval') {
                                                                                                                                echo $ddata['divisi'];
                                                                                                                            } ?>" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Posisi</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm" id="posisi" name="posisi" value="<?php if ($_GET['act'] == 'editapproval') {
                                                                                                                                echo $ddata['posisi'];
                                                                                                                            } ?>" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Code</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm" id="project_code" name="project_code" value="<?php if ($_GET['act'] == 'editapproval') {
                                                                                                                                            echo $ddata['project_code'];
                                                                                                                                        } ?>" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Kandidat</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm" id="kandidat" name="kandidat" value="<?php if ($_GET['act'] == 'editapproval') {
                                                                                                                                    echo $ddata['kandidat'];
                                                                                                                                } ?>" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Jumlah Dibutuhkan</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm" id="jumlah_dibutuhkan" name="jumlah_dibutuhkan" value="<?php if ($_GET['act'] == 'editapproval') {
                                                                                                                                                    echo $ddata['jumlah_dibutuhkan'];
                                                                                                                                                } ?>" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">

                        <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Karyawan</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm" id="status_karyawan" name="status_karyawan" value="<?php if ($_GET['act'] == 'editapproval') {
                                                                                                                                                echo $ddata['status_karyawan'];
                                                                                                                                            } ?>" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">kompetensi teknis</label>
                            <div class="col-sm-9">
                                <textarea type="text" class="form-control" id="kompetensi_teknis" name="kompetensi_teknis" rows="2" readonly><?php if ($_GET['act'] == 'editapproval') {
                                                                                                                                                    echo $ddata['kompetensi_teknis'];
                                                                                                                                                } ?></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Request</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm" id="status_request" name="status_request" value="<?php if ($_GET['act'] == 'editapproval') {
                                                                                                                                                echo $ddata['status_request'];
                                                                                                                                            } ?>" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Assign <b>*</b></label>
                            <div class="col-sm-9">
                                <select class="form-control" name="assign_requirement" id="assign_requirement">
                                    <?php if ($_GET['act'] == "editapproval" && $ddata['assign_requirement'] == null) { ?>
                                        <?php $gmbod = $DBHCM->get_sqlV2("SELECT * FROM sa_view_employees WHERE job_level='4' AND resign_date is null and `job_structure` LIKE '%JW ATN WM HCM Recruitment%'"); ?>
                                        <option></option>
                                        <option value="malik@mastersystem.co.id">malik@mastersystem.co.id</option>
                                        <?php while ($row = $gmbod[1]->fetch_assoc()) { ?>
                                            <option value="<?php echo $row['employee_email']; ?>">
                                                <?php echo $row['employee_name'] . " (" . $row['organization_name'] . ")"; ?>
                                            </option>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <option><?php echo ucwords($ddata['assign_requirement']); ?></option>
                                </select>
                            <?php } ?>
                            </select>
                            </div>
                        </div>
                        <!-- <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm"></label>
                            <div class="col-sm-9">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahAssignFormModal">
                                    New Assign Recruitment
                                </button>
                            </div>
                        </div> -->
                    </div>
                </div>
            <?php }
        if ($_GET['act'] == 'editshare') {
            global $DBHCM;
            $condition = "id=" . $_GET['id'];
            $data = $DBHCM->get_data($tblname, $condition);
            $ddata = $data[0];
            $qdata = $data[1];
            $tdata = $data[2];

            ?>
                <form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row mb-3" hidden>
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Id</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" id="id" name="id" value="<?php if ($_GET['act'] == 'editshare') {
                                                                                                                            echo $ddata['id'];
                                                                                                                        } ?>" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Divisi</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" id="divisi" name="divisi" value="<?php if ($_GET['act'] == 'editshare') {
                                                                                                                                    echo $ddata['divisi'];
                                                                                                                                } ?>" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Posisi</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" id="posisi" name="posisi" value="<?php if ($_GET['act'] == 'editshare') {
                                                                                                                                    echo $ddata['posisi'];
                                                                                                                                } ?>" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Kandidat</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" id="kandidat" name="kandidat" value="<?php if ($_GET['act'] == 'editshare') {
                                                                                                                                        echo $ddata['kandidat'];
                                                                                                                                    } ?>" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Kode Project</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" id="project_code" name="project_code" value="<?php if ($_GET['act'] == 'editshare') {
                                                                                                                                                echo $ddata['project_code'];
                                                                                                                                            } ?>" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Jumlah Dibutuhkan</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" id="jumlah_dibutuhkan" name="jumlah_dibutuhkan" value="<?php if ($_GET['act'] == 'editshare') {
                                                                                                                                                        echo $ddata['jumlah_dibutuhkan'];
                                                                                                                                                    } ?>" readonly>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Share <b>*</b></label>
                                <div class="col-sm-9">
                                    <?php if ($_GET['act'] == "editshare") { ?>
                                        <select class="form-control" name="share" id="share" readonly>
                                            <option value="CV Kandidat">CV Kandidat</option>
                                            <option value="<?php echo $ddata['share']; ?>"><?php echo $ddata['share']; ?></option>
                                        </select>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm"></label>
                                <div class="col-sm-9">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahLinkFormModal">
                                        New Recruitment Source
                                    </button>
                                </div>
                            </div>
                            <script>
                                var ProjectCode = document.getElementById("project_code").value;
                                document.cookie = "ProjectCode = " + ProjectCode;
                                var Department = document.getElementById('divisi').value;
                                document.cookie = "Department = " + Department;
                                var Posisi = document.getElementById('posisi').value;
                                document.cookie = "Posisi = " + Posisi;
                            </script>
                        </div>
                    </div>
                    <?php
                    global $DB;
                    $tblname = 'cfg_web';
                    $condition = 'config_key="MEDIA_REQUEST_REQUIREMENT" AND parent=8';
                    $folders = $DB->get_data($tblname, $condition);
                    $FolderName = 'request_requirement';
                    $sFolderTarget = $folders[0]['config_value'] . '/' . $_GET['ProjectCode'] . '/' . $FolderName . '/';
                    $sSubFolders = explode("/", $sFolderTarget);
                    $xFolder = "";
                    for ($i = 0; $i < count($sSubFolders); $i++) {
                        if ($i == 0) {
                            $xFolder .= $sSubFolders[$i];
                        } else {
                            $xFolder .= '/' . $sSubFolders[$i];
                        }
                        if ($sSubFolders[$i] != "..") {
                            if (!(is_dir($xFolder))) {
                                mkdir($xFolder, 0777, true);
                                $file = 'media/documents/projects/index.php';
                                $newfile = $xFolder . '/index.php';
                                // isset($file, $newfile);
                                // if (!copy($file, $newfile)) {
                                // 	echo "";
                                // }
                            }
                        }
                    }
                    ?>

                    <script>
                        var FolderTarget = "<?php echo $sFolderTarget; ?>";
                        document.cookie = "FolderTarget = " + FolderTarget;
                    </script>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row mb-3">
                                <?php if ($_GET['act'] == 'editshare') { ?>
                                    <button type="button" class="btn btn-primary" name="btn_upload" id="btn_upload" data-bs-toggle="modal" data-bs-target="#fileupload">Upload File</button>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div id="fileList"></div>
                        </div>
                        <div class="col-lg-12">
                            <?php
                            $d = dir($sFolderTarget);
                            // echo "Handle: " . $d->handle . "<br/>";
                            // echo "Path: " . $d->path . "<br/>";
                            // echo '<div class="list-group">';
                            ?>
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nama File</th>
                                        <th scope="col">Size</th>
                                        <!-- <th scope="col">Created</th> -->
                                        <th scope="col">Modified</th>
                                        <th scope="col">Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 0;
                                    while (false !== ($entry = $d->read())) {
                                        if ($entry != '.' && $entry != '..' && $entry != 'index.php') {
                                            $fstat = stat($sFolderTarget . $entry);
                                            $filenote = $DBHCM->get_sql("SELECT * from sa_hcm_notecv where file = '" . $entry . "'");
                                            // $filenotemalik = $filenote[0]['notes'];
                                            // var_dump($filenotemalik);
                                            // die;
                                    ?>
                                            <?php //if($entry = $_GET['cr_no']) {
                                            ?>
                                            <tr>
                                                <th scope="row"><?php echo $i + 1; ?></th>
                                                <input type="hidden" name="malik[]" value="<?php echo $entry; ?>">
                                                <td><a href="<?php echo $sFolderTarget . $entry; ?>" target="_blank" class="text-body"><?php echo $entry; ?></a></td>
                                                <td>
                                                    <?php
                                                    if ($fstat['size'] < 1024) {
                                                        echo number_format($fstat['size'], 2) . ' B';
                                                    } elseif ($fstat['size'] < (1024 * 1024)) {
                                                        echo number_format($fstat['size'] / 1024, 2) . ' KB';
                                                    } elseif ($fstat['size'] < (1024 * 1024 * 1024)) {
                                                        echo number_format($fstat['size'] / (1024 * 1024), 2) . ' MB';
                                                    } elseif ($fstat['size'] < (1024 * 1024 * 1024 * 1024)) {
                                                        echo number_format($fstat['size'] / (1024 * 1024 * 1024), 2) . ' GB';
                                                    }
                                                    ?>
                                                </td>
                                                <!-- <td><?php echo date('d-M-Y G:i:s', $fstat['ctime']); ?></td> -->
                                                <td><?php echo date('d-M-Y G:i:s', $fstat['mtime']); ?></td>
                                                <td>
                                                    <?php if ($_GET['act'] == "editshare") { ?>
                                                        <?php if ($filenote !== null && isset($filenote[0]['file'])) { ?>
                                                            <textarea readonly class="form-control form-control-sm" name="notes[]" id="notes[]"><?php echo $filenote[0]['notes']; ?></textarea>
                                                        <?php } else { ?>
                                                            <textarea class="form-control form-control-sm" name="notes[]" id="notes[]"></textarea>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php
                                            // echo '<a href="'.$sFolderTarget.$entry.'" target="_blank" class="list-group-item list-group-item-action">'.$entry.'</a>';
                                            $i++;
                                        }
                                    }
                                    if ($i == 0) {
                                        ?>
                                        <tr>
                                            <td colspan="5">No Files available.</td>
                                        </tr>
                                    <?php
                                        // echo 'No files available.';
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <?php
                            // echo '</div>';
                            $d->close();
                            ?>
                        </div>
                    </div>
                <?php } ?>
                <input type="submit" class="btn btn-secondary" name="cancel" value="Cancel">
                <?php if (isset($_GET['act']) && $_GET['act'] == 'edit') { ?>
                    <input type="submit" class="btn btn-primary" name="save" value="Save">
                <?php } elseif (isset($_GET['act']) && $_GET['act'] == 'add') { ?>
                    <input type="submit" class="btn btn-primary" name="add" value="Save">
                <?php } elseif (isset($_GET['act']) && $_GET['act'] == 'editapproval') { ?>
                    <input type="submit" class="btn btn-primary" name="editapproval" value="Save">
                <?php } elseif (isset($_GET['act']) && $_GET['act'] == 'editshare') { ?>
                    <input type="submit" class="btn btn-primary" name="editshare" value="Save">
                    <input type="submit" class="btn btn-primary" name="complete" value="Complete">
                <?php } ?>
                </form>


                <div class="modal fade" id="fileupload" tabindex="-1" aria-labelledby="saveLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="saveLabel"><b>Upload File</b></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                <!-- <button type="button" class="btn-success" data-bs-dismiss="modal" aria-label="Save" name="malik"></button> -->
                            </div>
                            <div class="modal-body">
                                <div class="row mb-3">
                                    <!-- <div class="col-sm-12"> -->
                                    <link href="components/modules/upload/upload.css" rel="stylesheet" type="text/css" />
                                    <form id="upload_form" enctype="multipart/form-data" method="post" action="components/modules/upload/upload.php">
                                        <div>
                                            <div><label for="image_file">Please select image file</label></div>
                                            <div><input type="file" name="image_file" id="image_file" onchange="fileSelected();" /></div>
                                        </div>
                                        <div>
                                            <!-- <input type="text" value="<?php echo $ddata['divisi']; ?>"> -->
                                            <label for="">Recruitment Source</label>
                                            <select class="form-control" name="link_from" id="link_from">
                                                <?php $linkfrom = $DBHCM->get_sqlV2("SELECT * FROM sa_hcm_link_requirement"); ?>
                                                <option></option>
                                                <?php do { ?>
                                                    <option value="<?php echo $linkfrom[0]['link_from']; ?>"><?php echo $linkfrom[0]['link_from']; ?></option>
                                                <?php } while ($linkfrom[0] = $linkfrom[1]->fetch_assoc()); ?>
                                            </select>
                                        </div>
                                        <div>
                                            <input type="button" value="Upload" onclick="startUploading()" />
                                        </div>
                                        <div id="fileinfo">
                                            <div id="filename"></div>
                                            <div id="filesize"></div>
                                            <div id="filetype"></div>
                                            <div id="filedim"></div>
                                        </div>
                                        <div id="error">You should select valid image files only!</div>
                                        <div id="error2">An error occurred while uploading the file</div>
                                        <div id="abort">The upload has been canceled by the user or the browser dropped the connection</div>
                                        <div id="warnsize">Your file is very big. We can't accept it. Please select more small file</div>
                                        <div id="progress_info">
                                            <div id="progress"></div>
                                            <div id="progress_percent">&nbsp;</div>
                                            <div class="clear_both"></div>
                                            <div>
                                                <div id="speed">&nbsp;</div>
                                                <div id="remaining">&nbsp;</div>
                                                <div id="b_transfered">&nbsp;</div>
                                                <div class="clear_both"></div>
                                            </div>
                                            <div id="upload_response"></div>
                                        </div>
                                    </form>
                                    <img id="preview" />
                                    <!-- </div> -->
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" name="malik">Save</button> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    $("#divisi").on('change', function() {
                        var divisi = $(this).val();

                        window.location = window.location.pathname + "?mod=hcm_requirement" + "&act=add&divisi=" + divisi;
                    })
                </script>

                <script>
                    // Inisialisasi Select2 pada elemen dengan ID 'status_karyawan'
                    $(document).ready(function() {
                        $('#status_karyawan').select2();
                    });

                    // Inisialisasi Select2 pada elemen dengan ID 'pendidikan_minimal'
                    $(document).ready(function() {
                        $('#internal').select2();
                    });
                </script>

                <script>
                    const kandidat = document.getElementById('kandidat');
                    const internal = document.getElementById('Internal-section');
                    kandidat.addEventListener('change', function handleChange(event) {
                        if (event.target.value === 'Internal') {
                            internal.style.visibility = 'visible';
                        } else {
                            internal.style.visibility = 'hidden';
                        }
                    });
                </script>

                <script>
                    const status_rekrutmen = document.getElementById('status_rekrutmen');
                    const approval_bod = document.getElementById('approval-bod-section');
                    status_rekrutmen.addEventListener('change', function handleChange(event) {
                        if (event.target.value === 'Penambahan') {
                            approval_bod.style.visibility = 'visible';
                        } else {
                            approval_bod.style.visibility = 'hidden';
                        }
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
                        var statusGmSelect = $("#status_gm");

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