<?php
// if($_GET['act']=='view') {
//         global $DB;
//         $condition = "id=" . $_GET['id'];
//         $data = $DB->get_data($tblname, $condition);
//         $ddata = $data[0];
//         $qdata = $data[1];
//         $tdata = $data[2];
//     }
    // $mdlnameawwww = "HCM";
    // $DBNW = get_conn($mdlnameawwww);
    global $DBNR;
    $query = $DBNR->get_sql("SELECT * FROM sa_trx_request_requirement");
    $nariknamadivisi = $DBNR->get_data("view_department","organization_name IS not NULL");
    $nariknamaposisi = $DBNR->get_data("mst_jobtitle","job_title IS not NULL ORDER BY job_title ASC");
    // $deskripsi = $DBNRNW->get_data("mst_organization","description IS not NULL");
    $aproval_gm = $DBNR->get_data("view_employees_v2","resign_date IS NULL ORDER BY employee_name ASC");
    // $aproval_bod = $DBNRNW->get_data("view_employees","resign_date IS NULL ORDER BY employee_email ASC");
    // $aproval_gm_hcm = $DBNRNW->get_data("view_employees","resign_date IS NULL ORDER BY employee_email ASC");


    // bagian ADD
if($_GET['act']=='add') {
        global $DBNR;
        // $condition = "id=" . $_GET['id'];
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
                                                                <?php if ($_GET['act'] == "add") {?>
                                                                    <?php if(isset($_GET['divisi'])) {
                                                                        $namadivisi = $DBNR->get_data("view_department","organization_name = '" . $_GET['divisi'] ."'"); ?>
                                                                        <option value="<?php echo $namadivisi[0]['organization_name']; ?>"><?php echo $namadivisi[0]['organization_name']; ?></option>
                                                                    <?php } ?>
                                                                        <option></option>
                                                                        <?php do { ?>
                                                                            <option value="<?php echo $nariknamadivisi[0]['organization_name']; ?>"><?php echo $nariknamadivisi[0]['organization_name']; ?></option>
                                                                        <?php } while ($nariknamadivisi[0] = $nariknamadivisi[1]->fetch_assoc()); ?>
                                                                <?php }?>
                                                            </select>                   
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Posisi Yang Dibutuhkan <b>*</b></label>
                                                        <div class="col-sm-9">
                                                            <select class="form-control" name="posisi" id="posisi" >
                                                                <?php if ($_GET['act'] == "add") {?>
                                                                    <?php if(isset($_GET['posisi'])) {
                                                                        $namaposisi = $DBNR->get_data("mst_jobtitle","job_title IS not NULL AND job_title LIKE '%" . $_GET['posisi'] ."%'"); ?>
                                                                        <option value="<?php echo $namaposisi[0]['job_title']; ?>"><?php echo $namaposisi[0]['job_title']; ?></option>
                                                                    <?php } ?>
                                                                        <option></option>
                                                                    <?php do { ?>
                                                                    <option><?php echo $nariknamaposisi[0]['job_title']; ?></option>
                                                                    <?php } while ($nariknamaposisi[0] = $nariknamaposisi[1]->fetch_assoc()); ?>
                                                                <?php }?>
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
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Kode Project</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control form-control-sm" id="kode_project" name="kode_project">
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- KANAN -->
                                                <div class="col-lg-6">
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Rekrutmen <b>*</b></label>
                                                        <div class="col-sm-9">
                                                            <?php if ($_GET['act'] == "add") { ?>
                                                                <select class="form-control" name="status_rekrutmen" id="status_rekrutmen">
                                                                    <option></option>
                                                                    <option value="Penambahan">Penambahan</option>
                                                                    <option value="Penggantian">Penggantian</option>
                                                                </select>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Karyawan <b>*</b></label>
                                                        <div class="col-sm-9">
                                                            <?php if ($_GET['act'] == "add") { ?>
                                                                <select class="form-control" name="status_karyawan" id="status_karyawan" >
                                                                    <option></option>
                                                                    <option value="Kontrak">Kontrak</option>
                                                                    <option value="Percobaan">Percobaan</option>
                                                                    <option value="Tetap">Tetap</option>
                                                                    <option value="Outsourching">Outsourching</option>
                                                                    <option value="Magang">Magang</option>
                                                                </select>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Apakah Ada Di MPP <b>*</b></label>
                                                        <div class="col-sm-9">
                                                            <?php if ($_GET['act'] == "add") { ?>
                                                                <select class="form-control" name="mpp" id="mpp" >
                                                                    <option></option>
                                                                    <option value="Ya">Ya</option>
                                                                    <option value="Tidak">Tidak</option>
                                                                </select>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h2>Employee Requirements</h2>
                                                <div class="col-lg-6">
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Jenis Kelamin <b>*</b></label>
                                                        <div class="col-sm-9">
                                                            <?php if ($_GET['act'] == "add") { ?>
                                                                <select class="form-control" name="jenis_kelamin" id="jenis_kelamin" >
                                                                    <option></option>
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
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Pendidikan Minimal <b>*</b></label>
                                                        <div class="col-sm-9">
                                                            <?php if ($_GET['act'] == "add") { ?>
                                                                                <select class="form-control" name="pendidikan_minimal" id="pendidikan_minimal" >
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
                                                </div>
                                                <!-- KANAN -->
                                                <div class="col-lg-6">
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
                                                                <select class="form-control" name="kandidat" id="kandidat" >
                                                                    <option></option>
                                                                    <option value="Internal">Internal</option>
                                                                    <option value="Eksternal">Ekternal</option>
                                                                </select>
                                                            <?php } ?>
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
                            <?php }
                            // bagian edit request by (yg nge add)
                            else if($_GET['act']=='edit') {
                            global $DBNR;
                            $condition = "id=" . $_GET['id'];
                            $data = $DBNR->get_data($tblname, $condition);
                            $ddata = $data[0];
                            $qdata = $data[1];
                            $tdata = $data[2];
                            ?>
                        <?php if ($ddata['request_by'] == $_SESSION['Microservices_UserEmail']){ ?> 
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
                                                            <input type="text" class="form-control form-control-sm" id="id" name="id" value="<?php if($_GET['act']=='edit') { echo $ddata['id']; } ?>"readonly>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Divisi <b>*</b></label>
                                                        <div class="col-sm-9">
                                                            <select class="form-control" name="divisi" id="divisi">                      
                                                                <?php if ($_GET['act'] == "edit") {?>  
                                                                    <?php $query = $DBNR->get_sql("SELECT * FROM sa_trx_request_requirement where id=" . $ddata['id'] ."");?>
                                                                    <option value="<?php echo $query[0]['divisi']; ?>"readonly><?php echo $query[0]['divisi']; ?></option>
                                                                <?php } ?>
                                                            </select>                   
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Posisi Yang Dibutuhkan <b>*</b></label>
                                                        <div class="col-sm-9">
                                                            <select class="form-control" name="posisi" id="posisi" >
                                                                    <?php if ($_GET['act'] == "edit") {?>  
                                                                        <?php $query = $DBNR->get_sql("SELECT * FROM sa_trx_request_requirement where id=" . $ddata['id'] ."");?>
                                                                        <option value="<?php echo $query[0]['posisi']; ?>"readonly><?php echo $query[0]['posisi']; ?></option>
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
                                                            <input type="text" class="form-control form-control-sm" id="jumlah_dibutuhkan" name="jumlah_dibutuhkan" value="<?php if($_GET['act']=='edit') { echo $ddata['jumlah_dibutuhkan']; } ?>">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Tanggal Dibutuhkan</label>
                                                        <div class="col-sm-9">
                                                            <input type="date" class="form-control form-control-sm" id="tanggal_dibutuhkan" name="tanggal_dibutuhkan" value="<?php if($_GET['act']=='edit') { echo $ddata['tanggal_dibutuhkan']; } ?>">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Deskripsi</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control form-control-sm" id="deskripsi" name="deskripsi" value="<?php if($_GET['act']=='edit') { echo $ddata['deskripsi']; } ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <h2>Project Status</h2>
                                                <div class="col-lg-6">
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Nama Project</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control form-control-sm" id="nama_project" name="nama_project" value="<?php if($_GET['act']=='edit') { echo $ddata['nama_project']; } ?>">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Nama Customer</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control form-control-sm" id="nama_customer" name="nama_customer" value="<?php if($_GET['act']=='edit') { echo $ddata['nama_customer']; } ?>">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Kode Project</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control form-control-sm" id="kode_project" name="kode_project" value="<?php if($_GET['act']=='edit') { echo $ddata['kode_project']; } ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- KANAN -->
                                                <div class="col-lg-6">
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Rekrutmen <b>*</b></label>
                                                        <div class="col-sm-9">
                                                            <?php if ($_GET['act'] == "edit" && $ddata['status_rekrutmen'] == "Penambahan" || $_GET['act'] == "edit" && $ddata['status_rekrutmen'] == null) { ?>
                                                                <select class="form-control" name="status_rekrutmen" id="status_rekrutmen">
                                                                    <option><?php echo ucwords($ddata['status_rekrutmen']); ?></option>
                                                                    <option value="Penggantian">Penggantian</option>
                                                                </select>
                                                            <?php } else if ($_GET['act'] == "edit" && $ddata['status_rekrutmen'] == "Penggantian" || $_GET['act'] == "edit" && $ddata['status_rekrutmen'] == null) { ?>
                                                                <select class="form-control" name="status_rekrutmen" id="status_rekrutmen">
                                                                    <option><?php echo ucwords($ddata['status_rekrutmen']); ?></option>
                                                                    <option value="Penambahan">Penambahan</option>
                                                                </select>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Karyawan <b>*</b></label>
                                                        <div class="col-sm-9">
                                                            <?php if ($_GET['act'] == "edit" && $ddata['status_karyawan'] == "Kontrak" || $_GET['act'] == "edit" && $ddata['status_karyawan'] == null) { ?>
                                                                <select class="form-control" name="status_karyawan" id="status_karyawan">
                                                                    <option><?php echo ucwords($ddata['status_karyawan']); ?></option>
                                                                    <option value="Percobaan">Percobaan</option>
                                                                    <option value="Tetap">Tetap</option>
                                                                    <option value="Outsourching">Outsourching</option>
                                                                    <option value="Magang">Magang</option>
                                                                </select>
                                                            <?php } else if ($_GET['act'] == "edit" && $ddata['status_karyawan'] == "Percobaan" || $_GET['act'] == "edit" && $ddata['status_karyawan'] == null) { ?>
                                                                <select class="form-control" name="status_karyawan" id="status_karyawan">
                                                                    <option><?php echo ucwords($ddata['status_karyawan']); ?></option>
                                                                    <option value="Kontrak">Kontrak</option>
                                                                    <option value="Tetap">Tetap</option>
                                                                    <option value="Outsourching">Outsourching</option>
                                                                    <option value="Magang">Magang</option>
                                                                </select>
                                                            <?php } else if ($_GET['act'] == "edit" && $ddata['status_karyawan'] == "Tetap" || $_GET['act'] == "edit" && $ddata['status_karyawan'] == null) { ?>
                                                                <select class="form-control" name="status_karyawan" id="status_karyawan">
                                                                    <option><?php echo ucwords($ddata['status_karyawan']); ?></option>
                                                                    <option value="Kontrak">Kontrak</option>
                                                                    <option value="Percobaan">Percobaan</option>
                                                                    <option value="Outsourching">Outsourching</option>
                                                                    <option value="Magang">Magang</option>
                                                                </select>
                                                            <?php } else if ($_GET['act'] == "edit" && $ddata['status_karyawan'] == "Outsourching" || $_GET['act'] == "edit" && $ddata['status_karyawan'] == null) { ?>
                                                                <select class="form-control" name="status_karyawan" id="status_karyawan">
                                                                    <option><?php echo ucwords($ddata['status_karyawan']); ?></option>
                                                                    <option value="Kontrak">Kontrak</option>
                                                                    <option value="Percobaan">Percobaan</option>
                                                                    <option value="Tetap">Tetap</option>
                                                                    <option value="Magang">Magang</option>
                                                                </select>
                                                            <?php } else if ($_GET['act'] == "edit" && $ddata['status_karyawan'] == "Magang" || $_GET['act'] == "edit" && $ddata['status_karyawan'] == null) { ?>
                                                                <select class="form-control" name="status_karyawan" id="status_karyawan">
                                                                    <option><?php echo ucwords($ddata['status_karyawan']); ?></option>
                                                                    <option value="Kontrak">Kontrak</option>
                                                                    <option value="Percobaan">Percobaan</option>
                                                                    <option value="Tetap">Tetap</option>
                                                                    <option value="Outsourching">Outsourching</option>
                                                                </select>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Apakah Ada Di MPP <b>*</b></label>
                                                        <div class="col-sm-9">
                                                            <?php if ($_GET['act'] == "edit" && $ddata['mpp'] == "Tidak" || $_GET['act'] == "edit" && $ddata['mpp'] == null) { ?>
                                                                <select class="form-control" name="mpp" id="mpp">
                                                                    <option><?php echo ucwords($ddata['mpp']); ?></option>
                                                                    <option value="Ya">Ya</option>
                                                                </select>
                                                            <?php } else if ($_GET['act'] == "edit" && $ddata['mpp'] == "Ya" || $_GET['act'] == "edit" && $ddata['mpp'] == null) { ?>
                                                                <select class="form-control" name="mpp" id="mpp">
                                                                    <option><?php echo ucwords($ddata['mpp']); ?></option>\
                                                                    <option value="Tidak">Tidak</option>
                                                                </select>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h2>Employee Requirements</h2>
                                                <div class="col-lg-6">
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Jenis Kelamin <b>*</b></label>
                                                        <div class="col-sm-9">
                                                                <?php if ($_GET['act'] == "edit" && $ddata['jenis_kelamin'] == "Perempuan" || $_GET['act'] == "edit" && $ddata['jenis_kelamin'] == null) { ?>
                                                                <select class="form-control" name="jenis_kelamin" id="jenis_kelamin">
                                                                    <option><?php echo ucwords($ddata['jenis_kelamin']); ?></option>
                                                                    <option value="Laki-Laki">Laki-Laki</option>
                                                                </select>
                                                                <?php } else if ($_GET['act'] == "edit" && $ddata['jenis_kelamin'] == "Laki-Laki" || $_GET['act'] == "edit" && $ddata['jenis_kelamin'] == null) { ?>
                                                                <select class="form-control" name="jenis_kelamin" id="jenis_kelamin">
                                                                    <option><?php echo ucwords($ddata['jenis_kelamin']); ?></option>\
                                                                    <option value="Perempuan">Perempuan</option>
                                                                </select>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                                <div class="row mb-3">
                                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Usia</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control form-control-sm" id="usia" name="usia" value="<?php if($_GET['act']=='edit') { echo $ddata['usia']; } ?>">
                                                                    </div>
                                                                </div>
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Pendidikan Minimal <b>*</b></label>
                                                        <div class="col-sm-9">
                                                            <?php if ($_GET['act'] == "edit" && $ddata['pendidikan_minimal'] == "SMA (High School)" || $_GET['act'] == "edit" && $ddata['pendidikan_minimal'] == null) { ?>
                                                                                <select class="form-control" name="pendidikan_minimal" id="pendidikan_minimal">
                                                                                    <option><?php echo ucwords($ddata['pendidikan_minimal']); ?></option>
                                                                                    <option value="D3 (Diploma)">D3 (Diploma)</option>
                                                                                    <option value="S1 (Bachelor)">S1 (Bachelor)</option>
                                                                                    <option value="S2 (Master)">S2 (Master)</option>
                                                                                </select>
                                                            <?php } else if ($_GET['act'] == "edit" && $ddata['pendidikan_minimal'] == "D3 (Diploma)" || $_GET['act'] == "edit" && $ddata['pendidikan_minimal'] == null) { ?>
                                                                                <select class="form-control" name="pendidikan_minimal" id="pendidikan_minimal">
                                                                                    <option><?php echo ucwords($ddata['pendidikan_minimal']); ?></option>
                                                                                    <option value="SMA (High School)">SMA (High School)</option>
                                                                                    <option value="S1 (Bachelor)">S1 (Bachelor)</option>
                                                                                    <option value="S2 (Master)">S2 (Master)</option>
                                                                                </select>
                                                            <?php } else if ($_GET['act'] == "edit" && $ddata['pendidikan_minimal'] == "S1 (Bachelor)" || $_GET['act'] == "edit" && $ddata['pendidikan_minimal'] == null) { ?>
                                                                                <select class="form-control" name="pendidikan_minimal" id="pendidikan_minimal">
                                                                                    <option><?php echo ucwords($ddata['pendidikan_minimal']); ?></option>
                                                                                    <option value="SMA (High School)">SMA (High School)</option>
                                                                                    <option value="D3 (Diploma)">D3 (Diploma)</option>
                                                                                    <option value="S2 (Master)">S2 (Master)</option>
                                                                                </select>
                                                            <?php } else if ($_GET['act'] == "edit" && $ddata['pendidikan_minimal'] == "S2 (Master)" || $_GET['act'] == "edit" && $ddata['pendidikan_minimal'] == null) { ?>
                                                                                <select class="form-control" name="pendidikan_minimal" id="pendidikan_minimal">
                                                                                    <option><?php echo ucwords($ddata['pendidikan_minimal']); ?></option>
                                                                                    <option value="SMA (High School)">SMA (High School)</option>
                                                                                    <option value="D3 (Diploma)">D3 (Diploma)</option>
                                                                                    <option value="S1 (Bachelor)">S1 (Bachelor)</option>
                                                                                </select>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Fakultas/Jurusan</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control form-control-sm" id="jurusan" name="jurusan" value="<?php if($_GET['act']=='edit') { echo $ddata['jurusan']; } ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- KANAN -->
                                                <div class="col-lg-6">
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Pengalaman Minimal</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control form-control-sm" id="pengalaman_minimal" name="pengalaman_minimal" value="<?php if($_GET['act']=='edit') { echo $ddata['pengalaman_minimal']; } ?>">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Kompetensi Teknis Yang Harus Dikuasai</label>
                                                        <div class="col-sm-9">
                                                            <textarea type="text" class="form-control" id="kompetensi_teknis" name="kompetensi_teknis" rows="2"><?php if($_GET['act']=='edit') { echo $ddata['kompetensi_teknis']; } ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Kompetensi Non Teknis Yang Harus Dikuasai</label>
                                                        <div class="col-sm-9">
                                                            <textarea type="text" class="form-control" id="kompetensi_non_teknis" name="kompetensi_non_teknis" rows="2"><?php if($_GET['act']=='edit') { echo $ddata['kompetensi_non_teknis']; } ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h2>Offering</h2>
                                                <div class="col-lg-6">
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Kandidat <b>*</b></label>
                                                        <div class="col-sm-9">
                                                            <?php if ($_GET['act'] == "edit" && $ddata['kandidat'] == "Internal" || $_GET['act'] == "edit" && $ddata['kandidat'] == null) { ?>
                                                                <select class="form-control" name="kandidat" id="kandidat" >
                                                                    <option><?php echo ucwords($ddata['kandidat']); ?></option>
                                                                    <option value="Eksternal">Ekternal</option>
                                                                </select>
                                                            <?php } else if ($_GET['act'] == "edit" && $ddata['kandidat'] == "Eksternal" || $_GET['act'] == "edit" && $ddata['kandidat'] == null) { ?>
                                                                <select class="form-control" name="kandidat" id="kandidat" >
                                                                    <option><?php echo ucwords($ddata['kandidat']); ?></option>
                                                                    <option value="Internal">Internal</option>
                                                                </select>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan</label>
                                                        <div class="col-sm-9">
                                                            <textarea type="text" class="form-control form-control-sm" id="catatan" name="catatan" rows="2"><?php if($_GET['act']=='edit') { echo $ddata['catatan']; } ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                    <!-- KANAN -->
                                                <div class="col-lg-6">
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Diisi Oleh HCM </label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control form-control-sm" id="diisi_oleh_hcm" name="diisi_oleh_hcm" value="<?php if($_GET['act']=='edit') { echo $ddata['diisi_oleh_hcm']; } ?>">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Range Salary <b>*</b></label>
                                                        <div class="col-sm-9">
                                                            <input type="number" class="form-control form-control-sm" id="range_salary" name="range_salary" value="<?php if($_GET['act']=='edit') { echo $ddata['range_salary']; } ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- bagian approve (hanya bisa GM / GM HCM) -->

                            <?php } else if($ddata['gm_hcm'] == $_SESSION['Microservices_UserEmail'] || $ddata['gm'] == $_SESSION['Microservices_UserEmail']){?>
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
                                                            <input type="text" class="form-control form-control-sm" id="id" name="id" value="<?php if($_GET['act']=='edit') { echo $ddata['id']; } ?>"readonly>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Divisi <b>*</b></label>
                                                        <div class="col-sm-9">
                                                            <select class="form-control" name="divisi" id="divisi" readonly>
                                                                    <option value="<?php echo $query[0]['divisi']; ?>"readonly><?php echo $query[0]['divisi']; ?></option>
                                                            </select>                   
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Posisi Yang Dibutuhkan <b>*</b></label>
                                                        <div class="col-sm-9">
                                                            <select class="form-control" name="posisi" id="posisi" readonly>
                                                                        <option value="<?php echo $query[0]['posisi']; ?>"readonly><?php echo $query[0]['posisi']; ?></option>
                                                            </select>                     
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- KANAN -->
                                                <div class="col-lg-6">
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Jumlah Dibutuhkan</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control form-control-sm" id="jumlah_dibutuhkan" name="jumlah_dibutuhkan" value="<?php if($_GET['act']=='edit') { echo $ddata['jumlah_dibutuhkan']; } ?>"readonly>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Tanggal Dibutuhkan</label>
                                                        <div class="col-sm-9">
                                                            <input type="date" class="form-control form-control-sm" id="tanggal_dibutuhkan" name="tanggal_dibutuhkan" value="<?php if($_GET['act']=='edit') { echo $ddata['tanggal_dibutuhkan']; } ?>"readonly>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Deskripsi</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control form-control-sm" id="deskripsi" name="deskripsi" value="<?php if($_GET['act']=='edit') { echo $ddata['deskripsi']; } ?>"readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h2>Project Status</h2>
                                                <div class="col-lg-6">
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Nama Project</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control form-control-sm" id="nama_project" name="nama_project" value="<?php if($_GET['act']=='edit') { echo $ddata['nama_project']; } ?>"readonly>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Nama Customer</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control form-control-sm" id="nama_customer" name="nama_customer" value="<?php if($_GET['act']=='edit') { echo $ddata['nama_customer']; } ?>"readonly>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Kode Project</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control form-control-sm" id="kode_project" name="kode_project" value="<?php if($_GET['act']=='edit') { echo $ddata['kode_project']; } ?>"readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- KANAN -->
                                                <div class="col-lg-6">
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Rekrutmen <b>*</b></label>
                                                        <div class="col-sm-9">
                                                            <select class="form-control" name="status_rekrutmen" id="status_rekrutmen"readonly>
                                                                    <option><?php echo ucwords($ddata['status_rekrutmen']); ?></option>
                                                                </select>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Karyawan <b>*</b></label>
                                                        <div class="col-sm-9">
                                                                <select class="form-control" name="status_karyawan" id="status_karyawan"readonly>
                                                                    <option><?php echo ucwords($ddata['status_karyawan']); ?></option>
                                                                </select>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Apakah Ada Di MPP <b>*</b></label>
                                                        <div class="col-sm-9">
                                                                <select class="form-control" name="mpp" id="mpp"readonly>
                                                                    <option><?php echo ucwords($ddata['mpp']); ?></option>
                                                                </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h2>Employee Requirements</h2>
                                                <div class="col-lg-6">
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Jenis Kelamin <b>*</b></label>
                                                        <div class="col-sm-9">
                                                            <select class="form-control" name="jenis_kelamin" id="jenis_kelamin"readonly>
                                                                    <option><?php echo ucwords($ddata['jenis_kelamin']); ?></option>
                                                                    <option value="Laki-Laki">Laki-Laki</option>
                                                                </select>
                                                        </div>
                                                    </div>
                                                                <div class="row mb-3">
                                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Usia</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control form-control-sm" id="usia" name="usia" value="<?php if($_GET['act']=='edit') { echo $ddata['usia']; } ?>"readonly>
                                                                    </div>
                                                                </div>
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Pendidikan Minimal <b>*</b></label>
                                                        <div class="col-sm-9">
                                                            <select class="form-control" name="pendidikan_minimal" id="pendidikan_minimal"readonly>
                                                                                    <option><?php echo ucwords($ddata['pendidikan_minimal']); ?></option>
                                                                                </select>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Fakultas/Jurusan</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control form-control-sm" id="jurusan" name="jurusan" value="<?php if($_GET['act']=='edit') { echo $ddata['jurusan']; } ?>"readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- KANAN -->
                                                <div class="col-lg-6">
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Pengalaman Minimal</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control form-control-sm" id="pengalaman_minimal" name="pengalaman_minimal" value="<?php if($_GET['act']=='edit') { echo $ddata['pengalaman_minimal']; } ?>"readonly>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Kompetensi Teknis Yang Harus Dikuasai</label>
                                                        <div class="col-sm-9">
                                                            <textarea type="text" class="form-control" id="kompetensi_teknis" name="kompetensi_teknis" rows="2"readonly><?php if($_GET['act']=='edit') { echo $ddata['kompetensi_teknis']; } ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Kompetensi Non Teknis Yang Harus Dikuasai</label>
                                                        <div class="col-sm-9">
                                                            <textarea type="text" class="form-control" id="kompetensi_non_teknis" name="kompetensi_non_teknis" rows="2"readonly><?php if($_GET['act']=='edit') { echo $ddata['kompetensi_non_teknis']; } ?></textarea>
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
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Catatan</label>
                                                        <div class="col-sm-9">
                                                            <textarea type="text" class="form-control form-control-sm" id="catatan" name="catatan" rows="2"readonly><?php if($_GET['act']=='edit') { echo $ddata['catatan']; } ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                    <!-- KANAN -->
                                                <div class="col-lg-6">
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Diisi Oleh HCM </label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control form-control-sm" id="diisi_oleh_hcm" name="diisi_oleh_hcm" value="<?php if($_GET['act']=='edit') { echo $ddata['diisi_oleh_hcm']; } ?>"readonly>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Range Salary <b>*</b></label>
                                                        <div class="col-sm-9">
                                                            <input type="number" class="form-control form-control-sm" id="range_salary" name="range_salary" value="<?php if($_GET['act']=='edit') { echo $ddata['range_salary']; } ?>"readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php }?>
                            <?php }?>
                                        <div class="card shadow mb-4">
                                            <div class="card-body">
                                                <h2>Employee Requirement</h2>
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Approval GM <b>*</b></label>
                                                                <div class="col-sm-9">
                                                                    <select class="form-control" name="gm" id="gm">
                                                                        <?php if ($_GET['act'] == "add") {?>
                                                                            <?php if(isset($_GET['divisi'])) {
                                                                                $approv_gm = $DBNR->get_data("view_employees","resign_date IS NULL AND job_level=2"); ?>
                                                                                <option></option>
                                                                                <option value="malik.aulia@mastersystem.co.id">malik.aulia@mastersystem.co.id</option>
                                                                                <?php do {?>
                                                                                <option value="<?php echo $approv_gm[0]['employee_email']; ?>"><?php echo $approv_gm[0]['employee_name'] . " (". $approv_gm[0]['organization_name']. ")"; ?></option>
                                                                                <?php }while($approv_gm[0] = $approv_gm[1]->fetch_assoc()); ?> 
                                                                                <?php } else {?>
                                                                                <option></option>
                                                                        <?php }
                                                                        }?>
                                                                            <?php if ($_GET['act'] == "edit") {?>  
                                                                                <?php $query = $DBNR->get_sql("SELECT * FROM sa_trx_request_requirement where id=" . $ddata['id'] ."");?>
                                                                                <option value="<?php echo $query[0]['gm']; ?>"readonly><?php echo $query[0]['gm']; ?></option>
                                                                            <?php } ?>
                                                                    </select>                    
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status GM <b>*</b></label>
                                                                <div class="col-sm-9">
                                                                    <?php if ($_GET['act'] == "add") { ?>
                                                                        <select class="form-control" name="status_gm" id="status_gm" >
                                                                            <option></option>
                                                                            <option value="Pending">Pending</option>
                                                                        </select>
                                                                    <?php } ?>
                                                                    <?php if ($_GET['act'] == "edit" && $ddata['status_gm'] == "Pending" || $_GET['act'] == "edit" && $ddata['status_gm'] == null) { ?>
                                                                        <?php if ($ddata['gm'] == $_SESSION['Microservices_UserEmail']){ ?>
                                                                            <!-- approve hanya bisa GM -->
                                                                        <select class="form-control" name="status_gm" id="status_gm" >
                                                                            <option><?php echo ucwords($ddata['status_gm']); ?></option>
                                                                            <option value="Approve">Approve</option>
                                                                            <option value="Disapprove">Disapprove</option>
                                                                        </select>
                                                                        <?php }?>
                                                                    <?php } elseif ($_GET['act'] == "edit" && $ddata['status_gm'] == "Approve" || $_GET['act'] == "edit" && $ddata['status_gm'] == null) { ?>
                                                                        <select class="form-control" name="status_gm" id="status_gm" >
                                                                            <option><?php echo ucwords($ddata['status_gm']); ?></option>
                                                                        </select>
                                                                    <?php } elseif ($_GET['act'] == "edit" && $ddata['status_gm'] == "Disapprove" || $_GET['act'] == "edit" && $ddata['status_gm'] == null) { ?>
                                                                        <select class="form-control" name="status_gm" id="status_gm" >
                                                                            <option><?php echo ucwords($ddata['status_gm']); ?></option>
                                                                        </select>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Approval GM HCM <b>*</b></label>
                                                                <div class="col-sm-9">
                                                                    <select class="form-control" name="gm_hcm" id="gm_hcm">
                                                                        <?php if ($_GET['act'] == "add") {?>
                                                                            <?php if(isset($_GET['divisi'])) {
                                                                                $approv_gm_hcm = $DBNR->get_data("view_employees"," `organization_name` LIKE '%human capital management%' AND `job_level` = 2 AND `resign_date` IS NULL"); ?>
                                                                                <option></option>
                                                                                <option value="malik.aulia@mastersystem.co.id">malik.aulia@mastersystem.co.id</option>
                                                                                <?php do {?>
                                                                                <option value="<?php echo $approv_gm_hcm[0]['employee_email']; ?>"><?php echo $approv_gm_hcm[0]['employee_name'] . " (". $approv_gm_hcm[0]['organization_name']. ")"; ?></option>
                                                                                <?php }while($approv_gm_hcm[0] = $approv_gm_hcm[1]->fetch_assoc()); ?> 
                                                                                <?php } else {?>
                                                                                <option></option>
                                                                        <?php }
                                                                        }?>
                                                                            <?php if ($_GET['act'] == "edit") {?>  
                                                                                <?php $query = $DBNR->get_sql("SELECT * FROM sa_trx_request_requirement where id=" . $ddata['id'] ."");?>
                                                                                <option value="<?php echo $query[0]['gm_hcm']; ?>"><?php echo $query[0]['gm_hcm']; ?></option>
                                                                            <?php } ?>
                                                                    </select>                    
                                                                </div>
                                                            </div>
                                                    <div class="row mb-3">
                                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status GM <b>*</b></label>
                                                        <div class="col-sm-9">
                                                            <?php if ($_GET['act'] == "add") { ?>
                                                                <select class="form-control" name="status_gm_hcm" id="status_gm_hcm" >
                                                                    <option></option>
                                                                    <option value="Pending">Pending</option>
                                                                </select>
                                                            <?php } ?>
                                                            <?php if ($_GET['act'] == "edit" && $ddata['status_gm_hcm'] == "Pending" || $_GET['act'] == "edit" && $ddata['status_gm_hcm'] == null) { ?>
                                                                <?php if ($ddata['gm_hcm'] == $_SESSION['Microservices_UserEmail']){ ?>
                                                                    <!-- approve hanya bisa GM HCM -->
                                                                <select class="form-control" name="status_gm_hcm" id="status_gm_hcm" >
                                                                    <option><?php echo ucwords($ddata['status_gm_hcm']); ?></option>
                                                                    <option value="Approve">Approve</option>
                                                                    <option value="Disapprove">Disapprove</option>
                                                                </select>
                                                                <?php }?>
                                                            <?php } elseif ($_GET['act'] == "edit" && $ddata['status_gm_hcm'] == "Approve" || $_GET['act'] == "edit" && $ddata['status_gm_hcm'] == null) { ?>
                                                                <select class="form-control" name="status_gm_hcm" id="status_gm_hcm" >
                                                                    <option><?php echo ucwords($ddata['status_gm_hcm']); ?></option>
                                                                </select>
                                                            <?php } elseif ($_GET['act'] == "edit" && $ddata['status_gm_hcm'] == "Disapprove" || $_GET['act'] == "edit" && $ddata['status_gm_hcm'] == null) { ?>
                                                                <select class="form-control" name="status_gm_hcm" id="status_gm_hcm" readonly>
                                                                    <option><?php echo ucwords($ddata['status_gm_hcm']); ?></option>
                                                                </select>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                        <input type="submit" class="btn btn-secondary" name="cancel" value="Cancel">
                        <?php if(isset($_GET['act']) && $_GET['act']=='edit') { ?>
                            <input type="submit" class="btn btn-primary" name="edit" value="Save">
                        <?php } elseif(isset($_GET['act']) && $_GET['act']=='add') { ?>
                            <input type="submit" class="btn btn-primary" name="add" value="Save">
                        <?php } elseif(isset($_GET['act']) && $_GET['act']=='approval') { ?>
                            <input type="submit" class="btn btn-primary" name="approval" value="Save">
                        <?php } ?>
                    </form>
    <script>
	$("#divisi").on('change', function() {
		var divisi = $(this).val();
		
			window.location = window.location.pathname + "?mod=new_request" + "&act=add&divisi="+ divisi;
	})
</script>   