<?php

function check_double($id)
{
    global $DBHCM, $tblname;
    $condition = "";
    if($id>0)
    {
        $condition = "`edo_id`!=" . $id . " AND ";
    }
    $condition .= "`employee_name`='" . addslashes($_POST['employee_name']) . "<" . $_SESSION['Microservices_UserEmail'] . ">' AND DATE_FORMAT(`start_date`, '%Y-%m-%d')='" . date("Y-m-d", strtotime($_POST['start_date'])) . "'";
    $getData = $DBHCM->get_data($tblname, $condition);
    if($getData[2]>0)
    {
        return TRUE;
    } 
    else
    {
        return FALSE;
    }
}

function check_double2($id)
{
    global $DBHCM, $tblname;
    $condition = "";
    if($id>0)
    {
        $condition = "`edo_id`!=" . $id . " AND ";
    }
    $condition .= "`employee_name`='" . addslashes($_POST['employee_name1']) . "' AND DATE_FORMAT(`actual_start`, '%Y-%m-%d')='" . date("Y-m-d", strtotime($_POST['actual_start'])) . "'";
    $getData = $DBHCM->get_data($tblname, $condition);
    if($getData[2]>0)
    {
        return TRUE;
    } 
    else
    {
        return FALSE;
    }
}

function get_flag_approval($edo_id)
{
    global $DBHCM, $tblname;
    $mysql = sprintf(
        "SELECT `flag_approval`, `overtime_approval_by`, `edo_approval_by`
        FROM `sa_trx_edo_request`
        WHERE `edo_id` = %s",
        GetSQLValueString($edo_id, "int")
    );
    $rsID = $DBHCM->get_sql($mysql);
    if($rsID[2]>0)
    {
        $flag = $rsID[0]['flag_approval'];
        $otApproval = $rsID[0]['overtime_approval_by'];
        $edoApproval = $rsID[0]['edo_approval_by'];
        $status = "Completed";
    } else
    {
        $flag = "NULL";
        $otApproval = "NULL";
        $edoApproval = "NULL";
        $status = "Data not found";
    }
    return array($flag, $otApproval, $edoApproval, $status);
}

$mdlname = "HCM";
$DBHCM = get_conn($mdlname);
$names = $DBHCM->get_leader_v2($_SESSION['Microservices_UserEmail']);
$to = "";
$msg1 = "";
$status = "";
$performed_by = addslashes($_SESSION['Microservices_UserName']) . "<" . $_SESSION['Microservices_UserEmail'] . ">";
$checkup = FALSE;
$MyFullName = $_SESSION['Microservices_UserName'] . "<" . $_SESSION['Microservices_UserEmail'] . ">";
$MyName = $_SESSION['Microservices_UserName'];
$MyEmail = $_SESSION['Microservices_UserEmail'];

if(isset($_POST['start_date']))
{
    $duration = ((strtotime($_POST['end_date']) - strtotime($_POST['start_date']))/3600);
    $duration = str_replace(",", ".", $duration);
}

