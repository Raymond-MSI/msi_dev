<?php

function getReportChangeRequest($filters)
{

    $db_name = "change_request";
    $DBRCR = get_conn($db_name);
    if (!$DBRCR) {
        die("Database connection not initialized.");
    }
    $query = " SELECT DISTINCT
        sa.gi_id,
        sa.project_code,
        sa.project_name,
        sa.cr_no,
        sa.type_of_service,
        sa.so_number,
        sa.order_number,
        sa.type_of_service,
        sa.requested_by_email,
        scp.used_ticket,
        scp.ticket_allocation,
        scp.ticket_allocation_sisa,
        sa.request_date,
        sa.modified_date
    FROM sa_general_informations sa
    LEFT JOIN sa_change_cost_plans scp
        ON sa.cr_no = scp.cr_no 
        ";
    if (!empty($filters['change_request_approval_type2'])) {
        $query .= " WHERE sa.change_request_approval_type2 = '" . $filters['change_request_approval_type2'] . "'";
    }
    $result = $DBRCR->get_sql($query);

    

    return $result;
}
