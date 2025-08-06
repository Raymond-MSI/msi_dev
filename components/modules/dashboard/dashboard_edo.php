<script>
$(document).ready(function () {
    $('#EDOSummary').DataTable({
        dom: 'rtip',
        ordering: false
    });
});

$(document).ready(function () {
    $('#EDOApproved').DataTable({
        dom: 'rtip',
        ordering: false
    });
});

$(document).ready(function () {
    $('#EDOSubmitted').DataTable({
        dom: 'rtip',
        ordering: false
    });
});

$(document).ready(function () {
    $('#EDORejected').DataTable({
        dom: 'rtip',
        ordering: false
    });
});


</script>

<?php
$mdlname = "HCM";
$DBEDO = get_conn($mdlname);

$ord = $DBEDO->get_leader_v2($_SESSION['Microservices_UserEmail']);



$tblname = "trx_edo_request";
?>
<div class="row">
    <div class="col-lg-6">
        <?php menu_dashboard(); ?>
    </div>
    <div class="col-lg-6">
        <!-- <div class="col-lg-12">
            <div class="col-lg-12 text-right"> -->
                <!-- Button trigger modal -->
                <!-- <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#filter">
                <i class="fa-solid fa-filter"></i>
                </button> -->
            <!-- </div>
        </div> -->
    </div>
</div>

<div class="row">
    <?php 
    if($_SESSION['Microservices_UserLevel']=="Administrator")
    {
        $employee_name = '%%';
        $mysql = sprintf("SELECT COUNT(IF(`status`='drafted',1, NULL)) AS `drafted`, COUNT(IF(`status`='edo submitted', 1, NULL)) AS `edo_submitted`, COUNT(IF(`status`='edo rejected', 1, NULL)) AS `edo_rejected`, COUNT(IF(`status`='request approved',1,NULL)) AS `request_approved`, COUNT(IF(`status`='leave submitted', 1,NULL)) AS `leave_submitted`, COUNT(IF(`status`='leave rejected', 1,NULL)) AS `leave_rejected`, COUNT(IF(`status`='completed', 1,NULL)) AS `completed`, COUNT(IF(`status`='completed with paid', 1,NULL)) AS `completed_with_paid` FROM `sa_trx_edo_request` WHERE `employee_name` LIKE %s;  ",
            GetSQLValueString($employee_name, "text")
        );
        $mysqlpaid = sprintf("SELECT count(edo_id) AS `total` FROM sa_trx_edo_request WHERE status = 'completed with paid' AND YEAR(performed_date) = %s;",
            GetSQLValueString(date("Y"), "text")
        );
    } else
    {
        $employee_name = $_SESSION['Microservices_UserName'] . "<" . $_SESSION['Microservices_UserEmail'] . ">";
        $mysql = sprintf("SELECT `employee_name`, COUNT(IF(`status`='drafted',1, NULL)) AS `drafted`, COUNT(IF(`status`='edo submitted', 1, NULL)) AS `edo_submitted`, COUNT(IF(`status`='edo rejected', 1, NULL)) AS `edo_rejected`, COUNT(IF(`status`='request approved',1,NULL)) AS `request_approved`, COUNT(IF(`status`='leave submitted', 1,NULL)) AS `leave_submitted`, COUNT(IF(`status`='leave rejected', 1,NULL)) AS `leave_rejected`, COUNT(IF(`status`='completed', 1,NULL)) AS `completed`, COUNT(IF(`status`='completed with paid', 1,NULL)) AS `completed_with_paid` FROM `sa_trx_edo_request` WHERE `employee_name` LIKE %s GROUP BY `employee_name`;  ",
            GetSQLValueString($employee_name, "text")
        );
        $mysqlpaid = sprintf("SELECT count(edo_id) AS `total` FROM sa_trx_edo_request WHERE status = 'completed with paid' AND YEAR(performed_date) = %s AND `employee_name` LIKE %s;",
        GetSQLValueString(date("Y"), "text"),
        GetSQLValueString($employee_name, "text")
        );
    }
    
    $results = $DBEDO->get_sql($mysql);

    $drafted = 0;
    $waiting = 0;
    $approved = 0;
    $rejected = 0;
    $completed = 0;
    if($results[2]>0)
    {
        $drafted = $results[0]['drafted'];
        $waiting = $results[0]['edo_submitted'] + $results[0]['leave_submitted'];
        $approved = $results[0]['request_approved'];
        $rejected = $results[0]['edo_rejected'] + $results[0]['leave_rejected'];
        $completed = $results[0]['completed'];
        $paid = $results[0]['completed_with_paid'];
    }

    $paid = 0;
    $paids = $DBEDO->get_sql($mysqlpaid);
    if($paids[2]>0)
    {
        $paid = $paids[0]['total'];
    }

    content("Draft", $drafted, "primary", "2"); 
    content("Waiting Approval", $waiting, "info", "2"); 
    content("Approved", $approved, "warning", "2"); 
    content("Rejected", $rejected, "danger", "2"); 
    content("Completed", $completed, "success", "2"); 
    content("Paid " . date("Y", strtotime("-1 year")), $paid, "secondary", "2"); 
    ?>
