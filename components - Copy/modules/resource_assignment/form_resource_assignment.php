<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
<?php
global $DTSB;

include_once("components/modules/resource_assignment/func_resource_assignment.php");

$db_sb = "SERVICE_BUDGET";
$DBSB = get_conn($db_sb);

$db_wrkld = "WORKLOAD";
$DBWRKLD = get_conn($db_wrkld);

$db_hcm = "HCM";
$DBHCM = get_conn($db_hcm);

if($_GET['act']=='edit') {
    global $DTSB;
    $condition = "id=" . $_GET['id'];
    $data = $DTSB->get_data($tblname, $condition);
    $ddata = $data[0];
    $qdata = $data[1];
    $tdata = $data[2];
}

if ($_GET['act'] == "add" || $_GET['act'] == "edit") {
    if(isset($_GET['project_code'])) {
        $sqlLookupSelectedPC = "SELECT * FROM sa_trx_project_list WHERE project_code = '".$_GET['project_code']."' AND status = 'acknowledge'";
        $selectedPC = $DBSB->get_sql($sqlLookupSelectedPC);

        $sqlLookupJobRoles = "SELECT a.project_code, a.resource_category_id, b.resource_qualification, a.brand 
        FROM sa_workload.sa_initial_jobroles AS a 
        JOIN sa_ps_service_budgets.sa_mst_resource_catalogs AS b ON a.resource_category_id = b.resource_catalog_id
        WHERE a.project_code = '".$_GET['project_code']."'";
        $selectedPCJobRoles = $DBWRKLD->get_sql($sqlLookupJobRoles);
        $rowSelectedPCJobRoles = $selectedPCJobRoles[0];
        $resSelectedPCJobRoles = $selectedPCJobRoles[1];
        $totalRowPCJobRoles = $selectedPCJobRoles[2];

        $sqlLookupJobRoles2 = "SELECT a.project_code, a.resource_category_id, b.resource_qualification, a.brand 
        FROM sa_workload.sa_initial_jobroles AS a 
        JOIN sa_ps_service_budgets.sa_mst_resource_catalogs AS b ON a.resource_category_id = b.resource_catalog_id
        WHERE a.project_code = '".$_GET['project_code']."'";
        $selectedPCJobRoles2 = $DBWRKLD->get_sql($sqlLookupJobRoles2);
        $rowSelectedPCJobRoles2 = $selectedPCJobRoles2[0];
        $resSelectedPCJobRoles2 = $selectedPCJobRoles2[1];
        $totalRowPCJobRoles2 = $selectedPCJobRoles2[2];
    }
}
?>

<?php 
    if($_GET['act'] == 'add' && isset($_GET['project_code']) || $_GET['act'] == 'edit'){
?>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active text-body" id="info-tab" data-bs-toggle="tab" data-bs-target="#Assignment" type="button" role="tab" aria-controls="Assignment" aria-selected="true">Assignment</button>
        </li>
    <?php if($_GET['project_code'] != ''){ ?>
        <li class="nav-item" role="presentation">
            <button class="nav-link text-body" id="history-tab" data-bs-toggle="tab" data-bs-target="#History" type="button" role="tab" aria-controls="history" aria-selected="false">History</button>
        </li>
    <?php }?>
    </ul>
<?php        
    }else if($_GET['act'] == 'add'){
?>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active text-body" id="info-tab" data-bs-toggle="tab" data-bs-target="#Assignment" type="button" role="tab" aria-controls="Assignment" aria-selected="true">Assignment</button>
        </li>
    </ul>
<?php
    }
?>


