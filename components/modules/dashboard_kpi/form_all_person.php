<?php
include('conn2.php');
// fungsi header dengan mengirimkan raw data excel
header("Content-type: application/vnd-ms-excel");

// membuat nama file ekspor "export-to-excel.xls"
header("Content-Disposition: attachment; filename=KPI Engineer.xls");


?>
<table border="1">
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Project Code</th>
        <th>Role</th>
        <th>Nilai Ideal</th>
        <th>Nilai Aktual</th>
        <th>Start Assignment</th>
        <th>End Assignment</th>
        <th>Duration</th>
        <th>Progress</th>
        <th>Project Support</th>
        <th>CTE</th>
        <th>Nilai Akhir Ideal</th>
        <th>Nilai Akhir Aktual</th>
        <th>Status Wrike</th>
        <th>Status Project</th>
    </tr>
    <?php
    $sql = "SELECT * FROM sa_user";
    $no = 1;
    mysqli_select_db($conn, 'sa_user');
    $retval = mysqli_query($conn, $sql);
    if (!$retval) {
        die('Could not get data: ' . mysqli_error());
    }

    while ($data = mysqli_fetch_assoc($retval)) {
        $nilai_ideal = number_format($data['nilai_ideal'], 5, ",", ".");
        $nilai_aktual = number_format($data['nilai_aktual'], 5, ",", ".");
        $duration = number_format($data['duration'], 0);
        $progress = number_format($data['progress'], 0);
        $cte = number_format($data['cte'], 3);
        $nilai_akhir_ideal = number_format($data['nilai_akhir_ideal'], 5, ",", ".");
        $nilai_akhir_aktual = number_format($data['nilai_akhir_aktual'], 5, ",", ".");
    ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo $data['Nama']; ?></td>
            <td><?php echo $data['project_code']; ?></td>
            <td><?php echo $data['role']; ?></td>
            <td><?php echo $nilai_ideal; ?></td>
            <td><?php echo $nilai_aktual; ?></td>
            <td><?php echo $data['start_assignment']; ?></td>
            <td><?php echo $data['end_assignment']; ?></td>
            <td><?php echo $duration; ?></td>
            <td><?php echo $progress . "%" ?></td>
            <td><?php echo $data['project_support']; ?></td>
            <td><?php echo $cte * 100 . "%"; ?></td>
            <td><?php echo $nilai_akhir_ideal; ?></td>
            <td><?php echo $nilai_akhir_aktual; ?></td>
            <td><?php echo $data['status_wr']; ?></td>
            <td><?php echo $data['status_project']; ?></td>
        </tr>
    <?php
    }
    ?>
</table>