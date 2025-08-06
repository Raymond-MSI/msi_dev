<?php
include 'conn.php';
include 'token_asset.php';
include 'token_request.php';
set_time_limit(500);
// GET REQUEST ID <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
// if ($_GET['act'] == 'add') {
$tgl_kemarin    = date('Y-m-d', strtotime("-1 day", strtotime(date("Y-m-d"))));
date_default_timezone_set('Asia/Jakarta');
$nowDate = date("M d, Y");
// $nowDate = ("Aug 26, 2022");
$stamp = strtotime($nowDate);
$finalDate = $stamp * 1000;
$input = array("list_info" => array("row_count" => 100, "start_index" => 900));
$postinput = json_encode($input);
$urlget = "https://sdpondemand.manageengine.com/api/v3/requests/?input_data=%7B%22list_info%22%3A%20%7B%22search_criteria%22%3A%20%7B%22field%22%3A%20%22created_time%22%2C%22condition%22%3A%20%22greater%20than%22%2C%20%22value%22%3A%20%22$finalDate%22%7D%7D%7D";
$header = array("Content-Type: application/x-www-form-urlencoded", "Accept: application/vnd.manageengine.sdp.v3+json", "Authorization: Zoho-Oauthtoken $accessTokenrequest");
$crlreqid = curl_init();
curl_setopt($crlreqid, CURLOPT_URL, $urlget);
curl_setopt($crlreqid, CURLOPT_HTTPHEADER, $header);
curl_setopt($crlreqid, CURLOPT_RETURNTRANSFER, 1);
$rslt = curl_exec($crlreqid);
curl_close($crlreqid);
$dataRequest = json_decode($rslt, true);

$accessRequest = $dataRequest["requests"];

