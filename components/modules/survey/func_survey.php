<?php

global $DB1;
$mdlnamesurvey = "SURVEY";
$DB1 = get_conn($mdlnamesurvey);


if (isset($_POST['add'])) {
    $checkquery = "select count(*) as count from sa_survey where project_code = '" . $_POST['project_code'] . "' AND so_number = '" . $_POST['so_number'] . "' AND project_type = '" . $_POST['project_type'] . "'";
    $check = $DB1->get_sql($checkquery);
    if ($check[0]['count'] > 0) {

        $ALERT->msgcustom('danger', 'Project Survey already created');
    } else {
        $link_datetime = date('U');
        // $survey_link = md5($link_datetime);

        $query = "SELECT sa_pic.pic_id, sa_pic.pic_name, sa_pic.pic_email,sa_pic.pic_phone,sa_customer.customer_code, sa_customer.customer_company_name
                  FROM sa_pic JOIN sa_customer ON sa_pic.customer_code = sa_customer.customer_code WHERE pic_id =" . $_POST['pic_id'] . "";
        $survey = $DB1->get_sql($query);
        $data = $survey[0];

        $query_survey_ID = "SELECT survey_id FROM sa_survey ORDER BY survey_id DESC";
        $get_id = $DB1->get_sql($query_survey_ID);
        $nambah1 = $get_id[0];
        $id_survey = $nambah1['survey_id'] + 3;

        $pic_name = $data['pic_name'];
        $pic_email = $data['pic_email'];
        $pic_phone = $data['pic_phone'];
        $customer_company_name = $data['customer_company_name'];
        $project_type = $_POST['project_type'];
        $project_name = $_POST['project_name'];

        $link_parameters = array(
            'usp' => 'pp_url',
            'entry.573954117' => $id_survey,
            'entry.36655501' => $pic_name,
            'entry.1828338478' => $pic_email,
            'entry.32479059' => $pic_phone,
            'entry.693179537' => $customer_company_name,
            'entry.875195738' => $project_name,
            'entry.1273177308' => $project_type,
        );

        $coba_narik = http_build_query($link_parameters);
        if ($_POST['template_type'] == "full") {
            $link_full = "https://docs.google.com/forms/d/e/1FAIpQLSc2usOISpkzzYUL55aseJCaY4AUnHUUnKjVcJcpBxb_JRm2Bg/viewform?" . $coba_narik;

            $insert = sprintf(
                "(`survey_id`,`pic_id`, `project_source`, `simple_template_id`, `so_number`, `project_code`,`project_name`,`project_type`,`type_of_service`,`template_type`,`survey_link`,`link_datetime`,`main_template_id`,`souvenir`, `extra_information`,`created_by`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
                GetSQLValueString($id_survey, "int"),
                GetSQLValueString($_POST['pic_id'], "int"),
                GetSQLValueString($_POST['project_source'], "text"),
                GetSQLValueString($_POST['simple_template_id'], "int"),
                GetSQLValueString($_POST['so_number'], "text"),
                GetSQLValueString($_POST['project_code'], "text"),
                GetSQLValueString($_POST['project_name'], "text"),
                GetSQLValueString($_POST['project_type'], "text"),
                GetSQLValueString($_POST['type_of_service'], "text"),
                GetSQLValueString($_POST['template_type'], "text"),
                GetSQLValueString($link_full, "text"),
                GetSQLValueString($link_datetime, "text"),
                GetSQLValueString($_POST['main_template_id'], "int"),
                GetSQLValueString($_POST['souvenir'], "text"),
                GetSQLValueString($_POST['extrainf'], "text"),
                GetSQLValueString($_SESSION['Microservices_UserName'] . '<' . $_SESSION['Microservices_UserEmail'] . '>', "text")
            );
            $res = $DB1->insert_data($tblname, $insert);
            $ALERT->savedata();

            $msg1 = '';

            $msg1 .= '<p>Kami mengucapkan terima kasih atas kesempatan yang diberikan kepada kami dalam project ' . $_POST['project_name'] . '</p>';
            $msg1 .= '<p>Sebagai masukan dan koreksi terhadap layanan kami, kami berharap Bapak/Ibu
dapat meluangkan waktu mengisi post-project review dengan mengklik <a href="' . $link_full . '">link</a> berikut ini.</p>';

            $msg1 .= '<p>Kami sangat berharap dapat terus melayani kebutuhan Bapak/Ibu untuk jangka panjang.</p>';
            $msg1 .= '<p>Komitmen Mastersystem adalah senantiasa memberikan Service Excellence kepada para Pelanggan,
sehingga masukan dan koreksi Bapak/Ibu sangat berharga bagi kami untuk terus memperbaiki kualitas layanan.</p>';

            $subject = "Project Review";
            $msg = "<table width='100%'>";
            $msg .= "<tr><td width='30%' rowspan='3'></td><td style='width:40%; border: thin solid #dadada; padding:20px'>";
            $msg .= "<img src='" . $_SERVER['HTTP_HOST'] . "/media/images/profiles/MSI-logo-revisi2.png' style='width:204px;height:74px'>";
            $msg .= "<br/><br/>";
            $from = "customer.care@mastersystem.co.id";
            $to = $_POST['pic_email'];
            $msg .= "<p>Bapak/Ibu Pelanggan yang Terhormat,</p>";
            $msg .= "<p>" . $msg1 . "</p>";
            $msg .= "<p>";
            $msg .= "</p>";
            $msg .= "<p>Terimakasih,</p>";
            $msg .= '<p>Customer Care<br/><br/>Email : customer.care@mastersystem.co.id</p>';
            $msg .= "</table>";
            $headers = "From: " . $from . "\r\n" .
                "Cc: henny.anggra@mastersystem.co.id, lucky.andiani@mastersystem.co.id" . "\r\n" .
                "MIME-Version: 1.0" . "\r\n" .
                "Content-Type: text/html; charset=UTF-8" . "\r\n" .
                "X-Mailer: PHP/" . phpversion();

            $ALERT = new Alert();
            if (!mail($to, $subject, $msg, $headers)) {
                echo $ALERT->email_not_send();
            } else {
                echo $ALERT->email_send();
            }

            $mdlname2 = "KPI_BOARD";
            $DBx = get_conn($mdlname2);
            $tblname2 = 'kpi_board';

            $condition = "so_number='" . $_POST['so_number'] . "'";
            $update = sprintf(
                "`isi`='send_survey'"
            );
            $res = $DBx->update_data($tblname2, $update, $condition);
            //echo $msg;

            // Save Notification in MSIZone
            /*$mdlname = "NOTIFICATION";
    $DBNOTIF = get_conn($mdlname);
    $tblname = "trx_notification";
    $notifmsg= $msg1;
    $notif_link = "index.php?mod=hcm&sub=edo&act=view&edo_id=" . $_POST['edo_id'] . "&submit=Submit";
    $insert = sprintf("(`notif_from`, `notif_to`, `notif_subject`, `notif_post`, `notif_link`) VALUES (%s, %s, %s, %s, %s)",
        GetSQLValueString($from, "text"),
        GetSQLValueString($to, "text"),
        GetSQLValueString(ucwords($status) . " Extra Day Off" . $_POST['edo_id'], "text"),
        GetSQLValueString($notifmsg, "text"),
        GetSQLValueString($notif_link, "text")
    );
    $res = $DBNOTIF->insert_data($tblname, $insert);*/
        } elseif ($_POST['template_type'] == "simple") {
            $link_simple = "https://docs.google.com/forms/d/e/1FAIpQLSeqPCZFX1yt_3MSdjuvHbC5NlorEaGdJSoeG-7jeWZrtVvfaw/viewform?" . $coba_narik;

            $insert = sprintf(
                "(`survey_id`,`pic_id`, `project_source`, `simple_template_id`, `so_number`, `project_code`,`project_name`,`project_type`,`type_of_service`,`template_type`,`survey_link`,`link_datetime`,`main_template_id`,`souvenir`, `extra_information`,`created_by`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
                GetSQLValueString($id_survey, "int"),
                GetSQLValueString($_POST['pic_id'], "int"),
                GetSQLValueString($_POST['project_source'], "text"),
                GetSQLValueString($_POST['simple_template_id'], "int"),
                GetSQLValueString($_POST['so_number'], "text"),
                GetSQLValueString($_POST['project_code'], "text"),
                GetSQLValueString($_POST['project_name'], "text"),
                GetSQLValueString($_POST['project_type'], "text"),
                GetSQLValueString($_POST['type_of_service'], "text"),
                GetSQLValueString($_POST['template_type'], "text"),
                GetSQLValueString($link_simple, "text"),
                GetSQLValueString($link_datetime, "text"),
                GetSQLValueString($_POST['main_template_id'], "int"),
                GetSQLValueString($_POST['souvenir'], "text"),
                GetSQLValueString($_POST['extrainf'], "text"),
                GetSQLValueString($_SESSION['Microservices_UserName'] . '<' . $_SESSION['Microservices_UserEmail'] . '>', "text")
            );
            $res = $DB1->insert_data($tblname, $insert);
            $ALERT->savedata();

            $msg1 = '';

            $msg1 .= '<p>Kami mengucapkan terima kasih atas kesempatan yang diberikan kepada kami dalam project ' . $_POST['project_name'] . '</p>';
            $msg1 .= '<p>Sebagai masukan dan koreksi terhadap layanan kami, kami berharap Bapak/Ibu
dapat meluangkan waktu mengisi post-project review dengan mengklik <a href="' . $link_simple . '">link</a> berikut ini.</p>';

            $msg1 .= '<p>Kami sangat berharap dapat terus melayani kebutuhan Bapak/Ibu untuk jangka panjang.</p>';
            $msg1 .= '<p>Komitmen Mastersystem adalah senantiasa memberikan Service Excellence kepada para Pelanggan,
sehingga masukan dan koreksi Bapak/Ibu sangat berharga bagi kami untuk terus memperbaiki kualitas layanan.</p>';

            $subject = "Project Review";
            $msg = "<table width='100%'>";
            $msg .= "<tr><td width='30%' rowspan='3'></td><td style='width:40%; border: thin solid #dadada; padding:20px'>";
            $msg .= "<img src='" . $_SERVER['HTTP_HOST'] . "/media/images/profiles/MSI-logo-revisi2.png' style='width:204px;height:74px'>";
            $msg .= "<br/><br/>";
            $from = "customer.care@mastersystem.co.id";
            $to = $_POST['pic_email'];
            $msg .= "<p>Bapak/Ibu Pelanggan yang Terhormat,</p>";
            $msg .= "<p>" . $msg1 . "</p>";
            $msg .= "<p>";
            $msg .= "</p>";
            $msg .= "<p>Terimakasih,</p>";
            $msg .= '<p>Customer Care<br/><br/>Email : customer.care@mastersystem.co.id</p>';
            $msg .= "</table>";
            $headers = "From: " . $from . "\r\n" .
                "Cc: henny.anggra@mastersystem.co.id, lucky.andiani@mastersystem.co.id" . "\r\n" .
                "MIME-Version: 1.0" . "\r\n" .
                "Content-Type: text/html; charset=UTF-8" . "\r\n" .
                "X-Mailer: PHP/" . phpversion();

            $ALERT = new Alert();
            if (!mail($to, $subject, $msg, $headers)) {
                echo $ALERT->email_not_send();
            } else {
                echo $ALERT->email_send();
            }

            $mdlname2 = "KPI_BOARD";
            $DBx = get_conn($mdlname2);
            $tblname2 = 'kpi_board';

            $condition = "so_number='" . $_POST['so_number'] . "'";
            $update = sprintf(
                "`isi`='send_survey'"
            );
            $res = $DBx->update_data($tblname2, $update, $condition);
        }
    }
} elseif (isset($_POST['save'])) {
    $condition = "survey_id=" . $_POST['survey_id'];
    $update = sprintf(
        "`survey_id`=%s",
        GetSQLValueString($_POST['survey_id'], "int")
    );
    $res = $DB1->update_data($tblname, $update, $condition);
    $ALERT->savedata();
} elseif (isset($_POST['resend_email'])) {
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
    var_dump($msg);
    die;
    $ALERT = new Alert();
    if (!mail($to, $subject, $msg, $headers)) {
        echo "Fail";
    } else {
        echo "Success";
    }
}
