<?php
include 'GenerateToken.php';
include 'conn.php';

$sql = "SELECT idworklog,CustCallID,description FROM sa_worklog WHERE description LIKE 'UPDATE%' AND status LIKE '%Belum%' OR status LIKE '%Update%'";
mysqli_select_db($conn, 'sa_worklog');
$retval = mysqli_query($conn, $sql);
if (!$retval) {
    die('Could not get data: ' . mysqli_error());
}
while ($row = mysqli_fetch_assoc($retval)) {
    $data1 = $row['description'];
    $data2 = $row['CustCallID'];
    $data3 = $row['idworklog'];

    date_default_timezone_set("Asia/Jakarta");
    $datenow = date("Y-m-d H:i:sa");

    $datapost = array(
        "Calls" => array(
            "CustCallID" => "$data2",
            "Remarks" => "$data1"
        ),
        "CallStates" => array(
            "ShortName" => "Update"
        )
    );

    $postdata = json_encode($datapost);
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

    // echo $result;
    $querySQL = "INSERT INTO sa_ticket_update (CustCallID,input_json,response_json,tanggal_update) VALUES ('$data2','$postdata','$result','$datenow')";
    if (mysqli_query($conn, $querySQL)) {
        echo "New record created on sa_ticket_update";
    } else {
        'Gagal Create Record';
    }

    $queryupdate = "UPDATE sa_master_data SET status='Updated on $datenow' WHERE CustCallID LIKE '%$data2%'";
    if (mysqli_query($conn, $queryupdate)) {
        echo "Update record successfully for sa_master_data";
    } else {
        echo "Gagal Update Close Ticket to sa_master_data";
    }

    $queryupdate2 = "UPDATE sa_worklog SET status='Updated on $datenow' WHERE idworklog LIKE '%$data3%'";
    if (mysqli_query($conn, $queryupdate2)) {
        echo "Update record successfully for sa_worklog";
    } else {
        echo "Gagal Update Close Ticket to sa_worklog";
    }
}