if(isset($_POST['add'])) {
    // Submite EDO
    $checkup = check_double(0);
    if($checkup==FALSE)
    {
        $insert = sprintf("(`employee_name`,`jabatan`,`division`,`start_date`,`end_date`,`duration`,`manager`,`leader`,`reason`,`status`,`entry_by`,`entry_date`,`performed_by`,`category`,`project_code`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
            GetSQLValueString(addslashes($MyFullName), "text"),
            GetSQLValueString($_POST['jabatan'], "text"),
            GetSQLValueString($_POST['division'], "text"),
            GetSQLValueString(date("Y-m-d G:i:s", strtotime($_POST['start_date'])), "date"),
            GetSQLValueString(date("Y-m-d G:i:s", strtotime($_POST['end_date'])), "date"),
            // GetSQLValueString(doubleval(str_replace(",", ".", ((strtotime($_POST['end_date']) - strtotime($_POST['start_date']))/3600))), "double"),
            GetSQLValueString($duration, "text"),
            GetSQLValueString($_POST['manager'], "text"),
            GetSQLValueString($_POST['leader'], "text"),
            GetSQLValueString(addslashes($_POST['reason']), "text"),
            // GetSQLValueString($_POST['status'], "text"),
            GetSQLValueString("edo submitted", "text"),
            GetSQLValueString(addslashes($names[0]), "text"),
            GetSQLValueString(date("Y-m-d G:i:s"), "date"),
            GetSQLValueString($performed_by, "text"),
            GetSQLValueString($_POST['category'], "text"),
            GetSQLValueString($_POST['project_code'], "text")
        );
        $res = $DBHCM->insert_data($tblname, $insert);
        $ALERT->savedata();
        $to = $_POST['employee_name'] . "; " . $_POST['manager'] . "; ";
        // $msg = format_approval_message($_POST['manager']);
        $status = "Submitted";

        $DBNAV = get_conn("NAVISION");
        $mysql = sprintf(
            "SELECT `project_name` FROM `sa_mst_order_number` WHERE `project_code` = %s;",
            GetSQLValueString($_POST['project_code'], "text")
        );
        $rsKP = $DBNAV->get_sql($mysql);
        $project_name = "";
        if($rsKP[2]>0)
        {
            $project_name = $rsKP[0]['project_name'];
        }

        $subject = "[EDO] Apply of EDO";
        $msgx = sprintf(
            "<p>To %s, </p>
    
            <p>I hereby submit your approval to my EDO as follows: </p>
            
            <p>Name : %s <br/>
            Position : %s <br/>
            Department : %s <br/>
            Date: %s to %s <br/>
            Duration: %s hours <br/>
            Project Code : %s <br/>
            Project Name : %s <br/>
            Status : %s <br/>
            Reason : %s </p>
            
            <p>Please make your approval before %s. </p>
            
            <p>Thank You. <br/>
            %s</p>",
            GetSQLValueString($_POST['manager'], 'defined', $_POST['manager'], $_POST['manager']),
            GetSQLValueString($_POST['employee_name'], 'defined', $_POST['employee_name'], $_POST['employee_name']),
            GetSQLValueString($_POST['jabatan'], 'defined', $_POST['jabatan'], $_POST['jabatan']),
            GetSQLValueString($_POST['division'], 'defined', $_POST['division'], $_POST['division']),
            GetSQLValueString(date('d-M-Y G:i:s', strtotime($_POST['start_date'])), 'date'),
            GetSQLValueString(date('d-M-Y G:i:s', strtotime($_POST['end_date'])), 'date'),
            GetSQLValueString($_POST['duration'], 'defined', $_POST['duration'], $_POST['duration']),
            GetSQLValueString($_POST['project_code'], 'defined', $_POST['project_code'], $_POST['project_code']),
            GetSQLValueString($project_name, 'defined', $project_name, $project_name),
            GetSQLValueString($_POST['category'], 'defined', $_POST['category'], $_POST['category']),
            GetSQLValueString($_POST['reason'], 'text'),
            GetSQLValueString(date('d-M-Y', strtotime('+7 day')), 'text'),
            GetSQLValueString($_POST['employee_name'], 'defined', $_POST['employee_name'], $_POST['employee_name'])
        );
    
    } else
    {
        $ALERT->msgcustom("danger", "EDO dated " . date("d M Y", strtotime($_POST['start_date'])) . " has already been submitted, please choose another date.");
    }
// } elseif(isset($_POST['save'])) {
//     $checkup = check_double($_POST['edo_id']);
//     if($checkup==FALSE)
//     {
//         $condition = "`edo_id`=" . $_POST['edo_id'];
//         $update = sprintf("`employee_name`=%s, `jabatan`=%s,`division`=%s,`start_date`=%s,`end_date`=%s,`duration`=%s,`manager`=%s,`leader`=%s,`reason`=%s,`status`=%s,`performed_by`=%s,`category`=%s,`project_code`=%s",
//             GetSQLValueString(addslashes($_POST['employee_name']), "text"),
//             GetSQLValueString($_POST['jabatan'], "text"),
//             GetSQLValueString($_POST['division'], "text"),
//             GetSQLValueString(date("Y-m-d G:i:s", strtotime($_POST['start_date'])), "date"),
//             GetSQLValueString(date("Y-m-d G:i:s", strtotime($_POST['end_date'])), "date"),
//             GetSQLValueString(doubleval(str_replace(",", ".", ((strtotime($_POST['end_date']) - strtotime($_POST['start_date']))/3600))), "double"),
//             GetSQLValueString(addslashes($_POST['manager']), "text"),
//             GetSQLValueString(addslashes($_POST['leader']), "text"),
//             GetSQLValueString(addslashes($_POST['reason']), "text"),
//             GetSQLValueString("edo submitted", "text"),
//             GetSQLValueString($performed_by, "text"),
//             GetSQLValueString($_POST['category'], "text"),
//             GetSQLValueString($_POST['project_code'], "text")
//         );
//         $res = $DBHCM->update_data($tblname, $update, $condition);
//         $ALERT->savedata();
//     } else
//     {
//         $ALERT->msgcustom("danger", "EDO tanggal " . date("d M Y", strtotime($_POST['start_date'])) . " sudah pernah diajukan.");
//     }
// } elseif(isset($_POST['request_submitted'])) {
//     $condition = "edo_id=" . $_POST['edo_id'];
//     $update = sprintf("`status`='edo submitted', `performed_by`=%s",
//         GetSQLValueString($performed_by, "text")
//     );
//     $res = $DBHCM->update_data($tblname, $update, $condition);
//     $to = $_POST['employee_name1'] . "; " . $_POST['manager'] . "; " . $_POST['leader'];
//     $msg1 = "Extra Day Off anda telah di submited oleh " . $names[0];
//     $status = "Submitted";
} elseif(isset($_POST['request_approved']) || (isset($_GET['todo']) && $_GET['todo']=='approve' && $_GET['status']=='edo submitted')) {
    if(isset($_POST['request_approved'])) {
        $condition = "edo_id=" . $_POST['edo_id'];
        $to = $_POST['employee_name1'] . $_POST['manager'] . "; ";
    } else {
        $condition = "edo_id=" . $_GET['edo_id'];
        $to = $_GET['employee'] . $_GET['manager'] . "; ";
    }
    $xxx = get_flag_approval($_POST['edo_id']);
    $flag = $xxx[0];
    $flag++;
    $approve = $xxx[1] . $performed_by . ";";
    $update = sprintf("`status`='request approved', `flag_approval`=%s, `overtime_approval_by`=%s, `performed_by`=%s",
        GetSQLValueString($flag, "int"),
        GetSQLValueString($approve, "text"),
        GetSQLValueString($performed_by, "text")
    );
    $res = $DBHCM->update_data($tblname, $update, $condition);
    // $msg1 = "Extra Day Off anda telah di approved oleh " . $names[0];
    $status = "Approved";
    $subject = "[EDO] Your leave has been approved";
    $msgx = sprintf(
        "
        <p>To %s, </p>

        <p>I hereby approved your EDO request for date %s with the following notes: </p>
        
        <p>%s </p>
        
        <p>Thank You, <br/>
        %s</p>",
        GetSQLValueString($to, "defined", $to, $to),
        GetSQLValueString(date("d-M-Y", strtotime($_POST['start_date'])), "date"),
        GetSQLValueString($_POST['reason'], "text"),
        GetSQLValueString($performed_by, "defined", $performed_by, $performed_by)
    );
// } elseif(isset($_POST['leave_submitted'])) {
//     $checkup = check_double2($_POST['edo_id']);
//     if($checkup==FALSE)
//     {
//         $condition = "edo_id=" . $_POST['edo_id'];
//         $to = $_POST['employee_name1'] . $_POST['manager'] . "; ";
//         $update = sprintf("`status`='leave submitted',`actual_start`=%s,`actual_end`=%s, `performed_by`=%s",
//             GetSQLValueString(date("Y-m-d G:i:s", strtotime($_POST['actual_start'])), "date"),
//             GetSQLValueString(date("Y-m-d G:i:s", strtotime($_POST['actual_end'])), "date"),
//             GetSQLValueString($performed_by, "text")
//         );
//         $res = $DBHCM->update_data($tblname, $update, $condition);
//         if(isset($_POST['leader']))
//         {
//             $leader = $_POST['leader'];
//         } else
//         {
//             $leader = $_POST['leader_name'];
//         }
//         $msg1 = "Extra Day Off anda telah di submited oleh " . $names[0];
//         $status = "Submitted";
//     } else
//     {
//         $ALERT->msgcustom("danger", "Cuti tanggal " . date("d M Y", strtotime($_POST['actual_start'])) . " sudah pernah diajukan.");
//     }
// } elseif(isset($_POST['leave_approved']) || (isset($_GET['todo']) && $_GET['todo']=='approve' && $_GET['status']=='leave submitted')) {
//     if(isset($_POST['leave_approved'])) {
//         $condition = "edo_id=" . $_POST['edo_id'];
//         $to = $_POST['employee_name1'] . ";" . $_POST['manager'] . "; ";
//     } else {
//         $condition = "edo_id=" . $_GET['edo_id'];
//         $to = $_GET['employee'] . ";" . $_GET['manager'];
//     }
//     $update = sprintf("`status`='completed',`performed_by`=%s",
//         GetSQLValueString($performed_by, "text")
//     );
//     $res = $DBHCM->update_data($tblname, $update, $condition);
//     $msg1 = "Extra Day Off anda  telah di dicompleted oleh " . $names[0];
//     $status = "Completed";
// } elseif(isset($_POST['leave_expired'])) {
//     $condition = "edo_id=" . $_POST['edo_id'];
//     $update = sprintf("`status`='expired', `performed_by`=%s",
//         GetSQLValueString($performed_by, "text")
//     );
//     $res = $DBHCM->update_data($tblname, $update, $condition);
//     $to = $_POST['employee_name1'] . ";";
//     $msg1 = "Extra Day Off anda telah di expired.";
//     $status = "Expired";
// } elseif(isset($_POST['request_rejected']) || (isset($_GET['todo']) && $_GET['todo']=='reject' && $_GET['status']=='edo submitted')) {
//     if(isset($_POST['request_rejected'])) {
//         $condition = "edo_id=" . $_POST['edo_id'];
//         $to = $_POST['employee_name1'] . ";" . $_POST['manager'] . "; " . $_POST['entry_by'];
//     } else {
//         $condition = "edo_id=" . $_GET['edo_id'];
//         $to = $_GET['employee'] . ";" . $_GET['manager'];
//     }
//     $update = sprintf("`status`='edo rejected', `performed_by`=%s",
//         GetSQLValueString($performed_by, "text")
//     );
//     $res = $DBHCM->update_data($tblname, $update, $condition);
//     // $msg1 = "Extra Day Off anda telah di rejected oleh " . $names[0];
//     $status = "Rejected";
//     $msgx = sprintf(
//         "
//         To %s, \r\n\r\n

//         I hereby do not approve your EDO request for date %s with the following notes: \r\n\r\n
        
//         %s \r\n\r\n
        
//         Thank You, \r\n\r\n
//         %s",
//         GetSQLValueString($to, "text"),
//         GetSQLValueString(date("d-M-Y", strtotime($_POST['start_date'])), "date"),
//         GetSQLValueString($_POST['reason'], "text"),
//         GetSQLValueString($performed_by, "text")
//     );
// } elseif(isset($_POST['leave_rejected']) || (isset($_GET['todo']) && $_GET['todo']=='reject' && $_GET['status']=='leave submitted')) {
//     if(isset($_POST['leave_rejected'])) {
//         $condition = "edo_id=" . $_POST['edo_id'];
//         $to = $_POST['employee_name1'] . ";" . $_POST['manager'] . "; " . $_POST['entry_by'];
//     } else {
//         $condition = "edo_id=" . $_GET['edo_id'];
//         $to = $_GET['employee'] . ";" . $_GET['manager'];
//     }
//     $update = sprintf("`status`='leave rejected', `performed_by`=%s",
//         GetSQLValueString($performed_by, "text")
//     );
//     $res = $DBHCM->update_data($tblname, $update, $condition);
//     $msg1 = "Extra Day Off anda telah di rejected oleh " . $names[0];
//     $status = "Rejected";
} elseif(isset($_POST['delete'])) {
    $condition = "edo_id=" . $_POST['edo_id'];
    $res = $DBHCM->delete_data($tblname, $condition);
// } elseif(isset($_POST['resubmitted']))
// {
//     $condition = "edo_id=" . $_POST['edo_id'];
//     $update = "`performed_date` = '" . date("Y-m-d G:i:s") . "'"; echo $update;
//     $update = $DBHCM->update_data($tblname, $update, $condition);
}

