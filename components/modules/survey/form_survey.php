<?php
global $DB1;
if ($_GET['act'] == 'view') {
    global $DB1;
    $condition = "survey_id=" . $_GET['id'];
    $data = $DB1->get_data($tblname, $condition);
    $ddata = $data[0];
    $qdata = $data[1];
    $tdata = $data[2];
    $mdlname = "SERVICE_BUDGET";
    $DBSB = get_conn($mdlname);
    $ambilcustname = $DBSB->get_sql("SELECT * FROM sa_trx_project_list where so_number='" . $ddata['so_number'] . "' AND status='acknowledge'");
    $ambilpicname = $DB1->get_sql("SELECT * FROM sa_pic a join sa_survey b on a.pic_id=b.pic_id where b.survey_id=" . $_GET['id']);
    //echo $ambilpicname[0]['pic_name'];
}

/*$mdlname = "WRIKE_INTEGRATE";
$DB2 = get_conn($mdlname);

$query2 = "select distinct(no_so), list.project_code, customer_name, list.project_type, project_category from sa_wrike_project_list list join sa_wrike_project_detail det on list.project_code = det.project_code where no_so != '' AND no_so is not null AND project_status IN ('Closed', 'Closed with Open Item')";
$data = $DB2->get_sql($query2);
$data1 = $data[0];
$data2 = $data[1];
$listSO = array();
do {
    array_push($listSO, "<option value='" . $data1['no_so'] . "' projtype='" . $data1['project_type'] . "' customer='" . $data1['customer_name'] . "' kp='" . $data1['project_code'] . "'  category='".$data1['project_category']."'>" . $data1['project_code'] . " ( " . $data1['no_so'] . " )");
} while ($data1 = $data2->fetch_assoc());

$mdlname = "KPI_BOARD";
$DBKPI = get_conn($mdlname);
$queryproject = "select so.project_code_kpi, so.project_name, so.so_number_kpi, so.customer_name, if(so.SB_service_type_implementation != '0',so.SB_project_category, null) as implementation, so.SB_service_type_maintenance as maintenance from sa_kpi_board kpi join sa_data_so so on kpi.so_number = so.so_number_kpi where kpi.verif_status = 'Completed;'";
$data_kpi = $DBKPI->get_sql($queryproject);
$currproject = $data_kpi[0];
$nextproject = $data_kpi[1];
$listSO = array();
do{
    $service_type = '';
    $category = '';
    if($currproject['implementation'] != null){
        $service_type = 'MSI Project Implementation';
        switch($currproject['implementation']){
            case 1: $category = 'High';break;
            case 2: $category = 'Medium';break;
            case 3: $category = 'Standard';break;
        }
    }else if($currproject['maintenance'] != null){
        $service_type = 'MSI Project Maintenance';
        $category = '';
    }
    
    if($currproject['implementation'] != null && $currproject['maintenance'] != null){
        $service_type = 'both';
    }
    array_push($listSO, "<option value='" . $currproject['so_number_kpi'] . "' projtype='" . $service_type . "' customer='" . $currproject['customer_name'] . "' kp='" . $currproject['project_code_kpi'] . "'>" . $currproject['project_code_kpi'] . " ( " . $currproject['so_number_kpi'] . " )");
}while($currproject = $nextproject->fetch_assoc());
*/
$mdlname = "KPI_BOARD";
$DBKPI = get_conn($mdlname);
// $queryproject = "select so_number, project_code_kpi from sa_kpi_board kpi left join sa_data_so so on kpi.so_number = so.so_number_kpi where kpi.verif_status = 'Completed;'";
$queryproject = "select so_number, project_code_kpi, kpi.order_number from sa_kpi_board kpi left join sa_data_so so on kpi.so_number = so.so_number_kpi where kpi.verif_status = 'Completed;'";
$data_kpi = $DBKPI->get_sql($queryproject);
$currproject = $data_kpi[0];
$nextproject = $data_kpi[1];
$listKPI = "<option value = ''></option>";
if ($data_kpi[2] > 0) {
    do {
        //array_push($listKPI, "<option value='" . $currproject['so_number'] . "'>" . $currproject['project_code_kpi'] . " ( " . $currproject['so_number'] . " )")
        // $listKPI .= "<option value='" . $currproject['so_number'] . "' kp='" . $currproject['project_code_kpi'] . "'>" . $currproject['project_code_kpi'] . " ( " . $currproject['so_number'] . " )";
        $listKPI .= "<option value='" . $currproject['so_number'] . "' kp='" . $currproject['project_code_kpi'] . "'>" . $currproject['project_code_kpi'] . " ( " . $currproject['so_number'] . " )" . " ( " . $currproject['order_number'] . " )";
    } while ($currproject = $nextproject->fetch_assoc());
}
$mdlname2 = "SERVICE_BUDGET";
$DBSB = get_conn($mdlname2);
$queryproject = "select distinct(so_number), project_code, order_number from sa_trx_project_list where status in ('acknowledge','approved') and bundling not like '0;0;0;'";
$data_sb = $DBSB->get_sql($queryproject);
$currproject = $data_sb[0];
$nextproject = $data_sb[1];
$listSB = "<option value=''></option>";
do {
    //array_push($listSB, "<option value='" . $currproject['so_number'] . "'>" . $currproject['project_code'] . " ( " . $currproject['so_number'] . " )");
    // $listSB .= "<option value='" . $currproject['so_number'] . "'  kp='" . $currproject['project_code'] . "'>" . $currproject['project_code'] . " ( " . $currproject['so_number'] . " )";
    $listSB .= "<option value='" . $currproject['so_number'] . "'  kp='" . $currproject['project_code'] . "'>" . $currproject['project_code'] . " ( " . $currproject['so_number'] . " )" . " ( " . $currproject['order_number'] . " )";
} while ($currproject = $nextproject->fetch_assoc());

