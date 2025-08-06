<?php 
include_once("../../../applications/connections/connections.php"); 
include_once("../../../components/classes/func_databases_v3.php");
include_once("../../../components/classes/func_hcm.php");
include_once( "../../../components/classes/func_modules.php" );
include_once( "../../../components/classes/func_cfg_web.php");
$DB = new WebConfig($hostname, $username, $password, $database);
?>
<script>
    $('#resource').DataTable({
        dom: 'rtip',
        ordering: false,
        scrollX: true
    });
</script>
<?php
$resource = $_GET['resource'];
$mdlname = 'WRIKE_INTEGRATE';
$DBWRIKE = get_conn($mdlname);

$mysql = "SELECT `sa_dashboard_kpi`.`sa_user`.`project_code`, `sa_dashboard_kpi`.`sa_user`.`so_number`, `sa_dashboard_kpi`.`sa_user`.`customer_name`, `sa_dashboard_kpi`.`sa_user`.`project_name`, `sa_dashboard_kpi`.`sa_user`.`role` FROM `sa_dashboard_kpi`.`sa_user` WHERE `sa_dashboard_kpi`.`sa_user`.`Nama` LIKE '%" . $resource . "%' AND `sa_dashboard_kpi`.`sa_user`.`kpi_status` = 'Reviewed' ORDER BY `sa_dashboard_kpi`.`sa_user`.`Nama`; ";
$project_list = $DBWRIKE->get_sql($mysql);

$mdlname = "HCM";
$DBHCM = get_conn($mdlname);
$nama = $DBHCM->get_leader_v2($resource);
// echo '<div class="mb-3">Resource Name : ' . $nama[0] . "</div>";
?>
<table class="display" id="resource" width="100%">
    <thead>
        <tr>
            <th>Project Code</th>
            <th>So Number</th>
            <th>Customer Name</th>
            <th>Project Name</th>
            <th>Tole</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if($project_list[2]>0)
        {
            do { 
                ?>
                <tr>
                    <td><?php echo $project_list[0]['project_code']; ?></td>
                    <td><?php echo $project_list[0]['so_number']; ?></td>
                    <td><?php echo $project_list[0]['customer_name']; ?></td>
                    <td><?php echo $project_list[0]['project_name']; ?></td>
                    <td><?php echo $project_list[0]['role']; ?></td>
                </tr>
                <?php 
            } while($project_list[0]=$project_list[1]->fetch_assoc()); 
        } else
        {
            ?>
                <tr><td colspan="6">No data to display.</td></tr>
            <?php
        }    
        ?>
    </tbody>
</table>