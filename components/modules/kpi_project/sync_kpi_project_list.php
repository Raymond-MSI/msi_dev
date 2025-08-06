<?php
$time_start = microtime(true);
$mulai_sync = date("d-M-Y G:i:s");
$header = "==========";
$header .= "Execution module : mod_wrike_integrate/form_wrike_integrate. | ";
$header .= "Started : " . $mulai_sync;
$header .= "==========\r\n";
echo $header . "<br/>";
?>

<?php
$DBKPI = get_conn("KPI_PROJECT");
$DBGCAL = get_conn("GOOGLE_CALENDAR");

$mysql = "TRUNCATE TABLE `sa_kpi_dashboard_pl`;";
$rs = $DBKPI->get_sql($mysql, false);

$mysql = "INSERT INTO `sa_kpi_dashboard_pl`(
    `project_id`,
    `project_type`,
    `project_code`,
    `so_number`,
    `order_number`,
    `periode_so`,
    `customer_name`,
    `project_name`,
    `wrike_permalink`,
    `project_leader`,
    `project_manager`,
    `project_amount`,
    `SB_mandays_implementation`,
    `CR_mandays_implementation`,
    `WR_mandays_actual_implementation`,
    `start_assignment`,
    `end_assignment`,
    `bast_plan`,
    `bast_actual`,
    `commercial_kpi`,
    `commercial_category`,
    `time_kpi`,
    `time_category`,
    `error_kpi`,
    `error_category`,
    `total_cte`,
    `weighted_value`,
    `status_wrike`,
    `status_project`,
    `kpi_status`,
    `periode_kpi`
)
SELECT
    `sa_wrike_integrate`.`sa_wrike_project_list`.`id`,
    `sa_wrike_integrate`.`sa_wrike_project_list`.`project_type`,
    `sa_wrike_integrate`.`sa_wrike_project_list`.`project_code`,
    `sa_wrike_integrate`.`sa_wrike_project_list`.`no_so`,
    `sa_wrike_integrate`.`sa_wrike_project_list`.`order_number`,
    `sa_dashboard_kpi`.`sa_data_so`.`periode_so`,
    `sa_wrike_integrate`.`sa_wrike_project_detail`.`customer_name`,
    `sa_wrike_integrate`.`sa_wrike_project_list`.`title`,
    `sa_wrike_integrate`.`sa_wrike_project_list`.`permalink`,
    `sa_wrike_integrate`.`sa_wrike_project_list`.`project_leader`,
    `sa_wrike_integrate`.`sa_wrike_project_list`.`owner_email`,
    `sa_dashboard_kpi`.`sa_kpi_so_wr`.`value`,
    `sa_dashboard_kpi`.`sa_data_so`.`SB_mandays_implementation`,
    `sa_dashboard_kpi`.`sa_data_so`.`CR_mandays_implementation`,
    `sa_dashboard_kpi`.`sa_data_so`.`WR_mandays_actual_implementation`,
    `sa_dashboard_kpi`.`sa_kpi_so_wr`.`start_assignment`,
    `sa_dashboard_kpi`.`sa_kpi_so_wr`.`start_assignment`,
    `sa_dashboard_kpi`.`sa_kpi_so_wr`.`bast_plan`,
    `sa_dashboard_kpi`.`sa_kpi_so_wr`.`bast_actual`,
    `sa_dashboard_kpi`.`sa_kpi_so_wr`.`commercial_kpi`,
    `sa_dashboard_kpi`.`sa_kpi_so_wr`.`commercial_category`,
    `sa_dashboard_kpi`.`sa_kpi_so_wr`.`time_kpi`,
    `sa_dashboard_kpi`.`sa_kpi_so_wr`.`time_category`,
    `sa_dashboard_kpi`.`sa_kpi_so_wr`.`error_kpi`,
    `sa_dashboard_kpi`.`sa_kpi_so_wr`.`error_category`,
    `sa_dashboard_kpi`.`sa_kpi_so_wr`.`total_cte`,
    `sa_dashboard_kpi`.`sa_kpi_so_wr`.`weighted_value`,
    `sa_dashboard_kpi`.`sa_kpi_so_wr`.`status_wr`,
    `sa_dashboard_kpi`.`sa_kpi_so_wr`.`status_project`,
    `sa_dashboard_kpi`.`sa_kpi_so_wr`.`kpi_status`,
    `sa_dashboard_kpi`.`sa_data_so`.`tahun_review`
FROM
    `sa_wrike_integrate`.`sa_wrike_project_list`
