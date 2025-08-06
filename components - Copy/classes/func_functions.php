<?php
if ((isset($property)) && ($property == 1)) {
	$version = '2.0';
	$author = 'Syamsul Arham';
} else {
	?>
	<?php
	
	function takeoutQueryString($takeout)
	{
		$QueryString = $_SERVER['QUERY_STRING'];
		$exp = explode("&", $QueryString);
		$i = 0;
		$string = "";
		foreach ($exp as $exp1) {
			$exp2 = explode("=", $exp1);
			if ($exp2[0] != $takeout) {
				if ($i == 1) {
					$string .= "&";
				}
				$string .= $exp1;
			}
			$i = 1;
		}
		return $string;
	}
	
	function takeout_QueryString($QueryString, $takeout)
	{
		$exp = explode("&", $QueryString);
		$i = 0;
		$string = "";
		foreach ($exp as $exp1) {
			$exp2 = explode("=", $exp1);
			if ($exp2[0] != $takeout) {
				if ($i == 1) {
					$string .= "&";
				}
				$string .= $exp1;
			}
			$i = 1;
		}
		return $string;
	}
	
	function get_QueryString($str)
	{
		$QueryString = $_SERVER['QUERY_STRING'];
		$exp = explode("&", $QueryString);
		$i = 0;
		$string = "";
		foreach ($exp as $exp1) {
			$exp2 = explode("=", $exp1);
			if ($exp2[0] == $str) {
				$string .= $exp1;
			}
		}
		return $string;
	}
	
	function pagination($row, $tRows, $mRows, $search = NULL)
	{
		if ($tRows < $mRows) {
			?>
			<hr />
			<div class="row">
			<div class="pagination">
			<div class="col-md-6">
			<?php
			$QueryString = takeoutQueryString("page");
			if (($mRows % $tRows) == 0) {
				$tPages = intval($mRows / $tRows);
			} else {
				$tPages = intval($mRows / $tRows) + 1;
			}
			if (isset($_GET["page"])) {
				$page = $_GET["page"];
				$row = ($_GET["page"] - 1) * $tRows;
			} else {
				$page = 1;
				$row = 0;
			}
			?>
			<?php 
			echo "Showing " . ($row + 1) . " to ";
			if (($row + $tRows) > $mRows) {
				echo $mRows;
			} else {
				echo $row + $tRows;
			}
			echo " of " . $mRows . " entries.";
			?>
			</div>
			<div class="col-md-6">
			<?php
			if (!isset($_GET["page"])) {
				$page = 1;
			} else {
				$page = $_GET["page"];
			}
			?>
			<nav aria-label="Page navigation example">
			<ul class="pagination float-md-right">
			<li class="page-item <?php if ($page == 1) {
				echo 'disabled';
			} ?>">
			<a class="page-link" href="http://www.excelmudah.com/index.php?<?php echo $QueryString; ?>&page=<?php if ($page > 1) {
				echo $page - 1;
			} else {
				echo '1';
			}
			if (isset($search)) {
				echo '&search=' . $search;
			} ?>" aria-label="Previous">
			<span aria-hidden="true">&laquo;</span>
			<span class="sr-only">Previous</span>
			</a>
			</li>
			
			<?php 
			for ($i = 0; $i < $tPages; $i++) 
			{
				if ($tPages < 5) { ?>
					<li class="page-item <?php if ($page == $i + 1 || (!isset($page) && $i == 0)) {
						echo 'active';
					} ?>">
					<a class="page-link" href="http://www.excelmudah.com/index.php?<?php $QueryString; ?>&page=<?php echo $i + 1;
					if (isset($search)) {
						echo '&search=' . $search;
					}  ?>">
					<?php echo $i + 1; ?>
					</a>
					</li>
					<?php 
				} else { 
					if ($i == 0 || $i == $tPages - 1 || ($i > $page - 5 && $i < $page + 3)) { 
						?>
						<li class="page-item <?php if ($page == $i + 1 || (!isset($page) && $i == 0)) {
							echo 'active';
						} ?>">
						<a class="page-link" href="http://www.excelmudah.com/index.php?<?php echo $QueryString; ?>&page=<?php echo $i + 1;
						if (isset($search)) {
							echo '&search=' . $search;
						}  ?>">
						<?php echo $i + 1; ?>
						</a>
						</li>
						<?php 
					} elseif ($i == 1 || $i == $tPages - 2) { 
						?>
						&nbsp;&nbsp;...&nbsp;&nbsp;
						<?php 
					} 
				} 
			}
			?>
			
			<li class="page-item <?php if ($page == $tPages) {
				echo 'disabled';
			} ?>">
			<a class="page-link" href="http://www.excelmudah.com/index.php?<?php echo $QueryString; ?>&page=<?php if ($page < ($tPages)) {
				echo $page + 1;
			} elseif ($page == 1) {
				echo '2';
			} else {
				echo ($tPages + 1);
			}
			if (isset($search)) {
				echo '&search=' . $search;
			}  ?>" aria-label="Next">
			<span aria-hidden="true">&raquo;</span>
			<span class="sr-only">Next</span>
			</a>
			
			</li>
			</ul>
			</nav>
			</div>
			</div>
			</div>
			<?php
		}
	}
	
	function call_Button()
	{
		?>
		<div class="clearfix"></div>
		<div class="form-group row">
		<div class="col-md-9 col-sm-9  offset-md-3">
		<?php 
		if (isset($_POST["btnNew"]) || isset($_POST["btnNew"])) { 
			?>
			<button type="submit" class="btn btn-light" name="btnInsert">Save</button>
			<?php 
		} else { 
			if (!isset($_POST["btnEdit"])) { 
				?>
				<button type="submit" class="btn btn-light" name="btnNew">New</button>
				<button type="submit" class="btn btn-light" name="btnEdit">Edit</button>
				<?php 
			} else { 
				?>
				<button type="submit" class="btn btn-light" name="btnUpdate">Save</button>
				<button type="submit" class="btn btn-light" name="btnDelete" disabled>Delete</button>
				<?php 
			} 
		} 
		?>
		<button type="submit" class="btn btn-light" name="btnCancel">Cancel</button>
		</div>
		</div>
		<?php
	}
	
	function call_Button_1()
	{
		?>
		<div class="clearfix"></div>
		<div class="form-group row">
		<div class="col-md-9 col-sm-9 offset-md-3 text-right">
		<?php 
		if (isset($_POST["btnNew"]) || (isset($_GET["act"]) && $_GET["act"] == "new")) { 
			?>
			<button type="submit" class="btn btn-light" name="btnInsert">Save</button>
			<?php 
		} else { 
			if ((!isset($_POST["btnEdit"]) || (isset($_GET["act"]) && $_GET["act"]) == "edit") && (isset($_GET["act"]) && $_GET["act"] != "view")) { 
				?>
				<button type="submit" class="btn btn-light" name="btnUpdate">Save</button>
				<?php 
			} 
		} 
		?>
		<button type="submit" class="btn btn-light" name="btnCancel"><?php if (isset($_GET["act"]) && $_GET["act"] == "view") {
			echo "Back";
		} else {
			echo "Cancel";
		} ?></button>
		</div>
		</div>
		<?php
	}
	
	function diffDays($date1, $date2)
	{
		$date3 = new DateTime($date1);
		$date4 = new DateTime($date2);
		$diffdays  = $date4->diff($date3)->format('%a');
		$msg = "";
		if ($diffdays < 7) {
			$msg1 = $diffdays;
			$msg2 = "days";
		} elseif ($diffdays < 30) {
			$msg1 = intval($diffdays / 7);
			$msg2 = "weeks";
		} elseif ($diffdays < 366) {
			$msg1 = intval($diffdays / 31);
			$msg2 = "months";
		} else {
			$msg1 = intval($diffdays / 366);
			$msg2 = "years";
		}
		return array($msg1, $msg2);
	}
	
	function getColor()
	{
		$rnd = rand(1, 6);
		switch ($rnd) 
		{
			case 1:
				$color = "aero";
				break;
			case 2:
				$color = "blue";
				break;
			case 3:
				$color = "red";
				break;
			case 4:
				$color = "yellow";
				break;
			case 5:
				$color = "red";
				break;
			case 6:
				$color = "green";
		}
		return $color;
	}
	
	function strtoval($str, $decpoint = ".")
	{
		if ($decpoint == ".") {
			$exp = explode(".", $str);
			$exp1 = explode(",", $exp[0]);
		} else {
			$exp = explode(",", $str);
			$exp1 = explode(".", $exp[0]);
		}
		if (count($exp) == 1) {
			$dec = "0";
		} else {
			$dec = $exp[1];
		}
		$str = "";
		foreach ($exp1 as $exp2) {
			$str .= $exp2;
		}
		return $str . $decpoint . $dec;
	}
	
	function filter_data()
	{
		if ($_SESSION["pmtools_UserCategory"] == "Company") {
			$condition = "`company_name`='" . $_SESSION["pmtools_UserCompany"] . "'";
		} else {
			$condition = "`customer_name`='" . $_SESSION["pmtools_UserCompany"] . "'";
		}
		if ($_SESSION["pmtools_UserCompany"] == "Mastersystem Infotama") {
			$condition = "1";
		}
		return $condition;
	}
	
	function send_email($from, $to = '', $cc = 'webmaster@syaarar.com', $subject, $message)
	{
		$headers = 'From: ' . $from . "\r\n" .
		'Reply-To: ' . $from . "\r\n" .
		'Cc: ' . $cc . "\r\n" .
		'Content-Type: text/html; charset=ISO-8859-1\r\n' .
		'X-Mailer: PHP/' . phpversion();
		
		mail($to, $subject, $message, $headers);
	}
	
	function field_caption($field)
	{
		$result = ucwords(str_replace("_", " ", $field));
		return $result;
	}
	
	/* 
	No argument required for current year.
	Otherwise, pass start year as a 4-digit value.
	*/
	function auto_copyright($startYear = null)
	{
		if (!is_numeric($startYear) || intval($startYear) >= date('Y')) {
			echo "&copy; " . date('Y'); // display current year if $startYear is same or greater than this year
		} else {
			// Display range of years. Replace date('Y') with date('y') to display range of years in YYYY-YY format.
			echo "&copy; " . intval($startYear) . "&ndash;" . date('Y');
		}
	}
	
	// Create 17 July 2021 01:45:30
	function deformat($number)
	{
		$decimalSeparator = ".";
		$thousandSeparator = ",";
		$result = strval($number);
		$parts = explode($decimalSeparator, $result);
		if (count($parts) == 1) {
			$parts[1] = "00";
		}
		$parts2 = explode($thousandSeparator, $parts[0]);
		$i = 0;
		$parts[0] = "";
		for ($i = 0; $i < count($parts2); $i++) {
			$parts[0] .= $parts2[$i];
		}
		
		$parts = $parts[0] . "." . $parts[1];
		return ($parts);
	}
	
	function get_media_folder($appname)
	{
		global $DB;
		$tblname = 'cfg_web';
		$condition = 'config_key="' . $appname . '" AND parent=8';
		$folders = $DB->get_data($tblname, $condition);
		$sFolderTarget = $folders[0]['config_value'];
		return $sFolderTarget;
	}
	
	function create_folder($sFolderTarget)
	{
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
	}
	
	function get_leader($email, $lead = 1, $order = "")
	{
		// $email : email address
		// $lead :
		//        0 - employee
		//        1 - leader
		$mdlname = "HCM";
		$DB_conn = get_conn($mdlname);
		$tblname = "view_employees";
		if ($lead == 0) {
			// $condition = "leader_email='$email' AND resign_date is null";
			$condition = "leader_email='$email'";
		} elseif ($lead == 1) {
			// $condition = "employee_email='$email' AND resign_date is null";
			$condition = "employee_email='$email'";
		}
		// $condition .= " AND (resign_date='0000-00-00' OR isnull(resign_date))";
		$leaders = $DB_conn->get_data($tblname, $condition, $order);
		return $leaders;
	}
	
	function textclean($string)
	{
		// $string = str_replace('', '-', $string); // Replaces spaces with hyphens.
		return preg_replace('/[^A-Za-z0-9]/', '', $string); // Removes special chars.
	}
	
	function reset_notif($to, $link)
	{
		$DBNOTIF = get_conn("NOTIFICATION");
		$tblname = "trx_notification";
		$condition = "notif_to LIKE '%" . $to . "%'  AND notif_link = '" . $link . "'";
		$update = "`notif_status`=0";
		$res = $DBNOTIF->update_data($tblname, $update, $condition);
	}
	
	function strip_tags_content($text)
	{
		return preg_replace('/<[^>]*>/', '', $text);
	}
} ?>