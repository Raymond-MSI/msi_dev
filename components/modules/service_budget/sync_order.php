<?php
$_SESSION['Microservices_UserEmail']="MSIZone<MSIZone@mastersystem.co.id>";
include( "components/classes/func_service_budget.php");
$mdlname = "SERVICE_BUDGET";
$DBSB = get_conn($mdlname);
$mdlname = "NAVISION";
$DBNAVISION = get_conn($mdlname);

$tblnameproject = "trx_project_list";
$condition = "(`order_number` IS NULL) AND status='acknowledge'";
$order = "";
$limit = 50;
$project = $DBSB->get_data($tblnameproject, $condition, $order, 0, $limit);
$msg1 = "<p>Target Data : " . $project[2] . "</p>";
$msg1.= "<table class='table' style='width:100%'>";
$msg1.="<thead><tr><th>Project Code</th><th>SO Number</th><th>Update Order Number</th><th>Status</th></tr></thead>";
$msg1.="<tbody>";
$tblname = "mst_order_number";
$i=0;
do {
    $condition = "`so_number` = '" . $project[0]['so_number'] . "' AND `order_number` IS NOT NULL";
    $orderNumbers = $DBNAVISION->get_data($tblname, $condition);
    if($orderNumbers[2]>0) {
        $amount = $orderNumbers[0]['currency_code']=="USD" ? "USD." . $orderNumbers[0]['amount'] : "IDR." . $orderNumbers[0]['amount'];
        $msg1 .= "<tr><td>" . $project[0]['project_code'] . "</td><td>" . $project[0]['so_number'] . "</td><td>" . $orderNumbers[0]['order_number'] . "</td>";
        $condition = "`project_id`=" . $project[0]['project_id'];
        $mysql = "`order_number`='" . $orderNumbers[0]['order_number'] . "'";
        $res = $DBSB->update_data($tblnameproject, $mysql, $condition);
        $msg1 .= "<td>Successed</td>";
        $msg1.="</tr>";
        $i++;
    }
} while($project[0]=$project[1]->fetch_assoc());
$msg1.="</tbody>";
$msg1.="</table>";
$msg1 .= "<p>Total Update : " . $i . "</p>";
$to = "Syamsul Arham<syamsul@mastersystem.co.id>";
$cc = "";
$bcc = "";
$from = "MSIZone<msizone@mastersystem.co.id>";
$reply = $from;
$subject = "[MSIZone] Sync. Service Budget with Order";

$msg="<table width='100%'";
$msg.="<tr><td width='30%' rowspan='3'></td><td style='width:40%; border: thin solid #dadada; padding:20px'>";
$msg.="<img src='https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png'>";
$msg.="<br/>";
$msg.="<p>Dear " . $to . "</p>";
$msg.="<p>Telah dilakukan syncronisasi data antara data order number dengan Service Budget.</p>"; 
$msg.="<p>" . $msg1 . "</p>";
$msg.="<p>Terimakasih,<br/>";
$msg.=$_SESSION['Microservices_UserEmail'] . "</p>";
$msg.="</td><td width='30%' rowspan='3'>";
$msg.="<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
$msg.="<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
$msg.="</table>";
echo $msg;

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

?>
