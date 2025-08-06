<?php
$mdlname = "HCM";
$DBHCM = get_conn($mdlname);
$mdlname = "SERVICE_BUDGET";
$DBSB = get_conn($mdlname);

if(isset($_GET['project_code'])) {
    $project_code = " AND `project_code`='" . $_GET['project_code'] . "'";
} else {
    $project_code = "";
}
$mysql = "SELECT `sa_logs`.`entry_date`, `sa_trx_project_list`.`project_code`, `sa_trx_project_list`.`order_number`, `sa_logs`.`description`, `sa_logs`.`entry_by` FROM `sa_trx_project_list` LEFT JOIN `sa_logs` ON `sa_logs`.`project_id` = `sa_trx_project_list`.`project_ID` WHERE (isnull(`sa_logs`.`description`) OR `sa_logs`.`description`!='')" . $project_code . " ORDER BY `log_id` DESC LIMIT 0, 100";
$logs = $DBSB->get_sql($mysql);
if($logs[2]>0) {
    ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-secondary">Log Activity Service Budget</h6>
            <?php spinner(); ?>
            <div class="align-items-right">
                <!-- <a href="index.php?mod=service_budget" class="btn btn-secondary" title='Back to Service Budget' style="font-size:10px"><i class='fa fa-arrow-left'></i></a> -->
                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#filter" style="font-size:10px"><span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Filter data'><i class='fa fa-filter'></i></span></button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-sm">
                <thead><tr><th>Date</th><th>Time</th><th>Activity</th></thead>
                <tbody>
                <?php
                $tgl = "";
                $jam = "";
                do {
                    $name = $DBHCM->get_leader_v2($logs[0]['entry_by']);
                    ?>
                    <tr>
                        <td>
                            <?php if($tgl != date("Y-m-d", strtotime($logs[0]['entry_date']))) { ?>
                                <table style="width: 100px">
                                    <tr>
                                        <td class="text-center bg-secondary text-light" colspan="2">
                                            <?php echo date("Y", strtotime($logs[0]['entry_date'])); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center" style="background-color: #aeaeae; width: 50%">
                                            <?php echo date("M", strtotime($logs[0]['entry_date'])); ?>
                                        </td>
                                        <td class="text-center" style="background-color: #eaeaea; width: 50%">
                                            <?php echo date("d", strtotime($logs[0]['entry_date'])); ?>
                                        </td>
                                    </tr>
                                </table>
                            <?php } ?>
                        </td>
                        <td>
                            <?php echo $jam != date("G:i:s", strtotime($logs[0]['entry_date'])) ? date("G:i:s", strtotime($logs[0]['entry_date'])) : NULL; ?>
                        </td>
                        <td>
                            <?php echo "Project Code : " . $logs[0]['project_code']; ?><br/>
                            <?php echo "Order Number : " . $logs[0]['order_number']; ?><br/>
                            <?php echo "Description : " . $logs[0]['description']; ?><br/>
                            <?php echo "Performed by : " . $logs[0]['entry_by']; ?>
                        </td>
                    <?php
                    $tgl = date("Y-m-d", strtotime($logs[0]['entry_date']));
                    $jam = date("G:i:s", strtotime($logs[0]['entry_date']));
                } while($logs[0]=$logs[1]->fetch_assoc());
                ?>
                </tbody>
            </table>
        </div>
    </div>
<?php } ?>

<!-- Modal -->
<form method="get" action="index.php">
<div class="modal fade" id="filter" tabindex="-1" aria-labelledby="saveLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="saveLabel"><b>Project Code</b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-sm-12">
                        <input type="text" class="form-control form-control-sm" name="project_code" id="project_code" value="<?php if(isset($_GET['project_code'])) { echo $_GET['project_code']; } ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="mod" value="activity_log_v2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <input type="submit" class="btn btn-primary" name="save_service_budget" id="save_service_budget" value="Save">
                </div>
            </div>
        </div>
    </div>
</div>
