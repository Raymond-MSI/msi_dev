<?php
// IMAP server details

// $_SESSION{
//     'Microservices_UserEmail'} = 'syamsul@mastersystem.co.id';
echo "==========";
echo "Execution module : Alert email 2024";
echo "Started : " . date("d-M-Y G:i:s");
echo "==========<br/>";
$time_start = microtime(true);


// include_once("components/classes/func_crontab.php");
// $descErr = "Completed";
// $DBCRON = get_conn("CRONTAB");
// $DBCRON->beginning();


global $DBHCM;
$mdlname = "HCM";
$DBHCM = get_conn($mdlname);


$query = 'SELECT * FROM sa_hcm_requirement';
$result = $DBHCM->get_sqlV2($query);

while ($row = mysqli_fetch_assoc($result[1])) {
    $date = $row['request_date'];
    $dateParts = explode(' ', $date);
    // Get today's date
    $today = date('Y-m-d');

    // Get the date one, two, and three days ago
    $oneDayAgo = date('Y-m-d', strtotime("-1 days"));
    $twoDaysAgo = date('Y-m-d', strtotime("-2 days"));
    $threeDaysAgo = date('Y-m-d', strtotime("-3 days"));


    // Check if the date matches any of the conditions
    if ($dateParts[0] == $oneDayAgo) {
        echo "The date is one day ago: " . $dateParts[0];
        // $linkdissaprove = 'mailto:repoadmin@mastersystem.co.id?subject=[TESTING] Request Recruitment-' . urlencode($idfpkb) . '&body=[Dissaprove] - gm%0D%0A[notes] - gm';
        // $linkapprove = 'mailto:repoadmin@mastersystem.co.id?subject=[TESTING] Request Recruitment-' . urlencode($idfpkb) . '&body=[Approve] - gm%0D%0A[notes] - gm';
        // $to = $_POST['gm'];
        $to = 'malik.aulia@mastersystem.co.id';
        $from = "MSIZone<msizone@mastersystem.co.id>";
        $cc = "malik.aulia@mastersystem.co.id";
        $bcc = "";
        $reply = $from;
        $subject = "[TESTING] Request Recruitment Alert";
        $msg = '<table width="100%">';
        $msg .= '    <tr><td width="20%" rowspan="4"></td>';
        $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
        $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
        $msg .= '    <td width="20%" rowspan="4"></tr>';
        $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
        $msg .= '        <p>Dear, ' . $to . '</p>';
        $msg .= '        <p>Berikut ini adalah daftar Request Karyawan yang menunggu approval dari Anda.</p>';
        $msg .= '        <p>';
        $msg .= '<table width="80%">';
        $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $row['id_fpkb'] . '</td></tr>';
        $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $row['divisi'] . '</td></tr>';
        $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $row['posisi'] . '</td></tr>';
        $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $row['project_code'] . '</td></tr>';
        $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $row['status_rekrutmen'] . '</td></tr>';
        $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $row['status_karyawan'] . '</td></tr>';
        $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $row['kandidat'] . '</td></tr>';
        $msg .= '<tr><td>Status</td><td>:</td><td>' . "Pending Approval" . '</td></tr>';
        $msg .= '</table>';
        $msg .= '</p>';
        $msg .= '<p>Request ini belum mendapatkan Approval. Mohon dibantu untuk Approvalnya.</p>';
        $msg .= '        <table>';
        $msg .= '        <tr>';
        // $msg .= '        <p>Jika Approve silahkan klik link berikut <a href="' . $linkapprove . '">Approve</a> jika Tidak maka klik link berikut <a href="' . $linkdissaprove . '">Disapprove</a></p>';
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
    } elseif ($dateParts[0] == $twoDaysAgo) {
        echo "The date is two days ago: " . $dateParts[0];
        $to = 'malik.aulia@mastersystem.co.id';
        $from = "MSIZone<msizone@mastersystem.co.id>";
        $cc = "malik.aulia@mastersystem.co.id";
        $bcc = "";
        $reply = $from;
        $subject = "[TESTING] Request Recruitment Alert";
        $msg = '<table width="100%">';
        $msg .= '    <tr><td width="20%" rowspan="4"></td>';
        $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
        $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
        $msg .= '    <td width="20%" rowspan="4"></tr>';
        $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
        $msg .= '        <p>Dear, ' . $to . '</p>';
        $msg .= '        <p>Berikut ini adalah daftar Request Karyawan yang menunggu approval dari Anda.</p>';
        $msg .= '        <p>';
        $msg .= '<table width="80%">';
        $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $row['id_fpkb'] . '</td></tr>';
        $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $row['divisi'] . '</td></tr>';
        $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $row['posisi'] . '</td></tr>';
        $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $row['project_code'] . '</td></tr>';
        $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $row['status_rekrutmen'] . '</td></tr>';
        $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $row['status_karyawan'] . '</td></tr>';
        $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $row['kandidat'] . '</td></tr>';
        $msg .= '<tr><td>Status</td><td>:</td><td>' . "Pending Approval" . '</td></tr>';
        $msg .= '</table>';
        $msg .= '</p>';
        $msg .= '<p>Request ini belum mendapatkan Approval. Mohon dibantu untuk Approvalnya</p>';
        $msg .= '        <table>';
        $msg .= '        <tr>';
        // $msg .= '        <p>Jika Approve silahkan klik link berikut <a href="' . $linkapprove . '">Approve</a> jika Tidak maka klik link berikut <a href="' . $linkdissaprove . '">Disapprove</a></p>';
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
    } elseif ($dateParts[0] == $threeDaysAgo) {
        echo "The date is three days ago: " . $dateParts[0];
        $to = 'malik.aulia@mastersystem.co.id';
        $from = "MSIZone<msizone@mastersystem.co.id>";
        $cc = "malik.aulia@mastersystem.co.id";
        $cc = "recruitment.team@mastersystem.co.id";
        $bcc = "";
        $reply = $from;
        $subject = "[TESTING] Request Recruitment Alert";
        $msg = '<table width="100%">';
        $msg .= '    <tr><td width="20%" rowspan="4"></td>';
        $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
        $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
        $msg .= '    <td width="20%" rowspan="4"></tr>';
        $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
        $msg .= '        <p>Dear, ' . $to . '</p>';
        $msg .= '        <p>Berikut ini adalah daftar Request Karyawan yang menunggu approval dari Anda.</p>';
        $msg .= '        <p>';
        $msg .= '<table width="80%">';
        $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $row['id_fpkb'] . '</td></tr>';
        $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $row['divisi'] . '</td></tr>';
        $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $row['posisi'] . '</td></tr>';
        $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $row['project_code'] . '</td></tr>';
        $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $row['status_rekrutmen'] . '</td></tr>';
        $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $row['status_karyawan'] . '</td></tr>';
        $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $row['kandidat'] . '</td></tr>';
        $msg .= '<tr><td>Status</td><td>:</td><td>' . "Pending Approval" . '</td></tr>';
        $msg .= '</table>';
        $msg .= '</p>';
        $msg .= '<p>Request ini belum mendapatkan Approval. Mohon dibantu untuk Approvalnya.</p>';
        $msg .= '        <table>';
        $msg .= '        <tr>';
        // $msg .= '        <p>Jika Approve silahkan klik link berikut <a href="' . $linkapprove . '">Approve</a> jika Tidak maka klik link berikut <a href="' . $linkdissaprove . '">Disapprove</a></p>';
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
    }
}
