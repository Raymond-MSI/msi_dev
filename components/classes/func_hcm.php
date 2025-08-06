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

        private function get_hcm($email, $name)
        {
            $condition = "email='" . $email . "'";
            $data = $this->get_data(self::tblmst, $condition);
            if($data[2]>0)
            {
                $res = $data[0][$name];
                return $res;
            }
            // return $data;
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
            // $condition .= " AND (resign_date='0000-00-00' OR isnull(resign_date))";
            $leader = $this->get_data(self::tblview, $condition);
            if($leader[2]>0)
            {
                return $leader[0]['leader_name'] . "<" . $leader[0]['leader_email'] . ">";
            } else
            {
                return NULL;
            }
        }

        function get_leader_v2($email)
        {
            $employee = "";
            $userlevel = "";
            // Get General Manager
            $general_manager = $this->get_general_manager($email);
            // Get Sub-Ordinats
            $subordinat = $this->get_subordinat($email);

            // Get Direct Leader
            $leader = array();
            $condition = "employee_email='$email'";
            $leaders = $this->get_data(self::tblview, $condition);
            if($leaders[2]>0)
            {
                // Get Employee Name
                $employee = $leaders[0]['employee_name'] . "<" . $leaders[0]['employee_email'] . ">";
                $userlevel = $leaders[0]['job_level'];
                // Get Sub-Ordinats
                if($leaders[2]>0) {
                    do {
                        array_push($leader, $leaders[0]['leader_name'] . "<" . $leaders[0]['leader_email'] . ">; ");
                    } while($leaders[0]=$leaders[1]->fetch_assoc());
                }
            }

            return array($employee, $leader, $subordinat, $userlevel, $general_manager);
        }

        function get_general_manager($email)
        {
            $condition = "employee_email='$email'";
            $leaders = $this->get_data(self::tblview, $condition);
            if($leaders[0]!="")
            {
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
            } else
            {
                $leader = "None";
            }
            return $leader;
        }

        function get_subordinat($email)
        {
            $condition = "leader_email='$email' AND `resign_date` IS NULL";
            $subordinats = $this->get_data(self::tblview, $condition);
            $subordinat = array();
            if($subordinats[2]>0) {
                do {
                    array_push($subordinat, addslashes($subordinats[0]['employee_name']) . "<" . $subordinats[0]['employee_email'] . "> ");
                } while($subordinats[0]=$subordinats[1]->fetch_assoc());
            } else {
                array_push($subordinat, "None");
            }
            return $subordinat;
        }

        function get_subordinat_condition($email)
        {

            $condition = "leader_email='$email' AND `resign_date` IS NULL";
            $subordinats = $this->get_data(self::tblview, $condition);
            $subordinat = array();
            if($subordinats[2]>0) {
                $subcon = "";
                $sambung = "";
                do {
                    // array_push($subordinat, addslashes($subordinats[0]['employee_name']) . "<" . $subordinats[0]['employee_email'] . "> ");
                    $subcon .= $sambung . ' employee_name LIKE "%' . $subordinats[0]['employee_email'] . '%"';
                    $sambung = " OR ";
                } while($subordinats[0]=$subordinats[1]->fetch_assoc());
            } else {
                array_push($subordinat, "None");
            }
            return $subordinat;

        } 

        function split_email($email)
        {
            $exp1 = explode("<", $email);
            if(count($exp1)>1)
            {
                $employee_name = $exp1[0];
                $exp2 = explode(">", $exp1[1]);
                $email = trim(($exp2[0]));
            } else
            {
                $exp = explode("@", $email);
                $employee_name = $exp[0];
                $email = $email;
            }
            $email_address = $employee_name . "<" . $email . ">";
            return array(trim($employee_name), $email, $email_address);
        }

        function get_id($email)
        {
            $res = $this->get_hcm($email, "id_number");
            return $res;
        }

        function get_name($email)
        {
            $res = $this->get_hcm($email, "employee_name");
            return $res;
        }

        function get_email($email)
        {
            $name = $this->get_hcm($email, "employee_name");
            $email = $this->get_hcm($email, "email");
            $emailname = $name . "<" . $email .">";
            return $emailname;
        }

        // function get_name($email)
        // {
        //     $condition = "employee_email='" . $email . "'";
        //     $dtname = $this->get_data(self::tblview, $condition);
        //     return $dtname[0]['employee_name'];
        // }

        function get_nik($email)
        {
            $res = $this->get_hcm($email, "nik");
            return $res;
        }

        function get_photo($email)
        {
            $res = $this->get_hcm($email, "unitdrawing");
            return $res;
        }

        function get_address($email)
        {
            $res = $this->get_hcm($email, "address");
            return $res;
        }

        function get_address1($email)
        {
            $res = $this->get_hcm($email, "address1");
            return $res;
        }

        function get_address2($email)
        {
            $res = $this->get_hcm($email, "address2");
            return $res;
        }

        function get_city($email)
        {
            $res = $this->get_hcm($email, "city");
            return $res;
        }

        function get_dateofbirth($email)
        {
            $res = $this->get_hcm($email, "date_of_birth");
            return $res;
        }

        function get_placeofbirth($email)
        {
            $res = $this->get_hcm($email, "place_of_birth");
            return $res;
        }

        function get_phonenumber($email)
        {
            $res = $this->get_hcm($email, "phone");
            return $res;
        }

        function get_handphonenumber($email)
        {
            $res = $this->get_hcm($email, "handphone");
            return $res;
        }

        function get_religion($email)
        {
            $res = $this->get_hcm($email, "religion");
            return $res;
        }

        function get_gender($email)
        {
            $res = $this->get_hcm($email, "gender");
            return $res;
        }

        function get_status($email)
        {
            $res = $this->get_hcm($email, "employee_status");
            return $res;
        }

        function get_joindate($email)
        {
            $res = $this->get_hcm($email, "join_date");
            return $res;
        }

        function get_resigndate($email)
        {
            $res = $this->get_hcm($email, "resign_date");
            return $res;
        }

        function get_profile($email)
        {
            $res = $this->get_hcm($email, "employee_name");
            return $res;
        }
    }
}
