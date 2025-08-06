<?php
include 'GenerateToken.php';
include 'conn.php';
session_start();
$query = "SELECT idworklog,CustCallID,description FROM sa_worklog WHERE description LIKE 'RESOLVE%' AND status LIKE '%Belum%' OR status LIKE '%Updated%'";
mysqli_select_db($conn, 'sa_worklog');
$dataworklog = mysqli_query($conn, $query);
if (!$dataworklog) {
    die('Could not get data from WORKLOG: ' . mysqli_error());
}
while ($getworklog = mysqli_fetch_assoc($dataworklog)) {
    $getdata = $getworklog['CustCallID'];
    $getdata2 = $getworklog['dekcription'];
    date_default_timezone_set("Asia/Jakarta");
    $datenow = date("Y-m-d H:i:s");
    $data = array(
        "Calls" => array(
            "CustCallID" => "$getdata",
            "Remarks" => "$getdata2"
        ),
        "CallStates" => array(
            "ShortName" => "Resolved"
        )
    );

    $postdata = json_encode($data);
    // echo "$postdata";
    $curl = curl_init();
    $headers = array(
        "Content-Type:application/json",
        "Authorization: Bearer $accessToken"
    );

    curl_setopt($curl, CURLOPT_URL, "https://sb.xylem.cisco.com/sb-partner-oauth-proxy-api/rest/v1/push/call");
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($curl);
    curl_close($curl);
    print_r($result);

    $feedback = json_decode($result, true);
    $sdcall = isset($feedback["status"]);
    if ($sdcall == 200) {
        $sdcall = $feedback["status"];
        $querySQL = "INSERT INTO sa_resolve_ticket (CustCallID,input_json,response_json,tanggal_resolve) VALUES ('$getdata','$postdata','$result','$datenow')";
        if (mysqli_query($conn, $querySQL)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $querySQL . "<br>" . mysqli_error($conn);
        }
        $queryupdate = "UPDATE sa_master_data SET status='Close' WHERE CustCallID LIKE '%$getdata%'";
        if (mysqli_query($conn, $queryupdate)) {
            echo "Update record master data successfully";
        } else {
            echo "Error: " . $queryupdate . "<br>" . mysqli_error($conn);
        }

        $queryupdate2 = "UPDATE sa_worklog SET status='Close' WHERE CustCallID LIKE '%$getdata%'";
        if (mysqli_query($conn, $queryupdate2)) {
            echo "Update record worklog successfully";
        } else {
            echo "Error: " . $queryupdate2 . "<br>" . mysqli_error($conn);
        }
    } else {
        $sdcall = $feedback["status"];
        $querySQL = "INSERT INTO sa_resolve_ticket (CustCallID,input_json,response_json,tanggal_resolve) VALUES ('$getdata','$postdata','$result','$datenow')";
        if (mysqli_query($conn, $querySQL)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $querySQL . "<br>" . mysqli_error($conn);
        }
    }
}
