<?php
class Database
{
    private $host;
    private $dbname;
    private $user;
    private $password;

    public function __construct(string $host, string $dbname, string $user, string $password)
    {
        $this->host = $host;
        $this->dbname = $dbname;
        $this->user = $user;
        $this->password = $password;
    }

    public function getConnection(): PDO
    {
        $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset-utf8";
        
        return new PDO($dsn, $this->user, $this->password, [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_STRINGIFY_FETCHES => false
        ]);
    }

    public function GetSQLValueString( $theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "" ) {
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

	public function getFilter($postFields)
	{
		$condition = "";
        if(isset($postFields['conditions']))
        {
            $condition = " WHERE " . $postFields['conditions'];
			$sambung = " AND ";
        }

		$order = null;
        if(isset($postFields['order']))
        {
            $order = " ORDER BY " . $postFields['order'];
        }

        $index = 0;
        if(isset($postFields['index']))
        {
            $index = $postFields['index'];
        }
		
        $length = 100;
        if(isset($postFields['length']))
        {
            $length = $postFields['length']<=1000 ? $postFields['length'] : 1000;
        }
		return join(" ", array($condition, $order, "LIMIT", $index, "," , $length));
	}

}