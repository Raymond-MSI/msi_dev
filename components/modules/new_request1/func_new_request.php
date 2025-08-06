<?php
// func bagian ADD
    if(isset($_POST['add'])) {
        $insert = sprintf("(`id`,`divisi`,`posisi`,`jumlah_dibutuhkan`,`tanggal_dibutuhkan`,`deskripsi`,`nama_project`,`nama_customer`,`kode_project`,`status_rekrutmen`,`status_karyawan`,`mpp`,
        `jenis_kelamin`,`usia`,`pendidikan_minimal`,`jurusan`,`pengalaman_minimal`,`kompetensi_teknis`,`kompetensi_non_teknis`,`kandidat`,`catatan`,`diisi_oleh_hcm`,`range_salary`,`request_by`,`gm`,`status_gm`,`gm_hcm`,`status_gm_hcm`,`status_request`) 
        VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
            GetSQLValueString($_POST['id'], "int"),
            GetSQLValueString($_POST['divisi'], "text"),
            GetSQLValueString($_POST['posisi'], "text"),
            GetSQLValueString($_POST['jumlah_dibutuhkan'], "text"),
            GetSQLValueString($_POST['tanggal_dibutuhkan'], "date"),
            GetSQLValueString($_POST['deskripsi'], "text"),
            GetSQLValueString($_POST['nama_project'], "text"),
            GetSQLValueString($_POST['nama_customer'], "text"),
            GetSQLValueString($_POST['kode_project'], "text"),
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
            GetSQLValueString($_POST['catatan'], "text"),
            GetSQLValueString($_POST['diisi_oleh_hcm'], "text"),
            GetSQLValueString($_POST['range_salary'], "text"),
            GetSQLValueString($_SESSION['Microservices_UserEmail'], "text"),
            GetSQLValueString($_POST['gm'],"text"),
            GetSQLValueString($_POST['status_gm'],"text"),
            GetSQLValueString($_POST['gm_hcm'],"text"),
            GetSQLValueString($_POST['status_gm_hcm'],"text"),
            GetSQLValueString("Active","text")
            
        );
        $res = $DBNR->insert_data($tblname, $insert);
        $ALERT->savedata();

