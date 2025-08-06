<?php
$_SESSION['Microservices_UserEmail'] = 'malik.aulia@mastersystem.co.id';
echo "==========";
echo "Execution module : fillsurvey";
echo "Started : " . date("d-M-Y G:i:s");
echo "==========<br/>";
$time_start = microtime(true);

global $DB1;
$mdlname = "SURVEY";
$DB1 = get_conn($mdlname);


require 'vendor/autoload.php';

/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */
function getClient()
{
    $client = new Google_Client();

    $client->setApplicationName('devapi');
    $client->setRedirectUri('oauth2callback.php');
    $client->setScopes([Google_Service_Sheets::SPREADSHEETS]);
    $client->setAuthConfig('Credentialssurveyfinal.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    // Load previously authorized token from a file, if it exists.
    $tokenPath = 'tokenSurveyFinal.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    // If there is no previous token or it's expired.
    if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
            $authCode = trim(fgets(STDIN));
            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            $client->setAccessToken($accessToken);

            // Check to see if there was an error.
            if (array_key_exists('error', $accessToken)) {
                throw new Exception(join(', ', $accessToken));
            }
        }

        // Save the token to a file.
        if (!file_exists(dirname($tokenPath))) {
            mkdir(dirname($tokenPath), 0700, true);
        }
        file_put_contents($tokenPath, json_encode($client->getAccessToken()));
    }
    return $client;
}

// Get the API client and construct the service object.
$client = getClient();

// Specify the spreadsheet ID for your Google Sheets document.
$spreadsheetId = '1Vkv9xxRujkS8-LO1QzF94Sr_x9vSfx7rsIi3Ad5pP3Q';
$sheetNames = ['Simple Survey', 'Full Survey']; // Replace with the actual sheet names

// Initialize Google Sheets API service
$service = new Google_Service_Sheets($client);

