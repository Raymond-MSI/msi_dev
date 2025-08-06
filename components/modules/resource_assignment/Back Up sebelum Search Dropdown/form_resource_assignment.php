<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
<?php
global $DTSB;

include_once("components/modules/resource_assignment/func_resource_assignment.php");


$db_sb = "SERVICE_BUDGET";
$DBSB = get_conn($db_sb);

$db_wrkld = "WORKLOAD";
$DBWRKLD = get_conn($db_wrkld);

    if($_GET['act']=='edit') {
        global $DTSB;
        $condition = "id=" . $_GET['id'];
        $data = $DTSB->get_data($tblname, $condition);
        $ddata = $data[0];
        $qdata = $data[1];
        $tdata = $data[2];
    }

    if ($_GET['act'] == "add" || $_GET['act'] == "edit") {
        if (isset($_GET['project_code'])) {
            $sqlLookupSelectedPC = "SELECT * FROM sa_trx_project_list WHERE project_code = '".$_GET['project_code']."'";
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

            echo date('Y/m/d H:i:s');
        }   
    }
    ?>
<form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-12">
            <h3 class="mb-3">Information Project</h3>
            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Code* (WAJIB DIISI)</label>
            <div class="col-sm-12 mb-2">
                <?php if ($_GET['act'] == "add") { ?>
                <?php if (isset($_GET['project_code'])) { ?>
                <?php if ($_GET['project_code'] == null) { ?>
                <input type="text" class="form-control form-control-sm" id="project_code" name="projectCode"
                    value="<?php if (isset($GET['project_code'])){echo $_GET['project_code'];}?>" required>
                <?php } else { ?>
                <input type="text" class="form-control form-control-sm" id="project_code" name="projectCode"
                    value="<?php echo $_GET['project_code']; ?>" required>
                <?php }
				    } else { ?>
                    
                <input type="text" class="form-control form-control-sm" id="project_code" name="projectCode" value="<?php if (isset($GET['project_code'])) {
																														    echo $_GET['project_code'];} ?>" required>
                <?php }  ?>
                <?php }else if ($_GET['act'] == "edit") { ?>
                <input type="text" class="form-control form-control-sm" id="project_code" name="projectCode" value="<?php if ($_GET['act'] == 'edit') {
																														    echo $ddata['project_code'];} ?>" required readonly>
                <?php } ?>
            </div>

            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Customer Name</label>
            <div class="col-sm-12 mb-2">
                <?php if ($_GET['act'] == "add")  { ?>
                <?php $customerName = isset($selectedPC[0]['customer_name']);
					if ($customerName == null) { ?>
                <input type="text" class="form-control form-control-sm" id="customer" name="customerName" value=""
                    readonly>
                <?php } else { ?>
                <input type="text" class="form-control form-control-sm" id="customer" name="customerName"
                    value="<?php echo $selectedPC[0]['customer_name'];?>" readonly>
                <?php } ?>
                <?php }else if ($_GET['act'] == "edit") { ?>
                <input type="text" class="form-control form-control-sm" id="customer" name="customerName"
                    value="<?php if ($_GET['act'] == 'edit') {echo $ddata['customer_name'];} ?>" readonly>
                <?php } ?>
            </div>

            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Name</label>
            <div class="col-sm-12 mb-2">
                <?php if ($_GET['act'] == "add") { ?>
                <?php $projectName = isset($selectedPC[0]['project_name']);
					if ($projectName == null) { ?>
                <input type="text" class="form-control form-control-sm" id="projectName" name="projectName" value=""
                    readonly>
                <?php } else { ?>
                <input type="text" class="form-control form-control-sm" id="projectName" name="projectName"
                    value="<?php echo $selectedPC[0]['project_name'];?>" readonly>
                <?php } ?>
                <?php }else if ($_GET['act'] == "edit") { ?>
                <input type="text" class="form-control form-control-sm" id="projectName" name="projectName"
                    value="<?php if ($_GET['act'] == 'edit') {echo $ddata['project_name'];}?>" readonly>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php 
        if($_GET['act'] == "add" && isset($_GET['project_code']) && $selectedPC[0] != NULL || $_GET['act'] == "edit") {
            
    ?>
    <div class="row mt-3">
        <?php if($_GET['act'] == "add" && isset($_GET['project_code'])){ ?>
        <div class="col-md-6">
            <h3>Resource Assignment</h3>
            <div class="control-group after-add-more">
                <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm mt-3">Resource Email* (WAJIB
                    DIISI)</label>
                <div class="col-sm-10 mb-2">
                    <input type="email" name="email[]" id="email" class="form-control form-control-sm" required>
                </div>
                <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm ">Roles on Project* (WAJIB
                    DIISI)</label>
                <div class="col-sm-10 mb-2">
                    <select class="form-control form-control-sm" name="roles[]" id="roles" required>
                        <?php if(isset($_GET['project_code'])){?>
                        <option value="#" selected="selected" disabled>--Choose Roles--</option>
                        <?php
                                do{
                                    $resourceCategory = $rowSelectedPCJobRoles['resource_qualification'];
                                    $resourceBrand = $rowSelectedPCJobRoles['brand']
                                ?>

                        <option value="<?php echo $resourceCategory . " - " . $resourceBrand; ?>">
                            <?php echo $resourceCategory . " " . $resourceBrand?></option>
                        <?php }while($rowSelectedPCJobRoles = $resSelectedPCJobRoles->fetch_assoc());
                            }?>
                    </select>
                </div>

                <?php if($_GET['act']=='edit') {?>
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status</label>
                <div class="col-sm-10 mb-2">
                    <select class="form-control form-control-sm" name="jurusan[]">
                        <option value="1">Active</option>
                        <option value="0">Unactive</option>
                    </select>
                </div>
                <?php } ?>
                <div class="col-sm-10 mb-2">
                    <label for="exampleFormControlTextarea1">Description (optional)</label>
                    <textarea class="form-control" id="exampleFormControlTextarea1" name="description[]"
                        rows="3"></textarea>
                </div>
                <div class="col-sm-10 mb-2">
                    <button class="btn btn-success add-more" type="button">
                        <i class="glyphicon glyphicon-plus"></i> Add
                    </button>
                </div>

            </div>


        </div>
        <?php }else if($_GET['act'] == "edit"){
        ?>
        <div class="col-md-6">
            <h3>Resource Assignment</h3>
            <div class="control-group after-add-more">
                <div class="col-sm-10 mb-2">
                    <input type="text" name="id" id="id" class="form-control form-control-sm" value="<?php if ($_GET['act'] == 'edit') {
																														    echo $ddata['id'];} ?>" hidden>
                </div>
                <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm mt-3">Resource Email* (WAJIB
                    DIISI)</label>
                <div class="col-sm-10 mb-2">
                    <input type="email" name="email" id="email" class="form-control form-control-sm" value="<?php if ($_GET['act'] == 'edit') {
																														    echo $ddata['resource_email'];} ?>" required>
                </div>
                <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm ">Roles on Project* (WAJIB
                    DIISI)</label>
                <div class="col-sm-10 mb-2">
                    <select class="form-control form-control-sm" name="roles" id="roles" required>
                        <?php if(isset($_GET['project_code'])){?>
                        <option value="<?php if ($_GET['act'] == 'edit') {
												echo $ddata['roles'] . " - "; } ?>"><?php if ($_GET['act'] == 'edit') {
                                                    echo $ddata['roles'];}?></option>
                        <?php
                                do{
                                    $resourceCategory = $rowSelectedPCJobRoles['resource_qualification'];
                                    $resourceBrand = $rowSelectedPCJobRoles['brand']
                                ?>
                        <option value="<?php echo $resourceCategory . " - " . $resourceBrand;?>">
                            <?php echo $resourceCategory . " " . $resourceBrand?></option>
                        <?php }while($rowSelectedPCJobRoles = $resSelectedPCJobRoles->fetch_assoc());
                            }?>
                    </select>
                </div>
                <?php if($_GET['act']=='edit') {?>
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status</label>
                <div class="col-sm-10 mb-2">
                    <select class="form-control form-control-sm" name="status" required>
                        <option value="<?php if ($_GET['act'] == 'edit') {
                            echo $ddata['status']; }?>" readonly><?php if ($_GET['act'] == 'edit') {
                                echo $ddata['status'];}  ?></option>
                        <option value="Active">Active</option>
                        <option value="Terminated">Terminated</option>
                        <option value="Joined">Joined</option>
                    </select>
                </div>
                <?php } ?>
                <div class="col-sm-10 mb-2">
                    <label for="exampleFormControlTextarea1">Description (optional)</label>
                    <textarea class="form-control" id="exampleFormControlTextarea1" name="description" rows="3"
                        value=""><?php if ($_GET['act'] == 'edit') {
                            echo $ddata['description']; }?></textarea>
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
                        <td><?php echo $statusTable; ?></td>
                        <td><?php echo $timestamp; ?></td>
                    </tr>
                    <?php $i++;
                        }while($rowDataPC = $resDataPC->fetch_assoc()); 
                    }     ?>

                </tbody>
            </table>
            <?php } else if($_GET['act'] == 'edit'){ ?>
            <h3 class="mb-4">Daftar resource pada <?php if ($_GET['act'] == 'edit') {echo $ddata['project_code'];} ?>
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
                        <td><?php echo $status ?></td>
                        <td><?php echo $timestamp ?></td>
                        <?php $i++;
                    }while($rowCheckTable = $resCheckTable->fetch_assoc());?>
                </tbody>
            </table>

            <?php }else{
            ?>
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
        }else {
    ?>

    <?php
        }
    ?>


    <!-- <div class="col-lg-6">
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Id</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="id" name="id"
                        value="<?php if($_GET['act']=='edit') { echo $ddata['id']; } ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Code</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="project_code" name="project_code"
                        value="<?php if($_GET['act']=='edit') { echo $ddata['project_code']; } ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Roles</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="roles" name="roles"
                        value="<?php if($_GET['act']=='edit') { echo $ddata['roles']; } ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Resource Email</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="resource_email" name="resource_email"
                        value="<?php if($_GET['act']=='edit') { echo $ddata['resource_email']; } ?>">
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="row mb-3">
                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" id="status" name="status"
                        value="<?php if($_GET['act']=='edit') { echo $ddata['status']; } ?>">
                </div>
            </div>
        </div> -->
    <hr />
    <input type="submit" class="btn btn-secondary mt-4" name="cancel" value="Cancel">
    <?php if(isset($_GET['act']) && $_GET['act']=='edit') { ?>
    <input type="submit" class="btn btn-primary mt-4" name="save" value="Save">
    <?php } elseif(isset($_GET['act']) && $_GET['act']=='add') { ?>
    <input type="submit" class="btn btn-primary mt-4" name="add" value="Save">
    <?php } ?>
