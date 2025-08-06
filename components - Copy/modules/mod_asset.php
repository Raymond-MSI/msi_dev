<script>
	$(document).ready(function() {
		// var groupColumn = 1;
		var tableAssetList = $('#view_asset_list').DataTable( {
			dom: 'Blfrtip',
			buttons: [
				{
					extend: 'excelHtml5',
					text: "<i class='fa fa-file-excel'></i>",
					title: 'Asset_list_'+<?php echo date("YmdGis"); ?>
				},
			],
			"columnDefs": [
				{
					"targets": [18],
					"visible": false
				},
			],
			"order": [
				[1, "asc"],
				[0, "asc"]
			],
		} );

		var tableAssetLoan = $('#view_asset_loan').DataTable( {
			dom: 'Blfrtip',
			buttons: [
			{
				extend: 'excelHtml5',
				text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Export to Excel'><i class='fa fa-file-excel'></i></span>",
				title: 'Asset_loan_'+<?php echo date("YmdGis"); ?>
			},
			],
			"columnDefs": [
				{
					"targets": [0,14],
					"visible": false
				},
				{
					"targets": [3],
					"className": 'dt-body-right',
					"render": DataTable.render.datetime('DD MMM YYYY'),
				},
			],
			"order": [
				[4, "desc"]
			],
		} );

		var tableAssetSU = $('#trx_asset_summary').DataTable( {
			dom: 'Blfrtip',
			buttons: [
				{
					extend: 'excelHtml5',
					text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Export to Excel'><i class='fa fa-file-excel'></i></span>",
					title: 'Asset_summary_'+<?php echo date("YmdGis"); ?>
				},
			],
			"columnDefs": [
				{
					"targets": [3,4,5,6,7,8,9,10,11,12,13,14,15,16,17],
					className: 'dt-body-center',
				},
				{
					"targets": [18],
					className: 'dt-body-right',
					render: function(data, type) {
						var number = $.fn.dataTable.render.number( ',', '.', 2, '').display(data);
						if (type === 'display') {
							let color = 'black';
							if (data > 10000) {
								color = 'green';
							}

							return '<span style="color:' + color + '">' + number + '</span>';
						}
						return number;
					}
				},
				{
					"targets": [19,20,21,22,23],
					className: 'dt-body-right',
					"render": DataTable.render.datetime('DD MMM YYYY'),
				},
				{
					"targets": [26],
					className: 'dt-body-right',
					"render": DataTable.render.datetime('DD MMM YYYY HH:MM:SS'),
				},
			],
			"order": [
				[0, "asc"],
				[2, "asc"],
				[1, "asc"],
			],
		} );

		var tableAssetPN = $('#view_asset_by_partnumber').DataTable( {
			dom: 'Blfrtip',
			buttons: [
				{
					extend: 'excelHtml5',
					text: "<span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Export to Excel'><i class='fa fa-file-excel'></i></span>",
					title: 'Asset_stock_'+<?php echo date("YmdGis"); ?>
				},
			],
			buttons: [
				{
					extend: 'excelHtml5',
					text: "<i class='fa fa-file-excel'></i>",
					title: 'Asset_stock_'+<?php echo date("YmdGis"); ?>
				},
			],
			"columnDefs": [
				{
					"targets": [6],
					className: 'dt-body-center',
					render: function(data, type) {
						var number = $.fn.dataTable.render.number( ',', '.', 0, '').display(data);
						return number;
					}
				},
			]
		} );

	})
