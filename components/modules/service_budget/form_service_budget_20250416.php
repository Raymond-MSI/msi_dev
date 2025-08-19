<!--
Module Name 	: Service Budget
Sub-Module Name : Form Service Budget
Version 		: 2.0.0
Created			: 
-->

<link href="components/vendor/jselect2-4.1.0/select2.min.css" rel="stylesheet" />
<script src="components/vendor/jselect2-4.1.0/select2.min.js"></script>
<script src="components/modules/service_budget/java_service_budget.js"></script>

<script>
	jQuery(function() {
		jQuery('#maintenance_start').datetimepicker({
			// defaultDate: +7,
			onShow: function(ct) {
				this.setOptions({
					// minDate: new Date(new Date().setDate(new Date().getDate()-7)),
					// maxDate: "0",
					// beforeShowDay: enableWEEKEND
				})
			}
		});
		jQuery('#maintenance_end').datetimepicker({
			onShow: function(ct) {
				this.setOptions({
					// minDate:jQuery('#date_timepicker_start').val()?jQuery('#date_timepicker_start').val():false,
					// minDate:jQuery('#date_timepicker_start').val(),
					// maxDate:jQuery('#date_timepicker_start').val(),
					// maxDate: "+2d",
					// beforeShowDay: enableWEEKEND
				})
			}
		});
	});
</script>

<?php
global $DTSB, $DBHCM;

define("HideOld", false);

$EditStatus = false;
if (isset($_GET['act']) && ($_GET['act'] == "order" || $_GET['act'] == "edit")) {
	$EditStatus = true;
}

if (isset($_GET['project_code']) && $_GET['project_code'] == "undefined") {
?>
	<script>
		window.location.href = 'index.php?mod=service_budget&err=datanotselected';
	</script>
<?php
}

$readonly = '';
if ($_GET['act'] == 'view') {
	$readonly = 'readonly';
}

if(isset($_POST['update']))
{
	$mysql = sprintf("UPDATE `sa_trx_project_list` SET `po_number`=%s,`so_number`=%s,`amount_idr`=%s,`amount_usd`=%s, `modified_by`=%s WHERE `order_number`=%s",
		GetSQLValueString($_POST['UpdatePONumber'], "text"),
		GetSQLValueString($_POST['UpdateSONumber'], "text"),
		GetSQLValueString($_POST['UpdateAmountIDR'], "text"),
		GetSQLValueString($_POST['UpdateAmountUSD'], "text"),
		GetSQLValueString($_SESSION['Microservices_UserEmail'], "text"),
		GetSQLValueString($_GET['order_number'], "text")
	);
	// echo $mysql;
	$rs = $DTSB->get_sql($mysql, false);
}

// Check data update CRM
$DBCRM = get_conn("NAVISION");
if(isset($_GET['order_number']) && $_GET['order_number']!="")
{
	$mysql = 'SELECT order_number, so_number, po_number, amount, currency_code FROM sa_mst_order_number WHERE order_number = "' . $_GET['order_number'] . '"';
	$rsChecks = $DBCRM->get_sql($mysql);
	if($rsChecks[2]>0)
	{
		$mysql = "SELECT `so_number`, `po_number`, `project_name`, `amount_idr`, `amount_usd` FROM `sa_view_project_list` WHERE `order_number` = '" . $rsChecks[0]['order_number'] . "'";
		$rsSBFUpdate = $DTSB->get_sql($mysql);
		if($rsSBFUpdate[2]>0)
		{
			$msg = "<ul>";
			if($rsChecks[0]['so_number'] != $rsSBFUpdate[0]['so_number'])
			{
				$msg .= "<li>SO Number updated from " . $rsSBFUpdate[0]['so_number'] . " to " . $rsChecks[0]['so_number'] . "</li>";
			}
			if($rsChecks[0]['po_number'] != $rsSBFUpdate[0]['po_number'])
			{
				$msg .= "<li>PO Number updated from " . $rsSBFUpdate[0]['po_number'] . " to " . $rsChecks[0]['po_number'] . "</li>";
			}
			if($rsChecks[0]['amount'] != $rsSBFUpdate[0]['amount_usd'] && $rsChecks[0]['currency_code'] == "USD")
			{
				$msg .= "<li>Amount updated from USD." . number_format($rsSBFUpdate[0]['amount_usd'],2,",",".") . " to USD." . number_format($rsChecks[0]['amount'],2,",",".") . "</li>";
			}
			if($rsChecks[0]['amount'] != $rsSBFUpdate[0]['amount_idr'] && $rsChecks[0]['currency_code'] == "IDR")
			{
				$msg .= "<li>Amount updated from Rp." . number_format($rsSBFUpdate[0]['amount_idr'],2,",",".") . " to Rp." . number_format($rsChecks[0]['amount'],2,",",".") . "</li>";
			}
			$msg .= "</ul>";
			if($msg!="<ul></ul>")
			{
				?>
				<div class="alert alert-danger" role="alert">
					<form method="post" action="index.php?mod=service_budget&act=edit&project_code=<?php echo $_GET['project_code']; ?>&so_number=<?php echo $_GET['so_number']; ?>&order_number=<?php echo $_GET['order_number']; ?>&submit=Draft">
						<?php 
						echo "<p>There is a change or update to the data:</p>";
						echo $msg;
						echo "Do you want it to be process?"; 
						?>
						<input type='hidden' name='UpdateSONumber' value='<?php echo $rsChecks[0]['so_number']; ?>'>
						<input type='hidden' name='UpdatePONumber' value='<?php echo $rsChecks[0]['po_number']; ?>'>
						<input type='hidden' name='UpdateAmountUSD' value='<?php echo ($rsChecks[0]['currency_code'] == "USD" ? $rsChecks[0]['amount'] : 0); ?>'>
						<input type='hidden' name='UpdateAmountIDR' value='<?php echo ($rsChecks[0]['currency_code'] == "IDR" ? $rsChecks[0]['amount'] : 0); ?>'>
						<button type="submit" class="btn btn-danger" name="update">Yes</button>
					</form>
				</div>
				<?php
			}
		}
	}
}

// Project Information
$srcdata = "project";
$tblname = "trx_project_list";
if (!isset($_GET['project_code'])) {
	$condition = "project_id=" . $_GET['id'];
} elseif ($_GET['act'] == "add") {
	$condition = "project_code='" . $_GET['project_code'] . "' AND so_number='" . $_GET['so_number'] . "'";
} elseif ($_GET['act'] == "order") {
	$condition = "project_code='" . $_GET['project_code'] . "' AND order_number='" . $_GET['order_number'] . "'";
} elseif ($_GET['act'] == 'edit' || $_GET['act'] == 'view') {
	$condition = "project_code='" . $_GET['project_code'] . "' AND (so_number='" . $_GET['so_number'] . "' OR order_number='" . $_GET['order_number'] . "')";
}
$order = "project_id DESC";
$sb = $DTSB->get_data($tblname, $condition, $order);
$dsb = $sb[0];

if ($sb[2] == 0) {
	global $username, $password, $hostname;
	$srcdata = "order";
	$databaseNav = "sa_md_navision";
	if ($_GET['act'] == 'add') {
		$condition = "`project_code`='" . $_GET['project_code'] . "' AND so_number='" . $_GET['so_number'] . "'";
		$tblname = "view_orders";
	} elseif ($_GET['act'] == 'order') {
		$condition = "`project_code`='" . $_GET['project_code'] . "' AND order_number='" . $_GET['order_number'] . "'";
		$tblname = "view_order_number";
	}
	$DTNAV = new Databases($hostname, $username, $password, $databaseNav);
	$sb = $DTNAV->get_data($tblname, $condition);
	$dsb = $sb[0];
	$ver = 0;
} else {
	$ver = $dsb['version'];
}
$tsb = $sb[2];
if (isset($_GET['act']) && $_GET['act'] == "order") {
	$project_id = 0;
} else {
	$project_id = $dsb['project_id'];
}

$permission = '';
if ($dsb['sales_name'] == $_SESSION['Microservices_UserName']) {
	$tblnamevalue = "mst_setup";
	$conditionvalue = "setup_name='Value SB Sederhana'";
	$sbs = $DTSB->get_data($tblnamevalue, $conditionvalue);
	$dsbs = $sbs[0];
	$dsbsexp = explode(";", $dsbs['setup_value']);
	$dsbidrexp = explode("=", $dsbsexp[0]);
	$dsbid = $dsbidrexp[1];
	$dsbusdexp = explode("=", $dsbsexp[1]);
	$dsbusd = $dsbusdexp[1];
	if ($dsb['amount_idr'] >= $dsbid || $dsb['amount_usd'] >= $dsbusd) {
		$permission = "readonly";
	}
}
if (USERPERMISSION_V2 == "bf7717bbfd879cd1a40b71171f9b393e" && (isset($dsb['status']) && ($dsb['status'] == "approved" || $dsb['status'] == 'submited' || $dsb['status'] == 'acknowledge'))) {
	$permission = 'readonly';
}
$adminpermission = "readonly";
if (USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b") {
	$adminpermission = "";
}
if (isset($dsb['sb_temporary']) && $dsb['sb_temporary']) {
	$permission = '';
}

// Project Solution
global $username, $password, $hostname;
if ($ver > 0) {
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
	if ($tpsolution > 0) {
		do {
			$array1 = array($dpsolution['solution_name'] => array("product" => $dpsolution['product'], "services" => $dpsolution['services']));
			$psol = array_merge($psol, $array1);
			if ($dpsolution['product'] != "" && $dpsolution['services'] != "") {
				$totalproductsolution += $dpsolution['product'];
				$totalservicesolution += $dpsolution['services'];
			}
		} while ($dpsolution = $qpsolution->fetch_assoc());
	}
}

// Project Implementation
global $username, $password, $hostname;
if ($ver > 0) {
	$database = "sa_ps_service_budgets";
	$tblname = "trx_project_implementations";
	$condition = "project_id=" . $dsb['project_id'] . " AND service_type=1";
	$DIMP = new Databases($hostname, $username, $password, $database);
	$implement = $DIMP->get_data($tblname, $condition);
	$dimplement = $implement[0];
	$qimplement = $implement[1];
	$timplement = $implement[2];
}

// Project Maintenance
if ($ver > 0) {
	$database = "sa_ps_service_budgets";
	$tblname = "trx_project_implementations";
	$condition = "`project_id`=" . $dsb['project_id'] . " AND service_type=2";
	$DMNT = new Databases($hostname, $username, $password, $database);
	$maintenance = $DMNT->get_data($tblname, $condition);
	$dmaintenance = $maintenance[0];
	$qmaintenance = $maintenance[1];
	$tmaintenance = $maintenance[2];
}

// Project Extended Warranty
if ($ver > 0) {
	$database = "sa_ps_service_budgets";
	$tblname = "trx_project_implementations";
	$condition = "`project_id`=" . $dsb['project_id'] . " AND service_type=3";
	$DWAR = new Databases($hostname, $username, $password, $database);
	$warranty = $DWAR->get_data($tblname, $condition);
	$dwarranty = $warranty[0];
	$qwarranty = $warranty[1];
	$twarranty = $warranty[2];
}

