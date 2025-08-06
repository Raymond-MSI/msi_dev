<?php
$mdlname = "SERVICE_BUDGET";
$DBLOGS = get_conn($mdlname);

$mysql = "SELECT `project_id`, `description`, `entry_by`, `entry_date` FROM `sa_logs` ORDER BY `log_id` DESC LIMIT 0,5";
$logs = $DBLOGS->get_sql($mysql);
?>

<div class="card shadow mb-3">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-secondary">Logs</h6>
    </div>
    <div class="card-body" style="font-size:12px">
        <?php
        do {
            $name = $DBHCM->get_profile($logs[0]['entry_by'], "employee_name");
            echo "</p>";
            echo "Project ID : " . $logs[0]['project_id'];
            if(date("Y-m-d",strtotime($logs[0]['entry_date']))==date("Y-m-d")) {
                echo "   <span class='badge rounded-pill bg-danger '>New</span>";
            }
            echo "<br/>";
            echo $logs[0]['description'] . "<br/>";
            echo "Performed : " . $name . "<br/>";
            echo "Date : " . date("d-M-Y G:i:s",strtotime($logs[0]['entry_date'])) . "</p><hr/>";
        } while($logs[0]=$logs[1]->fetch_assoc());
        ?>
    </div>
</div>
