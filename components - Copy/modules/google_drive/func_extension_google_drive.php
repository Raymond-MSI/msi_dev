<?php

class SBDrive extends Databases {
//    function get_data($tablename, $condition="", $order="", $startRows = 0, $maxRows = 0) {
//        $mysql = 'SELECT * FROM sa_' . $tablename;
//        if ($condition != '') {
//          $mysql .= ' WHERE ' . $condition;
//        }
//        if ($order != '') {
//          $mysql .= ' ORDER BY ' . $order;
//        }
//        if ($maxRows > 0) {
//          $mysql .= ' LIMIT ' . $startRows . ', ' . $maxRows;
//        } 
//        $data = $this->open_db( $mysql);
//        return $data;
//      }

    function get_data_distinct($tablename, $condition = "", $order = "", $startRows = 0, $maxRows = 0)
    {
        $mysql = "SELECT DISTINCT customer_code, customer_name FROM sa_" . $tablename;
        //'where CAST(`create_date` AS DATE) = CURDATE()';
        if ($condition != '') {
            $mysql .= ' WHERE ' . $condition;
        }
        if ($order != '') {
            $mysql .= ' ORDER BY ' . $order;
        }
        if ($maxRows > 0) {
            $mysql .= ' LIMIT ' . $startRows . ', ' . $maxRows;
        }

        echo 'lll';
        $data = $this->open_db($mysql);
        return $data;
    }


}


class Drive extends Databases{
//    function get_data($tablename, $condition="", $order="", $startRows = 0, $maxRows = 0) {
//        $mysql = 'SELECT * FROM sa_' . $tablename;
//        if ($condition != '') {
//          $mysql .= ' WHERE ' . $condition;
//        }
//        if ($order != '') {
//          $mysql .= ' ORDER BY ' . $order;
//        }
//        if ($maxRows > 0) {
//          $mysql .= ' LIMIT ' . $startRows . ', ' . $maxRows;
//        } 
//        $data = $this->open_db( $mysql);
//        return $data;
//      }

    //Untuk Google Drive Project
    function get_data_project($tablename, $tablejoin, $startRows = 0, $maxRows = 0)
    {
        $mysql = "SELECT proj.*, cust.gd_id gd_id_cust FROM sa_" . $tablename . " proj INNER JOIN sa_" . $tablejoin . " cust ON proj.`customer_code` = cust.`customer_code` WHERE `status` = 'open'";
        $data = $this->open_db($mysql);
        return $data;
    }

    function insert_data_condition($tablename, $insert, $condition)
    {
        $mysql = 'INSERT INTO `sa_' . $tablename . '` ' . $insert . ' WHERE ' . $condition;
        $data = $this->save_db($mysql, $this->conn, 1);
        $this->insert_log_DB($mysql);
        return $data;
    }
    //Batas Google Drive Project
}
