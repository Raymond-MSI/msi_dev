<?php

include $_SERVER['DOCUMENT_ROOT'] . '/microservices/gmail-imap.php';
echo "==========<br/>";
echo "Execution module: Alert email 2024<br/>";
echo "Started: " . date("d-M-Y G:i:s") . "<br/>";
echo "==========<br/>";
$time_start = microtime(true);

global $DBHCM;
$mdlname = "REQUIREMENT_HCM";
$DBHCM = get_conn($mdlname);

$service = getGmailService();

$user = 'me';
$messages = $service->users_messages->listUsersMessages($user);

// Start HTML table
echo '<table border="1">';
// Table header
echo '<tr><th>Subject</th><th>From</th><th>Received Date</th><th>Email Type</th><th>Additional Info</th><th>Body</th><th>Status</th><th>Catatan</th></tr>';

$pattern = '/\[TESTING\] Request Recruitment-(.*)/';

// Loop through each message
foreach ($messages->getMessages() as $message) {
    // Get the message ID
    $messageId = $message->getId();

    // Get the message details
    $messageDetails = $service->users_messages->get($user, $messageId);

    // Initialize variables for the details
    $subject = '';
    $senderName = '';
    $senderEmail = '';
    $receivedDate = '';
    $emailBody = '';
    $extractedPart = '';
    $status = '';
    $catatan = '';

    // Loop through the headers to extract details
    foreach ($messageDetails->getPayload()->getHeaders() as $header) {
        switch ($header->getName()) {
            case 'Subject':
                $subject = $header->getValue();
                break;
            case 'From':
                $sender = $header->getValue();
                if (preg_match('/(.*)<(.*)>/', $sender, $matches)) {
                    $senderName = trim($matches[1]);
                    $senderEmail = trim($matches[2]);
                } else {
                    $senderEmail = $sender;
                }
                break;
            case 'Date':
                $receivedDate = $header->getValue();
                break;
        }
    }

    // Check if the subject matches the pattern
    if (preg_match($pattern, $subject, $matches)) {
        $extractedPart = htmlspecialchars($matches[1]); // Extracted part

        // Get the body
        $parts = $messageDetails->getPayload()->getParts();
        foreach ($parts as $part) {
            if (
                $part['mimeType'] === 'text/plain' && $part['body'] && $part['body']['data']
            ) {
                $emailBody = $part['body']['data'];
                break;
            }
        }
        $emailBody = base64_decode(str_replace(['-', '_'], ['+', '/'], $emailBody));

        // Split the body into lines
        $lines = explode("\n", $emailBody);

        // Assign status and catatan
        if (count($lines) > 0) {
            $status = trim($lines[0]);
        }
        if (count($lines) > 1) {
            $catatan = trim($lines[1]);
        }

        // Output the row
        echo '<tr>';
        echo '<td>' . htmlspecialchars($subject) . '</td>';
        echo '<td>' . htmlspecialchars($senderName) . ' &lt;' . htmlspecialchars($senderEmail) . '&gt;' . '</td>';
        echo '<td>' . htmlspecialchars($receivedDate) . '</td>';
        echo '<td>Request Recruitment</td>';
        echo '<td>' . $extractedPart . '</td>';
        echo '<td>' . nl2br(htmlspecialchars($emailBody)) . '</td>';
        echo '<td>' . htmlspecialchars($status) . '</td>';
        echo '<td>' . htmlspecialchars($catatan) . '</td>';
        echo '</tr>';

        // Check database for specific conditions
        $query = "SELECT * FROM sa_hcm_requirement WHERE id_fpkb = '" . $extractedPart . "' AND status_request = 'Pending Approval'";
        $cekgm = $DBHCM->get_sqlV2($query);

        while ($view = mysqli_fetch_array($cekgm[1])) {
            $cekstatusrekrutmen = $view['status_rekrutmen'];
            $gm = htmlspecialchars($view['gm']);
            $gm_hcm = htmlspecialchars($view['gm_hcm']);
            $bod = htmlspecialchars($view['gm_bod']);

            if ($cekstatusrekrutmen == 'Penambahan') {
                if ($view['id_fpkb'] == $extractedPart && $view['status_gm'] == 'Pending') {
                    echo "Ada";
                } else {
                    echo "Tidak Ada";
                }
            }
        }
    }
}

// End HTML table
echo '</table>';

echo "==========<br/>";
echo "Execution module finished<br/>";
echo "Completed: " . date("d-M-Y G:i:s") . "<br/>";
echo "Execution time: " . (microtime(true) - $time_start) . " seconds<br/>";
