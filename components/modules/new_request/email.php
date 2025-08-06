<?php

require("microservices/msiguide/wp-includes/PHPMailer/SMTP.php");
require("microservices/msiguide/wp-includes/PHPMailer/PHPMailer.php");

use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;

$mail = new PHPMailer();

global $DBREC;
$mdlname = "new_request";
$DBREC = get_conn($mdlname);
$get = $DBREC->get_sqlV2("SELECT * FROM sa_email WHERE project_code IS NOT NULL");

$tgl_int = $get[0]['tanggal_interview'];
$days = array(
    'Sunday' => 'Minggu',
    'Monday' => 'Senin',
    'Tuesday' => 'Selasa',
    'Wednesday' => 'Rabu',
    'Thursday' => 'Kamis',
    'Friday' => 'Jumat',
    'Saturday' => 'Sabtu'
);

$cobatgl = str_replace(array_keys($days), array_values($days), date('l , d F Y', strtotime($tgl_int)));
$datemin2 = str_replace(array_keys($days), array_values($days), date('l , d F Y', strtotime($tgl_int . '- 2 days')));
$to = str_replace(";", ",", $get[0]['email']);
$link_webex = $get[0]['link_webex'];
$pic = $get[0]['link_webex'];
$from = "MSIZone<msizone@mastersystem.co.id>";
$cc = $from;
$bcc = "";
$reply = $from;
$subject = "[TESTING] Request Recruitment";
$msg = '<table width="100%">';
$msg .= '<tr>';
$msg .= '<td rowspan="5"></td>';
$msg .= '<td>';
$msg .= '<img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png" />';
$msg .= '</td>';
$msg .= '</tr>';
$msg .= '<tr>';
$msg .= '<td style="padding: 20px; border: thin solid #dadada">';
$msg .= '<br />';
$msg .= '<p>Dear, Kandidat</p>';
$msg .= '<table style="width: 100%">';
$msg .= '<tr>';
$msg .= '<td></td>';
$msg .= '</tr>';
$msg .= '<tr>';
$msg .= '<td>Terkait dengan CV yang kami terima, kami PT. Mastersystem Infotama bermaksud mengundang anda Interview sebagai Developer, </td>';
$msg .= '</tr>';
$msg .= '<td>berikut adalah link Cisco Webex. Jika menggunakan laptop bisa menggunakan browser atau jika memakai android/ios bisa download aplikasi cisco webex: ';
$msg .= '<p></p>';
$msg .= '</td>';
$msg .= '<tr>';
$msg .= '<td></td>';
$msg .= '</tr>';
$msg .= '<tr>';
$msg .= '<td></td>';
$msg .= '</tr>';
$msg .= '<tr>';
$msg .= '<td></td>';
$msg .= '</tr>';
$msg .= '<table>';
$msg .= '<tr>';
$msg .= '<td></td>';
$msg .= '<td></td>';
$msg .= '<td></td>';
$msg .= '</tr>';
$msg .= '<tr>';
$msg .= '<td>Hari / Tanggal</td>';
$msg .= '<td>=</td>';
$msg .= '<td align="left">' . $cobatgl . ' WIB</td>';
$msg .= '</tr>';
$msg .= '<tr>';
$msg .= '<td>Waktu</td>';
$msg .= '<td>=</td>';
$msg .= '<td align="left"> 10.30 WIB </td>';
$msg .= '</tr>';
$msg .= '<tr>';
$msg .= '<td>Link Webex</td>';
$msg .= '<td>=</td>';
$msg .= '<td align="left">' . $link_webex . '</td>';
$msg .= '</tr>';
$msg .= '<tr>';
$msg .= '<td>PIC</td>';
$msg .= '<td>=</td>';
$msg .= '<td align="left">' . $pic . '</td>';
$msg .= '</tr>';
$msg .= '<p></p>';
$msg .= '<tr>';
$msg .= '<td></td>';
$msg .= '</tr>';
$msg .= '<tr>';
$msg .= '<td></td>';
$msg .= '</tr>';
$msg .= '</table>';
$msg .= '<table>';
$msg .= '<tr>';
$msg .= '<td>Serta mohon mengisi dan mengirimkan kembali form terlampir serta CV terbaru, <b>paling lambat ' . $datemin2 . '';
$msg .= '</td>';
$msg .= '</tr>';
$msg .= '<p></p>';
$msg .= '<tr>';
$msg .= '<td></td>';
$msg .= '</tr>';
$msg .= '<tr>';
$msg .= '<td></td>';
$msg .= '</tr>';
$msg .= '<p></p>';
$msg .= '<tr>';
$msg .= '<td>Ditunggu konfirmasinya dengan membalas email ini, dikarenakan interview ini langsung dengan User.</td>';
$msg .= '</tr>';
$msg .= '<tr>';
$msg .= '<td></td>';
$msg .= '</tr>';
$msg .= '</table>';
$msg .= '<p>Demikian hal ini disampaikan, atas perhatiannya kami ucapkan terimakasih.</p>';
$msg .= '<p>Terimakasih</p>';
$msg .= '<td width="30%" rowspan="5">';
$msg .= '<tr>';
$msg .= '<td style="padding: 20px; border: thin solid #dadada">';
$msg .= '<table width="100%">';
$msg .= '<tr>';
$msg .= '<td>';
$msg .= '<a href="https://msizone.mastersystem.co.id">MSIZone</a>';
$msg .= '</td>';
$msg .= '<td style="text-align: right">';
$msg .= '<a href="https://msizone.mastersystem.co.id/msiguide/">MSIGuide</a>';
$msg .= '</td>';
$msg .= '</tr>';
$msg .= '</table>';
$msg .= '</td>';
$msg .= '</tr>';
$msg .= '<tr>';
$msg .= '<td style="font-size: 10px;padding-left: 20px;border: thin solid #dadada;">Dikirim secara otomatis oleh sistem MSIZone. </td>';
$msg .= '</tr>';
$msg .= '</td>';
$msg .= '</table>';
$msg .= '</td>';
$msg .= '</tr>';
$msg .= '</table>';
$msg .= '';
echo $msg;
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
} else {
    echo "Email gagal terkirim pada jam " . date("d M Y G:i:s");
}
