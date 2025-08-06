<?php
include 'AuthToken.php';
session_start();

$data = array(
    "Calls" => array(
        "CustCallID" => "PartnerMSITest1",
        "Remarks" => "some ticket updates. All notes / text updates go in here"
    ),
    "CallStates" => array(
        "ShortName" => "Update"
    ),
    "Priorities" => array(
        "ShortName" => "Escalated"
    )
);

$postdata = json_encode($data);
echo "$postdata";
$curl = curl_init();
$headers = array(
    "Content-Type:application/json",
    "Authorization: Bearer $accessToken"
);

curl_setopt($curl, CURLOPT_URL, "https://stage.sbnprd.xylem.cisco.com/sb-partner-oauth-proxy-api/rest/v1/push/call");
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
$result = curl_exec($curl);
curl_close($curl);
print_r($result);