</div>

<div class="row mb-3" style="font-size:13px">
    <div class="col-lg-6">

        <?php
        $mysql = sprintf("SELECT SUM(IF(`status` = 'edo requested', 1, 0)) AS `edo_submitted`, SUM(IF(`status` = 'request approved', 1, 0)) AS `edo_approved`, SUM(IF(`status` = 'leave submitted', 1, 0)) AS `leave_submitted`, SUM(IF(`status` = 'completed', 1, 0)) AS `completed` FROM `sa_trx_edo_request` WHERE `employee_name` LIKE %s; ",
            GetSQLValueString($employee_name, "text")
        );
        $results = $DBEDO->get_sql($mysql);
        ?>

        <div class="card shadow mb-3">
            <div class="card-header fw-bold">
                EDO Summary
            </div>
            <div class="card-body">
                <?php
                if($results[2]>0 && ($results[0]['edo_submitted'] + $results[0]['edo_approved'] + $results[0]['leave_submitted'] + $results[0]['completed'])>0)
                {
                    $dataEDOSummary = array();
                    $xxx = array("label"=> "EDO Submitted", "y"=> $results[0]['edo_submitted']);
                    array_push($dataEDOSummary, $xxx);
                    $xxx = array("label"=> "EDO Approved", "y"=> $results[0]['edo_approved']);
                    array_push($dataEDOSummary, $xxx);
                    $xxx = array("label"=> "Leave Submitted", "y"=> $results[0]['leave_submitted']);
                    array_push($dataEDOSummary, $xxx);
                    $xxx = array("label"=> "EDO Completed", "y"=> $results[0]['completed']);
                    array_push($dataEDOSummary, $xxx);
                    ?>
                    <div id="chartEDOSummary" style="height: 370px; width: 100%;"></div>
                    <?php
                } else
                {
                   ?>
                    <div style="height: 370px; width: 100%;">No data to display.</div>
                   <?php
                }
                ?>
            </div>
        <!-- </div>

        <div class="card shadow mb-3">
            <div class="card-header fw-bold">
                EDO Summary
            </div> -->
            <div class="card-body">
                <table class="table table-striped" id="EDOSummary">
                    <thead><tr><th>Employee Name</th><th class="text-center">EDO Submitted</th><th class="text-center">EDO Approved</th><th class="text-center">Leave Submitted</th><th class="text-center">Leave Approved</th></tr></thead>
                    <tbody>
                        <?php
                        $mysql = sprintf("SELECT `employee_name`, SUM(IF(`status` = 'edo requested', 1, 0)) AS `edo_submitted`, SUM(IF(`status` = 'request approved', 1, 0)) AS `edo_approved`, SUM(IF(`status` = 'leave submitted', 1, 0)) AS `leave_submitted`, SUM(IF(`status` = 'completed', 1, 0)) AS `completed` FROM `sa_trx_edo_request` WHERE `employee_name` LIKE %s GROUP BY `employee_name`; ",
                        GetSQLValueString($employee_name, "text")
                    );
                        $results = $DBEDO->get_sql($mysql);
                        if($results[2]>0)
                            {
                            ?>
                            <?php do { ?>
                                <tr>
                                    <td class="pt-0 pb-0"><?php echo $results[0]['employee_name']; ?></td>
                                    <td class="pt-0 pb-0 text-center"><?php echo $results[0]['edo_submitted']; ?></td>
                                    <td class="pt-0 pb-0 text-center"><?php echo $results[0]['edo_approved']; ?></td>
                                    <td class="pt-0 pb-0 text-center"><?php echo $results[0]['leave_submitted']; ?></td>
                                    <td class="pt-0 pb-0 text-center"><?php echo $results[0]['completed']; ?></td>
                                </tr>
                            <?php } while($results[0]=$results[1]->fetch_assoc()); ?>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <div class="col-lg-6">
        <div class="card shadow mb-3">
            <div class="card-header fw-bold">
                EDO Submitted
            </div>
            <div class="card-body">
                <table class="table table-striped table-borderless" id="EDOSubmitted">
                    <thead>
                        <tr><th>Employee Name</th><th>Start Date</th><th>End Date</th><th>Leave Start</th><th>Leave End</th></tr>
                    </thead>
                    <tbody>
                        <?php
                        $condition = sprintf("(`status`= 'edo submitted' OR `status`= 'leave submitted') AND `employee_name`LIKE %s",
                            GetSQLValueString($employee_name, "text")
                        );
                        $order = "`employee_name` ASC, `start_date` ASC";
                        $results = $DBEDO->get_data($tblname, $condition, $order);
                        if($results[2]>0)
                        {
                            ?>
                            <?php do { ?>
                                <tr>
                                    <td class="pt-0 pb-0"><a href="index.php?mod=hcm&sub=edo&act=edit&edo_id=<?php echo $results[0]['edo_id']; ?>&submit=Submit" class="text-decoration-none text-<?php echo LINK_COLOR; ?>"><?php echo $results[0]['employee_name']; ?></a></td>
                                    <td class="pt-0 pb-0"><?php echo $results[0]['start_date']; ?></td>
                                    <td class="pt-0 pb-0"><?php echo $results[0]['end_date']; ?></td>
                                    <td class="pt-0 pb-0"><?php echo $results[0]['actual_start']; ?></td>
                                    <td class="pt-0 pb-0"><?php echo $results[0]['actual_end']; ?></td>
                                </tr>
                            <?php } while($results[0]=$results[1]->fetch_assoc()); ?>
                            <?php
                        } else
                        {
                            ?>
                            <tr><td colspan="5">No data to display.</td></tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card shadow mb-3">
            <div class="card-header fw-bold">
                EDO Approved
            </div>
            <div class="card-body">
                <table class="table table-striped table-borderless" id="EDOApproved">
                    <thead>
                        <tr><th>Employee Name</th><th>Start Date</th><th>End Date</th><th>Duration</th></tr>
                    </thead>
                    <tbody>
                        <?php
                        $condition = sprintf("`status`= 'request approved' AND `employee_name` LIKE %s",
                            GetSQLValueString($employee_name, "text")
                        );
                        $order = "`employee_name` ASC, `start_date` ASC";
                        $results = $DBEDO->get_data($tblname, $condition, $order);
                        if($results[2]>0)
                        {
                            ?>
                            <?php do { ?>
                                <tr>
                                    <td class="pt-0 pb-0"><a href="index.php?mod=hcm&sub=edo&act=edit&edo_id=<?php echo $results[0]['edo_id']; ?>&submit=Submit" class=" text-decoration-none text-<?php echo LINK_COLOR; ?>"><?php echo $results[0]['employee_name']; ?></a></td>
                                    <td class="pt-0 pb-0"><?php echo $results[0]['start_date']; ?></td>
                                    <td class="pt-0 pb-0"><?php echo $results[0]['end_date']; ?></td>
                                    <td class="pt-0 pb-0"><?php echo $results[0]['duration']; ?></td>
                                </tr>
                            <?php } while($results[0]=$results[1]->fetch_assoc()); ?>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-header fw-bold">
                EDO Rejected
            </div>
            <div class="card-body">
                <table class="table table-striped table-borderless" id="EDORejected">
                    <thead>
                        <tr><th>Employee Name</th><th>Start Date</th><th>End Date</th><th>Duration</th></tr>
                    </thead>
                    <tbody>
                        <?php
                        // $condition = sprintf("(`status`= 'edo rejected' OR `status`= 'leave rejected') AND `employee_name`LIKE %s AND YEAR(`start_date`) = %s",
                        //     GetSQLValueString($employee_name, "text"),
                        //     GetSQLValueString(date("Y"), "date")
                        // );
                        $order = "`employee_name` ASC, `start_date` ASC";
                        $condition = sprintf("(`status`= 'edo rejected' OR `status`= 'leave rejected') AND `employee_name`LIKE %s",
                            GetSQLValueString($employee_name, "text")
                        );
                        $results = $DBEDO->get_data($tblname, $condition, $order);
                        if($results[2]>0)
                        {
                            ?>
                            <?php do { ?>
                                <tr>
                                    <td class="pt-0 pb-0"><a href="index.php?mod=hcm&sub=edo&act=edit&edo_id=<?php echo $results[0]['edo_id']; ?>&submit=Submit" class=" text-decoration-none text-<?php echo LINK_COLOR; ?>"><?php echo $results[0]['employee_name']; ?></a></td>
                                    <td class="pt-0 pb-0"><?php echo $results[0]['start_date']; ?></td>
                                    <td class="pt-0 pb-0"><?php echo $results[0]['end_date']; ?></td>
                                    <td class="pt-0 pb-0"><?php echo $results[0]['duration']; ?></td>
                                </tr>
                            <?php } while($results[0]=$results[1]->fetch_assoc()); ?>
                            <?php
                        } else
                        {
                            ?>
                            <tr><td colspan="4">No data to display.</td></tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
window.onload = function() {
 
var chart = new CanvasJS.Chart("chartEDOSummary", {
	theme: "light2",
	animationEnabled: true,
	// title: {
	// 	text: "Average Composition of Magma"
	// },
	data: [{
		type: "doughnut",
        startAngle: -90,
        radius: "100%",
        innerRadius: "50%",
		indexLabel: "{y}",
		yValueFormatString: "#,##0",
		showInLegend: true,
		legendText: "{label}",
		dataPoints: <?php echo json_encode($dataEDOSummary, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();
 
}
</script>