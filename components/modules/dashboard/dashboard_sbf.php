<div class="row">
    <div class="col-lg-6">
        <?php menu_dashboard(); ?>
    </div>
    <div class="col-lg-6">
    </div>
</div>

<?php
$mdlname = "SERVICE_BUDGET";
$userpermission = useraccess_v2($mdlname);
if($userpermission['mdllevel'] != '') 
{
    ?>

    <div class="row mb-3" style="font-size: 13px">
        <div class="col-lg-12">
            <div class="card shadow">
                <?php
                $reportName ="REPORT_SBF_CUSTOMER";
                $data = get_coding($reportName);
                eval("?>".$data[0]['config_value']);
                ?>
                <?php
                $reportName ="REPORT_SBF_SOLUTION";
                $data = get_coding($reportName);
                eval("?>".$data[0]['config_value']);
                ?>
            </div>
        </div>
    </div>

    <div class="row mb-3" style="font-size: 13px">
        <div class="col-lg-4">
            <?php
            $reportName ="REPORT_SBF_MY_ACTIVITY";
            $data = get_coding($reportName);
            eval("?>".$data[0]['config_value']);
            ?>

            <?php
            $reportName ="REPORT_SBF_MONTHLY_ACTIVITY";
            $data = get_coding($reportName);
            eval("?>".$data[0]['config_value']);
            ?>

        </div>
        <div class="col-lg-4">
            <?php
            $reportName ="REPORT_SBF_TODO_LIST";
            $data = get_coding($reportName);
            eval("?>".$data[0]['config_value']);
            ?>

        </div>
        <div class="col-lg-4">
            <?php
            $reportName ="REPORT_SBF_WAITING_TO_ORDER";
            $data = get_coding($reportName);
            eval("?>".$data[0]['config_value']);
            ?>


            <!-- <?php
            // $mdlname = "NAVISION";
            // $DBNAV = get_conn($mdlname);
            // $tblname = "mst_order_number";
            // $condition = "status_order=0 AND project_code <>'' AND (so_number LIKE '%/SO/%' OR so_number ='') AND order_number <> ''";
            // $order = "order_id DESC";
            // $OrderNumber = $DBNAV->get_data($tblname, $condition, $order);
            // if($OrderNumber[2]>0)
            // {
                ?>
                <script>
                    $(document).ready(function () {
                        $('#order').DataTable({
                            dom: 'ifrtp',
                            "ordering": false
                        });
                    });
                </script>
                <div class="card shadow mb-3">
                    <div class="card-header fw-bold">
                        Waiting to Order
                    </div>
                    <div class="card-body">
                        <table class="table" id="order">
                            <thead>
                                <tr>
                                    <th>Project Code</th>
                                    <th>SO Number</th>
                                    <th>Order Number</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // do {
                                    ?>
                                    <tr>
                                        <td class="p-1">
                                            <a href="index.php?mod=service_budget&act=order&project_code=<?php //echo $OrderNumber[0]['project_code']; ?>&order_number=<?php //echo $OrderNumber[0]['order_number']; ?>&submit=Submit" class="text-decoration-none text-reset">
                                                <?php //echo $OrderNumber[0]['project_code']; ?>
                                            </a>
                                        </td>
                                        <td class="p-1"><?php //echo $OrderNumber[0]['so_number']; ?></td>
                                        <td class="p-1"><?php //echo $OrderNumber[0]['order_number']; ?></td>
                                    </tr>
                                    <?php
                                // } while($OrderNumber[0]=$OrderNumber[1]->fetch_assoc());
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php
            // }
            ?> -->
        </div>
    </div>
    <?php
} else
{
    $ALERT->notpermission();
}
?>

<script>
window.onload = function () {


    var chart = new CanvasJS.Chart("chartSBStatus", {
        animationEnabled: true,
        toolTip: {
            shared: true
        },
        data: [
        {
            type: "column",
            name: "Total SBF",
            showInLegend: "true",
            dataPoints: <?php echo json_encode($dataSBbyMonthTotal, JSON_NUMERIC_CHECK); ?>
        },
        {
            type: "line",
            name: "Bundling",
            showInLegend: "true",
            dataPoints: <?php echo json_encode($dataSBbyMonthBdl, JSON_NUMERIC_CHECK); ?>
       },
        {
            type: "line",
            name: "Trade",
            showInLegend: "true",
            dataPoints: <?php echo json_encode($dataSBbyMonthTrade, JSON_NUMERIC_CHECK); ?>
        }
        ]
    });
    chart.render();

    var chart = new CanvasJS.Chart("chartSBFbyMonth", {
        animationEnabled: true,
        toolTip: {
            shared: true
        },
        data: [
        {
            type: "column",
            name: "Total SBF",
            showInLegend: "true",
            yValueFormatString: "#,##0.##M",
            dataPoints: <?php echo json_encode($dataSBFbyMonthTotal, JSON_NUMERIC_CHECK); ?>
        },
        {
            type: "line",
            name: "Bundling",
            showInLegend: "true",
            yValueFormatString: "#,##0.##M",
            dataPoints: <?php echo json_encode($dataSBFbyMonthBdl, JSON_NUMERIC_CHECK); ?>
        },
        {
            type: "line",
            name: "Trade",
            showInLegend: "true",
            yValueFormatString: "#,##0.##M",
            dataPoints: <?php echo json_encode($dataSBFbyMonthTrade, JSON_NUMERIC_CHECK); ?>
        }
        ]
    });
    chart.render();

}
</script>
