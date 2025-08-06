<?php
//    **********************************************************
//    *                  Database OPP                          *
//    *    Created at  : 12 Agustus 2022                       *
//    *    Created by  : Syamsul Arham                         *
//	  *														   *
//	  * 	 Modified at : 12 Agustus 2022	                   *
//	  *	   Modified by : Syamsul Arham						   *
//    **********************************************************
?>
<?php
if((isset($property)) && ($property == 1)) {
	$version = '1.0';
	$author = 'Syamsul Arham';
} else {
	class SQLSRV {
		public $hostname;
		public $username;
		public $password;
		public $database;
		
		function __construct($hostname, $username, $password, $database) {
            // $hostname = "scsm-gl01"; //serverName\instanceName
            $connectionInfo = array( "Database"=>$database, "UID"=>$username, "PWD"=>$password);
            $this->conn = sqlsrv_connect( $hostname, $connectionInfo);
            if( $this->conn ) {
                // echo "Connection established.<br />";
            } else {
                print_r("Connection could not be established.<br/>");
                die( print_r( sqlsrv_errors(), true));
            }
        }
		
        function open_db($sql) {
            // $err = "None";
            $res = sqlsrv_query( $this->conn, $sql );
            if( $res === false) {
                die( print_r( sqlsrv_errors(), true) );
            }
			$row = "";
            // $row = sqlsrv_fetch_array( $res, SQLSRV_FETCH_ASSOC);
            // // $numFields = sqlsrv_num_fields( $res );
            $params = array();
            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
            $stmt = sqlsrv_query( $this->conn, $sql , $params, $options );
            $row_count = sqlsrv_num_rows( $stmt );
			// return array( $row, $res, $row_count, $numFields, $err );
			return array( $row, $res, $row_count );
		}

		function get_res($sql)
		{
            $res = sqlsrv_query( $this->conn, $sql );
            if( $res === false) {
                die( print_r( sqlsrv_errors(), true) );
            }
			return $res;
		}

		function get_row($sql)
		{
			$res = $this->get_res($sql);
			$row = sqlsrv_fetch_array( $res, SQLSRV_FETCH_ASSOC);
			return $row;
		}

		function get_total_row($sql)
		{
            $params = array();
            $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
            $stmt = sqlsrv_query( $this->conn, $sql , $params, $options );
            $row_count = sqlsrv_num_rows( $stmt );
			return $row_count;
		}

		function get_data($tablename, $condition="", $order="", $startRows = 0, $maxRows = 0) {
			$sql = 'SELECT * FROM sa_' . $tablename;
			if ($condition != '') {
				$sql .= ' WHERE ' . $condition;
			}
			if ($order != '') {
				$sql .= ' ORDER BY ' . $order;
			}
			if ($maxRows > 0) {
				$sql .= ' LIMIT ' . $startRows . ', ' . $maxRows;
			} 
			$data = $this->open_db( $sql);
			return $data;
		}

		function get_sql( $sql) {
			$data = $this->open_db( $sql );
			return $data;
		}

		function get_fields($sql) {
			$fields = sqlsrv_prepare( $this->conn, $sql );
			return $fields;
		}

		function convert_date($date)
		{
			$SQLTimestamp = $date;
			$seconds = round($SQLTimestamp/1000, 0);
			return $seconds;
		}
    }
	
	function get_conn_sqlsrv($mdlname) {
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
				$dbconn = new SQLSRV($hostname,$username,$userpassword,$database);
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