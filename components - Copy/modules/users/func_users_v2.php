<?php
// Version #2
$tblname = "cfg_web";
$condition = "parent=10 AND params LIKE '%module: visibility=1%'";
$order = "title";
$modules = $DB->get_data($tblname, $condition, $order);

$msgx = "<table width=100%>";
$msgx .= "<tr><th style='border-bottom: 1px solid;'>Module</th><th style='border-bottom: 1px solid;'>User Level</th></tr>";

$json = "{
    ";
$i = 0;
do {
    if($_POST[$modules[0]['config_key']]!="Disabled")
    {
        $koma = $i>0 ? ",
    " : "";
        $mod = $modules[0]['config_key'] . "_module";
        $json .= $koma . '"' . $modules[0]['config_key'] . '": { "user_level" : "' . $_POST[$modules[0]['config_key']] . '"}';
        $msgx .= "<tr><td>" . $modules[0]['title'] . "</td><td>" . $_POST[$modules[0]['config_key']] . "</td></tr>";
        $i++;
    }
} while($modules[0]=$modules[1]->fetch_assoc());
$json .= "
}";

$msgx .= "<tr><td style='border-top: 1px solid ;'></td><td style='border-top: 1px solid ;'></td></tr></table>";

$tblname = "mst_users";
if(isset($_POST['add']))
{
    $insert = sprintf("(`name`, `username`, `email`, `password`, `organization_name`, `usertype`, `block`, `registerdate`, `user_status`, `permission`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
        GetSQLValueString(addslashes($_POST['name']), "text"),
        GetSQLValueString($_POST['username'], "text"),
        GetSQLValueString($_POST['email'], "text"),
        GetSQLValueString(MD5("p@ssw0rd123!"), "text"),
        GetSQLValueString($_POST['organization_name'], "text"),
        GetSQLValueString("Users", "text"),
        GetSQLValueString('0', "int"),
        GetSQLValueString(date("Y-m-d G:i:s"), "text"),
        GetSQLValueString((isset($_POST['user_status']) ? "1" : "0"), "int"),
        GetSQLValueString($json, "text")
    );
    $res = $DB->insert_data($tblname, $insert);
    $msgy = "ditambahkan.";
} else
{
    $update = sprintf("`user_status`=%s,`permission`=%s",
        GetSQLValueString((isset($_POST['user_status']) ? 1 : 0), "int"),
        GetSQLValueString($json, "text")
    );
    $condition = "`email`='" . $_POST['email'] . "'";
    $res = $DB->update_data($tblname, $update, $condition);
    $msgy = "diperbaharui.";
}



// Version #1
$tblname = "mst_users";
$condition = "username = '" . $_POST['username'] . "'";
$users = $DB->get_data($tblname, $condition);

$tblname = "cfg_web";
$condition = "parent=10 AND params LIKE '%module: visibility=1%'";
$order = "title";
$modules = $DB->get_data($tblname, $condition, $order);

$json_module = json_decode($json, true);

$tblname = "mst_users_level_access";
$condition = sprintf("`user_id`=%s",
GetSQLValueString($users[0]['user_id'], "int"),
);
$res = $DB->delete_data($tblname, $condition);

// if(isset($_POST['add']))
// {
    // Add new user
    do {
        if(isset($json_module[$modules[0]['config_key']]['user_level']))
        {
            $user_level = $json_module[$modules[0]['config_key']]['user_level'];
            if($user_level=="Read")
            {
                $user_level = "Member";
            } elseif($user_level=="Entry")
            {
                $user_level = "Entry Data";
            } elseif($user_level=="Approval")
            {
                $user_level = "Approval";
            } elseif($user_level=="User Admin")
            {
                $user_level = "User Admin";
            } elseif($user_level=="Super Admin")
            {
                $user_level = "Super Admin";
            } else{
                $user_level = "";
            }
            
            if($user_level=="")
            {
                $user_level_id = 0;
            } else
            {
                $tblname = "mst_users_level";
                $condition = "user_level = '" . $user_level . "'";
                $levels = $DB->get_data($tblname, $condition);
                if($levels[2]>0)
                {
                    $user_level_id = $levels[0]['user_level_id'];
                } else
                {
                    $user_level_id = 0;
                }
            }

            $tblname = "mst_users_level_access";
            $mysql = sprintf("(user_id, user_level_id, module) VALUES (%s,%s,%s)",
                GetSQLValueString($users[0]['user_id'], "int"),
                GetSQLValueString($user_level_id, "int"),
                GetSQLValueString($modules[0]['id'], "int")
            );
            $res = $DB->insert_data($tblname, $mysql);
        }
    } while($modules[0]=$modules[1]->fetch_assoc());
// } else
// {
//     // Update user
//     $tblname = "mst_users_level_access";
//     $condition = "user_id = " . $users[0]['user_id'];
//     $update = "user_level_id = 0";
//     $res = $DB->update_data($tblname, $update, $condition);

//     do {
//         if($_POST[$modules[0]['config_key']]!="Disabled")
//         {
//             // echo $modules[0]['config_key'] . " - " . $json_module[$modules[0]['config_key']]['user_level'];
//             $user_level = $json_module[$modules[0]['config_key']]['user_level'];
//             if($user_level=="Read")
//             {
//                 $user_level = "Member";
//             } elseif($user_level=="Entry")
//             {
//                 $user_level = "Entry Data";
//             } elseif($user_level=="Approval")
//             {
//                 $user_level = "Approval";
//             } elseif($user_level=="User Admin")
//             {
//                 $user_level = "User Admin";
//             } elseif($user_level=="Super Admin")
//             {
//                 $user_level = "Super Admin";
//             } else{
//                 $user_level = "";
//             }
//             if($user_level=="")
//             {
//                 $user_level_id = 0;
//             } else
//             {
//                 $tblname = "mst_users_level";
//                 $condition = "user_level = '" . $user_level . "'";
//                 $levels = $DB->get_data($tblname, $condition);
//                 if($levels[2]>0)
//                 {
//                     $user_level_id = $levels[0]['user_level_id'];
//                 }
//             }
//             // echo $user_level_id . "<br/>";
            
//             $tblname = "mst_users_level_access";
//             $condition = "user_id = " . $users[0]['user_id'] . " AND module = " . $modules[0]['id'];
//             $update = "user_level_id = " . $user_level_id;
//             $res = $DB->update_data($tblname, $update, $condition);
//         }
//     } while($modules[0]=$modules[1]->fetch_assoc());

// }

// $xxx = json_encode($json, JSON_PRETTY_PRINT);
// echo "<pre>" . $json . "</pre>";

$from = $_SESSION['Microservices_UserName'] . "<" . $_SESSION['Microservices_UserEmail'] . ">";
$to = $users[0]['name'] . "<" . $users[0]['email'] . ">";
$subject = "[MSIZone] Update Your Account";
$msg = "<p>Hi " . $to . ",</p>";
$msg .= "<p>User akses Anda di MSIZone telah " . $msgy . "</p>";
$msg .= "<p>" . $msgx . "</p>";
$msg .= "<p>Best Regards,<br/>";
$msg .= $from;

// echo $msg;
$data_msg = array('to'=>$to, 'reply'=>"", 'cc'=>$from, 'bcc'=>"", 'subject'=>$subject, 'message'=>$msg);
include("components/templates/template_notif.php");

?>