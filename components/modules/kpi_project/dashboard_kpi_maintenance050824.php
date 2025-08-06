<?php
$DBKPI = get_conn("DASHBOARD_KPI");
$sbsb = "SERVICE_BUDGET";
$DBSB = get_conn($sbsb);

$wrwr = "WRIKE_INTEGRATE";
$DBWR = get_conn($wrwr);

$survey = "SURVEY";
$DBSV = get_conn($survey);

$db_name = "HCM";
$DBHCM = get_conn($db_name);

$crcr = "CHANGE_REQUEST";
$DBCR = get_conn($crcr);

function get_subordinate($email)
{
    global $DBHCM, $MyEmail;
    $mysql = sprintf(
        // "SELECT `a`.`employee_name` AS `employee_name`,`a`.`email` AS `employee_email`,`c`.`description` AS `job_title`,`b`.`level` AS `level`,`b`.`description` AS `job_structure`,`a`.`resign_date` AS `resign_date`,`e`.`employee_name` AS `ordinate_name`,`e`.`email` AS `ordinate_email`,`g`.`description` AS `ordinate_job_title`,`f`.`level` AS `ordinate_level`,`f`.`description` AS `ordinate_job_structure`, `h`.`organization_name` from ((((((`sa_md_hcm`.`sa_mst_employees` `a` LEFT JOIN `sa_md_hcm`.`sa_mst_job` `b` ON(`a`.`job_id` = `b`.`job_id`)) LEFT JOIN `sa_md_hcm`.`sa_mst_jobtitle` `c` ON(`b`.`job_title_id` = `c`.`job_title_id`)) LEFT JOIN `sa_md_hcm`.`sa_mst_job` `d` ON(`d`.`parent_id` = `b`.`job_id`)) LEFT JOIN `sa_md_hcm`.`sa_mst_employees` `e` ON(`d`.`job_id` = `e`.`job_id`)) LEFT JOIN `sa_md_hcm`.`sa_mst_job` `f` ON(`e`.`job_id` = `f`.`job_id`)) LEFT JOIN `sa_md_hcm`.`sa_mst_jobtitle` `g` ON(`f`.`job_title_id` = `g`.`job_title_id`)) LEFT JOIN `sa_mst_organization` `h` ON `e`.`organization_id` = `h`.`organization_id` WHERE `e`.`resign_date` IS NULL AND `e`.`employee_name` IS NOT NULL AND `a`.`email` = %s ORDER BY `f`.`level`,`b`.`level`; ",
        "SELECT `a`.`employee_name` AS `employee_name`,`a`.`email` AS `employee_email`,`c`.`description` AS `job_title`,`b`.`level` AS `level`,`b`.`description` AS `job_structure`,`a`.`resign_date` AS `resign_date`,`e`.`employee_name` AS `ordinate_name`,`e`.`email` AS `ordinate_email`,`g`.`description` AS `ordinate_job_title`,`f`.`level` AS `ordinate_level`,`f`.`description` AS `ordinate_job_structure`, `h`.`organization_name` from ((((((`sa_md_hcm`.`sa_mst_employees` `a` LEFT JOIN `sa_md_hcm`.`sa_mst_job` `b` ON(`a`.`job_id` = `b`.`job_id`)) LEFT JOIN `sa_md_hcm`.`sa_mst_jobtitle` `c` ON(`b`.`job_title_id` = `c`.`job_title_id`)) LEFT JOIN `sa_md_hcm`.`sa_mst_job` `d` ON(`d`.`parent_id` = `b`.`job_id`)) LEFT JOIN `sa_md_hcm`.`sa_mst_employees` `e` ON(`d`.`job_id` = `e`.`job_id`)) LEFT JOIN `sa_md_hcm`.`sa_mst_job` `f` ON(`e`.`job_id` = `f`.`job_id`)) LEFT JOIN `sa_md_hcm`.`sa_mst_jobtitle` `g` ON(`f`.`job_title_id` = `g`.`job_title_id`)) LEFT JOIN `sa_mst_organization` `h` ON `e`.`organization_id` = `h`.`organization_id` WHERE `e`.`resign_date` IS NULL AND `e`.`employee_name` IS NOT NULL AND `a`.`email` = %s ORDER BY `e`.`employee_name`; ",
        GetSQLValueString($email, "text")
    );
    $result = $DBHCM->get_sql($mysql);
    return $result;
}

if (isset($_GET['project_type'])) {
    $project_type = $_GET['project_type'];
} else {
    $project_type = "Maintenance";
}

if (isset($_GET['periode_so'])) {
    $periode_so = $_GET['periode_so'];
} else {
    $periode_so = DATE("Y-m-d");
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
<!DOCTYPE HTML>
<html>
<style>
    .table-responsive {
        max-height: 400px;
        /* Adjust as necessary */
        overflow-y: auto;
        overflow-x: auto;
    }

    #ProjectDetail {
        width: 100%;
        border-collapse: collapse;
    }

    #ProjectDetail th,
    #ProjectDetail td {
        border: 1px solid #ddd;
        /* Add borders to the table cells */
        padding: 8px;
        text-align: left;
    }

    #ProjectDetail th {
        background-color: #f2f2f2;
        text-align: center;
    }

    .modal-lg {
        max-width: 90%;
    }

    .highlighted-row {
        border-top: 5px solid black;
        border-left: 5px solid black;
        border-right: 5px solid black;
        border-bottom: 5px solid black;
    }
