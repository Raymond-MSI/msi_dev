<?php
    if($_GET['act']=='edit') {
        global $DB;
        $condition = "survey_id=" . $_GET['survey_id'];
        $data = $DB->get_data($tblname, $condition);
        $ddata = $data[0];
        $qdata = $data[1];
        $tdata = $data[2];
    }
    $database1 = 'sa_survey';
    $DB1 = new Databases('localhost', 'root', '', $database1);
    $query2 = 'select pic_id, pic_name from sa_pic';
    $data = $DB1->get_sql($query2);
    $data1 = $data[0];
    $data2 = $data[1];
    $listcomp = array();
    do {
        array_push($listcomp, "<option value='".$data1['pic_id']."'>".$data1['pic_name']);
    } while ($data1 = $data2->fetch_assoc());

    $query4 = 'select template_id, template_name, template_type from sa_question_template';
    $data4 = $DB1->get_sql($query4);
    $data41 = $data4[0];
    $data42 = $data4[1];
    $listtemp = array();
$listtempsimple = array();
    do {
        if($data41['template_type'] == 'Main Form'){
            array_push($listtempsimple, "<option value='" . $data41['template_id'] . "'>" . $data41['template_name']);
        }
        array_push($listtemp, "<option value='".$data41['template_id']."' template='".$data41['template_type']."'>".$data41['template_name']);
        
    } while ($data41 = $data42->fetch_assoc());
    ?>
    <form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
        <div class="row">
            <div class="col-lg-6">
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">PIC</label>
                    <div class="col-sm-9">
                        <select name='pic_id' id='pic_id'   class="form-control form-control-sm">
                            <option value=""></option>
                            <?php 
                                foreach($listcomp as $options){
                                    echo $options;
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">PIC Email</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" readonly id="pic_email">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">PIC Address</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" readonly id="pic_address">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">PIC City</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" readonly id="pic_city">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">PIC Phone</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" readonly id="pic_phone">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Customer Name</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" readonly id="customer_name">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Template</label>
                    <div class="col-sm-9">
                        <select name='template_id' id='template_id' disabled class="form-control form-control-sm">
                            <option value=""></option>
                            <?php 
                                foreach($listtemp as $options){
                                    echo $options;
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">SO Number</label>
                    <div class="col-sm-9">
                        <select name='so_number' id='so_number' disabled class="form-control form-control-sm">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Name</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" readonly id="project_name" name='project_name'>
                    </div>
                </div>
            </div>
            <div class='col-lg-6'>
                <div class="row mb-3" id="mainTemp">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Main Template</label>
                    <div class="col-sm-9">
                    <select id="main_template_id" name="main_template_id" style='width:200px;' class="form-control form-control-sm">
                        <option value="">
                    <?php 
                        foreach($listtempsimple as $options){
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
            $('#mainTemp').hide();
            $('.btn-primary').click(function(){
                var template = $('#template_id option:selected').attr('template')
                if($('#pic_id').val() == ''){
                    alert('PIC is empty');
                    return false;
                }else if($('#customer_id option:selected').val() == ''){
                    alert('Customer is empty');
                    return false;
                }else if($('#so_number option:selected').val() == ''){
                    alert('Project Code is empty');
                    return false;
                }else if(template == 'Simple Form' && $('#main_template_id option:selected').val() == ''){
                    alert('Main Template is empty');
                    return false;
                }else{
                    return true;
                }
            });

            $('#customer_id').select2();
            $('#template_id').select2();
            $("#pic_id").select2();
            $('#main_template_id').select2();    
            $('#so_number').select2();
        });

        $('#so_number').change(function(){
            var so_number = $('#so_number option:selected').val();
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
                    $('#project_name').val($.trim(result2));
                }});
        });

        $('#template_id').change(function(){
            var template = $('#template_id option:selected').attr('template')
            if(template == 'Simple Form'){
                $('#mainTemp').show();
                $('#main_template_id option:selected').attr("selected",null);
            }else{
                $('#mainTemp').hide();
            }
        });

        $('#pic_id').change(function(){
            var pic = $('#pic_id option:selected').val();
            $('#customer_id').empty();
            $('#so_number').empty();
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
                        $('#customer_name').val(data['customer_company_name']);
                        if(data['customer_code'] != ''){
                            $.ajax({
                                url: "components/modules/ajax/ajax.php",
                                type: "POST",
                                datatype:"json",
                                data: {
                                    'act': 'getListSO',
                                    'cust_id': data['customer_code']
                                },
                                cache: false,
                                success: function(result2){
                                    var data2 = JSON.parse(result2);
                                    $('#so_number').append('<option value="">')
                                    $('#so_number').append(data2);
                                    $('#so_number').removeAttr("disabled");
                                }});
                            }else{
                                $('#so_number').prop('disabled', true);
                            }
                        if(data['pic_id'] != ''){
                            $('#template_id').removeAttr("disabled");
                        }else{
                            $('#template_id').prop('disabled', true);
                            $('#so_number').prop('disabled', true);
                        }
                    }
                });
            }else{
                $('#pic_address').val('');
                $('#pic_email').val('');
                $('#pic_city').val('');
                $('#customer_name').val('');
                $('#pic_id').val('');
                $('#pic_phone').val('');
                $('#template_id').prop('disabled', true);
                $('#so_number').prop('disabled', true);
            }
        });
        </script>