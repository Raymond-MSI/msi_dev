<style>
  #backButton {
	border-radius: 4px;
	padding: 8px;
	border: none;
	font-size: 16px;
	background-color: #2eacd1;
	color: white;
	position: absolute;
	top: 50px;
	right: 10px;
	cursor: pointer;
  }
  .invisible {
    display: none;
  }
</style>

<?php
$mdlname = "KPI_PROJECT";
$DBRPT = get_conn($mdlname);

function content($title, $value, $color="danger", $width="12")
{
    ?>
    <div class="col-lg-<?php echo $width; ?>">
        <div class="card border-left-<?php echo $color; ?> shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="text-xs font-weight-bold text-<?php echo $color; ?> text-uppercase mb-1"><?php echo $title; ?></div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 ml-3 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $value; ?></div>
                                    </div>
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

$conditionProject = "";
$conditionCTEProject = "";
$conditionCTEEngineer = "";
$periode = date("Y");
if(isset($_GET['btn_search']))
{
    $sambung = "";

    if(isset($_GET['periode']))
    {
        $periode = $_GET['periode'];
    }
    $conditionPeriode = "periode=" . $periode;

    if(isset($_GET['project_code']) && $_GET['project_code']!="")
    {
        $project_code = trim($_GET['project_code']);
        $conditionProject .= "`sa_dashboard_kpi`.`sa_data_project`.`project_code_kpi` LIKE '%" . $project_code . "%'";
        $conditionCTEProject .= "`sa_dashboard_kpi`.`sa_kpi_project_wr`.`project_code` LIKE '%" . $project_code . "%'";
        $sambung = " AND ";
    }
    if(isset($_GET['project_name']) && $_GET['project_name']!="")
    {
        $project_name = trim($_GET['project_name']);
        $conditionProject .= $sambung . "`sa_ps_service_budgets`.`sa_trx_project_list`.`project_name_internal` LIKE '%" . $project_name . "%'";
        $conditionCTEProject .= $sambung . "`sa_ps_service_budgets`.`sa_trx_project_list`.`project_name_internal` LIKE '%" . $project_name . "%'";
        $sambung = " AND ";
    }
    if(isset($_GET['customer_name']) && $_GET['customer_name']!="")
    {
        $customer_name = trim($_GET['customer_name']);
        $conditionProject .= $sambung . "`sa_ps_service_budgets`.`sa_trx_project_list`.`customer_name` LIKE '%" . $customer_name . "%'";
        $conditionCTEProject .= $sambung . "`sa_ps_service_budgets`.`sa_trx_project_list`.`customer_name` LIKE '%" . $customer_name . "%'";
        $sambung = " AND ";
    }
    if(isset($_GET['employee_name']) && $_GET['employee_name']!="")
    {
        $employee_name = trim($_GET['employee_name']);
        // $conditionProject .= $sambung . "`sa_ps_service_budgets`.`sa_trx_project_list`.`employee_name`='" . $employee_name . "'";
        // $conditionCTEProject .= $sambung . "`sa_ps_service_budgets`.`sa_trx_project_list`.`employee_name`='" . $employee_name . "'";
        $conditionCTEEngineer .= "`sa_dashboard_kpi`.`sa_summary_user`.`Nama` LIKE '%" . $employee_name . "%'";
        // $sambung = " AND ";
    }
    if(isset($_GET['project_status']) && $_GET['project_status']=="closed")
    {
        $project_status = trim($_GET['project_status']);
        $conditionProject .= $sambung . "`sa_dashboard_kpi`.`sa_data_project`.`WR_status_project` = 'closed'";
        $conditionCTEProject .= $sambung . "`sa_dashboard_kpi`.`sa_kpi_project_wr`.`status_project` = 'closed'";
    } else
    {
        $project_status = trim($_GET['project_status']);
        $conditionProject .= $sambung . "`sa_dashboard_kpi`.`sa_data_project`.`WR_status_project` <> 'closed'";
        $conditionCTEProject .= $sambung . "`sa_dashboard_kpi`.`sa_kpi_project_wr`.`status_project` <> 'closed'";
    }
    $conditionProject = " WHERE " . $conditionProject;
    $conditionCTEProject = " WHERE " . $conditionCTEProject;
    // $conditionCTEEngineer = " WHERE " . $conditionCTEEngineer;
} else
{
    $conditionProject .= "`sa_dashboard_kpi`.`sa_data_project`.`WR_status_project` = 'closed'";
    $conditionCTEProject .= "`sa_dashboard_kpi`.`sa_kpi_project_wr`.`status_project` = 'closed'";
    $conditionProject = " WHERE " . $conditionProject;
    $conditionCTEProject = " WHERE " . $conditionCTEProject;
}
?>

<div class="row">
    <div class="col-lg-6">
        <?php menu_dashboard(); ?>

    </div>

    <div class="col-lg-6">
        <div class="row">
            <div class="col-lg-12">
                <!-- <div class="card shadow">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12"> -->
                                    <div class="col-lg-12 text-right">
                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#filter">
                                        <i class="fa-solid fa-filter"></i>
                                        </button>
                                    </div>
                            <!-- </div>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="row mb-3">
            <?php
            $mysql = "SELECT if(`status_wr` like '%closed%', 'closed', 'open') AS `statusx`, if(`status_wr` like '%closed%', SUM(`value`), SUM(`value`)) AS `value` FROM `sa_kpi_project_wr` GROUP BY `statusx` ORDER BY `statusx` DESC; ";
            $totalValue = $DBRPT->get_sql($mysql);
            content("Periode", $periode, "danger", "3");
            if($totalValue[2]>0)
            {
                $i=0;
                do {
                    if($i==1)
                    {
                        content($totalValue[0]['statusx'] . " Project", "IDR." . number_format($totalValue[0]['value'],2), "primary", "3");
                    }
                    $i++;
                } while($totalValue[0]=$totalValue[1]->fetch_assoc());
            }

            $mdlname = "KPI_PROJECT";
            $DBRPT = get_conn($mdlname);
            $tblname = "summary_user";
            // $condition = "Nama = '" . $_SESSION['Microservices_UserName'] . "<" . $_SESSION['Microservices_UserEmail'] . ">'";
            $condition = "Nama = 'Desi Permatasari <desi.permatasari@mastersystem.co.id>'";
            $myKPI = $DBRPT->get_data($tblname, $condition);
            if($myKPI[2]>0)
            {
                content("My KPI", number_format($myKPI[0]['nilai_project'],2), "success", 3);
            }

            content("Productivity", number_format($myKPI[0]['produktifitas'],2), "info", 3);
            ?>
        </div>
    </div>

    <?php
    $reportName ="REPORT_KPI_MANDAYS_REALIZATION2";
    $data = get_coding($reportName);
    eval("?>".$data[0]['config_value']);

    $reportName ="REPORT_KPI_MANDAYS_REALIZATION1";
    $data = get_coding($reportName);
    eval("?>".$data[0]['config_value']);

    $reportName ="REPORT_KPI_BAST_DELAY2";
    $data = get_coding($reportName);
    eval("?>".$data[0]['config_value']);

    $reportName ="REPORT_KPI_TIMELINE";
    $data = get_coding($reportName);
    eval("?>".$data[0]['config_value']);

    $reportName ="REPORT_KPI_Engineer2";
    $data = get_coding($reportName);
    eval("?>".$data[0]['config_value']);

    $reportName ="REPORT_KPI_ENGINEER1";
    $data = get_coding($reportName);
    eval("?>".$data[0]['config_value']);

    $reportName ="REPORT_KPI_MANDAYS";
    $data = get_coding($reportName);
    eval("?>".$data[0]['config_value']);

    ?>
</div>
<div class="row">
<div class="card-body">

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active text-body" id="home-tab" data-bs-toggle="tab" data-bs-target="#tabCTEProject" type="button" role="tab" aria-controls="SBList" aria-selected="true" title='SB yang masih dalam bentuk draft'>CTE Project</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link text-body" id="submit-tab" data-bs-toggle="tab" data-bs-target="#tabCTEEngineer" type="button" role="tab" aria-controls="CTEEngineer" aria-selected="false" title='SB yang sudah disubmit ke manager'>CTE Engineer</button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="tabCTEProject" role="tabpanel" aria-labelledby="CTEProject-tab">
            <?php
            $reportName ="REPORT_KPI_CTE_PROJECT";
            $data = get_coding($reportName);
            eval("?>".$data[0]['config_value']);
            ?>
        </div>
        <div class="tab-pane" id="tabCTEEngineer" role="tabpanel" aria-labelledby="CTEEngineer-tab">
            <?php
            $reportName ="REPORT_KPI_CTE_ENGINEER";
            $data = get_coding($reportName);
            eval("?>".$data[0]['config_value']);
            ?>
        </div>
    </div>
</div>

<script>
window.onload = function () {

    var chart = new CanvasJS.Chart("chartKPIEngineer", {
        animationEnabled: true,
        axisX:
        {
            gridThickness: 0,
            tickLength: 0,
            lineThickness: 0,
            labelFormatter: function(){
                return " ";
            }
        },
        axisY:
        {
            includeZero: true,
            maximum: 100
        },
        toolTip: {
            shared: true,
        },
        legend: {
            cursor: "pointer",
            itemclick: toggleDataSeries
        },
        data: [{
            type: "stackedArea",
            toolTipContent: "<table><tr><td>Engineer Name</td><td>: {label}</td></tr><tr><td>Appraisal</td><td>: {y}</td></tr>",
            yValueFormatString: "#,##0.00",
            showInLegend: true,
            legendText: "{category}",
            indexLabelFontSize: 11,
            dataPoints: <?php echo json_encode($dataKPIUser1, JSON_NUMERIC_CHECK); ?>
        },
        {
            type: "stackedArea",
            showInLegend: true,
            toolTipContent: "<tr><td>KPI Project</td><td>: {y}</td></tr><tr><td>Total</td><td>: {total_kpi}</td></tr><tr><td>Total Project</td><td>: {productivitas}</td></tr><tr><td>Status</td><td>: {status_project}</td></tr></table>",
            yValueFormatString: "#,##0.00",
            legendText: "{category}",
            indexLabelFontSize: 11,
            dataPoints: <?php echo json_encode($dataKPIUser2, JSON_NUMERIC_CHECK); ?>
        }]
    });
    chart.render();

    var chart = new CanvasJS.Chart("chartKPIMandays", {
        animationEnabled: true,
        subtitles: [{
            text: "Total Mandays.",
            fontFamily: "calibri",
            fontSize: 14
        }],
        legend: {
            verticalAlign: "center",  // "top" , "center", "bottom"
            horizontalAlign: "right"  // "left", "center" , "right"
        },
        data: [{
            type: "pie",
            startAngle: -90,
            indexLabel: "{label} ({y})",
            yValueFormatString: "#,##0.00\"%\"",
            showInLegend: true,
            legendText: "{label}",
            indexLabelFontSize: 11,
            dataPoints: <?php echo json_encode($dataKPIMandays, JSON_NUMERIC_CHECK); ?>
        }]
    });
    chart.render();

    var chart = new CanvasJS.Chart("chartKPIMandaysRealization1", {
        animationEnabled: true,
        axisX:
        {
            gridThickness: 0,
            tickLength: 0,
            lineThickness: 0,
            labelFormatter: function(){
                return " ";
            }
        },
        axisY:
        {
            // includeZero: true
        },
        toolTip: {
            shared: true,
        },
        legend: {
            cursor: "pointer",
            itemclick: toggleDataSeries,
        },
        data: [
        {
            type: "stackedColumn",
            toolTipContent: "<table><tr><td>Project Code</td><td>: {project_code}</td></tr><tr><td>Project Name</td><td>: {project_name}</td></tr><tr><td>Customer Name</td><td>: {customer_name}</td></tr><tr><td>Sales Name</td><td>: {sales_name}</td></tr><tr><td>Service Budget</td><td>: {y}</td></tr>",
            // indexLabel: "{y}",
            yValueFormatString: "#,##0.00\"%\"",
            showInLegend: true,
            legendText: "Change Request",
            indexLabelFontSize: 11,
            dataPoints: <?php echo json_encode($dataKPIRealization11, JSON_NUMERIC_CHECK); ?>
        },
        {
            type: "stackedColumn",
            toolTipContent: "<tr><td>Change Request</td><td>: {y}</td></tr>",
            // indexLabel: "{y}",
            yValueFormatString: "#,##0.00\"%\"",
            showInLegend: true,
            legendText: "Service Budget",
            indexLabelFontSize: 11,
            dataPoints: <?php echo json_encode($dataKPIRealization12, JSON_NUMERIC_CHECK); ?>
        },
        {
            type: "line",
            toolTipContent: "<tr><td>Actual</td><td>: {y}</td></tr><tr><td>Status</td><td>: {status_project}</td></tr></table>",
            // indexLabel: "{y}",
            yValueFormatString: "#,##0.00\"%\"",
            showInLegend: true,
            legendText: "Actual",
            indexLabelFontSize: 11,
            dataPoints: <?php echo json_encode($dataKPIRealization13, JSON_NUMERIC_CHECK); ?>
        }]
    });
    chart.render();

    var chart = new CanvasJS.Chart("chartKPITimeLine", {
        animationEnabled: true,
        axisX:
        {
            gridThickness: 0,
            tickLength: 0,
            lineThickness: 0,
            labelFormatter: function(){
                return " ";
            }
        },
        toolTip: {
            shared: true,
        },
        legend: {
            cursor: "pointer",
            itemclick: toggleDataSeries,
        },
        data: [{
            type: "spline",
            toolTipContent: "<table><tr><td>Project Code</td><td>: {label}</td></tr><tr><td>SO Number</td><td>: {so_number}</td></tr><tr><td>Project Name</td><td>: {project_name}</td></tr><tr><td>Customer Name</td><td>: {customer_name}</td></tr><tr><td>Sales Name</td><td>: {sales_name}</td></tr><tr><td>BAST Plan</td><td>: {bast_plan}</td></tr><tr><td>BAST Actual</td><td>: {bast_actual}</td></tr><tr><td>BAST Delay</td><td>: {bast_delay} (days)</td></tr></table>",
            indexLabelFontSize: 11,
            dataPoints: <?php echo json_encode($dataKPITimeLine, JSON_NUMERIC_CHECK); ?>
        }]
    });
    chart.render();

    // Mandays Realization
    var MandaysRealization = {
        "KPI Mandays Realization": [{
            animationEnabled: true,
            click: MandaysRealizationChartDrilldownHandler,
            name: "KPI Mandays Realization",
            cursor: "pointer",
            explodeOnClick: false,
            startAngle: -90,
            radius: "100%",
            innerRadius: "50%",
            indexLabel: "{y}",
            yValueFormatString: "#,##0",
            showInLegend: true,
            type: "doughnut",
            dataPoints: <?php echo json_encode($dataKPIRealization, JSON_NUMERIC_CHECK); ?>
        }],
        "Under Budget (less than 100%)": [{
            animationEnabled: true,
            name: "Less than 100%",
            indexLabel: "{y}\"%\"",
            yValueFormatString: "#,##0.00",
            type: "bar",
            dataPoints: <?php echo json_encode($dataKPIRealizationLow, JSON_NUMERIC_CHECK); ?>
        }],
        "On Budget (100%)": [{
            animationEnabled: true,
            name: "Equal (100%)",
            indexLabel: "{y}\"%\"",
            yValueFormatString: "#,##0.00",
            type: "bar",
            dataPoints: <?php echo json_encode($dataKPIRealizationEqual, JSON_NUMERIC_CHECK); ?>
        }],
        "Normal (101%-110%)": [{
            animationEnabled: true,
            name: "Normal (101%-110%)",
            indexLabel: "{y}\"%\"",
            yValueFormatString: "#,##0.00",
            type: "bar",
            dataPoints: <?php echo json_encode($dataKPIRealizationNormal, JSON_NUMERIC_CHECK); ?>
        }],
        "Minor (111%-150%)": [{
            animationEnabled: true,
            name: "Minor (111%-150%)",
            indexLabel: "{y}\"%\"",
            yValueFormatString: "#,##0.00",
            type: "bar",
            dataPoints: <?php echo json_encode($dataKPIRealizationMinor, JSON_NUMERIC_CHECK); ?>
        }],
        "Major (151%-200%)": [{
            animationEnabled: true,
            name: "Major (151%-200%)",
            indexLabel: "{y}\"%\"",
            yValueFormatString: "#,##0.00",
            type: "bar",
            dataPoints: <?php echo json_encode($dataKPIRealizationMajor, JSON_NUMERIC_CHECK); ?>
        }],
        "Critical (over than 200%)": [{
            animationEnabled: true,
            name: "Critical (over 200%)",
            indexLabel: "{y}\"%\"",
            yValueFormatString: "#,##0.00",
            type: "bar",
            dataPoints: <?php echo json_encode($dataKPIRealizationCritical, JSON_NUMERIC_CHECK); ?>
        }]
    }

    var MandaysRealizationOption = {
        animationEnabled: true,
        theme: "light2",
        legend: {
            verticalAlign: "center",  // "top" , "center", "bottom"
            horizontalAlign: "right",  // "left", "center" , "right"
            fontFamily: "calibri",
        },
        data: []
    };

    var MandaysRealizationDrilldownedChartOption = {
        animationEnabled: true,
        theme: "light2",
        axisX: {
            labelFontColor: "#fff",
            lineColor: "#a2a2a2",
            tickColor: "#a2a2a2",
            margin: 10,
            labelPlacement: "inside",
            tickPlacement: "inside",
            labelMaxWidth: 500
        },
        axisY: {
            gridThickness: 0,
            includeZero: true,
            labelFontColor: "#717171",
            lineColor: "#a2a2a2",
            tickColor: "#a2a2a2",
            lineThickness: 1
        },
        data: []
    };

    var chart = new CanvasJS.Chart("chartContainer", MandaysRealizationOption);
    chart.options.data = MandaysRealization["KPI Mandays Realization"];
    chart.render();

    function MandaysRealizationChartDrilldownHandler(e) {
        chart = new CanvasJS.Chart("chartContainer", MandaysRealizationDrilldownedChartOption);
        chart.options.data = MandaysRealization[e.dataPoint.name];
        chart.options.title = { text: e.dataPoint.name };
        chart.render();
        $("#backButton").toggleClass("invisible");
    }

    $("#backButton").click(function() { 
        $(this).toggleClass("invisible");
        chart = new CanvasJS.Chart("chartContainer", MandaysRealizationOption);
        chart.options.data = MandaysRealization["KPI Mandays Realization"];
        chart.render();
    });
    // End Mandays Realization

    var chart = new CanvasJS.Chart("chartKPITimeLine2", {
        animationEnabled: true,
        subtitles: [{
            text: "BAST Timeliness.",
            fontFamily: "calibri",
            fontSize: 14
        }],
        legend: {
            verticalAlign: "center",  // "top" , "center", "bottom"
            horizontalAlign: "right"  // "left", "center" , "right"
        },
        data: [{
            type: "pie",
            startAngle: -90,
            indexLabel: "{label} ({y})",
            yValueFormatString: "#,##0",
            showInLegend: true,
            legendText: "{label}",
            indexLabelFontSize: 11,
            dataPoints: <?php echo json_encode($dataKPITimeLine2, JSON_NUMERIC_CHECK); ?>
        }]
    });
    chart.render();

    var chart = new CanvasJS.Chart("chartKPIEngineer2", {
        animationEnabled: true,
        subtitles: [{
            text: "Individual KPIs.",
            fontFamily: "calibri",
            fontSize: 14
        }],
        legend: {
            verticalAlign: "center",  // "top" , "center", "bottom"
            horizontalAlign: "right"  // "left", "center" , "right"
        },
        data: [{
            type: "pie",
            startAngle: -90,
            indexLabel: "{label} ({y})",
            yValueFormatString: "#,##0",
            showInLegend: true,
            legendText: "{label}",
            indexLabelFontSize: 11,
            dataPoints: <?php echo json_encode($dataKPIEngineer2, JSON_NUMERIC_CHECK); ?>
        }]
    });
    chart.render();


    function toggleDataSeries(e) {
        if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
            e.dataSeries.visible = false;
        } else {
            e.dataSeries.visible = true;
        }
        e.chart.render();
    }

}
</script>