for ($i = 0; $i < count($accessRequest); $i++) {
    $subject = $accessRequest[$i]['id'];
    $sbjct = $accessRequest[$i]['created_time']['display_value'];

    $querySQL = "INSERT INTO sa_requests (id,created_time,status) VALUES ('$subject','$sbjct','Belum')";

    if (mysqli_query($conn, $querySQL)) {
        echo "New record created successfully";
    } else {
        '';
    }
}
// GET REQUEST FOR MASTER DATA <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
$tanggal = date('M');
// $tanggal = ("Aug 26, 2022");
$tanggal_kemarin = date('M d, Y h:i A', strtotime("-1 day", strtotime(date("M d, Y h:i A"))));
$sqlmaster = "SELECT id,created_time,status FROM sa_requests WHERE created_time LIKE '%$tanggal%' AND status LIKE 'Belum'";
mysqli_select_db($conn, 'requests');
$retval = mysqli_query($conn, $sqlmaster);
set_time_limit(120);
if (!$retval) {
    die('Could not get data for Master Data: ' . mysqli_error());
}
while ($row = mysqli_fetch_assoc($retval)) {
    $data = $row['id'];

    $urlget = "https://sdpondemand.manageengine.com/api/v3/requests/$data";
    $header = array("Content-Type: application/x-www-form-urlencoded", "Accept: application/vnd.manageengine.sdp.v3+json", "Authorization: Zoho-Oauthtoken $accessTokenrequest");
    $crl = curl_init();
    curl_setopt($crl, CURLOPT_URL, $urlget);
    curl_setopt($crl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
    $rslt = curl_exec($crl);
    curl_close($crl);
    $idAsset = json_decode($rslt, true);
    $accessAsset = isset($idAsset["request"]["assets"][0]["id"]);
    if ($accessAsset == null) {
        $accessAsset = '';
    } else {
        $accessAsset = $idAsset["request"]["assets"][0]["id"];
    }
    $subject = $idAsset["request"]["id"];
    $subject2 = $idAsset["request"]["subject"];
    $subject3 = $idAsset["request"]["requester"]["id"];
    $subject4 = $idAsset["request"]["requester"]["last_name"];
    $subject5 = $idAsset["request"]["requester"]["first_name"];
    $subject6 = $idAsset["request"]["requester"]["mobile"];
    $subject7 = "putuwirama@mastersystem.co.id";
    $pin = "00u63wq7gzI8w8N1Y5d7";
    $subject8 = $idAsset["request"]["requester"]["job_title"];
    $subject9 = isset($idAsset["request"]["technician"]["id"]);
    if ($subject9 == null) {
        '';
    } else {
        $subject9 = $idAsset["request"]["technician"]["id"];
        $subject10 = $idAsset["request"]["technician"]["last_name"];
        $subject11 = $idAsset["request"]["technician"]["first_name"];
        $subject12 = $idAsset["request"]["technician"]["email_id"];
        $subject13 = $idAsset["request"]["technician"]["mobile"];
        $subject14 = $idAsset["request"]["technician"]["job_title"];
    }
    $subject15 = $idAsset["request"]["description"];
    $querySQL = "INSERT INTO sa_master_data (CustCallID,asset_id,short_description,chd_id,chd_lastname,chd_firstname,chd_mobile,chd_email,chd_pin,chd_jobtitle,caller_id,caller_lastname,caller_firstname,caller_email,caller_mobile,caller_jobtitle,description,status) VALUES ('$subject','$accessAsset','$subject2','$subject3','$subject4','$subject5','$subject6','$subject7','$pin','$subject8','$subject9','$subject10','$subject11','$subject12','$subject13','$subject14','$subject15','Belum')";

    if (mysqli_query($conn, $querySQL)) {
        echo "New record created successfully (MASTER DATA)";
    } else {
        '';
    }
    $queryupdate = "UPDATE sa_requests SET status='Sudah' WHERE id LIKE '$subject'";
    if (mysqli_query($conn, $queryupdate)) {
        echo "New Update Success (MASTER DATA)";
    } else {
        '';
    }
}
// }
// GET ASSET <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
$sqlasset = 'SELECT asset_id FROM sa_master_data';
mysqli_select_db($conn, 'master_data');
$aset = mysqli_query($conn, $sqlasset);
if (!$aset) {
    die('Could not get data for Asset: ' . mysqli_error());
}
while ($row = mysqli_fetch_assoc($aset)) {
    $data = $row['asset_id'];

    $crl = curl_init();
    curl_setopt_array($crl, array(
        CURLOPT_URL => "https://sdpondemand.manageengine.com/api/v3/assets/$data",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            "Accept: application/vnd.manageengine.sdp.v3+json",
            "Content-Type: application/x-www-form-urlencoded",
            ": ",
            "Authorization: Zoho-Oauthtoken $accessTokenasset",
            "Cookie: 6bc9ae5955=98bb51dff2c74a80ad54d38223d16303; _zcsr_tmp=93147a7f-d4d1-42a9-9520-6b60c7b1ddf2; sdpcscook=93147a7f-d4d1-42a9-9520-6b60c7b1ddf2"
        ),
    ));

    $result = curl_exec($crl);
    curl_close($crl);
    $asset = json_decode($result, true);
    $printasset = isset($asset["asset"]["udf_fields"]["udf_char24"]);
    if ($printasset == null) {
        $printasset = 'NULL';
    } else {
        $printasset = $asset["asset"]["udf_fields"]["udf_char24"];
        $printname = $asset["asset"]["name"];
        $printid = $asset["asset"]["id"];
        $printwarrantyend = isset($asset["asset"]["udf_fields"]["udf_date2"]["display_value"]);
        if ($printwarrantyend == null) {
            '';
        } else {
            $printwarrantyend = $asset["asset"]["udf_fields"]["udf_date2"]["display_value"];
        }
        $printnameasset = $asset["asset"]["product_type"]["name"];
        // $testwarranty = formatTanggal($printwarrantyend);
        $querySQL = "INSERT INTO sa_asset (asset_id,serial_number,contract_number,warranty_end,manufacture) VALUES ('$printid','$printname','$printasset','$printwarrantyend','$printnameasset')";

        if (mysqli_query($conn, $querySQL)) {
            echo "New record created successfully (ASSET)";
        } else {
            '';
        }

        $queryupdate = "UPDATE sa_master_data SET serial_number='$printname', contract_number='$printasset' WHERE asset_id='$data'";
        if (mysqli_query($conn, $queryupdate)) {
            echo "New record created successfully for SN dan Contract Number";
        } else {
            echo "Gagal Update SN for Master Data";
        }
    }
}
// GET TASK <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
$sqltask = 'SELECT CustCallID FROM sa_master_data';
mysqli_select_db($conn, 'master_data');
$tax = mysqli_query($conn, $sqltask);
if (!$tax) {
    die('Could not get data Task: ' . mysqli_error());
}
while ($row = mysqli_fetch_assoc($tax)) {
    $dataToken = json_decode($response, true);
    $accessToken = $dataToken["access_token"];
    $data = $row["CustCallID"];

    $crl = curl_init();

    curl_setopt_array($crl, array(
        CURLOPT_URL => "https://sdpondemand.manageengine.com/api/v3/requests/$data/tasks?input_data=%7B%22list_info%22%3A%20%7B%22row_count%22%3A%20100%7D%7D",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            "Accept: application/vnd.manageengine.sdp.v3+json",
            "Content-Type: application/x-www-form-urlencoded",
            "Authorization: Bearer $accessTokenrequest",
            "Cookie: 6bc9ae5955=7457abf2db47fcd63a6bf00b46e37537; JSESSIONID=83809DA42B285E655F71AAC8B4C8914B; _zcsr_tmp=984868c5-9852-4fbd-8d52-d31a1a4d8cd6; sdpcscook=984868c5-9852-4fbd-8d52-d31a1a4d8cd6"
        ),
    ));

    $hasil = curl_exec($crl);

    curl_close($crl);
    $task = json_decode($hasil, true);
    $task1 = $task['tasks'];
    for ($i = 0; $i < count($task1); $i++) {
        $task_id = isset($task1[$i]["id"]);
        if ($task_id == null) {
            '';
        } else {
            $task_id = $task1[$i]["id"];
            $title_task = $task1[$i]["title"];
            $desc = $task1[$i]["description"];
            $task_custcallid = $task1[$i]["request"]["id"];
            $querySQL = "INSERT INTO sa_task (task_id,description,CustCallID,title) VALUES ('$task_id','$desc','$task_custcallid','$title_task')";
            if (mysqli_query($conn, $querySQL)) {
                echo "New record created successfully (TASK)";
            } else {
                '';
            }
            $sqlsql = "UPDATE sa_response SET task_id='$task_id' WHERE Contract_Number LIKE '%$desc%'";
            if (mysqli_query($conn, $sqlsql)) {
                echo "New record";
            } else {
                '';
            }
        }
    }
}
// GET WORKLOG <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
$sqlworklog = 'SELECT CustCallID FROM sa_master_data';
mysqli_select_db($conn, 'master_data');
$wrklog = mysqli_query($conn, $sqlworklog);
if (!$wrklog) {
    '';
}
while ($row = mysqli_fetch_assoc($wrklog)) {
    $data = $row["CustCallID"];
    $sql = "SELECT task_id FROM sa_task WHERE CustCallID LIKE '%$data%'";
    mysqli_select_db($conn, 'task');
    $get = mysqli_query($conn, $sql);
    $list = mysqli_fetch_assoc($get);
    $data2 = isset($list['task_id']);
    if ($data2 == null) {
        '';
    } else {
        $data2 = $list['task_id'];
        $crl = curl_init();

        curl_setopt_array($crl, array(
            CURLOPT_URL => "https://sdpondemand.manageengine.com/api/v3/requests/$data/tasks/$data2/worklogs",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Accept: application/vnd.manageengine.sdp.v3+json',
                'Content-Type: application/x-www-form-urlencoded',
                "Authorization: Bearer $accessTokenrequest",
                'Cookie: 6bc9ae5955=7457abf2db47fcd63a6bf00b46e37537; JSESSIONID=83809DA42B285E655F71AAC8B4C8914B; _zcsr_tmp=984868c5-9852-4fbd-8d52-d31a1a4d8cd6; sdpcscook=984868c5-9852-4fbd-8d52-d31a1a4d8cd6'
            ),
        ));

        $hasil = curl_exec($crl);

        curl_close($crl);
        $worklog = json_decode($hasil, true);
        $worklog1 = $worklog['worklogs'];
        for ($i = 0; $i < count($worklog1); $i++) {
            $task_id = isset($worklog1[$i]["id"]);
            if ($task_id == null) {
                '';
            } else {
                $idworklog = $worklog1[$i]["id"];
                $desc = $worklog1[$i]["description"];
                $owner_id = $worklog1[$i]["owner"]["id"];
                $owner_name = $worklog1[$i]["owner"]["name"];
                $worklog_name = isset($worklog1[$i]["worklog_type"]["name"]);
                if ($worklog_name == null) {
                    '';
                } else {
                    $worklog_name = $worklog1[$i]["worklog_type"]["name"];
                }
                $worklog_id = isset($worklog1[$i]["worklog_type"]["id"]);
                if ($worklog_id == null) {
                    '';
                } else {
                    $worklog_id = $worklog1[$i]["worklog_type"]["id"];
                }
                $querySQL = "INSERT INTO sa_worklog (idworklog,CustCallID,task_id,owner_name,owner_id,description,worklog_name,worklog_id,status) VALUES ('$idworklog','$data','$data2','$owner_name', '$owner_id', '$desc', '$worklog_name', '$worklog_id', 'Belum')";
                if (mysqli_query($conn, $querySQL)) {
                    echo "New record created successfully (WORKLOG)";
                } else {
                    '';
                }
            }
        }
    }
}
// Add Worklog <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
date_default_timezone_set('Asia/Jakarta');
$sql = "SELECT CustCallID,SDCallID,Remarks FROM sa_response_update WHERE status='Belum'";
mysqli_select_db($conn, 'sa_response_update');
$retval = mysqli_query($conn, $sql);
if (!$retval) {
    die('Could not get data: ' . mysqli_error());
}

