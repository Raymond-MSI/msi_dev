<?php
    if($_GET['act']=='edit') {
        global $DB1;
        $condition = "question_id=" . $_GET['question_id'];
        $data = $DB1->get_data($tblname, $condition);
        $ddata = $data[0];
        $qdata = $data[1];
        $tdata = $data[2];
    }
    ?>
    <form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
        <div class="row">
            <div class="col-lg-6">
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Question</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="question" name="question" value="<?php if($_GET['act']=='edit') { echo $ddata['question']; } ?>">
                    </div>
                </div>
                <input type="hidden" id="question_id" name="question_id" value="<?php if($_GET['act']=='edit') { echo $ddata['question_id']; } ?>">
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Category</label>
                    <div class="col-sm-9">
                    <select id="category" name='category' class="form-control form-control-sm">
                        <option value=""></option>
                        <option value="rating">Rating</option>
                        <option value="textbox">Essay</option>
                        <option value="engineer">Engineer</option>
                    </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Weight</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm only-numeric" id="weight" name="weight" value="<?php if($_GET['act']=='edit') { echo $ddata['weight']; } ?>">
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
    
    <script>
        $(document).ready(function(){
            <?php if($_GET['act']=='edit'){ ?>
                $('#category option[value="<?php echo $ddata['category']; ?>"]').attr("selected","selected");
                <?php } ?>
            $('.btn-primary').click(function(){
                if($('#question').val() == ''){
                    alert('Question field is empty');
                    return false;
                /*}else if(choice_count<=1){
                    alert("Please add at least one answer choice")
                }else if(checkAnswer() == false){
                    alert('One or more answers are empty');
                    return false;
                }else if($('#correct_answer option:selected').val() == ''){
                    alert("Please choose the correct answer");
                    return false;
                */
                }else if($('#category option:selected').val() == ''){
                    alert('Category is empty');
                    return false;
                }else if($('#weight').val() == ''){
                    alert('Question\'s weight is empty');
                    return false;
                }else{
                    return true;
                }
            });

            /*function checkAnswer(){
                var counter1 = 0;
                for(var i=1;i<=choice_count;i++){
                    for(var j=i+1;j<=question_count;j++){
                        if(i!=j){
                            if($('#answer'+i+' option:selected').val() == ''){
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
            }*/

            /*var choice_count = 1;
            $('#add_choices').click(function(){
                var counter=0;
                if(choice_count>1){
                    for(var i=1;i<=choice_count; i++){
                        if($('#answer'+i).val() == ''){
                            alert('Fill in the Answer Choice '+i+' before adding');
                            counter++;
                        }
                    }
                }
                if(counter==0){    
                    $('#answer_choices').append("<span>Choice "+choice_count+"</span><input type='text' class='form-control form-control-sm' id='answer"+choice_count+"' name='answer["+choice_count+"]' style='width:100%' placeholder='Answer Choice "+ choice_count +"'>");
                    $('#correct_answer').append("<option value='"+choice_count+"'>"+choice_count);
                    choice_count++;
                }
            });

            $('#del_choices').click(function(){
                choice_count--;
                if(choice_count<=1){
                    choice_count=1;
                }
                $('#answer'+choice_count).remove();
            });*/

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