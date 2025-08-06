<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
<?php
global $DBMTdate;
$DBMTdate = get_conn("MAINTENANCE_DATE");
$DBSB = get_conn("SERVICE_BUDGET");
$DBWRIKE = get_conn("WRIKE_INTEGRATE");
$DBCR = get_conn("change_request");
$project_code = $_GET['project_code'];
$order_number = isset($_GET['order_number']) ? $_GET['order_number'] : '';
$queryMaster = "SELECT * FROM sa_master_maintenance_date WHERE project_code ='$project_code' and order_number ='$order_number'";

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

// if ($datatype == 'edit') {
// $project_code = $_GET['project_code'];
// $query = "SELECT * FROM sa_maintenance_date WHERE project_code = '". $project_code ."';";
// $data_result = $DBMTdate->get_sqlV2($query);

// if($data_result[2] > 0){
//     while($row = $data_result[1]->fetch_assoc()){
//         $data_result_date[$row['id_date']] = array(
//             "start_date" => $row['start_date'],
//             "end_date" => $row['end_date'],
//         );
//     }
// }

// }
if ($datatype == 'add') {
    // $get_kp = $DBWRIKE->get_sqlV2("SELECT DISTINCT project_code, project_type FROM sa_wrike_project_list WHERE project_type = 'MSI Project Maintenance' AND project_code IS NOT NULL AND flag_mt_date = 0 ORDER BY project_code ASC");


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

                                if (isset($_GET['project_code'])) {
                                    $get_project_code = $DBWRIKE->get_sqlV2("SELECT * FROM sa_wrike_project_list WHERE project_type = 'MSI Project Maintenance' AND project_code LIKE '%" . $_GET['project_code'] . "%'");
                                    // if($project_code[0]['flag_mt_date'] == 1){
                                    //     global $ALERT;
                                    //     return $ALERT->msgcustom('danger', 'Project sudah diinsert, lakukan edit pada menu maintenance report');
                                    // }
                                    $project_type = isset($get_project_code[0]['project_type']) ? $get_project_code[0]['project_type'] : '';

                                    $get_type_service = $DBSB->get_sqlV2("select * from sa_mst_type_of_service where service_type = '2'");
                                    while ($row = $get_type_service[1]->fetch_assoc()) {
                                        $type_services[$row['tos_id']] = $row['tos_name'];
                                    }


                                    $get_project = $DBSB->get_sqlV2("select a.project_id, b.tos_id, b.project_estimation, b.project_estimation_id from sa_trx_project_list a inner join sa_trx_project_implementations b on a.project_id = b.project_id where b.service_type = '2' and project_code = '" . $_GET['project_code'] . "'");

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

                                    $get_tiket = $DBSB->get_sqlV2("select * from sa_trx_project_list where project_code = '" . $project_code . "' and order_number = '" . $order_number . "'");

                                    if ($get_tiket[2] > 0  && $get_tiket[0]['reporting'] != '') {
                                        $tiket = json_decode($get_tiket[0]['reporting'], true);
                                        $tiket_reporting = $tiket['reporting']['plan'];
                                        $tiket_preventive = $tiket['preventive']['plan'];
                                        $ticket_allocation = $tiket['ticket']['plan'];
                                        $backup_config = $tiket_preventive + 1;
                                    }

                                    $estimationName = isset($estimation_name[$project_estimation_id]) ? $estimation_name[$project_estimation_id] : '';
                                }
                                ?>
                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Project
                                        Code</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "add") { ?>
                                            <select class="form-control" name="project_code" id="project_code" required readonly>
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
                                            <input type="text" class="form-control form-control-sm" id="service_type" name="service_type" value='<?= $project_type; ?>' readonly>
                                        <?php } else { ?>
                                            <input type="text" class="form-control form-control-sm" id="service_type" name="service_type" value='' readonly>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="service_type" class="col-sm-4 col-form-label col-form-label-sm">Service
                                        Type</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "add") { ?>
                                            <input type="text" class="form-control form-control-sm" id="service_type" name="service_type" value='<?= $service_type ?>' readonly>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="order_number" class="col-sm-4 col-form-label col-form-label-sm">Order
                                        Number</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "add") { ?>
                                            <input type="text" class="form-control form-control-sm" id="order_number" name="master[order_number]" value='<?= $order_number; ?>' readonly>
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
                            </div>

                            <div class="col-md-6">

                                <?php if ($_SESSION['Microservices_UserEmail'] == 'aryo.bimo@mastersystem.co.id') { ?>
                                    <div class="row mb-3">
                                        <label for="ticket_allocation" class="col-sm-4 col-form-label col-form-label-sm">Total
                                            Tiket <i class="fas fa-question-circle" data-bs-toggle="popover" data-bs-trigger="focus" title="Tiket dari SBF (Kolom Ke-1), Tiket addon (Kolom Ke-2), , Total tiket (Kolom Ke-3)"></i></label>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control form-control-sm" id="sbf_ticket_allocation" name="master[ticket_allocation]" value='<?= $ticket_allocation; ?>'>
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control form-control-sm" id="addon_ticket_allocation" name="master[addon_ticket_allocation]" value='0' onchange="totalReportDateEdit()">
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control form-control-sm" id="total_ticket_allocation" name="master[total_ticket_allocation]" value='<?= $ticket_allocation; ?>' readonly>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="mt_report_date" class="col-sm-4 col-form-label col-form-label-sm">Report
                                            Maintenance</label>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control form-control-sm" id="sbf_mt_report_date" name="master[mt_report_date]" value='<?= $tiket_reporting; ?>'>
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control form-control-sm" id="addon_mt_report_date" name="master[addon_mt_report_date]" value='0' onchange="totalReportDateEdit()">
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control form-control-sm" id="total_mt_report_date" name="master[total_mt_report_date]" value='<?= $tiket_reporting; ?>' readonly>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="assignment_note" class="col-sm-4 col-form-label col-form-label-sm">Preventive Maintenance</label>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control form-control-sm" id="sbf_preventive_mt_date" name="master[preventive_mt_date]" value='<?= $tiket_preventive; ?>'>
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control form-control-sm" id="addon_preventive_mt_date" name="master[addon_preventive_mt_date]" value='0' onchange="totalReportDateEdit()">
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control form-control-sm" id="total_preventive_mt_date" name="master[total_preventive_mt_date]" value='<?= $tiket_preventive; ?>'>
                                        </div>
                                    </div>

                                <?php } else { ?>
                                    <div class="row mb-3">
                                        <label for="total_ticket_allocation" class="col-sm-4 col-form-label col-form-label-sm">Total Tiket</label>
                                        <div class="col-sm-8">
                                            <?php if ($datatype == "add") { ?>
                                                <input type="text" class="form-control form-control-sm" id="total_ticket_allocation" name="master[total_ticket_allocation]" value='<?= $ticket_allocation; ?>'>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Report digabung <i class="fas fa-question-circle" data-bs-toggle="popover" data-bs-trigger="focus" title="Informasi mengenai Report Maintenance dan Preventive Maintenance apakah digabung? Jika ya maka cukup mengisi baseline report saja"></i></label>
                                        <div class="col-sm-8">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="master[gabungan]" value="1" onchange="gabunganReport('yes');" required>
                                                <label class="form-check-label">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="master[gabungan]" value="0" onchange="gabunganReport('no');" required>
                                                <label class="form-check-label">No</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3" id="display_gabungan" style="display: none;">
                                        <label for="assignment_note" class="col-sm-4 col-form-label col-form-label-sm">Report
                                            Maintenance</label>
                                        <div class="col-sm-8">
                                            <?php if ($datatype == "add") { ?>
                                                <input type="text" class="form-control form-control-sm" id="total_mt_report_date" name="master[total_mt_report_date]" value='<?= $tiket_reporting; ?>' onchange="totalReportDate('mt_report_date')">
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="row mb-3" id="display_gabungan6" style="display: none;">
                                        <label for="assignment_note" class="col-sm-4 col-form-label col-form-label-sm">Preventive Maintenance</label>
                                        <div class="col-sm-8">
                                            <?php if ($datatype == "add") { ?>
                                                <input type="text" class="form-control form-control-sm" id="total_preventive_mt_date" name="master[total_preventive_mt_date]" value='<?= $tiket_preventive; ?>' onchange="totalReportDate('preventive_mt_date')">
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="row mb-3" id="display_gabungan2" style="display: none;">
                                        <label for="assignment_note" class="col-sm-4 col-form-label col-form-label-sm">Preventive dan Maintenance Report</label>
                                        <div class="col-sm-8">
                                            <?php if ($datatype == "add") { ?>
                                                <input type="text" class="form-control form-control-sm" id="total_pmr_date" name="master[total_pmr_date]" value='<?= $tiket_reporting; ?>' onchange="totalReportDate('pmr_date')">
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="assignment_note" class="col-sm-4 col-form-label col-form-label-sm">Backup Config Maintenance</label>
                                        <div class="col-sm-8">
                                            <?php if ($datatype == "add") { ?>
                                                <input type="text" class="form-control form-control-sm" id="total_backup_mt_date" name="master[total_backup_mt_date]" value='<?= $backup_config; ?>' onchange="totalReportDate('backup_mt_date')">
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>


                                <div class="row mb-3">
                                    <label for="project_duration" class="col-sm-4 col-form-label col-form-label-sm">Project
                                        Duration</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "add") { ?>
                                            <input type="text" class="form-control form-control-sm" id="project_duration" name="project_duration" value='<?= $project_estimation_duration ?>' readonly>
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
                                        <?php if ($datatype == "add") { ?>
                                            <input type="date" class="form-control form-control-sm" id="mt_date_start" name="date[mt_date_start]" onchange="updateDurationActual('add')">
                                        <?php } ?>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-6">
                                <br>
                                <div class="row mb-3">
                                    <label for="mt_date_end" class="col-sm-4 col-form-label col-form-label-sm">Maintenance
                                        End</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "add") { ?>
                                            <input type="date" class="form-control form-control-sm" id="mt_date_end" name="date[mt_date_end]" onchange="updateDurationActual('add')">
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="duration_actual" class="col-sm-4 col-form-label col-form-label-sm">Project
                                        Duration Actual</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "add") { ?>
                                            <!-- <input type="date" class="form-control form-control-sm" id="duration_actual" value="" readonly> -->
                                            <input type="text" class="form-control form-control-sm" id="diff_duration" value='0 Days' readonly>
                                        <?php } ?>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="row mb-3">
                                    <label for="ikom_date_start" class="col-sm-4 col-form-label col-form-label-sm">IKOM
                                        Date</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "add") { ?>
                                            <input type="date" class="form-control form-control-sm" id="ikom_date_start" name="date[ikom]">
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="assignment_date" class="col-sm-4 col-form-label col-form-label-sm">Assignment Date</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "add") { ?>
                                            <input type="date" class="form-control form-control-sm" id="assignment_date" name="date[assignment_date]">
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Ontime <i class="fas fa-question-circle" data-bs-toggle="popover" data-bs-trigger="focus" title="Informasi mengenai GAP tanggal start maintenance dengan tanggal assignment ke PM. Jika berada di bulan yang sama maka Ontime (Yes), jika berbeda bulan artinya ada Tidak Ontime (No) / Ada GAP yang disebabkan Backdate Customer / Implementasi / Sales"></i></label>
                                    <div class="col-sm-8">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="master[ontime]" value="1" onchange="onTime('yes');" required>
                                            <label class="form-check-label">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="master[ontime]" value="0" onchange="onTime('no');" required>
                                            <label class="form-check-label">No</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3" id="display_ontime" style="display: none;">
                                    <label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm"></label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "add") { ?>
                                            <select class="form-control" name="master[ontime_notes]" id="ontime_notes">
                                                <option value="sales">Sales</option>
                                                <option value="implementation">Implementation</option>
                                                <option value="customer">Customer</option>
                                                <option value="other">Other</option>
                                            </select>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="assignment_note" class="col-sm-4 col-form-label col-form-label-sm">Note</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "add") { ?>
                                            <input type="text" class="form-control form-control-sm" id="assignment_note" name="master[assignment_note]" value=''>
                                        <?php } ?>
                                    </div>
                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="row mb-3">
                                    <label for="kom_date" class="col-sm-4 col-form-label col-form-label-sm">KOM Date</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "add") { ?>
                                            <input type="date" class="form-control form-control-sm" id="kom_date" name="date[kom]">
                                        <?php } ?>
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
                                                <input type="text" class="form-control form-control-sm" id="m_addon_title" name="m_addon_title" value="<?= $data_addon['addon_title'] ?>" readonly>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } else { ?>
                                    <div class="row mb-3">
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control form-control-sm" id="m_addon_title" name="m_addon_title" value="" readonly>
                                        </div>
                                    </div>
                                <?php } ?>

                                <div class="row mb-3">
                                    <label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Open Item Implementation <i class="fas fa-question-circle" data-bs-toggle="popover" data-bs-trigger="focus" title="Apakah project ini merupakan Open Item dari Implementation?"></i></label>
                                    <div class="col-sm-8">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="master[openitem]" value="1" onchange="OpenItem('yes');" required>
                                            <label class="form-check-label">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="master[openitem]" value="0" onchange="OpenItem('no');" required>
                                            <label class="form-check-label">No</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3" id="display_openitem" style="display: none;">
                                    <label for="assignment_note" class="col-sm-4 col-form-label col-form-label-sm">Note</label>
                                    <div class="col-sm-8">
                                        <?php if ($datatype == "add") { ?>
                                            <input type="text" class="form-control form-control-sm" id="openitem_note" name="master[openitem_note]" value=''>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <hr>
                            </div>

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
                        </div>
                    </div>
                </div>
            </div>

        </div>
    <?php
}
if ($datatype == 'edit') {
    $query = "SELECT id_date, date FROM sa_maintenance_date WHERE project_code ='$project_code' and order_number ='$order_number'";
    $data_result = $DBMTdate->get_sqlV2($query);

    $data_result_date = array();
    if ($data_result[2] > 0) {
        while ($row = $data_result[1]->fetch_assoc()) {
            $data_result_date[$row['id_date']] = $row['date'];
        }
    }

    $mt_start = isset($data_result_date['mt_date_start']) ? $data_result_date['mt_date_start'] : '';
    $mt_end = isset($data_result_date['mt_date_end']) ? $data_result_date['mt_date_end'] : '';
    $ikom = isset($data_result_date['ikom']) ? $data_result_date['ikom'] : '';
    $kom = isset($data_result_date['kom']) ? $data_result_date['kom'] : '';
    $assignment_date = isset($data_result_date['assignment_date']) ? $data_result_date['assignment_date'] : '';

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

                                    if (isset($_GET['project_code'])) {
                                        $get_project_code = $DBWRIKE->get_sqlV2("SELECT * FROM sa_wrike_project_list WHERE project_type = 'MSI Project Maintenance' AND project_code LIKE '%" . $_GET['project_code'] . "%'");

                                        $project_type = isset($get_project_code[0]['project_type']) ? $get_project_code[0]['project_type'] : '';

                                        $get_type_service = $DBSB->get_sqlV2("select * from sa_mst_type_of_service where service_type = '2'");
                                        while ($row = $get_type_service[1]->fetch_assoc()) {
                                            $type_services[$row['tos_id']] = $row['tos_name'];
                                        }


                                        $get_project = $DBSB->get_sqlV2("select a.project_id, b.tos_id, b.project_estimation, b.project_estimation_id from sa_trx_project_list a inner join sa_trx_project_implementations b on a.project_id = b.project_id where b.service_type = '2' and project_code = '" . $_GET['project_code'] . "'");

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

                                        // $get_cr = $DBCR->get_sqlV2("select project_code, ticket_allocation, cr_no from sa_change_cost_plans where project_code LIKE '%" . $_GET['project_code'] . "%'");
                                        // $cr_no = isset($get_cr[0]['cr_no']) ? $get_cr[0]['cr_no'] : '';
                                        // $ticket_allocation = isset($get_cr[0]['ticket_allocation']) ? $get_cr[0]['ticket_allocation'] : 'none';

                                        $get_tiket = $DBSB->get_sqlV2("select * from sa_trx_project_list where project_code = '" . $project_code . "' and order_number = '" . $order_number . "'");

                                        if ($get_tiket[2] > 0  && $get_tiket[0]['reporting'] != '') {
                                            $tiket = json_decode($get_tiket[0]['reporting'], true);
                                            $tiket_reporting = $tiket['reporting']['plan'];
                                            $tiket_preventive = $tiket['preventive']['plan'];
                                            $ticket_allocation = $tiket['ticket']['plan'];
                                            $backup_config = $tiket_preventive + 1;
                                        }
                                    }

                                    $estimationName = isset($estimation_name[$project_estimation_id]) ? $estimation_name[$project_estimation_id] : '';
                                    ?>
                                    <div class="row mb-3">
                                        <label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Project
                                            Code</label>
                                        <div class="col-sm-8">
                                            <?php if ($datatype == "edit") { ?>
                                                <select class="form-control" name="project_code" id="project_code" required readonly>
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
                                                <input type="text" class="form-control form-control-sm" id="service_type" name="service_type" value='<?= $project_type; ?>' readonly>
                                            <?php } else { ?>
                                                <input type="text" class="form-control form-control-sm" id="service_type" name="service_type" value='' readonly>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="service_type" class="col-sm-4 col-form-label col-form-label-sm">Service
                                            Type</label>
                                        <div class="col-sm-8">
                                            <?php if ($datatype == "edit") { ?>
                                                <input type="text" class="form-control form-control-sm" id="service_type" name="service_type" value='<?= $service_type ?>' readonly>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="order_number" class="col-sm-4 col-form-label col-form-label-sm">Order
                                            Number now</label>
                                        <div class="col-sm-8">
                                            <?php if ($datatype == "edit") { ?>
                                                <input type="text" class="form-control form-control-sm" id="order_number" name="master[order_number]" value='<?= $order_number; ?>' readonly>
                                                <?php $dataCN2 = $DBWRIKE->get_sqlV2("SELECT * FROM sa_wrike_project_list WHERE order_number = '" . $_GET['order_number'] . "' AND project_type LIKE '%" . $_GET['project_type'] . "%'");
                                                //echo "SELECT * FROM sa_wrike_project_list WHERE order_number = '" . $_GET['order_number'] . "' AND project_type LIKE '%" . $_GET['project_type'] . "%'";
                                                ?>
                                                <input type="hidden" id="projectId" name="projectId" value="<?php
                                                                                                            if (!empty($dataCN2[0]['id'])) {
                                                                                                                echo $dataCN2[0]['id'];
                                                                                                            } else {
                                                                                                                echo "Data tidak sinkron !";
                                                                                                            } ?>" />

                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <?php if ($_SESSION['Microservices_UserEmail'] == 'aryo.bimo@mastersystem.co.id') { ?>
                                        <div class="row mb-3">
                                            <label for="ticket_allocation" class="col-sm-4 col-form-label col-form-label-sm">Total Tiket <i class="fas fa-question-circle" data-bs-toggle="popover" data-bs-trigger="focus" title="Tiket dari SBF (Kolom Ke-1), Tiket addon (Kolom Ke-2), , Total tiket (Kolom Ke-3)"></i></label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="sbf_ticket_allocation" name="master[ticket_allocation]" value='<?= $ticket_allocation; ?>' readonly>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="addon_ticket_allocation" name="master[addon_ticket_allocation]" value='<?= $data_master[0]['addon_ticket_allocation']; ?>' onchange="totalReportDateEdit()">
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="total_ticket_allocation" name="master[total_ticket_allocation]" value='<?= $data_master[0]['total_ticket_allocation']; ?>' readonly>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="assignment_note" class="col-sm-4 col-form-label col-form-label-sm">Report Maintenance</label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="sbf_mt_report_date" name="master[mt_report_date]" value='<?= $tiket_reporting; ?>' readonly>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="addon_mt_report_date" name="master[addon_mt_report_date]" value='<?= $data_master[0]['addon_mt_report_date']; ?>' onchange="totalReportDateEdit()">
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="total_mt_report_date" name="master[total_mt_report_date]" value='<?= $data_master[0]['total_mt_report_date']; ?>' readonly>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="assignment_note" class="col-sm-4 col-form-label col-form-label-sm">Preventive Maintenance</label>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="sbf_preventive_mt_date" name="master[preventive_mt_date]" value='<?= $tiket_preventive; ?>' readonly>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="addon_preventive_mt_date" name="master[addon_preventive_mt_date]" value='<?= $data_master[0]['addon_preventive_mt_date']; ?>' onchange="totalReportDateEdit()">
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="total_preventive_mt_date" name="master[total_preventive_mt_date]" value='<?= $data_master[0]['total_preventive_mt_date']; ?>' readonly>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="assignment_note" class="col-sm-4 col-form-label col-form-label-sm">Backup Config Maintenance</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control form-control-sm" id="total_backup_mt_date" name="master[total_backup_mt_date]" value='<?= $backup_config; ?>' onchange="totalReportDate('backup_mt_date')">
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="row mb-3">
                                            <label for="ticket_allocation" class="col-sm-4 col-form-label col-form-label-sm">Total Tiket</label></label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control form-control-sm" id="total_ticket_allocation" name="master[total_ticket_allocation]" value='<?= $data_master[0]['total_ticket_allocation']; ?>'>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="assignment_note" class="col-sm-4 col-form-label col-form-label-sm">Report Maintenance</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control form-control-sm" id="total_mt_report_date" name="master[total_mt_report_date]" value='<?= $data_master[0]['total_mt_report_date']; ?>' onchange="totalReportDate('mt_report_date')">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="assignment_note" class="col-sm-4 col-form-label col-form-label-sm">Preventive Maintenance</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control form-control-sm" id="total_preventive_mt_date" name="master[total_preventive_mt_date]" value='<?= $data_master[0]['total_preventive_mt_date']; ?>' onchange="totalReportDate('preventive_mt_date')">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="assignment_note" class="col-sm-4 col-form-label col-form-label-sm">Backup Config Maintenance</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control form-control-sm" id="total_backup_mt_date" name="master[total_backup_mt_date]" value='<?= $data_master[0]['total_backup_mt_date']; ?>' onchange="totalReportDate('backup_mt_date')">
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <div class="row mb-3">
                                        <label for="project_duration" class="col-sm-4 col-form-label col-form-label-sm">Project Duration</label>
                                        <div class="col-sm-8">
                                            <?php if ($datatype == "edit") { ?>
                                                <input type="text" class="form-control form-control-sm" id="project_duration" name="project_duration" value='<?= $project_estimation_duration ?>' readonly>
                                            <?php } ?>
                                        </div>
                                    </div>



                                </div>


                                <div class="col-md-6">
                                    <br>
                                    <div class="row mb-3">
                                        <label for="mt_date_start" class="col-sm-4 col-form-label col-form-label-sm">Maintenance Start</label>
                                        <div class="col-sm-8">
                                            <?php if ($datatype == "edit") { ?>
                                                <input type="date" class="form-control form-control-sm" id="mt_date_start" name="date[mt_date_start]" value="<?= $mt_start ?>" onchange="updateDurationActual('edit')">
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <br>
                                    <div class="row mb-3">
                                        <label for="mt_date_end" class="col-sm-4 col-form-label col-form-label-sm">Maintenance End</label>
                                        <div class="col-sm-8">
                                            <?php if ($datatype == "edit") { ?>
                                                <input type="date" class="form-control form-control-sm" id="mt_date_end" name="date[mt_date_end]" value="<?= $mt_end ?>" onchange="updateDurationActual('edit')">
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="duration_actual" class="col-sm-4 col-form-label col-form-label-sm">Project Duration
                                            Actual</label>
                                        <div class="col-sm-8">
                                            <?php if ($datatype == "edit") { ?>
                                                <input type="text" class="form-control form-control-sm" id="diff_duration" value='0 Days' readonly>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6">

                                    <div class="row mb-3">
                                        <label for="ikom_date_start" class="col-sm-4 col-form-label col-form-label-sm">IKOM
                                            Date</label>
                                        <div class="col-sm-8">
                                            <?php if ($datatype == "edit") { ?>
                                                <input type="date" class="form-control form-control-sm" id="ikom_date_start" name="date[ikom]" value="<?= $ikom ?>">
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="assignment_date" class="col-sm-4 col-form-label col-form-label-sm">Assignment Date</label>
                                        <div class="col-sm-8">
                                            <?php if ($datatype == "edit") { ?>
                                                <input type="date" class="form-control form-control-sm" id="assignment_date" name="date[assignment_date]" value="<?= $assignment_date ?>">
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Ontime <i class="fas fa-question-circle" data-bs-toggle="popover" data-bs-trigger="focus" title="Informasi mengenai GAP tanggal start maintenance dengan tanggal assignment ke PM. Jika berada di bulan yang sama maka Ontime (Yes), jika berbeda bulan artinya ada Tidak Ontime (No) / Ada GAP yang disebabkan Backdate Customer / Implementasi / Sales"></i></label>
                                        <div class="col-sm-8">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="master[ontime]" value="1" onchange="onTime('yes');" <?= $data_master[0]['ontime'] == 1 ? 'checked' : '' ?>>
                                                <label class="form-check-label">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="master[ontime]" value="0" onchange="onTime('no');" <?= $data_master[0]['ontime'] == 0 ? 'checked' : '' ?>>
                                                <label class="form-check-label">No</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3" id="display_ontime" style="<?= $data_master[0]['ontime'] == 1 ? 'display: none' : '' ?>">
                                        <label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm"></label>
                                        <div class="col-sm-8">
                                            <?php if ($datatype == "edit") { ?>
                                                <select class="form-control" name="master[ontime_notes]" id="ontime_notes">
                                                    <option value="sales" <?= $data_master[0]['ontime_notes'] == "sales" ? 'selected' : '' ?>>
                                                        Sales</option>
                                                    <option value="implementation" <?= $data_master[0]['ontime_notes'] == "implementation" ? 'selected' : '' ?>>
                                                        Implementation</option>
                                                    <option value="customer" <?= $data_master[0]['ontime_notes'] == "customer" ? 'selected' : '' ?>>
                                                        Customer</option>
                                                    <option value="other" <?= $data_master[0]['ontime_notes'] == "other" ? 'selected' : '' ?>>
                                                        Other</option>
                                                </select>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="assignment_note" class="col-sm-4 col-form-label col-form-label-sm">Note</label>
                                        <div class="col-sm-8">
                                            <?php if ($datatype == "edit") { ?>
                                                <input type="text" class="form-control form-control-sm" id="assignment_note" name="master[assignment_note]" value='<?= $data_master[0]['notes']; ?>'>
                                            <?php } ?>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <div class="row mb-3">
                                        <label for="kom_date" class="col-sm-4 col-form-label col-form-label-sm">KOM
                                            Date</label>
                                        <div class="col-sm-8">
                                            <?php if ($datatype == "edit") { ?>
                                                <input type="date" class="form-control form-control-sm" id="kom_date" name="date[kom]" value="<?= $kom ?>">
                                            <?php } ?>
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
                                                    <input type="text" class="form-control form-control-sm" id="m_addon_title" name="m_addon_title" value="<?= $data_addon['addon_title'] ?>" readonly>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <div class="row mb-3">
                                            <div class="col-sm-12">
                                                <input type="text" class="form-control form-control-sm" id="m_addon_title" name="m_addon_title" value="" readonly>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>

                                <div>
                                    <hr>
                                </div>

                                <div class="col-md-6 border-right">
                                    <div class="row" id="add_mt_report_date">
                                        <div class="row mb-3">
                                            <div class="col-sm-6">
                                                <b>Maintenance Report</b>
                                            </div>
                                            <div class="col-sm-6">
                                                <b>Date</b>
                                            </div>
                                        </div>

                                        <?php for ($i = 1; $i <= $data_master[0]['total_mt_report_date']; $i++) { ?>
                                            <div class="row" id="input_mt_report_date">
                                                <div class="col-sm-6">
                                                    <div class="row mb-3">
                                                        <label for="mt_report_date" class="col-sm-12 col-form-label col-form-label-sm"><?= $i ?></label>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6">
                                                    <div class="row mb-3">
                                                        <div class="col-sm-12">
                                                            <input type="date" class="form-control form-control-sm" id="mt_report_date" name="date[mt_report_date][]" value="<?= isset($data_result_date['mt_report_date_' . $i]) ? $data_result_date['mt_report_date_' . $i] : '' ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>

                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="row" id="add_preventive_mt_date">
                                        <div class="row mb-3">
                                            <div class="col-sm-6">
                                                <b>Preventive Maintenance</b>
                                            </div>
                                            <div class="col-sm-6">
                                                <b>Date</b>
                                            </div>
                                        </div>

                                        <?php for ($i = 1; $i <= $data_master[0]['total_preventive_mt_date']; $i++) { ?>
                                            <div class="row" id="input_preventive_mt_date">
                                                <div class="col-sm-6">
                                                    <div class="row mb-3">
                                                        <label for="preventive_mt_date" class="col-sm-12 col-form-label col-form-label-sm"><?= $i ?></label>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6">
                                                    <div class="row mb-3">
                                                        <div class="col-sm-12">
                                                            <input type="date" class="form-control form-control-sm" id="preventive_mt_date" name="date[preventive_mt_date][]" value="<?= isset($data_result_date['preventive_mt_date_' . $i]) ? $data_result_date['preventive_mt_date_' . $i] : '' ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        <?php } ?>
        <input type="submit" class="btn btn-secondary" name="cancel" value="Cancel">
        <?php if (isset($datatype) && $datatype == 'edit') { ?>
            <input type="submit" class="btn btn-primary" name="saveReport" value="Save">
            <!-- Trigger Modal Submit -->
            <button type="button" class="btn btn-primary mt-1" data-toggle="modal" data-target="#submitModal">Submit</button>
        <?php } elseif (isset($datatype) && $datatype == 'add') { ?>
            <input type="submit" class="btn btn-primary" name="addReport" value="Save">
            <!-- Trigger Modal Submit -->
            <button type="button" class="btn btn-primary mt-1" data-toggle="modal" data-target="#submitModal">Submit</button>
        <?php } ?>
        <!-- Modal -->
        <div class="modal fade" id="submitModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                    <img src="components/modules/resource_assignment_mt/img/checklist.gif" alt="Girl in a jacket" width="100" height="100" data-aos="fade-up">
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
                                idname + '" name="date[' + idname + '][]"></div></div></div></div>';
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

                    var displayGabungan = document.getElementById("total_mt_report_date");
                    var displayGabungan6 = document.getElementById("total_preventive_mt_date");
                    var displayGabungan3 = document.getElementById("display_gabungan3");
                    var displayGabungan4 = document.getElementById("display_gabungan4");
                    if (displayGabungan) {
                        displayGabungan.value = 0;
                        displayGabungan6.value = 0;
                    }
                } else {
                    document.getElementById("display_gabungan").style.display = '';
                    document.getElementById("display_gabungan2").style.display = 'none';
                    document.getElementById("display_gabungan3").style.display = '';
                    document.getElementById("display_gabungan4").style.display = '';
                    document.getElementById("display_gabungan5").style.display = 'none';
                    document.getElementById("display_gabungan6").style.display = '';

                    var displayGabungan2 = document.getElementById("total_pmr_date");
                    var displayGabungan5 = document.getElementById("display_gabungan5");
                    if (displayGabungan2) {
                        displayGabungan2.value = 0;
                    }
                }
            }

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
                $('#project_code').select2({
                    placeholder: 'Pilih Project Code',
                    allowClear: true
                });
            });
        </script>