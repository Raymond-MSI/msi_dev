<?php
require __DIR__ . '/vendor/autoload.php';

/**
 * Returns an authorized Gmail service object.
 * @return Google_Service_Gmail the authorized Gmail service object
 */
function getGmailService()
{
    $client = new Google_Client();

    $client->setApplicationName('GMAIL-RECRUITMENT');
    $client->setScopes(Google_Service_Gmail::GMAIL_READONLY); // Use appropriate Gmail scope
    $client->setAuthConfig('CredentialsIMAPNEWW.json'); // Use Gmail credentials file
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    // Load previously authorized token from a file, if it exists.
    $tokenPath = 'TokenGmailProd.json'; // Change to Gmail token path
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    // If there is no previous token or it's expired, get a new one.
    if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else {
            // Redirect user to OAuth flow.
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

    // Return an authorized Gmail service object.
    return new Google_Service_Gmail($client);
}

// Example: Get Gmail service object
$service = getGmailService();

// $user = 'me';
// $messages = $service->users_messages->listUsersMessages($user);

// // Loop through each message
// foreach ($messages->getMessages() as $message) {
//     // Get the message ID
//     $messageId = $message->getId();

//     // Get the message details
//     $messageDetails = $service->users_messages->get($user, $messageId);

//     // Get the subject
//     $subject = '';
//     foreach ($messageDetails->getPayload()->getHeaders() as $header) {
//         if ($header->getName() === 'Subject') {
//             $subject = $header->getValue();
//             break;
//         }
//     }

//     // Print the subject
//     echo "Subject: $subject\n";
// }
