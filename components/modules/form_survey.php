<?php
global $DB1;
$database2 = 'sa_wrike_integrate';
$DB2 = new Databases('10.20.50.161', 'ITAdmin', 'P@ssw0rd.1', $database2);
$query2 = "select distinct(no_so), list.project_code, customer_name from sa_wrike_project_list list join sa_wrike_project_detail det on list.project_code = det.project_code where no_so != '' AND no_so is not null AND project_status IN ('Closed', 'Closed with Open Item')";
$data = $DB2->get_sql($query2);
$data1 = $data[0];
$data2 = $data[1];
$listSO = array();
do {
    array_push($listSO, "<option value='".$data1['no_so']."' customer='".$data1['customer_name']."' kp='".$data1['project_code']."'>".$data1['project_code']." ( ".$data1['no_so']." )");
} while ($data1 = $data2->fetch_assoc());

$query4 = 'select template_id, template_name, template_type from sa_question_template where valid_year = year(curdate())';
$data4 = $DB1->get_sql($query4);
$data41 = $data4[0];
$data42 = $data4[1];
$listtempmain = array();
$listtempsimple = array();
$listtemp = '';
    do {
        if($data41['template_type'] == 'Main Form'){
            array_push($listtempmain,"<option value='" . $data41['template_id'] . "'>" . $data41['template_name']);
            $listtemp .= "<option value='" . $data41['template_id'] . "'>" . $data41['template_name'];
        } else {
            //$listtempsimple .= "<option value='" . $data41['template_id'] . "' template='" . $data41['template_type'] . "'>" . $data41['template_name'];
            array_push($listtempsimple,"<option value='" . $data41['template_id'] . "'>" . $data41['template_name']);
        }
        
    } while ($data41 = $data42->fetch_assoc());
    ?>
    <div class="alert" role="alert" id='countReminder' style="background-color: #d1e7dd;"> This PIC has submitted a survey before.
			</div>
    <form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
        <div class="row">
            <div class="col-lg-6">
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">SO Number</label>
                    <div class="col-sm-9">
                        <select name='so_number' id='so_number' class="form-control form-control-sm">
                            <option value=""></option>
                            <?php 
                                foreach($listSO as $options){
                                    echo $options;
                                }
                            ?>
                        </select>
                        <input type="hidden" name='project_code' id='project_code'>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Name</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" readonly id="project_name" name="project_name">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Customer Name</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" readonly id="cust_name">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">PIC Name</label>
                    <div class="col-sm-9">
                    <select class='form-control form-control-sm' id='pic_id' name='pic_id' disabled>
                    </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">PIC Email</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name='pic_email' readonly id="pic_email">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">PIC Phone</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" readonly id="pic_phone">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Template</label>
                    <div class="col-sm-9">
                        <select name='template_type' id='template_type' disabled class="form-control form-control-sm">
                            <option value=""></option>
                            <option value="full">Full Template</option>
                            <option value="simple">Simple Template</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class='col-lg-6'>
                <div class="row mb-3" id="simpleTemp">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Simple Template</label>
                    <div class="col-sm-9">
                        <select name='simple_template_id' id='simple_template_id' class="form-control form-control-sm">
                            <option value=""></option>
                        <?php foreach($listtempsimple as $options){
                            echo $options;
                        } ?>
                        </select>
                    </div>
                </div>
                <div class="row mb-3" id="mainTemp">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Full Template</label>
                    <div class="col-sm-9">
                    <select id="main_template_id" name="main_template_id" style='width:200px;' class="form-control form-control-sm">
                        <option value="">
                    <?php 
                        foreach($listtempmain as $options){
                            echo $options;
                        }
                    ?>
                    </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Additional Information (Optional)</label>
                    <div class="col-sm-9">
                    <textarea id='extrainf' name="extrainf" class="form-control form-control-sm" rows=8 style="resize: none;"></textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Souvenir</label>
                    <div class="col-sm-9">
                    <select id='souvenir' name='souvenir' class="form-contorl form-control-sm">
                        <option value="Yes">Yes
                        <option value="No" selected>No
                    </select>
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
            $('#simpleTemp').hide();
            $('#mainTemp').hide();
            $('#countReminder').hide();
            $('.btn-primary').click(function(){
                var survcount = $('#pic_id option:selected').attr('survcount')
                var type = $('#template_type option:selected').val();
                if($('#pic_id').val() == ''){
                    alert('PIC is empty');
                    return false;
                }else if($('#customer_id option:selected').val() == ''){
                    alert('Customer is empty');
                    return false;
                }else if($('#so_number option:selected').val() == ''){
                    alert('Project Code is empty');
                    return false;
                }else if(type == ''){
                    alert("Template Type not selected");
                    return false;
                }else if(type == 'simple' && $('#simple_template_id option:selected').val() == ''){
                    alert('Simple Template is empty');
                    return false;
                }else if($('#main_template_id option:selected').val() == ''){
                    alert("Survey Template is empty");
                    return false;
                }else{
                    return true;
                }
            });

            $('#customer_id').select2();
            $('#simple_template_id').select2();
            $('#pic_id').select2();
            $('#main_template_id').select2();    
            $('#so_number').select2();
        });

        $('#template_type').change(function(){
            var type = $('#template_type option:selected').val();
            if(type == 'full'){
                $('#simpleTemp').hide();
                $('#mainTemp').show();
                $('#main_template_id option:selected').attr("selected",null);
                $('#simple_template_id option:selected').attr("selected",null);
            }
            else if(type == 'simple'){
                $('#simpleTemp').show();
                $('#mainTemp').show();
                $('#main_template_id option:selected').attr("selected",null);
                $('#simple_template_id option:selected').attr("selected",null);
            }
        });

        $('#so_number').change(function(){
            var so_number = $('#so_number option:selected').val();
            var cust_name = $('#so_number option:selected').attr('customer');
            var kp = $('#so_number option:selected').attr('kp');
            $('#project_code').val(kp);
            $('#cust_name').val(cust_name); 
            $.ajax({
                url: "components/modules/ajax/ajax.php",
                type: "POST",
                datatype:"json",
                data: {
                    'act': 'getProjectName',
                    'so_number': so_number
                },
                cache: false,
                success: function(result2){
                    var result = result2.split('|');
                    $('#project_name').val($.trim(result[1]));
                    $('#cust_name')
                    $.ajax({
                        url: "components/modules/ajax/ajax.php",
                        type: "POST",
                        datatype:"json",
                        data: {
                            'act': 'getPICList',
                            'cust_code': ($.trim(result[0]))
                        },
                        cache: false,
                        success: function(result3){
                            var data3 = JSON.parse(result3);
                            $('#pic_id').append('<option value="">')
                            $('#pic_id').append(data3);
                            $('#pic_id').removeAttr("disabled");
                        }});
                }});
        });

        $('#pic_id').change(function(){
            var pic = $('#pic_id option:selected').val();
            var survey_count = $('#pic_id option:selected').attr('survcount');
            var category = $('#pic_id option:selected').attr('category');
            if(category == 'Vendor' || survey_count == 0 || pic == ''){
                $('#countReminder').hide();
            }
            else if(survey_count > 0){
                $('#countReminder').show();
            }
            if(pic != ''){
                $.ajax({
                    url: "components/modules/ajax/ajax.php",
                    type: "POST",
                    datatype:"json",
                    data: {
                        'act': 'getPIC',
                        'pic_id': pic	
                    },
                    cache: false,
                    success: function(result){
                        var data = JSON.parse(result);
                        $('#pic_phone').val(data['pic_phone']);
                        $('#pic_address').val(data['pic_address']);
                        $('#pic_email').val(data['pic_email']);
                        $('#pic_city').val(data['pic_city']);
                        if(data['pic_id'] != ''){
                            $('#template_type').removeAttr("disabled");
                        }else{
                            $('#template_type').removeAttr("disabled");
                        }
                    }
                });
            }else{
                $('#pic_address').val('');
                $('#pic_email').val('');
                $('#pic_city').val('');
                $('#pic_id').val('');
                $('#pic_phone').val('');
                $('#template_type').prop('disabled', true);
            }
        });
        </script>