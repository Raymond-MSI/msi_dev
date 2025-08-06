<?php
global $DBCR;
// include 'func_hcm.php';
?>
<?php
if (!isset($_GET['on_review'])) {
	$on_review = 0;
} else {
	$on_review = 1;
}

if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
	$condition = "gi_id=" . $_GET['gi_id'];
	$condition3 = "ip_id=" . $_GET['gi_id'];
	$condition4 = "cr_no=" . "\"" . $_GET['cr_no'] . "\"";
	$condition5 = "project_code=" . "\"" . $_GET['project_code'] . "\"";
	$condition6 = "gi_id=" . "\"" . $_GET['gi_id'] .  "\" AND type_of_service LIKE '%General%'";
	$condition7 = "gi_id=" . "\"" . $_GET['gi_id'] .  "\" AND type_of_service LIKE '%Migration%'";
	$condition8 = "gi_id=" . "\"" . $_GET['gi_id'] .  "\" AND type_of_service LIKE '%Relocation%'";
	$condition9 = "gi_id=" . "\"" . $_GET['gi_id'] .  "\" AND type_of_service LIKE '%Implementasi%'";
	$condition10 = "gi_id=" . "\"" . $_GET['gi_id'] .  "\" AND type_of_service LIKE '%Maintenance%'";
	$condition11 = "gi_id=" . "\"" . $_GET['gi_id'] .  "\" AND type_of_service LIKE '%Others%'";

	$data = $DBCR->get_data($tblname, $condition);
	$data0 = $DBCR->get_data("backup", $condition4);
	$data1 = $DBCR->get_data("detail_of_change", $condition4);
	$data2 = $DBCR->get_data("assesments", $condition4);
	$data3 = $DBCR->get_data("implementation_plans", $condition4);
	$data4 = $DBCR->get_data("detail_plans", $condition4);
	$data5 = $DBCR->get_data("fallback_plans", $condition4);
	$data6 = $DBCR->get_data("assignees", $condition3);
	$data7 = $DBCR->get_data("customer_requirements", $condition3);
	$data8 = $DBCR->get_data("master_pic", $condition4);
	$data9 = $DBCR->get_data("customer_pic", $condition4);
	$data10 = $DBCR->get_data("risk_assesments", $condition4);
	$data11 = $DBCR->get_data("financial_others", $condition4);
	$data12 = $DBCR->get_data("mandays", $condition4);
	$data13 = $DBCR->get_data("prerequisites", $condition4);
	$data14 = $DBCR->get_data("supporting_documents", $condition4);
	$data15 = $DBCR->get_data("affected_ci", $condition4);
	$data16 = $DBCR->get_data("change_cost_plans", $condition4);
	$data17 = $DBCR->get_data("change_request_closing", $condition4);
	$data18 = $DBCR->get_data("approvals", $condition4);
	$data19 = $DBCR->get_data("change_cost_plans", $condition5);
	$data20 = $DBCR->get_data($tblname, $condition6);
	$data21 = $DBCR->get_data($tblname, $condition7);
	$data22 = $DBCR->get_data($tblname, $condition8);
	$data23 = $DBCR->get_data($tblname, $condition9);
	$data24 = $DBCR->get_data($tblname, $condition10);
	$data25 = $DBCR->get_data($tblname, $condition11);

	$ddata19 = $data19[0];
	$qdata19 = $data19[1];
	$tdata19 = $data19[2];

	$ddata18 = $data18[0];
	$qdata18 = $data18[1];
	$tdata18 = $data18[2];

	$ddata = $data[0];
	$qdata = $data[1];
	$tdata = $data[2];

	$ddata2 = $data2[0];
	$qdata2 = $data2[1];
	$tdata2 = $data2[2];

	$ddata3 = $data3[0];
	$qdata3 = $data3[1];
	$tdata3 = $data3[2];

	$ddata16 = $data16[0];
	$qdata16 = $data16[1];
	$tdata16 = $data16[2];

	$ddata17 = $data17[0];
	$qdata17 = $data17[1];
	$tdata17 = $data17[2];

	$ddata8 = $data8[0];
	$qdata8 = $data8[1];
	$tdata8 = $data8[2];

	$ddata0 = $data0[0];
	$qdata0 = $data0[1];
	$tdata0 = $data0[2];
}

$db_name = "HCM";
$DBHCM = get_conn($db_name);

$db_name1 = "MICROSERVICES";
$DBCR2 = get_conn($db_name1);

$db_name2 = "NAVISION";
$DBCR3 = get_conn($db_name2);

$db_name3 = "SERVICE_BUDGET";
$DBCR4 = get_conn($db_name3);

$db_name4 = "google_drive";
$DBGD = get_conn($db_name4);

$picpic = $DBCR4->get_sql("SELECT DISTINCT project_code,so_number,customer_name,project_name FROM sa_trx_project_list");

$jumlahtos_gold = $DBCR->get_sql("SELECT jumlah_ticket, nama_tos FROM sa_type_of_service WHERE nama_tos ='Gold'");
$jumlahtos_silver = $DBCR->get_sql("SELECT jumlah_ticket, nama_tos FROM sa_type_of_service WHERE nama_tos ='Silver'");
$jumlahtos_bronze = $DBCR->get_sql("SELECT jumlah_ticket, nama_tos FROM sa_type_of_service WHERE nama_tos ='Bronze'");

//$tipetos = $DBCR4->get_sql("SELECT tos_id, tos_name FROM sa_mst_type_of_service WHERE tos_id LIKE %1%");

$conditioncost = "project_code=" . "\"" . $_GET['project_code'] . "\" ORDER BY ccp_id DESC";
$sisaticketnya = $DBCR->get_data("change_cost_plans", $conditioncost);

$mdlname = "HCM";
$DTHCM = get_conn($mdlname);
$condition = "employee_email = '" . $_SESSION['Microservices_UserEmail'] . "'";
$tblname = "view_employees";
$dataleader = $DTHCM->get_data($tblname, $condition);

$mdlnameit = "HCM";
$DTHCMIT = get_conn($mdlnameit);
$conditionit = "job_structure like '%JG RRT MC MIS IT%' and  resign_date is null and job_title like '%Manager%'";
$tblnameit = "view_employees";
$dataleaderit = $DTHCMIT->get_data($tblnameit, $conditionit);

if ($_GET['act'] == "add" || $_GET['act'] == "edit" || $_GET['act'] == "review") {
	if ($_GET['type'] == "Implementation" || $_GET['type'] == "Maintenance" || $_GET['type'] == "IT") {
		if ($_GET['act'] == "add" || $_GET['act'] == "edit") {
			if (isset($_GET['project_code'])) {
				$selected_pc = $DBCR4->get_sql("SELECT DISTINCT project_code,so_number,customer_name,project_name,sales_name FROM sa_trx_project_list WHERE project_code='" . $_GET['project_code'] . "'");
				$selected_pc2 = $DBCR4->get_sql("SELECT DISTINCT project_code,so_number,customer_name,project_name,sales_name FROM sa_trx_project_list WHERE project_code='" . $_GET['project_code'] . "'");
				$selectted_tos = $DBCR4->get_sql("SELECT E.project_code, E.status, D.tos_id FROM sa_trx_project_list E JOIN sa_trx_project_implementations D ON E.project_id=D.project_id WHERE E.status LIKE '%approved%' AND D.service_type LIKE '%1%' AND so_number='" . $_GET['so_number'] . "'");
				$selectted_tosmt = $DBCR4->get_sql("SELECT E.project_code, E.status, D.tos_id FROM sa_trx_project_list E JOIN sa_trx_project_implementations D ON E.project_id=D.project_id WHERE E.status LIKE '%approved%' AND so_number='" . $_GET['so_number'] . "' ORDER BY D.project_implementation_id DESC");
				if (isset($selectted_tos[0]['tos_id'])) {
					if ($_GET['type'] == "Implementation") {
						$tblname2 = "mst_type_of_service";
						$conditiontos = "tos_id = '" . $selectted_tos[0]['tos_id'] . "'";
						$conditiongen = "$conditiontos LIKE '%1%'";
						$conditionmig = "$conditiontos LIKE '%3%'";
						$conditionrel = "$conditiontos LIKE '%4%'";
						$tosname = $DBCR4->get_data($tblname2, $conditiontos);
						$tosgeneral = $DBCR4->get_data($tblname2, $conditiongen);
						$tosmigration = $DBCR4->get_data($tblname2, $conditionmig);
						$tosrelocation = $DBCR4->get_data($tblname2, $conditionrel);
					}
					if ($_GET['type'] == "Maintenance") {
						$tblname2 = "mst_type_of_service";
						$conditiontos = "tos_id = '" . $selectted_tosmt[0]['tos_id'] . "'";
						$tosname = $DBCR4->get_data($tblname2, $conditiontos);
					}
				}
			}
			if (empty($_GET['so_number'])) {
				'';
			} else {
				$selected_pc2 = $DBCR4->get_sql("SELECT DISTINCT project_code,so_number,customer_name,project_name,sales_name FROM sa_trx_project_list WHERE so_number='" . $_GET['so_number'] . "'");
				$sales_email = $DBHCM->get_data("view_employees", "employee_name='" . $selected_pc2[0]['sales_name'] . "' AND resign_date IS NULL");
				$email_sales = isset($sales_email[0]['employee_email']);
				if ($email_sales == NULL) {
					$email_sales = $DBHCM->get_data("view_employees", "employee_name='" . $selected_pc2[0]['sales_name'] . "'");
					$get_leader = get_leader($email_sales[0]['employee_email']);
					$check_job = $DBHCM->get_data("view_employees", "employee_name='" . $get_leader[0]['leader_name'] . "'");
					if ($check_job[0]['job_level'] > 2) {
						$get_email = $DBHCM->get_data("view_employees", "employee_name='" . $get_leader[0]['leader_name'] . "'");
						$get_gm = get_leader($get_email[0]['employee_email']);
					} else if ($check_job[0]['job_level'] == 2) {
						$get_gm = get_leader($email_sales[0]['employee_email']);
					}
				} else {
					$email_sales = $sales_email[0]['employee_email'];
					$check_gm = get_leader($email_sales);
					$check_job = $DBHCM->get_data("view_employees", "employee_name='" . $check_gm[0]['leader_name'] . "'");
					if ($check_job[0]['job_level'] > 2) {
						$get_email = $DBHCM->get_data("view_employees", "employee_name='" . $check_gm[0]['leader_name'] . "'");
						$get_gm = get_leader($get_email[0]['employee_email']);
					} else if ($check_job[0]['job_level'] == 2) {
						$get_gm = get_leader($email_sales);
					}
				}
			}
		}
	}
	if ($_GET['type'] == "Sales/Presales") {
		if ($_GET['act'] == "add" || $_GET['act'] == "edit") {
			if (isset($_GET['project_code'])) {
				$selected_pc = $DBCR4->get_sql("SELECT DISTINCT project_code,so_number,customer_name,project_name,sales_name FROM sa_trx_project_list WHERE project_code='" . $_GET['project_code'] . "'");
				$selected_pc2 = $DBCR4->get_sql("SELECT DISTINCT project_code,so_number,customer_name,project_name,sales_name FROM sa_trx_project_list WHERE project_code='" . $_GET['project_code'] . "'");
				$selectted_tos = $DBCR4->get_sql("SELECT E.project_code, E.status, D.tos_id FROM sa_trx_project_list E JOIN sa_trx_project_implementations D ON E.project_id=D.project_id WHERE E.status LIKE '%approved%' AND D.service_type LIKE '%1%' AND project_code='" . $_GET['project_code'] . "'");
				$selectted_tosmt = $DBCR4->get_sql("SELECT E.project_code, E.status, D.tos_id FROM sa_trx_project_list E JOIN sa_trx_project_implementations D ON E.project_id=D.project_id WHERE E.status LIKE '%approved%' AND project_code='" . $_GET['project_code'] . "' ORDER BY D.project_implementation_id DESC");
				// $sales_email = $DBHCM->get_data("view_employees", "employee_name='" . $selected_pc2[0]['sales_name'] . "'");
				// $email_sales = $sales_email[0]['employee_email'];
				// $get_sales_leader = get_leader($email_sales);
				if (isset($selectted_tos[0]['tos_id'])) {
					if ($_GET['type'] == "Implementation") {
						$tblname2 = "mst_type_of_service";
						$conditiontos = "tos_id = '" . $selectted_tos[0]['tos_id'] . "'";
						$conditiongen = "$conditiontos LIKE '%1%'";
						$conditionmig = "$conditiontos LIKE '%3%'";
						$conditionrel = "$conditiontos LIKE '%4%'";
						$tosname = $DBCR4->get_data($tblname2, $conditiontos);
						$tosgeneral = $DBCR4->get_data($tblname2, $conditiongen);
						$tosmigration = $DBCR4->get_data($tblname2, $conditionmig);
						$tosrelocation = $DBCR4->get_data($tblname2, $conditionrel);
					}
					if ($_GET['type'] == "Maintenance") {
						$tblname2 = "mst_type_of_service";
						$conditiontos = "tos_id = '" . $selectted_tosmt[0]['tos_id'] . "'";
						$tosname = $DBCR4->get_data($tblname2, $conditiontos);
					}
				}
			}
		}
	}
	// $check_approval = $DBHCM->get_sql("SELECT * FROM sa_view_employees WHERE job_structure LIKE '%Expert%' AND resign_date IS NULL ORDER BY employee_name");
	$check_approval = $DBHCM->get_sql("SELECT * FROM sa_view_employees WHERE job_structure LIKE '%Expert%' AND resign_date IS NULL OR organization_name LIKE '%Expert%' AND resign_date IS NULL ORDER BY employee_name");
	$pic_apr = $DBHCM->get_sql("SELECT * FROM sa_view_employees WHERE job_structure LIKE '%Expert%' AND resign_date IS NULL OR organization_name LIKE '%Expert%' AND resign_date IS NULL ORDER BY employee_name");
	$approval_presale = $DBHCM->get_data("view_employees", "(job_title LIKE '%General Manager%' AND resign_date IS NULL AND (job_structure LIKE '%lww%' OR job_structure LIKE '%RBC%' OR job_structure LIKE '%JG%')) ORDER BY employee_name");
	// $check_approval2 = $DBHCM->get_data("view_employees", "employee_email='" . $check_approval[0]['approve_by'] . "'");
	$project_manager = $DBHCM->get_data("view_employees", "((organization_name IS NOT NULL AND job_title LIKE '%manager%' AND resign_date IS NULL) OR (job_title LIKE '%staff%' AND organization_name IS NOT NULL AND resign_date IS NULL) OR (job_structure LIKE '%engineer%' AND resign_date IS NULL) OR (organization_name IS NOT NULL AND job_structure LIKE '%expert support%' AND resign_date IS NULL)) ORDER BY employee_name");
	$pic_ra_mt = $DBHCM->get_data("view_employees", "((organization_name IS NOT NULL AND job_title LIKE '%manager%' AND resign_date IS NULL) OR (job_title LIKE '%staff%' AND organization_name IS NOT NULL AND resign_date IS NULL) OR (job_structure LIKE '%engineer%' AND resign_date IS NULL) OR (organization_name IS NOT NULL AND job_structure LIKE '%expert support%' AND resign_date IS NULL)) ORDER BY employee_name");
	$pic_ra_it = $DBHCM->get_data("view_employees", "job_structure LIKE '%JG IR MIS IT%' AND resign_date IS NULL");
	$pic_ta = $DBHCM->get_data("view_employees", "((organization_name IS NOT NULL AND job_title LIKE '%manager%' AND resign_date IS NULL) OR (job_title LIKE '%staff%' AND organization_name IS NOT NULL AND resign_date IS NULL) OR (job_structure LIKE '%engineer%' AND resign_date IS NULL) OR (organization_name IS NOT NULL AND job_structure LIKE '%expert support%' AND resign_date IS NULL)) ORDER BY employee_name");
	$pic_bb = $DBHCM->get_data("view_employees", "((organization_name IS NOT NULL AND job_title LIKE '%manager%' AND resign_date IS NULL) OR (job_title LIKE '%staff%' AND organization_name IS NOT NULL AND resign_date IS NULL) OR (job_structure LIKE '%engineer%' AND resign_date IS NULL) OR (organization_name IS NOT NULL AND job_structure LIKE '%expert support%' AND resign_date IS NULL)) ORDER BY employee_name");
	// $pic_apr = $DBHCM->get_data("view_employees", "((job_structure LIKE '%JG%' AND job_title LIKE '%Manager%' AND resign_date IS NULL)) OR ((job_structure LIKE '%LWW%' AND job_title LIKE '%Manager%' AND resign_date IS NULL)) OR ((job_structure LIKE '%RBC%' AND job_title LIKE '%Manager%' AND resign_date IS NULL)) OR ((job_title LIKE '%Direktur%' AND resign_date IS NULL)) ORDER BY employee_name");
	$pic_ra_imp = $DBHCM->get_data("view_employees", "((organization_name IS NOT NULL AND job_title LIKE '%manager%' AND resign_date IS NULL) OR (job_title LIKE '%staff%' AND organization_name IS NOT NULL AND resign_date IS NULL) OR (job_structure LIKE '%engineer%' AND resign_date IS NULL) OR (organization_name IS NOT NULL AND job_structure LIKE '%expert support%' AND resign_date IS NULL)) ORDER BY employee_name");
	$pic_ra_edit = $DBHCM->get_data("view_employees", "((organization_name IS NOT NULL AND job_title LIKE '%manager%' AND resign_date IS NULL) OR (job_title LIKE '%staff%' AND organization_name IS NOT NULL AND resign_date IS NULL) OR (job_structure LIKE '%engineer%' AND resign_date IS NULL) OR (organization_name IS NOT NULL AND job_structure LIKE '%expert support%' AND resign_date IS NULL)) ORDER BY employee_name");
	$pic_mandays = $DBHCM->get_data("view_employees", "((organization_name IS NOT NULL AND job_title LIKE '%manager%' AND resign_date IS NULL) OR (job_title LIKE '%staff%' AND organization_name IS NOT NULL AND resign_date IS NULL) OR (job_structure LIKE '%engineer%' AND resign_date IS NULL) OR (organization_name IS NOT NULL AND job_structure LIKE '%expert support%' AND resign_date IS NULL)) ORDER BY employee_name");
	$pic_mandays_edit = $DBHCM->get_data("view_employees", "((organization_name IS NOT NULL AND job_title LIKE '%manager%' AND resign_date IS NULL) OR (job_title LIKE '%staff%' AND organization_name IS NOT NULL AND resign_date IS NULL) OR (job_structure LIKE '%engineer%' AND resign_date IS NULL) OR (organization_name IS NOT NULL AND job_structure LIKE '%expert support%' AND resign_date IS NULL)) ORDER BY employee_name");
	$pic_dp = $DBHCM->get_data("view_employees", "((organization_name IS NOT NULL AND job_title LIKE '%manager%' AND resign_date IS NULL) OR (job_title LIKE '%staff%' AND organization_name IS NOT NULL AND resign_date IS NULL) OR (job_structure LIKE '%engineer%' AND resign_date IS NULL) OR (organization_name IS NOT NULL AND job_structure LIKE '%expert support%' AND resign_date IS NULL)) ORDER BY employee_name");
	$pic_dp_edit = $DBHCM->get_data("view_employees", "((organization_name IS NOT NULL AND job_title LIKE '%manager%' AND resign_date IS NULL) OR (job_title LIKE '%staff%' AND organization_name IS NOT NULL AND resign_date IS NULL) OR (job_structure LIKE '%engineer%' AND resign_date IS NULL) OR (organization_name IS NOT NULL AND job_structure LIKE '%expert support%' AND resign_date IS NULL)) ORDER BY employee_name");
	$pic_fp = $DBHCM->get_data("view_employees", "((organization_name IS NOT NULL AND job_title LIKE '%manager%' AND resign_date IS NULL) OR (job_title LIKE '%staff%' AND organization_name IS NOT NULL AND resign_date IS NULL) OR (job_structure LIKE '%engineer%' AND resign_date IS NULL) OR (organization_name IS NOT NULL AND job_structure LIKE '%expert support%' AND resign_date IS NULL)) ORDER BY employee_name");
	$pic_fp_edit = $DBHCM->get_data("view_employees", "((organization_name IS NOT NULL AND job_title LIKE '%manager%' AND resign_date IS NULL) OR (job_title LIKE '%staff%' AND organization_name IS NOT NULL AND resign_date IS NULL) OR (job_structure LIKE '%engineer%' AND resign_date IS NULL) OR (organization_name IS NOT NULL AND job_structure LIKE '%expert support%' AND resign_date IS NULL)) ORDER BY employee_name");
	$user_review = $DBHCM->get_data("view_employees", "employee_email!='" . $_SESSION['Microservices_UserEmail'] . "'");
	$user = $DBHCM->get_data("view_employees");
	$project = $DBCR3->get_data("mst_orders");
	$cr_no = $DBCR->get_data("general_informations", "change_request_type='" . $_GET['type'] . "'");
	$cr = $DBCR->get_data("general_informations", "project_code ='" . $_GET['project_code'] . "'");
	$upload = $DBCR4->get_data("trx_project_list", "project_code ='" . $_GET['project_code'] . "'");
	$dcr = $upload[0];
	$cr_type;
	$cr_count = $cr_no[1]->num_rows + 1;
	if ($_GET['type'] == "Implementation") {
		$cr_type = "CR-IM";
	} else if ($_GET['type'] == "Maintenance") {
		$cr_type = "CR-MT";
	} else if ($_GET['type'] == "IT") {
		$cr_type = "CR-IT";
	} else if ($_GET['type'] == "Sales/Presales") {
		$cr_type = "CR-SP";
	}
}

if ($_GET['act'] == "review" && $on_review == 1) {
	$check_data = $DBCR->get_data("approvals", "cr_no='" . $_GET['cr_no'] . "'" . " AND " . "reviewer_email='" . $_SESSION['Microservices_UserEmail'] . "'");
	$my_sequence = $check_data[0][0]['sequence'];
	if ($my_sequence == 1) {
		$sequence = $my_sequence;
	} else {
		$sequence = $my_sequence - 1;
	}
	$check_condition = "cr_no='" . $_GET['cr_no'] . "'" . " AND " . " sequence=" . $sequence;
	$checker = $DBCR->get_data("approvals", $check_condition);
	if ($check_data[0][0]['reviewer_email'] == $_SESSION['Microservices_UserEmail']) {
		if ($checker[0][0]['review_status'] == null && $my_sequence != 1) {
			echo "<script>
						alert('Cant open this page, must be approved by other reviewer first');
						window.location = window.location.pathname + '?mod=change_request'";
			echo "</script>";
		}
	} else {
		echo "<script>
					alert('Unauthorized review access');
					window.location = window.location.pathname + '?mod=change_request'";
		echo "</script>";
	}
}

function print_selection($input)
{
	global $DBCR;
	if ($input == "pt") {
		$data_db = $DBCR->get_data("project_types");
	} else if ($input == "classf") {
		$data_db = $DBCR->get_data("classifications");
	} else if ($input == "impact") {
		$data_db = $DBCR->get_data("impacts");
	}

	for ($i = 0; $i < count($data_db[0]); $i += 1) {
		if ($input == "pt") {
			$id = $data_db[0][$i]['pt_id'];
		} else if ($input == "classf") {
			$id = $data_db[0][$i]['classification_id'];
		} else if ($input == "impact") {
			$id = $data_db[0][$i]['impact_id'];
		}
		$val = $data_db[0][$i]['name'];
		echo "<option value='$id'>$val</option>";
	}
}
?>
<?php
//reset notif
$link = "index.php?" . $_SERVER['QUERY_STRING'];
reset_notif($_SESSION['Microservices_UserEmail'], $link);
?>


<form method="post" action="index.php?mod=<?php echo $_GET['mod'];
											?>" enctype="multipart/form-data">
	<input type="hidden" id="gi_id" name="gi_id" value="<?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
															echo $_GET['gi_id'];
														}
														?>">
	<input type="hidden" id="change_request_status" name="change_request_status" value="<?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																							echo $ddata['change_request_status'];
																						}
																						?>">
	<input type="hidden" id="sequence" name="sequence" value="<?php echo $_GET['seq'];
																?>">
	<input type="hidden" class="form-control form-control-sm" id="cr_no" name="cr_no" value="<?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																									echo $ddata['cr_no'];
																								}
																								?>">
	<?php if ($_GET['type'] == "Implementation" || $_GET['type'] == "Maintenance" || $_GET['type'] == "IT") { ?>
		<ul class="nav nav-tabs" id="myTab" role="tablist">
			<li class="nav-item" role="presentation">
				<button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#informationx" type="button" role="tab" aria-controls="generalinformation" aria-selected="false" style="color: black;">Information</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="assesment-tab" data-bs-toggle="tab" data-bs-target="#assesmentx" type="button" role="tab" aria-controls="assesment" aria-selected="false" style="color: black;">Assesment</button>
			</li>
			<?php if ($_GET['costimpact'] == "Financial" || $_GET['costimpact'] == "Ada") { ?>
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="changecostplan-tab" data-bs-toggle="tab" data-bs-target="#costplanx" type="button" role="tab" aria-controls="changecostplan" aria-selected="false" style="color: black;">Cost Plan</button>
				</li>
			<?php } elseif ($_GET['costimpact'] == "Technical" || $_GET['costimpact'] == "Project Deliverables" || $_GET['costimpact'] == "Tidak Ada") { ?>
				</li> <?php } ?>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="changeimplementationplan-tab" data-bs-toggle="tab" data-bs-target="#implementx" type="button" role="tab" aria-controls="changeimplementationplan" aria-selected="false" style="color: black;">Implementation Plan</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="changerequestapproval-tab" data-bs-toggle="tab" data-bs-target="#approvalx" type="button" role="tab" aria-controls="changerequestapproval" aria-selected="false" style="color: black;">Approval (Change Control Board)</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="fileupload-tab" data-bs-toggle="tab" data-bs-target="#uploadx" type="button" role="tab" aria-controls="fileupload" aria-selected="false" style="color: black;">File Upload</button>
			</li>
		</ul>
	<?php } ?>
	<?php if ($_GET['type'] == "Sales/Presales") { ?>
		<ul class="nav nav-tabs" id="myTab" role="tablist">
			<li class="nav-item" role="presentation">
				<button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#informationx" type="button" role="tab" aria-controls="generalinformation" aria-selected="false" style="color: black;">Information</button>
			</li>
			<?php if ($_GET['classification'] == 'Administrative Change') { ?>
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="assesment-tab" data-bs-toggle="tab" data-bs-target="#assesmentx" type="button" role="tab" aria-controls="assesment" aria-selected="false" style="color: black;">Adm Change</button>
				</li>
			<?php } elseif ($_GET['classification'] == 'Cost Change') { ?>
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="changeimplementationplan-tab" data-bs-toggle="tab" data-bs-target="#implementx" type="button" role="tab" aria-controls="changeimplementationplan" aria-selected="false" style="color: black;">Cost Change</button>
				</li>
			<?php } ?>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="changerequestapproval-tab" data-bs-toggle="tab" data-bs-target="#approvalx" type="button" role="tab" aria-controls="changerequestapproval" aria-selected="false" style="color: black;">Approval (Change Control Board)</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="fileupload-tab" data-bs-toggle="tab" data-bs-target="#uploadx" type="button" role="tab" aria-controls="fileupload" aria-selected="false" style="color: black;">File Upload</button>
			</li>
		</ul>
	<?php } ?>

	<div class="tab-content" id="myTabContent">
		<!-- TAB Change Request Type -->
		<div class="tab-pane fade show active" id="informationx" role="tabpanel" aria-labelledby="crtype-tab">
			<div class="card shadow mb-4">
				<!-- Card Body -->
				<div class="card-body">
					<h2>General Informations</h2>
					<div class="row">
						<div class="col-lg-6">
							<div class="row mb-3">
								<label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Change Request Type</label>
								<div class="col-sm-8">
									<?php if (isset($_GET['type']) && $_GET['act'] == "add") { ?>
										<select class="form-control" name="cr_type" id="cr_type" onchange="changeCRtype();" required>
											<option value="Implementation">Implementation</option>
											<option value="Maintenance">Maintenance</option>
											<option value="IT">IT</option>
											<option value="Sales/Presales">Sales/Presales</option>
										</select>
									<?php } ?>
									<?php if ($_GET['act'] == "review" || $_GET['act'] == "edit") { ?>
										<input type="text" class="form-control form-control-sm" id="cr_type" name="cr_type" onchange="changeCRtype();" <?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																							echo "disabled";
																																						} ?> value="<?php echo ucwords($ddata['change_request_type']); ?>" readonly>
									<?php } ?>
								</div>
							</div>
						</div>

						<?php if (isset($_GET['project_code'])) {
							if ($_GET['project_code'] == null) {
								"";
							} else {
						?>
								<div class="col-lg-6">
									<?php if ($_GET['type'] == "Implementation" || $_GET['type'] == "Maintenance") { ?>
										<div class="row mb-3">
											<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Impact <i class="fas fa-question-circle" data-bs-toggle="popover" data-bs-trigger="focus" title="Technical = Location Change, Firmware diluar kriteria ticket Move, Add, Change(Maintenance)
Project Deliverables = Perubahan Scope tanpa penambahan mandays/budget, Perubahan Schedule atas kesepakatan dengan Customer
Financial = Salah Assesment(penambahan scope, barang dan/atau mandays), Penambahan Biaya Project (Mandays), Penambahan Business Trip"></i></label>
											<div class="col-sm-9">
												<?php if ($_GET['act'] == "add") { ?>
													<?php if (empty($_GET['so_number'])) { ?>
														<select class="form-control" name="cost_impact" id="cost_impact" onchange="CostImpact();" readonly>
															<option value="Technical">Technical</option>
														</select>
													<?php } else { ?>
														<select class="form-control" name="cost_impact" id="cost_impact" onchange="CostImpact();" required>
															<option value="Technical">Technical</option>
															<option value="Project Deliverables">Project Deliverables</option>
															<option value="Financial">Financial</option>
														</select>
													<?php } ?>
												<?php } ?>
												<?php if ($_GET['act'] == "review" || $_GET['act'] == "edit") { ?>
													<input type="text" class="form-control form-control-sm" id="cost_impact" name="cost_impact" onchange="CostImpact();" <?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																												echo "disabled";
																																											} ?> value="<?php echo ucwords($ddata['cost_impact']); ?>">
												<?php } ?>
											</div>
										</div>
									<?php } ?>
									<?php if ($_GET['type'] == "Sales/Presales") { ?>
										<div class="row mb-3">
											<label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Type of Service <b>*</b></label>
											<div class="col-sm-8">
												<?php if ($_GET['act'] == "add" && $_GET['type'] == "Sales/Presales") { ?>
													<div class="form-check form-check-inline">
														<input class="form-check-input" type="checkbox" name="type_of_service[]" id="type_of_service" value="Implementasi">
														<label class="form-check-label" for="inlineRadio1">Implementasi</label>
													</div>
													<div class="form-check form-check-inline">
														<input class="form-check-input" type="checkbox" name="type_of_service[]" id="type_of_service" value="Maintenance">
														<label class="form-check-label" for="inlineRadio2">Maintenance</label>
													</div>
													<div class="form-check form-check-inline">
														<input class="form-check-input" type="checkbox" name="type_of_service[]" id="type_of_service" value="Others">
														<label class="form-check-label" for="inlineRadio3">Others: POC, dll. </label>
													</div>
												<?php } ?>
												<?php if ($_GET['act'] == "edit" && $_GET['type'] == "Sales/Presales") { ?>
													<?php $tos = isset($data23[0]['type_of_service']); ?>
													<?php if ($tos == null) { ?>
														<div class="form-check form-check-inline">
															<input class="form-check-input" type="checkbox" name="type_of_service[]" id="type_of_service" value="Implementasi">
															<label class="form-check-label" for="inlineRadio1">Implementasi</label>
														</div>
													<?php } else { ?>
														<div class="form-check form-check-inline">
															<input class="form-check-input" type="checkbox" name="type_of_service[]" id="type_of_service" value="Implementasi" <?php
																																												echo "checked";
																																												?>>
															<label class="form-check-label" for="inlineRadio1">Implementasi</label>
														</div>
													<?php } ?>
													<?php $tosm = isset($data24[0]['type_of_service']); ?>
													<?php if ($tosm == null) { ?>
														<div class="form-check form-check-inline">
															<input class="form-check-input" type="checkbox" name="type_of_service[]" id="type_of_service" value="Maintenance">
															<label class="form-check-label" for="inlineRadio2">Maintenance</label>
														</div>
													<?php } else { ?>
														<div class="form-check form-check-inline">
															<input class="form-check-input" type="checkbox" name="type_of_service[]" id="type_of_service" value="Maintenance" <?php echo "checked"; ?>>
															<label class="form-check-label" for="inlineRadio2">Maintenance</label>
														</div>
													<?php } ?>
													<?php $tosr = isset($data25[0]['type_of_service']); ?>
													<?php if ($tosr == null) { ?>
														<div class="form-check form-check-inline">
															<input class="form-check-input" type="checkbox" name="type_of_service[]" id="type_of_service" value="Others">
															<label class="form-check-label" for="inlineRadio3">Others: POC, dll. </label>
														</div>
													<?php } else { ?>
														<div class="form-check form-check-inline">
															<input class="form-check-input" type="checkbox" name="type_of_service[]" id="type_of_service" value="Others" <?php
																																											echo "checked";
																																											?>>
															<label class="form-check-label" for="inlineRadio3">Others: POC, dll. </label>
														</div>
													<?php } ?>
												<?php } ?>
												<?php if ($_GET['act'] == "review" && $_GET['type'] == "Sales/Presales") { ?>
													<?php $tos = isset($data23[0]['type_of_service']); ?>
													<?php if ($tos == null) { ?>
														<div class="form-check form-check-inline">
															<input class="form-check-input" type="checkbox" name="type_of_service[]" id="type_of_service" <?php echo "disabled"; ?> value="Implementasi">
															<label class="form-check-label" for="inlineRadio1">Implementasi</label>
														</div>
													<?php } else { ?>
														<div class="form-check form-check-inline">
															<input class="form-check-input" type="checkbox" name="type_of_service[]" id="type_of_service" <?php echo "disabled"; ?> value="Implementasi" <?php
																																																			echo "checked";
																																																			?>>
															<label class="form-check-label" for="inlineRadio1">Implementasi</label>
														</div>
													<?php } ?>
													<?php $tosm = isset($data24[0]['type_of_service']); ?>
													<?php if ($tosm == null) { ?>
														<div class="form-check form-check-inline">
															<input class="form-check-input" type="checkbox" name="type_of_service[]" id="type_of_service" <?php echo "disabled"; ?> value="Maintenance">
															<label class="form-check-label" for="inlineRadio2">Maintenance</label>
														</div>
													<?php } else { ?>
														<div class="form-check form-check-inline">
															<input class="form-check-input" type="checkbox" name="type_of_service[]" id="type_of_service" <?php echo "disabled"; ?> value="Maintenance" <?php echo "checked"; ?>>
															<label class="form-check-label" for="inlineRadio2">Maintenance</label>
														</div>
													<?php } ?>
													<?php $tosr = isset($data25[0]['type_of_service']); ?>
													<?php if ($tosr == null) { ?>
														<div class="form-check form-check-inline">
															<input class="form-check-input" type="checkbox" name="type_of_service[]" id="type_of_service" <?php echo "disabled"; ?> value="Others">
															<label class="form-check-label" for="inlineRadio3">Others: POC, dll. </label>
														</div>
													<?php } else { ?>
														<div class="form-check form-check-inline">
															<input class="form-check-input" type="checkbox" name="type_of_service[]" id="type_of_service" <?php echo "disabled"; ?> value="Others" <?php
																																																	echo "checked";
																																																	?>>
															<label class="form-check-label" for="inlineRadio3">Others: POC, dll. </label>
														</div>
													<?php } ?>
												<?php } ?>
											</div>
										</div>
									<?php } ?>
								</div>
						<?php }
						} ?>
					</div>

					<div class="row">
						<div class="col-lg-6">
							<div class="row mb-3">
								<label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Change Request No</label>
								<div class="col-sm-8">
									<?php if ($_GET['act'] == "add") { ?>
										<input type="text" class="form-control form-control-sm" id="cr_no" name="cr_no" value="Generated by system" disabled>
									<?php } ?>
									<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete" || $_GET['act'] == "edit") { ?>
										<input type="text" class="form-control form-control-sm" id="cr_no" name="cr_no" <?php if ($_GET['act'] == 'review' || $_GET['act'] == "edit" || $_GET['act'] == 'complete') {
																														} ?> value="<?php echo $ddata['cr_no']; ?>" readonly>
									<?php } ?>
								</div>
							</div>
							<?php if ($_GET['type'] == "Implementation" || $_GET['type'] == "Maintenance") { ?>
								<div class="row mb-3" id="sonumber">
									<label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">SO Number <b>*</b></label>
									<div class="col-sm-8">
										<?php if ($_GET['act'] == "add") { ?>
											<?php $sonum = isset($selected_pc[0]['so_number']);
											if ($sonum == null) { ?>
												<input type="text" class="form-control form-control-sm" id="so_number" name="so_number">
												<?php if ($_GET['project_code'] != null && $sonum == null) { ?>
													<script>
														alert("No.KP belum terdaftar");
													</script>
												<?php } ?>
											<?php } else { ?>
												<select class="form-control form-control-sm" id="so_number" name="so_number">
													<option value="">-----Choose SO-----</option>
													<?php do {
													?>
														<option value="<?php echo $selected_pc[0]['so_number']; ?>"><?php echo $selected_pc[0]['so_number']; ?></option>
													<?php } while ($selected_pc[0] = $selected_pc[1]->fetch_assoc());
													?>
												</select>
											<?php } ?>
										<?php } ?>
										<?php if ($_GET['act'] == "review" || $_GET['act'] == "edit" || $_GET['act'] == "complete") { ?>
											<input type="text" class="form-control form-control-sm" id="so_number" name="so_number" <?php if ($_GET['act'] == "edit" || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																	} ?> value="<?php echo $ddata['so_number']; ?>" readonly>
										<?php } ?>
									</div>
								</div>
							<?php } ?>
							<?php if ($_GET['type'] == "Sales/Presales") { ?>
								<div class="row mb-3" id="sonumber">
									<label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">SO Number <b>*</b></label>
									<div class="col-sm-8">
										<?php if ($_GET['act'] == "add") { ?>
											<?php $sonum = isset($selected_pc[0]['so_number']);
											if ($sonum == null) { ?>
												<input type="text" class="form-control form-control-sm" id="so_number" name="so_number">
												<?php if ($_GET['project_code'] != null && $sonum == null) { ?>
													<script>
														alert("No.KP belum terdaftar");
													</script>
												<?php } ?>
											<?php } else { ?>
												<select class="form-control form-control-sm" id="so_number" name="so_number">
													<option value="">-----Choose SO-----</option>
													<?php do {
													?>
														<option value="<?php echo $selected_pc[0]['so_number']; ?>"><?php echo $selected_pc[0]['so_number']; ?></option>
													<?php } while ($selected_pc[0] = $selected_pc[1]->fetch_assoc());
													?>
												</select>
											<?php } ?>
										<?php } ?>
										<?php if ($_GET['act'] == "review" || $_GET['act'] == "edit" || $_GET['act'] == "complete") { ?>
											<input type="text" class="form-control form-control-sm" id="so_number" name="so_number" <?php if ($_GET['act'] == "edit" || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																	} ?> value="<?php echo $ddata['so_number']; ?>" readonly>
										<?php } ?>
									</div>
								</div>
							<?php } ?>

							<?php if ($_GET['type'] == "Implementation" || $_GET['type'] == "Maintenance") { ?>
								<div class="row mb-3">
									<label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Project Code <b>*</b></label>
									<div class="col-sm-8">
										<?php if ($_GET['act'] == "add") { ?>
											<?php if (isset($_GET['project_code'])) { ?>
												<?php if ($_GET['project_code'] == null) { ?>
													<input type="text" class="form-control form-control-sm" id="project_code" name="project_code" value="<?php if (isset($GET['project_code'])) {
																																								echo $_GET['project_code'];
																																							} ?>" required>
												<?php } else { ?>
													<input type="text" class="form-control form-control-sm" id="project_code" name="project_code" value="<?php echo $_GET['project_code']; ?>" required>
												<?php }
											} else { ?>
												<input type="text" class="form-control form-control-sm" id="project_code" name="project_code" value="<?php if (isset($GET['project_code'])) {
																																							echo $_GET['project_code'];
																																						}  ?>" required>

											<?php }  ?>
										<?php }  ?>
										<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete" || $_GET['act'] == "edit") { ?>
											<input type="text" class="form-control form-control-sm" id="project_code" name="project_code" <?php if ($_GET['act'] == "edit" || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																			} ?> value="<?php echo $ddata['project_code']; ?>" readonly>
										<?php } ?>
									</div>
								</div>
								<div class="row mb-3">
									<label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Customer</label>
									<div class="col-sm-8">
										<?php if ($_GET['act'] == "add") { ?>
											<?php $cusname = isset($selected_pc2[0]['customer_name']);
											if ($cusname == null) { ?>
												<input type="text" class="form-control form-control-sm" id="customer" name="customer">
											<?php } else { ?>
												<select type="text" class="form-control form-control-sm" id="customer" name="customer">
													<option value="<?php echo $selected_pc2[0]['customer_name']; ?>"><?php echo $selected_pc2[0]['customer_name']; ?></option>
												</select>
											<?php } ?>
										<?php } ?>
										<?php if ($_GET['act'] == "edit" || $_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
											<input type="text" class="form-control form-control-sm" id="customer" name="customer" <?php if ($_GET['act'] == "edit" || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																	} ?> value="<?php echo $ddata['customer']; ?>" readonly>
										<?php } ?>
									</div>
								</div>
								<div class="row mb-3">
									<label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Project Name</label>
									<div class="col-sm-8">
										<?php if ($_GET['act'] == "add") { ?>
											<?php $prjctname = isset($selected_pc2[0]['project_name']); ?>
											<?php if ($prjctname == null) { ?>
												<input type="text" class="form-control form-control-sm" id="project_name" name="project_name">
											<?php } else { ?>
												<select class="form-control form-control-sm" id="project_name" name="project_name">
													<option value="<?php echo $selected_pc2[0]['project_name']; ?>"><?php echo $selected_pc2[0]['project_name']; ?></option>
												</select>
											<?php } ?>
										<?php } ?>
										<?php if ($_GET['act'] == "edit" || $_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
											<input type="text" class="form-control form-control-sm" id="project_name" name="project_name" <?php if ($_GET['act'] == "edit" || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																			} ?> value="<?php echo $ddata['project_name']; ?>" readonly>
										<?php } ?>
									</div>
								</div>
							<?php } ?>

							<?php if ($_GET['type'] == "Sales/Presales") { ?>
								<div class="row mb-3">
									<label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Project Code <b>*</b></label>
									<div class="col-sm-8">
										<?php if ($_GET['act'] == "add") { ?>
											<?php if (isset($_GET['project_code'])) { ?>
												<?php if ($_GET['project_code'] == null) { ?>
													<input type="text" class="form-control form-control-sm" id="project_code" name="project_code" value="<?php if (isset($GET['project_code'])) {
																																								echo $_GET['project_code'];
																																							} ?>" required>
												<?php } else { ?>
													<input type="text" class="form-control form-control-sm" id="project_code" name="project_code" value="<?php echo $_GET['project_code']; ?>" required>
												<?php }
											} else { ?>
												<input type="text" class="form-control form-control-sm" id="project_code" name="project_code" value="<?php if (isset($GET['project_code'])) {
																																							echo $_GET['project_code'];
																																						}  ?>" required>

											<?php }  ?>
										<?php }  ?>
										<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete" || $_GET['act'] == "edit") { ?>
											<input type="text" class="form-control form-control-sm" id="project_code" name="project_code" <?php if ($_GET['act'] == "edit" || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																			} ?> value="<?php echo $ddata['project_code']; ?>" readonly>
										<?php } ?>
									</div>
								</div>
								<div class="row mb-3">
									<label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Customer</label>
									<div class="col-sm-8">
										<?php if ($_GET['act'] == "add") { ?>
											<?php $cusname = isset($selected_pc2[0]['customer_name']);
											if ($cusname == null) { ?>
												<input type="text" class="form-control form-control-sm" id="customer" name="customer">
											<?php } else { ?>
												<select type="text" class="form-control form-control-sm" id="customer" name="customer">
													<option value="<?php echo $selected_pc2[0]['customer_name']; ?>"><?php echo $selected_pc2[0]['customer_name']; ?></option>
												</select>
											<?php } ?>
										<?php } ?>
										<?php if ($_GET['act'] == "edit" || $_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
											<input type="text" class="form-control form-control-sm" id="customer" name="customer" <?php if ($_GET['act'] == "edit" || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																	} ?> value="<?php echo $ddata['customer']; ?>" readonly>
										<?php } ?>
									</div>
								</div>
								<div class="row mb-3">
									<label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Project Name</label>
									<div class="col-sm-8">
										<?php if ($_GET['act'] == "add") { ?>
											<?php $prjctname = isset($selected_pc2[0]['project_name']); ?>
											<?php if ($prjctname == null) { ?>
												<input type="text" class="form-control form-control-sm" id="project_name" name="project_name">
											<?php } else { ?>
												<select class="form-control form-control-sm" id="project_name" name="project_name">
													<option value="<?php echo $selected_pc2[0]['project_name']; ?>"><?php echo $selected_pc2[0]['project_name']; ?></option>
												</select>
											<?php } ?>
										<?php } ?>
										<?php if ($_GET['act'] == "edit" || $_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
											<input type="text" class="form-control form-control-sm" id="project_name" name="project_name" <?php if ($_GET['act'] == "edit" || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																			} ?> value="<?php echo $ddata['project_name']; ?>" readonly>
										<?php } ?>
									</div>
								</div>
							<?php } ?>

							<?php if ($_GET['type'] == "IT") { ?>
								<div class="row mb-3">
									<label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Project Code</label>
									<div class="col-sm-8">
										<?php if ($_GET['act'] == "add") { ?>
											<?php if (isset($_GET['project_code'])) { ?>
												<?php if ($_GET['project_code'] == null) { ?>
													<input type="text" class="form-control form-control-sm" id="project_code" name="project_code" value="<?php if (isset($GET['project_code'])) {
																																								echo $_GET['project_code'];
																																							} ?>">
												<?php } else { ?>
													<input type="text" class="form-control form-control-sm" id="project_code" name="project_code" value="<?php echo $_GET['project_code']; ?>">
												<?php }
											} else { ?>
												<input type="text" class="form-control form-control-sm" id="project_code" name="project_code" value="<?php if (isset($GET['project_code'])) {
																																							echo $_GET['project_code'];
																																						}  ?>">
											<?php }  ?>
										<?php }  ?>
										<?php if ($_GET['act'] == "edit") { ?>
											<input type="text" class="form-control form-control-sm" id="project_code" name="project_code" value="<?php echo $ddata['project_code']; ?>">
										<?php } ?>
										<?php if ($_GET['act'] == "review") { ?>
											<input type="text" class="form-control form-control-sm" id="project_code" name="project_code" value="<?php echo $ddata['project_code']; ?>" readonly>
										<?php } ?>
									</div>
								</div>
							<?php } ?>
							<?php if ($_GET['type'] == "Implementation" || $_GET['type'] == "Maintenance") { ?>
								<div class="row mb-3" id="tos">
									<label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Type of Service <b>*</b></label>
									<div class="col-sm-8">
										<?php if ($_GET['project_code'] == null) { ?>
											<input type="text" class="form-control" name="type_of_service[]" id="type_of_service">
										<?php } else { ?>
											<?php if ($_GET['act'] == "add" && $_GET['type'] == "Implementation") { ?>
												<?php $tos = isset($tosgeneral[0]['tos_name']); ?>
												<?php if ($tos == null) { ?>
													<div class="form-check form-check-inline">
														<input class="form-check-input" type="checkbox" name="type_of_service[]" id="type_of_service" value="General">
														<label class="form-check-label" for="inlineRadio1">General</label>
													</div>
												<?php } else { ?>
													<div class="form-check form-check-inline">
														<input class="form-check-input" type="checkbox" name="type_of_service[]" id="type_of_service" value="General" <?php
																																										echo "checked";
																																										?>>
														<label class="form-check-label" for="inlineRadio1">General</label>
													</div>
												<?php } ?>
												<?php $tosm = isset($tosmigration[0]['tos_name']); ?>
												<?php if ($tosm == null) { ?>
													<div class="form-check form-check-inline">
														<input class="form-check-input" type="checkbox" name="type_of_service[]" id="type_of_service" value="Migration">
														<label class="form-check-label" for="inlineRadio2">Migration</label>
													</div>
												<?php } else { ?>
													<div class="form-check form-check-inline">
														<input class="form-check-input" type="checkbox" name="type_of_service[]" id="type_of_service" value="Migration" <?php echo "checked"; ?>>
														<label class="form-check-label" for="inlineRadio2">Migration</label>
													</div>
												<?php } ?>
												<?php $tosr = isset($tosrelocation[0]['tos_name']); ?>
												<?php if ($tosr == null) { ?>
													<div class="form-check form-check-inline">
														<input class="form-check-input" type="checkbox" name="type_of_service[]" id="type_of_service" value="Relocation">
														<label class="form-check-label" for="inlineRadio2">Relocation </label>
													</div>
												<?php } else { ?>
													<div class="form-check form-check-inline">
														<input class="form-check-input" type="checkbox" name="type_of_service[]" id="type_of_service" value="Relocation" <?php
																																											echo "checked";
																																											?>>
														<label class="form-check-label" for="inlineRadio2">Relocation </label>
													</div>
												<?php } ?>
											<?php } ?>
											<?php if ($_GET['act'] == "edit" && $_GET['type'] == "Implementation") { ?>
												<?php $tos = isset($data20[0]['type_of_service']); ?>
												<?php if ($tos == null) { ?>
													<div class="form-check form-check-inline">
														<input class="form-check-input" type="checkbox" name="type_of_service[]" id="type_of_service" value="General">
														<label class="form-check-label" for="inlineRadio1">General</label>
													</div>
												<?php } else { ?>
													<div class="form-check form-check-inline">
														<input class="form-check-input" type="checkbox" name="type_of_service[]" id="type_of_service" value="General" <?php
																																										echo "checked";
																																										?>>
														<label class="form-check-label" for="inlineRadio1">General</label>
													</div>
												<?php } ?>
												<?php $tosm = isset($data21[0]['type_of_service']); ?>
												<?php if ($tosm == null) { ?>
													<div class="form-check form-check-inline">
														<input class="form-check-input" type="checkbox" name="type_of_service[]" id="type_of_service" value="Migration">
														<label class="form-check-label" for="inlineRadio2">Migration</label>
													</div>
												<?php } else { ?>
													<div class="form-check form-check-inline">
														<input class="form-check-input" type="checkbox" name="type_of_service[]" id="type_of_service" value="Migration" <?php echo "checked"; ?>>
														<label class="form-check-label" for="inlineRadio2">Migration</label>
													</div>
												<?php } ?>
												<?php $tosr = isset($data22[0]['type_of_service']); ?>
												<?php if ($tosr == null) { ?>
													<div class="form-check form-check-inline">
														<input class="form-check-input" type="checkbox" name="type_of_service[]" id="type_of_service" value="Relocation">
														<label class="form-check-label" for="inlineRadio2">Relocation </label>
													</div>
												<?php } else { ?>
													<div class="form-check form-check-inline">
														<input class="form-check-input" type="checkbox" name="type_of_service[]" id="type_of_service" value="Relocation" <?php
																																											echo "checked";
																																											?>>
														<label class="form-check-label" for="inlineRadio2">Relocation </label>
													</div>
												<?php } ?>
											<?php } ?>
											<?php if ($_GET['act'] == "review" && $_GET['type'] == "Implementation") { ?>
												<?php $tos = isset($data20[0]['type_of_service']); ?>
												<?php if ($tos == null) { ?>
													<div class="form-check form-check-inline">
														<input class="form-check-input" type="checkbox" name="type_of_service[]" id="type_of_service" <?php echo "disabled"; ?> value="General">
														<label class="form-check-label" for="inlineRadio1">General</label>
													</div>
												<?php } else { ?>
													<div class="form-check form-check-inline">
														<input class="form-check-input" type="checkbox" name="type_of_service[]" id="type_of_service" <?php echo "disabled"; ?> value="General" <?php
																																																echo "checked";
																																																?>>
														<label class="form-check-label" for="inlineRadio1">General</label>
													</div>
												<?php } ?>
												<?php $tosm = isset($data21[0]['type_of_service']); ?>
												<?php if ($tosm == null) { ?>
													<div class="form-check form-check-inline">
														<input class="form-check-input" type="checkbox" name="type_of_service[]" id="type_of_service" <?php echo "disabled"; ?> value="Migration">
														<label class="form-check-label" for="inlineRadio2">Migration</label>
													</div>
												<?php } else { ?>
													<div class="form-check form-check-inline">
														<input class="form-check-input" type="checkbox" name="type_of_service[]" id="type_of_service" <?php echo "disabled"; ?> value="Migration" <?php echo "checked"; ?>>
														<label class="form-check-label" for="inlineRadio2">Migration</label>
													</div>
												<?php } ?>
												<?php $tosr = isset($data22[0]['type_of_service']); ?>
												<?php if ($tosr == null) { ?>
													<div class="form-check form-check-inline">
														<input class="form-check-input" type="checkbox" name="type_of_service[]" id="type_of_service" <?php echo "disabled"; ?> value="Relocation">
														<label class="form-check-label" for="inlineRadio2">Relocation </label>
													</div>
												<?php } else { ?>
													<div class="form-check form-check-inline">
														<input class="form-check-input" type="checkbox" name="type_of_service[]" id="type_of_service" <?php echo "disabled"; ?> value="Relocation" <?php
																																																	echo "checked";
																																																	?>>
														<label class="form-check-label" for="inlineRadio2">Relocation </label>
													</div>
												<?php } ?>
											<?php } ?>
											<?php if ($_GET['act'] == "add" && $_GET['type'] == "Maintenance") { ?>
												<?php $tos = isset($tosname[0]['tos_name']); ?>
												<select class="form-control" name="type_of_service" id="type_of_service" onchange="TypeCR()">
													<?php if ($tos == null) { ?>
														<option></option>
														<option value="Gold">Gold</option>
														<option value="Silver">Silver</option>
														<option value="Bronze">Bronze</option>
														<option value="Manage Service">Manage Service</option>
													<?php } else { ?>
														<option value="<?php echo $tosname[0]['tos_name']; ?>"><?php echo $tosname[0]['tos_name']; ?></option>
													<?php } ?>
												</select>
											<?php } ?>
											<?php if ($_GET['act'] == "edit" && $_GET['type'] == "Maintenance") { ?>
												<select class="form-control" name="type_of_service" id="type_of_service" onchange="TypeCR()">
													<option value="<?php echo $ddata['type_of_service']; ?>"><?php echo $ddata['type_of_service']; ?></option>
													<option value="Gold">Gold</option>
													<option value="Silver">Silver</option>
													<option value="Bronze">Bronze</option>
													<option value="Manage Service">Manage Service</option>
												</select>
											<?php } elseif ($_GET['act'] == "review" && $_GET['type'] == "Maintenance") { ?>
												<input type="text" class="form-control form-control-sm" id="type_of_service" name="type_of_service" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																						echo "disabled";
																																					} ?> value="<?php echo $ddata['type_of_service']; ?>">
											<?php } ?>
										<?php } ?>
									</div>
								</div>
							<?php } ?>
							<?php if ($_GET['type'] == "Implementation" || $_GET['type'] == "Maintenance" || $_GET['type'] == "IT") { ?>
								<div class="row mb-3" id="xaxa">
									<label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Project Manager</label>
									<div class="col-sm-8">
										<?php if ($_GET['act'] == "add") { ?>
											<select class="form-control" name="project_manager" id="project_manager">
												<option value="<?php echo $_SESSION['Microservices_UserEmail'] ?>"><?php echo $_SESSION['Microservices_UserName'] ?></option>
												<?php do { ?>
													<option><?php echo $project_manager[0]['employee_name']; ?></option>
												<?php } while ($project_manager[0] = $project_manager[1]->fetch_assoc()); ?>
											</select>
										<?php } ?>
										<?php if ($_GET['act'] == "edit") { ?>
											<select class="form-control" name="project_manager" id="project_manager">
												<option><?php echo $ddata['project_manager']; ?></option>
												<?php do { ?>
													<option><?php echo $project_manager[0]['employee_name']; ?></option>
												<?php } while ($project_manager[0] = $project_manager[1]->fetch_assoc()); ?>
											</select>
										<?php } ?>
										<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
											<input type="text" class="form-control form-control-sm" id="project_manager" name="project_manager" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																				} ?> value="<?php echo $ddata['project_manager']; ?>" readonly>
										<?php } ?>
									</div>
								</div>
								<div class="row mb-3">
									<label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Request Date</label>
									<div class="col-sm-8">
										<?php if ($_GET['act'] == "add" || $_GET['act'] == "edit") { ?>
											<select name="request_date" id="request_date" class="form-control">
												<option name="request_date" id="request_date" value="<?php echo date("Y-m-d"); ?>"><?php echo date("Y-m-d"); ?></option>
											</select>
										<?php } else if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') { ?>
											<input type="date" class="form-control form-control-sm" id="request_date" name="request_date" value="<?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																						echo $ddata['request_date'];
																																					} ?>" readonly>
										<?php } ?>
									</div>
								</div>
								<div class="row mb-3">
									<label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Requested By Email</label>
									<div class="col-sm-8">
										<?php if ($_GET['act'] == 'review') { ?>
											<input type="text" class="form-control form-control-sm" id="requested_by" name="requested_by" value="<?php echo $ddata['requested_by_email']; ?>" readonly>
										<?php } elseif ($_GET['act'] == 'add') { ?>
											<select class="form-control" name="requested_by" id="requested_by">
												<option value="<?php echo $_SESSION['Microservices_UserEmail']; ?>"><?php echo $_SESSION['Microservices_UserEmail']; ?></option>
											</select>
										<?php } elseif ($_GET['act'] == 'edit') { ?>
											<select class="form-control" name="requested_by" id="requested_by">
												<option value="<?php echo $ddata['requested_by_email']; ?>"><?php echo $ddata['requested_by_email']; ?></option>
											</select>
										<?php } ?>
									</div>
								</div>
							<?php } ?>
							<div class="row mb-3">
								<?php if ($_GET['type'] == 'Sales/Presales') { ?>
									<label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Classification <i class="fas fa-question-circle" data-bs-toggle="popover" data-bs-trigger="focus" title="1.	Administrative Change: perubahan yang tidak ada impact ke cost, misalkan perubahan akibat salah input (edit nama project, band, project informasi, informasi kategori, tipe service, typo pengisian cost, perubahan type SBF)
2.	Cost Change: perubahan yang berimpact kepada cost, misalkan penambahan mandays, tambahan pekerjaan pihak ketiga/subcon, request jasa tambahan: POC yang dilakukan oleh Post Sales, dll"></i></label>
								<?php } else { ?>
									<label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Classification</label>
								<?php } ?>
								<div class="col-sm-8">
									<?php if ($_GET['act'] == "add" && $_GET['type'] == "Maintenance") { ?>
										<select class="form-control" name="classification" id="classification">
											<option value="Major Change">Major Change</option>
											<option value="Minor Change">Minor Change</option>
											<option value="Minor-Non Ticket Change">Minor-Non Ticket Change</option>
										</select>
									<?php } ?>
									<?php if ($_GET['act'] == "edit" && $_GET['type'] == "Maintenance") { ?>
										<select class="form-control" name="classification" id="classification">
											<option><?php echo ucwords($ddata['classification']); ?></option>
											<option value="Major Change">Major Change</option>
											<option value="Minor Change">Minor Change</option>
											<option value="Minor-Non Ticket Change">Minor-Non Ticket Change</option>
										</select>
									<?php } ?>
									<?php if ($_GET['act'] == "review" && $_GET['type'] == "Maintenance") { ?>
										<input type="text" class="form-control form-control-sm" id="classification" name="classification" <?php echo "disabled"; ?> value="<?php echo ucwords($ddata['classification']); ?>">
									<?php } ?>
									<?php if ($_GET['act'] == "add" && $_GET['type'] == "Sales/Presales") { ?>
										<select class="form-control" name="classification" id="classification" onchange="changeClass();">
											<option value="Administrative Change">Administrative Change</option>
											<option value="Cost Change">Cost Change</option>
										</select>
									<?php } ?>
									<?php if ($_GET['act'] == "edit" && $_GET['type'] == "Sales/Presales") { ?>
										<select class="form-control" name="classification" id="classification">
											<option value="<?php echo ucwords($ddata['classification']); ?>" readonly><?php echo ucwords($ddata['classification']); ?></option>\
										</select>
									<?php } ?>
									<?php if ($_GET['act'] == "review" && $_GET['type'] == "Sales/Presales") { ?>
										<input type="text" class="form-control form-control-sm" id="classification" name="classification" <?php echo "disabled"; ?> value="<?php echo ucwords($ddata['classification']); ?>">
									<?php } ?>
									<?php if ($_GET['act'] == "add" && $_GET['type'] == "Implementation") { ?>
										<select class="form-control" name="classification" id="classification">
											<option value="Major Change">Major Change</option>
										</select>
									<?php } ?>
									<?php if ($_GET['act'] == "edit" && $_GET['type'] == "Implementation") { ?>
										<select class="form-control" name="classification" id="classification">
											<option><?php echo ucwords($ddata['classification']); ?></option>
										</select>
									<?php } ?>
									<?php if ($_GET['act'] == "review" && $_GET['type'] == "Implementation") { ?>
										<input type="text" class="form-control form-control-sm" id="classification" name="classification" <?php echo "disabled"; ?> value="<?php echo $ddata['classification']; ?>">
									<?php } ?>
									<?php if ($_GET['act'] == "add" && $_GET['type'] == "IT") { ?>
										<select class="form-control" name="classification" id="classification">
											<option value="Major Change">Major Change</option>
											<option value="Minor Change">Minor Change</option>
										</select>
									<?php } ?>
									<?php if ($_GET['act'] == "edit" && $_GET['type'] == "IT") { ?>
										<select class="form-control" name="classification" id="classification">
											<option><?php echo ucwords($ddata['classification']); ?></option>
											<option value="Major Change">Major Change</option>
											<option value="Minor Change">Minor Change</option>
										</select>
									<?php } ?>
									<?php if ($_GET['act'] == "review" && $_GET['type'] == "IT") { ?>
										<input type="text" class="form-control form-control-sm" id="classification" name="classification" <?php echo "disabled"; ?> value="<?php echo ucwords($ddata['classification']); ?>">
									<?php } ?>
								</div>
							</div>
							<?php if ($_GET['type'] == "Implementation") { ?>
								<div class="row mb-3" id="changeimpact">
									<label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Change Impact <b>*</b></label>
									<div class="col-sm-8">
										<?php if ($_GET['act'] == "add") { ?>
											<textarea class="form-control" name="change_impact" id="change_impact" rows="2" required></textarea>
										<?php
										}
										if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') { ?>
											<textarea class="form-control" name="change_impact" id="change_impact" rows="2"><?php echo $ddata['impact']; ?></textarea>
										<?php }    ?>
									</div>

								</div>
							<?php } ?>
							<?php if ($_GET['type'] == "Implementation" || $_GET['type'] == "Maintenance" || $_GET['type'] == "IT") { ?>
								<div class="row mb-3">
									<label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Scope Of Change <b>*</b></label>
									<div class="col-sm-8">
										<?php if ($_GET['act'] == "add") { ?>
											<textarea class="form-control" name="scope_of_change" id="scope_of_change" rows="2" required></textarea>
										<?php
										}
										if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') { ?>
											<textarea class="form-control" name="scope_of_change" id="scope_of_change" rows="2" readonly><?php echo $ddata['scope_of_change']; ?></textarea>
										<?php }    ?>
									</div>
								</div>
							<?php } ?>
							<div class="row mb-3">
								<label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Reason of Change <b>*</b></label>
								<div class="col-sm-8">
									<?php if ($_GET['act'] == "add") { ?>
										<textarea class="form-control" name="reason" id="reason" rows="2" required></textarea>
									<?php
									}
									if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') { ?>
										<textarea class="form-control" name="reason" id="reason" rows="2" readonly><?php echo $ddata['reason']; ?></textarea>
									<?php } ?>
								</div>
							</div>
							<?php if ($_GET['type'] == "IT") { ?>
								<div class="row mb-3" id="impact_it">
									<label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Impact</label>
									<div class="col-sm-8">
										<?php if ($_GET['act'] == "add") { ?>
											<select class="form-control" name="impact_it" id="impact_it">
												<option value="Critical Impact">Critical Impact</option>
												<option value="High Impact">High Impact</option>
												<option value="Medium Impact">Medium Impact</option>
												<option value="Low Impact">Low Impact</option>
											</select>
										<?php } elseif ($_GET['act'] == "edit") { ?>
											<select class="form-control" name="impact_it" id="impact_it">
												<option value="<?php echo $ddata['impact_it']; ?>"><?php echo $ddata['impact_it']; ?></option>
												<option value="Critical Impact">Critical Impact</option>
												<option value="High Impact">High Impact</option>
												<option value="Medium Impact">Medium Impact</option>
												<option value="Low Impact">Low Impact</option>
											</select>
										<?php } elseif ($_GET['act'] == "review") { ?>
											<select class="form-control" name="impact_it" id="impact_it">
												<option value="<?php echo $ddata['impact_it']; ?>"><?php echo $ddata['impact_it']; ?></option>
											</select>
										<?php } ?>
									</div>
								</div>
							<?php } ?>
						</div>
						<?php if (isset($_GET['project_code'])) { ?>
							<div class="col-lg-6">
								<div class="row mb-3">
									<?php if ($_GET['type'] == "Maintenance") { ?>
										<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Used Ticket Amount</label>
										<div class="col-sm-9">
											<?php if ($_GET['act'] == "add") { ?>
												<input type="text" class="form-control form-control-sm" id="used_ticket_amount" name="used_ticket_amount" value="0" placeholder="0">
											<?php } ?>
											<?php
											if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') { ?>
												<input class="form-control form-control-sm" type="text" id="used_ticket_amount" name="used_ticket_amount" <?php echo 'disabled'; ?> value="<?php echo $ddata16['used_ticket']; ?>">
											<?php } ?>
											<?php
											if ($_GET['type'] == "Maintenance" && $_GET['act'] == 'edit') { ?>
												<input type="text" class="form-control form-control-sm" id="used_ticket_amount" name="used_ticket_amount" value="<?php echo $ddata16['used_ticket']; ?>">
											<?php } ?>
										</div>
									<?php } ?>
								</div>
								<div class="row mb-3">
									<?php if ($_GET['type'] == "Maintenance") { ?>
										<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Ticket Allocation</label>
										<?php if ($_GET['act'] == "add") { ?>
											<?php $ticket = isset($sisaticketnya[0]['ticket_allocation_sisa']); ?>
											<?php if ($ticket == null) { ?>
												<?php if (isset($tosname)) { ?>
													<?php if ($tosname[0]['tos_name'] == 'Gold') { ?>
														<div class="col-sm-4">
															<select class="form-control form-control-sm" id="ticket_allocation_sisa" name="ticket_allocation_sisa">
																<option type="text" class="form-control form-control-sm" id="ticket_allocation_sisa" name="ticket_allocation_sisa" value="0">0</option>
															</select>
															<i style="font-size: 12px;">Sisa Ticket</i>
														</div>
													<?php } ?>
													<?php if ($tosname[0]['tos_name'] == 'Silver') { ?>
														<div class="col-sm-4">
															<select class="form-control form-control-sm" id="ticket_allocation_sisa" name="ticket_allocation_sisa">
																<option type="text" class="form-control form-control-sm" id="ticket_allocation_sisa" name="ticket_allocation_sisa" value="9">9</option>
															</select>
															<i style="font-size: 12px;">Sisa Ticket</i>
														</div>
													<?php } ?>
													<?php if ($tosname[0]['tos_name'] == 'Bronze') { ?>
														<div class="col-sm-4">
															<select class="form-control form-control-sm" id="ticket_allocation_sisa" name="ticket_allocation_sisa">
																<option type="text" class="form-control form-control-sm" id="ticket_allocation_sisa" name="ticket_allocation_sisa" value="6">6</option>
															</select>
															<i style="font-size: 12px;">Sisa Ticket</i>
														</div>
													<?php } ?>
												<?php } else { ?>
													<div class="col-sm-4" id="type_gold">
														<select class="form-control form-control-sm" id="ticket_allocation_sisa" name="ticket_allocation_sisa">
															<option type="text" class="form-control form-control-sm" id="ticket_allocation_sisa" name="ticket_allocation_sisa" value="0">0</option>
														</select>
														<i style="font-size: 12px;">Sisa Ticket</i>
													</div>
													<div class="col-sm-4" id="type_silver">
														<select class="form-control form-control-sm" id="ticket_allocation_sisa" name="ticket_allocation_sisa">
															<option type="text" class="form-control form-control-sm" id="ticket_allocation_sisa" name="ticket_allocation_sisa" value="9">9</option>
														</select>
														<i style="font-size: 12px;">Sisa Ticket</i>
													</div>
													<div class="col-sm-4" id="type_bronze">
														<select class="form-control form-control-sm" id="ticket_allocation_sisa" name="ticket_allocation_sisa">
															<option type="text" class="form-control form-control-sm" id="ticket_allocation_sisa" name="ticket_allocation_sisa" value="6">6</option>
														</select>
														<i style="font-size: 12px;">Sisa Ticket</i>
													</div>
												<?php } ?>
											<?php } else { ?>
												<div class="col-sm-4">
													<select class="form-control" name="ticket_allocation_sisa" id="ticket_allocation_sisa">
														<option type="text" class="form-control form-control-sm" id="ticket_allocation_sisa" name="ticket_allocation_sisa" value="<?php echo $sisaticketnya[0]['ticket_allocation_sisa']; ?>"><?php echo $sisaticketnya[0]['ticket_allocation_sisa']; ?></option>
													</select>
													<i style="font-size: 12px;">Sisa Ticket</i>
												</div>
											<?php } ?>
										<?php } ?>
										<?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {  ?>
											<div class="col-sm-4">
												<input type="text" class="form-control form-control-sm" id="ticket_allocation_sisa" name="ticket_allocation_sisa" <?php echo "disabled"; ?> value="<?php echo $ddata16['ticket_allocation_sisa']; ?>">
												<i style="font-size: 12px;">Sisa Ticket</i>
											</div>
										<?php } ?>
										<?php if ($_GET['act'] == 'edit') { ?>
											<div class="col-sm-4">
												<input type="text" class="form-control form-control-sm" id="ticket_allocation_sisa" name="ticket_allocation_sisa" value="<?php echo $ddata16['ticket_allocation_sisa'] + $ddata16['used_ticket']; ?>">
												<i style="font-size: 12px;">Sisa Ticket</i>
											</div>
										<?php } ?>
										<div class="col-sm-1">
											<label>/</label>
										</div>
										<?php if ($_GET['act'] == "add") { ?>
											<?php $allocation = isset($sisaticketnya[0]['ticket_allocation']); ?>
											<?php if ($allocation == null) { ?>
												<?php if (isset($tosname)) { ?>
													<?php if ($tosname[0]['tos_name'] == 'Gold') { ?>
														<div class="col-sm-4">
															<select class="form-control" name="ticket_allocation" id="ticket_allocation">
																<option type="text" class="form-control form-control-sm" id="ticket_allocation" name="ticket_allocation" value="<?php echo $jumlahtos_gold[0]['jumlah_ticket']; ?>"><?php echo $jumlahtos_gold[0]['jumlah_ticket']; ?></option>
															</select>
															<i style="font-size: 12px;">Jumlah Ticket</i>
														</div>
													<?php } ?>
													<?php if ($tosname[0]['tos_name'] == 'Silver') { ?>
														<div class="col-sm-4">
															<select class="form-control" name="ticket_allocation" id="ticket_allocation">
																<option type="text" class="form-control form-control-sm" id="ticket_allocation" name="ticket_allocation" value="<?php echo $jumlahtos_silver[0]['jumlah_ticket']; ?>"><?php echo $jumlahtos_silver[0]['jumlah_ticket']; ?></option>
															</select>
															<i style="font-size: 12px;">Jumlah Ticket</i>
														</div>
													<?php } ?>
													<?php if ($tosname[0]['tos_name'] == 'Bronze') { ?>
														<div class="col-sm-4">
															<select class="form-control" name="ticket_allocation" id="ticket_allocation">
																<option type="text" class="form-control form-control-sm" id="ticket_allocation" name="ticket_allocation" value="<?php echo $jumlahtos_bronze[0]['jumlah_ticket']; ?>"><?php echo $jumlahtos_bronze[0]['jumlah_ticket']; ?></option>
															</select>
															<i style="font-size: 12px;">Jumlah Ticket</i>
														</div>
													<?php } ?>
												<?php } else { ?>
													<div class="col-sm-4" id="xago">
														<select class="form-control" name="ticket_allocation" id="ticket_allocation">
															<option type="text" class="form-control form-control-sm" id="ticket_allocation" name="ticket_allocation" value="<?php echo $jumlahtos_gold[0]['jumlah_ticket']; ?>"><?php echo $jumlahtos_gold[0]['jumlah_ticket']; ?></option>
														</select>
														<i style="font-size: 12px;">Jumlah Ticket</i>
													</div>
													<div class="col-sm-4" id="xasi">
														<select class="form-control" name="ticket_allocation" id="ticket_allocation">
															<option type="text" class="form-control form-control-sm" id="ticket_allocation" name="ticket_allocation" value="<?php echo $jumlahtos_silver[0]['jumlah_ticket']; ?>"><?php echo $jumlahtos_silver[0]['jumlah_ticket']; ?></option>
														</select>
														<i style="font-size: 12px;">Jumlah Ticket</i>
													</div>
													<div class="col-sm-4" id="xabro">
														<select class="form-control" name="ticket_allocation" id="ticket_allocation">
															<option type="text" class="form-control form-control-sm" id="ticket_allocation" name="ticket_allocation" value="<?php echo $jumlahtos_bronze[0]['jumlah_ticket']; ?>"><?php echo $jumlahtos_bronze[0]['jumlah_ticket']; ?></option>
														</select>
														<i style="font-size: 12px;">Jumlah Ticket</i>
													</div>
												<?php } ?>
											<?php } else { ?>
												<div class="col-sm-4">
													<select class="form-control" name="ticket_allocation" id="ticket_allocation">
														<option type="text" class="form-control form-control-sm" id="ticket_allocation" name="ticket_allocation" value="<?php echo $sisaticketnya[0]['ticket_allocation']; ?>"><?php echo $sisaticketnya[0]['ticket_allocation']; ?></option>
													</select>
													<i style="font-size: 12px;">Jumlah Ticket</i>
												</div>
											<?php } ?>
										<?php } ?>
										<?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') { ?>
											<div class="col-sm-4">
												<input type="text" class="form-control form-control-sm" id="ticket_allocation" name="ticket_allocation" <?php echo "disabled"; ?> value="<?php echo $ddata16['ticket_allocation']; ?>">
												<i style="font-size: 12px;">Jumlah Ticket</i>
											</div>
										<?php } ?>
										<?php if ($_GET['act'] == 'edit') {  ?>
											<div class="col-sm-4">
												<input type="text" class="form-control form-control-sm" id="ticket_allocation" name="ticket_allocation" value="<?php echo $ddata16['ticket_allocation']; ?>">
												<i style="font-size: 12px;">Jumlah Ticket</i>
											</div>
										<?php } ?>
									<?php } ?>
								</div>
								<?php if ($_GET['type'] == "Maintenance" || $_GET['type'] == "Implementation") { ?>
									<div class="row mb-3">
										<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Apakah Perlu Barang Backup? <b>*</b></label>
										<div class="col-sm-9">
											<?php if ($_GET['act'] == "add") { ?>
												<select class="form-control" name="perlu_backup" id="perlu_backup" required>
													<option></option>
													<option value="Ya">Ya</option>
													<option value="Tidak">Tidak</option>
												</select>
											<?php } ?>
											<?php if ($_GET['act'] == "edit" && $ddata['perlu_backup'] == "Tidak" || $_GET['act'] == "edit" && $ddata['perlu_backup'] == null) { ?>
												<select class="form-control" name="perlu_backup" id="perlu_backup">
													<option><?php echo ucwords($ddata['perlu_backup']); ?></option>
													<option value="Ya">Ya</option>
													<option value="Tidak">Tidak</option>
												</select>
											<?php } else if ($_GET['act'] == "edit" && $ddata['perlu_backup'] == "Ya") { ?>
												<select class="form-control" name="perlu_backup" id="perlu_backup" readonly>
													<option><?php echo ucwords($ddata['perlu_backup']); ?></option>
												</select>
											<?php } ?>
											<?php if ($_GET['act'] == "review") { ?>
												<input type="text" class="form-control form-control-sm" id="perlu_backup" name="perlu_backup" value="<?php echo ucwords($ddata['perlu_backup']); ?>" readonly>
											<?php } ?>
										</div>
									</div>
									<div class="row mb-3" id="sn_backup_id">
										<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Alamat Customer (Untuk Pengiriman Backup) *</label>
										<div class="col-sm-9">
											<?php if ($_GET['act'] == "add") { ?>
												<textarea class="form-control" name="alamat_backup" id="alamat_backup" required>Wajib diisi</textarea>
											<?php } ?>
											<?php if ($_GET['act'] == "edit" && $ddata['perlu_backup'] == "Ya") { ?>
												<textarea class="form-control" name="alamat_backup" id="alamat_backup" value="<?php echo $ddata0['alamat_backup']; ?>" readonly><?php echo $ddata0['alamat_backup']; ?></textarea>
											<?php } else if ($_GET['act'] == "edit" && $ddata['perlu_backup'] == "Tidak" || $_GET['act'] == "edit" && $ddata['perlu_backup'] == NULL) { ?>
												<textarea class="form-control" name="alamat_backup" id="alamat_backup"></textarea>
											<?php } ?>
											<?php if ($_GET['act'] == "review") { ?>
												<input type="text" class="form-control form-control-sm" id="alamat_backup" name="alamat_backup" value="<?php $var = isset($ddata0['alamat_backup']);
																																						if ($var == NULL) {
																																							echo '';
																																						} else {
																																							echo $ddata0['alamat_backup'];
																																						} ?>" readonly>
											<?php } ?>
										</div>
									</div>
									<div class="row mb-3" id="pic_backup_id">
										<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Nama PIC User (Untuk Pengiriman Backup) *</label>
										<div class="col-sm-9">
											<?php if ($_GET['act'] == "add") { ?>
												<textarea class="form-control" name="pic_backup" id="pic_backup" required>Wajib diisi</textarea>
											<?php } ?>
											<?php if ($_GET['act'] == "edit" && $ddata['perlu_backup'] == "Ya") { ?>
												<input class="form-control" name="pic_backup" id="pic_backup" value="<?php echo $ddata0['pic_backup']; ?>" readonly>
											<?php } else if ($_GET['act'] == "edit" && $ddata['perlu_backup'] == "Tidak" || $_GET['act'] == "edit" && $ddata['perlu_backup'] == NULL) { ?>
												<input class="form-control" name="pic_backup" id="pic_backup">
											<?php } ?>
											<?php if ($_GET['act'] == "review") { ?>
												<input type="text" class="form-control form-control-sm" id="pic_backup" name="pic_backup" value="<?php $var = isset($ddata0['pic_backup']);
																																					if ($var == NULL) {
																																						echo '';
																																					} else {
																																						echo $ddata0['pic_backup'];
																																					} ?>" readonly>
											<?php } ?>
										</div>
									</div>
									<div class="row mb-3" id="pn_backup_id">
										<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Part Number Backup *</label>
										<div class="col-sm-9">
											<?php if ($_GET['act'] == "add") { ?>
												<textarea class="form-control" name="pn_backup" id="pn_backup" required>Wajib diisi</textarea>
											<?php } ?>
											<?php if ($_GET['act'] == "edit" && $ddata['perlu_backup'] == "Ya") { ?>
												<textarea class="form-control" name="pn_backup" id="pn_backup" value="<?php echo ucwords($ddata0['pn_backup']); ?>" readonly><?php echo ucwords($ddata0['pn_backup']); ?></textarea>
											<?php } else if ($_GET['act'] == "edit" && $ddata['perlu_backup'] == "Tidak" || $_GET['act'] == "edit" && $ddata['perlu_backup'] == NULL) { ?>
												<textarea class="form-control" name="pn_backup" id="pn_backup"></textarea>
											<?php } ?>
											<?php if ($_GET['act'] == "review") { ?>
												<input type="text" class="form-control form-control-sm" id="pn_backup" name="pn_backup" value="<?php $var = isset($ddata0['pn_backup']);
																																				if ($var == NULL) {
																																					echo '';
																																				} else {
																																					echo $ddata0['pn_backup'];
																																				} ?>" readonly>
											<?php } ?>
										</div>
									</div>
									<div class="row mb-3" id="deskripsi_backup_id" style="display:none;">
										<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Deskripsi Barang Backup</label>
										<div class="col-sm-9">
											<?php if ($_GET['act'] == "add") { ?>
												<textarea class="form-control" name="barang_backup" id="barang_backup"></textarea>
											<?php } ?>
											<?php if ($_GET['act'] == "edit" && $ddata['perlu_backup'] == "Ya") { ?>
												<textarea class="form-control" name="barang_backup" id="barang_backup" value="<?php echo ucwords($ddata0['barang_backup']); ?>" readonly><?php echo ucwords($ddata0['barang_backup']); ?></textarea>
											<?php } else if ($_GET['act'] == "edit" && $ddata['perlu_backup'] == "Tidak" || $_GET['act'] == "edit" &&  $ddata['perlu_backup'] == NULL) { ?>
												<textarea class="form-control" name="barang_backup" id="barang_backup"></textarea>
											<?php } ?>
											<?php if ($_GET['act'] == "review") { ?>
												<input type="text" class="form-control form-control-sm" id="barang_backup" name="barang_backup" value="<?php $var = isset($ddata0['barang_backup']);
																																						if ($var == NULL) {
																																							echo '';
																																						} else {
																																							echo $ddata0['barang_backup'];
																																						} ?>" readonly>
											<?php } ?>
										</div>
									</div>
								<?php } ?>
								<?php if ($_GET['type'] == "Sales/Presales") { ?>
									<div class="col-lg-12">
										<div class="row mb-3" id="xaxa">
											<label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Project Manager</label>
											<div class="col-sm-8">
												<?php if ($_GET['act'] == "add") { ?>
													<select class="form-control" name="project_manager" id="project_manager">
														<option value="<?php echo $_SESSION['Microservices_UserEmail'] ?>"><?php echo $_SESSION['Microservices_UserName'] ?></option>
														<?php do { ?>
															<option><?php echo $project_manager[0]['employee_name']; ?></option>
														<?php } while ($project_manager[0] = $project_manager[1]->fetch_assoc()); ?>
													</select>
												<?php } ?>
												<?php if ($_GET['act'] == "edit") { ?>
													<select class="form-control" name="project_manager" id="project_manager">
														<option><?php echo $ddata['project_manager']; ?></option>
														<?php do { ?>
															<option><?php echo $project_manager[0]['employee_name']; ?></option>
														<?php } while ($project_manager[0] = $project_manager[1]->fetch_assoc()); ?>
													</select>
												<?php } ?>
												<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
													<input type="text" class="form-control form-control-sm" id="project_manager" name="project_manager" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																							echo "disabled";
																																						} ?> value="<?php echo $ddata['project_manager']; ?>">
												<?php } ?>
											</div>
										</div>
										<div class="row mb-3">
											<label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Request Date</label>
											<div class="col-sm-8">
												<?php if ($_GET['act'] == "add" || $_GET['act'] == "edit") { ?>
													<select name="request_date" id="request_date" class="form-control">
														<option name="request_date" id="request_date" value="<?php echo date("Y-m-d"); ?>"><?php echo date("Y-m-d"); ?></option>
													</select>
												<?php } else if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') { ?>
													<input type="date" class="form-control form-control-sm" id="request_date" name="request_date" value="<?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																								echo $ddata['request_date'];
																																							} ?>" disabled>
												<?php } ?>
											</div>
										</div>
										<div class="row mb-3">
											<label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Requested By Email</label>
											<div class="col-sm-8">
												<?php if ($_GET['act'] == 'review') { ?>
													<input type="text" class="form-control form-control-sm" id="requested_by" name="requested_by" value="<?php echo $ddata['requested_by_email']; ?>" readonly>
												<?php } elseif ($_GET['act'] == 'add') { ?>
													<select class="form-control" name="requested_by" id="requested_by">
														<option value="<?php echo $_SESSION['Microservices_UserEmail']; ?>"><?php echo $_SESSION['Microservices_UserEmail']; ?></option>
													</select>
												<?php } elseif ($_GET['act'] == 'edit') { ?>
													<select class="form-control" name="requested_by" id="requested_by">
														<option value="<?php echo $ddata['requested_by_email']; ?>"><?php echo $ddata['requested_by_email']; ?></option>
													</select>
												<?php } ?>
											</div>
										</div>
									</div>
								<?php } ?>
								<?php if ($_GET['type'] == "Maintenance" || $_GET['type'] == "IT" || $_GET['type'] == "Implementation") { ?>
									<div class="row mb-3">
										<!-- <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm" id="Affected_ci">Affected CI</label> -->
										<div class="col-sm-12">
											<?php
											if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
												$sn = isset($data15[0]['serial_number']);
												if ($sn == null) {
													'';
												} else {
											?>
													<table class="table">
														<thead>
															<tr>
																<th scope="col">#</th>
																<th scope="col">Serial Number</th>
																<th scope="col">Part Number</th>
															</tr>
														</thead>
														<tbody>
															<?php
															do {
																$j = 1;
																$sn = isset($data15[0]['serial_number']);
																if ($sn == null) {
																	'';
																} else {
																	$sn = $data15[0]['serial_number'];
																}
																$pn = isset($data15[0]['part_number']);
																if ($pn == null) {
																	'';
																} else {
																	$pn = $data15[0]['part_number'];
																}
																echo "<tr><td>$j</td><td>$sn</td><td>$pn</td></tr>";
																$j++;
																//     //echo $user[0]['email'] . "|" . $user[0]['name'];
																//echo $user[0]['name'];
															} while ($data15[0] = $data15[1]->fetch_assoc());
															?>
														</tbody>
													</table>
												<?php } ?>
											<?php } elseif ($_GET['act'] == 'add') { ?>
												<div class="row mb-3">
													<div class="row">
														<div class="col-12 table-bordered">Affected CI</div>
													</div>
													<div class="row">
														<div class="col-6 table-bordered">Input Serial Number</div>
														<div class="col-6 table-bordered">Input Part Number</div>
													</div>
													<div id="newRow-affected-ci"></div>
													<button id="addRow-affected-ci" type="button" class="btn btn-info col-12">+</button>
												</div>
											<?php } elseif ($_GET['act'] == 'edit') { ?>
												<?php $valCI = isset($data15[0]['serial_number']) ?>
												<?php if ($valCI == null) { ?>
													<div class="row mb-3">
														<div class="row">
															<div class="col-12 table-bordered">Affected CI</div>
														</div>
														<div class="row">
															<div class="col-6 table-bordered">Input Serial Number</div>
															<div class="col-6 table-bordered">Input Part Number</div>
														</div>
														<div id="newRow-affected-ci"></div>
														<button id="addRow-affected-ci" type="button" class="btn btn-info col-12">+</button>
													</div>
												<?php } else { ?>
													<div class="row mb-3">
														<div class="row">
															<div class="col-12 table-bordered">Affected CI</div>
														</div>
														<div class="row">
															<div class="col-4 table-bordered">ID</div>
															<div class="col-4 table-bordered">Input Serial Number</div>
															<div class="col-4 table-bordered">Input Part Number</div>
														</div>
														<?php do { ?>
															<div class="p-0" id="inputFormRow-affected-ci">
																<div class="row">
																	<div class="col-4 table-bordered"><input type="text" class="form-control" name="ac_id[]" id="ac_id[]" value="<?php echo $data15[0]['ac_id']; ?>"></div>
																	<div class="col-4 table-bordered"><textarea class="form-control" name="serial_number[]" id="serial_number[]" rows="2" value="<?php echo $data15[0]['serial_number']; ?>"><?php echo $data15[0]['serial_number']; ?></textarea></div>
																	<div class="col-4 table-bordered"><textarea class="form-control" name="part_number[]" id="part_number[]" rows="2" value="<?php echo $data15[0]['part_number']; ?>"><?php echo $data15[0]['part_number']; ?></textarea></div>
																</div>
																<!-- <div id="newnewRow-affected-ci" name="newnewRow-affected-ci"></div> -->
															</div>
														<?php } while ($data15[0] = $data15[1]->fetch_assoc()); ?>
													</div>
													<!-- <button id="editRow-affected-ci" name="editRow-affected-ci" type="button" class="btn btn-info">+</button> -->
											<?php }
											} ?>
										</div>
									</div>
								<?php } ?>
							</div>
						<?php
						} ?>
					</div>
				</div>
			</div>



		</div>

		<?php if (isset($_GET['project_code'])) {
			if ($_GET['project_code'] == null) {
				"";
			} else {
		?>
				<!-- TAB Assesment -->
				<div class="tab-pane fade show" id="assesmentx" role="tabpanel" aria-labelledby="crtype-tab">
					<div class="card shadow mb-4">
						<!-- Card Body -->
						<div class="card-body">
							<?php if ($_GET['type'] == "Implementation" || $_GET['type'] == "Maintenance" || $_GET['type'] == "IT") { ?> <h2>Assesments</h2> <?php } ?>
							<?php // if ($_GET['type'] == "Sales/Presales") { 
							?><?php //} 
								?>
							<div class="row">
								<?php if ($_GET['type'] == "Sales/Presales") { ?>
									<div class="row mb-3">
										<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm"><b>Schedule Date</b></label>
										<div class="col-sm-9">
											<input type="date" class="form-control form-control-sm" id="schedule" name="schedule" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																		echo "disabled";
																																	} ?> value="<?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																					echo date('Y-m-d', strtotime($data1[0]['schedule']));
																																				} ?>">
										</div>
									</div>
									<?php if ($_GET['act'] == "add") { ?>
										<div class="col-lg-12">
											<div class="row mb-3">
												<div class="row">
													<div class="col-12 table-bordered"><b>Detail of Change</b></div>
												</div>
												<div class="row">
													<div class="col-4 table-bordered"><b>Detail Item</b></div>
													<div class="col-4 table-bordered"><b>Item yang diubah</b></div>
													<div class="col-4 table-bordered"><b>Perubahannya menjadi</b></div>
												</div>
												<div id="newRow-detailchange"></div>
												<button id="addRow-detailchange" type="button" class="btn btn-info col-12">+</button>
											</div>
										</div>
									<?php } ?>
									<?php if ($_GET['act'] == "edit") { ?>
										<div class="col-lg-12">
											<?php $val = isset($data1[0]['detail']); ?>
											<?php if ($val == null) { ?>
												<div class="row mb-3">
													<div class="row">
														<div class="col-12 table-bordered"><b>Detail of Change</b></div>
													</div>
													<div class="row">
														<div class="col-4 table-bordered"><b>Detail Item</b></div>
														<div class="col-4 table-bordered"><b>Item yang diubah</b></div>
														<div class="col-4 table-bordered"><b>Perubahannya menjadi</b></div>
													</div>
													<div id="newRow-detailchange"></div>
													<button id="addRow-detailchange" type="button" class="btn btn-info col-12">+</button>
												</div>
											<?php } else { ?>
												<div class="row mb-3">
													<div class="row">
														<div class="col-12 table-bordered"><b>Detail of Change</b></div>
													</div>
													<div class="row">
														<div class="col-3 table-bordered"><b>No ID</b></div>
														<div class="col-3 table-bordered"><b>Detail Item</b></div>
														<div class="col-3 table-bordered"><b>Item yang diubah</b></div>
														<div class="col-3 table-bordered"><b>Perubahannya menjadi</b></div>
													</div>
													<?php do { ?>
														<div class="p-0" id="inputFormRow-mandays">
															<div class="row">
																<div class="col-3 table-bordered">
																	<select class="form-control" name="no[]" id="no[]">
																		<option value="<?php echo $data1[0]['no']; ?>"><?php echo $data1[0]['no']; ?></option>
																	</select>
																</div>
																<div class="col-3 table-bordered"><textarea class="form-control" name="detail[]" id="detail[]" value="<?php echo $data1[0]['detail']; ?>"><?php echo $data1[0]['detail']; ?></textarea>
																</div>
																<div class="col-3 table-bordered"><textarea class="form-control" name="item[]" id="item[]" value="<?php echo $data1[0]['item']; ?>"><?php echo $data1[0]['item']; ?></textarea></div>
																<div class="col-3 table-bordered"><textarea class="form-control" name="perubahan[]" id="perubahan[]" value="<?php echo $data1[0]['perubahan']; ?>"><?php echo $data1[0]['perubahan']; ?></textarea></div>
															</div>
														</div>
													<?php } while ($data1[0] = $data1[1]->fetch_assoc()); ?>
												</div>
											<?php } ?>
										</div>
									<?php } ?>
									<?php if ($_GET['act'] == "review") { ?>
										<div class="col-lg-12">
											<?php $mantor = isset($data1[0]['detail']); ?>
											<?php if ($mantor == null) { ?>
												<?php echo ""; ?>
											<?php } else { ?>
												<div class="row mb-3">
													<div class="row">
														<div class="col-12 table-bordered"><b>Detail of Change</b></div>
													</div>
													<div class="row">
														<div class="col-3 table-bordered"><b>No ID</b></div>
														<div class="col-3 table-bordered"><b>Detail Item</b></div>
														<div class="col-3 table-bordered"><b>Item yang diubah</b></div>
														<div class="col-3 table-bordered"><b>Perubahannya menjadi</b></div>
													</div>
													<?php do { ?>
														<div class="p-0" id="inputFormRow-mandays">
															<div class="row">
																<div class="col-3 table-bordered">
																	<select class="form-control" name="no[]" id="no[]">
																		<option value="<?php echo $data1[0]['no']; ?>"><?php echo $data1[0]['no']; ?></option>
																	</select>
																</div>
																<div class="col-3 table-bordered"><textarea class="form-control" name="detail[]" id="detail[]" value="<?php echo $data1[0]['detail']; ?>" readonly><?php echo $data1[0]['detail']; ?></textarea></div>
																<div class="col-3 table-bordered"><textarea class="form-control" name="item[]" id="item[]" value="<?php echo $data1[0]['item']; ?>" readonly><?php echo $data1[0]['item']; ?></textarea></div>
																<div class="col-3 table-bordered"><textarea class="form-control" name="perubahan[]" id="perubahan[]" value="<?php echo $data1[0]['perubahan']; ?>" readonly><?php echo $data1[0]['perubahan']; ?></textarea></div>
															</div>
														</div>
													<?php } while ($data1[0] = $data1[1]->fetch_assoc()); ?>
												</div>
											<?php } ?>
										</div>
									<?php } ?>
								<?php } ?>

								<?php if ($_GET['act'] == "add" && $_GET['type'] == "Implementation") { ?>
									<div class="row mb-3 col-12">
										<div class="row">
											<div class="col-3 table-bordered"><b>Item Assesment</b></div>
											<div class="col-3 table-bordered"><b>Deskripsi</b></div>
											<div class="col-3 table-bordered"><b>PIC (Internal)</b></div>
											<div class="col-3 table-bordered"><b>PIC (Eksternal)</b></div>
										</div>
										<div class="row">
											<div class="col-3 table-bordered"><b>Risk Assesment</b></div>
											<div class="col-3 table-bordered"><textarea class="form-control" name="riskassesment" id="riskassesment" rows="1" cols="20" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																											echo "disabled";
																																										} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																													echo $ddata2['riskassesment'];
																																												} ?></textarea></div>
											<?php if ($_GET['type'] == "Implementation") { ?>
												<div class="col-3 table-bordered">
													<select class="form-control" name="risk_pic" id="risk_pic">
														<option></option>
														<?php do { ?>
															<option value="<?php echo $pic_ra_imp[0]['employee_email']; ?>"><?php echo $pic_ra_imp[0]['employee_name']; ?></option>
														<?php } while ($pic_ra_imp[0] = $pic_ra_imp[1]->fetch_assoc()); ?>
													</select>
												</div>
											<?php } elseif ($_GET['type'] == "IT") { ?>
												<div class="col-3 table-bordered">
													<select class="form-control" name="risk_pic" id="risk_pic">
														<option></option>
														<?php do { ?>
															<option value="<?php echo $pic_ra_it[0]['employee_email']; ?>"><?php echo $pic_ra_it[0]['employee_name']; ?></option>
														<?php } while ($pic_ra_it[0] = $pic_ra_it[1]->fetch_assoc()); ?>
													</select>
												</div>
											<?php } ?>
											<div class="col-3 table-bordered"><input class="form-control" type="text" name="pic_ket_ra" id="pic_ket_ra"></div>
										</div>
										<div class="row mb-3">
											<div class="col-3 table-bordered"><b>Technical Assesment</b></div>
											<div class="col-3 table-bordered"><textarea class="form-control" name="technical_assesment" id="technical_assesment" rows="1" cols="20" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																														echo "disabled";
																																													} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																echo $ddata2['technical_assesment'];
																																															} ?></textarea>
											</div>
											<div class="col-3 table-bordered">
												<select class="form-control" name="ta_pic" id="ta_pic">
													<option></option>
													<?php do { ?>
														<option value="<?php echo $pic_ta[0]['employee_email']; ?>"><?php echo $pic_ta[0]['employee_name']; ?></option>
													<?php } while ($pic_ta[0] = $pic_ta[1]->fetch_assoc()); ?>
												</select>
											</div>
											<div class="col-3 table-bordered"><input class="form-control" type="text" name="pic_ket_ta" id="pic_ket_ta"></div>
										</div>
										<div class="row mb-3">
											<div class="col-3 table-bordered"><b>Business Benefit</b></div>
											<div class="col-3 table-bordered"><textarea class="form-control" name="business_benefit" id="business_benefit" rows="1" COLS="20" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																													echo "disabled";
																																												} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																															echo $ddata2['business_benefit'];
																																														} ?></textarea>
											</div>
											<div class="col-3 table-bordered">
												<select class="form-control" name="bb_pic" id="bb_pic">
													<option></option>
													<?php do { ?>
														<option value="<?php echo $pic_bb[0]['employee_email']; ?>"><?php echo $pic_bb[0]['employee_name']; ?></option>
													<?php } while ($pic_bb[0] = $pic_bb[1]->fetch_assoc()); ?>
												</select>
											</div>
											<div class="col-3 table-bordered"><input class="form-control" type="text" name="pic_ket_bb" id="pic_ket_bb"></div>
										</div>
									</div>
								<?php } ?>
								<?php if ($_GET['act'] == "add" && $_GET['type'] == "IT") { ?>
									<div class="row mb-3 col-12">
										<div class="row">
											<div class="col-3 table-bordered"><b>Item Assesment</b></div>
											<div class="col-3 table-bordered"><b>Deskripsi</b></div>
											<div class="col-3 table-bordered"><b>PIC (Internal)</b></div>
											<div class="col-3 table-bordered"><b>PIC (Eksternal)</b></div>
										</div>
										<div class="row">
											<div class="col-3 table-bordered"><b>Risk Assesment</b></div>
											<div class="col-3 table-bordered"><textarea class="form-control" name="riskassesment" id="riskassesment" rows="1" cols="20" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																											echo "disabled";
																																										} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																													echo $ddata2['riskassesment'];
																																												} ?></textarea></div>
											<div class="col-3 table-bordered">
												<select class="form-control" name="risk_pic" id="risk_pic">
													<option></option>
													<?php do { ?>
														<option value="<?php echo $pic_ra_it[0]['employee_email']; ?>"><?php echo $pic_ra_it[0]['employee_name']; ?></option>
													<?php } while ($pic_ra_it[0] = $pic_ra_it[1]->fetch_assoc()); ?>
												</select>
											</div>
											<div class="col-3 table-bordered"><input class="form-control" type="text" name="pic_ket_ra" id="pic_ket_ra"></div>
										</div>
										<div class="row mb-3">
											<div class="col-3 table-bordered"><b>Technical Assesment</b></div>
											<div class="col-3 table-bordered"><textarea class="form-control" name="technical_assesment" id="technical_assesment" rows="1" cols="20" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																														echo "disabled";
																																													} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																echo $ddata2['technical_assesment'];
																																															} ?></textarea>
											</div>
											<div class="col-3 table-bordered">
												<select class="form-control" name="ta_pic" id="ta_pic">
													<option></option>
													<?php do { ?>
														<option value="<?php echo $pic_ta[0]['employee_email']; ?>"><?php echo $pic_ta[0]['employee_name']; ?></option>
													<?php } while ($pic_ta[0] = $pic_ta[1]->fetch_assoc()); ?>
												</select>
											</div>
											<div class="col-3 table-bordered"><input class="form-control" type="text" name="pic_ket_ta" id="pic_ket_ta"></div>
										</div>
										<div class="row mb-3">
											<div class="col-3 table-bordered"><b>Business Benefit</b></div>
											<div class="col-3 table-bordered"><textarea class="form-control" name="business_benefit" id="business_benefit" rows="1" COLS="20" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																													echo "disabled";
																																												} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																															echo $ddata2['business_benefit'];
																																														} ?></textarea>
											</div>
											<div class="col-3 table-bordered">
												<select class="form-control" name="bb_pic" id="bb_pic">
													<option></option>
													<?php do { ?>
														<option value="<?php echo $pic_bb[0]['employee_email']; ?>"><?php echo $pic_bb[0]['employee_name']; ?></option>
													<?php } while ($pic_bb[0] = $pic_bb[1]->fetch_assoc()); ?>
												</select>
											</div>
											<div class="col-3 table-bordered"><input class="form-control" type="text" name="pic_ket_bb" id="pic_ket_bb"></div>
										</div>
									</div>
								<?php } ?>
								<?php if ($_GET['act'] == "edit" && $_GET['type'] == "Implementation") { ?>
									<div class="row mb-3 col-12">
										<div class="row">
											<div class="col-3 table-bordered"><b>Item Assesment</b></div>
											<div class="col-3 table-bordered"><b>Deskripsi</b></div>
											<div class="col-3 table-bordered"><b>PIC (Internal)</b></div>
											<div class="col-3 table-bordered"><b>PIC (Eksternal)</b></div>
										</div>
										<div class="row">
											<div class="col-3 table-bordered"><b>Risk Assesment</b></div>
											<div class="col-3 table-bordered"><textarea class="form-control" name="riskassesment" id="riskassesment" rows="1" cols="20" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																											echo "disabled";
																																										} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																													echo $ddata2['riskassesment'];
																																												} ?></textarea></div>
											<?php if ($_GET['type'] == "Implementation") { ?>
												<div class="col-3 table-bordered">
													<select class="form-control" name="risk_pic" id="risk_pic">
														<option value="<?php echo $ddata2['risk_pic']; ?>"><?php echo $ddata2['risk_pic']; ?></option> <?php do { ?>
															<option value="<?php echo $pic_ra_imp[0]['employee_email']; ?>"><?php echo $pic_ra_imp[0]['employee_name']; ?></option>
														<?php } while ($pic_ra_imp[0] = $pic_ra_imp[1]->fetch_assoc()); ?>
													</select>
												</div>
											<?php } ?>
											<div class="col-3 table-bordered"><input class="form-control" type="text" name="pic_ket_ra" id="pic_ket_ra" value="<?php echo $ddata2['pic_ket_ra']; ?>"></div>
										</div>
										<div class="row mb-3">
											<div class="col-3 table-bordered"><b>Technical Assesment</b></div>
											<div class="col-3 table-bordered"><textarea class="form-control" name="technical_assesment" id="technical_assesment" rows="1" cols="20" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																														echo "disabled";
																																													} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																echo $ddata2['technical_assesment'];
																																															} ?></textarea>
											</div>
											<div class="col-3 table-bordered">
												<select class="form-control" name="ta_pic" id="ta_pic">
													<option value="<?php echo $ddata2['technical_assesment_pic']; ?>"><?php echo $ddata2['technical_assesment_pic']; ?></option>
													<?php do { ?>
														<option value="<?php echo $pic_ta[0]['employee_email']; ?>"><?php echo $pic_ta[0]['employee_name']; ?></option>
													<?php } while ($pic_ta[0] = $pic_ta[1]->fetch_assoc()); ?>
												</select>
											</div>
											<div class="col-3 table-bordered"><input class="form-control" type="text" name="pic_ket_ta" id="pic_ket_ta" value="<?php echo $ddata2['pic_ket_ta']; ?>"></div>
										</div>
										<div class="row mb-3">
											<div class="col-3 table-bordered"><b>Business Benefit</b></div>
											<div class="col-3 table-bordered"><textarea class="form-control" name="business_benefit" id="business_benefit" rows="1" COLS="20" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																													echo "disabled";
																																												} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																															echo $ddata2['business_benefit'];
																																														} ?></textarea>
											</div>
											<div class="col-3 table-bordered">
												<select class="form-control" name="bb_pic" id="bb_pic">
													<option value="<?php echo $ddata2['business_benefit_pic']; ?>"><?php echo $ddata2['business_benefit_pic']; ?></option>
													<?php do { ?>
														<option value="<?php echo $pic_bb[0]['employee_email']; ?>"><?php echo $pic_bb[0]['employee_name']; ?></option>
													<?php } while ($pic_bb[0] = $pic_bb[1]->fetch_assoc()); ?>
												</select>
											</div>
											<div class="col-3 table-bordered"><input class="form-control" type="text" name="pic_ket_bb" id="pic_ket_bb" value="<?php echo $ddata2['pic_ket_bb']; ?>"></div>
										</div>
									</div>
								<?php } ?>
								<?php if ($_GET['act'] == "edit" && $_GET['type'] == "IT") { ?>
									<div class="row mb-3 col-12">
										<div class="row">
											<div class="col-3 table-bordered"><b>Item Assesment</b></div>
											<div class="col-3 table-bordered"><b>Deskripsi</b></div>
											<div class="col-3 table-bordered"><b>PIC (Internal)</b></div>
											<div class="col-3 table-bordered"><b>PIC (Eksternal)</b></div>
										</div>
										<div class="row">
											<div class="col-3 table-bordered"><b>Risk Assesment</b></div>
											<div class="col-3 table-bordered"><textarea class="form-control" name="riskassesment" id="riskassesment" rows="1" cols="20" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																											echo "disabled";
																																										} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																													echo $ddata2['riskassesment'];
																																												} ?></textarea></div>
											<div class="col-3 table-bordered">
												<select class="form-control" name="risk_pic" id="risk_pic">
													<option value="<?php echo $data2[0]['risk_pic']; ?>"><?php echo $data2[0]['risk_pic']; ?></option>
													<?php do { ?>
														<option value="<?php echo $pic_ra_it[0]['employee_email']; ?>"><?php echo $pic_ra_it[0]['employee_name']; ?></option>
													<?php } while ($pic_ra_it[0] = $pic_ra_it[1]->fetch_assoc()); ?>
												</select>
											</div>
											<div class="col-3 table-bordered"><input class="form-control" type="text" name="pic_ket_ra" id="pic_ket_ra" value="<?php echo $ddata2['pic_ket_ra']; ?>"></div>
										</div>
										<div class="row mb-3">
											<div class="col-3 table-bordered"><b>Technical Assesment</b></div>
											<div class="col-3 table-bordered"><textarea class="form-control" name="technical_assesment" id="technical_assesment" rows="1" cols="20" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																														echo "disabled";
																																													} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																echo $ddata2['technical_assesment'];
																																															} ?></textarea>
											</div>
											<div class="col-3 table-bordered">
												<select class="form-control" name="ta_pic" id="ta_pic">
													<option value="<?php echo $ddata2['technical_assesment_pic']; ?>"><?php echo $ddata2['technical_assesment_pic']; ?></option>
													<?php do { ?>
														<option value="<?php echo $pic_ta[0]['employee_email']; ?>"><?php echo $pic_ta[0]['employee_name']; ?></option>
													<?php } while ($pic_ta[0] = $pic_ta[1]->fetch_assoc()); ?>
												</select>
											</div>
											<div class="col-3 table-bordered"><input class="form-control" type="text" name="pic_ket_ta" id="pic_ket_ta" value="<?php echo $ddata2['pic_ket_ta']; ?>"></div>
										</div>
										<div class="row mb-3">
											<div class="col-3 table-bordered"><b>Business Benefit</b></div>
											<div class="col-3 table-bordered"><textarea class="form-control" name="business_benefit" id="business_benefit" rows="1" COLS="20" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																													echo "disabled";
																																												} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																															echo $ddata2['business_benefit'];
																																														} ?></textarea>
											</div>
											<div class="col-3 table-bordered">
												<select class="form-control" name="bb_pic" id="bb_pic">
													<option value="<?php echo $ddata2['business_benefit_pic']; ?>"><?php echo $ddata2['business_benefit_pic']; ?></option>
													<?php do { ?>
														<option value="<?php echo $pic_bb[0]['employee_email']; ?>"><?php echo $pic_bb[0]['employee_name']; ?></option>
													<?php } while ($pic_bb[0] = $pic_bb[1]->fetch_assoc()); ?>
												</select>
											</div>
											<div class="col-3 table-bordered"><input class="form-control" type="text" name="pic_ket_bb" id="pic_ket_bb" value="<?php echo $ddata2['pic_ket_bb']; ?>"></div>
										</div>
									</div>
								<?php } ?>
								<?php if ($_GET['act'] == "review" && $_GET['type'] == "Implementation") { ?>
									<div class="row mb-3 col-12">
										<div class="row">
											<div class="col-3 table-bordered"><b>Item Assesment</b></div>
											<div class="col-3 table-bordered"><b>Deskripsi</b></div>
											<div class="col-3 table-bordered"><b>PIC (Internal)</b></div>
											<div class="col-3 table-bordered"><b>PIC (Eksternal)</b></div>
										</div>
										<div class="row">
											<div class="col-3 table-bordered"><b>Risk Assesment</b></div>
											<div class="col-3 table-bordered"><textarea class="form-control" name="riskassesment" id="riskassesment" rows="1" cols="20" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																											echo "disabled";
																																										} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																													echo $ddata2['riskassesment'];
																																												} ?></textarea></div>
											<?php if ($_GET['type'] == "Implementation") { ?>
												<div class="col-3 table-bordered">
													<select class="form-control" name="risk_pic" id="risk_pic" <?php echo "disabled"; ?>>
														<option value="<?php echo $ddata2['risk_pic']; ?>"><?php echo $ddata2['risk_pic']; ?></option>
													</select>
												</div>
											<?php } elseif ($_GET['type'] == "IT") { ?>
												<div class="col-3 table-bordered">
													<select class="form-control" name="risk_pic" id="risk_pic" <?php echo "disabled"; ?>>
														<option value="<?php echo $ddata2['risk_pic']; ?>"><?php echo $ddata2['risk_pic']; ?></option>
													</select>
												</div>
											<?php } ?>
											<div class="col-3 table-bordered"><input class="form-control" type="text" name="pic_ket_ra" id="pic_ket_ra" <?php echo "disabled"; ?> value="<?php echo $ddata2['pic_ket_ra']; ?>"></div>
										</div>
										<div class="row mb-3">
											<div class="col-3 table-bordered"><b>Technical Assesment</b></div>
											<div class="col-3 table-bordered"><textarea class="form-control" name="technical_assesment" id="technical_assesment" rows="1" cols="20" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																														echo "disabled";
																																													} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																echo $ddata2['technical_assesment'];
																																															} ?></textarea>
											</div>
											<div class="col-3 table-bordered">
												<select class="form-control" name="ta_pic" id="ta_pic" <?php echo "disabled"; ?>>
													<option value="<?php echo $ddata2['technical_assesment_pic']; ?>"><?php echo $ddata2['technical_assesment_pic']; ?></option>
												</select>
											</div>
											<div class="col-3 table-bordered"><input class="form-control" type="text" name="pic_ket_ta" id="pic_ket_ta" <?php echo "disabled"; ?> value="<?php echo $ddata2['pic_ket_ta']; ?>"></div>
										</div>
										<div class="row mb-3">
											<div class="col-3 table-bordered"><b>Business Benefit</b></div>
											<div class="col-3 table-bordered"><textarea class="form-control" name="business_benefit" id="business_benefit" rows="1" COLS="20" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																													echo "disabled";
																																												} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																															echo $ddata2['business_benefit'];
																																														} ?></textarea>
											</div>
											<div class="col-3 table-bordered">
												<select class="form-control" name="bb_pic" id="bb_pic" <?php echo "disabled"; ?>>
													<option value="<?php echo $ddata2['business_benefit_pic']; ?>"><?php echo $ddata2['business_benefit_pic']; ?></option>
												</select>
											</div>
											<div class="col-3 table-bordered"><input class="form-control" type="text" name="pic_ket_bb" id="pic_ket_bb" <?php echo "disabled"; ?> value="<?php echo $ddata2['pic_ket_bb']; ?>"></div>
										</div>
									</div>
								<?php } ?>
								<?php if ($_GET['act'] == "review" && $_GET['type'] == "IT") { ?>
									<div class="row mb-3 col-12">
										<div class="row">
											<div class="col-3 table-bordered"><b>Item Assesment</b></div>
											<div class="col-3 table-bordered"><b>Deskripsi</b></div>
											<div class="col-3 table-bordered"><b>PIC (Internal)</b></div>
											<div class="col-3 table-bordered"><b>PIC (Eksternal)</b></div>
										</div>
										<div class="row">
											<div class="col-3 table-bordered"><b>Risk Assesment</b></div>
											<div class="col-3 table-bordered"><textarea class="form-control" name="riskassesment" id="riskassesment" rows="1" cols="20" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																											echo "disabled";
																																										} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																													echo $ddata2['riskassesment'];
																																												} ?></textarea></div>
											<?php if ($_GET['type'] == "Implementation") { ?>
												<div class="col-3 table-bordered">
													<select class="form-control" name="risk_pic" id="risk_pic" <?php echo "disabled"; ?>>
														<option value="<?php echo $ddata2['risk_pic']; ?>"><?php echo $ddata2['risk_pic']; ?></option>
													</select>
												</div>
											<?php } elseif ($_GET['type'] == "IT") { ?>
												<div class="col-3 table-bordered">
													<select class="form-control" name="risk_pic" id="risk_pic" <?php echo "disabled"; ?>>
														<option value="<?php echo $ddata2['risk_pic']; ?>"><?php echo $ddata2['risk_pic']; ?></option>
													</select>
												</div>
											<?php } ?>
											<div class="col-3 table-bordered"><input class="form-control" type="text" name="pic_ket_ra" id="pic_ket_ra" <?php echo $ddata2['pic_ket_ra'];
																																						echo "disabled"; ?>></div>
										</div>
										<div class="row mb-3">
											<div class="col-3 table-bordered"><b>Technical Assesment</b></div>
											<div class="col-3 table-bordered"><textarea class="form-control" name="technical_assesment" id="technical_assesment" rows="1" cols="20" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																														echo "disabled";
																																													} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																echo $ddata2['technical_assesment'];
																																															} ?></textarea>
											</div>
											<div class="col-3 table-bordered">
												<select class="form-control" name="ta_pic" id="ta_pic" <?php echo "disabled"; ?>>
													<option value="<?php echo $ddata2['technical_assesment_pic']; ?>"><?php echo $ddata2['technical_assesment_pic']; ?></option>
												</select>
											</div>
											<div class="col-3 table-bordered"><input class="form-control" type="text" name="pic_ket_ta" id="pic_ket_ta" <?php echo "disabled"; ?> value="<?php echo $ddata2['pic_ket_ta']; ?>"></div>
										</div>
										<div class="row mb-3">
											<div class="col-3 table-bordered"><b>Business Benefit</b></div>
											<div class="col-3 table-bordered"><textarea class="form-control" name="business_benefit" id="business_benefit" rows="1" COLS="20" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																													echo "disabled";
																																												} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																															echo $ddata2['business_benefit'];
																																														} ?></textarea>
											</div>
											<div class="col-3 table-bordered">
												<select class="form-control" name="bb_pic" id="bb_pic" <?php echo "disabled"; ?>>
													<option value="<?php echo $ddata2['business_benefit_pic']; ?>"><?php echo $ddata2['business_benefit_pic']; ?></option>
												</select>
											</div>
											<div class="col-3 table-bordered"><input class="form-control" type="text" name="pic_ket_bb" id="pic_ket_bb" <?php echo $ddata2['pic_ket_bb'];
																																						echo "disabled"; ?>></div>
										</div>
									</div>
								<?php } ?>
								<?php if ($_GET['act'] == "add" && $_GET['type'] == "Maintenance") { ?>
									<div class="row mb-3 col-12">
										<div class="row">
											<div class="col-3 table-bordered"><b>Item Assesment</b></div>
											<div class="col-3 table-bordered"><b>Deskripsi</b></div>
											<div class="col-3 table-bordered"><b>PIC (Internal)</b></div>
											<div class="col-3 table-bordered"><b>PIC (Eksternal)</b></div>
										</div>
										<div class="row">
											<div class="col-3 table-bordered"><b>Risk Assesment</b></div>
											<div class="col-3 table-bordered"><textarea class="form-control" name="riskassesment" id="riskassesment" rows="1" cols="20" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																											echo "disabled";
																																										} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																													echo $ddata2['risk_assesment'];
																																												} ?></textarea>
											</div>
											<div class="col-3 table-bordered">
												<select class="form-control" name="risk_pic" id="risk_pic">
													<option></option>
													<?php do { ?>
														<option value="<?php echo $pic_ra_mt[0]['employee_email']; ?>"><?php echo $pic_ra_mt[0]['employee_name']; ?></option>
													<?php } while ($pic_ra_mt[0] = $pic_ra_mt[1]->fetch_assoc()); ?>
												</select>
											</div>
											<div class="col-3 table-bordered"><input class="form-control" type="text" name="pic_ket_ra" id="pic_ket_ra"></div>
										</div>
									</div>
								<?php } elseif ($_GET['act'] == "edit" && $_GET['type'] == "Maintenance") { ?>
									<div class="row mb-3 col-12">
										<div class="row">
											<div class="col-3 table-bordered"><b>Item Assesment</b></div>
											<div class="col-3 table-bordered"><b>Deskripsi</b></div>
											<div class="col-3 table-bordered"><b>PIC (Internal)</b></div>
											<div class="col-3 table-bordered"><b>PIC (Eksternal)</b></div>
										</div>
										<div class="row">
											<div class="col-3 table-bordered"><b>Risk Assesment</b></div>
											<div class="col-3 table-bordered"><textarea class="form-control" name="riskassesment" id="riskassesment" rows="1" cols="20" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																											echo "disabled";
																																										} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																													echo $ddata2['riskassesment'];
																																												} ?></textarea></div>
											<div class="col-3 table-bordered">
												<select class="form-control" name="risk_pic" id="risk_pic">
													<option value="<?php echo $data2[0]['risk_pic']; ?>"><?php echo $data2[0]['risk_pic']; ?></option>
													<?php do { ?>
														<option value="<?php echo $pic_ra_mt[0]['employee_email']; ?>"><?php echo $pic_ra_mt[0]['employee_name']; ?></option>
													<?php } while ($pic_ra_mt[0] = $pic_ra_mt[1]->fetch_assoc()); ?>
												</select>
											</div>
											<div class="col-3 table-bordered"><input class="form-control" type="text" name="pic_ket_ra" id="pic_ket_ra" value="<?php echo $ddata2['pic_ket_ra']; ?>"></div>
										</div>
									</div>
								<?php } elseif ($_GET['act'] == "review" && $_GET['type'] == "Maintenance") { ?>
									<div class="row mb-3 col-12">
										<div class="row">
											<div class="col-3 table-bordered"><b>Item Assesment</b></div>
											<div class="col-3 table-bordered"><b>Deskripsi</b></div>
											<div class="col-3 table-bordered"><b>PIC (Internal)</b></div>
											<div class="col-3 table-bordered"><b>PIC (Eksternal)</b></div>
										</div>
										<div class="row">
											<div class="col-3 table-bordered"><b>Risk Assesment</b></div>
											<div class="col-3 table-bordered"><textarea class="form-control" name="riskassesment" id="riskassesment" rows="1" cols="20" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																											echo "disabled";
																																										} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																													echo $ddata2['riskassesment'];
																																												} ?></textarea></div>
											<div class="col-3 table-bordered">
												<select class="form-control" name="risk_pic" id="risk_pic" <?php echo "disabled"; ?>>
													<option value="<?php echo $data2[0]['risk_pic']; ?>"><?php echo $data2[0]['risk_pic']; ?></option>
												</select>
											</div>
											<div class="col-3 table-bordered"><input class="form-control" type="text" name="pic_ket_ra" id="pic_ket_ra" <?php echo $ddata2['pic_ket_ra'];
																																						echo "disabled"; ?>></div>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>

				<?php if ($_GET['type'] == 'Implementation' && $_GET['costimpact'] == "Financial" || $_GET['type'] == 'Maintenance' && $_GET['costimpact'] == "Financial" || $_GET['type'] == 'Implementation' && $_GET['costimpact'] == "Ada" || $_GET['type'] == 'Maintenance' && $_GET['costimpact'] == "Ada") { ?>
					<!-- TAB Change Request Type -->
					<div class="tab-pane fade show" id="costplanx" role="tabpanel" aria-labelledby="crtype-tab">
						<div class="card shadow mb-4">
							<!-- Card Body -->
							<div class="card-body">
								<h2>Change Cost Plan</h2>
								<div class="row">
									<div class="col-lg-6">
										<div class="row mb-3">
											<label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Cost Type <b>*</b></label>
											<div class="col-sm-8">
												<?php if ($_GET['act'] == "add") { ?>
													<select class="form-control" name="cost_type" id="cost_type" onchange="changeCostType()" required>
														<option></option>
														<option value="Chargeable">Chargeable</option>
														<option value="Non-Chargeable">Non-Chargeable</option>
													</select>
												<?php } elseif ($_GET['act'] == "edit") { ?>
													<select class="form-control" name="cost_type" id="cost_type" onchange="changeCostType()" required>
														<option value="<?php echo $ddata16['cost_type']; ?>"><?php echo $ddata16['cost_type']; ?></option>
														<option value="Chargeable">Chargeable</option>
														<option value="Non-Chargeable">Non-Chargeable</option>
													</select>
												<?php } ?>
												<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
													<input type="text" class="form-control form-control-sm" id="cost_type" name="cost_type" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																				echo "disabled";
																																			} ?> value="<?php echo $ddata16['cost_type']; ?>">
												<?php } ?>
											</div>
										</div>
										<div class="row mb-3" id="nonchargeable_cost">
											<label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Non-Chargeable Cost Responsibility of</label>
											<div class="col-sm-8">
												<?php if ($_GET['act'] == "add") { ?>
													<select class="form-control" name="nccr" id="nccr" onchange="changeResponsibility()">
														<option></option>
														<option value="Sales">Sales</option>
														<option value="MSI">MSI</option>
													</select>
												<?php } elseif ($_GET['act'] == "edit") { ?>
													<select class="form-control" name="nccr" id="nccr" onchange="changeResponsibility()">
														<option value="<?php echo $ddata16['responsibility']; ?>"><?php echo $ddata16['responsibility']; ?></option>
														<option value="Sales">Sales</option>
														<option value="MSI">MSI</option>
													</select>
												<?php } ?>
												<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
													<input type="text" class="form-control form-control-sm" id="nccr" name="nccr" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																		echo "disabled";
																																	} ?> value="<?php echo ucwords($ddata16['responsibility']); ?>">
												<?php } ?>
											</div>
										</div>
										<div class="row mb-3">
											<label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Sales Name</label>
											<div class="col-sm-8">
												<?php if ($_GET['act'] == "add") { ?>
													<?php $salesname = isset($selected_pc2[0]['sales_name']); ?>
													<?php if ($salesname == null) { ?>
														<input type="text" class="form-control" id="sales_name" name="sales_name">
													<?php } else {  ?>
														<select class="form-control" name="sales_name" id="sales_name">
															<option id="sales_name" name="sales_name" value="<?php echo $selected_pc2[0]['sales_name']; ?>"><?php echo $selected_pc2[0]['sales_name'];
																																						} ?></option>
														</select>
													<?php } ?>
													<?php if ($_GET['act'] == "edit") { ?>
														<?php $checksales = isset($ddata16['sales_name']); ?>
														<?php if ($checksales == null) { ?>
															<input type="text" class="form-control form-control-sm" id="sales_name" name="sales_name">
														<?php } else { ?>
															<select class="form-control" name="sales_name" id="sales_name">
																<option id="sales_name" name="sales_name" value="<?php echo $ddata16['sales_name']; ?>"><?php echo $ddata16['sales_name']; ?></option>
															</select>
														<?php } ?>
													<?php } ?>
													<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
														<input type="text" class="form-control form-control-sm" id="sales_name" name="sales_name" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																						echo "disabled";
																																					} ?> value="<?php echo $ddata16['sales_name']; ?>">
													<?php } ?>
											</div>
										</div>
										<div class="row mb-3" id="pochargeable">
											<label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Nomor PO</label>
											<div class="col-sm-8">
												<?php if ($_GET['act'] == "add") { ?>
													<input type="text" class="form-control form-control-sm" id="nomorpo_chargeable" name="nomorpo_chargeable">
												<?php } ?>
												<?php if ($_GET['act'] == "edit" || $_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
													<input type="text" class="form-control form-control-sm" id="nomorpo_chargeable" name="nomorpo_chargeable" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																									echo "disabled";
																																								} ?> value="<?php echo $ddata16['nomor_po']; ?>">
												<?php } ?>
											</div>
										</div>
										<div class="row mb-3" id="changereason_non">
											<label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Change Reason</label>
											<div class="col-sm-8">
												<?php if ($_GET['act'] == "add") { ?>
													<select class="form-control" name="change_reason" id="change_reason">
														<option></option>
														<option value="Pre Sales">Pre Sales</option>
														<option value="Post Sales">Post Sales</option>
														<option value="Others">Others</option>
													</select>
												<?php } elseif ($_GET['act'] == "edit") { ?>
													<select class="form-control" name="change_reason" id="change_reson">
														<option value="<?php echo ucwords($ddata16['change_reason']); ?>"><?php echo ucwords($ddata16['change_reason']); ?></option>
														<option value="Pre Sales">Pre Sales</option>
														<option value="Post Sales">Post Sales</option>
														<option value="Others">Others</option>
													</select>
												<?php } ?>
												<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
													<input type="text" class="form-control form-control-sm" id="change_reason" name="change_reason" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																						echo "disabled";
																																					} ?> value="<?php echo ucwords($ddata16['change_reason']); ?>">
												<?php } ?>
											</div>
										</div>
										<div class="row mb-3">
											<label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Detail Reason</label>
											<div class="col-sm-8">
												<textarea class="form-control" name="detail_reason" id="detail_reason" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																	echo "disabled";
																																} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																			echo $ddata16['detail_reason'];
																																		} ?></textarea>
											</div>
										</div>
									</div>
									<?php if ($_GET['act'] == "add") { ?>
										<div class="col-lg-6">
											<div class="row mb-3">
												<div class="row">
													<div class="col-12 table-bordered"><b>Mandays</b></div>
												</div>
												<div class="row">
													<div class="col-4 table-bordered"><b>Type of Resource</b></div>
													<div class="col-4 table-bordered"><b>Total Mandays</b></div>
													<div class="col-4 table-bordered"><b>Nilai Mandays</b></div>
												</div>
												<div id="newRow-mandays"></div>
												<button id="addRow-mandays" type="button" class="btn btn-info col-12">+</button>
											</div>
											<div class="row mb-3">
												<div class="row">
													<div class="col-12 table-bordered"><b>Others</b></div>
												</div>
												<div class="row">
													<div class="col-4 table-bordered"><b>Item</b></div>
													<div class="col-4 table-bordered"><b>Detail Item</b></div>
													<div class="col-4 table-bordered"><b>Nilai Biaya</b></div>
												</div>
												<div id="newRow-others"></div>
												<button id="addRow-others" type="button" class="btn btn-info col-12">+</button>
											</div>
										</div>
									<?php } ?>
									<?php if ($_GET['act'] == "edit") { ?>
										<div class="col-lg-6">
											<?php $val = isset($data12[0]['type_of_resources']); ?>
											<?php if ($val == null) { ?>
												<div class="row mb-3">
													<div class="row">
														<div class="col-12 table-bordered"><b>Mandays</b></div>
													</div>
													<div class="row">
														<div class="col-4 table-bordered"><b>Type of Resource</b></div>
														<div class="col-4 table-bordered"><b>Total Mandays</b></div>
														<div class="col-4 table-bordered"><b>Nilai Mandays</b></div>
													</div>
													<div id="newRow-mandays"></div>
													<button id="addRow-mandays" type="button" class="btn btn-info col-12">+</button>
												</div>
											<?php } else { ?>
												<div class="row mb-3">
													<div class="row">
														<div class="col-12 table-bordered"><b>Mandays</b></div>
													</div>
													<div class="row">
														<div class="col-4 table-bordered"><b>Type of Resource</b></div>
														<div class="col-4 table-bordered"><b>Total Mandays</b></div>
														<div class="col-4 table-bordered"><b>Nilai Mandays</b></div>
													</div>
													<?php do { ?>
														<div class="p-0" id="inputFormRow-mandays">
															<div class="row">
																<!-- <input type="hidden" name="mandays_id[]" id="mandays_id[]" value="<?php //echo $data12[0]['mandays_id']; 
																																		?>"> -->
																<div class="col-4 table-bordered">
																	<select class="form-control mb-3" name="mandays_tor[]" id="mandays_tor[]">
																		<option value="<?php echo $data12[0]['type_of_resources']; ?>"><?php echo $data12[0]['type_of_resources']; ?></option>
																		<option value='Project Director'>Project Director</option>
																		<option value='Project Manager'>Project Manager</option>
																		<option value='Project Coordinator'>Project Coordinator</option>
																		<option value='Project Admin'>Project Admin</option>
																		<option value='Engineer Expert'>Engineer Expert</option>
																		<option value='Engineer Professional'>Engineer Professional</option>
																		<option value='Engineer Associate'>Engineer Associate</option>
																	</select>
																</div>
																<div class="col-4 table-bordered"><textarea class="form-control" name="mandays_tm" id="mandays_tm" rows="1" value="<?php echo $data12[0]['mandays_total']; ?>"><?php echo $data12[0]['mandays_total']; ?></textarea></div>
																<div class="col-4 table-bordered"><textarea class="form-control" name="mandays_value" id="mandays_value" rows="1" value="<?php echo $data12[0]['mandays_value']; ?>" readonly><?php echo $data12[0]['mandays_value']; ?></textarea></div>
															</div>
														</div>
													<?php } while ($data12[0] = $data12[1]->fetch_assoc()); ?>
												</div>
											<?php } ?>
											<?php $other = isset($data11[0]['item']) ?>
											<?php if ($other == null) { ?>
												<div class="row mb-3">
													<div class="row">
														<div class="col-12 table-bordered"><b>Others</b></div>
													</div>
													<div class="row">
														<div class="col-4 table-bordered"><b>Item</b></div>
														<div class="col-4 table-bordered"><b>Detail Item</b></div>
														<div class="col-4 table-bordered"><b>Nilai Biaya</b></div>
													</div>
													<div id="newRow-others"></div>
													<button id="addRow-others" type="button" class="btn btn-info col-12">+</button>
												</div>
											<?php } else { ?>
												<div class="row mb-3">
													<div class="row">
														<div class="col-12 table-bordered"><b>Others</b></div>
													</div>
													<div class="row">
														<!-- <div class="col-3 table-bordered"><b>ID</b></div> -->
														<div class="col-4 table-bordered"><b>Item</b></div>
														<div class="col-4 table-bordered"><b>Detail Item</b></div>
														<div class="col-4 table-bordered"><b>Nilai Biaya</b></div>
													</div>
													<?php do { ?>
														<div class="p-0" id="inputFormRow-others">
															<div class="row">
																<!-- <div class="col-3 table-bordered"> -->
																<!-- <select class="form-control" name="fo_id[]" id="fo_id[]"> -->
																<input type="hidden" name="fo_id[]" id="fo_id[]" value="<?php echo $data11[0]['fo_id']; ?>">
																<!-- </select> -->
																<!-- </div> -->
																<div class="col-4 table-bordered"><select class="form-control mb-3" name="others_item[]" id="others_item[]">
																		<option value="<?php echo $data11[0]['item']; ?>"><?php echo $data11[0]['item']; ?></option>

																		<option value='Outsourcing Plan'>Outsourcing Plan</option>
																		<option value='BPD'>BPD</option>
																		<option value='Maintenance Package Price'>Maintenance Package Price</option>
																		<option value='Existing Backup Unit'>Existing Backup Unit</option>
																		<option value='Investment Backup Unit'>Investment Backup Unit</option>
																		<option value='Extended Warranty Cisco'>Extended Warranty Cisco</option>
																		<option value='Extended Warranty Non-Cisco'>Extended Warranty Non-Cisco</option>
																		<option value='Price PO'>Price PO</option>
																		<option value='Band'>Band</option>
																		<option value='Others'>Others</option>
																	</select>
																</div>
																<div class="col-4 table-bordered"><textarea class="form-control" name="others_detail[]" id="others_detail[]" rows="1" value="<?php echo $data11[0]['detail_item']; ?>"><?php echo $data11[0]['detail_item']; ?></textarea></div>
																<div class="col-4 table-bordered"><textarea class="form-control" name="others_price[]" id="others_price[]" rows="1" value="<?php echo $data11[0]['value']; ?>"><?php echo $data11[0]['value']; ?></textarea></div>
															</div>
														</div>
													<?php } while ($data11[0] = $data11[1]->fetch_assoc()); ?>
												</div>
											<?php } ?>
										</div>
									<?php } ?>
									<?php if ($_GET['act'] == "review") { ?>
										<div class="col-lg-6">
											<?php $mantor = isset($data12[0]['type_of_resources']); ?>
											<?php if ($mantor == null) { ?>
												<?php echo ""; ?>
											<?php } else { ?>
												<div class="row mb-3">
													<div class="row">
														<div class="col-12 table-bordered"><b>Mandays</b></div>
													</div>
													<div class="row">
														<div class="col-4 table-bordered"><b>Type of Resource</b></div>
														<div class="col-4 table-bordered"><b>Total Mandays</b></div>
														<div class="col-4 table-bordered"><b>Nilai Mandays</b></div>
													</div>
													<?php do { ?>
														<div class="p-0" id="inputFormRow-mandays">
															<div class="row">
																<div class="col-4 table-bordered"><textarea class="form-control" name="mandays_tor[]" id="mandays_tor[]" rows="1" <?php echo "disabled"; ?> value="<?php echo $data12[0]['type_of_resources']; ?>"><?php echo $data12[0]['type_of_resources']; ?></textarea></div>
																<div class="col-4 table-bordered"><textarea class="form-control" name="mandays_tm[]" id="mandays_tm[]" rows="1" <?php echo "disabled"; ?> value="<?php echo $data12[0]['mandays_total']; ?>" onchange="format_amount_idr()"><?php echo $data12[0]['mandays_total']; ?></textarea></div>
																<div class="col-4 table-bordered"><textarea class="form-control" name="mandays_value[]" id="mandays_value[]" rows="1" <?php echo "disabled"; ?> value="<?php echo $data12[0]['mandays_value']; ?>" onchange="format_amount_idr()"><?php echo $data12[0]['mandays_value']; ?></textarea></div>
															</div>
														</div>
													<?php } while ($data12[0] = $data12[1]->fetch_assoc()); ?>
												</div>
											<?php } ?>
											<?php $oter = isset($data11[0]['item']); ?>
											<?php if ($oter == null) { ?>
												<?php echo ""; ?>
											<?php } else { ?>
												<div class="row mb-3">
													<div class="row">
														<div class="col-12 table-bordered"><b>Others</b></div>
													</div>
													<div class="row">
														<div class="col-4 table-bordered"><b>Item</b></div>
														<div class="col-4 table-bordered"><b>Detail Item</b></div>
														<div class="col-4 table-bordered"><b>Nilai Biaya</b></div>
													</div>
													<div class="row">
														<div class="col-4 table-bordered"><textarea class="form-control" name="others_item" id="others_item" rows="1" <?php echo "disabled"; ?> value="<?php echo $data11[0]['item']; ?>"><?php echo $data11[0]['item']; ?></textarea></div>
														<div class="col-4 table-bordered"><textarea class="form-control" name="others_detail" id="others_detail" rows="1" <?php echo "disabled"; ?> value="<?php echo $data11[0]['detail_item']; ?>"><?php echo $data11[0]['detail_item']; ?></textarea></div>
														<div class="col-4 table-bordered"><textarea class="form-control" name="others_price" id="others_price" rows="1" <?php echo "disabled"; ?> value="<?php echo $data11[0]['value']; ?>" onchange="format_amount_idr()"><?php echo $data11[0]['value']; ?></textarea></div>
													</div>
												</div>
											<?php } ?>
										</div>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
				<?php } else {
					'';
				} ?>

				<!-- TAB Change Implementation Plans -->
				<div class="tab-pane fade show" id="implementx" role="tabpanel" aria-labelledby="crtype-tab">
					<div class="card shadow mb-4">
						<!-- Card Body -->
						<div class="card-body">
							<?php if ($_GET['type'] == "Sales/Presales") { ?>
								<div class="row">
									<div class="col-lg-6">
										<div class="row mb-3">
											<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Start Date</label>
											<div class="col-sm-9">
												<input type="date" class="form-control form-control-sm" id="start_date" name="start_date" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																				echo "disabled";
																																			} ?> value="<?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																							echo date('Y-m-d', strtotime($ddata3['start_date']));
																																						} ?>" required>
											</div>
										</div>
									</div>
									<div class="col-lg-6">
										<div class="row mb-3">
											<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Finish Date</label>
											<div class="col-sm-9">
												<input type="date" class="form-control form-control-sm" id="finish_date" name="finish_date" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																				echo "disabled";
																																			} ?> value="<?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																							echo date('Y-m-d', strtotime($ddata3['finish_date']));
																																						} ?>" required>
											</div>
										</div>
									</div>
									<?php if ($_GET['act'] == "add") { ?>
										<div class="col-lg-12">
											<h2>Cost Impact Plan</h2>
											<div class="row mb-3">
												<div class="row">
													<div class="col-12 table-bordered"><b>Mandays</b></div>
												</div>
												<div class="row">
													<div class="col-4 table-bordered"><b>Type of Resource</b></div>
													<div class="col-4 table-bordered"><b>Total Mandays</b></div>
													<div class="col-4 table-bordered"><b>Nilai Mandays</b></div>
												</div>
												<div id="newRow-mandays"></div>
												<button id="addRow-mandays" type="button" class="btn btn-info col-12">+</button>
											</div>
											<div class="row mb-3">
												<div class="row">
													<div class="col-12 table-bordered"><b>Others</b></div>
												</div>
												<div class="row">
													<div class="col-4 table-bordered"><b>Item</b></div>
													<div class="col-4 table-bordered"><b>Detail Item</b></div>
													<div class="col-4 table-bordered"><b>Nilai Biaya</b></div>
												</div>
												<div id="newRow-others"></div>
												<button id="addRow-others" type="button" class="btn btn-info col-12">+</button>
											</div>
										</div>
									<?php } ?>
									<?php if ($_GET['act'] == "edit") { ?>
										<div class="col-lg-12">
											<h2>Cost Impact Plan</h2>
											<?php $val = isset($data12[0]['type_of_resources']); ?>
											<?php if ($val == null) { ?>
												<div class="row mb-3">
													<div class="row">
														<div class="col-12 table-bordered"><b>Mandays</b></div>
													</div>
													<div class="row">
														<div class="col-4 table-bordered"><b>Type of Resource</b></div>
														<div class="col-4 table-bordered"><b>Total Mandays</b></div>
														<div class="col-4 table-bordered"><b>Nilai Mandays</b></div>
													</div>
													<div id="newRow-mandays"></div>
													<button id="addRow-mandays" type="button" class="btn btn-info col-12">+</button>
												</div>
											<?php } else { ?>
												<div class="row mb-3">
													<div class="row">
														<div class="col-12 table-bordered"><b>Mandays</b></div>
													</div>
													<div class="row">
														<div class="col-4 table-bordered"><b>Type of Resource</b></div>
														<div class="col-4 table-bordered"><b>Total Mandays</b></div>
														<div class="col-4 table-bordered"><b>Nilai Mandays</b></div>
													</div>
													<?php do { ?>
														<div class="p-0" id="inputFormRow-mandays">
															<input type="hidden" name="mandays_id[]" id="mandays_id[]" value="<?php echo $data12[0]['mandays_id']; ?>">
															<div class="row">
																<div class="col-4 table-bordered">
																	<select class="form-control mb-3" name="mandays_tor[]" id="mandays_tor[]">
																		<option value="<?php echo $data12[0]['type_of_resources']; ?>"><?php echo $data12[0]['type_of_resources']; ?></option>
																		<option value='Project Director'>Project Director</option>
																		<option value='Project Manager'>Project Manager</option>
																		<option value='Project Coordinator'>Project Coordinator</option>
																		<option value='Project Admin'>Project Admin</option>
																		<option value='Engineer Expert'>Engineer Expert</option>
																		<option value='Engineer Professional'>Engineer Professional</option>
																		<option value='Engineer Associate'>Engineer Associate</option>
																	</select>
																</div>
																<div class="col-4 table-bordered"><textarea class="form-control" name="mandays_tm[]" id="mandays_tm[]" rows="1" value="<?php echo $data12[0]['mandays_total']; ?>"><?php echo $data12[0]['mandays_total']; ?></textarea></div>
																<div class="col-4 table-bordered"><textarea class="form-control" name="mandays_value[]" id="mandays_value[]" rows="1" value="<?php echo $data12[0]['mandays_value']; ?>" readonly><?php echo $data12[0]['mandays_value']; ?></textarea></div>
															</div>
														</div>
													<?php } while ($data12[0] = $data12[1]->fetch_assoc()); ?>
												</div>
											<?php } ?>
											<?php $other = isset($data11[0]['item']) ?>
											<?php if ($other == null) { ?>
												<div class="row mb-3">
													<div class="row">
														<div class="col-12 table-bordered"><b>Others</b></div>
													</div>
													<div class="row">
														<div class="col-4 table-bordered"><b>Item</b></div>
														<div class="col-4 table-bordered"><b>Detail Item</b></div>
														<div class="col-4 table-bordered"><b>Nilai Biaya</b></div>
													</div>
													<div id="newRow-others"></div>
													<button id="addRow-others" type="button" class="btn btn-info col-12">+</button>
												</div>
											<?php } else { ?>
												<div class="row mb-3">
													<div class="row">
														<div class="col-12 table-bordered"><b>Others</b></div>
													</div>
													<div class="row">
														<!-- <div class="col-3 table-bordered"><b>ID</b></div> -->
														<div class="col-4 table-bordered"><b>Item</b></div>
														<div class="col-4 table-bordered"><b>Detail Item</b></div>
														<div class="col-4 table-bordered"><b>Nilai Biaya</b></div>
													</div>
													<?php do { ?>
														<div class="p-0" id="inputFormRow-others">
															<div class="row">
																<!-- <div class="col-3 table-bordered"> -->
																<!-- <select class="form-control" name="fo_id[]" id="fo_id[]"> -->
																<input type="hidden" name="fo_id[]" id="fo_id[]" value="<?php echo $data11[0]['fo_id']; ?>">
																<!-- </select> -->
																<!-- </div> -->
																<div class="col-4 table-bordered"><select class="form-control mb-3" name="others_item[]" id="others_item[]">
																		<option value="<?php echo $data11[0]['item']; ?>"><?php echo $data11[0]['item']; ?></option>

																		<option value='Outsourcing Plan'>Outsourcing Plan</option>
																		<option value='BPD'>BPD</option>
																		<option value='Maintenance Package Price'>Maintenance Package Price</option>
																		<option value='Existing Backup Unit'>Existing Backup Unit</option>
																		<option value='Investment Backup Unit'>Investment Backup Unit</option>
																		<option value='Extended Warranty Cisco'>Extended Warranty Cisco</option>
																		<option value='Extended Warranty Non-Cisco'>Extended Warranty Non-Cisco</option>
																		<option value='Price PO'>Price PO</option>
																		<option value='Band'>Band</option>
																		<option value='Others'>Others</option>
																	</select>
																</div>
																<div class="col-4 table-bordered"><textarea class="form-control" name="others_detail[]" id="others_detail[]" rows="1" value="<?php echo $data11[0]['detail_item']; ?>"><?php echo $data11[0]['detail_item']; ?></textarea></div>
																<div class="col-4 table-bordered"><textarea class="form-control" name="others_price[]" id="others_price[]" rows="1" value="<?php echo $data11[0]['value']; ?>"><?php echo $data11[0]['value']; ?></textarea></div>
															</div>
														</div>
													<?php } while ($data11[0] = $data11[1]->fetch_assoc()); ?>
												</div>
											<?php } ?>
										</div>
									<?php } ?>
									<div class="row mb-3">
										<label for="inputCID3" class="col-sm-4 col-form-label col-form-label-sm">Scope Of Change <b>*</b></label>
										<div class="col-sm-8">
											<?php if ($_GET['act'] == "add") { ?>
												<textarea class="form-control" name="scope_of_change" id="scope_of_change" rows="2"></textarea>
											<?php
											}
											if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') { ?>
												<textarea class="form-control" name="scope_of_change" id="scope_of_change" rows="2" readonly><?php echo $ddata['scope_of_change']; ?></textarea>
											<?php }    ?>
										</div>
									</div>
								</div>
							<?php } ?>
							<?php if ($_GET['type'] == "Implementation" || $_GET['type'] == "Maintenance" || $_GET['type'] == "IT") { ?>
								<h2>Change Implementation Plans</h2>
								<div class="row">
									<div class="col-lg-6">
										<div class="row mb-3">
											<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Start Date</label>
											<div class="col-sm-9">
												<input type="date" class="form-control form-control-sm" id="start_date" name="start_date" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																				echo "disabled";
																																			} ?> value="<?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																							echo date('Y-m-d', strtotime($ddata3['start_date']));
																																						} ?>" required>
											</div>
										</div>
									</div>
									<div class="col-lg-6">
										<div class="row mb-3">
											<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Finish Date</label>
											<div class="col-sm-9">
												<input type="date" class="form-control form-control-sm" id="finish_date" name="finish_date" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																				echo "disabled";
																																			} ?> value="<?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																							echo date('Y-m-d', strtotime($ddata3['finish_date']));
																																						} ?>" required>
											</div>
										</div>
									</div>
									<div class="col-lg-12">
										<div class="row mb-5 border-top pt-3">
											<!-- <label for="inputCID3a" class="col-sm-3 col-form-label col-form-label-sm"><b>Detail Plan</b></label> -->
											<div class="col-sm-12">
												<?php if ($_GET['act'] == 'review') { ?>
													<?php $val = isset($data4[0]['working_detail']); ?>
													<?php if ($val == null) {
														'';
													} else { ?>
														<table class="table">
															<thead>
																<tr>
																	<th scope="col">#</th>
																	<th scope="col">Detil Pekerjaan</th>
																	<th scope="col">Start Date</th>
																	<th scope="col">Finish Date</th>
																	<th scope="col">PIC</th>
																</tr>
															</thead>
															<tbody>
																<?php do { ?>
																	<?php
																	$j = 1;
																	$working_detail = isset($data4[0]['working_detail']);
																	if ($working_detail == null) {
																		'';
																	} else {
																		$working_detail = $data4[0]['working_detail'];
																	}
																	$time = isset($data4[0]['time']);
																	if ($time == null) {
																		'';
																	} else {
																		$time = $data4[0]['time'];
																	}
																	$finish_time = isset($data4[0]['finish_time']);
																	if ($finish_time == null) {
																		'';
																	} else {
																		$finish_time = $data4[0]['finish_time'];
																	}
																	$picdp = isset($data4[0]['pic']);
																	if ($picdp == null) {
																		'';
																	} else {
																		$picdp = $data4[0]['pic'];
																	}
																	echo "<tr><td>$j</td><td>$working_detail</td><td>$time</td><td>$finish_time</td><td>$picdp</td></tr>";
																	$j++; ?>
																<?php } while ($data4[0] = $data4[1]->fetch_assoc()); ?>
															</tbody>
														</table>
													<?php } ?>
												<?php } elseif ($_GET['act'] == 'add') { ?>
													<div class="row-mb-3">
														<div class="row">
															<div class="col-12 table-bordered font-centered"><b>Detail Plan</b></div>
														</div>
														<div class="row">
															<div class="col-3 table-bordered"><b>Detil Pekerjaan</b></div>
															<div class="col-2 table-bordered"><b>Start Date</b></div>
															<div class="col-2 table-bordered"><b>Finish Date</b></div>
															<div class="col-3 table-bordered"><b>PIC</b></div>
															<div class="col-2 table-bordered"><b>Delete</b></div>
														</div>
														<div id="newRow-plan"></div>
														<button id="addRow-plan" type="button" class="btn btn-info col-12">+</button>
													</div>
												<?php } elseif ($_GET['act'] == 'edit') { ?>
													<?php $val = isset($data4[0]['working_detail']); ?>
													<?php if ($val == null) { ?>
														<div class="row-mb-3">
															<div class="row">
																<div class="col-12 table-bordered font-centered"><b>Detail Plan</b></div>
															</div>
															<div class="row">
																<div class="col-3 table-bordered"><b>Detil Pekerjaan</b></div>
																<div class="col-2 table-bordered"><b>Start Date</b></div>
																<div class="col-2 table-bordered"><b>Finish Date</b></div>
																<div class="col-3 table-bordered"><b>PIC</b></div>
																<div class="col-2 table-bordered"><b>Delete</b></div>
															</div>
															<div id="newRow-plan"></div>
															<button id="addRow-plan" type="button" class="btn btn-info col-12">+</button>
														</div>
													<?php } else { ?>
														<div class="row-mb-3">
															<div class="row">
																<div class="col-12 table-bordered font-centered"><b>Detail Plan</b></div>
															</div>
															<div class="row">
																<div class="col-2 table-bordered"><b>ID</b></div>
																<div class="col-3 table-bordered"><b>Detil Pekerjaan</b></div>
																<div class="col-2 table-bordered"><b>Start Date</b></div>
																<div class="col-2 table-bordered"><b>Finish Date</b></div>
																<div class="col-3 table-bordered"><b>PIC</b></div>
															</div>
															<?php do { ?>
																<div class="p-0" id="inputFormRow-plan">
																	<div class="row">
																		<div class="col-2 table-bordered">
																			<select class="form-control" name="dp_id[]" id="dp_id[]">
																				<option class="form-control" value="<?php echo $data4[0]['dp_id']; ?>"><?php echo $data4[0]['dp_id']; ?></option>
																			</select>
																		</div>
																		<div class="col-3 table-bordered"><textarea class="form-control" name="working_detail_plan[]" id="working_detail_plan[]" rows="1" value="<?php echo $data4[0]['working_detail']; ?>"><?php echo $data4[0]['working_detail']; ?></textarea></div>
																		<div class="col-2 table-bordered"><input type="date" name="time[]" id="time[]" class="form-control form-control-sm mb-3" value="<?php echo date('Y-m-d', strtotime($data4[0]['time'])); ?>"></div>
																		<div class="col-2 table-bordered"><input type="date" name="finish_time[]" id="finish_time[]" class="form-control form-control-sm mb-3" value="<?php echo date('Y-m-d', strtotime($data4[0]['finish_time'])); ?>"></div>
																		<div class="col-3 table-bordered"><select class="form-control mb-3" name="dp_pic[]" id="dp_pic[]">
																				<option value="<?php echo $data4[0]['pic']; ?>"><?php echo $data4[0]['pic']; ?></option>
																				<?php do {  ?>
																					<option value="<?php echo $pic_dp[0]['employee_email']; ?>"><?php echo $pic_dp[0]['employee_name']; ?></option>
																				<?php } while ($pic_dp[0] = $pic_dp[1]->fetch_assoc()); ?>
																			</select>
																		</div>
																	</div>
																</div>
															<?php } while ($data4[0] = $data4[1]->fetch_assoc()); ?>
															<!-- <div id="newnewRow-plan"></div>
										<button id="editRow-plan" type="button" class="btn btn-info">+</button> -->
														</div>
												<?php }
												} ?>
											</div>
											<div class="col-sm-9 text-right">
											</div>
										</div>
									</div>
									<div class="col-lg-12">
										<div class="row mb-3 border-top pt-3">
											<!-- <label for="inputCID3a" class="col-sm-3 col-form-label col-form-label-sm"><b>Fallback Plan</b></label> -->
											<div class="col-sm-12">
												<?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') { ?>
													<?php $val = isset($data5[0]['working_detail']); ?>
													<?php if ($val == null) {
														'';
													} else { ?>
														<table class="table">
															<thead>
																<tr>
																	<th scope="col">#</th>
																	<th scope="col">Detil Pekerjaan</th>
																	<th scope="col">PIC</th>
																</tr>
															</thead>
															<tbody>
																<?php do { ?>
																	<?php
																	$j = 1;
																	$working_detail = isset($data5[0]['working_detail']);
																	if ($working_detail == null) {
																		'';
																	} else {
																		$working_detail = $data5[0]['working_detail'];
																	}
																	$val = isset($data5[0]['pic']);
																	if ($val == null) {
																		'';
																	} else {
																		$val = $data5[0]['pic'];
																	}
																	echo "<tr><td>$j</td><td>$working_detail</td><td>$val</td></tr>";
																	$j++; ?>
																<?php } while ($data5[0] = $data5[1]->fetch_assoc()); ?>
															</tbody>
														</table>
													<?php } ?>
												<?php } elseif ($_GET['act'] == 'add') { ?>
													<div class="row-mb-3">
														<div class="row">
															<div class="col-12 table-bordered font-centered"><b>Fallback Plan</b></div>
														</div>
														<div class="row">
															<div class="col-5 table-bordered"><b>Detil Pekerjaan</b></div>
															<div class="col-4 table-bordered"><b>PIC</b></div>
															<div class="col-3 table-bordered"><b>Delete</b></div>
														</div>
														<div id="newRow-fallback"></div>
														<button id="addRow-fallback" type="button" class="btn btn-info col-12">+</button>
													</div>
												<?php } elseif ($_GET['act'] == 'edit') { ?>
													<?php $val = isset($data5[0]['working_detail']); ?>
													<?php if ($val == null) { ?>
														<div class="row-mb-3">
															<div class="row">
																<div class="col-12 table-bordered font-centered"><b>Fallback Plan</b></div>
															</div>
															<div class="row">
																<div class="col-5 table-bordered"><b>Detil Pekerjaan</b></div>
																<div class="col-4 table-bordered"><b>PIC</b></div>
																<div class="col-3 table-bordered"><b>Delete</b></div>
															</div>
															<div id="newRow-fallback"></div>
															<button id="addRow-fallback" type="button" class="btn btn-info col-12">+</button>
														</div>
													<?php } else { ?>
														<div class="row-mb-3">
															<div class="row">
																<div class="col-12 table-bordered font-centered"><b>Fallback Plan</b></div>
															</div>
															<div class="row">
																<div class="col-2 table-bordered"><b>ID</b></div>
																<div class="col-5 table-bordered"><b>Detil Pekerjaan</b></div>
																<div class="col-5 table-bordered"><b>PIC</b></div>
															</div>
															<?php do { ?>
																<div class="p-0 mb-3" id="inputFormRow-fallback">
																	<div class="row">
																		<div class="col-2 table-bordered">
																			<select class="form-control" name="dp_id[]" id="dp_id[]">
																				<option value="<?php echo $data5[0]['dp_id']; ?>"><?php echo $data5[0]['dp_id']; ?></option>
																			</select>
																		</div>
																		<div class="col-5 table-bordered"><textarea class="form-control" name="working_detail_fallback[]" id="working_detail_fallback[]" rows="1" value="<?php echo $data5[0]['working_detail']; ?>"><?php echo $data5[0]['working_detail']; ?></textarea></div>
																		<div class="col-5 table-bordered"><select class="form-control mb-3" name="fp_pic[]" id="fp_pic[]">
																				<option><?php echo $data5[0]['pic']; ?></option>
																				<?php do {  ?>
																					<option value="<?php echo $pic_fp[0]['employee_email']; ?>"><?php echo $pic_fp[0]['employee_name']; ?></option>
																				<?php } while ($pic_fp[0] = $pic_fp[1]->fetch_assoc()); ?>
																			</select>
																		</div>
																	</div>
																</div>
															<?php } while ($data5[0] = $data5[1]->fetch_assoc()); ?>
															<!-- <div id="newnewRow-fallback"></div>
											<button id="editRow-fallback" type="button" class="btn btn-info">+</button> -->
														</div>
												<?php }
												} ?>
											</div>
											<div class="col-sm-9 text-right">
											</div>
										</div>
									</div>
									<div class="row mb-3 border-top pt-3" id="prerequisite">
										<!-- <label for="inputCID3a" class="col-sm-3 col-form-label col-form-label-sm"><b>Prerequisite</b></label> -->
										<div class="col-sm-12">
											<?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') { ?>
												<?php $val = isset($data13[0]['description']); ?>
												<?php if ($val == null) {
													'';
												} else { ?>
													<div class="row-mb-3">
														<div class="row">
															<div class="col-6 table-bordered font-centered"><b>Prerequisite (for Customer)</b></div>
														</div>
														<div class="row">
															<div class="col-6 table-bordered"><b>Requirement</b></div>
															<div class="col-6 table-bordered"><textarea name="customer_requirement_description" id="customer_requirement_description" cols="55" rows="4" <?php echo "disabled"; ?> value="<?php echo $data13[0]['description']; ?>"><?php echo $data13[0]['description']; ?></textarea></div>
														</div>
													</div>
												<?php } ?>
											<?php } elseif ($_GET['act'] == 'add') { ?>
												<div class="row-mb-3">
													<div class="row">
														<div class="col-12 table-bordered font-centered"><b>Prerequisite (for Customer)</b></div>
													</div>
													<div class="row">
														<div class="col-6 table-bordered"><b>Requirement</b></div>
														<div class="col-6 table-bordered"><textarea name="customer_requirement_description" id="customer_requirement_description" cols="55" rows="4"></textarea></div>
													</div>
												</div>
											<?php } elseif ($_GET['act'] == 'edit') { ?>
												<?php $val = isset($data13[0]['description']); ?>
												<?php if ($val == null) { ?>
													<div class="row-mb-3">
														<div class="row">
															<div class="col-12 table-bordered font-centered"><b>Prerequisite (for Customer)</b></div>
														</div>
														<div class="row">
															<div class="col-6 table-bordered"><b>Requirement</b></div>
															<div class="col-6 table-bordered"><textarea name="customer_requirement_description" id="customer_requirement_description" cols="55" rows="4"></textarea></div>
														</div>
													</div>
												<?php } else { ?>
													<div class="row-mb-3">
														<div class="row">
															<div class="col-12 table-bordered font-centered"><b>Prerequisite (for Customer)</b></div>
														</div>
														<div class="row">
															<div class="col-6 table-bordered"><b>Requirement</b></div>
															<div class="col-6 table-bordered"><textarea name="customer_requirement_description" id="customer_requirement_description" cols="55" rows="4" value="<?php echo $data13[0]['description']; ?>"><?php echo $data13[0]['description']; ?></textarea></div>
														</div>
														<div class="row">
														</div>
													</div>
											<?php }
											} ?>
										</div>
										<div class="col-sm-9 text-right">
										</div>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>

				<!-- TAB Change Request Approval -->
				<div class="tab-pane fade show" id="approvalx" role="tabpanel" aria-labelledby="crtype-tab">
					<div class="card shadow mb-4">
						<!-- Card Body -->
						<div class="card-body">
							<div class="row">
								<div class="row mb-3">
									<h2>Change Request Approval</h2>
									<?php if ($_GET['type'] == "Sales/Presales") { ?>
										<div class="row">
											<label for="inputCID3a" class="col-sm-12 col-form-label col-form-label-sm"><b>Change Control Board : Internal</b></label>
										</div>
										<div class="row mb-3 pt-3 pb-3">
											<label for="inputCID3a" class="col-sm-3 col-form-label col-form-label-sm" id="pic_text"><b>PIC *</b></label>
											<div class="col-sm-9">
												<?php if ($_GET['act'] == 'review') { ?>
													<input type="text" class="form-control form-control-sm" id="pic_apr2" name="pic_apr2" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																				echo "disabled";
																																			} ?> value="<?php echo $ddata['pic']; ?>">
												<?php } elseif ($_GET['act'] == 'add') { ?>
													<select class="form-control" name="pic_apr2" id="pic_apr2" required>
														<option value="Herdiman@mastersystem.co.id">Herdiman Eka Wijaya/Herdiman@mastersystem.co.id</option>
														<option value="andino.hf@mastersystem.co.id">Andino Holi Fajra/andino.hf@mastersystem.co.id</option>
														<option value="lintar@mastersystem.co.id">Moch. Lintar Wahyu Wardana/lintar@mastersystem.co.id</option>
													</select>
												<?php } elseif ($_GET['act'] == 'edit') { ?>
													<select class="form-control" name="pic_apr2" id="pic_apr2" required>
														<label>*</label>
														<option value="<?php echo $ddata['pic']; ?>"><?php echo $ddata['pic']; ?></option>
														<?php do { ?>
															<option value="<?php echo $pic_apr[0]['employee_email']; ?>"><?php echo $pic_apr[0]['employee_name'] . "/" . $pic_apr[0]['employee_email']; ?></option>
														<?php } while ($pic_apr[0] = $pic_apr[1]->fetch_assoc()); ?>
													</select>
												<?php } ?>
											</div>
											<div class="col-sm-9 text-right">
											</div>
										</div>
										<?php if ($_GET['act'] == "add") { ?>
											<div class="row mb-3">
												<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status CR</label>
												<div class="col-sm-9">
													<select class="form-control" name="status_request" id="status_request">
														<option value="submission_to_be_reviewed">Pending</option>
													</select>
												</div>
											</div>
											<div class="row mb-3">
												<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval</label>
												<div class="col-sm-9">
													<select class="form-control" name="status_approval" id="status_approval" onchange="ChangeCRStatus()">
														<option value="submission_to_be_reviewed">Pending</option>
													</select>
													<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
														<input type="text" class="form-control form-control-sm" id="status_approval" name="status_approval" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																								echo "disabled";
																																							} ?> value="<?php echo $data[0]['change_request_approval_type']; ?>">
													<?php } ?>
												</div>
											</div>
										<?php } else if ($_GET['act'] == "edit" && $ddata['pic'] == $_SESSION['Microservices_UserEmail']) { ?>
											<div class="row mb-3">
												<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval</label>
												<div class="col-sm-9">
													<?php $val = isset($data[0]['change_request_approval_type']); ?>
													<?php if ($val = null) { ?>
														<select class="form-control" name="status_approval" id="status_approval" onchange="ChangeCRStatus()">
															<option value="submission_approved">Approved</option>
															<option value="submission_rejected">Rejected</option>
														</select>
													<?php } else { ?>
														<select class="form-control" name="status_approval" id="status_approval" onchange="ChangeCRStatus()">
															<option value="<?php echo $data[0]['change_request_approval_type']; ?>"><?php echo $data[0]['change_request_approval_type']; ?></option>
															<option value="submission_approved">Approved</option>
															<option value="submission_rejected">Rejected</option>
														</select>
													<?php } ?>
													<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
														<input type="text" class="form-control form-control-sm" id="status_approval" name="status_approval" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																								echo "disabled";
																																							} ?> value="<?php echo $data[0]['change_request_approval_type']; ?>">
													<?php } ?>
												</div>
											</div>
											<div class="row mb-3">
												<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Reason</label>
												<div class="col-sm-9">
													<?php $val = isset($ddata17['reason_request']); ?>
													<?php if ($val = null) { ?>
														<textarea class="form-control" name="reason_request" id="reason_request" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																				echo "disabled"; ?> value="<?php echo $ddata17['reason_request'];
																																										} ?>">
								<?php } else { ?>
								<textarea class="form-control" name="reason_request" id="reason_request" rows="3" value = "<?php echo $ddata17['reason_request']; ?>"<?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																											echo "disabled";
																																										} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																													echo $ddata17['reason_request'];
																																												} ?></textarea>
													<?php } ?>
												</div>
											</div>
											<div class="row mb-3" id="crclosing">
												<h2>Change Request Closing</h2>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Hasil Akhir</label>
													<div class="col-sm-9">
														<?php $val = isset($ddata17['hasil_akhir']); ?>
														<?php if ($val = null) { ?>
															<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																			echo "disabled";
																																		} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																					echo $ddata17['hasil_akhir'];
																																				} ?></textarea>
														<?php } else { ?>
															<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" value="<?php echo $ddata17['hasil_akhir']; ?>" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																															echo "disabled";
																																														} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																	echo $ddata17['hasil_akhir'];
																																																} ?></textarea>
														<?php } ?>
													</div>
												</div>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status CR</label>
													<div class="col-sm-9">
														<?php $val = isset($data[0]['change_request_status']); ?>
														<?php if ($val == null) { ?>
															<select class="form-control" name="status_request" id="status_request">
																<option value="submission_to_be_reviewed">Incomplete</option>
																<option value="all_done">Complete</option>
																<option value="submission_rejected">Rejected</option>
															</select>
														<?php } else { ?>
															<select class="form-control" name="status_request" id="status_request">
																<option value="<?php echo $data[0]['change_request_status']; ?>"><?php echo $data[0]['change_request_status']; ?></option>
																<option value="submission_to_be_reviewed">Incomplete</option>
																<option value="all_done">Complete</option>
																<option value="submission_rejected">Rejected</option>
															</select>
														<?php } ?>
													</div>
												</div>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm" id="reason_rejected_text">Reason</label>
													<div class="col-sm-9">
														<?php $val = isset($ddata17['reason_rejected']); ?>
														<?php if ($val = null) { ?>
															<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" <?php if ($_GET['act'] == 'review') {
																																					echo "disabled";
																																				} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																							echo $ddata17['reason_rejected'];
																																						} ?></textarea>
														<?php } else { ?>
															<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" value="<?php echo $ddata17['reason_rejected']; ?>" <?php if ($_GET['act'] == 'review') {
																																																		echo "disabled";
																																																	} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																				echo $ddata17['reason_rejected'];
																																																			} ?></textarea>
														<?php } ?>
													</div>
												</div>
											</div>
										<?php } else if ($_GET['act'] == "edit" && $ddata['requested_by_email'] == $_SESSION['Microservices_UserEmail']) { ?>
											<?php if ($_GET['status_approval'] == 'submission_to_be_reviewed') { ?>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status</label>
													<div class="col-sm-9">
														<?php $val = isset($data[0]['change_request_approval_type']); ?>
														<?php if ($val = null) { ?>
															<select class="form-control" name="status_approval" id="status_approval" onchange="ChangeCRStatus()">
																<option value="submission_to_be_reviewed">Pending</option>
															</select>
														<?php } else { ?>
															<select class="form-control" name="status_approval" id="status_approval" onchange="ChangeCRStatus()">
																<option value="<?php echo $data[0]['change_request_approval_type']; ?>"><?php echo $data[0]['change_request_approval_type']; ?></option>
															</select>
														<?php } ?>
														<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
															<input type="text" class="form-control form-control-sm" id="status_approval" name="status_approval" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																									echo "disabled";
																																								} ?> value="<?php echo $data[0]['change_request_approval_type']; ?>">
														<?php } ?>
													</div>
												</div>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Reason</label>
													<div class="col-sm-9">
														<?php $val = isset($ddata17['reason_request']); ?>
														<?php if ($val = null) { ?>
															<textarea class="form-control" name="reason_request" id="reason_request" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																					echo "disabled"; ?> value="<?php echo $ddata17['reason_request'];
																																											} ?>">
								<?php } else { ?>
								<textarea class="form-control" name="reason_request" id="reason_request" rows="3" value = "<?php echo $ddata17['reason_request']; ?>"<?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																											echo "disabled";
																																										} ?> readonly><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																															echo $ddata17['reason_request'];
																																														} ?></textarea>
														<?php } ?>
													</div>
												</div>
												<div class="row mb-3" id="crclosing">
													<h2>Change Request Closing</h2>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Hasil Akhir</label>
														<div class="col-sm-9">
															<?php $val = isset($ddata17['hasil_akhir']); ?>
															<?php if ($val = null) { ?>
																<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																				echo "disabled";
																																			} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																						echo $ddata17['hasil_akhir'];
																																					} ?></textarea>
															<?php } else { ?>
																<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" value="<?php echo $ddata17['hasil_akhir']; ?>" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																echo "disabled";
																																															} ?> readonly><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																				echo $ddata17['hasil_akhir'];
																																																			} ?></textarea>
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status CR</label>
														<div class="col-sm-9">
															<?php $val = isset($data[0]['change_request_status']); ?>
															<?php if ($val == null) { ?>
																<select class="form-control" name="status_request" id="status_request">
																	<option value="submission_to_be_reviewed">Incomplete</option>
																</select>
															<?php } else { ?>
																<select class="form-control" name="status_request" id="status_request">
																	<option value="<?php echo $data[0]['change_request_status']; ?>"><?php echo $data[0]['change_request_status']; ?></option>
																</select>
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm" id="reason_rejected_text">Reason</label>
														<div class="col-sm-9">
															<?php $val = isset($ddata17['reason_rejected']); ?>
															<?php if ($val = null) { ?>
																<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" <?php if ($_GET['act'] == 'review') {
																																						echo "disabled";
																																					} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																								echo $ddata17['reason_rejected'];
																																							} ?></textarea>
															<?php } else { ?>
																<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" value="<?php echo $ddata17['reason_rejected']; ?>" <?php if ($_GET['act'] == 'review') {
																																																			echo "disabled";
																																																		} ?> readonly><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																							echo $ddata17['reason_rejected'];
																																																						} ?></textarea>
															<?php } ?>
														</div>
													</div>
												</div>
											<?php } else if ($_GET['status_approval'] == "submission_approved") { ?>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval</label>
													<div class="col-sm-9">
														<?php $val = isset($data[0]['change_request_approval_type']); ?>
														<?php if ($val = null) { ?>
															<select class="form-control" name="status_approval" id="status_approval" onchange="ChangeCRStatus()">
																<option value="submission_to_be_reviewed">Pending</option>
															</select>
														<?php } else { ?>
															<select class="form-control" name="status_approval" id="status_approval" onchange="ChangeCRStatus()">
																<option value="<?php echo $data[0]['change_request_approval_type']; ?>"><?php echo $data[0]['change_request_approval_type']; ?></option>
															</select>
														<?php } ?>
														<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
															<input type="text" class="form-control form-control-sm" id="status_approval" name="status_approval" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																									echo "disabled";
																																								} ?> value="<?php echo $data[0]['change_request_approval_type']; ?>">
														<?php } ?>
													</div>
												</div>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Reason</label>
													<div class="col-sm-9">
														<?php $val = isset($ddata17['reason_request']); ?>
														<?php if ($val = null) { ?>
															<textarea class="form-control" name="reason_request" id="reason_request" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																					echo "disabled"; ?> value="<?php echo $ddata17['reason_request'];
																																											} ?>">
								<?php } else { ?>
								<textarea class="form-control" name="reason_request" id="reason_request" rows="3" value = "<?php echo $ddata17['reason_request']; ?>"<?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																											echo "disabled";
																																										} ?> readonly><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																															echo $ddata17['reason_request'];
																																														} ?></textarea>
														<?php } ?>
													</div>
												</div>
												<div class="row mb-3" id="crclosing">
													<h2>Change Request Closing</h2>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Hasil Akhir</label>
														<div class="col-sm-9">
															<?php $val = isset($ddata17['hasil_akhir']); ?>
															<?php if ($val = null) { ?>
																<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																				echo "disabled";
																																			} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																						echo $ddata17['hasil_akhir'];
																																					} ?></textarea>
															<?php } else { ?>
																<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" value="<?php echo $ddata17['hasil_akhir']; ?>" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																echo "disabled";
																																															} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																		echo $ddata17['hasil_akhir'];
																																																	} ?></textarea>
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status CR</label>
														<div class="col-sm-9">
															<?php $val = isset($data[0]['change_request_status']); ?>
															<?php if ($val == null) { ?>
																<select class="form-control" name="status_request" id="status_request">
																	<option value="submission_to_be_reviewed">Incomplete</option>
																	<option value="all_done">Complete</option>
																	<option value="submission_rejected">Rejected</option>
																</select>
															<?php } else { ?>
																<select class="form-control" name="status_request" id="status_request">
																	<option value="<?php echo $data[0]['change_request_status']; ?>"><?php echo $data[0]['change_request_status']; ?></option>
																	<option value="submission_to_be_reviewed">Incomplete</option>
																	<option value="all_done">Complete</option>
																	<option value="submission_rejected">Rejected</option>
																</select>
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm" id="reason_rejected_text">Reason</label>
														<div class="col-sm-9">
															<?php $val = isset($ddata17['reason_rejected']); ?>
															<?php if ($val = null) { ?>
																<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" <?php if ($_GET['act'] == 'review') {
																																						echo "disabled";
																																					} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																								echo $ddata17['reason_rejected'];
																																							} ?></textarea>
															<?php } else { ?>
																<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" value="<?php echo $ddata17['reason_rejected']; ?>" <?php if ($_GET['act'] == 'review') {
																																																			echo "disabled";
																																																		} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																					echo $ddata17['reason_rejected'];
																																																				} ?></textarea>
															<?php } ?>
														</div>
													</div>
												</div>
											<?php } ?>
										<?php } ?>
									<?php } ?>
									<?php if ($_GET['type'] == "IT") { ?>
										<div class="row">
											<label for="inputCID3a" class="col-sm-12 col-form-label col-form-label-sm"><b>Change Control Board : Internal</b></label>
										</div>
										<div class="row mb-3 border-top pt-3">
											<label for="inputCID3a" class="col-sm-3 col-form-label col-form-label-sm" id="pic_text"><b>PIC</b></label>
											<div class="col-sm-9">
												<?php if ($_GET['act'] == 'review') { ?>
													<input type="text" class="form-control form-control-sm" id="pic_apr" name="pic_apr" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																			echo "disabled";
																																		} ?> value="<?php echo $ddata8['name']; ?>">
												<?php } elseif ($_GET['act'] == 'add') { ?>
													<select class="form-control" name="pic_apr" id="pic_apr">
														<option value="<?php echo $dataleaderit[0]["employee_email"]; ?>"><?php echo $dataleaderit[0]["employee_name"] . "/" . $dataleaderit[0]["employee_email"]; ?></option>
													</select>
												<?php } elseif ($_GET['act'] == 'edit') { ?>
													<select class="form-control" name="pic_apr" id="pic_apr">
														<option value="<?php echo $ddata8['name']; ?>"><?php echo $ddata8['name']; ?></option>
													</select>
												<?php } ?>
											</div>
											<div class="col-sm-9 text-right">
											</div>
										</div>
										<div class="row mb-3">
											<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval</label>
											<div class="col-sm-9">
												<?php if ($_GET['act'] == "add") { ?>
													<select class="form-control" name="status_approval2" id="status_approval2" onchange="ChangeCRStatus()">
														<option value="submission_to_be_reviewed">Pending</option>
													</select>
												<?php } else if ($_GET['act'] == "edit" && $ddata['pic_leader'] == $_SESSION['Microservices_UserEmail']) { ?>
													<select class="form-control" name="status_approval2" id="status_approval2" onchange="ChangeCRStatus()">
														<option value="<?php echo $data[0]['change_request_approval_type2']; ?>"><?php echo $data[0]['change_request_approval_type2']; ?></option>
														<option value="submission_approved">Approved</option>
														<option value="submission_rejected">Rejected</option>
													</select>
												<?php } else if ($_GET['act'] == "edit" && $ddata['pic'] == $_SESSION['Microservices_UserEmail']) { ?>
													<select class="form-control" name="status_approval2" id="status_approval2" readonly>
														<option value="<?php echo $data[0]['change_request_approval_type2']; ?>" readonly><?php echo $data[0]['change_request_approval_type2']; ?></option>
													</select>
												<?php } else if ($_GET['act'] == "edit" && $ddata['requested_by_email'] == $_SESSION['Microservices_UserEmail']) { ?>
													<select class="form-control" name="status_approval2" id="status_approval2" readonly>
														<option value="<?php echo $data[0]['change_request_approval_type2']; ?>" readonly><?php echo $data[0]['change_request_approval_type2']; ?></option>
													</select>
												<?php } ?>
												<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
													<input type="text" class="form-control form-control-sm" id="status_approval2" name="status_approval2" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																								echo "disabled";
																																							} ?> value="<?php echo $data[0]['change_request_approval_type2']; ?>">
												<?php } ?>
											</div>
										</div>
										<div class="row mb-3 pt-3 pb-3">
											<label for="inputCID3a" class="col-sm-3 col-form-label col-form-label-sm" id="pic_text"><b>PIC *</b></label>
											<div class="col-sm-9">
												<?php if ($_GET['act'] == 'review') { ?>
													<input type="text" class="form-control form-control-sm" id="pic_apr2" name="pic_apr2" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																				echo "disabled";
																																			} ?> value="<?php echo $ddata['pic']; ?>">
												<?php } elseif ($_GET['act'] == 'add') { ?>
													<select class="form-control" name="pic_apr2" id="pic_apr2" required>
														<option></option>
														<?php do { ?>
															<option value="<?php echo $pic_apr[0]['employee_email']; ?>"><?php echo $pic_apr[0]['employee_name'] . "/" . $pic_apr[0]['employee_email']; ?></option>
														<?php } while ($pic_apr[0] = $pic_apr[1]->fetch_assoc()); ?>
													</select>
												<?php } elseif ($_GET['act'] == 'edit') { ?>
													<select class="form-control" name="pic_apr2" id="pic_apr2" required>
														<label>*</label>
														<option value="<?php echo $ddata['pic']; ?>"><?php echo $ddata['pic']; ?></option>
														<?php do { ?>
															<option value="<?php echo $pic_apr[0]['employee_email']; ?>"><?php echo $pic_apr[0]['employee_name'] . "/" . $pic_apr[0]['employee_email']; ?></option>
														<?php } while ($pic_apr[0] = $pic_apr[1]->fetch_assoc()); ?>
													</select>
												<?php } ?>
											</div>
											<div class="col-sm-9 text-right">
											</div>
										</div>
										<div class="row mb-3">
											<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval</label>
											<div class="col-sm-9">
												<?php if ($_GET['act'] == "add") { ?>
													<select class="form-control" name="status_approval" id="status_approval" onchange="ChangeCRStatus()">
														<option value="submission_to_be_reviewed">Pending</option>
													</select>
												<?php } else if ($_GET['act'] == "edit" && $ddata['pic'] == $_SESSION['Microservices_UserEmail']) { ?>
													<select class="form-control" name="status_approval" id="status_approval">
														<option value="<?php echo $data[0]['change_request_approval_type']; ?>"><?php echo $data[0]['change_request_approval_type']; ?></option>
														<option value="submission_approved">Approved</option>
														<option value="submission_rejected">Rejected</option>
													</select>
												<?php } else if ($_GET['act'] == "edit" && $ddata['pic_leader'] == $_SESSION['Microservices_UserEmail']) { ?>
													<select class="form-control" name="status_approval" id="status_approval" readonly>
														<option value="<?php echo $data[0]['change_request_approval_type']; ?>" readonly><?php echo $data[0]['change_request_approval_type']; ?></option>
													</select>
												<?php } else if ($_GET['act'] == "edit" && $ddata['requested_by_email'] == $_SESSION['Microservices_UserEmail']) { ?>
													<select class="form-control" name="status_approval" id="status_approval" readonly>
														<option value="<?php echo $data[0]['change_request_approval_type']; ?>" readonly><?php echo $data[0]['change_request_approval_type']; ?></option>
													</select>
												<?php } ?>
												<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
													<input type="text" class="form-control form-control-sm" id="status_approval" name="status_approval" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																							echo "disabled";
																																						} ?> value="<?php echo $data[0]['change_request_approval_type']; ?>">
												<?php } ?>
											</div>
										</div>
										<?php if ($_GET['act'] == "add") { ?>
											<div class="row mb-3">
												<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status CR</label>
												<div class="col-sm-9">
													<select class="form-control" name="status_request" id="status_request">
														<option value="submission_to_be_reviewed">Pending</option>
													</select>
												</div>
											</div>
										<?php } else if ($_GET['act'] == "edit" && $ddata['pic'] == $_SESSION['Microservices_UserEmail']) { ?>
											<div class="row mb-3">
												<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Reason</label>
												<div class="col-sm-9">
													<?php $val = isset($ddata17['reason_request']); ?>
													<?php if ($val = null) { ?>
														<textarea class="form-control" name="reason_request" id="reason_request" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																				echo "disabled"; ?> value="<?php echo $ddata17['reason_request'];
																																										} ?>">
								<?php } else { ?>
								<textarea class="form-control" name="reason_request" id="reason_request" rows="3" value = "<?php echo $ddata17['reason_request']; ?>"<?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																											echo "disabled";
																																										} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																													echo $ddata17['reason_request'];
																																												} ?></textarea>
													<?php } ?>
												</div>
											</div>
											<div class="row mb-3" id="crclosing">
												<h2>Change Request Closing</h2>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Hasil Akhir</label>
													<div class="col-sm-9">
														<?php $val = isset($ddata17['hasil_akhir']); ?>
														<?php if ($val = null) { ?>
															<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																			echo "disabled";
																																		} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																					echo $ddata17['hasil_akhir'];
																																				} ?></textarea>
														<?php } else { ?>
															<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" value="<?php echo $ddata17['hasil_akhir']; ?>" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																															echo "disabled";
																																														} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																	echo $ddata17['hasil_akhir'];
																																																} ?></textarea>
														<?php } ?>
													</div>
												</div>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status CR</label>
													<div class="col-sm-9">
														<?php $val = isset($data[0]['change_request_status']); ?>
														<?php if ($val == null) { ?>
															<select class="form-control" name="status_request" id="status_request">
																<option value="submission_to_be_reviewed">Incomplete</option>
																<option value="all_done">Complete</option>
																<option value="submission_rejected">Rejected</option>
															</select>
														<?php } else { ?>
															<select class="form-control" name="status_request" id="status_request">
																<option value="<?php echo $data[0]['change_request_status']; ?>"><?php echo $data[0]['change_request_status']; ?></option>
																<option value="submission_to_be_reviewed">Incomplete</option>
																<option value="all_done">Complete</option>
																<option value="submission_rejected">Rejected</option>
															</select>
														<?php } ?>
														<?php //if ($_GET['act'] == "review") { 
														?>
														<!-- <input type="text" class="form-control form-control-sm" id="status_request" name="status_request" <?php echo "disabled"; ?> value="<?php echo $data[0]['change_request_status']; ?>"> -->
														<?php //} 
														?>
													</div>
												</div>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm" id="reason_rejected_text">Reason</label>
													<div class="col-sm-9">
														<?php $val = isset($ddata17['reason_rejected']); ?>
														<?php if ($val = null) { ?>
															<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" <?php if ($_GET['act'] == 'review') {
																																					echo "disabled";
																																				} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																							echo $ddata17['reason_rejected'];
																																						} ?></textarea>
														<?php } else { ?>
															<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" value="<?php echo $ddata17['reason_rejected']; ?>" <?php if ($_GET['act'] == 'review') {
																																																		echo "disabled";
																																																	} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																				echo $ddata17['reason_rejected'];
																																																			} ?></textarea>
														<?php } ?>
													</div>
												</div>
											</div>
										<?php } else if ($_GET['act'] == "edit" && $ddata['pic_leader'] == $_SESSION['Microservices_UserEmail']) { ?>
											<div class="row mb-3">
												<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Reason</label>
												<div class="col-sm-9">
													<?php $val = isset($ddata17['reason_request']); ?>
													<?php if ($val = null) { ?>
														<textarea class="form-control" name="reason_request" id="reason_request" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																				echo "disabled"; ?> value="<?php echo $ddata17['reason_request'];
																																										} ?>">
								<?php } else { ?>
								<textarea class="form-control" name="reason_request" id="reason_request" rows="3" value = "<?php echo $ddata17['reason_request']; ?>"<?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																											echo "disabled";
																																										} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																													echo $ddata17['reason_request'];
																																												} ?></textarea>
													<?php } ?>
												</div>
											</div>
											<div class="row mb-3" id="crclosing">
												<h2>Change Request Closing</h2>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Hasil Akhir</label>
													<div class="col-sm-9">
														<?php $val = isset($ddata17['hasil_akhir']); ?>
														<?php if ($val = null) { ?>
															<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																			echo "disabled";
																																		} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																					echo $ddata17['hasil_akhir'];
																																				} ?></textarea>
														<?php } else { ?>
															<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" value="<?php echo $ddata17['hasil_akhir']; ?>" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																															echo "disabled";
																																														} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																	echo $ddata17['hasil_akhir'];
																																																} ?></textarea>
														<?php } ?>
													</div>
												</div>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status CR</label>
													<div class="col-sm-9">
														<?php $val = isset($data[0]['change_request_status']); ?>
														<?php if ($val == null) { ?>
															<select class="form-control" name="status_request" id="status_request">
																<option value="submission_to_be_reviewed">Incomplete</option>
																<option value="all_done">Complete</option>
																<option value="submission_rejected">Rejected</option>
															</select>
														<?php } else { ?>
															<select class="form-control" name="status_request" id="status_request">
																<option value="<?php echo $data[0]['change_request_status']; ?>"><?php echo $data[0]['change_request_status']; ?></option>
																<option value="submission_to_be_reviewed">Incomplete</option>
																<option value="all_done">Complete</option>
																<option value="submission_rejected">Rejected</option>
															</select>
														<?php } ?>
														<?php //if ($_GET['act'] == "review") { 
														?>
														<!-- <input type="text" class="form-control form-control-sm" id="status_request" name="status_request" <?php echo "disabled"; ?> value="<?php echo $data[0]['change_request_status']; ?>"> -->
														<?php //} 
														?>
													</div>
												</div>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm" id="reason_rejected_text">Reason</label>
													<div class="col-sm-9">
														<?php $val = isset($ddata17['reason_rejected']); ?>
														<?php if ($val = null) { ?>
															<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" <?php if ($_GET['act'] == 'review') {
																																					echo "disabled";
																																				} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																							echo $ddata17['reason_rejected'];
																																						} ?></textarea>
														<?php } else { ?>
															<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" value="<?php echo $ddata17['reason_rejected']; ?>" <?php if ($_GET['act'] == 'review') {
																																																		echo "disabled";
																																																	} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																				echo $ddata17['reason_rejected'];
																																																			} ?></textarea>
														<?php } ?>
													</div>
												</div>
											</div>
										<?php } else if ($_GET['act'] == "edit" && $ddata['requested_by_email'] == $_SESSION['Microservices_UserEmail']) { ?>
											<?php if ($_GET['status_approval'] == 'submission_to_be_reviewed') { ?>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Reason</label>
													<div class="col-sm-9">
														<?php $val = isset($ddata17['reason_request']); ?>
														<?php if ($val = null) { ?>
															<textarea class="form-control" name="reason_request" id="reason_request" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																					echo "disabled"; ?> value="<?php echo $ddata17['reason_request'];
																																											} ?>">
								<?php } else { ?>
								<textarea class="form-control" name="reason_request" id="reason_request" rows="3" value = "<?php echo $ddata17['reason_request']; ?>"<?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																											echo "disabled";
																																										} ?> readonly><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																															echo $ddata17['reason_request'];
																																														} ?></textarea>
														<?php } ?>
													</div>
												</div>
												<div class="row mb-3" id="crclosing">
													<h2>Change Request Closing</h2>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Hasil Akhir</label>
														<div class="col-sm-9">
															<?php $val = isset($ddata17['hasil_akhir']); ?>
															<?php if ($val = null) { ?>
																<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																				echo "disabled";
																																			} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																						echo $ddata17['hasil_akhir'];
																																					} ?></textarea>
															<?php } else { ?>
																<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" value="<?php echo $ddata17['hasil_akhir']; ?>" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																echo "disabled";
																																															} ?> readonly><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																				echo $ddata17['hasil_akhir'];
																																																			} ?></textarea>
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status CR</label>
														<div class="col-sm-9">
															<?php $val = isset($data[0]['change_request_status']); ?>
															<?php if ($val == null) { ?>
																<select class="form-control" name="status_request" id="status_request">
																	<option value="submission_to_be_reviewed">Incomplete</option>
																</select>
															<?php } else { ?>
																<select class="form-control" name="status_request" id="status_request">
																	<option value="<?php echo $data[0]['change_request_status']; ?>"><?php echo $data[0]['change_request_status']; ?></option>
																</select>
															<?php } ?>
															<?php //if ($_GET['act'] == "review") { 
															?>
															<!-- <input type="text" class="form-control form-control-sm" id="status_request" name="status_request" <?php echo "disabled"; ?> value="<?php echo $data[0]['change_request_status']; ?>"> -->
															<?php //} 
															?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm" id="reason_rejected_text">Reason</label>
														<div class="col-sm-9">
															<?php $val = isset($ddata17['reason_rejected']); ?>
															<?php if ($val = null) { ?>
																<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" <?php if ($_GET['act'] == 'review') {
																																						echo "disabled";
																																					} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																								echo $ddata17['reason_rejected'];
																																							} ?></textarea>
															<?php } else { ?>
																<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" value="<?php echo $ddata17['reason_rejected']; ?>" <?php if ($_GET['act'] == 'review') {
																																																			echo "disabled";
																																																		} ?> readonly><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																							echo $ddata17['reason_rejected'];
																																																						} ?></textarea>
															<?php } ?>
														</div>
													</div>
												</div>
											<?php } else if ($_GET['status_approval'] == "submission_approved") { ?>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Reason</label>
													<div class="col-sm-9">
														<?php $val = isset($ddata17['reason_request']); ?>
														<?php if ($val = null) { ?>
															<textarea class="form-control" name="reason_request" id="reason_request" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																					echo "disabled"; ?> value="<?php echo $ddata17['reason_request'];
																																											} ?>">
								<?php } else { ?>
								<textarea class="form-control" name="reason_request" id="reason_request" rows="3" value = "<?php echo $ddata17['reason_request']; ?>"<?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																											echo "disabled";
																																										} ?> readonly><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																															echo $ddata17['reason_request'];
																																														} ?></textarea>
														<?php } ?>
													</div>
												</div>
												<div class="row mb-3" id="crclosing">
													<h2>Change Request Closing</h2>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Hasil Akhir</label>
														<div class="col-sm-9">
															<?php $val = isset($ddata17['hasil_akhir']); ?>
															<?php if ($val = null) { ?>
																<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																				echo "disabled";
																																			} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																						echo $ddata17['hasil_akhir'];
																																					} ?></textarea>
															<?php } else { ?>
																<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" value="<?php echo $ddata17['hasil_akhir']; ?>" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																echo "disabled";
																																															} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																		echo $ddata17['hasil_akhir'];
																																																	} ?></textarea>
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status CR</label>
														<div class="col-sm-9">
															<?php $val = isset($data[0]['change_request_status']); ?>
															<?php if ($val == null) { ?>
																<select class="form-control" name="status_request" id="status_request">
																	<option value="submission_to_be_reviewed">Incomplete</option>
																	<option value="all_done">Complete</option>
																	<option value="submission_rejected">Rejected</option>
																</select>
															<?php } else { ?>
																<select class="form-control" name="status_request" id="status_request">
																	<option value="<?php echo $data[0]['change_request_status']; ?>"><?php echo $data[0]['change_request_status']; ?></option>
																	<option value="submission_to_be_reviewed">Incomplete</option>
																	<option value="all_done">Complete</option>
																	<option value="submission_rejected">Rejected</option>
																</select>
															<?php } ?>
															<?php //if ($_GET['act'] == "review") { 
															?>
															<!-- <input type="text" class="form-control form-control-sm" id="status_request" name="status_request" <?php echo "disabled"; ?> value="<?php echo $data[0]['change_request_status']; ?>"> -->
															<?php //} 
															?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm" id="reason_rejected_text">Reason</label>
														<div class="col-sm-9">
															<?php $val = isset($ddata17['reason_rejected']); ?>
															<?php if ($val = null) { ?>
																<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" <?php if ($_GET['act'] == 'review') {
																																						echo "disabled";
																																					} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																								echo $ddata17['reason_rejected'];
																																							} ?></textarea>
															<?php } else { ?>
																<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" value="<?php echo $ddata17['reason_rejected']; ?>" <?php if ($_GET['act'] == 'review') {
																																																			echo "disabled";
																																																		} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																					echo $ddata17['reason_rejected'];
																																																				} ?></textarea>
															<?php } ?>
														</div>
													</div>
												</div>
											<?php } ?>
										<?php } ?>
									<?php } else if ($_GET['type'] == "Implementation" || $_GET['type'] == "Maintenance") { ?>
										<?php if ($_GET['costimpact'] == "Financial") { ?>
											<div class="row">
												<label for="inputCID3a" class="col-sm-12 col-form-label col-form-label-sm"><b>Change Control Board : Internal</b></label>
											</div>
											<div class="row mb-3 pt-3 pb-3">
												<label for="inputCID3a" class="col-sm-3 col-form-label col-form-label-sm" id="pic_text"><b>PIC (Expert Support) *</b></label>
												<div class="col-sm-9">
													<?php if ($_GET['act'] == 'review') { ?>
														<input type="text" class="form-control form-control-sm" id="pic_apr" name="pic_apr" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																				echo "disabled";
																																			} ?> value="<?php echo $ddata['pic']; ?>">
													<?php } elseif ($_GET['act'] == 'add') { ?>
														<select class="form-control" name="pic_apr" id="pic_apr" required>
															<option></option>
															<option value="puji.riawan@mastersystem.co.id">Puji Riawan/puji.riawan@mastersystem.co.id</option>
															<?php do { ?>
																<option value="<?php echo $check_approval[0]['employee_email']; ?>"><?php echo $check_approval[0]['employee_name'] . "/" . $check_approval[0]['employee_email']; ?></option>
															<?php } while ($check_approval[0] = $check_approval[1]->fetch_assoc()); ?>
														</select>
													<?php } elseif ($_GET['act'] == 'edit') { ?>
														<select class="form-control" name="pic_apr" id="pic_apr" required>
															<label>*</label>
															<option value="<?php echo $ddata['pic']; ?>"><?php echo $ddata['pic']; ?></option>
															<?php do { ?>
																<option value="<?php echo $check_approval[0]['employee_email']; ?>"><?php echo $check_approval[0]['employee_name'] . "<" . $check_approval[0]['employee_email'] . ">"; ?></option>
															<?php } while ($check_approval[0] = $check_approval[1]->fetch_assoc()); ?>
														</select>
													<?php } ?>
												</div>
												<div class="col-sm-9 text-right">
												</div>
											</div>
											<?php if ($_GET['act'] == 'add') { ?>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval (Expert Support)</label>
													<div class="col-sm-9">
														<select class="form-control" name="status_approval" id="status_approval" onchange="ChangeCRStatus()">
															<option value="submission_to_be_reviewed">Pending</option>
														</select>
														<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
															<input type="text" class="form-control form-control-sm" id="status_approval" name="status_approval" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																									echo "disabled";
																																								} ?> value="<?php echo $data[0]['change_request_approval_type']; ?>">
														<?php } ?>
													</div>
												</div>
											<?php } ?>
											<div class="row mb-3 pt-3 pb-3">
												<label for="inputCID3a" class="col-sm-3 col-form-label col-form-label-sm" id="pic_text"><b>PIC (GM Sales)*</b></label>
												<div class="col-sm-9">
													<?php if ($_GET['act'] == 'review') { ?>
														<input type="text" class="form-control form-control-sm" id="pic_apr2" name="pic_apr2" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																					echo "disabled";
																																				} ?> value="<?php echo $ddata['pic_sales']; ?>">
													<?php } elseif ($_GET['act'] == 'add') { ?>
														<?php if (isset($get_gm[0]['leader_email'])) { ?>
															<select class="form-control" name="pic_apr2" id="pic_apr2" required>
																<!-- <option value="raka.putra@mastersystem.co.id">Raka Putra/raka.putra@mastersystem.co.id</option> -->
																<option value="<?php echo $get_gm[0]['leader_email']; ?>"><?php echo $get_gm[0]['leader_name'] . "/" . $get_gm[0]['leader_email']; ?></option>
																<option value="Enghok@mastersystem.co.id">Thio Eng Hok/Enghok@mastersystem.co.id (BOD)</option>
																<option value="Eddy.Anthony@mastersystem.co.id">Eddy Anthony/Eddy.Anthony@mastersystem.co.id (BOD)</option>
															</select>
														<?php } else { ?>
															<select class="form-control" name="pic_apr2" id="pic_apr2" required>
																<option>Silahkan isi SO yang terdaftar pada SB</option>
															</select>
														<?php } ?>
													<?php } elseif ($_GET['act'] == 'edit') { ?>
														<select class="form-control" name="pic_apr2" id="pic_apr2" required>
															<option value="<?php echo $ddata['pic_sales']; ?>"><?php echo $ddata['pic_sales']; ?></option>
														</select>
													<?php } ?>
												</div>
												<div class="col-sm-9 text-right">
												</div>
											</div>
											<?php if ($_GET['act'] == 'add') { ?>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval (GM Sales)</label>
													<div class="col-sm-9">
														<select class="form-control" name="status_approval_sales" id="status_approval_sales" onchange="ChangeCRStatus()">
															<option value="submission_to_be_reviewed">Pending</option>
														</select>
														<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
															<input type="text" class="form-control form-control-sm" id="status_approval_sales" name="status_approval_sales" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																												echo "disabled";
																																											} ?> value="<?php echo $data[0]['CR_status_approval_sales']; ?>">
														<?php } ?>
													</div>
												</div>
											<?php } ?>
											<div class="row mb-3 pt-3 pb-3">
												<label for="inputCID3a" class="col-sm-3 col-form-label col-form-label-sm" id="pic_text"><b>PIC (GM Expert Support) *</b><i class="fas fa-question-circle" data-bs-toggle="popover" data-bs-trigger="focus" title="Jika Approval ditujukan untuk GM Expert Support maka field ini tidak usah diganti, namun jika butuh untuk level GM keatas bisa memilih salah satu nama BOD yang telah disediakan."></i></label>
												<div class="col-sm-9">
													<?php if ($_GET['act'] == 'review') { ?>
														<input type="text" class="form-control form-control-sm" id="pic_apr3" name="pic_apr3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																					echo "disabled";
																																				} ?> value="<?php echo $ddata['pic_leader']; ?>">
													<?php } elseif ($_GET['act'] == 'add') { ?>
														<select class="form-control" name="pic_apr3" id="pic_apr3">
															<option value="GM Expert Support">GM Expert Support</option>
															<option value="Raymon@mastersystem.co.id">Raymon Budi Citra/Raymon@mastersystem.co.id (BOD)</option>
															<option value="lintar@mastersystem.co.id">Moch. Lintar Wahyu Wardana/lintar@mastersystem.co.id (BOD)</option>
															<option value="joko@mastersystem.co.id">Joko Gunawan/joko@mastersystem.co.id (BOD)</option>
														</select>
													<?php } elseif ($_GET['act'] == 'edit') { ?>
														<select class="form-control" name="pic_apr3" id="pic_apr3" required>
															<option value="<?php echo $ddata['pic_leader']; ?>"><?php echo $ddata['pic_leader']; ?></option>
														</select>
													<?php } ?>
												</div>
												<div class="col-sm-9 text-right">
												</div>
											</div>
											<?php if ($_GET['act'] == 'add') { ?>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval (GM Expert Support)</label>
													<div class="col-sm-9">
														<select class="form-control" name="status_approval2" id="status_approval2" onchange="ChangeCRStatus()">
															<option value="submission_to_be_reviewed">Pending</option>
														</select>
														<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
															<input type="text" class="form-control form-control-sm" id="status_approval2" name="status_approval2" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																										echo "disabled";
																																									} ?> value="<?php echo $data[0]['change_request_approval_type2']; ?>">
														<?php } ?>
													</div>
												</div>
											<?php } ?>
											<?php if ($_GET['type'] == "Implementation") { ?>
												<div class="row mb-3 pt-3 pb-3">
													<label for="inputCID3a" class="col-sm-3 col-form-label col-form-label-sm" id="pic_text"><b>PIC (GM PMO) *</b></label>
													<div class="col-sm-9">
														<?php if ($_GET['act'] == 'review') { ?>
															<input type="text" class="form-control form-control-sm" id="pic_apr4" name="pic_apr4" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																						echo "disabled";
																																					} ?> value="<?php echo $ddata['pic_pmo']; ?>">
														<?php } elseif ($_GET['act'] == 'add') { ?>
															<select class="form-control" name="pic_apr4" id="pic_apr4" required>
																<!-- <option value="matias.jepri@mastersystem.co.id">Matias Jepri/matias.jepri@mastersystem.co.id</option> -->
																<option value="fortuna@mastersystem.co.id">Fortuna Setyo Arumsari/fortuna@mastersystem.co.id</option>
																<option value="sumarno@mastersystem.co.id">Sumarno/sumarno@mastersystem.co.id</option>
																<option value="pitasari.amanda@mastersystem.co.id">Pitasari Amanda/pitasari.amanda@mastersystem.co.id</option>
																<option value="joko@mastersystem.co.id">Joko Gunawan/joko@mastersystem.co.id</option>
															</select>
														<?php } elseif ($_GET['act'] == 'edit') { ?>
															<select class="form-control" name="pic_apr4" id="pic_apr4" required>
																<option value="<?php echo $ddata['pic_pmo']; ?>"><?php echo $ddata['pic_pmo']; ?></option>
															</select>
														<?php } ?>
													</div>
													<div class="col-sm-9 text-right">
													</div>
												</div>
											<?php } ?>
											<?php if ($_GET['type'] == "Maintenance") { ?>
												<div class="row mb-3 pt-3 pb-3">
													<label for="inputCID3a" class="col-sm-3 col-form-label col-form-label-sm" id="pic_text"><b>PIC (PMO) *</b></label>
													<div class="col-sm-9">
														<?php if ($_GET['act'] == 'review') { ?>
															<input type="text" class="form-control form-control-sm" id="pic_apr4" name="pic_apr4" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																						echo "disabled";
																																					} ?> value="<?php echo $ddata['pic_pmo']; ?>">
														<?php } elseif ($_GET['act'] == 'add') { ?>
															<select class="form-control" name="pic_apr4" id="pic_apr4" required>
																<option value="anggi.fachrizal@mastersystem.co.id">Anggi Fachrizal/anggi.fachrizal@mastersystem.co.id</option>
																<option value="andino.hf@mastersystem.co.id">Andino Holi Fajra/andino.hf@mastersystem.co.id</option>
																<option value="aceng.zakariya@mastersystem.co.id">Aceng Zakariya Ramadani/aceng.zakariya@mastersystem.co.id</option>
																<option value="iwan@mastersystem.co.id">Iwan Rusmin/iwan@mastersystem.co.id</option>
															</select>
														<?php } elseif ($_GET['act'] == 'edit') { ?>
															<select class="form-control" name="pic_apr4" id="pic_apr4" required>
																<option value="<?php echo $ddata['pic_pmo']; ?>"><?php echo $ddata['pic_pmo']; ?></option>
															</select>
														<?php } ?>
													</div>
													<div class="col-sm-9 text-right">
													</div>
												</div>
											<?php } ?>
											<?php if ($_GET['act'] == 'add') { ?>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval (GM PMO)</label>
													<div class="col-sm-9">
														<select class="form-control" name="status_approval_pmo" id="status_approval_pmo" onchange="ChangeCRStatus()">
															<option value="submission_to_be_reviewed">Pending</option>
														</select>
														<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
															<input type="text" class="form-control form-control-sm" id="status_approval_pmo" name="status_approval_pmo" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																											echo "disabled";
																																										} ?> value="<?php echo $data[0]['CR_status_approval_pmo']; ?>">
														<?php } ?>
													</div>
												</div>
											<?php } ?>
											<div class="row mb-3 pt-3 pb-3">
												<label for="inputCID3a" class="col-sm-3 col-form-label col-form-label-sm" id="pic_text"><b>PIC (GM Presales) *</b></label>
												<div class="col-sm-9">
													<?php if ($_GET['act'] == 'review') { ?>
														<input type="text" class="form-control form-control-sm" id="pic_apr5" name="pic_apr5" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																					echo "disabled";
																																				} ?> value="<?php echo $ddata['pic_presales']; ?>">
													<?php } elseif ($_GET['act'] == 'add') { ?>
														<select class="form-control" name="pic_apr5" id="pic_apr5" required>
															<!-- <option value="matias.jepri@mastersystem.co.id">Matias Jepri/matias.jepri@mastersystem.co.id</option> -->
															<option value="Raymon@mastersystem.co.id">Raymon Budi Citra/Raymon@mastersystem.co.id</option>
															<option value="lintar@mastersystem.co.id">Moch. Lintar Wahyu Wardana/lintar@mastersystem.co.id</option>
															<?php do { ?>
																<option value="<?php echo $approval_presale[0]['employee_email']; ?>"><?php echo $approval_presale[0]['employee_name'] . "/" . $approval_presale[0]['employee_email']; ?></option>
															<?php } while ($approval_presale[0] = $approval_presale[1]->fetch_assoc()); ?>
														</select>
													<?php } elseif ($_GET['act'] == 'edit') { ?>
														<select class="form-control" name="pic_apr5" id="pic_apr5" required>
															<option value="<?php echo $ddata['pic_presales']; ?>"><?php echo $ddata['pic_presales']; ?></option>
															<?php do { ?>
																<option value="<?php echo $approval_presale[0]['employee_email']; ?>"><?php echo $approval_presale[0]['employee_name'] . "/" . $approval_presale[0]['employee_email']; ?></option>
															<?php } while ($approval_presale[0] = $approval_presale[1]->fetch_assoc()); ?>
														</select>
													<?php } ?>
												</div>
												<div class="col-sm-9 text-right">
												</div>
											</div>
											<?php if ($_GET['act'] == 'add') { ?>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval (GM Presales)</label>
													<div class="col-sm-9">
														<select class="form-control" name="status_approval_presales" id="status_approval_presales" onchange="ChangeCRStatus()">
															<option value="submission_to_be_reviewed">Pending</option>
														</select>
														<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
															<input type="text" class="form-control form-control-sm" id="status_approval_presales" name="status_approval_presales" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																														echo "disabled";
																																													} ?> value="<?php echo $data[0]['CR_status_approval_presales']; ?>">
														<?php } ?>
													</div>
												</div>
											<?php } ?>
											<?php if ($_GET['act'] == "add") { ?>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status CR</label>
													<div class="col-sm-9">
														<select class="form-control" name="status_request" id="status_request">
															<option value="submission_to_be_reviewed">Pending</option>
														</select>
													</div>
												</div>
											<?php } else if ($_GET['act'] == "edit" && $ddata['pic'] == $_SESSION['Microservices_UserEmail']) { ?>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval</label>
													<div class="col-sm-9">
														<?php $val = isset($data[0]['change_request_approval_type']); ?>
														<?php if ($val = null) { ?>
															<select class="form-control" name="status_approval" id="status_approval" onchange="ChangeCRStatus()">
																<option value="submission_approved">Approved</option>
																<option value="submission_rejected">Rejected</option>
															</select>
														<?php } else { ?>
															<select class="form-control" name="status_approval" id="status_approval" onchange="ChangeCRStatus()">
																<option value="<?php echo $data[0]['change_request_approval_type']; ?>"><?php echo $data[0]['change_request_approval_type']; ?></option>
																<option value="submission_approved">Approved</option>
																<option value="submission_rejected">Rejected</option>
															</select>
														<?php } ?>
														<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
															<input type="text" class="form-control form-control-sm" id="status_approval" name="status_approval" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																									echo "disabled";
																																								} ?> value="<?php echo $data[0]['change_request_approval_type']; ?>">
														<?php } ?>
													</div>
												</div>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Reason</label>
													<div class="col-sm-9">
														<?php $val = isset($ddata17['reason_request']); ?>
														<?php if ($val = null) { ?>
															<textarea class="form-control" name="reason_request" id="reason_request" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																					echo "disabled"; ?> value="<?php echo $ddata17['reason_request'];
																																											} ?>">
								<?php } else { ?>
								<textarea class="form-control" name="reason_request" id="reason_request" rows="3" value = "<?php echo $ddata17['reason_request']; ?>"<?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																											echo "disabled";
																																										} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																													echo $ddata17['reason_request'];
																																												} ?></textarea>
														<?php } ?>
													</div>
												</div>
												<div class="row mb-3" id="crclosing">
													<h2>Change Request Closing</h2>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Hasil Akhir</label>
														<div class="col-sm-9">
															<?php $val = isset($ddata17['hasil_akhir']); ?>
															<?php if ($val = null) { ?>
																<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																				echo "disabled";
																																			} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																						echo $ddata17['hasil_akhir'];
																																					} ?></textarea>
															<?php } else { ?>
																<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" value="<?php echo $ddata17['hasil_akhir']; ?>" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																echo "disabled";
																																															} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																		echo $ddata17['hasil_akhir'];
																																																	} ?></textarea>
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status CR</label>
														<div class="col-sm-9">
															<?php $val = isset($data[0]['change_request_status']); ?>
															<?php if ($val == null) { ?>
																<select class="form-control" name="status_request" id="status_request">
																	<option value="submission_to_be_reviewed">Incomplete</option>
																	<option value="all_done">Complete</option>
																	<option value="submission_rejected">Rejected</option>
																</select>
															<?php } else { ?>
																<select class="form-control" name="status_request" id="status_request">
																	<option value="<?php echo $data[0]['change_request_status']; ?>"><?php echo $data[0]['change_request_status']; ?></option>
																	<option value="submission_to_be_reviewed">Incomplete</option>
																	<option value="all_done">Complete</option>
																	<option value="submission_rejected">Rejected</option>
																</select>
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm" id="reason_rejected_text">Reason</label>
														<div class="col-sm-9">
															<?php $val = isset($ddata17['reason_rejected']); ?>
															<?php if ($val = null) { ?>
																<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" <?php if ($_GET['act'] == 'review') {
																																						echo "disabled";
																																					} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																								echo $ddata17['reason_rejected'];
																																							} ?></textarea>
															<?php } else { ?>
																<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" value="<?php echo $ddata17['reason_rejected']; ?>" <?php if ($_GET['act'] == 'review') {
																																																			echo "disabled";
																																																		} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																					echo $ddata17['reason_rejected'];
																																																				} ?></textarea>
															<?php } ?>
														</div>
													</div>
												</div>
											<?php } else if ($_GET['act'] == "edit" && $ddata['pic_leader'] == $_SESSION['Microservices_UserEmail']) { ?>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval</label>
													<div class="col-sm-9">
														<?php $val = isset($data[0]['change_request_approval_type2']); ?>
														<?php if ($val = null) { ?>
															<select class="form-control" name="status_approval2" id="status_approval2" onchange="ChangeCRStatus()">
																<option value="submission_approved">Approved</option>
																<option value="submission_rejected">Rejected</option>
															</select>
														<?php } else { ?>
															<select class="form-control" name="status_approval2" id="status_approval2" onchange="ChangeCRStatus()">
																<option value="<?php echo $data[0]['change_request_approval_type2']; ?>"><?php echo $data[0]['change_request_approval_type2']; ?></option>
																<option value="submission_approved">Approved</option>
																<option value="submission_rejected">Rejected</option>
															</select>
														<?php } ?>
														<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
															<input type="text" class="form-control form-control-sm" id="status_approval2" name="status_approval2" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																										echo "disabled";
																																									} ?> value="<?php echo $data[0]['change_request_approval_type2']; ?>">
														<?php } ?>
													</div>
												</div>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Reason</label>
													<div class="col-sm-9">
														<?php $val = isset($ddata17['reason_request']); ?>
														<?php if ($val = null) { ?>
															<textarea class="form-control" name="reason_request" id="reason_request" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																					echo "disabled"; ?> value="<?php echo $ddata17['reason_request'];
																																											} ?>">
								<?php } else { ?>
								<textarea class="form-control" name="reason_request" id="reason_request" rows="3" value = "<?php echo $ddata17['reason_request']; ?>"<?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																											echo "disabled";
																																										} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																													echo $ddata17['reason_request'];
																																												} ?></textarea>
														<?php } ?>
													</div>
												</div>
												<div class="row mb-3" id="crclosing">
													<h2>Change Request Closing</h2>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Hasil Akhir</label>
														<div class="col-sm-9">
															<?php $val = isset($ddata17['hasil_akhir']); ?>
															<?php if ($val = null) { ?>
																<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																				echo "disabled";
																																			} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																						echo $ddata17['hasil_akhir'];
																																					} ?></textarea>
															<?php } else { ?>
																<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" value="<?php echo $ddata17['hasil_akhir']; ?>" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																echo "disabled";
																																															} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																		echo $ddata17['hasil_akhir'];
																																																	} ?></textarea>
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status CR</label>
														<div class="col-sm-9">
															<?php $val = isset($data[0]['change_request_status']); ?>
															<?php if ($val == null) { ?>
																<select class="form-control" name="status_request" id="status_request">
																	<option value="submission_to_be_reviewed">Incomplete</option>
																	<option value="all_done">Complete</option>
																	<option value="submission_rejected">Rejected</option>
																</select>
															<?php } else { ?>
																<select class="form-control" name="status_request" id="status_request">
																	<option value="<?php echo $data[0]['change_request_status']; ?>"><?php echo $data[0]['change_request_status']; ?></option>
																	<option value="submission_to_be_reviewed">Incomplete</option>
																	<option value="all_done">Complete</option>
																	<option value="submission_rejected">Rejected</option>
																</select>
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm" id="reason_rejected_text">Reason</label>
														<div class="col-sm-9">
															<?php $val = isset($ddata17['reason_rejected']); ?>
															<?php if ($val = null) { ?>
																<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" <?php if ($_GET['act'] == 'review') {
																																						echo "disabled";
																																					} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																								echo $ddata17['reason_rejected'];
																																							} ?></textarea>
															<?php } else { ?>
																<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" value="<?php echo $ddata17['reason_rejected']; ?>" <?php if ($_GET['act'] == 'review') {
																																																			echo "disabled";
																																																		} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																					echo $ddata17['reason_rejected'];
																																																				} ?></textarea>
															<?php } ?>
														</div>
													</div>
												</div>
											<?php } else if ($_GET['act'] == "edit" && $ddata['pic_sales'] == $_SESSION['Microservices_UserEmail']) { ?>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval</label>
													<div class="col-sm-9">
														<?php $val = isset($data[0]['CR_status_approval_sales']); ?>
														<?php if ($val = null) { ?>
															<select class="form-control" name="status_approval_sales" id="status_approval_sales" onchange="ChangeCRStatus()">
																<option value="submission_approved">Approved</option>
																<option value="submission_rejected">Rejected</option>
															</select>
														<?php } else { ?>
															<select class="form-control" name="status_approval_sales" id="status_approval_sales" onchange="ChangeCRStatus()">
																<option value="<?php echo $data[0]['CR_status_approval_sales']; ?>"><?php echo $data[0]['CR_status_approval_sales']; ?></option>
																<option value="submission_approved">Approved</option>
																<option value="submission_rejected">Rejected</option>
															</select>
														<?php } ?>
														<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
															<input type="text" class="form-control form-control-sm" id="status_approval_sales" name="status_approval_sales" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																												echo "disabled";
																																											} ?> value="<?php echo $data[0]['CR_status_approval_sales']; ?>">
														<?php } ?>
													</div>
												</div>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Reason</label>
													<div class="col-sm-9">
														<?php $val = isset($ddata17['reason_request']); ?>
														<?php if ($val = null) { ?>
															<textarea class="form-control" name="reason_request" id="reason_request" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																					echo "disabled"; ?> value="<?php echo $ddata17['reason_request'];
																																											} ?>">
								<?php } else { ?>
								<textarea class="form-control" name="reason_request" id="reason_request" rows="3" value = "<?php echo $ddata17['reason_request']; ?>"<?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																											echo "disabled";
																																										} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																													echo $ddata17['reason_request'];
																																												} ?></textarea>
														<?php } ?>
													</div>
												</div>
												<div class="row mb-3" id="crclosing">
													<h2>Change Request Closing</h2>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Hasil Akhir</label>
														<div class="col-sm-9">
															<?php $val = isset($ddata17['hasil_akhir']); ?>
															<?php if ($val = null) { ?>
																<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																				echo "disabled";
																																			} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																						echo $ddata17['hasil_akhir'];
																																					} ?></textarea>
															<?php } else { ?>
																<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" value="<?php echo $ddata17['hasil_akhir']; ?>" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																echo "disabled";
																																															} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																		echo $ddata17['hasil_akhir'];
																																																	} ?></textarea>
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status CR</label>
														<div class="col-sm-9">
															<?php $val = isset($data[0]['change_request_status']); ?>
															<?php if ($val == null) { ?>
																<select class="form-control" name="status_request" id="status_request">
																	<option value="submission_to_be_reviewed">Incomplete</option>
																	<option value="all_done">Complete</option>
																	<option value="submission_rejected">Rejected</option>
																</select>
															<?php } else { ?>
																<select class="form-control" name="status_request" id="status_request">
																	<option value="<?php echo $data[0]['change_request_status']; ?>"><?php echo $data[0]['change_request_status']; ?></option>
																	<option value="submission_to_be_reviewed">Incomplete</option>
																	<option value="all_done">Complete</option>
																	<option value="submission_rejected">Rejected</option>
																</select>
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm" id="reason_rejected_text">Reason</label>
														<div class="col-sm-9">
															<?php $val = isset($ddata17['reason_rejected']); ?>
															<?php if ($val = null) { ?>
																<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" <?php if ($_GET['act'] == 'review') {
																																						echo "disabled";
																																					} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																								echo $ddata17['reason_rejected'];
																																							} ?></textarea>
															<?php } else { ?>
																<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" value="<?php echo $ddata17['reason_rejected']; ?>" <?php if ($_GET['act'] == 'review') {
																																																			echo "disabled";
																																																		} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																					echo $ddata17['reason_rejected'];
																																																				} ?></textarea>
															<?php } ?>
														</div>
													</div>
												</div>
											<?php } else if ($_GET['act'] == "edit" && $ddata['pic_pmo'] == $_SESSION['Microservices_UserEmail']) { ?>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval</label>
													<div class="col-sm-9">
														<?php $val = isset($data[0]['CR_status_approval_pmo']); ?>
														<?php if ($val = null) { ?>
															<select class="form-control" name="status_approval_pmo" id="status_approval_pmo" onchange="ChangeCRStatus()">
																<option value="submission_approved">Approved</option>
																<option value="submission_rejected">Rejected</option>
															</select>
														<?php } else { ?>
															<select class="form-control" name="status_approval_pmo" id="status_approval_pmo" onchange="ChangeCRStatus()">
																<option value="<?php echo $data[0]['CR_status_approval_pmo']; ?>"><?php echo $data[0]['CR_status_approval_pmo']; ?></option>
																<option value="submission_approved">Approved</option>
																<option value="submission_rejected">Rejected</option>
															</select>
														<?php } ?>
														<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
															<input type="text" class="form-control form-control-sm" id="status_approval_pmo" name="status_approval_pmo" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																											echo "disabled";
																																										} ?> value="<?php echo $data[0]['CR_status_approval_pmo']; ?>">
														<?php } ?>
													</div>
												</div>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Reason</label>
													<div class="col-sm-9">
														<?php $val = isset($ddata17['reason_request']); ?>
														<?php if ($val = null) { ?>
															<textarea class="form-control" name="reason_request" id="reason_request" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																					echo "disabled"; ?> value="<?php echo $ddata17['reason_request'];
																																											} ?>">
								<?php } else { ?>
								<textarea class="form-control" name="reason_request" id="reason_request" rows="3" value = "<?php echo $ddata17['reason_request']; ?>"<?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																											echo "disabled";
																																										} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																													echo $ddata17['reason_request'];
																																												} ?></textarea>
														<?php } ?>
													</div>
												</div>
												<div class="row mb-3" id="crclosing">
													<h2>Change Request Closing</h2>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Hasil Akhir</label>
														<div class="col-sm-9">
															<?php $val = isset($ddata17['hasil_akhir']); ?>
															<?php if ($val = null) { ?>
																<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																				echo "disabled";
																																			} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																						echo $ddata17['hasil_akhir'];
																																					} ?></textarea>
															<?php } else { ?>
																<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" value="<?php echo $ddata17['hasil_akhir']; ?>" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																echo "disabled";
																																															} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																		echo $ddata17['hasil_akhir'];
																																																	} ?></textarea>
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status CR</label>
														<div class="col-sm-9">
															<?php $val = isset($data[0]['change_request_status']); ?>
															<?php if ($val == null) { ?>
																<select class="form-control" name="status_request" id="status_request">
																	<option value="submission_to_be_reviewed">Incomplete</option>
																	<option value="all_done">Complete</option>
																	<option value="submission_rejected">Rejected</option>
																</select>
															<?php } else { ?>
																<select class="form-control" name="status_request" id="status_request">
																	<option value="<?php echo $data[0]['change_request_status']; ?>"><?php echo $data[0]['change_request_status']; ?></option>
																	<option value="submission_to_be_reviewed">Incomplete</option>
																	<option value="all_done">Complete</option>
																	<option value="submission_rejected">Rejected</option>
																</select>
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm" id="reason_rejected_text">Reason</label>
														<div class="col-sm-9">
															<?php $val = isset($ddata17['reason_rejected']); ?>
															<?php if ($val = null) { ?>
																<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" <?php if ($_GET['act'] == 'review') {
																																						echo "disabled";
																																					} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																								echo $ddata17['reason_rejected'];
																																							} ?></textarea>
															<?php } else { ?>
																<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" value="<?php echo $ddata17['reason_rejected']; ?>" <?php if ($_GET['act'] == 'review') {
																																																			echo "disabled";
																																																		} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																					echo $ddata17['reason_rejected'];
																																																				} ?></textarea>
															<?php } ?>
														</div>
													</div>
												</div>
											<?php } else if ($_GET['act'] == "edit" && $ddata['pic_presales'] == $_SESSION['Microservices_UserEmail']) { ?>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval</label>
													<div class="col-sm-9">
														<?php $val = isset($data[0]['CR_status_approval_presales']); ?>
														<?php if ($val = null) { ?>
															<select class="form-control" name="status_approval_presales" id="status_approval_presales" onchange="ChangeCRStatus()">
																<option value="submission_approved">Approved</option>
																<option value="submission_rejected">Rejected</option>
															</select>
														<?php } else { ?>
															<select class="form-control" name="status_approval_presales" id="status_approval_presales" onchange="ChangeCRStatus()">
																<option value="<?php echo $data[0]['CR_status_approval_presales']; ?>"><?php echo $data[0]['CR_status_approval_presales']; ?></option>
																<option value="submission_approved">Approved</option>
																<option value="submission_rejected">Rejected</option>
															</select>
														<?php } ?>
														<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
															<input type="text" class="form-control form-control-sm" id="status_approval_presales" name="status_approval_presales" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																														echo "disabled";
																																													} ?> value="<?php echo $data[0]['CR_status_approval_presales']; ?>">
														<?php } ?>
													</div>
												</div>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Reason</label>
													<div class="col-sm-9">
														<?php $val = isset($ddata17['reason_request']); ?>
														<?php if ($val = null) { ?>
															<textarea class="form-control" name="reason_request" id="reason_request" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																					echo "disabled"; ?> value="<?php echo $ddata17['reason_request'];
																																											} ?>">
								<?php } else { ?>
								<textarea class="form-control" name="reason_request" id="reason_request" rows="3" value = "<?php echo $ddata17['reason_request']; ?>"<?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																											echo "disabled";
																																										} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																													echo $ddata17['reason_request'];
																																												} ?></textarea>
														<?php } ?>
													</div>
												</div>
												<div class="row mb-3" id="crclosing">
													<h2>Change Request Closing</h2>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Hasil Akhir</label>
														<div class="col-sm-9">
															<?php $val = isset($ddata17['hasil_akhir']); ?>
															<?php if ($val = null) { ?>
																<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																				echo "disabled";
																																			} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																						echo $ddata17['hasil_akhir'];
																																					} ?></textarea>
															<?php } else { ?>
																<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" value="<?php echo $ddata17['hasil_akhir']; ?>" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																echo "disabled";
																																															} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																		echo $ddata17['hasil_akhir'];
																																																	} ?></textarea>
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status CR</label>
														<div class="col-sm-9">
															<?php $val = isset($data[0]['change_request_status']); ?>
															<?php if ($val == null) { ?>
																<select class="form-control" name="status_request" id="status_request">
																	<option value="submission_to_be_reviewed">Incomplete</option>
																	<option value="all_done">Complete</option>
																	<option value="submission_rejected">Rejected</option>
																</select>
															<?php } else { ?>
																<select class="form-control" name="status_request" id="status_request">
																	<option value="<?php echo $data[0]['change_request_status']; ?>"><?php echo $data[0]['change_request_status']; ?></option>
																	<option value="submission_to_be_reviewed">Incomplete</option>
																	<option value="all_done">Complete</option>
																	<option value="submission_rejected">Rejected</option>
																</select>
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm" id="reason_rejected_text">Reason</label>
														<div class="col-sm-9">
															<?php $val = isset($ddata17['reason_rejected']); ?>
															<?php if ($val = null) { ?>
																<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" <?php if ($_GET['act'] == 'review') {
																																						echo "disabled";
																																					} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																								echo $ddata17['reason_rejected'];
																																							} ?></textarea>
															<?php } else { ?>
																<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" value="<?php echo $ddata17['reason_rejected']; ?>" <?php if ($_GET['act'] == 'review') {
																																																			echo "disabled";
																																																		} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																					echo $ddata17['reason_rejected'];
																																																				} ?></textarea>
															<?php } ?>
														</div>
													</div>
												</div>
											<?php } else if ($_GET['act'] == "edit" && $ddata['requested_by_email'] == $_SESSION['Microservices_UserEmail']) { ?>
												<?php if ($_GET['status_approval'] == 'submission_to_be_reviewed') { ?>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval (Expert Support)</label>
														<div class="col-sm-9">
															<?php $val = isset($data[0]['change_request_approval_type']); ?>
															<?php if ($val = null) { ?>
																<select class="form-control" name="status_approval" id="status_approval">
																	<option value="submission_to_be_reviewed">Pending</option>
																</select>
															<?php } else { ?>
																<select class="form-control" name="status_approval" id="status_approval">
																	<option value="<?php echo $data[0]['change_request_approval_type']; ?>"><?php echo $data[0]['change_request_approval_type']; ?></option>
																</select>
															<?php } ?>
															<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
																<input type="text" class="form-control form-control-sm" id="status_approval" name="status_approval" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																										echo "disabled";
																																									} ?> value="<?php echo $data[0]['change_request_approval_type']; ?>">
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval (GM Sales)</label>
														<div class="col-sm-9">
															<?php $val2 = isset($data[0]['CR_status_approval_sales']); ?>
															<?php if ($val2 = null) { ?>
																<select class="form-control" name="status_approval_sales" id="status_approval_sales">
																	<option value="submission_to_be_reviewed">Pending</option>
																</select>
															<?php } else { ?>
																<select class="form-control" name="status_approval_sales" id="status_approval_sales">
																	<option value="<?php echo $data[0]['CR_status_approval_sales']; ?>"><?php echo $data[0]['CR_status_approval_sales']; ?></option>
																</select>
															<?php } ?>
															<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
																<input type="text" class="form-control form-control-sm" id="status_approval_sales" name="status_approval_sales" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																													echo "disabled";
																																												} ?> value="<?php echo $data[0]['CR_status_approval_sales']; ?>">
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval (GM Expert Support)</label>
														<div class="col-sm-9">
															<?php $val3 = isset($data[0]['change_request_approval_type2']); ?>
															<?php if ($val3 = null) { ?>
																<select class="form-control" name="status_approval2" id="status_approval2">
																	<option value="submission_to_be_reviewed">Pending</option>
																</select>
															<?php } else { ?>
																<select class="form-control" name="status_approval2" id="status_approval2">
																	<option value="<?php echo $data[0]['change_request_approval_type2']; ?>"><?php echo $data[0]['change_request_approval_type2']; ?></option>
																</select>
															<?php } ?>
															<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
																<input type="text" class="form-control form-control-sm" id="status_approval2" name="status_approval2" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																											echo "disabled";
																																										} ?> value="<?php echo $data[0]['change_request_approval_type2']; ?>">
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval (GM PMO)</label>
														<div class="col-sm-9">
															<?php $val4 = isset($data[0]['CR_status_approval_pmo']); ?>
															<?php if ($val4 = null) { ?>
																<select class="form-control" name="status_approval_pmo" id="status_approval_pmo">
																	<option value="submission_to_be_reviewed">Pending</option>
																</select>
															<?php } else { ?>
																<select class="form-control" name="status_approval_pmo" id="status_approval_pmo">
																	<option value="<?php echo $data[0]['CR_status_approval_pmo']; ?>"><?php echo $data[0]['CR_status_approval_pmo']; ?></option>
																</select>
															<?php } ?>
															<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
																<input type="text" class="form-control form-control-sm" id="status_approval_pmo" name="status_approval_pmo" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																												echo "disabled";
																																											} ?> value="<?php echo $data[0]['CR_status_approval_pmo']; ?>">
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval (GM Presales)</label>
														<div class="col-sm-9">
															<?php $val5 = isset($data[0]['CR_status_approval_presales']); ?>
															<?php if ($val5 = null) { ?>
																<select class="form-control" name="status_approval_presales" id="status_approval_presales">
																	<option value="submission_to_be_reviewed">Pending</option>
																</select>
															<?php } else { ?>
																<select class="form-control" name="status_approval_presales" id="status_approval_presales">
																	<option value="<?php echo $data[0]['CR_status_approval_presales']; ?>"><?php echo $data[0]['CR_status_approval_presales']; ?></option>
																</select>
															<?php } ?>
															<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
																<input type="text" class="form-control form-control-sm" id="status_approval_presales" name="status_approval_presales" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																															echo "disabled";
																																														} ?> value="<?php echo $data[0]['CR_status_approval_presales']; ?>">
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Reason</label>
														<div class="col-sm-9">
															<?php $val = isset($ddata17['reason_request']); ?>
															<?php if ($val = null) { ?>
																<textarea class="form-control" name="reason_request" id="reason_request" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																						echo "disabled"; ?> value="<?php echo $ddata17['reason_request'];
																																												} ?>">
								<?php } else { ?>
								<textarea class="form-control" name="reason_request" id="reason_request" rows="3" value = "<?php echo $ddata17['reason_request']; ?>"<?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																											echo "disabled";
																																										} ?> readonly><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																															echo $ddata17['reason_request'];
																																														} ?></textarea>
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3" id="crclosing">
														<h2>Change Request Closing</h2>
														<div class="row mb-3">
															<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Hasil Akhir</label>
															<div class="col-sm-9">
																<?php $val = isset($ddata17['hasil_akhir']); ?>
																<?php if ($val = null) { ?>
																	<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																					echo "disabled";
																																				} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																							echo $ddata17['hasil_akhir'];
																																						} ?></textarea>
																<?php } else { ?>
																	<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" value="<?php echo $ddata17['hasil_akhir']; ?>" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																	echo "disabled";
																																																} ?> readonly><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																					echo $ddata17['hasil_akhir'];
																																																				} ?></textarea>
																<?php } ?>
															</div>
														</div>
														<div class="row mb-3">
															<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status CR</label>
															<div class="col-sm-9">
																<?php $val = isset($data[0]['change_request_status']); ?>
																<?php if ($val == null) { ?>
																	<select class="form-control" name="status_request" id="status_request">
																		<option value="submission_to_be_reviewed">Incomplete</option>
																	</select>
																<?php } else { ?>
																	<select class="form-control" name="status_request" id="status_request">
																		<option value="<?php echo $data[0]['change_request_status']; ?>"><?php echo $data[0]['change_request_status']; ?></option>
																	</select>
																<?php } ?>
															</div>
														</div>
														<div class="row mb-3">
															<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm" id="reason_rejected_text">Reason</label>
															<div class="col-sm-9">
																<?php $val = isset($ddata17['reason_rejected']); ?>
																<?php if ($val = null) { ?>
																	<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" <?php if ($_GET['act'] == 'review') {
																																							echo "disabled";
																																						} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																									echo $ddata17['reason_rejected'];
																																								} ?></textarea>
																<?php } else { ?>
																	<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" value="<?php echo $ddata17['reason_rejected']; ?>" <?php if ($_GET['act'] == 'review') {
																																																				echo "disabled";
																																																			} ?> readonly><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																								echo $ddata17['reason_rejected'];
																																																							} ?></textarea>
																<?php } ?>
															</div>
														</div>
													</div>
												<?php } else if ($_GET['status_approval'] == "submission_approved") { ?>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval</label>
														<div class="col-sm-9">
															<?php $val = isset($data[0]['change_request_approval_type']); ?>
															<?php if ($val = null) { ?>
																<select class="form-control" name="status_approval" id="status_approval" onchange="ChangeCRStatus()">
																	<option value="submission_to_be_reviewed">Pending</option>
																</select>
															<?php } else { ?>
																<select class="form-control" name="status_approval" id="status_approval" onchange="ChangeCRStatus()">
																	<option value="<?php echo $data[0]['change_request_approval_type']; ?>"><?php echo $data[0]['change_request_approval_type']; ?></option>
																</select>
															<?php } ?>
															<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
																<input type="text" class="form-control form-control-sm" id="status_approval" name="status_approval" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																										echo "disabled";
																																									} ?> value="<?php echo $data[0]['change_request_approval_type']; ?>">
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Reason</label>
														<div class="col-sm-9">
															<?php $val = isset($ddata17['reason_request']); ?>
															<?php if ($val = null) { ?>
																<textarea class="form-control" name="reason_request" id="reason_request" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																						echo "disabled"; ?> value="<?php echo $ddata17['reason_request'];
																																												} ?>">
								<?php } else { ?>
								<textarea class="form-control" name="reason_request" id="reason_request" rows="3" value = "<?php echo $ddata17['reason_request']; ?>"<?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																											echo "disabled";
																																										} ?> readonly><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																															echo $ddata17['reason_request'];
																																														} ?></textarea>
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3" id="crclosing">
														<h2>Change Request Closing</h2>
														<div class="row mb-3">
															<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Hasil Akhir</label>
															<div class="col-sm-9">
																<?php $val = isset($ddata17['hasil_akhir']); ?>
																<?php if ($val = null) { ?>
																	<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																					echo "disabled";
																																				} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																							echo $ddata17['hasil_akhir'];
																																						} ?></textarea>
																<?php } else { ?>
																	<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" value="<?php echo $ddata17['hasil_akhir']; ?>" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																	echo "disabled";
																																																} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																			echo $ddata17['hasil_akhir'];
																																																		} ?></textarea>
																<?php } ?>
															</div>
														</div>
														<div class="row mb-3">
															<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status CR</label>
															<div class="col-sm-9">
																<?php $val = isset($data[0]['change_request_status']); ?>
																<?php if ($val == null) { ?>
																	<select class="form-control" name="status_request" id="status_request">
																		<option value="submission_to_be_reviewed">Incomplete</option>
																		<option value="all_done">Complete</option>
																		<option value="submission_rejected">Rejected</option>
																	</select>
																<?php } else { ?>
																	<select class="form-control" name="status_request" id="status_request">
																		<option value="<?php echo $data[0]['change_request_status']; ?>"><?php echo $data[0]['change_request_status']; ?></option>
																		<option value="submission_to_be_reviewed">Incomplete</option>
																		<option value="all_done">Complete</option>
																		<option value="submission_rejected">Rejected</option>
																	</select>
																<?php } ?>
															</div>
														</div>
														<div class="row mb-3">
															<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm" id="reason_rejected_text">Reason</label>
															<div class="col-sm-9">
																<?php $val = isset($ddata17['reason_rejected']); ?>
																<?php if ($val = null) { ?>
																	<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" <?php if ($_GET['act'] == 'review') {
																																							echo "disabled";
																																						} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																									echo $ddata17['reason_rejected'];
																																								} ?></textarea>
																<?php } else { ?>
																	<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" value="<?php echo $ddata17['reason_rejected']; ?>" <?php if ($_GET['act'] == 'review') {
																																																				echo "disabled";
																																																			} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																						echo $ddata17['reason_rejected'];
																																																					} ?></textarea>
																<?php } ?>
															</div>
														</div>
													</div>
												<?php } ?>
											<?php } ?>
										<?php } ?>
										<?php if ($_GET['costimpact'] == "Technical" || $_GET['costimpact'] == "Tidak_Ada" || $_GET['costimpact'] == "Ada" || $_GET['costimpact'] == "undefined") { ?>
											<div class="row">
												<label for="inputCID3a" class="col-sm-12 col-form-label col-form-label-sm"><b>Change Control Board : Internal</b></label>
											</div>
											<div class="row mb-3 pt-3 pb-3">
												<label for="inputCID3a" class="col-sm-3 col-form-label col-form-label-sm" id="pic_text"><b>PIC (Expert Support) *</b></label>
												<div class="col-sm-9">
													<?php if ($_GET['act'] == 'review') { ?>
														<input type="text" class="form-control form-control-sm" id="pic_apr" name="pic_apr" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																				echo "disabled";
																																			} ?> value="<?php echo $ddata['pic']; ?>">
													<?php } elseif ($_GET['act'] == 'add') { ?>
														<select class="form-control" name="pic_apr" id="pic_apr" required>
															<option></option>
															//<option value="malik.aulia@mastersystem.co.id">Malik Aulia /malik.aulia@mastersystem.co.id</option>
															<option value="puji.riawan@mastersystem.co.id">Puji Riawan/puji.riawan@mastersystem.co.id</option>
															<?php do { ?>
																<option value="<?php echo $pic_apr[0]['employee_email']; ?>"><?php echo $pic_apr[0]['employee_name'] . "/" . $pic_apr[0]['employee_email']; ?></option>
															<?php } while ($pic_apr[0] = $pic_apr[1]->fetch_assoc()); ?>
														</select>
													<?php } elseif ($_GET['act'] == 'edit') { ?>
														<select class="form-control" name="pic_apr" id="pic_apr" required>
															<label>*</label>
															<option value="<?php echo $ddata['pic']; ?>"><?php echo $ddata['pic']; ?></option>
															<?php do { ?>
																<option value="<?php echo $pic_apr[0]['employee_email']; ?>"><?php echo $pic_apr[0]['employee_name'] . "/" . $pic_apr[0]['employee_email']; ?></option>
															<?php } while ($pic_apr[0] = $pic_apr[1]->fetch_assoc()); ?>
														</select>
													<?php } ?>
												</div>
												<div class="col-sm-9 text-right">
												</div>
											</div>
											<?php if ($_GET['act'] == "add") { ?>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval (Expert Support)</label>
													<div class="col-sm-9">
														<select class="form-control" name="status_approval" id="status_approval" onchange="ChangeCRStatus()">
															<option value="submission_to_be_reviewed">Pending</option>
														</select>
														<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
															<input type="text" class="form-control form-control-sm" id="status_approval" name="status_approval" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																									echo "disabled";
																																								} ?> value="<?php echo $data[0]['change_request_approval_type']; ?>">
														<?php } ?>
													</div>
												</div>
											<?php } ?>
											<div class="row mb-3 pt-3 pb-3">
												<label for="inputCID3a" class="col-sm-3 col-form-label col-form-label-sm" id="pic_text"><b>PIC (GM Expert Support) *</b> <i class="fas fa-question-circle" data-bs-toggle="popover" data-bs-trigger="focus" title="Jika Approval ditujukan untuk GM Expert Support maka field ini tidak usah diganti, namun jika butuh untuk level GM keatas bisa memilih salah satu nama BOD yang telah disediakan."></i></label>
												<div class="col-sm-9">
													<?php if ($_GET['act'] == 'review') { ?>
														<input type="text" class="form-control form-control-sm" id="pic_apr3" name="pic_apr3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																					echo "disabled";
																																				} ?> value="<?php echo $ddata['pic_leader']; ?>">
													<?php } elseif ($_GET['act'] == 'add') { ?>
														<select class="form-control" name="pic_apr3" id="pic_apr3">
															<option value="GM Expert Support">GM Expert Support</option>
															//<option value="malik.aulia@mastersystem.co.id">Malik Aulia /malik.aulia@mastersystem.co.id</option>
														</select>
													<?php } elseif ($_GET['act'] == 'edit') { ?>
														<select class="form-control" name="pic_apr3" id="pic_apr3" required>
															<option value="<?php echo $ddata['pic_leader']; ?>"><?php echo $ddata['pic_leader']; ?></option>
														</select>
													<?php } ?>
												</div>
												<div class="col-sm-9 text-right">
												</div>
											</div>
											<?php if ($_GET['act'] == 'add') { ?>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval (GM Expert Support)</label>
													<div class="col-sm-9">
														<select class="form-control" name="status_approval2" id="status_approval2" onchange="ChangeCRStatus()">
															<option value="submission_to_be_reviewed">Pending</option>
														</select>
														<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
															<input type="text" class="form-control form-control-sm" id="status_approval2" name="status_approval2" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																										echo "disabled";
																																									} ?> value="<?php echo $data[0]['change_request_approval_type2']; ?>">
														<?php } ?>
													</div>
												</div>
											<?php } ?>
											<?php if ($_GET['act'] == "add") { ?>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status CR</label>
													<div class="col-sm-9">
														<select class="form-control" name="status_request" id="status_request">
															<option value="submission_to_be_reviewed">Pending</option>
														</select>
													</div>
												</div>
											<?php } else if ($_GET['act'] == "edit" && $ddata['pic'] == $_SESSION['Microservices_UserEmail']) { ?>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval</label>
													<div class="col-sm-9">
														<?php $val = isset($data[0]['change_request_approval_type']); ?>
														<?php if ($val = null) { ?>
															<select class="form-control" name="status_approval" id="status_approval" onchange="ChangeCRStatus()">
																<option value="submission_approved">Approved</option>
																<option value="submission_rejected">Rejected</option>
															</select>
														<?php } else { ?>
															<select class="form-control" name="status_approval" id="status_approval" onchange="ChangeCRStatus()">
																<option value="<?php echo $data[0]['change_request_approval_type']; ?>"><?php echo $data[0]['change_request_approval_type']; ?></option>
																<option value="submission_approved">Approved</option>
																<option value="submission_rejected">Rejected</option>
															</select>
														<?php } ?>
														<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
															<input type="text" class="form-control form-control-sm" id="status_approval" name="status_approval" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																									echo "disabled";
																																								} ?> value="<?php echo $data[0]['change_request_approval_type']; ?>">
														<?php } ?>
													</div>
												</div>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Reason</label>
													<div class="col-sm-9">
														<?php $val = isset($ddata17['reason_request']); ?>
														<?php if ($val = null) { ?>
															<textarea class="form-control" name="reason_request" id="reason_request" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																					echo "disabled"; ?> value="<?php echo $ddata17['reason_request'];
																																											} ?>">
								<?php } else { ?>
								<textarea class="form-control" name="reason_request" id="reason_request" rows="3" value = "<?php echo $ddata17['reason_request']; ?>"<?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																											echo "disabled";
																																										} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																													echo $ddata17['reason_request'];
																																												} ?></textarea>
														<?php } ?>
													</div>
												</div>
												<div class="row mb-3" id="crclosing">
													<h2>Change Request Closing</h2>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Hasil Akhir</label>
														<div class="col-sm-9">
															<?php $val = isset($ddata17['hasil_akhir']); ?>
															<?php if ($val = null) { ?>
																<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																				echo "disabled";
																																			} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																						echo $ddata17['hasil_akhir'];
																																					} ?></textarea>
															<?php } else { ?>
																<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" value="<?php echo $ddata17['hasil_akhir']; ?>" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																echo "disabled";
																																															} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																		echo $ddata17['hasil_akhir'];
																																																	} ?></textarea>
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status CR</label>
														<div class="col-sm-9">
															<?php $val = isset($data[0]['change_request_status']); ?>
															<?php if ($val == null) { ?>
																<select class="form-control" name="status_request" id="status_request">
																	<option value="submission_to_be_reviewed">Incomplete</option>
																	<option value="all_done">Complete</option>
																	<option value="submission_rejected">Rejected</option>
																</select>
															<?php } else { ?>
																<select class="form-control" name="status_request" id="status_request">
																	<option value="<?php echo $data[0]['change_request_status']; ?>"><?php echo $data[0]['change_request_status']; ?></option>
																	<option value="submission_to_be_reviewed">Incomplete</option>
																	<option value="all_done">Complete</option>
																	<option value="submission_rejected">Rejected</option>
																</select>
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm" id="reason_rejected_text">Reason</label>
														<div class="col-sm-9">
															<?php $val = isset($ddata17['reason_rejected']); ?>
															<?php if ($val = null) { ?>
																<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" <?php if ($_GET['act'] == 'review') {
																																						echo "disabled";
																																					} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																								echo $ddata17['reason_rejected'];
																																							} ?></textarea>
															<?php } else { ?>
																<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" value="<?php echo $ddata17['reason_rejected']; ?>" <?php if ($_GET['act'] == 'review') {
																																																			echo "disabled";
																																																		} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																					echo $ddata17['reason_rejected'];
																																																				} ?></textarea>
															<?php } ?>
														</div>
													</div>
												</div>
											<?php } else if ($_GET['act'] == "edit" && $ddata['pic'] == $_SESSION['Microservices_UserEmail']) { ?>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval</label>
													<div class="col-sm-9">
														<?php $val = isset($data[0]['change_request_approval_type']); ?>
														<?php if ($val = null) { ?>
															<select class="form-control" name="status_approval" id="status_approval" onchange="ChangeCRStatus()">
																<option value="submission_approved">Approved</option>
																<option value="submission_rejected">Rejected</option>
															</select>
														<?php } else { ?>
															<select class="form-control" name="status_approval" id="status_approval" onchange="ChangeCRStatus()">
																<option value="<?php echo $data[0]['change_request_approval_type']; ?>"><?php echo $data[0]['change_request_approval_type']; ?></option>
																<option value="submission_approved">Approved</option>
																<option value="submission_rejected">Rejected</option>
															</select>
														<?php } ?>
														<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
															<input type="text" class="form-control form-control-sm" id="status_approval" name="status_approval" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																									echo "disabled";
																																								} ?> value="<?php echo $data[0]['change_request_approval_type']; ?>">
														<?php } ?>
													</div>
												</div>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Reason</label>
													<div class="col-sm-9">
														<?php $val = isset($ddata17['reason_request']); ?>
														<?php if ($val = null) { ?>
															<textarea class="form-control" name="reason_request" id="reason_request" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																					echo "disabled"; ?> value="<?php echo $ddata17['reason_request'];
																																											} ?>">
								<?php } else { ?>
								<textarea class="form-control" name="reason_request" id="reason_request" rows="3" value = "<?php echo $ddata17['reason_request']; ?>"<?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																											echo "disabled";
																																										} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																													echo $ddata17['reason_request'];
																																												} ?></textarea>
														<?php } ?>
													</div>
												</div>
												<div class="row mb-3" id="crclosing">
													<h2>Change Request Closing</h2>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Hasil Akhir</label>
														<div class="col-sm-9">
															<?php $val = isset($ddata17['hasil_akhir']); ?>
															<?php if ($val = null) { ?>
																<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																				echo "disabled";
																																			} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																						echo $ddata17['hasil_akhir'];
																																					} ?></textarea>
															<?php } else { ?>
																<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" value="<?php echo $ddata17['hasil_akhir']; ?>" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																echo "disabled";
																																															} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																		echo $ddata17['hasil_akhir'];
																																																	} ?></textarea>
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status CR</label>
														<div class="col-sm-9">
															<?php $val = isset($data[0]['change_request_status']); ?>
															<?php if ($val == null) { ?>
																<select class="form-control" name="status_request" id="status_request">
																	<option value="submission_to_be_reviewed">Incomplete</option>
																	<option value="all_done">Complete</option>
																	<option value="submission_rejected">Rejected</option>
																</select>
															<?php } else { ?>
																<select class="form-control" name="status_request" id="status_request">
																	<option value="<?php echo $data[0]['change_request_status']; ?>"><?php echo $data[0]['change_request_status']; ?></option>
																	<option value="submission_to_be_reviewed">Incomplete</option>
																	<option value="all_done">Complete</option>
																	<option value="submission_rejected">Rejected</option>
																</select>
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm" id="reason_rejected_text">Reason</label>
														<div class="col-sm-9">
															<?php $val = isset($ddata17['reason_rejected']); ?>
															<?php if ($val = null) { ?>
																<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" <?php if ($_GET['act'] == 'review') {
																																						echo "disabled";
																																					} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																								echo $ddata17['reason_rejected'];
																																							} ?></textarea>
															<?php } else { ?>
																<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" value="<?php echo $ddata17['reason_rejected']; ?>" <?php if ($_GET['act'] == 'review') {
																																																			echo "disabled";
																																																		} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																					echo $ddata17['reason_rejected'];
																																																				} ?></textarea>
															<?php } ?>
														</div>
													</div>
												</div>
											<?php } else if ($_GET['act'] == "edit" && $ddata['requested_by_email'] == $_SESSION['Microservices_UserEmail']) { ?>
												<?php if ($_GET['status_approval'] == 'submission_to_be_reviewed') { ?>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status</label>
														<div class="col-sm-9">
															<?php $val = isset($data[0]['change_request_approval_type']); ?>
															<?php if ($val = null) { ?>
																<select class="form-control" name="status_approval" id="status_approval" onchange="ChangeCRStatus()">
																	<option value="submission_to_be_reviewed">Pending</option>
																</select>
															<?php } else { ?>
																<select class="form-control" name="status_approval" id="status_approval" onchange="ChangeCRStatus()">
																	<option value="<?php echo $data[0]['change_request_approval_type']; ?>"><?php echo $data[0]['change_request_approval_type']; ?></option>
																</select>
															<?php } ?>
															<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
																<input type="text" class="form-control form-control-sm" id="status_approval" name="status_approval" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																										echo "disabled";
																																									} ?> value="<?php echo $data[0]['change_request_approval_type']; ?>">
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Reason</label>
														<div class="col-sm-9">
															<?php $val = isset($ddata17['reason_request']); ?>
															<?php if ($val = null) { ?>
																<textarea class="form-control" name="reason_request" id="reason_request" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																						echo "disabled"; ?> value="<?php echo $ddata17['reason_request'];
																																												} ?>">
								<?php } else { ?>
								<textarea class="form-control" name="reason_request" id="reason_request" rows="3" value = "<?php echo $ddata17['reason_request']; ?>"<?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																											echo "disabled";
																																										} ?> readonly><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																															echo $ddata17['reason_request'];
																																														} ?></textarea>
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3" id="crclosing">
														<h2>Change Request Closing</h2>
														<div class="row mb-3">
															<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Hasil Akhir</label>
															<div class="col-sm-9">
																<?php $val = isset($ddata17['hasil_akhir']); ?>
																<?php if ($val = null) { ?>
																	<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																					echo "disabled";
																																				} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																							echo $ddata17['hasil_akhir'];
																																						} ?></textarea>
																<?php } else { ?>
																	<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" value="<?php echo $ddata17['hasil_akhir']; ?>" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																	echo "disabled";
																																																} ?> readonly><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																					echo $ddata17['hasil_akhir'];
																																																				} ?></textarea>
																<?php } ?>
															</div>
														</div>
														<div class="row mb-3">
															<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status CR</label>
															<div class="col-sm-9">
																<?php $val = isset($data[0]['change_request_status']); ?>
																<?php if ($val == null) { ?>
																	<select class="form-control" name="status_request" id="status_request">
																		<option value="submission_to_be_reviewed">Incomplete</option>
																	</select>
																<?php } else { ?>
																	<select class="form-control" name="status_request" id="status_request">
																		<option value="<?php echo $data[0]['change_request_status']; ?>"><?php echo $data[0]['change_request_status']; ?></option>
																	</select>
																<?php } ?>
															</div>
														</div>
														<div class="row mb-3">
															<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm" id="reason_rejected_text">Reason</label>
															<div class="col-sm-9">
																<?php $val = isset($ddata17['reason_rejected']); ?>
																<?php if ($val = null) { ?>
																	<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" <?php if ($_GET['act'] == 'review') {
																																							echo "disabled";
																																						} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																									echo $ddata17['reason_rejected'];
																																								} ?></textarea>
																<?php } else { ?>
																	<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" value="<?php echo $ddata17['reason_rejected']; ?>" <?php if ($_GET['act'] == 'review') {
																																																				echo "disabled";
																																																			} ?> readonly><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																								echo $ddata17['reason_rejected'];
																																																							} ?></textarea>
																<?php } ?>
															</div>
														</div>
													</div>
												<?php } else if ($_GET['status_approval'] == "submission_approved") { ?>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval</label>
														<div class="col-sm-9">
															<?php $val = isset($data[0]['change_request_approval_type']); ?>
															<?php if ($val = null) { ?>
																<select class="form-control" name="status_approval" id="status_approval" onchange="ChangeCRStatus()">
																	<option value="submission_to_be_reviewed">Pending</option>
																</select>
															<?php } else { ?>
																<select class="form-control" name="status_approval" id="status_approval" onchange="ChangeCRStatus()">
																	<option value="<?php echo $data[0]['change_request_approval_type']; ?>"><?php echo $data[0]['change_request_approval_type']; ?></option>
																</select>
															<?php } ?>
															<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
																<input type="text" class="form-control form-control-sm" id="status_approval" name="status_approval" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																										echo "disabled";
																																									} ?> value="<?php echo $data[0]['change_request_approval_type']; ?>">
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Reason</label>
														<div class="col-sm-9">
															<?php $val = isset($ddata17['reason_request']); ?>
															<?php if ($val = null) { ?>
																<textarea class="form-control" name="reason_request" id="reason_request" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																						echo "disabled"; ?> value="<?php echo $ddata17['reason_request'];
																																												} ?>">
								<?php } else { ?>
								<textarea class="form-control" name="reason_request" id="reason_request" rows="3" value = "<?php echo $ddata17['reason_request']; ?>"<?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																											echo "disabled";
																																										} ?> readonly><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																															echo $ddata17['reason_request'];
																																														} ?></textarea>
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3" id="crclosing">
														<h2>Change Request Closing</h2>
														<div class="row mb-3">
															<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Hasil Akhir</label>
															<div class="col-sm-9">
																<?php $val = isset($ddata17['hasil_akhir']); ?>
																<?php if ($val = null) { ?>
																	<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																					echo "disabled";
																																				} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																							echo $ddata17['hasil_akhir'];
																																						} ?></textarea>
																<?php } else { ?>
																	<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" value="<?php echo $ddata17['hasil_akhir']; ?>" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																	echo "disabled";
																																																} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																			echo $ddata17['hasil_akhir'];
																																																		} ?></textarea>
																<?php } ?>
															</div>
														</div>
														<div class="row mb-3">
															<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status CR</label>
															<div class="col-sm-9">
																<?php $val = isset($data[0]['change_request_status']); ?>
																<?php if ($val == null) { ?>
																	<select class="form-control" name="status_request" id="status_request">
																		<option value="submission_to_be_reviewed">Incomplete</option>
																		<option value="all_done">Complete</option>
																		<option value="submission_rejected">Rejected</option>
																	</select>
																<?php } else { ?>
																	<select class="form-control" name="status_request" id="status_request">
																		<option value="<?php echo $data[0]['change_request_status']; ?>"><?php echo $data[0]['change_request_status']; ?></option>
																		<option value="submission_to_be_reviewed">Incomplete</option>
																		<option value="all_done">Complete</option>
																		<option value="submission_rejected">Rejected</option>
																	</select>
																<?php } ?>
															</div>
														</div>
														<div class="row mb-3">
															<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm" id="reason_rejected_text">Reason</label>
															<div class="col-sm-9">
																<?php $val = isset($ddata17['reason_rejected']); ?>
																<?php if ($val = null) { ?>
																	<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" <?php if ($_GET['act'] == 'review') {
																																							echo "disabled";
																																						} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																									echo $ddata17['reason_rejected'];
																																								} ?></textarea>
																<?php } else { ?>
																	<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" value="<?php echo $ddata17['reason_rejected']; ?>" <?php if ($_GET['act'] == 'review') {
																																																				echo "disabled";
																																																			} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																						echo $ddata17['reason_rejected'];
																																																					} ?></textarea>
																<?php } ?>
															</div>
														</div>
													</div>
												<?php } ?>
											<?php } ?>
										<?php } ?>
										<?php if ($_GET['costimpact'] == "Project Deliverables") { ?>
											<div class="row">
												<label for="inputCID3a" class="col-sm-12 col-form-label col-form-label-sm"><b>Change Control Board : Internal</b></label>
											</div>
											<div class="row mb-3 pt-3 pb-3">
												<label for="inputCID3a" class="col-sm-3 col-form-label col-form-label-sm" id="pic_text"><b>PIC (Expert Support) *</b></label>
												<div class="col-sm-9">
													<?php if ($_GET['act'] == 'review') { ?>
														<input type="text" class="form-control form-control-sm" id="pic_apr" name="pic_apr" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																				echo "disabled";
																																			} ?> value="<?php echo $ddata['pic']; ?>">
													<?php } elseif ($_GET['act'] == 'add') { ?>
														<select class="form-control" name="pic_apr" id="pic_apr" required>
															<option></option>
															<option value="puji.riawan@mastersystem.co.id">Puji Riawan/puji.riawan@mastersystem.co.id</option>
															<?php do { ?>
																<option value="<?php echo $check_approval[0]['employee_email']; ?>"><?php echo $check_approval[0]['employee_name'] . "/" . $check_approval[0]['employee_email']; ?></option>
															<?php } while ($check_approval[0] = $check_approval[1]->fetch_assoc()); ?>
														</select>
													<?php } elseif ($_GET['act'] == 'edit') { ?>
														<select class="form-control" name="pic_apr" id="pic_apr" required>
															<label>*</label>
															<option value="<?php echo $ddata['pic']; ?>"><?php echo $ddata['pic']; ?></option>
															<?php do { ?>
																<option value="<?php echo $check_approval[0]['employee_email']; ?>"><?php echo $check_approval[0]['employee_name'] . "/" . $check_approval[0]['employee_email']; ?></option>
															<?php } while ($check_approval[0] = $check_approval[1]->fetch_assoc()); ?>
														</select>
													<?php } ?>
												</div>
												<div class="col-sm-9 text-right">
												</div>
											</div>
											<?php if ($_GET['act'] == 'add') { ?>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval (Expert Support)</label>
													<div class="col-sm-9">
														<select class="form-control" name="status_approval" id="status_approval" onchange="ChangeCRStatus()">
															<option value="submission_to_be_reviewed">Pending</option>
														</select>
														<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
															<input type="text" class="form-control form-control-sm" id="status_approval" name="status_approval" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																									echo "disabled";
																																								} ?> value="<?php echo $data[0]['change_request_approval_type']; ?>">
														<?php } ?>
													</div>
												</div>
											<?php } ?>
											<div class="row mb-3 pt-3 pb-3">
												<label for="inputCID3a" class="col-sm-3 col-form-label col-form-label-sm" id="pic_text"><b>PIC (GM Sales)*</b></label>
												<div class="col-sm-9">
													<?php if ($_GET['act'] == 'review') { ?>
														<input type="text" class="form-control form-control-sm" id="pic_apr2" name="pic_apr2" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																					echo "disabled";
																																				} ?> value="<?php echo $ddata['pic']; ?>">
													<?php } elseif ($_GET['act'] == 'add') { ?>
														<?php if (isset($get_gm[0]['leader_email'])) { ?>
															<select class="form-control" name="pic_apr2" id="pic_apr2" required>
																<option value="<?php echo $get_gm[0]['leader_email']; ?>"><?php echo $get_gm[0]['leader_name'] . "/" . $get_gm[0]['leader_email']; ?></option>
															</select>
														<?php } else { ?>
															<select class="form-control" name="pic_apr2" id="pic_apr2" required>
																<option>Tidak Ditemukan</option>
															</select>
														<?php } ?>
													<?php } elseif ($_GET['act'] == 'edit') { ?>
														<select class="form-control" name="pic_apr2" id="pic_apr2" required>
															<option value="<?php echo $ddata['pic_sales']; ?>"><?php echo $ddata['pic_sales']; ?></option>
														</select>
													<?php } ?>
												</div>
												<div class="col-sm-9 text-right">
												</div>
											</div>
											<?php if ($_GET['act'] == 'add') { ?>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval (GM Sales)</label>
													<div class="col-sm-9">
														<select class="form-control" name="status_approval_sales" id="status_approval_sales" onchange="ChangeCRStatus()">
															<option value="submission_to_be_reviewed">Pending</option>
														</select>
														<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
															<input type="text" class="form-control form-control-sm" id="status_approval_sales" name="status_approval_sales" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																												echo "disabled";
																																											} ?> value="<?php echo $data[0]['CR_status_approval_sales']; ?>">
														<?php } ?>
													</div>
												</div>
											<?php } ?>
											<div class="row mb-3 pt-3 pb-3">
												<label for="inputCID3a" class="col-sm-3 col-form-label col-form-label-sm" id="pic_text"><b>PIC (GM Expert Support) *</b> <i class="fas fa-question-circle" data-bs-toggle="popover" data-bs-trigger="focus" title="Jika Approval ditujukan untuk GM Expert Support maka field ini tidak usah diganti, namun jika butuh untuk level GM keatas bisa memilih salah satu nama BOD yang telah disediakan."></i></label>
												<div class="col-sm-9">
													<?php if ($_GET['act'] == 'review') { ?>
														<input type="text" class="form-control form-control-sm" id="pic_apr3" name="pic_apr3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																					echo "disabled";
																																				} ?> value="<?php echo $ddata['pic_leader']; ?>">
													<?php } elseif ($_GET['act'] == 'add') { ?>
														<select class="form-control" name="pic_apr3" id="pic_apr3">
															<option value="GM Expert Support">GM Expert Support</option>
														</select>
													<?php } elseif ($_GET['act'] == 'edit') { ?>
														<select class="form-control" name="pic_apr3" id="pic_apr3" required>
															<option value="<?php echo $ddata['pic_leader']; ?>"><?php echo $ddata['pic_leader']; ?></option>
														</select>
													<?php } ?>
												</div>
												<div class="col-sm-9 text-right">
												</div>
											</div>
											<?php if ($_GET['act'] == 'add') { ?>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval (GM Expert Support)</label>
													<div class="col-sm-9">
														<select class="form-control" name="status_approval2" id="status_approval2" onchange="ChangeCRStatus()">
															<option value="submission_to_be_reviewed">Pending</option>
														</select>
														<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
															<input type="text" class="form-control form-control-sm" id="status_approval2" name="status_approval2" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																										echo "disabled";
																																									} ?> value="<?php echo $data[0]['change_request_approval_type2']; ?>">
														<?php } ?>
													</div>
												</div>
											<?php } ?>
											<?php if ($_GET['type'] == "Implementation") { ?>
												<div class="row mb-3 pt-3 pb-3">
													<label for="inputCID3a" class="col-sm-3 col-form-label col-form-label-sm" id="pic_text"><b>PIC (GM PMO) *</b></label>
													<div class="col-sm-9">
														<?php if ($_GET['act'] == 'review') { ?>
															<input type="text" class="form-control form-control-sm" id="pic_apr4" name="pic_apr4" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																						echo "disabled";
																																					} ?> value="<?php echo $ddata['pic_pmo']; ?>">
														<?php } elseif ($_GET['act'] == 'add') { ?>
															<select class="form-control" name="pic_apr4" id="pic_apr4" required>
																<option value="fortuna@mastersystem.co.id">Fortuna Setyo Arumsari/fortuna@mastersystem.co.id</option>
																<option value="sumarno@mastersystem.co.id">Sumarno/sumarno@mastersystem.co.id</option>
																<option value="pitasari.amanda@mastersystem.co.id">Pitasari Amanda/pitasari.amanda@mastersystem.co.id</option>
															</select>
														<?php } elseif ($_GET['act'] == 'edit') { ?>
															<select class="form-control" name="pic_apr4" id="pic_apr4" required>
																<option value="<?php echo $ddata['pic_pmo']; ?>"><?php echo $ddata['pic_pmo']; ?></option>
															</select>
														<?php } ?>
													</div>
													<div class="col-sm-9 text-right">
													</div>
												</div>
											<?php } ?>
											<?php if ($_GET['type'] == "Maintenance") { ?>
												<div class="row mb-3 pt-3 pb-3">
													<label for="inputCID3a" class="col-sm-3 col-form-label col-form-label-sm" id="pic_text"><b>PIC (PMO) *</b></label>
													<div class="col-sm-9">
														<?php if ($_GET['act'] == 'review') { ?>
															<input type="text" class="form-control form-control-sm" id="pic_apr4" name="pic_apr4" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																						echo "disabled";
																																					} ?> value="<?php echo $ddata['pic_pmo']; ?>">
														<?php } elseif ($_GET['act'] == 'add') { ?>
															<select class="form-control" name="pic_apr4" id="pic_apr4" required>
																<option value="anggi.fachrizal@mastersystem.co.id">Anggi Fachrizal/anggi.fachrizal@mastersystem.co.id</option>
																<option value="andino.hf@mastersystem.co.id">Andino Holi Fajra/andino.hf@mastersystem.co.id</option>
																<option value="aceng.zakariya@mastersystem.co.id">Aceng Zakariya Ramadani/aceng.zakariya@mastersystem.co.id</option>
																<option value="iwan@mastersystem.co.id">Iwan Rusmin/iwan@mastersystem.co.id</option>
															</select>
														<?php } elseif ($_GET['act'] == 'edit') { ?>
															<select class="form-control" name="pic_apr4" id="pic_apr4" required>
																<option value="<?php echo $ddata['pic_pmo']; ?>"><?php echo $ddata['pic_pmo']; ?></option>
															</select>
														<?php } ?>
													</div>
													<div class="col-sm-9 text-right">
													</div>
												</div>
											<?php } ?>
											<?php if ($_GET['act'] == 'add') { ?>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval (GM PMO)</label>
													<div class="col-sm-9">
														<select class="form-control" name="status_approval_pmo" id="status_approval_pmo" onchange="ChangeCRStatus()">
															<option value="submission_to_be_reviewed">Pending</option>
														</select>
														<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
															<input type="text" class="form-control form-control-sm" id="status_approval_pmo" name="status_approval_pmo" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																											echo "disabled";
																																										} ?> value="<?php echo $data[0]['CR_status_approval_pmo']; ?>">
														<?php } ?>
													</div>
												</div>
											<?php } ?>
											<?php if ($_GET['act'] == "add") { ?>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status CR</label>
													<div class="col-sm-9">
														<select class="form-control" name="status_request" id="status_request">
															<option value="submission_to_be_reviewed">Pending</option>
														</select>
													</div>
												</div>
											<?php } else if ($_GET['act'] == "edit" && $ddata['pic'] == $_SESSION['Microservices_UserEmail']) { ?>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval</label>
													<div class="col-sm-9">
														<?php $val = isset($data[0]['change_request_approval_type']); ?>
														<?php if ($val = null) { ?>
															<select class="form-control" name="status_approval" id="status_approval" onchange="ChangeCRStatus()">
																<option value="submission_approved">Approved</option>
																<option value="submission_rejected">Rejected</option>
															</select>
														<?php } else { ?>
															<select class="form-control" name="status_approval" id="status_approval" onchange="ChangeCRStatus()">
																<option value="<?php echo $data[0]['change_request_approval_type']; ?>"><?php echo $data[0]['change_request_approval_type']; ?></option>
																<option value="submission_approved">Approved</option>
																<option value="submission_rejected">Rejected</option>
															</select>
														<?php } ?>
														<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
															<input type="text" class="form-control form-control-sm" id="status_approval" name="status_approval" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																									echo "disabled";
																																								} ?> value="<?php echo $data[0]['change_request_approval_type']; ?>">
														<?php } ?>
													</div>
												</div>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Reason</label>
													<div class="col-sm-9">
														<?php $val = isset($ddata17['reason_request']); ?>
														<?php if ($val = null) { ?>
															<textarea class="form-control" name="reason_request" id="reason_request" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																					echo "disabled"; ?> value="<?php echo $ddata17['reason_request'];
																																											} ?>">
								<?php } else { ?>
								<textarea class="form-control" name="reason_request" id="reason_request" rows="3" value = "<?php echo $ddata17['reason_request']; ?>"<?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																											echo "disabled";
																																										} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																													echo $ddata17['reason_request'];
																																												} ?></textarea>
														<?php } ?>
													</div>
												</div>
												<div class="row mb-3" id="crclosing">
													<h2>Change Request Closing</h2>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Hasil Akhir</label>
														<div class="col-sm-9">
															<?php $val = isset($ddata17['hasil_akhir']); ?>
															<?php if ($val = null) { ?>
																<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																				echo "disabled";
																																			} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																						echo $ddata17['hasil_akhir'];
																																					} ?></textarea>
															<?php } else { ?>
																<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" value="<?php echo $ddata17['hasil_akhir']; ?>" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																echo "disabled";
																																															} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																		echo $ddata17['hasil_akhir'];
																																																	} ?></textarea>
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status CR</label>
														<div class="col-sm-9">
															<?php $val = isset($data[0]['change_request_status']); ?>
															<?php if ($val == null) { ?>
																<select class="form-control" name="status_request" id="status_request">
																	<option value="submission_to_be_reviewed">Incomplete</option>
																	<option value="all_done">Complete</option>
																	<option value="submission_rejected">Rejected</option>
																</select>
															<?php } else { ?>
																<select class="form-control" name="status_request" id="status_request">
																	<option value="<?php echo $data[0]['change_request_status']; ?>"><?php echo $data[0]['change_request_status']; ?></option>
																	<option value="submission_to_be_reviewed">Incomplete</option>
																	<option value="all_done">Complete</option>
																	<option value="submission_rejected">Rejected</option>
																</select>
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm" id="reason_rejected_text">Reason</label>
														<div class="col-sm-9">
															<?php $val = isset($ddata17['reason_rejected']); ?>
															<?php if ($val = null) { ?>
																<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" <?php if ($_GET['act'] == 'review') {
																																						echo "disabled";
																																					} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																								echo $ddata17['reason_rejected'];
																																							} ?></textarea>
															<?php } else { ?>
																<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" value="<?php echo $ddata17['reason_rejected']; ?>" <?php if ($_GET['act'] == 'review') {
																																																			echo "disabled";
																																																		} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																					echo $ddata17['reason_rejected'];
																																																				} ?></textarea>
															<?php } ?>
														</div>
													</div>
												</div>
											<?php } else if ($_GET['act'] == "edit" && $ddata['pic_leader'] == $_SESSION['Microservices_UserEmail']) { ?>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval</label>
													<div class="col-sm-9">
														<?php $val = isset($data[0]['change_request_approval_type2']); ?>
														<?php if ($val = null) { ?>
															<select class="form-control" name="status_approval2" id="status_approval2" onchange="ChangeCRStatus()">
																<option value="submission_approved">Approved</option>
																<option value="submission_rejected">Rejected</option>
															</select>
														<?php } else { ?>
															<select class="form-control" name="status_approval2" id="status_approval2" onchange="ChangeCRStatus()">
																<option value="<?php echo $data[0]['change_request_approval_type2']; ?>"><?php echo $data[0]['change_request_approval_type2']; ?></option>
																<option value="submission_approved">Approved</option>
																<option value="submission_rejected">Rejected</option>
															</select>
														<?php } ?>
														<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
															<input type="text" class="form-control form-control-sm" id="status_approval2" name="status_approval2" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																										echo "disabled";
																																									} ?> value="<?php echo $data[0]['change_request_approval_type2']; ?>">
														<?php } ?>
													</div>
												</div>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Reason</label>
													<div class="col-sm-9">
														<?php $val = isset($ddata17['reason_request']); ?>
														<?php if ($val = null) { ?>
															<textarea class="form-control" name="reason_request" id="reason_request" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																					echo "disabled"; ?> value="<?php echo $ddata17['reason_request'];
																																											} ?>">
								<?php } else { ?>
								<textarea class="form-control" name="reason_request" id="reason_request" rows="3" value = "<?php echo $ddata17['reason_request']; ?>"<?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																											echo "disabled";
																																										} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																													echo $ddata17['reason_request'];
																																												} ?></textarea>
														<?php } ?>
													</div>
												</div>
												<div class="row mb-3" id="crclosing">
													<h2>Change Request Closing</h2>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Hasil Akhir</label>
														<div class="col-sm-9">
															<?php $val = isset($ddata17['hasil_akhir']); ?>
															<?php if ($val = null) { ?>
																<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																				echo "disabled";
																																			} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																						echo $ddata17['hasil_akhir'];
																																					} ?></textarea>
															<?php } else { ?>
																<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" value="<?php echo $ddata17['hasil_akhir']; ?>" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																echo "disabled";
																																															} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																		echo $ddata17['hasil_akhir'];
																																																	} ?></textarea>
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status CR</label>
														<div class="col-sm-9">
															<?php $val = isset($data[0]['change_request_status']); ?>
															<?php if ($val == null) { ?>
																<select class="form-control" name="status_request" id="status_request">
																	<option value="submission_to_be_reviewed">Incomplete</option>
																	<option value="all_done">Complete</option>
																	<option value="submission_rejected">Rejected</option>
																</select>
															<?php } else { ?>
																<select class="form-control" name="status_request" id="status_request">
																	<option value="<?php echo $data[0]['change_request_status']; ?>"><?php echo $data[0]['change_request_status']; ?></option>
																	<option value="submission_to_be_reviewed">Incomplete</option>
																	<option value="all_done">Complete</option>
																	<option value="submission_rejected">Rejected</option>
																</select>
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm" id="reason_rejected_text">Reason</label>
														<div class="col-sm-9">
															<?php $val = isset($ddata17['reason_rejected']); ?>
															<?php if ($val = null) { ?>
																<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" <?php if ($_GET['act'] == 'review') {
																																						echo "disabled";
																																					} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																								echo $ddata17['reason_rejected'];
																																							} ?></textarea>
															<?php } else { ?>
																<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" value="<?php echo $ddata17['reason_rejected']; ?>" <?php if ($_GET['act'] == 'review') {
																																																			echo "disabled";
																																																		} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																					echo $ddata17['reason_rejected'];
																																																				} ?></textarea>
															<?php } ?>
														</div>
													</div>
												</div>
											<?php } else if ($_GET['act'] == "edit" && $ddata['pic_sales'] == $_SESSION['Microservices_UserEmail']) { ?>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval</label>
													<div class="col-sm-9">
														<?php $val = isset($data[0]['CR_status_approval_sales']); ?>
														<?php if ($val = null) { ?>
															<select class="form-control" name="status_approval_sales" id="status_approval_sales" onchange="ChangeCRStatus()">
																<option value="submission_approved">Approved</option>
																<option value="submission_rejected">Rejected</option>
															</select>
														<?php } else { ?>
															<select class="form-control" name="status_approval_sales" id="status_approval_sales" onchange="ChangeCRStatus()">
																<option value="<?php echo $data[0]['CR_status_approval_sales']; ?>"><?php echo $data[0]['CR_status_approval_sales']; ?></option>
																<option value="submission_approved">Approved</option>
																<option value="submission_rejected">Rejected</option>
															</select>
														<?php } ?>
														<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
															<input type="text" class="form-control form-control-sm" id="status_approval_sales" name="status_approval_sales" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																												echo "disabled";
																																											} ?> value="<?php echo $data[0]['CR_status_approval_sales']; ?>">
														<?php } ?>
													</div>
												</div>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Reason</label>
													<div class="col-sm-9">
														<?php $val = isset($ddata17['reason_request']); ?>
														<?php if ($val = null) { ?>
															<textarea class="form-control" name="reason_request" id="reason_request" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																					echo "disabled"; ?> value="<?php echo $ddata17['reason_request'];
																																											} ?>">
								<?php } else { ?>
								<textarea class="form-control" name="reason_request" id="reason_request" rows="3" value = "<?php echo $ddata17['reason_request']; ?>"<?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																											echo "disabled";
																																										} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																													echo $ddata17['reason_request'];
																																												} ?></textarea>
														<?php } ?>
													</div>
												</div>
												<div class="row mb-3" id="crclosing">
													<h2>Change Request Closing</h2>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Hasil Akhir</label>
														<div class="col-sm-9">
															<?php $val = isset($ddata17['hasil_akhir']); ?>
															<?php if ($val = null) { ?>
																<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																				echo "disabled";
																																			} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																						echo $ddata17['hasil_akhir'];
																																					} ?></textarea>
															<?php } else { ?>
																<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" value="<?php echo $ddata17['hasil_akhir']; ?>" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																echo "disabled";
																																															} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																		echo $ddata17['hasil_akhir'];
																																																	} ?></textarea>
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status CR</label>
														<div class="col-sm-9">
															<?php $val = isset($data[0]['change_request_status']); ?>
															<?php if ($val == null) { ?>
																<select class="form-control" name="status_request" id="status_request">
																	<option value="submission_to_be_reviewed">Incomplete</option>
																	<option value="all_done">Complete</option>
																	<option value="submission_rejected">Rejected</option>
																</select>
															<?php } else { ?>
																<select class="form-control" name="status_request" id="status_request">
																	<option value="<?php echo $data[0]['change_request_status']; ?>"><?php echo $data[0]['change_request_status']; ?></option>
																	<option value="submission_to_be_reviewed">Incomplete</option>
																	<option value="all_done">Complete</option>
																	<option value="submission_rejected">Rejected</option>
																</select>
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm" id="reason_rejected_text">Reason</label>
														<div class="col-sm-9">
															<?php $val = isset($ddata17['reason_rejected']); ?>
															<?php if ($val = null) { ?>
																<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" <?php if ($_GET['act'] == 'review') {
																																						echo "disabled";
																																					} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																								echo $ddata17['reason_rejected'];
																																							} ?></textarea>
															<?php } else { ?>
																<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" value="<?php echo $ddata17['reason_rejected']; ?>" <?php if ($_GET['act'] == 'review') {
																																																			echo "disabled";
																																																		} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																					echo $ddata17['reason_rejected'];
																																																				} ?></textarea>
															<?php } ?>
														</div>
													</div>
												</div>
											<?php } else if ($_GET['act'] == "edit" && $ddata['pic_pmo'] == $_SESSION['Microservices_UserEmail']) { ?>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval</label>
													<div class="col-sm-9">
														<?php $val = isset($data[0]['CR_status_approval_pmo']); ?>
														<?php if ($val = null) { ?>
															<select class="form-control" name="status_approval_pmo" id="status_approval_pmo" onchange="ChangeCRStatus()">
																<option value="submission_approved">Approved</option>
																<option value="submission_rejected">Rejected</option>
															</select>
														<?php } else { ?>
															<select class="form-control" name="status_approval_pmo" id="status_approval_pmo" onchange="ChangeCRStatus()">
																<option value="<?php echo $data[0]['CR_status_approval_pmo']; ?>"><?php echo $data[0]['CR_status_approval_pmo']; ?></option>
																<option value="submission_approved">Approved</option>
																<option value="submission_rejected">Rejected</option>
															</select>
														<?php } ?>
														<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
															<input type="text" class="form-control form-control-sm" id="status_approval_pmo" name="status_approval_pmo" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																											echo "disabled";
																																										} ?> value="<?php echo $data[0]['CR_status_approval_pmo']; ?>">
														<?php } ?>
													</div>
												</div>
												<div class="row mb-3">
													<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Reason</label>
													<div class="col-sm-9">
														<?php $val = isset($ddata17['reason_request']); ?>
														<?php if ($val = null) { ?>
															<textarea class="form-control" name="reason_request" id="reason_request" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																					echo "disabled"; ?> value="<?php echo $ddata17['reason_request'];
																																											} ?>">
								<?php } else { ?>
								<textarea class="form-control" name="reason_request" id="reason_request" rows="3" value = "<?php echo $ddata17['reason_request']; ?>"<?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																											echo "disabled";
																																										} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																													echo $ddata17['reason_request'];
																																												} ?></textarea>
														<?php } ?>
													</div>
												</div>
												<div class="row mb-3" id="crclosing">
													<h2>Change Request Closing</h2>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Hasil Akhir</label>
														<div class="col-sm-9">
															<?php $val = isset($ddata17['hasil_akhir']); ?>
															<?php if ($val = null) { ?>
																<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																				echo "disabled";
																																			} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																						echo $ddata17['hasil_akhir'];
																																					} ?></textarea>
															<?php } else { ?>
																<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" value="<?php echo $ddata17['hasil_akhir']; ?>" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																echo "disabled";
																																															} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																		echo $ddata17['hasil_akhir'];
																																																	} ?></textarea>
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status CR</label>
														<div class="col-sm-9">
															<?php $val = isset($data[0]['change_request_status']); ?>
															<?php if ($val == null) { ?>
																<select class="form-control" name="status_request" id="status_request">
																	<option value="submission_to_be_reviewed">Incomplete</option>
																	<option value="all_done">Complete</option>
																	<option value="submission_rejected">Rejected</option>
																</select>
															<?php } else { ?>
																<select class="form-control" name="status_request" id="status_request">
																	<option value="<?php echo $data[0]['change_request_status']; ?>"><?php echo $data[0]['change_request_status']; ?></option>
																	<option value="submission_to_be_reviewed">Incomplete</option>
																	<option value="all_done">Complete</option>
																	<option value="submission_rejected">Rejected</option>
																</select>
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm" id="reason_rejected_text">Reason</label>
														<div class="col-sm-9">
															<?php $val = isset($ddata17['reason_rejected']); ?>
															<?php if ($val = null) { ?>
																<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" <?php if ($_GET['act'] == 'review') {
																																						echo "disabled";
																																					} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																								echo $ddata17['reason_rejected'];
																																							} ?></textarea>
															<?php } else { ?>
																<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" value="<?php echo $ddata17['reason_rejected']; ?>" <?php if ($_GET['act'] == 'review') {
																																																			echo "disabled";
																																																		} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																					echo $ddata17['reason_rejected'];
																																																				} ?></textarea>
															<?php } ?>
														</div>
													</div>
												</div>
											<?php } else if ($_GET['act'] == "edit" && $ddata['requested_by_email'] == $_SESSION['Microservices_UserEmail']) { ?>
												<?php if ($_GET['status_approval'] == 'submission_to_be_reviewed') { ?>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval (Expert Support)</label>
														<div class="col-sm-9">
															<?php $val = isset($data[0]['change_request_approval_type']); ?>
															<?php if ($val = null) { ?>
																<select class="form-control" name="status_approval" id="status_approval">
																	<option value="submission_to_be_reviewed">Pending</option>
																</select>
															<?php } else { ?>
																<select class="form-control" name="status_approval" id="status_approval">
																	<option value="<?php echo $data[0]['change_request_approval_type']; ?>"><?php echo $data[0]['change_request_approval_type']; ?></option>
																</select>
															<?php } ?>
															<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
																<input type="text" class="form-control form-control-sm" id="status_approval" name="status_approval" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																										echo "disabled";
																																									} ?> value="<?php echo $data[0]['change_request_approval_type']; ?>">
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval (GM Sales)</label>
														<div class="col-sm-9">
															<?php $val2 = isset($data[0]['CR_status_approval_sales']); ?>
															<?php if ($val2 = null) { ?>
																<select class="form-control" name="status_approval_sales" id="status_approval_sales">
																	<option value="submission_to_be_reviewed">Pending</option>
																</select>
															<?php } else { ?>
																<select class="form-control" name="status_approval_sales" id="status_approval_sales">
																	<option value="<?php echo $data[0]['CR_status_approval_sales']; ?>"><?php echo $data[0]['CR_status_approval_sales']; ?></option>
																</select>
															<?php } ?>
															<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
																<input type="text" class="form-control form-control-sm" id="status_approval_sales" name="status_approval_sales" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																													echo "disabled";
																																												} ?> value="<?php echo $data[0]['CR_status_approval_sales']; ?>">
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval (GM Expert Support)</label>
														<div class="col-sm-9">
															<?php $val3 = isset($data[0]['change_request_approval_type2']); ?>
															<?php if ($val3 = null) { ?>
																<select class="form-control" name="status_approval2" id="status_approval2">
																	<option value="submission_to_be_reviewed">Pending</option>
																</select>
															<?php } else { ?>
																<select class="form-control" name="status_approval2" id="status_approval2">
																	<option value="<?php echo $data[0]['change_request_approval_type2']; ?>"><?php echo $data[0]['change_request_approval_type2']; ?></option>
																</select>
															<?php } ?>
															<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
																<input type="text" class="form-control form-control-sm" id="status_approval2" name="status_approval2" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																											echo "disabled";
																																										} ?> value="<?php echo $data[0]['change_request_approval_type2']; ?>">
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval (GM PMO)</label>
														<div class="col-sm-9">
															<?php $val4 = isset($data[0]['CR_status_approval_pmo']); ?>
															<?php if ($val4 = null) { ?>
																<select class="form-control" name="status_approval_pmo" id="status_approval_pmo">
																	<option value="submission_to_be_reviewed">Pending</option>
																</select>
															<?php } else { ?>
																<select class="form-control" name="status_approval_pmo" id="status_approval_pmo">
																	<option value="<?php echo $data[0]['CR_status_approval_pmo']; ?>"><?php echo $data[0]['CR_status_approval_pmo']; ?></option>
																</select>
															<?php } ?>
															<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
																<input type="text" class="form-control form-control-sm" id="status_approval_pmo" name="status_approval_pmo" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																												echo "disabled";
																																											} ?> value="<?php echo $data[0]['CR_status_approval_pmo']; ?>">
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval (GM Presales)</label>
														<div class="col-sm-9">
															<?php $val5 = isset($data[0]['CR_status_approval_presales']); ?>
															<?php if ($val5 = null) { ?>
																<select class="form-control" name="status_approval_presales" id="status_approval_presales">
																	<option value="submission_to_be_reviewed">Pending</option>
																</select>
															<?php } else { ?>
																<select class="form-control" name="status_approval_presales" id="status_approval_presales">
																	<option value="<?php echo $data[0]['CR_status_approval_presales']; ?>"><?php echo $data[0]['CR_status_approval_presales']; ?></option>
																</select>
															<?php } ?>
															<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
																<input type="text" class="form-control form-control-sm" id="status_approval_presales" name="status_approval_presales" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																															echo "disabled";
																																														} ?> value="<?php echo $data[0]['CR_status_approval_presales']; ?>">
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Reason</label>
														<div class="col-sm-9">
															<?php $val = isset($ddata17['reason_request']); ?>
															<?php if ($val = null) { ?>
																<textarea class="form-control" name="reason_request" id="reason_request" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																						echo "disabled"; ?> value="<?php echo $ddata17['reason_request'];
																																												} ?>">
								<?php } else { ?>
								<textarea class="form-control" name="reason_request" id="reason_request" rows="3" value = "<?php echo $ddata17['reason_request']; ?>"<?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																											echo "disabled";
																																										} ?> readonly><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																															echo $ddata17['reason_request'];
																																														} ?></textarea>
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3" id="crclosing">
														<h2>Change Request Closing</h2>
														<div class="row mb-3">
															<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Hasil Akhir</label>
															<div class="col-sm-9">
																<?php $val = isset($ddata17['hasil_akhir']); ?>
																<?php if ($val = null) { ?>
																	<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																					echo "disabled";
																																				} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																							echo $ddata17['hasil_akhir'];
																																						} ?></textarea>
																<?php } else { ?>
																	<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" value="<?php echo $ddata17['hasil_akhir']; ?>" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																	echo "disabled";
																																																} ?> readonly><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																					echo $ddata17['hasil_akhir'];
																																																				} ?></textarea>
																<?php } ?>
															</div>
														</div>
														<div class="row mb-3">
															<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status CR</label>
															<div class="col-sm-9">
																<?php $val = isset($data[0]['change_request_status']); ?>
																<?php if ($val == null) { ?>
																	<select class="form-control" name="status_request" id="status_request">
																		<option value="submission_to_be_reviewed">Incomplete</option>
																	</select>
																<?php } else { ?>
																	<select class="form-control" name="status_request" id="status_request">
																		<option value="<?php echo $data[0]['change_request_status']; ?>"><?php echo $data[0]['change_request_status']; ?></option>
																	</select>
																<?php } ?>
															</div>
														</div>
														<div class="row mb-3">
															<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm" id="reason_rejected_text">Reason</label>
															<div class="col-sm-9">
																<?php $val = isset($ddata17['reason_rejected']); ?>
																<?php if ($val = null) { ?>
																	<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" <?php if ($_GET['act'] == 'review') {
																																							echo "disabled";
																																						} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																									echo $ddata17['reason_rejected'];
																																								} ?></textarea>
																<?php } else { ?>
																	<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" value="<?php echo $ddata17['reason_rejected']; ?>" <?php if ($_GET['act'] == 'review') {
																																																				echo "disabled";
																																																			} ?> readonly><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																								echo $ddata17['reason_rejected'];
																																																							} ?></textarea>
																<?php } ?>
															</div>
														</div>
													</div>
												<?php } else if ($_GET['status_approval'] == "submission_approved") { ?>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status Approval</label>
														<div class="col-sm-9">
															<?php $val = isset($data[0]['change_request_approval_type']); ?>
															<?php if ($val = null) { ?>
																<select class="form-control" name="status_approval" id="status_approval" onchange="ChangeCRStatus()">
																	<option value="submission_to_be_reviewed">Pending</option>
																</select>
															<?php } else { ?>
																<select class="form-control" name="status_approval" id="status_approval" onchange="ChangeCRStatus()">
																	<option value="<?php echo $data[0]['change_request_approval_type']; ?>"><?php echo $data[0]['change_request_approval_type']; ?></option>
																</select>
															<?php } ?>
															<?php if ($_GET['act'] == "review" || $_GET['act'] == "complete") { ?>
																<input type="text" class="form-control form-control-sm" id="status_approval" name="status_approval" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																										echo "disabled";
																																									} ?> value="<?php echo $data[0]['change_request_approval_type']; ?>">
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3">
														<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Reason</label>
														<div class="col-sm-9">
															<?php $val = isset($ddata17['reason_request']); ?>
															<?php if ($val = null) { ?>
																<textarea class="form-control" name="reason_request" id="reason_request" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																						echo "disabled"; ?> value="<?php echo $ddata17['reason_request'];
																																												} ?>">
								<?php } else { ?>
								<textarea class="form-control" name="reason_request" id="reason_request" rows="3" value = "<?php echo $ddata17['reason_request']; ?>"<?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																											echo "disabled";
																																										} ?> readonly><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																															echo $ddata17['reason_request'];
																																														} ?></textarea>
															<?php } ?>
														</div>
													</div>
													<div class="row mb-3" id="crclosing">
														<h2>Change Request Closing</h2>
														<div class="row mb-3">
															<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Hasil Akhir</label>
															<div class="col-sm-9">
																<?php $val = isset($ddata17['hasil_akhir']); ?>
																<?php if ($val = null) { ?>
																	<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																					echo "disabled";
																																				} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																							echo $ddata17['hasil_akhir'];
																																						} ?></textarea>
																<?php } else { ?>
																	<textarea class="form-control" name="hasil_akhir" id="hasil_akhir" rows="3" value="<?php echo $ddata17['hasil_akhir']; ?>" <?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																	echo "disabled";
																																																} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																			echo $ddata17['hasil_akhir'];
																																																		} ?></textarea>
																<?php } ?>
															</div>
														</div>
														<div class="row mb-3">
															<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status CR</label>
															<div class="col-sm-9">
																<?php $val = isset($data[0]['change_request_status']); ?>
																<?php if ($val == null) { ?>
																	<select class="form-control" name="status_request" id="status_request">
																		<option value="submission_to_be_reviewed">Incomplete</option>
																		<option value="all_done">Complete</option>
																		<option value="submission_rejected">Rejected</option>
																	</select>
																<?php } else { ?>
																	<select class="form-control" name="status_request" id="status_request">
																		<option value="<?php echo $data[0]['change_request_status']; ?>"><?php echo $data[0]['change_request_status']; ?></option>
																		<option value="submission_to_be_reviewed">Incomplete</option>
																		<option value="all_done">Complete</option>
																		<option value="submission_rejected">Rejected</option>
																	</select>
																<?php } ?>
															</div>
														</div>
														<div class="row mb-3">
															<label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm" id="reason_rejected_text">Reason</label>
															<div class="col-sm-9">
																<?php $val = isset($ddata17['reason_rejected']); ?>
																<?php if ($val = null) { ?>
																	<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" <?php if ($_GET['act'] == 'review') {
																																							echo "disabled";
																																						} ?>><?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																									echo $ddata17['reason_rejected'];
																																								} ?></textarea>
																<?php } else { ?>
																	<textarea class="form-control" name="reason_rejected" id="reason_rejected" rows="3" value="<?php echo $ddata17['reason_rejected']; ?>" <?php if ($_GET['act'] == 'review') {
																																																				echo "disabled";
																																																			} ?>><?php if ($_GET['act'] == 'edit' || $_GET['act'] == 'review' || $_GET['act'] == 'complete') {
																																																						echo $ddata17['reason_rejected'];
																																																					} ?></textarea>
																<?php } ?>
															</div>
														</div>
													</div>
												<?php } ?>
											<?php } ?>
										<?php } ?>
										<label for="inputCID3a" class="col-sm-12 col-form-label col-form-label-sm"><b>Change Control Board : Eksternal</b></label>
										<div class="row mb-3 border-top pt-3">
											<label for="inputCID3a" class="col-sm-3 col-form-label col-form-label-sm" id="cust_text"><b>Customer PIC</b></label>
											<div class="col-sm-9">
												<?php if ($_GET['act'] == 'review') { ?>
													<?php $val = isset($data9[0]['name']);
													if ($val == null) {
														'';
													} else {
													?>
														<table class="table">
															<thead>
																<tr>
																	<th scope="col">#</th>
																	<th scope="col">Name</th>
																	<th scope="col">Position</th>
																</tr>
															</thead>
															<tbody>
																<?php do { ?>
																	<?php
																	$j = 1;
																	$val = isset($data9[0]['name']);
																	if ($val == null) {
																		'';
																	} else {
																		$val = $data9[0]['name'];
																	}
																	$val2 = isset($data9[0]['position']);
																	if ($val2 == null) {
																		'';
																	} else {
																		$val2 = $data9[0]['position'];
																	}
																	echo "<tr><td>$j</td><td>$val</td><td>$val2</td></tr>";
																	$j++; ?>
																<?php } while ($data9[0] = $data9[1]->fetch_assoc()); ?>
															</tbody>
														</table>
												<?php }
												} ?>
												<?php if ($_GET['act'] == 'add') { ?>
													<div id="newRow-customer-pic"></div>
													<button id="addRow-customer-pic" type="button" class="btn btn-info">+</button>
												<?php } elseif ($_GET['act'] == 'edit') { ?>
													<div class="p-0 mb-3" id="inputFormRow-customer-pic">
														<?php $val = isset($data9[0]['name']); ?>
														<?php if ($val == null) { ?>
															<div id="newRow-customer-pic"></div>
															<button id="addRow-customer-pic" type="button" class="btn btn-info">+</button>
														<?php } else { ?>
															<?php do { ?>
																<input type="text" name="cp_id[]" id="cp_id[]" class="form-control form-control-xl mb-3" value="<?php echo $data9[0]['cp_id']; ?>">
																<input type="text" name="customer_pic_name[]" id="customer_pic_name[]" class="form-control form-control-xl mb-3" value="<?php echo $data9[0]['name']; ?>" autocomplete="off" required>
																<input type="text" name="customer_pic_position[]" id="customer_pic_position[]" class="form-control form-control-xl mb-3" value="<?php echo $data9[0]['position']; ?>" autocomplete="off" required>
															<?php } while ($data9[0] = $data9[1]->fetch_assoc()); ?>
															<!-- <button id="removeRow-customer-pic" type="button" class="btn btn-danger">Remove</button> -->
															<!-- <div id="newnewRow-customer-pic"></div>   -->
														<?php } ?>
													</div>
													<!-- <button id="editRow-customer-pic" type="button" class="btn btn-info">+</button>   -->
												<?php } ?>
											</div>
											<div class="col-sm-9 text-right">
											</div>
										</div>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- TAB File Upload -->
				<?php
				global $DB;
				$tblname = 'cfg_web';
				$condition = 'config_key="MEDIA_CHANGE_REQUEST" AND parent=8';
				$folders = $DB->get_data($tblname, $condition);
				$FolderName = 'change_request';
				$val = isset($dcr['customer_name']);
				if ($val == null) {
					$sFolderTarget = $folders[0]['config_value'] . '/' . $_GET['project_code'] . '/' . $FolderName . '/';
				} else {
					$sFolderTarget = $folders[0]['config_value'] . '/' . $dcr['customer_code'] . '_' . str_replace(' ', '_', $dcr['customer_name']) . '/' . $dcr['project_code'] . '/' . $FolderName . '/';
				}
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
							$file = 'media/documents/projects/index.php';
							$newfile = $xFolder . '/index.php';
							// isset($file, $newfile);
							// if (!copy($file, $newfile)) {
							// 	echo "";
							// }
						}
					}
				}
				?>
				<script>
					var FolderTarget = "<?php echo $sFolderTarget; ?>";
					document.cookie = "FolderTarget = " + FolderTarget;
				</script>
				<div class="tab-pane fade show" id="uploadx" role="tabpanel" aria-labelledby="crtype-tab">
					<div class="card shadow mb-4">
						<!-- Card Body -->
						<div class="card-body">
							<?php if ($_GET['act'] == 'add' || $_GET['act'] == 'edit' || $_GET['act'] == 'review') { ?>
								<div class="row">
									<div class="col-lg-12">
										<div class="row mb-3">
											<button type="button" class="btn btn-primary" name="btn_upload" id="btn_upload" data-bs-toggle="modal" data-bs-target="#fileupload">Upload File</button>
										</div>
									</div>
									<div class="col-lg-12">
										<div id="fileList"></div>
									</div>
									<div class="col-lg-12">
										<?php
										$d = dir($sFolderTarget);
										// echo "Handle: " . $d->handle . "<br/>";
										// echo "Path: " . $d->path . "<br/>";
										// echo '<div class="list-group">';
										?>
										<table class="table table-sm table-hover">
											<thead>
												<tr>
													<th scope="col">#</th>
													<th scope="col">Nama File</th>
													<th scope="col">Size</th>
													<!-- <th scope="col">Created</th> -->
													<th scope="col">Modified</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$i = 0;
												while (false !== ($entry = $d->read())) {
													if ($entry != '.' && $entry != '..' && $entry != 'index.php') {
														$fstat = stat($sFolderTarget . $entry);
												?>
														<?php //if($entry = $_GET['cr_no']) {
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
															<!-- <td><?php echo date('d-M-Y G:i:s', $fstat['ctime']); ?></td> -->
															<td><?php echo date('d-M-Y G:i:s', $fstat['mtime']); ?></td>
														</tr>
													<?php
														// echo '<a href="'.$sFolderTarget.$entry.'" target="_blank" class="list-group-item list-group-item-action">'.$entry.'</a>';
														$i++;
													}
												}
												if ($i == 0) {
													?>
													<tr>
														<td colspan="5">No Files available.</td>
													</tr>
												<?php
													// echo 'No files available.';
												}
												?>
											</tbody>
										</table>
										<?php
										// echo '</div>';
										$d->close();
										?>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>

		<?php     }
		} ?>
	</div>
	<!-- <input type="submit" class="btn btn-secondary" name="cancel" value="Cancel"> -->
	<!-- <a href="https://msizone.mastersystem.co.id/index.php?mod=change_request">Cancel</a> -->
	<button type="cancel" class="btn btn-secondary" onclick="window.location='https://msizone.mastersystem.co.id/index.php?mod=change_request';return false;">Cancel</button>
	<?php if (isset($_GET['act']) && ($_GET['act'] == 'edit' && $_GET['type'] == 'Implementation')) { ?>
		<input type="submit" class="btn btn-primary" name="update_imp" id="update_imp" value="Update">
		<?php if ($ddata0['status'] == 'Belum' && $ddata['perlu_backup'] == 'Ya') { ?>
			<input type="submit" class="btn btn-primary" name="req_backup" id="req_backup" value="Request Backup">
		<?php } else { ?>
		<?php } ?>
	<?php } else if (isset($_GET['act']) && ($_GET['act'] == 'edit' && $_GET['type'] == 'Maintenance')) { ?>
		<input type="submit" class="btn btn-primary" name="update_mt" id="update_mt" value="Update">
		<?php if ($ddata0['status'] == 'Belum' && $ddata['perlu_backup'] == 'Ya') { ?>
			<input type="submit" class="btn btn-primary" name="req_backup" id="req_backup" value="Request Backup">
		<?php } else { ?>
		<?php } ?>
	<?php } else if (isset($_GET['act']) && ($_GET['act'] == 'edit' && $_GET['type'] == 'IT')) { ?>
		<input type="submit" class="btn btn-primary" name="update_it" id="update_it" value="Update">
	<?php } else if (isset($_GET['act']) && ($_GET['act'] == 'edit' && $_GET['type'] == 'Sales/Presales')) { ?>
		<input type="submit" class="btn btn-primary" name="update_sb" id="update_sb" value="Update">
	<?php } elseif (isset($_GET['act']) && $_GET['act'] == 'add' && $_GET['type'] == 'Implementation') { ?>
		<input type="submit" class="btn btn-primary" name="save_implemen" id="save_implemen" value="Save">
	<?php } elseif (isset($_GET['act']) && $_GET['act'] == 'add' && $_GET['type'] == 'Maintenance') { ?>
		<input type="submit" class="btn btn-primary" name="save_maintenance" id="save_maintenance" value="Save">
	<?php } elseif (isset($_GET['act']) && $_GET['act'] == 'add' && $_GET['type'] == 'IT') { ?>
		<input type="submit" class="btn btn-primary" name="save_it" id="save_it" value="Save">
	<?php } elseif (isset($_GET['act']) && $_GET['act'] == 'add' && $_GET['type'] == 'Sales/Presales') { ?>
		<input type="submit" class="btn btn-primary" name="save_sb" id="save_sb" value="Save">
	<?php } elseif (isset($_GET['try']) && $_GET['try'] == 'delete') { ?>
		<!-- <input type="submit" class="btn btn-primary" name="delete" id="delete" value="Delete"> -->
		<a href="components/modules/change_request/delete.php?cr_no=<?php echo $ddata['cr_no']; ?>" class="btn btn-primary btn-danger alert_notif">Hapus</a>
	<?php } elseif (isset($_GET['try']) && $_GET['try'] == 'approve') { ?>
		<input type="submit" class="btn btn-primary" name="save_approve" id="save_approve" value="Approve">
		<input type="submit" class="btn btn-primary" name="reject_cr" id="reject_cr" value="Reject">
	<?php } elseif (isset($_GET['try']) && $_GET['try'] == 'close') { ?>
		<?php
		$get_sql = $DBCR->get_sql("SELECT * FROM sa_customer_pic WHERE cr_no='" . $_GET['cr_no'] . "'");
		if (isset($get_sql[0]['name'])) { ?>
			<button type="button" class="btn btn-primary" name="btn_upload" id="btn_upload" data-bs-toggle="modal" data-bs-target="#fileupload_close">Close</button>
			<input type="submit" class="btn btn-primary" name="cancel_cr" id="cancel_cr" value="Cancel CR">
		<?php } else { ?>
			<input type="submit" class="btn btn-primary" name="close_cr" id="close_cr" value="Close">
			<input type="submit" class="btn btn-primary" name="cancel_cr" id="cancel_cr" value="Cancel CR">
		<?php } ?>
	<?php } ?>
	<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous">
	</script>
	<!-- jangan lupa menambahkan script js sweet alert di bawah ini  -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.7/dist/sweetalert2.all.min.js"></script>
	<?php if (@$_SESSION['sukses']) { ?>
		<script>
			Swal.fire({
				icon: 'success',
				title: 'Sukses',
				text: 'data berhasil dihapus',
				timer: 3000,
				showConfirmButton: false
			})
		</script>
	<?php unset($_SESSION['sukses']);
	}
	?>


	<script>
		$('.alert_notif').on('click', function() {
			var getLink = $(this).attr('href');
			Swal.fire({
				title: "Yakin hapus data?",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#d33',
				confirmButtonText: 'Ya',
				cancelButtonColor: '#3085d6',
				cancelButtonText: "Batal"

			}).then(result => {
				//jika klik ya maka arahkan ke proses.php
				if (result.isConfirmed) {
					window.location.href = getLink
				}
			})
			return false;
		});
	</script>
</form>

<div class="modal fade" id="fileupload" tabindex="-1" aria-labelledby="saveLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="saveLabel"><b>Upload File</b></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row mb-3">
					<!-- <div class="col-sm-12"> -->
					<link href="components/modules/upload/upload.css" rel="stylesheet" type="text/css" />
					<form id="upload_form" enctype="multipart/form-data" method="post">
						<div>
							<div><label for="image_file">Please select image file</label></div>
							<div><input type="file" name="image_file" id="image_file" onchange="fileSelected();" /></div>
						</div>
						<div>
							<input type="button" value="Upload" onclick="startUploading()" />
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
					<!-- </div> -->
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<!-- <input type="submit" class="btn btn-primary" name="reject_service_budget" id="reject_service_budget" value="Reject"> -->
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="fileupload_close" tabindex="-1" aria-labelledby="saveLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="saveLabel"><b>Upload File</b></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row mb-3">
					<!-- <div class="col-sm-12"> -->
					<link href="components/modules/upload/upload.css" rel="stylesheet" type="text/css" />
					<form id="upload_form" enctype="multipart/form-data" method="post">
						<div>
							<div><label for="image_file">Please select file</label></div>
							<div><input type="file" name="image_file" id="image_file" onchange="fileSelected();" /></div>
						</div>
						<div>
							<input type="button" value="Upload" onclick="startUploading()" />
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
					<!-- </div> -->
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<!-- <input type="submit" class="btn btn-primary" name="reject_service_budget" id="reject_service_budget" value="Reject"> -->
				</div>
			</div>
		</div>
	</div>
</div>

<!-- TAB Change Request Type -->
<div class="tab-pane fade show" id="historyx" role="tabpanel" aria-labelledby="crtype-tab">

</div>

<script>
	function changeCRtype() {

		if (document.getElementById("cr_type").value == 'maintenance') {
			document.getElementById("changecostplan-tab").style.display = "";
			document.getElementById("impact_it").style.display = "none";
			document.getElementById("changeimpact").style.display = "";
			document.getElementById("Affected_ci").style.display = "";
			document.getElementById("newRow-affected-ci").style.display = "";
			document.getElementById("addRow-affected-ci").style.display = "";
			document.getElementById("technical_assesment_text").style.display = "none";
			document.getElementById("ta_pic").style.display = "none";
			document.getElementById("technical_assesment").style.display = "none";
			document.getElementById("mandays_text").style.display = "none";
			document.getElementById("newRow-mandays").style.display = "none";
			document.getElementById("addRow-mandays").style.display = "none";
			document.getElementById("bb_text").style.display = "none";
			document.getElementById("bb_pic").style.display = "none";
			document.getElementById("business_benefit").style.display = "none";
			document.getElementById("others_text").style.display = "none";
			document.getElementById("newRow-others").style.display = "none";
			document.getElementById("addRow-others").style.display = "none";
			document.getElementById("tos").style.display = "none";
			document.getElementById("sonumber").style.display = "none";
			document.getElementById("impact_services_it").style.display = "none";
			document.getElementById("service_requirement_it").style.display = "none";
			document.getElementById("technical_feasibility_it").style.display = "none";
			document.getElementById("bb_it").style.display = "none";
			document.getElementById("ta").style.display = "";
			document.getElementById("bb").style.display = "";
			document.getElementById("prerequisite").style.display = "";
			document.getElementById("cust_req").style.display = "none";
			document.getElementById("ra_implemen").style.display = "none";
			document.getElementById("ra_mt").style.display = "";
			document.getElementById("ra_it").style.display = "none";

		} else if (document.getElementById("cr_type").value == 'implementation') {
			document.getElementById("changecostplan-tab").style.display = "";
			document.getElementById("impact_it").style.display = "none";
			document.getElementById("changeimpact").style.display = "";
			document.getElementById("Affected_ci").style.display = "none";
			document.getElementById("newRow-affected-ci").style.display = "none";
			document.getElementById("addRow-affected-ci").style.display = "none";
			document.getElementById("technical_assesment_text").style.display = "";
			document.getElementById("ta_pic").style.display = "";
			document.getElementById("technical_assesment").style.display = "";
			document.getElementById("mandays_text").style.display = "";
			document.getElementById("newRow-mandays").style.display = "";
			document.getElementById("addRow-mandays").style.display = "";
			document.getElementById("bb_text").style.display = "";
			document.getElementById("bb_pic").style.display = "";
			document.getElementById("business_benefit").style.display = "";
			document.getElementById("others_text").style.display = "";
			document.getElementById("newRow-others").style.display = "";
			document.getElementById("addRow-others").style.display = "";
			document.getElementById("tos").style.display = "";
			document.getElementById("sonumber").style.display = "";
			document.getElementById("impact_services_it").style.display = "none";
			document.getElementById("service_requirement_it").style.display = "none";
			document.getElementById("technical_feasibility_it").style.display = "none";
			document.getElementById("bb_it").style.display = "none";
			document.getElementById("ta").style.display = "";
			document.getElementById("bb").style.display = "";
			document.getElementById("prerequisite").style.display = "";
			document.getElementById("cust_req").style.display = "none";
			document.getElementById("ra_implemen").style.display = "";
			document.getElementById("ra_mt").style.display = "none";
			document.getElementById("ra_it").style.display = "none";

		} else if (document.getElementById("cr_type").value == 'it') {
			document.getElementById("changecostplan-tab").style.display = "none";
			document.getElementById("impact_it").style.display = "";
			document.getElementById("changeimpact").style.display = "none";
			document.getElementById("Affected_ci").style.display = "";
			document.getElementById("newRow-affected-ci").style.display = "";
			document.getElementById("addRow-affected-ci").style.display = "";
			document.getElementById("tos").style.display = "none";
			document.getElementById("sonumber").style.display = "none";
			document.getElementById("impact_services_it").style.display = "";
			document.getElementById("service_requirement_it").style.display = "";
			document.getElementById("technical_feasibility_it").style.display = "";
			document.getElementById("bb_it").style.display = "";
			document.getElementById("ta").style.display = "none";
			document.getElementById("bb").style.display = "none";
			document.getElementById("others_text").style.display = "none";
			document.getElementById("newRow-others").style.display = "none";
			document.getElementById("addRow-others").style.display = "none";
			document.getElementById("prerequisite").style.display = "none";
			document.getElementById("cust_req").style.display = "";
			document.getElementById("ra_implemen").style.display = "none";
			document.getElementById("ra_mt").style.display = "none";
			document.getElementById("ra_it").style.display = "";
			document.getElementById("mandays_text").style.display = "none";
			document.getElementById("newRow-mandays").style.display = "none";
			document.getElementById("addRow-mandays").style.display = "none";

		}
	}

	function changeCostType() {
		if (documment.getElementById("cost_type").value == 'Chargeable') {
			document.getElementById("nonchargeable_cost").style.display = "none";
			document.getElementById("changereason_non").style.display = "none";
			document.getElementById("pochargeable").style.display = "";
		} else {
			document.getElementById("nonchargeable_cost").style.display = "";
			document.getElementById("changereason_non").style.display = "";
			document.getElementById("pochargeable").style.display = "none";
		}
	}

	function changeResponsibility() {
		if (documment.getElementById("nccr").value == 'Sales') {
			document.getElementById("changereason_non").style.display = "none";
		} else {
			document.getElementById("changereason_non").style.display = "";
		}
	}

	function ChangeCRStatus() {
		if (document.getElementById("status_approval").value == 'submission_approved') {
			document.getElementById("crclosing").style.display = "";
			document.getElementById("crclose").style.display = "";
			document.getElementById("reason-approve").style.display = "";
			document.getElementById("reason-review").style.display = "";
		} else {
			document.getElementById("crclosing").style.display = "none";
			document.getElementById("crclose").style.display = "none";
			document.getElementById("reason-approve").style.display = "none";
			document.getElementById("reason-review").style.display = "none";
		}
	}

	function CostImpact() {
		if (document.getElementById("cost_impact").value == 'Ada') {
			document.getElementById("changecostplan-tab").style.display = "";
		} else {
			document.getElementById("changecostplan-tab").style.display = "none";
		}
	}

	function changeClass() {
		if (document.getElementById("classification").value == 'Cost Change') {
			document.getElementById("assesment-tab").style.display = "none";
			document.getElementById("changeimplementationplan-tab").style.display = "";
		} else {
			document.getElementById("assesment-tab").style.display = "";
			document.getElementById("changeimplementationplan-tab").style.display = "none";
		}
	}

	function Approval() {
		if (document.getElementById("type_approval").value == 'internal') {
			document.getElementById("cust_text").style.display = "none";
			document.getElementById("newRow-customer-pic").style.display = "none";
			document.getElementById("addRow-customer-pic").style.display = "none";
			document.getElementById("pic_text").style.display = "";
			document.getElementById("pic_apr").style.display = "";
		} else {
			document.getElementById("cust_text").style.display = "";
			document.getElementById("newRow-customer-pic").style.display = "";
			document.getElementById("addRow-customer-pic").style.display = "";
			document.getElementById("pic_text").style.display = "none";
			document.getElementById("pic_apr").style.display = "none";
		}
	}

	function changeSO() {
		if (document.getElementById("so_number").value == null) {
			document.getElementById("assesment-tab").style.display = "none";
		} else {
			document.getElementById("assesment-tab").style.display = "";
		}
	}

	function TypeCR() {
		if (document.getElementById("type_of_service").value == 'Gold') {
			document.getElementById("type_gold").style.display = "";
			document.getElementById("type_silver").style.display = "none";
			document.getElementById("type_bronze").style.display = "none";
			document.getElementById("xago").style.display = "";
			document.getElementById("xasi").style.display = "none";
			document.getElementById("xabro").style.display = "none";
		} else if (document.getElementById("type_of_service").value == 'Silver') {
			document.getElementById("type_gold").style.display = "none";
			document.getElementById("type_silver").style.display = "";
			document.getElementById("type_bronze").style.display = "none";
			document.getElementById("xago").style.display = "none";
			document.getElementById("xasi").style.display = "";
			document.getElementById("xabro").style.display = "none";
			document.getElementById("xabro").value = "none";
			document.getElementById("xago").value = "none";
		} else if (document.getElementById("type_of_service").value == 'Bronze') {
			document.getElementById("type_gold").style.display = "none";
			document.getElementById("type_silver").style.display = "none";
			document.getElementById("type_bronze").style.display = "";
			document.getElementById("xago").style.display = "none";
			document.getElementById("xasi").style.display = "none";
			document.getElementById("xabro").style.display = "";
		} else if (document.getElementById("type_of_service").value == undefined) {
			document.getElementById("type_gold").style.display = "none";
			document.getElementById("type_silver").style.display = "none";
			document.getElementById("type_bronze").style.display = "none";
			document.getElementById("xago").style.display = "none";
			document.getElementById("xasi").style.display = "none";
			document.getElementById("xabro").style.display = "none";
		}
	}

	function TypeApr() {

	}


	function format_amount_idr() {
		document.getElementById('mandays_tm').value = format(document.getElementById('mandays_tm').value);
		document.getElementById('mandays_value').value = format(document.getElementById('mandays_value').value);
		document.getElementById('others_price').value = format(document.getElementById('others_price').value);
	}
</script>

<script>
	// AFFECTED CI
	$("#addRow-affected-ci").click(function() {
		var html = '';
		html += '<div class="p-0" id="inputFormRow-affected-ci">';
		html += '<div class="row">';
		html += '<div class="col-6 table-bordered"><textarea class="form-control" name="serial_number[]" id="serial_number[]" cols="12" rows="2"></textarea></div>';
		html += '<div class="col-6 table-bordered"><textarea class="form-control" name="part_number[]" id="part_number[]" cols="12" rows="2"></textarea></div>';
		html += '<button id="removeRow-affected-ci" type="button" class="btn btn-danger mt-2">Remove</button>';
		html += '</div>';
		html += '</div>';

		$('#newRow-affected-ci').append(html);
	});

	$(document).on('click', '#removeRow-affected-ci', function() {
		$(this).closest('#inputFormRow-affected-ci').remove();
	});
</script>

<script>
	// RISK ASSESMENT Implementation
	$("#addRow-risk-assesment").click(function() {
		var html = '';
		html += '<div class="p-0 mb-3" id="inputFormRow-risk-assesment">';
		html += '<input type="text" name="risk_description[]" class="form-control form-control-xl mb-3" placeholder="Enter description" autocomplete="off" required>';
		html += '<input type="text" name="risk_mitigation[]" class="form-control form-control-xl mb-3" placeholder="Enter migration" autocomplete="off" required>';
		html += '<select class="form-control mb-3" name="risk_pic[]" id="risk_pic" required>';
		<?php do { ?>
			html += "<option value="
			<?php echo $pic_ra_imp[0]['employee_email']; ?> > <?php echo $pic_ra_imp[0]['employee_name']; ?> < /option>";
		<?php } while ($pic_ra_imp[0] = $pic_ra_imp[1]->fetch_assoc()); ?>
		html += '</select>';
		html += '<button id="removeRow-risk-assesment" type="button" class="btn btn-danger">Remove</button>';
		html += '</div>';

		$('#newRow-risk-assesment').append(html);
	});

	$(document).on('click', '#removeRow-risk-assesment', function() {
		$(this).closest('#inputFormRow-risk-assesment').remove();
	});
</script>

<script>
	// RISK ASSESMENT Maintenance
	$("#addRow-risk-assesment-maintenance").click(function() {
		var html = '';
		html += '<div class="p-0 mb-3" id="inputFormRow-risk-assesment">';
		html += '<input type="text" name="risk_description[]" class="form-control form-control-xl mb-3" placeholder="Enter description" autocomplete="off" required>';
		html += '<input type="text" name="risk_mitigation[]" class="form-control form-control-xl mb-3" placeholder="Enter migration" autocomplete="off" required>';
		html += '<select class="form-control mb-3" name="risk_pic[]" id="risk_pic" required>';
		<?php do { ?>
			html += "<option value="
			<?php echo $pic_ra_mt[0]['employee_email']; ?> "><?php echo $pic_ra_mt[0]['employee_name']; ?></option>";
		<?php } while ($pic_ra_mt[0] = $pic_ra_mt[1]->fetch_assoc()); ?>
		html += '</select>';
		html += '<button id="removeRow-risk-assesment" type="button" class="btn btn-danger">Remove</button>';
		html += '</div>';

		$('#newRow-risk-assesment-maintenance').append(html);
	});

	$(document).on('click', '#removeRow-risk-assesment', function() {
		$(this).closest('#inputFormRow-risk-assesment').remove();
	});
</script>

<script>
	// RISK ASSESMENT IT
	$("#addRow-risk-assesment-it").click(function() {
		var html = '';
		html += '<div class="p-0 mb-3" id="inputFormRow-risk-assesment">';
		html += '<input type="text" name="risk_description[]" class="form-control form-control-xl mb-3" placeholder="Enter description" autocomplete="off" required>';
		html += '<input type="text" name="risk_mitigation[]" class="form-control form-control-xl mb-3" placeholder="Enter migration" autocomplete="off" required>';
		html += '<select class="form-control mb-3" name="risk_pic[]" id="risk_pic" required>';
		<?php do { ?>
			html += "<option value="
			<?php echo $pic_ra_it[0]['employee_email']; ?> "><?php echo $pic_ra_it[0]['employee_name']; ?></option>";
		<?php } while ($pic_ra_it[0] = $pic_ra_it[1]->fetch_assoc()); ?>
		html += '</select>';
		html += '<button id="removeRow-risk-assesment" type="button" class="btn btn-danger">Remove</button>';
		html += '</div>';

		$('#newRow-risk-assesment-it').append(html);
	});

	$(document).on('click', '#removeRow-risk-assesment', function() {
		$(this).closest('#inputFormRow-risk-assesment').remove();
	});
</script>

<script>
	//Detail of Change
	$("#addRow-detailchange").click(function() {
		var html = '';
		html += '<div class="p-0" id="inputFormRow-detailchange">';
		html += '<div class="row">';
		html += '<div class="col-4 table-bordered"><textarea class="form-control" name="detail[]" id="detail[]" rows="1"></textarea></div>';
		html += '<div class="col-4 table-bordered"><textarea class="form-control" name="item[]" id="item[]" rows="1"></textarea></div>';
		html += '<div class="col-4 table-bordered"><textarea class="form-control" name="perubahan[]" id="perubahan[]" rows="1"></textarea></div>';
		html += '<button id="removeRow-detailchange" type="button" class="btn btn-danger col-12">Remove</button>';
		html += '</div>';
		html += '</div>';

		$('#newRow-detailchange').append(html);
	});

	$(document).on('click', '#removeRow-detailchange', function() {
		$(this).closest('#inputFormRow-detailchange').remove();
	});
</script>

<script>
	//Mandays
	$("#addRow-mandays").click(function() {
		var html = '';
		html += '<div class="p-0" id="inputFormRow-mandays">';
		html += '<div class="row">';
		html += '<div class="col-4 table-bordered"><select class="form-control mb-3" name="mandays_tor[]" id="mandays_tor[]"">';
		html += "<option></option>";
		html += "<option value='Project Director'>Project Director</option>";
		html += "<option value='Project Manager'>Project Manager</option>";
		html += "<option value='Project Coordinator'>Project Coordinator</option>";
		html += "<option value='Project Admin'>Project Admin</option>";
		html += "<option value='Engineer Expert'>Engineer Expert</option>";
		html += "<option value='Engineer Professional'>Engineer Professional</option>";
		html += "<option value='Engineer Associate'>Engineer Associate</option>";
		html += '</select></div>';
		// html += '<div class="col-4 table-bordered"><textarea class="form-control" name="mandays_tor[]" id="mandays_tor[]" rows="1"></textarea></div>';
		html += '<div class="col-4 table-bordered"><input type="number" class="form-control" name="mandays_tm[]" id="mandays_tm[]" rows="1"></div>';
		html += '<div class="col-4 table-bordered"><textarea class="form-control" name="mandays_value[]" id="mandays_value[]" rows="1" readonly></textarea></div>';
		html += '<button id="removeRow-mandays" type="button" class="btn btn-danger col-12">Remove</button>';
		html += '</div>';
		html += '</div>';

		$('#newRow-mandays').append(html);
	});

	$(document).on('click', '#removeRow-mandays', function() {
		$(this).closest('#inputFormRow-mandays').remove();
	});
</script>

<script>
	//Others
	$("#addRow-others").click(function() {
		var html = '';
		html += '<div class="p-0" id="inputFormRow-others">';
		html += '<div class="row">';
		html += '<div class="col-4 table-bordered"><select class="form-control mb-3" name="others_item[]" id="others_item[]">';
		html += "<option></option>";
		html += "<option value='Outsourcing Plan'>Outsourcing Plan</option>";
		html += "<option value='BPD'>BPD</option>";
		html += "<option value='Maintenance Package Price'>Maintenance Package Price</option>";
		html += "<option value='Existing Backup Unit'>Existing Backup Unit</option>";
		html += "<option value='Investment Backup Unit'>Investment Backup Unit</option>";
		html += "<option value='Extended Warranty Cisco'>Extended Warranty Cisco</option>";
		html += "<option value='Extended Warranty Non-Cisco'>Extended Warranty Non-Cisco</option>";
		html += "<option value='Price PO'>Price PO</option>";
		html += "<option value='Band'>Band</option>";
		html += "<option value='Others'>Others</option>";
		html += '</select></div>';
		html += '<div class="col-4 table-bordered"><textarea class="form-control" name="others_detail[]" id="others_detail[]" rows="1"></textarea></div>';
		html += '<div class="col-4 table-bordered"><input type="number" class="form-control" name="others_price[]" id="others_price[]" rows="1"></div>';
		html += '<button id="removeRow-others" type="button" class="btn btn-danger">Remove</button>';
		html += '</div>';
		html += '</div>';

		$('#newRow-others').append(html);
	});

	$(document).on('click', '#removeRow-others', function() {
		$(this).closest('#inputFormRow-others').remove();
	});
</script>

<script>
	// DETAIL PLAN
	$("#addRow-plan").click(function() {
		var html = '';
		html += '<div class="p-0" id="inputFormRow-plan">';
		html += '<div class="row">';
		html += '<div class="col-3 table-bordered"><textarea class="form-control" name="working_detail_plan[]" id="working_detail_plan[]" rows="1"></textarea></div>';
		html += '<div class="col-2 table-bordered"><input type="date" name="time[]" class="form-control form-control-sm mb-3" placeholder="Time" autocomplete="off" required></div>';
		html += '<div class="col-2 table-bordered"><input type="date" name="finish_time[]" class="form-control form-control-sm mb-3" placeholder="Time_End" autocomplete="off" required></div>';
		html += '<div class="col-3 table-bordered"><select class="form-control mb-3" name="dp_pic[]" id="dp_pic" required>';
		<?php do {  ?>
			html += "<option value='<?php echo $pic_dp[0]['employee_email']; ?>'><?php echo $pic_dp[0]['employee_name']; ?></option>";
		<?php } while ($pic_dp[0] = $pic_dp[1]->fetch_assoc()); ?>
		html += '</select></div>';
		html += '<div class="col-2 table-bordered"><button id="removeRow-plan" type="button" class="btn btn-danger">Remove</button></div>';
		html += '</div>';
		html += '</div>';

		$('#newRow-plan').append(html);
	});

	$(document).on('click', '#removeRow-plan', function() {
		$(this).closest('#inputFormRow-plan').remove();
	});
</script>
<script>
	//FALLBACK
	$("#addRow-fallback").click(function() {
		var html = '';
		html += '<div class="p-0" id="inputFormRow-fallback">';
		html += '<div class="row">';
		html += '<div class="col-5 table-bordered"><textarea type="text" class="form-control" name="working_detail_fallback[]" id="working_detail_fallback[]" rows="1" required></textarea></div>';
		html += '<div class="col-4 table-bordered"><select class="form-control mb-3" name="fp_pic[]" id="fp_pic[]" required>';
		<?php do {  ?>
			html += "<option value='<?php echo $pic_fp[0]['employee_email']; ?>'><?php echo $pic_fp[0]['employee_name']; ?></option>";
		<?php } while ($pic_fp[0] = $pic_fp[1]->fetch_assoc()); ?>
		html += '</select>';
		html += '</div>';
		html += '<div class="col-3 table-bordered"><button id="removeRow-fallback" type="button" class="btn btn-danger">Remove</button></div>';
		html += '</div>';
		html += '</div>';

		$('#newRow-fallback').append(html);
	});

	$(document).on('click', '#removeRow-fallback', function() {
		$(this).closest('#inputFormRow-fallback').remove();
	});
</script>
<script>
	//Customer PIC
	$("#addRow-customer-pic").click(function() {
		var html = '';
		html += '<div class="p-0 mb-3" id="inputFormRow-customer-pic">';
		html += '<input type="text" name="customer_pic_name[]" class="form-control form-control-xl mb-3" placeholder="PIC Name" autocomplete="off" required>';
		html += '<input type="text" name="customer_pic_position[]" class="form-control form-control-xl mb-3" placeholder="PIC Position" autocomplete="off" required>';
		html += '<button id="removeRow-customer-pic" type="button" class="btn btn-danger">Remove</button>';
		html += '</div>';

		$('#newRow-customer-pic').append(html);
	});

	$(document).on('click', '#removeRow-customer-pic', function() {
		$(this).closest('#inputFormRow-customer-pic').remove();
	});
</script>
<script>
	//EDIT Customer PIC
	$("#editRow-customer-pic").click(function() {
		var html = '';
		html += '<div class="p-0 mb-3" id="inputFormRow-customer-pic">';
		html += '<input type="text" name="customer_pic_name[]" class="form-control form-control-xl mb-3" placeholder="PIC Name" autocomplete="off" required>';
		html += '<input type="text" name="customer_pic_position[]" class="form-control form-control-xl mb-3" placeholder="PIC Position" autocomplete="off" required>';
		html += '<button id="removeRow-customer-pic" type="button" class="btn btn-danger">Remove</button>';
		html += '</div>';

		$('#newnewRow-customer-pic').append(html);
	});

	$(document).on('click', '#removeRow-customer-pic', function() {
		$(this).closest('#inputFormRow-customer-pic').remove();
	});
</script>
<script>
	//CR Reviewer
	$("#addRow-cr-reviewer").click(function() {
		var html = '';
		html += '<div class="p-0 mb-3" id="inputFormRow-cr-reviewer">';
		html += '<select class="form-control mb-3" name="reviewer[]" id="reviewer[]" required>';
		<?php do { ?>
			html += "<option><?php echo $user_review[0]['employee_email']; ?></option>";
		<?php } while ($user_review[0] = $user_review[1]->fetch_assoc()); ?>
		html += '</select>';
		html += '<button id="removeRow-cr-reviewer" type="button" class="btn btn-danger">Remove</button>';
		html += '</div>';

		$('#newRow-cr-reviewer').append(html);
	});

	$(document).on('click', '#removeRow-cr-reviewer', function() {
		$(this).closest('#inputFormRow-cr-reviewer').remove();
	});
</script>


<script type="text/javascript">
	//PIC
	$("#addRow-pic").click(function() {
		var html = '';
		html += '<div class="p-0 mb-3" id="inputFormRow-pic">';
		html += '<input type="text" name="pic_name[]" class="form-control form-control-xl mb-3" placeholder="PIC Name" autocomplete="off" required>';
		html += '<input type="text" name="pic_position[]" class="form-control form-control-xl mb-3" placeholder="PIC Position" autocomplete="off" required>';
		html += '<button id="removeRow-pic" type="button" class="btn btn-danger">Remove</button>';
		html += '</div>';

		$('#newRow-pic').append(html);
	});

	$(document).on('click', '#removeRow-pic', function() {
		$(this).closest('#inputFormRow-pic').remove();
	});

	//CR Reviewer
	$("#addRow-cr-reviewer").click(function() {
		var html = '';
		html += '<div class="p-0 mb-3" id="inputFormRow-cr-reviewer">';
		html += '<select class="form-control mb-3" name="reviewer[]" id="reviewer[]" required>';
		<?php for ($i = 0; $i < count($user_review[0]); $i += 1) { ?>
			html += '<option value="<?php echo $user_review[0][$i]['email']; ?>"><?php echo $user_review[0][$i]['name']; ?></option>';
		<?php } ?>
		html += '</select>';
		html += '<button id="removeRow-cr-reviewer" type="button" class="btn btn-danger">Remove</button>';
		html += '</div>';

		$('#newRow-cr-reviewer').append(html);
	});

	$(document).on('click', '#removeRow-cr-reviewer', function() {
		$(this).closest('#inputFormRow-cr-reviewer').remove();
	});
</script>

<script>
	$(document).ready(function() {
		$('#cost_type').change(function() {
			if ($(this).val() == 'Non-Chargeable') {
				$('#nonchargeable_cost').show();
				$('#changereason_non').show();
			} else {
				$('#nonchargeable_cost').hide();
				$('#changereason_non').hide();
			}
		});
	});
</script>

<script>
	$(document).ready(function() {
		$('#perlu_backup').change(function() {
			if ($(this).val() == 'Ya') {
				$('#deskripsi_backup_id').show();
				$('#sn_backup_id').show();
				$('#pn_backup_id').show();
				$('#pic_backup_id').show();
				alert('Mohon untuk mengisi Affected CI sebelum save');
			} else {
				$('#deskripsi_backup_id').hide();
				$('#sn_backup_id').hide();
				$('#pn_backup_id').hide();
				$('#pic_backup_id').hide();
			}
		});
	});
</script>

<script>
	$(document).ready(function() {
		$('#nccr').change(function() {
			if ($(this).val() == 'MSI') {
				$('#changereason_non').show();
			} else {
				$('#changereason_non').hide();
			}
		});
	});
</script>

<script>
	<?php if ($_GET['act'] == 'review' || $_GET['act'] == 'complete') { ?>
		$('input[type=text]').prop('disabled', true);
		$('input[type=date]').prop('disabled', true);
		$('input[type=date]').prop('disabled', true);

		// $('textarea').prop('disabled', true);
		$('#reject_reason').prop('', true);

		$('select').prop('disabled', true);

	<?php } ?>
</script>

<script>
	<?php if ($_GET['act'] == 'complete') { ?>
		if (status != "submission_approved") {
			alert("Can't open this page, change request has to be approved first");
			window.location = window.location.pathname + "?mod=change_request";

		}
	<?php } ?>
	<?php if ($_GET['act'] == 'review') { ?>
	<?php } ?>
	if (status == "submission_rejected") {
		alert("Can't open this page, change request is rejected");
		window.location = window.location.pathname + "?mod=change_request";
	}
</script>
<script>
	$("#cr_type option[value='<?php echo $_GET['type']; ?>']").attr("selected", "selected");
	$('#cr_type').on('change', function() {
		var value = $(this).val();
		var costimpact = $('#cost_impact').val();
		var project_code = $('#project_code').val();
		var so_number = $('#so_number').val();
		var classification = $('#classification').val();
		if (value == 'IT') {
			window.location = window.location.pathname + "?mod=change_request" + "&act=<?php echo $_GET['act']; ?>" + "&type=" + value + "&costimpact=Tidak Ada&project_code=T.SSD.G.INT&so_number=";
		} else if (value == 'Sales/Presales') {
			window.location = window.location.pathname + "?mod=change_request" + "&act=<?php echo $_GET['act']; ?>" + "&type=" + value + "&costimpact=Tidak Ada&classification=Administrative Change&project_code=" + project_code;
		} else {
			window.location = window.location.pathname + "?mod=change_request" + "&act=<?php echo $_GET['act']; ?>" + "&type=" + value + "&costimpact=" + costimpact + "&project_code=" + project_code + "&so_number=" + so_number;
		}
	})
</script>
<?php if ($_GET['type'] == 'Sales/Presales') { ?>
	<script>
		$("#classification option[value='<?php echo $_GET['classification']; ?>']").attr("selected", "selected");
		$('#classification').on('change', function() {
			var value = $(this).val();
			var costimpact = $('#cost_impact').val();
			var project_code = $('#project_code').val();
			var so_number = $('#so_number').val();
			var cr_type = $('#cr_type').val();
			if (value == 'Cost Change') {
				window.location = window.location.pathname + "?mod=change_request" + "&act=<?php echo $_GET['act']; ?>" + "&type=" + cr_type + "&costimpact=Ada&classification=" + value + "&project_code=" + project_code;
			} else {
				window.location = window.location.pathname + "?mod=change_request" + "&act=<?php echo $_GET['act']; ?>" + "&type=" + cr_type + "&costimpact=Tidak Ada&classification=" + value + "&project_code=" + project_code;
			}
		})
	</script>
<?php } ?>

<script>
</script>

<script>
	$("#cost_impact option[value='<?php echo $_GET['costimpact']; ?>']").attr("selected", "selected");
	$('#cost_impact').on('change', function() {
		var value = $(this).val();
		var type = $('#cr_type').val();
		var project_code = $('#project_code').val();
		var so_number = $('#so_number').val();
		window.location = window.location.pathname + "?mod=change_request" + "&act=<?php echo $_GET['act']; ?>" + "&type=" + type + "&project_code=" + project_code + "&costimpact=" + value + "&so_number=" + so_number;
	})
</script>

<script>
	$("#project_code").on('change', function() {
		var project_code = $(this).val();
		var type = $('#cr_type').val();
		var costimpact = $('#cost_impact').val();
		var so_number = $('#so_number').val();
		var classification = $('#classification').val();
		if (type == 'Sales/Presales') {
			window.location = window.location.pathname + "?mod=change_request" + "&act=<?php echo $_GET['act']; ?>" + "&type=" + type + "&classification=" + classification + "&project_code=" + project_code;
		} else {
			window.location = window.location.pathname + "?mod=change_request" + "&act=<?php echo $_GET['act']; ?>" + "&type=" + type + "&costimpact=" + costimpact + "&project_code=" + project_code + "&so_number=" + so_number;
		}
	})
</script>

<script>
	$("#so_number option[value='<?php echo $_GET['so_number']; ?>']").attr("selected", "selected");
	$("#so_number").on('change', function() {
		var so_number = $(this).val();
		var project_code = $('#project_code').val();
		var type = $('#cr_type').val();
		var costimpact = $('#cost_impact').val();
		var classification = $('#classification').val();
		if (type == 'Sales/Presales') {
			window.location = window.location.pathname + "?mod=change_request" + "&act=<?php echo $_GET['act']; ?>" + "&type=" + type + "&classification=" + classification + "&project_code=" + project_code;
		} else {
			window.location = window.location.pathname + "?mod=change_request" + "&act=<?php echo $_GET['act']; ?>" + "&type=" + type + "&costimpact=" + costimpact + "&project_code=" + project_code + "&so_number=" + so_number;
		}
	})
</script>

<script>
	var status = $("#change_request_status").val();
	if (status == "completion_to_be_reviewed") {
		$("#reject_div").css('display', 'none');
	}

	if (status != "completion_to_be_reviewed") {
		$("#user_comment").css('display', 'none');
	}
</script>

<script>
	var projects = [];

	function autocomplete(inp, arr) {
		/*the autocomplete function takes two arguments,
		the text field element and an array of possible autocompleted values:*/
		var currentFocus;
		/*execute a function when someone writes in the text field:*/
		inp.addEventListener("input", function(e) {
			var a, b, i, val = this.value;
			/*close any already open lists of autocompleted values*/
			closeAllLists();
			if (!val) {
				return false;
			}
			currentFocus = -1;
			/*create a DIV element that will contain the items (values):*/
			a = document.createElement("DIV");
			a.setAttribute("id", this.id + "autocomplete-list");
			a.setAttribute("class", "autocomplete-items");
			/*append the DIV element as a child of the autocomplete container:*/
			this.parentNode.appendChild(a);
			/*for each item in the array...*/
			for (i = 0; i < arr.length; i++) {
				/*check if the item starts with the same letters as the text field value:*/
				if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
					/*create a DIV element for each matching element:*/
					b = document.createElement("DIV");
					/*make the matching letters bold:*/
					b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
					b.innerHTML += arr[i].substr(val.length);
					/*insert a input field that will hold the current array item's value:*/
					b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
					/*execute a function when someone clicks on the item value (DIV element):*/
					b.addEventListener("click", function(e) {
						/*insert the value for the autocomplete text field:*/
						inp.value = this.getElementsByTagName("input")[0].value;
						/*close the list of autocompleted values,
						(or any other open lists of autocompleted values:*/
						closeAllLists();
					});
					a.appendChild(b);
				}
			}
		});
		/*execute a function presses a key on the keyboard:*/
		inp.addEventListener("keydown", function(e) {
			var x = document.getElementById(this.id + "autocomplete-list");
			if (x) x = x.getElementsByTagName("div");
			if (e.keyCode == 40) {
				/*If the arrow DOWN key is pressed,
				increase the currentFocus variable:*/
				currentFocus++;
				/*and and make the current item more visible:*/
				addActive(x);
			} else if (e.keyCode == 38) { //up
				/*If the arrow UP key is pressed,
				decrease the currentFocus variable:*/
				currentFocus--;
				/*and and make the current item more visible:*/
				addActive(x);
			} else if (e.keyCode == 13) {
				/*If the ENTER key is pressed, prevent the form from being submitted,*/
				e.preventDefault();
				if (currentFocus > -1) {
					/*and simulate a click on the "active" item:*/
					if (x) x[currentFocus].click();
				}
			}
		});

		function addActive(x) {
			/*a function to classify an item as "active":*/
			if (!x) return false;
			/*start by removing the "active" class on all items:*/
			removeActive(x);
			if (currentFocus >= x.length) currentFocus = 0;
			if (currentFocus < 0) currentFocus = (x.length - 1);
			/*add class "autocomplete-active":*/
			x[currentFocus].classList.add("autocomplete-active");
		}

		function removeActive(x) {
			/*a function to remove the "active" class from all autocomplete items:*/
			for (var i = 0; i < x.length; i++) {
				x[i].classList.remove("autocomplete-active");
			}
		}

		function closeAllLists(elmnt) {
			/*close all autocomplete lists in the document,
			except the one passed as an argument:*/
			var x = document.getElementsByClassName("autocomplete-items");
			for (var i = 0; i < x.length; i++) {
				if (elmnt != x[i] && elmnt != inp) {
					x[i].parentNode.removeChild(x[i]);
				}
			}
		}
		/*execute a function when someone clicks in the document:*/
		document.addEventListener("click", function(e) {
			closeAllLists(e.target);
		});
	}

	/*An array containing all the country names in the world:*/
	<?php //for ($i = 0; $i < count($project[0]); $i += 1) { 
	?>
	//projects.push("<?php //echo $project[0][$i]['so_number']; 
						?>");
	<?php //} 
	?>

	/*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
	// autocomplete(document.getElementById("project_name"), projects);
</script>