<!-- TAB History -->
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="Assignment" role="tabpanel" aria-labelledby="assignment-tab">
        <div class="card shadow mb-4">
            <div class="card-body">
                <form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="mb-3">Information Project</h3>
                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Code* (WAJIB DIISI)</label>
                            <div class="col-sm-12 mb-2">
                                <?php if ($_GET['act'] == "add") { ?>
                                <?php if (isset($_GET['project_code'])) { ?>
                                <?php if ($_GET['project_code'] == null) { ?>
                                <select id="project_code" class="form-control form-control-sm" name="projectCode" onchange="onChangeFunction()">
                                    <?php
                                        $sqlQuery = "SELECT * FROM sa_trx_project_list WHERE status = 'acknowledge'";
                                        $dataQuery = $DBSB->get_sql($sqlQuery);
                                        $rowData = $dataQuery[0];
                                        $resData = $dataQuery[1];

                                        do{
                                            $projectCode = $rowData['project_code'];                    
                                    ?>
                                    <option></option>
                                    <option value="<?php echo $projectCode; ?>"><?php echo $projectCode ?></option>
                                    <?php }while($rowData = $resData->fetch_assoc());?>
                                </select>
                                <?php } else { ?>
                                <select id="project_code" class="form-control form-control-sm" name="projectCode" onchange="onChangeFunction()">
                                    <option value="<?php echo $_GET['project_code']; ?>"><?php echo $_GET['project_code']; ?></option>
                                    <?php
                                        $sqlQuery = "SELECT * FROM sa_trx_project_list WHERE status = 'acknowledge'";
                                        $dataQuery = $DBSB->get_sql($sqlQuery);
                                        $rowData = $dataQuery[0];
                                        $resData = $dataQuery[1];

                                        do{
                                            $projectCode = $rowData['project_code'];                    
                                    ?>

                                    <option value="<?php echo $projectCode; ?>"><?php echo $projectCode ?></option>
                                    <?php }while($rowData = $resData->fetch_assoc());?>
                                </select>
                                <?php }
				                } else { ?>
                                <select id="project_code" class="form-control form-control-sm" name="projectCode" onchange="onChangeFunction()">
                                <?php
                                    $sqlQuery = "SELECT * FROM sa_trx_project_list WHERE status = 'acknowledge'";
                                    $dataQuery = $DBSB->get_sql($sqlQuery);
                                    $rowData = $dataQuery[0];
                                    $resData = $dataQuery[1];

                                    do{
                                        $projectCode = $rowData['project_code'];                    
                                ?>
                                    <option></option>
                                    <option value="<?php echo $projectCode; ?>"><?php echo $projectCode ?></option>
                                    <?php }while($rowData = $resData->fetch_assoc());?>
                                </select>
                                <?php }  ?>
                                <?php }else if ($_GET['act'] == "edit") { ?>
                                <input type="text" class="form-control form-control-sm" id="project_code"
                                    name="projectCode" value="<?php if ($_GET['act'] == 'edit') {echo $ddata['project_code'];} ?>" required readonly>
                                <?php } ?>
                            </div>

                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">No. SO</label>
                            <div class="col-sm-12 mb-2">
                            <?php if ($_GET['act'] == "add") { ?>
                            <?php if (isset($_GET['project_code'])) { ?>
                            <?php if ($_GET['project_code'] == null) { ?>
                                <select id="noSO" class="form-control form-control-sm" name="noSO" disabled>
                                    <option selected="selected">--Choose SO--</option>
                                </select>
                            <?php }else{
                                if(isset($_GET['no_so'])){ ?>
                                    <select id="noSO" class="form-control form-control-sm" name="noSO" onchange="onChangeSOFunction()" required>
                                    <option disabled>--Choose SO--</option>
                                    <option selected="selected" value = "<?php echo $_GET['no_so']; ?>"><?php echo $_GET['no_so'];;?></option>
                                    <?php 
                                        $sqlCheckSO = "SELECT * FROM sa_trx_project_list WHERE project_code = '".$_GET['project_code']."' and status = 'acknowledge'"; 
                                        $dataSO = $DBSB->get_sql($sqlCheckSO);
                                        $rowDataSO = $dataSO[0];
                                        $resDataSO = $dataSO[1];

                                        do{
                                            $noSO = $rowDataSO['so_number'];
                                        ?>
                                        <option value = "<?php echo $noSO; ?>"><?php echo $noSO;?></option>
                                        <?php }while($rowDataSO = $resDataSO->fetch_assoc());?>
                                    </select>
                            <?php }else{  
                            ?>
                                <select id="noSO" class="form-control form-control-sm" name="noSO" onchange="onChangeSOFunction()" required>
                                <option readonly>--Choose SO--</option>
                                    <?php 
                                        $sqlCheckSO = "SELECT * FROM sa_trx_project_list WHERE project_code = '".$_GET['project_code']."' and status = 'acknowledge'"; 
                                        $dataSO = $DBSB->get_sql($sqlCheckSO);
                                        $rowDataSO = $dataSO[0];
                                        $resDataSO = $dataSO[1];

                                        do{
                                            $noSO = $rowDataSO['so_number'];
                                    ?>
                                    <option value = "<?php echo $noSO; ?>"><?php echo $noSO;?></option>
                                    <?php }while($rowDataSO = $resDataSO->fetch_assoc());?>
                                </select>
                            <?php
                                }
                            }
                                }else{?>
                                <select id="noSO" class="form-control form-control-sm" name="noSO" disabled>
                                    <option value="#">--Choose SO--</option>
                                </select>
                            <?php }?>
                                
                            <?php 
                            }else{?>
                                <select id="noSO" class="form-control form-control-sm" name="noSO" readonly>
                                    <option value="<?php echo $_GET['no_so'];?>"><?php echo $_GET['no_so'];?></option>
                                </select>
                            <?php }?>
                            </div>

                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Customer Name</label>
                            <div class="col-sm-12 mb-2">
                                <?php
                                    if($_GET['act'] == "edit") { ?>
                                    <input type="text" class="form-control form-control-sm" id="customer" name="customerName" value="<?php if ($_GET['act'] == 'edit') {echo $ddata['customer_name'];} ?>" readonly>
                                <?php } else if ($_GET['act'] == "add" && isset($_GET['project_code']) && isset($_GET['no_so'])){ 
                                    $sqlCheckData = "SELECT * FROM sa_trx_project_list WHERE project_code = '".$_GET['project_code']."' AND so_number = '".$_GET['no_so']."' AND status = 'acknowledge'";
                                    $dataCN = $DBSB->get_sql($sqlCheckData);
                                ?>
                                    <input type="text" class="form-control form-control-sm" id="customer" name="customerName" value="<?php echo $dataCN[0]['customer_name'];?>" readonly>
                                <?php }else {?>
                                    <input type="text" class="form-control form-control-sm" id="customer" name="customerName" value="" readonly>
                                <?php } ?>
                            </div>

                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Name</label>
                            <div class="col-sm-12 mb-2">
                                <?php 
                                if ($_GET['act'] == "edit") { ?>
                                        <input type="text" class="form-control form-control-sm" id="projectName" name="projectName" value="<?php if ($_GET['act'] == 'edit') {echo $ddata['project_name'];}?>" readonly>
                                <?php }else if ($_GET['act'] == "add" && isset($_GET['project_code']) && isset($_GET['no_so'])) { 
                                    $sqlCheckDataPN = "SELECT * FROM sa_trx_project_list WHERE project_code = '".$_GET['project_code']."' AND so_number = '".$_GET['no_so']."' AND status = 'acknowledge'";
                                    $dataPN = $DBSB->get_sql($sqlCheckDataPN);
                                ?>
                                        <input type="text" class="form-control form-control-sm" id="projectName" name="projectName" value="<?php echo $dataPN[0]['project_name'];?>" readonly>
                                <?php }else{ ?> 
                                        <input type="text" class="form-control form-control-sm" id="projectName" name="projectName" value="" readonly>
                                <?php }?>
                            </div>
                        </div>
                    </div>

                    <?php 
                    if($_GET['act'] == "add" && isset($_GET['project_code']) && isset($_GET['no_so']) || $_GET['act'] == "edit") {
                    ?>
                    <div class="row mt-3">
                        <?php if($_GET['act'] == "add" && isset($_GET['project_code'])){ ?>
                        <div class="col-md-6">
                            <h3>Resource Assignment</h3>
                            <div class="control-group after-add-more">
                                <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm mt-3">Resource Email* (WAJIB DIISI)</label>
                                <div class="col-sm-10 mb-2">
                                    <select id="email" class="form-control form-control-sm" name="email[]" onchange="searchTest(this.value)">
                                        <option value="#" selected="selected" disabled>--Choose Resource--</option>
                                        <?php
                                            $sqlHCM = "SELECT * FROM sa_view_employees WHERE job_structure LIKE 'JG%'";
                                            $dataEmployee = $DBHCM->get_sql($sqlHCM);
                                            $rowDataHcm = $dataEmployee[0];
                                            $resDataHcm = $dataEmployee[1];

                                            do{
                                                $resourceEmail = $rowDataHcm['employee_email'];         
                                                $resourceName = $rowDataHcm['employee_name'];   
                        
                                                if(substr($resourceName, -1) == " "){
                                                    $resourceName = substr($resourceName, 0, -1);
                                                }
                                            ?>
                                        <option value="<?php echo "$resourceName<$resourceEmail>"; ?>"><?php echo $resourceName ." - ". $resourceEmail ?></option>
                                        <?php }while($rowDataHcm = $resDataHcm->fetch_assoc());?>
                                    </select>
                                    <div class="button" id="buttonSearch"></div>
                                </div>
                                <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm ">Roles on Project* (WAJIB DIISI)</label>
                                <div class="col-sm-10 mb-2">
                                    <select class="form-control form-control-sm" name="roles[]" id="roles" required>
                                        <?php if(isset($_GET['project_code'])){?>
                                        <option value="#" selected="selected" disabled>--Choose Roles--</option>
                                        <?php
                                            do{
                                                $resourceCategory = $rowSelectedPCJobRoles['resource_qualification'];
                                                $resourceBrand = $rowSelectedPCJobRoles['brand']
                                        ?>

                                        <option value="<?php echo $resourceCategory . " - " . $resourceBrand; ?>"><?php echo $resourceCategory . " " . $resourceBrand?></option>
                                        <?php }while($rowSelectedPCJobRoles = $resSelectedPCJobRoles->fetch_assoc());
                                        }?>
                                    </select>
                                </div>
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status</label>
                                <div class="col-sm-10 mb-2">
                                    <select class="form-control form-control-sm" name="status[]" id="status"
                                        onchange="onChangeStatusFunction();" required>
                                        <option value="#" selected="selected" disabled>--Choose Status--</option>
                                        <option value="Active">Active</option>
                                        <option value="Joined">Joined</option>
                                    </select>
                                </div>
                                <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Start Progress (in Percent %)</label>
                                <div class="col-md-5 mb-2">
                                    <input type="number" name="startProgress[]" id="startProgress" class="form-control form-control-sm" style="display: inline-block;" required>
                                </div>
                                <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">End Progress (in Percent %)</label>
                                <div class="col-md-5 mb-2">
                                    <input type="number" name="endProgress[]" id="endProgress" class="form-control form-control-sm" style="display: inline-block;" required>
                                </div>
                                <div class="col-sm-10 mb-2">
                                    <label for="exampleFormControlTextarea1">Description (optional)</label>
                                    <textarea class="form-control" id="exampleFormControlTextarea1" name="description[]" rows="3"></textarea>
                                </div>
                                <div class="col-sm-10 mb-2">
                                    <button class="btn btn-success add-more" type="button"><i class="glyphicon glyphicon-plus"></i>Add</button>
                                </div>
                            </div>
                        </div>
                        <?php }else if($_GET['act'] == "edit"){?>
                        <div class="col-md-6">
                            <h3>Resource Assignment</h3>
                            <div class="control-group after-add-more">
                                <div class="col-sm-10 mb-2">
                                    <input type="text" name="id" id="id" class="form-control form-control-sm" value="<?php if ($_GET['act'] == 'edit') {
																														    echo $ddata['id'];} ?>" hidden>
                                </div>
                                <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm mt-3">Resource Email* (WAJIB DIISI)</label>
                                <div class="col-sm-10 mb-2">
                                    <!-- <input type="text" name="tampung" value="<?php if ($_GET['act'] == 'edit') {$currentEmail = explode("<", $ddata['resource_email']); 
                                        $currentEmail1 = str_replace(['>', ' '], '', $currentEmail[1]); echo $currentEmail1;} ?>" hidden> -->
                                    <select id="emailEdit" class="form-control form-control-sm" name="email">
                                        <option value="<?php if ($_GET['act'] == 'edit') { echo $ddata['resource_email'];} ?>" selected="selected" readonly>
                                            <?php if ($_GET['act'] == 'edit') {echo $ddata['resource_email'] . " - ". $currentEmail1;} ?>
                                        </option>
                                        <?php
                                            $sqlHCM = "SELECT * FROM sa_view_employees WHERE job_structure LIKE 'JG%'";
                                            $dataEmployee = $DBHCM->get_sql($sqlHCM);
                                            $rowDataHcm = $dataEmployee[0];
                                            $resDataHcm = $dataEmployee[1];

                                            do{
                                                $resourceEmail = $rowDataHcm['employee_email'];         
                                                $resourceName = $rowDataHcm['employee_name'];              

                                                if(substr($resourceName, -1) == " "){
                                                    $resourceName = substr($resourceName, 0, -1);
                                                }
                                        ?>
                                        <option value="<?php echo "$resourceName<$resourceEmail>"; ?>"><?php echo $resourceName ." - ". $resourceEmail ?></option>
                                        <?php }while($rowDataHcm = $resDataHcm->fetch_assoc());?>
                                    </select>
                                </div>
                                <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm ">Roles on Project* (WAJIB DIISI)</label>
                                <div class="col-sm-10 mb-2">
                                    <select class="form-control form-control-sm" name="roles" id="roles" required> 
                                        <?php if(isset($_GET['project_code'])){?>
                                        <option id="testing" value="<?php if ($_GET['act'] == 'edit') {echo $ddata['roles'] . " - "; } ?>">
                                        <?php if ($_GET['act'] == 'edit') {echo $ddata['roles'];}?></option>
                                        <?php
                                        do{
                                            $resourceCategory = $rowSelectedPCJobRoles['resource_qualification'];
                                            $resourceBrand = $rowSelectedPCJobRoles['brand']
                                        ?>
                                        <option value="<?php echo $resourceCategory . " - " . $resourceBrand;?>"><?php echo $resourceCategory . " " . $resourceBrand?></option>
                                        <?php }while($rowSelectedPCJobRoles = $resSelectedPCJobRoles->fetch_assoc());
                                        }?>
                                    </select>
                                </div>
                                <script>
                                    var email = $("#testing").val();
                                    alert(email);
                                </script>
                                <?php if($_GET['act']=='edit') {?>
                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status</label>
                                <div class="col-sm-10 mb-2">
                                    <select class="form-control form-control-sm" name="status" id="statusEdit" onchange="onChangeStatusEditFunction();" required>
                                        <option value="<?php if ($_GET['act'] == 'edit') {$explodeStatus = explode(" ",$ddata['status']); echo $explodeStatus[0]; }?>" readonly>
                                        <?php if ($_GET['act'] == 'edit') {echo $explodeStatus[0];}?></option>
                                        <option value="Active">Active</option>
                                        <option value="Terminated">Terminated</option>
                                        <option value="Joined">Joined</option>
                                    </select>
                                </div>
                                <?php } ?>
                                <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Start Progress (in Percent %)</label>
                                <div class="col-md-5 mb-2">
                                    <input type="number" name="startProgress" id="startProgressEdit"
                                        class="form-control form-control-sm" style="display: inline-block;"
                                        value="<?php if ($_GET['act'] == 'edit') {$explodeProgress = explode("%", $ddata['progress']); echo $explodeProgress[0]; }?>"
                                        required>
                                </div>
                                <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">End Progress (in Percent %)</label>
                                <div class="col-md-5 mb-2">
                                    <input type="number" name="endProgress" id="endProgressEdit"
                                        class="form-control form-control-sm" style="display: inline-block;"
                                        value="<?php if ($_GET['act'] == 'edit') {$explodeProgress = explode(" - ", $ddata['progress']); $explodeEnd = explode("%", $explodeProgress[1]); echo $explodeEnd[0]; }?>"
                                        required>
                                </div>
                                <div class="col-sm-10 mb-2">
                                    <label for="exampleFormControlTextarea1">Description (optional)</label>
                                    <textarea class="form-control" id="exampleFormControlTextarea1" name="description"
                                        rows="3" value=""><?php if ($_GET['act'] == 'edit') {echo $ddata['description'];}?></textarea>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="col-md-6">
                            <?php
                            if($_GET['act'] == 'add' && isset($_GET['project_code'])){
                            ?>
                            <h3 class="mb-4">Daftar resource pada <?php echo $_GET['project_code'] ?></h3>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Project Code</th>
                                        <th scope="col">Resource Email</th>
                                        <th scope="col">Roles</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Created In</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $project_code  = $_GET['project_code'];
                                    $sqlLookupDataPC = "SELECT * FROM sa_resource_assignment WHERE project_code = '$project_code'";
                                    $dataPC = $DTSB->get_sql($sqlLookupDataPC);
                                    $rowDataPC = $dataPC[0];
                                    $resDataPC = $dataPC[1];
                                    $i = 1;

                                    if($rowDataPC != "" || $rowDataPC != null){
                                        do{
                                            $projectCodeTable = $rowDataPC['project_code'];
                                            $resourceEmailTable = $rowDataPC['resource_email'];
                                            $rolesTable = $rowDataPC['roles'];
                                            $statusTable = $rowDataPC['status'];
                                            $startProgress = $rowDataPC['start_progress'];
                                            $endProgress = $rowDataPC['end_progress'];
                                            $timestamp = $rowDataPC['created_in_msizone'];
                                            $explodeRolesTable = explode(' - ', $rolesTable);

                                            if($explodeRolesTable[1] == ""){
                                                $rolesTable = $explodeRolesTable[0];
                                            }
                                    ?>
                                    <tr>
                                        <th scope="row"><?php echo $i; ?></th>
                                        <td><?php echo $projectCodeTable; ?></td>
                                        <td><?php echo $resourceEmailTable ?></td>
                                        <td><?php echo $rolesTable; ?></td>
                                        <td><?php echo $statusTable . " $startProgress% - $endProgress%"; ?></td>
                                        <td><?php echo $timestamp; ?></td>
                                    </tr>
                                    <?php $i++;
                                        }while($rowDataPC = $resDataPC->fetch_assoc()); 
                                    }?>
                                </tbody>
                            </table>
                            <?php } else if($_GET['act'] == 'edit'){ ?>
                            <h3 class="mb-4">Daftar resource pada
                                <?php if ($_GET['act'] == 'edit') {echo $ddata['project_code'];} ?>
                            </h3>
                            <?php 
                                $sqlCheckTable = "SELECT * FROM sa_resource_assignment WHERE project_code = '".$ddata['project_code']."'";
                                $dataCheckTable = $DTSB->get_sql($sqlCheckTable);
                                $rowCheckTable = $dataCheckTable[0];
                                $resCheckTable = $dataCheckTable[1];
                                $totalRowCheckTable = $dataCheckTable[2];
                                $i = 1;
                            ?>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Project Code</th>
                                        <th scope="col">Resource Email</th>
                                        <th scope="col">Roles</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Created In</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        do{
                                            $id = $rowCheckTable['id'];
                                            $projectCode = $rowCheckTable['project_code'];
                                            $resourceEmail = $rowCheckTable['resource_email'];
                                            $roles = $rowCheckTable['roles'];
                                            $status = $rowCheckTable['status'];
                                            $startProgress = $rowCheckTable['start_progress'];
                                            $endProgress = $rowCheckTable['end_progress'];
                                            $timestamp = $rowCheckTable['created_in_msizone'];
                                            $explodeRolesTable = explode(' - ', $roles);
                                            if($explodeRolesTable[1] == ""){
                                                $roles = $explodeRolesTable[0];
                                            }
                                        ?>
                                    <tr>
                                        <th scope="row"><?php echo $i ?></th>
                                        <td><?php echo $projectCode ?></td>
                                        <td><?php echo $resourceEmail ?></td>
                                        <td><?php echo $roles ?></td>
                                        <td><?php echo $status . " $startProgress% - $endProgress%";  ?></td>
                                        <td><?php echo $timestamp ?></td>
                                        <?php $i++;
                                            }while($rowCheckTable = $resCheckTable->fetch_assoc());?>
                                </tbody>
                            </table>
                            <?php }else{?>
                            <h3>Data resource akan muncul jika KP telah diisi</h3>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Project Code</th>
                                        <th scope="col">Resource Email</th>
                                        <th scope="col">Roles</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">1</th>
                                        <td colspan="4">Data akan muncul setelah input Project Code*</td>
                                </tbody>
                            </table>
                            <?php } ?>

                        </div>
                    </div>
                    <?php
                    }
                    ?>
                    <hr />
                    <input type="submit" class="btn btn-secondary mt-4" name="cancel" value="Cancel">
                    <?php if(isset($_GET['act']) && $_GET['act']=='edit') { ?>
                    <input type="submit" class="btn btn-primary mt-4" name="save" value="Save">
                    <?php } else if(isset($_GET['act']) && $_GET['act']=='add') { ?>
                    <input type="submit" class="btn btn-primary mt-4" name="add" value="Save">
                    <?php } ?>

                    
                </form>

                <?php if($_GET['act'] == "add") {?>
                <!--Hidden Add-->
                <div class="copy invisible">
                    <div class="control-group">
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm mt-3">Resource Email*</label>
                        <div class="col-sm-10 mb-2">
                            <select id="emailCopyCopy" class="form-control form-control-sm emailCopy " name="email[]" onchange="searchTestCopy(this.value)">
                                <option value="#" selected="selected" disabled>--Choose Resource--</option>
                                <?php
                                    $sqlHCM2 = "SELECT * FROM sa_view_employees WHERE job_structure LIKE 'JG%'";
                                    $dataEmployee2 = $DBHCM->get_sql($sqlHCM2);
                                    $rowDataHcm2 = $dataEmployee2[0];
                                    $resDataHcm2 = $dataEmployee2[1];

                                    do{
                                        $resourceEmail2 = $rowDataHcm2['employee_email'];         
                                        $resourceName2 = $rowDataHcm2['employee_name'];     
                        
                                        if(substr($resourceName2, -1) == " "){
                                            $resourceName2 = substr($resourceName2, 0, -1);
                                        }
                                    ?>
                                <option value="<?php echo "$resourceName2<$resourceEmail2>"; ?>">
                                    <?php echo $resourceName2 ." - ". $resourceEmail2 ?>
                                </option>
                                <?php }while($rowDataHcm2 = $resDataHcm2->fetch_assoc());?>
                            </select>
                            <div class="button" id="buttonSearchCopyCopy"></div>
                        </div>
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm ">Roles on Project</label>
                        <div class="col-sm-10 mb-2">
                            <select class="form-control form-control-sm" name="roles[]">
                                <?php if(isset($_GET['project_code'])){?>
                                <option value="" selected="selected" disabled>--Choose Roles--</option>
                                <?php
                                do{
                                    $resourceCategory2 = $rowSelectedPCJobRoles2['resource_qualification'];
                                    $resourceBrand2 = $rowSelectedPCJobRoles2['brand']
                                ?>
                                <option value="<?php echo $resourceCategory2 . " - " . $resourceBrand2?>">
                                    <?php echo $resourceCategory2 . " " . $resourceBrand2?></option>
                                <?php }while($rowSelectedPCJobRoles2 = $resSelectedPCJobRoles2->fetch_assoc());
                                }?>
                            </select>
                            
                        </div>
                        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status</label>
                        <div class="col-sm-10 mb-2">
                            <select class="form-control form-control-sm" name="status[]" id="statusCopy"
                                onchange="onChangeStatusCopyFunction();" required>
                                <option value="#" selected="selected" disabled>--Choose Status--</option>
                                <option value="Active">Active</option>
                                <option value="Joined">Joined</option>
                            </select>
                            
                        </div>
                        <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Start Progress (in Percent %)</label>
                        <div class="col-md-5 mb-2">
                            <input type="number" name="startProgress[]" id="startProgressCopy"
                                class="form-control form-control-sm" style="display: inline-block;" required>
                        </div>
                        <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">End Progress (in Percent %)</label>
                        <div class="col-md-5 mb-2">
                            <input type="number" name="endProgress[]" id="endProgressCopy"
                                class="form-control form-control-sm" style="display: inline-block;" required>
                        </div>
                        <div class="col-sm-10 mb-2">
                            <label for="exampleFormControlTextarea1">Description</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" name="description[]"
                                rows="3"></textarea>
                        </div>
                        <div class="col-sm-10 mb-2">
                            <button class="btn btn-danger remove" type="button"><i class="glyphicon glyphicon-remove"></i>Remove</button>
                        </div>
                    </div>
                </div>
                <?php } ?>  

                
                <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
                

                <script>
                    $("#project_code").on('change', function () {
                        var project_code = $(this).val();
                        var customer_name = $('#customer').val();
                        window.location = window.location.pathname + "?mod=resource_assignment" + "&act=<?php echo $_GET['act']; ?>" + "&project_code=" + project_code;
                    })

                    function onChangeFunction() {
                        var project_code = document.getElementById("project_code").value;
                        window.location = window.location.pathname + "?mod=resource_assignment" + "&act=<?php echo $_GET['act']; ?>" + "&project_code=" + project_code;
                    }

                    function onChangeSOFunction() {
                        var project_code = document.getElementById("project_code").value;
                        var no_so = document.getElementById("noSO").value;
                        window.location = window.location.pathname + "?mod=resource_assignment" + "&act=<?php echo $_GET['act']; ?>" + "&project_code=" + project_code +"&no_so=" + no_so;
                    }

                    function searchTest(str) {
                        // var email = document.getElementById('email').value;
                        // console.log(str);
                        var xhttp;    
                        if (str == "") {
                            document.getElementById("buttonSearch").innerHTML = "";
                            return;
                        }
                        const myStr = str.split("<");
                        const myFinalStr = myStr[1].split(">");
                        console.log(myFinalStr[0]);
                        xhttp = new XMLHttpRequest();
                        xhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById("buttonSearch").innerHTML = this.responseText;
                            }
                        };
                        xhttp.open("GET", "components/modules/resource_assignment/ajax.php?resource="+myStr[1], true);
                        xhttp.send();
                    }

                    function searchTestCopy(strCopy) {
                        // var testing = document.getElementById('emailCopyCopy').value;
                        // console.log(testing);
                        var xhttp;    
                        if (strCopy == "") {
                            document.getElementById("buttonSearchCopyCopy").innerHTML = "";
                            return;
                        }
                        const myStrCopy = strCopy.split("<");
                        const myFinalStrCopy = myStrCopy[1].split(">");
                        console.log(myFinalStrCopy[0]);
                        xhttp = new XMLHttpRequest();
                        xhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById("buttonSearchCopyCopy").innerHTML = this.responseText;
                            }
                        };
                        xhttp.open("GET", "components/modules/resource_assignment/ajax_copy.php?resource="+myStrCopy[1], true);
                        xhttp.send();
                    }


                    function onChangeStatusFunction() {
                        var e = document.getElementById("status").value;

                        if (e == 'Active') {
                            document.getElementById("startProgress").value = 0;
                            document.getElementById("endProgress").value = 100;
                        } else if (e == 'Joined') {
                            document.getElementById("startProgress").value = "";
                            document.getElementById("endProgress").value = 100;
                        } else {
                            document.getElementById("startProgress").value = "";
                            document.getElementById("endProgress").value = "";
                        }
                    }
                </script>

                <script type="text/javascript">
                    $(document).ready(function () {
                        $('#project_code').select2({
                            placeholder: 'Pilih Project Code',
                        allowClear: true
                        });
                    });

                    $(document).ready(function () {
                        $('#email').select2({
                            placeholder: 'Pilih Resource',
                            allowClear: true
                        });
                    });

                    $(document).ready(function () {
                        $('#emailEdit').select2({
                            placeholder: 'Pilih Resource',
                            allowClear: true
                        });
                    });
                </script>

                <script type="text/javascript">
                    $(document).ready(function () {
                        $(".add-more").click(function () {
                            var html = $(".copy").html();
                            $(".after-add-more").after(html);

                            $(document).ready(function () {
                                $('#emailCopyCopy').select2({
                                    placeholder: 'Pilih Resource Next',
                                    allowClear: true,
                                });
                            });
                        });

                        // saat tombol remove dklik control group akan dihapus 
                        $("body").on("click", ".remove", function () {
                            $(this).parents(".control-group").remove();
                        });
                    });

                    function onChangeStatusCopyFunction() {
                            var eCopy = document.getElementById("statusCopy").value;
            
                            if (eCopy == 'Active') {
                                document.getElementById("startProgressCopy").value = 0;
                                document.getElementById("endProgressCopy").value = 100;
                            } else if (eCopy == 'Joined') {
                                document.getElementById("startProgressCopy").value = "";
                                document.getElementById("endProgressCopy").value = 100;
                            } else {
                                document.getElementById("startProgressCopy").value = "";
                                document.getElementById("endProgressCopy").value = "";
                            }
                        }

                        function onChangeStatusEditFunction() {
                            var eCopy = document.getElementById("statusEdit").value;
            
                            if (eCopy == 'Active') {
                                document.getElementById("startProgressEdit").value = 0;
                                document.getElementById("endProgressEdit").value = 100;
                            } else if (eCopy == 'Joined') {
                                document.getElementById("startProgressEdit").value = "";
                                document.getElementById("endProgressEdit").value = 100;
                            } else {
                                document.getElementById("startProgressEdit").value = "";
                                document.getElementById("endProgressEdit").value = "";
                            }
                        }

                    function MyFunction() {
                        var value = document.getElementById("resourceEmailEdit").textContent;
                        var value2 = document.getElementById("resourceRolesEdit").textContent;
                        var e = document.getElementById("roles");

                        document.getElementById("email").value = value;
                        document.getElementById("email").options[e.selectedIndex].value2 = value2;
                    }
                </script>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="History" role="tabpanel" aria-labelledby="history-tab">
    <div class="card shadow mb-4">
        <div class="card-body">
            <?php
                $maxRows = 10;

                if(isset($_GET['maxRows'])) {
                    $maxRows = $_GET['maxRows'];
                }
                
                $tbl_resource_logs = "resource_logs";
                $condition = "project_code = '" . $_GET['project_code'] ."' ORDER BY entry_date DESC";
                $dataLogResource = $DTSB->get_data($tbl_resource_logs, $condition, "", 0, $maxRows);
                if($dataLogResource[2]>0) {
            ?>

            <h5>History</h5>
            <table class="table">
                <thead class="bg-light">
                    <th>Date</th>
                    <th>Time</th>
                    <th>Description</th>
                </thead>
                </thead>
                <tbody>
                <?php 
                    $tgl = ""; 
                ?>
                <?php do { ?>
                    <tr>
                        <td style="font-size: 12px">
                        <?php if($tgl != date("Y-m-d", strtotime($dataLogResource[0]['entry_date']))) 
                        { ?>
                            <table class="table table-sm table-light table-striped">
                                <tr>
                                    <td class="text-center fw-bold" colspan="2">
                                        <?php echo date("Y", strtotime($dataLogResource[0]['entry_date'])); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-center"><?php echo date("M", strtotime($dataLogResource[0]['entry_date'])); ?>
                                    </td>
                                    <td class="text-center"><?php echo date("d", strtotime($dataLogResource[0]['entry_date'])); ?>
                                    </td>
                                </tr>
                            </table>

                        <?php 
                        } ?>
                        </td>
                        <td style="font-size: 12px"><?php echo date("H:i:s", strtotime($dataLogResource[0]['entry_date'])); ?></td>
                        <td style="font-size: 12px">
                            <?php 
                            $description = str_replace(" -", "",$dataLogResource[0]['description']);
                            echo $description . "<br/>Performed by " . $dataLogResource[0]['entry_by']; ?></td>
                    </tr>
                    <?php $tgl = date("Y-m-d", strtotime($dataLogResource[0]['entry_date'])); ?>
                    <?php } while($dataLogResource[0]=$dataLogResource[1]->fetch_assoc()); ?>
                </tbody>
            </table>
            <?php } ?>
            <!-- <div class="" style="font-size: 12px">Readmore...</div> -->
        </div>
    </div>
</div>
</div>







