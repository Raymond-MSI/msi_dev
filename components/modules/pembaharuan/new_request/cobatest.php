<?php
$sbsb = "SERVICE_BUDGET";
$DBSB = get_conn($sbsb);

$wrwr = "WRIKE_INTEGRATE";
$DBWR = get_conn($wrwr);

$survey = "SURVEY";
$DBSV = get_conn($survey);

$DBGC = get_conn("GOOGLE_CALENDAR");

$db_name = "HCM";
$DBHCM = get_conn($db_name);

$crcr = "CHANGE_REQUEST";
$DBCR = get_conn($crcr);

if (isset($_POST['submitess'])) {
    $catatan_cancel = $_POST['catatan_cancel'];
    $so_number = $_POST['so_number'];
    $order_number = $_POST['order_number'];
    date_default_timezone_set('Asia/Jakarta');
    $date = date("Y-M-d");
    $time = date("H:i:s");
    $insert = sprintf(
        "(`so_number`,`date`,`time`,`status`) VALUES (%s,%s,%s,%s) ON DUPLICATE KEY UPDATE `date`= %s,`time`= %s,`status`= %s",
        GetSQLValueString($so_number, "text"),
        GetSQLValueString($date, "text"),
        GetSQLValueString($time, "text"),
        GetSQLValueString("Status : Canceled <br> Notes : " . $catatan_cancel . "<br> By : " . $_SESSION['Microservices_UserName'] . "", "text"),
        GetSQLValueString($date, "text"),
        GetSQLValueString($time, "text"),
        GetSQLValueString("Status : Canceled <br> Notes : " . $catatan_cancel . "<br> By : " . $_SESSION['Microservices_UserName'] . "", "text")
    );
    $res = $DBKPISO->insert_data($tblname7, $insert);

    $condition = "order_number=" . "\"" . $order_number . "\"";
    $update_kpi_board = sprintf(
        "`status`=%s",
        GetSQLValueString("Canceled", "text")
    );
    $update_kpi_so_wr = sprintf(
        "`board_status`=%s",
        GetSQLValueString("Canceled", "text")
    );
    $cancel_kpi_board = $DBKPISO->get_res("UPDATE sa_kpi_board SET status='Canceled' WHERE order_number='$order_number'");
    $cancel_kpi_so_wr = $DBKPISO->get_res("UPDATE sa_kpi_so_wr SET board_status='Canceled' WHERE order_number='$order_number'");
    // $res = $DBKPISO->update_data($tblname2, $update_kpi_board, $condition);
    // $res = $DBKPISO->update_data($tblname, $update_kpi_so_wr, $condition);

    $get_data_b = $DBKPISO->get_sql("SELECT * FROM sa_kpi_board WHERE order_number='$order_number'");
    $id_kpi = $get_data_b[0]['id'];
    $get_data_so = $DBKPISO->get_sql("SELECT * FROM sa_data_so WHERE order_number='$order_number'");
    $project_name = $get_data_so[0]['project_name'];
    $customer_name = $get_data_so[0]['customer_name'];
    $project_code = $get_data_so[0]['project_code_kpi'];
    $id_project = $DBWR->get_sql("SELECT id from sa_wrike_project_list WHERE order_number='$order_number' AND project_type='MSI Project Implementation'");
    $idproject = isset($id_project[0]['id']);
    if (empty($idproject)) {
        $id = null;
    } else {
        $id = $id_project[0]['id'];
    }
    $get_engineer = $DBWR->get_sql("SELECT DISTINCT a.resource_email from sa_view_dashboard_wrike_resource a left join sa_wrike_project_list b ON a.project_id=b.id where b.order_number='$order_number'");
    $get_data_kpi = $DBKPISO->get_sql("SELECT * from sa_kpi_so_wr WHERE order_number='$order_number'");
    $get_sb_mandays = $DBKPISO->get_sql("SELECT * FROM sa_data_so WHERE order_number='$order_number' AND SB_service_type_implementation=1");
    $persen_cost = $get_data_kpi[0]['commercial_kpi'] * 100;
    $bast_planing = $get_sb_mandays[0]['WR_bast_date_project_implementation'];
    if (empty($bast_planing)) {
        $bast_plan = 'Empty';
    } else {
        $bast_plan = $bast_planing;
    }
    $bast_actualing = $get_sb_mandays[0]['WR_bast_date_actual_project_implementation'];
    if (empty($bast_actualing)) {
        $bast_actual = 'Empty';
    } else {
        $bast_actual = $bast_actualing;
    }
    $cek_error = $DBKPISO->get_sql("SELECT * from sa_kpi_board WHERE order_number='$order_number'");
    $nilai = isset($cek_error[0]['nilai_error']);
    if (empty($nilai)) {
        $nilaierror = 0;
        $persen_error = $nilaierror * 100;
    } else {
        $nilaierror = $cek_error[0]['nilai_error'];
        $persen_error = $nilaierror * 100;
    }
    if ($nilaierror >= 0.06 && $nilaierror < 0.12) {
        $error_result = 'Minor';
    } else if ($nilaierror >= 0.12 && $nilaierror < 0.2) {
        $error_result = 'Major';
    } else if ($nilaierror >= 0.2) {
        $error_result = 'Critical';
    } else {
        $error_result = 'Normal';
    }
    $persen_time =  $get_data_kpi[0]['time_kpi'] * 100;
    $total_kpi = 100 - $persen_cost - $persen_time - $persen_error;
    $user_email = $_SESSION['Microservices_UserEmail'];
    $pic_cancel = $get_data_b[0]['pic_email'];
    $from =  $user_email;
    $cc = $from;
    $bcc = "";
    $to = $pic_cancel;
    // $to = "chrisheryanda@mastersystem.co.id";
    $msg1 = "<p>Dengan ini saya Cancel KPI Panel Project:</p>";
    $msg2 = "<p>Akan dilakukan revisi terlebih dahulu untuk KPI Panel Project ini dan akan disubmit kembali.</p>";
    $reply = $from;
    $subject = "[KPI] Cancel KPI Panel Project :  " . $project_name . " � " .     $project_code . "";
    $msg = "<table width='100%'";
    $msg .= "<tr><td rowspan='3'></td><td style='width:100%;'>";
    $msg .= "<br/>";
    $msg .= "<p>Dear All, </p>";
    $msg .= "<p>" . $msg1 . "</p>";
    $msg .= "<p>";
    $msg .= "<div style='width:980px; margin:0 auto;'>";
    $msg .= "<table style='width:100%; border-collapse: collapse;'>";
    $msg .= "<tr><td>Project Name</td><td>: </td><td>" . $project_name . "</td></tr>";
    $msg .= "<tr><td>Customer Name</td><td>: </td><td>" . $customer_name . "</td></tr>";
    $msg .= "<tr><td>Project Code</td><td>: </td><td>" . $project_code . "</td></tr>";
    $msg .= "<tr><td>SO Number</td><td>: </td><td>" . $so_number . "</td></tr>";
    $msg .= "</table>";
    $msg .= "<table class='table' style='width:100%; border-collapse: collapse;'>";
    $msg .= "<thead class='thead-light'>";
    $msg .= "<tr>";
    $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Cost (%)</th>";
    $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Time (%)</th>";
    $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Error (%)</th>";
    $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Total KPI (%)</th>";
    $msg .= "</tr>";
    $msg .= "</thead>";
    $msg .= "<tbody>";
    $msg .= "<tr>";
    $msg .= "<td style='border: solid 1px #777; text-align:center;'>";
    $msg .= "<label for='inputCID3' class='col-sm-8 col-form-label col-form-label-sm'>" . $persen_cost . "% | " . $get_data_kpi[0]['commercial_category'] . "</label>";
    $msg .= "<div class='row mb-1'>";
    $msg .= "<label for='inputCID3' class='col-sm-8 col-form-label col-form-label-sm'>" . $get_sb_mandays[0]['SB_mandays_implementation'] . " | " . $get_sb_mandays[0]['CR_mandays_implementation'] . " | " . round($get_sb_mandays[0]['WR_mandays_actual_implementation']) . "</label>";
    $msg .= "</div>";
    $msg .= "</td>";
    $msg .= "<td style='border: solid 1px #777; text-align:center;'>";
    $msg .= "<label for='inputCID3' class='col-sm-8 col-form-label col-form-label-sm'>" . $persen_time . "% | " . $get_data_kpi[0]['time_category'] . "</label>";
    $msg .= "<div class='row mb-1'>";
    $msg .= "<label for='inputCID3' class='col-sm-8 col-form-label col-form-label-sm'>" . $bast_plan . " | " . $bast_actual . "</label>";
    $msg .= "</div>";
    $msg .= "</td>";
    $msg .= "<td style='border: solid 1px #777; text-align:center;'>" . $persen_error . "% | " . $error_result . "</label></td>";
    $msg .= "<td style='text-align:center; border: solid 1px #777;'>" . $total_kpi . "%" . "</label></td>";
    $msg .= "</tr>";
    $msg .= "<tr>";
    $msg .= "<td>&nbsp;</td>";
    $msg .= "</tr>";
    $msg .= "</tbody>";
    $msg .= "</table>";
    if (isset($get_engineer[0]['resource_email'])) {
        $msg .= "<table class='table' style='width:100%; border-collapse: collapse;'>";
        $msg .= "<thead class='thead-light'>";
        $msg .= "<tr>";
        $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Resource Name</th>";
        $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Roles</th>";
        $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Productivity</th>";
        $msg .= "</tr>";
        $msg .= "</thead>";
        $msg .= "<tbody>";
        do {
            $id_project2 = $DBWR->get_sql("SELECT id from sa_wrike_project_list WHERE order_number='$order_number' AND project_type='MSI Project Implementation'");
            $idproject2 = isset($id_project2[0]['id']);
            if (empty($idproject2)) {
                $id2 = null;
            } else {
                $id2 = $id_project2[0]['id'];
            }
            $get_name = $DBHCM->get_sql("SELECT employee_name from sa_view_employees WHERE employee_email='" . $get_engineer[0]['resource_email'] . "'");
            $get_leader_resource = get_leader($get_engineer[0]['resource_email']);
            $get_roles = $DBWR->get_sql("SELECT * FROM sa_resource_assignment WHERE resource_email LIKE '%" . $get_engineer[0]['resource_email'] . "%' AND order_number='$order_number'");
            // $get_jumlah_task_resource = $DBWR->get_sql("SELECT COUNT(task_name) AS jumlah_task FROM sa_wrike_assignment WHERE project_id='$id2' AND resource_email='" . $get_engineer[0]['resource_email'] . "'");
            $get_resource_id = $DBWR->get_sql("SELECT resource_id FROM sa_wrike_project_resources WHERE resource_email='" . $get_engineer[0]['resource_email'] . "'");
            // $get_jumlah_task_resource_update = $DBWR->get_sql("SELECT COUNT(task_id) AS updated_task FROM sa_view_task_timelog WHERE project_id='$id2' AND resource_id='" . $get_resource_id[0]['resource_id'] . "' GROUP BY task_id");
            $get_jumlah_task_resource = $DBGC->get_sqlV2("SELECT COUNT(task_name) AS jumlah_task FROM sa_preschedule WHERE project_id='$id2' AND resource_email='" . $get_engineer[0]['resource_email'] . "'");
            $get_jumlah_task_resource_update = $DBGC->get_sqlV2("SELECT COUNT(a.project_id) as updated_task FROM sa_preschedule a left join sa_schedule b ON a.google_event_id=b.event_id WHERE a.project_id = '$id2' AND b.attendees_email='" . $get_engineer[0]['resource_email'] . "' AND b.response_status='accepted' AND b.project_type='MSI Project Implementation'");
            $msg .= "<tr>";
            $msg .= "<td style='border: solid 1px #777; text-align:center;'>" . $get_name[0]['employee_name'] . "</td>";
            if (isset($get_roles[0]['project_roles'])) {
                $msg .= "<td style='border: solid 1px #777; text-align:center;'>" . $get_roles[0]['project_roles'] . "</td>";
            } else {
                $msg .= "<td style='border: solid 1px #777; text-align:center;'>Kosong</td>";
            }
            $msg .= "<td style='border: solid 1px #777; text-align:center;'>" . $get_jumlah_task_resource[0]['jumlah_task'] . " | " . $get_jumlah_task_resource_update[0]['updated_task'] . "</td>";
            $msg .= "</tr>";
        } while ($get_engineer[0] = $get_engineer[1]->fetch_assoc());
        $msg .= "<tr>";
        $msg .= "<td>&nbsp;</td>";
        $msg .= "</tr>";
        $msg .= "</tbody>";
        $msg .= "</table>";
    }
    $msg .= "</p>";
    $msg .= "<p>" . $msg2 . "</p>";
    $msg .= "<p>Terimakasih,<br/>";
    $msg .= $_SESSION['Microservices_UserName'] . "</p>";
    $msg .= "</td><td width='30%' rowspan='3'>";
    $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id/index.php?mod=kpi_board'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
    $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
    $msg .= "</table>";
    $msg .= "</div>";

    $headers = "From: " . $from . "\r\n" .
        "Reply-To: " . $reply . "\r\n" .
        "Cc: " . $cc . "\r\n" .
        "Bcc: " . $bcc . "\r\n" .
        "MIME-Version: 1.0" . "\r\n" .
        "Content-Type: text/html; charset=UTF-8" . "\r\n" .
        "X-Mailer: PHP/" . phpversion();

    $ALERT = new Alert();
    if (!mail(
        $to,
        $subject,
        $msg,
        $headers
    )) {
        echo
        "Email gagal terkirim pada jam " . date("d M Y G:i:s") . "$to" . "/ $subject";
    } else {
        echo
        "Email terkirim pada jam " . date("d M Y G:i:s");
    }

    $ALERT->savedata();
}

