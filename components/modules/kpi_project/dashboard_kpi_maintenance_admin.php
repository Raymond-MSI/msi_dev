<?php
$DBKPI = get_conn("DASHBOARD_KPI");
$DBWR = get_conn("WRIKE_INTEGRATE");
if (isset($_GET['project_type'])) {
    $project_type = $_GET['project_type'];
} else {
    $project_type = "Maintenance";
}

if (isset($_GET['periode_so'])) {
    $periode_so = $_GET['periode_so'];
} else {
    $periode_so = DATE("Y-m-d");
}

if (isset($_GET['periode_review'])) {
    $periode_review = $_GET['periode_review'];
} else {
    $periode_review = date("Y");
}

if (isset($_GET['status_review'])) {
    $kpi_status = $_GET['status_review'];
} else {
    $kpi_status = 'Reviewed';
}

?>
<!DOCTYPE HTML>
<html>
<style>
    .table-responsive {
        max-height: 400px;
        /* Adjust as necessary */
        overflow-y: auto;
        overflow-x: auto;
    }

    #ProjectDetail {
        width: 100%;
        border-collapse: collapse;
    }

    #ProjectDetail th,
    #ProjectDetail td {
        border: 1px solid #ddd;
        /* Add borders to the table cells */
        padding: 8px;
        text-align: left;
    }

    #ProjectDetail th {
        background-color: #f2f2f2;
        text-align: center;
    }

    #ProjectDetail2 {
        width: 100%;
        border-collapse: collapse;
    }

    #ProjectDetail2 th,
    #ProjectDetail2 td {
        border: 1px solid #ddd;
        /* Add borders to the table cells */
        padding: 8px;
        text-align: left;
    }

    #ProjectDetail2 th {
        background-color: #f2f2f2;
        text-align: center;
    }

    #ProjectDetail3 {
        width: 100%;
        border-collapse: collapse;
    }

    #ProjectDetail3 th,
    #ProjectDetail3 td {
        border: 1px solid #ddd;
        /* Add borders to the table cells */
        padding: 8px;
        text-align: left;
    }

    #ProjectDetail3 th {
        background-color: #f2f2f2;
        text-align: center;
    }

    .modal-lg {
        max-width: 90%;
    }
