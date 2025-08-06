<?php
class ServiceBudget extends Databases {
    function insert_sb($index=0) {
        global $DTSB;
        $tblname = "trx_project_list";
        $mysql = sprintf("(`project_code`, `project_name`, `project_name_internal`, `customer_code`, `customer_name`, `po_number`, `po_date`, `order_number`, `so_number`, `so_date`, `status_so`, `amount_idr`, `amount_usd`, `sales_code`, `sales_name`, `duration`, `contract_type`, `wo_type`, `multiyears`, `version`, `sbtype`, `newproject`, `bundling`, `backup`, `band`, `status`, `modified_by`, `create_by`, `create_date`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
            GetSQLValueString($_POST['project_code'][$index], "text"),
            GetSQLValueString($_POST['project_name'][$index], "text"),
            GetSQLValueString($_POST['project_name_internal'][$index], "text"),
            GetSQLValueString($_POST['customer_code'][$index], "text"),
            GetSQLValueString($_POST['customer_name'][$index], "text"),
            GetSQLValueString($_POST['po_number'][$index], "text"),
            GetSQLValueString(date("Y-m-d G:i:s", strtotime($_POST['po_date'][$index])), "date"),
            GetSQLValueString($_POST['order_number'][$index], "text"),
            GetSQLValueString($_POST['so_number'][$index], "text"),
            GetSQLValueString(date("Y-m-d G:i:s", strtotime($_POST['so_date'][$index])), "date"),
            GetSQLValueString($_POST['status_so'][$index], "text"),
            GetSQLValueString(deformat($_POST['amount_idr'][$index]), "double"),
            GetSQLValueString(deformat($_POST['amount_usd'][$index]), "double"),
            GetSQLValueString($_POST['sales_code'][$index], "text"),
            GetSQLValueString($_POST['sales_name'][$index], "text"),
            GetSQLValueString($_POST['duration'][$index], "int"),
            GetSQLValueString($_POST['contract_type'][$index], "text"),
            GetSQLValueString($_POST['wo_type'][$index], "text"),
            GetSQLValueString($_POST['multiyears'][$index], "int"),
            GetSQLValueString($_POST['version'][$index], "int"),
            GetSQLValueString($_POST['sbtype'][$index], "int"),
            GetSQLValueString($_POST['newproject'][$index], "int"),
            GetSQLValueString($_POST['bundling'][$index], "text"),
            GetSQLValueString($_POST['backup'][$index], "text"),
            GetSQLValueString($_POST['band'][$index], "int"),
            GetSQLValueString($_POST['status'][$index], "text"),
            GetSQLValueString(date("Y-m-d G:i:s", strtotime($_POST['modified_by'][$index])), "date"),
            GetSQLValueString($_POST['create_by'][$index], "text"),
            GetSQLValueString(date("Y-m-d G:i:s", strtotime($_POST['create_date'][$index])), "date")
        );
        $res = $DTSB->insert_data($tblname, $mysql);
    }

