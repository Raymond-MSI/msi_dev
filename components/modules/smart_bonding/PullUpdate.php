<?php
include 'GenerateToken.php';
include 'conn.php';
set_time_limit(100);
$crl = curl_init();

curl_setopt_array($crl, array(
    CURLOPT_URL => 'https://sb.xylem.cisco.com/sb-partner-oauth-proxy-api/rest/v1/pull/call',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        "Authorization: Bearer $accessToken"
    ),
));

$response = curl_exec($crl);
curl_close($crl);
date_default_timezone_set("Asia/Jakarta");
$feedback = json_decode($response, true);
$message = isset($feedback["message"]);
echo "$response";
if ($message == 'No messages available to Send') {
    echo 'tidak ada update';
} else {
    do {
        $sdcall = isset($feedback["Calls"]["SDCallID"]);
        if ($sdcall == null) {
            '';
        } else {
            $sdcall = $feedback["Calls"]["SDCallID"];
            $custcall = $feedback["Calls"]["CustCallID"];
            $spcall = isset($feedback["Calls"]["SPCallID"]);
            $status = $feedback["Calls"]["CallStates"]["ShortName"];
            if ($status == "Rejected") {
                $remarks = json_encode($feedback["Calls"]["Remarks"]);
            } else {
                $remarks = $feedback["Calls"]["Remarks"];
            }
            if ($spcall == null) {
                '';
            } else {
                $spcall = $feedback["Calls"]["SPCallID"];
            }
            $contract = isset($feedback["Calls"]["MainComp"]["InvNr"]);
            if ($contract == null) {
                '';
            } else {
                $contract = $feedback["Calls"]["MainComp"]["InvNr"];
                $task_id = $feedback["ExtTableValues"]["Field115"];
            }
            $querySQL = "INSERT INTO sa_response_update (SDCallID,CustCallID,SPCallID,Remarks,Contract_Number,status,task_id) VALUES ('$sdcall','$custcall','$spcall','$remarks','$contract','Belum','$task_id')";

            if (mysqli_query($conn, $querySQL)) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $querySQL . "<br>" . mysqli_error($conn);
            }
            date_default_timezone_set("Asia/Jakarta");
            $datenow = date("Y-m-d H:i:s");
            $queryinsert = "INSERT INTO sa_ticket_update (CustCallID,input_json,response_json,tanggal_update) VALUES ('NULL','NULL','$response','$datenow')";
            if (mysqli_query($conn, $queryinsert)) {
                echo "Update record successfully for Status";
            } else {
                echo "Error: " . $queryupdate . "<br>" . mysqli_error($conn);
            }
        }
        $crl = curl_init();

        curl_setopt_array($crl, array(
            CURLOPT_URL => 'https://sb.xylem.cisco.com/sb-partner-oauth-proxy-api/rest/v1/pull/call',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                "Authorization: Bearer $accessToken"
            ),
        ));

        $response = curl_exec($crl);

        curl_close($crl);
        echo $response;
        $feedback = json_decode($response, true);
        $message = isset($feedback["message"]);
        if ($message == 'No messages available to Send') {
            echo 'tidak ada update';
        } else {
            $sdcall = isset($feedback["Calls"]["SDCallID"]);
            if ($sdcall == null) {
                '';
            } else {
                $sdcall = $feedback["Calls"]["SDCallID"];
                $custcall = $feedback["Calls"]["CustCallID"];
                $spcall = isset($feedback["Calls"]["SPCallID"]);
                if ($spcall == null) {
                    '';
                } else {
                    $spcall = $feedback["Calls"]["SPCallID"];
                }
                $status = $feedback["Calls"]["CallStates"]["ShortName"];
                if ($status == "Rejected") {
                    $remarks = json_encode($feedback["Calls"]["Remarks"]);
                } else {
                    $remarks = $feedback["Calls"]["Remarks"];
                }
                $contract = isset($feedback["Calls"]["MainComp"]["InvNr"]);
                if ($contract == null) {
                    '';
                } else {
                    $contract = $feedback["Calls"]["MainComp"]["InvNr"];
                    $task_id = $feedback["ExtTableValues"]["Field115"];
                }
                date_default_timezone_set("Asia/Jakarta");
                $datenow = date("Y-m-d H:i:s");
                $queryinsert = "INSERT INTO sa_ticket_update (CustCallID,input_json,response_json,tanggal_update) VALUES ('NULL','NULL','$response','$datenow')";
                if (mysqli_query($conn, $queryinsert)) {
                    echo "Update record successfully for Status";
                } else {
                    echo "Error: " . $queryupdate . "<br>" . mysqli_error($conn);
                }
                $querySQL = "INSERT INTO sa_response_update (SDCallID,CustCallID,SPCallID,Remarks,Contract_Number,status,task_id) VALUES ('$sdcall','$custcall','$spcall','$remarks','$contract','Belum','$task_id')";

                if (mysqli_query($conn, $querySQL)) {
                    echo "New record created successfully";
                } else {
                    echo "Error: " . $querySQL . "<br>" . mysqli_error($conn);
                }
            }
        }
    } while ($message != "No messages available to Send");
}
