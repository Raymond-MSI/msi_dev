<?php
// Service Budget V2
?>

<?php
function consumption_plan($price, $band)
{
    if ($band == 1) {
        $consumption_plan = $price * 0.025;
    } else
    if ($band == 2) {
        $consumption_plan = $price * 0.020;
    } else
    if ($band == 3) {
        $consumption_plan = $price * 0.015;
    } else
    if ($band == 4) {
        $consumption_plan = $price * 0.010;
    } else {
        $consumption_plan = 0;
    }
    return $consumption_plan;
}

// Insert implementation data
function insert_implementation($lastid, $itosid, $service_type, $band = 0)
{
    global $DTSB;
    $tblname = 'trx_project_implementations';
    if ($service_type == 1) {
        $consumption = consumption_plan(deformat($_POST['i_price']), $band);
        $mysql = sprintf(
            "(`project_id`, `project_estimation`, `project_estimation_id`, `implementation_price`, `implementation_price_list`, `agreed_price`, `tos_id`, `tos_category_id`, `bpd_total_location`, `bpd_description`, `bpd_price`, `out_description`, `service_type`, `maintenance_package_price`, `maintenance_addon_description`, `modified_by`, `consumption_plan`, `file_backup`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
            GetSQLValueString($lastid, "int"),
            GetSQLValueString($_POST['i_project_estimation'], "int"),
            GetSQLValueString($_POST['i_project_estimation_id'], "int"),
            GetSQLValueString(deformat($_POST['i_price']), "text"),
            GetSQLValueString(deformat($_POST['i_price_list']), "text"),
            GetSQLValueString(deformat($_POST['i_agreed_price']), "text"),
            GetSQLValueString($itosid, "text"),
            GetSQLValueString($_POST['i_tos_category_id'], "int"),
            GetSQLValueString($_POST['i_bpd_total_location'], "int"),
            GetSQLValueString($_POST['i_bpd_description'], "text"),
            GetSQLValueString(deformat($_POST['i_bpd_price']), "text"),
            GetSQLValueString($_POST['i_out_description'], "text"),
            GetSQLValueString($service_type, "int"),
            GetSQLValueString(0, "long"),
            GetSQLValueString(0, "long"),
            GetSQLValueString($_SESSION['Microservices_UserEmail'], "text"),
            GetSQLValueString(deformat($consumption), "text"),
            GetSQLValueString($_POST['fileName'], "text")
        );
    } elseif ($service_type == 2) {
        $consumption = consumption_plan(deformat($_POST['m_price']), $band);
        $mysql = sprintf(
            "(`project_id`, `project_estimation`, `project_estimation_id`, `implementation_price`, `implementation_price_list`, `agreed_price`, `tos_id`, `tos_category_id`, `bpd_total_location`, `bpd_description`, `bpd_price`, `out_description`, `service_type`, `maintenance_package_price`, `maintenance_addon_description`, `dedicate_backup`, `modified_by`, `consumption_plan`, `file_backup`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
            GetSQLValueString($lastid, "int"),
            GetSQLValueString($_POST['m_project_estimation'], "int"),
            GetSQLValueString($_POST['m_project_estimation_id'], "int"),
            GetSQLValueString(deformat($_POST['m_price']), "text"),
            GetSQLValueString(deformat($_POST['m_price_list']), "text"),
            GetSQLValueString(deformat($_POST['m_agreed_price']), "text"),
            GetSQLValueString($itosid, "text"),
            GetSQLValueString($_POST['m_tos_category_id'], "int"),
            GetSQLValueString($_POST['m_bpd_total_location'], "int"),
            GetSQLValueString($_POST['m_bpd_description'], "text"),
            GetSQLValueString(deformat($_POST['m_bpd_price']), "text"),
            GetSQLValueString($_POST['m_out_description'], "text"),
            GetSQLValueString($service_type, "int"),
            GetSQLValueString(0, "long"),
            GetSQLValueString(0, "long"),
            GetSQLValueString(isset($_POST['dedicate_backup']) ? $_POST['dedicate_backup'] : "", "int"),
            GetSQLValueString($_SESSION['Microservices_UserEmail'], "text"),
            GetSQLValueString(deformat($consumption), "text"),
            GetSQLValueString($_POST['fileName'], "text")
        );
    } else {
        $mysql = sprintf(
            "(`project_id`, `project_estimation`, `project_estimation_id`, `implementation_price`, `implementation_price_list`, `agreed_price`, `tos_id`, `tos_category_id`, `bpd_total_location`, `bpd_description`, `bpd_price`, `out_description`, `service_type`, `maintenance_package_price`, `maintenance_addon_description`, `modified_by`, `file_backup`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
            GetSQLValueString($lastid, "int"),
            GetSQLValueString($_POST['w_project_estimation'], "int"),
            GetSQLValueString($_POST['w_project_estimation_id'], "int"),
            GetSQLValueString(deformat($_POST["w_price"]), "text"),
            GetSQLValueString("0", "text"),
            GetSQLValueString("0", "text"),
            GetSQLValueString($_POST['w_tos_id'], "text"),
            GetSQLValueString("0", "int"),
            GetSQLValueString("0", "int"),
            GetSQLValueString(NULL, "text"),
            GetSQLValueString("0", "text"),
            GetSQLValueString(NULL, "text"),
            GetSQLValueString($service_type, "int"),
            GetSQLValueString(0, "long"),
            GetSQLValueString(0, "long"),
            GetSQLValueString($_SESSION['Microservices_UserEmail'], "text"),
            GetSQLValueString($_POST['fileName'], "text")
        );
    }
    $res = $DTSB->insert_data($tblname, $mysql);
}

// function insert Mandays;
function insert_mandays($project_id, $resource_level, $resource_catalog_id, $mantotal, $mandays, $brand, $value, $service_type)
{
    global $DTSB;
    $tblname = "trx_project_mandays";
    $mysql = sprintf(
        "(`project_id`, `resource_level`, `resource_catalog_id`, `mantotal`, `mandays`, `brand`, `value`, `service_type`, `modified_by`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)",
        GetSQLValueString($project_id, "int"),
        GetSQLValueString($resource_level, "int"),
        GetSQLValueString($resource_catalog_id, "int"),
        GetSQLValueString($mantotal, "int"),
        GetSQLValueString($mandays, "int"),
        GetSQLValueString($brand, "text"),
        GetSQLValueString($value, "int"),
        GetSQLValueString($service_type, "int"),
        GetSQLValueString($_SESSION['Microservices_UserEmail'], "text")
    );
    $res = $DTSB->insert_data($tblname, $mysql);
}

// save mandays
function save_mandays($project_id, $brand_id, $resource_level, $resource_catalog_id, $mantotal, $mandays, $brand, $value, $service_type)
{
    global $DTSB;
    $tblname = "trx_project_mandays";
    if ($_POST['version'] == 0) {
        if ($brand_id != "") {
            insert_mandays($project_id, $resource_level, $resource_catalog_id, $mantotal, $mandays, $brand, $value, $service_type);
        };
    } else {
        if ($brand_id != "") {
            $condition = "project_id = " . $project_id . " AND resource_level = " . $resource_level . " AND service_type = " . $service_type;
            $testExisting = $DTSB->get_data($tblname, $condition);
            if ($testExisting[2] > 0) {
                $mysql = sprintf(
                    "`project_id`=%s, `resource_level`=%s, `resource_catalog_id`=%s, `mantotal`=%s, `mandays`=%s, `brand`=%s, `value`=%s, `service_type`=%s, `modified_by`=%s",
                    GetSQLValueString($project_id, "int"),
                    GetSQLValueString($resource_level, "int"),
                    GetSQLValueString($resource_catalog_id, "int"),
                    GetSQLValueString($mantotal, "int"),
                    GetSQLValueString($mandays, "int"),
                    GetSQLValueString($brand, "text"),
                    GetSQLValueString($value, "int"),
                    GetSQLValueString($service_type, "int"),
                    GetSQLValueString($_SESSION['Microservices_UserEmail'], "text")
                );
                $res = $DTSB->update_data($tblname, $mysql, $condition);
            } else {
                insert_mandays($project_id, $resource_level, $resource_catalog_id, $mantotal, $mandays, $brand, $value, $service_type);
            }
        };
    }
}

// function insert addon
function insert_addon($lastid, $out_title, $out_price, $service_type)
{
    global $DTSB;
    $tblname = "trx_addon";
    $mysql = sprintf(
        "(`project_id`, `addon_title`, `addon_price`, `service_type`, `modified_by`) VALUES (%s,%s,%s,%s,%s)",
        GetSQLValueString($lastid, "int"),
        GetSQLValueString($out_title, "text"),
        GetSQLValueString(deformat($out_price), "text"),
        GetSQLValueString($service_type, "int"),
        GetSQLValueString($_SESSION['Microservices_UserEmail'], "text")
    );
    $res = $DTSB->insert_data($tblname, $mysql);
}

function check_subsolution($rsSolutions, $project_id = 0)
{
    global $DTSB;
    $mysql = sprintf(
        "SELECT
            `project_solution_code`
        FROM
            `sa_trx_project_solution_sub`
        WHERE
            `project_solution_code` = %s AND
            `project_id` = %s",
        GetSQLValueString($rsSolutions, "text"),
        GetSQLValueString($project_id, "int")
    );
    $rsGetSub = $DTSB->get_sql($mysql);
    $checked = false;
    if ($rsGetSub[2] > 0) {
        $checked = true;
    }
    return $checked;
}

function save_SubSolution()
{
    global $DTSB;
    // SAVE SUB-SOLUTION
    // ADD NEW DATA
    $NewData = array();
    if (isset($_POST['subsolution'])) {
        echo '<script>console.log("Sub-Solution");</script>';
        $i = 0;
        foreach ($_POST['subsolution'] as $subsolution) {

            array_push($NewData, $subsolution);
            $mysql = sprintf(
                "SELECT 
                    `solution_sub_id`, 
                    `project_solution_code`, 
                    `project_id` 
                FROM 
                    `sa_trx_project_solution_sub` 
                WHERE 
                    `project_solution_code` = %s
                    AND `project_id` = %s",
                GetSQLValueString($subsolution, "text"),
                GetSQLValueString($_POST['project_id'], "int")
            );
            $rsSubSolution = $DTSB->get_sql($mysql);
            if ($rsSubSolution[2] == 0) {
                $mysql = sprintf("INSERT INTO `sa_trx_project_solution_sub`(`project_solution_code`, `project_id`) VALUES(%s,%s)", GetSQLValueString($subsolution, "text"), GetSQLValueString($_POST['project_id'], "int"));
                $res = $DTSB->get_sql($mysql, false);
                echo '<script>console.log("    ' . $mysql . '");</script>';
            }
        }
    }
    // DELETE DATA 
    $mysql = "SELECT `project_solution_code`, `solution_sub_id` FROM `sa_trx_project_solution_sub` WHERE `project_id` = " . $_POST['project_id'];
    $rsCheckSub = $DTSB->get_sql($mysql);
    if ($rsCheckSub[2] > 0) {
        echo '<script>console.log("Sub-Solution");</script>';
        do {
            $key = array_search($rsCheckSub[0]['project_solution_code'], $NewData, true);
            if (strval($key) == "") {
                $mysql = sprintf("DELETE FROM `sa_trx_project_solution_sub` WHERE `project_id` = %s AND `project_solution_code` = %s AND `solution_sub_id` = %s", GetSQLValueString($_POST['project_id'], "int"), GetSQLValueString($rsCheckSub[0]['project_solution_code'], "text"), GetSQLValueString($rsCheckSub[0]['solution_sub_id'], "int"));
                $res = $DTSB->get_sql($mysql, false);
                echo '<script>console.log("    ' . $mysql . '");</script>';
            }
        } while ($rsCheckSub[0] = $rsCheckSub[1]->fetch_assoc());
    }
    // END SUB-SOLUTION
}

$sb_number = "";
$mdlname = "NAVISION";
$DBNAV = get_conn($mdlname);
if (isset($_POST['save_service_budget'])) {
    $tblname = "trx_project_list";

    // PROJECT INFORMATION SECTION    
    // Save Service Budget Information
    $wotype = "";
    if (isset($_POST['wo_type'])) {
        foreach ($_POST['wo_type'] as $wotype0) {
            $wotype .= $wotype0 . ";";
        }
    }

    // Setup Bundling Project
    $bundling = "";
    if (isset($_POST['bundling1']) and $_POST['bundling1'] == '1') {
        $bundling .= '1;';
    } else {
        $bundling .= '0;';
    }
    if (isset($_POST['bundling2']) and $_POST['bundling2'] == '2') {
        $bundling .= '2;';
    } else {
        $bundling .= '0;';
    }
    if (isset($_POST['bundling3']) and $_POST['bundling3'] == '3') {
        $bundling .= '3;';
    } else {
        $bundling .= '0;';
    }
    if (isset($_POST['bundling4']) and $_POST['bundling4'] == '4') {
        $bundling .= '4;';
    } else {
        $bundling .= '0;';
    }

    // Setup Multiyears Project
    $multiyear = '';
    if (isset($_POST['multiyears'])) {
        $multiyear = $_POST['multiyears'];
    }

    // Minta dicek karena dihapus
    $backup = (isset($_POST['backupEBU']) ? "1" : "0") . ";" . (isset($_POST['backupIBU']) ? "1" : "0");

    //Setup Band Project
    $band = 0;
    if (isset($_POST['band'])) {
        $band = $_POST['band'];
    }

    // Setup Version Service Budget
    if ($_POST['version'] > 0) {
        $createby = $_POST['create_by'];
        $createdate = $_POST['create_date'];
    } else {
        $createby = $_SESSION['Microservices_UserEmail'];
        $createdate = date("Y-m-d G:i:s");
    }

    // Setop Version Service Budget
    $_POST["status"] == "draft" ? $version = $_POST["version"] : $version = $_POST['version'] + 1;
    $version == 0 ? $version = 1 : '';

    // Setup Reporting
    $reportingPlan = $_POST['plan_reporting'];
    $reportingAddon = $_POST['addon_reporting'];
    $preventivePlan = $_POST['plan_preventive'];
    $preventiveAddon = $_POST['addon_preventive'];
    $ticketPlan = $_POST['plan_ticket'];
    $ticketAddon = $_POST['addon_ticket'];
    $reporting =
        '{
    "reporting":
    {
        "plan": "' . $reportingPlan . '",
        "addon": "' . $reportingAddon . '"
    },
    "preventive":
    {
        "plan": "' . $preventivePlan . '",
        "addon": "' . $preventiveAddon . '"
    },
    "ticket":
    {
        "plan": "' . $ticketPlan . '",
        "addon": "' . $ticketAddon . '"
    }    
}';

    // Save Project Information
    if ($_POST['version'] == 0) {
        $mysql = sprintf(
            "(`project_code`, `parent_id`, `previous_id`, `project_name`, `project_name_internal`, `customer_code`, `customer_name`, `order_number`, `po_number`, `po_date`, `so_number`, `so_date`, `sales_code`, `sales_name`, `amount_idr`, `amount_usd`, `status_so`, `duration`, `contract_type`, `wo_type`, `version`, `modified_by`, `create_by`, `create_date`, `band`, `sbtype`, `newproject`, `bundling`, `backup`, `multiyears`, `status`, `ps_account`, `ps_technical`, `reporting`, `maintenance_start`, `maintenance_end`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
            GetSQLValueString($_POST['project_code'], "text"),
            GetSQLValueString($_POST['parent_id'], "text"),
            GetSQLValueString($_POST['previous_id'], "text"),
            GetSQLValueString(addslashes($_POST['project_name']), "text"),
            GetSQLValueString(addslashes($_POST['project_name_internal']), "text"),
            GetSQLValueString($_POST['customer_code'], "text"),
            GetSQLValueString($_POST['customer_name'], "text"),
            GetSQLValueString($_POST['order_number'], "text"),
            GetSQLValueString($_POST['po_number'], "text"),
            GetSQLValueString(date("Y-m-d G:i:s", strtotime($_POST['po_date'])), "date"),
            GetSQLValueString($_POST['so_number'], "text"),
            GetSQLValueString(date("Y-m-d G:i:s", strtotime($_POST['so_date'])), "date"),
            GetSQLValueString($_POST['sales_code'], "text"),
            GetSQLValueString($_POST['sales_name'], "text"),
            GetSQLValueString(deformat($_POST['amount_idr']), "text"),
            GetSQLValueString(deformat($_POST['amount_usd']), "text"),
            GetSQLValueString($_POST['status_so'], "text"),
            GetSQLValueString($_POST['duration'], "int"),
            GetSQLValueString($_POST['contract_type'], "text"),
            GetSQLValueString($wotype, "text"),
            GetSQLValueString($version, "int"),
            GetSQLValueString($_SESSION['Microservices_UserEmail'], "text"),
            GetSQLValueString($createby, "text"),
            GetSQLValueString($createdate, "text"),
            GetSQLValueString($band, "int"),
            GetSQLValueString($_POST['sbtype'], "int"),
            GetSQLValueString($_POST['newproject'], "int"),
            GetSQLValueString($bundling, "text"),
            GetSQLValueString($backup, "text"),
            GetSQLValueString($multiyear, "int"),
            GetSQLValueString("draft", "text"),
            GetSQLValueString(isset($_POST['ps_account']) ? $_POST['ps_account'] : "", "text"),
            GetSQLValueString(isset($_POST['ps_technicalx']) ? $_POST['ps_technicalx'] : "", "text"),
            GetSQLValueString($reporting, "text"),
            GetSQLValueString(date("Y-m-d G:i:s", strtotime($_POST['maintenance_start'])), "date"),
            GetSQLValueString(date("Y-m-d G:i:s", strtotime($_POST['maintenance_end'])), "date")
        );
        $res = $DTSB->insert_data($tblname, $mysql);

        $order = "project_id DESC";
        $id = $DTSB->get_data($tblname, "", $order);
        $did = $id[0];
        $lastid = $did['project_id'];
    } else {
        $condition = "project_id=" . $_POST['project_id'];
        $update = sprintf(
            "`project_code`=%s, `parent_id`=%s, `previous_id`=%s, `project_name`=%s, `project_name_internal`=%s, `customer_code`=%s, `customer_name`=%s, `order_number`=%s, `po_number`=%s, `po_date`=%s, `so_number`=%s, `so_date`=%s, `sales_code`=%s, `sales_name`=%s, `amount_idr`=%s, `amount_usd`=%s, `status_so`=%s, `duration`=%s, `contract_type`=%s, `wo_type`=%s, `version`=%s, `modified_by`=%s, `create_by`=%s, `create_date`=%s, `band`=%s, `sbtype`=%s, `newproject`=%s, `bundling`=%s, `backup`=%s, `multiyears`=%s, `status`=%s, `ps_account`=%s, `ps_technical`=%s, `reporting`=%s, `maintenance_start`=%s, `maintenance_end`=%s",
            GetSQLValueString($_POST['project_code'], "text"),
            GetSQLValueString(isset($_POST['parent_id']) ? $_POST['parent_id'] : "", "text"),
            GetSQLValueString(isset($_POST['previous_id']) ? $_POST['previous_id'] : "", "text"),
            GetSQLValueString(addslashes($_POST['project_name']), "text"),
            GetSQLValueString(addslashes($_POST['project_name_internal']), "text"),
            GetSQLValueString($_POST['customer_code'], "text"),
            GetSQLValueString($_POST['customer_name'], "text"),
            GetSQLValueString($_POST['order_number'], "text"),
            GetSQLValueString($_POST['po_number'], "text"),
            GetSQLValueString(date("Y-m-d G:i:s", strtotime($_POST['po_date'])), "date"),
            GetSQLValueString($_POST['so_number'], "text"),
            GetSQLValueString(date("Y-m-d G:i:s", strtotime($_POST['so_date'])), "date"),
            GetSQLValueString($_POST['sales_code'], "text"),
            GetSQLValueString($_POST['sales_name'], "text"),
            GetSQLValueString(deformat($_POST['amount_idr']), "text"),
            GetSQLValueString(deformat($_POST['amount_usd']), "text"),
            GetSQLValueString($_POST['status_so'], "text"),
            GetSQLValueString($_POST['duration'], "int"),
            GetSQLValueString($_POST['contract_type'], "text"),
            GetSQLValueString($wotype, "text"),
            GetSQLValueString($version, "int"),
            GetSQLValueString($_SESSION['Microservices_UserEmail'], "text"),
            GetSQLValueString($createby, "text"),
            GetSQLValueString($createdate, "text"),
            GetSQLValueString($band, "int"),
            GetSQLValueString($_POST['sbtype'], "int"),
            GetSQLValueString($_POST['newproject'], "int"),
            GetSQLValueString($bundling, "text"),
            GetSQLValueString($backup, "text"),
            GetSQLValueString($multiyear, "int"),
            GetSQLValueString("draft", "text"),
            GetSQLValueString(isset($_POST['ps_account']) ? $_POST['ps_account'] : "", "text"),
            GetSQLValueString(isset($_POST['ps_technicalx']) ? $_POST['ps_technicalx'] : "", "text"),
            GetSQLValueString($reporting, "text"),
            GetSQLValueString(date("Y-m-d G:i:s", strtotime($_POST['maintenance_start'])), "date"),
            GetSQLValueString(date("Y-m-d G:i:s", strtotime($_POST['maintenance_end'])), "date")
        );
        $res = $DTSB->update_data($tblname, $update, $condition);

        $lastid = $_POST['project_id'];
    }

    // Set table order in Navision is 1
    // The mean Service Budget from the sales order has been created
    $tblname = "mst_orders";
    $condition = "project_code='" . $_POST['project_code'] . "' AND so_number='" . $_POST['so_number'] . "'";
    $updateorder = "status_sb=1";
    $res = $DBNAV->update_data($tblname, $updateorder, $condition);

    // Set table order number in Navision is 1
    // The mean Service Budget from the order number has been created
    $tblname = "mst_order_number";
    $condition = "project_code='" . $_POST['project_code'] . "' AND order_number='" . $_POST['order_number'] . "'";
    $updateorder = "status_order=1";
    $res = $DBNAV->update_data($tblname, $updateorder, $condition);

    // Save Project Solution
    $tblname = "trx_project_solutions";
    $condition = "project_id=" . $lastid;
    $checkSolutions = $DTSB->get_data($tblname, $condition);
    // if($_POST['version']==0) {
    if ($checkSolutions[2] == 0) {
        $mysql = sprintf(
            "(`project_id`, `solution_name`, `product`, `services`, `modified_by`) VALUES (%s,%s,%s,%s,%s), (%s,%s,%s,%s,%s), (%s,%s,%s,%s,%s), (%s,%s,%s,%s,%s), (%s,%s,%s,%s,%s), (%s,%s,%s,%s,%s)",
            GetSQLValueString($lastid, "int"),
            GetSQLValueString("DCCI", "text"),
            GetSQLValueString($_POST['DCCIP'], "text"),
            GetSQLValueString($_POST['DCCIS'], "text"),
            GetSQLValueString($_SESSION['Microservices_UserEmail'], "text"),
            GetSQLValueString($lastid, "int"),
            GetSQLValueString("EC", "text"),
            GetSQLValueString($_POST['ECP'], "text"),
            GetSQLValueString($_POST['ECS'], "text"),
            GetSQLValueString($_SESSION['Microservices_UserEmail'], "text"),
            GetSQLValueString($lastid, "int"),
            GetSQLValueString("BDA", "text"),
            GetSQLValueString($_POST['BDAP'], "text"),
            GetSQLValueString($_POST['BDAS'], "text"),
            GetSQLValueString($_SESSION['Microservices_UserEmail'], "text"),
            GetSQLValueString($lastid, "int"),
            GetSQLValueString("DBM", "text"),
            GetSQLValueString($_POST['DBMP'], "text"),
            GetSQLValueString($_POST['DBMS'], "text"),
            GetSQLValueString($_SESSION['Microservices_UserEmail'], "text"),
            GetSQLValueString($lastid, "int"),
            GetSQLValueString("ASA", "text"),
            GetSQLValueString($_POST['ASAP'], "text"),
            GetSQLValueString($_POST['ASAS'], "text"),
            GetSQLValueString($_SESSION['Microservices_UserEmail'], "text"),
            GetSQLValueString($lastid, "int"),
            GetSQLValueString("SP", "text"),
            GetSQLValueString($_POST['SPP'], "text"),
            GetSQLValueString($_POST['SPS'], "text"),
            GetSQLValueString($_SESSION['Microservices_UserEmail'], "text")
        );
        $res = $DTSB->insert_data($tblname, $mysql);
        echo '<script>console.log("Project Solution : ");</script>';
        echo '<script>console.log("    INSERT INTO sa_' . $tblname . " " . $mysql . '");</script>';
    } else {
        $condition = "project_id=" . $_POST['project_id'] . " AND solution_name='DCCI'";
        $mysql = sprintf(
            "`project_id`=%s, `solution_name`=%s, `product`=%s, `services`=%s, `modified_by`=%s",
            GetSQLValueString($lastid, "int"),
            GetSQLValueString("DCCI", "text"),
            GetSQLValueString($_POST['DCCIP'], "text"),
            GetSQLValueString($_POST['DCCIS'], "text"),
            GetSQLValueString($_SESSION['Microservices_UserEmail'], "text")
        );
        $res = $DTSB->update_data($tblname, $mysql, $condition);
        echo '<script>console.log("Project Solution : ");</script>';
        echo '<script>console.log("    UPDATE sa_' . $tblname . " SET " . $mysql . '");</script>';

        $condition = "project_id=" . $_POST['project_id'] . " AND solution_name='EC'";
        $mysql = sprintf(
            "`project_id`=%s, `solution_name`=%s, `product`=%s, `services`=%s, `modified_by`=%s",
            GetSQLValueString($lastid, "int"),
            GetSQLValueString("EC", "text"),
            GetSQLValueString($_POST['ECP'], "text"),
            GetSQLValueString($_POST['ECS'], "text"),
            GetSQLValueString($_SESSION['Microservices_UserEmail'], "text")
        );
        $res = $DTSB->update_data($tblname, $mysql, $condition);
        echo '<script>console.log("Project Solution : ");</script>';
        echo '<script>console.log("    UPDATE sa_' . $tblname . " SET " . $mysql . '");</script>';

        $condition = "project_id=" . $_POST['project_id'] . " AND solution_name='BDA'";
        $mysql = sprintf(
            "`project_id`=%s, `solution_name`=%s, `product`=%s, `services`=%s, `modified_by`=%s",
            GetSQLValueString($lastid, "int"),
            GetSQLValueString("BDA", "text"),
            GetSQLValueString($_POST['BDAP'], "text"),
            GetSQLValueString($_POST['BDAS'], "text"),
            GetSQLValueString($_SESSION['Microservices_UserEmail'], "text")
        );
        $res = $DTSB->update_data($tblname, $mysql, $condition);
        echo '<script>console.log("Project Solution : ");</script>';
        echo '<script>console.log("    UPDATE sa_' . $tblname . " SET " . $mysql . '");</script>';

        $condition = "project_id=" . $_POST['project_id'] . " AND solution_name='DBM'";
        $mysql = sprintf(
            "`project_id`=%s, `solution_name`=%s, `product`=%s, `services`=%s, `modified_by`=%s",
            GetSQLValueString($lastid, "int"),
            GetSQLValueString("DBM", "text"),
            GetSQLValueString($_POST['DBMP'], "text"),
            GetSQLValueString($_POST['DBMS'], "text"),
            GetSQLValueString($_SESSION['Microservices_UserEmail'], "text")
        );
        $res = $DTSB->update_data($tblname, $mysql, $condition);
        echo '<script>console.log("Project Solution : ");</script>';
        echo '<script>console.log("    UPDATE sa_' . $tblname . " SET " . $mysql . '");</script>';

        $condition = "project_id=" . $_POST['project_id'] . " AND solution_name='ASA'";
        $mysql = sprintf(
            "`project_id`=%s, `solution_name`=%s, `product`=%s, `services`=%s, `modified_by`=%s",
            GetSQLValueString($lastid, "int"),
            GetSQLValueString("ASA", "text"),
            GetSQLValueString($_POST['ASAP'], "text"),
            GetSQLValueString($_POST['ASAS'], "text"),
            GetSQLValueString($_SESSION['Microservices_UserEmail'], "text")
        );
        $res = $DTSB->update_data($tblname, $mysql, $condition);
        echo '<script>console.log("Project Solution : ");</script>';
        echo '<script>console.log("    UPDATE sa_' . $tblname . " SET " . $mysql . '");</script>';

        $condition = "project_id=" . $_POST['project_id'] . " AND solution_name='SP'";
        $mysql = sprintf(
            "`project_id`=%s, `solution_name`=%s, `product`=%s, `services`=%s, `modified_by`=%s",
            GetSQLValueString($lastid, "int"),
            GetSQLValueString("SP", "text"),
            GetSQLValueString($_POST['SPP'], "text"),
            GetSQLValueString($_POST['SPS'], "text"),
            GetSQLValueString($_SESSION['Microservices_UserEmail'], "text")
        );
        $res = $DTSB->update_data($tblname, $mysql, $condition);
        echo '<script>console.log("Project Solution : ");</script>';
        echo '<script>console.log("    UPDATE sa_' . $tblname . " SET " . $mysql . '");</script>';
    }

    save_SubSolution();

    // IMPLEMENTATION
    // SAVE OUTSOURCING
    function get_outsourcing($project_id, $type, $title, $price)
    {
        global $DTSB;
        $NewData = array();
        for ($i = 0; $i < COUNT($title); $i++) {
            array_push($NewData, $title[$i]);
            $mysql = sprintf("SELECT `addon_id`, `project_id`, `addon_title`, `addon_price`, `service_type`, `modified_by`, `modified_date` FROM `sa_trx_addon` WHERE `project_id` = %s AND `addon_title` = %s AND `service_type` = %s", GetSQLValueString($project_id, "int"), GetSQLValueString($title[$i], "text"), GetSQLValueString($type, "int"));
            echo '<script>console.log("    ' . $mysql . '");</script>';
            $rsCheckOut = $DTSB->get_sql($mysql);
            if ($rsCheckOut[2] > 0) {
                if ($title[$i] != "") {
                    if ($title[$i] != NULL && ($title[$i] != $rsCheckOut[0]['addon_title'] || $price[$i] != $rsCheckOut[0]['addon_price'])) {
                        $mysql = sprintf("UPDATE `sa_trx_addon` SET `addon_price` = %s, `modified_by` = %s WHERE `project_id` = %s AND `addon_title` = %s AND `addon_id` = %s", GetSQLValueString(deformat($price[$i]), "text"), GetSQLValueString(MYFULLNAME, "text"), GetSQLValueString($project_id, "int"), GetSQLValueString($title[$i], "text"), GetSQLValueString($rsCheckOut[0]['addon_id'], "int"));
                        $res = $DTSB->get_sql($mysql, false);
                        echo '<script>console.log("    ' . $mysql . '");</script>';
                    }
                }
            } else {
                if ($title[$i] != NULL) {
                    $mysql = sprintf("INSERT INTO `sa_trx_addon`(`project_id`, `addon_title`, `addon_price`, `service_type`, `modified_by`) VALUES(%s,%s,%s,%s,%s)", GetSQLValueString($project_id, "int"), GetSQLValueString($title[$i], "text"), GetSQLValueString(deformat($price[$i]), "double"), GetSQLValueString($type, "int"), GetSQLValueString(MYFULLNAME, "text"));
                    $res = $DTSB->get_sql($mysql, false);
                    echo '<script>console.log("    ' . $mysql . '");</script>';
                }
            }
        }
        // DELETE DATA 
        $mysql = sprintf("SELECT `addon_title` FROM `sa_trx_addon` WHERE `project_id` = %s AND `service_type` = %s", GetSQLValueString($project_id, "int"), GetSQLValueString($type, "int"),);
        $rsCheckSub = $DTSB->get_sql($mysql);
        echo '<script>console.log("    ' . $mysql . '");</script>';
        if ($rsCheckSub[2] > 0) {
            do {
                $key = array_search($rsCheckSub[0]['addon_title'], $NewData, true);
                if (strval(array_search($rsCheckSub[0]['addon_title'], $NewData, true)) != "") {
                } else {
                    if ($rsCheckSub[0]['addon_title'] != NULL) {
                        $mysql = sprintf("DELETE FROM `sa_trx_addon` WHERE `project_id` = %s AND `addon_title` = %s AND `service_type` = %s", GetSQLValueString($project_id, "int"), GetSQLValueString($rsCheckSub[0]['addon_title'], "text"), GetSQLValueString($type, "int"));
                    } else {
                        $mysql = sprintf("DELETE FROM `sa_trx_addon` WHERE `project_id` = %s AND `addon_title` IS NULL AND `service_type` = %s", GetSQLValueString($project_id, "int"), GetSQLValueString($type, "int"));
                    }
                    $res = $DTSB->get_sql($mysql, false);
                    echo '<script>console.log("    ' . $mysql . '");</script>';
                }
            } while ($rsCheckSub[0] = $rsCheckSub[1]->fetch_assoc());
        }
    }
    function delete_outsourcing($project_id, $type)
    {
        global $DTSB;
        $mysql = sprintf("DELETE FROM `sa_trx_addon` WHERE `project_id` = %s AND `service_type` = %s", GetSQLValueString($project_id, "int"), GetSQLValueString($type, "int"));
        $res = $DTSB->get_sql($mysql, false);
        echo '<script>console.log("    ' . $mysql . '");</script>';
    }

    // Implementation Oursourcing
    $type = 1;
    echo '<script>console.log("Addon Type : ' . $type . '");</script>';
    if (isset($_POST['i_out']["'title'"])) {
        get_outsourcing($lastid, $type, $_POST['i_out']["'title'"], $_POST['i_out']["'price'"]);
    } else {
        delete_outsourcing($lastid, $type);
    }
    // Maintenance Outsourcing
    $type = 2;
    echo '<script>console.log("Addon Type : ' . $type . '");</script>';
    if (isset($_POST['m_out']["'title'"])) {
        get_outsourcing($lastid, $type, $_POST['m_out']["'title'"], $_POST['m_out']["'price'"]);
    } else {
        delete_outsourcing($lastid, $type);
    }
    // Maintenance Package
    $type = 3;
    echo '<script>console.log("Addon Type : ' . $type . '");</script>';
    if (isset($_POST['m_pack']["'title'"])) {
        get_outsourcing($lastid, $type, $_POST['m_pack']["'title'"], $_POST['m_pack']["'price'"]);
    } else {
        delete_outsourcing($lastid, $type);
    }
    // Maintenance Existing Backup Unit
    $type = 4;
    echo '<script>console.log("Addon Type : ' . $type . '");</script>';
    if (isset($_POST['e_backup']["'title'"])) {
        get_outsourcing($lastid, $type, $_POST['e_backup']["'title'"], $_POST['e_backup']["'price'"]);
    } else {
        delete_outsourcing($lastid, $type);
    }
    // Maintenance Invetsment Backup Unit
    $type = 5;
    echo '<script>console.log("Addon Type : ' . $type . '");</script>';
    if (isset($_POST['inv_backup']["'title'"])) {
        // echo '<script>console.log("rrrrrrrrrr");</script>';
        get_outsourcing($lastid, $type, $_POST['inv_backup']["'title'"], $_POST['inv_backup']["'price'"]);
    } else {
        delete_outsourcing($lastid, $type);
    }
    // Warranty Cisco
    $type = 6;
    echo '<script>console.log("Addon Type : ' . $type . '");</script>';
    if (isset($_POST['warranty_Cisco']["'title'"])) {
        get_outsourcing($lastid, $type, $_POST['warranty_Cisco']["'title'"], $_POST['warranty_Cisco']["'price'"]);
    } else {
        delete_outsourcing($lastid, $type);
    }
    // Warranty Non-Cisco
    $type = 7;
    echo '<script>console.log("Addon Type : ' . $type . '");</script>';
    if (isset($_POST['warranty_nCisco']["'title'"])) {
        get_outsourcing($lastid, $type, $_POST['warranty_nCisco']["'title'"], $_POST['warranty_nCisco']["'price'"]);
    } else {
        delete_outsourcing($lastid, $type);
    }
    // Warranty Addon
    $type = 8;
    echo '<script>console.log("Addon Type : ' . $type . '");</script>';
    if (isset($_POST['warranty_addon']["'title'"])) {
        get_outsourcing($lastid, $type, $_POST['warranty_addon']["'title'"], $_POST['warranty_addon']["'price'"]);
    } else {
        delete_outsourcing($lastid, $type);
    }

    // END IMPLEMENTATION

