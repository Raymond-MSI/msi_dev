<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

$rootfolder = $_SERVER['DOCUMENT_ROOT'];
include($rootfolder . "/MICROSERVICES/applications/connections/connections.php");
include($rootfolder . "/MICROSERVICES/components/classes/func_databases_v3.php");
include($rootfolder . "/MICROSERVICES/components/classes/func_cfg_web.php");
include($rootfolder . "/MICROSERVICES/components/classes/func_component.php");
include($rootfolder . "/MICROSERVICES/components/classes/func_modules.php");

$DB = new WebConfig($hostname, $username, $password, $database);

if (isset($_POST['act']) && $_POST['act'] == 'getPIC') {
    $mdlname = "SURVEY";
    $DB1 = get_conn($mdlname);
    $query = 'select pic_id, pic_name, pic_email, pic_address, pic_city, pic_phone, customer_company_name, cust.customer_code
    from sa_pic pic join sa_customer cust on pic.customer_code = cust.customer_code where pic_id = ' . $_POST['pic_id'];
    $data = $DB1->get_sql($query);
    $data1 = json_encode($data[0]);
    echo $data1;
}

if (isset($_POST['act']) && $_POST['act'] == 'getListSO') {
    $mdlname = "SERVICE_BUDGET";
    $DB1 = get_conn($mdlname);
    $query = 'select distinct(so_number), project_code from sa_trx_project_list where customer_code = "' . $_POST['cust_id'] . '"';
    $data = $DB1->get_sql($query);
    $data1 = $data[0];
    $data2 = $data[1];
    $listsonumber = array();
    do {
        array_push($listsonumber, "<option value='" . $data1['so_number'] . "'>" . $data1['project_code'] . " ( " . $data1['so_number'] . " )");
    } while ($data1 = $data2->fetch_assoc());

    $listsonumber = json_encode($listsonumber);
    echo $listsonumber;
}

if (isset($_POST['act']) && $_POST['act'] == 'getProjectName') {
    $proj_name = '';
    $tos = '';
    $mdlname = "KPI_BOARD";
    $DBKPI = get_conn($mdlname);
    $mdlname2 = "SERVICE_BUDGET";
    $DBSB = get_conn($mdlname2);
    if ($_POST['source'] == 'kpi') {
        if ($_POST['type'] == 'MSI Project Implementation') {
            $query = 'select project_name, SB_project_category from sa_data_so where so_number_kpi = "' . $_POST['so_number'] . '"';
            $data = $DBKPI->get_sql($query);
            $data1 = $data[0];
            $data2 = $data[1];
            $proj_name = $data1['project_name'];
            switch ($data1['SB_project_category']) {
                case 1:
                    $tos = 'High';
                    break;
                case 2:
                    $tos = 'Medium';
                    break;
                case 3:
                    $tos = 'Standard';
                    break;
            }
        }
        if ($_POST['type'] == 'MSI Project Maintenance') {
            $query = 'select project_name, tos_id from sa_trx_project_implementations imp join sa_trx_project_list trx on imp.project_id = trx.project_id where trx.so_number = "' . $_POST['so_number'] . '" AND service_type = 2';
            $data = $DBSB->get_sql($query);
            $data1 = $data[0];
            $data2 = $data[1];
            $proj_name = $data1['project_name'];
            switch (str_replace(';', '', $data1['tos_id'])) {
                case 5:
                    $tos = 'Gold';
                    break;
                case 6:
                    $tos = 'Silver';
                    break;
                case 7:
                    $tos = 'Bronze';
                    break;
            }
        }
    }
    if ($_POST['source'] == 'sbf') {
        if ($_POST['type'] == 'MSI Project Implementation') {
            $query = 'select project_name, tos_category_id from sa_trx_project_implementations imp join sa_trx_project_list trx on imp.project_id = trx.project_id where trx.so_number = "' . $_POST['so_number'] . '" AND service_type = 1';
            $data = $DBSB->get_sql($query);
            $data1 = $data[0];
            $data2 = $data[1];
            $proj_name = $data1['project_name'];
            switch ($data1['tos_category_id']) {
                case 1:
                    $tos = 'High';
                    break;
                case 2:
                    $tos = 'Medium';
                    break;
                case 3:
                    $tos = 'Standard';
                    break;
            }
        }
        if ($_POST['type'] == 'MSI Project Maintenance') {
            $query = 'select project_name, tos_id from sa_trx_project_implementations imp join sa_trx_project_list trx on imp.project_id = trx.project_id where trx.so_number = "' . $_POST['so_number'] . '" AND service_type = 2';
            $data = $DBSB->get_sql($query);
            $data1 = $data[0];
            $data2 = $data[1];
            $proj_name = $data1['project_name'];
            switch (str_replace(';', '', $data1['tos_id'])) {
                case 5:
                    $tos = 'Gold';
                    break;
                case 6:
                    $tos = 'Silver';
                    break;
                case 7:
                    $tos = 'Bronze';
                    break;
            }
        }
    }
    $querycust = 'select customer_code, customer_name from sa_trx_project_list where so_number = "' . $_POST['so_number'] . '"';
    $cust = $DBSB->get_sql($querycust);
    $custcode = $cust[0]['customer_code'];
    $custname = $cust[0]['customer_name'];
    echo $custcode . '|' . $proj_name . '|' . $tos . '|' . $custname;
}

