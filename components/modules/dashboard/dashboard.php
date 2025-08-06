<div class="row">
    <div class="col-lg-6 mb-6">
        <?php menu_dashboard(); ?>
        <?php
        $reportName = "REPORT_SBF_SOLUTION_PRODUCT";
        $data = get_coding($reportName);
        eval("?>" . $data[0]['config_value']);

        $reportName = "REPORT_SBF_SOLUTION_SERVICE";
        $data = get_coding($reportName);
        eval("?>" . $data[0]['config_value']);

        $reportName = "REPORT_SBF_SOLUTION";
        $data = get_coding($reportName);
        eval("?>" . $data[0]['config_value']);

        $reportName = "REPORT_SBF_STATUS";
        $data = get_coding($reportName);
        eval("?>" . $data[0]['config_value']);

        $reportName = "REPORT_SBF_BUNDLING";
        $data = get_coding($reportName);
        eval("?>" . $data[0]['config_value']);
        ?>
    </div>
    <div class="col-lg-6 mb-6">
        <?php
        $reportName = "REPORT_EDO_REQUEST";
        $data = get_coding($reportName);
        eval("?>" . $data[0]['config_value']);

        $reportName = "REPORT_EDO_LEAVE";
        $data = get_coding($reportName);
        eval("?>" . $data[0]['config_value']);

        $reportName = "REPORT_ASSET_MOVING";
        $data = get_coding($reportName);
        eval("?>" . $data[0]['config_value']);

        $reportName = "REPORT_ASSET_LOCATION";
        $data = get_coding($reportName);
        eval("?>" . $data[0]['config_value']);

        $reportName = "REPORT_ASSET_CISCO_LDOS";
        $data = get_coding($reportName);
        eval("?>" . $data[0]['config_value']);

        $reportName = "REPORT_CISCO_LDOS";
        $data = get_coding($reportName);
        eval("?>" . $data[0]['config_value']);
        ?>
    </div>
</div>

