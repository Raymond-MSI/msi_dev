<?php

// require "connection.php";
// $conn = mysqli_connect("localhost", "root", "", "sa_md_hcm");   
$mdlname = "HCM";
$DBHCM = get_conn($mdlname);
$query = $DBHCM->get_sqlV2("SELECT * FROM sa_trx_maskomen WHERE artikel_submit<artikel_wajib");
while ($view = mysqli_fetch_array($query[1])) {
    // $owner=get_leader(isset($_SESSION['Microservices_UserEmail']));
    // if(isset($owner)){
    //     $downer = $owner[0];
    //     $from = $downer['employee_name'] . "<" . $downer['employee_email'] . ">; ";
    // } else {
    //     $downer = null;
    //     $from = null;
    // }
    $from = "MSIZone<msizone@mastersystem.co.id>";
    $to_name = str_replace(";", ",", $view['email']);
    $email = $view['email'];
    $employee_name = $view['employee_name'];
    $toname = $employee_name . "(" . $email . ")";
    $entryby = $view['entry_by'];
    $artikelwajib = $view['artikel_wajib'];
    $artikelsubmit = $view['artikel_submit'];
    $artikelsisa = ($artikelwajib - $artikelsubmit);
    $cc = $from;
    $bcc = "";
    $reply = $from;
    $subject = "masih kosong";
    $msg = "<table width='100%'>";
    $msg .= "<tr><td rowspan='5'></td><td>";
    $msg .= "<img src='https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png'>";
    $msg .= "</td></tr>";
    $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'>";
    $msg .= "<br/>";
    $msg .= "<p>Dear " . $toname . "</p>";
    $msg .= "<table style='width:100%;'>";
    $msg .= "<tr><td></td></tr>";
    $msg .= "<tr><td>Ayo Kumpulkan artikelmu di Maskomen!</td></tr>";
    $msg .= "<td>Dengan adanya pengangkatan Level Bapak/Ibu, maka Bapak/Ibu wajib mengumpulkan artikel di</td>";
    $msg .= "<tr><td>Maskomen dengan ketentuan sebagai berikut:</td></tr>";
    $msg .= "<tr><td></td></tr>";
    $msg .= "<tr><td></td></tr>";
    $msg .= "<tr><td></td></tr>";
    $msg .= "<table>";
    $msg .= "<tr>";
    $msg .= "<td>Keterangan</td>";
    $msg .= "<td></td>";
    $msg .= "<td></td>";
    $msg .= "<td></td>";
    $msg .= "</tr>";
    $msg .= "<tr>";
    $msg .= "<td>Artikel wajib</td>";
    $msg .= "<td>=</td>";
    $msg .= "<td align='right'>" . $artikelwajib . "</td>";
    $msg .= "<td>artikel</td>";
    $msg .= "</tr>";
    $msg .= "<tr>";
    $msg .= "<td>Artikel submit</td>";
    $msg .= "<td>=</td>";
    $msg .= "<td align='right'>" . $artikelsubmit . "</td>";
    $msg .= "<td>artikel</td>";
    $msg .= "</tr>";
    $msg .= "<tr>";
    $msg .= "<td>Artikel sisa</td>";
    $msg .= "<td>=</td>";
    $msg .= "<td align='right'>" . $artikelsisa . "</td>";
    $msg .= "<td>artikel</td>";
    $msg .= "</tr>";
    $msg .= "<tr><td></td></tr>";
    $msg .= "<tr><td></td></tr>";
    $msg .= "</table>";
    $msg .= "<table>";
    $msg .= "<tr><td>Batas Maksimal pengumpulan artikel adalah 1 tahun dari tanggal efektif pengangkatan level</td></tr>";
    $msg .= "<tr><td>Bapak/ibu. </td></tr>";
    $msg .= "<tr><td></td></tr>";
    $msg .= "</table>";
    $msg .= "<p>Kumpulkan artikelmu dan raih poinmu di Maskomen<br/>";
    $msg .= $entryby . "</p>";
    $msg .= "<td width='30%' rowspan='5'>";
    $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
    $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
    $msg .= "</table>";
    echo $msg;
    $headers = "From: " . $from . "\r\n" .
        "Reply-To: " . $reply . "\r\n" .
        "Cc: " . $cc . "\r\n" .
        "Bcc: " . $bcc . "\r\n" .
        "MIME-Version: 1.0" . "\r\n" .
        "Content-Type: text/html; charset=UTF-8" . "\r\n" .
        "X-Mailer: PHP/" . phpversion();

    $ALERT = new Alert();
    if (!mail($to_name, $subject, $msg, $headers)) {
        echo $ALERT->email_not_send();
    } else {
        echo $ALERT->email_send();
    }
}