LEFT JOIN `sa_dashboard_kpi`.`sa_kpi_so_wr` ON
    `sa_dashboard_kpi`.`sa_kpi_so_wr`.`order_number` = `sa_wrike_integrate`.`sa_wrike_project_list`.`order_number`
LEFT JOIN `sa_wrike_integrate`.`sa_wrike_project_detail` ON
    `sa_wrike_integrate`.`sa_wrike_project_list`.`id` = `sa_wrike_integrate`.`sa_wrike_project_detail`.`project_id`
LEFT JOIN `sa_dashboard_kpi`.`sa_data_so` ON
    `sa_dashboard_kpi`.`sa_data_so`.`order_number` = `sa_wrike_integrate`.`sa_wrike_project_list`.`order_number`;";
$rs = $DBKPI->get_sql($mysql, false);
echo "<p>$mysql</p>";
// $mysql = "SELECT `project_id` FROM `sa_kpi_dashboard_pl`";
// $rsPL = $DBKPI->get_sql($mysql);
// if($rsPL[2]>0)
// {
//     $i=0;
//     do {
//         $mysql = "SELECT COUNT(a.task_name) AS total_task FROM sa_preschedule a left join sa_schedule b ON a.google_event_id=b.event_id WHERE a.project_id='" . $rsPL[0]['project_id'] . "' AND b.response_status <>'declined'";
//         $rsPlan = $DBGCAL->get_sql($mysql);
//         if($rsPlan[2]>0)
//         {
//             $mysql = sprintf("UPDATE `sa_kpi_dashboard_pl` SET `total_task_plan` = " . $rsPlan[0]['total_task'] . " WHERE `project_id` = %s",
//                 GetSQLValueString($rsPL[0]['project_id'], "text")
//             );
//         }

//         $mysql = "SELECT COUNT(a.project_id) as total_update FROM sa_preschedule a left join sa_schedule b ON a.google_event_id=b.event_id WHERE a.project_id = '" . $rsPL[0]['project_id'] . "' AND b.response_status='accepted' AND b.diff_time<>0";
//         $rsActual = $DBGCAL->get_sql($mysql);
//         if($rsPlan[2]>0)
//         {
//             $mysql = sprintf("UPDATE `sa_kpi_dashboard_pl` SET `total_task_actual` = " . $rsActual[0]['total_update'] . " WHERE `project_id` = %s",
//                 GetSQLValueString($rsPL[0]['project_id'], "text")
//             );
//         }
//         echo "$i-";
//         $i++;
//     } while($rsPL[0]=$rsPL[1]->fetch_assoc());
// }


$mysql = "TRUNCATE TABLE `sa_kpi_dashboard_resource`;";
$rs = $DBKPI->get_sql($mysql, false);

// $mysql = "INSERT INTO `sa_kpi_dashboard_resource`(
//     `resource_name`,
//     `project_type`,
//     `ideal_value`,
//     `actual_value`,
//     `average_value`,
//     `ideal_final_value`,
//     `actual_final_value`,
//     `average_final_value`,
//     `kpi_status`,
//     `periode_kpi`
// )
// SELECT
//     `Nama`,
//     `project_type`,
//     SUM(`total_nilai_ideal`),
//     SUM(`total_nilai_aktual`),
//     SUM(`hasil_aktual_ideal`),
//     SUM(`total_nilai_akhir_ideal`),
//     SUM(`total_nilai_akhir_aktual`),
//     SUM(`hasil_akhir_aktual_ideal`),
//     `kpi_status`,
//     `periode`
// FROM
//     `sa_user_kpi`
// GROUP BY
//     `Nama`, `project_type`, `kpi_status`, `periode`;
// ";

$mysql = "INSERT INTO `sa_kpi_dashboard_resource`(
    `resource_name`,
    `project_type`,
    `ideal_value`,
    `actual_value`,
    `average_value`,
    `ideal_final_value`,
    `actual_final_value`,
    `average_final_value`,
    `kpi_status`,
    `periode_kpi`
)
SELECT
    `Nama`,
    `project_type`,
    SUM(`nilai_ideal`) AS `ideal_value`,
    SUM(`nilai_aktual`) AS `actual_value`,
    IF(
        `nilai_ideal`>0,
        SUM(`nilai_aktual`) / SUM(`nilai_ideal`) * 100,
        0
    ) AS `average`,
    SUM(
        REPLACE
            (`nilai_akhir_ideal`, ',', '.')
    ) AS `ideal_final_value`,
    SUM(
        REPLACE
            (`nilai_akhir_aktual`, ',', '.')
    ) AS `actual_final_value`,
    IF(
        REPLACE
            (`nilai_akhir_aktual`, ',', '.')>0,
        SUM(
            REPLACE
                (`nilai_akhir_aktual`, ',', '.')
        ) / SUM(
            REPLACE
                (`nilai_akhir_ideal`, ',', '.')
        ) * 100,
        0
    ) AS `average_resource`,
    `kpi_status`,
    `periode`
    FROM
        `sa_user`
    GROUP BY
        `Nama`, `project_type`, `kpi_status`, `periode`;";
