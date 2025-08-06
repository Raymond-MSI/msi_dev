<?php
global $DB;
$tblname = "mst_users_level";
if(isset($_POST['btn_save'])) {
    $condition = "user_level = '" . $_POST['user_level'] . "'";
    $data = $DB->get_data($tblname, $condition);
    if($data[2]>0) {
        $condition = sprintf("`user_level`=%s",
            GetSQLValueString($_POST['user_level'], "text")
        );
        $update = sprintf("`order` = %s",
            GetSQLValueString($_POST['order'], "int")
        );
        $res = $DB->update_data($tblname, $update, $condition);
    } else {
        $insert = sprintf("(`user_level`, `order`) VALUES (%s,%s)",
        GetSQLValueString($_POST['user_level'], "text"),
        GetSQLValueString($_POST['order'], "int")
        );
        $res = $DB->insert_data($tblname, $insert);
    }
}
if(isset($_POST['btn_cancel'])) {;
    echo '<script>window.location.href = "index.php?mod=users";</script>';
}
?>
<div class="card shadow">
    <div class="card-header py-3">
        User Setup Level
    </div>
    <div class="card-body">
        <form method="post" action="">
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Provider Id</label>
                <div class="col-sm-9">
                <input type="text" class="form-control form-control-sm" id="provider_id" name="provider_id" value="<?php //if($_GET['act']=='edit') { echo $ddata['provider_id']; } ?>" readonly>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">User level Name</label>
                <div class="col-sm-9">
                <input type="text" class="form-control form-control-sm" id="user_level" name="user_level" value="<?php //if($_GET['act']=='edit') { echo $ddata['user_level']; } ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Order</label>
                <div class="col-sm-9">
                <input type="text" class="form-control form-control-sm" id="order" name="order" value="<?php //if($_GET['act']=='edit') { echo $ddata['user_level']; } ?>">
                </div>
            </div>
            <input type="submit" class="btn btn-primary" name="btn_save" value="Save">
            <input type="submit" class="btn btn-secondary" name="btn_cancel" value="Cancel">
        </form>
        <script>
        $(document).ready(function() {
            var table = $('#<?php echo $tblname; ?>').DataTable({
                "ordering": false,
                "info": false,
                // "searching": false,
                // "paging": false,
                "columnDefs": [
                {
                    // "targets": [0,2],
                    "visible": false,
                }
            ]
            });
            $('#<?php echo $tblname; ?>').on('click', 'tr', function () {
                var data = table.row( this ).data();
                document.getElementById('provider_id').value= data[0];
                document.getElementById('user_level').value= data[1];
                document.getElementById('order').value= data[2];
                // alert( 'You clicked on '+data[0]+'\'s row' );
            } );
        } );
        </script>
        <div class="row mb-3">
            <?php
            $primarykey = "user_level_id";
            $condition = "";
            $order = "";
            view_table($DB, $tblname, $primarykey, $condition, $order);
            ?>
        </div>
    </div>
</div>