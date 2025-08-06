<?php
global $cobadb;

// $dbsb = "SERVICE_BUDGET";
$DBSB = get_conn("SERVICE_BUDGET");

$DBWRIKE = get_conn("WRIKE_INTEGRATE");

$get_ordernumber = $DBSB->get_sql("SELECT * FROM sa_trx_project_list WHERE order_number IS NOT NULL ORDER BY order_number ASC");
$get_project_leader = $DBWRIKE->get_sql("SELECT DISTINCT project_leader FROM sa_wrike_project_list WHERE project_leader IS NOT NULL ");
?>

<?php
if ($_GET['act'] == 'edit') {
    global $cobadb;
    $condition = "complain_id=" . $_GET['complain_id'];
    $data = $cobadb->get_data($tblname, $condition);
    $ddata = $data[0];
    $qdata = $data[1];
    $tdata = $data[2];
}
?>
<form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
    <div class="row">
        <div class="col-lg-6">
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Complain Id</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="complain_id" name="complain_id" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                            echo $ddata['complain_id'];
                                                                                                                        } ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Order Number</label>
                <div class="col-sm-9">
                    <select class="form-control" name="order_number" id="order_number">
                        <?php if ($_GET['act'] == "add") { ?>
                            <?php do { ?>
                                <option><?php echo $get_ordernumber[0]['order_number']; ?></option>
                            <?php } while ($get_ordernumber[0] = $get_ordernumber[1]->fetch_assoc()); ?>
                        <?php } ?>
                    </select>
                    <input type="text" class="form-control form-control-sm" id="order_number" name="order_number" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                echo $ddata['order_number'];
                                                                                                                            } ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Leader</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="project_leader" name="project_leader" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                    echo $ddata['project_leader'];
                                                                                                                                } ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Customer</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="customer" name="customer" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                        echo $ddata['customer'];
                                                                                                                    } ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Pic Customer</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="pic_customer" name="pic_customer" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                echo $ddata['pic_customer'];
                                                                                                                            } ?>">
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Complain</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="complain" name="complain" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                        echo $ddata['complain'];
                                                                                                                    } ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Category</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="category" name="category" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                        echo $ddata['category'];
                                                                                                                    } ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="status" name="status" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                    echo $ddata['status'];
                                                                                                                } ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Solution</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="solution" name="solution" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                        echo $ddata['solution'];
                                                                                                                    } ?>">
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