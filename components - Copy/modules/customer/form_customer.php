<?php
    if($_GET['act']=='edit') {
        global $DB;
        $condition = "customer_id=" . $_GET['customer_id'];
        $data = $DB->get_data($tblname, $condition);
        $ddata = $data[0];
        $qdata = $data[1];
        $tdata = $data[2];
    }
    /*if($_GET['act']=='add'){
        $database1 = 'sa_ps_service_budgets';
        $DB1 = new Databases('localhost', 'root', '', $database1);
        $query2 = 'select distinct(customer_name) from sa_trx_project_list';
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
    }*/
    ?>
    <form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
        <div class="row">
            <div class="col-lg-6">
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Customer Code</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="customer_code" name="customer_code" value="<?php if($_GET['act']=='edit') { echo $ddata['customer_code  ']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Customer Company Name</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="customer_company_name" name="customer_company_name" value="<?php if($_GET['act']=='edit') { echo $ddata['customer_company_name']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Customer Address</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="customer_address" name="customer_address" value="<?php if($_GET['act']=='edit') { echo $ddata['customer_company_name']; } ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Category</label>
                    <div class="col-sm-9">
                    <select name='category' class="form-control form-control-sm">
                        <option value=""></option>
                        <option value="Customer">Customer</option>
                        <option value="Vendor">Vendor</option>
                        <option value="Branch">Branch</option>
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
            $('.btn-primary').click(function(){
                if($('#customer_code').val() == ''){
                    alert("Customer Code is empty");
                    return false;
                }else if($('#customer_company_name').val() == ''){
                    alert("Customer Name is empty");
                    return false;
                }else if($('#customer_address').val() == ''){
                    alert("Customer Address is empty");
                    return false;
                }else if($('#category').val() == ''){
                    alert("Category is empty");
                    return false;
                }
            });
        });
    </script>
    