    function update_sb($index=0) {
        global $DTSB;
        $tblname = "trx_project_list";
        $condition = "project_id = " . $_POST['project_id'][$index];
        $mysql = sprintf("`project_code`=%s, `project_name`=%s, `project_name_internal`=%s, `customer_code`=%s, `customer_name`=%s, `po_number`=%s, `po_date`=%s, `order_number`=%s, `so_number`=%s, `so_date`=%s, `status_so`=%s, `amount_idr`=%s, `amount_usd`=%s, `sales_code`=%s, `sales_name`=%s, `duration`=%s, `contract_type`=%s, `wo_type`=%s, `multiyears`=%s, `version`=%s, `sbtype`=%s, `newproject`=%s, `bundling`=%s, `backup`=%s, `band`=%s, `status`=%s, `modified_by`=%s, `create_by`=%s, `create_date`=%s",
            GetSQLValueString($_POST['project_code'][$index], "text"),
            GetSQLValueString($_POST['project_name'][$index], "text"),
            GetSQLValueString($_POST['project_name_internal'][$index], "text"),
            GetSQLValueString($_POST['customer_code'][$index], "text"),
            GetSQLValueString($_POST['customer_name'][$index], "text"),
            GetSQLValueString($_POST['po_number'][$index], "text"),
            GetSQLValueString(date("Y-m-d G:i:s", strtotime($_POST['po_date'][$index])), "date"),
            GetSQLValueString($_POST['order_number'][$index], "text"),
            GetSQLValueString($_POST['so_number'][$index], "text"),
            GetSQLValueString(date("Y-m-d G:i:s", strtotime($_POST['so_date'][$index])), "date"),
            GetSQLValueString($_POST['status_so'][$index], "text"),
            GetSQLValueString(deformat($_POST['amount_idr'][$index]), "double"),
            GetSQLValueString(deformat($_POST['amount_usd'][$index]), "double"),
            GetSQLValueString($_POST['sales_code'][$index], "text"),
            GetSQLValueString($_POST['sales_name'][$index], "text"),
            GetSQLValueString($_POST['duration'][$index], "int"),
            GetSQLValueString($_POST['contract_type'][$index], "text"),
            GetSQLValueString($_POST['wo_type'][$index], "text"),
            GetSQLValueString($_POST['multiyears'][$index], "int"),
            GetSQLValueString($_POST['version'][$index], "int"),
            GetSQLValueString($_POST['sbtype'][$index], "int"),
            GetSQLValueString($_POST['newproject'][$index], "int"),
            GetSQLValueString($_POST['bundling'][$index], "text"),
            GetSQLValueString($_POST['backup'][$index], "text"),
            GetSQLValueString($_POST['band'][$index], "int"),
            GetSQLValueString($_POST['status'][$index], "text"),
            GetSQLValueString(date("Y-m-d G:i:s", strtotime($_POST['modified_by'][$index])), "date"),
            GetSQLValueString($_POST['create_by'][$index], "text"),
            GetSQLValueString(date("Y-m-d G:i:s", strtotime($_POST['create_date'][$index])), "date")
        );
        $res = $DTSB->update_data($tblname, $mysql, $condition);
    }

    function insert_solution($index=0) {
        global $DTSB;
        $tblname ="trx_project_solutions";
        $mysql = sprintf("(`project_id`, `solution_name`, `product`, `services`) VALUES (%s,%s,%s,%s), (%s,%s,%s,%s), (%s,%s,%s,%s), (%s,%s,%s,%s), (%s,%s,%s,%s), (%s,%s,%s,%s)",
        GetSQLValueString($_POST['project_id'][$index], "int"),
        GetSQLValueString("DCCI", "text"),
        GetSQLValueString($_POST['DCCIP'][$index], "text"),
        GetSQLValueString($_POST['DCCIS'][$index], "text"),
        GetSQLValueString($_POST['project_id'][$index], "int"),
        GetSQLValueString("EC", "text"),
        GetSQLValueString($_POST['ECP'][$index], "text"),
        GetSQLValueString($_POST['ECS'][$index], "text"),
        GetSQLValueString($_POST['project_id'][$index], "int"),
        GetSQLValueString("BDA", "text"),
        GetSQLValueString($_POST['BDAP'][$index], "text"),
        GetSQLValueString($_POST['BDAS'][$index], "text"),
        GetSQLValueString($_POST['project_id'][$index], "int"),
        GetSQLValueString("DBM", "text"),
        GetSQLValueString($_POST['DBMP'][$index], "text"),
        GetSQLValueString($_POST['DBMS'][$index], "text"),
        GetSQLValueString($_POST['project_id'][$index], "int"),
        GetSQLValueString("ASA", "text"),
        GetSQLValueString($_POST['ASAP'][$index], "text"),
        GetSQLValueString($_POST['ASAS'][$index], "text"),
        GetSQLValueString($_POST['project_id'][$index], "int"),
        GetSQLValueString("SP", "text"),
        GetSQLValueString($_POST['SPP'][$index], "text"),
        GetSQLValueString($_POST['SPS'][$index], "text")
        );
        $res = $DTSB->insert_data($tblname, $mysql);
    }

