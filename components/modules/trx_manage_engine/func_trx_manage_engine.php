<?php
global $DBManageEngine;
if (isset($_POST['add'])) {
    $get_customer = $DBManageEngine->get_sqlV2("SELECT id_customer FROM sa_trx_customer ORDER BY id_customer DESC LIMIT 1");
    if (!empty($get_customer)) {
        $last_id = $get_customer[0]['id_customer'];
    } else {
        $last_id = 0;
    }
    $incremented_id = $last_id + 1;

    // ini untuk trx_detail
    $insert = sprintf(
        "(`id_request`,`request`,`subject`) VALUES (%s,%s,%s)",
        GetSQLValueString($_POST['id_request'], "int"),
        GetSQLValueString($_POST['request'], "int"),
        GetSQLValueString($_POST['subject'], "text")
    );
    $res = $DBManageEngine->insert_data('trx_details', $insert);
    $ALERT->savedata();

    // ini untuk trx_customer
    $insert = sprintf(
        "(`id_company`,`company_name`,`company_branch`,`address`) VALUES (%s,%s,%s,%s)",
        GetSQLValueString($incremented_id, "int"),
        GetSQLValueString($_POST['company_name'], "text"),
        GetSQLValueString($_POST['company_branch'], "text"),
        GetSQLValueString($_POST['address'], "text")
    );
    $res = $DBManageEngine->insert_data('trx_customer', $insert);
    $ALERT->savedata();

    // ini untuk pic_customer
    $insert = sprintf(
        "(`id_company`,`pic`,`pic_phone`,`pic_email`) VALUES (%s,%s,%s,%s)",
        GetSQLValueString($incremented_id, "int"),
        GetSQLValueString($_POST['user'], "text"),
        GetSQLValueString($_POST['user_phone'], "int"),
        GetSQLValueString($_POST['email'], "text")
    );
    $res = $DBManageEngine->insert_data('pic_customer', $insert);
    $ALERT->savedata();
}
// elseif (isset($_POST['save'])) {
//     $condition = "id_manage_engine=" . $_POST['id_manage_engine'];
//     $update = sprintf(
//         "`id_manage_engine`=%s,`id_request`=%s,`request`=%s,`subject`=%s,`request_by`=%s,`request_date`=%s,`status`=%s",
//         GetSQLValueString($_POST['id_manage_engine'], "int"),
//         GetSQLValueString($_POST['id_request'], "text"),
//         GetSQLValueString($_POST['request'], "int"),
//         GetSQLValueString($_POST['subject'], "text"),
//         GetSQLValueString($_POST['request_by'], "text"),
//         GetSQLValueString($_POST['request_date'], "date"),
//         GetSQLValueString($_POST['status'], "text")
//     );
//     $res = $DBManageEngine->update_data($tblname, $update, $condition);
//     $ALERT->savedata();
// }
elseif (isset($_POST['save'])) {
    // Mengubah format datetime untuk nilai yang diterima
    $created_date_raw = $_POST['created_date'];
    $due_by_date_raw = $_POST['due_by_date'];
    $response_due_date_raw = $_POST['response_due_date'];
    $responded_time_raw = $_POST['responded_time'];
    $completed_time_raw = $_POST['completed_time'];

    $created_date = DateTime::createFromFormat('M j, Y h:i A', $created_date_raw);
    $due_by_date = DateTime::createFromFormat('M j, Y h:i A', $due_by_date_raw);
    $response_due_date = DateTime::createFromFormat('M j, Y h:i A', $response_due_date_raw);
    $responded_time = DateTime::createFromFormat('M j, Y h:i A', $responded_time_raw);
    $completed_time = DateTime::createFromFormat('M j, Y h:i A', $completed_time_raw);

    // Memastikan format datetime valid atau mengatur ke NULL jika tidak valid
    $created_date = $created_date ? $created_date->format('Y-m-d H:i:s') : "0000-00-00 00:00:00";
    $due_by_date = $due_by_date ? $due_by_date->format('Y-m-d H:i:s') : "0000-00-00 00:00:00";
    $response_due_date = $response_due_date ? $response_due_date->format('Y-m-d H:i:s') : "0000-00-00 00:00:00";
    $responded_time = $responded_time ? $responded_time->format('Y-m-d H:i:s') : "0000-00-00 00:00:00";
    $completed_time = $completed_time ? $completed_time->format('Y-m-d H:i:s') : "0000-00-00 00:00:00";

    $insert = sprintf(
        "(`request`, `request_name`, `assets`, `company_name`, `company_branch`, `address`, `user`, `user_phone`, `email`, `product_name`, `product_type`, `serial_number`, `category`, `sub_category`, `item`, `request_type`, `service_category`, `level`, `priority`, `impact`, `urgency`, `status`, `mode`, `site`, `group`, `technician`, `project_manager`, `created_date`, `due_by_date`, `response_due_date`, `detail_kerusakan`, `responded_time`, `completed_time`, `tindakan`, `created_by`, `template`, `closure_code`, `department`, `sla`, `notes`) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
        GetSQLValueString($_POST['request'], "text"),
        GetSQLValueString($_POST['request_name'], "text"),
        GetSQLValueString($_POST['assets'], "text"),
        GetSQLValueString($_POST['company_name'], "text"),
        GetSQLValueString($_POST['company_branch'], "text"),
        GetSQLValueString($_POST['address'], "text"),
        GetSQLValueString($_POST['user'], "text"),
        GetSQLValueString($_POST['user_phone'], "int"),
        GetSQLValueString($_POST['email'], "text"),
        GetSQLValueString($_POST['product_name'], "text"),
        GetSQLValueString($_POST['product_type'], "text"),
        GetSQLValueString($_POST['serial_number'], "text"),
        GetSQLValueString($_POST['category'], "text"),
        GetSQLValueString($_POST['sub_category'], "text"),
        GetSQLValueString($_POST['item'], "text"),
        GetSQLValueString($_POST['request_type'], "text"),
        GetSQLValueString($_POST['service_category'], "text"),
        GetSQLValueString($_POST['level'], "text"),
        GetSQLValueString($_POST['priority'], "text"),
        GetSQLValueString($_POST['impact'], "text"),
        GetSQLValueString($_POST['urgency'], "text"),
        GetSQLValueString($_POST['status'], "text"),
        GetSQLValueString($_POST['mode'], "text"),
        GetSQLValueString($_POST['site'], "text"),
        GetSQLValueString($_POST['group'], "text"),
        GetSQLValueString($_POST['technician'], "text"),
        GetSQLValueString($_POST['project_manager'], "text"),
        GetSQLValueString($created_date, "datetime"),
        GetSQLValueString($due_by_date, "datetime"),
        GetSQLValueString($response_due_date, "datetime"),
        GetSQLValueString($_POST['detail_kerusakan'], "text"),
        GetSQLValueString($responded_time, "datetime"),
        GetSQLValueString($completed_time, "datetime"),
        GetSQLValueString($_POST['tindakan'], "text"),
        GetSQLValueString($_POST['created_by'], "text"),
        GetSQLValueString($_POST['template'], "text"),
        GetSQLValueString($_POST['closure_code'], "text"),
        // GetSQLValueString($_POST['time_elapsed'], "text"),
        GetSQLValueString($_POST['department'], "text"),
        GetSQLValueString($_POST['sla'], "text"),
        GetSQLValueString($_POST['notes'], "text")
    );
    $res = $DBManageEngine->insert_data($tblname, $insert);
    $ALERT->savedata();
}
