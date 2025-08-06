<script>
$(document).ready(function () {
    var tblProject = $('#Resources').DataTable({
        dom: 'Bfrtip',
        "columnDefs": [
            {
            "targets": [1,2,3,4],
            "className": 'dt-body-center'
            }
        ],
        buttons:[
            {
            extend: 'excelHtml5',
            text: "<i class='fa-solid fa-file-excel'></i>",
            title: 'KPI_Summary_'+<?php echo date("YmdGis"); ?>,
            messageTop: 'KPI Summary berdasarkan data type project dan status review.',
            messageBottom: "bottom"
            }
        ],
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

$mysql = sprintf(
    // "SELECT `summary_id`, `resource_name`, `" . $type ."_not_reviewed` AS `not_reviewed`, `" . $type ."_reviewed` AS `reviewed`, `" . $type ."_period` AS `period` FROM `sa_kpi_summary` WHERE `" . $type ."_period` = %s " . ($year==date("Y") ? " OR `" . $type ."_period` = 0" : ""),
    //"SELECT `summary_id`, `resource_name`,`implementation_not_reviewed`, `maintenance_not_reviewed`, (`implementation_not_reviewed` + `maintenance_not_reviewed`) AS `not_reviewed`, `implementation_reviewed`, `maintenance_reviewed`, (`implementation_reviewed` + `maintenance_reviewed`) AS `reviewed`, `implementation_period` AS `period` FROM `sa_kpi_summary` WHERE `" . $type ."_period` = %s " . ($year==date("Y") ? " OR `" . $type ."_period` = 0" : ""),
    "SELECT * FROM `sa_kpi_summary` WHERE `" . $type ."_period` = %s " . ($year==date("Y") ? " OR `" . $type ."_period` = 0" : ""),
    GetSQLValueString($year, "int")
);

$rsKPISummary = $DBKPI->get_sql($mysql);
    ?>
<div class="card">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <div>
            <h6 class="m-0 font-weight-bold text-primary">KPI Summary</h6>
        </div>
        <div></div>
    </div>
    <div class="card-body">
        <!-- <div class="row mb-2">
            <label class="col-sm-2">Project Type</label>
            <div class="col-sm-2">
                <select class="form-select" aria-label="Default select example" id="type" onchange="loadpage()">
                    <option value="implementation" <?php //echo ((isset($_GET['type']) && $_GET['type']=="implementation") ? "Selected" : ""); ?>>Implementation</option>
                    <option value="maintenance" <?php //echo ((isset($_GET['type']) && $_GET['type']=="maintenance") ? "Selected" : ""); ?>>Maintenance</option>
                </select>
            </div>
        </div> -->
        <div class="row mb-3">
            <label class="col-sm-2">Review Period</label>
            <div class="col-sm-1">
                <select class="form-select" aria-label="Default select example" id="year" onchange="loadpage()">
                    <?php
                    for($year=date("Y"); $year>=2022; $year--)
                    {
                        ?>
                        <option value="<?php echo $year; ?>" <?php echo ((isset($_GET['year']) && $_GET['year']==$year) ? "Selected" : ""); ?>><?php echo $year; ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="display" id="Resources">
                <thead class="table-light">
                    <tr class="text-center">
                        <th rowspan="2">Resource Name</th>
                        <th colspan="2">Implementation</th>
                        <th colspan="2">Maintenance</th>
                    </tr>
                    <tr class="text-center">
                        <th>Not Yet Reviewed</th>
                        <th>Reviewed</th>
                        <th>Not Yet Reviewed</th>
                        <th>Reviewed</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if($rsKPISummary[2]>0)
                    {
                        do { 
                            ?>
                            <tr class="text-center">
                                <td class="text-left"><?php echo $rsKPISummary[0]['resource_name']; ?></td>
                                <td><?php echo $rsKPISummary[0]['implementation_not_reviewed']; ?></td>
                                <td><?php echo $rsKPISummary[0]['implementation_reviewed']; ?></td>
                                <td><?php echo $rsKPISummary[0]['maintenance_not_reviewed']; ?></td>
                                <td><?php echo $rsKPISummary[0]['maintenance_reviewed']; ?></td>
                            </tr>
                            <?php 
                        } while($rsKPISummary[0]=$rsKPISummary[1]->fetch_assoc()); 
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function loadpage()
    {
        window.location.href="index.php?mod=kpi_project&sub=dashboard_kpi_summary&year="+document.getElementById('year').value;
    }
</script>