</style>
<?php
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

    if ($review) {
        if (isset($_GET["periode_review"]) && $sub != "periode_review") {
            $link .= "&periode_review=" . $_GET["periode_review"];
        } else
        if ($sub == "periode_review") {
            $link .= "&periode_review=" . $value;
        }
    }

    return $link;
}
?>
<?php
$username = $_SESSION['Microservices_UserEmail'];
if ($username == 'chrisheryanda@mastersystem.co.id') {
    if (!empty($_GET['start_period']) && isset($_GET['end_period'])) {
        $start_period = date("Y-m-d", strtotime($_GET['start_period']));
        $end_period = date("Y-m-d", strtotime($_GET['end_period']));
        $monitoring_date = date("Y-m-d", strtotime($_GET['end_period']));
        $get_project = $DBKPI->get_sqlV2("SELECT a.* FROM sa_kpi_so_wr a left join sa_master_maintenance_date b ON a.order_number=b.order_number WHERE a.project_type LIKE '%Maintenance%' AND (a.start_assignment <= '$end_period' AND a.start_assignment >= '$start_period')");
        $get_project2 = $DBKPI->get_sqlV2("SELECT a.* FROM sa_kpi_so_wr a left join sa_master_maintenance_date b ON a.order_number=b.order_number WHERE a.project_type LIKE '%Maintenance%' AND (a.start_assignment <= '$end_period' AND a.start_assignment >= '$start_period')");
        $get_project3 = $DBKPI->get_sqlV2("SELECT a.* FROM sa_kpi_so_wr a left join sa_master_maintenance_date b ON a.order_number=b.order_number WHERE a.project_type LIKE '%Maintenance%' AND (a.start_assignment <= '$end_period' AND a.start_assignment >= '$start_period')");
    } else {
        $start_period = date("Y-m-d");
        $end_period = date("Y-m-d");
        $get_project = $DBKPI->get_sqlV2("SELECT a.* FROM sa_kpi_so_wr a left join sa_master_maintenance_date b ON a.order_number=b.order_number WHERE a.project_type LIKE '%Maintenance%'");
        $get_project2 = $DBKPI->get_sqlV2("SELECT a.* FROM sa_kpi_so_wr a left join sa_master_maintenance_date b ON a.order_number=b.order_number WHERE a.project_type LIKE '%Maintenance%'");
        $get_project3 = $DBKPI->get_sqlV2("SELECT a.* FROM sa_kpi_so_wr a left join sa_master_maintenance_date b ON a.order_number=b.order_number WHERE a.project_type LIKE '%Maintenance%'");
    }
} else {
    $get_subordinate = get_subordinate($username);
    $resourceEmail = '';
    if ($get_subordinate[2] > 2) {
        do {
            $email = $get_subordinate[0]['ordinate_email'];
            $resourceEmail .= "c.owner_email LIKE '%$email%' OR ";
        } while ($get_subordinate[0] = $get_subordinate[1]->fetch_assoc());
        $resourceEmail = substr($resourceEmail, 0, -4);
    } else {
        $resourceEmail = "c.owner_email LIKE '%$username%'";
    }
    if (!empty($_GET['start_period']) && isset($_GET['end_period'])) {
        $start_period = date("Y-m-d", strtotime($_GET['start_period']));
        $end_period = date("Y-m-d", strtotime($_GET['end_period']));
        $monitoring_date = date("Y-m-d", strtotime($_GET['end_period']));
        $get_project = $DBKPI->get_sqlV2("SELECT a.*,c.owner_email FROM sa_kpi_so_wr a left join sa_master_maintenance_date b ON a.order_number=b.order_number left join sa_wrike_integrate.sa_wrike_project_list c ON a.order_number=c.order_number WHERE a.project_type LIKE '%Maintenance%' AND c.project_type LIKE '%Maintenance%' AND (a.end_assignment <= '$end_period' AND a.start_assignment >= '$start_period') AND ($resourceEmail)");
        $get_project2 = $DBKPI->get_sqlV2("SELECT a.*,c.owner_email FROM sa_kpi_so_wr a left join sa_master_maintenance_date b ON a.order_number=b.order_number left join sa_wrike_integrate.sa_wrike_project_list c ON a.order_number=c.order_number WHERE a.project_type LIKE '%Maintenance%' AND c.project_type LIKE '%Maintenance%' AND (a.end_assignment <= '$end_period' AND a.start_assignment >= '$start_period') AND ($resourceEmail)");
        $get_project3 = $DBKPI->get_sqlV2("SELECT a.*,c.owner_email FROM sa_kpi_so_wr a left join sa_master_maintenance_date b ON a.order_number=b.order_number left join sa_wrike_integrate.sa_wrike_project_list c ON a.order_number=c.order_number WHERE a.project_type LIKE '%Maintenance%' AND c.project_type LIKE '%Maintenance%' AND (a.end_assignment <= '$end_period' AND a.start_assignment >= '$start_period') AND ($resourceEmail)");
    } else {
        $start_period = date("Y-m-d");
        $end_period = date("Y-m-d");
        $get_project = $DBKPI->get_sqlV2("SELECT a.*,c.owner_email FROM sa_kpi_so_wr a left join sa_master_maintenance_date b ON a.order_number=b.order_number left join sa_wrike_integrate.sa_wrike_project_list c ON a.order_number=c.order_number WHERE a.project_type LIKE '%Maintenance%' AND c.project_type LIKE '%Maintenance%' AND ($resourceEmail)");
        $get_project2 = $DBKPI->get_sqlV2("SELECT a.*,c.owner_email FROM sa_kpi_so_wr a left join sa_master_maintenance_date b ON a.order_number=b.order_number left join sa_wrike_integrate.sa_wrike_project_list c ON a.order_number=c.order_number WHERE a.project_type LIKE '%Maintenance%' AND c.project_type LIKE '%Maintenance%' AND ($resourceEmail)");
        $get_project3 = $DBKPI->get_sqlV2("SELECT a.*,c.owner_email FROM sa_kpi_so_wr a left join sa_master_maintenance_date b ON a.order_number=b.order_number left join sa_wrike_integrate.sa_wrike_project_list c ON a.order_number=c.order_number WHERE a.project_type LIKE '%Maintenance%' AND c.project_type LIKE '%Maintenance%' AND ($resourceEmail)");
    }
}
$gm = $DBHCM->get_sqlV2("SELECT * FROM sa_view_employees WHERE employee_email='$username' AND resign_date is null");
?>

