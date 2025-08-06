<?php
global $DBKPI;
if ($_GET['act'] == 'edit') {
    $sbsb = "SERVICE_BUDGET";
    $DBSB1 = get_conn($sbsb);

    $wrwr = "WRIKE_INTEGRATE";
    $DBWR = get_conn($wrwr);

    $hcm = "HCM";
    $DBHCM = get_conn($hcm);


    $query_project_kpi = $DBKPI->get_sql("SELECT project_code_kpi,SB_project_estimation_implementation,SB_mandays_implementation,CR_project_estimation_implementation,CR_mandays_implementation,SB_amount_idr,WR_project_estimation_implementation,WR_duration_actual_implementation,WR_mandays_plan_implementation,WR_mandays_actual_implementation,WR_start_assignment_implementation,WR_bast_date_project_implementation FROM sa_data_project WHERE SB_service_type_implementation=1 AND project_code_kpi='TB031821B0001'");
    $row = $query_project_kpi[0];
    $res = $query_project_kpi[1];
    do {
        $project_code = $row['project_code_kpi'];
        $estimation = $row['SB_project_estimation_implementation'];
        $estimation_cr = $row['CR_project_estimation_implementation'];
        $estimation_wr = $row['WR_project_estimation_implementation'];
        $duration_actual_wr = $row['WR_duration_actual_implementation'];
        $mandays = $row['SB_mandays_implementation'];
        $mandays_cr = $row['CR_mandays_implementation'];
        $mandays_plan_wr = $row['WR_mandays_plan_implementation'];
        $mandays_actual_wr = $row['WR_mandays_actual_implementation'];
        $implementation_price = $row['SB_amount_idr'];
        $start_date_project = $row['WR_start_assignment_implementation'];
        $bast_date_project = $row['WR_bast_date_project_implementation'];
        $timeline = $duration_actual_wr - $estimation_wr;
        if ($timeline >= 30 && $timeline < 90) {
            $get_param = $DBKPI->get_sql("SELECT value,value_type FROM sa_params WHERE category='Minor' AND type='Timeline'");
            $param1 = $get_param[0]['value'];
            $param2 = $get_param[0]['value_type'];
            $timeline_category = 'Minor';
            $value = $implementation_price / 1000000000;
            $timeline_result = $param1 * $param2;
        } else if ($timeline >= 90 && $timeline < 180) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Major'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Timeline'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $timeline_category = 'Major';
            $value = $implementation_price / 1000000000;
            $timeline_result = $param1 * $param2;
        } else if ($timeline >= 180) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Critical'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Timeline'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $timeline_category = 'Critical';
            $value = $implementation_price / 1000000000;
            $timeline_result = $param1 * $param2;
        } else if ($timeline < 0) {
            $timeline_category = 'Normal';
            $value = $implementation_price / 1000000000;
            $timeline_result = 0;
        }
        $commercial = $mandays_actual_wr - $mandays_plan_wr;
        $hasil_commercial = ($commercial / $mandays_plan_wr + 1) * 100;
        if ($hasil_commercial > 110 && $hasil_commercial <= 150) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Minor'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Commercial'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $commercial_category = 'Minor';
            $commercial_result = $param1 * $param2;
        } else if ($hasil_commercial > 150 && $hasil_commercial <= 200) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Major'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Commercial'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $commercial_category = 'Major';
            $commercial_result = $param1 * $param2;
        } else if ($hasil_commercial > 200) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Critical'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Commercial'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $commercial_category = 'Critical';
            $commercial_result = $param1 * $param2;
        } else if ($hasil_commercial < 110 || $commercial == 0) {
            $commercial_category = 'Normal';
            $commercial_result = 0;
        }
        $cte_project = $commercial_result + $timeline_result;
        $cte = 1 - $cte_project;
        $max_value = $value * 100;
        $weighted_value = $cte * $max_value;
        // echo $hasil_commercial . '/' . $commercial . '[' . ($commercial / $mandays_plan_wr) . ']';
        // echo $commercial_category . '' . $commercial_result;
        // echo '3. ' . $start_date_project . '4. ' . $bast_date_project;
        $insert_data = $DBKPI->get_sql("INSERT INTO sa_kpi_project_wr (project_code,value,start_assignment,bast,total,time_category,time_kpi,cte,total_cte,max_value,weighted_value,commercial_category,commercial_kpi) VALUES ('$project_code','$value','$start_date_project','$bast_date_project','$duration_actual_wr','$timeline_category','$timeline_result','$cte_project','$cte','$max_value','$weighted_value','$commercial_category','$commercial_result') ON DUPLICATE KEY UPDATE value = '$value', start_assignment = '$start_date_project', bast = '$bast_date_project', total = '$duration_actual_wr', time_category = '$timeline_category', time_kpi = '$timeline_result', cte = '$cte_project', total_cte = '$cte', max_value = '$max_value', weighted_value = '$weighted_value', commercial_category = '$commercial_category', commercial_kpi = '$commercial_result'");
    } while ($row = $res->fetch_assoc());


    $query_project_kpi2 = $DBKPI->get_sql("SELECT project_code_kpi,SB_project_estimation_implementation,SB_mandays_implementation,CR_project_estimation_implementation,CR_mandays_implementation,SB_amount_idr,WR_project_estimation_implementation,WR_duration_actual_implementation,WR_mandays_plan_implementation,WR_mandays_actual_implementation,WR_start_assignment_implementation,WR_bast_date_project_implementation FROM sa_data_project WHERE SB_service_type_implementation=1 AND project_code_kpi='TB006321B0025'");
    $row2 = $query_project_kpi2[0];
    $res2 = $query_project_kpi2[1];
    do {
        $project_code = $row2['project_code_kpi'];
        $estimation = $row2['SB_project_estimation_implementation'];
        $estimation_cr = $row2['CR_project_estimation_implementation'];
        $estimation_wr = $row2['WR_project_estimation_implementation'];
        $duration_actual_wr = $row2['WR_duration_actual_implementation'];
        $mandays = $row2['SB_mandays_implementation'];
        $mandays_cr = $row2['CR_mandays_implementation'];
        $mandays_plan_wr = $row2['WR_mandays_plan_implementation'];
        $mandays_actual_wr = $row2['WR_mandays_actual_implementation'];
        $implementation_price = $row2['SB_amount_idr'];
        $start_date_project = $row2['WR_start_assignment_implementation'];
        $bast_date_project = $row2['WR_bast_date_project_implementation'];
        $timeline = $duration_actual_wr - $estimation_wr;
        if ($timeline >= 30 && $timeline < 90) {
            $get_param = $DBKPI->get_sql("SELECT value,value_type FROM sa_params WHERE category='Minor' AND type='Timeline'");
            $param1 = $get_param[0]['value'];
            $param2 = $get_param[0]['value_type'];
            $timeline_category = 'Minor';
            $value = $implementation_price / 1000000000;
            $timeline_result = $param1 * $param2;
        } else if ($timeline >= 90 && $timeline < 180) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Major'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Timeline'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $timeline_category = 'Major';
            $value = $implementation_price / 1000000000;
            $timeline_result = $param1 * $param2;
        } else if ($timeline >= 180) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Critical'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Timeline'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $timeline_category = 'Critical';
            $value = $implementation_price / 1000000000;
            $timeline_result = $param1 * $param2;
        } else if ($timeline < 30) {
            $timeline_category = 'Normal';
            $value = $implementation_price / 1000000000;
            $timeline_result = 0;
        }
        $commercial = $mandays_actual_wr - $mandays;
        $hasil_commercial = ($commercial / $mandays + 1) * 100;
        if ($hasil_commercial > 110 && $hasil_commercial <= 150) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Minor'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Commercial'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $commercial_category = 'Minor';
            $commercial_result = $param1 * $param2;
        } else if ($hasil_commercial > 150 && $hasil_commercial <= 200) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Major'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Commercial'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $commercial_category = 'Major';
            $commercial_result = $param1 * $param2;
        } else if ($hasil_commercial > 200) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Critical'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Commercial'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $commercial_category = 'Critical';
            $commercial_result = $param1 * $param2;
        } else if ($hasil_commercial < 100) {
            $commercial_category = 'Normal';
            $commercial_result = 0;
        }
        $cte_project = $commercial_result + $timeline_result;
        $cte = 1 - $cte_project;
        $max_value = $value * 100;
        $weighted_value = $cte * $max_value;
        // echo $cte . '<' . $cte_project . '>' . '[' . (1 - $cte_project) . ']';
        // echo $project_id, $project_code_sb, $project_name_sb, $customer_name_sb, $bundling_sb, $amount_idr_sb, $amount_usd_sb;
        $insert_data = $DBKPI->get_sql("INSERT INTO sa_kpi_project_wr (project_code,value,start_assignment,bast,total,time_category,time_kpi,cte,total_cte,max_value,weighted_value,commercial_category,commercial_kpi) VALUES ('$project_code','$value','$start_date_project','$bast_date_project','$duration_actual_wr','$timeline_category','$timeline_result','$cte_project','$cte','$max_value','$weighted_value','$commercial_category','$commercial_result') ON DUPLICATE KEY UPDATE value = '$value', start_assignment = '$start_date_project', bast = '$bast_date_project', total = '$duration_actual_wr', time_category = '$timeline_category', time_kpi = '$timeline_result', cte = '$cte_project', total_cte = '$cte', max_value = '$max_value', weighted_value = '$weighted_value', commercial_category = '$commercial_category', commercial_kpi = '$commercial_result'");
    } while ($row2 = $res2->fetch_assoc());


    $query_project_kpi2 = $DBKPI->get_sql("SELECT project_code_kpi,SB_project_estimation_implementation,SB_mandays_implementation,CR_project_estimation_implementation,CR_mandays_implementation,SB_amount_idr,WR_project_estimation_implementation,WR_duration_actual_implementation,WR_mandays_plan_implementation,WR_mandays_actual_implementation,WR_start_assignment_implementation,WR_bast_date_project_implementation FROM sa_data_project WHERE SB_service_type_implementation=1 AND project_code_kpi='TA002222E0002'");
    $row2 = $query_project_kpi2[0];
    $res2 = $query_project_kpi2[1];
    do {
        $project_code = $row2['project_code_kpi'];
        $estimation = $row2['SB_project_estimation_implementation'];
        $estimation_cr = $row2['CR_project_estimation_implementation'];
        $estimation_wr = $row2['WR_project_estimation_implementation'];
        $duration_actual_wr = $row2['WR_duration_actual_implementation'];
        $mandays = $row2['SB_mandays_implementation'];
        $mandays_cr = $row2['CR_mandays_implementation'];
        $mandays_plan_wr = $row2['WR_mandays_plan_implementation'];
        $mandays_actual_wr = $row2['WR_mandays_actual_implementation'];
        $implementation_price = $row2['SB_amount_idr'];
        $start_date_project = $row2['WR_start_assignment_implementation'];
        $bast_date_project = $row2['WR_bast_date_project_implementation'];
        $timeline = $duration_actual_wr - $estimation_wr;
        if ($timeline >= 30 && $timeline < 90) {
            $get_param = $DBKPI->get_sql("SELECT value,value_type FROM sa_params WHERE category='Minor' AND type='Timeline'");
            $param1 = $get_param[0]['value'];
            $param2 = $get_param[0]['value_type'];
            $timeline_category = 'Minor';
            $value = $implementation_price / 1000000000;
            $timeline_result = $param1 * $param2;
        } else if ($timeline >= 90 && $timeline < 180) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Major'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Timeline'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $timeline_category = 'Major';
            $value = $implementation_price / 1000000000;
            $timeline_result = $param1 * $param2;
        } else if ($timeline >= 180) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Critical'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Timeline'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $timeline_category = 'Critical';
            $value = $implementation_price / 1000000000;
            $timeline_result = $param1 * $param2;
        } else if ($timeline < 30) {
            $timeline_category = 'Normal';
            $value = $implementation_price / 1000000000;
            $timeline_result = 0;
        }
        $commercial = $mandays_actual_wr - $mandays;
        $hasil_commercial = ($commercial / $mandays + 1) * 100;
        if ($hasil_commercial > 110 && $hasil_commercial <= 150) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Minor'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Commercial'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $commercial_category = 'Minor';
            $commercial_result = $param1 * $param2;
        } else if ($hasil_commercial > 150 && $hasil_commercial <= 200) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Major'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Commercial'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $commercial_category = 'Major';
            $commercial_result = $param1 * $param2;
        } else if ($hasil_commercial > 200) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Critical'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Commercial'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $commercial_category = 'Critical';
            $commercial_result = $param1 * $param2;
        } else if ($hasil_commercial < 100) {
            $commercial_category = 'Normal';
            $commercial_result = 0;
        }
        $cte_project = $commercial_result + $timeline_result;
        $cte = 1 - $cte_project;
        $max_value = $value * 100;
        $weighted_value = $cte * $max_value;
        // echo $cte . '<' . $cte_project . '>' . '[' . (1 - $cte_project) . ']';
        // echo $project_id, $project_code_sb, $project_name_sb, $customer_name_sb, $bundling_sb, $amount_idr_sb, $amount_usd_sb;
        $insert_data = $DBKPI->get_sql("INSERT INTO sa_kpi_project_wr (project_code,value,start_assignment,bast,total,time_category,time_kpi,cte,total_cte,max_value,weighted_value,commercial_category,commercial_kpi) VALUES ('$project_code','$value','$start_date_project','$bast_date_project','$duration_actual_wr','$timeline_category','$timeline_result','$cte_project','$cte','$max_value','$weighted_value','$commercial_category','$commercial_result') ON DUPLICATE KEY UPDATE value = '$value', start_assignment = '$start_date_project', bast = '$bast_date_project', total = '$duration_actual_wr', time_category = '$timeline_category', time_kpi = '$timeline_result', cte = '$cte_project', total_cte = '$cte', max_value = '$max_value', weighted_value = '$weighted_value', commercial_category = '$commercial_category', commercial_kpi = '$commercial_result'");
    } while ($row2 = $res2->fetch_assoc());


    $query_project_kpi2 = $DBKPI->get_sql("SELECT project_code_kpi,SB_project_estimation_implementation,SB_mandays_implementation,CR_project_estimation_implementation,CR_mandays_implementation,SB_amount_idr,WR_project_estimation_implementation,WR_duration_actual_implementation,WR_mandays_plan_implementation,WR_mandays_actual_implementation,WR_start_assignment_implementation,WR_bast_date_project_implementation FROM sa_data_project WHERE SB_service_type_implementation=1 AND project_code_kpi='TB000318D0002'");
    $row2 = $query_project_kpi2[0];
    $res2 = $query_project_kpi2[1];
    do {
        $project_code = $row2['project_code_kpi'];
        $estimation = $row2['SB_project_estimation_implementation'];
        $estimation_cr = $row2['CR_project_estimation_implementation'];
        $estimation_wr = $row2['WR_project_estimation_implementation'];
        $duration_actual_wr = $row2['WR_duration_actual_implementation'];
        $mandays = $row2['SB_mandays_implementation'];
        $mandays_cr = $row2['CR_mandays_implementation'];
        $mandays_plan_wr = $row2['WR_mandays_plan_implementation'];
        $mandays_actual_wr = $row2['WR_mandays_actual_implementation'];
        $implementation_price = $row2['SB_amount_idr'];
        $start_date_project = $row2['WR_start_assignment_implementation'];
        $bast_date_project = $row2['WR_bast_date_project_implementation'];
        $timeline = $duration_actual_wr - $estimation_wr;
        if ($timeline >= 30 && $timeline < 90) {
            $get_param = $DBKPI->get_sql("SELECT value,value_type FROM sa_params WHERE category='Minor' AND type='Timeline'");
            $param1 = $get_param[0]['value'];
            $param2 = $get_param[0]['value_type'];
            $timeline_category = 'Minor';
            $value = $implementation_price / 1000000000;
            $timeline_result = $param1 * $param2;
        } else if ($timeline >= 90 && $timeline < 180) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Major'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Timeline'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $timeline_category = 'Major';
            $value = $implementation_price / 1000000000;
            $timeline_result = $param1 * $param2;
        } else if ($timeline >= 180) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Critical'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Timeline'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $timeline_category = 'Critical';
            $value = $implementation_price / 1000000000;
            $timeline_result = $param1 * $param2;
        } else if ($timeline < 30) {
            $timeline_category = 'Normal';
            $value = $implementation_price / 1000000000;
            $timeline_result = 0;
        }
        $commercial = $mandays_actual_wr - $mandays;
        $hasil_commercial = ($commercial / $mandays + 1) * 100;
        if ($hasil_commercial > 110 && $hasil_commercial <= 150) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Minor'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Commercial'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $commercial_category = 'Minor';
            $commercial_result = $param1 * $param2;
        } else if ($hasil_commercial > 150 && $hasil_commercial <= 200) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Major'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Commercial'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $commercial_category = 'Major';
            $commercial_result = $param1 * $param2;
        } else if ($hasil_commercial > 200) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Critical'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Commercial'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $commercial_category = 'Critical';
            $commercial_result = $param1 * $param2;
        } else if ($hasil_commercial < 100) {
            $commercial_category = 'Normal';
            $commercial_result = 0;
            $cte_project = 0 + $timeline_result;
        }
        $cte_project = $commercial_result + $timeline_result;
        $cte = 1 - $cte_project;
        $max_value = $value * 100;
        $weighted_value = $cte * $max_value;
        // echo $cte . '<' . $cte_project . '>' . '[' . (1 - $cte_project) . ']';
        // echo $project_id, $project_code_sb, $project_name_sb, $customer_name_sb, $bundling_sb, $amount_idr_sb, $amount_usd_sb;
        $insert_data = $DBKPI->get_sql("INSERT INTO sa_kpi_project_wr (project_code,value,start_assignment,bast,total,time_category,time_kpi,cte,total_cte,max_value,weighted_value,commercial_category,commercial_kpi) VALUES ('$project_code','$value','$start_date_project','$bast_date_project','$duration_actual_wr','$timeline_category','$timeline_result','$cte_project','$cte','$max_value','$weighted_value','$commercial_category','$commercial_result') ON DUPLICATE KEY UPDATE value = '$value', start_assignment = '$start_date_project', bast = '$bast_date_project', total = '$duration_actual_wr', time_category = '$timeline_category', time_kpi = '$timeline_result', cte = '$cte_project', total_cte = '$cte', max_value = '$max_value', weighted_value = '$weighted_value', commercial_category = '$commercial_category', commercial_kpi = '$commercial_result'");
    } while ($row2 = $res2->fetch_assoc());


    $query_project_kpi2 = $DBKPI->get_sql("SELECT project_code_kpi,SB_project_estimation_implementation,SB_mandays_implementation,CR_project_estimation_implementation,CR_mandays_implementation,SB_amount_idr,WR_project_estimation_implementation,WR_duration_actual_implementation,WR_mandays_plan_implementation,WR_mandays_actual_implementation,WR_start_assignment_implementation,WR_bast_date_project_implementation FROM sa_data_project WHERE SB_service_type_implementation=1 AND project_code_kpi='TT003021N0015'");
    $row2 = $query_project_kpi2[0];
    $res2 = $query_project_kpi2[1];
    do {
        $project_code = $row2['project_code_kpi'];
        $estimation = $row2['SB_project_estimation_implementation'];
        $estimation_cr = $row2['CR_project_estimation_implementation'];
        $estimation_wr = $row2['WR_project_estimation_implementation'];
        $duration_actual_wr = $row2['WR_duration_actual_implementation'];
        $mandays = $row2['SB_mandays_implementation'];
        $mandays_cr = $row2['CR_mandays_implementation'];
        $mandays_plan_wr = $row2['WR_mandays_plan_implementation'];
        $mandays_actual_wr = $row2['WR_mandays_actual_implementation'];
        $implementation_price = $row2['SB_amount_idr'];
        $start_date_project = $row2['WR_start_assignment_implementation'];
        $bast_date_project = $row2['WR_bast_date_project_implementation'];
        $timeline = $duration_actual_wr - $estimation_wr;
        if ($timeline >= 30 && $timeline < 90) {
            $get_param = $DBKPI->get_sql("SELECT value,value_type FROM sa_params WHERE category='Minor' AND type='Timeline'");
            $param1 = $get_param[0]['value'];
            $param2 = $get_param[0]['value_type'];
            $timeline_category = 'Minor';
            $value = $implementation_price / 1000000000;
            $timeline_result = $param1 * $param2;
        } else if ($timeline >= 90 && $timeline < 180) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Major'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Timeline'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $timeline_category = 'Major';
            $value = $implementation_price / 1000000000;
            $timeline_result = $param1 * $param2;
        } else if ($timeline >= 180) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Critical'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Timeline'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $timeline_category = 'Critical';
            $value = $implementation_price / 1000000000;
            $timeline_result = $param1 * $param2;
        } else if ($timeline < 30) {
            $timeline_category = 'Normal';
            $value = $implementation_price / 1000000000;
            $timeline_result = 0;
        }
        $commercial = $mandays_actual_wr - $mandays;
        $hasil_commercial = ($commercial / $mandays + 1) * 100;
        if ($hasil_commercial > 110 && $hasil_commercial <= 150) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Minor'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Commercial'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $commercial_category = 'Minor';
            $commercial_result = $param1 * $param2;
        } else if ($hasil_commercial > 150 && $hasil_commercial <= 200) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Major'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Commercial'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $commercial_category = 'Major';
            $commercial_result = $param1 * $param2;
        } else if ($hasil_commercial > 200) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Critical'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Commercial'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $commercial_category = 'Critical';
            $commercial_result = $param1 * $param2;
        } else if ($hasil_commercial < 100) {
            $commercial_category = 'Normal';
            $commercial_result = 0;
            $cte_project = 0 + $timeline_result;
        }
        $cte_project = $commercial_result + $timeline_result;
        $cte = 1 - $cte_project;
        $max_value = $value * 100;
        $weighted_value = $cte * $max_value;
        // echo $cte . '<' . $cte_project . '>' . '[' . (1 - $cte_project) . ']';
        // echo $project_id, $project_code_sb, $project_name_sb, $customer_name_sb, $bundling_sb, $amount_idr_sb, $amount_usd_sb;
        $insert_data = $DBKPI->get_sql("INSERT INTO sa_kpi_project_wr (project_code,value,start_assignment,bast,total,time_category,time_kpi,cte,total_cte,max_value,weighted_value,commercial_category,commercial_kpi) VALUES ('$project_code','$value','$start_date_project','$bast_date_project','$duration_actual_wr','$timeline_category','$timeline_result','$cte_project','$cte','$max_value','$weighted_value','$commercial_category','$commercial_result') ON DUPLICATE KEY UPDATE value = '$value', start_assignment = '$start_date_project', bast = '$bast_date_project', total = '$duration_actual_wr', time_category = '$timeline_category', time_kpi = '$timeline_result', cte = '$cte_project', total_cte = '$cte', max_value = '$max_value', weighted_value = '$weighted_value', commercial_category = '$commercial_category', commercial_kpi = '$commercial_result'");
    } while ($row2 = $res2->fetch_assoc());


    $query_project_kpi2 = $DBKPI->get_sql("SELECT project_code_kpi,SB_project_estimation_implementation,SB_mandays_implementation,CR_project_estimation_implementation,CR_mandays_implementation,SB_amount_idr,WR_project_estimation_implementation,WR_duration_actual_implementation,WR_mandays_plan_implementation,WR_mandays_actual_implementation,WR_start_assignment_implementation,WR_bast_date_project_implementation FROM sa_data_project WHERE SB_service_type_implementation=1 AND project_code_kpi='TT000121N0014'");
    $row2 = $query_project_kpi2[0];
    $res2 = $query_project_kpi2[1];
    do {
        $project_code = $row2['project_code_kpi'];
        $estimation = $row2['SB_project_estimation_implementation'];
        $estimation_cr = $row2['CR_project_estimation_implementation'];
        $estimation_wr = $row2['WR_project_estimation_implementation'];
        $duration_actual_wr = $row2['WR_duration_actual_implementation'];
        $mandays = $row2['SB_mandays_implementation'];
        $mandays_cr = $row2['CR_mandays_implementation'];
        $mandays_plan_wr = $row2['WR_mandays_plan_implementation'];
        $mandays_actual_wr = $row2['WR_mandays_actual_implementation'];
        $implementation_price = $row2['SB_amount_idr'];
        $start_date_project = $row2['WR_start_assignment_implementation'];
        $bast_date_project = $row2['WR_bast_date_project_implementation'];
        $timeline = $duration_actual_wr - $estimation_wr;
        if ($timeline >= 30 && $timeline < 90) {
            $get_param = $DBKPI->get_sql("SELECT value,value_type FROM sa_params WHERE category='Minor' AND type='Timeline'");
            $param1 = $get_param[0]['value'];
            $param2 = $get_param[0]['value_type'];
            $timeline_category = 'Minor';
            $value = $implementation_price / 1000000000;
            $timeline_result = $param1 * $param2;
        } else if ($timeline >= 90 && $timeline < 180) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Major'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Timeline'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $timeline_category = 'Major';
            $value = $implementation_price / 1000000000;
            $timeline_result = $param1 * $param2;
        } else if ($timeline >= 180) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Critical'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Timeline'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $timeline_category = 'Critical';
            $value = $implementation_price / 1000000000;
            $timeline_result = $param1 * $param2;
        } else if ($timeline < 30) {
            $timeline_category = 'Normal';
            $value = $implementation_price / 1000000000;
            $timeline_result = 0;
        }
        $commercial = $mandays_actual_wr - $mandays;
        $hasil_commercial = ($commercial / $mandays + 1) * 100;
        if ($hasil_commercial > 110 && $hasil_commercial <= 150) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Minor'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Commercial'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $commercial_category = 'Minor';
            $commercial_result = $param1 * $param2;
        } else if ($hasil_commercial > 150 && $hasil_commercial <= 200) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Major'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Commercial'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $commercial_category = 'Major';
            $commercial_result = $param1 * $param2;
        } else if ($hasil_commercial > 200) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Critical'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Commercial'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $commercial_category = 'Critical';
            $commercial_result = $param1 * $param2;
        } else if ($hasil_commercial < 100) {
            $commercial_category = 'Normal';
            $commercial_result = 0;
            $cte_project = 0 + $timeline_result;
        }
        $cte_project = $commercial_result + $timeline_result;
        $cte = 1 - $cte_project;
        $max_value = $value * 100;
        $weighted_value = $cte * $max_value;
        // echo $cte . '<' . $cte_project . '>' . '[' . (1 - $cte_project) . ']';
        // echo $project_id, $project_code_sb, $project_name_sb, $customer_name_sb, $bundling_sb, $amount_idr_sb, $amount_usd_sb;
        $insert_data = $DBKPI->get_sql("INSERT INTO sa_kpi_project_wr (project_code,value,start_assignment,bast,total,time_category,time_kpi,cte,total_cte,max_value,weighted_value,commercial_category,commercial_kpi) VALUES ('$project_code','$value','$start_date_project','$bast_date_project','$duration_actual_wr','$timeline_category','$timeline_result','$cte_project','$cte','$max_value','$weighted_value','$commercial_category','$commercial_result') ON DUPLICATE KEY UPDATE value = '$value', start_assignment = '$start_date_project', bast = '$bast_date_project', total = '$duration_actual_wr', time_category = '$timeline_category', time_kpi = '$timeline_result', cte = '$cte_project', total_cte = '$cte', max_value = '$max_value', weighted_value = '$weighted_value', commercial_category = '$commercial_category', commercial_kpi = '$commercial_result'");
    } while ($row2 = $res2->fetch_assoc());


    $query_project_kpi2 = $DBKPI->get_sql("SELECT project_code_kpi,SB_project_estimation_implementation,SB_mandays_implementation,CR_project_estimation_implementation,CR_mandays_implementation,SB_amount_idr,WR_project_estimation_implementation,WR_duration_actual_implementation,WR_mandays_plan_implementation,WR_mandays_actual_implementation,WR_start_assignment_implementation,WR_bast_date_project_implementation FROM sa_data_project WHERE SB_service_type_implementation=1 AND project_code_kpi='TT000121N0001'");
    $row2 = $query_project_kpi2[0];
    $res2 = $query_project_kpi2[1];
    do {
        $project_code = $row2['project_code_kpi'];
        $estimation = $row2['SB_project_estimation_implementation'];
        $estimation_cr = $row2['CR_project_estimation_implementation'];
        $estimation_wr = $row2['WR_project_estimation_implementation'];
        $duration_actual_wr = $row2['WR_duration_actual_implementation'];
        $mandays = $row2['SB_mandays_implementation'];
        $mandays_cr = $row2['CR_mandays_implementation'];
        $mandays_plan_wr = $row2['WR_mandays_plan_implementation'];
        $mandays_actual_wr = $row2['WR_mandays_actual_implementation'];
        $implementation_price = $row2['SB_amount_idr'];
        $start_date_project = $row2['WR_start_assignment_implementation'];
        $bast_date_project = $row2['WR_bast_date_project_implementation'];
        $timeline = $duration_actual_wr - $estimation_wr;
        if ($timeline >= 30 && $timeline < 90) {
            $get_param = $DBKPI->get_sql("SELECT value,value_type FROM sa_params WHERE category='Minor' AND type='Timeline'");
            $param1 = $get_param[0]['value'];
            $param2 = $get_param[0]['value_type'];
            $timeline_category = 'Minor';
            $value = $implementation_price / 1000000000;
            $timeline_result = $param1 * $param2;
        } else if ($timeline >= 90 && $timeline < 180) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Major'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Timeline'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $timeline_category = 'Major';
            $value = $implementation_price / 1000000000;
            $timeline_result = $param1 * $param2;
        } else if ($timeline >= 180) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Critical'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Timeline'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $timeline_category = 'Critical';
            $value = $implementation_price / 1000000000;
            $timeline_result = $param1 * $param2;
        } else if ($timeline < 30) {
            $timeline_category = 'Normal';
            $value = $implementation_price / 1000000000;
            $timeline_result = 0;
        }
        $commercial = $mandays_actual_wr - $mandays;
        $hasil_commercial = ($commercial / $mandays + 1) * 100;
        if ($hasil_commercial > 110 && $hasil_commercial <= 150) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Minor'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Commercial'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $commercial_category = 'Minor';
            $commercial_result = $param1 * $param2;
        } else if ($hasil_commercial > 150 && $hasil_commercial <= 200) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Major'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Commercial'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $commercial_category = 'Major';
            $commercial_result = $param1 * $param2;
        } else if ($hasil_commercial > 200) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Critical'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Commercial'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $commercial_category = 'Critical';
            $commercial_result = $param1 * $param2;
        } else if ($hasil_commercial < 100) {
            $commercial_category = 'Normal';
            $commercial_result = 0;
            $cte_project = 0 + $timeline_result;
        }
        $cte_project = $commercial_result + $timeline_result;
        $cte = 1 - $cte_project;
        $max_value = $value * 100;
        $weighted_value = $cte * $max_value;
        // echo $cte . '<' . $cte_project . '>' . '[' . (1 - $cte_project) . ']';
        // echo $project_id, $project_code_sb, $project_name_sb, $customer_name_sb, $bundling_sb, $amount_idr_sb, $amount_usd_sb;
        $insert_data = $DBKPI->get_sql("INSERT INTO sa_kpi_project_wr (project_code,value,start_assignment,bast,total,time_category,time_kpi,cte,total_cte,max_value,weighted_value,commercial_category,commercial_kpi) VALUES ('$project_code','$value','$start_date_project','$bast_date_project','$duration_actual_wr','$timeline_category','$timeline_result','$cte_project','$cte','$max_value','$weighted_value','$commercial_category','$commercial_result') ON DUPLICATE KEY UPDATE value = '$value', start_assignment = '$start_date_project', bast = '$bast_date_project', total = '$duration_actual_wr', time_category = '$timeline_category', time_kpi = '$timeline_result', cte = '$cte_project', total_cte = '$cte', max_value = '$max_value', weighted_value = '$weighted_value', commercial_category = '$commercial_category', commercial_kpi = '$commercial_result'");
    } while ($row2 = $res2->fetch_assoc());


    $query_project_kpi2 = $DBKPI->get_sql("SELECT project_code_kpi,SB_project_estimation_implementation,SB_mandays_implementation,CR_project_estimation_implementation,CR_mandays_implementation,SB_amount_idr,WR_project_estimation_implementation,WR_duration_actual_implementation,WR_mandays_plan_implementation,WR_mandays_actual_implementation,WR_start_assignment_implementation,WR_bast_date_project_implementation FROM sa_data_project WHERE SB_service_type_implementation=1 AND project_code_kpi='TB029221D0003'");
    $row2 = $query_project_kpi2[0];
    $res2 = $query_project_kpi2[1];
    do {
        $project_code = $row2['project_code_kpi'];
        $estimation = $row2['SB_project_estimation_implementation'];
        $estimation_cr = $row2['CR_project_estimation_implementation'];
        $estimation_wr = $row2['WR_project_estimation_implementation'];
        $duration_actual_wr = $row2['WR_duration_actual_implementation'];
        $mandays = $row2['SB_mandays_implementation'];
        $mandays_cr = $row2['CR_mandays_implementation'];
        $mandays_plan_wr = $row2['WR_mandays_plan_implementation'];
        $mandays_actual_wr = $row2['WR_mandays_actual_implementation'];
        $implementation_price = $row2['SB_amount_idr'];
        $start_date_project = $row2['WR_start_assignment_implementation'];
        $bast_date_project = $row2['WR_bast_date_project_implementation'];
        $timeline = $duration_actual_wr - $estimation_wr;
        if ($timeline >= 30 && $timeline < 90) {
            $get_param = $DBKPI->get_sql("SELECT value,value_type FROM sa_params WHERE category='Minor' AND type='Timeline'");
            $param1 = $get_param[0]['value'];
            $param2 = $get_param[0]['value_type'];
            $timeline_category = 'Minor';
            $value = $implementation_price / 1000000000;
            $timeline_result = $param1 * $param2;
        } else if ($timeline >= 90 && $timeline < 180) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Major'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Timeline'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $timeline_category = 'Major';
            $value = $implementation_price / 1000000000;
            $timeline_result = $param1 * $param2;
        } else if ($timeline >= 180) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Critical'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Timeline'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $timeline_category = 'Critical';
            $value = $implementation_price / 1000000000;
            $timeline_result = $param1 * $param2;
        } else if ($timeline < 30) {
            $timeline_category = 'Normal';
            $value = $implementation_price / 1000000000;
            $timeline_result = 0;
        }
        $commercial = $mandays_actual_wr - $mandays;
        $hasil_commercial = ($commercial / $mandays + 1) * 100;
        if ($hasil_commercial > 110 && $hasil_commercial <= 150) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Minor'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Commercial'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $commercial_category = 'Minor';
            $commercial_result = $param1 * $param2;
        } else if ($hasil_commercial > 150 && $hasil_commercial <= 200) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Major'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Commercial'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $commercial_category = 'Major';
            $commercial_result = $param1 * $param2;
        } else if ($hasil_commercial > 200) {
            $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Critical'");
            $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Commercial'");
            $param1 = $get_param1[0]['value'];
            $param2 = $get_param2[0]['value_type'];
            $commercial_category = 'Critical';
            $commercial_result = $param1 * $param2;
        } else if ($hasil_commercial < 100) {
            $commercial_category = 'Normal';
            $commercial_result = 0;
            $cte_project = 0 + $timeline_result;
        }
        $cte_project = $commercial_result + $timeline_result;
        $cte = 1 - $cte_project;
        $max_value = $value * 100;
        $weighted_value = $cte * $max_value;
        // echo $cte . '<' . $cte_project . '>' . '[' . (1 - $cte_project) . ']';
        // echo $project_id, $project_code_sb, $project_name_sb, $customer_name_sb, $bundling_sb, $amount_idr_sb, $amount_usd_sb;
        $insert_data = $DBKPI->get_sql("INSERT INTO sa_kpi_project_wr (project_code,value,start_assignment,bast,total,time_category,time_kpi,cte,total_cte,max_value,weighted_value,commercial_category,commercial_kpi) VALUES ('$project_code','$value','$start_date_project','$bast_date_project','$duration_actual_wr','$timeline_category','$timeline_result','$cte_project','$cte','$max_value','$weighted_value','$commercial_category','$commercial_result') ON DUPLICATE KEY UPDATE value = '$value', start_assignment = '$start_date_project', bast = '$bast_date_project', total = '$duration_actual_wr', time_category = '$timeline_category', time_kpi = '$timeline_result', cte = '$cte_project', total_cte = '$cte', max_value = '$max_value', weighted_value = '$weighted_value', commercial_category = '$commercial_category', commercial_kpi = '$commercial_result'");
    } while ($row2 = $res2->fetch_assoc());


    $query_get_user_kpi = $DBWR->get_sql("SELECT project_code,resource_email,roles,status,progress FROM `sa_view_resource_assignment`");
    $row3 = $query_get_user_kpi[0];
    $res3 = $query_get_user_kpi[1];
    do {
        $project_code_wr = $row3['project_code'];
        $resource_email = $row3['resource_email'];
        $roles = $row3['roles'];
        $status = $row3['status'];
        $progress = $row3['progress'];
        $get_ideal_project = $DBKPI->get_sql("SELECT max_value,total_cte,weighted_value FROM sa_kpi_project_wr WHERE project_code='$project_code_wr'");
        $max_value = $get_ideal_project[0]['max_value'];
        $total_cte = $get_ideal_project[0]['total_cte'];
        $weighted_value = $get_ideal_project[0]['weighted_value'];
        $get_data_pic = $DBWR->get_sql("SELECT start_actual FROM `sa_view_dashboard_wrike_resource` WHERE resource_email='$resource_email' AND project_code='$project_code_wr' ORDER BY start_actual ASC");
        $start_actual = $get_data_pic[0]['start_actual'];
        $get_data_pic2 = $DBWR->get_sql("SELECT finish_actual FROM `sa_view_dashboard_wrike_resource` WHERE resource_email='$resource_email' AND project_code='$project_code_wr' ORDER BY finish_actual DESC");
        $finish_actual = $get_data_pic2[0]['finish_actual'];
        $result = preg_replace("/%/", "", $progress);
        $hobi = explode("-", $result);
        $satu = $hobi[0];
        $dua = $hobi[1];
        $progress_final = $dua - $satu;
        $nilai_akhir_ideal = $max_value * ($progress_final / 100);
        $nilai_akhir_aktual = $nilai_akhir_ideal * $total_cte;
        $datetime1 = new DateTime($start_actual);
        $datetime2 = new DateTime($finish_actual);
        $difference = $datetime1->diff($datetime2);
        $duration = $difference->days;
        $hasil_durasi = $duration + 1;
        $nama_orang = $DBHCM->get_sql("SELECT employee_name FROM sa_view_employees WHERE employee_email='$resource_email'");
        $nama = $nama_orang[0]['employee_name'];
        $nama_email = $nama . " <" . $resource_email . ">";
        if ($project_code_wr == null || $resource_email == null || $roles == null || $status == null || $max_value == null || $total_cte == null || $weighted_value == null || $start_actual == null || $finish_actual == null || $hasil_durasi == null || $progress_final == null || $nilai_akhir_ideal == null || $nilai_akhir_aktual == null) {
            $data = "Indicative";
        } else {
            $data = "Completed";
        }
        $check_data = $DBKPI->get_sql("SELECT Nama,project_code FROM sa_user WHERE Nama LIKE '%$nama_email%' AND project_code = '$project_code_wr'");
        // echo $nama, $duration;
        // echo $nilai_akhir_ideal . '] [' . $nilai_akhir_aktual . '] [' . $progress_final . '] [' . $project_code_wr . '] [' . $resource_email . '] [' . $roles . '] [' . $status . '] [ ' . $start_date_plan . '] [' . $due_date_plan;
        if (empty($check_data[0]['Nama'])) {
            $insert_data_user_kpi = $DBKPI->get_sql("INSERT INTO sa_user (Nama,project_code,role,nilai_ideal,nilai_aktual,start_assignment,end_assignment,duration,progress,project_support,cte,nilai_akhir_ideal,nilai_akhir_aktual,status) VALUES ('$nama_email','$project_code_wr','$roles','$max_value','$weighted_value','$start_actual','$finish_actual','$hasil_durasi','$progress_final','$status','$total_cte','$nilai_akhir_ideal','$nilai_akhir_aktual','$data')");
        } else {
            echo 'Data Sudah Ada';
        }
    } while ($row3 = $res3->fetch_assoc());

    $query_get_kpi_user = $DBKPI->get_sql("SELECT Nama,project_code,role,SUM(nilai_ideal) as total_nilai_ideal,SUM(nilai_aktual) as total_nilai_aktual,start_assignment,end_assignment,duration,progress,project_support,cte,SUM(nilai_akhir_ideal) as total_nilai_akhir_ideal,SUM(nilai_akhir_aktual) as total_nilai_akhir_aktual,status FROM `sa_user` GROUP BY Nama");
    $row5 = $query_get_kpi_user[0];
    $res5 = $query_get_kpi_user[1];
    do {
        $nama = $row5['Nama'];
        $role = $row5['role'];
        $total_nilai_ideal = $row5['total_nilai_ideal'];
        $total_nilai_aktual = $row5['total_nilai_aktual'];
        $start_assignment = $row5['start_assignment'];
        $end_assignment = $row5['end_assignment'];
        $duration = $row5['duration'];
        $progress = $row5['progress'];
        $project_support = $row5['project_support'];
        $cte = $row5['cte'];
        $total_nilai_akhir_ideal = $row5['total_nilai_akhir_ideal'];
        $total_nilai_akhir_aktual = $row5['total_nilai_akhir_aktual'];
        $hasil_aktual_ideal = $total_nilai_aktual / $total_nilai_ideal * 100;
        if ($hasil_aktual_ideal == NAN) {
            $hasil_actual_ideal == 0;
        } else {
            $hasil_actual_ideal = $total_nilai_aktual / $total_nilai_ideal * 100;
        }
        $hasil_akhir_aktual_ideal = $total_nilai_akhir_aktual / $total_nilai_akhir_ideal * 100;
        if ($nama == NULL || $role == NULL || $total_nilai_ideal == null || $total_nilai_aktual == null || $start_assignment == null || $end_assignment == null || $duration == null || $progress == null || $project_support == null || $cte == null || $total_nilai_akhir_ideal == null || $total_nilai_akhir_aktual == null || $hasil_aktual_ideal == null || $hasil_akhir_aktual_ideal == null) {
            $data = "Indicative";
        } else {
            $data = "Completed";
        }
        $status = $row5['status'];
        // echo $nama, $duration;
        // echo $nilai_akhir_ideal . '] [' . $nilai_akhir_aktual . '] [' . $progress_final . '] [' . $project_code_wr . '] [' . $resource_email . '] [' . $roles . '] [' . $status . '] [ ' . $start_date_plan . '] [' . $due_date_plan;
        $insert_data_user_kpi = $DBKPI->get_sql("INSERT INTO sa_user_kpi (Nama,role,total_nilai_ideal,total_nilai_aktual,hasil_aktual_ideal,start_assignment,end_assignment,duration,progress,project_support,cte,total_nilai_akhir_ideal,total_nilai_akhir_aktual,hasil_akhir_aktual_ideal,status) VALUES ('$nama','$role','$total_nilai_ideal','$total_nilai_aktual','$hasil_actual_ideal','$start_assignment','$end_assignment','$duration','$progress','$project_support','$cte','$total_nilai_akhir_ideal','$total_nilai_akhir_aktual','$hasil_akhir_aktual_ideal','$data') ON DUPLICATE KEY UPDATE status='$data'");
    } while ($row5 = $res5->fetch_assoc());

    $query_get_kpi_user_summary = $DBKPI->get_sql("SELECT Nama,role,total_nilai_ideal,total_nilai_aktual,hasil_aktual_ideal,start_assignment,end_assignment,duration,progress,project_support,cte,total_nilai_akhir_ideal,total_nilai_akhir_aktual,hasil_akhir_aktual_ideal,status FROM `sa_user_kpi`");
    $row6 = $query_get_kpi_user_summary[0];
    $res6 = $query_get_kpi_user_summary[1];
    do {
        $nama = $row6['Nama'];
        $project = 80 / 100;
        $personal_assignment = 20 / 100;
        $hasil_akhir_aktual_ideal = $row6['hasil_akhir_aktual_ideal'];
        $nilai_ideal_project = $hasil_akhir_aktual_ideal * $project;
        $nilai_aktual_project = $hasil_akhir_aktual_ideal * $project;
        $nilai_ideal_personal_assignment = $hasil_akhir_aktual_ideal * $personal_assignment;
        $nilai_aktual_personal_assignment = $hasil_akhir_aktual_ideal * $personal_assignment;
        $total_nilai_ideal = $nilai_ideal_project + $nilai_ideal_personal_assignment;
        $total_nilai_aktual = $nilai_aktual_project + $nilai_aktual_personal_assignment;
        $get_data_project = $DBKPI->get_sql("SELECT COUNT(project_code) as jumlah_project FROM `sa_user` WHERE Nama='$nama'");
        $produktifitas = $get_data_project[0]['jumlah_project'];
        if ($nama == NULL || $project == NULL || $personal_assignment == null || $hasil_akhir_aktual_ideal == null || $nilai_ideal_project == null || $nilai_aktual_project == null || $nilai_ideal_personal_assignment == null || $nilai_aktual_personal_assignment == null || $total_nilai_ideal == null || $total_nilai_aktual == null || $produktifitas == null) {
            $data = "Indicative";
        } else {
            $data = "Completed";
        }
        $status = $row6['status'];
        // echo $nama, $duration;
        // echo $nilai_akhir_ideal . '] [' . $nilai_akhir_aktual . '] [' . $progress_final . '] [' . $project_code_wr . '] [' . $resource_email . '] [' . $roles . '] [' . $status . '] [ ' . $start_date_plan . '] [' . $due_date_plan;
        $insert_data_user_kpi = $DBKPI->get_sql("INSERT INTO sa_summary_user (Nama,project,personal_assignment,nilai_project,nilai_personal_assignment,total_nilai,produktifitas,status) VALUES ('$nama','$project','$personal_assignment','$nilai_ideal_project','$nilai_ideal_personal_assignment','$total_nilai_ideal','$produktifitas','$status') ON DUPLICATE KEY UPDATE status='$status'");
    } while ($row6 = $res6->fetch_assoc());
} elseif ($_GET['act'] == 'add') {

    $sbsb = "SERVICE_BUDGET";
    $DBSB1 = get_conn($sbsb);

    $wrwr = "WRIKE_INTEGRATE";
    $DBWR = get_conn($wrwr);

    $crcr = "CHANGE_REQUEST";
    $DBCR = get_conn($crcr);

    $tblname2 = 'kpi_project_wr';

    $query_project_list = $DBSB1->get_sql("SELECT project_id,project_code,project_name,customer_name,bundling, SUM(amount_idr) AS total_amount_idr, SUM(amount_usd) AS total_amount_usd FROM sa_trx_project_list WHERE STATUS = 'acknowledge' GROUP BY project_code ORDER BY project_id");
    $row = $query_project_list[0];
    $res = $query_project_list[1];
    do {
        $project_id = $row['project_id'];
        $project_code_sb = $row['project_code'];
        $project_name_sb = $row['project_name'];
        $customer_name_sb = $row['customer_name'];
        $bundling_sb = $row['bundling'];
        $amount_idr_sb = $row['total_amount_idr'];
        $amount_usd_sb = $row['total_amount_usd'];
        // echo $project_id, $project_code_sb, $project_name_sb, $customer_name_sb, $bundling_sb, $amount_idr_sb, $amount_usd_sb;
        $insert_data_pl = $DBKPI->get_sql("INSERT INTO sa_data_project (project_id,project_code_kpi,project_name,customer_name,SB_bundling,SB_amount_idr,SB_amount_usd) VALUES ('$project_id','$project_code_sb','$project_name_sb','$customer_name_sb','$bundling_sb','$amount_idr_sb','$amount_usd_sb') ON DUPLICATE KEY UPDATE SB_amount_idr='$amount_idr_sb', SB_amount_usd='$amount_usd_sb'");
    } while ($row = $res->fetch_assoc());


    $query_project_implement_implement = $DBSB1->get_sql("SELECT project_code,service_type, tos_category_id, DAY, SUM(implementation_price) AS total_implementation_price, SUM(bpd_price) AS total_bpd_price FROM sa_view_project_implementations WHERE status='acknowledge' AND service_type=1 GROUP BY project_code");
    $row2 = $query_project_implement_implement[0];
    $res2 = $query_project_implement_implement[1];
    do {
        $project_code_sb2 = $row2['project_code'];
        $service_type_sb = $row2['service_type'];
        $tos_category_id_sb = $row2['tos_category_id'];
        $DAY = isset($row2['DAY']);
        if ($DAY == NULL) {
            $DAY = 0;
        } else {
            $DAY = $row2['DAY'];
        }
        $total_implementation_price_sb = $row2['total_implementation_price'];
        $bpd_price_sb = isset($row2['total_bpd_price']);
        if ($bpd_price_sb == '') {
            $bpd_price_sb = 0;
        } else {
            $bpd_price_sb = $row2['total_bpd_price'];
        }
        // echo "++" . "-" . $service_type_sb . "-" . $tos_category_id_sb . "-" . $DAY . "-" . $total_implementation_price_sb . "-" . $bpd_price_sb;
        $insert_data_pi_im = $DBKPI->get_sql("INSERT INTO sa_data_project (project_code_kpi,SB_service_type_implementation, SB_project_category, SB_project_estimation_implementation, SB_implementation_price, SB_bpd_price_implementation) VALUES ('$project_code_sb2',1,'$tos_category_id_sb','$DAY',$total_implementation_price_sb,$bpd_price_sb) ON DUPLICATE KEY UPDATE SB_service_type_implementation=1, SB_project_category='$tos_category_id_sb', SB_project_estimation_implementation='$DAY', SB_implementation_price=$total_implementation_price_sb, SB_bpd_price_implementation=$bpd_price_sb");
    } while ($row2 = $res2->fetch_assoc());


    $query_project_implement_maintenance = $DBSB1->get_sql("SELECT project_code,service_type, tos_category_id, DAY, SUM(implementation_price) AS total_implementation_price, SUM(bpd_price) AS total_bpd_price, SUM(maintenance_package_price) AS total_maintenance_package_price, SUM(maintenance_addon_description) AS total_maintenance_addon_description FROM sa_view_project_implementations WHERE status='acknowledge' AND service_type=2 GROUP BY project_code");
    $row3 = $query_project_implement_maintenance[0];
    $res3 = $query_project_implement_maintenance[1];
    do {
        $project_code_sb3 = $row3['project_code'];
        $service_type_sb2 = $row3['service_type'];
        $tos_category_id_sb2 = $row3['tos_category_id'];
        $DAY2 = isset($row3['DAY']);
        if ($DAY2 == NULL) {
            $DAY2 = 0;
        } else {
            $DAY2 = $row3['DAY'];
        }
        $total_implementation_price_sb_mt = $row3['total_implementation_price'];
        $bpd_price_sb_mt = isset($row3['total_bpd_price']);
        if ($bpd_price_sb_mt == '') {
            $bpd_price_sb_mt = 0;
        } else {
            $bpd_price_sb_mt = $row3['total_bpd_price'];
        }
        $maintenance_package = $row3['total_maintenance_package_price'];
        $maintenance_addon_description = $row3['total_maintenance_addon_description'];
        // echo "++" . $service_type_sb2 . "-" . $DAY2 . "-" . $total_implementation_price_sb_mt . "-" . $bpd_price_sb_mt . "-" . $maintenance_package . "-" . $maintenance_addon_description;
        $insert_data_pi_mt = $DBKPI->get_sql("INSERT INTO sa_data_project (project_code_kpi,SB_service_type_maintenance, SB_project_estimation_maintenance, SB_maintenance_price, SB_bpd_price_maintenance, SB_maintenance_package_price, SB_addon_maintenance_price) VALUES ('$project_code_sb3','$service_type_sb2','$DAY2','$total_implementation_price_sb_mt','$bpd_price_sb_mt','$maintenance_package','$maintenance_addon_description') ON DUPLICATE KEY UPDATE SB_service_type_maintenance='$service_type_sb2', SB_project_estimation_maintenance='$DAY2', SB_maintenance_price=$total_implementation_price_sb_mt, SB_bpd_price_maintenance=$bpd_price_sb_mt, SB_maintenance_package_price=$maintenance_package, SB_addon_maintenance_price=$maintenance_addon_description");
    } while ($row3 = $res3->fetch_assoc());


    $query_project_implement_warranty = $DBSB1->get_sql("SELECT project_code,service_type, DAY FROM sa_view_project_implementations WHERE status='acknowledge' AND service_type=3 GROUP BY project_code");
    $row4 = $query_project_implement_warranty[0];
    $res4 = $query_project_implement_warranty[1];
    do {
        $project_code_sb_wr = $row4['project_code'];
        $service_type_sb_wr = $row4['service_type'];
        $DAY3 = isset($row4['DAY']);
        if ($DAY3 == NULL) {
            $DAY3 = 0;
        } else {
            $DAY3 = $row4['DAY'];
        }
        // echo "++" . $service_type_sb2 . "-" . $DAY2 . "-" . $total_implementation_price_sb_mt . "-" . $bpd_price_sb_mt . "-" . $maintenance_package . "-" . $maintenance_addon_description;
        $insert_data_pi_mt = $DBKPI->get_sql("INSERT INTO sa_data_project (project_code_kpi,SB_service_type_warranty, SB_project_estimation_warranty) VALUES ('$project_code_sb_wr','$service_type_sb_wr','$DAY3') ON DUPLICATE KEY UPDATE SB_service_type_warranty='$service_type_sb_wr', SB_project_estimation_warranty='$DAY3'");
    } while ($row4 = $res4->fetch_assoc());


    $query_project_mandays_imp = $DBSB1->get_sql("SELECT project_code,SUM(mandays*mantotal*value) AS total_mandays FROM `sa_view_project_mandays` WHERE status='acknowledge' AND service_type=1 GROUP BY project_code");
    $row5 = $query_project_mandays_imp[0];
    $res5 = $query_project_mandays_imp[1];
    do {
        $project_code_sb_mandays = $row5['project_code'];
        $mandays_imp = isset($row5['total_mandays']);
        if ($mandays_imp == NULL) {
            $mandays_imp = 0;
        } else {
            $mandays_imp = $row5['total_mandays'];
        }
        // echo "++" . $service_type_sb2 . "-" . $DAY2 . "-" . $total_implementation_price_sb_mt . "-" . $bpd_price_sb_mt . "-" . $maintenance_package . "-" . $maintenance_addon_description;
        $insert_data_man_imp = $DBKPI->get_sql("INSERT INTO sa_data_project (project_code_kpi,SB_mandays_implementation) VALUES ('$project_code_sb_mandays','$mandays_imp') ON DUPLICATE KEY UPDATE SB_mandays_implementation='$mandays_imp'");
    } while ($row5 = $res5->fetch_assoc());


    $query_project_mandays_mt1 = $DBSB1->get_sql("SELECT project_code,SUM(mandays) AS total_mandays FROM `sa_view_project_mandays` WHERE status='acknowledge' AND service_type=2 AND resource_level LIKE '1%' GROUP BY project_code");
    $row6 = $query_project_mandays_mt1[0];
    $res6 = $query_project_mandays_mt1[1];
    do {
        $project_code_sb_mandays_mt1 = $row6['project_code'];
        $mandays_mt1 = isset($row6['total_mandays']);
        if ($mandays_mt1 == NULL) {
            $mandays_mt1 = 0;
        } else {
            $mandays_mt1 = $row6['total_mandays'];
        }
        // echo "++" . $service_type_sb2 . "-" . $DAY2 . "-" . $total_implementation_price_sb_mt . "-" . $bpd_price_sb_mt . "-" . $maintenance_package . "-" . $maintenance_addon_description;
        $insert_data_man_mt1 = $DBKPI->get_sql("INSERT INTO sa_data_project (project_code_kpi,SB_existing_bu_price) VALUES ('$project_code_sb_mandays_mt1','$mandays_mt1') ON DUPLICATE KEY UPDATE SB_existing_bu_price='$mandays_mt1'");
    } while ($row6 = $res6->fetch_assoc());


    $query_project_mandays_mt2 = $DBSB1->get_sql("SELECT project_code,SUM(mandays) AS total_mandays FROM `sa_view_project_mandays` WHERE status='acknowledge' AND service_type=2 AND resource_level LIKE '2%' GROUP BY project_code");
    $row7 = $query_project_mandays_mt2[0];
    $res7 = $query_project_mandays_mt2[1];
    do {
        $project_code_sb_mandays_mt2 = $row7['project_code'];
        $mandays_mt2 = isset($row7['total_mandays']);
        if ($mandays_mt2 == NULL) {
            $mandays_mt2 = 0;
        } else {
            $mandays_mt2 = $row7['total_mandays'];
        }
        // echo "++" . $service_type_sb2 . "-" . $DAY2 . "-" . $total_implementation_price_sb_mt . "-" . $bpd_price_sb_mt . "-" . $maintenance_package . "-" . $maintenance_addon_description;
        $insert_data_man_mt2 = $DBKPI->get_sql("INSERT INTO sa_data_project (project_code_kpi,SB_investment_bu_price) VALUES ('$project_code_sb_mandays_mt2','$mandays_mt2') ON DUPLICATE KEY UPDATE SB_investment_bu_price='$mandays_mt2'");
    } while ($row7 = $res7->fetch_assoc());


    $query_project_mandays_wr1 = $DBSB1->get_sql("SELECT project_code,SUM(mandays) AS total_mandays FROM `sa_view_project_mandays` WHERE status='acknowledge' AND service_type=3 AND resource_level LIKE '1%' GROUP BY project_code");
    $row8 = $query_project_mandays_wr1[0];
    $res8 = $query_project_mandays_wr1[1];
    do {
        $project_code_sb_mandays_wr1 = $row8['project_code'];
        $mandays_wr1 = isset($row8['total_mandays']);
        if ($mandays_wr1 == NULL) {
            $mandays_wr1 = 0;
        } else {
            $mandays_wr1 = $row8['total_mandays'];
        }
        // echo "++" . $service_type_sb2 . "-" . $DAY2 . "-" . $total_implementation_price_sb_mt . "-" . $bpd_price_sb_mt . "-" . $maintenance_package . "-" . $maintenance_addon_description;
        $insert_data_man_wr1 = $DBKPI->get_sql("INSERT INTO sa_data_project (project_code_kpi,SB_warranty_cisco_price) VALUES ('$project_code_sb_mandays_wr1','$mandays_wr1') ON DUPLICATE KEY UPDATE SB_warranty_cisco_price='$mandays_wr1'");
    } while ($row8 = $res8->fetch_assoc());


    $query_project_mandays_wr2 = $DBSB1->get_sql("SELECT project_code,SUM(mandays) AS total_mandays FROM `sa_view_project_mandays` WHERE status='acknowledge' AND service_type=3 AND resource_level LIKE '2%' GROUP BY project_code");
    $row9 = $query_project_mandays_wr2[0];
    $res9 = $query_project_mandays_wr2[1];
    do {
        $project_code_sb_mandays_wr2 = $row9['project_code'];
        $mandays_wr2 = isset($row9['total_mandays']);
        if ($mandays_wr2 == NULL) {
            $mandays_wr2 = 0;
        } else {
            $mandays_wr2 = $row9['total_mandays'];
        }
        // echo "++" . $service_type_sb2 . "-" . $DAY2 . "-" . $total_implementation_price_sb_mt . "-" . $bpd_price_sb_mt . "-" . $maintenance_package . "-" . $maintenance_addon_description;
        $insert_data_man_wr2 = $DBKPI->get_sql("INSERT INTO sa_data_project (project_code_kpi,SB_warranty_noncisco_price) VALUES ('$project_code_sb_mandays_wr2','$mandays_wr2') ON DUPLICATE KEY UPDATE SB_warranty_noncisco_price='$mandays_wr2'");
    } while ($row9 = $res9->fetch_assoc());


    $query_project_addon_imp = $DBSB1->get_sql("SELECT project_code,SUM(addon_price) AS total_addon FROM `sa_view_addon` WHERE status='acknowledge' AND service_type=1 GROUP BY project_code");
    $row10 = $query_project_addon_imp[0];
    $res10 = $query_project_addon_imp[1];
    do {
        $project_code_sb_addon_imp = $row10['project_code'];
        $addon_imp = isset($row10['total_addon']);
        if ($addon_imp == NULL) {
            $addon_imp = 0;
        } else {
            $addon_imp = $row10['total_addon'];
        }
        // echo "++" . $service_type_sb2 . "-" . $DAY2 . "-" . $total_implementation_price_sb_mt . "-" . $bpd_price_sb_mt . "-" . $maintenance_package . "-" . $maintenance_addon_description;
        $insert_data_man_wr2 = $DBKPI->get_sql("INSERT INTO sa_data_project (project_code_kpi,SB_outsourcing_implementation_price) VALUES ('$project_code_sb_addon_imp','$addon_imp') ON DUPLICATE KEY UPDATE SB_outsourcing_implementation_price='$addon_imp'");
    } while ($row10 = $res10->fetch_assoc());


    $query_project_addon_mt = $DBSB1->get_sql("SELECT project_code,SUM(addon_price) AS total_addon FROM `sa_view_addon` WHERE status='acknowledge' AND service_type=2 GROUP BY project_code");
    $row11 = $query_project_addon_mt[0];
    $res11 = $query_project_addon_mt[1];
    do {
        $project_code_sb_addon_mt = $row11['project_code'];
        $addon_mt = isset($row11['total_addon']);
        if ($addon_mt == NULL) {
            $addon_mt = 0;
        } else {
            $addon_mt = $row11['total_addon'];
        }
        // echo "++" . $service_type_sb2 . "-" . $DAY2 . "-" . $total_implementation_price_sb_mt . "-" . $bpd_price_sb_mt . "-" . $maintenance_package . "-" . $maintenance_addon_description;
        $insert_data_man_wr2 = $DBKPI->get_sql("INSERT INTO sa_data_project (project_code_kpi,SB_outsourcing_maintenance_price) VALUES ('$project_code_sb_addon_mt','$addon_mt') ON DUPLICATE KEY UPDATE SB_outsourcing_maintenance_price='$addon_mt'");
    } while ($row11 = $res11->fetch_assoc());


    $query_get_wrike_estimation = $DBWR->get_sql("SELECT project_code,start_date_project,finish_date_project,addendum_bast,po_bast,kom_bast,cr_bast,sbf_bast,contract_bast,document_bast FROM `sa_view_dashboard_wrike_project` GROUP BY project_code");
    $row12 = $query_get_wrike_estimation[0];
    $res12 = $query_get_wrike_estimation[1];
    do {
        $project_code_wr = $row12['project_code'];
        $start_date_project = $row12['start_date_project'];
        $finish_date_project = $row12['finish_date_project'];
        $bast_date_project1 = $row12['contract_bast'];
        $bast_date_project2 = $row12['sbf_bast'];
        $bast_date_project3 = $row12['cr_bast'];
        $bast_date_project4 = $row12['kom_bast'];
        $bast_date_project5 = $row12['addendum_bast'];
        $bast_date_project6 = $row12['po_bast'];
        $bast_date_actual = $row12['document_bast'];

        if ($bast_date_project5 != NULL || $bast_date_project5 != '') {
            $bast_date_project = $row12['addendum_bast'];
        } else if ($bast_date_project3 != NULL || $bast_date_project3 != '') {
            $bast_date_project = $row12['cr_bast'];
        } else if ($bast_date_project1 != NULL || $bast_date_project1 != '') {
            $bast_date_project = $row12['contract_bast'];
        } else if ($bast_date_project6 != NULL || $bast_date_project6 != '') {
            $bast_date_project = $row12['po_bast'];
        } else if ($bast_date_project4 != NULL || $bast_date_project4 != '') {
            $bast_date_project = $row12['kom_bast'];
        } else if ($bast_date_project2 != NULL || $bast_date_project2 != '') {
            $bast_date_project = $row12['sbf_bast'];
        } else {
            $bast_date_project = NULL;
        }

        $start = new DateTime($start_date_project);
        $finish = new DateTime($bast_date_project);
        $bast = new DateTime($bast_date_actual);
        $difference = $start->diff($finish);
        $duration = $difference->days;
        $difference2 = $start->diff($bast);
        $duration2 = $difference2->days;
        $project_duration_wrike = $duration + 1;
        $project_duration_actual_wrike = $duration2 + 1;
        // echo "++" . $service_type_sb2 . "-" . $DAY2 . "-" . $total_implementation_price_sb_mt . "-" . $bpd_price_sb_mt . "-" . $maintenance_package . "-" . $maintenance_addon_description;
        $insert_data_est_wr = $DBKPI->get_sql("INSERT INTO sa_data_project (project_code_kpi,WR_service_type_implementation,WR_start_assignment_implementation,WR_bast_date_project_implementation,WR_bast_date_actual_project_implementation,WR_project_estimation_implementation,WR_duration_actual_implementation) VALUES ('$project_code_wr',1,'$start_date_project','$bast_date_project','$bast_date_actual','$project_duration_wrike','$project_duration_actual_wrike') ON DUPLICATE KEY UPDATE WR_start_assignment_implementation='$start_date_project', WR_bast_date_project_implementation='$bast_date_project', WR_bast_date_actual_project_implementation='$bast_date_actual', WR_project_estimation_implementation='$project_duration_wrike', WR_duration_actual_implementation='$project_duration_actual_wrike'");
    } while ($row12 = $res12->fetch_assoc());


    $query_get_wrike_mandays = $DBWR->get_sql("SELECT project_code,SUM((jumlah_actual/60/8)*value) AS mandays_actual_wrike,SUM((duration_planning/60/8)*value) AS mandays_plan_wrike FROM `sa_view_dashboard_wrike_resource` GROUP BY project_code");
    $row13 = $query_get_wrike_mandays[0];
    $res13 = $query_get_wrike_mandays[1];
    do {
        $project_code_wr = $row13['project_code'];
        $mandays = $row13['mandays_actual_wrike'];
        $mandays_plan = $row13['mandays_plan_wrike'];
        // echo "++" . $service_type_sb2 . "-" . $DAY2 . "-" . $total_implementation_price_sb_mt . "-" . $bpd_price_sb_mt . "-" . $maintenance_package . "-" . $maintenance_addon_description;
        $insert_data_man_wr = $DBKPI->get_sql("INSERT INTO sa_data_project (project_code_kpi,WR_mandays_actual_implementation,WR_mandays_plan_implementation) VALUES ('$project_code_wr','$mandays','$mandays_plan') ON DUPLICATE KEY UPDATE WR_mandays_actual_implementation='$mandays', WR_mandays_plan_implementation='$mandays_plan'");
    } while ($row13 = $res13->fetch_assoc());


    $query_get_cr_data = $DBCR->get_sql("SELECT project_code,SUM(project_duration_cr) AS duration_cr,SUM(mandays_total) AS mandays_cr FROM `sa_view_dashboard_cr` GROUP BY project_code");
    $row14 = $query_get_cr_data[0];
    $res14 = $query_get_cr_data[1];
    do {
        $project_code_cr = $row14['project_code'];
        $duration_cr = $row14['duration_cr'];
        $mandays_cr = isset($row14['mandays_cr']);
        if ($mandays_cr == NULL) {
            $mandays_cr = 0;
        } else {
            $mandays_cr = $row14['mandays_cr'];
        }
        // echo "++" . $service_type_sb2 . "-" . $DAY2 . "-" . $total_implementation_price_sb_mt . "-" . $bpd_price_sb_mt . "-" . $maintenance_package . "-" . $maintenance_addon_description;
        $insert_data_man_wr = $DBKPI->get_sql("INSERT INTO sa_data_project (project_code_kpi,CR_project_estimation_implementation,CR_mandays_implementation) VALUES ('$project_code_cr','$duration_cr','$mandays_cr') ON DUPLICATE KEY UPDATE CR_project_estimation_implementation='$duration_cr', CR_mandays_implementation='$mandays_cr'");
    } while ($row14 = $res14->fetch_assoc());
} elseif ($_GET['act'] == 'ganti') {
    $nama = $_GET['nama'];
    $idaja = str_replace("&20", " ", $nama);
    $id = preg_replace("/[']/", "", $idaja);
    $idorang = str_replace("[_]", " ", $id);
    $hobi = explode("<", $idorang);

    $data = $DBKPI->get_sql("SELECT * FROM sa_summary_user WHERE Nama LIKE '%$hobi[0]%'");
    $project = $data[0]['project'] * 100;
    $personal_assignment = $data[0]['personal_assignment'] * 100;
?>
    <form action="index.php?mod=<?php echo $_GET['mod'];
                                ?>" enctype="multipart/form-data">
        <label for="nama">Nama : </label>
        <label for="namaa"><?php echo $hobi[0]; ?></label>
        <br><label for="project">Project :</label>
        <input type="text" id="project" name="project" value="<?php echo $project; ?>"> %<br>
        <label for="personal_assignment">Personal Assignment :</label>
        <input type="text" id="personal_assignment" name="personal_assignment" value="<?php echo $personal_assignment; ?>"> %<br><br>
        <input type="submit" value="Submit">
    </form>
<?php
}
// elseif ($_GET['act'] == 'data_orang') {
//     $nama = $_GET['nama'];
//     $idaja = str_replace("&20", " ", $nama);
//     $id = preg_replace("/[']/", "", $idaja);
//     $idorang = str_replace("[_]", " ", $id);
//     $hobi = explode("<", $idorang);
?>
<!-- <html>

    <head>
        <title>Export to Excel</title>
    </head>

    <body>
        <label>Export :</label>
        <a href="export.php?nama='<?php //echo $idorang; 
                                    ?>'"><button>Export to Excel</button></a><br />
        <hr /> -->
