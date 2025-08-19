<?php
$tblname = "mst_order_number";
$condition = "project_code='" . $_GET['project_code'] . "'";
$order = "order_number ASC";
$getProjects = $DBNAV->get_data($tblname, $condition, $order);
?>
<form method="post" action="index.php?mod=service_budget">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-secondary">Copy Service Budget</h6>
            <?php spinner(); ?>
            <div class="align-items-right">
                <a href="index.php?mod=service_budget" class="btn btn-light border-secondary" title='Back to Service Budget' style="font-size:10px; background-color:#ddd"><i class='fa fa-arrow-left'></i></a>
                <!-- <button type="button" class="btn btn-light border-secondary" data-bs-toggle="modal" data-bs-target="#filter" style="font-size:10px; background-color:#ddd"><span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Filter data'><i class='fa fa-filter'></i></span></button> -->
            </div>
        </div>
        <input type="hidden" name="org_order_number" value="<?php echo $_GET['order_number']; ?>">
        <div class="card-body">
            <div class="row mb-3">
                <label for="inputKP3" class="col-sm-2 col-form-label col-form-label-sm" style="background-color:#dadada; border:solid thin #aaa">Project Code</label>
                <label for="inputKP3" class="col-sm-2 col-form-label col-form-label-sm" style="background-color:#dadada; border:solid thin #aaa">SO Number</label>
                <label for="inputKP3" class="col-sm-2 col-form-label col-form-label-sm" style="background-color:#dadada; border:solid thin #aaa">Order Number</label>
                <label for="inputKP3" class="col-sm-2 col-form-label col-form-label-sm" style="background-color:#dadada; border:solid thin #aaa">PO Number</label>
                <label for="inputKP3" class="col-sm-2 col-form-label col-form-label-sm" style="background-color:#dadada; border:solid thin #aaa">Amount</label>
                <label for="inputKP3" class="col-sm-2 col-form-label col-form-label-sm" style="background-color:#dadada; border:solid thin #aaa">Project Name</label>
            </div>
            <?php if($getProjects[2]>0) { ?>
                <?php $i = 0; ?>
                <?php do { ?>
                    <?php
                    $tblname = "trx_project_list";
                    $condition = "order_number='" . $getProjects[0]['order_number'] . "' AND status!='deleted'";
                    $duplicates = $DTSB->get_data($tblname, $condition);
                    $org = "";
                    if($duplicates[2]>0) {
                        $org = "style='background-color: #F8D7DA'";
                    } 
                    ?>
                    <div class="row mb-3">
                        <div class="col-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="check[<?php echo $i; ?>]" <?php if($duplicates[2]>0) { echo "disabled"; } ?>>
                                <input type="text" class="form-control form-control-sm" <?php echo $org; ?> name="project_code[<?php echo $i; ?>]" id="project_code[<?php echo $i; ?>]" value="<?php echo $getProjects[0]['project_code']; ?>" readonly>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control form-control-sm " <?php echo $org; ?> name="so_number[<?php echo $i; ?>]" id="so_number[<?php echo $i; ?>]" value="<?php echo $getProjects[0]['so_number']; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control form-control-sm " <?php echo $org; ?> name="order_number[<?php echo $i; ?>]" id="order_number[<?php echo $i; ?>]" value="<?php echo $getProjects[0]['order_number']; ?>" readonly>
                            <input type="hidden" name="order_date[<?php echo $i; ?>]" value="<?php echo $getProjects[0]['order_date']; ?>">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control form-control-sm " <?php echo $org; ?> name="po_number[<?php echo $i; ?>]" id="po_number[<?php echo $i; ?>]" value="<?php echo $getProjects[0]['po_number']; ?>" readonly>
                            <input type="hidden" name="po_date[<?php echo $i; ?>]" value="<?php echo $getProjects[0]['po_date']; ?>">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control form-control-sm text-right " <?php echo $org; ?> name="amount[<?php echo $i; ?>]" id="amount[<?php echo $i; ?>]" value="<?php if($getProjects[0]['currency_code']=="USD") { echo "USD."; } else { echo "IDR."; } echo number_format($getProjects[0]['amount'],2,",","."); ?>" readonly>
                            <input type="hidden" name="amount_idr[<?php echo $i; ?>]" value="<?php if($getProjects[0]['currency_code']!="USD") { echo $getProjects[0]['amount']; } ?>">
                            <input type="hidden" name="amount_usd[<?php echo $i; ?>]" value="<?php if($getProjects[0]['currency_code']=="USD") { echo $getProjects[0]['amount']; } ?>">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control form-control-sm " <?php echo $org; ?> name="project_name[<?php echo $i; ?>]" id="project_name[<?php echo $i; ?>]" value="<?php echo $getProjects[0]['project_name']; ?>" readonly>
                        </div>
                    </div>
                    <?php $i++; ?>
                <?php } while($getProjects[0]=$getProjects[1]->fetch_assoc()); ?>
                <?php 
            } else {
                echo "Tidak ada datanya.";
            }
            ?>
        </div>
        <div class="card-footer">
            <input type="submit" class="btn btn-primary" name="save_copy_service_budget" id="save_copy_service_budget" value="Save">
            <input type="submit" class="btn btn-secondary" name="btn-cancel" id="btn-cancel" value="Cancel">
        </div>
    </div>
</form>