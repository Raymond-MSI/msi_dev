<?php
// IMAP server details

// $_SESSION{
//     'Microservices_UserEmail'} = 'syamsul@mastersystem.co.id';
// echo "==========";
// echo "Execution module : Alert email 2024";
// echo "Started : " . date("d-M-Y G:i:s");
// echo "==========<br/>";
// $time_start = microtime(true);


// include_once("components/classes/func_crontab.php");
// $descErr = "Completed";
// $DBCRON = get_conn("CRONTAB");
// $DBCRON->beginning();


global $DBHCM;
$mdlname = "HCM";
$DBHCM = get_conn($mdlname);


// $query = 'SELECT * 
// FROM sa_hcm_requirement a 
// INNER JOIN sa_hcm_notecv b ON a.id = b.id_request;
// ';

$query = 'SELECT a.*, b.*
FROM sa_hcm_notecv a
INNER JOIN sa_hcm_requirement b ON a.id_request = b.id
WHERE a.status_cv IS NULL
GROUP BY a.file
ORDER BY a.id_note DESC;
;
';

$result = $DBHCM->get_sqlV2($query);

$email_data = [];

// Kelompokkan data berdasarkan id_request
while ($row = mysqli_fetch_assoc($result[1])) {
    $id_request = $row['id_request']; // Mengambil id_request

    // Menambahkan data file ke array berdasarkan id_request
    if (!isset($email_data[$id_request])) {
        $email_data[$id_request] = []; // Jika id_request belum ada, buat array kosong
    }

    // Tambahkan baris data ke array
    $email_data[$id_request][] = $row;
}

// Kirimkan email untuk setiap id_request
foreach ($email_data as $id_request => $rows) {
    // Mendapatkan data pertama untuk subjek email dan penerima
    $first_row = $rows[0];
    $idfpkb = $first_row['id_fpkb'];
    $posisi = $first_row['posisi'];
    // $to = $first_row['request_by'];
    $to = "malik.aulia@mastersystem.co.id";
    $from = "MSIZone<msizone@mastersystem.co.id>";
    // $cc = "recruitment.team@mastersystem.co.id";
    $cc = "malik.aulia@mastersystem.co.id";
    $bcc = "";
    $reply = $from;
    $subject = "[CV Kandidat] $idfpkb - $posisi";

    // Inisialisasi konten pesan email
    $msg = '<table width="100%">';
    $msg .= '    <tr><td width="20%" rowspan="4"></td>';
    $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
    $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
    $msg .= '    <td width="20%" rowspan="4"></tr>';
    $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
    $msg .= '        <p>Dear ' . $to . '</p>';
    $msg .= '        <p>';
    $msg .= '<table width="80%">';
    $msg .= '<tr><td>ID FKPB</td><td>:</td><td>' . $first_row['id_fpkb'] . '</td></tr>';
    $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $first_row['divisi'] . '</td></tr>';
    $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $first_row['posisi'] . '</td></tr>';
    $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $first_row['project_code'] . '</td></tr>';
    $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $first_row['kandidat'] . '</td></tr>';
    $msg .= '</table>';
    $msg .= '</p>';
    $msg .= '<p>Berikut adalah CV Kandidat. Mohon Reviewnya</p>';
    $msg .= '<table border="1">';
    $msg .= '<tr>';
    $msg .= '<th>ID</th>';
    $msg .= '<th>Nama File</th>';
    $msg .= '<th>Status CV</th>';
    $msg .= '</tr>';

    // Proses semua file yang berhubungan dengan id_request yang sama
    foreach ($rows as $row) {
        // Mendapatkan data file, id_note, dan file_id
        $files = explode(',', $row['file']);
        $id_notes = explode(',', $row['id_note']);
        $file_ids = explode(',', $row['id_filedrive']);

        foreach ($files as $index => $file_name) {
            $id_note = $id_notes[$index];
            $file_id = $file_ids[$index];
            $fileDriveLink = 'https://drive.google.com/file/d/' . $file_id . '/view';

            // Membuat link untuk status CV
            $Yes = 'mailto:repoadmin@mastersystem.co.id?subject=[TESTING] Status CV-' . $id_note . '&body=[yyyy-mm-dd hh:mm:ss] - Tanggal Interview%0D%0A[Yes] - status_cv';
            $No = 'mailto:repoadmin@mastersystem.co.id?subject=[TESTING] Status CV-' . $id_note . '&body=[0000-00-00] - Tanggal Interview%0D%0A[No] - status_cv';

            // Menambahkan setiap file ke dalam tabel email
            $msg .= '<tr>';
            $msg .= '<td>' . $id_note . '</td>';
            $msg .= '<td><a href="' . $fileDriveLink . '">' . $file_name . '</a></td>';
            $msg .= '<td><a href="' . $Yes . '">Yes</a> | <a href="' . $No . '">No</a></td>';
            $msg .= '</tr>';
        }
    }

    $msg .= '</table>';
    $msg .= '        <table>';
    $msg .= '        <tr>';
    $msg .= '        </table>';
    $msg .= '        <p>Terimakasih</p>';
    $msg .= '        <p>(Notes : Bisa pilih lebih dari 1 cv)</p>';
    $msg .= '    </td>';
    $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
    $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
    $msg .= "</table>";

    echo $msg;
    // var_dump($msg);
    // die;
    // Kirim email
    $headers = "From: " . $from . "\r\n" .
        "Reply-To: " . $reply . "\r\n" .
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
}