while ($row = mysqli_fetch_assoc($retval)) {
    $data1 = $row["CustCallID"];
    $data2 = $row["SDCallID"];
    $data4 = $row["Remarks"];
    $tanggal = date('M d, Y h:i A');

    $data = array(
        "worklog" => array(
            "owner" => array(
                "name" => "Syamsul Arham",
                "id" => "145684000000179500"
            ),
            "include_nonoperational_hours" => false,
            "end_time" => null,
            "description" => "SDCallID : $data2 
            
            Remarks : $data4",
            "other_charge" => "0",
            "recorded_time" => array(
                "value" => "1478758440000"
            ),
            "time_spent" => array(
                "hours" => "01",
                "minutes" => "20",
                "value" => "4800000"
            ),
            "tech_charge" => "0",
            "mark_first_response" => false,
            "start_time" => null,
            "worklog_type" => array(
                "name" => "smartbonding",
                "id" => "145684000010950083"
            )
        )
    );

    $curl = curl_init();
    $postdata = json_encode($data);
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://sdpondemand.manageengine.com/api/v3/requests/$data1/worklogs",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => "input_data=$postdata",
        CURLOPT_HTTPHEADER => array(
            'Accept: application/vnd.manageengine.sdp.v3+json',
            'Content-Type: application/x-www-form-urlencoded',
            "Authorization: Bearer $accessTokenrequest",
            'Cookie: 6bc9ae5955=7457abf2db47fcd63a6bf00b46e37537; JSESSIONID=83809DA42B285E655F71AAC8B4C8914B; _zcsr_tmp=984868c5-9852-4fbd-8d52-d31a1a4d8cd6; sdpcscook=984868c5-9852-4fbd-8d52-d31a1a4d8cd6'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    $querySQL = "INSERT INTO sa_add_worklog (output_json,input_json,date) VALUES ('$response','$postdata','$tanggal')";
    if (mysqli_query($conn, $querySQL)) {
        echo "New record created successfully";
    } else {
        '';
    }

    $queryupdate = "UPDATE sa_response_update SET status='Sudah' WHERE CustCallID LIKE '$data1'";
    if (mysqli_query($conn, $queryupdate)) {
        echo "New record created successfully (Add Worklog)";
    } else {
        '';
    }
}
