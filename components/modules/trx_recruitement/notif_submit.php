<?php
$_SESSION['Microservices_UserEmail'] = 'malik.aulia@mastersystem.co.id';
echo "==========";
echo "Execution module : trx_request_requirement";
echo "Started : " . date("d-M-Y G:i:s");
echo "==========<br/>";
$time_start = microtime(true);


// include_once("components/classes/func_crontab.php");
// $descErr = "Completed";
// $DBCRON = get_conn("CRONTAB");
// $DBCRON->beginning();
global $DBREQ;

// status_request approval auto kirim email dan berubah menjadi submitted
// $hostname = "localhost";
// $username = "root";
// $password = "";
// $dbname = "sa_new";

// $DBREQ = new Databases($hostname, $username, $password, $dbname);
$mdlname = "trx_recruitement";
$DBREC = get_conn($mdlname);

$query = $DBREC->get_sqlV2("SELECT * from sa_trx_recruitement WHERE status_request = 'Active' AND status_gm = 'Approve' AND status_gm_hcm = 'Approve'");
while ($ambildata = mysqli_fetch_array($query[1])) {
    $divisi = $ambildata['divisi'];
    $posisi = $ambildata['posisi'];
    $jumlah = $ambildata['jumlah_dibutuhkan'];
    $tgl = $ambildata['tanggal_dibutuhkan'];
    $deskripsi = $ambildata['deskripsi'];
    $nama_project = $ambildata['nama_project'];
    $nama_customer = $ambildata['nama_customer'];
    $kode_project = $ambildata['kode_project'];
    $status_rekrutmen = $ambildata['status_rekrutmen'];
    $status_karyawan = $ambildata['status_karyawan'];
    $gm = $ambildata['gm'];
    $status_gm = $ambildata['status_gm'];
    $gmhcm = $ambildata['gm_hcm'];
    $status_gm_hcm = $ambildata['status_gm_hcm'];



    $to = $gmhcm;
    $from = "MSIZone<msizone@mastersystem.co.id>";
    $cc = $from;
    $bcc = "";
    $reply = $from;
    $subject = "[TESTING] Request Recruitment";
    $msg = '<table width="100%">';
    $msg .= '    <tr><td width="20%" rowspan="4"></td>';
    $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
    $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
    $msg .= '    <td width="20%" rowspan="4"></tr>';
    $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
    $msg .= '        <p>Dear ,' . $to . '</p>';
    $msg .= '        <p>Berikut ini adalah daftar Request Karyawan yang telah di Approve.</p>';
    $msg .= '        <p>';
    $msg .= '        <table width="100%">';
    $msg .= '            <tr>';
    $msg .= '                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold">Divisi</td>';
    $msg .= '                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold">Posisi</td>';
    $msg .= '                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold">Jumlah</td>';
    $msg .= '                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold">Kode Project</td>';
    $msg .= '                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold">Status Rekrutmen</td>';
    $msg .= '                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold">Status Karyawan</td>';
    $msg .= '                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold">GM</td>';
    $msg .= '                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold">Status GM</td>';
    $msg .= '                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold">GM HCM</td>';
    $msg .= '                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold">Status GM HCM</td>';
    $msg .= '            </tr>';
    $msg .= '                <tr>';
    $msg .= '                <td style="vertical-align:top; text-align:center; padding:5px">' . $divisi . '</td>';
    $msg .= '                <td style="vertical-align:top; text-align:center; padding:5px">' . $posisi . '</td>';
    $msg .= '                <td style="vertical-align:top; text-align:center; padding:5px">' . $jumlah . '</td>';
    $msg .= '                <td style="vertical-align:top; text-align:center; padding:5px">' . $kode_project . '</td>';
    $msg .= '                <td style="vertical-align:top; text-align:center; padding:5px">' . $status_rekrutmen . '</td>';
    $msg .= '                <td style="vertical-align:top; text-align:center; padding:5px">' . $status_karyawan . '</td>';
    $msg .= '                <td style="vertical-align:top; text-align:center; padding:5px">' . $gm . '</td>';
    $msg .= '                <td style="vertical-align:top; text-align:center; padding:5px">' . $status_gm . '</td>';
    $msg .= '                <td style="vertical-align:top; text-align:center; padding:5px">' . $gmhcm . '</td>';
    $msg .= '                <td style="vertical-align:top; text-align:center; padding:5px">' . $status_gm_hcm . '</td>';
    $msg .= '            </tr>';
    $msg .= '        </table>';
    $msg .= '        </p>';
    $msg .= '        <p>Silahkan untuk melanjutkan step berikutnya.</p>';
    $msg .= '        <table>';
    $msg .= '        <tr>';
    $msg .= '        </table>';
    $msg .= '        <p>Terimakasih</p>';
    $msg .= '    </td>';
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
    if (mail($to, $subject, $msg, $headers)) {
        echo "Email terkirim pada jam " . date("d M Y G:i:s");
    } else {
        echo "Email gagal terkirim pada jam " . date("d M Y G:i:s");
    }


    // update status request 
    $id_request = $ambildata['id'];
    $update_request = sprintf("status_request = 'Submitted' ");
    $condition_request = "id =" .  $id_request;

    // $insert = sprintf(
    //     "(`id_request`, `divisi`, `posisi`,`kode_project`, `send_datetime`, `jumlah_dibutuhkan`, `status_requirement`, `requirements`, `status_approval`) 
    // VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s)",
    //     GetSQLValueString($ambildata['id'], "int"),
    //     GetSQLValueString($ambildata['divisi'], "text"),
    //     GetSQLValueString($ambildata['posisi'], "text"),
    //     GetSQLValueString($ambildata['kode_project'], "text"),
    //     GetSQLValueString(date("Y-m-d G:i:s"), "date"),
    //     GetSQLValueString($ambildata['jumlah_dibutuhkan'], "text"),
    //     GetSQLValueString($ambildata['status_karyawan'], "text"),
    //     GetSQLValueString($ambildata['kompetensi_teknis'], "text"),
    //     GetSQLValueString("Approved", "text")
    // );
    // $res = $DBREQ->insert_data('trx_approval', $insert);
    // var_dump($res);

    $uprequest = $DBREC->update_data("trx_recruitement", $update_request, $condition_request);
}
$time_end = microtime(true);
$time = $time_end - $time_start;
echo "<br/>==========";
echo "Finished : " . date("d-M-Y G:i:s");
echo "The time used to run this module $time seconds";
echo "==========";
// $DBCRON->ending($descErr);