?>
    <?php
    // IMPLEMENTATION INFORMATION SECTION
    //Save Implementation
    if (isset($_POST['i_tos_id'])) {
        $itosid = "";
        foreach ($_POST['i_tos_id'] as $tosid) {
            $itosid .= $tosid . ";";
        }

        $tblname = "trx_project_implementations";
        if ($_POST['version'] == 0) {
            insert_implementation($lastid, $itosid, 1, $band);
        } else {
            //Update implementation data
            $consumption = consumption_plan(deformat($_POST['i_price']), $band);
            $condition = "project_id = " . $_POST['project_id'] . " AND service_type = 1";
            $testExisting = $DTSB->get_data($tblname, $condition);
            if ($testExisting[2] > 0) {
                $mysql = sprintf(
                    "`project_id`=%s, `project_estimation`=%s, `project_estimation_id`=%s, `implementation_price`=%s, `implementation_price_list`=%s, `agreed_price`=%s, `tos_id`=%s, `tos_category_id`=%s, `bpd_total_location`=%s, `bpd_description`=%s, `bpd_price`=%s, `out_description`=%s, `service_type`=%s, `maintenance_package_price`=%s, `maintenance_addon_description`=%s, `modified_by`=%s, `consumption_plan`=%s, `file_backup`=%s",
                    GetSQLValueString($lastid, "int"),
                    GetSQLValueString($_POST['i_project_estimation'], "int"),
                    GetSQLValueString($_POST['i_project_estimation_id'], "int"),
                    GetSQLValueString(deformat($_POST['i_price']), "text"),
                    GetSQLValueString(deformat($_POST['i_price_list']), "text"),
                    GetSQLValueString(deformat($_POST['i_agreed_price']), "text"),
                    GetSQLValueString($itosid, "text"),
                    GetSQLValueString($_POST['i_tos_category_id'], "int"),
                    GetSQLValueString($_POST['i_bpd_total_location'], "int"),
                    GetSQLValueString($_POST['i_bpd_description'], "text"),
                    GetSQLValueString(deformat($_POST['i_bpd_price']), "text"),
                    GetSQLValueString($_POST['i_out_description'], "text"),
                    GetSQLValueString(1, "int"),
                    GetSQLValueString(0, "long"),
                    GetSQLValueString(0, "long"),
                    GetSQLValueString($_SESSION['Microservices_UserEmail'], "text"),
                    GetSQLValueString(deformat($consumption), "text"),
                    GetSQLValueString($_POST['fileName'], "text")
                ); //echo "<p>$mysql</p>";
                $res = $DTSB->update_data($tblname, $mysql, $condition);
            } else {
                insert_implementation($lastid, $itosid, 1, $band);
            }
        }
    }

    // Save Maintenance
    if (isset($_POST['m_tos_id'])) {
        $mtosid = $_POST['m_tos_id'] . ';';
        if (isset($_POST['m_smo_id'])) {
            foreach ($_POST['m_smo_id'] as $tosid) {
                $mtosid .= $tosid . ";";
            }
        }

        $tblname = "trx_project_implementations";
        if ($_POST['version'] == 0) {
            insert_implementation($lastid, $mtosid, 2, $band);
        } else {
            $consumption = consumption_plan(deformat($_POST['m_price']), $band);
            $condition = "project_id = " . $_POST['project_id'] . " AND service_type = 2";
            $testExisting = $DTSB->get_data($tblname, $condition);
            if ($testExisting[2] > 0) {
                $mysql = sprintf(
                    "`project_id`=%s, `project_estimation`=%s, `project_estimation_id`=%s, `implementation_price`=%s, `implementation_price_list`=%s, `agreed_price`=%s, `tos_id`=%s, `tos_category_id`=%s, `bpd_total_location`=%s, `bpd_description`=%s, `bpd_price`=%s, `out_description`=%s, `service_type`=%s, `maintenance_package_price`=%s, `maintenance_addon_description`=%s, `dedicate_backup`=%s, `modified_by`=%s, `consumption_plan`=%s, `file_backup`=%s",
                    GetSQLValueString($lastid, "int"),
                    GetSQLValueString($_POST['m_project_estimation'], "int"),
                    GetSQLValueString($_POST['m_project_estimation_id'], "int"),
                    GetSQLValueString(deformat($_POST['m_price']), "text"),
                    GetSQLValueString(deformat($_POST['m_price_list']), "text"),
                    GetSQLValueString(deformat($_POST['m_agreed_price']), "text"),
                    GetSQLValueString($mtosid, "text"),
                    GetSQLValueString($_POST['m_tos_category_id'], "int"),
                    GetSQLValueString($_POST['m_bpd_total_location'], "int"),
                    GetSQLValueString($_POST['m_bpd_description'], "text"),
                    GetSQLValueString(deformat($_POST['m_bpd_price']), "text"),
                    GetSQLValueString($_POST['m_out_description'], "text"),
                    GetSQLValueString(2, "int"),
                    GetSQLValueString(deformat($_POST['m_package_price']), "text"),
                    GetSQLValueString(deformat($_POST['m_total_pack_price']), "text"),
                    GetSQLValueString(isset($_POST['dedicate_backup']) ? $_POST['dedicate_backup'] : NULL, "int"),
                    GetSQLValueString($_SESSION['Microservices_UserEmail'], "text"),
                    GetSQLValueString(deformat($consumption), "text"),
                    GetSQLValueString($_POST['fileName'], "text")
                ); //echo "<p>$mysql</p>";
                $res = $DTSB->update_data($tblname, $mysql, $condition);
            } else {
                insert_implementation($lastid, $mtosid, 2, $band);
            }
        }
    }

    // Save warranty
    if (isset($_POST['w_tos_id'])) {
        $tblname = "trx_project_implementations";
        if ($_POST['version'] == 0) {
            insert_implementation($lastid, $_POST['w_tos_id'], 3, $band);
        } else {
            $condition = "project_id = " . $_POST['project_id'] . " AND service_type = 3";
            $testExisting = $DTSB->get_data($tblname, $condition);
            if ($testExisting[2] > 0) {
                $mysql = sprintf(
                    "`project_id`=%s, `project_estimation`=%s, `project_estimation_id`=%s, `implementation_price`=%s, `implementation_price_list`=%s, `agreed_price`=%s, `tos_id`=%s, `tos_category_id`=%s, `bpd_total_location`=%s, `bpd_description`=%s, `bpd_price`=%s, `out_description`=%s, `service_type`=%s, `maintenance_package_price`=%s, `maintenance_addon_description`=%s, `modified_by`=%s, `file_backup`=%s",
                    GetSQLValueString($lastid, "int"),
                    GetSQLValueString($_POST['w_project_estimation'], "int"),
                    GetSQLValueString($_POST['w_project_estimation_id'], "int"),
                    GetSQLValueString(deformat($_POST['w_price']), "text"),
                    GetSQLValueString('0', "text"),
                    GetSQLValueString('0', "text"),
                    GetSQLValueString($_POST['w_tos_id'], "text"),
                    GetSQLValueString('0', "int"),
                    GetSQLValueString('0', "int"),
                    GetSQLValueString(NULL, "text"),
                    GetSQLValueString('0', "text"),
                    GetSQLValueString(NULL, "text"),
                    GetSQLValueString(3, "int"),
                    GetSQLValueString('0', "text"),
                    GetSQLValueString('0', "text"),
                    GetSQLValueString($_SESSION['Microservices_UserEmail'], "text"),
                    GetSQLValueString($_POST['fileName'], "text")
                ); //echo "<p>$mysql</p>";
                $res = $DTSB->update_data($tblname, $mysql, $condition);
            } else {
                insert_implementation($lastid, $_POST['w_tos_id'], 3, $band);
            }
        }
    }

    // MANDAYS SECTION
    if (isset($_POST['bundling1'])) {

        function save_mandaysx($project_id, $brand, $resource_level, $catalog_id, $service_type, $mans, $mandays, $rate, $catalogType = 0)
        {
            global $DTSB;
            $xxx = ($resource_level - fmod($resource_level, 10)) / 10;
            $mysql =
                "SELECT
                    `project_mandays_id`, `project_id`, `resource_level`, `resource_catalog_id`, `mantotal`, `mandays`, `brand`, `value`, `catalog_type`, `service_type`, `modified_by`, `modified_date`
                FROM
                    `sa_trx_project_mandays`
                WHERE
                    `project_id` = " . $_POST['project_id'] . "
                    AND (`resource_level` - (`resource_level` % 10))/10 = " . $xxx . "
                    AND `service_type` = " . $service_type . "
                    AND `brand` = '" . $brand . "'";
            $rsExisting = $DTSB->get_sql($mysql);
            if ($mans != NULL || $mandays != NULL) {
                if ($rsExisting[2] == 0) {
                    $mysql = sprintf("INSERT INTO `sa_trx_project_mandays` (`project_id`, `resource_level`, `resource_catalog_id`, `mantotal`, `mandays`, `brand`, `value`, `catalog_type`, `service_type`, `modified_by`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)", GetSQLValueString($project_id, "int"), GetSQLValueString($resource_level, "int"), GetSQLValueString($catalog_id, "int"), GetSQLValueString($mans, "int"), GetSQLValueString($mandays, "int"), GetSQLValueString($brand, "text"), GetSQLValueString($rate, "int"), GetSQLValueString($catalogType, "int"), GetSQLValueString($service_type, "int"), GetSQLValueString(MYFULLNAME, "text"));
                    $res = $DTSB->get_sql($mysql, false);
                    echo '<script>console.log("save_mandaysx :");</script>';
                    echo '<script>console.log("    ' . $mysql . '");</script>';
                } else
                if ($mans == $rsExisting[0]['mantotal'] && $mandays == $rsExisting[0]['mandays'] && $brand == $rsExisting[0]['brand'] && $resource_level == $rsExisting[0]['resource_level'] && $service_type == $rsExisting[0]['service_type'] && $catalogType == $rsExisting[0]['catalog_type']) {
                    // echo '<script>console.log("'.$catalogType." - ".$rsExisting[0]['catalog_type'].'");</script>';
                } else {
                    $mysql = sprintf("UPDATE `sa_trx_project_mandays` SET `mantotal` = %s, `mandays` = %s, `brand` = %s, `modified_by` = %s, `value` = %s, `catalog_type` = %s WHERE `project_id` = %s AND `resource_level` = %s AND `service_type`= %s", GetSQLValueString($mans, "int"), GetSQLValueString($mandays, "int"), GetSQLValueString($brand, "text"), GetSQLValueString(MYFULLNAME, "text"), GetSQLValueString($rate, "int"), GetSQLValueString($catalogType, "int"), GetSQLValueString($_POST['project_id'], "int"), GetSQLValueString($resource_level, "int"), GetSQLValueString($service_type, "int"));
                    $rs = $DTSB->get_sql($mysql, false);
                    echo '<script>console.log("save_mandaysx :");</script>';
                    echo '<script>console.log("    ' . $mysql . '");</script>';
                }
            } else
            if ($rsExisting[2] > 0) {
                delete_mandays($resource_level, $service_type);
            }
        }

        function delete_mandays($resource_level, $service_type)
        {
            global $DTSB;
            $mysql = sprintf("DELETE FROM `sa_trx_project_mandays` WHERE `project_id` = %s AND `service_type` = %s AND `resource_level` = %s", GetSQLValueString($_POST['project_id'], "int"), GetSQLValueString($service_type, "int"), GetSQLValueString($resource_level, "int"));
            $res = $DTSB->get_sql($mysql, false);
            echo '<script>console.log("save_mandaysx :");</script>';
            echo '<script>console.log("    ' . $mysql . '");</script>';
        }

        // Function save Mandays
        if (isset($_POST['mandays'])) {
            $brandx = 1;
            foreach ($_POST['mandays'] as $brand) {
                $service_type = 1;
                if (!isset($brand["'type'"])) {
                    $brand["'type'"] = 0;
                }
                if (isset($brand["'PDMans'"]) && isset($brand["'PDMandays'"])) {
                    save_mandaysx($_POST['project_id'], $brand["'brand_name'"], "1$brandx", $_POST['i_tos_category_id'], $service_type, $brand["'PDMans'"], $brand["'PDMandays'"], $brand["'PDRate'"], $brand["'type'"]);
                } else {
                    delete_mandays("1$brandx", $service_type);
                }
                if (isset($brand["'PMMans'"]) && isset($brand["'PMMandays'"])) {
                    save_mandaysx($_POST['project_id'], $brand["'brand_name'"], "2$brandx", $_POST['i_tos_category_id'], $service_type, $brand["'PMMans'"], $brand["'PMMandays'"], $brand["'PMRate'"], $brand["'type'"]);
                } else {
                    delete_mandays("2$brandx", $service_type);
                }
                if (isset($brand["'PCMans'"]) && isset($brand["'PCMandays'"])) {
                    save_mandaysx($_POST['project_id'], $brand["'brand_name'"], "3$brandx", $_POST['i_tos_category_id'], $service_type, $brand["'PCMans'"], $brand["'PCMandays'"], $brand["'PCRate'"], $brand["'type'"]);
                } else {
                    delete_mandays("3$brandx", $service_type);
                }
                if (isset($brand["'PAMans'"]) && isset($brand["'PAMandays'"])) {
                    save_mandaysx($_POST['project_id'], $brand["'brand_name'"], "4$brandx", $_POST['i_tos_category_id'], $service_type, $brand["'PAMans'"], $brand["'PAMandays'"], $brand["'PARate'"], $brand["'type'"]);
                } else {
                    delete_mandays("4$brandx", $service_type);
                }
                if (isset($brand["'EEMans'"]) && isset($brand["'EEMandays'"])) {
                    save_mandaysx($_POST['project_id'], $brand["'brand_name'"], "5$brandx", $_POST['i_tos_category_id'], $service_type, $brand["'EEMans'"], $brand["'EEMandays'"], $brand["'EERate'"], $brand["'type'"]);
                } else {
                    delete_mandays("5$brandx", $service_type);
                }
                if (isset($brand["'EPMans'"]) && isset($brand["'EPMandays'"])) {
                    save_mandaysx($_POST['project_id'], $brand["'brand_name'"], "6$brandx", $_POST['i_tos_category_id'], $service_type, $brand["'EPMans'"], $brand["'EPMandays'"], $brand["'EPRate'"], $brand["'type'"]);
                } else {
                    delete_mandays("6$brandx", $service_type);
                }
                if (isset($brand["'EAMans'"]) && isset($brand["'EAMandays'"])) {
                    save_mandaysx($_POST['project_id'], $brand["'brand_name'"], "7$brandx", $_POST['i_tos_category_id'], $service_type, $brand["'EAMans'"], $brand["'EAMandays'"], $brand["'EARate'"], $brand["'type'"]);
                } else {
                    delete_mandays("7$brandx", $service_type);
                }
                $brandx++;
            }
        } else {
            $mysql = sprintf(
                "DELETE
                FROM
                    `sa_trx_project_mandays`
                WHERE
                    `project_id` = %s AND `service_type` = 1",
                GetSQLValueString($_POST['project_id'], "int")
            );
            $res = $DTSB->get_sql($mysql, false);
        }
    }

    // Save Mandays for Implementation
    $tblname = "trx_project_mandays";

    // Brand1
    $tblname = "mst_resource_catalogs";
    $resource = $DTSB->get_data($tblname);
    $dresource = $resource[0];
    $qresource = $resource[1];
    $resources = array($dresource['resource_qualification'] => $dresource['mandays']);
    while ($dresource = $qresource->fetch_assoc()) {
        $res1 = array($dresource['resource_qualification'] => $dresource['mandays']);
        $resources = array_merge($resources, $res1);
    }
    ?>
    <?php
    // Function save backup V2
    if (isset($_POST['save_service_budget'])) {
        $service_type = 2;
        if (isset($_POST['backup'])) {
            $brandx = 1;
            $brandexisting = array();
            $brandinvestment = array();
            foreach ($_POST['backup'] as $brand) {
                if (isset($brand["'existing_brand_name'"]) && isset($brand["'existing_price'"])) {
                    save_mandaysx($_POST['project_id'], $brand["'existing_brand_name'"], "1$brandx", $_POST['i_tos_category_id'], $service_type, NULL, $brand["'existing_price'"], NULL);
                    $xxx = array($brand["'existing_brand_name'"] => $brand["'existing_price'"]);
                    $brandexisting = array_merge($brandexisting, $xxx);
                } else {
                    delete_mandays("1$brandx", $service_type);
                }
                if (isset($brand["'investment_brand_name'"]) && isset($brand["'investment_price'"])) {
                    save_mandaysx($_POST['project_id'], $brand["'investment_brand_name'"], "2$brandx", $_POST['i_tos_category_id'], $service_type, NULL, $brand["'investment_price'"], NULL);
                    $xxx = array($brand["'investment_brand_name'"] => $brand["'investment_price'"]);
                    $brandinvestment = array_merge($brandinvestment, $xxx);
                } else {
                    delete_mandays("2$brandx", $service_type);
                }
                $brandx++;
            }
            // Existing
            $mysql = sprintf(
                "SELECT
                    `project_id`,
                    `brand`
                FROM
                    `sa_trx_project_mandays`
                WHERE
                    `project_id` = %s
                    AND `resource_level` LIKE %s
                    AND `service_type` = 2",
                GetSQLValueString($_POST['project_id'], "int"),
                GetSQLValueString("1%", "text")
            );
            $rsmandaysex = $DTSB->get_sql($mysql);
            if ($rsmandaysex[2] > 0) {
                do {
                    if (!isset($brandexisting[$rsmandaysex[0]['brand']])) {
                        $mysql = sprintf(
                            "DELETE
                            FROM
                                `sa_trx_project_mandays`
                            WHERE
                                `project_id` = %s
                                AND `resource_level` LIKE %s
                                AND `service_type` = 2
                                AND `brand` = %s",
                            GetSQLValueString($_POST['project_id'], "int"),
                            GetSQLValueString("1%", "text"),
                            GetSQLValueString($rsmandaysex[0]['brand'], "text")
                        );
                        $res = $DTSB->get_sql($mysql, false);
                    }
                } while ($rsmandaysex[0] = $rsmandaysex[1]->fetch_assoc());
            }
            // Investment
            $mysql = sprintf(
                "SELECT
                    `project_id`,
                    `brand`
                FROM
                    `sa_trx_project_mandays`
                WHERE
                    `project_id` = %s
                    AND `resource_level` LIKE %s
                    AND `service_type` = 2",
                GetSQLValueString($_POST['project_id'], "int"),
                GetSQLValueString("2%", "text")
            );
            $rsmandaysex = $DTSB->get_sql($mysql);
            if ($rsmandaysex[2] > 0) {
                do {
                    if (!isset($brandinvestment[$rsmandaysex[0]['brand']])) {
                        $mysql = sprintf(
                            "DELETE
                            FROM
                                `sa_trx_project_mandays`
                            WHERE
                                `project_id` = %s
                                AND `resource_level` LIKE %s
                                AND `service_type` = 2
                                AND `brand` = %s",
                            GetSQLValueString($_POST['project_id'], "int"),
                            GetSQLValueString("2%", "text"),
                            GetSQLValueString($rsmandaysex[0]['brand'], "text")
                        );
                        $res = $DTSB->get_sql($mysql, false);
                    }
                } while ($rsmandaysex[0] = $rsmandaysex[1]->fetch_assoc());
            }
        } else {
            $mysql = sprintf(
                "DELETE
                FROM
                    `sa_trx_project_mandays`
                WHERE
                    `project_id` = %s AND `service_type` = %s",
                GetSQLValueString($_POST['project_id'], "int"),
                GetSQLValueString($service_type, "int")
            );
            $res = $DTSB->get_sql($mysql, false);
        }
    }
    ?>

    <?php

    // APPROVAL SECTION
    $tblname = "trx_approval";
    $insert_approval = sprintf(
        "(approve_by, approve_note, approve_status, project_id) VALUES (%s,%s,%s,%s)",
        GetSQLValueString($_SESSION['Microservices_UserEmail'], "text"),
        GetSQLValueString($_POST['note_save'], "text"),
        GetSQLValueString("Draft", "text"),
        GetSQLValueString($lastid, "int")
    );
    $res = $DTSB->insert_data($tblname, $insert_approval);

    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Horeee!</strong> Data has been successfully added.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
} elseif (isset($_POST['submit_service_budget']) || isset($_POST['approval_service_budget']) || isset($_POST['reject_service_budget']) || isset($_POST['reopen_service_budget']) || isset($_POST['acknowledge_service_budget'])) {
    // Lock previouos data
    // Update status approval
    $tblname = "trx_project_list";
    $condition = "project_id='" . $_POST['project_id'] . "'";
    $createby = $DTSB->get_data($tblname, $condition);
    $dcreateby = $createby[0];

    $version = $_POST['version'];
    $msg1 = "<p>I have reviewed the service budget:" . "</p>";
    $to = '';
    $buttons = "";
    $hashkey = MD5(date("Y-m-d G:i:s"));

    $status_sb = 0;

    if (isset($_POST['submit_service_budget'])) {
        // SUBMIT
        // $approved = 'Submited';
        $status = 'submited';
        if ($_POST['sbtype'] == 0 && $dcreateby["amount_id"] < 200000000) {
            $email = $_SESSION['Microservices_UserEmail'];
        } else {
            if(isset($_POST['ps_account']) && $_POST['ps_account'] != '') {
                $psAccount = $DBHCM->split_email($_POST['ps_account']);
                $email = $psAccount[1];
            } else {
                $email = '';
            }
        }
        if($email != '') {
            $leader = get_leader($email, 1);
            $dleader = $leader[0];
            $qleader = $leader[1];
            $to_name = '';
            if ($leader[2] > 0) {
                do {
                    if ($dleader['leader_name'] == "Eddy Anthony") {
                        $leadername = $_SESSION['Microservices_UserName'] . "<" . $_SESSION['Microservices_UserEmail'] . ">";
                        $to_name = $_SESSION['Microservices_UserName'];
                    } else {
                        $leadername = $dleader['leader_name'] . "<" . $dleader['leader_email'] . ">; ";
                        $to_name = $dleader['leader_name'];
                    }
                    $to .= $leadername;
                } while ($dleader = $qleader->fetch_assoc());
            } else {
                $mysql = "SELECT `parent` FROM `sa_mst_employee_non` WHERE `employee_email` = '" . $email . "'";
                $rsNonEmp = $DBHCM->get_sql($mysql);
                if ($rsNonEmp[2] > 0) {
                    $mysql = "SELECT `parent` FROM `sa_mst_employee_non` WHERE `parent` = " . $rsNonEmp[0]['parent'];
                    $rsNonEmp = $DBHCM->get_sql($mysql);
                    $leadername = $rsNonEmp[0]['employee_name'] . "<" . $rsNonEmp[0]['employee_email'] . ">";
                    $to_name = $rsNonEmp[0]['employee_name'];
                }
            }
            $msg1 = "<p>I hereby submit a service budget for approval:</p>";
            $msg2 = "<p>Please review and give approval by clicking the Approve button below or the Reject button if rejected. The Approval and Reject buttons will expire in the next 7 (seven) days. If the expired button is approved, approval is done via the MSIZone application.</p>";
            $msg2 = "<p>The Approve and Reject buttons below only work for office networks and VPN. Citrix and public networks cannot use this facility.</p>";
            $msg2 = "<p style='color: red'>Warning: Do not share this email with other people because the approve and reject buttons below will be recorded in your name.</p>";
            $notes = $_POST['note_submited'];
            if (!isset($_GET['evaluation'])) {
                $buttons = "<tr><td style='padding:20px; border:thin solid #dadada; text-align:center'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id/components/modules/service_budget/mod_approval.php?id=$hashkey&status=1' style='background:#B22222; padding:15px; color:#fff; border:mediun solid #000; border-style: ridge;text-decoration:none'>Approve</a></td><td style='text-align:center'><a href='https://msizone.mastersystem.co.id/components/modules/service_budget/mod_approval.php?id=$hashkey&status=0' style='background:#B22222; padding:15px; color:#fff; border:mediun solid #000; border-style: ridge; text-decoration:none'>Reject</a></td></tr></table></td></tr>";
            } else {
                $buttons = '<tr><td style=\'padding:20px; border:thin solid #dadada; text-align:center\'><table width=\'100%\'><tr><td><a href=\'mailto:repoadmin@mastersystem.co.id?subject=[SBF] Approval SBF&body=[{ 
    "key": "' . $hashkey . '", 
    "note": "please provide notes here",
    "status": "approved"
    }]\' style="background:#1e90ff; padding:15px; color:#fff; border:mediun solid #000; border-style: ridge;text-decoration:none">Approve</a></td><td style="text-align:center"><a href=\'mailto:repoadmin@mastersystem.co.id?subject=[SBF] Approval SBF&body=[{ 
    "key": "' . $hashkey . '", 
    "note": "please provide notes here",
    "status": "rejected"
    }]\' style="background:#B22222; padding:15px; color:#fff; border:mediun solid #000; border-style: ridge; text-decoration:none">Reject</a></td></tr></table></td></tr>';
            }
        }
    } elseif (isset($_POST['approval_service_budget'])) {
        // APPROVED
        // $approved = '2';
        $status = 'approved';
        if (isset($_POST['sbtype']) && $_POST['sbtype'] == 0 && $dcreateby["amount_id"] < 200000000) {
            $email = $dcreateby['modified_by'];
        } else {
            if(isset($_POST['ps_account']) && $_POST['ps_account'] != '') {
                $psAccount = $DBHCM->split_email($_POST['ps_account']);
                $email = $psAccount[1];
            } else {
                $email = '';
            }  
        }
        if($email != "") {
            $owner = get_leader($email, 1);
            $downer = $owner[0];
            $to = $downer['employee_name'] . "<" . $downer['employee_email'] . ">;";
            $to_name = $downer['employee_name'];

            if ($_POST['bundling10'] == "1") {
                $tblname = "view_user_access";
                $condition = "`user_level` LIKE 'PMO Implementation'";
                $pmo = $DB->get_data($tblname, $condition);
                if ($pmo[2] > 0) {
                    do {
                        $to .= $pmo[0]['name'] . "<" . $pmo[0]['email'] . ">;";
                    } while ($pmo[0] = $pmo[1]->fetch_assoc());
                }
            }
            if ($_POST['bundling20'] == "2") {
                $tblname = "view_user_access";
                $condition = "`user_level` LIKE 'PMO Maintenance'";
                $pmo = $DB->get_data($tblname, $condition);
                if ($pmo[2] > 0) {
                    do {
                        $to .= $pmo[0]['name'] . "<" . $pmo[0]['email'] . ">;";
                    } while ($pmo[0] = $pmo[1]->fetch_assoc());
                }
            }
            $to .= "Syamsul Arham<syamsul@mastersystem.co.id>;Sales Admin<SalesAdmin@mastersystem.co.id>;" . $dcreateby['modified_by'] . ";" . $dcreateby['create_by'];

            $msg2 = "<p>I give approval to this service budget with the following notes: </p><p>" . $_POST['note_approved'] . "</p>";
            $notes = $_POST['note_approved'];

            // create SB Number
            $tblname = "trx_sb_number";
            $condition = "sb_number LIKE '" . date("Y") . "%'";
            $order = "sb_number_id DESC";
            $sbnumber = $DTSB->get_data($tblname, $condition, $order);
            $dsbnumber = $sbnumber[0];
            $sbnew = true;
            if ($sbnumber[2] > 0) {
                $condition = sprintf(
                    "sb_number LIKE %s AND (so_number = %s OR order_number = %s)",
                    GetSQLValueString(date("Y") . "%", "text"),
                    GetSQLValueString($_POST['so_number'], "text"),
                    GetSQLValueString($_POST['order_number'], "text")
                );
                $sbnumber2 = $DTSB->get_data($tblname, $condition, $order);
                if ($sbnumber2[2] == 0) {
                    $numberexp = explode("/", $dsbnumber['sb_number']);
                    $len = strlen($numberexp[3] + 1);
                    $number = str_repeat("0", 4 - $len) . $numberexp[3] + 1;
                    $sb_number = date("Y") . "/FSB/" . date("m") . "/" . $number;
                } else {
                    $sbnew = false;
                }
            } else {
                $sb_number = date("Y") . "/FSB/" . date("m") . "/0001";
            }
            if ($sbnew) {
                $insert = sprintf(
                    "(so_number, order_number, sb_number) VALUES (%s, %s, %s)",
                    GetSQLValueString($_POST['so_number'], "text"),
                    GetSQLValueString($_POST['order_number'], "text"),
                    GetSQLValueString($sb_number, "text")
                );
                $res = $DTSB->insert_data($tblname, $insert);
            }
        }
    } elseif (isset($_POST['reopen_service_budget'])) {
        // Un-Approved / REOPEN
        // $approved = '0';
        $status = 'reopen';
        $status_sb = '1';
        $email = $dcreateby['modified_by'];
        if ($email == "MSIZone<msizone@mastersystem.co.id>") {
            $email = $dcreateby['create_by'];
        }
        $owner = get_leader($email, 1);
        $downer = $owner[0];
        $to = $downer['employee_name'] . "<" . $downer['employee_email'] . ">; ";
        $to_name = $downer['employee_name'];
        $msg2 = "<p>I have reopened for the following reasons: </p><p>" . $_POST['note_reopen'] . ".</p><p>Please make improvements.</p>";
        $notes = $_POST['note_reopen'];
    } elseif (isset($_POST['reject_service_budget'])) {
        // Un-Approved / REJECT
        // $approved = '0';
        $status = 'rejected';
        $email = $dcreateby['modified_by'];
        $owner = get_leader($email, 1);
        $downer = $owner[0];
        $to = $downer['employee_name'] . "<" . $downer['employee_email'] . ">; ";
        $to_name = $downer['employee_name'];
        $msg2 = "<p>I rejected the request because:</p><p>" . $_POST['note_rejected'] . ".</p><p> Please make improvements.</p>";
        $notes = $_POST['note_rejected'];
    } elseif (isset($_POST['acknowledge_service_budget'])) {
        // Save Sub-Solution
        save_SubSolution();
        // Acknowledge
        $status = 'acknowledge';
        $email = $dcreateby['modified_by'];
        $owner = get_leader($email, 1);
        $downer = $owner[0];
        $to = $downer['employee_name'] . "<" . $downer['employee_email'] . ">;  PMO Implementation<pmo.implementasi@mastersystem.co.id>; PMO Maintenance<pmo.maintenance@mastersystem.co.id>";
        $to_name = $downer['employee_name'];
        $msg2 = "<p>I have given approval for this service budget to be assigned to the project with the following notes:</p><p>" . $_POST['note_acknowledge'] . ".</p><p> Please follow up.</p>";
        $notes = $_POST['note_acknowledge'];
        $version++;
        if ($_SESSION['Microservices_UserEmail'] == 'fortuna@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'lucky.andiani@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'ary.mulyati@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'pitasari.amanda@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'sumarno@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'ricky.pebriandi@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'anjas.permana@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'fernando.wangsa@mastersystem.co.id') {
            $pmo_ack = $_SESSION['Microservices_UserEmail'];
            $pm_wrike = $_POST['pm_wrike'];
            $projectID = $_POST['project_id'];
            $ProjectCode = $_POST['project_code'];
            $status_wrike = 'Approved';
            $sbf_role = $_POST['sbf_role'];
            $update = $DTSB->get_res("UPDATE sa_trx_project_list SET pmo_ack_implementation='$pmo_ack' WHERE project_id=$projectID");
            syncWrike($projectID, $ProjectCode, $pm_wrike, $status_wrike, $sbf_role, $namaFile = "WRIKE_BUDGET");
        } else if ($_SESSION['Microservices_UserEmail'] == 'aceng.zakariya@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'anggi.fachrizal@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'david.kusuma@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'miko.widiarta@mastersystem.co.id') {
            $pmo_ack = $_SESSION['Microservices_UserEmail'];
            $pm_wrike = $_POST['pm_wrike'];
            $projectID = $_POST['project_id'];
            $ProjectCode = $_POST['project_code'];
            $status_wrike = 'Approved';
            $sbf_role = $_POST['sbf_role'];
            $update = $DTSB->get_res("UPDATE sa_trx_project_list SET pmo_ack_maintenance='$pmo_ack' WHERE project_id=$projectID");
            syncWrikeMT($projectID, $ProjectCode, $pm_wrike, $status_wrike, $sbf_role, $namaFile = "WRIKE_BUDGET");
        }
    }

    // Set status project
    $tblname = "trx_project_list";
    if ($status_sb == 0) {
        $update = sprintf(
            "status=%s, modified_by=%s, version=%s",
            GetSQLValueString($status, "text"),
            GetSQLValueString($_SESSION['Microservices_UserEmail'], "text"),
            GetSQLValueString($version, "int")
        );
    } else {
        $update = sprintf(
            "status=%s, `status_ack`=%s, modified_by=%s, version=%s",
            GetSQLValueString($status, "text"),
            GetSQLValueString($status_sb, "int"),
            GetSQLValueString($_SESSION['Microservices_UserEmail'], "text"),
            GetSQLValueString($version, "int")
        );
    }
    $condition = "project_id=" . $_POST['project_id'];
    $res = $DTSB->update_data($tblname, $update, $condition);

    // Insert Approval information
    $tblname = "trx_approval";
    $insert = sprintf(
        "(approve_by, approve_status, approve_note, project_id) VALUES (%s, %s, %s, %s) ",
        GetSQLValueString($_SESSION["Microservices_UserEmail"], "text"),
        GetSQLValueString(ucwords($status), "text"),
        GetSQLValueString($notes, "text"),
        GetSQLValueString($_POST['project_id'], "int")
    );
    $res = $DTSB->insert_data($tblname, $insert);

    // Insert link approval
    if ($status == "approved" || $status == "rejected") {
        $mdlname = "HCM";
        $DBHCM = get_conn($mdlname);
        $leader = $DBHCM->get_leader_v2($_SESSION['Microservices_UserEmail']);

        $tblname = "trx_approval_link";
        $insert = sprintf(
            "(project_id, approval_key, created_date, expired_date, approval_by, approval_method) VALUES (%s, %s, %s, %s, %s, %s)",
            GetSQLValueString($_POST['project_id'], "int"),
            GetSQLValueString($hashkey, "text"),
            GetSQLValueString(date("Y-m-d G:i:s"), "date"),
            GetSQLValueString(date("Y-m-d G:i:s", strtotime("+1 week")), "date"),
            GetSQLValueString($leader[1][0], "text"),
            GetSQLValueString($status, "text")
        );
        $res = $DTSB->insert_data($tblname, $insert);
    }

    // Send email notification
    $owner = get_leader($_SESSION['Microservices_UserEmail'], 1);
    $downer = $owner[0];
    if ($owner[2] > 0) {
        $from = $downer['employee_name'] . "<" . $downer['employee_email'] . ">; ";
    } else {
        $mysql = "SELECT `parent` FROM `sa_mst_employee_non` WHERE `employee_email` = '" . $_SESSION['Microservices_UserEmail'] . "'";
        $rsNonEmp = $DBHCM->get_sql($mysql);
        if ($rsNonEmp[2] > 0) {
            $mysql = "SELECT `parent` FROM `sa_mst_employee_non` WHERE `parent` = " . $rsNonEmp[0]['parent'];
            $rsNonEmp = $DBHCM->get_sql($mysql);
            $from = $rsNonEmp[0]['employee_name'] . "<" . $rsNonEmp[0]['employee_email'] . ">";
        } else {
            $from = MYFULLNAME;
        }
    }
    $cc = $from;
    $bcc = "";
    $reply = $from;
    $subject = "[MSIZone] " . ucwords($status) . " Service Budget : " . $_POST['project_code'] . " - " . $_POST['customer_name'] . ' - ' . $_POST['project_name'];
    $msg = "<table width='100%'";
    $msg .= "<tr><td width='30%' rowspan='5'></td><td style='width:40%; border: thin solid #dadada; padding:20px'>";
    $msg .= "<img src='https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png'>";
    $msg .= "</td></tr>";
    $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'>";
    $msg .= "<br/>";
    $msg .= "<p>Dear " . $to_name . "</p>";
    $msg .= "<p>" . $msg1 . "</p>";
    $msg .= "<p>";
    $msg .= "<table style='width:100%;'>";
    $msg .= "<tr><td>FSB Number</td><td>: </td><td>" . $sb_number . "</td></tr>";
    $msg .= "<tr><td>Project Code</td><td>: </td><td>" . $_POST['project_code'] . "</td></tr>";
    $msg .= "<tr><td>SO Number</td><td>: </td><td>" . $_POST['so_number'] . "</td></tr>";
    $msg .= "<tr><td style='vertical-align:top'>Project Name </td><td style='vertical-align:top'>: </td><td style='vertical-align:top'>" . $_POST['project_name'] . "</td></tr>";
    $msg .= "<tr><td style='vertical-align:top'>Customer Name </td><td style='vertical-align:top'>: </td><td style='vertical-align:top'>" . $_POST['customer_name'] . "</td></tr>";
    $msg .= "</table>";
    $msg .= "</p>";
    $msg .= "<p>" . $msg2 . "</p>";
    $msg .= "<p>Thank you,<br/>";
    $msg .= $_SESSION['Microservices_UserName'] . "</p>";
    $msg .= "</td><td width='30%' rowspan='5'>";
    $msg .= $buttons;
    $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
    $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
    $msg .= "</table>";

    $headers = "From: " . $from . "\r\n" .
        "Reply-To: " . $reply . "\r\n" .
        "Cc: " . $cc . "\r\n" .
        "Bcc: " . $bcc . "\r\n" .
        "MIME-Version: 1.0" . "\r\n" .
        "Content-Type: text/html; charset=UTF-8" . "\r\n" .
        "X-Mailer: PHP/" . phpversion();
    if ($status == "acknowledge") {
        '';
    } else {
        $ALERT = new Alert();
        if (!mail($to, $subject, $msg, $headers)) {
            echo $ALERT->email_not_send();
        } else {
            echo $ALERT->email_send();
        }
    }
    // Save Notification in MSIZone
    $mdlname = "NOTIFICATION";
    $DBNOTIF = get_conn($mdlname);
    $tblname = "trx_notification";
    $notifmsg = $sb_number . "; " . $_POST['project_code'] . "; " . $_POST['so_number'] . "; " . $_POST['project_name'] . "; " . $_POST['customer_name'] . ";";
    $notif_link = "index.php?mod=service_budget&act=edit&project_code=" . $_POST['project_code'] . "&so_number=" . $_POST['so_number'] . "&order_number=" . $_POST['order_number'] . "&submit=Submit";
    $insert = sprintf(
        "(`notif_from`, `notif_to`, `notif_subject`, `notif_post`, `notif_link`) VALUES (%s, %s, %s, %s, %s)",
        GetSQLValueString($from, "text"),
        GetSQLValueString($to, "text"),
        GetSQLValueString(ucwords($status) . " SB " . $_POST['project_code'], "text"),
        GetSQLValueString($notifmsg, "text"),
        GetSQLValueString($notif_link, "text")
    );
    $res = $DBNOTIF->insert_data($tblname, $insert);
} elseif (isset($_GET['save_project_name_internal'])) {
    // EDIT PROJECT NAME INTERNAL
    $tblname = "trx_project_list";
    $condition = "project_code = '" . $_GET['project_code'] . "'";
    if (isset($_GET['so_number']) && $_GET['so_number'] != "") {
        $condition .= " AND so_number = '" . $_GET['so_number'] . "'";
    }
    if (isset($_GET['order_number']) && $_GET['order_number'] != "") {
        $condition .= " AND order_number = '" . $_GET['order_number'] . "'";
    }
    $condition .= "";
    $update = sprintf(
        "project_name=%s, project_name_internal=%s, modified_by=%s",
        GetSQLValueString(addslashes($_GET['note_project_name']), "text"),
        GetSQLValueString(addslashes($_GET['note_project_name_internal']), "text"),
        GetSQLValueString($_SESSION['Microservices_UserEmail'], "text")
    );
    $res = $DTSB->update_data($tblname, $update, $condition);
} elseif (isset($_POST['save_so'])) {
    $tblname = "trx_project_list";
    $condition = "project_code = '" . $_POST['project_code'] . "' AND (order_number = '" . $_POST['order_number'] . "' OR so_number='" . $_POST['so_number'] . "')";;
    $update = sprintf(
        "`so_number`=%s, `amount_idr`=%s, `amount_usd`=%s, `po_number`=%s, `po_date`=%s, modified_by=%s",
        GetSQLValueString($_POST['so_number'], "text"),
        GetSQLValueString(deformat($_POST['amount_idr']), "text"),
        GetSQLValueString(deformat($_POST['amount_usd']), "text"),
        GetSQLValueString($_POST['po_number'], "text"),
        GetSQLValueString(date("Y-m-d G:i:s", strtotime($_POST['po_date'])), "date"),
        GetSQLValueString($_SESSION['Microservices_UserEmail'], "text")
    );
    $update = sprintf(
        "`so_number`=%s, `amount_idr`=%s, `amount_usd`=%s, `po_number`=%s, `po_date`=%s, modified_by=%s",
        GetSQLValueString($_POST['so_number'], "text"),
        GetSQLValueString(deformat($_POST['amount_idr']), "text"),
        GetSQLValueString(deformat($_POST['amount_usd']), "text"),
        GetSQLValueString($_POST['po_number'], "text"),
        GetSQLValueString(date("Y-m-d G:i:s", strtotime($_POST['po_date'])), "date"),
        GetSQLValueString($_SESSION['Microservices_UserEmail'], "text")
    );
    $res = $DTSB->update_data($tblname, $update, $condition);
}


