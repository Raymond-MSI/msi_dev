<?php 
include_once("../../../applications/connections/connections.php"); 
include_once("../../../components/classes/func_databases_v3.php");
include_once("../../../components/classes/func_hcm.php");
include_once( "../../../components/classes/func_modules.php" );
include_once( "../../../components/classes/func_cfg_web.php");
$DB = new WebConfig($hostname, $username, $password, $database);
?>
<script>
$(document).ready(function () {
    $('#tblKPIProject').DataTable({
        // dom: 'Bflrtip',
        // ordering: true,
        // scrollX: true,
        buttons:[
            {
            extend: 'excelHtml5',
            text: "<i class='fa-solid fa-file-excel'></i>",
            title: 'KPI_Summary_'+<?php echo date("YmdGis"); ?>,
            messageTop: 'KPI Summary berdasarkan data type project dan status review.',
            }
        ],

        initComplete: function () {
        this.api()
            .columns()
            .every(function () {
                let column = this;
                let title = column.footer().textContent;
 
                // Create input element
                let input = document.createElement('input');
                input.placeholder = title;
                column.footer().replaceChildren(input);
 
                // Event listener for user input
                input.addEventListener('keyup', () => {
                    if (column.search() !== this.value) {
                        column.search(input.value).draw();
                    }
                });
            });
        }
    });
});
</script>

<div class="alert alert-light fs-4" role="alert">
  <?php 
  if(isset($_GET['status_review']) && $_GET['status_review']=="Reviewed")
  {
    $status_review = "Reviewed";
  } else
  {
    $status_review = "Not Yet Reviewed";
  }
  echo "Status Review : " . $status_review; 
  ?>
</div>


<?php
$resource = $_GET['resource'];
// $status_review = $_GET['status_review'];
// if($status_review=="Not yet reviewed")
// {
//     $status_review = "Not%20Yet%20Reviewed";
// }
$periode_kpi = $_GET['periode_kpi'];
$mdlname = 'KPI_PROJECT';
$DBKPI = get_conn($mdlname);
$mdlname = "WRIKE_INTEGRATE";
$DBWRIKE = get_conn($mdlname);

// if($periode_kpi=="")
// {
//     $periode_kpi = "'%%'";
// }
// $mysql = "SELECT `sa_dashboard_kpi`.`sa_user`.`project_code`, `sa_dashboard_kpi`.`sa_user`.`so_number`, `sa_dashboard_kpi`.`sa_user`.`customer_name`, `sa_dashboard_kpi`.`sa_user`.`project_name`, `sa_dashboard_kpi`.`sa_user`.`role` FROM `sa_dashboard_kpi`.`sa_user` WHERE `sa_dashboard_kpi`.`sa_user`.`Nama` LIKE '%" . $resource . "%' AND `sa_dashboard_kpi`.`sa_user`.`kpi_status` = '" . $status_review . "' ORDER BY `sa_dashboard_kpi`.`sa_user`.`Nama`; ";
$mysql = sprintf("SELECT * FROM `sa_dashboard_kpi`.`sa_user` WHERE %s `sa_dashboard_kpi`.`sa_user`.`kpi_status` = %s AND `sa_dashboard_kpi`.`sa_user`.`Nama` LIKE %s ORDER BY `sa_dashboard_kpi`.`sa_user`.`Nama`",
    GetSQLValueString($periode_kpi, "defined", "`periode` = " . $periode_kpi . " AND ", ""),
    GetSQLValueString($status_review, "text"),
    GetSQLValueString("%" . $resource . "%", "text")
);
$resource_list = $DBKPI->get_sql($mysql);

$mdlname = "HCM";
$DBHCM = get_conn($mdlname);
$nama = $DBHCM->get_leader_v2($resource);
?>

