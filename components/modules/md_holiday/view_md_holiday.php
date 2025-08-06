<?php

if ($_GET['act'] == 'view') {
    global $DBHCM;
    $condition = "id=" . GetSQLValueString($_GET['id'], "int");
    $data = $DBHCM->get_data($tblname, $condition);
    $ddata = $data[0] ?? null;


    if (!empty($ddata)) {
?>
        <div class="card">
            <div class="card-header">
                Holiday Details
            </div>
            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-sm-3 col-form-label"><strong>Date:</strong></div>
                    <div class="col-sm-9">
                        <?php echo htmlspecialchars($ddata['holiday_date'] ?? 'N/A'); ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-3 col-form-label"><strong>Descriptions:</strong></div>
                    <div class="col-sm-9">
                        <?php echo htmlspecialchars($ddata['Descriptions'] ?? 'N/A'); ?>
                    </div>
                </div>

                <!-- NEW FIELDS FOR AUDIT TRAIL -->
                <hr>
                <div class="row mb-3">
                    <div class="col-sm-3 col-form-label"><strong>Created By:</strong></div>
                    <div class="col-sm-9">
                        <?php echo htmlspecialchars($ddata['created_by'] ?? 'N/A'); ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-3 col-form-label"><strong>Created Date:</strong></div>
                    <div class="col-sm-9">
                        <?php echo htmlspecialchars($ddata['created_date'] ?? 'N/A'); ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-3 col-form-label"><strong>Modified By:</strong></div>
                    <div class="col-sm-9">
                        <?php echo htmlspecialchars($ddata['modified_by'] ?? 'N/A'); ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-3 col-form-label"><strong>Modified Date:</strong></div>
                    <div class="col-sm-9">
                        <?php echo htmlspecialchars($ddata['modified_date'] ?? 'N/A'); ?>
                    </div>
                </div>


                <div class="mt-3">
                    <a href="index.php?mod=<?php echo htmlspecialchars($_GET['mod']); ?>" class="btn btn-secondary">Back</a>
                    <a href="index.php?mod=<?php echo htmlspecialchars($_GET['mod']); ?>&act=edit&id=<?php echo htmlspecialchars($ddata['id']); ?>" class="btn btn-primary">Edit</a>
                </div>
            </div>
        </div>
<?php
    } else {
        echo '<div class="alert alert-warning">Holiday not found.</div>';
    }
}
?>