// Add auto kirim email GM
$to= $_POST['gm'];
$from = "MSIZone<msizone@mastersystem.co.id>";
$cc = $from;
$bcc = "";
$reply=$from;
$subject="[TESTING] Request Recruitment";
$msg='<table width="100%">';
$msg.='    <tr><td width="20%" rowspan="4"></td>';
$msg.='    <td style="width:60%; padding:20px; border:thin solid #dadada">';
$msg.='        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
$msg.='    <td width="20%" rowspan="4"></tr>';
$msg.='    <tr><td style="padding:20px; border:thin solid #dadada">';
$msg.='        <p>Dear,' . $to . '</p>';
$msg.='        <p>Berikut ini adalah daftar Request Karyawan yang menunggu approval dari Anda.</p>';
$msg.='        <p>';
$msg.='        <table width="100%">';
$msg.='            <tr>';
$msg.='                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold">Divisi</td>';
$msg.='                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold">Posisi</td>';
$msg.='                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold">Jumlah</td>';
$msg.='                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold">Tanggal Dibutuhkan</td>';
$msg.='                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold">Deskripsi</td>';
$msg.='                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold">Nama Project</td>';
$msg.='                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold">Nama Customer</td>';
$msg.='                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold">Kode Project</td>';
$msg.='                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold">Status Rekrutmen</td>';
$msg.='                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold">Status Karyawan</td>';
$msg.='                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold">GM</td>';
$msg.='                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold">Status GM</td>';
$msg.='                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold">GM HCM</td>';
$msg.='                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold">Status GM HCM</td>';
$msg.='            </tr>';
$msg.='                <tr>';
$msg.='                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['divisi'] . '</td>';
$msg.='                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['posisi'] . '</td>';
$msg.='                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['jumlah_dibutuhkan'] . '</td>';
$msg.='                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['tanggal_dibutuhkan'] . '</td>';
$msg.='                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['deskripsi'] . '</td>';
$msg.='                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['nama_project'] . '</td>';
$msg.='                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['nama_customer'] . '</td>';
$msg.='                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['kode_project'] . '</td>';
$msg.='                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['status_rekrutmen'] . '</td>';
$msg.='                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['status_karyawan'] . '</td>';
$msg.='                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['gm'] . '</td>';
$msg.='                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['status_gm'] . '</td>';
$msg.='                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['gm_hcm'] . '</td>';
$msg.='                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['status_gm_hcm'] . '</td>';
$msg.='            </tr>';   
$msg.='        </table>';
$msg.='        </p>';
$msg.='        <p>Silahkan direview untuk kelayakan mendapatkan approval.</p>';
$msg.='        <table>';
$msg.='        <tr>';
// $msg.='        <a href="http://192.168.234.157/index.php?mod=new_request&nr_stat=Pending">Submit</a>';
$msg.='        </table>';
$msg.='        <p>Terimakasih</p>';
$msg.='    </td>';
$msg.="<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
$msg.="<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
$msg.="</table>";
echo $msg;
    $headers = "From: " . $from . "\r\n" .
        "Reply-To: " . $reply . "\r\n" .
        "Cc: " . $cc . "\r\n" .
        "Bcc: " . $bcc . "\r\n" .
        "MIME-Version: 1.0" . "\r\n" .
        "Content-Type: text/html; charset=UTF-8" . "\r\n" .
        "X-Mailer: PHP/" . phpversion();
        
    $ALERT=new Alert();
    if(mail($to, $subject, $msg, $headers)) {
        echo "Email terkirim pada jam " . date("d M Y G:i:s");
    } else {
       echo "Email gagal terkirim pada jam " . date("d M Y G:i:s");
    }

    } elseif(isset($_POST['edit'])) {
        if (!isset($_POST['status_gm_hcm'])) {
    $statusgmhcm = null;
    } else {
    $statusgmhcm = $_POST['status_gm_hcm'];
    }   
        // func Approve (GM)
        $data = $DBNR->get_sql("SELECT * FROM sa_trx_request_requirement WHERE id=". $_POST['id'] . "");
        if ($data[0]['gm'] == $_SESSION['Microservices_UserEmail']){
            $condition = "id=" . $_POST['id'];
        $update = sprintf("`id`=%s, `divisi`=%s, `posisi`=%s, `jumlah_dibutuhkan`=%s, `tanggal_dibutuhkan`=%s, `deskripsi`=%s, `nama_project`=%s, `nama_customer`=%s, `kode_project`=%s, `status_rekrutmen`=%s,`status_karyawan`=%s, `mpp`=%s
        , `jenis_kelamin`=%s, `usia`=%s, `pendidikan_minimal`=%s, `jurusan`=%s, `pengalaman_minimal`=%s, `kompetensi_teknis`=%s, `kompetensi_non_teknis`=%s, `kandidat`=%s
        , `catatan`=%s, `diisi_oleh_hcm`=%s, `range_salary`=%s, `gm`=%s, `status_gm`=%s,`gm_hcm`=%s",
            GetSQLValueString($_POST['id'], "int"),
            GetSQLValueString($_POST['divisi'], "text"),
            GetSQLValueString($_POST['posisi'], "text"),
            GetSQLValueString($_POST['jumlah_dibutuhkan'], "text"),
            GetSQLValueString($_POST['tanggal_dibutuhkan'], "date"),
            GetSQLValueString($_POST['deskripsi'], "text"),
            GetSQLValueString($_POST['nama_project'], "text"),
            GetSQLValueString($_POST['nama_customer'], "text"),
            GetSQLValueString($_POST['kode_project'], "text"),
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
            GetSQLValueString($_POST['catatan'], "text"),
            GetSQLValueString($_POST['diisi_oleh_hcm'], "text"),
            GetSQLValueString($_POST['range_salary'], "text"),
            GetSQLValueString($_POST['gm'],"text"),
            GetSQLValueString($_POST['status_gm'],"text"),
            GetSQLValueString($_POST['gm_hcm'],"text"),
            GetSQLValueString($statusgmhcm,"text"),
            
    );
        $res = $DBNR->update_data($tblname, $update, $condition);

// jika GM Approve kirim email ke GM (HCM)
$to= $data[0]['gm_hcm'];
$from = "MSIZone<msizone@mastersystem.co.id>";
$cc = $from;
$bcc = "";
$reply=$from;
$subject="[TESTING] Request Recruitment";
$msg='<table width="100%">';
$msg.='    <tr><td width="20%" rowspan="4"></td>';
$msg.='    <td style="width:60%; padding:20px; border:thin solid #dadada">';
$msg.='        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
$msg.='    <td width="20%" rowspan="4"></tr>';
$msg.='    <tr><td style="padding:20px; border:thin solid #dadada">';
$msg.='        <p>Dear,' . $to . '</p>';
$msg.='        <p>Berikut ini adalah daftar Request Karyawan yang menunggu approval dari Anda.</p>';
$msg.='        <p>';
$msg.='        <table width="100%">';
$msg.='            <tr>';
$msg.='                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold">Divisi</td>';
$msg.='                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold">Posisi</td>';
$msg.='                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold">Jumlah</td>';
$msg.='                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold">Tanggal Dibutuhkan</td>';
$msg.='                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold">Deskripsi</td>';
$msg.='                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold">Nama Project</td>';
$msg.='                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold">Nama Customer</td>';
$msg.='                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold">Kode Project</td>';
$msg.='                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold">Status Rekrutmen</td>';
$msg.='                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold">Status Karyawan</td>';
$msg.='                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold">GM</td>';
$msg.='                <td style="border-bottom:solid thin #888; text-align:center; font-weight:bold">Status GM</td>';
$msg.='            </tr>';
$msg.='                <tr>';
$msg.='                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['divisi'] . '</td>';
$msg.='                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['posisi'] . '</td>';
$msg.='                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['jumlah_dibutuhkan'] . '</td>';
$msg.='                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['tanggal_dibutuhkan'] . '</td>';
$msg.='                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['deskripsi'] . '</td>';
$msg.='                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['nama_project'] . '</td>';
$msg.='                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['nama_customer'] . '</td>';
$msg.='                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['kode_project'] . '</td>';
$msg.='                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['status_rekrutmen'] . '</td>';
$msg.='                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['status_karyawan'] . '</td>';
$msg.='                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['gm'] . '</td>';
$msg.='                <td style="vertical-align:top; text-align:center; padding:5px">' . $_POST['status_gm'] . '</td>';
$msg.='            </tr>';   
$msg.='        </table>';
$msg.='        </p>';
$msg.='        <p>Silahkan direview untuk kelayakan mendapatkan approval.</p>';
$msg.='        <table>';
$msg.='        <tr>';
// $msg.='        <a href="http://192.168.234.157/index.php?mod=new_request&nr_stat=Pending">Submit</a>';
$msg.='        </table>';
$msg.='        <p>Terimakasih</p>';
$msg.='    </td>';
$msg.="<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
$msg.="<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
$msg.="</table>";
echo $msg;
    $headers = "From: " . $from . "\r\n" .
        "Reply-To: " . $reply . "\r\n" .
        "Cc: " . $cc . "\r\n" .
        "Bcc: " . $bcc . "\r\n" .
        "MIME-Version: 1.0" . "\r\n" .
        "Content-Type: text/html; charset=UTF-8" . "\r\n" .
        "X-Mailer: PHP/" . phpversion();
        
    $ALERT=new Alert();
    if(mail($to, $subject, $msg, $headers)) {
        echo "Email terkirim pada jam " . date("d M Y G:i:s");
    } else {
       echo "Email gagal terkirim pada jam " . date("d M Y G:i:s");
    }    
    // `id`=%s, `divisi`=%s, `posisi`=%s, `jumlah_dibutuhkan`=%s, `tanggal_dibutuhkan`=%s, `deskripsi`=%s, `nama_project`=%s, `nama_customer`=%s, `kode_project`=%s, `status_rekrutmen`=%s,`status_karyawan`=%s, `mpp`=%s
    //     , `jenis_kelamin`=%s, `usia`=%s, `pendidikan_minimal`=%s, `jurusan`=%s, `pengalaman_minimal`=%s, `kompetensi_teknis`=%s, `kompetensi_non_teknis`=%s, `kandidat`=%s
    //     , `catatan`=%s, `diisi_oleh_hcm`=%s, `range_salary`=%s, 
    
    // BAGIA APPROVAL GM(HCM)
        }else if ($data[0]['gm_hcm'] == $_SESSION['Microservices_UserEmail']){
        $condition = "id=" . $_POST['id'];
        $update = sprintf("`gm`=%s,`gm_hcm`=%s, `status_gm_hcm`=%s",
            // GetSQLValueString($_POST['id'], "int"),
            // GetSQLValueString($_POST['divisi'], "text"),
            // GetSQLValueString($_POST['posisi'], "text"),
            // GetSQLValueString($_POST['jumlah_dibutuhkan'], "text"),
            // GetSQLValueString($_POST['tanggal_dibutuhkan'], "date"),
            // GetSQLValueString($_POST['deskripsi'], "text"),
            // GetSQLValueString($_POST['nama_project'], "text"),
            // GetSQLValueString($_POST['nama_customer'], "text"),
            // GetSQLValueString($_POST['kode_project'], "text"),
            // GetSQLValueString($_POST['status_rekrutmen'], "text"),
            // GetSQLValueString($_POST['status_karyawan'], "text"),
            // GetSQLValueString($_POST['mpp'], "text"),
            // GetSQLValueString($_POST['jenis_kelamin'], "text"),
            // GetSQLValueString($_POST['usia'], "text"),
            // GetSQLValueString($_POST['pendidikan_minimal'], "text"),
            // GetSQLValueString($_POST['jurusan'], "text"),
            // GetSQLValueString($_POST['pengalaman_minimal'], "text"),
            // GetSQLValueString($_POST['kompetensi_teknis'], "text"),
            // GetSQLValueString($_POST['kompetensi_non_teknis'], "text"),
            // GetSQLValueString($_POST['kandidat'], "text"),
            // GetSQLValueString($_POST['catatan'], "text"),
            // GetSQLValueString($_POST['diisi_oleh_hcm'], "text"),
            // GetSQLValueString($_POST['range_salary'], "text"),
            GetSQLValueString($_POST['gm'],"text"),
            GetSQLValueString($_POST['gm_hcm'],"text"),
            GetSQLValueString($_POST['status_gm_hcm'],"text"),
            
    );
        $res = $DBNR->update_data($tblname, $update, $condition);


        // EDIT ( REQUEST BY )
        }else if ($data[0]['request_by'] == $_SESSION['Microservices_UserEmail']){
        $condition = "id=" . $_POST['id'];
        $update = sprintf("`id`=%s, `divisi`=%s, `posisi`=%s, `jumlah_dibutuhkan`=%s, `tanggal_dibutuhkan`=%s, `deskripsi`=%s, `nama_project`=%s, `nama_customer`=%s, `kode_project`=%s, `status_rekrutmen`=%s,`status_karyawan`=%s, `mpp`=%s
        , `jenis_kelamin`=%s, `usia`=%s, `pendidikan_minimal`=%s, `jurusan`=%s, `pengalaman_minimal`=%s, `kompetensi_teknis`=%s, `kompetensi_non_teknis`=%s, `kandidat`=%s
        , `catatan`=%s, `diisi_oleh_hcm`=%s, `range_salary`=%s, `gm`=%s,`gm_hcm`=%s",
            GetSQLValueString($_POST['id'], "int"),
            GetSQLValueString($_POST['divisi'], "text"),
            GetSQLValueString($_POST['posisi'], "text"),
            GetSQLValueString($_POST['jumlah_dibutuhkan'], "text"),
            GetSQLValueString($_POST['tanggal_dibutuhkan'], "date"),
            GetSQLValueString($_POST['deskripsi'], "text"),
            GetSQLValueString($_POST['nama_project'], "text"),
            GetSQLValueString($_POST['nama_customer'], "text"),
            GetSQLValueString($_POST['kode_project'], "text"),
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
            GetSQLValueString($_POST['catatan'], "text"),
            GetSQLValueString($_POST['diisi_oleh_hcm'], "text"),
            GetSQLValueString($_POST['range_salary'], "text"),
            GetSQLValueString($_POST['gm'],"text"),
            GetSQLValueString($_POST['gm_hcm'],"text")
            
    );
        $res = $DBNR->update_data($tblname, $update, $condition);
        }
        $ALERT->savedata();
    }
    ?>