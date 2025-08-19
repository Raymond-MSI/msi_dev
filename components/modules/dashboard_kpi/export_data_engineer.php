<?php
include 'conn2.php';
$nama = $_GET['nama'];
$o = preg_replace("/[']/", "", $nama);
header("Content-type: application/vnd-ms-excel");

// membuat nama file ekspor "export-to-excel.xls"
header("Content-Disposition: attachment; filename= KPI $o.xls");
$sql = "SELECT * FROM sa_summary_user WHERE Nama LIKE '%$o%'";
mysqli_select_db($conn, 'sa_summary_user');
$retval = mysqli_query($conn, $sql);
if (!$retval) {
    die('Could not get data: ' . mysqli_error());
}
$data3 = mysqli_fetch_assoc($retval);
if ($data3 == NULL) {
    echo 'Data kamu tidak ada';
} else {
    $nilai_project = number_format($data3['nilai_project'], 5, ",", ".");
    $nilai_personal_assignment = number_format($data3['nilai_personal_assignment'], 5, ",", ".");
    $total_nilai = number_format($data3['total_nilai'], 5, ",", ".");
    $project = number_format($data3['project'], 2);
    $personal_assignment = number_format($data3['personal_assignment'], 2);
?>
    <label>Summary : </label>
    <label><?php echo $data3['Nama']; ?></label><br>
    <label>Produktifitas :</label>
    <label><?php echo $data3['produktifitas'] . " Project"; ?></label>
    <br>
    <table border="1">
        <!-- <tr>
            <th colspan="1">Summary : </th>
            <th colspan="2"><?php echo $data2['Nama']; ?></th>
            <th colspan="1">Produktifitas :</th>
            <th colspan="1"><?php echo $data3['produktifitas'] . " Project"; ?></th>
        </tr> -->
        <tr>
            <th colspan="4"></th>
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
<?php
}
$sql = "SELECT * FROM sa_user WHERE Nama LIKE '%$o%'";
$no = 1;
mysqli_select_db($conn, 'sa_user');
$retval = mysqli_query($conn, $sql);
if (!$retval) {
    die('Could not get data: ' . mysqli_error());
}
$data2 = mysqli_fetch_assoc($retval);
if ($data2 == NULL) {
    echo "<script>alert('Tidak ada data')</script>";
} else {
    // include ' data_orang.php'; 
?>
    <!-- <table border="1"> -->
    <br>
    <br>
    <table border="1">
        <tr>
            <th colspan="2">Nama :</th>
            <th colspan="11"><?php echo $data2['Nama']; ?></th>
        </tr>
        <tr>
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
            <th>Status Wrike</th>
            <th>Status Project</th>
        </tr>
    <?php
}
// $condition = "project_code IS NOT NULL";
// $data = $DBPKPI->get_data("kpi_project_wr", $condition);
$sql = "SELECT * FROM sa_user WHERE Nama LIKE '%$o%'";
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
            <!-- <td><?php //echo $no++; 
                        ?></td> -->
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
            <td><?php echo $data['status_wr']; ?></td>
            <td><?php echo $data['status_project']; ?></td>
        </tr>
    <?php
}
$sql = "SELECT * FROM sa_user_kpi WHERE Nama LIKE '%$o%'";
$no = 1;
mysqli_select_db($conn, 'sa_user_kpi');
$retval = mysqli_query($conn, $sql);
if (!$retval) {
    die('Could not get data: ' . mysqli_error());
}
$dataa = mysqli_fetch_assoc($retval);
if ($dataa == NULL) {
    echo ' ';
} else {
    $hasil_aktual_ideal = number_format($dataa['hasil_aktual_ideal'], 5, ",", ".");
    $hasil_akhir_aktual_ideal = number_format($dataa['hasil_akhir_aktual_ideal'], 5, ",", ".");
    ?>
        <tr>
            <td colspan="3">Total :</td>
            <td><?php echo $hasil_aktual_ideal; ?></td>
            <td colspan="6"></td>
            <td colspan="1"><?php echo $hasil_akhir_aktual_ideal; ?></td>
            <td colspan="2"></td>
        </tr>
    </table>

    <br>
    <br>
<?php
}
?>
<table border="1">
    <tr>
        <th>Project Code</th>
        <th>Value</th>
        <th>Start Assignment</th>
        <th>BAST Plan</th>
        <th>BAST Actual</th>
        <th>Total</th>
        <th>Commercial Category</th>
        <th>Commercial KPI</th>
        <th>Time Category</th>
        <th>Time KPI</th>
        <th>Error Category</th>
        <th>Error KPI</th>
        <th>CTE</th>
        <th>Total CTE</th>
        <th>Max Value</th>
        <th>Weighted Value</th>
    </tr>

    <?php
    $sql = "SELECT a.project_code, a.value, a.start_assignment, a.bast_plan, a.bast_actual, a.total, a.commercial_category, a.commercial_kpi, a.time_category, a.time_kpi, a.error_category, a.error_kpi, a.cte, a.total_cte, a.max_value, a.weighted_value, b.Nama FROM sa_kpi_project_wr a LEFT JOIN sa_user b ON a.project_code=b.project_code WHERE b.Nama LIKE '%$o%'";
    $no = 1;
    mysqli_select_db($conn, 'sa_kpi_project_wr');
    $retval = mysqli_query($conn, $sql);
    if (!$retval) {
        die('Could not get data: ' . mysqli_error());
    }

    while ($data = mysqli_fetch_assoc($retval)) {
        $cte = number_format($data['cte'], 3);
        $value = number_format($data['value'], 5, ",", ".");
        $commercial_kpi = number_format($data['commercial_kpi'], 3);
        $time_kpi = number_format($data['time_kpi'], 3);
        $total_cte = number_format($data['total_cte'], 3);
        $max_value = number_format($data['max_value'], 5, ",", ".");
        $weighted_value = number_format($data['weighted_value'], 5, ",", ".");
    ?>
        <tr>
            <!-- <td><?php //echo $no++; 
                        ?></td> -->
            <td><?php echo $data['project_code']; ?></td>
            <td><?php echo $value; ?></td>
            <td><?php echo $data['start_assignment']; ?></td>
            <td><?php echo $data['bast_plan']; ?></td>
            <td><?php echo $data['bast_actual']; ?></td>
            <td><?php echo $data['total']; ?></td>
            <td><?php echo $data['commercial_category']; ?></td>
            <td><?php echo $commercial_kpi * 100 . "%"; ?></td>
            <td><?php echo $data['time_category']; ?></td>
            <td><?php echo $time_kpi * 100 . "%"; ?></td>
            <td><?php echo $data['error_category']; ?></td>
            <td><?php echo $data['error_kpi']; ?></td>
            <td><?php echo $cte * 100 . "%"; ?></td>
            <td><?php echo $total_cte * 100 . "%"; ?></td>
            <td><?php echo $max_value ?></td>
            <td><?php echo $weighted_value ?></td>
        </tr>
    <?php
    }
    ?>
</table>