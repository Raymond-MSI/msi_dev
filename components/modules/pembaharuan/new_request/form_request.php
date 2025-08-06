<?php
// if($_GET['act']=='view') {
//         global $DB;
//         $condition = "id=" . $_GET['id'];
//         $data = $DB->get_data($tblname, $condition);
//         $ddata = $data[0];
//         $qdata = $data[1];
//         $tdata = $data[2];
//     }
global $DBREC;

$mdlname = "new_request";
$DBREC   = get_conn($mdlname);
$query   = $DBREC->get_sql("SELECT * FROM sa_trx_request_requirement");

$mdlname = "HCM";
$DBHCM = get_conn($mdlname);
// $nariknamadivisi = $DBHCM->get_data("view_department", "organization_name IS not NULL");
$user = $_SESSION['Microservices_UserEmail'];
$narikstatus = $DBREC->get_data("status_k", "status_karyawan is not null");
$trx_request_requirement = $DBREC->get_sqlV2("SELECT * FROM sa_trx_request_requirement");
//$nariknamadivisi = $DBHCM->get_sql("select * from sa_view_employees_v2 where employee_email = '" . $_SESSION['Microservices_UserEmail'] .  "'");
$nariknamadivisi = $DBHCM->get_sql("SELECT employee_name, employee_email, organization_name, job_level, job_title, job_structure, leader_name, leader_email FROM sa_view_employees WHERE employee_email is NOT null AND resign_date is null AND job_level = 2");
$nariknamaposisi = $DBHCM->get_data("mst_jobtitle", "job_title IS not NULL ORDER BY job_title ASC");
$namaemployee    = $DBHCM->get_sql("select DISTINCT employee_name, employee_email from sa_view_employees_v2 where resign_date is null");
// $deskripsi = $DBHCM->get_data("mst_organization","description IS not NULL");
$aproval_gm = $DBHCM->get_sql("SELECT * FROM sa_view_employees_v2 WHERE job_level='2' AND resign_date is null and division_name IS NOT NULL");
$aproval_bod = $DBHCM->get_sql("SELECT * FROM sa_view_employees_v2 WHERE job_level='1' AND resign_date is null and division_name IS NOT NULL");
$aproval_gm_hcm = $DBHCM->get_sql("SELECT * FROM sa_view_employees_v2 WHERE department_name = 'Human Capital Management' AND job_level='2'");
$assign = $DBREC->get_sql("SELECT * FROM sa_assign where assign IS NOT NULL");
$interview_user = $DBHCM->get_sql("select DISTINCT employee_name, employee_email from sa_view_employees_v2 where resign_date is null ORDER BY employee_name ASC");
$interview_user_1 = $DBHCM->get_sql("select DISTINCT employee_name, employee_email from sa_view_employees_v2 where resign_date is null ORDER BY employee_name ASC");
$interview_user_2 = $DBHCM->get_sql("select DISTINCT employee_name, employee_email from sa_view_employees_v2 where resign_date is null ORDER BY employee_name ASC");
$interview_user_3 = $DBHCM->get_sql("select DISTINCT employee_name, employee_email from sa_view_employees_v2 where resign_date is null ORDER BY employee_name ASC");
$interview_user_4 = $DBHCM->get_sql("select DISTINCT employee_name, employee_email from sa_view_employees_v2 where resign_date is null ORDER BY employee_name ASC");
$interview_hcm = $DBHCM->get_sql("select DISTINCT employee_name, employee_email from sa_view_employees_v2 where department_name = 'Human Capital Management' AND resign_date is null ORDER BY employee_name ASC");
$linkfrom = $DBREC->get_sql("SELECT * FROM sa_link");
$cekstatusinterview = $DBREC->get_sql("SELECT * FROM sa_email WHERE status = 'Proses'");
$appmgrhcm = $DBHCM->get_sql("SELECT * FROM sa_view_employees_v2 WHERE department_name = 'Human Capital Management' AND job_level = 3");
$manager_gm_bod = $DBHCM->get_sql("SELECT * FROM sa_view_employees_v2 where (job_level = 3 OR job_level = 2 OR job_level = 1) order by employee_name asc");
// $coba = $DBREC->get_sql("SELECT * FROM sa_trx_approval WHERE assign is not null AND project_code= '" . $_GET['project_code'] . "'");


if (
    $_GET['act'] == 'editinterview' || $_GET['act'] == 'editoffering' || $_GET['act'] == "editjoin"
) {
    $kondisi = "project_code=" . "\"" . $_GET['project_code'] . "\"";

    $dataemail = $DBREC->get_data("email", $kondisi);
    // var_dump($kondisi);
}