<!-- Modal -->
<div class="modal fade" id="filter" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="get">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"><i class="fa-solid fa-filter"></i> Filter</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="card-body">
                    <input type="hidden" name="mod" value="dashboard">
                    <input type="hidden" name="sub" value="dashboard_kpi"> 
                    <div class="row mb-1">
                        <label class="col-sm-4 col-form-label col-form-label-sm">Periode</label>
                        <div class="col-sm-8">
                            <select class="form-select form-select-sm" id="periode" name="periode" onchange="">
                                <?php for($i=date("Y");$i>=2022;$i--) { ?>
                                <option value="<?php echo $i; ?>" <?php echo (isset($_GET['periode']) && $_GET['periode']==$i) ? "selected" : ""; ?>><?php echo $i; ?></option>
                                <!-- <option value="2021" <?php //echo (isset($_GET['periode']) && $_GET['periode']=='2021') ? "selected" : ""; ?>>2021</option> -->
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-1">
                        <label class="col-sm-4 col-form-label col-form-label-sm">Project Code</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control form-control-sm" id="project_code" name="project_code" value="">
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label class="col-sm-4 col-form-label col-form-label-sm">Project Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control form-control-sm" id="project_name" name="project_name" value="">
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label class="col-sm-4 col-form-label col-form-label-sm">Customer Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control form-control-sm" id="customer_name" name="customer_name" value="">
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label class="col-sm-4 col-form-label col-form-label-sm">Employee Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control form-control-sm" id="employee_name" name="employee_name" value="">
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label class="col-sm-4 col-form-label col-form-label-sm">Project Status</label>
                        <div class="col-sm-8">
                            <select class="form-select form-select-sm" id="project_status" name="project_status">
                                <option value="closed">Closed</option>
                                <option value="closedOpenItem" disabled>Closed Open Item</option>
                                <option value="open" disabled>Open</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" name="btn_search" value="Search">
                </div>
            </form>
        </div>
    </div>
</div>
