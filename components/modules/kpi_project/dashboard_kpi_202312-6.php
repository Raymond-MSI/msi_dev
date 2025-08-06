<!--
Module : Dashboard KPI
Created : 10 November 2023
Created by : Syamsul Arham
Version : 2.0.0
-->
<?php include("components/modules/dashboard/func_dashboard.php"); ?>

<?php
if (isset($_GET['project_type'])) {
    $project_type = $_GET['project_type'];
} else {
    // $project_type = "All Project";
    $project_type = "Implementation";
}

if (isset($_GET['periode_so'])) {
    $periode_so = $_GET['periode_so'];
} else {
    $periode_so = DATE("Y");
}

if (isset($_GET['periode_review'])) {
    $periode_review = $_GET['periode_review'];
} else {
    $periode_review = date("Y");
}

if (isset($_GET['status_review'])) {
    $kpi_status = $_GET['status_review'];
} else {
    $kpi_status = 'Reviewed';
}
?>


<script>
    $(document).ready(function() {
        var tblProjectList = $('#ProjectList').DataTable({
            dom: 'Bfrtip',
            buttons: [{
                extend: 'excelHtml5',
                text: "<i class='fa-solid fa-file-excel'></i>",
                title: 'KPI_Project_List_' + <?php echo date("YmdGis"); ?>,
                messageTop: 'KPI Project : Project Type [<?php echo $project_type; ?>], Periode Project [<?php echo $periode_so; ?>], Status Review [<?php echo $kpi_status; ?>]<?php echo $kpi_status == "Reviewed" ? ", Status Review [" . $periode_review . "]" : ""; ?>',
            }],
            order: [
                [6, "desc"]
            ],
            "columnDefs": [{
                    "targets": [1, 2, 3, 4, 5, 6],
                    "className": 'dt-body-center'
                },
                {
                    "targets": [7, 8, 9],
                    "visible": false
                }
            ]
        });

        $('#ProjectList tbody').on('dblclick', 'tr', function() {
            var data1 = tblProjectList.row(this).data();
            $("#PopUpModal").modal("show");
            $("#div2").load("components/modules/kpi_project/dashboard_kpi_detail.php?project_id=" + data1[7]);
            document.getElementById("div1").innerHTML = data1[0];
            document.getElementById("title").innerHTML = 'KPI Resources by Project';
        });


        var tblKPIResource = $('#KPIResource').DataTable({
            dom: 'Bfrtip',
            buttons: [{
                extend: 'excelHtml5',
                text: "<i class='fa-solid fa-file-excel'></i>",
                title: 'KPI_Resource_' + <?php echo date("YmdGis"); ?>,
                messageTop: 'KPI Resource : Project Type [<?php echo $project_type; ?>], Periode Project [<?php echo $periode_so; ?>], Status Review [<?php echo $kpi_status; ?>]<?php echo $kpi_status == "Reviewed" ? ", Status Review [" . $periode_review . "]" : ""; ?>',
            }],
            order: [
                [0, "asc"]
            ],
            "columnDefs": [{
                    "targets": [1, 2, 3, 4, 5, 6, 7, 8, 9],
                    "className": 'dt-body-right'
                },
                {
                    "targets": [10, 11],
                    "visible": false
                }
            ],
            // paging: false,
            // scrollX: true,
            // scrollY: '60vh'
        });

        $('#KPIResource tbody').on('dblclick', 'tr', function() {
            var data2 = tblKPIResource.row(this).data();
            const exp1 = data2[0].split("<");
            const exp2 = exp1[1].split(">");
            const periode_so = data2[7];
            $("#PopUpModal").modal("show");
            document.getElementById("div1").innerHTML = '';
            if (data2[11] == "Reviewed") {
                review = "Reviewed";
            } else {
                review = "Not%20Yet%20Reviewed";
            }
            $("#div2").load("components/modules/kpi_project/dashboard_kpi_resource.php?resource=" + exp2[0] + "&status_review=" + review + "&periode_kpi=" + data2[10]);
            document.getElementById("title").innerHTML = 'KPI Resource : ' + data2[0];
        });

        var tblResource = $('#Resources').DataTable({
            dom: 'Bfrtip',
            "columnDefs": [{
                "targets": [1, 2, 3, 4],
                "className": 'dt-body-center'
            }],
            buttons: [{
                extend: 'excelHtml5',
                text: "<i class='fa-solid fa-file-excel'></i>",
                title: 'KPI_Summary_' + <?php echo date("YmdGis"); ?>,
                messageTop: 'KPI Summary berdasarkan data type project dan status review.',
            }],
            // paging: false,
            // scrollX: true,
            // scrollY: '60vh'

        });

        $('#Resources tbody').on('dblclick', 'tr', function() {
            var data2 = tblResource.row(this).data();
            const exp1 = data2[0].split("<");
            const exp2 = exp1[1].split(">");
            const periode_so = data2[7];
            $("#PopUpModal").modal("show");
            document.getElementById("div1").innerHTML = '';
            $("#div2").load("components/modules/kpi_project/dashboard_kpi_summary_detail.php?resource=" + exp2[0]);
            document.getElementById("title").innerHTML = 'KPI Summary : ' + data2[0];
        });

    });
</script>

