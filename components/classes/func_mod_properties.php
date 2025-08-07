<!-- Modal -->
<div class="modal fade" id="mdlProperties" tabindex="-1" aria-labelledby="mdlProperties" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Module Properties</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php
                $tblname = 'cfg_web';
                $condition = 'config_key = "MODULE_' . $mdlname . '"';
                $mdlProperties = $DB->get_data($tblname, $condition);
                if ($mdlProperties[2] > 0) {
                    do {
                ?>
                        <div class="card shadow">
                            <div class="card-header">
                                <?php echo $mdlProperties[0]['title']; ?> Module
                            </div>
                            <div class="card-body">
                                <table class="display table">
                                    <tr>
                                        <td class="col-lg-3">Title</td>
                                        <td><?php echo $mdlProperties[0]['title']; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="col-lg-3">Config Key</td>
                                        <td><?php echo $mdlProperties[0]['config_key']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Config Value</td>
                                        <td><?php echo $mdlProperties[0]['config_value']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Params</td>
                                        <td>
                                            <pre><?php echo $mdlProperties[0]['params']; ?></pre>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                <?php
                    } while ($mdlProperties[0] = $mdlProperties[1]->fetch_assoc());
                }
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>