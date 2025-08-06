<?php

include("components/modules/dashboard/func_dashboard.php");

?>
<?php
if ((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $released = "1672653627";
    $author = 'Syamsul Arham';
} else {

    $modulename = "SURVEY";
    $mdl_permission = useraccess_v2($modulename);
    if (USERPERMISSION_V2 == "810159712762c176ee4bbb27da703a78") {
        global $DB1;
        $DB1 = get_conn($modulename);

        if (!isset($_GET['year']))
            $year =  date("Y");
        else
            $year = $_GET['year'];

        $queryyear = "SELECT DISTINCT(YEAR(created_datetime)) as year from sa_survey where created_datetime != '' order by created_datetime desc";
        $getyear = $DB1->get_sql($queryyear);
        $years = $getyear[0];
        $prev_year = $getyear[1];

        $querycust = "SELECT customer_company_name, customer_code from sa_customer where customer_company_name is not null and customer_code is not null";
        $getcust = $DB1->get_sql($querycust);
        $cust = $getcust[0];
        $nextcust = $getcust[1];


        // End Function
        $mdlname = "SURVEY";
        $DB1 = get_conn($mdlname);
        $tblname = 'trx_survey';


        //include("components/modules/trx_survey/func_trx_survey.php");

        // Body
?>
        <div class="row mb-3">
            <div class="col-lg-6">
                <div class="col-lg-3">
                    <?php menu_dashboard(); ?>
                </div>
            </div>
            <div class="col-lg-6">
            </div>
        </div>
        <div class="filterDialog" id="filterDialog">
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-2 col-form-label col-form-label-sm">Tahun: </label>
                <div class="col-sm-9">
                    <select id='pilihtahun'>
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
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-2 col-form-label col-form-label-sm">Customer: </label>
                <div class="col-sm-9">
                    <select id='selectCust' class='form-control' style='width: 500px'>
                        <option value=""></option>
                        <?php
                        do {
                            $select = '';
                            $surveycust = '';
                            $scriptcust = '';
                            if (isset($_GET['customer']) && $_GET['customer'] != '') {
                                $select = ($_GET['customer'] == $cust['customer_code']) ? 'selected' : '';
                                $scriptcust = $_GET['customer'];
                                $surveycust = "AND pic.customer_code = '" . $_GET['customer'] . "'";
                            }
                            echo "<option value='" . $cust['customer_code'] . "' " . $select . ">" . $cust['customer_company_name'];
                        } while ($cust = $nextcust->fetch_assoc());
                        ?>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-2 col-form-label col-form-label-sm">Tipe Project: </label>
                <div class="col-sm-9">
                    <select id='selectTipe'>
                        <?php
                        $select = '';
                        $surveytype = '';
                        $tipe = '';
                        if (isset($_GET['tipe']) && $_GET['tipe'] != '') {
                            $surveytype = "AND sur.project_type LIKE '%" . $_GET['tipe'] . "%'";
                            $tipe = $_GET['tipe'];
                        }
                        ?>
                        <option value="">All</option>
                        <option value="Implementation" <?= ($tipe == 'Implementation') ? 'selected' : '' ?>>Implementasi</option>
                        <option value="Maintenance" <?= ($tipe == 'Maintenance') ? 'selected' : '' ?>>Maintenance</option>
                    </select>
                </div>
            </div>
            <div><input type="submit" class="btn btn-primary" id='filterbtn' name="filterbtn" value="Filter"></div>
        </div>
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary" style="float:left">Dashboard Survey</h6>
                    <button class="dt-button" type="button" style="float:right" id="filtershow"><span><i class="fa fa-filter"></i></span></button>
                </div>
                <div class="card-body">
                    <br />
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card shadow mb-1">
                                <div class="card-header fw-bold">Project Tersurvey</div>
                                <div class="card-body">
                                    <canvas id='surveyed_project_count'></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card shadow mb-1">
                                <div class="card-header fw-bold">Pengisian Survey</div>
                                <div class="card-body">
                                    <canvas id='survey_bar_chart'></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br />
                    <br />
                    <div id='template_table' class="card shadow mb-1">
                        <table id='table_template' class="table table-borderless table-striped text-center">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th colspan="2" style="background-color:aquamarine">Nilai diisi Customer</th>
                                    <th rowspan="2">Jumlah Survey diisi Nilai Default (7)</th>
                                    <th colspan="2" style="background-color:yellow">Total</th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th style="background-color:aquamarine">Jumlah Survey</th>
                                    <th style="background-color:aquamarine">Average</th>
                                    <th style="background-color:yellow">Jumlah Survey</th>
                                    <th style="background-color:yellow">Average</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $queryall = "select avg(case when status != 'submitted' then null else rating_average end) as avg_submit, project_type,
                                                    count(if(status ='submitted', 1, null)) as total_submit, count(if(status='default', 1, null)) as total_auto, count(*) as total_survey,
                                                    avg(rating_average) as total_all, customer_code from sa_trx_survey trx join
                                                    sa_survey sur on sur.survey_id = trx.survey_id join sa_pic pic on pic.pic_id = sur.pic_id where 
                                                    YEAR(sur.created_datetime) = $year $surveycust $surveytype group by sur.project_type";
                                $datatable = $DB1->get_sql($queryall);
                                $curr = $datatable[0];
                                $next = $datatable[1];
                                $count = $datatable[2];
                                if ($count > 0) {
                                    do {
                                        echo "<td>" . $curr['project_type'] . "</td>";
                                        echo "<td>" . $curr['total_submit'] . "</td>";
                                        echo "<td>" . $curr['avg_submit'] . "</td>";
                                        echo "<td>" . $curr['total_auto'] . "</td>";
                                        echo "<td>" . $curr['total_survey'] . "</td>";
                                        echo "<td>" . $curr['total_all'] . "</td>";
                                        echo "</tr>";
                                    } while ($curr = $next->fetch_assoc());
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <br />
                    <br />
                    <?php if ($tipe != 'Maintenance') { ?>
                        <div id='implement_table' class="card shadow mb-1">
                            <div class="card-header fw-bold">Detail Implementasi</div>
                            <div class="card-body">
                                <table id='table_implement' class="table table-borderless table-striped text-center">
                                    <thead>
                                        <tr style="font-weight:bold">
                                            <th>Description</th>
                                            <th>Average (tanpa Default)</th>
                                            <th>No of Feedback</th>
                                            <th>Average dengan Default</th>
                                            <th>No of Feedback</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $queryimp = "select avg(case when status != 'submitted' then null else rating_average end) as avg_submit, project_type, type_of_service,
                                                    count(if(status ='submitted', 1, null)) as total_submit, count(if(main_rating='auto', 1, null)) as total_auto, count(*) as total_survey,
                                                    avg(case when main_rating = 'auto' then null else rating_average end) as total_all, customer_code from sa_trx_survey trx join
                                                    sa_survey sur on sur.survey_id = trx.survey_id join sa_pic pic on pic.pic_id = sur.pic_id where 
                                                    YEAR(sur.created_datetime) = $year $surveycust $surveytype AND sur.project_type like '%Implementation%' group by type_of_service order by CASE
                                                    WHEN type_of_service = 'High' THEN 1
                                                    WHEN type_of_service = 'Medium' THEN 2
                                                    WHEN type_of_service = 'Standard' THEN 3
                                                    END ASC";
                                        $datatableimp = $DB1->get_sql($queryimp);
                                        $currimp = $datatableimp[0];
                                        $nextimp = $datatableimp[1];
                                        $countimp = $datatableimp[2];
                                        if ($countimp > 0) {
                                            do {
                                                echo "<tr>";
                                                echo "<td> Average " . $currimp['type_of_service'] . "</td>";
                                                echo "<td>" . $currimp['avg_submit'] . "</td>";
                                                echo "<td>" . $currimp['total_submit'] . "</td>";
                                                echo "<td>" . $currimp['total_all'] . "</td>";
                                                echo "<td>" . $currimp['total_survey'] . "</td>";
                                                echo "</tr>";
                                            } while ($currimp = $nextimp->fetch_assoc());
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php } ?>
                    <br />
                    <br />
                    <?php if ($tipe != 'Implementation') { ?>
                        <div id='maintenance_table' class="card shadow mb-1">
                            <div class="card-header fw-bold">Detail Maintenance</div>
                            <div class="card-body">
                                <table id='table_maintenance' class="table table-borderless table-striped text-center">
                                    <thead>
                                        <tr style="font-weight:bold">
                                            <th>Description</th>
                                            <th>Average (tanpa Default)</th>
                                            <th>No of Feedback</th>
                                            <th>Average dengan Default</th>
                                            <th>No of Feedback</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $queryimp = "select avg(case when status != 'submitted' then null else rating_average end) as avg_submit, project_type, type_of_service,
                                                    count(if(status ='submitted', 1, null)) as total_submit, count(if(main_rating='auto', 1, null)) as total_auto, count(*) as total_survey,
                                                    avg(case when main_rating = 'auto' then 7 else rating_average end) as total_all, customer_code from sa_trx_survey trx join
                                                    sa_survey sur on sur.survey_id = trx.survey_id join sa_pic pic on pic.pic_id = sur.pic_id where 
                                                    YEAR(sur.created_datetime) = $year $surveycust $surveytype AND sur.project_type like '%Maintenance%' group by type_of_service order by CASE
                                                    WHEN type_of_service = 'Gold' THEN 1
                                                    WHEN type_of_service = 'Silver' THEN 2
                                                    WHEN type_of_service = 'Bronze' THEN 3
                                                    END ASC";
                                        $datatableimp = $DB1->get_sql($queryimp);
                                        $currimp = $datatableimp[0];
                                        $nextimp = $datatableimp[1];
                                        $countimp = $datatableimp[2];
                                        if ($countimp > 0) {
                                            do {
                                                echo "<tr>";
                                                echo "<td> Average " . $currimp['type_of_service'] . "</td>";
                                                echo "<td>" . $currimp['avg_submit'] . "</td>";
                                                echo "<td>" . $currimp['total_submit'] . "</td>";
                                                echo "<td>" . $currimp['total_all'] . "</td>";
                                                echo "<td>" . $currimp['total_survey'] . "</td>";
                                                echo "</tr>";
                                            } while ($currimp = $nextimp->fetch_assoc());
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php } ?>
                    <br />
                    <br />
                    <div class="card shadow mb-1">
                        <div class="card-header fw-bold">Top 10 Project Survey</div>
                        <div class="card-body">
                            <table class="table table-borderless table-striped text-center">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Customer Name</th>
                                        <th>Project Code (SO Number)</th>
                                        <th>Project Name</th>
                                        <th>Average Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $querytop = "select sur.project_code, so_number, rating_average, project_name, customer_company_name from sa_trx_survey trx join
                            sa_survey sur on sur.survey_id = trx.survey_id join sa_pic pic on pic.pic_id = sur.pic_id join sa_customer cu on pic.customer_code = cu.customer_code where 
                            YEAR(sur.created_datetime) = $year $surveycust $surveytype order by rating_average desc LIMIT 10";
                                    $datatabletop = $DB1->get_sql($querytop);
                                    $currtop = $datatabletop[0];
                                    $nexttop = $datatabletop[1];
                                    $counttop = $datatabletop[2];
                                    $i = 1;
                                    if ($counttop > 0) {
                                        do {
                                            echo "<td>" . $i . "</td>";
                                            echo "<td>" . $currtop['customer_company_name'] . "</td>";
                                            echo "<td>" . $currtop['project_code'] . "( " . $currtop['so_number'] . " )</td>";
                                            echo "<td>" . $currtop['project_name'] . "</td>";
                                            echo "<td>" . $currtop['rating_average'] . "</td>";
                                            echo "</tr>";
                                            $i++;
                                        } while ($currtop = $nexttop->fetch_assoc());
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div><br />
                    <br />
                    <div class="card shadow mb-1">
                        <div class="card-header fw-bold">Top 10 Voted Resource</div>
                        <div class="card-body">
                            <table class="table table-borderless table-striped text-center">
                                <thead>
                                    <tr>
                                        <th>Nama Resource</th>
                                        <th>Jumlah Vote</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $querytop = "select main_engineer from sa_trx_survey trx join
                            sa_survey sur on sur.survey_id = trx.survey_id join sa_pic pic on pic.pic_id = sur.pic_id where 
                            YEAR(sur.created_datetime) = $year $surveycust $surveytype AND main_engineer not like '%email%:%none%'";
                                    $votes = $DB1->get_sql($querytop);
                                    $votes1 = $votes[0];
                                    $votes2 = $votes[1];
                                    $votes3 = $votes[2];
                                    $top = array();
                                    if ($votes3 > 0) {
                                        do {
                                            $resource = $votes1['main_engineer'];
                                            $resource = json_decode($resource, true);
                                            foreach ($resource as $score) {
                                                if (empty($top[$score['name'] . " (" . $score['email'] . ")"])) {
                                                    $top[$score['name'] . " (" . $score['email'] . ")"] = 1;
                                                } else {
                                                    $top[$score['name'] . " (" . $score['email'] . ")"] += 1;
                                                }
                                            }
                                        } while ($votes1 = $votes2->fetch_assoc());
                                        arsort($top);
                                        $top = array_slice($top, 0, 9);
                                        foreach ($top as $key => $value) {
                                            echo "<td>" . $key . "</td>";
                                            echo "<td>" . $value . "</td>";
                                            echo "</tr>";
                                        }
                                    }
                                    ?>
                                <tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $query = "SELECT sur.survey_id, so_number, pic_name, pic_email, pic_phone, project_type, customer_company_name, type_of_service, trx.project_code, trx.project_title, sur.survey_link, sur.status, main_rating, main_essay, main_engineer, sur.created_datetime, sur.reply_datetime, rating_average
                from sa_survey sur join sa_trx_survey trx on sur.survey_id = trx.survey_id join sa_pic pic on sur.pic_id = pic.pic_id join sa_customer cust on pic.customer_code = cust.customer_code
                where YEAR(sur.created_datetime) = $year $surveycust $surveytype";
            $datatable = $DB1->get_sql($query);
            $curr = $datatable[0];
            $next = $datatable[1];
            $count = $datatable[2];
            $modtitle = 'Catalog listing';

            $header = [
                'Project Tittle',
                'Project Code (SO Number)',
                'Project Type',
                'Project Category',
                'Customer Name',
                'PIC',
                'PIC Phone',
                'Status',
                'Created Date',
                'Reply Date',
                'Nilai',
                'Resource Terbaik Menurut Customer',
                'Project Memiliki Perencanaan Kerja yang Baik',
                'Project Team Terhadap Permintaan Customer',
                'Penanganan insiden',
                'Permintaan Perubahan (Change Request): Atas permintaan yang disetujui',
                'Dokumentasi project disampaikan kepada customer dengan lengkap',
                'Customer dengan umum puas dengan Hasil Kerja',
                'Pekerjaan team project sudah sesuai persyaratan dalam dokumen RFP/RKS',
                'Solusi yang diberikan PT. Mastersystem infotama memenuhi kebutuhan bisnis perusahaan',
                'Silahkan masukan saran perubahan anda untuk meningkatkan kinerja team project',
                'Action'
            ];
            $header1 = '';
            foreach ($header as $head) {
                $header1 .= "<th class='text-center align-middle'>" . $head . "</th>";
            }
            ?>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered hover stripe display compact dataTableMulti" id="trx_survey" width="100%" cellspacing="0">
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
                                    //echo "<td>" . $curr['survey_id'] . "</td>";
                                    echo "<td>" . $curr['project_title'] . "</td>"; //project title
                                    echo "<td>" . $curr['project_code'] . " ( " . $curr['so_number'] . " )</td>"; //project Code
                                    echo "<td>" . $curr['project_type'] . "</td>"; //project type
                                    echo "<td>" . $curr['type_of_service'] . "</td>"; //cust name
                                    echo "<td>" . $curr['customer_company_name'] . "</td>"; //cust name
                                    echo "<td>" . $curr['pic_name'] . " (" . $curr['pic_email'] . ")</td>"; //pic_name dan pic_email
                                    echo "<td>" . $curr['pic_phone'] . "</td>"; //pic_phone
                                    echo "<td>" . $curr['status'] . "</td>"; //status
                                    echo "<td>" . $curr['created_datetime'] . "</td>"; //created datetime
                                    echo "<td>" . $curr['reply_datetime'] . "</td>"; //Reply datetime

                                    $rating = json_decode($curr['main_rating'], true); //count rating average value
                                    $average_value = 0;
                                    $count = 0;
                                    $total_value = 0;
                                    $weight = 1;
                                    foreach ($rating as $score) {
                                        $total_value += $score['value'] * $weight;
                                        $count++;
                                    }
                                    $average_value = $total_value / ($count);
                                    echo "<td>" . $average_value . "</td>";


                                    // $rating = json_decode($curr['main_rating'], true); //count rating average value
                                    // $average_value = 0;
                                    // $count = 0;
                                    // $total_value = 0;
                                    // $weight = 1;
                                    // foreach ($rating as $score) {
                                    //     $total_value += $score['value'] * $weight;
                                    //     $count++;
                                    // }
                                    // $average_value = $total_value / ($count);
                                    // echo "<td>" . $average_value . "</td>";





                                    $engineer = json_decode($curr['main_engineer'], true); //engineer
                                    echo "<td><ul>";
                                    foreach ($engineer as $score) {
                                        echo "<li>" . $score['name'] . " (" . $score['email'] . ") </li>";
                                        if ($score['email'] == 'none') {
                                            $resource_exist = false;
                                        }
                                    }

                                    $question_id = json_decode($curr['main_rating'], true); // question_id = 2 Rating answer
                                    // if (count($question_id))
                                    //     echo "<td>" . 'masuk sini' . "</td>";
                                    // else {
                                    $question_id2 = '';
                                    $question_id5 = '';
                                    $question_id11 = '';
                                    $question_id14 = '';
                                    $question_id17 = '';
                                    $question_id20 = '';
                                    $question_id23 = '';
                                    $question_id35 = '';

                                    foreach ($question_id as $value) {

                                        if ($value['question_id'] == '2') {
                                            $question_id2 = $value['value'];
                                        }

                                        if ($value['question_id'] == '5') {
                                            $question_id5 = $value['value'];
                                        }

                                        if ($value['question_id'] == '8') {
                                            $question_id8 = $value['value'];
                                        }

                                        if ($value['question_id'] == '11') {
                                            $question_id11 = $value['value'];
                                        }

                                        if ($value['question_id'] == '14') {
                                            $question_id14 = $value['value'];
                                        }

                                        if ($value['question_id'] == '17') {
                                            $question_id17 = $value['value'];
                                        }

                                        if ($value['question_id'] == '20') {
                                            $question_id20 = $value['value'];
                                        }

                                        if ($value['question_id'] == '23') {
                                            $question_id23 = $value['value'];
                                        }

                                        if ($value['question_id'] == '35') {
                                            $question_id8 = '';
                                        }
                                    }
                                    echo "<td>" . $question_id2 . "</td>";
                                    echo "<td>" . $question_id5 . "</td>";
                                    echo "<td>" . $question_id8 . "</td>";
                                    echo "<td>" . $question_id11 . "</td>";
                                    echo "<td>" . $question_id14 . "</td>";
                                    echo "<td>" . $question_id17 . "</td>";
                                    echo "<td>" . $question_id20 . "</td>";
                                    echo "<td>" . $question_id23 . "</td>";

                                    $essay = json_decode($curr['main_essay'], true); //essay answer
                                    // if ($essay == 1) {
                                    //     echo "<td>" . $essay[0]['value'] . "</td>";
                                    // } 

                                    echo "<td>";
                                    $x = 1;
                                    foreach ($essay as $value) {
                                        $well = $value['value'];
                                        echo "$x . $well </br>";
                                        $x++;
                                    }
                                    echo "</td>";


                                    echo "</ul></td>";
                                    if ($resource_exist == false) {
                                        echo "<td><a href='index.php?mod=edit_survey&&id=" . $curr['survey_id'] . "'><i class='fa fa-pencil'></i></a></td>"; //action
                                    } else {
                                        echo "<td></td>";
                                        echo "</tr>";
                                    }
                                    echo "</tr>";
                                } while ($curr = $next->fetch_assoc());
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
<?php

    } else {
        $ALERT->notpermission();
    }
    // End Body
    //} 
}
?>
<script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.bundle.min.js'></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $("#filterDialog").hide();
        $('#filtershow').click(function() {
            $('#filterDialog').dialog({
                minWidth: 850
            });
        });
        $('#selectCust option[value="<?php echo $scriptcust; ?>"]').attr("selected", "selected");

        $('#filterbtn').click(function() {
            var years = custs = tipes = temps = '';
            var year = $('#pilihtahun option:selected').val();
            if (year != '') {
                years = '&year=' + year;
            }
            var cust = $('#selectCust option:selected').val();
            if (cust != '') {
                custs = '&customer=' + cust;
            }
            var tipe = $('#selectTipe option:selected').val();
            if (tipe != '') {
                tipes = '&tipe=' + tipe;
            }
            window.location.href = 'index.php?mod=survey&sub=dashboard_survey' + years + custs + tipes + temps;
        });

        $('#selectCust').select2();

        var table = $('#trx_survey').DataTable({
            dom: 'Blfrtip',
            autoWidth: true,
            select: {
                style: 'single'
            },
            buttons: [
                /*{
                   text: "<i class='fa fa-eye'></i>",
                   action: function () {
                       var rownumber = table.rows({selected: true}).indexes();
                       var survey_id = table.cell( rownumber,0 ).data();
                       window.location.href = "index.php?mod=trx_survey&act=view&survey_id="+survey_id;
                   }
                },*/
                // {
                //     text: "<i class='fa fa-download'></i>",
                //     action: function() {
                //         var yearExport = $('#pilihtahun option:selected').val();
                //         //var tempExport = $('#selectTemp option:selected').val();
                //         var custExport = $('#selectCust option:selected').val();
                //         var tipeExport = $('#selectTipe option:selected').val();
                //         window.location.href = "components/modules/ajax/export2.php?year=" + yearExport +"&cust="+custExport+"&tipe="+tipeExport;
                //     }
                // },
                {
                    extend: 'excelHtml5',
                    text: "<i class='fa-solid fa-file-excel'></i>",
                    title: 'Survey' + <?php echo date("YmdGis"); ?>
                },
            ],
            "columnDefs": [{
                    "targets": 5,
                    "autoWidth": true
                },
                {
                    "target": 0,
                    "visible": false
                }
            ],
        });
    });

    //bar chart total survey
    // pengisian survey
    <?php
    $query2 = "select count(if(status='Active' and project_type is not null, 1, null)) as active, count(if(status='default' AND project_type is not null, 1, null)) as inactive, count(if(status='submitted' AND project_type is not null, 1, null)) as submitted from sa_survey where year(created_datetime) = $year";
    $data2 = $DB1->get_sql($query2);
    $pengisian_survey = $data2[0];
    $on_process = $pengisian_survey['active'];
    $default = $pengisian_survey['inactive'];
    $completed = $pengisian_survey['submitted'];
    // $query2 = "SELECT status from sa_survey sur join sa_trx_survey trx on sur.survey_id = trx.survey_id join sa_pic pic on sur.pic_id = pic.pic_id join sa_customer cust on pic.customer_code = cust.customer_code where YEAR(sur.created_datetime) = $year $surveycust $surveytype LIMIT 0,100";
    // $data2 = $DB1->get_sql($query2);
    // $curr = $data2[0];
    // $next = $data2[1];
    // $datacount = $data2[2];
    // $count = 0;
    // $fill = 0;
    // $auto = 0;
    // $none = 0;

    // if ($datacount > 0) {
    //     do {
    //         switch ($curr['status']) {
    //             case 'auto_submit':
    //                 $auto++;
    //                 break;
    //             case 'active':
    //                 $none++;
    //                 break;
    //             case 'submitted':
    //                 $fill++;
    //                 break;
    //         }
    //         $count++;
    //     } while ($curr = $next->fetch_assoc());
    // } 
    ?>

    var ctx = $("#survey_bar_chart");
    var myLineChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Active', 'Default', 'Submitted'],
            datasets: [{
                data: [<?= $on_process . ',' . $default . ',' . $completed ?>],
                label: "Total",
                backgroundColor: ['blue', 'orange', 'red'],
                position: 'bottom'
            }, ]
        },
        options: {
            legend: {
                position: 'bottom'
            }
        }
    });

    // project Tersurvey
    <?php
    // $query3 = "SELECT count(distinct(so_number)) as countss from sa_survey sur join sa_trx_survey trx on sur.survey_id = trx.survey_id join sa_pic pic on sur.pic_id = pic.pic_id join sa_customer cust on pic.customer_code = cust.customer_code where YEAR(sur.created_datetime) = $year $surveycust $surveytype LIMIT 0,100";
    // $data3 = $DB1->get_sql($query3);
    // $curr = $data3[0];
    // $next = $data3[1];
    // $datacount = $data3[2];

    // $mdlname2 = "KPI_BOARD";
    // $DBx = get_conn($mdlname2);
    // $queryb = "select count(if(verif_status='Completed;' and isi is not null, 1, null)) as surveyed, count(if(verif_status='Completed;' AND isi is null, 1, null)) as not_surveyed, count(*) as total from sa_kpi_board";
    // $resultboard = $DBx->get_sql($queryb);
    // $result = $resultboard[0];

    // $surveyed = $result['surveyed'];
    // $hold = $result['not_surveyed'];
    // $closed = $result['total'];
    $mdlname2 = "KPI_BOARD";
    $DBx = get_conn($mdlname2);
    $queryb = "select count(if(b.so_number is not null,1,null)) as rfr, count(if(b.so_number is NULL,1,null)) as reviewed from sa_kpi_board a left join sa_survey.sa_survey b on a.so_number = b.so_number";

    // $queryb = "select count(if(a.status='Ready for Review', 1, null)) as rfr, count(if(a.status='Ready for Approve' , 1, null)) as rfa, count(if(a.status='Reviewed' , 1, null)) as reviewed from sa_kpi_board a join sa_survey.sa_survey b on a.so_number= b.so_number";
    $resultboard = $DBx->get_sql($queryb);
    $result = $resultboard[0];

    $ready_for_review = $result['rfr'];
    // $ready_for_approve = $result['rfa'];
    $reviewed = $result['reviewed'];
    ?>

    var dtx = $("#surveyed_project_count");
    var myLineChart2 = new Chart(dtx, {
        type: 'doughnut',
        data: {
            labels: ['Reviewed', 'Ready for Review'],
            datasets: [{
                data: [<?= $reviewed . ',' . $ready_for_review ?>],
                label: "Total",
                backgroundColor: ['blue', 'orange', 'red'],
                position: 'bottom'
            }, ]
        },
        options: {
            legend: {
                position: 'bottom'
            }
        }
    });
</script>
<style>
    td {
        max-width: 250px !important;
        word-wrap: break-word;
        min-width: 100px !important;
    }
</style>