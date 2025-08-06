<?php
$mdlname = "HCM";
$DBHCM = get_conn($mdlname);
$names = $DBHCM->get_leader_v2($_SESSION['Microservices_UserEmail']);
$to = "";
$msg1 = "";
$status = "";
$performed_by = $_SESSION['Microservices_UserName'] . "<" . $_SESSION['Microservices_UserEmail'] . ">";
if(isset($_POST['add'])) {
    $insert = sprintf("(`employee_name`,`jabatan`,`division`,`start_date`,`end_date`,`actual_start`,`actual_end`,`duration`,`leader`,`reason`,`status`,`entry_by`,`entry_date`,`performed_by`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
        GetSQLValueString($_POST['employee_name'], "text"),
        GetSQLValueString($_POST['jabatan'], "text"),
        GetSQLValueString($_POST['division'], "text"),
        GetSQLValueString(date("Y-m-d G:i:s", strtotime($_POST['start_date'])), "date"),
        GetSQLValueString(date("Y-m-d G:i:s", strtotime($_POST['end_date'])), "date"),
        GetSQLValueString(date("Y-m-d G:i:s", strtotime($_POST['actual_start'])), "date"),
        GetSQLValueString(date("Y-m-d G:i:s", strtotime($_POST['actual_end'])), "date"),
        GetSQLValueString(((strtotime($_POST['end_date']) - strtotime($_POST['start_date']))/3600), "text"),
        GetSQLValueString($_POST['leader'], "text"),
        GetSQLValueString($_POST['reason'], "text"),
        GetSQLValueString($_POST['status'], "text"),
        GetSQLValueString($names[0], "text"),
        GetSQLValueString(date("Y-m-d G:i:s"), "date"),
        GetSQLValueString($performed_by, "text")
    );
    $res = $DBHCM->insert_data($tblname, $insert);
    $ALERT->savedata();
} elseif(isset($_POST['save'])) {
    $condition = "edo_id=" . $_POST['edo_id'];
    $update = sprintf("`employee_name`=%s,`jabatan`=%s,`division`=%s,`start_date`=%s,`end_date`=%s,`actual_start`=%s,`actual_end`=%s,`duration`=%s,`leader`=%s,`reason`=%s,`status`=%s,`performed_by`=%s",
        GetSQLValueString($_POST['employee_name'], "text"),
        GetSQLValueString($_POST['jabatan'], "text"),
        GetSQLValueString($_POST['division'], "text"),
        GetSQLValueString(date("Y-m-d G;i:s", strtotime($_POST['start_date'])), "date"),
        GetSQLValueString(date("Y-m-d G;i:s", strtotime($_POST['end_date'])), "date"),
        GetSQLValueString(date("Y-m-d G:i:s", strtotime($_POST['actual_start'])), "date"),
        GetSQLValueString(date("Y-m-d G:i:s", strtotime($_POST['actual_end'])), "date"),
        GetSQLValueString(((strtotime($_POST['end_date']) - strtotime($_POST['start_date']))/3600), "text"),
        GetSQLValueString($_POST['leader'], "text"),
        GetSQLValueString($_POST['reason'], "text"),
        GetSQLValueString("drafted", "text"),
        GetSQLValueString($performed_by, "text")
    );
    $res = $DBHCM->update_data($tblname, $update, $condition);
    $ALERT->savedata();
} elseif(isset($_POST['request_submitted'])) {
    $condition = "edo_id=" . $_POST['edo_id'];
    $update = sprintf("`status`='edo submitted', `performed_by`=%s",
        GetSQLValueString($performed_by, "text")
    );
    $res = $DBHCM->update_data($tblname, $update, $condition);
    $to = $_POST['employee_name1'] . "; " . $_POST['manager'] . "; " . $_POST['leader'];
    $msg1 = "Extra Day Off anda telah di submited oleh " . $names[0];
    $status = "Submited";
} elseif(isset($_POST['request_approved'])) {
    $condition = "edo_id=" . $_POST['edo_id'];
    $update = sprintf("`status`='request approved', `performed_by`=%s",
        GetSQLValueString($performed_by, "text")
    );
    $res = $DBHCM->update_data($tblname, $update, $condition);
    $to = $_POST['employee_name1'] . $_POST['manager'] . "; ";
    $msg1 = "Extra Day Off anda telah di approved oleh " . $names[0];
    $status = "Approved";
} elseif(isset($_POST['leave_submitted'])) {
    $condition = "edo_id=" . $_POST['edo_id'];
    $update = sprintf("`status`='leave submitted',`actual_start`=%s,`actual_end`=%s, `performed_by`=%s",
        GetSQLValueString(date("Y-m-d G:i:s", strtotime($_POST['actual_start'])), "date"),
        GetSQLValueString(date("Y-m-d G:i:s", strtotime($_POST['actual_end'])), "date"),
        GetSQLValueString($performed_by, "text")
    );
    $res = $DBHCM->update_data($tblname, $update, $condition);
    if(isset($_POST['leader']))
    {
        $leader = $_POST['leader'];
    } else
    {
        $leader = $_POST['leader_name'];
    }
    $to = $_POST['employee_name1'] . "; " . $_POST['manager'] . "; " . $leader;
    $msg1 = "Extra Day Off anda telah di submited oleh " . $names[0];
    $status = "Submited";
} elseif(isset($_POST['leave_approved'])) {
    $condition = "edo_id=" . $_POST['edo_id'];
    $update = sprintf("`status`='completed',`performed_by`=%s",
        GetSQLValueString($performed_by, "text")
    );
    $res = $DBHCM->update_data($tblname, $update, $condition);
    $to = $_POST['employee_name1'] . ";" . $_POST['manager'] . "; ";
    $msg1 = "Extra Day Off anda  telah di dicompleted oleh " . $names[0];
    $status = "Completed";
} elseif(isset($_POST['leave_expired'])) {
    $condition = "edo_id=" . $_POST['edo_id'];
    $update = sprintf("`status`='expired', `performed_by`=%s",
        GetSQLValueString($performed_by, "text")
    );
    $res = $DBHCM->update_data($tblname, $update, $condition);
    $to = $_POST['employee_name1'] . ";";
    $msg1 = "Extra Day Off anda telah di expired.";
    $status = "Expired";
} elseif(isset($_POST['request_rejected'])) {
    $condition = "edo_id=" . $_POST['edo_id'];
    $update = sprintf("`status`='edo rejected', `performed_by`=%s",
        GetSQLValueString($performed_by, "text")
    );
    $res = $DBHCM->update_data($tblname, $update, $condition);
    $to = $_POST['employee_name1'] . ";" . $_POST['manager'] . "; " . $_POST['entry_by'];
    $msg1 = "Extra Day Off anda telah di rejected oleh " . $names[0];
    $status = "Rejected";
} elseif(isset($_POST['leave_rejected'])) {
    $condition = "edo_id=" . $_POST['edo_id'];
    $update = sprintf("`status`='leave rejected', `performed_by`=%s",
        GetSQLValueString($performed_by, "text")
    );
    $res = $DBHCM->update_data($tblname, $update, $condition);
    $to = $_POST['employee_name1'] . ";" . $_POST['manager'] . "; " . $_POST['entry_by'];
    $msg1 = "Extra Day Off anda telah di rejected oleh " . $names[0];
    $status = "Rejected";
}

