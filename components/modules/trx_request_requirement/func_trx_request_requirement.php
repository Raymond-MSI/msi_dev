<?php
$mdlname = "new_request";
$DBNEWREQUEST   = get_conn($mdlname);
// func bagian ADD
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
    $res = $DBNEWREQUEST->insert_data($tblname, $insert);
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
    $msg .= '<tr><td>Divisi</td><td>=</td><td>' . $_POST['divisi'] . '</td></tr>';
    $msg .= '<tr><td>Posisi</td><td>=</td><td>' . $_POST['posisi'] . '</td></tr>';
    $msg .= '<tr><td>Kode Project</td><td>=</td><td>' . $_POST['project_code'] . '</td></tr>';
    $msg .= '<tr><td>Status Requirement</td><td>=</td><td>' . $_POST['status_rekrutmen'] . '</td></tr>';
    $msg .= '<tr><td>Status Karyawan</td><td>=</td><td>' . $_POST['status_karyawan'] . '</td></tr>';
    $msg .= '<tr><td>Kandidat</td><td>=</td><td>' . $_POST['kandidat'] . '</td></tr>';
    $msg .= '<tr><td>Status</td><td>=</td><td>' . "Pending Approval" . '</td></tr>';
    $msg .= '</table>';
    $msg .= '</p>';
    $msg .= '<p>Silahkan direview untuk kelayakan mendapatkan approval.</p>';
    $msg .= '        <table>';
    $msg .= '        <tr>';
    $msg .= '        <a href="http://localhost/microservices/index.php?mod=request&nr_stat=Pending">Submit</a>';
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
} elseif (isset($_POST['edit'])) {
    // jika GM Approve kirim email ke GM (HCM)
    $mdlname = "new_request";
    $DBNEWREQUEST   = get_conn($mdlname);
    $data = $DBNEWREQUEST->get_sqlV2("SELECT * FROM sa_trx_request_requirement WHERE id=" . $_POST['id'] . "");


    if (!isset($_POST['status_gm_hcm'])) {
        $statusgmhcm = null;
    } else {
        $statusgmhcm = $_POST['status_gm_hcm'];
    }



    if ($data[0]['gm_bod'] == $_SESSION['Microservices_UserEmail'] || $data[0]['gm_hcm'] == $_SESSION['Microservices_UserEmail'] || $data[0]['gm'] == $_SESSION['Microservices_UserEmail']) {
        if ($_POST['status_gm'] == "Disapprove" || $_POST['status_gm_hcm'] == "Disapprove") {
            $status_request = 'Inactive';
        } else {
            $status_request = 'Pending Approval';
        }

        $condition = "id=" . $_POST['id'];
        $update = sprintf(
            "`gm`=%s, `status_gm`=%s, `catatan_gm`=%s,`gm_hcm`=%s, `status_gm_hcm`=%s, `catatan_gm_hcm`=%s,`status_request`=%s",
            GetSQLValueString($_POST['gm'], "text"),
            GetSQLValueString($_POST['status_gm'], "text"),
            GetSQLValueString($_POST['catatan_gm'], "text"),
            GetSQLValueString($_POST['gm_hcm'], "text"),
            GetSQLValueString($_POST['status_gm_hcm'], "text"),
            GetSQLValueString($_POST['catatan_gm_hcm'], "text"),
            GetSQLValueString($status_request, "text")

        );
        $res = $DBNEWREQUEST->update_data($tblname, $update, $condition);
        // var_dump($status_request, $condition, $res);
        // die;
        $ALERT->savedata();
    }

    // func Approve (GM)
    if ($data[0]['gm'] == $_SESSION['Microservices_UserEmail']) {
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
        $res = $DBNEWREQUEST->update_data($tblname, $update, $condition);

        // BAGIAN APPROVAL GM(HCM)
    }
    if ($data[0]['gm_hcm'] == $_SESSION['Microservices_UserEmail']) {
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
        $res = $DBNEWREQUEST->update_data($tblname, $update, $condition);
    }

    // EDIT ( GM BOD )


    elseif ($data[0]['gm_bod'] == $_SESSION['Microservices_UserEmail']) {
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
        $res = $DBNEWREQUEST->update_data($tblname, $update, $condition);
        // var_dump($status_request, $condition, $res);
        // die;
    }



    //////////////////// (editapproval) TABLES TRX_APPROVAL ////////////////////
} elseif (isset($_POST['editapproval'])) {
    $condition = "id=" . $_POST['id'];
    $update = sprintf(
        "`id`=%s,`divisi`=%s,`posisi`=%s,`project_code`=%s,`kandidat`=%s,`assign`=%s",
        GetSQLValueString($_POST['id'], "int"),
        GetSQLValueString($_POST['divisi'], "text"),
        GetSQLValueString($_POST['posisi'], "text"),
        GetSQLValueString($_POST['project_code'], "text"),
        GetSQLValueString($_POST['kandidat'], "text"),
        GetSQLValueString($_POST['assign'], "text")
    );
    $res = $DBNEWREQUEST->update_data($tblname2, $update, $condition);
    $id_request = $_POST['id'];
    $update_request = sprintf("status = 'Complete' ");
    $condition_request = "id =" .  $id_request;

    $insert = sprintf(
        "(`divisi`, `posisi`,`project_code`,`kandidat`,`jumlah_dibutuhkan`,`share`) 
    VALUES (%s, %s, %s, %s, %s, %s)",
        GetSQLValueString($_POST['divisi'], "text"),
        GetSQLValueString($_POST['posisi'], "text"),
        GetSQLValueString($_POST['project_code'], "text"),
        GetSQLValueString($_POST['kandidat'], "text"),
        GetSQLValueString($_POST['jumlah_dibutuhkan'], "text"),
        GetSQLValueString("CV Kandidat", "text"),
    );
    $res = $DBNEWREQUEST->insert_data('trx_share', $insert);

    $uprequest = $DBNEWREQUEST->update_data("trx_approval", $update_request, $condition_request);
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
} elseif (isset($_POST['assignbaru'])) {
    $insert = sprintf(
        "(`assign`) VALUES (%s)",
        GetSQLValueString($_POST['assign'], "text")
    );
    $res = $DBNEWREQUEST->insert_data("assign", $insert);
} elseif (isset($_POST['editshare'])) {
    $condition = "id=" . $_POST['id'];
    $update = sprintf(
        "`upload_by` = %s",
        GetSQLValueString($_SESSION['Microservices_UserEmail'], "text"),
        GetSQLValueString(date("Y-m-d G:i:s"), "date")
    );
    $res = $DBNEWREQUEST->update_data($tblname3, $update, $condition);
    $ALERT->savedata();
} elseif (isset($_POST['cobaan'])) {
    $insert = sprintf(
        "(`link_from`) VALUES (%s)",
        GetSQLValueString($_POST['link_from'], "text")
    );
    $res = $DBNEWREQUEST->insert_data("link", $insert);
} elseif (isset($_POST['complete'])) {
    $condition = "id=" . $_POST['id'];
    $update = sprintf(
        "`status_share`=%s",
        GetSQLValueString("Complete", "text")
    );
    $res = $DBNEWREQUEST->update_data($tblname3, $update, $condition);
    $insert = sprintf(
        "(`divisi`, `posisi`, `kandidat`, `project_code`,`jumlah_dibutuhkan`,`share`)
        VALUES (%s,%s,%s,%s,%s,%s)",
        GetSQLValueString($_POST['divisi'], "text"),
        GetSQLValueString($_POST['posisi'], "text"),
        GetSQLValueString($_POST['kandidat'], "text"),
        GetSQLValueString($_POST['project_code'], "text"),
        GetSQLValueString($_POST['jumlah_dibutuhkan'], "int"),
        GetSQLValueString($_POST['share'], "text")
    );
    $res = $DBNEWREQUEST->insert_data($tblname4, $insert);
    $ALERT->savedata();
} elseif (isset($_POST['editinterview'])) {
    $condition = "interview_id=" . $_POST['interview_id'];
    $update = sprintf(
        "`divisi`=%s,`posisi`=%s,`kandidat`=%s,`project_code`=%s,`jumlah_dibutuhkan`=%s,`share`=%s",
        GetSQLValueString($_POST['divisi'], "text"),
        GetSQLValueString($_POST['posisi'], "text"),
        GetSQLValueString($_POST['kandidat'], "text"),
        GetSQLValueString($_POST['project_code'], "text"),
        GetSQLValueString($_POST['jumlah_dibutuhkan'], "text"),
        GetSQLValueString($_POST['share'], "text")
    );
    $res = $DBNEWREQUEST->update_data($tblname4, $update, $condition);
    $ALERT->savedata();


    $dataemail = $DBNEWREQUEST->get_data("email", "project_code='" . $_POST['project_code'] . "'");
    if (isset($_POST['email_update'])) {
        $combine_con = array();
        if ($_POST)
            for ($i = 0; $i < count($_POST['email_update']); $i++) {
                $condition_con = "email_id=" . $_POST['email_id'][$i] . "";
                $combine_con[] = array($_POST['catatan_update'][$i]);
                foreach ($combine_con as $value) {
                    $update = sprintf(
                        "`catatan` = %s",
                        GetSQLValueString($value[0], "text")
                    );
                    $DBNEWREQUEST->update_data($tblname6, $update, $condition_con);
                }
            }
        $condition = "interview_id=" . $_POST['interview_id'];
        $update = sprintf(
            "`status_interview`=%s",
            GetSQLValueString("Selesai", "text")
        );
        $res = $DBNEWREQUEST->update_data($tblname4, $update, $condition);
        $insert = sprintf(
            "(`divisi`, `posisi`, `kandidat`, `project_code`,`share`,`jumlah_dibutuhkan`)
        VALUES (%s,%s,%s,%s,%s,%s)",
            GetSQLValueString($_POST['divisi'], "text"),
            GetSQLValueString($_POST['posisi'], "text"),
            GetSQLValueString($_POST['kandidat'], "text"),
            GetSQLValueString($_POST['project_code'], "text"),
            GetSQLValueString($_POST['share'], "text"),
            GetSQLValueString($_POST['jumlah_dibutuhkan'], "int")
        );
        $res = $DBNEWREQUEST->insert_data($tblname5, $insert);
    }
    if (isset($_POST['email'])) {
        $combine_arr = array();
        for ($i = 0; $i < count($_POST['email']); $i++) {
            $combine_arr[] = array($_POST['email'][$i], $_POST['tanggal_interview'][$i], $_POST['link_webex'][$i], $_POST['pic'][$i], $_POST['interview_user'][$i], $_POST['interview_hcm'][$i]);
        }
        foreach ($combine_arr as $value) {
            $insert_sql = sprintf(
                "(`project_code`,`email`,`tanggal_interview`,`link_webex`,`pic`,`interview_user`,`interview_hcm`) VALUES (%s,%s,%s,%s,%s,%s,%s)",
                GetSQLValueString($_POST['project_code'], "text"),
                GetSQLValueString($value[0], "text"),
                GetSQLValueString($value[1], "date"),
                GetSQLValueString($value[2], "text"),
                GetSQLValueString($value[3], "text"),
                GetSQLValueString($value[4], "text"),
                GetSQLValueString($value[5], "text")
            );
            $cobaan = $DBNEWREQUEST->insert_data($tblname6, $insert_sql);
            $mdlname = "new_request";
            $joindate = get_conn($mdlname);
            $tgl_int = $value[1];
            $days = array(
                'Sunday' => 'Minggu',
                'Monday' => 'Senin',
                'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu',
                'Thursday' => 'Kamis',
                'Friday' => 'Jumat',
                'Saturday' => 'Sabtu'
            );

            $cobatgl = str_replace(array_keys($days), array_values($days), date('l , d F Y', strtotime($tgl_int)));
            $datemin2 = str_replace(array_keys($days), array_values($days), date('l , d F Y', strtotime($tgl_int . '- 2 days')));

            $to = str_replace(";", ",", $value[0]);
            $link_webex = $value[2];
            $pic = $value[3];
            $from = "MSIZone<msizone@mastersystem.co.id>";
            $cc = $from;
            $bcc = "";
            $reply = $from;
            $subject = "[TESTING] Request Recruitment";
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
            $msg .= '<p>Dear, Kandidat</p>';
            $msg .= '<table style="width: 100%">';
            $msg .= '<tr>';
            $msg .= '<td></td>';
            $msg .= '</tr>';
            $msg .= '<tr>';
            $msg .= '<td>Terkait dengan CV yang kami terima, kami PT. Mastersystem Infotama bermaksud mengundang anda Interview sebagai Developer, </td>';
            $msg .= '</tr>';
            $msg .= '<td>berikut adalah link Cisco Webex. Jika menggunakan laptop bisa menggunakan browser atau jika memakai android/ios bisa download aplikasi cisco webex: ';
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
            $msg .= '<td>=</td>';
            $msg .= '<td align="left">' . $cobatgl . ' WIB</td>';
            $msg .= '</tr>';
            $msg .= '<tr>';
            $msg .= '<td>Waktu</td>';
            $msg .= '<td>=</td>';
            $msg .= '<td align="left"> 10.30 WIB </td>';
            $msg .= '</tr>';
            $msg .= '<tr>';
            $msg .= '<td>Link Webex</td>';
            $msg .= '<td>=</td>';
            $msg .= '<td align="left">' . $link_webex . '</td>';
            $msg .= '</tr>';
            $msg .= '<tr>';
            $msg .= '<td>PIC</td>';
            $msg .= '<td>=</td>';
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
            var_dump($cobaan);

            $condition = "interview_id=" . $_POST['interview_id'];
            $update = sprintf(
                "`status_interview`=%s",
                GetSQLValueString("Submit", "text")
            );
            $res = $DBNEWREQUEST->update_data($tblname4, $update, $condition);
        }
    }
} elseif (isset($_POST['editoffering'])) {

    $condition = "id_offering=" . $_POST['id_offering'];
    $update = sprintf(
        "`catatan_offering`=%s",
        GetSQLValueString($_POST['catatan_offering'], "text")
    );
    $res = $DBNEWREQUEST->update_data($tblname5, $update, $condition);

    $mdlname = "new_request";
    $DBNEWREQUEST   = get_conn($mdlname);
    $data = $DBNEWREQUEST->get_sqlV2("SELECT * FROM sa_email WHERE project_code=" . $_POST['project_code'] . "");
    $data1 = $DBNEWREQUEST->get_sqlV2("SELECT * FROM sa_offering WHERE project_code=" . $_POST['project_code'] . "");

    if ($data1[0]['manager_hcm'] == $_SESSION['Microservices_UserEmail'] || $data1[0]['hcm_gm'] == $_SESSION['Microservices_UserEmail'] || $data1[0]['manager_gm_bod'] == $_SESSION['Microservices_UserEmail']) {
        if ($data1[0]['manager_hcm'] == $_SESSION['Microservices_UserEmail']) {
            if ($_POST['status_manager_hcm'] == "Disapprove") {
                $condition = "id_offering=" . $_POST['id_offering'];
                $update = sprintf(
                    "`status_manager_hcm`=%s,`status_hcm_gm`=%s,`status_manager_gm_bod`=%s",
                    GetSQLValueString("Pending", "text"),
                    GetSQLValueString("Pending", "text"),
                    GetSQLValueString("Pending", "text")
                );
                $res = $DBNEWREQUEST->update_data($tblname5, $update, $condition);
            } else {
                $condition = "id_offering=" . $_POST['id_offering'];
                $update = sprintf(
                    "`status_manager_hcm`=%s",
                    GetSQLValueString($_POST['status_manager_hcm'], "text")
                );
                $res = $DBNEWREQUEST->update_data($tblname5, $update, $condition);
                $to = "malik.aulia@mastersystem.co.id";
                $from = "MSIZone<msizone@mastersystem.co.id>";
                $cc = $from;
                $bcc = "";
                $reply = $from;
                $subject = "[TESTING]";
                $msg = '<table width="100%">';
                $msg .= ' <tr><td width="20%" rowspan="4"></td>';
                $msg .= ' <td style="width:60%; padding:20px; border:thin solid #dadada">';
                $msg .= ' <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
                $msg .= '<td width="20%" rowspan="4"></tr>';
                $msg .= ' <tr><td style="padding:20px; border:thin solid #dadada">';
                $msg .= ' <p>Dear, All</p>';
                $msg .= ' <p>Dengan ini saya memberikan Approval</p>';
                $msg .= ' <p>';
                $msg .= '<table width="60%">';
                $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi'] . '</td></tr>';
                $msg .= '<tr><td>Posisi</td><td align="center">:</td><td>' . $_POST['posisi'] . '</td></tr>';
                $msg .= '<tr><td>Project Code</td><td align="center">:</td><td>' . $_POST['project_code'] . '</td></tr>';
                $msg .= '<tr><td>Kandidat</td><td align="center">:</td><td>' . $_POST['kandidat'] . '</td></tr>';
                $msg .= '<tr><td>Jumlah Dibutuhkan</td><td align="center">:</td><td> ' . $_POST['jumlah_dibutuhkan'] . '</td></tr>';
                $msg .= '<tr><td>Share</td><td align="center">:</td><td>' . $_POST['share'] . '</td></tr>';
                $msg .= '<tr><td>Manager HCM</td><td>:</td><td>' . $data1[0]['manager_hcm'] . '</td></tr>';
                $msg .= '<tr><td>Status Manager HCM</td><td>:</td><td>' . $_POST['status_manager_hcm'] . '</td></tr>';
                $msg .= '</table>';
                $msg .= '</p>';
                $msg .= '<p>Mohon dicek lebih lanjut.</p>';
                $msg .= ' <table>';
                $msg .= ' <tr>';
                $msg .= ' </table>';
                $msg .= ' <p>Terimakasih</p>';
                $msg .= '</td>';
                $msg .= "
        <tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
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
        } elseif ($data1[0]['hcm_gm'] == $_SESSION['Microservices_UserEmail']) {
            if ($_POST['status_hcm_gm'] == "Disapprove") {
                $condition = "id_offering=" . $_POST['id_offering'];
                $update = sprintf(
                    "`status_manager_hcm`=%s,`status_hcm_gm`=%s,`status_manager_gm_bod`=%s",
                    GetSQLValueString("Pending", "text"),
                    GetSQLValueString("Pending", "text"),
                    GetSQLValueString("Pending", "text")
                );
                $res = $DBNEWREQUEST->update_data($tblname5, $update, $condition);
            } else {
                $condition = "id_offering=" . $_POST['id_offering'];
                $update = sprintf(
                    "`status_hcm_gm`=%s",
                    GetSQLValueString($_POST['status_hcm_gm'], "text")
                );
                $res = $DBNEWREQUEST->update_data($tblname5, $update, $condition);
                $to = "malik.aulia@mastersystem.co.id";
                $from = "MSIZone<msizone@mastersystem.co.id>";
                $cc = $from;
                $bcc = "";
                $reply = $from;
                $subject = "[TESTING]";
                $msg = '<table width="100%">';
                $msg .= ' <tr><td width="20%" rowspan="4"></td>';
                $msg .= ' <td style="width:60%; padding:20px; border:thin solid #dadada">';
                $msg .= ' <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
                $msg .= '<td width="20%" rowspan="4"></tr>';
                $msg .= ' <tr><td style="padding:20px; border:thin solid #dadada">';
                $msg .= ' <p>Dear, All</p>';
                $msg .= ' <p>Dengan ini saya memberikan Approval</p>';
                $msg .= ' <p>';
                $msg .= '<table width="60%">';
                $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi'] . '</td></tr>';
                $msg .= '<tr><td>Posisi</td><td align="center">:</td><td>' . $_POST['posisi'] . '</td></tr>';
                $msg .= '<tr><td>Project Code</td><td align="center">:</td><td>' . $_POST['project_code'] . '</td></tr>';
                $msg .= '<tr><td>Kandidat</td><td align="center">:</td><td>' . $_POST['kandidat'] . '</td></tr>';
                $msg .= '<tr><td>Jumlah Dibutuhkan</td><td align="center">:</td><td> ' . $_POST['jumlah_dibutuhkan'] . '</td></tr>';
                $msg .= '<tr><td>Share</td><td align="center">:</td><td>' . $_POST['share'] . '</td></tr>';
                $msg .= '<tr><td>Manager HCM</td><td>:</td><td>' . $data1[0]['hcm_gm'] . '</td></tr>';
                $msg .= '<tr><td>Status Manager HCM</td><td>:</td><td>' . $_POST['status_hcm_gm'] . '</td></tr>';
                $msg .= '</table>';
                $msg .= '</p>';
                $msg .= '<p>Mohon dicek lebih lanjut.</p>';
                $msg .= ' <table>';
                $msg .= ' <tr>';
                $msg .= ' </table>';
                $msg .= ' <p>Terimakasih</p>';
                $msg .= '</td>';
                $msg .= "
        <tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
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
        } elseif ($data1[0]['manager_gm_bod'] == $_SESSION['Microservices_UserEmail']) {
            if ($_POST['status_manager_gm_bod'] == "Disapprove") {
                $condition = "id_offering=" . $_POST['id_offering'];
                $update = sprintf(
                    "`status_manager_hcm`=%s,`status_hcm_gm`=%s,`status_manager_gm_bod`=%s",
                    GetSQLValueString("Pending", "text"),
                    GetSQLValueString("Pending", "text"),
                    GetSQLValueString("Pending", "text")
                );
                $res = $DBNEWREQUEST->update_data($tblname5, $update, $condition);
            } else {
                $condition = "id_offering=" . $_POST['id_offering'];
                $update = sprintf(
                    "`status_manager_gm_bod`=%s",
                    GetSQLValueString($_POST['status_manager_gm_bod'], "text")
                );
                $res = $DBNEWREQUEST->update_data($tblname5, $update, $condition);
                $to = "malik.aulia@mastersystem.co.id";
                $from = "MSIZone<msizone@mastersystem.co.id>";
                $cc = $from;
                $bcc = "";
                $reply = $from;
                $subject = "[TESTING]";
                $msg = '<table width="100%">';
                $msg .= ' <tr><td width="20%" rowspan="4"></td>';
                $msg .= ' <td style="width:60%; padding:20px; border:thin solid #dadada">';
                $msg .= ' <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
                $msg .= '<td width="20%" rowspan="4"></tr>';
                $msg .= ' <tr><td style="padding:20px; border:thin solid #dadada">';
                $msg .= ' <p>Dear, All</p>';
                $msg .= ' <p>Dengan ini saya memberikan Approval</p>';
                $msg .= ' <p>';
                $msg .= '<table width="60%">';
                $msg .= '<tr><td>Divisi</td><td>:</td><td>' . $_POST['divisi'] . '</td></tr>';
                $msg .= '<tr><td>Posisi</td><td align="center">:</td><td>' . $_POST['posisi'] . '</td></tr>';
                $msg .= '<tr><td>Project Code</td><td align="center">:</td><td>' . $_POST['project_code'] . '</td></tr>';
                $msg .= '<tr><td>Kandidat</td><td align="center">:</td><td>' . $_POST['kandidat'] . '</td></tr>';
                $msg .= '<tr><td>Jumlah Dibutuhkan</td><td align="center">:</td><td> ' . $_POST['jumlah_dibutuhkan'] . '</td></tr>';
                $msg .= '<tr><td>Share</td><td align="center">:</td><td>' . $_POST['share'] . '</td></tr>';
                $msg .= '<tr><td>Manager HCM</td><td>:</td><td>' . $data1[0]['manager_gm_bod'] . '</td></tr>';
                $msg .= '<tr><td>Status Manager HCM</td><td>:</td><td>' . $_POST['status_manager_gm_bod'] . '</td></tr>';
                $msg .= '</table>';
                $msg .= '</p>';
                $msg .= '<p>Mohon dicek lebih lanjut.</p>';
                $msg .= ' <table>';
                $msg .= ' <tr>';
                $msg .= ' </table>';
                $msg .= ' <p>Terimakasih</p>';
                $msg .= '</td>';
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
    if ($_POST['status_hcm_gm'] == "Approve" && $_POST['status_manager_hcm'] == "Approve" && $_POST['status_manager_gm_bod'] == "Approve" && $_POST['catatan_offering'] !== null) {
        if ($_POST['project_code'] == null) {
            $insert = sprintf(
                "(`divisi`, `posisi`, `kandidat`, `project_code`,`share`,`jumlah_dibutuhkan`,`manager_hcm`,`status_manager_hcm`,`hcm_gm`,`status_hcm_gm`,`manager_gm_bod`,`status_manager_gm_bod`,`catatan_offering`)
        VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
                GetSQLValueString($_POST['divisi'], "text"),
                GetSQLValueString($_POST['posisi'], "text"),
                GetSQLValueString($_POST['kandidat'], "text"),
                GetSQLValueString($_POST['project_code'], "text"),
                GetSQLValueString($_POST['share'], "text"),
                GetSQLValueString($_POST['jumlah_dibutuhkan'], "int"),
                GetSQLValueString($_POST['manager_hcm'], "text"),
                GetSQLValueString($_POST['status_manager_hcm'], "text"),
                GetSQLValueString($_POST['hcm_gm'], "text"),
                GetSQLValueString($_POST['status_hcm_gm'], "text"),
                GetSQLValueString($_POST['manager_gm_bod'], "text"),
                GetSQLValueString($_POST['status_manager_gm_bod'], "text"),
                GetSQLValueString($_POST['catatan_offering'], "text")
            );
            $res = $DBNEWREQUEST->insert_data($tblname7, $insert);
            $ALERT->savedata();
        } else {
            $condition = "project_code=" . $_POST['project_code'];
            $update = sprintf(
                "`catatan_offering`=%s",
                GetSQLValueString($_POST['catatan_offering'], "text")
            );
            $res = $DBNEWREQUEST->update_data($tblname7, $update, $condition);
            $ALERT->savedata();
        }
    }
} elseif (isset($_POST['editjoin'])) {
    $condition = "id_join=" . $_POST['id_join'];
    $update = sprintf(
        "`join_date`=%s",
        GetSQLValueString($_POST['join_date'], "date")

    );
    $res = $DBNEWREQUEST->update_data($tblname7, $update, $condition);
    $ALERT->savedata();
}
