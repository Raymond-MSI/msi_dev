<?php
include 'conn2.php';
// fungsi header dengan mengirimkan raw data excel
$nama = $_GET['nama'];
$idaja = str_replace("&20", " ", $nama);
$id = preg_replace("/[']/", "", $idaja);
$idorang = str_replace("[_]", " ", $id);
$hobi = explode("<", $idorang);
header("Content-type: application/vnd-ms-excel");

// membuat nama file ekspor "export-to-excel.xls"
header("Content-Disposition: attachment; filename= KPI $hobi[0].xls");
$sql = "SELECT * FROM sa_user WHERE Nama LIKE '%$hobi[0]%'";
$no = 1;
mysqli_select_db($conn, 'sa_user');
$retval = mysqli_query($conn, $sql);
if (!$retval) {
    die('Could not get data: ' . mysqli_error());
}
$data2 = mysqli_fetch_assoc($retval);
// include 'data_orang.php';
?>
<table border="1">
    <tr>
        <th>Nama</th>
        <th><?php echo $data2['Nama']; ?></th>
    </tr>
    <tr>
        <th>No</th>
        <th>Project Code</th>
        <th>Role</th>
        <th>Nilai Ideal</th>
        <th>Nilai Aktual</th>
        <th>Start Assignment</th>
        <th>End Assignment</th>
        <th>Duration</th>
        <th>Project Progress Saat Mutasi</th>
        <th>Project Support</th>
        <th>Adjusted CTE Score</th>
        <th>Nilai Akhir Ideal</th>
        <th>Nilai Akhir Aktual</th>
        <th>Status</th>
    </tr>
    <?php
    // $condition = "project_code IS NOT NULL";
    // $data = $DBPKPI->get_data("kpi_project_wr", $condition);
    $sql = "SELECT * FROM sa_user WHERE Nama LIKE '%$hobi[0]%'";
    $no = 1;
    mysqli_select_db($conn, 'sa_user');
    $retval = mysqli_query($conn, $sql);
    if (!$retval) {
        die('Could not get data: ' . mysqli_error());
    }

    while ($data = mysqli_fetch_assoc($retval)) {
        $cte = number_format($data['cte'], 3);
        $nilai_ideal = number_format($data['nilai_ideal'], 5, ",", ".");
        $nilai_aktual = number_format($data['nilai_aktual'], 5, ",", ".");
        $duration = number_format($data['duration'], 0, ",", ".");
        $progress = number_format($data['progress'], 0);
        $nilai_akhir_aktual = number_format($data['nilai_akhir_aktual'], 5, ",", ".");
        $nilai_akhir_ideal = number_format($data['nilai_akhir_ideal'], 5, ",", ".");
    ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo $data['project_code']; ?></td>
            <td><?php echo $data['role']; ?></td>
            <td><?php echo $nilai_ideal; ?></td>
            <td><?php echo $nilai_aktual; ?></td>
            <td><?php echo $data['start_assignment']; ?></td>
            <td><?php echo $data['end_assignment']; ?></td>
            <td><?php echo $duration; ?></td>
            <td><?php echo $progress . "%"; ?></td>
            <td><?php echo $data['project_support']; ?></td>
            <td><?php echo $cte * 100 . "%"; ?></td>
            <td><?php echo $nilai_akhir_ideal; ?></td>
            <td><?php echo $nilai_akhir_aktual; ?></td>
            <td><?php echo $data['status']; ?></td>
        </tr>
    <?php
    }
    $sql = "SELECT * FROM sa_user_kpi WHERE Nama LIKE '%$hobi[0]%'";
    $no = 1;
    mysqli_select_db($conn, 'sa_user_kpi');
    $retval = mysqli_query($conn, $sql);
    if (!$retval) {
        die('Could not get data: ' . mysqli_error());
    }
    $dataa = mysqli_fetch_assoc($retval);
    $hasil_aktual_ideal = number_format($dataa['hasil_aktual_ideal'], 5, ",", ".");
    $hasil_akhir_aktual_ideal = number_format($dataa['hasil_akhir_aktual_ideal'], 5, ",", ".");
    ?>
    <tr>
        <td colspan="4">Total :</td>
        <td><?php echo $hasil_aktual_ideal; ?></td>
        <td colspan="7"></td>
        <td><?php echo $hasil_akhir_aktual_ideal; ?></td>
    </tr>
</table>
<?php
$sql = "SELECT * FROM sa_summary_user WHERE Nama LIKE '%$hobi[0]%'";
mysqli_select_db($conn, 'sa_summary_user');
$retval = mysqli_query($conn, $sql);
if (!$retval) {
    die('Could not get data: ' . mysqli_error());
}
$data3 = mysqli_fetch_assoc($retval);
$nilai_project = number_format($data3['nilai_project'], 5, ",", ".");
$nilai_personal_assignment = number_format($data3['nilai_personal_assignment'], 5, ",", ".");
$total_nilai = number_format($data3['total_nilai'], 5, ",", ".");
$project = number_format($data3['project'], 2);
$personal_assignment = number_format($data3['personal_assignment'], 2);
?>
<br>
<br>
<table border="1">
    <tr>
        <th>Summary : </th>
        <th><?php echo $data2['Nama']; ?></th>
        <th>Produktifitas :</th>
        <th><?php echo $data3['produktifitas'] . " Project"; ?></th>
    </tr>
    <tr>
        <td colspan="4"></td>
    </tr>
    <tr>
        <td>Project</td>
        <td><?php echo $project * 100 . "%"; ?></td>
        <td colspan="2"><?php echo $nilai_project; ?></td>
    </tr>
    <tr>
        <td>Personal Assignment</td>
        <td><?php echo $personal_assignment * 100 . "%"; ?></td>
        <td colspan="2"><?php echo $nilai_personal_assignment; ?></td>
    </tr>
    <tr>
        <td colspan="2">Total :</td>
        <td colspan="2"><?php echo $total_nilai; ?></td>
    </tr>
    <?php
    // $condition = "project_code IS NOT NULL";
    // $data = $DBPKPI->get_data("kpi_project_wr", $condition);
    ?>
</table>