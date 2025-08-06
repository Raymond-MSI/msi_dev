<?php
date_default_timezone_set( "Asia/Jakarta" );

function strip_tags_content($text)
{
	return preg_replace('/<[^>]*>/', '', $text);
}

$hostname = "10.20.50.161";
$username = "ITAdmin";
$password = "P@ssw0rd.1";
$database = "sa_msiguide";

// $hostname = "localhost";
// $username = "root";
// $password = "";

$DBDOC = new mysqli($hostname, $username, $password, $database);

$mysql = 'SELECT `post_title`, `post_content`, `post_name`, `post_modified_gmt` FROM `wp_posts` WHERE `post_type`="post" AND `post_status`="publish" AND post_title LIKE "%Service Budget%" AND DATE(post_modified) = "' . date("Y-m-d", strtotime("-1 day")) . '" ORDER BY `post_modified_gmt` DESC LIMIT 0,1';
$res = mysqli_query( $DBDOC, $mysql ) or die($DBDOC->errno . "-" . "[open_db][mysqli_query][" . $mysql . "] - " . $DBDOC->error . "<br/>");
$row = mysqli_fetch_assoc( $res );
$total = mysqli_num_rows( $res );

if($total>0) {
	$contents1 = explode("/p>", $row['post_content']); 
	$contents2 = strip_tags_content($contents1[0]); 
	$contents = explode("<", $contents2);
	
	$title0 = strtolower($row['post_title']);
	$permalink0 = str_replace("– ","",$title0);
	$permalink0 = str_replace(".","-", $permalink0);
	$permalink = str_replace(" ","-",$permalink0);
	
	$msg='<table width="100%">';
	$msg.='  <tr><td style="width:30%" rowspan="5"></td><td style="width:40%; border:solid thin #dadada; padding:20px"><img src="https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png"></td><td style="width:30%" rowspan="5"></td></tr>';
	$msg.='  <tr><td style="border:solid thin #dadada; padding:20px"><strong>News Update</strong></td></tr>';
	$msg.='  <tr><td style="border:solid thin #dadada; padding:20px">';
	$title = explode(":", $row['post_title']);
	$msg.='<p><strong>' . $title[1] . '</strong></p>';
	$msg.='<p>' . $contents[0] . '</p>';
	$msg.='<p><a href="https://msizone.mastersystem.co.id/msiguide/' . $row['post_name'] . '" target="_blank">Read more...</a></p>';
	$msg.='</td></tr>';
	$msg.='    <tr><td style="padding:20px; border:thin solid #dadada"><table width="100%"><tr><td><a href="https://msizone.mastersystem.co.id">MSIZone</a></td><td style="text-align:right"><a href="https://msizone.mastersystem.co.id/msiguide/">MSIGuide</a></td></tr></table></td></tr>';
	$msg.='    <tr><td style="font-size:10px; padding-left:20px;border: thin solid #dadada">Dikirim secara otomatis oleh sistem MSIZone.<br/>Jangan mereply email ini.</td></tr>';
	$msg.='</table>';
	
	$database = "sa_md_hcm";
	$DBHCM = new mysqli($hostname, $username, $password, $database);
	
	$mysql = 'SELECT employee_name, employee_email FROM sa_view_employees WHERE organization_name="Solution Architect" OR organization_name LIKE "Account Management%"';
	$res = mysqli_query( $DBHCM, $mysql ) or die($DBHCM->errno . "-" . "[open_db][mysqli_query][" . $mysql . "] - " . $DBHCM->error . "<br/>");
	$row = mysqli_fetch_assoc( $res );
	$total = mysqli_num_rows( $res );
	
	$to = '';
	$bcc="";
	do {
		$bcc.= str_replace(",", "" , $row['employee_name']) . "<" . $row['employee_email'] . ">,";
	} while($row=$res->fetch_assoc());
	
	$cc = "";
	$bcc .= "syamsul Arham<syamsul@mastersystem.co.id>,Raymon Citra<raymon@mastersystem.co.id>, Fortuna Arumsari<fortuna@mastersystem.co.id>, Lukman Susanto<lukman.susanto@mastersystem.co.id>, Riswan Fadhilah<riswan.fadhilah@mastersystem.co.id>";
	$from = "MSIZone<msizone@mastersystem.co.id>";
	$subject = "[MSIZone] News Update Service Budget";
	
	$headers = "From: " . $from . "\r\n" .
	"Cc: " . $cc . "\r\n" .
	"Bcc: " . $bcc . "\r\n" .
	"MIME-Version: 1.0" . "\r\n" .
	"Content-Type: text/html; charset=UTF-8" . "\r\n" .
	"X-Mailer: PHP/" . phpversion();
	
	if(mail($to, $subject, $msg, $headers)) {
		echo "Email terkirim pada jam " . date("d M Y G:i:s");
	} else {
		echo "Email gagal terkirim pada jam " . date("d M Y G:i:s");
	}
}
?>