foreach ($sheetNames as $sheetName) {
    // Fetch all values from the specified sheet
    $range = $sheetName . '!A2:Z';
    $response = $service->spreadsheets_values->get($spreadsheetId, $range);

    $values = $response->getValues();

    if (empty($values)) {
        echo "No data found in sheet '$sheetName'.<br>";
    } else {
        echo "Data in sheet '$sheetName':<br>";
        // echo '<table border="1">';
        foreach ($values as $row) {

            if ($sheetName == 'Simple Survey') {
                // echo '<h2>Data from Sheet: ' . $sheetName . '</h2>';
                echo '<table border="1">';
                echo '<tr>';
                echo '<td>' . $row[0] . '</td>';
                echo '<td>' . $row[1] . '</td>';
                echo '<td>' . $row[2] . '</td>';
                echo '<td>' . $row[3] . '</td>';
                echo '<td>' . $row[4] . '</td>';
                echo '<td>' . $row[5] . '</td>';
                echo '<td>' . $row[6] . '</td>';
                echo '<td>' . $row[7] . '</td>';
                echo '<td>' . $row[8] . '</td>';
                echo '<td>' . $row[9] . '</td>';
                echo '<td>' . (!empty($row[10]) ? $row[10] : '') . '</td>';
                echo '</tr>';
                echo '</table>';

                // $koneksi = mysqli_connect("localhost", "root", "", "sa_survey");
                //             if (!$DB1) {
                //                 die("Connection failed: " . mysqli_connect_error());
                //             }
                //             $coba =  $DB1->get_sqlV2("SELECT * FROM sa_survey WHERE template_type = 'simple' AND status = 'active' AND survey_id = '$row[1]'");
                //             // $get_datasurvey = mysqli_fetch_assoc($coba);
                //             $get_datasurvey = $coba[0];

                //             // var_dump($get_datasurvey);

                //             $trxsurvey = $DB1->get_sqlV2("SELECT answer_id FROM sa_trx_survey ORDER BY answer_id DESC");
                //             // $get_datatrxsurvey = mysqli_fetch_assoc($trxsurvey);
                //             $get_datatrxsurvey = $trxsurvey[0];

                //             if ($get_datasurvey) {
                //                 $survey_id = $get_datasurvey['survey_id'];
                //                 $getlink = $get_datasurvey['survey_link'];
                //                 $get_kp = $get_datasurvey['project_code'];
                //                 $get_project_name = $get_datasurvey['project_name'];
                //                 $get_type = $get_datasurvey['template_type'];
                //                 $get_souvenir = $get_datasurvey['souvenir'];
                //                 $get_answerid = $get_datatrxsurvey['answer_id'] + 3;

                //                 $main_rating_simple = [["question_id" => "35", "value" => $row[8]]];
                //                 $main_essay_simple = [["question_id" => "26", "value" => $row[10]]];
                //                 $main_engineer_simple = [["name" => $row[9], "email" => $row[9]]];
                //                 $main_rating_simple_json = json_encode($main_rating_simple);
                //                 $main_essay_simple_json = json_encode($main_essay_simple);
                //                 $main_engineer_simple_json = json_encode($main_engineer_simple);
                //                 $souvenir_address = "";

                //                 // $insertQuery = "INSERT INTO sa_trx_survey (answer_id, survey_id, survey_link, project_code, project_title, type, main_rating, rating_average, main_essay, main_engineer,souvenir,souvenir_address,flag) 
                //                 //     VALUES ('$get_answerid', '$survey_id', '$getlink', '$get_kp', '$get_project_name', '$get_type', '$main_rating_simple_json', '$row[8]', '$main_essay_simple_json', '$main_engineer_simple_json', '$get_souvenir', '$souvenir_address', '0')";

                //                 $insert = sprintf(
                //                     "(`answer_id`, `survey_id`,`survey_link`, `project_code`, `project_title`,`type`, `main_rating`, `rating_average`, `main_essay`, `main_engineer`, `souvenir`, `souvenir_address`,`flag`) 
                // VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                //                     GetSQLValueString($get_answerid, "text"),
                //                     GetSQLValueString($survey_id, "text"),
                //                     GetSQLValueString($getlink, "text"),
                //                     GetSQLValueString($get_kp, "text"),
                //                     GetSQLValueString($get_project_name, "text"),
                //                     GetSQLValueString($get_type, "text"),
                //                     GetSQLValueString($main_rating_full_json, "text"),
                //                     GetSQLValueString($hasil, "text"),
                //                     GetSQLValueString($main_essay_json, "text"),
                //                     GetSQLValueString($main_engineer_json, "text"),
                //                     GetSQLValueString($get_souvenir, "text"),
                //                     GetSQLValueString($souvenir_address, "text"),
                //                     GetSQLValueString("0", "text")
                //                 );
                //                 $res = $DB1->insert_data('trx_survey', $insert);
                //                 $upsurvey = $DB1->get_res("UPDATE sa_survey set status = 'Submitted' WHERE survey_id = '$survey_id'");
                //                 $ALERT->savedata();

                //                 // if (mysqli_query($DB1, $insertQuery)) {
                //                 //     echo "Data berhasil masuk";
                //                 //     $updateQuery = "UPDATE sa_survey SET status = 'Submitted' WHERE survey_id = '$survey_id'";
                //                 //     if (mysqli_query($DB1, $updateQuery)) {
                //                 //         echo "berhasil update juga";
                //                 //     }
                //                 // } else {
                //                 //     echo "Error: " . mysqli_error($DB1);
                //                 // }
                //             }
            } elseif ($sheetName == 'Full Survey') {
                // echo '<h2>Data from Sheet: ' . $sheetName . '</h2>';
                echo '<table border="1">';
                echo '<tr>';
                echo '<td>' . $row[0] . '</td>';
                echo '<td>' . $row[1] . '</td>';
                echo '<td>' . $row[2] . '</td>';
                echo '<td>' . $row[3] . '</td>';
                echo '<td>' . $row[4] . '</td>';
                echo '<td>' . $row[5] . '</td>';
                echo '<td>' . $row[6] . '</td>';
                echo '<td>' . $row[7] . '</td>';
                echo '<td>' . $row[8] . '</td>';
                echo '<td>' . $row[9] . '</td>';
                echo '<td>' . $row[10] . '</td>';
                echo '<td>' . $row[11] . '</td>';
                echo '<td>' . $row[12] . '</td>';
                echo '<td>' . $row[13] . '</td>';
                echo '<td>' . $row[14] . '</td>';
                echo '<td>' . $row[15] . '</td>';
                if (isset($row[16])) {
                    echo '<td>' . $row[16] . '</td>';
                } else {
                    echo '<td></td>'; // Output an empty cell if index 16 doesn't exist
                }

                if (isset($row[17])) {
                    echo '<td>' . $row[17] . '</td>';
                } else {
                    echo '<td></td>'; // Output an empty cell if index 17 doesn't exist
                }

                echo '<td>' . (!empty($row[22]) ? $row[22] : '') . '</td>';
                echo '</tr>';
                echo '</table>';

                // $koneksi = mysqli_connect("localhost", "root", "", "sa_survey");
                //             if (!$DB1) {
                //                 die("Connection failed: " . mysqli_connect_error());
                //             }
                //             $coba =  $DB1->get_sqlV2("SELECT * FROM sa_survey WHERE template_type = 'full' AND status = 'active' AND survey_id = '$row[1]'");
                //             // $get_datasurvey = mysqli_fetch_assoc($coba);
                //             $get_datasurvey = $coba[0];

                //             $trxsurvey = $DB1->get_sqlV2("SELECT answer_id FROM sa_trx_survey ORDER BY answer_id DESC");
                //             // $get_datatrxsurvey = mysqli_fetch_assoc($trxsurvey);
                //             $get_datatrxsurvey = $trxsurvey[0];

                //             if ($get_datasurvey) {
                //                 $survey_id = $get_datasurvey['survey_id'];
                //                 $getlink = $get_datasurvey['survey_link'];
                //                 $get_kp = $get_datasurvey['project_code'];
                //                 $get_project_name = $get_datasurvey['project_name'];
                //                 $get_type = $get_datasurvey['template_type'];
                //                 $get_souvenir = $get_datasurvey['souvenir'];
                //                 $get_answerid = $get_datatrxsurvey['answer_id'] + 3;

                //                 $rating_average = $row[8] + $row[9] + $row[10] + $row[11] + $row[12] + $row[13] + $row[14] + $row[15];
                //                 $hasil = $rating_average / 8;
                //                 $main_rating_full = [
                //                     ["question_id" => "2", "value"  => $row[8]],
                //                     ["question_id" => "5", "value"  => $row[9]],
                //                     ["question_id" => "8", "value"  => $row[10]],
                //                     ["question_id" => "11", "value" => $row[11]],
                //                     ["question_id" => "14", "value" => $row[12]],
                //                     ["question_id" => "17", "value" => $row[13]],
                //                     ["question_id" => "20", "value" => $row[14]],
                //                     ["question_id" => "23", "value" => $row[15]]
                //                 ];
                //                 $main_essay = [
                //                     ["question_id" => "29", "value" => $row[17]]
                //                 ];
                //                 $main_engineer = [
                //                     ["name" => $row[16], "email" => $row[16]]
                //                 ];

                //                 $main_rating_full_json = json_encode($main_rating_full);
                //                 $main_essay_json = json_encode($main_essay);
                //                 $main_engineer_json = json_encode($main_engineer);
                //                 $souvenir_address = "";

                //                 // $insertQuery = "INSERT INTO sa_trx_survey (answer_id, survey_id, survey_link, project_code, project_title, type, main_rating, rating_average, main_essay, main_engineer,souvenir,souvenir_address,flag) 
                //                 //     VALUES ('$get_answerid', '$survey_id', '$getlink', '$get_kp', '$get_project_name', '$get_type', '$main_rating_full_json', '$hasil', '$main_essay_json', '$main_engineer_json', '$get_souvenir', '$souvenir_address', '0')";

                //                 $insert = sprintf(
                //                     "(`answer_id`, `survey_id`,`survey_link`, `project_code`, `project_title`,`type`, `main_rating`, `rating_average`, `main_essay`, `main_engineer`, `souvenir`, `souvenir_address`,`flag`) 
                // VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                //                     GetSQLValueString($get_answerid, "text"),
                //                     GetSQLValueString($survey_id, "text"),
                //                     GetSQLValueString($getlink, "text"),
                //                     GetSQLValueString($get_kp, "text"),
                //                     GetSQLValueString($get_project_name, "text"),
                //                     GetSQLValueString($get_type, "text"),
                //                     GetSQLValueString($main_rating_full_json, "text"),
                //                     GetSQLValueString($hasil, "text"),
                //                     GetSQLValueString($main_essay_json, "text"),
                //                     GetSQLValueString($main_engineer_json, "text"),
                //                     GetSQLValueString($get_souvenir, "text"),
                //                     GetSQLValueString($souvenir_address, "text"),
                //                     GetSQLValueString("0", "text")
                //                 );
                //                 $res = $DB1->insert_data('trx_survey', $insert);
                //                 $upsurvey = $DB1->get_res("UPDATE sa_survey set status = 'Submitted' WHERE survey_id = '$survey_id'");
                //                 $ALERT->savedata();

                // if (mysqli_query($koneksi, $insertQuery)) {
                //     echo "Data berhasil masuk";
                //     $updateQuery = "UPDATE sa_survey SET status = 'Submitted' WHERE survey_id = '$survey_id'";
                //     if (mysqli_query($koneksi, $updateQuery)) {
                //         echo "berhasil update juga";
                //     }
                // } else {
                //     echo "Error: " . mysqli_error($koneksi);
                // }
                // }
            }
        }
    }
}

$time_end = microtime(true);
$time = $time_end - $time_start;
echo "<br/>==========";
echo "Finished : " . date("d-M-Y G:i:s");
echo "The time used to run this module $time seconds";
echo "==========";
