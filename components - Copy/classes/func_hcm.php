<?php
if ((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $author = 'Syamsul Arham';
} else {

    class HCM extends Databases
    {
        const TBLNAME = "hcm";
        const tblview = "view_employees";
        const tblmst = "mst_employees";

        // public $hostname;
        // public $username;
        // public $password;
        // public $database;

        // function __construct($hostname, $username, $password, $database) {
        //   $this->conn = new mysqli($hostname, $username, $password, $database);
        //   if ($this->conn->connect_errno) {
        //       echo "Failed to connect to MySQL: (" . $this->conn->connect_errno . ") " . $this->conn->connect_error;
        //   }
        // }
        // function __construct()
        // {
        // }

        private function get_hcm($email)
        {
            $condition = "email='" . $email . "'";
            $data = $this->get_data(self::tblmst, $condition);
            return $data;
        }

        function get_leader($email, $lead = 1)
        {
            // $email : email address
            // $lead :
            //        0 - employeer
            //        1 - leader
            // $mdlname = "HCM";
            // $DB_conn = get_conn($mdlname);
            $tblname = "view_employees";
            if ($lead == 0) {
                $condition = "leader_email='$email'";
            } elseif ($lead == 1) {
                $condition = "employee_email='$email'";
            }
            $condition .= " AND (resign_date='0000-00-00' OR isnull(resign_date))";
            $leader = $this->get_data(self::tblview, $condition);
            return $leader[0]['leader_name'];
        }

        function get_leader_v2($email)
        {
            // $condition = "employee_email='$email' AND (resign_date='0000-00-00' OR resign_date IS NULL)";
            $condition = "employee_email='$email'";
            $leaders = $this->get_data(self::tblview, $condition);
            $leader = array();
            $employee = $leaders[0]['employee_name'] . "<" . $leaders[0]['employee_email'] . ">";
            $userlevel = $leaders[0]['job_level'];
            if($leaders[2]>0) {
                do {
                    array_push($leader, $leaders[0]['leader_name'] . "<" . $leaders[0]['leader_email'] . ">; ");
                } while($leaders[0]=$leaders[1]->fetch_assoc());
            }

            $condition = "leader_email='$email' AND `resign_date` IS NULL";
            $subordinats = $this->get_data(self::tblview, $condition);
            $subordinat = array();
            if($subordinats[2]>0) {
                do {
                    array_push($subordinat, $subordinats[0]['employee_name'] . "<" . $subordinats[0]['employee_email'] . "> ");
                } while($subordinats[0]=$subordinats[1]->fetch_assoc());
            } else {
                $subordinat = "None";
            }
            return array($employee, $leader, $subordinat, $userlevel);
        }

        function get_general_manager($email)
        {
            $condition = "employee_email='$email'";
            $leaders = $this->get_data(self::tblview, $condition);
            $gm0 = $leaders[0]['employee_name'] . "<" . $leaders[0]['employee_email'] . ">";
            if(trim($leaders[0]['job_title'])!="General Manager" && trim($leaders[0]['job_title'])!="Direktur")
            {
                $condition = "employee_email='" . $leaders[0]['leader_email'] . "'";
                $leaders = $this->get_data(self::tblview, $condition);
                $gm1 = $leaders[0]['employee_name'] . "<" . $leaders[0]['employee_email'] . ">";
                if(trim($leaders[0]['job_title'])!="General Manager")
                {
                    $condition = "employee_email='" . $leaders[0]['leader_email'] . "'";
                    $leaders = $this->get_data(self::tblview, $condition);
                    $gm2 = $leaders[0]['employee_name'] . "<" . $leaders[0]['employee_email'] . ">";
                    if(trim($leaders[0]['job_title'])!="General Manager" && trim($leaders[0]['job_title'])!="Direktur")
                    {
                        $condition = "employee_email='" . $leaders[0]['leader_email'] . "'";
                        $leaders = $this->get_data(self::tblview, $condition);
                        $leader = $leaders[0]['employee_name'] . "<" . $leaders[0]['employee_email'] . ">";
                    } elseif(trim($leaders[0]['job_title'])=="Direktur")
                    {
                        $leader = $gm1;
                    } else
                    {
                        $leader = $leaders[0]['employee_name'] . "<" . $leaders[0]['employee_email'] . ">";
                    }
                } elseif(trim($leaders[0]['job_title'])=="Direktur")
                {
                    $leader = $gm0;
                } else
                {
                    $leader = $leaders[0]['employee_name'] . "<" . $leaders[0]['employee_email'] . ">";
                }
            } else
            {
                $leader = $leaders[0]['employee_name'] . "<" . $leaders[0]['employee_email'] . ">";
            }
            return $leader;
        }

        function get_id($email)
        {
            $phone = $this->get_hcm($email);
            return $phone[0]['id_number'];
        }

        function get_name($email)
        {
            $phone = $this->get_hcm($email);
            return $phone[0]['employee_name'];
        }

        // function get_name($email)
        // {
        //     $condition = "employee_email='" . $email . "'";
        //     $dtname = $this->get_data(self::tblview, $condition);
        //     return $dtname[0]['employee_name'];
        // }

        function get_nik($email)
        {
            $nik = $this->get_hcm($email);
            return $nik[0]['nik'];
        }

        function get_photo($email)
        {
            $dphoto = $this->get_hcm($email);
            return $dphoto[0]['unitdrawing'];
        }

        function get_address($email)
        {
            $phone = $this->get_hcm($email);
            return $phone[0]['address'];
        }

        function get_address1($email)
        {
            $phone = $this->get_hcm($email);
            return $phone[0]['address1'];
        }

        function get_address2($email)
        {
            $phone = $this->get_hcm($email);
            return $phone[0]['address2'];
        }

        function get_city($email)
        {
            $phone = $this->get_hcm($email);
            return $phone[0]['city'];
        }

        function get_dateofbirth($email)
        {
            $birthday = $this->get_hcm($email);
            return $birthday[0]['date_of_birth'];
        }

        function get_placeofbirth($email)
        {
            $birthday = $this->get_hcm($email);
            return $birthday[0]['place_of_birth'];
        }

        function get_phonenumber($email)
        {
            $phone = $this->get_hcm($email);
            return $phone[0]['phone'];
        }

        function get_handphonenumber($email)
        {
            $phone = $this->get_hcm($email);
            return $phone[0]['handphone'];
        }

        function get_religion($email)
        {
            $phone = $this->get_hcm($email);
            return $phone[0]['religion'];
        }

        function get_gender($email)
        {
            $phone = $this->get_hcm($email);
            return $phone[0]['religion'];
        }

        function get_status($email)
        {
            $phone = $this->get_hcm($email);
            return $phone[0]['employee_status'];
        }

        function get_joindate($email)
        {
            $phone = $this->get_hcm($email);
            return $phone[0]['join_date'];
        }

        function get_resigndate($email)
        {
            $phone = $this->get_hcm($email);
            return $phone[0]['resign_date'];
        }

        function get_profile($email, $profilename="name")
        {
            $data = $this->get_hcm($email);
            return $data[0][$profilename];
        }
    }
}
