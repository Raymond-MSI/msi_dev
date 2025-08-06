<?php
include_once("../../../applications/connections/connections.php");
include_once("../../../components/classes/func_databases_v3.php");
include_once("../../../components/classes/func_hcm.php");
include_once("../../../components/classes/func_modules.php");
include_once("../../../components/classes/func_cfg_web.php");
$DB = new WebConfig($hostname, $username, $password, $database);

$DBKPI = get_conn("DASHBOARD_KPI");
$sbsb = "SERVICE_BUDGET";
$DBSB = get_conn($sbsb);

$wrwr = "WRIKE_INTEGRATE";
$DBWR = get_conn($wrwr);

$survey = "SURVEY";
$DBSV = get_conn($survey);

$db_name = "HCM";
$DBHCM = get_conn($db_name);

$crcr = "CHANGE_REQUEST";
$DBCR = get_conn($crcr);

$DBGC = get_conn("GOOGLE_CALENDAR");

$email_resource = $resource = $_GET['resource'];
$status_review = $_GET['status_review'];
?>
<div class="table-responsive mb-3">
    <table class="table table-striped table-bordered" id="tblKPIProject" width="100%">
        <thead>
            <tr class="text-center">
                <th rowspan="2" class="align-middle text-info-emphasis bg-info-subtle">Project Information</th>
                <th rowspan="2" class="align-middle text-info-emphasis bg-info-subtle">Productivity</th>
                <th rowspan="2" class="align-middle text-info-emphasis bg-info-subtle">Periode Maintenance</th>
                <th colspan="8" class="align-middel text-success-emphasis bg-success-subtle">KPI Project</th>
                <th colspan="2" class="align-middel text-success-emphasis bg-success-subtle">KPI Resource</th>
            </tr>
            <tr class="text-center">
                <th class="align-middle text-success-emphasis bg-success-subtle">Renewal (%)</th>
                <th class="align-middle text-success-emphasis bg-success-subtle">Time (%)</th>
                <th class="align-middle text-success-emphasis bg-success-subtle">Error (%)</th>
                <th class="align-middle text-success-emphasis bg-success-subtle">Data Compliance (%)</th>
                <th class="align-middle text-success-emphasis bg-success-subtle">Backup Config (%)</th>
                <th class="align-middle text-success-emphasis bg-success-subtle">Adoption (%)</th>
                <th class="align-middle text-success-emphasis bg-success-subtle">Total Score (%)</th>
                <th class="align-middle text-success-emphasis bg-success-subtle">Weighted Value</th>
                <th class="align-middle text-success-emphasis bg-success-subtle">Total Score (%)</th>
                <th class="align-middle text-success-emphasis bg-success-subtle">Weighted Value</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $select = $DBKPI->get_sqlV2("SELECT * FROM sa_user WHERE Nama LIKE '%$email_resource%' AND project_type='MSI Project Maintenance' AND kpi_status='$status_review'");
            while ($row = $select[1]->fetch_assoc()) {
                $role = $row['role'];
                $totalKPIResource = $row['cte'];
                $weightedValueResource = $row['nilai_akhir_aktual'];
                $rawresource = explode("<", $row['Nama']);
                $rawresource2 = explode(">", $rawresource[1]);
                $resource_email = $rawresource2[0];
                $projectCode = $row['project_code'];
                $soNumber = $row['so_number'];
                $orderNumber = $row['order_number'];
                $projectName = $row['project_name'];
                $customerName = $row['customer_name'];
                $startResource = date("d-M-Y", strtotime($row['start_assignment']));
                $endResource = date("d-M-Y", strtotime($row['end_assignment']));
                $select_id = $DBWR->get_sqlV2("SELECT * FROM sa_wrike_project_list WHERE order_number='$orderNumber' AND project_type = 'MSI Project Maintenance'");
                if (empty($id2)) {
                    $taskPlan = 0;
                    $taskAktual = 0;
                } else {
                    $id2 = $select_id[0]['id'];
                    $get_jumlah_task_resource = $DBGC->get_sqlV2("SELECT COUNT(a.task_name) AS jumlah_task, a.project_id, a.project_code, b.project_type, a.resource_email FROM sa_preschedule a left join sa_schedule b ON a.google_event_id=b.event_id left join sa_wrike_integrate.sa_wrike_task c ON a.task_id=c.task_id WHERE b.response_status <>'declined' AND b.response_status<>'' AND b.response_status is not null AND a.work_date > '2023-03-31' AND a.project_id='$id2' AND a.project_code='$projectCode' AND b.project_type='MSI Project Maintenance' AND c.task_id is not null AND a.resource_email='$resource_email' GROUP BY a.project_id,a.project_code,b.project_type,a.resource_email");
                    $get_jumlah_task_resource_update = $DBGC->get_sqlV2("SELECT COUNT(a.project_id) as updated_task, a.project_id, a.project_code, b.project_type, a.resource_email FROM sa_preschedule a left join sa_schedule b ON a.google_event_id=b.event_id left join sa_wrike_integrate.sa_wrike_task c ON a.task_id=c.task_id WHERE b.response_status='accepted' AND b.response_status<>'' AND b.diff_time<>0 AND a.work_date > '2023-03-31' AND a.project_id='$id2' AND a.resource_email='$resource_email' AND a.project_code='$projectCode' AND b.project_type='MSI Project Maintenance' AND c.task_id is not null GROUP BY a.project_id,a.project_code,b.project_type,a.resource_email");
                    if (empty($get_jumlah_task_resource[0]['jumlah_task'])) {
                        $taskPlan = 0;
                    } else {
                        $taskPlan = $get_jumlah_task_resource[0]['jumlah_task'];
                    }
                    if (empty($get_jumlah_task_resource_update[0]['updated_task'])) {
                        $taskAktual = 0;
                    } else {
                        $taskAktual = $get_jumlah_task_resource_update[0]['updated_task'];
                    }
                }
                $select2 = $DBKPI->get_sqlV2("SELECT * FROM sa_kpi_so_wr WHERE order_number='$orderNumber' AND project_type LIKE '%Maintenance%'");
                $startAssignment = date("d-M-Y", strtotime($select2[0]['start_assignment']));
                $endAssignment = date("d-M-Y", strtotime($select2[0]['end_assignment']));
                $renewalCategory = $select2[0]['renewal_category'];
                $renewalKPI = $select2[0]['renewal_kpi'];
                $timeCategory = $select2[0]['time_category'];
                $timeKPI = $select2[0]['time_kpi'];
                $errorCategory = $select2[0]['error_category'];
                $errorKPI = $select2[0]['error_kpi'];
                $data_complianceCategory = $select2[0]['data_compliance_category'];
                $data_complianceKPI = $select2[0]['data_compliance_kpi'];
                $backup_configCategory = $select2[0]['backup_config_category'];
                $backup_configKPI = $select2[0]['backup_config_kpi'];
                $adoptionCategory = $select2[0]['adoption_category'];
                $adoptionKPI = $select2[0]['adoption_kpi'];
                $totalKPIProject = $select2[0]['total_cte'];
                $weightedValueProject = $select2[0]['weighted_value'];
                $cek_renewal = $DBSB->get_sqlV2("SELECT * FROM sa_trx_project_list WHERE order_number='$orderNumber' AND (status='acknowledge' OR status='reopen' OR status='approved')");
                if (empty($cek_renewal[0]['previous_id'])) {
                    $Renewal = 'Bukan Project Renewal';
                } else {
                    $previous_id = $cek_renewal[0]['previous_id'];
                    $get_start = $DBSB->get_sqlV2("SELECT * FROM sa_trx_project_list WHERE project_id=$previous_id");
                    $order = $get_start[0]['order_number'];
                    $get_start2 = $DBWR->get_sql("SELECT * FROM sa_maintenance_date WHERE order_number='$order' AND id_date=' mt_date_start'");
                    if (empty($get_start2[0]['date'])) {
                        $renewal_start = 'Kosong';
                    } else {
                        $renewal_start = $get_start2[0]['date'];
                    }
                    $get_end = $DBWR->get_sql("SELECT * FROM sa_maintenance_date WHERE order_number='$orderNumber' AND id_date=' mt_date_end'");
                    if (empty($get_end[0]['date'])) {
                        $renewal_end = 'Kosong';
                    } else {
                        $renewal_end = $get_end[0]['date'];
                    }
                    $Renewal = "$renewalCategory | $renewalKPI <br> $renewal_start | $renewal_end";
                }
                $cek_time = $DBKPI->get_sql("SELECT * FROM sa_master_maintenance_date WHERE order_number='$orderNumber'");
                if (empty($cek_time[0]['order_number'])) {
                    $plan_time = 0;
                } else {
                    $plan_report = $cek_time[0]['total_mt_report_date'];
                    $plan_preventive = $cek_time[0]['total_preventive_mt_date'];
                    $plan_time = $plan_report + $plan_preventive;
                }
                $cek_actual_time = $DBKPI->get_sqlV2("SELECT count(maintenance_name_file) as jumlah,order_number FROM sa_maintenance_date_kpi WHERE maintenance_name_file LIKE '%mt_report%' AND order_number='$orderNumber' GROUP BY order_number");
                if (empty($cek_actual_time[0]['jumlah'])) {
                    $actual_report = 0;
                } else {
                    $actual_report = $cek_actual_time[0]['jumlah'];
                }
                $cek_actual_time2 = $DBKPI->get_sqlV2("SELECT count(maintenance_name_file) as jumlah,order_number FROM sa_maintenance_date_kpi WHERE maintenance_name_file LIKE '%preventive%' AND order_number='$orderNumber' GROUP BY order_number");
                if (empty($cek_actual_time2[0]['jumlah'])) {
                    $actual_preventive = 0;
                } else {
                    $actual_preventive = $cek_actual_time2[0]['jumlah'];
                }
                $actual_time = $actual_report + $actual_preventive;
                if ($actual_time == $plan_time) {
                    $time_desc = "Complete";
                } else {
                    $time_desc = "Not Complete";
                }
                $cek_actual_time_bc = $DBKPI->get_sql("SELECT count(maintenance_name_file) as jumlah,order_number FROM sa_maintenance_date_kpi WHERE maintenance_name_file LIKE '%BC_0%' AND order_number='$orderNumber' GROUP BY order_number");
                if (empty($cek_actual_time_bc[0]['jumlah'])) {
                    $actual_report_bc = 0;
                } else {
                    $actual_report_bc = $cek_actual_time_bc[0]['jumlah'];
                }
                $cek_actual_time2_bc = $DBKPI->get_sql("SELECT * FROM sa_master_maintenance_date WHERE order_number='$orderNumber'");
                if (empty($cek_actual_time2_bc[0]['total_preventive_mt_date'])) {
                    $baseline_report_bc = 1;
                } else {
                    $baseline_report_bc = $cek_actual_time2_bc[0]['total_preventive_mt_date'] + 1;
                }
                if ($actual_report_bc == $baseline_report_bc || $actual_report_bc > $baseline_report_bc) {
                    $backup_desc = "Complete";
                } else {
                    $backup_desc = "Not Complete";
                }
                $get_data = $DBKPI->get_sql("SELECT * FROM sa_data_so WHERE order_number='$orderNumber'");
                if (isset($get_data[0]['maintenance_ikom_date'])) {
                    $maintenance_ikom_date = date("d-m-Y", strtotime($get_data[0]['maintenance_ikom_date']));
                } else {
                    $maintenance_ikom_date = 'Empty';
                }
                if (isset($get_data[0]['maintenance_compliance_actual_date'])) {
                    $maintenance_compliance_actual_date = date("d-m-Y", strtotime($get_data[0]['maintenance_compliance_actual_date']));
                } else {
                    $maintenance_compliance_actual_date = 'Empty';
                } ?>
                <tr>
                    <td>
                        <div style='font-size:13px'>
                            <?php echo "Maintenance | $projectCode | $soNumber | $orderNumber"; ?><br>
                            <?php echo $projectName; ?><br>
                            <?php echo $customerName; ?><br>
                            <?php echo $role; ?><br>
                            <?php echo "($startResource ~ $endResource)";?>
                        </div>
                    </td>
                    <td><?php echo "$taskPlan | $taskAktual";?></td>
                    <td><?php echo "$startAssignment ~ $endAssignment"; ?></td>
                    <td><?php echo "$Renewal"; ?></td>
                    <td><?php echo "$timeCategory | $timeKPI<br> $time_desc"; ?></td>
                    <td><?php echo "$errorCategory | $errorKPI"; ?></td>
                    <td><?php echo "$data_complianceCategory | $data_complianceKPI<br>$maintenance_ikom_date | $maintenance_compliance_actual_date";?></td>
                    <td><?php echo "$backup_configCategory | $backup_configKPI<br>$backup_desc";?></td>
                    <td><?php echo "$adoptionCategory | $adoptionKPI";?></td>
                    <td><?php echo "$totalKPIProject";?></td>
                    <td><?php echo "$weightedValueProject";?></td>
                    <td><?php echo "$totalKPIResource";?></td>
                    <td><?php echo "$weightedValueResource";?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>