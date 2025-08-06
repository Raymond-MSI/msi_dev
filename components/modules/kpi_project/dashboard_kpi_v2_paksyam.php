<!--
Module : Dashboard KPI
Created : 10 November 2023
Created by : Syamsul Arham
Version : 2.0.0
-->
<?php include("components/modules/dashboard/func_dashboard.php"); ?>

<?php
if(isset($_GET['project_type']))
{
    $project_type = $_GET['project_type'];
} else
{
    // $project_type = "All Project";
    $project_type = "Implementation";
}

if(isset($_GET['periode_so']))
{
    $periode_so = $_GET['periode_so'];
} else
{
    $periode_so = DATE("Y");
}

if(isset($_GET['periode_review']))
{
    $periode_review = $_GET['periode_review'];
} else
{
    $periode_review = date("Y");
}

if(isset($_GET['status_review']))
{
    $kpi_status = $_GET['status_review'];
} else
{
    $kpi_status = 'Reviewed';
}
?>                        


<script>
$(document).ready(function () {
    var tblProjectList = $('#ProjectList').DataTable({
        dom: 'Bfrtip',
        buttons:[
            {
            extend: 'excelHtml5',
            text: "<i class='fa-solid fa-file-excel'></i>",
            title: 'KPI_Project_List_'+<?php echo date("YmdGis"); ?>,
            messageTop: 'KPI Project : Project Type [<?php echo $project_type; ?>], Periode Project [<?php echo $periode_so; ?>], Status Review [<?php echo $kpi_status; ?>]<?php echo $kpi_status == "Reviewed" ? ", Status Review [" . $periode_review . "]" : ""; ?>',
            }
        ],
        order: [
            [6, "desc"]
        ],
        "columnDefs": [
            {
            "targets": [1,2,3,4,5,6],
            "className": 'dt-body-center'
            },
            {
            "targets": [7,8,9],
            "visible": false
            }
        ],
        // paging: false,
        // scrollX: true,
        // scrollY: '60vh'
    });

    $('#ProjectList tbody').on('dblclick', 'tr', function () {
        var data1 = tblProjectList.row(this).data();
        $("#PopUpModal").modal("show");
        $("#div2").load("components/modules/kpi_project/dashboard_kpi_detail_v2.php?project_id="+data1[7]);
        document.getElementById("div1").innerHTML = data1[0];
        document.getElementById("title").innerHTML = 'KPI Resources by Project';
    });


    var tblKPIResource = $('#KPIResource').DataTable({
        dom: 'Bfrtip',
        buttons:[
            {
            extend: 'excelHtml5',
            text: "<i class='fa-solid fa-file-excel'></i>",
            title: 'KPI_Resource_'+<?php echo date("YmdGis"); ?>,
            messageTop: 'KPI Resource : Project Type [<?php echo $project_type; ?>], Periode Project [<?php echo $periode_so; ?>], Status Review [<?php echo $kpi_status; ?>]<?php echo $kpi_status == "Reviewed" ? ", Status Review [" . $periode_review . "]" : ""; ?>',
            }
        ],
        order: [
            [0, "asc"]
        ],
        "columnDefs": [
            {
            "targets": [1,2,3,4,5,6],
            "className": 'dt-body-right'
            },
            {
            "targets": [7,8],
            "visible": false
            }
        ],
        // paging: false,
        // scrollX: true,
        // scrollY: '60vh'
    });

    $('#KPIResource tbody').on('dblclick', 'tr', function () {
        var data2 = tblKPIResource.row(this).data();
        const exp1 = data2[0].split("<");
        const exp2 = exp1[1].split(">");
        const periode_so =data2[7];
        $("#PopUpModal").modal("show");
        document.getElementById("div1").innerHTML = '';
        if(data2[8]=="Reviewed")
        {
            review  = "Reviewed";
        } else
        {
            review = "Not%20Yet%20Reviewed";
        }
        $("#div2").load("components/modules/kpi_project/dashboard_kpi_resource_v2.php?resource="+exp2[0]+"&status_review="+review+"&periode_kpi="+data2[7]);
        document.getElementById("title").innerHTML = 'KPI Resource : '+data2[0];
    });

    var tblProject = $('#Resources').DataTable({
        dom: 'Bfrtip',
        "columnDefs": [
            {
            "targets": [1,2,3,4],
            "className": 'dt-body-center'
            }
        ],
        buttons:[
            {
            extend: 'excelHtml5',
            text: "<i class='fa-solid fa-file-excel'></i>",
            title: 'KPI_Summary_'+<?php echo date("YmdGis"); ?>,
            messageTop: 'KPI Summary berdasarkan data type project dan status review.',
            }
        ],
        // paging: false,
        // scrollX: true,
        // scrollY: '60vh'
    });

});
</script>