if (isset($_POST['act']) && $_POST['act'] == 'getPICList') {
    $mdlname = "SURVEY";
    $DB1 = get_conn($mdlname);
    $query = 'select pic_id, pic_name, pic_email, pic_phone, survey_count, category from sa_pic pic join sa_customer cust on cust.customer_code=pic.customer_code where cust.customer_code = "' . $_POST['cust_code'] . '"';
    $data = $DB1->get_sql($query);
    $data1 = $data[0];
    $data2 = $data[1];
    $listpic = array();
    do {
        array_push($listpic, "<option value='" . $data1['pic_id'] . "' email='" . $data1['pic_email'] . "' phone='" . $data1['pic_phone'] . "' survcount='" . $data1['survey_count'] . "' category='" . $data1['category'] . "'>" . $data1['pic_name'] . "</option>");
    } while ($data1 = $data2->fetch_assoc());
    $listpic = json_encode($listpic);
    echo $listpic;
}

if (isset($_POST['act']) && $_POST['act'] == 'cancelSurvey') {
    $mdlname = "SURVEY";
    $DB1 = get_conn($mdlname);
    $condition = "survey_link = '" . $_POST['survey_link'] . "'";
    $update = "status='inactive' ";
    $tblname = 'survey';
    $update = $DB1->update_data($tblname, $update, $condition);
    $query = "select status from sa_survey where survey_link = '" . $_POST['survey_link'] . "'";
    $data = $DB1->get_sql($query);
    if ($data[0]['status'] == 'inactive') {
        echo "Success";
    } else {
        echo "Update Fail";
    }
}