function syncWrike($projectID, $ProjectCode, $pm_wrike, $status_wrike, $sbf_role, $namaFile = "WRIKE_BUDGET")
{
    error_reporting(0);
    ini_set('display_errors', 0);
    $db_workload = 'WORKLOAD';
    $DBWRKLD = get_conn($db_workload);

    $gdgd = "GOOGLE_DRIVE";
    $DBGD = get_conn($gdgd);

    $wrwr = "WRIKE_INTEGRATE";
    $DBWR = get_conn($wrwr);

    $db_sb = "SERVICE_BUDGET";
    $DBSB = get_conn($db_sb);

    $owner_wrike = $pm_wrike;

    $API_WRIKE = new API_WRIKE;

    $querySB = "SELECT a.project_id, a.project_code, a.project_name, a.project_name_internal, a.order_number, a.amount_idr, a.amount_usd, a.so_number, b.implementation_price, a.bundling, b.tos_id, b.tos_category_id, b.service_type, a.status, a.create_date 
    FROM sa_trx_project_list AS a 
    LEFT JOIN sa_trx_project_implementations AS b ON a.project_id=b.project_id
    WHERE a.project_id=$projectID";

    $tbl_wrike_config = 'wrike_config';
    $tbl_trx_project_list = 'trx_project_list';
    $conditionSB = "status = 'acknowledge'";
    $dataSB = $DBSB->get_sql($querySB);
    $rowSB = $dataSB[0];
    $resSB = $dataSB[1];
    $totalRowSB = $dataSB[2];

    $tbl_trx_project_implementations = 'trx_project_implementations';
    $dataSBExpand = $DBSB->get_data($tbl_trx_project_list);
    $rowSBExpand = $dataSBExpand[0];
    $resSBExpand = $dataSBExpand[1];
    $totalRowSBExpand = $dataSBExpand[2];

    $tbl_initial_project = "initial_project";
    $tbl_wrike_project_list = "wrike_project_list";

    do {
        $projectId = $rowSB['project_id'];
        $projectCode = $rowSB['project_code'];
        $soNumber = $rowSB['so_number'];
        $orderNumber = $rowSB['order_number'];
        $projectName = $rowSB['project_name'];
        $internalProjectName = $rowSB['project_name_internal'];
        $projectNameExplode = explode('#', $projectName);
        $tosId = $rowSB['tos_id'];
        $projectCategory = $rowSB['tos_category_id'];
        $serviceType = $rowSB['service_type'];
        $tosName = '';

        if ($serviceType == 1 && $projectCategory == 1) {
            $serviceType = "Implementation";
            $projectCategoryDescription = "High";
            $conditionImplementation = "object = 'Blueprint' AND condition1 = '$serviceType' AND condition2 = '$projectCategoryDescription'";
            $configImplementation = $DBWR->get_data($tbl_wrike_config, $conditionImplementation);
            $blueprintId = $configImplementation[0]['object_id'];

            // date_default_timezone_set('Indonesia/Jakarta');
            $date = date('Y-m-d', time());

            //Check Table Initial Project
            $conditionInitialImplementation = "project_code='$projectCode' AND project_type='$serviceType' AND service_type = '$projectCategoryDescription' AND order_number = '$orderNumber'";
            $dataInitialProject = $DBWR->get_data($tbl_initial_project, $conditionInitialImplementation);
            $totalRowInitialProject = $dataInitialProject[2];

            //Check Table Wrike Project List Implementation
            $conditionWrikeProjectList = "project_code = '$projectCode' AND order_number = '$orderNumber' AND project_type = 'MSI Project Implementation'";
            $dataWrikeProjectList = $DBWR->get_data($tbl_wrike_project_list, $conditionWrikeProjectList);
            $totalRowWPL = $dataWrikeProjectList[2];

            if ($totalRowWPL > 0) {
                '';
            } else {
                if ($totalRowInitialProject > 0) {
                    '';
                } else {
                    $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6OTIzOTA4MSxcImNcIjo0NjcxMzc3LFwidVwiOjE2NDc3MzM1LFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNzMxNDg0MDI4fQ.Ggy6YbLvLiIr_Yrfnyfy73yY8g1ts8bRRkrhpqvVV04";
                    $url = "https://www.wrike.com/api/v4/folder_blueprints/$blueprintId/launch_async";
                    //?parent=IEAEOPF5I4U765EL&title=".$projectNameExplode[0]."&titlePrefix=".$projectNameExplode[1]."&copyCustomFields=true&rescheduleMode=Start&rescheduleDate=$date";
                    $data = array('parent' => "IEAEOPF5I5AAFTHB", 'title' => "" . $projectNameExplode[0] . "", 'titlePrefix' => "[" . $internalProjectName . "] ", 'copyCustomFields' => "true", 'rescheduleMode' => "Start", 'rescheduleDate' => "$date");
                    $postdata = json_encode($data);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                    $result = curl_exec($ch);
                    curl_close($ch);
                    // print_r($result);

                    '';

                    $result = json_decode($result, true);
                    $jobId = $result['data'][0]['id'];

                    $insertJobId = sprintf(
                        "(`project_code`,`job_id`, `project_type`, `service_type`, `order_number`,`owner_project`,`approval_status`,`sb_role`) VALUES ('$projectCode', '$jobId' , '$serviceType', '$projectCategoryDescription', '$orderNumber','$owner_wrike','$status_wrike','$sbf_role')",
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($jobId, "text"),
                        GetSQLValueString($serviceType, "text"),
                        GetSQLValueString($projectCategoryDescription, "text"),
                        GetSQLValueString($orderNumber, "text"),
                        GetSQLValueString($owner_wrike, "text"),
                        GetSQLValueString($status_wrike, "text")
                    );

                    // $resJobId = $DBWR->insert_data($tbl_initial_project, $insertJobId);
                    $resJobId = $DBWR->get_res("INSERT INTO sa_initial_project (`project_code`,`job_id`, `project_type`, `service_type`, `order_number`,`owner_project`,`approval_status`,`sb_role`) VALUES ('$projectCode', '$jobId' , '$serviceType', '$projectCategoryDescription', '$orderNumber','$owner_wrike','$status_wrike','$sbf_role')");
                    // updateFile($namaFile, 'get_data_sb()', 'tabel : tbl_initial_project insert : ' . $insertJobId);

                    // echo "$projectId - $projectCode - $projectName - $serviceType - $projectCategoryDescription - $blueprintId - $jobId <br/>";



                    //INSERT LOG BLUEPRINT IMPLEMENTATION
                    $tbl_sa_log_activity = 'log_activity';
                    $insertLogBlueprintImplementation = sprintf(
                        "(`activity`) VALUES ('Created $serviceType - $projectCategoryDescription Blueprint in $projectCode with job id $jobId')",
                        GetSQLValueString($serviceType, "text"),
                        GetSQLValueString($projectCategoryDescription, "text"),
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($jobId, "text")
                    );

                    $resLogImplementation = $DBWR->insert_data($tbl_sa_log_activity, $insertLogBlueprintImplementation);
                    // updateFile($namaFile, 'get_data_sb()', 'tabel : tbl_sa_log_activity insert : ' . $insertLogBlueprintImplementation);
                }
            }
        } else if ($serviceType == 1 && $projectCategory == 2) {
            $serviceType = "Implementation";
            $projectCategoryDescription = "Medium";
            $conditionImplementation = "object = 'Blueprint' AND condition1 = '$serviceType' AND condition2 = '$projectCategoryDescription'";
            $configImplementation = $DBWR->get_data($tbl_wrike_config, $conditionImplementation);
            $blueprintId = $configImplementation[0]['object_id'];

            // date_default_timezone_set('Indonesia/Jakarta');
            $date = date('Y-m-d', time());

            //Check Table Initial Project
            $conditionInitialImplementation = "project_code='$projectCode' AND project_type='$serviceType' AND service_type = '$projectCategoryDescription' AND order_number = '$orderNumber'";
            $dataInitialProject = $DBWR->get_data($tbl_initial_project, $conditionInitialImplementation);
            $totalRowInitialProject = $dataInitialProject[2];

            //Check Table Wrike Project List Implementation
            $conditionWrikeProjectList = "project_code = '$projectCode' AND order_number = '$orderNumber' AND project_type = 'MSI Project Implementation'";
            $dataWrikeProjectList = $DBWR->get_data($tbl_wrike_project_list, $conditionWrikeProjectList);
            $totalRowWPL = $dataWrikeProjectList[2];

            if ($totalRowWPL > 0) {
                '';
            } else {
                if ($totalRowInitialProject > 0) {
                    '';
                } else {
                    $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6OTIzOTA4MSxcImNcIjo0NjcxMzc3LFwidVwiOjE2NDc3MzM1LFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNzMxNDg0MDI4fQ.Ggy6YbLvLiIr_Yrfnyfy73yY8g1ts8bRRkrhpqvVV04";
                    $url = "https://www.wrike.com/api/v4/folder_blueprints/$blueprintId/launch_async";
                    //?parent=IEAEOPF5I4U765EL&title=".$projectNameExplode[0]."&titlePrefix=".$projectNameExplode[1]."&copyCustomFields=true&rescheduleMode=Start&rescheduleDate=$date";
                    $data = array('parent' => "IEAEOPF5I5AAFTHB", 'title' => "" . $projectNameExplode[0] . "", 'titlePrefix' => "[" . $internalProjectName . "] ", 'copyCustomFields' => "true", 'rescheduleMode' => "Start", 'rescheduleDate' => "$date");
                    $postdata = json_encode($data);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                    $result = curl_exec($ch);
                    curl_close($ch);


                    '';

                    $result = json_decode($result, true);
                    $jobId = $result['data'][0]['id'];

                    $insertJobId = sprintf(
                        "(`project_code`,`job_id`, `project_type`, `service_type`, `order_number`,`owner_project`,`approval_status`) VALUES ('$projectCode', '$jobId' , '$serviceType', '$projectCategoryDescription', '$orderNumber','$owner_wrike','$status_wrike')",
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($jobId, "text"),
                        GetSQLValueString($serviceType, "text"),
                        GetSQLValueString($orderNumber, "text"),
                        GetSQLValueString($owner_wrike, "text"),
                        GetSQLValueString($status_wrike, "text")
                    );

                    // $resJobId = $DBWR->insert_data($tbl_initial_project, $insertJobId);
                    $resJobId = $DBWR->get_res("INSERT INTO sa_initial_project (`project_code`,`job_id`, `project_type`, `service_type`, `order_number`,`owner_project`,`approval_status`,`sb_role`) VALUES ('$projectCode', '$jobId' , '$serviceType', '$projectCategoryDescription', '$orderNumber','$owner_wrike','$status_wrike','$sbf_role')");
                    // updateFile($namaFile, 'get_data_sb()', 'tabel : tbl_initial_project insert : ' . $insertJobId);

                    '';


                    //INSERT LOG BLUEPRINT IMPLEMENTATION
                    $tbl_sa_log_activity = 'log_activity';
                    $insertLogBlueprintImplementation = sprintf(
                        "(`activity`) VALUES ('Created $serviceType - $projectCategoryDescription Blueprint in $projectCode with job id $jobId')",
                        GetSQLValueString($serviceType, "text"),
                        GetSQLValueString($projectCategoryDescription, "text"),
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($jobId, "text")
                    );

                    $resLogImplementation = $DBWR->insert_data($tbl_sa_log_activity, $insertLogBlueprintImplementation);
                    // updateFile($namaFile, 'get_data_sb()', 'tabel : tbl_sa_log_activity insert : ' . $insertLogBlueprintImplementation);
                }
            }
        } else if ($serviceType == 1 && $projectCategory == 3) {
            $serviceType = "Implementation";
            $projectCategoryDescription = "Standard";
            $conditionImplementation = "object = 'Blueprint' AND condition1 = '$serviceType' AND condition2 = '$projectCategoryDescription'";
            $configImplementation = $DBWR->get_data($tbl_wrike_config, $conditionImplementation);
            $blueprintId = $configImplementation[0]['object_id'];

            // date_default_timezone_set('Indonesia/Jakarta');
            $date = date('Y-m-d', time());

            //Check Table Initial Project
            $conditionInitialImplementation = "project_code='$projectCode' AND project_type='$serviceType' AND service_type = '$projectCategoryDescription' AND order_number = '$orderNumber'";
            $dataInitialProject = $DBWR->get_data($tbl_initial_project, $conditionInitialImplementation);
            $totalRowInitialProject = $dataInitialProject[2];

            //Check Table Wrike Project List Implementation
            $conditionWrikeProjectList = "project_code = '$projectCode' AND order_number = '$orderNumber' AND project_type = 'MSI Project Implementation'";
            $dataWrikeProjectList = $DBWR->get_data($tbl_wrike_project_list, $conditionWrikeProjectList);
            $totalRowWPL = $dataWrikeProjectList[2];

            if ($totalRowWPL > 0) {
                '';
            } else {
                if ($totalRowInitialProject > 0) {
                    '';
                } else {
                    $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6OTIzOTA4MSxcImNcIjo0NjcxMzc3LFwidVwiOjE2NDc3MzM1LFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNzMxNDg0MDI4fQ.Ggy6YbLvLiIr_Yrfnyfy73yY8g1ts8bRRkrhpqvVV04";
                    $url = "https://www.wrike.com/api/v4/folder_blueprints/$blueprintId/launch_async";
                    //?parent=IEAEOPF5I4U765EL&title=".$projectNameExplode[0]."&titlePrefix=".$projectNameExplode[1]."&copyCustomFields=true&rescheduleMode=Start&rescheduleDate=$date";
                    $data = array('parent' => "IEAEOPF5I5AAFTHB", 'title' => "" . $projectNameExplode[0] . "", 'titlePrefix' => "[" . $internalProjectName . "] ", 'copyCustomFields' => "true", 'rescheduleMode' => "Start", 'rescheduleDate' => "$date");
                    $postdata = json_encode($data);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                    $result = curl_exec($ch);
                    curl_close($ch);


                    '';

                    $result = json_decode($result, true);
                    $jobId = $result['data'][0]['id'];

                    $insertJobId = sprintf(
                        "(`project_code`,`job_id`, `project_type`, `service_type`, `order_number`,`owner_project`,`approval_status`) VALUES ('$projectCode', '$jobId' , '$serviceType', '$projectCategoryDescription', '$orderNumber','$owner_wrike','$status_wrike')",
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($jobId, "text"),
                        GetSQLValueString($serviceType, "text"),
                        GetSQLValueString($orderNumber, "text"),
                        GetSQLValueString($owner_wrike, "text"),
                        GetSQLValueString($status_wrike, "text")
                    );

                    $resJobId = $DBWR->get_res("INSERT INTO sa_initial_project (`project_code`,`job_id`, `project_type`, `service_type`, `order_number`,`owner_project`,`approval_status`,`sb_role`) VALUES ('$projectCode', '$jobId' , '$serviceType', '$projectCategoryDescription', '$orderNumber','$owner_wrike','$status_wrike','$sbf_role')");

                    // '';
                    '';


                    //INSERT LOG BLUEPRINT IMPLEMENTATION
                    $tbl_sa_log_activity = 'log_activity';
                    $insertLogBlueprintImplementation = sprintf(
                        "(`activity`) VALUES ('Created $serviceType - $projectCategoryDescription Blueprint in $projectCode with job id $jobId')",
                        GetSQLValueString($serviceType, "text"),
                        GetSQLValueString($projectCategoryDescription, "text"),
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($jobId, "text")
                    );

                    $resLogImplementation = $DBWR->insert_data($tbl_sa_log_activity, $insertLogBlueprintImplementation);
                    // updateFile($namaFile, 'get_data_sb()', 'tabel : tbl_sa_log_activity insert : ' . $insertLogBlueprintImplementation);
                }
            }
        } else {
            '';
        }
    } while ($rowSB = $resSB->fetch_assoc());
    sleep(15);
    $tbl_initial_project = 'initial_project';
    $dataInitialProject = $DBWR->get_sql("SELECT * FROM sa_initial_project WHERE status=1 AND project_code='$ProjectCode'");
    $rowInitialProject = $dataInitialProject[0];
    $resInitialProject = $dataInitialProject[1];
    $totalRowInitialProject = $dataInitialProject[2];

    do {
        $projectCode = $rowInitialProject['project_code'];
        $jobId = $rowInitialProject['job_id'];
        $status = $rowInitialProject['status'];
        $prjctTypeee = $rowInitialProject['project_type'];
        '';
        // if ($status == 1) {
        $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6OTIzOTA4MSxcImNcIjo0NjcxMzc3LFwidVwiOjE2NDc3MzM1LFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNzMxNDg0MDI4fQ.Ggy6YbLvLiIr_Yrfnyfy73yY8g1ts8bRRkrhpqvVV04";
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, "https://www.wrike.com/api/v4/async_job/$jobId");
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);

        $result = json_decode($result, true);

        //GET Customer Name
        $data = $result['data'];

        for ($i = 0; $i < count($data); $i++) {
            $folderId = $data[0]['result']['folderId'];

            $conditionUpdateFolderId = "job_id = '$jobId'";

            if ($folderId != '') {
                $updateFolderIdData = sprintf(
                    "`project_id`= '$folderId', `status` = 2",
                    GetSQLValueString($folderId, "text")
                );

                $resFolderIdData = $DBWR->update_data($tbl_initial_project, $updateFolderIdData, $conditionUpdateFolderId);
                if ($prjctTypeee == 'Implementation') {
                    $get_informasi_project = $DBSB->get_sql("SELECT * FROM sa_trx_project_list WHERE project_id=$projectID");
                    $ordrNumber = $get_informasi_project[0]['order_number'];
                    $cstmrName = $get_informasi_project[0]['customer_name'];
                    $prjctName = addslashes($get_informasi_project[0]['project_name']);
                    $soNo = $get_informasi_project[0]['so_number'];
                    $prjctType = 'Implementation';
                    $mdlname = "HCM";
                    $DBHCM = get_conn($mdlname);
                    $getName = $DBHCM->get_sql("SELECT * FROM sa_view_employees_v2 WHERE employee_email='$pm_wrike'");
                    $namaorang = $getName[0]['employee_name'];
                    $rsrcEmail = addslashes("$namaorang<$pm_wrike>");
                    $rls = $sbf_role . " -";
                    $prjctRoles = 'Project Leader';
                    $approval = $_SESSION['Microservices_UserEmail'];
                    $create_prochar = $DBWR->get_res("INSERT INTO sa_resource_assignment (project_code,project_id,order_number,no_so,project_type,customer_name,project_name,resource_email,roles,project_roles,start_progress,end_progress,status,approval_status,approval_to,created_by,modified_by,flag_assign_wrike) VALUES ('$ProjectCode','$folderId','$ordrNumber','$soNo','$prjctType','$cstmrName','$prjctName','$rsrcEmail','$rls','$prjctRoles',0,100,'Penuh','approved','$approval','$approval','$approval',0)");
                }
                // $infoOutput .= 'tabel : ' . $tbl_initial_project . ' update : ' . $updateFolderIdData . ' condition : ' . $conditionUpdateFolderId . '</br>';
                '';
            }
        }

        //INSERT LOG ASYNCJOB
        $tbl_sa_log_activity = 'log_activity';
        $insertLogAsyncJob = sprintf(
            "(`activity`) VALUES ('Asynced project $projectCode with job id $jobId')",
            GetSQLValueString($projectCode, "text")
        );

        $resLogAsync = $DBWR->insert_data($tbl_sa_log_activity, $insertLogAsyncJob);
        // $infoOutput .= 'tabel : ' . $tbl_sa_log_activity . ' insert : ' . $insertLogAsyncJob . '</br>';
        // }
    } while ($rowInitialProject = $resInitialProject->fetch_assoc());

    '';
    sleep(10);
    $querySQLIP = "SELECT * FROM sa_initial_project WHERE status = 2 AND project_id != '' AND project_code='$ProjectCode'";
    $dataSQLIP = $DBWR->get_sql($querySQLIP);
    $rowSQLIP = $dataSQLIP[0];
    $resSQLIP = $dataSQLIP[1];

    if ($dataSQLIP[2] == 0) {
        return 'Intial Project tidak ada yang memiliki status 2';
    }

    do {
        $projectCode = $rowSQLIP['project_code'];
        $projectId = $rowSQLIP['project_id'];
        $projectType = $rowSQLIP['project_type'];
        $OrderNumber = $rowSQLIP['order_number'];

        if ($projectType == 'Implementation') {
            $projectType = 1;
        } else {
            $projectType = 2;
        }

        $sqlGetProjectInternal = "SELECT * FROM sa_trx_project_list WHERE project_code = '$projectCode' AND order_number='$OrderNumber' AND STATUS = 'acknowledge'";
        $dataProjectInternal = $DBSB->get_sql($sqlGetProjectInternal);
        $projectNameInternalSB = $dataProjectInternal[0]['project_name_internal'];

        // $projectNameInternal = "[$projectNameInternalSB] Project Resources";
        // $projectNameInternal = "[$projectNameInternalSB] Service Budget";

        $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6OTIzOTA4MSxcImNcIjo0NjcxMzc3LFwidVwiOjE2NDc3MzM1LFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNzMxNDg0MDI4fQ.Ggy6YbLvLiIr_Yrfnyfy73yY8g1ts8bRRkrhpqvVV04";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://www.wrike.com/api/v4/folders/$projectId");
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($result, true);

        //GET Customer Name
        $data = $result['data'];

        for ($i = 0; $i < count($data); $i++) {
            $projectIdWrike = $data[$i]['id'];
            $jobRoleFolderIdArray = $data[$i]['childIds'];
            $title = $data[$i]['title'];

            for ($j = 0; $j < count($jobRoleFolderIdArray); $j++) {
                $jobRoleFolderId = $jobRoleFolderIdArray[$j];
                $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6OTIzOTA4MSxcImNcIjo0NjcxMzc3LFwidVwiOjE2NDc3MzM1LFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNzMxNDg0MDI4fQ.Ggy6YbLvLiIr_Yrfnyfy73yY8g1ts8bRRkrhpqvVV04";
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, "https://www.wrike.com/api/v4/folders/$jobRoleFolderId");
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                $result1 = curl_exec($curl);
                curl_close($curl);
                $result2 = json_decode($result1, true);

                //GET Customer Name
                $jobrolesName = $result2['data'][0]['title'];

                if (strpos($jobrolesName, 'Job Role') !== false) {
                    $jobrolesChildId = $result2['data'][0]['childIds'];
                    //var_dump($jobrolesChildId);

                    for ($i1 = 0; $i1 < count($jobrolesChildId); $i1++) {
                        $jobrolesChildId1 = $jobrolesChildId[$i1];

                        $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6OTIzOTA4MSxcImNcIjo0NjcxMzc3LFwidVwiOjE2NDc3MzM1LFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNzMxNDg0MDI4fQ.Ggy6YbLvLiIr_Yrfnyfy73yY8g1ts8bRRkrhpqvVV04";
                        $curl = curl_init();
                        curl_setopt($curl, CURLOPT_URL, "https://www.wrike.com/api/v4/folders/$jobrolesChildId1");
                        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                        $result2 = curl_exec($curl);
                        curl_close($curl);
                        $result3 = json_decode($result2, true);

                        //GET Customer Name
                        $sbName = $result3['data'][0]['title'];

                        '';

                        if (strpos($sbName, 'Service Budget') !== false) {
                            $sbFolderId = $result3['data'][0]['id'];
                            '';
                        }
                    }
                }
            }
        }

        $sqlMandaysInformation = "SELECT * FROM sa_trx_project_list WHERE project_code = '$projectCode' AND order_number='$OrderNumber' AND STATUS = 'acknowledge'";
        $dataMandaysInformation = $DBSB->get_sql($sqlMandaysInformation);
        $idProject = $dataMandaysInformation[0]['project_id'];
        //$idProject = 16002;

        $sqlMandays = "SELECT SUBSTRING(resource_level, 1, 1) AS resource_level, mantotal, mandays, brand FROM sa_trx_project_mandays WHERE project_id = $projectID AND mantotal != '' AND mandays !='' AND service_type=$projectType AND (SUBSTRING(resource_level, 1, 1) BETWEEN 1 AND 4)";
        $dataSqlMandays = $DBSB->get_sqlV2($sqlMandays);
        $rowSqlMandays = $dataSqlMandays[0];
        $resSqlMandays = $dataSqlMandays[1];

        $sqlMandaysInformation1 = "SELECT SUBSTRING(resource_level, 1, 1) AS resource_level, mantotal, mandays, brand FROM sa_trx_project_mandays WHERE project_id = $projectID AND mantotal != '' AND mandays !='' AND service_type=$projectType AND (SUBSTRING(resource_level, 1, 1) BETWEEN 1 AND 4)";
        $dataSqlMandaysInformation1 = $DBSB->get_sql($sqlMandaysInformation1);
        $rowSqlMandaysInformation1 = $dataSqlMandaysInformation1[0];
        $resSqlMandaysInformation1 = $dataSqlMandaysInformation1[1];

        do {
            $resourceLevels = $rowSqlMandays['resource_level'];
            $mantotal = $rowSqlMandays['mantotal'];
            $mandays = $rowSqlMandays['mandays'];

            '';

            if ($resourceLevels == 1) {
                $arrMantotals1[] = $mantotal;
                $totalMandays1 = $mantotal * $mandays;
                $arrTotalMandays1[] = $totalMandays1;
            } else if ($resourceLevels == 2) {
                $arrMantotals2[] = $mantotal;
                $totalMandays2 = $mantotal * $mandays;
                $arrTotalMandays2[] = $totalMandays2;
            } else if ($resourceLevels == 3) {
                $arrMantotals3[] = $mantotal;
                $totalMandays3 = $mantotal * $mandays;
                $arrTotalMandays3[] = $totalMandays3;
            } else {
                $arrMantotals4[] = $mantotal;
                $totalMandays4 = $mantotal * $mandays;
                $arrTotalMandays4[] = $totalMandays4;
            }
        } while ($rowSqlMandays = $resSqlMandays->fetch_assoc());

        // Variabel mantotal
        $mantotals1 = array_sum($arrMantotals1);
        $mantotals2 = array_sum($arrMantotals2);
        $mantotals3 = array_sum($arrMantotals3);
        $mantotals4 = array_sum($arrMantotals4);

        // Variabel Mandays
        $mandaysJobRoles1 = array_sum($arrTotalMandays1);
        $mandaysJobRoles2 = array_sum($arrTotalMandays2);
        $mandaysJobRoles3 = array_sum($arrTotalMandays3);
        $mandaysJobRoles4 = array_sum($arrTotalMandays4);

        do {
            $resourceLevel = $rowSqlMandaysInformation1['resource_level'];
            $brand = $rowSqlMandaysInformation1['brand'];

            '';

            if ($resourceLevel != '') {
                if ($resourceLevel == 1) {
                    $mantotals = $mantotals1;
                    $totalMandays = $mandaysJobRoles1;
                } else if ($resourceLevel == 2) {
                    $mantotals = $mantotals2;
                    $totalMandays = $mandaysJobRoles2;
                } else if ($resourceLevel == 3) {
                    $mantotals = $mantotals3;
                    $totalMandays = $mandaysJobRoles3;
                } else {
                    $mantotals = $mantotals4;
                    $totalMandays = $mandaysJobRoles4;
                }
                // '';

                $queryLookupCatalogs = "SELECT resource_qualification FROM sa_mst_resource_catalogs WHERE resource_catalog_id = $resourceLevel";
                $dataLookupCatalogs = $DBSB->get_sql($queryLookupCatalogs);
                $resourceCatalogName = $dataLookupCatalogs[0]['resource_qualification'];

                $tbl_initial_jobroles = 'initial_jobroles';
                $queryCheckJobRoles = "SELECT * FROM sa_initial_jobroles WHERE project_id = '$projectId' AND resource_category_id = $resourceLevel";
                $dataCheckJobRoles = $DBWRKLD->get_sql($queryCheckJobRoles);
                $totalRowJobRoles = $dataCheckJobRoles[2];

                if ($totalRowJobRoles > 0) {
                    '';
                } else {
                    $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6OTIzOTA4MSxcImNcIjo0NjcxMzc3LFwidVwiOjE2NDc3MzM1LFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNzMxNDg0MDI4fQ.Ggy6YbLvLiIr_Yrfnyfy73yY8g1ts8bRRkrhpqvVV04";
                    $url = "https://www.wrike.com/api/v4/custom_item_types/IEAEOPF5PIAAFWRU/instantiate";
                    //?parent=IEAEOPF5I4U765EL&title=".$projectNameExplode[0]."&titlePrefix=".$projectNameExplode[1]."&copyCustomFields=true&rescheduleMode=Start&rescheduleDate=$date";
                    $data = array('title' => "$resourceCatalogName", 'parentId' => "$sbFolderId");
                    $postdata = json_encode($data);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                    $result3 = curl_exec($ch);
                    curl_close($ch);


                    '';
                    $result3 = json_decode($result3, true);
                    $taskJobId = $result3['data'][0]['id'];

                    //INSERT LOG MODIFT IMPLEMENTATION
                    $insertJobRoles = sprintf(
                        "(`task_id`, `parent_id`, `project_code`, `project_id`, `resource_category_id`, `total_mandays`) VALUES ('$taskJobId', 'SERVICE BUDGET', '$projectCode', '$projectIdWrike', '$resourceLevel',$totalMandays)",
                        GetSQLValueString($taskJobId, "text"),
                        GetSQLValueString($sbFolderId, "text"),
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($resourceCatalogName, "text")
                    );

                    //$resInitialJobRoles = $DBWRKLD->insert_data($tbl_initial_jobroles, $insertJobRoles);
                    $resInitialJobRoles = $DBWRKLD->get_res("INSERT INTO sa_initial_jobroles (task_id,parent_id,project_code,project_id,resource_category_id,total_mandays) VALUES ('$taskJobId', 'SERVICE BUDGET', '$projectCode', '$projectIdWrike', '$resourceLevel',$totalMandays)");

                    $url = "https://www.wrike.com/api/v4/tasks/$taskJobId";
                    $dataPut = array('description' => "Total Mans = $mantotals", 'customFields' => "[{'id':'IEAEOPF5JUAC3YH2','value':'$totalMandays'}]");
                    $put_data = json_encode($dataPut);
                    $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6OTIzOTA4MSxcImNcIjo0NjcxMzc3LFwidVwiOjE2NDc3MzM1LFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNzMxNDg0MDI4fQ.Ggy6YbLvLiIr_Yrfnyfy73yY8g1ts8bRRkrhpqvVV04";

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $put_data);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));

                    $response = curl_exec($ch);
                    curl_close($ch);


                    '';
                }
            }
        } while ($rowSqlMandaysInformation1 = $resSqlMandaysInformation1->fetch_assoc());


        $sqlMandaysInformation2 = "SELECT SUBSTRING(resource_level, 1, 1) AS resource_level, mantotal, mandays, brand FROM sa_trx_project_mandays WHERE project_id = $projectID AND mantotal != '' AND mandays !='' AND service_type=$projectType AND (SUBSTRING(resource_level, 1, 1) BETWEEN 5 AND 7)";
        $dataSqlMandaysInformation2 = $DBSB->get_sql($sqlMandaysInformation2);
        $rowSqlMandaysInformation2 = $dataSqlMandaysInformation2[0];
        $resSqlMandaysInformation2 = $dataSqlMandaysInformation2[1];

        do {
            $resourceLevelEngineer = $rowSqlMandaysInformation2['resource_level'];
            $brandEngineer = $rowSqlMandaysInformation2['brand'];
            $mantotalEngineer = $rowSqlMandaysInformation2['mantotal'];
            $mandaysEngineer = $rowSqlMandaysInformation2['mandays'];
            $totalMandaysEngineer = $mantotalEngineer * $mandaysEngineer;

            if ($resourceLevelEngineer != '') {
                $queryLookupCatalogsEngineer = "SELECT resource_qualification FROM sa_mst_resource_catalogs WHERE resource_catalog_id = $resourceLevelEngineer";
                $dataLookupCatalogsEngineer = $DBSB->get_sql($queryLookupCatalogsEngineer);
                $resourceCatalogNameEngineer = $dataLookupCatalogsEngineer[0]['resource_qualification'];

                $tbl_initial_jobroles = 'initial_jobroles';
                $queryCheckJobRolesEngineer = "SELECT * FROM sa_initial_jobroles WHERE project_id = '$projectId' AND resource_category_id = $resourceLevelEngineer AND brand = '$brandEngineer'";
                $dataCheckJobRolesEngineer = $DBWRKLD->get_sql($queryCheckJobRolesEngineer);
                $totalRowJobRolesEngineer = $dataCheckJobRolesEngineer[2];

                if ($totalRowJobRolesEngineer > 0) {
                } else {
                    $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6OTIzOTA4MSxcImNcIjo0NjcxMzc3LFwidVwiOjE2NDc3MzM1LFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNzMxNDg0MDI4fQ.Ggy6YbLvLiIr_Yrfnyfy73yY8g1ts8bRRkrhpqvVV04";
                    $url = "https://www.wrike.com/api/v4/custom_item_types/IEAEOPF5PIAAFWRU/instantiate";
                    //?parent=IEAEOPF5I4U765EL&title=".$projectNameExplode[0]."&titlePrefix=".$projectNameExplode[1]."&copyCustomFields=true&rescheduleMode=Start&rescheduleDate=$date";
                    $data = array('title' => "$resourceCatalogNameEngineer $brandEngineer", 'parentId' => "$sbFolderId");
                    $postdata = json_encode($data);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                    $result4 = curl_exec($ch);
                    curl_close($ch);


                    '';
                    $result4 = json_decode($result4, true);
                    $taskJobIdEngineer = $result4['data'][0]['id'];

                    //INSERT LOG MODIFT IMPLEMENTATION

                    $insertJobRolesEngineer = sprintf(
                        "(`task_id`, `parent_id`, `project_code`, `project_id`, `resource_category_id`, `brand`, `total_mandays`) VALUES ('$taskJobIdEngineer', 'SERVICE BUDGET', '$projectCode', '$projectIdWrike', '$resourceLevelEngineer', '$brandEngineer', $totalMandaysEngineer)",
                        GetSQLValueString($taskJobIdEngineer, "text"),
                        GetSQLValueString($sbFolderId, "text"),
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($resourceCatalogName, "text")
                    );

                    //$resInitialJobRolesEngineer = $DBWRKLD->insert_data($tbl_initial_jobroles, $insertJobRolesEngineer);
                    $resInitialJobRolesEngineer = $DBWRKLD->get_res("INSERT INTO sa_initial_jobroles (task_id, parent_id, project_code, project_id, resource_category_id, brand, total_mandays) VALUES ('$taskJobIdEngineer', 'SERVICE BUDGET', '$projectCode', '$projectIdWrike', '$resourceLevelEngineer', '$brandEngineer', $totalMandaysEngineer)");

                    $urlEngineer = "https://www.wrike.com/api/v4/tasks/$taskJobIdEngineer";
                    $dataPutEngineer = array('description' => "Mans = $mantotalEngineer", 'customFields' => "[{'id':'IEAEOPF5JUAC3YH2','value':'$totalMandaysEngineer'}]");
                    $put_dataEngineer = json_encode($dataPutEngineer);
                    $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6OTIzOTA4MSxcImNcIjo0NjcxMzc3LFwidVwiOjE2NDc3MzM1LFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNzMxNDg0MDI4fQ.Ggy6YbLvLiIr_Yrfnyfy73yY8g1ts8bRRkrhpqvVV04";

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $urlEngineer);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $put_dataEngineer);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));

                    $response = curl_exec($ch);
                    curl_close($ch);


                    '';
                }
            }
        } while ($rowSqlMandaysInformation2 = $resSqlMandaysInformation2->fetch_assoc());
    } while ($rowSQLIP = $resSQLIP->fetch_assoc());

    '';

    $tbl_wrike_config = 'wrike_config';
    $tbl_initial_project = 'initial_project';
    // $dataInitialProject = $DBWR->get_data($tbl_initial_project);
    $dataInitialProject = $DBWR->get_sql("SELECT * FROM sa_initial_project WHERE status=2 AND project_code='$ProjectCode'");
    $rowInitialProject = $dataInitialProject[0];
    $resInitialProject = $dataInitialProject[1];
    $totalRowInitialProject = $dataInitialProject[2];

    do {
        $projectType = '';
        $projectId = $rowInitialProject['project_id'];
        $projectCode = $rowInitialProject['project_code'];
        $projectType = $rowInitialProject['project_type'];
        // $projectSbId = $rowInitialProject['project_sb_id'];
        $orderNumber = $rowInitialProject['order_number'];
        $status = $rowInitialProject['status'];

        // if ($status == 2) {
        $queryOwner = "SELECT * FROM sa_wrike_config WHERE object = 'Owner' AND condition1 = '$projectType'";
        $dataQuery = $DBWR->get_sql($queryOwner);
        $rowOwner = $dataQuery[0];
        $resOwner = $dataQuery[1];
        $fullOwnerId = '';

        do {
            $ownerId = $rowOwner['object_id'];
            $fullOwnerId = '"' . $ownerId . '", ' . $fullOwnerId;
            $fullOwnerId = rtrim($fullOwnerId, ', ');
        } while ($rowOwner = $resOwner->fetch_assoc());

        if (isset($pm_wrike)) {
            $checkIdWrike = $DBWR->get_sql("SELECT * FROM sa_contact_user WHERE email ='$pm_wrike'");
            $OwnerId = $checkIdWrike[0]['id'];
        } else {
            $OwnerId = $fullOwnerId;
        }

        if ($projectType == 'Implementation') {
            $projectType = 1;
        } else if ($projectType == 'Maintenance') {
            $projectType = 2;
        }

        $db_sb = "SERVICE_BUDGET";
        $DBSB = get_conn($db_sb);
        $queryEnterpriseProjectType = "SELECT a.project_id, a.project_code, a.project_name, a.project_name_internal, a.customer_name, a.amount_idr, a.amount_usd, 
            a.so_number, a.order_number, a.bundling, b.tos_id, b.tos_category_id, b.service_type, c.tos_name 
            FROM sa_trx_project_list AS a 
            LEFT JOIN sa_trx_project_implementations AS b ON a.project_id=b.project_id 
            LEFT JOIN sa_mst_type_of_service AS c ON b.tos_id=c.tos_id 
            WHERE a.project_id=$projectID";

        $dataProjectCategory = $DBSB->get_sql($queryEnterpriseProjectType);
        $rowProjectCategory = $dataProjectCategory[0];
        $resProjectCategory = $dataProjectCategory[1];
        $totalRowProjectCategory = $dataProjectCategory[2];

        do {
            $projectCategory = '';
            $fullTypeOfService = '';
            $amountIDR = '';
            $amountUSD = '';
            $currency = '';
            $soNumber = '';
            $projectName = $rowProjectCategory['project_name'];
            $projectNameExplode = explode('#', $projectName);
            $soNumber = $rowProjectCategory['so_number'];
            $orderNumberSB = $rowProjectCategory['order_number'];
            $customerName = $rowProjectCategory['customer_name'];
            $projectCategory = $rowProjectCategory['tos_category_id'];
            $serviceType1 = $rowProjectCategory['service_type'];
            $serviceType = $rowProjectCategory['tos_id'];
            $serviceTypeImplement = $rowProjectCategory['tos_id'];
            $serviceTypeImplement = rtrim($serviceTypeImplement, ';');
            $serviceTypeImplement = explode(';', $serviceTypeImplement);
            $tosNameraw = isset($rowProjectCategory['tos_name']);
            if (isset($tosNameraw)) {
                $tosName = $rowProjectCategory['tos_name'];
            } else {
                $tosName = '';
            }
            $internalProjectName = $rowProjectCategory['project_name_internal'];
            $amountIDR = $rowProjectCategory['amount_idr'];
            $amountIDR = round($amountIDR, 0);
            $amountUSD = $rowProjectCategory['amount_usd'];
            $amountUSD = round($amountUSD, 0);

            if ($amountIDR != '') {
                $currency = 'IDR';
                $priceService = $amountIDR;
                $conditionCurrencyOption = "object = 'CustomField' AND title = 'Currency' and condition2 = '" . $currency . "'";
                $customfieldCurrency = $DBWR->get_data($tbl_wrike_config, $conditionCurrencyOption);
                $currencyOptionId = $customfieldCurrency[0]['object_id'];
            } else if ($amountUSD != '') {
                $currency = 'USD';
                $priceService = $amountUSD;
                $conditionCurrencyOption = "object = 'CustomField' AND title = 'Currency' and condition2 = '" . $currency . "'";
                $customfieldCurrency = $DBWR->get_data($tbl_wrike_config, $conditionCurrencyOption);
                $currencyOptionId = $customfieldCurrency[0]['object_id'];
            }

            if ($projectType == 1 && $serviceType1 == 1) {
                if ($projectCategory == 1) {
                    $projectCategoryDescription = 'High';
                } else if ($projectCategory == 2) {
                    $projectCategoryDescription = 'Medium';
                } else if ($projectCategory == 3) {
                    $projectCategoryDescription = 'Standard';
                }

                $get_sub = $DBSB->get_sql("SELECT * FROM sa_trx_project_solution_sub WHERE project_id=" . $rowProjectCategory['project_id'] . "");
                if (empty($get_sub[0]['project_solution_code'])) {
                    $project_solutionCode = '';
                } else {
                    $get_subName = $DBWRKLD->get_sql("SELECT * FROM sa_workload_config WHERE project_solution_code='" . $get_sub[0]['project_solution_code'] . "'");
                    $project_solutionCode = "[$projectCategoryDescription] " . $get_subName[0]['project_solution_name'];
                }

                $get_cost = $DBSB->get_sql("SELECT * FROM sa_trx_project_implementations WHERE project_id=" . $rowProjectCategory['project_id'] . "");
                if (empty($get_cost[0]['consumption_plan'])) {
                    $consumption_plan = '';
                } else {
                    $consumption_plan = number_format($get_cost[0]['consumption_plan']);
                }

                for ($j = 0; $j < count($serviceTypeImplement); $j++) {
                    $conditionServiceName = "tos_id = '" . $serviceTypeImplement[$j] . "'";
                    $tblMstTypeOfService = 'mst_type_of_service';
                    $getTypeOfService = $DBSB->get_data($tblMstTypeOfService, $conditionServiceName);
                    $valueTypeOfService = $getTypeOfService[0]['tos_name'];
                    $fullTypeOfService = '"' . $valueTypeOfService . '", ' . $fullTypeOfService;
                    $fullTypeOfService = rtrim($fullTypeOfService, ', ');
                }

                if ($serviceType1 == 1) {
                    $enterpriseProjectType = "MSI Project Implementation";
                }

                //Enterprise Project Type ID
                $conditionEnterpriseProjectType = "object = 'CustomField' AND title = 'Enterprise Project Type' AND condition1 = '$enterpriseProjectType'";
                $customfieldEPT = $DBWR->get_data($tbl_wrike_config, $conditionEnterpriseProjectType);
                $eptId = $customfieldEPT[0]['object_id'];

                //Project Category ID
                $conditionProjectCategory = "object = 'CustomField' AND title = 'Project Category' AND condition2 = '$projectCategoryDescription'";
                $customfieldPC = $DBWR->get_data($tbl_wrike_config, $conditionProjectCategory);
                $pcId = $customfieldPC[0]['object_id'];

                //Project Code ID
                $conditionProjectCode = "object = 'CustomField' AND title = 'Project Code'";
                $customfieldPCode = $DBWR->get_data($tbl_wrike_config, $conditionProjectCode);
                $projectCodeId = $customfieldPCode[0]['object_id'];

                //Sales Order Number Id
                $conditionSalesOrder = "object = 'CustomField' AND title = 'Sales Order'";
                $customfieldSOrder = $DBWR->get_data($tbl_wrike_config, $conditionSalesOrder);
                $noSOId = $customfieldSOrder[0]['object_id'];

                //Project Value ID
                $conditionProjectValue = "object = 'CustomField' AND title = 'Project Value'";
                $customfieldPValue = $DBWR->get_data($tbl_wrike_config, $conditionProjectValue);
                $projectValueId = $customfieldPValue[0]['object_id'];

                //Project Service ID
                $conditionProjectService = "object = 'CustomField' AND title = 'Project Service'";
                $customfieldPService = $DBWR->get_data($tbl_wrike_config, $conditionProjectService);
                $projectServiceId = $customfieldPService[0]['object_id'];

                $conditionCostConsumption = "object = 'CustomField' AND title = 'Cost Consumption'";
                $customfieldCost = $DBWR->get_data($tbl_wrike_config, $conditionCostConsumption);
                $CostConsumptionID = $customfieldCost[0]['object_id'];

                $conditionProjectSolution = "object = 'CustomField' AND title = 'Project Solution'";
                $customfieldPSolution = $DBWR->get_data($tbl_wrike_config, $conditionProjectSolution);
                $ProjectSolutionID = $customfieldPSolution[0]['object_id'];

                //Mandays
                $mandaysQuery = "SELECT sa_trx_project_list.project_id, sa_trx_project_list.project_id, sa_trx_project_list.order_number, sa_trx_project_list.project_code, sa_trx_project_mandays.mantotal, 
                    sa_trx_project_mandays.mandays,
                    sa_trx_project_mandays.service_type,
                    sa_trx_project_mandays.brand FROM sa_trx_project_list JOIN sa_trx_project_mandays ON sa_trx_project_list.project_id=sa_trx_project_mandays.project_id WHERE sa_trx_project_list.project_code='$projectCode'
                    AND sa_trx_project_mandays.service_type=$projectType AND sa_trx_project_list.order_number='$orderNumberSB' AND sa_trx_project_list.project_id=$projectID";

                $dataMandays = $DBSB->get_sql($mandaysQuery);
                $rowDataMandays = $dataMandays[0];
                $resDataMandays = $dataMandays[1];
                $totalRowDataMandays = $dataMandays[2];
                unset($arrTotalMandays);

                do {
                    $mantotal = $rowDataMandays['mantotal'];
                    $mandays = $rowDataMandays['mandays'];

                    if ($mantotal != NULL && $mandays != NULL) {
                        $totalMandays = $mantotal * $mandays;
                        $arrTotalMandays[] = $totalMandays;
                    } else {
                        '';
                    }
                } while ($rowDataMandays = $resDataMandays->fetch_assoc());

                // Variabel Mandays
                $mandaysWrike = array_sum($arrTotalMandays);

                //Mandays ID
                $conditionTotalMandays = "object = 'CustomField' AND title = 'Total Mandays'";
                $customfieldTMandays = $DBWR->get_data($tbl_wrike_config, $conditionTotalMandays);
                $totalMandaysId = $customfieldTMandays[0]['object_id'];

                //Customer ID
                $conditionCustomerName = "object = 'CustomField' AND title = 'Customer'";
                $customfieldCName = $DBWR->get_data($tbl_wrike_config, $conditionCustomerName);
                $customerNameId = $customfieldCName[0]['object_id'];

                //Internal Project Name ID
                $conditionInternalProjectName = "object = 'CustomField' AND title = 'Internal Project Name'";
                $customfieldInternalProjectName = $DBWR->get_data($tbl_wrike_config, $conditionInternalProjectName);
                $internalProjectNameId = $customfieldInternalProjectName[0]['object_id'];

                //Order Number ID
                $conditionOrderNumber = "object = 'CustomField' AND title = 'Order Number'";
                $customfieldOrderNumber = $DBWR->get_data($tbl_wrike_config, $conditionOrderNumber);
                $orderNumberId = $customfieldOrderNumber[0]['object_id'];

                '';

                $url = "https://www.wrike.com/api/v4/folders/$projectId"; //?project={'ownersAdd':[$fullOwnerId]}&customFields=[{'id':'$pcId', 'value':'$projectCategoryDescription'}]";
                $data = array("project" => "{'ownersAdd':[$OwnerId]}", "customFields" => "[{'id':'$ProjectSolutionID', 'value':'$project_solutionCode'}, {'id':'$CostConsumptionID', 'value':'$consumption_plan'}, {'id':'$eptId', 'value':'$enterpriseProjectType'}, {'id':'$orderNumberId', 'value':'$orderNumberSB'}, {'id':'$currencyOptionId', 'value':'$currency'}, {'id':'$pcId', 'value':'$projectCategoryDescription'},  {'id':'$projectCodeId','value':'$projectCode'}, {'id':'$noSOId','value':'$soNumber'}, {'id':'$projectValueId','value':'$priceService'}, {'id':'$projectServiceId','value':'[$fullTypeOfService]'}, {'id':'$totalMandaysId','value':'$mandaysWrike'}, {'id':'$customerNameId','value':'$customerName'}, {'id':'$internalProjectNameId','value':'$internalProjectName'}]");

                //Internal Project Name
                //, {'id':'$internalProjectNameId','value':'" . $projectNameExplode[1] . "'}
                $put_data = json_encode($data);
                $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6OTIzOTA4MSxcImNcIjo0NjcxMzc3LFwidVwiOjE2NDc3MzM1LFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNzMxNDg0MDI4fQ.Ggy6YbLvLiIr_Yrfnyfy73yY8g1ts8bRRkrhpqvVV04";

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $put_data);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));

                $response = curl_exec($ch);
                curl_close($ch);


                $conditionStatus3 = "project_id = '$projectId'";
                $updateStatus3 = sprintf("`status`= 3");
                $resStatus3 = $DBWR->update_data($tbl_initial_project, $updateStatus3, $conditionStatus3);
                // $infoOutput .= 'tabel : ' . $tbl_initial_project . ' update : ' . $updateStatus3 . ' condition : ' . $conditionStatus3 . '</br>';

                '';

                '';

                //INSERT LOG MODIFT IMPLEMENTATION
                $tbl_sa_log_activity = 'log_activity';
                $insertLogCustomFieldsImplementation = sprintf(
                    "(`activity`) VALUES ('Edited customfields Wrike with id project $projectId and code $projectCode - Implementation')",
                    GetSQLValueString($projectCode, "text"),
                    GetSQLValueString($projectId, "text")
                );

                $resLogCustomFieldsImplementation = $DBWR->insert_data($tbl_sa_log_activity, $insertLogCustomFieldsImplementation);
                // $infoOutput .= 'tabel : ' . $tbl_sa_log_activity . ' insert : ' . $insertLogCustomFieldsImplementation . '</br>';
            } else if ($projectType == 2 && $serviceType1 == 2) {
                if ($serviceType == 5) {
                    $serviceTypeDescription = 'Gold';
                } else if ($serviceType == 8) {
                    $serviceTypeDescription = 'Gold';
                } else if ($serviceType == 6) {
                    $serviceTypeDescription = 'Silver';
                } else if ($serviceType == 7) {
                    $serviceTypeDescription = 'Bronze';
                }

                if ($serviceType1 == 2) {
                    $enterpriseProjectType = "MSI Project Maintenance";
                }

                //Enterprise Project Type ID
                $conditionEnterpriseProjectType = "object = 'CustomField' AND title = 'Enterprise Project Type' AND condition1 = '$enterpriseProjectType'";
                $customfieldEPT = $DBWR->get_data($tbl_wrike_config, $conditionEnterpriseProjectType);
                $eptId = $customfieldEPT[0]['object_id'];

                //Service Type ID
                $conditionServiceTypeMaintenance = "object = 'CustomField' AND title = 'Service Type' AND condition2 = '$serviceTypeDescription'";
                $customfieldServiceTypeMaintenance = $DBWR->get_data($tbl_wrike_config, $conditionServiceTypeMaintenance);
                $serviceTypeId = $customfieldServiceTypeMaintenance[0]['object_id'];

                //Project Code ID
                $conditionProjectCode = "object = 'CustomField' AND title = 'Project Code'";
                $customfieldPCode = $DBWR->get_data($tbl_wrike_config, $conditionProjectCode);
                $projectCodeId = $customfieldPCode[0]['object_id'];

                //Sales Order Number Id
                $conditionSalesOrder = "object = 'CustomField' AND title = 'Sales Order'";
                $customfieldSOrder = $DBWR->get_data($tbl_wrike_config, $conditionSalesOrder);
                $noSOId = $customfieldSOrder[0]['object_id'];

                //Project Value ID
                $conditionProjectValue = "object = 'CustomField' AND title = 'Project Value'";
                $customfieldPValue = $DBWR->get_data($tbl_wrike_config, $conditionProjectValue);
                $projectValueId = $customfieldPValue[0]['object_id'];

                //Mandays
                $mandaysQuery = "SELECT sa_trx_project_list.project_id, sa_trx_project_list.project_id, sa_trx_project_list.order_number, sa_trx_project_list.project_code, sa_trx_project_mandays.mantotal, 
                    sa_trx_project_mandays.mandays,
                    sa_trx_project_mandays.service_type,
                    sa_trx_project_mandays.brand FROM sa_trx_project_list JOIN sa_trx_project_mandays ON sa_trx_project_list.project_id=sa_trx_project_mandays.project_id WHERE sa_trx_project_list.project_code='$projectCode'
                    AND sa_trx_project_mandays.service_type=$projectType AND sa_trx_project_list.order_number='$orderNumberSB'";

                $dataMandays = $DBSB->get_sql($mandaysQuery);
                $rowDataMandays = $dataMandays[0];
                $resDataMandays = $dataMandays[1];
                $totalRowDataMandays = $dataMandays[2];
                unset($arrTotalMandays);

                do {
                    $mantotal = $rowDataMandays['mantotal'];
                    $mandays = $rowDataMandays['mandays'];

                    if ($mantotal != NULL && $mandays != NULL) {
                        $totalMandays = $mantotal * $mandays;
                        $arrTotalMandays[] = $totalMandays;
                    } else {
                        '';
                    }
                } while ($rowDataMandays = $resDataMandays->fetch_assoc());

                // Variabel Mandays
                if ($arrTotalMandays != '') {
                    $mandaysWrike = array_sum($arrTotalMandays);
                } else {
                    $mandaysWrike = '';
                }

                //Mandays ID
                $conditionTotalMandays = "object = 'CustomField' AND title = 'Total Mandays'";
                $customfieldTMandays = $DBWR->get_data($tbl_wrike_config, $conditionTotalMandays);
                $totalMandaysId = $customfieldTMandays[0]['object_id'];

                //Customer ID
                $conditionCustomerName = "object = 'CustomField' AND title = 'Customer'";
                $customfieldCName = $DBWR->get_data($tbl_wrike_config, $conditionCustomerName);
                $customerNameId = $customfieldCName[0]['object_id'];

                //Internal Project Name ID
                $conditionInternalProjectName = "object = 'CustomField' AND title = 'Internal Project Name'";
                $customfieldInternalProjectName = $DBWR->get_data($tbl_wrike_config, $conditionInternalProjectName);
                $internalProjectNameId = $customfieldInternalProjectName[0]['object_id'];

                //Order Number ID
                $conditionOrderNumber = "object = 'CustomField' AND title = 'Order Number'";
                $customfieldOrderNumber = $DBWR->get_data($tbl_wrike_config, $conditionOrderNumber);
                $orderNumberId = $customfieldOrderNumber[0]['object_id'];

                $url = "https://www.wrike.com/api/v4/folders/$projectId"; //?project={'ownersAdd':[$fullOwnerId]}&customFields=[{'id':'$pcId', 'value':'$projectCategoryDescription'}]";
                $data = array("project" => "{'ownersAdd':[$OwnerId]}", "customFields" => "[{'id':'$eptId', 'value':'$enterpriseProjectType'}, {'id':'$orderNumberId', 'value':'$orderNumberSB'}, {'id':'$currencyOptionId', 'value':'$currency'}, {'id':'$serviceTypeId', 'value':'$serviceTypeDescription'}, {'id':'$projectCodeId','value':'$projectCode'}, {'id':'$noSOId','value':'$soNumber'}, {'id':'$projectValueId','value':'$priceService'}, {'id':'$totalMandaysId','value':'0'}, {'id':'$customerNameId','value':'$customerName'}, {'id':'$internalProjectNameId','value':'$internalProjectName'}]");
                $put_data = json_encode($data);
                $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6OTIzOTA4MSxcImNcIjo0NjcxMzc3LFwidVwiOjE2NDc3MzM1LFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNzMxNDg0MDI4fQ.Ggy6YbLvLiIr_Yrfnyfy73yY8g1ts8bRRkrhpqvVV04";

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $put_data);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));

                $response = curl_exec($ch);
                curl_close($ch);


                $conditionStatus3 = "project_id = '$projectId'";
                $updateStatus3 = sprintf("`status`= 3");
                $resStatus3 = $DBWR->update_data($tbl_initial_project, $updateStatus3, $conditionStatus3);
                // $infoOutput .= 'tabel : ' . $tbl_initial_project . ' update : ' . $updateStatus3 . ' condition : ' . $conditionStatus3 . '</br>';

                '';

                '';

                //INSERT LOG MODIFT MAINTENANCE
                $tbl_sa_log_activity = 'log_activity';
                $insertLogCustomFieldsMaintenance = sprintf(
                    "(`activity`) VALUES ('Edited customfields Wrike with id project $projectId and code $projectCode - Maintenance $serviceTypeDescription')",
                    GetSQLValueString($projectCode, "text"),
                    GetSQLValueString($projectId, "text"),
                    GetSQLValueString($serviceTypeDescription, "text")
                );

                $resLogCustomFieldsMaintenance = $DBWR->insert_data($tbl_sa_log_activity, $insertLogCustomFieldsMaintenance);
                // $infoOutput .= 'tabel : ' . $tbl_sa_log_activity . ' insert : ' . $insertLogCustomFieldsMaintenance . '</br>';
            }
        } while ($rowProjectCategory = $resProjectCategory->fetch_assoc());
        // } 
    } while ($rowInitialProject = $resInitialProject->fetch_assoc());

    '';

    // $tbl_initial_project = "initial_project";
    // $tbl_wrike_config = "wrike_config";
    // // $dataApproval = $DBWR->get_data($tbl_initial_project);
    // $dataApproval = $DBWR->get_sql("SELECT * FROM sa_initial_project WHERE status=3 AND project_code='$ProjectCode'");
    // $rowDataApproval = $dataApproval[0];
    // $resDataApproval = $dataApproval[1];
    // $totalRowDataApproval = $dataApproval[2];

    // do {
    //     $projectId = $rowDataApproval['project_id'];
    //     $projectCode = $rowDataApproval['project_code'];
    //     $projectType = $rowDataApproval['project_type'];
    //     $status = $rowDataApproval['status'];
    //     //'';

    //     $conditionOwner = "object = 'Owner' AND condition1 = '$projectType'";
    //     $dataOwner = $DBWR->get_data($tbl_wrike_config, $conditionOwner);
    //     $rowOwner = $dataOwner[0];
    //     $resOwner = $dataOwner[1];

    //     $fullOwnerId = '';

    //     do {
    //         $ownerId = $rowOwner['object_id'];
    //         $fullOwnerId = '"' . $ownerId . '", ' . $fullOwnerId;
    //         $fullOwnerId = rtrim($fullOwnerId, ', ');
    //     } while ($rowOwner = $resOwner->fetch_assoc());

    //     $conditionApprovalDay = "object = 'approval' and title ='Approval Maximum Day'";
    //     $dataApprovalDay = $DBWR->get_data($tbl_wrike_config);
    //     $day = $dataApprovalDay[0]['condition1'];

    //     $dueDate = date('Y-m-d', strtotime("+$day  day"));

    //     // if ($status == 3) {
    //         $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6OTIzOTA4MSxcImNcIjo0NjcxMzc3LFwidVwiOjE2NDc3MzM1LFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNzMxNDg0MDI4fQ.Ggy6YbLvLiIr_Yrfnyfy73yY8g1ts8bRRkrhpqvVV04";
    //         $url = "https://www.wrike.com/api/v4/folders/$projectId/approvals"; //?approvers=$ownerId&description=$descApproval&dueDate=$dueApproval";
    //         //?parent=IEAEOPF5I4U765EL&title=".$projectNameExplode[0]."&titlePrefix=".$projectNameExplode[1]."&copyCustomFields=true&rescheduleMode=Start&rescheduleDate=$date";
    //         $data = array('approvers' => "[$fullOwnerId]", 'description' => "New Project Request $projectType", 'dueDate' => "$dueDate");
    //         // $data = array('approvers' => "['KUAL4N7R']", 'description' => "New Project Request $projectType", 'dueDate' => "$dueDate");
    //         $postdata = json_encode($data);
    //         $ch = curl_init();
    //         curl_setopt($ch, CURLOPT_URL, $url);
    //         curl_setopt($ch, CURLOPT_POST, 1);
    //         curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //         curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
    //         $result = curl_exec($ch);
    //         curl_close($ch);


    //         $conditionStatus4 = "project_id = '$projectId'";
    //         $updateStatus4 = sprintf("`status`= 4");
    //         $resStatus4 = $DBWR->update_data($tbl_initial_project, $updateStatus4, $conditionStatus4);
    //         $infoOutput .= 'tabel : ' . $tbl_initial_project . ' update : ' . $updateStatus4 . ' condition : ' . $conditionStatus4 . '</br>';

    //         '';

    //         '';

    //         //INSERT LOG CREATE APPROVAL
    //         $tbl_sa_log_activity = 'log_activity';
    //         $insertLogCreateApproval = sprintf(
    //             "(`activity`) VALUES ('Created approval from $projectId - $projectCode - $projectType to $fullOwnerId')",
    //             GetSQLValueString($projectCode, "text"),
    //             GetSQLValueString($projectId, "text"),
    //             GetSQLValueString($projectType, "text"),
    //             GetSQLValueString($fullOwnerId, "text")
    //         );

    //         $resLogCreateApproval = $DBWR->insert_data($tbl_sa_log_activity, $insertLogCreateApproval);
    //         $infoOutput .= 'tabel : ' . $tbl_sa_log_activity . ' insert : ' . $insertLogCreateApproval . '</br>';
    //     // }
    // } while ($rowDataApproval = $resDataApproval->fetch_assoc());

    // $tbl_initial_project = 'initial_project';
    // // $dataGetApproval = $DBWR->get_data($tbl_initial_project,);
    // $dataGetApproval = $DBWR->get_sql("SELECT * FROM sa_initial_project WHERE approval_status !='Approved' AND status=4 AND project_code='$ProjectCode'");
    // $rowGetApproval = $dataGetApproval[0];
    // $resGetApproval = $dataGetApproval[1];
    // $totalRowGetApproval = $dataGetApproval[2];

    // do {
    //     $projectId = $rowGetApproval['project_id'];
    //     $projectCode = $rowGetApproval['project_code'];
    //     $approvalStatus = $rowGetApproval['approval_status'];
    //     $projectType = $rowGetApproval['project_type'];
    //     $status = $rowGetApproval['status'];
    //     // '';

    //     // if ($approvalStatus != 'Approved' && $status == 4) {
    //         $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6OTIzOTA4MSxcImNcIjo0NjcxMzc3LFwidVwiOjE2NDc3MzM1LFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNzMxNDg0MDI4fQ.Ggy6YbLvLiIr_Yrfnyfy73yY8g1ts8bRRkrhpqvVV04";
    //         $curl = curl_init();

    //         curl_setopt($curl, CURLOPT_URL, "https://www.wrike.com/api/v4/folders/$projectId/approvals");
    //         curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
    //         curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //         $result = curl_exec($curl);
    //         curl_close($curl);

    //         $result2 = json_decode($result, true);

    //         $resultApproval = $result2['data'];

    //         for ($i = 0; $i < count($resultApproval); $i++) {
    //             $statusApproval = $resultApproval[$i]['status'];
    //             // '';

    //             if ($statusApproval == 'Approved') {
    //                 $conditionStatus5 = "project_id = '$projectId'";
    //                 $updateStatus5 = sprintf("`approval_status` = 'Approved', `status`= 5");
    //                 $resStatus5 = $DBWR->update_data($tbl_initial_project, $updateStatus5, $conditionStatus5);

    //                 //INSERT GET APPROVAL
    //                 $tbl_sa_log_activity = 'log_activity';
    //                 $insertLogGetApproval = sprintf(
    //                     "(`activity`) VALUES ('$projectId - $projectCode Approved')",
    //                     GetSQLValueString($projectCode, "text"),
    //                     GetSQLValueString($projectId, "text")
    //                 );

    //                 $resLogGetApproval = $DBWR->insert_data($tbl_sa_log_activity, $insertLogGetApproval);
    //             } else if ($statusApproval == 'Rejected') {
    //                 $conditionStatus5 = "project_id = '$projectId'";
    //                 $updateStatus5 = sprintf("`approval_status` = '$statusApproval', `status` = 5");
    //                 $resStatus5 = $DBWR->update_data($tbl_initial_project, $updateStatus5, $conditionStatus5);

    //                 //INSERT GET APPROVAL
    //                 $tbl_sa_log_activity = 'log_activity';
    //                 $insertLogGetApproval = sprintf(
    //                     "(`activity`) VALUES ('$projectId - $projectCode Rejected')",
    //                     GetSQLValueString($projectCode, "text"),
    //                     GetSQLValueString($projectId, "text")
    //                 );

    //                 $resLogGetApproval = $DBWR->insert_data($tbl_sa_log_activity, $insertLogGetApproval);
    //             } else {
    //                 $conditionStatus4 = "project_id = '$projectId'";
    //                 $updateStatus4 = sprintf("`approval_status` = '$statusApproval'");
    //                 $resStatus4 = $DBWR->update_data($tbl_initial_project, $updateStatus4, $conditionStatus4);
    //                 '';

    //                 //INSERT GET APPROVAL
    //                 $tbl_sa_log_activity = 'log_activity';
    //                 $insertLogGetApproval = sprintf(
    //                     "(`activity`) VALUES ('$projectId - $projectCode Pending')",
    //                     GetSQLValueString($projectCode, "text"),
    //                     GetSQLValueString($projectId, "text")
    //                 );

    //                 $resLogGetApproval = $DBWR->insert_data($tbl_sa_log_activity, $insertLogGetApproval);
    //             }
    //         }
    //     // } 
    // } while ($rowGetApproval = $resGetApproval->fetch_assoc());
    $from = $_SESSION['Microservices_UserEmail'];
    $to = $owner_wrike;
    $cc = $from;
    $bcc = "";
    $reply = $from;
    $subject = "[Wrike] Notifikasi Assignment Untuk Project KP $ProjectCode";
    $msg = "<table width='100%'";
    $msg .= "<tr><td width='30%' rowspan='5'></td><td style='width:40%; border: thin solid #dadada; padding:20px'>";
    $msg .= "<img src='https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png'>";
    $msg .= "</td></tr>";
    $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'>";
    $msg .= "<br/>";
    $msg .= "<p>Dear $owner_wrike ,</p>";
    $msg .= "<p>Dengan ini memberikan informasi bahwa Project KP $ProjectCode sudah ada di Wrike dan dapat diganti statusnya menjadi Open dan dibuatkan Project Charternya.</p>";
    $msg .= "<p>";
    $msg .= "</p>";
    $msg .= "<p>Demikian informasi ini disampaikan untuk selengkapnya dapat dilihat pada Wrike.</p>";
    $msg .= "<p>Terimakasih,<br/>";
    $msg .= "MSIZone Admin</p>";
    $msg .= "</td><td width='30%' rowspan='5'>";
    $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
    $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
    $msg .= "</table>";

    $headers = "From: " . $from . "\r\n" .
        "Reply-To: " . $reply . "\r\n" .
        "Cc: " . $cc . "\r\n" .
        "Bcc: " . $bcc . "\r\n" .
        "MIME-Version: 1.0" . "\r\n" .
        "Content-Type: text/html; charset=UTF-8" . "\r\n" .
        "X-Mailer: PHP/" . phpversion();

    $ALERT = new Alert();
    if (!mail($to, $subject, $msg, $headers)) {
        echo $ALERT->email_not_send();
    } else {
        echo $ALERT->email_send();
    }
}

