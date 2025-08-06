<?php
global $DBLD;
$tblname = "provider";
if(isset($_POST['btn_save']) && $_POST['provider_name']!="") {
    $condition = "provider_name = '" . $_POST['provider_name'] . "'";
    $data = $DBLD->get_data($tblname, $condition);
    if($data[2]>0) {
        $condition = "provider_name='" . $_POST['provider_name'] . "'";
        $update = "disabled=0";
        $res = $DBLD->update_data($tblname, $update, $condition);
    } else {
        $insert = "(provider_name) VALUES ('" . $_POST['provider_name'] . "')";
        $res = $DBLD->insert_data($tblname, $insert);
    }
}
if(isset($_POST['btn_delete'])) {
    $condition = "provider_id=" . $_POST['provider_id'];
    $update = "disabled=1";
    $res = $DBLD->update_data($tblname, $update, $condition); 
}
if(isset($_POST['btn_cancel'])) {;
    echo '<script>window.location.href = "index.php?mod=legal_documents";</script>';
}
?>
<div class="row">
    <div class="col-lg-6">
        <form method="post" action="">
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Provider Id</label>
                <div class="col-sm-9">
                <input type="text" class="form-control form-control-sm" id="provider_id" name="provider_id" value="<?php //if($_GET['act']=='edit') { echo $ddata['provider_id']; } ?>" readonly>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Provider Name</label>
                <div class="col-sm-9">
                <input type="text" class="form-control form-control-sm" id="provider_name" name="provider_name" value="<?php //if($_GET['act']=='edit') { echo $ddata['provider_name']; } ?>">
                </div>
            </div>
            <input type="submit" class="btn btn-primary" name="btn_save" value="Save">
            <input type="submit" class="btn btn-primary" name="btn_delete" value="Delete">
            <input type="submit" class="btn btn-secondary" name="btn_cancel" value="Cancel">
        </form>
    </div>
    <div class="col-lg-6">
    </div>
</div>
<br/>

<div class="card">
        <script>
        $(document).ready(function() {
            var table = $('#provider').DataTable({
                "ordering": false,
                "info": false,
                // "searching": false,
                // "paging": false,
                "columnDefs": [
                {
                    "targets": [0,2],
                    "visible": false,
                }
            ]
            });
            $('#provider tbody').on('click', 'tr', function () {
                var data = table.row( this ).data();
                document.getElementById('provider_id').value= data[0];
                document.getElementById('provider_name').value= data[1];
                // alert( 'You clicked on '+data[0]+'\'s row' );
            } );
        } );
        </script>

        <?php
        $primarykey = "provider_id";
        $condition = "disabled=0";
        $order = "provider_name ASC";
        view_table($DBLD, $tblname, $primarykey, $condition, $order);
        ?>
</div>