if (isset($_POST['act']) && $_POST['act'] == 'fillSurvey') {
    $_SESSION['Microservices_UserEmail'] = 'MSI';
    $mdlname = "SURVEY";
    $DB1 = get_conn($mdlname);
    $answer = $_POST['data'];
    $survey_link = $answer[0]['value'];
    $survey_id = $answer[1]['value'];
    $ifexist = "select * from sa_trx_survey where survey_link = '" . $survey_link . "'";
    $exist = $DB1->get_sql($ifexist);
    if ($exist[2] >= 1) {
        echo "Survey Already Submitted, no longer accessible";
        exit();
    }
    $i = 3;
    $main_rating = array();
    $main_essay = array();
    $main_engineer = array();
    $engineer = array('question_id' => 'engineer', 'value' => array());
    while ($answer[$i]['name'] != '') {
        $question = explode('_', $answer[$i]['name']);
        $question_type = $question[0];
        if ($question_type != 'engineer')
            $question_id = $question[2];
        // if($question_id == 'engineer'){
        // do {
        //         $question = explode('_',$answer[$i]['name']);
        //         array_push($engineer['value'], array('email' => $question[3], 'value' => $answer[$i]['value']));
        //         $i++;
        //     } while (strpos($answer[$i]['name'], 'engineer') !== false);
        //     array_push($temp_answer, $engineer);
        // } else {
        //     if($answer[$i]['value'] != '')
        //     array_push($temp_answer, array('question_id' => $question_id, 'value' => $answer[$i]['value']));
        //     $i++;
        // }
        switch ($question_type) {
            case 'radio':
                array_push($main_rating, array('question_id' => $question_id, 'value' => $answer[$i]['value'], 'weight' => $question[3]));
                break;
            case 'essay':
                if (strlen($answer[$i]['value']) > 0) {
                    array_push($main_essay, array('question_id' => $question_id, 'value' => $answer[$i]['value'], 'weight' => $question[3]));
                }
                break;
            case 'engineer':
                $man = explode('_', $answer[$i]['value']);
                $name = $man[0];
                $email = $man[1];
                array_push($main_engineer, array('name' => $name, 'email' => $email, 'weight' => $question[2]));
                break;
        }
        $i++;
    }
    $main_rating = json_encode($main_rating);
    $main_essay = json_encode($main_essay);
    $main_engineer = json_encode($main_engineer);
    $souvenir = 'No';
    $souvenirAddress = '';
    if (isset($_POST['souvenirCheck']) && $_POST['souvenirCheck'] == 'Yes') {
        $souvenir = 'Yes';
        if (isset($_POST['souvenirAddress']) && $_POST['souvenirAddress'] != '') {
            $souvenirAddress = $_POST['souvenirAddress'];
        }
    }

    $insert = sprintf(
        "(`survey_id`, `survey_link`, `project_code`,`project_title`, `type`, `main_rating`, `main_essay`, `main_engineer`, `souvenir`,`souvenir_address`) VALUES (%s,%s, %s, %s, %s, %s, %s, %s, %s, %s)",
        GetSQLValueString($survey_id, "int"),
        GetSQLValueString($survey_link, "text"),
        GetSQLValueString($_POST['projectCode'], "text"),
        GetSQLValueString($_POST['projectTitle'], "text"),
        GetSQLValueString($_POST['type'], "text"),
        GetSQLValueString($main_rating, "text"),
        GetSQLValueString($main_essay, "text"),
        GetSQLValueString($main_engineer, "text"),
        GetSQLValueString($souvenir, "text"),
        GetSQLValueString($souvenirAddress, "text"),
    );
    $res = $DB1->insert_data('trx_survey', $insert);
    $pic = $answer[2]['value'];
    $conditionpic = "pic_id=" . $pic;
    $updatepic = sprintf("survey_count = survey_count+1");
    $res = $DB1->update_data("pic", $updatepic, $conditionpic);
    echo 1;
}