</script>
<?php
if((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $author = 'Syamsul Arham';
} else {
	$mdlname = "ASSET";
	$userpermission = useraccess_v2($mdlname);
	if(USERPERMISSION_V2 == "201f0b64190638a4c32177be1e09e4d4") {
		if(isset($_GET['act']) && $_GET['act']=='sync_asset') {
			include("components/modules/asset/sync_asset.php");
		} else {
			$condition = "";
			$sambung = "";
			$tblnameview="view_asset_by_brand";
			$tblnameviewPN="";
			$tblnameviewSU="";
			if(isset($_GET['cat']) && $_GET['cat']=="part number") {
				$tblnameviewPN = "view_asset_by_partnumber";
			} elseif(isset($_GET['cat']) && $_GET['cat']=="summary") {
				$tblnameviewSU = "trx_asset_summary";
			} elseif(isset($_GET['cat']) && $_GET['cat']=="loan") {
				$tblnameview = "view_asset_loan";
				$condition = 'request_completed="false"';
				$sambung = " AND ";
			} else {
				$tblnameview = "view_asset_list";
			}
			?>

			<?php
			$mdlname = "ASSET";
			$DBAD = get_conn($mdlname);
			$firstRow = 0;
			$totalRows = 100;
			// $condition = "";
			// $sambung = "";
			if(isset($_GET['btn_search']) && $_GET['owner']!="0") {
				$condition .= $sambung . "asset_owner='" . $_GET['owner'] . "'";
				$sambung = " AND ";
				$totalRows = 0;
			}

			if(isset($_GET['brand']) && $_GET['brand']!="") {
				$condition .= $sambung . "brand_name='" . $_GET['brand'] . "'";
				$sambung = " AND ";
				$totalRows = 0;
			}

			if(isset($_GET['location']) && $_GET['location']!="0") {
				$condition .= $sambung . "location_nikname='" . $_GET['location'] ."'";
				$sambung = " AND ";
				$totalRows = 0;
			}

			if(isset($_GET['pn']) && $_GET['pn']!="") {
				$condition .= $sambung . "part_number LIKE '%" . $_GET['pn'] . "%'";
				$sambung = " AND ";
				$totalRows = 0;
			}

			$order = "";

			?>

			<div class="card shadow mb-4">
				<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					<h6 class="m-0 font-weight-bold text-secondary">Asset
						<?php
						if(isset($_GET['owner']) && $_GET['owner']!="0") {
							echo " " . ucwords($_GET['owner']);
						}
						if(isset($_GET['brand']) && $_GET['brand']!="") {
							echo " " . ucwords($_GET['brand']);
						}
						if(isset($_GET['cat']) && ($_GET['cat']!="0" && $_GET['cat']!="")) {
							echo " by " . ucwords($_GET['cat']);
						} else {
							echo " List";
						}
						if(isset($_GET['location']) && $_GET['location']!="0") {
							echo " & Location " . ucwords($_GET['location']);
						}
						if(isset($_GET['pn']) && $_GET['pn']!="") {
							echo " & PN " . ucwords($_GET['pn']);
						}
						?>
					</h6>
					<?php spinner(); ?>
					<div class="align-items-right">
						<button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Filter data'><i class='fa fa-filter'></i></span></button>
							<!-- <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#summaryBackup"><span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Asset by Category'><i class='fa fa-table'></i></span></button> -->
					</div>
				</div>
				<div class="card-body">
					<?php if(isset($_GET['cat']) && $_GET['cat']=="summary") { ?>
						<div class="row">
							<div class="col-sm-1 text-right">TOTAL ALL : </div>
							<div class="col-sm-2">
								<table class="table table-sm">
									<tr>
										<td>IDR</td>
										<td class="text-right">
											<?php
											$mysql = "SELECT sum(`price`*`total_asset`) AS `total_idr` FROM `sa_trx_asset_summary` WHERE `currency` = 'IDR'" . $sambung . $condition;
											$totals = $DBAD->get_sql($mysql);
											echo number_format($totals[0]['total_idr'], 2, ",", ".");
											?>
										</td>
									</tr>
									<tr>
										<td>USD</td>
										<td class="text-right">
											<?php
											$mysql = "SELECT sum(`price`*`total_asset`) AS `total_usd` FROM `sa_trx_asset_summary` WHERE `currency` = 'USD'" . $sambung . $condition;
											$totals = $DBAD->get_sql($mysql);
											echo number_format($totals[0]['total_usd'], 2, ",", ".");
											?>
										</td>
									</tr>
								</table>
							</div>

							<div class="col-sm-1 text-right">CISCO : </div>
							<div class="col-sm-2">
								<table class="table table-sm">
									<tr>
										<td>IDR</td>
										<td class="text-right">
											<?php
											$mysql = "SELECT sum(`price`*`total_asset`) AS `total_idr` FROM `sa_trx_asset_summary` WHERE `currency` = 'IDR' AND `brand_name` LIKE '%CISCO%'" . $sambung . $condition;
											$totals = $DBAD->get_sql($mysql);
											echo number_format($totals[0]['total_idr'], 2, ",", ".");
											?>
										</td>
									</tr>
									<tr>
										<td>USD</td>
										<td class="text-right">
											<?php
											$mysql = "SELECT sum(`price`*`total_asset`) AS `total_usd` FROM `sa_trx_asset_summary` WHERE `currency` = 'USD' AND `brand_name` LIKE '%CISCO%'" . $sambung . $condition;
											$totals = $DBAD->get_sql($mysql);
											echo number_format($totals[0]['total_usd'], 2, ",", ".");
											?>
										</td>
									</tr>
								</table>
							</div>

							<div class="col-sm-1 text-right">F5 : </div>
							<div class="col-sm-2">
								<table class="table table-sm">
									<tr>
										<td>IDR</td>
										<td class="text-right">
											<?php
											$mysql = "SELECT sum(`price`*`total_asset`) AS `total_idr` FROM `sa_trx_asset_summary` WHERE `currency` = 'IDR' AND `brand_name` LIKE '%F5%'" . $sambung . $condition;
											$totals = $DBAD->get_sql($mysql);
											echo number_format($totals[0]['total_idr'], 2, ",", ".");
											?>
										</td>
									</tr>
									<tr>
										<td>USD</td>
										<td class="text-right">
											<?php
											$mysql = "SELECT sum(`price`*`total_asset`) AS `total_usd` FROM `sa_trx_asset_summary` WHERE `currency` = 'USD' AND `brand_name` LIKE '%F5%'" . $sambung . $condition;
											$totals = $DBAD->get_sql($mysql);
											echo number_format($totals[0]['total_usd'], 2, ",", ".");
											?>
										</td>
									</tr>
								</table>
							</div>

							<div class="col-sm-1 text-right">HP : </div>
							<div class="col-sm-2">
								<table class="table table-sm">
									<tr>
										<td>IDR</td>
										<td class="text-right">
											<?php
											$mysql = "SELECT sum(`price`*`total_asset`) AS `total_idr` FROM `sa_trx_asset_summary` WHERE `currency` = 'IDR' AND `brand_name` LIKE '%HP%'" . $sambung . $condition;
											$totals = $DBAD->get_sql($mysql);
											echo number_format($totals[0]['total_idr'], 2, ",", ".");
											?>
										</td>
									</tr>
									<tr>
										<td>USD</td>
										<td class="text-right">
											<?php
											$mysql = "SELECT sum(`price`*`total_asset`) AS `total_usd` FROM `sa_trx_asset_summary` WHERE `currency` = 'USD' AND `brand_name` LIKE '%HP%'" . $sambung . $condition;
											$totals = $DBAD->get_sql($mysql);
											echo number_format($totals[0]['total_usd'], 2, ",", ".");
											?>
										</td>
									</tr>
								</table>
							</div>

						</div>
					<?php } ?>
					<div class="row">
						<?php
						$primarykey = "asset_owner";
						if($tblnameviewPN!="") {
							$tblnameview = $tblnameviewPN;
						} 
						if($tblnameviewSU!="") {
							$tblnameview = $tblnameviewSU;
						}
						view_table($DBAD, $tblnameview, $primarykey, $condition, $order, $firstRow, $totalRows); 
						?>
					</div>
				</div>
			</div>

			<!-- Modal -->
			<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="staticBackdropLabel">Filter Owner</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<form name="form" method="get" action="index.php">
							<div class="modal-body">
								<div class="row mb-3">
									<div class="col-lg-12">Owner:</div>
								</div>
								<div class="row mb-3">
									<div class="col-lg-12">
										<select class="form-select" name="owner" aria-label="Select owner">
											<?php
											$tblname = "mst_owner";
											$owners = $DBAD->get_data($tblname);
											$downer = $owners[0];
											$qowner = $owners[1];
											?>
											<option value='0'>Select All</option>
											<?php do { ?>
												<option value="<?php echo $downer['owner_name']; ?>" <?php if(isset($_GET['owner']) && $_GET['owner']==$downer['owner_name']) { echo 'selected'; } ?>><?php echo $downer['owner_name']; ?></option>
											<?php } while($downer=$qowner->fetch_assoc()); ?>
										</select>
									</div>
								</div>
								<div class="row mb-3">
									<div class="col-lg-12">Brand:</div>
								</div>
								<div class="row mb-3">
									<div class="col-lg-12">
										<input type="text" class="form-control" name="brand" value="<?php if(isset($_GET['brand'])) { echo $_GET['brand']; } ?>">
									</div>
								</div>
								<?php if(isset($_GET['cat']) && $_GET['cat']!="summary") { ?>
								<div class="row mb-3">
										<div class="col-lg-12">Location:</div>
								</div>
								<div class="row mb-3">
									<div class="col-lg-12">
										<select class="form-select" name="location" aria-label="Select location">
											<?php
											$mysql = "SELECT location_nikname FROM sa_mst_asset_locations GROUP BY location_nikname ORDER BY location_nikname";
											$locations = $DBAD->get_sql($mysql);
											$dlocation = $locations[0];
											$qlocation = $locations[1];
											?>
											<option value='0'>Select All</option>
											<?php do { ?>
												<option value="<?php echo $dlocation['location_nikname']; ?>" <?php if(isset($_GET['location']) && $_GET['location']==$dlocation['location_nikname']) { echo 'selected'; } ?>><?php echo $dlocation['location_nikname']; ?></option>
											<?php } while($dlocation=$qlocation->fetch_assoc()); ?>
										</select>
									</div>
								</div>
								<?php } ?>
								<?php if(!isset($_GET['cat']) || (isset($_GET['cat']) && ($_GET['cat']=="part number" || $_GET['cat']=='summary' || $_GET['cat']==""))) { ?>
									<div class="row mb-3">
										<div class="col-lg-12">Part Number:</div>
									</div>
									<div class="row mb-3">
										<div class="col-lg-12">
											<input type="text" class="form-control" name="pn" value="<?php if(isset($_GET['pn'])) { echo $_GET['pn']; } ?>">
										</div>
									</div>
								<?php } ?>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
								<input type="hidden" name="mod" value="asset">
								<input type="hidden" name="cat" value="<?php if(isset($_GET['cat'])) { echo $_GET['cat']; } ?>">
								<input type="submit" class="btn btn-primary" name="btn_search" id="btn_search" value="Search">
							</div>
						</form>
					</div>
				</div>
			</div>

		<?php
		}
	} else { 
		$ALERT->notpermission();
	} 
	// End Body
} 
?>