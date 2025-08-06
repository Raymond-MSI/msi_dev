<?php
$DBSB = get_conn("SERVICE_BUDGET");
if(isset($_POST['save'])) {
    if($_POST['info']=="project") {
        $tblname = "trx_project_list";
        $condition = "project_id=" . $_POST['project_id'];
        $wotype = "";
        if(isset($_POST['wo_type'])) 
        {
            foreach($_POST['wo_type'] as $wotype0) 
            {
                $wotype .= $wotype0.";";
            } 
        }
        $update = sprintf("`project_name`=%s, `contract_type`=%s, `wo_type`=%s",
            GetSQLValueString($_POST['project_name'], "text"),
            GetSQLValueString($_POST['contract_type'], "text"),
            GetSQLValueString($wotype, "text")
        );
        $res = $DBSB->update_data($tblname,$update,$condition);
    }

    if($_POST['info']=="imp") {
        $tblname = "trx_project_implementations";
        $condition = "project_id=" . $_POST['project_id'] . " AND service_type=1";
        $itosid = "";
        foreach($_POST['i_tos_id'] as $tosid) {
            $itosid .= $tosid.";";
        }
        $update = sprintf("`tos_id`=%s, `tos_category_id`=%s, `bpd_description`=%s, `out_description`=%s",
            GetSQLValueString($itosid, "text"),
            GetSQLValueString($_POST['i_tos_category_id'], "int"),
            GetSQLValueString($_POST['note_implementation'], "text"),
            GetSQLValueString($_POST['location_implementation'], "text")
        );
        $res = $DBSB->update_data($tblname,$update,$condition);
    }
    if($_POST['info']=="maint") {
        $tblname = "trx_project_implementations";
        $condition = "project_id=" . $_POST['project_id'] . " AND service_type=2";
        $update = sprintf("`tos_id`=%s, `bpd_description`=%s, `out_description`=%s",
            GetSQLValueString($_POST['m_tos_id'], "text"),
            GetSQLValueString($_POST['note_maintenance'], "text"),
            GetSQLValueString($_POST['out_maintenance'], "text")
        ); 
        $res = $DBSB->update_data($tblname,$update,$condition);
    }
    if($_POST['info']=="warranty") {
        $tblname = "trx_project_implementations";
        $condition = "project_id=" . $_POST['project_id'] . " AND service_type=3";
        $update = sprintf("`tos_id`=%s",
            GetSQLValueString($_POST['w_tos_id'], "text")
        ); 
        $res = $DBSB->update_data($tblname,$update,$condition);
    }
}
if(isset($_POST['cancel']) || isset($_POST['save'])) {
    ?>
    <script>
    window.location.href='index.php?mod=service_budget&act=view&project_code=<?php echo $_GET['project_code']; ?>&so_number=<?php echo $_GET['so_number']; ?>&submit=Submit';
    </script>
    <?php
}