if (isset($_POST['act']) && $_POST['act'] == 'exportSurvey') {
    $year = $_POST['year'];
    // fungsi header dengan mengirimkan raw data excel
    header("Content-type: application/vnd-ms-excel");

    // membuat nama file ekspor "export-to-excel.xls"
    header("Content-Disposition: attachment; filename=List Hasil Survey Tahun $year.xls");

    $content = "
    <table border='1'>
    <tr>
        <th>No</th>
        <th>Project Title</th>
        <th>Project Code</th>
        <th>Nilai</th>
        <th>Comment</th>
        <th>Penilaian Engineer</th>
        <th>Submit Date</th>
        <th>Pertanyaan Rating</th>
        <th>Pertanyaan Essay</th>
    ";

    $query = "SELECT surv.survey_id, so_number, pic_name, pic_email, customer_company_name, trx.project_code, trx.project_title, surv.survey_link, main_rating, main_essay, main_engineer, alt_rating, alt_essay, alt_engineer, trx.created_datetime from sa_survey surv join sa_trx_survey trx on surv.survey_id = trx.survey_id join sa_pic pic on surv.pic_id = pic.pic_id join sa_customer cust on pic.customer_code = cust.customer_code where YEAR(surv.created_datetime) = " . $year;
    $mdlname = "SURVEY";
    $DB1 = get_conn($mdlname);
    $result = $DB1->get_sql($query);
    $curr = $result[0];
    $next = $result[1];

    do {
        $content .= "<tr>";
        $rating = json_decode($curr['main_rating'], true); //count rating average value
        $average_value = 0;
        $count = 0;
        $total_value = 0;
        $weight = 1;
        foreach ($rating as $score) {
            $total_value += $score['value'] * $weight;
            $count++;
        }
        $average_value = $total_value / ($count);
        $content .= "<td>" . $average_value . "</td>";

        $essay = json_decode($curr['main_essay'], true); //essay answer
        $content .= "<td>" . $essay[0]['value'] . "</td>";

        $engineer = json_decode($curr['main_engineer'], true); //engineer
        $content .= "<td><ul>";
        foreach ($engineer as $score) {
            $content .= "<li>" . $score['name'] . " ( " . $score['email'] . " ) </li>";
        }
        $content .= "</ul></td>";
        $content .= "<td>" . $curr['created_datetime'] . "</td>";
        $content .= "</tr>";
        $content .= "</table>";
    } while ($curr = $next->fetch_assoc());
    echo $content;
}

if (isset($_POST['act']) && $_POST['act'] == 'resendSurvey') {
    $mdlname = "SURVEY";
    $DB1 = get_conn($mdlname);
    $query = "SELECT survey_link, survey_id, project_name, pic_email, pic_name from sa_survey surv join sa_pic pic on surv.pic_id = pic.pic_id where survey_id = " . $_POST['survey_id'];
    $result = $DB1->get_sql($query);
    $data = $result[0];

    $survey_link = "10.20.50.160/survey2.php?link=" . $data['survey_link'];
    $to = $data['pic_email'];
    $name = $data['pic_name'];

    $msg1 = '';
    $msg1 .= '<p>Perkenalkan saya dengan Henny Anggra, customer care dari PT Mastersystem Infotama.</p>';
    $msg1 .= '<p>Mohon konfirmasinya apakah Bapak sudah menerima email dari kami terkait survey project review untuk Project:
    "' . $data['project_name'] . '"</p>';
    $msg1 .= '<p>Jika belum, mohon ketersediaan Bapak untuk mengisi survey berikut ini:</p>';
    $msg1 .= '<p>' . $survey_link . '</p>';


    $subject = "Project Review";
    $msg = "<table width='100%'>";
    $msg .= "<tr><td width='30%' rowspan='3'></td><td style='width:40%; border: thin solid #dadada; padding:20px'>";
    $msg .= "<img src='" . $_SERVER['HTTP_HOST'] . "/media/images/profiles/MSI-logo-revisi2.png' style='width:204px;height:74px'>";
    $msg .= "<br/><br/>";
    $msg .= "<p>Selamat Siang Bapak/Ibu $name,</p>";
    $msg .= "<p>" . $msg1 . "</p>";
    $msg .= "<p>";
    $msg .= "</p>";
    $msg .= "<p>Terimakasih,</p>";
    $msg .= '<p>Customer Care<br/><br/>Email : Customer.Care@mastersystem.co.id</p>';
    $msg .= "</table>";

    $headers = "Cc: henny.anggra@mastersystem.co.id, lucky.andiani@mastersystem.co.id" . "\r\n" .
        "MIME-Version: 1.0" . "\r\n" .
        "Content-Type: text/html; charset=UTF-8" . "\r\n" .
        "X-Mailer: PHP/" . phpversion();

    $ALERT = new Alert();
    if (!mail($to, $subject, $msg, $headers)) {
        echo "Fail";
    } else {
        echo "Success";
    }
}

