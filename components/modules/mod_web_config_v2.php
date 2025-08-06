<?php
global $ALERT;
global $DB;
$tblname = "cfg_web";
// if(USERPERMISSION=="7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION=="dbf36ff3e3827639223983ee8ac47b42") {
    if(isset($_POST['btn_save']) && $_POST['config_key']!="") {
        $condition = "config_key = '" . $_POST['config_key'] . "'";
        $data = $DB->get_data($tblname, $condition);
        if($data[2]>0) {
            $condition = "id=" . $_POST['id'];
            $update = sprintf("`title`=%s, `config_key`=%s, `config_value`=%s, `params`=%s, `parent`=%s, `order`=%s",
                GetSQLValueString($_POST['title'], "text"),
                GetSQLValueString($_POST['config_key'], "text"),
                GetSQLValueString($_POST['config_value'], "text"),
                GetSQLValueString($_POST['params'], "text"),
                GetSQLValueString($_POST['parent'], "int"),
                GetSQLValueString($_POST['order'], "int")
            );
        $res = $DB->update_data($tblname, $update, $condition);
        } else {
            $insert = sprintf("(`title`, `config_key`, `config_value`, `params`, `parent`, `order`) VALUES (%s,%s,%s,%s,%s,%s)",
                GetSQLValueString($_POST['title'], "text"),
                GetSQLValueString($_POST['config_key'], "text"),
                GetSQLValueString($_POST['config_value'], "text"),
                GetSQLValueString($_POST['params'], "text"),
                GetSQLValueString($_POST['parent'], "int"),
                GetSQLValueString($_POST['order'], "int")
            );
            $res = $DB->insert_data($tblname, $insert);
        }
    }
    if(isset($_POST['btn_delete'])) {
        // $condition = "config_key='" . $_POST['config_key'] . "'";
        // $update = "disabled=1";
        // $res = $DB->update_data($tblname, $update, $condition); 
    }
    if(isset($_POST['btn_cancel'])) {;
        echo '<script>window.location.href = "index.php?mod=web_config_v2";</script>';
    }
    ?>

    <!-- <button type="button" class="btn btn-primary" name="btn_rejected" id="btn_rejected" data-bs-toggle="modal" data-bs-target="#modalEdit">Reject</button> -->
    <div class="card">
        <script>
        $(document).ready(function() {
            var table = $('#cfg_web').DataTable({
                dom: 'Blfrtip',
                select: {
                    style: 'single'
                },
                buttons: [
                    {
                        text: "<span class='d-inline-block' tabindex='0'  data-bs-toggle='modal' data-bs-target='#modalAdd' title='Add Configuration'><i class='fa fa-plus'></i></span>",
                        enabled: true
                    },
                    {
                        text: "<span class='d-inline-block' tabindex='0'  data-bs-toggle='modal' data-bs-target='#modalEdit' title='Edit Configuration'><i class='fa fa-pen'></i></span>",
                        enabled: true
                    },
                ],
                "ordering": false,
                "info": false,
                "columnDefs": [
                {
                    "targets": [0,5,6],
                    "visible": false,
                }
            ]
            });
            $('#cfg_web tbody').on('click', 'tr', function () {
                var data = table.row( this ).data();
                document.getElementById('id').value= data[0];
                document.getElementById('title').value= data[1];
                document.getElementById('config_key').value= data[2];
                document.getElementById('config_value').value= data[3];
                document.getElementById('params').value= data[4];
                document.getElementById('parent').value= data[5];
                document.getElementById('order').value= data[6];
                // alert( 'You clicked on '+data[0]+'\'s row' );
            } );
        } );
        </script>

        <?php
        $primarykey = "id";
        $condition = "";
        if(isset($_GET['mod_id'])) {
            $condition = "parent=" . $_GET['mod_id'];
        }
        $order = 'config_key ASC';
        view_table($DB, $tblname, $primarykey, $condition, $order);
        ?>
    </div>


<?php 
// } else {
//     $ALERT->notpermission();
// }

?>

<?php function formEdit($title) { ?>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="saveAcknowledge"><b><?php echo $title; ?></b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="index.php?mod=web_config_v2&mod_id=<?php echo isset($_GET['mod_id']) ? $_GET['mod_id'] : ''; ?>">
                <div class="modal-body">
                    <div class="row mb-3">
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">ID</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control form-control-sm" id="id" name="id" value="" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Title</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control form-control-sm" id="title" name="title" value="">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Config Key</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control form-control-sm" id="config_key" name="config_key" value="" <?php echo $title=="Edit Configuration" ? "readonly" : ""; ?>>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Config Value</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control form-control-sm" id="config_value" name="config_value" value="">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Params</label>
                        <div class="col-sm-9">
                            <textarea class="form-control form-control-sm" id="params" name="params" rows="4" value=""></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Parent</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control form-control-sm" id="parent" name="parent" value="">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Order</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control form-control-sm" id="order" name="order" value="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" name="btn_save" value="Save">
                </div>
            </form>
        </div>
    </div>
<?php } ?>

<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="saveFinal" aria-hidden="true">
    <?php formEdit("Edit Configuration"); ?>
</div>

<div class="modal fade" id="modalAdd" tabindex="-1" aria-labelledby="saveFinal" aria-hidden="true">
    <?php formEdit("New Configuration"); ?>
</div>
