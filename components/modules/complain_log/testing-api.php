<?php
$today = date('Y-m-d');
$yesterday = date('Y-m-d', strtotime('-1 day'));
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

// Mengambil data requests dengan input_data
$data = [
    "list_info" => [
        "row_count" => 50,
        // "start_index" => 1,
        "sort_field" => "last_updated_time",
        "sort_order" => "desc",
        "search_criteria" => [
            "field" => "updated_at",
            "condition" => "between",
            "value" => [$yesterday]
        ]
    ]
];

// Mengubah array menjadi JSON
$jsonData = json_encode($data);

// Menyiapkan URL dengan data ter-encode
$inputDataUrl = 'https://sdpondemand.manageengine.com/api/v3/requests/?input_data=' . urlencode($jsonData);

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => $inputDataUrl,
    CURLOPT_RETURNTRANSFER => true,
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

$requestsData = json_decode($response, true);

if (isset($requestsData['requests'])) {
    $requests = $requestsData['requests'];
} else {
    die("Failed to get requests data\n");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ManageEngine Requests Data</title>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include DataTables CSS and JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
</head>

<body>

    <h2>ManageEngine Requests Data</h2>
    <table id="requestsTable" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Request Title</th>
                <th>Status</th>
                <th>Ticket</th>
                <!-- Tambahkan kolom lainnya sesuai kebutuhan -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($requests as $request): ?>
                <tr>
                    <td><?php echo htmlspecialchars($request['id']); ?></td>
                    <td><?php echo htmlspecialchars($request['subject']); ?></td>
                    <td><?php echo htmlspecialchars($request['status']['name']); ?></td>
                    <td><?php echo htmlspecialchars($request['display_id']); ?></td>
                    <!-- Tambahkan data lainnya sesuai kebutuhan -->
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        $(document).ready(function() {
            $('#requestsTable').DataTable();
        });
    </script>

</body>

</html>