$res = $DBKPI->get_sql($mysql, false);
echo "<p>$mysql</p>";


$mysql = "TRUNCATE TABLE `sa_kpi_dashboard_utilization`;";
$rs = $DBKPI->get_sql($mysql, false);

// Cost Utilization
$mysql = "INSERT INTO `sa_kpi_dashboard_utilization`(
    `project_code`,
    `so_number`,
    `order_number`,
    `periode_so`,
    `periode_kpi`,
    `normal`,
    `minor`,
    `major`,
    `critical`,
    `none`,
    `utilization_category`,
    `project_type`,
    `kpi_status`
)
SELECT
	`a`.`project_code`,
    `a`.`no_so`,
    `a`.`order_number`,
    `b`.`periode_so`,
    `b`.`tahun_review`,
    SUM(
        IF(
            `b`.`WR_mandays_actual_implementation` > 0 AND `c`.`commercial_category` = 'Normal',
            1,
            0
        )
    ) AS `normal`,
    SUM(
        IF(
            `b`.`WR_mandays_actual_implementation` > 0 AND `c`.`commercial_category` = 'Minor',
            1,
            0
        )
    ) AS `minor`,
    SUM(
        IF(
            `b`.`WR_mandays_actual_implementation` > 0 AND `c`.`commercial_category` = 'Major',
            1,
            0
        )
    ) AS `major`,
    SUM(
        IF(
            `b`.`WR_mandays_actual_implementation` > 0 AND `c`.`commercial_category` = 'Critical',
            1,
            0
        )
    ) AS `critical`,
    SUM(
        IF(
            (
                `b`.`WR_mandays_actual_implementation` > 0 AND `c`.`commercial_category` = 'Normal'
            ) OR(
                `b`.`WR_mandays_actual_implementation` > 0 AND `c`.`commercial_category` = 'Minor'
            ) OR(
                `b`.`WR_mandays_actual_implementation` > 0 AND `c`.`commercial_category` = 'Major'
            ) OR(
                `b`.`WR_mandays_actual_implementation` > 0 AND `c`.`commercial_category` = 'Critical'
            ),
            0,
            1
        )
    ) AS 'none',
    'cost_utilization' AS `utilization_category`,
    `a`.`project_type`,
    `c`.`kpi_status`
FROM
    `sa_wrike_integrate`.`sa_wrike_project_list` `a`
LEFT JOIN `sa_dashboard_kpi`.`sa_data_so` `b`
ON
    `a`.`order_number` = `b`.`order_number`
LEFT JOIN `sa_dashboard_kpi`.`sa_kpi_so_wr` `c`
ON
    `b`.`order_number` = `c`.`order_number`
WHERE `a`.`project_code` IS NOT NULL
GROUP BY
    `b`.`periode_so`,
    `b`.`periode`,
    `a`.`project_type`,
    `c`.`kpi_status`;
";
$res = $DBKPI->get_sql($mysql, false); 

// Time Uilization
$mysql = "INSERT INTO `sa_kpi_dashboard_utilization`(
    `project_code`,
    `so_number`,
    `order_number`,
    `periode_so`,
    `periode_kpi`,
    `normal`,
    `minor`,
    `major`,
    `critical`,
    `none`,
    `utilization_category`,
    `project_type`,
    `kpi_status`
)
SELECT
	`a`.`project_code`,
    `a`.`no_so`,
    `a`.`order_number`,
    `b`.`periode_so`,
    `b`.`tahun_review`,
    SUM(
        IF(
            `b`.`WR_mandays_actual_implementation` > 0 AND `c`.`time_category` = 'Normal',
            1,
            0
        )
    ) AS `normal`,
    SUM(
        IF(
            `b`.`WR_mandays_actual_implementation` > 0 AND `c`.`time_category` = 'Minor',
            1,
            0
        )
    ) AS `minor`,
    SUM(
        IF(
            `b`.`WR_mandays_actual_implementation` > 0 AND `c`.`time_category` = 'Major',
            1,
            0
        )
    ) AS `major`,
    SUM(
        IF(
            `b`.`WR_mandays_actual_implementation` > 0 AND `c`.`time_category` = 'Critical',
            1,
            0
        )
    ) AS `critical`,
    SUM(
        IF(
            (
                `b`.`WR_mandays_actual_implementation` > 0 AND `c`.`time_category` = 'Normal'
            ) OR(
                `b`.`WR_mandays_actual_implementation` > 0 AND `c`.`time_category` = 'Minor'
            ) OR(
                `b`.`WR_mandays_actual_implementation` > 0 AND `c`.`time_category` = 'Major'
            ) OR(
                `b`.`WR_mandays_actual_implementation` > 0 AND `c`.`time_category` = 'Critical'
            ),
            0,
            1
        )
    ) AS 'none',
    'time_utilization' AS `utilization_category`,
    `a`.`project_type`,
    `c`.`kpi_status`