if(isset($_POST['add_leave']) || isset($_POST['edit_leave']))
{
    if(isset($_POST['add_leave']))
    {
        // New Leave
        $mysql = sprintf(
            "INSERT INTO `sa_trx_edo_leave`
                (
                    `employee_name`, 
                    `employee_email`,
                    `leader1_email`,
                    `leader2_email`, 
                    `leave_start`, 
                    `leave_end`, 
                    `leave_status`, 
                    `entry_by`, 
                    `entry_date`, 
                    `update_by`
                ) VALUES 
                (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
            GetSQLValueString($MyName, "text"),
            GetSQLValueString($MyEmail, "text"),
            GetSQLValueString($_POST['leader1_email'], "text"),
            GetSQLValueString($_POST['leader2_email'], "text"),
            GetSQLValueString(date("Y-m-d", strtotime($_POST['leave_start'])), "date"),
            GetSQLValueString(date("Y-m-d", strtotime($_POST['leave_end'])), "date"),
            GetSQLValueString("leave submitted", "text"),
            GetSQLValueString($MyName, "text"),
            GetSQLValueString(date("Y-m-d G:i:s"), "date"),
            GetSQLValueString($MyName, "text")
        );
        $rs = $DBHCM->get_sql($mysql, false);
    } elseif(isset($_POST['edit_leave']))
    {
        // Update Leave
        $mysql = sprintf(
            "UPDATE `sa_trx_edo_leave` 
            SET `leave_start`=%s,
                `leave_end`=%s,
                `update_by`=%s
            WHERE `leave_id`=%s",
            GetSQLValueString(date("Y-m-d", strtotime($_POST['leave_start'])), "date"),
            GetSQLValueString(date("Y-m-d", strtotime($_POST['leave_end'])), "date"),
            GetSQLValueString($MyName, "text"),
            GetSQLValueString($_POST['leave_id'], "int")
        );
        $rs = $DBHCM->get_sql($mysql, false);
    }

    $mysql = "SELECT `leave_id` FROM `sa_trx_edo_leave` ORDER BY `leave_id` DESC LIMIT 1";
    $rsLeaveID = $DBHCM->get_sql($mysql);
    $leave_id = 0;
    if($rsLeaveID[2]>0)
    {
        $leave_id = $rsLeaveID[0]['leave_id'];
    }
    
    $mysql = "DELETE FROM `sa_trx_edo_leave_detail` WHERE `leave_id` = " . $leave_id;
    $rs = $DBHCM->get_sql($mysql, false);
    // $i = 0;
    foreach($_POST['edo'] as $edo)
    {
        // if($i<2)
        // {
            $mysql = sprintf("INSERT INTO `sa_trx_edo_leave_detail`(`leave_id`, `edo_id`) VALUES (%s,%s)",
                GetSQLValueString($leave_id, "int"),
                GetSQLValueString($edo, "int")
            );
            $rs = $DBHCM->get_sql($mysql, false);

            $mysql = 'UPDATE `sa_trx_edo_request` SET `status`="leave submitted", `flag_approval` = 0, `performed_by`="' . addslashes($MyFullName) . '" WHERE `edo_id` = ' . $edo;
            $rs = $DBHCM->get_sql($mysql, false);
        // }
        // $i++;
    }
    // if($i>2)
    // {
        ?>
        <!-- <div class="alert alert-danger" role="alert">
            You select more than two tasks. The system takes the two earliest choices.
        </div> -->
        <?php
    // }
}

if($to!="") {
    // Send email notification
    $owner=get_leader($_SESSION['Microservices_UserEmail'], 1);
    $downer = $owner[0];
    $from = $downer['employee_name'] . "<" . $downer['employee_email'] . ">; ";
    $cc = $from;
    $bcc = "";
    $reply=$from;
    // $subject="[MSIZone] Extra Day Off " . ucwords($status);
    $msg="<table width='100%'";
    $msg.="<tr><td width='20%' rowspan='3'></td><td style='width:60%; border: thin solid #dadada; padding:20px'>";
    $msg.="<img src='https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png'>";
    $msg.="<br/>";
    // if(isset($_GET['employee'])) 
    // {
    //     $name = $_GET['employee'];
    // } elseif(isset($_POST['employee_name']))
    // {
    //     $name = $_POST['employee_name'];
    // } elseif($_POST['employee_name1'])
    // {
    //     $name = $_POST['employee_name1'];
    // }
    // $msg.="<p>Dear " . $name . "</p>";
    // $msg.="<p>" . $msg1 . "</p>";
    // $msg.="<p>";
    // $msg.="</p>";
    // $msg.="<p>Terimakasih,<br/>";
    // $msg.=$_SESSION['Microservices_UserName'] . "</p>";
    $msg .= $msgx;
    $msg.="</td><td width='30%' rowspan='3'>";
    $msg.="<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
    $msg.="<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
    $msg.="</table>";

    $headers = "From: " . $from . "\r\n" .
        "Reply-To: " . $reply . "\r\n" .
        "Cc: " . $cc . "\r\n" .
        "Bcc: " . $bcc . "\r\n" .
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
// echo $msg;
    // Save Notification in MSIZone
    $mdlname = "NOTIFICATION";
    $DBNOTIF = get_conn($mdlname);
    $tblname = "trx_notification";
    $notifmsg= $msg;
    if(isset($_POST['edo_id'])) {
        $edo_id = $_POST['edo_id'];
    } else {
        $edo_id = $_GET['edo_id'];
    }
    $notif_link = "index.php?mod=hcm&sub=edo&act=view&edo_id=" . $edo_id . "&submit=Submit";
    $insert = sprintf("(`notif_from`, `notif_to`, `notif_subject`, `notif_post`, `notif_link`) VALUES (%s, %s, %s, %s, %s)",
        GetSQLValueString(addslashes($from), "text"),
        GetSQLValueString(addslashes($to), "text"),
        GetSQLValueString(ucwords($status) . " Extra Day Off " . $edo_id, "text"),
        GetSQLValueString(addslashes($notifmsg), "text"),
        GetSQLValueString(addslashes($notif_link), "text")
    );
    $res = $DBNOTIF->insert_data($tblname, $insert);

}

function get_status($status)
{
    if($status == "edo submitted")
    {
        echo "EDO Submitted";
    } else
    if($status == "edo rejected")
    {
        echo "EDO Rejected";
    } else
    if($status == "request approved")
    {
        echo "EDO Approved";
    } else
    if($status == "leave submitted")
    {
        echo "Leave Submitted";
    } else
    if($status == "leave rejected")
    {
        echo "Leave Rejected";
    } else
    if($status == "completed")
    {
        echo "Leave Approved";
    } else
    if($status == "completed with paid")
    {
        echo "Cut-off With Paid";
    } else
    if($status == "cancel by cutoff")
    {
        echo "Cut-off by Cancel";
    } 
}
?>