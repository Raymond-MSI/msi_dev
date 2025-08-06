<?php
global $DBREC;
$mdlname = "new_request";
$DBREC = get_conn($mdlname);
$query = $DBREC->get_sqlV2("SELECT * from sa_trx_request_requirement WHERE status_request = 'Pending Approval'");
while ($ambildata = mysqli_fetch_array($query[1])) {
    $divisi = $ambildata['divisi'];
    $posisi = $ambildata['posisi'];
    $jumlah = $ambildata['jumlah_dibutuhkan'];
    $tgl = $ambildata['tanggal_dibutuhkan'];
    $deskripsi = $ambildata['deskripsi'];
    $nama_project = $ambildata['nama_project'];
    $nama_customer = $ambildata['nama_customer'];
    $kode_project = $ambildata['project_code'];
    $kandidat = $ambildata['kandidat'];
    $status_rekrutmen = $ambildata['status_rekrutmen'];
    $pengalaman = $ambildata['pengalaman_minimal'];
    $status_karyawan = $ambildata['status_karyawan'];
    $gm = $ambildata['gm'];
    $status_gm = $ambildata['status_gm'];
    $gmhcm = $ambildata['gm_hcm'];
    $status_gm_hcm = $ambildata['status_gm_hcm'];
    $bod = $ambildata['gm_bod'];
    $status_bod = $ambildata['status_gm_bod'];
    $req_request_by = $ambildata['request_by'];
    $to = "all";
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
    $msg .= '        <p>Dear ,' . $to . '</p>';
    $msg .= '        <p>Berikut ini adalah daftar Request Karyawan yang telah di Approve.</p>';
    $msg .= '        <p>';
    $msg .= '        <table>';
    $msg .= '            <tr><td style="font-weight: bold; width: 60%;">Divisi</td><td align = "center">:</td><td>' . $divisi . '</td></tr>';
    $msg .= '            <tr><td style="font-weight: bold; width: 60%;">Posisi</td><td align = "center">:</td><td>' . $posisi . '</td></tr>';
    $msg .= '            <tr><td style="font-weight: bold; width: 60%;">Jumlah</td><td align = "center">:</td><td>' . $jumlah . '</td></tr>';
    $msg .= '            <tr><td style="font-weight: bold; width: 60%;">Kode Project</td><td align = "center">:</td><td>' . $kode_project . '</td></tr>';
    $msg .= '            <tr><td style="font-weight: bold; width: 60%;">Kandidat</td><td align = "center">:</td><td>' . $kandidat . '</td></tr>';
    $msg .= '            <tr><td style="font-weight: bold; width: 60%;">Status Rekruitmen</td><td align = "center">:</td><td>' . $status_rekrutmen . '</td></tr>';
    $msg .= '            <tr><td style="font-weight: bold; width: 60%;">Status Karyawan</td><td align = "center">:</td><td>' . $status_karyawan . '</td></tr>';
    $msg .= '            <tr><td style="font-weight: bold; width: 60%;">Pengalaman</td><td align = "center">:</td><td>' . $pengalaman . '</td></tr>';
    $msg .= '            <tr><td style="font-weight: bold; width: 60%;">Request By</td><td align = "center">:</td><td>' . $req_request_by . '</td></tr>';
    $msg .= '        </table>';
    $msg .= '        </p>';
    $msg .= '        <p>Silahkan untuk melanjutkan step berikutnya.</p>';
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