$tblname = "trx_project_list";
$condition = "project_code ='" . $_GET['project_code'] . "' AND so_number='" . $_GET['so_number'] . "'";
$order = "project_id desc";
$sbredit = $DBSB->get_data($tblname,$condition, $order);
$dredit = $sbredit[0];
?>
<form name="form" method="post" action=""> 
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                Edit Information
            </div>

            <div class="card-body">
                <div class="row">
                    <?php if(isset($_GET['info']) && $_GET['info']=="project") { ?>
                        <div class="col-lg-6">
                            <label for="inputCID3" class="col-sm-12 alert alert-secondary"><b>Project Information</b></label>
                            <div class="row mb-3">
                                <label for="inputKP3" class="col-sm-3 col-form-label col-form-label-sm">Project Name</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control form-control-sm" name="project_name" id="project_name" value="<?php echo $dredit['project_name']; ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputKP3" class="col-sm-3 col-form-label col-form-label-sm">Contract Type</label>
                                <div class="col-sm-3">
                                    <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="contract_type" id="contract_type">
                                        <option value="Kontrak Biasa" <?php if($dredit['contract_type'] == 'Kontrak Biasa') { echo 'selected'; } ?>>Kontrak Biasa</option>
                                        <option value="Kontrak Payung" <?php if($dredit['contract_type'] == 'Kontrak Payung') { echo 'selected'; } ?>>Kontrak Payung</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputKP3" class="col-sm-3 col-form-label col-form-label-sm">Work Order</label>
                                <div class="col-sm-9">
                                    <?php 
                                    $tblname = "mst_type_of_service";
                                    $condition = "service_type=4 AND blocked=0";
                                    $tos = $DBSB->get_data($tblname, $condition);
                                    $dtos = $tos[0];
                                    $qtos = $tos[1];
                                    $tosidexp = explode(";",$dredit['wo_type']);
                                    $i=0; 
                                    do { 
                                        ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                            name="wo_type[<?php echo $i; ?>]" 
                                            id="wo_type<?php echo $i; ?>" 
                                            value="<?php echo $dtos['tos_id']; ?>" 
                                            <?php 
                                                for($j=0;$j<count($tosidexp);$j++) { 
                                                    if($tosidexp[$j]==$dtos['tos_id']) { 
                                                        echo 'checked'; 
                                                    }
                                                }
                                            ?>>
                                            <label class="form-check-label" id="wo_type_title<?php echo $i; ?>" for="inlineRadio1"><?php echo $dtos['tos_name']; ?></label>
                                        </div>
                                        <?php 
                                        $i++;
                                    } while($dtos=$qtos->fetch_assoc()); 
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if(isset($_GET['info']) && $_GET['info']=="imp") { ?>
                        <?php
                        $tblname="trx_project_implementations";
                        $condition = "project_id=" . $dredit['project_id'] . " AND service_type=1";
                        $imp = $DBSB->get_data($tblname, $condition);
                        $dimp = $imp[0];
                        if($imp[2]>0) {
                            ?>
                            <div class="col-lg-6">
                                <label for="inputCID3" class="col-sm-12 alert alert-secondary"><b>Implementation Information</b></label>
                                <div class="row mb-3">
                                    <label for="inputKP3" class="col-sm-3 col-form-label col-form-label-sm">Service Type</label>
                                    <div class="col-sm-9">
                                        <?php 

                                        $tblname = "mst_type_of_service";
                                        $condition = "service_type=1 AND blocked=0";
                                        $tos = $DBSB->get_data($tblname, $condition);
                                        $dtos = $tos[0];
                                        $qtos = $tos[1];
                                        $tosidexp = explode(";",$dimp['tos_id']);
                                        $i=0; 
                                        do { 
                                            ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                name="i_tos_id[<?php echo $i; ?>]" 
                                                id="i_tos_id<?php echo $i; ?>" 
                                                value="<?php echo $dtos['tos_id']; ?>" 
                                                <?php 
                                                    for($j=0;$j<count($tosidexp);$j++) { 
                                                        if($tosidexp[$j]==$dtos['tos_id']) { 
                                                            echo 'checked'; 
                                                        }
                                                    }
                                                ?>>
                                                <label class="form-check-label" for="inlineRadio1"><?php echo $dtos['tos_name']; ?></label>
                                            </div>
                                            <?php 
                                            $i++;
                                        } while($dtos=$qtos->fetch_assoc()); 
                                        ?>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputKP3" class="col-sm-3 col-form-label col-form-label-sm">Project Category</label>
                                    <div class="col-sm-2">
                                        <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="i_tos_category_id" <?php echo $permission; ?>>
                                            <option value="1" <?php if("1" == $dimp['tos_category_id']) { echo 'selected'; } ?>>High</option>
                                            <option value="2" <?php if("2" == $dimp['tos_category_id']) { echo 'selected'; } ?>>Medium</option>
                                            <option value="3" <?php if("3" == $dimp['tos_category_id']) { echo 'selected'; } ?>>Standard</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputKP3" class="col-sm-3 col-form-label col-form-label-sm">Note Outsourcing</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" id="exampleFormControlTextarea1" name="note_implementation" rows="3"><?php echo $dimp['out_description']; ?></textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputKP3" class="col-sm-3 col-form-label col-form-label-sm">Descriprion of Location</label>
                                    <div class="col-sm-9">
                                    <textarea class="form-control" id="exampleFormControlTextarea1" name="location_implementation" rows="3"><?php echo $dimp['bpd_description']; ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <?php 
                        } 
                    }    
                    ?>

                    <?php if(isset($_GET['info']) && $_GET['info']=="maint") { ?>
                        <?php
                        $tblname="trx_project_implementations";
                        $condition = "project_id=" . $dredit['project_id'] . " AND service_type=2";
                        $maintenance = $DBSB->get_data($tblname, $condition);
                        $dmaintenance = $maintenance[0];
                        if($maintenance[2]>0) {
                            ?>
                            <div class="col-lg-6">
                                <label for="inputCID3" class="col-sm-12 alert alert-secondary"><b>Maintenance Information</b></label>
                                <div class="row mb-3">
                                    <label for="inputKP3" class="col-sm-3 col-form-label col-form-label-sm">Service Type</label>
                                    <div class="col-sm-9">
                                        <?php 
                                        $tblname = "mst_type_of_service";
                                        $condition = "service_type=2 AND blocked=0";
                                        $tos = $DBSB->get_data($tblname, $condition);
                                        $dtos = $tos[0];
                                        $qtos = $tos[1];
                                        $i=0; 
                                        do { 
                                            ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" 
                                                name="m_tos_id" 
                                                id="m_tos_id<?php echo $i; ?>" 
                                                value="<?php echo $dtos['tos_id']; ?>" 
                                                <?php 
                                                    if(isset($dmaintenance['tos_id']) && $dmaintenance['tos_id']==$dtos['tos_id']) { 
                                                        echo 'checked'; 
                                                    }
                                                ?>>
                                                <label class="form-check-label" for="inlineRadio1"><?php echo $dtos['tos_name']; ?></label>
                                            </div>
                                            <?php 
                                            $i++;
                                        } while($dtos=$qtos->fetch_assoc()); 
                                        ?>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputKP3" class="col-sm-3 col-form-label col-form-label-sm">Note Outsourcing</label>
                                    <div class="col-sm-9">
                                    <textarea class="form-control" id="exampleFormControlTextarea1" name="out_maintenance" rows="3"><?php echo $dmaintenance['out_description']; ?></textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputKP3" class="col-sm-3 col-form-label col-form-label-sm">Description of Location</label>
                                    <div class="col-sm-9">
                                    <textarea class="form-control" id="exampleFormControlTextarea1" name="note_maintenance" rows="3"><?php echo $dmaintenance['bpd_description']; ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    <?php } ?>

                    <?php if(isset($_GET['info']) && $_GET['info']=="warranty") { ?>
                        <?php
                        $tblname="trx_project_implementations";
                        $condition = "project_id=" . $dredit['project_id'] . " AND service_type=3";
                        $warranty = $DBSB->get_data($tblname, $condition);
                        $dwarranty = $warranty[0];
                        
                        if($warranty[2]>0) {
                            ?>                    
                            <div class="col-lg-6">
                                <label for="inputCID3" class="col-sm-12 alert alert-secondary"><b>Warranty Information</b></label>
                                <div class="row mb-3">
                                    <label for="inputKP3" class="col-sm-3 col-form-label col-form-label-sm">Type of Warranty</label>
                                    <div class="col-sm-9">
                                    <?php 
                                        $tblname = "mst_type_of_service";
                                        $condition = "service_type=3";
                                        $tos = $DBSB->get_data($tblname, $condition);
                                        $dtos = $tos[0];
                                        $qtos = $tos[1];
                                        $tosidexp = explode(";",$dwarranty['tos_id']);
                                        $i=0; 
                                        do { 
                                        ?>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" 
                                                name="w_tos_id" 
                                                id="w_tos_id<?php echo $i; ?>" 
                                                value="<?php echo $dtos['tos_id']; ?>" 
                                                <?php 
                                                for($i=0;$i<count($tosidexp);$i++) { 
                                                    if($tosidexp[$i]==$dtos['tos_id']) { 
                                                        echo 'checked'; 
                                                    }
                                                }
                                                ?>>
                                                <label class="form-check-label" for="inlineRadio1"><?php echo $dtos['tos_name']; ?></label>
                                            </div>
                                            <?php $i++; ?>
                                            <?php } while($dtos=$qtos->fetch_assoc()); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    <?php } ?>

                    <div class="row">
                        <div class="col-lg-6">
                            <input type="hidden" name="project_id"  value="<?php echo $dredit['project_id']; ?>">
                            <input type="hidden" name="info" value="<?php echo $_GET['info']; ?>">
                            <input type="submit" class="btn btn-primary" name="cancel" value="Cancel">
                            <input type="submit" class="btn btn-primary" name="save" value="Save">
                        </div>
                    </div>
                </div>
            </div>    
            <div class="card-footer">
                 
            </div>
        </div>
    </div>
</form>