if (isset($_POST['act']) && $_POST['act'] == 'fillSurvey') {
    $answer = $_POST['data'];
    $survey_link = $answer[0]['value'];
    $survey_id = $answer[1]['value'];
    $ifexist = "select * from sa_trx_survey where survey_link = '" . $survey_link . "'";
    $exist = $DB1->get_sql($ifexist);
    if ($exist[2] >= 1) {
        echo "Survey Already Submitted, no longer accessible";
        exit();
    }
    $i = 3;
    $main_rating = array();
    $main_essay = array();
    $main_engineer = array();
    $engineer = array('question_id' => 'engineer', 'value' => array());
    $totalrating = 0;
    $ratingcount = 0;
    $avg_rating;
    while (isset($answer[$i])) {
        $question = explode('_', $answer[$i]['name']);
        $question_type = $question[0];
        if ($question_type != 'engineer')
            $question_id = $question[2];
        // if($question_id == 'engineer'){
        // do {
        //         $question = explode('_',$answer[$i]['name']);
        //         array_push($engineer['value'], array('email' => $question[3], 'value' => $answer[$i]['value']));
        //         $i++;
        //     } while (strpos($answer[$i]['name'], 'engineer') !== false);
        //     array_push($temp_answer, $engineer);
        // } else {
        //     if($answer[$i]['value'] != '')
        //     array_push($temp_answer, array('question_id' => $question_id, 'value' => $answer[$i]['value']));
        //     $i++;
        // }
        switch ($question_type) {
            case 'radio':
                array_push($main_rating, array('question_id' => $question_id, 'value' => $answer[$i]['value'], 'weight' => $question[3]));
                $ratingcount++;
                $totalrating += $answer[$i]['value'];
                break;
            case 'essay':
                if (strlen($answer[$i]['value']) > 0) {
                    array_push($main_essay, array('question_id' => $question_id, 'value' => $answer[$i]['value'], 'weight' => $question[3]));
                }
                break;
            case 'engineer':
                $man = explode('<', $answer[$i]['value']);
                $name = $man[0];
                $email = str_replace('>', '', $man[1]);
                array_push($main_engineer, array('name' => $name, 'email' => $email, 'weight' => $question[2]));
                break;
        }
        $i++;
    }
    $main_rating = json_encode($main_rating);
    $main_essay = json_encode($main_essay);
    $main_engineer = json_encode($main_engineer);
    $souvenir = 'No';
    $souvenirAddress = '';
    if (isset($_POST['souvenirCheck']) && $_POST['souvenirCheck'] == 'Yes') {
        $souvenir = 'Yes';
        if (isset($_POST['souvenirAddress']) && $_POST['souvenirAddress'] != '') {
            $souvenirAddress = $_POST['souvenirAddress'];
        }
    }

    if ($ratingcount > 0) {
        $avg_rating = $totalrating / $ratingcount;
    }

    $insert = sprintf(
        "(`survey_id`, `survey_link`, `project_code`,`project_title`, `type`, `main_rating`, `rating_average`,`main_essay`, `main_engineer`, `souvenir`,`souvenir_address`) VALUES (%s,%s, %s, %s,%s, %s, %s, %s, %s, %s, %s)",
        GetSQLValueString($survey_id, "int"),
        GetSQLValueString($survey_link, "text"),
        GetSQLValueString($_POST['projectCode'], "text"),
        GetSQLValueString($_POST['projectTitle'], "text"),
        GetSQLValueString($_POST['type'], "text"),
        GetSQLValueString($main_rating, "text"),
        GetSQLValueString($avg_rating, "text"),
        GetSQLValueString($main_essay, "text"),
        GetSQLValueString($main_engineer, "text"),
        GetSQLValueString($souvenir, "text"),
        GetSQLValueString($souvenirAddress, "text"),
    );
    $res = $DB1->insert_data('trx_survey', $insert);
    $pic = $answer[2]['value'];
    $conditionpic = "pic_id=" . $pic;
    $updatepic = sprintf("survey_count = survey_count+1");
    $res = $DB1->update_data("pic", $updatepic, $conditionpic);
    $reply_datetime = date("Y-m-d G:i:s");
    $updatesurvey = sprintf("status = 'submitted', reply_datetime = '$reply_datetime'");
    $conditionsurvey = "survey_id = " . $survey_id;
    $res = $DB1->update_data("survey", $updatesurvey, $conditionsurvey);

    if ($avg_rating < 7) {
        $query = "SELECT surv.survey_id, so_number, pic_name, pic_email, pic_phone, title, customer_company_name, trx.project_code, trx.project_title,
            surv.survey_link, type, main_rating, main_essay, main_engineer, trx.created_datetime, main_template_id, simple_template_id, project_type, type_of_service, project_name from sa_survey surv join sa_trx_survey trx 
            on surv.survey_id = trx.survey_id join sa_pic pic on surv.pic_id = pic.pic_id join sa_customer cust on pic.customer_code = cust.customer_code
            where trx.survey_link = '$survey_link'";
        $result = $DB1->get_sql($query);
        $data = $result[0];
        print_r($data);

        if ($_POST['type'] == 'simple') {
            $template = $data['simple_template_id'];
        } else {
            $template = $data['main_template_id'];
        }

        $mdlname3    = "KPI_BOARD";
        $DB3 = get_conn($mdlname3);
        $getpm = "SELECT distinct(Nama) FROM `sa_user` where project_code =  '" . $data['project_code'] . "' and role = 'Project Manager'";
        $resultpm = $DB3->get_sql($getpm);
        $pm = $resultpm[0];


        $gettemplate = "SELECT questions, template_name FROM sa_question_template where template_id = " . $template;
        $temp_list = $DB1->get_sql($gettemplate);
        $template = $temp_list[0];
        $questions = str_replace(']', '', str_replace('[', '', $template['questions']));

        $getquestions = "SELECT * FROM sa_question_library where question_id IN (" . $questions . ") AND category not like 'engineer'  order by CASE
        WHEN category = 'rating' THEN 1
        WHEN category = 'textbox' THEN 2
        END ASC;";
        $temp_questions = $DB1->get_sql($getquestions);
        $currquestion = $temp_questions[0];
        $nextquestion = $temp_questions[1];



        $htmlString = '<p>Dear All,</p>';

        $htmlString .= '<p>Berikut ini kami informasikan hasil survey dengan response tidak puas yang telah masuk sebagai berikut: </p>';

        $htmlString .= '<table>
        <tr><td>Project Code</td><td>' . $data['project_code'] . '</td></tr>
        <tr><td>Project Name</td><td>' . $data['project_name'] . '</td></tr>
        <tr><td>PM</td><td>' . $pm['Nama'] . '</td></tr>
        <tr><td>Project Type</td><td>' . $data['project_type'] . '</td></tr>
        <tr><td>Type of Service</td><td>' . $data['type_of_service'] . '</td></tr>
        <tr><td>PIC</td><td>' . $data['pic_name'] . '</td></tr>
        <tr><td>Title</td><td>' . $data['title'] . '</td></tr>
        <tr><td>Company Name</td><td>' . $data['customer_company_name'] . '</td></tr>
        <tr><td>Email</td><td>' . $data['pic_email'] . '</td></tr>';
        $i = 3;
        do {
            $question = explode('_', $answer[$i]['name']);
            $question_type = $question[0];
            $value = $answer[$i]['value'];
            if ($question_type == 'engineer') {
                $value = explode('<', $answer[$i]['value']);
                $value = str_replace('>', '', $value[1]);
            }
            $htmlString .= "<tr><td>" . $currquestion['question'] . "</td><td>" . $value . "</td></tr>";
            $i++;
        } while ($currquestion = $nextquestion->fetch_assoc());
        $htmlString .= '</table>';
        $to = 'hendra.novyantara@mastersystem.co.id';
        $from = 'customer.care@mastersystem.co.id';
        $subject = 'Notifikasi Hasil Survey Dibawah 7';
        $headers = "Cc: " . "\r\n" .
            "MIME-Version: 1.0" . "\r\n" .
            "Content-Type: text/html; charset=UTF-8" . "\r\n" .
            "X-Mailer: PHP/" . phpversion();

        mail($to, $subject, $htmlString, $headers);
    }

    echo 1;
}

