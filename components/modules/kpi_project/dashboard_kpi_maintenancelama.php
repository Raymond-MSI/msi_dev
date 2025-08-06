<link href="components/modules/resource_assignment/select2.min.css" rel="stylesheet" />
<!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Bundle with Popper.js (includes both Bootstrap JS and Popper.js) -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
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

$DBGC = get_conn("GOOGLE_CALENDAR");
include("components/modules/dashboard/func_dashboard.php");
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
if (isset($_GET['project_code'])) {
    $project_code1 = $_GET['project_code'];
} else {
    $project_code1 = "Maintenance";
}
if (isset($_GET['project_manager'])) {
    $project_manager = $_GET['project_manager'];
} else {
    $project_manager = null;
}
if (isset($_GET['order_number'])) {
    $order_number = $_GET['order_number'];
} else {
    $order_number = null;
}
if (isset($_GET['project_name'])) {
    $project_name = $_GET['project_name'];
} else {
    $project_name = null;
}
if (isset($_GET['periode_review'])) {
    $periode_review = $_GET['periode_review'];
} else {
    $periode_review = "Kosong";
}
if (isset($_GET['status_review'])) {
    $kpi_status = $_GET['status_review'];
} else {
    $kpi_status = "Not Yet Reviewed";
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

    #ProjectDetail2 {
        width: 100%;
        border-collapse: collapse;
    }

    #ProjectDetail2 th,
    #ProjectDetail2 td {
        border: 1px solid #ddd;
        /* Add borders to the table cells */
        padding: 8px;
        text-align: left;
    }

    #ProjectDetail2 th {
        background-color: #f2f2f2;
        text-align: center;
    }

    #ProjectDetail3 {
        width: 100%;
        border-collapse: collapse;
    }

    #ProjectDetail3 th,
    #ProjectDetail3 td {
        border: 1px solid #ddd;
        /* Add borders to the table cells */
        padding: 8px;
        text-align: left;
    }

    #ProjectDetail3 th {
        background-color: #f2f2f2;
        text-align: center;
    }

    .highlight {
        background-color: lightblue;
    }

    .highlight2 {
        background-color: green;
    }
