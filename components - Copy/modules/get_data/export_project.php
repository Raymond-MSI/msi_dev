<?php
include('conn2.php');

header("Content-type: application/vnd-ms-excel");

header("Content-Disposition: attachment; filename=KPI Project.xls");

?>
<table border="1">
    <tr>
        <th>No</th>
        <th>Project Code</th>
        <th>Value</th>
        <th>Start Assignment</th>
        <th>BAST</th>
        <th>Total</th>
        <th>Commercial Category</th>
        <th>Commercial KPI</th>
        <th>Time Category</th>
        <th>Time KPI</th>
        <th>Error Category</th>
        <th>Error KPI</th>
        <th>CTE</th>
        <th>Total Score CTE</th>
        <th>Max Value</th>
        <th>Weighted Value</th>
    </tr>
    <?php
    $sql = "SELECT * FROM sa_kpi_project_wr";
    $no = 1;
    mysqli_select_db($conn, 'sa_kpi_project_wr');
    $retval = mysqli_query($conn, $sql);
    if (!$retval) {
        die('Could not get data: ' . mysqli_error());
    }

    while ($data = mysqli_fetch_assoc($retval)) {
        $kpi_com = number_format($data['commercial_kpi'], 3);
        $time_kpi = number_format($data['time_kpi'], 3);
        $cte = number_format($data['cte'], 3);
        $total_cte = number_format($data['total_cte'], 3);
        $value = number_format($data['value'], 5, ",", ".");
        $max_value = number_format($data['max_value'], 5, ",", ".");
        $weighted_value = number_format($data['weighted_value'], 5, ",", ".");
    ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo $data['project_code']; ?></td>
            <td><?php echo $value; ?></td>
            <td><?php echo $data['start_assignment']; ?></td>
            <td><?php echo $data['bast']; ?></td>
            <td><?php echo $data['total']; ?></td>
            <td><?php echo $data['commercial_category']; ?></td>
            <td><?php echo $kpi_com * 100 . "%"; ?></td>
            <td><?php echo $data['time_category']; ?></td>
            <td><?php echo $time_kpi * 100 . "%" ?></td>
            <td><?php echo $data['error_category']; ?></td>
            <td><?php echo $data['error_kpi']; ?></td>
            <td><?php echo $cte * 100 . "%"; ?></td>
            <td><?php echo $total_cte * 100 . "%"; ?></td>
            <td><?php echo $max_value; ?></td>
            <td><?php echo $weighted_value; ?></td>
        </tr>
    <?php
    }
    ?>
</table>