<?php
if($resource_list[2]>0)
{
    ?>
    <div class="table-responsive mb-3">
        <table class="table table-striped table-bordered" id="tblKPIProject">
            <thead>
                <tr class="text-center">
                    <th class="align-middle text-info-emphasis bg-info-subtle" rowspan="2">Project Information</th>
                    <th class="align-middle text-warning-emphasis bg-warning-subtle" rowspan="2">Productivity</th>
                    <!-- <th class="align-middle text-success-emphasis bg-info-subtle" rowspan="2">Project Role</th> -->
                    <th class="align-middle text-warning-emphasis bg-warning-subtle" rowspan="2">BAST<br/><span class="text-nowrap">Plan | Actual</span></th>
                    <th class="align-middel text-success-emphasis bg-success-subtle" colspan="5">KPI Project</th>
                    <th class="align-middle text-primary-emphasis bg-primary-subtle" colspan="2">KPI Resource</th>
                </tr>
                <tr class="text-center">
                    <th class="align-middle text-success-emphasis bg-success-subtle">Cost <span class="text-nowrap">Category | KPI (%)</span></th>
                    <th class="align-middle text-success-emphasis bg-success-subtle">Time <span class="text-nowrap">Category | KPI (%)</span></th>
                    <th class="align-middle text-success-emphasis bg-success-subtle">Error <span class="text-nowrap">Category | KPI (%)</span></th>
                    <!-- <th class="align-middle text-success-emphasis bg-success-subtle">Total <span class="text-nowrap">CTE (%)</span></th> -->
                    <th class="align-middle text-success-emphasis bg-success-subtle">Total <span class="text-nowrap">Score (%)</span></th>
                    <!-- <th class="align-middle text-success-emphasis bg-success-subtle">Weighted <span class="text-nowrap">Ideal</span></th> -->
                    <th class="align-middle text-success-emphasis bg-success-subtle">Weighted <span class="text-nowrap">Value</span></th>
                    <!-- <th class="align-middle text-primary-emphasis bg-primary-subtle">Role</th> -->
                    <!-- <th class="align-middle text-primary-emphasis bg-primary-subtle">KPI Project Ideal</th> -->
                    <!-- <th class="align-middle text-primary-emphasis bg-primary-subtle">KPI Project Actual</th> -->
                    <!-- <th class="align-middle text-primary-emphasis bg-primary-subtle"><span class="text-nowrap">Start | End</span> Assignment</th> -->
                    <!-- <th class="align-middle text-primary-emphasis bg-primary-subtle">Progress <span class="text-nowrap">Actual (%)</span></th> -->
                    <!-- <th class="align-middle text-primary-emphasis bg-primary-subtle">Progress Support</th> -->
                    <th class="align-middle text-primary-emphasis bg-primary-subtle">Total <span class="text-nowrap">Score (%)</span></th>
                    <!-- <th class="align-middle text-primary-emphasis bg-primary-subtle">KPI Project Ideal Resource</th> -->
                    <th class="align-middle text-primary-emphasis bg-primary-subtle">Weighted <span class="text-nowrap">Value</span></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $totalWeightedIdeal = 0;
                $totalWeighted = 0;
                $totalKPIIdeal = 0;
                $totalKPI = 0;
                do 
                { 
                    $mysql = sprintf("SELECT COUNT(`sa_wrike_task`.`task_id`) AS `total_task` FROM `sa_wrike_project_list` LEFT JOIN `sa_wrike_assignment` ON `sa_wrike_project_list`.`id` = `sa_wrike_assignment`.`project_id` LEFT JOIN `sa_wrike_task` ON `sa_wrike_task`.`task_id` = `sa_wrike_assignment`.`task_id` AND `sa_wrike_task`.`project_id` = `sa_wrike_project_list`.`id` WHERE `sa_wrike_project_list`.`order_number` = %s AND `sa_wrike_assignment`.`resource_email` LIKE %s; ",
                        // GetSQLValueString($resource_list[0]['so_number'], "text"),
                        GetSQLValueString($resource_list[0]['order_number'], "text"),
                        GetSQLValueString($resource, "text")
                    );
                    $prodPlan = $DBWRIKE->get_sql($mysql);

                    $mysql = sprintf("SELECT COUNT(DISTINCT `sa_wrike_timelog`.`task_id`) AS `total_update` FROM `sa_wrike_project_list` LEFT JOIN `sa_wrike_timelog` ON `sa_wrike_project_list`.`id` = `sa_wrike_timelog`.`project_id` LEFT JOIN `sa_wrike_assignment` ON `sa_wrike_assignment`.`resource_id` = `sa_wrike_timelog`.`resource_id` WHERE `sa_wrike_project_list`.`order_number` LIKE %s AND `sa_wrike_assignment`.`resource_email` LIKE %s AND `sa_wrike_timelog`.`create_date_calendar` <> ''; ",
                        // GetSQLValueString($resource_list[0]['so_number'], "text"),
                        GetSQLValueString($resource_list[0]['order_number'], "text"),
                        GetSQLValueString($resource, "text")
                    );
                    $prodActual = $DBWRIKE->get_sql($mysql);


                    $tblname = "kpi_so_wr";
                    // $condition = "so_number = '" . $resource_list[0]['so_number'] . "'";
                    $condition = "order_number = '" . $resource_list[0]['order_number'] . "'";
                    $project_list = $DBKPI->get_data($tblname,$condition);
                    ?>
                    <tr>
                        <td><span class="text-nowrap"><?php echo $resource_list[0]['project_code']; ?>&nbsp;|&nbsp;<?php echo $resource_list[0]['so_number']; ?><br/><span style="font-size:12px"><?php echo $resource_list[0]['project_name']; ?><br/><?php echo $resource_list[0]['role']; ?></span></td>
                        <td class="text-center text-nowrap"><?php echo $prodPlan[0]['total_task'] . '&nbsp;&nbsp;|&nbsp;&nbsp;' . $prodActual[0]['total_update']; ?></td>
                        <!-- <td class="text-right text-nowrap"><?php echo $resource_list[0]['role']; ?></td> -->
                        <td class="text-center text-nowrap">
                            <?php 
                            echo ($project_list[2]>0 ? ($project_list[0]['bast_plan']<>"" ? $project_list[0]['bast_plan'] : "Empty") : 'Empty') . '&nbsp;&nbsp;|&nbsp;&nbsp;' . ($project_list[2]>0 ? ($project_list[0]['bast_actual']<>"" ? $project_list[0]['bast_actual'] : "Empty") : 'Empty');
                            // if($project_list[2]>0)
                            // {
                            //     if($project_list[0]['start_assignment']>0)
                            //     {
                            //         echo date("d-m-Y", strtotime($project_list[0]['start_assignment']));
                            //     }
                            //     echo "&nbsp;|&nbsp;";
                            //     if($project_list[0]['bast_plan']>0)
                            //     {
                            //         echo date("d-m-Y", strtotime($project_list[0]['bast_plan']));
                            //     }
                            // } else
                            // {
                            //     echo "Temporary";
                            // }
                            ?>
                        </td>
                        <td class="text-center text-nowrap"><?php echo $project_list[2]>0 ? $project_list[0]['commercial_category'] . '&nbsp;|&nbsp;' . number_format($project_list[0]['commercial_kpi']*100,2) : "Temporary"; ?></td>
                        <td class="text-center text-nowrap"><?php echo $project_list[2]>0 ? $project_list[0]['time_category'] . '&nbsp;|&nbsp;' . number_format($project_list[0]['time_kpi']*100,2) : "Temporary"; ?></td>
                        <td class="text-center text-nowrap"><?php echo $project_list[2]>0 ? $project_list[0]['error_category'] . '&nbsp;|&nbsp;' . number_format($project_list[0]['error_kpi']*100,2) : "Temporary"; ?></td>
                        <!-- <td class="text-right"><?php echo $project_list[2]>0 ? number_format($project_list[0]['cte']*100,2) : "Temporary"; ?></td> -->
                        <td class="text-right"><?php echo $project_list[2]>0 ? number_format($project_list[0]['total_cte']*100,2) : "Temporary"; ?></td>
                        <!-- <td class="text-right"><?php echo $project_list[2]>0 ? number_format($project_list[0]['max_value'],2) : "Temporary"; ?></td> -->
                        <td class="text-right"><?php echo $project_list[2]>0 ? number_format($project_list[0]['weighted_value'],2) : "Temporary"; ?></td>
                        <!-- <td class="text-center text-nowrap"><?php echo $resource_list[0]['role']; ?></td> -->
                        <!-- <td class="text-right">
                            <?php
                            echo number_format($resource_list[0]['nilai_ideal'],2);
                            $totalWeightedIdeal += $resource_list[0]['nilai_ideal'];
                            ?>
                        </td> -->
                        <!-- <td class="text-right">
                            <?php 
                            echo number_format($resource_list[0]['nilai_aktual'],2); 
                            $totalWeighted += $resource_list[0]['nilai_aktual'];
                            ?>
                        </td> -->
                        <!-- <td class="text-center text-nowrap">
                            <?php
                            if($resource_list[0]['start_assignment']>0)
                            {
                                echo date("d-m-Y", strtotime($resource_list[0]['start_assignment']));
                            }
                            echo "&nbsp;|&nbsp;";
                            if($resource_list[0]['end_assignment']>0)
                            {
                                echo date("d-m-Y", strtotime($resource_list[0]['end_assignment']));
                            }
                            ?>
                        </td> -->
                        <!-- <td class="text-right"><?php echo number_format($resource_list[0]['progress'],2); ?></td> -->
                        <!-- <td class="text-center"><?php echo $resource_list[0]['project_support']; ?></td> -->
                        <td class="text-right"><?php echo number_format($resource_list[0]['cte']*100,2); ?></td>
                        <!-- <td class="text-right">
                            <?php 
                            if($resource_list[0]['nilai_akhir_ideal']>0)
                            {
                                echo number_format(str_replace(",",".",$resource_list[0]['nilai_akhir_ideal']),2);
                                $totalKPIIdeal += str_replace(",",".", $resource_list[0]['nilai_akhir_ideal']);
                            }
                            ?>
                        </td> -->
                        <td class="text-right">
                            <?php 
                            if($resource_list[0]['nilai_akhir_aktual']>0)
                            {
                                echo number_format(str_replace(",",".",$resource_list[0]['nilai_akhir_aktual']),2); 
                                $totalKPI += str_replace(",",".",$resource_list[0]['nilai_akhir_aktual']);
                            }
                            ?>
                        </td>
                    </tr>
                <?php } while($resource_list[0]=$resource_list[1]->fetch_assoc()); ?>
            </tbody>
            <tfoot>
                <!-- <tr class="text-primary-emphasis bg-primary-subtle fw-bold">
                    <td class="text-right" colspan="7">Total Weighted</td>
                    <td class="text-right"><?php echo number_format($totalWeightedIdeal,2); ?></td>
                    <td class="text-right"><?php echo number_format($totalWeighted,2); ?></td>
                    <td class="text-right" colspan="7">Total KPI</td>
                    <td class="text-right"><?php echo number_format($totalKPIIdeal,2); ?></td>
                    <td class="text-right"><?php echo number_format($totalKPI,2); ?></td>
                </tr>
                <tr class="text-info-emphasis bg-info-subtle fw-bold">
                    <td class="text-right" colspan="10">Total Weighted Average</td>
                    <td class="text-right">
                        <?php
                        if($totalWeightedIdeal==0)
                        {
                            $totalWeightedIdeal = 1;
                        } 
                        echo number_format($totalWeighted/$totalWeightedIdeal*100,2) . "%"; 
                        ?>
                    </td>
                    <td class="text-right" colspan="8">KPI Project Average Value</td>
                    <td class="text-right">
                        <?php 
                        if($totalKPIIdeal>0)
                        {
                            echo number_format($totalKPI/$totalKPIIdeal*100,2) . "%";
                        } else
                        {
                            echo number_format("0",2) . "%";
                        }
                        ?>
                    </td>
                </tr> -->
                <tr class="text-center">
                    <th class="align-middle text-info-emphasis bg-info-subtle" >Project Information</th>
                    <th class="align-middle text-warning-emphasis bg-warning-subtle" >Productivity</th>
                    <th class="align-middle text-warning-emphasis bg-warning-subtle" >BAST<br/><span class="text-nowrap">Plan | Actual</span></th>
                    <th class="align-middle text-success-emphasis bg-success-subtle">Cost <span class="text-nowrap">Category | KPI (%)</span></th>
                    <th class="align-middle text-success-emphasis bg-success-subtle">Time <span class="text-nowrap">Category | KPI (%)</span></th>
                    <th class="align-middle text-success-emphasis bg-success-subtle">Error <span class="text-nowrap">Category | KPI (%)</span></th>
                    <th class="align-middle text-success-emphasis bg-success-subtle">Total <span class="text-nowrap">Score (%)</span></th>
                    <th class="align-middle text-success-emphasis bg-success-subtle">Weighted <span class="text-nowrap">Value</span></th>
                    <th class="align-middle text-primary-emphasis bg-primary-subtle">Total <span class="text-nowrap">Score (%)</span></th>
                    <th class="align-middle text-primary-emphasis bg-primary-subtle">Weighted <span class="text-nowrap">Value</span></th>
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
                        <!-- <tr><td>Project Value</td><td>Project value in billions.</td></tr> -->
                        <!-- <tr><td>Project Start | End</td><td>Project started till bast date.</td></tr> -->
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
                        <!-- <tr>
                            <td>Total CTE</td>
                            <td>
                                Total CTE = Cost KPI + Time KPI + Error KPI
                            </td>
                        </tr> -->
                        <tr>
                            <td>Total Score</td>
                            <td>
                                Total Score = 100% - Total CTE
                            </td>
                        </tr>
                        <!-- <tr>
                            <td>Weighted Ideal</td>
                            <td>
                                Weighted Ideal = Project Value * 100%
                            </td>
                        </tr> -->
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
                        <!-- <tr>
                            <td>Role</td>
                            <td>Role Resource</td>
                        </tr> -->
                        <!-- <tr>
                            <td>KPI Project Ideal</td>
                            <td>KPI Project Ideal = Weighted Ideal</td>
                        </tr> -->
                        <!-- <tr>
                            <td>KPI Project Actual</td>
                            <td>KPI Project Actual = Weighted Value</td>
                        </tr> -->
                        <!-- <tr>
                            <td>Start | End Assignment</td>
                            <td>The resource started the first time and the last date it was assigned</td>
                        </tr> -->
                        <!-- <tr>
                            <td>Progress Actual (%)</td>
                            <td>Percentage of resource involvement in the project</td>
                        </tr> -->
                        <!-- <tr>
                            <td>Progress Support</td>
                            <td>
                                <p>Status of resource involvement in the project</p>
                                <ul>
                                    <li>Penuh : Joined the project from start to finish, including those who were transferred due to complaints</li>
                                    <li>Mutasi : Normal addition or reduction until the project ends, subjective assessment (project value is reduced based on the remaining project progress)</li>
                                </ul>
                            </td>
                        </tr> -->
                        <tr>
                            <td>Total Score (%)</td>
                            <td>Total Score (%) = Total Score (%)</td>
                        </tr>
                        <!-- <tr>
                            <td>KPI Project Ideal Resource</td>
                            <td>KPI Project Ideal Resource = Weighted Ideal * Progress Actual</td>
                        </tr> -->
                        <!-- <tr>
                            <td>KPI Project Actual Resource</td>
                            <td>KPI Project Actual Resource = CTE (%) * KPI Project Ideal Resource</td>
                        </tr> -->
                        <tr>
                            <td>Total Weighted Value</td>
                            <td>Total Weighted Value = Total Weighted Actual / Total Weighted Ideal * 100</td>
                        </tr>
                        <!-- <tr>
                            <td>KPI Project Average Value</td>
                            <td>KPI Project Average Value = Total KPI Actual / Total KPI Ideal * 100</td>
                        </tr> -->
                    </table>
                </div>
            </div>
            <div class="card">
                <div class="card-header fw-bold">Other Information</div>
                <div class="card-body">
                    <table class="table table-striped">
                        <tr><td>Project Temporary</td><td>The project is temporary because there is no PO Customer yet.</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>