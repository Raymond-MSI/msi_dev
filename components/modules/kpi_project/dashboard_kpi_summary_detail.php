<?php 
include_once("../../../applications/connections/connections.php"); 
include_once("../../../components/classes/func_databases_v3.php");
include_once("../../../components/classes/func_hcm.php");
include_once("../../../components/classes/func_modules.php" );
include_once("../../../components/classes/func_cfg_web.php");
$DB = new WebConfig($hostname, $username, $password, $database);
?>

<style>
tr.group,
tr.group:hover {
    background-color: #ddd !important;
    font-weight: bold;
}
</style>

<script>
$(document).ready(function() {
    // var groupColumn = 0;

    // var table = $('#KPISummary').DataTable({
    //     dom: 'rtip',
    //     scrollX: true,
    //     ordering: false,
    //     buttons:[
    //         {
    //         extend: 'excelHtml5',
    //         text: "<i class='fa-solid fa-file-excel'></i>",
    //         title: 'KPI_Resources_'+<?php echo date("YmdGis"); ?>
    //         }
    //     ],
    //     order: [
    //         [groupColumn, "asc"],
    //     ],
    //     columnDefs: [
    //         { 
    //             visible: false, 
    //             targets: [groupColumn] 
    //         }
    //     ],
    //     drawCallback: function (settings) {
    //         var api = this.api();
    //         var rows = api.rows({ page: 'current' }).nodes();
    //         var last = null;

    //         api.column(groupColumn, { page: 'current' })
    //             .data()
    //             .each(function (group, i) {
    //                 if (last !== group) {
    //                     $(rows)
    //                         .eq(i)
    //                         .before(
    //                             '<tr class="group"><td class="pt-1 pb-1" colspan="2">' +
    //                                 group +
    //                                 '</td></tr>'
    //                         );

    //                     last = group;
    //                 }
    //             });
    //     },

    // });

    // // Order by the grouping
    // $('#KPISummary tbody').on('click', 'tr.group', function () {
    //     var currentOrder = table.order()[0];
    //     if (currentOrder[0] === groupColumn && currentOrder[1] === 'asc') {
    //         table.order([groupColumn, 'desc']).draw();
    //     }
    //     else {
    //         table.order([groupColumn, 'asc']).draw();
    //     }
    // });
    var groupColumn = 0;
    var table = $('#KPISummary').DataTable({
        dom: 'rtip',
        columnDefs: [{
                visible: false,
                targets: groupColumn
            },
            {
                "targets": [2],
                "className": 'dt-body-center'
            },
        ],
        order: [
            [groupColumn, 'asc'],
            [1, 'asc']
        ],
        buttons: [{
            extend: 'excelHtml5',
            text: "<i class='fa-solid fa-file-excel'></i>",
            title: 'KPI_Resources_' + <?php echo date("YmdGis"); ?>
        }],
        drawCallback: function(settings) {
            var api = this.api();
            var rows = api.rows({
                page: 'current'
            }).nodes();
            var last = null;

            api.column(groupColumn, {
                    page: 'current'
                })
                .data()
                .each(function(group, i) {
                    if (last !== group) {
                        $(rows)
                            .eq(i)
                            .before(
                                '<tr class="group"><td colspan="5">' +
                                group +
                                '</td></tr>'
                            );

                        last = group;
                    }
                });
        }
    });

    // Order by the grouping
    $('#KPISummary tbody').on('click', 'tr.group', function() {
        var currentOrder = table.order()[0];
        if (currentOrder[0] === groupColumn && currentOrder[1] === 'asc') {
            table.order([groupColumn, 'desc']).draw();
        } else {
            table.order([groupColumn, 'asc']).draw();
        }
    });
});
</script>

<?php
$DBKPI = get_conn("KPI_PROJECT");
$year = date("Y");
if(isset($_GET['periode_review']))
{
    $year = $_GET['periode_review'];
}

