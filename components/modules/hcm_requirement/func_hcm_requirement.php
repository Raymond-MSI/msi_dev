<?php

// include $_SERVER['DOCUMENT_ROOT'] . 'google_calendar_requirement.php';
// include $_SERVER['DOCUMENT_ROOT'] . '/microservices/google_calendar.php';
// include $_SERVER['DOCUMENT_ROOT'] . '/google_calendar_requirement.php';
require 'google_calendar.php';

if (isset($_POST['add'])) {
    $requestGMHCM = $DBHCM->get_sqlV2("SELECT employee_email FROM sa_view_employees WHERE organization_name LIKE '%Human Capital Management%' AND employee_email = '" . $_SESSION['Microservices_UserEmail'] .
        "'  AND resign_date IS null AND job_level = 2");

    if ($requestGMHCM[2] > 0) {
        $status = "Submitted";
    } else {
        $status = "Request Draft";
    }

    $result = $DBHCM->get_sqlV2("SELECT * FROM sa_hcm_requirement ORDER BY id DESC LIMIT 1");
    $status_rekrutmen = isset($_POST['status_rekrutmen']) ? addslashes($_POST['status_rekrutmen']) : '';
    $current_timestamp = time();
    $tahuninput = date('Y', $current_timestamp);
    $bulaninput = date('m', $current_timestamp);
    $tanggalinput = date('d', $current_timestamp);

    $divisi = $_POST['divisi'];
    $divisi = str_replace("&", "and", $divisi);

    // $last_id = 0;
    if ($result[2] > 0) {
        $id_fpkb = $result[0]['id_fpkb'];
        $part = explode("REC/", $id_fpkb);
        $part2 = explode("/", $part[1]);
        $last_id = $part2[0];
    } else {
        $last_id = 0;
    }
    $current_month = date('m');
    $current_year = date('Y');

    // Mendapatkan bulan dan tahun dari tanggal input terakhir
    // $last_date = date('Y-m-d', strtotime($_POST['tanggal_dibutuhkan']));
    $last_date = date('Y-m-d', strtotime($result[0]['request_date']));
    $last_month = date('m', strtotime($last_date));
    $last_year = date('Y', strtotime($last_date));

    // Jika permintaan rekrutmen masuk ke bulan baru atau tahun baru, reset nilai id_fpkb menjadi 1
    if ($current_month != $last_month || $current_year != $last_year) {
        $last_id = 0; // Reset nilai id_fpkb menjadi 0
    }
    $incremented_id = $last_id + 1;

    $idfpkb = $divisi . '/FPKB/REC/' . $incremented_id . '/' . $tahuninput;
    // var_dump($idfpkb);
    // die;

    $rangesalary = $_POST['from_salary'] . ' - ' . $_POST['to_salary'];
    $userName = addslashes($_SESSION['Microservices_UserName']);
    $entry_by = $userName . " <" . $_SESSION['Microservices_UserEmail'] . ">";

    // Ambil nilai dari status_karyawan
    $status_karyawan = $_POST['status_karyawan'];

    // Tentukan deskripsi kontrak berdasarkan status_karyawan
    if ($status_karyawan == 'Kontrak') {
        $deskripsi_kontrak = GetSQLValueString($_POST['deskripsi_kontrak'], "text");
    } else {
        $deskripsi_kontrak = "NULL"; // Set NULL untuk non-kontrak
    }
    $deskripsi = addslashes($_POST['deskripsi']);
    if ($_POST['status_rekrutmen'] == 'Penambahan') {
        $insert = sprintf(
            "(`id_fpkb`,`divisi`,`posisi`,`jumlah_dibutuhkan`,`tanggal_dibutuhkan`,`deskripsi`,`nama_project`,`nama_customer`,`project_code`,`periode_project`,`status_rekrutmen`,`status_karyawan`,`deskripsi_kontrak`,`mpp`,`jenis_kelamin`,`usia`,`pendidikan_minimal`,`jurusan`,`pengalaman_minimal`,`kompetensi_teknis`,`kompetensi_non_teknis`,`kandidat`,`internal`,`catatan`,`range_salary`,`request_by`,`gm`,`status_gm`,`gm_hcm`,`status_gm_hcm`,`gm_bod`,`status_gm_bod`,`status_request`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
            GetSQLValueString($idfpkb, "text"),
            GetSQLValueString($divisi, "text"),
            GetSQLValueString($_POST['posisi'], "text"),
            GetSQLValueString($_POST['jumlah_dibutuhkan'], "int"),
            GetSQLValueString($_POST['tanggal_dibutuhkan'], "date"),
            GetSQLValueString($deskripsi, "text"),
            GetSQLValueString($_POST['nama_project'], "text"),
            GetSQLValueString($_POST['nama_customer'], "text"),
            GetSQLValueString($_POST['project_code'], "text"),
            GetSQLValueString($_POST['periode_project'], "text"),
            GetSQLValueString($_POST['status_rekrutmen'], "text"),
            GetSQLValueString($status_karyawan, "text"),
            $deskripsi_kontrak,
            GetSQLValueString($_POST['mpp'], "text"),
            GetSQLValueString($_POST['jenis_kelamin'], "text"),
            GetSQLValueString($_POST['usia'], "text"),
            GetSQLValueString($_POST['pendidikan_minimal'], "text"),
            GetSQLValueString($_POST['jurusan'], "text"),
            GetSQLValueString($_POST['pengalaman_minimal'], "text"),
            GetSQLValueString($_POST['kompetensi_teknis'], "text"),
            GetSQLValueString($_POST['kompetensi_non_teknis'], "text"),
            GetSQLValueString($_POST['kandidat'], "text"),
            GetSQLValueString($_POST['internal'], "text"),
            GetSQLValueString($_POST['catatan'], "text"),
            GetSQLValueString($rangesalary, "text"),
            GetSQLValueString($entry_by, "text"),
            GetSQLValueString($_POST['gm'], "text"),
            GetSQLValueString($_POST['status_gm'], "text"),
            GetSQLValueString($_POST['gm_hcm'], "text"),
            GetSQLValueString($_POST['status_gm_hcm'], "text"),
            GetSQLValueString($_POST['gm_bod'], "text"),
            GetSQLValueString($_POST['status_gm_bod'], "text"),
            GetSQLValueString($status, "text")
        );
        $res = $DBHCM->insert_data($tblname, $insert);
    } else if ($_POST['status_rekrutmen'] == 'Penggantian') {
        $insert = sprintf(
            "(`id_fpkb`,`divisi`,`posisi`,`jumlah_dibutuhkan`,`tanggal_dibutuhkan`,`deskripsi`,`nama_project`,`nama_customer`,`project_code`,`periode_project`,`status_rekrutmen`,`reason_penggantian`,`status_karyawan`,`deskripsi_kontrak`,`mpp`,`jenis_kelamin`,`usia`,`pendidikan_minimal`,`jurusan`,`pengalaman_minimal`,`kompetensi_teknis`,`kompetensi_non_teknis`,`kandidat`,`internal`,`catatan`,`range_salary`,`request_by`,`gm`,`status_gm`,`gm_hcm`,`status_gm_hcm`,`status_request`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
            GetSQLValueString($idfpkb, "text"),
            GetSQLValueString($divisi, "text"),
            GetSQLValueString($_POST['posisi'], "text"),
            GetSQLValueString($_POST['jumlah_dibutuhkan'], "int"),
            GetSQLValueString($_POST['tanggal_dibutuhkan'], "date"),
            GetSQLValueString($deskripsi, "text"),
            GetSQLValueString($_POST['nama_project'], "text"),
            GetSQLValueString($_POST['nama_customer'], "text"),
            GetSQLValueString($_POST['project_code'], "text"),
            GetSQLValueString($_POST['periode_project'], "text"),
            GetSQLValueString($_POST['status_rekrutmen'], "text"),
            GetSQLValueString($_POST['reason_penggantian'], "text"),
            GetSQLValueString($status_karyawan, "text"),
            $deskripsi_kontrak,
            GetSQLValueString($_POST['mpp'], "text"),
            GetSQLValueString($_POST['jenis_kelamin'], "text"),
            GetSQLValueString($_POST['usia'], "text"),
            GetSQLValueString($_POST['pendidikan_minimal'], "text"),
            GetSQLValueString($_POST['jurusan'], "text"),
            GetSQLValueString($_POST['pengalaman_minimal'], "text"),
            GetSQLValueString($_POST['kompetensi_teknis'], "text"),
            GetSQLValueString($_POST['kompetensi_non_teknis'], "text"),
            GetSQLValueString($_POST['kandidat'], "text"),
            GetSQLValueString($_POST['internal'], "text"),
            GetSQLValueString($_POST['catatan'], "text"),
            GetSQLValueString($rangesalary, "text"),
            GetSQLValueString($entry_by, "text"),
            GetSQLValueString($_POST['gm'], "text"),
            GetSQLValueString($_POST['status_gm'], "text"),
            GetSQLValueString($_POST['gm_hcm'], "text"),
            GetSQLValueString($_POST['status_gm_hcm'], "text"),
            GetSQLValueString($status, "text")
        );
        $res = $DBHCM->insert_data($tblname, $insert);
    }
    $resultt = $DBHCM->get_sqlV2("SELECT * FROM sa_hcm_requirement ORDER BY id DESC LIMIT 1");
    // var_dump($resultt[0]['id']);
    // die;
    $mdlname = "NOTIFICATION";
    $DBNOTIF = get_conn($mdlname);
    $to = "Wahyuwinningdyah Margareta,S.S.PSI<margareta.sekar@mastersystem.co.id>";
    $from = $entry_by;
    $tblnamenotif = "trx_notification";
    $notifmsg = $idfpkb . "; " . $_POST['project_code'] . "; ";
    $project_name = str_replace(' ', '_', $_POST['nama_project']); // Mengganti spasi dengan underscore
    // $notif_link = "index.php?mod=hcm_requirement&act=editapproval&id=" . $_POST['id'] . "&project_code=" . $_POST['project_code'] . "&submit=Submit";
    $notif_link = "index.php?mod=hcm_requirement&act=edit&id=" . $resultt[0]['id'] . "&project_code=" . $_POST['project_code'] . "&project_name=" . $project_name . "&submit=Submit";
    $insert = sprintf(
        "(`notif_from`, `notif_to`, `notif_subject`, `notif_post`, `notif_link`) VALUES (%s, %s, %s, %s, %s)",
        GetSQLValueString($from, "text"),
        GetSQLValueString($to, "text"),
        GetSQLValueString(ucwords("Request ID FPKB ") . $idfpkb, "text"),
        GetSQLValueString($notifmsg, "text"),
        GetSQLValueString($notif_link, "text")
    );
    $res = $DBNOTIF->insert_data($tblnamenotif, $insert);
    // var_dump($notif_link);
    // die;

    $res = $DBNOTIF->insert_data($tblnamenotif, $insert);
    $posisi = $_POST['posisi'];
    $to = $_SESSION['Microservices_UserEmail'];
    $from = "MSIZone<msizone@mastersystem.co.id>";
    $cc = "recruitment.team@mastersystem.co.id";
    $bcc = "";
    $reply = $from;
    $subject = "Request Recruitment";
    $msg = '<table width="100%">';
    $msg .= '    <tr><td width="20%" rowspan="4"></td>';
    $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
    $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
    $msg .= '    <td width="20%" rowspan="4"></tr>';
    $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
    $msg .= '        <p>Dear ' . $userName . ',</p>';
    $msg .= '        <p>Berikut ini adalah daftar Request Karyawan.</p>';
    $msg .= '        <p>';
    $msg .= '<table width="80%">';
    $msg .= '<tr><td>FPKB Number</td><td>:</td><td>' . $idfpkb . '</td></tr>';
    $msg .= '<tr><td>Requester</td><td>:</td><td>' . $_SESSION['Microservices_UserName'] . '</td></tr>';
    $msg .= '<tr><td>Division</td><td>:</td><td>' . $_POST['divisi'] . '</td></tr>';
    $msg .= '<tr><td>Position</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
    $msg .= '<tr><td>Job Description</td><td>:</td><td>' . $_POST['deskripsi'] . '</td></tr>';
    $msg .= '<tr><td>Reason</td><td>:</td><td>' . $_POST['status_rekrutmen'];
    if ($_POST['status_rekrutmen'] == 'Penggantian') {
        $msg .= ' ' . $_POST['reason_penggantian'];
    }
    $msg .= '</td></tr>';
    $msg .= '<tr><td>Status Employee</td><td>:</td><td>' . $status_karyawan;
    if ($status_karyawan == 'Kontrak') {
        $msg .= ' ' . $deskripsi_kontrak;
    }
    $msg .= '</td></tr>';
    $msg .= '<tr><td>Source</td><td>:</td><td>' . $_POST['kandidat'] . '</td></tr>';
    $msg .= '<tr><td>Range Salary</td><td>:</td><td>' . $rangesalary . '</td></tr>';
    $msg .= '</table>';
    $msg .= '</p>';
    $msg .= '        <p>Base On Project :</p>';
    $msg .= '        <p>';
    $msg .= '<table width="80%" style="padding: 20px;">';
    $msg .= '<tr><td style="width: 20%;">Project Code</td><td style="width: 5%;">:</td><td>' . $_POST['project_code'] . '</td></tr>';
    $msg .= '<tr><td style="width: 20%;">Project Name</td><td style="width: 5%;">:</td><td>' . $_POST['nama_project'] . '</td></tr>';
    $msg .= '<tr><td style="width: 20%;">Customer</td><td style="width: 5%;">:</td><td>' . $_POST['nama_customer'] . '</td></tr>';
    $msg .= '<tr><td style="width: 20%;">Periode Project</td><td style="width: 5%;">:</td><td>' . $_POST['periode_project'] . '</td></tr>';
    $msg .= '</table>';
    $msg .= '</p>';

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


    $project_code = $_POST['project_code'];
    $project_name = $_POST['nama_project'];
    $description = $userName . " Telah memasukan data dengan KP " . $_POST['project_code'] . "dan Project Name " . $_POST['nama_project'];
    $insert = sprintf(
        "(`id_fpkb`,`project_code`,`project_name`,`description`,`entry_by`) VALUES (%s,%s,%s,%s,%s)",
        GetSQLValueString($idfpkb, "text"),
        GetSQLValueString($project_code, "text"),
        GetSQLValueString($project_name, "text"),
        GetSQLValueString($description, "text"),
        GetSQLValueString($entry_by, "text")
    );
    $res = $DBHCM->insert_data("hcm_requirement_log", $insert);
    // if ($_POST['status_rekrutmen'] == 'Penambahan' && $_POST['status_gm'] == 'Pending') {
    //     // $to = "malik.aulia@mastersystem.co.id";
    //     $linkdissaprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval] Request Recruitment-' . urlencode($idfpkb) . '&body=[Dissaprove] - gm%0D%0A[notes] - gm';
    //     $linkapprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval] Request Recruitment-' . urlencode($idfpkb) . '&body=[Approve] - gm%0D%0A[notes] - gm';
    //     $to = $_POST['gm'];
    //     // $to = 'malik.aulia@mastersystem.co.id';
    //     $from = "MSIZone<msizone@mastersystem.co.id>";
    //     $cc = "recruitment.team@mastersystem.co.id";
    //     $bcc = "";
    //     $reply = $from;
    //     $subject = "[Permohonan Approval] Request Recruitment - $posisi";
    //     $msg = '<table width="100%">';
    //     $msg .= '    <tr><td width="20%" rowspan="4"></td>';
    //     $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
    //     $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
    //     $msg .= '    <td width="20%" rowspan="4"></tr>';
    //     $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
    //     $msg .= '        <p>Dear ' . $to . '</p>';
    //     $msg .= '        <p>Berikut ini adalah daftar Request Karyawan yang menunggu approval dari Anda.</p>';
    //     $msg .= '        <p>';
    //     $msg .= '<table width="80%">';
    //     $msg .= '<tr><td>FPKB Number</td><td>:</td><td>' . $idfpkb . '</td></tr>';
    //     $msg .= '<tr><td>Requester</td><td>:</td><td>' . $_SESSION['Microservices_UserName'] . '</td></tr>';
    //     $msg .= '<tr><td>Division</td><td>:</td><td>' . $_POST['divisi'] . '</td></tr>';
    //     $msg .= '<tr><td>Position</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
    //     $msg .= '<tr><td>Job Description</td><td>:</td><td>' . $_POST['deskripsi'] . '</td></tr>';
    //     $msg .= '<tr><td>Reason</td><td>:</td><td>' . $_POST['status_rekrutmen'] . '</td></tr>';
    //     $msg .= '<tr><td>Status Employee</td><td>:</td><td>' . $status_karyawan;
    //     if ($status_karyawan == 'Kontrak') {
    //         $msg .= ' ' . $deskripsi_kontrak;
    //     }
    //     $msg .= '</td></tr>';
    //     $msg .= '<tr><td>Source</td><td>:</td><td>' . $_POST['kandidat'] . '</td></tr>';
    //     $msg .= '<tr><td>Range Salary</td><td>:</td><td>' . $rangesalary . '</td></tr>';
    //     $msg .= '</table>';
    //     $msg .= '</p>';

    //     $msg .= '        <p>Base On Project :</p>';
    //     $msg .= '        <p>';
    //     $msg .= '<table width="80%" style="padding: 20px;">';
    //     $msg .= '<tr><td style="width: 20%;">Project Code</td><td style="width: 5%;">:</td><td>' . $_POST['project_code'] . '</td></tr>';
    //     $msg .= '<tr><td style="width: 20%;">Project Name</td><td style="width: 5%;">:</td><td>' . $_POST['nama_project'] . '</td></tr>';
    //     $msg .= '<tr><td style="width: 20%;">Customer</td><td style="width: 5%;">:</td><td>' . $_POST['nama_customer'] . '</td></tr>';
    //     $msg .= '<tr><td style="width: 20%;">Periode Project</td><td style="width: 5%;">:</td><td>' . $_POST['periode_project'] . '</td></tr>';
    //     $msg .= '</table>';
    //     $msg .= '</p>';
    //     $msg .= '<p>Silahkan direview untuk kelayakan mendapatkan approval. Jika Approve silahkan klik tombol Approve atau tombol Reject bila ditolak.</p>';
    //     $msg .= '        <table style="width: 100%; text-align: center;">';  // Aligning the table to center
    //     $msg .= '        <tr>';
    //     $msg .= '        <td style="padding: 10px;">';  // Adding padding around the cells
    //     $msg .= '            <a href="' . $linkapprove . '" style="display: inline-block; padding: 10px 20px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px;">Approve</a>';  // Changed color to blue
    //     $msg .= '        </td>';
    //     $msg .= '        <td style="padding: 10px;">';  // Adding padding around the cells
    //     $msg .= '            <a href="' . $linkdissaprove . '" style="display: inline-block; padding: 10px 20px; background-color: #f44336; color: white; text-decoration: none; border-radius: 5px;">Disapprove</a>';  // Keeping color red
    //     $msg .= '        </td>';
    //     $msg .= '        </tr>';
    //     $msg .= '        </table>';
    //     $msg .= '        <p>Terimakasih</p>';
    //     $msg .= '    </td>';


    //     $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
    //     $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
    //     $msg .= "</table>";
    //     // echo $msg;
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
    // }
    // if ($_POST['status_rekrutmen'] == "Penggantian" && $_POST['status_gm'] == 'Pending') {
    //     $linkdissaprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval] Request Recruitment-' . $idfpkb . '&body=[Dissaprove] - gm%0D%0A[notes] - gm';
    //     $linkapprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval] Request Recruitment-' . $idfpkb . '&body=[Approve] - gm%0D%0A[notes] - gm';
    //     // $to = $entry_by . "," . $_POST['gm'] . "," . $_POST['gm_hcm'] . "," . $_POST['gm_bod'];
    //     // $to = 'malik.aulia@mastersystem.co.id';
    //     $to = $_POST['gm'];
    //     $from = "MSIZone<msizone@mastersystem.co.id>";
    //     $cc = "recruitment.team@mastersystem.co.id";
    //     $bcc = "";
    //     $reply = $from;
    //     $subject = "[Permohonan Approval] Request Recruitment - $posisi";
    //     $msg = '<table width="100%">';
    //     $msg .= '    <tr><td width="20%" rowspan="4"></td>';
    //     $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
    //     $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
    //     $msg .= '    <td width="20%" rowspan="4"></tr>';
    //     $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
    //     $msg .= '        <p>Dear ' . $to . '</p>';
    //     $msg .= '        <p>Berikut ini adalah daftar Request Karyawan yang menunggu approval dari Anda.</p>';
    //     $msg .= '        <p>';
    //     $msg .= '<table width="80%">';
    //     $msg .= '<tr><td>FPKB Number</td><td>:</td><td>' . $idfpkb . '</td></tr>';
    //     $msg .= '<tr><td>Requester</td><td>:</td><td>' . $_SESSION['Microservices_UserName'] . '</td></tr>';
    //     $msg .= '<tr><td>Division</td><td>:</td><td>' . $_POST['divisi'] . '</td></tr>';
    //     $msg .= '<tr><td>Position</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
    //     $msg .= '<tr><td>Job Description</td><td>:</td><td>' . $_POST['deskripsi'] . '</td></tr>';
    //     $msg .= '<tr><td>Reason</td><td>:</td><td>' . $_POST['status_rekrutmen'] . ' (' . $_POST['reason_penggantian'] . ') ' . '</td></tr>';
    //     $msg .= '<tr><td>Status Employee</td><td>:</td><td>' . $status_karyawan;
    //     if ($status_karyawan == 'Kontrak') {
    //         $msg .= ' ' . $deskripsi_kontrak;
    //     }
    //     $msg .= '</td></tr>';
    //     $msg .= '<tr><td>Source</td><td>:</td><td>' . $_POST['kandidat'] . '</td></tr>';
    //     $msg .= '<tr><td>Range Salary</td><td>:</td><td>' . $rangesalary . '</td></tr>';
    //     $msg .= '</table>';
    //     $msg .= '</p>';

    //     $msg .= '        <p>Base On Project :</p>';
    //     $msg .= '        <p>';
    //     $msg .= '<table width="80%" style="padding: 20px;">';
    //     $msg .= '<tr><td style="width: 20%;">Project Code</td><td style="width: 5%;">:</td><td>' . $_POST['project_code'] . '</td></tr>';
    //     $msg .= '<tr><td style="width: 20%;">Project Name</td><td style="width: 5%;">:</td><td>' . $_POST['nama_project'] . '</td></tr>';
    //     $msg .= '<tr><td style="width: 20%;">Customer</td><td style="width: 5%;">:</td><td>' . $_POST['nama_customer'] . '</td></tr>';
    //     $msg .= '<tr><td style="width: 20%;">Periode Project</td><td style="width: 5%;">:</td><td>' . $_POST['periode_project'] . '</td></tr>';
    //     $msg .= '</table>';
    //     $msg .= '</p>';
    //     $msg .= '<p>Silahkan direview untuk kelayakan mendapatkan approval. Jika Approve silahkan klik tombol Approve atau tombol Reject bila ditolak.</p>';
    //     $msg .= '        <table style="width: 100%; text-align: center;">';  // Aligning the table to center
    //     $msg .= '        <tr>';
    //     $msg .= '        <td style="padding: 10px;">';  // Adding padding around the cells
    //     $msg .= '            <a href="' . $linkapprove . '" style="display: inline-block; padding: 10px 20px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px;">Approve</a>';  // Changed color to blue
    //     $msg .= '        </td>';
    //     $msg .= '        <td style="padding: 10px;">';  // Adding padding around the cells
    //     $msg .= '            <a href="' . $linkdissaprove . '" style="display: inline-block; padding: 10px 20px; background-color: #f44336; color: white; text-decoration: none; border-radius: 5px;">Disapprove</a>';  // Keeping color red
    //     $msg .= '        </td>';
    //     $msg .= '        </tr>';
    //     $msg .= '        </table>';
    //     $msg .= '        <p>Terimakasih</p>';
    //     $msg .= '    </td>';


    //     $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
    //     $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
    //     $msg .= "</table>";
    //     // echo $msg;
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
    // }
    $ALERT->savedata();
} elseif (isset($_POST['hold'])) {
    $condition = "id=" . $_POST['id'];
    $update = sprintf(
        "`status_request`=%s",
        GetSQLValueString("Hold", "text")

    );
    $res = $DBHCM->update_data($tblname, $update, $condition);
} elseif (isset($_POST['approve_gm'])) {
    // var_dump($_POST['divisi_edit']);
    // die;
    $condition = "id=" . $_POST['id'];
    $update = sprintf(
        "`status_gm`=%s,`catatan_gm`=%s",
        GetSQLValueString("Approve", "text"),
        GetSQLValueString($_POST['catatan_gm'], "text")

    );
    $res = $DBHCM->update_data($tblname, $update, $condition);
    $description = $_POST['gm'] . " Telah memberikan Approve pada data dengan ID FPKB " . $_POST['id_fpkb'];
    $insert = sprintf(
        "(`id_fpkb`,`project_code`,`project_name`,`description`,`entry_by`) VALUES (%s,%s,%s,%s,%s)",
        GetSQLValueString($_POST['id_fpkb'], "text"),
        GetSQLValueString($_POST['project_code'], "text"),
        GetSQLValueString($_POST['nama_project'], "text"),
        GetSQLValueString($description, "text"),
        GetSQLValueString($_POST['gm'], "text")
    );
    $res = $DBHCM->insert_data("hcm_requirement_log", $insert);
    $idfpkb = $_POST['id_fpkb'];
    // $resultt = $DBHCM->get_sqlV2("SELECT * FROM sa_hcm_requirement WHERE id_fpkb = $idfpkb");
    // var_dump($resultt[0]['id']);
    // die;
    $mdlname = "NOTIFICATION";
    $DBNOTIF = get_conn($mdlname);
    $to = "Wahyuwinningdyah Margareta,S.S.PSI<margareta.sekar@mastersystem.co.id>";
    $from = $_POST['gm'];
    $tblnamenotif = "trx_notification";
    $notifmsg = $idfpkb . "; " . $_POST['project_code'] . "; ";
    $project_name = str_replace(' ', '_', $_POST['nama_project']); // Mengganti spasi dengan underscore
    // $notif_link = "index.php?mod=hcm_requirement&act=editapproval&id=" . $_POST['id'] . "&project_code=" . $_POST['project_code'] . "&submit=Submit";
    $notif_link = "index.php?mod=hcm_requirement&act=edit&id=" . $_POST['id'] . "&project_code=" . $_POST['project_code'] . "&project_name=" . $project_name . "&submit=Submit";
    $insert = sprintf(
        "(`notif_from`, `notif_to`, `notif_subject`, `notif_post`, `notif_link`) VALUES (%s, %s, %s, %s, %s)",
        GetSQLValueString($from, "text"),
        GetSQLValueString($to, "text"),
        GetSQLValueString(ucwords("Approve ID FPKB ") . $idfpkb, "text"),
        GetSQLValueString($notifmsg, "text"),
        GetSQLValueString($notif_link, "text")
    );
    $res = $DBNOTIF->insert_data($tblnamenotif, $insert);
    // var_dump($notif_link);
    // die;
    $to = $_POST['request_by'];
    $from = "MSIZone<msizone@mastersystem.co.id>";
    // $from = "MSIZone<msizone@mastersystem.co.id>";
    $cc = $_POST['gm'] . ',recruitment.team@mastersystem.co.id';
    $bcc = "";
    $reply = $from;
    $subject = "Request Karyawan " . $_POST['posisi'];
    $msg = '<table width="100%">';
    $msg .= '    <tr><td width="20%" rowspan="4"></td>';
    $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
    $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
    $msg .= '    <td width="20%" rowspan="4"></tr>';
    $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
    $msg .= '        <p>Dear ' . $to . '</p>';
    $msg .= '        <p>';


    // $msg .= '<table width="80%">';
    // $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $_POST['id_fpkb'] . '</td></tr>';
    // $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi_edit'] . '</td></tr>';
    // $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
    // $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $_POST['project_code'] . '</td></tr>';
    // $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $_POST['status_rekrutmen_edit'] . '</td></tr>';
    // $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $_POST['status_karyawan_edit'] . '</td></tr>';
    // $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $_POST['kandidat_edit'] . '</td></tr>';
    // $msg .= '</table>';

    $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
    $msg .= '                <tr><td style="width: 20%;">FPKB Number</td><td>:</td><td>' . htmlspecialchars($_POST['id_fpkb']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Requester</td><td>:</td><td>' . htmlspecialchars($_POST['request_by']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Division</td><td>:</td><td>' . htmlspecialchars($_POST['divisi_edit']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Position</td><td>:</td><td>' . htmlspecialchars($_POST['posisi']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Job Description</td><td>:</td><td>' . htmlspecialchars($_POST['deskripsi']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Reason</td><td>:</td><td>' . htmlspecialchars($_POST['status_rekrutmen_edit']);
    if ($_POST['status_rekrutmen_edit'] == 'Penggantian') {
        $msg .= ' ' . htmlspecialchars($_POST['reason_penggantian']);
    }
    $msg .= '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Status Employee</td><td>:</td><td>' . htmlspecialchars($_POST['status_karyawan_edit']);
    if ($_POST['status_karyawan_edit'] == 'Kontrak') {
        $msg .= ' ' . htmlspecialchars($_POST['deskripsi_kontrak']);
    }
    $msg .= '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Source</td><td>:</td><td>' . htmlspecialchars($_POST['kandidat_edit']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Range Salary</td><td>:</td><td>' . htmlspecialchars($_POST['range_salary']) . '</td></tr>';
    $msg .= '            </table>';
    $msg .= '            <p>Base On Project:</p>';
    $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
    $msg .= '                <tr><td style="width: 20%;">Project Code</td><td>:</td><td>' . htmlspecialchars($_POST['project_code']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Project Name</td><td>:</td><td>' . htmlspecialchars($_POST['nama_project']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Customer</td><td>:</td><td>' . htmlspecialchars($_POST['nama_customer']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Periode Project</td><td>:</td><td>' . htmlspecialchars($_POST['periode_project']) . '</td></tr>';
    $msg .= '            </table>';
    $msg .= '            <p>Approval:</p>';
    $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
    $msg .= '                <tr><td style="width: 20%;">By Leader</td><td>:</td><td>' . htmlspecialchars($_POST['gm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_gm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td> Approve </td></tr>';
    $msg .= '            </table>';
    $msg .= '        <table>';

    $msg .= '</p>';
    // $msg .= '<p>Telah mendapatkan Approve dari ' . $_POST['gm'] . '</p>';
    $msg .= '        <table>';
    $msg .= '        <tr>';
    $msg .= '        </table>';
    $msg .= '        <p>Terimakasih</p>';
    $msg .= '    </td>';
    $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
    $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
    $msg .= "</table>";
    // echo $msg;
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
    $to = $_POST['gm_hcm'];
    $from = "MSIZone<msizone@mastersystem.co.id>";
    // $cc = "recruitment.team@mastersystem.co.idco.id";
    $linkdissaprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval] Request Recruitment-' . $_POST['id_fpkb'] . '&body=[Dissaprove] - hcm%0D%0A[notes] - hcm';
    $linkapprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval] Request Recruitment-' . $_POST['id_fpkb'] . '&body=[Approve] - hcm%0D%0A[notes] - hcm';
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

    // $msg .= '        <p>Berikut ini adalah daftar Request Karyawan yang menunggu approval dari Anda.</p>';
    // $msg .= '        <p>';
    // $msg .= '<table width="80%">';
    // $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['id_fpkb'] . '</td></tr>';
    // $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi_edit'] . '</td></tr>';
    // $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
    // $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $_POST['project_code'] . '</td></tr>';
    // $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $_POST['status_rekrutmen_edit'] . '</td></tr>';
    // $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $_POST['status_karyawan_edit'] . '</td></tr>';
    // $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $_POST['kandidat_edit'] . '</td></tr>';
    // $msg .= '</table>';
    // $msg .= '</p>';
    // $msg .= '<p>Silahkan direview untuk kelayakan mendapatkan approval.</p>';
    // $msg .= '        <table>';
    // $msg .= '        <tr>';
    // $msg .= '        <p>Jika Approve silahkan klik link berikut <a href="' . $linkapprove . '">Approve</a> jika Tidak maka klik link berikut <a href="' . $linkdissaprove . '">Dissaprove</a></p>';
    // $msg .= '        </table>';
    // $msg .= '        <p>Terimakasih</p>';


    $msg .= '        <p>Berikut ini adalah daftar Request Karyawan yang menunggu approval dari Anda.</p>';
    $msg .= '        <p>';
    $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
    $msg .= '                <tr><td style="width: 20%;">FPKB Number</td><td>:</td><td>' . htmlspecialchars($_POST['id_fpkb']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Requester</td><td>:</td><td>' . htmlspecialchars($_POST['request_by']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Division</td><td>:</td><td>' . htmlspecialchars($_POST['divisi_edit']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Position</td><td>:</td><td>' . htmlspecialchars($_POST['posisi']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Job Description</td><td>:</td><td>' . htmlspecialchars($_POST['deskripsi']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Reason</td><td>:</td><td>' . htmlspecialchars($_POST['status_rekrutmen_edit']);
    if (
        $_POST['status_rekrutmen_edit'] == 'Penggantian'
    ) {
        $msg .= ' ' . htmlspecialchars($_POST['reason_penggantian']);
    }
    $msg .= '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Status Employee</td><td>:</td><td>' . htmlspecialchars($_POST['status_karyawan_edit']);
    if (
        $_POST['status_karyawan_edit'] == 'Kontrak'
    ) {
        $msg .= ' ' . htmlspecialchars($_POST['deskripsi_kontrak']);
    }
    $msg .= '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Source</td><td>:</td><td>' . htmlspecialchars($_POST['kandidat_edit']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Range Salary</td><td>:</td><td>' . htmlspecialchars($_POST['range_salary']) . '</td></tr>';
    $msg .= '            </table>';
    $msg .= '            <p>Base On Project:</p>';
    $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
    $msg .= '                <tr><td style="width: 20%;">Project Code</td><td>:</td><td>' . htmlspecialchars($_POST['project_code']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Project Name</td><td>:</td><td>' . htmlspecialchars($_POST['nama_project']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Customer</td><td>:</td><td>' . htmlspecialchars($_POST['nama_customer']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Periode Project</td><td>:</td><td>' . htmlspecialchars($_POST['periode_project']) . '</td></tr>';
    $msg .= '            </table>';
    $msg .= '            <p>Approval:</p>';
    $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
    $msg .= '                <tr><td style="width: 20%;">By Leader</td><td>:</td><td>' . htmlspecialchars($_POST['gm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_gm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td> Approve </td></tr>';
    $msg .= '            </table>';

    $msg .= '<p>Silahkan direview untuk kelayakan mendapatkan approval. Jika Approve silahkan klik tombol Approve atau tombol Reject bila ditolak.</p>';
    $msg .= '        <table style="width: 100%; text-align: center;">';  // Aligning the table to center
    $msg .= '        <tr>';
    $msg .= '        <td style="padding: 10px;">';  // Adding padding around the cells
    $msg .= '            <a href="' . $linkapprove . '" style="display: inline-block; padding: 10px 20px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px;">Approve</a>';  // Changed color to blue
    $msg .= '        </td>';
    $msg .= '        <td style="padding: 10px;">';  // Adding padding around the cells
    $msg .= '            <a href="' . $linkdissaprove . '" style="display: inline-block; padding: 10px 20px; background-color: #f44336; color: white; text-decoration: none; border-radius: 5px;">Disapprove</a>';  // Keeping color red
    $msg .= '        </td>';
    $msg .= '        </tr>';
    $msg .= '        </table>';
    $msg .= '        <p>Terimakasih</p>';


    $msg .= '    </td>';
    $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
    $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
    $msg .= "</table>";
    // echo $msg;
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
} elseif (isset($_POST['dissaprove_gm'])) {

    $condition = "id=" . $_POST['id'];
    $update = sprintf(
        "`status_gm`=%s,`catatan_gm`=%s, `status_request`=%s",
        GetSQLValueString("Disapprove", "text"),
        GetSQLValueString($_POST['catatan_gm'], "text"),
        GetSQLValueString("Inactive", "text")

    );
    $res = $DBHCM->update_data($tblname, $update, $condition);
    $description = $_POST['gm'] . " Telah memberikan Disapprove pada data dengan ID FPKB " . $_POST['id_fpkb'];
    $insert = sprintf(
        "(`id_fpkb`,`project_code`,`project_name`,`description`,`entry_by`) VALUES (%s,%s,%s,%s,%s)",
        GetSQLValueString($_POST['id_fpkb'], "text"),
        GetSQLValueString($_POST['project_code'], "text"),
        GetSQLValueString($_POST['nama_project'], "text"),
        GetSQLValueString($description, "text"),
        GetSQLValueString($_POST['gm'], "text")
    );
    $res = $DBHCM->insert_data("hcm_requirement_log", $insert);
    $idfpkb = $_POST['id_fpkb'];
    // $resultt = $DBHCM->get_sqlV2("SELECT * FROM sa_hcm_requirement WHERE id_fpkb = $idfpkb");
    // var_dump($resultt[0]['id']);
    // die;
    $mdlname = "NOTIFICATION";
    $DBNOTIF = get_conn($mdlname);
    $to = "Wahyuwinningdyah Margareta,S.S.PSI<margareta.sekar@mastersystem.co.id>";
    $from = $_POST['gm'];
    $tblnamenotif = "trx_notification";
    $notifmsg = $idfpkb . "; " . $_POST['project_code'] . "; ";
    $project_name = str_replace(' ', '_', $_POST['nama_project']); // Mengganti spasi dengan underscore
    // $notif_link = "index.php?mod=hcm_requirement&act=editapproval&id=" . $_POST['id'] . "&project_code=" . $_POST['project_code'] . "&submit=Submit";
    $notif_link = "index.php?mod=hcm_requirement&act=edit&id=" . $_POST['id'] . "&project_code=" . $_POST['project_code'] . "&project_name=" . $project_name . "&submit=Submit";
    $insert = sprintf(
        "(`notif_from`, `notif_to`, `notif_subject`, `notif_post`, `notif_link`) VALUES (%s, %s, %s, %s, %s)",
        GetSQLValueString($from, "text"),
        GetSQLValueString($to, "text"),
        GetSQLValueString(ucwords("Disapprove ID FPKB ") . $idfpkb, "text"),
        GetSQLValueString($notifmsg, "text"),
        GetSQLValueString($notif_link, "text")
    );
    $res = $DBNOTIF->insert_data($tblnamenotif, $insert);
    // var_dump($notif_link);
    // die;
    $to = $_POST['request_by'];
    $from = "MSIZone<msizone@mastersystem.co.id>";
    // $from = "MSIZone<msizone@mastersystem.co.id>";
    $cc = $_POST['gm'] . ',recruitment.team@mastersystem.co.id';
    $bcc = "";
    $reply = $from;
    $subject = "Request Karyawan " . $_POST['posisi'];
    $msg = '<table width="100%">';
    $msg .= '    <tr><td width="20%" rowspan="4"></td>';
    $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
    $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
    $msg .= '    <td width="20%" rowspan="4"></tr>';
    $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
    $msg .= '        <p>Dear ' . $to . '</p>';
    $msg .= '        <p>';


    // $msg .= '<table width="80%">';
    // $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $_POST['id_fpkb'] . '</td></tr>';
    // $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi_edit'] . '</td></tr>';
    // $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
    // $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $_POST['project_code'] . '</td></tr>';
    // $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $_POST['status_rekrutmen_edit'] . '</td></tr>';
    // $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $_POST['status_karyawan_edit'] . '</td></tr>';
    // $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $_POST['kandidat_edit'] . '</td></tr>';
    // $msg .= '</table>';
    // $msg .= '</p>';


    $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
    $msg .= '                <tr><td style="width: 20%;">FPKB Number</td><td>:</td><td>' . htmlspecialchars($_POST['id_fpkb']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Requester</td><td>:</td><td>' . htmlspecialchars($_POST['request_by']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Division</td><td>:</td><td>' . htmlspecialchars($_POST['divisi_edit']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Position</td><td>:</td><td>' . htmlspecialchars($_POST['posisi']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Job Description</td><td>:</td><td>' . htmlspecialchars($_POST['deskripsi']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Reason</td><td>:</td><td>' . htmlspecialchars($_POST['status_rekrutmen_edit']);
    if ($_POST['status_rekrutmen_edit'] == 'Penggantian') {
        $msg .= ' ' . htmlspecialchars($_POST['reason_penggantian']);
    }
    $msg .= '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Status Employee</td><td>:</td><td>' . htmlspecialchars($_POST['status_karyawan_edit']);
    if ($_POST['status_karyawan_edit'] == 'Kontrak') {
        $msg .= ' ' . htmlspecialchars($_POST['deskripsi_kontrak']);
    }
    $msg .= '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Source</td><td>:</td><td>' . htmlspecialchars($_POST['kandidat_edit']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Range Salary</td><td>:</td><td>' . htmlspecialchars($_POST['range_salary']) . '</td></tr>';
    $msg .= '            </table>';
    $msg .= '            <p>Base On Project:</p>';
    $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
    $msg .= '                <tr><td style="width: 20%;">Project Code</td><td>:</td><td>' . htmlspecialchars($_POST['project_code']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Project Name</td><td>:</td><td>' . htmlspecialchars($_POST['nama_project']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Customer</td><td>:</td><td>' . htmlspecialchars($_POST['nama_customer']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Periode Project</td><td>:</td><td>' . htmlspecialchars($_POST['periode_project']) . '</td></tr>';
    $msg .= '            </table>';
    $msg .= '            <p>Approval:</p>';
    $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
    $msg .= '                <tr><td style="width: 20%;">By Leader</td><td>:</td><td>' . htmlspecialchars($_POST['gm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_gm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td> Disapprove </td></tr>';
    $msg .= '            </table>';
    $msg .= '        <table>';

    $msg .= '</p>';



    // $msg .= '<p>Telah mendapatkan Disapprove dari ' . $_POST['gm'] . '</p>';
    $msg .= '        <table>';
    $msg .= '        <tr>';
    $msg .= '        </table>';
    $msg .= '        <p>Terimakasih</p>';
    $msg .= '    </td>';
    $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
    $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
    $msg .= "</table>";
    // echo $msg;
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
} elseif (isset($_POST['approve_gm_hcm'])) {
    if ($_POST['status_rekrutmen_edit'] == 'Penambahan') {
        $condition = "id=" . $_POST['id'];
        $update = sprintf(
            "`status_gm_hcm`=%s,`catatan_gm_hcm`=%s",
            GetSQLValueString("Approve", "text"),
            GetSQLValueString($_POST['catatan_gm_hcm'], "text")

        );
        $res = $DBHCM->update_data($tblname, $update, $condition);
        $description = $_POST['gm_hcm'] . " Telah memberikan Approve pada data dengan ID FPKB " . $_POST['id_fpkb'];
        $insert = sprintf(
            "(`id_fpkb`,`project_code`,`project_name`,`description`,`entry_by`) VALUES (%s,%s,%s,%s,%s)",
            GetSQLValueString($_POST['id_fpkb'], "text"),
            GetSQLValueString($_POST['project_code'], "text"),
            GetSQLValueString($_POST['nama_project'], "text"),
            GetSQLValueString($description, "text"),
            GetSQLValueString($_POST['gm_hcm'], "text")
        );
        $res = $DBHCM->insert_data("hcm_requirement_log", $insert);
        $idfpkb = $_POST['id_fpkb'];
        // $resultt = $DBHCM->get_sqlV2("SELECT * FROM sa_hcm_requirement WHERE id_fpkb = $idfpkb");
        // var_dump($resultt[0]['id']);
        // die;
        $mdlname = "NOTIFICATION";
        $DBNOTIF = get_conn($mdlname);
        $to = "Wahyuwinningdyah Margareta,S.S.PSI<margareta.sekar@mastersystem.co.id>";
        $from = $_POST['gm_hcm'];
        $tblnamenotif = "trx_notification";
        $notifmsg = $idfpkb . "; " . $_POST['project_code'] . "; ";
        $project_name = str_replace(' ', '_', $_POST['nama_project']); // Mengganti spasi dengan underscore
        // $notif_link = "index.php?mod=hcm_requirement&act=editapproval&id=" . $_POST['id'] . "&project_code=" . $_POST['project_code'] . "&submit=Submit";
        $notif_link = "index.php?mod=hcm_requirement&act=edit&id=" . $_POST['id'] . "&project_code=" . $_POST['project_code'] . "&project_name=" . $project_name . "&submit=Submit";
        $insert = sprintf(
            "(`notif_from`, `notif_to`, `notif_subject`, `notif_post`, `notif_link`) VALUES (%s, %s, %s, %s, %s)",
            GetSQLValueString($from, "text"),
            GetSQLValueString($to, "text"),
            GetSQLValueString(ucwords("Approve ID FPKB ") . $idfpkb, "text"),
            GetSQLValueString($notifmsg, "text"),
            GetSQLValueString($notif_link, "text")
        );
        $res = $DBNOTIF->insert_data($tblnamenotif, $insert);

        $to = $_POST['request_by'];
        $from = "MSIZone<msizone@mastersystem.co.id>";
        // $from = "MSIZone<msizone@mastersystem.co.id>";
        // $linkdissaprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval] Request Recruitment-' . $_POST['id_fpkb'] . '&&body=[Dissaprove] - bod%0D%0A[notes] - bod';
        // $linkapprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval] Request Recruitment-' . $_POST['id_fpkb'] . '&body=[Approve] - bod%0D%0A[notes] - bod';
        $cc =  $_POST['gm_hcm'] . ',' . $_POST['gm'] . ',recruitment.team@mastersystem.co.id';
        $bcc = "";
        $reply = $from;
        $subject = "Request Karyawan " . $_POST['posisi'];
        $msg = '<table width="100%">';
        $msg .= '    <tr><td width="20%" rowspan="4"></td>';
        $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
        $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
        $msg .= '    <td width="20%" rowspan="4"></tr>';
        $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
        $msg .= '        <p>Dear ' . $to . '</p>';
        $msg .= '        <p>';


        // $msg .= '<table width="80%">';
        // $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $_POST['id_fpkb'] . '</td></tr>';
        // $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi_edit'] . '</td></tr>';
        // $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
        // $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $_POST['project_code'] . '</td></tr>';
        // $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $_POST['status_rekrutmen_edit'] . '</td></tr>';
        // $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $_POST['status_karyawan_edit'] . '</td></tr>';
        // $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $_POST['kandidat_edit'] . '</td></tr>';
        // $msg .= '</table>';
        // $msg .= '</p>';


        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
        $msg .= '                <tr><td style="width: 20%;">FPKB Number</td><td>:</td><td>' . htmlspecialchars($_POST['id_fpkb']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Requester</td><td>:</td><td>' . htmlspecialchars($_POST['request_by']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Division</td><td>:</td><td>' . htmlspecialchars($_POST['divisi_edit']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Position</td><td>:</td><td>' . htmlspecialchars($_POST['posisi']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Job Description</td><td>:</td><td>' . htmlspecialchars($_POST['deskripsi']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Reason</td><td>:</td><td>' . htmlspecialchars($_POST['status_rekrutmen_edit']);
        if ($_POST['status_rekrutmen_edit'] == 'Penggantian') {
            $msg .= ' ' . htmlspecialchars($_POST['reason_penggantian']);
        }
        $msg .= '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status Employee</td><td>:</td><td>' . htmlspecialchars($_POST['status_karyawan_edit']);
        if ($_POST['status_karyawan_edit'] == 'Kontrak') {
            $msg .= ' ' . htmlspecialchars($_POST['deskripsi_kontrak']);
        }
        $msg .= '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Source</td><td>:</td><td>' . htmlspecialchars($_POST['kandidat_edit']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Range Salary</td><td>:</td><td>' . htmlspecialchars($_POST['range_salary']) . '</td></tr>';
        $msg .= '            </table>';
        $msg .= '            <p>Base On Project:</p>';
        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
        $msg .= '                <tr><td style="width: 20%;">Project Code</td><td>:</td><td>' . htmlspecialchars($_POST['project_code']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Project Name</td><td>:</td><td>' . htmlspecialchars($_POST['nama_project']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Customer</td><td>:</td><td>' . htmlspecialchars($_POST['nama_customer']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Periode Project</td><td>:</td><td>' . htmlspecialchars($_POST['periode_project']) . '</td></tr>';
        $msg .= '            </table>';
        $msg .= '            <p>Approval:</p>';
        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
        $msg .= '                <tr><td style="width: 20%;">By Leader</td><td>:</td><td>' . htmlspecialchars($_POST['gm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_gm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td>' . htmlspecialchars($_POST['status_gm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">By GM HCM</td><td>:</td><td>' . htmlspecialchars($_POST['gm_hcm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_gm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td> Approve </td></tr>';
        $msg .= '            </table>';
        $msg .= '        <table>';

        $msg .= '</p>';
        $msg .= '        <p>Terimakasih</p>';
        $msg .= '    </td>';
        $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
        $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
        $msg .= "</table>";
        // echo $msg;
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


        $to = $_POST['gm_bod'];
        $from = "MSIZone<msizone@mastersystem.co.id>";
        // $from = "MSIZone<msizone@mastersystem.co.id>";
        $linkdissaprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval] Request Recruitment-' . $_POST['id_fpkb'] . '&&body=[Dissaprove] - bod%0D%0A[notes] - bod';
        $linkapprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval] Request Recruitment-' . $_POST['id_fpkb'] . '&body=[Approve] - bod%0D%0A[notes] - bod';
        $cc =  $_POST['gm_hcm'] . ',' . $_POST['gm'] . ',recruitment.team@mastersystem.co.id';
        $bcc = "";
        $reply = $from;
        $subject = "Request Karyawan " . $_POST['posisi'];
        $msg = '<table width="100%">';
        $msg .= '    <tr><td width="20%" rowspan="4"></td>';
        $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
        $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
        $msg .= '    <td width="20%" rowspan="4"></tr>';
        $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
        $msg .= '        <p>Dear ' . $to . '</p>';
        $msg .= '        <p>';


        // $msg .= '<table width="80%">';
        // $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $_POST['id_fpkb'] . '</td></tr>';
        // $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi_edit'] . '</td></tr>';
        // $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
        // $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $_POST['project_code'] . '</td></tr>';
        // $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $_POST['status_rekrutmen_edit'] . '</td></tr>';
        // $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $_POST['status_karyawan_edit'] . '</td></tr>';
        // $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $_POST['kandidat_edit'] . '</td></tr>';
        // $msg .= '</table>';
        // $msg .= '</p>';


        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
        $msg .= '                <tr><td style="width: 20%;">FPKB Number</td><td>:</td><td>' . htmlspecialchars($_POST['id_fpkb']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Requester</td><td>:</td><td>' . htmlspecialchars($_POST['request_by']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Division</td><td>:</td><td>' . htmlspecialchars($_POST['divisi_edit']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Position</td><td>:</td><td>' . htmlspecialchars($_POST['posisi']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Job Description</td><td>:</td><td>' . htmlspecialchars($_POST['deskripsi']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Reason</td><td>:</td><td>' . htmlspecialchars($_POST['status_rekrutmen_edit']);
        if ($_POST['status_rekrutmen_edit'] == 'Penggantian') {
            $msg .= ' ' . htmlspecialchars($_POST['reason_penggantian']);
        }
        $msg .= '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status Employee</td><td>:</td><td>' . htmlspecialchars($_POST['status_karyawan_edit']);
        if ($_POST['status_karyawan_edit'] == 'Kontrak') {
            $msg .= ' ' . htmlspecialchars($_POST['deskripsi_kontrak']);
        }
        $msg .= '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Source</td><td>:</td><td>' . htmlspecialchars($_POST['kandidat_edit']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Range Salary</td><td>:</td><td>' . htmlspecialchars($_POST['range_salary']) . '</td></tr>';
        $msg .= '            </table>';
        $msg .= '            <p>Base On Project:</p>';
        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
        $msg .= '                <tr><td style="width: 20%;">Project Code</td><td>:</td><td>' . htmlspecialchars($_POST['project_code']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Project Name</td><td>:</td><td>' . htmlspecialchars($_POST['nama_project']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Customer</td><td>:</td><td>' . htmlspecialchars($_POST['nama_customer']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Periode Project</td><td>:</td><td>' . htmlspecialchars($_POST['periode_project']) . '</td></tr>';
        $msg .= '            </table>';
        $msg .= '            <p>Approval:</p>';
        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
        $msg .= '                <tr><td style="width: 20%;">By Leader</td><td>:</td><td>' . htmlspecialchars($_POST['gm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_gm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td>' . htmlspecialchars($_POST['status_gm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">By GM HCM</td><td>:</td><td>' . htmlspecialchars($_POST['gm_hcm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_gm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td> Approve </td></tr>';
        $msg .= '            </table>';
        $msg .= '        <table>';

        $msg .= '</p>';
        $msg .= '<p>Silahkan direview untuk kelayakan mendapatkan approval. Jika Approve silahkan klik tombol Approve atau tombol Reject bila ditolak.</p>';
        $msg .= '        <table style="width: 100%; text-align: center;">';  // Aligning the table to center
        $msg .= '        <tr>';
        $msg .= '        <td style="padding: 10px;">';  // Adding padding around the cells
        $msg .= '            <a href="' . $linkapprove . '" style="display: inline-block; padding: 10px 20px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px;">Approve</a>';  // Changed color to blue
        $msg .= '        </td>';
        $msg .= '        <td style="padding: 10px;">';  // Adding padding around the cells
        $msg .= '            <a href="' . $linkdissaprove . '" style="display: inline-block; padding: 10px 20px; background-color: #f44336; color: white; text-decoration: none; border-radius: 5px;">Disapprove</a>';  // Keeping color red
        $msg .= '        </td>';
        $msg .= '        </tr>';
        $msg .= '        </table>';
        $msg .= '        <p>Terimakasih</p>';
        $msg .= '    </td>';
        $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
        $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
        $msg .= "</table>";
        // echo $msg;
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
    } elseif ($_POST['status_rekrutmen_edit'] == 'Penggantian') {
        $condition = "id=" . $_POST['id'];
        $update = sprintf(
            "`status_gm_hcm`=%s,`catatan_gm_hcm`=%s, `status_request`=%s",
            GetSQLValueString("Approve", "text"),
            GetSQLValueString($_POST['catatan_gm_hcm'], "text"),
            GetSQLValueString("Submitted", "text")

        );
        $res = $DBHCM->update_data($tblname, $update, $condition);
        $description = $_POST['gm_hcm'] . " Telah memberikan Approve pada data dengan ID FPKB " . $_POST['id_fpkb'];
        $insert = sprintf(
            "(`id_fpkb`,`project_code`,`project_name`,`description`,`entry_by`) VALUES (%s,%s,%s,%s,%s)",
            GetSQLValueString($_POST['id_fpkb'], "text"),
            GetSQLValueString($_POST['project_code'], "text"),
            GetSQLValueString($_POST['nama_project'], "text"),
            GetSQLValueString($description, "text"),
            GetSQLValueString($_POST['gm_hcm'], "text")
        );
        $res = $DBHCM->insert_data("hcm_requirement_log", $insert);
        $idfpkb = $_POST['id_fpkb'];
        // $resultt = $DBHCM->get_sqlV2("SELECT * FROM sa_hcm_requirement WHERE id_fpkb = $idfpkb");
        // var_dump($resultt[0]['id']);
        // die;
        $mdlname = "NOTIFICATION";
        $DBNOTIF = get_conn($mdlname);
        $to = "Wahyuwinningdyah Margareta,S.S.PSI<margareta.sekar@mastersystem.co.id>";
        $from = $_POST['gm_hcm'];
        $tblnamenotif = "trx_notification";
        $notifmsg = $idfpkb . "; " . $_POST['project_code'] . "; ";
        $project_name = str_replace(' ', '_', $_POST['nama_project']); // Mengganti spasi dengan underscore
        // $notif_link = "index.php?mod=hcm_requirement&act=editapproval&id=" . $_POST['id'] . "&project_code=" . $_POST['project_code'] . "&submit=Submit";
        $notif_link = "index.php?mod=hcm_requirement&act=view&id=" . $_POST['id'] . "&submit=Submit";
        $insert = sprintf(
            "(`notif_from`, `notif_to`, `notif_subject`, `notif_post`, `notif_link`) VALUES (%s, %s, %s, %s, %s)",
            GetSQLValueString($from, "text"),
            GetSQLValueString($to, "text"),
            GetSQLValueString(ucwords("Approve ID FPKB ") . $idfpkb, "text"),
            GetSQLValueString($notifmsg, "text"),
            GetSQLValueString($notif_link, "text")
        );
        $res = $DBNOTIF->insert_data($tblnamenotif, $insert);
        // var_dump($notif_link);
        // die;
        $to = $_POST['request_by'];
        $from = "MSIZone<msizone@mastersystem.co.id>";
        // $from = "MSIZone<msizone@mastersystem.co.id>";
        $cc = $_POST['gm_hcm'] . ',' . $_POST['gm'] . ',recruitment.team@mastersystem.co.id';
        $bcc = "";
        $reply = $from;
        $subject = "Request Karyawan " . $_POST['posisi'];
        $msg = '<table width="100%">';
        $msg .= '    <tr><td width="20%" rowspan="4"></td>';
        $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
        $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
        $msg .= '    <td width="20%" rowspan="4"></tr>';
        $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
        $msg .= '        <p>Dear ' . $to . '</p>';
        $msg .= '        <p>';
        // $msg .= '<table width="80%">';
        // $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $_POST['id_fpkb'] . '</td></tr>';
        // $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi_edit'] . '</td></tr>';
        // $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
        // $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $_POST['project_code'] . '</td></tr>';
        // $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $_POST['status_rekrutmen_edit'] . '</td></tr>';
        // $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $_POST['status_karyawan_edit'] . '</td></tr>';
        // $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $_POST['kandidat_edit'] . '</td></tr>';
        // $msg .= '</table>';
        // $msg .= '</p>';


        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
        $msg .= '                <tr><td style="width: 20%;">FPKB Number</td><td>:</td><td>' . htmlspecialchars($_POST['id_fpkb']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Requester</td><td>:</td><td>' . htmlspecialchars($_POST['request_by']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Division</td><td>:</td><td>' . htmlspecialchars($_POST['divisi_edit']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Position</td><td>:</td><td>' . htmlspecialchars($_POST['posisi']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Job Description</td><td>:</td><td>' . htmlspecialchars($_POST['deskripsi']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Reason</td><td>:</td><td>' . htmlspecialchars($_POST['status_rekrutmen_edit']);
        if ($_POST['status_rekrutmen_edit'] == 'Penggantian') {
            $msg .= ' ' . htmlspecialchars($_POST['reason_penggantian']);
        }
        $msg .= '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status Employee</td><td>:</td><td>' . htmlspecialchars($_POST['status_karyawan_edit']);
        if ($_POST['status_karyawan_edit'] == 'Kontrak') {
            $msg .= ' ' . htmlspecialchars($_POST['deskripsi_kontrak']);
        }
        $msg .= '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Source</td><td>:</td><td>' . htmlspecialchars($_POST['kandidat_edit']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Range Salary</td><td>:</td><td>' . htmlspecialchars($_POST['range_salary']) . '</td></tr>';
        $msg .= '            </table>';
        $msg .= '            <p>Base On Project:</p>';
        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
        $msg .= '                <tr><td style="width: 20%;">Project Code</td><td>:</td><td>' . htmlspecialchars($_POST['project_code']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Project Name</td><td>:</td><td>' . htmlspecialchars($_POST['nama_project']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Customer</td><td>:</td><td>' . htmlspecialchars($_POST['nama_customer']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Periode Project</td><td>:</td><td>' . htmlspecialchars($_POST['periode_project']) . '</td></tr>';
        $msg .= '            </table>';
        $msg .= '            <p>Approval:</p>';
        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
        $msg .= '                <tr><td style="width: 20%;">By Leader</td><td>:</td><td>' . htmlspecialchars($_POST['gm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_gm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td>' . htmlspecialchars($_POST['status_gm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">By GM HCM</td><td>:</td><td>' . htmlspecialchars($_POST['gm_hcm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_gm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td> Approve </td></tr>';
        $msg .= '            </table>';
        $msg .= '        <table>';

        $msg .= '</p>';

        // $msg .= '<p>Telah mendapatkan Approve dari ' . $_POST['gm_hcm'] . '</p>';
        $msg .= '        <table>';
        $msg .= '        <tr>';
        $msg .= '        </table>';
        $msg .= '        <p>Terimakasih</p>';
        $msg .= '    </td>';
        $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
        $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
        $msg .= "</table>";
        // echo $msg;
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
        // $to = 'Malik Aulia Wiratama <malik.aulia@mastersystem.co.id>';
        $to = 'margareta.sekar@mastersystem.co.id';
        $from = $_POST['gm_hcm'];
        // $cc = "recruitment.team@mastersystem.co.id";
        $cc = 'recruitment.team@mastersystem.co.id';
        $kp = $_POST['project_code'];
        $id = $_POST['id'];
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
        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
        $msg .= '                <tr><td style="width: 20%;">FPKB Number</td><td>:</td><td>' . htmlspecialchars($_POST['id_fpkb']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Requester</td><td>:</td><td>' . htmlspecialchars($_POST['request_by']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Division</td><td>:</td><td>' . htmlspecialchars($_POST['divisi_edit']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Position</td><td>:</td><td>' . htmlspecialchars($_POST['posisi']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Job Description</td><td>:</td><td>' . htmlspecialchars($_POST['deskripsi']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Reason</td><td>:</td><td>' . htmlspecialchars($_POST['status_rekrutmen_edit']);
        if ($_POST['status_rekrutmen_edit'] == 'Penggantian') {
            $msg .= ' ' . htmlspecialchars($_POST['reason_penggantian']);
        }
        $msg .= '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status Employee</td><td>:</td><td>' . htmlspecialchars($_POST['status_karyawan_edit']);
        if ($_POST['status_karyawan_edit'] == 'Kontrak') {
            $msg .= ' ' . htmlspecialchars($_POST['deskripsi_kontrak']);
        }
        $msg .= '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Source</td><td>:</td><td>' . htmlspecialchars($_POST['kandidat_edit']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Range Salary</td><td>:</td><td>' . htmlspecialchars($_POST['range_salary']) . '</td></tr>';
        $msg .= '            </table>';
        $msg .= '            <p>Base On Project:</p>';
        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
        $msg .= '                <tr><td style="width: 20%;">Project Code</td><td>:</td><td>' . htmlspecialchars($_POST['project_code']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Project Name</td><td>:</td><td>' . htmlspecialchars($_POST['nama_project']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Customer</td><td>:</td><td>' . htmlspecialchars($_POST['nama_customer']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Periode Project</td><td>:</td><td>' . htmlspecialchars($_POST['periode_project']) . '</td></tr>';
        $msg .= '            </table>';
        $msg .= '            <p>Approval:</p>';
        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
        $msg .= '                <tr><td style="width: 20%;">By Leader</td><td>:</td><td>' . htmlspecialchars($_POST['gm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_gm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td>' . htmlspecialchars($_POST['status_gm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">By GM HCM</td><td>:</td><td>' . htmlspecialchars($_POST['gm_hcm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_gm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td> Approve </td></tr>';
        $msg .= '            </table>';
        $msg .= '        <table>';

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
        // echo $msg;
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
        $notifmsg = $_POST['id_fpkb'] . "; " . $_POST['project_code'] . "; ";
        $notif_link = "index.php?mod=hcm_requirement&act=editapproval&id=" . $_POST['id'] . "&project_code=" . $_POST['project_code'] . "&submit=Submit";
        $insert = sprintf(
            "(`notif_from`, `notif_to`, `notif_subject`, `notif_post`, `notif_link`) VALUES (%s, %s, %s, %s, %s)",
            GetSQLValueString($from, "text"),
            GetSQLValueString($to, "text"),
            GetSQLValueString(ucwords("Submitted ID FPKB ") . $_POST['id_fpkb'], "text"),
            GetSQLValueString($notifmsg, "text"),
            GetSQLValueString($notif_link, "text")
        );

        $res = $DBNOTIF->insert_data($tblnamenotif, $insert);
    }
} elseif (isset($_POST['dissaprove_gm_hcm'])) {

    $condition = "id=" . $_POST['id'];
    $update = sprintf(
        "`status_gm_hcm`=%s,`catatan_gm_hcm`=%s, `status_request`=%s",
        GetSQLValueString("Disapprove", "text"),
        GetSQLValueString($_POST['catatan_gm_hcm'], "text"),
        GetSQLValueString("Inactive", "text")

    );
    $res = $DBHCM->update_data($tblname, $update, $condition);
    $description = $_POST['gm_hcm'] . " Telah memberikan Disapprove pada data dengan ID FPKB " . $_POST['id_fpkb'];
    $insert = sprintf(
        "(`id_fpkb`,`project_code`,`project_name`,`description`,`entry_by`) VALUES (%s,%s,%s,%s,%s)",
        GetSQLValueString($_POST['id_fpkb'], "text"),
        GetSQLValueString($_POST['project_code'], "text"),
        GetSQLValueString($_POST['nama_project'], "text"),
        GetSQLValueString($description, "text"),
        GetSQLValueString($_POST['gm_hcm'], "text")
    );
    $res = $DBHCM->insert_data("hcm_requirement_log", $insert);
    $idfpkb = $_POST['id_fpkb'];
    // $resultt = $DBHCM->get_sqlV2("SELECT * FROM sa_hcm_requirement WHERE id_fpkb = $idfpkb");
    // var_dump($resultt[0]['id']);
    // die;
    $mdlname = "NOTIFICATION";
    $DBNOTIF = get_conn($mdlname);
    $to = "Wahyuwinningdyah Margareta,S.S.PSI<margareta.sekar@mastersystem.co.id>";
    $from = $_POST['gm_hcm'];
    $tblnamenotif = "trx_notification";
    $notifmsg = $idfpkb . "; " . $_POST['project_code'] . "; ";
    $project_name = str_replace(' ', '_', $_POST['nama_project']); // Mengganti spasi dengan underscore
    // $notif_link = "index.php?mod=hcm_requirement&act=editapproval&id=" . $_POST['id'] . "&project_code=" . $_POST['project_code'] . "&submit=Submit";
    $notif_link = "index.php?mod=hcm_requirement&act=edit&id=" . $_POST['id'] . "&project_code=" . $_POST['project_code'] . "&project_name=" . $project_name . "&submit=Submit";
    $insert = sprintf(
        "(`notif_from`, `notif_to`, `notif_subject`, `notif_post`, `notif_link`) VALUES (%s, %s, %s, %s, %s)",
        GetSQLValueString($from, "text"),
        GetSQLValueString($to, "text"),
        GetSQLValueString(ucwords("Disapprove ID FPKB ") . $idfpkb, "text"),
        GetSQLValueString($notifmsg, "text"),
        GetSQLValueString($notif_link, "text")
    );
    $res = $DBNOTIF->insert_data($tblnamenotif, $insert);
    $to = $_POST['request_by'];
    $from = "MSIZone<msizone@mastersystem.co.id>";
    // $from = "MSIZone<msizone@mastersystem.co.id>";
    $cc = $_POST['gm_hcm'] . ',' . $_POST['gm'] . ',recruitment.team@mastersystem.co.id';
    $bcc = "";
    $reply = $from;
    $subject = "Request Karyawan " . $_POST['posisi'];
    $msg = '<table width="100%">';
    $msg .= '    <tr><td width="20%" rowspan="4"></td>';
    $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
    $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
    $msg .= '    <td width="20%" rowspan="4"></tr>';
    $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
    $msg .= '        <p>Dear ' . $to . '</p>';
    $msg .= '        <p>';
    // $msg .= '<table width="80%">';
    // $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $_POST['id_fpkb'] . '</td></tr>';
    // $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi_edit'] . '</td></tr>';
    // $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
    // $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $_POST['project_code'] . '</td></tr>';
    // $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $_POST['status_rekrutmen_edit'] . '</td></tr>';
    // $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $_POST['status_karyawan_edit'] . '</td></tr>';
    // $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $_POST['kandidat_edit'] . '</td></tr>';
    // $msg .= '</table>';
    // $msg .= '</p>';


    $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
    $msg .= '                <tr><td style="width: 20%;">FPKB Number</td><td>:</td><td>' . htmlspecialchars($_POST['id_fpkb']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Requester</td><td>:</td><td>' . htmlspecialchars($_POST['request_by']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Division</td><td>:</td><td>' . htmlspecialchars($_POST['divisi_edit']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Position</td><td>:</td><td>' . htmlspecialchars($_POST['posisi']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Job Description</td><td>:</td><td>' . htmlspecialchars($_POST['deskripsi']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Reason</td><td>:</td><td>' . htmlspecialchars($_POST['status_rekrutmen_edit']);
    if ($_POST['status_rekrutmen_edit'] == 'Penggantian') {
        $msg .= ' ' . htmlspecialchars($_POST['reason_penggantian']);
    }
    $msg .= '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Status Employee</td><td>:</td><td>' . htmlspecialchars($_POST['status_karyawan_edit']);
    if ($_POST['status_karyawan_edit'] == 'Kontrak') {
        $msg .= ' ' . htmlspecialchars($_POST['deskripsi_kontrak']);
    }
    $msg .= '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Source</td><td>:</td><td>' . htmlspecialchars($_POST['kandidat_edit']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Range Salary</td><td>:</td><td>' . htmlspecialchars($_POST['range_salary']) . '</td></tr>';
    $msg .= '            </table>';
    $msg .= '            <p>Base On Project:</p>';
    $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
    $msg .= '                <tr><td style="width: 20%;">Project Code</td><td>:</td><td>' . htmlspecialchars($_POST['project_code']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Project Name</td><td>:</td><td>' . htmlspecialchars($_POST['nama_project']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Customer</td><td>:</td><td>' . htmlspecialchars($_POST['nama_customer']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Periode Project</td><td>:</td><td>' . htmlspecialchars($_POST['periode_project']) . '</td></tr>';
    $msg .= '            </table>';
    $msg .= '            <p>Approval:</p>';
    $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
    $msg .= '                <tr><td style="width: 20%;">By Leader</td><td>:</td><td>' . htmlspecialchars($_POST['gm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_gm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td>' . htmlspecialchars($_POST['status_gm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">By GM HCM</td><td>:</td><td>' . htmlspecialchars($_POST['gm_hcm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_gm_hcm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td> Disapprove </td></tr>';
    $msg .= '            </table>';
    $msg .= '        <table>';

    $msg .= '</p>';


    // $msg .= '<p>Telah mendapatkan Disapprove dari ' . $_POST['gm_hcm'] . '</p>';
    $msg .= '        <table>';
    $msg .= '        <tr>';
    $msg .= '        </table>';
    $msg .= '        <p>Terimakasih</p>';
    $msg .= '    </td>';
    $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
    $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
    $msg .= "</table>";
    // echo $msg;
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
} elseif (isset($_POST['approve_gm_bod'])) {
    $condition = "id=" . $_POST['id'];
    $update = sprintf(
        "`status_gm_bod`=%s,`catatan_gm_bod`=%s,`status_request`=%s",
        GetSQLValueString("Approve", "text"),
        GetSQLValueString($_POST['catatan_gm_bod'], "text"),
        GetSQLValueString("Submitted", "text")

    );
    $res = $DBHCM->update_data($tblname, $update, $condition);
    $description = $_POST['gm_bod'] . " Telah memberikan Approve pada data dengan ID FPKB " . $_POST['id_fpkb'];
    $insert = sprintf(
        "(`id_fpkb`,`project_code`,`project_name`,`description`,`entry_by`) VALUES (%s,%s,%s,%s,%s)",
        GetSQLValueString($_POST['id_fpkb'], "text"),
        GetSQLValueString($_POST['project_code'], "text"),
        GetSQLValueString($_POST['nama_project'], "text"),
        GetSQLValueString($description, "text"),
        GetSQLValueString($_POST['gm_bod'], "text")
    );
    $res = $DBHCM->insert_data("hcm_requirement_log", $insert);
    $idfpkb = $_POST['id_fpkb'];
    // $resultt = $DBHCM->get_sqlV2("SELECT * FROM sa_hcm_requirement WHERE id_fpkb = $idfpkb");
    // var_dump($resultt[0]['id']);
    // die;
    $mdlname = "NOTIFICATION";
    $DBNOTIF = get_conn($mdlname);
    $to = "Wahyuwinningdyah Margareta,S.S.PSI<margareta.sekar@mastersystem.co.id>";
    $from = $_POST['gm_bod'];
    $tblnamenotif = "trx_notification";
    $notifmsg = $idfpkb . "; " . $_POST['project_code'] . "; ";
    $project_name = str_replace(' ', '_', $_POST['nama_project']); // Mengganti spasi dengan underscore
    // $notif_link = "index.php?mod=hcm_requirement&act=editapproval&id=" . $_POST['id'] . "&project_code=" . $_POST['project_code'] . "&submit=Submit";
    $notif_link = "index.php?mod=hcm_requirement&act=view&id=" . $_POST['id'] . "&submit=Submit";
    $insert = sprintf(
        "(`notif_from`, `notif_to`, `notif_subject`, `notif_post`, `notif_link`) VALUES (%s, %s, %s, %s, %s)",
        GetSQLValueString($from, "text"),
        GetSQLValueString($to, "text"),
        GetSQLValueString(ucwords("Approve ID FPKB ") . $idfpkb, "text"),
        GetSQLValueString($notifmsg, "text"),
        GetSQLValueString($notif_link, "text")
    );
    $res = $DBNOTIF->insert_data($tblnamenotif, $insert);
    // var_dump($notif_link);
    // die;
    $to = $_POST['request_by'];
    $from = "MSIZone<msizone@mastersystem.co.id>";
    // $from = "MSIZone<msizone@mastersystem.co.id>";
    $cc = $_POST['gm_bod'] . ',' . $_POST['gm_hcm'] . ',' . $_POST['gm'] . ',recruitment.team@mastersystem.co.id';
    $bcc = "";
    $reply = $from;
    $subject = "Request Karyawan " . $_POST['posisi'];
    $msg = '<table width="100%">';
    $msg .= '    <tr><td width="20%" rowspan="4"></td>';
    $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
    $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
    $msg .= '    <td width="20%" rowspan="4"></tr>';
    $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
    $msg .= '        <p>Dear ' . $to . '</p>';
    $msg .= '        <p>';
    // $msg .= '<table width="80%">';
    // $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $_POST['id_fpkb'] . '</td></tr>';
    // $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi_edit'] . '</td></tr>';
    // $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
    // $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $_POST['project_code'] . '</td></tr>';
    // $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $_POST['status_rekrutmen_edit'] . '</td></tr>';
    // $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $_POST['status_karyawan_edit'] . '</td></tr>';
    // $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $_POST['kandidat_edit'] . '</td></tr>';
    // $msg .= '</table>';
    // $msg .= '</p>';



    $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
    $msg .= '                <tr><td style="width: 20%;">FPKB Number</td><td>:</td><td>' . htmlspecialchars($_POST['id_fpkb']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Requester</td><td>:</td><td>' . htmlspecialchars($_POST['request_by']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Division</td><td>:</td><td>' . htmlspecialchars($_POST['divisi_edit']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Position</td><td>:</td><td>' . htmlspecialchars($_POST['posisi']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Job Description</td><td>:</td><td>' . htmlspecialchars($_POST['deskripsi']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Reason</td><td>:</td><td>' . htmlspecialchars($_POST['status_rekrutmen_edit']);
    if ($_POST['status_rekrutmen_edit'] == 'Penggantian') {
        $msg .= ' ' . htmlspecialchars($_POST['reason_penggantian']);
    }
    $msg .= '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Status Employee</td><td>:</td><td>' . htmlspecialchars($_POST['status_karyawan_edit']);
    if ($_POST['status_karyawan_edit'] == 'Kontrak') {
        $msg .= ' ' . htmlspecialchars($_POST['deskripsi_kontrak']);
    }
    $msg .= '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Source</td><td>:</td><td>' . htmlspecialchars($_POST['kandidat_edit']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Range Salary</td><td>:</td><td>' . htmlspecialchars($_POST['range_salary']) . '</td></tr>';
    $msg .= '            </table>';
    $msg .= '            <p>Base On Project:</p>';
    $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
    $msg .= '                <tr><td style="width: 20%;">Project Code</td><td>:</td><td>' . htmlspecialchars($_POST['project_code']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Project Name</td><td>:</td><td>' . htmlspecialchars($_POST['nama_project']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Customer</td><td>:</td><td>' . htmlspecialchars($_POST['nama_customer']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Periode Project</td><td>:</td><td>' . htmlspecialchars($_POST['periode_project']) . '</td></tr>';
    $msg .= '            </table>';
    $msg .= '            <p>Approval:</p>';
    $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
    $msg .= '                <tr><td style="width: 20%;">By Leader</td><td>:</td><td>' . htmlspecialchars($_POST['gm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_gm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td>' . htmlspecialchars($_POST['status_gm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">By GM HCM</td><td>:</td><td>' . htmlspecialchars($_POST['gm_hcm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_gm_hcm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td>' . htmlspecialchars($_POST['status_gm_hcm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">By BOD</td><td>:</td><td>' . htmlspecialchars($_POST['gm_bod']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_gm_bod']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td> Approve </td></tr>';
    $msg .= '            </table>';
    $msg .= '        <table>';

    $msg .= '</p>';

    // $msg .= '<p>Telah mendapatkan Approve dari ' . $_POST['gm_bod'] . '</p>';
    $msg .= '        <table>';
    $msg .= '        <tr>';
    $msg .= '        </table>';
    $msg .= '        <p>Terimakasih</p>';
    $msg .= '    </td>';
    $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
    $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
    $msg .= "</table>";
    // echo $msg;
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
    // $to = 'Malik Aulia Wiratama <malik.aulia@mastersystem.co.id>';
    $to = 'margareta.sekar@mastersystem.co.id';
    $from = "MSIZone<msizone@mastersystem.co.id>";
    // $cc = "recruitment.team@mastersystem.co.id";
    $cc = 'recruitment.team@mastersystem.co.id';
    $kp = $_POST['project_code'];
    $id = $_POST['id'];
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
    // $msg .= '<table width="80%">';
    // $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $_POST['id_fpkb'] . '</td></tr>';
    // $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi_edit'] . '</td></tr>';
    // $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
    // $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $_POST['project_code'] . '</td></tr>';
    // $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $_POST['status_rekrutmen_edit'] . '</td></tr>';
    // $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $_POST['status_karyawan_edit'] . '</td></tr>';
    // $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $_POST['kandidat_edit'] . '</td></tr>';
    // $msg .= '</table>';
    // $msg .= '</p>';



    $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
    $msg .= '                <tr><td style="width: 20%;">FPKB Number</td><td>:</td><td>' . htmlspecialchars($_POST['id_fpkb']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Requester</td><td>:</td><td>' . htmlspecialchars($_POST['request_by']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Division</td><td>:</td><td>' . htmlspecialchars($_POST['divisi_edit']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Position</td><td>:</td><td>' . htmlspecialchars($_POST['posisi']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Job Description</td><td>:</td><td>' . htmlspecialchars($_POST['deskripsi']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Reason</td><td>:</td><td>' . htmlspecialchars($_POST['status_rekrutmen_edit']);
    if ($_POST['status_rekrutmen_edit'] == 'Penggantian') {
        $msg .= ' ' . htmlspecialchars($_POST['reason_penggantian']);
    }
    $msg .= '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Status Employee</td><td>:</td><td>' . htmlspecialchars($_POST['status_karyawan_edit']);
    if ($_POST['status_karyawan_edit'] == 'Kontrak') {
        $msg .= ' ' . htmlspecialchars($_POST['deskripsi_kontrak']);
    }
    $msg .= '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Source</td><td>:</td><td>' . htmlspecialchars($_POST['kandidat_edit']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Range Salary</td><td>:</td><td>' . htmlspecialchars($_POST['range_salary']) . '</td></tr>';
    $msg .= '            </table>';
    $msg .= '            <p>Base On Project:</p>';
    $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
    $msg .= '                <tr><td style="width: 20%;">Project Code</td><td>:</td><td>' . htmlspecialchars($_POST['project_code']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Project Name</td><td>:</td><td>' . htmlspecialchars($_POST['nama_project']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Customer</td><td>:</td><td>' . htmlspecialchars($_POST['nama_customer']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Periode Project</td><td>:</td><td>' . htmlspecialchars($_POST['periode_project']) . '</td></tr>';
    $msg .= '            </table>';
    $msg .= '            <p>Approval:</p>';
    $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
    $msg .= '                <tr><td style="width: 20%;">By Leader</td><td>:</td><td>' . htmlspecialchars($_POST['gm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_gm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td>' . htmlspecialchars($_POST['status_gm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">By GM HCM</td><td>:</td><td>' . htmlspecialchars($_POST['gm_hcm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_gm_hcm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td>' . htmlspecialchars($_POST['status_gm_hcm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">By BOD</td><td>:</td><td>' . htmlspecialchars($_POST['gm_bod']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_gm_bod']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td> Approve </td></tr>';
    $msg .= '            </table>';
    $msg .= '        <table>';

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
    // echo $msg;
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
    $notifmsg = $_POST['id_fpkb'] . "; " . $_POST['project_code'] . "; ";
    $notif_link = "index.php?mod=hcm_requirement&act=editapproval&id=" . $_POST['id'] . "&project_code=" . $_POST['project_code'] . "&submit=Submit";
    $insert = sprintf(
        "(`notif_from`, `notif_to`, `notif_subject`, `notif_post`, `notif_link`) VALUES (%s, %s, %s, %s, %s)",
        GetSQLValueString($from, "text"),
        GetSQLValueString($to, "text"),
        GetSQLValueString(ucwords("Submitted ID FPKB ") . $_POST['id_fpkb'], "text"),
        GetSQLValueString($notifmsg, "text"),
        GetSQLValueString($notif_link, "text")
    );
    $res = $DBNOTIF->insert_data($tblnamenotif, $insert);
} elseif (isset($_POST['dissaprove_gm_bod'])) {

    $condition = "id=" . $_POST['id'];
    $update = sprintf(
        "`status_gm_bod`=%s,`catatan_gm_bod`=%s, `status_request`=%s",
        GetSQLValueString("Disapprove", "text"),
        GetSQLValueString($_POST['catatan_gm_bod'], "text"),
        GetSQLValueString("Inactive", "text")

    );
    $res = $DBHCM->update_data($tblname, $update, $condition);
    $description = $_POST['gm_bod'] . " Telah memberikan Disapprove pada data dengan ID FPKB " . $_POST['id_fpkb'];
    $insert = sprintf(
        "(`id_fpkb`,`project_code`,`project_name`,`description`,`entry_by`) VALUES (%s,%s,%s,%s,%s)",
        GetSQLValueString($_POST['id_fpkb'], "text"),
        GetSQLValueString($_POST['project_code'], "text"),
        GetSQLValueString($_POST['nama_project'], "text"),
        GetSQLValueString($description, "text"),
        GetSQLValueString($_POST['gm'], "text")
    );
    $res = $DBHCM->insert_data("hcm_requirement_log", $insert);
    $idfpkb = $_POST['id_fpkb'];
    // $resultt = $DBHCM->get_sqlV2("SELECT * FROM sa_hcm_requirement WHERE id_fpkb = $idfpkb");
    // var_dump($resultt[0]['id']);
    // die;
    $mdlname = "NOTIFICATION";
    $DBNOTIF = get_conn($mdlname);
    $to = "Wahyuwinningdyah Margareta,S.S.PSI<margareta.sekar@mastersystem.co.id>";
    $from = $_POST['gm_bod'];
    $tblnamenotif = "trx_notification";
    $notifmsg = $idfpkb . "; " . $_POST['project_code'] . "; ";
    $project_name = str_replace(' ', '_', $_POST['nama_project']); // Mengganti spasi dengan underscore
    // $notif_link = "index.php?mod=hcm_requirement&act=editapproval&id=" . $_POST['id'] . "&project_code=" . $_POST['project_code'] . "&submit=Submit";
    $notif_link = "index.php?mod=hcm_requirement&act=edit&id=" . $_POST['id'] . "&project_code=" . $_POST['project_code'] . "&project_name=" . $project_name . "&submit=Submit";
    $insert = sprintf(
        "(`notif_from`, `notif_to`, `notif_subject`, `notif_post`, `notif_link`) VALUES (%s, %s, %s, %s, %s)",
        GetSQLValueString($from, "text"),
        GetSQLValueString($to, "text"),
        GetSQLValueString(ucwords("Disapprove ID FPKB ") . $idfpkb, "text"),
        GetSQLValueString($notifmsg, "text"),
        GetSQLValueString($notif_link, "text")
    );
    $res = $DBNOTIF->insert_data($tblnamenotif, $insert);
    $to = $_POST['request_by'];
    $from = "MSIZone<msizone@mastersystem.co.id>";
    // $from = "MSIZone<msizone@mastersystem.co.id>";
    $cc = $_POST['gm_bod'] . ',' . $_POST['gm'] . ',recruitment.team@mastersystem.co.id';
    $bcc = "";
    $reply = $from;
    $subject = "Request Karyawan " . $_POST['posisi'];
    $msg = '<table width="100%">';
    $msg .= '    <tr><td width="20%" rowspan="4"></td>';
    $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
    $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
    $msg .= '    <td width="20%" rowspan="4"></tr>';
    $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
    $msg .= '        <p>Dear ' . $to . '</p>';
    $msg .= '        <p>';
    // $msg .= '<table width="80%">';
    // $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $_POST['id_fpkb'] . '</td></tr>';
    // $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi_edit'] . '</td></tr>';
    // $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
    // $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $_POST['project_code'] . '</td></tr>';
    // $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $_POST['status_rekrutmen_edit'] . '</td></tr>';
    // $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $_POST['status_karyawan_edit'] . '</td></tr>';
    // $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $_POST['kandidat_edit'] . '</td></tr>';
    // $msg .= '</table>';
    // $msg .= '</p>';

    $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
    $msg .= '                <tr><td style="width: 20%;">FPKB Number</td><td>:</td><td>' . htmlspecialchars($_POST['id_fpkb']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Requester</td><td>:</td><td>' . htmlspecialchars($_POST['request_by']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Division</td><td>:</td><td>' . htmlspecialchars($_POST['divisi_edit']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Position</td><td>:</td><td>' . htmlspecialchars($_POST['posisi']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Job Description</td><td>:</td><td>' . htmlspecialchars($_POST['deskripsi']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Reason</td><td>:</td><td>' . htmlspecialchars($_POST['status_rekrutmen_edit']);
    if ($_POST['status_rekrutmen_edit'] == 'Penggantian') {
        $msg .= ' ' . htmlspecialchars($_POST['reason_penggantian']);
    }
    $msg .= '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Status Employee</td><td>:</td><td>' . htmlspecialchars($_POST['status_karyawan_edit']);
    if ($_POST['status_karyawan_edit'] == 'Kontrak') {
        $msg .= ' ' . htmlspecialchars($_POST['deskripsi_kontrak']);
    }
    $msg .= '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Source</td><td>:</td><td>' . htmlspecialchars($_POST['kandidat_edit']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Range Salary</td><td>:</td><td>' . htmlspecialchars($_POST['range_salary']) . '</td></tr>';
    $msg .= '            </table>';
    $msg .= '            <p>Base On Project:</p>';
    $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
    $msg .= '                <tr><td style="width: 20%;">Project Code</td><td>:</td><td>' . htmlspecialchars($_POST['project_code']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Project Name</td><td>:</td><td>' . htmlspecialchars($_POST['nama_project']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Customer</td><td>:</td><td>' . htmlspecialchars($_POST['nama_customer']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Periode Project</td><td>:</td><td>' . htmlspecialchars($_POST['periode_project']) . '</td></tr>';
    $msg .= '            </table>';
    $msg .= '            <p>Approval:</p>';
    $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
    $msg .= '                <tr><td style="width: 20%;">By Leader</td><td>:</td><td>' . htmlspecialchars($_POST['gm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_gm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td>' . htmlspecialchars($_POST['status_gm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">By GM HCM</td><td>:</td><td>' . htmlspecialchars($_POST['gm_hcm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_gm_hcm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td>' . htmlspecialchars($_POST['status_gm_hcm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">By BOD</td><td>:</td><td>' . htmlspecialchars($_POST['gm_bod']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_gm_bod']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td> Disapprove </td></tr>';
    $msg .= '            </table>';
    $msg .= '        <table>';

    $msg .= '</p>';

    // $msg .= '<p>Telah mendapatkan Disapprove dari ' . $_POST['gm_bod'] . '</p>';
    $msg .= '        <table>';
    $msg .= '        <tr>';
    $msg .= '        </table>';
    $msg .= '        <p>Terimakasih</p>';
    $msg .= '    </td>';
    $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
    $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
    $msg .= "</table>";
    // echo $msg;
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
} elseif (isset($_POST['inactive'])) {
    $condition = "id=" . $_POST['id'];
    $update = sprintf(
        "`status_request`=%s",
        GetSQLValueString("Inactive", "text")

    );
    $res = $DBHCM->update_data($tblname, $update, $condition);
} elseif (isset($_POST['editapproval'])) {
    $userName = addslashes($_SESSION['Microservices_UserName']);
    $entry_by = $userName . "<" . $_SESSION['Microservices_UserEmail'] . ">";
    $condition = "id=" . $_POST['id'];
    $update = sprintf(
        "`assign_requirement`=%s,`status_request`=%s",
        GetSQLValueString($_POST['assign_requirement'], "text"),
        GetSQLValueString("Proses Interview", "text")
    );
    $res = $DBHCM->update_data($tblname, $update, $condition);
    $from = $entry_by;
    $to = $_POST['assign_requirement'];
    $mdlname = "NOTIFICATION";
    $DBNOTIF = get_conn($mdlname);
    $tblnamenotif = "trx_notification";

    $project_name = str_replace(' ', '_', $_POST['project_name']); // Mengganti spasi dengan underscore
    $notifmsg = $_POST['id_fpkb'] . "; " . $_POST['project_code'] . "; ";
    $notif_link = "index.php?mod=hcm_requirement&act=editshare&id=" . $_POST['id'] . "&project_code=" . $_POST['project_code'] . "&project_name=" . $project_name . "&submit=Submit";

    // var_dump($notif_link);
    // die;
    $insert = sprintf(
        "(`notif_from`, `notif_to`, `notif_subject`, `notif_post`, `notif_link`) VALUES (%s, %s, %s, %s, %s)",
        GetSQLValueString($from, "text"),
        GetSQLValueString($to, "text"),
        GetSQLValueString(ucwords("Assign") . " ID FPKB " . $_POST['id_fpkb'], "text"),
        GetSQLValueString($notifmsg, "text"),
        GetSQLValueString($notif_link, "text")
    );

    $res = $DBNOTIF->insert_data($tblnamenotif, $insert);
    $project_code = $_POST['project_code'];
    $project_name = $_POST['project_name'];
    $description = $userName . " Telah memberikan Assign kepada " . $_POST['assign_requirement'] . "pada data dengan ID_FPKB" . $_POST['id_fpkb'] . " pada data dengan KP " . $_POST['project_code'];
    $insert = sprintf(
        "(`id_fpkb`,`project_code`,`project_name`,`description`,`entry_by`) VALUES (%s,%s,%s,%s,%s)",
        GetSQLValueString($_POST['id_fpkb'], "text"),
        GetSQLValueString($project_code, "text"),
        GetSQLValueString($project_name, "text"),
        GetSQLValueString($description, "text"),
        GetSQLValueString($entry_by, "text")
    );
    $res = $DBHCM->insert_data("hcm_requirement_log", $insert);

    // $to = 'malik.aulia@mastersystem.co.id';
    $to = $_POST['assign_requirement'];
    // $to = $_POST['assign_requirement'];
    $from = "MSIZone<msizone@mastersystem.co.id>";
    $cc = "recruitment.team@mastersystem.co.id";
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
    $msg .= '        <p>Berikut ini adalah daftar Request Karyawan.</p>';
    $msg .= '        <p>';
    $msg .= '<table width="80%">';
    $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $_POST['id_fpkb'] . '</td></tr>';
    $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi'] . '</td></tr>';
    $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
    $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $_POST['project_code'] . '</td></tr>';
    $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $_POST['kandidat'] . '</td></tr>';
    $msg .= '<tr><td>Jumlah Dibutuhkan</td><td>:</td><td>' . $_POST['jumlah_dibutuhkan'] . '</td></tr>';
    $msg .= '<tr><td>Status Request</td><td>:</td><td>' . $_POST['status_request'] . '</td></tr>';
    $msg .= '<tr><td>Kompetensi Teknis</td><td>:</td><td>' . $_POST['kompetensi_teknis'] . '</td></tr>';
    $msg .= '<tr><td>Assign Recruiter</td><td>:</td><td>' . $_POST['assign_requirement'] . '</td></tr>';
    $msg .= '</table>';
    $msg .= '</p>';
    $msg .= '        <p>Terimakasih</p>';
    $msg .= '    </td>';
    $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
    $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
    $msg .= "</table>";
    // echo $msg;
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
    $ALERT->savedata();
} elseif (isset($_POST['editshare'])) {
    $userName = addslashes($_SESSION['Microservices_UserName']);
    $entry_by = $userName . "<" . $_SESSION['Microservices_UserEmail'] . ">";
    // $malik = $_POST['id_note'][$i];
    for ($i = 0; $i < count($_POST['malik']); $i++) {
        $kp = $_POST['project_code'];
        $nama = $_POST['malik'][$i];
        $id_note = $_POST['id_note'][$i];
        $tanggal_interview = $_POST['tanggal_interview_cv'][$i];
        // $catatan = $_POST['notes'][$i];
        $status_cv = isset($_POST['status_cv'][$i]) ? $_POST['status_cv'][$i] : ''; // Check if the key exists
        $entryby = $entry_by;
        if (empty($status_cv)) {
            continue; // Skip the rest of the loop for this iteration
        }
        if ($status_cv === "No") {
            $tanggal_interview = '0000-00-00 00:00:00';
        }
        $res = $DBHCM->get_res("UPDATE sa_hcm_notecv SET status_cv = '$status_cv', tanggal_interview = '$tanggal_interview', update_by = '$entry_by' WHERE id_note = '$id_note'");
        $condition = "id=" . $_POST['id'];
        $update = sprintf(
            "`status_request`=%s",
            GetSQLValueString("Proses Interview", "text")
        );
        $res = $DBHCM->update_data($tblname, $update, $condition);
    }
    $ALERT->savedata();
    // $userName = addslashes($_SESSION['Microservices_UserName']);
    // $entry_by = $userName . "<" . $_SESSION['Microservices_UserEmail'] . ">";
    // $cobacombine = array();
    // $malik = $_POST['malik'][$i];
    // var_dump($malik);
    // die;
    // for ($i = 0; $i < count($_POST['malik']); $i++) {
    //     $nama = $_POST['malik'][$i];
    //     $id = $_POST['id_note'][$i];
    //     $tanggal_interview = $_POST['tanggal_interview_cv'][$i];
    //     $status_cv = $_POST['status_cv'][$i];
    //     $entryby = $entry_by;
    //     $combinecoba[] = array($status_cv, $tanggal_interview, $entryby);
    //     foreach ($combinecoba as $value) {
    //         $update = sprintf(
    //             "`status_cv` = %s,`tanggal_interview` = %s,`update_by` = %s",
    //             GetSQLValueString($value[0], "text"),
    //             GetSQLValueString($value[1], "date"),
    //             GetSQLValueString($value[2], "text")
    //         );
    //         $update_editshare = "file='" . $nama . "'";
    //         var_dump($update, $update_editshare);
    //         die;
    //         $res = $DBHCM->update_data("hcm_notecv", $update, $update_editshare);
    //     }
    // }
    // $condition = "id=" . $_POST['id'];
    // $update = sprintf(
    //     "`status_request`=%s",
    //     GetSQLValueString("Proses Interview", "text")
    // );
    // $res = $DBHCM->update_data($tblname, $update, $condition);
} elseif (isset($_POST['send_email_cv'])) {
    $userName = addslashes($_SESSION['Microservices_UserName']);
    $entry_by = $userName . "<" . $_SESSION['Microservices_UserEmail'] . ">";
    $kp = $_POST['project_code'];
    $idfpkb = $_POST['id_fpkb'];
    $id = $_POST['id'];
    $project_name = $_POST['nama_project'];
    $posisi = $_POST['posisi'];
    $to = $_POST['request_by'];
    // $to = "ajiesandimartin@yahoo.co.id";
    // $to = "malik.aulia@mastersystem.co.id";
    $from = "MSIZone<msizone@mastersystem.co.id>";
    $cc = "recruitment.team@mastersystem.co.id";
    $bcc = "";
    $reply = $from;
    $subject = "[CV Kandidat] $idfpkb - $posisi";

    // Initialize email message content
    $msg = '<table width="100%">';
    $msg .= '    <tr><td width="20%" rowspan="4"></td>';
    $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
    $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
    $msg .= '    <td width="20%" rowspan="4"></tr>';
    $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
    $msg .= '        <p>Dear ' . $to . '</p>';
    $msg .= '        <p>';
    $msg .= '<table width="80%">';
    $msg .= '<tr><td>ID FKPB</td><td>:</td><td>' . $_POST['id_fpkb'] . '</td></tr>';
    $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi'] . '</td></tr>';
    $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
    $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $_POST['project_code'] . '</td></tr>';
    $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $_POST['kandidat'] . '</td></tr>';
    $msg .= '</table>';
    $msg .= '</p>';
    $msg .= '<p>Berikut adalah CV Kandidat. Mohon Reviewnya</p>';
    $msg .= '<table border="1">';
    $msg .= '<tr>';
    $msg .= '<th>ID</th>';
    $msg .= '<th>Nama File</th>';
    $msg .= '<th>Status CV</th>';
    $msg .= '</tr>';

    // Add all filenames and IDs to the email content
    foreach ($_POST['malik'] as $index => $nama) {
        $id_note = $_POST['id_note'][$index];
        $file_id = $_POST['id_file'][$index];
        $Yes = 'mailto:repoadmin@mastersystem.co.id?subject=[TESTING] Status CV-' . $id_note . '&body=[yyyy-mm-dd hh:mm:ss] - Tanggal Interview%0D%0A[Yes] - status_cv';
        $No = 'mailto:repoadmin@mastersystem.co.id?subject=[TESTING] Status CV-' . $id_note . '&body=[0000-00-00] - Tanggal Interview%0D%0A[No] - status_cv';
        $id_filedrive = 'https://drive.google.com/file/d/' . $file_id . '/view';
        $msg .= '<tr>';
        $msg .= '<td>' . $id_note . '</td>';
        $msg .= '<td><a href="' . $id_filedrive . '">' . $nama . '</a></td>';
        $msg .= '<td><a href="' . $Yes . '">Yes</a> | <a href="' . $No . '">No</a></td>';
        $msg .= '</tr>';
    }

    $msg .= '</table>';
    $msg .= '        <table>';
    $msg .= '        <tr>';
    // $msg .= '        <p>Silahkan klik link berikut untuk melihat dokumen tersebut <a href="' . $linkapprove . '">Link</a></p>';
    $msg .= '        </table>';
    $msg .= '        <p>Terimakasih</p>';
    $msg .= '        <p>(Notes : Bisa pilih lebih dari 1 cv)</p>';
    $msg .= '    </td>';
    $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
    $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
    $msg .= "</table>";

    // echo $msg;

    $headers = "From: " . $from . "\r\n" .
        "Reply-To: " . $reply . "\r\n" .
        "Cc: " . $cc . "\r\n" . // Uncomment this line if you want to use CC
        "Bcc: " . $bcc . "\r\n" .
        "MIME-Version: 1.0" . "\r\n" .
        "Content-Type: text/html; charset=UTF-8" . "\r\n" .
        "X-Mailer: PHP/" . phpversion();

    if (mail($to, $subject, $msg, $headers)) {
        echo "Email terkirim pada jam " . date("d M Y G:i:s");
    } else {
        echo "Email gagal terkirim pada jam " . date("d M Y G:i:s");
    }
    // }
} elseif (isset($_POST['addlinkfrom'])) {
    $insert = sprintf(
        "(`link_from`) VALUES (%s)",
        GetSQLValueString($_POST['link_from'], "text")
    );
    $res = $DBHCM->insert_data("hcm_link_requirement", $insert);
} elseif (isset($_POST['addposisibaru'])) {
    $insert = sprintf(
        "(`posisi`) VALUES (%s)",
        GetSQLValueString($_POST['posisi'], "text")
    );
    $res = $DBHCM->insert_data("hcm_requirement_posisi", $insert);
} elseif (isset($_POST['editinterview'])) {

    $userName = addslashes($_SESSION['Microservices_UserName']);
    $entry_by = $userName . "<" . $_SESSION['Microservices_UserEmail'] . ">";
    if (isset($_POST['email'])) {
        $combine_user = implode(',', $_POST['interview_user']);
        // $idreq = $_POST['id_request'];
        // var_dump($idreq);
        // die;
        foreach ($_POST['email'] as $index => $email) {
            $insert_sql = sprintf(
                "(`id_fpkb`,`id_request`, `project_code`, `nama_kandidat`, `email`, `tanggal_interview`, `link_webex`, `pic`, `interview_hcm`, `interview_user`) VALUES (%s,%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                GetSQLValueString($_POST['id_fpkb'], "text"),
                GetSQLValueString($_POST['id_request'], "text"),
                GetSQLValueString($_POST['project_code'], "text"),
                GetSQLValueString($_POST['nama_kandidat'][$index], "text"),
                GetSQLValueString($email, "text"),
                GetSQLValueString($_POST['tanggal_interview'][$index], "date"),
                GetSQLValueString($_POST['link_baru'][$index], "text"),
                GetSQLValueString($_POST['pic'][$index], "text"),
                GetSQLValueString($_POST['interview_hcm'][$index], "text"),
                GetSQLValueString($combine_user, "text")
            );
            $cobaan = $DBHCM->insert_data("hcm_requirement_interview", $insert_sql);
            $startWorkDate = $_POST['tanggal_interview'][$index];
            $resourceEmail = $_POST['email'][0];
            $permalink = $_POST['link_baru'][0];

            $tgl_int = $_POST['tanggal_interview'][$index];
            $days = array(
                'Sunday' => 'Minggu',
                'Monday' => 'Senin',
                'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu',
                'Thursday' => 'Kamis',
                'Friday' => 'Jumat',
                'Saturday' => 'Sabtu'
            );
            // $tgl_int = '2024-06-27 09:09:00'
            $cobatgl = str_replace(array_keys($days), array_values($days), date('l , d F Y', strtotime($tgl_int)));
            $cobajam = date('H:i:s', strtotime($tgl_int));

            $datemin2 = str_replace(array_keys($days), array_values($days), date('l , d F Y', strtotime($tgl_int . '- 1 days')));

            // $to = str_replace(";", ",", $value[0]);
            $to = $email;
            // $to = "malik.aulia@mastersystem.co.id";
            $link_webex = $_POST['link_baru'][0];
            $pic = $_POST['pic'][0];
            $from = "MSIZone<msizone@mastersystem.co.id>";
            $cc = "recruitment.team@mastersystem.co.id";
            $bcc = "";
            $reply = $from;
            $subject = "Invitation Interview";

            // HTML content
            $msg = '<table width="100%">';
            $msg .= '<tr>';
            $msg .= '<td rowspan="5"></td>';
            $msg .= '<td>';
            $msg .= '<img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png" />';
            $msg .= '</td>';
            $msg .= '</tr>';
            $msg .= '<tr>';
            $msg .= '<td style="padding: 20px; border: thin solid #dadada">';
            $msg .= '<br />';
            $msg .= '<p>Dear Kandidat</p>';
            $msg .= '<table style="width: 100%">';
            $msg .= '<tr>';
            $msg .= '<td></td>';
            $msg .= '</tr>';
            $msg .= '<tr>';
            $msg .= '<td>Terkait dengan CV yang kami terima, kami PT. Mastersystem Infotama bermaksud mengundang anda Interview, </td>';
            $msg .= '</tr>';
            $msg .= '<td>Berikut adalah link Cisco Webex. Jika menggunakan laptop bisa menggunakan browser atau jika memakai android/ios bisa download aplikasi cisco webex: ';
            $msg .= '<p></p>';
            $msg .= '</td>';
            $msg .= '<tr>';
            $msg .= '<td></td>';
            $msg .= '</tr>';
            $msg .= '<tr>';
            $msg .= '<td></td>';
            $msg .= '</tr>';
            $msg .= '<tr>';
            $msg .= '<td></td>';
            $msg .= '</tr>';
            $msg .= '<table>';
            $msg .= '<tr>';
            $msg .= '<td></td>';
            $msg .= '<td></td>';
            $msg .= '<td></td>';
            $msg .= '</tr>';
            $msg .= '<tr>';
            $msg .= '<td>Hari / Tanggal</td>';
            $msg .= '<td>:</td>';
            $msg .= '<td align="left">' . $cobatgl . ' WIB</td>';
            $msg .= '</tr>';
            $msg .= '<tr>';
            $msg .= '<td>Waktu</td>';
            $msg .= '<td>:</td>';
            $msg .= '<td align="left">' . $cobajam . ' </td>';
            $msg .= '</tr>';
            $msg .= '<tr>';
            $msg .= '<td>Link Webex</td>';
            $msg .= '<td>:</td>';
            $msg .= '<td align="left">' . $permalink . '</td>';
            $msg .= '</tr>';
            $msg .= '<tr>';
            $msg .= '<td>PIC</td>';
            $msg .= '<td>:</td>';
            $msg .= '<td align="left">' . $pic . '</td>';
            $msg .= '</tr>';
            $msg .= '<p></p>';
            $msg .= '<tr>';
            $msg .= '<td></td>';
            $msg .= '</tr>';
            $msg .= '<tr>';
            $msg .= '<td></td>';
            $msg .= '</tr>';
            $msg .= '</table>';
            $msg .= '<table>';
            $msg .= '<tr>';
            $msg .= '<td>Serta mohon mengisi dan mengirimkan kembali form terlampir serta CV terbaru, <b>paling lambat ' . $datemin2 . '';
            $msg .= '</td>';
            $msg .= '</tr>';
            $msg .= '<p></p>';
            $msg .= '<tr>';
            $msg .= '<td></td>';
            $msg .= '</tr>';
            $msg .= '<tr>';
            $msg .= '<td></td>';
            $msg .= '</tr>';
            $msg .= '<p></p>';
            $msg .= '<tr>';
            $msg .= '<td>Ditunggu konfirmasinya dengan membalas email ini, dikarenakan interview ini langsung dengan User.</td>';
            $msg .= '</tr>';
            $msg .= '<tr>';
            $msg .= '<td></td>';
            $msg .= '</tr>';
            $msg .= '</table>';
            $msg .= '<p>Demikian hal ini disampaikan, atas perhatiannya kami ucapkan terimakasih.</p>';
            $msg .= '<p>Terimakasih</p>';
            $msg .= '<td width="30%" rowspan="5">';
            $msg .= '<tr>';
            $msg .= '<td style="padding: 20px; border: thin solid #dadada">';
            $msg .= '<table width="100%">';
            $msg .= '<tr>';
            $msg .= '<td>';
            $msg .= '<a href="https://msizone.mastersystem.co.id">MSIZone</a>';
            $msg .= '</td>';
            $msg .= '<td style="text-align: right">';
            $msg .= '<a href="https://msizone.mastersystem.co.id/msiguide/">MSIGuide</a>';
            $msg .= '</td>';
            $msg .= '</tr>';
            $msg .= '</table>';
            $msg .= '</td>';
            $msg .= '</tr>';
            $msg .= '<tr>';
            $msg .= '<td style="font-size: 10px;padding-left: 20px;border: thin solid #dadada;">Dikirim secara otomatis oleh sistem MSIZone. </td>';
            $msg .= '</tr>';
            $msg .= '</td>';
            $msg .= '</table>';
            $msg .= '</td>';
            $msg .= '</tr>';
            $msg .= '</table>';

            // Attachments
            $attachments = array(
                "components/modules/hcm_requirement/FORM_KETERANGAN_PELAMAR_FILL_UP.pdf" => "FORM_KETERANGAN_PELAMAR_FILL_UP.pdf",
                // "components/modules/hcm_requirement/masterian.pdf" => "masterian.pdf",
            );

            // Create a boundary for the multipart/mixed MIME type
            $boundary = md5(time());

            // Construct headers for the email
            $headers = "From: " . $from . "\r\n" .
                "Reply-To: " . $reply . "\r\n" .
                "Cc: " . $cc . "\r\n" .
                "Bcc: " . $bcc . "\r\n" .
                "MIME-Version: 1.0" . "\r\n" .
                "Content-Type: multipart/mixed; boundary=\"" . $boundary . "\"\r\n" .
                "X-Mailer: PHP/" . phpversion();

            // Construct the message body
            $body = "--" . $boundary . "\r\n" .
                "Content-Type: text/html; charset=UTF-8\r\n" .
                "\r\n" .
                $msg . "\r\n";

            // Add attachments to the message body
            foreach ($attachments as $file => $filename) {
                $file_content = file_get_contents($file);
                $file_content = base64_encode($file_content);
                $body .= "--" . $boundary . "\r\n" .
                    "Content-Type: application/octet-stream; name=\"" . $filename . "\"\r\n" .
                    "Content-Transfer-Encoding: base64\r\n" .
                    "Content-Disposition: attachment\r\n" .
                    "\r\n" .
                    $file_content . "\r\n";
            }

            $body .= "--" . $boundary . "--";
            // Send the email
            if (mail($to, $subject, $body, $headers)) {
                echo "Email with attachments sent at " . date("d M Y G:i:s");
            } else {
                echo "Failed to send email at " . date("d M Y G:i:s");
            }

            $to = $_POST['request_by'];
            // $to = "malik.aulia@mastersystem.co.id";
            $link_webex = $_POST['link_baru'][0];
            $pic = $_POST['pic'][0];
            $from = "MSIZone<msizone@mastersystem.co.id>";
            $cc = "recruitment.team@mastersystem.co.id";
            $bcc = "";
            $reply = $from;
            $subject = "Invitation Interview";

            // HTML content
            $msg = '<table width="100%">';
            $msg .= '<tr>';
            $msg .= '<td rowspan="5"></td>';
            $msg .= '<td>';
            $msg .= '<img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png" />';
            $msg .= '</td>';
            $msg .= '</tr>';
            $msg .= '<tr>';
            $msg .= '<td style="padding: 20px; border: thin solid #dadada">';
            $msg .= '<br />';
            $msg .= '<p>Dear Kandidat</p>';
            $msg .= '<table style="width: 100%">';
            $msg .= '<tr>';
            $msg .= '<td></td>';
            $msg .= '</tr>';
            $msg .= '<tr>';
            $msg .= '<td>Terkait dengan CV yang kami terima, kami PT. Mastersystem Infotama bermaksud mengundang anda Interview, </td>';
            $msg .= '</tr>';
            $msg .= '<td>Berikut adalah link Cisco Webex. Jika menggunakan laptop bisa menggunakan browser atau jika memakai android/ios bisa download aplikasi cisco webex: ';
            $msg .= '<p></p>';
            $msg .= '</td>';
            $msg .= '<tr>';
            $msg .= '<td></td>';
            $msg .= '</tr>';
            $msg .= '<tr>';
            $msg .= '<td></td>';
            $msg .= '</tr>';
            $msg .= '<tr>';
            $msg .= '<td></td>';
            $msg .= '</tr>';
            $msg .= '<table>';
            $msg .= '<tr>';
            $msg .= '<td></td>';
            $msg .= '<td></td>';
            $msg .= '<td></td>';
            $msg .= '</tr>';
            $msg .= '<tr>';
            $msg .= '<td>Hari / Tanggal</td>';
            $msg .= '<td>:</td>';
            $msg .= '<td align="left">' . $cobatgl . ' WIB</td>';
            $msg .= '</tr>';
            $msg .= '<tr>';
            $msg .= '<td>Waktu</td>';
            $msg .= '<td>:</td>';
            $msg .= '<td align="left">' . $cobajam . ' </td>';
            $msg .= '</tr>';
            $msg .= '<tr>';
            $msg .= '<td>Link Webex</td>';
            $msg .= '<td>:</td>';
            $msg .= '<td align="left">' . $permalink . '</td>';
            $msg .= '</tr>';
            $msg .= '<tr>';
            $msg .= '<td>PIC</td>';
            $msg .= '<td>:</td>';
            $msg .= '<td align="left">' . $pic . '</td>';
            $msg .= '</tr>';
            $msg .= '<tr>';
            // $msg .= '<td align="left"><a href="' . $id_filedrive . '">' . $_POST['nama_kandidat'][$index] . '</a></td>';
            $msg .= '<td>Nama Kandidat</td>';
            $msg .= '<td>:</td>';
            // $msg .= '<td align="left">' . $_POST['nama_kandidat'][$index] . '</td>';
            $file_id = $_POST['id_file'][$index];
            $id_filedrive = 'https://drive.google.com/file/d/' . $file_id . '/view';
            $msg .= '<td align="left"><a href="' . $id_filedrive . '">' . $_POST['nama_kandidat'][$index] . '</a></td>';
            $msg .= '</tr>';
            $msg .= '<p></p>';
            $msg .= '<tr>';
            $msg .= '<td></td>';
            $msg .= '</tr>';
            $msg .= '<tr>';
            $msg .= '<td></td>';
            $msg .= '</tr>';
            $msg .= '</table>';
            $msg .= '<table>';
            $msg .= '<tr>';
            $msg .= '<td>';
            $msg .= '</td>';
            $msg .= '</tr>';
            $msg .= '<p></p>';
            $msg .= '<tr>';
            $msg .= '<td></td>';
            $msg .= '</tr>';
            $msg .= '<tr>';
            $msg .= '<td></td>';
            $msg .= '</tr>';
            $msg .= '<p></p>';
            $msg .= '<tr>';
            $msg .= '<td></td>';
            $msg .= '</tr>';
            $msg .= '<tr>';
            $msg .= '<td></td>';
            $msg .= '</tr>';
            $msg .= '</table>';
            $msg .= '<p>Demikian hal ini disampaikan, atas perhatiannya kami ucapkan terimakasih.</p>';
            $msg .= '<p>Terimakasih</p>';
            $msg .= '<td width="30%" rowspan="5">';
            $msg .= '<tr>';
            $msg .= '<td style="padding: 20px; border: thin solid #dadada">';
            $msg .= '<table width="100%">';
            $msg .= '<tr>';
            $msg .= '<td>';
            $msg .= '<a href="https://msizone.mastersystem.co.id">MSIZone</a>';
            $msg .= '</td>';
            $msg .= '<td style="text-align: right">';
            $msg .= '<a href="https://msizone.mastersystem.co.id/msiguide/">MSIGuide</a>';
            $msg .= '</td>';
            $msg .= '</tr>';
            $msg .= '</table>';
            $msg .= '</td>';
            $msg .= '</tr>';
            $msg .= '<tr>';
            $msg .= '<td style="font-size: 10px;padding-left: 20px;border: thin solid #dadada;">Dikirim secara otomatis oleh sistem MSIZone. </td>';
            $msg .= '</tr>';
            $msg .= '</td>';
            $msg .= '</table>';
            $msg .= '</td>';
            $msg .= '</tr>';
            $msg .= '</table>';
            // echo $msg;
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

            $startWorkDate = $_POST['tanggal_interview'][$index];
            // $resourceEmail = $_POST['email'][0];
            // $permalink = $_POST['link_webex'][0];
            $linkbaru = $_POST['link_baru'][0];

            $recruitmenteam = $_POST['interview_hcm'][0];
            $divisi = $_POST['divisi'];
            $posisi = $_POST['posisi'];
            $requestby = $_POST['request_by'];
            $filecv = $_POST['File_CV'][0];
            $pic = $_POST['pic'][0];
            preg_match('/<(.+?)>/', $pic, $matches);
            $emailpic = $matches[1];


            $client = getClient();
            $service = new Google_Service_Calendar($client);

            // foreach ($_POST['email'] as $index => $email) {
            try {
                // Ensure the date format is correct
                $startWorkDateTime = new DateTime($startWorkDate, new DateTimeZone('Asia/Jakarta'));
                // Assuming the interview duration is 1 hour
                $endWorkDateTime = clone $startWorkDateTime;
                $endWorkDateTime->add(new DateInterval('PT1H'));

                // Combine all email strings into a single string, then split and deduplicate
                $combinedEmails = "$pic,$recruitmenteam,$requestby,$email,$combine_user";
                $emails = array_unique(array_map(
                    'trim',
                    explode(',', $combinedEmails)
                ));

                // Create attendees
                $attendees = array();
                foreach ($emails as $email) {
                    $attendee = new Google_Service_Calendar_EventAttendee();
                    $attendee->setEmail(trim($email));
                    $attendees[] = $attendee;
                }

                // Create event
                $event = new Google_Service_Calendar_Event([
                    'summary' => "Interview Kandidat $posisi",
                    'location' => "PT. Mastersystem Infotama",
                    'description' => "Dear All, berikut jadwal interview kandidat dengan Divisi : $divisi dan Posisi : $posisi",
                    'attendees' => $attendees,
                    'start' => [
                        'dateTime' => $startWorkDateTime->format('Y-m-d\TH:i:s'),
                        'timeZone' => 'Asia/Jakarta',
                    ],
                    'end' => [
                        'dateTime' => $endWorkDateTime->format('Y-m-d\TH:i:s'),
                        'timeZone' => 'Asia/Jakarta',
                    ],
                ]);

                // Insert event
                $calendarId = $emailpic; // Use the correct calendar ID
                $event = $service->events->insert($calendarId, $event);
                $idcalendar = $event->getId();

                // Update database
                // $condition = "email='" . $_POST['email'][$index] . "'";
                $condition = "email = '" . $_POST['email'][$index] . "'";
                $update = sprintf(
                    "`id_calendar`=%s",
                    GetSQLValueString($idcalendar, "text")
                );
                $cobaan = $DBHCM->update_data("hcm_requirement_interview", $update, $condition);
                // var_dump($cobaan);
                // die;
            } catch (Google_Service_Exception $e) {
                // Handle the exception
                $error = $e->getMessage();
                $code = $e->getCode();
                $reason = $e->getErrors()[0]['reason'];
                // Display an error message to the user or log the error
                echo "Error: $reason<br>";
                echo "Message: $error<br>";
                echo "Code: $code<br>";
            } catch (Exception $e) {
                // Handle general exceptions
                echo "Error: " . $e->getMessage();
            }

            try {
                // Ensure the date format is correct
                $startWorkDateTime = new DateTime($startWorkDate, new DateTimeZone('Asia/Jakarta'));
                // Assuming the interview duration is 1 hour
                $endWorkDateTime = clone $startWorkDateTime;
                $endWorkDateTime->add(new DateInterval('PT1H'));

                // Combine all email strings into a single string, then split and deduplicate
                $combinedEmails = "$pic,$recruitmenteam,$requestby,$combine_user";
                $emails = array_unique(array_map(
                    'trim',
                    explode(',', $combinedEmails)
                ));

                // Create attendees
                $attendees = array();
                foreach ($emails as $email) {
                    $attendee = new Google_Service_Calendar_EventAttendee();
                    $attendee->setEmail(trim($email));
                    $attendees[] = $attendee;
                }

                // Create event
                $event = new Google_Service_Calendar_Event([
                    'summary' => "Interview Kandidat $posisi",
                    'location' => "PT. Mastersystem Infotama",
                    'description' => "Dear All, berikut jadwal interview kandidat dengan Divisi : $divisi dan Posisi : $posisi berikut adalah Link CV : $filecv",
                    'attendees' => $attendees,
                    'start' => [
                        'dateTime' => $startWorkDateTime->format('Y-m-d\TH:i:s'),
                        'timeZone' => 'Asia/Jakarta',
                    ],
                    'end' => [
                        'dateTime' => $endWorkDateTime->format('Y-m-d\TH:i:s'),
                        'timeZone' => 'Asia/Jakarta',
                    ],
                ]);

                // Insert event
                $calendarId = $emailpic; // Use the correct calendar ID
                $event = $service->events->insert($calendarId, $event);
                $idcalendar = $event->getId();
            } catch (Google_Service_Exception $e) {
                // Handle the exception
                $error = $e->getMessage();
                $code = $e->getCode();
                $reason = $e->getErrors()[0]['reason'];
                // Display an error message to the user or log the error
                echo "Error: $reason<br>";
                echo "Message: $error<br>";
                echo "Code: $code<br>";
            } catch (Exception $e) {
                // Handle general exceptions
                echo "Error: " . $e->getMessage();
            }
            // }

            $ALERT->savedata();
        }
    }
} elseif (isset($_POST['delete_file_id'])) {
    include_once('C:\xampp\htdocs\microservices\google-drive.php');
    $driveClient = getDriveClient();
    $driveService = new Google_Service_Drive($driveClient);
    function deleteFileFromDrive($service, $fileId)
    {
        try {
            $service->files->delete($fileId);
            echo "File deleted successfully.";
        } catch (Exception $e) {
            echo "An error occurred: " . $e->getMessage();
        }
    }
    deleteFileFromDrive($driveService, $_POST['delete_file_id']);
} elseif (isset($_POST['editoffering'])) {
    $approvalBOD = isset($_POST['bod']) ? GetSQLValueString($_POST['bod'], "text") : 'NULL';
    $statusBOD = isset($_POST['status_bod']) ? GetSQLValueString($_POST['status_bod'], "text") : 'NULL';
    $offering_salary = isset($_POST['offering_salary']) ? GetSQLValueString($_POST['offering_salary'], "text") : 'NULL';
    $salary_Probation = isset($_POST['salary_Probation']) ? GetSQLValueString($_POST['salary_Probation'], "text") : 'NULL';
    $salary_Permanent = isset($_POST['salary_Permanent']) ? GetSQLValueString($_POST['salary_Permanent'], "text") : 'NULL';

    $condition = "email_id=" . $_POST['id_email'];
    $update = sprintf(
        "`offering_salary`=%s,`salary_Probation`=%s,`salary_Permanent`=%s,`manager_hcm`=%s, `status_manager_hcm`=%s, `hcm_gm`=%s, `status_hcm_gm`=%s, `gm_offering`=%s, `status_gm_offering`=%s, `bod`=%s, `status_bod`=%s, `status`=%s",
        $offering_salary,
        $salary_Probation,
        $salary_Permanent,
        GetSQLValueString($_POST['manager_hcm'], "text"),
        GetSQLValueString($_POST['status_manager_hcm'], "text"),
        GetSQLValueString($_POST['hcm_gm'], "text"),
        GetSQLValueString($_POST['status_hcm_gm'], "text"),
        GetSQLValueString($_POST['gm_offering'], "text"),
        GetSQLValueString($_POST['status_gm_offering'], "text"),
        $approvalBOD,
        $statusBOD,
        GetSQLValueString("Proses Offering", "text"),
    );

    $idfpkb = $_POST['id_fpkb'];
    $status_rekrutmen = $_POST['status_rekrutmen'];
    $status_karyawan = $_POST['status_karyawan'];
    $id_email = $_POST['id_email'];

    $mdlname = "NOTIFICATION";
    $DBNOTIF = get_conn($mdlname);
    $to = "Wahyuwinningdyah Margareta,S.S.PSI<margareta.sekar@mastersystem.co.id>";
    $from = $_SESSION['Microservices_UserName'] . '<' . $_SESSION['Microservices_UserEmail'] . '>';
    $tblnamenotif = "trx_notification";
    $notifmsg = $idfpkb . "; " . $_POST['project_code'] . "; ";
    $idfpkb = str_replace(' ', '_', $_POST['id_fpkb']); // Mengganti spasi dengan underscore
    // $notif_link = "index.php?mod=hcm_requirement&act=editapproval&id=" . $_POST['id'] . "&project_code=" . $_POST['project_code'] . "&submit=Submit";
    $notif_link = "index.php?mod=hcm_requirement&act=editoffering&id=" . $_POST['id_email'] . "&id_fpkb=" . $idfpkb . "&submit=Submit";
    $insert = sprintf(
        "(`notif_from`, `notif_to`, `notif_subject`, `notif_post`, `notif_link`) VALUES (%s, %s, %s, %s, %s)",
        GetSQLValueString($from, "text"),
        GetSQLValueString($to, "text"),
        GetSQLValueString(ucwords("Submit Offering Nama Kandidat ") . $_POST['nama_kandidat'], "text"),
        GetSQLValueString($notifmsg, "text"),
        GetSQLValueString($notif_link, "text")
    );
    $res = $DBNOTIF->insert_data($tblnamenotif, $insert);
    // var_dump($notif_link);
    // die;

    if ($_POST['status_karyawan'] == "Percobaan") {
        $linkdissaprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval Offering] Offering Recruitment-' . $idfpkb . '-' . $id_email . '&body=[Dissaprove] - Manager hcm%0D%0A[notes] - Manager hcm';
        $linkapprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval Offering] Offering Recruitment-' . $idfpkb . '-' . $id_email . '&body=[Approve] - Manager hcm%0D%0A[notes] - Manager hcm';
        // $to = $entry_by . "," . $_POST['gm'] . "," . $_POST['gm_hcm'] . "," . $_POST['gm_bod'];
        // $to = 'malik.aulia@mastersystem.co.id'; 
        $to = $_POST['manager_hcm'];
        $from = "MSIZone<msizone@mastersystem.co.id>";
        $cc = "recruitment.team@mastersystem.co.id";
        // $cc = 'malik.aulia@mastersystem.co.id';
        $bcc = "";
        $reply = $from;
        $subject = "[Permohonan Approval Offering] Offering Recruitment";
        $msg = '<table width="100%">';
        $msg .= '    <tr><td width="20%" rowspan="4"></td>';
        $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
        $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
        $msg .= '    <td width="20%" rowspan="4"></tr>';
        $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
        // $msg .= '        <p>INI status percobaan</p>';
        $msg .= '        <p>Dear ' . $to . '</p>';
        $msg .= '        <p>Berikut ini adalah daftar Request Offering Karyawan yang menunggu approval dari Anda.</p>';
        $msg .= '        <p>';
        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
        $msg .= '                <tr><td style="width: 20%;">FPKB Number</td><td>:</td><td>' . htmlspecialchars($_POST['id_fpkb']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Division</td><td>:</td><td>' . htmlspecialchars($_POST['divisi']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Position</td><td>:</td><td>' . htmlspecialchars($_POST['posisi']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Nama Kandidat</td><td>:</td><td>' . htmlspecialchars($_POST['nama_kandidat']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Reason</td><td>:</td><td>' . htmlspecialchars($_POST['status_rekrutmen']);
        if ($_POST['status_rekrutmen'] == 'Penggantian') {
            $msg .= ' ' . htmlspecialchars($_POST['reason_penggantian']);
        }
        $msg .= '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status Employee</td><td>:</td><td>' . htmlspecialchars($_POST['status_karyawan']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Source</td><td>:</td><td>' . htmlspecialchars($_POST['kandidat']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Salary Probation</td><td>:</td><td>' . htmlspecialchars($_POST['salary_Probation']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Salary Permanent</td><td>:</td><td>' . htmlspecialchars($_POST['salary_Permanent']) . '</td></tr>';
        $msg .= '            </table>';
        $msg .=
            '            <p>Base On Project:</p>';
        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
        $msg .= '                <tr><td style="width: 20%;">Project Code</td><td>:</td><td>' . htmlspecialchars($_POST['project_code']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Project Name</td><td>:</td><td>' . htmlspecialchars($_POST['project_name']) . '</td></tr>';
        $msg .= '            </table>';
        $msg .= '            <p>Approval:</p>';
        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';

        $msg .= '                <tr><td style="width: 20%;">By Manager HCM</td><td>:</td><td>' . htmlspecialchars($_POST['manager_hcm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_manager_hcm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td> Approve </td></tr>';
        $msg .= '            </table>';
        $msg .= '        <table>';
        $msg .= '</p>';
        $msg .= '<p>Berikut adalah Offering Kandidat. Mohon Reviewnya</p>';
        $msg .= '<table border="1">';
        $msg .= '<tr>';
        $msg .= '<th>Nama File</th>';
        $msg .= '</tr>';

        // Add all filenames and IDs to the email content
        foreach ($_POST['malik'] as $index => $nama) {
            $file_id = $_POST['id_file'][$index];
            $id_filedrive = 'https://drive.google.com/file/d/' . $file_id . '/view';
            $msg .= '<tr>';
            $msg .= '<td><a href="' . $id_filedrive . '">' . $nama . '</a></td>';
            $msg .= '</tr>';
        }

        $msg .= '</table>';
        $msg .= '<p>Silahkan direview untuk kelayakan mendapatkan approval. Jika Approve silahkan klik tombol Approve atau tombol Reject bila ditolak.</p>';
        $msg .= '        <table style="width: 100%; text-align: center;">';  // Aligning the table to center
        $msg .= '        <tr>';
        $msg .= '        <td style="padding: 10px;">';  // Adding padding around the cells
        $msg .= '            <a href="' . $linkapprove . '" style="display: inline-block; padding: 10px 20px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px;">Approve</a>';  // Changed color to blue
        $msg .= '        </td>';
        $msg .= '        <td style="padding: 10px;">';  // Adding padding around the cells
        $msg .= '            <a href="' . $linkdissaprove . '" style="display: inline-block; padding: 10px 20px; background-color: #f44336; color: white; text-decoration: none; border-radius: 5px;">Disapprove</a>';  // Keeping color red
        $msg .= '        </td>';
        $msg .= '        </tr>';
        $msg .= '        </table>';
        $msg .= '        <p>Terimakasih</p>';
        $msg .= '    </td>';
        $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
        $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
        $msg .= "</table>";
        // echo $msg;
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
    if ($_POST['status_karyawan'] != "Percobaan") {
        $linkdissaprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval Offering] Offering Recruitment-' . $idfpkb . '-' . $id_email . '&body=[Dissaprove] - Manager hcm%0D%0A[notes] - Manager hcm';
        $linkapprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval Offering] Offering Recruitment-' . $idfpkb . '-' . $id_email . '&body=[Approve] - Manager hcm%0D%0A[notes] - Manager hcm';
        $to = $_POST['manager_hcm'];
        $from = "MSIZone<msizone@mastersystem.co.id>";
        $cc = "recruitment.team@mastersystem.co.id";
        $bcc = "";
        $reply = $from;
        $subject = "[Permohonan Approval Offering] Offering Recruitment";
        $msg = '<table width="100%">';
        $msg .= '    <tr><td width="20%" rowspan="4"></td>';
        $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
        $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
        $msg .= '    <td width="20%" rowspan="4"></tr>';
        $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
        // $msg .= '        <p>INI status selain percobaan</p>';
        $msg .= '        <p>Dear ' . $to . '</p>';
        $msg .= '        <p>Berikut ini adalah daftar Request Offering Karyawan yang menunggu approval dari Anda.</p>';
        $msg .= '        <p>';
        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
        $msg .= '                <tr><td style="width: 20%;">FPKB Number</td><td>:</td><td>' . htmlspecialchars($_POST['id_fpkb']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Division</td><td>:</td><td>' . htmlspecialchars($_POST['divisi']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Position</td><td>:</td><td>' . htmlspecialchars($_POST['posisi']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Nama Kandidat</td><td>:</td><td>' . htmlspecialchars($_POST['nama_kandidat']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Reason</td><td>:</td><td>' . htmlspecialchars($_POST['status_rekrutmen']);
        if ($_POST['status_rekrutmen'] == 'Penggantian') {
            $msg .= ' ' . htmlspecialchars($_POST['reason_penggantian']);
        }
        $msg .= '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status Employee</td><td>:</td><td>' . htmlspecialchars($_POST['status_karyawan']) . '</td></tr>';

        $msg .= '                <tr><td style="width: 20%;">Source</td><td>:</td><td>' . htmlspecialchars($_POST['kandidat']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Salary Offering</td><td>:</td><td>' . htmlspecialchars($_POST['offering_salary']) . '</td></tr>';
        $msg .= '            </table>';
        $msg .= '            <p>Base On Project:</p>';
        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
        $msg .= '                <tr><td style="width: 20%;">Project Code</td><td>:</td><td>' . htmlspecialchars($_POST['project_code']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Project Name</td><td>:</td><td>' . htmlspecialchars($_POST['project_name']) . '</td></tr>';
        $msg .= '            </table>';
        $msg .= '            <p>Approval:</p>';
        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';

        $msg .= '                <tr><td style="width: 20%;">By Manager HCM</td><td>:</td><td>' . htmlspecialchars($_POST['manager_hcm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_manager_hcm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td> Approve </td></tr>';
        $msg .= '            </table>';
        $msg .= '        <table>';
        $msg .= '</p>';
        $msg .= '<p>Berikut adalah Offering Kandidat. Mohon Reviewnya</p>';
        $msg .= '<table border="1">';
        $msg .= '<tr>';
        $msg .= '<th>Nama File</th>';
        $msg .= '</tr>';

        // Add all filenames and IDs to the email content
        foreach ($_POST['malik'] as $index => $nama) {
            $file_id = $_POST['id_file'][$index];
            $id_filedrive = 'https://drive.google.com/file/d/' . $file_id . '/view';
            $msg .= '<tr>';
            $msg .= '<td><a href="' . $id_filedrive . '">' . $nama . '</a></td>';
            $msg .= '</tr>';
        }

        $msg .= '</table>';
        $msg .= '<p>Silahkan direview untuk kelayakan mendapatkan approval. Jika Approve silahkan klik tombol Approve atau tombol Reject bila ditolak.</p>';
        $msg .= '        <table style="width: 100%; text-align: center;">';  // Aligning the table to center
        $msg .= '        <tr>';
        $msg .= '        <td style="padding: 10px;">';  // Adding padding around the cells
        $msg .= '            <a href="' . $linkapprove . '" style="display: inline-block; padding: 10px 20px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px;">Approve</a>';  // Changed color to blue
        $msg .= '        </td>';
        $msg .= '        <td style="padding: 10px;">';  // Adding padding around the cells
        $msg .= '            <a href="' . $linkdissaprove . '" style="display: inline-block; padding: 10px 20px; background-color: #f44336; color: white; text-decoration: none; border-radius: 5px;">Disapprove</a>';  // Keeping color red
        $msg .= '        </td>';
        $msg .= '        </tr>';
        $msg .= '        </table>';
        $msg .= '        <p>Terimakasih</p>';
        $msg .= '    </td>';
        $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
        $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
        $msg .= "</table>";
        // echo $msg;
        $headers = "From: " . $from . "\r\n" .
            "Reply-To: " . $reply . "\r\n" .
            "Cc: " . $cc . "\r\n" .
            "Bcc: " . $bcc . "\r\n" .
            "MIME-Version: 1.0" . "\r\n" .
            "Content-Type: text/html; charset=UTF-8" . "\r\n" .
            "X-Mailer: PHP/" . phpversion();
        // var_dump($headers);
        // die;
        $ALERT = new Alert();
        if (mail($to, $subject, $msg, $headers)) {
            echo "Email terkirim pada jam " . date("d M Y G:i:s");
        } else {
            echo "Email gagal terkirim pada jam " . date("d M Y G:i:s");
        }
    }
    $res = $DBHCM->update_data("hcm_requirement_interview", $update, $condition);
    $project_code = $_POST['project_code'];
    $project_name = $_POST['project_name'];
    $entry_by = $userName . " <" . $_SESSION['Microservices_UserEmail'] . ">";
    $description = "Offering ini dengan ID FPKB " . $_POST['id_fpkb'] . " meminta approval dari " . $_POST['manager_hcm'] . ", " . $_POST['hcm_gm'] . " , " . $_POST['gm_offering'] . " , " . $approvalBOD;
    $insert = sprintf(
        "(`id_fpkb`,`project_code`,`project_name`,`description`,`entry_by`) VALUES (%s,%s,%s,%s,%s)",
        GetSQLValueString($_POST['id_fpkb'], "text"),
        GetSQLValueString($project_code, "text"),
        GetSQLValueString($project_name, "text"),
        GetSQLValueString($description, "text"),
        GetSQLValueString($entry_by, "text")
    );
    $res = $DBHCM->insert_data("hcm_requirement_log", $insert);
    // $condition = "id=" . $_POST['id_offering'];
    // $update = sprintf(
    //     "`status_request`=%s",
    //     GetSQLValueString("Proses Offering", "text"),
    // );
    // $res = $DBHCM->update_data($tblname, $update, $condition);

    $ALERT->savedata();
} elseif (isset($_POST['editdatacalendar'])) {
    if (isset($_POST['email_update'])) {
        $combine_con = array();
        $send_email = false; // Flag to determine if email should be sent
        $email_body = ''; // Variable to accumulate email content
        // $email_catatan = '';

        for ($i = 0; $i < count($_POST['email_update']); $i++) {
            $condition_con = "email_id=" . $_POST['email_id'][$i];

            if (empty($_POST['status'][$i])) {
                $status_update = NULL;
            } else {
                $status_update = $_POST['status'][$i];
            }

            $combine_con[] = array($_POST['catatan_update'][$i], $_POST['status'][$i]);

            // Ensure only one update per loop iteration
            if ($_POST['status'][$i] == 'No') {
                $update = sprintf(
                    "`catatan` = %s, `status_cv` = %s",
                    GetSQLValueString($_POST['catatan_update'][$i], "text"),
                    GetSQLValueString($_POST['status'][$i], "text")
                );


                $DBHCM->update_data("hcm_requirement_interview", $update, $condition_con);
            }

            if ($_POST['status'][$i] == 'Yes') {
                $update = sprintf(
                    "`catatan` = %s, `status_cv` = %s, `status` = %s",
                    GetSQLValueString($_POST['catatan_update'][$i], "text"),
                    GetSQLValueString($_POST['status'][$i], "text"),
                    GetSQLValueString("Proses Offering", "text")
                );


                $DBHCM->update_data("hcm_requirement_interview", $update, $condition_con);
            }

            if ($_POST['status'][$i] == 'Yes') {
                $send_email = true; // Set flag to true

                // Build the row for email body
                $email_body .= '<tr>';
                $email_body .= '<td>' . $_POST['email_update'][$i] . '</td>';
                $email_body .= '<td>' . $_POST['catatan_update'][$i] . '</td>';
                $email_body .= '</tr>';
            }
        }

        // If $send_email is true, send the email with accumulated data
        if ($send_email) {
            $to = $_POST['assign_requirement'];

            $from = "MSIZone<msizone@mastersystem.co.id>";
            $cc = "recruitment.team@mastersystem.co.id";
            $bcc = "";
            $reply = $from;
            $subject = "[Hasil Interview] " . $_POST['posisi'] . " -" . $_POST['id_fpkb'];


            // Construct email message
            $msg = '<table width="100%">';
            $msg .= '    <tr><td width="20%" rowspan="4"></td>';
            $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
            $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
            $msg .= '    <td width="20%" rowspan="4"></tr>';
            $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
            $msg .= '        <p>Dear ' . $to . '</p>';
            $msg .= '        <p>';
            $msg .= '<table width="80%">';
            $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $_POST['id_fpkb'] . '</td></tr>';
            $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi'] . '</td></tr>';
            $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
            $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $_POST['project_code'] . '</td></tr>';
            $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $_POST['kandidat'] . '</td></tr>';
            $msg .= '<tr><td>Jumlah Dibutuhkan</td><td>:</td><td>' . $_POST['jumlah_dibutuhkan'] . '</td></tr>';
            $msg .= '<tr><td>Assign Recruiter</td><td>:</td><td>' . $_POST['assign_requirement'] . '</td></tr>';
            $msg .= '</table>';
            $msg .= '</p>';
            $msg .= '        <p>Berikut ini adalah daftar Kandidat karyawan yang mendapatkan Rekomendasi :</p>';
            $msg .= '<table border="1">';
            $msg .= '<tr><th>Email Kandidat</th>';
            $msg .= '<th>Catatan</th></tr>';
            $msg .= $email_body; // Append accumulated email body rows
            $msg .= '</table>';
            $msg .= '        <p>Terimakasih</p>';
            $msg .= '    </td>';
            $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
            $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
            $msg .= "</table>";

            // Prepare email headers
            $headers = "From: " . $from . "\r\n" .
                "Reply-To: " . $reply . "\r\n" .
                "Cc: " . $cc . "\r\n" .
                "Bcc: " . $bcc . "\r\n" .
                "MIME-Version: 1.0" . "\r\n" .
                "Content-Type: text/html; charset=UTF-8" . "\r\n" .
                "X-Mailer: PHP/" . phpversion();

            // Send email
            if (mail($to, $subject, $msg, $headers)) {
                echo "Email terkirim pada jam " . date("d M Y G:i:s");
            } else {
                echo "Email gagal terkirim pada jam " . date("d M Y G:i:s");
            }
        }
    }
    $tanggal = $_POST['tanggal_interview_update'][0];
    $id_calendar = $_POST['id_calendar'][0];
    $combine_con = array();
    for ($i = 0; $i < count($_POST['email_update']); $i++) {
        $condition_con = "email_id=" . $_POST['email_id'][$i] . "";
        if (empty($_POST['status'][$i])) {
            $status_update = NULL;
        } else {
            $status_update = $_POST['status'][$i];
        }
        $combine_con[] = array($_POST['link_webex'][$i], $_POST['tanggal_interview_update'][$i], $_POST['catatan_update'][$i], $_POST['status'][$i]);
        foreach ($combine_con as $value) {
            $update = sprintf(
                "`link_webex` = %s,`tanggal_interview` = %s,`catatan` = %s,`status_cv` = %s",
                GetSQLValueString($value[0], "text"),
                GetSQLValueString($value[1], "text"),
                GetSQLValueString($value[2], "text"),
                GetSQLValueString($value[3], "text")
            );
            $DBHCM->update_data("hcm_requirement_interview", $update, $condition_con);
        }
        // var_dump($_POST['tanggal_interview_update'][$i]);
        $posisi = $_POST['posisi'];
        $divisi = $_POST['divisi'];
        $permalink = $_POST['link_webex'][$i];

        $tanggal_interview_update = $_POST['tanggal_interview_update'][$i];
        $startWorkDateTime = new DateTime($tanggal_interview_update, new DateTimeZone('Asia/Jakarta'));

        $picupdate = $_POST['pic_update'][0];
        preg_match('/<(.+?)>/', $picupdate, $matches);
        $emailpicupdate = $matches[1];
        // Mengatur durasi acara menjadi 1 jam setelah waktu mulai
        $endWorkDateTime = clone $startWorkDateTime;
        $endWorkDateTime->modify('+1 hour');

        // Mengonversi waktu ke format yang sesuai dengan Google Calendar API
        $startDateTime = $startWorkDateTime->format(DateTime::RFC3339);
        $endDateTime = $endWorkDateTime->format(DateTime::RFC3339);

        $client = getClient();
        $service = new Google_Service_Calendar($client);

        // Define the calendar ID and event ID you want to update
        $calendarId = "$emailpicupdate";

        $eventId = $_POST['id_calendar'][$i];
        // var_dump($eventId);
        // die;

        // Get the event
        $event = $service->events->get($calendarId, $eventId);

        // Set the start time
        $event->setDescription("Dear All, berikut jadwal interview kandidat dengan Divisi : $divisi dan Posisi : $posisi dengan link sebagai berikut: $permalink"); // Ganti dengan deskripsi baru yang diinginkan

        // Set the start time
        $start = new Google_Service_Calendar_EventDateTime();
        $start->setDateTime($startDateTime);
        $event->setStart($start);

        // Set the end time
        $end = new Google_Service_Calendar_EventDateTime();
        $end->setDateTime($endDateTime);
        $event->setEnd($end);

        // Update the event on the calendar
        $updatedEvent = $service->events->update($calendarId, $event->getId(), $event);

        // echo 'Event updated: ' . $updatedEvent->htmlLink;
    }
} elseif (isset($_POST['hold_offering'])) {
    $condition = "email_id='" . $_POST['id_email'] . "'";
    $update = sprintf(
        "`status`=%s",
        GetSQLValueString("Hold Offering", "text")
    );
    $res = $DBHCM->update_data("hcm_requirement_interview", $update, $condition);
} elseif (isset($_POST['reopen_offering'])) {
    $condition = "email_id='" . $_POST['id_email'] . "'";
    $update = sprintf(
        "`status`=%s,`manager_hcm`=%s,`status_manager_hcm`=%s,`catatan_manager_hcm`=%s,`hcm_gm`=%s,`status_hcm_gm`=%s,`catatan_hcm_gm`=%s,`gm_offering`=%s,`status_gm_offering`=%s,`catatan_gm_offering`=%s,`bod`=%s,`status_bod`=%s,`catatan_bod`=%s",
        GetSQLValueString("Proses Interview", "text"),
        GetSQLValueString(NULL, "text"),
        GetSQLValueString(NULL, "text"),
        GetSQLValueString(NULL, "text"),
        GetSQLValueString(NULL, "text"),
        GetSQLValueString(NULL, "text"),
        GetSQLValueString(NULL, "text"),
        GetSQLValueString(NULL, "text"),
        GetSQLValueString(NULL, "text"),
        GetSQLValueString(NULL, "text"),
        GetSQLValueString(NULL, "text"),
        GetSQLValueString(NULL, "text"),
        GetSQLValueString(NULL, "text")
    );
    $res = $DBHCM->update_data("hcm_requirement_interview", $update, $condition);
} elseif (isset($_POST['cancel_offering'])) {
    $condition = "email_id='" . $_POST['id_email'] . "'";
    $update = sprintf(
        "`status`=%s",
        GetSQLValueString("Cancel Offering", "text")
    );
    $res = $DBHCM->update_data("hcm_requirement_interview", $update, $condition);
} elseif (isset($_POST['complete_offering'])) {

    $condition = "email_id='" . $_POST['id_email'] . "'";
    $update = sprintf(
        "`join_date`=%s,`status`=%s",
        GetSQLValueString($_POST['join_date'], "date"),
        GetSQLValueString("Complete Offering", "text")
    );
    $res = $DBHCM->update_data("hcm_requirement_interview", $update, $condition);
    $ALERT->savedata();

    $userName = addslashes($_SESSION['Microservices_UserName']);
    $project_code = $_POST['project_code'];
    $project_name = $_POST['project_name'];
    $entry_by = $userName . " <" . $_SESSION['Microservices_UserEmail'] . ">";
    $description = "Kandidat dengan nama " . $_POST['nama_kandidat'] . " dengan email " . $_POST['email'] . " status sudah complete offering dan akan join pada tanggal " . $_POST['join_date'];
    $insert = sprintf(
        "(`id_fpkb`,`project_code`,`project_name`,`description`,`entry_by`) VALUES (%s,%s,%s,%s,%s)",
        GetSQLValueString($_POST['id_fpkb'], "text"),
        GetSQLValueString($project_code, "text"),
        GetSQLValueString($project_name, "text"),
        GetSQLValueString($description, "text"),
        GetSQLValueString($entry_by, "text")
    );
    $res = $DBHCM->insert_data("hcm_requirement_log", $insert);
    $to = $_POST['pic'];
    $from = "MSIZone<msizone@mastersystem.co.id>";
    // $from = "MSIZone<msizone@mastersystem.co.id>";
    $cc = "recruitment.team@mastersystem.co.id";
    $bcc = "";
    $reply = $from;
    $subject = "[Karyawan Join] Recruitment";
    $msg = '<table width="100%">';
    $msg .= '    <tr><td width="20%" rowspan="4"></td>';
    $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
    $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
    $msg .= '    <td width="20%" rowspan="4"></tr>';
    $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
    $msg .= '        <p>Dear ' . $to . '</p>';
    $msg .= '        <p>Berikut ini adalah daftar Offering Request Karyawan sudah complete.</p>';
    $msg .= '        <p>';
    $msg .= '<table width="80%">';
    $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $_POST['id_fpkb'] . '</td></tr>';
    $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi'] . '</td></tr>';
    $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
    $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $_POST['project_code'] . '</td></tr>';
    $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $_POST['status_rekrutmen'] . '</td></tr>';
    $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $_POST['status_karyawan'] . '</td></tr>';
    $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $_POST['nama_kandidat'] . '</td></tr>';
    $msg .= '<tr><td>Tanggal Bergabung</td><td>:</td><td>' . $_POST['join_date'] . '</td></tr>';
    $msg .= '</table>';
    $msg .= '</p>';
    $msg .= '        <p>Terimakasih</p>';
    $msg .= '    </td>';
    $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
    $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
    $msg .= "</table>";
    // echo $msg;
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
    $totalcomplete = $DBHCM->get_sqlV2("SELECT COUNT(*) AS jumlah_complete FROM sa_hcm_requirement_interview r WHERE r.id_fpkb = '" . $_POST['id_fpkb'] . "' AND EXISTS (SELECT 1 FROM sa_hcm_requirement i WHERE i.id_fpkb = r.id_fpkb AND r.status = 'Complete Offering');");
    $total = $totalcomplete[0];
    if ($_POST['jumlah_dibutuhkan'] == $total['jumlah_complete']) {
        echo "jumlah yang dibutuhkan sudah sama";

        $condition1 = "id_fpkb='" . $_POST['id_fpkb'] . "'";
        $update1 = sprintf(
            "`status_request`=%s",
            // GetSQLValueString($_POST['join_date'], "date"),
            GetSQLValueString("Request Complete", "text")
        );
        $res1 = $DBHCM->update_data("hcm_requirement", $update1, $condition1);
        $ALERT->savedata();
    }
    // else {
    //     // echo "jumlah yang dibutuhkan adalah " . $_POST['jumlah_dibutuhkan'] . "dan yang jumlah yang baru mendapatkan complete adalah " . $_POST['jumlah_complete'];
    //     $condition = "email_id='" . $_POST['id_email'] . "'";
    //     $update = sprintf(
    //         "`join_date`=%s,`status`=%s",
    //         GetSQLValueString($_POST['join_date'], "date"),
    //         GetSQLValueString("Complete Offering", "text")
    //     );
    //     $res = $DBHCM->update_data("hcm_requirement_interview", $update, $condition);
    //     $to = $_POST['pic'];
    //     $from = "MSIZone<msizone@mastersystem.co.id>";
    //     // $from = "MSIZone<msizone@mastersystem.co.id>";
    //     $cc = "recruitment.team@mastersystem.co.id";
    //     $bcc = "";
    //     $reply = $from;
    //     $subject = "[TESTING] Recruitment";
    //     $msg = '<table width="100%">';
    //     $msg .= '    <tr><td width="20%" rowspan="4"></td>';
    //     $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
    //     $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
    //     $msg .= '    <td width="20%" rowspan="4"></tr>';
    //     $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
    //     $msg .= '        <p>Dear' . $to . '</p>';
    //     $msg .= '        <p>Berikut ini adalah daftar Offering Request Karyawan sudah complete.</p>';
    //     $msg .= '        <p>';
    //     $msg .= '<table width="80%">';
    //     $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $_POST['id_fpkb'] . '</td></tr>';
    //     $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi'] . '</td></tr>';
    //     $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
    //     $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $_POST['project_code'] . '</td></tr>';
    //     $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $_POST['status_rekrutmen'] . '</td></tr>';
    //     $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $_POST['status_karyawan'] . '</td></tr>';
    //     $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $_POST['nama_kandidat'] . '</td></tr>';
    //     $msg .= '</table>';
    //     $msg .= '</p>';
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
    // }
} elseif (isset($_POST['approve_manager_hcm_offering'])) {
    // echo "masuk sini";

    $mdlname = "NOTIFICATION";
    $DBNOTIF = get_conn($mdlname);
    $to = "Wahyuwinningdyah Margareta,S.S.PSI<margareta.sekar@mastersystem.co.id>";
    $from = $_POST['manager_hcm'];
    $tblnamenotif = "trx_notification";
    $notifmsg = $_POST['id_fpkb'] . "; " . $_POST['project_code'] . "; ";
    $idfpkb = str_replace(' ', '_', $_POST['id_fpkb']); // Mengganti spasi dengan underscore
    // $notif_link = "index.php?mod=hcm_requirement&act=editapproval&id=" . $_POST['id'] . "&project_code=" . $_POST['project_code'] . "&submit=Submit";
    $notif_link = "index.php?mod=hcm_requirement&act=editoffering&id=" . $_POST['id_email'] . "&id_fpkb=" . $idfpkb . "&submit=Submit";
    $insert = sprintf(
        "(`notif_from`, `notif_to`, `notif_subject`, `notif_post`, `notif_link`) VALUES (%s, %s, %s, %s, %s)",
        GetSQLValueString($from, "text"),
        GetSQLValueString($to, "text"),
        GetSQLValueString(ucwords("Approve Offering Nama Kandidat ") . $_POST['nama_kandidat'], "text"),
        GetSQLValueString($notifmsg, "text"),
        GetSQLValueString($notif_link, "text")
    );
    $res = $DBNOTIF->insert_data($tblnamenotif, $insert);
    // var_dump($notif_link);
    // die;


    $condition = "email_id=" . $_POST['id_email'];
    $update = sprintf(
        "`status_manager_hcm`=%s,`catatan_manager_hcm`=%s",
        GetSQLValueString("Approve", "text"),
        GetSQLValueString($_POST['catatan_manager_hcm'], "text")

    );
    $updateResult = $DBHCM->update_data('hcm_requirement_interview', $update, $condition);
    $description = $_POST['manager_hcm'] . " Telah memberikan Approve pada data dengan ID FPKB " . $_POST['id_fpkb'] . " dan email kandidat: " . $_POST['email'];
    $insert = sprintf(
        "(`id_fpkb`,`project_code`,`project_name`,`description`,`entry_by`) VALUES (%s,%s,%s,%s,%s)",
        GetSQLValueString($_POST['id_fpkb'], "text"),
        GetSQLValueString($_POST['project_code'], "text"),
        GetSQLValueString($_POST['project_name'], "text"),
        GetSQLValueString($description, "text"),
        GetSQLValueString($_POST['manager_hcm'], "text")
    );
    $res = $DBHCM->insert_data("hcm_requirement_log", $insert);
    if ($_POST['status_karyawan'] == "Percobaan") {
        $to = $_POST['gm_hcm'];
        $from = "MSIZone<msizone@mastersystem.co.id>";
        $cc = "recruitment.team@mastersystem.co.id";
        $linkdissaprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval Offering] Offering Recruitment-' . $_POST['id_fpkb'] . '-' . $_POST['id_email'] . '&body=[Disapprove] - GM hcm%0D%0A[notes] - GM hcm';
        $linkapprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval Offering] Offering Recruitment-' . $_POST['id_fpkb'] . '-' . $_POST['id_email'] . '&body=[Approve] - GM hcm%0D%0A[notes] - GM hcm';
        $from = "MSIZone<msizone@mastersystem.co.id>";
        // $cc = "malik.aulia@mastersystem.co.id";
        $bcc = "";
        $reply = $from;
        $subject = "[Permohonan Approval Offering] Offering Recruitment";
        $msg = '<table width="100%">';
        $msg .= '    <tr><td width="20%" rowspan="4"></td>';
        $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
        $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
        $msg .= '    <td width="20%" rowspan="4"></tr>';
        $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
        $msg .= '        <p>Dear ' . $to . '</p>';
        $msg .= '        <p>Berikut ini adalah daftar Offering Request Karyawan yang menunggu approval dari Anda.</p>';
        $msg .= '        <p>';

        // $msg .= '<table width="80%">';
        // $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $_POST['id_fpkb'] . '</td></tr>';
        // $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi'] . '</td></tr>';
        // $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
        // $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $_POST['project_code'] . '</td></tr>';
        // $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $_POST['status_rekrutmen'] . '</td></tr>';
        // $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $_POST['status_karyawan'] . '</td></tr>';
        // $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $_POST['kandidat'] . '</td></tr>';
        // $msg .= '<tr><td>Nama Kandidat</td><td>:</td><td>' . $_POST['nama_kandidat'] . '</td></tr>';
        // $msg .= '<tr><td>Salary Probation</td><td>:</td><td>' . $_POST['salary_Probation'] . '</td></tr>';
        // $msg .= '<tr><td>Salary Permanent</td><td>:</td><td>' . $_POST['salary_Permanent'] . '</td></tr>';
        // $msg .= '</table>';



        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
        $msg .= '                <tr><td style="width: 20%;">FPKB Number</td><td>:</td><td>' . htmlspecialchars($_POST['id_fpkb']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Division</td><td>:</td><td>' . htmlspecialchars($_POST['divisi']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Position</td><td>:</td><td>' . htmlspecialchars($_POST['posisi']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Nama Kandidat</td><td>:</td><td>' . htmlspecialchars($_POST['nama_kandidat']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Reason</td><td>:</td><td>' . htmlspecialchars($_POST['status_rekrutmen']);
        if ($_POST['status_rekrutmen'] == 'Penggantian') {
            $msg .= ' ' . htmlspecialchars($_POST['reason_penggantian']);
        }
        $msg .= '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status Employee</td><td>:</td><td>' . htmlspecialchars($_POST['status_karyawan']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Source</td><td>:</td><td>' . htmlspecialchars($_POST['kandidat']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Salary Probation</td><td>:</td><td>' . htmlspecialchars($_POST['salary_Probation']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Salary Permanent</td><td>:</td><td>' . htmlspecialchars($_POST['salary_Permanent']) . '</td></tr>';
        $msg .= '            </table>';
        $msg .=
            '            <p>Base On Project:</p>';
        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
        $msg .= '                <tr><td style="width: 20%;">Project Code</td><td>:</td><td>' . htmlspecialchars($_POST['project_code']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Project Name</td><td>:</td><td>' . htmlspecialchars($_POST['project_name']) . '</td></tr>';
        $msg .= '            </table>';
        $msg .= '            <p>Approval:</p>';
        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';

        $msg .= '                <tr><td style="width: 20%;">By Manager HCM</td><td>:</td><td>' . htmlspecialchars($_POST['manager_hcm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_manager_hcm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td> Approve </td></tr>';
        $msg .= '            </table>';
        $msg .= '        <table>';

        $msg .= '</p>';



        $msg .= '</p>';
        $msg .= '<p>Berikut adalah Offering Kandidat. Mohon Reviewnya</p>';
        $msg .= '<table border="1">';
        $msg .= '<tr>';
        $msg .= '<th>Nama File</th>';
        $msg .= '</tr>';

        // Add all filenames and IDs to the email content
        foreach ($_POST['malik'] as $index => $nama) {
            $file_id = $_POST['id_file'][$index];
            $id_filedrive = 'https://drive.google.com/file/d/' . $file_id . '/view';
            $msg .= '<tr>';
            $msg .= '<td><a href="' . $id_filedrive . '">' . $nama . '</a></td>';
            $msg .= '</tr>';
        }

        $msg .= '</table>';


        $msg .= '<p>Silahkan direview untuk kelayakan mendapatkan approval. Jika Approve silahkan klik tombol Approve atau tombol Reject bila ditolak.</p>';
        $msg .= '        <table style="width: 100%; text-align: center;">';  // Aligning the table to center
        $msg .= '        <tr>';
        $msg .= '        <td style="padding: 10px;">';  // Adding padding around the cells
        $msg .= '            <a href="' . $linkapprove . '" style="display: inline-block; padding: 10px 20px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px;">Approve</a>';  // Changed color to blue
        $msg .= '        </td>';
        $msg .= '        <td style="padding: 10px;">';  // Adding padding around the cells
        $msg .= '            <a href="' . $linkdissaprove . '" style="display: inline-block; padding: 10px 20px; background-color: #f44336; color: white; text-decoration: none; border-radius: 5px;">Disapprove</a>';  // Keeping color red
        $msg .= '        </td>';
        $msg .= '        </tr>';
        $msg .= '        </table>';
        $msg .= '        <p>Terimakasih</p>';
        $msg .= '    </td>';


        // $msg .= '<p>Silahkan direview untuk kelayakan mendapatkan approval.</p>';
        // $msg .= '        <table>';
        // $msg .= '        <tr>';
        // $msg .= '        <p>Jika Approve silahkan klik link berikut <a href="' . $linkapprove . '">Approve</a> jika Tidak maka klik link berikut <a href="' . $linkdissaprove . '">Disapprove</a></p>';
        // $msg .= '        </table>';
        // $msg .= '        <p>Terimakasih</p>';
        // $msg .= '    </td>';
        $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
        $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
        $msg .= "</table>";
        // echo $msg;
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
    if ($_POST['status_karyawan'] != "Percobaan") {
        $to = $_POST['hcm_gm'];
        $from = "MSIZone<msizone@mastersystem.co.id>";
        $cc = "recruitment.team@mastersystem.co.id";
        $linkdissaprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval Offering] Offering Recruitment-' . $_POST['id_fpkb'] . '-' . $_POST['id_email'] . '&body=[Disapprove] - GM hcm%0D%0A[notes] - GM hcm';
        $linkapprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval Offering] Offering Recruitment-' . $_POST['id_fpkb'] . '-' . $_POST['id_email'] . '&body=[Approve] - GM hcm%0D%0A[notes] - GM hcm';
        $from = "MSIZone<msizone@mastersystem.co.id>";
        // $cc = "malik.aulia@mastersystem.co.id";
        $bcc = "";
        $reply = $from;
        $subject = "[Permohonan Approval Offering] Offering Recruitment";
        $msg = '<table width="100%">';
        $msg .= '    <tr><td width="20%" rowspan="4"></td>';
        $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
        $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
        $msg .= '    <td width="20%" rowspan="4"></tr>';
        $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
        $msg .= '        <p>Dear ' . $to . '</p>';
        $msg .= '        <p>Berikut ini adalah daftar Offering Request Karyawan yang menunggu approval dari Anda.</p>';
        $msg .= '        <p>';
        // $msg .= '<table width="80%">';
        // $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $_POST['id_fpkb'] . '</td></tr>';
        // $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi'] . '</td></tr>';
        // $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
        // $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $_POST['project_code'] . '</td></tr>';
        // $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $_POST['status_rekrutmen'] . '</td></tr>';
        // $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $_POST['status_karyawan'] . '</td></tr>';
        // $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $_POST['kandidat'] . '</td></tr>';
        // $msg .= '<tr><td>Nama Kandidat</td><td>:</td><td>' . $_POST['nama_kandidat'] . '</td></tr>';
        // $msg .= '<tr><td>Salary Offering</td><td>:</td><td>' . $_POST['offering_salary'] . '</td></tr>';
        // $msg .= '</table>';
        // $msg .= '</p>';


        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
        $msg .= '                <tr><td style="width: 20%;">FPKB Number</td><td>:</td><td>' . htmlspecialchars($_POST['id_fpkb']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Division</td><td>:</td><td>' . htmlspecialchars($_POST['divisi']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Position</td><td>:</td><td>' . htmlspecialchars($_POST['posisi']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Nama Kandidat</td><td>:</td><td>' . htmlspecialchars($_POST['nama_kandidat']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Reason</td><td>:</td><td>' . htmlspecialchars($_POST['status_rekrutmen']);
        if ($_POST['status_rekrutmen'] == 'Penggantian') {
            $msg .= ' ' . htmlspecialchars($_POST['reason_penggantian']);
        }
        $msg .= '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status Employee</td><td>:</td><td>' . htmlspecialchars($_POST['status_karyawan']) . '</td></tr>';

        $msg .= '                <tr><td style="width: 20%;">Source</td><td>:</td><td>' . htmlspecialchars($_POST['kandidat']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Salary Offering</td><td>:</td><td>' . htmlspecialchars($_POST['offering_salary']) . '</td></tr>';
        $msg .= '            </table>';
        $msg .= '            <p>Base On Project:</p>';
        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
        $msg .= '                <tr><td style="width: 20%;">Project Code</td><td>:</td><td>' . htmlspecialchars($_POST['project_code']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Project Name</td><td>:</td><td>' . htmlspecialchars($_POST['project_name']) . '</td></tr>';
        $msg .= '            </table>';
        $msg .= '            <p>Approval:</p>';
        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';

        $msg .= '                <tr><td style="width: 20%;">By Manager HCM</td><td>:</td><td>' . htmlspecialchars($_POST['manager_hcm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_manager_hcm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td> Approve </td></tr>';
        $msg .= '            </table>';
        $msg .= '        <table>';

        $msg .= '</p>';



        $msg .= '<p>Berikut adalah Offering Kandidat. Mohon Reviewnya</p>';
        $msg .= '<table border="1">';
        $msg .= '<tr>';
        $msg .= '<th>Nama File</th>';
        $msg .= '</tr>';

        // Add all filenames and IDs to the email content
        foreach ($_POST['malik'] as $index => $nama) {
            $file_id = $_POST['id_file'][$index];
            $id_filedrive = 'https://drive.google.com/file/d/' . $file_id . '/view';
            $msg .= '<tr>';
            $msg .= '<td><a href="' . $id_filedrive . '">' . $nama . '</a></td>';
            $msg .= '</tr>';
        }

        $msg .= '</table>';
        $msg .= '<p>Silahkan direview untuk kelayakan mendapatkan approval. Jika Approve silahkan klik tombol Approve atau tombol Reject bila ditolak.</p>';
        $msg .= '        <table style="width: 100%; text-align: center;">';  // Aligning the table to center
        $msg .= '        <tr>';
        $msg .= '        <td style="padding: 10px;">';  // Adding padding around the cells
        $msg .= '            <a href="' . $linkapprove . '" style="display: inline-block; padding: 10px 20px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px;">Approve</a>';  // Changed color to blue
        $msg .= '        </td>';
        $msg .= '        <td style="padding: 10px;">';  // Adding padding around the cells
        $msg .= '            <a href="' . $linkdissaprove . '" style="display: inline-block; padding: 10px 20px; background-color: #f44336; color: white; text-decoration: none; border-radius: 5px;">Disapprove</a>';  // Keeping color red
        $msg .= '        </td>';
        $msg .= '        </tr>';
        $msg .= '        </table>';
        $msg .= '        <p>Terimakasih</p>';
        $msg .= '    </td>';
        $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
        $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
        $msg .= "</table>";
        // echo $msg;
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
} elseif (isset($_POST['dissaprove_manager_hcm_offering'])) {
    // echo "masuk sini";
    $condition = "email_id=" . $_POST['id_email'];
    $update = sprintf(
        "`status_manager_hcm`=%s,`catatan_manager_hcm`=%s",
        GetSQLValueString("Disapprove", "text"),
        GetSQLValueString($_POST['catatan_manager_hcm'], "text")

    );
    $updateResult = $DBHCM->update_data('hcm_requirement_interview', $update, $condition);
    $mdlname = "NOTIFICATION";
    $DBNOTIF = get_conn($mdlname);
    $to = "Wahyuwinningdyah Margareta,S.S.PSI<margareta.sekar@mastersystem.co.id>";
    $from = $_POST['manager_hcm'];
    $tblnamenotif = "trx_notification";
    $notifmsg = $_POST['id_fpkb'] . "; " . $_POST['project_code'] . "; ";
    $idfpkb = str_replace(' ', '_', $_POST['id_fpkb']); // Mengganti spasi dengan underscore
    // $notif_link = "index.php?mod=hcm_requirement&act=editapproval&id=" . $_POST['id'] . "&project_code=" . $_POST['project_code'] . "&submit=Submit";
    $notif_link = "index.php?mod=hcm_requirement&act=editoffering&id=" . $_POST['id_email'] . "&id_fpkb=" . $idfpkb . "&submit=Submit";
    $insert = sprintf(
        "(`notif_from`, `notif_to`, `notif_subject`, `notif_post`, `notif_link`) VALUES (%s, %s, %s, %s, %s)",
        GetSQLValueString(
            $from,
            "text"
        ),
        GetSQLValueString($to, "text"),
        GetSQLValueString(ucwords("Disapprove Offering Nama Kandidat ") . $_POST['nama_kandidat'], "text"),
        GetSQLValueString($notifmsg, "text"),
        GetSQLValueString($notif_link, "text")
    );
    $res = $DBNOTIF->insert_data($tblnamenotif, $insert);
    $description = $_POST['manager_hcm'] . " Telah memberikan Disapprove pada data dengan ID FPKB " . $_POST['id_fpkb'] . " dan email kandidat: " . $_POST['email'];
    $insert = sprintf(
        "(`id_fpkb`,`project_code`,`project_name`,`description`,`entry_by`) VALUES (%s,%s,%s,%s,%s)",
        GetSQLValueString($_POST['id_fpkb'], "text"),
        GetSQLValueString($_POST['project_code'], "text"),
        GetSQLValueString($_POST['project_name'], "text"),
        GetSQLValueString($description, "text"),
        GetSQLValueString($_POST['manager_hcm'], "text")
    );
    $res = $DBHCM->insert_data("hcm_requirement_log", $insert);
    if ($_POST['status_karyawan'] == "Percobaan") {
        $to = $_POST['gm_hcm'];
        $from = "MSIZone<msizone@mastersystem.co.id>";
        $cc = "recruitment.team@mastersystem.co.id";
        $linkdissaprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval Offering] Offering Recruitment-' . $_POST['id_fpkb'] . '-' . $_POST['id_email'] . '&body=[Disapprove] - GM hcm%0D%0A[notes] - GM hcm';
        $linkapprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval Offering] Offering Recruitment-' . $_POST['id_fpkb'] . '-' . $_POST['id_email'] . '&body=[Approve] - GM hcm%0D%0A[notes] - GM hcm';
        $from = "MSIZone<msizone@mastersystem.co.id>";
        // $cc = "malik.aulia@mastersystem.co.id";
        $bcc = "";
        $reply = $from;
        $subject = "[Permohonan Approval Offering] Offering Recruitment";
        $msg = '<table width="100%">';
        $msg .= '    <tr><td width="20%" rowspan="4"></td>';
        $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
        $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
        $msg .= '    <td width="20%" rowspan="4"></tr>';
        $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
        $msg .= '        <p>Dear ' . $to . '</p>';
        $msg .= '        <p>Berikut ini adalah daftar Offering Request Karyawan yang menunggu approval dari Anda.</p>';
        $msg .= '        <p>';
        // $msg .= '<table width="80%">';
        // $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $_POST['id_fpkb'] . '</td></tr>';
        // $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi'] . '</td></tr>';
        // $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
        // $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $_POST['project_code'] . '</td></tr>';
        // $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $_POST['status_rekrutmen'] . '</td></tr>';
        // $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $_POST['status_karyawan'] . '</td></tr>';
        // $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $_POST['kandidat'] . '</td></tr>';
        // $msg .= '<tr><td>Nama Kandidat</td><td>:</td><td>' . $_POST['nama_kandidat'] . '</td></tr>';
        // $msg .= '<tr><td>Salary Probation</td><td>:</td><td>' . $_POST['salary_Probation'] . '</td></tr>';
        // $msg .= '<tr><td>Salary Permanent</td><td>:</td><td>' . $_POST['salary_Permanent'] . '</td></tr>';
        // $msg .= '</table>';
        // $msg .= '</p>';



        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
        $msg .= '                <tr><td style="width: 20%;">FPKB Number</td><td>:</td><td>' . htmlspecialchars($_POST['id_fpkb']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Division</td><td>:</td><td>' . htmlspecialchars($_POST['divisi']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Position</td><td>:</td><td>' . htmlspecialchars($_POST['posisi']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Nama Kandidat</td><td>:</td><td>' . htmlspecialchars($_POST['nama_kandidat']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Reason</td><td>:</td><td>' . htmlspecialchars($_POST['status_rekrutmen']);
        if ($_POST['status_rekrutmen'] == 'Penggantian') {
            $msg .= ' ' . htmlspecialchars($_POST['reason_penggantian']);
        }
        $msg .= '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status Employee</td><td>:</td><td>' . htmlspecialchars($_POST['status_karyawan']) . '</td></tr>';

        $msg .= '                <tr><td style="width: 20%;">Source</td><td>:</td><td>' . htmlspecialchars($_POST['kandidat']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Salary Offering</td><td>:</td><td>' . htmlspecialchars($_POST['offering_salary']) . '</td></tr>';
        $msg .= '            </table>';
        $msg .= '            <p>Base On Project:</p>';
        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
        $msg .= '                <tr><td style="width: 20%;">Project Code</td><td>:</td><td>' . htmlspecialchars($_POST['project_code']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Project Name</td><td>:</td><td>' . htmlspecialchars($_POST['project_name']) . '</td></tr>';
        $msg .= '            </table>';
        $msg .= '            <p>Approval:</p>';
        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';

        $msg .= '                <tr><td style="width: 20%;">By Manager HCM</td><td>:</td><td>' . htmlspecialchars($_POST['manager_hcm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_manager_hcm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td> Dissaprove </td></tr>';
        $msg .= '            </table>';
        $msg .= '        <table>';

        $msg .= '</p>';



        $msg .= '<p>Berikut adalah Offering Kandidat. Mohon Reviewnya</p>';
        $msg .= '<table border="1">';
        $msg .= '<tr>';
        $msg .= '<th>Nama File</th>';
        $msg .= '</tr>';

        // Add all filenames and IDs to the email content
        foreach ($_POST['malik'] as $index => $nama) {
            $file_id = $_POST['id_file'][$index];
            $id_filedrive = 'https://drive.google.com/file/d/' . $file_id . '/view';
            $msg .= '<tr>';
            $msg .= '<td><a href="' . $id_filedrive . '">' . $nama . '</a></td>';
            $msg .= '</tr>';
        }

        $msg .= '</table>';
        $msg .= '<p>Silahkan direview untuk kelayakan mendapatkan approval. Jika Approve silahkan klik tombol Approve atau tombol Reject bila ditolak.</p>';
        $msg .= '        <table style="width: 100%; text-align: center;">';  // Aligning the table to center
        $msg .= '        <tr>';
        $msg .= '        <td style="padding: 10px;">';  // Adding padding around the cells
        $msg .= '            <a href="' . $linkapprove . '" style="display: inline-block; padding: 10px 20px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px;">Approve</a>';  // Changed color to blue
        $msg .= '        </td>';
        $msg .= '        <td style="padding: 10px;">';  // Adding padding around the cells
        $msg .= '            <a href="' . $linkdissaprove . '" style="display: inline-block; padding: 10px 20px; background-color: #f44336; color: white; text-decoration: none; border-radius: 5px;">Disapprove</a>';  // Keeping color red
        $msg .= '        </td>';
        $msg .= '        </tr>';
        $msg .= '        </table>';
        $msg .= '        <p>Terimakasih</p>';
        $msg .= '    </td>';
        $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
        $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
        $msg .= "</table>";
        // echo $msg;
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
    if ($_POST['status_karyawan'] != "Percobaan") {
        $to = $_POST['hcm_gm'];
        $from = "MSIZone<msizone@mastersystem.co.id>";
        $cc = "recruitment.team@mastersystem.co.id";
        $linkdissaprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval Offering] Offering Recruitment-' . $_POST['id_fpkb'] . '-' . $_POST['id_email'] . '&body=[Disapprove] - GM hcm%0D%0A[notes] - GM hcm';
        $linkapprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval Offering] Offering Recruitment-' . $_POST['id_fpkb'] . '-' . $_POST['id_email'] . '&body=[Approve] - GM hcm%0D%0A[notes] - GM hcm';
        $from = "MSIZone<msizone@mastersystem.co.id>";
        // $cc = "malik.aulia@mastersystem.co.id";
        $bcc = "";
        $reply = $from;
        $subject = "[Permohonan Approval Offering] Offering Recruitment";
        $msg = '<table width="100%">';
        $msg .= '    <tr><td width="20%" rowspan="4"></td>';
        $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
        $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
        $msg .= '    <td width="20%" rowspan="4"></tr>';
        $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
        $msg .= '        <p>Dear ' . $to . '</p>';
        $msg .= '        <p>Berikut ini adalah daftar Offering Request Karyawan yang menunggu approval dari Anda.</p>';
        $msg .= '        <p>';
        // $msg .= '<table width="80%">';
        // $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $_POST['id_fpkb'] . '</td></tr>';
        // $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi'] . '</td></tr>';
        // $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
        // $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $_POST['project_code'] . '</td></tr>';
        // $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $_POST['status_rekrutmen'] . '</td></tr>';
        // $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $_POST['status_karyawan'] . '</td></tr>';
        // $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $_POST['kandidat'] . '</td></tr>';
        // $msg .= '<tr><td>Nama Kandidat</td><td>:</td><td>' . $_POST['nama_kandidat'] . '</td></tr>';
        // $msg .= '<tr><td>Salary Offering</td><td>:</td><td>' . $_POST['offering_salary'] . '</td></tr>';
        // $msg .= '</table>';
        // $msg .= '</p>';



        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
        $msg .= '                <tr><td style="width: 20%;">FPKB Number</td><td>:</td><td>' . htmlspecialchars($_POST['id_fpkb']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Division</td><td>:</td><td>' . htmlspecialchars($_POST['divisi']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Position</td><td>:</td><td>' . htmlspecialchars($_POST['posisi']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Nama Kandidat</td><td>:</td><td>' . htmlspecialchars($_POST['nama_kandidat']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Reason</td><td>:</td><td>' . htmlspecialchars($_POST['status_rekrutmen']);
        if ($_POST['status_rekrutmen'] == 'Penggantian') {
            $msg .= ' ' . htmlspecialchars($_POST['reason_penggantian']);
        }
        $msg .= '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status Employee</td><td>:</td><td>' . htmlspecialchars($_POST['status_karyawan']) . '</td></tr>';

        $msg .= '                <tr><td style="width: 20%;">Source</td><td>:</td><td>' . htmlspecialchars($_POST['kandidat']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Salary Offering</td><td>:</td><td>' . htmlspecialchars($_POST['offering_salary']) . '</td></tr>';
        $msg .= '            </table>';
        $msg .= '            <p>Base On Project:</p>';
        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
        $msg .= '                <tr><td style="width: 20%;">Project Code</td><td>:</td><td>' . htmlspecialchars($_POST['project_code']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Project Name</td><td>:</td><td>' . htmlspecialchars($_POST['project_name']) . '</td></tr>';
        $msg .= '            </table>';
        $msg .= '            <p>Approval:</p>';
        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';

        $msg .= '                <tr><td style="width: 20%;">By Manager HCM</td><td>:</td><td>' . htmlspecialchars($_POST['manager_hcm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_manager_hcm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td> Dissaprove </td></tr>';
        $msg .= '            </table>';
        $msg .= '        <table>';

        $msg .= '</p>';



        $msg .= '<p>Berikut adalah Offering Kandidat. Mohon Reviewnya</p>';
        $msg .= '<table border="1">';
        $msg .= '<tr>';
        $msg .= '<th>Nama File</th>';
        $msg .= '</tr>';

        // Add all filenames and IDs to the email content
        foreach ($_POST['malik'] as $index => $nama) {
            $file_id = $_POST['id_file'][$index];
            $id_filedrive = 'https://drive.google.com/file/d/' . $file_id . '/view';
            $msg .= '<tr>';
            $msg .= '<td><a href="' . $id_filedrive . '">' . $nama . '</a></td>';
            $msg .= '</tr>';
        }

        $msg .= '</table>';
        $msg .= '<p>Silahkan direview untuk kelayakan mendapatkan approval. Jika Approve silahkan klik tombol Approve atau tombol Reject bila ditolak.</p>';
        $msg .= '        <table style="width: 100%; text-align: center;">';  // Aligning the table to center
        $msg .= '        <tr>';
        $msg .= '        <td style="padding: 10px;">';  // Adding padding around the cells
        $msg .= '            <a href="' . $linkapprove . '" style="display: inline-block; padding: 10px 20px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px;">Approve</a>';  // Changed color to blue
        $msg .= '        </td>';
        $msg .= '        <td style="padding: 10px;">';  // Adding padding around the cells
        $msg .= '            <a href="' . $linkdissaprove . '" style="display: inline-block; padding: 10px 20px; background-color: #f44336; color: white; text-decoration: none; border-radius: 5px;">Disapprove</a>';  // Keeping color red
        $msg .= '        </td>';
        $msg .= '        </tr>';
        $msg .= '        </table>';
        $msg .= '        <p>Terimakasih</p>';
        $msg .= '    </td>';
        $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
        $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
        $msg .= "</table>";
        // echo $msg;
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
    $to = $_POST['assign_requirement'];
    $from = "MSIZone<msizone@mastersystem.co.id>";
    $cc = "recruitment.team@mastersystem.co.id";
    // $linkdissaprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval Offering] Offering Recruitment-' . $_POST['id_fpkb'] . '&body=[Disapprove] - Offering bod%0D%0A[notes] - Offering bod';
    // $linkapprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval Offering] Offering Recruitment-' . $_POST['id_fpkb'] . '&body=[Approve] - Offering bod%0D%0A[notes] - Offering bod';
    $from = "MSIZone<msizone@mastersystem.co.id>";
    // $cc = "malik.aulia@mastersystem.co.id";
    $bcc = "";
    $reply = $from;
    $subject = "[Permohonan Approval Offering] Offering Recruitment";
    $msg = '<table width="100%">';
    $msg .= '    <tr><td width="20%" rowspan="4"></td>';
    $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
    $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
    $msg .= '    <td width="20%" rowspan="4"></tr>';
    $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
    $msg .= '        <p>Dear ' . $to . '</p>';
    $msg .= '        <p>Berikut ini adalah daftar Offering Request Karyawan yang mendapatkan Dissapprove.</p>';
    $msg .= '        <p>';
    // $msg .= '<table width="80%">';
    // $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $_POST['id_fpkb'] . '</td></tr>';
    // $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi'] . '</td></tr>';
    // $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
    // $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $_POST['project_code'] . '</td></tr>';
    // $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $_POST['status_rekrutmen'] . '</td></tr>';
    // $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $_POST['status_karyawan'] . '</td></tr>';
    // $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $_POST['kandidat'] . '</td></tr>';
    // $msg .= '<tr><td>Nama Kandidat</td><td>:</td><td>' . $_POST['nama_kandidat'] . '</td></tr>';
    // $msg .= '</table>';
    // $msg .= '</p>';



    $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
    $msg .= '                <tr><td style="width: 20%;">FPKB Number</td><td>:</td><td>' . htmlspecialchars($_POST['id_fpkb']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Division</td><td>:</td><td>' . htmlspecialchars($_POST['divisi']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Position</td><td>:</td><td>' . htmlspecialchars($_POST['posisi']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Nama Kandidat</td><td>:</td><td>' . htmlspecialchars($_POST['nama_kandidat']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Reason</td><td>:</td><td>' . htmlspecialchars($_POST['status_rekrutmen']);
    if ($_POST['status_rekrutmen'] == 'Penggantian') {
        $msg .= ' ' . htmlspecialchars($_POST['reason_penggantian']);
    }
    $msg .= '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Status Employee</td><td>:</td><td>' . htmlspecialchars($_POST['status_karyawan']) . '</td></tr>';

    $msg .= '                <tr><td style="width: 20%;">Source</td><td>:</td><td>' . htmlspecialchars($_POST['kandidat']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Salary Offering</td><td>:</td><td>' . htmlspecialchars($_POST['offering_salary']) . '</td></tr>';
    $msg .= '            </table>';
    $msg .= '            <p>Base On Project:</p>';
    $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
    $msg .= '                <tr><td style="width: 20%;">Project Code</td><td>:</td><td>' . htmlspecialchars($_POST['project_code']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Project Name</td><td>:</td><td>' . htmlspecialchars($_POST['project_name']) . '</td></tr>';
    $msg .= '            </table>';
    $msg .= '            <p>Approval:</p>';
    $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';

    $msg .= '                <tr><td style="width: 20%;">By Manager HCM</td><td>:</td><td>' . htmlspecialchars($_POST['manager_hcm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_manager_hcm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td> Dissaprove </td></tr>';
    $msg .= '            </table>';
    $msg .= '        <table>';

    $msg .= '</p>';



    $msg .= '<p>Berikut adalah Offering Kandidat. Mohon Reviewnya</p>';
    $msg .= '<table border="1">';
    $msg .= '<tr>';
    $msg .= '<th>Nama File</th>';
    $msg .= '</tr>';

    // Add all filenames and IDs to the email content
    foreach ($_POST['malik'] as $index => $nama) {
        $file_id = $_POST['id_file'][$index];
        $id_filedrive = 'https://drive.google.com/file/d/' . $file_id . '/view';
        $msg .= '<tr>';
        $msg .= '<td><a href="' . $id_filedrive . '">' . $nama . '</a></td>';
        $msg .= '</tr>';
    }

    $msg .= '</table>';
    $msg .= '<p>Silahkan direview untuk ditidak lanjuti.</p>';
    $msg .= '        <table>';
    $msg .= '        <tr>';
    $msg .= '        </table>';
    $msg .= '        <p>Terimakasih</p>';
    $msg .= '    </td>';
    $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
    $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
    $msg .= "</table>";
    // echo $msg;
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
} elseif (isset($_POST['approve_hcm_gm_offering'])) {
    // var_dump($_POST['project_name']);
    // die;
    $mdlname = "NOTIFICATION";
    $DBNOTIF = get_conn($mdlname);
    $to = "Wahyuwinningdyah Margareta,S.S.PSI<margareta.sekar@mastersystem.co.id>";
    $from = $_POST['hcm_gm'];
    $tblnamenotif = "trx_notification";
    $notifmsg = $_POST['id_fpkb'] . "; " . $_POST['project_code'] . "; ";
    $idfpkb = str_replace(' ', '_', $_POST['id_fpkb']); // Mengganti spasi dengan underscore
    // $notif_link = "index.php?mod=hcm_requirement&act=editapproval&id=" . $_POST['id'] . "&project_code=" . $_POST['project_code'] . "&submit=Submit";
    $notif_link = "index.php?mod=hcm_requirement&act=editoffering&id=" . $_POST['id_email'] . "&id_fpkb=" . $idfpkb . "&submit=Submit";
    $insert = sprintf(
        "(`notif_from`, `notif_to`, `notif_subject`, `notif_post`, `notif_link`) VALUES (%s, %s, %s, %s, %s)",
        GetSQLValueString($from, "text"),
        GetSQLValueString($to, "text"),
        GetSQLValueString(ucwords("Approve Offering Nama Kandidat ") . $_POST['nama_kandidat'], "text"),
        GetSQLValueString($notifmsg, "text"),
        GetSQLValueString($notif_link, "text")
    );
    $res = $DBNOTIF->insert_data($tblnamenotif, $insert);
    // var_dump($notif_link);
    // die;
    $condition = "email_id=" . $_POST['id_email'];
    $update = sprintf(
        "`status_hcm_gm`=%s,`catatan_hcm_gm`=%s",
        GetSQLValueString("Approve", "text"),
        GetSQLValueString($_POST['catatan_hcm_gm'], "text")

    );
    $updateResult = $DBHCM->update_data('hcm_requirement_interview', $update, $condition);
    $description = $_POST['hcm_gm'] . " Telah memberikan Approve pada data dengan ID FPKB " . $_POST['id_fpkb'] . " dan email kandidat: " . $_POST['email'];
    $insert = sprintf(
        "(`id_fpkb`,`project_code`,`project_name`,`description`,`entry_by`) VALUES (%s,%s,%s,%s,%s)",
        GetSQLValueString($_POST['id_fpkb'], "text"),
        GetSQLValueString($_POST['project_code'], "text"),
        GetSQLValueString($_POST['project_name'], "text"),
        GetSQLValueString($description, "text"),
        GetSQLValueString($_POST['hcm_gm'], "text")
    );
    $res = $DBHCM->insert_data("hcm_requirement_log", $insert);
    if ($_POST['status_karyawan'] == "Percobaan") {
        $to = $_POST['gm_offering'];
        $from = "MSIZone<msizone@mastersystem.co.id>";
        $cc = "recruitment.team@mastersystem.co.id";
        $linkdissaprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval Offering] Offering Recruitment-' . $_POST['id_fpkb'] . '-' . $_POST['id_email'] . '&body=[Disapprove] - GM Offering%0D%0A[notes] - GM Offering';
        $linkapprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval Offering] Offering Recruitment-' . $_POST['id_fpkb'] . '-' . $_POST['id_email'] . '&body=[Approve] - GM Offering%0D%0A[notes] - GM Offering';
        $from = "MSIZone<msizone@mastersystem.co.id>";
        // $cc = "malik.aulia@mastersystem.co.id";
        $bcc = "";
        $reply = $from;
        $subject = "[Permohonan Approval Offering] Offering Recruitment";
        $msg = '<table width="100%">';
        $msg .= '    <tr><td width="20%" rowspan="4"></td>';
        $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
        $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
        $msg .= '    <td width="20%" rowspan="4"></tr>';
        $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
        $msg .= '        <p>Dear ' . $to . '</p>';
        $msg .= '        <p>Berikut ini adalah daftar Offering Request Karyawan yang menunggu approval dari Anda.</p>';
        $msg .= '        <p>';
        // $msg .= '<table width="80%">';
        // $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $_POST['id_fpkb'] . '</td></tr>';
        // $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi'] . '</td></tr>';
        // $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
        // $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $_POST['project_code'] . '</td></tr>';
        // $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $_POST['status_rekrutmen'] . '</td></tr>';
        // $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $_POST['status_karyawan'] . '</td></tr>';
        // $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $_POST['kandidat'] . '</td></tr>';
        // $msg .= '<tr><td>Nama Kandidat</td><td>:</td><td>' . $_POST['nama_kandidat'] . '</td></tr>';
        // $msg .= '<tr><td>Salary Probation</td><td>:</td><td>' . $_POST['salary_Probation'] . '</td></tr>';
        // $msg .= '<tr><td>Salary Permanent</td><td>:</td><td>' . $_POST['salary_Permanent'] . '</td></tr>';
        // $msg .= '</table>';
        // $msg .= '</p>';


        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
        $msg .= '                <tr><td style="width: 20%;">FPKB Number</td><td>:</td><td>' . htmlspecialchars($_POST['id_fpkb']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Division</td><td>:</td><td>' . htmlspecialchars($_POST['divisi']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Position</td><td>:</td><td>' . htmlspecialchars($_POST['posisi']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Nama Kandidat</td><td>:</td><td>' . htmlspecialchars($_POST['nama_kandidat']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Reason</td><td>:</td><td>' . htmlspecialchars($_POST['status_rekrutmen']);
        if ($_POST['status_rekrutmen'] == 'Penggantian') {
            $msg .= ' ' . htmlspecialchars($_POST['reason_penggantian']);
        }
        $msg .= '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status Employee</td><td>:</td><td>' . htmlspecialchars($_POST['status_karyawan']) . '</td></tr>';

        $msg .= '                <tr><td style="width: 20%;">Source</td><td>:</td><td>' . htmlspecialchars($_POST['kandidat']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Salary Offering</td><td>:</td><td>' . htmlspecialchars($_POST['offering_salary']) . '</td></tr>';
        $msg .= '            </table>';
        $msg .= '            <p>Base On Project:</p>';
        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
        $msg .= '                <tr><td style="width: 20%;">Project Code</td><td>:</td><td>' . htmlspecialchars($_POST['project_code']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Project Name</td><td>:</td><td>' . htmlspecialchars($_POST['project_name']) . '</td></tr>';
        $msg .= '            </table>';
        $msg .= '            <p>Approval:</p>';
        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';

        $msg .= '                <tr><td style="width: 20%;">By Manager HCM</td><td>:</td><td>' . htmlspecialchars($_POST['manager_hcm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_manager_hcm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td>' . htmlspecialchars($_POST['status_manager_hcm']) . '</td></tr>';

        $msg .= '                <tr><td style="width: 20%;">By GM HCM</td><td>:</td><td>' . htmlspecialchars($_POST['hcm_gm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_hcm_gm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td> Approve </td></tr>';
        $msg .= '            </table>';
        $msg .= '        <table>';

        $msg .= '</p>';



        $msg .= '<p>Berikut adalah Offering Kandidat. Mohon Reviewnya</p>';
        $msg .= '<table border="1">';
        $msg .= '<tr>';
        $msg .= '<th>Nama File</th>';
        $msg .= '</tr>';

        // Add all filenames and IDs to the email content
        foreach ($_POST['malik'] as $index => $nama) {
            $file_id = $_POST['id_file'][$index];
            $id_filedrive = 'https://drive.google.com/file/d/' . $file_id . '/view';
            $msg .= '<tr>';
            $msg .= '<td><a href="' . $id_filedrive . '">' . $nama . '</a></td>';
            $msg .= '</tr>';
        }

        $msg .= '</table>';
        $msg .= '<p>Silahkan direview untuk kelayakan mendapatkan approval. Jika Approve silahkan klik tombol Approve atau tombol Reject bila ditolak.</p>';
        $msg .= '        <table style="width: 100%; text-align: center;">';  // Aligning the table to center
        $msg .= '        <tr>';
        $msg .= '        <td style="padding: 10px;">';  // Adding padding around the cells
        $msg .= '            <a href="' . $linkapprove . '" style="display: inline-block; padding: 10px 20px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px;">Approve</a>';  // Changed color to blue
        $msg .= '        </td>';
        $msg .= '        <td style="padding: 10px;">';  // Adding padding around the cells
        $msg .= '            <a href="' . $linkdissaprove . '" style="display: inline-block; padding: 10px 20px; background-color: #f44336; color: white; text-decoration: none; border-radius: 5px;">Disapprove</a>';  // Keeping color red
        $msg .= '        </td>';
        $msg .= '        </tr>';
        $msg .= '        </table>';
        $msg .= '        <p>Terimakasih</p>';
        $msg .= '    </td>';
        $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
        $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
        $msg .= "</table>";
        // echo $msg;
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
    if ($_POST['status_karyawan'] != "Percobaan") {
        $to = $_POST['gm_offering'];
        $from = "MSIZone<msizone@mastersystem.co.id>";
        $cc = "recruitment.team@mastersystem.co.id";
        $linkdissaprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval Offering] Offering Recruitment-' . $_POST['id_fpkb'] . '-' . $_POST['id_email'] . '&body=[Disapprove] - GM Offering%0D%0A[notes] - GM Offering';
        $linkapprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval Offering] Offering Recruitment-' . $_POST['id_fpkb'] . '-' . $_POST['id_email'] . '&body=[Approve] - GM Offering%0D%0A[notes] - GM Offering';
        $from = "MSIZone<msizone@mastersystem.co.id>";
        // $cc = "malik.aulia@mastersystem.co.id";
        $bcc = "";
        $reply = $from;
        $subject = "[Permohonan Approval Offering] Offering Recruitment";
        $msg = '<table width="100%">';
        $msg .= '    <tr><td width="20%" rowspan="4"></td>';
        $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
        $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
        $msg .= '    <td width="20%" rowspan="4"></tr>';
        $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
        $msg .= '        <p>Dear ' . $to . '</p>';
        $msg .= '        <p>Berikut ini adalah daftar Offering Request Karyawan yang menunggu approval dari Anda.</p>';
        $msg .= '        <p>';
        // $msg .= '<table width="80%">';
        // $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $_POST['id_fpkb'] . '</td></tr>';
        // $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi'] . '</td></tr>';
        // $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
        // $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $_POST['project_code'] . '</td></tr>';
        // $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $_POST['status_rekrutmen'] . '</td></tr>';
        // $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $_POST['status_karyawan'] . '</td></tr>';
        // $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $_POST['kandidat'] . '</td></tr>';
        // $msg .= '<tr><td>Nama Kandidat</td><td>:</td><td>' . $_POST['nama_kandidat'] . '</td></tr>';
        // $msg .= '<tr><td>Offering Salary</td><td>:</td><td>' . $_POST['offering_salary'] . '</td></tr>';
        // $msg .= '</table>';
        // $msg .= '</p>';



        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
        $msg .= '                <tr><td style="width: 20%;">FPKB Number</td><td>:</td><td>' . htmlspecialchars($_POST['id_fpkb']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Division</td><td>:</td><td>' . htmlspecialchars($_POST['divisi']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Position</td><td>:</td><td>' . htmlspecialchars($_POST['posisi']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Nama Kandidat</td><td>:</td><td>' . htmlspecialchars($_POST['nama_kandidat']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Reason</td><td>:</td><td>' . htmlspecialchars($_POST['status_rekrutmen']);
        if ($_POST['status_rekrutmen'] == 'Penggantian') {
            $msg .= ' ' . htmlspecialchars($_POST['reason_penggantian']);
        }
        $msg .= '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status Employee</td><td>:</td><td>' . htmlspecialchars($_POST['status_karyawan']) . '</td></tr>';

        $msg .= '                <tr><td style="width: 20%;">Source</td><td>:</td><td>' . htmlspecialchars($_POST['kandidat']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Salary Offering</td><td>:</td><td>' . htmlspecialchars($_POST['offering_salary']) . '</td></tr>';
        $msg .= '            </table>';
        $msg .= '            <p>Base On Project:</p>';
        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
        $msg .= '                <tr><td style="width: 20%;">Project Code</td><td>:</td><td>' . htmlspecialchars($_POST['project_code']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Project Name</td><td>:</td><td>' . htmlspecialchars($_POST['project_name']) . '</td></tr>';
        $msg .= '            </table>';
        $msg .= '            <p>Approval:</p>';
        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';

        $msg .= '                <tr><td style="width: 20%;">By Manager HCM</td><td>:</td><td>' . htmlspecialchars($_POST['manager_hcm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_manager_hcm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td>' . htmlspecialchars($_POST['status_manager_hcm']) . '</td></tr>';

        $msg .= '                <tr><td style="width: 20%;">By GM HCM</td><td>:</td><td>' . htmlspecialchars($_POST['hcm_gm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_hcm_gm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td> Approve </td></tr>';
        $msg .= '            </table>';
        $msg .= '        <table>';

        $msg .= '</p>';



        $msg .= '<p>Berikut adalah Offering Kandidat. Mohon Reviewnya</p>';
        $msg .= '<table border="1">';
        $msg .= '<tr>';
        $msg .= '<th>Nama File</th>';
        $msg .= '</tr>';

        // Add all filenames and IDs to the email content
        foreach ($_POST['malik'] as $index => $nama) {
            $file_id = $_POST['id_file'][$index];
            $id_filedrive = 'https://drive.google.com/file/d/' . $file_id . '/view';
            $msg .= '<tr>';
            $msg .= '<td><a href="' . $id_filedrive . '">' . $nama . '</a></td>';
            $msg .= '</tr>';
        }

        $msg .= '</table>';
        $msg .= '<p>Silahkan direview untuk kelayakan mendapatkan approval. Jika Approve silahkan klik tombol Approve atau tombol Reject bila ditolak.</p>';
        $msg .= '        <table style="width: 100%; text-align: center;">';  // Aligning the table to center
        $msg .= '        <tr>';
        $msg .= '        <td style="padding: 10px;">';  // Adding padding around the cells
        $msg .= '            <a href="' . $linkapprove . '" style="display: inline-block; padding: 10px 20px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px;">Approve</a>';  // Changed color to blue
        $msg .= '        </td>';
        $msg .= '        <td style="padding: 10px;">';  // Adding padding around the cells
        $msg .= '            <a href="' . $linkdissaprove . '" style="display: inline-block; padding: 10px 20px; background-color: #f44336; color: white; text-decoration: none; border-radius: 5px;">Disapprove</a>';  // Keeping color red
        $msg .= '        </td>';
        $msg .= '        </tr>';
        $msg .= '        </table>';
        $msg .= '        <p>Terimakasih</p>';
        $msg .= '    </td>';
        $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
        $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
        $msg .= "</table>";
        // echo $msg;
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
} elseif (isset($_POST['dissaprove_hcm_gm_offering'])) {

    $condition = "email_id=" . $_POST['id_email'];
    $update = sprintf(
        "`status_hcm_gm`=%s,`catatan_hcm_gm`=%s",
        GetSQLValueString("Disapprove", "text"),
        GetSQLValueString($_POST['catatan_hcm_gm'], "text")

    );
    $updateResult = $DBHCM->update_data('hcm_requirement_interview', $update, $condition);
    $mdlname = "NOTIFICATION";
    $DBNOTIF = get_conn($mdlname);
    $to = "Wahyuwinningdyah Margareta,S.S.PSI<margareta.sekar@mastersystem.co.id>";
    $from = $_POST['hcm_gm'];
    $tblnamenotif = "trx_notification";
    $notifmsg = $_POST['id_fpkb'] . "; " . $_POST['project_code'] . "; ";
    $idfpkb = str_replace(' ', '_', $_POST['id_fpkb']); // Mengganti spasi dengan underscore
    // $notif_link = "index.php?mod=hcm_requirement&act=editapproval&id=" . $_POST['id'] . "&project_code=" . $_POST['project_code'] . "&submit=Submit";
    $notif_link = "index.php?mod=hcm_requirement&act=editoffering&id=" . $_POST['id_email'] . "&id_fpkb=" . $idfpkb . "&submit=Submit";
    $insert = sprintf(
        "(`notif_from`, `notif_to`, `notif_subject`, `notif_post`, `notif_link`) VALUES (%s, %s, %s, %s, %s)",
        GetSQLValueString($from, "text"),
        GetSQLValueString($to, "text"),
        GetSQLValueString(ucwords("Disapprove Offering Nama Kandidat ") . $_POST['nama_kandidat'], "text"),
        GetSQLValueString($notifmsg, "text"),
        GetSQLValueString($notif_link, "text")
    );
    $res = $DBNOTIF->insert_data($tblnamenotif, $insert);
    // var_dump($notif_link);
    // die;
    $description = $_POST['hcm_gm'] . " Telah memberikan Disapprove pada data dengan ID FPKB " . $_POST['id_fpkb'] . " dan email kandidat: " . $_POST['email'];
    $insert = sprintf(
        "(`id_fpkb`,`project_code`,`project_name`,`description`,`entry_by`) VALUES (%s,%s,%s,%s,%s)",
        GetSQLValueString($_POST['id_fpkb'], "text"),
        GetSQLValueString($_POST['project_code'], "text"),
        GetSQLValueString($_POST['project_name'], "text"),
        GetSQLValueString($description, "text"),
        GetSQLValueString($_POST['hcm_gm'], "text")
    );
    $res = $DBHCM->insert_data("hcm_requirement_log", $insert);
    if ($_POST['status_karyawan'] == "Percobaan") {
        $to = $_POST['gm_offering'];
        $from = "MSIZone<msizone@mastersystem.co.id>";
        // $cc = "recruitment.team@mastersystem.co.id";
        $linkdissaprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval Offering] Offering Recruitment-' . $_POST['id_fpkb'] . '-' . $_POST['id_email'] . '&body=[Disapprove] - GM Offering%0D%0A[notes] - GM Offering';
        $linkapprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval Offering] Offering Recruitment-' . $_POST['id_fpkb'] . '-' . $_POST['id_email'] . '&body=[Approve] - GM Offering%0D%0A[notes] - GM Offering';
        $from = "MSIZone<msizone@mastersystem.co.id>";
        $cc = "malik.aulia@mastersystem.co.id";
        $bcc = "";
        $reply = $from;
        $subject = "[Permohonan Approval Offering] Offering Recruitment";
        $msg = '<table width="100%">';
        $msg .= '    <tr><td width="20%" rowspan="4"></td>';
        $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
        $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
        $msg .= '    <td width="20%" rowspan="4"></tr>';
        $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
        $msg .= '        <p>Dear ' . $to . '</p>';
        $msg .= '        <p>Berikut ini adalah daftar Offering Request Karyawan yang menunggu approval dari Anda.</p>';
        $msg .= '        <p>';
        // $msg .= '<table width="80%">';
        // $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $_POST['id_fpkb'] . '</td></tr>';
        // $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi'] . '</td></tr>';
        // $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
        // $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $_POST['project_code'] . '</td></tr>';
        // $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $_POST['status_rekrutmen'] . '</td></tr>';
        // $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $_POST['status_karyawan'] . '</td></tr>';
        // $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $_POST['kandidat'] . '</td></tr>';
        // $msg .= '<tr><td>Nama Kandidat</td><td>:</td><td>' . $_POST['nama_kandidat'] . '</td></tr>';
        // $msg .= '<tr><td>Salary Probation</td><td>:</td><td>' . $_POST['salary_Probation'] . '</td></tr>';
        // $msg .= '<tr><td>Salary Permanent</td><td>:</td><td>' . $_POST['salary_Permanent'] . '</td></tr>';
        // $msg .= '</table>';
        // $msg .= '</p>';


        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
        $msg .= '                <tr><td style="width: 20%;">FPKB Number</td><td>:</td><td>' . htmlspecialchars($_POST['id_fpkb']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Division</td><td>:</td><td>' . htmlspecialchars($_POST['divisi']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Position</td><td>:</td><td>' . htmlspecialchars($_POST['posisi']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Nama Kandidat</td><td>:</td><td>' . htmlspecialchars($_POST['nama_kandidat']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Reason</td><td>:</td><td>' . htmlspecialchars($_POST['status_rekrutmen']);
        if ($_POST['status_rekrutmen'] == 'Penggantian') {
            $msg .= ' ' . htmlspecialchars($_POST['reason_penggantian']);
        }
        $msg .= '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status Employee</td><td>:</td><td>' . htmlspecialchars($_POST['status_karyawan']) . '</td></tr>';

        $msg .= '                <tr><td style="width: 20%;">Source</td><td>:</td><td>' . htmlspecialchars($_POST['kandidat']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Salary Offering</td><td>:</td><td>' . htmlspecialchars($_POST['offering_salary']) . '</td></tr>';
        $msg .= '            </table>';
        $msg .= '            <p>Base On Project:</p>';
        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
        $msg .= '                <tr><td style="width: 20%;">Project Code</td><td>:</td><td>' . htmlspecialchars($_POST['project_code']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Project Name</td><td>:</td><td>' . htmlspecialchars($_POST['project_name']) . '</td></tr>';
        $msg .= '            </table>';
        $msg .= '            <p>Approval:</p>';
        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';

        $msg .= '                <tr><td style="width: 20%;">By Manager HCM</td><td>:</td><td>' . htmlspecialchars($_POST['manager_hcm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_manager_hcm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td>' . htmlspecialchars($_POST['status_manager_hcm']) . '</td></tr>';

        $msg .= '                <tr><td style="width: 20%;">By GM HCM</td><td>:</td><td>' . htmlspecialchars($_POST['hcm_gm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_hcm_gm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td> Dissaprove </td></tr>';
        $msg .= '            </table>';
        $msg .= '        <table>';

        $msg .= '</p>';


        $msg .= '<p>Berikut adalah Offering Kandidat. Mohon Reviewnya</p>';
        $msg .= '<table border="1">';
        $msg .= '<tr>';
        $msg .= '<th>Nama File</th>';
        $msg .= '</tr>';

        // Add all filenames and IDs to the email content
        foreach ($_POST['malik'] as $index => $nama) {
            $file_id = $_POST['id_file'][$index];
            $id_filedrive = 'https://drive.google.com/file/d/' . $file_id . '/view';
            $msg .= '<tr>';
            $msg .= '<td><a href="' . $id_filedrive . '">' . $nama . '</a></td>';
            $msg .= '</tr>';
        }

        $msg .= '</table>';
        $msg .= '<p>Silahkan direview untuk kelayakan mendapatkan approval. Jika Approve silahkan klik tombol Approve atau tombol Reject bila ditolak.</p>';
        $msg .= '        <table style="width: 100%; text-align: center;">';  // Aligning the table to center
        $msg .= '        <tr>';
        $msg .= '        <td style="padding: 10px;">';  // Adding padding around the cells
        $msg .= '            <a href="' . $linkapprove . '" style="display: inline-block; padding: 10px 20px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px;">Approve</a>';  // Changed color to blue
        $msg .= '        </td>';
        $msg .= '        <td style="padding: 10px;">';  // Adding padding around the cells
        $msg .= '            <a href="' . $linkdissaprove . '" style="display: inline-block; padding: 10px 20px; background-color: #f44336; color: white; text-decoration: none; border-radius: 5px;">Disapprove</a>';  // Keeping color red
        $msg .= '        </td>';
        $msg .= '        </tr>';
        $msg .= '        </table>';
        $msg .= '        <p>Terimakasih</p>';
        $msg .= '    </td>';
        $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
        $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
        $msg .= "</table>";
        // echo $msg;
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
    if ($_POST['status_karyawan'] != "Percobaan") {
        $to = $_POST['gm_offering'];
        $from = "MSIZone<msizone@mastersystem.co.id>";
        // $cc = "recruitment.team@mastersystem.co.id";
        $linkdissaprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval Offering] Offering Recruitment-' . $_POST['id_fpkb'] . '-' . $_POST['id_email'] . '&body=[Disapprove] - GM Offering%0D%0A[notes] - GM Offering';
        $linkapprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval Offering] Offering Recruitment-' . $_POST['id_fpkb'] . '-' . $_POST['id_email'] . '&body=[Approve] - GM Offering%0D%0A[notes] - GM Offering';
        $from = "MSIZone<msizone@mastersystem.co.id>";
        $cc = "malik.aulia@mastersystem.co.id";
        $bcc = "";
        $reply = $from;
        $subject = "[Permohonan Approval Offering] Offering Recruitment";
        $msg = '<table width="100%">';
        $msg .= '    <tr><td width="20%" rowspan="4"></td>';
        $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
        $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
        $msg .= '    <td width="20%" rowspan="4"></tr>';
        $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
        $msg .= '        <p>Dear ' . $to . '</p>';
        $msg .= '        <p>Berikut ini adalah daftar Offering Request Karyawan yang menunggu approval dari Anda.</p>';
        $msg .= '        <p>';
        // $msg .= '<table width="80%">';
        // $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $_POST['id_fpkb'] . '</td></tr>';
        // $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi'] . '</td></tr>';
        // $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
        // $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $_POST['project_code'] . '</td></tr>';
        // $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $_POST['status_rekrutmen'] . '</td></tr>';
        // $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $_POST['status_karyawan'] . '</td></tr>';
        // $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $_POST['kandidat'] . '</td></tr>';
        // $msg .= '<tr><td>Nama Kandidat</td><td>:</td><td>' . $_POST['nama_kandidat'] . '</td></tr>';
        // $msg .= '<tr><td>Offering Salary</td><td>:</td><td>' . $_POST['offering_salary'] . '</td></tr>';
        // $msg .= '</table>';
        // $msg .= '</p>';


        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
        $msg .= '                <tr><td style="width: 20%;">FPKB Number</td><td>:</td><td>' . htmlspecialchars($_POST['id_fpkb']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Division</td><td>:</td><td>' . htmlspecialchars($_POST['divisi']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Position</td><td>:</td><td>' . htmlspecialchars($_POST['posisi']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Nama Kandidat</td><td>:</td><td>' . htmlspecialchars($_POST['nama_kandidat']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Reason</td><td>:</td><td>' . htmlspecialchars($_POST['status_rekrutmen']);
        if ($_POST['status_rekrutmen'] == 'Penggantian') {
            $msg .= ' ' . htmlspecialchars($_POST['reason_penggantian']);
        }
        $msg .= '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status Employee</td><td>:</td><td>' . htmlspecialchars($_POST['status_karyawan']) . '</td></tr>';

        $msg .= '                <tr><td style="width: 20%;">Source</td><td>:</td><td>' . htmlspecialchars($_POST['kandidat']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Salary Offering</td><td>:</td><td>' . htmlspecialchars($_POST['offering_salary']) . '</td></tr>';
        $msg .= '            </table>';
        $msg .= '            <p>Base On Project:</p>';
        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
        $msg .= '                <tr><td style="width: 20%;">Project Code</td><td>:</td><td>' . htmlspecialchars($_POST['project_code']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Project Name</td><td>:</td><td>' . htmlspecialchars($_POST['project_name']) . '</td></tr>';
        $msg .= '            </table>';
        $msg .= '            <p>Approval:</p>';
        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';

        $msg .= '                <tr><td style="width: 20%;">By Manager HCM</td><td>:</td><td>' . htmlspecialchars($_POST['manager_hcm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_manager_hcm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td>' . htmlspecialchars($_POST['status_manager_hcm']) . '</td></tr>';

        $msg .= '                <tr><td style="width: 20%;">By GM HCM</td><td>:</td><td>' . htmlspecialchars($_POST['hcm_gm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_hcm_gm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td> Dissaprove </td></tr>';
        $msg .= '            </table>';
        $msg .= '        <table>';

        $msg .= '</p>';


        $msg .= '<p>Berikut adalah Offering Kandidat. Mohon Reviewnya</p>';
        $msg .= '<table border="1">';
        $msg .= '<tr>';
        $msg .= '<th>Nama File</th>';
        $msg .= '</tr>';

        // Add all filenames and IDs to the email content
        foreach ($_POST['malik'] as $index => $nama) {
            $file_id = $_POST['id_file'][$index];
            $id_filedrive = 'https://drive.google.com/file/d/' . $file_id . '/view';
            $msg .= '<tr>';
            $msg .= '<td><a href="' . $id_filedrive . '">' . $nama . '</a></td>';
            $msg .= '</tr>';
        }

        $msg .= '</table>';
        $msg .= '<p>Silahkan direview untuk kelayakan mendapatkan approval. Jika Approve silahkan klik tombol Approve atau tombol Reject bila ditolak.</p>';
        $msg .= '        <table style="width: 100%; text-align: center;">';  // Aligning the table to center
        $msg .= '        <tr>';
        $msg .= '        <td style="padding: 10px;">';  // Adding padding around the cells
        $msg .= '            <a href="' . $linkapprove . '" style="display: inline-block; padding: 10px 20px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px;">Approve</a>';  // Changed color to blue
        $msg .= '        </td>';
        $msg .= '        <td style="padding: 10px;">';  // Adding padding around the cells
        $msg .= '            <a href="' . $linkdissaprove . '" style="display: inline-block; padding: 10px 20px; background-color: #f44336; color: white; text-decoration: none; border-radius: 5px;">Disapprove</a>';  // Keeping color red
        $msg .= '        </td>';
        $msg .= '        </tr>';
        $msg .= '        </table>';
        $msg .= '        <p>Terimakasih</p>';
        $msg .= '    </td>';
        $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
        $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
        $msg .= "</table>";
        // echo $msg;
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

    $to = $_POST['assign_requirement'];
    $cc = "recruitment.team@mastersystem.co.id";
    // $cc = "malik.aulia@mastersystem.co.id";
    $from = "MSIZone<msizone@mastersystem.co.id>";
    $bcc = "";
    $reply = $from;
    $subject = "[Permohonan Approval Offering] Offering Recruitment";
    $msg = '<table width="100%">';
    $msg .= '    <tr><td width="20%" rowspan="4"></td>';
    $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
    $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
    $msg .= '    <td width="20%" rowspan="4"></tr>';
    $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
    $msg .= '        <p>Dear ' . $to . '</p>';
    $msg .= '        <p>Berikut ini adalah daftar Offering Request Karyawan yang mendapatkan Dissapprove.</p>';
    $msg .= '        <p>';
    // $msg .= '<table width="80%">';
    // $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $_POST['id_fpkb'] . '</td></tr>';
    // $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi'] . '</td></tr>';
    // $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
    // $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $_POST['project_code'] . '</td></tr>';
    // $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $_POST['status_rekrutmen'] . '</td></tr>';
    // $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $_POST['status_karyawan'] . '</td></tr>';
    // $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $_POST['kandidat'] . '</td></tr>';
    // $msg .= '<tr><td>Nama Kandidat</td><td>:</td><td>' . $_POST['nama_kandidat'] . '</td></tr>';
    // $msg .= '</table>';
    // $msg .= '</p>';


    $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
    $msg .= '                <tr><td style="width: 20%;">FPKB Number</td><td>:</td><td>' . htmlspecialchars($_POST['id_fpkb']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Division</td><td>:</td><td>' . htmlspecialchars($_POST['divisi']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Position</td><td>:</td><td>' . htmlspecialchars($_POST['posisi']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Nama Kandidat</td><td>:</td><td>' . htmlspecialchars($_POST['nama_kandidat']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Reason</td><td>:</td><td>' . htmlspecialchars($_POST['status_rekrutmen']);
    if ($_POST['status_rekrutmen'] == 'Penggantian') {
        $msg .= ' ' . htmlspecialchars($_POST['reason_penggantian']);
    }
    $msg .= '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Status Employee</td><td>:</td><td>' . htmlspecialchars($_POST['status_karyawan']) . '</td></tr>';

    $msg .= '                <tr><td style="width: 20%;">Source</td><td>:</td><td>' . htmlspecialchars($_POST['kandidat']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Salary Offering</td><td>:</td><td>' . htmlspecialchars($_POST['offering_salary']) . '</td></tr>';
    $msg .= '            </table>';
    $msg .= '            <p>Base On Project:</p>';
    $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
    $msg .= '                <tr><td style="width: 20%;">Project Code</td><td>:</td><td>' . htmlspecialchars($_POST['project_code']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Project Name</td><td>:</td><td>' . htmlspecialchars($_POST['project_name']) . '</td></tr>';
    $msg .= '            </table>';
    $msg .= '            <p>Approval:</p>';
    $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';

    $msg .= '                <tr><td style="width: 20%;">By Manager HCM</td><td>:</td><td>' . htmlspecialchars($_POST['manager_hcm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_manager_hcm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td>' . htmlspecialchars($_POST['status_manager_hcm']) . '</td></tr>';

    $msg .= '                <tr><td style="width: 20%;">By GM HCM</td><td>:</td><td>' . htmlspecialchars($_POST['hcm_gm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_hcm_gm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td> Dissaprove </td></tr>';
    $msg .= '            </table>';
    $msg .= '        <table>';

    $msg .= '</p>';


    $msg .= '<p>Berikut adalah Offering Kandidat. Mohon Reviewnya</p>';
    $msg .= '<table border="1">';
    $msg .= '<tr>';
    $msg .= '<th>Nama File</th>';
    $msg .= '</tr>';

    // Add all filenames and IDs to the email content
    foreach ($_POST['malik'] as $index => $nama) {
        $file_id = $_POST['id_file'][$index];
        $id_filedrive = 'https://drive.google.com/file/d/' . $file_id . '/view';
        $msg .= '<tr>';
        $msg .= '<td><a href="' . $id_filedrive . '">' . $nama . '</a></td>';
        $msg .= '</tr>';
    }

    $msg .= '</table>';
    $msg .= '<p>Silahkan direview untuk ditidak lanjuti.</p>';
    $msg .= '        <table>';
    $msg .= '        <tr>';
    $msg .= '        </table>';
    $msg .= '        <p>Terimakasih</p>';
    $msg .= '    </td>';
    $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
    $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
    $msg .= "</table>";
    // echo $msg;
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
} elseif (isset($_POST['approve_gm_offering_offering'])) {
    $allquery = $DBHCM->get_sqlV2("SELECT * FROM sa_hcm_requirement JOIN sa_hcm_requirement_interview ON sa_hcm_requirement.id_fpkb = sa_hcm_requirement_interview.id_fpkb WHERE sa_hcm_requirement . id_fpkb = '" . $_POST['id_fpkb'] . "' AND sa_hcm_requirement_interview . email_id ='" . $_POST['id_email'] . "'");

    $condition = "email_id=" . $_POST['id_email'];
    $update = sprintf(
        "`status_gm_offering`=%s,`catatan_gm_offering`=%s",
        GetSQLValueString("Approve", "text"),
        GetSQLValueString($_POST['catatan_gm_offering'], "text")

    );
    $updateResult = $DBHCM->update_data('hcm_requirement_interview', $update, $condition);
    $mdlname = "NOTIFICATION";
    $DBNOTIF = get_conn($mdlname);
    $to = "Wahyuwinningdyah Margareta,S.S.PSI<margareta.sekar@mastersystem.co.id>";
    $from = $_POST['gm_offering'];
    $tblnamenotif = "trx_notification";
    $notifmsg = $_POST['id_fpkb'] . "; " . $_POST['project_code'] . "; ";
    $idfpkb = str_replace(' ', '_', $_POST['id_fpkb']); // Mengganti spasi dengan underscore
    // $notif_link = "index.php?mod=hcm_requirement&act=editapproval&id=" . $_POST['id'] . "&project_code=" . $_POST['project_code'] . "&submit=Submit";
    $notif_link = "index.php?mod=hcm_requirement&act=editoffering&id=" . $_POST['id_email'] . "&id_fpkb=" . $idfpkb . "&submit=Submit";
    $insert = sprintf(
        "(`notif_from`, `notif_to`, `notif_subject`, `notif_post`, `notif_link`) VALUES (%s, %s, %s, %s, %s)",
        GetSQLValueString($from, "text"),
        GetSQLValueString($to, "text"),
        GetSQLValueString(ucwords("Approve Offering Nama Kandidat ") . $_POST['nama_kandidat'], "text"),
        GetSQLValueString($notifmsg, "text"),
        GetSQLValueString($notif_link, "text")
    );
    $res = $DBNOTIF->insert_data($tblnamenotif, $insert);
    // var_dump($notif_link);
    // die;
    $description = $_POST['gm_offering'] . " Telah memberikan Approve pada data dengan ID FPKB " . $_POST['id_fpkb'] . " dan email kandidat: " . $_POST['email'];
    $insert = sprintf(
        "(`id_fpkb`,`project_code`,`project_name`,`description`,`entry_by`) VALUES (%s,%s,%s,%s,%s)",
        GetSQLValueString($_POST['id_fpkb'], "text"),
        GetSQLValueString($_POST['project_code'], "text"),
        GetSQLValueString($_POST['project_name'], "text"),
        GetSQLValueString($description, "text"),
        GetSQLValueString($_POST['gm_offering'], "text")
    );
    $res = $DBHCM->insert_data("hcm_requirement_log", $insert);
    if ($allquery[0]['bod'] == null) {

        $updateResult = $DBHCM->get_res("UPDATE sa_hcm_requirement_interview SET status = 'Complete' WHERE email_id = '" . $_POST['id_email'] . "'");
        // $to = "malik.aulia@mastersystem.co.id";
        if ($_POST['status_karyawan'] == "Percobaan") {
            $to = $_POST['assign_requirement'];
            $from = "MSIZone<msizone@mastersystem.co.id>";
            // $cc = "malik.aulia@mastersystem.co.id";
            $cc = "recruitment.team@mastersystem.co.id";
            $bcc = "";
            $reply = $from;
            $subject = "[Permohonan Approval Offering] Offering Recruitment";
            $msg = '<table width="100%">';
            $msg .= '    <tr><td width="20%" rowspan="4"></td>';
            $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
            $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
            $msg .= '    <td width="20%" rowspan="4"></tr>';
            $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
            $msg .= '        <p>Dear ' . $to . '</p>';
            $msg .= '        <p>Berikut ini adalah daftar Offering Request Karyawan yang telah mendapatkan approval.</p>';
            $msg .= '        <p>';
            // $msg .= '<table width="80%">';
            // $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $_POST['id_fpkb'] . '</td></tr>';
            // $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi'] . '</td></tr>';
            // $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
            // $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $_POST['project_code'] . '</td></tr>';
            // $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $_POST['status_rekrutmen'] . '</td></tr>';
            // $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $_POST['status_karyawan'] . '</td></tr>';
            // $msg .= '<tr><td>Email Kandidat</td><td>:</td><td>' . $_POST['email'] . '</td></tr>';
            // $msg .= '<tr><td>Nama Kandidat</td><td>:</td><td>' . $_POST['nama_kandidat'] . '</td></tr>';
            // $msg .= '<tr><td>Salary Probation</td><td>:</td><td>' . $_POST['salary_Probation'] . '</td></tr>';
            // $msg .= '<tr><td>Salary Permanent</td><td>:</td><td>' . $_POST['salary_Permanent'] . '</td></tr>';
            // $msg .= '</table>';
            // $msg .= '</p>';


            $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
            $msg .= '                <tr><td style="width: 20%;">FPKB Number</td><td>:</td><td>' . htmlspecialchars($_POST['id_fpkb']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Division</td><td>:</td><td>' . htmlspecialchars($_POST['divisi']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Position</td><td>:</td><td>' . htmlspecialchars($_POST['posisi']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Nama Kandidat</td><td>:</td><td>' . htmlspecialchars($_POST['nama_kandidat']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Reason</td><td>:</td><td>' . htmlspecialchars($_POST['status_rekrutmen']);
            if ($_POST['status_rekrutmen'] == 'Penggantian') {
                $msg .= ' ' . htmlspecialchars($_POST['reason_penggantian']);
            }
            $msg .= '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Status Employee</td><td>:</td><td>' . htmlspecialchars($_POST['status_karyawan']) . '</td></tr>';

            $msg .= '                <tr><td style="width: 20%;">Source</td><td>:</td><td>' . htmlspecialchars($_POST['kandidat']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Salary Offering</td><td>:</td><td>' . htmlspecialchars($_POST['offering_salary']) . '</td></tr>';
            $msg .= '            </table>';
            $msg .= '            <p>Base On Project:</p>';
            $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
            $msg .= '                <tr><td style="width: 20%;">Project Code</td><td>:</td><td>' . htmlspecialchars($_POST['project_code']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Project Name</td><td>:</td><td>' . htmlspecialchars($_POST['project_name']) . '</td></tr>';
            $msg .= '            </table>';
            $msg .= '            <p>Approval:</p>';
            $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';

            $msg .= '                <tr><td style="width: 20%;">By Manager HCM</td><td>:</td><td>' . htmlspecialchars($_POST['manager_hcm']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_manager_hcm']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td>' . htmlspecialchars($_POST['status_manager_hcm']) . '</td></tr>';

            $msg .= '                <tr><td style="width: 20%;">By GM HCM</td><td>:</td><td>' . htmlspecialchars($_POST['hcm_gm']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_hcm_gm']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td>' . htmlspecialchars($_POST['status_hcm_gm']) . '</td></tr>';

            $msg .= '                <tr><td style="width: 20%;">By GM HCM</td><td>:</td><td>' . htmlspecialchars($_POST['gm_offering']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_gm_offering']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td> Approve </td></tr>';
            $msg .= '            </table>';
            $msg .= '        <table>';

            $msg .= '</p>';


            $msg .= '<p>Berikut adalah Offering Kandidat. Mohon Reviewnya</p>';
            $msg .= '<table border="1">';
            $msg .= '<tr>';
            $msg .= '<th>Nama File</th>';
            $msg .= '</tr>';

            // Add all filenames and IDs to the email content
            foreach ($_POST['malik'] as $index => $nama) {
                $file_id = $_POST['id_file'][$index];
                $id_filedrive = 'https://drive.google.com/file/d/' . $file_id . '/view';
                $msg .= '<tr>';
                $msg .= '<td><a href="' . $id_filedrive . '">' . $nama . '</a></td>';
                $msg .= '</tr>';
            }

            $msg .= '</table>';
            $msg .= '<p>Silahkan diproses.</p>';
            $msg .= '        <table>';
            $msg .= '        <tr>';
            $msg .= '        </table>';
            $msg .= '        <p>Terimakasih</p>';
            $msg .= '    </td>';
            $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
            $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
            $msg .= "</table>";
            // echo $msg;
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
        if ($_POST['status_karyawan'] != "Percobaan") {
            $to = $_POST['assign_requirement'];
            $from = "MSIZone<msizone@mastersystem.co.id>";
            // $cc = "malik.aulia@mastersystem.co.id";
            $cc = "recruitment.team@mastersystem.co.id";
            $bcc = "";
            $reply = $from;
            $subject = "[Permohonan Approval Offering] Offering Recruitment";
            $msg = '<table width="100%">';
            $msg .= '    <tr><td width="20%" rowspan="4"></td>';
            $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
            $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
            $msg .= '    <td width="20%" rowspan="4"></tr>';
            $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
            $msg .= '        <p>Dear ' . $to . '</p>';
            $msg .= '        <p>Berikut ini adalah daftar Offering Request Karyawan yang telah mendapatkan approval.</p>';
            $msg .= '        <p>';
            // $msg .= '<table width="80%">';
            // $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $_POST['id_fpkb'] . '</td></tr>';
            // $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi'] . '</td></tr>';
            // $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
            // $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $_POST['project_code'] . '</td></tr>';
            // $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $_POST['status_rekrutmen'] . '</td></tr>';
            // $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $_POST['status_karyawan'] . '</td></tr>';
            // $msg .= '<tr><td>Email Kandidat</td><td>:</td><td>' . $_POST['email'] . '</td></tr>';
            // $msg .= '<tr><td>Nama Kandidat</td><td>:</td><td>' . $_POST['nama_kandidat'] . '</td></tr>';
            // $msg .= '<tr><td>Offering Salary</td><td>:</td><td>' . $_POST['offering_salary'] . '</td></tr>';
            // $msg .= '</table>';
            // $msg .= '</p>';


            $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
            $msg .= '                <tr><td style="width: 20%;">FPKB Number</td><td>:</td><td>' . htmlspecialchars($_POST['id_fpkb']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Division</td><td>:</td><td>' . htmlspecialchars($_POST['divisi']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Position</td><td>:</td><td>' . htmlspecialchars($_POST['posisi']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Nama Kandidat</td><td>:</td><td>' . htmlspecialchars($_POST['nama_kandidat']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Reason</td><td>:</td><td>' . htmlspecialchars($_POST['status_rekrutmen']);
            if ($_POST['status_rekrutmen'] == 'Penggantian') {
                $msg .= ' ' . htmlspecialchars($_POST['reason_penggantian']);
            }
            $msg .= '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Status Employee</td><td>:</td><td>' . htmlspecialchars($_POST['status_karyawan']) . '</td></tr>';

            $msg .= '                <tr><td style="width: 20%;">Source</td><td>:</td><td>' . htmlspecialchars($_POST['kandidat']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Salary Offering</td><td>:</td><td>' . htmlspecialchars($_POST['offering_salary']) . '</td></tr>';
            $msg .= '            </table>';
            $msg .= '            <p>Base On Project:</p>';
            $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
            $msg .= '                <tr><td style="width: 20%;">Project Code</td><td>:</td><td>' . htmlspecialchars($_POST['project_code']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Project Name</td><td>:</td><td>' . htmlspecialchars($_POST['project_name']) . '</td></tr>';
            $msg .= '            </table>';
            $msg .= '            <p>Approval:</p>';
            $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';

            $msg .= '                <tr><td style="width: 20%;">By Manager HCM</td><td>:</td><td>' . htmlspecialchars($_POST['manager_hcm']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_manager_hcm']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td>' . htmlspecialchars($_POST['status_manager_hcm']) . '</td></tr>';

            $msg .= '                <tr><td style="width: 20%;">By GM HCM</td><td>:</td><td>' . htmlspecialchars($_POST['hcm_gm']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_hcm_gm']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td>' . htmlspecialchars($_POST['status_hcm_gm']) . '</td></tr>';

            $msg .= '                <tr><td style="width: 20%;">By GM</td><td>:</td><td>' . htmlspecialchars($_POST['gm_offering']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_gm_offering']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td> Approve </td></tr>';
            $msg .= '            </table>';
            $msg .= '        <table>';

            $msg .= '</p>';


            $msg .= '<p>Berikut adalah Offering Kandidat. Mohon Reviewnya</p>';
            $msg .= '<table border="1">';
            $msg .= '<tr>';
            $msg .= '<th>Nama File</th>';
            $msg .= '</tr>';

            // Add all filenames and IDs to the email content
            foreach ($_POST['malik'] as $index => $nama) {
                $file_id = $_POST['id_file'][$index];
                $id_filedrive = 'https://drive.google.com/file/d/' . $file_id . '/view';
                $msg .= '<tr>';
                $msg .= '<td><a href="' . $id_filedrive . '">' . $nama . '</a></td>';
                $msg .= '</tr>';
            }

            $msg .= '</table>';
            $msg .= '<p>Silahkan diproses.</p>';
            $msg .= '        <table>';
            $msg .= '        <tr>';
            $msg .= '        </table>';
            $msg .= '        <p>Terimakasih</p>';
            $msg .= '    </td>';
            $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
            $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
            $msg .= "</table>";
            // echo $msg;
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
    } elseif ($allquery[0]['bod'] != null) {
        if ($_POST['status_karyawan'] == "Percobaan") {
            // $to = 'malik.aulia@mastersystem.co.id';
            $to = $_POST['gm_offering'];
            $from = "MSIZone<msizone@mastersystem.co.id>";
            $cc = "recruitment.team@mastersystem.co.id";
            $linkdissaprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval Offering] Offering Recruitment-' . $_POST['id_fpkb'] . '-' . $_POST['id_email'] . '&body=[Disapprove] - Offering bod%0D%0A[notes] - Offering bod';
            $linkapprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval Offering] Offering Recruitment-' . $_POST['id_fpkb'] . '-' . $_POST['id_email'] . '&body=[Approve] - Offering bod%0D%0A[notes] - Offering bod';
            $from = "MSIZone<msizone@mastersystem.co.id>";
            // $cc = "malik.aulia@mastersystem.co.id";
            $bcc = "";
            $reply = $from;
            $subject = "[Permohonan Approval Offering] Offering Recruitment";
            $msg = '<table width="100%">';
            $msg .= '    <tr><td width="20%" rowspan="4"></td>';
            $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
            $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
            $msg .= '    <td width="20%" rowspan="4"></tr>';
            $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
            $msg .= '        <p>Dear ' . $to . '</p>';
            $msg .= '        <p>Berikut ini adalah daftar Offering Request Karyawan yang menunggu approval dari Anda.</p>';
            $msg .= '        <p>';
            // $msg .= '<table width="80%">';
            // $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $_POST['id_fpkb'] . '</td></tr>';
            // $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi'] . '</td></tr>';
            // $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
            // $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $_POST['project_code'] . '</td></tr>';
            // $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $_POST['status_rekrutmen'] . '</td></tr>';
            // $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $_POST['status_karyawan'] . '</td></tr>';
            // $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $_POST['kandidat'] . '</td></tr>';
            // $msg .= '<tr><td>Nama Kandidat</td><td>:</td><td>' . $_POST['nama_kandidat'] . '</td></tr>';
            // $msg .= '<tr><td>Salary Probation</td><td>:</td><td>' . $_POST['salary_Probation'] . '</td></tr>';
            // $msg .= '<tr><td>Salary Permanent</td><td>:</td><td>' . $_POST['salary_Permanent'] . '</td></tr>';
            // $msg .= '</table>';


            $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
            $msg .= '                <tr><td style="width: 20%;">FPKB Number</td><td>:</td><td>' . htmlspecialchars($_POST['id_fpkb']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Division</td><td>:</td><td>' . htmlspecialchars($_POST['divisi']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Position</td><td>:</td><td>' . htmlspecialchars($_POST['posisi']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Nama Kandidat</td><td>:</td><td>' . htmlspecialchars($_POST['nama_kandidat']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Reason</td><td>:</td><td>' . htmlspecialchars($_POST['status_rekrutmen']);
            if ($_POST['status_rekrutmen'] == 'Penggantian') {
                $msg .= ' ' . htmlspecialchars($_POST['reason_penggantian']);
            }
            $msg .= '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Status Employee</td><td>:</td><td>' . htmlspecialchars($_POST['status_karyawan']) . '</td></tr>';

            $msg .= '                <tr><td style="width: 20%;">Source</td><td>:</td><td>' . htmlspecialchars($_POST['kandidat']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Salary Offering</td><td>:</td><td>' . htmlspecialchars($_POST['offering_salary']) . '</td></tr>';
            $msg .= '            </table>';
            $msg .= '            <p>Base On Project:</p>';
            $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
            $msg .= '                <tr><td style="width: 20%;">Project Code</td><td>:</td><td>' . htmlspecialchars($_POST['project_code']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Project Name</td><td>:</td><td>' . htmlspecialchars($_POST['project_name']) . '</td></tr>';
            $msg .= '            </table>';
            $msg .= '            <p>Approval:</p>';
            $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';

            $msg .= '                <tr><td style="width: 20%;">By Manager HCM</td><td>:</td><td>' . htmlspecialchars($_POST['manager_hcm']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_manager_hcm']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td>' . htmlspecialchars($_POST['status_manager_hcm']) . '</td></tr>';

            $msg .= '                <tr><td style="width: 20%;">By GM HCM</td><td>:</td><td>' . htmlspecialchars($_POST['hcm_gm']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_hcm_gm']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td>' . htmlspecialchars($_POST['status_hcm_gm']) . '</td></tr>';

            $msg .= '                <tr><td style="width: 20%;">By GM</td><td>:</td><td>' . htmlspecialchars($_POST['gm_offering']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_gm_offering']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td> Approve </td></tr>';
            $msg .= '            </table>';
            $msg .= '        <table>';

            $msg .= '</p>';
            $msg .= '<p>Berikut adalah Offering Kandidat. Mohon Reviewnya</p>';
            $msg .= '<table border="1">';
            $msg .= '<tr>';
            $msg .= '<th>Nama File</th>';
            $msg .= '</tr>';

            // Add all filenames and IDs to the email content
            foreach ($_POST['malik'] as $index => $nama) {
                $file_id = $_POST['id_file'][$index];
                $id_filedrive = 'https://drive.google.com/file/d/' . $file_id . '/view';
                $msg .= '<tr>';
                $msg .= '<td><a href="' . $id_filedrive . '">' . $nama . '</a></td>';
                $msg .= '</tr>';
            }

            $msg .= '</table>';
            $msg .= '<p>Silahkan direview untuk kelayakan mendapatkan approval. Jika Approve silahkan klik tombol Approve atau tombol Reject bila ditolak.</p>';
            $msg .= '        <table style="width: 100%; text-align: center;">';  // Aligning the table to center
            $msg .= '        <tr>';
            $msg .= '        <td style="padding: 10px;">';  // Adding padding around the cells
            $msg .= '            <a href="' . $linkapprove . '" style="display: inline-block; padding: 10px 20px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px;">Approve</a>';  // Changed color to blue
            $msg .= '        </td>';
            $msg .= '        <td style="padding: 10px;">';  // Adding padding around the cells
            $msg .= '            <a href="' . $linkdissaprove . '" style="display: inline-block; padding: 10px 20px; background-color: #f44336; color: white; text-decoration: none; border-radius: 5px;">Disapprove</a>';  // Keeping color red
            $msg .= '        </td>';
            $msg .= '        </tr>';
            $msg .= '        </table>';
            $msg .= '        <p>Terimakasih</p>';
            $msg .= '    </td>';
            $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
            $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
            $msg .= "</table>";
            // echo $msg;
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
        if ($_POST['status_karyawan'] != "Percobaan") {
            $to = $_POST['gm_offering'];
            // $to = 'malik.aulia@mastersystem.co.id';
            $from = "MSIZone<msizone@mastersystem.co.id>";
            $cc = "recruitment.team@mastersystem.co.id";
            $linkdissaprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval Offering] Offering Recruitment-' . $_POST['id_fpkb'] . '-' . $_POST['id_email'] . '&body=[Disapprove] - Offering bod%0D%0A[notes] - Offering bod';
            $linkapprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval Offering] Offering Recruitment-' . $_POST['id_fpkb'] . '-' . $_POST['id_email'] . '&body=[Approve] - Offering bod%0D%0A[notes] - Offering bod';
            $from = "MSIZone<msizone@mastersystem.co.id>";
            // $cc = "malik.aulia@mastersystem.co.id";
            $bcc = "";
            $reply = $from;
            $subject = "[Permohonan Approval Offering] Offering Recruitment";
            $msg = '<table width="100%">';
            $msg .= '    <tr><td width="20%" rowspan="4"></td>';
            $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
            $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
            $msg .= '    <td width="20%" rowspan="4"></tr>';
            $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
            $msg .= '        <p>Dear ' . $to . '</p>';
            $msg .= '        <p>Berikut ini adalah daftar Offering Request Karyawan yang menunggu approval dari Anda.</p>';
            $msg .= '        <p>';
            // $msg .= '<table width="80%">';
            // $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $_POST['id_fpkb'] . '</td></tr>';
            // $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi'] . '</td></tr>';
            // $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
            // $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $_POST['project_code'] . '</td></tr>';
            // $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $_POST['status_rekrutmen'] . '</td></tr>';
            // $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $_POST['status_karyawan'] . '</td></tr>';
            // $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $_POST['kandidat'] . '</td></tr>';
            // $msg .= '<tr><td>Nama Kandidat</td><td>:</td><td>' . $_POST['nama_kandidat'] . '</td></tr>';
            // $msg .= '<tr><td>Offering Salary</td><td>:</td><td>' . $_POST['offering_salary'] . '</td></tr>';
            // $msg .= '</table>';
            // $msg .= '</p>';


            $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
            $msg .= '                <tr><td style="width: 20%;">FPKB Number</td><td>:</td><td>' . htmlspecialchars($_POST['id_fpkb']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Division</td><td>:</td><td>' . htmlspecialchars($_POST['divisi']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Position</td><td>:</td><td>' . htmlspecialchars($_POST['posisi']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Nama Kandidat</td><td>:</td><td>' . htmlspecialchars($_POST['nama_kandidat']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Reason</td><td>:</td><td>' . htmlspecialchars($_POST['status_rekrutmen']);
            if ($_POST['status_rekrutmen'] == 'Penggantian') {
                $msg .= ' ' . htmlspecialchars($_POST['reason_penggantian']);
            }
            $msg .= '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Status Employee</td><td>:</td><td>' . htmlspecialchars($_POST['status_karyawan']) . '</td></tr>';

            $msg .= '                <tr><td style="width: 20%;">Source</td><td>:</td><td>' . htmlspecialchars($_POST['kandidat']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Salary Offering</td><td>:</td><td>' . htmlspecialchars($_POST['offering_salary']) . '</td></tr>';
            $msg .= '            </table>';
            $msg .= '            <p>Base On Project:</p>';
            $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
            $msg .= '                <tr><td style="width: 20%;">Project Code</td><td>:</td><td>' . htmlspecialchars($_POST['project_code']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Project Name</td><td>:</td><td>' . htmlspecialchars($_POST['project_name']) . '</td></tr>';
            $msg .= '            </table>';
            $msg .= '            <p>Approval:</p>';
            $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';

            $msg .= '                <tr><td style="width: 20%;">By Manager HCM</td><td>:</td><td>' . htmlspecialchars($_POST['manager_hcm']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_manager_hcm']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td>' . htmlspecialchars($_POST['status_manager_hcm']) . '</td></tr>';

            $msg .= '                <tr><td style="width: 20%;">By GM HCM</td><td>:</td><td>' . htmlspecialchars($_POST['hcm_gm']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_hcm_gm']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td>' . htmlspecialchars($_POST['status_hcm_gm']) . '</td></tr>';

            $msg .= '                <tr><td style="width: 20%;">By GM</td><td>:</td><td>' . htmlspecialchars($_POST['gm_offering']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_gm_offering']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td> Approve </td></tr>';
            $msg .= '            </table>';
            $msg .= '        <table>';

            $msg .= '</p>';


            $msg .= '<p>Berikut adalah Offering Kandidat. Mohon Reviewnya</p>';
            $msg .= '<table border="1">';
            $msg .= '<tr>';
            $msg .= '<th>Nama File</th>';
            $msg .= '</tr>';

            // Add all filenames and IDs to the email content
            foreach ($_POST['malik'] as $index => $nama) {
                $file_id = $_POST['id_file'][$index];
                $id_filedrive = 'https://drive.google.com/file/d/' . $file_id . '/view';
                $msg .= '<tr>';
                $msg .= '<td><a href="' . $id_filedrive . '">' . $nama . '</a></td>';
                $msg .= '</tr>';
            }

            $msg .= '</table>';
            $msg .= '<p>Silahkan direview untuk kelayakan mendapatkan approval. Jika Approve silahkan klik tombol Approve atau tombol Reject bila ditolak.</p>';
            $msg .= '        <table style="width: 100%; text-align: center;">';  // Aligning the table to center
            $msg .= '        <tr>';
            $msg .= '        <td style="padding: 10px;">';  // Adding padding around the cells
            $msg .= '            <a href="' . $linkapprove . '" style="display: inline-block; padding: 10px 20px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px;">Approve</a>';  // Changed color to blue
            $msg .= '        </td>';
            $msg .= '        <td style="padding: 10px;">';  // Adding padding around the cells
            $msg .= '            <a href="' . $linkdissaprove . '" style="display: inline-block; padding: 10px 20px; background-color: #f44336; color: white; text-decoration: none; border-radius: 5px;">Disapprove</a>';  // Keeping color red
            $msg .= '        </td>';
            $msg .= '        </tr>';
            $msg .= '        </table>';
            $msg .= '        <p>Terimakasih</p>';
            $msg .= '    </td>';
            $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
            $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
            $msg .= "</table>";
            // echo $msg;
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
} elseif (isset($_POST['dissaprove_gm_offering_offering'])) {
    $allquery = $DBHCM->get_sqlV2("SELECT * FROM sa_hcm_requirement JOIN sa_hcm_requirement_interview ON sa_hcm_requirement.id_fpkb = sa_hcm_requirement_interview.id_fpkb WHERE sa_hcm_requirement . id_fpkb = '" . $_POST['id_fpkb'] . "' AND sa_hcm_requirement_interview . email_id ='" . $_POST['id_email'] . "'");

    $condition = "email_id=" . $_POST['id_email'];
    $update = sprintf(
        "`status_gm_offering`=%s,`catatan_gm_offering`=%s",
        GetSQLValueString("Disapprove", "text"),
        GetSQLValueString($_POST['catatan_gm_offering'], "text")

    );
    $updateResult = $DBHCM->update_data('hcm_requirement_interview', $update, $condition);
    $mdlname = "NOTIFICATION";
    $DBNOTIF = get_conn($mdlname);
    $to = "Wahyuwinningdyah Margareta,S.S.PSI<margareta.sekar@mastersystem.co.id>";
    $from = $_POST['gm_offering'];
    $tblnamenotif = "trx_notification";
    $notifmsg = $_POST['id_fpkb'] . "; " . $_POST['project_code'] . "; ";
    $idfpkb = str_replace(' ', '_', $_POST['id_fpkb']); // Mengganti spasi dengan underscore
    // $notif_link = "index.php?mod=hcm_requirement&act=editapproval&id=" . $_POST['id'] . "&project_code=" . $_POST['project_code'] . "&submit=Submit";
    $notif_link = "index.php?mod=hcm_requirement&act=editoffering&id=" . $_POST['id_email'] . "&id_fpkb=" . $idfpkb . "&submit=Submit";
    $insert = sprintf(
        "(`notif_from`, `notif_to`, `notif_subject`, `notif_post`, `notif_link`) VALUES (%s, %s, %s, %s, %s)",
        GetSQLValueString($from, "text"),
        GetSQLValueString($to, "text"),
        GetSQLValueString(ucwords("Disapprove Offering Nama Kandidat ") . $_POST['nama_kandidat'], "text"),
        GetSQLValueString($notifmsg, "text"),
        GetSQLValueString($notif_link, "text")
    );
    $res = $DBNOTIF->insert_data($tblnamenotif, $insert);
    // var_dump($notif_link);
    // die;
    $description = $_POST['gm_offering'] . " Telah memberikan Disapprove pada data dengan ID FPKB " . $_POST['id_fpkb'] . " dan email kandidat: " . $_POST['email'];
    $insert = sprintf(
        "(`id_fpkb`,`project_code`,`project_name`,`description`,`entry_by`) VALUES (%s,%s,%s,%s,%s)",
        GetSQLValueString($_POST['id_fpkb'], "text"),
        GetSQLValueString($_POST['project_code'], "text"),
        GetSQLValueString($_POST['project_name'], "text"),
        GetSQLValueString($description, "text"),
        GetSQLValueString($_POST['gm_offering'], "text")
    );
    $res = $DBHCM->insert_data("hcm_requirement_log", $insert);
    if ($allquery[0]['bod'] == null) {
        $updateResult = $DBHCM->get_res("UPDATE sa_hcm_requirement_interview SET status = 'Complete' WHERE email_id = '" . $_POST['id_email'] . "'");
        $to = $_POST['assign_requirement'];
        // $cc = "recruitment.team@mastersystem.co.id";
        $cc = "malik.aulia@mastersystem.co.id";
        $from = "MSIZone<msizone@mastersystem.co.id>";
        $bcc = "";
        $reply = $from;
        $subject = "[Permohonan Approval Offering] Offering Recruitment";
        $msg = '<table width="100%">';
        $msg .= '    <tr><td width="20%" rowspan="4"></td>';
        $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
        $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
        $msg .= '    <td width="20%" rowspan="4"></tr>';
        $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
        $msg .= '        <p>Dear ' . $to . '</p>';
        $msg .= '        <p>Berikut ini adalah daftar Offering Request Karyawan yang mendapatkan Dissapprove.</p>';
        $msg .= '        <p>';
        // $msg .= '<table width="80%">';
        // $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $_POST['id_fpkb'] . '</td></tr>';
        // $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi'] . '</td></tr>';
        // $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
        // $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $_POST['project_code'] . '</td></tr>';
        // $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $_POST['status_rekrutmen'] . '</td></tr>';
        // $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $_POST['status_karyawan'] . '</td></tr>';
        // $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $_POST['kandidat'] . '</td></tr>';
        // $msg .= '<tr><td>Nama Kandidat</td><td>:</td><td>' . $_POST['nama_kandidat'] . '</td></tr>';
        // $msg .= '</table>';
        // $msg .= '</p>';


        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
        $msg .= '                <tr><td style="width: 20%;">FPKB Number</td><td>:</td><td>' . htmlspecialchars($_POST['id_fpkb']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Division</td><td>:</td><td>' . htmlspecialchars($_POST['divisi']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Position</td><td>:</td><td>' . htmlspecialchars($_POST['posisi']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Nama Kandidat</td><td>:</td><td>' . htmlspecialchars($_POST['nama_kandidat']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Reason</td><td>:</td><td>' . htmlspecialchars($_POST['status_rekrutmen']);
        if ($_POST['status_rekrutmen'] == 'Penggantian') {
            $msg .= ' ' . htmlspecialchars($_POST['reason_penggantian']);
        }
        $msg .= '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status Employee</td><td>:</td><td>' . htmlspecialchars($_POST['status_karyawan']) . '</td></tr>';

        $msg .= '                <tr><td style="width: 20%;">Source</td><td>:</td><td>' . htmlspecialchars($_POST['kandidat']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Salary Offering</td><td>:</td><td>' . htmlspecialchars($_POST['offering_salary']) . '</td></tr>';
        $msg .= '            </table>';
        $msg .= '            <p>Base On Project:</p>';
        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
        $msg .= '                <tr><td style="width: 20%;">Project Code</td><td>:</td><td>' . htmlspecialchars($_POST['project_code']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Project Name</td><td>:</td><td>' . htmlspecialchars($_POST['project_name']) . '</td></tr>';
        $msg .= '            </table>';
        $msg .= '            <p>Approval:</p>';
        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';

        $msg .= '                <tr><td style="width: 20%;">By Manager HCM</td><td>:</td><td>' . htmlspecialchars($_POST['manager_hcm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_manager_hcm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td>' . htmlspecialchars($_POST['status_manager_hcm']) . '</td></tr>';

        $msg .= '                <tr><td style="width: 20%;">By GM HCM</td><td>:</td><td>' . htmlspecialchars($_POST['hcm_gm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_hcm_gm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td>' . htmlspecialchars($_POST['status_hcm_gm']) . '</td></tr>';

        $msg .= '                <tr><td style="width: 20%;">By GM</td><td>:</td><td>' . htmlspecialchars($_POST['gm_offering']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_gm_offering']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td> Dissaprove </td></tr>';
        $msg .= '            </table>';
        $msg .= '        <table>';

        $msg .= '</p>';


        $msg .= '<p>Berikut adalah Offering Kandidat. Mohon Reviewnya</p>';
        $msg .= '<table border="1">';
        $msg .= '<tr>';
        $msg .= '<th>Nama File</th>';
        $msg .= '</tr>';

        // Add all filenames and IDs to the email content
        foreach ($_POST['malik'] as $index => $nama) {
            $file_id = $_POST['id_file'][$index];
            $id_filedrive = 'https://drive.google.com/file/d/' . $file_id . '/view';
            $msg .= '<tr>';
            $msg .= '<td><a href="' . $id_filedrive . '">' . $nama . '</a></td>';
            $msg .= '</tr>';
        }

        $msg .= '</table>';
        $msg .= '<p>Silahkan direview untuk ditidak lanjuti.</p>';
        $msg .= '        <table>';
        $msg .= '        <tr>';
        $msg .= '        </table>';
        $msg .= '        <p>Terimakasih</p>';
        $msg .= '    </td>';
        $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
        $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
        $msg .= "</table>";
        // echo $msg;
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
    } elseif ($allquery[0]['bod'] != null) {
        if ($_POST['status_karyawan'] == "Percobaan") {
            // $to = 'malik.aulia@mastersystem.co.id';
            $to = $_POST['gm_offering'];
            $from = "MSIZone<msizone@mastersystem.co.id>";
            $cc = "recruitment.team@mastersystem.co.id";
            $linkdissaprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval Offering] Offering Recruitment-' . $_POST['id_fpkb'] . '-' . $_POST['id_email'] . '&body=[Disapprove] - Offering bod%0D%0A[notes] - Offering bod';
            $linkapprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval Offering] Offering Recruitment-' . $_POST['id_fpkb'] . '-' . $_POST['id_email'] . '&body=[Approve] - Offering bod%0D%0A[notes] - Offering bod';
            $from = "MSIZone<msizone@mastersystem.co.id>";
            // $cc = "malik.aulia@mastersystem.co.id";
            $bcc = "";
            $reply = $from;
            $subject = "[Permohonan Approval Offering] Offering Recruitment";
            $msg = '<table width="100%">';
            $msg .= '    <tr><td width="20%" rowspan="4"></td>';
            $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
            $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
            $msg .= '    <td width="20%" rowspan="4"></tr>';
            $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
            $msg .= '        <p>Dear ' . $to . '</p>';
            $msg .= '        <p>Berikut ini adalah daftar Offering Request Karyawan yang menunggu approval dari Anda.</p>';
            $msg .= '        <p>';
            // $msg .= '<table width="80%">';
            // $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $_POST['id_fpkb'] . '</td></tr>';
            // $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi'] . '</td></tr>';
            // $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
            // $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $_POST['project_code'] . '</td></tr>';
            // $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $_POST['status_rekrutmen'] . '</td></tr>';
            // $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $_POST['status_karyawan'] . '</td></tr>';
            // $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $_POST['kandidat'] . '</td></tr>';
            // $msg .= '<tr><td>Nama Kandidat</td><td>:</td><td>' . $_POST['nama_kandidat'] . '</td></tr>';
            // $msg .= '<tr><td>Salary Probation</td><td>:</td><td>' . $_POST['salary_Probation'] . '</td></tr>';
            // $msg .= '<tr><td>Salary Permanent</td><td>:</td><td>' . $_POST['salary_Permanent'] . '</td></tr>';
            // $msg .= '</table>';
            // $msg .= '</p>';


            $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
            $msg .= '                <tr><td style="width: 20%;">FPKB Number</td><td>:</td><td>' . htmlspecialchars($_POST['id_fpkb']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Division</td><td>:</td><td>' . htmlspecialchars($_POST['divisi']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Position</td><td>:</td><td>' . htmlspecialchars($_POST['posisi']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Nama Kandidat</td><td>:</td><td>' . htmlspecialchars($_POST['nama_kandidat']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Reason</td><td>:</td><td>' . htmlspecialchars($_POST['status_rekrutmen']);
            if ($_POST['status_rekrutmen'] == 'Penggantian') {
                $msg .= ' ' . htmlspecialchars($_POST['reason_penggantian']);
            }
            $msg .= '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Status Employee</td><td>:</td><td>' . htmlspecialchars($_POST['status_karyawan']) . '</td></tr>';

            $msg .= '                <tr><td style="width: 20%;">Source</td><td>:</td><td>' . htmlspecialchars($_POST['kandidat']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Salary Offering</td><td>:</td><td>' . htmlspecialchars($_POST['offering_salary']) . '</td></tr>';
            $msg .= '            </table>';
            $msg .= '            <p>Base On Project:</p>';
            $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
            $msg .= '                <tr><td style="width: 20%;">Project Code</td><td>:</td><td>' . htmlspecialchars($_POST['project_code']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Project Name</td><td>:</td><td>' . htmlspecialchars($_POST['project_name']) . '</td></tr>';
            $msg .= '            </table>';
            $msg .= '            <p>Approval:</p>';
            $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';

            $msg .= '                <tr><td style="width: 20%;">By Manager HCM</td><td>:</td><td>' . htmlspecialchars($_POST['manager_hcm']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_manager_hcm']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td>' . htmlspecialchars($_POST['status_manager_hcm']) . '</td></tr>';

            $msg .= '                <tr><td style="width: 20%;">By GM HCM</td><td>:</td><td>' . htmlspecialchars($_POST['hcm_gm']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_hcm_gm']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td>' . htmlspecialchars($_POST['status_hcm_gm']) . '</td></tr>';

            $msg .= '                <tr><td style="width: 20%;">By GM</td><td>:</td><td>' . htmlspecialchars($_POST['gm_offering']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_gm_offering']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td> Disapprove </td></tr>';
            $msg .= '            </table>';
            $msg .= '        <table>';

            $msg .= '</p>';


            $msg .= '<p>Berikut adalah Offering Kandidat. Mohon Reviewnya</p>';
            $msg .= '<table border="1">';
            $msg .= '<tr>';
            $msg .= '<th>Nama File</th>';
            $msg .= '</tr>';

            // Add all filenames and IDs to the email content
            foreach ($_POST['malik'] as $index => $nama) {
                $file_id = $_POST['id_file'][$index];
                $id_filedrive = 'https://drive.google.com/file/d/' . $file_id . '/view';
                $msg .= '<tr>';
                $msg .= '<td><a href="' . $id_filedrive . '">' . $nama . '</a></td>';
                $msg .= '</tr>';
            }

            $msg .= '</table>';
            $msg .= '<p>Silahkan direview untuk kelayakan mendapatkan approval. Jika Approve silahkan klik tombol Approve atau tombol Reject bila ditolak.</p>';
            $msg .= '        <table style="width: 100%; text-align: center;">';  // Aligning the table to center
            $msg .= '        <tr>';
            $msg .= '        <td style="padding: 10px;">';  // Adding padding around the cells
            $msg .= '            <a href="' . $linkapprove . '" style="display: inline-block; padding: 10px 20px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px;">Approve</a>';  // Changed color to blue
            $msg .= '        </td>';
            $msg .= '        <td style="padding: 10px;">';  // Adding padding around the cells
            $msg .= '            <a href="' . $linkdissaprove . '" style="display: inline-block; padding: 10px 20px; background-color: #f44336; color: white; text-decoration: none; border-radius: 5px;">Disapprove</a>';  // Keeping color red
            $msg .= '        </td>';
            $msg .= '        </tr>';
            $msg .= '        </table>';
            $msg .= '        <p>Terimakasih</p>';
            $msg .= '    </td>';
            $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
            $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
            $msg .= "</table>";
            // echo $msg;
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
        if ($_POST['status_karyawan'] != "Percobaan") {
            $to = $_POST['gm_offering'];
            // $to = 'malik.aulia@mastersystem.co.id';
            $from = "MSIZone<msizone@mastersystem.co.id>";
            $cc = "recruitment.team@mastersystem.co.id";
            $linkdissaprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval Offering] Offering Recruitment-' . $_POST['id_fpkb'] . '-' . $_POST['id_email'] . '&body=[Disapprove] - Offering bod%0D%0A[notes] - Offering bod';
            $linkapprove = 'mailto:repoadmin@mastersystem.co.id?subject=[Permohonan Approval Offering] Offering Recruitment-' . $_POST['id_fpkb'] . '-' . $_POST['id_email'] . '&body=[Approve] - Offering bod%0D%0A[notes] - Offering bod';
            $from = "MSIZone<msizone@mastersystem.co.id>";
            // $cc = "malik.aulia@mastersystem.co.id";
            $bcc = "";
            $reply = $from;
            $subject = "[Permohonan Approval Offering] Offering Recruitment";
            $msg = '<table width="100%">';
            $msg .= '    <tr><td width="20%" rowspan="4"></td>';
            $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
            $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
            $msg .= '    <td width="20%" rowspan="4"></tr>';
            $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
            $msg .= '        <p>Dear ' . $to . '</p>';
            $msg .= '        <p>Berikut ini adalah daftar Offering Request Karyawan yang menunggu approval dari Anda.</p>';
            $msg .= '        <p>';
            // $msg .= '<table width="80%">';
            // $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $_POST['id_fpkb'] . '</td></tr>';
            // $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi'] . '</td></tr>';
            // $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
            // $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $_POST['project_code'] . '</td></tr>';
            // $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $_POST['status_rekrutmen'] . '</td></tr>';
            // $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $_POST['status_karyawan'] . '</td></tr>';
            // $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $_POST['kandidat'] . '</td></tr>';
            // $msg .= '<tr><td>Nama Kandidat</td><td>:</td><td>' . $_POST['nama_kandidat'] . '</td></tr>';
            // $msg .= '<tr><td>Offering Salary</td><td>:</td><td>' . $_POST['offering_salary'] . '</td></tr>';
            // $msg .= '</table>';
            // $msg .= '</p>';


            $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
            $msg .= '                <tr><td style="width: 20%;">FPKB Number</td><td>:</td><td>' . htmlspecialchars($_POST['id_fpkb']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Division</td><td>:</td><td>' . htmlspecialchars($_POST['divisi']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Position</td><td>:</td><td>' . htmlspecialchars($_POST['posisi']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Nama Kandidat</td><td>:</td><td>' . htmlspecialchars($_POST['nama_kandidat']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Reason</td><td>:</td><td>' . htmlspecialchars($_POST['status_rekrutmen']);
            if ($_POST['status_rekrutmen'] == 'Penggantian') {
                $msg .= ' ' . htmlspecialchars($_POST['reason_penggantian']);
            }
            $msg .= '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Status Employee</td><td>:</td><td>' . htmlspecialchars($_POST['status_karyawan']) . '</td></tr>';

            $msg .= '                <tr><td style="width: 20%;">Source</td><td>:</td><td>' . htmlspecialchars($_POST['kandidat']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Salary Offering</td><td>:</td><td>' . htmlspecialchars($_POST['offering_salary']) . '</td></tr>';
            $msg .= '            </table>';
            $msg .= '            <p>Base On Project:</p>';
            $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
            $msg .= '                <tr><td style="width: 20%;">Project Code</td><td>:</td><td>' . htmlspecialchars($_POST['project_code']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Project Name</td><td>:</td><td>' . htmlspecialchars($_POST['project_name']) . '</td></tr>';
            $msg .= '            </table>';
            $msg .= '            <p>Approval:</p>';
            $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';

            $msg .= '                <tr><td style="width: 20%;">By Manager HCM</td><td>:</td><td>' . htmlspecialchars($_POST['manager_hcm']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_manager_hcm']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td>' . htmlspecialchars($_POST['status_manager_hcm']) . '</td></tr>';

            $msg .= '                <tr><td style="width: 20%;">By GM HCM</td><td>:</td><td>' . htmlspecialchars($_POST['hcm_gm']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_hcm_gm']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td>' . htmlspecialchars($_POST['status_hcm_gm']) . '</td></tr>';

            $msg .= '                <tr><td style="width: 20%;">By GM</td><td>:</td><td>' . htmlspecialchars($_POST['gm_offering']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_gm_offering']) . '</td></tr>';
            $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td> Disapprove </td></tr>';
            $msg .= '            </table>';
            $msg .= '        <table>';

            $msg .= '</p>';


            $msg .= '<p>Berikut adalah Offering Kandidat. Mohon Reviewnya</p>';
            $msg .= '<table border="1">';
            $msg .= '<tr>';
            $msg .= '<th>Nama File</th>';
            $msg .= '</tr>';

            // Add all filenames and IDs to the email content
            foreach ($_POST['malik'] as $index => $nama) {
                $file_id = $_POST['id_file'][$index];
                $id_filedrive = 'https://drive.google.com/file/d/' . $file_id . '/view';
                $msg .= '<tr>';
                $msg .= '<td><a href="' . $id_filedrive . '">' . $nama . '</a></td>';
                $msg .= '</tr>';
            }

            $msg .= '</table>';
            $msg .= '<p>Silahkan direview untuk kelayakan mendapatkan approval. Jika Approve silahkan klik tombol Approve atau tombol Reject bila ditolak.</p>';
            $msg .= '        <table style="width: 100%; text-align: center;">';  // Aligning the table to center
            $msg .= '        <tr>';
            $msg .= '        <td style="padding: 10px;">';  // Adding padding around the cells
            $msg .= '            <a href="' . $linkapprove . '" style="display: inline-block; padding: 10px 20px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px;">Approve</a>';  // Changed color to blue
            $msg .= '        </td>';
            $msg .= '        <td style="padding: 10px;">';  // Adding padding around the cells
            $msg .= '            <a href="' . $linkdissaprove . '" style="display: inline-block; padding: 10px 20px; background-color: #f44336; color: white; text-decoration: none; border-radius: 5px;">Disapprove</a>';  // Keeping color red
            $msg .= '        </td>';
            $msg .= '        </tr>';
            $msg .= '        </table>';
            $msg .= '        <p>Terimakasih</p>';
            $msg .= '    </td>';
            $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
            $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
            $msg .= "</table>";
            // echo $msg;
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
} elseif (isset($_POST['approve_bod_offering'])) {
    $condition = "email_id=" . $_POST['id_email'];
    $update = sprintf(
        "`status_bod`=%s,`catatan_bod`=%s",
        GetSQLValueString("Approve", "text"),
        GetSQLValueString($_POST['catatan_bod'], "text")

    );
    $updateResult = $DBHCM->update_data('hcm_requirement_interview', $update, $condition);
    $mdlname = "NOTIFICATION";
    $DBNOTIF = get_conn($mdlname);
    $to = "Wahyuwinningdyah Margareta,S.S.PSI<margareta.sekar@mastersystem.co.id>";
    $from = $_POST['bod'];
    $tblnamenotif = "trx_notification";
    $notifmsg = $_POST['id_fpkb'] . "; " . $_POST['project_code'] . "; ";
    $idfpkb = str_replace(' ', '_', $_POST['id_fpkb']); // Mengganti spasi dengan underscore
    // $notif_link = "index.php?mod=hcm_requirement&act=editapproval&id=" . $_POST['id'] . "&project_code=" . $_POST['project_code'] . "&submit=Submit";
    $notif_link = "index.php?mod=hcm_requirement&act=editoffering&id=" . $_POST['id_email'] . "&id_fpkb=" . $idfpkb . "&submit=Submit";
    $insert = sprintf(
        "(`notif_from`, `notif_to`, `notif_subject`, `notif_post`, `notif_link`) VALUES (%s, %s, %s, %s, %s)",
        GetSQLValueString($from, "text"),
        GetSQLValueString($to, "text"),
        GetSQLValueString(ucwords("Approve Offering Nama Kandidat ") . $_POST['nama_kandidat'], "text"),
        GetSQLValueString($notifmsg, "text"),
        GetSQLValueString($notif_link, "text")
    );
    $res = $DBNOTIF->insert_data($tblnamenotif, $insert);
    $description = $_POST['bod'] . " Telah memberikan Approve pada data dengan ID FPKB " . $_POST['id_fpkb'] . " dan email kandidat: " . $_POST['email'];
    $insert = sprintf(
        "(`id_fpkb`,`project_code`,`project_name`,`description`,`entry_by`) VALUES (%s,%s,%s,%s,%s)",
        GetSQLValueString($_POST['id_fpkb'], "text"),
        GetSQLValueString($_POST['project_code'], "text"),
        GetSQLValueString($_POST['project_name'], "text"),
        GetSQLValueString($description, "text"),
        GetSQLValueString($_POST['bod'], "text")
    );
    $res = $DBHCM->insert_data("hcm_requirement_log", $insert);
    $updateResult = $DBHCM->get_res("UPDATE sa_hcm_requirement_interview SET status = 'Complete' WHERE email_id = '" . $_POST['id_email'] . "'");
    // echo "dapat approve";
    // $to = "malik.aulia@mastersystem.co.id";
    if ($_POST['status_karyawan'] == "Percobaan") {
        $to = $_POST['assign_requirement'];
        $from = "MSIZone<msizone@mastersystem.co.id>";
        // $from = "MSIZone<msizone@mastersystem.co.id>";
        // $cc = "malik.aulia@mastersystem.co.id";
        $cc = "recruitment.team@mastersystem.co.id";
        $bcc = "";
        $reply = $from;
        $subject = "[Permohonan Approval Offering] Offering Recruitment";
        $msg = '<table width="100%">';
        $msg .= '    <tr><td width="20%" rowspan="4"></td>';
        $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
        $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
        $msg .= '    <td width="20%" rowspan="4"></tr>';
        $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
        $msg .= '        <p>Dear ' . $to . '</p>';
        $msg .= '        <p>Berikut ini adalah daftar Offering Request Karyawan yang telah mendapatkan approval.</p>';
        $msg .= '        <p>';
        // $msg .= '<table width="80%">';
        // $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $_POST['id_fpkb'] . '</td></tr>';
        // $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi'] . '</td></tr>';
        // $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
        // $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $_POST['project_code'] . '</td></tr>';
        // $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $_POST['status_rekrutmen'] . '</td></tr>';
        // $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $_POST['status_karyawan'] . '</td></tr>';
        // $msg .= '<tr><td>Email Kandidat</td><td>:</td><td>' . $_POST['email'] . '</td></tr>';
        // $msg .= '<tr><td>Nama Kandidat</td><td>:</td><td>' . $_POST['nama_kandidat'] . '</td></tr>';
        // $msg .= '<tr><td>Salary Probation</td><td>:</td><td>' . $_POST['salary_Probation'] . '</td></tr>';
        // $msg .= '<tr><td>Salary Permanent</td><td>:</td><td>' . $_POST['salary_Permanent'] . '</td></tr>';
        // $msg .= '</table>';
        // $msg .= '</p>';


        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
        $msg .= '                <tr><td style="width: 20%;">FPKB Number</td><td>:</td><td>' . htmlspecialchars($_POST['id_fpkb']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Division</td><td>:</td><td>' . htmlspecialchars($_POST['divisi']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Position</td><td>:</td><td>' . htmlspecialchars($_POST['posisi']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Nama Kandidat</td><td>:</td><td>' . htmlspecialchars($_POST['nama_kandidat']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Reason</td><td>:</td><td>' . htmlspecialchars($_POST['status_rekrutmen']);
        if ($_POST['status_rekrutmen'] == 'Penggantian') {
            $msg .= ' ' . htmlspecialchars($_POST['reason_penggantian']);
        }
        $msg .= '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status Employee</td><td>:</td><td>' . htmlspecialchars($_POST['status_karyawan']) . '</td></tr>';

        $msg .= '                <tr><td style="width: 20%;">Source</td><td>:</td><td>' . htmlspecialchars($_POST['kandidat']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Salary Offering</td><td>:</td><td>' . htmlspecialchars($_POST['offering_salary']) . '</td></tr>';
        $msg .= '            </table>';
        $msg .= '            <p>Base On Project:</p>';
        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
        $msg .= '                <tr><td style="width: 20%;">Project Code</td><td>:</td><td>' . htmlspecialchars($_POST['project_code']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Project Name</td><td>:</td><td>' . htmlspecialchars($_POST['project_name']) . '</td></tr>';
        $msg .= '            </table>';
        $msg .= '            <p>Approval:</p>';
        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';

        $msg .= '                <tr><td style="width: 20%;">By Manager HCM</td><td>:</td><td>' . htmlspecialchars($_POST['manager_hcm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_manager_hcm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td>' . htmlspecialchars($_POST['status_manager_hcm']) . '</td></tr>';

        $msg .= '                <tr><td style="width: 20%;">By GM HCM</td><td>:</td><td>' . htmlspecialchars($_POST['hcm_gm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_hcm_gm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td>' . htmlspecialchars($_POST['status_hcm_gm']) . '</td></tr>';

        $msg .= '                <tr><td style="width: 20%;">By GM</td><td>:</td><td>' . htmlspecialchars($_POST['gm_offering']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_gm_offering']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td>' . htmlspecialchars($_POST['status_gm_offering']) . '</td></tr>';

        $msg .= '                <tr><td style="width: 20%;">By BOD</td><td>:</td><td>' . htmlspecialchars($_POST['bod']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($_POST['catatan_bod']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td> Approve </td></tr>';
        $msg .= '            </table>';
        $msg .= '        <table>';

        $msg .= '</p>';



        $msg .= '<p>Berikut adalah Offering Kandidat. Mohon Reviewnya</p>';
        $msg .= '<table border="1">';
        $msg .= '<tr>';
        $msg .= '<th>Nama File</th>';
        $msg .= '</tr>';

        // Add all filenames and IDs to the email content
        foreach ($_POST['malik'] as $index => $nama) {
            $file_id = $_POST['id_file'][$index];
            $id_filedrive = 'https://drive.google.com/file/d/' . $file_id . '/view';
            $msg .= '<tr>';
            $msg .= '<td><a href="' . $id_filedrive . '">' . $nama . '</a></td>';
            $msg .= '</tr>';
        }

        $msg .= '</table>';
        $msg .= '<p>Silahkan diproses.</p>';
        $msg .= '        <table>';
        $msg .= '        <tr>';
        $msg .= '        </table>';
        $msg .= '        <p>Terimakasih</p>';
        $msg .= '    </td>';
        $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
        $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
        $msg .= "</table>";
        // echo $msg;
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
    if ($_POST['status_karyawan'] != "Percobaan") {
        $to = $_POST['assign_requirement'];
        $from = "MSIZone<msizone@mastersystem.co.id>";
        $cc = "malik.aulia@mastersystem.co.id";
        $cc = "recruitment.team@mastersystem.co.id";
        $bcc = "";
        $reply = $from;
        $subject = "[Permohonan Approval Offering] Offering Recruitment";
        $msg = '<table width="100%">';
        $msg .= '    <tr><td width="20%" rowspan="4"></td>';
        $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
        $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
        $msg .= '    <td width="20%" rowspan="4"></tr>';
        $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
        $msg .= '        <p>Dear ' . $to . '</p>';
        $msg .= '        <p>Berikut ini adalah daftar Offering Request Karyawan yang telah mendapatkan approval.</p>';
        $msg .= '        <p>';
        $msg .= '<table width="80%">';
        $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $_POST['id_fpkb'] . '</td></tr>';
        $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi'] . '</td></tr>';
        $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
        $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $_POST['project_code'] . '</td></tr>';
        $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $_POST['status_rekrutmen'] . '</td></tr>';
        $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $_POST['status_karyawan'] . '</td></tr>';
        $msg .= '<tr><td>Email Kandidat</td><td>:</td><td>' . $_POST['email'] . '</td></tr>';
        $msg .= '<tr><td>Nama Kandidat</td><td>:</td><td>' . $_POST['nama_kandidat'] . '</td></tr>';
        $msg .= '<tr><td>Offering Salary</td><td>:</td><td>' . $_POST['offering_salary'] . '</td></tr>';
        $msg .= '</table>';
        $msg .= '</p>';
        $msg .= '<p>Berikut adalah Offering Kandidat. Mohon Reviewnya</p>';
        $msg .= '<table border="1">';
        $msg .= '<tr>';
        $msg .= '<th>Nama File</th>';
        $msg .= '</tr>';

        // Add all filenames and IDs to the email content
        foreach ($_POST['malik'] as $index => $nama) {
            $file_id = $_POST['id_file'][$index];
            $id_filedrive = 'https://drive.google.com/file/d/' . $file_id . '/view';
            $msg .= '<tr>';
            $msg .= '<td><a href="' . $id_filedrive . '">' . $nama . '</a></td>';
            $msg .= '</tr>';
        }

        $msg .= '</table>';
        $msg .= '<p>Silahkan diproses.</p>';
        $msg .= '        <table>';
        $msg .= '        <tr>';
        $msg .= '        </table>';
        $msg .= '        <p>Terimakasih</p>';
        $msg .= '    </td>';
        $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
        $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
        $msg .= "</table>";
        // echo $msg;
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
} elseif (isset($_POST['dissaprove_bod_offering'])) {
    $condition = "email_id=" . $_POST['id_email'];
    $update = sprintf(
        "`status_bod`=%s,`catatan_bod`=%s",
        GetSQLValueString("Disapprove", "text"),
        GetSQLValueString($_POST['catatan_bod'], "text")

    );
    $updateResult = $DBHCM->update_data('hcm_requirement_interview', $update, $condition);
    $mdlname = "NOTIFICATION";
    $DBNOTIF = get_conn($mdlname);
    $to = "Wahyuwinningdyah Margareta,S.S.PSI<margareta.sekar@mastersystem.co.id>";
    $from = $_POST['bod'];
    $tblnamenotif = "trx_notification";
    $notifmsg = $_POST['id_fpkb'] . "; " . $_POST['project_code'] . "; ";
    $idfpkb = str_replace(' ', '_', $_POST['id_fpkb']); // Mengganti spasi dengan underscore
    // $notif_link = "index.php?mod=hcm_requirement&act=editapproval&id=" . $_POST['id'] . "&project_code=" . $_POST['project_code'] . "&submit=Submit";
    $notif_link = "index.php?mod=hcm_requirement&act=editoffering&id=" . $_POST['id_email'] . "&id_fpkb=" . $idfpkb . "&submit=Submit";
    $insert = sprintf(
        "(`notif_from`, `notif_to`, `notif_subject`, `notif_post`, `notif_link`) VALUES (%s, %s, %s, %s, %s)",
        GetSQLValueString($from, "text"),
        GetSQLValueString($to, "text"),
        GetSQLValueString(ucwords("Disapprove Offering Nama Kandidat ") . $_POST['nama_kandidat'], "text"),
        GetSQLValueString($notifmsg, "text"),
        GetSQLValueString($notif_link, "text")
    );
    $res = $DBNOTIF->insert_data($tblnamenotif, $insert);
    $description = $_POST['bod'] . " Telah memberikan Disapprove pada data dengan ID FPKB " . $_POST['id_fpkb'] . " dan email kandidat: " . $_POST['email'];
    $insert = sprintf(
        "(`id_fpkb`,`project_code`,`project_name`,`description`,`entry_by`) VALUES (%s,%s,%s,%s,%s)",
        GetSQLValueString($_POST['id_fpkb'], "text"),
        GetSQLValueString($_POST['project_code'], "text"),
        GetSQLValueString($_POST['project_name'], "text"),
        GetSQLValueString($description, "text"),
        GetSQLValueString($_POST['bod'], "text")
    );
    $res = $DBHCM->insert_data("hcm_requirement_log", $insert);
    $updateResult = $DBHCM->get_res("UPDATE sa_hcm_requirement_interview SET status = 'Complete' WHERE email_id = '" . $_POST['id_email'] . "'");
    $to = $_POST['assign_requirement'];
    $from = "MSIZone<msizone@mastersystem.co.id>";
    $cc = "recruitment.team@mastersystem.co.id";
    $bcc = "";
    $reply = $from;
    $subject = "[Permohonan Approval Offering] Offering Recruitment";
    $msg = '<table width="100%">';
    $msg .= '    <tr><td width="20%" rowspan="4"></td>';
    $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
    $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
    $msg .= '    <td width="20%" rowspan="4"></tr>';
    $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
    $msg .= '        <p>Dear ' . $to . '</p>';
    $msg .= '        <p>Berikut ini adalah daftar Offering Request Karyawan yang mendapatkan Dissapprove.</p>';
    $msg .= '        <p>';
    $msg .= '<table width="80%">';
    $msg .= '<tr><td>ID FPKB</td><td>:</td><td>' . $_POST['id_fpkb'] . '</td></tr>';
    $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi'] . '</td></tr>';
    $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
    $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $_POST['project_code'] . '</td></tr>';
    $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $_POST['status_rekrutmen'] . '</td></tr>';
    $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $_POST['status_karyawan'] . '</td></tr>';
    $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $_POST['kandidat'] . '</td></tr>';
    $msg .= '<tr><td>Nama Kandidat</td><td>:</td><td>' . $_POST['nama_kandidat'] . '</td></tr>';
    $msg .= '</table>';
    $msg .= '</p>';
    $msg .= '<p>Berikut adalah Offering Kandidat. Mohon Reviewnya</p>';
    $msg .= '<table border="1">';
    $msg .= '<tr>';
    $msg .= '<th>Nama File</th>';
    $msg .= '</tr>';

    // Add all filenames and IDs to the email content
    foreach ($_POST['malik'] as $index => $nama) {
        $file_id = $_POST['id_file'][$index];
        $id_filedrive = 'https://drive.google.com/file/d/' . $file_id . '/view';
        $msg .= '<tr>';
        $msg .= '<td><a href="' . $id_filedrive . '">' . $nama . '</a></td>';
        $msg .= '</tr>';
    }

    $msg .= '</table>';
    $msg .= '<p>Silahkan direview untuk ditidak lanjuti.</p>';
    $msg .= '        <table>';
    $msg .= '        <tr>';
    $msg .= '        </table>';
    $msg .= '        <p>Terimakasih</p>';
    $msg .= '    </td>';
    $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
    $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
    $msg .= "</table>";
    // echo $msg;
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
} elseif (isset($_POST['send_email_offering'])) {
    $userName = addslashes($_SESSION['Microservices_UserName']);
    $entry_by = $userName . "<" . $_SESSION['Microservices_UserEmail'] . ">";
    $kp = $_POST['project_code'];
    $idfpkb = $_POST['id_fpkb'];
    $posisi = $_POST['posisi'];
    $to = "recruitment.team@mastersystem.co.id";
    $from = "MSIZone<msizone@mastersystem.co.id>";

    // Handle CC field, check if it's set and not empty
    $cc_list = [
        $_POST['manager_hcm'] ?? '',
        $_POST['hcm_gm'] ?? '',
        $_POST['gm_offering'] ?? '',
        $_POST['bod'] ?? ''
    ];
    $cc_list = array_filter($cc_list); // Remove empty values
    $cc = implode(',', $cc_list);

    $bcc = "";
    $reply = $from;
    $subject = "[Offering Kandidat] $idfpkb - $posisi";

    // Initialize email message content
    $msg = '<table width="100%">';
    $msg .= '    <tr><td width="20%" rowspan="4"></td>';
    $msg .= '    <td style="width:60%; padding:20px; border:thin solid #dadada">';
    $msg .= '        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
    $msg .= '    <td width="20%" rowspan="4"></tr>';
    $msg .= '    <tr><td style="padding:20px; border:thin solid #dadada">';
    $msg .= '        <p>Dear ' . $to . '</p>';
    $msg .= '        <p>';
    $msg .= '<table width="80%">';
    $msg .= '<tr><td>ID FKPB</td><td>:</td><td>' . $_POST['id_fpkb'] . '</td></tr>';
    $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi'] . '</td></tr>';
    $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
    $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $_POST['project_code'] . '</td></tr>';
    $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $_POST['kandidat'] . '</td></tr>';
    $msg .= '<tr><td>Nama Kandidat</td><td>:</td><td>' . $_POST['nama_kandidat'] . '</td></tr>';
    $msg .= '</table>';
    $msg .= '</p>';
    $msg .= '<p>Berikut adalah Offering Kandidat. Mohon Reviewnya</p>';
    $msg .= '<table border="1">';
    $msg .= '<tr>';
    $msg .= '<th>Nama File</th>';
    $msg .= '</tr>';

    // Add all filenames and IDs to the email content
    foreach ($_POST['malik'] as $index => $nama) {
        $file_id = $_POST['id_file'][$index];
        $id_filedrive = 'https://drive.google.com/file/d/' . $file_id . '/view';
        $msg .= '<tr>';
        $msg .= '<td><a href="' . $id_filedrive . '">' . $nama . '</a></td>';
        $msg .= '</tr>';
    }

    $msg .= '</table>';
    $msg .= '        <table>';
    $msg .= '        <tr>';
    $msg .= '        </table>';
    $msg .= '        <p>Terimakasih</p>';
    $msg .= '    </td>';
    $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
    $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
    $msg .= "</table>";

    // echo $msg;


    $headers = "From: " . $from . "\r\n" .
        "Reply-To: " . $reply . "\r\n" .
        (!empty($cc) ? "Cc: " . $cc . "\r\n" : "") .
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
