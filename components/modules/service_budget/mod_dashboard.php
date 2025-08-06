<?php
include( "components/classes/func_service_budget.php");

$mdlname = "SERVICE_BUDGET";
$DBLD = get_conn($mdlname);
$tblname = "trx_project_list";

$condition = "modified_date LIKE '" . date("Y-m-d") . "%' AND status<>'deleted'";
$order = "modified_date DESC";
$totals = $DBLD->get_data($tblname, $condition, $order);
?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-secondary">Service Budget</h6>
    </div>
    <div class="card-body">
        <div class="row mb-2">
            <?php
            $conditionUser = "";
            $sambung = "";
            if($_SESSION['Microservices_UserLevel']!="Administrator") {
                $conditionUser = "(create_by='" . $_SESSION['Microservices_UserEmail'] . "' OR modified_by='" . $_SESSION['Microservices_UserEmail'] . "')";
                $sambung = " AND ";
            }

            $condition = $conditionUser . $sambung . "(status='draft' OR status='rejected' OR status='reopen')";
            $tdoc1 = $DBLD->get_data($tblname, $condition);
            $condition = $conditionUser . $sambung . "status='submited'";
            $tdoc2 = $DBLD->get_data($tblname, $condition);
            $condition = $conditionUser . $sambung . "status='approved'";
            $tdoc3 = $DBLD->get_data($tblname, $condition);
            $condition = $conditionUser . $sambung . "status='acknowledge'";
            $tdoc4 = $DBLD->get_data($tblname, $condition);
            ?>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Drafted</div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 ml-3 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $tdoc1[2]; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Submited</div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 ml-3 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $tdoc2[2]; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Approved</div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 ml-3 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $tdoc3[2]; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Acknowledge</div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 ml-3 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $tdoc4[2]; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-12 col-md-6 mb-4">
                <div class="card border-left-secondary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Today Update</div>
                                        <div class="row no-gutters align-items-center">
                                            <div class="col-auto">
                                                <div class="h5 ml-3 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $totals[2]; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="text-xs mb-1 table-responsive">
                                            <table class="table table-sm">
                                                <tr class="bg-light"><th>Project Code</th><th>Order Number</th><th>SO Number</th><th>Update by</th><th>Status</th></tr>
                                                <?php
                                                if($totals[2]>0) {
                                                    do {
                                                        $name = $DBHCM->get_profile($totals[0]['modified_by'], "employee_name");
                                                        echo "<tr><td class='align-text-top'>" . $totals[0]['project_code'] . "</td><td class='align-text-top'>" . $totals[0]['order_number'] . "</td><td class='align-text-top'>" . $totals[0]['so_number'] . "</td><td class='align-text-top'>" . $name . "</td><td class='align-text-top'>" . $totals[0]['status'] . "</td></tr>";
                                                    } while($totals[0]=$totals[1]->fetch_assoc());
                                                }
                                                ?>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            // $mdlname = "SERVICE_BUDGET";
            // $DBLD = get_conn($mdlname);

            $subordinate = get_leader($_SESSION['Microservices_UserEmail'], 0);
            $ordinate ="";
            $sambung = "";
            if($subordinate[2]>0) {
                $ordinate.="(";
                do {
                    $ordinate.= $sambung . " create_by = '" . $subordinate[0]['employee_email'] . "'";
                    $sambung = " OR ";
                } while($subordinate[0]=$subordinate[1]->fetch_assoc());
                $ordinate.=" OR create_by ='" . $_SESSION['Microservices_UserEmail'] . "')";
            }
            $mysql = "select `solution_name`, avg(coalesce(`product`,0)) AS `product` from `sa_trx_project_list` join `sa_trx_project_solutions` on `sa_trx_project_list`.`project_id`=`sa_trx_project_solutions`.`project_id` where (`status`='approved' OR `status`='acknowledge') AND `so_number` IN (select `so_number` from `sa_trx_project_list` join `sa_trx_project_solutions` on `sa_trx_project_list`.`project_id`=`sa_trx_project_solutions`.`project_id` where (`status`='approved' OR `status`='acknowledge') AND `product`>0 AND sa_" . $tblname . ".modified_date>='" . date("Y-m-d", strtotime("-366 day")) . "' group by `so_number` order by `so_number`) group by `solution_name` order by `so_number`, `solution_name`;";
            $solutions_product = $DBLD->get_sql($mysql);
            $mysql = "select `solution_name`, avg(coalesce(`services`,0)) AS `service` from `sa_trx_project_list` join `sa_trx_project_solutions` on `sa_trx_project_list`.`project_id`=`sa_trx_project_solutions`.`project_id` where (`status`='approved' OR `status`='acknowledge') AND `so_number` IN (select `so_number` from `sa_trx_project_list` join `sa_trx_project_solutions` on `sa_trx_project_list`.`project_id`=`sa_trx_project_solutions`.`project_id` where (`status`='approved' OR `status`='acknowledge') AND `services`>0 AND sa_" . $tblname . ".modified_date>'" . date("Y-m-d", strtotime("-366 day")) . "' group by `so_number` order by `so_number`) group by `solution_name` order by `so_number`;";
            $solutions_service = $DBLD->get_sql($mysql);
            if($solutions_product[2]>0) {
                $solution_name = array('ASA'=>'Adaptive Security Architecture', 'BDA'=>'Big Data & Analytics', 'DCCI'=>'Data Center & Cloud Infrastructure', 'EC'=>'Enterprise Collaboratoion', 'DBM'=>'Digital Business Management', 'SP'=>'Service Provider');
                ?>
                <div class="col-xl-12 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs mb-1 table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr class="border-bottom bg-light"><th>Solution Name</th><th class="text-center">Product</th><th class="text-center">Service</th></tr>
                                            </thead>
                                            <tbody>
                                                <?php $tProduct=0; $tService=0; ?>
                                                <?php do { ?>
                                                    <?php
                                                    $mysql = "select `solution_name`, avg(coalesce(`services`,0)) AS `service` from `sa_trx_project_list` join `sa_trx_project_solutions` on `sa_trx_project_list`.`project_id`=`sa_trx_project_solutions`.`project_id` where (`status`='approved' OR `status`='acknowledge') AND `so_number` IN (select `so_number` from `sa_trx_project_list` join `sa_trx_project_solutions` on `sa_trx_project_list`.`project_id`=`sa_trx_project_solutions`.`project_id` where (`status`='approved' OR `status`='acknowledge') AND `services`>0 group by `so_number` order by `so_number`) AND `solution_name`='" . $solutions_product[0]['solution_name'] . "' group by `solution_name` order by `so_number`;";
                                                    $solutions_service = $DBLD->get_sql($mysql);
                                                    if($solutions_service[2]>0) {
                                                        $sService = $solutions_service[0]['service'];
                                                    } else {
                                                        $sService = 0;
                                                    }
                                                    ?>
                                                    <tr><td><?php echo $solution_name[$solutions_product[0]['solution_name']]; ?></td><td class="text-right"><?php echo number_format($solutions_product[0]['product'], 2, ",", "."); ?>%</td><td class="text-right"><?php echo number_format($sService, 2, ",", "."); ?>%</td></tr>
                                                        <?php $tProduct += $solutions_product[0]['product']; $tService += $sService; ?>
                                                <?php } while($solutions_product[0]=$solutions_product[1]->fetch_assoc()); ?>
                                            </tbody>
                                            <tfoot>
                                            <tr class="border-top bg-light"><th>Total</th><th class="text-right"><?php echo number_format($tProduct, 2, ",", "."); ?>%</th><th class="text-right"><?php echo number_format($tService, 2, ",", "."); ?>%</th></tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>