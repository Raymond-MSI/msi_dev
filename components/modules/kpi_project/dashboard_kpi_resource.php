<?php
include_once("../../../applications/connections/connections.php");
include_once("../../../components/classes/func_databases_v3.php");
include_once("../../../components/classes/func_hcm.php");
include_once("../../../components/classes/func_modules.php");
include_once("../../../components/classes/func_cfg_web.php");
$DB = new WebConfig($hostname, $username, $password, $database);
?>
<script>
$(document).ready(function() {
    $('#tblKPIProject').DataTable({
        buttons: [{
            extend: 'excelHtml5',
            text: "<i class='fa-solid fa-file-excel'></i>",
            title: 'KPI_Summary_' + <?php echo date("YmdGis"); ?>,
            messageTop: 'KPI Summary berdasarkan data type project dan status review.',
        }],
        "columnDefs": [{
            "targets": [10, 11],
            "visible": false
        }],
        footerCallback: function(row, data, start, end, display) {
            let api = this.api();

            // Remove the formatting to get integer data for summation
            let intVal = function(i) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '') * 1 :
                    typeof i === 'number' ?
                    i :
                    0;
            };

            // Total over all pages
            totalPlan = api
                .column(10)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);

            // Total over all pages
            totalActual = api
                .column(11)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);

            // Total over all pages
            totalWeightedIdeal = api
                .column(7)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);

            // Total over all pages
            totalWeightedActual = api
                .column(9)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);

            // Update footer
            api.column(1).footer().innerHTML =
                totalPlan + ' | ' + totalActual + '<br/>(' + format(totalActual / totalPlan * 100) +
                '%)';
            api.column(7).footer().innerHTML =
                format(totalWeightedIdeal);
            api.column(9).footer().innerHTML =
                format(totalWeightedActual);
        }

    });
});
</script>

<div class="alert alert-light fs-4" role="alert">
    <?php
    if (isset($_GET['status_review']) && $_GET['status_review'] == "Reviewed") {
        $status_review = "Reviewed";
    } else {
        $status_review = "Not Yet Reviewed";
    }
    echo "Status Review : " . $status_review;
    ?>
</div>


<?php
$resource = $_GET['resource'];
$periode_kpi = $_GET['periode_kpi'];
$mdlname = 'KPI_PROJECT';
$DBKPI = get_conn($mdlname);
$mdlname = "WRIKE_INTEGRATE";
$DBWRIKE = get_conn($mdlname);

$mysql = sprintf(
    "SELECT * FROM `sa_dashboard_kpi`.`sa_user` WHERE %s `sa_dashboard_kpi`.`sa_user`.`kpi_status` = %s AND `sa_dashboard_kpi`.`sa_user`.`Nama` LIKE %s ORDER BY `sa_dashboard_kpi`.`sa_user`.`Nama`",
    GetSQLValueString("`tahun_review` = " . $periode_kpi, "defined", "`tahun_review` = " . $periode_kpi . " AND ", ""),
    GetSQLValueString($status_review, "text"),
    GetSQLValueString("%" . $resource . "%", "text")
);
$resource_list = $DBKPI->get_sql($mysql);


$mdlname = "HCM"; 
$DBHCM = get_conn($mdlname); 
$nama = $DBHCM->get_leader_v2($resource);
?>

<div class="table-responsive mb-3">
    <table class="table table-striped table-bordered" id="tblKPIProject" width="100%">
        <thead>
            <tr class="text-center">
                <th class="align-middle text-info-emphasis bg-info-subtle" rowspan="2">Project Information</th>
                <th class="align-middle text-warning-emphasis bg-warning-subtle" rowspan="2">Productivity</th>
                <th class="align-middle text-warning-emphasis bg-warning-subtle" rowspan="2">BAST<br /><span
                        class="text-nowrap">Plan | Actual</span></th>
                <th class="align-middel text-success-emphasis bg-success-subtle" colspan="5">KPI Project</th>
                <th class="align-middle text-primary-emphasis bg-primary-subtle" colspan="2">KPI Resource</th>
                <th class="align-middle text-info-emphasis bg-info-subtle" rowspan="2">Productivity Plan</th>
                <th class="align-middle text-info-emphasis bg-info-subtle" rowspan="2">Productivity Actual</th>
            </tr>
            <tr class="text-center">
                <th class="align-middle text-success-emphasis bg-success-subtle">Cost <span class="text-nowrap">Category
                        | KPI (%)</span></th>
                <th class="align-middle text-success-emphasis bg-success-subtle">Time <span class="text-nowrap">Category
                        | KPI (%)</span></th>
                <th class="align-middle text-success-emphasis bg-success-subtle">Error <span
                        class="text-nowrap">Category | KPI (%)</span></th>
                <th class="align-middle text-success-emphasis bg-success-subtle">Total <span class="text-nowrap">Score
                        (%)</span></th>
                <th class="align-middle text-success-emphasis bg-success-subtle">Weighted <span
                        class="text-nowrap">Value</span></th>
                <th class="align-middle text-primary-emphasis bg-primary-subtle">Total <span class="text-nowrap">Score
                        (%)</span></th>
                <th class="align-middle text-primary-emphasis bg-primary-subtle">Weighted <span
                        class="text-nowrap">Value</span></th>
            </tr>
        </thead>
        <tbody>
            <?php
