<?php
$mdlname = "new_request";
$DBREC   = get_conn($mdlname);
if (isset($_POST['add'])) {

    $insert = sprintf(
        "(`id`,`divisi`,`posisi`,`jumlah_dibutuhkan`,`tanggal_dibutuhkan`,`deskripsi`,`nama_project`,`nama_customer`,`project_code`,`status_rekrutmen`,`status_karyawan`,`mpp`,
        `jenis_kelamin`,`usia`,`pendidikan_minimal`,`jurusan`,`pengalaman_minimal`,`kompetensi_teknis`,`kompetensi_non_teknis`,`kandidat`,`internal`,`catatan`,`diisi_oleh_hcm`,`range_salary`,`request_by`,`gm`,`status_gm`,`gm_hcm`,`status_gm_hcm`,`gm_bod`,`status_gm_bod`,`status_request`) 
        VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
        GetSQLValueString($_POST['id'], "int"),
        GetSQLValueString($_POST['divisi'], "text"),
        GetSQLValueString($_POST['posisi'], "text"),
        GetSQLValueString($_POST['jumlah_dibutuhkan'], "text"),
        GetSQLValueString($_POST['tanggal_dibutuhkan'], "date"),
        GetSQLValueString($_POST['deskripsi'], "text"),
        GetSQLValueString($_POST['nama_project'], "text"),
        GetSQLValueString($_POST['nama_customer'], "text"),
        GetSQLValueString($_POST['project_code'], "text"),
        GetSQLValueString($_POST['status_rekrutmen'], "text"),
        GetSQLValueString($_POST['status_karyawan'], "text"),
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
        GetSQLValueString($_POST['diisi_oleh_hcm'], "text"),
        GetSQLValueString($_POST['range_salary'], "text"),
        GetSQLValueString($_SESSION['Microservices_UserEmail'], "text"),
        GetSQLValueString($_POST['gm'], "text"),
        GetSQLValueString($_POST['status_gm'], "text"),
        GetSQLValueString($_POST['gm_hcm'], "text"),
        GetSQLValueString($_POST['status_gm_hcm'], "text"),
        GetSQLValueString($_POST['gm_bod'], "text"),
        GetSQLValueString($_POST['status_gm_bod'], "text"),
        GetSQLValueString("Pending Approval", "text")

    );
    $res = $DBRec->insert_data($tblname, $insert);
    $ALERT->savedata();

    // Add auto kirim email GM & GM BOD & GM HCM
    $to = "malik.aulia@mastersystem.co.id";
    //$to = $_POST['gm'] . "," . $_POST['gm_hcm'] . "," . $_POST['gm_bod'];
    $to2 = $_POST['gm_hcm'];
    $to3 = $_POST['gm_bod'];
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
    $msg .= '        <p>Dear, All</p>';
    $msg .= '        <p>Berikut ini adalah daftar Request Karyawan yang menunggu approval dari Anda.</p>';
    $msg .= '        <p>';
    $msg .= '<table width="80%">';
    $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi'] . '</td></tr>';
    $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
    $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $_POST['project_code'] . '</td></tr>';
    $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $_POST['status_rekrutmen'] . '</td></tr>';
    $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $_POST['status_karyawan'] . '</td></tr>';
    $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $_POST['kandidat'] . '</td></tr>';
    $msg .= '<tr><td>Status</td><td>:</td><td>' . "Pending Approval" . '</td></tr>';
    $msg .= '</table>';
    $msg .= '</p>';
    $msg .= '<p>Silahkan direview untuk kelayakan mendapatkan approval.</p>';
    $msg .= '        <table>';
    $msg .= '        <tr>';
    // $msg .= '        <a href="http://localhost/microservices/index.php?mod=request&nr_stat=Pending">Submit</a>';
    $msg .= '        </table>';
    $msg .= '        <p>Terimakasih</p>';
    $msg .= '    </td>';
    $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
    $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
    $msg .= "</table>";
    //echo $msg;
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
} elseif (isset($_POST['save'])) {
    $condition = "id=" . $_POST['id'];
    $update = sprintf(
        "`id`=%s,`divisi`=%s,`posisi`=%s,`jumlah_dibutuhkan`=%s,`tanggal_dibutuhkan`=%s,`deskripsi`=%s,`nama_project`=%s,`nama_customer`=%s,`project_code`=%s,`status_rekrutmen`=%s,`status_karyawan`=%s,`mpp`=%s,`jenis_kelamin`=%s,`usia`=%s,`pendidikan_minimal`=%s,`jurusan`=%s,`pengalaman_minimal`=%s,`kompetensi_teknis`=%s,`kompetensi_non_teknis`=%s,`kandidat`=%s,`internal`=%s,`catatan`=%s,`diisi_oleh_hcm`=%s,`range_salary`=%s,`request_by`=%s,`gm`=%s,`status_gm`=%s,`catatan_gm`=%s,`gm_hcm`=%s,`status_gm_hcm`=%s,`catatan_gm_hcm`=%s,`gm_bod`=%s,`status_gm_bod`=%s,`catatan_gm_bod`=%s,`status_request`=%s,`flag`=%s",
        GetSQLValueString($_POST['id'], "int"),
        GetSQLValueString($_POST['divisi'], "text"),
        GetSQLValueString($_POST['posisi'], "text"),
        GetSQLValueString($_POST['jumlah_dibutuhkan'], "int"),
        GetSQLValueString($_POST['tanggal_dibutuhkan'], "date"),
        GetSQLValueString($_POST['deskripsi'], "text"),
        GetSQLValueString($_POST['nama_project'], "text"),
        GetSQLValueString($_POST['nama_customer'], "text"),
        GetSQLValueString($_POST['project_code'], "text"),
        GetSQLValueString($_POST['status_rekrutmen'], "text"),
        GetSQLValueString($_POST['status_karyawan'], "text"),
        GetSQLValueString($_POST['mpp'], "text"),
        GetSQLValueString($_POST['jenis_kelamin'], "text"),
        GetSQLValueString($_POST['usia'], "int"),
        GetSQLValueString($_POST['pendidikan_minimal'], "text"),
        GetSQLValueString($_POST['jurusan'], "text"),
        GetSQLValueString($_POST['pengalaman_minimal'], "text"),
        GetSQLValueString($_POST['kompetensi_teknis'], "text"),
        GetSQLValueString($_POST['kompetensi_non_teknis'], "text"),
        GetSQLValueString($_POST['kandidat'], "text"),
        GetSQLValueString($_POST['internal'], "text"),
        GetSQLValueString($_POST['catatan'], "text"),
        GetSQLValueString($_POST['diisi_oleh_hcm'], "text"),
        GetSQLValueString($_POST['range_salary'], "int"),
        GetSQLValueString($_POST['request_by'], "text"),
        GetSQLValueString($_POST['gm'], "text"),
        GetSQLValueString($_POST['status_gm'], "text"),
        GetSQLValueString($_POST['catatan_gm'], "text"),
        GetSQLValueString($_POST['gm_hcm'], "text"),
        GetSQLValueString($_POST['status_gm_hcm'], "text"),
        GetSQLValueString($_POST['catatan_gm_hcm'], "text"),
        GetSQLValueString($_POST['gm_bod'], "text"),
        GetSQLValueString($_POST['status_gm_bod'], "text"),
        GetSQLValueString($_POST['catatan_gm_bod'], "text"),
        GetSQLValueString($_POST['status_request'], "text"),
        GetSQLValueString($_POST['flag'], "int")
    );
    $res = $DBRec->update_data($tblname, $update, $condition);
    $ALERT->savedata();
}
