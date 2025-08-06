<?php
if ($_GET['act'] == 'edit') {
    global $DBManageEngine;
    $condition = "id_request=" . $_GET['id_request'];
    $data = $DBManageEngine->get_data($tblname, $condition);
    $ddata = $data[0];
    $qdata = $data[1];
    $tdata = $data[2];
    $ticket = $_GET['id_request'];
    $subject = $DBManageEngine->get_sqlV2("SELECT * FROM sa_trx_manage_engine WHERE id_request = '" . $ticket . "'");
    $subject1 = $subject[0];
?>
    <form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
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
        $inputDataUrl = 'https://sdpondemand.manageengine.com/api/v3/requests/' . $ticket;

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

        if (isset($responseData['request'])) { ?>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active text-body" id="editreq-tab" data-bs-toggle="tab" data-bs-target="#editreq" type="button" role="tab" aria-controls="editreq" aria-selected="true">General</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-body" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab" aria-controls="history" aria-selected="false">History</button>
                </li>
            </ul>
            <form method="post" action="index.php?mod=<?php echo $_GET['mod']; ?>">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="editreq" role="tabpanel" aria-labelledby="editreq-tab">
                        <div class="row mb-3" hidden><input type="text" class="form-control form-control-sm" id="request" name="request" value="<?php echo htmlspecialchars($subject1['request']) ?>" readonly>
                        </div>
                        <?php if (strpos($subject1['subject'], 'INTERNAL_') !== false) {
                            echo "<div>Subject dengan INTERNAL_SW: " . htmlspecialchars($subject1['subject']) . "</div>"; ?>
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="card shadow mb-1">
                                            <div class="card-header fw-bold">Request Details</div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Requester Name</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="request_name" name="request_name" value="<?php echo htmlspecialchars($responseData['request']['requester']['name'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Site</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="site" name="site" value="<?php echo htmlspecialchars($responseData['request']['site']['name'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card shadow mb-1">
                                            <div class="card-header fw-bold">Requester</div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="status" name="status" value="<?php echo htmlspecialchars($responseData['request']['status']['name'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Mode</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="mode" name="mode" value="<?php echo htmlspecialchars($responseData['request']['mode']['internal_name'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Level</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="level" name="level" value="<?php echo htmlspecialchars($responseData['request']['level']['name'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Request Type</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="request_type" name="request_type" value="<?php echo htmlspecialchars($responseData['request']['request_type']['name'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Group</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="group" name="group" value="<?php echo htmlspecialchars($responseData['request']['group']['name'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Technician</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="technician" name="technician" value="<?php echo htmlspecialchars($responseData['request']['technician']['name'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card shadow mb-1">
                                            <div class="card-header fw-bold">Product</div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Impact</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="impact" name="impact" value="<?php echo htmlspecialchars($responseData['request']['impact']['name'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Priority</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="priority" name="priority" value="<?php echo htmlspecialchars($responseData['request']['priority']['name'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Urgency</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="urgency" name="urgency" value="<?php echo htmlspecialchars($responseData['request']['urgency']['name'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Service Category</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="service_category" name="service_category" value="<?php echo htmlspecialchars($responseData['request']['service_category']['name'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Category</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="category" name="category" value="<?php echo htmlspecialchars($responseData['request']['category']['name'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Sub Category</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="sub_category" name="sub_category" value="<?php echo htmlspecialchars($responseData['request']['subcategory']['name'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card shadow mb-1">
                                            <div class="card-header fw-bold">Status</div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Created Date</label>
                                                                <div class="col-sm-9">
                                                                    <input type="datetime" class="form-control form-control-sm" id="created_by" name="created_by" value="<?php echo htmlspecialchars($responseData['request']['created_time']['display_value'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Due By Date</label>
                                                                <div class="col-sm-9">
                                                                    <input type="datetime" class="form-control form-control-sm" id="due_by_date" name="due_by_date" value="<?php echo htmlspecialchars($responseData['request']['first_response_due_by_time']['display_value'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Response Due Date</label>
                                                                <div class="col-sm-9">
                                                                    <input type="datetime" class="form-control form-control-sm" id="respond_due_date" name="respond_due_date" value="<?php echo htmlspecialchars($responseData['request']['is_first_response_overdue']['display_value'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Responded Time</label>
                                                                <div class="col-sm-9">
                                                                    <input type="datetime" class="form-control form-control-sm" id="responded_time" name="responded_time" value="<?php echo htmlspecialchars($responseData['request']['responded_time']['display_value'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Completed Time</label>
                                                                <div class="col-sm-9">
                                                                    <input type="datetime" class="form-control form-control-sm" id="completed_time" name="completed_time" value="<?php echo htmlspecialchars($responseData['request']['completed_time']['display_value'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card shadow mb-1">
                                            <div class="card-header fw-bold"></div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Created by</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="created_by" name="created_by" value="<?php echo htmlspecialchars($responseData['request']['created_by']['name'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Template</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="template" name="template" value="<?php echo htmlspecialchars($responseData['request']['template']['name'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Closure Code</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="closure_code" name="closure_code" value="<?php echo htmlspecialchars($responseData['request']['closure_info']['closure_code']['name'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Department</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="department" name="department" value="<?php echo htmlspecialchars($responseData['request']['completed_time']['display_value'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">SLA</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="sla" name="sla" value="<?php echo htmlspecialchars($responseData['request']['completed_time']['display_value'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Closure Comments / Status Change Comments</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="notes" name="notes" value="<?php echo htmlspecialchars($responseData['request']['completed_time']['display_value'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } else {
                            echo "<div>Subject tanpa INTERNAL_SW: " . htmlspecialchars($subject1['subject']) . "</div>"; ?>
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="card shadow mb-1">
                                            <div class="card-header fw-bold">Request Details Section</div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Requester Name</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="request_name" name="request_name" value="<?php echo htmlspecialchars($responseData['request']['requester']['name'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Assets</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="assets" name="assets" value="<?php echo htmlspecialchars($responseData['request']['assets'][0]['name']) ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card shadow mb-1">
                                            <div class="card-header fw-bold">Customer Information</div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Nama Perusahaan</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="company_name" name="company_name" value="<?php echo htmlspecialchars($responseData['request']['udf_fields']['udf_char3'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Nama Cabang</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="company_branch" name="company_branch" value="<?php echo htmlspecialchars($responseData['request']['udf_fields']['udf_char17'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Alamat</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="address" name="address" value="<?php echo htmlspecialchars($responseData['request']['udf_fields']['udf_char12'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Nama Pengguna</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="user" name="user" value="<?php echo htmlspecialchars($responseData['request']['udf_fields']['udf_char10'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Nomor Mobile / Telephone</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="user_phone" name="user_phone" value="<?php echo htmlspecialchars($responseData['request']['udf_fields']['udf_long1'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Alamat Email</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="email" name="email" value="<?php echo htmlspecialchars($responseData['request']['udf_fields']['udf_char11'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card shadow mb-1">
                                            <div class="card-header fw-bold">Product Information</div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Product Name</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="product_name" name="product_name" value="<?php echo htmlspecialchars($responseData['request']['udf_fields']['udf_char7'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Product Type / PN</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="product_type" name="product_type" value="<?php echo htmlspecialchars($responseData['request']['udf_fields']['udf_char8'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Serial Number</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="serial_number" name="serial_number" value="<?php echo htmlspecialchars($responseData['request']['udf_fields']['udf_char9'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Category</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="category" name="category" value="<?php echo htmlspecialchars($responseData['request']['category']['name'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Sub Category</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="sub_category" name="sub_category" value="<?php echo htmlspecialchars($responseData['request']['subcategory'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Item</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="item" name="item" value="<?php echo htmlspecialchars($responseData['request']['item'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card shadow mb-1">
                                            <div class="card-header fw-bold">Service Information</div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Request Type</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="request_type" name="request_type" value="<?php echo htmlspecialchars($responseData['request']['request_type']['name'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Service Category</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="service_category" name="service_category" value="<?php echo htmlspecialchars($responseData['request']['service_category']['name'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Level</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="level" name="level" value="<?php echo htmlspecialchars($responseData['request']['level']['name'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Priority</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="priority" name="priority" value="<?php echo htmlspecialchars($responseData['request']['priority']['name'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Impact</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="impact" name="impact" value="<?php echo htmlspecialchars($responseData['request']['impact']['name'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Urgency</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="urgency" name="urgency" value="<?php echo htmlspecialchars($responseData['request']['urgency']['name'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card shadow mb-1">
                                            <div class="card-header fw-bold">Incident Information</div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Status</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="status" name="status" value="<?php echo htmlspecialchars($responseData['request']['status']['name'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Mode</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="mode" name="mode" value="<?php echo htmlspecialchars($responseData['request']['mode']['name'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Site</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="site" name="site" value="<?php echo htmlspecialchars($responseData['request']['site']['name'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Group</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="group" name="group" value="<?php echo htmlspecialchars($responseData['request']['group']['name'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Technician</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="technician" name="technician" value="<?php echo htmlspecialchars($responseData['request']['technician']['name'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Project Manager</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="project_manager" name="project_manager" value="<?php echo htmlspecialchars($responseData['request']['udf_fields']['udf_ref9']['name'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-header fw-bold"></div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Created Date</label>
                                                                <div class="col-sm-9">
                                                                    <input type="datetime" class="form-control form-control-sm" id="created_date" name="created_date" value="<?php echo htmlspecialchars($responseData['request']['created_time']['display_value'] ?? '0000-00-00 00:00:00') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Due By Date</label>
                                                                <div class="col-sm-9">
                                                                    <input type="datetime" class="form-control form-control-sm" id="due_by_date" name="due_by_date" value="<?php echo htmlspecialchars($responseData['request']['due_by_time']['display_value'] ?? '0000-00-00 00:00:00') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Response Due Date</label>
                                                                <div class="col-sm-9">
                                                                    <input type="datetime" class="form-control form-control-sm" id="response_due_date" name="response_due_date" value="<?php echo htmlspecialchars($responseData['request']['first_response_due_by_time']['display_value'] ?? '0000-00-00 00:00:00') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Detail Kerusakan</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="detail_kerusakan" name="detail_kerusakan" value="<?php echo htmlspecialchars($responseData['request']['udf_fields']['udf_char13'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Responded Time</label>
                                                                <div class="col-sm-9">
                                                                    <input type="datetime" class="form-control form-control-sm" id="responded_time" name="responded_time" value="<?php echo htmlspecialchars($responseData['request']['responded_time']['display_value'] ?? '0000-00-00 00:00:00') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Completed Time</label>
                                                                <div class="col-sm-9">
                                                                    <input type="datetime" class="form-control form-control-sm" id="completed_time" name="completed_time" value="<?php echo htmlspecialchars($responseData['request']['completed_time']['display_value'] ?? '0000-00-00 00:00:00') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Tindakan yang sudah dilakukan</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="tindakan" name="tindakan" value="<?php echo htmlspecialchars($responseData['request']['udf_fields']['udf_char14']['name'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card shadow mb-1">
                                            <div class="card-header fw-bold"></div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Created by</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="created_by" name="created_by" value="<?php echo htmlspecialchars($responseData['request']['created_by']['name'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Template</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="template" name="template" value="<?php echo htmlspecialchars($responseData['request']['template']['name'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Closure Code</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="closure_code" name="closure_code" value="<?php echo htmlspecialchars($responseData['request']['closure_info']['closure_code']['name'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Department</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="department" name="department" value="<?php echo htmlspecialchars($responseData['request']['department']['name'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">SLA</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="sla" name="sla" value="<?php echo htmlspecialchars($responseData['request']['sla']['name'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <label for="inputCID3" class="col-sm-3 col-form-label col-form-label-sm">Closure Comments / Status Change Comments</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control form-control-sm" id="notes" name="notes" value="<?php echo htmlspecialchars($responseData['request']['closure_info']['closure_comments']['name'] ?? 'Not Assigned') ?>" readonly>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                <?php }  ?>

                <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                    <div class="card shadow mb-4">
                        <div class="row">
                            <?php
                            // $refreshToken = '1000.8b498025c3b0f052c0a90738c62fed94.af94a2a626463b3b05bb7302c6e114b7';
                            // $clientId = '1000.XM8LNT6SZ4X6X2QXLSVHG4SUGUSKCG';
                            // $clientSecret = '837072371e410da75f64df6f82527ff15f80476abd';
                            // $redirectUri = 'http://localhost:8080/API/Smartbonding-Manageengine/smartbonding.php';
                            // $scope = 'SDPOnDemand.requests.ALL';

                            // // Mengambil access token menggunakan refresh token
                            // $tokenUrl = "https://accounts.zoho.com/oauth/v2/token";
                            // $tokenPostData = http_build_query([
                            //     'refresh_token' => $refreshToken,
                            //     'grant_type' => 'refresh_token',
                            //     'client_id' => $clientId,
                            //     'client_secret' => $clientSecret,
                            //     'redirect_uri' => $redirectUri,
                            //     'scope' => $scope,
                            // ]);

                            // $curl = curl_init();

                            // curl_setopt_array($curl, array(
                            //     CURLOPT_URL => $tokenUrl,
                            //     CURLOPT_RETURNTRANSFER => true,
                            //     CURLOPT_POST => true,
                            //     CURLOPT_POSTFIELDS => $tokenPostData,
                            //     CURLOPT_HTTPHEADER => array(
                            //         'Content-Type: application/x-www-form-urlencoded'
                            //     ),
                            // ));

                            // $response = curl_exec($curl);
                            // curl_close($curl);

                            // $responseData = json_decode($response, true);

                            // if (isset($responseData['access_token'])) {
                            //     $accessToken = $responseData['access_token'];
                            // } else {
                            //     die("Failed to get access token\n");
                            // }

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
                                            foreach ($tasksData['tasks'] as $task) {
                                                // $servername = "localhost";
                                                // $username = "root";
                                                // $password = "";
                                                // $dbname = "sa_manage_engine";

                                                // $conn = new mysqli($servername, $username, $password, $dbname);

                                                // // Cek koneksi
                                                // if ($conn->connect_error) {
                                                //     die("Connection failed: " . $conn->connect_error);
                                                // }

                                                // $taskId = $conn->real_escape_string($task['id']);
                                                // $taskTitle = $conn->real_escape_string($task['title']);
                                                // $createdBy = $conn->real_escape_string($task['created_by']['name']);
                                                // $createdDateRaw = $conn->real_escape_string($task['created_date']['display_value']);
                                                // $scheduledStartTime = $conn->real_escape_string($task['scheduled_start_time']['display_value']);
                                                // $scheduledEndTime = $conn->real_escape_string($task['scheduled_end_time']['display_value']);
                                                // $actualStartTime = $conn->real_escape_string($task['actual_start_time']['display_value'] ?? '-');
                                                // $actualEndTime = $conn->real_escape_string($task['actual_end_time']['display_value'] ?? '-');
                                                // $description = $conn->real_escape_string($task['description']);
                                                // $status = $conn->real_escape_string($task['status']['name']);
                                                // $group = $conn->real_escape_string($task['group']['name']);
                                                // $taskType = $conn->real_escape_string($task['task_type']['name'] ?? '-');
                                                // $owner = $conn->real_escape_string($task['owner']['name']);

                                                // // Konversi format tanggal
                                                // $createdDate = DateTime::createFromFormat('M j, Y h:i A', $createdDateRaw);
                                                // if ($createdDate === false) {
                                                //     die("Invalid date format: $createdDateRaw");
                                                // }
                                                // $createdDateFormatted = $createdDate->format('Y-m-d H:i:s');

                                                // $sql = "INSERT INTO sa_trx_tasks (task_id, task_title, created_by, created_date,scheduled_start_time, scheduled_end_time, 
                                                // actual_start_time, actual_end_time, description, status, group_name, task_type, owner) VALUES ('$taskId', '$taskTitle', '$createdBy', '$createdDateFormatted', '$scheduledStartTime', '$scheduledEndTime', '$actualStartTime', '$actualEndTime', '$description', '$status', '$group', '$taskType', '$owner') ON DUPLICATE KEY UPDATE
                                                // task_title='$taskTitle', created_by='$createdBy', created_date='$createdDateFormatted',
                                                // scheduled_start_time='$scheduledStartTime', scheduled_end_time='$scheduledEndTime',
                                                // actual_start_time='$actualStartTime', actual_end_time='$actualEndTime',
                                                // description='$description', status='$status', group_name='$group', task_type='$taskType', owner='$owner'";

                                                // if ($conn->query($sql) !== TRUE) {
                                                //     echo "Error: " . $sql . "<br>" . $conn->error;
                                                // }


                                                // // Tutup koneksi
                                                // $conn->close(); 
                                        ?>
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
                    </div>
                </div>
                </div>



            <?php } ?>
            <input type="submit" class="btn btn-secondary" name="cancel" value="Cancel">
            <?php if (isset($_GET['act']) && $_GET['act'] == 'edit') { ?>
                <input type="submit" class="btn btn-primary" name="save" value="Save">
            <?php } elseif (isset($_GET['act']) && $_GET['act'] == 'view') { ?>
                <input type="submit" class="btn btn-primary" name="save" value="Save">
            <?php } elseif (isset($_GET['act']) && $_GET['act'] == 'add') { ?>
                <input type="submit" class="btn btn-primary" name="add" value="Save">
            <?php } ?>
            </form>