
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-secondary">Configuration Item Database</h6>
        <?php spinner(); ?>
        <div class="align-items-right">
            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#filter" style="font-size:10px"><span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' title='Filter data'><i class='fa fa-filter'></i></span></button>
        </div>
    </div>
	<?php
	include("components/classes/func_databases_sqlsrv.php");
	// $hostname = "scsm-gl01";
	// $username = "sa";
	// $password = "P@ssw0rd.1";
	// $database = "CIDB";
	// $hostname = "labdb";
	// $username = "sa";
	// $password = "P@ssw0rd.1";
	// $database = "CIDB";
	// $hostname = "labdb";
	// $username = "scsmadmin";
	// $password = "sc5m@adm1n";
	// $database = "CIDB";

	// $DBCIDB = NEW SQLSRV($hostname, $username, $password, $database);

	$mdlname = "CIDB";
	$DBCIDB = get_conn_sqlsrv($mdlname);

	$condition = "";
	$sambung = "";
	$limit = 100;
	$filter = "";
	if(isset($_GET['project_code']) && $_GET['project_code']!="") 
	{
		$condition .= $sambung . "([Maintenance Project Code] LIKE '%" . $_GET['project_code'] . "%' OR [Implementation Project Code] LIKE '%" . $_GET['project_code'] . "%' OR [Project Code] LIKE '%" . $_GET['project_code'] . "%' OR [Vendor Project Code] LIKE '%" . $_GET['project_code'] . "%') ";
		$sambung = " AND ";
		$limit = 0;
		$filter .= "<tr><td>Project Code </td><td>: " . $_GET['project_code'] . "</td></tr>";
	}
	if(isset($_GET['customer_name']) && $_GET['customer_name']!="") 
	{
		$condition .= $sambung . "[Customer Name] LIKE '%" . $_GET['customer_name'] . "%'";
		$sambung = " AND ";
		$limit = 0;
		$filter .= "<tr><td>Customer Name </td><td>: " . $_GET['customer_name'] . "</td></tr>";
	}
	if(isset($_GET['part_number']) && $_GET['part_number']!="") 
	{
		$condition .= $sambung . "[Part Number] LIKE '%" . $_GET['part_number'] . "%'";
		$sambung = " AND ";
		$limit = 0;
		$filter .= "<tr><td>Part Number </td><td>: " . $_GET['part_number'] . "</td></tr>";
	}
	if(isset($_GET['serial_number']) && $_GET['serial_number']!="") 
	{
		$condition .= $sambung . "[Serial Number] LIKE '%" . $_GET['serial_number'] . "%'";
		$sambung = " AND ";
		$limit = 0;
		$filter .= "<tr><td>Serial Number </td><td>: " . $_GET['serial_number'] . "</td></tr>";
	}
	if(isset($_GET['tos'])) 
	{
		switch ($_GET['tos'])
		{
			case "warranty":
				$conditionStart = "[Warranty Start]";
				$conditionEnd = "[Warranty End]";
				$filter .= "<tr><td>Type of Service</td><td>: Warranty</td></tr>";
				break;
			case "implementation":
				$conditionStart = "[Implementation Start]";
				$conditionEnd = "[Implementation End]";
				$filter .= "<tr><td>Type of Service</td><td>: Implementation</td></tr>";
				break;
			case "maintenance":
				$conditionStart = "[Maintenance Start]";
				$conditionEnd = "[Maintenance End]";
				$filter .= "<tr><td>Type of Service</td><td>: Maintenance</td></tr>";
				break;
			case "rental":
				$conditionStart = "[Rental Start]";
				$conditionEnd = "[Rental End]";
				$filter .= "<tr><td>Type of Service</td><td>: Renatl</td></tr>";
				break;
			case "vendor":
				$conditionStart = "[Warranty Start Vendor]";
				$conditionEnd = "[Warranty End Vendor]";
				$filter .= "<tr><td>Type of Service</td><td>: Vendor</td></tr>";
				break;
			default:
				$conditionEnd = "";
		}
	}
	if(isset($_GET['end_date']) && $conditionEnd!="")
	{
		switch ($_GET['end_date'])
		{
			case "All":
				$condition .= "";
				$filter .= "<tr><td>End Date</td><td>: All Date";
				break;
			case "Today":
				$condition .= $sambung . $conditionEnd . "='" . date("Y-m-d") . "'";
				$filter .= "<tr><td>End Date</td><td>: Today (" . date("d/m/Y") . ")</td></tr>";
				break;
			case "30days":
				$condition .= $sambung . $conditionEnd . ">='" . date("Y-m-d") . "' AND " . $conditionEnd . "<='" . date("Y-m-d", strtotime("+30day")) . "'";
				$filter .= "<tr><td>End Date</td><td>: 30 days (" . date("d-M-Y") . " s/d " . date("d-M-Y", strtotime("+30day")) . ")</td></tr>";
				break;
			case "60days":
				$condition .= $sambung . $conditionEnd . ">='" . date("Y-m-d") . "' AND " . $conditionEnd . "<='" . date("Y-m-d", strtotime("+60day")) . "'";
				$filter .= "<tr><td>End Date</td><td>: 60 days (" . date("d-M-Y") . " s/d " . date("d-M-Y", strtotime("+60day")) . ")</td></tr>";
				break;
			case "90days":
				$condition .= $sambung . $conditionEnd . ">='" . date("Y-m-d") . "' AND " . $conditionEnd . "<='" . date("Y-m-d", strtotime("+90day")) . "'";
				$filter .= "<tr><td>End Date</td><td>: 90 days (" . date("d-M-Y") . " s/d " . date("d-M-Y", strtotime("+90day")) . ")</td></tr>";
				break;
			case "Actived":
				$condition .= $sambung . $conditionEnd . ">='" . date("Y-m-d") . "'";
				$filter .= "<tr><td>End Date</td><td>: Actived</td></tr>";
				break;
			case "Expired":
				$condition .= $sambung . $conditionEnd . "<='" . date("Y-m-d") . "'";
				$filter .= "<tr><td>End Date</td><td>: Expired</td></tr>";
				break;
			case "None":
				$condition .= $sambung . $conditionEnd . " IS NULL";
				$filter .= "<tr><td>End Date</td><td>: None</td></tr>";
		}
	}
	if($filter!="") {
		$filter = "<table><tr><td colspan='2'><b>Filter :</b></td></tr>" . $filter . "</table>";
	}

	// $sql = "SELECT  
	// 	[Manufacturer] AS [Product Type]
	// 	,[Serial Number] AS [Asset Name]
	// 	,NULL AS [Associated to Asset]

	// 	,[Part Number] AS [Product]
	// 	,[Serial Number] AS [Serial Number]
	// 	,[Firmware Implementation] AS [Firmware]

	// 	,[Manufacturer] AS [Product Manufacturer]
	// 	,NULL AS [Vendor]
	// 	,'FALSE' AS [Loanable]
	// 	,'Hardware' AS [Hardware / Software]
	// 	,'In Use' AS [Asset Status]
	// 	,CONVERT(varchar(32), [Warranty Start], 103) AS [Acquisition Date]
	// 	,CONVERT(varchar(32), [Warranty End], 103) AS [Expiry Date]

	// 	,[Customer code] AS [Customer Code]
	// 	,[Customer Name] AS [Assign to Department]

	// 	,[Project Code] AS [Warranty Project Code]
	// 	,[DO Project Name] AS [Warranty Project Name]
	// 	,[DO No.] AS [Warranty DO Number]
	// 	-- ,'Warranty` AS [Warranty ToS]
	// 	,[DO Notes] AS [Notes]
	// 	,[Location Category] AS [Site]
	// 	,[Location Address] AS [Location]
	// 	,CONVERT(varchar(32), [Warranty Start], 103) AS [Warranty Start]
	// 	,CONVERT(varchar(32), [Warranty End], 103) AS [Warranty End]

	// 	,[Implementation Project Code] AS [Implementation Project Code]
	// 	,[Implementation Contract No] AS [Implementation Contract Number]
	// 	,[Implementation Project Name] AS [Implementation Project Name]
	// 	,[Type of Services Implementation] AS [Implementation ToS]
	// 	,[Notes Implementation] AS [Implementation PM Name]
	// 	,CONVERT(varchar(32), [Implementation Start], 103) AS [Implementation Start]
	// 	,CONVERT(varchar(32), [Implementation End], 103) AS [Implementation End]

	// 	,[Maintenance Project Code] AS [Maintenance Project Code]
	// 	,[Maintenance Contract No] AS [Maintenance Contract Number]
	// 	,[Maintenance Project Name] AS [Maintenance Project Name]
	// 	,[Type of Services Maintenance] AS [Maintenance ToS]
	// 	,[Notes Maintenance] AS [Maintenance PM Name]
	// 	,CONVERT(varchar(32), [Maintenance Start], 103) AS [Maintenance Start]
	// 	,CONVERT(varchar(32), [Maintenance End], 103) AS [Maintenance End]

	// 	,[Vendor Project Code] AS [Vendor Project Code]
	// 	,[Contract No] AS [Vendor Contract Number]
	// 	,CONVERT(varchar(32), [Warranty Start Vendor], 103) AS [Vendor Start]
	// 	,CONVERT(varchar(32), [Warranty End Vendor], 103) AS [Vendor End]
	// 	,[Vendor Project Name] AS [Vendor Project Name]
	// 	,[Parent Instance Number] AS [Vendor Parent Instance Number]
	// 	,[Instance Number] AS [Vendor Instance Number]
	// 	,[Service Level] AS [Vendor Service Level]
	// 	,[Service Level Description] AS [Vendor Service Level Description]
	// 	,'Warranty' AS [Vendor ToS]
	// 	,CONVERT(varchar(32), [Ldos], 103) AS [LDoS]
	// FROM ReportMatrixCI ";

	$sql = "SELECT 
		-- [CI_ID]
		--   ,[CI_ID_V]
		--   ,[CI_ID_D]
		--   ,[CI_ID_RM]
		--   ,[CI_ID_R]
		--   ,[CI_ID_M]
		--   ,[CI_ID_I]
		  [Manufacturer] AS [Product Manufacturer]
		  ,[Part Number] AS [Product Part Number]
		  ,[Serial Number] AS [Product Serial Number]
		  ,[Product Category] AS [Product Category] 
		  ,[Architecture Group] AS [Product Architecture Group]
		  ,[Architecture Sub Group] AS [Product Architecture Sub Group]
		  ,[Customer code] AS [Customer Customer Code]
		  ,[Customer Name] AS [Customer Customer Name]
		  ,[Project Code] AS [Project Project Code]
		  ,[DO Project Name] AS [Project Project Name]
		  ,[Location Category] AS [Project Location Category]
		  ,[Location Address] AS [Project Location Address]
		  ,[DO No.] AS [Delivery DO No.]
		  ,[DO Notes] AS [Delivery Notes]
		  ,CONVERT(varchar(32), [Warranty Start], 103) AS [Delivery Warranty Start]
		  ,CONVERT(varchar(32), [Warranty End], 103) AS [Delivery Warranty End]
		  ,[Firmware DO] AS [Delivery Firmware]
		--   ,CONVERT(varchar(32), [LastModifiedD], 103) AS [LastModifiedD]
		  ,[Implementation Project Code]
		  ,[Implementation Contract No]
		  ,[Implementation Project Name]
		  ,[Type of Services Implementation] AS [Implementation Type of Services]
		  ,CONVERT(varchar(32), [Implementation Start], 103) AS [Implementation Implementation Start]
		  ,CONVERT(varchar(32), [Implementation End], 103) AS [Implementation Implementation End]
		  ,[Notes Implementation] AS [Implementation Notes]
		  ,[Firmware Implementation] AS [Implementation Firmware]
		--   ,CONVERT(varchar(32), [LastModifiedI], 103) AS [LastModifiedI]
		  ,[Maintenance Project Code]
		  ,[Maintenance Contract No]
		  ,[Maintenance Project Name]
		  ,[Type of Services Maintenance] AS [Maintenance Type of Services]
		  ,CONVERT(varchar(32), [Maintenance Start], 103) AS [Maintenance Maintenance Start]
		  ,CONVERT(varchar(32), [Maintenance End], 103) AS [Maintenance Maintenance End]
		  ,[Notes Maintenance] AS [Maintenance Notes]
		  ,[Firmware Maintenance] AS [Maintenance Firmware]
		--   ,CONVERT(varchar(32), [LastModifiedM], 103) AS [LastModifiedM]
		  ,[Rental Project Code]
		  ,[Rental Contract No]
		  ,[Rental Project Name]
		  ,[Type of Service Rental] AS [Rental Type of Service]
		  ,CONVERT(varchar(32), [Rental Warranty Start], 103) AS [Rental Rental Start]
		  ,CONVERT(varchar(32), [Rental Warranty End], 103) AS [Rental Rental End]
		  ,[Notes Rental] AS [Rental Notes]
		  ,[Firmware Rental] AS [Rental Firmware]
		--   ,CONVERT(varchar(32), [LastModifiedR], 103) AS [LastModifiedR]
		  ,[Rental Maintenance Code] AS [Rental-Maintenance Code]
		  ,[Rental Maintenance Contract No] AS [Rental-Maintenance Contract No]
		  ,[Rental Maintenance Project Name] AS [Rental-Maintenance Project Name]
		  ,[Type of Service Rental Maintenance] AS [Rental-Maintenance Type of Service Rental]
		  ,CONVERT(varchar(32), [Rental Maintenance Warranty Start], 103) AS [Rental-Maintenance Maintenance Start]
		  ,CONVERT(varchar(32), [Rental Maintenance Warranty End], 103) AS [Rental-Maintenance Maintenance End]
		  ,[Notes Rental Maintenance] AS [Rental-Maintenance Notes]
		  ,[Firmware Rental Maintenance] AS [Rental-Maintenance Firmware]
		--   ,CONVERT(varchar(32), [LastModifiedRM], 103) AS [LastModifiedRM]
		  ,[Vendor Project Code]
		  ,CONVERT(varchar(32), [Warranty Start Vendor], 103) AS [Vendor Warranty Start]
		  ,CONVERT(varchar(32), [Warranty End Vendor], 103) AS [Vendor Warranty End]
		  ,[Contract No] AS [Vendor Contract No]
		  ,[Vendor Project Name]
		  ,[Parent Instance Number] AS [Vendor Parent Instance Number]
		  ,[Instance Number] AS [Vendor Instance Number]
		  ,[Service Level] AS [Vendor Service Level]
		  ,[Service Level Description] AS [Vendor Service Level Description]
		  ,[Firmware Vendor] AS [Vendor Firmware]
		  ,CONVERT(varchar(32), [End of Product Sales], 103) AS [Lifecycle End of Product Sales]
		  ,CONVERT(varchar(32), [End of New Service Attachment Date:HW], 103) AS [Lifecycle End of New Service Attachment Date:HW]
		  ,CONVERT(varchar(32), [Last Date of Renew:HW], 103) AS [Lifecycle Last Date of Renew:HW]
		  ,CONVERT(varchar(32), [End of Software Maintenance Date], 103) AS [Lifecycle End of Software Maintenance Date]
		  ,CONVERT(varchar(32), [Ldos], 103) AS [Lifecycle Ldos]
		  ,[End of Life Product Bulletin] AS [Lifecycle End of Life Product Bulletin]
		  ,[Default Service List Price $] AS [Price Default Service List Price $]
		  ,[Existing Coverage Level List Price $] AS [Price Existing Coverage Level List Price $]
		  ,[PO Number] AS [ PO Number]
		--   ,CONVERT(varchar(32), [LastModifiedV], 103) AS [LastModifiedV]
	  FROM [CIDB].[dbo].[ReportMatrixCI]";

	if($condition!="") {
		$sql .= " WHERE " . $condition;
		$limit = 0;
	}
	$sql .= " ORDER BY [Part Number], [Serial Number]";
	if($limit>0) {
		$sql .= " OFFSET 0 ROWS FETCH NEXT $limit ROWS ONLY;";
	} 

	$dom0 = "
		$.extend(true, $.fn.dataTable.defaults, {
			searching: false,
			ordering: false,
		});";
	$dom = "
		dom: 'Blfrtip',
		buttons: 
		[
			{
				extend: 'excelHtml5',
				text: \"<i class='fa fa-file-excel'></i>\",
				title: 'Employee List'
			},
		],
		order: [54, 'asc']
	";
    $params = array("connection"=>$DBCIDB, "sql"=>$sql, "nameView"=>"view", "dom"=>$dom, "dom0"=>$dom0, "header"=>"splite");
	?>
	<div class="card-body">
		<div class="row mb-3">
			<div class="col-lg-12">
				<?php echo $filter; ?>
			</div>
		</div>
		<?php view_table_sqlsrv($params); ?>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="filter" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="filter" aria-hidden="true">
    <div class="modal-dialog">    
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Filter CIDB</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form name="form" method="get" action="index.php">
                <div class="modal-body">
					<div class="row">
						<div class="col-lg-6">
							<div class="row mb-3">
								<div class="col-lg-12">Project Code :</div>
							</div>
							<div class="rwo mb-3">
								<input type="text" class="form-control form-control-sm" name="project_code" id="project_code" value="<?php if(isset($_GET['project_code'])) { echo $_GET['project_code']; } ?>" placeholder="Project Code">
							</div>
							<div class="row mb-3">
								<div class="col-lg-12">Customer Name :</div>
							</div>
							<div class="row mb-3">
								<div class="col-lg-12">
									<input type="text" class="form-control form-control-sm" name="customer_name" id="customer_name" value="<?php if(isset($_GET['customer_name'])) { echo $_GET['customer_name']; } ?>" placeholder="Customer Name">
								</div>
							</div>
							<div class="row mb-3">
								<div class="col-lg-12">Part Number :</div>
							</div>
							<div class="row mb-3">
								<div class="col-lg-12">
									<input type="text" class="form-control form-control-sm" name="part_number" id="part_number" value="<?php if(isset($_GET['part_number'])) { echo $_GET['part_number']; } ?>" placeholder="Part Number">
								</div>
							</div>
							<div class="row mb-3">
								<div class="col-lg-12">Serial Number :</div>
							</div>
							<div class="row mb-3">
								<div class="col-lg-12">
									<input type="text" class="form-control form-control-sm" name="serial_number" id="serial_number" value="<?php if(isset($_GET['serial_number'])) { echo $_GET['serial_number']; } ?>" placeholder="Serial Number">
								</div>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="row mb-3">
								<div class="col-lg-12">Type of Service :</div>
							</div>
							<div class="row mb-3">
								<div class="col-lg-12">
									<select class="form-select form-select-sm" aria-label=".form-select-sm example" name="tos" id="tos">
										<option>All</option>
										<option value="warranty" <?php if(isset($_GET['tos']) && $_GET['tos']=="warranty") { echo 'selected'; } ?>>Warranty</option>
										<option value="implementation" <?php if(isset($_GET['tos']) && $_GET['tos']=="implementation") { echo 'selected'; } ?>>Implementation</option>
										<option value="maintenance" <?php if(isset($_GET['tos']) && $_GET['tos']=="maintenance") { echo 'selected'; } ?>>Maintenance</option>
										<option value="rental" <?php if(isset($_GET['tos']) && $_GET['tos']=="rental") { echo 'selected'; } ?> disabled>Rental</option>
										<option value="vendor" <?php if(isset($_GET['tos']) && $_GET['tos']=="vendor") { echo 'selected'; } ?>>Vendor</option>
									</select>
								</div>
							</div>
							<div class="row mb-3">
								<div class="col-lg-12">End Date :</div>
							</div>
							<div class="row mb-3">
								<div class="col-lg-12">
									<!-- <input type="text" class="form-control form-control-sm" name="end_date" id="end_date" value="<?php //if(isset($_GET['end_date'])) { echo $_GET['end_date']; } ?>" placeholder="End Date"> -->
									<select class="form-select form-select-sm" aria-label=".form-select-sm example" name="end_date" id="end_date">
										<option value="All" <?php if(isset($_GET['end_date']) && $_GET['end_date']=="All") { echo 'selected'; } ?>>All</option>
										<option value="Today" <?php if(isset($_GET['end_date']) && $_GET['end_date']=="Today") { echo 'selected'; } ?>>Today</option>
										<option value="30days" <?php if(isset($_GET['end_date']) && $_GET['end_date']=="30days") { echo 'selected'; } ?>>Within 30 days</option>
										<option value="60days" <?php if(isset($_GET['end_date']) && $_GET['end_date']=="60days") { echo 'selected'; } ?>>Within 60 days</option>
										<option value="90days" <?php if(isset($_GET['end_date']) && $_GET['end_date']=="90days") { echo 'selected'; } ?>>Within 90 days</option>
										<option value="Actived" <?php if(isset($_GET['end_date']) && $_GET['end_date']=="Actived") { echo 'selected'; } ?>>Actived</option>
										<option value="Expired" <?php if(isset($_GET['end_date']) && $_GET['end_date']=="Expired") { echo 'selected'; } ?>>Expired</option>
										<option value="None" <?php if(isset($_GET['end_date']) && $_GET['end_date']=="None") { echo 'selected'; } ?>>None Date</option>
									</select>
								</div>
							</div>
						</div>
					</div>

                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <input type="hidden" name="mod" value="cidb">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" name="btn_search" id="btn_search" value="Search">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
