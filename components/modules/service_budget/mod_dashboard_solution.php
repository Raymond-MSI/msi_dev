<?php
if((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $author = 'Syamsul Arham';
} else {

    $mdlname = "SERVICE_BUDGET";
    $DBSOL = get_conn($mdlname);

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
    $mysql = "select `solution_name`, avg(coalesce(`product`,0)) AS `product` from `sa_trx_project_list` join `sa_trx_project_solutions` on `sa_trx_project_list`.`project_id`=`sa_trx_project_solutions`.`project_id` where `status`='approved' AND `so_number` IN (select `so_number` from `sa_trx_project_list` join `sa_trx_project_solutions` on `sa_trx_project_list`.`project_id`=`sa_trx_project_solutions`.`project_id` where `status`='approved' AND `product`>0 AND `sa_trx_project_solutions`.`modified_date`>='" . date("Y-m-d", strtotime("-366 day")) . "' group by `so_number` order by `so_number`) group by `solution_name` order by `so_number`, `solution_name`;";
    $solutions_product = $DBSOL->get_sql($mysql);
    $mysql = "select `solution_name`, avg(coalesce(`services`,0)) AS `service` from `sa_trx_project_list` join `sa_trx_project_solutions` on `sa_trx_project_list`.`project_id`=`sa_trx_project_solutions`.`project_id` where `status`='approved' AND `so_number` IN (select `so_number` from `sa_trx_project_list` join `sa_trx_project_solutions` on `sa_trx_project_list`.`project_id`=`sa_trx_project_solutions`.`project_id` where `status`='approved' AND `services`>0 AND `sa_trx_project_solutions`.`modified_date`>'" . date("Y-m-d", strtotime("-366 day")) . "' group by `so_number` order by `so_number`) group by `solution_name` order by `so_number`;";
    $solutions_service = $DBSOL->get_sql($mysql);

    if($solutions_product[2]>0) {
        $solution_name = array('ASA'=>'Adaptive Security Architecture', 'BDA'=>'Big Data & Analytics', 'DCCI'=>'Data Center & Cloud Infrastructure', 'EC'=>'Enterprise Collaboratoion', 'DBM'=>'Digital Business Management', 'SP'=>'Service Provider');
        
        ?>

        <div class="card shadow mb-3">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-secondary">Solution</h6>
            </div>
            <div class="card-body" style="font-size:12px">
            <table width="100%">
                <thead>
                <tr class="border-bottom"><th>Solution Name</th><th class="text-center">Product</th><th class="text-center">Service</th></tr>
                </thead>
                <tbody>
                <?php $tProduct=0; $tService=0; ?>
                <?php do { ?>
                    <?php
                    $mysql = "select `solution_name`, avg(coalesce(`services`,0)) AS `service` from `sa_trx_project_list` join `sa_trx_project_solutions` on `sa_trx_project_list`.`project_id`=`sa_trx_project_solutions`.`project_id` where `status`='approved' AND `so_number` IN (select `so_number` from `sa_trx_project_list` join `sa_trx_project_solutions` on `sa_trx_project_list`.`project_id`=`sa_trx_project_solutions`.`project_id` where `status`='approved' AND `services`>0 group by `so_number` order by `so_number`) AND `solution_name`='" . $solutions_product[0]['solution_name'] . "' group by `solution_name` order by `so_number`;";
                    $solutions_service = $DBSOL->get_sql($mysql);
                    ?>
                    <tr><td><?php echo $solution_name[$solutions_product[0]['solution_name']]; ?></td><td class="text-right"><?php echo number_format($solutions_product[0]['product'], 2, ",", "."); ?>%</td><td class="text-right"><?php echo number_format($solutions_service[0]['service'], 2, ",", "."); ?>%</td></tr>
                        <?php $tProduct += $solutions_product[0]['product']; $tService += $solutions_service[0]['service']; ?>
                <?php } while($solutions_product[0]=$solutions_product[1]->fetch_assoc()); ?>
                </tbody>
                <tfoot>
                <tr class="border-top"><th>Total</th><th class="text-right"><?php echo number_format($tProduct, 2, ",", "."); ?>%</th><th class="text-right"><?php echo number_format($tService, 2, ",", "."); ?>%</th></tr>
                </tfoot>
            </table>
            </div>
        </div>
    <?php } ?>
<?php } ?> 