</style>
<?php
function content2($title, $value, $listData = array(), $color = "danger", $width = "12", $tiptool = "", $fontsize = "h4", $py = "2")
{
?>
    <!-- <div class="col border-left-<?php //echo $color; 
                                        ?> rounded shadow p-3 ml-3 mr-3 border-left h-100 py-2"> -->
    <div class="col border-left-<?php echo $color; ?> p-3 ml-3 mr-3 h-100 py-<?php echo $py; ?>">
        <span class="text-<?php echo $color; ?> text-uppercase text-xs font-weight-bold mb-1"><?php echo $title; ?></span><br>
        <span class="<?php echo $fontsize; ?> mb-0 mr-3 font-weight-bold text-gray-800y d-flex flex-row align-items-center justify-content-between">
            <?php
            if (count($listData) > 0) {
            ?>
                <div class="dropdown no-arrow" id="<?php echo $tiptool; ?>">
                    <a class="dropdown-toggle text-reset text-decoration-none" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo $value; ?></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-left shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Select item:</div>
                        <?php
                        for ($i = 0; $i < count($listData); $i++) {
                            $resource = "";
                            if (isset($_GET['resource'])) {
                                $resource = "&resource=" . $_GET['resource'];
                            }
                        ?>
                            <a class="dropdown-item" href="index.php?mod=kpi_project&sub=<?php echo $_GET['sub'] . $listData[$i]['link'] . $resource; ?>"><?php echo $listData[$i]['value']; ?></a>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            <?php
            } else {
                echo $value;
            }
            ?>
        </span>
    </div>
<?php
}
function getlink($sub, $value)
{
    $link = "";
    if (isset($_GET["project_type"]) && $sub != "project_type") {
        $link .= "&project_type=" . $_GET["project_type"];
    } else
    if ($sub == "project_type") {
        $link .= "&project_type=$value";
    }

    if (isset($_GET["periode_so"]) && $sub != "periode_so") {
        $link .= "&periode_so=" . $_GET["periode_so"];
    } else
    if ($sub == "periode_so") {
        $link .= "&periode_so=$value";
    }

    $review = false;
    if (isset($_GET["status_review"]) && $sub != "status_review") {
        $link .= "&status_review=" . $_GET["status_review"];
        if ($_GET['status_review'] == "Reviewed") {
            $review = true;
        }
    } else
    if ($sub == "status_review") {
        $link .= "&status_review=$value";
        if ($value == "Reviewed") {
            $review = true;
        }
    }

    if ($review) {
        if (isset($_GET["periode_review"]) && $sub != "periode_review") {
            $link .= "&periode_review=" . $_GET["periode_review"];
        } else
        if ($sub == "periode_review") {
            $link .= "&periode_review=" . $value;
        }
    }

    return $link;
}
?>
<form>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#informationx" type="button" role="tab" aria-controls="generalinformation" aria-selected="false" style="color: black;">Project
                Maintenance</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="rekappm-tab" data-bs-toggle="tab" data-bs-target="#rekappmx" type="button" role="tab" aria-controls="rekappm" aria-selected="false" style="color: black;">Rekap PM</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="rekapengineer-tab" data-bs-toggle="tab" data-bs-target="#rekapengineerx" type="button" role="tab" aria-controls="rekapengineer" aria-selected="false" style="color: black;">Rekap
                Engineer</button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <!-- TAB Change Request Type -->
        <div class="tab-pane fade show active" id="informationx" role="tabpanel" aria-labelledby="crtype-tab">
            <div class="card shadow mb-4">
                <!-- Card Body -->
                <div class="card-body">
                    <div class="row">

                        <head>
                            <div class="row mb-3">
                                <div class="col-lg-3">
                                </div>
                                <div class="col-lg-9">
                                    <div class="d-flex flex-row">
                                        <div class="">
                                            <?php
                                            $link = array();
                                            $xxxy = array();
                                            $xxx = array('link' => getlink("project_type", "All Project"), 'value' => 'All Project');
                                            array_push($link, $xxx);
                                            $xxx = array('link' => getlink("project_type", "Implementation"), 'value' => 'Implementation');
                                            array_push($link, $xxx);
                                            $xxx = array('link' => getlink('project_type', 'Maintenance'), 'value' => 'Maintenance');
                                            array_push($link, $xxx);
                                            content2("Project Type", $project_type, $link,  "none", "2", "ProjectType", "h6", "0");
                                            ?>
                                        </div>
                                        <div class="">
                                            <div class="row g-2">
                                                <label for="inputPassword" class="col-auto col-form-label col-form-label-sm">From
                                                    :</label>
                                                <div class="row mb-2">
                                                    <div class="col-auto">
                                                        <div class="input-group date">
                                                            <div class="input-group-addon">
                                                                <span class="glyphicon glyphicon-th"></span>
                                                            </div>
                                                            <input type="date" class="form-control form-control-sm" id="start_period" name="start_period" placeholder="Start Date." value="<?php echo date("d-M-Y"); ?>" onchange="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="">
                                            <div class="row g-2">
                                                <label for="inputPassword" class="col-auto col-form-label col-form-label-sm">To</label>
                                                <div class="row mb-2">
                                                    <div class="col-auto">
                                                        <div class="input-group date">
                                                            <div class="input-group-addon">
                                                                <span class="glyphicon glyphicon-th"></span>
                                                            </div>
                                                            <input type="date" class="form-control form-control-sm" id="end_period" name="end_period" placeholder="End Date." value="<?php echo date("d-M-Y"); ?>" onchange="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $get_project = $DBKPI->get_sql("SELECT COUNT(project_code) as jumlah_open FROM sa_kpi_so_wr WHERE project_type LIKE '%Maintenance%' AND status_wr='Open'");
                            $get_project2 = $DBKPI->get_sql("SELECT COUNT(project_code) as jumlah_closed FROM sa_kpi_so_wr WHERE project_type LIKE '%Maintenance%' AND status_wr LIKE '%Close%'");
                            $get_project3 = $DBKPI->get_sqlV2("SELECT a.*,b.entry_by as project_manager FROM sa_kpi_so_wr a left join sa_master_maintenance_date b ON a.order_number=b.order_number WHERE a.project_type LIKE '%Maintenance%' AND (a.status_wr='Open' OR a.status_wr LIKE '%Close%')"); ?>
                            <script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js">
                            </script>
                            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
                            <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
                            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js">
                            </script>
                            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
                            <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js">
                            </script>
                            <script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js">
                            </script>
                            <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
                            <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
                            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
                            <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
                            <!-- DataTables CSS -->
                            <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
                            <!-- Buttons CSS -->
                            <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
                            <!-- Font Awesome CSS (for icons) -->
                            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

                            <!-- jQuery -->
                            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                            <!-- DataTables JS -->
                            <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
                            <!-- Buttons JS -->
                            <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js">
                            </script>
                            <!-- JSZip (required for Excel export) -->
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
                            <!-- Excel Button JS -->
                            <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>

                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $('#ProjectDetail').DataTable({
                                        "dom": 'Blfrtip',
                                        "pageLength": 10,
                                        "paging": true,
                                        "searching": true,
                                        "info": true,
                                        "buttons": [{
                                            extend: 'excelHtml5',
                                            text: "<i class='fa-solid fa-file-excel'></i>",
                                            title: 'KPI_Project_List_' +
                                                <?php echo date("YmdGis"); ?>,
                                        }]
                                    });
                                });
                            </script>
                        </head>
                        <style>
                            #chart-container {
                                display: flex;
                                justify-content: center;
                                align-items: center;
                                width: 100%;
                                height: 100%;
                            }

                            #myPieChart {
                                width: 400px !important;
                                height: 400px !important;
                            }
                        </style>

                        <body>
                            <div class="row">
                                <div class="row mb-3">
                                    <div id="chart-container">
                                        <canvas id="myPieChart"></canvas>
                                    </div>
                                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                                    <script>
                                        // Ensure the script runs after the document is fully loaded
                                        document.addEventListener("DOMContentLoaded", function() {
                                            var ctx = document.getElementById('myPieChart').getContext('2d');
                                            var myPieChart = new Chart(ctx, {
                                                type: 'pie',
                                                data: {
                                                    labels: ['Open', 'Closed'], // Labels for each slice
                                                    datasets: [{
                                                        data: [
                                                            <?php
                                                            $jumlah_open = $get_project[0]['jumlah_open'];
                                                            echo $jumlah_open;
                                                            ?>, <?php
                                                                $jumlah_close = $get_project2[0]['jumlah_closed'];
                                                                echo $jumlah_close;
                                                                ?>
                                                        ], // Data points
                                                        backgroundColor: [
                                                            'rgba(255, 99, 132, 0.2)',
                                                            'rgba(54, 162, 235, 0.2)'
                                                        ],
                                                        borderColor: [
                                                            'rgba(255, 99, 132, 1)',
                                                            'rgba(54, 162, 235, 1)'
                                                        ],
                                                        borderWidth: 1
                                                    }]
                                                },
                                                options: {
                                                    responsive: true,
                                                    plugins: {
                                                        legend: {
                                                            position: 'top',
                                                        },
                                                        title: {
                                                            display: true,
                                                            text: 'Project Maintenance'
                                                        }
                                                    }
                                                }
                                            });
                                        });
                                    </script>
                                    <!-- <div id="chartContainer" style="height: 300px; width: 100%;"></div> -->
                                </div>
                            </div>
                            <div class="row">
                                <div class="row mb-10">
                                    <table class="display" id="ProjectDetail" style="width:100%">
                                        <thead>
                                            <tr class="text-center">
                                                <th>No</th>
                                                <th>Nama PM</th>
                                                <th>Nama Project</th>
                                                <th>Nama Customer</th>
                                                <th>Type</th>
                                                <th>Periode</th>
                                                <th>Nilai KPI</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $x = 0;
                                            while ($row = $get_project3[1]->fetch_assoc()) {
                                                $x++;
                                                $customer = $row['customer_name'];
                                                $project_name = $row['project_name'];
                                                $service_type = $row['service_type'];
                                                $start = $row['start_assignment'];
                                                $end = $row['end_assignment'];
                                                $total_cte = $row['total_cte'];
                                                $cte = $row['cte'];
                                                $order_number = $row['order_number'];
                                                $renewal_category = $row['renewal_category'];
                                                $renewal_kpi = $row['renewal_kpi'];
                                                $renewal_actual = 10 - $renewal_kpi;
                                                $time_category = $row['time_category'];
                                                $time_kpi = $row['time_kpi'];
                                                $time_actual = 20 - $time_kpi;
                                                $error_category = $row['error_category'];
                                                $error_kpi = $row['error_kpi'];
                                                $error_actual = 20 - $error_kpi;
                                                $compliance_category = $row['data_compliance_category'];
                                                $compliance_kpi = $row['data_compliance_kpi'];
                                                $compliance_actual = 10 - $compliance_kpi;
                                                $backup_category = $row['backup_config_category'];
                                                $backup_kpi = $row['backup_config_kpi'];
                                                $backup_actual = 10 - $backup_kpi;
                                                $adoption_category = $row['adoption_category'];
                                                $adoption_kpi = $row['adoption_kpi'];
                                                $adoption_actual = 30 - $adoption_kpi;
                                                $namaPM = $row['project_manager'];
                                            ?>
                                                <tr data-toggle="modal" data-target="#myModal" data-renewalactual="<?php echo $renewal_actual; ?>" data-timeactual="<?php echo $time_actual; ?>" data-erroractual="<?php echo $error_actual; ?>" data-complianceactual="<?php echo $compliance_actual; ?>" data-backupactual="<?php echo $backup_actual; ?>" data-adoptionactual="<?php echo $adoption_actual; ?>" data-totalcte="<?php echo $total_cte; ?>" data-renewalcategory="<?php echo $renewal_category; ?>" data-renewalkpi="<?php echo $renewal_kpi; ?>" data-timecategory="<?php echo $time_category; ?>" data-timekpi="<?php echo $time_kpi; ?>" data-errorcategory="<?php echo $error_category; ?>" data-errorkpi="<?php echo $error_kpi; ?>" data-compliancecategory="<?php echo $compliance_category; ?>" data-compliancekpi="<?php echo $compliance_kpi; ?>" data-backupcategory="<?php echo $backup_category; ?>" data-backupkpi="<?php echo $backup_kpi; ?>" data-adoptioncategory="<?php echo $adoption_category; ?>" data-adoptionkpi="<?php echo $adoption_kpi; ?>" data-customer="<?php echo $customer; ?>" data-project="<?php echo $project_name; ?>" data-type="<?php echo $service_type; ?>" data-period="<?php echo $start . ' ~ ' . $end; ?>" data-kpi="<?php echo $total_cte; ?>" data-order="<?php echo $order_number; ?>">
                                                    <td><?php echo $x; ?></td>
                                                    <td><?php echo $namaPM; ?></td>
                                                    <td><?php echo $project_name; ?></td>
                                                    <td><?php echo $customer; ?></td>
                                                    <td><?php echo $service_type; ?></td>
                                                    <td><?php echo $start . " ~ " . $end; ?></td>
                                                    <td><?php echo $total_cte; ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <link rel="stylesheet" href="path/to/bootstrap.css">
                            <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
                            <script src="path/to/jquery.js"></script>
                            <script src="path/to/bootstrap.js"></script>
                            <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
                            <div class="modal fade" id="myModal" role="dialog">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title"></h4>
                                        </div>
                                        <div class="modal-body">
                                            <h4 class="modal-title">Project Profile</h4>
                                            <div class="table-responsive">
                                                <table class="display table table-bordered" id="ProjectDetail" style="width:100%">
                                                    <thead>
                                                        <tr class="text-center">
                                                            <th>Nama Customer</th>
                                                            <th>Nama Project</th>
                                                            <th>Type</th>
                                                            <th>Start-End</th>
                                                            <th colspan="2">Nilai Project</th>
                                                            <th>Member</th>
                                                            <th>Roles</th>
                                                            <th>Start-End Member</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><span id="modalCustomer"></td>
                                                            <td><span id="modalProject"></td>
                                                            <td><span id="modalType"></td>
                                                            <td><span id="modalPeriod"></td>
                                                            <td colspan="2"><span id="modalValue"></td>
                                                            <td><span id="modalResources"></td>
                                                            <td><span id="modalProjectRoles"></span></td>
                                                            <td><span id="modalMembersPeriod"></span></td>
                                                            <td><span id="modalMembersStatus"></span></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <script>
                                jQuery(function() {

                                    jQuery('#start_period').datetimepicker({
                                        format: 'd-M-Y',
                                        timepicker: false,
                                        onShow: function(ct) {
                                            this.setOptions({})
                                        }
                                    });
                                    jQuery('#end_period').datetimepicker({
                                        format: 'd-M-Y',
                                        timepicker: false,
                                        onShow: function(ct) {
                                            this.setOptions({})
                                        }
                                    });

                                });
                            </script>
                        </body>
                        <script>
                            $('#myModal').on('show.bs.modal', function(event) {
                                var button = $(event.relatedTarget);
                                var customer = button.data('customer');
                                var project = button.data('project');
                                var type = button.data('type');
                                var period = button.data('period');
                                var kpi = button.data('kpi');
                                var order = button.data('order');
                                var renewalcategory = button.data('renewalcategory');
                                var renewalkpi = button.data('renewalkpi');
                                var timecategory = button.data('timecategory');
                                var timekpi = button.data('timekpi');
                                var errorcategory = button.data('errorcategory');
                                var errorkpi = button.data('errorkpi');
                                var compliancecategory = button.data('compliancecategory');
                                var compliancekpi = button.data('compliancekpi');
                                var backupcategory = button.data('backupcategory');
                                var backupkpi = button.data('backupkpi');
                                var adoptioncategory = button.data('adoptioncategory');
                                var adoptionkpi = button.data('adoptionkpi');
                                var cte = button.data('totalcte');
                                var renewalactual = button.data('renewalactual');
                                var timeactual = button.data('timeactual');
                                var erroractual = button.data('erroractual');
                                var complianceactual = button.data('complianceactual');
                                var backupactual = button.data('backupactual');
                                var adoptionactual = button.data('adoptionactual');

                                var modal = $(this);
                                modal.find('#modalCustomer').text(customer);
                                modal.find('#modalProject').text(project);
                                modal.find('#modalType').text(type);
                                modal.find('#modalPeriod').text(period);
                                modal.find('#modalKpi').text(kpi);
                                modal.find('#modalOrder').text(order);
                                modal.find('#modalRenewalCategory').text(renewalcategory);
                                modal.find('#modalRenewalKpi').text(renewalkpi);
                                modal.find('#modalTimeCategory').text(timecategory);
                                modal.find('#modalTimeKpi').text(timekpi);
                                modal.find('#modalErrorCategory').text(errorcategory);
                                modal.find('#modalErrorKpi').text(errorkpi);
                                modal.find('#modalComplianceCategory').text(compliancecategory);
                                modal.find('#modalComplianceKpi').text(compliancekpi);
                                modal.find('#modalBackupCategory').text(backupcategory);
                                modal.find('#modalBackupKpi').text(backupkpi);
                                modal.find('#modalAdoptionCategory').text(adoptioncategory);
                                modal.find('#modalAdoptionKpi').text(adoptionkpi);
                                modal.find('#modalCTE').text(cte);
                                modal.find('#modalRenewalActual').text(renewalactual);
                                modal.find('#modalTimeActual').text(timeactual);
                                modal.find('#modalErrorActual').text(erroractual);
                                modal.find('#modalComplianceActual').text(complianceactual);
                                modal.find('#modalBackupActual').text(backupactual);
                                modal.find('#modalAdoptionActual').text(adoptionactual);
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade show active" id="rekappmx" role="tabpanel" aria-labelledby="crtype-tab">
            <div class="card shadow mb-4">
                <!-- Card Body -->
                <div class="card-body">
                    <div class="row">

                        <head>
                            <div class="row mb-3">
                                <div class="col-lg-3">
                                </div>
                                <div class="col-lg-9">
                                    <div class="d-flex flex-row">
                                        <div class="">
                                            <?php
                                            $link = array();
                                            $xxxy = array();
                                            $xxx = array('link' => getlink("project_type", "All Project"), 'value' => 'All Project');
                                            array_push($link, $xxx);
                                            $xxx = array('link' => getlink("project_type", "Implementation"), 'value' => 'Implementation');
                                            array_push($link, $xxx);
                                            $xxx = array('link' => getlink('project_type', 'Maintenance'), 'value' => 'Maintenance');
                                            array_push($link, $xxx);
                                            content2("Project Type", $project_type, $link,  "none", "2", "ProjectType", "h6", "0");
                                            ?>
                                        </div>
                                        <div class="">
                                            <div class="row g-2">
                                                <label for="inputPassword" class="col-auto col-form-label col-form-label-sm">From
                                                    :</label>
                                                <div class="row mb-2">
                                                    <div class="col-auto">
                                                        <div class="input-group date">
                                                            <div class="input-group-addon">
                                                                <span class="glyphicon glyphicon-th"></span>
                                                            </div>
                                                            <input type="date" class="form-control form-control-sm" id="start_period" name="start_period" placeholder="Start Date." value="<?php echo date("d-M-Y"); ?>" onchange="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="">
                                            <div class="row g-2">
                                                <label for="inputPassword" class="col-auto col-form-label col-form-label-sm">To</label>
                                                <div class="row mb-2">
                                                    <div class="col-auto">
                                                        <div class="input-group date">
                                                            <div class="input-group-addon">
                                                                <span class="glyphicon glyphicon-th"></span>
                                                            </div>
                                                            <input type="date" class="form-control form-control-sm" id="end_period" name="end_period" placeholder="End Date." value="<?php echo date("d-M-Y"); ?>" onchange="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $get_project = $DBKPI->get_sqlV2("SELECT SUM(a.cte) as jumlah_cte,Count(a.project_code) jumlah_project,a.resource_email,b.status_wr FROM sa_user a left join sa_kpi_so_wr b ON a.order_number=b.order_number WHERE a.role='PIC Maintenance' AND a.project_type LIKE '%Maintenance%' AND b.status_wr='Closed' GROUP BY a.resource_email LIMIT 10");
                            $get_project2 = $DBKPI->get_sqlV2("SELECT COUNT(a.cte) as jumlah_cte,Count(a.project_code) jumlah_project,a.resource_email,b.status_wr FROM sa_user a left join sa_kpi_so_wr b ON a.order_number=b.order_number WHERE a.role='PIC Maintenance' AND a.project_type LIKE '%Maintenance%' AND b.status_wr='Closed' GROUP BY a.resource_email LIMIT 10");
                            $get_project3 = $DBKPI->get_sqlV2("SELECT SUM(a.cte) as jumlah_cte,Count(a.project_code) jumlah_project,a.Nama,b.status_wr,a.order_number FROM sa_user a left join sa_kpi_so_wr b ON a.order_number=b.order_number WHERE a.role='PIC Maintenance' AND a.project_type LIKE '%Maintenance%' AND b.status_wr='Closed' GROUP BY a.Nama LIMIT 10"); ?>
                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $('#ProjectDetail2').DataTable({
                                        "dom": 'Blfrtip',
                                        "pageLength": 10,
                                        "paging": true,
                                        "searching": true,
                                        "info": true,
                                        "buttons": [{
                                            extend: 'excelHtml5',
                                            text: "<i class='fa-solid fa-file-excel'></i>",
                                            title: 'KPI_Project_List_' +
                                                <?php echo date("YmdGis"); ?>,
                                        }]
                                    });
                                });
                            </script>
                        </head>

                        <body>
                            <div class="row">
                                <div class="row mb-3">
                                    <div id="chartContainer" style="height: 300px; width: 100%;"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="row mb-10">
                                    <table class="display" id="ProjectDetail2" style="width:100%">
                                        <thead>
                                            <tr class="text-center">
                                                <th>No</th>
                                                <th>Nama PM</th>
                                                <th>Jumlah Terlibat didalam Project Closed</th>
                                                <th>Total Pencapaian KPI</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $x = 0;
                                            while ($row = $get_project3[1]->fetch_assoc()) {
                                                $x++;
                                                $nama = $row['Nama'];
                                                $jumlah_project = $row['jumlah_project'];
                                                $pencapaian_kpi = $row['jumlah_cte'] / $jumlah_project;
                                                $get = $DBKPI->get_sqlV2("SELECT a.* FROM sa_user a left join sa_kpi_so_wr b ON a.order_number=b.order_number WHERE a.Nama LIKE '%$nama%' AND b.status_wr LIKE '%Closed%' GROUP BY a.order_number");
                                                $project_name = '';
                                                $customer = '';
                                                $service_type = '';
                                                $result = '';
                                                $kpi = '';
                                                while ($rows = $get[1]->fetch_assoc()) {
                                                    $order_number = $rows['order_number'];
                                                    $project_name .= $rows['project_name'] . ", ";
                                                    $customer .= $rows['customer_name'] . ", ";
                                                    $kpi_so_wr = $DBKPI->get_sqlV2("SELECT * FROM sa_kpi_so_wr WHERE order_number='$order_number' AND project_type LIKE '%Maintenance%'");
                                                    $service_type .= $kpi_so_wr[0]['service_type'] . ", ";
                                                    $end_assignment = date_create($kpi_so_wr[0]['end_assignment']);
                                                    $start_assignment = date_create($kpi_so_wr[0]['start_assignment']);
                                                    $hasil = date_diff($end_assignment, $start_assignment);
                                                    $result .= $hasil->m . ", ";
                                                    $kpi .= $rows['cte'] . ", ";
                                                }
                                                $project_name = substr($project_name, 0, -2);
                                                $customer = substr($customer, 0, -2);
                                                $service_type = substr($service_type, 0, -2);
                                                $result = substr($result, 0, -2);
                                                $kpi = substr($kpi, 0, -2);
                                            ?>
                                                <tr data-toggle="modal" data-target="#myModalPM" data-kpi="<?php echo $kpi; ?>" data-type="<?php $service_type; ?>" data-periode="<?php echo $kpi_so_wr[0]['start_assignment'] . " ~ " . $kpi_so_wr[0]['end_assignment'] . "($result Bulan)"; ?>" data-customername="<?php echo $customer; ?>" data-projectname="<?php echo $project_name; ?>">
                                                    <td><?php echo $x; ?></td>
                                                    <td><?php echo $nama; ?></td>
                                                    <td><?php echo $jumlah_project; ?></td>
                                                    <td><?php echo $pencapaian_kpi; ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <link rel="stylesheet" href="path/to/bootstrap.css">
                            <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
                            <script src="path/to/jquery.js"></script>
                            <script src="path/to/bootstrap.js"></script>
                            <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
                            <div class="modal fade" id="myModalPM" role="dialog">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title"></h4>
                                        </div>
                                        <div class="modal-body">
                                            <h4 class="modal-title">Detail Project</h4>
                                            <div class="table-responsive">
                                                <table class="display table table-bordered" id="ProjectDetail2" style="width:100%">
                                                    <thead>
                                                        <tr class="text-center">
                                                            <th>Nama Project</th>
                                                            <th>Nama Customer</th>
                                                            <th>Type</th>
                                                            <th>Durasi Maintenance</th>
                                                            <th>Total Nilai KPI Pencapaian</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><span id="modalProject"></td>
                                                            <td><span id="modalCustomer"></td>
                                                            <td><span id="modalType"></td>
                                                            <td><span id="modalPeriod"></td>
                                                            <td><span id="modalKpi"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <script>
                                $(document).ready(function() {
                                    $('#ProjectDetail3').DataTable({
                                        "dom": 'Blfrtip',
                                        "pageLength": 10,
                                        "paging": true,
                                        "searching": true,
                                        "info": true,
                                        "buttons": [{
                                            extend: 'excelHtml5',
                                            text: "<i class='fa-solid fa-file-excel'></i>",
                                            title: 'KPI_Project_List_' +
                                                <?php echo date("YmdGis"); ?>,
                                        }]
                                    });
                                });
                                $('#myModalPM').on('show.bs.modal', function(event) {
                                    var button = $(event.relatedTarget);
                                    var customer = button.data('customername');
                                    var project = button.data('projectname');
                                    var type = button.data('type');
                                    var period = button.data('periode');
                                    var kpi = button.data('kpi');

                                    var modal = $(this);
                                    modal.find('#modalCustomer').text(customer);
                                    modal.find('#modalProject').text(project);
                                    modal.find('#modalType').text(type);
                                    modal.find('#modalPeriod').text(period);
                                    modal.find('#modalKpi').text(kpi);
                                });
                            </script>
                            <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
                            <script>
                                window.onload = function() {
                                    var chart = new CanvasJS.Chart("chartContainer", {
                                        theme: "light2",
                                        title: {
                                            text: "Dashboard KPI Maintenance"
                                        },
                                        data: [{ //dataSeries - first quarter
                                                type: "column",
                                                name: "Project Open",
                                                showInLegend: true,
                                                dataPoints: [
                                                    <?php
                                                    $isinya = '';
                                                    while ($row = $get_project[1]->fetch_assoc()) {
                                                        $jumlah_cte = $row['jumlah_cte'];
                                                        $jumlah_project = $row['jumlah_project'];
                                                        $rata_rata = $jumlah_cte / $jumlah_project;
                                                        $nama = $row['Nama'];
                                                        $nilainya = round($rata_rata);
                                                        $isinya .= "{ label: '$nama', y: $rata_rata },";
                                                    }
                                                    $isinya = substr($isinya, 0, -1);
                                                    echo $isinya;

                                                    ?>
                                                ]
                                            },
                                            { //dataSeries - second quarter
                                                type: "column",
                                                name: "Project Closed",
                                                showInLegend: true,
                                                dataPoints: [
                                                    <?php
                                                    $isinya2 = '';
                                                    while ($row = $get_project2[1]->fetch_assoc()) {
                                                        $jumlah_cte = $row['jumlah_cte'];
                                                        $jumlah_project = $row['jumlah_project'];
                                                        $rata_rata = $jumlah_cte / $jumlah_project;
                                                        $nama = $row['Nama'];
                                                        $isinya2 .= "{ label: '$nama', y: 100 },";
                                                    }
                                                    $isinya2 = substr($isinya2, 0, -1);
                                                    echo $isinya2;

                                                    ?>
                                                ]
                                            }
                                        ],
                                        axisY: {
                                            suffix: "",
                                            maximum: 100 // Batasan nilai maksimum sumbu Y
                                        }
                                    });
                                    chart.render();
                                }
                                jQuery(function() {

                                    jQuery('#start_period').datetimepicker({
                                        format: 'd-M-Y',
                                        timepicker: false,
                                        onShow: function(ct) {
                                            this.setOptions({})
                                        }
                                    });
                                    jQuery('#end_period').datetimepicker({
                                        format: 'd-M-Y',
                                        timepicker: false,
                                        onShow: function(ct) {
                                            this.setOptions({})
                                        }
                                    });

                                });
                            </script>
                        </body>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade show active" id="rekapengineerx" role="tabpanel" aria-labelledby="crtype-tab">
            <div class="card shadow mb-4">
                <!-- Card Body -->
                <div class="card-body">
                    <div class="row">

                        <head>
                            <div class="row mb-3">
                                <div class="col-lg-3">
                                </div>
                                <div class="col-lg-9">
                                    <div class="d-flex flex-row">
                                        <div class="">
                                            <?php
                                            $link = array();
                                            $xxxy = array();
                                            $xxx = array('link' => getlink("project_type", "All Project"), 'value' => 'All Project');
                                            array_push($link, $xxx);
                                            $xxx = array('link' => getlink("project_type", "Implementation"), 'value' => 'Implementation');
                                            array_push($link, $xxx);
                                            $xxx = array('link' => getlink('project_type', 'Maintenance'), 'value' => 'Maintenance');
                                            array_push($link, $xxx);
                                            content2("Project Type", $project_type, $link,  "none", "2", "ProjectType", "h6", "0");
                                            ?>
                                        </div>
                                        <div class="">
                                            <div class="row g-2">
                                                <label for="inputPassword" class="col-auto col-form-label col-form-label-sm">From
                                                    :</label>
                                                <div class="row mb-2">
                                                    <div class="col-auto">
                                                        <div class="input-group date">
                                                            <div class="input-group-addon">
                                                                <span class="glyphicon glyphicon-th"></span>
                                                            </div>
                                                            <input type="date" class="form-control form-control-sm" id="start_period" name="start_period" placeholder="Start Date." value="<?php echo date("d-M-Y"); ?>" onchange="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="">
                                            <div class="row g-2">
                                                <label for="inputPassword" class="col-auto col-form-label col-form-label-sm">To</label>
                                                <div class="row mb-2">
                                                    <div class="col-auto">
                                                        <div class="input-group date">
                                                            <div class="input-group-addon">
                                                                <span class="glyphicon glyphicon-th"></span>
                                                            </div>
                                                            <input type="date" class="form-control form-control-sm" id="end_period" name="end_period" placeholder="End Date." value="<?php echo date("d-M-Y"); ?>" onchange="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $get_project = $DBKPI->get_sqlV2("SELECT SUM(a.cte) as jumlah_cte,Count(a.project_code) jumlah_project,a.resource_email,b.status_wr FROM sa_user a left join sa_kpi_so_wr b ON a.order_number=b.order_number WHERE a.role='PIC Maintenance' AND a.project_type LIKE '%Maintenance%' AND b.status_wr='Closed' GROUP BY a.resource_email LIMIT 0,10");
                            $get_project2 = $DBKPI->get_sqlV2("SELECT COUNT(a.cte) as jumlah_cte,Count(a.project_code) jumlah_project,a.resource_email,b.status_wr FROM sa_user a left join sa_kpi_so_wr b ON a.order_number=b.order_number WHERE a.role='PIC Maintenance' AND a.project_type LIKE '%Maintenance%' AND b.status_wr='Closed' GROUP BY a.resource_email LIMIT 0,10");
                            $get_projeect3 = $DBKPI->get_sqlV2("SELECT SUM(a.cte) as jumlah_cte,Count(a.project_code) jumlah_project,a.Nama,b.status_wr FROM sa_user a left join sa_kpi_so_wr b ON a.order_number=b.order_number WHERE a.role!='PIC Maintenance' AND a.project_type LIKE '%Maintenance%' AND b.status_wr='Closed' GROUP BY a.Nama"); ?>
                            <script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js">
                            </script>
                            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
                            <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
                            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js">
                            </script>
                            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
                            <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js">
                            </script>
                            <script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js">
                            </script>
                            <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
                            <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
                            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
                            <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
                            <!-- DataTables CSS -->
                            <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
                            <!-- Buttons CSS -->
                            <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
                            <!-- Font Awesome CSS (for icons) -->
                            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

                            <!-- jQuery -->
                            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                            <!-- DataTables JS -->
                            <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
                            <!-- Buttons JS -->
                            <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js">
                            </script>
                            <!-- JSZip (required for Excel export) -->
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
                            <!-- Excel Button JS -->
                            <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
                        </head>

                        <body>
                            <div class="row">
                                <div class="row mb-3">
                                    <div id="chartContainer2" style="height: 300px; width: 100%;"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="row mb-10">
                                    <table class="display" id="ProjectDetail3" style="width:100%">
                                        <thead>
                                            <tr class="text-center">
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>Jumlah Project</th>
                                                <th>Total Pencapaian KPI</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $x = 0;
                                            while ($row = $get_projeect3[1]->fetch_assoc()) {
                                                $x++;
                                                $nama = $row['Nama'];
                                                $jumlah_project = $row['jumlah_project'];
                                                $pencapaian_kpi = $row['jumlah_cte'] / $jumlah_project;
                                                $get = $DBKPI->get_sqlV2("SELECT a.* FROM sa_user a left join sa_kpi_so_wr b ON a.order_number=b.order_number WHERE a.Nama LIKE '%$nama%' AND b.status_wr LIKE '%Closed%' GROUP BY a.order_number");
                                                $project_name = '';
                                                $customer = '';
                                                $service_type = '';
                                                $result = '';
                                                $kpi = '';
                                                while ($rows = $get[1]->fetch_assoc()) {
                                                    $order_number = $rows['order_number'];
                                                    $project_name .= $rows['project_name'] . ", ";
                                                    $customer .= $rows['customer_name'] . ", ";
                                                    $kpi_so_wr = $DBKPI->get_sqlV2("SELECT * FROM sa_kpi_so_wr WHERE order_number='$order_number' AND project_type LIKE '%Maintenance%'");
                                                    $service_type .= $kpi_so_wr[0]['service_type'] . ", ";
                                                    $end_assignment = date_create($kpi_so_wr[0]['end_assignment']);
                                                    $start_assignment = date_create($kpi_so_wr[0]['start_assignment']);
                                                    $hasil = date_diff($end_assignment, $start_assignment);
                                                    $result .= $hasil->m . ", ";
                                                    $kpi .= $rows['cte'] . ", ";
                                                }
                                                $project_name = substr($project_name, 0, -2);
                                                $customer = substr($customer, 0, -2);
                                                $service_type = substr($service_type, 0, -2);
                                                $result = substr($result, 0, -2);
                                                $kpi = substr($kpi, 0, -2);
                                            ?>
                                                <tr data-toggle="modal" data-target="#myModalPM" data-kpi="<?php echo $kpi; ?>" data-type="<?php $service_type; ?>" data-periode="<?php echo $kpi_so_wr[0]['start_assignment'] . " ~ " . $kpi_so_wr[0]['end_assignment'] . "($result Bulan)"; ?>" data-customername="<?php echo $customer; ?>" data-projectname="<?php echo $project_name; ?>">
                                                    <td><?php echo $x; ?></td>
                                                    <td><?php echo $nama; ?></td>
                                                    <td><?php echo $jumlah_project; ?></td>
                                                    <td><?php echo $pencapaian_kpi; ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <link rel="stylesheet" href="path/to/bootstrap.css">
                            <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
                            <script src="path/to/jquery.js"></script>
                            <script src="path/to/bootstrap.js"></script>
                            <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
                            <div class="modal fade" id="myModal" role="dialog">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title"></h4>
                                        </div>
                                        <div class="modal-body">
                                            <h4 class="modal-title">Project Profile</h4>
                                            <div class="table-responsive">
                                                <table class="display table table-bordered" id="ProjectDetail3" style="width:100%">
                                                    <thead>
                                                        <tr class="text-center">
                                                            <th>Nama Customer</th>
                                                            <th>Nama Project</th>
                                                            <th>Type</th>
                                                            <th>Start-End</th>
                                                            <th colspan="2">Nilai Project</th>
                                                            <th>Member</th>
                                                            <th>Roles</th>
                                                            <th>Start-End Member</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><span id="modalCustomer"></td>
                                                            <td><span id="modalProject"></td>
                                                            <td><span id="modalType"></td>
                                                            <td><span id="modalPeriod"></td>
                                                            <td colspan="2"><span id="modalValue"></td>
                                                            <td><span id="modalResources"></td>
                                                            <td><span id="modalProjectRoles"></span></td>
                                                            <td><span id="modalMembersPeriod"></span></td>
                                                            <td><span id="modalMembersStatus"></span></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $get_project = $DBKPI->get_sqlV2("SELECT SUM(a.cte) as jumlah_cte,Count(a.project_code) jumlah_project,a.resource_email,b.status_wr FROM sa_user a left join sa_kpi_so_wr b ON a.order_number=b.order_number WHERE a.role='PIC Maintenance' AND a.project_type LIKE '%Maintenance%' AND b.status_wr='Closed' GROUP BY a.resource_email LIMIT 0,10");
                            $get_project2 = $DBKPI->get_sqlV2("SELECT COUNT(a.cte) as jumlah_cte,Count(a.project_code) jumlah_project,a.resource_email,b.status_wr FROM sa_user a left join sa_kpi_so_wr b ON a.order_number=b.order_number WHERE a.role='PIC Maintenance' AND a.project_type LIKE '%Maintenance%' AND b.status_wr='Closed' GROUP BY a.resource_email LIMIT 0,10");
                            $get_project3 = $DBKPI->get_sqlV2("SELECT SUM(a.cte) as jumlah_cte,Count(a.project_code) jumlah_project,a.Nama,b.status_wr FROM sa_user a left join sa_kpi_so_wr b ON a.order_number=b.order_number WHERE a.role='PIC Maintenance' AND a.project_type LIKE '%Maintenance%' AND b.status_wr='Closed' GROUP BY a.Nama LIMIT 0,10"); ?>
                            <script>
                                jQuery(function() {

                                    jQuery('#start_period').datetimepicker({
                                        format: 'd-M-Y',
                                        timepicker: false,
                                        onShow: function(ct) {
                                            this.setOptions({})
                                        }
                                    });
                                    jQuery('#end_period').datetimepicker({
                                        format: 'd-M-Y',
                                        timepicker: false,
                                        onShow: function(ct) {
                                            this.setOptions({})
                                        }
                                    });

                                });
                            </script>
                        </body>
                        <script>
                            $('#myModal').on('show.bs.modal', function(event) {
                                var button = $(event.relatedTarget);
                                var customer = button.data('customer');
                                var project = button.data('project');
                                var type = button.data('type');
                                var period = button.data('period');
                                var kpi = button.data('kpi');
                                var order = button.data('order');
                                var value = button.data('value');
                                var resources = button.data('resources');
                                var projectroles = button.data('projectroles');
                                var membersperiod = button.data('membersperiod');
                                var membersstatus = button.data('membersstatus');
                                var jumlahticket = button.data('jumlahticket');
                                var jumlahpreventive = button.data('jumlahpreventive');
                                var jumlahreport = button.data('jumlahreport');
                                var infogap = button.data('infogap');
                                var tanggalkom = button.data('tanggalkom');
                                var tanggalikom = button.data('tanggalikom');
                                var tanggalassignment = button.data('tanggalassignment');
                                var renewalcategory = button.data('renewalcategory');
                                var renewalkpi = button.data('renewalkpi');
                                var timecategory = button.data('timecategory');
                                var timekpi = button.data('timekpi');
                                var errorcategory = button.data('errorcategory');
                                var errorkpi = button.data('errorkpi');
                                var compliancecategory = button.data('compliancecategory');
                                var compliancekpi = button.data('compliancekpi');
                                var backupcategory = button.data('backupcategory');
                                var backupkpi = button.data('backupkpi');
                                var adoptioncategory = button.data('adoptioncategory');
                                var adoptionkpi = button.data('adoptionkpi');
                                var cte = button.data('totalcte');
                                var renewalactual = button.data('renewalactual');
                                var timeactual = button.data('timeactual');
                                var erroractual = button.data('erroractual');
                                var complianceactual = button.data('complianceactual');
                                var backupactual = button.data('backupactual');
                                var adoptionactual = button.data('adoptionactual');

                                var modal = $(this);
                                modal.find('#modalCustomer').text(customer);
                                modal.find('#modalProject').text(project);
                                modal.find('#modalType').text(type);
                                modal.find('#modalPeriod').text(period);
                                modal.find('#modalKpi').text(kpi);
                                modal.find('#modalOrder').text(order);
                                modal.find('#modalValue').text(value);
                                modal.find('#modalResources').text(resources);
                                modal.find('#modalProjectRoles').text(projectroles);
                                modal.find('#modalMembersPeriod').text(membersperiod);
                                modal.find('#modalMembersStatus').text(membersstatus);
                                modal.find('#modalJumlahTicket').text(jumlahticket);
                                modal.find('#modalJumlahPreventive').text(jumlahpreventive);
                                modal.find('#modalJumlahReport').text(jumlahreport);
                                modal.find('#modalInfoGap').text(infogap);
                                modal.find('#modalTanggalKom').text(tanggalkom);
                                modal.find('#modalTanggalIkom').text(tanggalikom);
                                modal.find('#modalTanggalAssignment').text(tanggalassignment);
                                modal.find('#modalRenewalCategory').text(renewalcategory);
                                modal.find('#modalRenewalKpi').text(renewalkpi);
                                modal.find('#modalTimeCategory').text(timecategory);
                                modal.find('#modalTimeKpi').text(timekpi);
                                modal.find('#modalErrorCategory').text(errorcategory);
                                modal.find('#modalErrorKpi').text(errorkpi);
                                modal.find('#modalComplianceCategory').text(compliancecategory);
                                modal.find('#modalComplianceKpi').text(compliancekpi);
                                modal.find('#modalBackupCategory').text(backupcategory);
                                modal.find('#modalBackupKpi').text(backupkpi);
                                modal.find('#modalAdoptionCategory').text(adoptioncategory);
                                modal.find('#modalAdoptionKpi').text(adoptionkpi);
                                modal.find('#modalCTE').text(cte);
                                modal.find('#modalRenewalActual').text(renewalactual);
                                modal.find('#modalTimeActual').text(timeactual);
                                modal.find('#modalErrorActual').text(erroractual);
                                modal.find('#modalComplianceActual').text(complianceactual);
                                modal.find('#modalBackupActual').text(backupactual);
                                modal.find('#modalAdoptionActual').text(adoptionactual);
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

</html>