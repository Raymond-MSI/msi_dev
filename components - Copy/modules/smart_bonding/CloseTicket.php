<?php
include 'GenerateToken.php';
include 'conn.php';
session_start();
$sql = "SELECT CustCallID,description FROM sa_worklog WHERE description LIKE 'CLOSE%'";
mysqli_select_db($conn, 'sa_worklog');
$retval = mysqli_query($conn, $sql);
if (!$retval) {
    die('Could not get data From sa_worklog: ');
}
while ($row = mysqli_fetch_assoc($retval)) {
    $data1 = $row['CustCallID'];
    $data2 = $row['description'];
    date_default_timezone_set("Asia/Jakarta");
    $datenow = date("Y-m-d H:i:s A");
    $mysql = "SELECT CustCallID,serial_number FROM sa_asset WHERE CustCallID LIKE '%$data1%'";
    mysqli_select_db($conn, 'sa_asset');
    $getdata = mysqli_query($conn, $mysql);
    $get = mysqli_fetch_assoc($getdata);
    $data3 = $get["serial_number"];
    $data = array(
        "Calls" => array(
            "CustCallID" => "$data1",
            "Description" => "Closing test",
            "Solution"  => "$data2",
            "Diagnosis"  => "Faulty power socket",
            "CCP" => array(
                "Department" => "3074572",
                "PIN" => "1866083", //$data3
                "LocationCity" => "1 Level -Basic",
                "LocationStreet" => "faulty power socket changed",
                "Room" => "$data3",
                "Fax" => "Software Configuration",
                "EMail" => "Software Bug"
            )
        ),
        "CallStates" => array(
            "ShortName" => "Closed"
        )
    );

    $postdata = json_encode($data);
    echo "$postdata";
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

    $querySQL = "INSERT INTO sa_close_ticket (CustCallID,input_json,response_json,tanggal_close) VALUES ('$data1','$postdata','$result','$datenow')";
    if (mysqli_query($conn, $querySQL)) {
        echo "New record created successfully for sa_close_ticket";
    } else {
        echo 'Gagal Insert Into sa_close_ticket' . $querySQL . "<br>" . mysqli_error($conn);
    }

    $queryupdate = "UPDATE sa_master_data SET status='Close' WHERE CustCallID LIKE '%$data1%'";
    if (mysqli_query($conn, $queryupdate)) {
        echo "Update record successfully for sa_master_data";
    } else {
        echo "Gagal Update Close Ticket to sa_master_data" . $queryupdate . "<br>" . mysqli_error($conn);
    }
}