    function update_solution($index=0) {
        global $DTSB;
        $tblname ="trx_project_solutions";
        
        $condition = "project_id=" . $_POST['project_id'][$index] . " AND solution_name='DCCI'";
        $mysql = sprintf("`product`=%s, `services`=%",
            GetSQLValueString($_POST['DCCIP'][$index], "text"),
            GetSQLValueString($_POST['DCCIS'][$index], "text")
        );
        $res = $DTSB->update_data($tblname, $mysql, $condition);

        $condition = "project_id=" . $_POST['project_id'][$index] . " AND solution_name='EC'";
        $mysql = sprintf("`product`=%s, `services`=%",
            GetSQLValueString($_POST['ECP'][$index], "text"),
            GetSQLValueString($_POST['ECS'][$index], "text")
        );
        $res = $DTSB->update_data($tblname, $mysql, $condition);

        $condition = "project_id=" . $_POST['project_id'][$index] . " AND solution_name='BDA'";
        $mysql = sprintf("`product`=%s, `services`=%",
            GetSQLValueString($_POST['BDAP'][$index], "text"),
            GetSQLValueString($_POST['BDAS'][$index], "text")
        );
        $res = $DTSB->update_data($tblname, $mysql, $condition);

        $condition = "project_id=" . $_POST['project_id'][$index] . " AND solution_name='DBM'";
        $mysql = sprintf("`product`=%s, `services`=%",
            GetSQLValueString($_POST['DBMP'][$index], "text"),
            GetSQLValueString($_POST['DBMS'][$index], "text")
        );
        $res = $DTSB->update_data($tblname, $mysql, $condition);

        $condition = "project_id=" . $_POST['project_id'][$index] . " AND solution_name='ASA'";
        $mysql = sprintf("`product`=%s, `services`=%",
            GetSQLValueString($_POST['ASAP'][$index], "text"),
            GetSQLValueString($_POST['ASAS'][$index], "text")
        );
        $res = $DTSB->update_data($tblname, $mysql, $condition);

        $condition = "project_id=" . $_POST['project_id'][$index] . " AND solution_name='SP'";
        $mysql = sprintf("`product`=%s, `services`=%",
            GetSQLValueString($_POST['SPP'][$index], "text"),
            GetSQLValueString($_POST['SPS'][$index], "text")
        );
        $res = $DTSB->update_data($tblname, $mysql, $condition);
    }

    function insert_implementation($index=0) {
        global $DTSB;
        $tblname = "trx_project_implementations";
        $mysql = sprintf("(`project_id`, `project_estimation`, `project_estimation_id`, `implementation_price`, `agreed_price`, `tos_id`, `tos_category_id`, `bpd_total_location`, `bpd_description`, `bpd_price`, `out_description`, `out_price`, `service_type`, `maintenance_package_description`, `maintenance_package_price`, `maintenance_addon_description`, `maintenance_addon_price`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
            GetSQLValueString($_POST['project_id'][$index], "int"),
            GetSQLValueString($_POST['project_estimation'][$index], "int"),
            GetSQLValueString($_POST['project_estimation_id'][$index], "int"),
            GetSQLValueString($_POST['implementation_price'][$index], "double"),
            GetSQLValueString($_POST['agreed_price'][$index], "double"),
            GetSQLValueString($_POST['tos_id'][$index], "text"),
            GetSQLValueString($_POST['tos_category_id'][$index], "int"),
            GetSQLValueString($_POST['bpd_total_location'][$index], "int"),
            GetSQLValueString($_POST['bpd_description'][$index], "text"),
            GetSQLValueString($_POST['bpd_price'][$index], "double"),
            GetSQLValueString($_POST['out_description'][$index], "text"),
            GetSQLValueString($_POST['out_price'][$index], "double"),
            GetSQLValueString($_POST['service_type'][$index], "int"),
            GetSQLValueString($_POST['maintenance_package_description'][$index], "text"),
            GetSQLValueString($_POST['maintenance_package_price'][$index], "double"),
            GetSQLValueString($_POST['maintenance_addon_description'][$index], "text"),
            GetSQLValueString($_POST['maintenance_addon_price'][$index], "double")
        );
        $res = $DTSB->insert_data($tblname, $mysql);
    }