// bagian ADD
if ($_GET['act'] == 'add') {
    global $DBREC;
    // var_dump($DBREC)
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
                                                        <!-- <option value="Internship">Internship</option> -->
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
                            <div class="card-header fw-bold">Backround</div>
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
                                                            <option value="<?php echo $namaemployee[0]['employee_email']; ?>"><?php echo $namaemployee[0]['employee_name'] . "(" . $namaemployee[0]['employee_email'] . ")"; ?></option>
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
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan Lain </label>
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
                                        <?php if (isset($_GET['divisi'])) {
                                            $employeenamee = $DBHCM->get_data("view_employees", "employee_email IS NOT NULL AND organization_name LIKE '%" . $_GET['divisi'] . "%' AND job_level = 2"); ?>
                                            <option value="<?php echo $employeenamee[0]['employee_email']; ?>"><?php echo $employeenamee[0]['employee_name']; ?></option>
                                        <?php } ?>
                                        <!-- <option></option> -->
                                        <!-- <option value="malik@mastersystem.co.id">malik@mastersystem.co.id</option> -->
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status GM <b>*</b></label>
                            <div class="col-sm-9">
                                <?php if ($_GET['act'] == "add") { ?>
                                    <select class="form-control" name="status_gm" id="status_gm">
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
    <?php }
// bagian edit request by, gm, gmhcm,gmbod
else if ($_GET['act'] == 'edit') {
    global $DBREC;
    $condition = "id=" . $_GET['id'];
    $data = $DBREC->get_data($tblname, $condition);
    $ddata = $data[0];
    $qdata = $data[1];
    $tdata = $data[2];
    ?>
        <?php //if ($ddata['gm_hcm'] == $_SESSION['Microservices_UserEmail'] || $ddata['gm'] == $_SESSION['Microservices_UserEmail'] || $ddata['gm_bod'] == $_SESSION['Microservices_UserEmail'] || $ddata['request_by'] == $_SESSION['Microservices_UserEmail']) { 
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
                                                <div hidden class="row mb-3">
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
                                                            <option value="<?php echo $ddata['divisi']; ?>" readonly><?php echo $ddata['divisi']; ?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Posisi Yang Dibutuhkan</label>
                                                    <div class="col-sm-9">
                                                        <select class="form-control" name="posisi" id="posisi" readonly>
                                                            <option value="<?php echo $ddata['posisi']; ?>" readonly><?php echo $ddata['posisi']; ?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- KANAN -->
                                            <div class="col-lg-6">
                                                <div class="row mb-3">
                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Jumlah Dibutuhkan</label>
                                                    <div class="col-sm-9">
                                                        <?php if ($ddata['request_by'] == $_SESSION['Microservices_UserEmail']) { ?>
                                                            <input type="text" class="form-control form-control-sm" id="jumlah_dibutuhkan" name="jumlah_dibutuhkan" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                                                                echo $ddata['jumlah_dibutuhkan'];
                                                                                                                                                                            } ?>">
                                                        <?php } else { ?>
                                                            <input type="text" class="form-control form-control-sm" id="jumlah_dibutuhkan" name="jumlah_dibutuhkan" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                                                                echo $ddata['jumlah_dibutuhkan'];
                                                                                                                                                                            } ?>" readonly>
                                                        <?php } ?>
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card shadow mb-1">
                                <div class="card-header fw-bold">Project Status</div>
                                <div class="card-body">
                                    <div class="row">
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
                                                        <option><?php echo ucwords($ddata['kandidat']); ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Internal <b>*</b></label>
                                                <div class="col-sm-9">
                                                    <select class="form-control" name="internal" id="internal" readonly>
                                                        <?php if ($_GET['act'] == "edit") { ?>
                                                            <?php $query = $DBREC->get_sql("SELECT * FROM sa_trx_request_requirement where id=" . $ddata['id'] . ""); ?>
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
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan Lain </label>
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
                    </div>
                </div>
            </div>
            <div class="card shadow mb-1">
                <div class="card-header fw-bold">Approval</div>
                <div class="card-body">
                    <div class="row">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Approval GM <b>*</b></label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="gm" id="gm" readonly>
                                            <?php if ($_GET['act'] == "edit") { ?>
                                                <?php $query = $DBREC->get_sql("SELECT * FROM sa_trx_request_requirement where id=" . $ddata['id'] . ""); ?>
                                                <option value="<?php echo $query[0]['gm']; ?>" readonly><?php echo $query[0]['gm']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3" id="gm_edit">
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
                                            <?php } else { ?>
                                                <select class="form-control" name="status_gm" id="status_gm" readonly>
                                                    <option><?php echo ucwords($ddata['status_gm']); ?></option>
                                                </select>
                                            <?php } ?>
                                        <?php } elseif ($_GET['act'] == "edit" && $ddata['status_gm'] == "Approve" || $_GET['act'] == "edit" && $ddata['status_gm'] == "Disapprove") { ?>
                                            <select class="form-control" name="status_gm" id="status_gm" readonly>
                                                <option><?php echo ucwords($ddata['status_gm']); ?></option>
                                            </select>
                                        <?php }  ?>
                                    </div>
                                </div>
                                <div class="row mb-3" id="catatan-gm-row">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan GM</label>
                                    <div class="col-sm-9">
                                        <?php if ($_GET['act'] == 'edit' && $ddata['catatan_gm'] == null) { ?>
                                            <textarea type="text" class="form-control form-control-sm" id="catatan_gm" name="catatan_gm" rows="2"></textarea>
                                        <?php } elseif ($_GET['act'] == 'edit' && $ddata['catatan_gm'] !== null) { ?>
                                            <textarea type="text" class="form-control form-control-sm" id="catatan_gm" name="catatan_gm" rows="2" readonly> <?php echo $ddata['catatan_gm']; ?> </textarea>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Approval GM HCM <b>*</b></label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="gm_hcm" id="gm_hcm" readonly>
                                            <?php if ($_GET['act'] == "edit") { ?>
                                                <?php $query = $DBREC->get_sql("SELECT * FROM sa_trx_request_requirement where id=" . $ddata['id'] . ""); ?>
                                                <option value="<?php echo $query[0]['gm_hcm']; ?>"><?php echo $query[0]['gm_hcm']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status GM HCM <b>*</b></label>
                                    <div class="col-sm-9">
                                        <?php if ($_GET['act'] == "edit" && $ddata['status_gm'] == "Pending" || $_GET['act'] == "edit" && $ddata['status_gm'] == "Disapprove" || $_GET['act'] == "edit" && $ddata['status_gm_hcm'] == null) { ?>
                                            <!-- approve hanya bisa GM HCM -->
                                            <select class="form-control" name="status_gm_hcm" id="status_gm_hcm" readonly>
                                                <option><?php echo ucwords($ddata['status_gm_hcm']); ?></option>
                                            </select>
                                        <?php } elseif ($_GET['act'] == "edit" && $ddata['status_gm'] == "Approve" && $_GET['act'] == "edit" && $ddata['status_gm_hcm'] == "Pending") { ?>
                                            <?php if ($ddata['gm_hcm'] == $_SESSION['Microservices_UserEmail']) { ?>
                                                <select class="form-control" name="status_gm_hcm" id="status_gm_hcm">
                                                    <option><?php echo ucwords($ddata['status_gm_hcm']); ?></option>
                                                    <option value="Approve">Approve</option>
                                                    <option value="Disapprove">Disapprove</option>
                                                </select>
                                            <?php } else { ?>
                                                <select class="form-control" name="status_gm_hcm" id="status_gm_hcm" readonly>
                                                    <option><?php echo ucwords($ddata['status_gm_hcm']); ?></option>
                                                </select>
                                            <?php } ?>
                                        <?php } elseif ($_GET['act'] == "edit" && $ddata['status_gm_hcm'] == "Approve" || $_GET['act'] == "edit" && $ddata['status_gm_hcm'] == "Disapprove") { ?>
                                            <select class="form-control" name="status_gm_hcm" id="status_gm_hcm" readonly>
                                                <option><?php echo ucwords($ddata['status_gm_hcm']); ?></option>
                                            </select>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="row mb-3" id="catatan-gm-hcm-row">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan GM HCM</label>
                                    <div class="col-sm-9">
                                        <?php if ($_GET['act'] == 'edit' && $ddata['catatan_gm_hcm'] == null) { ?>
                                            <textarea type="text" class="form-control form-control-sm" id="catatan_gm_hcm" name="catatan_gm_hcm" rows="2"></textarea>
                                        <?php } elseif ($_GET['act'] == 'edit' && $ddata['catatan_gm_hcm'] !== null) { ?>
                                            <textarea type="text" class="form-control form-control-sm" id="catatan_gm_hcm" name="catatan_gm_hcm" rows="2" readonly> <?php echo $ddata['catatan_gm_hcm']; ?> </textarea>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6" id="approval_gm_bod_section">
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Approval GM BOD<b>*</b></label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="gm_bod" id="gm_bod" readonly>
                                            <?php if ($_GET['act'] == "edit") { ?>
                                                <?php $query = $DBREC->get_sql("SELECT * FROM sa_trx_request_requirement where id=" . $ddata['id'] . ""); ?>
                                                <option value="<?php echo $query[0]['gm_bod']; ?>"><?php echo $query[0]['gm_bod']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status GM BOD <b>*</b></label>
                                    <div class="col-sm-9">
                                        <?php if ($_GET['act'] == "edit" && $ddata['status_gm_hcm'] == "Pending" || $_GET['act'] == "edit" && $ddata['status_gm_hcm'] == "Disapprove" || $_GET['act'] == "edit" && $ddata['status_gm'] == "Disapprove" || $_GET['act'] == "edit" && $ddata['status_gm_bod'] == null) { ?>
                                            <select class="form-control" name="status_gm_bod" id="status_gm_bod" readonly>
                                                <option><?php echo ucwords($ddata['status_gm_bod']); ?></option>
                                            </select>
                                        <?php } elseif ($_GET['act'] == "edit" && $ddata['status_gm_hcm'] == "Approve" && $_GET['act'] == "edit" && $ddata['status_gm_bod'] == "Pending") { ?>
                                            <?php if ($ddata['gm_bod'] == $_SESSION['Microservices_UserEmail']) { ?>
                                                <select class="form-control" name="status_gm_bod" id="status_gm_bod">
                                                    <option><?php echo ucwords($ddata['status_gm_bod']); ?></option>
                                                    <option value="Approve">Approve</option>
                                                    <option value="Disapprove">Disapprove</option>
                                                </select>
                                            <?php } else { ?>
                                                <select class="form-control" name="status_gm_bod" id="status_gm_bod" readonly>
                                                    <option><?php echo ucwords($ddata['status_gm_bod']); ?></option>
                                                </select>
                                            <?php } ?>
                                        <?php } elseif ($_GET['act'] == "edit" && $ddata['status_gm_bod'] == "Disapprove" || $_GET['act'] == "edit" && $ddata['status_gm_bod'] == "Approve") { ?>
                                            <select class="form-control" name="status_gm_bod" id="status_gm_bod" readonly>
                                                <option><?php echo ucwords($ddata['status_gm_bod']); ?></option>
                                            </select>
                                        <?php } ?>
                                    </div>
                                    <?php //} 
                                    ?>
                                </div>
                                <div class="row mb-3" id="catatan-gm-bod-row">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan GM BOD</label>
                                    <div class="col-sm-9">
                                        <?php if ($_GET['act'] == 'edit' && $ddata['catatan_gm_bod'] == null) { ?>
                                            <textarea type="text" class="form-control form-control-sm" id="catatan_gm_bod" name="catatan_gm_bod" rows="2"></textarea>
                                        <?php } elseif ($_GET['act'] == 'edit' && $ddata['catatan_gm_bod'] !== null) { ?>
                                            <textarea type="text" class="form-control form-control-sm" id="catatan_gm_bod" name="catatan_gm_bod" rows="2" readonly> <?php echo $ddata['catatan_gm_bod']; ?> </textarea>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php }

    /////////////////////////////////////////////////// [ EDIT APPROVAL]
    elseif ($_GET['act'] == 'editapproval') {
        global $DBREC;
        $condition = "id=" . $_GET['id'];
        $data = $DBREC->get_data($tblname, $condition);
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
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Kode Project</label>
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
                    </div>
                    <div class="col-lg-6">
                        <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Jumlah Dibutuhkan</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm" id="jumlah_dibutuhkan" name="jumlah_dibutuhkan" value="<?php if ($_GET['act'] == 'editapproval') {
                                                                                                                                                    echo $ddata['jumlah_dibutuhkan'];
                                                                                                                                                } ?>" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Requirement</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm" id="status_requirement" name="status_requirement" value="<?php if ($_GET['act'] == 'editapproval') {
                                                                                                                                                        echo $ddata['status_requirement'];
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
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm" id="status_approval" name="status_approval" value="<?php if ($_GET['act'] == 'editapproval') {
                                                                                                                                                echo $ddata['status_approval'];
                                                                                                                                            } ?>" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Assign <b>*</b></label>
                            <div class="col-sm-9">
                                <?php if ($_GET['act'] == "editapproval" && $ddata['assign'] == null) { ?>
                                    <select class="form-control" name="assign" id="assign">
                                        <?php if ($assign[0]['assign'] !== null) { ?>
                                            <?php do { ?>
                                                <option value="<?php echo $assign[0]['assign']; ?>"><?php echo $assign[0]['assign']; ?></option>
                                            <?php } while ($assign[0] = $assign[1]->fetch_assoc()); ?>
                                        <?php } else { ?>
                                            <option>Tambah Assign</option>
                                        <?php } ?>
                                    </select>
                                <?php } else { ?>
                                    <select class="form-control" name="assign" id="assign" readonly>
                                        <option><?php echo ucwords($ddata['assign']); ?></option>
                                    </select>
                                <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm"></label>
                            <div class="col-sm-9">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahAssignFormModal">
                                    New Assign Recruitment
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }


        /////////////////////////////////////////////////// [ EDIT SHARE]
        elseif ($_GET['act'] == 'editshare') {
            global $DBREC;
            $condition = "id=" . $_GET['id'];
            $data = $DBREC->get_data($tblname, $condition);
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
                    $condition = 'config_key="MEDIA_NEW_REQUEST" AND parent=8';
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
                                            $filenote = $DBREC->get_sql("SELECT * from sa_notecv where file = '" . $entry . "'");
                                            $filenotemalik = $filenote[0]['notes'];
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
                                                        <textarea class="form-control form-control-sm" name="notes[]" id="notes[]"></textarea>
                                                        <textarea class="form-control form-control-sm" name="notes[]" id="notes[]"><?php echo $filenotemalik; ?></textarea>
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
                <?php
            } elseif ($_GET['act'] == 'editinterview') {
                global $DBREC;
                $condition = "interview_id=" . $_GET['interview_id'];
                $data = $DBREC->get_data($tblname, $condition);
                $ddata = $data[0];
                $qdata = $data[1];
                $tdata = $data[2];
                $coba = $DBREC->get_sql("SELECT * FROM sa_trx_approval WHERE assign is not null AND project_code= '" . $_GET['project_code'] . "'");
                ?>
                    <form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <h2>Interview</h2>
                                <div class="card shadow mb-4">
                                    <div class="card-body">
                                        <h2></h2>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="row mb-3" hidden>
                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Interview Id</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control form-control-sm" id="interview_id" name="interview_id" value="<?php if ($_GET['act'] == 'editinterview') {
                                                                                                                                                                    echo $ddata['interview_id'];
                                                                                                                                                                } ?>">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Divisi</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control form-control-sm" id="divisi" name="divisi" value="<?php if ($_GET['act'] == 'editinterview') {
                                                                                                                                                        echo $ddata['divisi'];
                                                                                                                                                    } ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Posisi</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control form-control-sm" id="posisi" name="posisi" value="<?php if ($_GET['act'] == 'editinterview') {
                                                                                                                                                        echo $ddata['posisi'];
                                                                                                                                                    } ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Kandidat</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control form-control-sm" id="kandidat" name="kandidat" value="<?php if ($_GET['act'] == 'editinterview') {
                                                                                                                                                            echo $ddata['kandidat'];
                                                                                                                                                        } ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row mb-3">
                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Share</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control form-control-sm" id="share" name="share" value="<?php if ($_GET['act'] == 'editinterview') {
                                                                                                                                                    echo $ddata['share'];
                                                                                                                                                } ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Code</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control form-control-sm" id="project_code" name="project_code" value="<?php if ($_GET['act'] == 'editinterview') {
                                                                                                                                                                    echo $ddata['project_code'];
                                                                                                                                                                } ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Jumlah Dibutuhkan</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control form-control-sm" id="jumlah_dibutuhkan" name="jumlah_dibutuhkan" value="<?php if ($_GET['act'] == 'editinterview') {
                                                                                                                                                                            echo $ddata['jumlah_dibutuhkan'];
                                                                                                                                                                        } ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Tanggal Dibutuhkan</label>
                                                    <div class="col-sm-9">
                                                        <input type="date" class="form-control form-control-sm" id="tanggal_dibutuhkan" name="tanggal_dibutuhkan" value="<?php if ($_GET['act'] == 'editinterview') {
                                                                                                                                                                                echo $trx_request_requirement[0]['tanggal_dibutuhkan'];
                                                                                                                                                                            } ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                            global $DB;
                                            $tblname = 'cfg_web';
                                            $condition = 'config_key="MEDIA_NEW_REQUEST" AND parent=8';
                                            $folders = $DB->get_data($tblname, $condition);
                                            $FolderName = 'request_requirement';
                                            $sFolderTarget = $folders[0]['config_value'] . '/' . $_GET['project_code'] . '/' . $FolderName . '/';
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
                                            <?php if ($_GET['act'] == 'editinterview') { ?>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <!-- <div class="row mb-3">
                                                <button type="button" class="btn btn-primary" name="btn_upload" id="btn_upload" data-bs-toggle="modal" data-bs-target="#fileupload">Upload File</button>
                                            </div> -->
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
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $i = 0;
                                                                while (false !== ($entry = $d->read())) {
                                                                    if ($entry != '.' && $entry != '..' && $entry != 'index.php') {
                                                                        $fstat = stat($sFolderTarget . $entry);
                                                                ?>
                                                                        <?php //if($entry = $_GET['cr_no']) {
                                                                        ?>
                                                                        <tr>
                                                                            <th scope="row"><?php echo $i + 1; ?></th>
                                                                            <td><a href="<?php echo $sFolderTarget . $entry; ?>" target="_blank" class="text-body"><?php echo $entry; ?></a></td>
                                                                            <td class="text">
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
                                            <?php if ($_GET['act'] == 'editinterview') { ?>
                                                <?php $email = isset($dataemail[0]['email']) ?>
                                                <?php if ($email == null) { ?>
                                                <?php } else { ?>
                                                    <table>
                                                        <tr>
                                                            <th class="text-center" rowspan="2">Email</th>
                                                            <th class="text-center" rowspan="2">Tanggal Interview</th>
                                                            <!-- <th class="text-center" rowspan="2">Interview User</th>
        <th class="text-center" rowspan="2">Interview HCM</th> -->
                                                            <th class="text-center" rowspan="2">Catatan</th>
                                                            <th class="text-center" rowspan="2">Action</th>
                                                            <!-- <th class="text-center" rowspan="2">Ceklis</th> -->
                                                        </tr>
                                                        <tr>
                                                            <?php if ($ddata['status_interview'] == "Submit") { ?>
                                                                <th hidden class="text-center">Experience</th>
                                                                <th hidden class="text-center">Salary</th>
                                                                <th hidden class="text-center">Join Date</th>
                                                            <?php } else { ?>
                                                                <th hidden class="text-center">Experience</th>
                                                                <th hidden class="text-center">Salary</th>
                                                                <th hidden class="text-center">Join Date</th>
                                                            <?php } ?>
                                                        </tr>
                                                        <?php do { ?>
                                                            <tr>
                                                                <?php if ($dataemail[0]['status'] == 'Proses' || $dataemail[0]['status'] == 'Yes') { ?>
                                                                    <td>
                                                                        <input type="hidden" class="form-control form-control-sm" name="email_id[]" id="email_id[]" rows="2" value="<?php echo $dataemail[0]['email_id']; ?>">
                                                                        <input type="text" class="form-control form-control-sm" name="email_update[]" id="email_update[]" rows="2" value="<?php echo $dataemail[0]['email']; ?>" readonly>
                                                                    </td>
                                                                    <td>
                                                                        <input type="date" class="form-control form-control-sm" name="tanggal_interview_update[]" id="tanggal_interview_update[]" rows="2" value="<?php echo $dataemail[0]['tanggal_interview']; ?>" readonly>
                                                                    </td>
                                                                    <!-- <td>
                <input type="text" class="form-control form-control-sm" name="interview_user_update[]" id="interview_user_update[]" rows="2" value="<?php //echo $dataemail[0]['interview_user']; 
                                                                                                                                                    ?>" readonly>
            </td>
            <td>
                <input type="text" class="form-control form-control-sm" name="interview_hcm_update[]" id="interview_hcm_update[]" rows="2" value="<?php //echo $dataemail[0]['interview_hcm']; 
                                                                                                                                                    ?>" readonly>
            </td> -->
                                                                    <td>
                                                                        <?php if ($dataemail[0]['catatan'] == null) { ?>
                                                                            <textarea class="form-control form-control-sm" name="catatan_update[]" id="catatan_update[]" rows="2" style="height: 22px;"></textarea>
                                                                        <?php } else { ?>
                                                                            <textarea class="form-control form-control-sm" name="catatan_update[]" id="catatan_update[]" rows="2" readonly><?php echo $dataemail[0]['catatan']; ?></textarea>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <?php if ($dataemail[0]['status'] == 'Proses') { ?>
                                                                        <td class="text-center">
                                                                            <input type="submit" class="btn btn-primary" name="berhasil" value="Yes">
                                                                            <input type="submit" class="btn btn-danger" name="gagal" value="No">
                                                                        </td>
                                                                    <?php } else {
                                                                    } ?>
                                                            </tr>
                                                        <?php } ?>
                                                    <?php } while ($dataemail[0] = $dataemail[1]->fetch_assoc()); ?>
                                                    </table>
                                                <?php } ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div id="newRow-affected-ci"></div>
                                    <button id="addemail" type="button" class="btn btn-info col-12">+</button>
                                </div>
                            </div>
                        </div>

                    <?php } elseif ($_GET['act'] == 'editoffering') {
                    global $DBREC;
                    $condition = "id_offering=" . $_GET['id_offering'];
                    $data = $DBREC->get_data($tblname, $condition);
                    $ddata = $data[0];
                    $qdata = $data[1];
                    $tdata = $data[2];
                    ?>
                        <form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
                            <div class="tab-content" id="myTabContent">
                                <div class="card shadow mb-4">
                                    <div class="card-body">
                                        <h2>OFFERING</h2>
                                        <div class="card shadow mb-4">
                                            <div class="card-body">
                                                <h2></h2>
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="row mb-3" hidden>
                                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">ID Offering</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="form-control form-control-sm" id="id_offering" name="id_offering" value="<?php if ($_GET['act'] == 'editoffering') {
                                                                                                                                                                        echo $ddata['id_offering'];
                                                                                                                                                                    } ?>" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Divisi</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="form-control form-control-sm" id="divisi" name="divisi" value="<?php if ($_GET['act'] == 'editoffering') {
                                                                                                                                                                echo $ddata['divisi'];
                                                                                                                                                            } ?>" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Posisi</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="form-control form-control-sm" id="posisi" name="posisi" value="<?php if ($_GET['act'] == 'editoffering') {
                                                                                                                                                                echo $ddata['posisi'];
                                                                                                                                                            } ?>" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Code</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="form-control form-control-sm" id="project_code" name="project_code" value="<?php if ($_GET['act'] == 'editoffering') {
                                                                                                                                                                            echo $ddata['project_code'];
                                                                                                                                                                        } ?>" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Kandidat</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="form-control form-control-sm" id="kandidat" name="kandidat" value="<?php if ($_GET['act'] == 'editoffering') {
                                                                                                                                                                    echo $ddata['kandidat'];
                                                                                                                                                                } ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="row mb-3">
                                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Jumlah Dibutuhkan</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="form-control form-control-sm" id="jumlah_dibutuhkan" name="jumlah_dibutuhkan" value="<?php if ($_GET['act'] == 'editoffering') {
                                                                                                                                                                                    echo $ddata['jumlah_dibutuhkan'];
                                                                                                                                                                                } ?>" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Share</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" class="form-control form-control-sm" id="share" name="share" value="<?php if ($_GET['act'] == 'editoffering') {
                                                                                                                                                            echo $ddata['share'];
                                                                                                                                                        } ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card shadow mb-4">
                                            <div class="card-body">
                                                <h2></h2>
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="row mb-3">
                                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Manager HCM <b>*</b></label>
                                                            <div class="col-sm-9">
                                                                <?php if ($_GET['act'] == "editoffering" && $ddata['manager_hcm'] == null) { ?>
                                                                    <select class="form-control" name="manager_hcm" id="manager_hcm" readonly>
                                                                        <option value="malik.aulia@mastersystem">malik.aulia@mastersystem</option>
                                                                        <?php do { ?>
                                                                            <option value="<?php echo $appmgrhcm[0]['employee_email']; ?>"><?php echo $appmgrhcm[0]['employee_name']; ?></option>
                                                                        <?php } while ($appmgrhcm[0] = $appmgrhcm[1]->fetch_assoc()); ?>
                                                                    </select>
                                                                <?php } else { ?>
                                                                    <select class="form-control" name="manager_hcm" id="manager_hcm" readonly>
                                                                        <option value="<?php echo $ddata['manager_hcm']; ?>"><?php echo $ddata['manager_hcm']; ?></option>
                                                                    </select>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Manager <b>*</b></label>
                                                            <div class="col-sm-9">
                                                                <?php if ($_GET['act'] == "editoffering" && $ddata['status_manager_hcm'] == null) { ?>
                                                                    <select class="form-control" name="status_manager_hcm" id="status_manager_hcm">
                                                                        <option value="Pending">Pending</option>
                                                                    </select>
                                                                <?php } elseif ($_GET['act'] == "editoffering" && $ddata['status_manager_hcm'] == "Pending") { ?>
                                                                    <?php if ($ddata['manager_hcm'] == $_SESSION['Microservices_UserEmail']) { ?>
                                                                        <select class="form-control" name="status_manager_hcm" id="status_manager_hcm">
                                                                            <option><?php echo ucwords($ddata['status_manager_hcm']); ?></option>
                                                                            <option value="Approve">Approve</option>
                                                                            <option value="Disapprove">Disapprove</option>
                                                                        </select>
                                                                    <?php } else { ?>
                                                                        <select class="form-control" name="status_manager_hcm" id="status_manager_hcm" readonly>
                                                                            <option><?php echo ucwords($ddata['status_manager_hcm']); ?></option>
                                                                        </select>
                                                                    <?php } ?>
                                                                <?php } elseif ($_GET['act'] == "editoffering" && $ddata['status_manager_hcm'] == "Approve" || $_GET['act'] == "editoffering" && $ddata['status_manager_hcm'] == "Disapprove") { ?>
                                                                    <select class="form-control" name="status_manager_hcm" id="status_manager_hcm" readonly>
                                                                        <option><?php echo ucwords($ddata['status_manager_hcm']); ?></option>
                                                                    </select>
                                                                <?php }  ?>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">GM HCM <b>*</b></label>
                                                            <div class="col-sm-9">
                                                                <?php if ($_GET['act'] == "editoffering" && $ddata['hcm_gm'] == null) { ?>
                                                                    <select class="form-control" name="hcm_gm" id="hcm_gm" readonly>
                                                                        <option value="malik.aulia@mastersystem">malik.aulia@mastersystem</option>
                                                                        <?php do { ?>
                                                                            <option value="<?php echo $aproval_gm_hcm[0]['employee_email']; ?>"><?php echo $aproval_gm_hcm[0]['employee_name'] . "(" . $aproval_gm_hcm[0]['employee_email'] . ")"; ?></option>
                                                                        <?php } while ($aproval_gm_hcm[0] = $aproval_gm_hcm[1]->fetch_assoc()); ?>
                                                                    </select>
                                                                <?php } else { ?>
                                                                    <select class="form-control" name="hcm_gm" id="hcm_gm" readonly>
                                                                        <option value="<?php echo $ddata['hcm_gm']; ?>"><?php echo $ddata['hcm_gm']; ?></option>
                                                                    </select>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status GM HCM<b>*</b></label>
                                                            <div class="col-sm-9">
                                                                <?php if ($_GET['act'] == "editoffering" && $ddata['status_hcm_gm'] == null) { ?>
                                                                    <select class="form-control" name="status_hcm_gm" id="status_hcm_gm">
                                                                        <option value="Pending">Pending</option>
                                                                    </select>
                                                                <?php } elseif ($_GET['act'] == "editoffering" && $ddata['status_hcm_gm'] == "Pending") { ?>
                                                                    <?php if ($ddata['hcm_gm'] == $_SESSION['Microservices_UserEmail'] && $ddata['status_manager_hcm'] !== "Pending") { ?>
                                                                        <select class="form-control" name="status_hcm_gm" id="status_hcm_gm">
                                                                            <option><?php echo ucwords($ddata['status_hcm_gm']); ?></option>
                                                                            <option value="Approve">Approve</option>
                                                                            <option value="Disapprove">Disapprove</option>
                                                                        </select>
                                                                    <?php } else { ?>
                                                                        <select class="form-control" name="status_hcm_gm" id="status_hcm_gm" readonly>
                                                                            <option><?php echo ucwords($ddata['status_hcm_gm']); ?></option>
                                                                        </select>
                                                                    <?php } ?>
                                                                <?php } elseif ($_GET['act'] == "editoffering" && $ddata['status_hcm_gm'] == "Approve" || $_GET['act'] == "editoffering" && $ddata['status_hcm_gm'] == "Disapprove") { ?>
                                                                    <select class="form-control" name="status_hcm_gm" id="status_hcm_gm" readonly>
                                                                        <option><?php echo ucwords($ddata['status_hcm_gm']); ?></option>
                                                                    </select>
                                                                <?php }  ?>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Manager GM BOD <b>*</b></label>
                                                            <div class="col-sm-9">
                                                                <?php if ($_GET['act'] == "editoffering" && $ddata['manager_gm_bod'] == null) { ?>
                                                                    <select class="form-control" name="manager_gm_bod" id="manager_gm_bod">
                                                                        <option value="malik.aulia@mastersystem.co.id">malik.aulia@mastersystem.co.id</option>
                                                                        <?php do { ?>
                                                                            <option value="<?php echo $manager_gm_bod[0]['employee_email']; ?>"><?php echo $manager_gm_bod[0]['employee_name']; ?></option>
                                                                        <?php } while ($manager_gm_bod[0] = $manager_gm_bod[1]->fetch_assoc()); ?>
                                                                    </select>
                                                                <?php } else { ?>
                                                                    <select class="form-control" name="manager_gm_bod" id="manager_gm_bod" readonly>
                                                                        <option value="<?php echo $ddata['manager_gm_bod']; ?>"><?php echo $ddata['manager_gm_bod']; ?></option>
                                                                    </select>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Manager GM BOD<b>*</b></label>
                                                            <div class="col-sm-9">
                                                                <?php if ($_GET['act'] == "editoffering" && $ddata['status_manager_gm_bod'] == null) { ?>
                                                                    <select class="form-control" name="status_manager_gm_bod" id="status_manager_gm_bod">
                                                                        <option value="Pending">Pending</option>
                                                                    </select>
                                                                <?php } elseif ($_GET['act'] == "editoffering" && $ddata['status_manager_gm_bod'] == "Pending") { ?>
                                                                    <?php if ($ddata['manager_gm_bod'] == $_SESSION['Microservices_UserEmail'] && $ddata['status_hcm_gm'] !== "Pending") { ?>
                                                                        <select class="form-control" name="status_manager_gm_bod" id="status_manager_gm_bod">
                                                                            <option><?php echo ucwords($ddata['status_manager_gm_bod']); ?></option>
                                                                            <option value="Approve">Approve</option>
                                                                            <option value="Disapprove">Disapprove</option>
                                                                        </select>
                                                                    <?php } else { ?>
                                                                        <select class="form-control" name="status_manager_gm_bod" id="status_manager_gm_bod" readonly>
                                                                            <option><?php echo ucwords($ddata['status_manager_gm_bod']); ?></option>
                                                                        </select>
                                                                    <?php } ?>
                                                                <?php } elseif ($_GET['act'] == "editoffering" && $ddata['status_manager_gm_bod'] == "Approve" || $_GET['act'] == "editoffering" && $ddata['status_manager_gm_bod'] == "Disapprove") { ?>
                                                                    <select class="form-control" name="status_manager_gm_bod" id="status_manager_gm_bod" readonly>
                                                                        <option><?php echo ucwords($ddata['status_manager_gm_bod']); ?></option>
                                                                    </select>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <!-- <div class="row mb-3">
                                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan </label>
                                                            <?php ?>
                                                            <div class="col-sm-9">
                                                                <?php //if ($_GET['act'] == "editoffering" && $ddata['catatan_offering'] == null) { 
                                                                ?>
                                                                    <?php //if ($ddata['manager_hcm'] == $_SESSION['Microservices_UserEmail'] || $ddata['hcm_gm'] == $_SESSION['Microservices_UserEmail'] || $ddata['manager_gm_bod'] == $_SESSION['Microservices_UserEmail'] || $dataemail[0]['pic'] == $_SESSION['Microservices_UserEmail']) { 
                                                                    ?>
                                                                        <textarea class="form-control form-control-sm" name="catatan_offering" id="catatan_offering" rows="2" style="height: 309px;"></textarea>
                                                                    <?php //} 
                                                                    ?>
                                                                <?php //} else { 
                                                                ?>
                                                                    <?php //if ($ddata['manager_hcm'] == $_SESSION['Microservices_UserEmail'] || $ddata['hcm_gm'] == $_SESSION['Microservices_UserEmail'] || $ddata['manager_gm_bod'] == $_SESSION['Microservices_UserEmail'] || $dataemail[0]['pic'] == $_SESSION['Microservices_UserEmail']) { 
                                                                    ?>
                                                                        <textarea class="form-control form-control-sm" name="catatan_offering" id="catatan_offering" rows="2" style="height: 309px;"><?php //if ($_GET['act'] == 'editoffering') {
                                                                                                                                                                                                        //echo $ddata['catatan_offering'];
                                                                                                                                                                                                        //} 
                                                                                                                                                                                                        ?></textarea>
                                                                    <?php //} else { 
                                                                    ?>
                                                                        <textarea class="form-control form-control-sm" name="catatan_offering" id="catatan_offering" rows="2" style="height: 309px;" readonly><?php //if ($_GET['act'] == 'editoffering') {
                                                                                                                                                                                                                //echo $ddata['catatan_offering'];
                                                                                                                                                                                                                //} 
                                                                                                                                                                                                                ?></textarea>
                                                                    <?php //} 
                                                                    ?>
                                                                <?php //} 
                                                                ?>
                                                            </div>
                                                        </div> -->
                                                        <div class="row mb-3" id="catatan-manager-hcm">
                                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan Manager HCM</label>
                                                            <div class="col-sm-9">
                                                                <?php if ($ddata['catatan_manager_hcm'] == null) { ?>
                                                                    <textarea type="text" class="form-control form-control-sm" id="catatan_manager_hcm" name="catatan_manager_hcm" rows="2"></textarea>
                                                                <?php } else { ?>
                                                                    <textarea type="text" class="form-control form-control-sm" id="catatan_manager_hcm" name="catatan_manager_hcm" rows="2" readonly> <?php echo $ddata['catatan_manager_hcm']; ?></textarea>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3" id="catatan-hcm-gm">
                                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan GM HCM</label>
                                                            <div class="col-sm-9">
                                                                <?php if ($ddata['catatan_hcm_gm'] == null) { ?>
                                                                    <textarea type="text" class="form-control form-control-sm" id="catatan_hcm_gm" name="catatan_hcm_gm" rows="2"></textarea>
                                                                <?php } else { ?>
                                                                    <textarea type="text" class="form-control form-control-sm" id="catatan_hcm_gm" name="catatan_hcm_gm" rows="2" readonly> <?php echo $ddata['catatan_hcm_gm']; ?></textarea>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3" id="catatan-manager-gm-bod">
                                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan Manager/GM/BOD</label>
                                                            <div class="col-sm-9">
                                                                <?php if ($ddata['catatan_manager_gm_bod'] == null) { ?>
                                                                    <textarea type="text" class="form-control form-control-sm" id="catatan_manager_gm_bod" name="catatan_manager_gm_bod" rows="2"></textarea>
                                                                <?php } else { ?>
                                                                    <textarea type="text" class="form-control form-control-sm" id="catatan_manager_gm_bod" name="catatan_manager_gm_bod" rows="2" readonly> <?php echo $ddata['catatan_manager_gm_bod']; ?></textarea>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                        <!-- <div class="row mb-3">
                                                            <?php //if ($_GET['act'] == "editoffering" && $ddata['status_manager_hcm'] == "Approve" && $ddata['status_hcm_gm'] == "Approve" && $ddata['status_manager_gm_bod'] == "Approve") { 
                                                            ?>
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Join Date </label>
                                                                <div class="col-sm-9">
                                                                    <?php //if ($_GET['act'] == "editoffering") { 
                                                                    ?>
                                                                        <input type="date" class="form-control form-control-sm" id="join_date" name="join_date" value="">
                                                                    <?php //} else { 
                                                                    ?>
                                                                        <input type="date" class="form-control form-control-sm" id="join_date" name="join_date" value="<?php //if ($_GET['act'] == 'editoffering') {
                                                                                                                                                                        //echo $ddata['join_date'];
                                                                                                                                                                        // } 
                                                                                                                                                                        ?>" readonly>
                                                                    <?php //} 
                                                                    ?>
                                                                </div>
                                                            <?php //} 
                                                            ?>
                                                        </div> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if ($_GET['act'] == 'editoffering') { ?>

                                        <table>
                                            <tr>
                                                <th class="text-center" rowspan="2">Email</th>
                                                <th class="text-center" rowspan="2">Tanggal Interview</th>
                                                <th class="text-center" rowspan="2">PIC (Reqruiter)</th>
                                                <!-- <th class="text-center" rowspan="2">Interview User</th> -->
                                                <!-- <th class="text-center" rowspan="2">Interview HCM</th> -->
                                                <th class="text-center" rowspan="2">Catatan</th>
                                            </tr>
                                            <tr>
                                            </tr>
                                            <?php do { ?>
                                                <?php if ($dataemail[0]['status'] == 'Yes') { ?>
                                                    <tr>
                                                        <td>
                                                            <input type="hidden" class="form-control form-control-sm" name="email_id[]" id="email_id[]" rows="2" value="<?php echo $dataemail[0]['email_id']; ?>">
                                                            <input type="text" class="form-control form-control-sm" name="email_update[]" id="email_update[]" rows="2" value="<?php echo $dataemail[0]['email']; ?>" readonly>
                                                        </td>
                                                        <td>
                                                            <input type="date" class="form-control form-control-sm" name="tanggal_interview_update[]" id="tanggal_interview_update[]" rows="2" value="<?php echo $dataemail[0]['tanggal_interview']; ?>" readonly>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control form-control-sm" name="pic[]" id="pic[]" rows="2" value="<?php echo $dataemail[0]['pic']; ?>" readonly>
                                                            <!-- <input type="text" class="form-control form-control-sm" name="interview_user_update[]" id="interview_user_update[]" rows="2" value="<?php //echo $dataemail[0]['interview_user']; 
                                                                                                                                                                                                        ?>" readonly> -->
                                                        </td>
                                                        <!-- <td> -->
                                                        <!-- <input type="text" class="form-control form-control-sm" name="interview_hcm_update[]" id="interview_hcm_update[]" rows="2" value="<?php //echo $dataemail[0]['interview_hcm']; 
                                                                                                                                                                                                ?>" readonly> -->
                                                        <!-- </td> -->
                                                        <td>
                                                            <textarea class="form-control form-control-sm" name="catatan_update[]" id="catatan_update[]" rows="2" readonly><?php echo $dataemail[0]['catatan']; ?></textarea>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } while ($dataemail[0] = $dataemail[1]->fetch_assoc()); ?>
                                        </table>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } elseif ($_GET['act'] == 'editjoin') {
                        global $DBREC;
                        $condition = "id_join=" . $_GET['id_join'];
                        $data = $DBREC->get_data($tblname, $condition);
                        $ddata = $data[0];
                        $qdata = $data[1];
                        $tdata = $data[2];
                        ?>
                            <form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
                                <div class="tab-content" id="myTabContent">
                                    <div class="card shadow mb-4">
                                        <div class="card-body">
                                            <h2>JOIN</h2>
                                            <div class="card shadow mb-4">
                                                <div class="card-body">
                                                    <h2></h2>
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="row mb-3" hidden>
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">ID JOIN</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="id_join" name="id_join" value="<?php if ($_GET['act'] == 'editjoin') {
                                                                                                                                                                    echo $ddata['id_join'];
                                                                                                                                                                } ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Divisi</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="divisi" name="divisi" value="<?php if ($_GET['act'] == 'editjoin') {
                                                                                                                                                                    echo $ddata['divisi'];
                                                                                                                                                                } ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Posisi</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="posisi" name="posisi" value="<?php if ($_GET['act'] == 'editjoin') {
                                                                                                                                                                    echo $ddata['posisi'];
                                                                                                                                                                } ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Code</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="project_code" name="project_code" value="<?php if ($_GET['act'] == 'editjoin') {
                                                                                                                                                                                echo $ddata['project_code'];
                                                                                                                                                                            } ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Kandidat</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="kandidat" name="kandidat" value="<?php if ($_GET['act'] == 'editjoin') {
                                                                                                                                                                        echo $ddata['kandidat'];
                                                                                                                                                                    } ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Jumlah Dibutuhkan</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="jumlah_dibutuhkan" name="jumlah_dibutuhkan" value="<?php if ($_GET['act'] == 'editjoin') {
                                                                                                                                                                                        echo $ddata['jumlah_dibutuhkan'];
                                                                                                                                                                                    } ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Share</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="share" name="share" value="<?php if ($_GET['act'] == 'editjoin') {
                                                                                                                                                                echo $ddata['share'];
                                                                                                                                                            } ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Tanggal Dibutuhkan</label>
                                                                <div class="col-sm-9">
                                                                    <input type="date" class="form-control form-control-sm" id="tanggal_dibutuhkan" name="tanggal_dibutuhkan" value="<?php if ($_GET['act'] == 'editjoin') {
                                                                                                                                                                                            echo $trx_request_requirement[0]['tanggal_dibutuhkan'];
                                                                                                                                                                                        } ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card shadow mb-4">
                                                <div class="card-body">
                                                    <h2></h2>
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Manager HCM <b>*</b></label>
                                                                <div class="col-sm-9">
                                                                    <?php if ($_GET['act'] == "editjoin" && $ddata['manager_hcm'] == null) { ?>
                                                                        <select class="form-control" name="manager_hcm" id="manager_hcm" readonly>
                                                                            <?php do { ?>
                                                                                <option value="<?php echo $appmgrhcm[0]['employee_email']; ?>"><?php echo $appmgrhcm[0]['employee_name']; ?></option>
                                                                            <?php } while ($appmgrhcm[0] = $appmgrhcm[1]->fetch_assoc()); ?>
                                                                        </select>
                                                                    <?php } else { ?>
                                                                        <select class="form-control" name="manager_hcm" id="manager_hcm" readonly>
                                                                            <option value="<?php echo $ddata['manager_hcm']; ?>"><?php echo $ddata['manager_hcm']; ?></option>
                                                                        </select>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Manager <b>*</b></label>
                                                                <div class="col-sm-9">
                                                                    <?php if ($_GET['act'] == "editjoin" && $ddata['status_manager_hcm'] == null) { ?>
                                                                        <select class="form-control" name="status_manager_hcm" id="status_manager_hcm">
                                                                            <option value="Pending">Pending</option>
                                                                        </select>
                                                                    <?php } elseif ($_GET['act'] == "editjoin" && $ddata['status_manager_hcm'] == "Pending") { ?>
                                                                        <?php if ($ddata['manager_hcm'] == $_SESSION['Microservices_UserEmail']) { ?>
                                                                            <select class="form-control" name="status_manager_hcm" id="status_manager_hcm">
                                                                                <option><?php echo ucwords($ddata['status_manager_hcm']); ?></option>
                                                                                <option value="Approve">Approve</option>
                                                                                <option value="Disapprove">Disapprove</option>
                                                                            </select>
                                                                        <?php } else { ?>
                                                                            <select class="form-control" name="status_manager_hcm" id="status_manager_hcm" readonly>
                                                                                <option><?php echo ucwords($ddata['status_manager_hcm']); ?></option>
                                                                            </select>
                                                                        <?php } ?>
                                                                    <?php } elseif ($_GET['act'] == "editjoin" && $ddata['status_manager_hcm'] == "Approve" || $_GET['act'] == "editjoin" && $ddata['status_manager_hcm'] == "Disapprove") { ?>
                                                                        <select class="form-control" name="status_manager_hcm" id="status_manager_hcm" readonly>
                                                                            <option><?php echo ucwords($ddata['status_manager_hcm']); ?></option>
                                                                        </select>
                                                                    <?php }  ?>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">GM HCM <b>*</b></label>
                                                                <div class="col-sm-9">
                                                                    <?php if ($_GET['act'] == "editjoin" && $ddata['hcm_gm'] == null) { ?>
                                                                        <select class="form-control" name="hcm_gm" id="hcm_gm" readonly>
                                                                            <?php do { ?>
                                                                                <option value="<?php echo $aproval_gm_hcm[0]['employee_email']; ?>"><?php echo $aproval_gm_hcm[0]['employee_name'] . "(" . $aproval_gm_hcm[0]['employee_email'] . ")"; ?></option>
                                                                            <?php } while ($aproval_gm_hcm[0] = $aproval_gm_hcm[1]->fetch_assoc()); ?>
                                                                        </select>
                                                                    <?php } else { ?>
                                                                        <select class="form-control" name="hcm_gm" id="hcm_gm" readonly>
                                                                            <option value="<?php echo $ddata['hcm_gm']; ?>"><?php echo $ddata['hcm_gm']; ?></option>
                                                                        </select>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status GM HCM<b>*</b></label>
                                                                <div class="col-sm-9">
                                                                    <?php if ($_GET['act'] == "editjoin" && $ddata['status_hcm_gm'] == null) { ?>
                                                                        <select class="form-control" name="status_hcm_gm" id="status_hcm_gm">
                                                                            <option value="Pending">Pending</option>
                                                                        </select>
                                                                    <?php } elseif ($_GET['act'] == "editjoin" && $ddata['status_hcm_gm'] == "Pending") { ?>
                                                                        <?php if ($ddata['hcm_gm'] == $_SESSION['Microservices_UserEmail'] && $ddata['status_manager_hcm'] !== "Pending") { ?>
                                                                            <select class="form-control" name="status_hcm_gm" id="status_hcm_gm">
                                                                                <option><?php echo ucwords($ddata['status_hcm_gm']); ?></option>
                                                                                <option value="Approve">Approve</option>
                                                                                <option value="Disapprove">Disapprove</option>
                                                                            </select>
                                                                        <?php } else { ?>
                                                                            <select class="form-control" name="status_hcm_gm" id="status_hcm_gm" readonly>
                                                                                <option><?php echo ucwords($ddata['status_hcm_gm']); ?></option>
                                                                            </select>
                                                                        <?php } ?>
                                                                    <?php } elseif ($_GET['act'] == "editjoin" && $ddata['status_hcm_gm'] == "Approve" || $_GET['act'] == "editjoin" && $ddata['status_hcm_gm'] == "Disapprove") { ?>
                                                                        <select class="form-control" name="status_hcm_gm" id="status_hcm_gm" readonly>
                                                                            <option><?php echo ucwords($ddata['status_hcm_gm']); ?></option>
                                                                        </select>
                                                                    <?php }  ?>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Manager GM BOD <b>*</b></label>
                                                                <div class="col-sm-9">
                                                                    <?php if ($_GET['act'] == "editjoin" && $ddata['manager_gm_bod'] == null) { ?>
                                                                        <select class="form-control" name="manager_gm_bod" id="manager_gm_bod">
                                                                            <option value="malik@mastersystem.co.id">malik@mastersystem.co.id</option>
                                                                            <?php do { ?>
                                                                                <option value="<?php echo $manager_gm_bod[0]['employee_email']; ?>"><?php echo $manager_gm_bod[0]['employee_name']; ?></option>
                                                                            <?php } while ($manager_gm_bod[0] = $manager_gm_bod[1]->fetch_assoc()); ?>
                                                                        </select>
                                                                    <?php } else { ?>
                                                                        <select class="form-control" name="manager_gm_bod" id="manager_gm_bod" readonly>
                                                                            <option value="<?php echo $ddata['manager_gm_bod']; ?>"><?php echo $ddata['manager_gm_bod']; ?></option>
                                                                        </select>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Manager GM BOD<b>*</b></label>
                                                                <div class="col-sm-9">
                                                                    <?php if ($_GET['act'] == "editjoin" && $ddata['status_manager_gm_bod'] == null) { ?>
                                                                        <select class="form-control" name="status_manager_gm_bod" id="status_manager_gm_bod">
                                                                            <option value="Pending">Pending</option>
                                                                        </select>
                                                                    <?php } elseif ($_GET['act'] == "editjoin" && $ddata['status_manager_gm_bod'] == "Pending") { ?>
                                                                        <?php if ($ddata['manager_gm_bod'] == $_SESSION['Microservices_UserEmail'] && $ddata['status_hcm_gm'] !== "Pending") { ?>
                                                                            <select class="form-control" name="status_manager_gm_bod" id="status_manager_gm_bod">
                                                                                <option><?php echo ucwords($ddata['status_manager_gm_bod']); ?></option>
                                                                                <option value="Approve">Approve</option>
                                                                                <option value="Disapprove">Disapprove</option>
                                                                            </select>
                                                                        <?php } else { ?>
                                                                            <select class="form-control" name="status_manager_gm_bod" id="status_manager_gm_bod" readonly>
                                                                                <option><?php echo ucwords($ddata['status_manager_gm_bod']); ?></option>
                                                                            </select>
                                                                        <?php } ?>
                                                                    <?php } elseif ($_GET['act'] == "editjoin" && $ddata['status_manager_gm_bod'] == "Approve" || $_GET['act'] == "editjoin" && $ddata['status_manager_gm_bod'] == "Disapprove") { ?>
                                                                        <select class="form-control" name="status_manager_gm_bod" id="status_manager_gm_bod" readonly>
                                                                            <option><?php echo ucwords($ddata['status_manager_gm_bod']); ?></option>
                                                                        </select>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan </label>
                                                                <div class="col-sm-9">
                                                                    <textarea class="form-control form-control-sm" name="catatan_offering" id="catatan_offering" rows="2" style="height: 206px;" readonly><?php if ($_GET['act'] == 'editjoin') {
                                                                                                                                                                                                                echo $ddata['catatan_offering'];
                                                                                                                                                                                                            } ?></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Join Date </label>
                                                                <div class="col-sm-9">
                                                                    <?php if ($_GET['act'] == "editjoin" && $ddata['join_date'] == null) { ?>
                                                                        <input type="date" class="form-control form-control-sm" id="join_date" name="join_date" value="">
                                                                    <?php } else { ?>
                                                                        <input type="date" class="form-control form-control-sm" id="join_date" name="join_date" value="<?php if ($_GET['act'] == 'editjoin') {
                                                                                                                                                                            echo $ddata['join_date'];
                                                                                                                                                                        } ?>" readonly>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if ($_GET['act'] == 'editjoin') { ?>
                                            <table>
                                                <tr>
                                                    <th class="text-center" rowspan="2">Email</th>
                                                    <th class="text-center" rowspan="2">Tanggal Interview</th>
                                                    <th class="text-center" rowspan="2">Interview User</th>
                                                    <th class="text-center" rowspan="2">Interview HCM</th>
                                                    <th class="text-center" rowspan="2">Catatan</th>
                                                </tr>
                                                <tr>
                                                </tr>
                                                <?php do { ?>
                                                    <tr>
                                                        <td>
                                                            <input type="hidden" class="form-control form-control-sm" name="email_id[]" id="email_id[]" rows="2" value="<?php echo $dataemail[0]['email_id']; ?>">
                                                            <input type="text" class="form-control form-control-sm" name="email_update[]" id="email_update[]" rows="2" value="<?php echo $dataemail[0]['email']; ?>" readonly>
                                                        </td>
                                                        <td>
                                                            <input type="date" class="form-control form-control-sm" name="tanggal_interview_update[]" id="tanggal_interview_update[]" rows="2" value="<?php echo $dataemail[0]['tanggal_interview']; ?>" readonly>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control form-control-sm" name="interview_user_update[]" id="interview_user_update[]" rows="2" value="<?php echo $dataemail[0]['interview_user']; ?>" readonly>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control form-control-sm" name="interview_hcm_update[]" id="interview_hcm_update[]" rows="2" value="<?php echo $dataemail[0]['interview_hcm']; ?>" readonly>
                                                        </td>
                                                        <td>
                                                            <textarea class="form-control form-control-sm" name="catatan_update[]" id="catatan_update[]" rows="2" readonly><?php echo $dataemail[0]['catatan']; ?></textarea>
                                                        </td>
                                                    </tr>
                                                <?php } while ($dataemail[0] = $dataemail[1]->fetch_assoc()); ?>
                                            </table>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>




                            <input type="submit" class="btn btn-secondary" name="cancel" value="Cancel">
                            <?php if (isset($_GET['act']) && $_GET['act'] == 'edit') { ?>
                                <input type="submit" class="btn btn-primary" name="edit" value="Save">
                                <?php if ($trx_request_requirement[0]['status_gm'] == "Approve" && $trx_request_requirement[0]['status_gm_hcm'] == "Approve") { ?>
                                    <input type="submit" class="btn btn-primary" name="submitted" value="Submitted">
                                <?php } ?>
                                <?php if ($trx_request_requirement[0]['status_rekrutmen'] == "Penambahan" && $trx_request_requirement[0]['status_gm'] == "Approve" && $trx_request_requirement[0]['status_gm_hcm'] == "Approve" && $trx_request_requirement[0]['status_gm_bod'] == "Approve") { ?>
                                    <input type="submit" class="btn btn-primary" name="submitted" value="Submitted">
                                <?php } ?>
                            <?php } elseif (isset($_GET['act']) && $_GET['act'] == 'add') { ?>
                                <input type="submit" class="btn btn-primary" name="add" value="Save">
                            <?php } elseif (isset($_GET['act']) && $_GET['act'] == 'editapproval') { ?>
                                <input type="submit" class="btn btn-primary" name="editapproval" value="Save">
                            <?php } elseif (isset($_GET['act']) && $_GET['act'] == 'editshare') { ?>
                                <input type="submit" class="btn btn-primary" name="editshare" value="Save">
                                <input type="submit" class="btn btn-primary" name="complete" value="Complete">
                            <?php } elseif (isset($_GET['act']) && $_GET['act'] == 'editinterview') { ?>
                                <input type="submit" class="btn btn-primary" name="editinterview" value="Save">
                                <input type="submit" class="btn btn-primary" name="selesai" value="Complete">
                            <?php } elseif (isset($_GET['act']) && $_GET['act'] == 'approval') { ?>
                                <input type="submit" class="btn btn-primary" name="approval" value="Save">
                            <?php } elseif (isset($_GET['act']) && $_GET['act'] == 'editoffering') { ?>
                                <input type="submit" class="btn btn-primary" name="editoffering" value="Save">
                                <input type="submit" class="btn btn-primary" name="editofferingcomplete" value="Complete">
                            <?php } elseif (isset($_GET['act']) && $_GET['act'] == 'editjoin') { ?>
                                <input type="submit" class="btn btn-primary" name="editjoin" value="Save">
                            <?php } ?>
                            </form>

                            <!-- <script>
                                    $("#divisi").on('change', function() {
                                        var divisi = btoa($(this).val());

                                        window.location = window.location.pathname + "?mod=request" + "&act=add&divisi=" + divisi;
                                    })
                                </script> -->


                            <!-- internal script -->
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
                                $(document).ready(function() {
                                    // MANAGER HCM
                                    var statusGMHCM = $("#status_hcm_gm");
                                    var catatanHCMGM = $("#catatan-hcm-gm");

                                    statusGMHCM.change(function() {
                                        var selectedStatus = statusGMHCM.val();
                                        if (selectedStatus === "Pending") {
                                            catatanHCMGM.hide();
                                        } else {
                                            catatanHCMGM.show();
                                        }
                                    });

                                    // Trigger the change event on page load to set the initial state.
                                    statusGMHCM.trigger("change");


                                    // GM HCM
                                    var statusManagerHCM = $("#status_manager_hcm");
                                    var catatanManagerHCM = $("#catatan-manager-hcm");

                                    statusManagerHCM.change(function() {
                                        var selectedStatus = statusManagerHCM.val();
                                        if (selectedStatus === "Pending") {
                                            catatanManagerHCM.hide();
                                        } else {
                                            catatanManagerHCM.show();
                                        }
                                    });

                                    // Trigger the change event on page load to set the initial state.
                                    statusManagerHCM.trigger("change");

                                    // MANAGER/GM/BOD
                                    var statusManagerGMBOD = $("#status_manager_gm_bod");
                                    var catatanManagerGMBOD = $("#catatan-manager-gm-bod");

                                    statusManagerGMBOD.change(function() {
                                        var selectedStatus = statusManagerGMBOD.val();
                                        if (selectedStatus === "Pending") {
                                            catatanManagerGMBOD.hide();
                                        } else {
                                            catatanManagerGMBOD.show();
                                        }
                                    });

                                    // Trigger the change event on page load to set the initial state.
                                    statusManagerGMBOD.trigger("change");

                                });
                            </script>

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
                                                <form id="upload_form" enctype="multipart/form-data" method="post" action="components/modules/upload/upload_2.php">
                                                    <div>
                                                        <div><label for="image_file">Please select image file</label></div>
                                                        <div><input type="file" name="image_file" id="image_file" onchange="fileSelected();" /></div>
                                                    </div>
                                                    <div>
                                                        <!-- <input type="text" value="<?php echo $ddata['divisi']; ?>"> -->
                                                        <label for="">Recruitment Source</label>
                                                        <select class="form-control" name="link_from" id="link_from">
                                                            <option></option>
                                                            <?php do { ?>
                                                                <option value="<?php echo $linkfrom[0]['link_from']; ?>"><?php echo $linkfrom[0]['link_from']; ?></option>
                                                            <?php } while ($linkfrom[0] = $linkfrom[1]->fetch_assoc()); ?>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <input type="button" value="Upload" onclick="startUploading2()" />
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


                            <!-- MODAL LINK FROM BARU -->
                            <div class="modal fade" id="tambahLinkFormModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form method="post">
                                            <div class="modal-header">
                                                <h5 class="col-sm-9" id="exampleModalLabel">New Recruitmen Source</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">New Source</label>
                                                    <input type="text" class="form-control" id="link_from" name="link_from">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                <!-- Tombol Simpan dengan logika PHP -->

                                                <input type="submit" class="btn btn-primary" name="cobaan" value="Save">

                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="tambahAssignFormModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form method="post">
                                            <div class="modal-header">
                                                <h5 class="col-sm-9" id="exampleModalLabel">New Assign Recruitment</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">

                                                <div class="form-group">
                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">New Assign</label>
                                                    <input type="text" class="form-control" id="assign" name="assign">
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                <input type="submit" class="btn btn-primary" name="assignbaru" value="Save">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <script>
                                $(document).ready(function() {
                                    $("#addemail").click(function() {
                                        var html = '';
                                        html += '<div class="row mb-3" id="inputemail">';
                                        html += '<div class="row">';
                                        html += '<div class="col-3 table-bordered"><b>Email</b><input type="text" class="form-control" name="email[]" id="email[]" rows="2"></input></div>';
                                        html += '<div class="col-2 table-bordered"><b>Tanggal Interview</b><input type="datetime-local" class="form-control" name="tanggal_interview[]" id="tanggal_interview[]" rows="2"></input></div>';
                                        html += '<div class="col-2 table-bordered"><b>Link Webex</b><input type="text" class="form-control" name="link_webex[]" id="link_webex[]" rows="2"></input></div>';
                                        html += '<div class="col-2 table-bordered"><b>PIC (Reqruiter) </b><input type="text" class="form-control" name="pic[]" id="pic[]" rows="2" value="<?php echo $coba[0]['assign']; ?>"readonly></input></div>';
                                        html += '<div class="col-3 table-bordered"><b>HCM</b><select class="form-control mb-3" name="interview_hcm[]" id="interview_hcm[]" required>';
                                        html += "<option value='malikwitama@gmail.com'>malik aulia wiratama</option>";
                                        <?php do {  ?>
                                            html += "<option value='<?php echo $interview_hcm[0]['employee_email']; ?>'><?php echo $interview_hcm[0]['employee_name']; ?></option>";
                                        <?php } while ($interview_hcm[0] = $interview_hcm[1]->fetch_assoc()); ?>
                                        html += '</select></div>';
                                        html += '<div class="col-3 table-bordered"><b>Interviewer User</b><select class="form-control mb-3" name="interview_user[]" id="interview_user[]" required>';
                                        html += "<option value='malikwitama@gmail.com'>malik aulia wiratama</option>";
                                        <?php do {  ?>
                                            html += "<option value='<?php echo $interview_user[0]['employee_email']; ?>'><?php echo $interview_user[0]['employee_name']; ?></option>";
                                        <?php } while ($interview_user[0] = $interview_user[1]->fetch_assoc()); ?>
                                        html += '</select></div>';
                                        html += '<div class="col-2"><b>Action</b><div></div><button id="removeRow-affected-ci" type="button" class="btn btn-danger">Remove</button></div>';
                                        html += '</div>';
                                        html += '<div class="col-2"><b></b>';
                                        html += '<div class="new-interview-user-container"></div><button id="addnewinterviewuser" type="button" class="btn btn-primary">Add New Interview User</button></div>';
                                        html += '</div>';

                                        html += '</div>';

                                        $('#newRow-affected-ci').append(html);
                                    });

                                    var maxInterviewUsers = 7; // Set the maximum number of interview users

                                    $(document).on('click', '#addnewinterviewuser', function() {
                                        var currentInterviewUsers = $(this).closest('.row').find('.new-interview-user-container .row').length;

                                        // Check if the maximum number of interview users has been reached
                                        if (currentInterviewUsers < maxInterviewUsers) {
                                            var html = '';
                                            html += '<div class="row mb-3">';
                                            html += '<div class="row">';
                                            html += '<div class="col-8 table-bordered"><b>New Interviewer User</b><select class ="form-control mb-3" name="interview_user[]" id="interview_user[]" required>';
                                            html += "<option value='malikwitama@gmail.com'>malik aulia wiratama</option>";

                                            <?php do {  ?>
                                                html += "<option value='<?php echo $interview_user_1[0]['employee_email']; ?>'><?php echo $interview_user_1[0]['employee_name']; ?></option>";
                                            <?php } while ($interview_user_1[0] = $interview_user_1[1]->fetch_assoc()); ?>

                                            html += '</select></div>';
                                            html += '<div class="col-4"><b>Action</b><div></div><button class="btn btn-danger remove-new-interview-user" type="button">Remove</button></div>';
                                            html += '</div>';
                                            html += '</div>';

                                            $(this).closest('.row').find('.new-interview-user-container').append(html);
                                        } else {
                                            // Alert or handle the case when the maximum number of interview users is reached
                                            alert('Maksimal Interview user (5)');
                                        }
                                    });

                                    $(document).on('click', '.remove-new-interview-user', function() {
                                        $(this).closest('.row').remove();
                                    });

                                    $(document).on('click', '#removeRow-affected-ci', function() {
                                        $(this).closest('#inputemail').remove();
                                    });
                                });
                            </script>

                            <script>
                                $("#divisi").on('change', function() {
                                    var divisi = $(this).val();

                                    window.location = window.location.pathname + "?mod=new_request" + "&act=add&divisi=" + divisi;
                                })
                            </script>