<script>
    window.onload = function() {

        var chart = new CanvasJS.Chart("chartProduct", {
            animationEnabled: true,
            data: [{
                type: "pie",
                indexLabel: "{y}",
                yValueFormatString: "#,##0.00\"%\"",
                // indexLabelPlacement: "inside",
                // indexLabelFontColor: "#fff",
                showInLegend: true,
                legendText: "{label}",
                indexLabelFontSize: 11,
                dataPoints: <?php echo json_encode($sProducts, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chart.render();

        var chart = new CanvasJS.Chart("chartService", {
            animationEnabled: true,
            data: [{
                type: "pie",
                indexLabel: "{y}",
                // indexLabel: "{label} ({y})",
                yValueFormatString: "#,##0.00\"%\"",
                // indexLabelPlacement: "inside",
                // indexLabelFontColor: "#fff",
                showInLegend: true,
                legendText: "{label}",
                indexLabelFontSize: 11,
                dataPoints: <?php echo json_encode($sService, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chart.render();

        var chart = new CanvasJS.Chart("chartSBStatus", {
            animationEnabled: true,
            data: [{
                type: "pie",
                indexLabel: "{label} ({y})",
                // indexLabel: "{y}",
                yValueFormatString: "#,##0\"\"",
                // indexLabelPlacement: "inside",
                // indexLabelFontColor: "#fff",
                // showInLegend: true,
                // legendText: "{label}",
                indexLabelFontSize: 11,
                dataPoints: <?php echo json_encode($dataSBStatus, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chart.render();

        var chart = new CanvasJS.Chart("chartSBBundling", {
            animationEnabled: true,
            data: [{
                type: "bar",
                // indexLabel: "{label} ({y})",
                indexLabel: "{y}",
                yValueFormatString: "#,##0\"\"",
                // indexLabelPlacement: "inside",
                // indexLabelFontColor: "#fff",
                // showInLegend: true,
                // legendText: "{label}",
                indexLabelFontSize: 11,
                dataPoints: <?php echo json_encode($dataSBBundling, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chart.render();

        var chart = new CanvasJS.Chart("chartEDO", {
            animationEnabled: true,
            // axisY:{
            //     title: "Leave",
            //     lineColor: "#C24642",
            //     tickColor: "#C24642",
            //     labelFontColor: "#C24642",
            //     titleFontColor: "#C24642",
            //     includeZero: true,
            //     // suffix: "k"
            // },
            //     axisY2: {
            //     title: "Request",
            //     lineColor: "#7F6084",
            //     tickColor: "#7F6084",
            //     labelFontColor: "#7F6084",
            //     titleFontColor: "#7F6084",
            //     includeZero: true,
            //     // prefix: "$",
            //     // suffix: "k"
            // },
            data: [{
                type: "line",
                name: "Request",
                // indexLabel: "{label} ({y})",
                indexLabel: "{y}",
                yValueFormatString: "#,##0\"\"",
                // indexLabelPlacement: "inside",
                // indexLabelFontColor: "#fff",
                showInLegend: true,
                // legendText: "{label}",
                axisYIndex: 1,
                // axisYType: "secondary",
                indexLabelFontSize: 11,
                dataPoints: <?php echo json_encode($dataEDO, JSON_NUMERIC_CHECK); ?>
            }, {
                type: "line",
                name: "Leave",
                // indexLabel: "{label} ({y})",
                indexLabel: "{y}",
                yValueFormatString: "#,##0\"\"",
                // indexLabelPlacement: "inside",
                // indexLabelFontColor: "#fff",
                showInLegend: true,
                // legendText: "{label}",
                axisYIndex: 0,
                indexLabelFontSize: 11,
                dataPoints: <?php echo json_encode($dataEDOLeave, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chart.render();

        var chart = new CanvasJS.Chart("chartAssetMoving", {
            animationEnabled: true,
            data: [{
                type: "bar",
                // indexLabel: "{label} ({y})",
                indexLabel: "{y}",
                yValueFormatString: "#,##0\"\"",
                // indexLabelPlacement: "inside",
                // indexLabelFontColor: "#fff",
                // showInLegend: true,
                // legendText: "{label}",
                indexLabelFontSize: 11,
                dataPoints: <?php echo json_encode($AssetMoving, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chart.render();

        var chart = new CanvasJS.Chart("chartAssetLocation", {
            animationEnabled: true,
            data: [{
                type: "bar",
                // indexLabel: "{label} ({y})",
                indexLabel: "{y}",
                yValueFormatString: "#,##0\"\"",
                // indexLabelPlacement: "inside",
                // indexLabelFontColor: "#fff",
                // showInLegend: true,
                // legendText: "{label}",
                indexLabelFontSize: 11,
                dataPoints: <?php echo json_encode($AssetLocation, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chart.render();

        var chart = new CanvasJS.Chart("chartAssetLDOS", {
            animationEnabled: true,
            data: [{
                type: "bar",
                // indexLabel: "{label} ({y})",
                indexLabel: "{y}",
                yValueFormatString: "#,##0\"\"",
                // indexLabelPlacement: "inside",
                // indexLabelFontColor: "#fff",
                // showInLegend: true,
                // legendText: "{label}",
                indexLabelFontSize: 11,
                dataPoints: <?php echo json_encode($AssetLDOS, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chart.render();

        var chart = new CanvasJS.Chart("chartCISCOldos", {
            animationEnabled: true,
            data: [{
                type: "column",
                // indexLabel: "{label} ({y})",
                indexLabel: "{y}",
                yValueFormatString: "#,##0\"\"",
                // indexLabelPlacement: "inside",
                // indexLabelFontColor: "#fff",
                // showInLegend: true,
                // legendText: "{label}",
                indexLabelFontSize: 11,
                dataPoints: <?php echo json_encode($dataCISCOldos, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chart.render();

    }
</script>
<?php include("components/modules/dashboard/toastShow.php"); ?>