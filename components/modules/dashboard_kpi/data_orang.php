<?php
// include 'conn2.php';
global $DBKPI;
$nama = $_GET['nama'];
$idaja = str_replace("&20", " ", $nama);
$id = preg_replace("/[']/", "", $idaja);
$idorang = str_replace("[_]", " ", $id);
$hobi = explode("<", $idorang);
$data2 = $DBKPI->get_sql("SELECT * FROM sa_user WHERE Nama LIKE '%$hobi[0]%'");
$no = 1;
?>
<table border="1">
    <tr>
        <th>Nama</th>
        <th><?php echo $data2[0]['Nama']; ?></th>
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
    $data = $DBKPI->get_sql("SELECT * FROM sa_user WHERE Nama LIKE '%$hobi[0]%'");
    $no = 1;
    do {
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
    } while ($data[0] = $data[1]->fetch_assoc());
    ?>
</table>