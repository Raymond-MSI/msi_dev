<?php
global $DBCR;
global $DBHCM;

date_default_timezone_set("Asia/Jakarta");

$hostname = "10.20.50.161";
$username = "ITAdmin";
$password = "P@ssw0rd.1";

// $hostname = "localhost";
// $username = "root";
// $password = "";

// $mdlname = "HCM";
// $DBHCM = get_conn($mdlname);
// $dbname = "sa_md_hcm";
//$DBHCM = new mysqli($hostname, $username, $password, $dbname);
$mysql = "SELECT employee_name, employee_email FROM sa_view_employees WHERE ((job_structure LIKE '%JG%' AND job_title LIKE '%Manager%')) OR ((job_structure LIKE '%LWW%' AND job_title LIKE '%Manager%')) OR ((job_structure LIKE '%RBC%' AND job_title LIKE '%Manager%')) OR ((job_title LIKE '%Direktur%'))";
// $res = mysqli_query( $DBHCM, $mysql ) or die($DBHCM->errno . "-" . "[open_db][mysqli_query][" . $mysql . "] - " . $DBHCM->error . "<br/>");
// $row = mysqli_fetch_assoc( $res );
$dbhcm = $DBHCM->get_sql($mysql);
$res = $dbhcm[1];
$row = $dbhcm[0];

// $mdlname = "CHANGE_REQUEST";
// $DBCR = get_conn($mdlname);
// $dbname = 'sa_change_request';
// $DBSB = new mysqli($hostname, $username, $password, $dbname);
$mysql = "SELECT project_code, so_number, customer_name, po_number, project_name, requested_by_email FROM sa_general_informations WHERE change_request_approval_type='Pending' ORDER BY create_by";
// $res = mysqli_query( $DBSB, $mysql ) or die($DBSB->errno . "-" . "[open_db][mysqli_query][" . $mysql . "] - " . $DBSB->error . "<br/>");
// $row = mysqli_fetch_assoc( $res );
$dbcr = $DBCR->get_sql($mysql);
$res = $dbcr[1];
$row = $dbcr[0];

if ($dbcr[2] > 0) {
    $msg = '<table width="100%">';
    $msg .= '    <tr><td width="20%" rowspan="4"></td>';
    $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
    $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
    $msg .= '    <td width="20%" rowspan="4"></tr>';
    $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
    $msg .= '        <p>Dear All,</p>';
    $msg .= '        <p>Berikut ini adalah daftar Change Request yang menunggu approval dari Anda.</p>';
    $msg .= '        <p>';
    $msg .= '        <table width="100%">';
    $msg .= '            <tr>';
    $msg .= '                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold">Contract information</td>';
    $msg .= '                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold">Project Information</td>';
    $msg .= '            </tr>';
    $lastemail = "";
    $to = "";
    do {
        $dbname = "sa_md_hcm";
        // $DBHCM = new mysqli($hostname, $username, $password, $dbname);
        $mysql = "SELECT employee_name, employee_email, leader_name, leader_email FROM sa_view_employees WHERE employee_email='" . $row['requested_by_email'] . "'";
        // $resHCM = mysqli_query( $DBHCM, $mysql ) or die($DBHCM->errno . "-" . "[open_db][mysqli_query][" . $mysql . "] - " . $DBHCM->error . "<br/>");
        // $rowHCM = mysqli_fetch_assoc( $resHCM );
        $dbhcm = $DBHCM->get_sql($mysql);
        $resHCM = $dbhcm[1];
        $rowHMC = $dbhcm[0];
        if ($lastemail != $row["requested_by_email"]) {
            $to .= $rowHCM["leader_name"] . "<" . $rowHCM["leader_email"] . ">, ";
        }
        $lastemail = $row['requested_by_email'];
        $msg .= '                <tr>';
        $msg .= '                <td style="vertical-align:top; padding:5px">' . $row["customer_name"] . '<br/>KP : ' . $row["project_code"] . '<br/>SO : ' . $row["so_number"] . '<br/>PO : ' . $row["po_number"] . '</td>';
        $msg .= '                <td style="vertical-align:top; padding:5px">Project Name : ' . $row["project_name"] . "<br/>Created by : " . $rowHCM["employee_name"] . '</td>';
        $msg .= '            </tr>';
    } while ($row = $res->fetch_assoc());
    $msg .= '        </table>';
    $msg .= '        </p>';
    $msg .= '        <p>Silahkan direview untuk kelayakan mendapatkan approval.</p>';
    $msg .= '        <p>Terimakasih,</p>';
    $msg .= '    </td>';
    $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada"><table width="100%"><tr><td><a href="https://msizone.mastersystem.co.id">MSIZone</a></td><td style="text-align:right"><a href="https://msizone.mastersystem.co.id/msiguide/">MSIGuide</a></td></tr></table></td></tr>';
    $msg .= '    <tr><td style="font-size:10px; padding-left:20px;border: thin solid #dadada">Dikirim secara otomatis oleh sistem MSIZone.<br/>Jangan mereply email ini.</td></tr>';
    $msg .= '</table>';

    $subject = "[MSIZone] Change Request Menunggu Approval Anda.";
    $from = "MSIZone<msizone@mastersystem.co.id>";
    $cc = "Syamsul Arham<syamsul@mastersystem.co.id>, Fortuna Arumsari<fortuna@mastersystem.co.id>, Lucky Andiani<lucky.adiani@mastersystem.co.id>, Raymon Citra<raymon@mastersystem.co.id>, Lintar Wardana<lintar@mastersystem.co.id>";
    $bcc = "";
    $headers = "From: " . $from . "\r\n" .
        "Cc: " . $cc . "\r\n" .
        "Bcc: " . $bcc . "\r\n" .
        "MIME-Version: 1.0" . "\r\n" .
        "Content-Type: text/html; charset=UTF-8" . "\r\n" .
        "X-Mailer: PHP/" . phpversion();

    if (mail($to, $subject, $msg, $headers)) {
        echo "Email terkirim pada jam " . date("d M Y G:i:s");
    } else {
        echo "Email gagal terkirim pada jam " . date("d M Y G:i:s");
    }
} else {
    echo "Tidak ada data.";
}
