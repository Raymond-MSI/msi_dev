<?php
    function syncKPI() {
       set_time_limit(400);

        $dbdb = "DASHBOARD_KPI";
    $DBKPI = get_conn($dbdb);

    $sbsb = "SERVICE_BUDGET";
    $DBSB = get_conn($sbsb);

    $wrwr = "WRIKE_INTEGRATE";
    $DBWR = get_conn($wrwr);

    $hcm = "HCM";
    $DBHCM = get_conn($hcm);
    

        $query_project_kpi_so = $DBKPI->get_sql("SELECT project_code_kpi,periode_so,customer_name,so_number_kpi,SB_project_estimation_implementation,SB_mandays_implementation,SB_maintenance_price,SB_warranty_price,CR_project_estimation_implementation,CR_mandays_implementation,SB_amount_idr,WR_project_estimation_implementation,WR_duration_actual_implementation,WR_mandays_plan_implementation,WR_mandays_actual_implementation,WR_start_assignment_implementation,WR_bast_date_actual_project_implementation,periode,WR_status_project,status,WR_bast_date_project_implementation,kpi_status FROM sa_data_so WHERE SB_service_type_implementation=1");
        $row_so = $query_project_kpi_so[0];
        $res_so = $query_project_kpi_so[1];
        do {
            $project_code = $row_so['project_code_kpi'];
            $periode = $row_so['periode'];
            if ($periode == null) {
                $periode = 0;
            } else {
                $periode = $row_so['periode'];
            }
            $periode_so = $row_so['periode_so'];
            if ($periode_so == null) {
                $periode_so = 0;
            } else {
                $periode_so = $row_so['periode_so'];
            }
            $so_number = $row_so['so_number_kpi'];
            $kpi_status = $row_so['kpi_status'];
            $estimation = $row_so['SB_project_estimation_implementation'];
            $estimation_cr = $row_so['CR_project_estimation_implementation'];
            $estimation_wr = $row_so['WR_project_estimation_implementation'];
            $duration_actual_wr = $row_so['WR_duration_actual_implementation'];
            $po_maintenance = $row_so['SB_maintenance_price'];
            $po_warranty = $row_so['SB_warranty_price'];
            $mandays = $row_so['SB_mandays_implementation'];
            $mandays_cr = $row_so['CR_mandays_implementation'];
            $mandays_plan_wr = $row_so['WR_mandays_plan_implementation'];
            $mandays_actual_wr = $row_so['WR_mandays_actual_implementation'];
            $implementation_price = $row_so['SB_amount_idr'];
            $start_date_project = $row_so['WR_start_assignment_implementation'];
            $bast_plan = $row_so['WR_bast_date_project_implementation'];
            $bast_date_project = $row_so['WR_bast_date_actual_project_implementation'];
            $customer_name = $row_so['customer_name'];
            
            if (!empty($bast_plan) && !empty($bast_date_project)) {
                $test1 = strtotime("$bast_date_project");
                $test2 = strtotime("$bast_plan");
                $jarak = $test1 - $test2;
                $timeline = $jarak / 60 / 60 / 24;
                if ($timeline > 30 && $timeline < 90) {
                $get_param = $DBKPI->get_sql("SELECT value,value_type FROM sa_params WHERE category='Minor' AND type='Timeline'");
                $param1 = $get_param[0]['value'];
                $param2 = $get_param[0]['value_type'];
                $timeline_category = 'Minor';
                $timeline_result = $param1 * $param2;
            } else if ($timeline >= 90 && $timeline < 180) {
                $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Major'");
                $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Timeline'");
                $param1 = $get_param1[0]['value'];
                $param2 = $get_param2[0]['value_type'];
                $timeline_category = 'Major';
                $timeline_result = $param1 * $param2;
            } else if ($timeline >= 180) {
                $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Critical'");
                $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Timeline'");
                $param1 = $get_param1[0]['value'];
                $param2 = $get_param2[0]['value_type'];
                $timeline_category = 'Critical';
                $timeline_result = $param1 * $param2;
            } else if ($timeline < 0 || $timeline == 0) {
                $timeline_category = 'Normal';
                $timeline_result = 0;
            } else {
                $timeline_category = 'Normal';
                $timeline_result = 0;
            }
            } else if (empty($bast_plan) && empty($bast_date_project) || !empty($bast_plan) && empty($bast_date_project)) {
                $timeline = 'Empty';
                if ($timeline == 'Empty') {
                $timeline_category = 'Empty';
                $timeline_result = 0;
            }
            } else if (empty($bast_plan) && !empty($bast_date_project)) {
                $timeline = 'Critical';
                if ($timeline == 'Critical') {
                $get_param1 = $DBKPI->get_sql("SELECT value FROM sa_params WHERE category='Critical'");
                $get_param2 = $DBKPI->get_sql("SELECT value_type FROM sa_params WHERE type='Timeline'");
                $param1 = $get_param1[0]['value'];
                $param2 = $get_param2[0]['value_type'];
                $timeline_category = 'Critical';
                $timeline_result = $param1 * $param2;
            }
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
            } else if ($hasil_commercial < 110 || $commercial == 0) {
                $commercial_category = 'Normal';
                $commercial_result = 0;
            }

            $error_category = 'Empty';
            $error_result = '0.0';
            if ($commercial_category == 'Empty' && $timeline_category == 'Empty' && $error_category == 'Empty') {
                $cte_project = 0.0 + 0.0 + 0.0;
            } elseif ($commercial_category != 'Empty' && $timeline_category == 'Empty' && $error_category == 'Empty') {
                $cte_project = $commercial_result + 0.0 + 0.0;
            } elseif ($commercial_category == 'Empty' && $timeline_category != 'Empty' && $error_category == 'Empty') {
                $cte_project = 0.0 + $timeline_result + 0.0;
            } elseif ($commercial_category != 'Empty' && $timeline_category != 'Empty' && $error_category == 'Empty') {
                $cte_project = $commercial_result + $timeline_result + 0.0;
            }

            $cte = 1 - $cte_project;
            $value = ($implementation_price - $po_maintenance - $po_warranty) / 1000000000;
            $max_value = $value * 1;
            $weighted_value = $cte * $max_value;
            $status_wr = $row_so['WR_status_project'];
            $status_project = $row_so['status'];
            $sqll = $DBSB->get_sql("SELECT project_code,so_number,project_name_internal FROM sa_trx_project_list WHERE project_code='$project_code' GROUP BY project_code");
            $project_name = $sqll[0]['project_name_internal'];
            if ($so_number == null) {
                '';
            } else {
                $mysql = sprintf(
                    "(`project_code`,`customer_name`,`so_number`,`project_name`,`value`,`start_assignment`,`bast_plan`,`bast_actual`,`total`,`time_category`,`time_kpi`,`cte`,`total_cte`,`max_value`,`weighted_value`,`commercial_category`,`commercial_kpi`,`periode`,`periode_so`,`status_wr`,`status_project`,`error_category`,`error_kpi`,`kpi_status`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s) ON DUPLICATE KEY UPDATE value = %s, start_assignment = %s, bast_plan=%s, bast_actual = %s, total = %s, time_category = %s, time_kpi = %s, cte = %s, total_cte = %s, max_value = %s, weighted_value = %s, commercial_category = %s, commercial_kpi = %s, periode = %s, periode_so= %s, status_wr = %s, status_project= %s, error_kpi=%s, kpi_status=%s, error_category=%s",
                    GetSQLValueString($project_code, "text"),
                    GetSQLValueString($customer_name, "text"),
                    GetSQLValueString($so_number, "text"),
                    GetSQLValueString($project_name, "text"),
                    GetSQLValueString(number_format($value, 5, ".", ""), "text"),
                    GetSQLValueString($start_date_project, "date"),
                    GetSQLValueString($bast_plan, "date"),
                    GetSQLValueString($bast_date_project, "date"),
                    GetSQLValueString(number_format($estimation_wr, 5, ".", ""), "text"),
                    GetSQLValueString($timeline_category, "text"),
                    GetSQLValueString(number_format($timeline_result, 5, ".", ""), "text"),
                    GetSQLValueString(number_format($cte_project, 6, ".", ""), "text"),
                    GetSQLValueString(number_format($cte, 6, ".", ""), "text"),
                    GetSQLValueString(number_format($max_value, 5, ".", ""), "text"),
                    GetSQLValueString(number_format($weighted_value, 5, ".", ""), "text"),
                    GetSQLValueString($commercial_category, "text"),
                    GetSQLValueString(number_format($commercial_result, 5, ".", ""), "text"),
                    GetSQLValueString($periode, "int"),
                    GetSQLValueString($periode_so, "int"),
                    GetSQLValueString($status_wr, "text"),
                    GetSQLValueString($status_project, "text"),
                    GetSQLValueString($error_category, "text"),
                    GetSQLValueString(number_format($error_result, 5, ".", ""), "text"),
                    GetSQLValueString($kpi_status, "text"),
                    GetSQLValueString(number_format($value, 5, ".", ""), "text"),
                    GetSQLValueString($start_date_project, "date"),
                    GetSQLValueString($bast_plan, "date"),
                    GetSQLValueString($bast_date_project, "date"),
                    GetSQLValueString(number_format($estimation_wr, 2, ".", ""), "text"),
                    GetSQLValueString($timeline_category, "text"),
                    GetSQLValueString(number_format($timeline_result, 5, ".", ""), "text"),
                    GetSQLValueString(number_format($cte_project, 6, ".", ""), "text"),
                    GetSQLValueString(number_format($cte, 6, ".", ""), "text"),
                    GetSQLValueString(number_format($max_value, 5, ".", ""), "text"),
                    GetSQLValueString(number_format($weighted_value, 5, ".", ""), "text"),
                    GetSQLValueString($commercial_category, "text"),
                    GetSQLValueString(number_format($commercial_result, 2, ".", ""), "text"),
                    GetSQLValueString($periode, "int"),
                    GetSQLValueString($periode_so, "int"),
                    GetSQLValueString($status_wr, "text"),
                    GetSQLValueString($status_project, "text"),
                    GetSQLValueString(number_format($error_result, 2, ".", ""), "text"),
                    GetSQLValueString($kpi_status, "text"),
                    GetSQLValueString($error_category, "text")
                );
                $DBKPI->insert_data("kpi_so_wr", $mysql);
            }
        } while ($row_so = $res_so->fetch_assoc());

        $sql_wr_user = $DBWR->get_sql("SELECT a.project_code,a.resource_email,a.resource_role,a.start_actual,a.finish_actual,b.project_type, b.no_so FROM `sa_view_dashboard_wrike_resource` a left join sa_wrike_project_list b ON a.project_id=b.id");
        do {
            $project_code = $sql_wr_user[0]['project_code'];
            $resource_email = $sql_wr_user[0]['resource_email'];
            $resource_role = $sql_wr_user[0]['resource_role'];
            $start_assignment = $sql_wr_user[0]['start_actual'];
            $end_assignment = $sql_wr_user[0]['finish_actual'];
            $project_type = $sql_wr_user[0]['project_type'];
            $so_number = $sql_wr_user[0]['no_so'];
            $datetime1 = new DateTime($start_assignment);
            $datetime2 = new DateTime($end_assignment);
            $difference = $datetime1->diff($datetime2);
            $duration = $difference->days;
            $hasil_durasi = $duration + 1;
            $nama_orang = $DBHCM->get_sql("SELECT employee_name FROM sa_view_employees WHERE employee_email='$resource_email'");
            $nama = $nama_orang[0]['employee_name'];
            if ($resource_email == 'eko.ratno01@mastersystem.co.id') {
                $nama_email = "Eko Ratno <eko.ratno01@mastersystem.co.id>";
            } else if ($resource_email == 'ficky_admin@mastersystem.co.id') {
                $nama_email = "Ficky Admin <ficky_admin@mastersystem.co.id>";
            } else if ($resource_email == 'aldino.delama01@mastersystem.co.id') {
                $nama_email = "Aldino De Lama <aldino.delama@mastersystem.co.id>";
            } else if ($resource_email == 'arfend.atma01@mastersystem.co.id') {
                $nama_email = "Arfend Atma Maulana Khalifa <arfend.atma@mastersystem.co.id>";
            } else if ($resource_email == ' arfend.atma01@mastersystem.co.id') {
                $nama_email = "Arfend Atma Maulana Khalifa <arfend.atma@mastersystem.co.id>";
            } else {
                $nama_email = rtrim($nama," ") . " <" . $resource_email . ">";
            }
            if(empty($so_number)){
                $get_ordernum = $DBSB->get_sql("SELECT * FROM sa_trx_project_list WHERE project_code='$project_code'");
                $order_number = $get_ordernum[0]['order_number'];
                $sqll = $DBSB->get_sql("SELECT project_name_internal,customer_name FROM sa_trx_project_list WHERE so_number='$order_number'");
                $get_ideal_project = $DBKPI->get_sql("SELECT max_value,total_cte,weighted_value,periode,periode_so FROM sa_kpi_so_wr WHERE so_number='$order_number'");
                $get_status = $DBKPI->get_sql("SELECT WR_status_project,status,kpi_status FROM sa_data_so WHERE so_number_kpi='$order_number'");
            } else {
                $sqll = $DBSB->get_sql("SELECT project_name_internal,customer_name FROM sa_trx_project_list WHERE so_number='$so_number'");
                $get_ideal_project = $DBKPI->get_sql("SELECT max_value,total_cte,weighted_value,periode,periode_so FROM sa_kpi_so_wr WHERE so_number='$so_number'");
                $get_status = $DBKPI->get_sql("SELECT WR_status_project,status,kpi_status FROM sa_data_so WHERE so_number_kpi='$so_number'");
            }
            $status_wr = $get_status[0]['WR_status_project'];
            $status_project = $get_status[0]['status'];
            $kpi_status = $get_status[0]['kpi_status'];
            $max_value = $get_ideal_project[0]['max_value'];
            if (empty($max_value)) {
                $max_value = 0;
            } else {
                $max_value = $get_ideal_project[0]['max_value'];
            }
            $total_cte = $get_ideal_project[0]['total_cte'];
            if (empty($total_cte)) {
                $total_cte = 0;
            } else {
                $total_cte = $get_ideal_project[0]['total_cte'];
            }
            $periode = $get_ideal_project[0]['periode'];
            if (empty($periode)) {
                $periode = 0;
            } else {
                $periode = $get_ideal_project[0]['periode'];
            }
            $periode_so = $get_ideal_project[0]['periode_so'];
            if (empty($periode_so)) {
                $periode_so = 0;
            } else {
                $periode_so = $get_ideal_project[0]['periode_so'];
            }
            $weighted_value = $get_ideal_project[0]['weighted_value'];
            if (empty($weighted_value)) {
                $weighted_value = 0;
            } else {
                $weighted_value = $get_ideal_project[0]['weighted_value'];
            }
            $no = 1;
            $project_name = $sqll[0]['project_name_internal'];
            $customer_name = $sqll[0]['customer_name'];
            if(empty($so_number)){
                $get_order_number = $DBSB->get_sql("SELECT * from sa_trx_project_list WHERE project_code='$project_code'");
                $order_number = $get_order_number[0]['order_number'];
                $check_data = $DBKPI->get_sql("SELECT Nama,project_code,so_number FROM sa_user WHERE Nama='$nama_email' AND so_number='$order_number' AND role LIKE '%$resource_role%' AND periode_so='$periode_so' OR Nama LIKE '%$nama%' AND so_number='$order_number' AND so_number='$order_number' AND role LIKE '%$resource_role%' AND periode_so='$periode_so'");
                $ada = $check_data[0]['Nama'];
                if (empty($ada)) {
                    $insert_data_user_kpi = $DBKPI->get_res("INSERT INTO sa_user (Nama,project_code,customer_name,so_number,project_type,project_name,role,nilai_ideal,nilai_aktual,start_assignment,end_assignment,duration,cte,status_wr,periode,periode_so,status_project,kpi_status) VALUES ('$nama_email','$project_code','$customer_name','$order_number','$project_type','$project_name','$resource_role','$max_value','$weighted_value','$start_assignment','$end_assignment','$hasil_durasi','$total_cte','$status_wr','$periode','$periode_so','$status_project','$kpi_status')");
                } else {
                    $nama = $check_data[0]['Nama'];
                    $update_sql = $DBKPI->get_res("UPDATE sa_user SET kpi_status='$kpi_status',nilai_ideal='$max_value',nilai_aktual='$weighted_value',start_assignment='$start_assignment',end_assignment='$end_assignment',duration='$hasil_durasi',cte='$total_cte',status_wr='$status_wr',periode='$periode',status_project='$status_project' WHERE Nama LIKE '%$nama%' AND so_number='$order_number'");
                }
            } else {
                $check_data = $DBKPI->get_sql("SELECT Nama FROM sa_user WHERE Nama='$nama_email' AND so_number='$so_number' AND role LIKE '%$resource_role%' AND periode_so='$periode_so' OR Nama LIKE '%$nama%' AND so_number='$so_number' AND so_number='$order_number' AND role LIKE '%$resource_role%' AND periode_so='$periode_so'");
                $ada = $check_data[0]['Nama'];
                if (empty($ada)) {
                    $insert_data_user_kpi = $DBKPI->get_res("INSERT INTO sa_user (Nama,project_code,customer_name,so_number,project_type,project_name,role,nilai_ideal,nilai_aktual,start_assignment,end_assignment,duration,cte,status_wr,periode,periode_so,status_project,kpi_status) VALUES ('$nama_email','$project_code','$customer_name','$so_number','$project_type','$project_name','$resource_role','$max_value','$weighted_value','$start_assignment','$end_assignment','$hasil_durasi','$total_cte','$status_wr','$periode','$periode_so','$status_project','$kpi_status')");
                } else {
                    $nama = $check_data[0]['Nama'];
                    $update_sql = $DBKPI->get_res("UPDATE sa_user SET kpi_status='$kpi_status',nilai_ideal='$max_value',nilai_aktual='$weighted_value',start_assignment='$start_assignment',end_assignment='$end_assignment',duration='$hasil_durasi',cte='$total_cte',status_wr='$status_wr',periode='$periode',status_project='$status_project' WHERE Nama LIKE '%$nama%' AND so_number='$so_number'");
                }
            }
            
        } while ($sql_wr_user[0] = $sql_wr_user[1]->fetch_assoc());

        $sql_res_ass = $DBWR->get_sql("SELECT project_code,no_so,resource_email,roles,status,progress FROM `sa_view_resource_assignment` WHERE approval_status='approved'");
        do {
            $project_code_wr = $sql_res_ass[0]['project_code'];
            $resource_email = $sql_res_ass[0]['resource_email'];
            $nama = explode("<", $resource_email);
            $roles = $sql_res_ass[0]['roles'];
            $status = $sql_res_ass[0]['status'];
            $progress = $sql_res_ass[0]['progress'];
            $no_so = $sql_res_ass[0]['no_so'];
            $nama_orang = $nama[0];
            if (empty($no_so)){
                $ambil_order = $DBSB->get_sql("SELECT * FROM sa_trx_project_list WHERE project_code='$project_code_wr'");
                $order_num = $ambil_order[0]['order_number'];
                $get_ideal_project = $DBKPI->get_sql("SELECT so_number,max_value,total_cte,weighted_value,periode,periode_so FROM sa_kpi_so_wr WHERE so_number='$order_num'");
                $get_status = $DBKPI->get_sql("SELECT WR_status_project,status,kpi_status,project_name,customer_name FROM sa_data_so WHERE so_number_kpi='$order_num'");
            } else {
                $get_ideal_project = $DBKPI->get_sql("SELECT so_number,max_value,total_cte,weighted_value,periode,periode_so FROM sa_kpi_so_wr WHERE so_number='$no_so'");
                $get_status = $DBKPI->get_sql("SELECT WR_status_project,status,kpi_status,project_name,customer_name FROM sa_data_so WHERE so_number_kpi='$no_so'");
            }

            if(empty($no_so)){
                $get_ordernum = $DBSB->get_sql("SELECT * FROM sa_trx_project_list WHERE project_code='$project_code'");
                $order_number = $get_ordernum[0]['order_number'];
                $sqll = $DBSB->get_sql("SELECT project_name_internal,customer_name FROM sa_trx_project_list WHERE so_number='$order_number'");
             } else {
                $sqll = $DBSB->get_sql("SELECT project_name_internal,customer_name FROM sa_trx_project_list WHERE so_number='$no_so'");
             }
            $project_name = $sqll[0]['project_name_internal'];
            $customer_name = $sqll[0]['customer_name'];
            $max_value = $get_ideal_project[0]['max_value'];
            $total_cte = $get_ideal_project[0]['total_cte'];
            $periode = $get_ideal_project[0]['periode'];
            if (empty($periode)) {
                $periode = 0;
            } else {
                $periode = $get_ideal_project[0]['periode'];
            }
            $weighted_value = $get_ideal_project[0]['weighted_value'];
            $result = preg_replace("/%/", "", $progress);
            $hobi = explode("-", $result);
            $satu = $hobi[0];
            $dua = $hobi[1];
            $progress_final = $dua - $satu;
            $nilai_akhir_ideal = $max_value * ($progress_final / 100);
            $nilai_akhir_aktual = $nilai_akhir_ideal * $total_cte;
            if ($resource_email == 'eko.ratno01@mastersystem.co.id') {
                $nama_email = "Eko Ratno <eko.ratno01@mastersystem.co.id>";
            } else if ($resource_email == 'ficky_admin@mastersystem.co.id') {
                $nama_email = "Ficky Admin <ficky_admin@mastersystem.co.id>";
            } else if ($resource_email == 'aldino.delama01@mastersystem.co.id') {
                $nama_email = "Aldino De Lama <aldino.delama@mastersystem.co.id>";
            } else if ($resource_email == 'arfend.atma01@mastersystem.co.id') {
                $nama_email = "Arfend Atma Maulana Khalifa <arfend.atma@mastersystem.co.id>";
            } else {
                $nama_email = rtrim($nama[0]," ") . " <" . $nama[1];
            }
            $status_wr = $get_status[0]['WR_status_project'];
            $status_project = $get_status[0]['status'];
            $kpi_status = $get_status[0]['kpi_status'];
            $periode_so = $get_ideal_project[0]['periode_so'];
            if (empty($periode_so)) {
                $periode_so = 0;
            } else {
                $periode_so = $get_ideal_project[0]['periode_so'];
            }
            if(empty($no_so)){
                $get_order_number = $DBSB->get_sql("SELECT * from sa_trx_project_list WHERE project_code='$project_code_wr'");
                $order_number = $get_order_number[0]['order_number'];
                $check_data = $DBKPI->get_sql("SELECT Nama,project_code,so_number FROM sa_user WHERE Nama='$nama_email' AND so_number='$order_number' AND role LIKE '%$roles%' AND periode_so='$periode_so' OR Nama LIKE '%$nama_orang%' AND so_number='$order_number' AND role LIKE '%$roles%' AND periode_so='$periode_so'");
                $ada = $check_data[0]['Nama'];
                if (empty($ada)) {
                    echo "SO TIDAK ADA DAN DATA NAMA BELUM ADA order_number=" . $order_number . " Nama Email =" . $nama_email . " Nama Saja =" . $nama_orang . "";
                    $update_data_user_kpi = $DBKPI->get_res("UPDATE sa_user SET role='$roles',progress_start='$satu',progress_end='$dua',progress='$progress_final',project_support='$status',nilai_akhir_ideal='$nilai_akhir_ideal',nilai_akhir_aktual='$nilai_akhir_aktual',status_wr='$status_wr',status_project='$status_project',kpi_status='$kpi_status' WHERE Nama='$nama_email' AND so_number='$order_number'");
                } else {
                    $update_data_user_kpi = $DBKPI->get_res("UPDATE sa_user SET role='$roles',progress_start='$satu',progress_end='$dua',progress='$progress_final',project_support='$status',nilai_akhir_ideal='$nilai_akhir_ideal',nilai_akhir_aktual='$nilai_akhir_aktual',status_wr='$status_wr',status_project='$status_project',kpi_status='$kpi_status' WHERE Nama='$nama_email' AND so_number='$order_number'");
                }
            } else {
                $check_data = $DBKPI->get_sql("SELECT Nama,project_code,so_number FROM sa_user WHERE Nama='$nama_email' AND so_number='$no_so' AND role LIKE '%$roles%' AND periode_so='$periode_so' OR Nama LIKE '%$nama_orang%' AND so_number='$no_so' AND role LIKE '%$roles%' AND periode_so='$periode_so'");
                $ada = $check_data[0]['Nama'];
                if (empty($ada)) {
                    $update_data_user_kpi = $DBKPI->get_res("UPDATE sa_user SET role='$roles',progress_start='$satu',progress_end='$dua',progress='$progress_final',project_support='$status',nilai_akhir_ideal='$nilai_akhir_ideal',nilai_akhir_aktual='$nilai_akhir_aktual',status_wr='$status_wr',status_project='$status_project',kpi_status='$kpi_status' WHERE Nama='$nama_email' AND so_number='$no_so'");
                } else {
                    $update_data_user_kpi = $DBKPI->get_res("UPDATE sa_user SET role='$roles',progress_start='$satu',progress_end='$dua',progress='$progress_final',project_support='$status',nilai_akhir_ideal='$nilai_akhir_ideal',nilai_akhir_aktual='$nilai_akhir_aktual',status_wr='$status_wr',status_project='$status_project',kpi_status='$kpi_status' WHERE Nama='$nama_email' AND so_number='$no_so'");
                }
            }            
        } while ($sql_res_ass[0] = $sql_res_ass[1]->fetch_assoc());

        $query_get_kpi_user = $DBKPI->get_sql("SELECT Nama,project_code,so_number,role,SUM(nilai_ideal) as total_nilai_ideal,SUM(nilai_aktual) as total_nilai_aktual,start_assignment,end_assignment,duration,progress,project_support,cte,SUM(nilai_akhir_ideal) as total_nilai_akhir_ideal,SUM(nilai_akhir_aktual) as total_nilai_akhir_aktual,status_wr,periode,periode_so,status_project,kpi_status FROM `sa_user` WHERE kpi_status='Not yet reviewed' AND periode_so=2022 GROUP BY Nama");
        $row5 = $query_get_kpi_user[0];
        $res5 = $query_get_kpi_user[1];
        do {
            $project_code = $row5['project_code'];
            $so_number = $row5['so_number'];
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
            $hasil_actual_ideal = $total_nilai_aktual / $total_nilai_ideal * 100;
            if ($hasil_actual_ideal == NAN) {
                $hasil_actual_ideal == 0;
            } else {
                $hasil_actual_ideal = $total_nilai_aktual / $total_nilai_ideal * 100;
            }
            $hasil_akhir_aktual_ideal = $total_nilai_akhir_aktual / $total_nilai_akhir_ideal * 100;
            $status_wr = $row5['status_wr'];
            $status_project = $row5['status_project'];
            $kpi_status = $row5['kpi_status'];
            $periode = $row5['periode'];
            $periode_so = $row5['periode_so'];
            $sqll = $DBSB->get_sql("SELECT project_code,so_number,project_name_internal,customer_name FROM sa_trx_project_list WHERE project_code='$project_code' GROUP BY project_code");
            $no = 1;
            $project_name = $sqll[0]['project_name_internal'];
            $customer_name = $sqll[0]['customer_name'];
            $check_data = $DBKPI->get_sql("SELECT Nama,kpi_status,periode FROM sa_user_kpi WHERE Nama LIKE '%$nama%' AND periode_so=$periode_so AND kpi_status='Not yet reviewed'");
            $ada = $check_data[0]['Nama'];
            if (empty($ada)) {
                $insert_data_user_kpi = $DBKPI->get_res("INSERT IGNORE INTO sa_user_kpi (Nama,total_nilai_ideal,total_nilai_aktual,hasil_aktual_ideal,total_nilai_akhir_ideal,total_nilai_akhir_aktual,hasil_akhir_aktual_ideal,status_wr,periode,periode_so,status_project,kpi_status) VALUES ('$nama','$total_nilai_ideal','$total_nilai_aktual','$hasil_actual_ideal','$total_nilai_akhir_ideal','$total_nilai_akhir_aktual','$hasil_akhir_aktual_ideal','$status_wr','$periode','$periode_so','$status_project','$kpi_status')");
            } else {
                $name = $check_data[0]['Nama'];
                $kpii_status = $check_data[0]['kpi_status'];
                $period = $check_data[0]['periode'];
                $update_data = $DBKPI->get_res("UPDATE sa_user_kpi SET kpi_status='$kpii_status' WHERE Nama LIKE '%$name%' AND kpi_status='$kpii_status'");
            }
        } while ($row5 = $res5->fetch_assoc());

        $query_get_kpi_2023 = $DBKPI->get_sql("SELECT Nama,project_code,so_number,role,SUM(nilai_ideal) as total_nilai_ideal,SUM(nilai_aktual) as total_nilai_aktual,start_assignment,end_assignment,duration,progress,project_support,cte,SUM(nilai_akhir_ideal) as total_nilai_akhir_ideal,SUM(nilai_akhir_aktual) as total_nilai_akhir_aktual,status_wr,periode,periode_so,status_project,kpi_status FROM `sa_user` WHERE kpi_status='Not yet reviewed' AND periode_so=2023 GROUP BY Nama");
        $row55 = $query_get_kpi_2023[0];
        $res55 = $query_get_kpi_2023[1];
        do {
            $project_code = $row55['project_code'];
            $so_number = $row55['so_number'];
            $nama = $row55['Nama'];
            $role = $row55['role'];
            $total_nilai_ideal = $row55['total_nilai_ideal'];
            $total_nilai_aktual = $row55['total_nilai_aktual'];
            $start_assignment = $row55['start_assignment'];
            $end_assignment = $row55['end_assignment'];
            $duration = $row55['duration'];
            $progress = $row55['progress'];
            $project_support = $row55['project_support'];
            $cte = $row55['cte'];
            $total_nilai_akhir_ideal = $row55['total_nilai_akhir_ideal'];
            $total_nilai_akhir_aktual = $row55['total_nilai_akhir_aktual'];
            $hasil_actual_ideal = $total_nilai_aktual / $total_nilai_ideal * 100;
            if ($hasil_actual_ideal == NAN) {
                $hasil_actual_ideal == 0;
            } else {
                $hasil_actual_ideal = $total_nilai_aktual / $total_nilai_ideal * 100;
            }
            $hasil_akhir_aktual_ideal = $total_nilai_akhir_aktual / $total_nilai_akhir_ideal * 100;
            $status_wr = $row55['status_wr'];
            $status_project = $row55['status_project'];
            $kpi_status = $row55['kpi_status'];
            $periode = $row55['periode'];
            $sqll = $DBSB->get_sql("SELECT project_code,so_number,project_name_internal,customer_name FROM sa_trx_project_list WHERE project_code='$project_code' GROUP BY project_code");
            $no = 1;
            $project_name = $sqll[0]['project_name_internal'];
            $customer_name = $sqll[0]['customer_name'];
            $periode_so = $row55['periode_so'];
            $check_data = $DBKPI->get_sql("SELECT Nama,kpi_status,periode FROM sa_user_kpi WHERE Nama LIKE '%$nama%' AND periode_so=$periode_so AND kpi_status='Not yet reviewed'");
            if (empty($check_data[0]['Nama'])) {
                $insert_data_user_kpi = $DBKPI->get_res("INSERT IGNORE INTO sa_user_kpi (Nama,total_nilai_ideal,total_nilai_aktual,hasil_aktual_ideal,total_nilai_akhir_ideal,total_nilai_akhir_aktual,hasil_akhir_aktual_ideal,status_wr,periode,periode_so,status_project,kpi_status) VALUES ('$nama','$total_nilai_ideal','$total_nilai_aktual','$hasil_actual_ideal','$total_nilai_akhir_ideal','$total_nilai_akhir_aktual','$hasil_akhir_aktual_ideal','$status_wr','$periode','$periode_so','$status_project','$kpi_status')");
            } else {
                $name = $check_data[0]['Nama'];
                $kpii_status = $check_data[0]['kpi_status'];
                $period = $check_data[0]['periode'];
                $update_data = $DBKPI->get_res("UPDATE sa_user_kpi SET kpi_status='$kpii_status' WHERE Nama LIKE '%$name%' AND kpi_status='$kpii_status'");
            }
        } while ($row55 = $res55->fetch_assoc());

        $query_get_kpi_2021 = $DBKPI->get_sql("SELECT Nama,project_code,so_number,role,SUM(nilai_ideal) as total_nilai_ideal,SUM(nilai_aktual) as total_nilai_aktual,start_assignment,end_assignment,duration,progress,project_support,cte,SUM(nilai_akhir_ideal) as total_nilai_akhir_ideal,SUM(nilai_akhir_aktual) as total_nilai_akhir_aktual,status_wr,periode,periode_so,status_project,kpi_status FROM `sa_user` WHERE kpi_status='Not yet reviewed' AND periode_so=2021 GROUP BY Nama");
        $row66 = $query_get_kpi_2021[0];
        $res66 = $query_get_kpi_2021[1];
        do {
            $project_code = $row66['project_code'];
            $so_number = $row66['so_number'];
            $nama = $row66['Nama'];
            $role = $row66['role'];
            $total_nilai_ideal = $row66['total_nilai_ideal'];
            $total_nilai_aktual = $row66['total_nilai_aktual'];
            $start_assignment = $row66['start_assignment'];
            $end_assignment = $row66['end_assignment'];
            $duration = $row66['duration'];
            $progress = $row66['progress'];
            $project_support = $row66['project_support'];
            $cte = $row66['cte'];
            $total_nilai_akhir_ideal = $row66['total_nilai_akhir_ideal'];
            $total_nilai_akhir_aktual = $row66['total_nilai_akhir_aktual'];
            $hasil_actual_ideal = $total_nilai_aktual / $total_nilai_ideal * 100;
            if ($hasil_actual_ideal == NAN) {
                $hasil_actual_ideal == 0;
            } else {
                $hasil_actual_ideal = $total_nilai_aktual / $total_nilai_ideal * 100;
            }
            $hasil_akhir_aktual_ideal = $total_nilai_akhir_aktual / $total_nilai_akhir_ideal * 100;
            $status_wr = $row66['status_wr'];
            $status_project = $row66['status_project'];
            $kpi_status = $row66['kpi_status'];
            $periode = $row66['periode'];
            $periode_so = $row66['periode_so'];
            $sqll = $DBSB->get_sql("SELECT project_code,so_number,project_name_internal,customer_name FROM sa_trx_project_list WHERE project_code='$project_code' GROUP BY project_code");
            $no = 1;
            $project_name = $sqll[0]['project_name_internal'];
            $customer_name = $sqll[0]['customer_name'];
            $check_data = $DBKPI->get_sql("SELECT Nama,kpi_status,periode FROM sa_user_kpi WHERE Nama LIKE '%$nama%' AND periode_so=$periode_so AND kpi_status='Not yet reviewed'");
            if (empty($check_data[0]['Nama'])) {
                $insert_data_user_kpi = $DBKPI->get_res("INSERT IGNORE INTO sa_user_kpi (Nama,total_nilai_ideal,total_nilai_aktual,hasil_aktual_ideal,total_nilai_akhir_ideal,total_nilai_akhir_aktual,hasil_akhir_aktual_ideal,status_wr,periode,periode_so,status_project,kpi_status) VALUES ('$nama','$total_nilai_ideal','$total_nilai_aktual','$hasil_actual_ideal','$total_nilai_akhir_ideal','$total_nilai_akhir_aktual','$hasil_akhir_aktual_ideal','$status_wr','$periode','$periode_so','$status_project','$kpi_status')");
            } else {
                $name = $check_data[0]['Nama'];
                $kpii_status = $check_data[0]['kpi_status'];
                $period = $check_data[0]['periode'];
                $update_data = $DBKPI->get_res("UPDATE sa_user_kpi SET kpi_status='$kpii_status' WHERE Nama LIKE '%$name%' AND kpi_status='$kpii_status'");
            }
        } while ($row66 = $res66->fetch_assoc());

        $query_get_kpi_2020 = $DBKPI->get_sql("SELECT Nama,project_code,so_number,role,SUM(nilai_ideal) as total_nilai_ideal,SUM(nilai_aktual) as total_nilai_aktual,start_assignment,end_assignment,duration,progress,project_support,cte,SUM(nilai_akhir_ideal) as total_nilai_akhir_ideal,SUM(nilai_akhir_aktual) as total_nilai_akhir_aktual,status_wr,periode,periode_so,status_project,kpi_status FROM `sa_user` WHERE kpi_status='Not yet reviewed' AND periode_so=2020 GROUP BY Nama");
        $row77 = $query_get_kpi_2020[0];
        $res77 = $query_get_kpi_2020[1];
        do {
            $project_code = $row77['project_code'];
            $so_number = $row77['so_number'];
            $nama = $row77['Nama'];
            $role = $row77['role'];
            $total_nilai_ideal = $row77['total_nilai_ideal'];
            $total_nilai_aktual = $row77['total_nilai_aktual'];
            $start_assignment = $row77['start_assignment'];
            $end_assignment = $row77['end_assignment'];
            $duration = $row77['duration'];
            $progress = $row77['progress'];
            $project_support = $row77['project_support'];
            $cte = $row77['cte'];
            $total_nilai_akhir_ideal = $row77['total_nilai_akhir_ideal'];
            $total_nilai_akhir_aktual = $row77['total_nilai_akhir_aktual'];
            $hasil_actual_ideal = $total_nilai_aktual / $total_nilai_ideal * 100;
            if ($hasil_actual_ideal == NAN) {
                $hasil_actual_ideal == 0;
            } else {
                $hasil_actual_ideal = $total_nilai_aktual / $total_nilai_ideal * 100;
            }
            $hasil_akhir_aktual_ideal = $total_nilai_akhir_aktual / $total_nilai_akhir_ideal * 100;
            $status_wr = $row77['status_wr'];
            $status_project = $row77['status_project'];
            $kpi_status = $row77['kpi_status'];
            $periode = $row77['periode'];
            $periode_so = $row77['periode_so'];
            $sqll = $DBSB->get_sql("SELECT project_code,so_number,project_name_internal,customer_name FROM sa_trx_project_list WHERE project_code='$project_code' GROUP BY project_code");
            $no = 1;
            $project_name = $sqll[0]['project_name_internal'];
            $customer_name = $sqll[0]['customer_name'];
            $check_data = $DBKPI->get_sql("SELECT Nama,kpi_status,periode FROM sa_user_kpi WHERE Nama LIKE '%$nama%' AND periode_so=$periode_so AND kpi_status='Not yet reviewed'");
            if (empty($check_data[0]['Nama'])) {
                $insert_data_user_kpi = $DBKPI->get_res("INSERT IGNORE INTO sa_user_kpi (Nama,total_nilai_ideal,total_nilai_aktual,hasil_aktual_ideal,total_nilai_akhir_ideal,total_nilai_akhir_aktual,hasil_akhir_aktual_ideal,status_wr,periode,periode_so,status_project,kpi_status) VALUES ('$nama','$total_nilai_ideal','$total_nilai_aktual','$hasil_actual_ideal','$total_nilai_akhir_ideal','$total_nilai_akhir_aktual','$hasil_akhir_aktual_ideal','$status_wr','$periode','$periode_so','$status_project','$kpi_status')");
            } else {
                $name = $check_data[0]['Nama'];
                $kpii_status = $check_data[0]['kpi_status'];
                $period = $check_data[0]['periode'];
                $update_data = $DBKPI->get_res("UPDATE sa_user_kpi SET kpi_status='$kpii_status' WHERE Nama LIKE '%$name%' AND kpi_status='$kpii_status'");
            }
        } while ($row77 = $res77->fetch_assoc());

        $query_get_kpi_user_close = $DBKPI->get_sql("SELECT Nama,project_code,so_number,role,SUM(nilai_ideal) as total_nilai_ideal,SUM(nilai_aktual) as total_nilai_aktual,start_assignment,end_assignment,duration,progress,project_support,cte,SUM(nilai_akhir_ideal) as total_nilai_akhir_ideal,SUM(nilai_akhir_aktual) as total_nilai_akhir_aktual,status_wr,periode,periode_so,status_project,kpi_status FROM `sa_user` WHERE kpi_status='Reviewed' AND periode_so=2022 GROUP BY Nama");
        $row9 = $query_get_kpi_user_close[0];
        $res9 = $query_get_kpi_user_close[1];
        do {
            $project_code = $row9['project_code'];
            $so_number = $row9['so_number'];
            $nama = $row9['Nama'];
            $role = $row9['role'];
            $total_nilai_ideal = $row9['total_nilai_ideal'];
            $total_nilai_aktual = $row9['total_nilai_aktual'];
            $start_assignment = $row9['start_assignment'];
            $end_assignment = $row9['end_assignment'];
            $duration = $row9['duration'];
            $progress = $row9['progress'];
            $project_support = $row9['project_support'];
            $cte = $row9['cte'];
            $total_nilai_akhir_ideal = $row9['total_nilai_akhir_ideal'];
            $total_nilai_akhir_aktual = $row9['total_nilai_akhir_aktual'];
            $hasil_actual_ideal = $total_nilai_aktual / $total_nilai_ideal * 100;
            if ($hasil_actual_ideal == 'NAN') {
                $hasil_actual_ideal == 0;
            } else {
                $hasil_actual_ideal1 = $total_nilai_aktual / $total_nilai_ideal * 100;
                $hasil_actual_ideal = str_replace(",", ".", $hasil_actual_ideal1);
            }
            $hasil_akhir_aktual_ideal = $total_nilai_akhir_aktual / $total_nilai_akhir_ideal * 100;
            $status_wr = $row9['status_wr'];
            $status_project = $row9['status_project'];
            $kpi_status = $row9['kpi_status'];
            $periode = $row9['periode'];
            $periode_so = $row9['periode_so'];
            $sqll = $DBSB->get_sql("SELECT project_code,so_number,project_name_internal,customer_name FROM sa_trx_project_list WHERE project_code='$project_code' GROUP BY project_code");
            $project_name = $sqll[0]['project_name_internal'];
            $customer_name = $sqll[0]['customer_name'];
            $check_data = $DBKPI->get_sql("SELECT Nama,kpi_status,periode FROM sa_user_kpi WHERE Nama LIKE '%$nama%' AND periode_so=$periode_so AND kpi_status='Reviewed'");
            if (empty($check_data[0]['Nama'])) {
                $insert_data_user_kpi = $DBKPI->get_res("INSERT IGNORE INTO sa_user_kpi (Nama,total_nilai_ideal,total_nilai_aktual,hasil_aktual_ideal,total_nilai_akhir_ideal,total_nilai_akhir_aktual,hasil_akhir_aktual_ideal,status_wr,periode,periode_so,status_project,kpi_status) VALUES ('$nama','$total_nilai_ideal','$total_nilai_aktual','$hasil_actual_ideal','$total_nilai_akhir_ideal','$total_nilai_akhir_aktual','$hasil_akhir_aktual_ideal','$status_wr','$periode','$periode_so','$status_project','$kpi_status')");
            } else {
                $name = $check_data[0]['Nama'];
                $kpii_status = $check_data[0]['kpi_status'];
                $period = $check_data[0]['periode'];
                $update_data = $DBKPI->get_res("UPDATE sa_user_kpi SET kpi_status='$kpii_status' WHERE Nama LIKE '%$name%' AND kpi_status='$kpii_status'");
            }
        } while ($row9 = $res9->fetch_assoc());

        $query_get_kpi_user_2023 = $DBKPI->get_sql("SELECT Nama,project_code,so_number,role,SUM(nilai_ideal) as total_nilai_ideal,SUM(nilai_aktual) as total_nilai_aktual,start_assignment,end_assignment,duration,progress,project_support,cte,SUM(nilai_akhir_ideal) as total_nilai_akhir_ideal,SUM(nilai_akhir_aktual) as total_nilai_akhir_aktual,status_wr,periode,periode_so,status_project,kpi_status FROM `sa_user` WHERE kpi_status='Reviewed' AND periode_so=2023 GROUP BY Nama");
        $row13 = $query_get_kpi_user_2023[0];
        $res13 = $query_get_kpi_user_2023[1];
        do {
            $project_code = $row13['project_code'];
            $so_number = $row13['so_number'];
            $nama = $row13['Nama'];
            $role = $row13['role'];
            $total_nilai_ideal = $row13['total_nilai_ideal'];
            $total_nilai_aktual = $row13['total_nilai_aktual'];
            $start_assignment = $row13['start_assignment'];
            $end_assignment = $row13['end_assignment'];
            $duration = $row13['duration'];
            $progress = $row13['progress'];
            $project_support = $row13['project_support'];
            $cte = $row13['cte'];
            $total_nilai_akhir_ideal = $row13['total_nilai_akhir_ideal'];
            $total_nilai_akhir_aktual = $row13['total_nilai_akhir_aktual'];
            $hasil_actual_ideal = $total_nilai_aktual / $total_nilai_ideal * 100;
            if ($hasil_actual_ideal == 'NAN') {
                $hasil_actual_ideal == 0;
            } else {
                $hasil_actual_ideal1 = $total_nilai_aktual / $total_nilai_ideal * 100;
                $hasil_actual_ideal = str_replace(",", ".", $hasil_actual_ideal1);
            }
            $hasil_akhir_aktual_ideal = $total_nilai_akhir_aktual / $total_nilai_akhir_ideal * 100;
            $status_wr = $row13['status_wr'];
            $status_project = $row13['status_project'];
            $kpi_status = $row13['kpi_status'];
            $periode = $row13['periode'];
            $periode_so = $row13['periode_so'];
            $sqll = $DBSB->get_sql("SELECT project_code,so_number,project_name_internal,customer_name FROM sa_trx_project_list WHERE project_code='$project_code' GROUP BY project_code");
            $project_name = $sqll[0]['project_name_internal'];
            $customer_name = $sqll[0]['customer_name'];
            $check_data = $DBKPI->get_sql("SELECT Nama,kpi_status,periode FROM sa_user_kpi WHERE Nama LIKE '%$nama%' AND periode_so=$periode_so AND kpi_status='Reviewed'");
            if (empty($check_data[0]['Nama'])) {
                $insert_data_user_kpi = $DBKPI->get_res("INSERT IGNORE INTO sa_user_kpi (Nama,total_nilai_ideal,total_nilai_aktual,hasil_aktual_ideal,total_nilai_akhir_ideal,total_nilai_akhir_aktual,hasil_akhir_aktual_ideal,status_wr,periode,periode_so,status_project,kpi_status) VALUES ('$nama','$total_nilai_ideal','$total_nilai_aktual','$hasil_actual_ideal','$total_nilai_akhir_ideal','$total_nilai_akhir_aktual','$hasil_akhir_aktual_ideal','$status_wr','$periode','$periode_so','$status_project','$kpi_status')");
            } else {
                $name = $check_data[0]['Nama'];
                $kpii_status = $check_data[0]['kpi_status'];
                $period = $check_data[0]['periode'];
                $update_data = $DBKPI->get_res("UPDATE sa_user_kpi SET kpi_status='$kpii_status' WHERE Nama LIKE '%$name%' AND kpi_status='$kpii_status'");
            }
        } while ($row13 = $res13->fetch_assoc());

        $query_get_kpi_user_summary = $DBKPI->get_sql("SELECT Nama,total_nilai_ideal,total_nilai_aktual,hasil_aktual_ideal,total_nilai_akhir_ideal,total_nilai_akhir_aktual,hasil_akhir_aktual_ideal,status_wr,periode,periode_so,status_project,kpi_status FROM `sa_user_kpi` WHERE kpi_status='Not yet reviewed'");
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
            $get_data_project = $DBKPI->get_sql("SELECT COUNT(project_code) as jumlah_project FROM `sa_user` WHERE Nama='$nama' AND kpi_status='Not yet reviewed'");
            $produktifitas = $get_data_project[0]['jumlah_project'];
            $status_wr = $row6['status_wr'];
            $status_project = $row6['status_project'];
            $periode = $row6['periode'];
            $periode_so = $row6['periode_so'];
            $kpi_status = $row6['kpi_status'];
            $check_data = $DBKPI->get_sql("SELECT Nama,kpi_status FROM sa_summary_user WHERE Nama LIKE '%$nama%' AND kpi_status='Not yet reviewed'");
            if (empty($check_data[0]['Nama'])) {
                $insert_data_user_kpi = $DBKPI->get_res("INSERT IGNORE INTO sa_summary_user (Nama,project,personal_assignment,nilai_project,nilai_personal_assignment,total_nilai,produktifitas,status_wr,periode,periode_so,status_project,kpi_status) VALUES ('$nama','$project','$personal_assignment','$nilai_ideal_project','$nilai_ideal_personal_assignment','$total_nilai_ideal','$produktifitas','$status_wr', '$periode','$periode_so','$status_project','$kpi_status')");
            } else {
                echo "data sudah ada";
                
            }
        } while ($row6 = $res6->fetch_assoc());

        $query_get_kpi_user_summary2 = $DBKPI->get_sql("SELECT Nama,total_nilai_ideal,total_nilai_aktual,hasil_aktual_ideal,total_nilai_akhir_ideal,total_nilai_akhir_aktual,hasil_akhir_aktual_ideal,status_wr,periode,periode_so,status_project,kpi_status FROM `sa_user_kpi` WHERE kpi_status='Reviewed'");
        $row7 = $query_get_kpi_user_summary2[0];
        $res7 = $query_get_kpi_user_summary2[1];
        do {
            $nama = $row7['Nama'];
            $project = 80 / 100;
            $personal_assignment = 20 / 100;
            $hasil_akhir_aktual_ideal = $row7['hasil_akhir_aktual_ideal'];
            $nilai_ideal_project = $hasil_akhir_aktual_ideal * $project;
            $nilai_aktual_project = $hasil_akhir_aktual_ideal * $project;
            $nilai_ideal_personal_assignment = $hasil_akhir_aktual_ideal * $personal_assignment;
            $nilai_aktual_personal_assignment = $hasil_akhir_aktual_ideal * $personal_assignment;
            $total_nilai_ideal = $nilai_ideal_project + $nilai_ideal_personal_assignment;
            $total_nilai_aktual = $nilai_aktual_project + $nilai_aktual_personal_assignment;
            $get_data_project = $DBKPI->get_sql("SELECT COUNT(project_code) as jumlah_project FROM `sa_user` WHERE Nama='$nama' AND kpi_status='Reviewed'");
            $produktifitas = $get_data_project[0]['jumlah_project'];
            $status_wr = $row7['status_wr'];
            $status_project = $row7['status_project'];
            $periode = $row7['periode'];
            $periode_so = $row7['periode_so'];
            $kpi_status = $row7['kpi_status'];
            $check_data = $DBKPI->get_sql("SELECT Nama,kpi_status FROM sa_summary_user WHERE Nama LIKE '%$nama%' AND kpi_status='Reviewed'");
            if (empty($check_data[0]['Nama'])) {
                $insert_data_user_kpi = $DBKPI->get_res("INSERT IGNORE INTO sa_summary_user (Nama,project,personal_assignment,nilai_project,nilai_personal_assignment,total_nilai,produktifitas,status_wr,periode,periode_so,status_project,kpi_status) VALUES ('$nama','$project','$personal_assignment','$nilai_ideal_project','$nilai_ideal_personal_assignment','$total_nilai_ideal','$produktifitas','$status_wr', '$periode','$periode_so','$status_project','$kpi_status')");
            } else {
                echo "data sudah ada";
                
            }
        } while ($row7 = $res7->fetch_assoc());

        $sqlres_task = $DBWR->get_sql("SELECT project_code,no_so,resource_email,roles,status,progress FROM `sa_view_resource_assignment` WHERE approval_status='approved'");
        do {
            $project_code_wr = $sqlres_task[0]['project_code'];
            $no_so = $sqlres_task[0]['no_so'];
            $resource_email = $sqlres_task[0]['resource_email'];
            $nama = explode("<", $resource_email);
            $nama_resource = str_replace(">", "", $nama[1]);
            $get_data_pic = $DBWR->get_sql("SELECT a.start_actual,b.no_so FROM `sa_view_dashboard_wrike_resource` AS a LEFT JOIN `sa_wrike_project_list` AS b on a.project_id=b.id WHERE resource_email LIKE '%$nama_resource%' AND no_so='$no_so' AND a.start_actual IS NOT NULL ORDER BY start_actual ASC");
            $start_actual = $get_data_pic[0]['start_actual'];
            $update_data_user_kpi1 = $DBKPI->get_res("UPDATE sa_user SET start_assignment='$start_actual' WHERE Nama LIKE '%$nama_resource%' AND so_number='$no_so'");
        } while ($sqlres_task[0] = $sqlres_task[1]->fetch_assoc());

        $sqlres_task2 = $DBWR->get_sql("SELECT project_code,no_so,resource_email,roles,status,progress FROM `sa_view_resource_assignment` WHERE approval_status='approved'");
        do {
            $project_code_wr = $sqlres_task2[0]['project_code'];
            $no_so = $sqlres_task2[0]['no_so'];
            $resource_email = $sqlres_task2[0]['resource_email'];
            $nama = explode("<", $resource_email);
            $nama_resource = str_replace(">", "", $nama[1]);
            $get_data_pic2 = $DBWR->get_sql("SELECT a.resource_email,a.finish_actual, b.no_so FROM `sa_view_dashboard_wrike_resource` AS a LEFT JOIN `sa_wrike_project_list` AS b on a.project_id=b.id WHERE resource_email LIKE '%$nama_resource%' AND no_so='$no_so' AND a.finish_actual IS NOT NULL ORDER BY finish_actual DESC");
            $finish_actual = $get_data_pic2[0]['finish_actual'];
            $update_data_user_kpi = $DBKPI->get_res("UPDATE sa_user SET end_assignment='$finish_actual' WHERE Nama LIKE '%$nama_resource%' AND so_number='$no_so'");
        } while ($sqlres_task2[0] = $sqlres_task2[1]->fetch_assoc());
    } 
    
    function getData() {
        $crcr = "CHANGE_REQUEST";
        $DBCR = get_conn($crcr);

        $dbdb = "DASHBOARD_KPI";
    $DBKPI = get_conn($dbdb);

    $sbsb = "SERVICE_BUDGET";
    $DBSB = get_conn($sbsb);

    $wrwr = "WRIKE_INTEGRATE";
    $DBWR = get_conn($wrwr);

    $hcm = "HCM";
    $DBHCM = get_conn($hcm);
    

        $update_periode = $DBKPI->get_sql("SELECT concat('', MID(so_number_kpi, 1, 4)) AS tahun,so_number_kpi,kpi_status FROM sa_data_so");
        do {
            $tahun = $update_periode[0]['tahun'];
            $so_number = $update_periode[0]['so_number_kpi'];
            if (is_numeric($tahun)) {
                $sql_update_periode = $DBKPI->get_res("UPDATE sa_data_so SET periode_so=$tahun WHERE so_number_kpi='$so_number'");
                $sql_update_periode2 = $DBKPI->get_res("UPDATE sa_kpi_so_wr SET periode_so=$tahun WHERE so_number='$so_number'");
                $sql_update_periode3 = $DBKPI->get_res("UPDATE sa_user SET periode_so=$tahun WHERE so_number='$so_number'");                
            } else {
                $sql_update_periode = $DBKPI->get_res("UPDATE sa_data_so SET periode_so=2022 WHERE so_number_kpi='$so_number'");
                $sql_update_periode2 = $DBKPI->get_res("UPDATE sa_kpi_so_wr SET periode_so=2022 WHERE so_number='$so_number'");
                $sql_update_periode3 = $DBKPI->get_res("UPDATE sa_user SET periode_so=2022 WHERE so_number='$so_number'");
            }
        } while ($update_periode[0] = $update_periode[1]->fetch_assoc());

        $get_time = $DBKPI->get_sql("SELECT so_number_kpi FROM sa_data_so");
        do {
            $so_number = $get_time[0]['so_number_kpi'];
            $time_wr = $DBWR->get_sql("SELECT a.no_so, a.id, a.project_type, b.created_at FROM sa_wrike_integrate.sa_wrike_project_list AS a JOIN sa_wrike_integrate.sa_initial_project AS b ON a.id = b.project_id WHERE a.no_so= '$so_number'");
            $created_at = $time_wr[0]['created_at'];
            $no_so = $time_wr[0]['no_so'];
            if ($no_so == null) {
                '';
            } else {
                $update_time = $DBKPI->get_res("UPDATE sa_data_so SET WR_created_at='$created_at' WHERE so_number_kpi='$no_so'");
            }
        } while ($get_time[0] = $get_time[1]->fetch_assoc());

        $sqll = $DBSB->get_sql("SELECT project_id,project_code,order_number,so_number,project_name_internal,customer_name,bundling, SUM(amount_idr) AS total_amount_idr, SUM(amount_usd) AS total_amount_usd, status FROM sa_trx_project_list WHERE status = 'acknowledge' AND bundling LIKE '%1%' AND so_number !='' OR status='acknowledge' AND bundling LIKE '%2%' AND so_number !='' OR status='acknowledge' AND bundling LIKE '%3%' AND so_number !='' GROUP BY so_number ORDER BY project_id");
        do {
            $project_id = $sqll[0]['project_id'];
            $so_number = $sqll[0]['so_number'];
            $project_code_sb = $sqll[0]['project_code'];
            $project_name_sb = $sqll[0]['project_name_internal'];
            $customer_name_sb = $sqll[0]['customer_name'];
            $bundling_sb = $sqll[0]['bundling'];
            $amount_idr_sb = $sqll[0]['total_amount_idr'];
            $amount_usd_sb = $sqll[0]['total_amount_usd'];
            $status = 'Open';
            $kpi_status = 'Open';
            $insert_data_pl = $DBKPI->get_res("INSERT INTO sa_data_so (project_id,project_code_kpi,so_number_kpi,project_name,customer_name,SB_bundling,SB_amount_idr,SB_amount_usd,WR_status_project,kpi_status) VALUES ('$project_id','$project_code_sb','$so_number','$project_name_sb','$customer_name_sb','$bundling_sb','$amount_idr_sb','$amount_usd_sb','$status','$kpi_status') ON DUPLICATE KEY UPDATE SB_amount_idr='$amount_idr_sb', SB_amount_usd='$amount_usd_sb'");
        } while ($sqll[0] = $sqll[1]->fetch_assoc());

        $sqll1 = $DBSB->get_sql("SELECT project_id,project_code,order_number,so_number,project_name_internal,customer_name,bundling, SUM(amount_idr) AS total_amount_idr, SUM(amount_usd) AS total_amount_usd, status FROM sa_trx_project_list WHERE status = 'acknowledge' AND bundling LIKE '%1%' AND order_number !='' OR status='acknowledge' AND bundling LIKE '%2%' AND order_number !='' OR status='acknowledge' AND bundling LIKE '%3%' AND order_number !='' GROUP BY order_number ORDER BY project_id");
        do {
            $project_id = $sqll1[0]['project_id'];
            $so_number1 = $sqll1[0]['so_number'];
            if (isset($so_number1)) {
                $so_number = $sqll1[0]['so_number'];
            } else {
                $so_number = $sqll1[0]['order_number'];
            }
            $project_code_sb = $sqll1[0]['project_code'];
            $project_name_sb = $sqll1[0]['project_name_internal'];
            $customer_name_sb = $sqll1[0]['customer_name'];
            $bundling_sb = $sqll1[0]['bundling'];
            $amount_idr_sb = $sqll1[0]['total_amount_idr'];
            $amount_usd_sb = $sqll1[0]['total_amount_usd'];
            $status = 'Open';
            $kpi_status = 'Open';
            $insert_data_pl = $DBKPI->get_res("INSERT INTO sa_data_so (project_id,project_code_kpi,so_number_kpi,project_name,customer_name,SB_bundling,SB_amount_idr,SB_amount_usd,WR_status_project,kpi_status) VALUES ('$project_id','$project_code_sb','$so_number','$project_name_sb','$customer_name_sb','$bundling_sb','$amount_idr_sb','$amount_usd_sb','$status','$kpi_status') ON DUPLICATE KEY UPDATE SB_amount_idr='$amount_idr_sb', SB_amount_usd='$amount_usd_sb'");
        } while ($sqll1[0] = $sqll1[1]->fetch_assoc());

        $sqll2 = $DBSB->get_sql("SELECT project_code,so_number,service_type, tos_category_id, DAY, SUM(implementation_price) AS total_implementation_price, SUM(bpd_price) AS total_bpd_price FROM sa_view_project_implementations WHERE status = 'acknowledge' AND bundling LIKE '%1%' AND service_type=1 OR status='acknowledge' AND bundling LIKE '%2%' AND service_type=1 OR status='acknowledge' AND bundling LIKE '%3%' AND service_type=1 GROUP BY so_number");
        $no = 1;
        do {
            $project_code_sb2 = $sqll2[0]['project_code'];
            $so_number = $sqll2[0]['so_number'];
            $service_type_sb = $sqll2[0]['service_type'];
            $tos_category_id_sb = $sqll2[0]['tos_category_id'];
            $DAY = isset($sqll2[0]['DAY']);
            if ($DAY == NULL) {
                $DAY = 0;
            } else {
                $DAY = $sqll2[0]['DAY'];
            }
            $total_implementation_price_sb = $sqll2[0]['total_implementation_price'];
            $bpd_price_sb = isset($sqll2[0]['total_bpd_price']);
            if ($bpd_price_sb == '') {
                $bpd_price_sb = 0;
            } else {
                $bpd_price_sb = $sqll2[0]['total_bpd_price'];
            }
            $insert_data_pi_im = $DBKPI->get_res("INSERT INTO sa_data_so (project_code_kpi,so_number_kpi,SB_service_type_implementation, SB_project_category, SB_project_estimation_implementation, SB_implementation_price, SB_bpd_price_implementation) VALUES ('$project_code_sb2','$so_number',1,'$tos_category_id_sb','$DAY',$total_implementation_price_sb,$bpd_price_sb) ON DUPLICATE KEY UPDATE SB_service_type_implementation=1, SB_project_category='$tos_category_id_sb', SB_project_estimation_implementation='$DAY', SB_implementation_price=$total_implementation_price_sb, SB_bpd_price_implementation=$bpd_price_sb");
        } while ($sqll2[0] = $sqll2[1]->fetch_assoc());

        $sqll2_order = $DBSB->get_sql("SELECT project_code,so_number,order_number,service_type, tos_category_id, DAY, SUM(implementation_price) AS total_implementation_price, SUM(bpd_price) AS total_bpd_price FROM sa_view_project_implementations WHERE status = 'acknowledge' AND bundling LIKE '%1%' AND service_type=1 AND order_number !='' OR status='acknowledge' AND bundling LIKE '%2%' AND service_type=1 AND order_number !='' OR status='acknowledge' AND bundling LIKE '%3%' AND service_type=1 AND order_number !='' GROUP BY order_number");
        $no = 1;
        do {
            $project_code_sb2 = $sqll2_order[0]['project_code'];
            $so_number1 = $sqll2_order[0]['so_number'];
            if (isset($so_number1)) {
                $so_number = $sqll2_order[0]['so_number'];
            } else {
                $so_number = $sqll2_order[0]['order_number'];
            }
            $service_type_sb = $sqll2_order[0]['service_type'];
            $tos_category_id_sb = $sqll2_order[0]['tos_category_id'];
            $DAY = isset($sqll2_order[0]['DAY']);
            if ($DAY == NULL) {
                $DAY = 0;
            } else {
                $DAY = $sqll2_order[0]['DAY'];
            }
            $total_implementation_price_sb = $sqll2_order[0]['total_implementation_price'];
            $bpd_price_sb = isset($sqll2_order[0]['total_bpd_price']);
            if ($bpd_price_sb == '') {
                $bpd_price_sb = 0;
            } else {
                $bpd_price_sb = $sqll2_order[0]['total_bpd_price'];
            }
            $insert_data_pi_im = $DBKPI->get_res("INSERT INTO sa_data_so (project_code_kpi,so_number_kpi,SB_service_type_implementation, SB_project_category, SB_project_estimation_implementation, SB_implementation_price, SB_bpd_price_implementation) VALUES ('$project_code_sb2','$so_number',1,'$tos_category_id_sb','$DAY',$total_implementation_price_sb,$bpd_price_sb) ON DUPLICATE KEY UPDATE SB_service_type_implementation=1, SB_project_category='$tos_category_id_sb', SB_project_estimation_implementation='$DAY', SB_implementation_price=$total_implementation_price_sb, SB_bpd_price_implementation=$bpd_price_sb");
        } while ($sqll2_order[0] = $sqll2_order[1]->fetch_assoc());

        $sqll3 = $DBSB->get_sql("SELECT project_code,so_number,service_type, tos_category_id, DAY, SUM(implementation_price) AS total_implementation_price, SUM(bpd_price) AS total_bpd_price, SUM(maintenance_package_price) AS total_maintenance_package_price, SUM(maintenance_addon_description) AS total_maintenance_addon_description FROM sa_view_project_implementations WHERE status = 'acknowledge' AND bundling LIKE '%1%' AND service_type=2 OR status='acknowledge' AND bundling LIKE '%2%' AND service_type=2 AND so_number !='' OR status='acknowledge' AND bundling LIKE '%3%' AND service_type=2 AND so_number !='' GROUP BY so_number");
        $no = 1;
        do {
            $project_code_sb3 = $sqll3[0]['project_code'];
            $so_number = $sqll3[0]['so_number'];
            $service_type_sb2 = $sqll3[0]['service_type'];
            $tos_category_id_sb2 = $sqll3[0]['tos_category_id'];
            $DAY2 = isset($sqll3[0]['DAY']);
            if ($DAY2 == NULL) {
                $DAY2 = 0;
            } else {
                $DAY2 = $sqll3[0]['DAY'];
            }
            $total_implementation_price_sb_mt = $sqll3[0]['total_implementation_price'];
            $bpd_price_sb_mt = isset($sqll3[0]['total_bpd_price']);
            if ($bpd_price_sb_mt == '') {
                $bpd_price_sb_mt = 0;
            } else {
                $bpd_price_sb_mt = $sqll3[0]['total_bpd_price'];
            }
            $maintenance_package = $sqll3[0]['total_maintenance_package_price'];
            if ($maintenance_package == null) {
                $maintenance_package = 0;
            } else {
                $maintenance_package = $sqll3[0]['total_maintenance_package_price'];
            }
            $maintenance_addon_description = $sqll3[0]['total_maintenance_addon_description'];
            if ($maintenance_addon_description == null) {
                $maintenance_addon_description = 0;
            } else {
                $maintenance_addon_description = $sqll3[0]['total_maintenance_addon_description'];
            }
            $insert_data_pi_mt = $DBKPI->get_res("INSERT INTO sa_data_so (project_code_kpi,so_number_kpi,SB_service_type_maintenance, SB_project_estimation_maintenance, SB_maintenance_price, SB_bpd_price_maintenance, SB_maintenance_package_price, SB_addon_maintenance_price) VALUES ('$project_code_sb3','$so_number','$service_type_sb2','$DAY2','$total_implementation_price_sb_mt','$bpd_price_sb_mt','$maintenance_package','$maintenance_addon_description') ON DUPLICATE KEY UPDATE SB_service_type_maintenance='$service_type_sb2', SB_project_estimation_maintenance='$DAY2', SB_maintenance_price=$total_implementation_price_sb_mt, SB_bpd_price_maintenance=$bpd_price_sb_mt, SB_maintenance_package_price=$maintenance_package, SB_addon_maintenance_price=$maintenance_addon_description");
        } while ($sqll3[0] = $sqll3[1]->fetch_assoc());

        $sqll3_order = $DBSB->get_sql("SELECT project_code,so_number,order_number,service_type, tos_category_id, DAY, SUM(implementation_price) AS total_implementation_price, SUM(bpd_price) AS total_bpd_price, SUM(maintenance_package_price) AS total_maintenance_package_price, SUM(maintenance_addon_description) AS total_maintenance_addon_description FROM sa_view_project_implementations WHERE status = 'acknowledge' AND bundling LIKE '%1%' AND service_type=2 AND order_number !='' OR status='acknowledge' AND bundling LIKE '%2%' AND service_type=2 AND order_number !='' OR status='acknowledge' AND bundling LIKE '%3%' AND service_type=2 AND order_number !='' GROUP BY order_number");
        $no = 1;
        do {
            $project_code_sb3 = $sqll3_order[0]['project_code'];
            $so_number1 = $sqll3_order[0]['so_number'];
            if (isset($so_number1)) {
                $so_number = $sqll3_order[0]['so_number'];
            } else {
                $so_number = $sqll3_order[0]['order_number'];
            }
            $service_type_sb2 = $sqll3_order[0]['service_type'];
            $tos_category_id_sb2 = $sqll3_order[0]['tos_category_id'];
            $DAY2 = isset($sqll3_order[0]['DAY']);
            if ($DAY2 == NULL) {
                $DAY2 = 0;
            } else {
                $DAY2 = $sqll3_order[0]['DAY'];
            }
            $total_implementation_price_sb_mt = $sqll3_order[0]['total_implementation_price'];
            $bpd_price_sb_mt = isset($sqll3_order[0]['total_bpd_price']);
            if ($bpd_price_sb_mt == '') {
                $bpd_price_sb_mt = 0;
            } else {
                $bpd_price_sb_mt = $sqll3_order[0]['total_bpd_price'];
            }
            $maintenance_package = $sqll3_order[0]['total_maintenance_package_price'];
            if ($maintenance_package == null) {
                $maintenance_package = 0;
            } else {
                $maintenance_package = $sqll3_order[0]['total_maintenance_package_price'];
            }
            $maintenance_addon_description = $sqll3_order[0]['total_maintenance_addon_description'];
            if ($maintenance_addon_description == null) {
                $maintenance_addon_description = 0;
            } else {
                $maintenance_addon_description = $sqll3_order[0]['total_maintenance_addon_description'];
            }
            $insert_data_pi_mt = $DBKPI->get_res("INSERT INTO sa_data_so (project_code_kpi,so_number_kpi,SB_service_type_maintenance, SB_project_estimation_maintenance, SB_maintenance_price, SB_bpd_price_maintenance, SB_maintenance_package_price, SB_addon_maintenance_price) VALUES ('$project_code_sb3','$so_number','$service_type_sb2','$DAY2','$total_implementation_price_sb_mt','$bpd_price_sb_mt','$maintenance_package','$maintenance_addon_description') ON DUPLICATE KEY UPDATE SB_service_type_maintenance='$service_type_sb2', SB_project_estimation_maintenance='$DAY2', SB_maintenance_price=$total_implementation_price_sb_mt, SB_bpd_price_maintenance=$bpd_price_sb_mt, SB_maintenance_package_price=$maintenance_package, SB_addon_maintenance_price=$maintenance_addon_description");
        } while ($sqll3_order[0] = $sqll3_order[1]->fetch_assoc());


        $sqll4 = $DBSB->get_sql("SELECT project_code,so_number,service_type, DAY FROM sa_view_project_implementations WHERE status = 'acknowledge' AND bundling LIKE '%1%' AND service_type=3 AND so_number !='' OR status='acknowledge' AND bundling LIKE '%2%' AND service_type=3 AND so_number !='' OR status='acknowledge' AND bundling LIKE '%3%' AND service_type=3 AND so_number !='' GROUP BY so_number");
        do {
            $project_code_sb_wr = $sqll4[0]['project_code'];
            $so_number = $sqll4[0]['so_number'];
            $service_type_sb_wr = $sqll4[0]['service_type'];
            $DAY3 = isset($sqll4[0]['DAY']);
            if ($DAY3 == NULL) {
                $DAY3 = 0;
            } else {
                $DAY3 = $sqll4[0]['DAY'];
            }
            $insert_data_pi_mt = $DBKPI->get_res("INSERT INTO sa_data_so (project_code_kpi,so_number_kpi,SB_service_type_warranty, SB_project_estimation_warranty) VALUES ('$project_code_sb_wr','$so_number','$service_type_sb_wr','$DAY3') ON DUPLICATE KEY UPDATE SB_service_type_warranty='$service_type_sb_wr', SB_project_estimation_warranty='$DAY3'");
        } while ($sqll4[0] = $sqll4[1]->fetch_assoc());


        $sqll4_order = $DBSB->get_sql("SELECT project_code,so_number,order_number,service_type, DAY FROM sa_view_project_implementations WHERE status = 'acknowledge' AND bundling LIKE '%1%' AND service_type=3 AND order_number !='' OR status='acknowledge' AND bundling LIKE '%2%' AND service_type=3 AND order_number !='' OR status='acknowledge' AND bundling LIKE '%3%' AND service_type=3 AND order_number !='' GROUP BY order_number");
        do {
            $project_code_sb_wr = $sqll4_order[0]['project_code'];
            $so_number1 = $sqll4_order[0]['so_number'];
            if (isset($so_number1)) {
                $so_number = $sqll4_order[0]['so_number'];
            } else {
                $so_number = $sqll4_order[0]['order_number'];
            }
            $service_type_sb_wr = $sqll4_order[0]['service_type'];
            $DAY3 = isset($sqll4_order[0]['DAY']);
            if ($DAY3 == NULL) {
                $DAY3 = 0;
            } else {
                $DAY3 = $sqll4_order[0]['DAY'];
            }
            $insert_data_pi_mt = $DBKPI->get_res("INSERT INTO sa_data_so (project_code_kpi,so_number_kpi,SB_service_type_warranty, SB_project_estimation_warranty) VALUES ('$project_code_sb_wr','$so_number','$service_type_sb_wr','$DAY3') ON DUPLICATE KEY UPDATE SB_service_type_warranty='$service_type_sb_wr', SB_project_estimation_warranty='$DAY3'");
        } while ($sqll4_order[0] = $sqll4_order[1]->fetch_assoc());


        $sqll5 = $DBSB->get_sql("SELECT project_code,so_number,SUM(mandays*mantotal*value) AS total_mandays FROM `sa_view_project_mandays` WHERE status = 'acknowledge' AND bundling LIKE '%1%' AND service_type=1 AND so_number !='' OR status='acknowledge' AND bundling LIKE '%2%' AND service_type=1 AND so_number !='' OR status='acknowledge' AND bundling LIKE '%3%' AND service_type=1 AND so_number !='' GROUP BY so_number");
        $no = 1;
        do {
            $project_code_sb_mandays = $sqll5[0]['project_code'];
            $so_number = $sqll5[0]['so_number'];
            $mandays_imp = isset($sqll5[0]['total_mandays']);
            if ($mandays_imp == NULL) {
                $mandays_imp = 0;
            } else {
                $mandays_imp = $sqll5[0]['total_mandays'];
            }
            $insert_data_man_imp = $DBKPI->get_res("INSERT INTO sa_data_so (project_code_kpi,so_number_kpi,SB_mandays_implementation) VALUES ('$project_code_sb_mandays','$so_number','$mandays_imp') ON DUPLICATE KEY UPDATE SB_mandays_implementation='$mandays_imp'");
        } while ($sqll5[0] = $sqll5[1]->fetch_assoc());


        $sqll5_order = $DBSB->get_sql("SELECT project_code,so_number,order_number,SUM(mandays*mantotal*value) AS total_mandays FROM `sa_view_project_mandays` WHERE status = 'acknowledge' AND bundling LIKE '%1%' AND service_type=1 AND order_number !='' OR status='acknowledge' AND bundling LIKE '%2%' AND service_type=1 AND order_number !='' OR status='acknowledge' AND bundling LIKE '%3%' AND service_type=1 AND order_number !='' GROUP BY order_number");
        $no = 1;
        do {
            $project_code_sb_mandays = $sqll5_order[0]['project_code'];
            $so_number1 = $sqll5_order[0]['so_number'];
            if (isset($so_number1)) {
                $so_number = $sqll5_order[0]['so_number'];
            } else {
                $so_number = $sqll5_order[0]['order_number'];
            }
            $mandays_imp = isset($sqll5_order[0]['total_mandays']);
            if ($mandays_imp == NULL) {
                $mandays_imp = 0;
            } else {
                $mandays_imp = $sqll5_order[0]['total_mandays'];
            }
            $insert_data_man_imp = $DBKPI->get_res("INSERT INTO sa_data_so (project_code_kpi,so_number_kpi,SB_mandays_implementation) VALUES ('$project_code_sb_mandays','$so_number','$mandays_imp') ON DUPLICATE KEY UPDATE SB_mandays_implementation='$mandays_imp'");
        } while ($sqll5_order[0] = $sqll5_order[1]->fetch_assoc());


        $sqll6 = $DBSB->get_sql("SELECT project_code,so_number,SUM(mandays) AS total_mandays FROM `sa_view_project_mandays` WHERE status = 'acknowledge' AND bundling LIKE '%1%' AND service_type=2 AND resource_level LIKE '1%' AND so_number !='' OR status='acknowledge' AND bundling LIKE '%2%' AND service_type=2 AND resource_level LIKE '1%' AND so_number !='' OR status='acknowledge' AND bundling LIKE '%3%' AND service_type=2 AND resource_level LIKE '1%' AND so_number !='' GROUP BY so_number");
        do {
            $project_code_sb_mandays_mt1 = $sqll6[0]['project_code'];
            $so_number = $sqll6[0]['so_number'];
            $mandays_mt1 = isset($sqll6[0]['total_mandays']);
            if ($mandays_mt1 == NULL) {
                $mandays_mt1 = 0;
            } else {
                $mandays_mt1 = $sqll6[0]['total_mandays'];
            }
            $insert_data_man_mt1 = $DBKPI->get_res("INSERT INTO sa_data_so (project_code_kpi,so_number_kpi,SB_existing_bu_price) VALUES ('$project_code_sb_mandays_mt1','$so_number','$mandays_mt1') ON DUPLICATE KEY UPDATE SB_existing_bu_price='$mandays_mt1'");
        } while ($sqll6[0] = $sqll6[1]->fetch_assoc());


        $sqll6_order = $DBSB->get_sql("SELECT project_code,so_number,order_number,SUM(mandays) AS total_mandays FROM `sa_view_project_mandays` WHERE status = 'acknowledge' AND bundling LIKE '%1%' AND service_type=2 AND resource_level LIKE '1%' AND order_number !='' OR status='acknowledge' AND bundling LIKE '%2%' AND service_type=2 AND resource_level LIKE '1%' AND order_number !='' OR status='acknowledge' AND bundling LIKE '%3%' AND service_type=2 AND resource_level LIKE '1%' AND order_number !='' GROUP BY order_number");
        do {
            $project_code_sb_mandays_mt1 = $sqll6_order[0]['project_code'];
            $so_number1 = $sqll6_order[0]['so_number'];
            if (isset($so_number1)) {
                $so_number = $sqll6_order[0]['so_number'];
            } else {
                $so_number = $sqll6_order[0]['order_number'];
            }
            $mandays_mt1 = isset($sqll6_order[0]['total_mandays']);
            if ($mandays_mt1 == NULL) {
                $mandays_mt1 = 0;
            } else {
                $mandays_mt1 = $sqll6_order[0]['total_mandays'];
            }
            $insert_data_man_mt1 = $DBKPI->get_res("INSERT INTO sa_data_so (project_code_kpi,so_number_kpi,SB_existing_bu_price) VALUES ('$project_code_sb_mandays_mt1','$so_number','$mandays_mt1') ON DUPLICATE KEY UPDATE SB_existing_bu_price='$mandays_mt1'");
        } while ($sqll6_order[0] = $sqll6_order[1]->fetch_assoc());


        $sqll7 = $DBSB->get_sql("SELECT project_code,so_number,SUM(mandays) AS total_mandays FROM `sa_view_project_mandays` WHERE status = 'acknowledge' AND bundling LIKE '%1%' AND service_type=2 AND resource_level LIKE '2%' AND so_number !='' OR status='acknowledge' AND bundling LIKE '%2%' AND service_type=2 AND resource_level LIKE '2%' AND so_number !='' OR status='acknowledge' AND bundling LIKE '%3%' AND service_type=2 AND resource_level LIKE '2%' AND so_number !='' GROUP BY so_number");
        $no = 1;
        do {
            $project_code_sb_mandays_mt2 = $sqll7[0]['project_code'];
            $so_number = $sqll7[0]['so_number'];
            $mandays_mt2 = isset($sqll7[0]['total_mandays']);
            if ($mandays_mt2 == NULL) {
                $mandays_mt2 = 0;
            } else {
                $mandays_mt2 = $sqll7[0]['total_mandays'];
            }
            $insert_data_man_mt2 = $DBKPI->get_res("INSERT INTO sa_data_so (project_code_kpi,so_number_kpi,SB_investment_bu_price) VALUES ('$project_code_sb_mandays_mt2','$so_number','$mandays_mt2') ON DUPLICATE KEY UPDATE SB_investment_bu_price='$mandays_mt2'");
        } while ($sqll7[0] = $sqll7[1]->fetch_assoc());


        $sqll7_order = $DBSB->get_sql("SELECT project_code,so_number,order_number,SUM(mandays) AS total_mandays FROM `sa_view_project_mandays` WHERE status = 'acknowledge' AND bundling LIKE '%1%' AND service_type=2 AND resource_level LIKE '2%' AND order_number !='' OR status='acknowledge' AND bundling LIKE '%2%' AND service_type=2 AND resource_level LIKE '2%' AND order_number !='' OR status='acknowledge' AND bundling LIKE '%3%' AND service_type=2 AND resource_level LIKE '2%' AND order_number !='' GROUP BY order_number");
        $no = 1;
        do {
            $project_code_sb_mandays_mt2 = $sqll7_order[0]['project_code'];
            $so_number1 = $sqll7_order[0]['so_number'];
            if (isset($so_number1)) {
                $so_number = $sqll7_order[0]['so_number'];
            } else {
                $so_number = $sqll7_order[0]['order_number'];
            }
            $mandays_mt2 = isset($sqll7_order[0]['total_mandays']);
            if ($mandays_mt2 == NULL) {
                $mandays_mt2 = 0;
            } else {
                $mandays_mt2 = $sqll7_order[0]['total_mandays'];
            }
            $insert_data_man_mt2 = $DBKPI->get_res("INSERT INTO sa_data_so (project_code_kpi,so_number_kpi,SB_investment_bu_price) VALUES ('$project_code_sb_mandays_mt2','$so_number','$mandays_mt2') ON DUPLICATE KEY UPDATE SB_investment_bu_price='$mandays_mt2'");
        } while ($sqll7_order[0] = $sqll7_order[1]->fetch_assoc());


        $sqll8 = $DBSB->get_sql("SELECT project_code,so_number,SUM(mandays) AS total_mandays FROM `sa_view_project_mandays` WHERE status = 'acknowledge' AND bundling LIKE '%1%' AND service_type=3 AND resource_level LIKE '1%' AND so_number !='' OR status='acknowledge' AND bundling LIKE '%2%' AND service_type=3 AND resource_level LIKE '1%' AND so_number !='' OR status='acknowledge' AND bundling LIKE '%3%' AND service_type=3 AND resource_level LIKE '1%' AND so_number !='' GROUP BY so_number");
        do {
            $project_code_sb_mandays_wr1 = $sqll8[0]['project_code'];
            $so_number = $sqll8[0]['so_number'];
            $mandays_wr1 = isset($sqll8[0]['total_mandays']);
            if ($mandays_wr1 == NULL) {
                $mandays_wr1 = 0;
            } else {
                $mandays_wr1 = $sqll8[0]['total_mandays'];
            }
            $insert_data_man_wr1 = $DBKPI->get_res("INSERT INTO sa_data_so (project_code_kpi,so_number_kpi,SB_warranty_cisco_price) VALUES ('$project_code_sb_mandays_wr1','$so_number','$mandays_wr1') ON DUPLICATE KEY UPDATE SB_warranty_cisco_price='$mandays_wr1'");
        } while ($sqll8[0] = $sqll8[1]->fetch_assoc());


        $sqll8_order = $DBSB->get_sql("SELECT project_code,so_number,order_number,SUM(mandays) AS total_mandays FROM `sa_view_project_mandays` WHERE status = 'acknowledge' AND bundling LIKE '%1%' AND service_type=3 AND resource_level LIKE '1%' AND order_number !='' OR status='acknowledge' AND bundling LIKE '%2%' AND service_type=3 AND resource_level LIKE '1%' AND order_number !='' OR status='acknowledge' AND bundling LIKE '%3%' AND service_type=3 AND resource_level LIKE '1%' AND order_number !='' GROUP BY order_number");
        do {
            $project_code_sb_mandays_wr1 = $sqll8_order[0]['project_code'];
            $so_number1 = $sqll8_order[0]['so_number'];
            if (isset($so_number1)) {
                $so_number = $sqll8_order[0]['so_number'];
            } else {
                $so_number = $sqll8_order[0]['order_number'];
            }
            $mandays_wr1 = isset($sqll8_order[0]['total_mandays']);
            if ($mandays_wr1 == NULL) {
                $mandays_wr1 = 0;
            } else {
                $mandays_wr1 = $sqll8_order[0]['total_mandays'];
            }
            $insert_data_man_wr1 = $DBKPI->get_res("INSERT INTO sa_data_so (project_code_kpi,so_number_kpi,SB_warranty_cisco_price) VALUES ('$project_code_sb_mandays_wr1','$so_number','$mandays_wr1') ON DUPLICATE KEY UPDATE SB_warranty_cisco_price='$mandays_wr1'");
        } while ($sqll8_order[0] = $sqll8_order[1]->fetch_assoc());


        $sqll9 = $DBSB->get_sql("SELECT project_code,so_number,SUM(mandays) AS total_mandays FROM `sa_view_project_mandays` WHERE status = 'acknowledge' AND bundling LIKE '%1%' AND service_type=3 AND resource_level LIKE '2%' AND so_number !='' OR status='acknowledge' AND bundling LIKE '%2%' AND service_type=3 AND resource_level LIKE '2%' AND so_number !='' OR status='acknowledge' AND bundling LIKE '%3%' AND service_type=3 AND resource_level LIKE '2%' AND so_number !='' GROUP BY so_number");
        do {
            $project_code_sb_mandays_wr2 = $sqll9[0]['project_code'];
            $so_number = $sqll9[0]['so_number'];
            $mandays_wr2 = isset($sqll9[0]['total_mandays']);
            if ($mandays_wr2 == NULL) {
                $mandays_wr2 = 0;
            } else {
                $mandays_wr2 = $sqll9[0]['total_mandays'];
            }
            $insert_data_man_wr2 = $DBKPI->get_res("INSERT INTO sa_data_so (project_code_kpi,so_number_kpi,SB_warranty_noncisco_price) VALUES ('$project_code_sb_mandays_wr2','$so_number','$mandays_wr2') ON DUPLICATE KEY UPDATE SB_warranty_noncisco_price='$mandays_wr2'");
        } while ($sqll9[0] = $sqll9[1]->fetch_assoc());


        $sqll9_order = $DBSB->get_sql("SELECT project_code,so_number,order_number,SUM(mandays) AS total_mandays FROM `sa_view_project_mandays` WHERE status = 'acknowledge' AND bundling LIKE '%1%' AND service_type=3 AND resource_level LIKE '2%' AND order_number !='' OR status='acknowledge' AND bundling LIKE '%2%' AND service_type=3 AND resource_level LIKE '2%' AND order_number !='' OR status='acknowledge' AND bundling LIKE '%3%' AND service_type=3 AND resource_level LIKE '2%' AND order_number !='' GROUP BY order_number");
        do {
            $project_code_sb_mandays_wr2 = $sqll9_order[0]['project_code'];
            $so_number1 = $sqll9_order[0]['so_number'];
            if (isset($so_number1)) {
                $so_number = $sqll9_order[0]['so_number'];
            } else {
                $so_number = $sqll9_order[0]['order_number'];
            }
            $mandays_wr2 = isset($sqll9_order[0]['total_mandays']);
            if ($mandays_wr2 == NULL) {
                $mandays_wr2 = 0;
            } else {
                $mandays_wr2 = $sqll9_order[0]['total_mandays'];
            }
            $insert_data_man_wr2 = $DBKPI->get_res("INSERT INTO sa_data_so (project_code_kpi,so_number_kpi,SB_warranty_noncisco_price) VALUES ('$project_code_sb_mandays_wr2','$so_number','$mandays_wr2') ON DUPLICATE KEY UPDATE SB_warranty_noncisco_price='$mandays_wr2'");
        } while ($sqll9_order[0] = $sqll9_order[1]->fetch_assoc());


        $sqll13 = $DBSB->get_sql("SELECT project_code,so_number,SUM(mandays) AS total_mandays FROM `sa_view_project_mandays` WHERE status = 'acknowledge' AND bundling LIKE '%1%' AND service_type=3 AND resource_level LIKE '3%' AND so_number != '' OR status='acknowledge' AND bundling LIKE '%2%' AND service_type=3 AND resource_level LIKE '3%' AND so_number != '' OR status='acknowledge' AND bundling LIKE '%3%' AND service_type=3 AND resource_level LIKE '3%' AND so_number != '' GROUP BY so_number");
        do {
            $project_code_sb_mandays_wr2 = $sqll13[0]['project_code'];
            $so_number = $sqll13[0]['so_number'];
            $mandays_wr2 = isset($sqll13[0]['total_mandays']);
            if ($mandays_wr2 == NULL) {
                $mandays_wr2 = 0;
            } else {
                $mandays_wr2 = $sqll13[0]['total_mandays'];
            }
            $insert_data_man_wr2 = $DBKPI->get_res("INSERT INTO sa_data_so (project_code_kpi,so_number_kpi,SB_warranty_price) VALUES ('$project_code_sb_mandays_wr2','$so_number','$mandays_wr2') ON DUPLICATE KEY UPDATE SB_warranty_price='$mandays_wr2'");
        } while ($sqll13[0] = $sqll13[1]->fetch_assoc());


        $sqll13_order = $DBSB->get_sql("SELECT project_code,so_number,order_number,SUM(mandays) AS total_mandays FROM `sa_view_project_mandays` WHERE status = 'acknowledge' AND bundling LIKE '%1%' AND service_type=3 AND resource_level LIKE '3%' AND order_number != '' OR status='acknowledge' AND bundling LIKE '%2%' AND service_type=3 AND resource_level LIKE '3%' AND order_number != '' OR status='acknowledge' AND bundling LIKE '%3%' AND service_type=3 AND resource_level LIKE '3%' AND order_number != '' GROUP BY order_number");
        do {
            $project_code_sb_mandays_wr2 = $sqll13_order[0]['project_code'];
            $so_number1 = $sqll13_order[0]['so_number'];
            if (isset($so_number1)) {
                $so_number = $sqll13_order[0]['so_number'];
            } else {
                $so_number = $sqll13_order[0]['order_number'];
            }
            $mandays_wr2 = isset($sqll13_order[0]['total_mandays']);
            if ($mandays_wr2 == NULL) {
                $mandays_wr2 = 0;
            } else {
                $mandays_wr2 = $sqll13_order[0]['total_mandays'];
            }
            $insert_data_man_wr2 = $DBKPI->get_res("INSERT INTO sa_data_so (project_code_kpi,so_number_kpi,SB_warranty_price) VALUES ('$project_code_sb_mandays_wr2','$so_number','$mandays_wr2') ON DUPLICATE KEY UPDATE SB_warranty_price='$mandays_wr2'");
        } while ($sqll13_order[0] = $sqll13_order[1]->fetch_assoc());


        $sqll10 = $DBSB->get_sql("SELECT project_code,so_number,SUM(addon_price) AS total_addon FROM `sa_view_addon` WHERE status = 'acknowledge' AND bundling LIKE '%1%' AND service_type=1 AND so_number !='' OR status='acknowledge' AND bundling LIKE '%2%' AND service_type=1 AND so_number !='' OR status='acknowledge' AND bundling LIKE '%3%' AND service_type=1 AND so_number !='' GROUP BY so_number");
        do {
            $project_code_sb_addon_imp = $sqll10[0]['project_code'];
            $so_number = $sqll10[0]['so_number'];
            $addon_imp = isset($sqll10[0]['total_addon']);
            if ($addon_imp == NULL) {
                $addon_imp = 0;
            } else {
                $addon_imp = $sqll10[0]['total_addon'];
            }
            $insert_data_man_wr2 = $DBKPI->get_res("INSERT INTO sa_data_so (project_code_kpi,so_number_kpi,SB_outsourcing_implementation_price) VALUES ('$project_code_sb_addon_imp','$so_number','$addon_imp') ON DUPLICATE KEY UPDATE SB_outsourcing_implementation_price='$addon_imp'");
        } while ($sqll10[0] = $sqll10[1]->fetch_assoc());


        $sqll10_order = $DBSB->get_sql("SELECT project_code,so_number,order_number,SUM(addon_price) AS total_addon FROM `sa_view_addon` WHERE status = 'acknowledge' AND bundling LIKE '%1%' AND service_type=1 AND order_number !='' OR status='acknowledge' AND bundling LIKE '%2%' AND service_type=1 AND order_number !='' OR status='acknowledge' AND bundling LIKE '%3%' AND service_type=1 AND order_number !='' GROUP BY order_number");
        do {
            $project_code_sb_addon_imp = $sqll10_order[0]['project_code'];
            $so_number1 = $sqll10_order[0]['so_number'];
            if (isset($so_number1)) {
                $so_number = $sqll10_order[0]['so_number'];
            } else {
                $so_number = $sqll10_order[0]['order_number'];
            }
            $addon_imp = isset($sqll10_order[0]['total_addon']);
            if ($addon_imp == NULL) {
                $addon_imp = 0;
            } else {
                $addon_imp = $sqll10_order[0]['total_addon'];
            }
            $insert_data_man_wr2 = $DBKPI->get_res("INSERT INTO sa_data_so (project_code_kpi,so_number_kpi,SB_outsourcing_implementation_price) VALUES ('$project_code_sb_addon_imp','$so_number','$addon_imp') ON DUPLICATE KEY UPDATE SB_outsourcing_implementation_price='$addon_imp'");
        } while ($sqll10_order[0] = $sqll10_order[1]->fetch_assoc());


        $sqll11 = $DBSB->get_sql("SELECT project_code,so_number,SUM(addon_price) AS total_addon FROM `sa_view_addon` WHERE status = 'acknowledge' AND bundling LIKE '%1%' AND service_type=2 AND so_number !='' OR status='acknowledge' AND bundling LIKE '%2%' AND service_type=2 AND so_number !='' OR status='acknowledge' AND bundling LIKE '%3%' AND service_type=2 AND so_number !='' GROUP BY so_number");
        $no = 1;
        do {
            $project_code_sb_addon_mt = $sqll11[0]['project_code'];
            $so_number = $sqll11[0]['so_number'];
            $addon_mt = isset($sqll11[0]['total_addon']);
            if ($addon_mt == NULL) {
                $addon_mt = 0;
            } else {
                $addon_mt = $sqll11[0]['total_addon'];
            }
            $insert_data_man_wr2 = $DBKPI->get_res("INSERT INTO sa_data_so (project_code_kpi,so_number_kpi,SB_outsourcing_maintenance_price) VALUES ('$project_code_sb_addon_mt','$so_number','$addon_mt') ON DUPLICATE KEY UPDATE SB_outsourcing_maintenance_price='$addon_mt'");
        } while ($sqll11[0] = $sqll11[1]->fetch_assoc());


        $sqll11_order = $DBSB->get_sql("SELECT project_code,so_number,order_number,SUM(addon_price) AS total_addon FROM `sa_view_addon` WHERE status = 'acknowledge' AND bundling LIKE '%1%' AND service_type=2 AND order_number !='' OR status='acknowledge' AND bundling LIKE '%2%' AND service_type=2 AND order_number !='' OR status='acknowledge' AND bundling LIKE '%3%' AND service_type=2 AND order_number !='' GROUP BY order_number");
        $no = 1;
        do {
            $project_code_sb_addon_mt = $sqll11_order[0]['project_code'];
            $so_number1 = $sqll11_order[0]['so_number'];
            if (isset($so_number1)) {
                $so_number = $sqll11_order[0]['so_number'];
            } else {
                $so_number = $sqll11_order[0]['order_number'];
            }
            $addon_mt = isset($sqll11_order[0]['total_addon']);
            if ($addon_mt == NULL) {
                $addon_mt = 0;
            } else {
                $addon_mt = $sqll11_order[0]['total_addon'];
            }
            $insert_data_man_wr2 = $DBKPI->get_res("INSERT INTO sa_data_so (project_code_kpi,so_number_kpi,SB_outsourcing_maintenance_price) VALUES ('$project_code_sb_addon_mt','$so_number','$addon_mt') ON DUPLICATE KEY UPDATE SB_outsourcing_maintenance_price='$addon_mt'");
        } while ($sqll11_order[0] = $sqll11_order[1]->fetch_assoc());


        $sql112 = $DBWR->get_sql("SELECT a.project_code,a.project_type,a.project_status,b.no_so from sa_wrike_project_detail a left join sa_wrike_project_list b on a.project_id = b.id where a.project_status is not null AND a.project_status != '' AND a.project_type LIKE '%Implementation%'");
        $no = 1;
        do {
            $project_code_wr = $sql112[0]['project_code'];
            $so_number = $sql112[0]['no_so'];
            $status_project = isset($sql112[0]['project_status']);
            if (empty($status_project)) {
                $status_project = 'Open';
            } else {
                $status_project = $sql112[0]['project_status'];
            }
            $check = $DBKPI->get_sql("SELECT * FROM sa_data_so WHERE so_number_kpi='$so_number'");
            if (empty($check[0]['so_number'])) {
                $insert_data_est_wr = $DBKPI->get_res("UPDATE sa_data_so SET WR_status_project='$status_project' WHERE so_number_kpi='$so_number'");
            } else {
                $insert_data_est_wr = $DBKPI->get_res("UPDATE sa_data_so SET WR_status_project='$status_project' WHERE so_number_kpi='$so_number'");
            }
        } while ($sql112[0] = $sql112[1]->fetch_assoc());


        $sql112_order = $DBWR->get_sql("SELECT a.project_code,a.project_type,a.project_status,b.order_number,b.no_so from sa_wrike_project_detail a left join sa_wrike_project_list b on a.project_id = b.id where a.project_status is not null AND a.project_status != '' AND a.project_type  LIKE '%Implementation%' AND order_number !=''");
        $no = 1;
        do {
            $project_code_wr = $sql112_order[0]['project_code'];
            $so_number = $sql112_order[0]['no_so'];
            $order_number = $sql112_order[0]['order_number'];
            $status_project = isset($sql112_order[0]['project_status']);
            if (empty($status_project)) {
                $status_project = 'Open';
            } else {
                $status_project = $sql112_order[0]['project_status'];
            }
            $check = $DBKPI->get_sql("SELECT * FROM sa_data_so WHERE so_number_kpi='$order_number'");
            if (empty($check[0]['so_number_kpi'])) {
                $insert_data_est_wr = $DBKPI->get_res("UPDATE sa_data_so SET WR_status_project='$status_project' WHERE so_number_kpi='$order_number'");
            } else {
                $insert_data_est_wr = $DBKPI->get_res("UPDATE sa_data_so SET WR_status_project='$status_project' WHERE so_number_kpi='$order_number'");
            }
        } while ($sql112_order[0] = $sql112_order[1]->fetch_assoc());


        $sqll12 = $DBWR->get_sql("SELECT a.project_code,a.project_status,a.project_type,b.no_so from sa_wrike_project_detail a left join sa_wrike_project_list b on a.project_id = b.id where a.project_status is not null AND a.project_status != '' AND a.project_type LIKE '%Implementation%'");
        $no = 1;
        do {
            $project_code_wr = $sqll12[0]['project_code'];
            $so_number = $sqll12[0]['no_so'];
            $status_project = isset($sqll12[0]['project_status']);
            if (empty($status_project)) {
                $status_project = 'Open';
            } else {
                $status_project = $sqll12[0]['project_status'];
            }
            $check = $DBKPI->get_sql("SELECT so_number_kpi FROM sa_data_so WHERE so_number_kpi='$so_number'");
            if (empty($check[0]['so_number_kpi'])) {
                $project_code = $check[0]['so_number_kpi'];
                $insert_data_est_wr = $DBKPI->get_res("UPDATE sa_data_so SET WR_status_project = '$status_project' WHERE so_number_kpi='$project_code'");
            } else {
                $insert_data_est_wr = $DBKPI->get_res("UPDATE sa_data_so SET WR_status_project = '$status_project' WHERE so_number_kpi='$project_code'");
            }
        } while ($sqll12[0] = $sqll12[1]->fetch_assoc());


        $sqll12_order = $DBWR->get_sql("SELECT a.project_code,a.project_status,a.project_type,b.order_number,b.no_so from sa_wrike_project_detail a left join sa_wrike_project_list b on a.project_id = b.id where a.project_status is not null AND a.project_status != '' AND a.project_type LIKE '%Implementation%' AND order_number !=''");
        $no = 1;
        do {
            $project_code_wr = $sqll12_order[0]['project_code'];
            $so_number = $sqll12_order[0]['no_so'];
            $order_number = $sqll12_order[0]['order_number'];
            $status_project = isset($sqll12_order[0]['project_status']);
            if (empty($status_project)) {
                $status_project = 'Open';
            } else {
                $status_project = $sqll12_order[0]['project_status'];
            }
            $check = $DBKPI->get_sql("SELECT so_number_kpi FROM sa_data_so WHERE so_number_kpi='$order_number'");
            if (empty($check[0]['so_number_kpi'])) {
                $project_code = $check[0]['so_number_kpi'];
                $insert_data_est_wr = $DBKPI->get_res("UPDATE sa_data_so SET WR_status_project = '$status_project' WHERE so_number_kpi='$project_code'");
            } else {
                $insert_data_est_wr = $DBKPI->get_res("UPDATE sa_data_so SET WR_status_project = '$status_project' WHERE so_number_kpi='$project_code'");
            }
        } while ($sqll12_order[0] = $sqll12_order[1]->fetch_assoc());


        $sqll14 = $DBWR->get_sql("SELECT a.project_code,b.no_so,a.start_date_project,a.project_type,a.finish_date_project,a.sbf_bast_date,a.kom_bast_date,a.po_bast_date,a.contract_bast_date,a.cr_bast_date,a.addendum_bast_date,a.document_bast_date,a.project_status from sa_wrike_project_detail a left join sa_wrike_project_list b on a.project_id=b.id WHERE b.no_so is not null and b.no_so !='' AND a.project_type LIKE '%Implementation%'");
        $no = 1;
        do {
            $project_code_wr = $sqll14[0]['project_code'];
            $so_number = $sqll14[0]['no_so'];
            $start_date_project = $sqll14[0]['start_date_project'];
            if ($start_date_project == null || $start_date_project == '') {
                $start = '';
            } else {
                $start = new DateTime($start_date_project);
            }
            $finish_date_project = $sqll14[0]['finish_date_project'];
            $bast_date_project1 = $sqll14[0]['contract_bast_date'];
            $bast_date_project2 = $sqll14[0]['sbf_bast_date'];
            $bast_date_project3 = $sqll14[0]['cr_bast_date'];
            $bast_date_project4 = $sqll14[0]['kom_bast_date'];
            $bast_date_project5 = $sqll14[0]['addendum_bast_date'];
            $bast_date_project6 = $sqll14[0]['po_bast_date'];
            $bast_date_actual1 = $sqll14[0]['document_bast_date'];
            $status_wr = $sqll14[0]['project_status'];

            if ($bast_date_project5 != NULL || $bast_date_project5 != '') {
                $bast_date_project = $sqll14[0]['addendum_bast_date'];
            } else if ($bast_date_project3 != NULL || $bast_date_project3 != '') {
                $bast_date_project = $sqll14[0]['cr_bast_date'];
            } else if ($bast_date_project1 != NULL || $bast_date_project1 != '') {
                $bast_date_project = $sqll14[0]['contract_bast_date'];
            } else if ($bast_date_project6 != NULL || $bast_date_project6 != '') {
                $bast_date_project = $sqll14[0]['po_bast_date'];
            } else if ($bast_date_project4 != NULL || $bast_date_project4 != '') {
                $bast_date_project = $sqll14[0]['kom_bast_date'];
            } else if ($bast_date_project2 != NULL || $bast_date_project2 != '') {
                $bast_date_project = $sqll14[0]['sbf_bast_date'];
            } else {
                $bast_date_project = "";
            }

            if ($bast_date_actual1 != null || $bast_date_actual1 != '') {
                $bast_date_actual = $sqll14[0]['document_bast_date'];
            } else {
                $bast_date_actual = "";
            }
            if (isset($bast_date_project)) {
                if (is_string($bast_date_project)) {
                    $project_duration_wrike = '';
                } else {
                    $finish = new DateTime($bast_date_project);
                    $difference = $start->diff($finish);
                    $duration = $difference->days;
                    $project_duration_wrike = $duration + 1;
                }
            } else {
                $project_duration_wrike = '';
            }
            if (isset($bast_date_actual)) {
                if (is_string($bast_date_actual)) {
                    $project_duration_actual_wrike = '';
                } else {
                    $bast = new DateTime($bast_date_actual);
                    $difference2 = $start->diff($bast);
                    $duration2 = $difference2->days;
                    $project_duration_actual_wrike = $duration2 + 1;
                }
            } else {
                $project_duration_actual_wrike = '';
            }
            $querycheck = $DBKPI->get_sql("SELECT WR_status_project FROM sa_data_so WHERE so_number_kpi LIKE '%$so_number%'");
            if (empty($bast_date_actual) && $querycheck[0]['WR_status_project'] == 'Closed') {
                $periode = 0;
                $status = 'Indicative';
                $kpi_status = '';
            } else if ($bast_date_actual != NULL && $querycheck[0]['WR_status_project'] == 'Closed') {
                $periode = explode("-", $bast_date_actual);
                $status = $querycheck[0]['WR_status_project'];
                $kpi_status = 'Completed';
            } else if ($bast_date_actual != NULL && $querycheck[0]['WR_status_project'] == 'Open') {
                $periode = explode("-", $bast_date_actual);
                $status = 'Open';
                $kpi_status = '';
            } else if ($bast_date_actual != NULL && $querycheck[0]['WR_status_project'] == 'Closed with Open Item') {
                $periode = explode("-", $bast_date_actual);
                $status = $querycheck[0]['WR_status_project'];
                $kpi_status = 'Completed';
            } else if (empty($bast_date_actual)) {
                $periode = 0;
                $status = $querycheck[0]['WR_status_project'];
                $kpi_status = '';
            }
            $check = $DBKPI->get_sql("SELECT * FROM sa_data_so WHERE so_number_kpi LIKE '%$so_number%'");
            if (empty($check[0]['so_number_kpi'])) {
                echo '';
            } else if ($so_number == '' || $so_number == NULL) {
                $so_number = $check[0]['so_number_kpi'];
                $insert_data_est_wr = $DBKPI->get_res("UPDATE sa_data_so SET kpi_status='$kpi_status', periode='$periode[0]', WR_start_assignment_implementation='$start_date_project', WR_bast_date_project_implementation='$bast_date_project', WR_bast_date_actual_project_implementation='$bast_date_actual', WR_project_estimation_implementation='$project_duration_wrike', WR_duration_actual_implementation='$project_duration_actual_wrike', status='$status' WHERE so_number_kpi='$so_number'");
            } else if ($so_number != '' || $so_number != null) {
                $so_number = $check[0]['so_number_kpi'];
                if (empty($bast_date_actual)) {
                    $insert_data_est_wr = $DBKPI->get_res("UPDATE sa_data_so SET kpi_status='$kpi_status', periode='$periode', WR_start_assignment_implementation='$start_date_project', WR_bast_date_project_implementation='$bast_date_project', WR_bast_date_actual_project_implementation='$bast_date_actual', WR_project_estimation_implementation='$project_duration_wrike', WR_duration_actual_implementation='$project_duration_actual_wrike', status='$status' WHERE so_number_kpi='$so_number'");
                } else {
                    $insert_data_est_wr = $DBKPI->get_res("UPDATE sa_data_so SET kpi_status='$kpi_status', periode='$periode[0]', WR_start_assignment_implementation='$start_date_project', WR_bast_date_project_implementation='$bast_date_project', WR_bast_date_actual_project_implementation='$bast_date_actual', WR_project_estimation_implementation='$project_duration_wrike', WR_duration_actual_implementation='$project_duration_actual_wrike', status='$status' WHERE so_number_kpi='$so_number'");
                }
            }
        } while ($sqll14[0] = $sqll14[1]->fetch_assoc());


        $sqll14_order = $DBWR->get_sql("SELECT a.project_code,b.order_number,b.no_so,a.start_date_project,a.project_type,a.finish_date_project,a.sbf_bast_date,a.kom_bast_date,a.po_bast_date,a.contract_bast_date,a.cr_bast_date,a.addendum_bast_date,a.document_bast_date,a.project_status from sa_wrike_project_detail a left join sa_wrike_project_list b on a.project_id=b.id WHERE b.order_number is not null and b.order_number !='' AND a.project_type LIKE '%Implementation%'");
        $no = 1;
        do {
            $project_code_wr = $sqll14_order[0]['project_code'];
            $so_number = $sqll14_order[0]['order_number'];
            $start_date_project = $sqll14_order[0]['start_date_project'];
            if ($start_date_project == null || $start_date_project == '') {
                $start = '';
            } else {
                $start = new DateTime($start_date_project);
            }
            $finish_date_project = $sqll14_order[0]['finish_date_project'];
            $bast_date_project1 = $sqll14_order[0]['contract_bast_date'];
            $bast_date_project2 = $sqll14_order[0]['sbf_bast_date'];
            $bast_date_project3 = $sqll14_order[0]['cr_bast_date'];
            $bast_date_project4 = $sqll14_order[0]['kom_bast_date'];
            $bast_date_project5 = $sqll14_order[0]['addendum_bast_date'];
            $bast_date_project6 = $sqll14_order[0]['po_bast_date'];
            $bast_date_actual1 = $sqll14_order[0]['document_bast_date'];
            $status_wr = $sqll14_order[0]['project_status'];

            if ($bast_date_project5 != NULL || $bast_date_project5 != '') {
                $bast_date_project = $sqll14_order[0]['addendum_bast_date'];
            } else if ($bast_date_project3 != NULL || $bast_date_project3 != '') {
                $bast_date_project = $sqll14_order[0]['cr_bast_date'];
            } else if ($bast_date_project1 != NULL || $bast_date_project1 != '') {
                $bast_date_project = $sqll14_order[0]['contract_bast_date'];
            } else if ($bast_date_project6 != NULL || $bast_date_project6 != '') {
                $bast_date_project = $sqll14_order[0]['po_bast_date'];
            } else if ($bast_date_project4 != NULL || $bast_date_project4 != '') {
                $bast_date_project = $sqll14_order[0]['kom_bast_date'];
            } else if ($bast_date_project2 != NULL || $bast_date_project2 != '') {
                $bast_date_project = $sqll14_order[0]['sbf_bast_date'];
            } else {
                $bast_date_project = "";
            }

            if ($bast_date_actual1 != null || $bast_date_actual1 != '') {
                $bast_date_actual = $sqll14_order[0]['document_bast_date'];
            } else {
                $bast_date_actual = "";
            }


            if (isset($bast_date_project)) {
                if (is_string($bast_date_project)) {
                    $project_duration_wrike = '';
                } else {
                    $finish = new DateTime($bast_date_project);
                    $difference = $start->diff($finish);
                    $duration = $difference->days;
                    $project_duration_wrike = $duration + 1;
                }
            } else {
                $project_duration_wrike = '';
            }
            if (isset($bast_date_actual)) {
                if (is_string($bast_date_actual)) {
                    $project_duration_actual_wrike = '';
                } else {
                    $bast = new DateTime($bast_date_actual);
                    $difference2 = $start->diff($bast);
                    $duration2 = $difference2->days;
                    $project_duration_actual_wrike = $duration2 + 1;
                }
            } else {
                $project_duration_actual_wrike = '';
            }
            $querycheck = $DBKPI->get_sql("SELECT WR_status_project FROM sa_data_so WHERE so_number_kpi='$so_number'");
            if (empty($bast_date_actual) && $querycheck[0]['WR_status_project'] == 'Closed') {
                $periode = 0;
                $status = 'Indicative';
                $kpi_status = '';
            } else if ($bast_date_actual != NULL && $querycheck[0]['WR_status_project'] == 'Closed') {
                $periode = explode("-", $bast_date_actual);
                $status = $querycheck[0]['WR_status_project'];
                $kpi_status = 'Completed';
            } else if ($bast_date_actual != NULL && $querycheck[0]['WR_status_project'] == 'Open') {
                $periode = explode("-", $bast_date_actual);
                $status = 'Open';
                $kpi_status = '';
            } else if ($bast_date_actual != NULL && $querycheck[0]['WR_status_project'] == 'Closed with Open Item') {
                $periode = explode("-", $bast_date_actual);
                $status = $querycheck[0]['WR_status_project'];
                $kpi_status = 'Completed';
            } else if (empty($bast_date_actual)) {
                $periode = 0;
                $status = $querycheck[0]['WR_status_project'];
                $kpi_status = '';
            }
            $check = $DBKPI->get_sql("SELECT * FROM sa_data_so WHERE so_number_kpi='$so_number'");
            if (empty($check[0]['so_number_kpi'])) {
                echo '';
            } else if ($so_number == '' || $so_number == NULL) {
                $so_number = $check[0]['so_number_kpi'];
                $insert_data_est_wr = $DBKPI->get_res("UPDATE sa_data_so SET kpi_status='$kpi_status', periode='$periode[0]', WR_start_assignment_implementation='$start_date_project', WR_bast_date_project_implementation='$bast_date_project', WR_bast_date_actual_project_implementation='$bast_date_actual', WR_project_estimation_implementation='$project_duration_wrike', WR_duration_actual_implementation='$project_duration_actual_wrike', status='$status' WHERE so_number_kpi='$so_number'");
            } else if ($so_number != '' || $so_number != null) {
                $so_number = $check[0]['so_number_kpi'];
                if (empty($bast_date_actual)) {
                    $insert_data_est_wr = $DBKPI->get_res("UPDATE sa_data_so SET kpi_status='$kpi_status', periode='$periode', WR_start_assignment_implementation='$start_date_project', WR_bast_date_project_implementation='$bast_date_project', WR_bast_date_actual_project_implementation='$bast_date_actual', WR_project_estimation_implementation='$project_duration_wrike', WR_duration_actual_implementation='$project_duration_actual_wrike', status='$status' WHERE so_number_kpi='$so_number'");
                } else {
                    $insert_data_est_wr = $DBKPI->get_res("UPDATE sa_data_so SET kpi_status='$kpi_status', periode='$periode[0]', WR_start_assignment_implementation='$start_date_project', WR_bast_date_project_implementation='$bast_date_project', WR_bast_date_actual_project_implementation='$bast_date_actual', WR_project_estimation_implementation='$project_duration_wrike', WR_duration_actual_implementation='$project_duration_actual_wrike', status='$status' WHERE so_number_kpi='$so_number'");
                }
            }
        } while ($sqll14_order[0] = $sqll14_order[1]->fetch_assoc());

        $sqll151 = $DBWR->get_sql("SELECT a.project_code,SUM((a.jumlah_actual/60/8)*a.value) AS mandays_actual_wrike,SUM((a.duration_planning/60/8)*a.value) AS mandays_plan_wrike, b.no_so, b.order_number FROM `sa_view_dashboard_wrike_resource` a left join sa_wrike_project_list b on a.project_id = b.id GROUP BY b.no_so");
        do {
            $project_code_wr = $sqll151[0]['project_code'];
            $so_number = $sqll151[0]['no_so'];
            $mandays = number_format($sqll151[0]['mandays_actual_wrike'], 5, ".", "");
            $mandays_plan = number_format($sqll151[0]['mandays_plan_wrike'], 5, ".", "");
            $insert_data_man_wr = $DBKPI->get_res("INSERT INTO sa_data_so (project_code_kpi,so_number_kpi,WR_mandays_actual_implementation,WR_mandays_plan_implementation) VALUES ('$project_code_wr','$so_number','$mandays','$mandays_plan') ON DUPLICATE KEY UPDATE WR_mandays_actual_implementation='$mandays', WR_mandays_plan_implementation='$mandays_plan'");
        } while ($sqll151[0] = $sqll151[1]->fetch_assoc());


        $sqll151_order = $DBWR->get_sql("SELECT a.project_code,SUM((a.jumlah_actual/60/8)*a.value) AS mandays_actual_wrike,SUM((a.duration_planning/60/8)*a.value) AS mandays_plan_wrike, b.no_so, b.order_number FROM `sa_view_dashboard_wrike_resource` a left join sa_wrike_project_list b on a.project_id = b.id WHERE order_number !='' GROUP BY b.order_number");
        do {
            $project_code_wr = $sqll151_order[0]['project_code'];
            $so_number = $sqll151_order[0]['order_number'];
            $mandays = number_format($sqll151_order[0]['mandays_actual_wrike'], 5, ".", "");
            $mandays_plan = number_format($sqll151_order[0]['mandays_plan_wrike'], 5, ".", "");
            $insert_data_man_wr = $DBKPI->get_res("INSERT INTO sa_data_so (project_code_kpi,so_number_kpi,WR_mandays_actual_implementation,WR_mandays_plan_implementation) VALUES ('$project_code_wr','$so_number','$mandays','$mandays_plan') ON DUPLICATE KEY UPDATE WR_mandays_actual_implementation='$mandays', WR_mandays_plan_implementation='$mandays_plan'");
        } while ($sqll151_order[0] = $sqll151_order[1]->fetch_assoc());

        $query_get_cr_data = $DBCR->get_sql("SELECT a.project_code,a.so_number,SUM(b.mandays_value) AS mandays_cr from sa_general_informations a left join sa_mandays b on a.cr_no=b.cr_no left join sa_change_cost_plans c on b.cr_no=c.cr_no WHERE a.change_request_approval_type='submission_approved' AND a.change_request_approval_type2='submission_approved' AND a.CR_status_approval_pmo='submission_approved' AND a.CR_status_approval_presales='submission_approved' AND c.cost_type='Chargeable' GROUP BY a.cr_no");
        $row14 = $query_get_cr_data[0];
        $res14 = $query_get_cr_data[1];
        do {
            $project_code_cr = $row14['project_code'];
            $so_cr = isset($row14['so_number']);
            $mandays_cr = $row14['mandays_cr'];
            if ($so_cr == null) {
                $insert_data_man_wr = $DBKPI->get_res("UPDATE sa_data_so SET CR_mandays_implementation='$mandays_cr' WHERE project_code_kpi='$project_code_cr'");
            } else {
                $so_number = $row14['so_number'];
                $insert_data_man_wr = $DBKPI->get_res("INSERT INTO sa_data_so (project_code_kpi,so_number_kpi,CR_mandays_implementation) VALUES ('$project_code_cr','$so_number','$mandays_cr') ON DUPLICATE KEY UPDATE CR_mandays_implementation='$mandays_cr'");
            }
        } while ($row14 = $res14->fetch_assoc());

        $query_get_cr_data_sp = $DBCR->get_sql("SELECT a.project_code,a.so_number,SUM(b.mandays_value) AS mandays_cr from sa_general_informations a left join sa_mandays b on a.cr_no=b.cr_no WHERE a.change_request_approval_type='submission_approved' AND a.change_request_type='Sales/Presales' GROUP BY a.cr_no");
        $row15 = $query_get_cr_data_sp[0];
        $res15 = $query_get_cr_data_sp[1];
        do {
            $project_code_cr = $row15['project_code'];
            $so_cr = isset($row15['so_number']);
            $mandays_cr = $row15['mandays_cr'];
            if ($so_cr == null) {
                $insert_data_man_wr = $DBKPI->get_res("UPDATE sa_data_so SET CR_mandays_implementation='$mandays_cr' WHERE project_code_kpi='$project_code_cr'");
            } else {
                $so_number = $row15['so_number'];
                $insert_data_man_wr = $DBKPI->get_res("INSERT INTO sa_data_so (project_code_kpi,so_number_kpi,CR_mandays_implementation) VALUES ('$project_code_cr','$so_number','$mandays_cr') ON DUPLICATE KEY UPDATE CR_mandays_implementation='$mandays_cr'");
            }
        } while ($row15 = $res15->fetch_assoc());


        $ambil_status_so = $DBKPI->get_sql("SELECT * FROM sa_data_so");
        do {
            $project_code = $ambil_status_so[0]['project_code_kpi'];
            $so_number = $ambil_status_so[0]['so_number_kpi'];
            $status = $ambil_status_so[0]['WR_status_project'];
            $periode = $ambil_status_so[0]['periode'];
            $bast = $ambil_status_so[0]['WR_bast_date_actual_project_implementation'];
            $mandays = $ambil_status_so[0]['WR_mandays_actual_implementation'];
            if ($status == 'Open') {
                $stat = 'Open';
                $kpi_status = 'Not yet reviewed';
            } else if ($bast == 0 && $mandays != NULL && $status == 'Closed' || $bast == 0 && $mandays != NULL && $status == 'Closed with Open Item') {
                $stat = 'BAST Kosong';
                $kpi_status = 'Not yet reviewed';
            } else if ($bast != 0 && $mandays == NULL && $status == 'Closed' && $periode == 2022 || $bast != 0 && $mandays == NULL && $mandays == 0.00000 && $status == 'Closed with Open Item' && $periode == 2022) {
                $stat = 'Mandays Kosong';
                $kpi_status = 'Reviewed';
            } else if ($bast == 0 && $mandays == NULL && $status == 'Closed' || $bast == 0 && $mandays == NULL && $mandays == 0.00000 && $status == 'Closed with Open Item') {
                $stat = 'Mandays dan BAST Kosong';
                $kpi_status = 'Not yet reviewed';
            } else if ($status == 'Closed' && $mandays != NULL && $bast != 0 && $periode == 2022) {
                $stat = 'Closed';
                $kpi_status = 'Reviewed';
            } else if ($status == 'Closed with Open Item' && $mandays != NULL && $bast != 0 && $periode == 2022) {
                $stat = 'Closed with Open Item';
                $kpi_status = 'Reviewed';
            } else if ($status == 'Closed' && $mandays != NULL && $bast != 0 && $periode == 2023) {
                $stat = 'Closed';
                $kpi_status = 'Not yet reviewed';
            } else if ($status == 'Closed with Open Item' && $mandays != NULL && $bast != 0 && $periode == 2023) {
                $stat = 'Closed with Open Item';
                $kpi_status = 'Not yet reviewed';
            } else {
                $stat = 'Open';
                $kpi_status = 'Not yet reviewed';
            }
            $insert = $DBKPI->get_res("UPDATE sa_data_so SET kpi_status='$kpi_status', status='$stat' WHERE so_number_kpi='$so_number'");
        } while ($ambil_status_so[0] = $ambil_status_so[1]->fetch_assoc());
    } 