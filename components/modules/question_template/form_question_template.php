<?php
global $DB1;
    if($_GET['act']=='edit') {
        $condition = "template_id=" . $_GET['template_id'];
        $data = $DB1->get_data($tblname, $condition);
        $ddata = $data[0];
        $qdata = $data[1];
        $tdata = $data[2];
    }
        $query2 = 'select question_id, question, category, weight from sa_question_library order by category';
        $data = $DB1->get_sql($query2);
        $data1 = $data[0];
        $data2 = $data[1];
        $rating = '';
        $textbox = '';
        $engineer = '';
        do{
            switch($data1['category']){
                case 'rating':
                    $rating .= "<option type='".$data1['category']."' value='" . $data1['question_id'] . "'>" . $data1['question'] . " (" . $data1['weight'] . ")";
                    break;
                case 'textbox' :
                    $textbox .= "<option type='".$data1['category']."' value='" . $data1['question_id'] . "'>" . $data1['question'] . " (" . $data1['weight'] . ")";
                    break;
                case 'engineer':
                    $engineer .= "<option type='".$data1['category']."' value='" . $data1['question_id'] . "'>" . $data1['question'] . " (" . $data1['weight'] . ")";
                    break;
            }
        }while ($data1 = $data2->fetch_assoc());
        $listquestion = '';
        if($rating != ''){
            $listquestion .= "<optgroup label='Rating'>";
            $listquestion .= $rating;
            $listquestion .= '</optgroup>';
        }
        if($textbox != ''){
            $listquestion .= "<optgroup label='Essay'>";
            $listquestion .= $textbox;
            $listquestion .= '</optgroup>';
        }
        if($engineer != ''){
            $listquestion .= "<optgroup label='Engineer'>";
            $listquestion .= $engineer;
            $listquestion .= '</optgroup>';
        }
    ?>
    <form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
        <div class="row">
            <div class="col-lg-6">
                <input type="hidden" id='template_id' name='template_id' value="<?php if($_GET['act']=='edit') { echo $ddata['template_id']; } ?>">
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Template Name</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="template_name" name="template_name" value="<?php if($_GET['act']=='edit') { echo $ddata['template_name']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Template Type</label>
                    <div class="col-sm-9">
                    <select id='template_type' name="template_type" class="form-control form-control-sm">
                        <option value="">
                        <option value="Main Form" <?php if ($_GET['act'] == 'edit') {
                            echo ($ddata['template_type'] == 'Main Form') ? 'selected' : '';
                        }?>>Main Template
                        <option value="Simple Form" <?php if ($_GET['act'] == 'edit') {
                            echo ($ddata['template_type'] == 'Simple Form') ? 'selected' : '';
                        }?>>Simplified Template
                    </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Questions</label>
                    <div class="col-sm-9" id="question_list">
                    </div>
                    <div class="col-sm-9">
                        <input type="button" id="add_question" value="Add">
                        <input type="button" id="del_question" value="Delete">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Valid Year</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm only-numeric" id="valid_year" name="valid_year" value="<?php if($_GET['act']=='edit') { echo $ddata['valid_year']; } ?>">
                    </div>
                </div>
            </div>
        </div>
        <input type="submit" class="btn btn-secondary" name="cancel" value="Cancel">
        <?php if(isset($_GET['act']) && $_GET['act']=='edit') { ?>
            <input type="submit" class="btn btn-primary" name="save" value="Save">
        <?php } elseif(isset($_GET['act']) && $_GET['act']=='add') { ?>
            <input type="submit" class="btn btn-primary" name="add" value="Save">
        <?php } ?>
    </form>
    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.btn-primary').click(function(){
                if($('#template_name').val() == ''){
                    alert('Template Name is empty');
                    return false;
                }else if($('#template_type option:selected').val() == ''){
                    alert("Template Type is empty");
                    return false;
                }else if(question_count<=1){
                    alert("Please add a question");
                    return false;
                }else if(checkEmpty() == false){
                    alert('One or more questions are empty');
                    return false;
                }else if(checkQuestion() == false){
                    alert("Multiple Same Questions added");
                    return false;
                }else if(checkEngineer() == false){
                    alert('Max 1 Engineer Type Question allowed');
                    return false;
                }else if($('#valid_year').val() == ''){
                    alert('template\'s valid year is empty');
                    return false;
                }else{
                    return true;
                }
            });

            var question_count = 1;
            <?php if($_GET['act'] == 'edit'){
                $question = json_decode($ddata['questions']);
                foreach ($question as $list) { ?>
                    $('#question_list').append("<select id='question"+question_count+"' class='form-control form-control-sm' name='question["+question_count+"]'><option value=''></option></select>");
                    $('#question'+question_count).append("<?php echo $listquestion; ?>");
                    $('#question'+question_count).select2();
                    $('#question'+question_count+' option[value="<?php echo $list; ?>"]').attr("selected", "selected");
                    $('#question'+question_count).select2('val','<?php echo $list; ?>');
                    question_count++;
            <?php }} ?>

            $('#template_type').change(function(){
                var type = $('#template_type option:selected').val();
                if(type!=''){
                    $('#add_question').prop('disabled', false);
                    $('#del_question').prop('disabled', false);
                }else{
                    $('#add_question').prop('disabled', true);
                    $('#del_question').prop('disabled', true);
                }
            });

            $('#add_question').prop('disabled', true);
            $('#del_question').prop('disabled', true);
            $('#add_question').click(function(){
                if(question_count>1){
                    if(checkQuestion() == false){
                        alert('Multiple Same Question Added');
                        return;
                    }
                }
                if($('#template_type option:selected').val() == 'Simple Form' && question_count==4){
                    alert("Cannot add anymore question for Simplified Form");
                    return;
                }
                if(checkEmpty()){    
                    $('#question_list').append("<select id='question"+question_count+"' class='form-control form-control-sm' name='question["+question_count+"]'><option value=''></option></select>");
                    $('#question'+question_count).append("<?php echo $listquestion; ?>");
                    $('#question'+question_count).select2();
                    question_count++;
                    if(question_count > 4)
                    $('#template_type option[value="Simple Form"]').prop('disabled', true);
                }else{
                    alert("Please fill in the questions");
                }
            });

            function checkEmpty(){
                var counter=0;
                if(question_count>1){
                    for(var i=1;i<=question_count; i++){
                        if($('#question'+i).val() == ''){
                            counter++;
                        }
                    }
                }
                if(counter>0){
                    return false;
                }else{
                    return true;
                }
            }

            function checkQuestion(){
                var counter1 = 0;
                for(var i=1;i<=question_count;i++){
                    for(var j=i+1;j<=question_count;j++){
                        if(i!=j){
                            if($('#question'+i+' option:selected').val() == $('#question'+j+' option:selected').val()){
                                counter1++;
                            }
                        }
                    }
                }
                if(counter1>=1){
                    return false;
                }else{
                    return true;
                }
            }

            function checkEngineer(){
                var counter1 = 0;
                for(var i=1;i<=question_count;i++){
                    if($('#question'+i+' option:selected').attr('type') == 'engineer'){
                        counter1++;
                    }
                }
                if(counter1>=2){
                    return false;
                }else{
                    return true;
                }
            }

            $('name[question] option:selected').change(function(){
                console.log('1');
            });

            $('#del_question').click(function(){
                question_count--;
                if(question_count<=1){
                    question_count=1;
                }
                $('#question'+question_count).select2('destroy');
                $('#question'+question_count).remove();
                if(question_count <= 3)
                    $('#template_type option[value="Simple Form"]').prop('disabled', false);
            });

            $(".only-numeric").bind("keypress", function (e) {
                var keyCode = e.which ? e.which : e.keyCode
                    
                if (!(keyCode >= 48 && keyCode <= 57)) {
                    $(".error").css("display", "inline");
                    return false;
                }else{
                    $(".error").css("display", "none");
                }
            });
        });
    </script>
    <style>
        .select2{
            margin-bottom: 10px;
        }
    </style>