if($to!="") {
    // Send email notification
    $owner=get_leader($_SESSION['Microservices_UserEmail'], 1);
    $downer = $owner[0];
    $from = $downer['employee_name'] . "<" . $downer['employee_email'] . ">; ";
    $cc = $from;
    $bcc = "";
    $reply=$from;
    $subject="[MSIZone] Extra Day Off " . ucwords($status);
    $msg="<table width='100%'";
    $msg.="<tr><td width='30%' rowspan='3'></td><td style='width:40%; border: thin solid #dadada; padding:20px'>";
    $msg.="<img src='https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png'>";
    $msg.="<br/>";
    if(isset($_POST['employee_name']))
    {
        $name = $_POST['employee_name'];
    } else
    {
        $name = $_POST['employee_name1'];
    }
    $msg.="<p>Dear " . $name . "</p>";
    $msg.="<p>" . $msg1 . "</p>";
    $msg.="<p>";
    // $msg.="<table style='width:100%;'>";
    // $msg.="<tr><td>FSB Number</td><td>: </td><td>" . $sb_number . "</td></tr>";
    // $msg.="<tr><td>Project Code</td><td>: </td><td>" . $_POST['project_code'] . "</td></tr>";
    // $msg.="<tr><td>SO Number</td><td>: </td><td>" . $_POST['so_number'] . "</td></tr>";
    // $msg.="<tr><td style='vertical-align:top'>Project Name </td><td style='vertical-align:top'>: </td><td style='vertical-align:top'>" . $_POST['project_name'] . "</td></tr>";
    // $msg.="<tr><td style='vertical-align:top'>Customer Name </td><td style='vertical-align:top'>: </td><td style='vertical-align:top'>" . $_POST['customer_name'] . "</td></tr>";
    // $msg.="</table>";
    $msg.="</p>";
    // $msg.="<p>" . $msg1 . "</p>";
    $msg.="<p>Terimakasih,<br/>";
    $msg.=$_SESSION['Microservices_UserName'] . "</p>";
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

    // Save Notification in MSIZone
    $mdlname = "NOTIFICATION";
    $DBNOTIF = get_conn($mdlname);
    $tblname = "trx_notification";
    $notifmsg= $msg1;
    $notif_link = "index.php?mod=hcm&sub=edo&act=view&edo_id=" . $_POST['edo_id'] . "&submit=Submit";
    $insert = sprintf("(`notif_from`, `notif_to`, `notif_subject`, `notif_post`, `notif_link`) VALUES (%s, %s, %s, %s, %s)",
        GetSQLValueString($from, "text"),
        GetSQLValueString($to, "text"),
        GetSQLValueString(ucwords($status) . " Extra Day Off" . $_POST['edo_id'], "text"),
        GetSQLValueString($notifmsg, "text"),
        GetSQLValueString($notif_link, "text")
    );
    $res = $DBNOTIF->insert_data($tblname, $insert);

}
?>