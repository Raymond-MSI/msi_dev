<?php
//    **********************************************************
//    *                  Database OPP                          *
//    *		Version  	: 3                      *
//    *		Created at  : 4 Februari 2020                      *
//    *		Created by  : Syamsul Arham                        *
//	  *									                       *
//	  * 	Modified at : 15 Mei 2021				           *
//	  * 	Modified at : 23 Oktober 2022			           *
//	  *		Modified by : Syamsul Arham				           *
//    **********************************************************
?>
<?php
if ((isset($property)) && ($property == 1)) {
	$version = '3.2';
	$author = 'Syamsul Arham';
} else {

	$ErrorGlobal = "\r\n" . date("d-m-Y G:i:s") . "\n";
	if (isset($_SESSION['Microservices_UserName'])) {
		$ErrorGlobal .= "- User      : " . $_SESSION['Microservices_UserName'] . "\n";
	} else {
		$ErrorGlobal .= "- User      : Cronjob\n";
	}
	if (isset($_GET['mod'])) {
		define("MDLNAME", strtoupper($_GET['mod']));
	} else {
		define("MDLNAME", "MICROSERVICES");
	}
	if (isset($_GET['sub'])) {
		define("SUBMODULE", strtoupper($_GET['sub']));
	} else
	if (isset($_GET['notif'])) {
		define("SUBMODULE", strtoupper($_GET['notif']));
	} else {
		define("SUBMODULE", NULL);
	}

	$ErrorGlobal .= "- Module    : " . MDLNAME . "\n";
	$ErrorGlobal .= "- SubModule : " . SUBMODULE . "\n";
?>
	
	<?php
	if (!function_exists("GetSQLValueString")) {
		function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
		{
			if (PHP_VERSION < 6) {
				// $theValue = get_magic_quotes_gpc() ? stripslashes( $theValue ) : $theValue;
				$theValue = addslashes($theValue);
			}

			//  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

			switch ($theType) {
				case "text":
					$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
					break;
				case "longtext":
					$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
					break;
				case "long":
				case "int":
					$theValue = ($theValue != "") ? intval($theValue) : "NULL";
					break;
				case "double":
					$theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
					break;
				case "date":
					$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
					break;
				case "defined":
					$theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
					break;
			}
			return $theValue;
		}
	}

	class Databases
	{
		public $hostname;
		public $username;
		public $password;
		public $database;
		public $conn;
		public $ErrorGlobal;

		function __construct($hostname, $username, $password, $database)
		{

			/* activate reporting */
			mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

			try {
				/* if the connection fails, a mysqli_sql_exception will be thrown */
				$this->conn = new mysqli($hostname, $username, $password, $database);
			} catch (mysqli_sql_exception $e) {
				$positionString1 = strpos($e->getMessage(), "No connection could be made because the target machine actively refused it");
				$positionString2 = strpos($e->getMessage(), "MySQL server has gone away");
				if ($positionString1 !== false || $positionString2 !== false) {
					// Connection refused
					$this->alert("Connection refused. Please check your database connection.", "danger");
				} else {
					// Other error
					$this->alert($e->__toString(), "danger");
				}
			}
		}

		function create_database($dbname)
		{
			$mysql = 'create database ' . $dbname;
			mysqli_set_charset($this->conn, 'utf8');
			$res = mysqli_query($this->conn, $mysql);
			// $this->insert_log_DB( $mysql );
		}

		function show_databases($dbname = '')
		{
			if ($dbname == '') {
				$mysql = 'show databases';
			} else {
				$mysql = 'show databases like "%' . $dbname . '%"';
			}
			mysqli_set_charset($this->conn, 'utf8');
			$res = mysqli_query($this->conn, $mysql);
			$row = mysqli_fetch_assoc($res);
			$total_rows = mysqli_num_rows($res);
			return array($row, $res, $total_rows);
		}

		function drop_database($dbname)
		{
			$mysql = 'drop database ' . $dbname;
			mysqli_set_charset($this->conn, 'utf8');
			$res = mysqli_query($this->conn, $mysql);
			// $this->insert_log_DB( $mysql );
		}

		function show_create_database($dbname)
		{
			$mysql = 'show create database ' . $dbname;
			mysqli_set_charset($this->conn, 'utf8');
			$res = mysqli_query($this->conn, $mysql);
			$row = mysqli_fetch_assoc($res);
			$total_rows = mysqli_num_rows($res);
			return array($row, $res, $total_rows);
		}

		// Function ini digantikan oleh :
		// - get_res($mysql)
		// - get_rows($res)
		// - get_total($res)
		function open_db($mysql)
		{
			mysqli_set_charset($this->conn, 'utf8');

			// $res = mysqli_query( $this->conn, $mysql ) or die($this->conn->errno . "-" . "[open_db][mysqli_query][" . $mysql . "] - " . $this->conn->error . "<br/>");
			$err = "";
			// Exception error handling
			// Update 28 Mei 2025
			try {
				$res = mysqli_query($this->conn, $mysql);
				$row = mysqli_fetch_assoc($res);
				$total_rows = mysqli_num_rows($res);
				return array($row, $res, $total_rows, $err);
			} catch (mysqli_sql_exception $e) {
				// Connection error, try to reconnect
				$this->alert($this->conn->error, "danger");
			}
			// old coding
			// $res = mysqli_query( $this->conn, $mysql ) or die($err = $this->conn->errno . "-" . $this->conn->error . "<br/>");
			// $row = mysqli_fetch_assoc( $res );
			// $total_rows = mysqli_num_rows( $res );
			// return array( $row, $res, $total_rows, $err );
		}

		// Revisi: 23 Oktober 2022 00:45
		// Old Function : open_db($mysql)
		// New Fucntion :
		//                - get_res($mysql)
		//                - get_rows($res)
		//                - get_total($res)
		// Pemecahan fungsi $res, $row dan $total_rows
		function get_res($mysql)
		{
			// Exception error handling
			// Update 28 Mei 2025
			try {
				mysqli_set_charset($this->conn, 'utf8');
			} catch (mysqli_sql_exception $e) {
				// Connection error, try to reconnect
				$this->alert($this->conn->error, "danger");
			}
			$res = mysqli_query($this->conn, $mysql);
			// Old coding
			// mysqli_set_charset( $this->conn, 'utf8' );
			// if(!$res = mysqli_query( $this->conn, $mysql ))
			// {
			// 	throw new Exception($this->conn->errno . " - \"" . $mysql . "\" - " . $this->conn->error);
			// }
			return $res;
		}

		// Created at : 23 Oktober 2022 00:45
		function get_rows($res)
		{
			// version 3
			// $row = mysqli_fetch_assoc( $res );
			// version 3.1
			try {
				$row = mysqli_fetch_array($res, MYSQLI_ASSOC);
			} catch (Exception $e) {
				echo 'Caught get_sql_total error : ', $e->getMessage(), "\n";
			}
			if (!isset($e)) {
				return $row;
			}
		}

		// Created at : 23 Oktober 2022 00:45
		function get_total($res)
		{
			$total_rows = mysqli_num_rows($res);
			return $total_rows;
		}

		// Created at : 23 Oktober 2022 01:25
		function get_sql_total($mysql)
		{
			try {
				$res = $this->get_res($mysql);
			} catch (Exception $e) {
				echo 'Caught get_sql_total error : ', $e->getMessage(), "\n";
			}
			if (!isset($e)) {
				$total_rows = $this->get_total($res);
				return array($total_rows);
			}
		}

		// Created at : 23 Oktober 2022 01:25
		function get_data_total($tablename, $condition = "", $order = "", $startRows = 0, $maxRows = 0)
		{
			$mysql = 'SELECT * FROM sa_' . $tablename;
			if ($condition != '') {
				$mysql .= ' WHERE ' . $condition;
			}
			if ($order != '') {
				$mysql .= ' ORDER BY ' . $order;
			}
			if ($maxRows > 0) {
				$mysql .= ' LIMIT ' . $startRows . ', ' . $maxRows;
			}
			$data = $this->get_sql_total($mysql);
			return $data;
		}


		function save_db($mysql)
		{
			mysqli_set_charset($this->conn, 'utf8');

			$res = mysqli_query($this->conn, $mysql) or die($this->conn->errno . "-" . "[save_db][mysqli_query][" . $mysql . "] - " . $this->conn->error . "<br/>");
		}

		function close_db()
		{
			// $this->conn->close_db;
		}

		function get_databases()
		{
			$mysql = 'SHOW DATABASES';
			$data = $this->open_db($mysql, $this->conn);
			return $data;
		}

		function get_data($tablename, $condition = "", $order = "", $startRows = 0, $maxRows = 0)
		{
			$mysql = 'SELECT * FROM sa_' . $tablename;
			if ($condition != '') {
				$mysql .= ' WHERE ' . $condition;
			}
			if ($order != '') {
				$mysql .= ' ORDER BY ' . $order;
			}
			if ($maxRows > 0) {
				$mysql .= ' LIMIT ' . $startRows . ', ' . $maxRows;
			}
			// Revisi script
			// Update 23 Oktober 2022 00:45
			// Penambahan catch error
			// Old script
			// $data = $this->open_db( $mysql);
			// return $data;
			// New script
			$data = $this->get_sql($mysql);
			return $data;
		}

		function get_sql($mysql, $opt = true)
		{
			// Revisi script
			// Update 23 Oktober 2022 00:45
			// 		Penambahan catch error
			// 		Old script
			// 		$data = $this->open_db( $mysql, $this->conn );
			// 		return $data;
			// Update 22 Agustus 2023 13:13
			// 		Add parameter $opt.
			// 		$opt = true : for get data
			// 		$opt = false : for update or insert data 
			try {
				$res = $this->get_res($mysql);
			} catch (Exception $e) {
				// Old coding
				// $ErrorGlobal = date("d-M-Y G:i:s") . "\n";
				// $ErrorGlobal .= "- SubModule : " . SUBMODULE . "\n";
				// $ErrorGlobal .= "- Status    : Caught 'get_sql' error - [" . $mysql . "] " . $e->getMessage() . "\r\n\r\n";
				// echo $ErrorGlobal;
				// file_put_contents("log/DATABASE/log_MODULE_" . MDLNAME . "_" . date("Ymd") . ".txt", $ErrorGlobal, FILE_APPEND);
				// Update coding 28 Mei 2025
				$ErrorGlobal = "Caught 'get_sql' error - [" . $mysql . "] " . $e->getMessage() . "\r\n\r\n";
				$this->alert($ErrorGlobal, "danger");
			}
			if (!isset($e)) {
				if ($opt) {
					$row = $this->get_rows($res);
					$total_rows = $this->get_total($res);
					return array($row, $res, $total_rows);
				}
			}
		}

		function get_sql_v2($mysql)
		{
			try {
				$res = $this->get_res($mysql);
			} catch (Exception $e) {
				echo 'Caught get_sql error : ', $e->getMessage(), "\n";
			}
			if (!isset($e)) {
				// $row = $this->get_rows($res);
				// $total_rows = $this->get_total($res);
				$row = "";
				$total_rows = 0;
				return array($row, $res, $total_rows);
			}
		}

		function get_sqlV2($mysql)
		{
			// Revisi script
			// Update 26 Juni 2023
			// Perbaikan ketika res
			try {
				$res = $this->get_res($mysql);
				$row = $this->get_rows($res);
				$total_rows = $this->get_total($res);
				return array($row, $this->get_res($mysql), $total_rows);
			} catch (Exception $e) {
				echo 'Caught get_sql error : ' . $e->getMessage() . "\n";
			}
		}

		function update_data($tablename, $update, $condition)
		{
			$mysql = 'UPDATE `sa_' . $tablename . '`  SET ' . $update;
			if ($condition != '') {
				$mysql .= ' WHERE ' . $condition;
			}
			$data = $this->save_db($mysql, $this->conn, 1);
			// $this->insert_log_DB( $mysql );
			return $data;
		}

		function insert_data($tablename, $insert)
		{
			$mysql = 'INSERT INTO `sa_' . $tablename . '` ' . $insert;
			$data = $this->save_db($mysql, $this->conn, 1);
			// $this->insert_log_DB( $mysql );
			return $data;
		}

		function delete_data($tablename, $condition)
		{
			$mysql = 'DELETE FROM `sa_' . $tablename . '` WHERE ' . $condition;
			$data = $this->save_db($mysql, $this->conn, 1);
			// $this->insert_log_DB( $mysql );
			return $data;
		}

		function run_mysql($mysql)
		{
			$data = $this->open_db($mysql, $this->conn);
			// $this->insert_log_DB( $mysql );
			return $data;
		}

		function get_tables($database)
		{
			$mysql = 'SHOW TABLES FROM `' . $database . '`';
			$data = $this->open_db($mysql, $this->conn);
			return $data;
		}

		function create_table($tablename, $fieldsname)
		{
			$mysql = 'CREATE TABLE `sa_' . $tablename . '` (' . $fieldsname . ')';
			// $this->insert_log_DB( $mysql );
			if (!$this->conn->query($mysql)) {
				echo "Table creation failed: (" . $this->conn->errno . ") " . $this->conn->error;
			}
		}

		function drop_table($tablename)
		{
			$mysql = 'DROP TABLE IF EXISTS `sa_' . $tablename . "`";
			// $this->insert_log_DB( $mysql );
			if (!$this->conn->query($mysql)) {
				echo "Table drop failed: (" . $this->conn->errno . ") " . $this->conn->error;
			}
		}

		function get_columns($tablename)
		{
			$mysql = "SHOW COLUMNS FROM `sa_" . $tablename . "`";
			$data = $this->open_db($mysql, $this->conn);
			return $data;
		}

		function get_columns2($mysql)
		{
			// $mysql = "SHOW COLUMNS FROM `sa_" . $tablename . "`";
			$data = $this->open_db($mysql, $this->conn);
			return $data;
		}

		function show_columns($tablename)
		{
			$getFields = $this->get_columns($tablename, $this->conn);
			$dataFields = $getFields[0];
			$queryFields = $getFields[1];
			echo "<td class='show-columns text-center font-weight-bold'>No.</td>";
			do {
				echo "<td class='show-columns text-center font-weight-bold'>" . ucwords(str_replace("_", " ", $dataFields["Field"])) . "</td>";
			} while ($dataFields = $queryFields->fetch_assoc());
			echo "<td></td>";
		}

		function show_table($tablename, $id = 'id', $condition = '', $textlenght = 0, $order = "")
		{
			$getData = $this->get_data($tablename, $condition, $order, 0, 500);
			$Data = $getData[0];
			$Query = $getData[1];
			if ($getData[2] > 0) {
				$i = 1;
				do {
					echo "<tr>";
					$getFields = $this->get_columns($tablename);
					$dataFields = $getFields[0];
					$queryFields = $getFields[1];
					echo "<td>" . $i . "</td>";
					$i++;
					do {
						if (($dataFields["Type"] == "text" || substr($dataFields["Type"], 0, 7) == "varchar") && $textlenght > 0) {
							echo "<td class='show-table'>" . substr($Data[$dataFields["Field"]], 0, $textlenght);
							if (strlen($Data[$dataFields["Field"]]) > $textlenght) {
								echo "...";
							}
							echo "</td>";
						} else {

							echo "<td class='show-table'>" . $Data[$dataFields["Field"]] . "</td>";
						}
					} while ($dataFields = $queryFields->fetch_assoc());
					if (isset($_GET["mod"])) {
						echo "<td><a href='index.php?mod=" . $_GET["mod"] . "&act=view&id=" . $Data[$id] . "'><i class='fa fa-search'></i></a></td>";
					}
					echo "</tr>";
				} while ($Data = $Query->fetch_assoc());
			}
		}

		function insert_log_DB($mysql)
		{
			$insert = sprintf(
				"(`log_query`, `entry_by`) VALUES (%s, %s)",
				// Update 11 November 2022 17:23
				// an error occurs when there are quotes in the text.
				// Given a slashe to add an extra before the quotes.
				// Old coding: 
				// GetSQLValueString( str_replace('"', '\"', str_replace("'", "\'", $mysql)), "text" ),
				// New coding:
				GetSQLValueString(addslashes($mysql), "text"),
				// End update. 
				GetSQLValueString($_SESSION["Microservices_UserEmail"], "text")
			);
			$mysql = 'INSERT INTO `sa_log_database`' . ' ' . $insert;
			$data = $this->save_db($mysql, $this->conn);
			return $data;
		}

		function insert_log($username, $module, $log_category, $log_data_old = NULL, $log_data_new = NULL)
		{
			$insertData = sprintf(
				"(`log_user_name`, `log_module`, `log_data_old`, `log_data_new`, `log_category`) VALUES (%s, %s, %s, %s, %s)",
				GetSQLValueString($username, "text"),
				GetSQLValueString($module, "text"),
				GetSQLValueString(NULL, "text"),
				GetSQLValueString(NULL, "text"),
				GetSQLValueString($log_category, "text")
			);
			$this->insert_data("logs_user", $insertData);
		}

		function get_escape_string($mysql)
		{
			$esc_str = $this->conn->real_escape_string($mysql);
			return $esc_str;
		}

		function alert($msgx, $type = "danger")
		{
			// New function to alert message
			// Created at : 28 Mei 2025
			// Update 28 Mei 2025
			global $ALERT;
			$msgID = uniqid();
			$msg = date("d-m-Y H:i:s") . "\r\n";
			$msg .= "- ID : " . $msgID . "\r\n";
			$msg .= "- User Name   : " . (isset($_SESSION['Microservices_UserEmail']) ? $_SESSION['Microservices_UserEmail'] : "Not-Login") . "\r\n";
			$msg .= (isset($_GET['mod']) ? ("- Module      : " . $_GET['mod']) : "Home") . "\r\n";
			$msg .= (isset($_GET['sub']) ? ("- Sub-Module  : " . $_GET['sub']) . "\r\n" : "");
			$msg .= "- Description : " . $msgx . "\r\n\r\n";
			file_put_contents("log/DATABASE/" . date("Ymd") . "_" . MDLNAME . ".txt", $msg, FILE_APPEND);

			if (isset($ALERT)) {
				$ALERT->msgcustom($type, "Error ID : $msgID - Description : $msgx");
			} else {
				echo "<div class='alert alert-" . $type . "'>Error ID : $msgID - Description : $msgx</div>";
			}
			if ($type == "danger") {
				die;
			}
		}
	}

	function get_conn($mdlname)
	{
		global $DB, $ALERT;

		$tblname = "cfg_web";
		$condition = "config_key = 'MODULE_" . ucwords($mdlname) . "'";
		$cons = $DB->get_data($tblname, $condition);
		$dcon = $cons[0];
		if ($cons[2] > 0) {
			$json_conn = json_decode($cons[0]['config_value'], true);
			if (isset($json_conn['version']) && $json_conn['version'] == "4.2") {
				// Version 4.2
				$hostname = $json_conn['connection']['hostname'];
				$username = $json_conn['connection']['username'];
				$userpassword = $json_conn['connection']['password'];
				$database = $json_conn['connection']['database'];
				if (isset($json_conn['connection']['class']) && $json_conn['connection']['class'] != "") {
					$dbconn = new $json_conn['connection']['class']($hostname, $username, $userpassword, $database);
				} else {
					$dbconn = new Databases($hostname, $username, $userpassword, $database);
				}
			} else
			if (isset($json_conn['version']) && $json_conn['version'] == "4.1") {
				// Version 4.1
				$hostname = $json_conn['connection']['hostname'];
				$username = $json_conn['connection']['username'];
				$userpassword = $json_conn['connection']['password'];
				$database = $json_conn['connection']['database'];
				if (isset($json_conn['connection']['class']) && $json_conn['connection']['class'] != "") {
					$dbconn = new $json_conn['connection']['class']($hostname, $username, $userpassword, $database);
				} else {
					$dbconn = new Databases($hostname, $username, $userpassword, $database);
				}
			} else
			if (strpos($cons[0]['params'], 'version') == 0) {
				// Version 3
				$params = get_params($dcon['params']);
				$hostname = $params['database']['hostname'];
				$username = $params['database']['username'];
				$userpassword = $params['database']['userpassword'];
				$database = $params['database']['database_name'];

				if (isset($params['database']['class']) && $params['database']['class'] != "") {
					$dbconn = new $params['database']['class']($hostname, $username, $userpassword, $database);
				} else {
					$dbconn = new Databases($hostname, $username, $userpassword, $database);
				}
			} else {
				// Version 4
				$json_conn = json_decode($cons[0]['params'], true);
				if ($json_conn['version']['connection'] == "4") {
					$hostname = $json_conn['connection']['hostname'];
					$username = $json_conn['connection']['username'];
					$password = $json_conn['connection']['password'];
					$database = $json_conn['connection']['database'];
					if (isset($json_conn['connection']['class'])) {
						$dbconn = new $json_conn['connection']['class']($hostname, $username, $password, $database);
					} else {
						$dbconn = new Databases($hostname, $username, $password, $database);
					}
				} else {
					$msg = "Database version error!";
					$ErrorGlobal = date("d-M-Y G:i:s") . "\n";
					$ErrorGlobal .= "- SubModule : " . SUBMODULE . "\n";
					$ErrorGlobal .= "- Function  : " . $mdlname . "\n";
					$ErrorGlobal .= "- DB Version: " . $json_conn['version']['connection'] . "\n";
					$ErrorGlobal .= "- Status    : Database version error!.\r\n\r\n";
					file_put_contents("log/DATABASE/" . date("Ymd") . "_" . MDLNAME . ".txt", $ErrorGlobal, FILE_APPEND);
					$ALERT->msgcustom("danger", $msg);
				}
			}
			return $dbconn;
		} else {
			echo "Module not found.";
			$ErrorGlobal = date("d-M-Y G:i:s") . "\n";
			$ErrorGlobal .= "- SubModule : " . SUBMODULE . "\n";
			$ErrorGlobal .= "- Function  : " . $mdlname . "\n";
			$ErrorGlobal .= "- Status    : Module not found.\r\n\r\n";
			file_put_contents("log/DATABASE/" . date("Ymd") . "_" . MDLNAME . ".txt", $ErrorGlobal, FILE_APPEND);
			return array("hostname" => "", "username" => "", "password" => "", "database" => "");
		}
	}
}
	?>