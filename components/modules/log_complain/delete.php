<?php
if (isset($_GET['complain_id'])) {
    $modulename = "Log_Complain";
    $DBKPILog = get_conn('log_complain');
    $tblname = 'complain';
    $complain_id = $_GET['complain_id'];
    $condition = "complain_id=" . $complain_id;

    // Lakukan penghapusan data menggunakan fungsi delete_data
    $res = $DBKPILog->delete_data($tblname, $condition);
    var_dump($res);
    die;


    // Setelah penghapusan, arahkan pengguna kembali ke halaman sebelumnya atau halaman tertentu
    // Misalnya:
    header("Location: index.php?mod=log_complain");
    exit(); // Pastikan exit untuk menghentikan eksekusi skrip lebih lanjut
} else {
    // Jika parameter complain_id tidak ada, mungkin ada kesalahan
    // Tambahkan log atau tindakan lain sesuai kebutuhan
    echo "Parameter complain_id tidak valid.";
}
