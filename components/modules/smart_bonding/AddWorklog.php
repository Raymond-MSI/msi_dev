<?php
include 'conn.php';
include 'token_request.php';
function send_email($from, $to = '', $cc = 'webmaster@syaarar.com', $subject, $message)
{
    $headers = 'From: ' . $from . "\r\n" .
        'Reply-To: ' . $from . "\r\n" .
        'Cc: ' . $cc . "\r\n" .
        'Content-Type: text/html; charset=ISO-8859-1\r\n' .
        'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers);
}
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
    echo $response;
    $worklog = json_decode($response, true);
    $printasset = isset($worklog["response_status"]["status"]);

    $querySQL = "INSERT INTO sa_add_worklog (output_json,input_json,date) VALUES ('$response','$postdata','$tanggal')";
    if (mysqli_query($conn, $querySQL)) {
        echo "New record created successfully";
    } else {
        '';
    }

    $queryupdate = "UPDATE sa_response_update SET status='Sudah' WHERE CustCallID LIKE '%$data1%'";
    if (mysqli_query($conn, $queryupdate)) {
        echo "New record created successfully (Add Worklog)";
    } else {
        '';
    }

    if ($printasset == null) {
        '';
    } else {
        $printasset = $worklog["response_status"]["status"];
        if ($printasset == 'success') {
            $sql = "SELECT CustCallID,caller_lastname,caller_firstname,caller_email FROM sa_master_data WHERE CustCallID LIKE '%$data1%'";
            mysqli_select_db($conn, 'sa_response_update');
            $retval = mysqli_query($conn, $sql);
            if (!$retval) {
                die('Could not get data: ' . mysqli_error());
            }
            $row = mysqli_fetch_assoc($retval);
            $id = $row['CustCallID'];
            $caller_lastname = $row['caller_lastname'];
            $caller_firstname = $row['caller_firstname'];
            $caller_email = $row['caller_email'];
            $username_email = $caller_firstname . ' ' . $caller_lastname;
            $email = $caller_email;
            $from = $username_email . "<" . $caller_email . ">; ";
            $to =  $username_email . "<" . $caller_email . ">; ";
            $cc = $from;
            $bcc = "";
            $reply = $from;
            $subject = "[MSIZone] Response Worklog untuk tiket = " . $id . "pada Manage Engine";
            $msg = "<table width='100%'";
            $msg .= "<tr><td width='30%' rowspan='3'></td><td style='width:40%; border: thin solid #dadada; padding:20px'>";
            $msg .= "<img src='https://msizone.mastersystem.co.id/media/images/profiles/Logo_MSIZone_204x74.png'>";
            $msg .= "<br/>";
            $msg .= "<p>Dear " . $username_email . "</p>";
            $msg .= "<p> Terdapat balasan untuk tiket yang telah di open ke cisco dengan nomor id " . $id . "</p>";
            $msg .= "<p> Mohon untuk dapat di cek pada bagian Task Worklog tiket tersebut. </p>";
            $msg .= "<p>";
            $msg .= "<table style='width:100%;'>";
            $msg .= "<tr><td>CR Number</td><td>: </td><td>" . $_POST['cr_no'] . "</td></tr>";
            $msg .= "</table>";
            $msg .= "</p>";
            $msg .= "<p></p>";
            $msg .= "<p>Terimakasih,<br/>";
            $msg .= "</p>";
            $msg .= "</td><td width='30%' rowspan='3'>";
            $msg .= "<tr><td style='padding:20px; border:thin solid #dadada'><table width='100%'><tr><td><a href='https://msizone.mastersystem.co.id/index.php?mod=change_request'>MSIZone</a></td><td style='text-align:right'><a href='https://msizone.mastersystem.co.id/msiguide/'>MSIGuide</a></td></tr></table></td></tr>";
            $msg .= "<tr><td style='font-size:10px; padding-left:20px;border: thin solid #dadada'>Dikirim secara otomatis oleh sistem MSIZone.</td></tr>";
            $msg .= "</table>";

            $headers = "From: " . $from . "\r\n" .
                "Reply-To: " . $reply . "\r\n" .
                "Cc: " . $cc . "\r\n" .
                "Bcc: " . $bcc . "\r\n" .
                "MIME-Version: 1.0" . "\r\n" .
                "Content-Type: text/html; charset=UTF-8" . "\r\n" .
                "X-Mailer: PHP/" . phpversion();

            $ALERT = new Alert();
            if (!mail(
                $to,
                $subject,
                $msg,
                $headers
            )) {
                echo
                "Email gagal terkirim pada jam " . date("d M Y G:i:s");
            } else {
                echo
                "Email terkirim pada jam " . date("d M Y G:i:s");
            }
        } else {
            'tidak success';
        }
    }
}
