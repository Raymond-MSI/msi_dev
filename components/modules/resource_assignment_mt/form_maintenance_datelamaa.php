<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
    crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
<?php
global $DBMTdate;
$DBMTdate = get_conn("MAINTENANCE_DATE");
$DBSB = get_conn("SERVICE_BUDGET");
$DBWRIKE = get_conn("WRIKE_INTEGRATE");
$DBCR = get_conn("change_request");
$project_code = $_GET['project_code'];
$order_number = isset($_GET['order_number']) ? $_GET['order_number'] : '';
$queryMaster = "SELECT * FROM sa_master_maintenance_date WHERE project_code ='$project_code' and order_number ='$order_number' AND flag=1";
error_reporting(0);
ini_set('display_errors', 0);
$data_master = $DBMTdate->get_sqlV2($queryMaster);
if ($data_master[2] > 0) {
    $datatype = 'edit';
} else {
    $datatype = 'add';
}

$estimation_name = array(
    1 => "Days",
    2 => "Months",
    3 => "Years"
);

if ($datatype == 'add') {
?>
    <form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="addkp" role="tabpanel" aria-labelledby="addkp-tab">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <?php
                                $form_date = 0;
                                $service_type = 'none';
                                $project_estimation_duration = 'none';
                                $project_estimation = 0;
                                $project_estimation_id = 0;
                                $total_addon = 0;
                                $ticket_allocation = 0;
                                $tiket_reporting = 0;
                                $tiket_preventive = 0;
                                $backup_config = 0;
                                $total_pmr = 0;
                                if (isset($_GET['project_code'])) {
                                    $get_project_code = $DBWRIKE->get_sqlV2("SELECT * FROM sa_wrike_project_list WHERE project_type = 'MSI Project Maintenance' AND project_code LIKE '%" . $_GET['project_code'] . "%'");
                                    $project_type = isset($get_project_code[0]['project_type']) ? $get_project_code[0]['project_type'] : '';

                                    $get_type_service = $DBSB->get_sqlV2("select * from sa_mst_type_of_service where service_type = '2'");
                                    while ($row = $get_type_service[1]->fetch_assoc()) {
                                        $type_services[$row['tos_id']] = $row['tos_name'];
                                    }

                                    $get_project = $DBSB->get_sqlV2("select a.project_id, b.tos_id, b.project_estimation, b.project_estimation_id from sa_trx_project_list a inner join sa_trx_project_implementations b on a.project_id = b.project_id where b.service_type = '2' and a.order_number = '" . $_GET['order_number'] . "'");

                                    if ($get_project[2] > 0) {
                                        $project_estimation = $get_project[0]['project_estimation'];
                                        $project_estimation_id = $get_project[0]['project_estimation_id'];
                                        $project_estimation_duration = $project_estimation . ' ' . $estimation_name[$project_estimation_id];

                                        $tos_id = explode(";", $get_project[0]['tos_id']);
                                        foreach ($tos_id as $tos) {
                                            if (isset($type_services[$tos])) {
                                                $service_type = $type_services[$tos];
                                            }
                                        }

                                        if ($project_estimation_id == 1) {
                                            $form_date = ceil($project_estimation / 30);
                                        } elseif ($project_estimation == 2) {
                                            $form_date = $project_estimation;
                                        } elseif ($project_estimation_id == 3) {
                                            $form_date = $project_estimation * 12;
                                        }

                                        $get_addonn = $DBSB->get_sqlV2("select addon_title from sa_trx_addon where service_type = '3' and project_id = '" . $get_project[0]['project_id'] . "' order by addon_id asc");
                                        $total_addon = $get_addonn[2];
                                    }
                                    $checking = $DBMTdate->get_sql("SELECT * FROM sa_master_maintenance_date WHERE project_code ='$project_code' and order_number ='$order_number' AND flag=0");
                                    if (empty($checking[0]['order_number'])) {
                                        $get_tiket = $DBSB->get_sqlV2("select * from sa_trx_project_list where project_code = '" . $project_code . "' and order_number = '" . $order_number . "'");

                                        if ($get_tiket[2] > 0  && $get_tiket[0]['reporting'] != '') {
                                            $tiket = json_decode($get_tiket[0]['reporting'], true);
                                            $tiket_reportingPlan = $tiket['reporting']['plan'];
                                            $tiket_reportingAddon = $tiket['reporting']['addon'];
                                            $tiket_reporting = $tiket_reportingPlan + $tiket_reportingAddon;
                                            $tiket_preventivePlan = $tiket['preventive']['plan'];
                                            $tiket_preventiveAddon = $tiket['preventive']['addon'];
                                            $tiket_preventive = $tiket_preventivePlan + $tiket_preventiveAddon;
                                            $ticket_allocationPlan = $tiket['ticket']['plan'];
                                            $ticket_allocationAddon = $tiket['ticket']['addon'];
                                            $ticket_allocation = $ticket_allocationPlan + $ticket_allocationAddon;
                                            $backup_config = $tiket_preventive + 1;
                                        }
                                        $mt_date_end = null;
                                        $mt_date_ikom = null;
                                        $mt_date_kom = null;
                                        $mt_date_assignment = null;
                                    } else {
                                        $query = "SELECT id_date, date FROM sa_maintenance_date WHERE project_code ='$project_code' and order_number ='$order_number'";
                                        $data_result = $DBMTdate->get_sqlV2($query);

                                        $data_result_date = array();
                                        if ($data_result[2] > 0) {
                                            while ($row = $data_result[1]->fetch_assoc()) {
                                                $data_result_date[$row['id_date']] = $row['date'];
                                            }
                                        }
                                        $ontime = $checking[0]['ontime'];
                                        if ($ontime == 0) {
                                            $ontime_notes = $checking[0]['ontime_notes'];
                                            $notes = $checking[0]['notes'];
                                        }
                                        $gabungan = $checking[0]['gabungan'];
                                        $gabungan_parent = $checking[0]['gabungan_parent'];
                                        $openitem = $checking[0]['openitem'];
                                        if ($openitem == 1) {
                                            $openitem_note = $checking[0]['notes_openitem'];
                                        }
                                        $checking2 = $DBMTdate->get_sql("SELECT * FROM sa_maintenance_date WHERE project_code ='$project_code' and order_number ='$order_number' AND id_date='mt_date_start'");
                                        if (empty($checking2[0]['date'])) {
                                            $mt_date_start = null;
                                        } else {
                                            $mt_date_start = $checking2[0]['date'];
                                        }
                                        $checking3 = $DBMTdate->get_sql("SELECT * FROM sa_maintenance_date WHERE project_code ='$project_code' and order_number ='$order_number' AND id_date='mt_date_end'");
                                        if (empty($checking3[0]['date'])) {
                                            $mt_date_end = null;
                                        } else {
                                            $mt_date_end = $checking3[0]['date'];
                                        }
                                        $checking4 = $DBMTdate->get_sql("SELECT * FROM sa_maintenance_date WHERE project_code ='$project_code' and order_number ='$order_number' AND id_date='ikom'");
                                        if (empty($checking4[0]['date'])) {
                                            $mt_date_ikom = null;
                                        } else {
                                            $mt_date_ikom = $checking4[0]['date'];
                                        }
                                        $checking5 = $DBMTdate->get_sql("SELECT * FROM sa_maintenance_date WHERE project_code ='$project_code' and order_number ='$order_number' AND id_date='kom'");
                                        if (empty($checking5[0]['date'])) {
                                            $mt_date_kom = null;
                                        } else {
                                            $mt_date_kom = $checking5[0]['date'];
                                        }
                                        $checking6 = $DBMTdate->get_sql("SELECT * FROM sa_maintenance_date WHERE project_code ='$project_code' and order_number ='$order_number' AND id_date='kom'");
                                        if (empty($checking6[0]['date'])) {
                                            $mt_date_assignment = null;
                                        } else {
                                            $mt_date_assignment = $checking6[0]['date'];
                                        }
                                        $ticket_allocation = $checking[0]['total_ticket_allocation'];
                                        $tiket_reporting = $checking[0]['total_mt_report_date'];
                                        $tiket_preventive = $checking[0]['total_preventive_mt_date'];
                                        $backup_config = $checking[0]['total_backup_mt_date'];
                                        $total_pmr = $checking[0]['total_pmr_date'];
                                    }

                                    $estimationName = isset($estimation_name[$project_estimation_id]) ? $estimation_name[$project_estimation_id] : '';
                                }
                                ?>
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Project
                                        Code</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "add") { ?>
                                            <select class="form-control" name="project_code" id="project_code" required
                                                readonly>
                                                <?php if (isset($_GET['project_code'])) { ?>
                                                    <option value="<?php echo $_GET['project_code']; ?>">
                                                        <?php echo $_GET['project_code']; ?></option>
                                                <?php } ?>
                                            </select>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Project
                                        Type</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "add" && isset($_GET['project_code'])) { ?>
                                            <input type="text" class="form-control form-control-sm" id="service_type"
                                                name="service_type" value='<?= $project_type; ?>' readonly>
                                        <?php } else { ?>
                                            <input type="text" class="form-control form-control-sm" id="service_type"
                                                name="service_type" value='' readonly>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="service_type" class="col-sm-4 col-form-label col-form-label-sm">Service
                                        Type</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "add") { ?>
                                            <input type="text" class="form-control form-control-sm" id="service_type"
                                                name="service_type" value='<?= $service_type ?>' readonly>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="order_number" class="col-sm-4 col-form-label col-form-label-sm">Order
                                        Number</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "add") { ?>
                                            <input type="text" class="form-control form-control-sm" id="order_number"
                                                name="master[order_number]" value='<?= $order_number; ?>' readonly>
                                            <?php $dataCN = $DBWRIKE->get_sqlV2("SELECT * FROM sa_wrike_project_list WHERE order_number = '" . $_GET['order_number'] . "' AND project_type LIKE '%" . $_GET['project_type'] . "%'");
                                            //echo "SELECT * FROM sa_wrike_project_list WHERE order_number = '" . $_GET['order_number'] . "' AND project_type LIKE '%" . $_GET['project_type'] . "%'";
                                            ?>
                                            <input type="hidden" id="projectId" name="projectId" value="<?php
                                                                                                        if (!empty($dataCN[0]['id'])) {
                                                                                                            echo $dataCN[0]['id'];
                                                                                                        } else {
                                                                                                            echo "Data tidak sinkron !";
                                                                                                        } ?>" />
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Report digabung
                                        dengan Project lain ?
                                        <i class="fas fa-question-circle" data-bs-toggle="popover" data-bs-trigger="focus"
                                            title="Informasi mengenai Report Maintenance dan Preventive Maintenance apakah digabung dengan project yang lain?"></i></label>
                                    <div class="col-sm-8">
                                        <?php if (isset($gabungan_parent)) { ?>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="master[gabungan_parent]"
                                                    value="1" onchange="gabunganParent('yes');" <?php if ($gabungan_parent == 1) {
                                                                                                    echo "checked";
                                                                                                } ?> required>
                                                <label class="form-check-label">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="master[gabungan_parent]"
                                                    value="0" onchange="gabunganParent('no');" <?php if ($gabungan_parent == 0) {
                                                                                                    echo "checked";
                                                                                                } ?> required>
                                                <label class="form-check-label">No</label>
                                            </div>
                                        <?php } else { ?>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="master[gabungan_parent]"
                                                    value="1" onchange="gabunganParent('yes');" required>
                                                <label class="form-check-label">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="master[gabungan_parent]"
                                                    value="0" onchange="gabunganParent('no');" required>
                                                <label class="form-check-label">No</label>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="row mb-3" id="project_gabungan_parent" style="display: none;">
                                    <label for="order_number" class="col-sm-4 col-form-label col-form-label-sm">Project
                                        Induk (Parent)</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "add") { ?>
                                            <?php
                                            $customername = $DBSB->get_sql("SELECT * FROM sa_trx_project_list WHERE order_number='$order_number' AND status='acknowledge'");
                                            $parent = $DBSB->get_sql("SELECT a.*,b.tos_id from sa_trx_project_list a left join sa_trx_project_implementations b ON a.project_id=b.project_id WHERE a.customer_name LIKE '%" . $customername[0]['customer_name'] . "%' AND a.status='acknowledge' AND a.bundling LIKE '%2%' AND b.service_type=2 AND tos_id LIKE '%5%'");
                                            ?>
                                            <select class="form-control" name="master[parent]" id="parent">
                                                <option>===== Pilih Parent =====</option>
                                                <?php
                                                if ($parent[2] > 0) {
                                                    while ($row = $parent[1]->fetch_assoc()) {
                                                        $projectCodee = $row['project_code'];
                                                        $no_so = $row['so_number'];
                                                        $order = $row['order_number'];
                                                ?>
                                                        <option value="<?php echo $order; ?>">
                                                            <?php echo $projectCodee . " (" . $no_so . " - " . $order . ")" ?></option>
                                                    <?php
                                                    }
                                                } else {
                                                    $projectCodee = $parent[0]['project_code'];
                                                    $no_so = $parent[0]['so_number'];
                                                    $order = $parent[0]['order_number'];
                                                    ?>
                                                    <option value="<?php echo $order; ?>">
                                                        <?php echo $projectCodee . " (" . $no_so . " - " . $order . ")" ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="order_number" class="col-sm-4 col-form-label col-form-label-sm">Project
                                        Renewal</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "add") { ?>
                                            <?php
                                            $renewalraw = $DBSB->get_sql("SELECT * FROM sa_trx_project_list WHERE order_number='$order_number' AND status='acknowledge'");
                                            if (empty($renewalraw[0]['previous_id'])) {
                                                $dataRenewal = "Bukan Project Renewal";
                                            } else {
                                                $projectID = $renewalraw[0]['previous_id'];
                                                $renewal = $DBSB->get_sqlV2("SELECT * from sa_trx_project_list WHERE project_id=$projectID");
                                                $dataRenewal = $renewal[0]['project_code'] . " (" . $renewal[0]['so_number'] . " - " . $renewal[0]['order_number'] . ")";
                                            }
                                            ?>
                                            <select class="form-control" name="master[renewal]" id="renewal" readonly>
                                                <option value="<?php echo $dataRenewal; ?>"><?php echo $dataRenewal; ?></option>
                                            </select>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="order_number"
                                        class="col-sm-4 col-form-label col-form-label-sm">Sub-Project</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "add") { ?>
                                            <?php
                                            $subproject = $DBSB->get_sql("SELECT * FROM sa_trx_project_list WHERE order_number='$order_number' AND status='acknowledge'");
                                            if (empty($subproject[0]['parent_id'])) {
                                                $dataSub = "Bukan Sub-Project"; ?>
                                                <input class="form-control form-control-sm" value="<?php echo $dataSub; ?>"
                                                    readonly>
                                            <?php
                                            } else {
                                                $projectId = $subproject[0]['parent_id'];
                                                $get_sub = $DBSB->get_sql("SELECT * FROM sa_trx_project_list WHERE project_id=$projectId");
                                                $dataSub = "Sub-Project dari " . $get_sub[0]['project_code'] . " (" . $get_sub[0]['so_number'] . " - " . $get_sub[0]['order_number'] . ")"; ?>
                                                <textarea class="form-control" readonly><?php echo $dataSub; ?></textarea>
                                            <?php
                                            }
                                            ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">

                                <?php if ($_SESSION['Microservices_UserEmail'] == 'aryo.bimo@mastersystem.co.id') { ?>
                                    <div class="row mb-3">
                                        <label for="ticket_allocation" class="col-sm-4 col-form-label col-form-label-sm">Total
                                            Tiket <i class="fas fa-question-circle" data-bs-toggle="popover"
                                                data-bs-trigger="focus"
                                                title="Tiket dari SBF (Kolom Ke-1), Tiket addon (Kolom Ke-2), , Total tiket (Kolom Ke-3)"></i></label>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control form-control-sm" id="sbf_ticket_allocation"
                                                name="master[ticket_allocation]" value='<?= $ticket_allocation; ?>'>
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control form-control-sm" id="addon_ticket_allocation"
                                                name="master[addon_ticket_allocation]" value='0'
                                                onchange="totalReportDateEdit()">
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control form-control-sm" id="total_ticket_allocation"
                                                name="master[total_ticket_allocation]" value='<?= $ticket_allocation; ?>'
                                                readonly>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="mt_report_date" class="col-sm-4 col-form-label col-form-label-sm">Report
                                            Maintenance</label>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control form-control-sm" id="sbf_mt_report_date"
                                                name="master[mt_report_date]" value='<?= $tiket_reporting; ?>'>
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control form-control-sm" id="addon_mt_report_date"
                                                name="master[addon_mt_report_date]" value='0' onchange="totalReportDateEdit()">
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control form-control-sm" id="total_mt_report_date"
                                                name="master[total_mt_report_date]" value='<?= $tiket_reporting; ?>' readonly>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="assignment_note"
                                            class="col-sm-4 col-form-label col-form-label-sm">Preventive Maintenance</label>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control form-control-sm" id="sbf_preventive_mt_date"
                                                name="master[preventive_mt_date]" value='<?= $tiket_preventive; ?>'>
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control form-control-sm"
                                                id="addon_preventive_mt_date" name="master[addon_preventive_mt_date]" value='0'
                                                onchange="totalReportDateEdit()">
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control form-control-sm"
                                                id="total_preventive_mt_date" name="master[total_preventive_mt_date]"
                                                value='<?= $tiket_preventive; ?>'>
                                        </div>
                                    </div>

                                <?php } else { ?>
                                    <div class="row mb-3">
                                        <label for="total_ticket_allocation"
                                            class="col-sm-4 col-form-label col-form-label-sm">Total Tiket</label>
                                        <div class="col-sm-8">
                                            <?php if ($datatype == "add") { ?>
                                                <input type="text" class="form-control form-control-sm" id="total_ticket_allocation"
                                                    name="master[total_ticket_allocation]" value='<?= $ticket_allocation; ?>'>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Report digabung
                                            <i class="fas fa-question-circle" data-bs-toggle="popover" data-bs-trigger="focus"
                                                title="Informasi mengenai Report Maintenance dan Preventive Maintenance apakah digabung?"></i></label>
                                        <div class="col-sm-8">
                                            <?php if (isset($gabungan)) { ?>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="master[gabungan]" value="1"
                                                        onchange="gabunganReport('yes');" <?php if ($gabungan == 1) {
                                                                                                echo "checked";
                                                                                            } ?> required>
                                                    <label class="form-check-label">Yes</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="master[gabungan]" value="0"
                                                        onchange="gabunganReport('no');" <?php if ($gabungan == 0) {
                                                                                                echo "checked";
                                                                                            } ?> required>
                                                    <label class="form-check-label">No</label>
                                                </div>
                                            <?php } else { ?>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="master[gabungan]" value="1"
                                                        onchange="gabunganReport('yes');" required>
                                                    <label class="form-check-label">Yes</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="master[gabungan]" value="0"
                                                        onchange="gabunganReport('no');" required>
                                                    <label class="form-check-label">No</label>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="row mb-3" id="display_gabungan" style="display: none;">
                                        <label for="assignment_note" class="col-sm-4 col-form-label col-form-label-sm">Report
                                            Maintenance</label>
                                        <div class="col-sm-8">
                                            <?php if ($datatype == "add") { ?>
                                                <input type="text" class="form-control form-control-sm" id="total_mt_report_date"
                                                    name="master[total_mt_report_date]" value='<?= $tiket_reporting; ?>'
                                                    onchange="totalReportDate('mt_report_date')">
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="row mb-3" id="display_gabungan6" style="display: none;">
                                        <label for="assignment_note"
                                            class="col-sm-4 col-form-label col-form-label-sm">Preventive Maintenance</label>
                                        <div class="col-sm-8">
                                            <?php if ($datatype == "add") { ?>
                                                <input type="text" class="form-control form-control-sm"
                                                    id="total_preventive_mt_date" name="master[total_preventive_mt_date]"
                                                    value='<?= $tiket_preventive; ?>'
                                                    onchange="totalReportDate('preventive_mt_date')">
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="row mb-3" id="display_gabungan2" style="display: none;">
                                        <label for="assignment_note"
                                            class="col-sm-4 col-form-label col-form-label-sm">Preventive dan Maintenance
                                            Report</label>
                                        <div class="col-sm-8">
                                            <?php if ($datatype == "add") { ?>
                                                <input type="text" class="form-control form-control-sm" id="total_pmr_date"
                                                    name="master[total_pmr_date]" value='<?= $total_pmr; ?>'
                                                    onchange="totalReportDate('pmr_date')">
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="assignment_note" class="col-sm-4 col-form-label col-form-label-sm">Backup
                                            Config Maintenance</label>
                                        <div class="col-sm-8">
                                            <?php if ($datatype == "add") { ?>
                                                <input type="text" class="form-control form-control-sm" id="total_backup_mt_date"
                                                    name="master[total_backup_mt_date]" value='<?= $backup_config; ?>'
                                                    onchange="totalReportDate('backup_mt_date')">
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>


                                <div class="row mb-3">
                                    <label for="project_duration" class="col-sm-4 col-form-label col-form-label-sm">Project
                                        Duration</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "add") { ?>
                                            <input type="text" class="form-control form-control-sm" id="project_duration"
                                                name="project_duration" value='<?= $project_estimation_duration ?>' readonly>
                                        <?php } ?>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-6">
                                <br>
                                <div class="row mb-3">
                                    <label for="mt_date_start" class="col-sm-4 col-form-label col-form-label-sm">Maintenance
                                        Start</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "add") {
                                            if (isset($mt_date_start)) {
                                                if ($mt_date_start == null) { ?>
                                                    <input type="date" class="form-control form-control-sm" id="mt_date_start"
                                                        name="date[mt_date_start]" onchange="updateDurationActual('add')" required>
                                                <?php } else { ?>
                                                    <input type="date" class="form-control form-control-sm" id="mt_date_start"
                                                        name="date[mt_date_start]" onchange="updateDurationActual('add')"
                                                        value="<?php echo $mt_date_start; ?>" required>
                                                <?php }
                                            } else { ?>
                                                <input type="date" class="form-control form-control-sm" id="mt_date_start"
                                                    name="date[mt_date_start]" onchange="updateDurationActual('add')" required>
                                        <?php }
                                        } ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <br>
                                <div class="row mb-3">
                                    <label for="mt_date_end" class="col-sm-4 col-form-label col-form-label-sm">Maintenance
                                        End</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "add") {
                                            if ($mt_date_end == null) { ?>
                                                <input type="date" class="form-control form-control-sm" id="mt_date_end"
                                                    name="date[mt_date_end]" onchange="updateDurationActual('add')" required>
                                            <?php } else { ?>
                                                <input type="date" class="form-control form-control-sm" id="mt_date_end"
                                                    name="date[mt_date_end]" onchange="updateDurationActual('add')"
                                                    value="<?php echo $mt_date_end; ?>" required>
                                        <?php }
                                        } ?>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="duration_actual" class="col-sm-4 col-form-label col-form-label-sm">Project
                                        Duration Actual</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "add") { ?>
                                            <!-- <input type="date" class="form-control form-control-sm" id="duration_actual" value="" readonly> -->
                                            <input type="text" class="form-control form-control-sm" id="diff_duration"
                                                value='0 Days' readonly>
                                        <?php } ?>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="row mb-3">
                                    <label for="ikom_date_start" class="col-sm-4 col-form-label col-form-label-sm">IKOM
                                        Date</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "add") {
                                            if ($mt_date_ikom == null) { ?>
                                                <input type="date" class="form-control form-control-sm" id="ikom_date_start"
                                                    name="date[ikom]">
                                            <?php } else { ?>
                                                <input type="date" class="form-control form-control-sm" id="ikom_date_start"
                                                    name="date[ikom]" value="<?php echo $mt_date_ikom; ?>">
                                        <?php }
                                        } ?>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="assignment_date"
                                        class="col-sm-4 col-form-label col-form-label-sm">Assignment Date</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "add") {
                                            if ($mt_date_assignment == null) { ?>
                                                <input type="date" class="form-control form-control-sm" id="assignment_date"
                                                    name="date[assignment_date]">
                                            <?php } else { ?>
                                                <input type="date" class="form-control form-control-sm" id="assignment_date"
                                                    name="date[assignment_date]" value="<?php echo $mt_date_assignment; ?>">
                                        <?php }
                                        } ?>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Ontime <i
                                            class="fas fa-question-circle" data-bs-toggle="popover" data-bs-trigger="focus"
                                            title="Informasi mengenai GAP tanggal start maintenance dengan tanggal assignment ke PM. Jika berada di bulan yang sama maka Ontime (Yes), jika berbeda bulan artinya ada Tidak Ontime (No) / Ada GAP yang disebabkan Backdate Customer / Implementasi / Sales"></i></label>
                                    <div class="col-sm-8">
                                        <?php if (isset($ontime)) { ?>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="master[ontime]" value="1"
                                                    onchange="onTime('yes');" <?php if ($ontime == 0) {
                                                                                    echo "checked";
                                                                                } ?> required>
                                                <label class="form-check-label">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="master[ontime]" value="0"
                                                    onchange="onTime('no');" <?php if ($ontime == 1) {
                                                                                    echo "checked";
                                                                                } ?> required>
                                                <label class="form-check-label">No</label>
                                            </div>
                                        <?php } else { ?>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="master[ontime]" value="1"
                                                    onchange="onTime('yes');" required>
                                                <label class="form-check-label">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="master[ontime]" value="0"
                                                    onchange="onTime('no');" required>
                                                <label class="form-check-label">No</label>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="row mb-3" id="display_ontime" style="display: none;">
                                    <label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm"></label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "add") {
                                            if (isset($ontime_notes)) { ?>
                                                <select class="form-control" name="master[ontime_notes]" id="ontime_notes">
                                                    <option value="<?php echo $ontime_notes; ?>"><?php echo $ontime_notes; ?>
                                                    </option>
                                                    <?php if ($ontime_notes == 'sales') { ?>
                                                        <option value="implementation">Implementation</option>
                                                        <option value="customer">Customer</option>
                                                        <option value="other">Other</option>
                                                    <?php } else if ($ontime_notes == 'implementation') { ?>
                                                        <option value="sales">Sales</option>
                                                        <option value="customer">Customer</option>
                                                        <option value="other">Other</option>
                                                    <?php } else if ($ontime_notes == 'customer') { ?>
                                                        <option value="sales">Sales</option>
                                                        <option value="implementation">Implementation</option>
                                                        <option value="other">Other</option>
                                                    <?php } else if ($ontime_notes == 'other') { ?>
                                                        <option value="sales">Sales</option>
                                                        <option value="implementation">Implementation</option>
                                                        <option value="customer">Customer</option>
                                                    <?php } ?>
                                                </select>
                                            <?php } else { ?>
                                                <select class="form-control" name="master[ontime_notes]" id="ontime_notes">
                                                    <option value="sales">Sales</option>
                                                    <option value="implementation">Implementation</option>
                                                    <option value="customer">Customer</option>
                                                    <option value="other">Other</option>
                                                </select>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="assignment_note"
                                        class="col-sm-4 col-form-label col-form-label-sm">Note</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "add") {
                                            if (isset($notes)) { ?>
                                                <input type="text" class="form-control form-control-sm" id="assignment_note"
                                                    name="master[assignment_note]" value='<?php echo $notes; ?>'>
                                            <?php } else { ?>
                                                <input type="text" class="form-control form-control-sm" id="assignment_note"
                                                    name="master[assignment_note]" value=''>
                                        <?php }
                                        } ?>
                                    </div>
                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="row mb-3">
                                    <label for="kom_date" class="col-sm-4 col-form-label col-form-label-sm">KOM Date</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "add") {
                                            if ($mt_date_kom == null) { ?>
                                                <input type="date" class="form-control form-control-sm" id="kom_date"
                                                    name="date[kom]">
                                            <?php } else { ?>
                                                <input type="date" class="form-control form-control-sm" id="kom_date"
                                                    name="date[kom]" value="<?php echo $mt_date_kom; ?>">
                                        <?php }
                                        } ?>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="m_addon_title" class="col-sm-12 col-form-label col-form-label-sm">Add On
                                        :</label>

                                </div>
                                <?php if ($total_addon > 0) { ?>
                                    <?php while ($data_addon = $get_addonn[1]->fetch_assoc()) { ?>
                                        <div class="row mb-3">

                                            <div class="col-sm-12">
                                                <input type="text" class="form-control form-control-sm" id="m_addon_title"
                                                    name="m_addon_title" value="<?= $data_addon['addon_title'] ?>" readonly>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } else { ?>
                                    <div class="row mb-3">
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control form-control-sm" id="m_addon_title"
                                                name="m_addon_title" value="" readonly>
                                        </div>
                                    </div>
                                <?php } ?>

                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Open Item
                                        Implementation <i class="fas fa-question-circle" data-bs-toggle="popover"
                                            data-bs-trigger="focus"
                                            title="Apakah project ini merupakan Open Item dari Implementation?"></i></label>
                                    <div class="col-sm-8">
                                        <?php if (isset($openitem)) { ?>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="master[openitem]" value="1"
                                                    onchange="OpenItem('yes');" <?php if ($openitem == 1) {
                                                                                    echo "checked";
                                                                                } ?> required>
                                                <label class="form-check-label">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="master[openitem]" value="0"
                                                    onchange="OpenItem('no');" <?php if ($openitem == 0) {
                                                                                    echo "checked";
                                                                                } ?> required>
                                                <label class="form-check-label">No</label>
                                            </div>
                                        <?php } else { ?>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="master[openitem]" value="1"
                                                    onchange="OpenItem('yes');" required>
                                                <label class="form-check-label">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="master[openitem]" value="0"
                                                    onchange="OpenItem('no');" required>
                                                <label class="form-check-label">No</label>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="row mb-3" id="display_openitem" style="display: none;">
                                    <label for="assignment_note"
                                        class="col-sm-4 col-form-label col-form-label-sm">Note</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "add") {
                                            if (isset($openitem_note)) { ?>
                                                <input type="text" class="form-control form-control-sm" id="openitem_note"
                                                    name="master[openitem_note]" value='<?php echo $openitem_note; ?>'>
                                            <?php } else { ?>
                                                <input type="text" class="form-control form-control-sm" id="openitem_note"
                                                    name="master[openitem_note]" value=''>
                                        <?php }
                                        } ?>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <hr>
                            </div>
                            <?php if (isset($checking[0]['order_number'])) { ?>
                                <div class="col-md-4 border-right" id="display_gabungan3" style="display: none;">
                                    <div class="row" id="add_mt_report_date">
                                        <div class="row mb-3">
                                            <div class="col-sm-6">
                                                <b>Maintenance Report</b>
                                            </div>
                                            <div class="col-sm-6">
                                                <b>Date</b>
                                            </div>
                                        </div>

                                        <?php for ($i = 1; $i <= $checking[0]['total_mt_report_date']; $i++) { ?>
                                            <div class="row" id="input_mt_report_date">
                                                <div class="col-sm-6">
                                                    <div class="row mb-3">
                                                        <label for="mt_report_date"
                                                            class="col-sm-12 col-form-label col-form-label-sm"><?= $i ?></label>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6">
                                                    <div class="row mb-3">
                                                        <div class="col-sm-12">
                                                            <input type="date" class="form-control form-control-sm"
                                                                id="mt_report_date" name="date[mt_report_date][]"
                                                                value="<?= isset($data_result_date['mt_report_date_' . $i]) ? $data_result_date['mt_report_date_' . $i] : '' ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>

                                    </div>
                                </div>
                                <div class="col-md-4 border-right">
                                    <div class="row" id="add_backup_mt_date">
                                        <div class="row mb-3">
                                            <div class="col-sm-6">
                                                <b>Backup Config</b>
                                            </div>
                                            <div class="col-sm-6">
                                                <b>Date</b>
                                            </div>
                                        </div>

                                        <?php for ($i = 1; $i <= $checking[0]['total_backup_mt_date']; $i++) { ?>
                                            <div class="row" id="input_backup_mt_date">
                                                <div class="col-sm-6">
                                                    <div class="row mb-3">
                                                        <label for="backup_mt_date"
                                                            class="col-sm-12 col-form-label col-form-label-sm"><?= $i ?></label>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6">
                                                    <div class="row mb-3">
                                                        <div class="col-sm-12">
                                                            <input type="date" class="form-control form-control-sm"
                                                                id="backup_mt_date" name="date[backup_mt_date][]"
                                                                value="<?= isset($data_result_date['backup_mt_date_' . $i]) ? $data_result_date['backup_mt_date_' . $i] : '' ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="col-md-4 border-right" id="display_gabungan4" style="display: none;">
                                    <div class="row" id="add_preventive_mt_date">
                                        <div class="row mb-3">
                                            <div class="col-sm-6">
                                                <b>Preventive Maintenance Report</b>
                                            </div>
                                            <div class="col-sm-6">
                                                <b>Date</b>
                                            </div>
                                        </div>

                                        <?php for ($i = 1; $i <= $checking[0]['total_preventive_mt_date']; $i++) { ?>
                                            <div class="row" id="input_mt_report_date">
                                                <div class="col-sm-6">
                                                    <div class="row mb-3">
                                                        <label for="preventive_mt_date"
                                                            class="col-sm-12 col-form-label col-form-label-sm"><?= $i ?></label>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6">
                                                    <div class="row mb-3">
                                                        <div class="col-sm-12">
                                                            <input type="date" class="form-control form-control-sm"
                                                                id="preventive_mt_date" name="date[preventive_mt_date][]"
                                                                value="<?= isset($data_result_date['preventive_mt_date_' . $i]) ? $data_result_date['preventive_mt_date_' . $i] : '' ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="col-md-6 border-right" id="display_gabungan5" style="display: none;">
                                    <div class="row" id="add_pmr_date">
                                        <div class="row mb-3">
                                            <div class="col-sm-6">
                                                <b>Preventive dan Maintenance Report</b>
                                            </div>
                                            <div class="col-sm-6">
                                                <b>Date</b>
                                            </div>
                                        </div>

                                        <?php for ($i = 1; $i <= $checking[0]['total_pmr_date']; $i++) { ?>
                                            <div class="row" id="input_pmr_date">
                                                <div class="col-sm-6">
                                                    <div class="row mb-3">
                                                        <label for="pmr_date"
                                                            class="col-sm-12 col-form-label col-form-label-sm"><?= $i ?></label>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6">
                                                    <div class="row mb-3">
                                                        <div class="col-sm-12">
                                                            <input type="date" class="form-control form-control-sm" id="pmr_date"
                                                                name="date[pmr_date][]"
                                                                value="<?= isset($data_result_date['pmr_date_' . $i]) ? $data_result_date['pmr_date_' . $i] : '' ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="col-md-4 border-right" id="display_gabungan3" style="display: none;">
                                    <div class="row" id="add_mt_report_date">
                                        <div class="row mb-3">
                                            <div class="col-sm-6">
                                                <b>Maintenance Report</b>
                                            </div>
                                            <div class="col-sm-6">
                                                <b>Date</b>
                                            </div>
                                        </div>
                                        <div class="row" id="input_mt_report_date">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 border-right">
                                    <div class="row" id="add_backup_mt_date">
                                        <div class="row mb-3">
                                            <div class="col-sm-6">
                                                <b>Backup Config</b>
                                            </div>
                                            <div class="col-sm-6">
                                                <b>Date</b>
                                            </div>
                                        </div>
                                        <div class="row" id="input_backup_mt_date">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4" id="display_gabungan4" style="display: none;">
                                    <div class="row" id="add_preventive_mt_date">
                                        <div class="row mb-3">
                                            <div class="col-sm-6">
                                                <b>Preventive Maintenance</b>
                                            </div>
                                            <div class="col-sm-6">
                                                <b>Date</b>
                                            </div>
                                        </div>

                                        <div class="row" id="input_preventive_mt_date">

                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-6 border-right" id="display_gabungan5" style="display: none;">
                                    <div class="row" id="add_pmr_date">
                                        <div class="row mb-3">
                                            <div class="col-sm-6">
                                                <b>Preventive & Maintenance Report</b>
                                            </div>
                                            <div class="col-sm-6">
                                                <b>Date</b>
                                            </div>
                                        </div>
                                        <div class="row" id="input_pmr_date">
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
}
if ($datatype == 'edit') {
?>
    <form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="addkp" role="tabpanel" aria-labelledby="addkp-tab">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <?php
                                $form_date = 0;
                                $service_type = 'none';
                                $project_estimation_duration = 'none';
                                $project_estimation = 0;
                                $project_estimation_id = 0;
                                $total_addon = 0;
                                $ticket_allocation = 0;
                                $tiket_reporting = 0;
                                $tiket_preventive = 0;
                                $backup_config = 0;
                                $total_pmr = 0;
                                if (isset($_GET['project_code'])) {
                                    $get_project_code = $DBWRIKE->get_sqlV2("SELECT * FROM sa_wrike_project_list WHERE project_type = 'MSI Project Maintenance' AND project_code LIKE '%" . $_GET['project_code'] . "%'");
                                    $project_type = isset($get_project_code[0]['project_type']) ? $get_project_code[0]['project_type'] : '';

                                    $get_type_service = $DBSB->get_sqlV2("select * from sa_mst_type_of_service where service_type = '2'");
                                    while ($row = $get_type_service[1]->fetch_assoc()) {
                                        $type_services[$row['tos_id']] = $row['tos_name'];
                                    }

                                    $get_project = $DBSB->get_sqlV2("select a.project_id, b.tos_id, b.project_estimation, b.project_estimation_id from sa_trx_project_list a inner join sa_trx_project_implementations b on a.project_id = b.project_id where b.service_type = '2' and a.order_number = '" . $_GET['order_number'] . "'");

                                    if ($get_project[2] > 0) {
                                        $project_estimation = $get_project[0]['project_estimation'];
                                        $project_estimation_id = $get_project[0]['project_estimation_id'];
                                        $project_estimation_duration = $project_estimation . ' ' . $estimation_name[$project_estimation_id];

                                        $tos_id = explode(";", $get_project[0]['tos_id']);
                                        foreach ($tos_id as $tos) {
                                            if (isset($type_services[$tos])) {
                                                $service_type = $type_services[$tos];
                                            }
                                        }

                                        if ($project_estimation_id == 1) {
                                            $form_date = ceil($project_estimation / 30);
                                        } elseif ($project_estimation == 2) {
                                            $form_date = $project_estimation;
                                        } elseif ($project_estimation_id == 3) {
                                            $form_date = $project_estimation * 12;
                                        }

                                        $get_addonn = $DBSB->get_sqlV2("select addon_title from sa_trx_addon where service_type = '3' and project_id = '" . $get_project[0]['project_id'] . "' order by addon_id asc");
                                        $total_addon = $get_addonn[2];
                                    }
                                    $checking = $DBMTdate->get_sql("SELECT * FROM sa_master_maintenance_date WHERE project_code ='$project_code' and order_number ='$order_number' AND flag=1");
                                    if (empty($checking[0]['order_number'])) {
                                        $get_tiket = $DBSB->get_sqlV2("select * from sa_trx_project_list where project_code = '" . $project_code . "' and order_number = '" . $order_number . "'");

                                        if ($get_tiket[2] > 0  && $get_tiket[0]['reporting'] != '') {
                                            $tiket = json_decode($get_tiket[0]['reporting'], true);
                                            $tiket_reportingPlan = $tiket['reporting']['plan'];
                                            $tiket_reportingAddon = $tiket['reporting']['addon'];
                                            $tiket_reporting = $tiket_reportingPlan + $tiket_reportingAddon;
                                            $tiket_preventivePlan = $tiket['preventive']['plan'];
                                            $tiket_preventiveAddon = $tiket['preventive']['addon'];
                                            $tiket_preventive = $tiket_preventivePlan + $tiket_preventiveAddon;
                                            $ticket_allocationPlan = $tiket['ticket']['plan'];
                                            $ticket_allocationAddon = $tiket['ticket']['addon'];
                                            $ticket_allocation = $ticket_allocationPlan + $ticket_allocationAddon;
                                            $backup_config = $tiket_preventive + 1;
                                        }
                                        $mt_date_end = null;
                                        $mt_date_ikom = null;
                                        $mt_date_kom = null;
                                        $mt_date_assignment = null;
                                    } else {
                                        $query = "SELECT id_date, date FROM sa_maintenance_date WHERE project_code ='$project_code' and order_number ='$order_number'";
                                        $data_result = $DBMTdate->get_sqlV2($query);

                                        $data_result_date = array();
                                        if ($data_result[2] > 0) {
                                            while ($row = $data_result[1]->fetch_assoc()) {
                                                $data_result_date[$row['id_date']] = $row['date'];
                                            }
                                        }
                                        $ontime = $checking[0]['ontime'];
                                        if ($ontime == 0) {
                                            $ontime_notes = $checking[0]['ontime_notes'];
                                            $notes = $checking[0]['notes'];
                                        }
                                        $gabungan = $checking[0]['gabungan'];
                                        $gabungan_parent = $checking[0]['gabungan_parent'];
                                        $openitem = $checking[0]['openitem'];
                                        if ($openitem == 1) {
                                            $openitem_note = $checking[0]['notes_openitem'];
                                        }
                                        $checking2 = $DBMTdate->get_sql("SELECT * FROM sa_maintenance_date WHERE project_code ='$project_code' and order_number ='$order_number' AND id_date='mt_date_start'");
                                        if (empty($checking2[0]['date'])) {
                                            $mt_date_start = null;
                                        } else {
                                            $mt_date_start = $checking2[0]['date'];
                                        }
                                        $checking3 = $DBMTdate->get_sql("SELECT * FROM sa_maintenance_date WHERE project_code ='$project_code' and order_number ='$order_number' AND id_date='mt_date_end'");
                                        if (empty($checking3[0]['date'])) {
                                            $mt_date_end = null;
                                        } else {
                                            $mt_date_end = $checking3[0]['date'];
                                        }
                                        $checking4 = $DBMTdate->get_sql("SELECT * FROM sa_maintenance_date WHERE project_code ='$project_code' and order_number ='$order_number' AND id_date='ikom'");
                                        if (empty($checking4[0]['date'])) {
                                            $mt_date_ikom = null;
                                        } else {
                                            $mt_date_ikom = $checking4[0]['date'];
                                        }
                                        $checking5 = $DBMTdate->get_sql("SELECT * FROM sa_maintenance_date WHERE project_code ='$project_code' and order_number ='$order_number' AND id_date='kom'");
                                        if (empty($checking5[0]['date'])) {
                                            $mt_date_kom = null;
                                        } else {
                                            $mt_date_kom = $checking5[0]['date'];
                                        }
                                        $checking6 = $DBMTdate->get_sql("SELECT * FROM sa_maintenance_date WHERE project_code ='$project_code' and order_number ='$order_number' AND id_date='kom'");
                                        if (empty($checking6[0]['date'])) {
                                            $mt_date_assignment = null;
                                        } else {
                                            $mt_date_assignment = $checking6[0]['date'];
                                        }
                                        $ticket_allocation = $checking[0]['total_ticket_allocation'];
                                        $tiket_reporting = $checking[0]['total_mt_report_date'];
                                        $tiket_preventive = $checking[0]['total_preventive_mt_date'];
                                        $backup_config = $checking[0]['total_backup_mt_date'];
                                        $total_pmr = $checking[0]['total_pmr_date'];
                                    }

                                    $estimationName = isset($estimation_name[$project_estimation_id]) ? $estimation_name[$project_estimation_id] : '';
                                }
                                ?>
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Project
                                        Code</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "edit") { ?>
                                            <select class="form-control" name="project_code" id="project_code" readonly>
                                                <?php if (isset($_GET['project_code'])) { ?>
                                                    <option value="<?php echo $_GET['project_code']; ?>">
                                                        <?php echo $_GET['project_code']; ?></option>
                                                <?php } ?>
                                            </select>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Project
                                        Type</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "edit" && isset($_GET['project_code'])) { ?>
                                            <input type="text" class="form-control form-control-sm" id="service_type"
                                                name="service_type" value='<?= $project_type; ?>' readonly>
                                        <?php } else { ?>
                                            <input type="text" class="form-control form-control-sm" id="service_type"
                                                name="service_type" value='' readonly>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="service_type" class="col-sm-4 col-form-label col-form-label-sm">Service
                                        Type</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "edit") { ?>
                                            <input type="text" class="form-control form-control-sm" id="service_type"
                                                name="service_type" value='<?= $service_type ?>' readonly>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="order_number" class="col-sm-4 col-form-label col-form-label-sm">Order
                                        Number</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "edit") { ?>
                                            <input type="text" class="form-control form-control-sm" id="order_number"
                                                name="master[order_number]" value='<?= $order_number; ?>' readonly>
                                            <?php $dataCN = $DBWRIKE->get_sqlV2("SELECT * FROM sa_wrike_project_list WHERE order_number = '" . $_GET['order_number'] . "' AND project_type LIKE '%" . $_GET['project_type'] . "%'");
                                            //echo "SELECT * FROM sa_wrike_project_list WHERE order_number = '" . $_GET['order_number'] . "' AND project_type LIKE '%" . $_GET['project_type'] . "%'";
                                            ?>
                                            <input type="hidden" id="projectId" name="projectId" value="<?php
                                                                                                        if (!empty($dataCN[0]['id'])) {
                                                                                                            echo $dataCN[0]['id'];
                                                                                                        } else {
                                                                                                            echo "Data tidak sinkron !";
                                                                                                        } ?>" />
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Report digabung
                                        dengan Project lain ?
                                        <i class="fas fa-question-circle" data-bs-toggle="popover" data-bs-trigger="focus"
                                            title="Informasi mengenai Report Maintenance dan Preventive Maintenance apakah digabung dengan project yang lain?"></i></label>
                                    <div class="col-sm-8">
                                        <?php if (isset($gabungan_parent)) { ?>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="master[gabungan_parent]"
                                                    value="1" onchange="gabunganParent('yes');" <?php if ($gabungan_parent == 1) {
                                                                                                    echo "checked";
                                                                                                } ?> disabled>
                                                <label class="form-check-label">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="master[gabungan_parent]"
                                                    value="0" onchange="gabunganParent('no');" <?php if ($gabungan_parent == 0) {
                                                                                                    echo "checked";
                                                                                                } ?> disabled>
                                                <label class="form-check-label">No</label>
                                            </div>
                                        <?php } else { ?>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="master[gabungan_parent]"
                                                    value="1" onchange="gabunganParent('yes');" disabled>
                                                <label class="form-check-label">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="master[gabungan_parent]"
                                                    value="0" onchange="gabunganParent('no');" disabled>
                                                <label class="form-check-label">No</label>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="row mb-3" id="project_gabungan_parent" style="display: none;">
                                    <label for="order_number" class="col-sm-4 col-form-label col-form-label-sm">Project
                                        Induk (Parent)</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "edit") { ?>
                                            <?php
                                            $customername = $DBSB->get_sql("SELECT * FROM sa_trx_project_list WHERE order_number='$order_number' AND status='acknowledge'");
                                            $parent = $DBSB->get_sql("SELECT a.*,b.tos_id from sa_trx_project_list a left join sa_trx_project_implementations b ON a.project_id=b.project_id WHERE a.customer_name LIKE '%" . $customername[0]['customer_name'] . "%' AND a.status='acknowledge' AND a.bundling LIKE '%2%' AND b.service_type=2 AND tos_id LIKE '%5%'");
                                            ?>
                                            <select class="form-control" name="master[parent]" id="parent" readonly>
                                                <?php if($checking[0]['parent'] !== NULL || $checking[0]['parent'] !== "===== Pilih Parent =====" || $checking[0]['parent'] !== "") { 
                                                    $customernamew = $DBSB->get_sql("SELECT * FROM sa_trx_project_list WHERE order_number='" . $checking[0]['parent'] . "' AND status='acknowledge'");
                                                    $projectCodee = $customernamew[0]['project_code'];
                                                    $no_so = $customernamew[0]['so_number'];
                                                    $order = $customernamew[0]['order_number'];
                                                ?>
                                                <option value="<?php echo $order; ?>">
                                                            <?php echo $projectCodee . " (" . $no_so . " - " . $order . ")" ?></option>
                                                <?php } else { ?>
                                                <option>===== Pilih Parent =====</option>
                                                <?php } ?>
                                            </select>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="order_number" class="col-sm-4 col-form-label col-form-label-sm">Project
                                        Renewal</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "edit") { ?>
                                            <?php
                                            $renewalraw = $DBSB->get_sql("SELECT * FROM sa_trx_project_list WHERE order_number='$order_number' AND status='acknowledge'");
                                            if (empty($renewalraw[0]['previous_id'])) {
                                                $dataRenewal = "Bukan Project Renewal";
                                            } else {
                                                $projectID = $renewalraw[0]['previous_id'];
                                                $renewal = $DBSB->get_sqlV2("SELECT * from sa_trx_project_list WHERE project_id=$projectID");
                                                $dataRenewal = $renewal[0]['project_code'] . " (" . $renewal[0]['so_number'] . " - " . $renewal[0]['order_number'] . ")";
                                            }
                                            ?>
                                            <select class="form-control" name="master[renewal]" id="renewal" readonly>
                                                <option value="<?php echo $dataRenewal; ?>"><?php echo $dataRenewal; ?></option>
                                            </select>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="order_number"
                                        class="col-sm-4 col-form-label col-form-label-sm">Sub-Project</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "edit") { ?>
                                            <?php
                                            $subproject = $DBSB->get_sql("SELECT * FROM sa_trx_project_list WHERE order_number='$order_number' AND status='acknowledge'");
                                            if (empty($subproject[0]['parent_id'])) {
                                                $dataSub = "Bukan Sub-Project"; ?>
                                                <input class="form-control form-control-sm" value="<?php echo $dataSub; ?>"
                                                    readonly>
                                            <?php
                                            } else {
                                                $projectId = $subproject[0]['parent_id'];
                                                $get_sub = $DBSB->get_sql("SELECT * FROM sa_trx_project_list WHERE project_id=$projectId");
                                                $dataSub = "Sub-Project dari " . $get_sub[0]['project_code'] . " (" . $get_sub[0]['so_number'] . " - " . $get_sub[0]['order_number'] . ")"; ?>
                                                <textarea class="form-control" readonly><?php echo $dataSub; ?></textarea>
                                            <?php
                                            }
                                            ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">

                                <?php if ($_SESSION['Microservices_UserEmail'] == 'aryo.bimo@mastersystem.co.id') { ?>
                                    <div class="row mb-3">
                                        <label for="ticket_allocation" class="col-sm-4 col-form-label col-form-label-sm">Total
                                            Tiket <i class="fas fa-question-circle" data-bs-toggle="popover"
                                                data-bs-trigger="focus"
                                                title="Tiket dari SBF (Kolom Ke-1), Tiket addon (Kolom Ke-2), , Total tiket (Kolom Ke-3)"></i></label>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control form-control-sm" id="sbf_ticket_allocation"
                                                name="master[ticket_allocation]" value='<?= $ticket_allocation; ?>'>
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control form-control-sm" id="addon_ticket_allocation"
                                                name="master[addon_ticket_allocation]" value='0'
                                                onchange="totalReportDateEdit()">
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control form-control-sm" id="total_ticket_allocation"
                                                name="master[total_ticket_allocation]" value='<?= $ticket_allocation; ?>'
                                                readonly>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="mt_report_date" class="col-sm-4 col-form-label col-form-label-sm">Report
                                            Maintenance</label>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control form-control-sm" id="sbf_mt_report_date"
                                                name="master[mt_report_date]" value='<?= $tiket_reporting; ?>'>
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control form-control-sm" id="addon_mt_report_date"
                                                name="master[addon_mt_report_date]" value='0' onchange="totalReportDateEdit()">
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control form-control-sm" id="total_mt_report_date"
                                                name="master[total_mt_report_date]" value='<?= $tiket_reporting; ?>' readonly>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="assignment_note"
                                            class="col-sm-4 col-form-label col-form-label-sm">Preventive Maintenance</label>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control form-control-sm" id="sbf_preventive_mt_date"
                                                name="master[preventive_mt_date]" value='<?= $tiket_preventive; ?>'>
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control form-control-sm"
                                                id="addon_preventive_mt_date" name="master[addon_preventive_mt_date]" value='0'
                                                onchange="totalReportDateEdit()">
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control form-control-sm"
                                                id="total_preventive_mt_date" name="master[total_preventive_mt_date]"
                                                value='<?= $tiket_preventive; ?>'>
                                        </div>
                                    </div>

                                <?php } else { ?>
                                    <div class="row mb-3">
                                        <label for="total_ticket_allocation"
                                            class="col-sm-4 col-form-label col-form-label-sm">Total Tiket</label>
                                        <div class="col-sm-8">
                                            <?php if ($datatype == "edit") { ?>
                                                <input type="text" class="form-control form-control-sm" id="total_ticket_allocation"
                                                    name="master[total_ticket_allocation]" value='<?= $ticket_allocation; ?>' readonly>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Report digabung
                                            <i class="fas fa-question-circle" data-bs-toggle="popover" data-bs-trigger="focus"
                                                title="Informasi mengenai Report Maintenance dan Preventive Maintenance apakah digabung?"></i></label>
                                        <div class="col-sm-8">
                                            <?php if (isset($gabungan)) { ?>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="master[gabungan]" value="1"
                                                        onchange="gabunganReport('yes');" <?php if ($gabungan == 1) {
                                                                                                echo "checked";
                                                                                            } ?> disabled>
                                                    <label class="form-check-label">Yes</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="master[gabungan]" value="0"
                                                        onchange="gabunganReport('no');" <?php if ($gabungan == 0) {
                                                                                                echo "checked";
                                                                                            } ?> disabled>
                                                    <label class="form-check-label">No</label>
                                                </div>
                                            <?php } else { ?>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="master[gabungan]" value="1"
                                                        onchange="gabunganReport('yes');" disabled>
                                                    <label class="form-check-label">Yes</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="master[gabungan]" value="0"
                                                        onchange="gabunganReport('no');" disabled>
                                                    <label class="form-check-label">No</label>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="row mb-3" id="display_gabungan" style="display: none;">
                                        <label for="assignment_note" class="col-sm-4 col-form-label col-form-label-sm">Report
                                            Maintenance</label>
                                        <div class="col-sm-8">
                                            <?php if ($datatype == "edit") { ?>
                                                <input type="text" class="form-control form-control-sm" id="total_mt_report_date"
                                                    name="master[total_mt_report_date]" value='<?= $tiket_reporting; ?>'
                                                    onchange="totalReportDate('mt_report_date')" readonly>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="row mb-3" id="display_gabungan6" style="display: none;">
                                        <label for="assignment_note"
                                            class="col-sm-4 col-form-label col-form-label-sm">Preventive Maintenance</label>
                                        <div class="col-sm-8">
                                            <?php if ($datatype == "edit") { ?>
                                                <input type="text" class="form-control form-control-sm"
                                                    id="total_preventive_mt_date" name="master[total_preventive_mt_date]"
                                                    value='<?= $tiket_preventive; ?>'
                                                    onchange="totalReportDate('preventive_mt_date')" readonly>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="row mb-3" id="display_gabungan2" style="display: none;">
                                        <label for="assignment_note"
                                            class="col-sm-4 col-form-label col-form-label-sm">Preventive dan Maintenance
                                            Report</label>
                                        <div class="col-sm-8">
                                            <?php if ($datatype == "edit") { ?>
                                                <input type="text" class="form-control form-control-sm" id="total_pmr_date"
                                                    name="master[total_pmr_date]" value='<?= $total_pmr; ?>'
                                                    onchange="totalReportDate('pmr_date')" readonly>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="assignment_note" class="col-sm-4 col-form-label col-form-label-sm">Backup
                                            Config Maintenance</label>
                                        <div class="col-sm-8">
                                            <?php if ($datatype == "edit") { ?>
                                                <input type="text" class="form-control form-control-sm" id="total_backup_mt_date"
                                                    name="master[total_backup_mt_date]" value='<?= $backup_config; ?>'
                                                    onchange="totalReportDate('backup_mt_date')" readonly>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>


                                <div class="row mb-3">
                                    <label for="project_duration" class="col-sm-4 col-form-label col-form-label-sm">Project
                                        Duration</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "edit") { ?>
                                            <input type="text" class="form-control form-control-sm" id="project_duration"
                                                name="project_duration" value='<?= $project_estimation_duration ?>' readonly>
                                        <?php } ?>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-6">
                                <br>
                                <div class="row mb-3">
                                    <label for="mt_date_start" class="col-sm-4 col-form-label col-form-label-sm">Maintenance
                                        Start</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "edit") {
                                            if (isset($mt_date_start)) {
                                                if ($mt_date_start == null) { ?>
                                                    <input type="date" class="form-control form-control-sm" id="mt_date_start"
                                                        name="date[mt_date_start]" onchange="updateDurationActual('add')" readonly>
                                                <?php } else { ?>
                                                    <input type="date" class="form-control form-control-sm" id="mt_date_start"
                                                        name="date[mt_date_start]" onchange="updateDurationActual('add')"
                                                        value="<?php echo $mt_date_start; ?>" readonly>
                                                <?php }
                                            } else { ?>
                                                <input type="date" class="form-control form-control-sm" id="mt_date_start"
                                                    name="date[mt_date_start]" onchange="updateDurationActual('add')" readonly>
                                        <?php }
                                        } ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <br>
                                <div class="row mb-3">
                                    <label for="mt_date_end" class="col-sm-4 col-form-label col-form-label-sm">Maintenance
                                        End</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "edit") {
                                            if ($mt_date_end == null) { ?>
                                                <input type="date" class="form-control form-control-sm" id="mt_date_end"
                                                    name="date[mt_date_end]" onchange="updateDurationActual('add')" readonly>
                                            <?php } else { ?>
                                                <input type="date" class="form-control form-control-sm" id="mt_date_end"
                                                    name="date[mt_date_end]" onchange="updateDurationActual('add')"
                                                    value="<?php echo $mt_date_end; ?>" readonly>
                                        <?php }
                                        } ?>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="duration_actual" class="col-sm-4 col-form-label col-form-label-sm">Project
                                        Duration Actual</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "edit") { ?>
                                            <!-- <input type="date" class="form-control form-control-sm" id="duration_actual" value="" readonly> -->
                                            <input type="text" class="form-control form-control-sm" id="diff_duration"
                                                value='0 Days' readonly>
                                        <?php } ?>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="row mb-3">
                                    <label for="ikom_date_start" class="col-sm-4 col-form-label col-form-label-sm">IKOM
                                        Date</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "edit") {
                                            if ($mt_date_ikom == null) { ?>
                                                <input type="date" class="form-control form-control-sm" id="ikom_date_start"
                                                    name="date[ikom]" readonly>
                                            <?php } else { ?>
                                                <input type="date" class="form-control form-control-sm" id="ikom_date_start"
                                                    name="date[ikom]" value="<?php echo $mt_date_ikom; ?>" readonly>
                                        <?php }
                                        } ?>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="assignment_date"
                                        class="col-sm-4 col-form-label col-form-label-sm">Assignment Date</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "edit") {
                                            if ($mt_date_assignment == null) { ?>
                                                <input type="date" class="form-control form-control-sm" id="assignment_date"
                                                    name="date[assignment_date]" readonly>
                                            <?php } else { ?>
                                                <input type="date" class="form-control form-control-sm" id="assignment_date"
                                                    name="date[assignment_date]" value="<?php echo $mt_date_assignment; ?>" readonly>
                                        <?php }
                                        } ?>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Ontime <i
                                            class="fas fa-question-circle" data-bs-toggle="popover" data-bs-trigger="focus"
                                            title="Informasi mengenai GAP tanggal start maintenance dengan tanggal assignment ke PM. Jika berada di bulan yang sama maka Ontime (Yes), jika berbeda bulan artinya ada Tidak Ontime (No) / Ada GAP yang disebabkan Backdate Customer / Implementasi / Sales"></i></label>
                                    <div class="col-sm-8">
                                        <?php if (isset($ontime)) { ?>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="master[ontime]" value="1"
                                                    onchange="onTime('yes');" <?php if ($ontime == 0) {
                                                                                    echo "checked";
                                                                                } ?> disabled>
                                                <label class="form-check-label">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="master[ontime]" value="0"
                                                    onchange="onTime('no');" <?php if ($ontime == 1) {
                                                                                    echo "checked";
                                                                                } ?> disabled>
                                                <label class="form-check-label">No</label>
                                            </div>
                                        <?php } else { ?>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="master[ontime]" value="1"
                                                    onchange="onTime('yes');" disabled>
                                                <label class="form-check-label">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="master[ontime]" value="0"
                                                    onchange="onTime('no');" disabled>
                                                <label class="form-check-label">No</label>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="row mb-3" id="display_ontime" style="display: none;">
                                    <label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm"></label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "edit") {
                                            if (isset($ontime_notes)) { ?>
                                                <select class="form-control" name="master[ontime_notes]" id="ontime_notes" readonly>
                                                    <option value="<?php echo $ontime_notes; ?>"><?php echo $ontime_notes; ?>
                                                    </option>
                                                    <?php if ($ontime_notes == 'sales') { ?>
                                                        <option value="implementation">Implementation</option>
                                                        <option value="customer">Customer</option>
                                                        <option value="other">Other</option>
                                                    <?php } else if ($ontime_notes == 'implementation') { ?>
                                                        <option value="sales">Sales</option>
                                                        <option value="customer">Customer</option>
                                                        <option value="other">Other</option>
                                                    <?php } else if ($ontime_notes == 'customer') { ?>
                                                        <option value="sales">Sales</option>
                                                        <option value="implementation">Implementation</option>
                                                        <option value="other">Other</option>
                                                    <?php } else if ($ontime_notes == 'other') { ?>
                                                        <option value="sales">Sales</option>
                                                        <option value="implementation">Implementation</option>
                                                        <option value="customer">Customer</option>
                                                    <?php } ?>
                                                </select>
                                            <?php } else { ?>
                                                <select class="form-control" name="master[ontime_notes]" id="ontime_notes" readonly>
                                                    <option value="sales">Sales</option>
                                                    <option value="implementation">Implementation</option>
                                                    <option value="customer">Customer</option>
                                                    <option value="other">Other</option>
                                                </select>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="assignment_note"
                                        class="col-sm-4 col-form-label col-form-label-sm">Note</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "edit") {
                                            if (isset($notes)) { ?>
                                                <input type="text" class="form-control form-control-sm" id="assignment_note"
                                                    name="master[assignment_note]" value='<?php echo $notes; ?>' readonly>
                                            <?php } else { ?>
                                                <input type="text" class="form-control form-control-sm" id="assignment_note"
                                                    name="master[assignment_note]" value='' readonly>
                                        <?php }
                                        } ?>
                                    </div>
                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="row mb-3">
                                    <label for="kom_date" class="col-sm-4 col-form-label col-form-label-sm">KOM Date</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "edit") {
                                            if ($mt_date_kom == null) { ?>
                                                <input type="date" class="form-control form-control-sm" id="kom_date"
                                                    name="date[kom]" readonly>
                                            <?php } else { ?>
                                                <input type="date" class="form-control form-control-sm" id="kom_date"
                                                    name="date[kom]" value="<?php echo $mt_date_kom; ?>" readonly>
                                        <?php }
                                        } ?>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="m_addon_title" class="col-sm-12 col-form-label col-form-label-sm">Add On
                                        :</label>

                                </div>
                                <?php if ($total_addon > 0) { ?>
                                    <?php while ($data_addon = $get_addonn[1]->fetch_assoc()) { ?>
                                        <div class="row mb-3">

                                            <div class="col-sm-12">
                                                <input type="text" class="form-control form-control-sm" id="m_addon_title"
                                                    name="m_addon_title" value="<?= $data_addon['addon_title'] ?>" readonly>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } else { ?>
                                    <div class="row mb-3">
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control form-control-sm" id="m_addon_title"
                                                name="m_addon_title" value="" readonly>
                                        </div>
                                    </div>
                                <?php } ?>

                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Open Item
                                        Implementation <i class="fas fa-question-circle" data-bs-toggle="popover"
                                            data-bs-trigger="focus"
                                            title="Apakah project ini merupakan Open Item dari Implementation?"></i></label>
                                    <div class="col-sm-8">
                                        <?php if (isset($openitem)) { ?>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="master[openitem]" value="1"
                                                    onchange="OpenItem('yes');" <?php if ($openitem == 1) {
                                                                                    echo "checked";
                                                                                } ?> disabled>
                                                <label class="form-check-label">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="master[openitem]" value="0"
                                                    onchange="OpenItem('no');" <?php if ($openitem == 0) {
                                                                                    echo "checked";
                                                                                } ?> disabled>
                                                <label class="form-check-label">No</label>
                                            </div>
                                        <?php } else { ?>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="master[openitem]" value="1"
                                                    onchange="OpenItem('yes');" disabled>
                                                <label class="form-check-label">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="master[openitem]" value="0"
                                                    onchange="OpenItem('no');" disabled>
                                                <label class="form-check-label">No</label>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="row mb-3" id="display_openitem" style="display: none;">
                                    <label for="assignment_note"
                                        class="col-sm-4 col-form-label col-form-label-sm">Note</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "edit") {
                                            if (isset($openitem_note)) { ?>
                                                <input type="text" class="form-control form-control-sm" id="openitem_note"
                                                    name="master[openitem_note]" value='<?php echo $openitem_note; ?>' readonly>
                                            <?php } else { ?>
                                                <input type="text" class="form-control form-control-sm" id="openitem_note"
                                                    name="master[openitem_note]" value='' readonly>
                                        <?php }
                                        } ?>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <hr>
                            </div>
                            <?php if (isset($checking[0]['order_number'])) { ?>
                                <div class="col-md-4 border-right" id="display_gabungan3" style="display: none;">
                                    <div class="row" id="add_mt_report_date">
                                        <div class="row mb-3">
                                            <b>Maintenance Report</b>
                                            <div class="col-sm-1">
                                                <b>#</b>
                                            </div>
                                            <div class="col-sm-5">
                                                <b>Date</b>
                                            </div>
                                            <div class="col-sm-6">
                                                <b>Button</b>
                                            </div>
                                        </div>

                                        <?php for ($i = 1; $i <= $checking[0]['total_mt_report_date']; $i++) { ?>
                                            <div class="row" id="input_mt_report_date">
                                                <div class="col-sm-1">
                                                    <div class="row mb-3">
                                                        <label for="mt_report_date"
                                                            class="col-sm-12 col-form-label col-form-label-sm"><?= $i ?></label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-5">
                                                    <div class="row mb-3">
                                                        <div class="col-sm-12">
                                                            <input type="date" class="form-control form-control-sm"
                                                                id="mt_report_date" name="date[mt_report_date][]"
                                                                value="<?= isset($data_result_date['mt_report_date_' . $i]) ? $data_result_date['mt_report_date_' . $i] : '' ?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="row mb-3">
                                                        <div class="col-sm-12">
                                                            <input type="file" class="form-control form-control-sm ajax-upload"
                                                                name="upload_file[]" data-type="mt_report_date" data-row="<?= $i ?>">
                                                            <div class="upload-status"></div> <!-- Status Upload -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="col-md-4 border-right">
                                    <div class="row" id="add_backup_mt_date">                                           
                                            <div class="row mb-3">
                                                <b>Backup Config</b>
                                                <div class="col-sm-1">
                                                    <b>#</b>
                                                </div>
                                                <div class="col-sm-5">
                                                    <b>Date</b>
                                                </div>
                                                <div class="col-sm-6">
                                                    <b>Button</b>
                                                </div>
                                            </div>

                                        <?php for ($i = 1; $i <= $checking[0]['total_backup_mt_date']; $i++) { ?>
                                            <div class="row" id="input_backup_mt_date">
                                                <div class="col-sm-1">
                                                    <div class="row mb-3">
                                                        <label for="backup_mt_date"
                                                            class="col-sm-12 col-form-label col-form-label-sm"><?= $i ?></label>
                                                    </div>
                                                </div>

                                                <div class="col-sm-5">
                                                    <div class="row mb-3">
                                                        <div class="col-sm-12">
                                                            <input type="date" class="form-control form-control-sm"
                                                                id="backup_mt_date" name="date[backup_mt_date][]"
                                                                value="<?= isset($data_result_date['backup_mt_date_' . $i]) ? $data_result_date['backup_mt_date_' . $i] : '' ?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="row mb-3">
                                                        <div class="col-sm-12">
                                                            <input type="file" class="form-control form-control-sm ajax-upload"
                                                                name="upload_file[]" data-type="backup_mt_date" data-row="<?= $i ?>">
                                                            <div class="upload-status"></div> <!-- Status Upload -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="col-md-4 border-right" id="display_gabungan4" style="display: none;">
                                    <div class="row" id="add_preventive_mt_date">    
                                            <div class="row mb-3">
                                                <b>Preventive Maintenance Report</b>
                                                <div class="col-sm-1">
                                                    <b>#</b>
                                                </div>
                                                <div class="col-sm-5">
                                                    <b>Date</b>
                                                </div>
                                                <div class="col-sm-6">
                                                    <b>Button</b>
                                                </div>
                                            </div>

                                        <?php for ($i = 1; $i <= $checking[0]['total_preventive_mt_date']; $i++) { ?>
                                            <div class="row" id="input_mt_report_date">
                                                <div class="col-sm-1">
                                                    <div class="row mb-3">
                                                        <label for="preventive_mt_date"
                                                            class="col-sm-12 col-form-label col-form-label-sm"><?= $i ?></label>
                                                    </div>
                                                </div>

                                                <div class="col-sm-5">
                                                    <div class="row mb-3">
                                                        <div class="col-sm-12">
                                                            <input type="date" class="form-control form-control-sm"
                                                                id="preventive_mt_date" name="date[preventive_mt_date][]"
                                                                value="<?= isset($data_result_date['preventive_mt_date_' . $i]) ? $data_result_date['preventive_mt_date_' . $i] : '' ?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="row mb-3">
                                                        <div class="col-sm-12">
                                                            <input type="file" class="form-control form-control-sm ajax-upload"
                                                                name="upload_file[]" data-type="preventive_mt_date" data-row="<?= $i ?>">
                                                            <div class="upload-status"></div> <!-- Status Upload -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="col-md-6 border-right" id="display_gabungan5" style="display: none;">
                                    <div class="row" id="add_pmr_date">
                                            <div class="row mb-3">
                                                <b>Preventive dan Maintenance Report</b>
                                                <div class="col-sm-1">
                                                    <b>#</b>
                                                </div>
                                                <div class="col-sm-5">
                                                    <b>Date</b>
                                                </div>
                                                <div class="col-sm-6">
                                                    <b>Button</b>
                                                </div>
                                            </div>

                                        <?php for ($i = 1; $i <= $checking[0]['total_pmr_date']; $i++) { ?>
                                            <div class="row" id="input_pmr_date">
                                                <div class="col-sm-1">
                                                    <div class="row mb-3">
                                                        <label for="pmr_date"
                                                            class="col-sm-12 col-form-label col-form-label-sm"><?= $i ?></label>
                                                    </div>
                                                </div>

                                                <div class="col-sm-5">
                                                    <div class="row mb-3">
                                                        <div class="col-sm-12">
                                                            <input type="date" class="form-control form-control-sm" id="pmr_date"
                                                                name="date[pmr_date][]"
                                                                value="<?= isset($data_result_date['pmr_date_' . $i]) ? $data_result_date['pmr_date_' . $i] : '' ?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="row mb-3">
                                                        <div class="col-sm-12">
                                                            <input type="file" class="form-control form-control-sm ajax-upload"
                                                                name="upload_file[]" data-type="pmr_date" data-row="<?= $i ?>">
                                                            <div class="upload-status"></div> <!-- Status Upload -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="col-md-4 border-right" id="display_gabungan3" style="display: none;">
                                    <div class="row" id="add_mt_report_date">
                                        <div class="row mb-3">
                                            <div class="col-sm-6">
                                                <b>Maintenance Report</b>
                                            </div>
                                            <div class="col-sm-6">
                                                <b>Date</b>
                                            </div>
                                        </div>
                                        <div class="row" id="input_mt_report_date">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 border-right">
                                    <div class="row" id="add_backup_mt_date">
                                        <div class="row mb-3">
                                            <div class="col-sm-6">
                                                <b>Backup Config</b>
                                            </div>
                                            <div class="col-sm-6">
                                                <b>Date</b>
                                            </div>
                                        </div>
                                        <div class="row" id="input_backup_mt_date">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4" id="display_gabungan4" style="display: none;">
                                    <div class="row" id="add_preventive_mt_date">
                                        <div class="row mb-3">
                                            <div class="col-sm-6">
                                                <b>Preventive Maintenance</b>
                                            </div>
                                            <div class="col-sm-6">
                                                <b>Date</b>
                                            </div>
                                        </div>

                                        <div class="row" id="input_preventive_mt_date">

                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-6 border-right" id="display_gabungan5" style="display: none;">
                                    <div class="row" id="add_pmr_date">
                                        <div class="row mb-3">
                                            <div class="col-sm-6">
                                                <b>Preventive & Maintenance Report</b>
                                            </div>
                                            <div class="col-sm-6">
                                                <b>Date</b>
                                            </div>
                                        </div>
                                        <div class="row" id="input_pmr_date">
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
} ?>
        <input type="submit" class="btn btn-secondary" name="cancel" value="Cancel">
        <?php if (isset($datatype) && $datatype == 'edit') { ?>
            <input type="submit" class="btn btn-primary" name="saveReport" value="Save">
            <!-- Trigger Modal Submit -->
            <button type="button" class="btn btn-primary mt-1" data-toggle="modal"
                data-target="#submitModal">Submit</button>
        <?php } elseif (isset($datatype) && $datatype == 'add') { ?>
            <input type="submit" class="btn btn-primary" name="addReport" value="Save">
            <!-- Trigger Modal Submit -->
            <button type="button" class="btn btn-primary mt-1" data-toggle="modal"
                data-target="#submitModal">Submit</button>
        <?php } ?>
        <!-- Modal -->
        <div class="modal fade" id="submitModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Submit Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 text-center">
                                    Apakah anda yakin data ini akan di submit ? <br />
                                </div>
                                <div class="col-12 text-center">
                                    <img src="components/modules/resource_assignment_mt/img/checklist.gif"
                                        alt="Girl in a jacket" width="100" height="100" data-aos="fade-up">
                                </div>
                                <div class="col-12 text-center">
                                    <b>Note : Setelah di submit data tidak dapat di hapus</b>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary" name="submitResource" value="Submit">
                    </div>
                </div>
            </div>
        </div>

        </form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $(".ajax-upload").on("change", function () {
            var fileInput = $(this)[0];
            var file = fileInput.files[0];
            var fileType = $(this).data("type"); 
            var rowNumber = $(this).data("row"); 
            var statusDiv = $(this).closest(".row").find(".upload-status"); 

            if (file) {
                var formData = new FormData();
                formData.append("file", file);
                formData.append("file_type", fileType);
                formData.append("row_number", rowNumber);

                statusDiv.html("Uploading...");

                $.ajax({
                    url: "upload.php",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: "json",
                    success: function (response) {
                        if (response.status === "success") {
                            statusDiv.html("<span style='color: green;'>Upload sukses: " + response.file_name + "</span>");
                        } else {
                            statusDiv.html("<span style='color: red;'>Upload gagal!</span>");
                        }
                    },
                    error: function () {
                        statusDiv.html("<span style='color: red;'>Error pada server!</span>");
                    }
                });
            }
        });
    });
</script>

        <script>
            updateDurationActual('edit');

            $("#project_code").on('change', function() {
                var project_code = $(this).val();
                window.location = window.location.pathname + "?mod=maintenance_date" + "&act=add&project_code=" +
                    project_code;
            })

            document.querySelector('form').addEventListener('submit', function(event) {
                const selected = document.querySelector('input[name="master[gabungan]"]:checked');
                if (!selected) {
                    alert('Please select an option.');
                    event.preventDefault(); // Prevent form submission
                }
            });

            document.querySelector('form').addEventListener('submit', function(event) {
                const selected = document.querySelector('input[name="master[ontime]"]:checked');
                if (!selected) {
                    alert('Please select an option.');
                    event.preventDefault(); // Prevent form submission
                }
            });

            document.querySelector('form').addEventListener('submit', function(event) {
                const selected = document.querySelector('input[name="master[openitem]"]:checked');
                if (!selected) {
                    alert('Please select an option.');
                    event.preventDefault(); // Prevent form submission
                }
            });

            function addMonths(plusMonth, date) {
                newDate = new Date(date);
                newDate.setMonth(newDate.getMonth() + plusMonth);

                const year = newDate.getFullYear();
                const month = String(newDate.getMonth() + 1).padStart(2, '0');

                return `${year}-${month}-05`;
            }

            function fillMonth() {
                const startDate = new Date($('#mt_date_start').val());
                const endDate = new Date($('#mt_date_end').val());
                const totalReportDate = $('#total_mt_report_date').val();
                const totalPrevDate = $('#total_preventive_mt_date').val();
                const totalBackupDate = $('#total_backup_mt_date').val();
                const totalPMRDate = $('#total_pmr_date').val();
                startDate.setDate(5);

                const yearsDifference = endDate.getFullYear() - startDate.getFullYear();
                const monthsDifference = endDate.getMonth() - startDate.getMonth();
                const totalMonth = (yearsDifference * 12) + monthsDifference;

                const finalReportDate = Math.floor(totalMonth / totalReportDate);
                const finalPrevDate = Math.floor(totalMonth / totalPrevDate);
                const finalBackupDate = Math.floor(totalMonth / totalBackupDate);
                const finalPMRDate = Math.floor(totalMonth / totalPMRDate);

                const inputsR = document.querySelectorAll('#mt_report_date');
                inputsR.forEach((input, index) => {
                    index++;
                    report_date = addMonths((index * finalReportDate), startDate);
                    input.value = report_date;
                });

                const inputsP = document.querySelectorAll('#preventive_mt_date');
                inputsP.forEach((input, index) => {
                    index++;
                    prev_date = addMonths((index * finalPrevDate), startDate);
                    input.value = prev_date;
                });

                const inputsT = document.querySelectorAll('#backup_mt_date');
                inputsT.forEach((input, index) => {
                    index++;
                    backup_date = addMonths((index * finalBackupDate), startDate);
                    input.value = backup_date;
                });

                const inputsQ = document.querySelectorAll('#pmr_date');
                inputsQ.forEach((input, index) => {
                    index++;
                    pmr_date = addMonths((index * finalPMRDate), startDate);
                    input.value = pmr_date;
                });
            }

            // $("#mt_date_start, #mt_date_end").on('change', function() {

            // })

            function totalReportDate(idname) {
                const total = document.getElementById("total_" + idname).value;
                addRows(idname, total);
                fillMonth();
            }

            function totalReportDateEdit() {
                const sbfTA = parseInt(document.getElementById("sbf_ticket_allocation").value);
                const addonTA = parseInt(document.getElementById("addon_ticket_allocation").value);
                const totalTA = sbfTA + addonTA;
                document.getElementById("total_ticket_allocation").value = totalTA;

                const sbfRD = parseInt(document.getElementById("sbf_mt_report_date").value);
                const addonRD = parseInt(document.getElementById("addon_mt_report_date").value);
                const totalRD = sbfRD + addonRD;
                document.getElementById("total_mt_report_date").value = totalRD;
                addRows("mt_report_date", totalRD);

                const sbfPMD = parseInt(document.getElementById("sbf_preventive_mt_date").value);
                const addonPMD = parseInt(document.getElementById("addon_preventive_mt_date").value);
                const totalPMD = sbfPMD + addonPMD;
                document.getElementById("total_preventive_mt_date").value = totalPMD;
                addRows("preventive_mt_date", totalPMD);

                document.getElementById("total_backup_mt_date").value = totalBC;
                addRows("backup_mt_date", totalBC);

                document.getElementById("total_pmr_date").value = totalPMR;
                addRows("pmr_date", totalPMR);
            }

            function addRows(idname, total) {
                const inputs = document.querySelectorAll('#' + idname);
                let totalSekarang = 0;
                inputs.forEach((input, index) => {
                    totalSekarang++;
                });

                if (total != totalSekarang) {
                    if (total >= totalSekarang) {
                        for (var i = (totalSekarang + 1); i <= total; i++) {
                            html_report_date = '<div class="row" id="input_' + idname +
                                '"><div class="col-sm-4"><div class="row mb-3"><label for="' + idname +
                                '" class="col-sm-12 col-form-label col-form-label-sm">' + i +
                                '</label></div></div><div class="col-sm-6"><div class="row mb-3"><div class="col-sm-12"><input type="date" class="form-control form-control-sm" id="' +
                                idname + '" name="date[' + idname + '][]" required></div></div></div></div>';
                            $('#add_' + idname + '').append(html_report_date);
                        }

                    } else {
                        start = totalSekarang - total;

                        for (var i = 1; i <= start; i++) {
                            const inputElements = document.querySelectorAll('#input_' + idname);
                            const lastInputElement = inputElements[inputElements.length - 1];
                            lastInputElement.remove();
                        }

                    }
                }
            }

            function gabunganReport(stats) {
                if (stats == 'yes') {
                    document.getElementById("display_gabungan").style.display = 'none';
                    document.getElementById("display_gabungan2").style.display = '';
                    document.getElementById("display_gabungan3").style.display = 'none';
                    document.getElementById("display_gabungan4").style.display = 'none';
                    document.getElementById("display_gabungan5").style.display = '';
                    document.getElementById("display_gabungan6").style.display = 'none';
                } else {
                    document.getElementById("display_gabungan").style.display = '';
                    document.getElementById("display_gabungan2").style.display = 'none';
                    document.getElementById("display_gabungan3").style.display = '';
                    document.getElementById("display_gabungan4").style.display = '';
                    document.getElementById("display_gabungan5").style.display = 'none';
                    document.getElementById("display_gabungan6").style.display = '';
                }
            }

            function gabunganParent(stats) {
                if (stats == 'yes') {
                    document.getElementById("project_gabungan_parent").style.display = '';
                } else {
                    document.getElementById("project_gabungan_parent").style.display = 'none';
                }
            }

            window.onload = function() {
                <?php if (isset($gabungan) && $gabungan == 1) { ?>
                    gabunganReport('yes');
                <?php } elseif (isset($gabungan) && $gabungan == 0) { ?>
                    gabunganReport('no');
                <?php } ?>
                <?php if (isset($gabungan_parent) && $gabungan_parent == 1) { ?>
                    gabunganParent('yes');
                <?php } elseif (isset($gabungan_parent) && $gabungan_parent == 0) { ?>
                    gabunganParent('no');
                <?php } ?>
            };

            function OpenItem(stat) {
                if (stat == 'yes') {
                    document.getElementById("display_openitem").style.display = '';
                } else {
                    document.getElementById("display_openitem").style.display = 'none';
                }
            }

            function onTime(status) {
                if (status == 'yes') {
                    document.getElementById("display_ontime").style.display = 'none';
                } else {
                    document.getElementById("display_ontime").style.display = '';
                }
            }

            function updateDurationActual(act) {
                if (act === 'add') {
                    totalReportDate('mt_report_date');
                    totalReportDate('preventive_mt_date');
                    totalReportDate('backup_mt_date');
                    totalReportDate('pmr_date');
                    fillMonth();
                }

                const endDate = new Date($('#mt_date_end').val());
                const startDate = new Date($('#mt_date_start').val());

                var formattedDate = '';
                if (startDate.toString() === 'Invalid Date' || endDate.toString() === 'Invalid Date') {
                    formattedDate = "0 Years 0 Months 0 Days";
                } else {
                    formattedDate = formatDuration(startDate, endDate);
                }

                document.getElementById("diff_duration").value = formattedDate;
            }


            function formatDuration(startDate, endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);

                let years = end.getFullYear() - start.getFullYear();
                let months = end.getMonth() - start.getMonth();
                let days = end.getDate() - start.getDate();

                if (days < 0) {
                    months--;
                    days += new Date(end.getFullYear(), end.getMonth(), 0).getDate();
                }

                if (months < 0) {
                    years--;
                    months += 12;
                }

                return `${years} Year${years !== 1 ? "s" : ""} ${months} Month${months !== 1 ? "s" : ""} ${days} Day${days !== 1 ? "s" : ""}`;
            }
        </script>

        <script type="text/javascript">
            $(document).ready(function() {
                $('#parent').select2({
                    placeholder: 'Pilih Project Code',
                    allowClear: true
                });
            });
        </script>