<?php
include 'GenerateToken.php';
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://sb.xylem.cisco.com/sb-tspcodes-exp-api/api/v1/xylem/tspcodes',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        "Authorization: Bearer $accessToken"
    ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
