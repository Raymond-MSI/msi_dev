<style>
    tr.group,
    tr.group:hover {
        background-color: rgba(0, 0, 0, 0.15) !important;
        font-weight: bold;
    }

    :root.dark tr.group,
    :root.dark tr.group:hover {
        background-color: rgba(0, 0, 0, 0.75) !important;
    }
</style>

<script>
    $(document).ready(function() {
        var groupColumn = 0;
        var tableUnApproved = $('#TaskList').DataTable({
            dom: 'Bfrtip',
            buttons: [{
                extend: 'excelHtml5',
                text: "<i class='fa fa-file-excel'></i>",
                title: 'Edo_' + <?php echo date("YmdGis"); ?>
            }, ],
            "columnDefs": [{
                    visible: false,
                    targets: groupColumn
                },
                {
                    "targets": [2,3,4,5,6,7,8,9,15,16,17], //[2, 3, 4, 5, 6, 7, 8, 9, 15, 16, 17],
                    "visible": false
                    // 0 : Resource Name
                    // 1 : Project Information
                    // 2 : Project Type
                    // 3 : Project Code
                    // 4 : SO Number
                    // 5 : Order Number
                    // 6 : Project Name
                    // 7 : Customer Name
                    // 8 : Plan Start Date
                    // 9 : Plan End Date
                    // 10 : Work Date
                    // 11 : Actual
                    // 12 : Push Date</th>
                    // 13 : Update Date</th>
                    // 14 : Sync Date</th>
                    // 15 : Task Name</th>
                    // 16 : Rule</th>
                    // 17 : Weekend</th>
                    // 18 : Plan</th>
                    // 19 : Actual</th>
                    // 20 : Status</th>
                },
                {
                    "target": [12, 13, 14],
                    "className": 'dt-body-right'
                },
                {
                    "target": [18, 19],
                    "className": 'dt-body-center'
                }
            ],
            order: [
                [groupColumn, 'asc'],
                [1, 'asc']
            ],
            displayLength: 10,
            drawCallback: function(settings) {
                var api = this.api();
                var rows = api.rows({
                    page: 'current'
                }).nodes();
                var last = null;

                api.column(groupColumn, {
                        page: 'current'
                    })
                    .data()
                    .each(function(group, i) {
                        if (last !== group) {
                            $(rows)
                                .eq(i)
                                .before(
                                    '<tr class="group"><td colspan="18">' +
                                    group +
                                    '</td></tr>'
                                );

                            last = group;
                        }
                    });
            }
        });
        // Order by the grouping
        $('#example tbody').on('click', 'tr.group', function() {
            var currentOrder = table.order()[0];
            if (currentOrder[0] === groupColumn && currentOrder[1] === 'asc') {
                table.order([groupColumn, 'desc']).draw();
            } else {
                table.order([groupColumn, 'asc']).draw();
            }
        });
    });
</script>

<?php 
$TotalbyType['Maintenance']['Accepted'] = 0;
$TotalbyType['Maintenance']['Declined'] = 0;
$TotalbyType['Maintenance']['needsAction'] = 0;
$TotalbyType['Implementation']['Accepted'] = 0;
$TotalbyType['Implementation']['Declined'] = 0;
$TotalbyType['Implementation']['needsAction'] = 0;
$TotalbyType['Non-Project']['Accepted'] = 0;
$TotalbyType['Non-Project']['Declined'] = 0;
$TotalbyType['Non-Project']['needsAction'] = 0;
$TotalbyType['Self Improvement']['Accepted'] = 0;
$TotalbyType['Self Improvement']['Declined'] = 0;
$TotalbyType['Self Improvement']['needsAction'] = 0;
$TotalbyCategory['High'] = 0;
$TotalbyCategory['Medium'] = 0;
$TotalbyCategory['Standard'] = 0;
$dataTaskImp = array();
$dataTimeImp = array();
$dataTimeImp1 = array();
$dataTaskMnt = array();
$dataTimeMnt = array();
$dataTaskNon = array();
$dataTimeNon = array();
$dataTaskSelf = array();
$dataTimeSelf = array();
$dataTimeHigh = array();
$dataTimeMedium = array();
$dataTimeStandard = array();
$dataTimeWorkHours = array();
$dataTimeWorkHoursLess1 = array();
$dataTimeWorkHoursLess2 = array();
$dataTimeConsistence = array();
$dataTimeLess = array();
$dataTaskHigh = array();
$dataTaskMedium = array();
$dataTaskStandard = array();
$categoryA1 = array();
$categoryA2 = array();
$categoryB1 = array();
$categoryB2 = array();
$categoryC1 = array();
$categoryC2 = array();
$categoryD = array();
$Resources = array();

function empName($employeeName)
{
	if(strlen($employeeName)>20)
	{
		$xxx = explode(" ", $employeeName);
		$resTemp = "";
		$resourceName = "";
		foreach($xxx as $zzz)
		{
			$resTemp .= $zzz . " ";
			if(strlen($resTemp)<=20)
			{
				$resourceName .= $zzz . " ";
			} else
			{
				$resourceName .= substr($zzz,0,1) . ".";
			}
		}
	} else
	{
		$resourceName = $employeeName;
	}
	return $resourceName;
}

function array_msort($array, $cols, $array1 = array(), $array2 = array(), $array3 = array())
{
    $ret = array();
    $ret0 = array();
    $ret1 = array();
    $ret2 = array();
    $ret3 = array();
    $colarr = array();
    $colarr1 = array();
    $colarr2 = array();
    $colarr3 = array();

    // Colarr
    foreach ($cols as $col => $order) {
        $colarr[$col] = array();
        foreach ($array as $k0 => $row) { $colarr[$col]['_'.$k0] = str_replace(",",".",strtolower($row[$col])); }
    }
    $eval = 'array_multisort(';
    foreach ($cols as $col => $order) {
        $eval .= '$colarr[\''.$col.'\'],'.$order.',';
    }
    $eval = substr($eval,0,-1).');';
    eval($eval);
    // Colarr1
    foreach ($cols as $col => $order) {
        $colarr1[$col] = array();
        foreach ($array1 as $k => $row) { $colarr1[$col]['_'.$k] = str_replace(",",".",strtolower($row[$col])); }
    }
    $eval = 'array_multisort(';
    foreach ($cols as $col => $order) {
        $eval .= '$colarr1[\''.$col.'\'],'.$order.',';
    }
    $eval = substr($eval,0,-1).');';
    eval($eval);
    // Colarr2
    foreach ($cols as $col => $order) {
        $colarr2[$col] = array();
        foreach ($array2 as $k => $row) { $colarr2[$col]['_'.$k] = str_replace(",",".",strtolower($row[$col])); }
    }
    $eval = 'array_multisort(';
    foreach ($cols as $col => $order) {
        $eval .= '$colarr2[\''.$col.'\'],'.$order.',';
    }
    $eval = substr($eval,0,-1).');';
    eval($eval);
    // Colarr3
    foreach ($cols as $col => $order) {
        $colarr3[$col] = array();
        foreach ($array3 as $k => $row) { $colarr3[$col]['_'.$k] = str_replace(",",".",strtolower($row[$col])); }
    }
    $eval = 'array_multisort(';
    foreach ($cols as $col => $order) {
        $eval .= '$colarr3[\''.$col.'\'],'.$order.',';
    }
    $eval = substr($eval,0,-1).');';
    eval($eval);


    $ret0 = array();
    $ret = array();
    $ret1 = array();
    $ret2 = array();
    $ret3 = array();
    foreach($colarr as $col => $arr) {
        foreach ($arr as $k0 => $v) {
            $k0 = substr($k0,1);
            if (!isset($ret0[$k0])) {
                $ret0[$k0] = $array[$k0];
                $xxx = array("label" => ucwords($colarr['label']['_'.$k0]), "y" => $colarr['y']['_'.$k0]);
                array_push($ret, $xxx);
                if($array1 != null) {
                    $xxx = array("label" => ucwords($colarr1['label']['_'.$k0]), "y" => $colarr1['y']['_'.$k0]);
                    array_push($ret1, $xxx);
                }
                if($array2 != null) {
                    $xxx = array("label" => ucwords($colarr2['label']['_'.$k0]), "y" => $colarr2['y']['_'.$k0]);
                    array_push($ret2, $xxx);
                }
                if($array3 != null) {
                    $xxx = array("label" => ucwords($colarr3['label']['_'.$k0]), "y" => $colarr3['y']['_'.$k0]);
                    array_push($ret3, $xxx);
                }
            } else {
                $ret0[$k0][$col] = $array[$k0][$col];
            }
        }
    }
    return array($ret, $ret1, $ret2, $ret3);
}


$DBHCM = get_conn("HCM");
// $DBPV = get_conn("PRODUCTIVITY");
$DBGCAL = get_conn("GOOGLE_CALENDAR");

$showProgress = 1;
if(isset($_COOKIE['showProgress']))
{
    $showProgress = $_COOKIE['showProgress'];
}
$showDaysSummary = 1;
if(isset($_COOKIE['showDaysSummary']))
{
    $showDaysSummary = $_COOKIE['showDaysSummary'];
}
$showLegend = 1;
if(isset($_COOKIE['showLegend']))
{
    $showLegend = $_COOKIE['showLegend'];
}
$showChart = 1;
if(isset($_COOKIE['showChart']))
{
    $showChart = $_COOKIE['showChart'];
}
$showResourcesSummary = 1;
if(isset($_COOKIE['showResourcesSummary']))
{
    $showResourcesSummary = $_COOKIE['showResourcesSummary'];
}
$showTaskList = 1;
if(isset($_COOKIE['showTaskLisk']))
{
    $showTaskList = $_COOKIE['showTaskLisk'];
}

$periode = "";
if(isset($_GET['periode']))
{
    $periode = $_GET['periode'];
}

if($periode == "")
{
    $start = date("d-M-Y", strtotime("-6 day"));
    $end = date("d-M-Y");
    if(isset($_GET['start']) && isset($_GET['end']))
    {
        $start = $_GET['start'];
        $end = $_GET['end'];
    }
} else
if($periode == "HP7")
{
    $start = date("d-M-Y");
    $end = date("d-M-Y", strtotime("+6 day"));
} else
{
    $start = date("d-M-Y", strtotime("-6 day"));
    $end = date("d-M-Y");
}

$diffDate = date_diff(date_create(date("Y-m-d", strtotime($start))), date_create(date("Y-m-d", strtotime($end . " 1 day"))));
$diffDate = $diffDate->format("%R%a");
$workDays = number_of_working_days($start, $end);
$workHours = $workDays*6;

$category = "";
$categoryx = "(`project_type` = 'MSI Project Implementation' OR `project_type` = 'MSI Project Maintenance' OR `project_type` = 'MSI Non-Project' OR `project_type` = 'MSI Self Improvement')";
$categoryList = "Implementation, Maintenance, Non-Project, Self-Improvement";
$cat = null;
if(isset($_GET['category']) && $_GET['category'] != "null")
{
    $cat = (int)$_GET['category'];
} else
{
    $cat = 15; // All category
}
$categoryx = ""; 
$sambung = "";
$sambung1 = "";
$categoryList = "";
if(($cat & 8) == 8)
{
    $categoryx .= "`project_type` = 'MSI Project Implementation'"; 
    $sambung = " OR ";
    $categoryList .= "Implementation";
    $sambung1 = ", ";
}
if(($cat & 4) == 4)
{
    $categoryx .= $sambung . "`project_type` = 'MSI Project Maintenance'"; 
    $sambung = " OR ";
    $categoryList .= $sambung1 . "Maintenance";
    $sambung1 = ", ";
}
if(($cat & 2) == 2)
{
    $categoryx .= $sambung . "`project_type` = 'MSI Non-Project'";
    $sambung = " OR ";
    $categoryList .= $sambung1 . "Non-Project";
    $sambung1 = ", ";
}
if(($cat & 1) == 1)
{
    $categoryx .= $sambung . "`project_type` = 'MSI Self Improvement'"; 
    $sambung = " OR ";
    $categoryList .= $sambung1 . "Self-Improvement";
}
if($categoryx!="")
{
    $category = " AND (" . $categoryx . ") ";
}
?>

<!-- Top Bar -->
<div class="hstack gap-3 border mb-3">
    <div class="p-2">
        <?php 
        include("components/modules/dashboard/func_dashboard.php");
        menu_dashboard(); 
        ?>
    </div>
    <div class="p-2">

    </div>
    <div class="p-2 ms-auto">&nbsp;</div>
    <div class="p-2">
        <button class="btn btn-secondary btn-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop" aria-controls="staticBackdrop"><i class="fa-solid fa-filter"></i></button>
        <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#staticSetup" aria-controls="staticSetup"><i class="fa-solid fa-gear"></i></button>
    </div>
</div>


<?php
$group = "P4";
if(isset($_GET['group']) && $_GET['group']!="null")
{
	$group = $_GET['group'];
}

if($_SESSION["Microservices_UserLevel"] == "Administrator" || $_SESSION["Microservices_UserLevel"] == "Super Admin" || $_SESSION["Microservices_UserLevel"] == "User Admin" || $_SESSION["Microservices_UserLevel"] == "Manager" || $_SESSION["Microservices_UserLevel"] == "Project Manager" || $_SESSION["Microservices_UserLevel"] == "Project Coordinator")
{
    $group = "`sa_mst_organization_project`.`structure_code`= '" . $group . "'"; // Default group
    $resourceEmail = "";
    $resourceEmailX = "All"; 
    if(isset($_GET['resource']) && ($_GET['resource']!="null" && $_GET['resource']!="All"))
    {
        $resourceEmail = " AND employee_email LIKE '%" . $_GET["resource"] . "%'";
        $resourceEmailX = $DBHCM->get_name($_GET["resource"]);
    }
} else if($_SESSION["Microservices_UserLevel"] == "Users")
{
    $group = "";
    $resourceEmail = "employee_email LIKE '%" . $_SESSION["Microservices_UserEmail"]. "%'";
    $resourceEmailX = $_SESSION["Microservices_UserName"];
}
// Query Resources
$mysql = sprintf("SELECT `sa_mst_organization_project`.`structure_code`,
`sa_mst_employees`.`employee_name`, 
`sa_mst_organization_project_employee`.`employee_email`
FROM `sa_mst_organization_project_employee` 
LEFT JOIN `sa_mst_employees` ON `sa_mst_employees`.`email` = `sa_mst_organization_project_employee`.`employee_email`
LEFT JOIN `sa_mst_organization_project` ON `sa_mst_organization_project`.`structure_id` = `sa_mst_organization_project_employee`.`structure_id`
WHERE %s %s 
ORDER BY `sa_mst_employees`.`employee_name` ASC",
GetSQLValueString($group, "defined", $group, $group),
GetSQLValueString($resourceEmail, "defined", $resourceEmail, $resourceEmail)
);

