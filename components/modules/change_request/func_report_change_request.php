<?php

function getReportChangeRequest()
{
    $db_name = "change_request";
    $DBRCR = get_conn($db_name);
    if (!$DBRCR) {
        error_log("Failed to connect to the database: " . $db_name);
        return []; // Return an empty array to prevent further errors
    }
    $query = "SELECT * FROM sa_general_informations sa
    LEFT JOIN sa_change_cost_plans scp
    ON sa.cr_no = scp.cr_no";
    $result = $DBRCR->get_sql($query);
    return $result;
}
