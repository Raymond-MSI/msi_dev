<?php
if ($_GET['tambah'] == 'manageengine') {
// $curl = curl_init();
// curl_setopt_array($curl, array(
//     CURLOPT_URL => 'https://accounts.zoho.com/oauth/v2/token',
//     CURLOPT_RETURNTRANSFER => true,
//     CURLOPT_ENCODING => '',
//     CURLOPT_MAXREDIRS => 10,
//     CURLOPT_TIMEOUT => 0,
//     CURLOPT_FOLLOWLOCATION => true,
//     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//     CURLOPT_CUSTOMREQUEST => 'POST',
//     CURLOPT_POSTFIELDS => 'refresh_token=1000.0da7170414877004191928a71b14765c.ffa8d3e4c1f6fe1e185a90eb0997f23b&grant_type=refresh_token&client_id=1000.X9H2U3XIDZQQG37A2F6DIRTY69WSHR&client_secret=38f588f59754323391c45c569b62f9f65348dbf441&redirect_uri=http%3A%2F%2Flocalhost%3A8080%2FAPI%2FSmartbonding-Manageengine%2Fsmartbonding.php&scope=SDPOnDemand.changes.ALL',
//     CURLOPT_HTTPHEADER => array(
//         'Content-Type: application/x-www-form-urlencoded',
//         'Cookie: stk=bc949dadeaab6a0f5053bb5abad8c3c9; JSESSIONID=6CDE430E64CED351AB2EB902D54F5575; _zcsr_tmp=f50201ef-56ab-4c85-81bb-5fe763dbd596; b266a5bf57=dcb92d0f99dd7421201f8dc746d54606; e188bc05fe=4d6e62173a764ac5410d1192f41034cd; iamcsr=f50201ef-56ab-4c85-81bb-5fe763dbd596'
//     ),
// ));

// $response = curl_exec($curl);
// curl_close($curl);
// $dataToken = json_decode($response, true);
// $accessToken = $dataToken["access_token"];

// $crl = curl_init();
// // $nowDate = $start_date;
// // $stamp = strtotime($nowDate);
// // $finalDate = $stamp * 1000;
// // $Date = $finish_date;
// // $stampdate = strtotime($Date);
// // $final_finish = $stampdate * 1000;
// $data = array(
//     "change" => array(
//         "template" => array(
//             "inactive" => false,
//             "name" => "Request Backup CR",
//             "id" => "145684000012969165"
//         ),
//         "description" => "",
//         "urgency" => array(
//             "name" => "Low",
//             "id" => "145684000000007923"
//         ),
//         "services" => [array(
//             "inactive" => false,
//             "name" => "Hardware",
//             "id" => "145684000001014121",
//             "sort_index" => 0
//         )],
//         "change_type" => array(
//             "color" => "#ffff66",
//             "pre_approved" => false,
//             "name" => "Minor",
//             "id" => "145684000000007955"
//         ),
//         "title" => "Request Barang Backup 230113-CR-MT-0182, KP TB002321I0066",
//         "change_owner" => null,
//         "assets" => null,
//         "configuration_items" => null,
//         "group" => array(
//             "deleted" => false,
//             "name" => "Service Desk",
//             "id" => "145684000000369105"
//         ),
//         "workflow" => array(
//             "name" => "General Change Workflow",
//             "id" => "145684000000083981"
//         ),
//         "change_manager" => null,
//         "impact" => array(
//             "name" => "Kurang dari 24 Users",
//             "id" => "145684000000008039"
//         ),
//         "retrospective" => false,
//         "priority" => array(
//             "color" => "#f40080",
//             "name" => "P4",
//             "id" => "145684000010971203"
//         ),
//         "site" => null,
//         "reason_for_change" => null,
//         "stage" => array(
//             "internal_name" => "submission",
//             "stage_index" => 1,
//             "name" => "Submission",
//             "id" => "145684000000083125"
//         ),
//         "udf_fields" => array(
//             "udf_date6" => null,
//             "udf_char16" => null,
//             "udf_char15" => null,
//             "udf_char14" => null,
//             "udf_char13" => null,
//             "udf_char19" => "BANK UOB INDONESIA",
//             "udf_char18" => null,
//             "udf_char17" => null,
//             "udf_char23" => null,
//             "udf_char22" => "Smart-1 225 GAIA",
//             "udf_char21" => "LR201407008378",
//             "udf_char20" => "Syntia Ayu Kartika",
//             "udf_char6" => "Software Checkpoint R77.20 had been in end-of-support on September 2019 and smart-1 225 will be in the end -of-support on December 2023. The checkpoint Software R80.40 also has a backward compatibility with R77.20 so that it can manage the gateway firewall with the OS software Checkpoint R77.20. ",
//             "udf_char7" => null,
//             "udf_char8" => null,
//             "udf_char9" => null,
//             "udf_char1" => null,
//             "udf_char24" => null,
//             "udf_char2" => null,
//             "udf_char3" => null,
//             "udf_char4" => null,
//             "udf_char5" => null,
//             "udf_char12" => null,
//             "udf_char11" => null,
//             "udf_char10" => null,
//             "udf_date1" => null,
//             "udf_date5" => null,
//             "udf_date4" => null,
//             "udf_date3" => null,
//             "udf_date2" => null
//         ),
//         "comment" => "Testing",
//         "risk" => array(
//             "name" => "Low",
//             "id" => "145684000000083080"
//         ),
//         "scheduled_start_time" => array(
//             "value" => "2023-01-20"
//         ),
//         "scheduled_end_time" => array(
//             "value" => "2023-01-20"
//         ),
//         "category" => null,
//         "subcategory" => null,
//         "status" => array(
//             "name" => "Requested",
//             "id" => "145684000000083260"
//         )
//     )
// );

// $postdata = json_encode($data);
// curl_setopt_array($crl, array(
//     CURLOPT_URL => "https://sdpondemand.manageengine.com/api/v3/changes",
//     CURLOPT_RETURNTRANSFER => true,
//     CURLOPT_ENCODING => '',
//     CURLOPT_MAXREDIRS => 10,
//     CURLOPT_TIMEOUT => 0,
//     CURLOPT_FOLLOWLOCATION => true,
//     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//     CURLOPT_CUSTOMREQUEST => 'POST',
//     CURLOPT_POSTFIELDS => "input_data=$postdata",
//     CURLOPT_HTTPHEADER => array(
//         'Accept: application/vnd.manageengine.sdp.v3+json',
//         'Content-Type: application/x-www-form-urlencoded',
//         "Authorization: Zoho-Oauthtoken $accessToken",
//         'Cookie: 6bc9ae5955=4582ce414bcf651e5e172ad635169c99; _zcsr_tmp=c92e3067-2f19-410c-b350-4eb704a48b3d; sdpcscook=c92e3067-2f19-410c-b350-4eb704a48b3d'
//     ),
// ));
// $hasil = curl_exec($crl);
// curl_close($crl);
// $feedback = json_decode($hasil, true);
// $comment = $feedback["change"]["display_id"]["display_value"];
// $result = preg_replace("/['']/", "", $hasil);

// if ($comment == null) {
//     '';
// } else {
include('func_alert.php');
$email = "chrisheryanda@mastersystem.co.id";
$from = "Chrisheryanda Eka Saputra" . "<" . $email . ">; ";
$to =  "Raka Putra Eshardiansyah <raka.putra@mastersystem.co.id>";
$cc = $from;
$bcc = "";
$reply = $from;
$subject = "[MSIZone] Request Barang Backup CR Number = 230113-CR-MT-0182";
$msg = "<table width='100%'";
$msg .= "<tr><td width='30%' rowspan='3'></td><td style='width:40%; border: thin solid #dadada; padding:20px'>";
$msg .= "<img src='https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png'>";
$msg .= "<br/>";
$msg .= "<p>Dear Team Service Desk</p>";
$msg .= "<p> Dengan ini saya ingin mengajukan request barang backup untuk :</p>";
$msg .= "<p>";
$msg .= "<table style='width:100%;'>";
$msg .= "<tr><td>CR Number</td><td>: </td><td> 230113-CR-MT-0182 </td></tr>";
$msg .= "<tr><td>ID Changes Manageengine</td><td>: </td><td>CH-748</td></tr>";
$msg .= "</table>";
$msg .= "</p>";
$msg .= "<p> Mohon untuk dapat diproses dan disiapkan. </p>";
$msg .= "<p>Terimakasih,<br/>";
$msg .= "Chrisheryanda Eka Saputra</p>";
$msg .= "</td><td width='30%' rowspan='3'>";
$msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id/index.php?mod=change_request'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
$msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
$msg .= "</table>";

$headers = "From: " . $from . "\r\n" .
    "Reply-To: " . $reply . "\r\n" .
    "Cc: " . $cc . "\r\n" .
    "Bcc: " . $bcc . "\r\n" .
    "MIME-Version: 1.0" . "\r\n" .
    "Content-Type: text/html; charset=UTF-8" . "\r\n" .
    "X-Mailer: PHP/" . phpversion();

$ALERT = new Alert();
if (!mail(
    $to,
    $subject,
    $msg,
    $headers
)) {
    echo
    "Email gagal terkirim pada jam " . date("d M Y G:i:s");
} else {
    echo
    "Email terkirim pada jam " . date("d M Y G:i:s");
}
// }
}
