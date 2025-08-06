<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script> -->
<?php

global $DBHCM;

$servicebudget = "SERVICE_BUDGET";
$DBSB = get_conn($servicebudget);

?>
<?php
if (
    $_GET['act'] == 'editinterview' || $_GET['act'] == "editjoin"
) {
    $kondisi = "project_code=" . "\"" . $_GET['project_code'] . "\"";

    $dataemail = $DBHCM->get_data("hcm_requirement_interview", $kondisi);
    // var_dump($kondisi);
    // die;
} ?>
<?php
if ($_GET['act'] == 'add') {
    global $DBHCM;
    $user = $_SESSION['Microservices_UserEmail'];
    // $namadivisinew = $DBHCM->get_sqlV2("SELECT DISTINCT organization_name FROM sa_view_employees WHERE resign_date IS NULL AND employee_name IN (SELECT leader_name FROM sa_view_employees WHERE employee_email = '$user')");
    // $namadivisinew = $DBHCM->get_sqlV2("SELECT * FROM sa_view_employees WHERE resign_date IS NULL AND employee_name IN (SELECT leader_name FROM sa_view_employees WHERE employee_email = '$user')");
    $nariknamadivisi = $DBHCM->get_sqlV2("SELECT DISTINCT organization_name FROM sa_view_employees WHERE job_level = 2 AND resign_date is null ORDER BY organization_name ASC");
    // $nariknamadivisi = $DBHCM->get_sqlV2("SELECT employee_name, employee_email, organization_name, job_level, job_title, job_structure, leader_name, leader_email FROM sa_view_employees WHERE employee_email IS NOT null AND resign_date IS null AND job_level = 2");
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
                                    <select class="form-control" name="divisi" id="divisi" required>
                                        <?php if (isset($_GET['divisi'])) {
                                            $namadivisi = $DBHCM->get_data("view_employees", "resign_date is null AND employee_email IS NOT NULL AND organization_name LIKE '" . $_GET['divisi'] . "%'");
                                            // $namadivisi = $DBHCM->get_sqlV2("SELECT * FROM sa_view_employees WHERE resign_date is null AND employee_email like '%" . $user . "%'");
                                        ?>
                                            <option value="<?php echo $namadivisi[0]['organization_name']; ?>"><?php echo $namadivisi[0]['organization_name']; ?></option>
                                        <?php } ?>
                                        <option></option>
                                        <?php $namadivisinew = $DBHCM->get_sqlV2("SELECT DISTINCT organization_name FROM sa_view_employees WHERE resign_date IS NULL AND employee_name IN (SELECT leader_name FROM sa_view_employees WHERE employee_email = '$user')"); ?>
                                        <?php do { ?>
                                            <option><?php echo $namadivisinew[0]['organization_name']; ?></option>
                                        <?php } while ($namadivisinew[0] = $namadivisinew[1]->fetch_assoc()); ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Posisi <b>*</b></label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="posisi" id="posisi" required>
                                        <?php $get_posisi = $DBHCM->get_sqlV2("SELECT DISTINCT posisi FROM sa_hcm_requirement_posisi") ?>
                                        <option></option>
                                        <?php while ($row = $get_posisi[1]->fetch_assoc()) { ?>
                                            <option value="<?php echo $row['posisi']; ?>">
                                                <?php echo $row['posisi']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <!-- <input type="text" class="form-control form-control-sm" id="posisi" name="posisi"> -->
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm"></label>
                                <div class="col-sm-9">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahposisibaru">
                                        Tambah Posisi
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- KANAN -->
                        <div class="col-lg-6">
                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Jumlah Dibutuhkan<b>*</b></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" id="jumlah_dibutuhkan" name="jumlah_dibutuhkan" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Tanggal Dibutuhkan<b>*</b></label>
                                <div class="col-sm-9">
                                    <input type="date" class="form-control form-control-sm" id="tanggal_dibutuhkan" name="tanggal_dibutuhkan" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Deskripsi Pekerjaan<b>*</b></label>
                                <div class="col-sm-9">
                                    <textarea type="text" class="form-control" id="deskripsi" name="deskripsi" rows="2" required></textarea>
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
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Code <b>*</b></label>
                                <div class="col-sm-9">
                                    <select class="form-select form-select-sm" name="project_code" id="project_code" required>
                                        <?php $get_all_kp = $DBSB->get_sqlV2("SELECT `project_code`, `project_name` FROM `sa_trx_project_list` WHERE `status` = 'approved' OR `status` = 'acknowledge' GROUP BY `project_code`, `project_name` ORDER BY `project_id` DESC"); ?>
                                        <?php if (!isset($_GET['project_code']) && $_GET['project_code'] == "Non-Project") { ?>
                                            <option value="Non-Project">Non-Project</option>
                                        <?php } ?>
                                        <?php if (isset($_GET['project_code']) && $_GET['project_code'] != "Non-Project") {
                                            $get_kp = $DBSB->get_data("trx_project_list", "status IN ('acknowledge', 'approved') AND project_code like '%" . $_GET['project_code'] . "%'"); ?>
                                            <option value="<?php echo $get_kp[0]['project_code']; ?>"><?php echo $get_kp[0]['project_code'] . " [" . $get_kp[0]['project_name'] . "]"; ?></option>
                                        <?php } ?>
                                        <option value="Non-Project">Non-Project</option>
                                        <?php do { ?>
                                            <option value="<?php echo $get_all_kp[0]['project_code']; ?>"><?php echo $get_all_kp[0]['project_code'] . " [" . $get_all_kp[0]['project_name'] . "]"; ?></option>
                                        <?php } while ($get_all_kp[0] = $get_all_kp[1]->fetch_assoc()); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Nama Project <b>*</b></label>
                                <div class="col-sm-9">
                                    <?php if (!isset($_GET['project_code']) || $_GET['project_code'] == "Non-Project") { ?>
                                        <input type="text" class="form-control form-control-sm" id="nama_project" name="nama_project" required>
                                    <?php }
                                    if (isset($_GET['project_code']) && $_GET['project_code'] != "Non-Project") {
                                        $project_name = $DBSB->get_sqlV2("SELECT project_name, project_code FROM sa_trx_project_list WHERE project_code LIKE '%" . $_GET['project_code'] . "%'");
                                        // $project_name = $DBSB->get_data("trx_project_list", "status IN ('acknowledge', 'approved') AND project_code LIKE '%" . $_GET['project_code'] . "%'"); 
                                    ?>
                                        <input type="text" class="form-control form-control-sm" id="nama_project" name="nama_project" value="<?php echo $project_name[0]['project_name']; ?>" required>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Nama Customer <b>*</b></label>
                                <div class="col-sm-9">
                                    <?php if (!isset($_GET['project_code']) || $_GET['project_code'] == "Non-Project") { ?>
                                        <input type="text" class="form-control form-control-sm" id="nama_customer" name="nama_customer" required>
                                    <?php }
                                    if (isset($_GET['project_code']) && $_GET['project_code'] != "Non-Project") {
                                        $project_name =
                                            $DBSB->get_sqlV2("SELECT project_name, project_code,customer_name FROM sa_trx_project_list WHERE project_code LIKE '%" . $_GET['project_code'] . "%'"); ?>
                                        <input type="text" class="form-control form-control-sm" id="nama_customer" name="nama_customer" value="<?php echo $project_name[0]['customer_name']; ?>" required>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Periode Project <b>*</b></label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" id="periode_project" name="periode_project" placeholder="Periode Project" required>
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
                                <select class="form-control" name="status_rekrutmen" id="status_rekrutmen" required>
                                    <option value=""></option>
                                    <option value="Penambahan">Penambahan</option>
                                    <option value="Penggantian">Penggantian</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3" id="reason-row">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Reason <b>*</b></label>
                            <div class="col-sm-9">
                                <!-- <input type="text" class="form-control form-control-sm" id="reason_penggantian" name="reason_penggantian" placeholder="Penggantian dari Karyawan lama"> -->
                                <select class="form-control" name="reason_penggantian" id="reason_penggantian">
                                    <?php $internal = $DBHCM->get_sqlV2("SELECT DISTINCT employee_email, employee_name FROM sa_view_employees WHERE resign_date is null ORDER BY employee_name ASC"); ?>
                                    <option></option>
                                    <option value="malik.aulia@mastersystem.co.id">Malik Aulia Wiratama</option>
                                    <?php while ($row = $internal[1]->fetch_assoc()) { ?>
                                        <option value="<?php echo $row['employee_email']; ?>">
                                            <?php echo $row['employee_name'] . " (" . $row['employee_email'] . ")"; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Karyawan <b>*</b></label>
                            <div class="col-sm-9">
                                <select class="form-control" name="status_karyawan" id="status_karyawan" required>
                                    <option></option>
                                    <option value="Kontrak">Kontrak</option>
                                    <option value="Percobaan">Percobaan</option>
                                    <option value="Tetap">Tetap</option>
                                    <option value="Outsourching">Outsourching</option>
                                    <option value="Magang">Magang</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3" id="kontrak-row">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Deskripsi Kontrak <b>*</b></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm" id="deskripsi_kontrak" name="deskripsi_kontrak" placeholder="Kontrak Berapa lama">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Apakah Ada Di MPP <b>*</b></label>
                            <div class="col-sm-9">
                                <select class="form-control" name="mpp" id="mpp" required>
                                    <option></option>
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>

                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Jenis Kelamin <b>*</b></label>
                            <div class="col-sm-9">
                                <select class="form-control" name="jenis_kelamin" id="jenis_kelamin" required>
                                    <option></option>
                                    <option value="Any">Any</option>
                                    <option value="Laki-Laki">Laki-Laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>

                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Usia <b>*</b></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm" id="usia" name="usia" required>
                            </div>
                        </div>
                    </div>
                    <!-- KANAN -->
                    <div class="col-lg-6">
                        <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Pendidikan Minimal <b>*</b></label>
                            <div class="col-sm-9">
                                <select class="form-control" name="pendidikan_minimal" id="pendidikan_minimal" required>
                                    <option></option>
                                    <option value="SMA (High School)">SMA (High School)</option>
                                    <option value="D3 (Diploma)">D3 (Diploma)</option>
                                    <option value="S1 (Bachelor)">S1 (Bachelor)</option>
                                    <option value="S2 (Master)">S2 (Master)</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Fakultas/Jurusan<b>*</b></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm" id="jurusan" name="jurusan" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Pengalaman Minimal<b>*</b></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm" id="pengalaman_minimal" name="pengalaman_minimal" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Kompetensi Teknis Yang Harus Dikuasai<b>*</b></label>
                            <div class="col-sm-9">
                                <textarea type="text" class="form-control" id="kompetensi_teknis" name="kompetensi_teknis" rows="2" required></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Kompetensi Non Teknis Yang Harus Dikuasai<b>*</b></label>
                            <div class="col-sm-9">
                                <textarea type="text" class="form-control" id="kompetensi_non_teknis" name="kompetensi_non_teknis" rows="2" required></textarea>
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
                                <select class="form-control" name="kandidat" id="kandidat" required>
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
                                    <?php $internal = $DBHCM->get_sqlV2("SELECT DISTINCT employee_email, employee_name FROM sa_view_employees WHERE resign_date is null ORDER BY employee_name ASC"); ?>
                                    <option></option>
                                    <option value="malik.aulia@mastersystem.co.id">Malik Aulia Wiratama</option>
                                    <?php while ($row = $internal[1]->fetch_assoc()) { ?>
                                        <option value="<?php echo $row['employee_email']; ?>">
                                            <?php echo $row['employee_name'] . " (" . $row['employee_email'] . ")"; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan<b>*</b></label>
                            <div class="col-sm-9">
                                <textarea type="text" class="form-control form-control-sm" id="catatan" name="catatan" rows="2" required></textarea>
                            </div>
                        </div>
                    </div>
                    <!-- KANAN -->
                    <div class="col-lg-6">
                        <!-- <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan Lain </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm" id="diisi_oleh_hcm" name="diisi_oleh_hcm">
                            </div>
                        </div> -->
                        <div class="row mb-3">
                            <label for="inputFromSalary" class="col-sm-3 col-form-label col-form-label-sm">Range Salary <b>*</b></label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control text-end" style="text-align: right;" id="from_salary" name="from_salary" onchange="format_amount_idr('from_salary');" required>
                            </div>
                            -
                            <div class="col-sm-3">
                                <input type="text" class="form-control text-end" style="text-align: right;" id="to_salary" name="to_salary" onchange="format_amount_idr('to_salary');" required>
                            </div>
                        </div>
                        <script>
                            function format_amount_idr(id) {
                                var input = document.getElementById(id);
                                var value = input.value.replace(/,/g, ''); // Remove existing commas

                                if (!isNaN(value) && value !== '') {
                                    var formattedValue = parseFloat(value).toLocaleString('en-US'); // Format with commas
                                    input.value = formattedValue.replace(/,/g, '.') + ',00'; // Replace commas with periods and append ,00
                                }
                            }

                            // Call the function on page load to format any pre-filled value
                            document.addEventListener('DOMContentLoaded', (event) => {
                                format_amount_idr('from_salary');
                                format_amount_idr('to_salary');
                            });
                        </script>
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
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Approval Leader <b>*</b></label>
                            <div class="col-sm-9">
                                <select class="form-control" name="gm" id="gm" required>
                                    <!-- <option value="Malik Aulia Wiratama <malik.aulia@mastersystem.co.id>">Malik Aulia Wiratama</option> -->
                                    <?php
                                    if (isset($_GET['divisi'])) {
                                        $gm = $DBHCM->get_sqlV2("SELECT DISTINCT employee_name, employee_email, organization_name, job_structure FROM sa_view_employees WHERE job_level = 2 AND employee_email IS NOT NULL AND organization_name LIKE '" . $_GET['divisi'] . "%'");
                                        $namadivisinew = $DBHCM->get_sqlV2("SELECT * FROM sa_view_employees WHERE resign_date IS NULL AND employee_name IN (SELECT leader_name FROM sa_view_employees WHERE employee_email = '$user')");
                                        // Check if there are results
                                        if ($namadivisinew && $namadivisinew[1]->num_rows > 0) {
                                            while ($row = $namadivisinew[1]->fetch_assoc()) {
                                                $employee_name = $row['employee_name'];
                                                $employee_email = $row['employee_email'];
                                                $option_value = htmlspecialchars("$employee_name <$employee_email>");
                                                $option_label = htmlspecialchars("$employee_name ($employee_email)");
                                    ?>
                                                <option value="<?php echo $option_value; ?>"><?php echo $option_label; ?></option>
                                            <?php
                                            }
                                        } else {
                                            ?>
                                            <option value="">No leaders found</option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Leader <b>*</b></label>
                            <div class="col-sm-9">
                                <select class="form-control" name="status_gm" id="status_gm" required>
                                    <?php
                                    // $malik = "ardi.haris@mastersystem.co.id";

                                    $get_structure = $DBHCM->get_sqlV2("SELECT * FROM sa_view_employees WHERE organization_name LIKE '%" . $_GET['divisi'] . "%' AND leader_email = '$user'");
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
                                <select class="form-control" name="gm_hcm" id="gm_hcm" required>
                                    <?php $gmhcm = $DBHCM->get_sqlV2("SELECT * FROM sa_view_employees_v2 WHERE department_name = 'Human Capital Management' AND job_level='2'"); ?>
                                    <!-- <option value="Malik Aulia Wiratama <malik.aulia@mastersystem.co.id>">Malik Aulia Wiratama</option> -->
                                    <?php //do { 
                                    ?>
                                    <option value="<?php echo $gmhcm[0]['employee_name'] . " <" . $gmhcm[0]['employee_email'] . ">"; ?>"><?php echo $gmhcm[0]['employee_name'] . " (" . $gmhcm[0]['employee_email'] . ")"; ?></option>
                                    <?php //} while ($gmhcm[0] = $gmhcm[1]->fetch_assoc()); 
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status GM HCM<b>*</b></label>
                            <div class="col-sm-9">
                                <select class="form-control" name="status_gm_hcm" id="status_gm_hcm" required>
                                    <?php
                                    $get_structure = $DBHCM->get_sqlV2("SELECT * FROM sa_view_employees WHERE job_level = 2 AND employee_email = '$user' AND organization_name LIKE '%Human Capital Management%'");
                                    if ($get_structure[2] > 0) {
                                    ?>
                                        <option value="Approve">Approve</option>
                                    <?php } else { ?>
                                        <option value="Pending">Pending</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6" id="approval-bod-section">
                        <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Approval BOD<b>*</b></label>
                            <div class="col-sm-9">
                                <select class="form-select" name="gm_bod" id="gm_bod">
                                    <?php $gmbod = $DBHCM->get_sqlV2("SELECT * FROM sa_view_employees WHERE resign_date IS NULL AND employee_name IN (SELECT leader_name FROM sa_view_employees WHERE employee_email = '$user')"); ?>
                                    <!-- <option></option> -->
                                    <!-- <option value="Malik Aulia Wiratama <malik.aulia@mastersystem.co.id>">Malik Aulia Wiratama</option> -->
                                    <?php while ($row = $gmbod[1]->fetch_assoc()) { ?>
                                        <option value="<?php echo $row['leader_name'] . " <" . $row['leader_email'] . ">"; ?>">
                                            <?php echo $row['leader_name'] . " <" . $row['leader_email'] . ">"; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status GM BOD<b>*</b></label>
                            <div class="col-sm-9">
                                <select class="form-select" name="status_gm_bod" id="status_gm_bod">
                                    <!-- <option></option> -->
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
    $HCMfull = $DBHCM->get_sqlV2("SELECT employee_email FROM sa_view_employees WHERE employee_email = '" . $_SESSION['Microservices_UserEmail'] .
        "'  AND job_structure LIKE '%JW ATN WM HCM Recruitment%' AND resign_date is null");


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
                            <div class="row">
                                <div class="card shadow mb-1">
                                    <div class="card-header fw-bold">Department</div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="row mb-3" hidden>
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">ID</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control form-control-sm" id="id" name="id" value="<?php echo $ddata['id']; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Divisi <b>*</b></label>
                                                        <div class="col-sm-9">
                                                            <select class="form-control" name="divisi_edit" id="divisi_edit" readonly>
                                                                <option value="<?php echo $ddata['divisi']; ?>" readonly><?php echo $ddata['divisi']; ?></option>
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>


                                                    <input type="text" hidden class="form-control form-control-sm" id="id_fpkb" name="id_fpkb" value="<?php echo $ddata['id_fpkb']; ?>" readonly>

                                                    <input type="text" hidden class="form-control form-control-sm" id="request_by" name="request_by" value="<?php echo $ddata['request_by']; ?>" readonly>


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
                                                            <textarea type="text" class="form-control" id="deskripsi" name="deskripsi" rows="2" readonly><?php echo $ddata['deskripsi']; ?></textarea>
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
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Code</label>
                                                        <div class="col-sm-9">
                                                            <input readonly type="text" class="form-control form-control-sm" id="project_code" name="project_code" value="<?php echo $ddata['project_code']; ?>">
                                                        </div>
                                                    </div>
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
                                                        <select class="form-control" name="status_rekrutmen_edit" id="status_rekrutmen_edit" readonly>
                                                            <option value="<?php echo $ddata['status_rekrutmen']; ?>" readonly><?php echo $ddata['status_rekrutmen']; ?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Karyawan <b>*</b></label>
                                                    <div class="col-sm-9">
                                                        <select class="form-control" name="status_karyawan_edit" id="status_karyawan_edit" readonly>
                                                            <option value="<?php echo $ddata['status_karyawan']; ?>" readonly><?php echo $ddata['status_karyawan']; ?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Apakah Ada Di MPP <b>*</b></label>
                                                    <div class="col-sm-9">
                                                        <select class="form-control" name="mpp_edit" id="mpp_edit" readonly>
                                                            <option value="<?php echo $ddata['mpp']; ?>" readonly><?php echo $ddata['mpp']; ?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Jenis Kelamin <b>*</b></label>
                                                    <div class="col-sm-9">
                                                        <select class="form-control" name="jenis_kelamin_edit" id="jenis_kelamin_edit" readonly>
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
                                                        <select class="form-control" name="pendidikan_minimal_edit" id="pendidikan_minimal_edit" readonly>
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
                                                        <select class="form-control" name="kandidat_edit" id="kandidat_edit" readonly>
                                                            <option value="<?php echo $ddata['kandidat']; ?>"><?php echo $ddata['kandidat']; ?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Internal <b>*</b></label>
                                                    <div class="col-sm-9">
                                                        <select class="form-control" name="Internal" id="Internal" readonly>
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
                                                <!-- <div class="row mb-3">
                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan Lain </label>
                                                    <div class="col-sm-9">
                                                        <input readonly type="text" class="form-control form-control-sm" id="diisi_oleh_hcm" name="diisi_oleh_hcm" value="<?php //echo $ddata['diisi_oleh_hcm']; 
                                                                                                                                                                            ?>">
                                                    </div>
                                                </div> -->
                                                <div class="row mb-3">
                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Range Salary <b>*</b></label>
                                                    <div class="col-sm-9">
                                                        <input readonly type="text" class="form-control text-end" style="text-align: right;" id="range_salary" name="range_salary" value="<?php echo $ddata['range_salary']; ?>">
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
                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Approval Leader <b>*</b></label>
                                                    <div class="col-sm-9">
                                                        <select class="form-control" name="gm" id="gm" readonly>
                                                            <option value="<?php echo $ddata['gm']; ?>"><?php echo $ddata['gm']; ?></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Leader <b>*</b></label>
                                                    <div class="col-sm-9">
                                                        <?php if ($_GET['act'] == "edit" && $ddata['status_gm'] == "Pending") { ?>
                                                            <?php if ($ddata['gm'] == $_SESSION['Microservices_UserName'] . " <" . $_SESSION['Microservices_UserEmail'] . ">") { ?>
                                                                <!-- approve hanya bisa GM -->
                                                                <select class="form-control" name="status_gm" id="status_gm" readonly>
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
                                                <?php if ($_GET['act'] == "edit" && $ddata['status_gm'] == "Pending" && $ddata['gm'] == $_SESSION['Microservices_UserName'] . " <" . $_SESSION['Microservices_UserEmail'] . ">") { ?>
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan Leader <b>*</b></label>
                                                        <div class="col-sm-9">
                                                            <?php if ($_GET['act'] == 'edit' && $ddata['catatan_gm'] == null) { ?>
                                                                <textarea type="text" class="form-control form-control-sm" id="catatan_gm" name="catatan_gm" rows="2"></textarea>
                                                            <?php }  ?>
                                                        </div>
                                                    </div>
                                                <?php } elseif ($_GET['act'] == 'edit' && $ddata['catatan_gm'] !== null) { ?>
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan Leader <b>*</b></label>
                                                        <div class="col-sm-9">
                                                            <?php if ($_GET['act'] == 'edit' && $ddata['catatan_gm'] !== null) { ?>
                                                                <textarea type="text" class="form-control form-control-sm" id="catatan_gm" name="catatan_gm" rows="2" readonly> <?php echo $ddata['catatan_gm']; ?> </textarea>
                                                            <?php }  ?>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <!-- <div class="row mb-3" id="catatan-gm-row">
                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan Leader <b>*</b></label>
                                                    <div class="col-sm-9">
                                                        <?php //if ($_GET['act'] == 'edit' && $ddata['catatan_gm'] == null) { 
                                                        ?>
                                                            <textarea type="text" class="form-control form-control-sm" id="catatan_gm" name="catatan_gm" rows="2"></textarea>
                                                        <?php //} elseif ($_GET['act'] == 'edit' && $ddata['catatan_gm'] !== null) { 
                                                        ?>
                                                            <textarea type="text" class="form-control form-control-sm" id="catatan_gm" name="catatan_gm" rows="2" readonly> <?php //echo $ddata['catatan_gm']; 
                                                                                                                                                                            ?> </textarea>
                                                        <?php //} 
                                                        ?>
                                                    </div>
                                                </div> -->
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
                                                        <?php if ($_GET['act'] == "edit" && $ddata['status_gm'] == "Pending" || $_GET['act'] == "edit" && $ddata['status_gm'] == "Disapprove" || $_GET['act'] == "edit" && $ddata['status_gm_hcm'] == null) { ?>
                                                            <!-- approve hanya bisa GM HCM -->
                                                            <select class="form-control" name="status_gm_hcm" id="status_gm_hcm" readonly>
                                                                <option><?php echo ucwords($ddata['status_gm_hcm']); ?></option>
                                                            </select>
                                                        <?php } elseif ($_GET['act'] == "edit" && $ddata['status_gm'] == "Approve" && $_GET['act'] == "edit" && $ddata['status_gm_hcm'] == "Pending") { ?>
                                                            <?php if ($ddata['gm_hcm'] == $_SESSION['Microservices_UserName'] . " <" . $_SESSION['Microservices_UserEmail'] . ">") { ?>
                                                                <select class="form-control" name="status_gm_hcm" id="status_gm_hcm" readonly>
                                                                    <option><?php echo ucwords($ddata['status_gm_hcm']); ?></option>
                                                                    <!-- <option value="Approve">Approve</option>
                                                                    <option value="Disapprove">Disapprove</option> -->
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
                                                <?php if ($_GET['act'] == "edit" && $ddata['status_gm'] == "Approve" && $ddata['status_gm_hcm'] == "Pending" && $ddata['gm_hcm'] == $_SESSION['Microservices_UserName'] . " <" . $_SESSION['Microservices_UserEmail'] . ">") { ?>
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan GM HCM <b>*</b></label>
                                                        <div class="col-sm-9">
                                                            <?php if ($_GET['act'] == 'edit' && $ddata['catatan_gm_hcm'] == null) { ?>
                                                                <textarea type="text" class="form-control form-control-sm" id="catatan_gm_hcm" name="catatan_gm_hcm" rows="2"></textarea>
                                                            <?php }  ?>
                                                        </div>
                                                    </div>
                                                <?php } elseif ($_GET['act'] == 'edit' && $ddata['catatan_gm_hcm'] !== null) { ?>
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan GM HCM <b>*</b></label>
                                                        <div class="col-sm-9">
                                                            <?php if ($_GET['act'] == 'edit' && $ddata['catatan_gm_hcm'] !== null) { ?>
                                                                <textarea type="text" class="form-control form-control-sm" id="catatan_gm_hcm" name="catatan_gm_hcm" rows="2" readonly> <?php echo $ddata['catatan_gm_hcm']; ?> </textarea>
                                                            <?php }  ?>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <!-- <div class="row mb-3" id="catatan-gm-hcm-row">
                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan GM HCM <b>*</b></label>
                                                    <div class="col-sm-9">
                                                        <?php //if ($_GET['act'] == 'edit' && $ddata['catatan_gm_hcm'] == null) { 
                                                        ?>
                                                            <textarea type="text" class="form-control form-control-sm" id="catatan_gm_hcm" name="catatan_gm_hcm" rows="2"></textarea>
                                                        <?php //} elseif ($_GET['act'] == 'edit' && $ddata['catatan_gm_hcm'] !== null) { 
                                                        ?>
                                                            <textarea type="text" class="form-control form-control-sm" id="catatan_gm_hcm" name="catatan_gm_hcm" rows="2" readonly> <?php //echo $ddata['catatan_gm_hcm']; 
                                                                                                                                                                                    ?> </textarea>
                                                        <?php //} 
                                                        ?>
                                                    </div>
                                                </div> -->
                                            </div>
                                            <?php if ($ddata['status_rekrutmen'] == 'Penambahan') { ?>
                                                <div class="col-lg-6" id="approval_gm_bod_section">
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Approval GM bod <b>*</b></label>
                                                        <div class="col-sm-9">
                                                            <select class="form-control" name="gm_bod" id="gm_bod" readonly>
                                                                <option value="<?php echo $ddata['gm_bod']; ?>"><?php echo $ddata['gm_bod']; ?></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3" id="status-gm-bod-row">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status GM bod <b>*</b></label>
                                                        <div class="col-sm-9">
                                                            <?php if ($_GET['act'] == "edit" && $ddata['status_gm_hcm'] == "Pending" || $_GET['act'] == "edit" && $ddata['status_gm_hcm'] == "Disapprove" || $_GET['act'] == "edit" && $ddata['status_gm'] == "Disapprove" || $_GET['act'] == "edit" && $ddata['status_gm_bod'] == null) { ?>
                                                                <select class="form-control" name="status_gm_bod" id="status_gm_bod" readonly>
                                                                    <option><?php echo ucwords($ddata['status_gm_bod']); ?></option>
                                                                </select>
                                                            <?php } elseif ($_GET['act'] == "edit" && $ddata['status_gm_hcm'] == "Approve" && $_GET['act'] == "edit" && $ddata['status_gm_bod'] == "Pending") { ?>
                                                                <?php if ($ddata['gm_bod'] == $_SESSION['Microservices_UserName'] . " <" . $_SESSION['Microservices_UserEmail'] . ">") { ?>
                                                                    <select class="form-control" name="status_gm_bod" id="status_gm_bod" readonly>
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
                                                    </div>
                                                    <?php if ($_GET['act'] == "edit" && $ddata['status_gm'] == "Approve" && $ddata['status_gm_hcm'] == "Approve" && $ddata['gm_bod'] == $_SESSION['Microservices_UserName'] . " <" . $_SESSION['Microservices_UserEmail'] . ">") { ?>
                                                        <div class="row mb-3">
                                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan GM BOD <b>*</b></label>
                                                            <div class="col-sm-9">
                                                                <?php if ($_GET['act'] == 'edit' && $ddata['catatan_gm_bod'] == null) { ?>
                                                                    <textarea type="text" class="form-control form-control-sm" id="catatan_gm_bod" name="catatan_gm_bod" rows="2"></textarea>
                                                                <?php }  ?>
                                                            </div>
                                                        </div>
                                                    <?php } elseif ($_GET['act'] == 'edit' && $ddata['catatan_gm_bod'] !== null) { ?>
                                                        <div class="row mb-3">
                                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan GM HCM <b>*</b></label>
                                                            <div class="col-sm-9">
                                                                <?php if ($_GET['act'] == 'edit' && $ddata['catatan_gm_bod'] !== null) { ?>
                                                                    <textarea type="text" class="form-control form-control-sm" id="catatan_gm_bod" name="catatan_gm_bod" rows="2" readonly> <?php echo $ddata['catatan_gm_bod']; ?> </textarea>
                                                                <?php }  ?>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                    <!-- <div class="row mb-3" id="catatan-gm-bod-row">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan GM bod <b>*</b></label>
                                                        <div class="col-sm-9">
                                                            <?php //if ($ddata['catatan_gm_bod'] == null) { 
                                                            ?>
                                                                <textarea type="text" class="form-control form-control-sm" id="catatan_gm_bod" name="catatan_gm_bod" rows="2"></textarea>
                                                            <?php //} else { 
                                                            ?>
                                                                <textarea readonly type="text" class="form-control form-control-sm" id="catatan_gm_bod" name="catatan_gm_bod" rows="2"><?php //echo $ddata['catatan_gm_bod']; 
                                                                                                                                                                                        ?></textarea>
                                                            <?php //} 
                                                            ?>
                                                        </div>
                                                    </div> -->
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <?php
                            $maxRows = 100;

                            if (isset($_GET['maxRows'])) {
                                $maxRows = $_GET['maxRows'];
                            }

                            $tbl_resource_logs = "hcm_requirement_log";
                            $condition = "id_fpkb = '" . $ddata['id_fpkb'] . "' AND project_code = '" . $_GET['project_code'] . "' ORDER BY entry_date ASC";

                            $dataLogResource = $DBHCM->get_data($tbl_resource_logs, $condition, "", 0, $maxRows);
                            if ($dataLogResource[2] > 0) {
                            ?>

                                <h5>History</h5>
                                <table class="table">
                                    <thead class="bg-light">
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Description</th>
                                    </thead>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $tgl = "";
                                        ?>
                                        <?php do { ?>
                                            <tr>
                                                <td style="font-size: 12px">
                                                    <?php if ($tgl = date("Y-m-d", strtotime($dataLogResource[0]['entry_date']))) { ?>
                                                        <table class="table table-sm table-light table-striped">
                                                            <tr>
                                                                <td class="text-center fw-bold" colspan="2">
                                                                    <?php echo date("Y", strtotime($dataLogResource[0]['entry_date'])); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center">
                                                                    <?php echo date("M", strtotime($dataLogResource[0]['entry_date'])); ?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <?php echo date("d", strtotime($dataLogResource[0]['entry_date'])); ?>
                                                                </td>
                                                            </tr>
                                                        </table>

                                                    <?php
                                                    } ?>
                                                </td>
                                                <td style="font-size: 12px">
                                                    <?php echo date("H:i:s", strtotime($dataLogResource[0]['entry_date'])); ?></td>
                                                <td style="font-size: 12px">
                                                    <?php
                                                    $description = str_replace(" -", "", $dataLogResource[0]['description']);
                                                    echo $description . "<br/>Performed by " . $dataLogResource[0]['entry_by']; ?></td>
                                            </tr>
                                            <?php $tgl = date("Y-m-d", strtotime($dataLogResource[0]['entry_date'])); ?>
                                        <?php } while ($dataLogResource[0] = $dataLogResource[1]->fetch_assoc()); ?>
                                    </tbody>
                                </table>
                            <?php } ?>
                            <!-- <div class="" style="font-size: 12px">Readmore...</div> -->
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
                                                    <select class="form-control" name="Divisi" id="Divisi" readonly>
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
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Tanggal Dibutuhkan</label>
                                                <div class="col-sm-9">
                                                    <input readonly type="date" class="form-control form-control-sm" id="tanggal_dibutuhkan" name="tanggal_dibutuhkan" value="<?php echo $ddata['tanggal_dibutuhkan']; ?>">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Deskripsi Pekerjaan</label>
                                                <div class="col-sm-9">
                                                    <textarea type="text" class="form-control" id="deskripsi" name="deskripsi" rows="2" readonly><?php echo $ddata['deskripsi']; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Jumlah Dibutuhkan</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control form-control-sm" id="jumlah_dibutuhkan" name="jumlah_dibutuhkan" value="<?php echo $ddata['jumlah_dibutuhkan']; ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Jumlah Sudah Complete</label>
                                                <div class="col-sm-9">
                                                    <?php
                                                    $completequery = "SELECT COUNT(*) AS jumlah_complete FROM sa_hcm_requirement r WHERE r.id_fpkb = '" . $ddata['id_fpkb'] . "' AND EXISTS (SELECT 1 FROM sa_hcm_requirement_interview i WHERE i.id_fpkb = r.id_fpkb AND i.status = 'Complete Offering')";
                                                    $totalcomplete = $DBHCM->get_sqlV2("SELECT COUNT(*) AS jumlah_complete FROM sa_hcm_requirement_interview r WHERE r.id_fpkb = '" . $ddata['id_fpkb'] . "' AND EXISTS (SELECT 1 FROM sa_hcm_requirement i WHERE i.id_fpkb = r.id_fpkb AND r.status = 'Complete Offering');");
                                                    $total = $totalcomplete[0];
                                                    ?>
                                                    <input type="text" class="form-control form-control-sm" id="jumlah_complete" name="jumlah_complete" value="<?php echo $total['jumlah_complete']; ?>" readonly>
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
                                                <select class="form-control" name="Status_karyawan" id="Status_karyawan" readonly>
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
                                                <select class="form-control" name="Pendidikan_minimal" id="Pendidikan_minimal" readonly>
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
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Internal <b>*</b></label>
                                            <div class="col-sm-9">
                                                <select class="form-control" name="Internal" id="Internal" readonly>
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
                                        <!-- <div class="row mb-3">
                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan Lain </label>
                                                    <div class="col-sm-9">
                                                        <input readonly type="text" class="form-control form-control-sm" id="diisi_oleh_hcm" name="diisi_oleh_hcm" value="<?php //echo $ddata['diisi_oleh_hcm']; 
                                                                                                                                                                            ?>">
                                                    </div>
                                                </div> -->
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Range Salary <b>*</b></label>
                                            <div class="col-sm-9">
                                                <input readonly type="text" class="form-control text-end" style="text-align: right;" id="range_salary" name="range_salary" value="<?php echo $ddata['range_salary']; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-header fw-bold"></div>
                        <div class="card-body">
                            <div class="row">
                                <?php if ($_GET['act'] == 'view') { ?>
                                    <?php $get_interview = $DBHCM->get_sqlV2("SELECT * FROM sa_hcm_requirement_interview WHERE project_code = '" . $ddata['project_code'] . "' AND status = 'Complete Offering' AND id_fpkb = '" . $ddata['id_fpkb'] . "'"); ?>
                                    <?php $email = isset($get_interview[0]['email']) ? $get_interview[0]['email'] : null; ?>
                                    <?php if ($email != null) { ?>
                                        <table id="example" class="table table-sm table-hover">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Nama Kandidat</th>
                                                    <th scope="col">Email</th>
                                                    <?php if ($ddata['status_karyawan'] == "Percobaan") { ?>
                                                        <th scope="col">Salary Probation</th>
                                                        <th scope="col">Salary Permanent</th>
                                                    <?php } ?>
                                                    <?php if ($ddata['status_karyawan'] != "Percobaan") { ?>
                                                        <th scope="col">Offering Salary</th>
                                                    <?php } ?>
                                                    <th scope="col">Tanggal Join</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $counter = 1;
                                                while ($row = $get_interview[1]->fetch_assoc()) { ?>
                                                    <tr>
                                                        <th scope="row"><?php echo $counter++; ?></th>
                                                        <td>
                                                            <input type="hidden" class="form-control form-control-sm" name="email_id[]" value="<?php echo $row['email_id']; ?>">
                                                            <input type="text" class="form-control form-control-sm" name="nama_kandidat_update[]" value="<?php echo $row['nama_kandidat']; ?>" readonly>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control form-control-sm" name="email_update[]" value="<?php echo $row['email']; ?>" readonly>
                                                        </td>
                                                        <?php if ($ddata['status_karyawan'] != "Percobaan") { ?>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm" name="offering_salary[]" value="<?php echo $row['offering_salary']; ?>">
                                                            </td>
                                                        <?php } ?>
                                                        <?php if ($ddata['status_karyawan'] == "Percobaan") { ?>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm" name="salary_Probation[]" value="<?php echo $row['salary_Probation']; ?>">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control form-control-sm" name="salary_Permanent[]" value="<?php echo $row['salary_Permanent']; ?>">
                                                            </td>
                                                        <?php } ?>
                                                        <td>
                                                            <input type="date" class="form-control form-control-sm" name="join_date[]" value="<?php echo $row['join_date']; ?>">
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                            <script>
                                $(document).ready(function() {
                                    $('#example').DataTable();
                                });
                            </script>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <?php
                                $maxRows = 100;

                                if (isset($_GET['maxRows'])) {
                                    $maxRows = $_GET['maxRows'];
                                }

                                $tbl_resource_logs = "hcm_requirement_log";
                                $condition = "id_fpkb = '" . $ddata['id_fpkb'] . "' AND project_code = '" . $ddata['project_code'] . "' ORDER BY entry_date ASC";

                                $dataLogResource = $DBHCM->get_data($tbl_resource_logs, $condition, "", 0, $maxRows);
                                if ($dataLogResource[2] > 0) {
                                ?>

                                    <h5>History</h5>
                                    <table class="table">
                                        <thead class="bg-light">
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Description</th>
                                        </thead>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $tgl = "";
                                            ?>
                                            <?php do { ?>
                                                <tr>
                                                    <td style="font-size: 12px">
                                                        <?php if ($tgl = date("Y-m-d", strtotime($dataLogResource[0]['entry_date']))) { ?>
                                                            <table class="table table-sm table-light table-striped">
                                                                <tr>
                                                                    <td class="text-center fw-bold" colspan="2">
                                                                        <?php echo date("Y", strtotime($dataLogResource[0]['entry_date'])); ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-center">
                                                                        <?php echo date("M", strtotime($dataLogResource[0]['entry_date'])); ?>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?php echo date("d", strtotime($dataLogResource[0]['entry_date'])); ?>
                                                                    </td>
                                                                </tr>
                                                            </table>

                                                        <?php
                                                        } ?>
                                                    </td>
                                                    <td style="font-size: 12px">
                                                        <?php echo date("H:i:s", strtotime($dataLogResource[0]['entry_date'])); ?></td>
                                                    <td style="font-size: 12px">
                                                        <?php
                                                        $description = str_replace(" -", "", $dataLogResource[0]['description']);
                                                        echo $description . "<br/>Performed by " . $dataLogResource[0]['entry_by']; ?></td>
                                                </tr>
                                                <?php $tgl = date("Y-m-d", strtotime($dataLogResource[0]['entry_date'])); ?>
                                            <?php } while ($dataLogResource[0] = $dataLogResource[1]->fetch_assoc()); ?>
                                        </tbody>
                                    </table>
                                <?php } ?>
                                <!-- <div class="" style="font-size: 12px">Readmore...</div> -->
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
                                    <div class="row">
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
                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">ID FPKB</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control form-control-sm" id="id_fpkb" name="id_fpkb" value="<?php if ($_GET['act'] == 'editapproval') {
                                                                                                                                                        echo $ddata['id_fpkb'];
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
                                                <div class="row mb-3" hidden>
                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Code</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control form-control-sm" id="nama_project" name="nama_project" value="<?php if ($_GET['act'] == 'editapproval') {
                                                                                                                                                                    echo $ddata['nama_project'];
                                                                                                                                                                } ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="row mb-3" hidden>
                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project name</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control form-control-sm" id="project_name" name="project_name" value="<?php if ($_GET['act'] == 'editapproval') {
                                                                                                                                                                    echo $ddata['nama_project'];
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
                                                                <?php $gmbod = $DBHCM->get_sqlV2("SELECT * FROM sa_view_employees WHERE resign_date is null and job_structure LIKE '%JG ATN WM HCM Recruitment%' and job_level = 4"); ?>
                                                                <option></option>
                                                                <option value="Malik Aulia Wiratama <malik.aulia@mastersystem.co.id>">Malik Aulia Wiratama</option>
                                                                <?php while ($row = $gmbod[1]->fetch_assoc()) { ?>
                                                                    <option value="<?php echo $row['employee_name'] . " <" . $row['employee_email'] . ">"; ?>">
                                                                        <?php echo $row['employee_name'] . " (" . $row['organization_name'] . ")"; ?>
                                                                    </option>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <option><?php echo ucwords($ddata['assign_requirement']); ?></option>
                                                                <option value="Malik Aulia Wiratama <malik.aulia@mastersystem.co.id>">Malik Aulia Wiratama</option>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <?php
                                    $maxRows = 100;

                                    if (isset($_GET['maxRows'])) {
                                        $maxRows = $_GET['maxRows'];
                                    }

                                    $tbl_resource_logs = "hcm_requirement_log";
                                    // $condition = "project_code = '" . $_GET['project_code'] . "' ORDER BY entry_date ASC";
                                    $condition = "id_fpkb = '" . $ddata['id_fpkb'] . "' AND project_code = '" . $_GET['project_code'] . "' ORDER BY entry_date ASC";
                                    $dataLogResource = $DBHCM->get_data($tbl_resource_logs, $condition, "", 0, $maxRows);
                                    if ($dataLogResource[2] > 0) {
                                    ?>

                                        <h5>History</h5>
                                        <table class="table">
                                            <thead class="bg-light">
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Description</th>
                                            </thead>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $tgl = "";
                                                ?>
                                                <?php do { ?>
                                                    <tr>
                                                        <td style="font-size: 12px">
                                                            <?php if ($tgl = date("Y-m-d", strtotime($dataLogResource[0]['entry_date']))) { ?>
                                                                <table class="table table-sm table-light table-striped">
                                                                    <tr>
                                                                        <td class="text-center fw-bold" colspan="2">
                                                                            <?php echo date("Y", strtotime($dataLogResource[0]['entry_date'])); ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text-center">
                                                                            <?php echo date("M", strtotime($dataLogResource[0]['entry_date'])); ?>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <?php echo date("d", strtotime($dataLogResource[0]['entry_date'])); ?>
                                                                        </td>
                                                                    </tr>
                                                                </table>

                                                            <?php
                                                            } ?>
                                                        </td>
                                                        <td style="font-size: 12px">
                                                            <?php echo date("H:i:s", strtotime($dataLogResource[0]['entry_date'])); ?></td>
                                                        <td style="font-size: 12px">
                                                            <?php
                                                            $description = str_replace(" -", "", $dataLogResource[0]['description']);
                                                            echo $description . "<br/>Performed by " . $dataLogResource[0]['entry_by']; ?></td>
                                                    </tr>
                                                    <?php $tgl = date("Y-m-d", strtotime($dataLogResource[0]['entry_date'])); ?>
                                                <?php } while ($dataLogResource[0] = $dataLogResource[1]->fetch_assoc()); ?>
                                            </tbody>
                                        </table>
                                    <?php } ?>
                                    <!-- <div class="" style="font-size: 12px">Readmore...</div> -->
                                </div>
                            </div>
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
                $user = $_SESSION['Microservices_UserEmail'];
                $HCMfull = $DBHCM->get_sqlV2("SELECT employee_email FROM sa_view_employees WHERE employee_email = '" . $_SESSION['Microservices_UserEmail'] .
                    "'  AND job_structure LIKE '%JW ATN WM HCM Recruitment%' AND resign_date is null");
                // $malik = $HCMfull[0]['employee_email'];
                // var_dump($malik);
                // die;
                ?>
                    <form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Id FPKB</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control form-control-sm" id="id_fpkb" name="id_fpkb" value="<?php if ($_GET['act'] == 'editshare') {
                                                                                                                                        echo $ddata['id_fpkb'];
                                                                                                                                    } ?>" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3" hidden>
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">project name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control form-control-sm" id="nama_project" name="nama_project" value="<?php if ($_GET['act'] == 'editshare') {
                                                                                                                                                    echo $ddata['nama_project'];
                                                                                                                                                } ?>" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3" hidden>
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Id</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control form-control-sm" id="id" name="id" value="<?php if ($_GET['act'] == 'editshare') {
                                                                                                                                echo $ddata['id'];
                                                                                                                            } ?>" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3" hidden>
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Id</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control form-control-sm" id="request_by" name="request_by" value="<?php if ($_GET['act'] == 'editshare') {
                                                                                                                                                echo $ddata['request_by'];
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
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Kode Project</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control form-control-sm" id="project_code" name="project_code" value="<?php if ($_GET['act'] == 'editshare') {
                                                                                                                                                    echo $ddata['project_code'];
                                                                                                                                                } ?>" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Share <b>*</b></label>
                                    <div class="col-sm-9">
                                        <?php if ($_GET['act'] == "editshare") { ?>
                                            <select class="form-control" name="share" id="share" readonly>
                                                <option value="CV Kandidat">CV Kandidat</option>
                                            </select>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Assign Recruiter <b>*</b></label>
                                    <div class="col-sm-9">
                                        <?php if ($_GET['act'] == "editshare") { ?>
                                            <select class="form-control" name="assign_requirement" id="assign_requirement" readonly>
                                                <option value="<?php echo $ddata['assign_requirement']; ?>"><?php echo $ddata['assign_requirement']; ?></option>
                                            </select>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Deskripsi Pekerjaan</label>
                                    <div class="col-sm-9">
                                        <textarea type="text" class="form-control" id="deskripsi" name="deskripsi" rows="2" readonly><?php if ($_GET['act'] == 'editshare') {
                                                                                                                                            echo $ddata['deskripsi'];
                                                                                                                                        } ?></textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Kompetensi Teknis yang Harus Dikuasai</label>
                                    <div class="col-sm-9">
                                        <textarea type="text" class="form-control" id="kompetensi_teknis" name="kompetensi_teknis" rows="2" readonly><?php if ($_GET['act'] == 'editshare') {
                                                                                                                                                            echo $ddata['kompetensi_teknis'];
                                                                                                                                                        } ?></textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Kompetensi Teknis yang Harus Dikuasai</label>
                                    <div class="col-sm-9">
                                        <textarea type="text" class="form-control" id="kompetensi_non_teknis" name="kompetensi_non_teknis" rows="2" readonly><?php if ($_GET['act'] == 'editshare') {
                                                                                                                                                                    echo $ddata['kompetensi_non_teknis'];
                                                                                                                                                                } ?></textarea>
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
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Jumlah Sudah Complete</label>
                                    <div class="col-sm-9">
                                        <?php
                                        $completequery = "SELECT COUNT(*) AS jumlah_complete FROM sa_hcm_requirement r WHERE r.id_fpkb = '" . $ddata['id_fpkb'] . "' AND EXISTS (SELECT 1 FROM sa_hcm_requirement_interview i WHERE i.id_fpkb = r.id_fpkb AND i.status = 'Complete Offering')";
                                        $totalcomplete = $DBHCM->get_sqlV2("SELECT COUNT(*) AS jumlah_complete FROM sa_hcm_requirement_interview r WHERE r.id_fpkb = '" . $ddata['id_fpkb'] . "' AND EXISTS (SELECT 1 FROM sa_hcm_requirement i WHERE i.id_fpkb = r.id_fpkb AND r.status = 'Complete Offering');");
                                        $total = $totalcomplete[0];
                                        ?>
                                        <input type="text" class="form-control form-control-sm" id="jumlah_complete" name="jumlah_complete" value="<?php if ($_GET['act'] == 'editshare') {
                                                                                                                                                        echo $total['jumlah_complete'];
                                                                                                                                                    } ?>" readonly>
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

                        <div class="row">
                            <?php //if ($HCMfull[2] > 0) { 
                            ?>
                            <div class="col-lg-12">
                                <div class="row mb-3">
                                    <?php if ($_GET['act'] == 'editshare') { ?>
                                        <!-- <button type="button" class="btn btn-primary" name="btn_upload" id="btn_upload" data-bs-toggle="modal" data-bs-target="#fileupload">Upload File</button> -->
                                        <button type="button" class="btn btn-primary" name="btn_upload" id="btn_upload" data-bs-toggle="modal" data-bs-target="#fileuploaddrive">Upload File</button>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php //} else {
                            //} 
                            ?>
                            <div class="col-lg-12">
                                <div id="fileList"></div>
                            </div>
                            <div class="col-lg-12">
                                <?php
                                // $d = dir($sFolderTarget);
                                // echo "Handle: " . $d->handle . "<br/>";
                                // echo "Path: " . $d->path . "<br/>";
                                // echo '<div class="list-group">';
                                ?>
                                <!-- <table class="table table-sm table-hover">
                                    <thead>
                                        <tr> -->
                                <!-- <th scope="col">#</th>
                                            <th scope="col">Nama File</th>
                                            <th scope="col">Size</th> -->
                                <!-- <th scope="col">Created</th> -->
                                <!-- <th scope="col">Modified</th>
                                            <th scope="col">Notes</th> -->
                                <!-- </tr>
                                    </thead>
                                    <tbody> -->
                                <?php
                                // $i = 0;
                                // while (false !== ($entry = $d->read())) {
                                //     if ($entry != '.' && $entry != '..' && $entry != 'index.php') {
                                //         $fstat = stat($sFolderTarget . $entry);
                                //         $filenote = $DBHCM->get_sql("SELECT * from sa_hcm_notecv where file = '" . $entry . "'");
                                // $filenotemalik = $filenote[0]['notes'];
                                // var_dump($filenotemalik);
                                // die;
                                ?>
                                <?php //if($entry = $_GET['cr_no']) {
                                ?>
                                <tr>
                                    <!-- <th scope="row"><?php //echo $i + 1; 
                                                            ?></th> -->
                                    <!-- <input type="hidden" name="malik[]" value="<?php //echo $entry; 
                                                                                    ?>"> -->
                                    <!-- <td><a href="<?php //echo $sFolderTarget . $entry; 
                                                        ?>" target="_blank" class="text-body"><?php //echo $entry; 
                                                                                                ?></a></td> -->
                                    <td>
                                        <?php
                                        // if ($fstat['size'] < 1024) {
                                        //     echo number_format($fstat['size'], 2) . ' B';
                                        // } elseif ($fstat['size'] < (1024 * 1024)) {
                                        //     echo number_format($fstat['size'] / 1024, 2) . ' KB';
                                        // } elseif ($fstat['size'] < (1024 * 1024 * 1024)) {
                                        //     echo number_format($fstat['size'] / (1024 * 1024), 2) . ' MB';
                                        // } elseif ($fstat['size'] < (1024 * 1024 * 1024 * 1024)) {
                                        //     echo number_format($fstat['size'] / (1024 * 1024 * 1024), 2) . ' GB';
                                        // }
                                        ?>
                                    </td>
                                    <!-- <td><?php //echo date('d-M-Y G:i:s', $fstat['ctime']); 
                                                ?></td> -->
                                    <!-- <td><?php //echo date('d-M-Y G:i:s', $fstat['mtime']);  -->
                                                ?></td><td> -->
                                    <?php //if ($_GET['act'] == "editshare") { 
                                    ?>
                                    <?php //if ($filenote !== null && isset($filenote[0]['file'])) { 
                                    ?>
                                    <!-- <textarea readonly class="form-control form-control-sm" name="notes[]" id="notes[]"><?php //echo $filenote[0]['notes']; 
                                                                                                                                ?></textarea> -->
                                    <?php //} else { 
                                    ?>
                                    <!-- <textarea class="form-control form-control-sm" name="notes[]" id="notes[]"></textarea> -->
                                    <?php //} 
                                    ?>
                                    <?php //} 
                                    ?>
                                    <!-- </td>
                                        </tr> -->
                                    <?php
                                    // echo '<a href="'.$sFolderTarget.$entry.'" target="_blank" class="list-group-item list-group-item-action">'.$entry.'</a>';
                                    // $i++;
                                    // }
                                    // }
                                    if ($i == 0) {
                                    ?>
                                <tr>
                                    <td colspan="5">No Files available.</td>
                                </tr>
                            <?php
                                        // echo 'No files available.';
                                    }
                            ?>
                            <!-- </tbody>
                            </table> -->
                            <?php
                            // echo '</div>';
                            // $d->close();
                            ?>
                            </div>
                            <div class="col-lg-12">
                                <?php
                                include_once('C:\xampp\htdocs\microservices\google-drive-requirement.php');
                                // include $_SERVER['DOCUMENT_ROOT'] . '/google-drive-requirement.php';
                                $driveClient = getDriveClient();
                                $driveService = new Google_Service_Drive($driveClient);
                                $project_code = $_GET['project_code'];

                                // Fetch folderId
                                $getidfolder = $DBHCM->get_sqlV2("SELECT * FROM sa_hcm_notecv WHERE id_fpkb = '" . $ddata['id_fpkb'] . "'");
                                $folderId = null;
                                if ($getidfolder && is_array($getidfolder) && isset($getidfolder[0]['id_folderdrive'])) {
                                    $folderId = $getidfolder[0]['id_folderdrive'];
                                }

                                // function deleteFileFromDrive($service, $fileId)
                                // {
                                //     try {
                                //         $service->files->delete($fileId, array('supportsAllDrives' => true));
                                //         echo "File deleted successfully.";
                                //     } catch (Exception $e) {
                                //         echo "An error occurred: " . $e->getMessage();
                                //     }
                                // }

                                // if (isset($_POST['delete_file_id'])) {
                                //     deleteFileFromDrive($driveService, $_POST['delete_file_id']);
                                // }

                                $files = [];
                                if ($folderId) {
                                    $files = listFilesInFolder($driveService, $folderId);
                                }
                                ?>

                                <?php if ($folderId) : ?>
                                    <?php if (!empty($files)) : ?>
                                        <table class="table table-sm table-hover">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Nama File</th>
                                                    <th scope="col">Tanggal Interview</th>
                                                    <th scope="col" style="text-align: center;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $index = 1; // Inisialisasi variabel penghitung
                                                foreach ($files as $file) :
                                                    // Menggunakan ID file Google Drive untuk mencocokkan data yang benar dari database
                                                    $filenote = $DBHCM->get_sqlV2("SELECT * FROM sa_hcm_notecv WHERE id_fpkb = '" . $ddata['id_fpkb'] . "' AND project_code = '" . $_GET['project_code'] . "' AND id_filedrive = '" . $file['id'] . "'");
                                                    $fileId = isset($filenote[0]['id_filedrive']) ? $filenote[0]['id_filedrive'] : null;
                                                    if ($fileId) : // Cek jika fileId ada
                                                ?>
                                                        <tr>
                                                            <th scope="row"><?= $index++ ?></th> <!-- Gunakan variabel penghitung dan increment -->
                                                            <input type="hidden" name="id_note[]" id="id_note[]" value="<?= isset($filenote[0]['id_note']) ? htmlspecialchars($filenote[0]['id_note']) : '' ?>">
                                                            <input type="hidden" name="malik[]" value="<?= isset($filenote[0]['file']) ? htmlspecialchars($filenote[0]['file']) : '' ?>">
                                                            <td>
                                                                <a href="https://drive.google.com/file/d/<?= $fileId ?>/view" target="_blank"><?= isset($filenote[0]['file']) ? htmlspecialchars($filenote[0]['file']) : 'No File' ?></a>
                                                            </td>

                                                            <input type="hidden" name="id_file[]" id="id_file[]" value="<?= isset($filenote[0]['id_filedrive']) ? htmlspecialchars($filenote[0]['id_filedrive']) : '' ?>">

                                                            <td>
                                                                <?php if ($_GET['act'] == "editshare") : ?>
                                                                    <?php if (isset($filenote[0]['tanggal_interview'])) : ?>
                                                                        <input type="datetime" class="form-control form-control-sm" name="tanggal_interview_cv[]" value="<?= htmlspecialchars($filenote[0]['tanggal_interview']) ?>">
                                                                    <?php else : ?>
                                                                        <input type="datetime-local" class="form-control form-control-sm" name="tanggal_interview_cv[]" value="">
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td class="text-center">
                                                                <select name="status_cv[]" id="status_cv[]">
                                                                    <?php if (isset($filenote[0]['status_cv'])) : ?>
                                                                        <option value="<?= htmlspecialchars($filenote[0]['status_cv']) ?>"> <?= htmlspecialchars($filenote[0]['status_cv']) ?></option>
                                                                    <?php else : ?>
                                                                        <option></option>
                                                                        <option value="Yes">Yes</option>
                                                                        <option value="No">No</option>
                                                                    <?php endif; ?>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php else : ?>
                                        <p>No files found.</p>
                                    <?php endif; ?>
                                <?php else : ?>
                                    <p>Folder ID is not set.</p>
                                <?php endif; ?>


                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                            </div>
                            <div class="col-lg-6">
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm"></label>
                                    <div class="col-sm-9">
                                        <button type="delete" name="send_email_cv" class="btn btn-danger btn-sm">Send Email CV</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } elseif ($_GET['act'] == 'editinterview') {
                    global $DBHCM;
                    $condition = "id=" . $_GET['id'];
                    $data = $DBHCM->get_data($tblname, $condition);
                    $ddata = $data[0];
                    $qdata = $data[1];
                    $tdata = $data[2];
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
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">ID FPKB</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control form-control-sm" id="id_fpkb" name="id_fpkb" value="<?php if ($_GET['act'] == 'editinterview') {
                                                                                                                                                            echo $ddata['id_fpkb'];
                                                                                                                                                        } ?>" readonly>
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
                                                    <div class="row mb-3" hidden>
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">request_by</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control form-control-sm" id="request_by" name="request_by" value="<?php if ($_GET['act'] == 'editinterview') {
                                                                                                                                                                    echo $ddata['request_by'];
                                                                                                                                                                } ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3" hidden>
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Assign Recruiter</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control form-control-sm" id="assign_requirement" name="assign_requirement" value="<?php if ($_GET['act'] == 'editinterview') {
                                                                                                                                                                                    echo $ddata['assign_requirement'];
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
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Code</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control form-control-sm" id="project_code" name="project_code" value="<?php if ($_GET['act'] == 'editinterview') {
                                                                                                                                                                        echo $ddata['project_code'];
                                                                                                                                                                    } ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Tanggal Dibutuhkan</label>
                                                        <div class="col-sm-9">
                                                            <input type="date" class="form-control form-control-sm" id="tanggal_dibutuhkan" name="tanggal_dibutuhkan" value="<?php if ($_GET['act'] == 'editinterview') {
                                                                                                                                                                                    echo $ddata['tanggal_dibutuhkan'];
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
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Jumlah Sudah Complete</label>
                                                        <div class="col-sm-9">
                                                            <?php
                                                            $completequery = "SELECT COUNT(*) AS jumlah_complete FROM sa_hcm_requirement r WHERE r.id_fpkb = '" . $ddata['id_fpkb'] . "' AND EXISTS (SELECT 1 FROM sa_hcm_requirement_interview i WHERE i.id_fpkb = r.id_fpkb AND i.status = 'Complete Offering')";
                                                            $totalcomplete = $DBHCM->get_sqlV2("SELECT COUNT(*) AS jumlah_complete FROM sa_hcm_requirement_interview r WHERE r.id_fpkb = '" . $ddata['id_fpkb'] . "' AND EXISTS (SELECT 1 FROM sa_hcm_requirement i WHERE i.id_fpkb = r.id_fpkb AND r.status = 'Complete Offering');");
                                                            $total = $totalcomplete[0];
                                                            ?>
                                                            <input type="text" class="form-control form-control-sm" id="jumlah_complete" name="jumlah_complete" value="<?php if ($_GET['act'] == 'editinterview') {
                                                                                                                                                                            echo $total['jumlah_complete'];
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
                                                    <div class="col-lg-12">
                                                        <?php
                                                        // include_once('C:\xampp\htdocs\microservices\google-drive-requirement.php');
                                                        include $_SERVER['DOCUMENT_ROOT'] . 'google-drive-requirement.php';
                                                        $driveClient = getDriveClient();
                                                        $driveService = new Google_Service_Drive($driveClient);
                                                        $project_code = $_GET['project_code'];
                                                        $cobaan = "SELECT * FROM sa_hcm_notecv WHERE id_fpkb = '" . $ddata['id_fpkb'] . "'";
                                                        $getidfolder = $DBHCM->get_sqlV2("SELECT * FROM sa_hcm_notecv WHERE id_fpkb = '" . $ddata['id_fpkb'] . "'");
                                                        // var_dump($getidfolder);
                                                        // die;

                                                        $folderId = null;
                                                        if ($getidfolder && is_array($getidfolder) && isset($getidfolder[0]['id_folderdrive'])) {
                                                            $folderId = $getidfolder[0]['id_folderdrive'];
                                                        }

                                                        function deleteFileFromDrive($service, $fileId)
                                                        {
                                                            try {
                                                                $service->files->delete($fileId, array('supportsAllDrives' => true));
                                                                echo "File deleted successfully.";
                                                            } catch (Exception $e) {
                                                                echo "An error occurred: " . $e->getMessage();
                                                            }
                                                        }

                                                        if (isset($_POST['delete_file_id'])) {
                                                            deleteFileFromDrive($driveService, $_POST['delete_file_id']);
                                                        }

                                                        if ($folderId) {
                                                            $files = listFilesInFolder($driveService, $folderId);
                                                        }
                                                        ?>

                                                        <?php if ($folderId) : ?>
                                                            <?php if (!empty($files)) : ?>
                                                                <table class="table table-sm table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <th scope="col">#</th>
                                                                            <th scope="col">Nama File</th>
                                                                            <th scope="col">Tanggal Interview</th>
                                                                            <th scope="col" style="text-align: center;">Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <tbody>
                                                                        <?php
                                                                        $index = 1; // Inisialisasi variabel penghitung
                                                                        foreach ($files as $file) :
                                                                            // Menggunakan ID file Google Drive untuk mencocokkan data yang benar dari database
                                                                            $filenote = $DBHCM->get_sqlV2("SELECT * FROM sa_hcm_notecv WHERE id_fpkb = '" . $ddata['id_fpkb'] . "' AND project_code = '" . $_GET['project_code'] . "' AND id_filedrive = '" . $file['id'] . "'");
                                                                            $fileId = isset($filenote[0]['id_filedrive']) ? $filenote[0]['id_filedrive'] : null;
                                                                            if ($fileId) : // Cek jika fileId ada
                                                                        ?>
                                                                                <tr>
                                                                                    <th scope="row"><?= $index++ ?></th> <!-- Gunakan variabel penghitung dan increment -->
                                                                                    <input type="hidden" name="id_note[]" id="id_note[]" value="<?= isset($filenote[0]['id_note']) ? htmlspecialchars($filenote[0]['id_note']) : '' ?>">
                                                                                    <input type="hidden" name="malik[]" value="<?= isset($filenote[0]['file']) ? htmlspecialchars($filenote[0]['file']) : '' ?>">
                                                                                    <td>
                                                                                        <a href="https://drive.google.com/file/d/<?= $fileId ?>/view" target="_blank"><?= isset($filenote[0]['file']) ? htmlspecialchars($filenote[0]['file']) : 'No File' ?></a>
                                                                                    </td>

                                                                                    <input type="hidden" name="id_file[]" id="id_file[]" value="<?= isset($filenote[0]['id_filedrive']) ? htmlspecialchars($filenote[0]['id_filedrive']) : '' ?>">

                                                                                    <td>
                                                                                        <?php if ($_GET['act'] == "editinterview") : ?>
                                                                                            <?php if (isset($filenote[0]['tanggal_interview'])) : ?>
                                                                                                <input type="datetime" class="form-control form-control-sm" name="tanggal_interview_cv[]" value="<?= htmlspecialchars($filenote[0]['tanggal_interview']) ?>">
                                                                                            <?php else : ?>
                                                                                                <input type="datetime-local" class="form-control form-control-sm" name="tanggal_interview_cv[]" value="">
                                                                                            <?php endif; ?>
                                                                                        <?php endif; ?>
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <select name="status_cv[]" id="status_cv[]">
                                                                                            <?php if (isset($filenote[0]['status_cv'])) : ?>
                                                                                                <option value="<?= htmlspecialchars($filenote[0]['status_cv']) ?>"> <?= htmlspecialchars($filenote[0]['status_cv']) ?></option>
                                                                                            <?php else : ?>
                                                                                                <option></option>
                                                                                                <option value="Yes">Yes</option>
                                                                                                <option value="No">No</option>
                                                                                            <?php endif; ?>
                                                                                        </select>
                                                                                    </td>
                                                                                </tr>
                                                                            <?php endif; ?>
                                                                        <?php endforeach; ?>
                                                                    </tbody>
                                                                </table>
                                                            <?php else : ?>
                                                                <p>No files found.</p>
                                                            <?php endif; ?>
                                                        <?php else : ?>
                                                            <p>Folder ID is not set.</p>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php } ?>
                                                <?php if ($_GET['act'] == 'editinterview') { ?>
                                                    <?php $get_interview = $DBHCM->get_sqlV2("SELECT * FROM sa_hcm_requirement_interview WHERE project_code = '" . $_GET['project_code'] . "' AND id_fpkb = '" . $ddata['id_fpkb'] . "'"); ?>
                                                    <?php $email = isset($get_interview[0]['email']) ? $get_interview[0]['email'] : null; ?>
                                                    <?php if ($email != null) { ?>
                                                        <table class="table table-sm table-hover">
                                                            <!-- <thead>
                                                                <tr>
                                                                    <th scope="col">#</th>
                                                                    <th scope="col">Nama Kandidat</th>
                                                                    <th scope="col">Email</th>
                                                                    <th scope="col">Link Webex</th>
                                                                    <th scope="col">Tanggal Interview</th>
                                                                    <th scope="col">PIC </th>
                                                                    <th scope="col">Catatan</th>
                                                                    <th scope="col">Action</th>
                                                                    <th scope="col">Edit</th>
                                                                </tr>
                                                            </thead> -->
                                                            <table class="table table-sm table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th scope="col">#</th>
                                                                        <th scope="col">Nama Kandidat</th>
                                                                        <th scope="col">Email</th>
                                                                        <th scope="col">Link Webex</th>
                                                                        <th scope="col">Tanggal Interview</th>
                                                                        <th scope="col">PIC</th>
                                                                        <th scope="col">Catatan</th>
                                                                        <th scope="col">Action</th>
                                                                        <th scope="col">Edit</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $counter = 1; // Counter to number rows
                                                                    while ($row = $get_interview[1]->fetch_assoc()) { ?>
                                                                        <tr>
                                                                            <th scope="row"><?php echo $counter++; ?></th>
                                                                            <td>
                                                                                <input type="hidden" class="form-control form-control-sm" name="email_id[]" value="<?php echo $row['email_id']; ?>">
                                                                                <input type="text" class="form-control form-control-sm" name="nama_kandidat_update[]" value="<?php echo $row['nama_kandidat']; ?>" readonly>
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" class="form-control form-control-sm" name="email_update[]" value="<?php echo $row['email']; ?>" readonly>
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" class="form-control form-control-sm" name="link_webex[]" value="<?php echo $row['link_webex']; ?>">
                                                                            </td>
                                                                            <td>
                                                                                <input type="datetime-local" class="form-control form-control-sm" name="tanggal_interview_update[]" value="<?php echo $row['tanggal_interview']; ?>">
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" class="form-control form-control-sm" name="pic_update[]" value="<?php echo $row['pic']; ?>">
                                                                            </td>
                                                                            <td>
                                                                                <?php if ($row['catatan'] == null) { ?>
                                                                                    <textarea class="form-control form-control-sm" name="catatan_update[]" style="height: 22px;"></textarea>
                                                                                <?php } else { ?>
                                                                                    <textarea class="form-control form-control-sm" name="catatan_update[]"><?php echo $row['catatan']; ?></textarea>
                                                                                <?php } ?>
                                                                            </td>
                                                                            <td hidden>
                                                                                <input type="text" name="id_calendar[]" id="id_calendar[]" value="<?php echo $row['id_calendar']; ?>">
                                                                            </td>
                                                                            <td>
                                                                                <select name="status[]">
                                                                                    <?php if ($row['status_cv'] == null) { ?>
                                                                                        <option></option>
                                                                                        <option value="Yes">Yes</option>
                                                                                        <option value="No">No</option>
                                                                                    <?php } else { ?>
                                                                                        <option value="<?php echo $row['status_cv'] ?>"><?php echo $row['status_cv'] ?></option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                <input type="submit" class="btn btn-secondary" name="editdatacalendar" value="Update">
                                                                            </td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                </tbody>

                                                            </table>
                                                        </table>
                                                    <?php } ?>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div id="newRow-affected-ci"></div>
                                        <button id="addemail" type="button" class="btn btn-info col-12">Add Interview Calendar</button>
                                    </div>
                                </div>
                            </div>
                        <?php } elseif ($_GET['act'] == 'editoffering') {
                        global $DBHCM;
                        $condition = "email_id=" . $_GET['id'];
                        $data = $DBHCM->get_data("hcm_requirement_interview", $condition);
                        $ddata = $data[0];
                        $qdata = $data[1];
                        $tdata = $data[2];
                        $HCMfull = $DBHCM->get_sqlV2("SELECT employee_email FROM sa_view_employees WHERE employee_email = '" . $_SESSION['Microservices_UserEmail'] .
                            "' AND job_structure LIKE '%JW ATN WM HCM Recruitment%' AND resign_date is null");
                        $get_requirement = $DBHCM->get_sqlV2("SELECT * FROM sa_hcm_requirement WHERE id_fpkb = '" . $_GET['id_fpkb'] . "'");
                        $dget_requirement = $get_requirement[0];
                        $qget_requirement = $get_requirement[1];
                        $tget_requirement = $get_requirement[2];

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
                                                <h2>OFFERING</h2>
                                                <div class="card shadow mb-4">
                                                    <div class="card-body">
                                                        <h2></h2>
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="row mb-3" hidden>
                                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">ID FPKB</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control form-control-sm" id="id_fpkb" name="id_fpkb" value="<?php if ($_GET['act'] == 'editoffering') {
                                                                                                                                                                        echo $ddata['id_fpkb'];
                                                                                                                                                                    } ?>" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3" hidden>
                                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">ID FPKB</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control form-control-sm" id="id_email" name="id_email" value="<?php if ($_GET['act'] == 'editoffering') {
                                                                                                                                                                            echo $ddata['email_id'];
                                                                                                                                                                        } ?>" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3" hidden>
                                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">ID FPKB</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control form-control-sm" id="status_rekrutmen" name="status_rekrutmen" value="<?php if ($_GET['act'] == 'editoffering') {
                                                                                                                                                                                            echo $dget_requirement['status_rekrutmen'];
                                                                                                                                                                                        } ?>" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3" hidden>
                                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">project_name</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control form-control-sm" id="project_name" name="project_name" value="<?php if ($_GET['act'] == 'editoffering') {
                                                                                                                                                                                    echo $dget_requirement['nama_project'];
                                                                                                                                                                                } ?>" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">ID FPKB</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control form-control-sm" id="id_fpkb" name="id_fpkb" value="<?php if ($_GET['act'] == 'editoffering') {
                                                                                                                                                                        echo $dget_requirement['id_fpkb'];
                                                                                                                                                                    } ?>" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3" hidden>
                                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">ID FPKB</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control form-control-sm" id="status_karyawan" name="status_karyawan" value="<?php if ($_GET['act'] == 'editoffering') {
                                                                                                                                                                                        echo $dget_requirement['status_karyawan'];
                                                                                                                                                                                    } ?>" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3" hidden>
                                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">ID Offering</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control form-control-sm" id="id_offering" name="id_offering" value="<?php if ($_GET['act'] == 'editoffering') {
                                                                                                                                                                                echo $dget_requirement['id'];
                                                                                                                                                                            } ?>" readonly>
                                                                    </div>
                                                                </div>

                                                                <div class="row mb-3" hidden>
                                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Assign Recruitment</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control form-control-sm" id="assign_requirement" name="assign_requirement" value="<?php if ($_GET['act'] == 'editoffering') {
                                                                                                                                                                                                echo $dget_requirement['assign_requirement'];
                                                                                                                                                                                            } ?>" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Divisi</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control form-control-sm" id="divisi" name="divisi" value="<?php if ($_GET['act'] == 'editoffering') {
                                                                                                                                                                        echo $dget_requirement['divisi'];
                                                                                                                                                                    } ?>" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Posisi</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control form-control-sm" id="posisi" name="posisi" value="<?php if ($_GET['act'] == 'editoffering') {
                                                                                                                                                                        echo $dget_requirement['posisi'];
                                                                                                                                                                    } ?>" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Code</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control form-control-sm" id="project_code" name="project_code" value="<?php if ($_GET['act'] == 'editoffering') {
                                                                                                                                                                                    echo $dget_requirement['project_code'];
                                                                                                                                                                                } ?>" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Kandidat</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control form-control-sm" id="kandidat" name="kandidat" value="<?php if ($_GET['act'] == 'editoffering') {
                                                                                                                                                                            echo $dget_requirement['kandidat'];
                                                                                                                                                                        } ?>" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="row mb-3">
                                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Karyawan</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control form-control-sm" id="status_karyawan" name="status_karyawan" value="<?php if ($_GET['act'] == 'editoffering') {
                                                                                                                                                                                        echo $dget_requirement['status_karyawan'];
                                                                                                                                                                                    } ?>" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Jumlah Dibutuhkan</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control form-control-sm" id="jumlah_dibutuhkan" name="jumlah_dibutuhkan" value="<?php if ($_GET['act'] == 'editoffering') {
                                                                                                                                                                                            echo $dget_requirement['jumlah_dibutuhkan'];
                                                                                                                                                                                        } ?>" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Jumlah Sudah Complete</label>
                                                                    <div class="col-sm-9">
                                                                        <?php
                                                                        // $completequery = "SELECT COUNT(*) AS jumlah_complete FROM sa_hcm_requirement r WHERE r.id_fpkb = '" . $ddata['id_fpkb'] . "' AND EXISTS (SELECT 1 FROM sa_hcm_requirement_interview i WHERE i.id_fpkb = r.id_fpkb AND i.status = 'Complete Offering')";
                                                                        $totalcomplete = $DBHCM->get_sqlV2("SELECT COUNT(*) AS jumlah_complete FROM sa_hcm_requirement_interview r WHERE r.id_fpkb = '" . $ddata['id_fpkb'] . "' AND EXISTS (SELECT 1 FROM sa_hcm_requirement i WHERE i.id_fpkb = r.id_fpkb AND r.status = 'Complete Offering');");
                                                                        $total = $totalcomplete[0];
                                                                        ?>
                                                                        <input type="text" class="form-control form-control-sm" id="jumlah_complete" name="jumlah_complete" value="<?php if ($_GET['act'] == 'editoffering') {
                                                                                                                                                                                        echo $total['jumlah_complete'];
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
                                                                                <!-- <option value="Malik Aulia Wiratama <malik.aulia@mastersystem.co.id>">Malik Aulia Wiratama</option> -->
                                                                                <option value="Wahyuwinningdyah Margareta Sekarkusumo <margareta.sekar@mastersystem.co.id>">Wahyuwinningdyah Margareta Sekarkusumo</option>
                                                                                <?php //$manager_hcm = $DBHCM->get_sqlV2("SELECT * FROM sa_view_employees WHERE job_structure like '%JG ATN WM HCM Recruitment %' AND job_level = 3"); 
                                                                                ?>
                                                                                <?php //do { 
                                                                                ?>
                                                                                <!-- <option value="<? //php  echo $manager_hcm[0]['employee_name'] . " <" . $manager_hcm[0]['employee_email'] . ">"; 
                                                                                                    ?>"><?php //echo $manager_hcm[0]['employee_name']; 
                                                                                                        ?></option> -->
                                                                                <?php //} while ($manager_hcm[0] = $manager_hcm[1]->fetch_assoc()); 
                                                                                ?>
                                                                            </select>
                                                                        <?php } else { ?>
                                                                            <select class="form-control" name="manager_hcm" id="manager_hcm" readonly>
                                                                                <option value="<?php echo $ddata['manager_hcm']; ?>"><?php echo $ddata['manager_hcm']; ?></option>
                                                                            </select>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Manager HCM <b>*</b></label>
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
                                                                        <?php } elseif ($_GET['act'] == "editoffering") { ?>
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
                                                                                <!-- <option value="Malik Aulia Wiratama <malik.aulia@mastersystem.co.id>">Malik Aulia Wiratama</option> -->
                                                                                <?php $aproval_gm_hcm = $DBHCM->get_sqlV2("SELECT * FROM sa_view_employees WHERE job_structure like '%JG ATN %' AND job_level = 2"); ?>
                                                                                <?php do { ?>
                                                                                    <option value="<?php echo $aproval_gm_hcm[0]['employee_name'] . " <" . $aproval_gm_hcm[0]['employee_email'] . ">"; ?>"><?php echo $aproval_gm_hcm[0]['employee_name']; ?></option>
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
                                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">GM <b>*</b></label>
                                                                    <div class="col-sm-9">
                                                                        <?php if ($_GET['act'] == "editoffering" && $ddata['gm_offering'] == null) { ?>
                                                                            <select class="form-control" name="gm_offering" id="gm_offering">
                                                                                <option value="Malik Aulia Wiratama <malik.aulia@mastersystem.co.id>">Malik Aulia Wiratama</option>
                                                                                <?php $gm_offering = $DBHCM->get_sqlV2("SELECT * FROM sa_view_employees WHERE job_level = 2 AND resign_date is null"); ?>
                                                                                <?php do { ?>
                                                                                    <option value="<?php echo $gm_offering[0]['employee_name'] . " <" . $gm_offering[0]['employee_email'] . ">"; ?>"><?php echo $gm_offering[0]['employee_name']; ?></option>
                                                                                <?php } while ($gm_offering[0] = $gm_offering[1]->fetch_assoc()); ?>
                                                                            </select>
                                                                        <?php } else { ?>
                                                                            <select class="form-control" name="gm_offering" id="gm_offering" readonly>
                                                                                <option value="<?php echo $ddata['gm_offering']; ?>"><?php echo $ddata['gm_offering']; ?></option>
                                                                            </select>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status gm<b>*</b></label>
                                                                    <div class="col-sm-9">
                                                                        <?php if ($_GET['act'] == "editoffering" && $ddata['status_gm_offering'] == null) { ?>
                                                                            <select class="form-control" name="status_gm_offering" id="status_gm_offering">
                                                                                <option value="Pending">Pending</option>
                                                                            </select>
                                                                        <?php } elseif ($_GET['act'] == "editoffering" && $ddata['status_gm_offering'] == "Pending") { ?>
                                                                            <?php if ($ddata['gm_offering'] == $_SESSION['Microservices_UserEmail'] && $ddata['status_hcm_gm'] !== "Pending") { ?>
                                                                                <select class="form-control" name="status_gm_offering" id="status_gm_offering">
                                                                                    <option><?php echo ucwords($ddata['status_gm_offering']); ?></option>
                                                                                    <option value="Approve">Approve</option>
                                                                                    <option value="Disapprove">Disapprove</option>
                                                                                </select>
                                                                            <?php } else { ?>
                                                                                <select class="form-control" name="status_gm_offering" id="status_gm_offering" readonly>
                                                                                    <option><?php echo ucwords($ddata['status_gm_offering']); ?></option>
                                                                                </select>
                                                                            <?php } ?>
                                                                        <?php } elseif ($_GET['act'] == "editoffering" && $ddata['status_gm_offering'] == "Approve" || $_GET['act'] == "editoffering" && $ddata['status_gm_offering'] == "Disapprove") { ?>
                                                                            <select class="form-control" name="status_gm_offering" id="status_gm_offering" readonly>
                                                                                <option><?php echo ucwords($ddata['status_gm_offering']); ?></option>
                                                                            </select>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                                <?php if ($ddata['bod'] != NUll) { ?>
                                                                    <div>
                                                                        <div class="row mb-3">
                                                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">BOD <b>*</b></label>
                                                                            <div class="col-sm-9">
                                                                                <?php if ($_GET['act'] == "editoffering" && $ddata['bod'] == null) { ?>
                                                                                    <select class="form-control" name="bod" id="bod">
                                                                                        <option></option>
                                                                                        <option value="Malik Aulia Wiratama <malik.aulia@mastersystem.co.id>">Malik Aulia Wiratama</option>
                                                                                        <?php $bod = $DBHCM->get_sqlV2("SELECT * FROM sa_view_employees WHERE job_level = 1 AND resign_date is null"); ?>
                                                                                        <?php do { ?>
                                                                                            <option value="<?php echo $bod[0]['employee_name'] . " <" . $bod[0]['employee_email'] . ">"; ?>"><?php echo $bod[0]['employee_name']; ?></option>
                                                                                        <?php } while ($bod[0] = $bod[1]->fetch_assoc()); ?>
                                                                                    </select>
                                                                                <?php } else { ?>
                                                                                    <select class="form-control" name="bod" id="bod" readonly>
                                                                                        <option value="<?php echo $ddata['bod']; ?>"><?php echo $ddata['bod']; ?></option>
                                                                                    </select>
                                                                                <?php } ?>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mb-3">
                                                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status BOD<b>*</b></label>
                                                                            <div class="col-sm-9">
                                                                                <?php if ($_GET['act'] == "editoffering" && $ddata['status_bod'] == null) { ?>
                                                                                    <select class="form-control" name="status_bod" id="status_bod">
                                                                                        <option></option>
                                                                                        <option value="Pending">Pending</option>
                                                                                    </select>
                                                                                <?php } elseif ($_GET['act'] == "editoffering" && $ddata['status_bod'] == "Pending") { ?>
                                                                                    <?php if ($ddata['bod'] == $_SESSION['Microservices_UserEmail'] && $ddata['status_hcm_gm'] !== "Pending") { ?>
                                                                                        <select class="form-control" name="status_bod" id="status_bod">
                                                                                            <option><?php echo ucwords($ddata['status_bod']); ?></option>
                                                                                            <option value="Approve">Approve</option>
                                                                                            <option value="Disapprove">Disapprove</option>
                                                                                        </select>
                                                                                    <?php } else { ?>
                                                                                        <select class="form-control" name="status_bod" id="status_bod" readonly>
                                                                                            <option><?php echo ucwords($ddata['status_bod']); ?></option>
                                                                                        </select>
                                                                                    <?php } ?>
                                                                                <?php } elseif ($_GET['act'] == "editoffering" && $ddata['status_bod'] == "Approve" || $_GET['act'] == "editoffering" && $ddata['status_bod'] == "Disapprove") { ?>
                                                                                    <select class="form-control" name="status_bod" id="status_bod" readonly>
                                                                                        <option><?php echo ucwords($ddata['status_bod']); ?></option>
                                                                                    </select>
                                                                                <?php } ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php } ?>
                                                                <!-- <button type="button" id="showBOD" class="btn btn-primary">Show GM</button> -->
                                                                <?php if ($ddata['manager_hcm'] == NULL) { ?>
                                                                    <div class="row mb-3">
                                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm"></label>
                                                                        <div class="col-sm-9">
                                                                            <button type="button" id="showBOD" class="btn btn-primary">Show BOD</button>
                                                                        </div>
                                                                    </div>
                                                                <?php } ?>
                                                                <script>
                                                                    document.getElementById('showBOD').addEventListener('click', function() {
                                                                        var BODSection = document.getElementById('BODSection');
                                                                        var catatanBOD = document.getElementById('catatanBOD');
                                                                        if (BODSection.style.display === 'none') {
                                                                            BODSection.style.display = 'block';
                                                                            catatanBOD.style.display = 'block';
                                                                            document.getElementById('showBOD').textContent = 'Hide BOD';
                                                                        } else {
                                                                            BODSection.style.display = 'none';
                                                                            catatanBOD.style.display = 'none';
                                                                            document.getElementById('showBOD').textContent = 'Show BOD';
                                                                        }
                                                                    });
                                                                </script>

                                                                <div id="BODSection" style="display: none;">
                                                                    <div class="row mb-3">
                                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">BOD <b>*</b></label>
                                                                        <div class="col-sm-9">
                                                                            <?php if ($_GET['act'] == "editoffering" && $ddata['bod'] == null) { ?>
                                                                                <select class="form-control" name="bod" id="bod">
                                                                                    <option></option>
                                                                                    <!-- <option value="Malik Aulia Wiratama <malik.aulia@mastersystem.co.id>">Malik Aulia Wiratama</option> -->
                                                                                    <?php $bod = $DBHCM->get_sqlV2("SELECT * FROM sa_view_employees WHERE job_level = 1 AND resign_date is null"); ?>
                                                                                    <?php do { ?>
                                                                                        <option value="<?php echo $bod[0]['employee_email']; ?>"><?php echo $bod[0]['employee_name']; ?></option>
                                                                                    <?php } while ($bod[0] = $bod[1]->fetch_assoc()); ?>
                                                                                </select>
                                                                            <?php } else { ?>
                                                                                <select class="form-control" name="bod" id="bod" readonly>
                                                                                    <option value="<?php echo $ddata['bod']; ?>"><?php echo $ddata['bod']; ?></option>
                                                                                </select>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-3">
                                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status BOD<b>*</b></label>
                                                                        <div class="col-sm-9">
                                                                            <?php if ($_GET['act'] == "editoffering" && $ddata['status_bod'] == null) { ?>
                                                                                <select class="form-control" name="status_bod" id="status_bod">
                                                                                    <option></option>
                                                                                    <option value="Pending">Pending</option>
                                                                                </select>
                                                                            <?php } elseif ($_GET['act'] == "editoffering" && $ddata['status_bod'] == "Pending") { ?>
                                                                                <?php if ($ddata['bod'] == $_SESSION['Microservices_UserEmail'] && $ddata['status_hcm_gm'] !== "Pending") { ?>
                                                                                    <select class="form-control" name="status_bod" id="status_bod">
                                                                                        <option><?php echo ucwords($ddata['status_bod']); ?></option>
                                                                                        <option value="Approve">Approve</option>
                                                                                        <option value="Disapprove">Disapprove</option>
                                                                                    </select>
                                                                                <?php } else { ?>
                                                                                    <select class="form-control" name="status_bod" id="status_bod" readonly>
                                                                                        <option><?php echo ucwords($ddata['status_bod']); ?></option>
                                                                                    </select>
                                                                                <?php } ?>
                                                                            <?php } elseif ($_GET['act'] == "editoffering" && $ddata['status_bod'] == "Approve" || $_GET['act'] == "editoffering" && $ddata['status_bod'] == "Disapprove") { ?>
                                                                                <select class="form-control" name="status_bod" id="status_bod" readonly>
                                                                                    <option><?php echo ucwords($ddata['status_bod']); ?></option>
                                                                                </select>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
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
                                                                <div class="row mb-3" id="catatan-gm_offering">
                                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan GM</label>
                                                                    <div class="col-sm-9">
                                                                        <?php if ($ddata['catatan_gm_offering'] == null) { ?>
                                                                            <textarea type="text" class="form-control form-control-sm" id="catatan_gm_offering" name="catatan_gm_offering" rows="2"></textarea>
                                                                        <?php } else { ?>
                                                                            <textarea type="text" class="form-control form-control-sm" id="catatan_gm_offering" name="catatan_gm_offering" rows="2" readonly> <?php echo $ddata['catatan_gm_offering']; ?></textarea>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                                <div id="catatanBOD" style="display: none;">
                                                                    <div class="row mb-3">
                                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan BOD</label>
                                                                        <div class="col-sm-9">
                                                                            <?php if ($ddata['catatan_bod'] == null) { ?>
                                                                                <textarea type="text" class="form-control form-control-sm" id="catatan_bod" name="catatan_bod" rows="2"></textarea>
                                                                            <?php } else { ?>
                                                                                <textarea type="text" class="form-control form-control-sm" id="catatan_bod" name="catatan_bod" rows="2" readonly><?php echo $ddata['catatan_bod']; ?></textarea>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php if ($ddata['bod'] != NULL) { ?>
                                                                    <div>
                                                                        <div class="row mb-3">
                                                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan BOD</label>
                                                                            <div class="col-sm-9">
                                                                                <?php if ($ddata['catatan_bod'] == null) { ?>
                                                                                    <textarea type="text" class="form-control form-control-sm" id="catatan_bod" name="catatan_bod" rows="2"></textarea>
                                                                                <?php } else { ?>
                                                                                    <textarea type="text" class="form-control form-control-sm" id="catatan_bod" name="catatan_bod" rows="2" readonly><?php echo $ddata['catatan_bod']; ?></textarea>
                                                                                <?php } ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php } ?>

                                                                <!-- <div class="row mb-3">
                                                            <?php //if ($_GET['act'] == "editoffering" && $dget_requirement['status_manager_hcm'] == "Approve" && $dget_requirement['status_hcm_gm'] == "Approve" && $dget_requirement['status_manager_gm_bod'] == "Approve") { 
                                                            ?>
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Join Date </label>
                                                                <div class="col-sm-9">
                                                                    <?php //if ($_GET['act'] == "editoffering") { 
                                                                    ?>
                                                                        <input type="date" class="form-control form-control-sm" id="join_date" name="join_date" value="">
                                                                    <?php //} else { 
                                                                    ?>
                                                                        <input type="date" class="form-control form-control-sm" id="join_date" name="join_date" value="<?php //if ($_GET['act'] == 'editoffering') {
                                                                                                                                                                        //echo $dget_requirement['join_date'];
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
                                                <div class="card shadow mb-4">
                                                    <div class="card-body">
                                                        <h2></h2>
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="row mb-3">
                                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Nama Kandidat <b>*</b></label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control form-control-sm" id="nama_kandidat" name="nama_kandidat" value="<?php if ($_GET['act'] == 'editoffering') {
                                                                                                                                                                                    echo $ddata['nama_kandidat'];
                                                                                                                                                                                } ?>" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Kandidat <b>*</b></label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control form-control-sm" id="email" name="email" value="<?php if ($_GET['act'] == 'editoffering') {
                                                                                                                                                                    echo $ddata['email'];
                                                                                                                                                                } ?>" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Tanggal Interview <b>*</b></label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control form-control-sm" id="tanggal_interview" name="tanggal_interview" value="<?php if ($_GET['act'] == 'editoffering') {
                                                                                                                                                                                            echo $ddata['tanggal_interview'];
                                                                                                                                                                                        } ?>" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">PIC (Recruiter) <b>*</b></label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control form-control-sm" id="pic" name="pic" value="<?php if ($_GET['act'] == 'editoffering') {
                                                                                                                                                                echo $ddata['pic'];
                                                                                                                                                            } ?>" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3" hidden>
                                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">status <b>*</b></label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control form-control-sm" id="status" name="status" value="<?php if ($_GET['act'] == 'editoffering') {
                                                                                                                                                                        echo $ddata['status'];
                                                                                                                                                                    } ?>" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="row mb-3">
                                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan <b>*</b></label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control form-control-sm" id="catatan" name="catatan" value="<?php if ($_GET['act'] == 'editoffering') {
                                                                                                                                                                        echo $ddata['catatan'];
                                                                                                                                                                    } ?>" readonly>
                                                                    </div>
                                                                </div>
                                                                <?php if ($dget_requirement['status_karyawan'] == "Percobaan") { ?>
                                                                    <div class="row mb-3">
                                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Salary Probation <b>*</b></label>
                                                                        <div class="col-sm-9">
                                                                            <?php if ($ddata['salary_Probation'] == NULL) { ?>
                                                                                <input type="text" class="form-control text-end" style="text-align: right;" id="salary_Probation" name="salary_Probation" onchange="format_amount_idr('salary_Probation');">
                                                                            <?php } else { ?>
                                                                                <input type=" text" class="form-control text-end" style="text-align: right;" id="salary_Probation" name="salary_Probation" value="<?php if ($_GET['act'] == 'editoffering') {
                                                                                                                                                                                                                        echo $ddata['salary_Probation'];
                                                                                                                                                                                                                    } ?>" readonly>
                                                                            <?php } ?>
                                                                            <script>
                                                                                function format_amount_idr(id) {
                                                                                    var input = document.getElementById(id);
                                                                                    var value = input.value.replace(/,/g, ''); // Remove existing commas

                                                                                    if (!isNaN(value) && value !== '') {
                                                                                        var formattedValue = parseFloat(value).toLocaleString('en-US'); // Format with commas
                                                                                        input.value = formattedValue.replace(/,/g, '.') + ',00'; // Replace commas with periods and append ,00
                                                                                    }
                                                                                }

                                                                                // Call the function on page load to format any pre-filled value
                                                                                document.addEventListener('DOMContentLoaded', (event) => {
                                                                                    format_amount_idr('salary_Probation');
                                                                                });
                                                                            </script>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mb-3">
                                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Salary Permanent<b>*</b></label>
                                                                        <div class="col-sm-9">
                                                                            <?php if ($ddata['salary_Permanent'] == NULL) { ?>
                                                                                <input type="text" class="form-control text-end" style="text-align: right;" id="salary_Permanent" name="salary_Permanent" onchange="format_amount_idr('salary_Permanent');">
                                                                            <?php } else { ?>
                                                                                <input type=" text" class="form-control text-end" style="text-align: right;" id="salary_Permanent" name="salary_Permanent" value="<?php if ($_GET['act'] == 'editoffering') {
                                                                                                                                                                                                                        echo $ddata['salary_Permanent'];
                                                                                                                                                                                                                    } ?>" readonly>
                                                                            <?php } ?>
                                                                            <script>
                                                                                function format_amount_idr(id) {
                                                                                    var input = document.getElementById(id);
                                                                                    var value = input.value.replace(/,/g, ''); // Remove existing commas

                                                                                    if (!isNaN(value) && value !== '') {
                                                                                        var formattedValue = parseFloat(value).toLocaleString('en-US'); // Format with commas
                                                                                        input.value = formattedValue.replace(/,/g, '.') + ',00'; // Replace commas with periods and append ,00
                                                                                    }
                                                                                }

                                                                                // Call the function on page load to format any pre-filled value
                                                                                document.addEventListener('DOMContentLoaded', (event) => {
                                                                                    format_amount_idr('salary_Permanent');
                                                                                });
                                                                            </script>
                                                                        </div>
                                                                    </div>

                                                                <?php } ?>
                                                                <?php if ($dget_requirement['status_karyawan'] != "Percobaan") { ?>
                                                                    <div class="row mb-3">
                                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Offering Salary <b>*</b></label>
                                                                        <div class="col-sm-9">
                                                                            <?php if ($ddata['offering_salary'] == NULL) { ?>
                                                                                <input type="text" class="form-control text-end" style="text-align: right;" id="offering_salary" name="offering_salary" onchange="format_amount_idr('offering_salary');">
                                                                            <?php } else { ?>
                                                                                <input type=" text" class="form-control text-end" style="text-align: right;" id="offering_salary" name="offering_salary" value="<?php if ($_GET['act'] == 'editoffering') {
                                                                                                                                                                                                                    echo $ddata['offering_salary'];
                                                                                                                                                                                                                } ?>" readonly>
                                                                            <?php } ?>
                                                                            <script>
                                                                                function format_amount_idr(id) {
                                                                                    var input = document.getElementById(id);
                                                                                    var value = input.value.replace(/,/g, ''); // Remove existing commas

                                                                                    if (!isNaN(value) && value !== '') {
                                                                                        var formattedValue = parseFloat(value).toLocaleString('en-US'); // Format with commas
                                                                                        input.value = formattedValue.replace(/,/g, '.') + ',00'; // Replace commas with periods and append ,00
                                                                                    }
                                                                                }

                                                                                // Call the function on page load to format any pre-filled value
                                                                                document.addEventListener('DOMContentLoaded', (event) => {
                                                                                    format_amount_idr('offering_salary');
                                                                                });
                                                                            </script>
                                                                        </div>
                                                                    </div>
                                                                <?php } ?>
                                                                <div class="col-lg-12">
                                                                    <div class="row mb-3">
                                                                        <?php if ($_GET['act'] == 'editoffering') { ?>
                                                                            <!-- <button type="button" class="btn btn-primary" name="btn_upload" id="btn_upload" data-bs-toggle="modal" data-bs-target="#fileupload">Upload File</button> -->
                                                                            <button type="button" class="btn btn-primary" name="btn_upload" id="btn_upload" data-bs-toggle="modal" data-bs-target="#fileuploaddriveoffering">Upload File</button>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                                <?php if ($ddata['status'] == "Complete") { ?>
                                                                    <div class="row mb-3">
                                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Join Date <b>*</b></label>
                                                                        <div class="col-sm-9">
                                                                            <?php if ($ddata['join_date'] == null) { ?>
                                                                                <input type="date" class="form-control form-control-sm" id="join_date" name="join_date">
                                                                                <!-- <input type="date" class="form-control form-control-sm" id="tanggal_dibutuhkan" name="tanggal_dibutuhkan" required> -->
                                                                            <?php } else { ?>
                                                                                <input type="date" class="form-control form-control-sm" id="join_date" name="join_date" value="<?php if ($_GET['act'] == 'editoffering') {
                                                                                                                                                                                    echo $ddata['join_date'];
                                                                                                                                                                                } ?>" readonly>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                <?php } ?>
                                                                <?php if ($ddata['status'] == "Complete Offering") { ?>
                                                                    <div class="row mb-3">
                                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Join Date <b>*</b></label>
                                                                        <div class="col-sm-9">
                                                                            <input type="date" class="form-control form-control-sm" id="join_date" name="join_date" value="<?php if ($_GET['act'] == 'editoffering') {
                                                                                                                                                                                echo $ddata['join_date'];
                                                                                                                                                                            } ?>" readonly>
                                                                        </div>
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <?php
                                                    // include_once('C:\xampp\htdocs\microservices\google-drive-requirement.php');
                                                    include $_SERVER['DOCUMENT_ROOT'] . 'google-drive-requirement.php';
                                                    $driveClient = getDriveClient();
                                                    $driveService = new Google_Service_Drive($driveClient);
                                                    // $project_code = $_GET['project_code'];
                                                    $cobaan = "SELECT * FROM sa_hcm_requirement_interview WHERE email_id = '" . $_GET['id'] . "'";
                                                    $getidfolder = $DBHCM->get_sqlV2("SELECT * FROM sa_hcm_requirement_interview WHERE email_id = '" . $_GET['id'] . "'");
                                                    // var_dump($getidfolder);
                                                    // die;

                                                    $folderId = null;
                                                    if ($getidfolder && is_array($getidfolder) && isset($getidfolder[0]['id_folderdrive'])) {
                                                        $folderId = $getidfolder[0]['id_folderdrive'];
                                                        // var_dump($folderId);
                                                        // die;
                                                    }

                                                    // function deleteFileFromDrive($service, $fileId)
                                                    // {
                                                    //     try {
                                                    //         $service->files->delete($fileId, array('supportsAllDrives' => true));
                                                    //         echo "File deleted successfully.";
                                                    //     } catch (Exception $e) {
                                                    //         echo "An error occurred: " . $e->getMessage();
                                                    //     }
                                                    // }

                                                    // if (isset($_POST['delete_file_id'])) {
                                                    //     deleteFileFromDrive($driveService, $_POST['delete_file_id']);
                                                    // }

                                                    if ($folderId) {
                                                        $files = listFilesInFolder($driveService, $folderId);
                                                    }
                                                    ?>

                                                    <?php if ($folderId) : ?>
                                                        <table class="table table-sm table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">Nama File</th>
                                                                    <!-- <th scope="col">Tanggal Interview</th>
                <th scope="col" style="text-align: center;">Action</th> -->
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $filenote = $DBHCM->get_sqlV2("SELECT * FROM sa_hcm_requirement_interview WHERE email_id = '" . $_GET['id'] . "'");
                                                                $idfile = $filenote[0]['id_file'];
                                                                ?>
                                                                <tr>
                                                                    <input type="hidden" name="id_note[]" id="id_note[]" value="<?php echo $filenote[0]['id_note'] ?>">

                                                                    <input type="hidden" name="malik[]" value="<?= htmlspecialchars($filenote[0]['file']) ?>">
                                                                    <td>
                                                                        <?php if ($idfile) : ?>
                                                                            <a href="https://drive.google.com/file/d/<?= $idfile ?>/view" target="_blank"><?= htmlspecialchars($filenote[0]['file']) ?></a>
                                                                        <?php else : ?>
                                                                            <?= htmlspecialchars($filenote[0]['file']) ?>
                                                                        <?php endif; ?>
                                                                    </td>

                                                                    <input type="hidden" name="id_file[]" id="id_file[]" value="<?php echo $filenote[0]['id_file']; ?>">
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <div class="text-right mt-3">
                                                            <button type="submit" name="send_email_offering" class="btn btn-danger btn-sm">Send Email Offering</button>
                                                        </div>
                                                    <?php else : ?>
                                                        <p>Folder ID is not set.</p>
                                                    <?php endif; ?>

                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                                        <div class="card shadow mb-4">
                                            <div class="card-body">
                                                <?php
                                                $maxRows = 100;

                                                if (isset($_GET['maxRows'])) {
                                                    $maxRows = $_GET['maxRows'];
                                                }

                                                $tbl_resource_logs = "hcm_requirement_log";
                                                // $condition = "project_code = '" . $_GET['project_code'] . "' ORDER BY entry_date ASC";
                                                $condition = "id_fpkb = '" . $dget_requirement['id_fpkb'] . "' AND project_code = '" . $dget_requirement['project_code'] . "' ORDER BY entry_date ASC";
                                                $dataLogResource = $DBHCM->get_data($tbl_resource_logs, $condition, "", 0, $maxRows);
                                                if ($dataLogResource[2] > 0) {
                                                ?>

                                                    <h5>History</h5>
                                                    <table class="table">
                                                        <thead class="bg-light">
                                                            <th>Date</th>
                                                            <th>Time</th>
                                                            <th>Description</th>
                                                        </thead>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $tgl = "";
                                                            ?>
                                                            <?php do { ?>
                                                                <tr>
                                                                    <td style="font-size: 12px">
                                                                        <?php if ($tgl = date("Y-m-d", strtotime($dataLogResource[0]['entry_date']))) { ?>
                                                                            <table class="table table-sm table-light table-striped">
                                                                                <tr>
                                                                                    <td class="text-center fw-bold" colspan="2">
                                                                                        <?php echo date("Y", strtotime($dataLogResource[0]['entry_date'])); ?></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="text-center">
                                                                                        <?php echo date("M", strtotime($dataLogResource[0]['entry_date'])); ?>
                                                                                    </td>
                                                                                    <td class="text-center">
                                                                                        <?php echo date("d", strtotime($dataLogResource[0]['entry_date'])); ?>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>

                                                                        <?php
                                                                        } ?>
                                                                    </td>
                                                                    <td style="font-size: 12px">
                                                                        <?php echo date("H:i:s", strtotime($dataLogResource[0]['entry_date'])); ?></td>
                                                                    <td style="font-size: 12px">
                                                                        <?php
                                                                        $description = str_replace(" -", "", $dataLogResource[0]['description']);
                                                                        echo $description . "<br/>Performed by " . $dataLogResource[0]['entry_by']; ?></td>
                                                                </tr>
                                                                <?php $tgl = date("Y-m-d", strtotime($dataLogResource[0]['entry_date'])); ?>
                                                            <?php } while ($dataLogResource[0] = $dataLogResource[1]->fetch_assoc()); ?>
                                                        </tbody>
                                                    </table>
                                                <?php } ?>
                                                <!-- <div class="" style="font-size: 12px">Readmore...</div> -->
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end mb-3">
                                        <?php if ($ddata['status'] == 'Complete') { ?>
                                            <?php if ($ddata['email_id'] == $_GET['id'] && $ddata['status_manager_hcm'] == "Disapprove" || $ddata['status_hcm_gm'] == "Disapprove" || $ddata['status_gm_offering'] == "Disapprove" || $ddata['status_bod'] == "Disapprove") { ?>
                                                <div class="d-flex justify-content-end mb-3">
                                                    <input type="submit" class="btn btn-warning mr-3" name="hold_offering" value="Hold Offering">
                                                    <input type="submit" class="btn btn-danger mr-3" name="cancel_offering" value="Cancel Offering">
                                                </div>
                                            <?php } ?>
                                        <?php } ?>
                                        <?php if ($ddata['status'] == "Hold Offering") { ?>
                                            <div class="d-flex justify-content-end mb-3">
                                                <input type="submit" class="btn btn-primary mr-3" name="reopen_offering" value="Reopen Offering">
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>


                            <?php } ?>
                            <button type="cancel" class="btn btn-secondary" onclick="window.location='https://msizone.mastersystem.co.id/index.php?mod=hcm_requirement';return false;">Cancel</button>
                            <!-- <input type="submit" class="btn btn-secondary" name="cancel" value="Cancel"> -->
                            <?php if (isset($_GET['act']) && $_GET['act'] == 'edit') { ?>
                                <input type="submit" class="btn btn-primary" name="save" value="Save">
                                <?php if (isset($_GET['act']) && $_GET['act'] == 'edit' && $ddata['request_by'] == $_SESSION['Microservices_UserName'] . " <" . $_SESSION['Microservices_UserEmail'] . ">") { ?>
                                    <input type="submit" class="btn btn-warning" name="hold" value="Hold">
                                    <input type="submit" class="btn btn-danger" name="inactive" value="Inactive">
                                <?php } ?>
                                <?php if (isset($_GET['act']) && $_GET['act'] == 'edit' && $ddata['status_gm'] == 'Pending' && $ddata['gm'] == $_SESSION['Microservices_UserName'] . " <" . $_SESSION['Microservices_UserEmail'] . ">") { ?>
                                    <input type="submit" class="btn btn-success" name="approve_gm" value="Approve">
                                    <input type="submit" class="btn btn-danger" name="dissaprove_gm" value="Dissaprove">
                                <?php } ?>
                                <?php if (isset($_GET['act']) && $_GET['act'] == 'edit' && $ddata['status_gm_hcm'] == 'Pending' && $ddata['status_gm'] == 'Approve' && $ddata['gm_hcm'] == $_SESSION['Microservices_UserName'] . " <" . $_SESSION['Microservices_UserEmail'] . ">") { ?>
                                    <input type="submit" class="btn btn-success" name="approve_gm_hcm" value="Approve">
                                    <input type="submit" class="btn btn-danger" name="dissaprove_gm_hcm" value="Dissaprove">
                                <?php } ?>
                                <?php if (isset($_GET['act']) && $_GET['act'] == 'edit' && $ddata['status_rekrutmen'] == 'Penambahan' && $ddata['status_gm_hcm'] == 'Approve' && $ddata['gm_bod'] == $_SESSION['Microservices_UserName'] . " <" . $_SESSION['Microservices_UserEmail'] . ">") { ?>
                                    <input type="submit" class="btn btn-success" name="approve_gm_bod" value="Approve">
                                    <input type="submit" class="btn btn-danger" name="dissaprove_gm_bod" value="Dissaprove">
                                <?php } ?>


                            <?php } elseif (isset($_GET['act']) && $_GET['act'] == 'add') { ?>
                                <input type="submit" class="btn btn-primary" name="add" value="Save" id="saveButton">
                            <?php } elseif (isset($_GET['act']) && $_GET['act'] == 'editapproval') { ?>
                                <?php //if ($_SESSION['Microservices_UserEmail'] == 'margareta.sekar@mastersystem.co.id') {
                                ?>
                                <!-- <input type="submit" class="btn btn-primary" name="editapproval" value="Save"> -->
                                <?php //} 
                                ?>
                                <input type="submit" class="btn btn-primary" name="editapproval" value="Save">
                            <?php } elseif (isset($_GET['act']) && $_GET['act'] == 'editshare') { ?>
                                <input type="submit" class="btn btn-primary" name="editshare" value="Save">
                                <!-- <input type="submit" class="btn btn-primary" name="complete" value="Complete"> -->
                            <?php } elseif (isset($_GET['act']) && $_GET['act'] == 'editinterview') { ?>
                                <input type="submit" class="btn btn-primary" name="editinterview" value="Save">
                            <?php } elseif (isset($_GET['act']) && $_GET['act'] == 'editoffering') { ?>
                                <?php
                                $query2 = "SELECT * FROM sa_hcm_requirement_interview WHERE email_id = " . $_GET['id'];
                                $cek = $DBHCM->get_sqlV2("SELECT * FROM sa_hcm_requirement_interview WHERE email_id = '" . $_GET['id'] . "'");
                                if ($cek[0]['status'] == 'Complete') { ?>
                                    <input type="submit" class="btn btn-primary" name="complete_offering" value="Complete Offering">
                                <?php } else { ?>
                                    <input type="submit" class="btn btn-primary" name="editoffering" value="Save">
                                <?php } ?>
                                <?php if ($ddata['status_manager_hcm'] == 'Pending' && $ddata['manager_hcm'] == $_SESSION['Microservices_UserName'] . " <" . $_SESSION['Microservices_UserEmail'] . ">") { ?>
                                    <input type="submit" class="btn btn-success" name="approve_manager_hcm_offering" value="Approve">
                                    <input type="submit" class="btn btn-danger" name="dissaprove_manager_hcm_offering" value="Dissaprove">
                                <?php } ?>
                                <?php if ($ddata['status_hcm_gm'] == 'Pending' && $ddata['status_manager_hcm'] !== 'Pending' && $ddata['hcm_gm'] == $_SESSION['Microservices_UserName'] . " <" . $_SESSION['Microservices_UserEmail'] . ">") { ?>
                                    <input type="submit" class="btn btn-success" name="approve_hcm_gm_offering" value="Approve">
                                    <input type="submit" class="btn btn-danger" name="dissaprove_hcm_gm_offering" value="Dissaprove">
                                <?php } ?>
                                <?php if ($ddata['status_gm_offering'] == 'Pending' && $ddata['gm_offering'] == $_SESSION['Microservices_UserName'] . " <" . $_SESSION['Microservices_UserEmail'] . ">") { ?>
                                    <input type="submit" class="btn btn-success" name="approve_gm_offering_offering" value="Approve">
                                    <input type="submit" class="btn btn-danger" name="dissaprove_gm_offering_offering" value="Dissaprove">
                                <?php } ?>
                                <?php if ($ddata['status_gm_offering'] !== 'Pending' && $ddata['bod'] == $_SESSION['Microservices_UserName'] . " <" . $_SESSION['Microservices_UserEmail'] . ">") { ?>
                                    <input type="submit" class="btn btn-success" name="approve_bod_offering" value="Approve">
                                    <input type="submit" class="btn btn-danger" name="dissaprove_bod_offering" value="Dissaprove">
                                <?php } ?>
                            <?php } ?>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    // Prevent form submission when Enter is pressed
                                    document.addEventListener('keydown', function(event) {
                                        if (event.key === 'Enter') {
                                            event.preventDefault();
                                        }
                                    });
                                });
                            </script>
                            </form>

                            <div class="modal fade" id="fileuploaddrive" tabindex="-1" aria-labelledby="saveLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="saveLabel"><b>Upload File</b></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            <!-- <button type="button" class="btn-success" data-bs-dismiss="modal" aria-label="Save" name="malik"></button> -->
                                        </div>
                                        <div class="modal-body">
                                            <div class="row mb-3">

                                                <body>
                                                    <?php
                                                    // $coba = "SELECT * FROM sa_hcm_requirement WHERE id = '" . $_GET['id'] . "' AND project_code ='" . $_GET['project_code'] . "'";
                                                    // $allin = $DBHCM->get_sqlV2("SELECT * FROM sa_hcm_requirement WHERE id = '" . $_GET[0]['id'] . "'project_code ='" . $_GET['project_code'] . "'");
                                                    // var_dump($coba);
                                                    // die;
                                                    ?>
                                                    <div class="container mt-5">
                                                        <form method="post" action="components/modules/hcm_requirement/upload_submit.php" enctype="multipart/form-data">
                                                            <div class="form-group row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Recruitment Source</label>
                                                                <div class="col-sm-9">
                                                                    <select class="form-control" name="link_from" id="link_from">
                                                                        <option> -Pilih Source-</option>
                                                                        <?php $linkfrom = $DBHCM->get_sql("SELECT * FROM sa_hcm_link_requirement"); ?>
                                                                        <?php do { ?>
                                                                            <option value="<?php echo $linkfrom[0]['link_from']; ?>"><?php echo $linkfrom[0]['link_from']; ?></option>
                                                                        <?php } while ($linkfrom[0] = $linkfrom[1]->fetch_assoc()); ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">ID Request</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="id_request" name="id_request" value="<?php echo htmlspecialchars($_GET['id']); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Assign Recruiter</label>
                                                                <div class="col-sm-9">
                                                                    <?php
                                                                    $allin = $DBHCM->get_sqlV2("SELECT * FROM sa_hcm_requirement WHERE id = '" . $_GET['id'] . "'");
                                                                    ?>
                                                                    <input type="text" class="form-control form-control-sm" id="assign_requirement" name="assign_requirement" value="<?php echo htmlspecialchars($allin[0]['assign_requirement']); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Request By</label>
                                                                <div class="col-sm-9">
                                                                    <?php
                                                                    $allin = $DBHCM->get_sqlV2("SELECT * FROM sa_hcm_requirement WHERE id = '" . $_GET['id'] . "'");
                                                                    ?>
                                                                    <input type="text" class="form-control form-control-sm" id="request_by" name="request_by" value="<?php echo htmlspecialchars($allin[0]['request_by']); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">ID FPKB</label>
                                                                <div class="col-sm-9">
                                                                    <?php
                                                                    $allin = $DBHCM->get_sqlV2("SELECT * FROM sa_hcm_requirement WHERE id = '" . $_GET['id'] . "'");
                                                                    ?>
                                                                    <input type="text" class="form-control form-control-sm" id="id_fpkb" name="id_fpkb" value="<?php echo htmlspecialchars($allin[0]['id_fpkb']); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mb-3" hidden>
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Recruitment Team</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="recruitment_team" name="recruitment_team" value="Recruitment Team <recruitment.team@mastersystem.co.id>">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Code</label>
                                                                <div class="col-sm-9">
                                                                    <input hidden type="text" class="form-control form-control-sm" id="id" name="id" value="<?php echo htmlspecialchars($_GET['id']); ?>">
                                                                    <input readonly type="text" class="form-control form-control-sm" id="project_code" name="project_code" value="<?php echo htmlspecialchars($_GET['project_code']); ?>">
                                                                    <input hidden type="text" class="form-control form-control-sm" id="project_name" name="project_name" value="<?php echo htmlspecialchars($_GET['project_name']); ?>">

                                                                </div>
                                                            </div>
                                                            <div class="form-group row mb-3">
                                                                <label for="file" class="col-sm-3 col-form-label col-form-label-sm">Choose File</label>
                                                                <div class="col-sm-9">
                                                                    <input type="file" name="file" id="file" class="form-control form-control-sm">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-sm-9 offset-sm-3">
                                                                    <input type="submit" name="submit" value="Submit" class="btn btn-primary mt-3">
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </body>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="fileuploaddriveoffering" tabindex="-1" aria-labelledby="saveLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="saveLabel"><b>Upload File Offering</b></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            <!-- <button type="button" class="btn-success" data-bs-dismiss="modal" aria-label="Save" name="malik"></button> -->
                                        </div>
                                        <div class="modal-body">
                                            <div class="row mb-3">

                                                <body>
                                                    <div class="container mt-5">
                                                        <form method="post" action="components/modules/hcm_requirement/upload_submit_offering.php" enctype="multipart/form-data">

                                                            <div class="form-group row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Request By</label>
                                                                <div class="col-sm-9">
                                                                    <?php
                                                                    $allin = $DBHCM->get_sqlV2("SELECT * FROM sa_hcm_requirement WHERE id_fpkb = '" . $_GET['id_fpkb'] . "'");
                                                                    ?>
                                                                    <input type="text" class="form-control form-control-sm" id="request_by" name="request_by" value="<?php echo htmlspecialchars($allin[0]['request_by']); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">ID FPKB</label>
                                                                <div class="col-sm-9">
                                                                    <?php
                                                                    $allin = $DBHCM->get_sqlV2("SELECT * FROM sa_hcm_requirement WHERE id_fpkb = '" . $_GET['id_fpkb'] . "'");
                                                                    ?>
                                                                    <input type="text" class="form-control form-control-sm" id="id_fpkb" name="id_fpkb" value="<?php echo htmlspecialchars($allin[0]['id_fpkb']); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mb-3" hidden>
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">ID</label>
                                                                <div class="col-sm-9">
                                                                    <?php
                                                                    $allin = $DBHCM->get_sqlV2("SELECT * FROM sa_hcm_requirement_interview WHERE email_id = '" . $_GET['id'] . "'");
                                                                    ?>
                                                                    <input type="text" class="form-control form-control-sm" id="email_id" name="email_id" value="<?php echo htmlspecialchars($allin[0]['email_id']); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mb-3" hidden>
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">ID FolderDrive</label>
                                                                <div class="col-sm-9">
                                                                    <?php
                                                                    $allin = $DBHCM->get_sqlV2("SELECT * FROM sa_hcm_notecv WHERE id_fpkb = '" . $_GET['id_fpkb'] . "'");
                                                                    ?>
                                                                    <input type="text" class="form-control form-control-sm" id="id_folderdrive" name="id_folderdrive" value="<?php echo htmlspecialchars($allin[0]['id_folderdrive']); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mb-3" hidden>
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Recruitment Team</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="recruitment_team" name="recruitment_team" value="Recruitment Team <recruitment.team@mastersystem.co.id>">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Nama Kandidat</label>
                                                                <div class="col-sm-9">
                                                                    <?php
                                                                    $allin = $DBHCM->get_sqlV2("SELECT * FROM sa_hcm_requirement_interview WHERE email_id = '" . $_GET['id'] . "'");
                                                                    ?>
                                                                    <input type="text" class="form-control form-control-sm" id="nama_kandidat" name="nama_kandidat" value="<?php echo htmlspecialchars($allin[0]['nama_kandidat']); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row mb-3">
                                                                <label for="file" class="col-sm-3 col-form-label col-form-label-sm">Choose File</label>
                                                                <div class="col-sm-9">
                                                                    <input type="file" name="file" id="file" class="form-control form-control-sm">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-sm-9 offset-sm-3">
                                                                    <input type="submit" name="submit" value="Submit" class="btn btn-primary mt-3">
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </body>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel">Confirmation</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete this file?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="tambahLinkFormModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form method="post">
                                            <div class="modal-header">
                                                <h5 class="col-sm-9" id="exampleModalLabel">New Requirement Source</h5>
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

                                                <input type="submit" class="btn btn-primary" name="addlinkfrom" value="Save">

                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="tambahposisibaru" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form method="post">
                                            <div class="modal-header">
                                                <h5 class="col-sm-9" id="exampleModalLabel">Posisi</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Posisi</label>
                                                    <input type="text" class="form-control" id="posisi" name="posisi">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>

                                                <input type="submit" class="btn btn-primary" name="addposisibaru" value="Save">

                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- <script>
                                $(document).ready(function() {
                                    // When the division is changed
                                    $("#divisi").on('change', function() {
                                        var divisi = $(this).val();
                                        var projectCode = $("#project_code").val(); // Keep project code if already selected

                                        var url = window.location.pathname + "?mod=hcm_requirement&act=add&divisi=" + divisi;
                                        if (projectCode) {
                                            url += "&project_code=" + projectCode;
                                        }

                                        window.location = url;
                                    });

                                    // When the project code is changed
                                    $("#project_code").on('change', function() {
                                        var divisi = $("#divisi").val(); // Preserve the division selection
                                        var projectCode = $(this).val();

                                        var url = window.location.pathname + "?mod=hcm_requirement&act=add";
                                        if (divisi) {
                                            url += "&divisi=" + divisi;
                                        }
                                        url += "&project_code=" + projectCode;

                                        window.location = url;
                                    });
                                });
                            </script> -->

                            <script>
                                $(document).ready(function() {

                                    // Load values from localStorage if they exist
                                    const fields = [
                                        'posisi', 'jumlah_dibutuhkan', 'tanggal_dibutuhkan', 'deskripsi', 'status_karyawan', 'mpp', 'jenis_kelamin', 'usia',
                                        'pendidikan_minimal', 'jurusan', 'pengalaman_minimal', 'kompetensi_teknis',
                                        'kompetensi_non_teknis', 'catatan', 'from_salary', 'to_salary'
                                    ];

                                    fields.forEach(field => {
                                        if (localStorage.getItem(field)) {
                                            $(`#${field}`).val(localStorage.getItem(field));
                                        }
                                    });

                                    // Save fields to localStorage on change
                                    fields.forEach(field => {
                                        $(`#${field}`).on('change', function() {
                                            localStorage.setItem(field, $(this).val());
                                        });
                                    });

                                    // When the division is changed
                                    $("#divisi").on('change', function() {
                                        var divisi = $(this).val();
                                        var projectCode = $("#project_code").val(); // Keep project code if already selected

                                        // Save the current state to localStorage
                                        localStorage.setItem('divisi', divisi);

                                        var url = window.location.pathname + "?mod=hcm_requirement&act=add&divisi=" + divisi;
                                        if (projectCode) {
                                            url += "&project_code=" + projectCode;
                                        }

                                        window.location = url;
                                    });

                                    $("#project_code").on('change', function() {
                                        var divisi = $("#divisi").val(); // Preserve the division selection
                                        var projectCode = $(this).val();

                                        // Save the current state to localStorage
                                        localStorage.setItem('divisi', divisi);
                                        localStorage.setItem('project_code', projectCode);

                                        var url = window.location.pathname + "?mod=hcm_requirement&act=add";
                                        if (divisi) {
                                            url += "&divisi=" + divisi;
                                        }
                                        url += "&project_code=" + projectCode;

                                        window.location = url;
                                    });

                                    // Membersihkan localStorage saat tombol "Save" diklik
                                    $("#saveButton").on('click', function() {
                                        // Lakukan penghapusan localStorage
                                        fields.forEach(field => {
                                            localStorage.removeItem(field);
                                        });

                                        // Jika Anda ingin melakukan sesuatu setelah menghapus localStorage, tambahkan logika di sini.
                                        // Misalnya, mengirimkan data melalui AJAX atau mengalihkan halaman.
                                        // alert('Data has been saved and localStorage has been cleared.');
                                    });
                                });
                            </script>

                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $('#divisi').select2({
                                        placeholder: 'Pilih Divisi',
                                        allowClear: true
                                    });
                                });

                                $(document).ready(function() {
                                    $('#assign_requirement').select2({
                                        placeholder: 'Pilih Assign Recruiter',
                                        allowClear: true
                                    });
                                });

                                $(document).ready(function() {
                                    $('#posisi').select2({
                                        placeholder: 'Pilih Posisi',
                                        allowClear: true
                                    });
                                });

                                $(document).ready(function() {
                                    $('#internal').select2({
                                        placeholder: 'Pilih Internal',
                                        allowClear: true
                                    });
                                });

                                $(document).ready(function() {
                                    $('#reason_penggantian').select2({
                                        placeholder: 'Nama Karyawan Penggantian',
                                        allowClear: true
                                    });
                                });

                                $(document).ready(function() {
                                    $('#mpp').select2({
                                        placeholder: 'Pilih Mpp',
                                        allowClear: true
                                    });
                                });
                                $(document).ready(function() {
                                    $('#project_code').select2({
                                        placeholder: 'Pilih Project Code',
                                        allowClear: true
                                    });
                                });
                                $(document).ready(function() {
                                    $('#jenis_kelamin').select2({
                                        placeholder: 'Pilih Jenis Kelamin',
                                        allowClear: true
                                    });
                                });

                                $(document).ready(function() {
                                    $('#kandidat').select2({
                                        placeholder: 'Pilih Kandidat',
                                        allowClear: true
                                    });
                                });

                                // $(document).ready(function() {
                                //     $('#status_karyawan').select2({
                                //         placeholder: 'Pilih Status Karyawan',
                                //         allowClear: true
                                //     });
                                // });

                                $(document).ready(function() {
                                    $('#pendidikan_minimal').select2({
                                        placeholder: 'Pilih Pendidikan Minimal',
                                        allowClear: true
                                    });
                                });

                                $(document).ready(function() {
                                    $('#periode_project').select2({
                                        placeholder: 'Project Berapa Lama',
                                        allowClear: true
                                    });
                                });
                            </script>

                            <script>
                                $(document).ready(function() {
                                    $('#status_rekrutmen').select2({
                                        placeholder: 'Pilih Status Rekrutmen',
                                        allowClear: true
                                    }).on('change', function() {
                                        const approvalBod = document.getElementById('approval-bod-section');
                                        const reasonRow = document.getElementById('reason-row');

                                        // Mengatur tampilan Approval BOD berdasarkan Penambahan
                                        if (this.value === 'Penambahan') {
                                            approvalBod.style.display = 'block'; // Tampilkan Approval BOD
                                        } else {
                                            approvalBod.style.display = 'none'; // Sembunyikan Approval BOD
                                        }

                                        // Mengatur tampilan Reason berdasarkan Penggantian
                                        if (this.value === 'Penggantian') {
                                            reasonRow.style.display = 'flex'; // Tampilkan Reason
                                        } else {
                                            reasonRow.style.display = 'none'; // Sembunyikan Reason
                                        }
                                    });
                                });
                            </script>

                            <script>
                                $(document).ready(function() {
                                    $('#status_karyawan').select2({
                                        placeholder: 'Pilih Status Karyawan',
                                        allowClear: true
                                    }).on('change', function() {
                                        const reasonRow = document.getElementById('kontrak-row');

                                        // Mengatur tampilan Reason berdasarkan Penggantian
                                        if (this.value === 'Kontrak') {
                                            reasonRow.style.display = 'flex'; // Tampilkan Reason
                                        } else {
                                            reasonRow.style.display = 'none'; // Sembunyikan Reason
                                        }
                                    });
                                });
                            </script>


                            <script>
                                $(document).ready(function() {
                                    $('#kandidat').select2({
                                        placeholder: 'Pilih Status kandidat',
                                        allowClear: true
                                    }).on('change', function() {
                                        const approvalBod = document.getElementById('Internal-section');
                                        if (this.value === 'Internal') {
                                            approvalBod.style.visibility = 'visible';
                                        } else {
                                            approvalBod.style.visibility = 'hidden';
                                        }
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
                                    var maxInterviewUsers = 10;

                                    // Function to add new email section
                                    $("#addemail").click(function() {
                                        var html = `
            <div class="row mb-3" id="inputemail">
                <div class="col-lg-6">
                    <div class="row mb-3">
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Nama Kandidat</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control form-control-sm" id="nama_kandidat[]" name="nama_kandidat[]">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Email Kandidat</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control form-control-sm" id="email[]" name="email[]">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Tanggal Interview</label>
                        <div class="col-sm-9">
                            <input type="datetime-local" class="form-control form-control-sm" id="tanggal_interview[]" name="tanggal_interview[]">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Link Baru</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control form-control-sm" id="link_baru[]" name="link_baru[]">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">File CV</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="File_CV[]" id="File_CV[]" required>
                                <option>- Pilih CV -</option>
                                <?php
                                $filecv = $DBHCM->get_sqlV2("SELECT * FROM sa_hcm_notecv WHERE status_cv = 'Yes' AND id_fpkb = '" . $_GET['id_fpkb'] . "'");
                                ?>
                                <?php while ($row = $filecv[1]->fetch_assoc()) { ?>
                                    <option value="https://drive.google.com/file/d/<?php echo $row['id_filedrive']; ?>/view">
                                        <?php echo $row['file']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Action</label>
                        <div class="col-sm-9">
                            <button id="removeRow-affected-ci" type="button" class="btn btn-danger">Remove</button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row mb-3">
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">PIC (Recruiter)</label>
                        <div class="col-sm-9">
                        <?php
                        $namapic = $DBHCM->get_sqlV2("SELECT * FROM sa_hcm_requirement WHERE id = '" . $_GET['id'] . "'");
                        ?>
                            <input type="text" class="form-control form-control-sm" id="pic[]" name="pic[]" value="<?php echo $namapic[0]['assign_requirement']; ?>" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">HCM </label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control form-control-sm" id="interview_hcm[]" name="interview_hcm[]" value="Recruitment <recruitment.team@mastersystem.co.id>" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Interview User</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="interview_user[]" id="interview_user[]" required>
                            <option value="">-- Pilih Interview --</option>
                                <?php
                                $namainterviewuser = $DBHCM->get_sqlV2("select DISTINCT employee_name, employee_email from sa_view_employees_v2 where resign_date is null ORDER BY employee_name ASC");
                                ?>
                                <option value="malik aulia wiratama <malik.aulia@mastersystem.co.id>">malik aulia wiratama <malik.aulia@mastersystem.co.id></option>
                                <?php while ($row = $namainterviewuser[1]->fetch_assoc()) { ?>
                                    <option value="<?php echo $row['employee_email']; ?>">
                                        <?php echo $row['employee_name'] . " (" . $row['employee_email'] . ")"; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-9">
                            <div class="new-interview-user-container"></div>
                            <button id="addnewinterviewuser" type="button" class="btn btn-primary">Add New Interview User</button>
                        </div>
                    </div>
            </div>`;
                                        $('#newRow-affected-ci').append(html);
                                    });

                                    // Function to add new interview user
                                    $(document).on('click', '#addnewinterviewuser', function() {
                                        var currentInterviewUsers = $(this).closest('.row').find('.new-interview-user-container .new-interview-user-row').length;
                                        if (currentInterviewUsers < maxInterviewUsers) {
                                            var html = `
            <div class="row mb-3 new-interview-user-row">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">New Interview User</label>
                <div class="col-sm-9">
                    <select class="form-control" name="interview_user[]" id="interview_user[]" required>
                    <option value="">-- Pilih Interview --</option>
                        <?php
                        $namainterviewuser = $DBHCM->get_sqlV2("select DISTINCT employee_name, employee_email from sa_view_employees_v2 where resign_date is null ORDER BY employee_name ASC");
                        ?>
                        <option value="malik aulia wiratama <malik.aulia@mastersystem.co.id>">malik aulia wiratama <malik.aulia@mastersystem.co.id></option>
                        <?php while ($row = $namainterviewuser[1]->fetch_assoc()) { ?>
                            <option value="<?php echo $row['employee_email']; ?>">
                                <?php echo $row['employee_name'] . " (" . $row['employee_email'] . ")"; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-sm-3"></div>
                <div class="col-sm-9">
                    <button class="btn btn-danger remove-new-interview-user" type="button">Remove</button>
                </div>
            </div>`;
                                            $(this).closest('.row').find('.new-interview-user-container').append(html);
                                        } else {
                                            alert('Maksimal Interview user (10)');
                                        }
                                    });

                                    // Function to remove new interview user
                                    $(document).on('click', '.remove-new-interview-user', function() {
                                        $(this).closest('.new-interview-user-row').remove();
                                    });

                                    // Function to remove email section
                                    $(document).on('click', '#removeRow-affected-ci', function() {
                                        $(this).closest('#inputemail').remove();
                                    });
                                });
                            </script>