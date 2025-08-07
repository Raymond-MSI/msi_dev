<!-- Modal -->
<div class="modal fade" id="mdlHistory" tabindex="-1" aria-labelledby="mdlHistory" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Menu Module History</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php
                $tblname = 'mst_module_update';
                $condition = 'module_category = "' . $mdlcategory . '" AND module_name = "' . $mdlname . '"';
                $order = 'update_version DESC';
                $mdlHistories = $DB->get_data($tblname, $condition, $order);
                if ($mdlHistories[2] > 0) {
                    do {
                ?>
                        <div class="card shadow mb-3">
                            <div class="card-header">
                                <?php echo "Version : " . $mdlHistories[0]['update_version']; ?>
                            </div>
                            <div class="card-body">
                                <table class="display table">
                                    <tr>
                                        <td class="col-lg-3">Module Name</td>
                                        <td><?php echo $mdlHistories[0]['module_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Module Category</td>
                                        <td><?php echo $mdlHistories[0]['module_category']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Update Status</td>
                                        <td><?php echo $mdlHistories[0]['update_status']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>File Name</td>
                                        <td><?php echo $mdlHistories[0]['file_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Published By</td>
                                        <td><?php echo $mdlHistories[0]['update_by']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Published Date</td>
                                        <td><?php echo date("l, d F Y G:i:s", strtotime($mdlHistories[0]['update_date'])); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Dscription</td>
                                        <td><?php echo $mdlHistories[0]['update_desc']; ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                <?php
                    } while ($mdlHistories[0] = $mdlHistories[1]->fetch_assoc());
                }
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>