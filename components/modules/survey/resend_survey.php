<?php
$mdlname = "SURVEY";
$DB1 = get_conn($mdlname);
$query = "SELECT survey_link, survey_id, project_name, pic_email, pic_name from sa_survey surv join sa_pic pic on surv.pic_id = pic.pic_id where status = 'default'";
$result = $DB1->get_sql($query);
while ($data = mysqli_fetch_array($result[1])) {

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
