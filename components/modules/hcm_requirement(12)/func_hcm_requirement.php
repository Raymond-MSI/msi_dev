<?php
if (isset($_POST['add'])) {
    $insert = sprintf(
        "(`divisi`,`posisi`,`jumlah_dibutuhkan`,`tanggal_dibutuhkan`,`deskripsi`,`nama_project`,`nama_customer`,`project_code`,`status_rekrutmen`,`status_karyawan`,`mpp`,`jenis_kelamin`,`usia`,`pendidikan_minimal`,`jurusan`,`pengalaman_minimal`,`kompetensi_teknis`,`kompetensi_non_teknis`,`kandidat`,`internal`,`catatan`,`diisi_oleh_hcm`,`range_salary`,`request_by`,`gm`,`status_gm`,`gm_hcm`,`status_gm_hcm`,`gm_bod`,`status_gm_bod`,`status_request`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",

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
        GetSQLValueString($_POST['range_salary'], "int"),
        GetSQLValueString($_SESSION['Microservices_UserEmail'], "text"),
        GetSQLValueString($_POST['gm'], "text"),
        GetSQLValueString($_POST['status_gm'], "text"),
        GetSQLValueString($_POST['gm_hcm'], "text"),
        GetSQLValueString($_POST['status_gm_hcm'], "text"),
        GetSQLValueString($_POST['gm_bod'], "text"),
        GetSQLValueString($_POST['status_gm_bod'], "text"),
        GetSQLValueString("Pending Approval", "text"),
    );
    $res = $DBHCM->insert_data($tblname, $insert);
    if ($_POST['status_rekrutmen'] == 'Penambahan') {

        // Add auto kirim email GM & GM BOD & GM HCM
        // $to = "malik.aulia@mastersystem.co.id";
        $to = $_POST['gm'] . "," . $_POST['gm_hcm'] . "," . $_POST['gm_bod'];
        // $to2 = $_POST['gm_hcm'];
        // $to3 = $_POST['gm_bod'];
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
    } elseif ($_POST['status_rekrutmen'] == "Penggantian") {
        $to = $_POST['gm'] . "," . $_POST['gm_hcm'];
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
    $ALERT->savedata();
} elseif (isset($_POST['save'])) {

    $data = $DBHCM->get_sqlV2("SELECT * FROM sa_hcm_requirement WHERE id=" . $_POST['id'] . "");
    if (!isset($_POST['status_gm_hcm'])) {
        $statusgmhcm = null;
    } else {
        $statusgmhcm = $_POST['status_gm_hcm'];
    }


    if ($_POST['status_rekrutmen'] == 'Internship' || $_POST['status_rekrutmen'] == 'Penggantian') {
        if ($_POST['gm'] == $_SESSION['Microservices_UserEmail']) {
            if ($_POST['status_gm'] == "Disapprove" || $_POST['status_gm_hcm'] == "Disapprove") {
                $status_request = 'Inactive';
            } else {
                $status_request = 'Pending Approval';
            }
            $condition = "id=" . $_POST['id'];
            $update = sprintf(
                "`gm`=%s, `status_gm`=%s,`catatan_gm`=%s, `status_request`=%s",
                GetSQLValueString($_POST['gm'], "text"),
                GetSQLValueString($_POST['status_gm'], "text"),
                GetSQLValueString($_POST['catatan_gm'], "text"),
                GetSQLValueString($status_request, "text")

            );
            $res = $DBHCM->update_data($tblname, $update, $condition);
            $to = $_POST['gm_hcm'];
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
        if ($_POST['gm_hcm'] == $_SESSION['Microservices_UserEmail']) {
            if ($_POST['status_gm'] == "Disapprove" || $_POST['status_gm_hcm'] == "Disapprove") {
                $status_request = 'Inactive';
            } else {
                $status_request = 'Pending Approval';
            }
            $condition = "id=" . $_POST['id'];
            $update = sprintf(
                "`gm_hcm`=%s, `status_gm_hcm`=%s,`catatan_gm_hcm`=%s, `status_request`=%s",
                //GetSQLValueString($_POST['gm'], "text"),
                GetSQLValueString($_POST['gm_hcm'], "text"),
                GetSQLValueString($_POST['status_gm_hcm'], "text"),
                GetSQLValueString($_POST['catatan_gm_hcm'], "text"),
                GetSQLValueString($status_request, "text")

            );
            $res = $DBHCM->update_data($tblname, $update, $condition);
            // echo "gm_hcm";
            $to = $data[0]['request_by'];
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
        if ($_POST['status_gm'] == 'Approve' && $_POST['status_gm_hcm'] == 'Approve') {
            $submitted = 'Submitted';
            $condition = "id=" . $_POST['id'];
            $update = sprintf(
                "`status_request`=%s",
                GetSQLValueString($submitted, "text")

            );
            $res = $DBHCM->update_data($tblname, $update, $condition);
        }
    } elseif ($_POST['status_rekrutmen'] == 'Penambahan') {
        if ($_POST['gm'] == $_SESSION['Microservices_UserEmail']) {
            if ($_POST['status_gm'] == "Disapprove" || $_POST['status_gm_hcm'] == "Disapprove") {
                $status_request = 'Inactive';
            } else {
                $status_request = 'Pending Approval';
            }
            $condition = "id=" . $_POST['id'];
            $update = sprintf(
                "`gm`=%s, `status_gm`=%s,`catatan_gm`=%s, `status_request`=%s",
                GetSQLValueString($_POST['gm'], "text"),
                GetSQLValueString($_POST['status_gm'], "text"),
                GetSQLValueString($_POST['catatan_gm'], "text"),
                GetSQLValueString($status_request, "text")

            );
            $res = $DBHCM->update_data($tblname, $update, $condition);
            $to = $_POST['gm_hcm'];
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
        if ($_POST['gm_hcm'] == $_SESSION['Microservices_UserEmail']) {
            if ($_POST['status_gm_hcm'] == "Disapprove") {
                $status_request = 'Inactive';
            } else {
                $status_request = 'Pending Approval';
            }
            $condition = "id=" . $_POST['id'];
            $update = sprintf(
                "`gm_hcm`=%s, `status_gm_hcm`=%s,`catatan_gm_hcm`=%s, `status_request`=%s",
                //GetSQLValueString($_POST['gm'], "text"),
                GetSQLValueString($_POST['gm_hcm'], "text"),
                GetSQLValueString($_POST['status_gm_hcm'], "text"),
                GetSQLValueString($_POST['catatan_gm_hcm'], "text"),
                GetSQLValueString($status_request, "text")

            );
            $res = $DBHCM->update_data($tblname, $update, $condition);
            // echo "gm_hcm";
            $to = $data[0]['request_by'];
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
        if ($_POST['gm_bod'] == $_SESSION['Microservices_UserEmail']) {
            if ($_POST['status_gm_bod'] == "Disapprove") {
                $status_request = 'Inactive';
            } else {
                $status_request = 'Pending Approval';
            }
            $condition = "id=" . $_POST['id'];
            $update = sprintf(
                "`gm_bod`=%s, `status_gm_bod`=%s,`catatan_gm_bod`=%s, `status_request`=%s",
                GetSQLValueString($_POST['gm_bod'], "text"),
                GetSQLValueString($_POST['status_gm_bod'], "text"),
                GetSQLValueString($_POST['catatan_gm_bod'], "text"),
                GetSQLValueString($status_request, "text")

            );
            // if ($_POST['gm_bod'] == 'Approve')
            $res = $DBHCM->update_data($tblname, $update, $condition);
            // echo "gm_bod";
            $to = $data[0]['request_by'];
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
        if ($_POST['status_rekrutmen'] == 'Penambahan' && $_POST['status_gm'] == 'Approve' && $_POST['status_gm_hcm'] == 'Approve' && $_POST['status_gm_bod'] == 'Approve') {
            $submitted = 'Submitted';
            $condition = "id=" . $_POST['id'];
            $update = sprintf(
                "`status_request`=%s",
                GetSQLValueString($submitted, "text")

            );
            $res = $DBHCM->update_data($tblname, $update, $condition);
            $to = $data[0]['request_by'];
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
            $msg .= '        <p>Berikut ini adalah daftar Request Karyawan yang telah mendapatkan Approval.</p>';
            $msg .= '        <p>';
            $msg .= '<table width="80%">';
            $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi'] . '</td></tr>';
            $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
            $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $_POST['project_code'] . '</td></tr>';
            $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $_POST['status_rekrutmen'] . '</td></tr>';
            $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $_POST['status_karyawan'] . '</td></tr>';
            $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $_POST['kandidat'] . '</td></tr>';
            $msg .= '<tr><td>Status</td><td>:</td><td>' . $data[0]['status_request'] . '</td></tr>';
            $msg .= '</table>';
            $msg .= '</p>';
            $msg .= '<p>Silahkan diproses untuk masuk step selanjutnya.</p>';
            $msg .= '        <table>';
            $msg .= '        <tr>';
            // $msg .= '        <a href="http://localhost/microservices/index.php?mod=request&nr_stat=Pending">Submit</a>';
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
        } elseif ($_POST['status_rekrutmen'] == 'Penggantian' && $_POST['status_gm'] == 'Approve' && $_POST['status_gm_hcm'] == 'Approve') {
            $submitted = 'Submitted';
            $condition = "id=" . $_POST['id'];
            $update = sprintf(
                "`status_request`=%s",
                GetSQLValueString($submitted, "text")

            );
            $res = $DBHCM->update_data($tblname, $update, $condition);
            $to = $data[0]['request_by'];
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
            $msg .= '        <p>Berikut ini adalah daftar Request Karyawan yang menunggu approval dari Anda.</p>';
            $msg .= '        <p>';
            $msg .= '<table width="80%">';
            $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi'] . '</td></tr>';
            $msg .= '<tr><td>Posisi</td><td>:</td><td>' . $_POST['posisi'] . '</td></tr>';
            $msg .= '<tr><td>Kode Project</td><td>:</td><td>' . $_POST['project_code'] . '</td></tr>';
            $msg .= '<tr><td>Status Requirement</td><td>:</td><td>' . $_POST['status_rekrutmen'] . '</td></tr>';
            $msg .= '<tr><td>Status Karyawan</td><td>:</td><td>' . $_POST['status_karyawan'] . '</td></tr>';
            $msg .= '<tr><td>Kandidat</td><td>:</td><td>' . $_POST['kandidat'] . '</td></tr>';
            $msg .= '<tr><td>Status</td><td>:</td><td>' . $data[0]['status_request'] . '</td></tr>';
            $msg .= '</table>';
            $msg .= '</p>';
            $msg .= '<p>Silahkan diproses untuk masuk step selanjutnya.</p>';
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
    $ALERT->savedata();
} elseif (isset($_POST['editapproval'])) {
    $condition = "id=" . $_POST['id'];
    $update = sprintf(
        "`assign_requirement`=%s",
        GetSQLValueString($_POST['assign_requirement'], "text")
    );
    $res = $DBHCM->update_data($tblname, $update, $condition);
    // $id_request = $_POST['id'];
    // $update_request = sprintf("status = 'Complete' ");
    // $condition_request = "id =" .  $id_request;
    // $insert = sprintf(
    //     "(`divisi`, `posisi`,`project_code`,`kandidat`,`jumlah_dibutuhkan`,`share`) 
    // VALUES (%s, %s, %s, %s, %s, %s)",
    //     GetSQLValueString($_POST['divisi'], "text"),
    //     GetSQLValueString($_POST['posisi'], "text"),
    //     GetSQLValueString($_POST['project_code'], "text"),
    //     GetSQLValueString($_POST['kandidat'], "text"),
    //     GetSQLValueString($_POST['jumlah_dibutuhkan'], "text"),
    //     GetSQLValueString("CV Kandidat", "text"),
    // );
    // $res = $DBREC->insert_data('trx_share', $insert);
    // $uprequest = $DBREC->update_data("trx_approval", $update_request, $condition_request);
    $to = $_POST['assign'];
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
    $msg .= '                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold"> Divisi </td>';
    $msg .= '                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold"> Posisi </td>';
    $msg .= '                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold"> Kode Project </td>';
    $msg .= '                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold"> Kandidat </td>';
    $msg .= '                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold"> Jumlah Dibutuhkan </td>';
    $msg .= '                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold"> Status Recruitment </td>';
    $msg .= '                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold"> Kompetensi Teknis </td>';
    $msg .= '                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold"> Status Approval </td>';
    $msg .= '                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold"> Assign </td>';
    $msg .= '            </tr>';
    $msg .= '                <tr>';
    $msg .= '                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['divisi'] . '</td>';
    $msg .= '                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['posisi'] . '</td>';
    $msg .= '                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['project_code'] . '</td>';
    $msg .= '                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['kandidat'] . '</td>';
    $msg .= '                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['jumlah_dibutuhkan'] . '</td>';
    $msg .= '                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['status_requirement'] . '</td>';
    $msg .= '                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['kompetensi_teknis'] . '</td>';
    $msg .= '                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['status_approval'] . '</td>';
    $msg .= '                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['assign'] . '</td>';
    $msg .= '            </tr>';
    $msg .= '        </table>';
    $msg .= '        </p>';
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
    $ALERT->savedata();
} elseif (isset($_POST['editshare'])) {
    for ($i = 0; $i < count($_POST['malik']); $i++) {
        $kp = $_POST['project_code'];
        $nama = $_POST['malik'][$i];
        $catatan = $_POST['notes'][$i];
        if (empty($catatan)) {
            '';
        } else {
            $res = $DBHCM->get_res("INSERT INTO sa_hcm_notecv (project_code,file,notes) VALUES ('$kp','$nama','$catatan')");
        }
    }
}
