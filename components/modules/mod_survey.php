<?php
if (isset($_GET['sub'])) {
    include("components/modules/survey/" . $_GET['sub'] . ".php");
} else
if ((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $released = "1671529049";
    $author = 'Syamsul Arham';
} else {

    $modulename = "SURVEY";
    $mdl_permission = useraccess_v2($modulename);
    if (USERPERMISSION_V2 == "810159712762c176ee4bbb27da703a78") { ?>
        <script>
            $(document).ready(function() {
                var table = $('#survey').DataTable({
                    dom: 'Blfrtip',
                    select: {
                        style: 'single'
                    },
                    buttons: [{
                            text: "<i class='fa fa-plus'></i>",
                            action: function() {
                                window.location.href = "index.php?mod=survey&act=add";
                            },
                            // enabled: false
                        },
                        {
                            extend: 'csvHtml5',
                            text: "<i class='fa-solid fa-file-excel'></i>",
                            title: 'Report Review Survey Question Full' + <?php echo date("YmdGis"); ?>
                        },
                        {
                            text: "<span title='View'><i class='fa fa-eye'></i></span>",
                            action: function() {
                                var rownumber = table.rows({
                                    selected: true
                                }).indexes();
                                var survey_id = table.cell(rownumber, 0).data();
                                if (survey_id == null) {
                                    alert("Silahkan pilih project");
                                } else {
                                    window.location.href = "index.php?mod=survey&act=view&id=" + survey_id + "&submit=Submit";
                                }
                            },
                            //    enabled: false
                        },
                        {
                            text: "<span title='Delete'><i class='fa fa-trash'></i></span>",
                            action: function() {
                                var rownumber = table.rows({
                                    selected: true
                                }).indexes();
                                var survey_link = table.cell(rownumber, 11).data();
                                cancelSurvey(survey_link);
                            }
                        },
                        {
                            text: "<i class='fa fa-external-link'></i>",
                            action: function() {
                                var rownumber = table.rows({
                                    selected: true
                                }).indexes();
                                var survey_id = table.cell(rownumber, 0).data();
                                var link = table.cell(rownumber, 11).data();
                                var surveylink = link.replace(/&amp;/g, '&');
                                $('#linkSurvey').attr('href', surveylink);
                                var projName = table.cell(rownumber, 8).data();
                                $('#projectName').text(projName);
                                if (survey_id == null) {
                                    alert("Silahkan pilih project");
                                } else {
                                    $('#dialogSurvey').dialog({
                                        minWidth: 850
                                    });
                                }
                                //window.location.href = "index.php?mod=survey&act=view&survey_id="+survey_id;
                            }
                        },
                        {
                            text: "<i class='fa fa-mail-forward'></i>",
                            action: function() {
                                var rownumber = table.rows({
                                    selected: true
                                }).indexes();
                                var survey_id = table.cell(rownumber, 0).data();
                                var link = table.cell(rownumber, 11).data();
                                var surveylink = link;
                                $('#linkSurveyReminder').html(surveylink);
                                var projName = table.cell(rownumber, 8).data();
                                $('#projectNameReminder').text(projName);
                                if (survey_id == null) {
                                    alert("Silahkan pilih project");
                                } else {
                                    $('#dialogReminder').dialog({
                                        minWidth: 850
                                    });
                                }
                            }
                        },
                        {
                            text: '<button tabindex="0" data-toggle="modal" data-target="#modalimport" title="import data" class = "fa-solid fa-file-import"></button>',
                            enabled: true
                        }
                        // {
                        //     text: '<span class="d-inline-block" tabindex="0" data-toggle="modal" data-target="#modalimport" title="import data"><i class="fa-solid fa-file-import"></i></span>',
                        //     enabled: true
                        // },
                    ],
                    "columnDefs": [{
                        "targets": [0, 1, 2, 3, 4, 11, 12, 13, 14, 15],
                        "visible": false,
                    }],
                });
            });
        </script>
        <?php

        // Function
        //if($_SESSION['Microservices_UserLevel'] == "Administrator") {
        function view_data($tblname)
        {

            if (!isset($_GET['year']))
                $year =  date("Y");
            else
                $year = $_GET['year'];

            // Definisikan tabel yang akan ditampilkan dalam DataTable
            global $DB1;
            $primarykey = "survey_id";
            $condition = "";
            $order = "";
            if (isset($_GET['err']) && $_GET['err'] == "datanotfound") {
                global $ALERT;
                $ALERT->datanotfound();
            }
            //view_table($DB1, $tblname, $primarykey, $condition, $order);
            $query = "SELECT template_name, pic_name, project_code, project_name, survey_link,souvenir, cust.customer_company_name, survey.created_by, survey.created_datetime from sa_survey survey join sa_question_template temp on survey.main_template_id = temp.template_id join sa_pic pic on pic.pic_id = survey.pic_id join sa_customer cust on pic.customer_code = cust.customer_code where survey.status = 'Active' AND YEAR(survey.created_datetime) = " . $year . " LIMIT 0,100";
            $datatable = $DB1->get_sql($query);
            $ddatatable = $datatable[0];
            $qdatatable = $datatable[1];
            $tdatatable = $datatable[2];
            $modtitle = 'Catalog listing';

            $queryyear = "SELECT DISTINCT(YEAR(created_datetime)) as year from sa_survey where created_datetime != '' AND status = 'active' order by created_datetime desc";
            $getyear = $DB1->get_sql($queryyear);
            $years = $getyear[0];
            $prev_year = $getyear[1];

            $header = ['Name', 'Project Code', 'Project Name', 'Customer', 'Template Name', 'Survey Link', 'Souvenir', 'Create Date'];
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
                <div class="table-responsive">
                    <table class="table table-bordered hover stripe display compact dataTableMulti" id="<?php echo $tblname; ?>" width="100%" cellspacing="0">
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
                            if ($tdatatable > 0) {
                                do { ?>
                                    <tr>
                                        <?php
                                        $datatable_header2 = $DB1->get_columns($tblname);
                                        $ddatatable_header2 = $datatable_header2[0];
                                        $qdatatable_header2 = $datatable_header2[1];
                                        ?>
                                <?php
                                    echo "<td>" . $ddatatable['pic_name'] . "</td>";
                                    echo "<td>" . $ddatatable['project_code'] . "</td>";
                                    echo "<td>" . $ddatatable['project_name'] . "</td>";
                                    echo "<td>" . $ddatatable['customer_company_name'] . "</td>";
                                    echo "<td>" . $ddatatable['template_name'] . "</td>";
                                    echo "<td>" . $ddatatable['survey_link'] . "</td>";
                                    echo "<td>" . $ddatatable['souvenir'] . "</td>";
                                    echo "<td>" . $ddatatable['created_datetime'] . "</td>";
                                    echo "</tr>";
                                } while ($ddatatable = $qdatatable->fetch_assoc());
                            }
                                ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php
        }
        function check_data($tblname, $condition)
        {
            // Definisikan tabel yang akan ditampilkan dalam DataTable
            global $DB1;
            $primarykey = "id";
            $order = "";
            if (isset($_GET['err']) && $_GET['err'] == "datanotfound") {
                global $ALERT;
                $ALERT->datanotfound();
            }
            view_table($DB1, $tblname, $primarykey, $condition, $order, 0, 0);
        }
        function form_data($tblname)
        {
            include("components/modules/survey/form_survey.php");
        }

        // End Function

        //$database = 'sa_survey';
        //include("components/modules/survey/connection.php");
        //$DB1 = new Databases('localhost', 'root', '', $database);
        $mdlname = "SURVEY";
        $DB1 = get_conn($mdlname);
        $tblname = 'survey';
        $tblname3 = 'trx_survey';
        $tblname2 = 'survey survey join sa_question_template temp on survey.main_template_id = temp.template_id join sa_pic pic on pic.pic_id = survey.pic_id join sa_customer cust on pic.customer_code = cust.customer_code';

        include("components/modules/survey/func_survey.php");

        // Body
        ?>
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Survey List</h6>
                </div>
                <div class="card-body">
                    <?php
                    if (!isset($_GET['act'])) {
                        $condition = "status='Active'";
                        check_data($tblname, $condition);
                    } elseif ($_GET['act'] == 'add') {
                        form_data($tblname);
                    } elseif ($_GET['act'] == 'new') {
                        new_projects($tblname);
                    } elseif ($_GET['act'] == 'view') {
                        form_data($tblname);
                    } elseif ($_GET['act'] == 'del') {
                        echo 'Delete Data';
                    } elseif ($_GET['act'] == 'save') {
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
    // } 
}
?>

<div class='dialogSurvey' id='dialogSurvey'>
    <table width='800px'>
        <tr>
            <td rowspan='3'></td>
            <td style='border: thin solid #dadada; padding:20px'>
                <img src='media/images/profiles/MSI-logo-revisi2.png' style='width:204px;height:74px'>
                <br /><br />
                <p>Bapak/Ibu Pelanggan yang Terhormat,</p>
                <p>
                <p>Kami mengucapkan terima kasih atas kesempatan yang diberikan kepada kami dalam project <span id='projectName'></span></p>
                <p>Sebagai masukan dan koreksi terhadap layanan kami, kami berharap Bapak/Ibu
                    dapat meluangkan waktu mengisi post-project review dengan mengklik <a id="linkSurvey" href="">link</a> berikut ini.</p>
                <p>Kami sangat berharap dapat terus melayani kebutuhan Bapak/Ibu untuk jangka panjang.</p>
                <p>Komitmen Mastersystem adalah senantiasa memberikan Service Excellence kepada para Pelanggan,
                    sehingga masukan dan koreksi Bapak/Ibu sangat berharga bagi kami untuk terus memperbaiki kualitas layanan.</p>
                </p>
                <p>
                </p>
                <p>Terimakasih,</p>
                <p>Customer Care<br /><br />Email : Customer.Care@mastersystem.co.id</p>
    </table>
</div>

<div class='dialogReminder' id='dialogReminder'>
    <table width='800px'>
        <tr>
            <td rowspan='3'></td>
            <td style='border: thin solid #dadada; padding:20px'>
                <img src='media/images/profiles/MSI-logo-revisi2.png' style='width:204px;height:74px'>
                <br /><br />
                <p>Bapak/Ibu Pelanggan yang Terhormat,</p>
                <p>
                <p>Perkenalkan saya dengan Henny Anggra, customer care dari PT Mastersystem Infotama.</span></p>
                <p>Mohon konfirmasinya apakah Bapak/Ibu sudah menerima email dari kami terkait survey project review untuk Project:
                    “<span id="projectNameReminder"></span>”</p>
                <p>Jika belum, mohon ketersediaan Bapak/Ibu untuk mengisi survey berikut ini:</p>
                <p><span id="linkSurveyReminder"></span></p>
                </p>
                <p>
                </p>
                <p>Terimakasih,</p>
                <p>Customer Care<br /><br />Email : Customer.Care@mastersystem.co.id</p>
    </table>
</div>

<script>
    $(document).ready(function() {
        $('#dialogSurvey').hide();
        $('#dialogReminder').hide();
        $('#selectYear').change(function() {
            var year = $('#selectYear option:selected').val();
            if (year != '') {
                window.location.href = 'index.php?mod=trx_survey&year=' + year;
            }
        });
    });

    function cancelSurvey(survey_link) {
        if (confirm('Are you sure to cancel the survey?') == false) {
            return false;
        } else {
            $.ajax({
                url: "components/modules/ajax/ajax.php",
                type: "POST",
                datatype: "json",
                data: {
                    'act': 'cancelSurvey',
                    'survey_link': survey_link
                },
                cache: false,
                success: function(result) {
                    if ($.trim(result) == 'Success') {
                        alert('Survey Cancelled, link cannot be accessed now');
                    } else {
                        alert("Update Fail");
                    }
                }
            });
        }

    }

    function resendSurvey(survey_id) {
        if (confirm('Are you sure to send the survey reminder?') == false) {
            return false;
        } else {
            $.ajax({
                url: "components/modules/ajax/ajax.php",
                type: "POST",
                datatype: "json",
                data: {
                    'act': 'resendSurvey',
                    'survey_id': survey_id
                },
                cache: false,
                success: function(result) {
                    if ($.trim(result) == 'Success') {
                        alert('Survey Reminder has been mailed');
                    } else {
                        alert("Reminder fail to send");
                    }
                }
            });
        }

    }
</script>


<div class="modal fade" id="modalimport" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="col-sm-9" id="exampleModalLabel">Import Data Survey</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="" action="" enctype="multipart/form-data" method="post">
                    <div class="form-group">
                        <input type="file" name="excel" required value="">
                    </div>
                    <button type="submit" name="import" class="btn btn-primary">Upload</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php

$mdlname = "SURVEY";
$DB1 = get_conn($mdlname);

?>
<?php
// if (isset($_POST["import"])) {
//     // $koneksi = mysqli_connect("localhost", "root", "", "sa_survey");
//     $fileName = $_FILES["excel"]["name"];
//     $fileExtension = explode('.', $fileName);
//     $fileExtension = strtolower(end($fileExtension));

//     $newFileName = date("Y.m.d") . " - " . date("h.i.sa") . "." . $fileExtension;
//     $targetDirectory = "media/" . $newFileName;
//     move_uploaded_file($_FILES["excel"]["tmp_name"], $targetDirectory);

//     error_reporting(0);
//     ini_set('display_errors', 0);

//     require "components/modules/survey/excelReader/excel_reader2.php";
//     require "components/modules/survey/excelReader/SpreadsheetReader.php";

//     $reader = new SpreadsheetReader($targetDirectory);

//     $successCount = 0;
//     $failureCount = 0;
//     $isFirstRow = true;
//     if ($reader !== false) {
//         foreach ($reader as $key => $row) {
//             if ($isFirstRow) {
//                 $isFirstRow = false;
//                 continue;
//             }
//             $datetime = $row[0];
//             $survey_id = $row[1];

//             // TEMPLATE FULL



//             // TEMPLATE SIMPLE




//             $cobasurvey = $DB1->get_sqlV2("SELECT * FROM sa_survey WHERE survey_id = '$survey_id' AND status = 'Active'");
//             $data_survey = $cobasurvey[0];


//             $sa_trx_survey = $DB1->get_sqlV2("SELECT answer_id FROM sa_trx_survey ORDER BY answer_id DESC");
//             $data_trx_survey = $sa_trx_survey[0];
//             // untuk ambil dari sa_survey
//             $id_survey = $data_survey['survey_id'];
//             $survey_link = $data_survey['survey_link'];
//             $project_code = $data_survey['project_code'];
//             $project_title = $data_survey['project_name'];
//             $template_type = $data_survey['template_type'];
//             $souvenir = $data_survey['souvenir'];

//             // untuk ambil dari sa-trx_survey
//             $answer_id = $data_trx_survey['answer_id'] + 3;

//             // Convert the array to a JSON string




//             // Use the $main_rating_full_json in your SQL query
//             if (isset($id_survey)) {
//                 if ($template_type == "full") {
//                     $question_2 = $row[8];
//                     $question_5 = $row[9];
//                     $question_8 = $row[10];
//                     $question_11 = $row[11];
//                     $question_14 = $row[12];
//                     $question_17 = $row[13];
//                     $question_20 = $row[14];
//                     $question_23 = $row[15];

//                     $rating_average = $question_2 + $question_5 + $question_8 + $question_11 + $question_14 + $question_17 + $question_20 + $question_23;
//                     $hasil = $rating_average / 8;
//                     $question_essay_26 = $row[16];
//                     $question_essay_29 = $row[17];
//                     $flag = 0;
//                     $main_rating_full = [
//                         ["question_id" => "2", "value" => $question_2],
//                         ["question_id" => "5", "value" => $question_5],
//                         ["question_id" => "8", "value" => $question_8],
//                         ["question_id" => "11", "value" => $question_11],
//                         ["question_id" => "14", "value" => $question_14],
//                         ["question_id" => "17", "value" => $question_17],
//                         ["question_id" => "20", "value" => $question_20],
//                         ["question_id" => "23", "value" => $question_23]
//                     ];
//                     $main_essay = [
//                         ["question_id" => "29", "value" => $question_essay_29]
//                     ];
//                     $main_engineer = [
//                         ["name" => $question_essay_26, "email" => $question_essay_26]
//                     ];

//                     $main_rating_full_json = json_encode($main_rating_full);
//                     $main_essay_json = json_encode($main_essay);
//                     $main_engineer_json = json_encode($main_engineer);


//                     // $query = mysqli_query($koneksi, "INSERT into sa_trx_survey values('$answer_id',
//                     $query = $DB1->get_res("INSERT into sa_trx_survey values('$answer_id',
//         '$survey_id',
//         '$survey_link',
//         '$project_code',
//         '$project_title',
//         '$datetime',
//         '$template_type',
//         '$main_rating_full_json',
//         '$hasil',
//         '$main_essay_json',
//         '$main_engineer_json',
//         '$souvenir',
//         '$souvenir_address',
//         '$flag')");

//                     if ($query) {
//                         $successCount++;

//                         $updateStatusQuery = $DB1->get_res("UPDATE sa_survey SET status = 'Submitted' WHERE survey_id = '$survey_id'");

//                         if ($updateStatusQuery) {
//                             // Status updated successfully
//                             // Additional actions if needed
//                         } else {
//                             // Failed to update status
//                             // Handle accordingly
//                         }
//                     } else {
//                         $failureCount++;
//                         // Debugging: Print out any MySQL errors
//                         // var_dump(mysqli_error($koneksi));
//                     }
//                 } elseif ($template_type == "simple") {
//                     $question_simple_35 = $row[8];
//                     $question_simple_engineer_26 = $row[9];
//                     $question_essay_simple_29 = $row[10];

//                     $main_rating_simple = [["question_id" => "35", "value" => $question_simple_35]];
//                     $main_essay_simple = [["question_id" => "29", "value" => $question_essay_simple_29]];
//                     $main_engineer_simple = [["name" => $question_simple_engineer_26, "email" => $question_simple_engineer_26]];
//                     $main_rating_simple_json = json_encode($main_rating_simple);
//                     $main_essay_simple_json = json_encode($main_essay_simple);
//                     $main_engineer_simple_json = json_encode($main_engineer_simple);

//                     // $query = mysqli_query($koneksi, "INSERT into sa_trx_survey values('$answer_id',
//                     $query = $DB1->get_res("INSERT into sa_trx_survey values('$answer_id',
//         '$survey_id',
//         '$survey_link',
//         '$project_code',
//         '$project_title',
//         '$datetime',
//         '$template_type',
//         '$main_rating_simple_json',
//         '$question_simple_35',
//         '$main_essay_simple_json',
//         '$main_engineer_simple_json',
//         '$souvenir',
//         '$souvenir_address',
//         '$flag')");

//                     if ($query) {
//                         $successCount++;

//                         $updateStatusQuery = $DB1->get_res("UPDATE sa_survey SET status = 'Submitted' WHERE survey_id = '$survey_id'");

//                         if ($updateStatusQuery) {
//                             // Status updated successfully
//                             // Additional actions if needed
//                         } else {
//                             // Failed to update status
//                             // Handle accordingly
//                         }
//                     } else {
//                         $failureCount++;
//                         // Debugging: Print out any MySQL errors
//                         // var_dump(mysqli_error($koneksi));
//                     }
//                 }
//             }


//             if ($successCount > 0) {
//                 echo "<script>alert('Berhasil: $successCount data berhasil diimport.');</script>";
//             }

//             if ($failureCount > 0) {
//                 echo "<script>alert('Gagal: $failureCount data gagal diimport.');</script>";
//             }
//         }
//     }
// }



?>

<?php
if (isset($_POST["import"])) {
    $koneksi = mysqli_connect("mariadb.mastersystem.co.id:4006", "ITAdmin", "P@ssw0rd.1", "sa_survey");
    $fileName = $_FILES["excel"]["name"];
    $fileExtension = explode('.', $fileName);
    $fileExtension = strtolower(end($fileExtension));

    $newFileName = date("Y.m.d") . " - " . date("h.i.sa") . "." . $fileExtension;
    $targetDirectory = "media/" . $newFileName;
    move_uploaded_file($_FILES["excel"]["tmp_name"], $targetDirectory);

    error_reporting(0);
    ini_set('display_errors', 0);

    require "components/modules/survey/excelReader/excel_reader2.php";
    require "components/modules/survey/excelReader/SpreadsheetReader.php";

    $reader = new SpreadsheetReader($targetDirectory);
    $Sheets = $reader->Sheets();
    $successCount = 0;
    $failureCount = 0;
    $isFirstRow = true;
    if ($reader !== false) {
        foreach ($Sheets as $sheetIndex => $sheetName) {
            $reader->ChangeSheet($sheetName);
            // var_dump($reader);
            foreach ($reader as $key => $row) {

                if ($isFirstRow) {
                    $isFirstRow = false;
                    continue;
                }
                $datetime = $row[0];
                $survey_id = $row[1];

                // TEMPLATE FULL



                // TEMPLATE SIMPLE




                $cobasurvey = $DB1->get_sqlV2("SELECT * FROM sa_survey WHERE survey_id = '$survey_id' AND status = 'Active'");
                $data_survey = $cobasurvey[0];


                $sa_trx_survey = $DB1->get_sqlV2("SELECT answer_id FROM sa_trx_survey ORDER BY answer_id DESC");
                $data_trx_survey = $sa_trx_survey[0];
                // untuk ambil dari sa_survey
                $id_survey = $data_survey['survey_id'];
                $survey_link = $data_survey['survey_link'];
                $project_code = $data_survey['project_code'];
                $project_title = $data_survey['project_name'];
                $template_type = $data_survey['template_type'];
                $souvenir = $data_survey['souvenir'];
                $created_datetime = $data_survey['created_datetime'];
                $flag = "0";
                $souvenir_address = "";

                // untuk ambil dari sa-trx_survey
                $answer_id = $data_trx_survey['answer_id'] + 3;

                // Convert the array to a JSON string




                // Use the $main_rating_full_json in your SQL query
                if (isset($id_survey)) {
                    if ($template_type == "full") {
                        $question_2 = $row[8];
                        $question_5 = $row[9];
                        $question_8 = $row[10];
                        $question_11 = $row[11];
                        $question_14 = $row[12];
                        $question_17 = $row[13];
                        $question_20 = $row[14];
                        $question_23 = $row[15];
                        // $datetime = $row[0];
                        $survey_id = $row[1];

                        $rating_average = $question_2 + $question_5 + $question_8 + $question_11 + $question_14 + $question_17 + $question_20 + $question_23;
                        $hasil = $rating_average / 8;
                        $question_essay_26 = $row[16];
                        $question_essay_29 = $row[17];
                        // $flag = 0;
                        $main_rating_full = [
                            ["question_id" => "2", "value" => $question_2],
                            ["question_id" => "5", "value" => $question_5],
                            ["question_id" => "8", "value" => $question_8],
                            ["question_id" => "11", "value" => $question_11],
                            ["question_id" => "14", "value" => $question_14],
                            ["question_id" => "17", "value" => $question_17],
                            ["question_id" => "20", "value" => $question_20],
                            ["question_id" => "23", "value" => $question_23]
                        ];
                        $main_essay = [
                            ["question_id" => "29", "value" => $question_essay_29]
                        ];
                        $main_engineer = [
                            ["name" => $question_essay_26, "email" => $question_essay_26]
                        ];

                        $main_rating_full_json = json_encode($main_rating_full);
                        $main_essay_json = json_encode($main_essay);
                        $main_engineer_json = json_encode($main_engineer);


                        // $query = mysqli_query($koneksi, "INSERT into sa_trx_survey values('$answer_id',
                        $query = $DB1->get_res("INSERT into sa_trx_survey values('$answer_id',
        '$survey_id',
        '$survey_link',
        '$project_code',
        '$project_title',
        '$created_datetime',
        '$template_type',
        '$main_rating_full_json',
        '$hasil',
        '$main_essay_json',
        '$main_engineer_json',
        '$souvenir',
        '$souvenir_address',
        '$flag')");

                        if ($query) {
                            $successCount++;

                            $updateStatusQuery = $DB1->get_res("UPDATE sa_survey SET status = 'Submitted' WHERE survey_id = '$survey_id'");

                            if ($updateStatusQuery) {
                                // Status updated successfully
                                // Additional actions if needed
                            } else {
                                // Failed to update status
                                // Handle accordingly
                            }
                        } else {
                            $failureCount++;
                            // Debugging: Print out any MySQL errors
                            // var_dump(mysqli_error($koneksi));
                        }
                    } elseif ($template_type == "simple") {
                        $question_simple_35 = $row[8];
                        $question_simple_engineer_26 = $row[9];
                        $question_essay_simple_29 = $row[10];
                        // $datetime = $row[0];
                        $survey_id = $row[1];


                        $main_rating_simple = [["question_id" => "35", "value" => $question_simple_35]];
                        $main_essay_simple = [["question_id" => "29", "value" => $question_essay_simple_29]];
                        $main_engineer_simple = [["name" => $question_simple_engineer_26, "email" => $question_simple_engineer_26]];
                        $main_rating_simple_json = json_encode($main_rating_simple);
                        $main_essay_simple_json = json_encode($main_essay_simple);
                        $main_engineer_simple_json = json_encode($main_engineer_simple);

                        // $query = mysqli_query($koneksi, "INSERT into sa_trx_survey values('$answer_id',
                        $query = $DB1->get_res("INSERT into sa_trx_survey values('$answer_id',
        '$survey_id',
        '$survey_link',
        '$project_code',
        '$project_title',
        '$created_datetime',
        '$template_type',
        '$main_rating_simple_json',
        '$question_simple_35',
        '$main_essay_simple_json',
        '$main_engineer_simple_json',
        '$souvenir',
        '$souvenir_address',
        '$flag')");
                        var_dump($query);
                        die;

                        if ($query) {
                            $successCount++;

                            $updateStatusQuery = $DB1->get_res("UPDATE sa_survey SET status = 'Submitted' WHERE survey_id = '$survey_id'");

                            if ($updateStatusQuery) {
                                // Status updated successfully
                                // Additional actions if needed
                            } else {
                                // Failed to update status
                                // Handle accordingly
                            }
                        } else {
                            $failureCount++;
                            // Debugging: Print out any MySQL errors
                            // var_dump(mysqli_error($koneksi));
                        }
                    }
                }


                if ($successCount > 0) {
                    echo "<script>alert('Berhasil: $successCount data berhasil diimport.');</script>";
                }

                if ($failureCount > 0) {
                    echo "<script>alert('Gagal: $failureCount data gagal diimport.');</script>";
                }
            }
        }
    }
}

?>