function syncWrikeMT($projectID, $ProjectCode, $pm_wrike, $status_wrike, $sbf_role, $namaFile = "WRIKE_BUDGET")
{
    error_reporting(0);
    ini_set('display_errors', 0);
    $db_workload = 'WORKLOAD';
    $DBWRKLD = get_conn($db_workload);

    $gdgd = "GOOGLE_DRIVE";
    $DBGD = get_conn($gdgd);

    $wrwr = "WRIKE_INTEGRATE";
    $DBWR = get_conn($wrwr);

    $db_sb = "SERVICE_BUDGET";
    $DBSB = get_conn($db_sb);

    $owner_wrike = $pm_wrike;

    $API_WRIKE = new API_WRIKE;

    $querySB = "SELECT a.project_id, a.project_code, a.project_name, a.project_name_internal, a.order_number, a.amount_idr, a.amount_usd, a.so_number, b.implementation_price, a.bundling, b.tos_id, b.tos_category_id, b.service_type, a.status, a.create_date 
    FROM sa_trx_project_list AS a 
    LEFT JOIN sa_trx_project_implementations AS b ON a.project_id=b.project_id
    WHERE a.project_id=$projectID";

    $tbl_wrike_config = 'wrike_config';
    $tbl_trx_project_list = 'trx_project_list';
    $conditionSB = "status = 'acknowledge'";
    $dataSB = $DBSB->get_sql($querySB);
    $rowSB = $dataSB[0];
    $resSB = $dataSB[1];
    $totalRowSB = $dataSB[2];

    $tbl_trx_project_implementations = 'trx_project_implementations';
    $dataSBExpand = $DBSB->get_data($tbl_trx_project_list);
    $rowSBExpand = $dataSBExpand[0];
    $resSBExpand = $dataSBExpand[1];
    $totalRowSBExpand = $dataSBExpand[2];

    $tbl_initial_project = "initial_project";
    $tbl_wrike_project_list = "wrike_project_list";

    do {
        $projectId = $rowSB['project_id'];
        $projectCode = $rowSB['project_code'];
        $soNumber = $rowSB['so_number'];
        $orderNumber = $rowSB['order_number'];
        $projectName = $rowSB['project_name'];
        $internalProjectName = $rowSB['project_name_internal'];
        $projectNameExplode = explode('#', $projectName);
        $tosId = $rowSB['tos_id'];
        $projectCategory = $rowSB['tos_category_id'];
        $serviceType = $rowSB['service_type'];
        $tosName = '';

        if ($serviceType == 2 && $tosId == 5 || $serviceType == 2 && $tosId == 8) {
            $serviceType = "Maintenance";
            $tosName = "Gold";

            $conditionMaintenanceGold = "object = 'Blueprint' AND condition1 = '$serviceType' AND condition2='$tosName'";
            $configMaintenanceGold = $DBWR->get_data($tbl_wrike_config, $conditionMaintenanceGold);
            $blueprintId = $configMaintenanceGold[0]['object_id'];

            //date_default_timezone_set('Indonesia/Jakarta');
            $date = date('Y-m-d', time());

            //Check Table Initial Project
            $conditionInitialGold = "project_code='$projectCode' AND project_type='$serviceType' AND service_type='$tosName' AND order_number = '$orderNumber'";
            $dataInitialProject = $DBWR->get_data($tbl_initial_project, $conditionInitialGold);
            $totalRowInitialProject = $dataInitialProject[2];

            //Check Table Wrike Project List Maintenance
            $conditionWrikeProjectList = "project_code = '$projectCode' AND order_number = '$orderNumber' AND project_type = 'MSI Project Maintenance'";
            $dataWrikeProjectList = $DBWR->get_data($tbl_wrike_project_list, $conditionWrikeProjectList);
            $projectCodeWPL = $dataWrikeProjectList[0]['project_code'];
            $projectTypeWPL = $dataWrikeProjectList[0]['project_type'];
            $totalRowWPL = $dataWrikeProjectList[2];

            if ($totalRowWPL > 0) {
                '';
            } else {
                if ($totalRowInitialProject > 0) {
                    '';
                } else {
                    $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6OTIzOTA4MSxcImNcIjo0NjcxMzc3LFwidVwiOjE2NDc3MzM1LFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNzMxNDg0MDI4fQ.Ggy6YbLvLiIr_Yrfnyfy73yY8g1ts8bRRkrhpqvVV04";
                    $url = "https://www.wrike.com/api/v4/folder_blueprints/$blueprintId/launch_async";
                    //?parent=IEAEOPF5I4U765EL&title=".$projectNameExplode[0]."&titlePrefix=".$projectNameExplode[1]."&copyCustomFields=true&rescheduleMode=Start&rescheduleDate=$date";
                    $data = array('parent' => "IEAEOPF5I4U765EL", 'title' => "" . $projectNameExplode[0] . " " . $serviceType . "", 'titlePrefix' => "[" . $internalProjectName . "] ", 'copyCustomFields' => "true", 'rescheduleMode' => "Start", 'rescheduleDate' => "$date");
                    $postdata = json_encode($data);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                    $result = curl_exec($ch);
                    curl_close($ch);


                    '';

                    $result = json_decode($result, true);
                    $jobId = $result['data'][0]['id'];

                    $insertJobIdGold = sprintf(
                        "(`project_code`,`job_id`, `project_type`, `service_type`, `order_number`,`approval_status`) VALUES ('$projectCode', '$jobId' , '$serviceType', '$tosName', '$orderNumber','$status_wrike')",
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($jobId, "text"),
                        GetSQLValueString($serviceType, "text"),
                        GetSQLValueString($tosName, "text"),
                        GetSQLValueString($orderNumber, "text"),
                        GetSQLValueString($status_wrike, "text")
                    );

                    // $resJobIdGold = $DBWR->insert_data($tbl_initial_project, $insertJobIdGold);
                    $resJobIdGold = $DBWR->get_res("INSERT INTO sa_initial_project (`project_code`,`job_id`, `project_type`, `service_type`, `order_number`,`approval_status`) VALUES ('$projectCode', '$jobId' , '$serviceType', '$tosName', '$orderNumber','$status_wrike')");
                    // updateFile($namaFile, 'get_data_sb()', 'tabel : tbl_initial_project insert : ' . $insertJobIdGold);

                    '';

                    //INSERT LOG BLUEPRINT MAINTENANCE GOLD
                    $tbl_sa_log_activity = 'log_activity';
                    $insertLogBlueprintMaintenanceG = sprintf(
                        "(`activity`) VALUES ('Created $serviceType $tosName Blueprint in $projectCode with job id $jobId')",
                        GetSQLValueString($serviceType, "text"),
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($jobId, "text")
                    );

                    $resLogMaintenanceG = $DBWR->insert_data($tbl_sa_log_activity, $insertLogBlueprintMaintenanceG);
                    // updateFile($namaFile, 'get_data_sb()', 'tabel : tbl_sa_log_activity insert : ' . $insertLogBlueprintMaintenanceG);
                }
            }
        } else if ($serviceType == 2 && $tosId == 6) {
            $serviceType = "Maintenance";
            $tosName = "Silver";

            $conditionMaintenanceSilver = "object = 'Blueprint' AND condition1 = '$serviceType' AND condition2='$tosName'";
            $configMaintenanceSilver = $DBWR->get_data($tbl_wrike_config, $conditionMaintenanceSilver);
            $blueprintId = $configMaintenanceSilver[0]['object_id'];

            //date_default_timezone_set('Indonesia/Jakarta');
            $date = date('Y-m-d', time());

            //Check Table Initial Project
            $conditionInitialSilver = "project_code='$projectCode' AND project_type='$serviceType' AND service_type='$tosName' AND order_number = '$orderNumber'";
            $dataInitialProject = $DBWR->get_data($tbl_initial_project, $conditionInitialSilver);
            $totalRowInitialProject = $dataInitialProject[2];

            //Check Table Wrike Project List Maintenance
            $conditionWrikeProjectList = "project_code = '$projectCode' AND order_number = '$orderNumber' AND project_type = 'Maintenance'";
            $dataWrikeProjectList = $DBWR->get_data($tbl_wrike_project_list, $conditionWrikeProjectList);
            $projectCodeWPL = $dataWrikeProjectList[0]['project_code'];
            $projectTypeWPL = $dataWrikeProjectList[0]['project_type'];
            $totalRowWPL = $dataWrikeProjectList[2];

            if ($totalRowWPL > 0) {
                // echo "$projectCode - $orderNumber - $projectTypeWPL";
            } else {
                if ($totalRowInitialProject > 0) {
                    //    echo 'eh ada';
                } else {
                    $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6OTIzOTA4MSxcImNcIjo0NjcxMzc3LFwidVwiOjE2NDc3MzM1LFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNzMxNDg0MDI4fQ.Ggy6YbLvLiIr_Yrfnyfy73yY8g1ts8bRRkrhpqvVV04";
                    $url = "https://www.wrike.com/api/v4/folder_blueprints/$blueprintId/launch_async";
                    //?parent=IEAEOPF5I4U765EL&title=".$projectNameExplode[0]."&titlePrefix=".$projectNameExplode[1]."&copyCustomFields=true&rescheduleMode=Start&rescheduleDate=$date";
                    $data = array('parent' => "IEAEOPF5I4U765EL", 'title' => "" . $projectNameExplode[0] . " " . $serviceType . "", 'titlePrefix' => "[" . $internalProjectName . "] ", 'copyCustomFields' => "true", 'rescheduleMode' => "Start", 'rescheduleDate' => "$date");
                    $postdata = json_encode($data);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                    $result = curl_exec($ch);
                    curl_close($ch);
                    // print_r($result);

                    // echo "Maintenance";

                    $result = json_decode($result, true);
                    $jobId = $result['data'][0]['id'];

                    $insertJobIdSilver = sprintf(
                        "(`project_code`,`job_id`, `project_type`, `service_type`, `order_number`, `approval_status`) VALUES ('$projectCode', '$jobId' , '$serviceType', '$tosName', '$orderNumber','$status_wrike')",
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($jobId, "text"),
                        GetSQLValueString($serviceType, "text"),
                        GetSQLValueString($tosName, "text"),
                        GetSQLValueString($orderNumber, "text"),
                        GetSQLValueString($status_wrike, "text")
                    );

                    // $resJobIdSilver = $DBWR->insert_data($tbl_initial_project, $insertJobIdSilver);
                    $resJobIdSilver = $DBWR->get_res("INSERT INTO sa_initial_project (`project_code`,`job_id`, `project_type`, `service_type`, `order_number`, `approval_status`) VALUES ('$projectCode', '$jobId' , '$serviceType', '$tosName', '$orderNumber','$status_wrike')");
                    // updateFile($namaFile, 'get_data_sb()', 'tabel : tbl_initial_project insert : ' . $insertJobIdSilver);

                    // echo "$projectId - $projectCode - $projectName - $serviceType - $projectCategoryDescription - $blueprintId - $jobId <br/>";

                    //INSERT LOG BLUEPRINT MAINTENANCE SILVER
                    $tbl_sa_log_activity = 'log_activity';
                    $insertLogBlueprintMaintenanceS = sprintf(
                        "(`activity`) VALUES ('Created $serviceType $tosName Blueprint in $projectCode with job id $jobId')",
                        GetSQLValueString($serviceType, "text"),
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($jobId, "text")
                    );

                    $resLogMaintenanceS = $DBWR->insert_data($tbl_sa_log_activity, $insertLogBlueprintMaintenanceS);
                }
            }
        } else if ($serviceType == 2 && $tosId == 7 || $serviceType == 2 && $tosId == 18) {
            $serviceType = "Maintenance";
            $tosName = "Bronze";
            $conditionMaintenanceBronze = "object = 'Blueprint' AND condition1 = '$serviceType' AND condition2='$tosName'";
            $configMaintenanceBronze = $DBWR->get_data($tbl_wrike_config, $conditionMaintenanceBronze);
            $blueprintId = $configMaintenanceBronze[0]['object_id'];

            // date_default_timezone_set('Indonesia/Jakarta');
            $date = date('Y-m-d', time());

            //Check Table Initial Project
            $conditionInitialBronze = "project_code='$projectCode' AND project_type='$serviceType' AND service_type='$tosName' AND order_number = '$orderNumber'";
            $dataInitialProject = $DBWR->get_data($tbl_initial_project, $conditionInitialBronze);
            $totalRowInitialProject = $dataInitialProject[2];

            //Check Table Wrike Project List Maintenance
            $conditionWrikeProjectList = "project_code = '$projectCode' AND order_number = '$orderNumber' AND project_type = 'MSI Project Maintenance'";
            $dataWrikeProjectList = $DBWR->get_data($tbl_wrike_project_list, $conditionWrikeProjectList);
            $totalRowWPL = $dataWrikeProjectList[2];

            if ($totalRowWPL > 0) {
            } else {
                if ($totalRowInitialProject > 0) {
                    '';
                } else {
                    $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6OTIzOTA4MSxcImNcIjo0NjcxMzc3LFwidVwiOjE2NDc3MzM1LFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNzMxNDg0MDI4fQ.Ggy6YbLvLiIr_Yrfnyfy73yY8g1ts8bRRkrhpqvVV04";
                    $url = "https://www.wrike.com/api/v4/folder_blueprints/$blueprintId/launch_async";
                    //?parent=IEAEOPF5I4U765EL&title=".$projectNameExplode[0]."&titlePrefix=".$projectNameExplode[1]."&copyCustomFields=true&rescheduleMode=Start&rescheduleDate=$date";
                    $data = array('parent' => "IEAEOPF5I4U765EL", 'title' => "" . $projectNameExplode[0] . " " . $serviceType . "", 'titlePrefix' => "[" . $internalProjectName . "] ", 'copyCustomFields' => "true", 'rescheduleMode' => "Start", 'rescheduleDate' => "$date");
                    $postdata = json_encode($data);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                    $result = curl_exec($ch);
                    curl_close($ch);


                    '';

                    $result = json_decode($result, true);
                    $jobId = $result['data'][0]['id'];

                    $insertJobIdBronze = sprintf(
                        "(`project_code`,`job_id`, `project_type`, `service_type`, `order_number`, `approval_status`) VALUES ('$projectCode', '$jobId' , '$serviceType', '$tosName', '$orderNumber','$status_wrike')",
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($jobId, "text"),
                        GetSQLValueString($serviceType, "text"),
                        GetSQLValueString($tosName, "text"),
                        GetSQLValueString($orderNumber, "text"),
                        GetSQLValueString($status_wrike, "text")
                    );

                    $resJobIdBronze = $DBWR->get_res("INSERT INTO sa_initial_project (`project_code`,`job_id`, `project_type`, `service_type`, `order_number`, `approval_status`) VALUES ('$projectCode', '$jobId' , '$serviceType', '$tosName', '$orderNumber','$status_wrike')");

                    '';

                    //INSERT LOG BLUEPRINT MAINTENANCE BRONZE
                    $tbl_sa_log_activity = 'log_activity';
                    $insertLogBlueprintMaintenanceB = sprintf(
                        "(`activity`) VALUES ('Created $serviceType $tosName Blueprint in $projectCode with job id $jobId')",
                        GetSQLValueString($serviceType, "text"),
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($jobId, "text")
                    );

                    $resLogMaintenanceB = $DBWR->insert_data($tbl_sa_log_activity, $insertLogBlueprintMaintenanceB);
                    // updateFile($namaFile, 'get_data_sb()', 'tabel : tbl_sa_log_activity insert : ' . $insertLogBlueprintMaintenanceB);
                }
            }
        } else {
            '';
        }
    } while ($rowSB = $resSB->fetch_assoc());
    sleep(15);
    $tbl_initial_project = 'initial_project';
    $dataInitialProject = $DBWR->get_sql("SELECT * FROM sa_initial_project WHERE status=1 AND project_code='$ProjectCode'");
    $rowInitialProject = $dataInitialProject[0];
    $resInitialProject = $dataInitialProject[1];
    $totalRowInitialProject = $dataInitialProject[2];

    do {
        $projectCode = $rowInitialProject['project_code'];
        $jobId = $rowInitialProject['job_id'];
        $status = $rowInitialProject['status'];
        $prjctTypeee = $rowInitialProject['project_type'];
        '';
        // if ($status == 1) {
        $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6OTIzOTA4MSxcImNcIjo0NjcxMzc3LFwidVwiOjE2NDc3MzM1LFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNzMxNDg0MDI4fQ.Ggy6YbLvLiIr_Yrfnyfy73yY8g1ts8bRRkrhpqvVV04";
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, "https://www.wrike.com/api/v4/async_job/$jobId");
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);

        $result = json_decode($result, true);

        //GET Customer Name
        $data = $result['data'];

        for ($i = 0; $i < count($data); $i++) {
            $folderId = $data[0]['result']['folderId'];

            $conditionUpdateFolderId = "job_id = '$jobId'";

            if ($folderId != '') {
                $updateFolderIdData = sprintf(
                    "`project_id`= '$folderId', `status` = 2",
                    GetSQLValueString($folderId, "text")
                );

                $resFolderIdData = $DBWR->update_data($tbl_initial_project, $updateFolderIdData, $conditionUpdateFolderId);
                if ($prjctTypeee == 'Maintenance') {
                    $get_informasi_project = $DBSB->get_sql("SELECT * FROM sa_trx_project_list WHERE project_id=$projectID");
                    $ordrNumber = $get_informasi_project[0]['order_number'];
                    $cstmrName = $get_informasi_project[0]['customer_name'];
                    $prjctName = addslashes($get_informasi_project[0]['project_name']);
                    $soNo = $get_informasi_project[0]['so_number'];
                    $prjctType = 'Maintenance';
                    $mdlname = "HCM";
                    $DBHCM = get_conn($mdlname);
                    $getName = $DBHCM->get_sql("SELECT * FROM sa_view_employees_v2 WHERE employee_email='$pm_wrike'");
                    $namaorang = $getName[0]['employee_name'];
                    $rsrcEmail = addslashes("$namaorang<$pm_wrike>");
                    $rls = "";
                    $prjctRoles = "$sbf_role";
                    $approval = $_SESSION['Microservices_UserEmail'];
                    //$create_prochar = $DBWR->get_res("INSERT INTO sa_resource_assignment (project_code,project_id,order_number,no_so,project_type,customer_name,project_name,resource_email,roles,project_roles,start_progress,end_progress,status,approval_status,approval_to,created_by,modified_by,flag_assign_wrike) VALUES ('$ProjectCode','$folderId','$ordrNumber','$soNo','$prjctType','$cstmrName','$prjctName','$rsrcEmail','$rls','$prjctRoles',0,100,'Penuh','approved','$approval','$approval','$approval',0)");
                }
                // $infoOutput .= 'tabel : ' . $tbl_initial_project . ' update : ' . $updateFolderIdData . ' condition : ' . $conditionUpdateFolderId . '</br>';
                '';
            }
        }

        //INSERT LOG ASYNCJOB
        $tbl_sa_log_activity = 'log_activity';
        $insertLogAsyncJob = sprintf(
            "(`activity`) VALUES ('Asynced project $projectCode with job id $jobId')",
            GetSQLValueString($projectCode, "text")
        );

        $resLogAsync = $DBWR->insert_data($tbl_sa_log_activity, $insertLogAsyncJob);
        // $infoOutput .= 'tabel : ' . $tbl_sa_log_activity . ' insert : ' . $insertLogAsyncJob . '</br>';
        // }
    } while ($rowInitialProject = $resInitialProject->fetch_assoc());

    '';
    sleep(10);
    $querySQLIP = "SELECT * FROM sa_initial_project WHERE status = 2 AND project_id != '' AND project_code='$ProjectCode'";
    $dataSQLIP = $DBWR->get_sql($querySQLIP);
    $rowSQLIP = $dataSQLIP[0];
    $resSQLIP = $dataSQLIP[1];

    if ($dataSQLIP[2] == 0) {
        return 'Intial Project tidak ada yang memiliki status 2';
    }

    do {
        $projectCode = $rowSQLIP['project_code'];
        $projectId = $rowSQLIP['project_id'];
        $projectType = $rowSQLIP['project_type'];
        $OrderNumber = $rowSQLIP['order_number'];

        if ($projectType == 'Implementation') {
            $projectType = 1;
        } else {
            $projectType = 2;
        }

        $sqlGetProjectInternal = "SELECT * FROM sa_trx_project_list WHERE project_code = '$projectCode' AND order_number='$OrderNumber' AND STATUS = 'acknowledge'";
        $dataProjectInternal = $DBSB->get_sql($sqlGetProjectInternal);
        $projectNameInternalSB = $dataProjectInternal[0]['project_name_internal'];

        // $projectNameInternal = "[$projectNameInternalSB] Project Resources";
        // $projectNameInternal = "[$projectNameInternalSB] Service Budget";

        $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6OTIzOTA4MSxcImNcIjo0NjcxMzc3LFwidVwiOjE2NDc3MzM1LFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNzMxNDg0MDI4fQ.Ggy6YbLvLiIr_Yrfnyfy73yY8g1ts8bRRkrhpqvVV04";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://www.wrike.com/api/v4/folders/$projectId");
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($result, true);

        //GET Customer Name
        $data = $result['data'];

        for ($i = 0; $i < count($data); $i++) {
            $projectIdWrike = $data[$i]['id'];
            $jobRoleFolderIdArray = $data[$i]['childIds'];
            $title = $data[$i]['title'];

            for ($j = 0; $j < count($jobRoleFolderIdArray); $j++) {
                $jobRoleFolderId = $jobRoleFolderIdArray[$j];
                $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6OTIzOTA4MSxcImNcIjo0NjcxMzc3LFwidVwiOjE2NDc3MzM1LFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNzMxNDg0MDI4fQ.Ggy6YbLvLiIr_Yrfnyfy73yY8g1ts8bRRkrhpqvVV04";
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, "https://www.wrike.com/api/v4/folders/$jobRoleFolderId");
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                $result1 = curl_exec($curl);
                curl_close($curl);
                $result2 = json_decode($result1, true);

                //GET Customer Name
                $jobrolesName = $result2['data'][0]['title'];

                if (strpos($jobrolesName, 'Job Role') !== false) {
                    $jobrolesChildId = $result2['data'][0]['childIds'];
                    //var_dump($jobrolesChildId);

                    for ($i1 = 0; $i1 < count($jobrolesChildId); $i1++) {
                        $jobrolesChildId1 = $jobrolesChildId[$i1];

                        $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6OTIzOTA4MSxcImNcIjo0NjcxMzc3LFwidVwiOjE2NDc3MzM1LFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNzMxNDg0MDI4fQ.Ggy6YbLvLiIr_Yrfnyfy73yY8g1ts8bRRkrhpqvVV04";
                        $curl = curl_init();
                        curl_setopt($curl, CURLOPT_URL, "https://www.wrike.com/api/v4/folders/$jobrolesChildId1");
                        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                        $result2 = curl_exec($curl);
                        curl_close($curl);
                        $result3 = json_decode($result2, true);

                        //GET Customer Name
                        $sbName = $result3['data'][0]['title'];

                        '';

                        if (strpos($sbName, 'Service Budget') !== false) {
                            $sbFolderId = $result3['data'][0]['id'];
                            '';
                        }
                    }
                }
            }
        }

        $sqlMandaysInformation = "SELECT * FROM sa_trx_project_list WHERE project_code = '$projectCode' AND order_number='$OrderNumber' AND STATUS = 'acknowledge'";
        $dataMandaysInformation = $DBSB->get_sql($sqlMandaysInformation);
        $idProject = $dataMandaysInformation[0]['project_id'];
        //$idProject = 16002;

        $sqlMandays = "SELECT SUBSTRING(resource_level, 1, 1) AS resource_level, mantotal, mandays, brand FROM sa_trx_project_mandays WHERE project_id = $projectID AND mantotal != '' AND mandays !='' AND service_type=$projectType AND (SUBSTRING(resource_level, 1, 1) BETWEEN 1 AND 4)";
        $dataSqlMandays = $DBSB->get_sqlV2($sqlMandays);
        $rowSqlMandays = $dataSqlMandays[0];
        $resSqlMandays = $dataSqlMandays[1];

        $sqlMandaysInformation1 = "SELECT SUBSTRING(resource_level, 1, 1) AS resource_level, mantotal, mandays, brand FROM sa_trx_project_mandays WHERE project_id = $projectID AND mantotal != '' AND mandays !='' AND service_type=$projectType AND (SUBSTRING(resource_level, 1, 1) BETWEEN 1 AND 4)";
        $dataSqlMandaysInformation1 = $DBSB->get_sql($sqlMandaysInformation1);
        $rowSqlMandaysInformation1 = $dataSqlMandaysInformation1[0];
        $resSqlMandaysInformation1 = $dataSqlMandaysInformation1[1];

        do {
            $resourceLevels = $rowSqlMandays['resource_level'];
            $mantotal = $rowSqlMandays['mantotal'];
            $mandays = $rowSqlMandays['mandays'];

            '';

            if ($resourceLevels == 1) {
                $arrMantotals1[] = $mantotal;
                $totalMandays1 = $mantotal * $mandays;
                $arrTotalMandays1[] = $totalMandays1;
            } else if ($resourceLevels == 2) {
                $arrMantotals2[] = $mantotal;
                $totalMandays2 = $mantotal * $mandays;
                $arrTotalMandays2[] = $totalMandays2;
            } else if ($resourceLevels == 3) {
                $arrMantotals3[] = $mantotal;
                $totalMandays3 = $mantotal * $mandays;
                $arrTotalMandays3[] = $totalMandays3;
            } else {
                $arrMantotals4[] = $mantotal;
                $totalMandays4 = $mantotal * $mandays;
                $arrTotalMandays4[] = $totalMandays4;
            }
        } while ($rowSqlMandays = $resSqlMandays->fetch_assoc());

        // Variabel mantotal
        $mantotals1 = array_sum($arrMantotals1);
        $mantotals2 = array_sum($arrMantotals2);
        $mantotals3 = array_sum($arrMantotals3);
        $mantotals4 = array_sum($arrMantotals4);

        // Variabel Mandays
        $mandaysJobRoles1 = array_sum($arrTotalMandays1);
        $mandaysJobRoles2 = array_sum($arrTotalMandays2);
        $mandaysJobRoles3 = array_sum($arrTotalMandays3);
        $mandaysJobRoles4 = array_sum($arrTotalMandays4);

        do {
            $resourceLevel = $rowSqlMandaysInformation1['resource_level'];
            $brand = $rowSqlMandaysInformation1['brand'];

            '';

            if ($resourceLevel != '') {
                if ($resourceLevel == 1) {
                    $mantotals = $mantotals1;
                    $totalMandays = $mandaysJobRoles1;
                } else if ($resourceLevel == 2) {
                    $mantotals = $mantotals2;
                    $totalMandays = $mandaysJobRoles2;
                } else if ($resourceLevel == 3) {
                    $mantotals = $mantotals3;
                    $totalMandays = $mandaysJobRoles3;
                } else {
                    $mantotals = $mantotals4;
                    $totalMandays = $mandaysJobRoles4;
                }
                // '';

                $queryLookupCatalogs = "SELECT resource_qualification FROM sa_mst_resource_catalogs WHERE resource_catalog_id = $resourceLevel";
                $dataLookupCatalogs = $DBSB->get_sql($queryLookupCatalogs);
                $resourceCatalogName = $dataLookupCatalogs[0]['resource_qualification'];

                $tbl_initial_jobroles = 'initial_jobroles';
                $queryCheckJobRoles = "SELECT * FROM sa_initial_jobroles WHERE project_id = '$projectId' AND resource_category_id = $resourceLevel";
                $dataCheckJobRoles = $DBWRKLD->get_sql($queryCheckJobRoles);
                $totalRowJobRoles = $dataCheckJobRoles[2];

                if ($totalRowJobRoles > 0) {
                    '';
                } else {
                    $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6OTIzOTA4MSxcImNcIjo0NjcxMzc3LFwidVwiOjE2NDc3MzM1LFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNzMxNDg0MDI4fQ.Ggy6YbLvLiIr_Yrfnyfy73yY8g1ts8bRRkrhpqvVV04";
                    $url = "https://www.wrike.com/api/v4/custom_item_types/IEAEOPF5PIAAFWRU/instantiate";
                    //?parent=IEAEOPF5I4U765EL&title=".$projectNameExplode[0]."&titlePrefix=".$projectNameExplode[1]."&copyCustomFields=true&rescheduleMode=Start&rescheduleDate=$date";
                    $data = array('title' => "$resourceCatalogName", 'parentId' => "$sbFolderId");
                    $postdata = json_encode($data);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                    $result3 = curl_exec($ch);
                    curl_close($ch);


                    '';
                    $result3 = json_decode($result3, true);
                    $taskJobId = $result3['data'][0]['id'];

                    //INSERT LOG MODIFT IMPLEMENTATION
                    $insertJobRoles = sprintf(
                        "(`task_id`, `parent_id`, `project_code`, `project_id`, `resource_category_id`, `total_mandays`) VALUES ('$taskJobId', 'SERVICE BUDGET', '$projectCode', '$projectIdWrike', '$resourceLevel',$totalMandays)",
                        GetSQLValueString($taskJobId, "text"),
                        GetSQLValueString($sbFolderId, "text"),
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($resourceCatalogName, "text")
                    );

                    //$resInitialJobRoles = $DBWRKLD->insert_data($tbl_initial_jobroles, $insertJobRoles);
                    $resInitialJobRoles = $DBWRKLD->get_res("INSERT INTO sa_initial_jobroles (task_id,parent_id,project_code,project_id,resource_category_id,total_mandays) VALUES ('$taskJobId', 'SERVICE BUDGET', '$projectCode', '$projectIdWrike', '$resourceLevel',$totalMandays)");

                    $url = "https://www.wrike.com/api/v4/tasks/$taskJobId";
                    $dataPut = array('description' => "Total Mans = $mantotals", 'customFields' => "[{'id':'IEAEOPF5JUAC3YH2','value':'$totalMandays'}]");
                    $put_data = json_encode($dataPut);
                    $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6OTIzOTA4MSxcImNcIjo0NjcxMzc3LFwidVwiOjE2NDc3MzM1LFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNzMxNDg0MDI4fQ.Ggy6YbLvLiIr_Yrfnyfy73yY8g1ts8bRRkrhpqvVV04";

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $put_data);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));

                    $response = curl_exec($ch);
                    curl_close($ch);


                    '';
                }
            }
        } while ($rowSqlMandaysInformation1 = $resSqlMandaysInformation1->fetch_assoc());


        $sqlMandaysInformation2 = "SELECT SUBSTRING(resource_level, 1, 1) AS resource_level, mantotal, mandays, brand FROM sa_trx_project_mandays WHERE project_id = $projectID AND mantotal != '' AND mandays !='' AND service_type=$projectType AND (SUBSTRING(resource_level, 1, 1) BETWEEN 5 AND 7)";
        $dataSqlMandaysInformation2 = $DBSB->get_sql($sqlMandaysInformation2);
        $rowSqlMandaysInformation2 = $dataSqlMandaysInformation2[0];
        $resSqlMandaysInformation2 = $dataSqlMandaysInformation2[1];

        do {
            $resourceLevelEngineer = $rowSqlMandaysInformation2['resource_level'];
            $brandEngineer = $rowSqlMandaysInformation2['brand'];
            $mantotalEngineer = $rowSqlMandaysInformation2['mantotal'];
            $mandaysEngineer = $rowSqlMandaysInformation2['mandays'];
            $totalMandaysEngineer = $mantotalEngineer * $mandaysEngineer;

            if ($resourceLevelEngineer != '') {
                $queryLookupCatalogsEngineer = "SELECT resource_qualification FROM sa_mst_resource_catalogs WHERE resource_catalog_id = $resourceLevelEngineer";
                $dataLookupCatalogsEngineer = $DBSB->get_sql($queryLookupCatalogsEngineer);
                $resourceCatalogNameEngineer = $dataLookupCatalogsEngineer[0]['resource_qualification'];

                $tbl_initial_jobroles = 'initial_jobroles';
                $queryCheckJobRolesEngineer = "SELECT * FROM sa_initial_jobroles WHERE project_id = '$projectId' AND resource_category_id = $resourceLevelEngineer AND brand = '$brandEngineer'";
                $dataCheckJobRolesEngineer = $DBWRKLD->get_sql($queryCheckJobRolesEngineer);
                $totalRowJobRolesEngineer = $dataCheckJobRolesEngineer[2];

                if ($totalRowJobRolesEngineer > 0) {
                } else {
                    $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6OTIzOTA4MSxcImNcIjo0NjcxMzc3LFwidVwiOjE2NDc3MzM1LFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNzMxNDg0MDI4fQ.Ggy6YbLvLiIr_Yrfnyfy73yY8g1ts8bRRkrhpqvVV04";
                    $url = "https://www.wrike.com/api/v4/custom_item_types/IEAEOPF5PIAAFWRU/instantiate";
                    //?parent=IEAEOPF5I4U765EL&title=".$projectNameExplode[0]."&titlePrefix=".$projectNameExplode[1]."&copyCustomFields=true&rescheduleMode=Start&rescheduleDate=$date";
                    $data = array('title' => "$resourceCatalogNameEngineer $brandEngineer", 'parentId' => "$sbFolderId");
                    $postdata = json_encode($data);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                    $result4 = curl_exec($ch);
                    curl_close($ch);


                    '';
                    $result4 = json_decode($result4, true);
                    $taskJobIdEngineer = $result4['data'][0]['id'];

                    //INSERT LOG MODIFT IMPLEMENTATION

                    $insertJobRolesEngineer = sprintf(
                        "(`task_id`, `parent_id`, `project_code`, `project_id`, `resource_category_id`, `brand`, `total_mandays`) VALUES ('$taskJobIdEngineer', 'SERVICE BUDGET', '$projectCode', '$projectIdWrike', '$resourceLevelEngineer', '$brandEngineer', $totalMandaysEngineer)",
                        GetSQLValueString($taskJobIdEngineer, "text"),
                        GetSQLValueString($sbFolderId, "text"),
                        GetSQLValueString($projectCode, "text"),
                        GetSQLValueString($resourceCatalogName, "text")
                    );

                    //$resInitialJobRolesEngineer = $DBWRKLD->insert_data($tbl_initial_jobroles, $insertJobRolesEngineer);
                    $resInitialJobRolesEngineer = $DBWRKLD->get_res("INSERT INTO sa_initial_jobroles (task_id, parent_id, project_code, project_id, resource_category_id, brand, total_mandays) VALUES ('$taskJobIdEngineer', 'SERVICE BUDGET', '$projectCode', '$projectIdWrike', '$resourceLevelEngineer', '$brandEngineer', $totalMandaysEngineer)");

                    $urlEngineer = "https://www.wrike.com/api/v4/tasks/$taskJobIdEngineer";
                    $dataPutEngineer = array('description' => "Mans = $mantotalEngineer", 'customFields' => "[{'id':'IEAEOPF5JUAC3YH2','value':'$totalMandaysEngineer'}]");
                    $put_dataEngineer = json_encode($dataPutEngineer);
                    $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6OTIzOTA4MSxcImNcIjo0NjcxMzc3LFwidVwiOjE2NDc3MzM1LFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNzMxNDg0MDI4fQ.Ggy6YbLvLiIr_Yrfnyfy73yY8g1ts8bRRkrhpqvVV04";

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $urlEngineer);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $put_dataEngineer);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));

                    $response = curl_exec($ch);
                    curl_close($ch);


                    '';
                }
            }
        } while ($rowSqlMandaysInformation2 = $resSqlMandaysInformation2->fetch_assoc());
    } while ($rowSQLIP = $resSQLIP->fetch_assoc());

    '';

    $tbl_wrike_config = 'wrike_config';
    $tbl_initial_project = 'initial_project';
    // $dataInitialProject = $DBWR->get_data($tbl_initial_project);
    $dataInitialProject = $DBWR->get_sql("SELECT * FROM sa_initial_project WHERE status=2 AND project_code='$ProjectCode'");
    $rowInitialProject = $dataInitialProject[0];
    $resInitialProject = $dataInitialProject[1];
    $totalRowInitialProject = $dataInitialProject[2];

    do {
        $projectType = '';
        $projectId = $rowInitialProject['project_id'];
        $projectCode = $rowInitialProject['project_code'];
        $projectType = $rowInitialProject['project_type'];
        // $projectSbId = $rowInitialProject['project_sb_id'];
        $orderNumber = $rowInitialProject['order_number'];
        $status = $rowInitialProject['status'];

        // if ($status == 2) {
        $queryOwner = "SELECT * FROM sa_wrike_config WHERE object = 'Owner' AND condition1 = '$projectType'";
        $dataQuery = $DBWR->get_sql($queryOwner);
        $rowOwner = $dataQuery[0];
        $resOwner = $dataQuery[1];
        $fullOwnerId = '';

        do {
            $ownerId = $rowOwner['object_id'];
            $fullOwnerId = '"' . $ownerId . '", ' . $fullOwnerId;
            $fullOwnerId = rtrim($fullOwnerId, ', ');
        } while ($rowOwner = $resOwner->fetch_assoc());

        if (isset($pm_wrike)) {
            $checkIdWrike = $DBWR->get_sql("SELECT * FROM sa_contact_user WHERE email ='$pm_wrike'");
            $OwnerId = $checkIdWrike[0]['id'];
        } else {
            $OwnerId = $fullOwnerId;
        }

        if ($projectType == 'Implementation') {
            $projectType = 1;
        } else if ($projectType == 'Maintenance') {
            $projectType = 2;
        }

        $db_sb = "SERVICE_BUDGET";
        $DBSB = get_conn($db_sb);
        $queryEnterpriseProjectType = "SELECT a.project_id, a.project_code, a.project_name, a.project_name_internal, a.customer_name, a.amount_idr, a.amount_usd, 
            a.so_number, a.order_number, a.bundling, b.tos_id, b.tos_category_id, b.service_type, c.tos_name 
            FROM sa_trx_project_list AS a 
            LEFT JOIN sa_trx_project_implementations AS b ON a.project_id=b.project_id 
            LEFT JOIN sa_mst_type_of_service AS c ON b.tos_id=c.tos_id 
            WHERE a.project_id=$projectID";

        $dataProjectCategory = $DBSB->get_sql($queryEnterpriseProjectType);
        $rowProjectCategory = $dataProjectCategory[0];
        $resProjectCategory = $dataProjectCategory[1];
        $totalRowProjectCategory = $dataProjectCategory[2];

        do {
            $projectCategory = '';
            $fullTypeOfService = '';
            $amountIDR = '';
            $amountUSD = '';
            $currency = '';
            $soNumber = '';
            $projectName = $rowProjectCategory['project_name'];
            $projectNameExplode = explode('#', $projectName);
            $soNumber = $rowProjectCategory['so_number'];
            $orderNumberSB = $rowProjectCategory['order_number'];
            $customerName = $rowProjectCategory['customer_name'];
            $projectCategory = $rowProjectCategory['tos_category_id'];
            $serviceType1 = $rowProjectCategory['service_type'];
            $serviceType = $rowProjectCategory['tos_id'];
            $serviceTypeImplement = $rowProjectCategory['tos_id'];
            $serviceTypeImplement = rtrim($serviceTypeImplement, ';');
            $serviceTypeImplement = explode(';', $serviceTypeImplement);
            $tosNameraw = isset($rowProjectCategory['tos_name']);
            if (isset($tosNameraw)) {
                $tosName = $rowProjectCategory['tos_name'];
            } else {
                $tosName = '';
            }
            $internalProjectName = $rowProjectCategory['project_name_internal'];
            $amountIDR = $rowProjectCategory['amount_idr'];
            $amountIDR = round($amountIDR, 0);
            $amountUSD = $rowProjectCategory['amount_usd'];
            $amountUSD = round($amountUSD, 0);

            if ($amountIDR != '') {
                $currency = 'IDR';
                $priceService = $amountIDR;
                $conditionCurrencyOption = "object = 'CustomField' AND title = 'Currency' and condition2 = '" . $currency . "'";
                $customfieldCurrency = $DBWR->get_data($tbl_wrike_config, $conditionCurrencyOption);
                $currencyOptionId = $customfieldCurrency[0]['object_id'];
            } else if ($amountUSD != '') {
                $currency = 'USD';
                $priceService = $amountUSD;
                $conditionCurrencyOption = "object = 'CustomField' AND title = 'Currency' and condition2 = '" . $currency . "'";
                $customfieldCurrency = $DBWR->get_data($tbl_wrike_config, $conditionCurrencyOption);
                $currencyOptionId = $customfieldCurrency[0]['object_id'];
            }

            if ($projectType == 1 && $serviceType1 == 1) {
                if ($projectCategory == 1) {
                    $projectCategoryDescription = 'High';
                } else if ($projectCategory == 2) {
                    $projectCategoryDescription = 'Medium';
                } else if ($projectCategory == 3) {
                    $projectCategoryDescription = 'Standard';
                }

                for ($j = 0; $j < count($serviceTypeImplement); $j++) {
                    $conditionServiceName = "tos_id = '" . $serviceTypeImplement[$j] . "'";
                    $tblMstTypeOfService = 'mst_type_of_service';
                    $getTypeOfService = $DBSB->get_data($tblMstTypeOfService, $conditionServiceName);
                    $valueTypeOfService = $getTypeOfService[0]['tos_name'];
                    $fullTypeOfService = '"' . $valueTypeOfService . '", ' . $fullTypeOfService;
                    $fullTypeOfService = rtrim($fullTypeOfService, ', ');
                }

                if ($serviceType1 == 1) {
                    $enterpriseProjectType = "MSI Project Implementation";
                }

                //Enterprise Project Type ID
                $conditionEnterpriseProjectType = "object = 'CustomField' AND title = 'Enterprise Project Type' AND condition1 = '$enterpriseProjectType'";
                $customfieldEPT = $DBWR->get_data($tbl_wrike_config, $conditionEnterpriseProjectType);
                $eptId = $customfieldEPT[0]['object_id'];

                //Project Category ID
                $conditionProjectCategory = "object = 'CustomField' AND title = 'Project Category' AND condition2 = '$projectCategoryDescription'";
                $customfieldPC = $DBWR->get_data($tbl_wrike_config, $conditionProjectCategory);
                $pcId = $customfieldPC[0]['object_id'];

                //Project Code ID
                $conditionProjectCode = "object = 'CustomField' AND title = 'Project Code'";
                $customfieldPCode = $DBWR->get_data($tbl_wrike_config, $conditionProjectCode);
                $projectCodeId = $customfieldPCode[0]['object_id'];

                //Sales Order Number Id
                $conditionSalesOrder = "object = 'CustomField' AND title = 'Sales Order'";
                $customfieldSOrder = $DBWR->get_data($tbl_wrike_config, $conditionSalesOrder);
                $noSOId = $customfieldSOrder[0]['object_id'];

                //Project Value ID
                $conditionProjectValue = "object = 'CustomField' AND title = 'Project Value'";
                $customfieldPValue = $DBWR->get_data($tbl_wrike_config, $conditionProjectValue);
                $projectValueId = $customfieldPValue[0]['object_id'];

                //Project Service ID
                $conditionProjectService = "object = 'CustomField' AND title = 'Project Service'";
                $customfieldPService = $DBWR->get_data($tbl_wrike_config, $conditionProjectService);
                $projectServiceId = $customfieldPService[0]['object_id'];

                //Mandays
                $mandaysQuery = "SELECT sa_trx_project_list.project_id, sa_trx_project_list.project_id, sa_trx_project_list.order_number, sa_trx_project_list.project_code, sa_trx_project_mandays.mantotal, 
                    sa_trx_project_mandays.mandays,
                    sa_trx_project_mandays.service_type,
                    sa_trx_project_mandays.brand FROM sa_trx_project_list JOIN sa_trx_project_mandays ON sa_trx_project_list.project_id=sa_trx_project_mandays.project_id WHERE sa_trx_project_list.project_code='$projectCode'
                    AND sa_trx_project_mandays.service_type=$projectType AND sa_trx_project_list.order_number='$orderNumberSB' AND sa_trx_project_list.project_id=$projectID";

                $dataMandays = $DBSB->get_sql($mandaysQuery);
                $rowDataMandays = $dataMandays[0];
                $resDataMandays = $dataMandays[1];
                $totalRowDataMandays = $dataMandays[2];
                unset($arrTotalMandays);

                do {
                    $mantotal = $rowDataMandays['mantotal'];
                    $mandays = $rowDataMandays['mandays'];

                    if ($mantotal != NULL && $mandays != NULL) {
                        $totalMandays = $mantotal * $mandays;
                        $arrTotalMandays[] = $totalMandays;
                    } else {
                        '';
                    }
                } while ($rowDataMandays = $resDataMandays->fetch_assoc());

                // Variabel Mandays
                $mandaysWrike = array_sum($arrTotalMandays);

                //Mandays ID
                $conditionTotalMandays = "object = 'CustomField' AND title = 'Total Mandays'";
                $customfieldTMandays = $DBWR->get_data($tbl_wrike_config, $conditionTotalMandays);
                $totalMandaysId = $customfieldTMandays[0]['object_id'];

                //Customer ID
                $conditionCustomerName = "object = 'CustomField' AND title = 'Customer'";
                $customfieldCName = $DBWR->get_data($tbl_wrike_config, $conditionCustomerName);
                $customerNameId = $customfieldCName[0]['object_id'];

                //Internal Project Name ID
                $conditionInternalProjectName = "object = 'CustomField' AND title = 'Internal Project Name'";
                $customfieldInternalProjectName = $DBWR->get_data($tbl_wrike_config, $conditionInternalProjectName);
                $internalProjectNameId = $customfieldInternalProjectName[0]['object_id'];

                //Order Number ID
                $conditionOrderNumber = "object = 'CustomField' AND title = 'Order Number'";
                $customfieldOrderNumber = $DBWR->get_data($tbl_wrike_config, $conditionOrderNumber);
                $orderNumberId = $customfieldOrderNumber[0]['object_id'];

                '';

                $url = "https://www.wrike.com/api/v4/folders/$projectId"; //?project={'ownersAdd':[$fullOwnerId]}&customFields=[{'id':'$pcId', 'value':'$projectCategoryDescription'}]";
                $data = array("project" => "{'ownersAdd':[$OwnerId]}", "customFields" => "[{'id':'$eptId', 'value':'$enterpriseProjectType'}, {'id':'$orderNumberId', 'value':'$orderNumberSB'}, {'id':'$currencyOptionId', 'value':'$currency'}, {'id':'$pcId', 'value':'$projectCategoryDescription'},  {'id':'$projectCodeId','value':'$projectCode'}, {'id':'$noSOId','value':'$soNumber'}, {'id':'$projectValueId','value':'$priceService'}, {'id':'$projectServiceId','value':'[$fullTypeOfService]'}, {'id':'$totalMandaysId','value':'$mandaysWrike'}, {'id':'$customerNameId','value':'$customerName'}, {'id':'$internalProjectNameId','value':'$internalProjectName'}]");

                //Internal Project Name
                //, {'id':'$internalProjectNameId','value':'" . $projectNameExplode[1] . "'}
                $put_data = json_encode($data);
                $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6OTIzOTA4MSxcImNcIjo0NjcxMzc3LFwidVwiOjE2NDc3MzM1LFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNzMxNDg0MDI4fQ.Ggy6YbLvLiIr_Yrfnyfy73yY8g1ts8bRRkrhpqvVV04";

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $put_data);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));

                $response = curl_exec($ch);
                curl_close($ch);


                $conditionStatus3 = "project_id = '$projectId'";
                $updateStatus3 = sprintf("`status`= 3");
                $resStatus3 = $DBWR->update_data($tbl_initial_project, $updateStatus3, $conditionStatus3);
                // $infoOutput .= 'tabel : ' . $tbl_initial_project . ' update : ' . $updateStatus3 . ' condition : ' . $conditionStatus3 . '</br>';

                '';

                '';

                //INSERT LOG MODIFT IMPLEMENTATION
                $tbl_sa_log_activity = 'log_activity';
                $insertLogCustomFieldsImplementation = sprintf(
                    "(`activity`) VALUES ('Edited customfields Wrike with id project $projectId and code $projectCode - Implementation')",
                    GetSQLValueString($projectCode, "text"),
                    GetSQLValueString($projectId, "text")
                );

                $resLogCustomFieldsImplementation = $DBWR->insert_data($tbl_sa_log_activity, $insertLogCustomFieldsImplementation);
                // $infoOutput .= 'tabel : ' . $tbl_sa_log_activity . ' insert : ' . $insertLogCustomFieldsImplementation . '</br>';
            } else if ($projectType == 2 && $serviceType1 == 2) {
                if ($serviceType == 5) {
                    $serviceTypeDescription = 'Gold';
                } else if ($serviceType == 8) {
                    $serviceTypeDescription = 'Gold';
                } else if ($serviceType == 6) {
                    $serviceTypeDescription = 'Silver';
                } else if ($serviceType == 7) {
                    $serviceTypeDescription = 'Bronze';
                }

                if ($serviceType1 == 2) {
                    $enterpriseProjectType = "MSI Project Maintenance";
                }

                //Enterprise Project Type ID
                $conditionEnterpriseProjectType = "object = 'CustomField' AND title = 'Enterprise Project Type' AND condition1 = '$enterpriseProjectType'";
                $customfieldEPT = $DBWR->get_data($tbl_wrike_config, $conditionEnterpriseProjectType);
                $eptId = $customfieldEPT[0]['object_id'];

                //Service Type ID
                $conditionServiceTypeMaintenance = "object = 'CustomField' AND title = 'Service Type' AND condition2 = '$serviceTypeDescription'";
                $customfieldServiceTypeMaintenance = $DBWR->get_data($tbl_wrike_config, $conditionServiceTypeMaintenance);
                $serviceTypeId = $customfieldServiceTypeMaintenance[0]['object_id'];

                //Project Code ID
                $conditionProjectCode = "object = 'CustomField' AND title = 'Project Code'";
                $customfieldPCode = $DBWR->get_data($tbl_wrike_config, $conditionProjectCode);
                $projectCodeId = $customfieldPCode[0]['object_id'];

                //Sales Order Number Id
                $conditionSalesOrder = "object = 'CustomField' AND title = 'Sales Order'";
                $customfieldSOrder = $DBWR->get_data($tbl_wrike_config, $conditionSalesOrder);
                $noSOId = $customfieldSOrder[0]['object_id'];

                //Project Value ID
                $conditionProjectValue = "object = 'CustomField' AND title = 'Project Value'";
                $customfieldPValue = $DBWR->get_data($tbl_wrike_config, $conditionProjectValue);
                $projectValueId = $customfieldPValue[0]['object_id'];

                //Mandays
                $mandaysQuery = "SELECT sa_trx_project_list.project_id, sa_trx_project_list.project_id, sa_trx_project_list.order_number, sa_trx_project_list.project_code, sa_trx_project_mandays.mantotal, 
                    sa_trx_project_mandays.mandays,
                    sa_trx_project_mandays.service_type,
                    sa_trx_project_mandays.brand FROM sa_trx_project_list JOIN sa_trx_project_mandays ON sa_trx_project_list.project_id=sa_trx_project_mandays.project_id WHERE sa_trx_project_list.project_code='$projectCode'
                    AND sa_trx_project_mandays.service_type=$projectType AND sa_trx_project_list.order_number='$orderNumberSB'";

                $dataMandays = $DBSB->get_sql($mandaysQuery);
                $rowDataMandays = $dataMandays[0];
                $resDataMandays = $dataMandays[1];
                $totalRowDataMandays = $dataMandays[2];
                unset($arrTotalMandays);

                do {
                    $mantotal = $rowDataMandays['mantotal'];
                    $mandays = $rowDataMandays['mandays'];

                    if ($mantotal != NULL && $mandays != NULL) {
                        $totalMandays = $mantotal * $mandays;
                        $arrTotalMandays[] = $totalMandays;
                    } else {
                        '';
                    }
                } while ($rowDataMandays = $resDataMandays->fetch_assoc());

                // Variabel Mandays
                if ($arrTotalMandays != '') {
                    $mandaysWrike = array_sum($arrTotalMandays);
                } else {
                    $mandaysWrike = '';
                }

                //Mandays ID
                $conditionTotalMandays = "object = 'CustomField' AND title = 'Total Mandays'";
                $customfieldTMandays = $DBWR->get_data($tbl_wrike_config, $conditionTotalMandays);
                $totalMandaysId = $customfieldTMandays[0]['object_id'];

                //Customer ID
                $conditionCustomerName = "object = 'CustomField' AND title = 'Customer'";
                $customfieldCName = $DBWR->get_data($tbl_wrike_config, $conditionCustomerName);
                $customerNameId = $customfieldCName[0]['object_id'];

                //Internal Project Name ID
                $conditionInternalProjectName = "object = 'CustomField' AND title = 'Internal Project Name'";
                $customfieldInternalProjectName = $DBWR->get_data($tbl_wrike_config, $conditionInternalProjectName);
                $internalProjectNameId = $customfieldInternalProjectName[0]['object_id'];

                //Order Number ID
                $conditionOrderNumber = "object = 'CustomField' AND title = 'Order Number'";
                $customfieldOrderNumber = $DBWR->get_data($tbl_wrike_config, $conditionOrderNumber);
                $orderNumberId = $customfieldOrderNumber[0]['object_id'];

                $url = "https://www.wrike.com/api/v4/folders/$projectId"; //?project={'ownersAdd':[$fullOwnerId]}&customFields=[{'id':'$pcId', 'value':'$projectCategoryDescription'}]";
                $data = array("project" => "{'ownersAdd':[$OwnerId]}", "customFields" => "[{'id':'$eptId', 'value':'$enterpriseProjectType'}, {'id':'$orderNumberId', 'value':'$orderNumberSB'}, {'id':'$currencyOptionId', 'value':'$currency'}, {'id':'$serviceTypeId', 'value':'$serviceTypeDescription'}, {'id':'$projectCodeId','value':'$projectCode'}, {'id':'$noSOId','value':'$soNumber'}, {'id':'$projectValueId','value':'$priceService'}, {'id':'$totalMandaysId','value':'0'}, {'id':'$customerNameId','value':'$customerName'}, {'id':'$internalProjectNameId','value':'$internalProjectName'}]");
                $put_data = json_encode($data);
                $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6OTIzOTA4MSxcImNcIjo0NjcxMzc3LFwidVwiOjE2NDc3MzM1LFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNzMxNDg0MDI4fQ.Ggy6YbLvLiIr_Yrfnyfy73yY8g1ts8bRRkrhpqvVV04";

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $put_data);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));

                $response = curl_exec($ch);
                curl_close($ch);


                $conditionStatus3 = "project_id = '$projectId'";
                $updateStatus3 = sprintf("`status`= 3");
                $resStatus3 = $DBWR->update_data($tbl_initial_project, $updateStatus3, $conditionStatus3);
                // $infoOutput .= 'tabel : ' . $tbl_initial_project . ' update : ' . $updateStatus3 . ' condition : ' . $conditionStatus3 . '</br>';

                '';

                '';

                //INSERT LOG MODIFT MAINTENANCE
                $tbl_sa_log_activity = 'log_activity';
                $insertLogCustomFieldsMaintenance = sprintf(
                    "(`activity`) VALUES ('Edited customfields Wrike with id project $projectId and code $projectCode - Maintenance $serviceTypeDescription')",
                    GetSQLValueString($projectCode, "text"),
                    GetSQLValueString($projectId, "text"),
                    GetSQLValueString($serviceTypeDescription, "text")
                );

                $resLogCustomFieldsMaintenance = $DBWR->insert_data($tbl_sa_log_activity, $insertLogCustomFieldsMaintenance);
                // $infoOutput .= 'tabel : ' . $tbl_sa_log_activity . ' insert : ' . $insertLogCustomFieldsMaintenance . '</br>';
            }
        } while ($rowProjectCategory = $resProjectCategory->fetch_assoc());
        // } 
    } while ($rowInitialProject = $resInitialProject->fetch_assoc());

    '';

    // $tbl_initial_project = "initial_project";
    // $tbl_wrike_config = "wrike_config";
    // // $dataApproval = $DBWR->get_data($tbl_initial_project);
    // $dataApproval = $DBWR->get_sql("SELECT * FROM sa_initial_project WHERE status=3 AND project_code='$ProjectCode'");
    // $rowDataApproval = $dataApproval[0];
    // $resDataApproval = $dataApproval[1];
    // $totalRowDataApproval = $dataApproval[2];

    // do {
    //     $projectId = $rowDataApproval['project_id'];
    //     $projectCode = $rowDataApproval['project_code'];
    //     $projectType = $rowDataApproval['project_type'];
    //     $status = $rowDataApproval['status'];
    //     //'';

    //     $conditionOwner = "object = 'Owner' AND condition1 = '$projectType'";
    //     $dataOwner = $DBWR->get_data($tbl_wrike_config, $conditionOwner);
    //     $rowOwner = $dataOwner[0];
    //     $resOwner = $dataOwner[1];

    //     $fullOwnerId = '';

    //     do {
    //         $ownerId = $rowOwner['object_id'];
    //         $fullOwnerId = '"' . $ownerId . '", ' . $fullOwnerId;
    //         $fullOwnerId = rtrim($fullOwnerId, ', ');
    //     } while ($rowOwner = $resOwner->fetch_assoc());

    //     $conditionApprovalDay = "object = 'approval' and title ='Approval Maximum Day'";
    //     $dataApprovalDay = $DBWR->get_data($tbl_wrike_config);
    //     $day = $dataApprovalDay[0]['condition1'];

    //     $dueDate = date('Y-m-d', strtotime("+$day  day"));

    //     // if ($status == 3) {
    //         $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6OTIzOTA4MSxcImNcIjo0NjcxMzc3LFwidVwiOjE2NDc3MzM1LFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNzMxNDg0MDI4fQ.Ggy6YbLvLiIr_Yrfnyfy73yY8g1ts8bRRkrhpqvVV04";
    //         $url = "https://www.wrike.com/api/v4/folders/$projectId/approvals"; //?approvers=$ownerId&description=$descApproval&dueDate=$dueApproval";
    //         //?parent=IEAEOPF5I4U765EL&title=".$projectNameExplode[0]."&titlePrefix=".$projectNameExplode[1]."&copyCustomFields=true&rescheduleMode=Start&rescheduleDate=$date";
    //         $data = array('approvers' => "[$fullOwnerId]", 'description' => "New Project Request $projectType", 'dueDate' => "$dueDate");
    //         // $data = array('approvers' => "['KUAL4N7R']", 'description' => "New Project Request $projectType", 'dueDate' => "$dueDate");
    //         $postdata = json_encode($data);
    //         $ch = curl_init();
    //         curl_setopt($ch, CURLOPT_URL, $url);
    //         curl_setopt($ch, CURLOPT_POST, 1);
    //         curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //         curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
    //         $result = curl_exec($ch);
    //         curl_close($ch);


    //         $conditionStatus4 = "project_id = '$projectId'";
    //         $updateStatus4 = sprintf("`status`= 4");
    //         $resStatus4 = $DBWR->update_data($tbl_initial_project, $updateStatus4, $conditionStatus4);
    //         $infoOutput .= 'tabel : ' . $tbl_initial_project . ' update : ' . $updateStatus4 . ' condition : ' . $conditionStatus4 . '</br>';

    //         '';

    //         '';

    //         //INSERT LOG CREATE APPROVAL
    //         $tbl_sa_log_activity = 'log_activity';
    //         $insertLogCreateApproval = sprintf(
    //             "(`activity`) VALUES ('Created approval from $projectId - $projectCode - $projectType to $fullOwnerId')",
    //             GetSQLValueString($projectCode, "text"),
    //             GetSQLValueString($projectId, "text"),
    //             GetSQLValueString($projectType, "text"),
    //             GetSQLValueString($fullOwnerId, "text")
    //         );

    //         $resLogCreateApproval = $DBWR->insert_data($tbl_sa_log_activity, $insertLogCreateApproval);
    //         $infoOutput .= 'tabel : ' . $tbl_sa_log_activity . ' insert : ' . $insertLogCreateApproval . '</br>';
    //     // }
    // } while ($rowDataApproval = $resDataApproval->fetch_assoc());

    // $tbl_initial_project = 'initial_project';
    // // $dataGetApproval = $DBWR->get_data($tbl_initial_project,);
    // $dataGetApproval = $DBWR->get_sql("SELECT * FROM sa_initial_project WHERE approval_status !='Approved' AND status=4 AND project_code='$ProjectCode'");
    // $rowGetApproval = $dataGetApproval[0];
    // $resGetApproval = $dataGetApproval[1];
    // $totalRowGetApproval = $dataGetApproval[2];

    // do {
    //     $projectId = $rowGetApproval['project_id'];
    //     $projectCode = $rowGetApproval['project_code'];
    //     $approvalStatus = $rowGetApproval['approval_status'];
    //     $projectType = $rowGetApproval['project_type'];
    //     $status = $rowGetApproval['status'];
    //     // '';

    //     // if ($approvalStatus != 'Approved' && $status == 4) {
    //         $authorization = "Authorization: Bearer eyJ0dCI6InAiLCJhbGciOiJIUzI1NiIsInR2IjoiMSJ9.eyJkIjoie1wiYVwiOjQ2Njg2MDUsXCJpXCI6OTIzOTA4MSxcImNcIjo0NjcxMzc3LFwidVwiOjE2NDc3MzM1LFwiclwiOlwiVVNcIixcInNcIjpbXCJXXCIsXCJGXCIsXCJJXCIsXCJVXCIsXCJLXCIsXCJDXCIsXCJEXCIsXCJNXCIsXCJBXCIsXCJMXCIsXCJQXCJdLFwielwiOltdLFwidFwiOjB9IiwiaWF0IjoxNzMxNDg0MDI4fQ.Ggy6YbLvLiIr_Yrfnyfy73yY8g1ts8bRRkrhpqvVV04";
    //         $curl = curl_init();

    //         curl_setopt($curl, CURLOPT_URL, "https://www.wrike.com/api/v4/folders/$projectId/approvals");
    //         curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
    //         curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //         $result = curl_exec($curl);
    //         curl_close($curl);

    //         $result2 = json_decode($result, true);

    //         $resultApproval = $result2['data'];

    //         for ($i = 0; $i < count($resultApproval); $i++) {
    //             $statusApproval = $resultApproval[$i]['status'];
    //             // '';

    //             if ($statusApproval == 'Approved') {
    //                 $conditionStatus5 = "project_id = '$projectId'";
    //                 $updateStatus5 = sprintf("`approval_status` = 'Approved', `status`= 5");
    //                 $resStatus5 = $DBWR->update_data($tbl_initial_project, $updateStatus5, $conditionStatus5);

    //                 //INSERT GET APPROVAL
    //                 $tbl_sa_log_activity = 'log_activity';
    //                 $insertLogGetApproval = sprintf(
    //                     "(`activity`) VALUES ('$projectId - $projectCode Approved')",
    //                     GetSQLValueString($projectCode, "text"),
    //                     GetSQLValueString($projectId, "text")
    //                 );

    //                 $resLogGetApproval = $DBWR->insert_data($tbl_sa_log_activity, $insertLogGetApproval);
    //             } else if ($statusApproval == 'Rejected') {
    //                 $conditionStatus5 = "project_id = '$projectId'";
    //                 $updateStatus5 = sprintf("`approval_status` = '$statusApproval', `status` = 5");
    //                 $resStatus5 = $DBWR->update_data($tbl_initial_project, $updateStatus5, $conditionStatus5);

    //                 //INSERT GET APPROVAL
    //                 $tbl_sa_log_activity = 'log_activity';
    //                 $insertLogGetApproval = sprintf(
    //                     "(`activity`) VALUES ('$projectId - $projectCode Rejected')",
    //                     GetSQLValueString($projectCode, "text"),
    //                     GetSQLValueString($projectId, "text")
    //                 );

    //                 $resLogGetApproval = $DBWR->insert_data($tbl_sa_log_activity, $insertLogGetApproval);
    //             } else {
    //                 $conditionStatus4 = "project_id = '$projectId'";
    //                 $updateStatus4 = sprintf("`approval_status` = '$statusApproval'");
    //                 $resStatus4 = $DBWR->update_data($tbl_initial_project, $updateStatus4, $conditionStatus4);
    //                 '';

    //                 //INSERT GET APPROVAL
    //                 $tbl_sa_log_activity = 'log_activity';
    //                 $insertLogGetApproval = sprintf(
    //                     "(`activity`) VALUES ('$projectId - $projectCode Pending')",
    //                     GetSQLValueString($projectCode, "text"),
    //                     GetSQLValueString($projectId, "text")
    //                 );

    //                 $resLogGetApproval = $DBWR->insert_data($tbl_sa_log_activity, $insertLogGetApproval);
    //             }
    //         }
    //     // } 
    // } while ($rowGetApproval = $resGetApproval->fetch_assoc());
    $from = $_SESSION['Microservices_UserEmail'];
    $to = $owner_wrike;
    $cc = $from;
    $bcc = "";
    $reply = $from;
    $subject = "[Wrike] Notifikasi Assignment Untuk Project KP $ProjectCode";
    $msg = "<table width='100%'";
    $msg .= "<tr><td width='30%' rowspan='5'></td><td style='width:40%; border: thin solid #dadada; padding:20px'>";
    $msg .= "<img src='https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png'>";
    $msg .= "</td></tr>";
    $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'>";
    $msg .= "<br/>";
    $msg .= "<p>Dear $owner_wrike ,</p>";
    $msg .= "<p>Dengan ini memberikan informasi bahwa Project KP $ProjectCode sudah ada di Wrike dan dapat diganti statusnya menjadi Open dan dibuatkan Project Charternya.</p>";
    $msg .= "<p>";
    $msg .= "</p>";
    $msg .= "<p>Demikian informasi ini disampaikan untuk selengkapnya dapat dilihat pada Wrike.</p>";
    $msg .= "<p>Terimakasih,<br/>";
    $msg .= "MSIZone Admin</p>";
    $msg .= "</td><td width='30%' rowspan='5'>";
    $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
    $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
    $msg .= "</table>";

    $headers = "From: " . $from . "\r\n" .
        "Reply-To: " . $reply . "\r\n" .
        "Cc: " . $cc . "\r\n" .
        "Bcc: " . $bcc . "\r\n" .
        "MIME-Version: 1.0" . "\r\n" .
        "Content-Type: text/html; charset=UTF-8" . "\r\n" .
        "X-Mailer: PHP/" . phpversion();

    $ALERT = new Alert();
    if (!mail($to, $subject, $msg, $headers)) {
        echo $ALERT->email_not_send();
    } else {
        echo $ALERT->email_send();
    }
}

    ?>

<?php
// module update
$title = "Service Budget Saving";
$author = 'Syamsul Arham';
$type = "function"; // module (&mod), submodule (&sub)
$revisionType = 'control'; // major, minor, control
$dashboardEnable = false;
$moduleDesc = "This module is used to save the Service Budget created to the database.";
$revision_msg = "Update user Sylvia team for Service Budget";
$showFooter = false;

$ClassVersion->show_footer(__FILE__, $title, $moduleDesc, $type, $revisionType, $dashboardEnable, $author, $revision_msg, $showFooter);
?>
