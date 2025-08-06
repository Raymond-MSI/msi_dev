<?php
$mdlname = "HCM";
$DBHCM = get_conn($mdlname);
$query = $DBHCM->get_sql("SELECT * FROM sa_trx_maskomen");
$employees = $DBHCM->get_data("mst_employees", "resign_date IS NULL ORDER BY employee_name ASC");
$employeess = $DBHCM->get_data("mst_employees", "resign_date IS NULL");
if ($_GET['act'] == 'edit') {
    global $DBMK;
    $condition = "id=" . $_GET['id'];
    $data = $DBMK->get_data($tblname, $condition);
    $ddata = $data[0];
    $qdata = $data[1];
    $tdata = $data[2];
}
?>
<form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
    <div class="row">
        <div class="col-lg-6">
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Id</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="id" name="id" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                            echo $ddata['id'];
                                                                                                        } ?>" readonly>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Employee Name</label>
                <div class="col-sm-9">
                    <select class="form-control" name="employee_name" id="employee_name">
                        <?php if ($_GET['act'] == "add") { ?>
                            <?php if (isset($_GET['employee_name'])) {
                                $employeename = $DBHCM->get_data("mst_employees", "resign_date IS NULL AND employee_name LIKE '%" . $_GET['employee_name'] . "%'"); ?>
                                <option value="<?php echo $employeename[0]['employee_name']; ?>"><?php echo $employeename[0]['employee_name']; ?></option>
                            <?php } ?>
                            <option></option>
                            <?php do { ?>
                                <option><?php echo $employees[0]['employee_name']; ?></option>
                            <?php } while ($employees[0] = $employees[1]->fetch_assoc()); ?>
                        <?php } ?>

                        <?php if ($_GET['act'] == "edit") { ?>
                            <?php $query = $DBHCM->get_sql("SELECT * FROM sa_trx_maskomen where id=" . $ddata['id'] . ""); ?>
                            <option value="<?php echo $query[0]['employee_name']; ?>"><?php echo $query[0]['employee_name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Email</label>
                <div class="col-sm-9">
                    <select class="form-control" name="email" id="email">
                        <?php if ($_GET['act'] == "add") { ?>
                            <?php if (isset($_GET['employee_name'])) {
                                $employeenamee = $DBHCM->get_data("mst_employees", "resign_date IS NULL AND employee_name LIKE '%" . $_GET['employee_name'] . "%'"); ?>
                                <option value="<?php echo $employeenamee[0]['email']; ?>"><?php echo $employeenamee[0]['email']; ?></option>
                            <?php } ?>
                            <option></option>
                            <?php do {
                            ?>
                                <option><?php echo $employeess[0]['employee_name'];
                                        ?></option>
                            <?php } while ($employeess[0] = $employeess[1]->fetch_assoc());
                            ?>
                        <?php }
                        ?>
                        <?php if ($_GET['act'] == "edit") { ?>
                            <?php $query = $DBHCM->get_sql("SELECT * FROM sa_trx_maskomen where id=" . $ddata['id'] . ""); ?>
                            <option><?php echo ucwords($ddata['email']); ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

        </div>
        <div class="col-lg-6">
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Artikel Wajib</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="artikel_wajib" name="artikel_wajib" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                echo $ddata['artikel_wajib'];
                                                                                                                            } ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Artikel Submit</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="artikel_submit" name="artikel_submit" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                    echo $ddata['artikel_submit'];
                                                                                                                                } ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm"> Batas Pengumpulan</label>
                <div class="col-sm-9">
                    <input type="date" class="form-control form-control-sm" id="batas_pengumpulan" name="batas_pengumpulan" value="<?php if ($_GET['act'] == 'edit') {
                                                                                                                                        echo $ddata['batas_pengumpulan'];
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
<script>
    $("#employee_name").on('change', function() {
        var employee_name = $(this).val();

        window.location = window.location.pathname + "?mod=trx_maskomen" + "&act=add&employee_name=" + employee_name;
    })
</script>