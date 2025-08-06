<?php
function content2($title, $value, $listData=array(), $color="danger", $width="12", $tiptool="")
{
    ?>
    <div class="col border-left-<?php echo $color; ?> rounded shadow p-3 ml-3 mr-3 border-left h-100 py-2">
        <span class="text-<?php echo $color; ?> text-uppercase text-xs font-weight-bold mb-1"><?php echo $title; ?></span><br>
        <span class="h4 mb-0 mr-3 font-weight-bold text-gray-800y d-flex flex-row align-items-center justify-content-between">
            <?php 
            if(count($listData)>0) 
            { 
                ?>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle text-reset text-decoration-none" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo $value; ?></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Select item:</div>
                        <?php
                        for($i=0;$i<count($listData);$i++)
                        {
                            ?>
                            <a class="dropdown-item" href="index.php?mod=dashboard&sub=dashboard_kpi<?php echo $listData[$i]['link']; ?>"><?php echo $listData[$i]['value']; ?></a>
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

function get_subCondition($email)
{
    $subOrdinatsCondition = "";
    $mdlname = 'HCM';
    $DBHCM = get_conn($mdlname);
    $subOrdinats1 = $DBHCM->get_leader_v2($email);
    $sub_ordinat = "";
    
    if(count($subOrdinats1[2])>0)
    {
        $sub_ordinat = '<div class="card"><div class="card-body shadow table-responsive"><table class="display" id="example"><thead><tr><th>No.</th><th>Resource Name</th></tr></thead><tbody>';
        $sambung = "";
        $subOrdinatsCondition = " OR (";
        $subOrdi1 = $subOrdinats1[2];
        $i = 1;
        foreach($subOrdi1 as $subordinat1)
        {
            $emailx = $DBHCM->split_email($subordinat1);
            $subOrdinatsCondition .= $sambung . "`sa_wrike_integrate`.`sa_wrike_project_list`.`owner_email` = '" . $emailx[1] . "'";
            $sambung = " OR ";
            $sub_ordinat .= '<tr><td>' . $i . '</td><td>' . $subordinat1 . '</td></tr>'; 
            $i++;

            $subOrdinats2 = $DBHCM->get_leader_v2($emailx[1]);
            if(count($subOrdinats2[2])>0)
            {
                $subOrdi2 = $subOrdinats2[2];
                foreach($subOrdi2 as $subordinat2)
                {
                    $emailx = $DBHCM->split_email($subordinat2);
                    $subOrdinatsCondition .= $sambung . "`sa_wrike_integrate`.`sa_wrike_project_list`.`owner_email` = '" . $emailx[1] . "'";
                    $sambung = " OR ";
                    $sub_ordinat .= '<tr><td>' . $i . '</td><td>-&nbsp;' . $subordinat2 . '</td></tr>'; 
                    $i++;

                    $subOrdinats3 = $DBHCM->get_leader_v2($emailx[1]);
                    if(count($subOrdinats3[2])>0)
                    {
                        $subOrdi3 = $subOrdinats3[2];
                        foreach($subOrdi3 as $subordinat3)
                        {
                            $emailx = $DBHCM->split_email($subordinat3);
                            $subOrdinatsCondition .= $sambung . "`sa_wrike_integrate`.`sa_wrike_project_list`.`owner_email` = '" . $emailx[1] . "'";
                            $sambung = " OR ";
                            $sub_ordinat .= '<tr><td>' . $i . '</td><td>&nbsp;&nbsp;-&nbsp;' . $subordinat2 . '</td></tr>'; 
                            $i++;
                        }
                    }
                }
            }
        }
        $subOrdinatsCondition .= ") ";
        $sub_ordinat .= '</tbody></table></div></div>';
    }
    return array($subOrdinatsCondition, $sub_ordinat);
}

?>

<script>
$(document).ready(function () {
    var tblProject = $('#CTEProject').DataTable({
        dom: 'Bflrtip',
        scrollX: true,
        buttons:[
            {
            extend: 'excelHtml5',
            text: "<i class='fa-solid fa-file-excel'></i>",
            title: 'CTE_Project_'+<?php echo date("YmdGis"); ?>
            }
        ],
        order: [
            [14, "desc"]
        ],
        "columnDefs": [
            {
            "targets": [3,4,6,7,8,9,10,11,12,13,14],
            "className": 'dt-body-center'
            },
            {
            "targets": [1,2,3,4,5,8,16,17],
            "visible": false
            }
        ]
    });

    $('#CTEProject tbody').on('click', 'tr', function () {
        var data1 = tblProject.row(this).data();
        $("#exampleModal").modal("show");
        // $("#div1").load("components/modules/dashboard/dashboard_kpi_detail.php?owner="+data1[5]+"&kpi_status="+data1[15]+"&project_type=MSI%20Project%20Implementation&periode="+data1[16]+"&subOrdinatsCondition=%%&so_number="+data1[2]+"&id="+data1[16]);
        $("#div1").load("components/modules/dashboard/dashboard_kpi_detail.php?so_number="+data1[2]);
        document.getElementById("div2").innerHTML = '';
        document.getElementById("title").innerHTML = 'KPI Resources by Project';
    });

    var tblResource = $('#CTEResource').DataTable({
        dom: 'Bflrtip',
        // scrollX: true,
        buttons:[
            {
            extend: 'excelHtml5',
            text: "<i class='fa-solid fa-file-excel'></i>",
            title: 'KPI_Resources_'+<?php echo date("YmdGis"); ?>
            }
        ],
        order: [
            [0, "asc"]
        ],
        "columnDefs": [
            {
            "targets": [2,3,4,5,6,7],
            "className": 'dt-body-center'
            },
            {
            "targets": [0],
            "visible": false
            }
        ]
    });

    $('#CTEResource tbody').on('click', 'tr', function () {
        var data2 = tblResource.row(this).data();
        const exp1 = data2[0].split("<");
        const exp2 = exp1[1].split(">");
        $("#exampleModal").modal("show");
        document.getElementById("div1").innerHTML = '';
        $("#div2").load("components/modules/dashboard/dashboard_kpi_resource.php?resource="+exp2[0]);
        document.getElementById("title").innerHTML = 'Project List by Resources';
    });

    $('#example').DataTable({
        dom: 'flrtip',
        ordering: false
    });
});
</script>

<?php
$project_type = 'MSI Project Implementation';
$mdlname = 'WRIKE_INTEGRATE';
$DBWRIKE = get_conn($mdlname);
$mdlname = 'KPI_PROJECT';
$DBKPI = get_conn($mdlname);
$mdlname = 'HCM';
$DBHCM = get_conn($mdlname);

$MyKPI = 0;
$MyKPIAll = 0;
$MyKPICountALL = 0;
$MyTotalTask = 0;
$MyTotalUpdate = 0;
$MyProjectValue = 0;
$KPIProject = "";
$KPIResources = "";
$DataResource = array();
$DataIndividual = array();
$KPIIndividualPoor = 0;
$KPIIndividualGood = 0;
$KPIIndividualVeryGood = 0;
$KPIIndividualExcellence = 0;
$KPICostNormal = 0;
$KPICostMinor = 0;
$KPICostMajor = 0;
$KPICostCritical = 0;
$KPICostEmpty = 0;
$KPITimeNormal = 0;
$KPITimeMinor = 0;
$KPITimeMajor = 0;
$KPITimeCritical = 0;
$KPITimeEmpty = 0;
$subOrdinatsCondition = "";

$dataKPICostChart = array();
$dataKPITimeChart = array();
$dataKPIResourceChart = array();

$owner = $_SESSION['Microservices_UserEmail'];
if($_SESSION['Microservices_UserLevel']!="Administrator" AND $_SESSION['Microservices_UserLevel']!="Super Admin")
{
    $xxx = get_subCondition($owner);
    if($xxx[1]!='')
    {
        $subOrdinatsConditionx = get_subCondition($owner);
        $subOrdinatsCondition = $subOrdinatsConditionx[0];
    } else
    {
        $subOrdinatsConditionx = array(NULL, '<div class="card"><div class="card-body shadow table-responsive"><table class="display" id="example"><thead><tr><th>No.</th><th>Resource Name</th></tr></thead><tbody><tr><td class="text-center" colspan="2">No data to display.</td></tr></tbody></table></div></div>');
    }
} else
{
    $owner = '%%';
    $subOrdinatsConditionx = array(NULL, '<div class="card"><div class="card-body shadow table-responsive"><table class="display" id="example"><thead><tr><th>No.</th><th>Resource Name</th></tr></thead><tbody><tr><td class="text-center" colspan="2">No data to display.</td></tr></tbody></table></div></div>');
}
$userLogin = $_SESSION['Microservices_UserEmail'];
$userLevel = $DBHCM->get_leader_v2($userLogin);
if($owner== 'sumarno@mastersystem.co.id' OR $owner== 'lucky.andiani@mastersystem.co.id' OR $userLevel[3]<3)
{
    $owner = '%%';
}

if(isset($_GET['periode']))
{
    $periodex = $_GET['periode'];
    $periode = ' = ' . $_GET['periode'];
} else
{
    $periodex = '2022';
    $periode = ' = 2022';
}

if(isset($_GET['status']))
{
    $kpi_statusx = $_GET['status'];
    $kpi_status = '= "' . $_GET['status'] . '"';
} else
{
    $kpi_statusx = 'Reviewed';
    $kpi_status = '= "Reviewed"';
}
if($kpi_statusx=='Not Yet Reviewed')
{
    $periodex= 'All';
    $periode = ' = 0';
}

$mysql = sprintf("SELECT `sa_wrike_integrate`.`sa_wrike_project_list`.`id`, `sa_wrike_integrate`.`sa_wrike_project_detail`.`project_code`, `sa_wrike_integrate`.`sa_wrike_project_list`.`no_so` AS `so_number`, `sa_wrike_integrate`.`sa_wrike_project_detail`.`customer_name`, `sa_wrike_integrate`.`sa_wrike_project_list`.`title` AS `project_name`, `sa_wrike_integrate`.`sa_wrike_project_list`.`permalink`, `sa_wrike_integrate`.`sa_wrike_project_list`.`owner_email`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`kpi_status`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`value`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`commercial_kpi`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`commercial_category`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`time_kpi`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`time_category`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`error_kpi`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`error_category`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`total_cte`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`weighted_value`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`start_assignment`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`bast_plan`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`bast_actual`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`status_wr`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`status_project`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`periode`, `sa_dashboard_kpi`.`sa_data_so`.`WR_bast_date_actual_project_implementation` FROM `sa_wrike_integrate`.`sa_wrike_project_list` LEFT JOIN `sa_dashboard_kpi`.`sa_kpi_so_wr` ON `sa_dashboard_kpi`.`sa_kpi_so_wr`.`so_number` = `sa_wrike_integrate`.`sa_wrike_project_list`.`no_so` LEFT JOIN `sa_wrike_integrate`.`sa_wrike_project_detail` ON `sa_wrike_integrate`.`sa_wrike_project_list`.`id` = `sa_wrike_integrate`.`sa_wrike_project_detail`.`project_id` LEFT JOIN `sa_dashboard_kpi`.`sa_data_so` ON `sa_dashboard_kpi`.`sa_data_so`.`so_number_kpi` = `sa_wrike_integrate`.`sa_wrike_project_list`.`no_so` WHERE `sa_wrike_integrate`.`sa_wrike_project_list`.`project_type` LIKE %s AND `sa_dashboard_kpi`.`sa_kpi_so_wr`.`kpi_status` %s AND `sa_dashboard_kpi`.`sa_data_so`.`periode` %s AND (`sa_wrike_integrate`.`sa_wrike_project_list`.`owner_email` LIKE %s OR `sa_wrike_integrate`.`sa_wrike_project_list`.`id` IN (SELECT `sa_wrike_integrate`.`sa_wrike_assignment`.`project_id` FROM `sa_wrike_integrate`.`sa_wrike_assignment` WHERE `sa_wrike_integrate`.`sa_wrike_assignment`.`resource_email` LIKE %s GROUP BY `sa_wrike_integrate`.`sa_wrike_assignment`.`project_id`, `sa_wrike_integrate`.`sa_wrike_assignment`.`resource_email`) %s); ",
    GetSQLValueString($project_type, "text"),
    GetSQLValueString($kpi_status, "defined", $kpi_status, ""),
    GetSQLValueString($periode, "defined", $periode, ""),
    GetSQLValueString($owner, "text"),
    GetSQLValueString($owner, "text"),
    GetSQLValueString($subOrdinatsCondition, "defined", $subOrdinatsCondition, "")
); 
$dtWrileProjectList = $DBWRIKE->get_sql($mysql);

$KPIProject .= '<div class="card"><div class="card-body shadow table-responsive">
<table class="display" id="CTEProject">
    <thead>';

$KPIProjectHeader = '
            <tr class="text-center">
            <th>Project Information</th>
            <th>Project Code</th>
            <th>SO Number</th>
            <th>Customer Name</th>
            <th>Project Name</th>
            <th>Owners Name</th>
            <th>Owners</th>
            <th title="The resources involved in the project.">Resources</th>
            <th>Members Name</th>
            <th title="Legend:
Total Task Assignment | Total Task Update | Percentage Update
            ">Productivity Internal</th>
            <th title="Legend:
% Resource Utilization | Remarks
Mandays SB | Mandays CR | Mandays Actual

Resource Utlization = Mandays Actual / (Mandays Service Budget + Mandays Change Request)
            ">Cost&nbsp;(%)</th>
            <th title="Legend:
% Time | Remarks
BAST Plan | BAST Actual
            ">Time&nbsp;(%)</th>
            <th title="Upcoming features.">Error&nbsp;(%)</th>
            <th title="Total&nbsp;KPI (%) = (100% - (Commercial (%) + Time (%) + Error (%))">Total&nbsp;KPI&nbsp;(%)</th>
            <th title="Weighted Value KPI Project = Total KPI (%) x Project Value">Weighted Value KPI</th>
            <th title="Project status is the status that is in wrike.
            KPI Status is a project that has been considered complete and has been assessed.
            Note contains information if any.">Status</th>
            <th>KPI Status</th>
            <th>Periode</th>
        </tr>';
$KPIProject .= $KPIProjectHeader;
$KPIProject .= '
    </thead>
    <tbody>';

$KPIResources .= '<div class="card"><div class="card-body shadow table-responsive">
<table class="display" id="CTEResource" width="100%">
    <thead>
        <tr class="text-center">
            <th rowspan="2">Resource</th>
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
        </tr>
    </thead>
    <tbody>';

if($dtWrileProjectList[2]>0)
{
    do {
        $KPIProject .= '<tr>';
        // Project Information
        $MyProjectValue += $dtWrileProjectList[0]['value'];
        $KPIProject .= '
        <td> 
            <div class="fw-bold">' .
                    $dtWrileProjectList[0]['project_code'] . '&nbsp;&nbsp;|&nbsp;&nbsp;
                    <span class="qwerty">' . 
                        $dtWrileProjectList[0]['so_number'] . '
                    </span>
                </div>
            </div>
            <div style="font-size:13px">
            <span class="text-nowrap">' . 
                $dtWrileProjectList[0]['customer_name'] . '
            </span>&nbsp;&nbsp;|&nbsp; 
            <span class="text-nowrap">' . 
                $dtWrileProjectList[0]['project_name'] . '
            </span>&nbsp;&nbsp;|&nbsp; 
            <span>
                IDR' . number_format($dtWrileProjectList[0]['value'], 2)  . 'M&nbsp;&nbsp;|&nbsp; ' . '
                <a href="' . $dtWrileProjectList[0]['permalink'] . '" class="link-secondary text-nowrap" target="blank_">' . $dtWrileProjectList[0]['permalink'] . '</a>
            </span>
            </div>
        </td>
        <td>' . $dtWrileProjectList[0]['project_code'] . '</td>
        <td>' . $dtWrileProjectList[0]['so_number'] . '</td>
        <td>' . $dtWrileProjectList[0]['customer_name'] . '</td>
        <td>' . $dtWrileProjectList[0]['project_name'] . '</td>
        ';

        // Owner Project
        $resources = explode(",", $dtWrileProjectList[0]['owner_email']);
        $img = "";
        foreach($resources as $resource)
        {
            $tblname = 'mst_employees';
            $condition = '`email`="' . trim($resource) . '"';
            $employees = $DBHCM->get_data($tblname, $condition);
            if($employees[2]>0)
            {
                $img .= "<img class='img-profile' src='data:image/jpeg;base64, " .  base64_encode($employees[0]['unitdrawing']) . "' style='padding:2px; border:1px solid #e6e6e6; width:30px; height:40px;' title='" . $employees[0]['employee_name'] . "' />";
            }
        }
        $KPIProject .= '<td>' . $dtWrileProjectList[0]['owner_email'] . '</td>';
        $KPIProject .= '<td class="align-top">' . $img . '</td>';

        // Resource Project
        $mysql = "SELECT resource_email FROM sa_wrike_assignment WHERE project_id = '" . $dtWrileProjectList[0]['id'] . "' GROUP BY project_id, resource_email";
        $dtWrikeAssignment = $DBWRIKE->get_sql($mysql);
        $resourcex = '';

        if($dtWrikeAssignment[2]>0)
        {
            $KPIProject .= '<td class="align-top">';
            do {
                $tblname = 'mst_employees';
                $condition = '`email`="' . $dtWrikeAssignment[0]['resource_email'] . '"';
                $employees = $DBHCM->get_data($tblname, $condition);
                if($employees[2]>0)
                {
                    // KPI Project
                    $KPIProject .= "<img class='img-profile' src='data:image/jpeg;base64, " .  base64_encode($employees[0]['unitdrawing']) . "' style='padding:2px; border:1px solid #e6e6e6; width:30px; height:40px;' title='" . $employees[0]['employee_name'] . "' />";
                    $resourcex .= $employees[0]['employee_name'] . '; ';

                    // KPI Resource
                    if(array_search($dtWrikeAssignment[0]['resource_email'], $DataResource)=='')
                    {
                        $mysql = sprintf("SELECT `Nama`, `total_nilai_ideal`, `total_nilai_aktual`, `hasil_aktual_ideal`, `total_nilai_akhir_ideal`, `total_nilai_akhir_aktual`, `hasil_akhir_aktual_ideal` FROM `sa_user_kpi` WHERE `periode` %s AND `Nama` LIKE %s AND `kpi_status` %s",
                            GetSQLValueString($periode, "defined", $periode, ""),
                            GetSQLValueString('%' . $dtWrikeAssignment[0]['resource_email'] . '%', "text"),
                            GetSQLValueString($kpi_status, "defined", $kpi_status, "")
                        );
                        $kpiResourcex = $DBKPI->get_sql($mysql);
                        if($kpiResourcex[2]>0)
                        {
                            do {
                                $exp1 = explode("<", $kpiResourcex[0]['Nama']);
                                $email = explode(">", $exp1[1]);
                                $tblname = 'mst_employees';
                                $condition = '`email`="' . $email[0] . '"';
                                $employees = $DBHCM->get_data($tblname, $condition);
                                if($employees[2]>0)
                                {
                                    $img = "<img class='img-profile' src='data:image/jpeg;base64, " .  base64_encode($employees[0]['unitdrawing']) . "' style='padding:2px; border:1px solid #e6e6e6; width:30px; height:40px;' title='" . $employees[0]['employee_name'] . "' />";
                                    $resourcex .= $employees[0]['employee_name'] . '; ';
                                }
                    
                                if(array_search($email[0], $DataResource)=='')
                                {
                                    $KPIResources .= '
                                    <tr>
                                    <td>' . $kpiResourcex[0]['Nama'] . '</td>
                                    <td class="text-nowrap">' . $img . '&nbsp;' . $kpiResourcex[0]['Nama'] . '</td>
                                    <td>' . number_format($kpiResourcex[0]['total_nilai_ideal'], 2) . '</td>
                                    <td>' . number_format($kpiResourcex[0]['total_nilai_aktual'], 2) . '</td>
                                    <td>' . number_format($kpiResourcex[0]['hasil_aktual_ideal'], 2) . '</td>
                                    <td>' . number_format($kpiResourcex[0]['total_nilai_akhir_ideal'], 2) . '</td>
                                    <td>' . number_format($kpiResourcex[0]['total_nilai_akhir_aktual'], 2) . '</td>
                                    <td>' . number_format($kpiResourcex[0]['hasil_akhir_aktual_ideal'], 2) . '</td>
                                    </tr>';

                                    // KPI Individual
                                    $tblname = 'summary_user';
                                    $condition = sprintf('Nama = %s AND kpi_status %s AND periode %s',
                                        GetSQLValueString($kpiResourcex[0]['Nama'], "text"),
                                        GetSQLValueString($kpi_status, "defined", $kpi_status, ""),
                                        GetSQLValueString($periode, "defined", $periode, "")
                                    );
                                    $res = $DBKPI->get_data($tblname, $condition);
                                    if($res[2]>0)
                                    {
                                        $exp1 = explode("<", $kpiResourcex[0]['Nama']);
                                        $name = explode(">", $exp1[1]);
                                        if(array_search($name[0], $DataIndividual)=='')
                                        {
                                            if($res[0]['nilai_project']>0)
                                            {
                                                if($res[0]['nilai_project']<=0.6)
                                                { 
                                                    $KPIIndividualPoor++;
                                                } elseif($res[0]['nilai_project']<=0.75)
                                                { 
                                                    $KPIIndividualGood++;
                                                } elseif($res[0]['nilai_project']<=0.9)
                                                {
                                                    $KPIIndividualVeryGood++;
                                                } else
                                                {
                                                    $KPIIndividualExcellence++;
                                                }
                                            }
                                            $MyKPIAll += $res[0]['nilai_project'];
                                            $MyKPICountALL ++;
                                            if($res[0]['Nama'] == $_SESSION['Microservices_UserName'] . "<" . $_SESSION['Microservices_UserEmail'] . ">")
                                            {
                                                $MyKPI += $res[0]['nilai_project'];
                                            }
                                        }
                                        array_push($DataIndividual, $kpiResourcex[0]['Nama']);
                                    }
                                }

                            } while($kpiResourcex[0]=$kpiResourcex[1]->fetch_assoc());
                        }
                        if(isset($email))
                        {
                            array_push($DataResource, $email[0]);
                        }
                    }
                }
            } while($dtWrikeAssignment[0]=$dtWrikeAssignment[1]->fetch_assoc());
            $KPIProject .= '</td>';
            $KPIProject .= '<td>' . $resourcex . '</td>';
        } else
        {
            $KPIProject .= '<td></td><td></td>';
        }

        // Productivity
        // $mysql = "SELECT COUNT(`sa_wrike_task`.`project_id`) AS `total_task`, COUNT(`sa_wrike_timelog`.`task_id`) AS `total_update` FROM `sa_wrike_task` LEFT JOIN `sa_wrike_timelog` ON `sa_wrike_task`.`task_id` = `sa_wrike_timelog`.`task_id` LEFT JOIN `sa_wrike_assignment` ON `sa_wrike_assignment`.`task_id` = `sa_wrike_task`.`task_id` WHERE `sa_wrike_assignment`.`project_id` = '" . $dtWrileProjectList[0]['id'] . "' ORDER BY `sa_wrike_task`.`task_id`;";
        $mysql = "SELECT COUNT(DISTINCT a.task_id, b.resource_id) AS `total_task`, IF(c.resource_id IS NOT NULL, COUNT(c.resource_id), 0) AS `total_update` FROM sa_wrike_task a LEFT JOIN sa_wrike_assignment b ON a.task_id = b.task_id AND a.project_id = b.project_id LEFT JOIN sa_wrike_timelog c ON b.task_id = c.task_id AND b.resource_id = c.resource_id AND b.project_id = c.project_id WHERE a.project_id = '" . $dtWrileProjectList[0]['id'] . "' AND b.project_id IS NOT NULL";
        $dtWrikeTask = $DBWRIKE->get_sql($mysql);
        $KPIProject .= '<td class="align-top">';
        if($dtWrikeTask[2]>0)
        {
            $KPIProject .= $dtWrikeTask[0]['total_task'] . '&nbsp;&nbsp;|&nbsp;&nbsp;' . $dtWrikeTask[0]['total_update'] . '&nbsp;&nbsp;|&nbsp;&nbsp;' . ($dtWrikeTask[0]['total_task']>0 ? number_format($dtWrikeTask[0]['total_update']/$dtWrikeTask[0]['total_task']*100, 0) : 0) . '%';
            $MyTotalTask += $dtWrikeTask[0]['total_task'];
            $MyTotalUpdate += $dtWrikeTask[0]['total_update'];
        }
        $KPIProject .= '</td>';

        // CTE
        $tblname = "data_so";
        $condition = "`so_number_kpi` = '" . $dtWrileProjectList[0]['so_number'] . "'";
        $dtKPIDataSO = $DBKPI->get_data($tblname, $condition);

        //Commercial
        $KPIProject .= '<td class="align-top">' . number_format($dtWrileProjectList[0]['commercial_kpi']*100, 2) . '&nbsp;&nbsp;|&nbsp;&nbsp;' . $dtWrileProjectList[0]['commercial_category'] . '<br><span style="font-size:13px">' . number_format($dtKPIDataSO[0]['SB_mandays_implementation'], 0) . '&nbsp;&nbsp;|&nbsp;&nbsp;' . number_format($dtKPIDataSO[0]['CR_mandays_implementation'], 0) . '&nbsp;&nbsp;|&nbsp;&nbsp;' . number_format($dtKPIDataSO[0]['WR_mandays_actual_implementation'], 0) . '</span></td>';

        if($dtWrileProjectList[0]['commercial_category']=="Normal" && $dtKPIDataSO[0]['WR_mandays_actual_implementation']>0)
        {
            $KPICostNormal++;
        } elseif($dtWrileProjectList[0]['commercial_category']=="Minor" && $dtKPIDataSO[0]['WR_mandays_actual_implementation']>0)
        {
            $KPICostMinor++;
        } elseif($dtWrileProjectList[0]['commercial_category']=="Major" && $dtKPIDataSO[0]['WR_mandays_actual_implementation']>0)
        {
            $KPICostMajor++;
        } else if($dtWrileProjectList[0]['commercial_category']=="Critical" && $dtKPIDataSO[0]['WR_mandays_actual_implementation']>0)
        {
            $KPICostCritical++;
        } else
        {
            $KPICostEmpty++;
        }

        // Time
        $KPIProject .= '<td class="align-top">' . number_format($dtWrileProjectList[0]['time_kpi']*100, 2) . '&nbsp;&nbsp;|&nbsp;&nbsp;' . $dtWrileProjectList[0]['time_category'] . '<br><span style="font-size:13px">' . ($dtWrileProjectList[0]['bast_plan']>0 ? date("d/m/Y", strtotime($dtWrileProjectList[0]['bast_plan'])) : "Empty" ) . '&nbsp;&nbsp;|&nbsp;&nbsp;' . ($dtWrileProjectList[0]['bast_actual'] ? date("d/m/Y", strtotime($dtWrileProjectList[0]['bast_actual'])) : "Empty");

        if($dtWrileProjectList[0]['time_category']=="Normal" && $dtWrileProjectList[0]['bast_actual']!=NULL)
        {
            $KPITimeNormal++;
        } elseif($dtWrileProjectList[0]['time_category']=="Minor" && $dtWrileProjectList[0]['bast_actual']!=NULL)
        {
            $KPITimeMinor++;
        } elseif($dtWrileProjectList[0]['time_category']=="Major" && $dtWrileProjectList[0]['bast_actual']!=NULL)
        {
            $KPITimeMajor++;
        } elseif($dtWrileProjectList[0]['time_category']=="Crictical" && $dtWrileProjectList[0]['bast_actual']!=NULL)
        {
            $KPITimeCritical++;
        } else
        {
            $KPITimeEmpty++;
        }
        $KPIProject .= '</span></td>';
        // Error
        $KPIProject .= '<td class="align-top">' . number_format($dtWrileProjectList[0]['error_kpi']*100, 2) . '&nbsp;&nbsp;|&nbsp;&nbsp;' .$dtWrileProjectList[0]['error_category'] . '</td>';
        // Total CTE
        $KPIProject .= '<td class="align-top">' . number_format($dtWrileProjectList[0]['total_cte']*100,2) . '</td>';
        // Weighted Value
        $KPIProject .= '<td class="align-top">' . number_format($dtWrileProjectList[0]['weighted_value'],2) . '</td>';
        // Status
        $KPIProject .= '<td class="align-top text-nowrap">Project Status is ' . $dtWrileProjectList[0]['status_wr'] . (($dtWrileProjectList[0]['status_project']<>'Closed' && $dtWrileProjectList[0]['status_project']<>'Open') ? '<br>Note is ' . $dtWrileProjectList[0]['status_project'] : '') . '</td>';
        $KPIProject .= '<td>' . $dtWrileProjectList[0]['kpi_status'] . '</td>';
        $KPIProject .= '<td>' . $dtWrileProjectList[0]['periode'] . '</td>';
        $KPIProject .= '</tr>';


    } while($dtWrileProjectList[0]=$dtWrileProjectList[1]->fetch_assoc());

} else
{
    $KPIProject .= '<tr><td colspan="13">No data to display.</td></tr>';
}
$KPIProjectFooter = '<tfoot>' . $KPIProjectHeader . '</tfoot>';
$KPIProject .= $KPIProjectFooter;
$KPIResources .= '</tbody></table></div></div>';
$KPIProject .= '</tbody></table></div></div>';

?>


<div class="row mb-3">
    <div class="col-lg-6">
        <?php menu_dashboard(); ?>
    </div>
    <div class="col-lg-6">
    </div>
</div>

<div class="row mb-3">
    <?php 
    $link = array();
    $xxx = array('link'=>'&periode=2022', 'value'=>'2022');
    array_push($link, $xxx);
    $xxx = array('link'=>'&periode=2023', 'value'=>'2023');
    array_push($link, $xxx);
    Content2("Periode", $periodex, $link,  "primary", "2", "Tiptool"); 
    $link = array();
    $xxx = array('link'=>'&status=Reviewed', 'value'=>'Reviewed');
    array_push($link, $xxx);
    $xxx = array('link'=>'&status=Not Yet Reviewed', 'value'=>'Not Yet Reviewed');
    array_push($link, $xxx);
    Content2("Status Review", $kpi_statusx, $link, "secondary", "2", "Tiptool");
    
    Content2("Project Value", 'IDR' . number_format($MyProjectValue, 2) . 'M', array(), "info", "2", "Tiptool"); 
    Content2("My KPI", number_format($MyKPI, 2), array(), "warning", "3", "Legend:
        
MyKPI | MyKPITeam
        "); 
    
    Content2("My Team KPI", number_format(($MyKPICountALL>0 ? $MyKPIAll/$MyKPICountALL : 0), 2), array(), "danger", "2", "Tiptool"); 
    Content2("My Team Project", $dtWrileProjectList[2], array(), "success", "3", "Legend:

Total Projects | Total Task Assignment | Total Task Update | Percentage Task
"); ?>
    <?php Content2("My Team Task", $MyTotalTask, array(), "dark", "2", "Tiptool"); ?>
    <?php Content2("My Team Task Update", $MyTotalUpdate, array(), "primary", "2", "Tiptool"); ?>
</div>

<div class="row mb-3">
    <div class="col-lg-4">
        <?php
        // Chart
        if($KPICostNormal>0)
        {
            $xxx = array("label"=> "Normal (RU lesss than 100%)", "y"=> $KPICostNormal);
            array_push($dataKPICostChart, $xxx);
        }
        if($KPICostMinor>0)
        {
            $xxx = array("label"=> "Minor (RU 110% - 150%)", "y"=> $KPICostMinor);
            array_push($dataKPICostChart, $xxx);
        }
        if($KPICostMajor>0)
        {
            $xxx = array("label"=> "Major (RU 150% - 200%)", "y"=> $KPICostMajor);
            array_push($dataKPICostChart, $xxx);
        }
        if($KPICostCritical>0)
        {
            $xxx = array("label"=> "Critical (RU over than 200%)", "y"=> $KPICostCritical);
            array_push($dataKPICostChart, $xxx);
        }
        if($KPICostEmpty>0)
        {
            $xxx = array("label"=> "Mandays doesn't exist yet", "y"=> $KPICostEmpty);
            array_push($dataKPICostChart, $xxx);
        }
        ?>

        <div class="card shadow mb-1">
            <div class="card-header fw-bold">Cost : Resource Utilization</div>
            <div class="card-body">
                <?php 
                if(($KPICostNormal+$KPICostMinor+$KPICostMajor+$KPICostCritical)>0) 
                { 
                    ?>
                    <div id="chartKPIRU" style="height: 370px; width: 100%;"></div>
                    <?php 
                } else 
                { 
                    ?>
                    <div id="" style="height: 370px; width: 100%;">No data to display</div>
                    <?php 
                } 
                ?>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <?php
        // Chart
        if($KPITimeNormal>0)
        {
            $xxx = array("label"=> "Normal (delay less than 30 days)", "y"=> $KPITimeNormal);
            array_push($dataKPITimeChart, $xxx);
        }
        if($KPITimeMinor>0)
        {
            $xxx = array("label"=> "Minor (delay 1-3 months)", "y"=> $KPITimeMinor);
            array_push($dataKPITimeChart, $xxx);
        }
        if($KPITimeMajor>0)
        {
            $xxx = array("label"=> "Major (delay 4-6 months)", "y"=> $KPITimeMajor);
            array_push($dataKPITimeChart, $xxx);
        }
        if($KPITimeCritical>0)
        {
            $xxx = array("label"=> "Critical (delay over than 6 months)", "y"=> $KPITimeCritical);
            array_push($dataKPITimeChart, $xxx);
        }
        if($KPITimeEmpty>0)
        {
            $xxx = array("label"=> "BAST doesn't exist yet", "y"=> $KPITimeEmpty);
            array_push($dataKPITimeChart, $xxx);
        }
        ?>

        <div class="card shadow mb-1">
            <div class="card-header fw-bold">Time</div>
            <div class="card-body">
                <?php
                if(($KPITimeNormal+$KPITimeMinor+$KPITimeMajor+$KPITimeCritical)>0)
                {
                    ?>
                    <div id="chartKPITime" style="height: 370px; width: 100%;"></div>
                    <?php
                } else
                {
                    ?>
                    <div id="" style="height: 370px; width: 100%;">No dat to display.</div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <?php
        // Chart
        if($KPIIndividualPoor>0)
        {
            $xxx = array("label"=> "Poor (less than 60%)", "y"=> $KPIIndividualPoor);
            array_push($dataKPIResourceChart, $xxx);
        }
        if($KPIIndividualGood>0)
        {
            $xxx = array("label"=> "Good (60% - 75%)", "y"=> $KPIIndividualGood);
            array_push($dataKPIResourceChart, $xxx);
        }
        if($KPIIndividualVeryGood>0)
        {
            $xxx = array("label"=> "Very Good (75% - 90%)", "y"=> $KPIIndividualVeryGood);
            array_push($dataKPIResourceChart, $xxx);
        }
        if($KPIIndividualExcellence>0)
        {
            $xxx = array("label"=> "Excellence (over than 90%)", "y"=> $KPIIndividualExcellence);
            array_push($dataKPIResourceChart, $xxx);
        }
        ?>

        <div class="card shadow mb-1">
            <div class="card-header fw-bold">KPI Resource (Individual)</div>
            <div class="card-body">
                <?php
                if(($KPIIndividualPoor+$KPIIndividualGood+$KPIIndividualVeryGood+$KPIIndividualExcellence)>0)
                {
                    ?>
                    <div id="chartKPIResource" style="height: 370px; width: 100%;"></div>
                    <?php
                } else
                {
                    ?>
                    <div id="" style="height: 370px; width: 100%;">No data to display.</div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-lg-12">
        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active text-body fw-bold" id="home-tab" data-bs-toggle="tab" data-bs-target="#tabCTEProject" type="button" role="tab" aria-controls="SBList" aria-selected="true" title='SB yang masih dalam bentuk draft'>KPI Project</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-body fw-bold" id="submit-tab" data-bs-toggle="tab" data-bs-target="#tabCTEEngineer" type="button" role="tab" aria-controls="CTEEngineer" aria-selected="false" title='SB yang sudah disubmit ke manager'>KPI Resources</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-body fw-bold" id="submit-tab" data-bs-toggle="tab" data-bs-target="#subOrdinate" type="button" role="tab" aria-controls="subOrdinate" aria-selected="false" title='SB yang sudah disubmit ke manager'>Sub-Ordinate</button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="tabCTEProject" role="tabpanel" aria-labelledby="CTEProject-tab">
                    <?php echo $KPIProject; ?>
                </div>
                <div class="tab-pane" id="tabCTEEngineer" role="tabpanel" aria-labelledby="CTEEngineer-tab">
                    <?php echo $KPIResources; ?>
                </div>
                <div class="tab-pane" id="subOrdinate" role="tabpanel" aria-labelledby="subOrdinate-tab">
                    <?php echo $subOrdinatsConditionx[1]; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel"><div id="title"></div></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="div1"></div>
        <div id="div2"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<script>
window.onload = function () {

    var chart = new CanvasJS.Chart("chartKPIRU", {
        animationEnabled: true,
        legend: {
            verticalAlign: "center",  // "top" , "center", "bottom"
            horizontalAlign: "right"  // "left", "center" , "right"
        },
        data: [{
            type: "doughnut",
            startAngle: -90,
            radius: "100%",
            innerRadius: "50%",
            indexLabel: "{y}",
            yValueFormatString: "#,##0",
            showInLegend: true,
            legendText: "{label}",
            dataPoints: <?php echo json_encode($dataKPICostChart, JSON_NUMERIC_CHECK); ?>
        }],
    });
    chart.render();

    var chart = new CanvasJS.Chart("chartKPITime", {
        animationEnabled: true,
        legend: {
            verticalAlign: "center",  // "top" , "center", "bottom"
            horizontalAlign: "right"  // "left", "center" , "right"
        },
        data: [{
            type: "doughnut",
            startAngle: -90,
            radius: "100%",
            innerRadius: "50%",
            indexLabel: "{y}",
            yValueFormatString: "#,##0",
            showInLegend: true,
            legendText: "{label}",
            dataPoints: <?php echo json_encode($dataKPITimeChart, JSON_NUMERIC_CHECK); ?>
        }]
    });
    chart.render();

    var chart = new CanvasJS.Chart("chartKPIResource", {
        animationEnabled: true,
        legend: {
            verticalAlign: "center",  // "top" , "center", "bottom"
            horizontalAlign: "right"  // "left", "center" , "right"
        },
        data: [{
            type: "doughnut",
            startAngle: -90,
            radius: "100%",
            innerRadius: "50%",
            indexLabel: "{y}",
            yValueFormatString: "#,##0",
            showInLegend: true,
            legendText: "{label}",
            dataPoints: <?php echo json_encode($dataKPIResourceChart, JSON_NUMERIC_CHECK); ?>
        }]
    });
    chart.render();
}
</script>