<?php
function content2($title, $value, $listData=array(), $color="danger", $width="12", $tiptool="", $fontsize="h4", $py="2")
{
    ?>
    <!-- <div class="col border-left-<?php //echo $color; ?> rounded shadow p-3 ml-3 mr-3 border-left h-100 py-2"> -->
    <div class="col border-left-<?php echo $color; ?> p-3 ml-3 mr-3 h-100 py-<?php echo $py; ?>">
        <span class="text-<?php echo $color; ?> text-uppercase text-xs font-weight-bold mb-1"><?php echo $title; ?></span><br>
        <span class="<?php echo $fontsize; ?> mb-0 mr-3 font-weight-bold text-gray-800y d-flex flex-row align-items-center justify-content-between">
            <?php 
            if(count($listData)>0) 
            { 
                ?>
                <div class="dropdown no-arrow" id="<?php echo $tiptool; ?>">
                    <a class="dropdown-toggle text-reset text-decoration-none" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo $value; ?></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-left shadow animated--fade-in"
                        aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Select item:</div>
                        <?php
                        for($i=0;$i<count($listData);$i++)
                        {
                            ?>
                            <a class="dropdown-item" href="index.php?mod=kpi_project&sub=<?php echo $_GET['sub'] . $listData[$i]['link']; ?>"><?php echo $listData[$i]['value']; ?></a>
                            <?php 
                        } 
                        ?>
                    </div>
                </div>
                <?php 
            } else
            {
                echo $value;
            } 
            ?>
        </span>
    </div>
    <?php
}

function getlink($sub, $value)
{
    $link = "";
    if(isset($_GET["project_type"]) && $sub!="project_type")
    {
        $link .= "&project_type=" . $_GET["project_type"];
    } else
    if($sub=="project_type")
    {
        $link .= "&project_type=$value";
    }

    if(isset($_GET["periode_so"]) && $sub!="periode_so")
    {
        $link .= "&periode_so=" . $_GET["periode_so"];
    } else
    if($sub=="periode_so")
    {
        $link .= "&periode_so=$value";
    }

    // if(isset($_GET["project_status"]) && $sub!="project_status")
    // {
    //     $link .= "&project_status=" . $_GET["project_status"];
    // } else
    // if($sub=="project_status")
    // {
    //     $link .= "&project_status=$value";
    // }

    $review = false;
    if(isset($_GET["status_review"]) && $sub!="status_review")
    {
        $link .= "&status_review=" . $_GET["status_review"];
        if($_GET['status_review']=="Reviewed")
        {
            $review = true;
        }
    } else
    if($sub=="status_review")
    {
        $link .= "&status_review=$value";
        if($value=="Reviewed")
        {
            $review = true;
        }
    }

    if(isset($_GET["periode_review"]) && $sub!="periode_review")
    {
        $link .= "&periode_review=" . $_GET["periode_review"];
    } else
    if($sub=="periode_review" || $review)
    {
        $link .= "&periode_review=" . date("Y");
    }

    return $link;
}

?>

<?php
$DBKPI = get_conn("KPI_PROJECT");
$dataKPICostChart = array();
$dataKPITimeChart = array();
$dataKPIErrorChart = array();
?>


<?php
$MainColor = "danger";
?>

