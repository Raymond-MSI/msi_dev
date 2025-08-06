<link href="components/vendor/jselect2-4.1.0/select2.min.css" rel="stylesheet" />
<script src="components/vendor/jselect2-4.1.0/select2.min.js"></script>
<script>
	$(function() {
		$("#employee_name").select2();
		$("#project_code").select2();
	});
</script>
<script src="components/modules/hcm/java_hcm.js"></script>

<?php
if (isset($_SERVER['HTTP_REFERER'])) {
	$x1 = $_SERVER['HTTP_REFERER'];
	$x2 = explode("?", $x1);
	$referer = $x2[1];
}

$DBHCM = get_conn("HCM");
$MyFullName = $_SESSION['Microservices_UserName'] . "<" . $_SESSION['Microservices_UserEmail'] . ">";
$MyName = $_SESSION['Microservices_UserName'];
$MyEmail = $_SESSION['Microservices_UserEmail'];
$narikjabatan = $DBHCM->get_sql("SELECT * FROM sa_view_employees WHERE employee_email = 'malik.aulia@mastersystem.co.id' and resign_date is null");

//reset notif
$link = "index.php?" . $_SERVER['QUERY_STRING'];
reset_notif($_SESSION['Microservices_UserEmail'], $link);

// @$$MyName = $_GET['employee_name'];
if (isset($_GET['act']) && ($_GET['act'] == 'edit' || $_GET['act'] == "view")) {
	global $DBHCM;
	$condition = "edo_id=" . $_GET['edo_id'];
	$data = $DBHCM->get_data($tblname, $condition);
	$newData = 0;
} else {
	$newData = 1;
}

$readonly = "";
if (isset($_GET['act']) && $_GET['act'] == 'view') {
	$readonly = "readonly";
	$disabled = "disabled='ture'";
}
?>

<ul class="nav nav-tabs" id="myTab" role="tablist">
	<li class="nav-item" role="presentation">
		<button class="nav-link active text-body" id="info-tab" data-bs-toggle="tab" data-bs-target="#EDO" type="button" role="tab" aria-controls="edo" aria-selected="true">EDO</button>
	</li>
	<li class="nav-item" role="presentation">
		<button class="nav-link" id="edo-tab" data-bs-toggle="tab" data-bs-target="#History" type="button" role="tab" aria-controls="history" aria-selected="false">History</button>
	</li>
