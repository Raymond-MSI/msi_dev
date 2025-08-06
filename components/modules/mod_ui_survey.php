<?php
//connect db
$database = 'sa_survey';
$link = $_GET['link'];
$DB1 = new Databases('localhost', 'root', '', $database);

//check survey access
$ifexist = "select * from sa_trx_survey where survey_link = '" . $link . "'";
$exist = $DB1->get_sql($ifexist);
if($exist[2] >= 1){
    echo "Survey Already Submitted, no longer accessible";
    exit();
}

//retrieve data survey
$query = "select survey_id, pic_name, temp.questions as tquestions,so_number, survey.pic_id, pic_phone, temp.template_type, extra_information,main_template_id, pic_address, pic_email, pic_city, customer_company_name, temp.template_name, main.questions as mquestions, main.template_name, survey.template_id, pic.customer_id from sa_survey survey join sa_pic pic on survey.pic_id = pic.pic_id join sa_customer cust on pic.customer_id = cust.customer_id join sa_question_template temp on temp.template_id = survey.template_id left join sa_question_template main on survey.main_template_id = main.template_id where survey_link = '".$link."' AND status = 'active'";
$survey = $DB1->get_sql($query);
$data = $survey[0];

if($survey[2] == 0){
    echo "Survey does not exist";
    exit();
}

$questions = str_replace(']', '', str_replace('[', '', $data['tquestions']));
$array_questions = explode(',', str_replace('"','',$questions));

//get main question data
$query_temp = "select question, category from sa_question_library where question_id IN (".$questions.")  order by CASE
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

//get main simple question data
if($data['template_type'] == 'Simple Form'){
    $main_temp = str_replace(']', '', str_replace('[', '', $data['mquestions']));
    $array_main = explode(',', str_replace('"','',$main_temp));
    $query_temp = "select question, category from sa_question_library where question_id IN (".$main_temp.")  order by CASE
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
}

//get project data
$database2 = 'sa_wrike_integrate';
$DB2 = new Databases('localhost', 'root', '', $database2);
$query2 = "select title, project_type, no_so, first_name, last_name, resource_email, pl.project_code from sa_wrike_project_list  pl inner join sa_wrike_assignment ass on pl.project_code = ass.project_code inner join sa_wrike_contact_user cu on ass.resource_id = cu.author_id  where no_so = '".$data['so_number']."' group by title, resource_email";
$project = $DB2->get_sql($query2);
$data2 = $project[0];

$proj_title = $data2['title'];
$proj_code = $data2['project_code'];
$proj_type = $data2['project_type'];
$so_number = $data['so_number'];
$engineer_list = array();
do {
    array_push($engineer_list, $data2['first_name'] . ' ' . $data2['last_name'].' - '.$data2['resource_email']);
} while ($data2 = $project[1]->fetch_assoc());
?>
<div id="surveyFormDiv">
<section style="margin-bottom: 50px;">
    <div class="container">
        <div class="navbar-brand">
                <img src="/microservices/media/images/profiles/MSI-logo-revisi2.png" alt="Mastersystem Infotama" style="height: 3.8rem;">
        </div>
    </div>
