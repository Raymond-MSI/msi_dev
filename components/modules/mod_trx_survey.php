<?php
if ((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $released = "1672653627";
    $author = 'Syamsul Arham';
} else {

    $modulename = "SURVEY";
    $mdl_permission = useraccess_v2($modulename);
    if (USERPERMISSION_V2 == "810159712762c176ee4bbb27da703a78") { ?>
        <script>
            $(document).ready(function() {
                var table = $('#trx_survey').DataTable({
                    dom: 'Blfrtip',
                    autoWidth: true,
                    select: {
                        style: 'single'
                    },
                    buttons: [{
                            extend: 'colvis',
                            text: "<i class='fa fa-columns'></i>",
                            collectionLayout: 'fixed four-column'
                        },
                        /*{
                           text: "<i class='fa fa-eye'></i>",
                           action: function () {
                               var rownumber = table.rows({selected: true}).indexes();
                               var survey_id = table.cell( rownumber,0 ).data();
                               window.location.href = "index.php?mod=trx_survey&act=view&survey_id="+survey_id;
                           }
                        },*/
                        {
                            text: "<i class='fa fa-download'></i>",
                            action: function() {
                                var yearExport = $('#selectYear option:selected').val();
                                var tempExport = $('#selectTemp option:selected').val();
                                window.location.href = "components/modules/ajax/export2.php?year=" + yearExport + "&template_id=" + tempExport;
                            }
                        }
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
        </script>
        <?php

        // Function
        //if($_SESSION['Microservices_UserLevel'] == "Administrator") {
        function view_data($tblname)
        {
            // Definisikan tabel yang akan ditampilkan dalam DataTable
            global $DB1;
            $primarykey = "answer_id";
            $condition = "";
            $order = "";
            if (isset($_GET['err']) && $_GET['err'] == "datanotfound") {
                global $ALERT;
                $ALERT->datanotfound();
            }
            //view_table($DB1, $tblname, $primarykey, $condition, $order);
            if (!isset($_GET['year']))
                $year =  date("Y");
            else
                $year = $_GET['year'];

            $querytemp = "SELECT template_id, template_name from sa_question_template WHERE template_type = 'Main Form' AND valid_year = " . $year;
            $templist = $DB1->get_sql($querytemp);
            $currtemp = $templist[0];
            $nexttemp = $templist[1];

            if (!isset($_GET['template_id'])) {
                $template_id = $currtemp['template_id'];
                $tempquery = "AND main_template_id = " . $currtemp['template_id'];
            } else {
                $template_id = $_GET['template_id'];
                $tempquery = "AND main_template_id = " . $template_id;
            }

            $queryyear = "SELECT DISTINCT(YEAR(created_datetime)) as year from sa_survey where created_datetime != '' order by created_datetime desc";
            $getyear = $DB1->get_sql($queryyear);
            $years = $getyear[0];
            $prev_year = $getyear[1];

            $query = "SELECT surv.survey_id, so_number, pic_name, pic_email, pic_phone, customer_company_name, trx.project_code, trx.project_title, surv.survey_link, main_rating, main_essay, main_engineer, trx.created_datetime
                from sa_survey surv join sa_trx_survey trx on surv.survey_id = trx.survey_id join sa_pic pic on surv.pic_id = pic.pic_id join sa_customer cust on pic.customer_code = cust.customer_code
                where YEAR(surv.created_datetime) = " . $year . " " . $tempquery . " LIMIT 0,100";
            $datatable = $DB1->get_sql($query);
            $curr = $datatable[0];
            $next = $datatable[1];
            $count = $datatable[2];
            $modtitle = 'Catalog listing';

            $header = ['Survey ID', 'Project Code (SO Number)', 'Project Name', 'Customer Name', 'Nilai', 'Comment', 'Engineer', 'PIC', 'PIC Phone', 'Submit Date'];
            $header1 = '';
            foreach ($header as $head) {
                $header1 .= "<th class='text-center align-middle'>" . $head . "</th>";
            }
        ?>

            <div class="card-body">
                <div>Tahun: <select id='selectYear'>
                        <?php
                        do {
                            $select = '';
                            if (isset($_GET['year']) && $_GET['year'] != '') {
                                $select = ($year == $years['year']) ? 'selected' : '';
                            }
                            echo "<option value='" . $years['year'] . "' $select>" . $years['year'];
                        } while ($years = $prev_year->fetch_assoc());
                        ?>
                    </select></div>
                <div>Template: <select id='selectTemp'>
                        <?php
                        do {
                            $select2 = '';
                            if (isset($_GET['template_id']) && $_GET['template_id'] != '') {
                                $select2 = ($template_id == $currtemp['template_id']) ? 'selected' : '';
                            }
                            echo "<option value='" . $currtemp['template_id'] . "' $select2>" . $currtemp['template_name'];
                        } while ($currtemp = $nexttemp->fetch_assoc());
                        ?>
                    </select></div>
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
                                    echo "<tr>";
                                    echo "<td>" . $curr['survey_id'] . "</td>";
                                    echo "<td>" . $curr['project_code'] . " ( " . $curr['so_number'] . " )</td>"; //project Code
                                    echo "<td>" . $curr['project_title'] . "</td>"; //project title
                                    echo "<td>" . $curr['customer_company_name'] . "</td>"; //cust name

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

                                    $essay = json_decode($curr['main_essay'], true); //essay answer
                                    if (count($essay) == 1)
                                        echo "<td>" . $essay[0]['value'] . "</td>";
                                    else {
                                        echo "<td>";
                                        $x = 1;
                                        foreach ($essay as $value) {
                                            echo $x . '.' . $value['value'] . '</br>';
                                            $x++;
                                        }
                                        echo "</td>";
                                    }

                                    $engineer = json_decode($curr['main_engineer'], true); //engineer
                                    echo "<td><ul>";
                                    foreach ($engineer as $score) {
                                        echo "<li>" . $score['name'] . " (" . $score['email'] . ") </li>";
                                    }
                                    echo "</ul></td>";
                                    echo "<td>" . $curr['pic_name'] . " (" . $curr['pic_email'] . ")</td>";
                                    echo "<td>" . $curr['pic_phone'] . "</td>";
                                    echo "<td>" . $curr['created_datetime'] . "</td>";
                                    echo "</tr>";
                                } while ($curr = $next->fetch_assoc());
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php
        }
        function form_data($tblname)
        {
            include("components/modules/trx_survey/form_trx_survey.php");
        }

        // End Function

        //$database = 'sa_survey';
        //include("components/modules/trx_survey/connection.php");
        //$DB1 = new Databases($hostname, $username, $userpassword, $database);
        $mdlname = "SURVEY";
        $DB1 = get_conn($mdlname);
        $tblname = 'trx_survey';


        include("components/modules/trx_survey/func_trx_survey.php");

        // Body
        ?>
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">trx_survey</h6>
                </div>
                <div class="card-body">
                    <?php
                    if (!isset($_GET['act'])) {
                        view_data($tblname);
                    } elseif ($_GET['act'] == 'view') {
                        form_data($tblname);
                    }
                    ?>
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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#selectYear').change(function() {
            var year = $('#selectYear option:selected').val();
            if (year != '') {
                window.location.href = 'index.php?mod=trx_survey&year=' + year;
            }
        });

        $('#selectTemp').change(function() {
            var temp = $('#selectTemp option:selected').val();
            var year = $('#selectYear option:selected').val();
            if (temp != '') {
                window.location.href = 'index.php?mod=trx_survey&year=' + year + '&template_id=' + temp;
            }
        });
    });
</script>
<style>
    td {
        max-width: 250px !important;
        word-wrap: break-word;
        min-width: 100px !important;
    }
</style>