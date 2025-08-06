<?php 
include_once("../../../applications/connections/connections.php"); 
include_once("../../../components/classes/func_databases_v3.php");
include_once("../../../components/classes/func_hcm.php");
include_once( "../../../components/classes/func_modules.php" );
include_once( "../../../components/classes/func_cfg_web.php");
$DB = new WebConfig($hostname, $username, $password, $database);
?>
<script>
$(document).ready(function () {
    $('#ProjectDetail').DataTable({
        dom: 'rtip',
        scrollX: true,
        ordering: false,
        buttons:[
            {
            extend: 'excelHtml5',
            text: "<i class='fa-solid fa-file-excel'></i>",
            title: 'KPI_Resources_'+<?php echo date("YmdGis"); ?>
            }
        ],
        // order: [
        //     [0, "asc"]
        // ],
        "columnDefs": [
            {
            "targets": [1,2,3,4,5,6,7],
            "className": 'dt-body-center'
            },
            {
            "targets": [],
            "visible": false
            }
        ]
    });
});
</script>

<?php

// $kpi_status = ' = "' . $_GET['kpi_status'] . '"';
// $project_type = $_GET['project_type'];
// $periode = ' = ' . $_GET['periode'];
// $owner = ' = "' . $_GET['owner'] . '"';
// $PMList = $_GET['subOrdinatsCondition'];
$so_number = $_GET['so_number'];
// $project_id = $_GET['id'];

$mdlname = 'WRIKE_INTEGRATE';
$DBWRIKE = get_conn($mdlname);

$mysql = sprintf("SELECT `sa_wrike_integrate`.`sa_wrike_project_list`.`project_code`, `sa_wrike_integrate`.`sa_wrike_project_list`.`no_so` AS `so_number`, `sa_wrike_integrate`.`sa_wrike_project_detail`.`customer_name`, `sa_wrike_integrate`.`sa_wrike_project_list`.`title` AS `project_name`, `sa_wrike_integrate`.`sa_wrike_project_list`.`owner_email`, `sa_wrike_integrate`.`sa_wrike_assignment`.`resource_email` FROM `sa_wrike_integrate`.`sa_wrike_project_list` LEFT JOIN `sa_wrike_integrate`.`sa_wrike_assignment` ON `sa_wrike_integrate`.`sa_wrike_assignment`.`project_id` = `sa_wrike_integrate`.`sa_wrike_project_list`.`id` LEFT JOIN `sa_wrike_integrate`.`sa_wrike_project_detail` ON `sa_wrike_integrate`.`sa_wrike_project_list`.`id` = `sa_wrike_integrate`.`sa_wrike_project_detail`.`project_id` LEFT JOIN `sa_dashboard_kpi`.`sa_user` ON `sa_dashboard_kpi`.`sa_user`.`so_number` = `sa_wrike_integrate`.`sa_wrike_project_list`.`no_so` WHERE `no_so`=%s GROUP BY `sa_wrike_integrate`.`sa_wrike_assignment`.`resource_email` ORDER BY `sa_wrike_integrate`.`sa_wrike_assignment`.`resource_email` ASC;",
    GetSQLValueString($so_number, "text")
);
$dtKPI = $DBWRIKE->get_sql($mysql);
?>
<table class="display" id="ProjectDetail" style="width:100%">
    <thead>
        <tr class="text-center">
            <th>Resource Name</th>
            <th>Project Support</th>
            <th>Productivity</th>
            <th>Role</th>
            <th>KPI Project Ideal</th>
            <th>KPI Project Actual</th>
            <th>KPI Project Ideal Resource Specific</th>
            <th>KPI Project Actual Resource Specific</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if($dtKPI[2]>0) 
        {
            do { 
                ?>
                <tr>
                    <?php
                    $tblname = "mst_employees";
                    $condition = 'email = "' . $dtKPI[0]['resource_email'] . '"';
                    $mdlname = 'HCM';
                    $DBHCM = get_conn($mdlname);
                    $employees = $DBHCM->get_data($tblname, $condition);
                    ?>
                    <td class="text-nowrap">
                        <img class='img-profile' src='data:image/jpeg;base64, <?php echo base64_encode($employees[0]['unitdrawing']); ?>' style='padding:2px; border:1px solid #e6e6e6; width:60px; height:80px;' title='<?php echo $employees[0]['employee_name']; ?>' />&nbsp;<?php echo $employees[0]['employee_name']; ?>
                    </td>
                    <td class="text-nowrap">
                        <?php
                        $mdlname = 'KPI_PROJECT';
                        $DBKPI = get_conn($mdlname);
                        $tblname = 'user';
                        $condition = 'so_number = "' . $so_number . '" AND Nama LIKE "%' . $dtKPI[0]['resource_email'] . '%"';
                        $KPIUser = $DBKPI->get_data($tblname, $condition);
                        echo ($KPIUser[0]['start_assignment']!='' ? date("d/m/Y", strtotime($KPIUser[0]['start_assignment'])) : "Empty") . '&nbsp;&nbsp;|&nbsp;&nbsp;' . ($KPIUser[0]['start_assignment']!='' ? date("d/m/Y", strtotime($KPIUser[0]['start_assignment'])) : "Empty") . "<br>";
                        echo ($KPIUser[0]['progress_start']!='' ? $KPIUser[0]['progress_start'] : "Empty") . '&nbsp;&nbsp;|&nbsp;&nbsp;' . ($KPIUser[0]['progress_end']!='' ? $KPIUser[0]['progress_end'] : "Empty") . "<br>";
                        echo ($KPIUser[0]['project_support']!='' ? $KPIUser[0]['progress_start'] : "Empty");
                        ?>
                    </td>
                    <?php
                    $mysql = "SELECT COUNT(`sa_wrike_task`.`task_id`) AS `total_task`, COUNT(`sa_wrike_timelog`.`task_id`) AS `update_task` FROM `sa_wrike_project_list` LEFT JOIN `sa_wrike_assignment` ON `sa_wrike_project_list`.`id` = `sa_wrike_assignment`.`project_id` LEFT JOIN `sa_wrike_task` ON `sa_wrike_task`.`task_id` = `sa_wrike_assignment`.`task_id` AND `sa_wrike_task`.`project_id` = `sa_wrike_project_list`.`id` LEFT JOIN `sa_wrike_timelog` ON `sa_wrike_timelog`.`task_id` = `sa_wrike_assignment`.`task_id` AND `sa_wrike_timelog`.`project_id` = `sa_wrike_project_list`.`id` WHERE `sa_wrike_project_list`.`no_so` = '" . $so_number . "' AND `sa_wrike_assignment`.`resource_email` LIKE '" . $dtKPI[0]['resource_email'] . "'; ";
                    
                    $productivity = $DBWRIKE->get_sql($mysql);
                    ?>
                    <td><?php echo $productivity[0]['total_task'] . '&nbsp;&nbsp;|&nbsp;&nbsp;' . $productivity[0]['update_task']; ?></td>
                    <td><?php echo $KPIUser[0]['role']; ?></td>
                    <td><?php echo number_format($KPIUser[0]['nilai_ideal']*100, 2); ?></td>
                    <td><?php echo number_format($KPIUser[0]['nilai_aktual']*100, 2); ?></td>
                    <td><?php echo number_format($KPIUser[0]['nilai_akhir_ideal']*100, 2); ?></td>
                    <td><?php echo number_format($KPIUser[0]['nilai_akhir_aktual']*100, 2); ?></td>
                </tr>
                <?php 
            } while($dtKPI[0]=$dtKPI[1]->fetch_assoc()); 
        }
        ?>
    </tbody>
</table>