<?php
if ((isset($property)) && ($property == 1)) {
    $version = '1.0';
    $released = "1722499928";
    $author = 'Syamsul Arham';
} else {

?>

    <?php
    $mdlname = "trx_manage_engine";
    $userpermission = useraccess($mdlname);
    // var_dump($userpermission);
    // die;
    // if (USERPERMISSION == "7b7bc2512ee1fedcd76bdc68926d4f7b" || USERPERMISSION == "dbf36ff3e3827639223983ee8ac47b42") {
    ?>
    <script>
        $(document).ready(function() {
            var table = $('#trx_manage_engine').DataTable({
                dom: 'Blfrtip',
                select: {
                    style: 'single'
                },
                buttons: [{
                        extend: 'colvis',
                        text: "<i class='fa fa-columns'></i>",
                        collectionLayout: 'fixed four-column'
                    },
                    {
                        text: "<i class='fa fa-eye'></i>",
                        action: function() {
                            var rownumber = table.rows({
                                selected: true
                            }).indexes();
                            var id_manage_engine = table.cell(rownumber, 0).data();
                            var id_request = table.cell(rownumber, 1).data();
                            window.location.href = "index.php?mod=trx_manage_engine&act=view&id_request=" + id_request + "&submit=Submit";
                        }
                    },
                    {
                        text: "<i class='fa fa-pen'></i>",
                        action: function() {
                            var rownumber = table.rows({
                                selected: true
                            }).indexes();
                            var id_manage_engine = table.cell(rownumber, 0).data();
                            var id_request = table.cell(rownumber, 1).data();
                            window.location.href = "index.php?mod=trx_manage_engine&act=edit&id_request=" + id_request + "&submit=Submit";
                        }
                    },
                    {
                        text: "<i class='fa fa-plus'></i>",
                        // action: function() {
                        //     window.location.href = "index.php?mod=trx_manage_engine&act=add";
                        //     text: "<i class='fa fa-pen'></i>",
                        action: function() {
                            var rownumber = table.rows({
                                selected: true
                            }).indexes();
                            var id_manage_engine = table.cell(rownumber, 0).data();
                            var id_request = table.cell(rownumber, 1).data();
                            window.location.href = "index.php?mod=trx_manage_engine&act=add&id_request=" + id_request + "&submit=Submit";
                        },
                    }
                ],
                "columnDefs": [{
                    "targets": [],
                    "visible": false,
                }],
            });
        });
    </script>
    <?php

    // Function
    //   if($_SESSION['Microservices_UserLevel'] == "Administrator") {
    function view_data($tblname)
    {
        global $DBManageEngine;
        $primarykey = "id_manage_engine";
        $condition = "";
        $order = "";
        if (isset($_GET['err']) && $_GET['err'] == "datanotfound") {
            global $ALERT;
            $ALERT->datanotfound();
        }
        //SELECT * FROM sa_pic LIMIT 0, 100
        //view_table($DB, $tblname, $primarykey, $condition, $order);
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
        if (curl_errno($curl)) {
            die("cURL error: " . curl_error($curl));
        }
        curl_close($curl);

        $responseData = json_decode($response, true);

        if (isset($responseData['access_token'])) {
            $accessToken = $responseData['access_token'];
        } else {
            die("Failed to get access token\n");
        }

        // Mengambil data requests dengan input_data
        // $inputDataUrl = 'https://sdpondemand.manageengine.com/api/v3/requests?input_data=%7B%22list_info%22%3A%20%7B%22row_count%22%3A%201000%2C%20%22start_index%22%3A%201%2C%20%22sort_field%22%3A%20%22created_time%22%2C%22sort_order%22%3A%20%22desc%22%7D%7D';
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $data = [
            "list_info" => [
                "row_count" => 100,
                "start_index" => 1,
                "sort_field" => "last_updated_time",
                "sort_order" => "desc",
                "search_criteria" => [
                    "field" => "updated_at",
                    "condition" => "between",
                    "value" => [$yesterday, $today]
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
        if (curl_errno($curl)) {
            die("cURL error: " . curl_error($curl));
        }
        curl_close($curl);

        $requestsData = json_decode($response, true);

        // Koneksi ke database
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "sa_manage_engine";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $allInserted = true; // Flag untuk memeriksa apakah semua data berhasil diinsert

        // Menyimpan data ke database
        if (isset($requestsData['requests'])) {
            foreach ($requestsData['requests'] as $request) {
                $id = $conn->real_escape_string($request['id']);
                $subject = $conn->real_escape_string($request['subject']);
                $display_id = $conn->real_escape_string($request['display_id']);
                $created_time_raw = $conn->real_escape_string($request['created_time']['display_value']);
                $status = $conn->real_escape_string($request['status']['name']);
                $requester_name = $conn->real_escape_string($request['requester']['name']);

                // Mengonversi format tanggal
                $dateTime = DateTime::createFromFormat('M j, Y h:i A', $created_time_raw);
                if ($dateTime === false) {
                    die("Format tanggal tidak valid: $created_time_raw");
                }
                $created_time = $dateTime->format('Y-m-d H:i:s');

                $sql = "INSERT INTO sa_trx_manage_engine (id_request, request, subject, request_by, created_date, status)
                VALUES ('$id', '$display_id', '$subject', '$requester_name', '$created_time', '$status')
                ON DUPLICATE KEY UPDATE
                subject='$subject', request='$display_id', created_date='$created_time', status='$status', request_by='$requester_name'";

                if (!$conn->query($sql) === TRUE) {
                    $allInserted = false; // Jika ada kesalahan, set flag menjadi false
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }
        }

        $conn->close();
        if ($allInserted) {
            echo "<script>alert('Success Insert');</script>";
        }
        $query = "SELECT * FROM sa_trx_manage_engine";
        $datatable = $DBManageEngine->get_sql($query);
        $ddatatable = $datatable[0];
        $qdatatable = $datatable[1];
        $tdatatable = $datatable[2];

        $header = ['ID', 'ID Request', 'Ticket', 'Subject', 'Request By', 'Created By', 'Status'];
        $header1 = '';
        foreach ($header as $head) {
            $header1 .= "<th class='text-center align-middle'>" . $head . "</th>";
        }
    ?>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered hover stripe display compact dataTableMulti" id="<?php echo $tblname; ?>" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <?php
                            echo $header1;
                            ?>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <?php echo $header1; ?>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                        if ($tdatatable > 0) {
                            do { ?>
                                <tr>
                                    <?php
                                    $datatable_header2 = $DBManageEngine->get_columns($tblname);
                                    $ddatatable_header2 = $datatable_header2[0];
                                    $qdatatable_header2 = $datatable_header2[1];
                                    ?>
                                    <?php do { ?>
                                        <?php
                                        if ($ddatatable_header2['Field'] == 'customer_code') {
                                            $ddatatable_header2['Field'] = 'customer_company_name';
                                        }
                                        if ($ddatatable_header2['Field'] != 'survey_count') {
                                            echo '<td>' . $ddatatable[$ddatatable_header2['Field']]; ?></td>
                                    <?php } else {
                                            continue;
                                        }
                                    } while ($ddatatable_header2 = $qdatatable_header2->fetch_assoc()); ?>
                                </tr>
                        <?php
                            } while ($ddatatable = $qdatatable->fetch_assoc());
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php

        // view_table($DBManageEngine, $tblname, $primarykey, $condition, $order);
    }
    function form_data($tblname)
    {
        include("components/modules/trx_manage_engine/form_trx_manage_engine.php");
    }

    // End Function

    // $database = 'sa_manage_engine';
    // include("components/modules/trx_manage_engine/connection.php");
    // $DBManageEngine = new Databases($hostname, $username, $userpassword, $database);
    $DBManageEngine = get_conn("TRX_MANAGE_ENGINE");
    $tblname = 'trx_manage_engine';
    $tblname1 = 'customer_information';
    $tblname2 = 'trx_customer';

    include("components/modules/trx_manage_engine/func_trx_manage_engine.php");

    // Body
    ?>
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">trx_manage_engine</h6>
            </div>
            <div class="card-body">
                <?php
                if (!isset($_GET['act'])) {
                    // $condition = "";
                    view_data($tblname);
                } elseif ($_GET['act'] == 'add') {
                    form_data($tblname);
                } elseif ($_GET['act'] == 'new') {
                    new_projects($tblname);
                } elseif ($_GET['act'] == 'edit') {
                    form_data($tblname);
                } elseif ($_GET['act'] == 'del') {
                    echo 'Delete Data';
                } elseif ($_GET['act'] == 'save') {
                    form_data($tblname);
                }
                ?>
            </div>
        </div>
    </div>
<?php

    // } else {
    //     $ALERT->notpermission();
    // }
    // End Body
}
// }
?>