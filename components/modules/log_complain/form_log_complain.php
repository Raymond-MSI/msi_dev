<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
<?php
global $DBKPILog;
if (
    $_GET['act'] == 'edit'
) {
    $kondisi = "project_code=" . "\"" . $_GET['ProjectCode'] . "\"";

    $data_result = $DBKPILog->get_data("complain_result", $kondisi);
    // var_dump($data_result);
    // die;
    // var_dump($kondisi);
}
if ($_GET['act'] == 'add') {
    $DBSB = get_conn("SERVICE_BUDGET");

    $DBWRIKE = get_conn("WRIKE_INTEGRATE");

    $get_kp = $DBSB->get_sql("SELECT * FROM sa_trx_project_list WHERE bundling not like '%0;0;0%' AND project_code IS NOT NULL AND so_number IS NOT NULL ORDER BY project_code ASC");
    $get_ordernumber = $DBSB->get_sql(" SELECT * FROM sa_trx_project_list WHERE order_number IS NOT NULL ORDER BY order_number ASC");

    // $get_project_leader = $DBWRIKE->get_sql("SELECT DISTINCT project_leader FROM sa_wrike_project_list WHERE project_leader IS NOT NULL ");
?>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active text-body" id="addkp-tab" data-bs-toggle="tab" data-bs-target="#addkp" type="button" role="tab" aria-controls="addkp" aria-selected="true">General</button>
        </li>
        <!-- <li class="nav-item" role="presentation">
            <button class="nav-link text-body" id="addresult-tab" data-bs-toggle="tab" data-bs-target="#add_result" type="button" role="tab" aria-controls="add_result" aria-selected="false">Resolution</button>
        </li> -->
        <li class="nav-item" role="presentation">
            <button class="nav-link text-body" id="addfile-tab" data-bs-toggle="tab" data-bs-target="#addfile" type="button" role="tab" aria-controls="addfile" aria-selected="false">File</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link text-body" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab" aria-controls="history" aria-selected="false">History</button>
        </li>
    </ul>
    <form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="addkp" role="tabpanel" aria-labelledby="addkp-tab">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row mb-3" hidden>
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Complain Id</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control form-control-sm" id="complain_id" name="complain_id" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                                echo $ddata['complain_id'];
                                                                                                                                            } ?>">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Code</label>
                                    <div class="col-sm-9">
                                        <?php if ($_GET['act'] == "add") { ?>
                                            <select class="form-control" name="project_code" id="project_code">
                                                <?php if (isset($_GET['project_code'])) {
                                                    $project_code = $DBSB->get_data("trx_project_list", "project_code LIKE '%" . $_GET['project_code'] . "%'"); ?>
                                                    <option value="<?php echo $project_code[0]['project_code']; ?>"><?php echo $project_code[0]['project_code']; ?></option>
                                                <?php } ?>
                                                <?php do { ?>
                                                    <option></option>
                                                    <option value="<?php echo $get_kp[0]['project_code']; ?>"><?php echo $get_kp[0]['project_code']; ?></option>
                                                <?php } while ($get_kp[0] = $get_kp[1]->fetch_assoc()); ?>
                                            </select>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">SO Number</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="so_number" id="so_number">
                                            <?php if ($_GET['act'] == "add" && isset($_GET['project_code'])) {
                                                $so_number = $DBSB->get_data("trx_project_list", "project_code LIKE '%" . $_GET['project_code'] . "%' AND so_number LIKE '%" . $_GET['so_number'] .  "%'");
                                                $so_number2 = $DBSB->get_data("trx_project_list", "project_code LIKE '%" . $_GET['project_code'] . "%'"); ?>
                                                <option value="<?php echo $so_number[0]['so_number']; ?>"><?php echo $so_number[0]['so_number']; ?></option>
                                                <?php do { ?>
                                                    <option value="<?php echo $so_number2[0]['so_number']; ?>"><?php echo $so_number2[0]['so_number']; ?></option>
                                                <?php } while ($so_number2[0] = $so_number2[1]->fetch_assoc()); ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Order Number</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="order_number" id="order_number" readonly>
                                            <?php if ($_GET['act'] == "add" && isset($_GET['project_code'])) {
                                                $order_number = $DBSB->get_data("trx_project_list", "project_code LIKE '%" . $_GET['project_code'] . "%' AND so_number LIKE '%" . $_GET['so_number'] .  "%'"); ?>
                                                <?php do { ?>
                                                    <option value="<?php echo $order_number[0]['order_number']; ?>"><?php echo $order_number[0]['order_number']; ?></option>
                                                <?php } while ($order_number[0] = $order_number[1]->fetch_assoc()); ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Name</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="project_name" id="project_name" readonly>
                                            <?php if ($_GET['act'] == "add" && isset($_GET['project_code'])) {
                                                $project_name = $DBSB->get_data("trx_project_list", "project_code LIKE '%" . $_GET['project_code'] . "%' AND so_number LIKE '%" . $_GET['so_number'] .  "%'"); ?>
                                                <?php do { ?>
                                                    <option value="<?php echo $project_name[0]['project_name_internal']; ?>"><?php echo $project_name[0]['project_name_internal']; ?></option>
                                                <?php } while ($project_name[0] = $project_name[1]->fetch_assoc()); ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Leader</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="project_leader" id="project_leader" readonly>
                                            <?php if ($_GET['act'] == "add" && isset($_GET['project_code'])) {
                                                if (!empty($project_leader)) { ?>
                                                    <option value="<?php echo $project_leader[0]['project_leader']; ?>"><?php echo $project_leader[0]['project_leader']; ?></option>
                                                <?php } else { ?>
                                                    <option value="">Tidak ada project leader</option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Customer</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="customer" id="customer" readonly>
                                            <?php if ($_GET['act'] == "add" && isset($_GET['project_code'])) {
                                                $get_customer = $DBSB->get_data("trx_project_list", "project_code LIKE '%" . $_GET['project_code'] . "%' AND so_number LIKE '%" . $_GET['so_number'] .  "%'"); ?>
                                                <option value="<?php echo $get_customer[0]['customer_name']; ?>"><?php echo $get_customer[0]['customer_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">PIC Customer</label>
                                    <div class="col-sm-9">
                                        <?php if ($_GET['act'] == "add") { ?>
                                            <input type="text" class="form-control form-control-sm" id="pic_customer" name="pic_customer">
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Category</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="category" id="category">
                                            <?php if ($_GET['act'] == "add") { ?>
                                                <option></option>
                                                <option value="Project - Internal">Project - Internal</option>
                                                <option value="Project - Eksternal">Project - Eksternal</option>
                                                <option value="Personal - Internal">Personal - Internal</option>
                                                <option value="Personal - Eksternal">Personal - Eksternal</option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Type</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="type_project" id="type_project">
                                            <?php if ($_GET['act'] == "add") { ?>
                                                <?php if ($get_kp[0]['bundling'] != '0') { ?>
                                                    <option></option>
                                                    <option value="Maintenance">Maintenance</option>
                                                    <option value="Implementation">Implementation</option>
                                                <?php } else {
                                                } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Tittle</label>
                                    <div class="col-sm-9">
                                        <?php if ($_GET['act'] == "add") { ?>
                                            <input type="text" class="form-control form-control-sm" id="tittle" name="tittle">
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Complain</label>
                                    <div class="col-sm-9">
                                        <?php if ($_GET['act'] == "add") {
                                        ?>
                                            <input type="text" class="form-control form-control-sm" id="complain" name="complain">
                                        <?php }
                                        ?>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Action Plan</label>
                                    <div class="col-sm-9">
                                        <?php if ($_GET['act'] == "add") {
                                        ?>
                                            <textarea class="form-control form-control-sm" name="action_plan" id="action_plan" cols="30" rows="2" style="height: 180px;"></textarea>
                                        <?php }
                                        ?>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Tanggal</label>
                                    <div class="col-sm-9">
                                        <?php if ($_GET['act'] == "add") { ?>
                                            <input type="date" class="form-control form-control-sm" id="tanggal" name="tanggal">
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="status" id="status" readonly>
                                            <?php if ($_GET['act'] == "add") { ?>
                                                <option value="Open">Open</option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="add_result" role="tabpanel" aria-labelledby="addresult-tab">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="row">
                            <h2>Result</h2>
                            <?php if ($_GET['act'] == 'add') { ?>
                                <?php $get_kp = isset($data_result[0]['project_code']) ?>
                                <?php if ($get_kp == null) { ?>
                                <?php } else { ?>
                                    <table style="width:100%" border="1">
                                        <tr>
                                            <th class="text-center align-middle">Result</th>
                                            <th class="text-center align-middle">Solution</th>
                                        </tr>
                                        <?php do { ?>
                                            <tr>
                                                <td>
                                                    <textarea class="form-control form-control-sm" name="result_edit[]" id="result_edit[]" rows="2" readonly><?php echo $data_result[0]['result']; ?></textarea>
                                                </td>
                                                <td>
                                                    <textarea class="form-control form-control-sm" name="solution_edit[]" id="solution_edit[]" rows="2"><?php echo $data_result[0]['solution']; ?></textarea>

                                                </td>
                                            </tr>
                                        <?php } while ($data_result[0] = $data_result[1]->fetch_assoc()); ?>
                                    </table>
                                <?php } ?>
                                <!-- </div> -->
                            <?php } ?>
                        </div>
                        <div class="row mb-3">
                            <div id="newrowresult"></div>
                            <button id="addrowresult" type="button" class="btn btn-info col-12">+</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="addfile" role="tabpanel" aria-labelledby="addfile-tab">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="row">
                            <script>
                                var ProjectCode = document.getElementById("project_code").value;
                                document.cookie = "ProjectCode = " + ProjectCode;
                            </script>
                            <h2>File</h2>
                            <?php
                            global $DB;
                            $tblname = 'cfg_web';
                            $condition = 'config_key="MEDIA_LOG_COMPLAIN" AND parent=8';
                            $folders = $DB->get_data($tblname, $condition);
                            $FolderName = 'log_complain';
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
                                <div class="col-lg-12">
                                    <div class="row mb-3">
                                        <?php if ($_GET['act'] == 'add') { ?>
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
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <?php
                        $maxRows = 10;

                        if (isset($_GET['maxRows'])) {
                            $maxRows = $_GET['maxRows'];
                        }

                        $tbl_resource_logs = "complain_log";
                        $condition = "project_code = '" . $_GET['project_code'] . "' ORDER BY entry_date ASC";
                        $dataLogResource = $DBKPILog->get_data($tbl_resource_logs, $condition, "", 0, $maxRows);
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
            <script>
                $("#project_code").on('change', function() {
                    var project_code = $(this).val();

                    window.location = window.location.pathname + "?mod=log_complain" + "&act=add&project_code=" + project_code;
                })

                $("#so_number").on('change', function() {
                    var project_code = $("#project_code").val();
                    var so_number = $(this).val();


                    window.location = window.location.pathname + "?mod=log_complain" + "&act=add&project_code=" + project_code + "&so_number=" + so_number;
                })
            </script>


        <?php
    }
    if ($_GET['act'] == 'edit') {
        global $DBKPILog;
        $condition = "complain_id=" . $_GET['complain_id'];
        $data = $DBKPILog->get_data($tblname, $condition);
        $ddata = $data[0];
        $qdata = $data[1];
        $tdata = $data[2];
        $DBSB = get_conn("SERVICE_BUDGET");

        $DBWRIKE = get_conn("WRIKE_INTEGRATE");

        $get_kp = $DBSB->get_sql("SELECT * FROM sa_trx_project_list WHERE bundling not like '%0;0;0%' AND project_code IS NOT NULL AND so_number IS NOT NULL ORDER BY project_code ASC");
        $get_ordernumber = $DBSB->get_sql(" SELECT * FROM sa_trx_project_list WHERE order_number IS NOT NULL ORDER BY order_number ASC");
        // $get_project_leader = $DBWRIKE->get_sql("SELECT DISTINCT project_leader FROM sa_wrike_project_list WHERE project_leader IS NOT NULL ");

        ?>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active text-body" id="addkp-tab" data-bs-toggle="tab" data-bs-target="#addkp" type="button" role="tab" aria-controls="addkp" aria-selected="true">General</button>
                </li>
                <!-- <li class="nav-item" role="presentation">
                <button class="nav-link text-body" id="addresult-tab" data-bs-toggle="tab" data-bs-target="#add_result" type="button" role="tab" aria-controls="add_result" aria-selected="false">Resolution</button>
            </li> -->
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-body" id="addfile-tab" data-bs-toggle="tab" data-bs-target="#addfile" type="button" role="tab" aria-controls="addfile" aria-selected="false">File</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-body" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab" aria-controls="history" aria-selected="false">History</button>
                </li>
            </ul>
            <form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="addkp" role="tabpanel" aria-labelledby="addkp-tab">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row mb-3" hidden>
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Complain Id</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control form-control-sm" id="complain_id" name="complain_id" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                                        echo $ddata['complain_id'];
                                                                                                                                                    } ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Code</label>
                                            <div class="col-sm-9">
                                                <select class="form-control" name="project_code" id="project_code">
                                                    <?php if ($_GET['act'] == "edit" && isset($_GET['ProjectCode'])) {
                                                        $projectcodeeditget = $DBSB->get_data("trx_project_list", "project_code LIKE '%" . $_GET['ProjectCode'] . "%'");
                                                        // $projectcodeeditnew = $DBSB->get_data("trx_project_list", "project_code IS NOT null"); 
                                                    ?>
                                                        <option value="<?php echo $projectcodeeditget[0]['project_code']; ?>"><?php echo $projectcodeeditget[0]['project_code']; ?></option>
                                                        <?php do { ?>
                                                            <option value="<?php echo $get_kp[0]['project_code']; ?>"><?php echo $get_kp[0]['project_code']; ?></option>
                                                        <?php } while ($get_kp[0] = $get_kp[1]->fetch_assoc()); ?>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">SO Number</label>
                                            <div class="col-sm-9">
                                                <select class="form-control" name="so_number" id="so_number">
                                                    <?php if ($_GET['act'] == "edit" && isset($_GET['ProjectCode'])) {
                                                        $so_number = $DBSB->get_data("trx_project_list", "project_code LIKE '%" . $_GET['ProjectCode'] . "%' AND so_number LIKE '%" . $_GET['So_Number'] .  "%'");
                                                        $so_number2 = $DBSB->get_data("trx_project_list", "project_code LIKE '%" . $_GET['ProjectCode'] . "%'"); ?>
                                                        <option value="<?php echo $_GET['So_Number']; ?>"><?php echo $_GET['So_Number']; ?></option>
                                                        <?php do { ?>
                                                            <option value="<?php echo $so_number2[0]['so_number']; ?>"><?php echo $so_number2[0]['so_number']; ?></option>
                                                        <?php } while ($so_number2[0] = $so_number2[1]->fetch_assoc()); ?>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Order Number</label>
                                            <div class="col-sm-9">
                                                <select class="form-control" name="order_number" id="order_number" readonly>
                                                    <?php if ($_GET['act'] == "edit" && isset($_GET['So_Number'])) {
                                                        // $so_number = $DBSB->get_data("trx_project_list", "project_code LIKE '%" . $_GET['ProjectCode'] . "%' AND so_number LIKE '%" . $_GET['So_Number'] .  "%'");
                                                        $so_number2 = $DBSB->get_data("trx_project_list", "so_number LIKE '%" . $_GET['So_Number'] . "%'"); ?>
                                                        <option value="<?php echo $so_number2[0]['order_number']; ?>"><?php echo $so_number2[0]['order_number']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Name</label>
                                            <div class="col-sm-9">
                                                <select class="form-control" name="project_name" id="project_name" readonly>
                                                    <?php if ($_GET['act'] == "edit" && isset($_GET['So_Number'])) {
                                                        // $so_number = $DBSB->get_data("trx_project_list", "project_code LIKE '%" . $_GET['ProjectCode'] . "%' AND so_number LIKE '%" . $_GET['So_Number'] .  "%'");
                                                        $so_number2 = $DBSB->get_data("trx_project_list", "so_number LIKE '%" . $_GET['So_Number'] . "%'"); ?>
                                                        <option value="<?php echo $so_number2[0]['project_name_internal']; ?>"><?php echo $so_number2[0]['project_name_internal']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Leader</label>
                                            <div class="col-sm-9">
                                                <input type="text" readonly class="form-control form-control-sm" id="project_leader" name="project_leader" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                                                        echo $ddata['project_leader'];
                                                                                                                                                                    } ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Customer</label>
                                            <div class="col-sm-9">
                                                <select class="form-control" name="customer" id="customer" readonly>
                                                    <?php if ($_GET['act'] == "edit" && isset($_GET['So_Number'])) {
                                                        // $so_number = $DBSB->get_data("trx_project_list", "project_code LIKE '%" . $_GET['ProjectCode'] . "%' AND so_number LIKE '%" . $_GET['So_Number'] .  "%'");
                                                        $so_number2 = $DBSB->get_data("trx_project_list", "so_number LIKE '%" . $_GET['So_Number'] . "%'"); ?>
                                                        <option value="<?php echo $so_number2[0]['customer_name']; ?>"><?php echo $so_number2[0]['customer_name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Pic Customer</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control form-control-sm" id="pic_customer" name="pic_customer" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                                            echo $ddata['pic_customer'];
                                                                                                                                                        } ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Category</label>
                                            <div class="col-sm-9">
                                                <select class="form-control" name="category" id="category">
                                                    <option value="<?php echo $ddata['category']; ?>"><?php echo $ddata['category']; ?></option>
                                                    <?php if ($_GET['act'] == "edit") { ?>
                                                        <option></option>
                                                        <option value="Project - Internal">Project - Internal</option>
                                                        <option value="Project - Eksternal">Project - Eksternal</option>
                                                        <option value="Personal - Internal">Personal - Internal</option>
                                                        <option value="Personal - Eksternal">Personal - Eksternal</option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Type</label>
                                            <div class="col-sm-9">
                                                <select class="form-control" name="type_project" id="type_project">
                                                    <option value="<?php echo $ddata['type_project']; ?>"><?php echo $ddata['type_project']; ?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Tittle</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control form-control-sm" id="tittle" name="tittle" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                                echo $ddata['tittle'];
                                                                                                                                            } ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Action Plan</label>
                                            <div class="col-sm-9">
                                                <?php if ($_GET['act'] == "edit") {
                                                ?>
                                                    <textarea class="form-control form-control-sm" name="action_plan" id="action_plan" cols="30" rows="2" style="height: 180px;"><?php echo $ddata['action_plan'] ?></textarea>
                                                <?php }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Complain</label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control form-control-sm" name="complain" id="complain" cols="30" rows="2" style="height: 250px;"><?php echo $ddata['complain']; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Tanggal</label>
                                            <div class="col-sm-9">
                                                <input type="date" class="form-control form-control-sm" id="tanggal" name="tanggal" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                                echo $ddata['tanggal'];
                                                                                                                                            } ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status</label>
                                            <div class="col-sm-9">
                                                <?php if ($ddata['status'] == 'Open') { ?>
                                                    <select class="form-control" name="status" id="status">
                                                        <option value=<?php echo $ddata['status']; ?>><?php echo $ddata['status']; ?></option>
                                                        <option value="Completed">Completed</option>
                                                    </select>
                                                <?php } elseif ($ddata['status'] == 'Completed') { ?>
                                                    <select class="form-control" name="status" id="status" readonly>
                                                        <option value="<?php echo $ddata['status']; ?>"><?php echo $ddata['status'] ?></option>
                                                    </select>
                                                <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3" hidden>
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Entry By</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control form-control-sm" id="entry_by" name="entry_by" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                                    echo $ddata['entry_by'];
                                                                                                                                                } ?>">
                                            </div>
                                        </div>
                                        <div class="row mb-3" hidden>
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Entry Date</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control form-control-sm" id="entry_date" name="entry_date" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                                        echo $ddata['entry_date'];
                                                                                                                                                    } ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="addfile" role="tabpanel" aria-labelledby="addfile-tab">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <script>
                                        var ProjectCode = document.getElementById("project_code").value;
                                        document.cookie = "ProjectCode = " + ProjectCode;
                                    </script>
                                    <h2>File</h2>
                                    <?php
                                    global $DB;
                                    $tblname = 'cfg_web';
                                    $condition = 'config_key="MEDIA_LOG_COMPLAIN" AND parent=8';
                                    $folders = $DB->get_data($tblname, $condition);
                                    $FolderName = 'log_complain';
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
                                                <?php if ($_GET['act'] == 'edit') { ?>
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
                                                                    <form method="post">
                                                                        <button type="submit" name="deletefile">Delete File</button>
                                                                    </form>

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
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <?php
                                $maxRows = 10;

                                if (isset($_GET['maxRows'])) {
                                    $maxRows = $_GET['maxRows'];
                                }

                                $tbl_resource_logs = "complain_log";
                                $condition = "project_code = '" . $_GET['ProjectCode'] . "' ORDER BY entry_date ASC";
                                $dataLogResource = $DBKPILog->get_data($tbl_resource_logs, $condition, "", 0, $maxRows);
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
                <script>
                    $("#project_code").on('change', function() {
                        var complain_id = $("#complain_id").val();
                        var project_code = $(this).val();
                        var so_number = $("#so_number").val();

                        window.location = window.location.pathname + "?mod=log_complain" + "&act=edit&complain_id=" + complain_id + "&ProjectCode=" + project_code + "&So_Number=" + so_number;
                    })

                    $("#so_number").on('change', function() {
                        var complain_id = $("#complain_id").val();
                        var project_code = $("#project_code").val();
                        var so_number = $(this).val();


                        window.location = window.location.pathname + "?mod=log_complain" + "&act=edit&complain_id=" + complain_id + "&ProjectCode=" + project_code + "&So_Number=" + so_number;
                    })
                </script>
            <?php }
        if ($_GET['act'] == 'delete') {
            global $DBKPILog;
            $condition = "complain_id=" . $_GET['complain_id'];
            $data = $DBKPILog->get_data($tblname, $condition);
            $ddata = $data[0];
            $qdata = $data[1];
            $tdata = $data[2];
            $get_kpiboard = $DBKPILog->get_sqlV2("SELECT * FROM sa_kpi_board WHERE status_pic LIKE '%pending%' AND project_code ='" . $_GET['ProjectCode'] . "'");
            var_dump($get_kpiboard);
            die;

            ?>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active text-body" id="addkp-tab" data-bs-toggle="tab" data-bs-target="#addkp" type="button" role="tab" aria-controls="addkp" aria-selected="true">General</button>
                    </li>
                    <!-- <li class="nav-item" role="presentation">
                <button class="nav-link text-body" id="addresult-tab" data-bs-toggle="tab" data-bs-target="#add_result" type="button" role="tab" aria-controls="add_result" aria-selected="false">Resolution</button>
            </li> -->
                    <li class="nav-item" role="presentation">
                        <button class="nav-link text-body" id="addfile-tab" data-bs-toggle="tab" data-bs-target="#addfile" type="button" role="tab" aria-controls="addfile" aria-selected="false">File</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link text-body" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab" aria-controls="history" aria-selected="false">History</button>
                    </li>
                </ul>
                <form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="addkp" role="tabpanel" aria-labelledby="addkp-tab">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row mb-3" hidden>
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Complain Id</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control form-control-sm" id="complain_id" name="complain_id" value="<?php if ($_GET['act'] == 'delete') {
                                                                                                                                                            echo $ddata['complain_id'];
                                                                                                                                                        } ?>">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Code</label>
                                                <div class="col-sm-9">
                                                    <input type="text" readonly class="form-control form-control-sm" id="project_code" name="project_code" value="<?php if ($_GET['act'] == 'delete') {
                                                                                                                                                                        echo $ddata['project_code'];
                                                                                                                                                                    } ?>">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">SO Number</label>
                                                <div class="col-sm-9">
                                                    <input type="text" readonly class="form-control form-control-sm" id="so_number" name="so_number" value="<?php if ($_GET['act'] == 'delete') {
                                                                                                                                                                echo $ddata['so_number'];
                                                                                                                                                            } ?>">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Order Number</label>
                                                <div class="col-sm-9">
                                                    <input type="text" readonly class="form-control form-control-sm" id="order_number" name="order_number" value="<?php if ($_GET['act'] == 'delete') {
                                                                                                                                                                        echo $ddata['order_number'];
                                                                                                                                                                    } ?>">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Name</label>
                                                <div class="col-sm-9">
                                                    <input type="text" readonly class="form-control form-control-sm" id="project_name" name="project_name" value="<?php if ($_GET['act'] == 'delete') {
                                                                                                                                                                        echo $ddata['project_name'];
                                                                                                                                                                    } ?>">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Leader</label>
                                                <div class="col-sm-9">
                                                    <input type="text" readonly class="form-control form-control-sm" id="project_leader" name="project_leader" value="<?php if ($_GET['act'] == 'delete') {
                                                                                                                                                                            echo $ddata['project_leader'];
                                                                                                                                                                        } ?>">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Customer</label>
                                                <div class="col-sm-9">
                                                    <input type="text" readonly class="form-control form-control-sm" id="customer" name="customer" value="<?php if ($_GET['act'] == 'delete') {
                                                                                                                                                                echo $ddata['customer'];
                                                                                                                                                            } ?>">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Pic Customer</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control form-control-sm" id="pic_customer" readonly name="pic_customer" value="<?php if ($_GET['act'] == 'delete') {
                                                                                                                                                                        echo $ddata['pic_customer'];
                                                                                                                                                                    } ?>">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Category</label>
                                                <div class="col-sm-9">
                                                    <select class="form-control" name="category" id="category" readonly>
                                                        <option value="<?php echo $ddata['category']; ?>"><?php echo $ddata['category']; ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Type</label>
                                                <div class="col-sm-9">
                                                    <select class="form-control" name="type_project" id="type_project">
                                                        <option value="<?php echo $ddata['type_project']; ?>"><?php echo $ddata['type_project']; ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Tittle</label>
                                                <div class="col-sm-9">
                                                    <input readonly type="text" class="form-control form-control-sm" id="tittle" name="tittle" value="<?php if ($_GET['act'] == 'delete') {
                                                                                                                                                            echo $ddata['tittle'];
                                                                                                                                                        } ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Complain</label>
                                                <div class="col-sm-9">
                                                    <textarea readonly class="form-control form-control-sm" name="complain" id="complain" cols="30" rows="2" style="height: 250px;"><?php echo $ddata['complain']; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Tanggal</label>
                                                <div class="col-sm-9">
                                                    <input readonly type="date" class="form-control form-control-sm" id="tanggal" name="tanggal" value="<?php if ($_GET['act'] == 'delete') {
                                                                                                                                                            echo $ddata['tanggal'];
                                                                                                                                                        } ?>">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status</label>
                                                <div class="col-sm-9">
                                                    <?php if ($_GET['act'] == 'delete') { ?>
                                                        <select class="form-control" name="status" id="status" readonly>
                                                            <option value="<?php echo $ddata['status']; ?>"><?php echo $ddata['status'] ?></option>
                                                        </select>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="row mb-3" hidden>
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Entry By</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control form-control-sm" id="entry_by" name="entry_by" value="<?php if ($_GET['act'] == 'delete') {
                                                                                                                                                        echo $ddata['entry_by'];
                                                                                                                                                    } ?>">
                                                </div>
                                            </div>
                                            <div class="row mb-3" hidden>
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Entry Date</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control form-control-sm" id="entry_date" name="entry_date" value="<?php if ($_GET['act'] == 'delete') {
                                                                                                                                                            echo $ddata['entry_date'];
                                                                                                                                                        } ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="addfile" role="tabpanel" aria-labelledby="addfile-tab">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <div class="row">
                                        <script>
                                            var ProjectCode = document.getElementById("project_code").value;
                                            document.cookie = "ProjectCode = " + ProjectCode;
                                        </script>
                                        <h2>File</h2>
                                        <?php
                                        global $DB;
                                        $tblname = 'cfg_web';
                                        $condition = 'config_key="MEDIA_LOG_COMPLAIN" AND parent=8';
                                        $folders = $DB->get_data($tblname, $condition);
                                        $FolderName = 'log_complain';
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
                                                    <?php if ($_GET['act'] == 'delete') { ?>
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
                                                            <th scope="col">Action</th>
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
                                                                        <form method="post">
                                                                            <button type="submit" name="deletefile">Delete File</button>
                                                                        </form>

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
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <?php
                                    $maxRows = 10;

                                    if (isset($_GET['maxRows'])) {
                                        $maxRows = $_GET['maxRows'];
                                    }

                                    $tbl_resource_logs = "complain_log";
                                    $condition = "project_code = '" . $_GET['ProjectCode'] . "' ORDER BY entry_date ASC";
                                    $dataLogResource = $DBKPILog->get_data($tbl_resource_logs, $condition, "", 0, $maxRows);
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
                <?php } ?>
                <input type="submit" class="btn btn-secondary" name="cancel" value="Cancel">
                <?php if (isset($_GET['act']) && $_GET['act'] == 'edit') { ?>
                    <input type="submit" class="btn btn-primary" name="save" value="Save">
                <?php } elseif (isset($_GET['act']) && $_GET['act'] == 'add') { ?>
                    <input type="submit" class="btn btn-primary" name="add" value="Save">
                <?php } elseif (isset($_GET['act']) && $_GET['act'] == 'delete') { ?>
                    <?php $get_kpiboard = $DBKPILog->get_data("kpi_board", "project_code ='" . $_GET['ProjectCode'] . "' AND status_pic LIKE '%pending%'"); ?>
                    <?php if ($_GET['ProjectCode'] == $get_kpiboard) { ?>
                        <input type="submit" class="btn btn-danger" name="delete" value="Delete">
                    <?php } ?>
                <?php } ?>
                </form>
                <!-- <script>
                $("#addrowresult").click(function() {
                    var html = '';
                    html += '<div class="p-0" id="inputformresult">';
                    // html += '<div class="card shadow mb-4">';
                    html += '<div class="card-body">';
                    html += '<div class="row">';
                    html += '<div class="col-6 text-center"><b>RESULT</b><textarea class="form-control form-control-sm" name="result[]" id="result[]" rows="2"></textarea></div>';
                    html += '<div class="col-6 text-center"><b>SOLUTION</b><textarea class="form-control form-control-sm" name="solution[]" id="solution[]" rows="2"></textarea>';
                    html += '<div class="text-right mt-2">';
                    html += '<button id="removerowresult" type="button" class="btn btn-danger">Remove</button>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';
                    // html += '</div>';

                    $('#newrowresult').append(html);
                });

                $(document).on('click', '#removerowresult', function() {
                    $(this).closest('#inputformresult').remove();
                });
            </script> -->

                <script type="text/javascript">
                    $(document).ready(function() {
                        $('#project_code').select2({
                            placeholder: 'Pilih Project Code',
                            allowClear: true
                        });
                    });

                    $(document).ready(function() {
                        $('#projectcode').select2({
                            // placeholder: 'Pilih Project Code',
                            allowClear: true
                        });
                    });
                    $(document).ready(function() {
                        $('#So_Number').select2({
                            // placeholder: 'Pilih Project Code',
                            allowClear: true
                        });
                    });

                    // $(document).ready(function() {
                    //     $('#order_number').select2({
                    //         placeholder: 'Pilih Order Number',
                    //         allowClear: true
                    //     });
                    // });

                    $(document).ready(function() {
                        $('#category').select2({
                            placeholder: 'Pilih Category',
                            allowClear: true
                        });
                    });

                    $(document).ready(function() {
                        $('#type_project').select2({
                            placeholder: 'Pilih Type',
                            allowClear: true
                        });
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
                                    <form id="upload_form" enctype="multipart/form-data" method="post" action="components/modules/upload/upload.php">
                                        <div>
                                            <div><label for="image_file">Please select image file</label></div>
                                            <div><input type="file" name="image_file" id="image_file" onchange="fileSelected();" /></div>
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