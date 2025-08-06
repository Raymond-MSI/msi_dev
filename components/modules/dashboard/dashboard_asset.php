<?php
$mdlname = "ASSET";
$DBSB = get_conn($mdlname);
?>
<div class="row">
	<div class="col-lg-6">
		<?php menu_dashboard(); ?>
	</div>
	<div class="col-lg-6">
		<div class="col-lg-12">
			<div class="col-lg-12 text-right">
				<button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#filter">
				<i class="fa-solid fa-filter"></i>
				</button>
			</div>
		</div>
	</div>
</div>

<div class="row mb-3">
	<?php 
	$owner = "WHBU";
	if(isset($_GET['asset_owner']))
	{
		$owner = strtoupper($_GET['asset_owner']);
	}
    content("Asset Owner", $owner, "primary", "2"); 
    content("Content #2", 0, "info", "2"); 
    content("Content #3", 0, "warning", "2"); 
    content("Content #4", 0, "danger", "2"); 
    content("Content #5", 0, "success", "2"); 
    content("Content #6", 0, "secondary", "2"); 
	?>
</div>

<div class="row">
	<div class="col-lg-6">
		<div class="row mb-3">
			<?php
			$reportName ="REPORT_CISCO_LDOS";
			$data = get_coding($reportName);
			eval("?>".$data[0]['config_value']);

			$reportName ="REPORT_ASSET_CISCO_LDOS";
			$data = get_coding($reportName);
			eval("?>".$data[0]['config_value']);
		

			?>
		</div>
	</div>
	<div class="col-lg-6">
		<div class="row mb-3">
			<?php
			$reportName ="REPORT_ASSET_MOVING";
			$data = get_coding($reportName);
			eval("?>".$data[0]['config_value']);
		
			$reportName ="REPORT_ASSET_LOCATION";
			$data = get_coding($reportName);
			eval("?>".$data[0]['config_value']);
		
			?>
		</div>
	</div>
</div>

<script>
window.onload = function () {

	var chart = new CanvasJS.Chart("chartCISCOldos", {
		animationEnabled: true,
		data: [{
			type: "column",
			// indexLabel: "{label} ({y})",
			indexLabel: "{y}",
			yValueFormatString: "#,##0\"\"",
			// indexLabelPlacement: "inside",
			// indexLabelFontColor: "#fff",
			// showInLegend: true,
			// legendText: "{label}",
			indexLabelFontSize: 11,
			dataPoints: <?php echo json_encode($dataCISCOldos, JSON_NUMERIC_CHECK); ?>
		}]
	});
	chart.render();

	var chart = new CanvasJS.Chart("chartAssetMoving", {
		animationEnabled: true,
		data: [{
			type: "pie",
            startAngle: -90,
			// indexLabel: "{label} ({y})",
			indexLabel: "{y}",
			yValueFormatString: "#,##0\"\"",
			// indexLabelPlacement: "inside",
			// indexLabelFontColor: "#fff",
			showInLegend: true,
			legendText: "{label}",
			indexLabelFontSize: 11,
			dataPoints: <?php echo json_encode($AssetMoving, JSON_NUMERIC_CHECK); ?>
		}]
	});
	chart.render();

	var chart = new CanvasJS.Chart("chartAssetLocation", {
		animationEnabled: true,
		data: [{
			type: "pie",
            startAngle: -90,
			// indexLabel: "{label} ({y})",
			indexLabel: "{y}",
			yValueFormatString: "#,##0\"\"",
			// indexLabelPlacement: "inside",
			// indexLabelFontColor: "#fff",
			showInLegend: true,
			legendText: "{label}",
			indexLabelFontSize: 11,
			dataPoints: <?php echo json_encode($AssetLocation, JSON_NUMERIC_CHECK); ?>
		}]
	});
	chart.render();

	var chart = new CanvasJS.Chart("chartAssetLDOS", {
		animationEnabled: true,
		data: [{
			type: "pie",
            startAngle: -90,
			// indexLabel: "{label} ({y})",
			indexLabel: "{y}",
			yValueFormatString: "#,##0\"\"",
			// indexLabelPlacement: "inside",
			// indexLabelFontColor: "#fff",
			showInLegend: true,
			legendText: "{label}",
			indexLabelFontSize: 11,
			dataPoints: <?php echo json_encode($AssetLDOS, JSON_NUMERIC_CHECK); ?>
		}]
	});
	chart.render();

}
</script>

<!-- Modal -->
<div class="modal fade" id="filter" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form method="get">
				<div class="card">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="exampleModalLabel"><i class="fa-solid fa-filter"></i> Filter</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="card-body">
					<input type="hidden" name="mod" value="dashboard">
					<input type="hidden" name="sub" value="dashboard_asset"> 
					<div class="row mb-1">
						<label class="col-sm-4 col-form-label col-form-label-sm">Asset Owner</label>
						<div class="col-sm-8">
							<select class="form-select form-select-sm" id="asset_owner" name="asset_owner">
								<option value="backup" <?php echo (isset($_GET['asset_owner']) && $_GET['asset_owner']=="backup") ? "selected" : ""; ?>>Backup</option>
								<option value="demo" <?php echo (isset($_GET['asset_owner']) && $_GET['asset_owner']=="demo") ? "selected" : ""; ?>>Demo</option>
								<option value="it" <?php echo (isset($_GET['asset_owner']) && $_GET['asset_owner']=="it") ? "selected" : ""; ?>>IT/MIS</option>
								<option value="ga" <?php echo (isset($_GET['asset_owner']) && $_GET['asset_owner']=="ga") ? "selected" : ""; ?>>General Affair</option>
							</select>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<input type="submit" class="btn btn-primary" name="btn_search" value="Search">
				</div>
				</div>
			</form>
		</div>
	</div>
</div>
