<?php
echo "==========";
echo "Execution module : mod_wrike_integrate/form_wrike_integrate.";
echo "Started : " . date("d-M-Y G:i:s");
echo "==========<br/>";
$time_start = microtime(true);

$_SESSION['Microservices_UserEmail'] = 'syamsul@mastersystem.co.id';

include_once("components/classes/func_crontab.php");
include_once("components/classes/func_helper.php");
$descErr = "Completed";
$DBCRON = get_conn("CRONTAB");
$res = $DBCRON->beginning();


$_SESSION['Microservices_UserEmail']="MSIZone<MSIZone@mastersystem.co.id>";
include( "components/classes/func_service_budget.php");
$mdlname = "SERVICE_BUDGET";
$DBSB = get_conn($mdlname);
$mdlname = "NAVISION";
$DBNAVISION = get_conn($mdlname);
$DBHCM = get_conn("HCM");

$tblnameproject = "trx_project_list";
// $condition = "(`order_number` IS NULL OR `amount_idr`=0) AND status='acknowledge'";
// $condition = "`order_number` IS NULL OR `so_number` IS NULL OR `amount_idr`=0";
// $condition = "`so_number` IS NULL OR `po_number` IS NULL OR `amount_idr` IS NULL OR `amount_usd` IS NULL";
$condition = "`so_number` IS NULL OR `so_number` = '' OR `po_number` IS NULL OR `po_number` = '' OR (`amount_idr` IS NULL AND `amount_usd` IS NULL) OR (`amount_idr` = 0 AND `amount_usd` = 0)";
$order = "";
// $limit = 0;
// $project = $DBSB->get_data($tblnameproject, $condition, $order, 0, $limit);
$project = $DBSB->get_data($tblnameproject, $condition);
// $msg0 = "<p>Target Data : " . $project[2] . "</p>";
$msg0 = "<p style='color:red'>Bila ada perbedaan amount antara CRM dan SBF agar bisa dikoordinasikan dengan pihak terkait untuk dilakukan perbaikan. Perbaikan bisa dilakukan dari sisi CRM maupun SBF.</p>";
$msg0.= "<table class='table' style='width:100%' border='1'>";
$msg0.="<thead><tr><th>Project Code</th><th>Update SO Number</th><th>Order Number</th><th>Update PO Number</th><th>Update Amount</th><th>Currency Code</th><th>Status</th></tr></thead>";
$msg0.="<tbody>";
$msg2 = "";
$msg3 = "";
$tblname = "mst_order_number";
$i=0;
$xxx = false;
echo $project[2];
do {
    $j=0;
    $msg1 = $msg0;
    $msg1x = "";
    // $condition = "`so_number` = '" . $project[0]['so_number'] . "' AND `order_number` IS NOT NULL";
    $condition = "`order_number` = '" . $project[0]['order_number'] . "'";
    $orderNumbers = $DBNAVISION->get_data($tblname, $condition);
    if($orderNumbers[2]>0) {
        if($orderNumbers[0]['so_number']!=$project[0]['so_number'] || $orderNumbers[0]['po_number']!=$project[0]['po_number'] || $orderNumbers[0]['amount']!=$project[0]['amount_idr'])
        {
            $amount = $orderNumbers[0]['currency_code']=="USD" ? "USD." . $orderNumbers[0]['amount'] : "IDR." . $orderNumbers[0]['amount'];
            $msg1x .= "
                <tr>
                    <td>" . $project[0]['project_code'] . "</td>
                    <td>" . $orderNumbers[0]['so_number'] . ($orderNumbers[0]['so_number']!=$project[0]['so_number'] ? "<span style='color:red'>*</span>" : "") . "</td>
                    <td>" . $orderNumbers[0]['order_number'] . "</td>
                    <td>" . $orderNumbers[0]['po_number'] . ($orderNumbers[0]['po_number']!=$project[0]['po_number'] ? "<span style='color:red'>*</span>" : "") . "</td>
                    <td style='text-align:right'><span style='color:red'>" . 
                        number_format(($orderNumbers[0]['currency_code']=="USD" ? $project[0]['amount_usd'] : $project[0]['amount_idr']),2) . "</span> (CRM)<br/><span style='color:red'>" .
                        number_format($orderNumbers[0]['amount'],2) . "</span> (SBF)
                    </td>
                    <td>" . ($orderNumbers[0]['currency_code']=="USD" ? "USD" : "IDR") . "</td>";
                        $condition = "`project_id`=" . $project[0]['project_id'];
                        // $mysql = "`order_number`='" . $orderNumbers[0]['order_number'] . "'";
                        // $mysql = sprintf("`so_number`=%s, `po_number`=%s, `amount_idr`=%s, amount_usd=%s, modified_by=%s",
                        //     GetSQLValueString($orderNumbers[0]['so_number'], "text"),
                        //     GetSQLValueString($orderNumbers[0]['po_number'], "text"),
                        //     GetSQLValueString(($orderNumbers[0]['currency_code'] != 'USD' ? $orderNumbers[0]['amount'] : 0), "double"),
                        //     GetSQLValueString(($orderNumbers[0]['currency_code'] == 'USD' ? $orderNumbers[0]['amount'] : 0), "double"),
                        //     GetSQLValueString("MSIZone<msizone@mastersystem.co.id", "text")
                        // );
                        // 08 September 2023
                        // Amount ditake-out dari disini.
                        $mysql = sprintf("`so_number`=%s, `po_number`=%s, amount_idr=%s, modified_by=%s",
                            GetSQLValueString($orderNumbers[0]['so_number'], "text"),
                            GetSQLValueString($orderNumbers[0]['po_number'], "text"),
                            GetSQLValueString($orderNumbers[0]['amount'], "text"),
                            GetSQLValueString("MSIZone<msizone@mastersystem.co.id", "text")
                        );
                        $res = $DBSB->update_data($tblnameproject, $mysql, $condition);
                        $msg1x .= "
                    <td>Successed</td>";
                        $msg3 .= "Project Code : " . $project[0]['project_code'] . ", SO Number : " . $project[0]['so_number'] . ($orderNumbers[0]['so_number']!=$project[0]['so_number'] ? "*" : "") . ", Order Number : " . $orderNumbers[0]['order_number'] . ", PO Number : " . $orderNumbers[0]['po_number'] . ($orderNumbers[0]['po_number']!=$project[0]['po_number'] ? "*" : "") . ", Amount : " . $orderNumbers[0]['amount'] . ($orderNumbers[0]['amount']!=$project[0]['amount_idr'] ? "*" : "") . ", " . ($orderNumbers[0]['currency_code']=="USD" ? "USD" : "IDR") . ", Status : Successed" . "\n";
                        $xxx = true;
                        $msg1x.="
                </tr>";
                $msg1 = $msg0 . $msg1x;
                $msg2 .= $msg1x;
            $i++;
            $j++;
        }
    }
    $to = "";
    $to1 = "";
    $to2 = "";
    $to3 = "";
    if($j>0)
    {
        $to1 = $DBHCM->get_email(trim($project[0]['create_by'])) . "; ";
        $to2 = $DBHCM->get_email(trim($project[0]['modified_by'])) . "; ";
        $mysql = sprintf(
            "SELECT `employee_name`, `email` 
            FROM `sa_mst_employees` 
            WHERE `employee_name` LIKE %s",
            GetSQLValueString(trim($project[0]['sales_name']), "text")
        );
        $res = $DBHCM->get_sql($mysql);
        if($res[2]>0)
        {
            $to3 = $res[0]['employee_name'] . "<" . $res[0]['email'] . ">; ";
        }
        $to4 = "Syamsul Arham<syamsul@mastersystem.co.id>";
        $to = $to1 . $to2 . $to3 . $to4;
        $subject = "[MSIZone] Sync. Service Budget with Order Number " . $orderNumbers[0]['order_number'];
        get_email_msg($msg1);
        send_email_SBF($subject);
    }
} while($project[0]=$project[1]->fetch_assoc());