$query4 = 'select template_id, template_name, template_type from sa_question_template';
$data4 = $DB1->get_sql($query4);
$data41 = $data4[0];
$data42 = $data4[1];
$listtempmain = array();
$listtempsimple = array();
$listtemp = '';
do {
    if ($data41['template_type'] == 'Main Form') {
        array_push($listtempmain, "<option value='" . $data41['template_id'] . "'>" . $data41['template_name']);
        $listtemp .= "<option value='" . $data41['template_id'] . "'>" . $data41['template_name'];
    } else {
        //$listtempsimple .= "<option value='" . $data41['template_id'] . "' template='" . $data41['template_type'] . "'>" . $data41['template_name'];
        array_push($listtempsimple, "<option value='" . $data41['template_id'] . "'>" . $data41['template_name']);
    }
} while ($data41 = $data42->fetch_assoc());

?>
<?php if ($_GET['act'] == 'add') { ?>
    <div class="alert" role="alert" id='countReminder' style="background-color: #d1e7dd;"> This User has submitted a survey before.</div>
    <div class="alert" role="alert" id='serviceBudgets' style="background-color: #d1e7dd;"> If Project Number is not found, please input manually on service budgets
    </div>
<?php } ?>
<form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
    <div class="row">
        <div class="col-lg-6">
            <?php if ($_GET['act'] == 'view') { ?>
            <?php } else { ?>
                <div class="row mb-3" hidden>
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Survey ID</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" id="survey_id" name="survey_id" value="">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Source From</label>
                    <div class="col-sm-9">
                        <select class="form-control form-control-sm" name='project_source' id='project_source'>
                            <option value="kpi">KPI Board</option>
                            <option value="sbf">Service Budgets</option>
                            <option value="coba">coba</option>
                        </select>
                    </div>
                </div>
            <?php } ?>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">SO Number</label>
                <div class="col-sm-9">
                    <select name='so_number' id='so_number' class="form-control form-control-sm">
                        <?php if ($_GET['act'] == 'view') { ?>
                            <option value="<?php echo $ddata['so_number'];  ?>" readonly><?php echo $ddata['so_number']; ?></option>
                        <?php } ?>
                    </select>
                    <input type="hidden" name='project_code' id='project_code'>
                    <input type="hidden" name='type_of_service' id='type_of_service'>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Type</label>
                <div class="col-sm-9">
                    <select class="form-control form-control-sm" name='project_type' id='project_type'>
                        <?php if ($_GET['act'] == 'view') { ?>
                            <option value="<?php echo $ddata['project_type'];  ?>"><?php echo $ddata['project_type'];  ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Customer Name</label>
                <div class="col-sm-9">
                    <?php if ($_GET['act'] == "view") { ?>
                        <?php $narik_customer_name = $DBSB->get_sql("SELECT * FROM sa_trx_project_list where so_number='" . $ddata['so_number'] . "' AND status='acknowledge'"); ?>
                        <input type="text" class="form-control form-control-sm" readonly id="cust_name" value="<?php echo $narik_customer_name[0]['customer_name']; ?>">
                    <?php } else { ?>
                        <input type="text" class="form-control form-control-sm" readonly id="cust_name">
                    <?php } ?>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Name</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" readonly id="project_name" name="project_name" value="<?php if ($_GET['act'] == 'view') {
                                                                                                                                        echo $ddata['project_name'];
                                                                                                                                    } ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">PIC Name</label>
                <div class="col-sm-9">
                    <select class='form-control form-control-sm' id='pic_id' name='pic_id' disabled>
                        <?php if ($_GET['act'] == "view") { ?>
                            <option value="<?php echo $ambilpicname[0]['pic_name']; ?>"><?php echo $ambilpicname[0]['pic_name'];  ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">PIC Email</label>
                <div class="col-sm-9">
                    <?php if ($_GET['act'] == "view") { ?>
                        <?php $narik_pic_email = $DB1->get_sql("SELECT * FROM sa_pic a join sa_survey b on a.pic_id=b.pic_id where b.survey_id=" . $_GET['id']); ?>
                        <input type="text" class="form-control form-control-sm" name='pic_email' readonly id="pic_email" value="<?php echo $narik_pic_email[0]['pic_email']; ?>">
                    <?php } else { ?>
                        <input type="text" class="form-control form-control-sm" name='pic_email' readonly id="pic_email">
                    <?php } ?>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">PIC Phone</label>
                <div class="col-sm-9">
                    <?php if ($_GET['act'] == "view") { ?>
                        <?php $malikphone = $DB1->get_sql("SELECT * FROM sa_pic a join sa_survey b on a.pic_id=b.pic_id where b.survey_id=" . $_GET['id']); ?>
                        <input type="text" class="form-control form-control-sm" name='pic_phone' readonly id="pic_phone" value="<?php echo $malikphone[0]['pic_phone']; ?>">
                    <?php } else { ?>
                        <input type="text" class="form-control form-control-sm" readonly id="pic_phone">
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class='col-lg-6'>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Template</label>
                <div class="col-sm-9">
                    <select name='template_type' id='template_type' disabled class="form-control form-control-sm">
                        <?php if ($_GET['act'] == "view") { ?>
                            <option value="<?php echo $ddata['template_type'];  ?>"><?php echo $ddata['template_type']; ?>
                            <?php } else { ?>
                            <option value=""></option>
                            <option value="full">Full Template</option>
                            <option value="simple">Simple Template</option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="row mb-3" id="simpleTemp">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Simple Template</label>
                <div class="col-sm-9">
                    <select name='simple_template_id' id='simple_template_id' class="form-control form-control-sm" style="width:100%">
                        <option value=""></option>
                        <?php foreach ($listtempsimple as $options) {
                            echo $options;
                        } ?>
                    </select>
                </div>
            </div>
            <div class="row mb-3" id="mainTemp">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Full Template</label>
                <div class="col-sm-9">
                    <select id="main_template_id" name="main_template_id" class="form-control form-control-sm" style="width:100%">
                        <option value="">
                            <?php
                            foreach ($listtempmain as $options) {
                                echo $options;
                            }
                            ?>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Additional Information (Optional)</label>
                <div class="col-sm-9">
                    <?php if ($_GET['act'] == "view") { ?>
                        <textarea id='extrainf' name="extrainf" class="form-control form-control-sm" rows=8 style="resize: none;" readonly><?php if ($_GET['act'] == 'view') echo $ddata['extra_information'] ?></textarea>
                    <?php } else { ?>
                        <textarea id='extrainf' name="extrainf" class="form-control form-control-sm" rows=8 style="resize: none;"></textarea>
                    <?php } ?>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Souvenir</label>
                <div class="col-sm-9">
                    <select id='souvenir' name='souvenir' class="form-contorl form-control-sm">
                        <?php if ($_GET['act'] == "view") { ?>
                            <option value="<?php echo $ddata['souvenir'];  ?>"><?php echo $ddata['souvenir'];  ?>
                            <?php } else { ?>
                            <option value="Yes">Yes
                            <option value="No" selected>No
                            <?php } ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <input type="submit" class="btn btn-secondary" name="cancel" value="Cancel">
    <?php if (isset($_GET['act']) && $_GET['act'] == 'edit') { ?>
        <input type="submit" class="btn btn-primary" name="save" value="Save">
    <?php } elseif (isset($_GET['act']) && $_GET['act'] == 'add') { ?>
        <input type="submit" class="btn btn-primary" name="add" value="Save">
    <?php } ?>
</form>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#project_source').trigger('change');
        $('#simpleTemp').hide();
        $('#mainTemp').hide();
        $('#countReminder').hide();

        $('#project_source').change(function() {

        });

        $('.btn-primary').click(function() {
            var survcount = $('#pic_id option:selected').attr('survcount')
            var type = $('#template_type option:selected').val();
            if ($('#pic_id').val() == '') {
                alert('PIC is empty');
                return false;
            } else if ($('#customer_id option:selected').val() == '') {
                alert('Customer is empty');
                return false;
            } else if ($('#so_number option:selected').val() == '') {
                alert('Project Code is empty');
                return false;
            } else if (type == '') {
                alert("Template Type not selected");
                return false;
            } else if (type == 'simple' && $('#simple_template_id option:selected').val() == '') {
                alert('Simple Template is empty');
                return false;
            } else if ($('#main_template_id option:selected').val() == '') {
                alert("Survey Template is empty");
                return false;
            } else {
                return true;
            }
        });

        $('#customer_id').select2();
        $('#simple_template_id').select2();
        $('#pic_id').select2();
        $('#main_template_id').select2();
        $('#so_number').select2();
    });

    $('#template_type').change(function() {
        var type = $('#template_type option:selected').val();
        if (type == 'full') {
            $('#simpleTemp').hide();
            $('#mainTemp').show();
            $('#main_template_id option:selected').attr("selected", null);
            $('#simple_template_id option:selected').attr("selected", null);
        } else if (type == 'simple') {
            $('#simpleTemp').show();
            $('#mainTemp').show();
            $('#main_template_id option:selected').attr("selected", null);
            $('#simple_template_id option:selected').attr("selected", null);
        }
    });

    $('#project_source').change(function() {
        $('#so_number option').remove();
        var source = $('#project_source option:selected').val();
        if (source == 'kpi') {
            $('#so_number').append("<?php echo $listKPI; ?>");
        } else if (source == 'sbf') {
            $('#so_number').append("<?php echo $listSB; ?>");
        }
    })

    $('#so_number').change(function() {
        $('#project_type option').remove();
        $('#pic_id option').remove();
        var source = $('#project_source option:selected').val();
        var kp = $('#so_number option:selected').attr('kp');
        var so = $('#so_number option:selected').val();
        $('#project_code').val(kp);
        $.ajax({
            url: "http://localhost/microservices/components/modules/ajax/ajax.php",
            type: "POST",
            datatype: "json",
            data: {
                'act': 'getProjectDetail',
                'so_number': so,
                'source': source
            },
            cache: false,
            success: function(result2) {
                if ($.trim(result2) == 'both') {
                    $('#project_type').append('<option value=""></option><option value="MSI Project Implementation" >MSI Project Implementation</option><option value="MSI Project Maintenance">MSI Project Maintenance</option>');
                } else {
                    if ($.trim(result2) == 'imp')
                        $('#project_type').append('<option value=""></option><option value="MSI Project Implementation" >MSI Project Implementation</option>');
                    if ($.trim(result2) == 'mtc')
                        $('#project_type').append('<option value=""></option><option value="MSI Project Maintenance" >MSI Project Maintenance</option>');
                    else {
                        $('#project_type').append('<option value="">SO Number Not Found</option>');
                    }
                }
            }
        });
    })

    $('#project_type').change(function() {
        $('#type_of_service').val($('option:selected', this).attr('category'));
        var so_number = $('#so_number option:selected').val();
        var category = $('#so_number option:selected').attr('category');
        var source = $('#project_source option:selected').val();
        var type = $('#project_type option:selected').val();
        $.ajax({
            url: "components/modules/ajax/ajax.php",
            type: "POST",
            datatype: "json",
            data: {
                'act': 'getProjectName',
                'so_number': so_number,
                'source': source,
                'type': type
            },
            cache: false,
            success: function(result2) {
                var result = result2.split('|');
                $('#project_name').val($.trim(result[1]));
                $('#cust_name').val($.trim(result[3]))
                $('#pic_id option').remove();
                $('#pic_id').prop("disabled", true);
                //if(category == '')
                $('#type_of_service').val(result[2]);
                $.ajax({
                    url: "components/modules/ajax/ajax.php",
                    type: "POST",
                    datatype: "json",
                    data: {
                        'act': 'getPICList',
                        'cust_code': ($.trim(result[0]))
                    },
                    cache: false,
                    success: function(result3) {
                        var data3 = JSON.parse(result3);
                        $('#pic_id').append('<option value="">')
                        $('#pic_id').append(data3);
                        $('#pic_id').removeAttr("disabled");
                    }
                });
            }
        });
    });

    $('#pic_id').change(function() {
        var pic = $('#pic_id option:selected').val();
        var survey_count = $('#pic_id option:selected').attr('survcount');
        var category = $('#pic_id option:selected').attr('category');
        if (category == 'Vendor' || survey_count == 0 || pic == '') {
            $('#countReminder').hide();
        } else if (survey_count > 0) {
            $('#countReminder').show();
        }
        if (pic != '') {
            $.ajax({
                url: "components/modules/ajax/ajax.php",
                type: "POST",
                datatype: "json",
                data: {
                    'act': 'getPIC',
                    'pic_id': pic
                },
                cache: false,
                success: function(result) {
                    var data = JSON.parse(result);
                    $('#pic_phone').val(data['pic_phone']);
                    $('#pic_address').val(data['pic_address']);
                    $('#pic_email').val(data['pic_email']);
                    $('#pic_city').val(data['pic_city']);
                    if (data['pic_id'] != '') {
                        $('#template_type').removeAttr("disabled");
                    } else {
                        $('#template_type').removeAttr("disabled");
                    }
                }
            });
        } else {
            $('#pic_address').val('');
            $('#pic_email').val('');
            $('#pic_city').val('');
            $('#pic_id').val('');
            $('#pic_phone').val('');
            $('#template_type').prop('disabled', true);
        }
    });
</script>