if (isset($_POST['save'])) {
    // // $error_type = "";
    // if (isset($_POST['error_type'])) {
    //     $error_type = $_POST['error_type'];
    // } else {
    //     $error_type = null;
    // }
    // if (isset($_POST['error_type2'])) {
    //     $error_type2 = $_POST['error_type2'];
    // } else {
    //     $error_type2 = null;
    // }
    // if (isset($_POST['error_type3'])) {
    //     $error_type3 = $_POST['error_type3'];
    // } else {
    //     $error_type3 = null;
    // }
    // if (isset($_POST['error_type4'])) {
    //     $error_type4 = $_POST['error_type4'];
    // } else {
    //     $error_type4 = null;
    // }
    // if (isset($_POST['error_type5'])) {
    //     $error_type5 = $_POST['error_type5'];
    // } else {
    //     $error_type5 = null;
    // }
    // if (isset($_POST['error_type6'])) {
    //     $error_type6 = $_POST['error_type6'];
    // } else {
    //     $error_type6 = null;
    // }
    // if (isset($_POST['error_type7'])) {
    //     $error_type7 = $_POST['error_type7'];
    // } else {
    //     $error_type7 = null;
    // }
    // if (isset($_POST['error_type8'])) {
    //     $error_type8 = $_POST['error_type8'];
    // } else {
    //     $error_type8 = null;
    // }
    // if (isset($_POST['impact'])) {
    //     $impact = $_POST['impact'];
    // } else {
    //     $impact = null;
    // }
    // if (isset($_POST['impact2'])) {
    //     $impact2 = $_POST['impact2'];
    // } else {
    //     $impact2 = null;
    // }
    // if (isset($_POST['impact3'])) {
    //     $impact3 = $_POST['impact3'];
    // } else {
    //     $impact3 = null;
    // }
    // if (isset($_POST['impact4'])) {
    //     $impact4 = $_POST['impact4'];
    // } else {
    //     $impact4 = null;
    // }
    // if (isset($_POST['impact5'])) {
    //     $impact5 = $_POST['impact5'];
    // } else {
    //     $impact5 = null;
    // }
    // if (isset($_POST['impact6'])) {
    //     $impact6 = $_POST['impact6'];
    // } else {
    //     $impact6 = null;
    // }
    // if (isset($_POST['impact7'])) {
    //     $impact7 = $_POST['impact7'];
    // } else {
    //     $impact7 = null;
    // }
    // if (isset($_POST['impact8'])) {
    //     $impact8 = $_POST['impact8'];
    // } else {
    //     $impact8 = null;
    // }
    // if (isset($_POST['notes'])) {
    //     $notes = $_POST['notes'];
    // } else {
    //     $notes = null;
    // }
    // if (isset($_POST['notes2'])) {
    //     $notes2 = $_POST['notes2'];
    // } else {
    //     $notes2 = null;
    // }
    // if (isset($_POST['notes3'])) {
    //     $notes3 = $_POST['notes3'];
    // } else {
    //     $notes3 = null;
    // }
    // if (isset($_POST['notes4'])) {
    //     $notes4 = $_POST['notes4'];
    // } else {
    //     $notes4 = null;
    // }
    // if (isset($_POST['notes5'])) {
    //     $notes5 = $_POST['notes5'];
    // } else {
    //     $notes5 = null;
    // }
    // if (isset($_POST['notes6'])) {
    //     $notes6 = $_POST['notes6'];
    // } else {
    //     $notes6 = null;
    // }
    // if (isset($_POST['notes7'])) {
    //     $notes7 = $_POST['notes7'];
    // } else {
    //     $notes7 = null;
    // }
    // if (isset($_POST['notes8'])) {
    //     $notes8 = $_POST['notes8'];
    // } else {
    //     $notes8 = null;
    // }
    // if (isset($_POST['impact'])) {
    //     if ($_POST['impact'] == 'Normal') {
    //         $nilai1 = 0;
    //     } else if ($_POST['impact'] == 'Minor') {
    //         $nilai1 = 0.06;
    //     } else if ($_POST['impact'] == 'Major') {
    //         $nilai1 = 0.12;
    //     } else if ($_POST['impact'] == 'Critical') {
    //         $nilai1 = 0.20;
    //     }
    // } else {
    //     $nilai1 = 0;
    // }
    // if (isset($_POST['impact2'])) {
    //     if ($_POST['impact2'] == 'Normal') {
    //         $nilai2 = 0;
    //     } else if ($_POST['impact2'] == 'Minor') {
    //         $nilai2 = 0.06;
    //     } else if ($_POST['impact2'] == 'Major') {
    //         $nilai2 = 0.12;
    //     } else if ($_POST['impact2'] == 'Critical') {
    //         $nilai2 = 0.20;
    //     }
    // } else {
    //     $nilai2 = 0;
    // }
    // if (isset($_POST['impact3'])) {
    //     if ($_POST['impact3'] == 'Normal') {
    //         $nilai3 = 0;
    //     } else if ($_POST['impact3'] == 'Minor') {
    //         $nilai3 = 0.06;
    //     } else if ($_POST['impact3'] == 'Major') {
    //         $nilai3 = 0.12;
    //     } else if ($_POST['impact3'] == 'Critical') {
    //         $nilai3 = 0.20;
    //     }
    // } else {
    //     $nilai3 = 0;
    // }
    // if (isset($_POST['impact4'])) {
    //     if ($_POST['impact4'] == 'Normal') {
    //         $nilai4 = 0;
    //     } else if ($_POST['impact4'] == 'Minor') {
    //         $nilai4 = 0.06;
    //     } else if ($_POST['impact4'] == 'Major') {
    //         $nilai4 = 0.12;
    //     } else if ($_POST['impact4'] == 'Critical') {
    //         $nilai4 = 0.20;
    //     }
    // } else {
    //     $nilai4 = 0;
    // }
    // if (isset($_POST['impact5'])) {
    //     if ($_POST['impact5'] == 'Normal') {
    //         $nilai5 = 0;
    //     } else if ($_POST['impact5'] == 'Minor') {
    //         $nilai5 = 0.06;
    //     } else if ($_POST['impact5'] == 'Major') {
    //         $nilai5 = 0.12;
    //     } else if ($_POST['impact5'] == 'Critical') {
    //         $nilai5 = 0.20;
    //     }
    // } else {
    //     $nilai5 = 0;
    // }
    // if (isset($_POST['impact6'])) {
    //     if ($_POST['impact6'] == 'Normal') {
    //         $nilai6 = 0;
    //     } else if ($_POST['impact6'] == 'Minor') {
    //         $nilai6 = 0.06;
    //     } else if ($_POST['impact6'] == 'Major') {
    //         $nilai6 = 0.12;
    //     } else if ($_POST['impact6'] == 'Critical') {
    //         $nilai6 = 0.20;
    //     }
    // } else {
    //     $nilai6 = 0;
    // }
    // if (isset($_POST['impact7'])) {
    //     if ($_POST['impact7'] == 'Normal') {
    //         $nilai7 = 0;
    //     } else if ($_POST['impact7'] == 'Minor') {
    //         $nilai7 = 0.06;
    //     } else if ($_POST['impact7'] == 'Major') {
    //         $nilai7 = 0.12;
    //     } else if ($_POST['impact7'] == 'Critical') {
    //         $nilai7 = 0.20;
    //     }
    // } else {
    //     $nilai7 = 0;
    // }
    // if (isset($_POST['impact8'])) {
    //     if ($_POST['impact8'] == 'Normal') {
    //         $nilai8 = 0;
    //     } else if ($_POST['impact8'] == 'Minor') {
    //         $nilai8 = 0.06;
    //     } else if ($_POST['impact8'] == 'Major') {
    //         $nilai8 = 0.12;
    //     } else if ($_POST['impact8'] == 'Critical') {
    //         $nilai8 = 0.20;
    //     }
    // } else {
    //     $nilai8 = 0;
    // }
    // $nilai_error = $nilai1 + $nilai2 + $nilai3 + $nilai4 + $nilai5 + $nilai6 + $nilai7 + $nilai8;
    // $project_code = $_POST['project_code'];
    // $so_number = $_POST['so_number'];
    // if (isset($_POST['pic'])) {
    //     $pic_board = $_POST['pic'];
    // } else if (isset($_POST['pic_board'])) {
    //     $pic_board = '';
    //     $status_pic = '';
    //     foreach ($_POST["pic_board"] as $row) {
    //         $pic_board .= $row . ',';
    //         $status = str_replace($row, 'Pending', $row);
    //         $status_pic .= $status . ', </br>';
    //     }
    //     $status_pic = substr($status_pic, 0, -7);
    //     $pic_board = substr($pic_board, 0, -1);
    // } else {
    //     $pic_board = null;
    //     $status_pic = null;
    // }
    // if (isset($_POST['no_bast'])) {
    //     $no_bast = $_POST['no_bast'];
    // } else {
    //     $no_bast = null;
    // }
    // $status_error = 'Draft';

    // $insert = sprintf(
    //     "(`so_number`,`project_code`,`project_name`,`customer_name`,`no_bast`,`email_requester`,`error_list`,`error_list2`,`error_list3`,`error_list4`,`error_list5`,`error_list6`,`error_list7`,`error_list8`,`error_category`,`error_category2`,`error_category3`,`error_category4`,`error_category5`,`error_category6`,`error_category7`,`error_category8`,`notes`,`notes2`,`notes3`,`notes4`,`notes5`,`notes6`,`notes7`,`notes8`,`nilai_error`,`pic_email`,`status_approval`,`status_error`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s) ON DUPLICATE KEY UPDATE `no_bast`=%s,`email_requester`=%s,`error_list`=%s,`error_list2`=%s,`error_list3`=%s,`error_list4`=%s,`error_list5`=%s,`error_list6`=%s,`error_list7`=%s,`error_list8`=%s,`error_category`=%s,`error_category2`=%s,`error_category3`=%s,`error_category4`=%s,`error_category5`=%s,`error_category6`=%s,`error_category7`=%s,`error_category8`=%s,`notes`=%s,`notes2`=%s,`notes3`=%s,`notes4`=%s,`notes5`=%s,`notes6`=%s,`notes7`=%s,`notes8`=%s,`nilai_error`=%s,`pic_email`=%s,`status_error`=%s",
    //     GetSQLValueString($so_number, "text"),
    //     GetSQLValueString($project_code, "text"),
    //     GetSQLValueString($_POST['project_name'], "text"),
    //     GetSQLValueString($_POST['customer_name'], "text"),
    //     GetSQLValueString($no_bast, "text"),
    //     GetSQLValueString($_SESSION['Microservices_UserEmail'], "text"),
    //     GetSQLValueString($error_type, "text"),
    //     GetSQLValueString($error_type2, "text"),
    //     GetSQLValueString($error_type3, "text"),
    //     GetSQLValueString($error_type4, "text"),
    //     GetSQLValueString($error_type5, "text"),
    //     GetSQLValueString($error_type6, "text"),
    //     GetSQLValueString($error_type7, "text"),
    //     GetSQLValueString($error_type8, "text"),
    //     GetSQLValueString($impact, "text"),
    //     GetSQLValueString($impact2, "text"),
    //     GetSQLValueString($impact3, "text"),
    //     GetSQLValueString($impact4, "text"),
    //     GetSQLValueString($impact5, "text"),
    //     GetSQLValueString($impact6, "text"),
    //     GetSQLValueString($impact7, "text"),
    //     GetSQLValueString($impact8, "text"),
    //     GetSQLValueString($notes, "text"),
    //     GetSQLValueString($notes2, "text"),
    //     GetSQLValueString($notes3, "text"),
    //     GetSQLValueString($notes4, "text"),
    //     GetSQLValueString($notes5, "text"),
    //     GetSQLValueString($notes6, "text"),
    //     GetSQLValueString($notes7, "text"),
    //     GetSQLValueString($notes8, "text"),
    //     GetSQLValueString($nilai_error, "text"),
    //     GetSQLValueString($pic_board, "text"),
    //     GetSQLValueString($status_pic, "text"),
    //     GetSQLValueString($status_error, "text"),
    //     GetSQLValueString($no_bast, "text"),
    //     GetSQLValueString($_SESSION['Microservices_UserEmail'], "text"),
    //     GetSQLValueString($error_type, "text"),
    //     GetSQLValueString($error_type2, "text"),
    //     GetSQLValueString($error_type3, "text"),
    //     GetSQLValueString($error_type4, "text"),
    //     GetSQLValueString($error_type5, "text"),
    //     GetSQLValueString($error_type6, "text"),
    //     GetSQLValueString($error_type7, "text"),
    //     GetSQLValueString($error_type8, "text"),
    //     GetSQLValueString($impact, "text"),
    //     GetSQLValueString($impact2, "text"),
    //     GetSQLValueString($impact3, "text"),
    //     GetSQLValueString($impact4, "text"),
    //     GetSQLValueString($impact5, "text"),
    //     GetSQLValueString($impact6, "text"),
    //     GetSQLValueString($impact7, "text"),
    //     GetSQLValueString($impact8, "text"),
    //     GetSQLValueString($notes, "text"),
    //     GetSQLValueString($notes2, "text"),
    //     GetSQLValueString($notes3, "text"),
    //     GetSQLValueString($notes4, "text"),
    //     GetSQLValueString($notes5, "text"),
    //     GetSQLValueString($notes6, "text"),
    //     GetSQLValueString($notes7, "text"),
    //     GetSQLValueString($notes8, "text"),
    //     GetSQLValueString($nilai_error, "text"),
    //     GetSQLValueString($pic_board, "text"),
    //     GetSQLValueString($status_error, "text")
    // );
    // $res = $DBKPISO->insert_data($tblname2, $insert);

    if (isset($_POST['error_type'])) {
        $error_type = $_POST['error_type'];
    } else {
        $error_type = null;
    }
    if (isset($_POST['error_type2'])) {
        $error_type2 = $_POST['error_type2'];
    } else {
        $error_type2 = null;
    }
    if (isset($_POST['error_type3'])) {
        $error_type3 = $_POST['error_type3'];
    } else {
        $error_type3 = null;
    }
    if (isset($_POST['error_type4'])) {
        $error_type4 = $_POST['error_type4'];
    } else {
        $error_type4 = null;
    }
    if (isset($_POST['error_type5'])) {
        $error_type5 = $_POST['error_type5'];
    } else {
        $error_type5 = null;
    }
    if (isset($_POST['error_type6'])) {
        $error_type6 = $_POST['error_type6'];
    } else {
        $error_type6 = null;
    }
    if (isset($_POST['error_type7'])) {
        $error_type7 = $_POST['error_type7'];
    } else {
        $error_type7 = null;
    }
    if (isset($_POST['error_type8'])) {
        $error_type8 = $_POST['error_type8'];
    } else {
        $error_type8 = null;
    }
    if (isset($_POST['impact'])) {
        $impact = $_POST['impact'];
    } else {
        $impact = null;
    }
    if (isset($_POST['impact2'])) {
        $impact2 = $_POST['impact2'];
    } else {
        $impact2 = null;
    }
    if (isset($_POST['impact3'])) {
        $impact3 = $_POST['impact3'];
    } else {
        $impact3 = null;
    }
    if (isset($_POST['impact4'])) {
        $impact4 = $_POST['impact4'];
    } else {
        $impact4 = null;
    }
    if (isset($_POST['impact5'])) {
        $impact5 = $_POST['impact5'];
    } else {
        $impact5 = null;
    }
    if (isset($_POST['impact6'])) {
        $impact6 = $_POST['impact6'];
    } else {
        $impact6 = null;
    }
    if (isset($_POST['impact7'])) {
        $impact7 = $_POST['impact7'];
    } else {
        $impact7 = null;
    }
    if (isset($_POST['impact8'])) {
        $impact8 = $_POST['impact8'];
    } else {
        $impact8 = null;
    }
    if (isset($_POST['notes'])) {
        $notes = $_POST['notes'];
    } else {
        $notes = null;
    }
    if (isset($_POST['notes2'])) {
        $notes2 = $_POST['notes2'];
    } else {
        $notes2 = null;
    }
    if (isset($_POST['notes3'])) {
        $notes3 = $_POST['notes3'];
    } else {
        $notes3 = null;
    }
    if (isset($_POST['notes4'])) {
        $notes4 = $_POST['notes4'];
    } else {
        $notes4 = null;
    }
    if (isset($_POST['notes5'])) {
        $notes5 = $_POST['notes5'];
    } else {
        $notes5 = null;
    }
    if (isset($_POST['notes6'])) {
        $notes6 = $_POST['notes6'];
    } else {
        $notes6 = null;
    }
    if (isset($_POST['notes7'])) {
        $notes7 = $_POST['notes7'];
    } else {
        $notes7 = null;
    }
    if (isset($_POST['notes8'])) {
        $notes8 = $_POST['notes8'];
    } else {
        $notes8 = null;
    }
    if (isset($_POST['impact'])) {
        if ($_POST['impact'] == 'Normal') {
            $nilai1 = 0;
        } else if ($_POST['impact'] == 'Minor') {
            $nilai1 = 0.06;
        } else if ($_POST['impact'] == 'Major') {
            $nilai1 = 0.12;
        } else if ($_POST['impact'] == 'Critical') {
            $nilai1 = 0.20;
        }
    } else {
        $nilai1 = 0;
    }
    if (isset($_POST['impact2'])) {
        if ($_POST['impact2'] == 'Normal') {
            $nilai2 = 0;
        } else if ($_POST['impact2'] == 'Minor') {
            $nilai2 = 0.06;
        } else if ($_POST['impact2'] == 'Major') {
            $nilai2 = 0.12;
        } else if ($_POST['impact2'] == 'Critical') {
            $nilai2 = 0.20;
        }
    } else {
        $nilai2 = 0;
    }
    if (isset($_POST['impact3'])) {
        if ($_POST['impact3'] == 'Normal') {
            $nilai3 = 0;
        } else if ($_POST['impact3'] == 'Minor') {
            $nilai3 = 0.06;
        } else if ($_POST['impact3'] == 'Major') {
            $nilai3 = 0.12;
        } else if ($_POST['impact3'] == 'Critical') {
            $nilai3 = 0.20;
        }
    } else {
        $nilai3 = 0;
    }
    if (isset($_POST['impact4'])) {
        if ($_POST['impact4'] == 'Normal') {
            $nilai4 = 0;
        } else if ($_POST['impact4'] == 'Minor') {
            $nilai4 = 0.06;
        } else if ($_POST['impact4'] == 'Major') {
            $nilai4 = 0.12;
        } else if ($_POST['impact4'] == 'Critical') {
            $nilai4 = 0.20;
        }
    } else {
        $nilai4 = 0;
    }
    if (isset($_POST['impact5'])) {
        if ($_POST['impact5'] == 'Normal') {
            $nilai5 = 0;
        } else if ($_POST['impact5'] == 'Minor') {
            $nilai5 = 0.06;
        } else if ($_POST['impact5'] == 'Major') {
            $nilai5 = 0.12;
        } else if ($_POST['impact5'] == 'Critical') {
            $nilai5 = 0.20;
        }
    } else {
        $nilai5 = 0;
    }
    if (isset($_POST['impact6'])) {
        if ($_POST['impact6'] == 'Normal') {
            $nilai6 = 0;
        } else if ($_POST['impact6'] == 'Minor') {
            $nilai6 = 0.06;
        } else if ($_POST['impact6'] == 'Major') {
            $nilai6 = 0.12;
        } else if ($_POST['impact6'] == 'Critical') {
            $nilai6 = 0.20;
        }
    } else {
        $nilai6 = 0;
    }
    if (isset($_POST['impact7'])) {
        if ($_POST['impact7'] == 'Normal') {
            $nilai7 = 0;
        } else if ($_POST['impact7'] == 'Minor') {
            $nilai7 = 0.06;
        } else if ($_POST['impact7'] == 'Major') {
            $nilai7 = 0.12;
        } else if ($_POST['impact7'] == 'Critical') {
            $nilai7 = 0.20;
        }
    } else {
        $nilai7 = 0;
    }
    if (isset($_POST['impact8'])) {
        if ($_POST['impact8'] == 'Normal') {
            $nilai8 = 0;
        } else if ($_POST['impact8'] == 'Minor') {
            $nilai8 = 0.06;
        } else if ($_POST['impact8'] == 'Major') {
            $nilai8 = 0.12;
        } else if ($_POST['impact8'] == 'Critical') {
            $nilai8 = 0.20;
        }
    } else {
        $nilai8 = 0;
    }
    $hasil_nilai_error = $nilai1 + $nilai2 + $nilai3 + $nilai4 + $nilai5 + $nilai6 + $nilai7 + $nilai8;
    if ($hasil_nilai_error == null) {
        $nilai_error = 0;
    } else {
        $nilai_error = $hasil_nilai_error;
    }
    $project_code = $_POST['project_code'];
    $so_number = $_POST['so_number'];
    $order_number = $_POST['order_number'];
    date_default_timezone_set('Asia/Jakarta');
    $date = date("Y-M-d");
    $time = date("H:i:s");
    if (isset($_POST['pic'])) {
        $email_pic = explode(",", $_POST['pic']);
        $status_pic = '';
        $pic_name = '';
        for ($i = 0; $i < count($email_pic); $i++) {
            $email = $email_pic[$i];
            $sqlCheck = "SELECT employee_name,employee_email FROM sa_view_employees_v2 WHERE employee_email='" . $email_pic[$i] . "'";
            $get_nama = $DBHCM->get_sql($sqlCheck);
            $employee_name = $get_nama[0]['employee_name'];
            $employee_email = $get_nama[0]['employee_email'];
            $final_email = $employee_email;
            $pic_name .= $employee_name . ', </br>';
            // $a .= $final_email . ",";
            $status = str_replace($final_email, 'Pending', $final_email);
            $status_pic .= $status . ', </br>';
        }
        $pic_name = substr($pic_name, 0, -7);
        $pic_board = $_POST['pic'];
        $status_pic = substr($status_pic, 0, -7);
    } else if (isset($_POST['pic_board'])) {
        $pic_board = '';
        $status_pic = '';
        $pic_name = '';
        foreach ($_POST["pic_board"] as $row) {
            $pic_board .= $row . ',';
            $rawpic_name = $DBHCM->get_sql("SELECT employee_name FROM sa_view_employees_v2 WHERE employee_email LIKE '%$row%' AND resign_date is null");
            $pic_name .= $rawpic_name[0]['employee_name'] . ', </br>';
            $status = str_replace($row, 'Pending', $row);
            $status_pic .= $status . ', </br>';
        }
        $pic_name = substr($pic_name, 0, -7);
        $status_pic = substr($status_pic, 0, -7);
        $pic_board = substr($pic_board, 0, -1);
    } else {
        $pic_name = null;
        $pic_board = null;
        $status_pic = null;
    }
    if (isset($_POST['no_bast'])) {
        $no_bast = $_POST['no_bast'];
    } else {
        $no_bast = null;
    }
    $status_error = 'Pending';

    $insert = sprintf(
        "(`so_number`,`order_number`,`pic_approval`,`project_code`,`project_name`,`customer_name`,`no_bast`,`email_requester`,`error_list`,`error_list2`,`error_list3`,`error_list4`,`error_list5`,`error_list6`,`error_list7`,`error_list8`,`error_category`,`error_category2`,`error_category3`,`error_category4`,`error_category5`,`error_category6`,`error_category7`,`error_category8`,`notes`,`notes2`,`notes3`,`notes4`,`notes5`,`notes6`,`notes7`,`notes8`,`nilai_error`,`pic_email`,`status_approval`,`status_error`,`status`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s) ON DUPLICATE KEY UPDATE `pic_approval`=%s,`no_bast`=%s,`error_list`=%s,`error_list2`=%s,`error_list3`=%s,`error_list4`=%s,`error_list5`=%s,`error_list6`=%s,`error_list7`=%s,`error_list8`=%s,`error_category`=%s,`error_category2`=%s,`error_category3`=%s,`error_category4`=%s,`error_category5`=%s,`error_category6`=%s,`error_category7`=%s,`error_category8`=%s,`notes`=%s,`notes2`=%s,`notes3`=%s,`notes4`=%s,`notes5`=%s,`notes6`=%s,`notes7`=%s,`notes8`=%s,`nilai_error`=%s,`pic_email`=%s,`status_error`=%s,`status`=%s, `order_number`=%s",
        GetSQLValueString($so_number, "text"),
        GetSQLValueString($order_number, "text"),
        GetSQLValueString($pic_name, "text"),
        GetSQLValueString($project_code, "text"),
        GetSQLValueString($_POST['project_name'], "text"),
        GetSQLValueString($_POST['customer_name'], "text"),
        GetSQLValueString($no_bast, "text"),
        GetSQLValueString($_SESSION['Microservices_UserEmail'], "text"),
        GetSQLValueString($error_type, "text"),
        GetSQLValueString($error_type2, "text"),
        GetSQLValueString($error_type3, "text"),
        GetSQLValueString($error_type4, "text"),
        GetSQLValueString($error_type5, "text"),
        GetSQLValueString($error_type6, "text"),
        GetSQLValueString($error_type7, "text"),
        GetSQLValueString($error_type8, "text"),
        GetSQLValueString($impact, "text"),
        GetSQLValueString($impact2, "text"),
        GetSQLValueString($impact3, "text"),
        GetSQLValueString($impact4, "text"),
        GetSQLValueString($impact5, "text"),
        GetSQLValueString($impact6, "text"),
        GetSQLValueString($impact7, "text"),
        GetSQLValueString($impact8, "text"),
        GetSQLValueString($notes, "text"),
        GetSQLValueString($notes2, "text"),
        GetSQLValueString($notes3, "text"),
        GetSQLValueString($notes4, "text"),
        GetSQLValueString($notes5, "text"),
        GetSQLValueString($notes6, "text"),
        GetSQLValueString($notes7, "text"),
        GetSQLValueString($notes8, "text"),
        GetSQLValueString(number_format($nilai_error, 5, ".", ""), "text"),
        GetSQLValueString($pic_board, "text"),
        GetSQLValueString($status_pic, "text"),
        GetSQLValueString($status_error, "text"),
        GetSQLValueString("Draft", "text"),
        GetSQLValueString($pic_name, "text"),
        GetSQLValueString($no_bast, "text"),
        GetSQLValueString($error_type, "text"),
        GetSQLValueString($error_type2, "text"),
        GetSQLValueString($error_type3, "text"),
        GetSQLValueString($error_type4, "text"),
        GetSQLValueString($error_type5, "text"),
        GetSQLValueString($error_type6, "text"),
        GetSQLValueString($error_type7, "text"),
        GetSQLValueString($error_type8, "text"),
        GetSQLValueString($impact, "text"),
        GetSQLValueString($impact2, "text"),
        GetSQLValueString($impact3, "text"),
        GetSQLValueString($impact4, "text"),
        GetSQLValueString($impact5, "text"),
        GetSQLValueString($impact6, "text"),
        GetSQLValueString($impact7, "text"),
        GetSQLValueString($impact8, "text"),
        GetSQLValueString($notes, "text"),
        GetSQLValueString($notes2, "text"),
        GetSQLValueString($notes3, "text"),
        GetSQLValueString($notes4, "text"),
        GetSQLValueString($notes5, "text"),
        GetSQLValueString($notes6, "text"),
        GetSQLValueString($notes7, "text"),
        GetSQLValueString($notes8, "text"),
        GetSQLValueString(number_format($nilai_error, 5, ".", ""), "text"),
        GetSQLValueString($pic_board, "text"),
        GetSQLValueString($status_error, "text"),
        GetSQLValueString("Draft", "text"),
        GetSQLValueString($order_number, "text")
    );
    $res = $DBKPISO->insert_data($tblname2, $insert);


    // $condition_kpi_so = "so_number=" . "\"" . $so_number . "\"";
    // $update_kpi_so = sprintf(
    //     "`pic`=%s,`board_status`=%s",
    //     GetSQLValueString($pic_name, "text"),
    //     GetSQLValueString("Ready for Approve", "text")
    // );
    // $res = $DBKPISO->update_data($tblname, $update_kpi_so, $condition_kpi_so);

    // $insert = sprintf(
    //     "(`so_number`,`date`,`time`,`status`) VALUES (%s,%s,%s,%s) ON DUPLICATE KEY UPDATE `date`= %s,`time`= %s,`status`= %s",
    //     GetSQLValueString($so_number, "text"),
    //     GetSQLValueString($date, "text"),
    //     GetSQLValueString($time, "text"),
    //     GetSQLValueString("Status : Submitted <br> Nilai Error : " . $nilai_error . "<br> By : " . $_SESSION['Microservices_UserName'] . "", "text"),
    //     GetSQLValueString($date, "text"),
    //     GetSQLValueString($time, "text"),
    //     GetSQLValueString("Status : Submitted <br> Nilai Error : " . $nilai_error . "<br> By : " . $_SESSION['Microservices_UserName'] . "", "text")
    // );
    // $res = $DBKPISO->insert_data($tblname7, $insert);

    $ALERT->savedata();
} elseif (isset($_POST['submit_error'])) {
    if (isset($_POST['error_type'])) {
        $error_type = $_POST['error_type'];
    } else {
        $error_type = null;
    }
    if (isset($_POST['error_type2'])) {
        $error_type2 = $_POST['error_type2'];
    } else {
        $error_type2 = null;
    }
    if (isset($_POST['error_type3'])) {
        $error_type3 = $_POST['error_type3'];
    } else {
        $error_type3 = null;
    }
    if (isset($_POST['error_type4'])) {
        $error_type4 = $_POST['error_type4'];
    } else {
        $error_type4 = null;
    }
    if (isset($_POST['error_type5'])) {
        $error_type5 = $_POST['error_type5'];
    } else {
        $error_type5 = null;
    }
    if (isset($_POST['error_type6'])) {
        $error_type6 = $_POST['error_type6'];
    } else {
        $error_type6 = null;
    }
    if (isset($_POST['error_type7'])) {
        $error_type7 = $_POST['error_type7'];
    } else {
        $error_type7 = null;
    }
    if (isset($_POST['error_type8'])) {
        $error_type8 = $_POST['error_type8'];
    } else {
        $error_type8 = null;
    }
    if (isset($_POST['impact'])) {
        $impact = $_POST['impact'];
    } else {
        $impact = null;
    }
    if (isset($_POST['impact2'])) {
        $impact2 = $_POST['impact2'];
    } else {
        $impact2 = null;
    }
    if (isset($_POST['impact3'])) {
        $impact3 = $_POST['impact3'];
    } else {
        $impact3 = null;
    }
    if (isset($_POST['impact4'])) {
        $impact4 = $_POST['impact4'];
    } else {
        $impact4 = null;
    }
    if (isset($_POST['impact5'])) {
        $impact5 = $_POST['impact5'];
    } else {
        $impact5 = null;
    }
    if (isset($_POST['impact6'])) {
        $impact6 = $_POST['impact6'];
    } else {
        $impact6 = null;
    }
    if (isset($_POST['impact7'])) {
        $impact7 = $_POST['impact7'];
    } else {
        $impact7 = null;
    }
    if (isset($_POST['impact8'])) {
        $impact8 = $_POST['impact8'];
    } else {
        $impact8 = null;
    }
    if (isset($_POST['notes'])) {
        $notes = $_POST['notes'];
    } else {
        $notes = null;
    }
    if (isset($_POST['notes2'])) {
        $notes2 = $_POST['notes2'];
    } else {
        $notes2 = null;
    }
    if (isset($_POST['notes3'])) {
        $notes3 = $_POST['notes3'];
    } else {
        $notes3 = null;
    }
    if (isset($_POST['notes4'])) {
        $notes4 = $_POST['notes4'];
    } else {
        $notes4 = null;
    }
    if (isset($_POST['notes5'])) {
        $notes5 = $_POST['notes5'];
    } else {
        $notes5 = null;
    }
    if (isset($_POST['notes6'])) {
        $notes6 = $_POST['notes6'];
    } else {
        $notes6 = null;
    }
    if (isset($_POST['notes7'])) {
        $notes7 = $_POST['notes7'];
    } else {
        $notes7 = null;
    }
    if (isset($_POST['notes8'])) {
        $notes8 = $_POST['notes8'];
    } else {
        $notes8 = null;
    }
    if (isset($_POST['impact'])) {
        if ($_POST['impact'] == 'Normal') {
            $nilai1 = 0;
        } else if ($_POST['impact'] == 'Minor') {
            $nilai1 = 0.06;
        } else if ($_POST['impact'] == 'Major') {
            $nilai1 = 0.12;
        } else if ($_POST['impact'] == 'Critical') {
            $nilai1 = 0.20;
        }
    } else {
        $nilai1 = 0;
    }
    if (isset($_POST['impact2'])) {
        if ($_POST['impact2'] == 'Normal') {
            $nilai2 = 0;
        } else if ($_POST['impact2'] == 'Minor') {
            $nilai2 = 0.06;
        } else if ($_POST['impact2'] == 'Major') {
            $nilai2 = 0.12;
        } else if ($_POST['impact2'] == 'Critical') {
            $nilai2 = 0.20;
        }
    } else {
        $nilai2 = 0;
    }
    if (isset($_POST['impact3'])) {
        if ($_POST['impact3'] == 'Normal') {
            $nilai3 = 0;
        } else if ($_POST['impact3'] == 'Minor') {
            $nilai3 = 0.06;
        } else if ($_POST['impact3'] == 'Major') {
            $nilai3 = 0.12;
        } else if ($_POST['impact3'] == 'Critical') {
            $nilai3 = 0.20;
        }
    } else {
        $nilai3 = 0;
    }
    if (isset($_POST['impact4'])) {
        if ($_POST['impact4'] == 'Normal') {
            $nilai4 = 0;
        } else if ($_POST['impact4'] == 'Minor') {
            $nilai4 = 0.06;
        } else if ($_POST['impact4'] == 'Major') {
            $nilai4 = 0.12;
        } else if ($_POST['impact4'] == 'Critical') {
            $nilai4 = 0.20;
        }
    } else {
        $nilai4 = 0;
    }
    if (isset($_POST['impact5'])) {
        if ($_POST['impact5'] == 'Normal') {
            $nilai5 = 0;
        } else if ($_POST['impact5'] == 'Minor') {
            $nilai5 = 0.06;
        } else if ($_POST['impact5'] == 'Major') {
            $nilai5 = 0.12;
        } else if ($_POST['impact5'] == 'Critical') {
            $nilai5 = 0.20;
        }
    } else {
        $nilai5 = 0;
    }
    if (isset($_POST['impact6'])) {
        if ($_POST['impact6'] == 'Normal') {
            $nilai6 = 0;
        } else if ($_POST['impact6'] == 'Minor') {
            $nilai6 = 0.06;
        } else if ($_POST['impact6'] == 'Major') {
            $nilai6 = 0.12;
        } else if ($_POST['impact6'] == 'Critical') {
            $nilai6 = 0.20;
        }
    } else {
        $nilai6 = 0;
    }
    if (isset($_POST['impact7'])) {
        if ($_POST['impact7'] == 'Normal') {
            $nilai7 = 0;
        } else if ($_POST['impact7'] == 'Minor') {
            $nilai7 = 0.06;
        } else if ($_POST['impact7'] == 'Major') {
            $nilai7 = 0.12;
        } else if ($_POST['impact7'] == 'Critical') {
            $nilai7 = 0.20;
        }
    } else {
        $nilai7 = 0;
    }
    if (isset($_POST['impact8'])) {
        if ($_POST['impact8'] == 'Normal') {
            $nilai8 = 0;
        } else if ($_POST['impact8'] == 'Minor') {
            $nilai8 = 0.06;
        } else if ($_POST['impact8'] == 'Major') {
            $nilai8 = 0.12;
        } else if ($_POST['impact8'] == 'Critical') {
            $nilai8 = 0.20;
        }
    } else {
        $nilai8 = 0;
    }
    $hasil_nilai_error = $nilai1 + $nilai2 + $nilai3 + $nilai4 + $nilai5 + $nilai6 + $nilai7 + $nilai8;
    if ($hasil_nilai_error == null) {
        $nilai_error = 0;
    } else {
        $nilai_error = $hasil_nilai_error;
    }
    $project_code = $_POST['project_code'];
    $so_number = $_POST['so_number'];
    $order_number = $_POST['order_number'];
    date_default_timezone_set('Asia/Jakarta');
    $date = date("Y-M-d");
    $time = date("H:i:s");
    if (isset($_POST['pic'])) {
        $email_pic = explode(",", $_POST['pic']);
        $status_pic = '';
        $pic_name = '';
        for ($i = 0; $i < count($email_pic); $i++) {
            $email = $email_pic[$i];
            $sqlCheck = "SELECT employee_name,employee_email FROM sa_view_employees_v2 WHERE employee_email='" . $email_pic[$i] . "'";
            $get_nama = $DBHCM->get_sql($sqlCheck);
            $employee_name = $get_nama[0]['employee_name'];
            $employee_email = $get_nama[0]['employee_email'];
            $final_email = $employee_email;
            $pic_name .= $employee_name . ', </br>';
            // $a .= $final_email . ",";
            $status = str_replace($final_email, 'Pending', $final_email);
            $status_pic .= $status . ', </br>';
        }
        $pic_name = substr($pic_name, 0, -7);
        $pic_board = $_POST['pic'];
        $status_pic = substr($status_pic, 0, -7);
    } else if (isset($_POST['pic_board'])) {
        $pic_board = '';
        $status_pic = '';
        $pic_name = '';
        foreach ($_POST["pic_board"] as $row) {
            $pic_board .= $row . ',';
            $rawpic_name = $DBHCM->get_sql("SELECT employee_name FROM sa_view_employees_v2 WHERE employee_email LIKE '%$row%' AND resign_date is null");
            $pic_name .= $rawpic_name[0]['employee_name'] . ', </br>';
            $status = str_replace($row, 'Pending', $row);
            $status_pic .= $status . ', </br>';
        }
        $pic_name = substr($pic_name, 0, -7);
        $status_pic = substr($status_pic, 0, -7);
        $pic_board = substr($pic_board, 0, -1);
    } else {
        $pic_name = null;
        $pic_board = null;
        $status_pic = null;
    }
    if (isset($_POST['no_bast'])) {
        $no_bast = $_POST['no_bast'];
    } else {
        $no_bast = null;
    }
    $status_error = 'Pending';

    $insert = sprintf(
        "(`so_number`,`order_number`,`pic_approval`,`project_code`,`project_name`,`customer_name`,`no_bast`,`email_requester`,`error_list`,`error_list2`,`error_list3`,`error_list4`,`error_list5`,`error_list6`,`error_list7`,`error_list8`,`error_category`,`error_category2`,`error_category3`,`error_category4`,`error_category5`,`error_category6`,`error_category7`,`error_category8`,`notes`,`notes2`,`notes3`,`notes4`,`notes5`,`notes6`,`notes7`,`notes8`,`nilai_error`,`pic_email`,`status_approval`,`status_error`,`status`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s) ON DUPLICATE KEY UPDATE `pic_approval`=%s,`no_bast`=%s,`error_list`=%s,`error_list2`=%s,`error_list3`=%s,`error_list4`=%s,`error_list5`=%s,`error_list6`=%s,`error_list7`=%s,`error_list8`=%s,`error_category`=%s,`error_category2`=%s,`error_category3`=%s,`error_category4`=%s,`error_category5`=%s,`error_category6`=%s,`error_category7`=%s,`error_category8`=%s,`notes`=%s,`notes2`=%s,`notes3`=%s,`notes4`=%s,`notes5`=%s,`notes6`=%s,`notes7`=%s,`notes8`=%s,`nilai_error`=%s,`pic_email`=%s,`status_error`=%s,`status`=%s, `order_number`=%s",
        GetSQLValueString($so_number, "text"),
        GetSQLValueString($order_number, "text"),
        GetSQLValueString($pic_name, "text"),
        GetSQLValueString($project_code, "text"),
        GetSQLValueString($_POST['project_name'], "text"),
        GetSQLValueString($_POST['customer_name'], "text"),
        GetSQLValueString($no_bast, "text"),
        GetSQLValueString($_SESSION['Microservices_UserEmail'], "text"),
        GetSQLValueString($error_type, "text"),
        GetSQLValueString($error_type2, "text"),
        GetSQLValueString($error_type3, "text"),
        GetSQLValueString($error_type4, "text"),
        GetSQLValueString($error_type5, "text"),
        GetSQLValueString($error_type6, "text"),
        GetSQLValueString($error_type7, "text"),
        GetSQLValueString($error_type8, "text"),
        GetSQLValueString($impact, "text"),
        GetSQLValueString($impact2, "text"),
        GetSQLValueString($impact3, "text"),
        GetSQLValueString($impact4, "text"),
        GetSQLValueString($impact5, "text"),
        GetSQLValueString($impact6, "text"),
        GetSQLValueString($impact7, "text"),
        GetSQLValueString($impact8, "text"),
        GetSQLValueString($notes, "text"),
        GetSQLValueString($notes2, "text"),
        GetSQLValueString($notes3, "text"),
        GetSQLValueString($notes4, "text"),
        GetSQLValueString($notes5, "text"),
        GetSQLValueString($notes6, "text"),
        GetSQLValueString($notes7, "text"),
        GetSQLValueString($notes8, "text"),
        GetSQLValueString(number_format($nilai_error, 5, ".", ""), "text"),
        GetSQLValueString($pic_board, "text"),
        GetSQLValueString($status_pic, "text"),
        GetSQLValueString($status_error, "text"),
        GetSQLValueString("Ready for Approve", "text"),
        GetSQLValueString($pic_name, "text"),
        GetSQLValueString($no_bast, "text"),
        GetSQLValueString($error_type, "text"),
        GetSQLValueString($error_type2, "text"),
        GetSQLValueString($error_type3, "text"),
        GetSQLValueString($error_type4, "text"),
        GetSQLValueString($error_type5, "text"),
        GetSQLValueString($error_type6, "text"),
        GetSQLValueString($error_type7, "text"),
        GetSQLValueString($error_type8, "text"),
        GetSQLValueString($impact, "text"),
        GetSQLValueString($impact2, "text"),
        GetSQLValueString($impact3, "text"),
        GetSQLValueString($impact4, "text"),
        GetSQLValueString($impact5, "text"),
        GetSQLValueString($impact6, "text"),
        GetSQLValueString($impact7, "text"),
        GetSQLValueString($impact8, "text"),
        GetSQLValueString($notes, "text"),
        GetSQLValueString($notes2, "text"),
        GetSQLValueString($notes3, "text"),
        GetSQLValueString($notes4, "text"),
        GetSQLValueString($notes5, "text"),
        GetSQLValueString($notes6, "text"),
        GetSQLValueString($notes7, "text"),
        GetSQLValueString($notes8, "text"),
        GetSQLValueString(number_format($nilai_error, 5, ".", ""), "text"),
        GetSQLValueString($pic_board, "text"),
        GetSQLValueString($status_error, "text"),
        GetSQLValueString("Ready for Approve", "text"),
        GetSQLValueString($order_number, "text")
    );
    $res = $DBKPISO->insert_data($tblname2, $insert);


    $condition_kpi_so = "order_number=" . "\"" . $order_number . "\"";
    $update_kpi_so = sprintf(
        "`pic`=%s,`board_status`=%s",
        GetSQLValueString($pic_name, "text"),
        GetSQLValueString("Ready for Approve", "text")
    );
    $res = $DBKPISO->update_data($tblname, $update_kpi_so, $condition_kpi_so);

    $insert = sprintf(
        "(`so_number`,`date`,`time`,`status`) VALUES (%s,%s,%s,%s) ON DUPLICATE KEY UPDATE `date`= %s,`time`= %s,`status`= %s",
        GetSQLValueString($so_number, "text"),
        GetSQLValueString($date, "text"),
        GetSQLValueString($time, "text"),
        GetSQLValueString("Status : Submitted <br> Nilai Error : " . $nilai_error . "<br> By : " . $_SESSION['Microservices_UserName'] . "", "text"),
        GetSQLValueString($date, "text"),
        GetSQLValueString($time, "text"),
        GetSQLValueString("Status : Submitted <br> Nilai Error : " . $nilai_error . "<br> By : " . $_SESSION['Microservices_UserName'] . "", "text")
    );
    $res = $DBKPISO->insert_data($tblname7, $insert);

    if (isset($_POST['nama_resource'])) {
        $combine_arr = array();
        for ($i = 0; $i < count($_POST['nama_resource']); $i++) {
            $combine_arr[] = array($_POST['nama_resource'][$i], $_POST['status_resource'][$i], $_POST['kpi_adjustment'][$i], $_POST['weighted_value'][$i], $_POST['note_exc'][$i]);
        }

        foreach ($combine_arr as $value) {
            if (empty($value[2])) {
                '';
            } else {
                $get_nilai_akhir_ideal = $DBKPISO->get_sql("SELECT * FROM sa_user WHERE order_number='$order_number' AND Nama LIKE '%" . $value[0] . "%'");
                $adjustment1 = $value[2] / 100;
                $max_value1 = $get_nilai_akhir_ideal[0]['nilai_akhir_ideal'] * $adjustment1;
                $max_value = str_replace(",", ".", $max_value1);
                $adjustment = str_replace(",", ".", $adjustment1);
                $check_data = $DBKPISO->get_sql("SELECT * FROM sa_kpi_exception WHERE resource_name='" . $value[0] . "' AND order_number='$order_number'");
                if (isset($check_data[0]['resource_name'])) {
                    $update_exception = $DBKPISO->get_res("UPDATE IGNORE sa_kpi_exception SET kpi_adjustment='$adjustment', weighted_value='$max_value', note='" . $value[4] . "' WHERE resource_name='" . $value[0] . "' AND order_number='$order_number'");
                    $update_sa_user = $DBKPISO->get_res("UPDATE IGNORE sa_user SET cte='$adjustment', nilai_akhir_aktual='$max_value' WHERE Nama LIKE '%" . $value[0] . "%' AND order_number='$order_number'");
                } else {
                    $insert_exception = $DBKPISO->get_res("INSERT IGNORE INTO sa_kpi_exception (`resource_name`, `project_code`, `so_number`, `order_number`, `status_resource`, `kpi_adjustment`, `weighted_value`, `note`) VALUES ('$value[0]','$project_code','$so_number', '$order_number', '$value[1]','$adjustment','$max_value','$value[4]')");
                    $update_sa_user = $DBKPISO->get_res("UPDATE sa_user SET cte='$adjustment', nilai_akhir_aktual='$max_value' WHERE Nama LIKE '%" . $value[0] . "%' AND order_number='$order_number'");
                    //var_dump($value[0], $adjustment, $max_value, $update_sa_user);die;       
                }
            }
            if (empty($value[4])) {
                '';
            } else {
                $insert_note = sprintf(
                    "(`so_number`,`date`,`time`,`status`) VALUES (%s,%s,%s,%s) ON DUPLICATE KEY UPDATE `date`= %s,`time`= %s,`status`= %s",
                    GetSQLValueString($so_number, "text"),
                    GetSQLValueString($date, "text"),
                    GetSQLValueString($time, "text"),
                    GetSQLValueString("Add Note : " . $value[4] . " to " . $value[0] . " <br> By : " . $_SESSION['Microservices_UserName'] . "", "text"),
                    GetSQLValueString($date, "text"),
                    GetSQLValueString($time, "text"),
                    GetSQLValueString("Add Note : " . $value[4] . " to " . $value[0] . " <br> By : " . $_SESSION['Microservices_UserName'] . "", "text")
                );
                $res = $DBKPISO->insert_data($tblname7, $insert_note);
            }
        }
    }

    $a = "";
    $email_pic = explode(",", $pic_board);
    for ($i = 0; $i < count($email_pic); $i++) {
        $email = $email_pic[$i];
        $sqlCheck = "SELECT employee_name,employee_email FROM sa_view_employees_v2 WHERE employee_email='" . $email_pic[$i] . "'";
        $get_nama = $DBHCM->get_sql($sqlCheck);
        $employee_name = $get_nama[0]['employee_name'];
        $employee_email = $get_nama[0]['employee_email'];
        $final_email = $employee_email;
        $a .= $final_email . ",";
    }
    $final_a = rtrim($a, ",");
    $ab = "$final_a";

    $get_data_b = $DBKPISO->get_sql("SELECT * FROM sa_kpi_board WHERE order_number='$order_number'");
    $id_kpi = $get_data_b[0]['id'];
    $get_data_so = $DBKPISO->get_sql("SELECT * FROM sa_data_so WHERE order_number='$order_number'");
    $project_name = $get_data_so[0]['project_name'];
    $customer_name = $get_data_so[0]['customer_name'];
    $id_project = $DBWR->get_sql("SELECT id from sa_wrike_project_list WHERE order_number='$order_number' AND project_type='MSI Project Implementation'");
    $idproject = isset($id_project[0]['id']);
    if (empty($idproject)) {
        $id = null;
    } else {
        $id = $id_project[0]['id'];
    }
    $get_engineer = $DBWR->get_sql("SELECT DISTINCT a.resource_email from sa_view_dashboard_wrike_resource a left join sa_wrike_project_list b ON a.project_id=b.id where b.no_so='$so_number'");
    $get_data_kpi = $DBKPISO->get_sql("SELECT * from sa_kpi_so_wr WHERE order_number='$order_number'");
    $weighted_val = $get_data_kpi[0]['weighted_value'];
    $get_sb_mandays = $DBKPISO->get_sql("SELECT * FROM sa_data_so WHERE order_number='$order_number' AND SB_service_type_implementation=1");
    $persen_cost = $get_data_kpi[0]['commercial_kpi'] * 100;
    $bast_planing = isset($get_sb_mandays[0]['WR_bast_date_project_implementation']);
    if (empty($bast_planing)) {
        $bast_plan = 'Empty';
    } else {
        $bast_plan = $get_sb_mandays[0]['WR_bast_date_project_implementation'];
    }
    $bast_actualing = isset($get_sb_mandays[0]['WR_bast_date_actual_project_implementation']);
    if (empty($bast_actualing)) {
        $bast_actual = 'Empty';
    } else {
        $bast_actual = $get_sb_mandays[0]['WR_bast_date_actual_project_implementation'];
    }
    $persen_error = $nilai_error * 100;
    if ($nilai_error >= 0.06 && $nilai_error < 0.12) {
        $error_result = 'Minor';
    } else if ($nilai_error >= 0.12 && $nilai_error < 0.2) {
        $error_result = 'Major';
    } else if ($nilai_error >= 0.2) {
        $error_result = 'Critical';
    } else {
        $error_result = 'Normal';
    }
    $SB_mandays_implementationraw = isset($get_sb_mandays[0]['SB_mandays_implementation']);
    if (empty($SB_mandays_implementationraw)) {
        $SB_mandays_implementation = 0;
    } else {
        $SB_mandays_implementation = $get_sb_mandays[0]['SB_mandays_implementation'];
    }
    $CR_mandays_implementationraw = isset($get_sb_mandays[0]['CR_mandays_implementation']);
    if (empty($CR_mandays_implementationraw)) {
        $CR_mandays_implementation = 0;
    } else {
        $CR_mandays_implementation = $get_sb_mandays[0]['CR_mandays_implementation'];
    }
    $WR_mandays_implementationraw = isset($get_sb_mandays[0]['WR_mandays_actual_implementation']);
    if (empty($WR_mandays_implementationraw)) {
        $WR_mandays_implementation = 0;
    } else {
        $WR_mandays_implementation = round($get_sb_mandays[0]['WR_mandays_actual_implementation']);
    }
    $persen_time =  $get_data_kpi[0]['time_kpi'] * 100;
    $total_kpi = 100 - $persen_cost - $persen_time - $persen_error;
    $user_email = $_SESSION['Microservices_UserEmail'];
    $from =  $user_email;
    $cc = $from;
    $bcc = "";
    $to = $ab;
    // $to = "chrisheryanda@mastersystem.co.id";
    $msg1 = "<p>Dengan ini saya mengajukan Approval untuk KPI Panel Project:</p>";
    $msg2 = "<p>Mohon untuk direview dan diberikan persetujuan.</p>";
    $reply = $from;
    $subject = "[KPI] Request Approval KPI Project :  " . $project_name . " � " .     $project_code . "";
    $msg = "<table width='100%'";
    $msg .= "<tr><td rowspan='3'></td><td style='width:100%;'>";
    $msg .= "<br/>";
    $msg .= "<p>Dear All, </p>";
    $msg .= "<p>" . $msg1 . "</p>";
    $msg .= "<p>";
    $msg .= "<div style='width:980px; margin:0 auto;'>";
    $msg .= "<table style='width:100%; border-collapse: collapse;'>";
    $msg .= "<tr><td>Project Name</td><td>: </td><td>" . $project_name . "</td></tr>";
    $msg .= "<tr><td>Customer Name</td><td>: </td><td>" . $customer_name . "</td></tr>";
    $msg .= "<tr><td>Project Code</td><td>: </td><td>" . $project_code . "</td></tr>";
    $msg .= "<tr><td>SO Number</td><td>: </td><td>" . $so_number . "</td></tr>";
    $msg .= "</table>";
    $msg .= "<table class='table' style='width:100%; border-collapse: collapse;'>";
    $msg .= "<thead class='thead-light'>";
    $msg .= "<tr>";
    $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Cost (%)</th>";
    $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Time (%)</th>";
    $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Error (%)</th>";
    $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Total KPI (%)</th>";
    $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Weighted Value</th>";
    $msg .= "</tr>";
    $msg .= "</thead>";
    $msg .= "<tbody>";
    $msg .= "<tr>";
    $msg .= "<td style='border: solid 1px #777; text-align:center;'>";
    $msg .= "<label for='inputCID3' class='col-sm-8 col-form-label col-form-label-sm'>" . $persen_cost . "% | " . $get_data_kpi[0]['commercial_category'] . "</label>";
    $msg .= "<div class='row mb-1'>";
    $msg .= "<label for='inputCID3' class='col-sm-8 col-form-label col-form-label-sm'>" . $SB_mandays_implementation . " | " . $CR_mandays_implementation . " | " . $WR_mandays_implementation . "</label>";
    $msg .= "</div>";
    $msg .= "</td>";
    $msg .= "<td style='border: solid 1px #777; text-align:center;'>";
    $msg .= "<label for='inputCID3' class='col-sm-8 col-form-label col-form-label-sm'>" . $persen_time . "% | " . $get_data_kpi[0]['time_category'] . "</label>";
    $msg .= "<div class='row mb-1'>";
    $msg .= "<label for='inputCID3' class='col-sm-8 col-form-label col-form-label-sm'>" . $bast_plan . " | " . $bast_actual . "</label>";
    $msg .= "</div>";
    $msg .= "</td>";
    $msg .= "<td style='border: solid 1px #777; text-align:center;'>" . $persen_error . "% | " . $error_result . "</label></td>";
    $msg .= "<td style='text-align:center; border: solid 1px #777;'>" . $total_kpi . "%" . "</label></td>";
    $msg .= "<td style='text-align:center; border: solid 1px #777;'>" . $weighted_val . "</label></td>";
    $msg .= "</tr>";
    $msg .= "<tr>";
    $msg .= "<td>&nbsp;</td>";
    $msg .= "</tr>";
    $msg .= "</tbody>";
    $msg .= "</table>";
    if (isset($get_engineer[0]['resource_email'])) {
        $msg .= "<table class='table' style='width:100%; border-collapse: collapse;'>";
        $msg .= "<thead class='thead-light'>";
        $msg .= "<tr>";
        $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Resource Name</th>";
        $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Roles</th>";
        $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Productivity</th>";
        $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Total KPI (%)</th>";
        $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Weighted Value</th>";
        $msg .= "</tr>";
        $msg .= "</thead>";
        $msg .= "<tbody>";
        do {
            $id_project2 = $DBWR->get_sql("SELECT id from sa_wrike_project_list WHERE order_number='$order_number' AND project_type='MSI Project Implementation'");
            $idproject2 = isset($id_project2[0]['id']);
            if (empty($idproject2)) {
                $id2 = null;
            } else {
                $id2 = $id_project2[0]['id'];
            }
            $get_name = $DBHCM->get_sql("SELECT employee_name from sa_view_employees WHERE employee_email='" . $get_engineer[0]['resource_email'] . "'");
            $get_leader_resource = get_leader($get_engineer[0]['resource_email']);
            $get_roles = $DBWR->get_sql("SELECT * FROM sa_resource_assignment WHERE resource_email LIKE '%" . $get_engineer[0]['resource_email'] . "%' AND order_number='$order_number'");
            // $get_jumlah_task_resource = $DBWR->get_sql("SELECT COUNT(task_name) AS jumlah_task FROM sa_wrike_assignment WHERE project_id='$id2' AND resource_email='" . $get_engineer[0]['resource_email'] . "'");
            $get_resource_id = $DBWR->get_sql("SELECT resource_id FROM sa_wrike_project_resources WHERE resource_email='" . $get_engineer[0]['resource_email'] . "'");
            // $get_jumlah_task_resource_update = $DBWR->get_sql("SELECT COUNT(task_id) AS updated_task FROM sa_view_task_timelog WHERE project_id='$id2' AND resource_id='" . $get_resource_id[0]['resource_id'] . "' GROUP BY task_id");
            $get_jumlah_task_resource = $DBGC->get_sqlV2("SELECT COUNT(task_name) AS jumlah_task FROM sa_preschedule WHERE project_id='$id2' AND resource_email='" . $get_engineer[0]['resource_email'] . "'");
            $get_jumlah_task_resource_update = $DBGC->get_sqlV2("SELECT COUNT(a.project_id) as updated_task FROM sa_preschedule a left join sa_schedule b ON a.google_event_id=b.event_id WHERE a.project_id = '$id2' AND b.attendees_email='" . $get_engineer[0]['resource_email'] . "' AND b.response_status='accepted' AND b.project_type='MSI Project Implementation'");
            $get_data_kpi_user = $DBKPISO->get_sql("SELECT * FROM sa_kpi_exception WHERE resource_name='" . $get_engineer[0]['resource_email'] . "' AND order_number='$order_number'");
            if (empty($get_data_kpi_user[0]['kpi_adjustment'])) {
                $get_kpi_user = $DBKPISO->get_sql("SELECT * FROM sa_user WHERE Nama LIKE '%" . $get_engineer[0]['resource_email'] . "%' AND order_number='$order_number'");
                $kpi_adjustment = $get_kpi_user[0]['cte'] * 100;
                $weighted_value_user = $get_kpi_user[0]['nilai_akhir_aktual'];
            } else {
                $kpi_adjustment = $get_data_kpi_user[0]['kpi_adjustment'];
                $weighted_value_user = $get_data_kpi_user[0]['weighted_value'];
            }
            $msg .= "<tr>";
            $msg .= "<td style='border: solid 1px #777; text-align:center;'>" . $get_name[0]['employee_name'] . "</td>";
            if (isset($get_roles[0]['project_roles'])) {
                $msg .= "<td style='border: solid 1px #777; text-align:center;'>" . $get_roles[0]['project_roles'] . "</td>";
            } else {
                $msg .= "<td style='border: solid 1px #777; text-align:center;'>Kosong</td>";
            }
            $msg .= "<td style='border: solid 1px #777; text-align:center;'>" . $get_jumlah_task_resource[0]['jumlah_task'] . " | " . $get_jumlah_task_resource_update[0]['updated_task'] . "</td>";
            $msg .= "<td style='text-align:center; border: solid 1px #777;'>" . $kpi_adjustment . "%" . "</label></td>";
            $msg .= "<td style='text-align:center; border: solid 1px #777;'>" . $weighted_value_user . "</label></td>";
            $msg .= "</tr>";
        } while ($get_engineer[0] = $get_engineer[1]->fetch_assoc());
        $msg .= "<tr>";
        $msg .= "<td>&nbsp;</td>";
        $msg .= "</tr>";
        $msg .= "</tbody>";
        $msg .= "</table>";
    }
    $msg .= "</p>";
    $msg .= "<p>" . $msg2 . "</p>";
    $msg .= "<p>Terimakasih,<br/>";
    $msg .= $_SESSION['Microservices_UserName'] . "</p>";
    $msg .= "</td><td width='30%' rowspan='3'>";
    $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id/index.php?mod=kpi_board'>MSIZone</a></td><td><a href='https://msizone.mastersystem.co.id/index.php?mod=kpi_board&act=review&id=" . $id_kpi . "&so_number=" . $so_number . "'>Approve</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
    $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
    $msg .= "</table>";
    $msg .= "</div>";

    $headers = "From: " . $from . "\r\n" .
        "Reply-To: " . $reply . "\r\n" .
        "Cc: " . $cc . "\r\n" .
        "Bcc: " . $bcc . "\r\n" .
        "MIME-Version: 1.0" . "\r\n" .
        "Content-Type: text/html; charset=UTF-8" . "\r\n" .
        "X-Mailer: PHP/" . phpversion();

    $ALERT = new Alert();
    if (!mail(
        $to,
        $subject,
        $msg,
        $headers
    )) {
        echo
        "Email gagal terkirim pada jam " . date("d M Y G:i:s") . "$to" . "/ $subject";
    } else {
        echo
        "Email terkirim pada jam " . date("d M Y G:i:s");
    }

    $ALERT->savedata();
} elseif (isset($_POST['add'])) {
    $dbdb = "DASHBOARD_KPI";
    $DBKPISO = get_conn($dbdb);
    $check_data = $DBKPISO->get_sql("SELECT * FROM sa_kpi_board WHERE order_number ='" . $_POST['order_number'] . "'");
    $pic = isset($check_data[0]['pic_email']);
    if ($pic == NULL) {
        $pic = '';
    } else {
        $pic = $check_data[0]['pic_email'];
    }
    date_default_timezone_set('Asia/Jakarta');
    $date = date("Y-M-d");
    $time = date("H:i:s");
    $so_number = $_POST['so_number'];
    $order_number = $_POST['order_number'];
    if (isset($_POST['project_code'])) {
        $project_code = $_POST['project_code'];
    } else {
        $project_code = '';
    }
    if (isset($_POST['no_bast'])) {
        $no_bast = $_POST['no_bast'];
    } else {
        $no_bast = '';
    }
    if (isset($_POST['notes'])) {
        $notes = $_POST['notes'];
    } else {
        $notes = '';
    }
    if (isset($_POST['verif_status'])) {
        $verif_status = $_POST['verif_status'];
    } else {
        $verif_status = '';
    }
    if (isset($_POST['pic'])) {
        $pic_board = $_POST['pic'];
    } else if (isset($_POST['pic_board'])) {
        $pic_board = '';
        $status_pic = '';
        foreach ($_POST["pic_board"] as $row) {
            $pic_board .= $row . ',';
            $status = str_replace($row, 'Pending', $row);
            $status_pic .= $status . ', </br>';
        }
        $status_pic = substr($status_pic, 0, -7);
        $pic_board = substr($pic_board, 0, -1);
    } else {
        $pic_board = null;
    }
    if (isset($_POST['verif_status'])) {
        $verif_status = '';
        foreach ($_POST["verif_status"] as $row) {
            $verif_status .= $row . '; ';
        }
        $verif_status = substr($verif_status, 0, -1);
    }

    $insert = sprintf(
        "(`project_code`,`so_number`,`order_number`,`project_name`,`customer_name`,`no_bast`,`verif_status`,`pic_email`,`notes`,`status_approval`,`status`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s) ON DUPLICATE KEY UPDATE `no_bast`= %s,`verif_status`= %s,`pic_email`=%s,`notes`=%s,`status`=%s,`order_number`=%s",
        GetSQLValueString($project_code, "text"),
        GetSQLValueString($so_number, "text"),
        GetSQLValueString($order_number, "text"),
        GetSQLValueString($_POST['project_name'], "text"),
        GetSQLValueString($_POST['customer_name'], "text"),
        GetSQLValueString($no_bast, "text"),
        GetSQLValueString($verif_status, "text"),
        GetSQLValueString($pic_board, "text"),
        GetSQLValueString($notes, "text"),
        GetSQLValueString($status_pic, "text"),
        GetSQLValueString("Draft", "text"),
        GetSQLValueString($no_bast, "text"),
        GetSQLValueString($verif_status, "text"),
        GetSQLValueString($pic_board, "text"),
        GetSQLValueString($notes, "text"),
        GetSQLValueString("Draft", "text"),
        GetSQLValueString($order_number, "text")
    );
    $res = $DBKPISO->insert_data($tblname2, $insert);

    $condition = "order_number=" . "\"" . $_POST['order_number'] . "\"";
    $update = sprintf(
        "`board_status`=%s",
        GetSQLValueString("Draft", "text")
    );
    $res = $DBKPISO->update_data($tblname, $update, $condition);
    $res = $DBKPISO->update_data($tblname4, $update, $condition);

    $condition2 = "order_number=" . "\"" . $_POST['order_number'] . "\"";
    $update2 = sprintf(
        "`board_status`=%s",
        GetSQLValueString("Draft", "text")
    );
    $res = $DBKPISO->update_data($tblname3, $update2, $condition2);

    $insert1 = sprintf(
        "(`so_number`,`date`,`time`,`status`) VALUES (%s,%s,%s,%s) ON DUPLICATE KEY UPDATE `date`= %s,`time`= %s,`status`= %s",
        GetSQLValueString($so_number, "text"),
        GetSQLValueString($date, "text"),
        GetSQLValueString($time, "text"),
        GetSQLValueString("Status : " . $verif_status . " <br> Notes : " . $notes . "<br> By : " . $_SESSION['Microservices_UserName'] . "", "text"),
        GetSQLValueString($date, "text"),
        GetSQLValueString($time, "text"),
        GetSQLValueString("Status : " . $verif_status . " <br> Notes : " . $notes . "<br> By : " . $_SESSION['Microservices_UserName'] . "", "text")
    );
    $res = $DBKPISO->insert_data($tblname7, $insert1);

    $ALERT->savedata();
} elseif (isset($_POST['submit_review'])) {
    $dbdb = "DASHBOARD_KPI";
    $DBKPISO = get_conn($dbdb);
    $check_data = $DBKPISO->get_sql("SELECT * FROM sa_kpi_board WHERE order_number ='" . $_POST['order_number'] . "'");
    $pic = isset($check_data[0]['pic_email']);
    if ($pic == NULL) {
        $pic = '';
    } else {
        $pic = $check_data[0]['pic_email'];
    }
    date_default_timezone_set('Asia/Jakarta');
    $date = date("Y-M-d");
    $time = date("H:i:s");
    $so_number = $_POST['so_number'];
    $order_number = $_POST['order_number'];
    if (isset($_POST['project_code'])) {
        $project_code = $_POST['project_code'];
    } else {
        $project_code = '';
    }
    if (isset($_POST['resources'])) {
        $resources = '';
        foreach ($_POST["resources"] as $row) {
            $resources .= $row . ',';
        }
        $resources = substr($resources, 0, -1);
    } else {
        $resources = '';
    }
    // echo $resources . "INI RESOURCE";
    if (isset($_POST['no_bast'])) {
        $no_bast = $_POST['no_bast'];
    } else {
        $no_bast = '';
    }
    if (isset($_POST['notes'])) {
        $notes = $_POST['notes'];
    } else {
        $notes = '';
    }
    if (isset($_POST['verif_status'])) {
        $verif_status = $_POST['verif_status'];
    } else {
        $verif_status = '';
    }
    if (isset($_POST['pic'])) {
        $pic_board = $_POST['pic'];
        $status_pic = '';
    } else if (isset($_POST['pic_board'])) {
        $pic_board = '';
        $status_pic = '';
        $pic_name = '';
        foreach ($_POST["pic_board"] as $row) {
            $pic_board .= $row . ',';
            $status = str_replace($row, 'Pending', $row);
            $status_pic .= $status . ', </br>';
            $rawpic_name = $DBHCM->get_sql("SELECT employee_name FROM sa_view_employees_v2 WHERE employee_email LIKE '%$row%' AND resign_date IS NULL");
            $pic_name .= $rawpic_name[0]['employee_name'] . ', </br>';
        }
        $pic_name = substr($pic_name, 0, -7);
        $status_pic = substr($status_pic, 0, -7);
        $pic_board = substr($pic_board, 0, -1);
    } else {
        $pic_board = null;
    }
    if (isset($_POST['verif_status'])) {
        $verif_status = '';
        foreach ($_POST["verif_status"] as $row) {
            $verif_status .= $row . '; ';
        }
        $verif_status = substr($verif_status, 0, -1);
    }

    if (isset($_POST['tanggal_ic'])) {
        $tanggal_ic = $_POST['tanggal_ic'];
    } else {
        $tanggal_ic = null;
    }

    $insert = sprintf(
        "(`email_requester`,`pic_approval`,`so_number`,`order_number`,`project_code`,`project_name`,`customer_name`,`no_bast`,`verif_status`,`resources`,`pic_email`,`note`,`status_approval`,`status`,`tanggal_ic`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s) ON DUPLICATE KEY UPDATE `tanggal_ic`=%s,`no_bast`= %s,`verif_status`= %s,`resources`=%s,`pic_email`=%s,`pic_approval`=%s,`notes`=%s,`status`=%s,`order_number`=%s",
        GetSQLValueString($_SESSION['Microservices_UserEmail'], "text"),
        GetSQLValueString($pic_name, "text"),
        GetSQLValueString($so_number, "text"),
        GetSQLValueString($order_number, "text"),
        GetSQLValueString($project_code, "text"),
        GetSQLValueString($_POST['project_name'], "text"),
        GetSQLValueString($_POST['customer_name'], "text"),
        GetSQLValueString($no_bast, "text"),
        GetSQLValueString($verif_status, "text"),
        GetSQLValueString($resources, "text"),
        GetSQLValueString($pic_board, "text"),
        GetSQLValueString($notes, "text"),
        GetSQLValueString($status_pic, "text"),
        GetSQLValueString("Ready for Review", "text"),
        GetSQLValueString($tanggal_ic, "text"),
        GetSQLValueString($tanggal_ic, "text"),
        GetSQLValueString($no_bast, "text"),
        GetSQLValueString($verif_status, "text"),
        GetSQLValueString($resources, "text"),
        GetSQLValueString($pic_board, "text"),
        GetSQLValueString($pic_name, "text"),
        GetSQLValueString($notes, "text"),
        GetSQLValueString("Ready for Review", "text"),
        GetSQLValueString($order_number, "text")
    );
    $res = $DBKPISO->insert_data($tblname2, $insert);

    $condition = "order_number='$order_number'";
    $update = sprintf(
        "`board_status`=%s",
        GetSQLValueString("Ready for Review", "text")
    );
    $res = $DBKPISO->update_data($tblname, $update, $condition);
    $res = $DBKPISO->update_data($tblname4, $update, $condition);

    $condition_kpi_so = "order_number=" . "\"" . $order_number . "\"";
    $update_kpi_so = sprintf(
        "`pic`=%s",
        GetSQLValueString($pic_name, "text")
    );
    $res = $DBKPISO->update_data($tblname, $update_kpi_so, $condition_kpi_so);

    $condition2 = "order_number='$order_number'";
    $update2 = sprintf(
        "`board_status`=%s",
        GetSQLValueString("Ready for Review", "text")
    );
    $res = $DBKPISO->update_data($tblname3, $update2, $condition2);

    $insert1 = sprintf(
        "(`so_number`,`date`,`time`,`status`) VALUES (%s,%s,%s,%s) ON DUPLICATE KEY UPDATE `date`= %s,`time`= %s,`status`= %s",
        GetSQLValueString($so_number, "text"),
        GetSQLValueString($date, "text"),
        GetSQLValueString($time, "text"),
        GetSQLValueString("Status : " . $verif_status . " <br> Notes : " . $notes . "<br> By : " . $_SESSION['Microservices_UserName'] . "", "text"),
        GetSQLValueString($date, "text"),
        GetSQLValueString($time, "text"),
        GetSQLValueString("Status : " . $verif_status . " <br> Notes : " . $notes . "<br> By : " . $_SESSION['Microservices_UserName'] . "", "text")
    );
    $res = $DBKPISO->insert_data($tblname7, $insert1);

    $a = "";
    $email_pic = explode(",", $pic_board);
    for ($i = 0; $i < count($email_pic); $i++) {
        $email = $email_pic[$i];
        $sqlCheck = "SELECT employee_name,employee_email FROM sa_view_employees_v2 WHERE employee_email='" . $email_pic[$i] . "'";
        $get_nama = $DBHCM->get_sql($sqlCheck);
        $employee_name = $get_nama[0]['employee_name'];
        $employee_email = $get_nama[0]['employee_email'];
        $final_email = $employee_email;
        $a .= $final_email . ",";
    }
    $final_a = rtrim($a, ",");
    $ab = "$final_a";

    $get_data_b = $DBKPISO->get_sql("SELECT * FROM sa_kpi_board WHERE order_number='$order_number'");
    $id_kpi = $get_data_b[0]['id'];
    $get_data_so = $DBKPISO->get_sql("SELECT * FROM sa_data_so WHERE order_number='$order_number'");
    $project_name = $get_data_so[0]['project_name'];
    $customer_name = $get_data_so[0]['customer_name'];
    $implementation_price = $get_data_so[0]['SB_amount_idr'];
    $po_maintenance = $get_data_so[0]['SB_maintenance_price'];
    $po_warranty = $get_data_so[0]['SB_warranty_price'];
    $value = $implementation_price - $po_maintenance - $po_warranty;
    $id_project = $DBWR->get_sql("SELECT id from sa_wrike_project_list WHERE order_number='$order_number' AND project_type='MSI Project Implementation'");
    $idproject = isset($id_project[0]['id']);
    if (empty($idproject)) {
        $id = null;
    } else {
        $id = $id_project[0]['id'];
    }
    $get_engineer = $DBWR->get_sql("SELECT DISTINCT a.resource_email from sa_view_dashboard_wrike_resource a left join sa_wrike_project_list b ON a.project_id=b.id where b.no_so='$so_number'");
    $get_data_kpi = $DBKPISO->get_sql("SELECT * from sa_kpi_so_wr WHERE order_number='$order_number'");
    $weighted_val = $get_data_kpi[0]['weighted_value'];
    $get_sb_mandays = $DBKPISO->get_sql("SELECT * FROM sa_data_so WHERE order_number='$order_number' AND SB_service_type_implementation=1");
    $persen_cost = $get_data_kpi[0]['commercial_kpi'] * 100;
    $bast_planing = $get_sb_mandays[0]['WR_bast_date_project_implementation'];
    if (empty($bast_planing)) {
        $bast_plan = 'Empty';
    } else {
        $bast_plan = $bast_planing;
    }
    $bast_actualing = $get_sb_mandays[0]['WR_bast_date_actual_project_implementation'];
    if (empty($bast_actualing)) {
        $bast_actual = 'Empty';
    } else {
        $bast_actual = $bast_actualing;
    }
    $cek_error = $DBKPISO->get_sql("SELECT * from sa_kpi_board WHERE order_number='$order_number'");
    $nilai = isset($cek_error[0]['nilai_error']);
    if (empty($nilai)) {
        $nilaierror = 0;
        $persen_error = $nilaierror * 100;
    } else {
        $nilaierror = $cek_error[0]['nilai_error'];
        $persen_error = $nilaierror * 100;
    }
    if ($nilaierror >= 0.06 && $nilaierror < 0.12) {
        $error_result = 'Minor';
    } else if ($nilaierror >= 0.12 && $nilaierror < 0.2) {
        $error_result = 'Major';
    } else if ($nilaierror >= 0.2) {
        $error_result = 'Critical';
    } else {
        $error_result = 'Normal';
    }
    $persen_time =  $get_data_kpi[0]['time_kpi'] * 100;
    $total_kpi = 100 - $persen_cost - $persen_time - $persen_error;
    $user_email = $_SESSION['Microservices_UserEmail'];
    $from = $user_email;
    $to = $resources . ",lucky.andiani@mastersystem.co.id";
    // $to = "chrisheryanda@mastersystem.co.id";
    $cc = $from;
    $bcc = "";
    $msg3 = "<p>Dengan ini memberitahukan untuk KPI status project ini telah closed. </br> Mohon agar setiap resource dapat melakukan pengecekan pada penilaian KPI pada project ini:</p>";
    $msg4 = "<p>Apabila tidak ada konfirmasi dalam 3 hari kerja pada email ini, maka Data KPI tersebut dianggap sudah sesuai dan akan diteruskan kepada KPI Panel untuk dilakukan penilaian.</p>";
    $reply = $from;
    $subject = "[KPI] Konfirmasi KPI : " . $project_name . " � " .     $project_code . "";
    $msg = "<table width='100%'";
    $msg .= "<tr><td rowspan='3'></td><td style='width:100%;'>";
    // $msg .= "<img src='https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png'>";
    $msg .= "<br/>";
    $msg .= "<p>Dear All, </p>";
    $msg .= "<p>" . $msg3 . "</p>";
    $msg .= "<p>";
    $msg .= "<div style='width:980px; margin:0 auto;'>";
    $msg .= "<table style='width:100%; border-collapse: collapse;'>";
    $msg .= "<tr><td>Project Name</td><td>: </td><td>" . $project_name . "</td></tr>";
    $msg .= "<tr><td>Customer Name</td><td>: </td><td>" . $customer_name . "</td></tr>";
    $msg .= "<tr><td>Project Code</td><td>: </td><td>" . $project_code . "</td></tr>";
    $msg .= "<tr><td>SO Number</td><td>: </td><td>" . $so_number . "</td></tr>";
    $msg .= "<tr><td>Nilai Project</td><td>: </td><td>" . $value . "</td></tr>";
    $msg .= "</table>";
    $msg .= "<table class='table' style='width:100%; border-collapse: collapse;'>";
    $msg .= "<thead class='thead-light'>";
    $msg .= "<tr>";
    $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Cost (%)</th>";
    $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Time (%)</th>";
    $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Error (%)</th>";
    $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Total KPI (%)</th>";
    $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Weighted Value</th>";
    $msg .= "</tr>";
    $msg .= "</thead>";
    $msg .= "<tbody>";
    $msg .= "<tr>";
    $msg .= "<td style='border: solid 1px #777; text-align:center;'>";
    $msg .= "<label for='inputCID3' class='col-sm-8 col-form-label col-form-label-sm'>" . $persen_cost . "% | " . $get_data_kpi[0]['commercial_category'] . "</label>";
    $msg .= "<div class='row mb-1'>";
    $msg .= "<label for='inputCID3' class='col-sm-8 col-form-label col-form-label-sm'>" . $get_sb_mandays[0]['SB_mandays_implementation'] . " | " . $get_sb_mandays[0]['CR_mandays_implementation'] . " | " . round($get_sb_mandays[0]['WR_mandays_actual_implementation']) . "</label>";
    $msg .= "</div>";
    $msg .= "</td>";
    $msg .= "<td style='border: solid 1px #777; text-align:center;'>";
    $msg .= "<label for='inputCID3' class='col-sm-8 col-form-label col-form-label-sm'>" . $persen_time . "% | " . $get_data_kpi[0]['time_category'] . "</label>";
    $msg .= "<div class='row mb-1'>";
    $msg .= "<label for='inputCID3' class='col-sm-8 col-form-label col-form-label-sm'>" . $bast_plan . " | " . $bast_actual . "</label>";
    $msg .= "</div>";
    $msg .= "</td>";
    $msg .= "<td style='border: solid 1px #777; text-align:center;'>" . $persen_error . "% | " . $error_result . "</label></td>";
    $msg .= "<td style='text-align:center; border: solid 1px #777;'>" . $total_kpi . "%" . "</label></td>";
    $msg .= "<td style='text-align:center; border: solid 1px #777;'>" . $weighted_val . "</label></td>";
    $msg .= "</tr>";
    $msg .= "<tr>";
    $msg .= "<td>&nbsp;</td>";
    $msg .= "</tr>";
    $msg .= "</tbody>";
    $msg .= "</table>";
    if (isset($get_engineer[0]['resource_email'])) {
        $msg .= "<table class='table' style='width:100%; border-collapse: collapse;'>";
        $msg .= "<thead class='thead-light'>";
        $msg .= "<tr>";
        $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Resource Name</th>";
        $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Roles</th>";
        $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Productivity</th>";
        $msg .= "</tr>";
        $msg .= "</thead>";
        $msg .= "<tbody>";
        do {
            $id_project2 = $DBWR->get_sql("SELECT id from sa_wrike_project_list WHERE order_number='$order_number' AND project_type='MSI Project Implementation'");
            $idproject2 = isset($id_project2[0]['id']);
            if (empty($idproject2)) {
                $id2 = null;
            } else {
                $id2 = $id_project2[0]['id'];
            }
            $get_name = $DBHCM->get_sql("SELECT employee_name from sa_view_employees WHERE employee_email='" . $get_engineer[0]['resource_email'] . "'");
            $get_leader_resource = get_leader($get_engineer[0]['resource_email']);
            $get_roles = $DBWR->get_sql("SELECT * FROM sa_resource_assignment WHERE resource_email LIKE '%" . $get_engineer[0]['resource_email'] . "%' AND order_number='$order_number'");
            // $get_jumlah_task_resource = $DBWR->get_sql("SELECT COUNT(task_name) AS jumlah_task FROM sa_wrike_assignment WHERE project_id='$id2' AND resource_email='" . $get_engineer[0]['resource_email'] . "'");
            $get_resource_id = $DBWR->get_sql("SELECT resource_id FROM sa_wrike_project_resources WHERE resource_email='" . $get_engineer[0]['resource_email'] . "'");
            // $get_jumlah_task_resource_update = $DBWR->get_sql("SELECT COUNT(task_id) AS updated_task FROM sa_view_task_timelog WHERE project_id='$id2' AND resource_id='" . $get_resource_id[0]['resource_id'] . "' GROUP BY task_id");
            $get_jumlah_task_resource = $DBGC->get_sqlV2("SELECT COUNT(task_name) AS jumlah_task FROM sa_preschedule WHERE project_id='$id2' AND resource_email='" . $get_engineer[0]['resource_email'] . "'");
            $get_jumlah_task_resource_update = $DBGC->get_sqlV2("SELECT COUNT(a.project_id) as updated_task FROM sa_preschedule a left join sa_schedule b ON a.google_event_id=b.event_id WHERE a.project_id = '$id2' AND b.attendees_email='" . $get_engineer[0]['resource_email'] . "' AND b.response_status='accepted' AND b.project_type='MSI Project Implementation'");
            $msg .= "<tr>";
            $msg .= "<td style='border: solid 1px #777; text-align:center;'>" . $get_name[0]['employee_name'] . "</td>";
            if (isset($get_roles[0]['project_roles'])) {
                $msg .= "<td style='border: solid 1px #777; text-align:center;'>" . $get_roles[0]['project_roles'] . "</td>";
            } else {
                $msg .= "<td style='border: solid 1px #777; text-align:center;'>Kosong</td>";
            }
            $msg .= "<td style='border: solid 1px #777; text-align:center;'>" . $get_jumlah_task_resource[0]['jumlah_task'] . " | " . $get_jumlah_task_resource_update[0]['updated_task'] . "</td>";
            $msg .= "</tr>";
        } while ($get_engineer[0] = $get_engineer[1]->fetch_assoc());
        $msg .= "<tr>";
        $msg .= "<td>&nbsp;</td>";
        $msg .= "</tr>";
        $msg .= "</tbody>";
        $msg .= "</table>";
    }
    $msg .= "</p>";
    $msg .= "<p>" . $msg4 . "</p>";
    $msg .= "<p>Terimakasih,<br/>";
    $msg .= $_SESSION['Microservices_UserName'] . "</p>";
    $msg .= "</td><td width='30%' rowspan='3'>";
    $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id/index.php?mod=kpi_board'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
    $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
    $msg .= "</table>";
    $msg .= "</div>";

    $headers = "From: " . $from . "\r\n" .
        "Reply-To: " . $reply . "\r\n" .
        "Cc: " . $cc . "\r\n" .
        "Bcc: " . $bcc . "\r\n" .
        "MIME-Version: 1.0" . "\r\n" .
        "Content-Type: text/html; charset=UTF-8" . "\r\n" .
        "X-Mailer: PHP/" . phpversion();

    $ALERT = new Alert();
    if (!mail(
        $to,
        $subject,
        $msg,
        $headers
    )) {
        echo
        "Email gagal terkirim pada jam " . date("d M Y G:i:s") . "$to" . "/ $subject";
    } else {
        echo
        "Email terkirim pada jam " . date("d M Y G:i:s");
    }

    $ALERT->savedata();
} elseif (isset($_POST['review'])) {
    $so_number = $_POST['so_number'];
    $order_number = $_POST['order_number'];
    $user_mail = $_SESSION['Microservices_UserEmail'];
    $check_data = $DBKPISO->get_sql("SELECT * FROM sa_kpi_board WHERE order_number='$order_number' AND pic_email LIKE '%$user_mail%'");
    if (isset($check_data[0]['pic_email'])) {
        date_default_timezone_set('Asia/Jakarta');
        $date = date("Y-M-d");
        $time = date("H:i:s");
        $sql_get = $DBKPISO->get_sql("SELECT * FROM sa_kpi_board WHERE order_number='$order_number'");
        $email_pic = explode(",", $sql_get[0]['pic_email']);
        $stts_pic = explode(", </br>", $sql_get[0]['status_approval']);
        for ($i = 0; $i < count($email_pic); $i++) {
            $email = $email_pic[$i];
            $sqlCheck = "SELECT employee_name,employee_email FROM sa_view_employees_v2 WHERE employee_email='" . $email_pic[$i] . "'";
            $get_nama = $DBHCM->get_sql($sqlCheck);
            $employee_name = $get_nama[0]['employee_name'];
            $employee_email = $get_nama[0]['employee_email'];
            $final_email = $employee_email;
            if ($final_email == $user_mail) {
                $angka = $i;
            } else {
                '';
            }
        }
        if (isset($angka)) {
            $nomor = $angka;
            $status_pic = '';
            for ($x = 0; $x < count($stts_pic); $x++) {
                $stts = $stts_pic[$x];
                $stts_apprv = str_replace($stts_pic[$nomor], 'Approved', $stts);
                $stts_pending = str_replace($stts_pic[$x], 'Pending', $stts);
                if ($x == $nomor) {
                    $status_pic .= $stts_apprv . ', </br>';
                } else {
                    $status_pic .= $stts . ', </br>';
                }
            }
            $status_pic = substr($status_pic, 0, -7);
            // echo $stts_pic[$nomor];
            if ($stts_pic[$nomor] == 'Pending') {
                $condition_upd = "order_number=" . "\"" . $_POST['order_number'] . "\"";
                $update_upd = sprintf(
                    "`status_approval`=%s",
                    GetSQLValueString($status_pic, "text")
                );
                $catatan_approval = "(" . $_POST['catatan_approval'] . ") <br> By :" . $_SESSION['Microservices_UserName'];

                $insert = sprintf(
                    "(`so_number`,`date`,`time`,`status`) VALUES (%s,%s,%s,%s) ON DUPLICATE KEY UPDATE `date`= %s,`time`= %s,`status`= %s",
                    GetSQLValueString($so_number, "text"),
                    GetSQLValueString($date, "text"),
                    GetSQLValueString($time, "text"),
                    GetSQLValueString("Status : Approved <br> Notes : " . $catatan_approval . "", "text"),
                    GetSQLValueString($date, "text"),
                    GetSQLValueString($time, "text"),
                    GetSQLValueString("Status : Approved <br> Notes : " . $catatan_approval . "", "text")
                );
                $res = $DBKPISO->insert_data($tblname7, $insert);


                $update_upd2 = sprintf(
                    "`status_approval`=%s,`status_error`=%s",
                    GetSQLValueString($status_pic, "text"),
                    GetSQLValueString("Approved", "text")
                );
                $res = $DBKPISO->update_data($tblname2, $update_upd, $condition_upd);
                $res = $DBKPISO->update_data($tblname2, $update_upd2, $condition_upd);

                $get_requester = $DBKPISO->get_sql("SELECT * FROM sa_kpi_board WHERE order_number='$order_number'");
                $get_project_code = $DBKPISO->get_sql("SELECT * FROM sa_kpi_so_wr WHERE order_number='$order_number'");
                $project_code = $get_project_code[0]['project_code'];
                $weighted_val = $get_project_code[0]['weighted_value'];
                $check_approval = $DBKPISO->get_sql("SELECT * FROM sa_kpi_board WHERE order_number='$order_number'");
                $get_error = $DBKPISO->get_sql("SELECT * from sa_kpi_board WHERE order_number='$order_number'");
                $get_data_b = $DBKPISO->get_sql("SELECT * FROM sa_kpi_board WHERE order_number='$order_number'");
                $id_kpi = $get_data_b[0]['id'];
                $get_data_so = $DBKPISO->get_sql("SELECT * FROM sa_data_so WHERE order_number='$order_number'");
                $project_name = $get_data_so[0]['project_name'];
                $customer_name = $get_data_so[0]['customer_name'];
                $implementation_price = $get_data_so[0]['SB_amount_idr'];
                $po_maintenance = $get_data_so[0]['SB_maintenance_price'];
                $po_warranty = $get_data_so[0]['SB_warranty_price'];
                $value = $implementation_price - $po_maintenance - $po_warranty;
                $id_project = $DBWR->get_sql("SELECT id from sa_wrike_project_list WHERE order_number='$order_number' AND project_type='MSI Project Implementation'");
                $idproject = isset($id_project[0]['id']);
                if (empty($idproject)) {
                    $id = null;
                } else {
                    $id = $id_project[0]['id'];
                }
                $get_engineer = $DBWR->get_sql("SELECT DISTINCT a.resource_email from sa_view_dashboard_wrike_resource a left join sa_wrike_project_list b ON a.project_id=b.id where b.order_number='$order_number'");
                $get_data_kpi = $DBKPISO->get_sql("SELECT * from sa_kpi_so_wr WHERE order_number='$order_number'");
                $get_sb_mandays = $DBKPISO->get_sql("SELECT * FROM sa_data_so WHERE order_number='$order_number' AND SB_service_type_implementation=1");
                $persen_cost = $get_data_kpi[0]['commercial_kpi'] * 100;
                $bast_planing = $get_sb_mandays[0]['WR_bast_date_project_implementation'];
                if (empty($bast_planing)) {
                    $bast_plan = 'Empty';
                } else {
                    $bast_plan = $bast_planing;
                }
                $bast_actualing = $get_sb_mandays[0]['WR_bast_date_actual_project_implementation'];
                if (empty($bast_actualing)) {
                    $bast_actual = 'Empty';
                } else {
                    $bast_actual = $bast_actualing;
                }
                $cek_error = $DBKPISO->get_sql("SELECT * from sa_kpi_board WHERE order_number='$order_number'");
                $nilai = isset($cek_error[0]['nilai_error']);
                if (empty($nilai)) {
                    $nilaierror = 0;
                    $persen_error = $nilaierror * 100;
                } else {
                    $nilaierror = $cek_error[0]['nilai_error'];
                    $persen_error = $nilaierror * 100;
                }
                if ($nilaierror >= 0.06 && $nilaierror < 0.12) {
                    $error_result = 'Minor';
                } else if ($nilaierror >= 0.12 && $nilaierror < 0.2) {
                    $error_result = 'Major';
                } else if ($nilaierror >= 0.2) {
                    $error_result = 'Critical';
                } else {
                    $error_result = 'Normal';
                }
                $persen_time =  $get_data_kpi[0]['time_kpi'] * 100;
                $total_kpi = 100 - $persen_cost - $persen_time - $persen_error;
                $user_email = $_SESSION['Microservices_UserEmail'];
                $from = $user_email;
                $status = "Approved KPI Panel Project";
                $cc = $from;
                $bcc = "";
                $to = "lucky.andiani@mastersystem.co.id";
                $msg1 = "<p>Dengan ini saya memberikan Approval untuk KPI Panel Project:</p>";
                $msg2 = "<p>Bisa dilanjutkan ke tahapan berikutnya.</p>";
                $reply = $from;
                $subject = "[KPI] " . ucwords($status) . " : " . $project_name . " � " .     $project_code . "";
                $msg5 = "<p>Dengan ini memberitahukan untuk KPI status project ini telah di nilai, dengan detail penilaian sebagai berikut:</p>";
                $msg6 = "<p>Atas kerjasamanya kami ucapkan terima kasih.</p>";
                $msg = "<table width='100%'";
                $msg .= "<tr><td rowspan='3'></td><td style='width:100%;'>";
                // $msg .= "<img src='https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png'>";
                $msg .= "<br/>";
                $msg .= "<p>Dear All, </p>";
                $msg .= "<p>" . $msg1 . "</p>";
                $msg .= "<p>";
                $msg .= "<div style='width:980px; margin:0 auto;'>";
                $msg .= "<table style='width:100%; border-collapse: collapse;'>";
                $msg .= "<tr><td>Project Name</td><td>: </td><td>" . $project_name . "</td></tr>";
                $msg .= "<tr><td>Customer Name</td><td>: </td><td>" . $customer_name . "</td></tr>";
                $msg .= "<tr><td>Project Code</td><td>: </td><td>" . $project_code . "</td></tr>";
                $msg .= "<tr><td>SO Number</td><td>: </td><td>" . $so_number . "</td></tr>";
                $msg .= "<tr><td>Nilai Project</td><td>: </td><td>" . $value . "</td></tr>";
                $msg .= "</table>";
                $msg .= "<table class='table' style='width:100%; border-collapse: collapse;'>";
                $msg .= "<thead class='thead-light'>";
                $msg .= "<tr>";
                $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Cost (%)</th>";
                $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Time (%)</th>";
                $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Error (%)</th>";
                $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Total KPI (%)</th>";
                $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Weighted Value</th>";
                $msg .= "</tr>";
                $msg .= "</thead>";
                $msg .= "<tbody>";
                $msg .= "<tr>";
                $msg .= "<td style='border: solid 1px #777; text-align:center;'>";
                $msg .= "<label for='inputCID3' class='col-sm-8 col-form-label col-form-label-sm'>" . $persen_cost . "% | " . $get_data_kpi[0]['commercial_category'] . "</label>";
                $msg .= "<div class='row mb-1'>";
                $msg .= "<label for='inputCID3' class='col-sm-8 col-form-label col-form-label-sm'>" . $get_sb_mandays[0]['SB_mandays_implementation'] . " | " . $get_sb_mandays[0]['CR_mandays_implementation'] . " | " . round($get_sb_mandays[0]['WR_mandays_actual_implementation']) . "</label>";
                $msg .= "</div>";
                $msg .= "</td>";
                $msg .= "<td style='border: solid 1px #777; text-align:center;'>";
                $msg .= "<label for='inputCID3' class='col-sm-8 col-form-label col-form-label-sm'>" . $persen_time . "% | " . $get_data_kpi[0]['time_category'] . "</label>";
                $msg .= "<div class='row mb-1'>";
                $msg .= "<label for='inputCID3' class='col-sm-8 col-form-label col-form-label-sm'>" . $bast_plan . " | " . $bast_actual . "</label>";
                $msg .= "</div>";
                $msg .= "</td>";
                $msg .= "<td style='border: solid 1px #777; text-align:center;'>" . $persen_error . "% | " . $error_result . "</label></td>";
                $msg .= "<td style='text-align:center; border: solid 1px #777;'>" . $total_kpi . "%" . "</label></td>";
                $msg .= "<td style='text-align:center; border: solid 1px #777;'>" . $weighted_val . "</label></td>";
                $msg .= "</tr>";
                $msg .= "<tr>";
                $msg .= "<td>&nbsp;</td>";
                $msg .= "</tr>";
                $msg .= "</tbody>";
                $msg .= "</table>";
                if (isset($get_engineer[0]['resource_email'])) {
                    $msg .= "<table class='table' style='width:100%; border-collapse: collapse;'>";
                    $msg .= "<thead class='thead-light'>";
                    $msg .= "<tr>";
                    $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Resource Name</th>";
                    $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Roles</th>";
                    $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Productivity</th>";
                    $msg .= "</tr>";
                    $msg .= "</thead>";
                    $msg .= "<tbody>";
                    do {
                        $id_project2 = $DBWR->get_sql("SELECT id from sa_wrike_project_list WHERE order_number='$order_number' AND project_type='MSI Project Implementation'");
                        $idproject2 = isset($id_project2[0]['id']);
                        if (empty($idproject2)) {
                            $id2 = null;
                        } else {
                            $id2 = $id_project2[0]['id'];
                        }
                        $get_name = $DBHCM->get_sql("SELECT employee_name from sa_view_employees WHERE employee_email='" . $get_engineer[0]['resource_email'] . "'");
                        $get_leader_resource = get_leader($get_engineer[0]['resource_email']);
                        $get_roles = $DBWR->get_sql("SELECT * FROM sa_resource_assignment WHERE resource_email LIKE '%" . $get_engineer[0]['resource_email'] . "%' AND order_number='$order_number'");
                        // $get_jumlah_task_resource = $DBWR->get_sql("SELECT COUNT(task_name) AS jumlah_task FROM sa_wrike_assignment WHERE project_id='$id2' AND resource_email='" . $get_engineer[0]['resource_email'] . "'");
                        $get_resource_id = $DBWR->get_sql("SELECT resource_id FROM sa_wrike_project_resources WHERE resource_email='" . $get_engineer[0]['resource_email'] . "'");
                        // $get_jumlah_task_resource_update = $DBWR->get_sql("SELECT COUNT(task_id) AS updated_task FROM sa_view_task_timelog WHERE project_id='$id2' AND resource_id='" . $get_resource_id[0]['resource_id'] . "' GROUP BY task_id");
                        $get_jumlah_task_resource = $DBGC->get_sqlV2("SELECT COUNT(task_name) AS jumlah_task FROM sa_preschedule WHERE project_id='$id2' AND resource_email='" . $get_engineer[0]['resource_email'] . "'");
                        $get_jumlah_task_resource_update = $DBGC->get_sqlV2("SELECT COUNT(a.project_id) as updated_task FROM sa_preschedule a left join sa_schedule b ON a.google_event_id=b.event_id WHERE a.project_id = '$id2' AND b.attendees_email='" . $get_engineer[0]['resource_email'] . "' AND b.response_status='accepted' AND b.project_type='MSI Project Implementation'");
                        $msg .= "<tr>";
                        $msg .= "<td style='border: solid 1px #777; text-align:center;'>" . $get_name[0]['employee_name'] . "</td>";
                        if (isset($get_roles[0]['project_roles'])) {
                            $msg .= "<td style='border: solid 1px #777; text-align:center;'>" . $get_roles[0]['project_roles'] . "</td>";
                        } else {
                            $msg .= "<td style='border: solid 1px #777; text-align:center;'>Kosong</td>";
                        }
                        $msg .= "<td style='border: solid 1px #777; text-align:center;'>" . $get_jumlah_task_resource[0]['jumlah_task'] . " | " . $get_jumlah_task_resource_update[0]['updated_task'] . "</td>";
                        $msg .= "</tr>";
                    } while ($get_engineer[0] = $get_engineer[1]->fetch_assoc());
                    $msg .= "<tr>";
                    $msg .= "<td>&nbsp;</td>";
                    $msg .= "</tr>";
                    $msg .= "</tbody>";
                    $msg .= "</table>";
                }
                $msg .= "</p>";
                $msg .= "<p>" . $msg2 . "</p>";
                $msg .= "<p>Terimakasih,<br/>";
                $msg .= $_SESSION['Microservices_UserName'] . "</p>";
                $msg .= "</td><td width='30%' rowspan='3'>";
                $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id/index.php?mod=kpi_board'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
                $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
                $msg .= "</table>";
                $msg .= "</div>";

                $headers = "From: " . $from . "\r\n" .
                    "Reply-To: " . $reply . "\r\n" .
                    "Cc: " . $cc . "\r\n" .
                    "Bcc: " . $bcc . "\r\n" .
                    "MIME-Version: 1.0" . "\r\n" .
                    "Content-Type: text/html; charset=UTF-8" . "\r\n" .
                    "X-Mailer: PHP/" . phpversion();

                $ALERT = new Alert();
                if (!mail(
                    $to,
                    $subject,
                    $msg,
                    $headers
                )) {
                    echo
                    "Email gagal terkirim pada jam " . date("d M Y G:i:s") . "$to" . "/ $subject";
                } else {
                    echo
                    "Email terkirim pada jam " . date("d M Y G:i:s");
                }
                if (strpos($status_pic, "Pending") !== false) {
                    '';
                } else {
                    $commercial_result = $get_data_kpi[0]['commercial_kpi'];
                    $timeline_result = $get_data_kpi[0]['time_kpi'];
                    $error_result = $get_error[0]['nilai_error'];
                    $cte_project = $commercial_result + $timeline_result + $error_result;
                    $cte = 1 - $cte_project;
                    $max_value = $get_data_kpi[0]['max_value'];
                    $weighted_value = $cte * $max_value;
                    $error_persen = $error_result * 100;
                    $total_kpi = $cte * 100;

                    if ($error_result >= 0.06 && $error_result < 0.12) {
                        $error_category = 'Minor';
                    } else if ($error_result >= 0.12 && $error_result < 0.20) {
                        $error_category = 'Major';
                    } else if ($error_result >= 0.20) {
                        $error_category = 'Critical';
                    } else {
                        $error_category = 'Normal';
                    }

                    $condition_kpi = "order_number=" . "\"" . $_POST['order_number'] . "\"";
                    $update_kpi = sprintf(
                        "`error_category`=%s,`error_kpi`=%s,`weighted_value`=%s,`cte`=%s,`total_cte`=%s",
                        GetSQLValueString($error_category, "text"),
                        GetSQLValueString(number_format($error_result, 5, ".", ""), "text"),
                        GetSQLValueString(number_format($weighted_value, 5, ".", ""), "text"),
                        GetSQLValueString(number_format($cte_project, 5, ".", ""), "text"),
                        GetSQLValueString(number_format($cte, 5, ".", ""), "text")
                    );
                    $res = $DBKPISO->update_data($tblname, $update_kpi, $condition_kpi);
                    echo "MASUK KPI";
                    $insert = sprintf(
                        "(`order_number`,`status`) VALUES (%s,%s) ON DUPLICATE KEY UPDATE `status`= %s",
                        GetSQLValueString($order_number, "text"),
                        GetSQLValueString("Reviewed", "text"),
                        GetSQLValueString("Reviewed", "text")
                    );
                    $res = $DBKPISO->insert_data($tblname2, $insert);

                    $condition = "order_number=" . "\"" . $_POST['order_number'] . "\"";
                    $update = sprintf(
                        "`kpi_status`=%s,`board_status`=%s",
                        GetSQLValueString("Reviewed", "text"),
                        GetSQLValueString("Reviewed", "text")
                    );
                    $res = $DBKPISO->update_data($tblname, $update, $condition);

                    $condition1 = "order_number=" . "\"" . $_POST['order_number'] . "\"";
                    $update1 = sprintf(
                        "`kpi_status`=%s,`board_status`=%s",
                        GetSQLValueString("Reviewed", "text"),
                        GetSQLValueString("Reviewed", "text")
                    );
                    $res = $DBKPISO->update_data($tblname4, $update1, $condition1);

                    $condition2 = "order_number=" . "\"" . $_POST['order_number'] . "\"";
                    $update2 = sprintf(
                        "`kpi_status`=%s,`board_status`=%s",
                        GetSQLValueString("Reviewed", "text"),
                        GetSQLValueString("Reviewed", "text")
                    );
                    $res = $DBKPISO->update_data($tblname3, $update2, $condition2);

                    $so_number2 = $_POST['so_number'];
                    $get_engineer3 = $DBWR->get_sql("SELECT DISTINCT a.resource_email from sa_view_dashboard_wrike_resource a left join sa_wrike_project_list b ON a.project_id=b.id where b.order_number='$order_number'");
                    $resources = $check_approval[0]['resources'];
                    $email_requester = $check_approval[0]['email_requester'];
                    $pic = $check_approval[0]['pic_email'];
                    $from = "msizone@mastersystem.co.id";
                    $cc = $pic . "," . $email_requester . ",lucky.andiani@mastersystem.co.id";
                    $bcc = "";
                    $to =  $resources;
                    $subject = "[KPI] Approved KPI : " . $project_name . " � " .     $project_code . "";
                    $msg5 = "<p>Dengan ini memberitahukan untuk KPI status project ini telah di nilai, dengan detail penilaian sebagai berikut:</p>";
                    $msg6 = "<p>Atas kerjasamanya kami ucapkan terima kasih.</p>";
                    $msg = "<table width='100%'";
                    $msg .= "<tr><td rowspan='3'></td><td style='width:100%;'>";
                    // $msg .= "<img src='https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png'>";
                    $msg .= "<br/>";
                    $msg .= "<p>Dear All, </p>";
                    $msg .= "<p>" . $msg5 . "</p>";
                    $msg .= "<p>";
                    $msg .= "<div style='width:980px; margin:0 auto;'>";
                    $msg .= "<table style='width:100%; border-collapse: collapse;'>";
                    $msg .= "<tr><td>Project Name</td><td>: </td><td>" . $project_name . "</td></tr>";
                    $msg .= "<tr><td>Customer Name</td><td>: </td><td>" . $customer_name . "</td></tr>";
                    $msg .= "<tr><td>Project Code</td><td>: </td><td>" . $project_code . "</td></tr>";
                    $msg .= "<tr><td>SO Number</td><td>: </td><td>" . $so_number2 . "</td></tr>";
                    $msg .= "<tr><td>Nilai Project</td><td>: </td><td>" . $value . "</td></tr>";
                    $msg .= "</table>";
                    $msg .= "<table class='table' style='width:100%; border-collapse: collapse;'>";
                    $msg .= "<thead class='thead-light'>";
                    $msg .= "<tr>";
                    $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Cost (%)</th>";
                    $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Time (%)</th>";
                    $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Error (%)</th>";
                    $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Total KPI (%)</th>";
                    $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Weighted Value</th>";
                    $msg .= "</tr>";
                    $msg .= "</thead>";
                    $msg .= "<tbody>";
                    $msg .= "<tr>";
                    $msg .= "<td style='border: solid 1px #777; text-align:center;'>";
                    $msg .= "<label for='inputCID3' class='col-sm-8 col-form-label col-form-label-sm'>" . $persen_cost . "% | " . $get_data_kpi[0]['commercial_category'] . "</label>";
                    $msg .= "<div class='row mb-1'>";
                    $msg .= "<label for='inputCID3' class='col-sm-8 col-form-label col-form-label-sm'>" . $get_sb_mandays[0]['SB_mandays_implementation'] . " | " . $get_sb_mandays[0]['CR_mandays_implementation'] . " | " . round($get_sb_mandays[0]['WR_mandays_actual_implementation']) . "</label>";
                    $msg .= "</div>";
                    $msg .= "</td>";
                    $msg .= "<td style='border: solid 1px #777; text-align:center;'>";
                    $msg .= "<label for='inputCID3' class='col-sm-8 col-form-label col-form-label-sm'>" . $persen_time . "% | " . $get_data_kpi[0]['time_category'] . "</label>";
                    $msg .= "<div class='row mb-1'>";
                    $msg .= "<label for='inputCID3' class='col-sm-8 col-form-label col-form-label-sm'>" . $bast_plan . " | " . $bast_actual . "</label>";
                    $msg .= "</div>";
                    $msg .= "</td>";
                    $msg .= "<td style='border: solid 1px #777; text-align:center;'>" . $error_persen . "% | " . $error_category . "</label></td>";
                    $msg .= "<td style='text-align:center; border: solid 1px #777;'>" . $total_kpi . "%" . "</label></td>";
                    $msg .= "<td style='text-align:center; border: solid 1px #777;'>" . $weighted_val . "</label></td>";
                    $msg .= "</tr>";
                    $msg .= "<tr>";
                    $msg .= "<td>&nbsp;</td>";
                    $msg .= "</tr>";
                    $msg .= "</tbody>";
                    $msg .= "</table>";
                    if (isset($get_engineer3[0]['resource_email'])) {
                        $msg .= "<table class='table' style='width:100%; border-collapse: collapse;'>";
                        $msg .= "<thead class='thead-light'>";
                        $msg .= "<tr>";
                        $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Resource Name</th>";
                        $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Roles</th>";
                        $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Productivity</th>";
                        $msg .= "</tr>";
                        $msg .= "</thead>";
                        $msg .= "<tbody>";
                        do {
                            $id_project2 = $DBWR->get_sql("SELECT id from sa_wrike_project_list WHERE order_number='$order_number' AND project_type='MSI Project Implementation'");
                            $idproject2 = isset($id_project2[0]['id']);
                            if (empty($idproject2)) {
                                $id2 = null;
                            } else {
                                $id2 = $id_project2[0]['id'];
                            }
                            $get_name = $DBHCM->get_sql("SELECT employee_name from sa_view_employees WHERE employee_email='" . $get_engineer3[0]['resource_email'] . "'");
                            $get_leader_resource = get_leader($get_engineer3[0]['resource_email']);
                            $get_roles = $DBWR->get_sql("SELECT * FROM sa_resource_assignment WHERE resource_email LIKE '%" . $get_engineer3[0]['resource_email'] . "%' AND order_number='$order_number'");
                            $get_jumlah_task_resource = $DBWR->get_sql("SELECT COUNT(task_name) AS jumlah_task FROM sa_wrike_assignment WHERE project_id='$id2' AND resource_email='" . $get_engineer3[0]['resource_email'] . "'");
                            $get_resource_id = $DBWR->get_sql("SELECT resource_id FROM sa_wrike_project_resources WHERE resource_email='" . $get_engineer3[0]['resource_email'] . "'");
                            $get_jumlah_task_resource_update = $DBWR->get_sql("SELECT COUNT(task_id) AS updated_task FROM sa_view_task_timelog WHERE project_id='$id2' AND resource_id='" . $get_resource_id[0]['resource_id'] . "' GROUP BY task_id");
                            $msg .= "<tr>";
                            $msg .= "<td style='border: solid 1px #777; text-align:center;'>" . $get_name[0]['employee_name'] . "</td>";
                            if (isset($get_roles[0]['project_roles'])) {
                                $msg .= "<td style='border: solid 1px #777; text-align:center;'>" . $get_roles[0]['project_roles'] . "</td>";
                            } else {
                                $msg .= "<td style='border: solid 1px #777; text-align:center;'>Kosong</td>";
                            }
                            $msg .= "<td style='border: solid 1px #777; text-align:center;'>" . $get_jumlah_task_resource[0]['jumlah_task'] . " | " . $get_jumlah_task_resource_update[0]['updated_task'] . "</td>";
                            $msg .= "</tr>";
                        } while ($get_engineer3[0] = $get_engineer3[1]->fetch_assoc());
                        $msg .= "<tr>";
                        $msg .= "<td>&nbsp;</td>";
                        $msg .= "</tr>";
                        $msg .= "</tbody>";
                        $msg .= "</table>";
                    }
                    $msg .= "</p>";
                    $msg .= "<p>" . $msg6 . "</p>";
                    $msg .= "<p>Terimakasih,<br/>";
                    $msg .= "<p>Admin System</p>";
                    $msg .= "</td><td width='30%' rowspan='3'>";
                    $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id/index.php?mod=kpi_board'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
                    $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
                    $msg .= "</table>";
                    $msg .= "</div>";

                    $headers = "From: " . $from . "\r\n" .
                        "Reply-To: " . $from . "\r\n" .
                        "Cc: " . $cc . "\r\n" .
                        "Bcc: " . $bcc . "\r\n" .
                        "MIME-Version: 1.0" . "\r\n" .
                        "Content-Type: text/html; charset=UTF-8" . "\r\n" .
                        "X-Mailer: PHP/" . phpversion();

                    $ALERT = new Alert();
                    if (!mail(
                        $to,
                        $subject,
                        $msg,
                        $headers
                    )) {
                        echo
                        "Email gagal terkirim pada jam " . date("d M Y G:i:s") . "$to" . "/ $subject";
                    } else {
                        echo
                        "Email terkirim pada jam " . date("d M Y G:i:s");
                    }

                    $check_exception = $DBKPISO->get_sqlV2("SELECT * FROM sa_kpi_exception WHERE order_number='$order_number'");
                    while ($row = $check_exception[1]->fetch_assoc()) {
                        $email_resource_exception = $row['resource_name'];
                        $cte = $row['kpi_adjustment'];
                        $weighted_valuee = $row['weighted_value'];
                        $get_approval = $DBKPISO->get_sqlV2("SELECT * FROM sa_kpi_board WHERE order_number='$order_number'");
                        $pic_approval_email = $get_approval[0]['pic_email'];
                        $fromm = "msizone@mastersystem.co.id";
                        $ccc = $pic_email . ",lucky.andiani@mastersystem.co.id";
                        $bccc = "";
                        $too =  $email_resource_exception;
                        $subject = "[KPI] Perubahan CTE & Weighted Value KPI Resource : " . $project_name . " � " .     $project_code . "";
                        $msgg5 = "<p>Dengan ini memberitahukan bahwa telah dilakukan adjustment kepada KPI Resource, dengan detail penilaian sebagai berikut:</p>";
                        $msgg6 = "<p>Atas kerjasamanya kami ucapkan terima kasih.</p>";
                        $msgg = "<table width='100%'";
                        $msgg .= "<tr><td rowspan='3'></td><td style='width:100%;'>";
                        $msgg .= "<br/>";
                        $msgg .= "<p>Dear All, </p>";
                        $msgg .= "<p>" . $msgg5 . "</p>";
                        $msgg .= "<p>";
                        $msgg .= "<div style='width:980px; margin:0 auto;'>";
                        $msgg .= "<table style='width:100%; border-collapse: collapse;'>";
                        $msgg .= "<tr><td>Project Name</td><td>: </td><td>" . $project_name . "</td></tr>";
                        $msgg .= "<tr><td>Customer Name</td><td>: </td><td>" . $customer_name . "</td></tr>";
                        $msgg .= "<tr><td>Project Code</td><td>: </td><td>" . $project_code . "</td></tr>";
                        $msgg .= "<tr><td>SO Number</td><td>: </td><td>" . $so_number2 . "</td></tr>";
                        $msgg .= "<tr><td>CTE Menjadi</td><td>: </td><td>" . $cte . "</td></tr>";
                        $msgg .= "<tr><td>Weighted Value Menjadi</td><td>: </td><td>" . $weighted_valuee . "</td></tr>";
                        $msgg .= "</table>";
                        $msgg .= "</p>";
                        $msgg .= "<p>" . $msgg6 . "</p>";
                        $msgg .= "<p>Terimakasih,<br/>";
                        $msgg .= "<p>Admin System</p>";
                        $msgg .= "</td><td width='30%' rowspan='3'>";
                        $msgg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id/index.php?mod=kpi_board'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
                        $msgg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
                        $msgg .= "</table>";
                        $msgg .= "</div>";

                        $headerss = "From: " . $fromm . "\r\n" .
                            "Reply-To: " . $fromm . "\r\n" .
                            "Cc: " . $ccc . "\r\n" .
                            "Bcc: " . $bccc . "\r\n" .
                            "MIME-Version: 1.0" . "\r\n" .
                            "Content-Type: text/html; charset=UTF-8" . "\r\n" .
                            "X-Mailer: PHP/" . phpversion();

                        $ALERT = new Alert();
                        if (!mail(
                            $too,
                            $subjectt,
                            $msgg,
                            $headerss
                        )) {
                            echo
                            "Email gagal terkirim pada jam " . date("d M Y G:i:s") . "$too" . "/ $subjectt";
                        } else {
                            echo
                            "Email terkirim pada jam " . date("d M Y G:i:s");
                        }
                    }

                    $ALERT->savedata();
                }
            } else {
                echo "<script>alert('Anda telah melakukan Approve untuk project ini')</script>";
            }
        } else {
            '';
        }
    } else {
        echo "<script>alert('Anda tidak dapat Approve Project ini')</script>";
    }
}

