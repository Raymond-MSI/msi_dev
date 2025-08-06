<?php
require 'vendor/autoload.php';

use Google\Client;

$client = new Client();
$client->setAuthConfig('c:\xampp\htdocs\microservices\credentialsimapbaru.json');
$client->setRedirectUri('http://localhost');
$client->addScope('https://mail.google.com/');
$client->setAccessType('offline');
$client->setPrompt('consent');

$authUrl = $client->createAuthUrl();

echo "Open the following link in your browser:\n$authUrl\n";

// After obtaining the authorization code, use it to get the access token
if (isset($_GET['code'])) {
    $code = $_GET['code'];
    $accessToken = $client->fetchAccessTokenWithAuthCode($code);
    file_put_contents('c:\xampp\htdocs\microservices\tokencobaimap.json', json_encode($accessToken));
    echo "Access token saved to 'c:\xampp\htdocs\microservices\tokencobaimap.json'";
}
