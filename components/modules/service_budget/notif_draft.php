<?php
date_default_timezone_set( "Asia/Jakarta" );

$hostname = "10.20.50.161";
$username = "ITAdmin";
$password = "P@ssw0rd.1";

// $hostname = "localhost";
// $username = "root";
// $password = "";

$dbname = "sa_md_hcm";
$DBHCM = new mysqli($hostname, $username, $password, $dbname);
$mysql = "SELECT employee_name, employee_email FROM sa_view_employees WHERE organization_name='Solution Engineering'";
$res = mysqli_query( $DBHCM, $mysql ) or die($DBHCM->errno . "-" . "[open_db][mysqli_query][" . $mysql . "] - " . $DBHCM->error . "<br/>");
$row = mysqli_fetch_assoc( $res );


$dbname = 'sa_ps_service_budgets';
$DBSB = new mysqli($hostname, $username, $password, $dbname);
$mysql = "SELECT project_code, so_number, customer_name, po_number, project_name, create_by FROM sa_trx_project_list WHERE `status`='draft' OR `status`='rejected' OR `status`='reopen' ORDER BY create_by";
$res = mysqli_query( $DBSB, $mysql ) or die($DBSB->errno . "-" . "[open_db][mysqli_query][" . $mysql . "] - " . $DBSB->error . "<br/>");
$row = mysqli_fetch_assoc( $res );


$lastemail ="";
$to = "";
do {
    $dbname = "sa_md_hcm";
    $DBHCM = new mysqli($hostname, $username, $password, $dbname);
    $mysql = "SELECT employee_name, employee_email, leader_name, leader_email FROM sa_view_employees WHERE employee_email='" . $row['create_by'] . "'";
    $resHCM = mysqli_query( $DBHCM, $mysql ) or die($DBHCM->errno . "-" . "[open_db][mysqli_query][" . $mysql . "] - " . $DBHCM->error . "<br/>");
    $rowHCM = mysqli_fetch_assoc( $resHCM );
    $to = $rowHCM["employee_name"] . "<" . $rowHCM["employee_email"] . ">, ";

    $msg='<table width="100%">';
    $msg.='    <tr><td width="20%" rowspan="4"></td>';
    $msg.='    <td style="width:60%; padding:20px; border:thin solid #dadada">';
    $msg.='        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
    $msg.='    <td width="20%" rowspan="4"></tr>';
    $msg.='    <tr><td style="padding:20px; border:thin solid #dadada">';
    $msg.='        <p>Dear ' . $rowHCM['employee_name'] . ',</p>';
    $msg.='        <p>Berikut ini adalah Service Budget Anda yang masih dalam bentuk draft.</p>';
    $msg.='        <p>';
    $msg.='        <table width="100%">';
    $msg.='            <tr><td style="" style="vertical-align:top; padding:5px">Project Code</td><td style="vertical-align:top; padding:5px">: ' . $row["project_code"] . '</td></tr>';
    $msg.='            <tr><td style="" style="vertical-align:top; padding:5px">SO Number</td><td style="vertical-align:top; padding:5px">: ' . $row["so_number"] . '</td></tr>';
    $msg.='            <tr><td style="" style="vertical-align:top; padding:5px">Customer Name</td><td style="vertical-align:top; padding:5px">: ' . $row["customer_name"] . '</td></tr>';
    $msg.='            <tr><td style="" style="vertical-align:top; padding:5px">PO Number</td><td style="vertical-align:top; padding:5px">: ' . $row["po_number"] . '</td></tr>';
    $msg.='            <tr><td style="" style="vertical-align:top; padding:5px">Project Name</td><td style="vertical-align:top; padding:5px">: ' . $row["project_name"] . '</td></tr>';
    $msg.='            <tr><td style="" style="vertical-align:top; padding:5px">Created by</td><td style="vertical-align:top; padding:5px">: ' . $rowHCM["employee_name"] . '</td></tr>';
    $msg.='        </table>';
    $msg.='        </p>';
    $msg.='        <p>Silahkan diselesaikan untuk bisa diajukan approval.</p>';
    $msg.='        <p>Terimakasih,</p>';
    $msg.='    </td>';
    $msg.='    <tr><td style="padding:20px; border:thin solid #dadada"><table width="100%"><tr><td><a href="https://msizone.mastersystem.co.id">MSIZone</a></td><td style="text-align:right"><a href="https://msizone.mastersystem.co.id/msiguide/">MSIGuide</a></td></tr></table></td></tr>';
    $msg.='    <tr><td style="font-size:10px; padding-left:20px;border: thin solid #dadada">Dikirim secara otomatis oleh sistem MSIZone.<br/>Jangan mereply email ini.</td></tr>';
    $msg.='</table>';

    $subject ="[MSIZone] Service Budget Menunggu Submit Dari Anda.";
    $from ="MSIZone<msizone@mastersystem.co.id>";
    //$cc ="Syamsul Arham<syamsul@mastersystem.co.id>, Fortuna Arumsari<fortuna@mastersystem.co.id>, Lucky Andiani<lucky.adiani@mastersystem.co.id>, Raymon Citra<raymon@mastersystem.co.id>, Lintar Wardana<lintar@mastersystem.co.id>";
    //$cc = "Syamsul Arham<syamsul@mastersystem.co.id>";
    $cc = "";
    $bcc = "";

    $headers = "From: " . $from . "\r\n" .
        "Cc: " . $cc . "\r\n" .
        "Bcc: " . $bcc . "\r\n" .
        "MIME-Version: 1.0" . "\r\n" .
        "Content-Type: text/html; charset=UTF-8" . "\r\n" .
        "X-Mailer: PHP/" . phpversion();
        
    if(mail($to, $subject, $msg, $headers)) {
        echo "Email terkirim pada jam " . date("d M Y G:i:s");
    } else {
    echo "Email gagal terkirim pada jam " . date("d M Y G:i:s");
    }

} while($row=$res->fetch_assoc());

?>
