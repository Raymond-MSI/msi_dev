<?php
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://accounts.zoho.com/oauth/v2/token',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => 'refresh_token=1000.ab116c506ce81cc77713ca9d97f4952e.6209fb588db1bac8697fdf0962969421&grant_type=refresh_token&client_id=1000.X9H2U3XIDZQQG37A2F6DIRTY69WSHR&client_secret=38f588f59754323391c45c569b62f9f65348dbf441&redirect_uri=http%3A%2F%2Flocalhost%3A8080%2FAPI%2Foauth2callback.php&scope=SDPOnDemand.requests.ALL',
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/x-www-form-urlencoded',
        'Cookie: JSESSIONID=6DBA5723B5F74B0D2ED605C5BCD4760C; _zcsr_tmp=fd10a5b4-b89f-4832-b109-f2216a062fa2; b266a5bf57=a711b6da0e6cbadb5e254290f114a026; iamcsr=fd10a5b4-b89f-4832-b109-f2216a062fa2'
    ),
));

$response = curl_exec($curl);

curl_close($curl);
$dataToken = json_decode($response, true);
$accessTokenrequest = $dataToken["access_token"];
