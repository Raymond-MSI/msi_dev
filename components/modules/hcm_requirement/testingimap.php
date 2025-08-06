<?php
// IMAP server details


echo "==========";
echo "Execution module : Alert email 2024";
echo "Started : " . date("d-M-Y G:i:s");
echo "==========<br/>";
$time_start = microtime(true);

global $DBHCM;
$mdlname = "REQUIREMENT_HCM";
$DBHCM = get_conn($mdlname);

$hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
$username = 'malikwitama@gmail.com';
$password = 'bdlk jgtq fhdl bbyq'; // Ensure this is your actual app-specific password

// Try to connect to the IMAP server
$inbox = imap_open($hostname, $username, $password) or die('Tidak dapat terhubung ke server IMAP: ' . imap_last_error());

// Tentukan subjek yang akan dicari
$searchSubject = '[TESTING] Request Recruitment-';

// Cari email dengan subjek yang ditentukan
$emails = imap_search($inbox, 'SUBJECT "' . $searchSubject . '"');

if ($emails) {
    echo "Ditemukan " . count($emails) . " email dengan subjek '$searchSubject':<br><br>";

    // Loop melalui email dan tampilkan subjek, dari, tanggal, dan tubuh
    foreach ($emails as $email_number) {
        $header = imap_headerinfo($inbox, $email_number);
        $fullSubject = $header->subject;

        // Ekstrak bagian yang diinginkan dari subjek
        $pattern = '/\[TESTING\] Request Recruitment-(.*)/';
        if (preg_match($pattern, $fullSubject, $matches)) {
            $extractedPart = $matches[1]; // Ini akan menjadi "Penggantian/2024/05/29/45"
            echo "Bagian yang Diekstrak: " . htmlspecialchars($extractedPart) . "<br>";
        }

        echo "Subjek: " . htmlspecialchars($fullSubject) . "<br>";
        echo "Dari: " . htmlspecialchars($header->fromaddress) . "<br>";
        echo "Tanggal: " . htmlspecialchars($header->date) . "<br>";

        // Fetch the email body
        $from = htmlspecialchars($header->fromaddress);
        $body = imap_fetchbody($inbox, $email_number, 1);

        // Definisikan pola regex yang sama untuk kedua pola tubuh email
        $bodyPattern = '/\[(.*?)\]\s*-\s*(.*?)\s*\[(.*?)\]/';

        // Ekstrak bagian yang diinginkan dari tubuh email
        if (preg_match_all($bodyPattern, $body, $matches, PREG_SET_ORDER)) {
            $foundHCM = false;

            foreach ($matches as $match) {
                $status = trim($match[1]);
                $catatan = trim($match[3]);

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
                    $foundHCM = true;
                }
                if (strpos($body, 'bod') !== false) {
                    $status_bod = $status;
                    $catatan_bod = $catatan;
                    echo "Email Tipe BOD:<br>";
                    echo "Status BOD: $status_bod<br>";
                    echo "Catatan BOD: $catatan_bod<br>";
                }
            }

            if ($foundHCM) {
                $query = "SELECT * FROM sa_hcm_requirement WHERE id_fpkb = '" . $extractedPart . "' AND status_request = 'Pending Approval'";
                $cekgm = $DBHCM->get_sqlV2($query);

                while ($view = mysqli_fetch_array($cekgm[1])) {
                    $cekstatusrekrutmen = $view['status_rekrutmen'];
                    $gm = htmlspecialchars($view['gm']);
                    $gm_hcm = htmlspecialchars($view['gm_hcm']);
                    $bod = htmlspecialchars($view['gm_bod']);

                    if ($view['status_rekrutmen'] == 'Penggantian') {
                        if ($view['id_fpkb'] == $extractedPart && $view['status_gm'] == 'Pending') {
                            if ($gm == $from) {
                                if ($status_gm == "Disapprove") {
                                    $status_request = 'Inactive';
                                } else {
                                    $status_request = 'Pending Approval';
                                }
                                // Perform database update
                                $updateResult = $DBHCM->get_res("UPDATE sa_hcm_requirement SET status_gm = '$status_gm', catatan_gm = '$catatan_gm', status_request = '$status_request' WHERE id_fpkb = '" . $extractedPart . "'");
                                if ($updateResult) {
                                    echo "Updated status_gm and catatan_gm for id_fpkb: " . htmlspecialchars($extractedPart) . "<br>";
                                    $to = $view['gm_hcm'];
                                    $from = "MSIZone<msizone@mastersystem.co.id>";
                                    $cc = "recruitment.team@mastersystem.co.id";
                                    $bcc = "";
                                    $reply = $from;
                                    $subject = "[TESTING] Request Recruitment";
                                    $msg = '<table width="100%">';
                                    $msg .= '    <tr><td width="20%" rowspan="4"></td>';
                                    $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
                                    $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
                                    $msg .= '    <td width="20%" rowspan="4"></tr>';
                                    $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
                                    $msg .= '        <p>Dear,' . $to . '</p>';
                                    $msg .= '        <p>Penggantian Pending</p>';
                                    $msg .= '        <p>Berikut ini adalah daftar Request Karyawan yang menunggu approval dari Anda.</p>';
                                    $msg .= '        <p>';
                                    $msg .= '<table width="80%">';
                                    $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $view['divisi'] . '</td></tr>';
                                    $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $view['posisi'] . '</td></tr>';
                                    $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $view['project_code'] . '</td></tr>';
                                    $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $view['status_rekrutmen'] . '</td></tr>';
                                    $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $view['status_karyawan'] . '</td></tr>';
                                    $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $view['kandidat'] . '</td></tr>';
                                    $msg .= '<tr><td>Status</td><td>:</td><td>' . "Pending Approval" . '</td></tr>';
                                    $msg .= '</table>';
                                    $msg .= '</p>';
                                    $msg .= '<p>Silahkan direview untuk kelayakan mendapatkan approval.</p>';
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
                                } else {
                                    echo "Failed to update status_gm and catatan_gm for id_fpkb: " . htmlspecialchars($extractedPart) . "<br>";
                                }
                            } else {
                                echo "Nama GM tidak sesuai atau status GM sudah tidak pending.<br>";
                            }
                        }

                        if ($view['id_fpkb'] == $extractedPart && $view['status_gm'] == 'Approve' && $view['status_gm_hcm'] == 'Pending') {
                            if ($gm_hcm == $from) {
                                if ($status_hcm == "Disapprove") {
                                    $status_request = 'Inactive';
                                } else {
                                    $status_request = 'Pending Approval';
                                }
                                $updateResult = $DBHCM->get_res("UPDATE sa_hcm_requirement SET status_gm_hcm = '$status_hcm', catatan_gm_hcm = '$catatan_hcm', status_request = '$status_request' WHERE id_fpkb = '" . $extractedPart . "'");
                                if ($updateResult) {
                                    echo "Updated status_gm_hcm and catatan_gm_hcm for id_fpkb: " . htmlspecialchars($extractedPart) . "<br>";
                                } else {
                                    echo "Failed to update status_gm_hcm and catatan_gm_hcm for id_fpkb: " . htmlspecialchars($extractedPart) . "<br>";
                                }
                            } else {
                                echo "Nama GM HCM tidak sesuai.<br>";
                            }
                        }
                        if ($view['id_fpkb'] == $extractedPart && $view['status_gm'] == 'Approve' && $view['status_gm_hcm'] == 'Approve') {
                            // Email kirim ke bu reta
                            $to = "margareta.sekar@mastersystem.co.id";
                            $from = "MSIZone<msizone@mastersystem.co.id>";
                            $cc = "recruitment.team@mastersystem.co.id";
                            $bcc = "";
                            $reply = $from;
                            $subject = "[TESTING] Request Recruitment";
                            $msg = '<table width="100%">';
                            $msg .= '    <tr><td width="20%" rowspan="4"></td>';
                            $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
                            $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
                            $msg .= '    <td width="20%" rowspan="4"></tr>';
                            $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
                            $msg .= '        <p>Dear,' . $to . '</p>';
                            $msg .= '        <p>Penggantian Pending</p>';
                            $msg .= '        <p>Berikut ini adalah daftar Request Karyawan yang menunggu approval dari Anda.</p>';
                            $msg .= '        <p>';
                            $msg .= '<table width="80%">';
                            $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $view['divisi'] . '</td></tr>';
                            $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $view['posisi'] . '</td></tr>';
                            $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $view['project_code'] . '</td></tr>';
                            $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $view['status_rekrutmen'] . '</td></tr>';
                            $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $view['status_karyawan'] . '</td></tr>';
                            $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $view['kandidat'] . '</td></tr>';
                            $msg .= '<tr><td>Status</td><td>:</td><td>' . "Pending Approval" . '</td></tr>';
                            $msg .= '</table>';
                            $msg .= '</p>';
                            $msg .= '<p>Silahkan direview untuk kelayakan mendapatkan approval.</p>';
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
                        }
                    }
                }
            } else {
                echo "Email tidak berisi informasi HCM.<br>";
            }
        }

        echo "<br><br>";
    }
}


// Tutup koneksi IMAP
imap_close($inbox);