$rsResources = $DBHCM->get_sql($mysql);
$group = $rsResources[0]['structure_code'];
$i = 0;
$Org = " AND `resource_email` = null";
$ResourceList = "AND `resource_email` = null";
$ResourceList2 = "";
$ResourceList3 = "";
$Totalresources = $rsResources[2];
if($rsResources[2]>0)
{
    $Org = " AND `resource_email` IN (";
    $ResourceList = " AND `resource_email` IN (";
    $ResourceList2 = " AND `attendees_email` IN (";
    $ResourceList3 = " `attendees_email` IN (";
    do {
        if($i==0)
        {
            $Org .= "'" . addslashes($rsResources[0]['employee_name']) . "<" . $rsResources[0]['employee_email'] . ">'";
            $ResourceList .= "'" . $rsResources[0]['employee_email'] . "'";
            $ResourceList2 .= "'" . $rsResources[0]['employee_email'] . "'";
            $ResourceList3 .= "'" . $rsResources[0]['employee_email'] . "'";
        } else
        {
            $Org .= ", '" . addslashes($rsResources[0]['employee_name']) . "<" . $rsResources[0]['employee_email'] . ">'";
            $ResourceList .= ", '" . $rsResources[0]['employee_email'] . "'";
            $ResourceList2 .= ", '" . $rsResources[0]['employee_email'] . "'";
            $ResourceList3 .= ", '" . $rsResources[0]['employee_email'] . "'";
        }
        array_push($Resources, addslashes($rsResources[0]['employee_name']) . "<" . $rsResources[0]['employee_email'] . ">");
        $taskPlan[$rsResources[0]['employee_email']] = 0;
        $timePlan[$rsResources[0]['employee_email']] = 0;
        $taskActual[$rsResources[0]['employee_email']] = 0;
        $taskDeclined[$rsResources[0]['employee_email']] = 0;
        $taskneedsAction[$rsResources[0]['employee_email']] = 0;
        $timeActual[$rsResources[0]['employee_email']] = 0;
        $timeActualType[$rsResources[0]['employee_email']]['Implementation'] = 0;
        $timeActualType[$rsResources[0]['employee_email']]['Maintenance'] = 0;
        $timeActualType[$rsResources[0]['employee_email']]['Non-Project'] = 0;
        $timeActualType[$rsResources[0]['employee_email']]['Self-Improvement'] = 0;
        $timeActualType[$rsResources[0]['employee_email']]['High'] = 0;
        $timeActualType[$rsResources[0]['employee_email']]['Medium'] = 0;
        $timeActualType[$rsResources[0]['employee_email']]['Standard'] = 0;
        $timeActualType[$rsResources[0]['employee_email']]['Gold'] = 0;
        $timeActualType[$rsResources[0]['employee_email']]['Silver'] = 0;
        $timeActualType[$rsResources[0]['employee_email']]['Bronze'] = 0;
        $timeActualType[$rsResources[0]['employee_email']]['WorkHours'] = 0;
        $timeActualType[$rsResources[0]['employee_email']]['TimeLess'] = 0;
        $timeActualType[$rsResources[0]['employee_email']]['Consistence1'] = 0;
        $timeActualType[$rsResources[0]['employee_email']]['Consistence2'] = 0;
        $timeActualWorkDay[$rsResources[0]['employee_email']] = 0;
        $taskUnplan[$rsResources[0]['employee_email']] = 0;
        $timeUnplan[$rsResources[0]['employee_email']] = 0;
        $taskPlanWeek[$rsResources[0]['employee_email']] = 0;
        $i++;
    } while($rsResources[0]=$rsResources[1]->fetch_assoc());
    $Org .= ")";
    $ResourceList .= ")";
    $ResourceList2 .= ")";
    $ResourceList3 .= ")";
    // }

    // Query Pre-Schedule
    $mysql = "SELECT `project_code`, `task_name`, `task_category`, `resource_role`, `catalogue_code`, `start_date`, `due_date`, `work_date`, `created_at`, `duration_date_minute`, `resource_email`, `resource_role`, `permalink`, `google_event_id`, `google_event_id`, `status_task` FROM `sa_preschedule_testing_webhook` WHERE `work_date` >= '" . date("Y-m-d", strtotime($start)) . "' AND `work_date` < '" . date("Y-m-d", strtotime($end . " 1 day")) . "'" . $ResourceList . " ORDER BY `resource_email` ASC, `work_date` ASC";
    $rsPreSchedules = $DBGCAL->get_sql($mysql);
    $dataTotalPlanTask = $rsPreSchedules[2];
    $dataTotalPlanHours = 0;
    $taskCategoryHigh = 0;
    $taskCategoryMedium = 0;
    $taskCategoryStandard = 0;
    $INSchedule = "";
    $sambung = "";
    if($rsPreSchedules[2]>0)
    { 
        do {
            $dataIDDate[$rsPreSchedules[0]['google_event_id']]['work_date'] = $rsPreSchedules[0]['work_date'];
            $dataIDDate[$rsPreSchedules[0]['google_event_id']]['duration'] = $rsPreSchedules[0]['duration_date_minute']/60;
            if(isset($taskPlan[$rsPreSchedules[0]['resource_email']]))
            {
                $taskPlan[$rsPreSchedules[0]['resource_email']]++;
            }
            if(!isset($dataPreSchedulesHours[$rsPreSchedules[0]['work_date']][$rsPreSchedules[0]['resource_email']]['total_hours']))
            {
                $dataPreSchedulesHours[$rsPreSchedules[0]['work_date']][$rsPreSchedules[0]['resource_email']]['total_hours'] = $rsPreSchedules[0]['duration_date_minute']/60;
                $dataPreSchedulesTask[$rsPreSchedules[0]['work_date']][$rsPreSchedules[0]['resource_email']]['total_task'] = 1;
            } else
            {
                $dataPreSchedulesHours[$rsPreSchedules[0]['work_date']][$rsPreSchedules[0]['resource_email']]['total_hours'] += $rsPreSchedules[0]['duration_date_minute']/60;
                $dataPreSchedulesTask[$rsPreSchedules[0]['work_date']][$rsPreSchedules[0]['resource_email']]['total_task']++;
            }
            if(!isset($dataPreSchedulesTaskWeek[$rsPreSchedules[0]['resource_email']][$rsPreSchedules[0]['work_date']]))
            {
                $dataPreSchedulesTaskWeek[$rsPreSchedules[0]['resource_email']] = 1;
            }
            $INSchedule .= $sambung . "'" . $rsPreSchedules[0]['google_event_id'] . "'";
            $sambung = ", ";

            $xxx1['project_code'] = $rsPreSchedules[0]['project_code'];
            $xxx1['task_name'] = $rsPreSchedules[0]['task_name'];
            $xxx1['task_category'] = $rsPreSchedules[0]['task_category'];
            $xxx1['resource_role'] = $rsPreSchedules[0]['resource_role'];
            $xxx1['start_date'] = $rsPreSchedules[0]['start_date'];
            $xxx1['due_date'] = $rsPreSchedules[0]['due_date'];
            $xxx1['work_date'] = $rsPreSchedules[0]['work_date'];
            $xxx1['created_at'] = $rsPreSchedules[0]['created_at'];
            $xxx1['event_id'] = $rsPreSchedules[0]['google_event_id'];
            $xxx1['duration'] = $rsPreSchedules[0]['duration_date_minute'];
            $xxx1['status_task'] = $rsPreSchedules[0]['status_task'];
            if($rsPreSchedules[0]['status_task'] == "actived") {
                $PreSchedules[$rsPreSchedules[0]['resource_email']][] = $xxx1;
            } else {
                $PreSchedulesUnactived[$rsPreSchedules[0]['resource_email']][] = $xxx1;
            }
        } while($rsPreSchedules[0]=$rsPreSchedules[1]->fetch_assoc());
    }
    
    // Query Schedule Plan
    $INSchedule = $INSchedule!="" ? $INSchedule : "'None'";

    $mysql = "SELECT `event_id`, `summary`, `description`, `html_link`, `created_event`, `updated_event`, `start_time`, `end_time`, `diff_time_minutes`, `attendees_email`, `response_status`, `project_type`, `timestamp`, `status_task` FROM `sa_schedule_testing_webhook` WHERE `event_id` IN (" . $INSchedule . ") " . $category . $ResourceList2 . ";";
    $rsSchedules = $DBGCAL->get_sql($mysql);

    // $dataSchedules = [];
    $dataTotalActualTask = 0;
    $dataTotalActualHours = 0;
    if($rsSchedules[2]>0)
    {
        $qwerty = 1;
        do {
            $diffScheduleActual = 0;
            if($rsSchedules[0]['response_status'] != "declined")
            {
                $diff = $rsSchedules[0]['diff_time_minutes'];
                $diffScheduleActual = $diff/60; // convert to hours
            }
            $tgl = $dataIDDate[$rsSchedules[0]['event_id']]['work_date'];
            $PlanHours = $dataIDDate[$rsSchedules[0]['event_id']]['duration'];
            if(isset($taskActual[$rsSchedules[0]['attendees_email']]) && $rsSchedules[0]['response_status'] == "accepted")
            {
                $taskActual[$rsSchedules[0]['attendees_email']]++;
                $timeActual[$rsSchedules[0]['attendees_email']] += $diffScheduleActual;

                // add 20250312
                // Prepare chart Resources Productivity Summary
                if($rsSchedules[0]['project_type'] == "MSI Project Implementation") {
                    $TotalbyType['Implementation']['Accepted'] += $diffScheduleActual;
                    $timeActualType[$rsSchedules[0]['attendees_email']]['Implementation'] += $diffScheduleActual;
                }
                if($rsSchedules[0]['project_type'] == "MSI Project Maintenance") {
                    $TotalbyType['Maintenance']['Accepted'] += $diffScheduleActual;
                    $timeActualType[$rsSchedules[0]['attendees_email']]['Maintenance'] += $diffScheduleActual;
                }

                $timeActualType[$rsSchedules[0]['attendees_email']]['Consistence1']++;


                $wday = date("w", strtotime("$tgl $i day"));
                if(isset($timeActualWorkDay[$rsSchedules[0]['attendees_email']]) && $wday > 0 && $wday < 6)
                {
                    $timeActualWorkDay[$rsSchedules[0]['attendees_email']] += $diffScheduleActual;
                }
            } else
            if(isset($taskDeclined[$rsSchedules[0]['attendees_email']]) && $rsSchedules[0]['response_status'] == "declined")
            {
                $taskDeclined[$rsSchedules[0]['attendees_email']]++;
                // add 20250312
                // Prepare chart Resources Productivity Summary
                if($rsSchedules[0]['project_type'] == "MSI Project Implementation") {
                    $TotalbyType['Implementation']['Declined'] += $diffScheduleActual;
                }
                if($rsSchedules[0]['project_type'] == "MSI Project Maintenance") {
                    $TotalbyType['Maintenance']['Declined'] += $diffScheduleActual;
                }
                $timeActualType[$rsSchedules[0]['attendees_email']]['Consistence1']++;
            } else
            if(isset($taskneedsAction[$rsSchedules[0]['attendees_email']]) && $rsSchedules[0]['response_status'] == "needsAction")
            {
                $taskneedsAction[$rsSchedules[0]['attendees_email']]++;
                // add 20250312
                // Prepare chart Resources Productivity Summary
                if($rsSchedules[0]['project_type'] == "MSI Project Implementation") {
                    $TotalbyType['Implementation']['needsAction'] += $diffScheduleActual;
                }
                if($rsSchedules[0]['project_type'] == "MSI Project Maintenance") {
                    $TotalbyType['Maintenance']['needsAction'] += $diffScheduleActual;
                }
                $timeActualType[$rsSchedules[0]['attendees_email']]['Consistence2']++;
            } else {
                $timeActualType[$rsSchedules[0]['attendees_email']]['Consistence2']++;
            }
            if(!isset($dataSchedulesHours[date("Y-m-d", strtotime($tgl))][$rsSchedules[0]['attendees_email']]['total_hours']))
            {
                $dataSchedulesHours[date("Y-m-d", strtotime($tgl))][$rsSchedules[0]['attendees_email']]['total_hours'] = $diffScheduleActual;
                $dataSchedulesTask[date("Y-m-d", strtotime($tgl))][$rsSchedules[0]['attendees_email']]['total_task'] = 1;
                if($rsSchedules[0]['response_status'] == "accepted")
                {
                    $dataSchedulesTask[date("Y-m-d", strtotime($tgl))][$rsSchedules[0]['attendees_email']]['accepted_task'] = 1;
                    $dataSchedulesTask[date("Y-m-d", strtotime($tgl))][$rsSchedules[0]['attendees_email']]['needAction_task'] = 0;
                    $dataSchedulesTask[date("Y-m-d", strtotime($tgl))][$rsSchedules[0]['attendees_email']]['declined_task'] = 0;
                    $dataSchedulesHours[date("Y-m-d", strtotime($tgl))][$rsSchedules[0]['attendees_email']]['accepted_hours'] = $diffScheduleActual;
                    $dataSchedulesHours[date("Y-m-d", strtotime($tgl))][$rsSchedules[0]['attendees_email']]['needAction_hours'] = 0;
                    $dataSchedulesHours[date("Y-m-d", strtotime($tgl))][$rsSchedules[0]['attendees_email']]['declined_hours'] = 0;
                    $dataSchedulesHours[date("Y-m-d", strtotime($tgl))][$rsSchedules[0]['attendees_email']]['plan_hours'] = $PlanHours;
                    if(isset($timePlan[$rsSchedules[0]['attendees_email']]))
                    {
                        $timePlan[$rsSchedules[0]['attendees_email']] += $PlanHours;
                    }
                    $dataTotalActualTask++;
                } else
                if($rsSchedules[0]['response_status'] == "needsAction")
                {
                    $dataSchedulesTask[date("Y-m-d", strtotime($tgl))][$rsSchedules[0]['attendees_email']]['accepted_task'] = 0;
                    $dataSchedulesTask[date("Y-m-d", strtotime($tgl))][$rsSchedules[0]['attendees_email']]['needAction_task'] = 1;
                    $dataSchedulesTask[date("Y-m-d", strtotime($tgl))][$rsSchedules[0]['attendees_email']]['declined_task'] = 0;
                    $dataSchedulesHours[date("Y-m-d", strtotime($tgl))][$rsSchedules[0]['attendees_email']]['accepted_hours'] = 0;
                    $dataSchedulesHours[date("Y-m-d", strtotime($tgl))][$rsSchedules[0]['attendees_email']]['needAction_hours'] = $diffScheduleActual;
                    $dataSchedulesHours[date("Y-m-d", strtotime($tgl))][$rsSchedules[0]['attendees_email']]['declined_hours'] = 0;
                    $dataSchedulesHours[date("Y-m-d", strtotime($tgl))][$rsSchedules[0]['attendees_email']]['plan_hours'] = $PlanHours;
                    $timePlan[$rsSchedules[0]['attendees_email']] += $PlanHours;
                } else
                {
                    $dataSchedulesTask[date("Y-m-d", strtotime($tgl))][$rsSchedules[0]['attendees_email']]['accepted_task'] = 0;
                    $dataSchedulesTask[date("Y-m-d", strtotime($tgl))][$rsSchedules[0]['attendees_email']]['needAction_task'] = 0;
                    $dataSchedulesTask[date("Y-m-d", strtotime($tgl))][$rsSchedules[0]['attendees_email']]['declined_task'] = 1;
                    $dataSchedulesHours[date("Y-m-d", strtotime($tgl))][$rsSchedules[0]['attendees_email']]['accepted_hours'] = 0;
                    $dataSchedulesHours[date("Y-m-d", strtotime($tgl))][$rsSchedules[0]['attendees_email']]['needAction_hours'] = 0;
                    $dataSchedulesHours[date("Y-m-d", strtotime($tgl))][$rsSchedules[0]['attendees_email']]['declined_hours'] = $diffScheduleActual;
                    $dataSchedulesHours[date("Y-m-d", strtotime($tgl))][$rsSchedules[0]['attendees_email']]['plan_hours'] = 0;
                    $dataTotalActualTask++;
                }
            } else
            {
                $dataSchedulesHours[date("Y-m-d", strtotime($tgl))][$rsSchedules[0]['attendees_email']]['total_hours'] += $diffScheduleActual;
                $dataSchedulesTask[date("Y-m-d", strtotime($tgl))][$rsSchedules[0]['attendees_email']]['total_task']++;
                if($rsSchedules[0]['response_status'] == "accepted")
                {
                    $dataSchedulesTask[date("Y-m-d", strtotime($tgl))][$rsSchedules[0]['attendees_email']]['accepted_task']++;
                    $dataSchedulesHours[date("Y-m-d", strtotime($tgl))][$rsSchedules[0]['attendees_email']]['accepted_hours'] += $diffScheduleActual;
                    $dataSchedulesHours[date("Y-m-d", strtotime($tgl))][$rsSchedules[0]['attendees_email']]['plan_hours'] += $PlanHours;
                    if(isset($timePlan[$rsSchedules[0]['attendees_email']]))
                    {
                        $timePlan[$rsSchedules[0]['attendees_email']] += $PlanHours;
                    }
                    $dataTotalActualTask++;
                } else
                if($rsSchedules[0]['response_status'] == "needsAction")
                {
                    $dataSchedulesTask[date("Y-m-d", strtotime($tgl))][$rsSchedules[0]['attendees_email']]['needAction_task']++;
                    $dataSchedulesHours[date("Y-m-d", strtotime($tgl))][$rsSchedules[0]['attendees_email']]['needAction_hours'] += $diffScheduleActual;
                    $dataSchedulesHours[date("Y-m-d", strtotime($tgl))][$rsSchedules[0]['attendees_email']]['plan_hours'] += $PlanHours;
                    $timePlan[$rsSchedules[0]['attendees_email']] += $PlanHours;
                } else
                {
                    $dataSchedulesTask[date("Y-m-d", strtotime($tgl))][$rsSchedules[0]['attendees_email']]['declined_task']++;
                    $dataSchedulesHours[date("Y-m-d", strtotime($tgl))][$rsSchedules[0]['attendees_email']]['declined_hours'] += $diffScheduleActual;
                    $dataTotalActualTask++;
                }
            }

            $xxx1 = [];
            $xxx1['project_type'] = $rsSchedules[0]['project_type'];
            $xxx1['start_time'] = $rsSchedules[0]['start_time'];
            $xxx1['end_time'] = $rsSchedules[0]['end_time'];
            $xxx1['diff_time_minutes'] = $rsSchedules[0]['diff_time_minutes'];
            $xxx1['updated_event'] = $rsSchedules[0]['updated_event'];
            $xxx1['sync_date'] = $rsSchedules[0]['timestamp'];
            $xxx1['status'] = $rsSchedules[0]['response_status'];
            if($rsSchedules[0]['status_task'] == "actived") {
                $Schedules[$rsSchedules[0]['attendees_email']][$rsSchedules[0]['event_id']][] = $xxx1;
            } else {
                $SchedulesUnactived[$rsSchedules[0]['attendees_email']][$rsSchedules[0]['event_id']][] = $xxx1;
            }

            $qwerty++;
        } while($rsSchedules[0]=$rsSchedules[1]->fetch_assoc());
    }

    $dataUnplan = [];
    $dataTotalUnplanTask = 0;
    $dataTotalUnplanHours = 0;

    if(($cat & 1) == 1 || ($cat & 2) == 2 )
        {
        // Query Schedule Unplan
        $mysql = "SELECT `event_id`, `summary`, `description`, `html_link`, `created_event`, `updated_event`, `start_time`, `end_time`, `diff_time_minutes`, `attendees_email`, `response_status`, `project_type`, `timestamp` FROM `sa_schedule_testing_webhook` WHERE `start_time` >= '" . date("Y-m-d", strtotime($start)) . "' AND `start_time` < '" . date("Y-m-d", strtotime($end . " 1 day")) . "' AND (`project_type` = 'MSI Non-Project' OR `project_type` = 'MSI Self Improvement')" . $ResourceList2 . " AND `response_status` = 'accepted';";
        $rsUnplan = $DBGCAL->get_sql($mysql);
        $dataTotalUnplanTask = $rsUnplan[2];

        if($rsUnplan[2]>0)
        {
            $xxx1 = [];
            do {
                $diffUnplanActual = 0;
                if($rsUnplan[0]['response_status'] != "declined")
                {
                    $diff = $rsUnplan[0]['diff_time_minutes'];
                    $diffUnplanActual = $diff/60;
                }
                $dataUnplan[$rsUnplan[0]['attendees_email']][] = [
                    "event_id" => $rsUnplan[0]['event_id'],
                    "task_name" => $rsUnplan[0]['summary'],
                    "permalink_calendar" => $rsUnplan[0]['html_link'],
                    "start_time" => $rsUnplan[0]['start_time'],
                    "end_time" => $rsUnplan[0]['end_time'],
                    "duration_actual" => $diffUnplanActual,
                    "response_status" => $rsUnplan[0]['response_status'],
                    "project_type" => $rsUnplan[0]['project_type']
                ];
                $dataTotalUnplanHours += $diffUnplanActual;
                if(isset($taskUnplan[$rsUnplan[0]['attendees_email']]) && $rsUnplan[0]['start_time'] <= date("Y-m-d", strtotime("1 day")) && $rsUnplan[0]['response_status'] == "accepted")
                {
                    $taskUnplan[$rsUnplan[0]['attendees_email']]++;
                    $timeUnplan[$rsUnplan[0]['attendees_email']] += $diffUnplanActual;
                }
                if(!isset($dataUnplanTask[date("Y-m-d", strtotime($rsUnplan[0]['start_time']))][$rsUnplan[0]['attendees_email']]['total_task']))
                {
                    if($rsUnplan[0]['response_status'] == "accepted")
                    {
                        $dataUnplanHours[date("Y-m-d", strtotime($rsUnplan[0]['start_time']))][$rsUnplan[0]['attendees_email']]['total_hours'] = $diffUnplanActual;
                        $dataUnplanTask[date("Y-m-d", strtotime($rsUnplan[0]['start_time']))][$rsUnplan[0]['attendees_email']]['total_task'] = 1;
                    } else
                    {
                        $dataUnplanHours[date("Y-m-d", strtotime($rsUnplan[0]['start_time']))][$rsUnplan[0]['attendees_email']]['total_hours'] = 0;
                        $dataUnplanTask[date("Y-m-d", strtotime($rsUnplan[0]['start_time']))][$rsUnplan[0]['attendees_email']]['total_task'] = 0;
                    }
                } else
                {
                    if($rsUnplan[0]['response_status'] == "accepted")
                    {
                        $dataUnplanHours[date("Y-m-d", strtotime($rsUnplan[0]['start_time']))][$rsUnplan[0]['attendees_email']]['total_hours'] += $diffUnplanActual;
                        $dataUnplanTask[date("Y-m-d", strtotime($rsUnplan[0]['start_time']))][$rsUnplan[0]['attendees_email']]['total_task']++;
                    }
                }

                if($rsUnplan[0]['project_type'] == "MSI Non-Project") {
                    $xxx = "Non-Project";
                } else
                if($rsUnplan[0]['project_type'] == "MSI Self Improvement") {
                    $xxx = "Self-Improvement";
                }
                $timeActualType[$rsUnplan[0]['attendees_email']][$xxx] += $diffUnplanActual;

                $xxx1['project_type'] = $rsUnplan[0]['project_type'];
                $xxx1['subject'] = $rsUnplan[0]['summary'];
                $xxx1['start_time'] = $rsUnplan[0]['start_time'];
                $xxx1['end_time'] = $rsUnplan[0]['end_time'];
                $xxx1['diff_time_minutes'] = $rsUnplan[0]['diff_time_minutes'];
                $xxx1['updated_event'] = $rsUnplan[0]['updated_event'];
                $xxx1['sync_date'] = $rsUnplan[0]['timestamp'];
                $xxx1['status'] = $rsUnplan[0]['response_status'];
                $xxx1['status_task'] = $rsUnplan[0]['status_task'];
                if($rsSchedules[0]['status_task'] == "actived") {
                    $Schedules[$rsUnplan[0]['attendees_email']][$rsUnplan[0]['event_id']][$rsUnplan[0]['project_type']] = $xxx1;
                } else {
                    $SchedulesUnactived[$rsUnplan[0]['attendees_email']][$rsUnplan[0]['event_id']][$rsUnplan[0]['project_type']] = $xxx1;
                }
            } while($rsUnplan[0]=$rsUnplan[1]->fetch_assoc());
        }
    }
    ?>

    <div class="card mb-3">
        <div class="card-header fw-bold">Resource Capacity</div>
        <div class="card-body">

            <div class="hstack mb-5">
                <!-- Filtering -->
                <div class="col-lg-5">
                    <table class="table table-sm">
                        <tr>
                            <td class="fs-3" class="fs-3" colspan="2">Filtering</td>
                        </tr>
                        <tr class="bg-light">
                            <td>Description</td>
                            <td>Value</td>
                        </tr>
                        <tr>
                            <td><b>Group</b></td><td class="pl-3"><?php echo $group; ?></td>
                        </tr>
                        <tr>
                            <td><b>Periode</b></td><td class="pl-3"><?php echo $start . " to " . $end; ?> <?php echo "(" . $diffDate . " days and " . $workDays . " workdays)"; ?></td>
                        </tr>
                        <tr>
                            <td><b>Project Type</b></td><td class="pl-3"><?php echo $categoryList != "" ? $categoryList : "All"; ?></td>
                        </tr>
                        <tr>
                            <td><b>Resource Name</b></td><td class="pl-3"><?php echo $resourceEmailX; ?></td>
                        </tr>
                    </table>
                </div>
                <!-- End Filtering -->

                <!-- General Summary -->
                <div class="col-lg-4">
                    <table class="table table-sm">
                        <tr>
                            <td class="fs-3" colspan="4">General Summary</td>
                        </tr>
                        <tr class="bg-light">
                            <td class="fw-bold">Description</td>
                            <td class="pl-3 text-right fw-bold">Plan</td>
                            <td class="pl-3 text-right fw-bold">Actual</td>
                            <td class="pl-3 text-right fw-bold">Percentage</td>
                        </tr>
                        <tr>
                            <td><b>Total Task Plan</b></td>
                            <td class="pl-3 text-right"><?php echo $dataTotalPlanTask; ?></td>
                            <td class="pl-3 text-right"><?php echo $dataTotalActualTask; ?></td>
                            <td class="pl-3 text-right"><?php echo $dataTotalPlanTask > 0 ? number_format($dataTotalActualTask/$dataTotalPlanTask*100, 2) : 0; ?>%</td>
                        </tr>
                        <tr>
                            <td><b>Total Hours Plan</b></td>
                            <td class="pl-3 text-right"><div id="dataTotalPlanHours"></div></td>
                            <td class="pl-3 text-right"><div id="dataTotalActualHours"></div></td>
                            <td class="pl-3 text-right"><div id="dataTotalActualProgress"></div></td>
                        </tr>
                        <tr>
                            <td><b>Total Task Unplan</b></td>
                            <td class="pl-3 text-right"></td>
                            <td class="pl-3 text-right"><?php echo $dataTotalUnplanTask; ?></td>
                            <td class="pl-3 text-right"></td>
                        </tr>
                        <tr>
                            <td><b>Total Hours Unplan</b></td>
                            <td class="pl-3 text-right"></td>
                            <td class="pl-3 text-right"><?php echo number_format($dataTotalUnplanHours, 2); ?></td>
                            <td class="pl-3 text-right"></td>
                        </tr>
                    </table>
                </div>
                <!-- End General Summary -->

                <div class="col-lg-3">
                    <?php // Feature ?>
                </div>
            </div>

            <!-- Show Legend -->
            <div id="showLegend">
                <div class="hstack gap-3 border-top border-bottom">
                    <div class="p-2">
                        <div class="fs-3">Legend</div>
                    </div>
                    <div class="p-2">
                    </div>
                    <div class="p-2 ms-auto">&nbsp;</div>
                    <div class="p-2">
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-lg-4">
                        <div><span class="fw-bold">Days Summary :</span></div>
                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col"><i class="fa-solid fa-circle-dot"></i> Plan : Assignment plan. Plan hours without declined</div>
                        </div>
                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col"><i class='fa-solid fa-circle-check text-success'></i> Accepted : The assignment accepted</div>
                        </div>
                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col"><i class='fa-solid fa-circle-exclamation text-warning'></i> needsAction : The assignment not responded to yet</div>
                        </div>
                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col"><i class='fa-solid fa-circle-xmark text-danger'></i> Declined : The assignment declined</div>
                        </div>
                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col"><i class="fa-solid fa-circle-arrow-down text-danger"></i> Idle (Less than an hour) : Working hours less than an hour</div>
                        </div>
                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col"><i class="fa-solid fa-circle-right text-success"></i> Midle (Between 1 - 4 hours) : Working hours between 1 - 4 hours</div>
                        </div>
                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col"><i class="fa-solid fa-circle-arrow-up text-primary"></i> Full (More than 4 hours) : Working hours above 4 hours</div>
                        </div>
                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col"><i class="fa-regular fa-star text-success"></i> Star : Number of actual task completed 100%</div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div><span class="fw-bold">% Task</span> : Percentage of accepted and declined task against plan task</div>
                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col"><i class="fa-regular fa-star text-success"></i> : Number of actual task completed 100%</div>
                        </div>
                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col"><i class="fa-solid fa-check text-primary"></i> : Greater than or equal 75%</div>
                        </div>
                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col"><i class="fa-solid fa-arrow-up text-success"></i> : Between 50% - 75%</div>
                        </div>
                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col"><i class="fa-solid fa-arrow-down text-danger"></i> : Less than 50%</div>
                        </div>
                        
                        <div><span class="fw-bold">% Hours</span> : Percentage of accepted hours against plan hours</div>
                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col"><i class="fa-solid fa-arrow-up text-primary"></i> : Greater than 100%</div>
                        </div>
                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col"><i class="fa-regular fa-star text-success"></i> : Number of actual hours completed 100%</div>
                        </div>
                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col"><i class="fa-solid fa-check text-success"></i> : Between 75% - 100%</div>
                        </div>
                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col"><i class="fa-solid fa-arrow-down text-danger"></i> : Less than 75%</div>
                        </div>
                        
                        <div><span class="fw-bold">% Declined</span> : Percentage of declined task against plan task</div>
                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col"><i class="fa-regular fa-star text-success"></i> : No assignment rejected</div>
                        </div>
                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col"><i class="fa-solid fa-check text-primary"></i> : Less than 25%</div>
                        </div>
                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col"><i class="fa-solid fa-arrow-right text-success"></i> : Between 25% - 50%</div>
                        </div>
                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col"><i class="fa-solid fa-arrow-down text-danger"></i> : Greater than 50%</div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div><span class="fw-bold">Accepted Hours #1</span> : Number of hours a weekday</div>
                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col"><i class="fa-solid fa-arrow-up text-primary"></i> : Greater than 45 hours</div>
                        </div>
                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col"><i class="fa-solid fa-check text-success"></i> : Between 30 - 45 hours</div>
                        </div>
                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col"><i class="fa-solid fa-arrow-down text-danger"></i> : Less than 30 hours</div>
                        </div>

                        <div><span class="fw-bold">Accepted Hours #2</span> : Number of hours a week</div>
                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col"><i class="fa-solid fa-arrow-up text-primary"></i> : Greater than 63 hours</div>
                        </div>
                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col"><i class="fa-solid fa-check text-success"></i> : Between 42 - 63 hours</div>
                        </div>
                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col"><i class="fa-solid fa-arrow-down text-danger"></i> : Less than 42 hours</div>
                        </div>

                        <div><span class="fw-bold">Idle Days</span> : Number of work days no assignment</div>
                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col"><i class="fa-regular fa-star text-success"></i> : Assignment fulfilled for all working days</div>
                        </div>
                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col"><i class="fa-solid fa-arrow-right text-success"></i> : Between 1 - 2 days</div>
                        </div>
                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col"><i class="fa-solid fa-arrow-down text-danger"></i> : Greater than 2 days</div>
                        </div>

                        <div><span class="fw-bold">Weekend</span> : Number of weekend assignment</div>
                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col"><i class="fa-solid fa-arrow-down text-danger"></i> : Using more than half of the total on weekends</div>
                        </div>
                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col"><i class="fa-solid fa-arrow-right text-success"></i> : Using half or less of the total on weekends</div>
                        </div>
                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col"><i class="fa-regular fa-star text-success"></i> : No assignments on weekends</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Show Legend -->

            <!-- Show Chart -->
             <?php
             $ChartHeight = 400;
            //  if($Totalresources > 10) {
            //     $ChartHeight = $Totalresources * 30;
            //  }
             ?>
            <div id="showChart">
                <div class="hstack gap-3 border-top border-bottom ps-0">
                    <div class="p-2">
                        <div class="fs-3">Chart</div>
                    </div>
                    <div class="p-2">
                    </div>
                    <div class="p-2 ms-auto">&nbsp;</div>
                    <div class="p-2">
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-sm-6">
                        <div id="chartContainer0" style="width: 100%;"></div>
                        <div style="min-height:<?php echo $ChartHeight; ?> px"></div>
                    </div>
                    <div class="col-sm-6">
                        <div id="chartContainer1" style="width: 100%;"></div>
                        <div style="min-height:<?php echo $ChartHeight; ?>px"></div>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-sm-4">
                        <div id="chartContainer2" style="width: 100%;"></div>
                        <div style="min-height:<?php echo $ChartHeight; ?>px"></div>
                    </div>
                    <div class="col-sm-4">
                        <div id="chartContainer3" style="width: 100%;"></div>
                        <div style="min-height:<?php echo $ChartHeight; ?>px"></div>
                    </div>
                    <div class="col-sm-4">
                        <div id="chartContainer4" style="width: 100%;"></div>
                        <div style="min-height:<?php echo $ChartHeight; ?>px"></div>
                    </div>
                </div>
            </div>
            <!-- End Shoe Chart -->

            <!-- Resources Summary -->
            <div class="row mt-3 ps-3 pe-3 mb-5" id="showResourcesSummary">
                <div class="hstack gap-3 border-top ps-0">
                    <div class="p-2">
                        <div class="fs-3">Resources Summary</div>
                    </div>
                    <div class="p-2">
                    </div>
                    <div class="p-2 ms-auto">&nbsp;</div>
                    <div class="p-2">
                    </div>
                </div>
                <div class="table-responsive ps-0 pe-0">
                <table class="table mb-0" width="100%">
                        <thead class="bg-danger-subtle text-danger-emphasis">
                            <tr>
                                <th class="text-left">Resources</th>
                                <th>Plan Task</th>
                                <th id="showHours10">Plan Hours</th>
                                <th>Accepted Task</th>
                                <th id="showHours20">Accepted Hours</th>
                                <th>needsAction Task</th>
                                <th>Declined Task</th>
                                <th id="showProgress10">% Task</th>
                                <th id="showProgress20">% Hours</th>
                                <th id="showProgress30">% Declined</th>
                                <th>Idle Days</th>
                                <th>Weekend</th>
                                <th>Unplan Task</th>
                                <th id="showHours30">Unplan Hours</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $j = 1; 
                            foreach($Resources as $rsc) { 
                                ?>
                                <tr class="text-right">
                                    <td class="text-left text-nowrap">
                                        <?php
                                        $xxx = $DBHCM->split_email($rsc);
                                        $emailx = $xxx[1];
                                        $tgl = date("Y-m-d", strtotime($start));
                                        $tglend = date("Y-m-d", strtotime($end));
                                        $nameExp = explode(" ", $xxx[0]);
                                        $i =0;
                                        $ShortName = "";
                                        foreach($nameExp as $nikname)
                                        {
                                            if($i < 3)
                                            {
                                                $ShortName .= $nikname . " ";
                                            } else
                                            {
                                                $FirstLetter = substr($nikname, 0, 1);
                                                $ShortName .= $FirstLetter;
                                            }
                                            $i++;
                                        }
                                        if($i > 3)
                                        {
                                            $ShortName .= ".";
                                        }
                                        ?>
                                        <span role="button" onclick="ShowModal('<?php echo $tgl; ?>', '<?php echo $emailx; ?>', '<?php echo $tglend; ?>');" >
                                            <?php echo $ShortName; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php
                                        $res = explode("<", $rsc);
                                        $res1 = explode(">", $res[1]);
                                        $xxx = str_replace("@", ".", $res1[0]);
                                        $xxx = str_replace(".", "", $xxx);
                                        if(isset($taskPlan[$res1[0]]))
                                        {
                                            echo $taskPlan[$res1[0]];
                                        } else
                                        {
                                            echo number_format(0, 2);
                                        }
                                        ?>
                                    </td>
                                    <td id="showHours4<?php echo $j; ?>">
                                        <?php
                                        if(isset($taskPlan[$res1[0]]))
                                        {
                                            if($taskPlan[$res1[0]]>0)
                                            {
                                                echo number_format($timePlan[$res1[0]], 2);
                                                $avgrTime = $timePlan[$res1[0]]/$taskPlan[$res1[0]];
                                            } else
                                            {
                                                echo number_format($timePlan[$res1[0]], 2);
                                                $avgrTime = 0;
                                            }
                                            $dataTotalPlanHours += $timePlan[$res1[0]];
                                        } else
                                        {
                                            echo number_format(0, 2);
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if(isset($taskActual[$res1[0]]))
                                        {
                                            echo "<div class='col text-end'>".$taskActual[$res1[0]]."</div>";
                                        } else
                                        {
                                            echo number_format(0, 2);
                                        }
                                        ?>
                                    </td>
                                    <td id="showHours5<?php echo $j; ?>">
                                        <div class="row">
                                            <?php
                                                $xxx = 0;
                                                $yyy = 0;
                                                if(isset($taskActual[$res1[0]]))
                                                {
                                                    $yyy = $timeActual[$res1[0]] / $diffDate;
                                                    $xxx = $timeActualWorkDay[$res1[0]] / $workDays;
                                                    $dataTotalActualHours += $timeActual[$res1[0]];
                                                    echo "<div class='col text-end'>".number_format($timeActual[$res1[0]], 2)."</div>";
                                                } else
                                                {
                                                    echo "<div class='col text-end'>".number_format(0, 2)."</div>";
                                                }
                                                if($xxx > 9)
                                                {
                                                    echo '<div class="col-auto ps-0 text-primary"><i class="fa-solid fa-arrow-up"></i></div>';
                                                } else
                                                if($xxx >= 6)
                                                {
                                                    echo '<div class="col-auto ps-0 text-success"><i class="fa-solid fa-check"></i></div>';
                                                } else
                                                {
                                                    echo '<div class="col-auto ps-0 text-danger"><i class="fa-solid fa-arrow-down"></i></div>';
                                                }
                                                if($yyy > 9)
                                                {
                                                    echo '<div class="col-auto ps-0 text-primary"><i class="fa-solid fa-arrow-up"></i></div>';
                                                } else
                                                if($yyy >= 6)
                                                {
                                                    echo '<div class="col-auto ps-0 text-success"><i class="fa-solid fa-check"></i></div>';
                                                } else
                                                {
                                                    echo '<div class="col-auto ps-0 text-danger"><i class="fa-solid fa-arrow-down"></i></div>';
                                                }
                                            ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php
                                        if(isset($taskneedsAction[$res1[0]]))
                                        {
                                            $xxx = $taskPlan[$res1[0]]-$taskActual[$res1[0]]-$taskDeclined[$res1[0]];
                                            echo "<div class='col text-end'>". $xxx . "</div>";
                                        } else
                                        {
                                            echo number_format(0, 2);
                                        }
                                        ?>
                                    </td>
                                    <td>
                                    <?php
                                        if(isset($taskDeclined[$res1[0]]))
                                        {
                                            echo "<div class='col text-end'>".$taskDeclined[$res1[0]]."</div>";
                                        } else
                                        {
                                            echo number_format(0, 2);
                                        }
                                        ?>
                                    </td>
                                    <td id="showProgress4<?php echo $j; ?>">
                                        <div class="row">
                                            <?php 
                                            $xxxm= 0;
                                            if(isset($taskActual[$res1[0]]))
                                            {
                                                $xxx = ($taskActual[$res1[0]]+$taskDeclined[$res1[0]])/($taskPlan[$res1[0]]>0 ? $taskPlan[$res1[0]] :1)*100;
                                                echo "<div class='col text-end'>".number_format($xxx,2) . "%</div>";
                                            } else
                                            {
                                                echo "<div class='col text-end'>".number_format(0, 2)."%</div>";
                                            }
                                            if($xxx == 100)
                                            {
                                                echo '<div class="col-auto ps-0 text-primary"><i class="fa-regular fa-star text-success"></i></div>';
                                            } else
                                            if($xxx >= 75)
                                            {
                                                echo '<div class="col-auto ps-0 text-primary"><i class="fa-solid fa-check"></i></div>';
                                            } else
                                            if($xxx >= 50)
                                            {
                                                echo '<div class="col-auto ps-0 text-success"><i class="fa-solid fa-arrow-up"></i></div>';
                                            } else
                                            {
                                                echo '<div class="col-auto ps-0 text-danger"><i class="fa-solid fa-arrow-down"></i></div>';
                                            }
                                            ?>
                                        </div>
                                    </td>
                                    <td id="showProgress5<?php echo $j; ?>">
                                        <div class="row">
                                            <?php
                                            $xxx = 0;
                                            if($timePlan[$res1[0]]>0)
                                            {
                                                $xxx = $timeActual[$res1[0]]/$timePlan[$res1[0]]*100;
                                                echo "<div class='col text-end'>".number_format($xxx, 2) . "%</div>";
                                            } else
                                            {
                                                echo "<div class='col text-end'>".number_format(0, 2)."%</div>";
                                            }
                                            if($xxx > 100)
                                            {
                                                echo '<div class="col-auto ps-0 text-primary"><i class="fa-solid fa-arrow-up"></i></div>';
                                            } else
                                            if($xxx == 100)
                                            {
                                                echo '<div class="col-auto ps-0 text-primary"><i class="fa-regular fa-star text-success"></i></div>';
                                            } else
                                            if($xxx >= 75)
                                            {
                                                echo '<div class="col-auto ps-0 text-success"><i class="fa-solid fa-check"></i></div>';
                                            } else
                                            {
                                                echo '<div class="col-auto ps-0 text-danger"><i class="fa-solid fa-arrow-down"></i></div>';
                                            }
                                            ?>
                                        </div>
                                    </td>
                                    <td id="showProgress6<?php echo $j; ?>">
                                        <div class="row">
                                            <?php
                                            $ProgressDeclined = 0;
                                            if($timePlan[$res1[0]]>0)
                                            {
                                                $ProgressDeclined = $taskDeclined[$res1[0]]/$taskPlan[$res1[0]]*100;
                                                echo "<div class='col text-end'>".number_format($taskDeclined[$res1[0]]/$taskPlan[$res1[0]]*100,2)."%</div>";
                                            } else
                                            {
                                                echo "<div class='col text-end'>".number_format(0,2)."%</div>";
                                            }
                                            if($ProgressDeclined >= 50)
                                            {
                                                echo '<div class="col-auto ps-0 text-danger"><i class="fa-solid fa-arrow-down"></i></div>';
                                            } else
                                            if($ProgressDeclined >= 25)
                                            {
                                                echo '<div class="col-auto ps-0 text-primary"><i class="fa-solid fa-arrow-right"></i></div>';
                                            } else
                                            if($ProgressDeclined > 0)
                                            {
                                                echo '<div class="col-auto ps-0 text-success"><i class="fa-solid fa-check"></i></div>';
                                            } else
                                            {
                                                echo '<div class="col-auto ps-0 text-success"><i class="fa-regular fa-star"></i></div>';
                                            }
                                            ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div id="IdleTask_<?php echo $res1[0]; ?>"></div>
                                    </td>
                                    <td >
                                        <div id="dataWeekend<?php echo $res1[0]; ?>"></div>
                                    </td>
                                    <td>
                                        <?php
                                        if(isset($taskUnplan[$res1[0]]))
                                        {
                                            echo $taskUnplan[$res1[0]];
                                        } else
                                        {
                                            echo number_format(0, 2);
                                        }
                                        ?>
                                    </td>
                                    <td id="showHours6<?php echo $j; ?>">
                                    <?php
                                        if(isset($taskUnplan[$res1[0]]))
                                        {
                                            echo number_format($timeUnplan[$res1[0]], 2);
                                        } else
                                        {
                                            echo number_format(0, 2);
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php 
                                $j++;

                                
                                // Chart
                                $xxx = array("title" => "Time Implementation", "label" => $ShortName, "y" => $timeActualType[$emailx]['Implementation']);
                                array_push($dataTimeImp, $xxx);
                                $xxx = array("title" => "Time Maintenance", "label" => $ShortName, "y" => $timeActualType[$emailx]['Maintenance']);
                                array_push($dataTimeMnt, $xxx);
                                $xxx = array("title" => "Time Non-Project", "label" => $ShortName, "y" => $timeActualType[$emailx]['Non-Project']);
                                array_push($dataTimeNon, $xxx);
                                $xxx = array("title" => "Time Self-Improvement", "label" => $ShortName, "y" => $timeActualType[$emailx]['Self-Improvement']);
                                array_push($dataTimeSelf, $xxx);



                                $TotalWorkHours = $timeActual[$res1[0]] + $timeUnplan[$res1[0]];
                                if($TotalWorkHours < $workHours)
                                {
                                    $xxx = array("label" => $ShortName, "y" => $TotalWorkHours);
                                    array_push($dataTimeWorkHours, $xxx);
                                }

                                $xx1 = $timeActualType[$emailx]['Implementation']*1 + $timeActualType[$emailx]['Maintenance']*1;
                                $xx2 = $timeActualType[$emailx]['Non-Project'] + $timeActualType[$emailx]['Self-Improvement'];
                                if($xx1 < $xx2) {
                                    $xxx = array("label" => $ShortName, "y" => $xx1);
                                    array_push($dataTimeWorkHoursLess1, $xxx);
                                    $xxx = array("label" => $ShortName, "y" => $xx2);
                                    array_push($dataTimeWorkHoursLess2, $xxx);
                                }

                                if(($timeActualType[$emailx]['Consistence1']*1 + $timeActualType[$emailx]['Consistence2']*1) == 0) {
                                    $xx1 = 0;
                                } else {
                                    $xx1 = $timeActualType[$emailx]['Consistence1']*1 / ($timeActualType[$emailx]['Consistence1']*1 + $timeActualType[$emailx]['Consistence2']*1)*100;
                                }
                                if($xx1 < 80) {
                                    $xxx = array("label" => $ShortName, "y" => $xx1);
                                    array_push($dataTimeConsistence, $xxx);
                                }
                            } 
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php
            ?>

            <?php
            $dataTimeImpx = $dataTimeImp;
            $dataTimeMntx = $dataTimeMnt;
            $dataTimeNonx = $dataTimeNon;
            $dataTimeSelfx =$dataTimeSelf;
            $msort = array_msort($dataTimeImp, array('y'=>SORT_ASC, 'label'=>SORT_ASC), $dataTimeMnt, $dataTimeNon, $dataTimeSelf);
            $dataTimeImp = $msort[0];
            $dataTimeMnt = $msort[1];
            $dataTimeNon = $msort[2];
            $dataTimeSelf = $msort[3];

            $dataTimeWorkHoursx = $dataTimeWorkHours;
            $msort = array_msort($dataTimeWorkHours, array('y'=>SORT_ASC, 'label'=>SORT_ASC));
            $dataTimeWorkHours = $msort[0];

            $dataTimeConsistencex = $dataTimeConsistence;
            $msort = array_msort($dataTimeConsistence, array('y'=>SORT_ASC, 'label'=>SORT_ASC));
            $dataTimeConsistence = $msort[0];

            $dataTimeWorkHoursLess1x = $dataTimeWorkHoursLess1;
            $dataTimeWorkHoursLess2x = $dataTimeWorkHoursLess2;
            $msort = array_msort($dataTimeWorkHoursLess1, array('y'=>SORT_ASC, 'label'=>SORT_ASC), $dataTimeWorkHoursLess2);
            $dataTimeWorkHoursLess1 = $msort[0];
            $dataTimeWorkHoursLess2 = $msort[1];
            ?>

            <script>document.getElementById("dataTotalPlanHours").innerHTML = format(<?php echo $dataTotalPlanHours; ?>);</script>
            <script>document.getElementById("dataTotalActualHours").innerHTML = format(<?php echo $dataTotalActualHours; ?>);</script>
            <script>document.getElementById("dataTotalActualProgress").innerHTML = format(<?php echo $dataTotalPlanHours>0 ? $dataTotalActualHours/$dataTotalPlanHours*100 : 0; ?>)+"%";
            </script>
            <!-- End Resources Summary -->

            <!-- Row Task -->
            <div class="row mt-3 ps-3 pe-3 mb-5" id="showTaskList">
                <div class="hstack gap-3 border-top border-bottom">
                    <div class="p-2">
                        <div class="fs-3">Task List</div>
                    </div>
                    <div class="p-2">
                    </div>
                    <div class="p-2 ms-auto">&nbsp;</div>
                    <div class="p-2">
                        <div class="fs-3"><span class="fs-5"><?php //echo date("F Y", strtotime($start)); ?></span></div>
                    </div>
                </div>
                <div class="table-responsive ps-0 pe-0">
                    <table id="TaskList" class="display compact" style="width:100%">
                        <thead class="bg-danger-subtle text-danger-emphasis">
                            <tr>
                                <th rowspan="2">Resource Name</th>
                                <th rowspan="2">Project Information</th>
                                <th rowspan="2">Project Type</th>
                                <th rowspan="2">Project Code</th>
                                <th rowspan="2">SO Number</th>
                                <th rowspan="2">Order Number</th>
                                <th rowspan="2">Project Name</th>
                                <th rowspan="2">Customer Name</th>
                                <th colspan="2">Plan Date</th>
                                <th colspan="2" class="text-center">Schedule Date</th>
                                <th rowspan="2" class="text-center">Push Date</th>
                                <th rowspan="2" class="text-center">Update Date</th>
                                <th rowspan="2" class="text-center">Sync Date</th>
                                <th rowspan="2">Task Name</th>
                                <th rowspan="2">Rule</th>
                                <th rowspan="2">Weekend</th>
                                <th colspan="2" class="text-center">Cost (Hours)</th>
                                <th rowspan="2" class="text-center">Status</th>
                            </tr>
                            <tr>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th class="text-center">Plan</th>
                                <th class="text-center">Actual</th>
                                <th class="text-center">Plan</th>
                                <th class="text-center">Actual</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            foreach($Resources as $rsc) { 
                                $xx1 = explode("<", $rsc);
                                $rscName = $xx1[0];
                                $xx2 = explode(">", $xx1[1]);
                                $email = $xx2[0];

                                if(isset($PreSchedules[$email])) {
                                    foreach($PreSchedules[$email] as $dt) {
                                        if($dt['status_task'] == "actived") {
                                            $fontColor = "";
                                            if(isset($Schedules[$email][$dt['event_id']])) {
                                                foreach($Schedules[$email][$dt['event_id']] as $event) {
                                                    if($event['status'] == "accepted") {
                                                        $fontColor = "text-success-emphasis bg-success-subtle border-bottom border-success-subtle";
                                                    } else
                                                    if($event['status'] == "declined") {
                                                        $fontColor = "text-warning-emphasis bg-warning-subtle border-bottom border-warning-subtle";
                                                    }
                                                }
                                            }
                                            if(isset($Schedules[$email][$dt['event_id']])) {
                                                foreach($Schedules[$email][$dt['event_id']] as $event) {
                                                    if($event['status'] == "accepted") {
                                                        if($dt['task_category'] == "High") {
                                                            $timeActualType[$email]["High"]++;
                                                        } else
                                                        if($dt['task_category'] == "Medium") {
                                                            $timeActualType[$email]["Medium"]++;
                                                        } else
                                                        if($dt['task_category'] == "Standard") {
                                                            $timeActualType[$email]["Standard"]++;
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
                                            <tr class="">
                                                <td><?php echo $rsc; // Resource Name ?></td>
                                                <td>
                                                    <?php 
                                                    // Project Information
                                                    if(isset($Schedules[$email][$dt['event_id']])) {
                                                        foreach($Schedules[$email][$dt['event_id']] as $event) {
                                                            if($event['project_type'] == "MSI Project Implementation")
                                                            {
                                                                $xxx = "Implementation";
                                                            } else 
                                                            if($event['project_type'] == "MSI Project Maintenance")
                                                            {
                                                                $xxx = "Maintenance";
                                                            } else 
                                                            if($event['project_type'] == "MSI Non-Project")
                                                            {
                                                                $xxx = "Non-Project";
                                                            } else {
                                                                $xxx = "Self-Improvement";
                                                            }
                                                            echo "<b>" . $xxx . "</b> | ";
                                                        }
                                                    }
                                                    echo $dt['task_name'] . "<br/>";
                                                    echo "<span style='font-size:11px'>";
                                                    echo "<b>Project Code : </b>" . $dt['project_code'] . " | ";
                                                    echo "<b>Role </b> : " . $dt['resource_role'] . " | ";
                                                    echo "<b>Category </b> : " . $dt['task_category'];
                                                    echo "</span>";
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                    // Project Type 
                                                    if(isset($Schedules[$email][$dt['event_id']])) {
                                                        foreach($Schedules[$email][$dt['event_id']] as $event) {
                                                            echo $event['project_type'];
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                                <td><?php echo $dt['project_code']; // Project Code ?></td>
                                                <td><?php // SO Number ?></td>
                                                <td><?php // Order Number ?></td>
                                                <td><?php // Project Name ?></td>
                                                <td><?php // Customer Name ?></td>
                                                <td><?php echo "<span class='text-nowrap'>" . date("d-M-Y", strtotime($dt['start_date'])) . "</span>"; // Plan Start Date?></td>
                                                <td><?php echo "<span class='text-nowrap'>" . date("d-M-Y", strtotime($dt['due_date'])) . "</span>"; // Plan End Date?></td>
                                                <td class="text-center"><?php echo "<span class='text-nowrap'>" . date("d-M-Y", strtotime($dt['work_date'])) . "</span>";// Plan Date ?></td>
                                                <td class="text-center">
                                                    <?php 
                                                    // Actual Date 
                                                    if($Schedules[$email][$dt['event_id']][0]['status'] == "accepted") {
                                                        echo $Schedules[$email][$dt['event_id']][0]['start_time'] . " |<br>" . $Schedules[$email][$dt['event_id']][0]['end_time'];
                                                    }
                                                    ?>
                                                </td>
                                                <td class="">
                                                    <?php 
                                                    // Push Date
                                                    echo date("d-M-Y H:i", strtotime($dt['created_at']));
                                                    ?>
                                                </td>
                                                <td class="">
                                                    <?php 
                                                    // Updated Date
                                                    if($Schedules[$email][$dt['event_id']][0]['status'] == "accepted") {
                                                        echo $Schedules[$email][$dt['event_id']][0]['updated_event'];
                                                    }
                                                    ?>
                                                </td>
                                                <td class="">
                                                    <?php 
                                                    // Sync Date
                                                    if($Schedules[$email][$dt['event_id']][0]['status'] == "accepted") {
                                                        echo $Schedules[$email][$dt['event_id']][0]['sync_date'];
                                                    }
                                                    ?>
                                                </td>
                                                <td><?php echo $dt['task_name']; // Task Name?></td>
                                                <td><?php echo $dt['resource_role']; // Resource Role?></td>
                                                <td>
                                                    <?php 
                                                    // Weekend 
                                                    if(isset($Schedules[$email][$dt['event_id']])) {
                                                        foreach($Schedules[$email][$dt['event_id']] as $event) {
                                                            if(isset($event['status']) && $event['status'] != "needsAction") {
                                                                if(date("N", strtotime($event['start_time'])) == 6 || date("N", strtotime($event['start_time'])) == 7) {
                                                                echo "Weekend" ;
                                                                } else {
                                                                    echo "Workday";
                                                                }
                                                            } else {
                                                                if(date("N", strtotime($dt['start_date'])) == 6 || date("N", strtotime($dt['start_date'])) == 7) {
                                                                echo "Weekend" ;
                                                                } else {
                                                                    echo "Workday";
                                                                }
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php 
                                                    // Cost Plan
                                                    echo number_format($dt['duration']/60,2); 
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php 
                                                    // Cost Actual 
                                                    if($Schedules[$email][$dt['event_id']][0]['status'] == "accepted") {
                                                        if($Schedules[$email][$dt['event_id']][0]['diff_time_minutes'] == 0) { 
                                                            $frame = "<span class='text-warning-emphasis bg-warning-subtle border border-warning-subtle'>";
                                                        } else 
                                                        if($Schedules[$email][$dt['event_id']][0]['diff_time_minutes'] > 480) { 
                                                            $frame = "<span class='text-danger-emphasis bg-danger-subtle border border-warning-subtle'>";
                                                        } else {
                                                            $frame = "";
                                                        }
                                                        echo $frame . number_format($Schedules[$email][$dt['event_id']][0]['diff_time_minutes']/60,2) . "</span>";
                                                    }
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php 
                                                    // Status 
                                                    echo ucfirst($Schedules[$email][$dt['event_id']][0]['status']);
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php 
                                        }
                                    }
                                } else {
                                    if(isset($Schedules[$email])) {
                                        foreach($Schedules[$email] as $EventID) {
                                            if($EventID['status_task'] == "actived") {
                                                foreach($EventID as $NonProject) {
                                                    $fontColor = "text-primary-emphasis bg-primary-subtle border-bottom border-primary-subtle";
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $rsc; // Resource Name?></td>
                                                        <td class="">
                                                            <?php 
                                                            // Project Information
                                                            if($NonProject['project_type'] == "MSI Non-Project")
                                                            {
                                                                $xxx = "Non-Project";
                                                            } else {
                                                                $xxx = "Self-Improvement";
                                                            }
                                                            echo "<b>" . $xxx . "</b> | "; 
                                                            echo $NonProject['subject'];
                                                            ?>
                                                        </td>
                                                        <td><?php echo $NonProject['project_type']; // Project Type ?></td>
                                                        <td><?php // Project Code ?></td>
                                                        <td><?php // SO Number ?></td>
                                                        <td><?php // Order Number ?></td>
                                                        <td><?php // Project Name ?></td>
                                                        <td><?php // Customer Name ?></td>
                                                        <td><?php echo "<span class='text-nowrap'>" . date("d-M-Y", strtotime($NonProject['start_date'])) . "</span>"; // Plan Start Date?></td>
                                                        <td><?php echo "<span class='text-nowrap'>" . date("d-M-Y", strtotime($NonProject['due_date'])) . "</span>"; // Plan End Date?></td>
                                                        <td class=""><?php // Plan Date ?></td>
                                                        <td class="text-center">
                                                            <?php 
                                                            // Actual Date 
                                                            echo "<span class='text-nowrap'>" . date("d-M-Y", strtotime($NonProject['start_time'])) . "</span> <span class='text-nowrap'>" . date("H:i", strtotime($NonProject['start_time'])) . " | " . date("H:i", strtotime($NonProject['end_time'])) . "</span>" ;
                                                            ?>
                                                        </td>
                                                        <td class=""><?php // Push Date ?></td>
                                                        <td class="">
                                                            <?php 
                                                            // Updated Date
                                                            echo "<span class='text-nowrap'>" . date("d-M-Y", strtotime($NonProject['updated_event'])) . "</span> <span class='text-nowrap'>" . date("H:i", strtotime($NonProject['updated_event'])) . "</span>" ;
                                                            ?>
                                                        </td>
                                                        <td class="">
                                                            <?php 
                                                            // Sync Date
                                                            echo "<span class='text-nowrap'>" . date("d-M-Y", strtotime($NonProject['sync_date'])) . "</span> <span class='text-nowrap'>" . date("H:i", strtotime($NonProject['sync_date'])) . "</span>" ;
                                                            ?>
                                                        </td>
                                                        <td><?php echo $NonProject['subject']; // Task Name?></td>
                                                        <td><?php // Resource Role?></td>
                                                        <td>
                                                            <?php 
                                                            // Weekend 
                                                            if(isset($NonProject['status']) && $NonProject['status'] != "needsAction") {
                                                                if(date("N", strtotime($NonProject['start_time'])) == 6 || date("N", strtotime($NonProject['start_time'])) == 7) {
                                                                echo "Weekend" ;
                                                                } else {
                                                                    echo "Workday";
                                                                }
                                                            }
                                                            ?>
                                                        </td>
                                                        <td class=""><?php // Cost Plan ?></td>
                                                        <td class="">
                                                            <?php 
                                                            // Cost Actual 
                                                            $diff = $NonProject['diff_time_minutes'];
                                                            $diffExp = explode(".", $diff);
                                                            $hours = $diffExp[0];
                                                            $xdecimal = ($diff - $hours)*100;
                                                            $CostActual = $hours + $xdecimal/60;
                                                            echo number_format($CostActual,2);
                                                            ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php
                                                            // Status 
                                                            echo ucfirst($NonProject['status']);
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                        }
                                    }
                                }
                                $xxx = array("label" => $rscName, "y" => strval($timeActualType[$email]['High']));
                                array_push($dataTimeHigh, $xxx);
                                $xxx = array("label" => $rscName, "y" => intval($timeActualType[$email]['Medium']));
                                array_push($dataTimeMedium, $xxx);
                                $xxx = array("label" => $rscName, "y" => strval($timeActualType[$email]['Standard']));
                                array_push($dataTimeStandard, $xxx);
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php
            $dataTimeHighx = $dataTimeHigh;
            $dataTimeMediumx = $dataTimeMedium;
            $dataTimeStandardx = $dataTimeStandard;
            $msort = array_msort($dataTimeHigh, array('y'=>SORT_ASC, 'label'=>SORT_ASC), $dataTimeMedium, $dataTimeStandard);
            $dataTimeHigh = $msort[0];
            $dataTimeMedium = $msort[1];
            $dataTimeStandard = $msort[2];
            // End Row Task
            ?>

            <div class="mb-5">
                <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseWidthExample" aria-expanded="false" aria-controls="collapseWidthExample">
                    Deleted Task List
                </button>
                <div class="collapse collapse-horizontal" id="collapseWidthExample">
                    <div class="card card-body">
                        This is a collection of assignments that were deleted by the Project Manager or user.
                        <table id="TaskList" class="display compact" style="width:100%">
                            <thead class="bg-danger-subtle text-danger-emphasis">
                                <tr>
                                    <th rowspan="2">Resource Name</th>
                                    <th rowspan="2">Project Information</th>
                                    <th colspan="2" class="text-center">Schedule Date</th>
                                </tr>
                                <tr>
                                    <th class="text-center">Plan</th>
                                    <th class="text-center">Actual</th>
                                </tr>
                            </thead>
                            <tbody>
                            
                            <?php 
                            foreach($Resources as $rsc) { 
                                $xx1 = explode("<", $rsc);
                                $rscName = $xx1[0];
                                $xx2 = explode(">", $xx1[1]);
                                $email = $xx2[0];

                                if(isset($PreSchedulesUnactived[$email])) {
                                    foreach($PreSchedulesUnactived[$email] as $dt) {
                                        if($dt['status_task'] == "deactived") {
                                            $fontColor = "";
                                            if(isset($SchedulesUnactived[$email][$dt['event_id']])) {
                                                foreach($SchedulesUnactived[$email][$dt['event_id']] as $event) {
                                                    if($event['status'] == "accepted") {
                                                        $fontColor = "text-success-emphasis bg-success-subtle border-bottom border-success-subtle";
                                                    } else
                                                    if($event['status'] == "declined") {
                                                        $fontColor = "text-warning-emphasis bg-warning-subtle border-bottom border-warning-subtle";
                                                    }
                                                }
                                            }
                                            if(isset($SchedulesUnactived[$email][$dt['event_id']])) {
                                                foreach($SchedulesUnactived[$email][$dt['event_id']] as $event) {
                                                    if($event['status'] == "accepted") {
                                                        if($dt['task_category'] == "High") {
                                                            $timeActualType[$email]["High"]++;
                                                        } else
                                                        if($dt['task_category'] == "Medium") {
                                                            $timeActualType[$email]["Medium"]++;
                                                        } else
                                                        if($dt['task_category'] == "Standard") {
                                                            $timeActualType[$email]["Standard"]++;
                                                        }
                                                    }
                                                }
                                            }
                                            ?>
                                            <tr class="">
                                                <td><?php echo $rsc; // Resource Name ?></td>
                                                <td>
                                                    <?php 
                                                    // Project Information
                                                    if(isset($SchedulesUnactived[$email][$dt['event_id']])) {
                                                        foreach($SchedulesUnactived[$email][$dt['event_id']] as $event) {
                                                            if($event['project_type'] == "MSI Project Implementation")
                                                            {
                                                                $xxx = "Implementation";
                                                            } else 
                                                            if($event['project_type'] == "MSI Project Maintenance")
                                                            {
                                                                $xxx = "Maintenance";
                                                            } else 
                                                            if($event['project_type'] == "MSI Non-Project")
                                                            {
                                                                $xxx = "Non-Project";
                                                            } else {
                                                                $xxx = "Self-Improvement";
                                                            }
                                                            echo "<b>" . $xxx . "</b> | ";
                                                        }
                                                    }
                                                    echo $dt['task_name'] . "<br/>";
                                                    echo "<span style='font-size:11px'>";
                                                    echo "<b>Project Code : </b>" . $dt['project_code'] . " | ";
                                                    echo "<b>Role </b> : " . $dt['resource_role'] . " | ";
                                                    echo "<b>Category </b> : " . $dt['task_category'];
                                                    echo "</span>";
                                                    ?>
                                                </td>
                                                <td class="text-center"><?php echo "<span class='text-nowrap'>" . date("d-M-Y", strtotime($dt['work_date'])) . "</span>";// Plan Date ?></td>
                                                <td class="text-center">
                                                    <?php 
                                                    // Actual Date 
                                                    if($Schedules[$email][$dt['event_id']][0]['status'] == "accepted") {
                                                        echo $Schedules[$email][$dt['event_id']][0]['start_time'] . " |<br>" . $Schedules[$email][$dt['event_id']][0]['end_time'];
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php 
                                        }
                                    }
                                } else {
                                    if(isset($SchedulesUnactived[$email])) {
                                        foreach($SchedulesUnactived[$email] as $EventID) {
                                            if($EventID['status_task'] == "deactived") {
                                                foreach($EventID as $NonProject) {
                                                    $fontColor = "text-primary-emphasis bg-primary-subtle border-bottom border-primary-subtle";
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $rsc; // Resource Name?></td>
                                                        <td class="">
                                                            <?php 
                                                            // Project Information
                                                            if($NonProject['project_type'] == "MSI Non-Project")
                                                            {
                                                                $xxx = "Non-Project";
                                                            } else {
                                                                $xxx = "Self-Improvement";
                                                            }
                                                            echo "<b>" . $xxx . "</b> | "; 
                                                            echo $NonProject['subject'];
                                                            ?>
                                                        </td>
                                                        <td class=""><?php // Plan Date ?></td>
                                                        <td class="text-center">
                                                            <?php 
                                                            // Actual Date 
                                                            echo "<span class='text-nowrap'>" . date("d-M-Y", strtotime($NonProject['start_time'])) . "</span> <span class='text-nowrap'>" . date("H:i", strtotime($NonProject['start_time'])) . " | " . date("H:i", strtotime($NonProject['end_time'])) . "</span>" ;
                                                            ?>
                                                        </td>
                                                    <?php
                                                }
                                            }
                                        }
                                    }
                                }
                                // $xxx = array("label" => $rscName, "y" => strval($timeActualType[$email]['High']));
                                // array_push($dataTimeHigh, $xxx);
                                // $xxx = array("label" => $rscName, "y" => intval($timeActualType[$email]['Medium']));
                                // array_push($dataTimeMedium, $xxx);
                                // $xxx = array("label" => $rscName, "y" => strval($timeActualType[$email]['Standard']));
                                // array_push($dataTimeStandard, $xxx);
                            }
                            ?>



                            </tbody>
                        </table>

                        </div>
                </div>
            </div>

            <?php
            if(isset($_GET['check'])) {
            ?>
            <div>Chart Resources Summary</div>
            <div class="row">
                <div class="col-sm-6">
                    <?php echo "<pre>" . json_encode($dataTimeImpx, JSON_PRETTY_PRINT) . "</pre>"; ?>
                </div>
                <div class="col-sm-6">
                    <?php echo "<pre>" . json_encode($dataTimeImp, JSON_PRETTY_PRINT) . "</pre>"; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <?php echo "<pre>" . json_encode($dataTimeMntx, JSON_PRETTY_PRINT) . "</pre>"; ?>
                </div>
                <div class="col-sm-6">
                    <?php echo "<pre>" . json_encode($dataTimeMnt, JSON_PRETTY_PRINT) . "</pre>"; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <?php echo "<pre>" . json_encode($dataTimeNonx, JSON_PRETTY_PRINT) . "</pre>"; ?>
                </div>
                <div class="col-sm-6">
                    <?php echo "<pre>" . json_encode($dataTimeNon, JSON_PRETTY_PRINT) . "</pre>"; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <?php echo "<pre>" . json_encode($dataTimeSelfx, JSON_PRETTY_PRINT) . "</pre>"; ?>
                </div>
                <div class="col-sm-6">
                    <?php echo "<pre>" . json_encode($dataTimeSelf, JSON_PRETTY_PRINT) . "</pre>"; ?>
                </div>
            </div>

            <div>Resources category Type</div>
            <div class="row">
                <div class="col-sm-6">
                    <?php echo "<pre>" . json_encode($dataTimeHighx, JSON_PRETTY_PRINT) . "</pre>"; ?>
                </div>
                <div class="col-sm-6">
                    <?php echo "<pre>" . json_encode($dataTimeHigh, JSON_PRETTY_PRINT) . "</pre>"; ?>
                </div>
                <div class="col-sm-6">
                    <?php echo "<pre>" . json_encode($dataTimeMediumx, JSON_PRETTY_PRINT) . "</pre>"; ?>
                </div>
                <div class="col-sm-6">
                    <?php echo "<pre>" . json_encode($dataTimeMedium, JSON_PRETTY_PRINT) . "</pre>"; ?>
                </div>
                <div class="col-sm-6">
                    <?php echo "<pre>" . json_encode($dataTimeStandardx, JSON_PRETTY_PRINT) . "</pre>"; ?>
                </div>
                <div class="col-sm-6">
                    <?php echo "<pre>" . json_encode($dataTimeStandard, JSON_PRETTY_PRINT) . "</pre>"; ?>
                </div>
            </div>

            <div>The number working working hours</div>
            <div class="row">
                <div class="col-sm-6">
                    <?php echo "<pre>" . json_encode($dataTimeWorkHoursx, JSON_PRETTY_PRINT) . "</pre>"; ?>
                </div>
                <div class="col-sm-6">
                    <?php echo "<pre>" . json_encode($dataTimeWorkHours, JSON_PRETTY_PRINT) . "</pre>"; ?>
                </div>
            </div>

            <div>The number working less than</div>
            <div class="row">
                <div class="col-sm-6">
                    <?php echo "<pre>" . json_encode($dataTimeWorkHoursLess1x, JSON_PRETTY_PRINT) . "</pre>"; ?>
                </div>
                <div class="col-sm-6">
                    <?php echo "<pre>" . json_encode($dataTimeWorkHoursLess1, JSON_PRETTY_PRINT) . "</pre>"; ?>
                </div>
                <div class="col-sm-6">
                    <?php echo "<pre>" . json_encode($dataTimeWorkHoursLess2x, JSON_PRETTY_PRINT) . "</pre>"; ?>
                </div>
                <div class="col-sm-6">
                    <?php echo "<pre>" . json_encode($dataTimeWorkHoursLess2, JSON_PRETTY_PRINT) . "</pre>"; ?>
                </div>
            </div>

            <div>Resource Consistance</div>
            <div class="row">
                <div class="col-sm-6">
                    <?php echo "<pre>" . json_encode($dataTimeConsistencex, JSON_PRETTY_PRINT) . "</pre>"; ?>
                </div>
                <div class="col-sm-6">
                    <?php echo "<pre>" . json_encode($dataTimeConsistence, JSON_PRETTY_PRINT) . "</pre>"; ?>
                </div>
            </div>

            <?php
            }
            ?>

            <!-- Days Summary -->
            <div class="row mt-3 ps-3 pe-3" id="showDaysSummary">
                <div class="hstack gap-3 border-top border-bottom">
                    <div class="p-2">
                        <div class="fs-3">Days Summary</div>
                    </div>
                    <div class="p-2">
                    </div>
                    <div class="p-2 ms-auto">&nbsp;</div>
                    <div class="p-2">
                        <div class="fs-3"><span class="fs-5"><?php //echo date("F Y", strtotime($start)); ?></span></div>
                    </div>
                </div>

                <?php 
                $diff = date_diff(date_create(date("Y-m-d", strtotime($start))), date_create(date("Y-m-d", strtotime($end)))); 
                $diffx =  $diff->format("%R%a")+1;
                for($i=0; $i<$diffx; $i++) 
                { 
                    $wday = (date("w", strtotime("$start $i day")));
                    $days = (date("Ymd", strtotime("$start $i day")));
                    $bgcolor = "";
                    if($wday == 0 || $wday == 6)
                    {
                        $bgcolor = "text-danger-emphasis bg-danger-subtle border border-danger-subtle";
                    }
                    ?>
                    <div class="col-lg-3 mb-5 p-0">
                        <div class="card">
                            <div class="card-header <?php echo $bgcolor; ?>">
                                <?php 
                                $tgl = date("Y-m-d", strtotime("$start $i day"));
                                echo date("l, d F Y", strtotime("$start $i day")); 
                                ?>
                            </div>
                            <div class="card-body">
                                <?php
                                foreach($Resources as $rsc)
                                {
                                    $j = 0;
                                    $xxx = explode("<", $rsc);
                                    $zzz = explode(">", $xxx[1]);
                                    $emailx = $zzz[0];

                                    $PlanHours = $dataPreSchedulesHours["$tgl"]["$emailx"]['total_hours'] ?? 0;
                                    $PlanTask = $dataPreSchedulesTask["$tgl"]["$emailx"]['total_task'] ?? 0;
                                    $ActualHours = $dataSchedulesHours["$tgl"]["$emailx"]['total_hours'] ?? 0;
                                    $ActualTask = $dataSchedulesTask["$tgl"]["$emailx"]['total_task'] ?? 0;
                                    $ActualAcceptedTask = $dataSchedulesTask["$tgl"]["$emailx"]['accepted_task'] ?? 0;
                                    $ActualneedActionTask = $dataSchedulesTask["$tgl"]["$emailx"]['needAction_task'] ?? 0;
                                    $ActualDeclinedTask = $dataSchedulesTask["$tgl"]["$emailx"]['declined_task'] ?? 0;
                                    $PlanAcceptedHours = $dataSchedulesHours["$tgl"]["$emailx"]['plan_hours'] ?? 0;
                                    $ActualAcceptedHours = $dataSchedulesHours["$tgl"]["$emailx"]['accepted_hours'] ?? 0;
                                    $ActualneedActionHours = $dataSchedulesHours["$tgl"]["$emailx"]['needAction_hours'] ?? 0;
                                    $ActualDeclinedHours = $dataSchedulesHours["$tgl"]["$emailx"]['declined_hours'] ?? 0;
                                    $UnplanHours = $dataUnplanHours["$tgl"]["$emailx"]['total_hours'] ?? 0;
                                    $UnplanTask = $dataUnplanTask["$tgl"]["$emailx"]['total_task'] ?? 0;
                                    $ProgressTask = 0;
                                    if($PlanTask>0)
                                    {
                                        $ProgressTask = ($ActualAcceptedTask + $ActualDeclinedTask)/$PlanTask*100;
                                    }
                                    $ProgressHours = 0;
                                    if($PlanTask>0)
                                    {
                                        $ProgressHours = $ActualAcceptedHours/$PlanHours*100;
                                    }
                                    $ProgressPlanHours = 0;
                                    $ProgressPlanHours2 = 0;
                                    if($PlanAcceptedHours>0)
                                    {
                                        $ProgressPlanHours = $ActualAcceptedHours/$PlanAcceptedHours*100;
                                        $ProgressPlanHours2 = (1 + (1 - ($ActualAcceptedHours/$PlanAcceptedHours)))*100;
                                    }
                                    if(!isset($dataIdle["$emailx"]))
                                    {
                                        if($PlanTask == 0 && $UnplanTask == 0 && $wday != 0 && $wday != 6)
                                        {
                                            $dataIdle["$emailx"] = 1;
                                        } else
                                        {
                                            $dataIdle["$emailx"] = 0;
                                        }
                                    } else
                                    {
                                        if($PlanTask == 0 && $UnplanTask == 0 && $wday != 0 && $wday != 6)
                                        {
                                            $dataIdle["$emailx"]++;
                                        }
                                    }

                                    if(!isset($dataWeekend[$emailx]))
                                    {
                                        if(($ActualAcceptedTask > 0 || $UnplanTask > 0) && ($wday == 0 || $wday == 6))
                                        {
                                            $dataWeekend[$emailx] = 1;
                                        } else
                                        {
                                            $dataWeekend[$emailx] = 0;
                                        }
                                    } else
                                    {
                                        if(($ActualAcceptedTask > 0 || $UnplanTask > 0) && ($wday == 0 || $wday == 6))
                                        {
                                            $dataWeekend[$emailx]++;
                                        }
                                    }
                                    ?>
                                    <div class='row border-bottom mb-2'>
                                        <label class='col-sm-12 fw-bold' role="button" onclick="ShowModal('<?php echo $tgl; ?>', '<?php echo $emailx; ?>');" for="daysSummary"><?php echo $rsc; ?></label>
                                        
                                        <label class='col-sm-3' for="daysSummary">Task</label>
                                        <label class='col-sm-9' for="daysSummary">: 
                                            <i class="fa-solid fa-circle-dot"></i> <?php echo $PlanTask; ?> | 
                                            <i class='fa-solid fa-circle-check text-success'></i> <?php echo $ActualAcceptedTask; ?> | 
                                            <i class='fa-solid fa-circle-exclamation text-warning'></i> <?php echo $PlanTask - $ActualAcceptedTask - $ActualDeclinedTask; ?> | 
                                            <i class='fa-solid fa-circle-xmark text-danger'></i> <?php echo $ActualDeclinedTask; ?> | 
                                            <?php echo number_format($ProgressTask, 2); ?>% <?php 
                                            if($ProgressTask==100) 
                                            { 
                                                echo '<i class="fa-regular fa-star text-success"></i>';
                                            } else
                                            if($ProgressTask>100)
                                            {
                                                echo '<i class="fa-regular fa-star text-danger"></i>';
                                            }
                                            ?>
                                        </label>

                                        <label class='col-sm-3' for="daysSummary">Hours</label>
                                        <label class='col-sm-9' for="daysSummary">: 
                                            <i class="fa-solid fa-circle-dot"></i> <?php echo number_format($PlanAcceptedHours,2); ?> | 
                                            <i class='fa-solid fa-circle-check text-success'></i> <?php echo number_format($ActualAcceptedHours, 2); ?> | 
                                            <?php echo number_format($ProgressPlanHours, 2); ?>% <!-- | <?php echo number_format($ProgressPlanHours2, 2); ?>% -->
                                            <?php 
                                            if($ProgressPlanHours==100) 
                                            { 
                                                echo '<i class="fa-regular fa-star text-success"></i>';
                                            } else
                                            if($ProgressPlanHours>100)
                                            {
                                                echo '<i class="fa-regular fa-star text-danger"></i>';
                                            }
                                            ?>
                                        </label>

                                        <label class='col-sm-3' for="daysSummary">Unplan</label>
                                        <label class='col-sm-9' for="daysSummary">: <i class='fa-solid fa-circle-check text-success'></i> <?php echo $UnplanTask; ?> task | <?php echo number_format($UnplanHours,2); ?> hours</label>

                                        <label class='col-sm-3' for="daysSummary">Status</label>
                                        <label class='col-sm-9' for="daysSummary">: <?php echo $ActualHours <= 1 ? "<span class='text-danger'><i class='fa-solid fa-circle-arrow-down'></i> Idle (Less than an hour)</span>" : ($ActualHours <= 4 ? "<span class='text-success'><i class='fa-solid fa-circle-right'></i> Midle (Between 1 - 4 hours)</span>" : "<span class='text-primary'><i class='fa-solid fa-circle-arrow-up'></i> Full (More than 4 hours)</span>"); ?></label>
                                    </div>
                                    <?php
                                    $j++;
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php 
                } 

                foreach($Resources as $rsc)
                {
                    $xxx = explode("<", $rsc);
                    $zzz = explode(">", $xxx[1]);
                    $emailx = $zzz[0];
                    if($dataIdle[$emailx] > 2)
                    {
                        $result = "<i class=\"fa-solid fa-arrow-down text-danger\"></i>";
                    } else
                    if($dataIdle[$emailx] > 0)
                    {
                        $result = "<i class=\"fa-solid fa-arrow-right text-success\"></i>";
                    } else
                    {
                        $result = "<i class=\"fa-regular fa-star text-success\"></i>";
                    }
                    $xxx = "<div class=\"row\"><div class=\"col text-end\">" . $dataIdle[$emailx] . "</div><div class=\"col-auto ps-0 text-danger\">" . $result . "</div></div>";

                    if($dataWeekend[$emailx] > 0)
                    {
                        $TotalWeekend = $diffDate - $workDays;
                        $FaktorWeekend = $dataWeekend[$emailx] / $TotalWeekend;
                    } else
                    {
                        $FaktorWeekend = 0;
                    }
                    if($FaktorWeekend > 0.5)
                    {
                        $StatusWeekend = "<i class=\"fa-solid fa-arrow-down text-danger\"></i>";
                    } else
                    if($FaktorWeekend > 0)
                    {
                        $StatusWeekend = "<i class=\"fa-solid fa-arrow-right text-success\"></i>";
                    } else
                    {
                        $StatusWeekend = "<i class=\"fa-regular fa-star text-success\"></i>";
                    }

                    ?>
                    <script>
                        document.getElementById("IdleTask_<?php echo $emailx; ?>").innerHTML = '<?php echo $xxx; ?>';
                        document.getElementById("dataWeekend<?php echo $emailx; ?>").innerHTML = '<?php echo "<div class=\"row\"><div class=\"col text-end\">" . $dataWeekend[$emailx] . "</div><div class=\"col-auto ps-0 text-danger\">" . $StatusWeekend . "</div></div>"; ?>';
                    </script>
                    <?php
                }
                ?>
            </div>
            <!-- End Days Summary -->
        </div>
        <?php 
        //module update
        $title = "Productivity Chart";
        $author = 'Syamsul Arham';
        $type = "sub-module"; // module (&mod), submodule (&sub)
        $revisionType = 'control'; // major, minor, control
        $dashboardEnable = false;
        $moduleDescription = "This module is used to view Productivity Chart.";
        $revision_msg = "Update Non-Projet List";
        $showFooter = true;

        global $ClassVersion;
        $ClassVersion->show_footer(__FILE__, $title, $moduleDescription, $type, $revisionType, $dashboardEnable, $author, $revision_msg, $showFooter);
        ?>
    </div>
    <?php 
} else
{
    ?>
    <div class="alert alert-warning" role="alert">
        <?php 
        echo "Member of group " . $group . " is not available! Please add a resource in group.";
        ?>
    </div>
    <?php
} 
?>

<!-- Menu Filter -->
<div class="offcanvas offcanvas-end" data-bs-backdrop="static" tabindex="-1" id="staticBackdrop" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header border border-bottom bg-light">
        <h5 class="offcanvas-title" id="staticBackdropLabel">Filter</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">

        <div class="card mb-3">
            <div class="card-header fw-bold">
                Project Type
            </div>
            <div class="card-body">
                <div class="form-check">
                    <input class="form-check-input ProjectType" type="checkbox" name="category" id="category01" value="1" 
                        <?php 
                        if(isset($cat))
                        {
                            if(($cat & 8) == 8)
                            {
                                echo "checked";
                            } else
                            if($cat == 'null')
                            {
                                echo "checked";
                            } else
                            {
                                echo "";
                            }
                        } else
                        {
                            echo "checked";
                        }
                        ?>>
                    <label class="form-check-label" for="category01">
                        Implementation
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input ProjectType" type="checkbox" name="category" id="category02" value="1" 
                        <?php 
                        if(isset($cat))
                        {
                            if(($cat & 4) == 4)
                            {
                                echo "checked";
                            } else
                            if($cat == 'null')
                            {
                                echo "checked";
                            } else
                            {
                                echo "";
                            }
                        } else
                        {
                            echo "checked";
                        }
                        ?>>
                    <label class="form-check-label" for="category02">
                        Maintenance
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input ProjectType" type="checkbox" name="category" id="category03" value="1" 
                    <?php 
                    if(isset($cat))
                    {
                        if(($cat & 2) == 2)
                        {
                            echo "checked";
                        } else
                        if($cat == 'null')
                        {
                            echo "checked";
                    } else
                        {
                            echo "";
                        }
                    } else
                    {
                        echo "checked";
                    }
                    ?>>
                    <label class="form-check-label" for="category03">
                        Non-Project
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input ProjectType" type="checkbox" name="category" id="category04" value="1" 
                    <?php 
                    if(isset($cat))
                    {
                        if(($cat & 1) == 1)
                        {
                            echo "checked";
                        } else
                        if($cat == 'null')
                        {
                            echo "checked";
                    } else
                        {
                            echo "";
                        }
                    } else
                    {
                        echo "checked";
                    }
                    ?>>
                    <label class="form-check-label" for="category04">
                        Self Improvement
                    </label>
                </div>
            </div>
        </div>

        <?php if($_SESSION["Microservices_UserLevel"] != "Users") { ?>
            <div class="card mb-3">
                <div class="card-header fw-bold">
                    Group Resources
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php
                        $mysql = "SELECT `structure_code`, `group` FROM `sa_mst_organization_project` ORDER BY `structure_code` ASC";
                        $rsLevel = $DBHCM->get_sql($mysql);
                        if($rsLevel[2]>0)
                        {
                            ?>
                            <div class="row">
                                <?php
                                $i=0;
                                do {
                                    if($i==0)
                                    {
                                        $x0 = $rsLevel[0]['group'];
                                        ?>
                                            <div class="row">
                                                <label class="col-form-label fw-bold" for="level"><?php echo $x0; ?></label>
                                            </div>
                                        <?php
                                    }
                                    $x1 = $rsLevel[0]['group'];
                                    if($i>0 && $x0!=$x1)
                                    {
                                        ?>
                                        </div>
                                        <div class="row">
                                            <div class="row">
                                                <label class="col-form-label fw-bold" for="level"><?php echo $x1; ?></label>
                                            </div>
                                        <?php
                                    }
                                    ?>
                                    <div class="col-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="group" id="group<?php echo $i; ?>" value="<?php echo $rsLevel[0]['structure_code']; ?>" <?php echo ($group==$rsLevel[0]['structure_code']) ? "checked" : ($i==0 ? "checked" : ""); ?>>
                                            <label class="form-check-label" for="level01">
                                                <?php echo $rsLevel[0]['structure_code']; ?>
                                            </label>
                                        </div>
                                    </div>
                                    <?php
                                    $x0 = $x1;
                                    $i++;
                                } while($rsLevel[0]=$rsLevel[1]->fetch_assoc());
                                ?>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="card mb-3">
            <div class="card-header fw-bold">
                Periode
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="row">
                        <label class="col-form-label fw-bold" for="level">Last or Next Week</label>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="periode" id="hm7" value="HM7" <?php echo ($periode=="HM7") ? "checked" : ""; ?>>
                            <label class="form-check-label" for="level01">
                                H-7
                            </label>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="periode" id="hp7" value="HP7" <?php echo ($periode=="HP7") ? "checked" : ""; ?>>
                            <label class="form-check-label" for="level01">
                                H+7
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="row">
                        <label class="col-form-label fw-bold" for="level">Date Periode</label>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-5" for="periode">Start : </label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control form-control-sm" id="start" name="start" placeholder="Start Date." value="<?php echo $start>0 ? date("d-M-Y", strtotime($start)) : ""; ?>" onchange="">
                    </div>
                </div>
                <div class="row mb-3">
                    <?php
                    $end = "";
                    if(isset($_GET['end']) && $_GET['end']!="NaN")
                    {
                        $end = $_GET['end'];
                    } else
                    {
                        $end = date("d-M-Y");
                    }
                    ?>
                    <label class="col-sm-5" for="periode">End : </label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control form-control-sm" id="end" name="start" placeholder="End Date." value="<?php echo $end>0 ? date("d-M-Y", strtotime($end)) : ""; ?>" onchange="">
                    </div>
                </div>
                <div class="row" class="btn-sm">
                    <button class="col-sm-3 btn btn-sm btn-light border" id="myButton">Submit</button>
                </div>
            </div>
        </div>

        <?php if($_SESSION["Microservices_UserLevel"] != "Users") { ?>
            <div class="card mb-3">
                <div class="card-header fw-bold">
                    Resource List
                </div>
                <div class="card-body">
                    <?php
                    // Query Resources
                    $mysql = sprintf("SELECT `sa_mst_organization_project`.`structure_code`,
                    `sa_mst_employees`.`employee_name`, 
                    `sa_mst_organization_project_employee`.`employee_email`
                    FROM `sa_mst_organization_project_employee` 
                    LEFT JOIN `sa_mst_employees` ON `sa_mst_employees`.`email` = `sa_mst_organization_project_employee`.`employee_email`
                    LEFT JOIN `sa_mst_organization_project` ON `sa_mst_organization_project`.`structure_id` = `sa_mst_organization_project_employee`.`structure_id`
                    WHERE `sa_mst_organization_project`.`structure_code`= %s 
                    ORDER BY `sa_mst_employees`.`employee_name` ASC",
                    GetSQLValueString($group, "text"),
                    );
                    $rsResources = $DBHCM->get_sql($mysql);

                    if($rsResources[2]>0)
                    {
                        $xxx = isset($_GET['resource']) ? $_GET['resource'] : "All";
                        $i = 0;
                        ?>
                            <div class="form-check">
                                <input class="form-check-input Resources" type="radio" name="resource" id="resource[<?php echo $i; ?>]" value="All" <?php echo $xxx=="All" ? "checked" : ""; ?>>
                                <label class="form-check-label" for="category04">All</label>
                            </div>
                        <?php
                        $i++;
                        do {
                            $email = $DBHCM->split_email($rsResources[0]['employee_email']);
                            ?>
                            <div class="form-check">
                                <input class="form-check-input Resources" type="radio" name="resource" id="resource[<?php echo $i; ?>]" value="<?php echo $email[1]; ?>" <?php echo $email[1]==$xxx ? "checked" : ""; ?>>
                                <label class="form-check-label" for="category04"><?php echo $email[0]; ?></label>
                            </div>
                            <?php
                            $i++;
                        } while($rsResources[0]=$rsResources[1]->fetch_assoc());
                    }
                    ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<!-- End Menu Filter -->

<!-- Menu Setup -->
<div class="offcanvas offcanvas-end" data-bs-backdrop="static" tabindex="-1" id="staticSetup" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header border border-bottom bg-light">
        <h5 class="offcanvas-title" id="staticBackdropLabel">Setup</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">

        <div class="row bg-body-secondary p-3 mb-3">View Listing</div>
        <div class="row">
            <div class="col-lg-6">
                <div class="form-check">
                    <input class="form-check-input ViewListing" type="checkbox" name="Legend" id="Legend" value="Legend" 
                    <?php
                        if($showLegend)
                        {
                            echo "checked";
                        } else
                        {
                            echo "";
                        }
                        ?>>
                    <label class="form-check-label" for="ShowLegend">
                        Legend
                    </label>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-check">
                    <input class="form-check-input ViewListing" type="checkbox" name="Chart" id="Chart" value="Chart" 
                    <?php
                        if($showChart)
                        {
                            echo "checked";
                        } else
                        {
                            echo "";
                        }
                        ?>>
                    <label class="form-check-label" for="ShowChart">
                        Chart
                    </label>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-check">
                    <input class="form-check-input ViewListing" type="checkbox" name="RSumm" id="RSumm" value="RSumm" 
                    <?php
                        if($showResourcesSummary)
                        {
                            echo "checked";
                        } else
                        {
                            echo "";
                        }
                        ?>>
                    <label class="form-check-label" for="ShowChart">
                        Resources Summary
                    </label>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-check">
                    <input class="form-check-input ViewListing" type="checkbox" name="pTask" id="pTask" value="pTask" 
                        <?php
                        if($showProgress)
                        {
                            echo "checked";
                        } else
                        {
                            echo "";
                        }
                        ?>>
                    <label class="form-check-label" for="pTask">
                        Resource Detail
                    </label>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-check">
                    <input class="form-check-input ViewListing" type="checkbox" name="Task" id="Task" value="Task" 
                        <?php
                        if($showTaskList)
                        {
                            echo "checked";
                        } else
                        {
                            echo "";
                        }
                        ?>>
                    <label class="form-check-label" for="pTask">
                        Task List
                    </label>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-check">
                    <input class="form-check-input ViewListing" type="checkbox" name="DaysSummary" id="DaysSummary" value="DaysSummary" 
                    <?php
                        if($showDaysSummary)
                        {
                            echo "checked";
                        } else
                        {
                            echo "";
                        }
                        ?>>
                    <label class="form-check-label" for="DaysSummary">
                        Days Summary
                    </label>
                </div>
            </div>
        </div>
        <div class="row bg-body-secondary p-3 mb-3">Link </div>
        <div class="row">
            <a href="index.php?mod=productivity&sub=structure_project" class="text-body text-decoration-none">Structure Project</a>
        </div>
    </div>
</div>
<!-- End Menu Setup -->

<script>
function ShowModal(tgl, email, tglend = "")
{
    if(tglend != "")
    {
        tglend = "&end="+tglend;
    }
    $("#PopUpModal").modal("show");
    $("#div2").load("components/modules/productivity/resources_load_detail.php?tgl="+tgl+"&email="+email+tglend);
}
</script>

<!-- Modal -->
<div class="modal fade" id="PopUpModal" tabindex="-1" aria-labelledby="exampleModalLabelx" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabelx">
                    <div id="title">List of all assignments</div>
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="div2"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
var TotalResources = <?php echo $Totalresources; ?>;

// Get all radio buttons
const radioButtons = document.querySelectorAll('input[type="radio"]');
get1 = ""; // project type
get2 = ""; // group
get3 = ""; // periode
get4 = ""; // resource

// Add event listener to each radio button
radioButtons.forEach(radioButton => {
    radioButton.addEventListener('click', function() {
        // Get the current URL
        const urlParams = new URLSearchParams(window.location.search);

        // Retrieve a specific parameter value by name
        const xlevel = urlParams.get('group');
        const xcategory = urlParams.get('category');
        const xstart = urlParams.get('start');
        const xend = urlParams.get('end');
        const xresource = urlParams.get('resource');
        const xperiode = urlParams.get('periode');

        // Get the selected radio button's value
        const selectedValue = this.value;
        const xxx = `${selectedValue}`.substr(0, 5);

        const month = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
        const datex = ["31","28","31","30","31","30","31","31","30","31","30","31"];
        var curr = new Date; // get current date
        var firstx = curr.getDate() - curr.getDay(); // First day is the day of the month - the day of the week
        var xyz = 0;

        if(xxx=="imple" || xxx=="maint" || xxx=="nonpr" || xxx=="selfi" || xxx=="all")
        {
            get1 = "&category="+`${selectedValue}`;
            xyz = 1;
        } else
        {
            get1 = "&category="+xcategory;
        }

        if(xxx=="P1" || xxx=="P2" || xxx=="P3" || xxx=="P4" || xxx=="I1" || xxx=="I2" || xxx=="I3" || xxx=="I4" || xxx=="I5" || xxx=="S1" || xxx=="S2" || xxx=="S3" || xxx=="S4" || xxx=="S5" || xxx=="S6" || xxx=="M1" || xxx=="M2" || xxx=="M3" || xxx=="M4" || xxx=="M5")
        {
            get2 = "&group="+`${selectedValue}`;
            get4 = "&resource=All";
            xyz = 2;
        } else{
            get2 = "&group="+xlevel;
        }

        const xxx1 = document.getElementById("start").value;
        const xxx2 = document.getElementById("end").value;
        if(xxx1=="01-Jan-1970" || xxx1=="")
        {
            get3 = "&start="+xstart+"&end="+xend;
            xyz = 1;
        } else {
            get3 = "&start="+xxx1+"&end="+xxx2;
        }

        if(xxx=="HM7")
        {
            var today = new Date();
            var lastweek = new Date(today.getFullYear(), today.getMonth(), today.getDate()-6);
            get3 = "&start="+lastweek.toLocaleDateString("en-US")+"&end="+today.toLocaleDateString("en-US")+"&periode="+`${selectedValue}`;
            xyz = 1;
        } else
        if(xxx=="HP7")
        {
            var today = new Date();
            var nextweek = new Date(today.getFullYear(), today.getMonth(), today.getDate()+6);
            get3 = "&start="+today.toLocaleDateString("en-US")+"&end="+nextweek.toLocaleDateString("en-US")+"&periode="+`${selectedValue}`;
            xyz = 1;
        } else
        {
            get3 += "&periode="+xperiode;
        }

        if(xyz == 0)
        {
            get4 = "&resource="+`${selectedValue}`;
        } else
        if(xyz == 1)
        {
            get4 = "&resource="+xresource;
        }

        window.location.href="index.php?mod=productivity&sub=chart_productivity_v3"+get1+get2+get3+get4;
    });
});

// Get all radio buttons
const checkboxButtons = document.querySelectorAll('.ProjectType');
get1 = ""; // project type
get2 = ""; // group
get3 = ""; // periode
get4 = ""; // resource

// Add event listener to each radio button
checkboxButtons.forEach(checkboxButton => {
    checkboxButton.addEventListener('click', function() {
        // Get the current URL
        const urlParams = new URLSearchParams(window.location.search);

        // Retrieve a specific parameter value by name
        const xperiode = urlParams.get('periode');
        const xlevel = urlParams.get('group');
        const xcategory = urlParams.get('category');
        const xstart = urlParams.get('start');
        const xend = urlParams.get('end');
        const xresource = urlParams.get('resource');
        const xdayssummary = urlParams.get('days');
        const xlegend = urlParams.get('legend');
        const xhours = urlParams.get('hours');

        // Get the selected radio button's value
        const selectedValuex = this.value;
        const xxx = `${selectedValuex}`.substr(0, 5);

        var ddd = 0;
        if(document.getElementById("category01").checked)
        {
            ddd += 8;
        }
        if(document.getElementById("category02").checked)
        {
            ddd += 4;
        }
        if(document.getElementById("category03").checked)
        {
            ddd += 2;
        }
        if(document.getElementById("category04").checked)
        {
            ddd += 1;
        }
        get1 = "&category="+ddd;
        get2 = "&group="+xlevel;
        get3 = "&start="+xstart+"&end="+xend+"&periode="+xperiode;
        get4 = "&resource="+xresource;


        window.location.href="index.php?mod=productivity&sub=chart_productivity_v3"+get1+get2+get3+get4;
    });

});

// Get all radio buttons
const checkboxButtons2 = document.querySelectorAll('.ViewListing');

// Add event listener to each radio button
checkboxButtons2.forEach(checkboxButton2 => {
    checkboxButton2.addEventListener('click', function() {
        // Get the selected radio button's value
        const selectedValuex = this.value;
        const xxx = `${selectedValuex}`.substr(0, 5);

        if(xxx=="DaysS")
        {
            var asd = document.getElementById("DaysSummary").checked;
            if(asd == true)
            {
                asdf = 1;
                document.getElementById('showDaysSummary').style.display = '';
                document.getElementById("DaysSummary").checked = true;
            } else
            {
                asdf = 0;
                document.getElementById('showDaysSummary').style.display = 'none';
                document.getElementById("DaysSummary").checked = false;
            }
            setCookie('showDaysSummary',asdf,3600);
        }

        if(xxx=="Legen")
        {
            var asd = document.getElementById("Legend").checked;
            if(asd == true)
            {
                asdf = 1;
                document.getElementById('showLegend').style.display = '';
                document.getElementById("Legend").checked = true;
            } else
            {
                asdf = 0;
                document.getElementById('showLegend').style.display = 'none';
                document.getElementById("Legend").checked = false;
            }
            setCookie('showLegend',asdf,3600);
        }

        if(xxx=="Chart")
        {
            var asd = document.getElementById("Chart").checked;
            if(asd == true)
            {
                asdf = 1;
                document.getElementById('showChart').style.display = '';
                document.getElementById("Chart").checked = true;
            } else
            {
                asdf = 0;
                document.getElementById('showChart').style.display = 'none';
                document.getElementById("Chart").checked = false;
            }
            setCookie('showChart',asdf,3600);
        }
        
        if(xxx=="RSumm")
        {
            var asd = document.getElementById("RSumm").checked;
            if(asd == true)
            {
                asdf = 1;
                document.getElementById('showResourcesSummary').style.display = '';
                document.getElementById("RSumm").checked = true;
            } else
            {
                asdf = 0;
                document.getElementById('showResourcesSummary').style.display = 'none';
                document.getElementById("RSumm").checked = false;
            }
            setCookie('showResourcesSummary',asdf,3600);
        }

        if(xxx=="Task")
        {
            var asd = document.getElementById("Task").checked;
            if(asd == true)
            {
                asdf = 1;
                document.getElementById('showTaskList').style.display = '';
                document.getElementById("Task").checked = true;
            } else
            {
                asdf = 0;
                document.getElementById('showTaskList').style.display = 'none';
                document.getElementById("Task").checked = false;
            }
            setCookie('showTaskList',asdf,3600);
        }

        if(xxx=="Task")
        {
            var asd = document.getElementById("Task").checked;
            if(asd == true)
            {
                asdf = 1;
                document.getElementById('showTaskList').style.display = '';
                document.getElementById("Task").checked = true;
            } else
            {
                asdf = 0;
                document.getElementById('showTaskList').style.display = 'none';
                document.getElementById("Task").checked = false;
            }
            setCookie('showTaskList',asdf,3600);
        }

        if(xxx=="pTask")
        { show_console('showProgress');
            var asd = document.getElementById("pTask").checked;
            if(asd == true)
            { show_console('showProgressTrue(10)');
                asdf = 1;
                document.getElementById('showProgress10').style.display = '';
                document.getElementById('showProgress20').style.display = '';
                document.getElementById('showProgress30').style.display = '';
                document.getElementById('showHours10').style.display = '';
                document.getElementById('showHours20').style.display = '';
                document.getElementById('showHours30').style.display = '';
                for(i=1;i<=TotalResources;i++)
                { show_console('showProgressTrue(100)');
                    document.getElementById('showProgress4'+i).style.display = '';
                    document.getElementById('showProgress5'+i).style.display = '';
                    document.getElementById('showProgress6'+i).style.display = '';
                    document.getElementById('showHours4'+i).style.display = '';
                    document.getElementById('showHours5'+i).style.display = '';
                    document.getElementById('showHours6'+i).style.display = '';
                }
                document.getElementById("pTask").checked = true;
            } else
            { show_console('showProgressFalse(40)');
                asdf = 0;
                document.getElementById('showProgress10').style.display = 'none';
                document.getElementById('showProgress20').style.display = 'none';
                document.getElementById('showProgress30').style.display = 'none';
                document.getElementById('showHours10').style.display = 'none';
                document.getElementById('showHours20').style.display = 'none';
                document.getElementById('showHours30').style.display = 'none';
                for(i=1;i<=TotalResources;i++)
                { show_console('showProgressFalse(400)');
                    document.getElementById('showProgress4'+i).style.display = 'none';
                    document.getElementById('showProgress5'+i).style.display = 'none';
                    document.getElementById('showProgress6'+i).style.display = 'none';
                    document.getElementById('showHours4'+i).style.display = 'none';
                    document.getElementById('showHours5'+i).style.display = 'none';
                    document.getElementById('showHours6'+i).style.display = 'none';
                }
                document.getElementById("pTask").checked = false;
            }
            setCookie('showProgress',asdf,3600);
        }
    });
});

// Get the button element by its ID
const button = document.getElementById('myButton');

// Function to be executed when the button is clicked
function handleClick() {
    // Get the current URL
    const urlParams = new URLSearchParams(window.location.search);

    // Retrieve a specific parameter value by name
    const xlevel = urlParams.get('group');
    const xcategory = urlParams.get('category');
    const xstart = urlParams.get('start');
    const xend = urlParams.get*('end');
	const xresource = urlParams.get('resource');
    const xperiode = urlParams.get('periode');
    get1 = "&category="+xcategory;
    get2 = "&group="+xlevel;

	// Get the selected radio button's value
	const selectedValuex = this.value;
	const xxx = `${selectedValuex}`.substr(0, 5);

    get1 = "&category="+xcategory;
    get2 = "&group="+xlevel;

    // Get the selected radio button's value
    const xxx1 = document.getElementById("start").value;
    const xxx2 = document.getElementById("end").value;
    if(xxx1=="01-Jan-1970" || xxx1=="")
    {
        get3 = "&start="+xstart+"&end="+xend;
    } else {
        get3 = "&start="+xxx1+"&end="+xxx2;
    }

	if(xxx=="resou")
	{
		get4 = "&resource="+`${selectedValue}`;
	} else
	{
		get4 = "&resource="+xresource;
	}

    window.location.href="index.php?mod=productivity&sub=chart_productivity_v3"+get1+get2+get3+get4;
}

// Add an event listener to the button
button.addEventListener('click', handleClick);

jQuery(function(){

    jQuery('#start').datetimepicker({
        format:'d-M-Y',
        timepicker:false,
        onShow:function( ct ){
            this.setOptions({
            })
        }
    });
    jQuery('#end').datetimepicker({
        format:'d-M-Y',
        timepicker:false,
        onShow:function( ct ){
            this.setOptions({
            })
        }
    });

});

function setCookie(c_name,value,exdays)
{
    var exdate = new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
    document.cookie=c_name + "=" + c_value;
}

function getCookie(c_name) {
    var nameEQ = c_name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function loadpage() {
    window.location.href = "index.php?" + document.getElementById('onload').value;
}

xxx = getCookie("showDaysSummary");
if(xxx == 1)
{ show_console('showDaysSummaryTrue(0)');
    document.getElementById('showDaysSummary').style.display = '';
    document.getElementById("DaysSummary").checked = true;
} else
{ show_console('showDaysSummaryFalse(0)');
    document.getElementById('showDaysSummary').style.display = 'none';
    document.getElementById("DaysSummary").checked = false;
}
xxx = getCookie("showLegend");
if(xxx == 1)
{ show_console('showLegendTrue(0)');
    document.getElementById('showLegend').style.display = '';
    document.getElementById("Legend").checked = true;
} else
{ show_console('showLegendFalse(0)');
    document.getElementById('showLegend').style.display = 'none';
    document.getElementById("Legend").checked = false;
}
xxx = getCookie("showChart");
if(xxx == 1)
{ show_console('showChartTrue(0)');
    document.getElementById('showChart').style.display = '';
    document.getElementById("Chart").checked = true;
} else
{ show_console('showChartFalse(0)');
    document.getElementById('showChart').style.display = 'none';
    document.getElementById("Chart").checked = false;
}
xxx = getCookie("showResourcesSummary");
if(xxx == 1)
{ show_console('showResourcesSummaryTrue(0)');
    document.getElementById('showResourcesSummary').style.display = '';
    document.getElementById("ResourcesSummary").checked = true;
} else
{ show_console('showResourcesSummaryFalse(0)');
    document.getElementById('showResourcesSummary').style.display = 'none';
    document.getElementById("ResourcesSummary").checked = false;
}
xxx = getCookie("showTaskList");
if(xxx == 1)
{ show_console('showTaskListTrue(0)');
    document.getElementById('showTaskList').style.display = '';
    document.getElementById("Task").checked = true;
} else
{ show_console('showTaskListFalse(0)');
    document.getElementById('showTaskList').style.display = 'none';
    document.getElementById("Task").checked = false;
}
xxx = getCookie("showProgress");
if(xxx == 1)
{ show_console('showProgressTrue(0)');
    document.getElementById('showProgress10').style.display = '';
    document.getElementById('showProgress20').style.display = '';
    document.getElementById('showProgress30').style.display = '';
    document.getElementById('showHours10').style.display = '';
    document.getElementById('showHours20').style.display = '';
    document.getElementById('showHours30').style.display = '';
    for(i=1;i<=TotalResources;i++)
        { show_console('showProgressTrue(00)');
            document.getElementById('showProgress4'+i).style.display = '';
            document.getElementById('showProgress5'+i).style.display = '';
            document.getElementById('showProgress6'+i).style.display = '';
            document.getElementById('showHours4'+i).style.display = '';
            document.getElementById('showHours5'+i).style.display = '';
            document.getElementById('showHours6'+i).style.display = '';
    }
    document.getElementById("pTask").checked = true;
} else
{ show_console('showProgressFalse(0)');
    document.getElementById('showProgress10').style.display = 'none';
    document.getElementById('showProgress20').style.display = 'none';
    document.getElementById('showProgress30').style.display = 'none';
    document.getElementById('showHours10').style.display = 'none';
    document.getElementById('showHours20').style.display = 'none';
    document.getElementById('showHours30').style.display = 'none';
    for(i=1;i<=TotalResources;i++)
        { show_console('showProgressFalse(00)');
            document.getElementById('showProgress4'+i).style.display = 'none';
            document.getElementById('showProgress5'+i).style.display = 'none';
            document.getElementById('showProgress6'+i).style.display = 'none';
            document.getElementById('showHours4'+i).style.display = 'none';
            document.getElementById('showHours5'+i).style.display = 'none';
            document.getElementById('showHours6'+i).style.display = 'none';
    }
    document.getElementById("pTask").checked = false;
}

function show_console(axd)
{
    const d = new Date();
    let hour = d.getHours();
    let minutes = d.getMinutes();
    let seconds = d.getSeconds();
    let microseconds = addZero(d.getMilliseconds(), 5);
    console.log(hour+":"+minutes+":"+seconds+":"+microseconds+" "+axd);
}
</script>

<script>
    window.onload = function() {

        function compareDataPointYAscend(dataPoint1, dataPoint2) {
            return dataPoint1.y - dataPoint2.y;
        }

        function compareDataPointYDescend(dataPoint1, dataPoint2) {
            return dataPoint2.y - dataPoint1.y;
        }

        var chartHeight = 400;
        if(TotalResources > 10) {
            chartHeight = TotalResources * 30;
        }
        var chart0 = new CanvasJS.Chart("chartContainer0", {
            animationEnabled: true,
            exportEnabled: true,
            subtitles: [{
                text: "Resources Summary",
                fontSize: 24
            }],
            axisX: {
                interval: 1,
                labelFontSize: 12
            },
            axisY: {
                interlacedColor: "rgba(1,77,101,.2)",
                gridColor: "rgba(1,77,101,.1)",
                title: "Hours",
                labelFontSize: 12,
                titleFontSize: 12,
                minimum: 0
            },
            legend: {
                fontSize: 12
            },
            toolTip: {
                shared: true
            },
            // height: chartHeight,
            data: [{
                    type: "stackedBar",
                    name: "Implementation",
                    showInLegend: true,
                    color: "#014D65",
                    yValueFormatString: "###0.00 hours",
                    dataPoints: <?php echo json_encode($dataTimeImp, JSON_NUMERIC_CHECK); ?>
                },
                {
                    type: "stackedBar",
                    name: "Maintenance",
                    showInLegend: true,
                    color:"#A57164",
                    yValueFormatString: "###0.00 hours",
                    dataPoints: <?php echo json_encode($dataTimeMnt, JSON_NUMERIC_CHECK); ?>
                },
                {
                    type: "stackedBar",
                    name: "Non-Project",
                    showInLegend: true,
                    color: "#339900",
                    yValueFormatString: "###0.00 hours",
                    dataPoints: <?php echo json_encode($dataTimeNon, JSON_NUMERIC_CHECK); ?>
                },
                {
                    type: "stackedBar",
                    name: "Self-Improvement",
                    showInLegend: true,
                    color: "#cccc00",
                    yValueFormatString: "###0.00 hours",
                    dataPoints: <?php echo json_encode($dataTimeSelf, JSON_NUMERIC_CHECK); ?>
                }
            ]
        });
        //increasing order
        // chart0.options.data[0].dataPoints.sort(compareDataPointYAscend);
        chart0.render();

        var chart1 = new CanvasJS.Chart("chartContainer1", {
            animationEnabled: true,
            exportEnabled: true,
            subtitles: [{
                text: "Resources Category Type",
                fontSize: 24
            }],
            axisX: {
                interval: 1,
                labelFontSize: 12
            },
            axisY: {
                interlacedColor: "rgba(1,77,101,.2)",
                gridColor: "rgba(1,77,101,.1)",
                title: "Tasks",
                labelFontSize: 12,
                titleFontSize: 12,
                minimum: 0
            },
            legend: {
                fontSize: 12
            },
            toolTip: {
                shared: true
            },
            // height: chartHeight,
            data: [{
                    type: "stackedBar",
                    name: "High",
                    showInLegend: true,
                    color: "#2f4f4f", //"#014D65",
                    yValueFormatString: "###0 tasks",
                    dataPoints: <?php echo json_encode($dataTimeHigh, JSON_NUMERIC_CHECK); ?>
                },
                {
                    type: "stackedBar",
                    name: "Medium",
                    showInLegend: true,
                    color: "#918151", //"#A57164",
                    yValueFormatString: "###0 tasks",
                    dataPoints: <?php echo json_encode($dataTimeMedium, JSON_NUMERIC_CHECK); ?>
                },
                {
                    type: "stackedBar",
                    name: "Standard",
                    showInLegend: true,
                    color: "#ffa812", //"#339900",
                    yValueFormatString: "###0 tasks",
                    dataPoints: <?php echo json_encode($dataTimeStandard, JSON_NUMERIC_CHECK); ?>
                }
            ]
        });
        //increasing order
        // chart1.options.data[0].dataPoints.sort(compareDataPointYAscend);
        chart1.render();

        var chart2 = new CanvasJS.Chart("chartContainer2", {
            animationEnabled: true,
            exportEnabled: true,
            // title: {
            //     text: "Category A",
            //     fontSize: 30
            // },
            subtitles: [{
                text: "Number of working hours less than <?php echo $workHours; ?> hours",
                fontSize: 24
            }],
            axisX: {
                interval: 1,
                labelFontSize: 12
            },
            axisY: {
                interlacedColor: "rgba(1,77,101,.2)",
                gridColor: "rgba(1,77,101,.1)",
                title: "Hours",
                labelFontSize: 12,
                titleFontSize: 12,
                minimum: 0,
                maximum: <?php echo $workHours; ?>
            },
            legend: {
                fontSize: 12
            },
            toolTip: {
                shared: true
            },
            // height: chartHeight,
            data: [{
                type: "bar",
                name: "Hours",
                color: "#808080",
                toolTipContent: "{label}<br/><span style='\"'color: #808080;'\"'>Percentage</span> : {y}hours from {task} tasks",
                showInLegend: true,
                dataPoints: <?php echo json_encode($dataTimeWorkHours, JSON_NUMERIC_CHECK); ?>
            }]
        });
        //increasing order
        // chart2.options.data[0].dataPoints.sort(compareDataPointYAscend);
        chart2.render();

        var chart3 = new CanvasJS.Chart("chartContainer3", {
            animationEnabled: true,
            exportEnabled: true,
            // title: {
            //     text: "Category B",
            //     fontSize: 30
            // },
            subtitles: [{
                text: "The number of working hours for Project",
                fontSize: 24
            },
            {
                text: "is less than Non-Project",
                fontSize: 24
            }],
            axisX: {
                interval: 1,
                labelFontSize: 12
            },
            axisY: {
                interlacedColor: "rgba(1,77,101,.2)",
                gridColor: "rgba(1,77,101,.1)",
                title: "Hours",
                labelFontSize: 12,
                titleFontSize: 12,
                minimum: 0
            },
            legend: {
                fontSize: 12
            },
            toolTip: {
                shared: true
            },
            // height: chartHeight,
            data: [{
                    type: "bar",
                    name: "Project",
                    color: "#0047ab", //"#014D65",
                    showInLegend: true,
                    yValueFormatString: "###0.00 hours",
                    dataPoints: <?php echo json_encode($dataTimeWorkHoursLess1, JSON_NUMERIC_CHECK); ?>
                },
                {
                    type: "bar",
                    name: "Non-Project",
                    color: "#21abcd", //"#339900",
                    showInLegend: true,
                    yValueFormatString: "###0.00 hours",
                    dataPoints: <?php echo json_encode($dataTimeWorkHoursLess2, JSON_NUMERIC_CHECK); ?>
                }
            ]
        });
        //increasing order
        // chart3.options.data[0].dataPoints.sort(compareDataPointYAscend);
        chart3.render();

        var chart4 = new CanvasJS.Chart("chartContainer4", {
            animationEnabled: true,
            exportEnabled: true,
            // title: {
            //     text: "Category C",
            //     fontSize: 30
            // },
            subtitles: [{
                text: "Resource consistency in task updates",
                fontSize: 20
            }],
            axisX: {
                interval: 1,
                labelFontSize: 12
            },
            legend: {
                fontSize: 12
            },
            axisY: {
                interlacedColor: "rgba(1,77,101,.2)",
                gridColor: "rgba(1,77,101,.1)",
                title: "% (percentage)",
                labelFontSize: 12,
                titleFontSize: 12,
                interval: 10,
                minimum: 0,
                maximum: 100
            },
            toolTip: {
                shared: true,
            },
            // height: chartHeight,
            data: [{
                type: "bar",
                name: "Percentage",
                showInLegend: true,
                color: "#800080",
                yValueFormatString: "###0.00",
                toolTipContent: "{label}<br/><span style='\"'color: #014D65;'\"'>Percentage</span> : {y}% from {task} tasks",
                dataPoints: <?php echo json_encode($dataTimeConsistence, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chart4.render();

        function toolTipFormatter(e) {
            var str = "";
            var total = 0;
            var str3;
            var str2;
            for (var i = 0; i < e.entries.length; i++) {
                var str1 = "<span style= \"color:" + e.entries[i].dataSeries.color + "\">" + e.entries[i].dataSeries.name + "</span>: <strong>" + e.entries[i].dataPoint.y + "</strong> <br/>";
                total = e.entries[i].dataPoint.y + total;
                str = str.concat(str1);
            }
            str2 = "<strong>" + e.entries[0].dataPoint.label + "</strong> <br/>";
            str3 = "<span style = \"color:Tomato\">Total: </span><strong>" + total + "</strong><br/>";
            return (str2.concat(str)).concat(str3);
        }

        function toggleDataSeries(e) {
            if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                e.dataSeries.visible = false;
            } else {
                e.dataSeries.visible = true;
            }
            chart.render();
        }
    }
</script>