<head>
    <div class="row mb-3">
        <div class="col-lg-3">

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
                <form name="form" method="get" action="index.php">
                    <input type="hidden" name="mod" value="kpi_project">
                    <input type="hidden" name="sub" value="dashboard_kpi_maintenance">
                    <div class="">
                        <div class="row g-2">
                            <label for="inputPassword" class="col-auto col-form-label col-form-label-sm">From
                                :</label>
                            <div class="row mb-2">
                                <div class="col-auto">
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                        <input type="text" class="form-control form-control-sm" id="start_period" name="start_period" placeholder="Start Date." value="<?php echo date("d-M-Y", strtotime($start_period)); ?>" onchange="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <div class="row g-2">
                            <label for="inputPassword" class="col-auto col-form-label col-form-label-sm">To</label>
                            <div class="row mb-2">
                                <div class="col-auto">
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                        <input type="text" class="form-control form-control-sm" id="end_period" name="end_period" placeholder="End Date." value="<?php echo date("d-M-Y", strtotime($end_period)); ?>" onchange="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <div class="row g-2">
                            <div class="row mb-2">
                                <div class="col-auto">
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <span class="glyphicon glyphicon-th"></span>
                                        </div>
                                        <input type="submit" class="btn btn-primary" name="btn_search" id="btn_search" value="Go">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</head>

