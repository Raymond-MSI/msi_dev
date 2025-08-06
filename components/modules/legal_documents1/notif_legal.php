<script src="../../../components/vendor/bootstrap-5.1.0/js/bootstrap.bundle.min.js"></script>
<link href="../../../components/vendor/bootstrap-5.1.0/css/bootstrap.min.css" rel="stylesheet" type="text/css">


<?php
date_default_timezone_set( "Asia/Jakarta" );

// Local
// $hostname = "localhost";
// $username = "root";
// $password = "";
// Development
// $hostname = "192.168.234.158";
// $username = "moodleuser";
// $password = "P@ssw0rd";
// Production
$hostname = "10.20.50.161";
$username = "ITAdmin";
$password = "P@ssw0rd.1";
$database = "sa_legal_documents";
$conn = new mysqli($hostname, $username, $password, $database);

$mysql = "SELECT * FROM sa_setup WHERE setup_key LIKE 'EMAIL_NOTIF_%' AND disabled=0";
mysqli_set_charset( $conn, 'utf8' );
$qemail = mysqli_query( $conn, $mysql ) or die($conn->errno . "-" . "[open_db][mysqli_query][" . $mysql . "] - " . $conn->error . "<br/>");
$demail = mysqli_fetch_assoc( $qemail );
$temail = mysqli_num_rows( $qemail ); 

do {
    $mysql = "SELECT * FROM sa_view_documents WHERE date_expired <=  '" . date("Y-m-d", strtotime("+90 day")) . "' AND category = '" . $demail['setup_value'] . "' ORDER BY date_expired ASC";
    mysqli_set_charset( $conn, 'utf8' );

    $qdoc = mysqli_query( $conn, $mysql ) or die($conn->errno . "-" . "[open_db][mysqli_query][" . $mysql . "] - " . $conn->error . "<br/>");
    $ddoc = mysqli_fetch_assoc( $qdoc );
    $tdoc = mysqli_num_rows( $qdoc ); 

    $i = 1;
    $msg = "<p>Dear all,</p>" . "\r\n";
    $msg .= "<p>Dengan ini diberitahukan bahwa dokumen " . $demail['setup_value'] . " berikut ini akan berakhir masa berlakunya 3 bulan kedepan. Silahkan untuk dilakukan perpanjangan agar datanya tetap valid.</p>";
    $msg .= "<table class='table table-striped'>";
    $msg .= "<thead>";
    $msg .= "<th>No.</th>";
    $msg .= "<th>Doc.Number</th>";
    $msg .= "<th>Doc.Title</th>";
    $msg .= "<th>Provider</th>";
    $msg .= "<th>Category</th>";
    $msg .= "<th>Released</th>";
    $msg .= "<th>Expired</th>";
    $msg .= "</thead>";
    if($tdoc>0) {
        do {
            $msg .= "<tr>";
            $msg .= "<td>" . $i . "</td>";
            $msg .= "<td>" . $ddoc['doc_number'] . "</td>";
            $msg .= "<td>" . $ddoc['doc_title'] . "</td>";
            $msg .= "<td>" . $ddoc['provider'] . "</td>";
            $msg .= "<td>" . $ddoc['category'] . "</td>";
            $msg .= "<td>" . date("d-M-Y", strtotime($ddoc['date_released'])) . "</td>";
            $msg .= "<td>" . date("d-M-Y", strtotime($ddoc['date_expired'])) . "</td>";
            $msg .= "</tr>";
            $i++;
        } while($ddoc=$qdoc->fetch_assoc());
        $msg .= "</table>";
        $msg .= "<p>Dikirim secara otomatis oleh sistem <a href='https://msizone.mastersystem.co.id'>MSIZone</a>.<br/>";
        $msg .= "Jangan membalas/reply email ini.</p>";

        $to = $demail['params'];
        $from="MSIZone<msizone@mastersystem.co.id>";
        $subject="[MSIZone] Dokumen " . $demail['setup_value'] . " akan berakhir sebelum ". date("d M Y", strtotime("+90 day"));

        // echo $subject . "\r\n" . $msg;
        
        $headers = "From: " . $from . "\r\n" .
            "MIME-Version: 1.0" . "\r\n" .
            "Content-Type: text/html; charset=UTF-8" . "\r\n" .
            "X-Mailer: PHP/" . phpversion();
            
        if(mail($to, $subject, $msg, $headers)) {
            echo "Email terkirim pada jam " . date("d M Y G:i:s");
        } else {
            echo "Email gagal terkirim pada jam " . date("d M Y G:i:s");
        }
        // echo "<p>" . $to . "<br/>" . $subject . "<br/>" . $msg . "</p>";
    }
} while($demail=$qemail->fetch_assoc());
?>

<script src="../../../components/vendor/bootstrap-5.1.0/js/bootstrap.min.js"></script>
<script src="../../../applications/templates/sb_admin2/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