</form>

<?php if($_GET['act'] == "add") {
    ?>
<div class="copy invisible">
    <div class="control-group">
        <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm mt-3">Resource Email</label>
        <div class="col-sm-10 mb-2">
            <input type="text" name="email[]" class="form-control form-control-sm">
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
        <div class="col-sm-10 mb-2">
            <label for="exampleFormControlTextarea1">Description</label>
            <textarea class="form-control" id="exampleFormControlTextarea1" name="description[]" rows="3"></textarea>
        </div>
        <div class="col-sm-10 mb-2">
            <button class="btn btn-danger remove" type="button"><i class="glyphicon glyphicon-remove"></i>
                Remove</button>
        </div>
    </div>
</div>
<?php } ?>

<script>
    $("#project_code").on('change', function () {
        var project_code = $(this).val();
        var customer_name = $('#customer').val();
        window.location = window.location.pathname + "?mod=resource_assignment" +
            "&act=<?php echo $_GET['act']; ?>" + "&project_code=" + project_code;
    })
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $(".add-more").click(function () {
            var html = $(".copy").html();
            $(".after-add-more").after(html);
        });

        // saat tombol remove dklik control group akan dihapus 
        $("body").on("click", ".remove", function () {
            $(this).parents(".control-group").remove();
        });
    });

    function MyFunction() {
        var value = document.getElementById("resourceEmailEdit").textContent;
        var value2 = document.getElementById("resourceRolesEdit").textContent;

        var e = document.getElementById("roles");

        document.getElementById("email").value = value;
        document.getElementById("email").options[e.selectedIndex].value2 = value2;
    }
</script>
<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
    crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#project_code').select2({
            placeholder: 'Pilih Project Code',
            allowClear: true
        });
    });
</script>