    function update_implementation($index=0) {
        global $DTSB;
        $tblname = "trx_project_implementations";
        $condition = "project_id = " . $_POST['project_id'][$index];
        $mysql = sprintf("`project_estimation`=%s, `project_estimation_id`=%s, `implementation_price`=%s, `agreed_price`=%s, `tos_id`=%s, `tos_category_id`=%s, `bpd_total_location`=%s, `bpd_description`=%s, `bpd_price`=%s, `out_description`=%s, `out_price`=%s, `service_type`=%s, `maintenance_package_description`=%s, `maintenance_package_price`=%s, `maintenance_addon_description`=%s, `maintenance_addon_price`=%s",
            GetSQLValueString($_POST['project_estimation'][$index], "int"),
            GetSQLValueString($_POST['project_estimation_id'][$index], "int"),
            GetSQLValueString($_POST['implementation_price'][$index], "double"),
            GetSQLValueString($_POST['agreed_price'][$index], "double"),
            GetSQLValueString($_POST['tos_id'][$index], "text"),
            GetSQLValueString($_POST['tos_category_id'][$index], "int"),
            GetSQLValueString($_POST['bpd_total_location'][$index], "int"),
            GetSQLValueString($_POST['bpd_description'][$index], "text"),
            GetSQLValueString($_POST['bpd_price'][$index], "double"),
            GetSQLValueString($_POST['out_description'][$index], "text"),
            GetSQLValueString($_POST['out_price'][$index], "double"),
            GetSQLValueString($_POST['service_type'][$index], "int"),
            GetSQLValueString($_POST['maintenance_package_description'][$index], "text"),
            GetSQLValueString($_POST['maintenance_package_price'][$index], "double"),
            GetSQLValueString($_POST['maintenance_addon_description'][$index], "text"),
            GetSQLValueString($_POST['maintenance_addon_price'][$index], "double")
        );
        $res = $DTSB->update_data($tblname, $mysql, $condition);
    }

    function insert_mandays($index=0) {
        global $DTSB;
        $tblname = "trx_project_mandays";
        $mysql = sprintf("(`project_id`, `resource_level`, `resource_catalog_id`, `mantotal`, `mandays`, `brand`, `value`, `service_type`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,)",
            GetSQLValueString($_POST['project_id'][$index], "int"),
            GetSQLValueString($_POST['resource_level'][$index], "int"),
            GetSQLValueString($_POST['resource_catalog_id'][$index], "int"),
            GetSQLValueString($_POST['mantotal'][$index], "int"),
            GetSQLValueString($_POST['mandays'][$index], "double"),
            GetSQLValueString($_POST['brand'][$index], "text"),
            GetSQLValueString($_POST['value'][$index], "double"),
            GetSQLValueString($_POST['service_type'][$index], "int")
        );
        $res = $DTSB->insert_data($tblname, $mysql);
    }

    function update_mandays($index=0) {
        global $DTSB;
        $tblname = "trx_project_mandays";
        $condition = "project_id = " . $_POST['project_id'];
        $mysql = sprintf("`resource_level`=%s, `resource_catalog_id`=%s, `mantotal`=%s, `mandays`=%s, `brand`=%s, `value`=%s, `service_type`=%s",
            GetSQLValueString($_POST['resource_level'][$index], "int"),
            GetSQLValueString($_POST['resource_catalog_id'][$index], "int"),
            GetSQLValueString($_POST['mantotal'][$index], "int"),
            GetSQLValueString($_POST['mandays'][$index], "double"),
            GetSQLValueString($_POST['brand'][$index], "text"),
            GetSQLValueString($_POST['value'][$index], "double"),
            GetSQLValueString($_POST['service_type'][$index], "int")
        );
        $res = $DTSB->update_data($tblname, $mysql, $condition);
    }

    function insert_addon($index=0) {
        global $DTSB;
        $tblname = "trx_project_mandays";
        $mysql = sprintf("(`project_id`, `addon_title`, `addon_price`, `service_type`) VALUES (%s,%s,%s,%s,)",
            GetSQLValueString($_POST['project_id'][$index], "int"),
            GetSQLValueString($_POST['addon_title'][$index], "text"),
            GetSQLValueString($_POST['addon_price'][$index], "double"),
            GetSQLValueString($_POST['service_type'][$index], "int")
        );
        $res = $DTSB->insert_data($tblname, $mysql);
    }

    function update_addon($index=0) {
        global $DTSB;
        $tblname = "trx_project_mandays";
        $condition = "project_id = " . $_POST['project_id'];
        $mysql = sprintf("`addon_title`=%s, `addon_price`=%s, `service_type`=%s",
            GetSQLValueString($_POST['addon_title'][$index], "text"),
            GetSQLValueString($_POST['addon_price'][$index], "double"),
            GetSQLValueString($_POST['service_type'][$index], "int")
        );
        $res = $DTSB->update_data($tblname, $mysql, $condition);
    }

}
?>