</style>
<?php
function content2($title, $value, $listData = array(), $color = "danger", $width = "12", $tiptool = "", $fontsize = "h4", $py = "2")
{
?>
    <div
        class="col border-left-<?php echo htmlspecialchars($color); ?> p-3 ml-3 mr-3 h-100 py-<?php echo htmlspecialchars($py); ?>">
        <span class="text-<?php echo htmlspecialchars($color); ?> text-uppercase text-xs font-weight-bold mb-1">
            <?php echo htmlspecialchars($title); ?>
        </span><br>
        <span
            class="<?php echo htmlspecialchars($fontsize); ?> mb-0 mr-3 font-weight-bold text-gray-800 d-flex flex-row align-items-center justify-content-between">
            <?php
            if (count($listData) > 0) {
            ?>
                <div class="dropdown no-arrow" id="<?php echo htmlspecialchars($tiptool); ?>">
                    <a class="dropdown-toggle text-reset text-decoration-none" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo htmlspecialchars($value); ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-left shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Select item:</div>
                        <?php
                        foreach ($listData as $item) {
                            $resource = isset($_GET['resource']) ? "&resource=" . urlencode($_GET['resource']) : '';
                        ?>
                            <a class="dropdown-item"
                                href="index.php?mod=kpi_project&sub=<?php echo urlencode($_GET['sub']) . htmlspecialchars($item['link']) . htmlspecialchars($resource); ?>">
                                <?php echo htmlspecialchars($item['value']); ?>
                            </a>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            <?php
            } else {
                echo htmlspecialchars($value);
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
        $link .= "&project_type=" . urlencode($_GET["project_type"]);
    } elseif ($sub == "project_type") {
        $link .= "&project_type=" . urlencode($value);
    }

    if (isset($_GET["periode_so"]) && $sub != "periode_so") {
        $link .= "&periode_so=" . urlencode($_GET["periode_so"]);
    } elseif ($sub == "periode_so") {
        $link .= "&periode_so=" . urlencode($value);
    }

    if (isset($_GET["status_review"]) && $sub != "status_review") {
        $link .= "&status_review=" . urlencode($_GET["status_review"]);
        if ($_GET['status_review'] == "Reviewed") {
            $review = true;
        }
    } elseif ($sub == "status_review") {
        $link .= "&status_review=" . urlencode($value);
        if ($value == "Reviewed") {
            $review = true;
        }
    }

    if (isset($review) && $review) {
        if (isset($_GET["periode_review"]) && $sub != "periode_review") {
            $link .= "&periode_review=" . urlencode($_GET["periode_review"]);
        } elseif ($sub == "periode_review") {
            $link .= "&periode_review=" . urlencode($value);
        }
    }

    return $link;
}
?>

<?php
$username = $_SESSION['Microservices_UserEmail'];
if ($username == 'chrisheryanda@mastersystem.co.id' || $username == 'syamsul@mastersystem.co.id') {
    if (empty($order_number)) {
        $orderNumber = '';
    } else {
        $orderNumber = "AND a.order_number='$order_number'";
    }
    if (empty($project_manager)) {
        $projectManager = '';
    } else {
        $projectManager = "AND c.owner_email LIKE '%$project_manager%'";
    }
    if (empty($project_name)) {
        $projectName = '';
    } else {
        $projectName = "AND a.project_name LIKE '%$project_name%'";
    }
    $get_project = $DBKPI->get_sqlV2("SELECT a.* FROM sa_kpi_so_wr a left join sa_master_maintenance_date b ON a.order_number=b.order_number WHERE a.project_type LIKE '%Maintenance%' $orderNumber $projectManager $projectName");
    $get_project2 = $DBKPI->get_sqlV2("SELECT a.* FROM sa_kpi_so_wr a left join sa_master_maintenance_date b ON a.order_number=b.order_number WHERE a.project_type LIKE '%Maintenance%' $orderNumber $projectManager $projectName");
    $get_project3 = $DBKPI->get_sqlV2("SELECT a.* FROM sa_kpi_so_wr a left join sa_master_maintenance_date b ON a.order_number=b.order_number WHERE a.project_type LIKE '%Maintenance%' $orderNumber $projectManager $projectName");
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
    if (empty($order_number)) {
        $orderNumber = '';
    } else {
        $orderNumber = "AND a.order_number='$order_number'";
    }
    if (empty($project_manager)) {
        $projectManager = '';
    } else {
        $projectManager = "AND c.owner_email LIKE '%$project_manager%'";
    }
    if (empty($project_name)) {
        $projectName = '';
    } else {
        $projectName = "AND a.project_name LIKE '%$project_name%'";
    }
    $get_project = $DBKPI->get_sqlV2("SELECT a.*,c.owner_email FROM sa_kpi_so_wr a left join sa_master_maintenance_date b ON a.order_number=b.order_number left join sa_wrike_integrate.sa_wrike_project_list c ON a.order_number=c.order_number WHERE a.project_type LIKE '%Maintenance%' AND c.project_type LIKE '%Maintenance%' AND ($resourceEmail)");
    $get_project2 = $DBKPI->get_sqlV2("SELECT a.*,c.owner_email FROM sa_kpi_so_wr a left join sa_master_maintenance_date b ON a.order_number=b.order_number left join sa_wrike_integrate.sa_wrike_project_list c ON a.order_number=c.order_number WHERE a.project_type LIKE '%Maintenance%' AND c.project_type LIKE '%Maintenance%' AND ($resourceEmail)");
    $get_project3 = $DBKPI->get_sqlV2("SELECT a.*,c.owner_email FROM sa_kpi_so_wr a left join sa_master_maintenance_date b ON a.order_number=b.order_number left join sa_wrike_integrate.sa_wrike_project_list c ON a.order_number=c.order_number WHERE a.project_type LIKE '%Maintenance%' AND c.project_type LIKE '%Maintenance%' AND ($resourceEmail)");
}
$gm = $DBHCM->get_sqlV2("SELECT * FROM sa_view_employees WHERE employee_email='$username' AND resign_date is null");
?>

<head>
    <div class="row mb-3">
        <div class="col-lg-3">
            <?php menu_dashboard(); ?>
        </div>
        <div class="col-lg-9">
            <div class="d-flex flex-row">
                <div class="">
                    <div
                        class="col border-left-danger p-3 ml-3 mr-3 h-100 py-2">
                        <span
                            class="text-danger text-uppercase text-xs font-weight-bold mb-1">
                            Project Manager
                        </span><br>
                        <span
                            class="h4 mb-0 mr-3 font-weight-bold text-gray-800 d-flex flex-row align-items-center justify-content-between">
                            <?php
                            if (empty($_GET['resource'])) {
                            ?>
                                <select id="projectManager" class="form-control form-control-sm" name="projectManager" onchange="onChangeFunction()">
                                    <option value=""></option>
                                    <?php
                                    $select_all = $DBKPI->get_sqlV2("SELECT * FROM sa_user WHERE project_type LIKE '%Maintenance%' AND role='PIC Maintenance' GROUP BY Nama");
                                    while ($row = $select_all[1]->fetch_assoc()) {
                                        $nama = $row['Nama'];
                                        $email = $row['resource_email'];
                                    ?>
                                        <option value="<?php echo $email ?>"><?php echo $nama; ?></option>
                                    <?php } ?>
                                </select>
                                <!-- <div id="projectCodee" class="dropdown no-arrow" id="ProjectCode">
                                    <a class="dropdown-toggle text-reset text-decoration-none" href="#" role="button"
                                        id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        --- Pilih Project Code ---
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-left shadow animated--fade-in"
                                        aria-labelledby="dropdownMenuLink">
                                        <div class="dropdown-header">Select item:</div>
                                        <?php
                                        $select_all = $DBKPI->get_sqlV2("SELECT * FROM sa_kpi_so_wr WHERE project_type LIKE '%Maintenance%'");
                                        while ($row = $select_all[1]->fetch_assoc()) {
                                            $project_code = $row['project_code'];
                                            $so_number = $row['so_number'];
                                            $order_number = $row['order_number'];
                                        ?>
                                            <a class="dropdown-item"
                                                href="index.php?mod=kpi_project&sub=<?php echo urlencode($_GET['sub']); ?>&order_number=<?php echo $order_number; ?>">
                                                <?php echo $project_code . "-" . $so_number . "-" . $order_number; ?>
                                            </a>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div> -->
                            <?php
                            } else { ?>
                                <select id="projectCodee" class="form-control form-control-sm" name="projectCodee" onchange="onChangeFunction()">
                                    <?php $email = $_GET['resource'];
                                    $get_data = $DBKPI->get_sqlV2("SELECT * FROM sa_user WHERE resource_email='$email'"); ?>
                                    <option value="<?php echo $_GET['resource']; ?>"><?php echo $get_data[0]['Nama']; ?></option>
                                    <?php
                                    $select_all = $DBKPI->get_sqlV2("SELECT * FROM sa_user WHERE project_type LIKE '%Maintenance%' AND role='PIC Maintenance' GROUP BY Nama");
                                    while ($row = $select_all[1]->fetch_assoc()) {
                                        $nama = $row['Nama'];
                                        $email = $row['resource_email'];
                                    ?>
                                        <option value="<?php echo $email ?>"><?php echo $nama; ?></option>
                                    <?php } ?>
                                </select>
                            <?php }
                            ?>
                        </span>
                    </div>
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
                    }
                    ?>
                </div>
                <div class="">
                    <div
                        class="col border-left-danger p-3 ml-3 mr-3 h-100 py-2">
                        <span
                            class="text-danger text-uppercase text-xs font-weight-bold mb-1">
                            Project Code
                        </span><br>
                        <span
                            class="h4 mb-0 mr-3 font-weight-bold text-gray-800 d-flex flex-row align-items-center justify-content-between">
                            <?php
                            if (empty($_GET['order_number'])) {
                            ?>
                                <select id="projectCodee" class="form-control form-control-sm" name="projectCodee" onchange="onChangeFunction()">
                                    <option value=""></option>
                                    <?php
                                    $select_all = $DBKPI->get_sqlV2("SELECT * FROM sa_kpi_so_wr WHERE project_type LIKE '%Maintenance%'");
                                    while ($row = $select_all[1]->fetch_assoc()) {
                                        $project_code = $row['project_code'];
                                        $so_number = $row['so_number'];
                                        $order_number = $row['order_number'];
                                    ?>
                                        <option value="<?php echo $order_number ?>"><?php echo $project_code . "-" . $so_number . "-" . $order_number; ?></option>
                                    <?php } ?>
                                </select>
                                <!-- <div id="projectCodee" class="dropdown no-arrow" id="ProjectCode">
                                    <a class="dropdown-toggle text-reset text-decoration-none" href="#" role="button"
                                        id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        --- Pilih Project Code ---
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-left shadow animated--fade-in"
                                        aria-labelledby="dropdownMenuLink">
                                        <div class="dropdown-header">Select item:</div>
                                        <?php
                                        $select_all = $DBKPI->get_sqlV2("SELECT * FROM sa_kpi_so_wr WHERE project_type LIKE '%Maintenance%'");
                                        while ($row = $select_all[1]->fetch_assoc()) {
                                            $project_code = $row['project_code'];
                                            $so_number = $row['so_number'];
                                            $order_number = $row['order_number'];
                                        ?>
                                            <a class="dropdown-item"
                                                href="index.php?mod=kpi_project&sub=<?php echo urlencode($_GET['sub']); ?>&order_number=<?php echo $order_number; ?>">
                                                <?php echo $project_code . "-" . $so_number . "-" . $order_number; ?>
                                            </a>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div> -->
                            <?php
                            } else { ?>
                                <select id="projectCodee" class="form-control form-control-sm" name="projectCodee" onchange="onChangeFunction()">
                                    <?php $order = $_GET['order_number'];
                                    $get_data = $DBKPI->get_sqlV2("SELECT * FROM sa_kpi_so_wr WHERE order_number='$order' AND project_type LIKE '%Maintenance%'"); ?>
                                    <option value="<?php echo $_GET['order_number']; ?>"><?php echo $get_data[0]['project_code'] . "-" . $get_data[0]['so_number'] . "-" . $get_data[0]['order_number']; ?></option>
                                    <?php
                                    $select_all = $DBKPI->get_sqlV2("SELECT * FROM sa_kpi_so_wr WHERE project_type LIKE '%Maintenance%'");
                                    while ($row = $select_all[1]->fetch_assoc()) {
                                        $project_code = $row['project_code'];
                                        $so_number = $row['so_number'];
                                        $order_number = $row['order_number'];
                                    ?>
                                        <option value="<?php echo $order_number ?>"><?php echo $project_code . "-" . $so_number . "-" . $order_number; ?></option>
                                    <?php } ?>
                                </select>
                            <?php }
                            ?>
                        </span>
                    </div>
                </div>
                <div class="">
                    <div class="col p-3 ml-3 mr-3 h-100 py-0">
                        <span class="text-uppercase text-xs font-weight-bold mb-1">Default</span><br>
                        <span
                            class="mb-0 mr-3 font-weight-bold text-gray-800y d-flex flex-row align-items-center justify-content-between">
                            <a href="index.php?mod=kpi_project&sub=<?php echo $_GET['sub']; ?>"
                                class="text-muted text-decoration-none">Reset</a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</head>

<body>
    <div class="row">
        <div class="row mb-3">
            <h2 style="text-align: center;"></h2>
            <table class="display" id="ProjectDetail2" style="width:100%">
                <thead>
                    <tr>
                        <th style="width:50%">Project Information</th>
                        <th hidden>Project Name</th>
                        <th hidden>Customer Name</th>
                        <th hidden>Project Code</th>
                        <th hidden>SO Number</th>
                        <th hidden>Project Manager</th>
                        <th hidden>Periode Maintenance</th>
                        <th hidden>Backdate</th>
                        <th>2019</th>
                        <th>2020</th>
                        <th>2021</th>
                        <th>2022</th>
                        <th>2023</th>
                        <th>2024</th>
                        <th>2025</th>
                        <th>2026</th>
                        <th>2027</th>
                        <th>2028</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $rawProject = '';
                    if ($get_project[2] > 0) {
                        while ($row = $get_project[1]->fetch_assoc()) {
                            $project_name = $row['project_name'];
                            $project_code = $row['project_code'];
                            $order_number = $row['order_number'];
                            $so_number = $row['so_number'];
                            $customer_name = $row['customer_name'];
                            $check = $DBWR->get_sql("SELECT * FROM sa_resource_assignment WHERE project_roles='PIC Maintenance' AND order_number='$order_number' AND project_type LIKE '%Maintenance%'");
                            if (empty($check[0]['resource_email'])) {
                                $check2 = $DBWR->get_sql("SELECT * FROM sa_wrike_project_list WHERE order_number='$order_number' AND project_type LIKE '%Maintenance%'");
                                if (isset($check2[0]['owner_email'])) {
                                    $resource_email = explode(",", $check2[0]['owner_email']);
                                    $nama = $DBHCM->get_sql("SELECT * FROM sa_view_employees WHERE employee_email LIKE '%$resource_email[0]%'");
                                    $resource_name = $nama[0]['employee_name'];
                                } else {
                                    $resource_name = "Kosong";
                                }
                            } else {
                                $resource_name = $check[0]['resource_email'];
                            }
                            $periode = date("d-M-Y", strtotime($row['start_assignment'])) . " ~ " . date("d-M-Y", strtotime($row['end_assignment']));
                            $periode_raw = $row['start_assignment'] . " ~ " . $row['end_assignment'];
                            $cek_backdate = $DBKPI->get_sql("SELECT * FROM sa_master_maintenance_date WHERE order_number='$order_number'");
                            if (empty($cek_backdate[0]['order_number'])) {
                                    $backdate = "Tidak Backdate";
                            } else {
                                $ontime = $cek_backdate[0]['ontime'];
                                if ($ontime == 0) {
                                    $backdate = "Backdate (" . $cek_backdate[0]['ontime_notes'] . " - " . $cek_backdate[0]['notes'] . ")";
                                } else if ($ontime == 1) {
                                    $backdate = "Tidak Backdate";
                                }
                            }

                            $projects[] = [
                                'name' => $project_name,
                                'code' => $project_code,
                                'so_number' => $so_number,
                                'manager' => $resource_name,
                                'maintenance' => $periode,
                                'backdate' => $backdate,
                                'order_number' => $order_number,
                                'customer_name' => $customer_name,
                                'range' => $periode_raw
                            ];
                        }

                        // Tampilkan data di tabel
                        foreach ($projects as $project) {
                            echo "<tr>";
                            echo "<td>{$project['name']} <br><span class='text-nowrap' style='font-size:12px'>{$project['customer_name']} |</span> <span class='text-nowrap' style='font-size:12px'>{$project['code']} |</span> <span class='text-nowrap' style='font-size:12px'>{$project['so_number']} |</span> <span class='text-nowrap' style='font-size:12px'>{$project['order_number']} |</span> <span class='text-nowrap' style='font-size:12px'>{$project['manager']} |</span> <span class='text-nowrap' style='font-size:12px'>{$project['maintenance']} |</span> <span class='text-nowrap' style='font-size:12px'>{$project['backdate']}</span></td>";
                            echo "<td hidden>{$project['name']}</td>";
                            echo "<td hidden>{$project['customer_name']}</td>";
                            echo "<td hidden>{$project['code']}</td>";
                            echo "<td hidden>{$project['so_number']}</td>";
                            echo "<td hidden>{$project['manager']}</td>";
                            echo "<td hidden>{$project['maintenance']}</td>";
                            echo "<td hidden>{$project['backdate']}</td>";

                            // Kolom untuk tahun 2023-2027
                            for ($year = 2019; $year <= 2028; $year++) {
                                $class = '';
                                // Pecah periode maintenance menjadi awal dan akhir
                                list($start, $end) = explode(' ~ ', $project['range']);
                                $check = $DBKPI->get_sql("SELECT * FROM sa_kpi_so_wr WHERE order_number='{$project['order_number']}' AND project_type LIKE '%Maintenance%'");
                                if (empty($check[0]['tahun_review'])) {
                                    if ($year > $start && $year <= $end) {
                                        $class = 'highlight';
                                    } else if ($year == $start && $year == $end) {
                                        $class = 'highlight';
                                    }
                                } else {
                                    $tahun_review = $check[0]['tahun_review'];
                                    if ($year == $tahun_review) {
                                        $class = 'highlight2';
                                    }
                                }
                                echo "<td class='$class'></td>";
                            }
                            echo "</tr>";
                        }
                    } else { ?>
                        <tr>
                            <td></td>
                            <td hidden></td>
                            <td hidden></td>
                            <td hidden></td>
                            <td hidden></td>
                            <td hidden></td>
                            <td hidden></td>
                            <td hidden></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    <?php }
                    ?>
                </tbody>
            </table>
        </div>
	<div class="row mb-3" style="display: flex; justify-content: center; align-items: center;">
        <table id="testtable" style="text-align: center;">
            <tr>
                <td><span class="highlight"></span></td>
                <td>Not yet Reviewed</td>
                <td><span class="highlight2"></span></td>
                <td>Reviewed</td>
            </tr>
        </table>
    	</div>
    </div>
    <div class="row">
        <div class="row mb-10">

        </div>
    </div>
    <div class="row">
        <div class="row mb-10">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active text-body fw-bold" id="project-tab" data-bs-toggle="tab"
                        data-bs-target="#tabProject" type="button" role="tab" aria-controls="SBList"
                        aria-selected="true" title='SB yang masih dalam bentuk draft'>KPI Project</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-body fw-bold" id="resource-tab" data-bs-toggle="tab"
                        data-bs-target="#tabResource" type="button" role="tab" aria-controls="CTEEngineer"
                        aria-selected="false" title='SB yang sudah disubmit ke manager'>KPI Resources</button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="tabProject" role="tabpanel" aria-labelledby="Project-tab">
                    <!-- KPI Projects -->
                    <div class="card">
                        <div class="card-body">
                            <div>
                                <table class="display" id="ProjectDetail" style="width:100%">
                                    <thead>
                                        <tr class="text-center">
                                            <th>No</th>
                                            <th>Information</th>
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
                                            $projctCode = $row['project_code'];
                                            $noSO = $row['so_number'];
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
                                                    $resources_detailraw .= "<tr><td>$resource_name&nbsp;&nbsp;</td><td>$project_role</td><td>$status_member</td><td><span class='text-nowrap' style='font-size:12px'>$member_period</span>&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>";
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
                                                    $maintenance_plan_date = date("d-M-Y", strtotime($row['date']));
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
                                            <tr data-toggle="modal" data-target="#myModal"
                                                data-ownerpm="<?php echo $ownerPM; ?>"
                                                data-prjctcode="<?php echo "$projctCode | $noSO | $order_number"; ?>"
                                                data-noSO="<?php echo $noSO; ?>"
                                                data-tablepreventive="<?php echo $table_preventive; ?>"
                                                data-tablecr="<?php echo $table_cr; ?>"
                                                data-tablebackup="<?php echo $table_backup; ?>"
                                                data-tablereport="<?php echo $table_report; ?>"
                                                data-resourcedetail="<?php echo htmlspecialchars($resources_detailraw, ENT_QUOTES, 'UTF-8'); ?>"
                                                data-renewalactual="<?php echo $renewal_actual; ?>"
                                                data-timeactual="<?php echo $time_actual; ?>"
                                                data-erroractual="<?php echo $error_actual; ?>"
                                                data-complianceactual="<?php echo $compliance_actual; ?>"
                                                data-backupactual="<?php echo $backup_actual; ?>"
                                                data-adoptionactual="<?php echo $adoption_actual; ?>"
                                                data-totalcte="<?php echo $total_cte; ?>"
                                                data-renewalcategory="<?php echo $renewal_category; ?>"
                                                data-renewalkpi="<?php echo $renewal_kpi; ?>"
                                                data-timecategory="<?php echo $time_category; ?>"
                                                data-timekpi="<?php echo $time_kpi; ?>"
                                                data-errorcategory="<?php echo $error_category; ?>"
                                                data-errorkpi="<?php echo $error_kpi; ?>"
                                                data-compliancecategory="<?php echo $compliance_category; ?>"
                                                data-compliancekpi="<?php echo $compliance_kpi; ?>"
                                                data-backupcategory="<?php echo $backup_category; ?>"
                                                data-backupkpi="<?php echo $backup_kpi; ?>"
                                                data-adoptioncategory="<?php echo $adoption_category; ?>"
                                                data-adoptionkpi="<?php echo $adoption_kpi; ?>"
                                                data-jumlahticket="<?php echo $jumlah_ticket; ?>"
                                                data-jumlahpreventive="<?php echo $jumlah_preventive; ?>"
                                                data-jumlahreport="<?php echo $jumlah_report; ?>"
                                                data-infogap="<?php echo $info_gap; ?>"
                                                data-tanggalkom="<?php echo $tanggal_kom; ?>"
                                                data-tanggalikom="<?php echo $tanggal_ikom; ?>"
                                                data-tanggalassignment="<?php echo $tanggal_assignment; ?>"
                                                data-projectroles="<?php echo $project_roles; ?>"
                                                data-membersstatus="<?php echo $members_status; ?>"
                                                data-membersperiod="<?php echo $members_period; ?>"
                                                data-value="<?php echo $value; ?>"
                                                data-resources="<?php echo $resources; ?>"
                                                data-customer="<?php echo $customer; ?>"
                                                data-project="<?php echo $project_name; ?>"
                                                data-type="<?php echo $service_type; ?>"
                                                data-period="<?php echo $start . ' ~ ' . $end; ?>"
                                                data-kpi="<?php echo $total_cte; ?>"
                                                data-order="<?php echo $order_number; ?>"
                                                data-weighted="<?php echo $weighted_value; ?>">
                                                <td><?php echo $x; ?></td>
                                                <td><?php echo "$projctCode | $noSO | $order_number"; ?></td>
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
                    </div>
                </div>
                <div class="tab-pane" id="tabResource" role="tabpanel" aria-labelledby="Resource-tab">
                    <!-- KPI Resources -->
                    <div class="card">
                        <div class="card-body">
                            <table class="display compact hover" id="ProjectDetail3" style="width:100%">
                                <thead
                                    class="text-<?php echo $MainColor; ?>-emphasis bg-<?php echo $MainColor; ?>-subtle">
                                    <tr class="text-center">
                                        <th rowspan="2" class="align-middle text-warning-emphasis bg-warning-subtle">
                                            Resource Name</th>
                                        <th colspan="3" class="align-middle text-success-emphasis bg-success-subtle">
                                            Average KPI - Project Full</th>
                                        <th colspan="3" class="align-middle text-info-emphasis bg-info-subtle">Average
                                            KPI - Project (Resource Specific)</th>
                                        <th colspan="3" class="align-middle text-primary-emphasis bg-primary-subtle">
                                            Average KPI - Productivity</th>
                                    </tr>
                                    <tr class="text-center">
                                        <th class="align-middle text-success-emphasis bg-success-subtle">Total KPI
                                            Project Ideal</th>
                                        <th class="align-middle text-success-emphasis bg-success-subtle">Total KPI
                                            Project Actual</th>
                                        <th class="align-middle text-success-emphasis bg-success-subtle">Average KPI
                                            Project</th>
                                        <th class="align-middle text-info-emphasis bg-info-subtle">Total KPI Project
                                            Ideal Resource Specific</th>
                                        <th class="align-middle text-info-emphasis bg-info-subtle">Total KPI Project
                                            Actual Resource Specific</th>
                                        <th class="align-middle text-info-emphasis bg-info-subtle">Average KPI Project
                                            Resource Specific</th>
                                        <th class="align-middle text-primary-emphasis bg-primary-subtle">Total Task Plan
                                        </th>
                                        <th class="align-middle text-primary-emphasis bg-primary-subtle">Total Task
                                            Actual</th>
                                        <th class="align-middle text-primary-emphasis bg-primary-subtle">Average
                                            Productivity</th>
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
                                    $rsResources = $DBKPI->get_sql("SELECT
                                        `resource_name`,
                                        SUM(`ideal_value`) AS `ideal_value`,
                                        SUM(`actual_value`) AS `actual_value`,
                                        IF(
                                            COUNT(`average_value`)>0,
                                            SUM(`average_value`) / COUNT(`average_value`),
                                            0
                                        ) AS `average_value`,
                                        SUM(`ideal_final_value`) `ideal_final_value`,
                                        SUM(`actual_final_value`) AS `actual_final_value`,
                                        IF(
                                            COUNT(`average_final_value`)>0,
                                            SUM(`average_final_value`) / COUNT(`average_final_value`),
                                            0
                                        ) AS `average_final_value`,
                                        SUM(`total_task_plan`) AS `task_plan`,
                                        SUM(`total_task_actual`) AS `task_actual`,
                                        SUM(`average_productivity`) AS `average_productivity`,
                                        `kpi_status`,
                                        `periode_kpi`
                                    FROM
                                        `sa_kpi_dashboard_resource` `a`
                                    WHERE project_type LIKE '%Maintenance%'
                                    GROUP BY
                                        `resource_name`");
                                    if ($rsResources[2] > 0) {
                                        $tableResources = '';
                                        do {
                                    ?>
                                            <tr>
                                                <td><?php echo $rsResources[0]['resource_name']; ?></td>
                                                <td><?php echo number_format($rsResources[0]['ideal_value'], 2); ?></td>
                                                <td><?php echo number_format($rsResources[0]['actual_value'], 2); ?></td>
                                                <td><?php echo number_format($rsResources[0]['actual_value'] / $rsResources[0]['ideal_value'] * 100, 2); ?>
                                                </td>
                                                <td><?php echo number_format($rsResources[0]['ideal_final_value'], 2); ?></td>
                                                <td><?php echo number_format($rsResources[0]['actual_final_value'], 2); ?></td>
                                                <td><?php echo ($rsResources[0]['ideal_final_value'] > 0 ? number_format($rsResources[0]['actual_final_value'] / $rsResources[0]['ideal_final_value'] * 100, 2) : 0); ?>
                                                </td>
                                                <td><?php echo number_format($rsResources[0]['task_plan'], 0); ?></td>
                                                <td><?php echo number_format($rsResources[0]['task_actual'], 0); ?></td>
                                                <td><?php echo ($rsResources[0]['task_plan'] > 0 ? number_format($rsResources[0]['task_actual'] / $rsResources[0]['task_plan'] * 100, 2) : 0); ?>
                                                </td>
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
            </div>
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
                                    <th style="width: 50%;">Project Information</th>
                                    <th>Type</th>
                                    <th>Periode</th>
                                    <th>Member</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div style="font-size:13px">
                                            <span class="text-nowrap" id="modalPrjctcode"></span><br>
                                            <span class="text-nowrap">Customer Name : <span id="modalCustomer"></span>
                                            </span><br>
                                            Project Name : <span id="modalProject">
                                                </span><br>
                                            <span class="text-nowrap">Value : <span id="modalValue"></span></span><br>
                                            <span class="text-nowrap">Owner Project : <span id="modalOwner">
                                                </span></span>
                                        </div>
                                    </td>
                                    <td><span id="modalType"></td>
                                    <td><span class='text-nowrap' style='font-size:12px'><span id="modalPeriod"></span></span></td>
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
                                        <tr
                                            style="border-top: 3px solid black; border-left: 3px solid black; border-right: 3px solid black; border-bottom: 3px solid black;">
                                            <td colspan="4"><b>Total Pencapaian</b></td>
                                            <td style="text-align: center;"><b><span id="modalCTE"></span></b></td>
                                        </tr>
                                        <tr
                                            style="border-top: 3px solid black; border-left: 3px solid black; border-right: 3px solid black; border-bottom: 3px solid black;">
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
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <div class="modal fade" id="PopUpModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content" style="width:100%">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                        <div id="title"></div>
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="width:100%">
                    <div class="mb-3" id="div1"></div>
                    <div id="div2"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <!-- Buttons CSS -->
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
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
    <script src="components/modules/resource_assignment/select2.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#projectCodee').select2({
                placeholder: 'Pilih KP',
                allowClear: true
            });
        });
        $(document).ready(function() {
            $('#orderNumber').select2({
                placeholder: 'Pilih SO',
                allowClear: true
            });
        });
        $(document).ready(function() {
            $('#projectManager').select2({
                placeholder: 'Pilih PM',
                allowClear: true
            });
        });
        $(document).ready(function() {
            $('#projectName').select2({
                placeholder: 'Pilih Project Name',
                allowClear: true
            });
        });
    </script>
    <script>
        $("#projectCodee").on('change', function() {
            var order_number = $(this).val();
            var customer_name = $('#customer').val();
            window.location = window.location.pathname + "?mod=kpi_project&sub=<?php echo $_GET['sub']; ?>&order_number=" + order_number;
        })
        $("#projectManager").on('change', function() {
            var resource = $(this).val();
            var customer_name = $('#customer').val();
            window.location = window.location.pathname + "?mod=kpi_project&sub=<?php echo $_GET['sub']; ?>&resource=" + resource;
        })
    </script>
    <script>
        $("#projectCode").on('change', function() {
            var projectCode = $(this).val();
            <?php
            if (empty($_GET['order_number'])) {
                $order_number = null;
            } else {
                $order_number = $_GET['order_number'];
            }
            if (empty($_GET['project_manager'])) {
                $project_manager = null;
            } else {
                $project_manager = $_GET['project_manager'];
            }
            if (empty($_GET['project_name'])) {
                $project_name = null;
            } else {
                $project_name = $_GET['project_name'];
            }
            ?>
            window.location = window.location.pathname +
                "?mod=kpi_project&sub=".<?php echo $_GET['sub']; ?>.
            "&project_code=" +
            projectCode + "&order_number=" + <?php echo $order_number; ?> + "&project_manager=" +
                <?php echo $project_manager; ?> + "&project_name=" + <?php echo $project_name; ?>;
        })
        $("#orderNumber").on('change', function() {
            var order_number = $(this).val();
            <?php
            if (empty($_GET['order_number'])) {
                $order_number = null;
            } else {
                $order_number = $_GET['order_number'];
            }
            if (empty($_GET['project_manager'])) {
                $project_manager = null;
            } else {
                $project_manager = $_GET['project_manager'];
            }
            if (empty($_GET['project_name'])) {
                $project_name = null;
            } else {
                $project_name = $_GET['project_name'];
            }
            ?>
            window.location = window.location.pathname +
                "?mod=kpi_project&sub=".<?php echo $_GET['sub']; ?>.
            "&project_code=" +
            <?php echo $order_number; ?> + "&order_number=" + order_number + "&project_manager=" +
                <?php echo $project_manager; ?> + "&project_name=" + <?php echo $project_name; ?>;
        })
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#ProjectDetail2').DataTable({
                "pageLength": 10,
                "paging": true,
                "searching": true,
                "info": true,
                "dom": 'Blfrtip',
                "lengthMenu": [
                    [5, 10, 50, 100, -1],
                    [5, 10, 50, 100, "All"]
                ],
                "buttons": [{
                    extend: 'excelHtml5',
                    text: "<i class='fa-solid fa-file-excel'></i>",
                    title: 'excel',
                }]
            });
        });

        $(document).ready(function() {
            var tblProjectDetail = $('#ProjectDetail').DataTable({
                "pageLength": 10,
                "paging": true,
                "searching": true,
                "info": true,
                "dom": 'Blfrtip',
                "lengthMenu": [
                    [5, 10, 50, 100, -1],
                    [5, 10, 50, 100, "All"]
                ],
                "buttons": [{
                    extend: 'excelHtml5',
                    text: "<i class='fa-solid fa-file-excel'></i>",
                    title: 'excel',
                }]
            });

            var tblKPIResource = $('#ProjectDetail3').DataTable({
                "pageLength": 10,
                "paging": true,
                "searching": true,
                "info": true,
                "dom": 'Blfrtip',
                "lengthMenu": [
                    [5, 10, 50, 100, -1],
                    [5, 10, 50, 100, "All"]
                ],
                "buttons": [{
                    extend: 'excelHtml5',
                    text: "<i class='fa-solid fa-file-excel'></i>",
                    title: 'excel',
                }]
            });

            $('#ProjectDetail3 tbody').on('dblclick', 'tr', function() {
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
                $("#div2").load("components/modules/kpi_project/dashboard_kpi_resource_mt.php?resource=" + exp2[0]);
                document.getElementById("title").innerHTML = 'KPI Resource : ' + data2[0];
            });
        });
    </script>
</body>
<script>
    $('#myModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var customer = button.data('customer');
        var prjctcode = button.data('prjctcode');
        var noSO = button.data('noSO');
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
        modal.find('#modalPrjctcode').text(prjctcode);
        modal.find('#modalnoSO').text(noSO);
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