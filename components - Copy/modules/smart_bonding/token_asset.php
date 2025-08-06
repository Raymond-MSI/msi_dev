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
    CURLOPT_POSTFIELDS => 'refresh_token=1000.15fbac42ad6e153ead2b9f9a47e8b22f.9f5c5fac334fb3699b5a85efb84c4ea4&grant_type=refresh_token&client_id=1000.X9H2U3XIDZQQG37A2F6DIRTY69WSHR&client_secret=38f588f59754323391c45c569b62f9f65348dbf441&redirect_uri=http%3A%2F%2Flocalhost%3A8080%2FAPI%2FSmartbonding-Manageengine%2Fsmartbonding.php&scope=SDPOnDemand.assets.ALL',
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/x-www-form-urlencoded',
        'Cookie: _zcsr_tmp=f3c68615-bc90-4c30-be5c-f17c7787aad8; b266a5bf57=57c7a14afabcac9a0b9dfc64b3542b70; iamcsr=f3c68615-bc90-4c30-be5c-f17c7787aad8'
    ),
));

$response = curl_exec($curl);
curl_close($curl);
$dataToken = json_decode($response, true);
$accessTokenasset = $dataToken["access_token"];