<body>
    <div class="row">
        <div class="row mb-3">
            <div id="chartContainer"></div>
        </div>
    </div>
    <div class="row">
        <div class="row mb-10">
            <table class="display" id="ProjectDetail" style="width:100%">
                <thead>
                    <tr class="text-center">
                        <th>No</th>
                        <th>Nama Customer</th>
                        <th>Nama Project</th>
                        <th>Type</th>
                        <th>Periode</th>
                        <th>Nilai KPI</th>
                        <th>Weighted Value</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $x = 0;
                    while ($row = $get_project3[1]->fetch_assoc()) {
                        $x++;
                        $customer = $row['customer_name'];
                        $project_name = $row['project_name'];
                        $service_type = $row['service_type'];
                        $start = date("d-M-Y", strtotime($row['start_assignment']));
                        $end = date("d-M-Y", strtotime($row['end_assignment']));;
                        $total_cte = $row['total_cte'];
                        $cte = $row['cte'];
                        $order_number = $row['order_number'];
                        $renewal_category = $row['renewal_category'];
                        $renewal_kpi = $row['renewal_kpi'];
                        $renewal_actual = 10 - $renewal_kpi;
                        $time_category = $row['time_category'];
                        $time_kpi = $row['time_kpi'];
                        $time_actual = 20 - $time_kpi;
                        $error_category = $row['error_category'];
                        $error_kpi = $row['error_kpi'];
                        $error_actual = 20 - $error_kpi;
                        $compliance_category = $row['data_compliance_category'];
                        $compliance_kpi = $row['data_compliance_kpi'];
                        $compliance_actual = 10 - $compliance_kpi;
                        $backup_category = $row['backup_config_category'];
                        $backup_kpi = $row['backup_config_kpi'];
                        $backup_actual = 10 - $backup_kpi;
                        $adoption_category = $row['adoption_category'];
                        $adoption_kpi = $row['adoption_kpi'];
                        $adoption_actual = 30 - $adoption_kpi;
                        $weighted_value = $row['weighted_value'];
                        $get_data_so = $DBKPI->get_sql("SELECT * FROM sa_data_so WHERE order_number='$order_number'");
                        $value = "Rp." . number_format($get_data_so[0]['SB_maintenance_price'], 0, ".", ".");
                        $get_resource = $DBWR->get_sqlV2("SELECT * FROM sa_resource_assignment WHERE order_number='$order_number' AND project_type='Maintenance' AND start_date is not null");
                        if ($get_resource[2] > 0) {
                            $resources = '';
                            $project_roles = '';
                            $members_period = '';
                            $members_status = '';
                            $resources_detail = '';
                            $resources_detailraw = '';
                            while ($get_resource[0] = $get_resource[1]->fetch_assoc()) {
                                $resourceraw = explode("<", $get_resource[0]['resource_email']);
                                $resource_name = $resourceraw[0];
                                $resources .= $resource_name . ", ";
                                $project_role = $get_resource[0]['project_roles'];
                                $project_roles .= $project_role . ", ";
                                $member_period = date("d-M-Y", strtotime($get_resource[0]['start_date'])) . " ~ " . date("d-M-Y", strtotime($get_resource[0]['end_date']));
                                $members_period .= $member_period . ", ";
                                $status_member = $get_resource[0]['status'];
                                $members_status .= $status_member . ", ";
                                $resources_detailraw .= "<tr><td>$resource_name&nbsp;&nbsp;</td><td>$project_role</td><td>$status_member</td><td>$member_period&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>";
                            }
                            $resources = substr($resources, 0, -2);
                            $project_roles = substr($project_roles, 0, -2);
                            $members_period = substr($members_period, 0, -2);
                            $members_status = substr($members_status, 0, -2);
                            $resources_detail = $resources_detailraw;
                        } else {
                            $resources = '';
                            $project_roles = '';
                            $members_period = '';
                            $members_status = '';
                            $resources_detailraw = "<tr><td colspan='4'>No data available</td></tr>";
                        }
                        $get_data_ticket = $DBKPI->get_sql("SELECT * FROM sa_master_maintenance_date WHERE order_number='$order_number'");
                        if (empty($get_data_ticket[0]['order_number'])) {
                            $jumlah_ticket = '';
                            $jumlah_preventive = '';
                            $jumlah_report = '';
                            $info_gap = '';
                            $aktual_ticket = '';
                            $aktual_preventive = '';
                            $aktual_report = '';
                        } else {
                            $get_report_aktual = $DBKPI->get_sqlV2("SELECT order_number,COUNT(project_code) as jumlah_report FROM sa_maintenance_date_kpi WHERE order_number='$order_number' AND maintenance_name_file LIKE '%mt_report%'");
                            $get_preventive_aktual = $DBKPI->get_sqlV2("SELECT order_number,COUNT(project_code) as jumlah_report FROM sa_maintenance_date_kpi WHERE order_number='$order_number' AND maintenance_name_file LIKE '%preventive%'");
                            $getTicketAktual = $DBCR->get_sqlV2("SELECT b.project_code,b.order_number,b.so_number,SUM(a.used_ticket) as ticket_aktual FROM sa_change_cost_plans a left join sa_general_informations b ON a.cr_no=b.cr_no WHERE b.change_request_type='Maintenance' AND b.order_number='$order_number' GROUP BY b.order_number,b.change_request_type");
                            if (empty($get_report_aktual[0]['jumlah_report'])) {
                                $aktual_report = 0;
                            } else {
                                $aktual_report = $get_report_aktual[0]['jumlah_report'];
                            }
                            if (empty($get_preventive_aktual[0]['jumlah_report'])) {
                                $aktual_preventive = 0;
                            } else {
                                $aktual_preventive = $get_preventive_aktual[0]['jumlah_report'];
                            }
                            if (empty($getTicketAktual[0]['ticket_aktual'])) {
                                $aktual_ticket = 0;
                            } else {
                                $aktual_ticket = $getTicketAktual[0]['ticket_aktual'];
                            }
                            $jumlah_ticket = $get_data_ticket[0]['total_ticket_allocation'] . " | " . $aktual_ticket;
                            $jumlah_preventive = $get_data_ticket[0]['total_preventive_mt_date'] . " | " . $aktual_preventive;
                            $jumlah_report = $get_data_ticket[0]['total_mt_report_date'] . " | " . $aktual_report;
                            $info_gap = $get_data_ticket[0]['ontime_notes'] . " - " . $get_data_ticket[0]['notes'];
                        }
                        $baseline_report_maintenance = $DBKPI->get_sqlV2("SELECT * FROM sa_maintenance_date WHERE order_number='$order_number' AND id_date LIKE '%mt_report%'");
                        $report_maintenance = $DBKPI->get_sqlV2("SELECT a.*,b.maintenance_aktual_date,b.maintenance_category from sa_maintenance_date a left join sa_maintenance_date_kpi b ON a.order_number=b.order_number AND a.id_date=b.maintenance_name_file WHERE a.id_date LIKE '%mt_report%' AND a.order_number='$order_number'");
                        if (isset($baseline_report_maintenance[0]['order_number'])) {
                            $a = 0;
                            $table_report = '';
                            while ($row = $report_maintenance[1]->fetch_assoc()) {
                                $a++;
                                $maintenance_plan_date = $row['date'];
                                if (empty($row['maintenance_aktual_date'])) {
                                    $maintenance_aktual_date = 'File belum diupload';
                                    $maintenance_category = '';
                                } else {
                                    $maintenance_aktual_date = date("d-M-Y", strtotime($row['maintenance_aktual_date']));
                                    $maintenance_category = $row['maintenance_category'];
                                }
                                $table_report .= "<tr><td>$a</td><td>$maintenance_plan_date</td><td>$maintenance_aktual_date</td><td>$maintenance_category</td></tr>";
                            }
                        } else {
                            $table_report = "<tr><td colspan='4'>No data available</td></tr>";
                        }
                        $baseline_preventive_maintenance = $DBKPI->get_sqlV2("SELECT * FROM sa_maintenance_date WHERE order_number='$order_number' AND id_date LIKE '%preventive%'");
                        $preventive_maintenance = $DBKPI->get_sqlV2("SELECT a.*,b.maintenance_aktual_date,b.maintenance_category from sa_maintenance_date a left join sa_maintenance_date_kpi b ON a.order_number=b.order_number AND a.id_date=b.maintenance_name_file WHERE a.id_date LIKE '%preventive%' AND a.order_number='$order_number'");
                        if (isset($baseline_preventive_maintenance[0]['order_number'])) {
                            $a = 0;
                            $table_preventive = '';
                            while ($row = $preventive_maintenance[1]->fetch_assoc()) {
                                $a++;
                                $preventive_plan_date = date("d-M-Y", strtotime($row['date']));
                                if (empty($row['maintenance_aktual_date'])) {
                                    $preventive_aktual_date = 'File belum diupload';
                                    $preventive_category = '';
                                } else {
                                    $preventive_aktual_date = date("d-M-Y", strtotime($row['maintenance_aktual_date']));
                                    $preventive_category = $row['maintenance_category'];
                                }
                                $table_preventive .= "<tr><td>$a</td><td>$preventive_plan_date</td><td>$preventive_aktual_date</td><td>$preventive_category</td></tr>";
                            }
                        } else {
                            $table_preventive = "<tr><td colspan='4'>No data available</td></tr>";
                        }
                        $preventive_maintenance2 = $DBKPI->get_sqlV2("SELECT a.*,b.maintenance_aktual_date,b.maintenance_category from sa_maintenance_date a left join sa_maintenance_date_kpi b ON a.order_number=b.order_number AND a.id_date=b.maintenance_name_file WHERE a.id_date LIKE '%preventive%' AND a.order_number='$order_number'");
                        if (isset($baseline_preventive_maintenance[0]['order_number'])) {
                            $a = 1;
                            if (isset($get_data_so[0]['maintenance_kom_date'])) {
                                $maintenance_kom_date2raw = date("d-M-Y", strtotime($get_data_so[0]['maintenance_kom_date']));
                                $initial_date = new DateTime($maintenance_kom_date2raw);

                                // Clone the DateTime object to avoid modifying the initial date
                                $modified_date = clone $initial_date;
                                $modified_date->modify('+28 days'); // Add 28 days

                                // Format the modified date to the desired format
                                $maintenance_kom_date2 = $modified_date->format('d-M-Y');
                            } else {
                                $maintenance_kom_date2 = 'Tidak ada tanggal KOM';
                            }
                            if (isset($get_data_so[0]['maintenance_config_backup_actual_date'])) {
                                $maintenance_config_backup_actual_date2 = date("d-M-Y", strtotime($get_data_so[0]['maintenance_config_backup_actual_date']));
                            } else {
                                $maintenance_config_backup_actual_date2 = 'File belum diupload';
                            }
                            $tambahan = '';
                            while ($row = $preventive_maintenance2[1]->fetch_assoc()) {
                                $a++;
                                $preventive_plan_date = date("d-M-Y", strtotime($row['date']));
                                if (empty($row['maintenance_aktual_date'])) {
                                    $preventive_aktual_date = 'File belum diupload';
                                    $preventive_category = '';
                                } else {
                                    $preventive_aktual_date = 'File belum diupload';
                                    $preventive_category = '';
                                }
                                $tambahan .= "<tr><td>$a</td><td>$preventive_plan_date</td><td>$preventive_aktual_date</td><td>$preventive_category</td></tr>";
                            }
                            $table_backup = "<tr><td>1</td><td>$maintenance_kom_date2</td><td></td></tr>$tambahan";
                        } else {
                            $table_backup = "<tr><td colspan='4'>No data available</td></tr>";
                        }
                        $cekcr_so = $DBCR->get_sqlV2("SELECT * FROM sa_general_informations WHERE order_number='$order_number' AND (cr_no LIKE '%MT%') AND change_request_status");
                        if (isset($cekcr_so[0]['cr_no'])) {
                            $i = 0;
                            $table_cr = '';
                            while ($cekcr_so[0] = $cekcr_so[1]->fetch_assoc()) {
                                $i++;
                                $cr_no = $cekcr_so[0]['cr_no'];
                                $getTicketAktual2 = $DBCR->get_sqlV2("SELECT b.project_code,b.order_number,b.so_number,SUM(a.used_ticket) as ticket_aktual FROM sa_change_cost_plans a left join sa_general_informations b ON a.cr_no=b.cr_no WHERE b.change_request_type='Maintenance' AND b.order_number='$order_number' AND a.cr_no='$cr_no' GROUP BY b.order_number,b.change_request_type");
                                if (empty($getTicketAktual2[0]['ticket_aktual'])) {
                                    $ticket_aktual = 0;
                                } else {
                                    $ticket_aktual = $getTicketAktual2[0]['ticket_aktual'];
                                }
                                $date = date("d-M-Y", strtotime($cekcr_so[0]['request_date']));
                                $scope = $cekcr_so[0]['scope_of_change'];
                                $status1 = $cekcr_so[0]['change_request_status'];
                                if ($status1 == "submission_to_be_reviewed") {
                                    $status = "Open";
                                } else if ($status1 == "Canceled") {
                                    $status = "Cancel";
                                } else {
                                    $status = "Closed";
                                }
                                $table_cr .= "<tr><td>$cr_no</td><td>$date</td><td>$scope</td><td>$ticket_aktual</td><td>$status</td></tr>";
                            }
                        } else {
                            $table_cr = "<tr><td colspan='5'>No data available</td></tr>";
                        }
                        $get_tanggal_kom = $DBKPI->get_sql("SELECT * from sa_maintenance_date WHERE id_date='kom' and order_number='$order_number'");
                        $get_tanggal_ikom = $DBKPI->get_sql("SELECT * from sa_maintenance_date WHERE id_date='ikom' and order_number='$order_number'");
                        $get_tanggal_assignment = $DBKPI->get_sql("SELECT * from sa_maintenance_date WHERE id_date='assignment_date' and order_number='$order_number'");
                        if (empty($get_tanggal_kom[0]['date'])) {
                            $tanggal_kom = 'Tidak Ada';
                        } else {
                            $tanggal_kom = date("d-M-Y", strtotime($get_tanggal_kom[0]['date']));
                        }
                        if (empty($get_tanggal_ikom[0]['date'])) {
                            $tanggal_ikom = 'Tidak Ada';
                        } else {
                            $tanggal_ikom = date("d-M-Y", strtotime($get_tanggal_ikom[0]['date']));
                        }
                        if (empty($get_tanggal_assignment[0]['date'])) {
                            $tanggal_assignment = 'Tidak Ada';
                        } else {
                            $tanggal_assignment = date("d-M-Y", strtotime($get_tanggal_assignment[0]['date']));
                        }
                        $get_owner = $DBWR->get_sqlV2("SELECT * from sa_wrike_project_list WHERE order_number='$order_number' AND project_type LIKE '%Maintenance%'");
                        if (empty($get_owner[0]['owner_email'])) {
                            $ownerPM = 'Kosong';
                        } else {
                            if (strpos($get_owner[0]['owner_email'], ",") !== false) {
                                $ownerpmraw = explode(",", $get_owner[0]['owner_email']);
                                $nama1 = $DBHCM->get_sql("SELECT * FROM sa_view_employees WHERE employee_email='$ownerpmraw[0]'");
                                $nama2raw = ltrim($ownerpmraw[1]);
                                $nama2 = $DBHCM->get_sql("SELECT * FROM sa_view_employees WHERE employee_email='$nama2raw'");
                                if (empty($nama1[0]['employee_name'])) {
                                    $namapm1 = '';
                                } else {
                                    $namapm1 = $nama1[0]['employee_name'];
                                }
                                if (empty($nama2[0]['employee_name'])) {
                                    $namapm2 = '';
                                    $ownerPM = $namapm1;
                                } else {
                                    $namapm2 = $nama2[0]['employee_name'];
                                    $ownerPM = "$namapm1 dan $namapm2";
                                }
                            } else {
                                $nama1 = $DBHCM->get_sqlV2("SELECT * FROM sa_view_employees WHERE employee_email='" . $get_owner[0]['owner_email'] . "'");
                                $ownerPM = $nama1[0]['employee_name'];
                            }
                        }
                    ?>
                        <tr data-toggle="modal" data-target="#myModal" data-ownerpm="<?php echo $ownerPM; ?>" data-tablepreventive="<?php echo $table_preventive; ?>" data-tablecr="<?php echo $table_cr; ?>" data-tablebackup="<?php echo $table_backup; ?>" data-tablereport="<?php echo $table_report; ?>" data-resourcedetail="<?php echo htmlspecialchars($resources_detailraw, ENT_QUOTES, 'UTF-8'); ?>" data-renewalactual="<?php echo $renewal_actual; ?>" data-timeactual="<?php echo $time_actual; ?>" data-erroractual="<?php echo $error_actual; ?>" data-complianceactual="<?php echo $compliance_actual; ?>" data-backupactual="<?php echo $backup_actual; ?>" data-adoptionactual="<?php echo $adoption_actual; ?>" data-totalcte="<?php echo $total_cte; ?>" data-renewalcategory="<?php echo $renewal_category; ?>" data-renewalkpi="<?php echo $renewal_kpi; ?>" data-timecategory="<?php echo $time_category; ?>" data-timekpi="<?php echo $time_kpi; ?>" data-errorcategory="<?php echo $error_category; ?>" data-errorkpi="<?php echo $error_kpi; ?>" data-compliancecategory="<?php echo $compliance_category; ?>" data-compliancekpi="<?php echo $compliance_kpi; ?>" data-backupcategory="<?php echo $backup_category; ?>" data-backupkpi="<?php echo $backup_kpi; ?>" data-adoptioncategory="<?php echo $adoption_category; ?>" data-adoptionkpi="<?php echo $adoption_kpi; ?>" data-jumlahticket="<?php echo $jumlah_ticket; ?>" data-jumlahpreventive="<?php echo $jumlah_preventive; ?>" data-jumlahreport="<?php echo $jumlah_report; ?>" data-infogap="<?php echo $info_gap; ?>" data-tanggalkom="<?php echo $tanggal_kom; ?>" data-tanggalikom="<?php echo $tanggal_ikom; ?>" data-tanggalassignment="<?php echo $tanggal_assignment; ?>" data-projectroles="<?php echo $project_roles; ?>" data-membersstatus="<?php echo $members_status; ?>" data-membersperiod="<?php echo $members_period; ?>" data-value="<?php echo $value; ?>" data-resources="<?php echo $resources; ?>" data-customer="<?php echo $customer; ?>" data-project="<?php echo $project_name; ?>" data-type="<?php echo $service_type; ?>" data-period="<?php echo $start . ' ~ ' . $end; ?>" data-kpi="<?php echo $total_cte; ?>" data-order="<?php echo $order_number; ?>" data-weighted="<?php echo $weighted_value; ?>">
                            <td><?php echo $x; ?></td>
                            <td><?php echo $customer; ?></td>
                            <td><?php echo $project_name; ?></td>
                            <td><?php echo $service_type; ?></td>
                            <td><?php echo $start . " ~ " . $end; ?></td>
                            <td><?php echo $total_cte; ?></td>
                            <td><?php echo $weighted_value; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <style>
        .modal-xl {
            max-width: 4500px;
            /* Anda bisa mengubah nilai ini sesuai kebutuhan */
        }

        .modal-body {
            max-height: 4500px;
            /* Sesuaikan nilai ini jika modal terlalu tinggi */
            overflow-y: auto;
        }
    </style>
    <link rel="stylesheet" href="path/to/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <script src="path/to/jquery.js"></script>
    <script src="path/to/bootstrap.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <h4 class="modal-title">Project Profile</h4>
                    <div class="table-responsive">
                        <table class="display table table-bordered" id="ProjectDetail" style="width:100%">
                            <thead>
                                <tr class="text-center">
                                    <th>Project Information</th>
                                    <th>Type</th>
                                    <th>Periode</th>
                                    <th>Member</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div style="font-size:13px">
                                            <span class="text-nowrap">Customer Name : <span id="modalCustomer"></span>
                                            </span><br>
                                            <span class="text-nowrap">Project Name : <span id="modalProject">
                                                </span></span><br>
                                            <span class="text-nowrap">Value : <span id="modalValue"></span></span><br>
                                            <span class="text-nowrap">Owner Project : <span id="modalOwner">
                                                </span></span>
                                        </div>
                                    </td>
                                    <td><span id="modalType"></td>
                                    <td><span id="modalPeriod"></td>
                                    <td>
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="ProjectDetail">
                                                <thead>
                                                    <tr>
                                                        <th>Resources</th>
                                                        <th>Role</th>
                                                        <th>Status</th>
                                                        <th>Start-End</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="modalResourceDetail">
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="row mb-10">
                            <div class="table-responsive">
                                <table class="display table table-bordered" id="ProjectDetail" style="width:100%">
                                    <thead>
                                        <tr class="text-center">
                                            <th>Jumlah Report</th>
                                            <th>Jumlah Preventive</th>
                                            <th>Jumlah Ticket</th>
                                            <th>Data Compliance</th>
                                            <th>Backup Config</th>
                                            <th>Tanggal Assignment</th>
                                            <th>Info GAP (Sales, Customer, Impl)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="text-align: center;"><span id="modalJumlahReport"></span></td>
                                            <td style="text-align: center;"><span id="modalJumlahPreventive"></span>
                                            </td>
                                            <td style="text-align: center;"><span id="modalJumlahTicket"></span></td>
                                            <td><span id="modalTanggalIkom"></span></td>
                                            <td><span id="modalTanggalKom"></span></td>
                                            <td><span id="modalTanggalAssignment"></span></td>
                                            <td><span id="modalInfoGap"></span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="row mb-10">
                            <h4 class="modal-title">KPI Achievement</h4>
                            <div class="table-responsive">
                                <table class="display table table-bordered" id="ProjectDetail" style="width:100%">
                                    <thead>
                                        <tr class="text-center">
                                            <th>KPI Parameter</th>
                                            <th>Finding</th>
                                            <th>% Finding</th>
                                            <th>Total KPI Target</th>
                                            <th>Actual KPI Pencapaian</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1. Renewal</td>
                                            <td><span id="modalRenewalCategory"></span></td>
                                            <td style="text-align: center;"><span id="modalRenewalKpi"></span></td>
                                            <td style="text-align: center;">10</td>
                                            <td style="text-align: center;"><span id="modalRenewalActual"></span></td>
                                        </tr>
                                        <tr>
                                            <td>2. Time</td>
                                            <td><span id="modalTimeCategory"></span></td>
                                            <td style="text-align: center;"><span id="modalTimeKpi"></span></td>
                                            <td style="text-align: center;">20</td>
                                            <td style="text-align: center;"><span id="modalTimeActual"></span></td>
                                        </tr>
                                        <tr>
                                            <td>3. Error</td>
                                            <td><span id="modalErrorCategory"></span></td>
                                            <td style="text-align: center;"><span id="modalErrorKpi"></span></td>
                                            <td style="text-align: center;">20</td>
                                            <td style="text-align: center;"><span id="modalErrorActual"></span></td>
                                        </tr>
                                        <tr>
                                            <td>4. Compliance</td>
                                            <td><span id="modalComplianceCategory"></span></td>
                                            <td style="text-align: center;"><span id="modalComplianceKpi"></span></td>
                                            <td style="text-align: center;">10</td>
                                            <td style="text-align: center;"><span id="modalComplianceActual"></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>5. Backup Config</td>
                                            <td><span id="modalBackupCategory"></span></td>
                                            <td style="text-align: center;"><span id="modalBackupKpi"></span></td>
                                            <td style="text-align: center;">10</td>
                                            <td style="text-align: center;"><span id="modalBackupActual"></span></td>
                                        </tr>
                                        <tr>
                                            <td>6. Adoption</td>
                                            <td><span id="modalAdoptionCategory"></span></td>
                                            <td style="text-align: center;"><span id="modalAdoptionKpi"></span></td>
                                            <td style="text-align: center;">30</td>
                                            <td style="text-align: center;"><span id="modalAdoptionActual"></span></td>
                                        </tr>
                                        <tr style="border-top: 3px solid black; border-left: 3px solid black; border-right: 3px solid black; border-bottom: 3px solid black;">
                                            <td colspan="4"><b>Total Pencapaian</b></td>
                                            <td style="text-align: center;"><b><span id="modalCTE"></span></b></td>
                                        </tr>
                                        <tr style="border-top: 3px solid black; border-left: 3px solid black; border-right: 3px solid black; border-bottom: 3px solid black;">
                                            <td colspan="4"><b>Weighted Value</b></td>
                                            <td style="text-align: center;"><b><span id="modalWeighted"></span></b></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="openSecondModal">Lihat
                        Detail</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="secondModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Detail</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row mb-3">
                                <div class="table-responsive">
                                    <table class="display table table-bordered" id="ProjectDetail" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th colspan="4"><b>Maintenance Report</b></th>
                                            </tr>
                                            <tr>
                                                <th>Maintenance Report ke-</th>
                                                <th>Tanggal Plan</th>
                                                <th>Tanggal Aktual</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="modalTableReport">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row mb-3">
                                <div class="table-responsive">
                                    <table class="display table table-bordered" id="ProjectDetail" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th colspan="4"><b>Preventive Maintenance</b></th>
                                            </tr>
                                            <tr>
                                                <th>Preventive Maintenance ke-</th>
                                                <th>Tanggal Plan</th>
                                                <th>Tanggal Aktual</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="modalTablePreventive">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row mb-3">
                                <div class="table-responsive">
                                    <table class="display table table-bordered" id="ProjectDetail" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th colspan="4"><b>Backup Config</b></th>
                                            </tr>
                                            <tr>
                                                <th>Backup Config ke-</th>
                                                <th>Tanggal Plan</th>
                                                <th>Tanggal Aktual</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="modalTableBackup">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row mb-3">
                                <div class="table-responsive">
                                    <table class="display table table-bordered" id="ProjectDetail" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th colspan="5"><b>List Change Request</b></th>
                                            </tr>
                                            <tr>
                                                <th>CR-No</th>
                                                <th>Tanggal CR</th>
                                                <th>Scope of Change</th>
                                                <th>Jumlah Ticket (Aktual)</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="modalTableCr">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js">
    </script>
    <script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <!-- Buttons CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
    <!-- Font Awesome CSS (for icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <!-- Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <!-- JSZip (required for Excel export) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <!-- Excel Button JS -->
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <?php $dataPoints = [];
    while ($row = $get_project[1]->fetch_assoc()) {
        $dataPoints[] = [
            'label' => $row['project_name'],
            'y' => $row['total_cte']
        ];
    }
    ?>
    <script>
        var dataPoints = <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>;
        console.log(dataPoints); // Tambahkan ini untuk memeriksa output
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#ProjectDetail').DataTable({
                "pageLength": 10,
                "paging": true,
                "searching": true,
                "info": true,
                "dom": 'Blfrtip',
                "buttons": [{
                    extend: 'excelHtml5',
                    text: "<i class='fa-solid fa-file-excel'></i>",
                    title: 'excel',
                }]
            });
        });

        window.onload = function() {
            if (dataPoints.length < 5) {
                var baseHeightPerDataPoint = 400;
            } else if (dataPoints.length < 10) {
                var baseHeightPerDataPoint = 70;
            } else {
                var baseHeightPerDataPoint = 30;
            }
            // Hitung tinggi total berdasarkan jumlah data points
            var totalHeight = dataPoints.length * baseHeightPerDataPoint;

            // Atur tinggi container chart
            document.getElementById("chartContainer").style.height = totalHeight + "px";

            var chart = new CanvasJS.Chart("chartContainer", {

                animationEnabled: true,
                exportEnabled: true,
                title: {
                    text: "Category D",
                    fontSize: 30
                },
                subtitles: [{
                    text: "Resources that never update",
                    fontSize: 16
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
                    labelFontSize: 12,
                    titleFontSize: 12,
                    interval: 10,
                    minimum: 0,
                    maximum: 100
                },
                toolTip: {
                    shared: true,
                },
                data: [{
                    type: "bar",
                    name: "Implementation",
                    color: "#014D65",
                    yValueFormatString: "###0.00",
                    dataPoints: dataPoints
                }]
            });
            chart.render();
        }
        jQuery(function() {

            jQuery('#start_period').datetimepicker({
                format: 'd-M-Y',
                timepicker: false,
                onShow: function(ct) {
                    this.setOptions({})
                }
            });
            jQuery('#end_period').datetimepicker({
                format: 'd-M-Y',
                timepicker: false,
                onShow: function(ct) {
                    this.setOptions({})
                }
            });

        });
    </script>
</body>
<script>
    $('#myModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var customer = button.data('customer');
        var project = button.data('project');
        var type = button.data('type');
        var period = button.data('period');
        var kpi = button.data('kpi');
        var order = button.data('order');
        var value = button.data('value');
        var resources = button.data('resources');
        var projectroles = button.data('projectroles');
        var membersperiod = button.data('membersperiod');
        var membersstatus = button.data('membersstatus');
        var jumlahticket = button.data('jumlahticket');
        var jumlahpreventive = button.data('jumlahpreventive');
        var jumlahreport = button.data('jumlahreport');
        var infogap = button.data('infogap');
        var tanggalkom = button.data('tanggalkom');
        var tanggalikom = button.data('tanggalikom');
        var tanggalassignment = button.data('tanggalassignment');
        var renewalcategory = button.data('renewalcategory');
        var renewalkpi = button.data('renewalkpi');
        var timecategory = button.data('timecategory');
        var timekpi = button.data('timekpi');
        var errorcategory = button.data('errorcategory');
        var errorkpi = button.data('errorkpi');
        var compliancecategory = button.data('compliancecategory');
        var compliancekpi = button.data('compliancekpi');
        var backupcategory = button.data('backupcategory');
        var backupkpi = button.data('backupkpi');
        var adoptioncategory = button.data('adoptioncategory');
        var adoptionkpi = button.data('adoptionkpi');
        var cte = button.data('totalcte');
        var renewalactual = button.data('renewalactual');
        var timeactual = button.data('timeactual');
        var erroractual = button.data('erroractual');
        var complianceactual = button.data('complianceactual');
        var backupactual = button.data('backupactual');
        var adoptionactual = button.data('adoptionactual');
        var resourcedetail = button.data('resourcedetail');
        var weighted = button.data('weighted');
        var tablereport = button.data('tablereport');
        var tablepreventive = button.data('tablepreventive');
        var tablebackup = button.data('tablebackup');
        var tablecr = button.data('tablecr');
        var ownerpm = button.data('ownerpm');

        var modal = $(this);
        modal.find('#modalOwner').text(ownerpm);
        modal.find('#modalCustomer').text(customer);
        modal.find('#modalProject').text(project);
        modal.find('#modalType').text(type);
        modal.find('#modalPeriod').text(period);
        modal.find('#modalKpi').text(kpi);
        modal.find('#modalOrder').text(order);
        modal.find('#modalValue').text(value);
        modal.find('#modalResources').text(resources);
        modal.find('#modalProjectRoles').text(projectroles);
        modal.find('#modalMembersPeriod').text(membersperiod);
        modal.find('#modalMembersStatus').text(membersstatus);
        modal.find('#modalJumlahTicket').text(jumlahticket);
        modal.find('#modalJumlahPreventive').text(jumlahpreventive);
        modal.find('#modalJumlahReport').text(jumlahreport);
        modal.find('#modalInfoGap').text(infogap);
        modal.find('#modalTanggalKom').text(tanggalkom);
        modal.find('#modalTanggalIkom').text(tanggalikom);
        modal.find('#modalTanggalAssignment').text(tanggalassignment);
        modal.find('#modalRenewalCategory').text(renewalcategory);
        modal.find('#modalRenewalKpi').text(renewalkpi);
        modal.find('#modalTimeCategory').text(timecategory);
        modal.find('#modalTimeKpi').text(timekpi);
        modal.find('#modalErrorCategory').text(errorcategory);
        modal.find('#modalErrorKpi').text(errorkpi);
        modal.find('#modalComplianceCategory').text(compliancecategory);
        modal.find('#modalComplianceKpi').text(compliancekpi);
        modal.find('#modalBackupCategory').text(backupcategory);
        modal.find('#modalBackupKpi').text(backupkpi);
        modal.find('#modalAdoptionCategory').text(adoptioncategory);
        modal.find('#modalAdoptionKpi').text(adoptionkpi);
        modal.find('#modalCTE').text(cte);
        modal.find('#modalRenewalActual').text(renewalactual);
        modal.find('#modalTimeActual').text(timeactual);
        modal.find('#modalErrorActual').text(erroractual);
        modal.find('#modalComplianceActual').text(complianceactual);
        modal.find('#modalBackupActual').text(backupactual);
        modal.find('#modalAdoptionActual').text(adoptionactual);
        modal.find('#modalResourceDetail').html(resourcedetail);
        modal.find('#modalWeighted').text(weighted);
        modal.find('#modalTableReport').html(tablereport);
        modal.find('#modalTablePreventive').html(tablepreventive);
        modal.find('#modalTableBackup').html(tablebackup);
        modal.find('#modalTableCr').html(tablecr);

        $('#openSecondModal').data('tablereport', tablereport);
        $('#openSecondModal').data('tablepreventive', tablepreventive);
        $('#openSecondModal').data('tablebackup', tablebackup);
        $('#openSecondModal').data('tablecr', tablecr);
    });
    $('#secondModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var tablereport = button.data('tablereport');
        var tablepreventive = button.data('tablepreventive');
        var tablebackup = button.data('tablebackup');
        var tablecr = button.data('tablecr');

        var modal = $(this);
        modal.find('#modalTableReport').html(tablereport);
        modal.find('#modalTablePreventive').html(tablepreventive);
        modal.find('#modalTableBackup').html(tablebackup);
        modal.find('#modalTableCr').html(tablecr);
    });

    // Open modal2 from modal1 button
    $('#openSecondModal').on('click', function() {
        var tablereport = $(this).data('tablereport');
        var tablepreventive = $(this).data('tablepreventive');
        var tablebackup = $(this).data('tablebackup');
        var tablecr = $(this).data('tablecr');


        $('#myModal').modal('hide'); // Hide the first modal
        setTimeout(function() { // Wait for the first modal to be completely hidden
            $('#secondModal').modal('show');
            $('#secondModal').find('#modalTableReport').html(tablereport);
            $('#secondModal').find('#modalTablePreventive').html(tablepreventive);
            $('#secondModal').find('#modalTableBackup').html(tablebackup);
            $('#secondModal').find('#modalTableCr').html(tablecr);
        }, 500); // Adjust the timeout duration if needed
    });
</script>

</html>