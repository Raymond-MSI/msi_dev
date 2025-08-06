<?php
//    **********************************************************
//    *                  Database OPP                          *
//    *    Created at  : 4 Februari 2020                       *
//    *    Created by  : Syamsul Arham                         *
//	  *														                             *
//	  * 	 Modified at : 15 Mei 2021							             *
//	  *	   Modified by : Syamsul Arham						             *
//    **********************************************************
?>
<?php
if((isset($property)) && ($property == 1)) {
	$version = '3.0';
	$author = 'Syamsul Arham';
} else {
	?>
	
	<?php
	if ( !function_exists( "GetSQLValueString" ) ) {
		function GetSQLValueString( $theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "" ) {
			if ( PHP_VERSION < 6 ) {
				$theValue = get_magic_quotes_gpc() ? stripslashes( $theValue ) : $theValue;
			}
			
			//  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);
			
			switch ( $theType ) {
				case "text":
					$theValue = ( $theValue != "" ) ? "'" . $theValue . "'": "NULL";
					break;
				case "longtext":
					$theValue = ( $theValue != "" ) ? "'" . $theValue . "'": "NULL";
					break;
				case "long":
				case "int":
					$theValue = ( $theValue != "" ) ? intval( $theValue ) : "NULL";
					break;
				case "double":
					$theValue = ( $theValue != "" ) ? doubleval( $theValue ) : "NULL";
					break;
				case "date":
					$theValue = ( $theValue != "" ) ? "'" . $theValue . "'": "NULL";
					break;
				case "defined":
					$theValue = ( $theValue != "" ) ? $theDefinedValue : $theNotDefinedValue;
					break;
			}
			return $theValue;
		}
	}
			
	class Databases {
		public $hostname;
		public $username;
		public $password;
		public $database;
		
		function __construct($hostname, $username, $password, $database) {
			$this->conn = new mysqli($hostname, $username, $password, $database);
			if ($this->conn->connect_errno) {
				echo "Failed to connect to MySQL: (" . $this->conn->connect_errno . ") " . $this->conn->connect_error;
			}
		}
		
		function create_database($dbname) {
			$mysql = 'create database ' .$dbname;
			mysqli_set_charset( $this->conn, 'utf8' );
			$res = mysqli_query( $this->conn, $mysql );
			$this->insert_log_DB( $mysql );
		}
		
		function show_databases($dbname = '') {
			if($dbname=='') {
				$mysql = 'show databases';
			} else {
				$mysql = 'show databases like "%' . $dbname . '%"';
			}
			mysqli_set_charset( $this->conn, 'utf8' );
			$res = mysqli_query( $this->conn, $mysql );
			$row = mysqli_fetch_assoc($res);
			$total_rows = mysqli_num_rows($res);
			return array($row, $res, $total_rows);
		}
		
		function drop_database($dbname) {
			$mysql = 'drop database ' .$dbname;
			mysqli_set_charset( $this->conn, 'utf8' );
			$res = mysqli_query( $this->conn, $mysql );
			$this->insert_log_DB( $mysql );
		}
		
		function show_create_database($dbname) {
			$mysql = 'show create database ' .$dbname;
			mysqli_set_charset( $this->conn, 'utf8' );
			$res = mysqli_query( $this->conn, $mysql );
			$row = mysqli_fetch_assoc( $res );
			$total_rows = mysqli_num_rows( $res ); 
			return array( $row, $res, $total_rows );
		}
		
		function open_db($mysql) {
			mysqli_set_charset( $this->conn, 'utf8' );
			
			// $res = mysqli_query( $this->conn, $mysql ) or die($this->conn->errno . "-" . "[open_db][mysqli_query][" . $mysql . "] - " . $this->conn->error . "<br/>");
			$err = "";
			$res = mysqli_query( $this->conn, $mysql ) or die($err = $this->conn->errno . "-" . $this->conn->error . "<br/>");
			$row = mysqli_fetch_assoc( $res );
			$total_rows = mysqli_num_rows( $res ); 
			return array( $row, $res, $total_rows, $err );
		}
		
		function save_db($mysql) {
			mysqli_set_charset( $this->conn, 'utf8' );
			
			$res = mysqli_query( $this->conn, $mysql ) or die($this->conn->errno . "-" . "[save_db][mysqli_query][" . $mysql . "] - " . $this->conn->error . "<br/>");
		}
		
		function close_db( ) {
			$this->conn->close_db;
		}
		
		function get_databases() {
			$mysql = 'SHOW DATABASES';
			$data = $this->open_db($mysql, $this->conn);
			return $data;
		}
		
		function get_data($tablename, $condition="", $order="", $startRows = 0, $maxRows = 0) {
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
			$data = $this->open_db( $mysql);
			return $data;
		}
		
		function get_sql( $mysql) {
			$data = $this->open_db( $mysql, $this->conn );
			return $data;
		}
		
		function update_data( $tablename, $update, $condition) {
			$mysql = 'UPDATE `sa_' . $tablename . '`  SET ' . $update;
			if ( $condition != '' ) {
				$mysql .= ' WHERE ' . $condition;
			}
			$data = $this->save_db( $mysql, $this->conn, 1 );
			$this->insert_log_DB( $mysql );
			return $data;
		}
		
		function insert_data( $tablename, $insert) {
			$mysql = 'INSERT INTO `sa_' . $tablename . '` ' . $insert; 
			$data = $this->save_db( $mysql, $this->conn, 1 );
			$this->insert_log_DB( $mysql );
			return $data;
		}
		
		function delete_data( $tablename, $condition) {
			$mysql = 'DELETE FROM `sa_' . $tablename . '` WHERE ' . $condition;
			$data = $this->save_db( $mysql, $this->conn, 1 );
			$this->insert_log_DB( $mysql );
			return $data;
		}
		
		function run_mysql($mysql) {
			$data = $this->open_db($mysql, $this->conn);
			$this->insert_log_DB( $mysql );
			return $data;
		}
		
		function get_tables($database) {
			$mysql = 'SHOW TABLES FROM `' . $database . '`';
			$data = $this->open_db($mysql, $this->conn);
			return $data;
		}
		
		function create_table( $tablename, $fieldsname) {
			$mysql = 'CREATE TABLE `sa_' . $tablename . '` (' . $fieldsname . ')';
			$this->insert_log_DB( $mysql );
			if ( !$this->conn->query( $mysql ) ) {
				echo "Table creation failed: (" . $this->conn->errno . ") " . $this->conn->error;
			}
		}
		
		function drop_table( $tablename) {
			$mysql = 'DROP TABLE IF EXISTS `sa_' . $tablename . "`";
			$this->insert_log_DB( $mysql );
			if ( !$this->conn->query( $mysql ) ) {
				echo "Table drop failed: (" . $this->conn->errno . ") " . $this->conn->error;
			}
		}
		
		function get_columns( $tablename) {
			$mysql = "SHOW COLUMNS FROM `sa_" . $tablename . "`";
			$data = $this->open_db( $mysql, $this->conn );
			return $data;
		}
		
		function get_columns2( $mysql) {
			$mysql = "SHOW COLUMNS FROM `sa_" . $tablename . "`";
			$data = $this->open_db( $mysql, $this->conn );
			return $data;
		}
		
		function show_columns( $tablename) {
			$getFields = get_columns( $tablename, $this->conn );
			$dataFields = $getFields[ 0 ];
			$queryFields = $getFields[ 1 ];
			echo "<td class='show-columns text-center font-weight-bold'>No.</td>";
			do {
				echo "<td class='show-columns text-center font-weight-bold'>" . ucwords( str_replace( "_", " ", $dataFields[ "Field" ] ) ) . "</td>";
			} while ( $dataFields = $queryFields->fetch_assoc() );
			echo "<td></td>";
		}
		
		function show_table( $tablename, $id = 'id', $condition = '', $textlenght = 0, $order = "" ) {
			$getData = get_data( $tablename, $condition, $order, 0, 500 );
			$Data = $getData[ 0 ];
			$Query = $getData[ 1 ];
			if($getData[2]>0) {
				$i = 1;
				do {
					echo "<tr>";
					$getFields = get_columns( $tablename );
					$dataFields = $getFields[ 0 ];
					$queryFields = $getFields[ 1 ];
					echo "<td>" . $i . "</td>";
					$i++;
					do {
						if ( ( $dataFields[ "Type" ] == "text" || substr( $dataFields[ "Type" ], 0, 7 ) == "varchar" ) && $textlenght > 0 ) {
							echo "<td class='show-table'>" . substr( $Data[ $dataFields[ "Field" ] ], 0, $textlenght );
							if ( strlen( $Data[ $dataFields[ "Field" ] ] ) > $textlenght ) {
								echo "...";
							}
							echo "</td>";
						} else {
							
							echo "<td class='show-table'>" . $Data[ $dataFields[ "Field" ] ] . "</td>";
						}
					} while ( $dataFields = $queryFields->fetch_assoc() );
					if ( isset( $_GET[ "mod" ] ) ) {
						echo "<td><a href='index.php?mod=" . $_GET[ "mod" ] . "&act=view&id=" . $Data[ $id ] . "'><i class='fa fa-search'></i></a></td>";
					}
					echo "</tr>";
				} while ( $Data = $Query->fetch_assoc() );
			}
		}
		
		function insert_log_DB( $mysql) {
			$insert = sprintf( "(`log_query`, `entry_by`) VALUES (%s, %s)",
			//GetSQLValueString( htmlentities( $mysql, ENT_QUOTES ), "text" ),
			GetSQLValueString( str_replace('"', '\"', str_replace("'", "\'", $mysql)), "text" ),
			GetSQLValueString( $_SESSION[ "Microservices_UserEmail" ], "text" ) );
			$mysql = 'INSERT INTO `sa_log_database`' . ' ' . $insert; 
			$data = $this->save_db( $mysql, $this->conn);
			return $data;
		}
		
		function insert_log( $username, $module, $log_category, $log_data_old = NULL, $log_data_new = NULL ) {
			$insertData = sprintf( "(`log_user_name`, `log_module`, `log_data_old`, `log_data_new`, `log_category`) VALUES (%s, %s, %s, %s, %s)",
			GetSQLValueString( $username, "text" ),
			GetSQLValueString( $module, "text" ),
			GetSQLValueString( NULL, "text" ),
			GetSQLValueString( NULL, "text" ),
			GetSQLValueString( $log_category, "text" )
			);
			insert_data( "logs_user", $insertData );
		}
		
		function get_escape_string($mysql) {
			$esc_str = $this->conn->real_escape_string( $mysql );
			return $esc_str;
		}
		
	}
		
	function get_conn($mdlname) {
		global $DB;
		$tblname = "cfg_web";
		$condition = "config_key = 'MODULE_" . ucwords($mdlname) . "'";
		$cons = $DB->get_data($tblname,$condition);
		$dcon = $cons[0];
		if($cons[2]>0) {
			$params = get_params($dcon['params']);
			$hostname = $params['database']['hostname'];
			$username = $params['database']['username'];
			$userpassword = $params['database']['userpassword'];
			$database = $params['database']['database_name'];
			
			if(!isset($params['database']['class']))
			{
				$dbconn = new Databases($hostname,$username,$userpassword,$database);
			} else {
				$dbconn = new $params['database']['class']($hostname,$username,$userpassword,$database);
			}
			return $dbconn;
		} else {
			echo "Module not found.";
			return array("hostname"=>"","username"=>"","password"=>"","database"=>"");
		}
		
	}
	
} 
?>