</section>
    <div class="row" style="background-color:rgba(200,200,200,0.56); margin: 10px 0px 10px 0px; padding-top: 10px; border-radius:10px;">
        <div class="col-lg-6">
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">PIC</label>
                <div class="col-sm-9">
                <span><?php echo $data['pic_name'];?></span>    
            </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">PIC Email</label>
                <div class="col-sm-9">
                <span><?php echo $data['pic_email']; ?></span>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">PIC Phone</label>
                <div class="col-sm-9">
                <span><?php echo $data['pic_phone']; ?></span>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Customer Name</label>
                <div class="col-sm-9">
                <span><?php echo $data['customer_company_name']; ?></span>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Title</label>
                <div class="col-sm-9">
                <span><?php echo $proj_title; ?></span>
                </div>
            </div>
        </div>
    </div>
    <br/>
    <?php if($data['extra_information'] != '' && isset($data['extra_information'])){ ?>
        <div style="font-size:22px;background-color:blanchedalmond; border-radius: 10px; border: 1px solid #555;">
        &emsp;Information
        <p style="font-size:19px;margin-left:1em;margin-right:1em;"><?php echo $data['extra_information']; ?></p>
        </div>
        <br/>
        <?php } ?>
    <div id='extrainf' style="font-size: 22px;background-color:#DDD7D7;border:1px solid #555; border-radius: 10px">
        &emsp;Rating Information
        <br/>
        <p style="font-size:19px">&emsp;1 = Very Bad
        <br/>
        &emsp;10 = Very Good</p>
    </div>
    <br/>
    <div id="questionnaire_form">
        <form action='#' id='surveyForm'>
            <input type="hidden" id="survey_link" name="survey_link" value=<?php echo $_GET['link']; ?>>
            <input type="hidden" id="survey_id" name="survey_id" value=<?php echo $data['survey_id']; ?>>
            <input type="hidden" id="pic_id" name="pic_id" value=<?php echo $data['pic_id']; ?>>
            <?php foreach($questionlist as $index => $each){
                $no = $index+1;
                echo '<p class="surveyText">'.$no.'. '.$each['question'].'</p>';
                echo '<div class="surveyBox">';
                switch ($each['category']) {
                    case 'textbox': 
                        echo "<textarea class='form-control' id='essay$index' name='essay_".$index."_".$array_questions[$index]."' required  rows=4 style='width:500px'></textarea>";
                        break;
                    case 'rating':
                        $i = 1;
                        while ($i <= 10) {
                            echo "<input type='radio' id='radio".$i."_".$index."' name='radio_".$index."_".$array_questions[$index]."' value='$i' required>";
                            echo "<label for='radio".$i."_".$index."'>&nbsp;$i</label>&emsp;&emsp;";
                            $i++;
                        }
                        break;
                    case 'engineer': 
                        foreach($engineer_list as $indexi => $engineer){
                            $x = 1;
                            $xx = $indexi+1;
                            $email = explode(' - ', $engineer);
                            echo "<p>$xx. $email[0]</p>";
                            while ($x<= 10){
                                echo "<input type='radio' id='radio".$x."_".$email[1]."' name='radio_".$index."_engineer_".$email[1]."' value='$x'>";
                                echo "<label for='radio".$x."_".$email[1]."'>&nbsp;$x</label>&emsp;&emsp;";
                                $x++;
                            }
                        }
                }
                echo "</div>";
            } ?>
            <?php if($data['template_type'] == 'Simple Form'){ ?>
                <input type="hidden" name='main_question_count' value="start_of_main">
                <p id='clickFullMain'>click <span id='clickFull' style="color:blue; font-size: 22px;">here</span> to view Full Survey Form (Optional)</p>
                <div id="mainForm">
                <?php foreach($questionmain as $index => $each){
                    $no = $index+1;
                    echo '<p class="surveyText">'.$no.'. '.$each['question'].'</p>';
                    echo '<div class="surveyBox">';
                    switch ($each['category']) {
                    case 'textbox':
                        echo "<textarea class='form-control' id='main_essay_$index' name='main_essay_".$index."_".$array_main[$index]."' rows=4 style='width:500px'></textarea>";
                        break;
                    case 'rating':
                        $i = 1;
                        while ($i <= 10) {
                            echo "<input type='radio' id='main_radio".$i."_".$index."' name='main_radio_".$index."_".$array_main[$index]."' value='$i'>";
                            echo "<label for='main_radio_".$i."_".$index."'>&nbsp;$i</label>&emsp;&emsp;";
                            $i++;
                        }
                        break;
                        case 'engineer': 
                            foreach($engineer_list as $indexi => $engineer){
                                $x = 1;
                                $xx = $indexi+1;
                                $email = explode(' - ', $engineer);
                                echo "<p>$xx. $email[0]</p>";
                                while ($x<= 10){
                                    echo "<input type='radio' id='main_radio".$x."_".$email[1]."' name='main_radio_".$index."_engineer_".$email[1]."' value='$i'>";
                                    echo "<label for='main_radio".$x."_".$email."'>&nbsp;$x</label>&emsp;&emsp;";
                                    $x++;
                                }
                            }
                }
                echo "</div>";
            } ?>
                <input type="hidden" name='main_question_count' value="end_of_main">
                </div>
            <?php } ?>
        </form>
        <br/>
        <button id='submitSurvey'>Submit</button>
    </div>
</div>
<style>
.surveyText{
    font-size: 22px;
    border-radius: 5px 5px 0px 0px;
    border:1px solid #555;
    width: fit-content;
    padding: 0px 5px 0px 5px;
    margin-bottom: 0px;
    margin-top: 15px;
    background-color: #B5BAB7;
}

.surveyBox
{
    padding-left:30px;
    width: fit-content;
    border-radius: 0px 0px 5px 5px;
    padding: 10px 10px 10px 30px;
    border: 1px solid #555;
    background-color:#C4C7C6;
    margin-bottom:30px;
}

textarea{resize:none;}
</style>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function(){
        <?php if($data['template_type'] == 'Simple Form'){  ?>
            $('#mainForm').hide();
            $('#clickFull').click(function(){
                $('#mainForm').show();
            });
        <?php } ?>

        $('#submitSurvey').click(function(){
            var formData = $("#surveyForm :input[value!=''][value!=null]").serializeArray();
            console.log(formData)
            $.ajax({
                url: "components/modules/ajax/ajax.php",
                type: "POST",
                datatype:"json",
                data: {
                    'act': 'fillSurvey',
                    'data': formData
                },
                cache: false,
                success: function(result){
                    if(result == 1){
                        alert("Thank You, Survey Form Submitted, link is no longer accessible");
                        $('#surveyFormDiv').remove();
                        alert("This Survey Tab can be closed now");
                    }
                }});
        });
    });
</script>

<style>
    .header {
  padding: 10px 16px;
  background: #555;
  color: #f1f1f1;
}
</style>