<!-- Menu Item -->
<div class="row mb-3">
    <div class="col-lg-3">
        <?php menu_dashboard(); ?>
    </div>
    <div class="col-lg-9">
        <div class="d-flex flex-row">
            <div class="">
                <?php
                $link = array();
                $xxxy = array();
                // $xxx = array('link'=>getlink("project_type", "All Project"), 'value'=>'All Project');
                // array_push($link, $xxx);
                $xxx = array('link'=>getlink("project_type", "Implementation"), 'value'=>'Implementation');
                array_push($link, $xxx);
                // $xxx = array('link'=>getlink('project_type', 'Maintenance'), 'value'=>'Maintenance');
                // array_push($link, $xxx);
                content2("Project Type", $project_type, $link,  "none", "2", "ProjectType", "h6", "0");
                ?>
            </div>
            <div class="">
                <?php
                $mysql = "SELECT `periode_so` AS `tahun` FROM `sa_data_so` GROUP BY `periode_so` ORDER BY `periode_so` DESC";
                $tahun = $DBKPI->get_sql($mysql);
                if($tahun[2]>0)
                {
                    $link = array();
                    $xxx = array('link'=>getlink("periode_so", "All"), 'value'=>"All");
                    array_push($link, $xxx);
                    do {
                        $xxx = array('link'=>getlink("periode_so", $tahun[0]['tahun']), 'value'=>$tahun[0]['tahun']);
                        array_push($link, $xxx);
                    } while($tahun[0]=$tahun[1]->fetch_assoc());
                }
                content2("Periode Project", $periode_so, $link,  "none", "2", "PeriodeSO", "h6", "0");
                ?>
            </div>
            <div class="">
                <?php
                $link = array();
                $xxxy = array();
                $xxx = array('link'=>getlink("status_review", "Reviewed"), 'value'=>'Reviewed');
                array_push($link, $xxx);
                $xxx = array('link'=>getlink('status_review', 'Not Yet Reviewed'), 'value'=>'Not Yet Reviewed');
                array_push($link, $xxx);
                content2("Status Review", $kpi_status, $link,  "none", "2", "PeriodeReview", "h6", "0");
                ?>
            </div>
            <div class="">
                <?php
                if((isset($_GET['status_review']) && $_GET['status_review']=="Reviewed") || !isset($_GET['status_review']))
                {
                    $link = array();
                    $xxx = array();
                    for($year=date("Y"); $year>=date("Y")-1; $year--)
                    {
                        $xxx = array('link'=>getlink('periode_review', $year), 'value'=>$year);
                        array_push($link, $xxx);
                    }
                    content2("Periode Review", $periode_review, $link,  "none", "2", "PeriodeReview", "h6", "0");
                // } else
                // {
                //     $link = array();
                //     $xxx = array();
                //     // $xxx = array('link'=>'&periode_so=' . $periode_so . '&status_review=' . $kpi_status . '&periode_review=' . $periode_review . '&project_status=Open', 'value'=>"Open");
                //     $xxx = array('link'=>getlink('project_status', 'Open'), 'value'=>"Open");
                //     array_push($link, $xxx);
                //     // $xxx = array('link'=>'&periode_so=' . $periode_so . '&status_review=' . $kpi_status . '&periode_review=' . $periode_review . '&project_status=Close', 'value'=>"Close");
                //     $xxx = array('link'=>getlink('project_status', 'Close'), 'value'=>"Close");
                //     array_push($link, $xxx);
                //     content2("Status Project", $project_status, $link,  "none", "2", "StatusProject", "h6", "0");
                }
                ?>
            </div>
            <div class="">
                <div class="col p-3 ml-3 mr-3 h-100 py-0">
                    <span class="text-uppercase text-xs font-weight-bold mb-1">Default</span><br>
                    <span class="mb-0 mr-3 font-weight-bold text-gray-800y d-flex flex-row align-items-center justify-content-between">
                        <a href="index.php?mod=kpi_project&sub=<?php echo $_GET['sub']; ?>" class="text-muted text-decoration-none">Reset</a>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Badge Item -->
<div class="row mb-3">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <?php
                    $test = "xxx";
                    content2("Project Value", $periode_so, $link,  "primary", "2", "ProjectValue"); 
                    content2("My KPI", $test, $link,  "success", "2", "MyKPI"); 
                    content2("My Team KPI", $test, $link,  "info", "2", "TeamKPI"); 
                    content2("My Team Project", $periode_so, $link,  "danger", "2", "TeamProject"); 
                    content2("My Team Task", $test, $link,  "warning", "2", "TeamTask"); 
                    content2("My Team Task Update", $test, $link,  "secondary", "2", "TeamUpdate"); 
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Chart Item -->
<?php
$sambung = "";

if($kpi_status == "Reviewed")
{
    $kpi_status = "$sambung `kpi_status` = 'Reviewed'";
    $sambung = " AND ";

    $periode_review = "$sambung `periode_kpi` = $periode_review";
    // if(isset($_GET['periode_review']))
    // {
    //     $periode_review = "$sambung `periode_kpi` = " . $_GET['periode_review'];
    //     $sambung = " AND ";
    // } else
    // {
    //     $periode_review = "";
    //     $sambung = " AND ";
    // }
} else
{
    $kpi_status = "$sambung (`kpi_status` = 'Not Yet Reviewed' OR `kpi_status` IS NULL OR `kpi_status` = '')";
    $sambung = " AND ";

    $periode_review = "";
}

if($periode_so == "All")
{
    $periode_so = "";
} else
{
    $periode_so = "$sambung `periode_so` = $periode_so";
    $sambung = " AND ";
}

if($project_type == "Implementation")
{
    $project_type = "$sambung `project_type` = 'MSI Project Implementation'";
    $sambung = " AND ";
} else
if($project_type == "Maintenance")
{
    $project_type = "$sambung `project_type` = 'MSI Project Maintenance'";
    $sambung = " AND ";
} else
{
    $project_type = "";
    $sambung = " AND ";
}

?>