if (isset($_POST['reject'])) {
    $so_number = $_POST['so_number'];
    $order_number = $_POST['order_number'];
    $user_mail = $_SESSION['Microservices_UserEmail'];
    $check_data = $DBKPISO->get_sql("SELECT * FROM sa_kpi_board WHERE order_number='$order_number' AND pic_email LIKE '%$user_mail%'");
    if (isset($check_data[0]['pic_email'])) {
        date_default_timezone_set('Asia/Jakarta');
        $date = date("Y-M-d");
        $time = date("H:i:s");
        $sql_get = $DBKPISO->get_sql("SELECT * FROM sa_kpi_board WHERE order_number='$order_number'");
        $email_pic = explode(",", $sql_get[0]['pic_email']);
        $stts_pic = explode(", </br>", $sql_get[0]['status_approval']);
        for ($i = 0; $i < count($email_pic); $i++) {
            $email = $email_pic[$i];
            $sqlCheck = "SELECT employee_name,employee_email FROM sa_view_employees_v2 WHERE employee_email='" . $email_pic[$i] . "'";
            $get_nama = $DBHCM->get_sql($sqlCheck);
            $employee_name = $get_nama[0]['employee_name'];
            $employee_email = $get_nama[0]['employee_email'];
            $final_email = $employee_email;
            if ($final_email == $user_mail) {
                $angka = $i;
            } else {
                '';
            }
            // if ($final_email == $user_mail) {
            //     $status = str_replace($user_mail, 'Approved', $final_email);
            // } else {
            //     $status = str_replace($final_email, 'Pending', $final_email);
            // }
            // $status_pic .= $status . ',';
        }
        if (isset($angka)) {
            $nomor = $angka;
            for ($x = 0; $x < count($stts_pic); $x++) {
                $stts = $stts_pic[$x];
                $stts_reject = str_replace($stts_pic[$nomor], 'Reject', $stts);
                $stts_pending = str_replace($stts_pic[$x], 'Pending', $stts);
                // $email = $email_pic[$i];
                //     $sqlCheck = "SELECT employee_name,employee_email FROM sa_view_employees_v2 WHERE employee_email='". $email_pic[$i] ."'";
                //     $get_nama = $DBHCM->get_sql($sqlCheck);
                //     $employee_name = $get_nama[0]['employee_name'];
                //     $employee_email = $get_nama[0]['employee_email'];
                //     $final_email = $employee_email;
                //     // $a .= $final_email . ",";
                // $status = str_replace($final_email, 'Pending', $final_email);
                if ($x == $nomor) {
                    $status_pic .= $stts_reject . ', </br>';
                } else {
                    $status_pic .= $stts . ', </br>';
                }
            }
            // $pic_board = $_POST['pic'];
            $status_pic = substr($status_pic, 0, -7);

            $condition_upd = "order_number=" . "\"" . $_POST['order_number'] . "\"";
            $update_upd = sprintf(
                "`status_approval`=%s,`status`=%s",
                GetSQLValueString($status_pic, "text"),
                GetSQLValueString("Not Yet Reviewed", "text")
            );

            $catatan_reject = "(" . $_POST['catatan_reject'] . ") <br> By :" . $_SESSION['Microservices_UserName'];

            $insert = sprintf(
                "(`so_number`,`date`,`time`,`status`) VALUES (%s,%s,%s,%s) ON DUPLICATE KEY UPDATE `date`= %s,`time`= %s,`status`= %s",
                GetSQLValueString($so_number, "text"),
                GetSQLValueString($date, "text"),
                GetSQLValueString($time, "text"),
                GetSQLValueString("Status : Reject <br> Notes : " . $catatan_reject . "", "text"),
                GetSQLValueString($date, "text"),
                GetSQLValueString($time, "text"),
                GetSQLValueString("Status : Reject <br> Notes : " . $catatan_reject . "", "text")
            );
            $res = $DBKPISO->insert_data($tblname7, $insert);


            $update_upd2 = sprintf(
                "`status_approval`=%s,`status_error`=%s",
                GetSQLValueString($status_pic, "text"),
                GetSQLValueString("Reject", "text")
            );
            $res = $DBKPISO->update_data($tblname2, $update_upd, $condition_upd);
            $res = $DBKPISO->update_data($tblname2, $update_upd2, $condition_upd);

            $get_requester = $DBKPISO->get_sql("SELECT * FROM sa_kpi_board WHERE order_number='$order_number'");
            $get_project_code = $DBKPISO->get_sql("SELECT * FROM sa_kpi_so_wr WHERE order_number='$order_number'");
            $project_code = $get_project_code[0]['project_code'];
            $weighted_val = $get_project_code[0]['weighted_value'];
            $verif_status = $get_requester[0]['verif_status'];
            $req_email = $get_requester[0]['email_requester'];
            $get_data_b = $DBKPISO->get_sql("SELECT * FROM sa_kpi_board WHERE order_number='$order_number'");
            $id_kpi = $get_data_b[0]['id'];
            $get_data_so = $DBKPISO->get_sql("SELECT * FROM sa_data_so WHERE order_number='$order_number'");
            $project_name = $get_data_so[0]['project_name'];
            $customer_name = $get_data_so[0]['customer_name'];
            $project_code = $get_data_so[0]['project_code_kpi'];
            $implementation_price = $get_data_so[0]['SB_amount_idr'];
            $po_maintenance = $get_data_so[0]['SB_maintenance_price'];
            $po_warranty = $get_data_so[0]['SB_warranty_price'];
            $value = $implementation_price - $po_maintenance - $po_warranty;
            $id_project = $DBWR->get_sql("SELECT id from sa_wrike_project_list WHERE order_number='$order_number' AND project_type='MSI Project Implementation'");
            $idproject = isset($id_project[0]['id']);
            if (empty($idproject)) {
                $id = null;
            } else {
                $id = $id_project[0]['id'];
            }
            $get_engineer = $DBWR->get_sql("SELECT DISTINCT a.resource_email from sa_view_dashboard_wrike_resource a left join sa_wrike_project_list b ON a.project_id=b.id where b.order_number='$order_number'");
            $get_data_kpi = $DBKPISO->get_sql("SELECT * from sa_kpi_so_wr WHERE order_number='$order_number'");
            $get_sb_mandays = $DBKPISO->get_sql("SELECT * FROM sa_data_so WHERE order_number='$order_number' AND SB_service_type_implementation=1");
            $persen_cost = $get_data_kpi[0]['commercial_kpi'] * 100;
            $bast_planing = $get_sb_mandays[0]['WR_bast_date_project_implementation'];
            if (empty($bast_planing)) {
                $bast_plan = 'Empty';
            } else {
                $bast_plan = $bast_planing;
            }
            $bast_actualing = $get_sb_mandays[0]['WR_bast_date_actual_project_implementation'];
            if (empty($bast_actualing)) {
                $bast_actual = 'Empty';
            } else {
                $bast_actual = $bast_actualing;
            }
            $cek_error = $DBKPISO->get_sql("SELECT * from sa_kpi_board WHERE order_number='$order_number'");
            $nilai = isset($cek_error[0]['nilai_error']);
            if (empty($nilai)) {
                $nilaierror = 0;
                $persen_error = $nilaierror * 100;
            } else {
                $nilaierror = $cek_error[0]['nilai_error'];
                $persen_error = $nilaierror * 100;
            }
            if ($nilaierror >= 0.06 && $nilaierror < 0.12) {
                $error_result = 'Minor';
            } else if ($nilaierror >= 0.12 && $nilaierror < 0.2) {
                $error_result = 'Major';
            } else if ($nilaierror >= 0.2) {
                $error_result = 'Critical';
            } else {
                $error_result = 'Normal';
            }
            $persen_time =  $get_data_kpi[0]['time_kpi'] * 100;
            $total_kpi = 100 - $persen_cost - $persen_time - $persen_error;
            $user_email = $_SESSION['Microservices_UserEmail'];
            $from = $user_email;
            $cc = $from;
            $bcc = "";
            $to = "lucky.andiani@mastersystem.co.id";
            $msg1 = "<p>Dengan ini saya Reject KPI Panel Project :</p>";
            $msg2 = "<p>Dengan catatan : " . $_POST['catatan_reject'] . "</p>";
            $reply = $from;
            $subject = "[KPI] Reject KPI Panel Project :  " . $project_name . " � " .     $project_code . "";
            $msg = "<table width='100%'";
            $msg .= "<tr><td rowspan='3'></td><td style='width:100%;'>";
            $msg .= "<br/>";
            $msg .= "<p>Dear All, </p>";
            $msg .= "<p>" . $msg1 . "</p>";
            $msg .= "<p>";
            $msg .= "<div style='width:980px; margin:0 auto;'>";
            $msg .= "<table style='width:100%; border-collapse: collapse;'>";
            $msg .= "<tr><td>Project Name</td><td>: </td><td>" . $project_name . "</td></tr>";
            $msg .= "<tr><td>Customer Name</td><td>: </td><td>" . $customer_name . "</td></tr>";
            $msg .= "<tr><td>Project Code</td><td>: </td><td>" . $project_code . "</td></tr>";
            $msg .= "<tr><td>SO Number</td><td>: </td><td>" . $so_number . "</td></tr>";
            $msg .= "<tr><td>Nilai Project</td><td>: </td><td>" . $value . "</td></tr>";
            $msg .= "</table>";
            $msg .= "<table class='table' style='width:100%; border-collapse: collapse;'>";
            $msg .= "<thead class='thead-light'>";
            $msg .= "<tr>";
            $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Cost (%)</th>";
            $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Time (%)</th>";
            $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Error (%)</th>";
            $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Total KPI (%)</th>";
            $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Weighted Value</th>";
            $msg .= "</tr>";
            $msg .= "</thead>";
            $msg .= "<tbody>";
            $msg .= "<tr>";
            $msg .= "<td style='border: solid 1px #777; text-align:center;'>";
            $msg .= "<label for='inputCID3' class='col-sm-8 col-form-label col-form-label-sm'>" . $persen_cost . "% | " . $get_data_kpi[0]['commercial_category'] . "</label>";
            $msg .= "<div class='row mb-1'>";
            $msg .= "<label for='inputCID3' class='col-sm-8 col-form-label col-form-label-sm'>" . $get_sb_mandays[0]['SB_mandays_implementation'] . " | " . $get_sb_mandays[0]['CR_mandays_implementation'] . " | " . round($get_sb_mandays[0]['WR_mandays_actual_implementation']) . "</label>";
            $msg .= "</div>";
            $msg .= "</td>";
            $msg .= "<td style='border: solid 1px #777; text-align:center;'>";
            $msg .= "<label for='inputCID3' class='col-sm-8 col-form-label col-form-label-sm'>" . $persen_time . "% | " . $get_data_kpi[0]['time_category'] . "</label>";
            $msg .= "<div class='row mb-1'>";
            $msg .= "<label for='inputCID3' class='col-sm-8 col-form-label col-form-label-sm'>" . $bast_plan . " | " . $bast_actual . "</label>";
            $msg .= "</div>";
            $msg .= "</td>";
            $msg .= "<td style='border: solid 1px #777; text-align:center;'>" . $persen_error . "% | " . $error_result . "</label></td>";
            $msg .= "<td style='text-align:center; border: solid 1px #777;'>" . $total_kpi . "%" . "</label></td>";
            $msg .= "<td style='text-align:center; border: solid 1px #777;'>" . $weighted_val . "</label></td>";
            $msg .= "</tr>";
            $msg .= "<tr>";
            $msg .= "<td>&nbsp;</td>";
            $msg .= "</tr>";
            $msg .= "</tbody>";
            $msg .= "</table>";
            if (isset($get_engineer[0]['resource_email'])) {
                $msg .= "<table class='table' style='width:100%; border-collapse: collapse;'>";
                $msg .= "<thead class='thead-light'>";
                $msg .= "<tr>";
                $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Resource Name</th>";
                $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Roles</th>";
                $msg .= "<th scope='col' style='border: solid 1px #777; padding: 5px;'>Productivity</th>";
                $msg .= "</tr>";
                $msg .= "</thead>";
                $msg .= "<tbody>";
                do {
                    $id_project2 = $DBWR->get_sql("SELECT id from sa_wrike_project_list WHERE order_number='$order_number' AND project_type='MSI Project Implementation'");
                    $idproject2 = isset($id_project2[0]['id']);
                    if (empty($idproject2)) {
                        $id2 = null;
                    } else {
                        $id2 = $id_project2[0]['id'];
                    }
                    $get_name = $DBHCM->get_sql("SELECT employee_name from sa_view_employees WHERE employee_email='" . $get_engineer[0]['resource_email'] . "'");
                    $get_leader_resource = get_leader($get_engineer[0]['resource_email']);
                    $get_roles = $DBWR->get_sql("SELECT * FROM sa_resource_assignment WHERE resource_email LIKE '%" . $get_engineer[0]['resource_email'] . "%' AND order_number='$order_number'");
                    // $get_jumlah_task_resource = $DBWR->get_sql("SELECT COUNT(task_name) AS jumlah_task FROM sa_wrike_assignment WHERE project_id='$id2' AND resource_email='" . $get_engineer[0]['resource_email'] . "'");
                    $get_resource_id = $DBWR->get_sql("SELECT resource_id FROM sa_wrike_project_resources WHERE resource_email='" . $get_engineer[0]['resource_email'] . "'");
                    // $get_jumlah_task_resource_update = $DBWR->get_sql("SELECT COUNT(task_id) AS updated_task FROM sa_view_task_timelog WHERE project_id='$id2' AND resource_id='" . $get_resource_id[0]['resource_id'] . "' GROUP BY task_id");
                    $get_jumlah_task_resource = $DBGC->get_sqlV2("SELECT COUNT(task_name) AS jumlah_task FROM sa_preschedule WHERE project_id='$id2' AND resource_email='" . $get_engineer[0]['resource_email'] . "'");
                    $get_jumlah_task_resource_update = $DBGC->get_sqlV2("SELECT COUNT(a.project_id) as updated_task FROM sa_preschedule a left join sa_schedule b ON a.google_event_id=b.event_id WHERE a.project_id = '$id2' AND b.attendees_email='" . $get_engineer[0]['resource_email'] . "' AND b.response_status='accepted' AND b.project_type='MSI Project Implementation'");
                    $msg .= "<tr>";
                    $msg .= "<td style='border: solid 1px #777; text-align:center;'>" . $get_name[0]['employee_name'] . "</td>";
                    if (isset($get_roles[0]['project_roles'])) {
                        $msg .= "<td style='border: solid 1px #777; text-align:center;'>" . $get_roles[0]['project_roles'] . "</td>";
                    } else {
                        $msg .= "<td style='border: solid 1px #777; text-align:center;'>Kosong</td>";
                    }
                    $msg .= "<td style='border: solid 1px #777; text-align:center;'>" . $get_jumlah_task_resource[0]['jumlah_task'] . " | " . $get_jumlah_task_resource_update[0]['updated_task'] . "</td>";
                    $msg .= "</tr>";
                } while ($get_engineer[0] = $get_engineer[1]->fetch_assoc());
                $msg .= "<tr>";
                $msg .= "<td>&nbsp;</td>";
                $msg .= "</tr>";
                $msg .= "</tbody>";
                $msg .= "</table>";
            }
            $msg .= "</p>";
            $msg .= "<p>" . $msg2 . "</p>";
            $msg .= "<p>Terimakasih,<br/>";
            $msg .= $_SESSION['Microservices_UserName'] . "</p>";
            $msg .= "</td><td width='30%' rowspan='3'>";
            $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id/index.php?mod=kpi_board'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
            $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
            $msg .= "</table>";
            $msg .= "</div>";

            $headers = "From: " . $from . "\r\n" .
                "Reply-To: " . $reply . "\r\n" .
                "Cc: " . $cc . "\r\n" .
                "Bcc: " . $bcc . "\r\n" .
                "MIME-Version: 1.0" . "\r\n" .
                "Content-Type: text/html; charset=UTF-8" . "\r\n" .
                "X-Mailer: PHP/" . phpversion();

            $ALERT = new Alert();
            if (!mail(
                $to,
                $subject,
                $msg,
                $headers
            )) {
                echo
                "Email gagal terkirim pada jam " . date("d M Y G:i:s") . "$to" . "/ $subject";
            } else {
                echo
                "Email terkirim pada jam " . date("d M Y G:i:s");
            }

            $ALERT->savedata();
        } else {
            '';
        }
    } else {
        echo "<script>alert('Anda tidak bisa reject Project ini')</script>";
    }
}
if (isset($_POST['Cancel_CR1'])) {
    $cr_no = '';
    $status_pic = '';
    foreach ($_POST["cr_no"] as $row) {
        $cr_no .= $row . ',';
    }
    $cr_no = substr($cr_no, 0, -1);
    $cr = explode(",", $cr_no);
    for ($i = 0; $i < count($cr); $i++) {
        $email = $cr[0];
    }
    $cr_number = $email;
    $condition = "cr_no='$cr_number'";
    $update_cr = sprintf(
        "`change_request_status`=%s",
        GetSQLValueString("Canceled", "text")
    );
    $res = $DBCR->update_data("general_informations", $update_cr, $condition);
    // $update_status_cr = $DBCR->get_sql("UPDATE sa_general_informations SET change_request_status='Cancelled' WHERE cr_no='$cr_number'");
    $ALERT->savedata();
}
if (isset($_POST['Cancel_CR2'])) {
    $cr_no = '';
    $status_pic = '';
    foreach ($_POST["cr_no"] as $row) {
        $cr_no .= $row . ',';
    }
    $cr_no = substr($cr_no, 0, -1);
    $cr = explode(",", $cr_no);
    for ($i = 0; $i < count($cr); $i++) {
        $email = $cr[1];
    }
    $cr_number = $email;
    $condition = "cr_no='$cr_number'";
    $update_cr = sprintf(
        "`change_request_status`=%s",
        GetSQLValueString("Canceled", "text")
    );
    $res = $DBCR->update_data("general_informations", $update_cr, $condition);
    $ALERT->savedata();
}
if (isset($_POST['Cancel_CR3'])) {
    $cr_no = '';
    $status_pic = '';
    foreach ($_POST["cr_no"] as $row) {
        $cr_no .= $row . ',';
    }
    $cr_no = substr($cr_no, 0, -1);
    $cr = explode(",", $cr_no);
    for ($i = 0; $i < count($cr); $i++) {
        $email = $cr[2];
    }
    $cr_number = $email;
    $condition = "cr_no='$cr_number'";
    $update_cr = sprintf(
        "`change_request_status`=%s",
        GetSQLValueString("Canceled", "text")
    );
    $res = $DBCR->update_data("general_informations", $update_cr, $condition);
    $ALERT->savedata();
}
if (isset($_POST['Cancel_CR4'])) {
    $cr_no = '';
    $status_pic = '';
    foreach ($_POST["cr_no"] as $row) {
        $cr_no .= $row . ',';
    }
    $cr_no = substr($cr_no, 0, -1);
    $cr = explode(",", $cr_no);
    for ($i = 0; $i < count($cr); $i++) {
        $email = $cr[3];
    }
    $cr_number = $email;
    $condition = "cr_no='$cr_number'";
    $update_cr = sprintf(
        "`change_request_status`=%s",
        GetSQLValueString("Canceled", "text")
    );
    $res = $DBCR->update_data("general_informations", $update_cr, $condition);
    $ALERT->savedata();
}
if (isset($_POST['Cancel_CR5'])) {
    $cr_no = '';
    $status_pic = '';
    foreach ($_POST["cr_no"] as $row) {
        $cr_no .= $row . ',';
    }
    $cr_no = substr($cr_no, 0, -1);
    $cr = explode(",", $cr_no);
    for ($i = 0; $i < count($cr); $i++) {
        $email = $cr[4];
    }
    $cr_number = $email;
    $condition = "cr_no='$cr_number'";
    $update_cr = sprintf(
        "`change_request_status`=%s",
        GetSQLValueString("Canceled", "text")
    );
    $res = $DBCR->update_data("general_informations", $update_cr, $condition);
    $ALERT->savedata();
}
if (isset($_POST['Cancel_CR6'])) {
    $cr_no = '';
    $status_pic = '';
    foreach ($_POST["cr_no"] as $row) {
        $cr_no .= $row . ',';
    }
    $cr_no = substr($cr_no, 0, -1);
    $cr = explode(",", $cr_no);
    for ($i = 0; $i < count($cr); $i++) {
        $email = $cr[5];
    }
    $cr_number = $email;
    $condition = "cr_no='$cr_number'";
    $update_cr = sprintf(
        "`change_request_status`=%s",
        GetSQLValueString("Canceled", "text")
    );
    $res = $DBCR->update_data("general_informations", $update_cr, $condition);
    $ALERT->savedata();
}
if (isset($_POST['Cancel_CR7'])) {
    $cr_no = '';
    $status_pic = '';
    foreach ($_POST["cr_no"] as $row) {
        $cr_no .= $row . ',';
    }
    $cr_no = substr($cr_no, 0, -1);
    $cr = explode(",", $cr_no);
    for ($i = 0; $i < count($cr); $i++) {
        $email = $cr[6];
    }
    $cr_number = $email;
    $condition = "cr_no='$cr_number'";
    $update_cr = sprintf(
        "`change_request_status`=%s",
        GetSQLValueString("Canceled", "text")
    );
    $res = $DBCR->update_data("general_informations", $update_cr, $condition);
    $ALERT->savedata();
}
if (isset($_POST['Cancel_CR8'])) {
    $cr_no = '';
    $status_pic = '';
    foreach ($_POST["cr_no"] as $row) {
        $cr_no .= $row . ',';
    }
    $cr_no = substr($cr_no, 0, -1);
    $cr = explode(",", $cr_no);
    for ($i = 0; $i < count($cr); $i++) {
        $email = $cr[7];
    }
    $cr_number = $email;
    $condition = "cr_no='$cr_number'";
    $update_cr = sprintf(
        "`change_request_status`=%s",
        GetSQLValueString("Canceled", "text")
    );
    $res = $DBCR->update_data("general_informations", $update_cr, $condition);
    $ALERT->savedata();
}
if (isset($_POST['Cancel_CR9'])) {
    $cr_no = '';
    $status_pic = '';
    foreach ($_POST["cr_no"] as $row) {
        $cr_no .= $row . ',';
    }
    $cr_no = substr($cr_no, 0, -1);
    $cr = explode(",", $cr_no);
    for ($i = 0; $i < count($cr); $i++) {
        $email = $cr[8];
    }
    $cr_number = $email;
    $condition = "cr_no='$cr_number'";
    $update_cr = sprintf(
        "`change_request_status`=%s",
        GetSQLValueString("Canceled", "text")
    );
    $res = $DBCR->update_data("general_informations", $update_cr, $condition);
    $ALERT->savedata();
}
if (isset($_POST['Cancel_CR10'])) {
    $cr_no = '';
    $status_pic = '';
    foreach ($_POST["cr_no"] as $row) {
        $cr_no .= $row . ',';
    }
    $cr_no = substr($cr_no, 0, -1);
    $cr = explode(",", $cr_no);
    for ($i = 0; $i < count($cr); $i++) {
        $email = $cr[9];
    }
    $cr_number = $email;
    $condition = "cr_no='$cr_number'";
    $update_cr = sprintf(
        "`change_request_status`=%s",
        GetSQLValueString("Canceled", "text")
    );
    $res = $DBCR->update_data("general_informations", $update_cr, $condition);
    $ALERT->savedata();
}

