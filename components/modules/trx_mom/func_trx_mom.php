<?php
if (isset($_POST['add'])) {
    $insert = sprintf(
        "(`mom_id`,`module_name`,`created_by`,`etc`,`status_mom`,`module_note`) VALUES (%s,%s,%s,%s,%s,%s)",
        GetSQLValueString($_POST['mom_id'], "int"),
        GetSQLValueString($_POST['module_name'], "text"),
        GetSQLValueString($_POST['created_by'], "text"),
        GetSQLValueString($_POST['etc'], "date"),
        GetSQLValueString($_POST['status_mom'], "text"),
        GetSQLValueString($_POST['module_note'], "text")
    );

    $from = "MSIZone<msizone@mastersystem.co.id>";
    $cc = $from;
    $bcc = "";
    $reply = $from;
    $to_name = $_POST['created_by'];
    $mdl_name = $_POST['module_name'];
    $mdl_note = $_POST['module_note'];
    $mdl_date = $_POST['etc'];
    $subject = "Report MSIZONE";
    $msg = "<table width='100%'>";
    $msg .= "<tr><td rowspan='5'></td><td>";
    $msg .= "<img src='https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png'>";
    $msg .= "</td></tr>";
    $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'>";
    $msg .= "<br/>";
    $msg .= "<p>Dear " . $to_name . "</p>";
    $msg .= "<table style='width:100%;'>";
    $msg .= "<tr><td></td></tr>";
    $msg .= "<tr><td>Berikut Adalah Catatan Perubahan Module Sebagai berikut :</td></tr>";
    $msg .= "<tr><td></td></tr>";
    $msg .= "<tr><td></td></tr>";
    $msg .= "<tr><td></td></tr>";
    $msg .= "<table>";
    $msg .= "<tr>";
    $msg .= "<td></td>";
    $msg .= "<td></td>";
    $msg .= "<td></td>";
    $msg .= "<td></td>";
    $msg .= "</tr>";
    $msg .= "<tr>";
    $msg .= "<td>Nama Module</td>";
    $msg .= "<td>:</td>";
    $msg .= "<td align='left'>" . $mdl_name . "</td>";
    $msg .= "<td></td>";
    $msg .= "</tr>";
    $msg .= "<tr>";
    $msg .= "<td>Catatan Module</td>";
    $msg .= "<td>:</td>";
    $msg .= "<td align='left'>" . $mdl_note . "</td>";
    $msg .= "<td></td>";
    $msg .= "</tr>";
    $msg .= "<tr>";
    $msg .= "<td>Target Pengerjaan</td>";
    $msg .= "<td>:</td>";
    $msg .= "<td align='left'>" . $mdl_date . "</td>";
    $msg .= "<td></td>";
    $msg .= "</tr>";
    $msg .= "<tr><td></td></tr>";
    $msg .= "<tr><td></td></tr>";
    $msg .= "</table>";
    $msg .= "<table>";
    $msg .= "<tr><td>Terimakasih</td></tr>";
    $msg .= "<tr><td>Bapak/ibu. </td></tr>";
    $msg .= "<tr><td></td></tr>";
    $msg .= "</table>";
    $msg .= "<td width='30%' rowspan='5'>";
    $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
    $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
    $msg .= "</table>";
    echo $msg;
    $headers = "From: " . $from . "\r\n" .
        "Reply-To: " . $reply . "\r\n" .
        "Cc: " . $cc . "\r\n" .
        "Bcc: " . $bcc . "\r\n" .
        "MIME-Version: 1.0" . "\r\n" .
        "Content-Type: text/html; charset=UTF-8" . "\r\n" .
        "X-Mailer: PHP/" . phpversion();

    $ALERT = new Alert();
    if (!mail($to_name, $subject, $msg, $headers)) {
        echo $ALERT->email_not_send();
    } else {
        echo $ALERT->email_send();
    }
    $res = $DBMOM->insert_data($tblname, $insert);
    $ALERT->savedata();
} elseif (isset($_POST['save'])) {
    $condition = "mom_id=" . $_POST['mom_id'];
    $update = sprintf(
        "`mom_id`=%s,`module_name`=%s,`etc`=%s,`module_note`=%s,`status_mom`=%s",
        GetSQLValueString($_POST['mom_id'], "int"),
        GetSQLValueString($_POST['module_name'], "text"),
        GetSQLValueString($_POST['etc'], "date"),
        GetSQLValueString($_POST['module_note'], "text"),
        GetSQLValueString($_POST['status_mom'], "text")
    );
    $res = $DBMOM->update_data($tblname, $update, $condition);
    $ALERT->savedata();
}
