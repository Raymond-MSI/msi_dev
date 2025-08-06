<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" /> -->
<link href="components/modules/resource_assignment_mt/select2.min.css" rel="stylesheet" />
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<?php
global $DTSB;

// include_once("components/modules/resource_assignment/func_resource_assignment.php");
// include_once("components/modules/resource_assignment/class_wrike_project.php");

//include("components/modules/resource_assignment/select2.min.css");
// echo $_SESSION['Microservices_UserName'];

$db_sb = "SERVICE_BUDGET";
$DBSB = get_conn($db_sb);

$db_wr = "WRIKE_INTEGRATE";
$DBWR = get_conn($db_wr);

$db_wrkld = "WORKLOAD";
$DBWRKLD = get_conn($db_wrkld);

$db_hcm = "HCM";
$DBHCM = get_conn($db_hcm);

$db_kpi = "DASHBOARD_KPI";
$DBKPI = get_conn($db_kpi);


if ($_GET['act'] == 'edit') {
    global $DTSB;
    $condition = "id=" . $_GET['id'];
    $data = $DTSB->get_data($tblname, $condition);
    $ddata = $data[0];
    $qdata = $data[1];
    $tdata = $data[2];
}

if ($_GET['act'] == "add" || $_GET['act'] == "edit") {
    if (isset($_GET['project_code']) && isset($_GET['order_number'])) {
        $sqlLookupSelectedPC = "SELECT * FROM sa_trx_project_list WHERE project_code = '" . $_GET['project_code'] . "' AND status = 'acknowledge'";
        $selectedPC = $DBSB->get_sql($sqlLookupSelectedPC);

        $sqlLookupJobRoles = "SELECT a.project_code, a.resource_category_id, b.resource_qualification, a.brand 
        FROM sa_workload.sa_initial_jobroles AS a 
        JOIN sa_ps_service_budgets.sa_mst_resource_catalogs AS b ON a.resource_category_id = b.resource_catalog_id
        JOIN sa_wrike_integrate.sa_wrike_project_list AS c ON a.project_id = c.id
        WHERE c.order_number = '" . $_GET['order_number'] . "'";
        $selectedPCJobRoles = $DBWRKLD->get_sql($sqlLookupJobRoles);
        $rowSelectedPCJobRoles = $selectedPCJobRoles[0];
        $resSelectedPCJobRoles = $selectedPCJobRoles[1];
        $totalRowPCJobRoles = $selectedPCJobRoles[2];

        $sqlLookupJobRoles2 = "SELECT a.project_code, a.resource_category_id, b.resource_qualification, a.brand 
        FROM sa_workload.sa_initial_jobroles AS a 
        JOIN sa_ps_service_budgets.sa_mst_resource_catalogs AS b ON a.resource_category_id = b.resource_catalog_id
        JOIN sa_wrike_integrate.sa_wrike_project_list AS c ON a.project_id = c.id
        WHERE c.order_number = '" . $_GET['order_number'] . "'";
        $selectedPCJobRoles2 = $DBWRKLD->get_sql($sqlLookupJobRoles2);
        $rowSelectedPCJobRoles2 = $selectedPCJobRoles2[0];
        $resSelectedPCJobRoles2 = $selectedPCJobRoles2[1];
        $totalRowPCJobRoles2 = $selectedPCJobRoles2[2];

        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $check_periode_end = $DBKPI->get_sql("SELECT date FROM sa_maintenance_date WHERE order_number='" . $_GET['order_number'] . "' AND id_date='mt_date_end'");
        $check_periode_start = $DBKPI->get_sql("SELECT date FROM sa_maintenance_date WHERE order_number='" . $_GET['order_number'] . "' AND id_date='mt_date_start'");
        if (empty($check_periode_start[0]['date'])) {
            $periode_start = null;
        } else {
            $periode_start = $check_periode_start[0]['date'];
        }
        if (empty($check_periode_end[0]['date'])) {
            $periode_end = null;
        } else {
            $periode_end = $check_periode_end[0]['date'];
        }
    }
}
?>

<?php
if ($_GET['act'] == 'add' && isset($_GET['project_code']) || $_GET['act'] == 'edit') {
?>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active text-body" id="info-tab" data-bs-toggle="tab" data-bs-target="#Assignment"
                type="button" role="tab" aria-controls="Assignment" aria-selected="true">Assignment</button>
        </li>
        <?php if ($_GET['project_code'] != '') { ?>
            <li class="nav-item" role="presentation">
                <button class="nav-link text-body" id="mt_report-tab" data-bs-toggle="tab" data-bs-target="#mt_report"
                    type="button" role="tab" aria-controls="mt_report" aria-selected="false">Maintenance Report</button>
            </li>
        <?php } ?>
        <?php if ($_GET['project_code'] != '') { ?>
            <li class="nav-item" role="presentation">
                <button class="nav-link text-body" id="history-tab" data-bs-toggle="tab" data-bs-target="#History" type="button"
                    role="tab" aria-controls="history" aria-selected="false">History</button>
            </li>
        <?php } ?>
    </ul>
<?php
} else if ($_GET['act'] == 'add') {
?>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active text-body" id="info-tab" data-bs-toggle="tab" data-bs-target="#Assignment"
                type="button" role="tab" aria-controls="Assignment" aria-selected="true">Assignment</button>
        </li>
    </ul>
<?php
}
?>


