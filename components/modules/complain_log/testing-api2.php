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
$tasksUrl = 'https://sdpondemand.manageengine.com/api/v3/requests/145684000026745679/tasks';
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => $tasksUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Authorization: Zoho-oauthtoken ' . $accessToken,
        'Accept: application/vnd.manageengine.sdp.v3+json',
        'Content-Type: application/x-www-form-urlencoded',
    ),
));

$response = curl_exec($curl);
curl_close($curl);

$tasksData = json_decode($response, true);

?>

<!DOCTYPE html>
<html>

<head>
    <title>ManageEngine Tasks</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
</head>

<body>

    <table id="tasksTable" class="display">
        <thead>
            <tr>
                <th>Task ID</th>
                <th>Task Title</th>
                <th>Status</th>
                <th>Owner</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($tasksData['tasks'])) {
                foreach ($tasksData['tasks'] as $task) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($task['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($task['title']) . "</td>";
                    echo "<td>" . htmlspecialchars($task['status']['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($task['owner']['name']) . "</td>";
                    echo "</tr>";
                }
            }
            ?>
        </tbody>
    </table>

    <script>
        $(document).ready(function() {
            $('#tasksTable').DataTable();
        });
    </script>

</body>

</html>