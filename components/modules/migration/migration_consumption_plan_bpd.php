<!-- <?php
function consumption_plan($price, $band)
{
    if ($band == 1) {
        $factor = 0.025; // 2.5%
        // $consumption_plan = $price * 0.025;
    } else
    if ($band == 2) {
        $factor = 0.020; // 2.0%
        // $consumption_plan = $price * 0.020;
    } else
    if ($band == 3) {
        $factor = 0.015; // 1.5%
        // $consumption_plan = $price * 0.015;
    } else
    if ($band == 4) {
        $factor = 0.010; // 1.0%
        // $consumption_plan = $price * 0.010;
    } else {
        $factor = 0; // Default factor for other bands
        // $consumption_plan = 0;
    }
    $consumption_plan = number_format($price * $factor, 2, '.', '');
    return array($consumption_plan, $factor);
}

$DBSBF = get_conn("SERVICE_BUDGET");
?>
<table>
    <thead>
        <tr>
            <th>Order Number</th>
            <th>Band</th>
            <th>Price</th>
            <th>Factor</th>
            <th>Consumption Plan</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $rsData = $DBSBF->get_data("trx_project_implementations");
        if ($rsData[2] > 0) {
            do {
                $condition = "project_id = " . $rsData[0]['project_id'];
                $rsProject = $DBSBF->get_data("trx_project_list", $condition);
                if ($rsProject[2] > 0) {
                    $band = $rsProject[0]['band'];
                } else {
                    $band = 0;
                }
                $price = $rsData[0]['implementation_price'];
                $xxxx = consumption_plan($price, $band);
                $consumption_plan = $xxxx[0];
                $factor = $xxxx[1];
                $update = "consumption_plan = '" . $consumption_plan . "'";
                $id = $rsData[0]['project_implementation_id'];
                $condiion = "project_implementation_id = " . $id;
                $DBSBF->update_data("trx_project_implementations", $update, $condiion);
                echo "<tr>";
                echo "<td>" . $rsProject[0]['order_number'] . "</td>";
                echo "<td>" . $band . "</td>";
                echo "<td>" . number_format($price, 2) . "</td>";
                echo "<td>" . number_format($factor, 3) . "</td>";
                echo "<td>" . number_format($consumption_plan, 2) . "</td>";
                echo "<td>" . $consumption_plan . "</td>";
                echo "</tr>";
            } while ($rsData[0] = $rsData[1]->fetch_assoc());
        }

        ?>
    </tbody>
</table> -->