if (isset($_POST['act']) && $_POST['act'] == 'saveResource') {
    $listresource = $_POST['data'];
    $list = array();
    foreach ($listresource as $value) {
        $man = explode(' <', $value['value']);
        array_push($list, array('name' => $man[0], 'email' => str_replace('>', '', $man[1])));
    }
    $list = json_encode($list);
    $mdlname = "SURVEY";
    $DB1 = get_conn($mdlname);
    $condition = 'survey_id = ' . $_POST['survey_id'];
    $update = sprintf(
        "main_engineer=%s",
        GetSQLValueString($list, "text")
    );
    $res = $DB1->update_data('trx_survey', $update, $condition);
}

if (isset($_POST['act']) && $_POST['act'] == 'getProjectDetail') {
    $so_number = $_POST['so_number'];
    $source = $_POST['source'];
    if ($source == 'kpi' && $so_number != '') {
        $mdlname = "KPI_BOARD";
        $DB1 = get_conn($mdlname);
        $query = 'select if(so.SB_service_type_implementation != "0",so.SB_project_category, null) as implementation, so.SB_service_type_maintenance as maintenance from sa_kpi_board kpi join sa_data_so so on kpi.so_number = so.so_number_kpi where kpi.verif_status = "Completed;" AND kpi.so_number = "' . $so_number . '"';
        $data = $DB1->get_sql($query);
        $data1 = $data[0];
        $data2 = $data[1];
        $sbtype = '';
        if ($data1['implementation'] && $data1['maintenance'] != 0) {
            $sbtype = 'both';
        } else {
            if ($data1['implementation'] != null) {
                $sbtype = 'imp';
            }
            if ($data1['maintenance'] != 0) {
                $sbtype = 'mtc';
            }
        }
        echo $sbtype;
    }
    if ($source == 'sbf' && $so_number != '') {
        $mdlname = "SERVICE_BUDGET";
        $DB1 = get_conn($mdlname);
        $query = 'select bundling from sa_trx_project_list where so_number = "' . $so_number . '" and status in ("acknowledge","approved")';
        $data = $DB1->get_sql($query);
        $data1 = $data[0];
        $data2 = $data[1];
        $sbtype = '';
        if (strpos($data1['bundling'], '1;2') !== false) {
            $sbtype = 'both';
        } else {
            if (strpos($data1['bundling'], '1') !== false) {
                $sbtype = 'imp';
            }
            if (strpos($data1['bundling'], '2') !== false) {
                $sbtype = 'mtc';
            }
        }
        echo $sbtype;
    }
}

if (isset($_POST['act']) && $_POST['act'] == 'getCustomerName') {
    $mdlname = "SURVEY";
    $DB1 = get_conn($mdlname);
    $query = 'select customer_company_name from sa_customer where customer_code = "' . $_POST['cust_code'] . '"';
    $data = $DB1->get_sql($query);
    echo $data[0]['customer_company_name'];
}
