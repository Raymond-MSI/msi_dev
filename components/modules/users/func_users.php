<?php
if(isset($_POST['user_status'])) {
    $status = '1';
} else {
    $status = '0';
}

function insert_user($modules, $user_id) {
    global $DB;
    $tblname = "mst_users_level_access";
    $mysql = sprintf("(`user_id`, `user_level_id`, `module`) VALUES (%s,%s,%s)",
        GetSQLValueString($user_id, "int"),
        GetSQLValueString($_POST[$modules[0]['config_value'] . '_id'], "int"),
        GetSQLValueString($modules[0]['id'], "int")
    );
    $res = $DB->insert_data($tblname, $mysql);
}

function insert_users($status) {
    global $DB;

    $tblname = "mst_users_level";
    $userlevel = $DB->get_data($tblname);
    do {

    } while($userlevel[0]=$userlevel[1]->fetch_assoc());

    $tblname = "mst_users";
    $insert = sprintf("(`name`, `username`, `email`, `password`, `organization_name`, `usertype`, `block`, `registerdate`, `user_status`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)",
        GetSQLValueString($_POST['name'], "text"),
        GetSQLValueString($_POST['username'], "text"),
        GetSQLValueString($_POST['email'], "text"),
        GetSQLValueString(MD5("p@ssw0rd123!"), "text"),
        GetSQLValueString($_POST['organization_name'], "text"),
        GetSQLValueString("Users", "text"),
        GetSQLValueString('0', "int"),
        GetSQLValueString(date("Y-m-d G:i:s"), "text"),
        GetSQLValueString($status, "int")
    );
    $res = $DB->insert_data($tblname, $insert);
}

function update_user($modules, $status) {
    global $DB;
    $tblname = "mst_users_level_access";
    $condition = "user_id=" . $_POST['user_id'] . " AND module=" . $modules[0]['id'];
    $mysql = sprintf("`user_level_id`=%s",
        GetSQLValueString($_POST[$modules[0]['config_value'] . '_id'], "int")
    );
    $res = $DB->update_data($tblname, $mysql, $condition);

    $tblname = "mst_users_level";
    $condition = sprintf("user_level_id=%s",
        GetSQLValueString($_POST[$modules[0]['config_value'] . '_id'], "int")
    );
    $userlevel = $DB->get_data($tblname, $condition);

    if($userlevel[2]>0) {
        if($userlevel[0]['user_level']=="Super Admin") {
            $tblname = "mst_users";
            $condition = "user_id='" . $_POST['user_id'] . "'";
            $update = sprintf("`name`=%s,`block`=0, `user_status`=%s, `usertype`=%s",
                GetSQLValueString($_POST['name'], "text"),
                GetSQLValueString($status, "int"),
                GetSQLValueString($userlevel[0]['user_level'], "text")
            );
            $res = $DB->update_data($tblname, $update, $condition);
        }
    }
}