?>
<div class="card shadow">
	<div class="card-header fw-bold">
		Service Budget
	</div>
	<div class="card-body">
		<form name="form" method="post" action="index.php?mod=service_budget">
			<?php if ($tsb > 0) { ?>
				<ul class="nav nav-tabs" id="myTab" role="tablist">
					<li class="nav-item" role="presentation">
						<button class="nav-link active text-body" id="info-tab" data-bs-toggle="tab" data-bs-target="#ProjectInformation" type="button" role="tab" aria-controls="projectinformation" aria-selected="true">Project Information</button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="solution-tab" data-bs-toggle="tab" data-bs-target="#ProjectSolution" type="button" role="tab" aria-controls="projectsolution" aria-selected="false">Project Solution</button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="implementation-tab" data-bs-toggle="tab" data-bs-target="#Implementation" type="button" role="tab" aria-controls="implementation" aria-selected="false">Implementation</button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="maintenance-tab" data-bs-toggle="tab" data-bs-target="#Maintenance" type="button" role="tab" aria-controls="maintenance" aria-selected="false">Maintenance</button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="warranty-tab" data-bs-toggle="tab" data-bs-target="#ExtendedWarranty" type="button" role="tab" aria-controls="warranty" aria-selected="false">Extended Warranty</button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link text-body" id="fileupload-tab" data-bs-toggle="tab" data-bs-target="#FileUpload" type="button" role="tab" aria-controls="fileupload" aria-selected="false">File Upload</button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link text-body" id="history-tab" data-bs-toggle="tab" data-bs-target="#History" type="button" role="tab" aria-controls="history" aria-selected="false">History</button>
					</li>
				</ul>
				<div class="tab-content" id="myTabContent">
					<!-- TAB Project Information -->
					<div class="tab-pane fade show active" id="ProjectInformation" role="tabpanel" aria-labelledby="projectinformation-tab">
						<div class="card mb-4">
							<!-- Card Body -->
							<div class="card-body">
								<div class="row">
									<div class="col-lg-6 mb-5">
										<label for="inputCID3" class="col-sm-12 alert alert-secondary py-3 d-flex flex-row align-items-center justify-content-between">
											<b>Project Information</b>
											<div class="align-items-right">
												<?php
												if (isset($_GET['act']) && $_GET['act'] != "order") {
													if ((($dsb['status'] == 'draft' || $dsb['status'] == 'rejected' || $dsb['status'] == 'reopen') && USERPERMISSION == "5898299487c5b9cdbe7d61809fd20213") || (($dsb['status'] == 'approved' || $dsb['status'] == 'acknowledge') && (USERPERMISSION == "726ea0dd998698e8a87f8e344d373533" || USERPERMISSION == "125b55092905c1919f7558d68cfd62d7" || USERPERMISSION == "975031eb0e919d08ec6ba1993b455793")) || USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION == "dbf36ff3e3827639223983ee8ac47b42") {
												?>
														<i class="fa-solid fa-pencil" data-bs-toggle="modal" data-bs-target="#modal_project_name_internal"></i>
												<?php
													}
												}
												?>
											</div>
										</label>
										<div class="row">
											<div class="col-lg-6">
												<div class="row mb-3">
													<label for="inputKP3" class="col-sm-6 col-form-label col-form-label-sm">Project ID</label>
													<div class="col-sm-6">
														<input type="text" class="form-control form-control-sm" name="project_id" id="project_id" value="<?php echo $ver > 0 ? $dsb['project_id'] : ""; ?>" readonly>
													</div>
												</div>
												<div class="row mb-3">
													<label for="inputKP3" class="col-sm-6 col-form-label col-form-label-sm">Project Code</label>
													<div class="col-sm-6">
														<input type="text" class="form-control form-control-sm" name="project_code" id="project_code" value="<?php echo $dsb['project_code']; ?>" readonly>
													</div>
												</div>
												<div class="row mb-3">
													<label for="inputKP3" class="col-sm-6 col-form-label col-form-label-sm">Order Number</label>
													<div class="col-sm-6">
														<input type="text" class="form-control form-control-sm" name="order_number" id="order_number" value="<?php echo $dsb['order_number']; ?>" readonly>
													</div>
												</div>
												<div class="row mb-3">
													<label for="inputSO3" class="col-sm-6 col-form-label col-form-label-sm">SO Number</label>
													<div class="col-sm-6">
														<input type="text" class="form-control form-control-sm" id="inputSONumber" value="<?php echo $dsb['so_number']; ?>" <?php echo $adminpermission; ?> name="so_number" readonly>
													</div>
													<input type="hidden" class="form-control form-control-sm text-end" style="text-align: right;" id="inputSODate" value="<?php
														if (isset($_GET['act']) && $_GET['act'] == 'add') {
															echo $dsb['so_date'] > 0 ? date('d-M-Y', strtotime($dsb['so_date'])) : '';
														} ?>" readonly name="so_date">
													<input type="hidden" class="form-control form-control-sm" name="status_so" id="inputStatus" value="<?php
														if (isset($_GET['act']) && $_GET['act'] == 'add') {
															echo $dsb['status_so'] <> -1 ? $dsb['status_so'] : "";
														} ?>" readonly>
												</div>
												<div class="row mb-3">
													<label for="inputPO3" class="col-sm-6 col-form-label col-form-label-sm">PO/WO/SP/Kont./Tgl</label>
													<div class="col-sm-6">
														<input type="text" class="form-control form-control-sm" id="inputPONumber" value="<?php echo $dsb['po_number']; ?>" readonly name="po_number">
													</div>
													<div class="col-sm-2">
														<input type="hidden" class="form-control form-control-sm" style="text-align: right;" id="inputPODate" value="<?php
															if (isset($_GET['act']) && $_GET['act'] == 'add') {
																echo date('d-M-Y', strtotime($dsb['po_date']));
															} ?>" <?php echo $adminpermission; ?> name="po_date">
													</div>
												</div>
											</div>
											<div class="col-lg-6">
												<div class="card">
													<div class="card-header">Sub-Project</div>
													<div class="card-body">
														<div class="overflow-scroll" style="height:140px">
															<table style="font-size:12px" width="100%">
																<thead>
																	<tr class="border-bottom">
																		<th>Project Code</th>
																		<th>SO Number</th>
																		<th>Order Number</th>
																	</tr>
																</thead>
																<tbody>
																	<?php
																	if ($ver > 0) {
																		$mysql = sprintf(
																			"SELECT `project_code`, `so_number`, `order_number`  FROM `sa_trx_project_list` WHERE `parent_id`=%s;",
																			GetSQLValueString($dsb['project_id'], "int")
																		);
																		$rsChilds = $DTSB->get_sql($mysql);
																		if ($rsChilds[2] > 0) {
																			do {
																	?>
																				<tr class="border-bottom">
																					<td class="border-left border-right"><?php echo $rsChilds[0]['project_code']; ?></td>
																					<td class="border-right"><?php echo $rsChilds[0]['so_number']; ?></td>
																					<td class="border-right"><?php echo $rsChilds[0]['order_number']; ?></td>
																				</tr>
																	<?php
																			} while ($rsChilds[0] = $rsChilds[1]->fetch_assoc());
																		}
																	}
																	?>
																</tbody>
															</table>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="row mb-3">
											<?php
											$msg = "Price Project = Implementasi Price (sesuai PO/SPK) + Maintenance Price (sesuai PO/SPK) + Extended Warranty Price (PO Customer)";
											?>
											<label for="inputNP3" class="col-sm-3 col-form-label col-form-label-sm">PO Customer (PO/SPK) <i class="fas fa-question-circle" data-bs-toggle="popover" data-bs-trigger="focus" title="<?php echo $msg; ?>"></i></label>
											<div class="col-sm-4">
												<div class="input-group input-group-sm mb-1">
													<span class="input-group-text" id="basic-addon1">IDR</span>
													<input type="text" class="form-control text-end" style="text-align: right;" id="amount_idr" value="<?php
														if (isset($dsb['so_value'])) {
															echo $dsb['amount_idr'] > 0 ? number_format($dsb['amount_idr'], 2) : $dsb['so_value'];
														} else {
															echo number_format($dsb['amount_idr'], 2);
														}
														?>" readonly name="amount_idr" onchange="">
												</div>
											</div>
											<label for="inputSO3" class="col-sm-1 col-form-label col-form-label-sm text-center">/</label>
											<div class="col-sm-4">
												<div class="input-group input-group-sm mb-1">
													<span class="input-group-text" id="basic-addon1">USD</span>
													<input type="text" class="form-control text-end" style="text-align: right;" id="amount_usd" value="<?php echo number_format($dsb['amount_usd'], 2); ?>" readonly name="amount_usd" onchange="">
												</div>
											</div>
										</div>
										<div class="row mb-3">
											<label for="inputNP3" class="col-sm-3 col-form-label col-form-label-sm">Project Name</label>
											<div class="col-sm-9">
												<textarea class="form-control" id="project_name" name="project_name" rows="2" readonly><?php echo $dsb['project_name']; ?></textarea>
											</div>
										</div>
										<div class="row mb-3">
											<label for="inputNP3" class="col-sm-3 col-form-label col-form-label-sm">Project Name Internal</label>
											<div class="col-sm-9">
												<input type="text" class="form-control form-control-sm" name="project_name_internal" id="project_name_internal" aria-label="Project Name Internal" aria-describedby="button-addon2" value="<?php
													if (isset($_GET['act']) && ($_GET['act'] != 'add' && $_GET['act'] != 'order')) {
														echo $dsb['project_name_internal'];
													} else {
														echo date("Y") . " - " . $dsb['project_name'];
													} ?>" readonly>
											</div>
										</div>
										<div class="row mb-3">
											<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Customer</label>
											<div class="col-sm-9">
												<div class="input-group input-group-sm mb-1">
													<input type="hidden" class="form-control" id="customer_code" value="<?php echo $dsb['customer_code']; ?>" readonly name="customer_code">
													<span class="input-group-text" id="basic-addon1"><?php echo $dsb['customer_code']; ?></span>
													<input type="text" class="form-control" id="customer_name" value="<?php echo $dsb['customer_name']; ?>" readonly name="customer_name">
												</div>
											</div>
										</div>
										<script>
											var ProjectCode = document.getElementById('project_code').value; console.log(document.getElementById('project_code').value);
											document.cookie = "PrefixName = " + ProjectCode + "_"; console.log(ProjectCode);
											var CustomerCode = document.getElementById('customer_code').value;
											document.cookie = "CustomerCode = " + CustomerCode;
											var CustomerName = document.getElementById('customer_name').value;
											document.cookie = "CustomerName = " + CustomerName;
											var OrderNumber = document.getElementById('order_number').value;
											document.cookie = "OrderNumber = " + OrderNumber;
										</script>
										<div class="row mb-3">
											<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Sales Name</label>
											<div class="col-sm-9">
												<div class="input-group input-group-sm mb-1">
													<input type="hidden" class="form-control" id="sales_code" value="<?php echo $dsb['sales_code']; ?>" readonly name="sales_code">
													<span class="input-group-text" id="basic-addon1"><?php echo $dsb['sales_code']; ?></span>
													<input type="text" class="form-control" id="sales_name" value="<?php echo $dsb['sales_name']; ?>" readonly name="sales_name">
												</div>
											</div>
										</div>
										<?php
										$tempStatus = "";
										$sambung = "";
										$i = 0;
										if ($dsb['so_number'] == "") {
											$tempStatus .= "SO Number";
											$sambung = ", ";
											$i++;
										}
										if ($dsb['po_number'] == "") {
											$tempStatus .= $sambung . "PO Number";
											$sambung = ", ";
											$i++;
										}
										if ($dsb['amount_idr'] == 0) {
											$tempStatus .= $sambung . "Project Value";
											$i++;
										}
										if ($tempStatus != "") {
											$xxx = $i > 1 ? " are " : " is ";
										?>
											<div class="row mb-3">
												<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Note</label>
												<div class="col-sm-9">
													<span class='text-danger'><?php echo "This Service Budget is set as temporary because the data; <b>" . $tempStatus . "</b>" . $xxx . " not yet. Please complete it at the next opportunity in the CRM application."; ?></span>
												</div>
											</div>
										<?php
										}
										?>
										<input type="hidden" name="temporary" id="temporary" value="<?php echo $tempStatus != "" ? 1 : 0; ?>">

									</div>
									<div class="col-lg-6 mb-5">
										<label for="inputCID3" class="col-sm-12 alert alert-secondary"><b>Setup Project</b></label>
										<div class="row mb-3">
											<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Version</label>
											<div class="col-sm-3">
												<div class="form-check-input-inline">
													<input class="form-control form-control-sm" type="text" value="<?php echo ($ver > 0) ? $dsb['version'] : 0; ?>" readonly>
												</div>
											</div>
											<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status</label>
											<div class="col-sm-3">
												<div class="form-check-input-inline">
													<input class="form-control form-control-sm" type="text" value="<?php echo ($ver > 0) ? $dsb['status'] : "New"; ?>" readonly>
												</div>
											</div>
										</div>
										<div class="row mb-3">
											<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Project</label>
											<div class="col-sm-9">
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="newproject" id="newproject1" value="1" onchange="get_renewal(1);" <?php
														// echo ' checked '; 
														if ($ver > 0) {
															if ($dsb['newproject'] == 1) {
																echo ' checked ';
															}
														}
														?> <?php if ($permission == 'readonly') {
																echo 'disabled';
															} ?>>
													<label class="form-check-label" for="inlineRadio1">New</label>
												</div>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="newproject" id="newproject0" value="0" onchange="get_renewal(2);" <?php
														if ($ver > 0) {
															if ($dsb['newproject'] == 0) {
																echo ' checked ';
															}
														}
														?> <?php if ($permission == 'readonly') {
																echo 'disabled';
															} ?>>
													<label class="form-check-label" for="inlineRadio2">Renewal</label>
												</div>
											</div>
										</div>
										<div class="row mb-3" id="Renewal">
											<?php
											$msg = "This is specifically for project maintenance. The Previous Project Code is selected \r\nif the Service Budget is the Renewal of a previous project.";
											?>
											<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Previous Project Code <i class="fas fa-question-circle" data-bs-toggle="popover" data-bs-trigger="focus" title="<?php echo $msg; ?>"></i></label>
											<div class="col-sm-9">
												<select class="form-select form-select-sm" aria-label="form-select-sm example" name="previous_id" id="previous_id" <?php if ($permission == 'readonly') {
																																										echo 'disabled';
																																									} ?>>
													<option value="0">-------- Select Old Project --------</option>
													<?php
													$mysql = "SELECT `project_id`, `project_code`, `so_number`, `order_number`, `project_name` FROM `sa_trx_project_list` WHERE `create_date` >= '" . date("Y-m-d", strtotime("-5 year")) . "' AND LEFT(`project_code`, 6) = '" . substr($dsb['project_code'], 0, 6) . "' ORDER BY `project_code` DESC";
													$rsPreviousProject = $DTSB->get_sql($mysql);
													if ($rsPreviousProject[2] > 0) {
														do {
															?>
															<option value="<?php echo $rsPreviousProject[0]['project_id']; ?>" <?php
																if ($ver > 0) {
																	if ($dsb['previous_id'] != NULL && $rsPreviousProject[0]['project_id'] == $dsb['previous_id']) {
																		echo 'selected';
																	}
																}
																?>><?php echo $rsPreviousProject[0]['project_code'] . ' - ' . $rsPreviousProject[0]['order_number'] . ' - ' . $rsPreviousProject[0]['project_name']; ?></option>
															<?php
														} while ($rsPreviousProject[0] = $rsPreviousProject[1]->fetch_assoc());
													}
													?>
												</select>
											</div>
										</div>
										<div class="row mb-3">
											<?php
											$msg = "This section is defined by the sales type in CRM.\r\n";
											$msg .= "- Normal chioce if the sales type is one or more of following;\r\n";
											$msg .= "   Implementation, Maintenance, Extended Warranty and Lease/Rent.\r\n";
											$msg .= "- Sederhana/Non-Project choice if the sales type is Trade only.\r\n";
											$msg .= "- Full Outsourcing choice if the sales type is Outsourcing.";
											?>
											<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Type Service Budget <i class="fas fa-question-circle" data-bs-toggle="popover" data-bs-trigger="focus" title="<?php echo $msg; ?>"></i></label>
											<div class="col-sm-9">
												<div class="form-check form-check-inline">
													<!-- SERVICE BUDGET NORMAL -->
													<input class="form-check-input" type="radio" name="sbtype" id="sbtype1" value="1" onchange="sbtypex(1);" <?php
														$xxx = false;
														if ($ver > 0) {
															if (($dsb['sbtype'] == 1 && $ver > 0)) {
																echo ' checked ';
															}
														} else {
															if (
																strpos($dsb['sales_type'], "Maintenance") !== false ||
																strpos($dsb['sales_type'], "Implementation") !== false ||
																strpos($dsb['sales_type'], "Installation") !== false ||
																strpos($dsb['sales_type'], "Lease") !== false ||
																strpos($dsb['sales_type'], "Rent") !== false ||
																strpos($dsb['sales_type'], "Extended Warranty") !== false
															) {
																echo ' checked ';
																$xxx = true;
															}
														}
														?> <?php if ($permission == 'readonly') {
																echo 'disabled';
															} ?>>
													<?php
													$msg = "Created by a Presales Account with a project value above 200 million\r\n";
													$msg .= "and/or has services/extended warranty/services from the vendor.";
													?>
													<label class="form-check-label" for="inlineRadio1">Normal <i class="fas fa-question-circle" data-bs-toggle="popover" data-bs-trigger="focus" title="<?php echo $msg; ?>"></i></label>
												</div>
												<div class="form-check form-check-inline">
													<!-- SERVICE BUDGET SEDERHANA -->
													<input class="form-check-input" type="radio" name="sbtype" id="sbtype0" value="0" onchange="sbtypex(0);" <?php
														if ($ver > 0) {
															if ($dsb['sbtype'] == 0) {
																echo ' checked ';
															}
														} else 
													if ($xxx == false) {
															echo ' checked ';
														}
														?> <?php if ($permission == 'readonly') {
															echo 'disabled';
														} ?>>
													<?php
													$msg = "Created by Sales Account with project value below 200 million\r\n";
													$msg .= "and/or product only."
													?>
													<label class="form-check-label" for="inlineRadio2">Sederhana/Non-Project <i class="fas fa-question-circle" data-bs-toggle="popover" data-bs-trigger="focus" title="<?php echo $msg; ?>"></i></label>
												</div>
												<div class="form-check form-check-inline">
													<!-- SERVICE BUDGET FULL OUTSOURCING -->
													<input class="form-check-input" type="radio" name="sbtype" id="sbtype2" value="2" onchange="sbtypex(2);" <?php
														if ($ver > 0) {
															if ($dsb['sbtype'] == 2 && $ver > 0) {
																echo ' checked ';
															}
														} else {
															if (strpos($dsb['sales_type'], "Outsourcing") !== false) {
																echo ' checked ';
															}
														}
														?> <?php if ($permission == 'readonly') {
															echo 'disabled';
														} ?>>
													<?php
													$msg = "Created by a Presales Account with a project value above 200 million\r\n";
													$msg .= "and/or has services/extended warranty/services from the vendor.\r\n";
													$msg .= "Work is performed by outsourcing without involvement of Mastersystem.";
													?>
													<label class="form-check-label" for="inlineRadio2">Full Outsourcing <i class="fas fa-question-circle" data-bs-toggle="popover" data-bs-trigger="focus" title="<?php echo $msg; ?>"></i></label>
												</div>
											</div>
										</div>
										<?php
										if ($ver > 0) {
											$bundlingexp = explode(";", $dsb['bundling']);
										}
										?>
										<div class="row mb-3" id="bundling_project">
											<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Bundling Project <i class="fas fa-question-circle" data-bs-toggle="popover" data-bs-trigger="focus" title="This section is defined by the sales type in CRM."></i></label>
											<div class="col-sm-9">
												<div class="form-check">
													<input class="form-check-input" type="checkbox" name="bundling1" id="i_bundling" value="1" <?php
														if ($ver > 0) {
															for ($j = 0; $j < count($bundlingexp); $j++) {
																if ($bundlingexp[$j] == 1) {
																	echo ' checked ';
																}
															}
														} else {
															if (strpos($dsb['sales_type'], "Implementation") !== false || strpos($dsb['sales_type'], "Installation") !== false) {
																echo ' checked ';
															}
														}
														?> <?php if ($permission == 'readonly') {
															echo 'disabled';
														} ?>>
													<label class="form-check-label" for="inlineRadio1">Implementation</label>
													<input type="hidden" name="bundling10" value="<?php
														if ($ver > 0) {
															echo substr($dsb['bundling'], 0, 1) == '1' ? '1' : '0';
														} else {
															echo '0';
														}
														?>">
												</div>

												<div class="form-check">
													<input class="form-check-input" type="checkbox" name="bundling2" id="m_bundling" value="2" <?php
														if ($ver > 0) {
															for ($j = 0; $j < count($bundlingexp); $j++) {
																if ($bundlingexp[$j] == 2) {
																	echo ' checked ';
																}
															}
														} else {
															if (strpos($dsb['sales_type'], "Maintenance") !== false) {
																echo ' checked ';
															}
														}
														?> <?php if ($permission == 'readonly') {
															echo 'disabled';
														} ?>>
													<label class="form-check-label" for="inlineRadio2">Maintenance</label>
													<input type="hidden" name="bundling20" value="<?php
														if ($ver > 0) {
															echo strpos($dsb['bundling'], '2') != '' ? '2' : '0';
														} else {
															echo '0';
														}
														?>">
												</div>

												<div class="form-check">
													<input class="form-check-input" type="checkbox" name="bundling3" id="w_bundling" value="3" <?php
														if ($ver > 0) {
															for ($j = 0; $j < count($bundlingexp); $j++) {
																if ($bundlingexp[$j] == 3) {
																	echo ' checked ';
																}
															}
														} else {
															if (strpos($dsb['sales_type'], "Extended") !== false) {
																echo ' checked ';
															}
														}
														if ($permission == 'readonly') {
															echo ' disabled';
														}
														?>>
													<?php
													$msg = "a. Extended Warranty is filled only if the Warranty purchased is\r\n";
													$msg .= "    separate from the goods or is in the form of renewal maintenance.\r\n";
													$msg .= "b. Subscriptions are included in the Product category, not extended\r\n";
													$msg .= "    warranties."
													?>
													<label class="form-check-label" for="inlineRadio2">Extended Warranty <i class="fas fa-question-circle" data-bs-toggle="popover" data-bs-trigger="focus" title="<?php echo $msg; ?>"></i></label>
													<input type="hidden" name="bundling30" value="<?php
														if ($ver > 0) {
															echo strpos($dsb['bundling'], '3') != '' ? '3' : '0';
														} else {
															echo '0';
														}
														?>">
												</div>
												<div class="form-check">
													<input class="form-check-input" type="checkbox" name="bundling4" id="r_bundling" value="1" <?php
														if ($ver > 0) {
															for ($j = 0; $j < count($bundlingexp); $j++) {
																if ($bundlingexp[$j] == 4) {
																	echo ' checked ';
																}
															}
														} else {
															if (strpos($dsb['sales_type'], "Lease") !== false || strpos($dsb['sales_type'], "Rent") !== false) {
																echo ' checked ';
															}
														}
														if ($permission == 'readonly') {
															echo ' disabled';
														}
														?>>
													<label class="form-check-label" for="inlineRadio1">Lease/Rent</label>
													<input type="hidden" name="bundling40" value="<?php
														if ($ver > 0) {
															echo substr($dsb['bundling'], 0, 1) == '4' ? '4' : '0';
														} else {
															echo '0';
														}
														?>">
												</div>
											</div>
										</div>

										<div class="row mb-3" id="multiyearsy">
											<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Multi Years</label>
											<div class="col-sm-9">
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="multiyears" id="multiyears0" value="1" onchange="change_multiyears(1);" <?php
														if ($ver > 0) {
															if ($dsb['multiyears'] == 1) {
																echo ' checked ';
															}
														}
														if ($permission == 'readonly') {
															echo ' disabled';
														}
														?>>
													<label class="form-check-label" for="inlineRadio1">Yes</label>
												</div>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="multiyears" id="multiyears1" value="0" onchange="change_multiyears(2);" <?php
														if ($ver > 0) {
															if ($dsb['multiyears'] == 0) {
																echo ' checked ';
															}
														} else {
															echo ' checked ';
														}
														if ($permission == 'readonly') {
															echo 'disabled';
														}
														?>>
													<label class="form-check-label" for="inlineRadio2">No</label>
												</div>
											</div>
										</div>
										<div class="row mb-3">
											<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Sub-Project</label>
											<div class="col-sm-9">
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="subproject" id="subproject1" value="1" onchange="sub_project(1);" <?php echo 'checked'; ?> <?php if ($permission == 'readonly') {
															echo 'disabled';
														} ?>>
													<label class="form-check-label" for="inlineRadio1">Yes</label>
												</div>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="subproject" id="subproject0" value="0" onchange="sub_project(2);" <?php
														if ($ver > 0) {
															if ($dsb['subproject'] == 0) {
																echo ' checked ';
															}
														} else {
															echo ' checked ';
														}
														if ($permission == 'readonly') {
															echo ' disabled';
														}
														?>>
													<label class="form-check-label" for="inlineRadio2">No</label>
												</div>
											</div>
										</div>
										<div class="row mb-3" id="subproject">
											<?php
											$msg = "The Parent Project Code is selected if the Service Budget is an additional project.";
											?>
											<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Parent Project Code <i class="fas fa-question-circle" data-bs-toggle="popover" data-bs-trigger="focus" title="<?php echo $msg; ?>"></i></label>
											<div class="col-sm-9">
												<select class="form-select form-select-sm" aria-label="form-select-sm example" name="parent_id" id="parent_id" <?php if ($permission == 'readonly') {
														echo 'disabled';
													} ?>>
													<option value="0">-------- Select Parent Project --------</option>
													<?php
													$mysql = "SELECT `project_id`, `project_code`, `so_number`, `order_number`, `project_name` FROM `sa_trx_project_list` WHERE `create_date` >= '" . date("Y-m-d", strtotime("-5 year")) . "' AND LEFT(`project_code`, 6) = '" . substr($dsb['project_code'], 0, 6) . "' ORDER BY `project_code` DESC";
													$rsParentProjects = $DTSB->get_sql($mysql);
													if ($rsParentProjects[2] > 0) {
														do {
															?>
															<option value="<?php echo $rsParentProjects[0]['project_id']; ?>" <?php
																if ($ver > 0) {
																	if ($dsb['parent_id'] != NULL && $rsParentProjects[0]['project_id'] == $dsb['parent_id']) {
																		echo 'selected';
																	}
																}
																?>><?php echo $rsParentProjects[0]['project_code'] . ' - ' . $rsParentProjects[0]['order_number'] . ' - ' . $rsParentProjects[0]['project_name']; ?></option>
															<?php
														} while ($rsParentProjects[0] = $rsParentProjects[1]->fetch_assoc());
													}
													?>
												</select>
											</div>
										</div>
									</div>

									<div class="col-lg-6">
										<div class="" id="official">
											<label for="inputCID3" class="col-sm-12 alert alert-secondary"><b>Officials</b></label>
											<div class="row mb-3">
												<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Pre-Sales Account</label>
												<div class="col-sm-9">
													<select class="form-select form-select-sm" aria-label="form-select-sm example" name="ps_account" id="ps_account" <?php if ($permission == 'readonly') {
															echo 'disabled';
														} ?>>
														<option value="0">-------- Select Pre-Sales Account --------</option>
														<?php
														// $mysql =
															// "SELECT `employee_name`, `employee_email`, `organization_name` 
															// FROM `sa_view_employees` 
															// WHERE (
															// 	`job_structure` LIKE '%Presales Account%' 
															// 	OR `employee_name` LIKE 'Prisanto Dimas Jayaputra'
															// 	OR `employee_name` LIKE 'Muhammad Ardiansyah'
															// 	OR `employee_name` LIKE 'Farizi Arya Putra'
															// 	OR `employee_name` LIKE 'Bayu Santoso'
															// 	OR `employee_name` LIKE 'Fathan Hidayat'
															// 	OR `employee_name` LIKE 'Wahyudin Djohan'
															// 	OR `employee_name` LIKE 'Abdul Aziz'
															// 	OR `employee_name` LIKE 'Betseba Levyana Ceriaroselina'
															// 	OR `employee_name` LIKE 'Muhammad Kukuh Andifa'
															// 	OR `employee_name` LIKE 'Oszi Fitriyanto'
															// 	OR `employee_name` LIKE 'Imam Nawawi'
															// 	OR `employee_name` LIKE 'Arga Sotarduga Yeremia.S'
															// 	OR `employee_name` LIKE 'Rakhmadian Purnama'
															// 	OR `employee_name` LIKE 'Muhammad Dicky Hans Setiawan'
															// 	OR `employee_name` LIKE 'Adhitya Reza Pratama'
															// 	OR `employee_name` LIKE 'Oki Akbar Ramadhan'
															// 	OR `employee_name` LIKE 'Iqro Firmansyah'
															// 	OR `employee_name` LIKE 'Arfend Atma Maulana Khalifa'
															// 	OR `employee_name` LIKE 'Bima Ifa Ristiyandi'
															// 	OR `employee_name` LIKE 'Respati Prayoga'
															// 	OR `employee_name` LIKE 'Muhammad Farkhan Khoir'
															// 	OR `employee_name` LIKE 'Galih Putro Dwi Setyo'
															// 	OR `employee_name` LIKE 'Algie Putra Handaya'
															// 	OR `employee_name` LIKE 'Agung Siddiq Ashari'
															// 	OR `employee_name` LIKE 'Setyanto Pitoyo Haddade'
															// 	)
															// 	AND `resign_date` IS NULL 
															// ORDER BY `employee_name` ASC ";
															$mysql = 
															"SELECT `a`.`employee_name`, `a`.`employee_email`, `a`.`organization_name` 
															FROM `sa_md_hcm`.`sa_view_employees` `a` 
															WHERE
																	(`a`.`job_structure` LIKE '%Presales Account%' OR
																`a`.`employee_email` IN (SELECT `b`.`employee_email` FROM `sa_ps_service_budgets`.`sa_mst_presales_list` `b` WHERE `b`.`presales_type` = 'Presales Account'))
																	AND `a`.`resign_date` IS NULL 
															ORDER BY `a`.`employee_name` ASC ";
														$rsPresales = $DBHCM->get_sql($mysql);
														if ($rsPresales[2] > 0) {
															do {
														?>
															<option value="<?php echo $rsPresales[0]['employee_email']; ?>" <?php
																if ($ver > 0) {
																	if ($dsb['parent_id'] != NULL && $rsPresales[0]['employee_email'] == $dsb['ps_account']) {
																		echo 'selected';
																	}
																}
																?>><?php echo $rsPresales[0]['employee_name'] . ' - ' . $rsPresales[0]['organization_name']; ?></option>
														<?php
															} while ($rsPresales[0] = $rsPresales[1]->fetch_assoc());
														}
														?>
													</select>
												</div>
											</div>
											<div class="row mb-3">
												<label for="inputCID3" class="col-form-label col-form-label-sm">Pre-Sales Technical</label>
												<div class="col-sm-5">
													<select multiple="multiple" class="form-select form-select-sm option1" name="ps_technical[]" id="ps_technical" <?php if ($permission == 'readonly') {
															echo 'disabled';
														} ?>>
														<?php
														// $mysql = "SELECT `employee_name`, `employee_email`, `organization_name` FROM `sa_view_employees` 
														// WHERE 
														// 	`resign_date` IS NULL AND
														// 	(
														// 		`organization_name` LIKE '%Presales%' OR
														// 		`employee_name` LIKE 'Rendy Kharisma%' OR
														// 		`employee_name` LIKE 'Nur Azizah%' OR
														// 		`employee_name` LIKE 'Windhu Syaputra%' OR
														// 		`employee_name` LIKE 'Ulia Warman%' OR
														// 		`employee_name` LIKE 'Windhu Syaputra%' OR
														// 		`employee_name` LIKE 'Dede Wahyu%' OR
														// 		`employee_name` LIKE 'Akhmad Zaki Alsafi%' OR
														// 		`employee_name` LIKE 'Ancer Afriono%' OR
														// 		`employee_name` LIKE 'Andreas Johansen%' OR
														// 		`employee_name` LIKE 'Rendhika Ch Golmen BN%' OR
														// 		`employee_name` LIKE 'Ryanno Lukman%' OR
														// 		`employee_name` LIKE 'Deni Hardiansyah%' OR
														// 		`employee_name` LIKE 'Rahseto Ajie Basuki%' OR
														// 		`employee_name` LIKE 'Gunawan%' OR
														// 		`employee_name` LIKE 'Fajar Pambudi Satiyo%' OR
														// 		`employee_name` LIKE 'Muhammad Faisal Irsyadi%' OR
														// 		`employee_name` LIKE 'Faris Aulia Ramadhan%' OR
														// 		`employee_name` LIKE 'Ardi Haris%' OR
														// 		`employee_name` LIKE 'Muhamad Rafif Hadi Kusmawan%' OR
														// 		`employee_name` LIKE 'Rio Kristian%' OR
														// 		`employee_name` LIKE 'Muhammad Uwais Al Qarni%' OR
														// 		`employee_name` LIKE 'Alif Famela Azzahra%' OR
														// 		`employee_name` LIKE 'Adil Aldianto Nooril%' OR
														// 		`employee_name` LIKE 'Ruli Handrio%' OR
														// 		`employee_name` LIKE 'Herdiman Eka Wijaya%' OR
														// 		`employee_name` LIKE 'Novriadi, ST%' OR
														// 		`employee_name` LIKE 'Wiradharma Gunawan%' OR
														// 		`employee_name` LIKE 'FX Ferdinand%' OR
														// 		`employee_name` LIKE 'Eko Jatmiko' OR
														// 		`employee_name` LIKE 'Jingga Prabaswara' OR
														// 		`employee_name` LIKE 'Fernando Wangsa' OR
														// 		`employee_name` LIKE 'Sumarto Santosa' OR
														// 		`employee_name` LIKE 'Ade Koswara' OR
														// 		`employee_name` LIKE 'Setyanto Pitoyo Haddade'
														// 	)
														// ORDER BY `job_level` ASC ";
														$mysql = "SELECT `a`.`employee_name`, `a`.`employee_email`, `a`.`organization_name` 
														FROM `sa_md_hcm`.`sa_view_employees` `a` 
														WHERE
																(`a`.`organization_name` LIKE '%Presales%' OR
																`a`.`employee_email` IN (SELECT `b`.`employee_email` FROM `sa_ps_service_budgets`.`sa_mst_presales_list` `b` WHERE `b`.`presales_type` = 'Presales Engineer'))
																AND `a`.`resign_date` IS NULL 
														ORDER BY `a`.`employee_name` ASC ";
														$rsPresales = $DBHCM->get_sql($mysql);
														if ($rsPresales[2] > 0) {
															do {
														?>
																<option value="<?php echo $rsPresales[0]['employee_name'] . "<" . $rsPresales[0]['employee_email'] . ">"; ?>"><?php echo $rsPresales[0]['employee_name']; ?></option>
														<?php
															} while ($rsPresales[0] = $rsPresales[1]->fetch_assoc());
														}
														?>
													</select>
													<input type="hidden" class="form-control" name="ps_technicalx" id="ps_technicalx"></textarea>
												</div>
												<div class="col-sm-2">
													<div class="row">
														<div class="btn btn-secondary btn-sm mb-1 <?php echo $EditStatus ? "" : "disabled"; ?>" onclick="AddMySelect();">Add</div>
														<div class="btn btn-secondary btn-sm mb-1 <?php echo $EditStatus ? "" : "disabled"; ?>" onclick="RemoveMySelect();">Remove</div>
													</div>
												</div>
												<div class="col-sm-5">
													<select multiple="multiple" name="psSelected" id="psSelected" class="form-select form-select-sm option2" <?php if ($permission == 'readonly') {
															echo 'disabled';
														} ?>>
														<?php
														if($dsb['ps_technical']!=null)
														{
														$psExplode = explode("; ", $dsb['ps_technical']);
														for ($i = 0; $i < count($psExplode) - 1; $i++) {
														?>
															<option value="<?php echo $psExplode[$i]; ?>" selected><?php echo $psExplode[$i]; ?></option>
														<?php
														}
														}
														?>
													</select>
												</div>
												<script type="text/javascript">
													function sort_select(psSelected) {
														var select = document.getElementById(psSelected);
														var options = Array.from(select.options);
														options.sort(function(a, b) {
															if (a.textContent < b.textContent) {
																return -1;
															} else
															if (a.textContent > b.textContent) {
																return 1;
															} else {
																return 0;
															}
														});
														select.innerHTML = '';
														options.forEach(function(option) {
															select.add(option);
														});

														var textarea = document.getElementById("ps_technicalx");
														textarea.value = "";
														var psSelectedx = document.getElementById("psSelected");
														var options = Array.from(psSelectedx.options);
														options.forEach(function(xxx) {
															textarea.value += xxx.value + "; ";
														});
													}
													sort_select("ps_technical");

													function AddMySelect() {
														// Add psSelected
														mySelected = document.getElementById("ps_technical");
														if (mySelected.value != "") {
															var psSelected = $("#psSelected");
															psSelected.append($("<option selected></option>").val(mySelected.value).html(mySelected.value));
															// remove ps_technical
															var psSelected = $("select#ps_technical option[value='" + mySelected.value + "']");
															psSelected.remove();
															sort_select("psSelected");
														}
													}

													function RemoveMySelect() {
														// add ps_technical
														mySelected = document.getElementById("psSelected");
														if (mySelected.value != "") {
															var psSelected = $("#ps_technical");
															psSelected.append($("<option></option>").val(mySelected.value).html(mySelected.value));
															// Remove psSelected
															mySelected = document.getElementById("psSelected");
															var psSelected = $("select#psSelected option[value='" + mySelected.value + "']");
															psSelected.remove();
															sort_select("ps_technical");
														}
													}
												</script>
											</div>
										</div>

										<div class="mt-5" id="multiyearsx">
											<label for="inputCID3" class="col-sm-12 alert alert-secondary" title="Project Implementasi dan Managed Service (Durasi lebih dari 1 tahun)."><b>Multi Years</b></label>
											<div class="card-body">
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Duration</label>
													<div class="col-sm-2">
														<input type="text" class="form-control form-control-sm" id="duration" value="<?php if ($ver > 0) {
																echo $dsb['duration'];
															} ?>" name="duration" style="text-align: right" <?php echo $permission; ?>>
													</div>
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">years</label>
												</div>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Contract Type</label>
													<div class="col-sm-8">
														<select class="form-select form-select-sm" aria-label=".form-select-sm example" name="contract_type" id="contract_type" onchange="change_contract_type();" <?php if ($permission == 'readonly') {
																echo 'disabled';
															} ?>>
															<option value="Kontrak Biasa" <?php if (($ver > 0) && ($dsb['contract_type'] == 'Kontrak Biasa')) {
																	echo 'selected';
																} ?>>Kontrak Biasa</option>
															<option value="Kontrak Payung" <?php if (($ver > 0) && ($dsb['contract_type'] == 'Kontrak Payung')) {
																	echo 'selected';
																} ?>>Kontrak Payung</option>
														</select>
													</div>
												</div>
												<div class="row mb-2">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Work Order</label>
													<div class="col-sm-3">
														<?php
														global $DTSB;
														$tblname = "mst_type_of_service";
														$condition = "service_type=4 AND blocked=0";
														$tos = $DTSB->get_data($tblname, $condition);
														$dtos = $tos[0];
														$qtos = $tos[1];
														$tosidexp = "";
														if ($ver > 0 && $dsb['wo_type'] <> "") {
															$tosidexp = explode(";", $dsb['wo_type']);
														}
														$i = 0;
														do {
														?>
															<div class="form-check">
																<input class="form-check-input" type="checkbox" name="wo_type[<?php echo $i; ?>]" id="wo_type<?php echo $i; ?>" value="<?php echo $dtos['tos_id']; ?>" <?php
																	if ($ver > 0 && $dsb['wo_type'] <> "") {
																		for ($j = 0; $j < count($tosidexp); $j++) {
																			if ($tosidexp[$j] == $dtos['tos_id']) {
																				echo ' checked ';
																			}
																		}
																	} ?> <?php if ($permission == 'readonly') {
																				echo 'disabled';
																			} ?>>
																<label class="form-check-label"><?php echo $dtos['tos_name']; ?></label>
															</div>
														<?php
															$i++;
														} while ($dtos = $qtos->fetch_assoc());
														?>
													</div>
												</div>
											</div>
										</div>
									</div>

									<div class="col-lg-6">
										<div class="" id="agreed_price">
											<label [for="inputCID3" class="col-sm-12 alert alert-secondary"><b>Agreed Price</b></label>
											<div class="row mb-3">
												<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Band</label>
												<div class="col-sm-9">
													<?php
													$band[0] = "Band-0 : The project is fully assigned to outsource";
													$band[1] = "Band-1";
													$band[2] = "Band-2";
													$band[3] = "Band-3";
													$band[4] = "Band-4";
													$band[5] = "Band-5";
													$band[6] = "Band-6";
													for ($i = 1; $i < 7; $i++) {
													?>
														<div class="form-check form-check-inline" title="<?php echo $band[$i]; ?>">
															<input class="form-check-input" type="radio" name="band" id="band<?php echo $i; ?>" value="<?php echo $i; ?>" <?php if ($ver > 0) {
																	if ($dsb['band'] == $i) {
																		echo 'checked';
																	}
																} else {
																	echo 'checked';
																} ?>>
															<label class="form-check-label" for="inlineRadio1"><?php echo $i; ?></label>
														</div>
													<?php
													}
													?>
												</div>
											</div>
											<div class="row mb-1">
												<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm"></label>
												<label for="inputCID4" class="col-sm-4 col-form-label col-form-label-sm" id="i_agreed_0">Implementation</label>
												<label for="inputCID4" class="col-sm-4 col-form-label col-form-label-sm" id="m_agreed_0">Maintenance</label>
											</div>
											<div class="row mb-3">
												<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">PO Customer</label>
												<div class="col-sm-4" id="i_agreed_1">
													<input type="text" class="form-control form-control-sm" id="i_price_copy" style="text-align: right;" readonly>
												</div>
												<div class="col-sm-4" id="m_agreed_1">
													<input type="text" class="form-control form-control-sm" id="m_price_copy" style="text-align: right" readonly>
												</div>
											</div>
											<div class="row mb-3">
												<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Price List</label>
												<div class="col-sm-4" id="i_agreed_2">
													<input type="text" class="form-control form-control-sm" id="i_price_list" name="i_price_list" value="<?php if ($ver > 0 && $timplement > 0) {
															echo $dimplement['implementation_price_list'];
														} ?>" style="text-align: right;" onchange="band_change_v2(1);">
												</div>
												<div class="col-sm-4" id="m_agreed_2">
													<input type="text" class="form-control form-control-sm" id="m_price_list" name="m_price_list" value="<?php if ($ver > 0 && $tmaintenance > 0) {
															echo $dmaintenance['implementation_price_list'];
														} ?>" style="text-align: right;" onchange="band_change_v2(2);">
												</div>
											</div>
											<div class="row mb-3">
												<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Agreed Price</label>
												<div class="col-sm-4" id="i_agreed_3">
													<input type="text" class="form-control form-control-sm" id="i_agreed_price" name="i_agreed_price" value="<?php if ($ver > 0 && $timplement > 0) {
															echo number_format($dimplement['agreed_price'], 2);
														} ?>" style="text-align: right;" <?php echo $permission; ?> onchange="band_change_v2(3);">
												</div>
												<div class="col-sm-4" id="m_agreed_3">
													<input type="text" class="form-control form-control-sm" id="m_agreed_price" name="m_agreed_price" value="<?php if ($ver > 0 && $tmaintenance > 0) {
															echo number_format($dmaintenance['agreed_price'], 2);
														} ?>" style="text-align: right" <?php echo $permission; ?> onchange="band_change_v2(4);">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- TAB Project Solution -->
					<div class="tab-pane fade" id="ProjectSolution" role="tabpanel" aria-labelledby="projectsolution-tab">
						<div class="card shadow mb-4">
							<!-- Card Body -->
							<div class="row">
								<div class="col-lg-6">
									<div class="card-body">
										<div class="row mb-3 card-header">
											<label for="inputCID3" class="col-sm-6 col-form-label">Project Solution</label>
											<label for="inputCID3" class="col-sm-3 col-form-label">Product (%)</label>
											<label for="inputCID3" id="labelservice" class="col-sm-3 col-form-label">Services (%)</label>
										</div>
										<div class="row mb-3">
											<label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Data Center & Cloud Infrastructure</label>
											<div class="col-sm-3">
												<input type="text" class="form-control form-control-sm" id="DCCIP" name="DCCIP" value="<?php if ($ver > 0 && $tpsolution > 0) {
														echo $psol['DCCI']['product'];
													} ?>" style="text-align: right" onchange="s_change(1);" <?php echo $permission; ?>>
											</div>
											<div class="col-sm-3">
												<input type="text" class="form-control form-control-sm" id="DCCIS" name="DCCIS" value="<?php if ($ver > 0 && $tpsolution > 0) {
														echo $psol['DCCI']['services'];
													} ?>" style="text-align: right" onchange="s_change(2);" <?php echo $permission; ?>>
											</div>
										</div>
										<div class="row mb-3">
											<label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Enterprise Collaboration</label>
											<div class="col-sm-3">
												<input type="text" class="form-control form-control-sm" id="ECP" name="ECP" value="<?php if ($ver > 0 && $tpsolution > 0) {
														echo $psol['EC']['product'];
													} ?>" style="text-align: right" onchange="s_change(3);" <?php echo $permission; ?>>
											</div>
											<div class="col-sm-3">
												<input type="text" class="form-control form-control-sm" id="ECS" name="ECS" value="<?php if ($ver > 0 && $tpsolution > 0) {
														echo $psol['EC']['services'];
													} ?>" style="text-align: right" onchange="s_change(4);" <?php echo $permission; ?>>
											</div>
										</div>
										<div class="row mb-3">
											<label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Big Data & Analytics</label>
											<div class="col-sm-3">
												<input type="text" class="form-control form-control-sm" id="BDAP" name="BDAP" value="<?php if ($ver > 0 && $tpsolution > 0) {
														echo $psol['BDA']['product'];
													} ?>" style="text-align: right" onchange="s_change(5);" <?php echo $permission; ?>>
											</div>
											<div class="col-sm-3">
												<input type="text" class="form-control form-control-sm" id="BDAS" name="BDAS" value="<?php if ($ver > 0 && $tpsolution > 0) {
														echo $psol['BDA']['services'];
													} ?>" style="text-align: right" onchange="s_change(6);" <?php echo $permission; ?>>
											</div>
										</div>
										<div class="row mb-3">
											<label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Digital Business Management</label>
											<div class="col-sm-3">
												<input type="text" class="form-control form-control-sm" id="DBMP" name="DBMP" value="<?php if ($ver > 0 && $tpsolution > 0) {
														echo $psol['DBM']['product'];
													} ?>" style="text-align: right" onchange="s_change(7);" <?php echo $permission; ?>>
											</div>
											<div class="col-sm-3">
												<input type="text" class="form-control form-control-sm" id="DBMS" name="DBMS" value="<?php if ($ver > 0 && $tpsolution > 0) {
														echo $psol['DBM']['services'];
													} ?>" style="text-align: right" onchange="s_change(8);" <?php echo $permission; ?>>
											</div>
										</div>
										<div class="row mb-3">
											<label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Adaptive Security Architecture</label>
											<div class="col-sm-3">
												<input type="text" class="form-control form-control-sm" id="ASAP" name="ASAP" value="<?php if ($ver > 0 && $tpsolution > 0) {
														echo $psol['ASA']['product'];
													} ?>" style="text-align: right" onchange="s_change(9);" <?php echo $permission; ?>>
											</div>
											<div class="col-sm-3">
												<input type="text" class="form-control form-control-sm" id="ASAS" name="ASAS" value="<?php if ($ver > 0 && $tpsolution > 0) {
														echo $psol['ASA']['services'];
													} ?>" style="text-align: right" onchange="s_change(10);" <?php echo $permission; ?>>
											</div>
										</div>
										<div class="row mb-3">
											<label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Service Provider</label>
											<div class="col-sm-3">
												<input type="text" class="form-control form-control-sm" id="SPP" name="SPP" value="<?php if ($ver > 0 && $tpsolution > 0) {
														echo $psol['SP']['product'];
													} ?>" style="text-align: right" onchange="s_change(11);" <?php echo $permission; ?>>
											</div>
											<div class="col-sm-3">
												<input type="text" class="form-control form-control-sm" id="SPS" name="SPS" value="<?php if ($ver > 0 && $tpsolution > 0) {
														echo $psol['SP']['services'];
													} ?>" style="text-align: right" onchange="s_change(12);" <?php echo $permission; ?>>
											</div>
										</div>
										<div class="row mb-3 card-footer">
											<label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Total (100%)</label>
											<div class="col-sm-3">
												<input type="text" class="form-control form-control-sm" id="s_total_product" name="s_total_product" value="<?php if ($ver > 0 && $tpsolution > 0) {
														echo $totalproductsolution;
													} ?>" style="text-align: right" readonly>
											</div>
											<div class="col-sm-3">
												<input type="text" class="form-control form-control-sm" id="s_total_service" name="s_total_service" value="<?php if ($ver > 0 && $tpsolution > 0) {
														echo $totalservicesolution;
													} ?>" style="text-align: right" readonly>
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="card-body" id="sub-solution">
										<label for="inputCID3" class="col-sm-12 alert alert-secondary"><b>Sub-Solution</b></label>
										<div class="accordion" id="accordionSolution">
											<div class="accordion-item">
												<h2 class="accordion-header">
													<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
														Data Center & Cloud Infrastructure
													</button>
												</h2>
												<div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionSolution">
													<div class="accordion-body">
														<table class="display" style="width:100%">
															<thead>
																<tr>
																	<th class="text-center">Select</th>
																	<th>Solution Code</th>
																	<th>Solution Name</th>
																</tr>
															</thead>
															<tbody>
																<?php
																$DBWL = get_conn("WORKLOAD");
																$mysql =
																	"SELECT
																		`id`,
																		`project_solution_name`,
																		`project_solution_code`
																	FROM
																		`sa_workload_config`
																	WHERE
																		LEFT(`project_solution_name`,2)='DC'";
																$rsSolutions = $DBWL->get_sql($mysql);

																if ($rsSolutions[2] > 0) {
																	do {
																		$checked = check_subsolution($rsSolutions[0]['project_solution_code'], $project_id);
																?>
																		<tr>
																			<td class="text-center">
																				<div class="form-check">
																					<input type="checkbox" class="form-check-input SubSolution" name="subsolution[]" id="subsolution[]" value="<?php echo $rsSolutions[0]['project_solution_code']; ?>" <?php echo $checked ? "checked" : ""; ?>>
																				</div>
																			</td>
																			<td><?php echo $rsSolutions[0]['project_solution_code']; ?></td>
																			<td><?php echo $rsSolutions[0]['project_solution_name']; ?></td>
																		</tr>
																<?php
																	} while ($rsSolutions[0] = $rsSolutions[1]->fetch_assoc());
																}
																?>
															</tbody>
														</table>
													</div>
												</div>
											</div>
											<div class="accordion-item">
												<h2 class="accordion-header">
													<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
														Enterprise Collaboration
													</button>
												</h2>
												<div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionSolution">
													<div class="accordion-body">
														<table class="display" style="width:100%">
															<thead>
																<tr>
																	<th class="text-center">Select</th>
																	<th>Solution Code</th>
																	<th>Solution Name</th>
																</tr>
															</thead>
															<tbody>
																<?php
																$DBWL = get_conn("WORKLOAD");
																$mysql =
																	"SELECT
																		`id`,
																		`project_solution_name`,
																		`project_solution_code`
																	FROM
																		`sa_workload_config`
																	WHERE
																		LEFT(`project_solution_name`,2)='EC'";
																$rsSolutions = $DBWL->get_sql($mysql);

																if ($rsSolutions[2] > 0) {
																	do {
																		$checked = check_subsolution($rsSolutions[0]['project_solution_code'], $project_id);
																?>
																		<tr>
																			<td class="text-center">
																				<div class="form-check">
																					<input type="checkbox" class="form-check-input SubSolution" name="subsolution[]" value="<?php echo $rsSolutions[0]['project_solution_code']; ?>" <?php echo $checked ? "checked" : ""; ?>>
																				</div>
																			</td>
																			<td><?php echo $rsSolutions[0]['project_solution_code']; ?></td>
																			<td><?php echo $rsSolutions[0]['project_solution_name']; ?></td>
																		</tr>
																<?php
																	} while ($rsSolutions[0] = $rsSolutions[1]->fetch_assoc());
																}
																?>
															</tbody>
														</table>
													</div>
												</div>
											</div>
											<div class="accordion-item">
												<h2 class="accordion-header">
													<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
														Big Data & Analytics
													</button>
												</h2>
												<div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionSolution">
													<div class="accordion-body">
														<table class="display" style="width:100%">
															<thead>
																<tr>
																	<th class="text-center">Select</th>
																	<th>Solution Code</th>
																	<th>Solution Name</th>
																</tr>
															</thead>
															<tbody>
																<?php
																$DBWL = get_conn("WORKLOAD");
																$mysql =
																	"SELECT
																		`id`,
																		`project_solution_name`,
																		`project_solution_code`
																	FROM
																		`sa_workload_config`
																	WHERE
																		LEFT(`project_solution_name`,2)='BA'";
																$rsSolutions = $DBWL->get_sql($mysql);

																if ($rsSolutions[2] > 0) {
																	do {
																		$checked = check_subsolution($rsSolutions[0]['project_solution_code'], $project_id);
																?>
																		<tr>
																			<td class="text-center">
																				<div class="form-check">
																					<input type="checkbox" class="form-check-input SubSolution" name="subsolution[]" value="<?php echo $rsSolutions[0]['project_solution_code']; ?>" <?php echo $checked ? "checked" : ""; ?>>
																				</div>
																			</td>
																			<td><?php echo $rsSolutions[0]['project_solution_code']; ?></td>
																			<td><?php echo $rsSolutions[0]['project_solution_name']; ?></td>
																		</tr>
																<?php
																	} while ($rsSolutions[0] = $rsSolutions[1]->fetch_assoc());
																}
																?>
															</tbody>
														</table>
													</div>
												</div>
											</div>
											<div class="accordion-item">
												<h2 class="accordion-header">
													<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
														Digital Business Management
													</button>
												</h2>
												<div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionSolution">
													<div class="accordion-body">
														<table class="display" style="width:100%">
															<thead>
																<tr>
																	<th class="text-center">Select</th>
																	<th>Solution Code</th>
																	<th>Solution Name</th>
																</tr>
															</thead>
															<tbody>
																<?php
																$DBWL = get_conn("WORKLOAD");
																$mysql =
																	"SELECT
																		`id`,
																		`project_solution_name`,
																		`project_solution_code`
																	FROM
																		`sa_workload_config`
																	WHERE
																		LEFT(`project_solution_name`,2)='DB'";
																$rsSolutions = $DBWL->get_sql($mysql);

																if ($rsSolutions[2] > 0) {
																	do {
																		$checked = check_subsolution($rsSolutions[0]['project_solution_code'], $project_id);
																?>
																		<tr>
																			<td class="text-center">
																				<div class="form-check">
																					<input type="checkbox" class="form-check-input SubSolution" name="subsolution[]" value="<?php echo $rsSolutions[0]['project_solution_code']; ?>" <?php echo $checked ? "checked" : ""; ?>>
																				</div>
																			</td>
																			<td><?php echo $rsSolutions[0]['project_solution_code']; ?></td>
																			<td><?php echo $rsSolutions[0]['project_solution_name']; ?></td>
																		</tr>
																<?php
																	} while ($rsSolutions[0] = $rsSolutions[1]->fetch_assoc());
																}
																?>
															</tbody>
														</table>
													</div>
												</div>
											</div>
											<div class="accordion-item">
												<h2 class="accordion-header">
													<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
														Adaptive Security Architecture
													</button>
												</h2>
												<div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionSolution">
													<div class="accordion-body">
														<table class="display" style="width:100%">
															<thead>
																<tr>
																	<th class="text-center">Select</th>
																	<th>Solution Code</th>
																	<th>Solution Name</th>
																</tr>
															</thead>
															<tbody>
																<?php
																$DBWL = get_conn("WORKLOAD");
																$mysql =
																	"SELECT
																		`id`,
																		`project_solution_name`,
																		`project_solution_code`
																	FROM
																		`sa_workload_config`
																	WHERE
																		LEFT(`project_solution_name`,2)='SA'";
																$rsSolutions = $DBWL->get_sql($mysql);

																if ($rsSolutions[2] > 0) {
																	do {
																		$checked = check_subsolution($rsSolutions[0]['project_solution_code'], $project_id);
																?>
																		<tr>
																			<td class="text-center">
																				<div class="form-check">
																					<input type="checkbox" class="form-check-input SubSolution" name="subsolution[]" value="<?php echo $rsSolutions[0]['project_solution_code']; ?>" <?php echo $checked ? "checked" : ""; ?>>
																				</div>
																			</td>
																			<td><?php echo $rsSolutions[0]['project_solution_code']; ?></td>
																			<td><?php echo $rsSolutions[0]['project_solution_name']; ?></td>
																		</tr>
																<?php
																	} while ($rsSolutions[0] = $rsSolutions[1]->fetch_assoc());
																}
																?>
															</tbody>
														</table>
													</div>
												</div>
											</div>
											<div class="accordion-item">
												<h2 class="accordion-header">
													<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
														Service Provider
													</button>
												</h2>
												<div id="collapseSix" class="accordion-collapse collapse" data-bs-parent="#accordionSolution">
													<div class="accordion-body">
														<table class="display" style="width:100%">
															<thead>
																<tr>
																	<th class="text-center">Select</th>
																	<th>Solution Code</th>
																	<th>Solution Name</th>
																</tr>
															</thead>
															<tbody>
																<?php
																$DBWL = get_conn("WORKLOAD");
																$mysql =
																	"SELECT
																		`id`,
																		`project_solution_name`,
																		`project_solution_code`
																	FROM
																		`sa_workload_config`
																	WHERE
																		LEFT(`project_solution_name`,2)='SP'";
																$rsSolutions = $DBWL->get_sql($mysql);

																if ($rsSolutions[2] > 0) {
																	do {
																		$checked = check_subsolution($rsSolutions[0]['project_solution_code'], $project_id);
																?>
																		<tr>
																			<td class="text-center">
																				<div class="form-check">
																					<input type="checkbox" class="form-check-input SubSolution" name="subsolution[]" value="<?php echo $rsSolutions[0]['project_solution_code']; ?>" <?php echo $checked ? "checked" : ""; ?>>
																				</div>
																			</td>
																			<td><?php echo $rsSolutions[0]['project_solution_code']; ?></td>
																			<td><?php echo $rsSolutions[0]['project_solution_name']; ?></td>
																		</tr>
																<?php
																	} while ($rsSolutions[0] = $rsSolutions[1]->fetch_assoc());
																}
																?>
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- TAB Implementation -->
					<div class="tab-pane fade" id="Implementation" role="tabpanel" aria-labelledby="implementation-tab">
						<div class="card mb-4">
							<!-- Card Body -->
							<div class="card-body">
								<div class="row">
									<!-- Service catalogs -->
									<div class="col-lg-6">
										<label for="inputCID3" class="col-sm-12 alert alert-secondary"><b>Service Catalog</b></label>
										<div class="row mb-2">
											<label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Service Type</label>
											<div class="col-sm-2">
												<?php
												global $DTSB;
												$tblname = "mst_type_of_service";
												$condition = "service_type=1 AND blocked=0";
												$tos = $DTSB->get_data($tblname, $condition);
												$dtos = $tos[0];
												$qtos = $tos[1];
												$tosidexp = "";
												if ($ver > 0 && isset($dimplement['tos_id'])) {
													$tosidexp = explode(";", $dimplement['tos_id']);
												}
												$i = 0;
												do {
												?>
													<div class="form-check">
														<input class="form-check-input" type="checkbox" name="i_tos_id[<?php echo $i; ?>]" id="i_tos_id<?php echo $i; ?>" value="<?php echo $dtos['tos_id']; ?>" <?php
															if ($ver > 0 && $tosidexp != "") {
																for ($j = 0; $j < count($tosidexp); $j++) {
																	if ($tosidexp[$j] == $dtos['tos_id']) {
																		echo ' checked ';
																	}
																}
															} ?> <?php if ($permission == 'readonly') {
																echo 'disabled';
															} ?>>
														<label class="form-check-label" for="inlineRadio1"><?php echo $dtos['tos_name']; ?></label>
													</div>
												<?php
													$i++;
												} while ($dtos = $qtos->fetch_assoc());
												?>
											</div>
										</div>
										<div class="row mb-2">
											<label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Project Category</label>
											<div class="col-sm-2">
												<select class="form-select form-select-sm" aria-label=".form-select-sm example" name="i_tos_category_id" <?php echo $permission; ?> <?php if ($permission == 'readonly') {
														echo 'disabled';
													} ?>>
													<option value="1" <?php if (($ver > 0 && $timplement > 0) && ("1" == $dimplement['tos_category_id'])) {
															echo 'selected';
														} ?>>High</option>
													<option value="2" <?php if (($ver > 0 && $timplement > 0) && ("2" == $dimplement['tos_category_id'])) {
															echo 'selected';
														} ?>>Medium</option>
													<option value="3" <?php if (($ver > 0 && $timplement > 0) && ("3" == $dimplement['tos_category_id'])) {
															echo 'selected';
														} ?>>Standard</option>
												</select>
											</div>
										</div>
										<div class="row mb-5">
											<label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Estimation Project Duration</label>
											<div class="col-sm-2">
												<input type="text" class="form-control form-control-sm" id="i_project_estimation" name="i_project_estimation" value="<?php if ($ver > 0 && $timplement > 0) {
														echo $dimplement['project_estimation'];
													} ?>" style="text-align: right;" <?php echo $permission; ?>>
											</div>
											<div class="col-sm-3">
												<select class="form-select form-select-sm" aria-label=".form-select-sm example" name="i_project_estimation_id" <?php echo $permission; ?> <?php if ($permission == 'readonly') {
														echo 'disabled';
													} ?>>
													<option value="1" <?php if (($ver > 0 && $timplement > 0) && ("1" == $dimplement['project_estimation_id'])) {
															echo 'selected';
														} ?>>Days</option>
													<option value="2" <?php if (($ver > 0 && $timplement > 0) && ("2" == $dimplement['project_estimation_id'])) {
															echo 'selected';
														} ?>>Months</option>
													<option value="3" <?php if (($ver > 0 && $timplement > 0) && ("3" == $dimplement['project_estimation_id'])) {
															echo 'selected';
														} ?>>Years</option>
												</select>
											</div>
										</div>

										<label for="inputCID3" class="col-sm-12 alert alert-secondary"><b>Price</b></label>
										<div class="row mb-5">
											<div class="row mb-3">
												<label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Implementasi Price (sesuai PO/SPK)</label>
												<div class="col-sm-6">
													<input type="text" class="form-control form-control-sm" name="i_price" id="i_price" value="<?php if ($ver > 0 && $timplement > 0) {
															echo $dimplement['implementation_price'];
														} ?>" style="text-align: right;" onchange="i_change_price(1);" <?php echo $permission; ?>>
												</div>
											</div>
										</div>

										<!-- Business Trips -->
										<div id="Business Trip">
											<label for="inputCID3" class="col-sm-12 alert alert-secondary"><b>Business Trip</b></label>
											<div class="row mb-3">
												<label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Total Locations</label>
												<div class="col-sm-6">
													<input type="text" class="form-control form-control-sm" id="inputBPDImplementationLocation" name="i_bpd_total_location" value="<?php if ($ver > 0 && $timplement > 0) {
															echo $dimplement['bpd_total_location'];
														} ?>" style="text-align: right;" <?php echo $permission; ?>>
												</div>
											</div>
											<div class="row mb-3">
												<label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Description of Location</label>
												<div class="col-sm-6">
													<textarea class="form-control" id="i_bpd_description" name="i_bpd_description" rows="3" <?php echo $permission; ?>><?php if ($ver > 0 && $timplement > 0) {
															echo $dimplement['bpd_description'];
														} ?></textarea>
												</div>
											</div>
											<div class="row mb-3">
												<label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Subtotal Business Trip (IDR)</label>
												<div class="col-sm-6">
													<input type="text" class="form-control form-control-sm" id="i_bpd_price" name="i_bpd_price" value="<?php if ($ver > 0 && $timplement > 0) {
															echo number_format($dimplement['bpd_price'], 2);
														} ?>" style="text-align: right;" onchange="i_change_bpd(1);" <?php echo $permission; ?>>
												</div>
											</div>
										</div>

										<!-- Outsourcing -->
										<div id="i_outsourcing">
											<label for="inputCID3" class="col-sm-12 alert alert-secondary py-3 d-flex flex-row align-items-center justify-content-between mt-5">
												<b>Outsourcing Plan</b>
												<div class="align-items-right">
													<button type="button" class="btn btn-primary btn-sm" id="btnAdd_i_out" <?php echo $EditStatus ? "" : "disabled"; ?>>+</button>
												</div>
											</label>
											<div class="row fw-bold">
												<label for="inputCID3" class="col-sm-7 col-form-label col-form-label-sm">Description</label>
												<label for="inputCID3" class="col-sm-5 col-form-label col-form-label-sm">Price</label>
											</div>

											<?php
											$mysql = sprintf(
												"SELECT `addon_id`,`addon_title`, `addon_price` 
											FROM `sa_trx_addon`
											WHERE `project_id`=%s AND `service_type`=1",
												GetSQLValueString($project_id, "int")
											);
											$addon = $DTSB->get_sql($mysql);
											$TotalResourceImplementation = 0;
											if ($addon[2] > 0) {
												$i = 0;
												do {
											?>
													<div class="row mb-3 group" id="addon_i_out">
														<div class="col-lg-7">
															<input type="text" id="i_out_title[<?php echo $i; ?>]" name="i_out['title'][]" class="form-control form-control-sm" value="<?php echo $ver > 0 ? $addon[0]['addon_title'] : ""; ?>" <?php echo $EditStatus ? "" : "disabled"; ?>>
														</div>
														<div class="col-lg-4">
															<input type="text" id="i_out_price[<?php echo $i; ?>]" name="i_out['price'][]" class="form-control form-control-sm i_price" value="<?php echo $ver > 0 ? number_format($addon[0]['addon_price'], 2) : ""; ?>" style="text-align: right;" onchange="implementation_outsourcing_price(<?php echo $i; ?>);" <?php echo $EditStatus ? "" : "disabled"; ?>>
														</div>
														<div class="col-lg-1">
															<button id="btnRemove_i_out" type="button" class="btn btn-danger btn-sm" <?php echo $EditStatus ? "" : "disabled"; ?>>-</button>
														</div>
													</div>
											<?php
													$xxx = $ver > 0 ? $addon[0]['addon_price'] : 0;
													$TotalResourceImplementation += $xxx;
													$i++;
												} while ($addon[0] = $addon[1]->fetch_assoc());
											}
											?>

											<div id="Addon_i_out"></div>
											<hr />
											<div class="row mb-3">
												<label for="inputCID3" class="col-sm-7 col-form-label col-form-label-sm text-right fw-bold">Subtotal Outsourcing Plant (IDR)</label>
												<div class="col-sm-4">
													<input type="text" class="form-control form-control-sm" id="i_total_out_price" name="i_total_out_price" value="<?php echo number_format($TotalResourceImplementation, 2); ?>" style="text-align: right;" onchange="" readonly>
												</div>
											</div>
											<div class="row mb-5">
												<label for="inputCID3" class="col-sm-2 col-form-label col-form-label-sm">Note</label>
												<div class="col-sm-10">
													<textarea class="form-control" id="i_out_description" name="i_out_description" rows="3" <?php echo $permission; ?>><?php if ($ver > 0 && $dimplement > 0) {
															echo $dimplement['out_description'];
														} ?></textarea>
												</div>
											</div>
											<script>
												$(document).ready(function() {
													i1 = <?php echo $i; ?>;
													$("#btnAdd_i_out").click(function(btn) {
														var html = '';
														html += '<div class="row mb-3 group" id="addon_i_out">';
														html += '<div class="col-lg-7">';
														html += '<input type="text" id="i_out_title[' + i1 + ']" name="i_out[\'title\'][]" class="form-control form-control-sm">';
														html += '</div>';
														html += '<div class="col-lg-4">';
														html += '<input type="text" id="i_out_price[' + i1 + ']" name="i_out[\'price\'][]" class="form-control form-control-sm i_price" style="text-align: right;" onchange="implementation_outsourcing_price(<?php echo $i; ?>);">';
														html += '</div>';
														html += '<div class="col-lg-1">';
														html += '<button id="btnRemove_i_out" type="button" class="btn btn-danger btn-sm">-</button>';
														html += '</div>';
														html += '</div>';

														$("#Addon_i_out").append(html);
														i1++;
													});

													$(document).on('click', '#btnRemove_i_out', function() {
														$(this).closest('#addon_i_out').remove();
													});
												});
											</script>
										</div>
									</div>

									<!-- Implementation Price -->
									<div class="col-lg-6">
										<?php //include("components/modules/service_budget/func_mandays.php"); 
										?>
										<?php
										$mands = "mandays";
										if (isset($dsb['catalog_type'])) {
											if ($dsb['catalog_type'] == 1) {
												$mands = "mandays";
											} else
											if ($dsb['catalog_type'] == 2) {
												$mands = "manmonths";
											} else {
												$mands = "manyears";
											}
										}
										?>

										<label for="inputCID3" class="col-sm-12 alert alert-secondary py-3 d-flex flex-row align-items-center justify-content-between">
											<b>Mandays Calculation</b>
											<div class="align-items-right">

												<button type="button" class="btn btn-primary btn-sm" id="btnAdd_mandays" <?php echo $permission == "readonly" ? "disabled" : ""; ?>>+</button>
											</div>
										</label>

										<div class="accordion" id="accordionExample">

											<?php
											$GrandTotal = 0;
											$mysql = "SELECT `resource_qualification`, `mandays`, `manmnoth` AS `manmonths`, `manyear` AS `manyears` FROM `sa_mst_resource_catalogs`";
											$rsCatalogs = $DTSB->get_sql($mysql);
											$i = 1;
											do {
												$zzz = array("mandays" => $rsCatalogs[0]['mandays'], "manmonths" => $rsCatalogs[0]['manmonths'], "manyears" => $rsCatalogs[0]['manyears']);
												if ($i == 1) {
													$Catalogs = array($i => $zzz);
												} else {
													$xxx = array($i => $zzz);
													$Catalogs = $Catalogs + $xxx;
												}

												switch ($i) {
													case $i == 1:
											?>
														<!-- <div class="row">
															<label for="inputCID3" class="col-sm-3 col-form-label"><?php //echo $rsCatalogs[0]['resource_qualification']; 
																?></label>
															<div class="col-sm-3"> -->
														<input type="hidden" class="form-control form-control-sm text-right" id="catalogPDMandays" value="<?php echo $rsCatalogs[0]['mandays']; ?>" readonly>
														<!-- </div>
															<div class="col-sm-3"> -->
														<input type="hidden" class="form-control form-control-sm text-right" id="catalogPDManmonths" value="<?php echo $rsCatalogs[0]['manmonths']; ?>" readonly>
														<!-- </div>
															<div class="col-sm-3"> -->
														<input type="hidden" class="form-control form-control-sm text-right" id="catalogPDManyears" value="<?php echo $rsCatalogs[0]['manyears']; ?>" readonly>
														<!-- </div>
														</div> -->
													<?php
														break;
													case $i == 2:
													?>
														<!-- <div class="row">
															<label for="inputCID3" class="col-sm-3 col-form-label"><?php //echo $rsCatalogs[0]['resource_qualification']; 
																?></label>
															<div class="col-sm-3"> -->
														<input type="hidden" class="form-control form-control-sm text-right" id="catalogPMMandays" value="<?php echo $rsCatalogs[0]['mandays']; ?>" readonly>
														<!-- </div>
															<div class="col-sm-3"> -->
														<input type="hidden" class="form-control form-control-sm text-right" id="catalogPMManmonths" value="<?php echo $rsCatalogs[0]['manmonths']; ?>" readonly>
														<!-- </div>
															<div class="col-sm-3"> -->
														<input type="hidden" class="form-control form-control-sm text-right" id="catalogPMManyears" value="<?php echo $rsCatalogs[0]['manyears']; ?>" readonly>
														<!-- </div>
														</div> -->
													<?php
														break;
													case $i == 3:
													?>
														<!-- <div class="row">
															<label for="inputCID" class="col-sm-3 col-form-label"><?php //echo $rsCatalogs[0]['resource_qualification']; 
																													?></label>
															<div class="col-sm-3"> -->
														<input type="hidden" class="form-control form-control-sm text-right" id="catalogPCMandays" value="<?php echo $rsCatalogs[0]['mandays']; ?>" readonly>
														<!-- </div>
															<div class="col-sm-3"> -->
														<input type="hidden" class="form-control form-control-sm text-right" id="catalogPCManmonths" value="<?php echo $rsCatalogs[0]['manmonths']; ?>" readonly>
														<!-- </div>
															<div class="col-sm-3"> -->
														<input type="hidden" class="form-control form-control-sm text-right" id="catalogPCManyears" value="<?php echo $rsCatalogs[0]['manyears']; ?>" readonly>
														<!-- </div>
														</div> -->
													<?php
														break;
													case $i == 4:
													?>
														<!-- <div class="row">
															<label for="inputCID3" class="col-sm-3 col-form-label"><?php //echo $rsCatalogs[0]['resource_qualification']; 
																													?></label>
															<div class="col-sm-3"> -->
														<input type="hidden" class="form-control form-control-sm text-right" id="catalogPAMandays" value="<?php echo $rsCatalogs[0]['mandays']; ?>" readonly>
														<!-- </div>
															<div class="col-sm-3"> -->
														<input type="hidden" class="form-control form-control-sm text-right" id="catalogPAManmonths" value="<?php echo $rsCatalogs[0]['manmonths']; ?>" readonly>
														<!-- </div>
															<div class="col-sm-3"> -->
														<input type="hidden" class="form-control form-control-sm text-right" id="catalogPAManyears" value="<?php echo $rsCatalogs[0]['manyears']; ?>" readonly>
														<!-- </div>
														</div> -->
													<?php
														break;
													case $i == 5:
													?>
														<!-- <div class="row">
															<label for="inputCID3" class="col-sm-3 col-form-label"><?php //echo $rsCatalogs[0]['resource_qualification']; 
																?></label>
															<div class="col-sm-3"> -->
														<input type="hidden" class="form-control form-control-sm text-right" id="catalogEEMandays" value="<?php echo $rsCatalogs[0]['mandays']; ?>" readonly>
														<!-- </div>
															<div class="col-sm-3"> -->
														<input type="hidden" class="form-control form-control-sm text-right" id="catalogEEManmonths" value="<?php echo $rsCatalogs[0]['manmonths']; ?>" readonly>
														<!-- </div>
															<div class="col-sm-3"> -->
														<input type="hidden" class="form-control form-control-sm text-right" id="catalogEEManyears" value="<?php echo $rsCatalogs[0]['manyears']; ?>" readonly>
														<!-- </div>
														</div> -->
													<?php
														break;
													case $i == 6:
													?>
														<!-- <div class="row">
															<label for="inputCID3" class="col-sm-3 col-form-label"><?php //echo $rsCatalogs[0]['resource_qualification']; 
																?></label>
															<div class="col-sm-3"> -->
														<input type="hidden" class="form-control form-control-sm text-right" id="catalogEPMandays" value="<?php echo $rsCatalogs[0]['mandays']; ?>" readonly>
														<!-- </div>
															<div class="col-sm-3"> -->
														<input type="hidden" class="form-control form-control-sm text-right" id="catalogEPManmonths" value="<?php echo $rsCatalogs[0]['manmonths']; ?>" readonly>
														<!-- </div>
															<div class="col-sm-3"> -->
														<input type="hidden" class="form-control form-control-sm text-right" id="catalogEPManyears" value="<?php echo $rsCatalogs[0]['manyears']; ?>" readonly>
														<!-- </div>
														</div> -->
													<?php
														break;
													case $i == 7:
													?>
														<!-- <div class="row">
															<label for="inputCID3" class="col-sm-3 col-form-label"><?php //echo $rsCatalogs[0]['resource_qualification']; 
																?></label>
															<div class="col-sm-3"> -->
														<input type="hidden" class="form-control form-control-sm text-right" id="catalogEAMandays" value="<?php echo $rsCatalogs[0]['mandays']; ?>" readonly>
														<!-- </div>
															<div class="col-sm-3"> -->
														<input type="hidden" class="form-control form-control-sm text-right" id="catalogEAManmonths" value="<?php echo $rsCatalogs[0]['manmonths']; ?>" readonly>
														<!-- </div>
															<div class="col-sm-3"> -->
														<input type="hidden" class="form-control form-control-sm text-right" id="catalogEAManyears" value="<?php echo $rsCatalogs[0]['manyears']; ?>" readonly>
														<!-- </div>
														</div> -->
													<?php
														break;
												}
												$i++;
											} while ($rsCatalogs[0] = $rsCatalogs[1]->fetch_assoc());

											$ix = 0;
											for ($brandx = 1; $brandx < 10; $brandx++) {
												$mysql =
													"SELECT
														`project_mandays_id`,
														`project_id`,
														`resource_level`,
														`resource_catalog_id`,
														`mantotal`,
														`mandays`,
														`brand`,
														`value`,
														`catalog_type`,
														`service_type`,
														`modified_by`,
														`modified_date`
													FROM
														`sa_trx_project_mandays`
													WHERE
														`project_id` = " . $project_id . " AND `service_type` = 1 AND `resource_level` % 10 = " . $brandx;
												$rsMandays = $DTSB->get_sql($mysql);
												$SubTotalMandays = 0;
												if ($rsMandays[2] > 0) {
													$i = 0;
													do {
														$xsolution = (int)($rsMandays[0]['resource_level'] / 10);
														$xbrand = $rsMandays[0]['brand'];
														$xmans = $rsMandays[0]['mantotal'];
														$xmandays = $rsMandays[0]['mandays'];
														$xvalue = $rsMandays[0]['value'];
														$xtype = $rsMandays[0]['catalog_type'];
														$xitems = array("brand" => $xbrand, "mans" => $xmans, "mandays" => $xmandays, "value" => $xvalue, "type" => $xtype);
														$xxx = array($xsolution => $xitems);
														if ($i == 0) {
															$Mandays = $xxx;
														} else {
															$zzz = $Mandays;
															$Mandays += $xxx;
														}
														$i++;
													} while ($rsMandays[0] = $rsMandays[1]->fetch_assoc());

													$show = "";
													$collapsed = "collapsed";
													$expanded = "false";
													if ($brandx == 1) {
														$show = "show";
														$collapsed = "";
														$expanded = "true";
													}

													?>
													<style>
														.accordion-header .collapsed {
															background-color: whitesmoke;
														}
													</style>
													<div class="accordion-item" id="item">
														<h2 class="accordion-header">
															<button class="accordion-button <?php echo $collapsed; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#ProductNamex<?php echo $brandx; ?>" aria-expanded="<?php echo $expanded; ?>" aria-controls="ProductNamex<?php echo $brandx; ?>">
																<div class="fw-bold" id="BrandName">Product Brand : <?php echo $xbrand; ?></div>
															</button>
														</h2>
														<div id="ProductNamex<?php echo $brandx; ?>" class="accordion-collapse collapse <?php echo $show; ?>" data-bs-parent="#accordionExample">
															<div class="accordion-body">
																<div class="row">
																	<label for="inputCID3" class="col-sm-3 col-form-label">Mandays ID</label>
																	<div class="col-sm-8">
																		<input type="text" class="form-control form-control-sm" id="mandays[<?php echo $ix; ?>]['id']" name="mandays[<?php echo $ix; ?>]['id']" readonly>
																	</div>
																</div>
																<div class="row mb-2">
																	<label for="inputCID3" class="col-sm-3 col-form-label">Brand</label>
																	<div class="col-sm-8">
																		<input type="text" class="form-control form-control-sm" id="mandays[<?php echo $ix; ?>]['brand_name']" name="mandays[<?php echo $ix; ?>]['brand_name']" value="<?php echo $xbrand; ?>">
																	</div>
																	<div class="col-sm-1">
																		<button type="button" class="btn btn-sm btn-light" id="Remove_Mandays"><i class="fa-regular fa-trash-can"></i></button>
																	</div>
																</div>

																<div class="row">
																	<label for="inputCID3" class="col-sm-3 col-form-label">Mandays Type</label>
																	<div class="col mb-3">
																		<?php
																		$xxx = 0;
																		if (isset($Mandays[1])) {
																			$xxx = $Mandays[1]['type'];
																		} else
																		if (isset($Mandays[2])) {
																			$xxx = $Mandays[2]['type'];
																		} else
																		if (isset($Mandays[3])) {
																			$xxx = $Mandays[3]['type'];
																		} else
																		if (isset($Mandays[4])) {
																			$xxx = $Mandays[4]['type'];
																		} else
																		if (isset($Mandays[5])) {
																			$xxx = $Mandays[5]['type'];
																		} else
																		if (isset($Mandays[6])) {
																			$xxx = $Mandays[6]['type'];
																		} else
																		if (isset($Mandays[7])) {
																			$xxx = $Mandays[7]['type'];
																		}
																		?>
																		<div class="form-check form-check-inline">
																			<input class="form-check-input" type="radio" name="mandays[<?php echo $ix; ?>]['type']" id="mandays[<?php echo $ix; ?>]['type1']" value="1" onclick="change_catalog(1, <?php echo $ix; ?>);" <?php echo $xxx == 1 ? "checked" : ""; ?>>
																			<label class="form-check-label" for="inlineRadio1">Mandays</label>
																		</div>
																		<div class="form-check form-check-inline">
																			<input class="form-check-input" type="radio" name="mandays[<?php echo $ix; ?>]['type']" id="mandays[<?php echo $ix; ?>]['type2']" value="2" onclick="change_catalog(2, <?php echo $ix; ?>);" <?php echo $xxx == 2 ? "checked" : ""; ?>>
																			<label class="form-check-label" for="inlineRadio2">Manmonths</label>
																		</div>
																		<div class="form-check form-check-inline">
																			<input class="form-check-input" type="radio" name="mandays[<?php echo $ix; ?>]['type']" id="mandays[<?php echo $ix; ?>]['type3']" value="3" onclick="change_catalog(3, <?php echo $ix; ?>);" <?php echo $xxx == 3 ? "checked" : ""; ?>>
																			<label class="form-check-label" for="inlineRadio3">Manyears</label>
																		</div>
																	</div>
																</div>

																<div class="row">
																	<label for="inputCID3" class="col-sm-3 col-form-label">Resource level</label>
																	<label for="inputCID3" class="col-sm-2 col-form-label">Mans</label>
																	<label for="inputCID3" class="col-sm-2 col-form-label" id="mandaysLabel">Mandays</label>
																	<label for="inputCID3" class="col-sm-2 col-form-label">Rate</label>
																	<label for="inputCID3" class="col-sm-3 col-form-label">Total</label>
																</div>
																<div class="row">
																	<label for="inputCID3" class="col-sm-3 col-form-label">Project Director</label>
																	<div class="col-sm-2">
																		<input type="text" class="form-control form-control-sm text-right mans[<?php echo $ix; ?>]" id="mandays[<?php echo $ix; ?>]['PDMans']" name="mandays[<?php echo $ix; ?>]['PDMans']" value="<?php echo isset($Mandays[1]) ? $Mandays[1]['mans'] : ""; ?>">
																	</div>
																	<div class="col-sm-2">
																		<input type="text" class="form-control form-control-sm text-right mandays[<?php echo $ix; ?>]" id="mandays[<?php echo $ix; ?>]['PDMandays']" name="mandays[<?php echo $ix; ?>]['PDMandays']" value="<?php echo isset($Mandays[1]) ? $Mandays[1]['mandays'] : ""; ?>" onchange="mandays_change(1);">
																	</div>
																	<div class="col-sm-2">
																		<input type="text" class="form-control form-control-sm text-right rate[<?php echo $ix; ?>]" id="mandays[<?php echo $ix; ?>]['PDRate']" name="mandays[<?php echo $ix; ?>]['PDRate']" value="<?php echo isset($Mandays[1]) ? $Mandays[1]['value'] : $Catalogs[1][$mands]; ?>" readonly>
																	</div>
																	<div class="col-sm-3">
																		<?php $SubTotalMandays += (isset($Mandays[1]) ? $Mandays[1]['mandays'] * $Mandays[1]['value'] : 0); ?>
																		<input type="hidden" class="form-control form-control-sm text-right total_rate_mandays" id="mandays[<?php echo $ix; ?>]['PDtotalrate']" name="mandays[<?php echo $ix; ?>]['PDtotalrate']" value="<?php echo isset($Mandays[1]) ? $Mandays[1]['mandays'] * $Mandays[1]['value'] : ""; ?>" readonly>
																		<input type="text" class="form-control form-control-sm text-right total_mandays" id="mandays[<?php echo $ix; ?>]['PDTotal']" name="mandays[<?php echo $ix; ?>]['PDTotal']" value="" readonly>
																	</div>
																</div>
																<div class="row">
																	<label for="inputCID3" class="col-sm-3 col-form-label">Project Manager</label>
																	<div class="col-sm-2">
																		<input type="text" class="form-control form-control-sm text-right mans[<?php echo $ix; ?>]" id="mandays[<?php echo $ix; ?>]['PMMans']" name="mandays[<?php echo $ix; ?>]['PMMans']" value="<?php echo isset($Mandays[2]) ? $Mandays[2]['mans'] : ""; ?>">
																	</div>
																	<div class="col-sm-2">
																		<input type="text" class="form-control form-control-sm text-right mandays[<?php echo $ix; ?>]" id="mandays[<?php echo $ix; ?>]['PMMandays']" name="mandays[<?php echo $ix; ?>]['PMMandays']" value="<?php echo isset($Mandays[2]) ? $Mandays[2]['mandays'] : ""; ?>" onchange="mandays_change(2);">
																	</div>
																	<div class="col-sm-2">
																		<input type="text" class="form-control form-control-sm text-right rate[<?php echo $ix; ?>]" id="mandays[<?php echo $ix; ?>]['PMRate']" name="mandays[<?php echo $ix; ?>]['PMRate']" value="<?php echo isset($Mandays[2]) ? $Mandays[2]['value'] : $Catalogs[2][$mands]; ?>" readonly>
																	</div>
																	<div class="col-sm-3">
																		<?php $SubTotalMandays += isset($Mandays[2]) ? $Mandays[2]['mandays'] * $Mandays[2]['value'] : 0; ?>
																		<input type="hidden" class="form-control form-control-sm text-right total_rate_mandays" id="mandays[<?php echo $ix; ?>]['PMtotalrate']" name="mandays[<?php echo $ix; ?>]['PMtotalrate']" value="<?php echo isset($Mandays[2]) ? $Mandays[2]['mandays'] * $Mandays[2]['value'] : ""; ?>" readonly>
																		<input type="text" class="form-control form-control-sm text-right total_mandays" id="mandays[<?php echo $ix; ?>]['PMTotal']" name="mandays[<?php echo $ix; ?>]['PMTotal']" value="" readonly>
																	</div>
																</div>
																<div class="row">
																	<label for="inputCID3" class="col-sm-3 col-form-label">Project Coordinator</label>
																	<div class="col-sm-2">
																		<input type="text" class="form-control form-control-sm text-right mans[<?php echo $ix; ?>]" id="mandays[<?php echo $ix; ?>]['PCMans']" name="mandays[<?php echo $ix; ?>]['PCMans']" value="<?php echo isset($Mandays[3]) ? $Mandays[3]['mans'] : ""; ?>">
																	</div>
																	<div class="col-sm-2">
																		<input type="text" class="form-control form-control-sm text-right mandays[<?php echo $ix; ?>]" id="mandays[<?php echo $ix; ?>]['PCMandays']" name="mandays[<?php echo $ix; ?>]['PCMandays']" value="<?php echo isset($Mandays[3]) ? $Mandays[3]['mandays'] : ""; ?>" onchange="mandays_change(3);">
																	</div>
																	<div class="col-sm-2">
																		<input type="text" class="form-control form-control-sm text-right rate[<?php echo $ix; ?>]" id="mandays[<?php echo $ix; ?>]['PCRate']" name="mandays[<?php echo $ix; ?>]['PCRate']" value="<?php echo isset($Mandays[3]) ? $Mandays[3]['value'] : $Catalogs[3][$mands]; ?>" readonly>
																	</div>
																	<div class="col-sm-3">
																		<?php $SubTotalMandays += isset($Mandays[3]) ? $Mandays[3]['mandays'] * $Mandays[3]['value'] : 0; ?>
																		<input type="hidden" class="form-control form-control-sm text-right total_rate_mandays" id="mandays[<?php echo $ix; ?>]['PCtotalrate']" name="mandays[<?php echo $ix; ?>]['PCtotalrate']" value="<?php echo isset($Mandays[3]) ? $Mandays[3]['mandays'] * $Mandays[3]['value'] : ""; ?>" readonly>
																		<input type="text" class="form-control form-control-sm text-right total_mandays" id="mandays[<?php echo $ix; ?>]['PCTotal']" name="mandays[<?php echo $ix; ?>]['PCTotal']" value="" readonly>
																	</div>
																</div>
																<div class="row">
																	<label for="inputCID3" class="col-sm-3 col-form-label">Project Admin</label>
																	<div class="col-sm-2">
																		<input type="text" class="form-control form-control-sm text-right mans[<?php echo $ix; ?>]" id="mandays[<?php echo $ix; ?>]['PAMans']" name="mandays[<?php echo $ix; ?>]['PAMans']" value="<?php echo isset($Mandays[4]) ? $Mandays[4]['mans'] : ""; ?>">
																	</div>
																	<div class="col-sm-2">
																		<input type="text" class="form-control form-control-sm text-right mandays[<?php echo $ix; ?>]" id="mandays[<?php echo $ix; ?>]['PAMandays']" name="mandays[<?php echo $ix; ?>]['PAMandays']" value="<?php echo isset($Mandays[4]) ? $Mandays[4]['mandays'] : ""; ?>" onchange="mandays_change(4);">
																	</div>
																	<div class="col-sm-2">
																		<input type="text" class="form-control form-control-sm text-right rate[<?php echo $ix; ?>]" id="mandays[<?php echo $ix; ?>]['PARate']" name="mandays[<?php echo $ix; ?>]['PARate']" value="<?php echo isset($Mandays[4]) ? $Mandays[4]['value'] : $Catalogs[4][$mands]; ?>" readonly>
																	</div>
																	<div class="col-sm-3">
																		<?php $SubTotalMandays += isset($Mandays[4]) ? $Mandays[4]['mandays'] * $Mandays[4]['value'] : 0; ?>
																		<input type="hidden" class="form-control form-control-sm text-right total_rate_mandays" id="mandays[<?php echo $ix; ?>]['PAtotalrate']" name="mandays[<?php echo $ix; ?>]['PAtotalrate']" value="<?php echo isset($Mandays[4]) ? $Mandays[4]['mandays'] * $Mandays[4]['value'] : ""; ?>" readonly>
																		<input type="text" class="form-control form-control-sm text-right total_mandays" id="mandays[<?php echo $ix; ?>]['PATotal']" name="mandays[<?php echo $ix; ?>]['PATotal']" value="" readonly>
																	</div>
																</div>
																<div class="row">
																	<label for="inputCID3" class="col-sm-3 col-form-label">Engineer Expert</label>
																	<div class="col-sm-2">
																		<input type="text" class="form-control form-control-sm text-right mans[<?php echo $ix; ?>]" id="mandays[<?php echo $ix; ?>]['EEMans']" name="mandays[<?php echo $ix; ?>]['EEMans']" value="<?php echo isset($Mandays[5]) ? $Mandays[5]['mans'] : ""; ?>">
																	</div>
																	<div class="col-sm-2">
																		<input type="text" class="form-control form-control-sm text-right mandays[<?php echo $ix; ?>]" id="mandays[<?php echo $ix; ?>]['EEMandays']" name="mandays[<?php echo $ix; ?>]['EEMandays']" value="<?php echo isset($Mandays[5]) ? $Mandays[5]['mandays'] : ""; ?>" onchange="mandays_change(5);">
																	</div>
																	<div class="col-sm-2">
																		<input type="text" class="form-control form-control-sm text-right rate[<?php echo $ix; ?>]" id="mandays[<?php echo $ix; ?>]['EERate']" name="mandays[<?php echo $ix; ?>]['EERate']" value="<?php echo isset($Mandays[5]) ? $Mandays[5]['value'] : $Catalogs[5][$mands]; ?>" readonly>
																	</div>
																	<div class="col-sm-3">
																		<?php $SubTotalMandays += isset($Mandays[5]) ? $Mandays[5]['mandays'] * $Mandays[5]['value'] : 0; ?>
																		<input type="hidden" class="form-control form-control-sm text-right total_rate_mandays" id="mandays[<?php echo $ix; ?>]['EEtotalrate']" name="mandays[<?php echo $ix; ?>]['EEtotalrate']" value="<?php echo isset($Mandays[5]) ? $Mandays[5]['mandays'] * $Mandays[5]['value'] : ""; ?>" readonly>
																		<input type="text" class="form-control form-control-sm text-right total_mandays" id="mandays[<?php echo $ix; ?>]['EETotal']" name="mandays[<?php echo $ix; ?>]['EETotal']" value="" readonly>
																	</div>
																</div>
																<div class="row">
																	<label for="inputCID3" class="col-sm-3 col-form-label">Engineer Professional</label>
																	<div class="col-sm-2">
																		<input type="text" class="form-control form-control-sm text-right mans[<?php echo $ix; ?>]" id="mandays[<?php echo $ix; ?>]['EPMans']" name="mandays[<?php echo $ix; ?>]['EPMans']" value="<?php echo isset($Mandays[6]) ? $Mandays[6]['mans'] : ""; ?>">
																	</div>
																	<div class="col-sm-2">
																		<input type="text" class="form-control form-control-sm text-right mandays[<?php echo $ix; ?>]" id="mandays[<?php echo $ix; ?>]['EPMandays']" name="mandays[<?php echo $ix; ?>]['EPMandays']" value="<?php echo isset($Mandays[6]) ? $Mandays[6]['mandays'] : ""; ?>" onchange="mandays_change(6);">
																	</div>
																	<div class="col-sm-2">
																		<input type="text" class="form-control form-control-sm text-right rate[<?php echo $ix; ?>]" id="mandays[<?php echo $ix; ?>]['EPRate']" name="mandays[<?php echo $ix; ?>]['EPRate']" value="<?php echo isset($Mandays[6]) ? $Mandays[6]['value'] : $Catalogs[6][$mands]; ?>" readonly>
																	</div>
																	<div class="col-sm-3">
																		<?php $SubTotalMandays += isset($Mandays[6]) ? $Mandays[6]['mandays'] * $Mandays[6]['value'] : 0; ?>
																		<input type="hidden" class="form-control form-control-sm text-right total_rate_mandays" id="mandays[<?php echo $ix; ?>]['EPtotalrate']" name="mandays[<?php echo $ix; ?>]['EPtotalrate']" value="<?php echo isset($Mandays[6]) ? $Mandays[6]['mandays'] * $Mandays[6]['value'] : ""; ?>" readonly>
																		<input type="text" class="form-control form-control-sm text-right total_mandays" id="mandays[<?php echo $ix; ?>]['EPTotal']" name="mandays[<?php echo $ix; ?>]['EPTotal']" value="" readonly>
																	</div>
																</div>
																<div class="row">
																	<label for="inputCID3" class="col-sm-3 col-form-label">Engineer Associate</label>
																	<div class="col-sm-2">
																		<input type="text" class="form-control form-control-sm text-right mans[<?php echo $ix; ?>]" id="mandays[<?php echo $ix; ?>]['EAMans']" name="mandays[<?php echo $ix; ?>]['EAMans']" value="<?php echo isset($Mandays[7]) ? $Mandays[7]['mans'] : ""; ?>">
																	</div>
																	<div class="col-sm-2">
																		<input type="text" class="form-control form-control-sm text-right mandays[<?php echo $ix; ?>]" id="mandays[<?php echo $ix; ?>]['EAMandays']" name="mandays[<?php echo $ix; ?>]['EAMandays']" value="<?php echo isset($Mandays[7]) ? $Mandays[7]['mandays'] : ""; ?>" onchange="mandays_change(7);">
																	</div>
																	<div class="col-sm-2">
																		<input type="text" class="form-control form-control-sm text-right rate[<?php echo $ix; ?>]" id="mandays[<?php echo $ix; ?>]['EARate']" name="mandays[<?php echo $ix; ?>]['EARate']" value="<?php echo isset($Mandays[7]) ? $Mandays[7]['value'] : $Catalogs[7][$mands]; ?>" readonly>
																	</div>
																	<div class="col-sm-3">
																		<?php $SubTotalMandays += isset($Mandays[7]) ? $Mandays[7]['mandays'] * $Mandays[7]['value'] : 0; ?>
																		<input type="hidden" class="form-control form-control-sm text-right total_rate_mandays" id="mandays[<?php echo $ix; ?>]['EAtotalrate']" name="mandays[<?php echo $ix; ?>]['EAtotalrate']" value="<?php echo isset($Mandays[7]) ? $Mandays[7]['mandays'] * $Mandays[7]['value'] : ""; ?>" readonly>
																		<input type="text" class="form-control form-control-sm text-right total_mandays" id="mandays[<?php echo $ix; ?>]['EATotal']" name="mandays[<?php echo $ix; ?>]['EATotal']" value="" readonly>
																	</div>
																</div>
																<div class="row">
																	<label for="inputCID3" class="col-sm-7 col-form-label"></label>
																	<label for="inputCID3" class="col-sm-2 col-form-label fw-bold">Total</label>
																	<div class="col-sm-3">
																		<input type="hidden" class="form-control form-control-sm text-right" id="total_rate_mandays[<?php echo $ix; ?>]" value="<?php echo $SubTotalMandays; ?>" readonly>
																		<input type="text" class="form-control form-control-sm text-right" id="total_mandays[<?php echo $ix; ?>]" value="" readonly>
																	</div>
																</div>
															</div>
														</div>
													</div>
											<?php
													$GrandTotal += $SubTotalMandays;
													$ix++;
												}
											}
											?>
											<div id="AddSummarry"></div>
											<script>
												$(document).ready(function() {
													var ix = <?php echo $ix; ?>;
													$("#btnAdd_mandays").click(function(btn) {
														showx = "";
														collapsex = "collapsed";
														truex = "false";
														if (ix == 0) {
															showx = "show";
															collapsex = "";
															truex = "true";
														}
														var html = '';
														html += '<div class="accordion-item" id="item">';
														html += '    <h2 class="accordion-header">';
														html += '    <button class="accordion-button ' + collapsex + '" type="button" data-bs-toggle="collapse" data-bs-target="#ProductName' + ix + '" aria-expanded="' + truex + '" aria-controls="ProductName' + ix + '">';
														html += '        <div id="BrandName">Product Brand #' + ix + '</div>';
														html += '    </button>';
														html += '    </h2>';
														html += '    <div id="ProductName' + ix + '" class="accordion-collapse collapse ' + showx + '" data-bs-parent="#accordionExample">';
														html += '        <div class="accordion-body">';
														html += '            <div class="row">';
														html += '                <label for="inputCID3" class="col-sm-3 col-form-label">ID</label>';
														html += '                <div class="col-sm-8">';
														html += '                    <input type="text" class="form-control form-control-sm" id="mandays[' + ix + '][\'id\']" name="mandays[' + ix + '][\'id\']" readonly>';
														html += '                </div>';
														html += '            </div>';
														html += '            <div class="row">';
														html += '                <label for="inputCID3" class="col-sm-3 col-form-label">Brand</label>';
														html += '                <div class="col-sm-8">';
														html += '                    <input type="text" class="form-control form-control-sm" id="mandays[' + ix + '][\'brand_name\']" name="mandays[' + ix + '][\'brand_name\']" value="">';
														html += '                </div>';
														html += '                <div class="col-sm-1">';
														html += '                    <button type="button" class="btn btn-sm btn-light" id="Remove_Mandays"><i class="fa-regular fa-trash-can"></i></button>';
														html += '                </div>';
														html += '            </div>';


														html += '<div class="row">';
														html += '				<label for="inputCID3" class="col-sm-3 col-form-label">Mandays Type</label>';
														html += '				<div class="col mb-3">';
														xxx = 0;
														// 					if(isset($Mandays[1]))
														// 					{
														// 						$xxx = $Mandays[1]['type'];
														// 					} else
														// 					if(isset($Mandays[2]))
														// 					{
														// 						$xxx = $Mandays[2]['type'];
														// 					} else
														// 					if(isset($Mandays[3]))
														// 					{
														// 						$xxx = $Mandays[3]['type'];
														// 					} else
														// 					if(isset($Mandays[4]))
														// 					{
														// 						$xxx = $Mandays[4]['type'];
														// 					} else
														// 					if(isset($Mandays[5]))
														// 					{
														// 						$xxx = $Mandays[5]['type'];
														// 					} else
														// 					if(isset($Mandays[6]))
														// 					{
														// 						$xxx = $Mandays[6]['type'];
														// 					} else
														// 					if(isset($Mandays[7]))
														// 					{
														// 						$xxx = $Mandays[7]['type'];
														// 					}

														html += '					<div class="form-check form-check-inline">';
														html += '						<input class="form-check-input" type="radio" name="mandays[' + ix + '][\'type\']" id="mandays[' + ix + '][\'type1\']" value="1" onclick="change_catalog(1, ' + ix + ');" ' + (xxx == 1 ? "checked" : "") + '>';
														html += '						<label class="form-check-label" for="inlineRadio1">Mandays</label>';
														html += '					</div>';
														html += '					<div class="form-check form-check-inline">';
														html += '						<input class="form-check-input" type="radio" name="mandays[' + ix + '][\'type\']" id="mandays[' + ix + '][\'type2\']" value="2" onclick="change_catalog(2, ' + ix + ');" ' + (xxx == 2 ? "checked" : "") + '>';
														html += '						<label class="form-check-label" for="inlineRadio2">Manmonths</label>';
														html += '					</div>';
														html += '					<div class="form-check form-check-inline">';
														html += '						<input class="form-check-input" type="radio" name="mandays[' + ix + '][\'type\']" id="mandays[' + ix + '][\'type3\']" value="3" onclick="change_catalog(3, ' + ix + ');" ' + (xxx == 3 ? "checked" : "") + '>';
														html += '						<label class="form-check-label" for="inlineRadio3">Manyears</label>';
														html += '					</div>';
														html += '				</div>';
														html += '			</div>';




														html += '            <div class="row">';
														html += '                <label for="inputCID3" class="col-sm-3 col-form-label">Resource level</label>';
														html += '                <label for="inputCID3" class="col-sm-2 col-form-label">Mans</label>';
														html += '                <label for="inputCID3" class="col-sm-2 col-form-label">Total Mandays</label>';
														html += '                <label for="inputCID3" class="col-sm-2 col-form-label">Rate</label>';
														html += '                <label for="inputCID3" class="col-sm-3 col-form-label">Total</label>';
														html += '            </div>';
														html += '            <div class="row">';
														html += '                <label for="inputCID3" class="col-sm-3 col-form-label">Project Director</label>';
														html += '                <div class="col-sm-2">';
														html += '                    <input type="text" class="form-control form-control-sm text-right" id="mandays[' + ix + '][\'PDMans\']" name="mandays[' + ix + '][\'PDMans\']">';
														html += '                </div>';
														html += '                <div class="col-sm-2">';
														html += '                    <input type="text" class="form-control form-control-sm text-right" id="mandays[' + ix + '][\'PDMandays\']" name="mandays[' + ix + '][\'PDMandays\']" onchange="mandays_change(8);">';
														html += '                </div>';
														html += '                <div class="col-sm-2">';
														html += '                    <input type="text" class="form-control form-control-sm text-right" id="mandays[' + ix + '][\'PDRate\']" name="mandays[' + ix + '][\'PDRate\']" value="<?php echo $Catalogs[1][$mands]; ?>" readonly>';
														html += '                </div>';
														html += '                <div class="col-sm-3">';
														html += '                    <input type="hidden" class="form-control form-control-sm text-right total_rate_mandays" id="mandays[' + ix + '][\'PDtotalrate\']" name="mandays[' + ix + '][\'PDtotalrate\']" value="" readonly>';
														html += '                    <input type="text" class="form-control form-control-sm text-right total_mandays" id="mandays[' + ix + '][\'PDTotal\']" name="mandays[' + ix + '][\'PDTotal\']" readonly>';
														html += '                </div>';
														html += '            </div>';
														html += '            <div class="row">';
														html += '                <label for="inputCID3" class="col-sm-3 col-form-label">Project Manager</label>';
														html += '                <div class="col-sm-2">';
														html += '                    <input type="text" class="form-control form-control-sm text-right" id="mandays[' + ix + '][\'PMMans\']" name="mandays[' + ix + '][\'PMMans\']">';
														html += '                </div>';
														html += '                <div class="col-sm-2">';
														html += '                    <input type="text" class="form-control form-control-sm text-right" id="mandays[' + ix + '][\'PMMandays\']" name="mandays[' + ix + '][\'PMMandays\']" onchange="mandays_change(9);">';
														html += '                </div>';
														html += '                <div class="col-sm-2">';
														html += '                    <input type="text" class="form-control form-control-sm text-right" id="mandays[' + ix + '][\'PMRate\']" name="mandays[' + ix + '][\'PMRate\']" value="<?php echo $Catalogs[2][$mands]; ?>" readonly>';
														html += '                </div>';
														html += '                <div class="col-sm-3">';
														html += '                    <input type="hidden" class="form-control form-control-sm text-right total_rate_mandays" id="mandays[' + ix + '][\'PMtotalrate\']" name="mandays[' + ix + '][\'PMtotalrate\']" value="" readonly>';
														html += '                    <input type="text" class="form-control form-control-sm text-right total_mandays" id="mandays[' + ix + '][\'PMTotal\']" name="mandays[' + ix + '][\'PMTotal\']" readonly>';
														html += '                </div>';
														html += '            </div>';
														html += '            <div class="row">';
														html += '                <label for="inputCID3" class="col-sm-3 col-form-label">Project Coordinator</label>';
														html += '                <div class="col-sm-2">';
														html += '                    <input type="text" class="form-control form-control-sm text-right" id="mandays[' + ix + '][\'PCMans\']" name="mandays[' + ix + '][\'PCMans\']">';
														html += '                </div>';
														html += '                <div class="col-sm-2">';
														html += '                    <input type="text" class="form-control form-control-sm text-right" id="mandays[' + ix + '][\'PCMandays\']" name="mandays[' + ix + '][\'PCMandays\']" onchange="mandays_change(10);">';
														html += '                </div>';
														html += '                <div class="col-sm-2">';
														html += '                    <input type="text" class="form-control form-control-sm text-right" id="mandays[' + ix + '][\'PCRate\']" name="mandays[' + ix + '][\'PCRate\']" value="<?php echo $Catalogs[3][$mands]; ?>" readonly>';
														html += '                </div>';
														html += '                <div class="col-sm-3">';
														html += '                    <input type="hidden" class="form-control form-control-sm text-right total_rate_mandays" id="mandays[' + ix + '][\'PCtotalrate\']" name="mandays[' + ix + '][\'PCtotalrate\']" value="" readonly>';
														html += '                    <input type="text" class="form-control form-control-sm text-right total_mandays" id="mandays[' + ix + '][\'PCTotal\']" name="mandays[' + ix + '][\'PCTotal\']" readonly>';
														html += '                </div>';
														html += '            </div>';
														html += '            <div class="row">';
														html += '                <label for="inputCID3" class="col-sm-3 col-form-label">Project Admin</label>';
														html += '                <div class="col-sm-2">';
														html += '                    <input type="text" class="form-control form-control-sm text-right" id="mandays[' + ix + '][\'PAMans\']" name="mandays[' + ix + '][\'PAMans\']">';
														html += '                </div>';
														html += '                <div class="col-sm-2">';
														html += '                    <input type="text" class="form-control form-control-sm text-right" id="mandays[' + ix + '][\'PAMandays\']" name="mandays[' + ix + '][\'PAMandays\']" onchange="mandays_change(11);">';
														html += '                </div>';
														html += '                <div class="col-sm-2">';
														html += '                    <input type="text" class="form-control form-control-sm text-right" id="mandays[' + ix + '][\'PARate\']" name="mandays[' + ix + '][\'PARate\']" value="<?php echo $Catalogs[4][$mands]; ?>" readonly>';
														html += '                </div>';
														html += '                <div class="col-sm-3">';
														html += '                    <input type="hidden" class="form-control form-control-sm text-right total_rate_mandays" id="mandays[' + ix + '][\'PAtotalrate\']" name="mandays[' + ix + '][\'PAtotalrate\']" value="" readonly>';
														html += '                    <input type="text" class="form-control form-control-sm text-right total_mandays" id="mandays[' + ix + '][\'PATotal\']" name="mandays[' + ix + '][\'PATotal\']" readonly>';
														html += '                </div>';
														html += '            </div>';
														html += '            <div class="row">';
														html += '                <label for="inputCID3" class="col-sm-3 col-form-label">Engineer Expert</label>';
														html += '                <div class="col-sm-2">';
														html += '                    <input type="text" class="form-control form-control-sm text-right" id="mandays[' + ix + '][\'EEMans\']" name="mandays[' + ix + '][\'EEMans\']">';
														html += '                </div>';
														html += '                <div class="col-sm-2">';
														html += '                    <input type="text" class="form-control form-control-sm text-right" id="mandays[' + ix + '][\'EEMandays\']" name="mandays[' + ix + '][\'EEMandays\']" onchange="mandays_change(12);">';
														html += '                </div>';
														html += '                <div class="col-sm-2">';
														html += '                    <input type="text" class="form-control form-control-sm text-right" id="mandays[' + ix + '][\'EERate\']" name="mandays[' + ix + '][\'EERate\']" value="<?php echo $Catalogs[5][$mands]; ?>" readonly>';
														html += '                </div>';
														html += '                <div class="col-sm-3">';
														html += '                    <input type="hidden" class="form-control form-control-sm text-right total_rate_mandays" id="mandays[' + ix + '][\'EEtotalrate\']" name="mandays[' + ix + '][\'EEtotalrate\']" value="" readonly>';
														html += '                    <input type="text" class="form-control form-control-sm text-right total_mandays" id="mandays[' + ix + '][\'EETotal\']" name="mandays[' + ix + '][\'EETotal\']" readonly>';
														html += '                </div>';
														html += '            </div>';
														html += '            <div class="row">';
														html += '                <label for="inputCID3" class="col-sm-3 col-form-label">Engineer Professional</label>';
														html += '                <div class="col-sm-2">';
														html += '                    <input type="text" class="form-control form-control-sm text-right" id="mandays[' + ix + '][\'EPMans\']" name="mandays[' + ix + '][\'EPMans\']">';
														html += '                </div>';
														html += '                <div class="col-sm-2">';
														html += '                    <input type="text" class="form-control form-control-sm text-right" id="mandays[' + ix + '][\'EPMandays\']" name="mandays[' + ix + '][\'EPMandays\']" onchange="mandays_change(13);">';
														html += '                </div>';
														html += '                <div class="col-sm-2">';
														html += '                    <input type="text" class="form-control form-control-sm text-right" id="mandays[' + ix + '][\'EPRate\']" name="mandays[' + ix + '][\'EPRate\']" value="<?php echo $Catalogs[6][$mands]; ?>" readonly>';
														html += '                </div>';
														html += '                <div class="col-sm-3">';
														html += '                    <input type="hidden" class="form-control form-control-sm text-right total_rate_mandays" id="mandays[' + ix + '][\'EPtotalrate\']" name="mandays[' + ix + '][\'EPtotalrate\']" value="" readonly>';
														html += '                    <input type="text" class="form-control form-control-sm text-right total_mandays" id="mandays[' + ix + '][\'EPTotal\']" name="mandays[' + ix + '][\'EPTotal\']" readonly>';
														html += '                </div>';
														html += '            </div>';
														html += '            <div class="row">';
														html += '                <label for="inputCID3" class="col-sm-3 col-form-label">Engineer Associate</label>';
														html += '                <div class="col-sm-2">';
														html += '                    <input type="text" class="form-control form-control-sm text-right" id="mandays[' + ix + '][\'EAMans\']" name="mandays[' + ix + '][\'EAMans\']">';
														html += '                </div>';
														html += '                <div class="col-sm-2">';
														html += '                    <input type="text" class="form-control form-control-sm text-right" id="mandays[' + ix + '][\'EAMandays\']" name="mandays[' + ix + '][\'EAMandays\']" onchange="mandays_change(14);">';
														html += '                </div>';
														html += '                <div class="col-sm-2">';
														html += '                    <input type="text" class="form-control form-control-sm text-right" id="mandays[' + ix + '][\'EARate\']" name="mandays[' + ix + '][\'EARate\']" value="<?php echo $Catalogs[7][$mands]; ?>" readonly>';
														html += '                </div>';
														html += '                <div class="col-sm-3">';
														html += '                    <input type="hidden" class="form-control form-control-sm text-right total_rate_mandays" id="mandays[' + ix + '][\'EAtotalrate\']" name="mandays[' + ix + '][\'EAtotalrate\']" value="" readonly>';
														html += '                    <input type="text" class="form-control form-control-sm text-right total_mandays" id="mandays[' + ix + '][\'EATotal\']" name="mandays[' + ix + '][\'EATotal\']" readonly>';
														html += '                </div>';
														html += '            </div>';
														html += '            <div class="row">';
														html += '                <label for="inputCID3" class="col-sm-7 col-form-label"></label>';
														html += '                <label for="inputCID3" class="col-sm-2 col-form-label fw-bold">Total</label>';
														html += '                <div class="col-sm-3">';
														html += '                    <input type="hidden" class="form-control form-control-sm text-right" id="total_rate_mandays[' + ix + ']" value="" readonly>';
														html += '                    <input type="text" class="form-control form-control-sm text-right" id="total_mandays[' + ix + ']" value="" readonly>';
														html += '                </div>';
														html += '            </div>';
														html += '        </div>';
														html += '    </div>';
														html += '</div>';
														$("#AddSummarry").append(html);
														ix++;
													});
													$(document).on('click', '#Remove_Mandays', function() {
														$(this).closest('#item').remove();
													});
												});
											</script>
										</div>

										<div class="card-body">
											<div class="row mb-3">
												<label for="inputCID3" class="col-sm-7 col-form-label"></label>
												<label for="inputCID3" class="col-sm-2 col-form-label fw-bold">Grand Total</label>
												<div class="col-sm-3">
													<input type="hidden" class="form-control form-control-sm text-right" id="total_rate_mandays" value="<?php echo $GrandTotal; ?>" readonly>
													<input type="text" class="form-control form-control-sm text-right" id="total_mandays" value="<?php echo $GrandTotal; ?>" readonly>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- TAB Project Maintenance -->
					<div class="tab-pane fade" id="Maintenance" role="tabpanel" aria-labelledby="maintenance-tab">
						<?php //include("components/modules/service_budget/func_maintenance.php"); 
						?>

						<div class="card shadow mb-4">
							<!-- Card Body -->
							<div class="card-body">
								<div class="row">
									<div class="col-lg-6">
										<label for="inputCID3" class="col-sm-12 alert alert-secondary"><b>Service Catalog</b></label>
										<div class="row mb-2">
											<label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Service Type</label>
											<div class="col-sm-6">
												<script>
													const tosArray = [];
												</script>
												<?php
												global $DTSB;
												$tblname = "mst_type_of_service";
												$condition = "service_type=2 AND blocked=0";
												$tos = $DTSB->get_data($tblname, $condition);
												$dtos = $tos[0];
												$qtos = $tos[1];
												$rosidexp = "";
												if ($ver > 0 && isset($dmaintenance['tos_id'])) {
													$tosidexp = explode(";", $dmaintenance['tos_id']);
												}
												$i = 0;
												$tosname = "Gold";
												do {
												?>
													<div class="form-check">
														<input class="form-check-input" type="radio" name="m_tos_id" id="m_tos_id<?php echo $i; ?>" value="<?php echo $dtos['tos_id']; ?>" <?php
															if ($ver > 0 && $tosidexp != "") {
																for ($j = 0; $j < count($tosidexp); $j++) {
																	if ($tosidexp[$j] == $dtos['tos_id']) {
																		echo ' checked ';
																		$tosname = $dtos['tos_name'];
																	}
																}
															}
															if ($permission == 'readonly') {
																echo 'disabled';
															}
															?> onchange="change_mtos();">
														<label class="form-check-label" for="inlineRadio1"><?php echo $dtos['tos_name']; ?></label>
													</div>
													<?php
													if ($dtos['custom'] != "") {
														$xTos = json_decode($dtos['custom'], true);
													?>
														<input type="hidden" class="form-control form-control-sm" id="TosM['<?php echo $xTos["service type"]; ?>']['reporting']" value="<?php echo $xTos['reporting']['total']; ?>">
														<input type="hidden" class="form-control form-control-sm" id="TosM['<?php echo $xTos['service type']; ?>']['preventive']" value="<?php echo $xTos['preventive']['total']; ?>">
														<input type="hidden" class="form-control form-control-sm" id="TosM['<?php echo $xTos['service type']; ?>']['ticket']" value="<?php echo $xTos['ticket']; ?>">
													<?php
													}
													?>
												<?php
													$i++;
												} while ($dtos = $qtos->fetch_assoc());
												?>
											</div>
										</div>
										<!-- <script>
											// console.log(tosArray.toString());
													// jsonString = JSON.stringify(tosArray);
													// console.log(jsonString);

													// Parse JSON string back to a JSON object
													// parsedObject = JSON.parse(jsonString);
													// zzz = "{ "+tosArray.toString()+" }";
													// console.log(zzz);
													// const xxx = JSON.stringify(zzz);
													// console.log(tosArray.toString());
													// console.log(xxx);
													// parsedObject = JSON.parse("{"+tosArray+"}");
													// parsedObject = JSON.parse(xxx);
													// parsedObject = xxx;

													// Accessing properties of the parsed JSON object
													// console.log("service type : "+parsedObject[asd0][serviceType]); // Output: John Doe
													// console.log("service type : "+parsedObject[0][serviceType]); // Output: John Doe
													// console.log("reporting : "+parsedObject.reporting.total);
													// console.log("preventive : "+parsedObject.preventive.total);
													// console.log("ticket : "+parsedObject.ticket); // Output: 30
													// console.log(parsedObject.city); // Output: New York
												</script> -->
										<!-- Minta direview -->
										<div class="row mb-3">
											<select class="form-select form-select-sm" aria-label=".form-select-sm example" name="m_tos_category_id" <?php echo $permission; ?> hidden>
												<option value="1" <?php if (($ver > 0 && $tmaintenance > 0) && (1 == $dmaintenance['tos_category_id'])) {
																		echo 'selected';
																	} ?>>High</option>
												<option value="2" <?php if (($ver > 0 && $tmaintenance > 0) && (2 == $dmaintenance['tos_category_id'])) {
																		echo 'selected';
																	} ?>>Medium</option>
												<option value="3" <?php if (($ver > 0 && $tmaintenance > 0) && (3 == $dmaintenance['tos_category_id'])) {
																		echo 'selected';
																	} ?>>Standard</option>
											</select>
										</div>
										<!-- End Minta direview -->
										<div class="row mb-3">
											<label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Estimation Project Duration</label>
											<div class="col-sm-2">
												<input type="text" class="form-control form-control-sm" id="m_project_estimation" name="m_project_estimation" value="<?php if ($ver > 0 && $tmaintenance > 0) {
														echo $dmaintenance['project_estimation'];
													} ?>" style="text-align: right;" <?php echo $permission; ?>>
											</div>
											<div class="col-sm-3">
												<select class="form-select form-select-sm" aria-label=".form-select-sm example" name="m_project_estimation_id" <?php echo $permission; ?> <?php if ($permission == 'readonly') {
														echo 'disabled';
													} ?>>
													<option value="1" <?php if (($ver > 0 && $tmaintenance > 0) && (1 == $dmaintenance['project_estimation_id'])) {
															echo 'selected';
														} ?>>Days</option>
													<option value="2" <?php if (($ver > 0 && $tmaintenance > 0) && (2 == $dmaintenance['project_estimation_id'])) {
															echo 'selected';
														} ?>>Months</option>
													<option value="3" <?php if (($ver > 0 && $tmaintenance > 0) && (3 == $dmaintenance['project_estimation_id'])) {
															echo 'selected';
														} ?>>Years</option>
												</select>
											</div>
										</div>

										<?php
										// Cataloge reporting & ticket
										if ($ver == 0 || ($ver > 0 && $dsb['reporting'] == NULL)) {
											$mysql = "SELECT custom FROM sa_mst_type_of_service WHERE tos_name = '" . $tosname . "'";
											$rsCatalogs = $DTSB->get_sql($mysql);
											$jsonData = json_decode($rsCatalogs[0]['custom'], true);
											$jsonData = $jsonData != NULL ? $jsonData : '{"reporting": {"total": 0}, "preventive": {"total": 0}, "ticket": 0}';
											$planReporting = $jsonData['reporting']['total'] > 0 ? $jsonData['reporting']['total'] : 0;
											$addonReporting = NULL;
											$planPreventive = $jsonData['preventive']['total'] > 0 ? $jsonData['preventive']['total'] : 0;
											$addonPreventive = NULL;
											$planTicket = $jsonData['ticket'] > 0 ? $jsonData['ticket'] : 0;
											$addonTicket = NULL;
										} else {
											$jsonData = json_decode($dsb['reporting'], true);
											$planReporting = $jsonData['reporting']['plan'];
											$addonReporting = $jsonData['reporting']['addon'];
											$planPreventive = $jsonData['preventive']['plan'];
											$addonPreventive = $jsonData['preventive']['addon'];
											$planTicket = $jsonData['ticket']['plan'];
											$addonTicket = $jsonData['ticket']['addon'];
										}
										?>
										<div class="row mb-3" id="New">
											<label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Periode Maintenance</label>
											<div class="col-sm-2">
												<input class="form-control form-control-sm" name="maintenance_start" id="maintenance_start" type="text" value="<?php echo $ver > 0 ? date('d-M-Y', strtotime($dsb['maintenance_start'])) : ''; ?>">
											</div>
											<div class="col-sm-1">
												to
											</div>
											<div class="col-sm-2">
												<input class="form-control form-control-sm" name="maintenance_end" id="maintenance_end" type="text" value="<?php echo $ver > 0 ? date('d-M-Y', strtotime($dsb['maintenance_end'])) : ""; ?>">
											</div>
										</div>
										<div class="row mb-1 fw-bold border-bottom text-bg-light">
											<label class="col-sm-6 col-form-label col-form-label-sm">Reporting Maintenance</label>
											<label class="col-sm-2 col-form-label col-form-label-sm">Cataloge</label>
											<label class="col-sm-2 col-form-label col-form-label-sm">Addon</label>
											<label class="col-sm-2 col-form-label col-form-label-sm">Total</label>
										</div>
										<div class="row mb-1">
											<label class="col-sm-6 col-form-label col-form-label-sm">TOTAL Maintenance Report</label>
											<div class="col-sm-2">
												<input type="text" class="form-control form-control-sm text-right" name="plan_reporting" id="plan_reporting" value="<?php echo $planReporting; ?>" readonly>
											</div>
											<div class="col-sm-2">
												<input type="text" class="form-control form-control-sm text-right" name="addon_reporting" id="addon_reporting" value="<?php echo $addonReporting; ?>" onchange="total_reportingx(1);">
											</div>
											<div class="col-sm-2">
												<?php $addonReporting = $addonReporting != NULL ? $addonReporting : 0; ?>
												<input type="text" class="form-control form-control-sm text-right" name="total_reporting" id="total_reporting" value="<?php echo $planReporting + $addonReporting; ?>" readonly>
											</div>
										</div>
										<div class="row mb-1">
											<label class="col-sm-6 col-form-label col-form-label-sm">TOTAL Preventive Maintenance Report</label>
											<div class="col-sm-2">
												<input type="text" class="form-control form-control-sm text-right" name="plan_preventive" id="plan_preventive" value="<?php echo $planPreventive; ?>" readonly>
											</div>
											<div class="col-sm-2">
												<input type="text" class="form-control form-control-sm text-right" name="addon_preventive" id="addon_preventive" value="<?php echo $addonPreventive; ?>" onchange="total_preventivex(1);">
											</div>
											<div class="col-sm-2">
												<?php $addonPreventive = $addonPreventive != NULL ? $addonPreventive : 0; ?>
												<input type="text" class="form-control form-control-sm text-right" name="total_preventive" id="total_preventive" value="<?php echo $planPreventive + $addonPreventive; ?>" readonly>
											</div>
										</div>
										<div class="row mb-1">
											<label class="col-sm-6 col-form-label col-form-label-sm">TOTAL Add-Move-Change Ticket</label>
											<div class="col-sm-2">
												<input type="text" class="form-control form-control-sm text-right" name="plan_ticket" id="plan_ticket" value="<?php echo $planTicket; ?>" readonly>
											</div>
											<div class="col-sm-2">
												<input type="text" class="form-control form-control-sm text-right" name="addon_ticket" id="addon_ticket" value="<?php echo $addonTicket; ?>" onchange="total_ticketx(1);">
											</div>
											<div class="col-sm-2">
												<?php $addonTicket = $addonTicket != NULL ? $addonTicket : 0; ?>
												<input type="text" class="form-control form-control-sm text-right" name="total_ticket" id="total_ticket" value="<?php echo $planTicket + $addonTicket; ?>" readonly>
											</div>
										</div>

										<label for="inputCID3" class="col-sm-12 alert alert-secondary mt-5"><b>Price</b></label>
										<div class="row mb-3">
											<label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Maintenance Price (sesuai PO/SPK)</label>
											<div class="col-sm-6">
												<input type="text" class="form-control form-control-sm" id="m_price" name="m_price" value="<?php if ($ver > 0 && $tmaintenance > 0) {
														echo $dmaintenance['implementation_price'];
													} ?>" style="text-align: right" onchange="m_change_price();" <?php echo $permission; ?>>
											</div>
										</div>

										<label for="inputCID3" class="col-sm-12 alert alert-secondary mt-5"><b>Business Trip</b></label>
										<div class="row mb-3">
											<label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Total Location</label>
											<div class="col-sm-6">
												<input type="text" class="form-control form-control-sm" id="m_bpd_total_location" name="m_bpd_total_location" value="<?php if ($ver > 0 && $tmaintenance > 0) {
														echo $dmaintenance['bpd_total_location'];
													} ?>" style="text-align: right;" <?php echo $permission; ?>>
											</div>
										</div>
										<div class="row mb-3">
											<label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Description of Location</label>
											<div class="col-sm-6">
												<textarea class="form-control" id="m_bpd_description" name="m_bpd_description" rows="3" <?php echo $permission; ?>><?php if ($ver > 0 && $tmaintenance > 0) {
														echo $dmaintenance['bpd_description'];
													} ?></textarea>
											</div>
										</div>
										<div class="row mb-3">
											<label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Subtotal Mandays/Product (IDR)</label>
											<div class="col-sm-6">
												<input type="text" class="form-control form-control-sm" id="m_bpd_price" name="m_bpd_price" value="<?php if ($ver > 0 && $tmaintenance > 0) {
														echo number_format($dmaintenance['bpd_price'], 2);
													} ?>" style="text-align: right;" onchange="m_change_bpd(1);" <?php echo $permission; ?>>
											</div>
										</div>

										<div id="m_outsourcing">
											<label for="inputCID3" class="col-sm-12 alert alert-secondary py-3 d-flex flex-row align-items-center justify-content-between mt-5">
												<b>Outsourcing Plan</b>
												<div class="align-items-right">
													<button type="button" class="btn btn-primary btn-sm" id="btnAdd_m_out" <?php echo $EditStatus ? "" : "disabled"; ?>>+</button>
												</div>
											</label>
											<div class="row fw-bold">
												<label for="inputCID3" class="col-sm-7 col-form-label col-form-label-sm">Description</label>
												<label for="inputCID3" class="col-sm-5 col-form-label col-form-label-sm">Price</label>
											</div>

											<?php
											if (isset($_GET['act']) && $_GET['act'] == "order") {
												$project_id = 0;
											} else {
												$project_id = $dsb['project_id'];
											}
											$mysql = sprintf(
												"SELECT `addon_id`,`addon_title`, `addon_price` 
											FROM `sa_trx_addon`
											WHERE `project_id`=%s AND `service_type`=2",
												GetSQLValueString($project_id, "int")
											);
											$addon = $DTSB->get_sql($mysql);
											$TotalResourceImplementation = 0;
											if ($addon[2] > 0) {
												$i = 0;
												do {
											?>
													<div class="row mb-3 group" id="addon_m_out">
														<div class="col-lg-7">
															<input type="text" id="m_out_title[<?php echo $i; ?>]" name="m_out['title'][]" class="form-control form-control-sm" value="<?php echo $ver > 0 ? $addon[0]['addon_title'] : ""; ?>" <?php echo $EditStatus ? "" : "disabled"; ?>>
														</div>
														<div class="col-lg-4">
															<input type="text" id="m_out_price[<?php echo $i; ?>]" name="m_out['price'][]" class="form-control form-control-sm m_price" value="<?php echo $ver > 0 ? number_format($addon[0]['addon_price'], 2) : ""; ?>" style="text-align: right;" onchange="maintenance_outsourcing_price(<?php echo $i; ?>);" <?php echo $EditStatus ? "" : "disabled"; ?>>
														</div>
														<div class="col-lg-1">
															<button id="btnRemove_m_out" type="button" class="btn btn-danger btn-sm" <?php echo $EditStatus ? "" : "disabled"; ?>>-</button>
														</div>
													</div>
											<?php
													$xxx = $ver > 0 ? $addon[0]['addon_price'] : 0;
													$TotalResourceImplementation += $xxx;
													$i++;
												} while ($addon[0] = $addon[1]->fetch_assoc());
											}
											?>

											<div id="Addon_m_out"></div>
											<hr />
											<div class="row mb-3">
												<label for="inputCID3" class="col-sm-7 col-form-label col-form-label-sm text-right fw-bold">Subtotal Outsourcing Plant (IDR)</label>
												<div class="col-sm-4">
													<input type="text" class="form-control form-control-sm" id="m_total_out_price" name="m_total_out_price" value="<?php echo number_format($TotalResourceImplementation, 2); ?>" style="text-align: right;" onchange="" readonly>
												</div>
											</div>
											<div class="row mb-5">
												<label for="inputCID3" class="col-sm-2 col-form-label col-form-label-sm">Note</label>
												<div class="col-sm-10">
													<textarea class="form-control" id="m_out_description" name="m_out_description" rows="3" <?php echo $permission; ?>><?php if ($ver > 0 && $dmaintenance > 0) {
															echo $dmaintenance['out_description'];
														} ?></textarea>
												</div>
											</div>
											<script>
												i2 = <?php echo $i; ?>;
												$(document).ready(function() {
													$("#btnAdd_m_out").click(function(btn) {
														var html = '';
														html += '<div class="row mb-3 group" id="addon_m_out">';
														html += '<div class="col-lg-7">';
														html += '<input type="text" id="m_out_title[' + i2 + ']" name="m_out[\'title\'][]" class="form-control form-control-sm">';
														html += '</div>';
														html += '<div class="col-lg-4">';
														html += '<input type="text" id="m_out_price[' + i2 + ']" name="m_out[\'price\'][]" class="form-control form-control-sm m_price" style="text-align: right;" onchange="maintenance_outsourcing_price(' + i2 + ');">';
														html += '</div>';
														html += '<div class="col-lg-1">';
														html += '<button id="btnRemove_m_out" type="button" class="btn btn-danger btn-sm">-</button>';
														html += '</div>';
														html += '</div>';

														$("#Addon_m_out").append(html);
														i2++;
													});

													$(document).on('click', '#btnRemove_m_out', function() {
														$(this).closest('#addon_m_out').remove();
													});
												});
											</script>
										</div>
									</div>

									<div class="col-lg-6">
										<!-- MAINTENANCE PACKGAE -->
										<label for="inputCID3" class="col-sm-12 alert alert-secondary py-3 d-flex flex-row align-items-center justify-content-between">
											<b>Maintenance Package</b>
											<div class="align-items-right">
												<button type="button" class="btn btn-primary btn-sm" id="btnAdd_m_pack" <?php echo $EditStatus ? "" : "disabled"; ?>>+</button>
											</div>
										</label>
										<div class="row fw-bold">
											<label for="inputCID3" class="col-sm-7 col-form-label col-form-label-sm">Add On</label>
											<label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Price</label>
										</div>

										<?php
										if (isset($_GET['act']) && $_GET['act'] == "order") {
											$project_id = 0;
										} else {
											$project_id = $dsb['project_id'];
										}
										$mysql = sprintf(
											"SELECT `addon_id`,`addon_title`, `addon_price` 
										FROM `sa_trx_addon`
										WHERE `project_id`=%s AND `service_type`=3",
											GetSQLValueString($project_id, "int")
										);
										$addon = $DTSB->get_sql($mysql);
										$TotalResourceImplementation = 0;
										if ($addon[2] > 0) {
											$i = 0;
											do {
										?>
												<div class="row mb-3 group" id="addon_m_pack">
													<div class="col-lg-7">
														<input type="text" id="m_pack_title[<?php echo $i; ?>]" name="m_pack['title'][]" class="form-control form-control-sm" value="<?php echo $ver > 0 ? $addon[0]['addon_title'] : ""; ?>" <?php echo $EditStatus ? "" : "disabled"; ?>>
													</div>
													<div class="col-lg-4">
														<input type="text" id="m_pack_price[<?php echo $i; ?>]" name="m_pack['price'][]" class="form-control form-control-sm m_addon" value="<?php echo $ver > 0 ? number_format($addon[0]['addon_price'], 2) : ""; ?>" style="text-align: right;" onchange="maintenance_addon(<?php echo $i; ?>);" <?php echo $EditStatus ? "" : "disabled"; ?>>
													</div>
													<div class="col-lg-1">
														<button id="btnRemove_m_pack" type="button" class="btn btn-danger btn-sm" <?php echo $EditStatus ? "" : "disabled"; ?>>-</button>
													</div>
												</div>
										<?php
												$xxx = $ver > 0 ? $addon[0]['addon_price'] : 0;
												$TotalResourceImplementation += $xxx;
												$i++;
											} while ($addon[0] = $addon[1]->fetch_assoc());
										}
										?>

										<div id="Addon_m_pack"></div>
										<hr />
										<div class="row mb-3">
											<label for="inputCID3" class="col-sm-7 col-form-label col-form-label-sm text-right fw-bold">Subtotal Package Plant (IDR)</label>
											<div class="col-sm-4">
												<input type="text" class="form-control form-control-sm" id="m_total_pack_price" name="m_total_pack_price" value="<?php echo number_format($TotalResourceImplementation, 2); ?>" style="text-align: right;" onchange="" readonly>
											</div>
										</div>
										<div class="row mb-3">
											<label for="inputCID3" class="col-sm-7 col-form-label col-form-label-sm text-right fw-bold">Other (Non-Addon) (IDR)</label>
											<div class="col-sm-4">
												<input type="text" class="form-control form-control-sm" id="m_package_other_price" name="m_package_other_price" value="" style="text-align: right;" onchange="" readonly>
											</div>
										</div>
										<div class="row mb-3">
											<label for="inputCID3" class="col-sm-7 col-form-label col-form-label-sm text-right fw-bold">Total Maintenance Package (IDR) <i class="fas fa-question-circle" data-bs-toggle="popover" data-bs-trigger="focus" title="Total Maintenance Package (IDR) = Maintenance Price (sesuai PO/SPK) - Subtotal Mandays/Product (IDR) - Subtotal Outsourcing Plant (IDR) - Subtotal Add On (IDR) - Backup Unit"></i></label>
											<div class="col-sm-4">
												<input type="text" class="form-control form-control-sm" id="m_package_price" name="m_package_price" value="<?php if ($ver > 0 && $tmaintenance > 0) {
														echo number_format($dmaintenance['maintenance_package_price'], 2);
													} ?>" style="text-align: right;" onchange="" readonly>
											</div>
										</div>
										<script>
											i3 = <?php echo $i; ?>;
											$(document).ready(function() {
												$("#btnAdd_m_pack").click(function(btn) {
													var html = '';
													html += '<div class="row mb-3 group" id="addon_m_pack">';
													html += '<div class="col-lg-7">';
													html += '<input type="text" id="m_pack_title[' + i3 + ']" name="m_pack[\'title\'][]" class="form-control form-control-sm">';
													html += '</div>';
													html += '<div class="col-lg-4">';
													html += '<input type="text" id="m_pack_price[' + i3 + ']" name="m_pack[\'price\'][]" class="form-control form-control-sm m_addon" style="text-align: right;" onchange="maintenance_addon(' + i3 + ');">';
													html += '</div>';
													html += '<div class="col-lg-1">';
													html += '<button id="btnRemove_m_pack" type="button" class="btn btn-danger btn-sm">-</button>';
													html += '</div>';
													html += '</div>';

													$("#Addon_m_pack").append(html);
													i3++;
												});

												$(document).on('click', '#btnRemove_m_pack', function() {
													$(this).closest('#addon_m_pack').remove();
												});
											});
										</script>

										<div id="backup_unit">
											<!-- Start Existing Backup Unit -->
											<label for="inputCID3" class="col-sm-12 alert alert-secondary py-3 d-flex flex-row align-items-center justify-content-between mt-5">
												<b>Existing Backup Unit</b>
												<div class="align-items-right">
													<button type="button" class="btn btn-primary btn-sm" id="btnAdd_ExistingBackupUnit" <?php echo $EditStatus ? "" : "disabled"; ?>>+</button>
												</div>
											</label>
											<div class="row">
												<label for="inputCID3" class="col-sm-7 col-form-label col-form-label-sm fw-bold">Brand</label>
												<label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm fw-bold">Price</label>
											</div>

											<?php
											if (isset($_GET['act']) && $_GET['act'] == "order") {
												$project_id = 0;
											} else {
												$project_id = $dsb['project_id'];
											}
											$mysql = sprintf(
												"SELECT `addon_id`,`addon_title`, `addon_price` 
											FROM `sa_trx_addon`
											WHERE `project_id`=%s AND `service_type`=4",
												GetSQLValueString($project_id, "int")
											);
											$addon = $DTSB->get_sql($mysql);
											$TotalResourceImplementation = 0;
											if ($addon[2] > 0) {
												$i = 0;
												do {
											?>
													<div class="row mb-3 group" id="existing_backup_unit">
														<div class="col-lg-7">
															<input type="text" id="e_backup_title[<?php echo $i; ?>]" name="e_backup['title'][]" class="form-control form-control-sm" value="<?php echo $ver > 0 ? $addon[0]['addon_title'] : ""; ?>" <?php echo $EditStatus ? "" : "disabled"; ?>>
														</div>
														<div class="col-lg-4">
															<input type="text" id="e_backup_price[<?php echo $i; ?>]" name="e_backup['price'][]" class="form-control form-control-sm m_existing" value="<?php echo $ver > 0 ? number_format($addon[0]['addon_price'], 2) : ""; ?>" style="text-align: right;" onchange="maintenance_existing_backup(<?php echo $i; ?>);" <?php echo $EditStatus ? "" : "disabled"; ?>>
														</div>
														<div class="col-lg-1">
															<button id="btnRemove_e_backup" type="button" class="btn btn-danger btn-sm" <?php echo $EditStatus ? "" : "disabled"; ?>>-</button>
														</div>
													</div>
											<?php
													$xxx = $ver > 0 ? $addon[0]['addon_price'] : 0;
													$TotalResourceImplementation += $xxx;
													$i++;
												} while ($addon[0] = $addon[1]->fetch_assoc());
											}
											?>

											<div id="Addon_e_backup"></div>
											<hr />
											<div class="row mb-3">
												<label for="inputCID3" class="col-sm-7 col-form-label col-form-label-sm text-right fw-bold">Total</label>
												<div class="col-sm-4">
													<input type="text" class="form-control form-control-sm" id="e_total_backup_price" name="e_total_backup_price" value="<?php echo number_format($TotalResourceImplementation, 2); ?>" style="text-align: right;" onchange="" readonly>
												</div>
											</div>
											<div class="row mb-3">
												<div class="col-sm-12 text-right">
													<!-- <button type="button" class="btn btn-primary btn-sm" <?php //echo $EditStatus ? "" : "disabled"; 
														?>>Upload File</button> -->
												</div>
											</div>
											<script>
												i4 = <?php echo $i; ?>;
												$(document).ready(function() {
													$("#btnAdd_ExistingBackupUnit").click(function(btn) {
														var html = '';
														html += '<div class="row mb-3 group" id="existing_backup_unit">';
														html += '<div class="col-lg-7">';
														html += '<input type="text" id="e_backup_title[' + i4 + ']" name="e_backup[\'title\'][]" class="form-control form-control-sm">';
														html += '</div>';
														html += '<div class="col-lg-4">';
														html += '<input type="text" id="e_backup_price[' + i4 + ']" name="e_backup[\'price\'][]" class="form-control form-control-sm m_existing" style="text-align: right;" onchange="maintenance_existing_backup(' + i4 + ');">';
														html += '</div>';
														html += '<div class="col-lg-1">';
														html += '<button id="btnRemove_e_backup" type="button" class="btn btn-danger btn-sm">-</button>';
														html += '</div>';
														html += '</div>';

														$("#Addon_e_backup").append(html);
														i4++;
													});

													$(document).on('click', '#btnRemove_e_backup', function() {
														$(this).closest('#existing_backup_unit').remove();
													});
												});
											</script>
											<!-- End Existing Backup Unit -->

											<!-- Start Investment Backup Unit -->
											<label for="inputCID3" class="col-sm-12 alert alert-secondary py-3 d-flex flex-row align-items-center justify-content-between mt-5">
												<b>Investment Backup Unit</b>
												<div class="align-items-right">
													<button type="button" class="btn btn-primary btn-sm" id="btnAdd_InvestmentBackupUnit" <?php echo $EditStatus ? "" : "disabled"; ?>>+</button>
												</div>
											</label>
											<div class="row">
												<label for="inputCID3" class="col-sm-7 col-form-label col-form-label-sm fw-bold">Brand</label>
												<label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm fw-bold">Price</label>
											</div>

											<?php
											if (isset($_GET['act']) && $_GET['act'] == "order") {
												$project_id = 0;
											} else {
												$project_id = $dsb['project_id'];
											}
											$mysql = sprintf(
												"SELECT `addon_id`,`addon_title`, `addon_price` 
											FROM `sa_trx_addon`
											WHERE `project_id`=%s AND `service_type`=5",
												GetSQLValueString($project_id, "int")
											);
											$addon = $DTSB->get_sql($mysql);
											$TotalResourceImplementation = 0;
											if ($addon[2] > 0) {
												$i = 0;
												do {
											?>
													<div class="row mb-3 group" id="investment_backup_unit">
														<div class="col-lg-7">
															<input type="text" id="inv_backup_title[<?php echo $i; ?>]" name="inv_backup['title'][]" class="form-control form-control-sm" value="<?php echo $ver > 0 ? $addon[0]['addon_title'] : ""; ?>" <?php echo $EditStatus ? "" : "disabled"; ?>>
														</div>
														<div class="col-lg-4">
															<input type="text" id="inv_backup_price[<?php echo $i; ?>]" name="inv_backup['price'][]" class="form-control form-control-sm inv_backup" value="<?php echo $ver > 0 ? number_format($addon[0]['addon_price'], 2) : ""; ?>" style="text-align: right;" onchange="maintenance_investment_backup(<?php echo $i; ?>);" <?php echo $EditStatus ? "" : "disabled"; ?>>
														</div>
														<div class="col-lg-1">
															<button id="btnRemove_inv_backup" type="button" class="btn btn-danger btn-sm" <?php echo $EditStatus ? "" : "disabled"; ?>>-</button>
														</div>
													</div>
													<?php
													$xxx = $ver > 0 ? $addon[0]['addon_price'] : 0;
													$TotalResourceImplementation += $xxx;
													$i++;
												} while ($addon[0] = $addon[1]->fetch_assoc());
											}
											?>
											<div id="Addon_inv_backup"></div>
											<hr />
											<div class="row mb-3">
												<label for="inputCID3" class="col-sm-7 col-form-label col-form-label-sm text-right fw-bold">Total</label>
												<div class="col-sm-4">
													<input type="text" class="form-control form-control-sm" id="inv_total_backup_price" name="inv_total_backup_price" value="<?php echo number_format($TotalResourceImplementation, 2); ?>" style="text-align: right;" onchange="" readonly>
												</div>
											</div>
											<div class="row mb-3" id="InvestmentBackup">
												<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Dedicate Backup Unit</label>
												<div class="col-sm-9">
													<div class="form-check form-check-inline">
														<input class="form-check-input" type="radio" name="dedicate_backup" id="dedicate_backup1" value="1" 
															<?php 
															if($ver > 0)
															{
																if($tmaintenance > 0 && $dmaintenance['dedicate_backup'] == "1")
																{
																	echo " checked";
																} else
																{
																	echo " unchecked";
																}
															} 
															echo $EditStatus ? "" : " disabled"; 
															?>
														>
														<label class="form-check-label" for="inlineRadio1">Yes</label>
													</div>
													<div class="form-check form-check-inline">
														<input class="form-check-input" type="radio" name="dedicate_backup" id="dedicate_backup2" value="0" 
															<?php 
															if($ver > 0)
															{
																if($tmaintenance > 0 && $dmaintenance['dedicate_backup'] == "0")
																{
																	echo " checked";
																} else
																{
																	echo " unchecked";
																}
															} 
															echo $EditStatus ? "" : " disabled"; 
															?>
														>
														<label class="form-check-label" for="inlineRadio2">No</label>
													</div>
												</div>
												<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">File BOQ <span style="font-size:11px">(Last uploaded)</span></label>
												<div class="col-sm-9">
													<input type="text" class="form-control form-control-sm" id="fileName" name="fileName" value="<?php echo $dmaintenance['file_backup']!=null ? $dmaintenance['file_backup'] : ""; ?>" readonly>
												</div>
											</div>
											<script>
												document.getElementById("InvestmentBackup").style.display = "none";
											</script>
											<script>
												i5 = <?php echo $i; ?>;
												$(document).ready(function() {
													$("#btnAdd_InvestmentBackupUnit").click(function(btn) {
														var html = '';
														html += '<div class="row mb-3 group" id="investment_backup_unit">';
														html += '<div class="col-lg-7">';
														html += '<input type="text" id="inv_backup_title[' + i5 + ']" name="inv_backup[\'title\'][]" class="form-control form-control-sm">';
														html += '</div>';
														html += '<div class="col-lg-4">';
														html += '<input type="text" id="inv_backup_price[' + i5 + ']" name="inv_backup[\'price\'][]" class="form-control form-control-sm inv_backup" style="text-align: right;" onchange="maintenance_investment_backup(' + i5 + ');">';
														html += '</div>';
														html += '<div class="col-lg-1">';
														html += '<button id="btnRemove_inv_backup" type="button" class="btn btn-danger btn-sm">-</button>';
														html += '</div>';
														html += '</div>';

														$("#Addon_inv_backup").append(html);
														i5++;
													});

													$(document).on('click', '#btnRemove_inv_backup', function() {
														let nTotalx = deformat(document.getElementById("inv_total_backup_price").value);
														let ix = i5-1;
														let nTotal = nTotalx - deformat(document.getElementById('inv_backup_price[' + ix + ']').value);
														document.getElementById("inv_total_backup_price").value = format(nTotal);
																	if(nTotal==0)
														{
															document.getElementById("InvestmentBackup").style.display = "none";
															// document.getElementById("BOQUpload").setAttribute("disabled", true);
														} else
														{
															document.getElementById("InvestmentBackup").style.display = "";
															// document.getElementById("BOQUpload").removeAttribute("disabled");
														}
														$(this).closest('#investment_backup_unit').remove();
													});
												});
											</script>
											<!-- End Investment Backup Unit -->
										</div>
									</div>
								</div>
								<!-- End Mandays Calculation -->
							</div>
						</div>
					</div>

					<!-- TAB Extended Warranty -->
					<div class="tab-pane fade" id="ExtendedWarranty" role="tabpanel" aria-labelledby="extendedwarranty-tab">
						<?php //include("components/modules/service_budget/func_warranty.php"); 
						?>

						<div class="card shadow mb-4">
							<!-- Card Body -->
							<div class="card-body">
								<div class="row mb-5">
									<div class="col-lg-6">
										<label for="inputCID3" class="col-sm-12 alert alert-secondary"><b>Service Catalog</b></label>
										<div class="row mb-2">
											<label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Service Type</label>
											<div class="col-sm-4">
												<?php
												global $DTSB;
												$tblname = "mst_type_of_service";
												$condition = "service_type=3";
												$tos = $DTSB->get_data($tblname, $condition);
												$dtos = $tos[0];
												$qtos = $tos[1];
												$tosidexp = "";
												if ($ver > 0 && isset($dwarranty['tos_id'])) {
													$tosidexp = explode(";", $dwarranty['tos_id']);
												}
												$i = 0;
												do {
												?>
													<div class="form-check form-check-inline">
														<input class="form-check-input" type="radio" name="w_tos_id" id="w_tos_id<?php echo $i; ?>" value="<?php echo $dtos['tos_id']; ?>" 
															<?php
															if ($ver > 0 && $tosidexp != "") {
																for ($j = 0; $j < count($tosidexp); $j++) {
																	if ($tosidexp[$j] == $dtos['tos_id']) {
																		echo ' checked ';
																	}
																}
															} ?> <?php if ($permission == 'readonly') {
																echo 'disabled';
															} ?>>
														<label class="form-check-label" for="inlineRadio1"><?php echo $dtos['tos_name']; ?></label>
													</div>
													<?php $i++; ?>
												<?php } while ($dtos = $qtos->fetch_assoc()); ?>
											</div>
										</div>
										<div class="row mb-3">
											<label for="inputCID3" class="col-sm-6 col-form-label col-form-label-sm">Estimation Warranty Duration</label>
											<div class="col-sm-2">
												<input type="text" class="form-control form-control-sm" id="w_project_estimation" name="w_project_estimation" value="<?php
													if ($ver > 0 && $twarranty > 0) {
														echo $dwarranty['project_estimation'];
													} ?>" style="text-align: right;" <?php echo $permission; ?>>
											</div>
											<div class="col-sm-3">
												<select class="form-select form-select-sm" aria-label=".form-select-sm example" name="w_project_estimation_id" <?php echo $permission; ?> <?php if ($permission == 'readonly') {
														echo 'disabled';
													} ?>>
													<option value="1" <?php if (($ver > 0 && $twarranty > 0) && (1 == $dwarranty['project_estimation_id'])) {
															echo 'selected';
														} ?>>Days</option>
													<option value="2" <?php if (($ver > 0 && $twarranty > 0) && (2 == $dwarranty['project_estimation_id'])) {
															echo 'selected';
														} ?>>Months</option>
													<option value="3" <?php if (($ver > 0 && $twarranty > 0) && (3 == $dwarranty['project_estimation_id'])) {
															echo 'selected';
														} ?>>Years</option>
												</select>
											</div>
										</div>

										<label for="inputCID3" class="col-sm-12 alert alert-secondary mt-5 fw-bold">Price</label>
										<div class="row">
											<label for="inputCID3" class="col-lg-6 col-form-label col-form-label-sm">Extended Warranty Price (sesuai PO/SPK)</label>
											<div class="col-lg-6">
												<input type="text" class="form-control form-control-sm text-right" id="w_price" name="w_price" value="<?php echo ($ver > 0 && $twarranty > 0) ? $dwarranty['implementation_price'] : 0; ?>" onchange="w_change_price();">
											</div>
										</div>

										<!-- Non-Cisco Product -->
										<label for="inputCID3" class="col-sm-12 alert alert-secondary py-3 d-flex flex-row align-items-center justify-content-between fw-bold mt-5">
											Addon Warranty
											<div class="align-items-right">
												<button type="button" class="btn btn-primary btn-sm" id="btnAddon_Warranty" <?php echo $EditStatus ? "" : "disabled"; ?>>+</button>
											</div>
										</label>
										<div class="row">
											<label for="inputCID3" class="col-sm-7 col-form-label col-form-label-sm fw-bold">Brand</label>
											<label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm fw-bold">Price</label>
										</div>
										<?php
										if (isset($_GET['act']) && $_GET['act'] == "order") {
											$project_id = 0;
										} else {
											$project_id = $dsb['project_id'];
										}
										$mysql = sprintf(
											"SELECT `addon_id`,`addon_title`, `addon_price` 
										FROM `sa_trx_addon`
										WHERE `project_id`=%s AND `service_type`=8",
											GetSQLValueString($project_id, "int")
										);
										$addon = $DTSB->get_sql($mysql);
										$TotalResourceImplementation = 0;
										if ($addon[2] > 0) {
											$i = 0;
											do {
										?>
												<div class="row mb-3 group" id="warranty_addon">
													<div class="col-lg-7">
														<input type="text" id="warranty_addon_title[<?php echo $i; ?>]" name="warranty_addon['title'][]" class="form-control form-control-sm" value="<?php echo $ver > 0 ? $addon[0]['addon_title'] : ""; ?>" <?php echo $EditStatus ? "" : "disabled"; ?>>
													</div>
													<div class="col-lg-4">
														<input type="text" id="warranty_addon_price[<?php echo $i; ?>]" name="warranty_addon['price'][]" class="form-control form-control-sm warranty_addon" value="<?php echo $ver > 0 ? number_format($addon[0]['addon_price'], 2) : ""; ?>" style="text-align: right;" onchange="warranty_addon_pricex(<?php echo $i; ?>);" <?php echo $EditStatus ? "" : "disabled"; ?>>
													</div>
													<div class="col-lg-1">
														<button id="btnRemove_warranty_addon" type="button" class="btn btn-danger btn-sm" <?php echo $EditStatus ? "" : "disabled"; ?>>-</button>
													</div>
												</div>
										<?php
												$xxx = $ver > 0 ? $addon[0]['addon_price'] : 0;
												$TotalResourceImplementation += $xxx;
												$i++;
											} while ($addon[0] = $addon[1]->fetch_assoc());
										}
										?>

										<div id="Addon_warranty_addon"></div>
										<hr />
										<div class="row mb-3">
											<label for="inputCID3" class="col-sm-7 col-form-label col-form-label-sm text-right fw-bold">Total</label>
											<div class="col-sm-4">
												<input type="text" class="form-control form-control-sm" id="warranty_addon_price" name="warranty_addon_price" value="<?php echo number_format($TotalResourceImplementation, 2); ?>" style="text-align: right;" onchange="" readonly>
											</div>
										</div>
										<script>
											i7 = <?php echo $i; ?>;
											$(document).ready(function() {
												$("#btnAddon_Warranty").click(function(btn) {
													var html = '';
													html += '<div class="row mb-3 group" id="warranty_addon">';
													html += '<div class="col-lg-7">';
													html += '<input type="text" id="warranty_addon_title[' + i7 + ']" name="warranty_addon[\'title\'][]" class="form-control form-control-sm">';
													html += '</div>';
													html += '<div class="col-lg-4">';
													html += '<input type="text" id="warranty_addon_price[' + i7 + ']" name="warranty_addon[\'price\'][]" class="form-control form-control-sm warranty_addon" style="text-align: right;" onchange="warranty_addon_pricex(' + i7 + ');">';
													html += '</div>';
													html += '<div class="col-lg-1">';
													html += '<button id="btnRemove_warranty_addon" type="button" class="btn btn-danger btn-sm">-</button>';
													html += '</div>';
													html += '</div>';

													$("#Addon_warranty_addon").append(html);
													i7++;
												});

												$(document).on('click', '#btnRemove_warranty_addon', function() {
													$(this).closest('#warranty_addon').remove();
													// warranty_addon_pricex2();
												});
											});
										</script>
										<!-- End Non-Cisco Product -->
									</div>

									<div class="col-lg-6">
										<!-- Cisco Product -->
										<label for="inputCID3" class="col-sm-12 alert alert-secondary py-3 d-flex flex-row align-items-center justify-content-between">
											<b>Cisco Product</b>
										</label>
										<div class="row">
											<label for="inputCID3" class="col-sm-7 col-form-label col-form-label-sm fw-bold">Brand</label>
											<label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm fw-bold">Price List (Cisco)</label>
										</div>
										<?php
										if (isset($_GET['act']) && $_GET['act'] == "order") {
											$project_id = 0;
										} else {
											$project_id = $dsb['project_id'];
										}
										$mysql = sprintf(
											"SELECT `addon_id`,`addon_title`, `addon_price` 
										FROM `sa_trx_addon`
										WHERE `project_id`=%s AND `service_type`=6",
											GetSQLValueString($project_id, "int")
										);
										$addon = $DTSB->get_sql($mysql);
										$TotalResourceImplementation = 0;
										$price = 0;
										if ($addon[2] > 0) {
											$price = $addon[0]['addon_price'];
										}
										?>
										<div class="row mb-3 group" id="warranty_Cisco">
											<div class="col-lg-7">
												<input type="text" id="warranty_Cisco_title[0]" name="warranty_Cisco['title'][]" class="form-control form-control-sm" value="Cisco" readonly>
											</div>
											<div class="col-lg-4">
												<input type="text" id="warranty_Cisco_price[0]" name="warranty_Cisco['price'][]" class="form-control form-control-sm xxx" value="<?php echo number_format($price, 2); ?>" style="text-align: right;" onfocus="deformat();" onchange="warranty_Cisco_pricex();" <?php echo $EditStatus ? "" : "disabled"; ?>>
											</div>
										</div>
										<!-- End Cisco Product -->

										<!-- Non-Cisco Product -->
										<label for="inputCID3" class="col-sm-12 alert alert-secondary py-3 d-flex flex-row align-items-center justify-content-between fw-bold mt-5">
											Non-Cisco Product
											<div class="align-items-right">
												<button type="button" class="btn btn-primary btn-sm" id="btnAdd_Warranty" <?php echo $EditStatus ? "" : "disabled"; ?>>+</button>
											</div>
										</label>
										<div class="row">
											<label for="inputCID3" class="col-sm-7 col-form-label col-form-label-sm fw-bold">Brand</label>
											<label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm fw-bold">Discounted (Non Cisco)</label>
										</div>
										<?php
										if (isset($_GET['act']) && $_GET['act'] == "order") {
											$project_id = 0;
										} else {
											$project_id = $dsb['project_id'];
										}
										$mysql = sprintf(
											"SELECT `addon_id`,`addon_title`, `addon_price` 
										FROM `sa_trx_addon`
										WHERE `project_id`=%s AND `service_type`=7",
											GetSQLValueString($project_id, "int")
										);
										$addon = $DTSB->get_sql($mysql);
										$TotalResourceImplementation = 0;
										if ($addon[2] > 0) {
											$i = 0;
											do {
										?>
												<div class="row mb-3 group" id="warranty_nCisco">
													<div class="col-lg-7">
														<input type="text" id="warranty_nCisco_title[<?php echo $i; ?>]" name="warranty_nCisco['title'][]" class="form-control form-control-sm" value="<?php echo $ver > 0 ? $addon[0]['addon_title'] : ""; ?>" <?php echo $EditStatus ? "" : "disabled"; ?>>
													</div>
													<div class="col-lg-4">
														<input type="text" id="warranty_nCisco_price[<?php echo $i; ?>]" name="warranty_nCisco['price'][]" class="form-control form-control-sm non_cisco" value="<?php echo $ver > 0 ? number_format($addon[0]['addon_price'], 2) : ""; ?>" style="text-align: right;" onchange="warranty_nCisco_pricex(<?php echo $i; ?>);" <?php echo $EditStatus ? "" : "disabled"; ?>>
													</div>
													<div class="col-lg-1">
														<button id="btnRemove_warranty_nCisco" type="button" class="btn btn-danger btn-sm" <?php echo $EditStatus ? "" : "disabled"; ?>>-</button>
													</div>
												</div>
										<?php
												$xxx = $ver > 0 ? $addon[0]['addon_price'] : 0;
												$TotalResourceImplementation += $xxx;
												$i++;
											} while ($addon[0] = $addon[1]->fetch_assoc());
										}
										?>

										<div id="Addon_warranty_nCisco"></div>
										<hr />
										<div class="row mb-3">
											<label for="inputCID3" class="col-sm-7 col-form-label col-form-label-sm text-right fw-bold">Total</label>
											<div class="col-sm-4">
												<input type="text" class="form-control form-control-sm" id="warranty_nCisco_price" name="warranty_nCisco_price" value="<?php echo number_format($TotalResourceImplementation, 2); ?>" style="text-align: right;" onchange="" readonly>
											</div>
										</div>
										<script>
											i6 = <?php echo $i; ?>;
											$(document).ready(function() {
												$("#btnAdd_Warranty").click(function(btn) {
													var html = '';
													html += '<div class="row mb-3 group" id="warranty_nCisco">';
													html += '<div class="col-lg-7">';
													html += '<input type="text" id="warranty_nCisco_title[' + i6 + ']" name="warranty_nCisco[\'title\'][]" class="form-control form-control-sm">';
													html += '</div>';
													html += '<div class="col-lg-4">';
													html += '<input type="text" id="warranty_nCisco_price[' + i6 + ']" name="warranty_nCisco[\'price\'][]" class="form-control form-control-sm non_cisco" style="text-align: right;" onchange="warranty_nCisco_pricex(' + i6 + ');">';
													html += '</div>';
													html += '<div class="col-lg-1">';
													html += '<button id="btnRemove_warranty_nCisco" type="button" class="btn btn-danger btn-sm">-</button>';
													html += '</div>';
													html += '</div>';

													$("#Addon_warranty_nCisco").append(html);
													i6++;
												});

												$(document).on('click', '#btnRemove_warranty_nCisco', function() {
													$(this).closest('#warranty_nCisco').remove();
												});
											});
										</script>
										<!-- End Non-Cisco Product -->
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- TAB File Upload -->
					<div class="tab-pane fade" id="FileUpload" role="tabpanel" aria-labelledby="fileupload-tab">
						<?php
						global $DB;
						$tblname = 'cfg_web';
						$condition = 'config_key="MEDIA_SERVICE_BUDGET" AND parent=8';
						$folders = $DB->get_data($tblname, $condition);
						$FolderName = 'service_budget';
						$sFolderTarget = $folders[0]['config_value'] . '/' . $dsb['customer_code'] . '_' . str_replace(".", "", str_replace(' ', '_', $dsb['customer_name'])) . '/' . $dsb['project_code'] . '/' . $FolderName . '/';
						$sSubFolders = explode("/", $sFolderTarget);
						$xFolder = "";
						for ($i = 0; $i < count($sSubFolders); $i++) {
							if ($i == 0) {
								$xFolder .= $sSubFolders[$i];
							} else {
								$xFolder .= '/' . $sSubFolders[$i];
							}
							if ($sSubFolders[$i] != "..") {
								if (!(is_dir($xFolder))) {
									mkdir($xFolder, 0777, true);
									$file = 'media/index.php';
									$newfile = $xFolder . '/index.php';

									if (!copy($file, $newfile)) {
										echo "failed to copy $file...\n";
									}
								}
							}
						}
						?>
						<script>
							var FolderTarget = "<?php echo $sFolderTarget; ?>";
							document.cookie = "FolderTarget = " + FolderTarget;
						</script>
						<div class="card shadow mb-4">
							<!-- Card Body -->
							<div class="card-body">
								<div class="row mb-3">
									<div class="col-lg-12">
										<!-- <div class="row mb-3"> -->
										<button type="button" class="btn btn-primary" name="btn_upload" id="btn_upload" data-bs-toggle="modal" data-bs-target="#fileupload" <?php if ($permission == 'readonly') {
												echo 'disabled';
											} ?>>Upload File</button>
										<!-- </div> -->
									</div>
								</div>
								<div class="row mb-3">
									<div class="col-lg-12">
										<div id="fileList"></div>
									</div>
									<div class="col-lg-12">
										<?php
										$d = dir($sFolderTarget);
										?>
										<table class="table table-sm table-hover">
											<thead>
												<tr>
													<th scope="col">#</th>
													<th scope="col">Nama File</th>
													<th scope="col">Size</th>
													<th scope="col">Modified</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$i = 0;
												while (false !== ($entry = $d->read())) {
													if ($entry != '.' && $entry != '..' && $entry != 'index.php') {
														$fstat = stat($sFolderTarget . $entry);
														// if(strpos($entry,$project_id)!="")
														// {
														?>
														<tr>
															<th scope="row"><?php echo $i + 1; ?></th>
															<td><a href="<?php echo $sFolderTarget . $entry; ?>" target="_blank" class="text-body"><?php echo $entry; ?></a></td>
															<td class="text-center">
																<?php
																if ($fstat['size'] < 1024) {
																	echo number_format($fstat['size'], 2) . ' B';
																} elseif ($fstat['size'] < (1024 * 1024)) {
																	echo number_format($fstat['size'] / 1024, 2) . ' KB';
																} elseif ($fstat['size'] < (1024 * 1024 * 1024)) {
																	echo number_format($fstat['size'] / (1024 * 1024), 2) . ' MB';
																} elseif ($fstat['size'] < (1024 * 1024 * 1024 * 1024)) {
																	echo number_format($fstat['size'] / (1024 * 1024 * 1024), 2) . ' GB';
																}
																?>
															</td>
															<td><?php echo date('d-M-Y G:i:s', $fstat['mtime']); ?></td>
														</tr>
													<?php
														// }
														$i++;
													}
												}
												if ($i == 0) {
													?>
													<tr>
														<td colspan="4">No Files available.</td>
													</tr>
												<?php
												}
												?>
											</tbody>
										</table>
										<?php
										$d->close();
										?>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- TAB History -->
					<div class="tab-pane fade" id="History" role="tabpanel" aria-labelledby="history-tab">
						<div class="card shadow mb-4">
							<div class="card-body">
								<?php
								$maxRows = 100;
								if (isset($_GET['maxRows'])) {
									$maxRows = $_GET['maxRows'];
								}
								$tblname = "view_logs";
								$condition = "project_code = '" . $dsb['project_code'] . "' AND (so_number = '" . $dsb['so_number'] . "' OR order_number = '" . $dsb['order_number'] . "') AND `description`!=''";
								$order = "log_id DESC";
								$his2 = $DTSB->get_data($tblname, $condition, $order, 0, $maxRows);
								if ($his2[2] > 0) {
								?>
									<h5>History</h5>
									<table class="table">
										<thead class="bg-light">
											<th class="col-lg-2">Date</th>
											<th class="col-lg-2">Time</th>
											<th class="col-lg-8">Description</th>
										</thead>
										<tbody>
											<?php
											$tgl = "";
											?>
											<?php do { ?>
												<tr>
													<td style="font-size: 12px">
														<?php if ($tgl != date("Y-m-d", strtotime($his2[0]['entry_date']))) { ?>
															<table class="table table-sm table-light table-striped">
																<tr>
																	<td class="text-center fw-bold" colspan="2">
																		<?php echo date("Y", strtotime($his2[0]['entry_date'])); ?>
																	</td>
																</tr>
																<tr>
																	<td class="text-center"><?php echo date("M", strtotime($his2[0]['entry_date'])); ?></td>
																	<td class="text-center"><?php echo date("d", strtotime($his2[0]['entry_date'])); ?></td>
																</tr>
															</table>

														<?php } ?>
													</td>
													<td style="font-size: 12px"><?php echo date("H:i:s", strtotime($his2[0]['entry_date'])); ?></td>
													<?php
													if ($his2[0]['entry_by'] != "system") {
														if (strpos($his2[0]['entry_by'], "<") == 0) {
															$name = $DBHCM->get_profile($his2[0]['entry_by'], "employee_name");
														} else {
															$name = $his2[0]['entry_by'];
														}
													} else {
														$name = "system";
													}
													?>
													<td style="font-size: 12px">
														<?php
														$desc = str_replace(";", "<br/>", $his2[0]['description']);
														echo $desc . "Performed by " . $name;
														?>
													</td>
												</tr>
												<?php $tgl = date("Y-m-d", strtotime($his2[0]['entry_date'])); ?>
											<?php } while ($his2[0] = $his2[1]->fetch_assoc()); ?>
										</tbody>
									</table>
								<?php } ?>
								<!-- <div class="" style="font-size: 12px">Readmore...</div> -->
							</div>
						</div>

					</div>
				</div>
				<input type="hidden" name="version" value="<?php echo $ver; ?>">
				<input type="hidden" name="project_id" value="<?php
					if ($srcdata == "project") {
						echo $dsb['project_id'];
					} else {
						echo 0;
					}
					?>">
				<input type="hidden" name="create_date" value="<?php if ($ver > 0) {
						echo $dsb['create_date'];
					} else {
						echo date("Y-m-d G:i:s");
					} ?>">
				<input type="hidden" name="create_by" value="<?php if ($ver > 0) {
						echo $dsb['create_by'];
					} else {
						echo $_SESSION['Microservices_UserEmail'];
					} ?>">
				<input type="hidden" name="status" value="<?php if ($ver > 0) {
						echo $dsb['status'];
					} else {
						echo 'draft';
					} ?>">
				<div class="mb-3 bg-light">
					<?php
					if ((!isset($dsb['status']) || (isset($dsb['status']) && ($dsb['status'] == 'draft' || $dsb['status'] == 'rejected' || $dsb['status'] == 'reopen')) && (USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION == "dbf36ff3e3827639223983ee8ac47b42" || USERPERMISSION == "726ea0dd998698e8a87f8e344d373533" || USERPERMISSION == "5898299487c5b9cdbe7d61809fd20213" || USERPERMISSION == "335a66c239a137964a33e8c60b24e3d9" || USERPERMISSION == "0162bce636a63c3ae499224203e06ed0"))) {
					?>
						<button type="button" class="btn btn-primary" name="btn_save" id="btn_save" onclick="check_error(1);" data-bs-toggle="modal" data-bs-target="#save" <?php if ($permission == "readonly") {
								echo "disabled";
							} ?>>Save</button>
					<?php
					}

					if (isset($dsb['status']) && ($dsb['status'] == 'draft' || $dsb['status'] == 'rejected' || $dsb['status'] == 'reopen') && (USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION == "726ea0dd998698e8a87f8e344d373533" || USERPERMISSION == "dbf36ff3e3827639223983ee8ac47b42" || USERPERMISSION == "5898299487c5b9cdbe7d61809fd20213" || USERPERMISSION == "335a66c239a137964a33e8c60b24e3d9" || USERPERMISSION == "0162bce636a63c3ae499224203e06ed0")) {
					?>
						<button type="button" class="btn btn-primary" name="btn_submit" id="btn_submit" onclick="check_error(2);" data-bs-toggle="modal" data-bs-target="#submit" <?php
							if ($permission == "readonly") {
								echo "disabled";
							} elseif ($srcdata = 'project') {
								$tblname = "trx_approval";
								$condition = "project_id=" . $dsb['project_id'];
								$order = "approve_id DESC";
								$status = $DTSB->get_data($tblname, $condition, $order);
								if ($status[0]['approve_note'] != 'The data has been completed.') {
									echo 'disabled';
								}
							}
							?>>Submit</button>
						<?php
					}
					if ((isset($dsb['status']) && $dsb['status'] == 'submited') && (USERPERMISSION == "0162bce636a63c3ae499224203e06ed0" || USERPERMISSION == "dbf36ff3e3827639223983ee8ac47b42" || USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b") && ($_SESSION['Microservices_UserEmail'] != $dsb['create_by'] || USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION == "0162bce636a63c3ae499224203e06ed0")) {
						if (USERPERMISSION_V2 == "bf7717bbfd879cd1a40b71171f9b393e") {
						?>
							<button type="button" class="btn btn-primary" name="btn_approved" id="btn_approved" data-bs-toggle="modal" data-bs-target="#approval">Approve</button>
							<button type="button" class="btn btn-primary" name="btn_rejected" id="btn_rejected" data-bs-toggle="modal" data-bs-target="#rejected">Reject</button>
						<?php
						}
					}
					if ((isset($dsb['status']) && ($dsb['status'] == 'approved' || $dsb['status'] == 'acknowledge')) &&
						(
							USERPERMISSION == "dbf36ff3e3827639223983ee8ac47b42" || // Super Admin
							USERPERMISSION == "726ea0dd998698e8a87f8e344d373533" || // User Admin
							USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" || // Administrator
							USERPERMISSION == "0162bce636a63c3ae499224203e06ed0" || // Approval
							USERPERMISSION == "125b55092905c1919f7558d68cfd62d7" || // PMO Implementation
							USERPERMISSION == "975031eb0e919d08ec6ba1993b455793"    // PMO Maintenance
						)
					) {
						?>
						<button type="button" class="btn btn-primary" name="btn_reopen" id="btn_reopen" data-bs-toggle="modal" data-bs-target="#reopen">Re-open</button>
					<?php
					}
					if ((isset($dsb['status']) && $dsb['status'] == 'approved') && (USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION == "dbf36ff3e3827639223983ee8ac47b42"  || USERPERMISSION == "726ea0dd998698e8a87f8e344d373533" || USERPERMISSION == "125b55092905c1919f7558d68cfd62d7" || USERPERMISSION == "975031eb0e919d08ec6ba1993b455793")) {
					?>
						<button type="button" class="btn btn-primary" name="btn_acknowledge" id="btn_acknowledge" data-bs-toggle="modal" data-bs-target="#acknowledge" onclick="check_acknowledge(1);">Acknowledge</button>
					<?php
					}
					if ((isset($dsb['status']) && $dsb['status'] == 'acknowledge' && strpos($dsb['bundling'], "1;2;") !== false && empty($dsb['pmo_ack_maintenance'])) && ($_SESSION['Microservices_UserEmail'] == 'aceng.zakariya@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'anggi.fachrizal@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'david.kusuma@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'miko.widiarta@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'chrisheryanda@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'syamsul@mastersystem.co.id')) {
					?>
						<button type="button" class="btn btn-primary" name="btn_acknowledge" id="btn_acknowledge" data-bs-toggle="modal" data-bs-target="#acknowledge" onclick="check_acknowledge(1);">Acknowledge</button>
					<?php
					}
					if ((isset($dsb['status']) && $dsb['status'] == 'acknowledge' && strpos($dsb['bundling'], "1;2;") !== false && empty($dsb['pmo_ack_implementation'])) && ($_SESSION['Microservices_UserEmail'] == 'fortuna@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'lucky.andiani@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'ary.mulyati@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'pitasari.amanda@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'sumarno@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'ricky.pebriandi@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'anjas.permana@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'fernando.wangsa@mastersystem.co.id')) {
					?>
						<button type="button" class="btn btn-primary" name="btn_acknowledge" id="btn_acknowledge" data-bs-toggle="modal" data-bs-target="#acknowledge" onclick="check_acknowledge(1);">Acknowledge</button>
					<?php
					}
					?>
					<input type="submit" class="btn btn-secondary" name="btn-cancel" id="btn-cancel" value="Cancel">
				</div>
			<?php } ?>

			<!-- Modal Save -->
			<div class="modal fade" id="save" tabindex="-1" aria-labelledby="saveLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="saveLabel"><b>Notes to Save</b></h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<div class="row mb-3">
								<div class="col-sm-12">
									<textarea class="form-control" id="note_save" name="note_save" rows="3" placeholder="Berikan note..." hidden></textarea>
									<label class="col-sm-12 col-form-label col-form-label-sm" name="save_pesan_error" id="save_pesan_error"></label>
									<label class="col-sm-12 col-form-label col-form-label-sm" name="save_pesan_confirm" id="save_pesan_confirm"></label>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
								<input type="submit" class="btn btn-primary" name="save_service_budget" id="save_service_budget" value="Save">
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Modal Submit -->
			<div class="modal fade" id="submit" tabindex="-1" aria-labelledby="submitLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="submitLabel"><b>Notes to Submit</b></h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<div class="row mb-3">
								<div class="col-sm-12">
									<textarea class="form-control" id="note_submited" name="note_submited" rows="3" placeholder="Berikan note..."></textarea>
									<label class="col-sm-12 col-form-label col-form-label-sm text-danger" name="submit_pesan_error" id="submit_pesan_error"></label>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<input type="submit" class="btn btn-primary" name="submit_service_budget" id="submit_service_budget" value="Submit">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
						</div>
					</div>
				</div>
			</div>

			<!-- Modal Approval -->
			<div class="modal fade" id="approval" tabindex="-1" aria-labelledby="approvalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="approvalLabel"><b>Notes to Approve</b></h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<div class="row mb-3">
								<div class="col-sm-12">
									<textarea class="form-control" id="note_approved" name="note_approved" rows="3" placeholder="Berikan note..."></textarea>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
								<input type="submit" class="btn btn-primary" name="approval_service_budget" id="approval_service_budget" value="Approve">
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Modal Reject -->
			<div class="modal fade" id="rejected" tabindex="-1" aria-labelledby="rejectedLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="rejectedLabel"><b>Notes to Reject</b></h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<div class="row mb-3">
								<div class="col-sm-12">
									<textarea class="form-control" id="note_rejected" name="note_rejected" rows="3" placeholder="Berikan note..."></textarea>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
								<input type="submit" class="btn btn-primary" name="reject_service_budget" id="reject_service_budget" value="Reject">
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Modal Re-Open -->
			<div class="modal fade" id="reopen" tabindex="-1" aria-labelledby="reopenLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="reopenLabel"><b>Notes to Re-Open</b></h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<div class="row mb-3">
								<div class="col-sm-12">
									<textarea class="form-control" id="note_reopen" name="note_reopen" rows="3" placeholder="Berikan note..."></textarea>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
								<input type="submit" class="btn btn-primary" name="reopen_service_budget" id="reopen_service_budget" value="Re-Open">
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Modal Acknowledge -->
			<?php $SbfId = $ver > 0 ? $dsb['project_id'] : 0; ?>
			<div class="modal fade" id="acknowledge" tabindex="-1" aria-labelledby="saveFinal" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="saveAcknowledge"><b>Notes to Acknowledge</b></h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<div id="ack_failed"></div>
							<div id="ack_pass">
								<div class="row mb-3">
									<div class="col-sm-12">
										<textarea class="form-control" id="note_acknowledge" name="note_acknowledge" rows="3" placeholder="Berikan note..."></textarea>
									</div>
								</div>
								<?php if ($_SESSION['Microservices_UserEmail'] == 'fortuna@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'lucky.andiani@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'ary.mulyati@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'pitasari.amanda@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'sumarno@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'ricky.pebriandi@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'anjas.permana@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'fernando.wangsa@mastersystem.co.id') { ?>
									<div class="row mb-3">
										<div class="col-sm-5">
											<label for="staticEmail" class="col-sm-6 col-form-label">Project Leader</label>
										</div>
										<div class="col-sm-7">
											<div class="row d-flex justify-content-center mt-100">
												<select class="form-select form-select-sm" id="pm_wrike" name="pm_wrike">
													<?php
													$DBWR = get_conn("WRIKE_INTEGRATE");
													$get_employeeName = $DBWR->get_sqlV2("SELECT * FROM sa_contact_user WHERE name is not null AND name<>'' AND email<>'aldino.delama01@mastersystem.co.id' ORDER BY name ASC");
													while ($row = $get_employeeName[1]->fetch_assoc()) { ?>
														<option value="<?php echo $row['email']; ?>"><?php echo $row['name']; ?>
														</option>
													<?php } ?>
												</select>
											</div>
										</div>
									</div>
									<div class="row mb-3">
										<div class="col-sm-5">
											<label for="staticEmail" class="col-sm-6 col-form-label">SBF Role</label>
										</div>
										<div class="col-sm-7">
											<div class="row d-flex justify-content-center mt-100">
												<select class="form-select form-select-sm" id="sbf_role" name="sbf_role">
													<?php
													$get_role = $DTSB->get_sqlV2(
														"SELECT
															CASE
																WHEN resource_level = 11 THEN 'Project Director'
																WHEN resource_level = 21 THEN 'Project Manager'
																WHEN resource_level = 31 THEN 'Project Coordinator'
															END AS position
														FROM
															sa_trx_project_mandays
														WHERE
															service_type = 1
															AND project_id = $SbfId
															AND resource_level IN (11,21,31)
														");
													while ($row = $get_role[1]->fetch_assoc()) { ?>
														<option value="<?php echo $row['position']; ?>">
															<?php echo $row['position']; ?>
														</option>
													<?php } ?>
												</select>
											</div>
										</div>
									</div>
								<?php } ?>
								<?php if ($_SESSION['Microservices_UserEmail'] == 'aceng.zakariya@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'anggi.fachrizal@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'david.kusuma@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'miko.widiarta@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'chrisheryanda@mastersystem.co.id' || $_SESSION['Microservices_UserEmail'] == 'syamsul@mastersystem.co.id') { ?>
									<div class="row mb-3">
										<div class="col-sm-5">
											<label for="staticEmail" class="col-sm-6 col-form-label">Owner Project</label>
										</div>
										<div class="col-sm-7">
											<div class="row d-flex justify-content-center mt-100">
												<select class="form-select form-select-sm" id="pm_wrike" name="pm_wrike">
													<?php
													$DBWR = get_conn("WRIKE_INTEGRATE");
													$get_employeeName = $DBWR->get_sqlV2("SELECT * FROM sa_contact_user WHERE name is not null AND name<>'' ORDER BY name ASC");
													while ($row = $get_employeeName[1]->fetch_assoc()) { ?>
														<option value="<?php echo $row['email']; ?>"><?php echo $row['name']; ?>
														</option>
													<?php } ?>
												</select>
											</div>
										</div>
									</div>
									<div class="row mb-3">
										<div class="col-sm-5">
											<label for="staticEmail" class="col-sm-6 col-form-label">Project Role</label>
										</div>
										<div class="col-sm-7">
											<div class="row d-flex justify-content-center mt-100">
												<select class="form-select form-select-sm" id="sbf_role" name="sbf_role">
													<option value="PIC Maintenance">PM Maintenance</option>
												</select>
											</div>
										</div>
									</div>
								<?php } ?>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
								<input type="submit" class="btn btn-primary" name="acknowledge_service_budget" id="acknowledge_service_budget" value="Acknowledge">
							</div>
						</div>
					</div>
				</div>
			</div>


		</form>
	</div>
	<?php
	// module update
	$title = "Service Budget Form";
	$author = 'Syamsul Arham';
	$type = "sub-module"; // module (&mod), submodule (&sub)
	$revisionType = 'control'; // major, minor, control
	$dashboardEnable = false;
	$moduleDescription = "This module is used to create or view a Service Budget that has been created.";
	$revision_msg = "Lock permission select component";
	$showFooter = true;

	global $ClassVersion;
	$ClassVersion->show_footer(__FILE__, $title, $moduleDescription, $type, $revisionType, $dashboardEnable, $author, $revision_msg, $showFooter);
	?>
</div>


<form method="get">
	<div class="modal fade" id="modal_project_name_internal" tabindex="-1" aria-labelledby="saveFinal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="saveAcknowledge"><b>Edit Project Information</b></h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="card shadow">
						<div class="card-body">
							<div class="row mb-1">
								<label for="staticEmail" class="col-sm-12 col-form-label">Project Name</label>
								<div class="col-sm-12">
									<textarea class="form-control" id="note_project_name" name="note_project_name" rows="3" placeholder="Berikan Project Name..." onkeyup="getlen();"><?php echo $ver > 0 ? $dsb['project_name'] : ""; ?></textarea>
								</div>
							</div>
							<div class="row mb-1">
								<label for="staticEmail" class="col-sm-12 col-form-label">Project Name Internal (<span id="demo"></span> of 100)</label>
								<div class="col-sm-12">
									<textarea class="form-control" id="note_project_name_internal" name="note_project_name_internal" rows="3" placeholder="Berikan Project Name Internal..." onkeyup="getlen();" <?php echo $dsb['status'] == 'acknowledge' ? "readonly" : ""; ?>><?php echo $ver > 0 ? $dsb['project_name_internal'] : ""; ?></textarea>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="mod" value="service_budget">
					<input type="hidden" name="act" value="view">
					<input type="hidden" name="project_code" value="<?php echo $dsb['project_code']; ?>">
					<input type="hidden" name="so_number" value="<?php echo $dsb['so_number']; ?>">
					<input type="hidden" name="order_number" value="<?php echo $dsb['order_number']; ?>">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
					<input type="submit" class="btn btn-primary" name="save_project_name_internal" id="save_project_name_internal" value="Save">
				</div>
			</div>
		</div>
	</div>
</form>

<div class="modal fade" id="fileupload" tabindex="-1" aria-labelledby="fileuploadLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="fileuploadLabel"><b>Upload File</b></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row mb-3">
					<link href="components/modules/upload/upload.css" rel="stylesheet" type="text/css" />
					<form id="upload_form" enctype="multipart/form-data" method="post" action="components/modules/upload/upload.php">
						<div>
							<div><label for="image_file">Please select image file</label></div>
							<div><input type="file" name="image_file" id="image_file" onchange="fileSelected();" /></div>
						</div>
						<div>
							<input type="button" value="BOM Upload" onclick="startUploadingBOQ()" />
							<!-- <input type="button" value="Upload Price-List" onclick="startUploading()" /> -->
							<input type="button" value="Other Upload" onclick="startUploadingOther()" />
						</div>
						<div class="text-danger" style="font-size:12px">
						The BOQ Upload button is only used if there is an Investment Backup Unit, otherwise use the Other Upload button.
						</div>
						<div id="fileinfo">
							<div id="filename"></div>
							<div id="filesize"></div>
							<div id="filetype"></div>
							<div id="filedim"></div>
						</div>
						<div id="error">You should select valid image files only!</div>
						<div id="error2">An error occurred while uploading the file</div>
						<div id="abort">The upload has been canceled by the user or the browser dropped the connection</div>
						<div id="warnsize">Your file is very big. We can't accept it. Please select more small file</div>
						<div id="progress_info">
							<div id="progress"></div>
							<div id="progress_percent">&nbsp;</div>
							<div class="clear_both"></div>
							<div>
								<div id="speed">&nbsp;</div>
								<div id="remaining">&nbsp;</div>
								<div id="b_transfered">&nbsp;</div>
								<div class="clear_both"></div>
							</div>
							<div id="upload_response"></div>
						</div>
					</form>
					<img id="preview" />
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="msgModal" tabindex="-1" aria-labelledby="msgModalLabel" aria-hidden="true" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="msgModalLabel">Modal title</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				...
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
</div>

<script>
	getlen(0);
	get_renewal(0);
	sub_project(0);
	sbtypex(0);
	band_change_v2(0);
	s_change(0);
	m_change_price(0);
	w_change_price(0);
	mandays_change(0);
	i_change_price(0);
	agreed_show(0);
	check_error(0);
</script>