function get_email_msg($msg1)
{
    global $to, $msg, $cc, $bcc, $reply;
    $msg1.="</tbody>";
    $msg1.="</table>";
    // $msg1 .= "<p>Total Update : " . $j . "</p>";
    $msg1 .= "<p><span style='red'>*</span> Data yang diupdate.</p>";

    $from = "MSIZone<msizone@mastersystem.co.id>";
    $cc = "";
    $bcc = "";
    $reply = $from;

    $msg="<table width='100%'";
    $msg.="<tr><td width='30%' rowspan='3'></td><td style='width:40%; border: thin solid #dadada; padding:20px'>";
    $msg.="<img src='https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png'>";
    $msg.="<br/>";
    $msg.="<p>Dear " . $to . "</p>";
    $msg.="<p>Email ini merupakan pemberitahuan bahwa telah dilakukan update data di Service Budget terhadap SO Number dan PO Number dikarenakan sebelumnya datanya masih kosong.</p>"; 
    $msg.="<p>" . $msg1 . "</p>";
    $msg.="<p>Terimakasih,<br/>";
    $msg.=$_SESSION['Microservices_UserEmail'] . "</p>";
    $msg.="</td><td width='30%' rowspan='3'>";
    $msg.="<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
    $msg.="<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
    $msg.="</table>";
}


function send_email_SBF($subject)
{
    global $from, $to, $reply, $cc, $bcc, $msg, $subject, $xxx;
    $headers = "From: " . $from . "\r\n" .
    "Reply-To: " . $reply . "\r\n" .
    "Cc: " . $cc . "\r\n" .
    "Bcc: " . $bcc . "\r\n" .
    "MIME-Version: 1.0" . "\r\n" .
    "Content-Type: text/html; charset=UTF-8" . "\r\n" .
    "X-Mailer: PHP/" . phpversion();

    // echo $subject . "<br/>";
    echo $msg;

    if($xxx==true)
    {
        $ALERT=new Alert();
        if(!mail($to, $subject, $msg, $headers))
        {
        echo $ALERT->email_not_send();
        } else
        {
        echo $ALERT->email_send();
        }
    }
}

if($i>0)
{
    $to = "Syamsul Arham<syamsul@mastersystem.co.id>";
    $subject = "[MSIZone] Sync. Service Budget with Order";
    $msg = $msg0 . $msg2;
    get_email_msg($msg);
    send_email_SBF($subject);
}

$time_end = microtime(true);
$time = $time_end - $time_start;

echo "<br/>==========";
echo "Finished : " . date("d-M-Y G:i:s");
echo "The time used to run this module $time seconds";
echo "==========";

$log_file = log_message('SYNC_ORDER_NUMBER', [
    'Sync Order Number' => $msg3, 
]);
    
    $DBCRON->ending($descErr, $log_file);
?>