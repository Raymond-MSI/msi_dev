<?php

$_SESSION['Microservices_UserEmail'] = 'syamsul@mastersystem.co.id';

global $DBHCM;
$mdlname = "HCM";
$DBHCM = get_conn($mdlname);


$query = "SELECT * FROM sa_hcm_requirement WHERE id = 87";
$result = $DBHCM->get_sqlV2($query);

while ($view = mysqli_fetch_assoc($result[1])) {
    $bod = $view['status_gm_bod'];
    $catatan = $view['catatan_gm_bod'];
    $posisi = $view['posisi'];
    // var_dump($bod, $catatan);
    // die;
    $updateResult = $DBHCM->get_res("UPDATE sa_hcm_requirement SET status_gm_bod = 'Approved', catatan_gm_bod = 'notes', status_request = 'Submitted' WHERE id_fpkb = '" . $view['id_fpkb'] . "'");
    $description = $view['gm_bod'] . " Telah memberikan Approved pada data dengan ID FPKB " . $view['id_fpkb'];
    $insert = sprintf(
        "(`id_fpkb`,`project_code`,`project_name`,`description`,`entry_by`) VALUES (%s,%s,%s,%s,%s)",
        GetSQLValueString($view['id_fpkb'], "text"),
        GetSQLValueString($view['project_code'], "text"),
        GetSQLValueString($view['nama_project'], "text"),
        GetSQLValueString($description, "text"),
        GetSQLValueString($view['gm_bod'], "text")
    );
    $res = $DBHCM->insert_data("hcm_requirement_log", $insert);
    $mdlname = "NOTIFICATION";
    $DBNOTIF = get_conn($mdlname);
    $to = "Wahyuwinningdyah Margareta,S.S.PSI<margareta.sekar@mastersystem.co.id>";
    $from = $view['gm_bod'];
    $tblnamenotif = "trx_notification";
    $notifmsg = $view['id_fpkb'] . "; " . $view['project_code'] . "; ";
    $project_name = str_replace(' ', '_', $view['nama_project']); // Mengganti spasi dengan underscore
    $notif_link = "index.php?mod=hcm_requirement&act=edit&id=" . $view['id'] . "&project_code=" . $view['project_code'] . "&project_name=" . $project_name . "&submit=Submit";

    $toArray = explode(',', $to);
    foreach ($toArray as $recipient) {
        $recipient = trim($recipient);
        $insert = sprintf(
            "(`notif_from`, `notif_to`, `notif_subject`, `notif_post`, `notif_link`) VALUES (%s, %s, %s, %s, %s)",
            GetSQLValueString($from, "text"),
            GetSQLValueString($recipient, "text"),
            GetSQLValueString(ucwords("Approved ID FPKB ") . $view['id_fpkb'], "text"),
            GetSQLValueString($notifmsg, "text"),
            GetSQLValueString($notif_link, "text")
        );
        $res = $DBNOTIF->insert_data($tblnamenotif, $insert);
    }
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

    $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
    $msg .= '                <tr><td style="width: 20%;">FPKB Number</td><td>:</td><td>' . htmlspecialchars($view['id_fpkb']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Requester</td><td>:</td><td>' . htmlspecialchars($view['request_by']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Division</td><td>:</td><td>' . htmlspecialchars($view['divisi']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Position</td><td>:</td><td>' . htmlspecialchars($view['posisi']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Job Description</td><td>:</td><td>' . htmlspecialchars($view['deskripsi']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Reason</td><td>:</td><td>' . htmlspecialchars($view['status_rekrutmen']);
    if ($view['status_rekrutmen'] == 'Penggantian') {
        $msg .= ' ' . htmlspecialchars($view['reason_penggantian']);
    }
    $msg .= '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Status Employee</td><td>:</td><td>' . htmlspecialchars($view['status_karyawan']);
    if ($view['status_karyawan'] == 'Kontrak') {
        $msg .= ' ' . htmlspecialchars($view['deskripsi_kontrak']);
    }
    $msg .= '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Source</td><td>:</td><td>' . htmlspecialchars($view['kandidat']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Range Salary</td><td>:</td><td>' . htmlspecialchars($view['range_salary']) . '</td></tr>';
    $msg .= '            </table>';
    $msg .= '            <p>Base On Project:</p>';
    $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
    $msg .= '                <tr><td style="width: 20%;">Project Code</td><td>:</td><td>' . htmlspecialchars($view['project_code']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Project Name</td><td>:</td><td>' . htmlspecialchars($view['nama_project']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Customer</td><td>:</td><td>' . htmlspecialchars($view['nama_customer']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Periode Project</td><td>:</td><td>' . htmlspecialchars($view['periode_project']) . '</td></tr>';
    $msg .= '            </table>';
    $msg .= '            <p>Approval:</p>';
    $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
    $msg .= '                <tr><td style="width: 20%;">By Leader</td><td>:</td><td>' . htmlspecialchars($view['gm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($view['catatan_gm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td>' . htmlspecialchars($view['status_gm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">By GM HCM</td><td>:</td><td>' . htmlspecialchars($view['gm_hcm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($view['catatan_gm_hcm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td>' . htmlspecialchars($view['status_gm_hcm']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">By BOD</td><td>:</td><td>' . htmlspecialchars($view['gm_bod']) . '</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>notes</td></tr>';
    $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td>Approved</td></tr>';
    $msg .= '            </table>';
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
    if ($view['status_gm'] == 'Approve' && $view['status_gm_hcm'] == 'Approve' && $status_bod == 'Approve') {
        echo "udah masuk";
        // email kirim ke bu reta
        // $to = 'Malik Aulia Wiratama <malik.aulia@mastersystem.co.id>';
        $to = 'margareta.sekar@mastersystem.co.id';
        $from = $view['gm_bod'];
        // $cc = "recruitment.team@mastersystem.co.id";
        $cc = 'recruitment.team@mastersystem.co.id';
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
        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
        $msg .= '                <tr><td style="width: 20%;">FPKB Number</td><td>:</td><td>' . htmlspecialchars($view['id_fpkb']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Requester</td><td>:</td><td>' . htmlspecialchars($view['request_by']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Division</td><td>:</td><td>' . htmlspecialchars($view['divisi']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Position</td><td>:</td><td>' . htmlspecialchars($view['posisi']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Job Description</td><td>:</td><td>' . htmlspecialchars($view['deskripsi']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Reason</td><td>:</td><td>' . htmlspecialchars($view['status_rekrutmen']);
        if ($view['status_rekrutmen'] == 'Penggantian') {
            $msg .= ' ' . htmlspecialchars($view['reason_penggantian']);
        }
        $msg .= '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status Employee</td><td>:</td><td>' . htmlspecialchars($view['status_karyawan']);
        if ($view['status_karyawan'] == 'Kontrak') {
            $msg .= ' ' . htmlspecialchars($view['deskripsi_kontrak']);
        }
        $msg .= '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Source</td><td>:</td><td>' . htmlspecialchars($view['kandidat']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Range Salary</td><td>:</td><td>' . htmlspecialchars($view['range_salary']) . '</td></tr>';
        $msg .= '            </table>';
        $msg .= '            <p>Base On Project:</p>';
        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
        $msg .= '                <tr><td style="width: 20%;">Project Code</td><td>:</td><td>' . htmlspecialchars($view['project_code']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Project Name</td><td>:</td><td>' . htmlspecialchars($view['nama_project']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Customer</td><td>:</td><td>' . htmlspecialchars($view['nama_customer']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Periode Project</td><td>:</td><td>' . htmlspecialchars($view['periode_project']) . '</td></tr>';
        $msg .= '            </table>';
        $msg .= '            <p>Approval:</p>';
        $msg .= '            <table width="100%" cellspacing="0" cellpadding="5" border="0" style="border-collapse: collapse;">';
        $msg .= '                <tr><td style="width: 20%;">By Leader</td><td>:</td><td>' . htmlspecialchars($view['gm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($view['catatan_gm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td>' . htmlspecialchars($view['status_gm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">By GM HCM</td><td>:</td><td>' . htmlspecialchars($view['gm_hcm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($view['catatan_gm_hcm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td>' . htmlspecialchars($view['status_gm_hcm']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">By BOD</td><td>:</td><td>' . htmlspecialchars($view['gm_bod']) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Notes</td><td>:</td><td>' . htmlspecialchars($catatan_bod) . '</td></tr>';
        $msg .= '                <tr><td style="width: 20%;">Status</td><td>:</td><td>' . htmlspecialchars($status_bod) . '</td></tr>';
        $msg .= '            </table>';
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
    }
}