<div class="row mb-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header fw-bold text-<?php echo $MainColor; ?>-emphasis bg-<?php echo $MainColor; ?>-subtle">
                Cost : Resource Utilization
            </div>
            <div class="card-body">
                <?php
                $mysql = sprintf(
                    "SELECT
                        `utilization_id`,
                        `project_code`,
                        `so_number`,
                        `order_number`,
                        `periode_so`,
                        `periode_kpi`,
                        SUM(`normal`) AS `normal`,
                        SUM(`minor`) AS `minor`,
                        SUM(`major`) AS `major`,
                        SUM(`critical`) AS `critical`,
                        SUM(`none`) AS `none`,
                        `utilization_category`
                    FROM
                        `sa_kpi_dashboard_utilization`
                    WHERE
                        `utilization_category` = 'cost_utilization' AND
                        %s %s %s %s",
                    GetSQLValueString($kpi_status, "define", $kpi_status, $kpi_status),
                    GetSQLValueString($periode_so, "define", $periode_so, $periode_so),
                    GetSQLValueString($periode_review, "define", $periode_review, $periode_review),
                    GetSQLValueString($project_type, "define", $project_type, $project_type)
                );
                $rsCostUtilization = $DBKPI->get_sql($mysql);
                       
                $xxx = array("label"=> "Normal", "y"=> $rsCostUtilization[0]['normal']);
                array_push($dataKPICostChart, $xxx);
                $xxx = array("label"=> "Minor", "y"=> $rsCostUtilization[0]['minor']);
                array_push($dataKPICostChart, $xxx);
                $xxx = array("label"=> "Major", "y"=> $rsCostUtilization[0]['major']);
                array_push($dataKPICostChart, $xxx);
                $xxx = array("label"=> "Critical", "y"=> $rsCostUtilization[0]['critical']);
                array_push($dataKPICostChart, $xxx);
                $xxx = array("label"=> "None", "y"=> $rsCostUtilization[0]['none']);
                array_push($dataKPICostChart, $xxx);
                ?>
                <div id="chartKPIRU" style="height: 370px; width: 100%;"></div>
            </div>
            <div class="card-footer fw-bold text-<?php echo $MainColor; ?>-emphasis bg-<?php echo $MainColor; ?>-subtle">
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header fw-bold text-<?php echo $MainColor; ?>-emphasis bg-<?php echo $MainColor; ?>-subtle">
                Time
            </div>
            <div class="card-body">
            <?php
                $mysql = sprintf(
                    "SELECT
                        `utilization_id`,
                        `project_code`,
                        `so_number`,
                        `order_number`,
                        `periode_so`,
                        `periode_kpi`,
                        SUM(`normal`) AS `normal`,
                        SUM(`minor`) AS `minor`,
                        SUM(`major`) AS `major`,
                        SUM(`critical`) AS `critical`,
                        SUM(`none`) AS `none`,
                        `utilization_category`
                    FROM
                        `sa_kpi_dashboard_utilization`
                    WHERE
                        `utilization_category` = 'time_utilization' AND
                        %s %s %s %s",
                    GetSQLValueString($kpi_status, "define", $kpi_status, $kpi_status),
                    GetSQLValueString($periode_so, "define", $periode_so, $periode_so),
                    GetSQLValueString($periode_review, "define", $periode_review, $periode_review),
                    GetSQLValueString($project_type, "define", $project_type, $project_type)
                );
                $rsTimeUtilization = $DBKPI->get_sql($mysql);
                       
                $xxx = array("label"=> "Normal", "y"=> $rsTimeUtilization[0]['normal']);
                array_push($dataKPITimeChart, $xxx);
                $xxx = array("label"=> "Minor", "y"=> $rsTimeUtilization[0]['minor']);
                array_push($dataKPITimeChart, $xxx);
                $xxx = array("label"=> "Major", "y"=> $rsTimeUtilization[0]['major']);
                array_push($dataKPITimeChart, $xxx);
                $xxx = array("label"=> "Critical", "y"=> $rsTimeUtilization[0]['critical']);
                array_push($dataKPITimeChart, $xxx);
                $xxx = array("label"=> "None", "y"=> $rsTimeUtilization[0]['none']);
                array_push($dataKPITimeChart, $xxx);
                ?>
                <div id="chartKPITime" style="height: 370px; width: 100%;"></div>
            </div>
            <div class="card-footer fw-bold text-<?php echo $MainColor; ?>-emphasis bg-<?php echo $MainColor; ?>-subtle">
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header fw-bold text-<?php echo $MainColor; ?>-emphasis bg-<?php echo $MainColor; ?>-subtle">
                KPI Resource (Individual)
            </div>
            <div class="card-body">
                <?php
                $mysql = sprintf(
                    "SELECT
                        `utilization_id`,
                        `project_code`,
                        `so_number`,
                        `order_number`,
                        `periode_so`,
                        `periode_kpi`,
                        SUM(`normal`) AS `normal`,
                        SUM(`minor`) AS `minor`,
                        SUM(`major`) AS `major`,
                        SUM(`critical`) AS `critical`,
                        SUM(`none`) AS `none`,
                        `utilization_category`
                    FROM
                        `sa_kpi_dashboard_utilization`
                    WHERE
                        `utilization_category` = 'error_utilization' AND
                        %s %s %s %s",
                    GetSQLValueString($kpi_status, "define", $kpi_status, $kpi_status),
                    GetSQLValueString($periode_so, "define", $periode_so, $periode_so),
                    GetSQLValueString($periode_review, "define", $periode_review, $periode_review),
                    GetSQLValueString($project_type, "define", $project_type, $project_type)
                );
                $rsErrorUtilization = $DBKPI->get_sql($mysql);
                       
                $xxx = array("label"=> "Poor", "y"=> $rsErrorUtilization[0]['normal']);
                array_push($dataKPIErrorChart, $xxx);
                $xxx = array("label"=> "Good", "y"=> $rsErrorUtilization[0]['minor']);
                array_push($dataKPIErrorChart, $xxx);
                $xxx = array("label"=> "Very Good", "y"=> $rsErrorUtilization[0]['major']);
                array_push($dataKPIErrorChart, $xxx);
                $xxx = array("label"=> "Excellence", "y"=> $rsErrorUtilization[0]['critical']);
                array_push($dataKPIErrorChart, $xxx);
                $xxx = array("label"=> "None", "y"=> $rsErrorUtilization[0]['none']);
                array_push($dataKPIErrorChart, $xxx);
                ?>
                <div id="chartKPIError" style="height: 370px; width: 100%;"></div>
            </div>
            <div class="card-footer fw-bold text-<?php echo $MainColor; ?>-emphasis bg-<?php echo $MainColor; ?>-subtle">
            </div>
        </div>
    </div>
