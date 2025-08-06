<?php
$ticket = $_GET['id'];
?>
<form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
    <div class="row">
        <div class="row">
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
            $tasksUrl = 'https://sdpondemand.manageengine.com/api/v3/requests/' . $ticket . '/tasks';
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
            <div class="table-responsive">
                <table class="table" id="tblApproval">
                    <thead>
                        <tr>

                            <th class="align-middle">TASK TITLE</th>
                            <th class="align-middle text-center">Created By</th>
                            <th class="align-middle text-center">CREATED DATE TIME</th>
                            <th class="align-middle text-center col-sm-1">SCHEDULED START TIME</th>
                            <th class="align-middle text-center col-sm-1">SCHEDULED END TIME</th>
                            <th class="align-middle text-center col-sm-1">ACTUAL START TIME</th>
                            <th class="align-middle text-center col-sm-1">ACTUAL END TIME</th>
                            <th class="align-middle">DESCRIPTION</th>
                            <th class="align-middle">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($tasksData['tasks'])) {
                            foreach ($tasksData['tasks'] as $task) { ?>

                                <tr>
                                    <td>
                                        <?php echo htmlspecialchars($task['request']['subject']); ?>
                                        <span style="font-size:12px">[Subject]<br />
                                            Task&nbsp;Title&nbsp;:&nbsp;<?php echo htmlspecialchars($task['title']); ?>&nbsp;|&nbsp;
                                            Group&nbsp;:&nbsp;<?php echo htmlspecialchars($task['group']['name']); ?>&nbsp;|&nbsp;
                                            Task&nbsp;Type&nbsp;:&nbsp;<?php echo htmlspecialchars($task['task_type']['name'] ?? '-'); ?>&nbsp;|&nbsp;
                                            Owner&nbsp;:&nbsp;<?php echo htmlspecialchars($task['owner']['name']); ?>&nbsp;|&nbsp;
                                        </span>
                                    </td>
                                    <td class="text-center"><?php echo htmlspecialchars($task['created_by']['name']); ?></td>
                                    <td class="text-center"><?php echo htmlspecialchars($task['created_date']['display_value']); ?></td>
                                    <td class="text-center"><?php echo htmlspecialchars($task['scheduled_start_time']['display_value']); ?></td>
                                    <td class="text-center"><?php echo htmlspecialchars($task['scheduled_end_time']['display_value']); ?></td>
                                    <td class="text-center">
                                        <?php echo htmlspecialchars($task['actual_start_time']['display_value'] ?? '-'); ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo htmlspecialchars($task['actual_end_time']['display_value'] ?? '-'); ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($task['description']); ?></td>
                                    <td><?php echo htmlspecialchars($task['status']['name']); ?></td>
                                </tr>
                            <?php
                            }
                        } else { ?>
                            <tr>
                                <td colspan="14">Data not ready</td>
                            </tr>
                        <?php
                        }
                        ?>

                    </tbody>
                    <tfood>
                        <tr>

                            <th class="align-middle">TASK TITLE</th>
                            <th class="align-middle text-center">REQUEST</th>
                            <th class="align-middle text-center">CREATED DATE TIME</th>
                            <th class="align-middle text-center col-sm-1">SCHEDULED START TIME</th>
                            <th class="align-middle text-center col-sm-1">SCHEDULED END TIME</th>
                            <th class="align-middle text-center col-sm-1">ACTUAL START TIME</th>
                            <th class="align-middle text-center col-sm-1">ACTUAL END TIME</th>
                            <th class="align-middle">DESCRIPTION</th>
                            <th class="align-middle">STATUS</th>
                        </tr>
                    </tfood>
                </table>
            </div>

            </html>
        </div>

        <input type="submit" class="btn btn-secondary" name="cancel" value="Cancel">
        <?php if (isset($_GET['act']) && $_GET['act'] == 'edit') { ?>
            <input type="submit" class="btn btn-primary" name="save" value="Save">
        <?php } elseif (isset($_GET['act']) && $_GET['act'] == 'add') { ?>
            <input type="submit" class="btn btn-primary" name="add" value="Save">
        <?php } ?>
</form>