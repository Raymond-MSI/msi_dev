<?php
global $DB1;
$ddata['customer_code'] = '';
if ($_GET['act'] == 'edit') {
    $condition = "pic_id=" . $_GET['pic_id'];
    $data = $DB1->get_data($tblname, $condition);
    $ddata = $data[0];
    $qdata = $data[1];
    $tdata = $data[2];
}

$mdlname = "SERVICE_BUDGET";
$DB2 = get_conn($mdlname);
$query2 = 'SELECT distinct(customer_code), customer_name FROM `sa_trx_project_list`';
$data = $DB2->get_sql($query2);
$data1 = $data[0];
$data2 = $data[1];
$cust_code = '';
$so_number = '';
$project_name = '';
$cust_name = '';
$listcomp = array();
do {
    $select = ($ddata['customer_code'] == $data1['customer_code']) ? 'selected' : '';
    array_push($listcomp, "<option value='" . $data1['customer_code'] . "' " . $select . "'>" . $data1['customer_name']);
} while ($data1 = $data2->fetch_assoc());
?>
<form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
    <div class="row">
        <div class="col-lg-6">
            <input type="hidden" id='pic_id' name='pic_id' value="<?php if ($_GET['act'] == 'edit') {
                                                                        echo $ddata['pic_id'];
                                                                    } ?>">
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Name</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="pic_name" name="pic_name" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                        echo $ddata['pic_name'];
                                                                                                                    } ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Email</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="pic_email" name="pic_email" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                        echo $ddata['pic_email'];
                                                                                                                    } ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Address</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="pic_address" name="pic_address" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                            echo $ddata['pic_address'];
                                                                                                                        } ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">City</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="pic_city" name="pic_city" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                        echo $ddata['pic_city'];
                                                                                                                    } ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Phone</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm only-numeric" id="pic_phone" name="pic_phone" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                    echo $ddata['pic_phone'];
                                                                                                                                } ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Title</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="pic_title" name="pic_title" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                        echo $ddata['title'];
                                                                                                                    } ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Company</label>
                <div class="col-sm-9">
                    <select name='customer_code' id='customer_code' class="form-control form-control-sm">
                        <option value=""></option>
                        <?php
                        foreach ($listcomp as $options) {
                            echo $options;
                        }
                        ?>
                    </select>
                    <input type='hidden' id='customer_name' name='customer_name' value="">
                </div>
            </div>
        </div>
        <div class="col-lg-6">
        </div>
    </div>
    <input type="submit" class="btn btn-secondary" name="cancel" value="Cancel" onclick="window.location='http://localhost/microservices/index.php?mod=survey&sub=mod_pic';return false;">
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
        $('.btn-primary').click(function() {
            if ($('#pic_name').val() == '') {
                alert('PIC Name is empty');
                return false;
            } else if ($('#pic_email').val() == '') {
                alert('PIC Email is empty');
                return false;
            } else if ($('#pic_phone').val() == '') {
                alert("PIC Phone is empty");
                return false;
            } else if ($('#customer_code option:selected').val() == '') {
                alert("Customer is empty");
                return false
            } else {
                return true;
            }
        });
        $('#customer_code').select2();
        $('#customer_code option[value="<?php echo $ddata['customer_code']; ?>"]').attr("selected", "selected");
        $('#customer_code').val("<?php echo $ddata['customer_code']; ?>");
        $('#customer_code').select2().trigger('change');
        $('#customer_code').change(function() {
            if ($('#customer_code').val() != '') {
                $('#customer_name').val($('#customer_code option:selected').text());
            }
        });

        $(".only-numeric").bind("keypress", function(e) {
            var keyCode = e.which ? e.which : e.keyCode

            if (!(keyCode >= 48 && keyCode <= 57)) {
                $(".error").css("display", "inline");
                return false;
            } else {
                $(".error").css("display", "none");
            }
        });
    });
</script>