</ul>
<div class="tab-content" id="myTabContent">
	<!-- TAB Project Information -->
	<div class="tab-pane fade show active" id="EDO" role="tabpanel" aria-labelledby="edo-tab">
		<div class="card mb-4">
			<!-- Card Body -->
			<div class="card-body">

				<form method="post" action="index.php?<?php echo $referer; ?>">
					<div class="row">
						<div class="col-lg-6">
							<div class="row mb-3">
								<div class="col">
									<div class="card-header">
										Employee Information
									</div>
								</div>
							</div>
							<input type="hidden" id="edo_id" name="edo_id" value="<?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'view') {
																						echo $data[0]['edo_id'];
																					} ?>" readonly>
							<div class="row mb-3">
								<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Employee Name</label>
								<div class="col-sm-9">
									<input type="text" class="form-control form-control-sm" name="employee_name" value="<?php echo $MyName; ?>" readonly>
								</div>
							</div>
							<div class="row mb-3">
								<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Jabatan</label>
								<div class="col-sm-9">
									<?php
									// $jobtitle = $DBHCM->get_job_title($MyEmail);
									?>
									<input type="text" class="form-control form-control-sm" id="jabatan" name="jabatan" value="<?php echo $narikjabatan[0]['job_title']; ?>" readonly>
								</div>
							</div>
							<div class="row mb-3">
								<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Department</label>
								<div class="col-sm-9">
									<?php //$department = $DBHCM->get_department($MyEmail); 
									?>
									<input type="text" class="form-control form-control-sm" id="division" name="division" value="<?php echo $narikjabatan[0]['organization_name']; ?>" readonly>
								</div>
							</div>
							<div class="row mb-3">
								<?php
								$mysql = sprintf(
									// "SELECT
									// 	`b`.`employee_email`,
									// 	`d`.`employee_email` AS `leader_email`
									// FROM
									// 	`sa_mst_organization_project` `a`
									// LEFT JOIN `sa_mst_organization_project_employee` `b` ON
									// 	`a`.`structure_id` = `b`.`structure_id`
									// LEFT JOIN `sa_mst_organization_project` `c` ON
									// 	`a`.`parent` = `c`.`structure_id`
									// LEFT JOIN `sa_mst_organization_project_employee` `d` ON
									// 	`c`.`structure_id` = `d`.`structure_id`
									// WHERE `b`.`employee_email` = %s
									// ORDER BY
									// 	`b`.`employee_email` ASC;",
									GetSQLValueString($MyEmail, "text")
								);
								// $rsStructures = $DBHCM->get_sql($mysql);
								if ($rsStructures[2] > 0 && $rsStructures[0]['leader_email'] != NULL) {
									$emailEmp = $rsStructures[0]['leader_email'];
								?>
									<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Direct Leader Project <span class="text-danger">*</span></label>
									<div class="col-sm-9">
										<select class="form-select form-select-sm" id="manager" name="manager">
											<?php
											// do {
											// 	$xxx = $DBHCM->get_email($rsStructures[0]['leader_email']);
											?>
											<option value="<?php //echo $xxx; 
															?>" <?php ///echo (isset($data) && $xxx == $data[0]['manager']) ? "selected" : ""; 
																?>><?php //echo $DBHCM->get_name($rsStructures[0]['leader_email']); 
																	?></option>
											<?php
											// 	if (isset($data) && $xxx == $data[0]['manager']) {
											// 		$DirectLeader = $data[0]['manager'];
											// 	} else {
											// 		$DirectLeader = $emailEmp;
											// 	}
											// } while ($rsStructures[0] = $rsStructures[1]->fetch_assoc());
											?>
										</select>
									</div>
								<?php
								} else {
									$DirectLeader = $DBHCM->get_leader($MyEmail);
								?>
									<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Direct Leader</label>
									<div class="col-sm-9">
										<input type="text" class="form-control form-control-sm" id="manager" name="manager" value="<?php echo $DirectLeader; ?>" readonly>
									</div>
								<?php
								}
								?>
							</div>
							<div class="row mb-3">
								<?php
								$emailx = $DBHCM->split_email($DirectLeader);
								$mysql = sprintf(
									"SELECT
										`b`.`employee_email`,
										`d`.`employee_email` AS `leader_email`
									FROM
										`sa_mst_organization_project` `a`
									LEFT JOIN `sa_mst_organization_project_employee` `b` ON
										`a`.`structure_id` = `b`.`structure_id`
									LEFT JOIN `sa_mst_organization_project` `c` ON
										`a`.`parent` = `c`.`structure_id`
									LEFT JOIN `sa_mst_organization_project_employee` `d` ON
										`c`.`structure_id` = `d`.`structure_id`
									WHERE `b`.`employee_email` = %s
									ORDER BY
										`b`.`employee_email` ASC;",
									GetSQLValueString($emailx[1], "text")
								);
								$rsStructures = $DBHCM->get_sql($mysql);
								if ($rsStructures[2] > 0 && $rsStructures[0]['leader_email'] != NULL) {
								?>
									<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Indirect Leader Project <span class="text-danger">*</span></label>
									<div class="col-sm-9">
										<select class="form-select form-select-sm" id="leader" name="leader">
											<?php
											do {
												$xxx = $DBHCM->get_email($rsStructures[0]['leader_email']);
											?>
												<option value="<?php echo $xxx; ?>" <?php echo (isset($data) && $xxx == $data[0]['leader']) ? "selected" : ""; ?>><?php echo $DBHCM->get_name($rsStructures[0]['leader_email']); ?></option>
											<?php
											} while ($rsStructures[0] = $rsStructures[1]->fetch_assoc());
											?>
										</select>
									</div>
								<?php
								} else {
									$xxx = $DBHCM->split_email($DirectLeader);
									$IndirectLeader = $DBHCM->get_leader($xxx[1]);
									if ($DirectLeader != "") {
										$xxx = $DBHCM->split_email($IndirectLeader);
										$joblevel = $DBHCM->get_job_level($xxx[1]);
										if ($joblevel == 1) {
											$IndirectLeader = $DirectLeader;
										}
									}
								?>
									<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Indirect Leader</label>
									<div class="col-sm-9">
										<input type="text" class="form-control form-control-sm" id="leader" name="leader" value="<?php echo $IndirectLeader; ?>" readonly>
									</div>
								<?php
								}
								?>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="row mb-3">
								<div class="col">
									<div class="card-header">
										Activity Information
									</div>
								</div>
							</div>
							<div class="row mb-3">
								<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Code <span class="text-danger">*</span></label>
								<div class="col-sm-9">
									<?php
									$DBSBF = get_conn("SERVICE_BUDGET");
									$mysql = "SELECT `project_code`, `project_name` FROM `sa_trx_project_list` WHERE `status` = 'approved' OR `status` = 'acknowledge' GROUP BY `project_code`, `project_name` ORDER BY `project_id` DESC;";
									$rsProjects = $DBSBF->get_sql($mysql);
									?>
									<select class="form-select form-select-sm" id="project_code" name="project_code" <?php echo (!isset($data) || $data[0]['status'] == 'drafted' || $data[0]['status'] == 'edo rejected') ? "" : "disabled"; ?>>
										<option value="">------ Select ------</option>
										<option value="Non-Project">Non-Project</option>
										<?php do { ?>
											<option value="<?php echo $rsProjects[0]['project_code']; ?>" <?php echo (isset($data) && $data[0]['project_code'] == $rsProjects[0]['project_code']) ? "selected" : ""; ?>>
												<?php echo $rsProjects[0]['project_code'] . "-" . $rsProjects[0]['project_name']; ?>
											</option>
										<?php } while ($rsProjects[0] = $rsProjects[1]->fetch_assoc()); ?>
									</select>
								</div>
							</div>
							<div class="row mb-3">
								<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">On-Site / Remote <span class="text-danger">*</span></label>
								<div class="col-sm-9">
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="category" id="category1" value="Onsite" <?php echo ((isset($data) && $data[0]['category'] == "Onsite") ? "checked" : ""); ?> <?php echo (!isset($_GET['act']) || $_GET['act'] == 'edit' || $_GET['act'] == 'add') ? "" : "disabled"; ?>>
										<label class="form-check-label" for="inlineRadio1">Onsite</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="category" id="category2" value="Remote" <?php echo ((isset($data) && $data[0]['category'] == "Remote") ? "checked" : ""); ?> <?php echo (!isset($_GET['act']) || $_GET['act'] == 'edit' || $_GET['act'] == 'add') ? "" : "disabled"; ?>>
										<label class="form-check-label" for="inlineRadio2">Remote</label>
									</div>
								</div>
							</div>
							<div class="row mb-3">
								<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status</label>
								<div class="col-sm-3">
									<input type="text" class="form-control form-control-sm" id="status" name="status" value="<?php
																																if ($_GET['act'] == 'edit' || $_GET['act'] == "view") {
																																	$status = $data[0]['status'];
																																	if ($status == "edo submitted") {
																																		echo "EDO Submitted";
																																	} else
											if ($status == "edo rejected") {
																																		echo "EDO Rejected";
																																	} else
											if ($status == "request approved") {
																																		echo "EDO Approved";
																																	} else
											if ($status == "leave submitted") {
																																		echo "Leave Submitted";
																																	} else
											if ($status == "leave rejected") {
																																		echo "Leave Rejected";
																																	} else
											if ($status == "completed") {
																																		echo "Leave Approved";
																																	} else
											if ($status == "completed with paid") {
																																		echo "Cut-off With Paid";
																																	} else
											if ($status == "cancel by cutoff") {
																																		echo "Cut-off by Cancel";
																																	}
																																} else {
																																	echo 'drafted';
																																} ?>" readonly>
								</div>
								<?php
								if (isset($_GET['act']) && $_GET['act'] == 'edit' && date("Y-m-d", strtotime($data[0]['performed_date'])) < date("Y-m-d", strtotime("-1 week")) && $data[0]['status'] == 'edo submitted') {
								?>
									<div class="col-sm-6" id="msg1x">
										<label class="col text-danger" style="font-size:11px">The approval deadline has passed, please resubmit.</label>
									</div>
								<?php
								}
								?>
							</div>
							<div class="row mb-3">
								<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Reason <span class="text-danger">*</span></label>
								<div class="col-sm-9">
									<textarea class="form-control form-control-sm" id="reason" name="reason" rows="3" <?php echo (!isset($data) || $data[0]['status'] == 'drafted' || $data[0]['status'] == 'edo rejected') ? "" : "readonly"; ?> onchange="EDODuration();"><?php if ($_GET['act'] == 'edit' || $_GET['act'] == "view") {
																																																																				echo $data[0]['reason'];
																																																																			} ?></textarea>
								</div>
							</div>
							<div class="row mb-3">
								<div class="col-sm-9">
									<input type="hidden" name="entry_by" value="<?php if ($_GET['act'] == 'edit' || $_GET['act'] == "view") {
																					echo $data[0]['entry_by'];
																				} ?>">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6">
							<div class="row mb-3">
								<div class="col">
									<div class="card-header">
										Weekend Overtime
									</div>
								</div>
							</div>
							<div class="row mb-3">
								<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Overtime Start <span class="text-danger">*</span></label>
								<div class="col-sm-3">
									<div class="input-group date">
										<div class="input-group-addon">
											<span class="glyphicon glyphicon-th"></span>
										</div>
										<input type="text" class="form-control form-control-sm" id="date_timepicker_start<?php echo (!isset($data) || $data[0]['status'] == 'drafted' || $data[0]['status'] == 'edo rejected') ? "" : "1"; ?>" name="start_date" placeholder="Tanggal masuk kerja di hari libur." value="<?php
																																																																															if (isset($_GET['act']) && ($_GET['act'] == 'edit' || $_GET['act'] == "view")) {
																																																																																echo date("d-M-Y G:i:s", strtotime($data[0]['start_date']));
																																																																															} ?>" <?php echo (!isset($data) || $data[0]['status'] == 'drafted' || $data[0]['status'] == 'edo rejected') ? "" : "readonly"; ?> onchange="EDODuration();">
									</div>
								</div>
							</div>
							<div class="row mb-3">
								<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Overtime End <span class="text-danger">*</span></label>
								<div class="col-sm-3">
									<div class="input-group date">
										<div class="input-group-addon">
											<span class="glyphicon glyphicon-th"></span>
										</div>
										<input type="text" class="form-control form-control-sm" id="date_timepicker_end<?php echo (!isset($data) || $data[0]['status'] == 'drafted' || $data[0]['status'] == 'edo rejected') ? "" : "1"; ?>" name="end_date" placeholder="Tanggal masuk kerja di hari libur." value="<?php if (isset($_GET['act']) && ($_GET['act'] == 'edit' || $_GET['act'] == "view")) {
																																																																															echo date("d-M-Y G:i:s", strtotime($data[0]['end_date']));
																																																																														} ?>" <?php echo (!isset($data) || $data[0]['status'] == 'drafted' || $data[0]['status'] == 'edo rejected') ? "" : "readonly"; ?> onchange="EDODuration();">
									</div>
								</div>
								<div class="col-sm-6" id="msg1">
									<label class="col text-danger" style="font-size:11px">The minimum duration is 8 hours and the maximum is 24 hours.</label>
								</div>
							</div>
							<div class="row mb-3">
								<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Duration</label>
								<div class="col-sm-2">
									<input type="text" class="form-control form-control-sm text-right" id="duration" name="duration" value="<?php if ($_GET['act'] == 'edit' || $_GET['act'] == "view") {
																																				echo $data[0]['duration'];
																																			} ?>" readonly>
								</div>
								<label for="inputCID3" class="col-sm-2 col-form-label col-form-label-sm">hours</label>
							</div>
							<div class="row mb-3">
								<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Approval by</label>
								<div class="col-sm-9">
									<div class="input-group date">
										<input type="text" class="form-control form-control-sm" id="OTApproval" name="OTApproval" value="<?php if ($_GET['act'] == 'edit' || $_GET['act'] == "view") {
																																				echo $data[0]['overtime_approval_by'];
																																			} ?>" readonly>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-6">
							<?php
							if (isset($data) && $data[0]['flag_approval'] == 2) {
							?>
								<div class="alert alert-primary" role="alert">
									You can take leave based on this approval. Please fill in the leave application form in the "HRIS Management -> Extra Day Off -> EDO Submitted" menu.
								</div>
							<?php
							} else {
							?>
								<div class="alert alert-primary" role="alert">
									You must get 2 approvals; direct leaders and indirect leaders.
								</div>
							<?php
							}
							?>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<!-- <?php //if(isset($data) && ($mdl_permission['user_level']=="Administrator" || $mdl_permission['user_level']=="Super Admin" || $mdl_permission['user_level']=='Entry' || $mdl_permission['user_level']=='Approval') && ($data[0]['status']=='drafted' || $data[0]['status']=='edo rejected')) { 
									?>
								<input type="submit" class="btn btn-primary" name="save" id="save" value="Submit">
								<input type="submit" class="btn btn-primary" name="delete" value="Delete">
							<?php //} 
							?>
							<?php //if(!isset($data) && ($mdl_permission['user_level']=="Administrator" || $mdl_permission['user_level']=="Super Admin" || $mdl_permission['user_level']=='Entry' || $mdl_permission['user_level']=='Approval')) { 
							?> -->
							<input type="submit" class="btn btn-primary" name="add" id="add" value="Submit">
							<!-- <?php //} 
									?>
							<?php //if(isset($data) && ($mdl_permission['user_level']=="Administrator" || $mdl_permission['user_level']=="Super Admin" || $mdl_permission['user_level']=='Entry' || $mdl_permission['user_level']=='Approval') && $data[0]['status']=='drafted') { 
							?>
								<input type="submit" class="btn btn-primary" id="request_submitted" name="request_submitted" value="Submit">
							<?php //} 
							?> -->
							<input type="submit" class="btn btn-secondary" name="cancel" value="Cancel">
						</div>
					</div>
				</form>

			</div>
		</div>
	</div>
	<!-- TAB EDO -->
	<div class="tab-pane fade show" id="History" role="tabpanel" aria-labelledby="history-tab">
		<div class="card shadow mb-4">
			<!-- Card Body -->
			<div class="card-body">

				<?php
				$maxRows = 25;
				if (isset($_GET['maxRows'])) {
					$maxRows = $_GET['maxRows'];
				}
				$tblname = "logs";
				if ($newData) {
					$condition = "edo_id=0";
				} else {
					$condition = "edo_id = " . $data[0]['edo_id'] . " AND `module`='EDO'";
				}
				$order = "log_id DESC";
				$his2 = $DBHCM->get_data($tblname, $condition, $order, 0, $maxRows);
				if ($his2[2] > 0) {
				?>
					<h5>History</h5>
					<table class="table">
						<thead class="bg-light">
							<th class="col-lg-2">Date</th>
							<th class="col-lg-2">Time</th>
							<th class="col-lg-8">Description</th>
						</thead>
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



			</div>
		</div>
	</div>
</div>

<script>
	EDODuration();
	// request_submit();
	// leave_submit();

	// document.getElementById("request_submitted").disabled = true;
	// reSubmit = true;
	// function mandatory()
	// {
	// 	var xxx = document.getElementById("reason").value;
	// 	if(xxx!="")
	// 	{
	// 		reqSubmit = false;
	// 	}
	// }
</script>