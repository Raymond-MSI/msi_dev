<?php
$refreshToken = '1000.8b498025c3b0f052c0a90738c62fed94.af94a2a626463b3b05bb7302c6e114b7';
$clientId = '1000.XM8LNT6SZ4X6X2QXLSVHG4SUGUSKCG';
$clientSecret = '837072371e410da75f64df6f82527ff15f80476abd';
$redirectUri = 'http://localhost:8080/API/Smartbonding-Manageengine/smartbonding.php';
$scope = 'SDPOnDemand.requests.ALL';

// Mengambil access token menggunakan refresh token
$tokenUrl = "https://accounts.zoho.com/oauth/v2/token";
$tokenPostData = http_build_query([
    'refresh_token' => $refreshToken,
    'grant_type' => 'refresh_token',
    'client_id' => $clientId,
    'client_secret' => $clientSecret,
    'redirect_uri' => $redirectUri,
    'scope' => $scope,
]);

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => $tokenUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $tokenPostData,
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/x-www-form-urlencoded'
    ),
));

$response = curl_exec($curl);
curl_close($curl);

$responseData = json_decode($response, true);

if (isset($responseData['access_token'])) {
    $accessToken = $responseData['access_token'];
} else {
    die("Failed to get access token\n");
}

// Mengambil data tasks
$ticket = '12345'; // Sesuaikan dengan ID ticket yang diinginkan
$inputDataUrl = 'https://sdpondemand.manageengine.com/api/v3/requests/145684000026402719';

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => $inputDataUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Accept: application/vnd.manageengine.sdp.v3+json',
        'Content-Type: application/x-www-form-urlencoded',
        'Authorization: Zoho-oauthtoken ' . $accessToken,
    ),
));

$response = curl_exec($curl);
curl_close($curl);

$responseData = json_decode($response, true);

// Menampilkan hasil dengan print_r
echo "<pre>";
print_r($responseData);
echo "</pre>";