$type = "implementation";
if(isset($_GET['type']) && $_GET['type']=="maintenance")
{
    $type = "maintenance";
}
// $mysql = sprintf(
//     "SELECT
//         DISTINCT
//         `a`.`Nama` AS `resource_name`,
//         `a`.`project_support`,
//         `a`.`project_code`,
//         `a`.`so_number`,
//         `a`.`order_number`,
//         `a`.`customer_name`,
//         `a`.`project_name`,
//         `a`.`project_type`,
//         `a`.`role`,
//         `a`.`kpi_status`,
//         `a`.`periode` AS `periode_project`
//     FROM
//         `sa_user` `a`
//     LEFT JOIN `sa_log_board` `b` ON
//         `a`.`so_number` = `b`.`so_number`
//     WHERE
//         (
//             LEFT(`b`.`date`, 4) LIKE %s
//             OR `a`.`kpi_status` = 'Not Yet Reviewed'
//         )
//         AND `a`.`Nama` LIKE %s;",
//         GetSQLValueString($year, "int"),
//         GetSQLValueString("%<" . $_GET['resource'] . ">%", "text")
//     );
$mysql = sprintf(
    "SELECT
        DISTINCT
        `a`.`Nama` AS `resource_name`,
        `a`.`project_support`,
        `a`.`project_code`,
        `a`.`so_number`,
        `a`.`order_number`,
        `a`.`customer_name`,
        `a`.`project_name`,
        `a`.`project_type`,
        `a`.`role`,
        `a`.`kpi_status`,
        `a`.`periode` AS `periode_project`
    FROM
        `sa_user` `a`
    WHERE
        (
            a.periode LIKE %s
            OR `a`.`kpi_status` = 'Not Yet Reviewed'
	    OR `a`.`kpi_status` = 'Reviewed'
        )
        AND `a`.`Nama` LIKE %s;",
        GetSQLValueString($year, "int"),
        GetSQLValueString("%<" . $_GET['resource'] . ">%", "text")
    );
$rsSummaryDetail = $DBKPI->get_sql($mysql);
?>

<table class="display" id="KPISummary" width="100%">
    <thead>
        <tr class=" text-danger-emphasis bg-danger-subtle">
            <th>KPI Status</th>
            <th>Project Information</th>
            <th>Periode Project</th>
            <th>Role Resource</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if($rsSummaryDetail[2]>0)
        {
            do {
                ?>
        <tr>
            <td>
                <?php
                        if($rsSummaryDetail[0]['project_type']=="MSI Project Implementation")
                        {
                            $ProjectType = "Implementation";
                        } else
                        if($rsSummaryDetail[0]['project_type']=="MSI Project Maintenance")
                        {
                            $ProjectType = "Maintenance";
                        }
                        echo $rsSummaryDetail[0]['resource_name'] . " | " . $ProjectType . " | " . $rsSummaryDetail[0]['kpi_status'];
                        ?>
            </td>
            <td>
                <?php
                        echo "<span class='fw-bold'>" . $rsSummaryDetail[0]['project_code'] . " | " . $rsSummaryDetail[0]['so_number'] . " | " . $rsSummaryDetail[0]['order_number'] . "</span><br/>";
                        echo "<span style='font-size:12px'>";
                        echo "<b>Customet Name :</b> " . $rsSummaryDetail[0]['customer_name'] . " | ";
                        echo "<b>Project Name :</b> " . $rsSummaryDetail[0]['project_name'];
                        echo "</span>";
                        ?>
            </td>
            <td>
                <?php echo $rsSummaryDetail[0]['periode_project']; ?>
            </td>
            <td>
                <?php
                        echo $rsSummaryDetail[0]['role'];
                        ?>
            </td>
        </tr>
        <?php
            } while($rsSummaryDetail[0]=$rsSummaryDetail[1]->fetch_assoc());
        }
        ?>
    </tbody>
</table>