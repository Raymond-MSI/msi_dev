<?php
    if($_GET['act']=='edit') {
        global $DB;
        $condition = "customer_id=" . $_GET['customer_id'];
        $data = $DB->get_data($tblname, $condition);
        $ddata = $data[0];
        $qdata = $data[1];
        $tdata = $data[2];
    }
    if($_GET['act']=='add'){
        $database1 = 'sa_ps_service_budgets';
        $DB1 = new Databases('localhost', 'root', '', $database1);
        $query2 = 'select distinct(project_code) from sa_trx_project_list';
        $data = $DB1->get_sql($query2);
        $data1 = $data[0];
        $data2 = $data[1];
        $cust_code = '';
        $so_number = '';
        $project_name = '';
    $cust_name = '';
        $listprojcode = array();
        do {
            array_push($listprojcode, $data1['project_code']);
        } while ($data1 = $data2->fetch_assoc());
        if(isset($_GET['projectCode']) && $_GET['projectCode'] != ''){
            $projectCode = $_GET['projectCode'];
        $query3 = "select distinct(customer_name), so_number, project_name, customer_code from sa_trx_project_list where project_code = '$projectCode'";
            $data3 = $DB1->get_sql($query3);
            $cust_name = $data3[0]['customer_name'];
            $so_number = $data3[0]['so_number'];
            $project_name = $data3[0]['project_name'];
            $cust_code = $data3[0]['customer_code'];
        }
    }
    ?>
    <form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
        <div class="row">
            <div class="col-lg-6">
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Code</label>
                    <div class="col-sm-9">
                    <select id="project_code" name="project_code" style="width:250px">
                    <option value="">
                    <?php 
                        foreach($listprojcode as $code){
                            $selected = (isset($_GET['projectCode']) && $_GET['projectCode'] == $code) ? 'selected' : '';
                            echo "<option value='$code' $selected>$code";
                        }
                    ?>
                    </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">SO Number</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" value="<?php echo $so_number; ?>" readonly id="so_number" name="so_number" value="<?php if($_GET['act']=='edit') { echo $ddata['customer_id']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Name</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" value="<?php echo $project_name; ?>" readonly id="project_name" name="project_name" value="<?php if($_GET['act']=='edit') { echo $ddata['customer_id']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Customer Id</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" readonly value="<?php echo $cust_code?>" id="customer_id" name="customer_id" value="<?php if($_GET['act']=='edit') { echo $ddata['customer_id']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Customer Company Name</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" readonly value="<?php echo $cust_name; ?>" id="customer_company_name" name="customer_company_name" value="<?php if($_GET['act']=='edit') { echo $ddata['customer_company_name']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Pic Name</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="pic_name" name="pic_name" value="<?php if($_GET['act']=='edit') { echo $ddata['pic_name']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Pic Email</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="pic_email" name="pic_email" value="<?php if($_GET['act']=='edit') { echo $ddata['pic_email']; } ?>">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Pic Phone</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="pic_phone" name="pic_phone" value="<?php if($_GET['act']=='edit') { echo $ddata['pic_phone']; } ?>">
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
            $('#project_code').select2();
            $('#project_code').change(function(){
                projCode = $('#project_code option:selected').val();
                location.href = 'http://localhost/microservices/index.php?mod=customer&act=add&projectCode='+projCode;
            });
        });
    </script>
    