<?php
function get_subCondition($email)
{
    $subOrdinatsCondition = "(";
    $mdlname = 'HCM';
    $DBHCM = get_conn($mdlname);
    $subOrdinats1 = $DBHCM->get_leader_v2($email);
    $nama = $DBHCM->get_name($email);
    $subOrdinatsCondition .= "'" . $nama . " <" . $email . ">', ";

    if (count($subOrdinats1[2]) > 0) {
        $sambung = "";
        $subOrdi1 = $subOrdinats1[2];
        $i = 1;
        foreach ($subOrdi1 as $subordinat1) {
            $emailx = $DBHCM->split_email($subordinat1);
            $subOrdinatsCondition .= $sambung . "'" . $emailx[0] . " <" . $emailx[1] . ">'";
            $sambung = ", ";
            $i++;

            $subOrdinats2 = $DBHCM->get_leader_v2($emailx[1]);
            if (count($subOrdinats2[2]) > 0) {
                $subOrdi2 = $subOrdinats2[2];
                foreach ($subOrdi2 as $subordinat2) {
                    if ($subordinat2 != "None") {
                        $emailx = $DBHCM->split_email($subordinat2);
                        $subOrdinatsCondition .= $sambung . "'" . $emailx[0] . " <" . $emailx[1] . ">'";
                        $sambung = ", ";
                        $i++;

                        $subOrdinats3 = $DBHCM->get_leader_v2($emailx[1]);
                        if (count($subOrdinats3[2]) > 0) {
                            $subOrdi3 = $subOrdinats3[2];
                            foreach ($subOrdi3 as $subordinat3) {
                                if ($subordinat3 != "None") {
                                    $emailx = $DBHCM->split_email($subordinat3);
                                    $subOrdinatsCondition .= $sambung . "'" . $emailx[0] . " <" . $emailx[1] . ">'";
                                    $sambung = ", ";
                                    $i++;

                                    $subOrdinats4 = $DBHCM->get_leader_v2($emailx[1]);
                                    if (count($subOrdinats4[2]) > 0) {
                                        $subOrdi4 = $subOrdinats4[2];
                                        foreach ($subOrdi4 as $subordinat4) {
                                            if ($subordinat4 != "None") {
                                                $emailx = $DBHCM->split_email($subordinat4);
                                                $subOrdinatsCondition .= $sambung . "'" . $emailx[0] . " <" . $emailx[1] . ">'";
                                                $sambung = ", ";
                                                $i++;

                                                $subOrdinats5 = $DBHCM->get_leader_v2($emailx[1]);
                                                if (count($subOrdinats5[2]) > 0) {
                                                    $subOrdi5 = $subOrdinats5[2];
                                                    foreach ($subOrdi5 as $subordinat5) {
                                                        if ($subordinat5 != "None") {
                                                            $emailx = $DBHCM->split_email($subordinat5);
                                                            $subOrdinatsCondition .= $sambung . "'" . $emailx[0] . " <" . $emailx[1] . ">'";
                                                            $sambung = ", ";
                                                            $i++;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $subOrdinatsCondition .= ") ";
    }
    return $subOrdinatsCondition;
}
$email = $_SESSION['Microservices_UserEmail'];
if ($_SESSION['Microservices_UserEmail'] == "syamsul@mastersystem.co.id") {
    $email = "joko@mastersystem.co.id";
}
if (isset($_GET['resource'])) {
    $email = $_GET['resource'];
}

$email = $DBHCM->split_email($email);

$mysql = sprintf(
    "SELECT 
        `subordinate`
    FROM 
        `sa_mst_subordinate`
    WHERE 
        `employee_name` = %s;",
    GetSQLValueString($email[2], "text")
);
// echo "<textarea>$mysql</textarea>";
$res = $DBHCM->get_sql($mysql);
$SubOrdinates = "('" . $email[2] . "'";
if ($res[2] > 0) {
    $SubOrdinates .= $res[0]['subordinate'] . ")";
}
// echo "<textarea>$SubOrdinates</textarea>";

function content2($title, $value, $listData = array(), $color = "danger", $width = "12", $tiptool = "", $fontsize = "h4", $py = "2")
{
?>
    <!-- <div class="col border-left-<?php //echo $color; 
                                        ?> rounded shadow p-3 ml-3 mr-3 border-left h-100 py-2"> -->
    <div class="col border-left-<?php echo $color; ?> p-3 ml-3 mr-3 h-100 py-<?php echo $py; ?>">
        <span class="text-<?php echo $color; ?> text-uppercase text-xs font-weight-bold mb-1"><?php echo $title; ?></span><br>
        <span class="<?php echo $fontsize; ?> mb-0 mr-3 font-weight-bold text-gray-800y d-flex flex-row align-items-center justify-content-between">
            <?php
            if (count($listData) > 0) {
            ?>
                <div class="dropdown no-arrow" id="<?php echo $tiptool; ?>">
                    <a class="dropdown-toggle text-reset text-decoration-none" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo $value; ?></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-left shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Select item:</div>
                        <?php
                        for ($i = 0; $i < count($listData); $i++) {
                            $resource = "";
                            if (isset($_GET['resource'])) {
                                $resource = "&resource=" . $_GET['resource'];
                            }
                        ?>
                            <a class="dropdown-item" href="index.php?mod=kpi_project&sub=<?php echo $_GET['sub'] . $listData[$i]['link'] . $resource; ?>"><?php echo $listData[$i]['value']; ?></a>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            <?php
            } else {
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
    if (isset($_GET["project_type"]) && $sub != "project_type") {
        $link .= "&project_type=" . $_GET["project_type"];
    } else
    if ($sub == "project_type") {
        $link .= "&project_type=$value";
    }

    if (isset($_GET["periode_so"]) && $sub != "periode_so") {
        $link .= "&periode_so=" . $_GET["periode_so"];
    } else
    if ($sub == "periode_so") {
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
    if (isset($_GET["status_review"]) && $sub != "status_review") {
        $link .= "&status_review=" . $_GET["status_review"];
        if ($_GET['status_review'] == "Reviewed") {
            $review = true;
        }
    } else
    if ($sub == "status_review") {
        $link .= "&status_review=$value";
        if ($value == "Reviewed") {
            $review = true;
        }
    }

    if (isset($_GET["periode_review"]) && $sub != "periode_review") {
        $link .= "&periode_review=" . $_GET["periode_review"];
    } else
    if ($sub == "periode_review" || $review) {
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
$TotalKPI = 0;
$MyKPI = 0;
$TotalTaskPlan = 0;
$TotalTaskActual = 0;
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
                $xxx = array('link' => getlink("project_type", "All Project"), 'value' => 'All Project');
                array_push($link, $xxx);
                $xxx = array('link' => getlink("project_type", "Implementation"), 'value' => 'Implementation');
                array_push($link, $xxx);
                $xxx = array('link' => getlink('project_type', 'Maintenance'), 'value' => 'Maintenance');
                array_push($link, $xxx);
                content2("Project Type", $project_type, $link,  "none", "2", "ProjectType", "h6", "0");
                ?>
            </div>
            <div class="">
                <?php
                $mysql = "SELECT `periode_so` AS `tahun` FROM `sa_data_so` GROUP BY `periode_so` ORDER BY `periode_so` DESC";
                $tahun = $DBKPI->get_sql($mysql);
                if ($tahun[2] > 0) {
                    $link = array();
                    $xxx = array('link' => getlink("periode_so", "All"), 'value' => "All");
                    array_push($link, $xxx);
                    do {
                        $xxx = array('link' => getlink("periode_so", $tahun[0]['tahun']), 'value' => $tahun[0]['tahun']);
                        array_push($link, $xxx);
                    } while ($tahun[0] = $tahun[1]->fetch_assoc());
                }
                content2("Periode Project", $periode_so, $link,  "none", "2", "PeriodeSO", "h6", "0");
                ?>
            </div>
            <div class="">
                <?php
                $link = array();
                $xxxy = array();
                $xxx = array('link' => getlink("status_review", "Reviewed"), 'value' => 'Reviewed');
                array_push($link, $xxx);
                $xxx = array('link' => getlink('status_review', 'Not Yet Reviewed'), 'value' => 'Not Yet Reviewed');
                array_push($link, $xxx);
                content2("Status Review", $kpi_status, $link,  "none", "2", "PeriodeReview", "h6", "0");
                ?>
            </div>
            <div class="">
                <?php
                if ((isset($_GET['status_review']) && $_GET['status_review'] == "Reviewed") || !isset($_GET['status_review'])) {
                    $link = array();
                    $xxx = array();
                    for ($year = date("Y"); $year >= date("Y") - 1; $year--) {
                        $xxx = array('link' => getlink('periode_review', $year), 'value' => $year);
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
                    // content2("My KPI", $test, $link,  "success", "2", "MyKPI"); 
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

if ($kpi_status == "Reviewed") {
    $kpi_status = "$sambung `a`.`kpi_status` = 'Reviewed'";
    $sambung = " AND ";

    $periode_review = "$sambung `a`.`periode_kpi` = $periode_review";
    // if(isset($_GET['periode_review']))
    // {
    //     $periode_review = "$sambung `periode_kpi` = " . $_GET['periode_review'];
    //     $sambung = " AND ";
    // } else
    // {
    //     $periode_review = "";
    //     $sambung = " AND ";
    // }
} else {
    $kpi_status = "$sambung (`a`.`kpi_status` = 'Not Yet Reviewed' OR `a`.`kpi_status` IS NULL OR `a`.`kpi_status` = '')";
    $sambung = " AND ";

    $periode_review = "";
}

if ($periode_so == "All") {
    $periode_so = "";
} else {
    $periode_so = "$sambung `a`.`periode_so` = $periode_so";
    $sambung = " AND ";
}

if ($project_type == "Implementation") {
    $project_type = "$sambung `a`.`project_type` = 'MSI Project Implementation'";
    $sambung = " AND ";
} else
if ($project_type == "Maintenance") {
    $project_type = "$sambung `a`.`project_type` = 'MSI Project Maintenance'";
    $sambung = " AND ";
} else {
    $project_type = "";
    $sambung = " AND ";
}

// $Resource = "Alya Yulisti Fauziyyah Bachtiar <alya.yulisti@mastersystem.co.id>";

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
                        `a`.`commercial_category`,
                        COUNT(DISTINCT `a`.`order_number`) AS `total`
                    FROM
                        `sa_kpi_dashboard_pl` `a`
                    LEFT JOIN `sa_user` AS `b`
                    ON
                        `a`.`project_code` = `b`.`project_code`
                    WHERE %s %s %s %s AND `b`.`Nama` IN %s
                    GROUP BY `a`.`commercial_category`
                    ORDER BY `a`.`commercial_category`;",
                    GetSQLValueString($kpi_status, "define", $kpi_status, $kpi_status),
                    GetSQLValueString($periode_so, "define", $periode_so, $periode_so),
                    GetSQLValueString($periode_review, "define", $periode_review, $periode_review),
                    GetSQLValueString($project_type, "define", $project_type, $project_type),
                    GetSQLValueString($SubOrdinates, "define", $SubOrdinates, $SubOrdinates)
                );
                $rsCostUtilization = $DBKPI->get_sql($mysql);
                if ($rsCostUtilization[2] > 0) {
                    $CostTemp = array();
                    do {
                        $xxx = array($rsCostUtilization[0]['commercial_category'] => $rsCostUtilization[0]['total']);
                        $CostTemp = $CostTemp + $xxx;
                    } while ($rsCostUtilization[0] = $rsCostUtilization[1]->fetch_assoc());
                    if (strval(array_search("Normal", array_keys($CostTemp))) != "") {
                        $xxx = array("label" => "Normal", "y" => $CostTemp['Normal']);
                        array_push($dataKPICostChart, $xxx);
                    } else {
                        $xxx = array("label" => "Normal", "y" => 0);
                        array_push($dataKPICostChart, $xxx);
                    }
                    if (strval(array_search("Minor", array_keys($CostTemp))) != "") {
                        $xxx = array("label" => "Minor", "y" => $CostTemp['Minor']);
                        array_push($dataKPICostChart, $xxx);
                    } else {
                        $xxx = array("label" => "Minor", "y" => 0);
                        array_push($dataKPICostChart, $xxx);
                    }
                    if (strval(array_search("Major", array_keys($CostTemp))) != "") {
                        $xxx = array("label" => "Major", "y" => $CostTemp['Major']);
                        array_push($dataKPICostChart, $xxx);
                    } else {
                        $xxx = array("label" => "Major", "y" => 0);
                        array_push($dataKPICostChart, $xxx);
                    }
                    if (strval(array_search("Critical", array_keys($CostTemp))) != "") {
                        $xxx = array("label" => "Critical", "y" => $CostTemp['Critical']);
                        array_push($dataKPICostChart, $xxx);
                    } else {
                        $xxx = array("label" => "Critical", "y" => 0);
                        array_push($dataKPICostChart, $xxx);
                    }
                }
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
                        `a`.`time_category`,
                        COUNT(DISTINCT `a`.`order_number`) AS `total`
                    FROM
                        `sa_kpi_dashboard_pl` `a`
                    LEFT JOIN `sa_user` AS `b`
                    ON
                        `a`.`project_code` = `b`.`project_code`
                    WHERE %s %s %s %s AND `b`.`Nama` IN %s
                    GROUP BY `a`.`time_category`
                    ORDER BY `a`.`time_category`;",
                    GetSQLValueString($kpi_status, "define", $kpi_status, $kpi_status),
                    GetSQLValueString($periode_so, "define", $periode_so, $periode_so),
                    GetSQLValueString($periode_review, "define", $periode_review, $periode_review),
                    GetSQLValueString($project_type, "define", $project_type, $project_type),
                    GetSQLValueString($SubOrdinates, "define", $SubOrdinates, $SubOrdinates)
                );
                $rsCostUtilization = $DBKPI->get_sql($mysql);
                if ($rsCostUtilization[2] > 0) {
                    $CostTemp = array();
                    do {
                        $xxx = array($rsCostUtilization[0]['time_category'] => $rsCostUtilization[0]['total']);
                        $CostTemp = $CostTemp + $xxx;
                    } while ($rsCostUtilization[0] = $rsCostUtilization[1]->fetch_assoc());
                    if (strval(array_search("Normal", array_keys($CostTemp))) != "") {
                        $xxx = array("label" => "Normal", "y" => $CostTemp['Normal']);
                        array_push($dataKPITimeChart, $xxx);
                    } else {
                        $xxx = array("label" => "Normal", "y" => 0);
                        array_push($dataKPITimeChart, $xxx);
                    }
                    if (strval(array_search("Minor", array_keys($CostTemp))) != "") {
                        $xxx = array("label" => "Minor", "y" => $CostTemp['Minor']);
                        array_push($dataKPITimeChart, $xxx);
                    } else {
                        $xxx = array("label" => "Minor", "y" => 0);
                        array_push($dataKPITimeChart, $xxx);
                    }
                    if (strval(array_search("Major", array_keys($CostTemp))) != "") {
                        $xxx = array("label" => "Major", "y" => $CostTemp['Major']);
                        array_push($dataKPITimeChart, $xxx);
                    } else {
                        $xxx = array("label" => "Major", "y" => 0);
                        array_push($dataKPITimeChart, $xxx);
                    }
                    if (strval(array_search("Critical", array_keys($CostTemp))) != "") {
                        $xxx = array("label" => "Critical", "y" => $CostTemp['Critical']);
                        array_push($dataKPITimeChart, $xxx);
                    } else {
                        $xxx = array("label" => "Critical", "y" => 0);
                        array_push($dataKPITimeChart, $xxx);
                    }
                }
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
                        `a`.`error_category`,
                        COUNT(DISTINCT `a`.`order_number`) AS `total`
                    FROM
                        `sa_kpi_dashboard_pl` `a`
                    LEFT JOIN `sa_user` AS `b`
                    ON
                        `a`.`project_code` = `b`.`project_code`
                    WHERE %s %s %s %s AND `b`.`Nama` IN %s
                    GROUP BY `a`.`error_category`
                    ORDER BY `a`.`error_category`;",
                    GetSQLValueString($kpi_status, "define", $kpi_status, $kpi_status),
                    GetSQLValueString($periode_so, "define", $periode_so, $periode_so),
                    GetSQLValueString($periode_review, "define", $periode_review, $periode_review),
                    GetSQLValueString($project_type, "define", $project_type, $project_type),
                    GetSQLValueString($SubOrdinates, "define", $SubOrdinates, $SubOrdinates)
                );
                $rsCostUtilization = $DBKPI->get_sql($mysql);
                if ($rsCostUtilization[2] > 0) {
                    $CostTemp = array();
                    do {
                        $xxx = array($rsCostUtilization[0]['error_category'] => $rsCostUtilization[0]['total']);
                        $CostTemp = $CostTemp + $xxx;
                    } while ($rsCostUtilization[0] = $rsCostUtilization[1]->fetch_assoc());
                    if (strval(array_search("Normal", array_keys($CostTemp))) != "") {
                        $xxx = array("label" => "Poor", "y" => $CostTemp['Normal']);
                        array_push($dataKPIErrorChart, $xxx);
                    } else {
                        $xxx = array("label" => "Poor", "y" => 0);
                        array_push($dataKPIErrorChart, $xxx);
                    }
                    if (strval(array_search("Minor", array_keys($CostTemp))) != "") {
                        $xxx = array("label" => "Good", "y" => $CostTemp['Minor']);
                        array_push($dataKPIErrorChart, $xxx);
                    } else {
                        $xxx = array("label" => "Good", "y" => 0);
                        array_push($dataKPIErrorChart, $xxx);
                    }
                    if (strval(array_search("Major", array_keys($CostTemp))) != "") {
                        $xxx = array("label" => "Very Good", "y" => $CostTemp['Major']);
                        array_push($dataKPIErrorChart, $xxx);
                    } else {
                        $xxx = array("label" => "Very Good", "y" => 0);
                        array_push($dataKPIErrorChart, $xxx);
                    }
                    if (strval(array_search("Critical", array_keys($CostTemp))) != "") {
                        $xxx = array("label" => "Excellence", "y" => $CostTemp['Critical']);
                        array_push($dataKPIErrorChart, $xxx);
                    } else {
                        $xxx = array("label" => "Excellence", "y" => 0);
                        array_push($dataKPIErrorChart, $xxx);
                    }
                }
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
                                        // $mysql = sprintf(
                                        //     "SELECT `project_id`, `project_type`, `project_code`, `so_number`, `order_number`, `periode_so`, `customer_name`, `project_name`, `wrike_permalink`, `project_leader`, `project_manager`, `project_amount`, `total_task_plan`, `total_task_actual`, `SB_mandays_implementation`, `CR_mandays_implementation`, `WR_mandays_actual_implementation`, `start_assignment`, `end_assignment`, `bast_plan`, `bast_actual`, `commercial_kpi`, `commercial_category`, `time_kpi`, `time_category`, `error_kpi`, `error_category`, `total_cte`, `weighted_value`, `status_wrike`, `status_wrike`, `kpi_status`, `periode_kpi` 
                                        //     FROM `sa_kpi_dashboard_pl` 
                                        //     WHERE %s %s %s %s",
                                        //     GetSQLValueString($kpi_status, "define", $kpi_status, $kpi_status),
                                        //     GetSQLValueString($periode_so, "define", $periode_so, $periode_so),
                                        //     GetSQLValueString($periode_review, "define", $periode_review, $periode_review),
                                        //     GetSQLValueString($project_type, "define", $project_type, $project_type)
                                        // );
                                        $mysql = sprintf(
                                            "SELECT DISTINCT `a`.`order_number`, `a`.`project_id`, `a`.`project_type`, `a`.`project_code`, `a`.`so_number`, `a`.`order_number`, `a`.`periode_so`, `a`.`customer_name`, `a`.`project_name`, `a`.`wrike_permalink`, `a`.`project_leader`, `a`.`project_manager`, `a`.`project_amount`, `a`.`total_task_plan`, `a`.`total_task_actual`, `a`.`SB_mandays_implementation`, `a`.`CR_mandays_implementation`, `a`.`WR_mandays_actual_implementation`, `a`.`start_assignment`, `a`.`end_assignment`, `a`.`bast_plan`, `a`.`bast_actual`, `a`.`commercial_kpi`, `a`.`commercial_category`, `a`.`time_kpi`, `a`.`time_category`, `a`.`error_kpi`, `a`.`error_category`, `a`.`total_cte`, `a`.`weighted_value`, `a`.`status_wrike`, `a`.`status_wrike`, `a`.`kpi_status`, `a`.`periode_kpi` 
                                            FROM `sa_kpi_dashboard_pl` `a` 
                                            LEFT JOIN `sa_user` AS `b` ON `a`.`project_code` = `b`.`project_code`
                                            WHERE %s %s %s %s AND `b`.`Nama` IN %s",
                                            GetSQLValueString($kpi_status, "define", $kpi_status, $kpi_status),
                                            GetSQLValueString($periode_so, "define", $periode_so, $periode_so),
                                            GetSQLValueString($periode_review, "define", $periode_review, $periode_review),
                                            GetSQLValueString($project_type, "define", $project_type, $project_type),
                                            GetSQLValueString($SubOrdinates, "define", $SubOrdinates, $SubOrdinates)
                                        );
                                        $rsProjectList = $DBKPI->get_sql($mysql);
                                        $ProjectValue = 0;
                                        if ($rsProjectList[2] > 0) {
                                            do {
                                        ?>
                                                <tr class="align-top">
                                                    <td class="align-top">
                                                        <?php
                                                        if ($rsProjectList[0]['project_type'] == "MSI Project Implementation") {
                                                            $ProjectType = "Implementation";
                                                        } else
                                                        if ($rsProjectList[0]['project_type'] == "MSI Project Maintenance") {
                                                            $ProjectType = "Maintenance";
                                                        } else
                                                        if ($rsProjectList[0]['project_type'] == "MSI Non-Project") {
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
                                                        echo "<span class='text-nowrap'><b>Value : </b>" . number_format($rsProjectList[0]['project_amount'], 2) . "</span> | ";
                                                        echo "<span class='text-nowrap'><b>Leader : </b>" . ($rsProjectList[0]['project_leader'] != "" ? $rsProjectList[0]['project_leader'] : "-") . "</span> | ";
                                                        // echo "<b>Manager : </b>[";
                                                        // $owners = explode(",", $rsProjectList[0]['project_manager']);
                                                        // foreach($owners as $owner)
                                                        // {
                                                        ?>
                                                        <!-- <span class="text-nowrap"><?php //echo $owner; 
                                                                                        ?></span>,  -->
                                                        <?php
                                                        // }
                                                        // echo "] | ";
                                                        echo "<span class='text-nowrap'><b>Status : </b>";
                                                        if ($rsProjectList[0]['status_wrike'] == "Open") {
                                                            echo "<span class='badge bg-danger'>" . $rsProjectList[0]['status_wrike'] . "</span> - ";
                                                        } else {
                                                            echo "<span class='badge bg-primary'>" . $rsProjectList[0]['status_wrike'] . "</span> - ";
                                                        }
                                                        echo "</span>";
                                                        if ($rsProjectList[0]['kpi_status'] == "Reviewed") {
                                                            echo "<span class='badge bg-primary'>" . $rsProjectList[0]['kpi_status'] . "</span> | ";
                                                        } else {
                                                            echo "<span class='badge bg-danger'>" . $rsProjectList[0]['kpi_status'] . "</span> | ";
                                                        }
                                                        echo "<a href='" . $rsProjectList[0]['wrike_permalink'] . "' class='text-nowrap' target='_new'>Wrike Permalink</a>";
                                                        echo "</span>";
                                                        ?>
                                                    </td>
                                                    <td class="align-top">
                                                        <?php
                                                        $TotalTaskPlan += $rsProjectList[0]['total_task_plan'];
                                                        $TotalTaskActual += $rsProjectList[0]['total_task_actual'];
                                                        echo ($rsProjectList[0]['total_task_plan'] > 0 ? $rsProjectList[0]['total_task_plan'] : "0") . " | " . ($rsProjectList[0]['total_task_actual'] > 0 ? $rsProjectList[0]['total_task_actual'] : "0");
                                                        ?>
                                                    </td>
                                                    <td class="align-top">
                                                        <?php
                                                        echo "<span class='text-nowrap'>" . number_format($rsProjectList[0]['commercial_kpi'] * 100, 2) . " | " . $rsProjectList[0]['commercial_category'] . "</span><br/>";
                                                        echo "<span class='text-nowrap' style='font-size:12px'>" . number_format($rsProjectList[0]['SB_mandays_implementation'], 0) . " | " . number_format($rsProjectList[0]['CR_mandays_implementation'], 0) . " | " . number_format($rsProjectList[0]['WR_mandays_actual_implementation'], 0) . "</span>";
                                                        ?>
                                                    </td>
                                                    <td class="align-top">
                                                        <?php
                                                        echo "<span class='text-nowrap'>" . number_format($rsProjectList[0]['time_kpi'] * 100, 2) . " | " . $rsProjectList[0]['time_category'] . "</span><br/>";
                                                        echo "<span class='text-nowrap' style='font-size:12px'>" . ($rsProjectList[0]['bast_plan'] > 0 ? date("d-M-Y", strtotime($rsProjectList[0]['bast_plan'])) : "Empty") . " | " . ($rsProjectList[0]['bast_actual'] > 0 ? date("d-M-Y", strtotime($rsProjectList[0]['bast_actual'])) : "Empty") . "</span>"
                                                        ?>
                                                    </td>
                                                    <td class="align-top">
                                                        <?php
                                                        echo "<span class='text-nowrap'>" . number_format($rsProjectList[0]['error_kpi'] * 100, 2) . " | " . $rsProjectList[0]['error_category'] . "</span>";
                                                        ?>
                                                    </td>
                                                    <td class="align-top">
                                                        <?php
                                                        echo number_format($rsProjectList[0]['total_cte'] * 100, 2);
                                                        ?>
                                                    </td>
                                                    <td class="align-top">
                                                        <?php
                                                        echo number_format($rsProjectList[0]['weighted_value'], 2);
                                                        $TotalKPI += $rsProjectList[0]['weighted_value'];
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
                                            } while ($rsProjectList[0] = $rsProjectList[1]->fetch_assoc());
                                        }
                                        ?>
                                        <script>
                                            document.getElementById("TeamProject").innerHTML = "<?php echo $rsProjectList[2]; ?>";
                                            document.getElementById("ProjectValue").innerHTML = "<?php echo number_format($ProjectValue, 2) . "M"; ?>";
                                            document.getElementById("TeamTask").innerHTML = "<?php echo number_format($TotalTaskPlan, 0); ?>";
                                            document.getElementById("TeamUpdate").innerHTML = "<?php echo number_format($TotalTaskActual, 0); ?>";
                                            document.getElementById("TeamKPI").innerHTML = "<?php echo number_format($TotalKPI, 2); ?>";
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
                                        <th rowspan="2" class="align-middle text-warning-emphasis bg-warning-subtle">Resource Name</th>
                                        <th colspan="3" class="align-middle text-success-emphasis bg-success-subtle">Average KPI - Project Full</th>
                                        <th colspan="3" class="align-middle text-info-emphasis bg-info-subtle">Average KPI - Project (Resource Specific)</th>
                                        <th colspan="3" class="align-middle text-primary-emphasis bg-primary-subtle">Average KPI - Productivity</th>
                                    </tr>
                                    <tr class="text-center">
                                        <th class="align-middle text-success-emphasis bg-success-subtle">Total KPI Project Ideal</th>
                                        <th class="align-middle text-success-emphasis bg-success-subtle">Total KPI Project Actual</th>
                                        <th class="align-middle text-success-emphasis bg-success-subtle">Average KPI Project</th>
                                        <th class="align-middle text-info-emphasis bg-info-subtle">Total KPI Project Ideal Resource Specific</th>
                                        <th class="align-middle text-info-emphasis bg-info-subtle">Total KPI Project Actual Resource Specific</th>
                                        <th class="align-middle text-info-emphasis bg-info-subtle">Average KPI Project Resource Specific</th>
                                        <th class="align-middle text-primary-emphasis bg-primary-subtle">Total Task Plan</th>
                                        <th class="align-middle text-primary-emphasis bg-primary-subtle">Total Task Actual</th>
                                        <th class="align-middle text-primary-emphasis bg-primary-subtle">Average Productivity</th>
                                        <th>Periode KPI</th>
                                        <th>KPI Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // $mysql = sprintf("SELECT
                                    //     `resource_name`,
                                    //     SUM(`ideal_value`) AS `ideal_value`,
                                    //     SUM(`actual_value`) AS `actual_value`,
                                    //     SUM(`average_value`) / COUNT(`average_value`) AS `average_value`,
                                    //     SUM(`ideal_final_value`) `ideal_final_value`,
                                    //     SUM(`actual_final_value`) AS `actual_final_value`,
                                    //     SUM(`average_final_value`) / COUNT(`average_final_value`) AS `average_final_value`,
                                    //     `kpi_status`,
                                    //     `periode_kpi`
                                    // FROM
                                    //     `sa_kpi_dashboard_resource`
                                    // WHERE %s %s %s
                                    // GROUP BY
                                    //     `resource_name`",
                                    //     GetSQLValueString($kpi_status, "define", $kpi_status, $kpi_status),
                                    //     GetSQLValueString($periode_review, "define", $periode_review, $periode_review),
                                    //     GetSQLValueString($project_type, "define", $project_type, $project_type)
                                    // );
                                    $mysql = sprintf(
                                        "SELECT
                                        `resource_name`,
                                        SUM(`ideal_value`) AS `ideal_value`,
                                        SUM(`actual_value`) AS `actual_value`,
                                        SUM(`average_value`) / COUNT(`average_value`) AS `average_value`,
                                        SUM(`ideal_final_value`) `ideal_final_value`,
                                        SUM(`actual_final_value`) AS `actual_final_value`,
                                        SUM(`average_final_value`) / COUNT(`average_final_value`) AS `average_final_value`,
                                        SUM(`total_task_plan`) AS `task_plan`,
                                        SUM(`total_task_actual`) AS `task_actual`,
                                        SUM(`average_productivity`) AS `average_productivity`,
                                        `kpi_status`,
                                        `periode_kpi`
                                    FROM
                                        `sa_kpi_dashboard_resource` `a`
                                    WHERE %s %s %s AND `resource_name` IN %s
                                    GROUP BY
                                        `resource_name`",
                                        GetSQLValueString($kpi_status, "define", $kpi_status, $kpi_status),
                                        GetSQLValueString($periode_review, "define", $periode_review, $periode_review),
                                        GetSQLValueString($project_type, "define", $project_type, $project_type),
                                        GetSQLValueString($SubOrdinates, "define", $SubOrdinates, $SubOrdinates)
                                    );
                                    $rsResources = $DBKPI->get_sql($mysql);
                                    if ($rsResources[2] > 0) {
                                        do {
                                    ?>
                                            <tr>
                                                <td><?php echo $rsResources[0]['resource_name']; ?></td>
                                                <td><?php echo number_format($rsResources[0]['ideal_value'], 2); ?></td>
                                                <td><?php echo number_format($rsResources[0]['actual_value'], 2); ?></td>
                                                <td><?php echo number_format($rsResources[0]['average_value'], 2); ?></td>
                                                <td><?php echo number_format($rsResources[0]['ideal_final_value'], 2); ?></td>
                                                <td><?php echo number_format($rsResources[0]['actual_final_value'], 2); ?></td>
                                                <td><?php echo number_format($rsResources[0]['average_final_value'], 2); ?></td>
                                                <td><?php echo number_format($rsResources[0]['task_plan'], 2); ?></td>
                                                <td><?php echo number_format($rsResources[0]['task_actual'], 2); ?></td>
                                                <td><?php echo number_format($rsResources[0]['average_productivity'], 2); ?></td>
                                                <td><?php echo $rsResources[0]['periode_kpi']; ?></td>
                                                <td><?php echo $rsResources[0]['kpi_status']; ?></td>
                                            </tr>
                                    <?php
                                        } while ($rsResources[0] = $rsResources[1]->fetch_assoc());
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
                                            <option value="<?php //echo $year; 
                                                            ?>" <?php //echo ((isset($_GET['year']) && $_GET['year']==$year) ? "Selected" : ""); 
                                                                ?>><?php //echo $year; 
                                                                    ?></option>
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
                                    if (isset($_GET['year'])) {
                                        $year = $_GET['year'];
                                    }

                                    $type = "implementation";
                                    if (isset($_GET['type']) && $_GET['type'] == "maintenance") {
                                        $type = "maintenance";
                                    }

                                    // $mysql = sprintf(
                                    //     // "SELECT `summary_id`, `resource_name`, `" . $type ."_not_reviewed` AS `not_reviewed`, `" . $type ."_reviewed` AS `reviewed`, `" . $type ."_period` AS `period` FROM `sa_kpi_summary` WHERE `" . $type ."_period` = %s " . ($year==date("Y") ? " OR `" . $type ."_period` = 0" : ""),
                                    //     "SELECT 
                                    //         DISTINCT 
                                    //         `summary_id`, 
                                    //         `resource_name`,
                                    //         `implementation_not_reviewed`, 
                                    //         `maintenance_not_reviewed`, 
                                    //         (`implementation_not_reviewed` + `maintenance_not_reviewed`) AS `not_reviewed`, 
                                    //         `implementation_reviewed`, 
                                    //         `maintenance_reviewed`, 
                                    //         (`implementation_reviewed` + `maintenance_reviewed`) AS `reviewed`, 
                                    //         `implementation_period` AS `period` 
                                    //     FROM `sa_kpi_summary` 
                                    //     WHERE 
                                    //         `" . $type ."_period` = %s " . ($year==date("Y") ? " OR `" . $type ."_period` = 0" : ""),
                                    //     GetSQLValueString($year, "int")
                                    // );

                                    $mysql = sprintf(
                                        "SELECT
                                            `a`.`Nama` AS `resource_name`,
                                            COUNT(
                                                DISTINCT(
                                                    IF(
                                                        `a`.`project_type` = 'MSI Project Implementation' AND `a`.`kpi_status` = 'Reviewed',
                                                        `a`.`order_number`,
                                                        NULL
                                                    )
                                                )
                                            ) AS `implementation_reviewed`,
                                            COUNT(
                                                DISTINCT(
                                                    IF(
                                                        `a`.`project_type` = 'MSI Project Implementation' AND `a`.`kpi_status` = 'Not Yet Reviewed',
                                                        `a`.`order_number`,
                                                        NULL
                                                    )
                                                )
                                            ) AS `implementation_not_yet_reviewed`,
                                            COUNT(
                                                DISTINCT(
                                                    IF(
                                                        `a`.`project_type` = 'MSI Project Maintenance' AND `a`.`kpi_status` = 'Reviewed',
                                                        `a`.`order_number`,
                                                        NULL
                                                    )
                                                )
                                            ) AS `maintenance_reviewed`,
                                            COUNT(
                                                DISTINCT(
                                                    IF(
                                                        `a`.`project_type` = 'MSI Project Maintenance' AND `a`.`kpi_status` = 'Not Yet Reviewed',
                                                        `a`.`order_number`,
                                                        NULL
                                                    )
                                                )
                                            ) AS `maintenance_not_yet_reviewed`
                                        FROM
                                            `sa_user` `a`
                                        LEFT JOIN `sa_log_board` `b` ON
                                            `a`.`so_number` = `b`.`so_number`
                                        WHERE
                                            (
                                                LEFT(`b`.`date`, 4) LIKE %s OR `a`.`kpi_status` = 'Not Yet Reviewed'
                                            )
                                            AND `a`.`Nama` IN %s
                                        GROUP BY
                                            `a`.`Nama`
                                        ORDER BY
                                            `a`.`Nama`;",
                                        GetSQLValueString($year, "int"),
                                        GetSQLValueString($SubOrdinates, "define", $SubOrdinates, $SubOrdinates)
                                    );

                                    $rsKPISummary = $DBKPI->get_sql($mysql);
                                    if ($rsKPISummary[2] > 0) {
                                        do {
                                    ?>
                                            <tr class="text-center">
                                                <td class="text-left"><?php echo $rsKPISummary[0]['resource_name']; ?></td>
                                                <td>
                                                    <!-- <button type="button" class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#PopUpModal2"> -->
                                                    <?php echo $rsKPISummary[0]['implementation_not_yet_reviewed']; ?>
                                                    <!-- </button> -->
                                                </td>
                                                <td><?php echo $rsKPISummary[0]['implementation_reviewed']; ?></td>
                                                <td><?php echo $rsKPISummary[0]['maintenance_not_yet_reviewed']; ?></td>
                                                <td><?php echo $rsKPISummary[0]['maintenance_reviewed']; ?></td>
                                            </tr>
                                    <?php
                                        } while ($rsKPISummary[0] = $rsKPISummary[1]->fetch_assoc());
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

<?php show_footer("control", "Syamsul Arham", $msg = "Testing"); ?>

<!-- Modal -->
<div class="modal fade" id="PopUpModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">
                    <div id="title"></div>
                </h1>
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

<!-- Modal -->
<div class="modal fade" id="PopUpModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Project Summary</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3" id="div3"></div>
                <!-- <div id="div4"></div> -->
                <script>
                    $("#div3").load("components/modules/kpi_project/dashboard_kpi_summary_detail.php?resource=aditias.fauzi@mastersystem.co.id&status=Reviewed");
                </script>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function loadpage() {
        window.location.href = "index.php?" + document.getElementById('onload').value;
    }
</script>

<script>
    window.onload = function() {

        CanvasJS.addColorSet("greenShades",
            [ //colorSet Array

                "#4682B4", // SteelBlue 
                "#FF6347", // Tomato 
                "#006400", // DarkGreen 
                "#DEB887", // BurlyWood  
                "#B22222" // FireBrick 
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