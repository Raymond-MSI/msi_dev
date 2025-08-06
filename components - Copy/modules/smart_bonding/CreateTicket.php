<?php
include 'GenerateToken.php';
include 'conn.php';
session_start();
$query = "SELECT idworklog,CustCallID,description,task_id FROM sa_worklog WHERE description LIKE 'REQUEST%'";
mysqli_select_db($conn, 'sa_worklog');
$dataworklog = mysqli_query($conn, $query);
if (!$dataworklog) {
    die('Could not get data from WORKLOG: ' . mysqli_error());
}
while ($getworklog = mysqli_fetch_assoc($dataworklog)) {
    $getdata = $getworklog['CustCallID'];
    $taskid = $getworklog['task_id'];
    date_default_timezone_set("Asia/Jakarta");
    $datenow = date("Y-m-d H:i:s");
    $sql = "SELECT CustCallID,asset_id,short_description,description,caller_id,caller_lastname,caller_firstname,caller_jobtitle,caller_mobile,caller_email,chd_id,chd_lastname,chd_firstname,chd_jobtitle,chd_pin,chd_mobile,chd_email,status FROM sa_master_data WHERE CustCallID LIKE '%$getdata%' AND status LIKE '%Belum%'";
    mysqli_select_db($conn, 'sa_master_data');
    $retval = mysqli_query($conn, $sql);
    if (!$retval) {
        die('Could not get data from Master Data: ' . mysqli_error());
    }
    $row = mysqli_fetch_assoc($retval);
    $custcall = isset($row['CustCallID']);
    if ($custcall == null) {
        '';
    } else {
        $asset_id = isset($row['asset_id']);
        if ($asset_id == null) {
            '';
        } else {
            $taskid = $getworklog['task_id'];
            $asset_id = $row['asset_id'];
            $custcall = $row['CustCallID'];
            $subject = $row['short_description'];
            $description = $row['description'];
            $callid = $row['caller_id'];
            $calllastname = $row['caller_lastname'];
            $callfirstname = $row['caller_firstname'];
            $calljobtitle = $row['caller_jobtitle'];
            $callmobile = $row['caller_mobile'];
            $callemail = $row['caller_email'];
            $chdid = $row['chd_id'];
            $chdlastname = $row['chd_lastname'];
            $chdfirstname = $row['chd_firstname'];
            $chdjobtitle = $row['chd_jobtitle'];
            $chdmobile = $row['chd_mobile'];
            $chdpin = $row['chd_pin'];
            $chdemail = $row['chd_email'];
            $mysql = "SELECT asset_id,serial_number,contract_number,warranty_end,manufacture FROM sa_asset WHERE asset_id LIKE '%$asset_id%'";
            mysqli_select_db($conn, 'sa_asset');
            $assetconn = mysqli_query($conn, $mysql);
            $list = mysqli_fetch_assoc($assetconn);
            $contract = isset($list['contract_number']);
            $manufacture = isset($list['manufacture']);
            if ($manufacture == null && $contract == null) {
                '';
            } else {
                $custcall = $row['CustCallID'];
                $asset_id = $row['asset_id'];
                $subject = $row['short_description'];
                $description = $row['description'];
                $callid = $row['caller_id'];
                $calllastname = $row['caller_lastname'];
                $callfirstname = $row['caller_firstname'];
                $calljobtitle = $row['caller_jobtitle'];
                $callmobile = $row['caller_mobile'];
                $callemail = $row['caller_email'];
                $chdid = $row['chd_id'];
                $chdlastname = $row['chd_lastname'];
                $chdfirstname = $row['chd_firstname'];
                $chdjobtitle = $row['chd_jobtitle'];
                $chdmobile = $row['chd_mobile'];
                $chdpin = $row['chd_pin'];
                $chdemail = $row['chd_email'];
                $contract = $list['contract_number'];
                $serial_number = $list['serial_number'];

                $data = array(
                    'Calls' => array(
                        'CustCallID' => "$custcall", 'ShortDescription' => "$subject", 'Description' => "$description", 'Remarks' => "$description", 'CustomerRequestedStartTime' => "$datenow", 'CustomerRequestedEndTime' => "2022-07-01 11:00:00", 'CustomerReasonCategory1' => "Cisco Technology Code", 'CustomerReasonCategory2' => "Cisco Sub-Technology Code", 'CustomerReasonCategory3' => "Cisco Problem Code",
                        'Caller' => array('LastName' => "$calllastname", 'FirstName' => "$callfirstname", 'Title' => "ACMEInc.", 'Tel' => "$callmobile", 'EMail' => "$callemail"),
                        'CCP' => array('LastName' => "Mustermann", 'FirstName' => "Max", 'Department' => "testccpdept", 'PIN' => "00u63wq7gzI8w8N1Y5d7", 'LocationCity' => "testccloccity", 'LocationStreet' => "testccpst", 'Room' => "tesccpromt", 'Tel' => "1-919-000-0000", 'EMail' => "putuwirama@mastersystem.co.id", 'Fax' => "test"),
                        'CHD' => array('LastName' => "$chdlastname", 'FirstName' => "$chdfirstname", 'PIN' => "$chdpin", 'Sign' => 'B2B', 'Tel' => "$chdmobile", 'EMail' => "$chdemail"),
                        'MainComp' => array('Location' => "123", 'Room' => "", 'SerNrProv' => "$serial_number", 'InvNr' => "$contract"),
                        'SubComp' => array('LocationName' => "Suite 112Gate 09", 'LocationCategory' => "Data Center", 'LocationZip' => "12345", 'LocationCity' => "Capital City", 'LocationStreet' => "12345 Main Street", 'LocationProvince' => "VA", 'LocationTel' => "3041", 'Room' => "test", 'OpSys' => "562")
                    ), 'Contracts' => array('ShortName' => "TAC"), 'ContractElements' => array('ShortName' => "SRM"),
                    'CallStates' => array('ShortName' => "New"), 'Priorities' => array('ShortName' => "Shadow"), 'Severities' => array('ShortName' => "3"), 'ExtTableValues' => array(
                        'Field1' => "deliveries accepted between 9AM and 3PM only", 'Field2' => "please ensure all seals are intact before opening", 'Field11' => "Jane Doe, Reception", 'Field14' => "2022-04-01 10:20:00", 'Field16' => "Tech Management", 'Field17' => "2nd Floor", 'Field18' => "No", 'Field19' => "Replace Part", 'Field20' => "Router", 'Field21' => "None", 'Field25' => "United States", 'Field26' => "1", 'Field31' => "2022-06-29 10:20:00",
                        'Field104' => "$datenow", 'Field105' => "", 'Field106' => "", 'Field107' => "", 'Field108' => "VendINC123", 'Field109' => "Vendor", 'Field110' => "Yes", 'Field111' => "Router Cat9K", 'Field112' => "Power", 'Field113' => "Simon", 'Field114' => "Smith", 'Field115' => "1-855-000-0000", 'Field116' => "simon.smith@vendor.com"
                    )
                );

                $postdata = json_encode($data);
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
		echo $postdata;

                $queryupdate = "UPDATE sa_master_data SET status='Sudah' WHERE CustCallID='$custcall'";
                if (mysqli_query($conn, $queryupdate)) {
                    echo "Update record successfully for Status";
                } else {
                    echo "Error: " . $queryupdate . "<br>" . mysqli_error($conn);
                }

                $queryinsert = "INSERT INTO sa_create_ticket (CustCallID,input_json,response_json,tanggal_create) VALUES ('$custcall','$postdata','$result','$datenow')";
                if (mysqli_query($conn, $queryinsert)) {
                    echo "Update record successfully for Status";
                } else {
                    echo "Error: " . $queryupdate . "<br>" . mysqli_error($conn);
                }
                }
            }
        }
    }
