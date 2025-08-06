<?php
global $ALERT;
if(USERPERMISSION=="7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION=="dbf36ff3e3827639223983ee8ac47b42") {

    $mdlname = "LEGAL_DOCUMENTS";
    $DBLD = get_conn($mdlname);
    if(isset($_POST['btn_save'])) {
        $tblname = "setup";
        $condition = "setup_key ='" . $_POST['setup_key'] . "'";
        $look = $DBLD->get_data($tblname, $condition);
        if($look[2]>0) {
            // Update
            $update = sprintf("setup_value=%s, params=%s",
                GetSQLValueString($_POST['setup_value'], "text"),
                GetSQLValueString($_POST['params'], "text")
            );
            $res = $DBLD->update_data($tblname, $update, $condition);
        } else {
            // Insert
            $insert = sprintf("(setup_key, setup_value, params) VALUES (%s, %s, %s)",
                GetSQLValueString($_POST['setup_key'], "text"),
                GetSQLValueString($_POST['setup_value'], "text"),
                GetSQLValueString($_POST['params'], "text")
            );
            $res = $DBLD->insert_data($tblname, $insert);
        }
        echo '<script>window.location.href = "index.php?mod=legal_documents&act=setup";</script>';

    } elseif(isset($_POST['btn_back'])) {
        echo '<script>window.location.href = "index.php?mod=legal_documents";</script>';
    } elseif(isset($_POST['btn_delete'])) {
        $tblname = "setup";
        $condition = "setup_id ='" . $_POST['setup_id'] . "'";
        $update = "disabled=1";
        $res = $DBLD->update_data($tblname, $update, $condition);
        echo '<script>window.location.href = "index.php?mod=legal_documents";</script>';
    }

    ?>


    <form method="post">
        <div class="row">
            <div class="col-lg-6">
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">ID</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" id="setup_id" name="setup_id" value="" readonly>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Setup Key</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" id="setup_key" name="setup_key" value="">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Setup Value</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control form-control-sm" id="setup_value" name="setup_value" value="">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row mb-3">
                    <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Params</label>
                    <div class="col-sm-9">
                        <textarea class="form-control form-control-sm" id="params" name="params" rows="4" value=""></textarea>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <input type="submit" class="btn btn-primary" name="btn_save" value="Save">
                <input type="submit" class="btn btn-primary" name="btn_delete" value="Delete">
                <input type="submit" class="btn btn-primary" name="btn_back" value="Back">
            </div>
        </div>
    </form>
    <br/>
    <!-- <?php
    $tblname = "setup";
    $doc = $DBLD->get_data($tblname);
    $ddoc = $doc[0];
    $qdoc = $doc[1];
    $tdoc = $doc[2];
    ?>
    <table class="table">
        <thead>
            <tr><th>ID</th><th>Setup Key</th><th>Setup Value</th><th>Params</th></tr>
        </thead>
        <tbody>
            <?php 
            if($tdoc>0) {
                do { ?>
                    <tr>
                        <td><?php echo $ddoc['setup_id']; ?></td>
                        <td><?php echo $ddoc['setup_key']; ?></td>
                        <td><?php echo $ddoc['setup_value']; ?></td>
                        <td><?php echo $ddoc['params']; ?></td>
                    </tr>
                    <?php 
                } while($ddoc=$qdoc->fetch_assoc()); 
            } else {
                echo "<tr><td colspan='3'>Data empty</td></tr>";
            }
            ?>
        </tbody>
    </table> -->

    <div class="card">
        <script>
        $(document).ready(function() {
            var table = $('#setup').DataTable({
                "ordering": false,
                "info": false,
                // "searching": false,
                // "paging": false,
                "columnDefs": [
                {
                    "targets": [0,4],
                    "visible": false,
                }
            ]
            });
            $('#setup tbody').on('click', 'tr', function () {
                var data = table.row( this ).data();
                document.getElementById('setup_id').value= data[0];
                document.getElementById('setup_key').value= data[1];
                document.getElementById('setup_value').value= data[2];
                document.getElementById('params').value= data[3];
                // alert( 'You clicked on '+data[0]+'\'s row' );
            } );
        } );
        </script>

        <?php
        $tblname = "setup";
        $primarykey = "provider_id";
        $condition = "disabled=0";
        view_table($DBLD, $tblname, $primarykey, $condition);
        ?>
</div>


<?php 
} else {
    $ALERT->notpermission();
}

?>