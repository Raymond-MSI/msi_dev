<?php
function getReportAcknowledgeServiceBudget()
{
    $db_name = "service_budget";
    $DBRSB = get_conn($db_name);
    if (!$DBRSB) {
        error_log("Failed to connect to the database: " . $db_name);
        return [];
    }
    $query  = "
    SELECT * FROM sa_ps_service_budgets.sa_trx_project_list st
        WHERE st.status = 'acknowledge'
        AND (st.po_number = '' OR st.amount_idr = '' OR st.so_number ='')
        AND st.po_date >= NOW() - INTERVAL 6 MONTH
        ORDER BY st.project_id DESC;
    ";

    $result = $DBRSB->get_sql($query);
    return $result;
}
function convertBundling($data)
{
    if (empty($data)) {
        return 'No Data';
    }
    $array_data = explode(';', $data);
    $hasil = [];

    foreach ($array_data as $value) {
        $nilai = (int) $value;
        if ($nilai === 1) {
            $hasil[] = 'Implementation';
        } elseif ($nilai === 2) {
            $hasil[] = 'Maintenance';
        } elseif ($nilai === 3) {
            $hasil[] = 'Warranty';
        } elseif ($nilai === 4) {
            $hasil[] = 'Manage Service';
        }
    }
    if (empty($hasil)) {
        return 'No Data';
    } else {
        return implode(' ', $hasil);
    }
}
