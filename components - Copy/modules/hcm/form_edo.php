<script language=JavaScript>
function reload(form)
{
var val=form.employee_name.options[form.employee_name.options.selectedIndex].value;
self.location='index.php?mod=hcm&sub=edo&act=add&employee_name=' + val ;
}
</script>

<?php
@$get_employee_name = $_GET['employee_name'];
if(isset($_GET['act']) && ($_GET['act']=='edit' || $_GET['act']=="view")) {
	global $DBHCM;
	$condition = "edo_id=" . $_GET['edo_id'];
	$data = $DBHCM->get_data($tblname, $condition);
}
$readonly = "";
if(isset($_GET['act']) && $_GET['act']=='view') {
	$readonly = "readonly";
	$disabled = "disabled='ture'";
}
?>
<form method="post" action="index.php?mod=<?php echo $_GET['mod'] . '&sub=edo'; ?>">
	<div class="row">
		<div class="col-lg-6">
			<input type="hidden" id="edo_id" name="edo_id" value="<?php if($_GET['act']=='edit' || $_GET['act']=='view') { echo $data[0]['edo_id']; } ?>" readonly>
			<div class="row mb-3">
				<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Employee Name</label>
				<div class="col-sm-9">
					<?php
					$mdlname = "HCM";
					$DBHCM = get_conn($mdlname);

					if(isset($_GET['edo_id']))
					{
						$tblname = "trx_edo_request";
						$condition = "`edo_id`=" . $_GET['edo_id'];
						$edos = $DBHCM->get_data($tblname, $condition);
						$get_employee_name = $edos[0]['employee_name'];
					}

					$mysql = "SELECT `employee_name`, `employee_email` FROM `sa_view_employees_v2` WHERE (`job_structure` LIKE 'JG%' OR `job_structure` LIKE 'RBC%' OR `job_structure` LIKE 'LWW%') AND `resign_date` IS NULL AND `job_level`>2 GROUP BY `employee_name` ORDER BY `employee_name` ASC";
					$employeeList = $DBHCM->get_sql($mysql);
					?>
					<input type="hidden" name="employee_name1" value="<?php echo $data[0]['employee_name']; ?>">
					<select class="form-select form-select-sm" id="employee_name" name="employee_name" onchange="reload(this.form)" <?php echo (!isset($data) || $data[0]['status']=='drafted' || $data[0]['status']=='edo rejected') ? "" : "disabled"; ?>>
						<option value="">--- Select ---</option>
						<?php do { ?>
							<option value="<?php echo $employeeList[0]['employee_name'] . "<" . $employeeList[0]['employee_email'] . ">"; ?>" 
								<?php 
								$employee_name = $employeeList[0]['employee_name'] . "<" . $employeeList[0]['employee_email'] . ">";
								echo $employee_name == $get_employee_name ? "selected" : ""; 
								?>><?php echo $employee_name; ?></option>
						<?php } while($employeeList[0]=$employeeList[1]->fetch_assoc()); ?>
					</select>
				</div>
			</div>
			<div class="row mb-3">
				<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Jabatan</label>
				<div class="col-sm-9">
					<?php
					$condition = "CONCAT(`employee_name`, '<', `employee_email`, '>')=\"" . $get_employee_name . "\"";
					$tblname = 'view_employees_v2';
					$xxx = $DBHCM->get_data($tblname, $condition);
					?>
					<input type="text" class="form-control form-control-sm" id="jabatan" name="jabatan" value="<?php echo (isset($_GET['edo_id']) || isset($_GET['employee_name'])) ? $xxx[0]['job_title'] : ""; ?>" readonly>
				</div>
			</div>
			<div class="row mb-3">
				<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Department</label>
				<div class="col-sm-9">
					<input type="text" class="form-control form-control-sm" id="division" name="division" value="<?php echo (isset($_GET['edo_id']) || isset($_GET['employee_name'])) ?$xxx[0]['department_name'] : ""; ?>" readonly>
				</div>
			</div>
			<div class="row mb-3">
				<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Leader</label>
				<div class="col-sm-9">
					<input type="text" class="form-control form-control-sm" id="manager" name="manager" value="<?php echo (isset($_GET['edo_id']) || isset($_GET['employee_name'])) ?$xxx[0]['leader_name'] . "<" . $xxx[0]['leader_email'] . ">" : ""; ?>" readonly>
				</div>
			</div>
			<div class="row mb-3">
				<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">General Manager</label>
				<div class="col-sm-9">
					<input type="text" class="form-control form-control-sm" id="leader" name="leader" value="<?php echo (isset($_GET['edo_id']) || isset($_GET['employee_name'])) ?$DBHCM->get_general_manager($xxx[0]['employee_email']) : ""; ?>" readonly>
				</div>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="row mb-3">
				<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Start Date</label>
				<div class="col-sm-3">
					<div class="input-group date">
						<div class="input-group-addon">
							<span class="glyphicon glyphicon-th"></span>
						</div>
						<input type="text" class="form-control form-control-sm" id="date_timepicker_start<?php echo (!isset($data) || $data[0]['status']=='drafted' || $data[0]['status']=='edo rejected') ? "" : "1"; ?>" name="start_date" placeholder="Start Date" value="<?php 
							if(isset($_GET['act']) && ($_GET['act']=='edit' || $_GET['act']=="view")) { echo date("d-M-Y G:i:s", strtotime($data[0]['start_date'])); } ?>" <?php echo (!isset($data) || $data[0]['status']=='drafted' || $data[0]['status']=='edo rejected') ? "" : "readonly"; ?>>
					</div>
				</div>
				<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">End Date</label>
				<div class="col-sm-3">
					<div class="input-group date">
						<div class="input-group-addon">
							<span class="glyphicon glyphicon-th"></span>
						</div>
						<input type="text" class="form-control form-control-sm" id="date_timepicker_end<?php echo (!isset($data) || $data[0]['status']=='drafted' || $data[0]['status']=='edo rejected') ? "" : "1"; ?>" name="end_date" placeholder="End Date" value="<?php if(isset($_GET['act']) && ($_GET['act']=='edit' || $_GET['act']=="view")) { echo date("d-M-Y G:i:s", strtotime($data[0]['end_date'])); } ?>" <?php echo (!isset($data) || $data[0]['status']=='drafted' || $data[0]['status']=='edo rejected') ? "" : "readonly"; ?>>
					</div>
				</div>
			</div>
			<div class="row mb-3">
				<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Actual Start Date</label>
				<div class="col-sm-3">
					<div class="input-group date">
						<div class="input-group-addon">
							<span class="glyphicon glyphicon-th"></span>
						</div>
						<input type="text" class="form-control form-control-sm" id="date_picker_start<?php echo ($data[0]['status']=='request approved' || $data[0]['status']=='leave rejected') ? "" : "2"; ?>" name="actual_start" placeholder="Actual Start Date" value="<?php if(isset($_GET['act']) && ($_GET['act']=='edit' || $_GET['act']=="view") && isset($data[0]['actual_start'])) { echo date("d-M-Y", strtotime($data[0]['actual_start'])); } 
							?>" <?php echo (isset($data) && ($data[0]['status']=='request approved' || $data[0]['status']=='leave rejected')) ? "" : "readonly"; ?>>
					</div>
				</div>
				<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Actual End Date</label>
				<div class="col-sm-3">
					<div class="input-group date">
						<div class="input-group-addon">
							<span class="glyphicon glyphicon-th"></span>
						</div>
						<input type="text" class="form-control form-control-sm" id="date_picker_end<?php echo ($data[0]['status']=='request approved' || $data[0]['status']=='leave rejected') ? "" : "2"; ?>" name="actual_end" placeholder="Actual End Date" value="<?php if(isset($_GET['act']) && ($_GET['act']=='edit' || $_GET['act']=="view") && isset($data[0]['actual_end'])) { echo date("d-M-Y", strtotime($data[0]['actual_end'])); } ?>" <?php echo (isset($data) && ($data[0]['status']=='request approved' || $data[0]['status']=='leave rejected')) ? "" : "readonly"; ?>>
					</div>
				</div>
			</div>
			<div class="row mb-3">
				<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Duration</label>
				<div class="col-sm-2">
					<input type="text" class="form-control form-control-sm" id="duration" name="duration" value="<?php if ($_GET['act']=='edit' || $_GET['act']=="view") { echo $data[0]['duration']; } ?>" readonly>
					<!-- <input type="text" class="form-control form-control-sm text-end" id="duration" name="duration" value="<?php //if ($_GET['act']=='edit' || $_GET['act']=="view") { echo number_format((strtotime($data[0]['end_date']) - strtotime($data[0]['start_date']))/3600,2,",","."); } ?>" readonly> -->
				</div>
				<label for="inputCID3" class="col-sm-1 col-form-label col-form-label-sm">hours</label>
				<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status</label>
				<div class="col-sm-3">
					<input type="text" class="form-control form-control-sm" id="status" name="status" value="<?php if ($_GET['act']=='edit' || $_GET['act']=="view") { echo $data[0]['status']; } else { echo 'drafted'; } ?>" readonly>
				</div>
			</div>
			<div class="row mb-3">
				<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Reason</label>
				<div class="col-sm-9">
					<textarea class="form-control form-control-sm" id="reason" name="reason" rows="1" <?php echo (!isset($data) || $data[0]['status']=='drafted' || $data[0]['status']=='edo rejected') ? "" : "readonly"; ?>><?php if ($_GET['act']=='edit' || $_GET['act']=="view") { echo $data[0]['reason']; } ?></textarea>
					<!-- <textarea class="form-control form-control-sm" id="reason" name="reason" rows="1" ><?php if ($_GET['act']=='edit' || $_GET['act']=="view") { echo $data[0]['reason']; } ?></textarea> -->
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-sm-9">
					<input type="hidden" name="entry_by" value="<?php if ($_GET['act']=='edit' || $_GET['act']=="view") { echo $data[0]['entry_by']; } ?>">
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<?php if(isset($data) && ($data[0]['status']=='drafted' || $data[0]['status']=='edo rejected') && ($mdl_permission['mdllevel']=="Administrator" || $mdl_permission['mdllevel']=='Entry Data')) { ?>
				<input type="submit" class="btn btn-primary" name="save" value="Save">
			<?php } ?>
			<?php if(!isset($data) && ($mdl_permission['mdllevel']=="Administrator" || $mdl_permission['mdllevel']=='Entry Data')) { ?>
				<input type="submit" class="btn btn-primary" name="add" value="Save">
			<?php } ?>
			<?php if(isset($data) && $data[0]['status']=='drafted' && ($mdl_permission['mdllevel']=="Administrator" || $mdl_permission['mdllevel']=='Entry Data')) { ?>
				<input type="submit" class="btn btn-primary" name="request_submitted" value="Submit">
			<?php } ?>
			<?php if(isset($data) && $data[0]['status']=='edo submitted' && ($mdl_permission['mdllevel']=="Administrator" || $mdl_permission['mdllevel']=='Approval')) { ?>
				<input type="submit" class="btn btn-primary" name="request_approved" value="Approve">
				<input type="submit" class="btn btn-primary" name="request_rejected" value="Reject">
			<?php } ?>
			<!-- <?php //if(isset($data) && ($data[0]['status']=='request approved' || $data[0]['status']=='leave rejected') && ($mdl_permission['mdllevel']=="Administrator" || $mdl_permission['mdllevel']=='Approval')) { ?> -->
			<?php if(isset($data) && ($data[0]['status']=='request approved' || $data[0]['status']=='leave rejected') && ($mdl_permission['mdllevel']=="Administrator" || $mdl_permission['mdllevel']=='Approval' || $mdl_permission['mdllevel']=='Member')) { ?>
				<input type="submit" class="btn btn-primary" name="leave_submitted" value="Submit">
			<?php } ?>
			<?php if(isset($data) && $data[0]['status']=='leave submitted' && ($mdl_permission['mdllevel']=="Administrator" || $mdl_permission['mdllevel']=='Approval' || $mdl_permission['mdllevel']=='Approval')) { ?>
				<?php if($data[0]['start_date'] >= date("Y-m-d G:i:s", strtotime("-6 month"))) { ?>
					<input type="submit" class="btn btn-primary" name="leave_approved" value="Approve">
					<input type="submit" class="btn btn-primary" name="leave_rejected" value="Reject">
				<?php } else { ?>
					<input type="submit" class="btn btn-primary" name="leave_expired" value="Expired">
				<?php } ?>
			<?php } ?>
			<input type="submit" class="btn btn-secondary" name="cancel" value="Cancel">
		</div>
	</div>
</form>
										
<script>
jQuery(function(){
	jQuery('#date_timepicker_start').datetimepicker({
		onShow:function( ct ){
			this.setOptions({
				// maxDate:jQuery('#date_timepicker_end').val()?jQuery('#date_timepicker_end').val():false
				maxDate: '-1970/01/01'
			})
		}
	});
	jQuery('#date_timepicker_end').datetimepicker({
		onShow:function( ct ){
			this.setOptions({
				minDate:jQuery('#date_timepicker_start').val()?jQuery('#date_timepicker_start').val():false,
				maxDate: '-1970/01/01'
			})
		}
	});
	jQuery('#date_picker_start').datetimepicker({
		format:'d-M-Y',
		timepicker:false,
		minDate: '+1970/01/01',
		onShow:function( ct ){
			this.setOptions({
				maxDate:jQuery('#date_picker_end').val()?jQuery('#date_picker_end').val():false
			})
		}
	});
	jQuery('#date_picker_end').datetimepicker({
		format:'d-M-Y',
		timepicker:false,
		minDate: '+1970/01/01',
		onShow:function( ct ){
			this.setOptions({
				minDate:jQuery('#date_picker_start').val()?jQuery('#date_picker_start').val():false
			})
		}
	});
});
</script>
										