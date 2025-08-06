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



$hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
$username = 'repoadmin@mastersystem.co.id';
$password = 'MS!12345'; // Ensure this is your actual app-specific password

// Try to connect to the IMAP server
$inbox = imap_open($hostname, $username, $password) or die('Tidak dapat terhubung ke server IMAP: ' . imap_last_error());

// Tentukan subjek yang akan dicari
$searchSubject = '[Permohonan Approval] Request Recruitment-';

// Cari email dengan subjek yang ditentukan
$emails = imap_search($inbox, 'UNSEEN SUBJECT "' . $searchSubject . '"');

if ($emails) {
    echo "Ditemukan " . count($emails) . " email dengan subjek '$searchSubject':<br><br>";

    // Loop melalui email dan tampilkan subjek, dari, tanggal, dan tubuh
    foreach ($emails as $email_number) {
        $header = imap_headerinfo($inbox, $email_number);
        $fullSubject = $header->subject;

        // Ekstrak bagian yang diinginkan dari subjek
        $pattern = '/\[Permohonan Approval\] Request Recruitment-(.*)/';
        if (preg_match($pattern, $fullSubject, $matches)) {
            $extractedPart = $matches[1]; // Ini akan menjadi "Penggantian/2024/05/29/45"
            echo "Bagian yang Diekstrak: " . htmlspecialchars($extractedPart) . "<br>";
        }

        echo "Subjek: " . htmlspecialchars($fullSubject) . "<br>";
        echo "Dari: " . htmlspecialchars($header->fromaddress) . "<br>";
        echo "Tanggal: " . htmlspecialchars($header->date) . "<br>";

        // Ambil tubuh email
        $extractedPart = htmlspecialchars($extractedPart);
        $from = $header->fromaddress; // Tidak perlu htmlspecialchars() di sini untuk $from
        // echo "Nilai dari \$header->fromaddress: " . $from . "<br>";

        // Mencoba mengekstrak alamat email
        if (preg_match("/<([^>]+)>/", $from, $matches)) {
            $fromEmail = $matches[1];
            echo "Email dari: " . htmlspecialchars($fromEmail) . "<br>";
        } else {
            // Jika pola tidak cocok, coba ekstrak hanya alamat email
            preg_match("/[\w.-]+@[\w.-]+/", $from, $matches);
            if (!empty($matches)) {
                $fromEmail = $matches[0];
                echo "Email dari: " . htmlspecialchars($fromEmail) . "<br>";
            } else {
                echo "Format alamat email tidak valid.";
            }
        }

        $fromemail = htmlspecialchars($fromEmail);

        // Fetch the email body
        $body = imap_fetchbody($inbox, $email_number, 1);

        // Definisikan pola regex yang sama untuk kedua pola tubuh email
        $bodyPattern = '/\[(.*?)\]\s*-\s*(.*?)\s*\[(.*?)\]/';

        // Ekstrak bagian yang diinginkan dari tubuh email
        if (preg_match($bodyPattern, $body, $matches)) {
            $status = trim($matches[1]);
            $catatan = trim($matches[3]);

            // Periksa akhir kata untuk menentukan tipe email
            if (strpos($body, 'gm') !== false) {
                $status_gm = $status;
                $catatan_gm = $catatan;
                echo "Email Tipe GM:<br>";
                echo "Status GM: $status_gm<br>";
                echo "Catatan GM: $catatan_gm<br>";
            }
            if (strpos($body, 'hcm') !== false) {
                $status_hcm = $status;
                $catatan_hcm = $catatan;
                echo "Email Tipe HCM:<br>";
                echo "Status HCM: $status_hcm<br>";
                echo "Catatan HCM: $catatan_hcm<br>";
            }
            if (strpos($body, 'bod') !== false) {
                $status_bod = $status;
                $catatan_bod = $catatan;
                echo "Email Tipe BOD:<br>";
                echo "Status BOD: $status_bod<br>";
                echo "Catatan BOD: $catatan_bod<br>";
            }



            $query2 = "SELECT * FROM sa_hcm_requirement WHERE id_fpkb = '$extractedPart' AND status_request = 'Pending Approval'";
            // $condition = "id_fpkb LIKE '%$extractedPart%' AND status_request = 'Pending Approval'";
            $cek = $DBHCM->get_sqlV2($query2);
            // $cek = $DBHCM->get_data('sa_hcm_requirement', $condition);





            while ($view = mysqli_fetch_array($cek[1])) {
                $cekstatusrekrutmen = $view['status_rekrutmen'];
                $gm = $view['gm'];
                $parts_gm = explode('<', $gm);
                $emailgm = trim($parts_gm[1], '>');

                $gm_hcm = $view['gm_hcm'];
                $parts_gmhcm = explode('<', $gm_hcm);
                $emailgm_hcm = trim($parts_gmhcm[1], '>');

                // $bod = $view['gm_bod'];
                // $parts_bod = explode('<', $bod);
                // $emailbod = trim($parts_bod[1], '>');
                $posisi = $view['posisi'];


                if ($view['status_rekrutmen'] == 'Penambahan') {
                    if ($view['id_fpkb'] == $extractedPart && $view['status_gm'] == 'Pending') {
                        // if ($emailgm == $fromemail) {
                        if (stripos($fromemail, $emailgm) !== false) {
                            if ($status_gm == "Dissaprove" || $status_gm == "Dissaprove") {
                                $status_request = 'Inactive';
                            } else {
                                $status_request = 'Pending Approval';
                            }
                            $updateResult = $DBHCM->get_res("UPDATE sa_hcm_requirement SET status_gm = '$status_gm', catatan_gm = '$catatan_gm', status_request = '$status_request' WHERE id_fpkb = '" . $extractedPart . "'");
                            $description = $view['gm'] . " Telah memberikan " . $status_gm . " pada data dengan ID FPKB " . $extractedPart;
                            $insert = sprintf(
                                "(`id_fpkb`,`project_code`,`project_name`,`description`,`entry_by`) VALUES (%s,%s,%s,%s,%s)",
                                GetSQLValueString($extractedPart, "text"),
                                GetSQLValueString($view['project_code'], "text"),
                                GetSQLValueString($view['nama_project'], "text"),
                                GetSQLValueString($description, "text"),
                                GetSQLValueString($view['gm'], "text")
                            );
                            $res = $DBHCM->insert_data("hcm_requirement_log", $insert);
                            // $to = 'malik.aulia@mastersystem.co.id';
                            $to = $view['request_by'];
                            $from = "MSIZone<msizone@mastersystem.co.id>";
                            // $cc = "recruitment.team@mastersystem.co.id";
                            $from = "MSIZone<msizone@mastersystem.co.id>";
                            $cc = $view['gm'] . ',recruitment.team@mastersystem.co.id';
                            $bcc = "";
                            $reply = $from;
                            $subject = "Request Karyawan $posisi";
                            $msg = '<table width="100%">';
                            $msg .= '    <tr><td width="20%" rowspan="4"></td>';
                            $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
                            $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
                            $msg .= '    <td width="20%" rowspan="4"></tr>';
                            $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
                            $msg .= '        <p>Dear ' . $to . '</p>';
                            $msg .= '        <p>';
                            $msg .= '<table width="80%">';
                            $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $view['id_fpkb'] . '</td></tr>';
                            $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $view['divisi'] . '</td></tr>';
                            $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $view['posisi'] . '</td></tr>';
                            $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $view['project_code'] . '</td></tr>';
                            $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $view['status_rekrutmen'] . '</td></tr>';
                            $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $view['status_karyawan'] . '</td></tr>';
                            $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $view['kandidat'] . '</td></tr>';
                            $msg .= '</table>';
                            $msg .= '</p>';
                            $msg .= '<p>Telah mendapatkan ' . $status_gm . ' dari ' . $view['gm'] . '</p>';
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
                            if ($updateResult) {
                                $to = $view['gm_hcm'];
                                $from = "MSIZone<msizone@mastersystem.co.id>";
                                // $cc = "recruitment.team@mastersystem.co.idco.id";
                                $linkdissaprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval] Request Recruitment-' . $extractedPart . '&body=[Dissaprove] - hcm%0D%0A[notes] - hcm';
                                $linkapprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval] Request Recruitment-' . $extractedPart . '&body=[Approve] - hcm%0D%0A[notes] - hcm';
                                $from = "MSIZone<msizone@mastersystem.co.id>";
                                $cc = "recruitment.team@mastersystem.co.id";
                                $bcc = "";
                                $reply = $from;
                                $subject = "[Permohonan Approval] Request Recruitment";
                                $msg = '<table width="100%">';
                                $msg .= '    <tr><td width="20%" rowspan="4"></td>';
                                $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
                                $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
                                $msg .= '    <td width="20%" rowspan="4"></tr>';
                                $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
                                $msg .= '        <p>Dear ' . $to . '</p>';
                                // $msg .= '        <p>Penambahan Pending</p>';
                                $msg .= '        <p>Berikut ini adalah daftar Request Karyawan yang menunggu approval dari Anda.</p>';
                                $msg .= '        <p>';
                                $msg .= '<table width="80%">';
                                $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $view['id_fpkb'] . '</td></tr>';
                                $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $view['divisi'] . '</td></tr>';
                                $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $view['posisi'] . '</td></tr>';
                                $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $view['project_code'] . '</td></tr>';
                                $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $view['status_rekrutmen'] . '</td></tr>';
                                $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $view['status_karyawan'] . '</td></tr>';
                                $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $view['kandidat'] . '</td></tr>';

                                $msg .= '</table>';
                                $msg .= '</p>';
                                $msg .= '<p>Silahkan direview untuk kelayakan mendapatkan approval.</p>';
                                $msg .= '        <table>';
                                $msg .= '        <tr>';
                                $msg .= '        <p>Jika Approve silahkan klik link berikut <a href="' . $linkapprove . '">Approve</a> jika Tidak maka klik link berikut <a href="' . $linkdissaprove . '">Dissaprove</a></p>';
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
                            // } else {
                            //     echo "Failed to update status_gm and catatan_gm for id_fpkb: " . htmlspecialchars($extractedPart) . "<br>";
                            // }
                        } else {
                            echo "nama gm tidak sesuai";
                        }
                    }
                    if ($view['id_fpkb'] == $extractedPart && $view['status_gm'] == 'Approve' && $view['status_gm_hcm'] == 'Pending') {
                        if (stripos($fromemail, $emailgm_hcm) !== false) {
                            // if ($emailgm_hcm == $fromemail) {
                            if ($status_hcm == "Dissaprove" || $status_hcm == "Dissaprove") {
                                $status_request = 'Inactive';
                            } else {
                                $status_request = 'Pending Approval';
                            }
                            $updateResult = $DBHCM->get_res("UPDATE sa_hcm_requirement SET status_gm_hcm = '$status_hcm', catatan_gm_hcm = '$catatan_hcm', status_request = '$status_request' WHERE id_fpkb = '" . $extractedPart . "'");
                            $description = $view['gm_hcm'] . " Telah memberikan " . $status_hcm . " pada data dengan ID FPKB " . $extractedPart;
                            $insert = sprintf(
                                "(`id_fpkb`,`project_code`,`project_name`,`description`,`entry_by`) VALUES (%s,%s,%s,%s,%s)",
                                GetSQLValueString($extractedPart, "text"),
                                GetSQLValueString($view['project_code'], "text"),
                                GetSQLValueString($view['nama_project'], "text"),
                                GetSQLValueString($description, "text"),
                                GetSQLValueString($view['gm_hcm'], "text")
                            );
                            $res = $DBHCM->insert_data("hcm_requirement_log", $insert);
                            // $to = 'malik.aulia@mastersystem.co.id';
                            $to = $view['request_by'];
                            $from = "MSIZone<msizone@mastersystem.co.id>";
                            // $cc = "recruitment.team@mastersystem.co.idco.id";
                            $from = "MSIZone<msizone@mastersystem.co.id>";
                            $cc = $view['gm'] . ',' . $view['gm_hcm'] . ',recruitment.team@mastersystem.co.id';
                            $bcc = "";
                            $reply = $from;
                            $subject = "Request Karyawan $posisi";
                            $msg = '<table width="100%">';
                            $msg .= '    <tr><td width="20%" rowspan="4"></td>';
                            $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
                            $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
                            $msg .= '    <td width="20%" rowspan="4"></tr>';
                            $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
                            $msg .= '        <p>Dear ' . $to . '</p>';
                            $msg .= '        <p>';
                            $msg .= '<table width="80%">';
                            $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $view['id_fpkb'] . '</td></tr>';
                            $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $view['divisi'] . '</td></tr>';
                            $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $view['posisi'] . '</td></tr>';
                            $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $view['project_code'] . '</td></tr>';
                            $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $view['status_rekrutmen'] . '</td></tr>';
                            $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $view['status_karyawan'] . '</td></tr>';
                            $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $view['kandidat'] . '</td></tr>';
                            $msg .= '</table>';
                            $msg .= '</p>';
                            $msg .= '<p>Telah mendapatkan ' . $status_hcm . ' dari ' . $view['gm_hcm'] . '</p>';
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
                            if ($updateResult) {
                                $to = $view['gm_bod'];
                                $from = "MSIZone<msizone@mastersystem.co.id>";
                                // $cc = "recruitment.team@mastersystem.co.idco.id";
                                $linkdissaprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval] Request Recruitment-' . $extractedPart . '&body=[Dissaprove] - bod%0D%0A[notes] - bod';
                                $linkapprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval] Request Recruitment-' . $extractedPart . '&body=[Approve] - bod%0D%0A[notes] - bod';
                                $from = "MSIZone<msizone@mastersystem.co.id>";
                                $cc = "recruitment.team@mastersystem.co.id";
                                $bcc = "";
                                $reply = $from;
                                $subject = "[Permohonan Approval] Request Recruitment";
                                $msg = '<table width="100%">';
                                $msg .= '    <tr><td width="20%" rowspan="4"></td>';
                                $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
                                $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
                                $msg .= '    <td width="20%" rowspan="4"></tr>';
                                $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
                                $msg .= '        <p>Dear ' . $to . '</p>';
                                $msg .= '        <p>Berikut ini adalah daftar Request Karyawan yang menunggu approval dari Anda.</p>';
                                $msg .= '        <p>';
                                $msg .= '<table width="80%">';
                                $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $view['id_fpkb'] . '</td></tr>';
                                $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $view['divisi'] . '</td></tr>';
                                $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $view['posisi'] . '</td></tr>';
                                $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $view['project_code'] . '</td></tr>';
                                $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $view['status_rekrutmen'] . '</td></tr>';
                                $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $view['status_karyawan'] . '</td></tr>';
                                $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $view['kandidat'] . '</td></tr>';

                                $msg .= '</table>';
                                $msg .= '</p>';
                                $msg .= '<p>Silahkan direview untuk kelayakan mendapatkan approval.</p>';
                                $msg .= '        <table>';
                                $msg .= '        <tr>';
                                $msg .= '        <p>Jika Approve silahkan klik link berikut <a href="' . $linkapprove . '">Approve</a> jika Tidak maka klik link berikut <a href="' . $linkdissaprove . '">Dissaprove</a></p>';
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
                                echo "Updated status_gm and catatan_gm for id_fpkb: " . htmlspecialchars($extractedPart) . "<br>";
                            } else {
                                echo "Failed to update status_gm and catatan_gm for id_fpkb: " . htmlspecialchars($extractedPart) . "<br>";
                            }
                        } else {
                            echo "nama gm hcm tidak sesuai";
                        }
                    }
                    if ($view['id_fpkb'] == $extractedPart && $view['status_gm'] == 'Approve' && $view['status_gm_hcm'] == 'Approve' && $view['status_gm_bod'] == 'Pending') {
                        $bod = $view['gm_bod'];
                        $parts_bod = explode('<', $bod);
                        $emailbod = trim($parts_bod[1], '>');
                        if (stripos($fromemail, $emailbod) !== false) {
                            if ($status_bod == "Dissaprove" || $status_bod == "Dissaprove") {
                                $status_request = 'Inactive';
                            } else {
                                $status_request = 'Submitted';
                            }
                            $updateResult = $DBHCM->get_res("UPDATE sa_hcm_requirement SET status_gm_bod = '$status_bod', catatan_gm_bod = '$catatan_bod', status_request = '$status_request' WHERE id_fpkb = '" . $extractedPart . "'");
                            $description = $view['gm_bod'] . " Telah memberikan " . $status_bod . " pada data dengan ID FPKB " . $extractedPart;
                            $insert = sprintf(
                                "(`id_fpkb`,`project_code`,`project_name`,`description`,`entry_by`) VALUES (%s,%s,%s,%s,%s)",
                                GetSQLValueString($extractedPart, "text"),
                                GetSQLValueString($view['project_code'], "text"),
                                GetSQLValueString($view['nama_project'], "text"),
                                GetSQLValueString($description, "text"),
                                GetSQLValueString($view['gm_bod'], "text")
                            );
                            $res = $DBHCM->insert_data("hcm_requirement_log", $insert);
                            // $to = 'malik.aulia@mastersystem.co.id';
                            $to = $view['request_by'];
                            $from = "MSIZone<msizone@mastersystem.co.id>";
                            // $cc = "recruitment.team@mastersystem.co.idco.id";
                            $from = "MSIZone<msizone@mastersystem.co.id>";
                            $cc = "recruitment.team@mastersystem.co.id";
                            $cc = $view['gm'] . ',' . $view['gm_hcm'] . ',' . $view['gm_bod'] . ',recruitment.team@mastersystem.co.idco.id';
                            $bcc = "";
                            $reply = $from;
                            $subject = "Request Karyawan $posisi";
                            $msg = '<table width="100%">';
                            $msg .= '    <tr><td width="20%" rowspan="4"></td>';
                            $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
                            $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
                            $msg .= '    <td width="20%" rowspan="4"></tr>';
                            $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
                            $msg .= '        <p>Dear ' . $to . '</p>';
                            $msg .= '        <p>';
                            $msg .= '<table width="80%">';
                            $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $view['id_fpkb'] . '</td></tr>';
                            $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $view['divisi'] . '</td></tr>';
                            $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $view['posisi'] . '</td></tr>';
                            $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $view['project_code'] . '</td></tr>';
                            $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $view['status_rekrutmen'] . '</td></tr>';
                            $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $view['status_karyawan'] . '</td></tr>';
                            $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $view['kandidat'] . '</td></tr>';
                            $msg .= '</table>';
                            $msg .= '</p>';
                            $msg .= '<p>Telah mendapatkan ' . $status_bod . ' dari ' . $view['gm_bod'] . '</p>';
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
                            // if ($view['status_gm'] == 'Approve' && $view['status_gm_hcm'] == 'Approve' && $view['status_gm_bod'] == 'Approve') {
                            //     // email kirim ke bu reta
                            //     // $to = 'Malik Aulia Wiratama <malik.aulia@mastersystem.co.id>';
                            //     $to = 'Wahyuwinningdyah Margareta,S.S.PSI <margareta.sekar@mastersystem.co.id>';
                            //     $from = "MSIZone<msizone@mastersystem.co.id>";
                            $cc = "recruitment.team@mastersystem.co.id";
                            //     $cc = $view['gm'] . ',' . $view['gm_hcm'] . ',' . $view['gm_bod'];
                            //     $id = $view['id'];
                            //     $kp = $view['project_code'];
                            //     $link = 'https://msizone.mastersystem.co.id/index.php?mod=hcm_requirement&act=editapproval&id=' . $id . '&project_code=' . $kp . '&submit=Submit';
                            //     $bcc = "";
                            //     $reply = $from;
                            //     $subject = "[Permohonan Approval] Request Recruitment";
                            //     $msg = '<table width="100%">';
                            //     $msg .= '    <tr><td width="20%" rowspan="4"></td>';
                            //     $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
                            //     $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
                            //     $msg .= '    <td width="20%" rowspan="4"></tr>';
                            //     $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
                            //     $msg .= '        <p>Dear ' . $to . '</p>';
                            //     $msg .= '        <p>Berikut ini adalah daftar Request Karyawan yang menunggu assignment recruiter dari anda.</p>';
                            //     $msg .= '        <p>';
                            //     $msg .= '<table width="80%">';
                            //     $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $view['id_fpkb'] . '</td></tr>';
                            //     $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $view['divisi'] . '</td></tr>';
                            //     $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $view['posisi'] . '</td></tr>';
                            //     $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $view['project_code'] . '</td></tr>';
                            //     $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $view['status_rekrutmen'] . '</td></tr>';
                            //     $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $view['status_karyawan'] . '</td></tr>';
                            //     $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $view['kandidat'] . '</td></tr>';
                            //     $msg .= '</table>';
                            //     $msg .= '</p>';
                            //     $msg .= '<p>Silahkan diproses untuk lebih lanjut dengan mengklik link berikut <a href="' . $link . '">Approve</a> .</p>';
                            //     $msg .= '        <table>';
                            //     $msg .= '        <tr>';
                            //     $msg .= '        </table>';
                            //     $msg .= '        <p>Terimakasih</p>';
                            //     $msg .= '    </td>';
                            //     $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
                            //     $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
                            //     $msg .= "</table>";
                            //     echo $msg;
                            //     $headers = "From: " . $from . "\r\n" .
                            //         "Reply-To: " . $reply . "\r\n" .
                            //         "Cc: " . $cc . "\r\n" .
                            //         "Bcc: " . $bcc . "\r\n" .
                            //         "MIME-Version: 1.0" . "\r\n" .
                            //         "Content-Type: text/html; charset=UTF-8" . "\r\n" .
                            //         "X-Mailer: PHP/" . phpversion();

                            //     $ALERT = new Alert();
                            //     if (mail($to, $subject, $msg, $headers)) {
                            //         echo "Email terkirim pada jam " . date("d M Y G:i:s");
                            //     } else {
                            //         echo "Email gagal terkirim pada jam " . date("d M Y G:i:s");
                            //     }
                            //     $mdlname = "NOTIFICATION";
                            //     $DBNOTIF = get_conn($mdlname);
                            //     $tblnamenotif = "trx_notification";
                            //     // $from = "Malik Aulia Wiratama <malik.aulia@mastersystem.co.id>";
                            //     // $to = "Malik Aulia Wiratama <malik.aulia@mastersystem.co.id>";
                            //     $notifmsg = $view['id_fpkb'] . "; " . $view['project_code'] . "; ";
                            //     $notif_link = "index.php?mod=hcm_requirement&act=editapproval&id=" . $view['id'] . "&project_code=" . $view['project_code'] . "&submit=Submit";
                            //     $insert = sprintf(
                            //         "(`notif_from`, `notif_to`, `notif_subject`, `notif_post`, `notif_link`) VALUES (%s, %s, %s, %s, %s)",
                            //         GetSQLValueString($from, "text"),
                            //         GetSQLValueString($to, "text"),
                            //         GetSQLValueString(ucwords("Submitted") . " ID FPKB " . $view['id_fpkb'], "text"),
                            //         GetSQLValueString($notifmsg, "text"),
                            //         GetSQLValueString($notif_link, "text")
                            //     );
                            //     $res = $DBNOTIF->insert_data($tblnamenotif, $insert);
                            // } else {
                            //     echo "Failed to update status_gm and catatan_gm for id_fpkb: " . htmlspecialchars($extractedPart) . "<br>";
                            // }
                            if ($view['status_gm'] == 'Approve' && $view['status_gm_hcm'] == 'Approve' && $status_bod == 'Approve') {
                                echo "udah masuk";
                                // email kirim ke bu reta
                                $to = 'Malik Aulia Wiratama <malik.aulia@mastersystem.co.id>';
                                $to = 'margareta.sekar@mastersystem.co.id';
                                $from = $view['gm_bod'];
                                // $cc = "recruitment.team@mastersystem.co.id";
                                $cc = $view['gm'] . ',' . $view['gm_hcm'] . ',' . $view['gm_bod'] . ',recruitment.team@mastersystem.co.id';
                                $kp = $view['project_code'];
                                $id = $view['id'];
                                $link = 'https://msizone.mastersystem.co.id/index.php?mod=hcm_requirement&act=editapproval&id=' . $id . '&project_code=' . $kp . '&submit=Submit';
                                $bcc = "";
                                $reply = $from;
                                $subject = "[Assign Recruiter] Request Recruitment";
                                $msg = '<table width="100%">';
                                $msg .= '    <tr><td width="20%" rowspan="4"></td>';
                                $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
                                $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
                                $msg .= '    <td width="20%" rowspan="4"></tr>';
                                $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
                                $msg .= '        <p>Dear ' . $to . '</p>';
                                $msg .= '        <p>Berikut ini adalah daftar Request Karyawan yang menunggu assignment recruiter dari Anda.</p>';
                                $msg .= '        <p>';
                                $msg .= '<table width="80%">';
                                $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $view['id_fpkb'] . '</td></tr>';
                                $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $view['divisi'] . '</td></tr>';
                                $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $view['posisi'] . '</td></tr>';
                                $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $view['project_code'] . '</td></tr>';
                                $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $view['status_rekrutmen'] . '</td></tr>';
                                $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $view['status_karyawan'] . '</td></tr>';
                                $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $view['kandidat'] . '</td></tr>';
                                $msg .= '</table>';
                                $msg .= '</p>';
                                $msg .= '<p>Silahkan diproses untuk lebih lanjut dengan mengklik link berikut <a href="' . $link . '">Link</a> .</p>';
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
                                $mdlname = "NOTIFICATION";
                                $DBNOTIF = get_conn($mdlname);
                                $tblnamenotif = "trx_notification";
                                // $from = "Malik Aulia Wiratama <malik.aulia@mastersystem.co.id>";
                                // $to = "Malik Aulia Wiratama <malik.aulia@mastersystem.co.id>";
                                // var_dump($view['id_fpkb']);
                                // die;
                                $notifmsg = $view['id_fpkb'] . "; " . $view['project_code'] . "; ";
                                $notif_link = "index.php?mod=hcm_requirement&act=editapproval&id=" . $view['id'] . "&project_code=" . $view['project_code'] . "&submit=Submit";
                                $insert = sprintf(
                                    "(`notif_from`, `notif_to`, `notif_subject`, `notif_post`, `notif_link`) VALUES (%s, %s, %s, %s, %s)",
                                    GetSQLValueString($from, "text"),
                                    GetSQLValueString($to, "text"),
                                    GetSQLValueString(ucwords("Submitted ID FPKB ")  . $view['id_fpkb'], "text"),
                                    GetSQLValueString($notifmsg, "text"),
                                    GetSQLValueString($notif_link, "text")
                                );

                                $res = $DBNOTIF->insert_data($tblnamenotif, $insert);
                            } else {
                                echo "Failed to update status_gm and catatan_gm for id_fpkb: " . htmlspecialchars($extractedPart) . "<br>";
                            }
                        } else {
                            echo "nama gm hcm tidak sesuai";
                        }
                    }
                }
                if ($view['status_rekrutmen'] == 'Penggantian') {
                    if ($view['id_fpkb'] == $extractedPart && $view['status_gm'] == 'Pending') {
                        if (stripos($fromemail, $emailgm) !== false) {
                            // if ($emailgm == $fromemail) {
                            if ($status_gm == "Dissaprove" || $status_gm == "Dissaprove") {
                                $status_request = 'Inactive';
                            } else {
                                $status_request = 'Pending Approval';
                            }
                            $updateResult = $DBHCM->get_res("UPDATE sa_hcm_requirement SET status_gm = '$status_gm', catatan_gm = '$catatan_gm', status_request = '$status_request' WHERE id_fpkb = '" . $extractedPart . "'");
                            $description = $view['gm'] . " Telah memberikan " . $status_gm . " pada data dengan ID FPKB " . $extractedPart;
                            $insert = sprintf(
                                "(`id_fpkb`,`project_code`,`project_name`,`description`,`entry_by`) VALUES (%s,%s,%s,%s,%s)",
                                GetSQLValueString($extractedPart, "text"),
                                GetSQLValueString($view['project_code'], "text"),
                                GetSQLValueString($view['nama_project'], "text"),
                                GetSQLValueString($description, "text"),
                                GetSQLValueString($view['gm'], "text")
                            );
                            $res = $DBHCM->insert_data("hcm_requirement_log", $insert);
                            // $to = 'malik.aulia@mastersystem.co.id';
                            $to = $view['request_by'];
                            $from = "MSIZone<msizone@mastersystem.co.id>";
                            // $cc = "recruitment.team@mastersystem.co.idco.id";
                            $cc = "recruitment.team@mastersystem.co.id";
                            $cc = $view['gm'] . ',recruitment.team@mastersystem.co.id';
                            $bcc = "";
                            $reply = $from;
                            $subject = "Request Karyawan $posisi";
                            $msg = '<table width="100%">';
                            $msg .= '    <tr><td width="20%" rowspan="4"></td>';
                            $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
                            $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
                            $msg .= '    <td width="20%" rowspan="4"></tr>';
                            $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
                            $msg .= '        <p>Dear ' . $to . '</p>';
                            $msg .= '        <p>';
                            $msg .= '<table width="80%">';
                            $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $view['id_fpkb'] . '</td></tr>';
                            $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $view['divisi'] . '</td></tr>';
                            $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $view['posisi'] . '</td></tr>';
                            $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $view['project_code'] . '</td></tr>';
                            $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $view['status_rekrutmen'] . '</td></tr>';
                            $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $view['status_karyawan'] . '</td></tr>';
                            $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $view['kandidat'] . '</td></tr>';

                            $msg .= '</table>';
                            $msg .= '</p>';
                            $msg .= '<p>Telah mendapatkan ' . $status_gm . ' dari ' . $view['gm'] . '</p>';
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
                            if ($updateResult) {
                                $to = $view['gm_hcm'];
                                // $to = 'ajie.sandi@mastersystem.co.id';
                                // $to = 'malik.aulia@mastersystem.co.id';
                                $from = "MSIZone<msizone@mastersystem.co.id>";
                                // $cc = "recruitment.team@mastersystem.co.idco.id";
                                $linkdissaprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval] Request Recruitment-' . $extractedPart . '&body=[Dissaprove] - hcm%0D%0A[notes] - hcm';
                                $linkapprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval] Request Recruitment-' . $extractedPart . '&body=[Approve] - hcm%0D%0A[notes] - hcm';
                                $from = "MSIZone<msizone@mastersystem.co.id>";
                                $cc = "recruitment.team@mastersystem.co.id";
                                $bcc = "";
                                $reply = $from;
                                $subject = "[Permohonan Approval] Request Recruitment";
                                $msg = '<table width="100%">';
                                $msg .= '    <tr><td width="20%" rowspan="4"></td>';
                                $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
                                $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
                                $msg .= '    <td width="20%" rowspan="4"></tr>';
                                $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
                                $msg .= '        <p>Dear ' . $to . '</p>';
                                $msg .= '        <p>Berikut ini adalah daftar Request Karyawan yang menunggu approval dari Anda.</p>';
                                $msg .= '        <p>';
                                $msg .= '<table width="80%">';
                                $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $view['id_fpkb'] . '</td></tr>';
                                $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $view['divisi'] . '</td></tr>';
                                $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $view['posisi'] . '</td></tr>';
                                $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $view['project_code'] . '</td></tr>';
                                $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $view['status_rekrutmen'] . '</td></tr>';
                                $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $view['status_karyawan'] . '</td></tr>';
                                $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $view['kandidat'] . '</td></tr>';

                                $msg .= '</table>';
                                $msg .= '</p>';
                                $msg .= '<p>Silahkan direview untuk kelayakan mendapatkan approval.</p>';
                                $msg .= '        <table>';
                                $msg .= '        <tr>';
                                $msg .= '        <p>Jika Approve silahkan klik link berikut <a href="' . $linkapprove . '">Approve</a> jika Tidak maka klik link berikut <a href="' . $linkdissaprove . '">Dissaprove</a></p>';
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
                            // } else {
                            //     echo "Failed to update status_gm and catatan_gm for id_fpkb: " . htmlspecialchars($extractedPart) . "<br>";
                            // }
                        } else {
                            echo "nama gm tidak sesuai";
                        }
                    }

                    if ($view['id_fpkb'] == $extractedPart && $view['status_gm'] == 'Approve' && $view['status_gm_hcm'] == 'Pending') {
                        if (stripos($fromemail, $emailgm_hcm) !== false) {
                            // if ($emailgm_hcm == $fromemail) {
                            if ($status_hcm == "Dissaprove" || $status_hcm == "Dissaprove") {
                                $status_request = 'Inactive';
                            } else {
                                $status_request = 'Submitted';
                            }

                            $updateResult = $DBHCM->get_res("UPDATE sa_hcm_requirement SET status_gm_hcm = '$status_hcm', catatan_gm_hcm = '$catatan_hcm', status_request = '$status_request' WHERE id_fpkb = '" . $extractedPart . "'");
                            $description = $view['gm_hcm'] . " Telah memberikan " . $status_hcm . " pada data dengan ID FPKB " . $extractedPart;
                            $insert = sprintf(
                                "(`id_fpkb`,`project_code`,`project_name`,`description`,`entry_by`) VALUES (%s,%s,%s,%s,%s)",
                                GetSQLValueString($extractedPart, "text"),
                                GetSQLValueString($view['project_code'], "text"),
                                GetSQLValueString($view['nama_project'], "text"),
                                GetSQLValueString($description, "text"),
                                GetSQLValueString($view['gm_hcm'], "text")
                            );
                            $res = $DBHCM->insert_data("hcm_requirement_log", $insert);
                            // $to = 'malik.aulia@mastersystem.co.id';
                            $to = $view['request_by'];
                            $from = "MSIZone<msizone@mastersystem.co.id>";
                            // $cc = "recruitment.team@mastersystem.co.idco.id";
                            $from = "MSIZone<msizone@mastersystem.co.id>";
                            // $cc = "recruitment.team@mastersystem.co.id";
                            $cc = $view['gm'] . ',' . $view['gm_hcm'] . ',recruitment.team@mastersystem.co.id';
                            $bcc = "";
                            $reply = $from;
                            $subject = "Request Karyawan $posisi";
                            $msg = '<table width="100%">';
                            $msg .= '    <tr><td width="20%" rowspan="4"></td>';
                            $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
                            $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
                            $msg .= '    <td width="20%" rowspan="4"></tr>';
                            $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
                            $msg .= '        <p>Dear ' . $to . '</p>';
                            $msg .= '        <p>';
                            $msg .= '<table width="80%">';
                            $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $view['id_fpkb'] . '</td></tr>';
                            $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $view['divisi'] . '</td></tr>';
                            $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $view['posisi'] . '</td></tr>';
                            $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $view['project_code'] . '</td></tr>';
                            $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $view['status_rekrutmen'] . '</td></tr>';
                            $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $view['status_karyawan'] . '</td></tr>';
                            $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $view['kandidat'] . '</td></tr>';
                            $msg .= '</table>';
                            $msg .= '</p>';
                            $msg .= '<p>Telah mendapatkan ' . $status_hcm . ' dari ' . $view['gm_hcm'] . '</p>';
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
                            if ($view['status_gm'] == 'Approve' && $status_hcm == 'Approve') {
                                echo "udah masuk";
                                // email kirim ke bu reta
                                $to = 'Malik Aulia Wiratama <malik.aulia@mastersystem.co.id>';
                                $to = 'margareta.sekar@mastersystem.co.id';
                                $from = $view['gm_hcm'];
                                // $cc = "recruitment.team@mastersystem.co.id";
                                $cc = $view['gm'] . ',' . $view['gm_hcm'] . ',recruitment.team@mastersystem.co.id';
                                $kp = $view['project_code'];
                                $id = $view['id'];
                                $link = 'https://msizone.mastersystem.co.id/index.php?mod=hcm_requirement&act=editapproval&id=' . $id . '&project_code=' . $kp . '&submit=Submit';
                                $bcc = "";
                                $reply = $from;
                                $subject = "[Assign Recruiter] Request Recruitment";
                                $msg = '<table width="100%">';
                                $msg .= '    <tr><td width="20%" rowspan="4"></td>';
                                $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
                                $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
                                $msg .= '    <td width="20%" rowspan="4"></tr>';
                                $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
                                $msg .= '        <p>Dear ' . $to . '</p>';
                                $msg .= '        <p>Berikut ini adalah daftar Request Karyawan yang menunggu assignment recruiter dari Anda.</p>';
                                $msg .= '        <p>';
                                $msg .= '<table width="80%">';
                                $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $view['id_fpkb'] . '</td></tr>';
                                $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $view['divisi'] . '</td></tr>';
                                $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $view['posisi'] . '</td></tr>';
                                $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $view['project_code'] . '</td></tr>';
                                $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $view['status_rekrutmen'] . '</td></tr>';
                                $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $view['status_karyawan'] . '</td></tr>';
                                $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $view['kandidat'] . '</td></tr>';
                                $msg .= '</table>';
                                $msg .= '</p>';
                                $msg .= '<p>Silahkan diproses untuk lebih lanjut dengan mengklik link berikut <a href="' . $link . '">Link</a> .</p>';
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
                                $mdlname = "NOTIFICATION";
                                $DBNOTIF = get_conn($mdlname);
                                $tblnamenotif = "trx_notification";
                                // $from = "Malik Aulia Wiratama <malik.aulia@mastersystem.co.id>";
                                // $to = "Malik Aulia Wiratama <malik.aulia@mastersystem.co.id>";
                                // var_dump($view['id_fpkb']);
                                // die;
                                $notifmsg = $view['id_fpkb'] . "; " . $view['project_code'] . "; ";
                                $notif_link = "index.php?mod=hcm_requirement&act=editapproval&id=" . $view['id'] . "&project_code=" . $view['project_code'] . "&submit=Submit";
                                $insert = sprintf(
                                    "(`notif_from`, `notif_to`, `notif_subject`, `notif_post`, `notif_link`) VALUES (%s, %s, %s, %s, %s)",
                                    GetSQLValueString($from, "text"),
                                    GetSQLValueString($to, "text"),
                                    GetSQLValueString(ucwords("Submitted ID FPKB ") . $view['id_fpkb'], "text"),
                                    GetSQLValueString($notifmsg, "text"),
                                    GetSQLValueString($notif_link, "text")
                                );

                                $res = $DBNOTIF->insert_data($tblnamenotif, $insert);
                            } else {
                                echo "Failed to update status_gm and catatan_gm for id_fpkb: " . htmlspecialchars($extractedPart) . "<br>";
                            }
                        } else {
                            echo "nama gm hcm tidak sesuai";
                        }
                    }
                }
            }

            echo "<br><br>";
        }
    }
} else {
    echo "Tidak ditemukan email dengan subjek '$searchSubject'.<br>";
}

// Tutup koneksi IMAP
imap_close($inbox);

// $time_end = microtime(true);
// $time = $time_end - $time_start;
// echo "<br/>==========";
// echo "Finished : " . date("d-M-Y G:i:s");
// echo "The time used to run this module $time seconds";
// echo "==========";
// $DBCRON->ending($descErr);