</div>

<!-- TAB Item -->
<div class="row mb-3">
    <div class="col-lg-12">
        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active text-body fw-bold" id="project-tab" data-bs-toggle="tab" data-bs-target="#tabProject" type="button" role="tab" aria-controls="SBList" aria-selected="true" title='SB yang masih dalam bentuk draft'>KPI Project</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-body fw-bold" id="resource-tab" data-bs-toggle="tab" data-bs-target="#tabResource" type="button" role="tab" aria-controls="CTEEngineer" aria-selected="false" title='SB yang sudah disubmit ke manager'>KPI Resources</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-body fw-bold" id="summary-tab" data-bs-toggle="tab" data-bs-target="#tabSummary" type="button" role="tab" aria-controls="subOrdinate" aria-selected="false" title='SB yang sudah disubmit ke manager'>KPI Summary</button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="tabProject" role="tabpanel" aria-labelledby="Project-tab">
                    <!-- KPI Projects -->
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="display compact hover" id="ProjectList" width="100%">
                                    <thead class="text-<?php echo $MainColor; ?>-emphasis bg-<?php echo $MainColor; ?>-subtle">
                                        <tr class="text-center">
                                            <th>Project Information</th>
                                            <th>Productivity Internal</th>
                                            <th>Cost (%)</th>
                                            <th>Time (%)</th>
                                            <th>Error (%)</th>
                                            <th class='text-nowrap'>Total KPI (%)</th>
                                            <th>Weighted KPI Value</th>
                                            <th>Project ID</th>
                                            <th>Periode Project</th>
                                            <th>Periode KPI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $mysql = sprintf(
                                            "SELECT `project_id`, `project_type`, `project_code`, `so_number`, `order_number`, `periode_so`, `customer_name`, `project_name`, `wrike_permalink`, `project_leader`, `project_manager`, `project_amount`, `total_task_plan`, `total_task_actual`, `SB_mandays_implementation`, `CR_mandays_implementation`, `WR_mandays_actual_implementation`, `start_assignment`, `end_assignment`, `bast_plan`, `bast_actual`, `commercial_kpi`, `commercial_category`, `time_kpi`, `time_category`, `error_kpi`, `error_category`, `total_cte`, `weighted_value`, `status_wrike`, `status_wrike`, `kpi_status`, `periode_kpi` 
                                            FROM `sa_kpi_dashboard_pl` 
                                            WHERE %s %s %s %s",
                                            GetSQLValueString($kpi_status, "define", $kpi_status, $kpi_status),
                                            GetSQLValueString($periode_so, "define", $periode_so, $periode_so),
                                            GetSQLValueString($periode_review, "define", $periode_review, $periode_review),
                                            GetSQLValueString($project_type, "define", $project_type, $project_type)
                                        );
                                        $rsProjectList = $DBKPI->get_sql($mysql);
                                        $ProjectValue = 0;
                                        if($rsProjectList[2]>0)
                                        {
                                            do {
                                                ?>
                                                <tr class="align-top">
                                                    <td class="align-top">
                                                        <?php
                                                        if($rsProjectList[0]['project_type']=="MSI Project Implementation")
                                                        {
                                                            $ProjectType = "Implementation";
                                                        } else
                                                        if($rsProjectList[0]['project_type']=="MSI Project Maintenance")
                                                        {
                                                            $ProjectType = "Maintenance";
                                                        } else
                                                        if($rsProjectList[0]['project_type']=="MSI Non-Project")
                                                        {
                                                            $ProjectType = "Non-Project";
                                                        } else
                                                        // if($rsProjectList[0]['project_type']=="MSI Project Implementation")
                                                        {
                                                            $ProjectType = "Self Improvement";
                                                        }

                                                        echo "<span class='fw-bold text-nowrap'>" . $ProjectType . " | " . $rsProjectList[0]['project_code'] . " | " . $rsProjectList[0]['so_number'] . " | " . $rsProjectList[0]['order_number'] . "</span><br/>";
                                                        echo "<span style='font-size:12px'>";
                                                        echo "<span class=''><b>Title</b> : " . $rsProjectList[0]['project_name'] . "</span> | ";
                                                        echo "<span class='text-nowrap'><b>Customer : </b>" . $rsProjectList[0]['customer_name'] . "</span> | ";
                                                        echo "<span class='text-nowrap'><b>Value : </b>" . number_format($rsProjectList[0]['project_amount'],2) . "</span> | ";
                                                        echo "<span class='text-nowrap'><b>Leader : </b>" . ($rsProjectList[0]['project_leader']!="" ? $rsProjectList[0]['project_leader'] : "-") . "</span> | ";
                                                        // echo "<b>Manager : </b>[";
                                                        // $owners = explode(",", $rsProjectList[0]['project_manager']);
                                                        // foreach($owners as $owner)
                                                        // {
                                                            ?>
                                                            <!-- <span class="text-nowrap"><?php //echo $owner; ?></span>,  -->
                                                            <?php
                                                        // }
                                                        // echo "] | ";
                                                        echo "<span class='text-nowrap'><b>Status : </b>";
                                                        if($rsProjectList[0]['status_wrike']=="Open")
                                                        {
                                                            echo "<span class='badge bg-danger'>" . $rsProjectList[0]['status_wrike'] . "</span> - ";
                                                        } else
                                                        {
                                                            echo "<span class='badge bg-primary'>" . $rsProjectList[0]['status_wrike'] . "</span> - ";
                                                        }
                                                        echo "</span>";
                                                        if($rsProjectList[0]['kpi_status']=="Reviewed")
                                                        {
                                                            echo "<span class='badge bg-primary'>" . $rsProjectList[0]['kpi_status'] . "</span> | ";
                                                        } else
                                                        {
                                                            echo "<span class='badge bg-danger'>" . $rsProjectList[0]['kpi_status'] . "</span> | ";
                                                        }
                                                        echo "<a href='" . $rsProjectList[0]['wrike_permalink'] . "' class='text-nowrap' target='_new'>Wrike Permalink</a>";
                                                        echo "</span>";
                                                        ?>
                                                    </td>
                                                    <td class="align-top">
                                                        <?php
                                                        echo ($rsProjectList[0]['total_task_plan']>0 ? $rsProjectList[0]['total_task_plan'] : "0") . " | " . ($rsProjectList[0]['total_task_actual']>0 ? $rsProjectList[0]['total_task_actual'] : "0");
                                                        ?>
                                                    </td>
                                                    <td class="align-top">
                                                        <?php
                                                        echo "<span class='text-nowrap'>" . number_format($rsProjectList[0]['commercial_kpi']*100,2) . " | " . $rsProjectList[0]['commercial_category'] . "</span><br/>";
                                                        echo "<span class='text-nowrap' style='font-size:12px'>" . number_format($rsProjectList[0]['SB_mandays_implementation'],0) . " | " . number_format($rsProjectList[0]['CR_mandays_implementation'],0) . " | " . number_format($rsProjectList[0]['WR_mandays_actual_implementation'],0) . "</span>";
                                                        ?>
                                                    </td>
                                                    <td class="align-top">
                                                        <?php
                                                        echo "<span class='text-nowrap'>" . number_format($rsProjectList[0]['time_kpi']*100,2) . " | " . $rsProjectList[0]['time_category'] . "</span><br/>";
                                                        echo "<span class='text-nowrap' style='font-size:12px'>" . ($rsProjectList[0]['bast_plan']>0 ? date("d-M-Y", strtotime($rsProjectList[0]['bast_plan'])) : "Empty") . " | " . ($rsProjectList[0]['bast_actual']>0 ? date("d-M-Y", strtotime($rsProjectList[0]['bast_actual'])) : "Empty") . "</span>"
                                                        ?>
                                                    </td>
                                                    <td class="align-top">
                                                        <?php
                                                        echo "<span class='text-nowrap'>" . number_format($rsProjectList[0]['error_kpi']*100,2) . " | " . $rsProjectList[0]['error_category'] . "</span>";
                                                        ?>
                                                    </td>
                                                    <td class="align-top">
                                                        <?php
                                                        echo number_format($rsProjectList[0]['total_cte']*100,2);
                                                        ?>
                                                    </td>
                                                    <td class="align-top">
                                                        <?php
                                                        echo number_format($rsProjectList[0]['weighted_value'],2);
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        echo $rsProjectList[0]['project_id'];
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        echo $rsProjectList[0]['periode_so'];
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        echo $rsProjectList[0]['periode_kpi'];
                                                        ?>
                                                    </td>
                                                </tr>
                                                <?php
                                                $ProjectValue += $rsProjectList[0]['project_amount'];
                                            } while($rsProjectList[0]=$rsProjectList[1]->fetch_assoc());
                                        }
                                        ?>
                                        <script>
                                            document.getElementById("TeamProject").innerHTML="<?php echo $rsProjectList[2]; ?>";
                                            document.getElementById("ProjectValue").innerHTML="<?php echo number_format($ProjectValue,2) . "M"; ?>";
                                        </script>
                                    </tbody>
                                    <tfoot class="text-<?php echo $MainColor; ?>-emphasis bg-<?php echo $MainColor; ?>-subtle">
                                        <tr class="text-center">
                                            <th>Project Information</th>
                                            <th>Productivity Internal</th>
                                            <th>Cost (%)</th>
                                            <th>Time (%)</th>
                                            <th>Error (%)</th>
                                            <th class='text-nowrap'>Total KPI (%)</th>
                                            <th>Weighted KPI Value</th>
                                            <th>Project ID</th>
                                            <th>Periode Project</th>
                                            <th>Periode KPI</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tabResource" role="tabpanel" aria-labelledby="Resource-tab">
                    <!-- KPI Resources -->
                    <div class="card">
                        <div class="card-body table-responsive">
                            <table class="display compact hover" id="KPIResource" width="100%">
                                <thead class="text-<?php echo $MainColor; ?>-emphasis bg-<?php echo $MainColor; ?>-subtle">
                                    <tr class="text-center">
                                        <th rowspan="2">Resource Name</th>
                                        <th colspan="3">Average KPI - Project Full</th>
                                        <th colspan="3">Average KPI - Project (Resource Specific)</th>
                                    </tr>
                                    <tr class="text-center">
                                        <th>Total KPI Project Ideal</th>
                                        <th>Total KPI Project Actual</th>
                                        <th>Average KPI Project</th>
                                        <th>Total KPI Project Ideal Resource Specific</th>
                                        <th>Total KPI Project Actual Resource Specific</th>
                                        <th>Average KPI Project Resource Specific</th>
                                        <th>Periode KPI</th>
                                        <th>KPI Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $mysql = sprintf("SELECT
                                        `resource_name`,
                                        SUM(`ideal_value`) AS `ideal_value`,
                                        SUM(`actual_value`) AS `actual_value`,
                                        SUM(`average_value`) / COUNT(`average_value`) AS `average_value`,
                                        SUM(`ideal_final_value`) `ideal_final_value`,
                                        SUM(`actual_final_value`) AS `actual_final_value`,
                                        SUM(`average_final_value`) / COUNT(`average_final_value`) AS `average_final_value`,
                                        `kpi_status`,
                                        `periode_kpi`
                                    FROM
                                        `sa_kpi_dashboard_resource`
                                    WHERE %s %s %s
                                    GROUP BY
                                        `resource_name`",
                                        GetSQLValueString($kpi_status, "define", $kpi_status, $kpi_status),
                                        GetSQLValueString($periode_review, "define", $periode_review, $periode_review),
                                        GetSQLValueString($project_type, "define", $project_type, $project_type)
                                    );
                                    $rsResources = $DBKPI->get_sql($mysql);
                                    if($rsResources[2]>0)
                                    {
                                        do {
                                            ?>
                                            <tr>
                                                <td><?php echo $rsResources[0]['resource_name']; ?></td>
                                                <td><?php echo number_format($rsResources[0]['ideal_value'],2); ?></td>
                                                <td><?php echo number_format($rsResources[0]['actual_value'],2); ?></td>
                                                <td><?php echo number_format($rsResources[0]['average_value'],2); ?></td>
                                                <td><?php echo number_format($rsResources[0]['ideal_final_value'],2); ?></td>
                                                <td><?php echo number_format($rsResources[0]['actual_final_value'],2); ?></td>
                                                <td><?php echo number_format($rsResources[0]['average_final_value'],2); ?></td>
                                                <td><?php echo $rsResources[0]['periode_kpi']; ?></td>
                                                <td><?php echo $rsResources[0]['kpi_status']; ?></td>
                                            </tr>
                                            <?php
                                        } while($rsResources[0]=$rsResources[1]->fetch_assoc());
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tabSummary" role="tabpanel" aria-labelledby="Summary-tab">
                    <!-- KPI Summary -->
                    <?php
                    ?>

                    <div class="card">
                        <div class="card-body table-responsive">
                            <!-- <div class="row mb-3">
                                <label class="col-sm-2">Review Period</label>
                                <div class="col-sm-1">
                                    <select class="form-select" aria-label="Default select example" id="year" onchange="loadpage()">
                                        <?php
                                        // for($year=date("Y"); $year>=2022; $year--)
                                        // {
                                            ?>
                                            <option value="<?php //echo $year; ?>" <?php //echo ((isset($_GET['year']) && $_GET['year']==$year) ? "Selected" : ""); ?>><?php //echo $year; ?></option>
                                            <?php
                                        // }
                                        ?>
                                    </select>
                                </div>
                            </div> -->

                            <table class="display compact hover" id="Resources" width="100%">
                                <thead class="text-<?php echo $MainColor; ?>-emphasis bg-<?php echo $MainColor; ?>-subtle">
                                    <tr class="text-center">
                                        <th rowspan="2">Resource Name</th>
                                        <th colspan="2">Implementation</th>
                                        <th colspan="2">Maintenance</th>
                                    </tr>
                                    <tr class="text-center">
                                        <th>Not Yet Reviewed</th>
                                        <th>Reviewed</th>
                                        <th>Not Yet Reviewed</th>
                                        <th>Reviewed</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $year = date("Y");
                                    if(isset($_GET['year']))
                                    {
                                        $year = $_GET['year'];
                                    }

                                    $type = "implementation";
                                    if(isset($_GET['type']) && $_GET['type']=="maintenance")
                                    {
                                        $type = "maintenance";
                                    }

                                    $mysql = sprintf(
                                        // "SELECT `summary_id`, `resource_name`, `" . $type ."_not_reviewed` AS `not_reviewed`, `" . $type ."_reviewed` AS `reviewed`, `" . $type ."_period` AS `period` FROM `sa_kpi_summary` WHERE `" . $type ."_period` = %s " . ($year==date("Y") ? " OR `" . $type ."_period` = 0" : ""),
                                        "SELECT `summary_id`, `resource_name`,`implementation_not_reviewed`, `maintenance_not_reviewed`, (`implementation_not_reviewed` + `maintenance_not_reviewed`) AS `not_reviewed`, `implementation_reviewed`, `maintenance_reviewed`, (`implementation_reviewed` + `maintenance_reviewed`) AS `reviewed`, `implementation_period` AS `period` FROM `sa_kpi_summary` WHERE `" . $type ."_period` = %s " . ($year==date("Y") ? " OR `" . $type ."_period` = 0" : ""),
                                        GetSQLValueString($year, "int")
                                    );

                                    $rsKPISummary = $DBKPI->get_sql($mysql);
                                    if($rsKPISummary[2]>0)
                                    {
                                        do { 
                                            ?>
                                            <tr class="text-center">
                                                <td class="text-left"><?php echo $rsKPISummary[0]['resource_name']; ?></td>
                                                <td><?php echo $rsKPISummary[0]['implementation_not_reviewed']; ?></td>
                                                <td><?php echo $rsKPISummary[0]['implementation_reviewed']; ?></td>
                                                <td><?php echo $rsKPISummary[0]['maintenance_not_reviewed']; ?></td>
                                                <td><?php echo $rsKPISummary[0]['maintenance_reviewed']; ?></td>
                                            </tr>
                                            <?php 
                                        } while($rsKPISummary[0]=$rsKPISummary[1]->fetch_assoc()); 
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php show_footer("control", "Syamsul Arham", $msg="Testing"); ?>

<!-- Modal -->
<div class="modal fade" id="PopUpModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel"><div id="title"></div></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3" id="div1"></div>
        <div id="div2"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
function loadpage()
{
    window.location.href="index.php?"+document.getElementById('onload').value;
}
</script>

<script>
window.onload = function () {

    CanvasJS.addColorSet("greenShades",
        [//colorSet Array

        "#4682B4", // SteelBlue 
        "#FF6347", // Tomato 
        "#006400", // DarkGreen 
        "#DEB887", // BurlyWood  
        "#B22222"  // FireBrick 
        ]);

    var chart = new CanvasJS.Chart("chartKPIRU", {
        animationEnabled: true,
        colorSet: "greenShades",
        data: [{
            type: "doughnut",
            startAngle: -90,
            radius: "100%",
            innerRadius: "50%",
            indexLabel: "{label} - {y}",
            yValueFormatString: "#,##0",
            showInLegend: false,
            legendText: "{label}",
            dataPoints: <?php echo json_encode($dataKPICostChart, JSON_NUMERIC_CHECK); ?>
        }],
    });
    chart.render();

    var chart = new CanvasJS.Chart("chartKPITime", {
        animationEnabled: true,
        colorSet: "greenShades",
        data: [{
            type: "doughnut",
            startAngle: -90,
            radius: "100%",
            innerRadius: "50%",
            indexLabel: "{label} - {y}",
            yValueFormatString: "#,##0",
            showInLegend: false,
            legendText: "{label}",
            dataPoints: <?php echo json_encode($dataKPITimeChart, JSON_NUMERIC_CHECK); ?>
        }],
    });
    chart.render();

    var chart = new CanvasJS.Chart("chartKPIError", {
        animationEnabled: true,
        colorSet: "greenShades",
        data: [{
            type: "doughnut",
            startAngle: -90,
            radius: "100%",
            innerRadius: "50%",
            indexLabel: "{label} - {y}",
            yValueFormatString: "#,##0",
            showInLegend: false,
            legendText: "{label}",
            dataPoints: <?php echo json_encode($dataKPIErrorChart, JSON_NUMERIC_CHECK); ?>
        }],
    });
    chart.render();

}
</script>