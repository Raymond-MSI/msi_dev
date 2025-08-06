<?php
if((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $author = 'Syamsul Arham';
} else {
    $mdlname = "POEMS";
    $userpermission = useraccess_v2($mdlname);
    if(USERPERMISSION_V2 == "6199ce1d11637b28de3c095e3dc96b53") {
        global $ALERT;
        global $DB;
        $DBPOEMS = get_conn($mdlname);
        $tblname = "poems";
        if(isset($_POST['btn_save']) && $_POST['poetry_quote']!="") {
            $condition = "poetry_id = '" . $_POST['poetry_id'] . "'";
            $data = $DBPOEMS->get_data($tblname, $condition);
            if($data[2]>0) {
                $condition = "poetry_id=" . $_POST['poetry_id'];
                $update = sprintf("`poetry_quote`=%s, `category_id`=%s",
                    GetSQLValueString($_POST['poetry_quote'], "text"),
                    GetSQLValueString($_POST['category_id'], "inr")
                );
            $res = $DBPOEMS->update_data($tblname, $update, $condition);
            } else {
                $insert = sprintf("(`poetry_quote`, `category_id`) VALUES (%s,%s)",
                    GetSQLValueString($_POST['poetry_quote'], "text"),
                    GetSQLValueString($_POST['category_id'], "int")
                );
                $res = $DBPOEMS->insert_data($tblname, $insert);
            }
        }
        // if(isset($_POST['btn_delete'])) {
            // $condition = "config_key='" . $_POST['config_key'] . "'";
            // $update = "disabled=1";
            // $res = $DBPOEMS->update_data($tblname, $update, $condition); 
        // }
        if(isset($_POST['btn_cancel'])) {;
            echo '<script>window.location.href = "index.php?mod=poems";</script>';
        }
        ?>

        <!-- <button type="button" class="btn btn-primary" name="btn_rejected" id="btn_rejected" data-bs-toggle="modal" data-bs-target="#modalEdit">Reject</button> -->
        <div class="card">
            <script>
            $(document).ready(function() {
                var table = $('#poems').DataTable({
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
                    "order" : [
                        [1, "asc"]
                    ],
                    // "ordering": false,
                    // "info": false,
                    "columnDefs": [
                    {
                        "targets": [0,3],
                        "visible": false,
                    }
                ]
                });
                $('#poems tbody').on('click', 'tr', function () {
                    var data = table.row( this ).data();
                    document.getElementById('poetry_id').value= data[0];
                    document.getElementById('poetry_quote').value= data[1];
                    document.getElementById('category_id').value= data[2];
                    // alert( 'You clicked on '+data[0]+'\'s row' );
                } );
            } );
            </script>

            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-secondary">POEMS</h6>
                <?php spinner(); ?>
                <div class="align-items-right">
                <!-- <a href="index.php?mod=poems" class="btn btn-light border-secondary" title='Back to Service Budget' style="font-size:10px; background-color:#ddd"><i class='fa fa-arrow-left'></i></a> -->
                    <button type="button" class="btn btn-light border-secondary" data-bs-toggle="modal" data-bs-target="#filter" style="font-size:10px; background-color:#ddd"><span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Filter data'><i class='fa fa-filter'></i></span></button>
                </div>
            </div>

            <?php
            $primarykey = "poetry_id";
            $condition = "";
            if(isset($_GET['category_id'])) {
                $condition = "category_id=" . $_GET['category_id'];
            }
            $order = 'poetry_quote ASC';
            view_table($DBPOEMS, $tblname, $primarykey, $condition, $order);
            ?>
        </div>

        <?php function formEdit($title) { ?>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="saveAcknowledge"><b><?php echo $title; ?></b></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" action="index.php?mod=poems<?php echo isset($_GET['category_id']) ? "&category_id=" . $_GET['category_id'] : ''; ?>">
                        <div class="modal-body">
                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Poetry ID</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" id="poetry_id" name="poetry_id" value="" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Poetry Quote</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" id="poetry_quote" name="poetry_quote" value="">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Category</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" id="category_id" name="category_id" value="<?php echo $_GET['category_id']; ?>" <?php echo $title=="Edit category" ? "readonly" : ""; ?> readonly>
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


        <?php function filter() { ?>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="filter"><b>Select Category</b></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="get" action="index.php">
                        <div class="modal-body">
                            <div class="row mb-3">
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Category ID</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" id="category_id" name="category_id" value="<?php echo isset($_GET['category_id']) ? $_GET['category_id'] : ''; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="mod" value="poems">
                            <input type="submit" class="btn btn-primary" name="btn_filter" value="Filter">
                        </div>
                    </form>
                </div>
            </div>
        <?php } ?>


        <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="saveFinal" aria-hidden="true">
            <?php formEdit("Edit POEMS"); ?>
        </div>

        <div class="modal fade" id="modalAdd" tabindex="-1" aria-labelledby="saveFinal" aria-hidden="true">
            <?php formEdit("New POEMS"); ?>
        </div>

        <div class="modal fade" id="filter" tabindex="-1" aria-labelledby="filter" aria-hidden="true">
            <?php filter("New POEMS"); ?>
        </div>
<?php 
    } else { 
        $ALERT->notpermission();
    }
    ?>    
<?php } ?>