<?php
if ((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $released = "1722398279";
    $author = 'Syamsul Arham';
} else {
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
    $inputDataUrl = 'https://sdpondemand.manageengine.com/api/v3/requests?input_data=%7B%22list_info%22%3A%20%7B%22row_count%22%3A%201000%2C%20%22start_index%22%3A%201%2C%20%22sort_field%22%3A%20%22created_time%22%2C%22sort_order%22%3A%20%22desc%22%7D%7D';
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

?>

    <!DOCTYPE html>
    <html>

    <head>
        <title>ManageEngine Requests</title>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
        <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    </head>

    <body>
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Manage Engine</h6>
                </div>
                <div class="card-body">
                    <table id="requestsTable" class="display">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Subject</th>
                                <th>Ticket</th>
                                <th>Request Date Time</th>
                                <th>Status</th>
                                <th>Request Name</th>
                                <th>View Task</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($requestsData['requests'])) {
                                foreach ($requestsData['requests'] as $request) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($request['id']) . "</td>";
                                    echo "<td>" . htmlspecialchars($request['subject']) . "</td>";
                                    echo "<td>" . htmlspecialchars($request['display_id']) . "</td>";
                                    echo "<td>" . htmlspecialchars($request['created_time']['display_value']) . "</td>";
                                    echo "<td>" . htmlspecialchars($request['status']['name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($request['requester']['name']) . "</td>";
                                    echo "<td><button class='viewbtn' data-id='" . htmlspecialchars($request['id']) . "'>View Task</button></td>";
                                    echo "</tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                $('#requestsTable').DataTable();

                $(document).on('click', '.viewbtn', function() {
                    var id = $(this).data('id');
                    window.location.href = 'index.php?mod=dashboard_manage_engine&sub=cobageser&id=' + id;
                });
            });
        </script>
    </body>
<?php
}
?>