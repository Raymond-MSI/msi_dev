<?php
require __DIR__ . '/vendor/autoload.php';
// include $_SERVER['DOCUMENT_ROOT'] . '/microservices/vendor/autoload.php';

/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */
function getCobaClient()
{
    $client = new Google_Client();

    $client->setApplicationName('DRIVE-RECRUITMENT');
    $client->setRedirectUri('http://localhost/microservices/');

    $client->setScopes(Google_Service_Drive::DRIVE);
    $client->setAuthConfig('CredentialsDrive.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    $tokenPath = 'DriveTokenProd.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    // If there is no previous token or it's expired.
    if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
            $authCode = trim(fgets(STDIN));

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            $client->setAccessToken($accessToken);

            // Check to see if there was an error.
            if (array_key_exists('error', $accessToken)) {
                throw new Exception(join(', ', $accessToken));
            }
        }
        // Save the token to a file.
        if (!file_exists(dirname($tokenPath))) {
            mkdir(dirname($tokenPath), 0700, true);
        }
        file_put_contents($tokenPath, json_encode($client->getAccessToken()));
    }
    return $client;
}

function listFilesInFolder($service, $folderId)
{
    $query = "'" . $folderId . "' in parents";
    $response = $service->files->listFiles(array(
        'q' => $query,
        'fields' => 'files(id, name, size, modifiedTime, description)'
    ));
    return $response->files;
}

// function deleteFileFromDrive($service, $fileId)
// {
//     try {
//         $service->files->delete($fileId);
//         echo "File deleted successfully.";
//     } catch (Exception $e) {
//         echo "An error occurred: " . $e->getMessage();
//     }
// }
// if (isset($_POST['delete_file_id'])) {
//     deleteFileFromDrive($driveService, $_POST['delete_file_id']);
// }

// Get the API client and construct the service object.
$client = getCobaClient();
