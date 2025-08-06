<?php

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://cloudsso.cisco.com/as/token.oauth2?grant_type=client_credentials&client_id=c9456fbd45894c56848a0cda61c14c4a&client_secret=47947b97eee54CC286e7F7D2dbaa2ee5',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Authorization: OAuth Yzk0NTZmYmQ0NTg5NGM1Njg0OGEwY2RhNjFjMTRjNGE6NDc5NDdiOTdlZWU1NENDMjg2ZTdGN0QyZGJhYTJlZTU=',
        'Cookie: PF=G2Q89T8Om60cqBk39xMtuE'
    ),
));

$response = curl_exec($curl);

curl_close($curl);

$dataToken = json_decode($response, true);
$accessToken = $dataToken["access_token"];