$tblname = "mst_users";
if(isset($_POST['save'])) {
    // $condition = "username='" . $_POST['username'] . "'";
    // $update = sprintf("`name`=%s,`block`=0, `user_status`=%s, `usertype`=%s",
    //     GetSQLValueString($_POST['name'], "text"),
    //     GetSQLValueString($status, "int"),
    //     GetSQLValueString()
    // );
    // $res = $DB->update_data($tblname, $update, $condition);

    $tblname = "cfg_web";
    $condition = "parent=10 AND params LIKE '%log_database: visibility=1%'";
    $modules = $DB->get_data($tblname, $condition);

    $tblname = "mst_users_level_access";
    if($modules[2]>0) {
        do {
            $condition = "user_id=" . $_POST['user_id'] . " AND module=" . $modules[0]['id'];
            $cekacces = $DB->get_data($tblname, $condition);
            if($cekacces[2]>0) {
                update_user($modules, $status);
            } else {
                insert_user($modules, $_POST['user_id']);
            }
        } while($modules[0]=$modules[1]->fetch_assoc());
    }

    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Horeee!</strong> Data has been successfully update.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';

    $subject = "[MSIZone] Your account is updated";
    $msg='<table width="100%">';
    $msg.='    <tr><td width="20%" rowspan="4"></td>';
    $msg.='    <td style="width:60%; padding:20px; border:thin solid #dadada">';
    $msg.='        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
    $msg.='    <td width="20%" rowspan="4"></tr>';
    $msg.='    <tr><td style="padding:20px; border:thin solid #dadada">';
    $msg.='        <p>Dear ' . $_POST['name'] . '</p>';
    $msg.='        <p>Your account has been updated today with the following information:</p>';
                    $tblname = 'view_user_access';
                    $condition = sprintf('`user_id` = %s AND user_level <> "Non Member" AND date(modified_date)=%s',
                        GetSQLValueString($_POST['user_id'], "int"),
                        GetSQLValueString(date("Y-m-d"), "text")
                    );
                    $mdlAccess = $DB->get_data($tblname, $condition);
                    $dmdlAccess = $mdlAccess[0];
                    $qmdlAccess = $mdlAccess[1];
                    $tmdlAccess = $mdlAccess[2];
                    if($tmdlAccess>0) {
                        $msg .= '<p><table width="100%">';
                        $msg .= '<tr><td>Module</td><td>User Level</td></tr>';
                        if($tmdlAccess>0) {
                            do {
                                $msg .= "<tr><td>" . $dmdlAccess['module_name'] . "</td><td>" . $dmdlAccess['user_level'] . "</td></tr>";
                            } while($dmdlAccess=$qmdlAccess->fetch_assoc());
                        }
                        $msg .= "</table></p>";
                    } else {
                        $msg.= "<p>No access changed.";
                    }
    $msg.='        <p>Best Regard,<br/>' . $_SESSION['Microservices_UserName'] . '</p>';
    $msg.='    </td></tr>';
    $msg.='    <tr><td style="padding:20px; border:thin solid #dadada"><table width="100%"><tr><td><a href="https://msizone.mastersystem.co.id">MSIZone</a></td><td style="text-align:right"><a href="https://msizone.mastersystem.co.id/msiguide/">MSIGuide</a></td></tr></table></td></tr>';
    $msg.='    <tr><td style="font-size:10px; padding-left:20px;border: thin solid #dadada">Dikirim secara otomatis oleh sistem MSIZone.<br/>Jangan mereply email ini.</td></tr>';
    $msg.='</table>';
    $to = $_POST['email'];

} elseif(isset($_POST['add'])) {
    // $insert = sprintf("(`name`, `username`, `email`, `password`, `organization_name`, `usertype`, `block`, `registerdate`, `user_status`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)",
    //     GetSQLValueString($_POST['name'], "text"),
    //     GetSQLValueString($_POST['username'], "text"),
    //     GetSQLValueString($_POST['email'], "text"),
    //     GetSQLValueString(MD5("p@ssw0rd123!"), "text"),
    //     GetSQLValueString($_POST['organization_name'], "text"),
    //     GetSQLValueString("Users", "text"),
    //     GetSQLValueString('0', "int"),
    //     GetSQLValueString(date("Y-m-d G:i:s"), "text"),
    //     GetSQLValueString($status, "int")
    // );
    // $res = $DB->insert_data($tblname, $insert);
    insert_users($status);

    $tblname = "mst_users";
    $condition = "";
    $order = "user_id DESC";
    $users = $DB->get_data($tblname, $condition, $order);
    $dusers = $users[0];

    $tblname = "cfg_web";
    $condition = "parent=10 AND params LIKE '%log_database: visibility=1%'";
    $modules = $DB->get_data($tblname, $condition);

    $tblname = "mst_users_level_access";
    do {
        // $insert = sprintf("(`user_id`, `user_level_id`, `module`) VALUES (%s,%s,%s)",
        //     GetSQLValueString($dusers['user_id'], "int"),
        //     GetSQLValueString($_POST[$modules[0]['config_value'] . '_id'], "int"),
        //     GetSQLValueString($modules[0]['id'], "int")
        // );
        // $res = $DB->insert_data($tblname, $insert);
        insert_user($modules, $users[0]['user_id']);
    } while($modules[0]=$modules[1]->fetch_assoc());
    
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Horeee!</strong> Data has been successfully add.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';

    $subject = "[MSIZone] Create a Service Budget account";
    $msg='<table width="100%">';
    $msg.='    <tr><td width="20%" rowspan="4"></td>';
    $msg.='    <td style="width:60%; padding:20px; border:thin solid #dadada">';
    $msg.='        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
    $msg.='    <td width="20%" rowspan="4"></tr>';
    $msg.='    <tr><td style="padding:20px; border:thin solid #dadada">';
    $msg.='        <p>Dear ' . $_POST['name'] . '</p>';
    $msg.='        <p>You have been registered to use the Service Budget app.</p>';
    $msg.='        <p>Please login at this link; https://msizone.mastersystem.co.id and don\'t forget to change your password.</p>';
    $msg.='        <table width="100%">';
    $msg.='           <tr><td>User Name </td><td> : </td><td> ' . $_POST['username'] . '</td></tr>';
    $msg.='           <tr><td>Password  </td><td> : </td><td> p@ssw0rd123!</td></tr>';
    $msg.='        </table>';
    $msg.='        <p>Best Regard,<br/>' . $_SESSION['Microservices_UserName'] . '</p>';
    $msg.='    </td></tr>';
    $msg.='    <tr><td style="padding:20px; border:thin solid #dadada"><table width="100%"><tr><td><a href="https://msizone.mastersystem.co.id">MSIZone</a></td><td style="text-align:right"><a href="https://msizone.mastersystem.co.id/msiguide/">MSIGuide</a></td></tr></table></td></tr>';
    $msg.='    <tr><td style="font-size:10px; padding-left:20px;border: thin solid #dadada">Dikirim secara otomatis oleh sistem MSIZone.<br/>Jangan mereply email ini.</td></tr>';
    $msg.='</table>';
    $to = $_POST['email'];

} elseif(isset($_GET['act']) && $_GET['act']=='change_password') {
    $condition = "`user_id`=" . $_GET['user_id'];
    $myupdate = sprintf("`user_id`=%s, `password`=%s",
        GetSQLValueString($_GET['user_id'], "int"),
        GetSQLValueString(MD5("p@ssw0rd123!"), "text")
        );
    $res = $DB->update_data($tblname, $myupdate, $condition);
    $ALERT = new Alert();
    $ALERT->change_password_success();

    $username = $DB->get_data($tblname, $condition);

    // echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
    // <strong>Horeee!</strong> Password has been successfully change.
    // <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    // </div>';

    $subject = "[MSIZone] Reset Password Successfully";
    $msg='<table width="100%">';
    $msg.='    <tr><td width="20%" rowspan="4"></td>';
    $msg.='    <td style="width:60%; padding:20px; border:thin solid #dadada">';
    $msg.='        <p><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></p>';
    $msg.='    <td width="20%" rowspan="4"></tr>';
    $msg.='    <tr><td style="padding:20px; border:thin solid #dadada">';
    $msg.='        <p>Dear ' . $username[0]['name'] . '</p>';
    $msg.='        <p>Your password has been reset to the default password.</p>';
    $msg.='        <p>Please login at this link; https://msizone.mastersystem.co.id and don\'t forget to change your password.</p>';
    $msg.='        <table width="100%">';
    $msg.='           <tr><td>User Name </td><td> : </td><td> ' . $username[0]['username'] . '</td></tr>';
    $msg.='           <tr><td>Password  </td><td> : </td><td> p@ssw0rd123!</td></tr>';
    $msg.='        </table><br/>';
    $msg.='        <p>Best Regard,<br/>' . $_SESSION['Microservices_UserName'] . '</p>';
    $msg.='    </td></tr>';
    $msg.='    <tr><td style="padding:20px; border:thin solid #dadada"><table width="100%"><tr><td><a href="https://msizone.mastersystem.co.id">MSIZone</a></td><td style="text-align:right"><a href="https://msizone.mastersystem.co.id/msiguide/">MSIGuide</a></td></tr></table></td></tr>';
    $msg.='    <tr><td style="font-size:10px; padding-left:20px;border: thin solid #dadada">Dikirim secara otomatis oleh sistem MSIZone.<br/>Jangan mereply email ini.</td></tr>';
    $msg.='</table>';
    $to = $username[0]['name'] . "<" . $username[0]['email'] . ">";
}

$from=$_SESSION['Microservices_UserEmail'];
$cc=$_SESSION['Microservices_UserEmail'];
$bcc="syamsul@mastersystem.co.id";
$reply=$from;
$headers = "From: " . $from . "\r\n" .
    "Reply-To: " . $reply . "\r\n" .
    "Cc: " . $cc . "\r\n" .
    "MIME-Version: 1.0" . "\r\n" .
    "Content-Type: text/html; charset=UTF-8" . "\r\n" .
    "X-Mailer: PHP/" . phpversion();
    
$ALERT=new Alert();
if(!mail($to, $subject, $msg, $headers))
{
    echo $ALERT->email_not_send();
} else
{
    echo $ALERT->email_send();
}

?>
