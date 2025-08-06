<?php
// if(isset($_POST['add'])) {
//     $insert = sprintf("(`id`,`id_request`,`divisi`,`posisi`,`kode_project`,`send_datetime`,`jumlah_dibutuhkan`,`status_requirement`,`requirements`,`status_approval`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
//         GetSQLValueString($_POST['id'], "int"),
//         GetSQLValueString($_POST['id_request'], "int"),
//         GetSQLValueString($_POST['divisi'], "text"),
//         GetSQLValueString($_POST['posisi'], "text"),
//         GetSQLValueString($_POST['kode_project'], "text"),
//         GetSQLValueString($_POST['send_datetime'], "date"),
//         GetSQLValueString($_POST['jumlah_dibutuhkan'], "int"),
//         GetSQLValueString($_POST['status_requirement'], "text"),
//         GetSQLValueString($_POST['requirements'], "text"),
//         GetSQLValueString($_POST['status_approval'], "text")
//     );
//     $res = $DBTRXAPP->insert_data($tblname, $insert);
//     $ALERT->savedata();
// } 
// else
if (isset($_POST['save'])) {
    $condition = "id=" . $_POST['id'];
    // $update = sprintf("`id`=%s,`id_request`=%s,`divisi`=%s,`posisi`=%s,`kode_project`=%s,`send_datetime`=%s,`jumlah_dibutuhkan`=%s,`status_requirement`=%s,`requirements`=%s,`status_approval`=%s",
    $update = sprintf(
        " `status_approval`=%s, `assign`=%s",
        // GetSQLValueString($_POST['id'], "int"),
        // GetSQLValueString($_POST['id_request'], "int"),
        // GetSQLValueString($_POST['divisi'], "text"),
        // GetSQLValueString($_POST['posisi'], "text"),
        // GetSQLValueString($_POST['kode_project'], "text"),
        // GetSQLValueString($_POST['send_datetime'], "date"),
        // GetSQLValueString($_POST['jumlah_dibutuhkan'], "int"),
        // GetSQLValueString($_POST['status_requirement'], "text"),
        // GetSQLValueString($_POST['requirements'], "text"),
        GetSQLValueString($_POST['status_approval'], "text"),
        GetSQLValueString($_POST['assign'], "text"),
    );
    $res = $DBAPPROVAL->update_data($tblname, $update, $condition);
    $ALERT->savedata();

    $to = "malik.aulia@mastersystem.co.id";
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
    $msg .= '        <p>Dear,' . $to . '</p>';
    $msg .= '        <p>Berikut ini adalah daftar Request Karyawan yang sudah mendapatkan approval, silahkan dilanjutkan.</p>';
    $msg .= '        <p>';
    $msg .= '        <table width="100%">';
    $msg .= '            <tr>';
    $msg .= '                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold"> ID Request </td>';
    $msg .= '                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold"> Divisi </td>';
    $msg .= '                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold"> Posisi </td>';
    $msg .= '                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold"> Kode Project </td>';
    $msg .= '                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold"> Jumlah Dibutuhkan </td>';
    $msg .= '                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold"> Status Recruitment </td>';
    $msg .= '                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold"> Requirements </td>';
    $msg .= '                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold"> Status Approval </td>';
    $msg .= '                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold"> Assign </td>';
    $msg .= '            </tr>';
    $msg .= '                <tr>';
    $msg .= '                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['id_request'] . '</td>';
    $msg .= '                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['divisi'] . '</td>';
    $msg .= '                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['posisi'] . '</td>';
    $msg .= '                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['kode_project'] . '</td>';
    $msg .= '                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['jumlah_dibutuhkan'] . '</td>';
    $msg .= '                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['status_requirement'] . '</td>';
    $msg .= '                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['requirements'] . '</td>';
    $msg .= '                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['status_approval'] . '</td>';
    $msg .= '                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['assign'] . '</td>';
    $msg .= '            </tr>';
    $msg .= '        </table>';
    $msg .= '        </p>';
    // $msg.='        <p>Silahkan direview untuk kelayakan mendapatkan approval.</p>';
    $msg .= '        <table>';
    $msg .= '        <tr>';
    // $msg.='        <a href="http://192.168.234.157/index.php?mod=new_request&nr_stat=Pending">Submit</a>';
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