<!-- TAB History -->
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="Assignment" role="tabpanel" aria-labelledby="assignment-tab">
        <div class="card shadow mb-4">
            <div class="card-body">
                <form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="mb-3">Information Project</h3>
                            <input type="hidden" id="emailSession"
                                value="<?php echo addslashes($_SESSION['Microservices_UserName']) . "<" . $_SESSION['Microservices_UserEmail'] . ">"; ?>" />
                            <input type="hidden" id="actualLink" value="<?php echo $actual_link; ?>">

                            <!-- Project Code -->
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Code*
                                (WAJIB DIISI)</label>
                            <div class="col-sm-12 mb-2">
                                <?php if ($_GET['act'] == "add") { ?>
                                    <?php if (isset($_GET['project_code'])) {
                                        $wrike_project = new WRIKE_PROJECT();
                                        try {
                                            $resultWrikeProject = $wrike_project->get_projectMandiri($_GET['project_code']);
                                            if ($resultWrikeProject !== true) {
                                                echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                <strong>' . $resultWrikeProject . '</strong>
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                  <span aria-hidden="true">&times;</span>
                                                </button>
                                              </div>';
                                            }
                                        } catch (Exception $e) {
                                            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <strong>' . $e->getMessage() . ' reload halaman kembali' . '</strong>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                            </button>
                                          </div>';
                                        }
                                    ?>
                                        <select id="project_code" class="form-control form-control-sm" name="projectCode"
                                            onchange="onChangeFunction()">
                                            <option value="<?php echo $_GET['project_code']; ?>">
                                                <?php echo $_GET['project_code']; ?></option>
                                            <?php
                                            $sqlQuery = "select a.* from sa_ps_service_budgets.sa_trx_project_list a inner join sa_wrike_integrate.sa_wrike_project_list b on b.project_code = a.project_code where b.project_type = 'MSI Project Maintenance' group by a.project_code";
                                            $sqlQuery2 = $DBWR->get_sqlV2("SELECT * FROM sa_wrike_project_list WHERE project_type = 'MSI Project Maintenance' and owner_email LIKE '%" . $_SESSION['Microservices_UserEmail'] . "%'");
                                            $sqlQuery3 = $DBWR->get_sqlV2("SELECT * FROM sa_resource_assignment WHERE project_roles='Project Leader' AND resource_email LIKE '%" . $_SESSION['Microservices_UserEmail'] . "%'");
                                            $sqlQuery4 = $DBWR->get_sqlV2("SELECT * FROM sa_initial_project WHERE owner_project LIKE '%" . $_SESSION['Microservices_UserEmail'] . "%'");
                                            $dataQuery = $DBSB->get_sql($sqlQuery);
                                            $rowData = $dataQuery[0];
                                            $resData = $dataQuery[1];
                                            if (
                                                $_SESSION['Microservices_UserEmail'] == 'miko.widiarta@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'david.kusuma@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'chrisheryanda@mastersystem.co.id' ||
                                                $_SESSION['Microservices_UserEmail'] == 'aryo.bimo@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'malik.aulia@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'aceng.zakariya@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'zahira.nafira@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'arieftyarto@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'agdi.rizky@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'nabella.yanza@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'rezha.aulia@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'aldo.octavianto@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'zaimatul@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'anggi.fachrizal@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'lucky.andiani@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'anita.angelina@mastersystem.co.id'
                                            ) {
                                                while ($rowData = $resData->fetch_assoc()) {
                                                    $projectCode = $rowData['project_code'];
                                            ?>
                                                    <option value="<?php echo $projectCode; ?>"><?php echo $projectCode ?></option>
                                                <?php }
                                            } else {
                                                while ($row = $sqlQuery2[1]->fetch_assoc()) {
                                                    $projectCode = $row['project_code']; ?>
                                                    <option></option>
                                                    <option value="<?php echo $projectCode; ?>"><?php echo $projectCode ?></option>
                                                <?php }
                                                while ($rows = $sqlQuery3[1]->fetch_assoc()) {
                                                    $projectCode2 = $row['project_code']; ?>
                                                    <option value="<?php echo $projectCode2; ?>"><?php echo $projectCode2 ?></option>
                                            <?php }
                                            } ?>
                                        </select>
                                    <?php } else {; ?>
                                        <select id="project_code" class="form-control form-control-sm" name="projectCode"
                                            onchange="onChangeFunction()">
                                            <?php
                                            $sqlQuery = "select a.* from sa_ps_service_budgets.sa_trx_project_list a inner join sa_wrike_integrate.sa_wrike_project_list b on b.project_code = a.project_code where b.project_type = 'MSI Project Maintenance' group by a.project_code";
                                            $sqlQuery2 = $DBWR->get_sqlV2("SELECT * FROM sa_wrike_project_list WHERE owner_email LIKE '%" . $_SESSION['Microservices_UserEmail'] . "%'");
                                            $sqlQuery3 = $DBWR->get_sqlV2("SELECT * FROM sa_resource_assignment WHERE project_roles='Project Leader' AND resource_email LIKE '%" . $_SESSION['Microservices_UserEmail'] . "%'");
                                            //$sqlQuery4 = $DBWR->get_sqlV2("SELECT * FROM sa_initial_project WHERE owner_project LIKE '%" . $_SESSION['Microservices_UserEmail'] . "%'");
                                            $dataQuery = $DBSB->get_sql($sqlQuery);
                                            $rowData = $dataQuery[0];
                                            $resData = $dataQuery[1];
                                            if (
                                                $_SESSION['Microservices_UserEmail'] == 'miko.widiarta@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'david.kusuma@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'chrisheryanda@mastersystem.co.id' ||
                                                $_SESSION['Microservices_UserEmail'] == 'aryo.bimo@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'malik.aulia@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'aceng.zakariya@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'zahira.nafira@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'arieftyarto@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'agdi.rizky@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'nabella.yanza@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'rezha.aulia@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'aldo.octavianto@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'zaimatul@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'anggi.fachrizal@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'lucky.andiani@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'anita.angelina@mastersystem.co.id'
                                            ) {
                                                while ($rowData = $resData->fetch_assoc()) {
                                                    $projectCode = $rowData['project_code'];
                                            ?>
                                                    <option></option>
                                                    <option value="<?php echo $projectCode; ?>"><?php echo $projectCode ?></option>
                                                <?php } ?>
                                                <?php } else {
                                                while ($row = $sqlQuery2[1]->fetch_assoc()) {
                                                    $projectCode = $row['project_code']; ?>
                                                    <option></option>
                                                    <option value="<?php echo $projectCode; ?>"><?php echo $projectCode ?></option>
                                                <?php }
                                                while ($rows = $sqlQuery3[1]->fetch_assoc()) {
                                                    $projectCode2 = $rows['project_code']; ?>
                                                    <option value="<?php echo $projectCode2; ?>"><?php echo $projectCode2 ?></option>
                                            <?php }
                                            } ?>
                                        </select>
                                    <?php }  ?>
                                <?php } else if ($_GET['act'] == "edit") { ?>
                                    <input type="text" class="form-control form-control-sm" id="project_code"
                                        name="projectCode"
                                        value="<?php if ($_GET['act'] == 'edit') {
                                                    echo $ddata['project_code'];
                                                } ?>"
                                        required readonly>
                                <?php } ?>
                            </div>

                            <!-- Order Number -->
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">SO Number / Order
                                Number</label>
                            <div class="col-sm-12 mb-2">
                                <?php if ($_GET['act'] == "add") { ?>
                                    <?php if (isset($_GET['project_code'])) { ?>
                                        <?php if ($_GET['project_code'] == null) { ?>
                                            <select id="orderNumber" class="form-control form-control-sm" name="orderNumber"
                                                disabled>
                                                <option selected="selected">--Choose Items--</option>
                                            </select>
                                            <?php } else {
                                            if (isset($_GET['order_number'])) { ?>
                                                <?php
                                                $sqlGetON = "SELECT no_so FROM sa_wrike_project_list WHERE project_type = 'MSI Project Maintenance' and order_number = '" . $_GET['order_number'] . "'";
                                                $executeGetON = $DBWR->get_sql($sqlGetON);
                                                $getONSo = $executeGetON[0]['no_so'];
                                                ?>
                                                <select id="orderNumber" class="form-control form-control-sm" name="orderNumber"
                                                    onchange="onChangeSOFunction()" required>
                                                    <option disabled>--Choose SO--</option>
                                                    <option selected="selected" value="<?php echo $_GET['order_number']; ?>">
                                                        <?php echo $getONSo . " (" . $_GET['order_number'] . ")"; ?></option>
                                                    <?php
                                                    $sqlCheckSO = "SELECT DISTINCT order_number, no_so, project_type FROM sa_wrike_project_list WHERE  project_type = 'MSI Project Maintenance' and project_code = '" . $_GET['project_code'] . "'";
                                                    $dataSO = $DBWR->get_sql($sqlCheckSO);
                                                    $rowDataSO = $dataSO[0];
                                                    $resDataSO = $dataSO[1];

                                                    do {
                                                        $orderNumber2 = $_GET['order_number'];
                                                        $orderNumber = $rowDataSO['order_number'];
                                                        if ($orderNumber == $orderNumber2) {
                                                            $orderNumber = $rowDataSO['order_number'];
                                                            $noSO = $rowDataSO['no_so'];
                                                            $projectType = $rowDataSO['project_type'];
                                                        } else {
                                                            $orderNumber = $rowDataSO['order_number'];
                                                            $noSO = $rowDataSO['no_so'];
                                                            $projectType = $rowDataSO['project_type'];
                                                    ?>
                                                            <option value="<?php echo $orderNumber; ?>"><?php echo $noSO . " ($orderNumber)"; ?>
                                                            </option>
                                                        <?php
                                                        }
                                                        ?>
                                                    <?php } while ($rowDataSO = $resDataSO->fetch_assoc()); ?>
                                                </select>
                                                <input type="hidden" name="noSO" id="noSO" value="<?php echo $noSO; ?>">
                                            <?php } else {
                                            ?>
                                                <select id="orderNumber" class="form-control form-control-sm" name="orderNumber"
                                                    onchange="onChangeSOFunction()" required>
                                                    <option readonly>--Choose Items--</option>
                                                    <?php
                                                    $sqlCheckSO = "SELECT DISTINCT order_number, no_so, project_type FROM sa_wrike_project_list WHERE project_type = 'MSI Project Maintenance' and project_code = '" . $_GET['project_code'] . "'";
                                                    $dataSO = $DBWR->get_sql($sqlCheckSO);
                                                    $rowDataSO = $dataSO[0];
                                                    $resDataSO = $dataSO[1];

                                                    do {
                                                        $orderNumber = $rowDataSO['order_number'];
                                                        $noSO = $rowDataSO['no_so'];
                                                    ?>
                                                        <option value="<?php echo $orderNumber; ?>"><?php echo $noSO . " ($orderNumber)"; ?>
                                                        </option>
                                                    <?php } while ($rowDataSO = $resDataSO->fetch_assoc()); ?>
                                                </select>
                                        <?php
                                            }
                                        }
                                    } else { ?>
                                        <select id="orderNumber" class="form-control form-control-sm" name="orderNumber"
                                            disabled>
                                            <option value="#">--Choose SO--</option>
                                        </select>
                                    <?php } ?>

                                <?php
                                } else { ?>
                                    <select id="noSO" class="form-control form-control-sm" name="noSO" readonly>
                                        <option value="<?php echo $_GET['no_so']; ?>"><?php echo $_GET['no_so']; ?></option>
                                    </select>
                                <?php } ?>
                            </div>

                            <!-- Project Type -->
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project
                                Type</label>
                            <div class="col-sm-12 mb-2">
                                <?php if ($_GET['act'] == "add") { ?>
                                    <?php if (isset($_GET['project_code']) && isset($_GET['order_number'])) { ?>
                                        <?php if ($_GET['project_code'] == null || $_GET['order_number'] == null) { ?>
                                            <select id="projectType" class="form-control form-control-sm" name="projectType"
                                                disabled>
                                                <option selected="selected">--Project Type--</option>
                                            </select>
                                        <?php } else { ?>
                                            <select id="projectType" class="form-control form-control-sm" name="projectType"
                                                required>
                                                <option disabled>--Project Type--</option>
                                                <?php
                                                if ($projectType == 'MSI Project Implementation') {
                                                    $projectType = 'Implementation';
                                                } elseif ($projectType == 'MSI Project Maintenance') {
                                                    $projectType = 'Maintenance';
                                                ?>
                                                    <option value="<?php echo $projectType; ?>"><?php echo $projectType; ?></option>
                                                <?php }  ?>
                                            </select>
                                        <?php
                                        }
                                    } else { ?>
                                        <select id="projectType" class="form-control form-control-sm" name="projectType"
                                            disabled>
                                            <option value="#">--Project Type--</option>
                                        </select>
                                    <?php } ?>

                                <?php
                                } else if ($_GET['act'] == "edit") { ?>
                                    <select id="projectType" class="form-control form-control-sm" name="projectType"
                                        readonly>
                                        <option value="<?php echo $_GET['project_type']; ?>">
                                            <?php echo $_GET['project_type']; ?></option>
                                    </select>
                                <?php

                                } ?>
                            </div>

                            <!-- Customer Name -->
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Customer
                                Name</label>
                            <div class="col-sm-12 mb-2">
                                <?php
                                if ($_GET['act'] == "edit") { ?>
                                    <input type="text" class="form-control form-control-sm" id="customer"
                                        name="customerName"
                                        value="<?php if ($_GET['act'] == 'edit') {
                                                    echo $ddata['customer_name'];
                                                } ?>"
                                        readonly>
                                <?php } else if ($_GET['act'] == "add" && isset($_GET['project_code']) && isset($_GET['order_number']) && isset($_GET['project_type'])) {
                                    //$sqlCheckData = "SELECT * FROM sa_wrike_project_list as a 
                                    //JOIN sa_initial_project as c ON a.id=c.project_id
                                    //JOIN sa_wrike_project_detail as b on a.id=b.project_id
                                    //WHERE a.project_code = '" . $_GET['project_code'] . "' AND c.order_number LIKE '%" . $_GET['order_number'] . "%' AND a.project_type LIKE '%" . $_GET['project_type'] . "%'";
                                    $sqlCheckData = "SELECT * FROM sa_trx_project_list WHERE project_code = '" . $_GET['project_code'] . "' AND order_number LIKE '%" . $_GET['order_number'] . "%'";

                                    $dataCN = $DBSB->get_sql($sqlCheckData);
                                ?>
                                    <input type="text" class="form-control form-control-sm" id="customer"
                                        name="customerName"
                                        value="<?php
                                                if ($dataCN[2] > 0) {
                                                    echo $dataCN[0]['customer_name'];
                                                } else {
                                                    echo "Data tidak sinkron !";
                                                }

                                                ?>"
                                        readonly>
                                <?php } else { ?>
                                    <input type="text" class="form-control form-control-sm" id="customer"
                                        name="customerName" value="" readonly>
                                <?php } ?>
                            </div>

                            <!-- Project Name -->
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project
                                Name</label>
                            <div class="col-sm-12 mb-2">
                                <?php
                                if ($_GET['act'] == "edit") { ?>
                                    <input type="text" class="form-control form-control-sm" id="projectName"
                                        name="projectName"
                                        value="<?php if ($_GET['act'] == 'edit') {
                                                    echo $ddata['project_name'];
                                                } ?>"
                                        readonly>
                                <?php } else if ($_GET['act'] == "add" && isset($_GET['project_code']) && isset($_GET['order_number']) && isset($_GET['project_type'])) {
                                    $sqlCheckDataPN = "SELECT * FROM sa_wrike_project_list as a 
                                    JOIN sa_wrike_project_detail as b on a.id=b.project_id
                                    WHERE a.project_code = '" . $_GET['project_code'] . "' AND a.order_number LIKE '%" . $_GET['order_number'] . "%' AND a.project_type LIKE '%" . $_GET['project_type'] . "%' ";
                                    $dataPN = $DBWR->get_sql($sqlCheckDataPN);
                                    $sqlCheckDataPN2 = "SELECT * FROM sa_trx_project_list WHERE project_code='" . $_GET['project_code'] . "' AND order_number='" . $_GET['order_number'] . "'";
                                    $dataPN2 = $DBSB->get_sql($sqlCheckDataPN2);
                                ?>
                                    <input type="text" class="form-control form-control-sm" id="projectName"
                                        name="projectName"
                                        value="<?php
                                                if ($dataPN[2] > 0) {
                                                    echo $dataPN[0]['title'];
                                                } else {
                                                    echo $dataPN2[0]['project_name'];
                                                }
                                                ?>"
                                        readonly>
                                <?php } else { ?>
                                    <input type="text" class="form-control form-control-sm" id="projectName"
                                        name="projectName" value="" readonly>
                                <?php } ?>
                            </div>

                            <div class="col-sm-12 mb-2">
                                <?php
                                if ($_GET['act'] == "edit") { ?>
                                <?php } else if ($_GET['act'] == "add" && isset($_GET['project_code']) && isset($_GET['order_number']) && isset($_GET['project_type'])) {
                                    $sqlCheckData = "SELECT * FROM sa_wrike_project_list WHERE project_code = '" . $_GET['project_code'] . "' AND order_number='" . $_GET['order_number'] . "' AND project_type LIKE '%" . $_GET['project_type'] . "%'";
                                    $dataCN = $DBWR->get_sql("SELECT * FROM sa_wrike_project_list WHERE project_code = '" . $_GET['project_code'] . "' AND order_number='" . $_GET['order_number'] . "' AND project_type LIKE '%" . $_GET['project_type'] . "%'");
                                ?>
                                    <input type="hidden" id="projectId" name="projectId" value="<?php
                                                                                                if (isset($dataCN[0]['id'])) {
                                                                                                    echo $dataCN[0]['id'];
                                                                                                } else {
                                                                                                    echo "Data tidak sinkron !";
                                                                                                }

                                                                                                ?>" />
                                <?php } else { ?>
                                    <input type="hidden" class="form-control form-control-sm" id="customer"
                                        name="customerName" value="">
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <?php
                    if (($_GET['act'] == "add" && isset($_GET['project_code']) && isset($_GET['order_number']) && isset($_GET['project_type'])) || $_GET['act'] == "edit") {
                    ?>
                        <div class="row mt-3">
                            <?php if ($_GET['act'] == "add" && isset($_GET['project_code'])) { ?>
                                <div class="col-md-4">
                                    <h3>Resource Assignment</h3>
                                    <div class="control-group after-add-more">
                                        <label for="inputCID3" class="col-sm-12 col-form-label col-form-label-sm mt-3">Resource
                                            Email* (WAJIB DIISI)</label>
                                        <div class="col-sm-10 mb-2">
                                            <select id="email" class="form-control form-control-sm" name="email[]"
                                                onchange="searchTest(this.value)">
                                                <option value="#" selected="selected" disabled>--Choose Resource--</option>
                                                <?php
                                                $sqlHCM = "SELECT * FROM sa_view_employees WHERE (job_structure LIKE '%JG%' OR job_structure LIKE '%LWW%' OR job_structure LIKE '%RBC%' OR job_structure LIKE '%Engineer%')  AND employee_email <> '' AND resign_date IS NULL ORDER BY employee_name";
                                                $dataEmployee = $DBHCM->get_sql($sqlHCM);
                                                $rowDataHcm = $dataEmployee[0];
                                                $resDataHcm = $dataEmployee[1];
                                                do {
                                                    $resourceEmail = $rowDataHcm['employee_email'];
                                                    $resourceName = addslashes($rowDataHcm['employee_name']);

                                                    if (substr($resourceName, -1) == " ") {
                                                        $resourceName = substr($resourceName, 0, -1);
                                                    }
                                                ?>
                                                    <option value="<?php echo "$resourceName<$resourceEmail>"; ?>">
                                                        <?php echo $resourceName . " - " . $resourceEmail ?></option>
                                                <?php } while ($rowDataHcm = $resDataHcm->fetch_assoc()); ?>
                                            </select>
                                            <div class="button" id="buttonSearch"></div>
                                        </div>
                                        <?php if ($_GET['project_type'] == "Implementation") { ?>
                                            <label for="inputCID3" class="col-sm-12 col-form-label col-form-label-sm ">Roles Service
                                                Budgets* (WAJIB DIISI)</label>
                                            <div class="col-sm-10 mb-2">
                                                <select class="form-control form-control-sm" name="roles[]" id="roles" required>
                                                    <?php if (isset($_GET['order_number'])) {
                                                    ?>
                                                        <option value="#" selected="selected" disabled>--Choose Roles--</option>
                                                        <?php
                                                        if ($totalRowPCJobRoles > 0) {
                                                            do {
                                                                $resourceCategory = $rowSelectedPCJobRoles['resource_qualification'];
                                                                $resourceBrand = $rowSelectedPCJobRoles['brand']
                                                        ?>

                                                                <option value="<?php echo $resourceCategory . " - " . $resourceBrand; ?>">
                                                                    <?php echo $resourceCategory . " " . $resourceBrand ?></option>
                                                    <?php } while ($rowSelectedPCJobRoles = $resSelectedPCJobRoles->fetch_assoc());
                                                        }
                                                    } ?>
                                                </select>
                                            </div>
                                        <?php } ?>
                                        <label for="inputCID3" class="col-sm-12 col-form-label col-form-label-sm ">Roles
                                            Project* (WAJIB DIISI)</label>
                                        <div class="col-sm-10 mb-2">
                                            <select class="form-control form-control-sm" name="projectRoles[]" id="projectRoles"
                                                required>
                                                <?php if (isset($_GET['project_code'])) { ?>
                                                    <option value="#" selected="selected" disabled>--Choose Roles--</option>
                                                    <option value="PIC Maintenance">PM Maintenance</option>
                                                    <option value="Technical Expert">Technical Expert</option>
                                                    <option value="Technical Leader">Technical Leader</option>
                                                    <option value="Technical Team Member">Technical Member</option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status<i
                                                class="fas fa-question-circle" data-bs-toggle="popover" data-bs-trigger="focus"
                                                title="Penuh = Resource mengikuti project dari awal hingga akhir
Mutasi = Resource tidak mengikuti project dari awal/Resource tidak mengikuti project hingga akhir"></i></label>
                                        <div class="col-sm-10 mb-2">
                                            <select class="form-control form-control-sm" name="status[]" id="status" required>
                                                <option value="#" selected="selected" disabled>--Choose Status--</option>
                                                <option value="Penuh">Penuh</option>
                                                <option value="Mutasi">Mutasi</option>
                                            </select>
                                        </div>
                                        <!-- <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Start Progress
                                            (in Percent %)</label>
                                        <div class="col-md-5 mb-2">
                                            <input type="number" name="startProgress[]" id="startProgress" class="form-control form-control-sm" style="display: inline-block;">
                                        </div>
                                        <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">End Progress
                                            (in Percent %)</label>
                                        <div class="col-md-5 mb-2">
                                            <input type="number" name="endProgress[]" id="endProgress" class="form-control form-control-sm" style="display: inline-block;">
                                        </div> -->

                                        <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Start
                                            Date</label>
                                        <div class="col-md-5 mb-2">
                                            <input type="date" name="start_date[]" id="start_date"
                                                class="form-control form-control-sm" style="display: inline-block;">
                                        </div>
                                        <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">End
                                            Date</label>
                                        <div class="col-md-5 mb-2">
                                            <input type="date" name="end_date[]" id="end_date"
                                                class="form-control form-control-sm" style="display: inline-block;">
                                        </div>

                                        <div class="col-sm-10 mb-2">
                                            <label for="exampleFormControlTextarea1">Description (optional)</label>
                                            <textarea class="form-control" id="description" name="description"
                                                rows="3"></textarea>
                                        </div>
                                        <!-- Disini ya -->
                                        <div class="col-sm-10 mb-2">
                                            <?php if ($_GET['project_type'] == "Implementation") { ?>
                                                <button type="button" class="btn btn-success" value="Save to database"
                                                    id="butsave"><i class="glyphicon glyphicon-plus"></i>Add Resource</button>
                                            <?php } else { ?>
                                                <button type="button" class="btn btn-success" value="Save to database"
                                                    id="butsavemt"><i class="glyphicon glyphicon-plus"></i>Add Resource</button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } else if ($_GET['act'] == "edit") { ?>
                                <div class="col-md-6">
                                    <h3>Resource Assignment</h3>
                                    <div class="control-group after-add-more">
                                        <div class="col-sm-10 mb-2">
                                            <input type="text" name="id" id="id" class="form-control form-control-sm"
                                                value="<?php if ($_GET['act'] == 'edit') {
                                                            echo $ddata['id'];
                                                        } ?>" hidden>
                                        </div>
                                        <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Resource
                                            Email</label>
                                        <div class="col-sm-10 mb-2">
                                            <input type="text" name="email" id="email" class="form-control form-control-sm"
                                                style="display: inline-block;"
                                                value="<?php if ($_GET['act'] == 'edit') {
                                                            $currentEmail = explode("<", $ddata['resource_email']);
                                                            $currentEmail1 = str_replace(['>', ' '], '', $currentEmail[1]);

                                                            echo $currentEmail[0] . " - " . $currentEmail1;
                                                        } ?>"
                                                readonly>
                                        </div>
                                        <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Roles Service
                                            Budgets</label>
                                        <div class="col-sm-10 mb-2">
                                            <input type="text" name="email" id="email" class="form-control form-control-sm"
                                                style="display: inline-block;"
                                                value="<?php if ($_GET['act'] == 'edit') {
                                                            echo $ddata['roles'];
                                                        } ?>"
                                                readonly>
                                        </div>
                                        <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Roles
                                            Project</label>
                                        <div class="col-sm-10 mb-2">
                                            <input type="text" name="email" id="email" class="form-control form-control-sm"
                                                style="display: inline-block;"
                                                value="<?php if ($_GET['act'] == 'edit') {
                                                            echo $ddata['project_roles'];
                                                        } ?>"
                                                readonly>
                                        </div>
                                        <?php
                                        if (USERPERMISSION == '0162bce636a63c3ae499224203e06ed0' || USERPERMISSION == '7b7bc2512ee1fedcd76bdc68926d4f7b' || USERPERMISSION == 'dbf36ff3e3827639223983ee8ac47b42' || USERPERMISSION == '726ea0dd998698e8a87f8e344d373533' || USERPERMISSION == '5898299487c5b9cdbe7d61809fd20213') {
                                        ?>
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status<i
                                                    class="fas fa-question-circle" data-bs-toggle="popover" data-bs-trigger="focus"
                                                    title="Penuh = Resource mengikuti project dari awal hingga akhir
Mutasi = Resource tidak mengikuti project dari awal/Resource tidak mengikuti project hingga akhir"></i></label>
                                            <div class="col-sm-10 mb-2">
                                                <select class="form-control form-control-sm" name="status" id="statusEditApproval"
                                                    onchange="onChangeStatusEditApprovalFunction();">
                                                    <option value="<?php if ($_GET['act'] == 'edit') {
                                                                        $explodeStatus = explode(" ", $ddata['status']);
                                                                        echo $explodeStatus[0];
                                                                    } ?>">
                                                        <?php if ($_GET['act'] == 'edit') {
                                                            echo $explodeStatus[0];
                                                        } ?></option>
                                                    <option value="Penuh">Penuh</option>
                                                    <option value="Mutasi">Mutasi</option>
                                                    <option value="Terminate">Terminate</option>
                                                </select>
                                            </div>
                                            <!-- <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Start Progress
                                                (in Percent %)</label>
                                            <div class="col-md-5 mb-2">
                                                <input type="number" name="startProgress" id="startProgressEdit" class="form-control form-control-sm" style="display: inline-block;" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                                                                                $explodeProgress = explode("%", $ddata['progress']);
                                                                                                                                                                                                echo $explodeProgress[0];
                                                                                                                                                                                            } ?>" required>
                                            </div>
                                            <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">End Progress
                                                (in Percent %)</label>
                                            <div class="col-md-5 mb-2">
                                                <input type="number" name="endProgress" id="endProgressEdit" class="form-control form-control-sm" style="display: inline-block;" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                                                                            $explodeProgress = explode(" - ", $ddata['progress']);
                                                                                                                                                                                            $explodeEnd = explode("%", $explodeProgress[1]);
                                                                                                                                                                                            echo $explodeEnd[0];
                                                                                                                                                                                        } ?>" required>
                                            </div> -->
                                            <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Start
                                                Date</label>
                                            <div class="col-md-5 mb-2">
                                                <input type="date" name="start_date" id="start_date"
                                                    class="form-control form-control-sm" style="display: inline-block;"
                                                    value="<?= $ddata['start_date']; ?>">
                                            </div>
                                            <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">End
                                                Date</label>
                                            <div class="col-md-5 mb-2">
                                                <input type="date" name="end_date" id="end_date"
                                                    class="form-control form-control-sm" style="display: inline-block;"
                                                    value="<?= $ddata['end_date']; ?>">
                                            </div>
                                            <div class="col-sm-10 mb-2">
                                                <label for="exampleFormControlTextarea1">Description (optional)</label>
                                                <textarea class="form-control" id="exampleFormControlTextarea1" name="description"
                                                    rows="3"
                                                    value=""><?php if ($_GET['act'] == 'edit') {
                                                                    echo $ddata['description'];
                                                                } ?></textarea>
                                            </div>
                                        <?php
                                        } else {
                                        ?>
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status<i
                                                    class="fas fa-question-circle" data-bs-toggle="popover" data-bs-trigger="focus"
                                                    title="Penuh = Resource mengikuti project dari awal hingga akhir
Mutasi = Resource tidak mengikuti project dari awal/Resource tidak mengikuti project hingga akhir"></i></label>
                                            <div class="col-sm-10 mb-2">
                                                <select class="form-control form-control-sm" name="status" id="statusEdit"
                                                    onchange="onChangeStatusEditFunction();" disabled>
                                                    <option value="<?php if ($_GET['act'] == 'edit') {
                                                                        $explodeStatus = explode(" ", $ddata['status']);
                                                                        echo $explodeStatus[0];
                                                                    } ?>" readonly>
                                                        <?php if ($_GET['act'] == 'edit') {
                                                            echo $explodeStatus[0];
                                                        } ?></option>
                                                    <option value="Penuh">Penuh</option>
                                                    <option value="Terminate">Terminate</option>
                                                    <option value="Mutasi">Mutasi</option>
                                                </select>
                                            </div>
                                            <!-- <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Start Progress
                                                (in Percent %)</label>
                                            <div class="col-md-5 mb-2">
                                                <input type="number" name="startProgress" id="startProgressEdit" class="form-control form-control-sm" style="display: inline-block;" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                                                                                $explodeProgress = explode("%", $ddata['progress']);
                                                                                                                                                                                                echo $explodeProgress[0];
                                                                                                                                                                                            } ?>" readonly>
                                            </div>
                                            <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">End Progress
                                                (in Percent %)</label>
                                            <div class="col-md-5 mb-2">
                                                <input type="number" name="endProgress" id="endProgressEdit" class="form-control form-control-sm" style="display: inline-block;" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                                                                            $explodeProgress = explode(" - ", $ddata['progress']);
                                                                                                                                                                                            $explodeEnd = explode("%", $explodeProgress[1]);
                                                                                                                                                                                            echo $explodeEnd[0];
                                                                                                                                                                                        } ?>" readonly>
                                            </div> -->
                                            <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Start
                                                Date</label>
                                            <div class="col-md-5 mb-2">
                                                <input type="date" name="start_date" id="start_date"
                                                    class="form-control form-control-sm" style="display: inline-block;"
                                                    value="<?= $ddata['start_date']; ?>">
                                            </div>
                                            <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">End
                                                Date</label>
                                            <div class="col-md-5 mb-2">
                                                <input type="date" name="end_date" id="end_date"
                                                    class="form-control form-control-sm" style="display: inline-block;"
                                                    value="<?= $ddata['end_date']; ?>">
                                            </div>
                                            <div class="col-sm-10 mb-2">
                                                <label for="exampleFormControlTextarea1">Description (optional)</label>
                                                <textarea class="form-control" id="exampleFormControlTextarea1" name="description"
                                                    rows="3" value=""
                                                    readonly><?php if ($_GET['act'] == 'edit') {
                                                                    echo $ddata['description'];
                                                                } ?></textarea>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="col-md-8">
                                <?php
                                if ($_GET['act'] == 'add' && isset($_GET['project_code'])) {
                                ?>
                                    <!-- Resource Sementara -->
                                    <h3 class="mb-4">Daftar resource sementara</h3>
                                    <table class="table table-hover" id="temporaryResourceTable">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Project Code</th>
                                                <th scope="col">Resource Email</th>
                                                <th scope="col">Roles</th>
                                                <th scope="col">Date</th>
                                                <th scope="col">Created In</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <?php
                                        if (USERPERMISSION == '7b7bc2512ee1fedcd76bdc68926d4f7b') {
                                        ?>
                                            <tbody>
                                                <?php
                                                $project_code  = $_GET['project_code'];
                                                $orderNumber  = $_GET['order_number'];
                                                $projectType = $_GET['project_type'];
                                                $sqlLookupDataPCTemp = "SELECT * FROM sa_temporary_resource WHERE project_code = '$project_code' AND order_number = '$orderNumber' AND project_type = '$projectType'";
                                                $dataPCTemp = $DTSB->get_sql($sqlLookupDataPCTemp);
                                                $rowDataPCTemp = $dataPCTemp[0];
                                                $resDataPCTemp = $dataPCTemp[1];
                                                $totalRowDataPCTemp = $dataPCTemp[2];
                                                $i = 1;
                                                if ($totalRowDataPCTemp > 0) {
                                                    do {
                                                        $idResource = $rowDataPCTemp['id'];
                                                        $projectCodeTable = $rowDataPCTemp['project_code'];
                                                        $noSOTable = $rowDataPCTemp['no_so'];
                                                        $resourceEmailTable = $rowDataPCTemp['resource_email'];
                                                        $project_roles = $rowDataPCTemp['project_roles'];
                                                        if (empty($rowDataPCTemp['roles'])) {
                                                            $rolesTable = '';
                                                        } else {
                                                            $rolesTable = $rowDataPCTemp['roles'];
                                                            $explodeRolesTable = explode(' - ', $rolesTable);

                                                            if ($explodeRolesTable[1] == "") {
                                                                $rolesTable = $explodeRolesTable[0];
                                                            }
                                                        }
                                                        $dateRange = $rowDataPCTemp['start_date'] . ' s/d ' . $rowDataPCTemp['end_date'];
                                                        $timestamp = $rowDataPCTemp['created_in_msizone'];

                                                ?>
                                                        <tr>
                                                            <th scope="row"><?php echo $i; ?></th>
                                                            <td><?php echo $projectCodeTable; ?></td>
                                                            <td><?php echo $resourceEmailTable; ?></td>
                                                            <?php if ($_GET['project_type'] == "Implementation") { ?>
                                                                <td><?php echo $rolesTable; ?></td>
                                                            <?php } elseif ($_GET['project_type'] == 'Maintenance') { ?>
                                                                <td><?php echo $project_roles; ?></td>
                                                            <?php } ?>
                                                            <td><?php echo $dateRange; ?></td>
                                                            <td><?php echo $timestamp; ?></td>
                                                            <td><button data-id="<?php echo $idResource; ?>" type="button"
                                                                    data-toggle="modal" data-target="#konfirmasi"
                                                                    class="btn btn-danger delete-button">Delete</button></td>

                                                            <!-- Modal -->
                                                            <div class="modal fade" id="konfirmasi" tabindex="-1" role="dialog"
                                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title text-center" id="exampleModalLabel">
                                                                                <b>Delete Confirmation</b>
                                                                            </h5>
                                                                            <button type="button" class="close" data-dismiss="modal"
                                                                                aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <center>Apakah yakin ingin menghapus data ini ?</center> <br />
                                                                            <center>
                                                                                <img src="components/modules/resource_assignment_mt/img/delivery.gif"
                                                                                    width="100" height="100" data-aos="fade-up" />
                                                                            </center>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary"
                                                                                data-dismiss="modal">Close</button>
                                                                            <a data-id="<?php echo $idResource; ?>"
                                                                                class="btn btn-danger confirm-delete" href="">Hapus</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </tr>
                                                <?php $i++;
                                                    } while ($rowDataPCTemp = $resDataPCTemp->fetch_assoc());
                                                }
                                                ?>
                                            </tbody>
                                        <?php
                                        } else {
                                        ?>
                                            <?php if ($_GET['project_type'] == "Implementation") { ?>
                                                <tbody>
                                                    <?php
                                                    $project_code  = $_GET['project_code'];
                                                    $orderNumber  = $_GET['order_number'];
                                                    $projectType = $_GET['project_type'];
                                                    $sqlLookupDataPCTemp = "SELECT * FROM sa_temporary_resource WHERE project_code = '$project_code' AND order_number = '$orderNumber' AND project_type = '$projectType' AND created_by LIKE '%" . $_SESSION['Microservices_UserEmail'] . "%'";
                                                    $dataPCTemp = $DTSB->get_sql($sqlLookupDataPCTemp);
                                                    $rowDataPCTemp = $dataPCTemp[0];
                                                    $resDataPCTemp = $dataPCTemp[1];
                                                    $totalRowDataPCTemp = $dataPCTemp[2];
                                                    $i = 1;

                                                    if ($totalRowDataPCTemp > 0) {
                                                        do {
                                                            $idResource = $rowDataPCTemp['id'];
                                                            $projectCodeTable = $rowDataPCTemp['project_code'];
                                                            $noSOTable = $rowDataPCTemp['no_so'];
                                                            $resourceEmailTable = $rowDataPCTemp['resource_email'];
                                                            $rolesTable = $rowDataPCTemp['roles'];
                                                            $statusTable = $rowDataPCTemp['status'];
                                                            $startProgress = $rowDataPCTemp['start_progress'];
                                                            $endProgress = $rowDataPCTemp['end_progress'];
                                                            $timestamp = $rowDataPCTemp['created_in_msizone'];
                                                            $explodeRolesTable = explode(' - ', $rolesTable);

                                                            if ($explodeRolesTable[1] == "") {
                                                                $rolesTable = $explodeRolesTable[0];
                                                            }
                                                    ?>
                                                            <tr>
                                                                <th scope="row"><?php echo $i; ?></th>
                                                                <td><?php echo $projectCodeTable; ?></td>
                                                                <td><?php echo $resourceEmailTable; ?></td>
                                                                <td><?php echo $rolesTable; ?></td>
                                                                <td><?php echo $statusTable . " $startProgress% - $endProgress%"; ?></td>
                                                                <td><?php echo $timestamp; ?></td>
                                                                <td><button data-id="<?php echo $idResource; ?>" type="button"
                                                                        data-toggle="modal" data-target="#konfirmasi"
                                                                        class="btn btn-danger delete-button">Delete</button></td>

                                                                <!-- Modal -->
                                                                <div class="modal fade" id="konfirmasi" tabindex="-1" role="dialog"
                                                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title text-center" id="exampleModalLabel">
                                                                                    <b>Delete Confirmation</b>
                                                                                </h5>
                                                                                <button type="button" class="close" data-dismiss="modal"
                                                                                    aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <center>Apakah yakin ingin menghapus data ini ?</center> <br />
                                                                                <center>
                                                                                    <img src="components/modules/resource_assignment_mt/img/delivery.gif"
                                                                                        width="100" height="100" data-aos="fade-up" />
                                                                                </center>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary"
                                                                                    data-dismiss="modal">Close</button>
                                                                                <a data-id="<?php echo $idResource; ?>"
                                                                                    class="btn btn-danger confirm-delete" href="">Hapus</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </tr>
                                                    <?php $i++;
                                                        } while ($rowDataPCTemp = $resDataPCTemp->fetch_assoc());
                                                    }
                                                    ?>
                                                </tbody>
                                            <?php } else if ($_GET['project_type'] == "Maintenance") { ?>
                                                <tbody>
                                                    <?php
                                                    $project_code  = $_GET['project_code'];
                                                    $orderNumber  = $_GET['order_number'];
                                                    $projectType = $_GET['project_type'];
                                                    $sqlLookupDataPCTemp = "SELECT * FROM sa_temporary_resource WHERE project_code = '$project_code' AND order_number = '$orderNumber' AND project_type = '$projectType' AND created_by LIKE '%" . $_SESSION['Microservices_UserEmail'] . "%'";
                                                    $dataPCTemp = $DTSB->get_sql($sqlLookupDataPCTemp);
                                                    $rowDataPCTemp = $dataPCTemp[0];
                                                    $resDataPCTemp = $dataPCTemp[1];
                                                    $totalRowDataPCTemp = $dataPCTemp[2];
                                                    $i = 1;

                                                    if ($totalRowDataPCTemp > 0) {
                                                        do {
                                                            $idResource = $rowDataPCTemp['id'];
                                                            $projectCodeTable = $rowDataPCTemp['project_code'];
                                                            $noSOTable = $rowDataPCTemp['no_so'];
                                                            $resourceEmailTable = $rowDataPCTemp['resource_email'];
                                                            $rolesTable = $rowDataPCTemp['project_roles'];
                                                            $statusTable = $rowDataPCTemp['status'];
                                                            $startProgress = $rowDataPCTemp['start_progress'];
                                                            $endProgress = $rowDataPCTemp['end_progress'];
                                                            $timestamp = $rowDataPCTemp['created_in_msizone'];
                                                            $dateRange = $rowDataPCTemp['start_date'] . ' s/d ' . $rowDataPCTemp['end_date'];

                                                    ?>
                                                            <tr>
                                                                <th scope="row"><?php echo $i; ?></th>
                                                                <td><?php echo $projectCodeTable; ?></td>
                                                                <td><?php echo $resourceEmailTable;
                                                                    ?></td>
                                                                <td><?php echo $rolesTable; ?></td>
                                                                <td><?php echo $statusTable . " " . $dateRange . ""; ?></td>
                                                                <td><?php echo $timestamp; ?></td>
                                                                <td><button data-id="<?php echo $idResource; ?>" type="button"
                                                                        data-toggle="modal" data-target="#konfirmasi"
                                                                        class="btn btn-danger delete-button">Delete</button></td>

                                                                <!-- Modal -->
                                                                <div class="modal fade" id="konfirmasi" tabindex="-1" role="dialog"
                                                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title text-center" id="exampleModalLabel">
                                                                                    <b>Delete Confirmation</b>
                                                                                </h5>
                                                                                <button type="button" class="close" data-dismiss="modal"
                                                                                    aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <center>Apakah yakin ingin menghapus data ini ?</center> <br />
                                                                                <center>
                                                                                    <img src="components/modules/resource_assignment_mt/img/delivery.gif"
                                                                                        width="100" height="100" data-aos="fade-up" />
                                                                                </center>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary"
                                                                                    data-dismiss="modal">Close</button>
                                                                                <a data-id="<?php echo $idResource; ?>"
                                                                                    class="btn btn-danger confirm-delete" href="">Hapus</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </tr>
                                                    <?php $i++;
                                                        } while ($rowDataPCTemp = $resDataPCTemp->fetch_assoc());
                                                    }
                                                    ?>
                                                </tbody>
                                            <?php } ?>
                                        <?php
                                        }
                                        ?>
                                    </table>
                                    <!-- Resource Sementara -->
                                <?php } ?>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                    <hr />
                    <!-- <input type="submit" class="btn btn-secondary mt-2" name="cancel" value="Cancel"> -->
                    <?php if (isset($_GET['act']) && $_GET['act'] == 'edit' && (USERPERMISSION == '0162bce636a63c3ae499224203e06ed0' || USERPERMISSION == '7b7bc2512ee1fedcd76bdc68926d4f7b' || USERPERMISSION == 'dbf36ff3e3827639223983ee8ac47b42' || USERPERMISSION == '726ea0dd998698e8a87f8e344d373533' || USERPERMISSION == '5898299487c5b9cdbe7d61809fd20213')) { ?>
                        <!-- Trigger Modal Submit -->
                        <button type="button" class="btn btn-primary mt-2" data-toggle="modal"
                            data-target="#submitModalApproval">Edit</button>

                        <!-- Modal -->
                        <div class="modal fade" id="submitModalApproval" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Edit Confirmation</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-12 text-center">
                                                    Apakah anda yakin ingin edit data ini ? <br />
                                                </div>
                                                <div class="col-12 text-center">
                                                    <img src="components/modules/resource_assignment_mt/img/checklist.gif"
                                                        alt="Girl in a jacket" width="100" height="100" data-aos="fade-up">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <input type="submit" class="btn btn-primary" name="submitResourceApproval"
                                            value="Edit">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    }

                    if (isset($_GET['act']) && $_GET['act'] == 'edit' && (USERPERMISSION == '0162bce636a63c3ae499224203e06ed0' || USERPERMISSION == '7b7bc2512ee1fedcd76bdc68926d4f7b' || USERPERMISSION == 'dbf36ff3e3827639223983ee8ac47b42' || USERPERMISSION == '726ea0dd998698e8a87f8e344d373533')) {
                    ?>

                        <button type="button" class="btn btn-danger mt-2 float-right" data-toggle="modal"
                            data-target="#deleteModalApproval">Delete</button>

                        <!-- Modal -->
                        <div class="modal fade" id="deleteModalApproval" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Delete Confirmation</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-12 text-center">
                                                    Apakah anda yakin ingin <b>menghapus</b> data ini ? <br />
                                                </div>
                                                <div class="col-12 text-center">
                                                    <img src="components/modules/resource_assignment_mt/img/trash.gif"
                                                        alt="Girl in a jacket" width="100" height="100" data-aos="fade-up">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <input type="submit" class="btn btn-danger" name="deleteResource" value="Delete">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } else if (isset($_GET['act']) && $_GET['act'] == 'add') { ?>
                        <!-- Trigger Modal Submit -->
                        <!-- <button type="button" class="btn btn-primary mt-2" data-toggle="modal" data-target="#submitModal">Submit</button>

                        Modal -->
                        <!-- <div class="modal fade" id="submitModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Submit Confirmation</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-12 text-center">
                                                    Apakah anda yakin data ini akan di submit ? <br />
                                                </div>
                                                <div class="col-12 text-center">
                                                    <img src="components/modules/resource_assignment_mt/img/checklist.gif" alt="Girl in a jacket" width="100" height="100" data-aos="fade-up">
                                                </div>
                                                <div class="col-12 text-center">
                                                    <b>Note : Setelah di submit data tidak dapat di hapus</b>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <input type="submit" class="btn btn-primary" name="submitResource" value="Submit">
                                    </div>
                                </div>
                            </div>
                        </div> -->

                    <?php }
                    ?>
                    <div class="col-md-12 mt-5">
                        <?php
                        if ($_GET['act'] == 'add' && isset($_GET['project_code']) && isset($_GET['order_number']) && isset($_GET['project_type'])) {
                        ?>
                            <h3 class="mb-3">Daftar resource pada <?php echo $_GET['project_code'] ?></h3>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Project Code</th>
                                        <th scope="col">Resource Email</th>
                                        <th scope="col">Roles</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Approval</th>
                                        <th scope="col">Created In</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $project_code  = $_GET['project_code'];
                                    $orderNumber = $_GET['order_number'];
                                    $project_type = $_GET['project_type'];
                                    $sqlLookupDataPC = "SELECT * FROM sa_resource_assignment WHERE project_code = '$project_code' AND order_number = '$orderNumber' AND project_type='$project_type'";
                                    $dataPC = $DTSB->get_sql($sqlLookupDataPC);
                                    $rowDataPC = $dataPC[0];
                                    $resDataPC = $dataPC[1];
                                    $i = 1;

                                    if ($rowDataPC != "" || $rowDataPC != null) {
                                        do {
                                            $projectCodeTable = $rowDataPC['project_code'];
                                            $resourceEmailTable = $rowDataPC['resource_email'];
                                            $rolesTable = $rowDataPC['roles'] ?? '';
                                            $approvalStatusTable = $rowDataPC['approval_status'];
                                            $startDate = $rowDataPC['start_date'];
                                            $endDate = $rowDataPC['end_date'];
                                            $timestamp = $rowDataPC['created_in_msizone'];
                                            $explodeRolesTable = explode(' - ', $rolesTable);

                                            if (isset($explodeRolesTable[1]) && $explodeRolesTable[1] !== '') {
                                                $rolesTable = $explodeRolesTable[0];
                                            } else {
                                                $rolesTable = $explodeRolesTable[0] ?? 'Unknown Role';
                                            }

                                    ?>
                                            <tr>
                                                <th scope="row"><?php echo $i; ?></th>
                                                <td><?php echo $projectCodeTable; ?></td>
                                                <td><?php echo $resourceEmailTable ?></td>
                                                <td><?php echo $rolesTable; ?></td>
                                                <td><?php echo $startDate . " s/d " . $endDate ?></td>
                                                <td><?php echo $approvalStatusTable; ?></td>
                                                <td><?php echo $timestamp; ?></td>
                                            </tr>
                                    <?php $i++;
                                        } while ($rowDataPC = $resDataPC->fetch_assoc());
                                    } ?>
                                </tbody>
                            </table>
                        <?php } else if ($_GET['act'] == 'edit') { ?>
                            <h3 class="mb-4">Daftar resource pada
                                <?php if ($_GET['act'] == 'edit') {
                                    echo $ddata['project_code'];
                                } ?>
                            </h3>
                            <?php
                            $sqlCheckTable = "SELECT * FROM sa_resource_assignment WHERE project_code = '" . $ddata['project_code'] . "' AND project_type='Maintenance'";
                            $dataCheckTable = $DTSB->get_sql($sqlCheckTable);
                            $rowCheckTable = $dataCheckTable[0];
                            $resCheckTable = $dataCheckTable[1];
                            $totalRowCheckTable = $dataCheckTable[2];
                            $i = 1;
                            ?>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Project Code</th>
                                        <th scope="col">Resource Email</th>
                                        <th scope="col">Roles</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Approval</th>
                                        <th scope="col">Created In</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    do {
                                        $id = $rowCheckTable['id'];
                                        $projectCode = $rowCheckTable['project_code'];
                                        $resourceEmail = $rowCheckTable['resource_email'];
                                        $roles = $rowCheckTable['project_roles'];
                                        $status = $rowCheckTable['status'];
                                        $approvalStatus = $rowCheckTable['approval_status'];
                                        $startProgress = $rowCheckTable['start_progress'];
                                        $endProgress = $rowCheckTable['end_progress'];
                                        $startDate = $rowCheckTable['start_date'];
                                        $endDate = $rowCheckTable['end_date'];
                                        $timestamp = $rowCheckTable['created_in_msizone'];
                                        // $explodeRolesTable = explode(' - ', $roles);
                                        // if ($explodeRolesTable[1] == "") {
                                        //     $roles = $explodeRolesTable[0];
                                        // }
                                    ?>
                                        <tr>
                                            <th scope="row"><?php echo $i ?></th>
                                            <td><?php echo $projectCode ?></td>
                                            <td><?php echo $resourceEmail ?></td>
                                            <td><?php echo $roles ?></td>
                                            <td><?php echo $startDate . " s/d " . $endDate ?></td>
                                            <td><?php echo $approvalStatus;  ?></td>
                                            <td><?php echo $timestamp ?></td>
                                        <?php $i++;
                                    } while ($rowCheckTable = $resCheckTable->fetch_assoc()); ?>
                                </tbody>
                            </table>
                        <?php } ?>
                    </div>
                </form>
                <?php if ($periode_start == null) { ?>
                    <script>
                        $('#butsavemt').click(function(event) {
                            // Ambil nilai Start Date, End Date, dan Status
                            const startDate = $('#start_date').val();
                            const endDate = $('#end_date').val();
                            const status = $('#status').val(); // Ambil nilai status

                            // Jika status bukan "Penuh", lewati pengecekan Start Date dan End Date
                            if (status !== "Penuh") {
                                // Lanjutkan dengan kode AJAX tanpa validasi tanggal
                                submitData();
                            } else {
                                submitData();
                            }
                        });

                        function submitData() {
                            var projectCode = $('#project_code option:selected').val();
                            var orderNumber = $('#orderNumber option:selected').val();
                            var email = $('#email option:selected').val();
                            var projectRoles = $('#projectRoles option:selected').val();
                            var status = $('#status option:selected').val();
                            var projectType = $('#projectType option:selected').val();
                            var noSO = $('#noSO').val();
                            var description = $('#description').val();
                            var emailSession = $('#emailSession').val();
                            var customer = $('#customer').val();
                            var projectId = $('#projectId').val();
                            var projectName = $('#projectName').val();
                            var actualLink = $('#actualLink').val();
                            var startDate = $('#start_date').val();
                            var endDate = $('#end_date').val();

                            if (email != "" && email != "#" && status != "" && projectId != '' &&
                                projectRoles != '' && projectRoles != '#' && orderNumber != '' &&
                                orderNumber != '#' && startDate != "" && endDate != "") {
                                $.ajax({
                                    url: "components/modules/resource_assignment_mt/scriptmt.php",
                                    type: "POST",
                                    dataType: 'json',
                                    data: {
                                        email: email,
                                        roles: "",
                                        status: status,
                                        projectType: projectType,
                                        description: description,
                                        projectCode: projectCode,
                                        noSO: noSO,
                                        emailSession: emailSession,
                                        customer: customer,
                                        projectId: projectId,
                                        projectRoles: projectRoles,
                                        projectName: projectName,
                                        orderNumber: orderNumber,
                                        actualLink: actualLink,
                                        startDate: startDate,
                                        endDate: endDate
                                    },
                                    cache: false,
                                    success: function(result) {
                                        if (result['notifId'] == 1) {
                                            alert(result['notification']);
                                        } else if (result['notifId'] == 2) {
                                            alert(result['notification']);
                                        } else if (result['notifId'] == 3) {
                                            alert(result['notification']);
                                            window.location.href = actualLink;
                                        } else {
                                            alert('CODE 404 - Ada kesalahan pada program !');
                                        }
                                    }
                                });
                            } else {
                                alert('Ada field yang masih kosong !');
                            }
                        }
                    </script>
                <?php
                } else { ?>
                    <?php $check_review = $DBKPI->get_sql("SELECT * FROM sa_kpi_board WHERE order_number='" . $_GET['order_number'] . "' AND project_type LIKE '%Maintenance%'");
                    $check_review2 = $DBKPI->get_sql("SELECT * FROM sa_kpi_so_wr WHERE order_number='" . $_GET['order_number'] . "' AND project_type LIKE '%Maintenance%'");
                    if (isset($check_review[0]['order_number'])) { ?>
                        <script>
                            // Kirimkan data ini ke JavaScript
                            const startAssignment = "<?php echo $periode_start; ?>";
                            const endAssignment = "<?php echo $periode_end; ?>";

                            // Ambil elemen dropdown dan input tanggal
                            const statusDropdown = document.getElementById('status');
                            const startDateInput = document.getElementById('start_date');
                            const endDateInput = document.getElementById('end_date');

                            // Set initial values from PHP if status is already "Penuh"
                            if (statusDropdown.value === "Penuh") {
                                startDateInput.value = startAssignment;
                                endDateInput.value = endAssignment;
                            }

                            // Menambahkan event listener pada dropdown status
                            statusDropdown.addEventListener('change', function() {
                                if (this.value === "Penuh") {
                                    // Isi Start Date dan End Date otomatis dengan nilai dari PHP jika status Penuh
                                    startDateInput.value = startAssignment;
                                    endDateInput.value = endAssignment;
                                } else {
                                    // Kosongkan Start Date dan End Date jika status bukan "Penuh"
                                    startDateInput.value = '';
                                    endDateInput.value = '';
                                }
                            });

                            $('#butsavemt').click(function(event) {
                                // Ambil nilai Start Date, End Date, dan Status
                                const startDate = $('#start_date').val();
                                const endDate = $('#end_date').val();
                                const status = $('#status').val(); // Ambil nilai status

                                // Jika status bukan "Penuh", lewati pengecekan Start Date dan End Date
                                if (status !== "Penuh") {
                                    if (startDate && endDate) {
                                        const startDateObj = new Date(startDate);
                                        const endDateObj = new Date(endDate);
                                        const startAssignmentObj = new Date(startAssignment);
                                        const endAssignmentObj = new Date(endAssignment);
                                        const tahunReview = "<?php echo $check_review[0]['tahun_review']; ?>"; // Get the date as a string
                                        const endReview = "<?php echo $check_review2[0]['end_assignment']; ?>";
                                        // Split the string by the dash ('-')
                                        const parts = endReview.split('-');
                                        // Extract month and day (parts[1] is the month, parts[2] is the day)
                                        const month = parts[1];
                                        const day = parts[2];
                                        const lastDays = tahunReview + '-' + month + '-' + day;
                                        const lastDay = new Date(lastDays);
                                        console.log("Start Date (Input):", startDate);
                                        console.log("Last Day (Review):", lastDays);
                                        console.log("startDateObj:", startDateObj);
                                        console.log("lastDay:", lastDay);
                                        console.log("Tahun Review:", tahunReview);
                                        console.log("End Review (Raw):", endReview);
                                        console.log("Split Parts:", parts);
                                        if (startDateObj < lastDay) {
                                            alert('Start Assignment tidak boleh lebih kecil dari Cutoff Project');
                                            return;
                                        }
                                        // Validasi End Date tidak lebih besar dari end_assignment
                                        if (endDateObj > endAssignmentObj) {
                                            alert('End Date tidak boleh lebih besar dari End Assignment yang ada di database.');
                                            return;
                                        }
                                        // Validasi End Date tidak lebih kecil dari Start Date
                                        if (endDateObj < startDateObj) {
                                            alert('End Date tidak boleh lebih kecil dari Start Date.');
                                            return;
                                        }
                                        // Jika validasi berhasil, lanjutkan dengan menjalankan kode AJAX
                                        submitData();
                                    } else {
                                        alert('Start Date dan End Date harus diisi dengan benar!' + lastDay + startDateObj);
                                    }
                                } else if (status === "Penuh") {
                                    // Cek apakah Start Date dan End Date valid berdasarkan start_assignment dan end_assignment
                                    if (startDate && endDate) {
                                        // Convert string date to Date object for comparison
                                        const startDateObj = new Date(startDate);
                                        const endDateObj = new Date(endDate);
                                        const startAssignmentObj = new Date(startAssignment);
                                        const endAssignmentObj = new Date(endAssignment);
                                        <?php $check_review2 = $DBKPI->get_sql("SELECT * FROM sa_kpi_so_wr WHERE order_number='" . $_GET['order_number'] . "' AND project_type LIKE '%Maintenance%'"); ?>
                                        const tahunReview = "<?php echo $check_review[0]['tahun_review']; ?>"; // Get the date as a string
                                        const endReview = "<?php echo $check_review2[0]['end_assignment']; ?>";
                                        // Split the string by the dash ('-')
                                        const parts = endReview.split('-');
                                        // Extract month and day (parts[1] is the month, parts[2] is the day)
                                        const month = parts[1];
                                        const day = parts[2];
                                        const lastDays = tahunReview + '-' + month + '-' + day;
                                        const lastDay = new Date(lastDays);

                                        console.log("Start Date (Input):", startDate);
                                        console.log("Last Day (Review):", lastDays);
                                        console.log("startDateObj:", startDateObj);
                                        console.log("lastDay:", lastDay);
                                        console.log("Tahun Review:", tahunReview);
                                        console.log("End Review (Raw):", endReview);
                                        console.log("Split Parts:", parts);


                                        if (startDateObj < lastDay) {
                                            alert('Project sudah pernah direview dan tidak bisa menambahkan resource dengan status Penuh startDateObj < lastDay');
                                            return;
                                        }
                                        if (startDateObj > startAssignmentObj) {
                                            alert('Project sudah pernah direview dan tidak bisa menambahkan resource dengan status Penuh startDateObj > startAssignmentObj');
                                            return;
                                        }

                                        // Validasi Start Date tidak lebih kecil dari start_assignment
                                        if (startDateObj < startAssignmentObj) {
                                            alert('Project sudah pernah direview dan tidak bisa menambahkan resource dengan status Penuh startDateObj < startAssignmentObj');
                                            return;
                                        }

                                        // Validasi End Date tidak lebih besar dari end_assignment
                                        if (endDateObj > endAssignmentObj) {
                                            alert('Project sudah pernah direview dan tidak bisa menambahkan resource dengan status Penuh endDateObj > endAssignmentObj');
                                            return;
                                        }

                                        // Validasi End Date tidak lebih kecil dari end_assignment
                                        if (endDateObj < endAssignmentObj) {
                                            alert('Project sudah pernah direview dan tidak bisa menambahkan resource dengan status Penuh endDateObj < endAssignmentObj');
                                            return;
                                        }

                                        // Validasi End Date tidak lebih kecil dari Start Date
                                        if (endDateObj < startDateObj) {
                                            alert('Project sudah pernah direview dan tidak bisa menambahkan resource dengan status Penuh endDateObj < startDateObj');
                                            return;
                                        }

                                        // Jika validasi berhasil, lanjutkan dengan menjalankan kode AJAX
                                        submitData();
                                    } else {
                                        alert('Start Date dan End Date harus diisi dengan benar!');
                                    }
                                }

                            });
                            // });

                            // Fungsi untuk submit data via AJAX
                            function submitData() {
                                var projectCode = $('#project_code option:selected').val();
                                var orderNumber = $('#orderNumber option:selected').val();
                                var email = $('#email option:selected').val();
                                var projectRoles = $('#projectRoles option:selected').val();
                                var status = $('#status option:selected').val();
                                var projectType = $('#projectType option:selected').val();
                                var noSO = $('#noSO').val();
                                var description = $('#description').val();
                                var emailSession = $('#emailSession').val();
                                var customer = $('#customer').val();
                                var projectId = $('#projectId').val();
                                var projectName = $('#projectName').val();
                                var actualLink = $('#actualLink').val();
                                var startDate = $('#start_date').val();
                                var endDate = $('#end_date').val();

                                if (email != "" && email != "#" && status != "" && projectId != '' &&
                                    projectRoles != '' && projectRoles != '#' && orderNumber != '' &&
                                    orderNumber != '#' && startDate != "" && endDate != "") {
                                    $.ajax({
                                        url: "components/modules/resource_assignment_mt/scriptmt.php",
                                        type: "POST",
                                        dataType: 'json',
                                        data: {
                                            email: email,
                                            roles: "",
                                            status: status,
                                            projectType: projectType,
                                            description: description,
                                            projectCode: projectCode,
                                            noSO: noSO,
                                            emailSession: emailSession,
                                            customer: customer,
                                            projectId: projectId,
                                            projectRoles: projectRoles,
                                            projectName: projectName,
                                            orderNumber: orderNumber,
                                            actualLink: actualLink,
                                            startDate: startDate,
                                            endDate: endDate
                                        },
                                        cache: false,
                                        success: function(result) {
                                            if (result['notifId'] == 1) {
                                                alert(result['notification']);
                                            } else if (result['notifId'] == 2) {
                                                alert(result['notification']);
                                            } else if (result['notifId'] == 3) {
                                                alert(result['notification']);
                                                window.location.href = actualLink;
                                            } else {
                                                alert('CODE 404 - Ada kesalahan pada program !');
                                            }
                                        }
                                    });
                                } else {
                                    alert('Ada field yang masih kosong !');
                                }
                            }
                        </script>
                    <?php } else { ?>
                        <script>
                            // Kirimkan data ini ke JavaScript
                            const startAssignment = "<?php echo $periode_start; ?>";
                            const endAssignment = "<?php echo $periode_end; ?>";

                            // Ambil elemen dropdown dan input tanggal
                            const statusDropdown = document.getElementById('status');
                            const startDateInput = document.getElementById('start_date');
                            const endDateInput = document.getElementById('end_date');

                            // Set initial values from PHP if status is already "Penuh"
                            if (statusDropdown.value === "Penuh") {
                                startDateInput.value = startAssignment;
                                endDateInput.value = endAssignment;
                            }

                            // Menambahkan event listener pada dropdown status
                            statusDropdown.addEventListener('change', function() {
                                if (this.value === "Penuh") {
                                    // Isi Start Date dan End Date otomatis dengan nilai dari PHP jika status Penuh
                                    startDateInput.value = startAssignment;
                                    endDateInput.value = endAssignment;
                                } else {
                                    // Kosongkan Start Date dan End Date jika status bukan "Penuh"
                                    startDateInput.value = '';
                                    endDateInput.value = '';
                                }
                            });
                            $('#butsavemt').click(function(event) {
                                // Ambil nilai Start Date, End Date, dan Status
                                const startDate = $('#start_date').val();
                                const endDate = $('#end_date').val();
                                const status = $('#status').val(); // Ambil nilai status

                                // Jika status bukan "Penuh", lewati pengecekan Start Date dan End Date
                                if (status !== "Penuh") {
                                    if (startDate && endDate) {
                                        const startDateObj = new Date(startDate);
                                        const endDateObj = new Date(endDate);
                                        const startAssignmentObj = new Date(startAssignment);
                                        const endAssignmentObj = new Date(endAssignment);
                                        // Validasi End Date tidak lebih besar dari end_assignment
                                        if (endDateObj > endAssignmentObj) {
                                            alert('End Date tidak boleh lebih besar dari End Assignment yang ada di database.');
                                            return;
                                        }
                                        if (startDateObj < startAssignmentObj) {
                                            alert('Start Date tidak boleh Mulai Sebelum Start Assignment yang ada di database.');
                                            return;
                                        }
                                        // Validasi End Date tidak lebih kecil dari Start Date
                                        if (endDateObj < startDateObj) {
                                            alert('End Date tidak boleh lebih kecil dari Start Date.');
                                            return;
                                        }
                                        // Jika validasi berhasil, lanjutkan dengan menjalankan kode AJAX
                                        submitData();
                                    } else {
                                        alert('Start Date dan End Date harus diisi dengan benar!' + lastDay + startDateObj);
                                    }
                                } else if (status === "Penuh") {
                                    // Cek apakah Start Date dan End Date valid berdasarkan start_assignment dan end_assignment
                                    if (startDate && endDate) {
                                        // Convert string date to Date object for comparison
                                        const startDateObj = new Date(startDate);
                                        const endDateObj = new Date(endDate);
                                        const startAssignmentObj = new Date(startAssignment);
                                        const endAssignmentObj = new Date(endAssignment);
                                        // Validasi Start Date tidak lebih besar dari start_assignment
                                        if (startDateObj > startAssignmentObj) {
                                            alert('Start Date tidak boleh lebih besar dari Start Assignment yang ada di database.');
                                            return;
                                        }

                                        // Validasi Start Date tidak lebih kecil dari start_assignment
                                        if (startDateObj < startAssignmentObj) {
                                            alert('Start Date tidak boleh lebih kecil dari Start Assignment yang ada di database.');
                                            return;
                                        }

                                        // Validasi End Date tidak lebih besar dari end_assignment
                                        if (endDateObj > endAssignmentObj) {
                                            alert('End Date tidak boleh lebih besar dari End Assignment yang ada di database.');
                                            return;
                                        }

                                        // Validasi End Date tidak lebih kecil dari end_assignment
                                        if (endDateObj < endAssignmentObj) {
                                            alert('End Date tidak boleh lebih kecil dari End Assignment yang ada di database.');
                                            return;
                                        }

                                        // Validasi End Date tidak lebih kecil dari Start Date
                                        if (endDateObj < startDateObj) {
                                            alert('End Date tidak boleh lebih kecil dari Start Date.');
                                            return;
                                        }

                                        // Jika validasi berhasil, lanjutkan dengan menjalankan kode AJAX
                                        submitData();
                                    } else {
                                        alert('Start Date dan End Date harus diisi dengan benar!');
                                    }
                                }
                            });
                            // });

                            // Fungsi untuk submit data via AJAX
                            function submitData() {
                                var projectCode = $('#project_code option:selected').val();
                                var orderNumber = $('#orderNumber option:selected').val();
                                var email = $('#email option:selected').val();
                                var projectRoles = $('#projectRoles option:selected').val();
                                var status = $('#status option:selected').val();
                                var projectType = $('#projectType option:selected').val();
                                var noSO = $('#noSO').val();
                                var description = $('#description').val();
                                var emailSession = $('#emailSession').val();
                                var customer = $('#customer').val();
                                var projectId = $('#projectId').val();
                                var projectName = $('#projectName').val();
                                var actualLink = $('#actualLink').val();
                                var startDate = $('#start_date').val();
                                var endDate = $('#end_date').val();

                                if (email != "" && email != "#" && status != "" && projectId != '' &&
                                    projectRoles != '' && projectRoles != '#' && orderNumber != '' &&
                                    orderNumber != '#' && startDate != "" && endDate != "") {
                                    $.ajax({
                                        url: "components/modules/resource_assignment_mt/scriptmt.php",
                                        type: "POST",
                                        dataType: 'json',
                                        data: {
                                            email: email,
                                            roles: "",
                                            status: status,
                                            projectType: projectType,
                                            description: description,
                                            projectCode: projectCode,
                                            noSO: noSO,
                                            emailSession: emailSession,
                                            customer: customer,
                                            projectId: projectId,
                                            projectRoles: projectRoles,
                                            projectName: projectName,
                                            orderNumber: orderNumber,
                                            actualLink: actualLink,
                                            startDate: startDate,
                                            endDate: endDate
                                        },
                                        cache: false,
                                        success: function(result) {
                                            if (result['notifId'] == 1) {
                                                alert(result['notification']);
                                            } else if (result['notifId'] == 2) {
                                                alert(result['notification']);
                                            } else if (result['notifId'] == 3) {
                                                alert(result['notification']);
                                                window.location.href = actualLink;
                                            } else {
                                                alert('CODE 404 - Ada kesalahan pada program !');
                                            }
                                        }
                                    });
                                } else {
                                    alert('Ada field yang masih kosong !');
                                }
                            }
                        </script>
                    <?php } ?>
                <?php } ?>
                <script src="https://code.jquery.com/jquery-3.4.1.js"
                    integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
                <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script> -->
                <script src="components/modules/resource_assignment_mt/select2.min.js"></script>
                <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
                <script>
                    AOS.init();
                </script>
                <script>
                    $('.delete-button').on('click', function(e) {
                        var id = $(this).attr('data-id');
                        $('.confirm-delete').attr('data-id', id);
                    });

                    $(".confirm-delete").on('click', function(e) {
                        var id = $(this).attr('data-id');
                        console.log(id);
                        location.href = "components/modules/resource_assignment_mt/script_delete.php?id=" + id;
                    });

                    $("#project_code").on('change', function() {
                        var project_code = $(this).val();
                        var customer_name = $('#customer').val();
                        window.location = window.location.pathname + "?mod=resource_assignment_mt" +
                            "&act=<?php echo $_GET['act']; ?>" + "&project_code=" + project_code;
                    })

                    function onChangeFunction() {
                        var project_code = document.getElementById("project_code").value;
                        window.location = window.location.pathname + "?mod=resource_assignment_mt" +
                            "&act=<?php echo $_GET['act']; ?>" + "&project_code=" + project_code;
                    }

                    function onChangeSOFunction() {
                        var project_code = document.getElementById("project_code").value;
                        var orderNumber = document.getElementById("orderNumber").value;
                        var project_type = "Maintenance";
                        window.location = window.location.pathname + "?mod=resource_assignment_mt" +
                            "&act=<?php echo $_GET['act']; ?>" + "&project_code=" + project_code + "&order_number=" +
                            orderNumber +
                            "&project_type=" + project_type;
                    }

                    function onChangePTFunction() {
                        var project_code = document.getElementById("project_code").value;
                        var orderNumber = document.getElementById("orderNumber").value;
                        var project_type = document.getElementById("projectType").value;
                        window.location = window.location.pathname + "?mod=resource_assignment_mt" +
                            "&act=<?php echo $_GET['act']; ?>" + "&project_code=" + project_code + "&order_number=" +
                            orderNumber +
                            "&project_type=" + project_type;
                    }

                    function searchTest(str) {
                        // var email = document.getElementById('email').value;
                        // console.log(str);
                        var xhttp;
                        if (str == "") {
                            document.getElementById("buttonSearch").innerHTML = "";
                            return;
                        }
                        const myStr = str.split("<");
                        const myFinalStr = myStr[1].split(">");
                        xhttp = new XMLHttpRequest();
                        xhttp.onreadystatechange = function() {
                            if (this.readyState == 4 && this.status == 200) {
                                document.getElementById("buttonSearch").innerHTML = this.responseText;
                            }
                        };
                        xhttp.open("GET", "components/modules/resource_assignment_mt/ajax.php?resource=" + myStr[1], true);
                        xhttp.send();
                    }

                    function searchTestCopy(strCopy) {
                        var xhttp;
                        if (strCopy == "") {
                            document.getElementById("buttonSearchCopyCopy").innerHTML = "";
                            return;
                        }
                        const myStrCopy = strCopy.split("<");
                        const myFinalStrCopy = myStrCopy[1].split(">");
                        // console.log(myFinalStrCopy[0]);
                        xhttp = new XMLHttpRequest();
                        xhttp.onreadystatechange = function() {
                            if (this.readyState == 4 && this.status == 200) {
                                document.getElementById("buttonSearchCopyCopy").innerHTML = this.responseText;
                            }
                        };
                        xhttp.open("GET", "components/modules/resource_assignment_mt/ajax_copy.php?resource=" + myStrCopy[
                            1], true);
                        xhttp.send();
                    }

                    $(document).ready(function() {
                        var index = 0;
                        var arrayData = [];

                        $('#butsave').click(function() {
                            var projectCode = $('#project_code option:selected').val();
                            var orderNumber = $('#orderNumber option:selected').val();
                            var email = $('#email option:selected').val();
                            var roles = $('#roles option:selected').val();
                            var projectRoles = $('#projectRoles option:selected').val();
                            var status = $('#status option:selected').val();
                            var projectType = $('#projectType option:selected').val();
                            var noSO = $('#noSO').val();
                            // var startProgress = $('#startProgress').val();
                            // var endProgress = $('#endProgress').val();
                            var description = $('#description').val();
                            var emailSession = $('#emailSession').val();
                            var customer = $('#customer').val();
                            var projectId = $('#projectId').val();
                            var projectName = $('#projectName').val();
                            var actualLink = $('#actualLink').val();
                            var startDate = $('#start_date').val();
                            var endDate = $('#end_date').val();

                            if (email != "" && email != "#" && status != "" && projectId != '' &&
                                projectRoles != '' && projectRoles != '#' && orderNumber != '' &&
                                orderNumber != '#' && startDate != "" && endDate != "") {
                                alert('Masuk !');
                                $.ajax({
                                    url: "components/modules/resource_assignment_mt/script.php",
                                    type: "POST",
                                    dataType: 'json',
                                    data: {
                                        email: email,
                                        roles: roles,
                                        status: status,
                                        projectType: projectType,
                                        // startProgress: startProgress,
                                        // endProgress: endProgress,
                                        description: description,
                                        projectCode: projectCode,
                                        noSO: noSO,
                                        emailSession: emailSession,
                                        customer: customer,
                                        projectId: projectId,
                                        projectRoles: projectRoles,
                                        projectName: projectName,
                                        orderNumber: orderNumber,
                                        actualLink: actualLink,
                                        startDate: startDate,
                                        endDate: endDate
                                    },
                                    cache: false,
                                    success: function(result) {
                                        if (result['notifId'] == 1) {
                                            alert(result['notification']);
                                        } else if (result['notifId'] == 2) {
                                            alert(result['notification']);
                                        } else if (result['notifId'] == 3) {
                                            alert(result['notification']);
                                            window.location.href = actualLink;
                                        } else {
                                            alert('CODE 404 - Ada kesalahan pada program !');
                                        }
                                        // console.log(result['notification']) // The value of your php $row['adverts'] will be displayed
                                    }
                                });
                            } else {
                                alert('Ada field yang masih kosong yaa !');
                            }
                        });
                    });

                    // function onChangeStatusFunction() {
                    //     var e = document.getElementById("status").value;

                    //     if (e == 'Penuh') {
                    //         document.getElementById("startProgress").value = 0;
                    //         document.getElementById("endProgress").value = 100;
                    //     } else if (e == 'Mutasi') {
                    //         document.getElementById("startProgress").value = "";
                    //         document.getElementById("endProgress").value = 100;
                    //     } else {
                    //         document.getElementById("startProgress").value = "";
                    //         document.getElementById("endProgress").value = "";
                    //     }
                    // }
                </script>

                <script type="text/javascript">
                    $(document).ready(function() {
                        $('#project_code').select2({
                            placeholder: 'Pilih Project Code',
                            allowClear: true
                        });
                    });

                    $(document).ready(function() {
                        $('#email').select2({
                            placeholder: 'Pilih Resource',
                            allowClear: true
                        });
                    });

                    $(document).ready(function() {
                        $('#emailEdit').select2({
                            placeholder: 'Pilih Resource',
                            allowClear: true
                        });
                    });
                </script>

                <script type="text/javascript">
                    $(document).ready(function() {
                        $(".add-more").click(function() {
                            var html = $(".copy").html();
                            $(".after-add-more").after(html);

                            $(document).ready(function() {
                                $('#emailCopyCopy').select2({
                                    placeholder: 'Pilih Resource Next',
                                    allowClear: true,
                                });
                            });
                        });

                        // saat tombol remove dklik control group akan dihapus 
                        $("body").on("click", ".remove", function() {
                            $(this).parents(".control-group").remove();
                        });
                    });

                    function onChangeStatusCopyFunction() {
                        var eCopy = document.getElementById("statusCopy").value;

                        if (eCopy == 'Penuh') {
                            document.getElementById("startProgressCopy").value = 0;
                            document.getElementById("endProgressCopy").value = 100;
                        } else if (eCopy == 'Mutasi') {
                            document.getElementById("startProgressCopy").value = "";
                            document.getElementById("endProgressCopy").value = 100;
                        } else {
                            document.getElementById("startProgressCopy").value = "";
                            document.getElementById("endProgressCopy").value = "";
                        }
                    }

                    function onChangeStatusEditFunction() {
                        var eCopy = document.getElementById("statusEdit").value;

                        if (eCopy == 'Penuh') {
                            document.getElementById("startProgressEdit").value = 0;
                            document.getElementById("endProgressEdit").value = 100;
                        } else if (eCopy == 'Mutasi') {
                            document.getElementById("startProgressEdit").value = "";
                            document.getElementById("endProgressEdit").value = 100;
                        } else {
                            document.getElementById("startProgressEdit").value = "";
                            document.getElementById("endProgressEdit").value = "";
                        }
                    }

                    function onChangeStatusEditApprovalFunction() {
                        var eCopy = document.getElementById("statusEditApproval").value;
                        console.log(eCopy);

                        if (eCopy == 'Terminate') {
                            document.getElementById("startProgressEdit").value = 0;
                            document.getElementById("endProgressEdit").value = '';
                        } else if (eCopy == 'Penuh') {
                            document.getElementById("startProgressEdit").value = 0;
                            document.getElementById("endProgressEdit").value = 100;
                        } else {
                            document.getElementById("startProgressEdit").value = "";
                            document.getElementById("endProgressEdit").value = 100;
                        }
                    }

                    function MyFunction() {
                        var value = document.getElementById("resourceEmailEdit").textContent;
                        var value2 = document.getElementById("resourceRolesEdit").textContent;
                        var e = document.getElementById("roles");

                        document.getElementById("email").value = value;
                        document.getElementById("email").options[e.selectedIndex].value2 = value2;
                    }
                </script>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="History" role="tabpanel" aria-labelledby="history-tab">
        <div class="card shadow mb-4">
            <div class="card-body">
                <?php
                $maxRows = 10;

                if (isset($_GET['maxRows'])) {
                    $maxRows = $_GET['maxRows'];
                }

                $tbl_resource_logs = "resource_logs";
                $condition = "project_code = '" . $_GET['project_code'] . "' ORDER BY entry_date DESC";
                $dataLogResource = $DTSB->get_data($tbl_resource_logs, $condition, "", 0, $maxRows);
                if ($dataLogResource[2] > 0) {
                ?>

                    <h5>History</h5>
                    <table class="table">
                        <thead class="bg-light">
                            <th>Date</th>
                            <th>Time</th>
                            <th>Description</th>
                        </thead>
                        </thead>
                        <tbody>
                            <?php
                            $tgl = "";
                            ?>
                            <?php do { ?>
                                <tr>
                                    <td style="font-size: 12px">
                                        <?php if ($tgl != date("Y-m-d", strtotime($dataLogResource[0]['entry_date']))) { ?>
                                            <table class="table table-sm table-light table-striped">
                                                <tr>
                                                    <td class="text-center fw-bold" colspan="2">
                                                        <?php echo date("Y", strtotime($dataLogResource[0]['entry_date'])); ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">
                                                        <?php echo date("M", strtotime($dataLogResource[0]['entry_date'])); ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php echo date("d", strtotime($dataLogResource[0]['entry_date'])); ?>
                                                    </td>
                                                </tr>
                                            </table>

                                        <?php
                                        } ?>
                                    </td>
                                    <td style="font-size: 12px">
                                        <?php echo date("H:i:s", strtotime($dataLogResource[0]['entry_date'])); ?></td>
                                    <td style="font-size: 12px">
                                        <?php
                                        $description = str_replace(" -", "", $dataLogResource[0]['description']);
                                        echo $description . "<br/>Performed by " . $dataLogResource[0]['entry_by']; ?>
                                    </td>
                                </tr>
                                <?php $tgl = date("Y-m-d", strtotime($dataLogResource[0]['entry_date'])); ?>
                            <?php } while ($dataLogResource[0] = $dataLogResource[1]->fetch_assoc()); ?>
                        </tbody>
                    </table>
                <?php } ?>
                <!-- <div class="" style="font-size: 12px">Readmore...</div> -->
            </div>
        </div>
        <?php
        if ($_SESSION['Microservices_UserEmail'] == 'aryo.bimo@mastersystem.co.id') {
            $email_renewal = "SELECT * FROM sa_email_renewal";
            $email = $DBKPI->get_sql($email_renewal);
            $so_number = $email[0]['so_number'];
            $project_code = $_GET['project_code'];
            $order_number = $_GET['order_number'];
            $end_maintenance1 = $DBKPI->get_sql("SELECT * FROM sa_maintenance_date WHERE project_code ='$project_code' and order_number ='$order_number' AND id_date='mt_date_end'");
            $end_maintenance = $end_maintenance1[0]['date'];
            $current_date = date('Y-m-d');
            $one_month_before = date('Y-m-d', strtotime('-3 month', strtotime($end_maintenance)));

            if ($current_date >= $one_month_before && $current_date <= $end_maintenance) {
                echo '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_email_to">Email</button>';
            }
        }
        ?>
        <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_email_to">
            Submit Email
        </button> -->
        <?php include 'components/modules/resource_assignment_mt/send_email_rmt.php' ?>
    </div>
    <?php if (isset($_GET['order_number'])) { ?>
        <div class="tab-pane fade" id="mt_report" role="tabpanel" aria-labelledby="mt_report-tab">
            <?php include('form_maintenance_date.php') ?>
        </div>
    <?php } ?>
