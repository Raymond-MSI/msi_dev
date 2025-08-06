<?php
require 'vendor/autoload.php';

use League\OAuth2\Client\Provider\Google;

// Fungsi untuk mendapatkan objek client OAuth2
function getClient()
{
    $clientId = '147050116174-njkaouja0ago9nf8fckl85n04o2hl1o4.apps.googleusercontent.com';
    $clientSecret = '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';
    $redirectUri = 'http://localhost/cobaapi/microservices_lama';

    $provider = new Google([
        'clientId'     => $clientId,
        'clientSecret' => $clientSecret,
        'redirectUri'  => $redirectUri,
    ]);

    return $provider;
}

// Mendapatkan instance client OAuth2
$client = getClient();

// Mendapatkan token access
$accessToken = $client->getAccessToken();

if ($accessToken) {
    $email = 'malikwitama@gmail.com';
    $hostname = '{imap.gmail.com:993/imap/ssl}INBOX';

    // Opsi autentikasi IMAP menggunakan OAuth2
    $imapOptions = [
        'DISABLE_AUTHENTICATOR' => 'GSSAPI',
        'XOAUTH2' => "{imap.gmail.com:993/imap/ssl}user=$email\1auth=Bearer {$accessToken->getToken()}",
    ];

    // Buka koneksi IMAP
    $imap = imap_open($hostname, '', '', 0, 1, $imapOptions) or die('Cannot connect to Gmail: ' . imap_last_error());

    // Lakukan operasi lainnya dengan koneksi IMAP

    // Tutup koneksi IMAP
    imap_close($imap);
} else {
    echo 'Failed to get access token.';
}