if (isset($_POST['testing'])) {
    $so_number = $_POST['so_number'];
    $user_mail = $_SESSION['Microservices_UserEmail'];
    $check_data = $DBKPISO->get_sql("SELECT * FROM sa_kpi_board WHERE so_number='$so_number' AND pic_email LIKE '%$user_mail%'");
    if (isset($check_data[0]['pic_email'])) {
        date_default_timezone_set('Asia/Jakarta');
        $date = date("Y-M-d");
        $time = date("H:i:s");
        $sql_get = $DBKPISO->get_sql("SELECT * FROM sa_kpi_board WHERE so_number='$so_number'");
        $email_pic = explode(",", $sql_get[0]['pic_email']);
        $stts_pic = explode(", </br>", $sql_get[0]['status_approval']);
        for ($i = 0; $i < count($email_pic); $i++) {
            $email = $email_pic[$i];
            $sqlCheck = "SELECT employee_name,employee_email FROM sa_view_employees_v2 WHERE employee_email='" . $email_pic[$i] . "'";
            $get_nama = $DBHCM->get_sql($sqlCheck);
            $employee_name = $get_nama[0]['employee_name'];
            $employee_email = $get_nama[0]['employee_email'];
            $final_email = $employee_email;
            if ($final_email == $user_mail) {
                $angka = $i;
            } else {
                '';
            }
        }
        if (isset($angka)) {
            $nomor = $angka;
            $status_pic = '';
            for ($x = 0; $x < count($stts_pic); $x++) {
                $stts = $stts_pic[$x];
                $stts_apprv = str_replace($stts_pic[$nomor], 'Approved', $stts);
                $stts_pending = str_replace($stts_pic[$x], 'Pending', $stts);
                if ($x == $nomor) {
                    $status_pic .= $stts_apprv . ', </br>';
                } else {
                    $status_pic .= $stts . ', </br>';
                }
            }
            $status_pic = substr($status_pic, 0, -7);
            echo "test" . $stts_pic[$nomor] . "test";
            if ($stts_pic[$nomor] == 'Approved') {
                echo "Berhasil";
            } else {
                echo "Gagal";
            }
        }
    }
}

