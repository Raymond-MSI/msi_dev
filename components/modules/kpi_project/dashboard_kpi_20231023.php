<?php
function menu_dashboard()
{
    global $DB;
    ?>
    <div class="row">
        <div class="col-lg-12 mb-3">
            <select class="form-select form-select-lg shadow" id="onload" name="onload" onchange="loadpage()">
                <option value="dashboard" <?php echo (isset($_GET['sub']) && $_GET['sub']=="dashboard") ? "selected" : ""; ?>>Dashboard</option>
                <?php
                // option("SERVICE_BUDGET", "Service Budget", "dashboard_sbf");
                // option("KPI_PROJECT", "KPI Project Implementation", "dashboard_kpi");
                // option("EDO", "Extra Day Off", "dashboard_edo");
                // option("ASSET", "Asset", "dashboard_asset");
                // option("CHANGE_REQUEST", "Change Request", "dashboard_cr");
                // option("SURVEY", "Survey", "dashboard_survey");
                // $tblname = "cfg_web";
                // $condition = "parent=152";
                // $order = "`order` ASC";
                // $dash = $DB->get_data($tblname, $condition, $order);
                // if($dash[2]>0)
                // {
                //     do {
                //         $xxx = $dash[0]['params'];
                //         $values = json_decode($xxx, true);
                //         if($values['status']=="enabled") {
                //             option($values['module'], $values['title'], $values['value']);
                //         }
                //     } while($dash[0]=$dash[1]->fetch_assoc());
                // }

                $tblname = "cfg_web";
                $condition = "parent=10";
                $order = "`order` ASC";
                $dash = $DB->get_data($tblname, $condition, $order);
                if($dash[2]>0)
                {
                    do {
                        $xxx = $dash[0]['config_value'];
                        $values = json_decode($xxx, true);
                        if(isset($values['module']['dashboard']['status']) && $values['module']['dashboard']['status']=="enabled") {
                            option($values['module']['dashboard']['module'], $values['module']['dashboard']['title'], $values['module']['dashboard']['link']);
                        }
                    } while($dash[0]=$dash[1]->fetch_assoc());
                }

                ?>
            </select>
        </div>
    </div>
    <?php
}
function option($mdlname, $title, $value)
{
    // $userpermission = useraccess_v2($mdlname, false);
    // if($userpermission['mdllevel']!='') 
    // {
        $link = "mod=" . $_GET['mod'] . "&sub=" . $_GET['sub'];
        echo '<option value="' . $value . '" ' . ($link==$value ? "selected" : "") . '>' . $title . '</option>';
    // }
}


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
                            <a class="dropdown-item" href="index.php?mod=kpi_project&sub=dashboard_kpi<?php echo $listData[$i]['link']; ?>"><?php echo $listData[$i]['value']; ?></a>
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
                    if($subordinat2!="None")
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
                            if($subordinat3!="None")
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

    $('#CTEProject tbody').on('dblclick', 'tr', function () {
        var data1 = tblProject.row(this).data();
        $("#exampleModal").modal("show");
        $("#div1").load("components/modules/kpi_project/dashboard_kpi_detail.php?so_number="+data1[2]);
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
            "targets": [2,3,4,5,6,7,8],
            "className": 'dt-body-center'
            },
            {
            "targets": [0,8,9],
            "visible": false
            }
        ]
    });

    $('#CTEResource tbody').on('dblclick', 'tr', function () {
        var data2 = tblResource.row(this).data();
        const exp1 = data2[0].split("<");
        const exp2 = exp1[1].split(">");
        const periode_so = data2[8];
        const kpi_status = data2[9];
        $("#exampleModal").modal("show");
        document.getElementById("div1").innerHTML = '';
        if(kpi_status=="Not yet reviewed")
        {
            $("#div2").load("components/modules/kpi_project/dashboard_kpi_resource.php?resource="+exp2[0]+"&status=Not%20Yet%20Reviewed&periode_so="+periode_so);
        } else
        {
            $("#div2").load("components/modules/kpi_project/dashboard_kpi_resource.php?resource="+exp2[0]+"&status=Reviewed&periode_so="+periode_so);
        }
        document.getElementById("title").innerHTML = 'KPI Resource : '+data2[0];
    });

    var tblResource2 = $('#CTEResource2').DataTable({
        dom: 'Bflrtip',
        buttons:[
            {
            extend: 'excelHtml5',
            text: "<i class='fa-solid fa-file-excel'></i>",
            title: 'KPI_Resources_'+<?php echo date("YmdGis"); ?>
            }
        ],
        ordering: false,
        "columnDefs": [
            {
            "targets": [2,3,4,5,6,7],
            "className": 'dt-body-center'
            },
            {
            "targets": [0,8],
            "visible": false
            }
        ]
    });

    $('#CTEResource2 tbody').on('dblclick', 'tr', function () {
        var data2 = tblResource2.row(this).data();
        const exp1 = data2[0].split("<");
        const exp2 = exp1[1].split(">");
        const periode_so =data2[8];
        $("#exampleModal").modal("show");
        document.getElementById("div1").innerHTML = '';
        $("#div2").load("components/modules/kpi_project/dashboard_kpi_resource.php?resource="+exp2[0]+"&status=Not%20Yet%20Reviewed&periode_so="+periode_so);
        document.getElementById("title").innerHTML = 'KPI Resource : '+data2[0];
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
    // $xxx = get_subCondition($owner);
    // if($xxx[1]!='')
    // {
    //     // $subOrdinatsConditionx = get_subCondition($owner);
    //     // $subOrdinatsCondition = $subOrdinatsConditionx[0];
    // } else
    // {
    //     // $subOrdinatsConditionx = array(NULL, '<div class="card"><div class="card-body shadow table-responsive"><table class="display" id="example"><thead><tr><th>No.</th><th>Resource Name</th></tr></thead><tbody><tr><td class="text-center" colspan="2">No data to display.</td></tr></tbody></table></div></div>');
    // }
} else
{
    $owner = '%%';
    // $subOrdinatsConditionx = array(NULL, '<div class="card"><div class="card-body shadow table-responsive"><table class="display" id="example"><thead><tr><th>No.</th><th>Resource Name</th></tr></thead><tbody><tr><td class="text-center" colspan="2">No data to display.</td></tr></tbody></table></div></div>');
}
$userLogin = $_SESSION['Microservices_UserEmail'];
$userLevel = $DBHCM->get_leader_v2($userLogin);
if($owner== 'sumarno@mastersystem.co.id' OR $owner== 'lucky.andiani@mastersystem.co.id' OR $owner == 'ary.mulyati@mastersystem.co.id' OR $owner== 'hendri@mastersystem.co.id' OR $userLevel[3]<3)
{
    $owner = '%%';
}

if(isset($_GET['periode_so']))
{
    $periode_sox = $_GET['periode_so'];
    $periode_so = ' = ' . $_GET['periode_so'];
} else
{
    $periode_sox = DATE("Y");
    $periode_so = ' = ' . date("Y");
}

if(isset($_GET['status']))
{
    $kpi_statusx = $_GET['status'];
    $kpi_status = '= "' . $_GET['status'] . '"';
} else
{
    $kpi_statusx = 'Not Yet Reviewed';
    $kpi_status = '= "Not Yet Reviewed"';
}

if(isset($_GET['periode_so']) && $_GET['periode_so']=="All")
{
    $periode_sox = 'All';
    $periode_so = ' LIKE "%%"';
}

// New Code 20230526
    $periode = 'All';
    $periode_x = ' LIKE "%%"';

// New Code 20230526
// $mysql = sprintf("SELECT `sa_wrike_integrate`.`sa_wrike_project_list`.`id`, `sa_wrike_integrate`.`sa_wrike_project_detail`.`project_code`, `sa_wrike_integrate`.`sa_wrike_project_list`.`no_so` AS `so_number`, `sa_wrike_integrate`.`sa_wrike_project_detail`.`customer_name`, `sa_wrike_integrate`.`sa_wrike_project_list`.`title` AS `project_name`, `sa_wrike_integrate`.`sa_wrike_project_list`.`permalink`, `sa_wrike_integrate`.`sa_wrike_project_list`.`owner_email`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`kpi_status`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`value`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`commercial_kpi`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`commercial_category`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`time_kpi`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`time_category`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`error_kpi`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`error_category`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`total_cte`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`weighted_value`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`start_assignment`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`bast_plan`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`bast_actual`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`status_wr`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`status_project`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`periode_so`, `sa_dashboard_kpi`.`sa_data_so`.`WR_bast_date_actual_project_implementation` FROM `sa_wrike_integrate`.`sa_wrike_project_list` LEFT JOIN `sa_dashboard_kpi`.`sa_kpi_so_wr` ON `sa_dashboard_kpi`.`sa_kpi_so_wr`.`so_number` = `sa_wrike_integrate`.`sa_wrike_project_list`.`no_so` LEFT JOIN `sa_wrike_integrate`.`sa_wrike_project_detail` ON `sa_wrike_integrate`.`sa_wrike_project_list`.`id` = `sa_wrike_integrate`.`sa_wrike_project_detail`.`project_id` LEFT JOIN `sa_dashboard_kpi`.`sa_data_so` ON `sa_dashboard_kpi`.`sa_data_so`.`so_number_kpi` = `sa_wrike_integrate`.`sa_wrike_project_list`.`no_so` WHERE `sa_wrike_integrate`.`sa_wrike_project_list`.`project_type` LIKE %s AND `sa_dashboard_kpi`.`sa_kpi_so_wr`.`kpi_status` %s AND `sa_dashboard_kpi`.`sa_data_so`.`periode_so` %s AND `sa_dashboard_kpi`.`sa_data_so`.`periode` %s AND (`sa_wrike_integrate`.`sa_wrike_project_list`.`owner_email` LIKE %s OR `sa_wrike_integrate`.`sa_wrike_project_list`.`id` IN (SELECT `sa_wrike_integrate`.`sa_wrike_assignment`.`project_id` FROM `sa_wrike_integrate`.`sa_wrike_assignment` WHERE `sa_wrike_integrate`.`sa_wrike_assignment`.`resource_email` LIKE %s GROUP BY `sa_wrike_integrate`.`sa_wrike_assignment`.`project_id`, `sa_wrike_integrate`.`sa_wrike_assignment`.`resource_email`) %s); ",
//     GetSQLValueString($project_type, "text"),
//     GetSQLValueString($kpi_status, "defined", $kpi_status, ""),
//     GetSQLValueString($periode_so, "defined", $periode_so, ""),
//     GetSQLValueString($periode_x, "defined", $periode_x, ""),
//     GetSQLValueString($owner, "text"),
//     GetSQLValueString($owner, "text"),
//     GetSQLValueString($subOrdinatsCondition, "defined", $subOrdinatsCondition, "")
// );

$mysql = sprintf("SELECT `sa_wrike_integrate`.`sa_wrike_project_list`.`id`, `sa_wrike_integrate`.`sa_wrike_project_detail`.`project_code`, `sa_wrike_integrate`.`sa_wrike_project_list`.`no_so` AS `so_number`, `sa_wrike_integrate`.`sa_wrike_project_detail`.`customer_name`, `sa_wrike_integrate`.`sa_wrike_project_list`.`title` AS `project_name`, `sa_wrike_integrate`.`sa_wrike_project_list`.`permalink`, `sa_wrike_integrate`.`sa_wrike_project_list`.`owner_email`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`kpi_status`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`value`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`commercial_kpi`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`commercial_category`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`time_kpi`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`time_category`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`error_kpi`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`error_category`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`total_cte`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`weighted_value`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`start_assignment`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`bast_plan`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`bast_actual`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`status_wr`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`status_project`, `sa_dashboard_kpi`.`sa_kpi_so_wr`.`periode_so`, `sa_dashboard_kpi`.`sa_data_so`.`WR_bast_date_actual_project_implementation` FROM `sa_wrike_integrate`.`sa_wrike_project_list` LEFT JOIN `sa_dashboard_kpi`.`sa_kpi_so_wr` ON `sa_dashboard_kpi`.`sa_kpi_so_wr`.`order_number` = `sa_wrike_integrate`.`sa_wrike_project_list`.`order_number` LEFT JOIN `sa_wrike_integrate`.`sa_wrike_project_detail` ON `sa_wrike_integrate`.`sa_wrike_project_list`.`id` = `sa_wrike_integrate`.`sa_wrike_project_detail`.`project_id` LEFT JOIN `sa_dashboard_kpi`.`sa_data_so` ON `sa_dashboard_kpi`.`sa_data_so`.`order_number` = `sa_wrike_integrate`.`sa_wrike_project_list`.`order_number` WHERE `sa_wrike_integrate`.`sa_wrike_project_list`.`project_type` LIKE %s AND `sa_dashboard_kpi`.`sa_kpi_so_wr`.`kpi_status` %s AND `sa_dashboard_kpi`.`sa_data_so`.`periode_so` %s AND `sa_dashboard_kpi`.`sa_kpi_so_wr`.`periode_so` %s AND `sa_dashboard_kpi`.`sa_data_so`.`periode` %s AND (`sa_wrike_integrate`.`sa_wrike_project_list`.`owner_email` LIKE %s OR `sa_wrike_integrate`.`sa_wrike_project_list`.`id` IN (SELECT `sa_wrike_integrate`.`sa_wrike_assignment`.`project_id` FROM `sa_wrike_integrate`.`sa_wrike_assignment` WHERE `sa_wrike_integrate`.`sa_wrike_assignment`.`resource_email` LIKE %s GROUP BY `sa_wrike_integrate`.`sa_wrike_assignment`.`project_id`, `sa_wrike_integrate`.`sa_wrike_assignment`.`resource_email`) %s); ",
    GetSQLValueString($project_type, "text"),
    GetSQLValueString($kpi_status, "defined", $kpi_status, ""),
    GetSQLValueString($periode_so, "defined", $periode_so, ""),
    GetSQLValueString($periode_so, "defined", $periode_so, ""),
    GetSQLValueString($periode_x, "defined", $periode_x, ""),
    GetSQLValueString($owner, "text"),
    GetSQLValueString($owner, "text"),
    GetSQLValueString($subOrdinatsCondition, "defined", $subOrdinatsCondition, "")
);
//echo $mysql;
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

if($dtWrileProjectList[2]>0)
{
    $resourcez = array();
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
            </span>&nbsp;&nbsp;|&nbsp;&nbsp; 
            <span class="text-nowrap">' . 
                $dtWrileProjectList[0]['project_name'] . '
            </span>&nbsp;&nbsp;|&nbsp;&nbsp; 
            <span>
                IDR' . number_format($dtWrileProjectList[0]['value'], 2)  . 'M&nbsp;&nbsp;|&nbsp;&nbsp; ' . '
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
                $condition = '`email`="' . $dtWrikeAssignment[0]['resource_email'] . '" AND `email` <> ""';
                $employees = $DBHCM->get_data($tblname, $condition);
                if($employees[2]>0)
                {
                    // KPI Project
                    $KPIProject .= "<img class='img-profile' src='data:image/jpeg;base64, " .  base64_encode($employees[0]['unitdrawing']) . "' style='padding:2px; border:1px solid #e6e6e6; width:30px; height:40px;' title='" . $employees[0]['employee_name'] . "' />";
                    $resourcex .= $employees[0]['employee_name'] . '; ';

                    // KPI Resource
                    if(array_search($dtWrikeAssignment[0]['resource_email'], $DataResource)=='')
                    {
                        $emailx = array("email"=>$dtWrikeAssignment[0]['resource_email']);
                        $resourcez = array_merge($resourcez, $emailx);
                        $mysql = sprintf("SELECT DISTINCT `Nama`, `total_nilai_ideal`, `total_nilai_aktual`, `hasil_aktual_ideal`, `total_nilai_akhir_ideal`, `total_nilai_akhir_aktual`, `hasil_akhir_aktual_ideal` FROM `sa_user_kpi` WHERE `periode_so` %s AND `Nama` LIKE %s AND `kpi_status` %s",
                            GetSQLValueString($periode_so, "defined", $periode_so, ""),
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
                                    // KPI Individual
                                    $tblname = 'summary_user';
                                    $email = $DBHCM->split_email($kpiResourcex[0]['Nama']);
                                    $condition = sprintf('Nama LIKE %s AND kpi_status %s AND periode_so %s',
                                        // GetSQLValueString($kpiResourcex[0]['Nama'], "text"),
                                        GetSQLValueString("%" . $email[1] . "%", "text"),
                                        GetSQLValueString($kpi_status, "defined", $kpi_status, ""),
                                        GetSQLValueString($periode_so, "defined", $periode_so, "")
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
                                                if($res[0]['nilai_project']<=60)
                                                { 
                                                    $KPIIndividualPoor++;
                                                } elseif($res[0]['nilai_project']<=75)
                                                { 
                                                    $KPIIndividualGood++;
                                                } elseif($res[0]['nilai_project']<=90)
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
        $mysql = "SELECT COUNT(`sa_wrike_task`.`task_id`) AS `total_task` FROM `sa_wrike_project_list` LEFT JOIN `sa_wrike_assignment` ON `sa_wrike_project_list`.`id` = `sa_wrike_assignment`.`project_id` LEFT JOIN `sa_wrike_task` ON `sa_wrike_task`.`task_id` = `sa_wrike_assignment`.`task_id` AND `sa_wrike_task`.`project_id` = `sa_wrike_project_list`.`id` WHERE `sa_wrike_project_list`.`id` = '" . $dtWrileProjectList[0]['id'] . "' ;";
        $dtWrikeTask = $DBWRIKE->get_sql($mysql);
        $KPIProject .= '<td class="align-top">';

        $mysql = "SELECT COUNT(DISTINCT `sa_wrike_timelog`.`timelog_id`) AS `total_update` FROM `sa_wrike_project_list` LEFT JOIN `sa_wrike_timelog` ON `sa_wrike_project_list`.`id` = `sa_wrike_timelog`.`project_id` LEFT JOIN `sa_wrike_assignment` ON `sa_wrike_assignment`.`resource_id` = `sa_wrike_timelog`.`resource_id` WHERE `sa_wrike_project_list`.`id` = '" . $dtWrileProjectList[0]['id'] . "' AND `sa_wrike_timelog`.`timelog_id` <> ''; ";
        $dtWrikeTaskUpdate = $DBWRIKE->get_sql($mysql);

        if($dtWrikeTask[2]>0)
        {
            $KPIProject .= $dtWrikeTask[0]['total_task'] . '&nbsp;&nbsp;|&nbsp;&nbsp;' . $dtWrikeTaskUpdate[0]['total_update']; // . '&nbsp;&nbsp;|&nbsp;&nbsp;' . ($dtWrikeTask[0]['total_task']>0 ? number_format($dtWrikeTask[0]['total_update']/$dtWrikeTask[0]['total_task']*100, 0) : 0) . '%';
            $MyTotalTask += $dtWrikeTask[0]['total_task'];
            $MyTotalUpdate += $dtWrikeTaskUpdate[0]['total_update'];
        }
        $KPIProject .= '</td>';

        // CTE
        $tblname = "data_so";
        $condition = "`so_number_kpi` = '" . $dtWrileProjectList[0]['so_number'] . "'";
        $dtKPIDataSO = $DBKPI->get_data($tblname, $condition);

        //Commercial
        $KPIProject .= 
        '<td class="align-top">' . number_format($dtWrileProjectList[0]['commercial_kpi']*100, 2) . '&nbsp;&nbsp;|&nbsp;&nbsp;' . $dtWrileProjectList[0]['commercial_category'] . '&nbsp;&nbsp;<br>
        <span style="font-size:13px">' . number_format($dtKPIDataSO[0]['SB_mandays_implementation'], 0) . '&nbsp;&nbsp;|&nbsp;&nbsp;' . 
        number_format($dtKPIDataSO[0]['CR_mandays_implementation'], 0) . '&nbsp;&nbsp;|&nbsp;&nbsp;' . 
        number_format(($dtKPIDataSO[0]['WR_mandays_actual_implementation']!="" ? $dtKPIDataSO[0]['WR_mandays_actual_implementation'] : "0"), 0) . '</span></td>';

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
        $KPIProject .= '<td class="align-top">' . number_format($dtWrileProjectList[0]['time_kpi']*100, 2) . '&nbsp;&nbsp;|&nbsp;&nbsp;' . $dtWrileProjectList[0]['time_category'] . '&nbsp;&nbsp;<br><span style="font-size:13px">' . ($dtWrileProjectList[0]['bast_plan']>0 ? date("d/m/Y", strtotime($dtWrileProjectList[0]['bast_plan'])) : "Empty" ) . '&nbsp;&nbsp;|&nbsp;&nbsp;' . ($dtWrileProjectList[0]['bast_actual'] ? date("d/m/Y", strtotime($dtWrileProjectList[0]['bast_actual'])) : "Empty");

        $KPIProject .= '</span></td>';
        // Error
        $KPIProject .= '<td class="align-top">' . number_format($dtWrileProjectList[0]['error_kpi']*100, 2) . '&nbsp;&nbsp;|&nbsp;&nbsp;' .$dtWrileProjectList[0]['error_category'] . '</td>';
        // Total CTE
        $KPIProject .= '<td class="align-top">' . number_format($dtWrileProjectList[0]['total_cte']*100,2) . '</td>';
        // Weighted Value
        $KPIProject .= '<td class="align-top">' . number_format($dtWrileProjectList[0]['weighted_value'],2) . '</td>';
        // Status
        $KPIProject .= '<td class="align-top text-nowrap">Project Status is ' . $dtWrileProjectList[0]['status_wr'] . (($dtWrileProjectList[0]['status_project']<>'Closed' && $dtWrileProjectList[0]['status_project']<>'Open') ? '&nbsp;&nbsp;<br>Note is ' . $dtWrileProjectList[0]['status_project'] : '') . '</td>';
        $KPIProject .= '<td>' . $dtWrileProjectList[0]['kpi_status'] . '</td>';
        $KPIProject .= '<td>' . $dtWrileProjectList[0]['periode_so'] . '</td>';
        $KPIProject .= '</tr>';


    } while($dtWrileProjectList[0]=$dtWrileProjectList[1]->fetch_assoc());

} else
{
    $KPIProject .= '<tr><td colspan="13">No data to display.</td></tr>';
}
$KPIProjectFooter = '<tfoot>' . $KPIProjectHeader . '</tfoot>';
$KPIProject .= $KPIProjectFooter;
// $KPIResources .= '</tbody></table></div></div>';
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
    $mysql = "SELECT `periode_so` AS `tahun` FROM `sa_data_so` GROUP BY `periode_so` ORDER BY `periode_so` DESC";
    $tahun = $DBKPI->get_sql($mysql);
    if($tahun[2]>0)
    {
        $link = array();
        $xxx = array('link'=>'&periode_so=All&status=' . $kpi_statusx, 'value'=>"All");
        array_push($link, $xxx);
    do {
            $xxx = array('link'=>'&periode_so=' . $tahun[0]['tahun'] . '&status=' . $kpi_statusx, 'value'=>$tahun[0]['tahun']);
            array_push($link, $xxx);
        } while($tahun[0]=$tahun[1]->fetch_assoc());
    }
    Content2("Periode Project", $periode_sox, $link,  "primary", "2", "Tiptool"); 
    $link = array();
    $xxx = array('link'=>'&periode_so=' . $periode_sox . '&status=Reviewed', 'value'=>'Reviewed');
    array_push($link, $xxx);
    $xxx = array('link'=>'&periode_so=' . $periode_sox . '&status=Not Yet Reviewed', 'value'=>'Not Yet Reviewed');
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
            $xxx = array("label"=> "Normal", "y"=> $KPICostNormal);
            array_push($dataKPICostChart, $xxx);
        }
        if($KPICostMinor>0)
        {
            $xxx = array("label"=> "Minor", "y"=> $KPICostMinor);
            array_push($dataKPICostChart, $xxx);
        }
        if($KPICostMajor>0)
        {
            $xxx = array("label"=> "Major", "y"=> $KPICostMajor);
            array_push($dataKPICostChart, $xxx);
        }
        if($KPICostCritical>0)
        {
            $xxx = array("label"=> "Critical", "y"=> $KPICostCritical);
            array_push($dataKPICostChart, $xxx);
        }
        if($KPICostEmpty>0)
        {
            $xxx = array("label"=> "None", "y"=> $KPICostEmpty);
            array_push($dataKPICostChart, $xxx);
        }
        ?>

        <div class="card shadow mb-1">
            <div class="card-header fw-bold d-flex flex-row align-items-center justify-content-between">
                Cost : Resource Utilization
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Menu Properties:</div>
                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#mdlHelp" data-bs-whatever="Cost">Help</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="chartKPIRU" style="height: 370px; width: 100%;"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow mb-1">
            <div class="card-header fw-bold d-flex flex-row align-items-center justify-content-between">
                Time
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Menu Properties:</div>
                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#mdlHelp" data-bs-whatever="Time">Help</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php
                if($project_type == "MSI Project Implementation")
                {
                    $project_typex = "%1%";
                } else
                {
                    $project_typex = "%2%";
                }
                $mysql = sprintf("
                    SELECT 
                        IF(DATEDIFF(WR_bast_date_actual_project_implementation, WR_bast_date_project_implementation) < 31, 
                            'Normal',
                            IF(DATEDIFF(WR_bast_date_actual_project_implementation, WR_bast_date_project_implementation) < 90,
                                'Minor',
                                IF(DATEDIFF(WR_bast_date_actual_project_implementation, WR_bast_date_project_implementation) < 180,
                                    'Major',
                                    'Critical'
                                )
                            )
                        ) AS `time_category`,
                        COUNT(`id`) AS `total`
                        FROM
                            sa_dashboard_kpi.sa_data_so
                        WHERE
                            WR_bast_date_actual_project_implementation <> ''
                                AND kpi_status %s
                                AND periode_so %s
                                AND SB_bundling LIKE %s
                        GROUP BY 
                            IF(DATEDIFF(WR_bast_date_actual_project_implementation, WR_bast_date_project_implementation) < 31,
                                'Normal',
                                IF(DATEDIFF(WR_bast_date_actual_project_implementation, WR_bast_date_project_implementation) < 90,
                                    'Minor',
                                    IF(DATEDIFF(WR_bast_date_actual_project_implementation, WR_bast_date_project_implementation) < 180,
                                        'Major',
                                        'Critical'
                                    )
                                )
                            )
                        ORDER BY
                            IF(DATEDIFF(WR_bast_date_actual_project_implementation, WR_bast_date_project_implementation) < 31,
                                '1',
                                IF(DATEDIFF(WR_bast_date_actual_project_implementation, WR_bast_date_project_implementation) < 90,
                                    '2',
                                    IF(DATEDIFF(WR_bast_date_actual_project_implementation, WR_bast_date_project_implementation) < 180,
                                        '3',
                                        '4'
                                    )
                                )
                            );",
                    GetSQLValueString($kpi_status, "defined", $kpi_status, ""),
                    GetSQLValueString($periode_so, "defined", $periode_so, ""),
                    GetSQLValueString($project_typex, "text")
                );
                $KPITime = $DBWRIKE->get_sql($mysql);
                if($KPITime[2]>0)
                {
                    do {
                        $xxx = array("label"=> $KPITime[0]['time_category'], "y"=> $KPITime[0]['total']);
                        array_push($dataKPITimeChart, $xxx);
                    } while($KPITime[0]=$KPITime[1]->fetch_assoc());
                }
                ?>
                <div id="chartKPITime" style="height: 370px; width: 100%;"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <?php
        if($periode_sox=="All")
        {
            $periode_soxx = "";
        } else
        {
            $periode_soxx = $periode_sox;
        }
        $mysql = sprintf("SELECT IFNULL(SUM(CAST(REPLACE(`nilai_akhir_aktual`, ',', '.') AS Decimal(10,2))),0) AS `actual_final_value`, IFNULL(SUM(CAST(REPLACE(`nilai_akhir_ideal`, ',', '.') AS Decimal(10,2))),0) / IFNULL(SUM(REPLACE(`nilai_akhir_aktual`, ',', '.')),1) * 100 AS `average_resource` FROM `sa_user` WHERE  %s `kpi_status` = %s GROUP BY `Nama`; ",
            GetSQLValueString($periode_soxx, "defined", "`periode_so` = " . $periode_soxx . " AND ", ""),
            GetSQLValueString($kpi_statusx, "text")
        );
        $KPIResources = $DBKPI->get_sql($mysql);
        if($KPIResources[2]>0)
        {
            $KPIIndividualPoor = 0;
            $KPIIndividualGood = 0;
            $KPIIndividualVeryGood = 0;
            $KPIIndividualExcellence = 0;
            do { 
                if($KPIResources[0]['average_resource']<=60)
                {
                    $KPIIndividualPoor++;
                } elseif($KPIResources[0]['average_resource']<=75)
                {
                    $KPIIndividualGood++;
                } elseif($KPIResources[0]['average_resource']<=90)
                {
                    $KPIIndividualVeryGood++;
                } else
                // if($KPIResources[0]['actual_final_value']>80)
                {
                    $KPIIndividualExcellence++;
                }
            } while($KPIResources[0]=$KPIResources[1]->fetch_assoc());

            if($KPIIndividualPoor>0)
            {
                $xxx = array("label"=> "Poor", "y"=> $KPIIndividualPoor);
                array_push($dataKPIResourceChart, $xxx);
            }
            if($KPIIndividualGood>0)
            {
                $xxx = array("label"=> "Good", "y"=> $KPIIndividualGood);
                array_push($dataKPIResourceChart, $xxx);
            }
            if($KPIIndividualVeryGood>0)
            {
                $xxx = array("label"=> "Very Good", "y"=> $KPIIndividualVeryGood);
                array_push($dataKPIResourceChart, $xxx);
            }
            if($KPIIndividualExcellence>0)
            {
                $xxx = array("label"=> "Excellence", "y"=> $KPIIndividualExcellence);
                array_push($dataKPIResourceChart, $xxx);
            }
        }
        ?>

        <div class="card shadow mb-1">
            <div class="card-header fw-bold d-flex flex-row align-items-center justify-content-between">
                KPI Resource (Individual)
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Menu Properties:</div>
                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#mdlHelp" data-bs-whatever="Resource">Help</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="chartKPIResource" style="height: 370px; width: 100%;"></div>
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
                    <!-- KPI Projects -->
                    <?php echo $KPIProject; ?>
                </div>
                <div class="tab-pane" id="tabCTEEngineer" role="tabpanel" aria-labelledby="CTEEngineer-tab">
                    <!-- KPI Resources -->
                    <div class="card shadow">
                        <div class="card-body">
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
                                        <th>Periode</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if($periode_sox=="All")
                                    {
                                        $periode_sox = "";
                                    }
                                    $mysql = sprintf("SELECT `Nama`, SUM(`nilai_ideal`) AS `ideal_value`, SUM(`nilai_aktual`) AS `actual_value`, SUM(`nilai_aktual`) / SUM(`nilai_ideal`) * 100 AS `average`, SUM(REPLACE(`nilai_akhir_ideal`, ',', '.')) AS `ideal_final_value`, SUM(REPLACE(`nilai_akhir_aktual`, ',', '.')) AS `actual_final_value`, SUM(REPLACE(`nilai_akhir_ideal`, ',', '.')) / SUM(REPLACE(`nilai_akhir_aktual`, ',', '.')) * 100 AS `average_resource`, `periode_so`, `kpi_status` FROM `sa_user` WHERE %s `kpi_status` = %s GROUP BY `Nama`; ",
                                            GetSQLValueString($periode_sox, "defined", "`periode_so` = " . $periode_sox . " AND ", ""),
                                            GetSQLValueString($kpi_statusx, "text")
                                    );
                                    $dtResources = $DBKPI->get_sql($mysql);
                                    if($dtResources[2]>0)
                                    {
                                        do {
                                            $exp1 = explode("<", $dtResources[0]['Nama']);
                                            $email = explode(">", $exp1[1]);
                                            $img = "<img class='img-profile' src='data:image/jpeg;base64, " .  base64_encode($DBHCM->get_photo($email[0])) . "' style='padding:2px; border:1px solid #e6e6e6; width:30px; height:40px;' title='" . $exp1[0] . "' />";
                                            $email = $DBHCM->split_email($dtResources[0]['Nama']);
                                            $emailx = $email[2];
                                            ?>
                                            <tr>
                                                <td><?php echo $emailx; ?></td>
                                                <td class="text-nowrap"><?php echo $img . "&nbsp;" . $emailx; ?></td>
                                                <td><?php echo number_format($dtResources[0]['ideal_value'], 2); ?></td>
                                                <td><?php echo number_format($dtResources[0]['actual_value'], 2); ?></td>
                                                <td><?php echo number_format($dtResources[0]['average'], 2); ?></td>
                                                <td><?php echo number_format($dtResources[0]['ideal_final_value'], 2); ?></td>
                                                <td><?php echo number_format($dtResources[0]['actual_final_value'], 2); ?></td>
                                                <td><?php echo number_format($dtResources[0]['average_resource'], 2); ?></td>
                                                <td><?php echo $periode_sox; ?></td>
                                                <td><?php echo $dtResources[0]['kpi_status']; ?></td>
                                            </tr>
                                            <?php
                                        } while($dtResources[0]=$dtResources[1]->fetch_assoc());
                                    } else
                                    {
                                        ?>
                                        <tr><td colspan="8">No data to displayed.</td></tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="subOrdinate" role="tabpanel" aria-labelledby="subOrdinate-tab">
                    <!-- KPI Sub-Ordinats -->
                    <div class="card shadow">
                        <div class="card-body table-responsive">
                            <table class="display" id="CTEResource2" width="100%">
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
                                        <th>Periode</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if($periode_sox=="All")
                                    {
                                        $periode_sox = "";
                                    }
                                    $userEmail = $_SESSION['Microservices_UserEmail'];
                                    $subOrdinats = $DBHCM->get_leader_v2($userEmail);
                                    foreach($subOrdinats[2] as $subOrdinat)
                                    {
                                        $emailz = $DBHCM->split_email($subOrdinat);
                                        $mysql = sprintf("SELECT `Nama`, SUM(`nilai_ideal`) AS `ideal_value`, SUM(`nilai_aktual`) AS `actual_value`, SUM(`nilai_aktual`) / SUM(`nilai_ideal`) * 100 AS `average`, SUM(REPLACE(`nilai_akhir_ideal`, ',', '.')) AS `ideal_final_value`, SUM(REPLACE(`nilai_akhir_aktual`, ',', '.')) AS `actual_final_value`, SUM(REPLACE(`nilai_akhir_ideal`, ',', '.')) / SUM(REPLACE(`nilai_akhir_aktual`, ',', '.')) * 100 AS `average_resource`, `periode_so`, `kpi_status` FROM `sa_user` WHERE %s `kpi_status` = %s AND `Nama` LIKE %s GROUP BY `Nama`; ",
                                                GetSQLValueString($periode_sox, "defined", "`periode_so` = " . $periode_sox . " AND ", ""),
                                                GetSQLValueString($kpi_statusx, "text"),
                                                GetSQLValueString("%" . $emailz[1] . "%", "text")
                                        );
                                        $dtResources = $DBKPI->get_sql($mysql);
                                        if($dtResources[2]>0)
                                        {
                                            do {
                                                $exp1 = explode("<", $dtResources[0]['Nama']);
                                                $email = explode(">", $exp1[1]);
                                                $img = "<img class='img-profile' src='data:image/jpeg;base64, " .  base64_encode($DBHCM->get_photo($email[0])) . "' style='padding:2px; border:1px solid #e6e6e6; width:30px; height:40px;' title='" . $exp1[0] . "' />";
                                                $email = $DBHCM->split_email($dtResources[0]['Nama']);
                                                $emailx = $email[2];
                                                    ?>
                                                <tr>
                                                    <td><?php echo $emailx; ?></td>
                                                    <td class="text-nowrap"><?php echo $img . "&nbsp;" . $emailx; ?></td>
                                                    <td><?php echo number_format($dtResources[0]['ideal_value'], 2); ?></td>
                                                    <td><?php echo number_format($dtResources[0]['actual_value'], 2); ?></td>
                                                    <td><?php echo number_format($dtResources[0]['average'], 2); ?></td>
                                                    <td><?php echo number_format($dtResources[0]['ideal_final_value'], 2); ?></td>
                                                    <td><?php echo number_format($dtResources[0]['actual_final_value'], 2); ?></td>
                                                    <td><?php echo number_format($dtResources[0]['average_resource'], 2); ?></td>
                                                    <td><?php echo $periode_sox; ?></td>
                                                </tr>
                                                <?php

                                                $userEmail2 = $email[0];
                                                $subOrdinats2 = $DBHCM->get_leader_v2($userEmail2);
                                                if(COUNT($subOrdinats2[2])>0 && $subOrdinats2[2][0]!="None")
                                                {
                                                    foreach($subOrdinats2[2] as $subOrdinat2)
                                                    {
                                                        $emailz2 = $DBHCM->split_email($subOrdinat2);
                                                        $mysql2 = sprintf("SELECT `Nama`, SUM(`nilai_ideal`) AS `ideal_value`, SUM(`nilai_aktual`) AS `actual_value`, SUM(`nilai_aktual`) / SUM(`nilai_ideal`) * 100 AS `average`, SUM(REPLACE(`nilai_akhir_ideal`, ',', '.')) AS `ideal_final_value`, SUM(REPLACE(`nilai_akhir_aktual`, ',', '.')) AS `actual_final_value`, SUM(REPLACE(`nilai_akhir_ideal`, ',', '.')) / SUM(REPLACE(`nilai_akhir_aktual`, ',', '.')) * 100 AS `average_resource`, `periode_so`, `kpi_status` FROM `sa_user` WHERE %s `kpi_status` = %s AND `Nama` LIKE %s GROUP BY `Nama`; ",
                                                                GetSQLValueString($periode_sox, "defined", "`periode_so` = " . $periode_sox . " AND ", ""),
                                                                GetSQLValueString($kpi_statusx, "text"),
                                                                GetSQLValueString("%" . $emailz2[1] . "%", "text")
                                                        );
                                                        $dtResources2 = $DBKPI->get_sql($mysql2);
                                                        if($dtResources2[2]>0)
                                                        {
                                                            do {
                                                                $exp12 = explode("<", $dtResources2[0]['Nama']);
                                                                $email2 = explode(">", $exp12[1]);
                                                                $img2 = "<img class='img-profile' src='data:image/jpeg;base64, " .  base64_encode($DBHCM->get_photo($email2[0])) . "' style='padding:2px; border:1px solid #e6e6e6; width:30px; height:40px;' title='" . $exp12[0] . "' />";
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $dtResources2[0]['Nama']; ?></td>
                                                                    <td class="text-nowrap">- <?php echo $img2 . "&nbsp;" . $dtResources2[0]['Nama']; ?></td>
                                                                    <td><?php echo number_format($dtResources2[0]['ideal_value'], 2); ?></td>
                                                                    <td><?php echo number_format($dtResources2[0]['actual_value'], 2); ?></td>
                                                                    <td><?php echo number_format($dtResources2[0]['average'], 2); ?></td>
                                                                    <td><?php echo number_format($dtResources2[0]['ideal_final_value'], 2); ?></td>
                                                                    <td><?php echo number_format($dtResources2[0]['actual_final_value'], 2); ?></td>
                                                                    <td><?php echo number_format($dtResources2[0]['average_resource'], 2); ?></td>
                                                                    <td><?php echo $periode_sox; ?></td>
                                                                </tr>
                                                                <?php

                                                                $userEmail3 = $emailz2[1];
                                                                $subOrdinats3 = $DBHCM->get_leader_v2($userEmail3);
                                                                if(COUNT($subOrdinats3[2])>0 && $subOrdinats3[2][0]!="None")
                                                                {
                                                                    foreach($subOrdinats3[2] as $subOrdinat3)
                                                                    {
                                                                        $emailz3 = $DBHCM->split_email($subOrdinat3);
                                                                        $mysql3 = sprintf("SELECT `Nama`, SUM(`nilai_ideal`) AS `ideal_value`, SUM(`nilai_aktual`) AS `actual_value`, SUM(`nilai_aktual`) / SUM(`nilai_ideal`) * 100 AS `average`, SUM(REPLACE(`nilai_akhir_ideal`, ',', '.')) AS `ideal_final_value`, SUM(REPLACE(`nilai_akhir_aktual`, ',', '.')) AS `actual_final_value`, SUM(REPLACE(`nilai_akhir_ideal`, ',', '.')) / SUM(REPLACE(`nilai_akhir_aktual`, ',', '.')) * 100 AS `average_resource`, `periode_so`, `kpi_status` FROM `sa_user` WHERE %s `kpi_status` = %s AND `Nama` LIKE %s GROUP BY `Nama`; ",
                                                                                GetSQLValueString($periode_sox, "defined", "`periode_so` = " . $periode_sox . " AND ", ""),
                                                                                GetSQLValueString($kpi_statusx, "text"),
                                                                                GetSQLValueString("%" . $emailz3[1] . "%", "text")
                                                                        );
                                                                        $dtResources3 = $DBKPI->get_sql($mysql3);
                                                                        if($dtResources3[2]>0)
                                                                        {
                                                                            do {
                                                                                $exp13 = explode("<", $dtResources3[0]['Nama']);
                                                                                $email3 = explode(">", $exp13[1]);
                                                                                $img3 = "<img class='img-profile' src='data:image/jpeg;base64, " .  base64_encode($DBHCM->get_photo($email3[0])) . "' style='padding:2px; border:1px solid #e6e6e6; width:30px; height:40px;' title='" . $exp13[0] . "' />";
                                                                                ?>
                                                                                <tr>
                                                                                    <td><?php echo $dtResources3[0]['Nama']; ?></td>
                                                                                    <td class="text-nowrap">- <?php echo $img3 . "&nbsp;" . $dtResources3[0]['Nama']; ?></td>
                                                                                    <td><?php echo number_format($dtResources3[0]['ideal_value'], 2); ?></td>
                                                                                    <td><?php echo number_format($dtResources3[0]['actual_value'], 2); ?></td>
                                                                                    <td><?php echo number_format($dtResources3[0]['average'], 2); ?></td>
                                                                                    <td><?php echo number_format($dtResources3[0]['ideal_final_value'], 2); ?></td>
                                                                                    <td><?php echo number_format($dtResources3[0]['actual_final_value'], 2); ?></td>
                                                                                    <td><?php echo number_format($dtResources3[0]['average_resource'], 2); ?></td>
                                                                                    <td><?php echo $periode_sox; ?></td>
                                                                                </tr>
                                                                                <?php
                                                                            } while($dtResources3[0]=$dtResources3[1]->fetch_assoc());
                                                                        } else
                                                                        {
                                                                            ?>
                                                                            <!-- <tr><td colspan="8">No data to displayed.</td></tr> -->
                                                                            <?php
                                                                        }
                                                                    }
                                                                }



                                                            } while($dtResources2[0]=$dtResources2[1]->fetch_assoc());
                                                        } else
                                                        {
                                                            ?>
                                                            <!-- <tr><td colspan="8">No data to displayed.</td></tr> -->
                                                            <?php
                                                        }
                                                    }
                                                }


                                            } while($dtResources[0]=$dtResources[1]->fetch_assoc());
                                        } else
                                        {
                                            ?>
                                            <!-- <tr><td colspan="8">No data to displayed.</td></tr> -->
                                            <?php
                                        }
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

<div class="row mb-3">
    <div class="col-lg-12">
        <div class="card shadow">
            <?php module_version("KPI_PROJECT"); ?>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
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
        colorSet: "greenShades",
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
        colorSet: "greenShades",
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

<!-- Modal -->
<div class="modal fade" id="mdlHelp" tabindex="-1" aria-labelledby="mdlHelp" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Help</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="modal-text"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
const exampleModal = document.getElementById('mdlHelp')
exampleModal.addEventListener('show.bs.modal', event => {
  // Button that triggered the modal
  const button = event.relatedTarget
  // Extract info from data-bs-* attributes
  const recipient = button.getAttribute('data-bs-whatever')
  if(recipient=='Cost')
  {
    helpTitle = 'Cost : Resource Utilization'
    helpText = "<p>Cost Resource Utilization (RU) parameter :</p>"
    helpText = helpText+"<ol>"
    helpText = helpText+"<li>Normal : RU less than 110%</li>"
    helpText = helpText+"<li>Minor : RU 110% - 150%</li>"
    helpText = helpText+"<li>Major : RU 150% - 200%</li>"
    helpText = helpText+"<li>Critical : RU over than 200%</li>"
    helpText = helpText+"<li>None : task not update</li>"
    helpText = helpText+"</ol>"
  } else if(recipient=='Time')
  {
    helpTitle = 'Time'
    helpText = "<p>Time parameter :</p>"
    helpText = helpText+"<ol>"
    helpText = helpText+"<li>Normal : BAST delay less than 1 month</li>"
    helpText = helpText+"<li>Minor : BAST delay 1 - 3 months</li>"
    helpText = helpText+"<li>Major : BAST delay 4 - 6 months</li>"
    helpText = helpText+"<li>Critical : BAST delay over than 6 month</li>"
    helpText = helpText+"<li>None : BAST not available</li>"
    helpText = helpText+"</ol>"
  } else
  {
    helpTitle = 'KPI Resource'
    helpText = "<p>KPI Resource (Individual) parameter :</p>"
    helpText = helpText+"<ol>"
    helpText = helpText+"<li>Poor : KPI Project Average Value 0% - 60%</li>"
    helpText = helpText+"<li>Good : KPI Project Average Value 60% - 75%</li>"
    helpText = helpText+"<li>Very Good : KPI Project Average Value 75% - 90%</li>"
    helpText = helpText+"<li>Excellence : KPI Project Average Value 90% - 100%</li>"
    helpText = helpText+"</ol>"
  }
  // If necessary, you could initiate an AJAX request here
  // and then do the updating in a callback.
  //
  // Update the modal's content.
  const modalTitle = exampleModal.querySelector('.modal-title')
  const modalBodyInput = exampleModal.querySelector('.modal-body .modal-text')

  modalTitle.textContent = `${helpTitle}`
  modalBodyInput.innerHTML = `${helpText}`
})
</script>

<script>
function loadpage()
{
    window.location.href="index.php?"+document.getElementById('onload').value;
}
</script>
