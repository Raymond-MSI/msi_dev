<?php

    if(isset($_POST['add'])) {
        $link_datetime = date('U');
    $survey_link = md5($link_datetime);
        $insert = sprintf("(`pic_id`, `template_id`, `so_number`, `survey_link`,`link_datetime`,`main_template_id`, `extra_information`,`created_by`) VALUES (%s,%s, %s, %s, %s, %s, %s, %s)",
            GetSQLValueString($_POST['pic_id'], "int"),
            GetSQLValueString($_POST['template_id'], "int"),
            GetSQLValueString($_POST['so_number'], "text"),
            GetSQLValueString($survey_link, "text"),
            GetSQLValueString($link_datetime, "text"),
            GetSQLValueString($_POST['main_template_id'], "int"),
            GetSQLValueString($_POST['extrainf'], "text"),
            GetSQLValueString($_SESSION['Microservices_UserName'].'<'.$_SESSION['Microservices_UserEmail'].'>', "text")
        );
        $res = $DB->insert_data($tblname, $insert);
        $ALERT->savedata();
    $msg1 = '';



$msg1 .= '<p>Kami mengucapkan terima kasih atas kesempatan yang diberikan kepada kami dalam project '.$_POST['project_name'].'</p>';
$msg1 .= '<p>Sebagai masukan dan koreksi terhadap layanan kami, kami berharap Bapak/Ibu
dapat meluangkan waktu mengisi post-project review dengan mengklik <a href="?mod=ui_survey&link='.$survey_link.'">link</a> berikut ini.</p>';

$msg1 .= '<p>Kami sangat berharap dapat terus melayani kebutuhan Bapak/Ibu untuk jangka panjang.</p>';
$msg1 .= '<p>Komitmen Mastersystem adalah senantiasa memberikan Service Excellence kepada para Pelanggan,
sehingga masukan dan koreksi Bapak/Ibu sangat berharga bagi kami untuk terus memperbaiki kualitas layanan.</p>';

    //function below not yet applied
    //$owner=get_leader($_SESSION['Microservices_UserEmail'], 1);
    //$downer = $owner[0];
    //$from = $downer['employee_name'] . "<" . $downer['employee_email'] . ">; ";
    $from = 'hendra.putra002@yahoo.com';
    $cc = $from;
    $bcc = "";
    $reply=$from;
    $subject="Post Project Review";
    $msg="<table width='100%'>";
    $msg.="<tr><td width='30%' rowspan='3'></td><td style='width:40%; border: thin solid #dadada; padding:20px'>";
    $msg.="<img src='https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png'>";
    $msg.="<br/>";
    //$to = $_POST['pic_email'];
    $to = 'hendra.putra002@yahoo.com';
    $msg.="<p>Bapak/Ibu Pelanggan yang Terhormat,</p>";
    $msg.="<p>" . $msg1 . "</p>";
    $msg.="<p>";
    $msg.="</p>";
    $msg.="<p>Terimakasih,</p>";
    $msg.='<p>Customer Care<br/><br/>Email : Customer.Care@mastersystem.co.id</p>';
    $msg.="</table>";

    $headers = "From: " . $from . "\r\n" .
        "Reply-To: " . $reply . "\r\n" .
        "Cc: " . $cc . "\r\n" .
        "Bcc: " . $bcc . "\r\n" .
        "MIME-Version: 1.0" . "\r\n" .
        "Content-Type: text/html; charset=UTF-8" . "\r\n" .
        "X-Mailer: PHP/" . phpversion();
        
    /*$ALERT=new Alert();
    if(!mail($to, $subject, $msg, $headers))
    {
        echo $ALERT->email_not_send();
    } else
    {
        echo $ALERT->email_send();
    }*/
    echo $msg;

    // Save Notification in MSIZone
    /*$mdlname = "NOTIFICATION";
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
    $res = $DBNOTIF->insert_data($tblname, $insert);*/
    } elseif(isset($_POST['save'])) {
        $condition = "survey_id=" . $_POST['survey_id'];
        $update = sprintf("`survey_id`=%s",
            GetSQLValueString($_POST['survey_id'], "int")
    );
        $res = $DB->update_data($tblname, $update, $condition);
        $ALERT->savedata();
    }
    ?>