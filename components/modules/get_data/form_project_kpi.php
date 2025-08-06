<?php
global $DBPKPI;
// $db_name3 = "DASHBOARD_KPI";
// $DBKPIP = get_conn($db_name3);
?>
<?php
// if ($_GET['act'] == 'export') {
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
            <th>∑ CTE</th>
            <th>Total Score CTE</th>
            <th>Max Value</th>
            <th>Weighted Value</th>
        </tr>
        <?php
        $data = $DBPKPI->get_sql("SELECT * FROM sa_kpi_project_wr");
        $no = 1;
        // while ($data = mysqli_fetch_assoc($retval)) {
        do {
            $kpi_com = number_format($data[0]['commercial_kpi'], 3);
            $time_kpi = number_format($data[0]['time_kpi'], 3);
            $cte = number_format($data[0]['cte'], 3);
            $total_cte = number_format($data[0]['total_cte'], 3);
            $value = number_format($data[0]['value'], 5, ",", ".");
            $max_value = number_format($data[0]['max_value'], 5, ",", ".");
            $weighted_value = number_format($data[0]['weighted_value'], 5, ",", ".");
        ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $data[0]['project_code']; ?></td>
                <td><?php echo $value; ?></td>
                <td><?php echo $data[0]['start_assignment']; ?></td>
                <td><?php echo $data[0]['bast']; ?></td>
                <td><?php echo $data[0]['total']; ?></td>
                <td><?php echo $data[0]['commercial_category']; ?></td>
                <td><?php echo $kpi_com * 100 . "%"; ?></td>
                <td><?php echo $data[0]['time_category']; ?></td>
                <td><?php echo $time_kpi * 100 . "%" ?></td>
                <td><?php echo $data[0]['error_category']; ?></td>
                <td><?php echo $data[0]['error_kpi']; ?></td>
                <td><?php echo $cte * 100 . "%"; ?></td>
                <td><?php echo $total_cte * 100 . "%"; ?></td>
                <td><?php echo $max_value; ?></td>
                <td><?php echo $weighted_value; ?></td>
            </tr>
        <?php
        } while ($data[0] = $data[1]->fetch_assoc());
        ?>
    </table>
<?php
//}
?>