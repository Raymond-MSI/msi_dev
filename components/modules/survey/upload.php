<?php
// if (isset($_POST["import"])) {
//     $mdlname = "SURVEY";
//     $DB1 = get_conn($mdlname);
//     $fileName = $_FILES["excel"]["name"];
//     $fileExtension = explode('.', $fileName);
//     $fileExtension = strtolower(end($fileExtension));

//     $newFileName = date("Y.m.d") . " - " . date("h.i.sa") . "." . $fileExtension;
//     $targetDirectory = "media/" . $newFileName;
//     move_uploaded_file($_FILES["excel"]["tmp_name"], $targetDirectory);

//     error_reporting(0);
//     ini_set('display_errors', 0);

//     require "excelReader/excel_reader2.php";
//     require "excelReader/SpreadsheetReader.php";

//     $reader = new SpreadsheetReader($targetDirectory);
//     foreach ($reader as $key => $row) {
//         $answer_id = $row[0];
//         $survey_id = $row[1];
//         $survey_link = $row[2];
//         $project_code = $row[3];
//         $project_title = $row[4];
//         $created_datetime = $row[5];
//         $type = $row[6];
//         $main_rating = $row[7];
//         $rating_average = $row[8];
//         $main_essay = $row[9];
//         $main_engineer = $row[10];
//         $souvenir = $row[11];
//         $souvenir_address = $row[12];
//         $flag = $row[13];

//         if ($status == 0) {
//             mysqli_query($DB1, "INSERT into sa_trx_survey values('',
// 		'$survey_id',
// 		'$survey_link',
// 		'$project_code',
// 		'$project_title',
// 		'$created_datetime',
// 		'$type',
// 		'$main_rating',
// 		'$rating_average',
// 		'$main_essay',
// 		'$main_engineer',
// 		'$souvenir',
// 		'$souvenir_address',
// 		'$flag')");
//         }
//     }

//     echo
//     "<script>
//             alert('berhasil');
//             document.location.href = '';
//             </script>";
// }


if (isset($_POST["import"])) {
    $mdlname = "SURVEY";
    $DB1 = get_conn($mdlname);
    $fileName = $_FILES["excel"]["name"];
    $fileExtension = explode('.', $fileName);
    $fileExtension = strtolower(end($fileExtension));

    $newFileName = date("Y.m.d") . " - " . date("h.i.sa") . "." . $fileExtension;
    $targetDirectory = "media/" . $newFileName;
    move_uploaded_file($_FILES["excel"]["tmp_name"], $targetDirectory);

    error_reporting(0);
    ini_set('display_errors', 0);

    require "excelReader/excel_reader2.php";
    require "excelReader/SpreadsheetReader.php";

    $reader = new SpreadsheetReader($targetDirectory);
    foreach ($reader as $key => $row) {
        $answer_id = $row[0];
        $survey_id = $row[1];
        $survey_link = $row[2];
        $project_code = $row[3];
        $project_title = $row[4];
        $created_datetime = $row[5];
        $type = $row[6];
        $main_rating = $row[7];
        $rating_average = $row[8];
        $main_essay = $row[9];
        $main_engineer = $row[10];
        $souvenir = $row[11];
        $souvenir_address = $row[12];
        $flag = $row[13];

        if ($status == 0) {
            mysqli_query($DB1, "INSERT into sa_trx_survey values('',
		'$survey_id',
		'$survey_link',
		'$project_code',
		'$project_title',
		'$created_datetime',
		'$type',
		'$main_rating',
		'$rating_average',
		'$main_essay',
		'$main_engineer',
		'$souvenir',
		'$souvenir_address',
		'$flag')");
        }
    }

    echo
    "<script>
            alert('berhasil');
            document.location.href = '';
            </script>";
}
