<?php
$hostname = "10.20.50.161";
$username = "ITAdmin";
$password = "P@ssw0rd.1";
$database = "sa_md_hcm";

$DBWB = new Databases($hostname, $username, $password, $database);

$tblname = "sa_view_employees";

 $mysql = "SELECT `employee_name`, `nik`, `employee_email`, `organization_name`, `job_title`, `join_date`, MAX(`leader_name`) AS `leader_name`, MAX(`unitdrawing`) AS `unitdrawing` FROM `sa_view_employees` WHERE isnull(`resign_date`) GROUP BY `employee_name`, `nik`, `employee_email`, `organization_name`, `job_title`, `join_date` ORDER BY join_date DESC LIMIT 0,1";

$employees = $DBWB->get_sql($mysql);

if($employees[2]>0) {

do {

$msg= '<table style="width:100%;">';
$msg.='  <tr><td style="width:20%;" rowspan="3"></td>';
$msg.='          <td style="width:60%;background-color: #D90000; text-align: center">';
$msg.='                 <img src="https://msizone.mastersystem.co.id/media/images/welcome/WelcomeOnBoard0.fw.png">';
// Welcome banner
//$msg.='<span style="font-size:24px; font-weight: bold; padding: 20px">Welcome Onboard</span>';
$msg.='          </td>';
$msg.='          <td style="width:20%" rowspan="3"></td>';
$msg.='  </tr>';

// Welcome Onboard
$msg.='<tr><td style="background:#eaeaea; padding: 20px;">';
$msg.='   <p>Dear ' . $employees[0]['employee_name'] . ',</p>';
$msg.='   <p>Selamat bergabung dengan Mastersystem Infotama. Anda merupakan orang yang terpilih untuk menjadi bagian dari Mastersystem Infotama.</p>';
//$msg.='</td></tr>';

//Todo greating
//$msg.='<tr><td style="background:#eaeaea; padding: 20px;">';
$msg.='   <p>Pada halaman ini terdapat to-do-list yang dapat Anda siapkan dan lakukan untuk membuat proses mengenal Mastersystem lebih cepat. Let’s get started!</p>';
//$msg.='</td></tr>';

$todo=6;

for($i=1;$i<$todo;$i++) {
// Todo #1
//$msg.='<tr><td style="background:#eaeaea; padding: 20px;">';
$msg.='   <table width="100%"><tr>';
$msg.='   <td style="width:60%;"><a href="https://www.webex.com/downloads.html"><img src="https://msizone.mastersystem.co.id/media/images/welcome/WelcomeOnBoard' . $i . '.fw.png"></a></td>';
$msg.='   </tr></table>';
//$msg.='</td></tr>';
}

$msg.='    <tr><td style="font-size:10px; padding-left:20px; background-color: #eaeaea">Dikirim secara otomatis oleh sistem MSIZone.<br/>Jangan mereply email ini.</td></tr>';
$msg.='</table>';

echo $msg;

$to = "Syamsul Arham<syamsul@mastersystem.co.id>";
$from = "MSIZone<msizone@mastersystem.co.id>";
$subject = "Mastersystem Infotama | Welcome Board";

        $headers = "From: " . $from . "\r\n" .
            "MIME-Version: 1.0" . "\r\n" .
            "Content-Type: text/html; charset=UTF-8" . "\r\n" .
            "X-Mailer: PHP/" . phpversion();
            
        if(mail($to, $subject, $msg, $headers)) {
            echo "Email terkirim pada jam " . date("d M Y G:i:s");
        } else {
            echo "Email gagal terkirim pada jam " . date("d M Y G:i:s");
        }

} while($employees[0]=$employees[1]->fetch_assoc());

}

?>