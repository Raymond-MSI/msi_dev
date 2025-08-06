<?php
if($_SERVER['SERVER_NAME']=="jobs.mastersystem.co.id")
{
    $from="Server Jobs<jobs@mastersystem.co.id>";
    echo "Dikirim dari jobs.mastersystem.co.id";
}
if($_SERVER['SERVER_NAME']=="msizone.mastersystem.co.id")
{
    $from="MSIZone<msizone@mastersystem.co.id>";
    echo "Dikirim dari msizone.mastersystem.co.id";
}
if($_SERVER['SERVER_NAME']=="survey.mastersystem.co.id")
{
    $from="Survey Customer<surveyw@mastersystem.co.id>";
    echo "Dikirim dari survey.mastersystem.co.id";
}
    $to="Syamsul Arham<syamsul@mastersystem.co.id>; Chrisheryanda Eka Saputra <chrisheryanda@mastersystem.co.id>; Aan Dafa Setiawan <aan.dafa@mastersystem.co.id>; Malik Aulia Wiratama <malik.aulia@mastersystem.co.id>; IT <it@mastersystem.co.id>";
    // $to="Syamsul Arham<syamsul@mastersystem.co.id>";
    $cc="";
    $bcc="";
    $reply=$from;
    $subject="Test kirim email dari server jobs";
    $msg="<p>Untuk melakukan test kirim email dari server jobs.mastersystem adalah:</p>";
    $msg.="<p>https://jobs.mastersystem.co.id/test_mail.php</p>";
    $msg="<p>Untuk melakukan test kirim email dari server msizone.mastersystem adalah:</p>";
    $msg.="<p>https://msizone.mastersystem.co.id/test_mail.php</p>";
    $headers = "From: " . $from . "\r\n" .
        "Reply-To: " . $reply . "\r\n" .
        "Cc: " . $cc . "\r\n" .
        "Bcc: " . $bcc . "\r\n" .
        "MIME-Version: 1.0" . "\r\n" .
        "Content-Type: text/html; charset=UTF-8" . "\r\n" .
        "X-Mailer: PHP/" . phpversion();
        
    if(!mail($to, $subject, $msg, $headers))
    {
        echo "<p>Email tidak bisa dikirim.</p>";
    } else
    {
        echo "<p>Email terkirim dengan sukses.</p>";
    }
