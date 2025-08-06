<?php
libxml_use_internal_errors(true);
$rootfolder = $_SERVER['DOCUMENT_ROOT'];
include( $rootfolder."/MICROSERVICES/components/classes/func_databases_v3.php" ); 
include( $rootfolder."/MICROSERVICES/components/classes/func_component.php");

$database = 'sa_survey';
$DB1 = new Databases('locahost', 'username', '', 'sa_survey');

// $DB1 = new Databases('192.168.234.158', 'moodleuser', 'P@ssw0rd', $database);

$year = $_GET['year'];

$gettemplate = "SELECT questions, template_name FROM sa_question_template where template_id = ".$_GET['template_id'];
$temp_list = $DB1->get_sql($gettemplate);
$template = $temp_list[0];


// fungsi header dengan mengirimkan raw data excel
header("Content-type: application/vnd-ms-excel");

// membuat nama file ekspor "export-to-excel.xls"
header("Content-Disposition: attachment; filename=Report Review Survey ".$template['template_name']." Tahun ".$_GET['year'].".xls");

$questionslist = "select * from sa_question_library order by question_id asc";
$result2 = $DB1->get_sql($questionslist);
$question = $result2[0];
$questionnext = $result2[1];
$questions = array();
do {
    $questions[$question['question_id']] = $question['question'];
} while ($question = $questionnext->fetch_assoc());

$questions = str_replace(']', '', str_replace('[', '', $template['questions']));
$getquestions = "SELECT * FROM sa_question_library where question_id IN (".$questions.") AND category not like 'engineer'  order by CASE
WHEN category = 'rating' THEN 1
WHEN category = 'textbox' THEN 2
END ASC;";
$temp_questions = $DB1->get_sql($getquestions);
$currquestion = $temp_questions[0];
$nextquestion = $temp_questions[1];

$htmlString = '';
$htmlString .= "
<table border='1'>
<tr>
    <th>No</th>
    <th>Project Title</th>
    <th>Project Code</th>
    <th>Customer Name</th>
    <th>PIC</th>
    <th>PIC Phone</th>
    <th>Submit Date</th>
    <th>Nilai (Simple)</th>
    <th>Comment (Simple)</th>
    <th>Penilaian Engineer</th>";
    do {
        $htmlString .= "<th>" . $currquestion['question'] . "</th>";
    } while ($currquestion = $nextquestion->fetch_assoc()); 
    
$htmlString .= "</tr>";

$query = "SELECT surv.survey_id, so_number, pic_name, pic_email, pic_phone, customer_company_name, trx.project_code, trx.project_title,
        surv.survey_link, type, main_rating, main_essay, main_engineer, trx.created_datetime from sa_survey surv join sa_trx_survey trx 
        on surv.survey_id = trx.survey_id join sa_pic pic on surv.pic_id = pic.pic_id join sa_customer cust on pic.customer_id = cust.customer_id
        where YEAR(surv.created_datetime) = " . $year ." AND surv.main_template_id = '".$_GET['template_id']."'";
$result = $DB1->get_sql($query);
$curr = $result[0];
$next = $result[1];
$i = 1;

    do {
    $rating = json_decode($curr['main_rating'], true); //count rating average value
    $engineer = json_decode($curr['main_engineer'], true); //engineer
    $essay = json_decode($curr['main_essay'], true); //essay answer
    $average_value = '';
    $count = 0;
    $total_value = 0;
    $weight = 1;
    $comment = '';

    if ($curr['type'] == 'simple') {
        foreach ($rating as $score) {
            $total_value += $score['value'] * $weight;
            $count++;
        }
        $average_value = $total_value / ($count);


        $comment = $essay[0]['value'];
    }
    $htmlString .= "
    <tr>
        <td>$i</td>
        <td>".$curr['project_title']."</td>
        <td>".$curr['project_code']." ( ".$curr['so_number']." )</td>
        <td>".$curr['customer_company_name']."</td>
    <td>".$curr['pic_name']." ( ".$curr['pic_email']." ) </td>
    <td>".$curr['pic_phone']."</td>
    <td>".$curr['created_datetime']."</td>
    <td>".$average_value."</td>     
    <td>".$comment."</td>
    <td><ul>";

    foreach ($engineer as $score) {
        $htmlString .= "<li>".$score['name']." ( ".$score['email']." ) </li>";
    }

    $htmlString .= "</ul></td>";
    
    if ($curr['type'] == 'full') {
        foreach ($rating as $score) {
            $htmlString .= "<td>" . $score['value'] . "</td>";
        }
        foreach ($essay as $score) {
            $htmlString.= "<td>" . $score['value'] . "</td>";
        }
    }
    $htmlString .= "<tr>";
    $i++;
} while ($curr = $next->fetch_assoc());
$htmlString .= "</table>";
echo $htmlString;