</div>
<?php
if ($_GET['act'] == 'testing') {
    $sqlQuery = "SELECT DISTINCT a.project_id, c.project_code, b.project_status FROM sa_workload.`sa_initial_jobroles` AS a
                                        JOIN sa_wrike_integrate.sa_wrike_project_detail AS b ON a.project_id = b.project_id
                                        JOIN sa_wrike_integrate.`sa_wrike_project_list` AS c ON a.project_id = c.id
                                        WHERE (c.order_number != '' OR c.order_number != NULL)
                                        AND a.parent_id = 'SERVICE BUDGET'
                                        ORDER BY a.project_id";
    $dataQuery = $DBWR->get_sql($sqlQuery);
    $rowData = $dataQuery[0];
    $resData = $dataQuery[1];
    $sqlQuery2 = "SELECT DISTINCT a.project_id, c.project_code, b.project_status FROM sa_workload.`sa_initial_jobroles_project` AS a
                                        JOIN sa_wrike_integrate.sa_wrike_project_detail AS b ON a.project_id = b.project_id
                                        JOIN sa_wrike_integrate.`sa_wrike_project_list` AS c ON a.project_id = c.id
					JOIN sa_wrike_integrate.`sa_initial_project` AS d ON a.project_id=d.project_id
                                        WHERE (d.order_number != '' OR d.order_number != NULL)
                                        ORDER BY a.project_id";
    $dataQuery2 = $DBWR->get_sql($sqlQuery2);
    $rowData2 = $dataQuery2[0];
    $resData2 = $dataQuery2[1];
    do {
        $projectCode = $rowData['project_code'];
?>
        <option></option>
        <option value="<?php echo $projectCode; ?>"><?php echo $projectCode ?></option>
    <?php } while ($rowData = $resData->fetch_assoc()); ?>

<?php }
?>