if ($resource_list[2] > 0) {

                $totalWeightedIdeal = 0;
                $totalWeighted = 0;
                $totalKPIIdeal = 0;
                $totalKPI = 0;
                do {
                    $mysql = "SELECT * FROM sa_productivity_alluser WHERE resource_email='$resource' AND order_number='" . $resource_list[0]['order_number'] . "' AND project_type='" . $resource_list[0]['project_type'] . "'";
                    $prodPlan = $DBKPI->get_sql($mysql);
                    if ($resource_list[0]['project_type'] == 'MSI Project Implementation'){
                        $tipe_project = "Implementation";
                    } else {
                        $tipe_project = "Maintenance";
                    }
                    $get_progress = $DBWRIKE->get_sql("SELECT * FROM sa_resource_assignment WHERE order_number='" . $resource_list[0]['order_number'] . "' AND resource_email LIKE '%$resource%' AND approval_status='approved' AND project_type='$tipe_project'");
                    if ($get_progress[2] > 0) {
                        $start_progressraw = $get_progress[0]['start_progress'];
                        if (empty($start_progressraw)) {
                            $start_progress = 0;
                        } else {
                            $start_progress = $get_progress[0]['start_progress'];
                        }
                        $end_progressraw = $get_progress[0]['end_progress'];
                        if (empty($end_progressraw)) {
                            $end_progress = 0;
                        } else {
                            $end_progress = $get_progress[0]['end_progress'];
                        }
                        $persen_project_charter = "($start_progress% - $end_progress%)";
                    } else {
                        $start_progress = 0;
                        $end_progress = 0;
                        $persen_project_charter = "($start_progress% - $end_progress%) (<span class='text-danger'>Project Charter N/A</span>)";
                    }

                    if (empty($prodPlan[0]['task_plan'])) {
                        $task_plan = 0;
                    } else {
                        $task_plan = $prodPlan[0]['task_plan'];
                        $prodPlan[0]['task_actual'];
                    }

                    if (empty($prodPlan[0]['task_actual'])) {
                        $task_actual = 0;
                    } else {
                        $task_actual = $prodPlan[0]['task_actual'];
                    }

                    $tblname = "kpi_so_wr";
                    $condition = "order_number = '" . $resource_list[0]['order_number'] . "'";
                    $project_list = $DBKPI->get_data($tblname, $condition);
                ?>
            <tr>
                <td>
                    <span class="fw-bold text-nowrap">
                        <?php
                                if ($resource_list[0]['project_type'] == "MSI Project Implementation") {
                                    $ProjectType = "Implementation";
                                } else
                                if ($resource_list[0]['project_type'] == "MSI Project Maintenance") {
                                    $ProjectType = "Maintenance";
                                }
                                echo $ProjectType;
                                echo "<br/>";
                                echo $resource_list[0]['project_code'] . "&nbsp;|&nbsp;" . $resource_list[0]['so_number'] . "&nbsp;|&nbsp;" . $resource_list[0]['order_number'] . "<br/>";
                                ?>
                    </span>
                    <span class=" text-nowrap" style="font-size:12px">
                        <?php
                                $xxx = explode(" ", $resource_list[0]['project_name']);
                                $title = "";
                                $yyy = "";
                                $x = 80;
                                foreach ($xxx as $zzz) {
                                    $len = strlen($yyy) + strlen($zzz);
                                    if (strval($len) <= $x) {
                                        $yyy .= $zzz . " ";
                                    } else {
                                        $yyy .= "<br/>" . $zzz;
                                        $x = 160;
                                    }
                                }
                                $title = $yyy;
                                $get_project_id = $DBWRIKE->get_sqlV2("SELECT id FROM sa_wrike_project_list WHERE order_number='" . $resource_list[0]['order_number'] . "' AND project_type='" . $resource_list[0]['project_type'] . "'");
                                $projectid = $get_project_id[0]['id'];
                                $get_role = $DBWRIKE->get_sqlV2("SELECT DISTINCT resource_email, resource_role FROM sa_wrike_evans_assignment WHERE project_id='$projectid' AND resource_email='$resource'");
                                // echo $resource_list[0]['project_name'] . "<br />" . ($resource_list[0]['role'] != "" ? $resource_list[0]['role'] : "xxxx") . " ($start_progress% - $end_progress%)";
                                //echo $title . "<br />" . ($resource_list[0]['role'] != "" ? $resource_list[0]['role'] : "xxxx") . " ($start_progress% - $end_progress%)";
                                echo $title . "<br />" . ($get_role[0]['resource_role']) . " $persen_project_charter";
                                ?>
                    </span>
                </td>
                <td class="text-center text-nowrap">
                    <?php echo $task_plan . '&nbsp;&nbsp;|&nbsp;&nbsp;' . $task_actual; ?>
                </td>
                <td class="text-center text-nowrap">
                    <?php
                            echo ($project_list[2] > 0 ? ($project_list[0]['bast_plan'] <> "" ? $project_list[0]['bast_plan'] : "Empty") : 'Empty') . '&nbsp;&nbsp;|&nbsp;&nbsp;' . ($project_list[2] > 0 ? ($project_list[0]['bast_actual'] <> "" ? $project_list[0]['bast_actual'] : "Empty") : 'Empty');
                            ?>
                </td>
                <td class="text-center text-nowrap">
                    <?php echo $project_list[2] > 0 ? $project_list[0]['commercial_category'] . '&nbsp;|&nbsp;' . number_format($project_list[0]['commercial_kpi'] * 100, 2) : "Temporary"; ?>
                </td>
                <td class="text-center text-nowrap">
                    <?php echo $project_list[2] > 0 ? $project_list[0]['time_category'] . '&nbsp;|&nbsp;' . number_format($project_list[0]['time_kpi'] * 100, 2) : "Temporary"; ?>
                </td>
                <td class="text-center text-nowrap">
                    <?php echo $project_list[2] > 0 ? $project_list[0]['error_category'] . '&nbsp;|&nbsp;' . number_format($project_list[0]['error_kpi'] * 100, 2) : "Temporary"; ?>
                </td>
                <td class="text-right">
                    <?php echo $project_list[2] > 0 ? number_format($project_list[0]['total_cte'] * 100, 2) : "Temporary"; ?>
                </td>
                <td class="text-right">
                    <?php echo $project_list[2] > 0 ? number_format($project_list[0]['weighted_value'], 2) : "Temporary"; ?>
                </td>
                <td class="text-right">
                    <?php echo number_format($resource_list[0]['cte'] * 100, 2); ?>
                </td>
                <td class="text-right">
                    <?php
                            if ($resource_list[0]['nilai_akhir_aktual'] > 0) {
                                echo number_format(str_replace(",", ".", $resource_list[0]['nilai_akhir_aktual']), 2);
                                $totalKPI += str_replace(",", ".", $resource_list[0]['nilai_akhir_aktual']);
                            }
                            ?>
                </td>
                <td>
                    <?php echo $task_plan; ?>
                </td>
                <td>
                    <?php echo $task_actual; ?>
                </td>
            </tr>
            <?php } while ($resource_list[0] = $resource_list[1]->fetch_assoc()); ?>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr class="text-center">
                <th class="align-middle text-info-emphasis bg-info-subtle">Total</th>
                <th class="align-middle text-warning-emphasis bg-warning-subtle"></th>
                <th class="align-middle text-warning-emphasis bg-warning-subtle"></span></th>
                <th class="align-middle text-success-emphasis bg-success-subtle"></span></th>
                <th class="align-middle text-success-emphasis bg-success-subtle"></span></th>
                <th class="align-middle text-success-emphasis bg-success-subtle"></span></th>
                <th class="align-middle text-success-emphasis bg-success-subtle"></span></th>
                <th class="align-middle text-success-emphasis bg-success-subtle text-end"></span></th>
                <th class="align-middle text-primary-emphasis bg-primary-subtle"></th>
                <th class="align-middle text-primary-emphasis bg-primary-subtle text-end"></span></th>
                <th class="align-middle text-info-emphasis bg-info-subtle"></th>
                <th class="align-middle text-info-emphasis bg-info-subtle"></th>
            </tr>
            <tr class="text-center">
                <th class="align-middle text-info-emphasis bg-info-subtle">Project Information</th>
                <th class="align-middle text-warning-emphasis bg-warning-subtle">Productivity</th>
                <th class="align-middle text-warning-emphasis bg-warning-subtle">BAST<br /><span
                        class="text-nowrap">Plan | Actual</span></th>
                <th class="align-middle text-success-emphasis bg-success-subtle">Cost <span class="text-nowrap">Category
                        | KPI (%)</span></th>
                <th class="align-middle text-success-emphasis bg-success-subtle">Time <span class="text-nowrap">Category
                        | KPI (%)</span></th>
                <th class="align-middle text-success-emphasis bg-success-subtle">Error <span
                        class="text-nowrap">Category | KPI (%)</span></th>
                <th class="align-middle text-success-emphasis bg-success-subtle">Total <span class="text-nowrap">Score
                        (%)</span></th>
                <th class="align-middle text-success-emphasis bg-success-subtle">Weighted <span
                        class="text-nowrap">Value</span></th>
                <th class="align-middle text-primary-emphasis bg-primary-subtle">Total <span class="text-nowrap">Score
                        (%)</span></th>
                <th class="align-middle text-primary-emphasis bg-primary-subtle">Weighted <span
                        class="text-nowrap">Value</span></th>
                <th class="align-middle text-info-emphasis bg-info-subtle"></th>
                <th class="align-middle text-info-emphasis bg-info-subtle"></th>
            </tr>
        </tfoot>
    </table>
</div>

<div class="fs-3">Description :</div>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header fw-bold text-success-emphasis bg-success-subtle">KPI Project</div>
            <div class="card-body">
                <table class="table table-striped">
                    <tr>
                        <td>Cost Category | KPI</td>
                        <td>
                            <p>Cost Resource Utilization (RU) parameter :</p>
                            <ol>
                                <li>Normal : RU less than 110%</li>
                                <li>Minor : RU 110% - 150%</li>
                                <li>Major : RU 150% - 200%</li>
                                <li>Critical : RU over than 200%</li>
                                <li>None : task not update</li>
                            </ol>
                        </td>
                    </tr>
                    <tr>
                        <td>Time Category | KPI</td>
                        <td>
                            <p>Time parameter :</p>
                            <ol>
                                <li>Normal : BAST delay less than 1 month</li>
                                <li>Minor : BAST delay 1 - 3 months</li>
                                <li>Major : BAST delay 4 - 6 months</li>
                                <li>Critical : BAST delay over than 6 month</li>
                                <li>None : BAST not available</li>
                            </ol>
                        </td>
                    </tr>
                    <tr>
                        <td>Error Category | KPI</td>
                        <td>
                            <p>KPI Resource (Individual) parameter :</p>
                            <ol>
                                <li>Poor : KPI less than 60%</li>
                                <li>Good : KPI 60% - 75%</li>
                                <li>Very Good : KPI 75% - 90%</li>
                                <li>Excellence : KPI 90% - 100%</li>
                            </ol>
                        </td>
                    </tr>
                    <tr>
                        <td>Total Score</td>
                        <td>
                            Total Score = 100% - Total CTE
                        </td>
                    </tr>
                    <tr>
                        <td>Weighted Value</td>
                        <td>
                            Weighted Value = Weighted Ideal * Total Score
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card mb-3">
            <div class="card-header fw-bold text-primary-emphasis bg-primary-subtle">KPI Resources</div>
            <div class="card-body">
                <table class="table table-striped">
                    <tr>
                        <td>Total Score (%)</td>
                        <td>Total Score (%) = Total Score (%)</td>
                    </tr>
                    <tr>
                        <td>Total Weighted Value</td>
                        <td>Total Weighted Value = Total Weighted Actual / Total Weighted Ideal * 100</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-header fw-bold">Other Information</div>
            <div class="card-body">
                <table class="table table-striped">
                    <tr>
                        <td>Project Temporary</td>
                        <td>The project is temporary because there is no PO Customer yet.</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>