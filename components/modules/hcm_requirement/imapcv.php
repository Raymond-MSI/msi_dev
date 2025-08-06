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
$username = 'malikwitama@gmail.com';
$password = 'bdlk jgtq fhdl bbyq'; // Ensure this is your actual app-specific password

// Try to connect to the IMAP server
$inbox = imap_open($hostname, $username, $password) or die('Tidak dapat terhubung ke server IMAP: ' . imap_last_error());

// Tentukan subjek yang akan dicari
$searchSubject = '[TESTING] Status CV-';

// Cari email dengan subjek yang ditentukan
$emails = imap_search($inbox, 'UNSEEN SUBJECT "' . $searchSubject . '"');

if ($emails) {
    echo "Ditemukan " . count($emails) . " email dengan subjek '$searchSubject':<br><br>";

    // Loop melalui email dan tampilkan subjek, dari, tanggal, dan tubuh
    foreach ($emails as $email_number) {
        $header = imap_headerinfo($inbox, $email_number);
        $fullSubject = $header->subject;

        // Ekstrak bagian yang diinginkan dari subjek
        $pattern = '/\[TESTING\] Status CV-(.*)/';
        if (preg_match($pattern, $fullSubject, $matches)) {
            $extractedPart = $matches[1]; // Ini akan menjadi "Penggantian/2024/05/29/45"
            echo "Bagian yang Diekstrak: " . htmlspecialchars($extractedPart) . "<br>";
        }

        echo "Subjek: " . htmlspecialchars($fullSubject) . "<br>";
        echo "Dari: " . htmlspecialchars($header->fromaddress) . "<br>";
        echo "Tanggal: " . htmlspecialchars($header->date) . "<br>";

        // Ambil tubuh email
        $extractedPPart = htmlspecialchars($extractedPart);
        $from = htmlspecialchars($header->fromaddress);

        // Fetch the email body
        $body = imap_fetchbody($inbox, $email_number, 1);

        // Definisikan pola regex yang sama untuk kedua pola tubuh email
        $bodyPattern = '/\[(.*?)\]\s*-\s*(.*?)\s*\[(.*?)\]/';

        // Ekstrak bagian yang diinginkan dari tubuh email
        if (preg_match($bodyPattern, $body, $matches)) {
            $tanggal_interview = trim($matches[1]);
            $status_cv = trim($matches[3]);

            echo "Id CV: $extractedPPart<br>";
            echo "Tanggal Interview: $tanggal_interview<br>";
            echo "Status CV: $status_cv<br>";

            $query = "SELECT * FROM sa_hcm_notecv WHERE id_note = $extractedPPart AND status_cv is NULL";
            $cek = $DBHCM->get_sqlV2($query);
            while ($view = mysqli_fetch_array($cek[1])) {
                $updateResult = $DBHCM->get_res("UPDATE sa_hcm_notecv SET tanggal_interview = '$tanggal_interview', status_cv = '$status_cv', update_by = '$header->fromaddress' WHERE id_note = $extractedPPart");
                if ($updateResult) {
                    echo "berhasil";
                } else {
                    echo "gagal";
                }
            }
        }

        echo "<br><br>";
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
