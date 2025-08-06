<?php

// $_SESSION{
//     'Microservices_UserEmail'} = 'syamsul@mastersystem.co.id';
$_SESSION['Microservices_UserEmail'] = 'malik.aulia@mastersystem.co.id';


echo "==========";
echo "Execution module : fillsurvey";
echo "Started : " . date("d-M-Y G:i:s");
echo "==========<br/>";
$time_start = microtime(true);


// include_once("components/classes/func_crontab.php");
// $descErr = "Completed";
// $DBCRON = get_conn("CRONTAB");
// $DBCRON->beginning();


global $DB1;
$mdlname = "SURVEY";
$DB1 = get_conn($mdlname);
$query = $DB1->get_sqlV2("SELECT surv.survey_id, so_number, pic_name, pic_email, pic_phone, title, customer_company_name, trx.project_code, trx.project_title, trx.rating_average,trx.answer_id,
            surv.survey_link, type, main_rating, main_essay, main_engineer, trx.created_datetime, main_template_id, simple_template_id, project_type, type_of_service, project_name from sa_survey surv join sa_trx_survey trx 
            on surv.survey_id = trx.survey_id join sa_pic pic on surv.pic_id = pic.pic_id join sa_customer cust on pic.customer_code = cust.customer_code
            WHERE trx.rating_average < 8 AND trx.type = 'simple' and trx.flag = 0 ");
while ($data = mysqli_fetch_array($query[1])) {


    // $mdlname3    = "KPI_BOARD";
    // $DB3 = get_conn($mdlname3);
    // $getpm = "SELECT distinct(Nama) FROM `sa_user` where project_code =  '" . $data['project_code'] . "' and role = 'Project Manager'";
    // $resultpm = $DB3->get_sql($getpm);
    // $pm = $resultpm[0];

    // $mdlname3 = "WRIKE_INTEGRATE";
    // $DB3 = get_conn($mdlname3);
    // $getpm = "SELECT * from sa_resource_assignment WHERE project_code =  '" . $data['project_code'] . "' and (roles = 'Project Coordinator - ' or roles = 'Project Manager')";
    // $resultpm = $DB3->get_sql($getpm);
    // $pm = $resultpm[0];




    $question_id = json_decode($data['main_rating'], true);
    $question_id2 = '';
    $question_id5 = '';
    $question_id11 = '';
    $question_id14 = '';
    $question_id17 = '';
    $question_id20 = '';
    $question_id23 = '';
    $question_id35 = '';

    foreach ($question_id as $value) {

        if ($value['question_id'] == '2') {
            $question_id2 = $value['value'];
        }

        if ($value['question_id'] == '5') {
            $question_id5 = $value['value'];
        }

        if ($value['question_id'] == '8') {
            $question_id8 = $value['value'];
        }

        if ($value['question_id'] == '11') {
            $question_id11 = $value['value'];
        }

        if ($value['question_id'] == '14') {
            $question_id14 = $value['value'];
        }

        if ($value['question_id'] == '17') {
            $question_id17 = $value['value'];
        }

        if ($value['question_id'] == '20') {
            $question_id20 = $value['value'];
        }

        if ($value['question_id'] == '23') {
            $question_id23 = $value['value'];
        }

        if ($value['question_id'] == '35') {
            $question_id8 = '';
        }
    }
    $questionid2 =  "<td>" . $question_id2 . "</td>";
    $questionid5 =  "<td>" . $question_id5 . "</td>";
    $questionid8 =  "<td>" . $question_id8 . "</td>";
    $questionid11 =  "<td>" . $question_id11 . "</td>";
    $questionid14 =  "<td>" . $question_id14 . "</td>";
    $questionid17 =  "<td>" . $question_id17 . "</td>";
    $questionid20 =  "<td>" . $question_id20 . "</td>";
    $questionid23 =  "<td>" . $question_id23 . "</td>";


    $questionessay = json_decode($data['main_essay'], true);
    $questionessay26 = '';
    foreach ($questionessay as $essay) {
        if ($essay['question_id'] == '26') {
            $questionessay26 = $essay['value'];
        }
    }
    $essay = "<td>" . $questionessay26 . "</td>";

    // var_dump($essay);



    $engineer = json_decode($data['main_engineer'], true); //engineer
    // echo "<td><ul>";
    $engineer1 = '';
    foreach ($engineer as $score) {
        $engineer1 .= "<li>" . $score['name'] . " (" . $score['email'] . ") </li>";
        if ($score['email'] == 'none') {
            break;
        }
    }
    $tarikengineer = $engineer1;




    // $to = "malik.aulia@mastersystem.co.id>, malik.aulia@mastersystem.co.id>, malik.aulia@mastersystem.co.id>, malik.aulia@mastersystem.co.id>";
    // $from = "MSIZone<msizone@mastersystem.co.id>";
    // $cc = "malik.aulia@mastersystem.co,id";
    // $bcc = "";
    // $reply = $from;
    // $subject = "Notifikasi Hasil Survey Dibawah 7";

    $msg = '<p>Dear All,</p>';

    $msg .= '<p>Berikut ini kami informasikan hasil survey dengan response tidak puas yang telah masuk sebagai berikut: </p>';
    $msg .= '<table border = "1" width="1100px">';
    $msg .= '<tr><td>Project Code</td><td>' . $data['project_code'] . '</td></tr>';

    $msg .= '<tr><td>SO NUMBER</td><td>' . $data['so_number'] . '</td></tr>';

    $msg .= '<tr><td>Project Name</td><td>' . $data['project_name'] . '</td></tr>';

    // $msg .= '<tr><td>PM / Project Coordinator</td><td>' . $pm['resource_email'] . '</td></tr>';

    $msg .= '<tr><td>Project Type</td><td>' . $data['project_type'] . '</td></tr>';

    $msg .= '<tr><td>Type of Service</td><td>' . $data['type_of_service'] . '</td></tr>';

    $msg .= '<tr><td>PIC</td><td>' . $data['pic_name'] . '</td></tr>';

    $msg .= '<tr><td>TYPE</td><td>' . $data['type'] . '</td></tr>';

    $msg .= '<tr><td>Title</td><td>' . $data['title'] . '</td></tr>';

    $msg .= '<tr><td>Company Name</td><td>' . $data['customer_company_name'] . '</td></tr>';

    $msg .= '<tr><td>Email</td><td>' . $data['pic_email'] . '</td></tr>';
    $msg .= '<tr><td>Project ini memiliki Perencanaan Kerja yang baik.</td>' . $questionid2 . '</tr>';
    $msg .= '<tr><td>Project Team Cepat Tanggap (responsive) terhadap permintaan Customer dan permasalahan project.</td>' . $questionid5 . '</tr>';
    $msg .= '<tr><td>Penanganan Insiden: Insiden dalam project dapat diselesaikan dengan cepat dan tepat waktu sesuai Komitmen Layanan yang disepakati (Service Level Agreement).</td>' . $questionid8 . '</tr>';
    $msg .= '<tr><td>Permintaan Perubahan (Change Request): Atas Permintaan Perubahan yang disetujui, team proyek memberikan dukungan dan layanan yang baik, sesuai dengan harapan Customer.</td>' . $questionid11 . '</tr>';
    $msg .= '<tr><td>Dokumentasi project disampaikan kepada Customer dengan lengkap dan tepat waktu. </td>' . $questionid14 . '</tr>';
    $msg .= '<tr><td>Customer secara umum puas dengan Hasil Kerja Team Project.</td>' . $questionid17 . '</tr>';
    $msg .= '<tr><td>Pekerjaan team project sudah sesuai dengan Persyaratan (requirement) yang dinyatakan dalam dokumen RFP/RKS.</td>' . $questionid20 . '</tr>';
    $msg .= '<tr><td>Solusi yang diberikan PT. Mastersystem Infotama memenuhi kebutuhan bisnis perusahaan.</td>' . $questionid23 . '</tr>';
    $msg .= '<tr><td>Nilai Average</td><td>' . $data['rating_average'] . '</td></tr>';
    $msg .= '<tr><td>Menurut Anda, siapa anggota Project Team yang memiliki Kinerja Baik? (Boleh mengisi lebih dari 1 nama team project)</td><td>' . $tarikengineer . '</td></tr>';
    $msg .= '<tr><td>Silahkan masukan saran perbaikan Anda untuk meningkatkan kinerja Team Project.</td>' . $essay . '</tr>';
    $msg .= '</table>';
    echo $msg;
    // $to = 'malik.aulia@mastersystem.co.id';
    $to = 'henny.anggra@mastersystem.co.id, fortuna@mastersystem.co.id, Iwan@mastersystem.co.id, andino.hf@mastersystem.co.id, cassendra@mastersystem.co.id, fortuna@mastersystem.co.id, lucky.andiani@mastersystem.co.id';
    $from = 'MSIZone<msizone@mastersystem.co.id>';
    $cc = '';
    $bcc = '';
    $reply = $from;
    $subject = 'Notifikasi Hasil Survey Dibawah 7';
    $headers = "From: " . $from . "\r\n" .
        "Reply-To: " . $reply . "\r\n" .
        "Cc: " . $cc . "\r\n" .
        "Bcc: " . $bcc . "\r\n" .
        "MIME-Version: 1.0" . "\r\n" .
        "Content-Type: text/html; charset=UTF-8" . "\r\n" .
        "X-Mailer: PHP/" . phpversion();


    $ALERT = new Alert();
    if (mail($to, $subject, $msg, $headers)) {
        echo "Email terkirim pada jam " . date("d M Y G:i:s");
        // $upsurvey = $DB1->get_res("UPDATE sa_trx_survey set flag = 1 where answer_id = '" . $data['answer_id'] . "'");
    } else {
        echo "Email gagal terkirim pada jam " . date("d M Y G:i:s");
    }
}
$time_end = microtime(true);
$time = $time_end - $time_start;
echo "<br/>==========";
echo "Finished : " . date("d-M-Y G:i:s");
echo "The time used to run this module $time seconds";
echo "==========";
// $DBCRON->ending($descErr);
