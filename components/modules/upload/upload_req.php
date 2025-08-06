<?php
function bytesToSize1024($bytes, $precision = 2)
{
    $unit = array('B', 'KB', 'MB');
    return @round($bytes / pow(1024, ($i = floor(log($bytes, 1024)))), $precision) . ' ' . $unit[$i];
}

$sFileName = $_FILES['image_file']['name'];
$sFileType = $_FILES['image_file']['type'];
$sFileSize = bytesToSize1024($_FILES['image_file']['size'], 1);
$sFileSource = $_FILES['image_file']['tmp_name'];
$sFolderTarget = str_replace(" ", "_", $_COOKIE['FolderTarget']);
$sFileTarget = "../../../" . $sFolderTarget . '/' . $sFileName;
move_uploaded_file($sFileSource, $sFileTarget);
echo <<<EOF
<p>Your file: {$sFileName} has been successfully received.</p>
<p>Type: {$sFileType}</p>
<p>Size: {$sFileSize}</p>
EOF;