<?php
// include 'data_orang.php';
// $data2 = $DBKPI->get_sql("SELECT * FROM sa_user WHERE Nama LIKE '%$hobi[0]%'");
?>
<!-- <div class="col-lg-12">
            <table border=" 1">
                <tr>
                    <th>Nama</th>
                    <th><?php // echo $data2[0]['Nama']; 
                        ?></th>
                </tr>
                <tr>
                    <th>Project Code</th>
                    <th>Role</th>
                    <th>Nilai Ideal</th>
                    <th>Nilai Aktual</th>
                    <th>Start Assignment</th>
                    <th>End Assignment</th>
                    <th>Duration</th>
                    <th>Project Progress Saat Mutasi</th>
                    <th>Project Support</th>
                    <th>Adjusted CTE Score</th>
                    <th>Nilai Akhir Ideal</th>
                    <th>Nilai Akhir Aktual</th>
                    <th>Status</th>
                </tr> -->
<?php
// $data = $DBKPI->get_sql("SELECT * FROM sa_user WHERE Nama LIKE '%$hobi[0]%'");
// do {
//     $cte = number_format($data[0]['cte'], 3);
//     $nilai_ideal = number_format($data[0]['nilai_ideal'], 5, ",", ".");
//     $nilai_aktual = number_format($data[0]['nilai_aktual'], 5, ",", ".");
//     $duration = number_format($data[0]['duration'], 0, ",", ".");
//     $progress = number_format($data[0]['progress'], 0);
//     $nilai_akhir_aktual = number_format($data[0]['nilai_akhir_aktual'], 5, ",", ".");
//     $nilai_akhir_ideal = number_format($data[0]['nilai_akhir_ideal'], 5, ",", ".");
?>
<!-- <tr>
                        <td><?php // echo $data[0]['project_code']; 
                            ?></td>
                        <td><?php // echo $data[0]['role']; 
                            ?></td>
                        <td><?php // echo $nilai_ideal; 
                            ?></td>
                        <td><?php // echo $nilai_aktual; 
                            ?></td>
                        <td><?php // echo $data[0]['start_assignment']; 
                            ?></td>
                        <td><?php // echo $data[0]['end_assignment']; 
                            ?></td>
                        <td><?php // echo $duration; 
                            ?></td>
                        <td><?php // echo $progress . "%"; 
                            ?></td>
                        <td><?php // echo $data[0]['project_support']; 
                            ?></td>
                        <td><?php // echo $cte * 100 . "%"; 
                            ?></td>
                        <td><?php // echo $nilai_akhir_ideal; 
                            ?></td>
                        <td><?php // echo $nilai_akhir_aktual; 
                            ?></td>
                        <td><?php // echo $data[0]['status']; 
                            ?></td>
                    </tr>
                <?php
                // } while ($data[0] = $data[1]->fetch_assoc())
                ?>
            </table>
        </div> -->
<?php
// }
