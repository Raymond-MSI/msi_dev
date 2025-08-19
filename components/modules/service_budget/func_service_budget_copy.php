<?php
if (isset($_POST['save_copy_service_budget'])) {
    for ($i = 0; $i < count($_POST['project_code']); $i++) {
        if (isset($_POST['check'][$i])) {

            $tblname = "trx_project_list";
            // $condition = "project_code = '" . $_POST['project_code'] . "' AND order_number = '" . $_POST['order_number'] . "' AND status !='deleted'";
            $condition = "order_number = '" . $_POST['org_order_number'] . "' AND status !='deleted'";
            $orgSB = $DTSB->get_data($tblname, $condition);

            $tblname = "trx_project_list";
            $mysql = sprintf(
                "(`project_code`, `project_name`, `project_name_internal`, `customer_code`, `customer_name`, `po_number`, `po_date`, `order_number`, `so_number`, `amount_idr`, `amount_usd`, `sales_code`, `sales_name`, `duration`, `contract_type`, `wo_type`, `multiyears`, `version`, `sbtype`, `newproject`, `bundling`, `backup`, `band`, `status`, `modified_by`, `create_by`, `create_date`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
                GetSQLValueString($orgSB[0]['project_code'], "text"),
                GetSQLValueString($_POST["project_name"][$i], "text"),
                GetSQLValueString($orgSB[0]["project_name_internal"], "text"),
                GetSQLValueString($orgSB[0]['customer_code'], "text"),
                GetSQLValueString($orgSB[0]['customer_name'], "text"),
                GetSQLValueString($_POST["po_number"][$i], "text"),
                GetSQLValueString(date("Y-m-d G:i:s", strtotime($orgSB[0]["po_date"])), "date"),
                GetSQLValueString($_POST["order_number"][$i], "text"),
                GetSQLValueString($_POST["so_number"][$i], "text"),
                GetSQLValueString($_POST["amount_idr"][$i], "double"),
                GetSQLValueString($_POST["amount_usd"][$i], "double"),
                GetSQLValueString($orgSB[0]["sales_code"], "text"),
                GetSQLValueString($orgSB[0]["sales_name"], "text"),
                GetSQLValueString($orgSB[0]["duration"], "int"),
                GetSQLValueString($orgSB[0]["contract_type"], "text"),
                GetSQLValueString($orgSB[0]["wo_type"], "text"),
                GetSQLValueString($orgSB[0]["multiyears"], "int"),
                GetSQLValueString(1, "int"),
                GetSQLValueString($orgSB[0]["sbtype"], "int"),
                GetSQLValueString($orgSB[0]["newproject"], "int"),
                GetSQLValueString($orgSB[0]["bundling"], "text"),
                GetSQLValueString($orgSB[0]["backup"], "text"),
                GetSQLValueString($orgSB[0]["band"], "int"),
                GetSQLValueString($orgSB[0]["status"], "text"),
                GetSQLValueString($_SESSION['Microservices_UserEmail'], "text"),
                GetSQLValueString($_SESSION['Microservices_UserEmail'], "text"),
                GetSQLValueString(date("Y-m-d G:i:s"), "date")
            );
            $res = $DTSB->insert_data($tblname, $mysql);

            $condition = "";
            $order = "project_id DESC";
            $newSB = $DTSB->get_data($tblname, $condition, $order, 0, 1);

            $tblname = "trx_project_solutions";
            $condition = "project_id = " . $orgSB[0]['project_id'];
            $orgData = $DTSB->get_data($tblname, $condition);
            if ($orgData[2]) {
                do {
                    $mysql = sprintf(
                        "(`project_id`, `solution_name`, `product`, `services`) VALUES (%s,%s,%s,%s)",
                        GetSQLValueString($newSB[0]['project_id'], "int"),
                        GetSQLValueString($orgData[0]['solution_name'], "text"),
                        GetSQLValueString($orgData[0]['product'], "int"),
                        GetSQLValueString($orgData[0]['services'], "int")
                    );
                    $res = $DTSB->insert_data($tblname, $mysql);
                } while ($orgData[0] = $orgData[1]->fetch_assoc());
            }

            $tblname = "trx_project_implementations";
            $condition = "project_id = " . $orgSB[0]['project_id'];
            $orgData = $DTSB->get_data($tblname, $condition);
            if ($orgData[2]) {
                do {
                    $mysql = sprintf(
                        "(`project_id`, `project_estimation`, `project_estimation_id`, `implementation_price`, `agreed_price`, `tos_id`, `tos_category_id`, `bpd_total_location`, `bpd_description`, `bpd_price`, `out_description`, `out_price`, `service_type`, `maintenance_package_description`, `maintenance_package_price`, `maintenance_addon_description`, `maintenance_addon_price`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
                        GetSQLValueString($newSB[0]['project_id'], "int"),
                        GetSQLValueString($orgData[0]['project_estimation'], "int"),
                        GetSQLValueString($orgData[0]['project_estimation_id'], "int"),
                        GetSQLValueString($orgData[0]['implementation_price'], "double"),
                        GetSQLValueString($orgData[0]['agreed_price'], "double"),
                        GetSQLValueString($orgData[0]['tos_id'], "text"),
                        GetSQLValueString($orgData[0]['tos_category_id'], "int"),
                        GetSQLValueString($orgData[0]['bpd_total_location'], "int"),
                        GetSQLValueString($orgData[0]['bpd_description'], "text"),
                        GetSQLValueString($orgData[0]['bpd_price'], "double"),
                        GetSQLValueString($orgData[0]['out_description'], "text"),
                        GetSQLValueString($orgData[0]['out_price'], "double"),
                        GetSQLValueString($orgData[0]['service_type'], "int"),
                        GetSQLValueString($orgData[0]['maintenance_package_description'], "text"),
                        GetSQLValueString($orgData[0]['maintenance_package_price'], "double"),
                        GetSQLValueString($orgData[0]['maintenance_addon_description'], "text"),
                        GetSQLValueString($orgData[0]['maintenance_addon_price'], "double")
                    );
                    $res = $DTSB->insert_data($tblname, $mysql);
                } while ($orgData[0] = $orgData[1]->fetch_assoc());
            }

            $tblname = "trx_project_mandays";
            $condition = "project_id = " . $orgSB[0]['project_id'];
            $orgData = $DTSB->get_data($tblname, $condition);
            if ($orgData[2]) {
                do {
                    $mysql = sprintf(
                        "(`project_id`, `resource_level`, `resource_catalog_id`, `mantotal`, `mandays`, `brand`, `value`, `service_type`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s)",
                        GetSQLValueString($newSB[0]['project_id'], "int"),
                        GetSQLValueString($orgData[0]['resource_level'], "int"),
                        GetSQLValueString($orgData[0]['resource_catalog_id'], "int"),
                        GetSQLValueString($orgData[0]['mantotal'], "int"),
                        GetSQLValueString($orgData[0]['mandays'], "double"),
                        GetSQLValueString($orgData[0]['brand'], "text"),
                        GetSQLValueString($orgData[0]['value'], "double"),
                        GetSQLValueString($orgData[0]['service_type'], "int")
                    );
                    $res = $DTSB->insert_data($tblname, $mysql);
                } while ($orgData[0] = $orgData[1]->fetch_assoc());
            }

            $tblname = "trx_addon";
            $condition = "project_id = " . $orgSB[0]['project_id'];
            $orgData = $DTSB->get_data($tblname, $condition);
            if ($orgData[2]) {
                do {
                    $mysql = sprintf(
                        "(`project_id`, `addon_title`, `addon_price`, `service_type`) VALUES (%s,%s,%s,%s)",
                        GetSQLValueString($newSB[0]['project_id'], "int"),
                        GetSQLValueString($orgData[0]['addon_title'], "text"),
                        GetSQLValueString($orgData[0]['addon_price'], "double"),
                        GetSQLValueString($orgData[0]['service_type'], "int")
                    );
                    $res = $DTSB->insert_data($tblname, $mysql);
                } while ($orgData[0] = $orgData[1]->fetch_assoc());
            }

            $tblname = "trx_approval";
            $mysql = sprintf(
                "(`approve_by`, `approve_note`,`approve_status`, `project_id`) VALUES (%s,%s,%s,%s)",
                GetSQLValueString($_SESSION['Microservices_UserEmail'], "text"),
                GetSQLValueString("Completed", "text"),
                GetSQLValueString($newSB[0]['status'], "text"),
                GetSQLValueString($newSB[0]['project_id'], "int")
            );
            $res = $DTSB->insert_data($tblname, $mysql);

            $tblname = "logs";
            $desc = "Copy to Order Number : " . $newSB[0]['order_number'];
            $mysql = sprintf(
                "(`project_id`, `description`, `entry_by`) VALUES (%s,%s,%s)",
                GetSQLValueString($orgSB[0]['project_id'], "text"),
                GetSQLValueString($desc, "text"),
                GetSQLValueString($_SESSION['Microservices_UserEmail'], "text")
            );
            $res = $DTSB->insert_data($tblname, $mysql);

            $desc = "Copy from Order Number : " . $orgSB[0]['order_number'];
            $mysql = sprintf(
                "(`project_id`, `description`, `entry_by`) VALUES (%s,%s,%s)",
                GetSQLValueString($newSB[0]['project_id'], "text"),
                GetSQLValueString($desc, "text"),
                GetSQLValueString($_SESSION['Microservices_UserEmail'], "text")
            );
            $res = $DTSB->insert_data($tblname, $mysql);

            $tblname = "mst_order_number";
            $condition = "project_code='" . $newSB[0]['project_code'] . "' AND order_number='" . $newSB[0]['order_number'] . "'";
            $updateorder = "status_order=1";
            $res = $DBNAV->update_data($tblname, $updateorder, $condition);
        }
    }
}