if (isset($_POST['edit_board'])) {
    $so_number = $_POST['so_number'];
    $order_number = $_POST['order_number'];
    if (isset($_POST['pic_board_edit'])) {
        $pic_board = '';
        $status_pic = '';
        $pic_name = '';
        foreach ($_POST["pic_board_edit"] as $row) {
            $pic_board .= $row . ',';
            $rawpic_name = $DBHCM->get_sql("SELECT employee_name FROM sa_view_employees_v2 WHERE employee_email LIKE '%$row%' AND resign_date is null");
            $pic_name .= $rawpic_name[0]['employee_name'] . ', </br>';
            $status = str_replace($row, 'Pending', $row);
            $status_pic .= $status . ', </br>';
        }
        $pic_name = substr($pic_name, 0, -7);
        $status_pic = substr($status_pic, 0, -7);
        $pic_board = substr($pic_board, 0, -1);
    } else {
        $pic_name = null;
        $pic_board = null;
        $status_pic = null;
    }
    $update_approval = $DBKPISO->get_res("UPDATE sa_kpi_board SET pic_email='$pic_board', pic_approval='$pic_name', status_approval='$status_pic' WHERE order_number='$order_number'");
    $ALERT->savedata();
}

if (isset($_POST['submit_resource'])) {
    $so_number = $_POST['so_number'];
    if (isset($_POST['test'])) {
        $resourcess = '';
        foreach ($_POST["test"] as $row) {
            $resourcess .= $row . ',';
        }
        $resourcess = substr($resourcess, 0, -1);
    } else {
        $resourcess = '';
    }
    $update = $DBKPISO->get_res("UPDATE sa_table_report SET resources='$resourcess' WHERE so_number='$so_number'");
    $ALERT->savedata();
}

//if (isset($_POST['cancel_board'])) {
  //  $so_number = $_POST['so_number'];
    //    $update_status_board = $DBKPISO->get_sql("UPDATE sa_kpi_board SET status='Canceled' WHERE so_number='$so_number'");
      //  $update_status_error = $DBKPISO->get_sql("UPDATE sa_error SET status='Canceled' WHERE so_number='$so_number'");
//	$update_status_kpi = $DBKPISO->get_sql("UPDATE sa_kpi_so_wr SET board_status='Canceled' WHERE so_number='$so_number'");
  //  $ALERT->savedata();
//}
