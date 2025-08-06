<?php
if($_GET['act']=='view') {
    global $DB1;
$query = 'SELECT surv.survey_id, so_number, pic_name, pic_email, pic_phone, customer_company_name, trx.project_code, trx.project_title, surv.survey_link, main_rating, main_essay, main_engineer, trx.created_datetime
    from sa_survey surv join sa_trx_survey trx on surv.survey_id = trx.survey_id join sa_pic pic on surv.pic_id = pic.pic_id join sa_customer cust on pic.customer_id = cust.customer_id
    where trx.survey_id = "' . $_GET['survey_id'] . '"';
    $data = $DB1->get_sql($query);
    $ddata = $data[0];
    $qdata = $data[1];
    $tdata = $data[2];
    //print_r($ddata['main_rating']);
    $main_rating = json_decode($ddata['main_rating'],true);
    $main_essay = json_decode($ddata['main_essay'],true);
    $main_engineer = json_decode($ddata['main_engineer'],true);
}
?>
<div id="viewSurveyForm">
    <label for="inputCID3" class="col-form-label col-form-label-sm font-weight-bold">PIC :</label>
    <span><br/><?php echo $ddata['pic_name'];?></span>
    <br/><br/>
    <label for="inputCID3" class="col-form-label col-form-label-sm font-weight-bold">PIC Email :</label>
    <span><br/><?php echo $ddata['pic_email']; ?></span>
    <br/><br/>
    <label for="inputCID3" class="col-form-label col-form-label-sm font-weight-bold">PIC Phone :</label>
    <span><br/><?php echo $ddata['pic_phone']; ?></span>
    <br/><br/>
    <label for="inputCID3" class="col-form-label col-form-label-sm font-weight-bold">Nama Customer :</label>
    <span><br/><?php echo $ddata['customer_company_name']; ?></span>
    <br/><br/>
    <label for="inputCID3" class="col-form-label col-form-label-sm font-weight-bold">Nama Proyek :</label>
    <span><br/><?php echo $ddata['project_title']; ?></span>
</div>
<?php
$queryquestions = "SELECT survey_id, tq.template_type, tq.questions as tquestions, fq.questions as mquestions FROM sa_survey surv JOIN sa_question_template tq on surv.template_id = tq.template_id JOIN sa_question_template fq on surv.main_template_id = fq.template_id where survey_id = ".$_GET['survey_id'];
    $qq = $DB1->get_sql($queryquestions);
    $qq1 = $qq[0];

    $questionlist = array();
    $question_main = array();
    $questions = str_replace(']', '', str_replace('[', '', $qq1['tquestions']));
    $array_questions = explode(',', str_replace('"','',$questions));
    $query_temp = "select question_id, question, category, weight from sa_question_library where question_id IN (".$questions.")  order by CASE
    WHEN category = 'rating' THEN 1
    WHEN category = 'textbox' THEN 2
    WHEN category = 'engineer' THEN 3
    END ASC;";
    $questions = $DB1->get_sql($query_temp);
    $data_temp = $questions[0];
    $data_temp2 = $questions[1];
    $questionlist = array();
    do {
        array_push($questionlist, $data_temp);
    } while ($data_temp = $data_temp2->fetch_assoc());
    //print_r($qq1);
?>
<br/>
<p>Pertanyaan</p>
<ol class="list-group list-group-numbered">
<?php foreach($questionlist as $index => $each){
    $no = $index+1;
    echo '<li class="list-group-item">'.$each['question'].'<br/><br/>';
    foreach($main_rating as $key => $value){
        if($value['question_id'] == $each['question_id']){
            echo "<p> Nilai : " . $value['value'] . "</p>";
        }
    }
    foreach($main_essay as $key => $value){
        if($value['question_id'] == $each['question_id']){
            echo "<p> Jawaban : " . $value['value'] . "</p>";
        }
    }
    if($each['category'] == 'engineer'){
        foreach($main_engineer as $key => $value){
            echo "<p>Nama: ".$value['name']." (".$value['email'].")";
        }
    }
    echo "</li>";
} ?>
</ol>
<?php
if ($qq1['template_type'] == 'Simple Form') {
    $alt_rating = json_decode($ddata['alt_rating'],true);
    $alt_essay = json_decode($ddata['alt_essay'],true);
    $alt_engineer = json_decode($ddata['alt_engineer'],true);
    $main_temp = str_replace(']', '', str_replace('[', '', $qq1['mquestions']));
    $array_main = explode(',', str_replace('"', '', $main_temp));
    $query_temp = "select question_id, question, category, weight from sa_question_library where question_id IN (" . $main_temp . ")  order by CASE
	WHEN category = 'rating' THEN 1
	WHEN category = 'textbox' THEN 2
	WHEN category = 'engineer' THEN 3
END ASC;";
    $mquestions = $DB1->get_sql($query_temp);
    $main_temp = $mquestions[0];
    $main_temp2 = $mquestions[1];
    $questionmain = array();
    do {
        array_push($questionmain, $main_temp);
    } while ($main_temp = $main_temp2->fetch_assoc());
    ?>
<p>Pertanyaan Full Form</p>
<ol class="list-group list-group-numbered">
<?php foreach ($questionmain as $index => $each) {
            $no = $index + 1;
            echo '<li class="list-group-item">' . $each['question'] . '<br/><br/>';
            foreach ($alt_rating as $key => $value) {
                if ($value['question_id'] == $each['question_id']) {
                    echo "<p> Nilai : " . $value['value'] . "</p>";
                }
            }
            foreach ($alt_essay as $key => $value) {
                if ($value['question_id'] == $each['question_id']) {
                    echo "<p> Jawaban : " . $value['value'] . "</p>";
                }
            }
            if ($each['category'] == 'engineer') {
                foreach ($alt_engineer as $key => $value) {
                    echo "<p>Nama: " . $value['name'] . " (" . $value['email'] . ")";
                }
            }
            echo "</li>";
        } ?>
</ol>
<?php } ?>