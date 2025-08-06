<?php 
$to = $data_msg['to'];
// $to = "Syamsul Arham<syamsul@mastersystem.co.id>";
if(isset($_SESSION['Microservices_UserEmail']))
{
    $from = $_SESSION['Microservices_UserName'] . "<" . $_SESSION['Microservices_UserEmail'] . ">";
} else
{
    $from = "MSIZone<msizone@mastersystem.co.id>";
}
$reply = $data_msg['reply'];
$cc = $data_msg['cc'];
$bcc = $data_msg['bcc'];
$subject = $data_msg['subject'];

$msg = '<table width="100%">';
$msg .= '    <tr>';
$msg .= '        <td width="20%"></td>';
$msg .= '        <td width="60%" style="padding:20px;" colspan="2"><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></td>';
$msg .= '        <td></td>';
$msg .= '    </tr>';
$msg .= '    <tr>';
$msg .= '        <td></td>';
$msg .= '        <td style="padding-top: 10px; padding-bottom:10px;" colspan="2">';
$msg .=              $data_msg['message'];
$msg .= '        </td>';
$msg .= '        <td></td>';
$msg .= '    </tr>';
$msg .= '    <tr>';
$msg .= '        <td></td>';
$msg .= '        <td width="30%" style="padding:5px; border-top: 1px solid; text-align: center;">';
$msg .= '            <a href="https://msizone.mastersystem.co.id" target="_blank">MSIZone</a>';
$msg .= '        </td>';
$msg .= '        <td width="30%" style="padding:5px; border-top: 1px solid; text-align: center;">';
$msg .= '           <a href="https://msizone.mastersystem.co.id/msiguide" target="_blank">MSIGuide</a>';
$msg .= '        </td>';
$msg .= '        <td></td>';
$msg .= '    </tr>';
$msg .= '</table>';

// echo "From : " . $from . "<br/>";
// echo "To : " . $to . "<br/>";
// echo "Reply : " . $reply . "<br/>";
// echo "CC : " . $cc . "<br/>";
// echo "BCC : " . $bcc . "<br/>";
// echo "Sucject : " . $subject . "<br/>"; 
// echo $msg;

$headers = "From: " . $from . "\r\n" .
"Reply-To: " . $reply . "\r\n" .
"Cc: " . $cc . "\r\n" .
"Bcc: " . $bcc . "\r\n" .
"MIME-Version: 1.0" . "\r\n" .
"Content-Type: text/html; charset=UTF-8" . "\r\n" .
"X-Mailer: PHP/" . phpversion();

$ALERT=new Alert();
if(!mail($to, $subject, $msg, $headers)) {
    echo $ALERT->email_not_send();
} else
{
    echo $ALERT->email_send();
}

?>
<br/><br/><br/>