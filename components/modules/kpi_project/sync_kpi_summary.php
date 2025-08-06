<?php
$mulai_sync = date("d-M-Y G:i:s");
$header = "==========";
$header .= "Execution module : mod_wrike_integrate/form_wrike_integrate.";
$header .= "Started : " . $mulai_sync;
$header .= "==========\r\n";
echo $header . "<br/>";
$time_start = microtime(true);
$time_start1 = microtime(true);

$_SESSION['Microservices_UserEmail'] = 'syamsul@mastersystem.co.id';

// include_once("components/classes/func_crontab.php");
// include_once("components/classes/func_helper.php");
// $DBCRON = get_conn("CRONTAB");
// $log_id = $DBCRON->beginning();

$DBKPI = get_conn("KPI_PROJECT");
$mysql = sprintf("DELETE FROM `sa_kpi_summary` WHERE `implementation_period` = %s OR `maintenance_period` = %s;",
    GetSQLValueString(date("Y"), "int"),
    GetSQLValueString(date("Y"), "int")
);
$rsKPISummary = $DBKPI->get_sql($mysql, false);

$mysql = "SELECT `summary_id` FROM `sa_kpi_summary` ORDER BY `summary_id` DESC LIMIT 1";
$rsKPISummary = $DBKPI->get_sql($mysql);

//$mysql = "SET insert_id=" . $rsKPISummary[0]['summary_id']+1;
//$rsKPISummary = $DBKPI->get_sql($mysql, false);

$mysql = sprintf(
    "INSERT INTO `sa_kpi_summary`
        (
            `resource_name`,
            `implementation_not_reviewed`, 
            `implementation_reviewed`, 
            `implementation_period`, 
            `maintenance_not_reviewed`, 
            `maintenance_reviewed`, 
            `maintenance_period`
        ) 
            SELECT
                `a`.`Nama` AS `resource_name`,
                SUM(
                    IF(
                        `a`.`project_type` = 'MSI Project Implementation' AND LOCATE('Completed', `b`.`status`) = 0,
                        1,
                        0
                    )
                ) AS `i_not_yet_reviewed`,
                SUM(
                    IF(
                        `a`.`project_type` = 'MSI Project Implementation' AND LOCATE('Completed', `b`.`status`) > 0,
                        1,
                        0
                    )
                ) AS `i_reviewed`,
                IF(
                    `a`.`project_type` = 'MSI Project Implementation' AND LOCATE('Completed', `b`.`status`) > 0,
                    LEFT(`b`.`date`,4),
                    0
                ) AS `i_year`,
                SUM(
                    IF(
                        `a`.`project_type` = 'MSI Project Maintenance' AND LOCATE('Completed', `b`.`status`) = 0,
                        1,
                        0
                    )
                ) AS `m_not_yet_reviewed`,
                SUM(
                    IF(
                        `a`.`project_type` = 'MSI Project Maintenance' AND LOCATE('Completed', `b`.`status`) > 0,
                        1,
                        0
                    )
                ) AS `m_reviewed`,
                IF(
                    `a`.`project_type` = 'MSI Project Maintenance' AND LOCATE('Completed', `b`.`status`) > 0,
                    LEFT(`b`.`date`,4),
                    0
                ) AS `m_year`
            FROM
                `sa_user` `a`
            LEFT JOIN `sa_log_board` `b` ON
                `a`.`so_number` = `b`.`so_number`
            WHERE LEFT(`b`.`date`,4) LIKE %s
            GROUP BY
                `a`.`Nama`, LEFT(`b`.`date`,4);",
    GetSQLValueString(date("Y"), "text")
);
$rsKPISummary = $DBKPI->get_sql($mysql, false);

$time_end = microtime(true);
$time = $time_end - $time_start;
$end_syn = date("d-M-Y G:i:s");
$footer = "==========";
$footer .= "Finished : " . $end_syn;
$footer .= "The time used to run this module $time seconds";
$footer .= "==========";
echo $footer;

// $DBCRON->endingV2($log_id, "Completed", $log_file);

?>