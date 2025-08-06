<script src="components/modules/service_budget/java_service_budget.js"></script>

<?php
global $DTSB;

$tblname = "trx_project_list";
if(!isset($_GET['project_code'])) {
    $condition = "project_id=" . $_GET['id'];
} else {
    $condition = "project_code='" . $_GET['project_code'] . "'";
}
$order = "project_id DESC";
$sb = $DTSB->get_data($tblname, $condition, $order);
$dsb = $sb[0];

if($sb[2] == 0) {
    global $username, $password, $hostname;
    $condition = "`project_code`='" . $_GET['project_code'] . "'";
    $databaseNav = "sa_md_navision";
    $tblname = "mst_projects";
    $DTNAV = new Databases($hostname, $username, $password, $databaseNav);
    $sb = $DTNAV->get_data($tblname, $condition);
    $dsb = $sb[0];
    $ver = 0;
} else {
    $ver = $dsb['version'];
}
$tsb = $sb[2];
?>
<form name="form" method="post" action="index.php?mod=service_budget"> 
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <?php echo 'Version ' . $ver; ?>
            </div>

                <div class="card-body">
                <?php 
                if($tsb > 0) {
                ?>

                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#ProjectInformation" type="button" role="tab" aria-controls="projectinformation" aria-selected="true">Project Information</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#ProjectSolution" type="button" role="tab" aria-controls="projectsolution" aria-selected="false">Project Solution</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#Implementation" type="button" role="tab" aria-controls="implementation" aria-selected="false">Implementation</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#Maintenance" type="button" role="tab" aria-controls="maintenance" aria-selected="false">Maintenance</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="warranty-tab" data-bs-toggle="tab" data-bs-target="#ExtendedWarranty" type="button" role="tab" aria-controls="warranty" aria-selected="false">Extended Warranty</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#History" type="button" role="tab" aria-controls="history" aria-selected="false">History</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <!-- TAB Project Information -->
                        <div class="tab-pane fade show active" id="ProjectInformation" role="tabpanel" aria-labelledby="projectinformation-tab">
                            <div class="card shadow mb-4">
                                <!-- Card Body -->
                                <div class="row">
                                    <?php //include("components/modules/service_budget/form_popup_new_project.php"); ?>
                                    <div class="col-lg-6">
                                        <div class="card-body">
                                            <?php //if(isset($_GET['act']) && ($_GET['act'] != 'add')) { ?>
                                            <div class="row mb-3">
                                                <label for="inputKP3" class="col-sm-3 col-form-label col-form-label-sm">No. KP</label>
                                                <div class="col-sm-3">
                                                <input type="text" class="form-control form-control-sm" id="inputKP" value="<?php echo $dsb['project_code']; ?>" name="project_code" readonly>
                                                </div>
                                                <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="inputStatus" value="<?php if($dsb['status'] <> -1) { echo $dsb['status']; } ?>" readonly name="status">
                                                </div>
                                            </div>
                                            <?php //} ?>
                                            <div class="row mb-3">
                                                <label for="inputSO3" class="col-sm-3 col-form-label col-form-label-sm">No. SO</label>
                                                <div class="col-sm-5">
                                                <input type="text" class="form-control form-control-sm" id="inputSONumber" value="<?php if($_GET['act'] != 'add') { echo $dsb['so_number']; } ?>" readonly name="so_number">
                                                </div>
                                                <div class="col-sm-4">
                                                <input type="text" class="form-control form-control-sm text-end" style="text-align: right;" id="inputSODate" value="<?php if(($_GET['act'] != 'add') && (strtotime($dsb['so_date']) >= strtotime('2000-01-01'))) { echo date('d-M-Y', strtotime($dsb['so_date'])); } ?>" readonly name="so_date">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputPO3" class="col-sm-3 col-form-label col-form-label-sm">PO/WO/SP/Kont.</label>
                                                <div class="col-sm-5">
                                                <input type="text" class="form-control form-control-sm" id="inputPONumber" value="<?php echo $dsb['po_number']; ?>" readonly name="po_number">
                                                </div>
                                                <div class="col-sm-4">
                                                <input type="text" class="form-control form-control-sm" style="text-align: right;" id="inputPODate" value="<?php if(strtotime($dsb['po_date']) >= strtotime('2000-01-01')) { echo date('d-M-Y', strtotime($dsb['po_date'])); } ?>" readonly name="po_date">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputNP3" class="col-sm-3 col-form-label col-form-label-sm">Nama Project</label>
                                                <div class="col-sm-9">
                                                <input type="text" class="form-control form-control-sm" id="inputProjectName" value="<?php echo $dsb['project_name']; ?>" readonly name="project_name">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Customer Code</label>
                                                <div class="col-sm-9">
                                                <input type="text" class="form-control form-control-sm" id="inputCustomerCode" value="<?php echo $dsb['customer_code']; ?>" readonly name="customer_code">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Customer</label>
                                                <div class="col-sm-9">
                                                <input type="text" class="form-control form-control-sm" id="inputCustomerName" value="<?php echo $dsb['customer_name']; ?>" readonly name="customer_name">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Sales Name</label>
                                                <div class="col-sm-9">
                                                <input type="text" class="form-control form-control-sm" id="inputSalesName" value="<?php echo $dsb['sales_name']; ?>" readonly name="sales_name">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="card-body">
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Duration</label>
                                                <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="inputDuration" value="<?php if($ver > 0) { echo $dsb['duration']; } ?>" name="duration" style="text-align: right" <?php echo $permission; ?>>
                                                </div>
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">years</label>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Contract Type</label>
                                                <div class="col-sm-9">
                                                    <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="contract_type" <?php echo $permission; ?>>
                                                        <option value="Kontrak Biasa" <?php if(($ver > 0) && ($dsb['contract_type'] == 'Kontrak Biasa')) { echo 'selected'; } ?>>Kontrak Biasa</option>
                                                        <option value="Kontrak Payung" <?php if(($ver > 0) && ($dsb['contract_type'] == 'Kontrak Payung')) { echo 'selected'; } ?>>Kontrak Payung</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Work Order</label>
                                                <div class="col-sm-9">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="wo_type" id="flexRadioDefault1" value="PO" <?php if(($ver > 0) && ($dsb['wo_type'] == 'PO')) { echo 'checked'; } else { echo 'checked'; } ?> <?php echo $permission; ?>>
                                                        <label class="form-check-label" for="flexRadioDefault1">Purchase Order</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="wo_type" id="flexRadioDefault2"  value="WO" <?php if(($ver > 0) && ($dsb['wo_type'] == 'WO')) { echo 'checked'; } ?> <?php echo $permission; ?>>
                                                        <label class="form-check-label" for="flexRadioDefault2">Work Order</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="wo_type" id="flexRadioDefault2"  value="SPK" <?php if(($ver > 0) && ($dsb['wo_type'] == 'SPK')) { echo 'checked'; } ?> <?php echo $permission; ?>>
                                                        <label class="form-check-label" for="flexRadioDefault2">Surat Perintah Kerja</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="wo_type" id="flexRadioDefault2"  value="Ticket" <?php if(($ver > 0) && ($dsb['wo_type'] == 'Ticket')) { echo 'checked'; } ?> <?php echo $permission; ?>>
                                                        <label class="form-check-label" for="flexRadioDefault2">Ticketing System</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="wo_type" id="flexRadioDefault2"  value="Others" <?php if(($ver > 0) && ($dsb['wo_type'] == 'Others')) { echo 'checked'; } ?> <?php echo $permission; ?>>
                                                        <label class="form-check-label" for="flexRadioDefault2">Others</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="alert alert-info" role="alert">
                                                <div>
                                                    <li>Service Budget dengan nilai project di atas 200jt akan dibuat oleh Presales Account, termasuk untuk project penyediaan; services dan  extended warranty, termasuk penyediaan services dari principal.</li>
                                                    <li>Service Budget Sederhana yang dibuat oleh Sales dengan ketentuan hanya Product (tanpa services) dan nilai project dibawah 200jt. Cukup mengisi Project Information dan Project Solution.</li>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB Project Solution -->
                        <?php 
                            global $username, $password, $hostname;
                            if($ver>0) {
                                $database = "sa_ps_service_budgets";
                                $tblname = "trx_project_solutions";
                                $condition = "`project_id`=" . $dsb['project_id'];
                                $DPS = new Databases($hostname, $username, $password, $database);
                                $psolution = $DPS->get_data($tblname, $condition);
                                $dpsolution = $psolution[0];
                                $qpsolution = $psolution[1];
                                $tpsolution = $psolution[2];

                                $totalproductsolution = 0;
                                $totalservicesolution = 0;
                                $psol = array();
                                if($tpsolution>0) {
                                    do { 
                                        $array1 = array($dpsolution['solution_name']=>array("product"=>$dpsolution['product'], "services"=>$dpsolution['services'] ));
                                        $psol = array_merge($psol, $array1);
                                        $totalproductsolution += $dpsolution['product'];
                                        $totalservicesolution += $dpsolution['services'];
                                    } while($dpsolution=$qpsolution->fetch_assoc());
                                }
                            }
                        ?>
                        <!-- tab-pane fade -->
                        <div class="tab-pane fade" id="ProjectSolution" role="tabpanel" aria-labelledby="projectsolution-tab">
                            <div class="card shadow mb-4">
                                <!-- Card Body -->
                                <div class="col-lg-6">
                                    <div class="card-body">
                                        <div class="row mb-3 card-header">
                                            <label for="inputCID3" class="col-sm-6 col-form-label">Project Solution</label>
                                            <label for="inputCID3" class="col-sm-3 col-form-label">Product</label>
                                            <label for="inputCID3" class="col-sm-3 col-form-label">Services</label>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Data Center & Cloud Infrastructure</label>
                                            <div class="col-sm-3">
                                            <input type="text" class="form-control form-control-sm" id="inputDCCIP" name="DCCIP" value="<?php if($ver>0 && $tpsolution>0) { echo $psol['DCCI']['product']; } ?>" style="text-align: right" <?php echo $permission; ?>>
                                            </div>
                                            <div class="col-sm-3">
                                            <input type="text" class="form-control form-control-sm" id="inputDCCIS" name="DCCIS" value="<?php if($ver>0 && $tpsolution>0) { echo $psol['DCCI']['services']; } ?>" style="text-align: right" <?php echo $permission; ?>>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Enterprise Collaboration</label>
                                            <div class="col-sm-3">
                                            <input type="text" class="form-control form-control-sm" id="inputECP" name="ECP" value="<?php if($ver>0 && $tpsolution>0) { echo $psol['EC']['product']; } ?>" style="text-align: right" <?php echo $permission; ?>>
                                            </div>
                                            <div class="col-sm-3">
                                            <input type="text" class="form-control form-control-sm" id="inputECS" name="ECS" value="<?php if($ver>0 && $tpsolution>0) { echo $psol['EC']['services']; } ?>" style="text-align: right" <?php echo $permission; ?>>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Big Data & Analytics</label>
                                            <div class="col-sm-3">
                                            <input type="text" class="form-control form-control-sm" id="inputBDAP" name="BDAP" value="<?php if($ver>0 && $tpsolution>0) { echo $psol['BDA']['product']; } ?>" style="text-align: right" <?php echo $permission; ?>>
                                            </div>
                                            <div class="col-sm-3">
                                            <input type="text" class="form-control form-control-sm" id="inputBDAS" name="BDAS" value="<?php if($ver>0 && $tpsolution>0) { echo $psol['BDA']['services']; } ?>" style="text-align: right" <?php echo $permission; ?>>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Digital Business Management</label>
                                            <div class="col-sm-3">
                                            <input type="text" class="form-control form-control-sm" id="inputDBMP" name="DBMP" value="<?php if($ver>0 && $tpsolution>0) { echo $psol['DBM']['product']; } ?>" style="text-align: right" <?php echo $permission; ?>>
                                            </div>
                                            <div class="col-sm-3">
                                            <input type="text" class="form-control form-control-sm" id="inputDBMS" name="DBMS" value="<?php if($ver>0 && $tpsolution>0) { echo $psol['DBM']['services']; } ?>" style="text-align: right" <?php echo $permission; ?>>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Adaptive Security Architecture</label>
                                            <div class="col-sm-3">
                                            <input type="text" class="form-control form-control-sm" id="inputASAP" name="ASAP" value="<?php if($ver>0 && $tpsolution>0) { echo $psol['ASA']['product']; } ?>" style="text-align: right" <?php echo $permission; ?>>
                                            </div>
                                            <div class="col-sm-3">
                                            <input type="text" class="form-control form-control-sm" id="inputASAS" name="ASAS" value="<?php if($ver>0 && $tpsolution>0) { echo $psol['ASA']['services']; } ?>" style="text-align: right" <?php echo $permission; ?>>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Service Provider</label>
                                            <div class="col-sm-3">
                                            <input type="text" class="form-control form-control-sm" id="inputSPP" name="SPP" value="<?php if($ver>0 && $tpsolution>0) { echo $psol['SP']['services']; } ?>" style="text-align: right" <?php echo $permission; ?>>
                                            </div>
                                            <div class="col-sm-3">
                                            <input type="text" class="form-control form-control-sm" id="inputSPS" name="SPS" value="<?php if($ver>0 && $tpsolution>0) { echo $psol['SP']['services']; } ?>" style="text-align: right" <?php echo $permission; ?>>
                                            </div>
                                        </div>
                                        <div class="row mb-3 card-footer">
                                            <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Total</label>
                                            <div class="col-sm-3">
                                            <input type="text" class="form-control form-control-sm" id="inputSPP" name="" value="<?php if($ver>0 && $tpsolution>0) { echo $totalproductsolution; } ?>" style="text-align: right" readonly>
                                            </div>
                                            <div class="col-sm-3">
                                            <input type="text" class="form-control form-control-sm" id="inputSPS" name="" value="<?php if($ver>0 && $tpsolution>0) { echo $totalservicesolution; } ?>" style="text-align: right" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB Implementation -->
                        <?php 
                            global $username, $password, $hostname;
                            if($ver>0) {
                                $database = "sa_ps_service_budgets";
                                $tblname = "trx_project_implementations";
                                $condition = "project_id=" . $dsb['project_id'] . " AND service_type=1";
                                $DIMP = new Databases($hostname, $username, $password, $database);
                                $implement = $DIMP->get_data($tblname, $condition);
                                $dimplement = $implement[0];
                                $qimplement = $implement[1];
                                $timplement = $implement[2];
                            }
                        ?>
                        <div class="tab-pane fade" id="Implementation" role="tabpanel" aria-labelledby="implementation-tab">
                            <div class="card shadow mb-4">
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="row">
                                            <!-- Service catalogs -->
                                        <div class="col-lg-6">
                                            <label for="inputCID3" class="col-sm-12 alert alert-secondary"><b>Service Catalog</b></label>
                                            <div class="row mb-2">
                                                <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Service Type</label>
                                                <div class="col-sm-6">
                                                    <?php 
                                                        global $DTSB;
                                                        $tblname = "mst_type_of_service";
                                                        $condition = "service_type=1";
                                                        $tos = $DTSB->get_data($tblname, $condition);
                                                        $dtos = $tos[0];
                                                        $qtos = $tos[1];
                                                    ?>
                                                    <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="i_tos_id">
                                                        <option selected>Select Service of Type</option>
                                                        <?php do { ?>
                                                            <option value="<?php echo $dtos['tos_id']; ?>" <?php if($ver>0) { if($timplement>0 && $dtos['tos_id'] == $dimplement['tos_id']) { echo 'selected'; }} ?>><?php echo $dtos['tos_name']; ?></option>
                                                        <?php } while($dtos=$qtos->fetch_assoc()); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Project Category</label>
                                                <div class="col-sm-6">
                                                    <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="i_tos_category_id" <?php echo $permission; ?>>
                                                        <option value="1" <?php if(($ver > 0 && $timplement>0) && ("1" == $dimplement['tos_category_id'])) { echo 'selected'; } ?>>Large</option>
                                                        <option value="2" <?php if(($ver > 0 && $timplement>0) && ("2" == $dimplement['tos_category_id'])) { echo 'selected'; } ?>>Medium</option>
                                                        <option value="3" <?php if(($ver > 0 && $timplement>0) && ("3" == $dimplement['tos_category_id'])) { echo 'selected'; } ?>>Small</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Estimation Project Duration</label>
                                                <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="inputEstimation" name="i_project_estimation" value="<?php if($ver>0 && $timplement>0) { echo $dimplement['project_estimation']; } ?>" style="text-align: right;" <?php echo $permission; ?>>
                                                </div>
                                                <div class="col-sm-3">
                                                    <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="i_project_estimation_id" <?php echo $permission; ?>>
                                                        <option value="1" <?php if(($ver > 0 && $timplement>0) && ("1" == $dimplement['project_estimation_id'])) { echo 'selected'; } ?>>Days</option>
                                                        <option value="2" <?php if(($ver > 0 && $timplement>0) && ("2" == $dimplement['project_estimation_id'])) { echo 'selected'; } ?>>Months</option>
                                                        <option value="3" <?php if(($ver > 0 && $timplement>0) && ("3" == $dimplement['project_estimation_id'])) { echo 'selected'; } ?>>Years</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Implementation Price -->
                                        <div class="col-lg-6">
                                            <label for="inputCID3" class="col-sm-12 alert alert-secondary"><b>Agree Price</b></label>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Agreed Price</label>
                                                <div class="col-sm-3">
                                                <input type="text" class="form-control form-control-sm" id="i_agreed_price" name="i_agreed_price" value="<?php if($ver>0 && $timplement>0) { echo $dimplement['agreed_price']; } ?>" style="text-align: right;" <?php echo $permission; ?>>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Implementasi Price (sesuai PO/SPK)</label>
                                                <div class="col-sm-3">
                                                <input type="text" class="form-control form-control-sm" name="i_price" id="i_price" value="<?php if($ver>0 && $timplement>0) { echo $dimplement['implementation_price']; } ?>" style="text-align: right;" onchange="i_totalbranded();" <?php echo $permission; ?>>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Businiess Trip -->
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label for="inputCID3" class="col-sm-12 alert alert-secondary"><b>Busines Trip</b></label>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Total Locations</label>
                                                <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="inputBPDImplementationLocation" name="i_bpd_total_location" value="<?php if($ver>0 && $timplement>0) { echo $dimplement['bpd_total_location']; } ?>" style="text-align: right;" <?php echo $permission; ?>>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Description of Location</label>
                                                <div class="col-sm-6">
                                                <textarea class="form-control" id="exampleFormControlTextarea1" name="i_bpd_description" rows="3" <?php echo $permission; ?>><?php if($ver>0 && $timplement>0) { echo $dimplement['bpd_description']; } ?></textarea>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Subtotal Mandays/Product (IDR)</label>
                                                <div class="col-sm-3">
                                                <input type="text" class="form-control form-control-sm" id="i_bpd_price" name="i_bpd_price" value="<?php if($ver>0 && $timplement>0) { echo $dimplement['bpd_price']; } ?>" style="text-align: right;" onchange="i_totalbranded();" <?php echo $permission; ?>>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Outsourcing Plan -->
                                        <div class="col-lg-6">
                                            <label for="inputCID3" class="col-sm-12 alert alert-secondary"><b>Outsourcing Plan</b></label>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Outsourcing Plan Description</label>
                                                <div class="col-sm-6">
                                                <textarea class="form-control" id="exampleFormControlTextarea1" name="i_out_description" rows="3" <?php echo $permission; ?>><?php if($ver>0 && $timplement>0) { echo $dimplement['out_description']; } ?></textarea>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Subtotal Outsourcing Plan (IDR)</label>
                                                <div class="col-sm-3">
                                                <input type="text" class="form-control form-control-sm" id="i_out_price" name="i_out_price" value="<?php if($ver>0 && $timplement>0) { echo $dimplement['out_price']; } ?>" style="text-align: right;" onchange="i_totalbranded();" <?php echo $permission; ?>>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Mandays Calculation -->
                                    <label for="inputCID3" class="col-sm-12 alert alert-secondary"><b>Mandays Calculation</b></label>
                                    <?php 
                                        if($ver>0) {
                                            global $DTSB;

                                            $tblname = "trx_project_mandays";
                                            $array = array(); $i=1;
                                            for($i=1; $i<8; $i++) {
                                                $condition = "project_id=" . $dsb['project_id'] . " AND service_type=1 AND (resource_level DIV 10)=" . $i;
                                                $order = "resource_level ASC";
                                                $data = $DTSB->get_data($tblname, $condition, $order);
                                                $ddata = $data[0];
                                                $qdata = $data[1];
                                                $tdata = $data[2];

                                                $arrayitems = array();
                                                $value = NULL;
                                                $j=0; 
                                                if($tdata>0) {
                                                do { 
                                                    if($ddata['resource_level'] != (($i)*10 +$j+1)) {
                                                        $arrayitem = array('brand'=>NULL, 'mantotal'=>NULL, 'mandays'=>NULL, 'value'=>$value);
                                                        array_push($arrayitems, $arrayitem);
                                                        $j++;
                                                    }
                                                    $arrayitem = array('brand'=>$ddata['brand'], 'mantotal'=>$ddata['mantotal'], 'mandays'=>$ddata['mandays'], 'value'=>$ddata['value']);
                                                    array_push($arrayitems, $arrayitem);
                                                    $j++;
                                                    $value = $ddata['value'];
                                                } while($ddata=$qdata->fetch_assoc());
                                                }
                                                if($j<5) {
                                                    for($k=$j; $k<5; $k++) {
                                                        $arrayitem = array('brand'=>NULL, 'mantotal'=>NULL, 'mandays'=>NULL, 'value'=>$value);
                                                        array_push($arrayitems, $arrayitem);
                                                    }
                                                }
                                                array_push($array, $arrayitems);
                                            }

                                            $mysql = sprintf("SELECT `resource_level`,`project_id`,`brand`, COUNT(`brand`) AS `tbrand`, (`resource_level`-(`resource_level` DIV 10)*10) AS `res` FROM `sa_trx_project_mandays` WHERE `project_id`=%s AND service_type=1 GROUP BY `project_id`,`brand` ORDER BY `res` ASC",
                                                GetSQLValueString($dsb['project_id'], "int"));

                                            $brandlist = $DTSB->get_sql($mysql);
                                            $dbrandlist = $brandlist[0];
                                            $qbrandlist = $brandlist[1];
                                            $tbrandlist = $brandlist[2]; 

                                            $j=0;
                                            $brand = array();
                                            if($tbrandlist>0) {
                                                do {
                                                    if(($dbrandlist['resource_level'] % 10) != ($j+1)) {
                                                        array_push($brand, NULL);
                                                        $j++;
                                                    }
                                                    array_push($brand, $dbrandlist['brand']);
                                                    $j++;
                                                } while($dbrandlist=$qbrandlist->fetch_assoc());
                                                if($j<5) {
                                                    for($k=$j; $k<5; $k++) {
                                                        array_push($brand,NULL);
                                                    }
                                                }
                                            } else {
                                                for($k=0; $k<5; $k++) {
                                                    array_push($brand,NULL);
                                                }
                                            }

                                        } else {
                                            $array = array();
                                            for($i=1; $i<8; $i++) {
                                                $arrayitems = array();
                                                for($j=0; $j<5; $j++) {
                                                    $arrayitem = array('brand'=>NULL, 'mantotal'=>NULL, 'mandays'=>NULL, 'value'=>NULL);
                                                    array_push($arrayitems, $arrayitem);
                                                }
                                                array_push($array, $arrayitems);
                                            }
                                            $brand = array();
                                            for($i=0; $i<5; $i++) {
                                                array_push($brand, NULL);
                                            }
                                            $tblname = "mst_resource_catalogs";
                                            $resource = $DTSB->get_data($tblname);
                                            $dresource = $resource[0];
                                            $qresource = $resource[1]; 
                                            $resources = array($dresource['resource_qualification']=>$dresource['mandays']);
                                            while($dresource=$qresource->fetch_assoc()) {
                                                $res1 = array($dresource['resource_qualification']=>$dresource['mandays']);
                                                $resources = array_merge($resources, $res1);
                                            } 

                                        }
                                        $reslevel = array("PD", "PM", "PC", "PA", "EE", "EP", "EA");

                                        ?>

                                        <div class="row mb-3 card-header">
                                            <label for="inputCID3" class="col-sm-2 col-form-label">Resource Level</label>
                                            <label for="inputCID3" class="col-sm-1 col-form-label">Mandays</label>
                                            <label for="inputCID3" class="col col-form-label">Brand 1</label>
                                            <label for="inputCID3" class="col col-form-label">Brand 2</label>
                                            <label for="inputCID3" class="col col-form-label">Brand 3</label>
                                            <label for="inputCID3" class="col col-form-label">Brand 4</label>
                                            <label for="inputCID3" class="col col-form-label">Brand 5</label>
                                            <label for="inputCID3" class="col col-form-label">Total Mandays</label>
                                            <label for="inputCID3" class="col col-form-label">Rate (USD)</label>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-2 col-form-label col-form-label-sm"></label>
                                            <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">Brand</label>
                                            <?php for($i=0; $i<5; $i++) { ?>
                                            <div class="col">
                                            <input type="text" class="form-control form-control-sm" id="inputCID3" name="i_brand<?php echo $i+1; ?>" value="<?php echo $brand[$i]; ?>" placeholder="Brand Product" <?php echo $permission; ?>>
                                            </div>
                                            <?php } ?>
                                            <div class="col">
                                            <input type="hidden" class="form-control form-control-sm" id="" name="" value="" placeholder="Brand Product" <?php echo $permission; ?>>
                                            </div>
                                            <div class="col">
                                            <input type="hidden" class="form-control form-control-sm" id="" name="" value="" placeholder="Brand Product" <?php echo $permission; ?>>
                                            </div>
                                        </div>

                                        <?php for($i=0; $i<7; $i++) { ?>
                                        <?php
                                            switch ($i) {
                                                case 0:
                                                    $rlevel = "Project Director";
                                                    break;
                                                case 1:
                                                    $rlevel = "Project Manager";
                                                    break;
                                                case 2:
                                                    $rlevel = "Project Coordinator";
                                                    break;
                                                case 3:
                                                    $rlevel = "Project Admin";
                                                    break;
                                                case 4:
                                                    $rlevel = "Engineer Expert";
                                                    break;
                                                case 5:
                                                    $rlevel = "Engineer Professional";
                                                    break;
                                                case 6:
                                                    $rlevel = "Engineer Associate";

                                            } 
                                        ?>
                                        <div class="row mb-1">
                                            <label for="inputCID3" class="col-sm-2 col-form-label col-form-label-sm"><?php echo $rlevel; ?></label>
                                            <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">Mans</label>
                                            <div class="col">
                                            <input type="text" class="form-control form-control-sm" id="i_brand1_<?php echo $reslevel[$i]; ?>" name="i_brand1_<?php echo $reslevel[$i]; ?>" value="<?php echo $array[$i][0]['mantotal']; ?>" style="text-align: right;" onchange="i_changebrand();" <?php echo $permission; ?>>
                                            </div>
                                            <div class="col">
                                            <input type="text" class="form-control form-control-sm" id="i_brand2_<?php echo $reslevel[$i]; ?>" name="i_brand2_<?php echo $reslevel[$i]; ?>" value="<?php echo $array[$i][1]['mantotal']; ?>" style="text-align: right;" onchange="i_changebrand();" <?php echo $permission; ?>>
                                            </div>
                                            <div class="col">
                                            <input type="text" class="form-control form-control-sm" id="i_brand3_<?php echo $reslevel[$i]; ?>" name="i_brand3_<?php echo $reslevel[$i]; ?>" value="<?php echo $array[$i][2]['mantotal']; ?>" style="text-align: right;" onchange="i_changebrand();" <?php echo $permission; ?>>
                                            </div>
                                            <div class="col">
                                            <input type="text" class="form-control form-control-sm" id="i_brand4_<?php echo $reslevel[$i]; ?>" name="i_brand4_<?php echo $reslevel[$i]; ?>" value="<?php echo $array[$i][3]['mantotal']; ?>" style="text-align: right;" onchange="i_changebrand();" <?php echo $permission; ?>>
                                            </div>
                                            <div class="col">
                                            <input type="text" class="form-control form-control-sm" id="i_brand5_<?php echo $reslevel[$i]; ?>" name="i_brand5_<?php echo $reslevel[$i]; ?>" value="<?php echo $array[$i][4]['mantotal']; ?>" style="text-align: right;" onchange="i_changebrand();" <?php echo $permission; ?>>
                                            </div>
                                            <div class="col">
                                            <input type="hidden" class="form-control form-control-sm" id="totx" name="totx" value="" readonly>
                                            </div>
                                            <div class="col">
                                            <input type="hidden" class="form-control form-control-sm" id="i_total_<?php echo $reslevel[$i]; ?>" name="i_total_<?php echo $reslevel[$i]; ?>" value="<?php if($ver>0) { echo $array[$i][4]['value']; } else { echo $resources[$rlevel]; } ?>" style="text-align: right;" readonly>
                                            </div>
                                            <!-- <div class="col-sm-3">
                                            <input type="text" class="form-control form-control-sm" id="inputCID3" name="i_notes_<?php echo $reslevel[$i]; ?>" value="">
                                            </div> -->
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-2 col-form-label col-form-label-sm"></label>
                                            <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">Mandays</label>
                                            <div class="col">
                                            <input type="text" class="form-control form-control-sm" id="i_brand1_<?php echo $reslevel[$i]; ?>M" name="i_brand1_<?php echo $reslevel[$i]; ?>M" value="<?php echo $array[$i][0]['mandays']; ?>" style="text-align: right;" onchange="i_changebrand();" <?php echo $permission; ?>>
                                            </div>
                                            <div class="col">
                                            <input type="text" class="form-control form-control-sm" id="i_brand2_<?php echo $reslevel[$i]; ?>M" name="i_brand2_<?php echo $reslevel[$i]; ?>M" value="<?php echo $array[$i][1]['mandays']; ?>" style="text-align: right;" onchange="i_changebrand();" <?php echo $permission; ?>>
                                            </div>
                                            <div class="col">
                                            <input type="text" class="form-control form-control-sm" id="i_brand3_<?php echo $reslevel[$i]; ?>M" name="i_brand3_<?php echo $reslevel[$i]; ?>M" value="<?php echo $array[$i][2]['mandays']; ?>" style="text-align: right;" onchange="i_changebrand();" <?php echo $permission; ?>>
                                            </div>
                                            <div class="col">
                                            <input type="text" class="form-control form-control-sm" id="i_brand4_<?php echo $reslevel[$i]; ?>M" name="i_brand4_<?php echo $reslevel[$i]; ?>M" value="<?php echo $array[$i][3]['mandays']; ?>" style="text-align: right;" onchange="i_changebrand();" <?php echo $permission; ?>>
                                            </div>
                                            <div class="col">
                                            <input type="text" class="form-control form-control-sm" id="i_brand5_<?php echo $reslevel[$i]; ?>M" name="i_brand5_<?php echo $reslevel[$i]; ?>M" value="<?php echo $array[$i][4]['mandays']; ?>" style="text-align: right;" onchange="i_changebrand();" <?php echo $permission; ?>>
                                            </div>
                                            <div class="col">
                                            <input type="text" class="form-control form-control-sm" id="i_totmandays_<?php echo $reslevel[$i]; ?>" name="i_totmandays_<?php echo $reslevel[$i]; ?>" value="" style="text-align: right;" readonly>
                                            </div>
                                            <div class="col">
                                            <input type="text" class="form-control form-control-sm" id="i_total<?php echo $reslevel[$i]; ?>_M" name="i_total<?php echo $reslevel[$i]; ?>_M" value="<?php if($ver>0) { echo $array[$i][4]['value']; } else { echo $resources[$rlevel]; } ?>" style="text-align: right;" readonly>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="row mb-3 card-footer">
                                        <label for="inputCID3" class="col-sm-2 col-form-label col-form-label-sm">Total (IDR)</label>
                                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm"></label>
                                        <div class="col">
                                        <input type="text" class="form-control form-control-sm" id="i_totalbrand1" name="i_totalbrand1" value="0" style="text-align: right;" readonly>
                                        </div>
                                        <div class="col">
                                        <input type="text" class="form-control form-control-sm" id="i_totalbrand2" name="i_totalbrand2" value="0" style="text-align: right;" readonly>
                                        </div>
                                        <div class="col">
                                        <input type="text" class="form-control form-control-sm" id="i_totalbrand3" name="i_totalbrand3" value="0" style="text-align: right;" readonly>
                                        </div>
                                        <div class="col">
                                        <input type="text" class="form-control form-control-sm" id="i_totalbrand4" name="i_totalbrand4" value="0" style="text-align: right;" readonly>
                                        </div>
                                        <div class="col">
                                        <input type="text" class="form-control form-control-sm" id="i_totalbrand5" name="i_totalbrand5" value="0" style="text-align: right;" readonly>
                                        </div>
                                        <div class="col">
                                        <input type="text" class="form-control form-control-sm" id="i_totalbrand" name="i_totalbrand" value="0" style="text-align: right;" readonly>
                                        </div>
                                        <div class="col">
                                        <input type="hidden" class="form-control form-control-sm" id="" name="" value="0" style="text-align: right;" readonly>
                                        </div>
                                </div>
                                <!-- End Mandays Calculation -->
                                </div>
                            </div>
                        </div>
                        
                        <script>
                        //Implementasi]
                            i_totalbranded();
                            i_totalmandays();
                            i_changebrand();
                        </script>


                        <!-- TAB Project Maintenance -->
                        <?php 
                            global $username, $password, $hostname;
                            if($ver>0) {
                                $database = "sa_ps_service_budgets";
                                $tblname = "trx_project_implementations";
                                $condition = "`project_id`=" . $dsb['project_id'] . " AND service_type=2";
                                $DIMP = new Databases($hostname, $username, $password, $database);
                                $maintenance = $DIMP->get_data($tblname, $condition);
                                $dmaintenance = $maintenance[0];
                                $qmaintenance = $maintenance[1];
                                $tmaintenance = $maintenance[2];
                            }
                        ?>
                        <div class="tab-pane fade" id="Maintenance" role="tabpanel" aria-labelledby="maintenance-tab">
                            <div class="card shadow mb-4">
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label for="inputCID3" class="col-sm-12 alert alert-secondary"><b>Service Catalog</b></label>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Service Type</label>
                                                <div class="col-sm-6">
                                                <?php 
                                                        global $DTSB;
                                                        $tblname = "mst_type_of_service";
                                                        $condition = "service_type=2";
                                                        $tos = $DTSB->get_data($tblname, $condition);
                                                        $dtos = $tos[0];
                                                        $qtos = $tos[1];
                                                    ?>
                                                    <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="m_tos_id" <?php echo $permission; ?>>
                                                        <option selected>Select Service of Type</option>
                                                        <?php do { ?>
                                                            <option value="<?php echo $dtos['tos_id']; ?>" <?php if($ver>0) { if(($tmaintenance>0) && ($dtos['tos_id'] == $dmaintenance['tos_id'])) { echo 'selected'; }} ?>><?php echo $dtos['tos_name']; ?></option>
                                                        <?php } while($dtos=$qtos->fetch_assoc()); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Project Category</label>
                                                <div class="col-sm-6">
                                                    <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="m_tos_category_id" <?php echo $permission; ?>>
                                                        <option value="1" <?php if(($ver > 0 && $tmaintenance>0) && (1 == $dmaintenance['tos_category_id'])) { echo 'selected'; } ?>>Large</option>
                                                        <option value="2" <?php if(($ver > 0 && $tmaintenance>0) && (2 == $dmaintenance['tos_category_id'])) { echo 'selected'; } ?>>Medium</option>
                                                        <option value="3" <?php if(($ver > 0 && $tmaintenance>0) && (3 == $dmaintenance['tos_category_id'])) { echo 'selected'; } ?>>Small</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Estimation Project Duration</label>
                                                <div class="col-sm-2">
                                                <input type="text" class="form-control form-control-sm" id="inputCID3" name="m_project_estimation" value="<?php if($ver>0 && $tmaintenance>0) { echo $dmaintenance['project_estimation']; } ?>" style="text-align: right;" <?php echo $permission; ?>>
                                                </div>
                                                <div class="col-sm-3">
                                                    <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="m_project_estimation_id" <?php echo $permission; ?>>
                                                        <option value="1" <?php if(($ver > 0 && $tmaintenance>0) && (1 == $dmaintenance['project_estimation_id'])) { echo 'selected'; } ?>>Days</option>
                                                        <option value="2" <?php if(($ver > 0 && $tmaintenance>0) && (2 == $dmaintenance['project_estimation_id'])) { echo 'selected'; } ?>>Months</option>
                                                        <option value="3" <?php if(($ver > 0 && $tmaintenance>0) && (3 == $dmaintenance['project_estimation_id'])) { echo 'selected'; } ?>>Years</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <label for="inputCID3" class="col-sm-12 alert alert-secondary"><b>Agree Price</b></label>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Maintenance Price (sesuai PO/SPK)</label>
                                                <div class="col-sm-3">
                                                <input type="text" class="form-control form-control-sm" id="m_price" name="m_price" value="<?php if($ver>0 && $tmaintenance>0) { echo $dmaintenance['implementation_price']; } ?>" style="text-align: right" onchange="m_totalmandays();" <?php echo $permission; ?>>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Agreed Price</label>
                                                <div class="col-sm-3">
                                                <input type="text" class="form-control form-control-sm" id="m_agreed_price" name="m_agreed_price" value="<?php if($ver>0 && $tmaintenance>0) { echo $dmaintenance['agreed_price']; } ?>" style="text-align: right" onchange="" <?php echo $permission; ?>>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label for="inputCID3" class="col-sm-12 alert alert-secondary"><b>Business Trip</b></label>
                                            <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Total Location</label>
                                                <div class="col-sm-3">
                                                <input type="text" class="form-control form-control-sm" id="inputCID3" name="m_bpd_total_location" value="<?php if($ver>0 && $tmaintenance>0) { echo $dmaintenance['bpd_total_location']; } ?>" style="text-align: right;" <?php echo $permission; ?>>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Description of Location</label>
                                                <div class="col-sm-6">
                                                <textarea class="form-control" id="exampleFormControlTextarea1" name="m_bpd_description" rows="3" <?php echo $permission; ?>><?php if($ver>0 && $tmaintenance>0) { echo $dmaintenance['bpd_description']; } ?></textarea>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Subtotal Mandays/Product (IDR)</label>
                                                <div class="col-sm-3">
                                                <input type="text" class="form-control form-control-sm" id="m_bpd_price" name="m_bpd_price" value="<?php if($ver>0 && $tmaintenance>0) { echo $dmaintenance['bpd_price']; } ?>" style="text-align: right;" onchange="m_totalmandays();" <?php echo $permission; ?>>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <label for="inputCID3" class="col-sm-12 alert alert-secondary"><b>Outsourcing Plan</b></label>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Outsourcing Plan Description</label>
                                                <div class="col-sm-6">
                                                <textarea class="form-control" id="exampleFormControlTextarea1" name="m_out_description" rows="3" <?php echo $permission; ?>><?php if($ver>0 && $tmaintenance>0) { echo $dmaintenance['out_description']; } ?></textarea>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Subtotal Outsourcing Plant (IDR)</label>
                                                <div class="col-sm-3">
                                                <input type="text" class="form-control form-control-sm" id="m_out_price" name="m_out_price" value="<?php if($ver>0 && $tmaintenance>0) { echo $dmaintenance['out_price']; } ?>" style="text-align: right;" onchange="m_totalmandays();" <?php echo $permission; ?>>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label for="inputCID3" class="col-sm-12 alert alert-secondary"><b>Maintenance Package</b></label>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Maintenance Package Description</label>
                                                <div class="col-sm-6">
                                                <textarea class="form-control" id="exampleFormControlTextarea1" name="m_package_description" rows="3" <?php echo $permission; ?>><?php if($ver>0 && $tmaintenance>0) { echo $dmaintenance['maintenance_package_description']; } ?></textarea>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Subtotal Maintenance Package (IDR)</label>
                                                <div class="col-sm-3">
                                                <input type="text" class="form-control form-control-sm" id="m_package_price" name="m_package_price" value="<?php if($ver>0 && $tmaintenance>0) { echo $dmaintenance['maintenance_package_price']; } ?>" style="text-align: right;" onchange="" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <label for="inputCID3" class="col-sm-12 alert alert-secondary"><b>Add On</b></label>
                                            <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Add On Description</label>
                                                <div class="col-sm-6">
                                                <textarea class="form-control" id="exampleFormControlTextarea1" name="m_addon_description" rows="3" <?php echo $permission; ?>><?php if($ver>0 && $tmaintenance>0) { echo $dmaintenance['maintenance_addon_description']; } ?></textarea>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Subtotal Add Onct (IDR)</label>
                                                <div class="col-sm-3">
                                                <input type="text" class="form-control form-control-sm" id="m_addon_price" name="m_addon_price" value="<?php if($ver>0 && $tmaintenance>0) { echo $dmaintenance['maintenance_addon_price']; } ?>" style="text-align: right;" onchange="m_totalmandays();" <?php echo $permission; ?>>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Start Backup Unit -->
                                    <!-- Mandays Calculation -->
                                    <label for="inputCID3" class="col-sm-12 alert alert-secondary"><b>Backup Unit</b></label>
                                    <?php 
                                        if($ver>0) {
                                            global $DTSB;

                                            $tblname = "trx_project_mandays";
                                            $array = array(); $i=1;
                                            for($i=1; $i<3; $i++) {
                                                $condition = "project_id=" . $dsb['project_id'] . " AND service_type=2 AND (resource_level DIV 10)=" . $i;
                                                $order = "resource_level ASC";
                                                $data = $DTSB->get_data($tblname, $condition, $order);
                                                $ddata = $data[0];
                                                $qdata = $data[1];
                                                $tdata = $data[2];

                                                $arrayitems = array();
                                                $value = NULL;
                                                $j=0; 
                                                if($tdata>0) {
                                                do { 
                                                    if($ddata['resource_level'] != (($i)*10 +$j+1)) {
                                                        $arrayitem = array('brand'=>NULL, 'mantotal'=>NULL, 'mandays'=>NULL, 'value'=>$value);
                                                        array_push($arrayitems, $arrayitem);
                                                        $j++;
                                                    }
                                                    $arrayitem = array('brand'=>$ddata['brand'], 'mantotal'=>$ddata['mantotal'], 'mandays'=>$ddata['mandays'], 'value'=>$ddata['value']);
                                                    array_push($arrayitems, $arrayitem);
                                                    $j++;
                                                    $value = $ddata['value'];
                                                } while($ddata=$qdata->fetch_assoc());
                                                }
                                                if($j<5) {
                                                    for($k=$j; $k<5; $k++) {
                                                        $arrayitem = array('brand'=>NULL, 'mantotal'=>NULL, 'mandays'=>NULL, 'value'=>$value);
                                                        array_push($arrayitems, $arrayitem);
                                                    }
                                                }
                                                array_push($array, $arrayitems);
                                            }

                                            $mysql = sprintf("SELECT `resource_level`,`project_id`,`brand`, COUNT(`brand`) AS `tbrand`, (`resource_level`-(`resource_level` DIV 10)*10) AS `res` FROM `sa_trx_project_mandays` WHERE `project_id`=%s AND service_type=1 GROUP BY `project_id`,`brand` ORDER BY `res` ASC",
                                                GetSQLValueString($dsb['project_id'], "int"));

                                            $brandlist = $DTSB->get_sql($mysql);
                                            $dbrandlist = $brandlist[0];
                                            $qbrandlist = $brandlist[1];
                                            $tbrandlist = $brandlist[2]; 

                                            $j=0;
                                            $brand = array();
                                            if($tbrandlist>0) {
                                                do {
                                                    if(($dbrandlist['resource_level'] % 10) != ($j+1)) {
                                                        array_push($brand, NULL);
                                                        $j++;
                                                    }
                                                    array_push($brand, $dbrandlist['brand']);
                                                    $j++;
                                                } while($dbrandlist=$qbrandlist->fetch_assoc());
                                                if($j<5) {
                                                    for($k=$j; $k<5; $k++) {
                                                        array_push($brand,NULL);
                                                    }
                                                }
                                            } else {
                                                for($k=0; $k<5; $k++) {
                                                    array_push($brand,NULL);
                                                }
                                            }

                                        } else {
                                            $array = array();
                                            for($i=1; $i<7; $i++) {
                                                $arrayitems = array();
                                                for($j=0; $j<5; $j++) {
                                                    $arrayitem = array('brand'=>NULL, 'mantotal'=>NULL, 'mandays'=>NULL, 'value'=>NULL);
                                                    array_push($arrayitems, $arrayitem);
                                                }
                                                array_push($array, $arrayitems);
                                            }
                                            $brand = array();
                                            for($i=0; $i<5; $i++) {
                                                array_push($brand, NULL);
                                            }
                                            $tblname = "mst_resource_catalogs";
                                            $resource = $DTSB->get_data($tblname);
                                            $dresource = $resource[0];
                                            $qresource = $resource[1]; 
                                            $resources = array($dresource['resource_qualification']=>$dresource['mandays']);
                                            while($dresource=$qresource->fetch_assoc()) {
                                                $res1 = array($dresource['resource_qualification']=>$dresource['mandays']);
                                                $resources = array_merge($resources, $res1);
                                            } 

                                        }
                                        $reslevel = array("BU", "BUE");

                                        ?>

                                        <div class="row mb-3 card-header">
                                            <label for="inputCID3" class="col-sm-2 col-form-label">Backup Unit</label>
                                            <label for="inputCID3" class="col-sm-1 col-form-label"></label>
                                            <label for="inputCID3" class="col col-form-label">Brand 1</label>
                                            <label for="inputCID3" class="col col-form-label">Brand 2</label>
                                            <label for="inputCID3" class="col col-form-label">Brand 3</label>
                                            <label for="inputCID3" class="col col-form-label">Brand 4</label>
                                            <label for="inputCID3" class="col col-form-label">Brand 5</label>
                                            <label for="inputCID3" class="col col-form-label">Total Backup</label>
                                            <label for="inputCID3" class="col col-form-label">Rate (USD)</label>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-2 col-form-label col-form-label-sm"></label>
                                            <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">Brand</label>
                                            <?php for($i=0; $i<5; $i++) { ?>
                                            <div class="col">
                                            <input type="text" class="form-control form-control-sm" id="inputCID3" name="m_brand<?php echo $i+1; ?>" value="<?php echo $brand[$i]; ?>" placeholder="Brand Product" <?php echo $permission; ?>>
                                            </div>
                                            <?php } ?>
                                            <div class="col">
                                            <input type="hidden" class="form-control form-control-sm" id="" name="" value="" placeholder="Brand Product" <?php echo $permission; ?>>
                                            </div>
                                            <div class="col">
                                            <input type="hidden" class="form-control form-control-sm" id="" name="" value="" placeholder="Brand Product" <?php echo $permission; ?>>
                                            </div>
                                        </div>

                                        <?php for($i=0; $i<2; $i++) { ?>
                                        <?php
                                            switch ($i) {
                                                case 0:
                                                    $rlevel = "Existing Backup Unit";
                                                    break;
                                                case 1:
                                                    $rlevel = "Investment Backup Unit";
                                                    break;
                                            } 
                                        ?>
                                        <div class="row mb-1">
                                            <label for="inputCID3" class="col-sm-2 col-form-label col-form-label-sm"><?php echo $rlevel; ?></label>
                                            <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm"></label>
                                            <div class="col">
                                            <input type="text" class="form-control form-control-sm" id="m_brand1_<?php echo $reslevel[$i]; ?>" name="m_brand1_<?php echo $reslevel[$i]; ?>" value="<?php echo $array[$i][0]['mandays']; ?>" style="text-align: right;" onchange="m_changebrand();" <?php echo $permission; ?>>
                                            </div>
                                            <div class="col">
                                            <input type="text" class="form-control form-control-sm" id="m_brand2_<?php echo $reslevel[$i]; ?>" name="m_brand2_<?php echo $reslevel[$i]; ?>" value="<?php echo $array[$i][1]['mandays']; ?>" style="text-align: right;" onchange="m_changebrand();" <?php echo $permission; ?>>
                                            </div>
                                            <div class="col">
                                            <input type="text" class="form-control form-control-sm" id="m_brand3_<?php echo $reslevel[$i]; ?>" name="m_brand3_<?php echo $reslevel[$i]; ?>" value="<?php echo $array[$i][2]['mandays']; ?>" style="text-align: right;" onchange="m_changebrand();" <?php echo $permission; ?>>
                                            </div>
                                            <div class="col">
                                            <input type="text" class="form-control form-control-sm" id="m_brand4_<?php echo $reslevel[$i]; ?>" name="m_brand4_<?php echo $reslevel[$i]; ?>" value="<?php echo $array[$i][3]['mandays']; ?>" style="text-align: right;" onchange="m_changebrand();" <?php echo $permission; ?>>
                                            </div>
                                            <div class="col">
                                            <input type="text" class="form-control form-control-sm" id="m_brand5_<?php echo $reslevel[$i]; ?>" name="m_brand5_<?php echo $reslevel[$i]; ?>" value="<?php echo $array[$i][4]['mandays']; ?>" style="text-align: right;" onchange="m_changebrand();" <?php echo $permission; ?>>
                                            </div>
                                            <div class="col">
                                            <input type="text" class="form-control form-control-sm" id="m_totmandays_<?php echo $reslevel[$i]; ?>" name="m_totmandays_<?php echo $reslevel[$i]; ?>" value="" style="text-align: right;" readonly>
                                            </div>
                                            <label for="inputCID3" class="col col-form-label col-form-label-sm"></label>
                                        </div>
                                    <?php } ?>
                                    <div class="row mb-3 card-footer">
                                        <label for="inputCID3" class="col-sm-2 col-form-label col-form-label-sm">Total (IDR)</label>
                                        <label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm"></label>
                                        <div class="col">
                                        <input type="text" class="form-control form-control-sm" id="m_totalbrand1" name="m_totalbrand1" value="0" style="text-align: right;" readonly>
                                        </div>
                                        <div class="col">
                                        <input type="text" class="form-control form-control-sm" id="m_totalbrand2" name="m_totalbrand2" value="0" style="text-align: right;" readonly>
                                        </div>
                                        <div class="col">
                                        <input type="text" class="form-control form-control-sm" id="m_totalbrand3" name="m_totalbrand3" value="0" style="text-align: right;" readonly>
                                        </div>
                                        <div class="col">
                                        <input type="text" class="form-control form-control-sm" id="m_totalbrand4" name="m_totalbrand4" value="0" style="text-align: right;" readonly>
                                        </div>
                                        <div class="col">
                                        <input type="text" class="form-control form-control-sm" id="m_totalbrand5" name="m_totalbrand5" value="0" style="text-align: right;" readonly>
                                        </div>
                                        <div class="col">
                                        <input type="text" class="form-control form-control-sm" id="m_totalbrand" name="m_totalbrand" value="0" style="text-align: right;" readonly>
                                        </div>
                                        <div class="col">
                                        <input type="hidden" class="form-control form-control-sm" id="" name="" value="0" style="text-align: right;" readonly>
                                        </div>
                                    </div>

                                    <script>
                                        m_totalmandays();
                                        m_changebrand();
                                    </script>

                                    <!-- End Mandays Calculation -->
                                </div>
                            </div>
                        </div>

                        <!-- TAB Extended Warranty -->
                        <?php 
                            global $username, $password, $hostname;
                            if($ver>0) {
                                $database = "sa_ps_service_budgets";
                                $tblname = "trx_project_warranty";
                                $condition = "`project_id`=" . $dsb['project_id'];
                                $DWAR = new Databases($hostname, $username, $password, $database);
                                $warranty = $DWAR->get_data($tblname, $condition);
                                $dwarranty = $warranty[0];
                                $qwarranty = $warranty[1];
                                $twarranty = $warranty[2];
                            }
                        ?>
                        <div class="tab-pane fade" id="ExtendedWarranty" role="tabpanel" aria-labelledby="extendedwarranty-tab">
                            <div class="card shadow mb-4">
                                <!-- Card Body -->
                                <div class="col-lg-12">
                                    <div class="card-body">
                                        <label for="inputCID3" class="col-sm-12 alert alert-secondary"><b>Service Catalog</b></label>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Service Type</label>
                                            <div class="col-sm-6">
                                                <?php 
                                                    global $DTSB;
                                                    $tblname = "mst_type_of_service";
                                                    $condition = "service_type=3";
                                                    $tos = $DTSB->get_data($tblname, $condition);
                                                    $dtos = $tos[0];
                                                    $qtos = $tos[1];
                                                ?>
                                                <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="w_tos_id" <?php echo $permission; ?>>
                                                    <?php do { ?>
                                                        <option value="<?php echo $dtos['tos_id']; ?>" 
                                                        <?php if($ver>0) { if(($twarranty>0 && $dtos['tos_id'] == $dwarranty['tos_id'])) { echo 'selected'; }} ?>><?php echo $dtos['tos_name']; ?></option>
                                                    <?php } while($dtos=$qtos->fetch_assoc()); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <label for="inputCID3" class="col-sm-12 alert alert-secondary"><b>Warranty Budget</b></label>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Periode Extended Warranty</label>
                                            <div class="col-sm-2">
                                            <input type="text" class="form-control form-control-sm" id="inputCID3" name="start_date" value="<?php if($ver>0 && $twarranty>0) { echo $dwarranty['start_date']; } ?>" style="text-align: right;" <?php echo $permission; ?>>
                                            </div>
                                            <div class="col-sm-2">
                                            <input type="text" class="form-control form-control-sm" id="inputCID3" name="end_date" value="<?php if($ver>0 && $twarranty>0) { echo $dwarranty['end_date']; } ?>" style="text-align: right;" <?php echo $permission; ?>>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Price List Extended Warranty (Cisco)</label>
                                            <div class="col-sm-2">
                                            <input type="text" class="form-control form-control-sm" id="inputCID3" name="price_cisco" value="<?php if($ver>0 && $twarranty>0) { echo $dwarranty['price_cisco']; } ?>" style="text-align: right;" <?php echo $permission; ?>>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Discounted Ext. Warranty (NON Cisco)</label>
                                            <div class="col-sm-2">
                                            <input type="text" class="form-control form-control-sm" id="inputCID3" name="price_others" value="<?php if($ver>0 && $twarranty>0) { echo $dwarranty['price_others']; } ?>" style="text-align: right;" <?php echo $permission; ?>>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">PO Customer (IDR)</label>
                                            <div class="col-sm-2">
                                            <input type="text" class="form-control form-control-sm" id="inputCID3" name="price_po" value="<?php if($ver>0 && $twarranty>0) { echo $dwarranty['price_po']; } ?>" style="text-align: right;" <?php echo $permission; ?>>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB History -->
                        <?php 
                            global $username, $password, $hostname;
                            if($ver>0) {
                                $database = "sa_ps_service_budgets";
                                $tblname = "trx_project_list";
                                // if(isset($_GET['project_code'])) {
                                    $condition = "`project_code`='" . $dsb['project_code'] . "'";
                                // } else {
                                //     $condition = "project_id=" . $_GET['id'];
                                // }
                                $order = "version DESC";
                                $DHIS = new Databases($hostname, $username, $password, $database);
                                $history = $DHIS->get_data($tblname, $condition, $order);
                                $dhistory = $history[0];
                                $qhistory = $history[1];
                                $thistory = $history[2];
                            }
                        ?>
                        <div class="tab-pane fade" id="History" role="tabpanel" aria-labelledby="history-tab">
                            <div class="card shadow mb-4">
                                <!-- Card Body -->
                                <div class="row">
                                    <?php //include("components/modules/service_budget/form_popup_new_project.php"); ?>
                                    <div class="col-lg-12">
                                        <div class="card-body">
                                            <table width="100%">
                                                <tr><td>Version</td><td>Date</td><td>Notes</td><td>Revisi by</td></tr>
                                                <?php if($ver>0) { ?>
                                                <?php do { ?>
                                                <tr><td><a href="index.php?mod=service_budget&act=view&id=<?php if($thistory>0) { echo $dhistory['project_id']; } ?>&submit=Submit"><?php if($thistory>0) { echo $dhistory['version']; } ?></a></td><td><?php if($thistory>0) { echo date('d-M-Y H:i:s', strtotime($dhistory['entry_date'])); } ?></td><td><?php if($thistory>0) { echo $dhistory['notes']; } ?></td><td><?php if($thistory>0) { echo $dhistory['entry_by']; } ?></td></tr>
                                                <?php } while($dhistory=$qhistory->fetch_assoc()); ?>
                                                <?php } ?>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <input type="hidden" name="version" value="<?php echo $ver; ?>" >
                    <input type="hidden" name="project_id" value="<?php echo $dsb['project_id']; ?>" >
                    <input type="submit" class="btn btn-secondary" name="cancel" value="Cancel">
                    <?php if($_SESSION['Microservices_UserLevel'] == 'Administrator' || USERPERMISSION=="Super Admin" || USERPERMISSION=="User Admin" || USERPERMISSION=="Entry Data" || USERPERMISSION=="Edit Data") { ?> 
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#save" <?php if($ver>0) { if($dsb['approved'] == 1) { echo 'disabled'; }} ?>>Save</button>
                    <?php 
                    } 
                    if($_SESSION['Microservices_UserLevel'] == 'Administrator' || USERPERMISSION=='Approval' || USERPERMISSION=="Super Admin" || USERPERMISSION=="User Admin") { ?> 
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#approval" <?php if($ver>0) { if($dsb['approved'] == 1) { echo 'disabled'; } } else { echo 'disabled'; } ?>>Approve</button>
                    <?php } ?>

                    <!-- Modal -->
                    <div class="modal fade" id="save" tabindex="-1" aria-labelledby="saveLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="saveLabel"><b>Notes to Save</b></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row mb-3">
                                            <!-- <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Description of Location</label> -->
                                            <div class="col-sm-12">
                                            <textarea class="form-control" id="exampleNotes" name="notes" rows="3">Save at <?php echo date("D, d-M-Y G:h:i"); ?></textarea>
                                            </div>
                                        </div>
                                    <div class="modal-footer"> 
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <input type="submit" class="btn btn-primary" name="save_service_budget" value="Save">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="approval" tabindex="-1" aria-labelledby="approvalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="approvalLabel"><b>Notes to Approval</b></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row mb-3">
                                            <!-- <label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Description of Location</label> -->
                                            <div class="col-sm-12">
                                            <textarea class="form-control" id="approvalNotes" name="notes_approved" rows="3">Approval at <?php echo date("D, d-M-Y G:h:i"); ?></textarea>
                                            </div>
                                        </div>
                                    <div class="modal-footer"> 
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <input type="submit" class="btn btn-primary" name="approval_service_budget" value="Approval">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php
                } else {
                    ?>
                    <script>window.location.href='index.php?mod=service_budget&err=datanotfound';</script>
                    <?php
                }        
                ?>
            </div>
        </div>
    </div>
</form>