FROM
    `sa_wrike_integrate`.`sa_wrike_project_list` `a`
LEFT JOIN `sa_dashboard_kpi`.`sa_data_so` `b`
ON
    `a`.`order_number` = `b`.`order_number`
LEFT JOIN `sa_dashboard_kpi`.`sa_kpi_so_wr` `c`
ON
    `b`.`order_number` = `c`.`order_number`
WHERE `a`.`project_code` IS NOT NULL
GROUP BY
    `b`.`periode_so`,
    `b`.`periode`,
    `a`.`project_type`,
    `c`.`kpi_status`;
";
$res = $DBKPI->get_sql($mysql, false); 

// Error Uilization
$mysql = "INSERT INTO `sa_kpi_dashboard_utilization`(
    `project_code`,
    `so_number`,
    `order_number`,
    `periode_so`,
    `periode_kpi`,
    `normal`,
    `minor`,
    `major`,
    `critical`,
    `none`,
    `utilization_category`,
    `project_type`,
    `kpi_status`
)
SELECT
	`a`.`project_code`,
    `a`.`no_so`,
    `a`.`order_number`,
    `b`.`periode_so`,
    `b`.`tahun_review`,
    SUM(
        IF(
            `b`.`WR_mandays_actual_implementation` > 0 AND `c`.`error_category` = 'Normal',
            1,
            0
        )
    ) AS `normal`,
    SUM(
        IF(
            `b`.`WR_mandays_actual_implementation` > 0 AND `c`.`error_category` = 'Minor',
            1,
            0
        )
    ) AS `minor`,
    SUM(
        IF(
            `b`.`WR_mandays_actual_implementation` > 0 AND `c`.`error_category` = 'Major',
            1,
            0
        )
    ) AS `major`,
    SUM(
        IF(
            `b`.`WR_mandays_actual_implementation` > 0 AND `c`.`error_category` = 'Critical',
            1,
            0
        )
    ) AS `critical`,
    SUM(
        IF(
            (
                `b`.`WR_mandays_actual_implementation` > 0 AND `c`.`error_category` = 'Normal'
            ) OR(
                `b`.`WR_mandays_actual_implementation` > 0 AND `c`.`error_category` = 'Minor'
            ) OR(
                `b`.`WR_mandays_actual_implementation` > 0 AND `c`.`error_category` = 'Major'
            ) OR(
                `b`.`WR_mandays_actual_implementation` > 0 AND `c`.`error_category` = 'Critical'
            ),
            0,
            1
        )
    ) AS 'none',
    'error_utilization' AS `utilization_category`,
    `a`.`project_type`,
    `c`.`kpi_status`
FROM
    `sa_wrike_integrate`.`sa_wrike_project_list` `a`
LEFT JOIN `sa_dashboard_kpi`.`sa_data_so` `b`
ON
    `a`.`order_number` = `b`.`order_number`
LEFT JOIN `sa_dashboard_kpi`.`sa_kpi_so_wr` `c`
ON
    `b`.`order_number` = `c`.`order_number`
WHERE `a`.`project_code` IS NOT NULL
GROUP BY
    `b`.`periode_so`,
    `b`.`periode`,
    `a`.`project_type`,
    `c`.`kpi_status`;
";
$res = $DBKPI->get_sql($mysql, false); 

echo "<p>$mysql</p>";
?>


<?php
$time_end = microtime(true);
$time = $time_end - $time_start;
$end_syn = date("d-M-Y G:i:s");
$footer = "==========";
$footer .= "Finished : " . $end_syn . " | ";
$footer .= "The time used to run this module $time seconds";
$footer .= "==========";
echo $footer;
?>