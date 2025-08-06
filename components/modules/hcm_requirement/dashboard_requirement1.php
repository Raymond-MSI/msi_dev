<?php

if ((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $released = "1672653627";
    $author = 'Syamsul Arham';
} else {

    $modulename = "REQUIREMENT_HCM";
    $mdl_permission = useraccess_v2($modulename);
    // if (USERPERMISSION_V2 == "810159712762c176ee4bbb27da703a78") {
    global $DBHCM;
    // $DBHCM = get_conn($modulename);
?>
    <?php
    if (!isset($_GET['year']))
        $year =  date("Y");
    else
        $year = $_GET['year'];

    $queryyear = "SELECT DISTINCT(YEAR(request_date)) as year from sa_hcm_requirement where request_date != '' order by request_date desc";
    $getyear = $DBHCM->get_sql($queryyear);
    $years = $getyear[0];
    $prev_year = $getyear[1];
    ?>
    <!-- filter -->
    <div class="filterDialog" id="filterDialog">
        <div class="row mb-3">
            <label for="inputCID3" class="col-sm-2 col-form-label col-form-label-sm">Tahun: </label>
            <div class="col-sm-9">
                <select id='pilihtahun'>
                    <option value="all" <?= (!isset($_GET['year']) || $_GET['year'] == 'all') ? 'selected' : ''; ?>>All</option>
                    <?php
                    do {
                        $select = '';
                        if (isset($_GET['year']) && $_GET['year'] != '') {
                            $select = ($year == $years['year']) ? 'selected' : '';
                        }
                        echo "<option value='" . $years['year'] . "' $select>" . $years['year'];
                    } while ($years = $prev_year->fetch_assoc());
                    ?>
                </select>
            </div>
        </div>
        <div><input type="submit" class="btn btn-primary" id='filterbtn' name="filterbtn" value="Filter"></div>
    </div>
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary" style="float:left">Dashboard Requirement</h6>
                <button class="dt-button" type="button" style="float:right" id="filtershow"><span><i class="fa fa-filter"></i></span></button>
            </div>
            <div class="row mb-3">
                <div class="col-lg-12">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card shadow mb-1">
                                    <div class="card-header fw-bold">All Request</div>
                                    <div class="card-body">
                                        <div id="requirementPieChart" style="height: 300px; width: 100%;"></div>

                                        <script>
                                            <?php
                                            $selectedYear = isset($_GET['year']) ? $_GET['year'] : 'all';
                                            // Query untuk mendapatkan data
                                            $querybar = "SELECT 
                    COUNT(IF(status_request = 'Pending Approval', 1, NULL)) AS pending, 
                    COUNT(IF(status_request = 'Submitted', 1, NULL)) AS submitted, 
                    COUNT(IF(status_request = 'Proses Interview', 1, NULL)) AS interview, 
                    COUNT(IF(status_request = 'Request Complete', 1, NULL)) AS request_complete
                FROM sa_hcm_requirement ";
                                            if ($selectedYear !== 'all') {
                                                $querybar .= " WHERE YEAR(request_date) = $selectedYear";
                                            }
                                            $data = $DBHCM->get_sqlV2($querybar);
                                            $datarequest = $data[0];
                                            $pending = $datarequest['pending'] ?? 0;
                                            $submitted = $datarequest['submitted'] ?? 0;
                                            $interview = $datarequest['interview'] ?? 0;
                                            $reqcomplete = $datarequest['request_complete'] ?? 0;
                                            ?>

                                            document.addEventListener("DOMContentLoaded", function() {
                                                var chart = new CanvasJS.Chart("requirementPieChart", {
                                                    animationEnabled: true,
                                                    title: {
                                                        text: "All Request"
                                                    },
                                                    data: [{
                                                        type: "pie",
                                                        showInLegend: true,
                                                        toolTipContent: "{label}: <strong>{y}</strong>",
                                                        indexLabel: "{y}",
                                                        indexLabelFontSize: 14, // Ukuran font angka
                                                        indexLabelPlacement: "inside", // Tempatkan angka di dalam chart
                                                        indexLabelFontColor: "black", // Warna font agar lebih jelas
                                                        legendText: "{label}",
                                                        dataPoints: [{
                                                                label: "Pending",
                                                                y: <?= $pending; ?>
                                                            },
                                                            {
                                                                label: "Submitted",
                                                                y: <?= $submitted; ?>
                                                            },
                                                            {
                                                                label: "Interview",
                                                                y: <?= $interview; ?>
                                                            },
                                                            {
                                                                label: "Request Complete",
                                                                y: <?= $reqcomplete; ?>
                                                            }
                                                        ]
                                                    }]
                                                });
                                                chart.render();
                                            });
                                        </script>

                                        <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="card shadow mb-1">
                                    <div class="card-header fw-bold">Alasan Recruitment</div>
                                    <div class="card-body">
                                        <div id="statusRekrutmenPieChart" style="height: 300px; width: 100%;"></div>

                                        <script>
                                            <?php
                                            $selectedYear = isset($_GET['year']) ? $_GET['year'] : 'all';
                                            $querybar = "SELECT 
    COUNT(IF(status_rekrutmen = 'Penggantian', 1, NULL)) AS replacement, 
    COUNT(IF(status_rekrutmen = 'Penambahan', 1, NULL)) AS penambahan
FROM sa_hcm_requirement
WHERE status_request NOT IN ('Inactive', 'Submitted')";
                                            if ($selectedYear !== 'all') {
                                                $querybar .= " AND YEAR(request_date) = $selectedYear";
                                            }
                                            $data = $DBHCM->get_sqlV2($querybar);
                                            $datarequest = $data[0];
                                            $replacement = $datarequest['replacement'];
                                            $penambahan = $datarequest['penambahan'];
                                            ?>

                                            window.onload = function() {
                                                var chart = new CanvasJS.Chart("statusRekrutmenPieChart", {
                                                    animationEnabled: true,
                                                    title: {
                                                        text: "Alasan Recruitment"
                                                    },
                                                    data: [{
                                                        type: "pie",
                                                        showInLegend: true,
                                                        toolTipContent: "{label}: <strong>{y}</strong>",
                                                        indexLabel: "{y}",
                                                        indexLabelFontSize: 14, // Ukuran font angka
                                                        indexLabelPlacement: "inside", // Tempatkan angka di dalam chart
                                                        indexLabelFontColor: "black", // Warna font agar lebih jelas
                                                        legendText: "{label}",
                                                        dataPoints: [{
                                                                label: "Penggantian",
                                                                y: <?= $replacement; ?>
                                                            },
                                                            {
                                                                label: "Penambahan",
                                                                y: <?= $penambahan; ?>
                                                            }
                                                        ]
                                                    }]
                                                });
                                                chart.render();
                                            }
                                        </script>

                                        <!-- <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script> -->
                                    </div>
                                </div>

                                <!-- <canvas id='status_rekrutmen_bar_chart'></canvas> -->
                            </div>
                            <div class="row">
                                <div>
                                    <div class="card shadow mb-1">
                                        <div class="card-header fw-bold">Karyawan Join</div>
                                        <div class="card-body">
                                            <div id="join_bar_chart" style="height: 370px; width: 100%;"></div>
                                            <?php
                                            // Query untuk mendapatkan data
                                            $selectedYear = isset($_GET['year']) ? $_GET['year'] : 'all';
                                            $currentYear = date("Y");
                                            $querybar = "SELECT 
    MONTHNAME(join_date) AS month, 
    COUNT(*) AS total,
    MONTH(join_date) AS month_number,
    YEAR(join_date) AS year
FROM sa_hcm_requirement_interview 
WHERE join_date IS NOT NULL";
                                            // Tambahkan filter tahun jika dipilih tahun tertentu
                                            if ($selectedYear !== 'all') {
                                                $querybar .= " AND YEAR(join_date) = $selectedYear";
                                            }

                                            // Tambahkan GROUP BY dan ORDER BY
                                            $querybar .= " GROUP BY YEAR(join_date), MONTH(join_date) 
ORDER BY YEAR(join_date), MONTH(join_date)";
                                            $data = $DBHCM->get_sqlV2($querybar);
                                            $chartData = [];
                                            $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                                            $currentYear = date("Y");

                                            $previousData = [];
                                            if ($data) {
                                                while ($row = $data[1]->fetch_assoc()) {
                                                    $previousData[] = [
                                                        "month" => $row['month'],
                                                        "y" => (int)$row['total'],
                                                        "month_number" => (int)$row['month_number'],
                                                        "year" => (int)$row['year']
                                                    ];
                                                }
                                            }
                                            for ($monthNum = 1; $monthNum <= 12; $monthNum++) {
                                                $monthName = $months[$monthNum - 1];
                                                $found = false;
                                                foreach ($previousData as $dataRow) {
                                                    if ($dataRow['month_number'] == $monthNum) {
                                                        $chartData[] = [
                                                            "label" => $monthName,
                                                            "y" => $dataRow['y']
                                                        ];
                                                        $found = true;
                                                        break;
                                                    }
                                                }
                                                if (!$found) {
                                                    $chartData[] = [
                                                        "label" => $monthName,
                                                        "y" => 0
                                                    ];
                                                }
                                            }
                                            ?>


                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id='template_table' class="card shadow mb-1">
                                <table id='table_template' class="table table-borderless table-striped text-center">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th style="background-color:aquamarine">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $selectedYear = isset($_GET['year']) ? $_GET['year'] : 'all';
                                        $queryall = "SELECT 
    a.total_jumlah_dibutuhkan,
    b.total_data
FROM 
    (SELECT SUM(jumlah_dibutuhkan) AS total_jumlah_dibutuhkan
     FROM sa_hcm_requirement
     WHERE status_request NOT IN ('Inactive', 'Submitted')";

                                        if ($selectedYear !== 'all') {
                                            $queryall .= " AND YEAR(request_date) = $selectedYear";
                                        }

                                        $queryall .= ") AS a CROSS JOIN (SELECT COUNT(*) AS total_data
     FROM sa_hcm_requirement_interview
     WHERE status NOT IN ('Proses Offering')";

                                        if ($selectedYear !== 'all') {
                                            $queryall .= " AND YEAR(join_date) = $selectedYear";
                                        }

                                        $queryall .= ") AS b;";
                                        $datatable = $DBHCM->get_sql($queryall);
                                        $curr = $datatable[0];
                                        $next = $datatable[1];
                                        $count = $datatable[2];
                                        if ($count > 0) {
                                            do {
                                                echo "<td>Permintaan Karyawan</td>";
                                                echo "<td>" . $curr['total_jumlah_dibutuhkan'] . "</td>";
                                                echo "</tr>";
                                                echo "<td>Karyawan Join & akan Join</td>";
                                                echo "<td>" . $curr['total_data'] . "</td>";
                                                echo "</tr>";
                                            } while ($curr = $next->fetch_assoc());
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active text-body fw-bold" id="pending-tab" data-bs-toggle="tab"
                                                data-bs-target="#contentPending" type="button" role="tab" aria-controls="contentPending"
                                                aria-selected="true" title='Request Pending'>All Request</button>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade show active" id="contentPending" role="tabpanel" aria-labelledby="pending-tab">
                                            <?php
                                            $selectedYear = isset($_GET['year']) ? $_GET['year'] : 'all';
                                            $query = "SELECT 
    a.id_fpkb, a.assign_requirement, a.divisi, a.request_by, a.posisi, 
    a.status_rekrutmen, a.status_karyawan, a.reason_penggantian, a.nama_project, 
    a.project_code, a.jumlah_dibutuhkan, a.request_date, a.tanggal_dibutuhkan, 
    b.join_date, b.nama_kandidat, a.pendidikan_minimal, a.jurusan 
FROM sa_hcm_requirement a 
LEFT JOIN sa_hcm_requirement_interview b ON a.id = b.id_request";

                                            // Tambahkan filter tahun jika dipilih tahun tertentu
                                            if ($selectedYear !== 'all') {
                                                $query .= " WHERE YEAR(a.request_date) = $selectedYear";
                                            }
                                            $datatable = $DBHCM->get_sql($query);
                                            $curr = $datatable[0];
                                            $next = $datatable[1];
                                            $count = $datatable[2];
                                            $header = [
                                                'FPKB',
                                                'Recruiter',
                                                'Department',
                                                'User',
                                                'Posisi',
                                                'Category',
                                                'Nama Karyawan Lama',
                                                'Customer / Project Name',
                                                'Kode Project',
                                                'Yang Dibutuhkan',
                                                'Tanggal Fpkb',
                                                'Tanggal Dibutuhkan',
                                                'Tanggal Join',
                                                'Nama Karyawan Baru',
                                                'Education',
                                                'Jurusan',
                                            ];
                                            $header1 = '';
                                            foreach ($header as $head) {
                                                $header1 .= "<th class='text-center align-middle'>" . $head . "</th>";
                                            }
                                            ?>

                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered hover stripe display compact dataTableMulti" id="request_employee" width="100%" cellspacing="0">
                                                        <thead>
                                                            <tr>
                                                                <?php
                                                                echo $header1;
                                                                ?>
                                                            </tr>
                                                        </thead>
                                                        <tfoot>
                                                            <tr>
                                                                <?php echo $header1; ?>
                                                            </tr>
                                                        </tfoot>
                                                        <tbody>
                                                            <?php
                                                            if (isset($curr)) {
                                                                do {
                                                                    $resource_exist = true;
                                                                    echo "<tr>";
                                                                    echo "<td>" . $curr['id_fpkb'] . "</td>";
                                                                    echo "<td>" . $curr['assign_requirement'] . "</td>";
                                                                    echo "<td>" . $curr['divisi'] . "</td>";
                                                                    echo "<td>" . $curr['request_by'] . "</td>";
                                                                    echo "<td>" . $curr['posisi'] . "</td>";
                                                                    echo "<td>" . $curr['status_rekrutmen'] . "</td>";
                                                                    echo "<td>" . $curr['reason_penggantian'] . "</td>";
                                                                    echo "<td>" . $curr['nama_project'] . "</td>";
                                                                    echo "<td>" . $curr['project_code'] . "</td>";
                                                                    echo "<td>" . $curr['jumlah_dibutuhkan'] . "</td>";
                                                                    echo "<td>" . $curr['request_date'] . "</td>";
                                                                    echo "<td>" . $curr['tanggal_dibutuhkan'] . "</td>";
                                                                    echo "<td>" . $curr['join_date'] . "</td>";
                                                                    echo "<td>" . $curr['nama_kandidat'] . "</td>";
                                                                    echo "<td>" . $curr['pendidikan_minimal'] . "</td>";
                                                                    echo "<td>" . $curr['jurusan'] . "</td>";
                                                                    echo "</tr>";
                                                                } while ($curr = $next->fetch_assoc());
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
<?php
}
?>
<script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.bundle.min.js'></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0/dist/chartjs-plugin-datalabels.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/modified-line-chart@3.6.8/dist/apexcharts.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

<script>
    $(document).ready(function() {
        $(document).ready(function() {
            $("#filterDialog").hide();
            $('#filtershow').click(function() {
                $('#filterDialog').dialog({
                    minWidth: 850
                });
            });

            $('#filterbtn').click(function() {
                var years = custs = tipes = temps = '';
                var year = $('#pilihtahun option:selected').val();
                if (year != '') {
                    years = '&year=' + year;
                }
                window.location.href = 'index.php?mod=hcm_requirement&sub=dashboard_requirement1' + years;
            });
        });
        $('#request_employee').DataTable({
            dom: 'Bfrtip',
            buttons: [{
                extend: 'excelHtml5',
                title: 'Requirements Data',
                exportOptions: {
                    columns: ':visible'
                }
            }, ]
        });
    });
</script>
<script>
    $(document).ready(function() {
        var myTab = new bootstrap.Tab(document.querySelector('#myTab button.active'));
        myTab.show();
    });
</script>
<style>
    td {
        max-width: 250px !important;
        word-wrap: break-word;
        min-width: 100px !important;
    }
</style>




<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Data dari PHP
        const chartData = <?php echo json_encode($chartData); ?>;

        // Render Line Chart menggunakan CanvasJS
        const chart = new CanvasJS.Chart("join_bar_chart", {
            animationEnabled: true,
            theme: "light2", // Pilihan tema: "light1", "light2", "dark1", "dark2"
            title: {
                text: "Karyawan Join"
            },
            axisX: {
                title: "Month",
                interval: 1
            },
            axisY: {
                title: "Total Karyawan",
                includeZero: true
            },
            data: [{
                type: "line", // Menggunakan jenis line chart
                dataPoints: chartData.map(point => ({
                    ...point,
                    indexLabel: point.y.toString() // Menambahkan label pada setiap titik dengan angka y
                }))
            }]
        });

        chart.render();
    });
</script>